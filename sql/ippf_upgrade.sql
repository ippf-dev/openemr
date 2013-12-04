#IfNotRow2D list_options list_id lbfnames option_id LBFgcac
DELETE FROM list_options WHERE list_id = 'lbfnames';
INSERT INTO list_options ( list_id, option_id, title, seq ) VALUES ('lbfnames','LBFgcac','IPPF GCAC',1);
INSERT INTO list_options ( list_id, option_id, title, seq ) VALUES ('lbfnames','LBFsrh' ,'IPPF SRH' ,2);
#EndIf

#IfNotRow2D list_options list_id LBFsrh option_id uro_disease
DELETE FROM layout_options WHERE form_id = 'LBFsrh';
INSERT INTO layout_options VALUES ('LBFsrh','usertext15' ,'1Gynecology'                ,'Menstrual History'             , 1,22,1, 0, 0,'genmenhist'  ,1,3,'','H','',0);
INSERT INTO layout_options VALUES ('LBFsrh','men_hist'   ,'1Gynecology'                ,'Recent Menstrual History'      , 2,21,1, 2, 0,'menhist'     ,1,3,'','','Recent Menstrual History',0);
INSERT INTO layout_options VALUES ('LBFsrh','men_compl'  ,'1Gynecology'                ,'Menstrual Complications'       , 3,21,1, 2, 0,'men_compl'   ,1,3,'','','Menstrual Complications',0);
INSERT INTO layout_options VALUES ('LBFsrh','pap_hist'   ,'1Gynecology'                ,'Pap Smear Recent History'      , 4,22,1, 0, 0,'pap_hist'    ,1,3,'','','Pap Smear Recent History',0);
INSERT INTO layout_options VALUES ('LBFsrh','gyn_exams'  ,'1Gynecology'                ,'Gynecological Tests'           , 5,23,1, 0, 0,'gyn_exams'   ,1,1,'','','Gynecological test results',0);
INSERT INTO layout_options VALUES ('LBFsrh','pr_status'  ,'2Obstetrics'                ,'Pregnancy Status Confirmed'    , 1, 1,1, 0, 0,'pr_status'   ,1,3,'','','Pregnancy Status Confirmed',0);
INSERT INTO layout_options VALUES ('LBFsrh','gest_age_by','2Obstetrics'                ,'Gestational Age Confirmed By'  , 2, 1,1, 0, 0,'gest_age_by' ,1,3,'','','Gestational Age Confirmed By',0);
INSERT INTO layout_options VALUES ('LBFsrh','usertext12' ,'2Obstetrics'                ,'Blood Group'                   , 3, 1,1, 0, 0,'bloodgroup'  ,1,3,'','H','',0);
INSERT INTO layout_options VALUES ('LBFsrh','usertext13' ,'2Obstetrics'                ,'RH Factor'                     , 4, 1,1, 0, 0,'rh_factor'   ,1,3,'','H','',0);
INSERT INTO layout_options VALUES ('LBFsrh','obs_exams'  ,'2Obstetrics'                ,'Obstetric Tests'               , 5,23,1, 0, 0,'obs_exams'   ,1,1,'','','Obstetric test results',0);
INSERT INTO layout_options VALUES ('LBFsrh','usertext16' ,'2Obstetrics'                ,'Obstetric History'             , 6,22,1, 0, 0,'genobshist'  ,1,1,'','H','',0);
INSERT INTO layout_options VALUES ('LBFsrh','pr_outcome' ,'2Obstetrics'                ,'Outcome of Last Pregnancy'     , 7,21,1, 2, 0,'pr_outcome'  ,1,3,'','','Outcome of Last Pregnancy',0);
INSERT INTO layout_options VALUES ('LBFsrh','pr_compl'   ,'2Obstetrics'                ,'Pregnancy Complications'       , 8,21,1, 2, 0,'pr_compl'    ,1,3,'','','Pregnancy Complications',0);
INSERT INTO layout_options VALUES ('LBFsrh','usertext17' ,'3Basic RH (female only)'    ,'Abortion Basic History'        , 1,22,1, 0, 0,'genabohist'  ,1,1,'','H','',0);
INSERT INTO layout_options VALUES ('LBFsrh','abo_exams'  ,'3Basic RH (female only)'    ,'Abortion Tests'                , 2,23,1, 0, 0,'abo_exams'   ,1,1,'','','Abortion test results',0);
INSERT INTO layout_options VALUES ('LBFsrh','usertext18' ,'4Basic RH (female and male)','HIV/AIDS Basic History'        , 1,21,1, 0, 0,'genhivhist'  ,1,1,'','H','',0);
INSERT INTO layout_options VALUES ('LBFsrh','hiv_exams'  ,'4Basic RH (female and male)','HIV/AIDS Tests'                , 2,23,1, 0, 0,'hiv_exams'   ,1,1,'','','HIV/AIDS test results',0);
INSERT INTO layout_options VALUES ('LBFsrh','usertext19' ,'4Basic RH (female and male)','ITS/ITR Basic History'         , 3,21,1, 0, 0,'genitshist'  ,1,1,'','H','',0);
INSERT INTO layout_options VALUES ('LBFsrh','its_exams'  ,'4Basic RH (female and male)','ITS/ITR Tests'                 , 4,23,1, 0, 0,'its_exams'   ,1,1,'','','ITS/ITR test results',0);
INSERT INTO layout_options VALUES ('LBFsrh','usertext20' ,'4Basic RH (female and male)','Fertility Basic History'       , 5,21,1, 0, 0,'genferhist'  ,1,1,'','H','',0);
INSERT INTO layout_options VALUES ('LBFsrh','fer_exams'  ,'4Basic RH (female and male)','Fertility Tests'               , 6,23,1, 0, 0,'fer_exams'   ,1,1,'','','Infertility/subfertility test results',0);
INSERT INTO layout_options VALUES ('LBFsrh','fer_causes' ,'4Basic RH (female and male)','Causes of Infertility'         , 7,21,1, 2, 0,'fer_causes'  ,1,3,'','','Causes of Infertility',0);
INSERT INTO layout_options VALUES ('LBFsrh','fer_treat'  ,'4Basic RH (female and male)','Infertility Treatment'         , 8,21,1, 2, 0,'fer_treat'   ,1,3,'','','Infertility Treatment',0);
INSERT INTO layout_options VALUES ('LBFsrh','usertext21' ,'4Basic RH (female and male)','Urology Basic History'         , 9,21,1, 0, 0,'genurohist'  ,1,1,'','H','',0);
INSERT INTO layout_options VALUES ('LBFsrh','uro_exams'  ,'4Basic RH (female and male)','Urology Tests'                 ,10,23,1, 0, 0,'uro_exams'   ,1,1,'','','Urology test results',0);
INSERT INTO layout_options VALUES ('LBFsrh','uro_disease','4Basic RH (female and male)','Male Genitourinary diseases'   ,11,21,1, 2, 0,'uro_disease' ,1,3,'','','Male Genitourinary diseases',0);
#EndIf

# DELETE FROM layout_options WHERE form_id = 'GCA';
# INSERT INTO layout_options VALUES ('GCA','reason'       ,'2Counseling'  ,'Reason for Termination'          , 1,21,1, 0, 0,'abreasons'   ,1,3,'','' ,'Reasons for Termination of Pregnancy');
# INSERT INTO layout_options VALUES ('GCA','exp_p_i'      ,'2Counseling'  ,'Explanation of Procedures/Issues', 2,21,1, 2, 0,'exp_p_i'     ,1,3,'','' ,'Explanation of Procedures and Issues');
# INSERT INTO layout_options VALUES ('GCA','exp_pop'      ,'2Counseling'  ,'Explanation of Pregnancy Options', 3,21,1, 2, 0,'exp_pop'     ,1,3,'','' ,'Explanation of Pregnancy Options');
# INSERT INTO layout_options VALUES ('GCA','ab_contraind' ,'2Counseling'  ,'Contraindications'               , 4,21,1, 2, 0,'ab_contraind',1,3,'','' ,'Contraindications');
# INSERT INTO layout_options VALUES ('GCA','screening'    ,'2Counseling'  ,'Screening for SRHR Concerns'     , 5,21,1, 2, 0,'screening'   ,1,3,'','' ,'Screening for SRHR Concerns');
# INSERT INTO layout_options VALUES ('GCA','in_ab_proc'   ,'3Admission'   ,'Induced Abortion Procedure'      , 2, 1,1, 0, 0,'in_ab_proc'  ,1,3,'','' ,'Abortion Procedure Accepted or Performed');
# INSERT INTO layout_options VALUES ('GCA','ab_types'     ,'3Admission'   ,'Abortion Types'                  , 3,21,1, 2, 0,'ab_types'    ,1,3,'','' ,'Abortion Types');
# INSERT INTO layout_options VALUES ('GCA','pr_status'    ,'4Preparatory' ,'Pregnancy Status Confirmed'      , 1, 1,1, 0, 0,'pr_status'   ,1,3,'','' ,'Pregnancy Status Confirmed');
# INSERT INTO layout_options VALUES ('GCA','gest_age_by'  ,'4Preparatory' ,'Gestational Age Confirmed By'    , 2, 1,1, 0, 0,'gest_age_by' ,1,3,'','' ,'Gestational Age Confirmed By');
# INSERT INTO layout_options VALUES ('GCA','usertext12'   ,'4Preparatory' ,'Blood Group'                     , 3, 1,1, 0, 0,'bloodgroup'  ,1,3,'','H','');
# INSERT INTO layout_options VALUES ('GCA','usertext13'   ,'4Preparatory' ,'RH Factor'                       , 4, 1,1, 0, 0,'rh_factor'   ,1,3,'','H','');
# INSERT INTO layout_options VALUES ('GCA','prep_procs'   ,'4Preparatory' ,'Preparation Procedures'          , 6,21,1, 0, 0,'prep_procs'  ,1,3,'','' ,'Preparation Procedures');
# INSERT INTO layout_options VALUES ('GCA','pre_op'       ,'5Intervention','Pre-Surgery Procedures'          , 1,21,1, 2, 0,'pre_op'      ,1,3,'','' ,'Pre-Surgery Procedures');
# INSERT INTO layout_options VALUES ('GCA','anesthesia'   ,'5Intervention','Anesthesia'                      , 2, 1,1, 0, 0,'anesthesia'  ,1,3,'','' ,'Type of Anesthesia Used');
# INSERT INTO layout_options VALUES ('GCA','side_eff'     ,'5Intervention','Immediate Side Effects'          , 3,21,1, 2, 0,'side_eff'    ,1,3,'','' ,'Immediate Side Effects (observed at intervention');
# INSERT INTO layout_options VALUES ('GCA','post_op'      ,'5Intervention','Post-Surgery Procedures'         , 5,21,1, 2, 0,'post_op'     ,1,3,'','' ,'Post-Surgery Procedures');
# INSERT INTO layout_options VALUES ('GCA','qc_ind'       ,'6Followup'    ,'Quality of Care Indicators'      , 1,21,1, 0, 0,'qc_ind'      ,1,3,'','' ,'Quality of Care Indicators');

#IfNotRow list_options list_id occupations
INSERT INTO list_options ( list_id, option_id, title, seq ) VALUES ('occupations','oth','Other', 1);
DELETE FROM list_options WHERE list_id = 'lists' AND option_id = 'occupations';
INSERT INTO list_options ( list_id, option_id, title, seq ) VALUES ('lists','occupations','Occupations',61);
#EndIf

UPDATE layout_options SET data_type = 26, list_id = 'occupations'  WHERE form_id = 'DEM' AND field_id = 'occupation';
UPDATE layout_options SET data_type = 26, title = 'Religion'       WHERE form_id = 'DEM' AND field_id = 'userlist5';
UPDATE layout_options SET data_type = 26, title = 'Monthly Income' WHERE form_id = 'DEM' AND field_id = 'userlist3';
UPDATE layout_options SET data_type = 26 WHERE form_id = 'DEM' AND field_id = 'ethnoracial';
UPDATE layout_options SET data_type = 26 WHERE form_id = 'DEM' AND field_id = 'language';
UPDATE layout_options SET data_type = 26 WHERE form_id = 'DEM' AND field_id = 'status';
# UPDATE layout_options SET uor = 0 WHERE form_id = 'DEM' AND field_id = 'providerID';

UPDATE layout_options SET seq = 3 WHERE form_id = 'REF' AND field_id = 'refer_external' AND seq = 5;
UPDATE layout_options SET seq = 4 WHERE form_id = 'REF' AND field_id = 'refer_to'       AND seq = 3;
UPDATE layout_options SET seq = 5 WHERE form_id = 'REF' AND field_id = 'body'           AND seq = 4;

# UPDATE layout_options AS a, list_options AS i SET a.group_name = '1Basic Data', a.title = 'Transgender', a.seq = 13, a.data_type = 26, a.uor = 1, a.description = 'Transgender', i.title = 'Transgender' WHERE a.form_id = 'DEM' AND a.field_id = 'userlist6' AND a.uor = 0 AND i.list_id = 'lists' AND i.option_id = 'userlist6';
UPDATE layout_options AS a, list_options AS i SET a.title = 'Transgender', a.data_type = 26, a.description = 'Transgender', i.title = 'Transgender' WHERE a.form_id = 'DEM' AND a.field_id = 'userlist6' AND a.uor = 0 AND i.list_id = 'lists' AND i.option_id = 'userlist6';

UPDATE list_options SET title = 'Retention of Product' WHERE title = 'Incomplete Abortion or Retention of Prod';

UPDATE list_options SET title = 'Surgical - MVA/EVA' WHERE list_id = 'in_ab_proc' AND title = 'Surgical - MVA';

DELETE FROM list_options where list_id = 'contrameth' AND option_id = 'abs';
DELETE FROM list_options where list_id = 'contrameth' AND option_id = 'eva';
DELETE FROM list_options where list_id = 'contrameth' AND option_id = 'oth';
DELETE FROM list_options where list_id = 'contrameth' AND option_id = 'wd';

UPDATE list_options SET mapping = ':2522231'   WHERE list_id = 'in_ab_proc' AND option_id = 's_dnc';
UPDATE list_options SET mapping = ':2522232'   WHERE list_id = 'in_ab_proc' AND option_id = 's_dne';
UPDATE list_options SET mapping = ':2522233'   WHERE list_id = 'in_ab_proc' AND option_id = 's_mva';
UPDATE list_options SET mapping = ':2522239'   WHERE list_id = 'in_ab_proc' AND option_id = 's_oth';
UPDATE list_options SET mapping = ':2522242'   WHERE list_id = 'in_ab_proc' AND option_id = 'm_mis';
UPDATE list_options SET mapping = ':2522241'   WHERE list_id = 'in_ab_proc' AND option_id = 'm_mm';
UPDATE list_options SET mapping = ':2522249'   WHERE list_id = 'in_ab_proc' AND option_id = 'm_oth';

UPDATE list_options SET mapping = ':11214'     WHERE list_id = 'contrameth' AND option_id = 'con';
UPDATE list_options SET mapping = ':11215'     WHERE list_id = 'contrameth' AND option_id = 'dia';
UPDATE list_options SET mapping = ':14521'     WHERE list_id = 'contrameth' AND option_id = 'ec';
UPDATE list_options SET mapping = ':13119'     WHERE list_id = 'contrameth' AND option_id = 'fab';
UPDATE list_options SET mapping = ':11216'     WHERE list_id = 'contrameth' AND option_id = 'fc';
UPDATE list_options SET mapping = ':11113'     WHERE list_id = 'contrameth' AND option_id = 'pat';
UPDATE list_options SET mapping = ':11112'     WHERE list_id = 'contrameth' AND option_id = 'imp';
UPDATE list_options SET mapping = ':11111'     WHERE list_id = 'contrameth' AND option_id = 'inj';
UPDATE list_options SET mapping = ':11317'     WHERE list_id = 'contrameth' AND option_id = 'iud';
UPDATE list_options SET mapping = ':11110'     WHERE list_id = 'contrameth' AND option_id = 'or';
UPDATE list_options SET mapping = ':11215'     WHERE list_id = 'contrameth' AND option_id = 'cap';
UPDATE list_options SET mapping = ':11216'     WHERE list_id = 'contrameth' AND option_id = 'sp';
UPDATE list_options SET mapping = ':12.18'     WHERE list_id = 'contrameth' AND option_id = 'vsc';
UPDATE list_options SET mapping = ':00000'     WHERE list_id = 'contrameth' AND option_id = 'no';

UPDATE list_options SET mapping = 'F' WHERE list_id = 'sex' AND option_id = 'Female';
UPDATE list_options SET mapping = 'M' WHERE list_id = 'sex' AND option_id = 'Male';

#IfNotRow2D list_options list_id userlist2 mapping 1
UPDATE list_options SET title = 'Education' WHERE list_id = 'lists' AND option_id = 'userlist2';
DELETE FROM list_options WHERE list_id = 'userlist2';
INSERT INTO list_options ( list_id, option_id, title, seq, is_default, mapping ) VALUES ('userlist2','1','Illiterate',1,0,'0');
INSERT INTO list_options ( list_id, option_id, title, seq, is_default, mapping ) VALUES ('userlist2','2','Basic Schooling',2,1,'1');
INSERT INTO list_options ( list_id, option_id, title, seq, is_default, mapping ) VALUES ('userlist2','3','Advanced Schooling',3,0,'2');
#EndIf

#IfNotRow lang_constants constant_name (New Patient)
insert into lang_constants ( constant_name ) values ( '(New Patient)' );
#EndIf
#IfNotRow lang_constants constant_name Active Encounter
insert into lang_constants ( constant_name ) values ( 'Active Encounter' );
#EndIf
#IfNotRow lang_constants constant_name Active Patient
insert into lang_constants ( constant_name ) values ( 'Active Patient' );
#EndIf
#IfNotRow lang_constants constant_name Active Patient:
insert into lang_constants ( constant_name ) values ( 'Active Patient:' );
#EndIf
#IfNotRow lang_constants constant_name Add Copay
insert into lang_constants ( constant_name ) values ( 'Add Copay' );
#EndIf
#IfNotRow lang_constants constant_name Add Drug
insert into lang_constants ( constant_name ) values ( 'Add Drug' );
#EndIf
#IfNotRow lang_constants constant_name Add Patient Record
insert into lang_constants ( constant_name ) values ( 'Add Patient Record' );
#EndIf
#IfNotRow lang_constants constant_name Appointments and Encounters
insert into lang_constants ( constant_name ) values ( 'Appointments and Encounters' );
#EndIf
#IfNotRow lang_constants constant_name Appointments and Visits
insert into lang_constants ( constant_name ) values ( 'Appointments and Visits' );
#EndIf
#IfNotRow lang_constants constant_name Appt-Enc
insert into lang_constants ( constant_name ) values ( 'Appt-Enc' );
#EndIf
#IfNotRow lang_constants constant_name Cash Rec
insert into lang_constants ( constant_name ) values ( 'Cash Rec' );
#EndIf
#IfNotRow lang_constants constant_name Cash Receipts
insert into lang_constants ( constant_name ) values ( 'Cash Receipts' );
#EndIf
#IfNotRow lang_constants constant_name Chart
insert into lang_constants ( constant_name ) values ( 'Chart' );
#EndIf
#IfNotRow lang_constants constant_name Chief Complaint
insert into lang_constants ( constant_name ) values ( 'Chief Complaint' );
#EndIf
#IfNotRow lang_constants constant_name Chief Compliant
insert into lang_constants ( constant_name ) values ( 'Chief Compliant' );
#EndIf
#IfNotRow lang_constants constant_name Client
insert into lang_constants ( constant_name ) values ( 'Client' );
#EndIf
#IfNotRow lang_constants constant_name CO-PAY
insert into lang_constants ( constant_name ) values ( 'CO-PAY' );
#EndIf
#IfNotRow lang_constants constant_name Collections
insert into lang_constants ( constant_name ) values ( 'Collections' );
#EndIf
#IfNotRow lang_constants constant_name COPAY
insert into lang_constants ( constant_name ) values ( 'COPAY' );
#EndIf
#IfNotRow lang_constants constant_name CPT4
insert into lang_constants ( constant_name ) values ( 'CPT4' );
#EndIf
#IfNotRow lang_constants constant_name Encounter
insert into lang_constants ( constant_name ) values ( 'Encounter' );
#EndIf
#IfNotRow lang_constants constant_name Encounter Forms to Include in this Report:
insert into lang_constants ( constant_name ) values ( 'Encounter Forms to Include in this Report:' );
#EndIf
#IfNotRow lang_constants constant_name Encounters
insert into lang_constants ( constant_name ) values ( 'Encounters' );
#EndIf
#IfNotRow lang_constants constant_name Encounters Report
insert into lang_constants ( constant_name ) values ( 'Encounters Report' );
#EndIf
#IfNotRow lang_constants constant_name Facility
insert into lang_constants ( constant_name ) values ( 'Facility' );
#EndIf
#IfNotRow lang_constants constant_name Fee Sheet
insert into lang_constants ( constant_name ) values ( 'Fee Sheet' );
#EndIf
#IfNotRow lang_constants constant_name Find Patient
insert into lang_constants ( constant_name ) values ( 'Find Patient' );
#EndIf
#IfNotRow lang_constants constant_name ID
insert into lang_constants ( constant_name ) values ( 'ID' );
#EndIf
#IfNotRow lang_constants constant_name IPPF SRH Data
insert into lang_constants ( constant_name ) values ( 'IPPF SRH Data' );
#EndIf
#IfNotRow lang_constants constant_name IPPF SRH Data for
insert into lang_constants ( constant_name ) values ( 'IPPF SRH Data for' );
#EndIf
#IfNotRow lang_constants constant_name Last
insert into lang_constants ( constant_name ) values ( 'Last' );
#EndIf
#IfNotRow lang_constants constant_name New Encounter
insert into lang_constants ( constant_name ) values ( 'New Encounter' );
#EndIf
#IfNotRow lang_constants constant_name New Encounter Form
insert into lang_constants ( constant_name ) values ( 'New Encounter Form' );
#EndIf
#IfNotRow lang_constants constant_name New Patient
insert into lang_constants ( constant_name ) values ( 'New Patient' );
#EndIf
#IfNotRow lang_constants constant_name Past Encounters and Documents
insert into lang_constants ( constant_name ) values ( 'Past Encounters and Documents' );
#EndIf
#IfNotRow lang_constants constant_name Patient
insert into lang_constants ( constant_name ) values ( 'Patient' );
#EndIf
#IfNotRow lang_constants constant_name Patient Appointment
insert into lang_constants ( constant_name ) values ( 'Patient Appointment' );
#EndIf
#IfNotRow lang_constants constant_name Patient Encounter Form
insert into lang_constants ( constant_name ) values ( 'Patient Encounter Form' );
#EndIf
#IfNotRow lang_constants constant_name Patient Notes
insert into lang_constants ( constant_name ) values ( 'Patient Notes' );
#EndIf
#IfNotRow lang_constants constant_name Patient Number
insert into lang_constants ( constant_name ) values ( 'Patient Number' );
#EndIf
#IfNotRow lang_constants constant_name Patient Record Report
insert into lang_constants ( constant_name ) values ( 'Patient Record Report' );
#EndIf
#IfNotRow lang_constants constant_name Patient Report
insert into lang_constants ( constant_name ) values ( 'Patient Report' );
#EndIf
#IfNotRow lang_constants constant_name Patient Transactions
insert into lang_constants ( constant_name ) values ( 'Patient Transactions' );
#EndIf
#IfNotRow lang_constants constant_name Patient/Client
insert into lang_constants ( constant_name ) values ( 'Patient/Client' );
#EndIf
#IfNotRow lang_constants constant_name PID
insert into lang_constants ( constant_name ) values ( 'PID' );
#EndIf
#IfNotRow lang_constants constant_name Prepay
insert into lang_constants ( constant_name ) values ( 'Prepay' );
#EndIf
#IfNotRow lang_constants constant_name Provider
insert into lang_constants ( constant_name ) values ( 'Provider' );
#EndIf
#IfNotRow lang_constants constant_name Pt Notes/Auth
insert into lang_constants ( constant_name ) values ( 'Pt Notes/Auth' );
#EndIf
#IfNotRow lang_constants constant_name Rendering
insert into lang_constants ( constant_name ) values ( 'Rendering' );
#EndIf
#IfNotRow lang_constants constant_name Rx
insert into lang_constants ( constant_name ) values ( 'Rx' );
#EndIf
#IfNotRow lang_constants constant_name Sales by Item
insert into lang_constants ( constant_name ) values ( 'Sales by Item' );
#EndIf
#IfNotRow lang_constants constant_name Save Patient Demographic
insert into lang_constants ( constant_name ) values ( 'Save Patient Demographic' );
#EndIf
#IfNotRow lang_constants constant_name Search or Add Patient
insert into lang_constants ( constant_name ) values ( 'Search or Add Patient ' );
#EndIf
#IfNotRow lang_constants constant_name Select Patient
insert into lang_constants ( constant_name ) values ( 'Select Patient' );
#EndIf
#IfNotRow lang_constants constant_name Select Patient by Last
insert into lang_constants ( constant_name ) values ( 'Select Patient by Last' );
#EndIf
#IfNotRow lang_constants constant_name Sensitivity
insert into lang_constants ( constant_name ) values ( 'Sensitivity' );
#EndIf
#IfNotRow lang_constants constant_name SSN
insert into lang_constants ( constant_name ) values ( 'SSN' );
#EndIf
#IfNotRow lang_constants constant_name Superbill
insert into lang_constants ( constant_name ) values ( 'Superbill' );
#EndIf
#IfNotRow lang_constants constant_name This Encounter
insert into lang_constants ( constant_name ) values ( 'This Encounter' );
#EndIf
#IfNotRow lang_constants constant_name Transact
insert into lang_constants ( constant_name ) values ( 'Transact' );
#EndIf
#IfNotRow lang_constants constant_name User & Group Administration
insert into lang_constants ( constant_name ) values ( 'User & Group Administration' );
#EndIf
#IfNotRow lang_constants constant_name View Comprehensive Patient Report
insert into lang_constants ( constant_name ) values ( 'View Comprehensive Patient Report' );
#EndIf
#IfNotRow lang_constants constant_name Procedures
insert into lang_constants ( constant_name ) values ( 'Procedures' );
#EndIf
#IfNotRow lang_constants constant_name Layout Based
insert into lang_constants ( constant_name ) values ( 'Layout Based' );
#EndIf

insert into lang_definitions ( cons_id, lang_id, definition ) select lc.cons_id, 1, 'New Client' from lang_constants as lc left join lang_definitions as ld on ld.cons_id = lc.cons_id and ld.lang_id = 1 where lc.constant_name = '(New Patient)' and ld.cons_id is null;
insert into lang_definitions ( cons_id, lang_id, definition ) select lc.cons_id, 1, 'Active visit' from lang_constants as lc left join lang_definitions as ld on ld.cons_id = lc.cons_id and ld.lang_id = 1 where lc.constant_name = 'Active Encounter' and ld.cons_id is null;
insert into lang_definitions ( cons_id, lang_id, definition ) select lc.cons_id, 1, 'Active Client' from lang_constants as lc left join lang_definitions as ld on ld.cons_id = lc.cons_id and ld.lang_id = 1 where lc.constant_name = 'Active Patient' and ld.cons_id is null;
insert into lang_definitions ( cons_id, lang_id, definition ) select lc.cons_id, 1, 'Active Client:' from lang_constants as lc left join lang_definitions as ld on ld.cons_id = lc.cons_id and ld.lang_id = 1 where lc.constant_name = 'Active Patient:' and ld.cons_id is null;
insert into lang_definitions ( cons_id, lang_id, definition ) select lc.cons_id, 1, 'Add Payment' from lang_constants as lc left join lang_definitions as ld on ld.cons_id = lc.cons_id and ld.lang_id = 1 where lc.constant_name = 'Add Copay' and ld.cons_id is null;
insert into lang_definitions ( cons_id, lang_id, definition ) select lc.cons_id, 1, 'Add Product' from lang_constants as lc left join lang_definitions as ld on ld.cons_id = lc.cons_id and ld.lang_id = 1 where lc.constant_name = 'Add Drug' and ld.cons_id is null;
insert into lang_definitions ( cons_id, lang_id, definition ) select lc.cons_id, 1, 'Create a new OpenEMR record' from lang_constants as lc left join lang_definitions as ld on ld.cons_id = lc.cons_id and ld.lang_id = 1 where lc.constant_name = 'Add Patient Record' and ld.cons_id is null;
insert into lang_definitions ( cons_id, lang_id, definition ) select lc.cons_id, 1, 'Appointments and Visits' from lang_constants as lc left join lang_definitions as ld on ld.cons_id = lc.cons_id and ld.lang_id = 1 where lc.constant_name = 'Appointments and Encounters' and ld.cons_id is null;
insert into lang_definitions ( cons_id, lang_id, definition ) select lc.cons_id, 1, 'Charges by Visit' from lang_constants as lc left join lang_definitions as ld on ld.cons_id = lc.cons_id and ld.lang_id = 1 where lc.constant_name = 'Appointments and Visits' and ld.cons_id is null;
insert into lang_definitions ( cons_id, lang_id, definition ) select lc.cons_id, 1, 'Appt-Visit' from lang_constants as lc left join lang_definitions as ld on ld.cons_id = lc.cons_id and ld.lang_id = 1 where lc.constant_name = 'Appt-Enc' and ld.cons_id is null;
insert into lang_definitions ( cons_id, lang_id, definition ) select lc.cons_id, 1, 'Svcs Provider Cash Rec' from lang_constants as lc left join lang_definitions as ld on ld.cons_id = lc.cons_id and ld.lang_id = 1 where lc.constant_name = 'Cash Rec' and ld.cons_id is null;
insert into lang_definitions ( cons_id, lang_id, definition ) select lc.cons_id, 1, 'Service Provider Cash Receipts' from lang_constants as lc left join lang_definitions as ld on ld.cons_id = lc.cons_id and ld.lang_id = 1 where lc.constant_name = 'Cash Receipts' and ld.cons_id is null;
insert into lang_definitions ( cons_id, lang_id, definition ) select lc.cons_id, 1, 'System ID' from lang_constants as lc left join lang_definitions as ld on ld.cons_id = lc.cons_id and ld.lang_id = 1 where lc.constant_name = 'Chart' and ld.cons_id is null;
insert into lang_definitions ( cons_id, lang_id, definition ) select lc.cons_id, 1, 'Reason of Visit' from lang_constants as lc left join lang_definitions as ld on ld.cons_id = lc.cons_id and ld.lang_id = 1 where lc.constant_name = 'Chief Complaint' and ld.cons_id is null;
insert into lang_definitions ( cons_id, lang_id, definition ) select lc.cons_id, 1, 'Reason of Visit' from lang_constants as lc left join lang_definitions as ld on ld.cons_id = lc.cons_id and ld.lang_id = 1 where lc.constant_name = 'Chief Compliant' and ld.cons_id is null;
insert into lang_definitions ( cons_id, lang_id, definition ) select lc.cons_id, 1, 'Cliente' from lang_constants as lc left join lang_definitions as ld on ld.cons_id = lc.cons_id and ld.lang_id = 1 where lc.constant_name = 'Client' and ld.cons_id is null;
insert into lang_definitions ( cons_id, lang_id, definition ) select lc.cons_id, 1, 'Payment' from lang_constants as lc left join lang_definitions as ld on ld.cons_id = lc.cons_id and ld.lang_id = 1 where lc.constant_name = 'CO-PAY' and ld.cons_id is null;
insert into lang_definitions ( cons_id, lang_id, definition ) select lc.cons_id, 1, 'Collection Report' from lang_constants as lc left join lang_definitions as ld on ld.cons_id = lc.cons_id and ld.lang_id = 1 where lc.constant_name = 'Collections' and ld.cons_id is null;
insert into lang_definitions ( cons_id, lang_id, definition ) select lc.cons_id, 1, 'Payment' from lang_constants as lc left join lang_definitions as ld on ld.cons_id = lc.cons_id and ld.lang_id = 1 where lc.constant_name = 'COPAY' and ld.cons_id is null;
insert into lang_definitions ( cons_id, lang_id, definition ) select lc.cons_id, 1, 'C3' from lang_constants as lc left join lang_definitions as ld on ld.cons_id = lc.cons_id and ld.lang_id = 1 where lc.constant_name = 'CPT4' and ld.cons_id is null;
insert into lang_definitions ( cons_id, lang_id, definition ) select lc.cons_id, 1, 'Visit' from lang_constants as lc left join lang_definitions as ld on ld.cons_id = lc.cons_id and ld.lang_id = 1 where lc.constant_name = 'Encounter' and ld.cons_id is null;
insert into lang_definitions ( cons_id, lang_id, definition ) select lc.cons_id, 1, 'Visit Forms to include in this Report:' from lang_constants as lc left join lang_definitions as ld on ld.cons_id = lc.cons_id and ld.lang_id = 1 where lc.constant_name = 'Encounter Forms to Include in this Report:' and ld.cons_id is null;
insert into lang_definitions ( cons_id, lang_id, definition ) select lc.cons_id, 1, 'Visits' from lang_constants as lc left join lang_definitions as ld on ld.cons_id = lc.cons_id and ld.lang_id = 1 where lc.constant_name = 'Encounters' and ld.cons_id is null;
insert into lang_definitions ( cons_id, lang_id, definition ) select lc.cons_id, 1, 'Visits Report' from lang_constants as lc left join lang_definitions as ld on ld.cons_id = lc.cons_id and ld.lang_id = 1 where lc.constant_name = 'Encounters Report' and ld.cons_id is null;
insert into lang_definitions ( cons_id, lang_id, definition ) select lc.cons_id, 1, 'Clinic ID' from lang_constants as lc left join lang_definitions as ld on ld.cons_id = lc.cons_id and ld.lang_id = 1 where lc.constant_name = 'Facility' and ld.cons_id is null;
insert into lang_definitions ( cons_id, lang_id, definition ) select lc.cons_id, 1, 'Tally Sheet' from lang_constants as lc left join lang_definitions as ld on ld.cons_id = lc.cons_id and ld.lang_id = 1 where lc.constant_name = 'Fee Sheet' and ld.cons_id is null;
insert into lang_definitions ( cons_id, lang_id, definition ) select lc.cons_id, 1, 'Find Client' from lang_constants as lc left join lang_definitions as ld on ld.cons_id = lc.cons_id and ld.lang_id = 1 where lc.constant_name = 'Find Patient' and ld.cons_id is null;
insert into lang_definitions ( cons_id, lang_id, definition ) select lc.cons_id, 1, 'ID' from lang_constants as lc left join lang_definitions as ld on ld.cons_id = lc.cons_id and ld.lang_id = 1 where lc.constant_name = 'ID' and ld.cons_id is null;
insert into lang_definitions ( cons_id, lang_id, definition ) select lc.cons_id, 1, 'GCAC Form (example)' from lang_constants as lc left join lang_definitions as ld on ld.cons_id = lc.cons_id and ld.lang_id = 1 where lc.constant_name = 'IPPF SRH Data' and ld.cons_id is null;
insert into lang_definitions ( cons_id, lang_id, definition ) select lc.cons_id, 1, 'GCAC Form (example) for' from lang_constants as lc left join lang_definitions as ld on ld.cons_id = lc.cons_id and ld.lang_id = 1 where lc.constant_name = 'IPPF SRH Data for' and ld.cons_id is null;
insert into lang_definitions ( cons_id, lang_id, definition ) select lc.cons_id, 1, 'Last name' from lang_constants as lc left join lang_definitions as ld on ld.cons_id = lc.cons_id and ld.lang_id = 1 where lc.constant_name = 'Last' and ld.cons_id is null;
insert into lang_definitions ( cons_id, lang_id, definition ) select lc.cons_id, 1, 'Visit' from lang_constants as lc left join lang_definitions as ld on ld.cons_id = lc.cons_id and ld.lang_id = 1 where lc.constant_name = 'New Encounter' and ld.cons_id is null;
insert into lang_definitions ( cons_id, lang_id, definition ) select lc.cons_id, 1, 'New Visit Form' from lang_constants as lc left join lang_definitions as ld on ld.cons_id = lc.cons_id and ld.lang_id = 1 where lc.constant_name = 'New Encounter Form' and ld.cons_id is null;
insert into lang_definitions ( cons_id, lang_id, definition ) select lc.cons_id, 1, 'New Client' from lang_constants as lc left join lang_definitions as ld on ld.cons_id = lc.cons_id and ld.lang_id = 1 where lc.constant_name = 'New Patient' and ld.cons_id is null;
insert into lang_definitions ( cons_id, lang_id, definition ) select lc.cons_id, 1, 'Past Visits and Documents' from lang_constants as lc left join lang_definitions as ld on ld.cons_id = lc.cons_id and ld.lang_id = 1 where lc.constant_name = 'Past Encounters and Documents' and ld.cons_id is null;
insert into lang_definitions ( cons_id, lang_id, definition ) select lc.cons_id, 1, 'Client' from lang_constants as lc left join lang_definitions as ld on ld.cons_id = lc.cons_id and ld.lang_id = 1 where lc.constant_name = 'Patient' and ld.cons_id is null;
insert into lang_definitions ( cons_id, lang_id, definition ) select lc.cons_id, 1, 'Client Appointment' from lang_constants as lc left join lang_definitions as ld on ld.cons_id = lc.cons_id and ld.lang_id = 1 where lc.constant_name = 'Patient Appointment' and ld.cons_id is null;
insert into lang_definitions ( cons_id, lang_id, definition ) select lc.cons_id, 1, 'Client Visit Form' from lang_constants as lc left join lang_definitions as ld on ld.cons_id = lc.cons_id and ld.lang_id = 1 where lc.constant_name = 'Patient Encounter Form' and ld.cons_id is null;
insert into lang_definitions ( cons_id, lang_id, definition ) select lc.cons_id, 1, 'Client Notes' from lang_constants as lc left join lang_definitions as ld on ld.cons_id = lc.cons_id and ld.lang_id = 1 where lc.constant_name = 'Patient Notes' and ld.cons_id is null;
insert into lang_definitions ( cons_id, lang_id, definition ) select lc.cons_id, 1, 'Client number' from lang_constants as lc left join lang_definitions as ld on ld.cons_id = lc.cons_id and ld.lang_id = 1 where lc.constant_name = 'Patient Number' and ld.cons_id is null;
insert into lang_definitions ( cons_id, lang_id, definition ) select lc.cons_id, 1, 'Client Record Report' from lang_constants as lc left join lang_definitions as ld on ld.cons_id = lc.cons_id and ld.lang_id = 1 where lc.constant_name = 'Patient Record Report' and ld.cons_id is null;
insert into lang_definitions ( cons_id, lang_id, definition ) select lc.cons_id, 1, 'Client Report' from lang_constants as lc left join lang_definitions as ld on ld.cons_id = lc.cons_id and ld.lang_id = 1 where lc.constant_name = 'Patient Report' and ld.cons_id is null;
insert into lang_definitions ( cons_id, lang_id, definition ) select lc.cons_id, 1, 'Referrals and Other Transactions' from lang_constants as lc left join lang_definitions as ld on ld.cons_id = lc.cons_id and ld.lang_id = 1 where lc.constant_name = 'Patient Transactions' and ld.cons_id is null;
insert into lang_definitions ( cons_id, lang_id, definition ) select lc.cons_id, 1, 'Client' from lang_constants as lc left join lang_definitions as ld on ld.cons_id = lc.cons_id and ld.lang_id = 1 where lc.constant_name = 'Patient/Client' and ld.cons_id is null;
insert into lang_definitions ( cons_id, lang_id, definition ) select lc.cons_id, 1, 'Client ID (assign by the system)' from lang_constants as lc left join lang_definitions as ld on ld.cons_id = lc.cons_id and ld.lang_id = 1 where lc.constant_name = 'PID' and ld.cons_id is null;
insert into lang_definitions ( cons_id, lang_id, definition ) select lc.cons_id, 1, 'Payments' from lang_constants as lc left join lang_definitions as ld on ld.cons_id = lc.cons_id and ld.lang_id = 1 where lc.constant_name = 'Prepay' and ld.cons_id is null;
insert into lang_definitions ( cons_id, lang_id, definition ) select lc.cons_id, 1, 'Service Provider' from lang_constants as lc left join lang_definitions as ld on ld.cons_id = lc.cons_id and ld.lang_id = 1 where lc.constant_name = 'Provider' and ld.cons_id is null;
insert into lang_definitions ( cons_id, lang_id, definition ) select lc.cons_id, 1, 'Client notes/Auth' from lang_constants as lc left join lang_definitions as ld on ld.cons_id = lc.cons_id and ld.lang_id = 1 where lc.constant_name = 'Pt Notes/Auth' and ld.cons_id is null;
insert into lang_definitions ( cons_id, lang_id, definition ) select lc.cons_id, 1, 'Default' from lang_constants as lc left join lang_definitions as ld on ld.cons_id = lc.cons_id and ld.lang_id = 1 where lc.constant_name = 'Rendering' and ld.cons_id is null;
insert into lang_definitions ( cons_id, lang_id, definition ) select lc.cons_id, 1, 'Prescp & Disp' from lang_constants as lc left join lang_definitions as ld on ld.cons_id = lc.cons_id and ld.lang_id = 1 where lc.constant_name = 'Rx' and ld.cons_id is null;
insert into lang_definitions ( cons_id, lang_id, definition ) select lc.cons_id, 1, 'Sales by Item' from lang_constants as lc left join lang_definitions as ld on ld.cons_id = lc.cons_id and ld.lang_id = 1 where lc.constant_name = 'Sales by Item' and ld.cons_id is null;
insert into lang_definitions ( cons_id, lang_id, definition ) select lc.cons_id, 1, 'Save Demographic Client  Data' from lang_constants as lc left join lang_definitions as ld on ld.cons_id = lc.cons_id and ld.lang_id = 1 where lc.constant_name = 'Save Patient Demographic' and ld.cons_id is null;
insert into lang_definitions ( cons_id, lang_id, definition ) select lc.cons_id, 1, 'Search or Add Client' from lang_constants as lc left join lang_definitions as ld on ld.cons_id = lc.cons_id and ld.lang_id = 1 where lc.constant_name = 'Search or Add Patient ' and ld.cons_id is null;
insert into lang_definitions ( cons_id, lang_id, definition ) select lc.cons_id, 1, 'Select Client' from lang_constants as lc left join lang_definitions as ld on ld.cons_id = lc.cons_id and ld.lang_id = 1 where lc.constant_name = 'Select Patient' and ld.cons_id is null;
insert into lang_definitions ( cons_id, lang_id, definition ) select lc.cons_id, 1, 'Select Client by Last' from lang_constants as lc left join lang_definitions as ld on ld.cons_id = lc.cons_id and ld.lang_id = 1 where lc.constant_name = 'Select Patient by Last' and ld.cons_id is null;
insert into lang_definitions ( cons_id, lang_id, definition ) select lc.cons_id, 1, 'Risk' from lang_constants as lc left join lang_definitions as ld on ld.cons_id = lc.cons_id and ld.lang_id = 1 where lc.constant_name = 'Sensitivity' and ld.cons_id is null;
insert into lang_definitions ( cons_id, lang_id, definition ) select lc.cons_id, 1, 'National ID' from lang_constants as lc left join lang_definitions as ld on ld.cons_id = lc.cons_id and ld.lang_id = 1 where lc.constant_name = 'SSN' and ld.cons_id is null;
insert into lang_definitions ( cons_id, lang_id, definition ) select lc.cons_id, 1, 'Tally Sheet' from lang_constants as lc left join lang_definitions as ld on ld.cons_id = lc.cons_id and ld.lang_id = 1 where lc.constant_name = 'Superbill' and ld.cons_id is null;
insert into lang_definitions ( cons_id, lang_id, definition ) select lc.cons_id, 1, 'This Visit' from lang_constants as lc left join lang_definitions as ld on ld.cons_id = lc.cons_id and ld.lang_id = 1 where lc.constant_name = 'This Encounter' and ld.cons_id is null;
insert into lang_definitions ( cons_id, lang_id, definition ) select lc.cons_id, 1, 'Referrals' from lang_constants as lc left join lang_definitions as ld on ld.cons_id = lc.cons_id and ld.lang_id = 1 where lc.constant_name = 'Transact' and ld.cons_id is null;
insert into lang_definitions ( cons_id, lang_id, definition ) select lc.cons_id, 1, 'User Administration' from lang_constants as lc left join lang_definitions as ld on ld.cons_id = lc.cons_id and ld.lang_id = 1 where lc.constant_name = 'User & Group Administration' and ld.cons_id is null;
insert into lang_definitions ( cons_id, lang_id, definition ) select lc.cons_id, 1, 'View Comprehensive Client Report' from lang_constants as lc left join lang_definitions as ld on ld.cons_id = lc.cons_id and ld.lang_id = 1 where lc.constant_name = 'View Comprehensive Patient Report' and ld.cons_id is null;
insert into lang_definitions ( cons_id, lang_id, definition ) select lc.cons_id, 1, 'Diagnostic Labs & Orders' from lang_constants as lc left join lang_definitions as ld on ld.cons_id = lc.cons_id and ld.lang_id = 1 where lc.constant_name = 'Procedures' and ld.cons_id is null;
insert into lang_definitions ( cons_id, lang_id, definition ) select lc.cons_id, 1, 'Program' from lang_constants as lc left join lang_definitions as ld on ld.cons_id = lc.cons_id and ld.lang_id = 1 where lc.constant_name = 'Layout Based' and ld.cons_id is null;

UPDATE openemr_postcalendar_categories SET pc_catname = '1 Admission', pc_catcolor = '#FFFFFF' WHERE pc_catid = 10 AND pc_catname = 'New Patient';
UPDATE openemr_postcalendar_categories SET pc_catname = '2 Re-Visit', pc_catcolor = '#CCFFFF' WHERE pc_catid = 9 AND pc_catname = 'Established Patient';
#IfNotRow openemr_postcalendar_categories pc_catid 12
INSERT INTO `openemr_postcalendar_categories` VALUES (12,'3 Counselling Only','#FFFFCC','Counselling',1,NULL,'a:5:{s:17:\"event_repeat_freq\";s:1:\"1\";s:22:\"event_repeat_freq_type\";s:1:\"4\";s:19:\"event_repeat_on_num\";s:1:\"1\";s:19:\"event_repeat_on_day\";s:1:\"0\";s:20:\"event_repeat_on_freq\";s:1:\"0\";}',0,900,0,3,2,0,0,0);
#EndIf
#IfNotRow openemr_postcalendar_categories pc_catid 13
INSERT INTO `openemr_postcalendar_categories` VALUES (13,'4 Supply/Re-Supply','#CCCCCC','Supply/Re-Supply',1,NULL,'a:5:{s:17:\"event_repeat_freq\";s:1:\"1\";s:22:\"event_repeat_freq_type\";s:1:\"4\";s:19:\"event_repeat_on_num\";s:1:\"1\";s:19:\"event_repeat_on_day\";s:1:\"0\";s:20:\"event_repeat_on_freq\";s:1:\"0\";}',0,900,0,3,2,0,0,1);
#EndIf
#IfNotRow openemr_postcalendar_categories pc_catid 14
INSERT INTO `openemr_postcalendar_categories` VALUES (14,'5 Administrative','#FFFFFF','Supply/Re-Supply',1,NULL,'a:5:{s:17:\"event_repeat_freq\";s:1:\"1\";s:22:\"event_repeat_freq_type\";s:1:\"4\";s:19:\"event_repeat_on_num\";s:1:\"1\";s:19:\"event_repeat_on_day\";s:1:\"0\";s:20:\"event_repeat_on_freq\";s:1:\"0\";}',0,900,0,3,2,0,0,1);
#EndIf

#IfNotRow globals gl_name full_new_patient_form
INSERT INTO globals ( gl_name, gl_index, gl_value ) VALUES ( 'full_new_patient_form'       , 0, '3' );
#EndIf
#IfNotRow globals gl_name patient_search_results_style
INSERT INTO globals ( gl_name, gl_index, gl_value ) VALUES ( 'patient_search_results_style', 0, '1' );
#EndIf
#IfNotRow globals gl_name simplified_demographics
INSERT INTO globals ( gl_name, gl_index, gl_value ) VALUES ( 'simplified_demographics'     , 0, '1' );
#EndIf
#IfNotRow globals gl_name online_support_link
INSERT INTO globals ( gl_name, gl_index, gl_value ) VALUES ( 'online_support_link'         , 0, ''  );
#EndIf
#IfNotRow globals gl_name units_of_measurement
INSERT INTO globals ( gl_name, gl_index, gl_value ) VALUES ( 'units_of_measurement'        , 0, '2' );
#EndIf
#IfNotRow globals gl_name specific_application
INSERT INTO globals ( gl_name, gl_index, gl_value ) VALUES ( 'specific_application'        , 0, '2' );
#EndIf
#IfNotRow globals gl_name inhouse_pharmacy
INSERT INTO globals ( gl_name, gl_index, gl_value ) VALUES ( 'inhouse_pharmacy'            , 0, '2' );
#EndIf
#IfNotRow globals gl_name configuration_import_export
INSERT INTO globals ( gl_name, gl_index, gl_value ) VALUES ( 'configuration_import_export' , 0, '1' );
#EndIf

#IfNotRow code_types ct_id 11
DELETE FROM code_types;
INSERT INTO code_types (ct_key, ct_id, ct_seq, ct_mod, ct_just, ct_fee, ct_rel, ct_nofs, ct_diag ) VALUES ('MA'  ,12, 1, 0, '', 1, 1, 0, 0);
INSERT INTO code_types (ct_key, ct_id, ct_seq, ct_mod, ct_just, ct_fee, ct_rel, ct_nofs, ct_diag ) VALUES ('IPPF',11, 2, 0, '', 0, 0, 1, 0);
INSERT INTO code_types (ct_key, ct_id, ct_seq, ct_mod, ct_just, ct_fee, ct_rel, ct_nofs, ct_diag ) VALUES ('ICD9', 2, 3, 2, '', 0, 0, 0, 1);
INSERT INTO code_types (ct_key, ct_id, ct_seq, ct_mod, ct_just, ct_fee, ct_rel, ct_nofs, ct_diag ) VALUES ('ACCT',13, 4, 0, '', 0, 0, 1, 0);
#EndIf

#IfNotRow2D layout_options form_id LBFgcac field_id gc_rreason
DELETE FROM layout_options WHERE form_id = 'LBFgcac';
INSERT INTO layout_options VALUES ('LBFgcac','client_status','1Basic Information','Client Status'               , 1,27,2, 0, 0,'clientstatus',1,1,'','' ,'Client Status',0);
INSERT INTO layout_options VALUES ('LBFgcac','ab_location'  ,'1Basic Information','Type of Visit'               , 2,27,2, 0, 0,'ab_location' ,1,1,'','' ,'Nature of this visit',0);
INSERT INTO layout_options VALUES ('LBFgcac','in_ab_proc'   ,'1Basic Information','Associated Induced Procedure', 3,27,1, 0, 0,'in_ab_proc'  ,1,3,'','' ,'Applies regardless of when or where done',0);
INSERT INTO layout_options VALUES ('LBFgcac','complications','2Complications','Complications'                   , 1,21,1, 2, 0,'complication',1,3,'','' ,'Post-Abortion Complications',0);
INSERT INTO layout_options VALUES ('LBFgcac','main_compl'   ,'2Complications','Main Complication'               , 2, 1,1, 2, 0,'complication',1,3,'','' ,'Primary Complication',0);
INSERT INTO layout_options VALUES ('LBFgcac','gc_rreason'   ,'3IPPA CAC Section','Reason when Rejected/Referred', 1, 1,0, 0, 0,'gc_rreason'   ,1,3,'','','Reason for rejecting or referring services',0);
INSERT INTO layout_options VALUES ('LBFgcac','gc_reason'    ,'3IPPA CAC Section','Main Reason for MR Services'  , 1, 1,0, 0, 0,'gc_reason'   ,1,3,'','' ,'Main reason for requesting MR services',0);
INSERT INTO layout_options VALUES ('LBFgcac','gc_condition' ,'3IPPA CAC Section','Aborted Conception Condition' , 2, 1,0, 0 ,0,'gc_condition',1,3,'','' ,'Condition of Aborted Conception',0);
INSERT INTO layout_options VALUES ('LBFgcac','gc_efforts'   ,'3IPPA CAC Section','Efforts Prior to Visit'       , 3, 1,0, 0, 0,'gc_efforts'  ,1,3,'','' ,'Other efforts conducted before visiting the clinic',0);
INSERT INTO layout_options VALUES ('LBFgcac','gc_complaint' ,'3IPPA CAC Section','Complaint from Client'        , 4, 1,0, 0, 0,'gc_complaint',1,3,'','' ,'Complaint from Client',0);
#EndIf

# #IfNotRow2D list_options list_id lists option_id gc_rreason
# INSERT INTO list_options ( list_id, option_id, title, seq ) VALUES ('lists','gc_rreason','GCAC Reason to Reject/Refer Services',88);
# DELETE FROM list_options WHERE list_id = 'gc_rreason';
# INSERT INTO list_options ( list_id, option_id, title, seq ) VALUES ('gc_rreason','1' ,'Service not available', 1);
# INSERT INTO list_options ( list_id, option_id, title, seq ) VALUES ('gc_rreason','2' ,'Cost of service'      , 2);
# INSERT INTO list_options ( list_id, option_id, title, seq ) VALUES ('gc_rreason','3' ,'Single'               , 3);
# INSERT INTO list_options ( list_id, option_id, title, seq ) VALUES ('gc_rreason','4' ,'Medical reason'       , 4);
# INSERT INTO list_options ( list_id, option_id, title, seq ) VALUES ('gc_rreason','5' ,'No responsible person', 5);
# INSERT INTO list_options ( list_id, option_id, title, seq ) VALUES ('gc_rreason','6' ,'Weeks of pregnancy'   , 6);
# #EndIf

# #IfNotRow2D list_options list_id lists option_id gc_reason
# INSERT INTO list_options ( list_id, option_id, title, seq ) VALUES ('lists','gc_reason','GCAC Main Reason for MR Services',88);
# DELETE FROM list_options WHERE list_id = 'gc_reason';
# INSERT INTO list_options ( list_id, option_id, title, seq ) VALUES ('gc_reason','1' ,'Have already enough children'                , 1);
# INSERT INTO list_options ( list_id, option_id, title, seq ) VALUES ('gc_reason','2' ,'The children are still babies'               , 2);
# INSERT INTO list_options ( list_id, option_id, title, seq ) VALUES ('gc_reason','3' ,'Too young to have baby'                      , 3);
# INSERT INTO list_options ( list_id, option_id, title, seq ) VALUES ('gc_reason','4' ,'Too old to have other child'                 , 4);
# INSERT INTO list_options ( list_id, option_id, title, seq ) VALUES ('gc_reason','5' ,'Not / not yet married'                       , 5);
# INSERT INTO list_options ( list_id, option_id, title, seq ) VALUES ('gc_reason','6' ,'Still goes to school / college'              , 6);
# INSERT INTO list_options ( list_id, option_id, title, seq ) VALUES ('gc_reason','7' ,'Engage with Official'                        , 7);
# INSERT INTO list_options ( list_id, option_id, title, seq ) VALUES ('gc_reason','8' ,'Could not stand pain / sickness of pregnancy', 8);
# INSERT INTO list_options ( list_id, option_id, title, seq ) VALUES ('gc_reason','9' ,'Others'                                      , 9);
# #EndIf

# #IfNotRow2D list_options list_id lists option_id gc_condition
# INSERT INTO list_options ( list_id, option_id, title, seq ) VALUES ('lists','gc_condition','GCAC Aborted Conception Condition',88);
# DELETE FROM list_options WHERE list_id = 'gc_condition';
# INSERT INTO list_options ( list_id, option_id, title, seq ) VALUES ('gc_condition','1' ,'Fresh' , 1);
# INSERT INTO list_options ( list_id, option_id, title, seq ) VALUES ('gc_condition','2' ,'Dark'  , 2);
# INSERT INTO list_options ( list_id, option_id, title, seq ) VALUES ('gc_condition','3' ,'Sticky', 3);
# INSERT INTO list_options ( list_id, option_id, title, seq ) VALUES ('gc_condition','4' ,'Others', 4);
# INSERT INTO list_options ( list_id, option_id, title, seq ) VALUES ('gc_condition','5' ,'N/A'   , 0);
# #EndIf

# #IfNotRow2D list_options list_id lists option_id gc_efforts
# INSERT INTO list_options ( list_id, option_id, title, seq ) VALUES ('lists','gc_efforts','GCAC Prior Efforts',88);
# DELETE FROM list_options WHERE list_id = 'gc_efforts';
# INSERT INTO list_options ( list_id, option_id, title, seq ) VALUES ('gc_efforts','1' ,'None'                                         , 1);
# INSERT INTO list_options ( list_id, option_id, title, seq ) VALUES ('gc_efforts','2' ,'Drinking herbs / medicines'                   , 2);
# INSERT INTO list_options ( list_id, option_id, title, seq ) VALUES ('gc_efforts','3' ,'Had been taken care by paramedic'             , 3);
# INSERT INTO list_options ( list_id, option_id, title, seq ) VALUES ('gc_efforts','4' ,'Massage / went to traditional birth attendant', 4);
# INSERT INTO list_options ( list_id, option_id, title, seq ) VALUES ('gc_efforts','5' ,'Other efforts'                                , 5);
# INSERT INTO list_options ( list_id, option_id, title, seq ) VALUES ('gc_efforts','6' ,'Emergency'                                    , 6);
# #EndIf

# #IfNotRow2D list_options list_id lists option_id gc_complaint
# INSERT INTO list_options ( list_id, option_id, title, seq ) VALUES ('lists','gc_complaint','GCAC Complaint from Client',88);
# DELETE FROM list_options WHERE list_id = 'gc_complaint';
# INSERT INTO list_options ( list_id, option_id, title, seq ) VALUES ('gc_complaint','1' ,'Facility'         , 1);
# INSERT INTO list_options ( list_id, option_id, title, seq ) VALUES ('gc_complaint','2' ,'Time of Queue'    , 2);
# INSERT INTO list_options ( list_id, option_id, title, seq ) VALUES ('gc_complaint','3' ,'Charge of Service', 3);
# INSERT INTO list_options ( list_id, option_id, title, seq ) VALUES ('gc_complaint','4' ,'None'             , 4);
# #EndIf

#IfNotRow2D list_options list_id clientstatus option_id defer
DELETE FROM list_options WHERE list_id = 'clientstatus';
INSERT INTO list_options ( list_id, option_id, title, seq, is_default, option_value ) VALUES ('clientstatus','maaa'  ,'MA Client Accepting Abortion', 1,1,0);
INSERT INTO list_options ( list_id, option_id, title, seq, is_default, option_value ) VALUES ('clientstatus','mara'  ,'MA Client Refusing Abortion' , 2,0,0);
INSERT INTO list_options ( list_id, option_id, title, seq, is_default, option_value ) VALUES ('clientstatus','refin' ,'Inbound Referral'            , 3,0,0);
INSERT INTO list_options ( list_id, option_id, title, seq, is_default, option_value ) VALUES ('clientstatus','self'  ,'Self Referred'               , 4,0,0);
INSERT INTO list_options ( list_id, option_id, title, seq, is_default, option_value ) VALUES ('clientstatus','defer' ,'Deferring / Undecided'       , 5,0,0);
#EndIf

#IfNotRow2D list_options list_id ab_location option_id ma
DELETE FROM list_options WHERE list_id = 'ab_location';
INSERT INTO list_options ( list_id, option_id, title, seq, is_default, option_value ) VALUES ('ab_location','proc' ,'Procedure at this site'              , 1,0,0);
INSERT INTO list_options ( list_id, option_id, title, seq, is_default, option_value ) VALUES ('ab_location','ma'   ,'Followup procedure from this site'   , 2,0,0);
INSERT INTO list_options ( list_id, option_id, title, seq, is_default, option_value ) VALUES ('ab_location','part' ,'Followup procedure from partner site', 3,0,0);
INSERT INTO list_options ( list_id, option_id, title, seq, is_default, option_value ) VALUES ('ab_location','oth'  ,'Followup procedure from other site'  , 4,0,0);
#EndIf

#IfNotRow2D layout_options form_id REF field_id reply_related_code
DELETE FROM layout_options WHERE form_id = 'REF';
INSERT INTO layout_options VALUES ('REF','refer_date'        ,'1Referral','Referral Date'                  , 5, 4,2, 0,  0,''         ,1,1,'C','D','Date of referral',0);
INSERT INTO layout_options VALUES ('REF','refer_from'        ,'1Referral','Referred By'                    ,10,14,2, 0,  0,''         ,1,1,'' ,'' ,'Referral By',0);
INSERT INTO layout_options VALUES ('REF','refer_external'    ,'1Referral','Referral Type'                  ,15, 1,2, 0,  0,'reftype'  ,1,1,'' ,'' ,'Type of referral',0);
INSERT INTO layout_options VALUES ('REF','refer_to'          ,'1Referral','Referred To'                    ,20,14,2, 0,  0,''         ,1,1,'' ,'' ,'Referral To',0);
INSERT INTO layout_options VALUES ('REF','body'              ,'1Referral','Reason'                         ,25, 3,2,30,  3,''         ,1,1,'' ,'' ,'Reason for referral',0);
INSERT INTO layout_options VALUES ('REF','refer_risk_level'  ,'1Referral','Risk Level'                     ,30, 1,1, 0,  0,'risklevel',1,1,'' ,'' ,'Level of urgency',0);
INSERT INTO layout_options VALUES ('REF','refer_vitals'      ,'1Referral','Include Vital Signs'            ,35, 1,1, 0,  0,'boolean'  ,1,1,'' ,'' ,'Include vitals data?',0);
INSERT INTO layout_options VALUES ('REF','refer_reply_date'  ,'1Referral','Expected Reply Date'            ,40, 4,2, 0,  0,''         ,1,1,'' ,'D','Expected date of reply',0);
INSERT INTO layout_options VALUES ('REF','refer_related_code','1Referral','Requested Service'              ,45,15,2,30,255,''         ,1,1,'' ,'' ,'Billing Code for Requested Service',0);
INSERT INTO layout_options VALUES ('REF','refer_diag'        ,'1Referral','Preliminary Diagnosis'          ,50, 2,1,30,255,''         ,1,1,'' ,'X','Referrer diagnosis',0);
INSERT INTO layout_options VALUES ('REF','reply_date'        ,'2Counter-Referral','Reply Date'             , 5, 4,1, 0,  0,''         ,1,1,'' ,'D','Date of reply',0);
INSERT INTO layout_options VALUES ('REF','reply_from'        ,'2Counter-Referral','Reply From'             ,10, 2,1,30,255,''         ,1,1,'' ,'' ,'Who replied?',0);
INSERT INTO layout_options VALUES ('REF','reply_init_diag'   ,'2Counter-Referral','Presumed Diagnosis'     ,15, 2,0,30,255,''         ,1,1,'' ,'' ,'Presumed diagnosis by specialist',0);
INSERT INTO layout_options VALUES ('REF','reply_final_diag'  ,'2Counter-Referral','Final Diagnosis'        ,20, 2,1,30,255,''         ,1,1,'' ,'' ,'Final diagnosis by specialist',0);
INSERT INTO layout_options VALUES ('REF','reply_documents'   ,'2Counter-Referral','Documents'              ,25, 2,1,30,255,''         ,1,1,'' ,'' ,'Where may related scanned or paper documents be found?',0);
INSERT INTO layout_options VALUES ('REF','reply_findings'    ,'2Counter-Referral','Findings'               ,30, 3,1,30,  3,''         ,1,1,'' ,'' ,'Findings by specialist',0);
INSERT INTO layout_options VALUES ('REF','reply_services'    ,'2Counter-Referral','Services Provided'      ,35, 3,0,30,  3,''         ,1,1,'' ,'' ,'Service provided by specialist',0);
INSERT INTO layout_options VALUES ('REF','reply_related_code','2Counter-Referral','Service Provided'       ,40,15,1,30,255,''         ,1,1,'' ,'' ,'Billing Code for actual services provided',0);
INSERT INTO layout_options VALUES ('REF','reply_recommend'   ,'2Counter-Referral','Recommendations'        ,45, 3,1,30,  3,''         ,1,1,'' ,'' ,'Recommendations by specialist',0);
INSERT INTO layout_options VALUES ('REF','reply_rx_refer'    ,'2Counter-Referral','Prescriptions/Referrals',50, 3,1,30,  3,''         ,1,1,'' ,'' ,'Prescriptions and/or referrals by specialist',0);
#EndIf

#IfMissingColumn patient_data usertext11
ALTER TABLE patient_data
  ADD usertext11 varchar(255) NOT NULL DEFAULT '',
  ADD usertext12 varchar(255) NOT NULL DEFAULT '',
  ADD usertext13 varchar(255) NOT NULL DEFAULT '',
  ADD usertext14 varchar(255) NOT NULL DEFAULT '',
  ADD usertext15 varchar(255) NOT NULL DEFAULT '',
  ADD usertext16 varchar(255) NOT NULL DEFAULT '',
  ADD usertext17 varchar(255) NOT NULL DEFAULT '',
  ADD usertext18 varchar(255) NOT NULL DEFAULT '',
  ADD usertext19 varchar(255) NOT NULL DEFAULT '',
  ADD usertext20 varchar(255) NOT NULL DEFAULT '';
#EndIf

#IfNotRow2D layout_options form_id DEM field_id usertext11
INSERT INTO `layout_options` VALUES ('DEM', 'usertext11', '6Misc', 'User Defined Text 11', 8,2,0,10,63,'',1,1,'','','User Defined',0);
INSERT INTO `layout_options` VALUES ('DEM', 'usertext12', '6Misc', 'User Defined Text 12', 8,2,0,10,63,'',1,1,'','','User Defined',0);
INSERT INTO `layout_options` VALUES ('DEM', 'usertext13', '6Misc', 'User Defined Text 13', 8,2,0,10,63,'',1,1,'','','User Defined',0);
INSERT INTO `layout_options` VALUES ('DEM', 'usertext14', '6Misc', 'User Defined Text 14', 8,2,0,10,63,'',1,1,'','','User Defined',0);
INSERT INTO `layout_options` VALUES ('DEM', 'usertext15', '6Misc', 'User Defined Text 15', 8,2,0,10,63,'',1,1,'','','User Defined',0);
INSERT INTO `layout_options` VALUES ('DEM', 'usertext16', '6Misc', 'User Defined Text 16', 8,2,0,10,63,'',1,1,'','','User Defined',0);
INSERT INTO `layout_options` VALUES ('DEM', 'usertext17', '6Misc', 'User Defined Text 17', 8,2,0,10,63,'',1,1,'','','User Defined',0);
INSERT INTO `layout_options` VALUES ('DEM', 'usertext18', '6Misc', 'User Defined Text 18', 8,2,0,10,63,'',1,1,'','','User Defined',0);
INSERT INTO `layout_options` VALUES ('DEM', 'usertext19', '6Misc', 'User Defined Text 19', 8,2,0,10,63,'',1,1,'','','User Defined',0);
INSERT INTO `layout_options` VALUES ('DEM', 'usertext20', '6Misc', 'User Defined Text 20', 8,2,0,10,63,'',1,1,'','','User Defined',0);
#EndIf

#IfNotRow code_types ct_key REF
INSERT INTO code_types (ct_key, ct_id, ct_seq, ct_mod, ct_just, ct_fee, ct_rel, ct_nofs, ct_diag) VALUES ('REF',16, 5, 0, '', 0, 1, 1, 0);
#EndIf

#IfNotRow2D list_options list_id lists option_id actorest
INSERT INTO list_options ( list_id, option_id, title, seq, is_default ) VALUES ('lists','actorest','Actual or Estimated', 1,0);
INSERT INTO list_options ( list_id, option_id, title, seq, is_default ) VALUES ('actorest','act'  ,'Actual'   ,10,1);
INSERT INTO list_options ( list_id, option_id, title, seq, is_default ) VALUES ('actorest','est'  ,'Estimated',20,0);
UPDATE layout_options SET group_name = '1Who', title='', seq = 6, data_type = 1,
  uor = 1, fld_length = 0, list_id = 'actorest', titlecols = 0, datacols = 0,
  description = 'Indicates if DOB is estimated' WHERE
  form_id = 'DEM' AND field_id = 'usertext3' AND uor = 0;
#EndIf

#IfNotRow lang_constants constant_name CLIA Number
insert into lang_constants ( constant_name ) values ( 'CLIA Number' );
#EndIf
insert into lang_definitions ( cons_id, lang_id, definition ) select lc.cons_id, 1, 'SDP ID' from lang_constants as lc left join lang_definitions as ld on ld.cons_id = lc.cons_id and ld.lang_id = 1 where lc.constant_name = 'CLIA Number' and ld.cons_id is null;

# 2011-08-01 we decided not to do this.
# 2011-08-10 decided to do it again.
UPDATE facility SET domain_identifier = facility_npi WHERE facility_npi != '' AND ( domain_identifier IS NULL OR domain_identifier = '' );

# The following re-added 2011-08-15 because LV asked for it. --Rod
#IfNotRow2D list_options list_id lists option_id posref
INSERT INTO list_options ( list_id, option_id, title, seq, is_default ) VALUES ('lists','posref','Channels of Distribution', 1,0);
INSERT INTO list_options ( list_id, option_id, title, seq, is_default ) VALUES ('posref','01','Static Clinic'         ,01,0);
INSERT INTO list_options ( list_id, option_id, title, seq, is_default ) VALUES ('posref','02','Mobile/Outreach Clinic',02,0);
INSERT INTO list_options ( list_id, option_id, title, seq, is_default ) VALUES ('posref','03','Associated Clinics'    ,03,0);
INSERT INTO list_options ( list_id, option_id, title, seq, is_default ) VALUES ('posref','04','Private Physicians'    ,04,0);
INSERT INTO list_options ( list_id, option_id, title, seq, is_default ) VALUES ('posref','05','CBD / CBS'             ,05,0);
INSERT INTO list_options ( list_id, option_id, title, seq, is_default ) VALUES ('posref','06','MA Social Marketing'   ,06,0);
INSERT INTO list_options ( list_id, option_id, title, seq, is_default ) VALUES ('posref','07','Commercial Marketing'  ,07,0);
INSERT INTO list_options ( list_id, option_id, title, seq, is_default ) VALUES ('posref','08','Government'            ,08,0);
INSERT INTO list_options ( list_id, option_id, title, seq, is_default ) VALUES ('posref','09','Other Agencies'        ,09,0);
#EndIf
#IfNotRow lang_constants constant_name POS Code
INSERT INTO lang_constants ( constant_name ) VALUES ( 'POS Code' );
#EndIf
INSERT INTO lang_definitions ( cons_id, lang_id, definition ) SELECT lc.cons_id, 1, 'COD Code' FROM lang_constants AS lc LEFT JOIN lang_definitions AS ld ON ld.cons_id = lc.cons_id AND ld.lang_id = 1 WHERE lc.constant_name = 'POS Code' AND ld.cons_id IS NULL;

#IfNotRow2D list_options list_id lists option_id ippfconmeth
INSERT INTO list_options ( list_id, option_id, title, seq, is_default ) VALUES ('lists','ippfconmeth','IPPF Contraceptive Methods', 1,0);
INSERT INTO list_options ( list_id, option_id, title, seq, is_default ) VALUES ('ippfconmeth','111101110','COC & POC'                                   ,01,0);
INSERT INTO list_options ( list_id, option_id, title, seq, is_default ) VALUES ('ippfconmeth','111111110','Combined Injectable Contraceptives (1 month)',02,0);
INSERT INTO list_options ( list_id, option_id, title, seq, is_default ) VALUES ('ippfconmeth','111112110','Progestogen Only Injectables (2 months)'     ,03,0);
INSERT INTO list_options ( list_id, option_id, title, seq, is_default ) VALUES ('ippfconmeth','111113110','Progestogen Only Injectables (3 months)'     ,04,0);
INSERT INTO list_options ( list_id, option_id, title, seq, is_default ) VALUES ('ippfconmeth','111122110','Subdermal implants 6 rods'                   ,05,0);
INSERT INTO list_options ( list_id, option_id, title, seq, is_default ) VALUES ('ippfconmeth','111123110','Subdermal implants 2 rods'                   ,06,0);
INSERT INTO list_options ( list_id, option_id, title, seq, is_default ) VALUES ('ippfconmeth','111124110','Subdermal implants 1 rod'                    ,07,0);
INSERT INTO list_options ( list_id, option_id, title, seq, is_default ) VALUES ('ippfconmeth','111132110','Transdermal Patch (1 month)'                 ,08,0);
INSERT INTO list_options ( list_id, option_id, title, seq, is_default ) VALUES ('ippfconmeth','111133110','Vaginal Ring (1 month)'                      ,09,0);
INSERT INTO list_options ( list_id, option_id, title, seq, is_default ) VALUES ('ippfconmeth','112141110','Male Condom'                                 ,10,0);
INSERT INTO list_options ( list_id, option_id, title, seq, is_default ) VALUES ('ippfconmeth','112142110','Female Condom'                               ,11,0);
INSERT INTO list_options ( list_id, option_id, title, seq, is_default ) VALUES ('ippfconmeth','112151110','Diaphragm'                                   ,12,0);
INSERT INTO list_options ( list_id, option_id, title, seq, is_default ) VALUES ('ippfconmeth','112152010','Cervical Cap'                                ,13,0);
INSERT INTO list_options ( list_id, option_id, title, seq, is_default ) VALUES ('ippfconmeth','112161110','Spermicides - Foam Tabs/Tube/Suppositories'  ,14,0);
INSERT INTO list_options ( list_id, option_id, title, seq, is_default ) VALUES ('ippfconmeth','112162110','Spermicides - Foam Tabs/Strip'               ,15,0);
INSERT INTO list_options ( list_id, option_id, title, seq, is_default ) VALUES ('ippfconmeth','112163110','Spermicides - Foam Cans'                     ,16,0);
INSERT INTO list_options ( list_id, option_id, title, seq, is_default ) VALUES ('ippfconmeth','112164110','Spermicides - Cream & Jelly'                 ,17,0);
INSERT INTO list_options ( list_id, option_id, title, seq, is_default ) VALUES ('ippfconmeth','112165110','Spermicides - Pessaries / C-film'            ,18,0);
INSERT INTO list_options ( list_id, option_id, title, seq, is_default ) VALUES ('ippfconmeth','113171110','Hormone releasing IUD (5 years)'             ,19,0);
INSERT INTO list_options ( list_id, option_id, title, seq, is_default ) VALUES ('ippfconmeth','113172110','Copper releasing IUD (10 years) '            ,20,0);
INSERT INTO list_options ( list_id, option_id, title, seq, is_default ) VALUES ('ippfconmeth','121181213','Female VSC - Minilaparatomy'                 ,21,0);
INSERT INTO list_options ( list_id, option_id, title, seq, is_default ) VALUES ('ippfconmeth','121181313','Female VSC - Laparoscopy'                    ,22,0);
INSERT INTO list_options ( list_id, option_id, title, seq, is_default ) VALUES ('ippfconmeth','121181413','Female VSC - Laparotomy'                     ,23,0);
INSERT INTO list_options ( list_id, option_id, title, seq, is_default ) VALUES ('ippfconmeth','122182213','Male VSC - Incisional vasectomy'             ,24,0);
INSERT INTO list_options ( list_id, option_id, title, seq, is_default ) VALUES ('ippfconmeth','122182313','Male VSC - No-scalpel Vasectomy'             ,25,0);
INSERT INTO list_options ( list_id, option_id, title, seq, is_default ) VALUES ('ippfconmeth','145212110','Emergency Contraception'                     ,26,0);
#EndIf

#IfNotRow2D list_options list_id lists option_id contratype
INSERT INTO list_options ( list_id, option_id, title, seq, is_default ) VALUES ('lists','contratype','Contraception Event Types', 1,0);
# INSERT INTO list_options ( list_id, option_id, title, seq, is_default ) VALUES ('contratype','1','Starting for lifetime but not MA' ,1,0);
INSERT INTO list_options ( list_id, option_id, title, seq, is_default ) VALUES ('contratype','2','Starting contraception at association',2,0);
# INSERT INTO list_options ( list_id, option_id, title, seq, is_default ) VALUES ('contratype','3','Starting for both lifetime and MA',3,0);
INSERT INTO list_options ( list_id, option_id, title, seq, is_default ) VALUES ('contratype','4','Method change',4,0);
# INSERT INTO list_options ( list_id, option_id, title, seq, is_default ) VALUES ('contratype','5','Not choosing contraception'       ,5,0);
#EndIf

#IfNotRow2D codes code_type 11 code 253232521
DELETE FROM codes WHERE code_type = '11';
INSERT INTO codes ( code_type, code, modifier, code_text ) VALUES ( 11, '110000000', '', 'FAMILY PLANNING METHODS' );
INSERT INTO codes ( code_type, code, modifier, code_text ) VALUES ( 11, '111100000', '', 'CONTRACEPTIVES -  ORAL CONTRACEPTIVES' );
INSERT INTO codes ( code_type, code, modifier, code_text ) VALUES ( 11, '111100119', '', 'Contraceptives - Oral Contraceptives - OC - Method Specific Counselling' );
INSERT INTO codes ( code_type, code, modifier, code_text ) VALUES ( 11, '111100999', '', 'Contraceptives - Oral Contraceptives - OC - OTHER' );
INSERT INTO codes ( code_type, code, modifier, code_text ) VALUES ( 11, '111101000', '', 'CONTRACEPTIVES -  COMBINED & PROGESTOGEN-ONLY ORAL CONTRACEPTIVES' );
INSERT INTO codes ( code_type, code, modifier, code_text ) VALUES ( 11, '111101110', '', 'Contraceptives - Oral Contraceptives - COC & POC - Initial Consultation' );
INSERT INTO codes ( code_type, code, modifier, code_text ) VALUES ( 11, '111101111', '', 'Contraceptives - Oral Contraceptives - COC & POC - Follow up/Resupply' );
INSERT INTO codes ( code_type, code, modifier, code_text ) VALUES ( 11, '111101999', '', 'Contraceptives - Oral Contraceptives - COC & POC - OTHER' );
INSERT INTO codes ( code_type, code, modifier, code_text ) VALUES ( 11, '111110000', '', 'CONTRACEPTIVES -  INJECTABLE CONTRACEPTIVES' );
INSERT INTO codes ( code_type, code, modifier, code_text ) VALUES ( 11, '111110119', '', 'Contraceptives - Injectable Contraceptives - Method Specific Counselling' );
INSERT INTO codes ( code_type, code, modifier, code_text ) VALUES ( 11, '111110999', '', 'Contraceptives - Injectable Contraceptives - OTHER' );
INSERT INTO codes ( code_type, code, modifier, code_text ) VALUES ( 11, '111111000', '', 'CONTRACEPTIVES -  COMBINED INJECTABLE CONTRACEPTIVES - CIC' );
INSERT INTO codes ( code_type, code, modifier, code_text ) VALUES ( 11, '111111110', '', 'Contraceptives - Combined Injectable Contraceptives (1 month) -  Initial Consultation' );
INSERT INTO codes ( code_type, code, modifier, code_text ) VALUES ( 11, '111111111', '', 'Contraceptives - Combined Injectable Contraceptives (1 month) - Follow up/Resupply' );
INSERT INTO codes ( code_type, code, modifier, code_text ) VALUES ( 11, '111111999', '', 'Contraceptives - Combined Injectable Contraceptives (1 month) - OTHER' );
INSERT INTO codes ( code_type, code, modifier, code_text ) VALUES ( 11, '111112000', '', 'CONTRACEPTIVES -  PROGESTOGEN ONLY INJECTABLES (2 MONTHS)' );
INSERT INTO codes ( code_type, code, modifier, code_text ) VALUES ( 11, '111112110', '', 'Contraceptives - Progestogen Only Injectables (2 months) - Initial Consultation' );
INSERT INTO codes ( code_type, code, modifier, code_text ) VALUES ( 11, '111112111', '', 'Contraceptives - Progestogen Only Injectables (2 months) - Follow up/Resupply' );
INSERT INTO codes ( code_type, code, modifier, code_text ) VALUES ( 11, '111112999', '', 'Contraceptives - Progestogen Only Injectables (2 months) - OTHER' );
INSERT INTO codes ( code_type, code, modifier, code_text ) VALUES ( 11, '111113000', '', 'CONTRACEPTIVES -  PROGESTOGEN ONLY INJECTABLES (3 MONTHS)' );
INSERT INTO codes ( code_type, code, modifier, code_text ) VALUES ( 11, '111113110', '', 'Contraceptives - Progestogen Only Injectables (3 months) - Initial Consultation' );
INSERT INTO codes ( code_type, code, modifier, code_text ) VALUES ( 11, '111113111', '', 'Contraceptives - Progestogen Only Injectables (3 months) - Follow up/Resupply' );
INSERT INTO codes ( code_type, code, modifier, code_text ) VALUES ( 11, '111113999', '', 'Contraceptives - Progestogen Only Injectables (3 months) - OTHER' );
INSERT INTO codes ( code_type, code, modifier, code_text ) VALUES ( 11, '111120000', '', 'CONTRACEPTIVES -  SUBDERMAL IMPLANTS' );
INSERT INTO codes ( code_type, code, modifier, code_text ) VALUES ( 11, '111120112', '', 'Contraceptives - Subdermal Implants - Removal' );
INSERT INTO codes ( code_type, code, modifier, code_text ) VALUES ( 11, '111120119', '', 'Contraceptives - Subdermal Implants - Method Specific Counselling' );
INSERT INTO codes ( code_type, code, modifier, code_text ) VALUES ( 11, '111120999', '', 'Contraceptives - Subdermal Implants - OTHER' );
INSERT INTO codes ( code_type, code, modifier, code_text ) VALUES ( 11, '111122000', '', 'CONTRACEPTIVES -  SUBDERMAL IMPLANTS 6 rods' );
INSERT INTO codes ( code_type, code, modifier, code_text ) VALUES ( 11, '111122110', '', 'Contraceptives - Subdermal implants 6 rods - Initial Consultation/Insertion' );
INSERT INTO codes ( code_type, code, modifier, code_text ) VALUES ( 11, '111122111', '', 'Contraceptives - Subdermal implants 6 rods - Follow up/Reinsertion' );
INSERT INTO codes ( code_type, code, modifier, code_text ) VALUES ( 11, '111122999', '', 'Contraceptives - Subdermal implants 6 rods - OTHER' );
INSERT INTO codes ( code_type, code, modifier, code_text ) VALUES ( 11, '111123000', '', 'CONTRACEPTIVES -  SUBDERMAL IMPLANTS 2 rods' );
INSERT INTO codes ( code_type, code, modifier, code_text ) VALUES ( 11, '111123110', '', 'Contraceptives - Subdermal implants 2 rods - Initial Consultation/Insertion' );
INSERT INTO codes ( code_type, code, modifier, code_text ) VALUES ( 11, '111123111', '', 'Contraceptives - Subdermal implants 2 rods - Follow up/Reinsertion' );
INSERT INTO codes ( code_type, code, modifier, code_text ) VALUES ( 11, '111123999', '', 'Contraceptives - Subdermal implants 2 rods - OTHER' );
INSERT INTO codes ( code_type, code, modifier, code_text ) VALUES ( 11, '111124000', '', 'CONTRACEPTIVES -  SUBDERMAL IMPLANTS 1 rods' );
INSERT INTO codes ( code_type, code, modifier, code_text ) VALUES ( 11, '111124110', '', 'Contraceptives - Subdermal implants 1 rod - Initial Consultation/Insertion' );
INSERT INTO codes ( code_type, code, modifier, code_text ) VALUES ( 11, '111124111', '', 'Contraceptives - Subdermal implants 1 rod - Follow up/Reinsertion' );
INSERT INTO codes ( code_type, code, modifier, code_text ) VALUES ( 11, '111124999', '', 'Contraceptives - Subdermal implants 1 rod - OTHER' );
INSERT INTO codes ( code_type, code, modifier, code_text ) VALUES ( 11, '111130000', '', 'CONTRACEPTIVES -  OTHER HORMONAL METHODS' );
INSERT INTO codes ( code_type, code, modifier, code_text ) VALUES ( 11, '111130999', '', 'Contraceptives - Other hormonal methods - OTHER' );
INSERT INTO codes ( code_type, code, modifier, code_text ) VALUES ( 11, '111132000', '', 'CONTRACEPTIVES - OTHER -  TRANSDERMAL PATCH (1 month)' );
INSERT INTO codes ( code_type, code, modifier, code_text ) VALUES ( 11, '111132110', '', 'Contraceptives - Other methods - Transdermal Patch (1 month) - Initial Consultation' );
INSERT INTO codes ( code_type, code, modifier, code_text ) VALUES ( 11, '111132111', '', 'Contraceptives - Other methods - Transdermal Patch (1 month) - Follow up/Resupply' );
INSERT INTO codes ( code_type, code, modifier, code_text ) VALUES ( 11, '111132119', '', 'Contraceptives - Other methods - Transdermal Patch (1 month) - Method Specific Counselling' );
INSERT INTO codes ( code_type, code, modifier, code_text ) VALUES ( 11, '111132999', '', 'Contraceptives - Other methods - Transdermal Patch (1 month) - OTHER' );
INSERT INTO codes ( code_type, code, modifier, code_text ) VALUES ( 11, '111133000', '', 'CONTRACEPTIVES - OTHER -  VAGINAL RING (1 month)' );
INSERT INTO codes ( code_type, code, modifier, code_text ) VALUES ( 11, '111133110', '', 'Contraceptives - Other methods - Vaginal Ring (1 month) - Initial Consultation' );
INSERT INTO codes ( code_type, code, modifier, code_text ) VALUES ( 11, '111133111', '', 'Contraceptives - Other methods - Vaginal Ring (1 month) - Follow up/Resupply' );
INSERT INTO codes ( code_type, code, modifier, code_text ) VALUES ( 11, '111133119', '', 'Contraceptives - Other methods - Vaginal Ring (1 month) - Method Specific Counselling' );
INSERT INTO codes ( code_type, code, modifier, code_text ) VALUES ( 11, '111133999', '', 'Contraceptives - Other methods - Vaginal Ring (1 month) - OTHER' );
INSERT INTO codes ( code_type, code, modifier, code_text ) VALUES ( 11, '112140000', '', 'CONTRACEPTIVES -  CONDOMS' );
INSERT INTO codes ( code_type, code, modifier, code_text ) VALUES ( 11, '112140999', '', 'Contraceptives - Condoms (Male and Female) - OTHER' );
INSERT INTO codes ( code_type, code, modifier, code_text ) VALUES ( 11, '112141000', '', 'CONTRACEPTIVES -  MALE CONDOMS' );
INSERT INTO codes ( code_type, code, modifier, code_text ) VALUES ( 11, '112141110', '', 'Contraceptives - Condoms - Male Condom - Initial Consultation' );
INSERT INTO codes ( code_type, code, modifier, code_text ) VALUES ( 11, '112141111', '', 'Contraceptives - Condoms - Male Condom - Follow up/Resupply' );
INSERT INTO codes ( code_type, code, modifier, code_text ) VALUES ( 11, '112141119', '', 'Contraceptives - Condoms - Male Condom - Method Specific Counselling' );
INSERT INTO codes ( code_type, code, modifier, code_text ) VALUES ( 11, '112141999', '', 'Contraceptives - Condoms - Male Condom - OTHER' );
INSERT INTO codes ( code_type, code, modifier, code_text ) VALUES ( 11, '112142000', '', 'CONTRACEPTIVES -  FEMALE CONDOMS' );
INSERT INTO codes ( code_type, code, modifier, code_text ) VALUES ( 11, '112142110', '', 'Contraceptives - Condoms - Female Condom - Initial Consultation' );
INSERT INTO codes ( code_type, code, modifier, code_text ) VALUES ( 11, '112142111', '', 'Contraceptives - Condoms - Female Condom - Follow up/Resupply' );
INSERT INTO codes ( code_type, code, modifier, code_text ) VALUES ( 11, '112142119', '', 'Contraceptives - Condoms - Female Condom - Method Specific Counselling' );
INSERT INTO codes ( code_type, code, modifier, code_text ) VALUES ( 11, '112142999', '', 'Contraceptives - Condoms - Female Condom - OTHER' );
INSERT INTO codes ( code_type, code, modifier, code_text ) VALUES ( 11, '112150000', '', 'CONTRACEPTIVES -  TYPES OF DIAPHRAGMS / CERVICAL CAPS' );
INSERT INTO codes ( code_type, code, modifier, code_text ) VALUES ( 11, '112150119', '', 'Contraceptives - Diaphragm / Cervical Cap - Method Specific Counselling' );
INSERT INTO codes ( code_type, code, modifier, code_text ) VALUES ( 11, '112150999', '', 'Contraceptives - Diaphragm / Cervical Cap - OTHER' );
INSERT INTO codes ( code_type, code, modifier, code_text ) VALUES ( 11, '112151000', '', 'CONTRACEPTIVES -  DIAPHRAGMS' );
INSERT INTO codes ( code_type, code, modifier, code_text ) VALUES ( 11, '112151110', '', 'Contraceptives - Diaphragm - Initial Consultation' );
INSERT INTO codes ( code_type, code, modifier, code_text ) VALUES ( 11, '112151111', '', 'Contraceptives - Diaphragm - Follow up/Resupply' );
INSERT INTO codes ( code_type, code, modifier, code_text ) VALUES ( 11, '112151999', '', 'Contraceptives - Diaphragm - OTHER' );
INSERT INTO codes ( code_type, code, modifier, code_text ) VALUES ( 11, '112152000', '', 'CONTRACEPTIVES -  CERVICAL CAPS' );
INSERT INTO codes ( code_type, code, modifier, code_text ) VALUES ( 11, '112152010', '', 'Contraceptives - Cervical Cap - Initial Consultation' );
INSERT INTO codes ( code_type, code, modifier, code_text ) VALUES ( 11, '112152011', '', 'Contraceptives - Cervical Cap - Follow up/Resupply' );
INSERT INTO codes ( code_type, code, modifier, code_text ) VALUES ( 11, '112152999', '', 'Contraceptives - Cervical Cap - OTHER' );
INSERT INTO codes ( code_type, code, modifier, code_text ) VALUES ( 11, '112160000', '', 'CONTRACEPTIVES -  SPERMICIDES' );
INSERT INTO codes ( code_type, code, modifier, code_text ) VALUES ( 11, '112160119', '', 'Contraceptives - Spermicides - Method Specific Counselling' );
INSERT INTO codes ( code_type, code, modifier, code_text ) VALUES ( 11, '112160999', '', 'Contraceptives - Spermicides - OTHER' );
INSERT INTO codes ( code_type, code, modifier, code_text ) VALUES ( 11, '112161000', '', 'CONTRACEPTIVES - SPERMICIDES -  FOAM TABS/TUBE/SUPPOSITIORIES' );
INSERT INTO codes ( code_type, code, modifier, code_text ) VALUES ( 11, '112161110', '', 'Contraceptives - Spermicides - Foam Tabs/Tube/Suppositories - Initial Consultation' );
INSERT INTO codes ( code_type, code, modifier, code_text ) VALUES ( 11, '112161111', '', 'Contraceptives - Spermicides - Foam Tabs/Tube/Suppositories - Follow up/Resupply' );
INSERT INTO codes ( code_type, code, modifier, code_text ) VALUES ( 11, '112161999', '', 'Contraceptives - Spermicides - Foam Tabs/Tube/Suppositories - OTHER' );
INSERT INTO codes ( code_type, code, modifier, code_text ) VALUES ( 11, '112162000', '', 'CONTRACEPTIVES - SPERMICIDES -  FOAM TAB/STRIPS' );
INSERT INTO codes ( code_type, code, modifier, code_text ) VALUES ( 11, '112162110', '', 'Contraceptives - Spermicides - Foam Tabs/Strip - Initial Consultation' );
INSERT INTO codes ( code_type, code, modifier, code_text ) VALUES ( 11, '112162111', '', 'Contraceptives - Spermicides - Foam Tabs/Strip - Follow up/Resupply' );
INSERT INTO codes ( code_type, code, modifier, code_text ) VALUES ( 11, '112162999', '', 'Contraceptives - Spermicides - Foam Tabs/Strip - OTHER' );
INSERT INTO codes ( code_type, code, modifier, code_text ) VALUES ( 11, '112163000', '', 'CONTRACEPTIVES - SPERMICIDES -  FOAM CANS' );
INSERT INTO codes ( code_type, code, modifier, code_text ) VALUES ( 11, '112163110', '', 'Contraceptives - Spermicides - Foam Cans - Initial Consultation' );
INSERT INTO codes ( code_type, code, modifier, code_text ) VALUES ( 11, '112163111', '', 'Contraceptives - Spermicides - Foam Cans - Follow up/Resupply' );
INSERT INTO codes ( code_type, code, modifier, code_text ) VALUES ( 11, '112163999', '', 'Contraceptives - Spermicides - Foam Cans - OTHER' );
INSERT INTO codes ( code_type, code, modifier, code_text ) VALUES ( 11, '112164000', '', 'CONTRACEPTIVES - SPERMICIDES -  CREAM & JELLY' );
INSERT INTO codes ( code_type, code, modifier, code_text ) VALUES ( 11, '112164110', '', 'Contraceptives - Spermicides - Cream & Jelly - Initial Consultation' );
INSERT INTO codes ( code_type, code, modifier, code_text ) VALUES ( 11, '112164111', '', 'Contraceptives - Spermicides - Cream & Jelly - Follow up/Resupply' );
INSERT INTO codes ( code_type, code, modifier, code_text ) VALUES ( 11, '112164999', '', 'Contraceptives - Spermicides - Cream & Jelly - OTHER' );
INSERT INTO codes ( code_type, code, modifier, code_text ) VALUES ( 11, '112165000', '', 'CONTRACEPTIVES - SPERMICIDES -  PESSARIES / C-FILM' );
INSERT INTO codes ( code_type, code, modifier, code_text ) VALUES ( 11, '112165110', '', 'Contraceptives - Spermicides - Pessaries / C-film - Initial Consultation' );
INSERT INTO codes ( code_type, code, modifier, code_text ) VALUES ( 11, '112165111', '', 'Contraceptives - Spermicides - Pessaries / C-film - Follow up/Resupply' );
INSERT INTO codes ( code_type, code, modifier, code_text ) VALUES ( 11, '112165999', '', 'Contraceptives - Spermicides - Pessaries / C-film - OTHER' );
INSERT INTO codes ( code_type, code, modifier, code_text ) VALUES ( 11, '113170000', '', 'CONTRACEPTIVES -  IUD' );
INSERT INTO codes ( code_type, code, modifier, code_text ) VALUES ( 11, '113170112', '', 'Contraceptives - IUD - Removal' );
INSERT INTO codes ( code_type, code, modifier, code_text ) VALUES ( 11, '113170119', '', 'Contraceptives - IUD - Method Specific Counselling' );
INSERT INTO codes ( code_type, code, modifier, code_text ) VALUES ( 11, '113170999', '', 'Contraceptives - IUD - OTHER' );
INSERT INTO codes ( code_type, code, modifier, code_text ) VALUES ( 11, '113171000', '', 'CONTRACEPTIVES - IUD (5 years)' );
INSERT INTO codes ( code_type, code, modifier, code_text ) VALUES ( 11, '113171110', '', 'Contraceptives - IUD (5 years) - Initial Consultation/Insertion' );
INSERT INTO codes ( code_type, code, modifier, code_text ) VALUES ( 11, '113171111', '', 'Contraceptives - IUD (5 years) - Follow up/Reinsertion' );
INSERT INTO codes ( code_type, code, modifier, code_text ) VALUES ( 11, '113171999', '', 'Contraceptives - IUD (5 years) - OTHER' );
INSERT INTO codes ( code_type, code, modifier, code_text ) VALUES ( 11, '113172000', '', 'CONTRACEPTIVES - IUD (10 years)' );
INSERT INTO codes ( code_type, code, modifier, code_text ) VALUES ( 11, '113172110', '', 'Contraceptives - IUD (10 years) - Initial Consultation/Insertion' );
INSERT INTO codes ( code_type, code, modifier, code_text ) VALUES ( 11, '113172111', '', 'Contraceptives - IUD (10 years) - Follow up/Reinsertion' );
INSERT INTO codes ( code_type, code, modifier, code_text ) VALUES ( 11, '113172999', '', 'Contraceptives - IUD (10 years) - OTHER' );
INSERT INTO codes ( code_type, code, modifier, code_text ) VALUES ( 11, '120180000', '', 'CONTRACEPTION -  VOLUNTARY SURGICAL CONTRACEPTION (VSC)' );
INSERT INTO codes ( code_type, code, modifier, code_text ) VALUES ( 11, '120180999', '', 'Contraception - Voluntary Surgical Contraception (VSC) - OTHER' );
INSERT INTO codes ( code_type, code, modifier, code_text ) VALUES ( 11, '121181000', '', 'CONTRACEPTION -  FEMALE VOLUNTARY SURGICAL CONTRACEPTION (FVSC)' );
INSERT INTO codes ( code_type, code, modifier, code_text ) VALUES ( 11, '121181112', '', 'Contraception Surgical - Female VSC - Reversal' );
INSERT INTO codes ( code_type, code, modifier, code_text ) VALUES ( 11, '121181119', '', 'Contraception Surgical - Female VSC - Method Specific Counselling' );
INSERT INTO codes ( code_type, code, modifier, code_text ) VALUES ( 11, '121181211', '', 'Contraception Surgical - Female VSC - Minilaparatomy - Follow up' );
INSERT INTO codes ( code_type, code, modifier, code_text ) VALUES ( 11, '121181213', '', 'Contraception Surgical - Female VSC - Minilaparatomy - Contraceptive Surgery' );
INSERT INTO codes ( code_type, code, modifier, code_text ) VALUES ( 11, '121181311', '', 'Contraception Surgical - Female VSC - Laparoscopy - Follow up' );
INSERT INTO codes ( code_type, code, modifier, code_text ) VALUES ( 11, '121181313', '', 'Contraception Surgical - Female VSC - Laparoscopy - Contraceptive Surgery' );
INSERT INTO codes ( code_type, code, modifier, code_text ) VALUES ( 11, '121181411', '', 'Contraception Surgical - Female VSC - Laparotomy - Follow up' );
INSERT INTO codes ( code_type, code, modifier, code_text ) VALUES ( 11, '121181413', '', 'Contraception Surgical - Female VSC - Laparotomy - Contraceptive Surgery' );
INSERT INTO codes ( code_type, code, modifier, code_text ) VALUES ( 11, '121181999', '', 'Contraception Surgical - Female VSC - OTHER' );
INSERT INTO codes ( code_type, code, modifier, code_text ) VALUES ( 11, '122182000', '', 'CONTRACEPTION -  MALE VOLUNTARY SURGICAL CONTRACEPTION (MVSC)' );
INSERT INTO codes ( code_type, code, modifier, code_text ) VALUES ( 11, '122182112', '', 'Contraception Surgical - Male VSC - Reversal' );
INSERT INTO codes ( code_type, code, modifier, code_text ) VALUES ( 11, '122182119', '', 'Contraception Surgical - Male VSC - Method Specific Counselling' );
INSERT INTO codes ( code_type, code, modifier, code_text ) VALUES ( 11, '122182211', '', 'Contraception Surgical - Male VSC - Incisional vasectomy - Follow up (Sperm count)' );
INSERT INTO codes ( code_type, code, modifier, code_text ) VALUES ( 11, '122182213', '', 'Contraception Surgical - Male VSC - Incisional vasectomy - Contraceptive Surgery' );
INSERT INTO codes ( code_type, code, modifier, code_text ) VALUES ( 11, '122182311', '', 'Contraception Surgical - Male VSC - No-scalpel Vasectomy - Follow up  (Sperm count)' );
INSERT INTO codes ( code_type, code, modifier, code_text ) VALUES ( 11, '122182313', '', 'Contraception Surgical - Male VSC - No-scalpel Vasectomy - Contraceptive Surgery' );
INSERT INTO codes ( code_type, code, modifier, code_text ) VALUES ( 11, '122182999', '', 'Contraception Surgical - Male VSC - OTHER' );
INSERT INTO codes ( code_type, code, modifier, code_text ) VALUES ( 11, '130190000', '', 'CONTRACEPTION -  AWARENESS-BASED METHODS' );
INSERT INTO codes ( code_type, code, modifier, code_text ) VALUES ( 11, '130190999', '', 'Contraception -  Awareness-Based Methods - OTHER' );
INSERT INTO codes ( code_type, code, modifier, code_text ) VALUES ( 11, '131191000', '', 'CONTRACEPTION -  FERTILITY AWARENESS-BASED METHODS (FABM)' );
INSERT INTO codes ( code_type, code, modifier, code_text ) VALUES ( 11, '131191119', '', 'Contraception FAB Methods - Method Specific Counselling' );
INSERT INTO codes ( code_type, code, modifier, code_text ) VALUES ( 11, '131191210', '', 'Contraception FAB Methods - Cervical Mucous Method (CMM) - Initial Consultation/Training' );
INSERT INTO codes ( code_type, code, modifier, code_text ) VALUES ( 11, '131191211', '', 'Contraception FAB Methods - Cervical Mucous Method (CMM) - Follow up/Training' );
INSERT INTO codes ( code_type, code, modifier, code_text ) VALUES ( 11, '131191310', '', 'Contraception FAB Methods - Calendar Based Method (CBM) - Initial Consultation/Training' );
INSERT INTO codes ( code_type, code, modifier, code_text ) VALUES ( 11, '131191311', '', 'Contraception FAB Methods - Calendar Based Method (CBM) - Follow up/Training' );
INSERT INTO codes ( code_type, code, modifier, code_text ) VALUES ( 11, '131191410', '', 'Contraception FAB Methods - Sympto-thermal method - Initial Consultation/Training' );
INSERT INTO codes ( code_type, code, modifier, code_text ) VALUES ( 11, '131191411', '', 'Contraception FAB Methods - Sympto-thermal method - Follow up/Training' );
INSERT INTO codes ( code_type, code, modifier, code_text ) VALUES ( 11, '131191510', '', 'Contraception FAB Methods - Standard days method - Initial Consultation/Training' );
INSERT INTO codes ( code_type, code, modifier, code_text ) VALUES ( 11, '131191511', '', 'Contraception FAB Methods - Standard days method - Follow up/Training' );
INSERT INTO codes ( code_type, code, modifier, code_text ) VALUES ( 11, '131191610', '', 'Contraception FAB Methods - Basal Body Temperature (BBT) - Initial Consultation/Training' );
INSERT INTO codes ( code_type, code, modifier, code_text ) VALUES ( 11, '131191611', '', 'Contraception FAB Methods - Basal Body Temperature (BBT) - Follow up/Training' );
INSERT INTO codes ( code_type, code, modifier, code_text ) VALUES ( 11, '131191999', '', 'Contraception - FAB Methods - OTHER' );
INSERT INTO codes ( code_type, code, modifier, code_text ) VALUES ( 11, '141200000', '', 'FAMILY PLANNING GENERAL COUNSELLING' );
INSERT INTO codes ( code_type, code, modifier, code_text ) VALUES ( 11, '141200118', '', 'Contraception - FP General Counselling' );
INSERT INTO codes ( code_type, code, modifier, code_text ) VALUES ( 11, '141200218', '', 'Contraception - FP General Counselling - Combined Counselling (FP - HIV/AIDS incl. Dual protection' );
INSERT INTO codes ( code_type, code, modifier, code_text ) VALUES ( 11, '141200999', '', 'Contraception - FP General Counselling - OTHER' );
INSERT INTO codes ( code_type, code, modifier, code_text ) VALUES ( 11, '145210000', '', 'EMERGENCY CONTRACEPTION SERVICES' );
INSERT INTO codes ( code_type, code, modifier, code_text ) VALUES ( 11, '145210999', '', 'Emergency Contraception Services - OTHER' );
INSERT INTO codes ( code_type, code, modifier, code_text ) VALUES ( 11, '145211000', '', 'EC - COUNSELLING' );
INSERT INTO codes ( code_type, code, modifier, code_text ) VALUES ( 11, '145211119', '', 'EC - Counselling' );
INSERT INTO codes ( code_type, code, modifier, code_text ) VALUES ( 11, '145211999', '', 'EC - Counselling - OTHER' );
INSERT INTO codes ( code_type, code, modifier, code_text ) VALUES ( 11, '145212000', '', 'EC - THERAPEUTIC' );
INSERT INTO codes ( code_type, code, modifier, code_text ) VALUES ( 11, '145212110', '', 'EC - Combined Oral Contraceptives - Yuzpe - Contraceptive Supply (Treatment)' );
INSERT INTO codes ( code_type, code, modifier, code_text ) VALUES ( 11, '145212111', '', 'EC - Combined Oral Contraceptives - Yuzpe - Follow Up' );
INSERT INTO codes ( code_type, code, modifier, code_text ) VALUES ( 11, '145212210', '', 'EC Progestogen Only Pills - Contraceptive Supply (Treatment)' );
INSERT INTO codes ( code_type, code, modifier, code_text ) VALUES ( 11, '145212211', '', 'EC Progestogen Only Pills - Follow Up' );
INSERT INTO codes ( code_type, code, modifier, code_text ) VALUES ( 11, '145212310', '', 'EC Dedicated Product - Contraceptive Supply (Treatment)' );
INSERT INTO codes ( code_type, code, modifier, code_text ) VALUES ( 11, '145212311', '', 'EC Dedicated Product - Follow Up' );
INSERT INTO codes ( code_type, code, modifier, code_text ) VALUES ( 11, '145212410', '', 'EC Copper releasing IUD - DIU Insertion (Treatment)' );
INSERT INTO codes ( code_type, code, modifier, code_text ) VALUES ( 11, '145212411', '', 'EC Copper releasing IUD - Follow Up' );
INSERT INTO codes ( code_type, code, modifier, code_text ) VALUES ( 11, '145212999', '', 'EC - Therapeutic - OTHER' );
INSERT INTO codes ( code_type, code, modifier, code_text ) VALUES ( 11, '250000000', '', 'SRH (NON FAMILY PLANNING) SERVICES' );
INSERT INTO codes ( code_type, code, modifier, code_text ) VALUES ( 11, '252220000', '', 'ABORTION SERVICES' );
INSERT INTO codes ( code_type, code, modifier, code_text ) VALUES ( 11, '252220999', '', 'Abortion Services - OTHER' );
INSERT INTO codes ( code_type, code, modifier, code_text ) VALUES ( 11, '252221000', '', 'ABORTION / PRE ABORTION COUNSELLING' );
INSERT INTO codes ( code_type, code, modifier, code_text ) VALUES ( 11, '252221129', '', 'Abortion / Pre Abortion Counselling - Pregnancy options Counseling - Including Family Planning' );
INSERT INTO codes ( code_type, code, modifier, code_text ) VALUES ( 11, '252221229', '', 'Abortion / Pre Abortion Counselling - Counselling on HIV Testing' );
INSERT INTO codes ( code_type, code, modifier, code_text ) VALUES ( 11, '252221329', '', 'Abortion / Pre Abortion Counselling  Harm Reduction Initial Consultation' );
INSERT INTO codes ( code_type, code, modifier, code_text ) VALUES ( 11, '252221999', '', 'Abortion / Pre Abortion  Counselling - OTHER' );
INSERT INTO codes ( code_type, code, modifier, code_text ) VALUES ( 11, '252222000', '', 'ABORTION / PRE-ABORTION DIAGNOSTICS' );
INSERT INTO codes ( code_type, code, modifier, code_text ) VALUES ( 11, '252222121', '', 'Abortion Diagnosis - Exclusion of Anaemia (Haemoglobin and/or Hematocrit tests)' );
INSERT INTO codes ( code_type, code, modifier, code_text ) VALUES ( 11, '252222221', '', 'Abortion Diagnosis - Tests for ABO and Rhesus (Rh) blood groups typing' );
INSERT INTO codes ( code_type, code, modifier, code_text ) VALUES ( 11, '252222321', '', 'Abortion Diagnosis - Exclusion of ectopic pregnancy (through ultrasound)' );
INSERT INTO codes ( code_type, code, modifier, code_text ) VALUES ( 11, '252222421', '', 'Abortion Diagnosis - Cervical cytology (Pap test or visual acid test)' );
INSERT INTO codes ( code_type, code, modifier, code_text ) VALUES ( 11, '252222521', '', 'Abortion Diagnosis - HIV testing' );
INSERT INTO codes ( code_type, code, modifier, code_text ) VALUES ( 11, '252222999', '', 'Abortion Diagnosis - OTHER' );
INSERT INTO codes ( code_type, code, modifier, code_text ) VALUES ( 11, '252223000', '', 'ABORTION / INDUCED - SURGICAL' );
INSERT INTO codes ( code_type, code, modifier, code_text ) VALUES ( 11, '252223123', '', 'Abortion Induced (Surgical) - Dilatation And Curettage (D&C)' );
INSERT INTO codes ( code_type, code, modifier, code_text ) VALUES ( 11, '252223223', '', 'Abortion Induced (Surgical) - Dilatation And Evacuation (D&E) (2nd trimester of pregnancy)' );
INSERT INTO codes ( code_type, code, modifier, code_text ) VALUES ( 11, '252223323', '', 'Abortion Induced (Surgical) - Vacuum Aspiration (Manual or Electrical)' );
INSERT INTO codes ( code_type, code, modifier, code_text ) VALUES ( 11, '252223999', '', 'Abortion Induced (Surgical) - OTHER' );
INSERT INTO codes ( code_type, code, modifier, code_text ) VALUES ( 11, '252224000', '', 'ABORTION (MEDICAL)' );
INSERT INTO codes ( code_type, code, modifier, code_text ) VALUES ( 11, '252224122', '', 'Abortion Induced (Medical) - Drug induced (combination of mifepristone and misopristol))' );
INSERT INTO codes ( code_type, code, modifier, code_text ) VALUES ( 11, '252224222', '', 'Abortion Induced (Medical) - Drug induced (Misoprostol Only)' );
INSERT INTO codes ( code_type, code, modifier, code_text ) VALUES ( 11, '252224999', '', 'Abortion Induced (Medical) - OTHER' );
INSERT INTO codes ( code_type, code, modifier, code_text ) VALUES ( 11, '252225000', '', 'ABORTION / INCOMPLETE ABORTION  TREATMENT (SURGICAL/MEDICAL)' );
INSERT INTO codes ( code_type, code, modifier, code_text ) VALUES ( 11, '252225123', '', 'Abortion (Incomplete Abortion) - Surgical treatment / D&C or D&E' );
INSERT INTO codes ( code_type, code, modifier, code_text ) VALUES ( 11, '252225223', '', 'Abortion (Incomplete Abortion) - Surgical treatment / Vacuum aspiration' );
INSERT INTO codes ( code_type, code, modifier, code_text ) VALUES ( 11, '252225722', '', 'Abortion (Incomplete Abortion) - Medical treatment' );
INSERT INTO codes ( code_type, code, modifier, code_text ) VALUES ( 11, '252225999', '', 'Abortion (Incomplete Abortion) - OTHER' );
INSERT INTO codes ( code_type, code, modifier, code_text ) VALUES ( 11, '252226000', '', 'ABORTION / POST ABORTION FOLLOW UP' );
INSERT INTO codes ( code_type, code, modifier, code_text ) VALUES ( 11, '252226120', '', 'Abortion - Post - Follow-up incl. Uterine Involution Monitoring & Bimanual Pelvic Exam.' );
INSERT INTO codes ( code_type, code, modifier, code_text ) VALUES ( 11, '252226999', '', 'Abortion - Post Abortion Follow-up - OTHER (including treatment of complications)' );
INSERT INTO codes ( code_type, code, modifier, code_text ) VALUES ( 11, '252227000', '', 'ABORTION / POST ABORTION COUNSELLING' );
INSERT INTO codes ( code_type, code, modifier, code_text ) VALUES ( 11, '252227129', '', 'Abortion Counselling - Post Abortion Counseling - Including Family Planning' );
INSERT INTO codes ( code_type, code, modifier, code_text ) VALUES ( 11, '252227229', '', 'Abortion Counselling  Harm Reduction Follow-up Consultation' );
INSERT INTO codes ( code_type, code, modifier, code_text ) VALUES ( 11, '252227999', '', 'Abortion Counselling - Post Abortion Counseling and family planning counselling' );
INSERT INTO codes ( code_type, code, modifier, code_text ) VALUES ( 11, '253230000', '', 'HIV and AIDS SERVICES' );
INSERT INTO codes ( code_type, code, modifier, code_text ) VALUES ( 11, '253230999', '', 'HIV and AIDS Services - OTHER' );
INSERT INTO codes ( code_type, code, modifier, code_text ) VALUES ( 11, '253231000', '', 'HIV and AIDS TREATMENT' );
INSERT INTO codes ( code_type, code, modifier, code_text ) VALUES ( 11, '253231122', '', 'HIV and AIDS - Treatment- Anti Retro Viral (ARV)' );
INSERT INTO codes ( code_type, code, modifier, code_text ) VALUES ( 11, '253231222', '', 'HIV and AIDS - Treatment - Opportunistic Infection (OI)' );
INSERT INTO codes ( code_type, code, modifier, code_text ) VALUES ( 11, '253231322', '', 'HIV and AIDS - Treatment - Post Exposure Prophylaxis (PEP)' );
INSERT INTO codes ( code_type, code, modifier, code_text ) VALUES ( 11, '253231422', '', 'HIV and AIDS - Treatment - Psycho-Social Support' );
INSERT INTO codes ( code_type, code, modifier, code_text ) VALUES ( 11, '253231522', '', 'HIV and AIDS - Treatment - Home Care' );
INSERT INTO codes ( code_type, code, modifier, code_text ) VALUES ( 11, '253231999', '', 'HIV and AIDS - Treatment - OTHER' );
INSERT INTO codes ( code_type, code, modifier, code_text ) VALUES ( 11, '253232000', '', 'HIV and AIDS DIAGNOSTIC LAB TESTS' );
INSERT INTO codes ( code_type, code, modifier, code_text ) VALUES ( 11, '253232121', '', 'HIV and AIDS Diagnostic Lab Tests - ELISA (Blood) Test' );
INSERT INTO codes ( code_type, code, modifier, code_text ) VALUES ( 11, '253232221', '', 'HIV and AIDS Diagnostic Lab Tests - Western Blot (WB) Assay' );
INSERT INTO codes ( code_type, code, modifier, code_text ) VALUES ( 11, '253232321', '', 'HIV and AIDS Diagnostic Lab Tests - Indirect Immunofluorescence Assay (IFA)' );
INSERT INTO codes ( code_type, code, modifier, code_text ) VALUES ( 11, '253232421', '', 'HIV and AIDS Diagnostic Lab Tests - Rapid Test (Murex-SUDS)' );
INSERT INTO codes ( code_type, code, modifier, code_text ) VALUES ( 11, '253232521', '', 'HIV and AIDS Diagnostic Lab  tests - Urine Test for HIV' );
INSERT INTO codes ( code_type, code, modifier, code_text ) VALUES ( 11, '253232999', '', 'HIV and AIDS Lab Tests - OTHER' );
INSERT INTO codes ( code_type, code, modifier, code_text ) VALUES ( 11, '253233000', '', 'HIV and AIDS STAGING AND MONITORING TESTS' );
INSERT INTO codes ( code_type, code, modifier, code_text ) VALUES ( 11, '253233121', '', 'HIV and AIDS Other Lab Tests - Urine Test for HIV' );
INSERT INTO codes ( code_type, code, modifier, code_text ) VALUES ( 11, '253233221', '', 'HIV and AIDS Staging and monitoring Tests - Assessment of Immunologic Function (Viral Load)' );
INSERT INTO codes ( code_type, code, modifier, code_text ) VALUES ( 11, '253233321', '', 'HIV and AIDS Staging and monitoring Tests - Assessment of Immunologic Function (CD4 count)' );
INSERT INTO codes ( code_type, code, modifier, code_text ) VALUES ( 11, '253233999', '', 'HIV and AIDS Staging and monitoring Tests - OTHER' );
INSERT INTO codes ( code_type, code, modifier, code_text ) VALUES ( 11, '253234000', '', 'HIV and AIDS PREVENTION COUNSELING' );
INSERT INTO codes ( code_type, code, modifier, code_text ) VALUES ( 11, '253234129', '', 'HIV and AIDS Prevention Counselling' );
INSERT INTO codes ( code_type, code, modifier, code_text ) VALUES ( 11, '253234999', '', 'HIV and AIDS Prevention Counselling - OTHER' );
INSERT INTO codes ( code_type, code, modifier, code_text ) VALUES ( 11, '253235000', '', 'HIV and AIDS PRE/POST TEST COUNSELLING' );
INSERT INTO codes ( code_type, code, modifier, code_text ) VALUES ( 11, '253235129', '', 'HIV and AIDS Counselling - PRE Test' );
INSERT INTO codes ( code_type, code, modifier, code_text ) VALUES ( 11, '253235229', '', 'HIV and AIDS Counselling - POST Test (Positive) - Clients Only' );
INSERT INTO codes ( code_type, code, modifier, code_text ) VALUES ( 11, '253235329', '', 'HIV and AIDS Counseling - POST Test (Negative)' );
INSERT INTO codes ( code_type, code, modifier, code_text ) VALUES ( 11, '253235429', '', 'HIV and AIDS Counseling - POST Test (Positive) - Sexual Partners' );
INSERT INTO codes ( code_type, code, modifier, code_text ) VALUES ( 11, '253235529', '', 'HIV and AIDS Counselling - POST Test' );
INSERT INTO codes ( code_type, code, modifier, code_text ) VALUES ( 11, '253235999', '', 'HIV and AIDS Counseling - OTHER' );
INSERT INTO codes ( code_type, code, modifier, code_text ) VALUES ( 11, '254240000', '', 'STI/RTI SERVICES' );
INSERT INTO codes ( code_type, code, modifier, code_text ) VALUES ( 11, '254240999', '', 'STI/RTI Services - OTHER' );
INSERT INTO codes ( code_type, code, modifier, code_text ) VALUES ( 11, '254241000', '', 'STI/RTI PREVENTION / POST TEST COUNSELLING' );
INSERT INTO codes ( code_type, code, modifier, code_text ) VALUES ( 11, '254241129', '', 'STI/RTI Counseling - Prevention Counseling' );
INSERT INTO codes ( code_type, code, modifier, code_text ) VALUES ( 11, '254241229', '', 'STI/RTI Counseling - POST Test' );
INSERT INTO codes ( code_type, code, modifier, code_text ) VALUES ( 11, '254241999', '', 'STI/RTI Prevention / Post Test Counseling - OTHER' );
INSERT INTO codes ( code_type, code, modifier, code_text ) VALUES ( 11, '254242000', '', 'STI/RTI CONSULTATION' );
INSERT INTO codes ( code_type, code, modifier, code_text ) VALUES ( 11, '254242120', '', 'STI/RTI Consultation - Follow Up' );
INSERT INTO codes ( code_type, code, modifier, code_text ) VALUES ( 11, '254242999', '', 'STI/RTI Consultation - OTHER' );
INSERT INTO codes ( code_type, code, modifier, code_text ) VALUES ( 11, '254243000', '', 'STI/RTI LAB TESTS' );
INSERT INTO codes ( code_type, code, modifier, code_text ) VALUES ( 11, '254243121', '', 'STI/RTI Test - Bacterial Vaginosis' );
INSERT INTO codes ( code_type, code, modifier, code_text ) VALUES ( 11, '254243221', '', 'STI/RTI Test - Candidiasis' );
INSERT INTO codes ( code_type, code, modifier, code_text ) VALUES ( 11, '254243321', '', 'STI/RTI Test - Chancroid' );
INSERT INTO codes ( code_type, code, modifier, code_text ) VALUES ( 11, '254243421', '', 'STI/RTI Test - Chlamydia' );
INSERT INTO codes ( code_type, code, modifier, code_text ) VALUES ( 11, '254243521', '', 'STI/RTI Test - Gonorrhea' );
INSERT INTO codes ( code_type, code, modifier, code_text ) VALUES ( 11, '254243999', '', 'STI/RTI Test - OTHER' );
INSERT INTO codes ( code_type, code, modifier, code_text ) VALUES ( 11, '254244000', '', 'STI/RTI LAB TESTS (CONTINUED)' );
INSERT INTO codes ( code_type, code, modifier, code_text ) VALUES ( 11, '254244121', '', 'STI/RTI Test - Herpes Simplex (HSV)' );
INSERT INTO codes ( code_type, code, modifier, code_text ) VALUES ( 11, '254244221', '', 'STI/RTI Test - Human Papillomavirus (HPV)' );
INSERT INTO codes ( code_type, code, modifier, code_text ) VALUES ( 11, '254244321', '', 'STI/RTI Test - Syphilis' );
INSERT INTO codes ( code_type, code, modifier, code_text ) VALUES ( 11, '254244421', '', 'STI/RTI Test - Trichomoniasis' );
INSERT INTO codes ( code_type, code, modifier, code_text ) VALUES ( 11, '254244521', '', 'STI/RTI Test - Hepatitis A and B' );
INSERT INTO codes ( code_type, code, modifier, code_text ) VALUES ( 11, '254244621', '', 'STI/RTI Test - Hepatitis A' );
INSERT INTO codes ( code_type, code, modifier, code_text ) VALUES ( 11, '254244721', '', 'STI/RTI Test - Hepatitis B' );
INSERT INTO codes ( code_type, code, modifier, code_text ) VALUES ( 11, '254244999', '', 'STI/RTI Test - OTHER' );
INSERT INTO codes ( code_type, code, modifier, code_text ) VALUES ( 11, '254245000', '', 'STI/RTI TREATMENT (including prophylactics)' );
INSERT INTO codes ( code_type, code, modifier, code_text ) VALUES ( 11, '254245122', '', 'STI/RTI Treatment - based on syndromic approach' );
INSERT INTO codes ( code_type, code, modifier, code_text ) VALUES ( 11, '254245222', '', 'STI/RTI Treatment - Etiological diagnosis with clinical treatment' );
INSERT INTO codes ( code_type, code, modifier, code_text ) VALUES ( 11, '254245322', '', 'STI/RTI Treatment - Hepatitis A vaccination' );
INSERT INTO codes ( code_type, code, modifier, code_text ) VALUES ( 11, '254245422', '', 'STI/RTI Treatment - Hepatitis B vaccination' );
INSERT INTO codes ( code_type, code, modifier, code_text ) VALUES ( 11, '254245522', '', 'STI/RTI Treatment - HPV vaccination' );
INSERT INTO codes ( code_type, code, modifier, code_text ) VALUES ( 11, '254245999', '', 'STI/RTI Treatment - OTHER' );
INSERT INTO codes ( code_type, code, modifier, code_text ) VALUES ( 11, '254246000', '', 'STI/RTI TREATMENT 1' );
INSERT INTO codes ( code_type, code, modifier, code_text ) VALUES ( 11, '254246122', '', 'STI Treatment for Bacterial Vaginosis based on positive lab test' );
INSERT INTO codes ( code_type, code, modifier, code_text ) VALUES ( 11, '254246222', '', 'STI Treatment for Candidiasis based on positive lab test' );
INSERT INTO codes ( code_type, code, modifier, code_text ) VALUES ( 11, '254246322', '', 'STI Treatment for Chancroid based on positive lab test' );
INSERT INTO codes ( code_type, code, modifier, code_text ) VALUES ( 11, '254246422', '', 'STI Treatment for Chlamydia based on positive lab tes' );
INSERT INTO codes ( code_type, code, modifier, code_text ) VALUES ( 11, '254246522', '', 'STI Treatment for Gonorrhea based on positive lab test' );
INSERT INTO codes ( code_type, code, modifier, code_text ) VALUES ( 11, '254247000', '', 'STI/RTI TREATMENT 2' );
INSERT INTO codes ( code_type, code, modifier, code_text ) VALUES ( 11, '254247122', '', 'STI Treatment for Herpes Simplex (HSV) based on positive lab test' );
INSERT INTO codes ( code_type, code, modifier, code_text ) VALUES ( 11, '254247222', '', 'STI Treatment for Human Papillomavirus (HPV) based on positive lab test' );
INSERT INTO codes ( code_type, code, modifier, code_text ) VALUES ( 11, '254247322', '', 'STI Treatment for Syphilis based on positive lab test' );
INSERT INTO codes ( code_type, code, modifier, code_text ) VALUES ( 11, '254247422', '', 'STI Treatment for Trichomoniasis based on positive lab test' );
INSERT INTO codes ( code_type, code, modifier, code_text ) VALUES ( 11, '254247522', '', 'STI Treatment for Hepatitis A based on positive lab test' );
INSERT INTO codes ( code_type, code, modifier, code_text ) VALUES ( 11, '254247622', '', 'STI Treatment for Hepatitis B based on positive lab test' );
INSERT INTO codes ( code_type, code, modifier, code_text ) VALUES ( 11, '254247999', '', 'STI/RTI Treatment  based on laboratory diagnostic tests -OTHER' );
INSERT INTO codes ( code_type, code, modifier, code_text ) VALUES ( 11, '255250000', '', 'GYNECOLOGICAL SERVICES' );
INSERT INTO codes ( code_type, code, modifier, code_text ) VALUES ( 11, '255250999', '', 'Gynecological Services - OTHER' );
INSERT INTO codes ( code_type, code, modifier, code_text ) VALUES ( 11, '255251000', '', 'GYNECOLOGICAL BIOPSY' );
INSERT INTO codes ( code_type, code, modifier, code_text ) VALUES ( 11, '255251123', '', 'Gynecological Biopsy - Conization' );
INSERT INTO codes ( code_type, code, modifier, code_text ) VALUES ( 11, '255251223', '', 'Gynecological Biopsy - Needle Biopsy' );
INSERT INTO codes ( code_type, code, modifier, code_text ) VALUES ( 11, '255251323', '', 'Gynecological Biopsy - Aspiration Biopsy' );
INSERT INTO codes ( code_type, code, modifier, code_text ) VALUES ( 11, '255251423', '', 'Gynecological Biopsy - Dilatation & Curretage (D&C)' );
INSERT INTO codes ( code_type, code, modifier, code_text ) VALUES ( 11, '255251999', '', 'Gynecological Biopsy - OTHER' );
INSERT INTO codes ( code_type, code, modifier, code_text ) VALUES ( 11, '255252000', '', 'GYNECOLOGICAL ENDOSCOPY' );
INSERT INTO codes ( code_type, code, modifier, code_text ) VALUES ( 11, '255252123', '', 'Gynecological Endoscopy - Colposcopy' );
INSERT INTO codes ( code_type, code, modifier, code_text ) VALUES ( 11, '255252223', '', 'Gynecological Endoscopy - Laparoscopy' );
INSERT INTO codes ( code_type, code, modifier, code_text ) VALUES ( 11, '255252323', '', 'Gynecological Endoscopy - Hysteroscopy' );
INSERT INTO codes ( code_type, code, modifier, code_text ) VALUES ( 11, '255252423', '', 'Gynecological Endoscopy - Culdoscopy' );
INSERT INTO codes ( code_type, code, modifier, code_text ) VALUES ( 11, '255252523', '', 'Gynecological Endoscopy - Hysteretomy' );
INSERT INTO codes ( code_type, code, modifier, code_text ) VALUES ( 11, '255252623', '', 'Gynecological Endoscopy - Ovariectomy' );
INSERT INTO codes ( code_type, code, modifier, code_text ) VALUES ( 11, '255252723', '', 'Gynecological Endoscopy - Mastectomy' );
INSERT INTO codes ( code_type, code, modifier, code_text ) VALUES ( 11, '255252823', '', 'Gynecological Endoscopy - Lumpectomy' );
INSERT INTO codes ( code_type, code, modifier, code_text ) VALUES ( 11, '255252999', '', 'Gynecological Endoscopy - OTHER' );
INSERT INTO codes ( code_type, code, modifier, code_text ) VALUES ( 11, '255253000', '', 'GYNECOLOGICAL DIAGNOSTIC IMAGING' );
INSERT INTO codes ( code_type, code, modifier, code_text ) VALUES ( 11, '255253121', '', 'Gynecological Diagnostic Imaging - Radiography - Hysterosalpingography' );
INSERT INTO codes ( code_type, code, modifier, code_text ) VALUES ( 11, '255253221', '', 'Gynecological Diagnostic Imaging - Radiography - Mammography' );
INSERT INTO codes ( code_type, code, modifier, code_text ) VALUES ( 11, '255253321', '', 'Gynecological Diagnostic Imaging - Ultrasonography' );
INSERT INTO codes ( code_type, code, modifier, code_text ) VALUES ( 11, '255253421', '', 'Gynecological Diagnostic Imaging - Tomography' );
INSERT INTO codes ( code_type, code, modifier, code_text ) VALUES ( 11, '255253521', '', 'Gynecological Diagnostic Imaging - Dexa, Bone Density Scan' );
INSERT INTO codes ( code_type, code, modifier, code_text ) VALUES ( 11, '255253999', '', 'Gynecological Diagnostic Imaging - OTHER' );
INSERT INTO codes ( code_type, code, modifier, code_text ) VALUES ( 11, '255254000', '', 'GYNECOLOGICAL EXAM DIAGNOSIS' );
INSERT INTO codes ( code_type, code, modifier, code_text ) VALUES ( 11, '255254121', '', 'Gynecological Exam - Manual Pelvic Exam (includes Palpation)' );
INSERT INTO codes ( code_type, code, modifier, code_text ) VALUES ( 11, '255254221', '', 'Gynecological Exam - Manual Breast Exam' );
INSERT INTO codes ( code_type, code, modifier, code_text ) VALUES ( 11, '255254321', '', 'Gynecological Exam - Cervical cancer screening (Pap smear)' );
INSERT INTO codes ( code_type, code, modifier, code_text ) VALUES ( 11, '255254421', '', 'Gynecological Exam - Consultation without pelvic exam' );
INSERT INTO codes ( code_type, code, modifier, code_text ) VALUES ( 11, '255254521', '', 'Gynecological Exam  Cervical cancer screening  Visual Inspection (VIA or VILI)' );
INSERT INTO codes ( code_type, code, modifier, code_text ) VALUES ( 11, '255254621', '', 'Gynecological Exam  Cervical cancer screening - Liquid-based cytology (sampling procedure)' );
INSERT INTO codes ( code_type, code, modifier, code_text ) VALUES ( 11, '255254721', '', 'Gynecological Exam  Cervical cancer screening - HPV DNA test (sampling procedure)' );
INSERT INTO codes ( code_type, code, modifier, code_text ) VALUES ( 11, '255254999', '', 'Gynecological Exam - OTHER' );
INSERT INTO codes ( code_type, code, modifier, code_text ) VALUES ( 11, '255255000', '', 'GYNECOLOGICAL CYTOLOGIC TESTS' );
INSERT INTO codes ( code_type, code, modifier, code_text ) VALUES ( 11, '255255121', '', 'Gynecological Lab Test - Cytology Analysis' );
INSERT INTO codes ( code_type, code, modifier, code_text ) VALUES ( 11, '255255221', '', 'Gynecological Lab Test - Cytology Analysis - Liquid-based cytology' );
INSERT INTO codes ( code_type, code, modifier, code_text ) VALUES ( 11, '255255321', '', 'Gynecological Lab Test -Cervical cancer screening - HPV DNA test' );
INSERT INTO codes ( code_type, code, modifier, code_text ) VALUES ( 11, '255255999', '', 'Gynecological Lab Test - OTHER' );
INSERT INTO codes ( code_type, code, modifier, code_text ) VALUES ( 11, '255256000', '', 'GYNECOLOGICAL THERAPIES' );
INSERT INTO codes ( code_type, code, modifier, code_text ) VALUES ( 11, '255256122', '', 'Gynecological Therapies - Menopause Consultations, Hormonal Replacement Therapy' );
INSERT INTO codes ( code_type, code, modifier, code_text ) VALUES ( 11, '255256222', '', 'Gynecological Therapies - Menstrual regulation' );
INSERT INTO codes ( code_type, code, modifier, code_text ) VALUES ( 11, '255256322', '', 'Gynecological Therapies - Female Genital Mutilation Treatment of Complications' );
INSERT INTO codes ( code_type, code, modifier, code_text ) VALUES ( 11, '255256422', '', 'Gynecological Therapies  Treatment of erratic menstruation' );
INSERT INTO codes ( code_type, code, modifier, code_text ) VALUES ( 11, '255256999', '', 'Gynecological Therapies - OTHER' );
INSERT INTO codes ( code_type, code, modifier, code_text ) VALUES ( 11, '255257000', '', 'GYNECOLOGICAL SURGERIES' );
INSERT INTO codes ( code_type, code, modifier, code_text ) VALUES ( 11, '255257123', '', 'Gynecological Surgeries - Cryosurgery - Cervical' );
INSERT INTO codes ( code_type, code, modifier, code_text ) VALUES ( 11, '255257223', '', 'Gynecological Surgeries - Cauterization (Cervical / Vaginal)' );
INSERT INTO codes ( code_type, code, modifier, code_text ) VALUES ( 11, '255257323', '', 'Gynecological Surgeries - Female Genital Mutilation Reconstructive Surgery' );
INSERT INTO codes ( code_type, code, modifier, code_text ) VALUES ( 11, '255257999', '', 'Gynecological Surgeries - OTHER' );
INSERT INTO codes ( code_type, code, modifier, code_text ) VALUES ( 11, '255258000', '', 'GYNECOLOGICAL COUNSELLING' );
INSERT INTO codes ( code_type, code, modifier, code_text ) VALUES ( 11, '255258129', '', 'Gynecological Counselling - Menopause Counseling' );
INSERT INTO codes ( code_type, code, modifier, code_text ) VALUES ( 11, '255258229', '', 'Gynecological Counselling - Pap Smear - Pre-test counselling' );
INSERT INTO codes ( code_type, code, modifier, code_text ) VALUES ( 11, '255258329', '', 'Gynecological Counselling - Pap Smear, Abnormal Results (post test follow-up)' );
INSERT INTO codes ( code_type, code, modifier, code_text ) VALUES ( 11, '255258429', '', 'Gynecological Counselling - Breast Exam Results, Mammography/Biopsy' );
INSERT INTO codes ( code_type, code, modifier, code_text ) VALUES ( 11, '255258529', '', 'Gynecological Counselling - Female Genital Mutilation' );
INSERT INTO codes ( code_type, code, modifier, code_text ) VALUES ( 11, '255258629', '', 'Gynecological Counselling-  Pap smear - Post-test counselling' );
INSERT INTO codes ( code_type, code, modifier, code_text ) VALUES ( 11, '255258999', '', 'Gynecological Counselling - OTHER' );
INSERT INTO codes ( code_type, code, modifier, code_text ) VALUES ( 11, '256260000', '', 'OBSTETRICS SERVICES' );
INSERT INTO codes ( code_type, code, modifier, code_text ) VALUES ( 11, '256260999', '', 'Obstetric Services - OTHER' );
INSERT INTO codes ( code_type, code, modifier, code_text ) VALUES ( 11, '256261000', '', 'OBSTETRICS - PRE NATAL DIAGNOSTIC PROCEDURES' );
INSERT INTO codes ( code_type, code, modifier, code_text ) VALUES ( 11, '256261121', '', 'Obstetrics - Pre-Natal Diagnostic - Fetoscopy' );
INSERT INTO codes ( code_type, code, modifier, code_text ) VALUES ( 11, '256261221', '', 'Obstetrics - Pre-Natal Diagnostic - Ultrasonography, Pre-natal' );
INSERT INTO codes ( code_type, code, modifier, code_text ) VALUES ( 11, '256261321', '', 'Obstetrics - Pre-Natal Diagnostic - Pelvimetry' );
INSERT INTO codes ( code_type, code, modifier, code_text ) VALUES ( 11, '256261421', '', 'Obstetrics - Pre-Natal Diagnostic - Placental Function Tests' );
INSERT INTO codes ( code_type, code, modifier, code_text ) VALUES ( 11, '256261999', '', 'Obstetrics - Pre-Natal Diagnostic - OTHER' );
INSERT INTO codes ( code_type, code, modifier, code_text ) VALUES ( 11, '256262000', '', 'OBSTETRICS - PRE NATAL CARE' );
INSERT INTO codes ( code_type, code, modifier, code_text ) VALUES ( 11, '256262121', '', 'Obstetrics - Pre natal Care - Uterine Monitoring' );
INSERT INTO codes ( code_type, code, modifier, code_text ) VALUES ( 11, '256262221', '', 'Obstetrics - Pre natal Care - Fetal Monitoring' );
INSERT INTO codes ( code_type, code, modifier, code_text ) VALUES ( 11, '256262422', '', 'Obstetrics - Pre natal Care - Immunisations' );
INSERT INTO codes ( code_type, code, modifier, code_text ) VALUES ( 11, '256262999', '', 'Obstetrics - Pre natal Care - OTHER' );
INSERT INTO codes ( code_type, code, modifier, code_text ) VALUES ( 11, '256263000', '', 'OBSTETRICS - PRE NATAL COUNSELLING' );
INSERT INTO codes ( code_type, code, modifier, code_text ) VALUES ( 11, '256263129', '', 'Obstetrics - Pre natal Counselling - Pre Natal Care Info' );
INSERT INTO codes ( code_type, code, modifier, code_text ) VALUES ( 11, '256263229', '', 'Obstetrics - Pre natal Counselling - Unplanned Pregnancy' );
INSERT INTO codes ( code_type, code, modifier, code_text ) VALUES ( 11, '256263329', '', 'Obstetrics - Pre natal Counselling - HIV Prevention and Testing' );
INSERT INTO codes ( code_type, code, modifier, code_text ) VALUES ( 11, '256263999', '', 'Obstetrics - Pre natal Counselling - OTHER' );
INSERT INTO codes ( code_type, code, modifier, code_text ) VALUES ( 11, '256264000', '', 'OBSTETRICS - PREGNANCY TESTS' );
INSERT INTO codes ( code_type, code, modifier, code_text ) VALUES ( 11, '256264121', '', 'Obstetrics - Pregnancy Tests - Agglutination Inhibition - Urine 1 test' );
INSERT INTO codes ( code_type, code, modifier, code_text ) VALUES ( 11, '256264221', '', 'Obstetrics - Pregnancy Tests - Radioimmunoasays - Blood test' );
INSERT INTO codes ( code_type, code, modifier, code_text ) VALUES ( 11, '256264999', '', 'Obstetrics - Pregnancy Tests - OTHER' );
INSERT INTO codes ( code_type, code, modifier, code_text ) VALUES ( 11, '256265000', '', 'OBSTETRICS - PRE NATAL LAB TESTS' );
INSERT INTO codes ( code_type, code, modifier, code_text ) VALUES ( 11, '256265121', '', 'Obstetrics - Pre-Natal Lab Tests - Urine 1' );
INSERT INTO codes ( code_type, code, modifier, code_text ) VALUES ( 11, '256265221', '', 'Obstetrics - Pre-Natal Lab Tests - Fasting blood sugar' );
INSERT INTO codes ( code_type, code, modifier, code_text ) VALUES ( 11, '256265321', '', 'Obstetrics - Pre-Natal Lab Tests - Hemoglobin (HB)' );
INSERT INTO codes ( code_type, code, modifier, code_text ) VALUES ( 11, '256265421', '', 'Obstetrics - Pre-Natal Lab Tests - Blood Type' );
INSERT INTO codes ( code_type, code, modifier, code_text ) VALUES ( 11, '256265521', '', 'Obstetrics - Pre-Natal Lab Tests - VDRL' );
INSERT INTO codes ( code_type, code, modifier, code_text ) VALUES ( 11, '256265621', '', 'Obstetrics - Pre-Natal Lab Tests - HIV' );
INSERT INTO codes ( code_type, code, modifier, code_text ) VALUES ( 11, '256265721', '', 'Obstetrics - Pre-Natal Lab Tests - Amniocentesis' );
INSERT INTO codes ( code_type, code, modifier, code_text ) VALUES ( 11, '256265821', '', 'Obstetrics - Pre-Natal Lab Tests - Chorionic Villi Sampling' );
INSERT INTO codes ( code_type, code, modifier, code_text ) VALUES ( 11, '256265999', '', 'Obstetrics - Pre-Natal Lab Tests - OTHER' );
INSERT INTO codes ( code_type, code, modifier, code_text ) VALUES ( 11, '256267000', '', 'OBSTETRICS - CHILD BIRTH' );
INSERT INTO codes ( code_type, code, modifier, code_text ) VALUES ( 11, '256267123', '', 'Obstetrics - Child Birth, Vaginal Delivery' );
INSERT INTO codes ( code_type, code, modifier, code_text ) VALUES ( 11, '256267223', '', 'Obstetrics - Child Birth, Cesarean Delivery' );
INSERT INTO codes ( code_type, code, modifier, code_text ) VALUES ( 11, '256267323', '', 'Obstetrics - Emergency Obstetric Care (EmOC)' );
INSERT INTO codes ( code_type, code, modifier, code_text ) VALUES ( 11, '256267999', '', 'Obstetrics - Surgery - OTHER' );
INSERT INTO codes ( code_type, code, modifier, code_text ) VALUES ( 11, '256268000', '', 'OBSTETRICS - POST NATAL CARE' );
INSERT INTO codes ( code_type, code, modifier, code_text ) VALUES ( 11, '256268120', '', 'Obstetrics - Post natal Care - Consultation' );
INSERT INTO codes ( code_type, code, modifier, code_text ) VALUES ( 11, '256268999', '', 'Obstetrics - Post natal Care - OTHER' );
INSERT INTO codes ( code_type, code, modifier, code_text ) VALUES ( 11, '256269000', '', 'OBSTETRICS - POST NATAL COUNSELLING' );
INSERT INTO codes ( code_type, code, modifier, code_text ) VALUES ( 11, '256269129', '', 'Obstetrics - Post-Natal Counselling - FP Methods' );
INSERT INTO codes ( code_type, code, modifier, code_text ) VALUES ( 11, '256269229', '', 'Obstetrics - Post-Natal Counselling - Breastfeeding Advice' );
INSERT INTO codes ( code_type, code, modifier, code_text ) VALUES ( 11, '256269329', '', 'Obstetrics - Post-Natal Counselling - HIV Counselling' );
INSERT INTO codes ( code_type, code, modifier, code_text ) VALUES ( 11, '256269999', '', 'Obstetrics - Post-Natal Counselling - OTHER' );
INSERT INTO codes ( code_type, code, modifier, code_text ) VALUES ( 11, '257270000', '', 'UROLOGICAL SERVICES' );
INSERT INTO codes ( code_type, code, modifier, code_text ) VALUES ( 11, '257270999', '', 'Urological Services - OTHER' );
INSERT INTO codes ( code_type, code, modifier, code_text ) VALUES ( 11, '257271000', '', 'UROLOGICAL ENDOSCOPY' );
INSERT INTO codes ( code_type, code, modifier, code_text ) VALUES ( 11, '257271123', '', 'Urological Endoscopy - Cystoscopy' );
INSERT INTO codes ( code_type, code, modifier, code_text ) VALUES ( 11, '257271223', '', 'Urological Endoscopy - Ureteroscopy' );
INSERT INTO codes ( code_type, code, modifier, code_text ) VALUES ( 11, '257271999', '', 'Urological Endoscopy - OTHER' );
INSERT INTO codes ( code_type, code, modifier, code_text ) VALUES ( 11, '257272000', '', 'UROLOGICAL DIAGNOSTIC IMAGING' );
INSERT INTO codes ( code_type, code, modifier, code_text ) VALUES ( 11, '257272121', '', 'Urological Diagnostic Imaging - Urography' );
INSERT INTO codes ( code_type, code, modifier, code_text ) VALUES ( 11, '257272999', '', 'Urological Diagnostic Imaging - OTHER' );
INSERT INTO codes ( code_type, code, modifier, code_text ) VALUES ( 11, '257273000', '', 'UROLOGICAL DIAGNOSIS (OTHER )' );
INSERT INTO codes ( code_type, code, modifier, code_text ) VALUES ( 11, '257273121', '', 'Urological Diagnosis Other - Exam' );
INSERT INTO codes ( code_type, code, modifier, code_text ) VALUES ( 11, '257273221', '', 'Urological Diagnosis Other - Prostate Cancer Screening' );
INSERT INTO codes ( code_type, code, modifier, code_text ) VALUES ( 11, '257273321', '', 'Urological Diagnosis Other - Peniscopy' );
INSERT INTO codes ( code_type, code, modifier, code_text ) VALUES ( 11, '257273421', '', 'Urological Diagnosis Other - Other Urogenital Services' );
INSERT INTO codes ( code_type, code, modifier, code_text ) VALUES ( 11, '257273999', '', 'Urological Diagnosis Other - OTHER' );
INSERT INTO codes ( code_type, code, modifier, code_text ) VALUES ( 11, '257274000', '', 'UROLOGICAL SURGERY' );
INSERT INTO codes ( code_type, code, modifier, code_text ) VALUES ( 11, '257274123', '', 'Urological Male Surgery - Biopsy' );
INSERT INTO codes ( code_type, code, modifier, code_text ) VALUES ( 11, '257274223', '', 'Urological Male Surgery - Circumcision' );
INSERT INTO codes ( code_type, code, modifier, code_text ) VALUES ( 11, '257274323', '', 'Urological Male Surgery - Other Surgical Services' );
INSERT INTO codes ( code_type, code, modifier, code_text ) VALUES ( 11, '257274999', '', 'Urological Male Surgery - OTHER' );
INSERT INTO codes ( code_type, code, modifier, code_text ) VALUES ( 11, '258280000', '', 'INFERTILITY SERVICES' );
INSERT INTO codes ( code_type, code, modifier, code_text ) VALUES ( 11, '258280999', '', 'Infertility/Subfertility - OTHER' );
INSERT INTO codes ( code_type, code, modifier, code_text ) VALUES ( 11, '258281000', '', 'INFERTILITY BIOPSY' );
INSERT INTO codes ( code_type, code, modifier, code_text ) VALUES ( 11, '258281123', '', 'Infertility Biopsy - Endometrial biopsy' );
INSERT INTO codes ( code_type, code, modifier, code_text ) VALUES ( 11, '258281223', '', 'Infertility Biopsy - Testicular biopsy' );
INSERT INTO codes ( code_type, code, modifier, code_text ) VALUES ( 11, '258281999', '', 'Infertility Biopsy - OTHER' );
INSERT INTO codes ( code_type, code, modifier, code_text ) VALUES ( 11, '258282000', '', 'INFERTILITY ENDOSCOPY' );
INSERT INTO codes ( code_type, code, modifier, code_text ) VALUES ( 11, '258282123', '', 'Infertility Endoscopy - Laparoscopy' );
INSERT INTO codes ( code_type, code, modifier, code_text ) VALUES ( 11, '258282223', '', 'Infertility Endoscopy - Histeroscopy' );
INSERT INTO codes ( code_type, code, modifier, code_text ) VALUES ( 11, '258282999', '', 'Infertility Endoscopy - OTHER' );
INSERT INTO codes ( code_type, code, modifier, code_text ) VALUES ( 11, '258283000', '', 'INFERTILITY DIAGNOSTIC IMAGING' );
INSERT INTO codes ( code_type, code, modifier, code_text ) VALUES ( 11, '258283121', '', 'Infertility Diagnostic Imaging - Histerosalpingography' );
INSERT INTO codes ( code_type, code, modifier, code_text ) VALUES ( 11, '258283221', '', 'Infertility Diagnostic Imaging - Ovarian ultrasound' );
INSERT INTO codes ( code_type, code, modifier, code_text ) VALUES ( 11, '258283321', '', 'Infertility Diagnostic Imaging - Transvaginal ecography' );
INSERT INTO codes ( code_type, code, modifier, code_text ) VALUES ( 11, '258283999', '', 'Infertility Diagnostic Imaging - OTHER' );
INSERT INTO codes ( code_type, code, modifier, code_text ) VALUES ( 11, '258284000', '', 'INFERTILITY LAB TESTS' );
INSERT INTO codes ( code_type, code, modifier, code_text ) VALUES ( 11, '258284121', '', 'Infertility Lab Test - Post-coital test or Sims-Huhner test' );
INSERT INTO codes ( code_type, code, modifier, code_text ) VALUES ( 11, '258284221', '', 'Infertility Lab Test - Fallopian Tube Patency Tests' );
INSERT INTO codes ( code_type, code, modifier, code_text ) VALUES ( 11, '258284321', '', 'Infertility Lab Test - Clomiphene citrate challenge test (CCCT)' );
INSERT INTO codes ( code_type, code, modifier, code_text ) VALUES ( 11, '258284421', '', 'Infertility Lab Test - Semen analysis' );
INSERT INTO codes ( code_type, code, modifier, code_text ) VALUES ( 11, '258284521', '', 'Infertility Lab Test - Basal Temperature' );
INSERT INTO codes ( code_type, code, modifier, code_text ) VALUES ( 11, '258284621', '', 'Infertility Lab Test - Mucose Analysis' );
INSERT INTO codes ( code_type, code, modifier, code_text ) VALUES ( 11, '258284721', '', 'Infertility Lab Test - Sperm Count' );
INSERT INTO codes ( code_type, code, modifier, code_text ) VALUES ( 11, '258284821', '', 'Infertility Lab Test - Spermiogram' );
INSERT INTO codes ( code_type, code, modifier, code_text ) VALUES ( 11, '258284921', '', 'Infertility Lab Test - Hormonal analysis' );
INSERT INTO codes ( code_type, code, modifier, code_text ) VALUES ( 11, '258284999', '', 'Infertility Lab Test - OTHER' );
INSERT INTO codes ( code_type, code, modifier, code_text ) VALUES ( 11, '258286000', '', 'INFERTILITY TREATMENT' );
INSERT INTO codes ( code_type, code, modifier, code_text ) VALUES ( 11, '258286122', '', 'Infertility Treatment - Ovulation Induction' );
INSERT INTO codes ( code_type, code, modifier, code_text ) VALUES ( 11, '258286222', '', 'Infertility Treatment - Embryo Transfer' );
INSERT INTO codes ( code_type, code, modifier, code_text ) VALUES ( 11, '258286322', '', 'Infertility Treatment - Fertilization in Vitro' );
INSERT INTO codes ( code_type, code, modifier, code_text ) VALUES ( 11, '258286422', '', 'Infertility Treatment - Gamete Intrafallopian Transfer' );
INSERT INTO codes ( code_type, code, modifier, code_text ) VALUES ( 11, '258286522', '', 'Infertility Treatment - Artificial Insemination' );
INSERT INTO codes ( code_type, code, modifier, code_text ) VALUES ( 11, '258286622', '', 'Infertility Treatment - Oocyte Donation' );
INSERT INTO codes ( code_type, code, modifier, code_text ) VALUES ( 11, '258286722', '', 'Infertility Treatment - Zygote Intrafallopian Transfer' );
INSERT INTO codes ( code_type, code, modifier, code_text ) VALUES ( 11, '258286999', '', 'Infertility Treatment - OTHER' );
INSERT INTO codes ( code_type, code, modifier, code_text ) VALUES ( 11, '258288000', '', 'INFERTILITY CONSULTATION' );
INSERT INTO codes ( code_type, code, modifier, code_text ) VALUES ( 11, '258288120', '', 'Infertility/Subfertility Consultation' );
INSERT INTO codes ( code_type, code, modifier, code_text ) VALUES ( 11, '258288999', '', 'Infertility/Subfertility Consultation - OTHER' );
INSERT INTO codes ( code_type, code, modifier, code_text ) VALUES ( 11, '258289000', '', 'INFERTILITY COUNSELLING' );
INSERT INTO codes ( code_type, code, modifier, code_text ) VALUES ( 11, '258289129', '', 'Infertility/Subfertility  Counseling' );
INSERT INTO codes ( code_type, code, modifier, code_text ) VALUES ( 11, '258289999', '', 'Infertility/Subfertility  Counseling - OTHER' );
INSERT INTO codes ( code_type, code, modifier, code_text ) VALUES ( 11, '260290000', '', 'OTHER SPECIALIZED COUNSELLING SERVICES' );
INSERT INTO codes ( code_type, code, modifier, code_text ) VALUES ( 11, '260290999', '', 'Other Specialized Counselling Services - OTHER' );
INSERT INTO codes ( code_type, code, modifier, code_text ) VALUES ( 11, '261291000', '', 'COUNSELLING - GBV' );
INSERT INTO codes ( code_type, code, modifier, code_text ) VALUES ( 11, '261291129', '', 'Counselling - GBV - Individual Counseling' );
INSERT INTO codes ( code_type, code, modifier, code_text ) VALUES ( 11, '261291229', '', 'Counselling - GBV - Support Groups for Survivors' );
INSERT INTO codes ( code_type, code, modifier, code_text ) VALUES ( 11, '261291329', '', 'Counselling - GBV - Legal Counseling' );
INSERT INTO codes ( code_type, code, modifier, code_text ) VALUES ( 11, '261291429', '', 'Counselling - GBV - Intimate Partner Sexual Abuse' );
INSERT INTO codes ( code_type, code, modifier, code_text ) VALUES ( 11, '261291529', '', 'Counselling - GBV - Intimate Partner Physical  Abuse' );
INSERT INTO codes ( code_type, code, modifier, code_text ) VALUES ( 11, '261291629', '', 'Counselling - GBV - Intimate Partner Emotional Abuse' );
INSERT INTO codes ( code_type, code, modifier, code_text ) VALUES ( 11, '261291729', '', 'Counselling - GBV - NonIntimate Partner Sexual Assalt/Rape' );
INSERT INTO codes ( code_type, code, modifier, code_text ) VALUES ( 11, '261291829', '', 'Counselling - GBV - Screening Only' );
INSERT INTO codes ( code_type, code, modifier, code_text ) VALUES ( 11, '261291999', '', 'Counselling - GBV - OTHER' );
INSERT INTO codes ( code_type, code, modifier, code_text ) VALUES ( 11, '262292000', '', 'COUNSELLING - DOMESTIC VIOLENCE' );
INSERT INTO codes ( code_type, code, modifier, code_text ) VALUES ( 11, '262292129', '', 'Counselling - Domestic Violence' );
INSERT INTO codes ( code_type, code, modifier, code_text ) VALUES ( 11, '262292229', '', 'Counselling - Domestic Violence, Screening' );
INSERT INTO codes ( code_type, code, modifier, code_text ) VALUES ( 11, '262292999', '', 'Counselling - Domestic Violence - OTHER' );
INSERT INTO codes ( code_type, code, modifier, code_text ) VALUES ( 11, '262293000', '', 'COUNSELLING - FAMILY' );
INSERT INTO codes ( code_type, code, modifier, code_text ) VALUES ( 11, '262293129', '', 'Counselling - Family - Parent/Child Relationship' );
INSERT INTO codes ( code_type, code, modifier, code_text ) VALUES ( 11, '262293229', '', 'Counselling - Family- Family Conflict' );
INSERT INTO codes ( code_type, code, modifier, code_text ) VALUES ( 11, '262293329', '', 'Counselling - Family, Delinquency' );
INSERT INTO codes ( code_type, code, modifier, code_text ) VALUES ( 11, '262293999', '', 'Counselling - Family - OTHER' );
INSERT INTO codes ( code_type, code, modifier, code_text ) VALUES ( 11, '262294000', '', 'COUNSELLING - PRE-MARITAL / MARITAL' );
INSERT INTO codes ( code_type, code, modifier, code_text ) VALUES ( 11, '262294129', '', 'Counselling - Pre-Marital including Pre-Marital Family Planning' );
INSERT INTO codes ( code_type, code, modifier, code_text ) VALUES ( 11, '262294229', '', 'Counselling - Marital - Relationship, Partner Negotiation' );
INSERT INTO codes ( code_type, code, modifier, code_text ) VALUES ( 11, '262294329', '', 'Counselling - Marital - Sexuality / Sexual Disfunction' );
INSERT INTO codes ( code_type, code, modifier, code_text ) VALUES ( 11, '262294999', '', 'Counselling - Marital - OTHER' );
INSERT INTO codes ( code_type, code, modifier, code_text ) VALUES ( 11, '262295000', '', 'COUNSELLING - YOUTH (less than 25 yrs)' );
INSERT INTO codes ( code_type, code, modifier, code_text ) VALUES ( 11, '262295129', '', 'Counselling - Youth - Life Skills Counseling' );
INSERT INTO codes ( code_type, code, modifier, code_text ) VALUES ( 11, '262295229', '', 'Counselling - Youth - Sexuality' );
INSERT INTO codes ( code_type, code, modifier, code_text ) VALUES ( 11, '262295329', '', 'Counselling - Youth - Telephone / Internet Hotline Counseling' );
INSERT INTO codes ( code_type, code, modifier, code_text ) VALUES ( 11, '262295429', '', 'Counselling - Youth - SRH Counselling' );
INSERT INTO codes ( code_type, code, modifier, code_text ) VALUES ( 11, '262295999', '', 'Counselling - Youth - OTHER' );
INSERT INTO codes ( code_type, code, modifier, code_text ) VALUES ( 11, '262296000', '', 'COUNSELLING - MALE' );
INSERT INTO codes ( code_type, code, modifier, code_text ) VALUES ( 11, '262296129', '', 'Counselling - Male - SRH Counselling' );
INSERT INTO codes ( code_type, code, modifier, code_text ) VALUES ( 11, '262296229', '', 'Counselling - Male - Sexuality' );
INSERT INTO codes ( code_type, code, modifier, code_text ) VALUES ( 11, '262296329', '', 'Counselling - Male - GBV' );
INSERT INTO codes ( code_type, code, modifier, code_text ) VALUES ( 11, '262296999', '', 'Counselling - Male - OTHER' );
INSERT INTO codes ( code_type, code, modifier, code_text ) VALUES ( 11, '263297000', '', 'COUNSELLING SERVICES (OTHER)' );
INSERT INTO codes ( code_type, code, modifier, code_text ) VALUES ( 11, '263297129', '', 'Counseling - Other - Sexuality Issues ( 25 years and over)' );
INSERT INTO codes ( code_type, code, modifier, code_text ) VALUES ( 11, '263297999', '', 'Counseling - OTHER' );
INSERT INTO codes ( code_type, code, modifier, code_text ) VALUES ( 11, '269298000', '', 'OTHER SRH MEDICAL SERVICES' );
INSERT INTO codes ( code_type, code, modifier, code_text ) VALUES ( 11, '269298120', '', 'Other SRH medical services - Consultation' );
INSERT INTO codes ( code_type, code, modifier, code_text ) VALUES ( 11, '269298221', '', 'Other SRH medical services - Diagnostic Test' );
INSERT INTO codes ( code_type, code, modifier, code_text ) VALUES ( 11, '269298322', '', 'Other SRH medical services - Therapy / Treatment' );
INSERT INTO codes ( code_type, code, modifier, code_text ) VALUES ( 11, '269298423', '', 'Other SRH medical services - Surgery' );
INSERT INTO codes ( code_type, code, modifier, code_text ) VALUES ( 11, '269298999', '', 'Other SRH medical services - OTHER' );
INSERT INTO codes ( code_type, code, modifier, code_text ) VALUES ( 11, '370000000', '', 'MEDICAL SPECIALTY SERVICIES' );
INSERT INTO codes ( code_type, code, modifier, code_text ) VALUES ( 11, '371300000', '', 'MEDICAL SPECIALTIES - SYSTEM ORIENTED SERVICES' );
INSERT INTO codes ( code_type, code, modifier, code_text ) VALUES ( 11, '371300999', '', 'Medical Specialties - System Oriented Services - OTHER' );
INSERT INTO codes ( code_type, code, modifier, code_text ) VALUES ( 11, '371301000', '', 'ANGIOLOGY' );
INSERT INTO codes ( code_type, code, modifier, code_text ) VALUES ( 11, '371301130', '', 'Angiology - Consultation' );
INSERT INTO codes ( code_type, code, modifier, code_text ) VALUES ( 11, '371301231', '', 'Angiology - Diagnostic Test' );
INSERT INTO codes ( code_type, code, modifier, code_text ) VALUES ( 11, '371301332', '', 'Angiology - Therapy / Treatment' );
INSERT INTO codes ( code_type, code, modifier, code_text ) VALUES ( 11, '371301433', '', 'Angiology - Surgery' );
INSERT INTO codes ( code_type, code, modifier, code_text ) VALUES ( 11, '371301999', '', 'Angiology - OTHER' );
INSERT INTO codes ( code_type, code, modifier, code_text ) VALUES ( 11, '371311000', '', 'CARDIOLOGY' );
INSERT INTO codes ( code_type, code, modifier, code_text ) VALUES ( 11, '371311130', '', 'Cardiology - Consultation' );
INSERT INTO codes ( code_type, code, modifier, code_text ) VALUES ( 11, '371311231', '', 'Cardiology - Diagnostic EKG' );
INSERT INTO codes ( code_type, code, modifier, code_text ) VALUES ( 11, '371311332', '', 'Cardiology - Therapy / Treatment' );
INSERT INTO codes ( code_type, code, modifier, code_text ) VALUES ( 11, '371311433', '', 'Cardiology - Surgery' );
INSERT INTO codes ( code_type, code, modifier, code_text ) VALUES ( 11, '371311999', '', 'Cardiology - OTHER' );
INSERT INTO codes ( code_type, code, modifier, code_text ) VALUES ( 11, '371321000', '', 'DENTISTRY' );
INSERT INTO codes ( code_type, code, modifier, code_text ) VALUES ( 11, '371321131', '', 'Dentistry - Diagnosis' );
INSERT INTO codes ( code_type, code, modifier, code_text ) VALUES ( 11, '371321232', '', 'Dentistry -Therapy / Treatment' );
INSERT INTO codes ( code_type, code, modifier, code_text ) VALUES ( 11, '371321332', '', 'Dentistry - Orthodontics' );
INSERT INTO codes ( code_type, code, modifier, code_text ) VALUES ( 11, '371321432', '', 'Dentistry - Periodontics' );
INSERT INTO codes ( code_type, code, modifier, code_text ) VALUES ( 11, '371321533', '', 'Dentistry - Surgery' );
INSERT INTO codes ( code_type, code, modifier, code_text ) VALUES ( 11, '371321999', '', 'Dentistry - OTHER' );
INSERT INTO codes ( code_type, code, modifier, code_text ) VALUES ( 11, '371331000', '', 'DERMATOLOGY' );
INSERT INTO codes ( code_type, code, modifier, code_text ) VALUES ( 11, '371331130', '', 'Dermatology - Consultation' );
INSERT INTO codes ( code_type, code, modifier, code_text ) VALUES ( 11, '371331231', '', 'Dermatology - Diagnostic Test' );
INSERT INTO codes ( code_type, code, modifier, code_text ) VALUES ( 11, '371331332', '', 'Dermatology - Therapy / Treatment' );
INSERT INTO codes ( code_type, code, modifier, code_text ) VALUES ( 11, '371331433', '', 'Dermatology - Surgery' );
INSERT INTO codes ( code_type, code, modifier, code_text ) VALUES ( 11, '371331999', '', 'Dermatology - OTHER' );
INSERT INTO codes ( code_type, code, modifier, code_text ) VALUES ( 11, '371341000', '', 'ENDOCRINOLOGY' );
INSERT INTO codes ( code_type, code, modifier, code_text ) VALUES ( 11, '371341130', '', 'Endocrinology - Consultation' );
INSERT INTO codes ( code_type, code, modifier, code_text ) VALUES ( 11, '371341231', '', 'Endocrinology - Diagnostic Test' );
INSERT INTO codes ( code_type, code, modifier, code_text ) VALUES ( 11, '371341332', '', 'Endocrinology - Therapy / Treatment' );
INSERT INTO codes ( code_type, code, modifier, code_text ) VALUES ( 11, '371341433', '', 'Endocrinology - Surgery' );
INSERT INTO codes ( code_type, code, modifier, code_text ) VALUES ( 11, '371341999', '', 'Endocrinology - OTHER' );
INSERT INTO codes ( code_type, code, modifier, code_text ) VALUES ( 11, '371351000', '', 'GASTROENTEROLOGY' );
INSERT INTO codes ( code_type, code, modifier, code_text ) VALUES ( 11, '371351130', '', 'Gastroenterology - Consultation' );
INSERT INTO codes ( code_type, code, modifier, code_text ) VALUES ( 11, '371351231', '', 'Gastroenterology - Diagnostic Test' );
INSERT INTO codes ( code_type, code, modifier, code_text ) VALUES ( 11, '371351332', '', 'Gastroenterology - Therapy / Treatment' );
INSERT INTO codes ( code_type, code, modifier, code_text ) VALUES ( 11, '371351433', '', 'Gastroenterology - Surgery' );
INSERT INTO codes ( code_type, code, modifier, code_text ) VALUES ( 11, '371351999', '', 'Gastroenterology - OTHER' );
INSERT INTO codes ( code_type, code, modifier, code_text ) VALUES ( 11, '371361000', '', 'GENETICS' );
INSERT INTO codes ( code_type, code, modifier, code_text ) VALUES ( 11, '371361129', '', 'Genetics - Counselling' );
INSERT INTO codes ( code_type, code, modifier, code_text ) VALUES ( 11, '371361230', '', 'Genetics - Consultation' );
INSERT INTO codes ( code_type, code, modifier, code_text ) VALUES ( 11, '371361331', '', 'Genetics - Diagnostic Test' );
INSERT INTO codes ( code_type, code, modifier, code_text ) VALUES ( 11, '371361432', '', 'Genetics - Therapy / Treatment' );
INSERT INTO codes ( code_type, code, modifier, code_text ) VALUES ( 11, '371361999', '', 'Genetics - OTHER' );
INSERT INTO codes ( code_type, code, modifier, code_text ) VALUES ( 11, '371371000', '', 'NEPHROLOGY' );
INSERT INTO codes ( code_type, code, modifier, code_text ) VALUES ( 11, '371371130', '', 'Nephrology - Consultation' );
INSERT INTO codes ( code_type, code, modifier, code_text ) VALUES ( 11, '371371231', '', 'Nephrology - Diagnostic Test' );
INSERT INTO codes ( code_type, code, modifier, code_text ) VALUES ( 11, '371371332', '', 'Nephrology - Therapy / Treatment' );
INSERT INTO codes ( code_type, code, modifier, code_text ) VALUES ( 11, '371371433', '', 'Nephrology - Surgery' );
INSERT INTO codes ( code_type, code, modifier, code_text ) VALUES ( 11, '371371999', '', 'Nephrology - OTHER' );
INSERT INTO codes ( code_type, code, modifier, code_text ) VALUES ( 11, '371381000', '', 'NEUMOLOGY' );
INSERT INTO codes ( code_type, code, modifier, code_text ) VALUES ( 11, '371381130', '', 'Neumology - Consultation' );
INSERT INTO codes ( code_type, code, modifier, code_text ) VALUES ( 11, '371381231', '', 'Neumology - Diagnostic Test' );
INSERT INTO codes ( code_type, code, modifier, code_text ) VALUES ( 11, '371381332', '', 'Neumology - Therapy / Treatment' );
INSERT INTO codes ( code_type, code, modifier, code_text ) VALUES ( 11, '371381433', '', 'Neumology - Surgery' );
INSERT INTO codes ( code_type, code, modifier, code_text ) VALUES ( 11, '371381999', '', 'Neumology - OTHER' );
INSERT INTO codes ( code_type, code, modifier, code_text ) VALUES ( 11, '371391000', '', 'NEUROLOGY' );
INSERT INTO codes ( code_type, code, modifier, code_text ) VALUES ( 11, '371391130', '', 'Neurology - Consultation' );
INSERT INTO codes ( code_type, code, modifier, code_text ) VALUES ( 11, '371391231', '', 'Neurology - Diagnostic Exam' );
INSERT INTO codes ( code_type, code, modifier, code_text ) VALUES ( 11, '371391332', '', 'Neurology - Therapy / Treatment' );
INSERT INTO codes ( code_type, code, modifier, code_text ) VALUES ( 11, '371391433', '', 'Neurology - Surgery' );
INSERT INTO codes ( code_type, code, modifier, code_text ) VALUES ( 11, '371391999', '', 'Neurology - OTHER' );
INSERT INTO codes ( code_type, code, modifier, code_text ) VALUES ( 11, '371401000', '', 'OPHTALMOLOGY' );
INSERT INTO codes ( code_type, code, modifier, code_text ) VALUES ( 11, '371401130', '', 'Ophtalmology - Consultation' );
INSERT INTO codes ( code_type, code, modifier, code_text ) VALUES ( 11, '371401231', '', 'Ophtalmology - Diagnostic Exam' );
INSERT INTO codes ( code_type, code, modifier, code_text ) VALUES ( 11, '371401332', '', 'Ophtalmology - Therapy / Treatment' );
INSERT INTO codes ( code_type, code, modifier, code_text ) VALUES ( 11, '371401433', '', 'Ophtalmology - Surgery' );
INSERT INTO codes ( code_type, code, modifier, code_text ) VALUES ( 11, '371401999', '', 'Ophtalmology - OTHER' );
INSERT INTO codes ( code_type, code, modifier, code_text ) VALUES ( 11, '371411000', '', 'ORTHOPEDICS' );
INSERT INTO codes ( code_type, code, modifier, code_text ) VALUES ( 11, '371411130', '', 'Orthopedics - Consultation' );
INSERT INTO codes ( code_type, code, modifier, code_text ) VALUES ( 11, '371411231', '', 'Orthopedics - Diagnostic Exam' );
INSERT INTO codes ( code_type, code, modifier, code_text ) VALUES ( 11, '371411332', '', 'Orthopedics - Therapy / Treatment' );
INSERT INTO codes ( code_type, code, modifier, code_text ) VALUES ( 11, '371411433', '', 'Orthopedics - Surgery' );
INSERT INTO codes ( code_type, code, modifier, code_text ) VALUES ( 11, '371411999', '', 'Orthopedics - OTHER' );
INSERT INTO codes ( code_type, code, modifier, code_text ) VALUES ( 11, '371421000', '', 'OTHORHINOLARINGOLOGY' );
INSERT INTO codes ( code_type, code, modifier, code_text ) VALUES ( 11, '371421130', '', 'Othorhinolaringology - Consultation' );
INSERT INTO codes ( code_type, code, modifier, code_text ) VALUES ( 11, '371421231', '', 'Othorhinolaringology - Diagnostic Exam' );
INSERT INTO codes ( code_type, code, modifier, code_text ) VALUES ( 11, '371421332', '', 'Othorhinolaringology - Therapy / Treatment' );
INSERT INTO codes ( code_type, code, modifier, code_text ) VALUES ( 11, '371421433', '', 'Othorhinolaringology - Surgery' );
INSERT INTO codes ( code_type, code, modifier, code_text ) VALUES ( 11, '371421999', '', 'Othorhinolaringology - OTHER' );
INSERT INTO codes ( code_type, code, modifier, code_text ) VALUES ( 11, '371431000', '', 'PODOLOGY' );
INSERT INTO codes ( code_type, code, modifier, code_text ) VALUES ( 11, '371431130', '', 'Podology - Consultation' );
INSERT INTO codes ( code_type, code, modifier, code_text ) VALUES ( 11, '371431231', '', 'Podology - Diagnostic Exam' );
INSERT INTO codes ( code_type, code, modifier, code_text ) VALUES ( 11, '371431332', '', 'Podology - Therapy / Treatment' );
INSERT INTO codes ( code_type, code, modifier, code_text ) VALUES ( 11, '371431433', '', 'Podology - Surgery' );
INSERT INTO codes ( code_type, code, modifier, code_text ) VALUES ( 11, '371431999', '', 'Podology - OTHER' );
INSERT INTO codes ( code_type, code, modifier, code_text ) VALUES ( 11, '371441000', '', 'RHEUMATOLOGY' );
INSERT INTO codes ( code_type, code, modifier, code_text ) VALUES ( 11, '371441130', '', 'Rheumatology - Consultation' );
INSERT INTO codes ( code_type, code, modifier, code_text ) VALUES ( 11, '371441231', '', 'Rheumatology - Diagnostic Exam' );
INSERT INTO codes ( code_type, code, modifier, code_text ) VALUES ( 11, '371441332', '', 'Rheumatology - Therapy / Treatment' );
INSERT INTO codes ( code_type, code, modifier, code_text ) VALUES ( 11, '371441433', '', 'Rheumatology - Surgery' );
INSERT INTO codes ( code_type, code, modifier, code_text ) VALUES ( 11, '371441999', '', 'Rheumatology - OTHER' );
INSERT INTO codes ( code_type, code, modifier, code_text ) VALUES ( 11, '372500000', '', 'MEDICAL SPECIALTIES - DISEASE ORIENTED SERVICES' );
INSERT INTO codes ( code_type, code, modifier, code_text ) VALUES ( 11, '372500999', '', 'Medical Specialties - Disease Oriented Services - OTHER' );
INSERT INTO codes ( code_type, code, modifier, code_text ) VALUES ( 11, '372501000', '', 'OPTOMETRY' );
INSERT INTO codes ( code_type, code, modifier, code_text ) VALUES ( 11, '372501130', '', 'Optometry - Consultation' );
INSERT INTO codes ( code_type, code, modifier, code_text ) VALUES ( 11, '372501231', '', 'Optometry - Diagnostic Exam' );
INSERT INTO codes ( code_type, code, modifier, code_text ) VALUES ( 11, '372501999', '', 'Optometry - OTHER' );
INSERT INTO codes ( code_type, code, modifier, code_text ) VALUES ( 11, '372511000', '', 'PSYCHIATRY' );
INSERT INTO codes ( code_type, code, modifier, code_text ) VALUES ( 11, '372511131', '', 'Psychiatry - Diagnostic consultation' );
INSERT INTO codes ( code_type, code, modifier, code_text ) VALUES ( 11, '372511232', '', 'Psychiatry - Therapy / Treatment' );
INSERT INTO codes ( code_type, code, modifier, code_text ) VALUES ( 11, '372511999', '', 'Psychiatry - OTHER' );
INSERT INTO codes ( code_type, code, modifier, code_text ) VALUES ( 11, '372521000', '', 'PSYCHOLOGY' );
INSERT INTO codes ( code_type, code, modifier, code_text ) VALUES ( 11, '372521131', '', 'Psychology - Diagnostic consultation' );
INSERT INTO codes ( code_type, code, modifier, code_text ) VALUES ( 11, '372521232', '', 'Psychology - Therapy / Treatment' );
INSERT INTO codes ( code_type, code, modifier, code_text ) VALUES ( 11, '372521999', '', 'Psychology - OTHER' );
INSERT INTO codes ( code_type, code, modifier, code_text ) VALUES ( 11, '372531000', '', 'RADIOLOGY' );
INSERT INTO codes ( code_type, code, modifier, code_text ) VALUES ( 11, '372531131', '', 'Radiology - Diagnostic Imaging' );
INSERT INTO codes ( code_type, code, modifier, code_text ) VALUES ( 11, '372531232', '', 'Radiology - Therapy / Treatment' );
INSERT INTO codes ( code_type, code, modifier, code_text ) VALUES ( 11, '372531999', '', 'Radiology - OTHER' );
INSERT INTO codes ( code_type, code, modifier, code_text ) VALUES ( 11, '372541000', '', 'ONCOLOGY' );
INSERT INTO codes ( code_type, code, modifier, code_text ) VALUES ( 11, '372541131', '', 'Oncology - Diagnostic Test' );
INSERT INTO codes ( code_type, code, modifier, code_text ) VALUES ( 11, '372541232', '', 'Oncology - Therapy / Treatment' );
INSERT INTO codes ( code_type, code, modifier, code_text ) VALUES ( 11, '372541333', '', 'Oncology - Surgery' );
INSERT INTO codes ( code_type, code, modifier, code_text ) VALUES ( 11, '372541999', '', 'Oncology - OTHER' );
INSERT INTO codes ( code_type, code, modifier, code_text ) VALUES ( 11, '372551000', '', 'ALLERGY' );
INSERT INTO codes ( code_type, code, modifier, code_text ) VALUES ( 11, '372551130', '', 'Allergy - Consultation' );
INSERT INTO codes ( code_type, code, modifier, code_text ) VALUES ( 11, '372551231', '', 'Allergy - Diagnostic Test' );
INSERT INTO codes ( code_type, code, modifier, code_text ) VALUES ( 11, '372551332', '', 'Allergy - Therapy / Treatment' );
INSERT INTO codes ( code_type, code, modifier, code_text ) VALUES ( 11, '372551999', '', 'Allergy - OTHER' );
INSERT INTO codes ( code_type, code, modifier, code_text ) VALUES ( 11, '372561000', '', 'IMMUNOLOGY' );
INSERT INTO codes ( code_type, code, modifier, code_text ) VALUES ( 11, '372561130', '', 'Immunology - Consultation' );
INSERT INTO codes ( code_type, code, modifier, code_text ) VALUES ( 11, '372561231', '', 'Immunology - Diagnostic Test' );
INSERT INTO codes ( code_type, code, modifier, code_text ) VALUES ( 11, '372561999', '', 'Immunology - OTHER' );
INSERT INTO codes ( code_type, code, modifier, code_text ) VALUES ( 11, '373600000', '', 'MEDICAL SPECIALTIES - COMMUNITY ORIENTED SERVICES' );
INSERT INTO codes ( code_type, code, modifier, code_text ) VALUES ( 11, '373600999', '', 'Medical Specialties - Community Oriented Services - OTHER' );
INSERT INTO codes ( code_type, code, modifier, code_text ) VALUES ( 11, '373601000', '', 'FAMILY HEALTH' );
INSERT INTO codes ( code_type, code, modifier, code_text ) VALUES ( 11, '373601131', '', 'Family Health -  Hypertension Screening' );
INSERT INTO codes ( code_type, code, modifier, code_text ) VALUES ( 11, '373601231', '', 'Family Health -  Physical Exam' );
INSERT INTO codes ( code_type, code, modifier, code_text ) VALUES ( 11, '373601331', '', 'Family Health -  Weight & Vital Signs' );
INSERT INTO codes ( code_type, code, modifier, code_text ) VALUES ( 11, '373601431', '', 'Family Health -  Diabetes Screening' );
INSERT INTO codes ( code_type, code, modifier, code_text ) VALUES ( 11, '373601531', '', 'Family Health -  Urinalysis' );
INSERT INTO codes ( code_type, code, modifier, code_text ) VALUES ( 11, '373601631', '', 'Family Health -  Cholesterol screening' );
INSERT INTO codes ( code_type, code, modifier, code_text ) VALUES ( 11, '373601729', '', 'Family Health -  Nutrition Counseling' );
INSERT INTO codes ( code_type, code, modifier, code_text ) VALUES ( 11, '373601829', '', 'Family Health -  Diet/Weight Control Counseling' );
INSERT INTO codes ( code_type, code, modifier, code_text ) VALUES ( 11, '373601999', '', 'Family Health - OTHER' );
INSERT INTO codes ( code_type, code, modifier, code_text ) VALUES ( 11, '373621000', '', 'GERIATRICS' );
INSERT INTO codes ( code_type, code, modifier, code_text ) VALUES ( 11, '373621130', '', 'Geriatrics - Consultation' );
INSERT INTO codes ( code_type, code, modifier, code_text ) VALUES ( 11, '373621231', '', 'Geriatrics - Diagnostic Test' );
INSERT INTO codes ( code_type, code, modifier, code_text ) VALUES ( 11, '373621332', '', 'Geriatrics - Therapy / Treatment' );
INSERT INTO codes ( code_type, code, modifier, code_text ) VALUES ( 11, '373621999', '', 'Geriatrics - OTHER' );
INSERT INTO codes ( code_type, code, modifier, code_text ) VALUES ( 11, '373641000', '', 'PEDIATRICS' );
INSERT INTO codes ( code_type, code, modifier, code_text ) VALUES ( 11, '373641130', '', 'Pediatrics - Consultation' );
INSERT INTO codes ( code_type, code, modifier, code_text ) VALUES ( 11, '373641231', '', 'Pediatrics - Diagnostic - Neonatal Screening (at Birth)' );
INSERT INTO codes ( code_type, code, modifier, code_text ) VALUES ( 11, '373641331', '', 'Pediatrics - Diagnostic - Well Baby Care / Infant Health Check' );
INSERT INTO codes ( code_type, code, modifier, code_text ) VALUES ( 11, '373641432', '', 'Pediatrics - Therapy / Treatment - Nutrition' );
INSERT INTO codes ( code_type, code, modifier, code_text ) VALUES ( 11, '373641532', '', 'Pediatrics - Therapy / Treatment - Immunization' );
INSERT INTO codes ( code_type, code, modifier, code_text ) VALUES ( 11, '373641632', '', 'Pediatrics - Therapy / Treatment - Oral rehydration (ORT/ORS)' );
INSERT INTO codes ( code_type, code, modifier, code_text ) VALUES ( 11, '373641732', '', 'Pediatrics - Therapy / Treatment - Neonatal Intensive Care' );
INSERT INTO codes ( code_type, code, modifier, code_text ) VALUES ( 11, '373641833', '', 'Pediatrics - Surgery - Circumcision' );
INSERT INTO codes ( code_type, code, modifier, code_text ) VALUES ( 11, '373641999', '', 'Pediatrics - OTHER' );
INSERT INTO codes ( code_type, code, modifier, code_text ) VALUES ( 11, '373661000', '', 'PHYSICAL MEDICINE & REHABILITATION' );
INSERT INTO codes ( code_type, code, modifier, code_text ) VALUES ( 11, '373661130', '', 'Physical Medicine & Rehabilitation - Consultation' );
INSERT INTO codes ( code_type, code, modifier, code_text ) VALUES ( 11, '373661231', '', 'Physical Medicine & Rehabilitation - Diagnostic Test' );
INSERT INTO codes ( code_type, code, modifier, code_text ) VALUES ( 11, '373661332', '', 'Physical Medicine & Rehabilitation - Therapy / Treatment' );
INSERT INTO codes ( code_type, code, modifier, code_text ) VALUES ( 11, '373661433', '', 'Physical Medicine & Rehabilitation - Surgery' );
INSERT INTO codes ( code_type, code, modifier, code_text ) VALUES ( 11, '373661999', '', 'Physical Medicine & Rehabilitation - OTHER' );
INSERT INTO codes ( code_type, code, modifier, code_text ) VALUES ( 11, '373671000', '', 'PREVENTIVE MEDICINE' );
INSERT INTO codes ( code_type, code, modifier, code_text ) VALUES ( 11, '373671130', '', 'Preventive Medicine - Consultation' );
INSERT INTO codes ( code_type, code, modifier, code_text ) VALUES ( 11, '373671231', '', 'Preventive Medicine - Diagnostic Test' );
INSERT INTO codes ( code_type, code, modifier, code_text ) VALUES ( 11, '373671999', '', 'Preventive Medicine - OTHER' );
INSERT INTO codes ( code_type, code, modifier, code_text ) VALUES ( 11, '373681000', '', 'EMERGENCY MEDICINE' );
INSERT INTO codes ( code_type, code, modifier, code_text ) VALUES ( 11, '373681131', '', 'Emergency Medicine - Evaluation' );
INSERT INTO codes ( code_type, code, modifier, code_text ) VALUES ( 11, '373681232', '', 'Emergency Medicine - Initial Treatment' );
INSERT INTO codes ( code_type, code, modifier, code_text ) VALUES ( 11, '373681333', '', 'Emergency Medicine - Emergency Surgery' );
INSERT INTO codes ( code_type, code, modifier, code_text ) VALUES ( 11, '373681999', '', 'Emergency Medicine - OTHER' );
INSERT INTO codes ( code_type, code, modifier, code_text ) VALUES ( 11, '373691000', '', 'HOSPITALIZATION' );
INSERT INTO codes ( code_type, code, modifier, code_text ) VALUES ( 11, '373691140', '', 'Hospitalization - Ambulatory (1 day)' );
INSERT INTO codes ( code_type, code, modifier, code_text ) VALUES ( 11, '373691241', '', 'Hospitalization - Extended (>1day)' );
INSERT INTO codes ( code_type, code, modifier, code_text ) VALUES ( 11, '373691999', '', 'Hospitalization - OTHER' );
INSERT INTO codes ( code_type, code, modifier, code_text ) VALUES ( 11, '374700000', '', 'MEDICAL SPECIALTIES DIAGNOSTIC/THERAPEUTIC PROCEDURES' );
INSERT INTO codes ( code_type, code, modifier, code_text ) VALUES ( 11, '374700999', '', 'Medical Specialties - Diagnostic/Therapeutic Procedures - OTHER' );
INSERT INTO codes ( code_type, code, modifier, code_text ) VALUES ( 11, '374701000', '', 'HEMATOLOGY' );
INSERT INTO codes ( code_type, code, modifier, code_text ) VALUES ( 11, '374701130', '', 'Hematology - Consultation' );
INSERT INTO codes ( code_type, code, modifier, code_text ) VALUES ( 11, '374701231', '', 'Hematology - Diagnostic Test' );
INSERT INTO codes ( code_type, code, modifier, code_text ) VALUES ( 11, '374701332', '', 'Hematology - Therapy / Treatment' );
INSERT INTO codes ( code_type, code, modifier, code_text ) VALUES ( 11, '374701999', '', 'Hematology - OTHER' );
INSERT INTO codes ( code_type, code, modifier, code_text ) VALUES ( 11, '374721000', '', 'TOXICOLOGY' );
INSERT INTO codes ( code_type, code, modifier, code_text ) VALUES ( 11, '374721130', '', 'Toxicology - Consultation' );
INSERT INTO codes ( code_type, code, modifier, code_text ) VALUES ( 11, '374721231', '', 'Toxicology - Diagnostic tests' );
INSERT INTO codes ( code_type, code, modifier, code_text ) VALUES ( 11, '374721332', '', 'Toxicology - Therapy / Treatment' );
INSERT INTO codes ( code_type, code, modifier, code_text ) VALUES ( 11, '374721999', '', 'Toxicology - OTHER' );
INSERT INTO codes ( code_type, code, modifier, code_text ) VALUES ( 11, '374741000', '', 'CHEMICAL PATHOLOGY LAB TESTS' );
INSERT INTO codes ( code_type, code, modifier, code_text ) VALUES ( 11, '374741130', '', 'Chemical Patology - Consultation' );
INSERT INTO codes ( code_type, code, modifier, code_text ) VALUES ( 11, '374741231', '', 'Chemical Patology - Diagnostic Test' );
INSERT INTO codes ( code_type, code, modifier, code_text ) VALUES ( 11, '374751999', '', 'Chemical Patology - OTHER' );
INSERT INTO codes ( code_type, code, modifier, code_text ) VALUES ( 11, '374761000', '', 'PATHOLOGY LAB TESTS' );
INSERT INTO codes ( code_type, code, modifier, code_text ) VALUES ( 11, '374761130', '', 'Pathology - Consultation' );
INSERT INTO codes ( code_type, code, modifier, code_text ) VALUES ( 11, '374761231', '', 'Pathology - Diagnostic Test' );
INSERT INTO codes ( code_type, code, modifier, code_text ) VALUES ( 11, '374761999', '', 'Pathology - OTHER' );
INSERT INTO codes ( code_type, code, modifier, code_text ) VALUES ( 11, '374781000', '', 'MICROBIOLOGY' );
INSERT INTO codes ( code_type, code, modifier, code_text ) VALUES ( 11, '374781130', '', 'Microbiology - Consultation' );
INSERT INTO codes ( code_type, code, modifier, code_text ) VALUES ( 11, '374781231', '', 'Microbiology - Diagnostic Test' );
INSERT INTO codes ( code_type, code, modifier, code_text ) VALUES ( 11, '374781999', '', 'Microbiology - OTHER' );
INSERT INTO codes ( code_type, code, modifier, code_text ) VALUES ( 11, '375800000', '', 'MEDICAL SPECIALTIES - OTHER SERVICES' );
INSERT INTO codes ( code_type, code, modifier, code_text ) VALUES ( 11, '375800999', '', 'Medical Specialties - Other Services - OTHER' );
INSERT INTO codes ( code_type, code, modifier, code_text ) VALUES ( 11, '375801000', '', 'CHIROPRACTICE' );
INSERT INTO codes ( code_type, code, modifier, code_text ) VALUES ( 11, '375801130', '', 'Chiropractice - Consultation' );
INSERT INTO codes ( code_type, code, modifier, code_text ) VALUES ( 11, '375801232', '', 'Chiropractice - Therapy / Treatment' );
INSERT INTO codes ( code_type, code, modifier, code_text ) VALUES ( 11, '375801999', '', 'Chiropractice - OTHER' );
INSERT INTO codes ( code_type, code, modifier, code_text ) VALUES ( 11, '375811000', '', 'OSTEOPHATY' );
INSERT INTO codes ( code_type, code, modifier, code_text ) VALUES ( 11, '375811130', '', 'Osteophaty - Consultation' );
INSERT INTO codes ( code_type, code, modifier, code_text ) VALUES ( 11, '375811232', '', 'Osteophaty - Therapy / Treatment' );
INSERT INTO codes ( code_type, code, modifier, code_text ) VALUES ( 11, '375811331', '', 'Osteophaty - Diagnostic Test' );
INSERT INTO codes ( code_type, code, modifier, code_text ) VALUES ( 11, '375811999', '', 'Osteophaty - OTHER' );
INSERT INTO codes ( code_type, code, modifier, code_text ) VALUES ( 11, '375821000', '', 'PLASTIC SURGERY' );
INSERT INTO codes ( code_type, code, modifier, code_text ) VALUES ( 11, '375821130', '', 'Plastic Surgery - Consultation' );
INSERT INTO codes ( code_type, code, modifier, code_text ) VALUES ( 11, '375821232', '', 'Plastic Surgery - Therapy / Treatment' );
INSERT INTO codes ( code_type, code, modifier, code_text ) VALUES ( 11, '375821333', '', 'Plastic Surgery - Surgery' );
INSERT INTO codes ( code_type, code, modifier, code_text ) VALUES ( 11, '375821999', '', 'Plastic Surgery - OTHER' );
INSERT INTO codes ( code_type, code, modifier, code_text ) VALUES ( 11, '375831000', '', 'OTHER NON SRH MEDICAL SERVICES' );
INSERT INTO codes ( code_type, code, modifier, code_text ) VALUES ( 11, '375831130', '', 'Other non-SRH medical services - Consultation' );
INSERT INTO codes ( code_type, code, modifier, code_text ) VALUES ( 11, '375831231', '', 'Other non-SRH medical services - Diagnostic Test' );
INSERT INTO codes ( code_type, code, modifier, code_text ) VALUES ( 11, '375831332', '', 'Other non-SRH medical services - Therapy / Treatment' );
INSERT INTO codes ( code_type, code, modifier, code_text ) VALUES ( 11, '375831433', '', 'Other non-SRH medical services - Surgery' );
INSERT INTO codes ( code_type, code, modifier, code_text ) VALUES ( 11, '375831539', '', 'Other non-SRH medical services - Counselling' );
INSERT INTO codes ( code_type, code, modifier, code_text ) VALUES ( 11, '375831999', '', 'Other non-SRH medical services - OTHER' );
INSERT INTO codes ( code_type, code, modifier, code_text ) VALUES ( 11, '376100000', '', 'PREVENTION AND TREATMENT SERVICES' );
INSERT INTO codes ( code_type, code, modifier, code_text ) VALUES ( 11, '376101000', '', 'MALARIA PREVENTION AND TREATMENT SERVICES' );
INSERT INTO codes ( code_type, code, modifier, code_text ) VALUES ( 11, '376101132', '', 'Malaria prevention and treatment services  for children under 5 years' );
INSERT INTO codes ( code_type, code, modifier, code_text ) VALUES ( 11, '376101232', '', 'Malaria prevention and treatment services  for pregnant mothers' );
INSERT INTO codes ( code_type, code, modifier, code_text ) VALUES ( 11, '376101999', '', 'Malaria prevention and treatment services  OTHER' );
INSERT INTO codes ( code_type, code, modifier, code_text ) VALUES ( 11, '380910000', '', 'OTHER NON SRH SERVICES' );
INSERT INTO codes ( code_type, code, modifier, code_text ) VALUES ( 11, '380910999', '', 'ALL OTHER NON SRH SERVICES - OTHER' );
INSERT INTO codes ( code_type, code, modifier, code_text ) VALUES ( 11, '380911000', '', 'SALES & RENTALS' );
INSERT INTO codes ( code_type, code, modifier, code_text ) VALUES ( 11, '380911999', '', 'Sales & Rentals - OTHER' );
INSERT INTO codes ( code_type, code, modifier, code_text ) VALUES ( 11, '381912000', '', 'SALES OF MEDICINES, SUPPLIES AND EQUIPMENT' );
INSERT INTO codes ( code_type, code, modifier, code_text ) VALUES ( 11, '381912150', '', 'Sales of Medicines' );
INSERT INTO codes ( code_type, code, modifier, code_text ) VALUES ( 11, '381912250', '', 'Sales Medical Supplies' );
INSERT INTO codes ( code_type, code, modifier, code_text ) VALUES ( 11, '381912350', '', 'Sales Medical Equipment' );
INSERT INTO codes ( code_type, code, modifier, code_text ) VALUES ( 11, '381913999', '', 'Sales - OTHER' );
INSERT INTO codes ( code_type, code, modifier, code_text ) VALUES ( 11, '382914000', '', 'RENTAL OF MEDICAL EQUIPMENT / INFRASTRUCTURE' );
INSERT INTO codes ( code_type, code, modifier, code_text ) VALUES ( 11, '382914450', '', 'Rental Medical Infrastructure' );
INSERT INTO codes ( code_type, code, modifier, code_text ) VALUES ( 11, '382915999', '', 'Rental Medical Equipment / Infrastructure - OTHER' );
INSERT INTO codes ( code_type, code, modifier, code_text ) VALUES ( 11, '490000000', '', 'NON-MEDICAL PRODUCTS AND SERVICES' );
INSERT INTO codes ( code_type, code, modifier, code_text ) VALUES ( 11, '491990000', '', 'OTHER NON MEDICAL PRODUCTS & SERVICES' );
INSERT INTO codes ( code_type, code, modifier, code_text ) VALUES ( 11, '491990190', '', 'Other non-medical products - Sales of IEC Materials' );
INSERT INTO codes ( code_type, code, modifier, code_text ) VALUES ( 11, '491990290', '', 'Other non-medical Products & Services - Free distribution of IEC materials' );
INSERT INTO codes ( code_type, code, modifier, code_text ) VALUES ( 11, '491990999', '', 'Other non-medical products - Other Generic Products' );
INSERT INTO codes ( code_type, code, modifier, code_text ) VALUES ( 11, '492992000', '', 'OTHER NON MEDICAL SERVICES SALES' );
INSERT INTO codes ( code_type, code, modifier, code_text ) VALUES ( 11, '492992090', '', 'Other non-medical services - Sales of IEC Services' );
INSERT INTO codes ( code_type, code, modifier, code_text ) VALUES ( 11, '492992999', '', 'Other non-medical services - OTHER' );
#EndIf

# Following lines mirror line-for-line the spreadsheet "CYP Factors 2010.1.xlsx".
UPDATE codes SET cyp_factor = 0.0666667 WHERE code_type = 11 AND code LIKE '11110_%';
UPDATE codes SET cyp_factor = 0.0769230 WHERE code_type = 11 AND code LIKE '111111%';
UPDATE codes SET cyp_factor = 0.1666667 WHERE code_type = 11 AND code LIKE '111112%';
UPDATE codes SET cyp_factor = 0.2500000 WHERE code_type = 11 AND code LIKE '111113%';
UPDATE codes SET cyp_factor = 3.5000000 WHERE code_type = 11 AND code LIKE '111122%';
UPDATE codes SET cyp_factor = 3.5000000 WHERE code_type = 11 AND code LIKE '111123%';
UPDATE codes SET cyp_factor = 2.5000000 WHERE code_type = 11 AND code LIKE '111124%';
UPDATE codes SET cyp_factor = 0.0666667 WHERE code_type = 11 AND code LIKE '111132%';
UPDATE codes SET cyp_factor = 0.0666667 WHERE code_type = 11 AND code LIKE '111133%';
UPDATE codes SET cyp_factor = 0.0083333 WHERE code_type = 11 AND code LIKE '112141%';
UPDATE codes SET cyp_factor = 0.0083333 WHERE code_type = 11 AND code LIKE '112142%';
UPDATE codes SET cyp_factor = 1.0000000 WHERE code_type = 11 AND code LIKE '112151%';
UPDATE codes SET cyp_factor = 1.0000000 WHERE code_type = 11 AND code LIKE '112152%';
UPDATE codes SET cyp_factor = 0.1333333 WHERE code_type = 11 AND code LIKE '112161%';
UPDATE codes SET cyp_factor = 0.1333333 WHERE code_type = 11 AND code LIKE '112162%';
UPDATE codes SET cyp_factor = 0.1333333 WHERE code_type = 11 AND code LIKE '112163%';
UPDATE codes SET cyp_factor = 0.1333333 WHERE code_type = 11 AND code LIKE '112164%';
UPDATE codes SET cyp_factor = 0.1333333 WHERE code_type = 11 AND code LIKE '112165%';
UPDATE codes SET cyp_factor = 3.5000000 WHERE code_type = 11 AND code LIKE '113171%';
UPDATE codes SET cyp_factor = 3.5000000 WHERE code_type = 11 AND code LIKE '113172%';
UPDATE codes SET cyp_factor = 10.000000 WHERE code_type = 11 AND code LIKE '121181%';
UPDATE codes SET cyp_factor = 10.000000 WHERE code_type = 11 AND code LIKE '122182%';
UPDATE codes SET cyp_factor = 0.0500000 WHERE code_type = 11 AND code LIKE '145212%';
# Next line clears cyp for codes corresponding to removal of contraception.
UPDATE codes SET cyp_factor = 0         WHERE code_type = 11 AND code LIKE '1_____112';

#IfMissingColumn patient_data contrastart
ALTER TABLE patient_data ADD contrastart DATE DEFAULT NULL;
#EndIf

#IfMissingColumn patient_data ippfconmeth
ALTER TABLE patient_data ADD ippfconmeth varchar(255) NOT NULL DEFAULT '';
#EndIf

#IfNotRow2D list_options list_id lbfnames option_id LBFccicon
INSERT INTO list_options (list_id,option_id,title,seq,option_value) VALUES ('lbfnames','LBFccicon','Contraception',1,5);
DELETE FROM layout_options WHERE form_id = 'LBFccicon';
INSERT INTO layout_options VALUES ('LBFccicon', 'newmauser', '1', 'First contraceptive at this clinic?',
  1,  1, 2, 0, 0, 'boolean'    , 1, 3, '', '', 'Is this the first contraceptive accepted at this clinic?',0);
INSERT INTO layout_options VALUES ('LBFccicon', 'curmethod', '1', 'Current Method',
  2,  1, 1, 0, 0, 'contrameth' , 1, 3, '', '', 'Method in use at start of visit',0);
INSERT INTO layout_options VALUES ('LBFccicon', 'pastmodern','1', 'Previous modern contraceptive use?',
  3,  1, 1, 0, 0, 'boolean'    , 1, 3, '', '', 'Was a modern contraceptive method used at some time in the past?',0);
INSERT INTO layout_options VALUES ('LBFccicon', 'reqmethod', '1', 'Requested Method',
  4,  1, 1, 0, 0, 'contrameth' , 1, 3, '', '', 'Method requested by the client',0);
INSERT INTO layout_options VALUES ('LBFccicon', 'newmethod', '1', 'Adopted Method',
  5,  1, 1, 0, 0, 'ippfconmeth', 1, 3, '', '', 'Method adopted in this visit',0);
INSERT INTO layout_options VALUES ('LBFccicon', 'provider' , '1', 'Service Provider',
  6, 10, 1, 0, 0, ''           , 1, 3, '', '', 'Provider of this initial consultation',0);
INSERT INTO layout_options VALUES ('LBFccicon', 'mcreason' , '1', 'Reason for Method Change',
  7,  1, 1, 0, 0, 'mcreason'   , 1, 3, '', '', 'Reason for method change',0);

# This section creates LBFccicon visit forms to replace contraception dates/methods in demographics.
# Fields generated: newmauser (always 1), newmethod (only if present in demographics, probably not).
#
# Create newmauser data item for each patient contrastart that assigns the form_id and
# temporarily holds the pid.  This pid is later used for matching to get the new form ID.
INSERT INTO lbf_data (field_id, field_value)
  SELECT 'newmauser', pd.pid FROM patient_data AS pd
  WHERE pd.contrastart IS NOT NULL AND pd.contrastart NOT LIKE '0%';
#
# Create newmethod data item for each such patient that also has a starting method defined.
INSERT INTO lbf_data (form_id, field_id, field_value)
  SELECT MAX(ld.form_id), 'newmethod', pd.ippfconmeth
  FROM patient_data AS pd, lbf_data AS ld
  WHERE pd.contrastart IS NOT NULL AND pd.contrastart NOT LIKE '0%'
  AND pd.ippfconmeth != ''
  AND ld.field_id = 'newmauser'
  AND ld.field_value = pd.pid
  GROUP BY pd.pid;
#
# Create form_encounter table entries for missing encounters.
SELECT @i:=(SELECT id FROM sequences);
INSERT INTO form_encounter (date, reason, pid, encounter)
  SELECT pd.contrastart, 'PreOpenEMR Data', pd.pid, @i:=@i+1
  FROM patient_data AS pd, lbf_data AS ld
  WHERE pd.contrastart IS NOT NULL AND pd.contrastart NOT LIKE '0%'
  AND (SELECT COUNT(*) FROM form_encounter WHERE pid = pd.pid AND date = pd.contrastart) = 0
  AND ld.field_id = 'newmauser'
  AND ld.field_value = pd.pid;
# Update sequences table to hold the next encounter number.
UPDATE sequences set id = @i;
#
# Create the forms table entries for missing encounters.
INSERT INTO forms (date, encounter, form_name, form_id, pid, user, groupname, authorized, formdir)
  SELECT CURRENT_DATE, fe.encounter, 'New Patient Encounter', fe.id, pd.pid, 'admin', 'Default', '1', 'newpatient'
  FROM patient_data AS pd, form_encounter AS fe
  WHERE pd.contrastart IS NOT NULL AND pd.contrastart NOT LIKE '0%'
  AND fe.pid = pd.pid
  AND fe.date = pd.contrastart
  AND fe.reason = 'PreOpenEMR Data'
  AND (SELECT COUNT(*) FROM forms WHERE pid = pd.pid AND encounter = fe.encounter AND formdir = 'newpatient' and deleted = 0) = 0;
#
# Create the forms table entries for Contraception.
INSERT INTO forms (date, encounter, form_name, form_id, pid, user, groupname, authorized, formdir)
  SELECT CURRENT_DATE, MIN(fe.encounter), 'Contraception Initial Consult', MAX(ld.form_id), pd.pid, 'admin', 'Default', '1', 'LBFccicon'
  FROM patient_data AS pd, form_encounter AS fe, lbf_data AS ld
  WHERE pd.contrastart IS NOT NULL AND pd.contrastart NOT LIKE '0%'
  AND fe.pid = pd.pid
  AND fe.date = pd.contrastart
  AND ld.field_id = 'newmauser'
  AND ld.field_value = pd.pid
  GROUP BY pd.pid;
#
# Clean up the newmauser data items.
UPDATE forms AS f, lbf_data AS ld SET ld.field_value = '1' WHERE
  f.formdir = 'LBFccicon' AND f.deleted = 0 AND ld.form_id = f.form_id AND
  ld.field_id = 'newmauser' AND ld.field_value = f.pid;
#EndIf

ALTER TABLE patient_data DROP ippfconmeth;
ALTER TABLE patient_data DROP contrastart;
DELETE FROM `layout_options` WHERE form_id = 'DEM' AND field_id = 'ippfconmeth';
DELETE FROM `layout_options` WHERE form_id = 'DEM' AND field_id = 'contrastart';

#IfNotRow2D list_options list_id ippfconmeth option_id 145212110
INSERT INTO list_options ( list_id, option_id, title, seq, is_default ) VALUES ('ippfconmeth','145212110','Emergency Contraception',26,0);
#EndIf

# Set group names for IPPF contraceptive methods.
UPDATE list_options SET mapping = 'Pills'          WHERE list_id = 'ippfconmeth' AND option_id LIKE '11110%'  AND mapping = '';
UPDATE list_options SET mapping = 'Injectables'    WHERE list_id = 'ippfconmeth' AND option_id LIKE '11111%'  AND mapping = '';
UPDATE list_options SET mapping = 'Implants'       WHERE list_id = 'ippfconmeth' AND option_id LIKE '11112%'  AND mapping = '';
UPDATE list_options SET mapping = 'Hormonal Other' WHERE list_id = 'ippfconmeth' AND option_id LIKE '11113%'  AND mapping = '';
UPDATE list_options SET mapping = 'Barrier'        WHERE list_id = 'ippfconmeth' AND option_id LIKE '11214%'  AND mapping = '';
UPDATE list_options SET mapping = 'Barrier'        WHERE list_id = 'ippfconmeth' AND option_id LIKE '11215%'  AND mapping = '';
UPDATE list_options SET mapping = 'Spermicides'    WHERE list_id = 'ippfconmeth' AND option_id LIKE '11216%'  AND mapping = '';
UPDATE list_options SET mapping = 'IUD'            WHERE list_id = 'ippfconmeth' AND option_id LIKE '11317%'  AND mapping = '';
UPDATE list_options SET mapping = 'Female VSC'     WHERE list_id = 'ippfconmeth' AND option_id LIKE '121181%' AND mapping = '';
UPDATE list_options SET mapping = 'Male VSC'       WHERE list_id = 'ippfconmeth' AND option_id LIKE '121182%' AND mapping = '';
UPDATE list_options SET mapping = 'EC'             WHERE list_id = 'ippfconmeth' AND option_id LIKE '14521%'  AND mapping = '';

# Set flags to indicate which are the modern conraceptive methods.
UPDATE list_options SET option_value = 0 WHERE list_id = 'contrameth';
UPDATE list_options SET option_value = 1 WHERE list_id = 'contrameth' AND mapping LIKE '%:1%';

#IfNotRow2D list_options list_id mcreason option_id stc
DELETE FROM list_options WHERE list_id = 'mcreason';
INSERT INTO list_options ( list_id, option_id, title, seq, is_default, option_value ) VALUES ('mcreason'  ,'eco','Economic (cost)'                         , 1,0,0);
INSERT INTO list_options ( list_id, option_id, title, seq, is_default, option_value ) VALUES ('mcreason'  ,'mda','Medical - Allergy'                       , 1,0,0);
INSERT INTO list_options ( list_id, option_id, title, seq, is_default, option_value ) VALUES ('mcreason'  ,'mdb','Medical - Breast Feeding'                , 1,0,0);
INSERT INTO list_options ( list_id, option_id, title, seq, is_default, option_value ) VALUES ('mcreason'  ,'mdc','Medical - Contraindication'              , 1,0,0);
INSERT INTO list_options ( list_id, option_id, title, seq, is_default, option_value ) VALUES ('mcreason'  ,'men','Medical - Menopause'                     , 1,0,0);
INSERT INTO list_options ( list_id, option_id, title, seq, is_default, option_value ) VALUES ('mcreason'  ,'sef','Medical - Side Effects of Current Method', 1,0,0);
INSERT INTO list_options ( list_id, option_id, title, seq, is_default, option_value ) VALUES ('mcreason'  ,'com','Method Too Complicated'                  , 1,0,0);
INSERT INTO list_options ( list_id, option_id, title, seq, is_default, option_value ) VALUES ('mcreason'  ,'fop','Personal - Family Pressure/Advice'       , 1,0,0);
INSERT INTO list_options ( list_id, option_id, title, seq, is_default, option_value ) VALUES ('mcreason'  ,'con','Personal - Fear of Infertility'          , 1,0,0);
INSERT INTO list_options ( list_id, option_id, title, seq, is_default, option_value ) VALUES ('mcreason'  ,'oth','Personal - Other Reason'                 , 1,0,0);
INSERT INTO list_options ( list_id, option_id, title, seq, is_default, option_value ) VALUES ('mcreason'  ,'pop','Personal - Partner Opposes'              , 1,0,0);
INSERT INTO list_options ( list_id, option_id, title, seq, is_default, option_value ) VALUES ('mcreason'  ,'rel','Personal - Religious'                    , 1,0,0);
INSERT INTO list_options ( list_id, option_id, title, seq, is_default, option_value ) VALUES ('mcreason'  ,'pse','Personal - Side Effects Concern'         , 1,0,0);
INSERT INTO list_options ( list_id, option_id, title, seq, is_default, option_value ) VALUES ('mcreason'  ,'oop','Personal - Social Pressure/Friend Advice', 1,0,0);
INSERT INTO list_options ( list_id, option_id, title, seq, is_default, option_value ) VALUES ('mcreason'  ,'prg','Planning Pregnancy'                      , 1,0,0);
INSERT INTO list_options ( list_id, option_id, title, seq, is_default, option_value ) VALUES ('mcreason'  ,'stc','Sterilization of Client'                 , 1,0,0);
INSERT INTO list_options ( list_id, option_id, title, seq, is_default, option_value ) VALUES ('mcreason'  ,'stp','Sterilization of Partner'                , 1,0,0);
INSERT INTO list_options ( list_id, option_id, title, seq, is_default, option_value ) VALUES ('mcreason'  ,'nav','Unavailable at Clinic'                   , 1,0,0);
INSERT INTO list_options ( list_id, option_id, title, seq, is_default, option_value ) VALUES ('mcreason'  ,'uns','Unspecified - No Reason Provided'        , 1,0,0);
#EndIf

#IfNotRow code_types ct_key ADM
INSERT INTO code_types (ct_key, ct_id, ct_seq, ct_mod, ct_just, ct_fee, ct_rel, ct_nofs, ct_diag ) VALUES ('ADM',17, 5, 0, '', 1, 0, 0, 0);
#EndIf

UPDATE list_options SET title = 'Client has received ITS Counselling' WHERE
  list_id = 'genitshist' AND title = 'Client has received ITS  Counselling';

UPDATE facility SET billing_location = 1 WHERE pos_code = 1 AND billing_location != 1;

