/**********************************************************************
*  Copyright (C) 2014 Rod Roark <rod@sunsetsystems.com>
*  All Rights Reserved Worldwide
*
*  This utility is intended to interface with an Epson TM-U220AFII
*  Argentina ticket printer and other compatible printers.
*
*  If run with a single filename argument, it will cause the printer to
*  print a document using the contents of that file.  That will be the
*  normal case when invoked from a web browser.
*
*  Otherwise, running the program will allow it to be used for various
*  printer maintenance actions.
*
*  There are no resource files; this module is the entire application.
*
*  Compilation with MinGW-W64 may be done like this for 32-bit targets:
*  i686-w64-mingw32-gcc fiscalprinter.c -mwindows -o fiscalprinter.exe
*  Or for 64-bit targets:
*  x86_64-w64-mingw32-gcc fiscalprinter.c -mwindows -o fiscalprinter.exe
**********************************************************************/
#include <windows.h>
#include <string.h>
#include <stdio.h>

#define IDC_MAIN_EDIT	 101
#define PRINT_BUF_LEN 2048
#define COM_BUF_LEN   2048

// Each command line to send to the fiscal printer is built here.
char pPrintBuf[PRINT_BUF_LEN] = "Copyright (C) 2014 Rod Roark <rod@sunsetsystems.com>";

// Input from the serial port goes here.
char pComBuf[COM_BUF_LEN] = "";

// A place to construct error messages.
char pError[256] = "";

// This is the handle of the application's main window.
HWND hWndMain = NULL;

const char * g_szClassName = "fiscalPrinterClass";

const char * pComPort = "COM2";

DWORD myError(char * str) {
  MessageBox(hWndMain, str, "Error", MB_OK | MB_ICONERROR);
  return -1;
}

// Increment line sequence number which is maintained in the registry, and return its value.
//
DWORD getNextSeq() {
  LONG  lResult;
  HKEY  hKey;
  DWORD dwValue;
  DWORD dwTmp;

  // Open our registry key, creating it if necessary.
  lResult = RegCreateKeyEx(HKEY_LOCAL_MACHINE, TEXT("SOFTWARE\\IPPF"), 0, NULL, 0,
    KEY_READ | KEY_WRITE, NULL, &hKey, NULL);
  if (lResult != ERROR_SUCCESS) {
    return myError("Could not open registry key.");
  }

  // Get the last sequence number, or assign it as 0 if not there yet.
  dwTmp = sizeof(DWORD);
  lResult = RegQueryValueEx(hKey, TEXT("TicketPrinterSeq"), NULL, &dwTmp, (BYTE *) &dwValue, &dwTmp);
  if (lResult == ERROR_FILE_NOT_FOUND) {
    dwValue = 0;
  }
  else if (lResult != ERROR_SUCCESS) {
    return myError("Error reading TicketPrinterSeq from registry.");
  }

  // Increment and normalize the value to what the printer will accept.
  ++dwValue;
  if (dwValue < 0x20 || dwValue > 0x7f) dwValue = 0x20;

  // Save the updated value to the registry.
  lResult = RegSetValueEx(hKey, TEXT("TicketPrinterSeq"), 0, REG_DWORD, (BYTE *) &dwValue, sizeof(DWORD));
  if (lResult != ERROR_SUCCESS) {
    return myError("Error writing TicketPrinterSeq to registry.");
  }

  RegCloseKey(hKey);

  return dwValue;
}

// Convert an input string to a string formatted for the fiscal printer.
//
char * makePrintString(char * s) {
  int    iCommand;
  char * pOut = pPrintBuf;
  char * p1 = s;

  // Extract the command byte from the first token which might be like "42" or "0x2a".
  *pOut = 0;
  char * pDelim = strchr(p1, '|');
  int iLen = pDelim ? (pDelim - p1) : strlen(p1);
  if (sscanf(p1, "%i", &iCommand) != 1) return NULL;

  // Set STX, sequence byte, command byte.
  *pOut++ = 0x02;
  *pOut++ = (char) getNextSeq();
  *pOut++ = (char) iCommand;

  // Each subsequent token is copied verbatim with a 0x1c prefix.
  while (iLen < strlen(p1)) {
    p1 += iLen + 1;
    pDelim = strchr(p1, '|');
    iLen = pDelim ? (pDelim - p1) : strlen(p1);
    if (pOut + iLen + 6 - pPrintBuf > PRINT_BUF_LEN) return NULL;
    *pOut++ = 0x1c;
    strncpy(pOut, p1, iLen);
    pOut += iLen;
  }

  // ETX byte denotes end of arguments.
  *pOut++ = 0x03;

  // Then the checksum as 4 hexadecimal characters, and a terminating null byte.
  unsigned int uSum = 0;
  for (p1 = pPrintBuf; p1 < pOut; ++p1) uSum += *p1;
  sprintf(pOut, "%04X", uSum & 0xffff);

  return pPrintBuf;
}

HANDLE commOpen() {
  HANDLE hSerial = CreateFile(pComPort, GENERIC_READ | GENERIC_WRITE, 0, 0, OPEN_EXISTING, 0, 0);
  if (hSerial == INVALID_HANDLE_VALUE) {
    myError("Error opening serial port.");
    return NULL;
  }
  DCB dcbSerialParams = {0};
  dcbSerialParams.DCBlength = sizeof(dcbSerialParams);
  if (!GetCommState(hSerial, &dcbSerialParams)) {
    myError("Error getting serial port parameters.");
    return NULL;
  }
  dcbSerialParams.BaudRate = 9600;
  dcbSerialParams.ByteSize = 8;
  dcbSerialParams.StopBits = ONESTOPBIT;
  dcbSerialParams.Parity   = NOPARITY;
  if(!SetCommState(hSerial, &dcbSerialParams)) {
    myError("Error setting serial port parameters.");
    return NULL;
  }
  COMMTIMEOUTS timeouts = {0};
  timeouts.ReadIntervalTimeout         = 100;
  timeouts.ReadTotalTimeoutMultiplier  =   2;
  timeouts.ReadTotalTimeoutConstant    = 100;
  timeouts.WriteTotalTimeoutMultiplier =   2;
  timeouts.WriteTotalTimeoutConstant   = 100;
  if(!SetCommTimeouts(hSerial, &timeouts)) {
    myError("Error setting serial port timeouts.");
    return NULL;
  }
  return hSerial;
}

// Write the string at pPrintBuf to the serial port and get the printer's response.
//
char * commRW(HANDLE hSerial) {
  int writeRetry = 3;
  DWORD dwBytes = 0;
  while (--writeRetry > 0) {
    if(!WriteFile(hSerial, pPrintBuf, strlen(pPrintBuf), &dwBytes, NULL)) {
      sprintf(pError, "Error %d writing to serial port.", GetLastError());
      myError(pError);
      return NULL;
    }
    int readRetry = 10;
    while (--readRetry > 0) {
      if(!ReadFile(hSerial, pComBuf, 1, &dwBytes, NULL)) continue;
      if (pComBuf[0] == 0x12 || pComBuf[0] == 0x14) {
        readRetry = 20;
        continue;
      }
      if (pComBuf[0] != 0x15 && pComBuf[0] != 0x02) {
        sprintf(pError, "Expected 0x02 from serial port, got 0x%02X, discarded.", (unsigned int) pComBuf[0]);
        myError(pError);
        readRetry = 10;
        continue;
      }
    }
    if (readRetry <= 0) {
      sprintf(pError, "Error %d reading from serial port.", GetLastError());
      myError(pError);
      return NULL;
    }
    if (pComBuf[0] == 0x15) {
      myError("NAK from printer, re-sending request.");
      continue;
    }
    readRetry = 10;
    char * pIn = pComBuf + 1;
    while (--readRetry > 0) {
      if (pIn + 6 - pComBuf > COM_BUF_LEN) {
        myError("Serial port input buffer overflow.");
        return NULL;
      }
      if(!ReadFile(hSerial, pIn, 1, &dwBytes, NULL)) continue;
      if (*pIn++ == 0x03) break;
      readRetry = 10;
    }
    readRetry = 10;
    ReadFile(hSerial, pIn, 4, &dwBytes, NULL);
    pIn[4] = 0;

    // TBD: Verify checksum here. If failing, send NAK and continue. If OK, break.
    break;

  }
  if (writeRetry <= 0) {
    myError("Giving up writing to printer.");
    return NULL;
  }
  return pComBuf;
}

// The Window Procedure
//
LRESULT CALLBACK WndProc(HWND hwnd, UINT msg, WPARAM wParam, LPARAM lParam) {
  switch(msg) {

    case WM_CREATE: {
      HFONT hfDefault;
      HWND hEdit;
      hEdit = CreateWindowEx(WS_EX_CLIENTEDGE, "EDIT", "",
        WS_CHILD | WS_VISIBLE | WS_VSCROLL | WS_HSCROLL | ES_MULTILINE | ES_AUTOVSCROLL | ES_AUTOHSCROLL, 
        0, 0, 100, 100, hwnd, (HMENU)IDC_MAIN_EDIT, GetModuleHandle(NULL), NULL);
      if (hEdit == NULL) {
        myError("Could not create edit box.");
      }
      hfDefault = (HFONT) GetStockObject(DEFAULT_GUI_FONT);
      SendMessage(hEdit, WM_SETFONT, (WPARAM) hfDefault, MAKELPARAM(FALSE, 0));
    }
    break;

    case WM_SIZE: {
      HWND hEdit;
      RECT rcClient;
      GetClientRect(hwnd, &rcClient);
      hEdit = GetDlgItem(hwnd, IDC_MAIN_EDIT);
      SetWindowPos(hEdit, NULL, 0, 0, rcClient.right, rcClient.bottom, SWP_NOZORDER);
    }
    break;

    case WM_CLOSE: {
      DestroyWindow(hwnd);
    }
    break;

    case WM_DESTROY: {
      PostQuitMessage(0);
    }
    break;

    default: {
      return DefWindowProc(hwnd, msg, wParam, lParam);
    }

  }
  return 0;
}

// Application Entry Point
//
int WINAPI WinMain(HINSTANCE hInstance, HINSTANCE hPrevInstance, LPSTR lpCmdLine, int nCmdShow) {
  WNDCLASSEX wc;
  MSG Msg;

  // Registering the Window Class
  wc.cbSize        = sizeof(WNDCLASSEX);
  wc.style         = 0;
  wc.lpfnWndProc   = WndProc;
  wc.cbClsExtra    = 0;
  wc.cbWndExtra    = 0;
  wc.hInstance     = hInstance;
  wc.hIcon         = LoadIcon(NULL, IDI_APPLICATION);
  wc.hCursor       = LoadCursor(NULL, IDC_ARROW);
  wc.hbrBackground = (HBRUSH) (COLOR_WINDOW + 1);
  wc.lpszMenuName  = NULL;
  wc.lpszClassName = g_szClassName;
  wc.hIconSm       = LoadIcon(NULL, IDI_APPLICATION);
  if (!RegisterClassEx(&wc)) {
    myError("Window Registration Failed!");
    return 0;
  }

  // MessageBox(NULL, lpCmdLine, "Command Line Arguments", MB_ICONINFORMATION | MB_OK); // testing

  // Creating the Window
  hWndMain = CreateWindowEx(
    WS_EX_CLIENTEDGE,
    g_szClassName,
    "IPPF Fiscal Printer Utility",
    WS_OVERLAPPEDWINDOW,
    CW_USEDEFAULT, CW_USEDEFAULT, 480, 320,
    NULL, NULL, hInstance, NULL);
  if (hWndMain == NULL) {
    myError("Window Creation Failed!");
    return 0;
  }

  ShowWindow(hWndMain, nCmdShow);
  UpdateWindow(hWndMain);

  //////////////////////////// Testing ////////////////////////////////
  char inbytes[] = "0x2a|N";
  makePrintString(inbytes);
  FILE * hFile = fopen("e:\\fptest.bin", "w");
  fprintf(hFile, "> %s\n", pPrintBuf);
  HANDLE hSerial = commOpen();
  if (hSerial && commRW(hSerial)) fprintf(hFile, "< %s\n", pComBuf);
  CloseHandle(hSerial);
  fclose(hFile);
  /////////////////////////////////////////////////////////////////////

  // The Message Loop
  while (GetMessage(&Msg, NULL, 0, 0) > 0) {
    TranslateMessage(&Msg);
    DispatchMessage(&Msg);
  }
  return Msg.wParam;
}
