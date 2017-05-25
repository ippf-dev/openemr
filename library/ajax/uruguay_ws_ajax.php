<?php
$ignoreAuth = true; // only for direct testing with no login

include_once("../../interface/globals.php");
include_once($GLOBALS['srcdir'] . "/sql.inc");

class BuscarParameters {
  function __construct($clientid) {
    $this->numeroDocumento = $clientid;
    $this->tipoDocumento = "CI";
    $this->token_ = "test.asse:ASD23D874JADQ37RUFOASDPOAJWD9234";
  }
}

function translateDate($date) {
  $date = preg_replace('/\D/', '', $date);
  if (strlen($date) < 8) return '';
  return substr($date, 4, 4) . '-' . substr($date, 2, 2) . '-' . substr($date, 0, 2);
}

function translateSex($sex) {
  if ($sex == 'F') return 'Female';
  if ($sex == 'M') return 'Male';
  return $sex;
}

function translateState($state) {
  $row = sqlQuery("SELECT option_id FROM list_options WHERE list_id = 'state' AND option_value = ?",
    array($state));
  if (empty($row['option_id'])) return '';
  return $row['option_id'];
}

$userdata = array();

if (empty($_GET['clientid'])) {
  $userdata['error'] = xl('There is no National ID');
}
else if (false) {
  // Set above to true for testing with no SOAP connection.
  $userdata['fname' ] = 'Maria';
  $userdata['lname' ] = 'Gonzalez';
  $userdata['DOB'   ] = '2000-05-25';
  $userdata['sex'   ] = 'Female';
  $userdata['state' ] = 'j';
  $userdata['street'] = 'Calle';
}
else if (!class_exists('SoapClient')) {
  $userdata['error'] = xl('PHP SOAP extension is required but not loaded');
}
else {
  // This host is at 10.202.200.31 but the hostname is required in the URL.
  $client = new SoapClient("http://web03.t1lib.testing:8081/padron/services/persona/consulta?wsdl");

  // This was for initial connection testing:
  // $userdata['functions'] = $client->__getFunctions();
  // $userdata['types'    ] = $client->__getTypes();

  $response = $client->buscarPersonaPorTipoYNumeroDocumento(array(
    'buscarPersonaPorTipoYNumeroDocumentoInParameters' => new BuscarParameters($_GET['clientid'])
  ));

  if (empty($response->return->personas) || $response->return->personas->estado == 'BAJ') {
    $userdata['error'] = xl('No match');
  }
  else {
    $personas = $response->return->personas;
    $userdata['fname' ] = $personas->primerNombre;
    $userdata['lname' ] = $personas->segundoNombre;
    $userdata['DOB'   ] = translateDate($personas->fechaNacimiento);
    $userdata['sex'   ] = translateSex($personas->sexo);
    $userdata['state' ] = translateState($personas->departamento);
    $userdata['street'] = $personas->calle;
  }
}

echo json_encode($userdata);

// PHP end tag omitted to avoid output of any trailing white space.
