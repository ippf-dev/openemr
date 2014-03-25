#IfNotRow2D list_options list_id lbfnames option_id LBFgcac
DELETE FROM list_options WHERE list_id = 'lbfnames';
INSERT INTO list_options ( list_id, option_id, title, seq ) VALUES ('lbfnames','LBFgcac','IPPF GCAC',1);
INSERT INTO list_options ( list_id, option_id, title, seq ) VALUES ('lbfnames','LBFsrh' ,'IPPF SRH' ,2);
#EndIf

#IfNotRow2D list_options list_id LBFsrh option_id uro_disease
DELETE FROM layout_options WHERE form_id = 'LBFsrh';
INSERT INTO layout_options VALUES ('LBFsrh','usertext15' ,'1Gynecology'                ,'Menstrual History'             , 1,22,1, 0, 0,'genmenhist'  ,1,3,'','H','');
INSERT INTO layout_options VALUES ('LBFsrh','men_hist'   ,'1Gynecology'                ,'Recent Menstrual History'      , 2,21,1, 2, 0,'menhist'     ,1,3,'','','Recent Menstrual History');
INSERT INTO layout_options VALUES ('LBFsrh','men_compl'  ,'1Gynecology'                ,'Menstrual Complications'       , 3,21,1, 2, 0,'men_compl'   ,1,3,'','','Menstrual Complications');
INSERT INTO layout_options VALUES ('LBFsrh','pap_hist'   ,'1Gynecology'                ,'Pap Smear Recent History'      , 4,22,1, 0, 0,'pap_hist'    ,1,3,'','','Pap Smear Recent History');
INSERT INTO layout_options VALUES ('LBFsrh','gyn_exams'  ,'1Gynecology'                ,'Gynecological Tests'           , 5,23,1, 0, 0,'gyn_exams'   ,1,1,'','','Gynecological test results');
INSERT INTO layout_options VALUES ('LBFsrh','pr_status'  ,'2Obstetrics'                ,'Pregnancy Status Confirmed'    , 1, 1,1, 0, 0,'pr_status'   ,1,3,'','','Pregnancy Status Confirmed');
INSERT INTO layout_options VALUES ('LBFsrh','gest_age_by','2Obstetrics'                ,'Gestational Age Confirmed By'  , 2, 1,1, 0, 0,'gest_age_by' ,1,3,'','','Gestational Age Confirmed By');
INSERT INTO layout_options VALUES ('LBFsrh','usertext12' ,'2Obstetrics'                ,'Blood Group'                   , 3, 1,1, 0, 0,'bloodgroup'  ,1,3,'','H','');
INSERT INTO layout_options VALUES ('LBFsrh','usertext13' ,'2Obstetrics'                ,'RH Factor'                     , 4, 1,1, 0, 0,'rh_factor'   ,1,3,'','H','');
INSERT INTO layout_options VALUES ('LBFsrh','obs_exams'  ,'2Obstetrics'                ,'Obstetric Tests'               , 5,23,1, 0, 0,'obs_exams'   ,1,1,'','','Obstetric test results');
INSERT INTO layout_options VALUES ('LBFsrh','usertext16' ,'2Obstetrics'                ,'Obstetric History'             , 6,22,1, 0, 0,'genobshist'  ,1,1,'','H','');
INSERT INTO layout_options VALUES ('LBFsrh','pr_outcome' ,'2Obstetrics'                ,'Outcome of Last Pregnancy'     , 7,21,1, 2, 0,'pr_outcome'  ,1,3,'','','Outcome of Last Pregnancy');
INSERT INTO layout_options VALUES ('LBFsrh','pr_compl'   ,'2Obstetrics'                ,'Pregnancy Complications'       , 8,21,1, 2, 0,'pr_compl'    ,1,3,'','','Pregnancy Complications');
INSERT INTO layout_options VALUES ('LBFsrh','usertext17' ,'3Basic RH (female only)'    ,'Abortion Basic History'        , 1,22,1, 0, 0,'genabohist'  ,1,1,'','H','');
INSERT INTO layout_options VALUES ('LBFsrh','abo_exams'  ,'3Basic RH (female only)'    ,'Abortion Tests'                , 2,23,1, 0, 0,'abo_exams'   ,1,1,'','','Abortion test results');
INSERT INTO layout_options VALUES ('LBFsrh','usertext18' ,'4Basic RH (female and male)','HIV/AIDS Basic History'        , 1,21,1, 0, 0,'genhivhist'  ,1,1,'','H','');
INSERT INTO layout_options VALUES ('LBFsrh','hiv_exams'  ,'4Basic RH (female and male)','HIV/AIDS Tests'                , 2,23,1, 0, 0,'hiv_exams'   ,1,1,'','','HIV/AIDS test results');
INSERT INTO layout_options VALUES ('LBFsrh','usertext19' ,'4Basic RH (female and male)','ITS/ITR Basic History'         , 3,21,1, 0, 0,'genitshist'  ,1,1,'','H','');
INSERT INTO layout_options VALUES ('LBFsrh','its_exams'  ,'4Basic RH (female and male)','ITS/ITR Tests'                 , 4,23,1, 0, 0,'its_exams'   ,1,1,'','','ITS/ITR test results');
INSERT INTO layout_options VALUES ('LBFsrh','usertext20' ,'4Basic RH (female and male)','Fertility Basic History'       , 5,21,1, 0, 0,'genferhist'  ,1,1,'','H','');
INSERT INTO layout_options VALUES ('LBFsrh','fer_exams'  ,'4Basic RH (female and male)','Fertility Tests'               , 6,23,1, 0, 0,'fer_exams'   ,1,1,'','','Infertility/subfertility test results');
INSERT INTO layout_options VALUES ('LBFsrh','fer_causes' ,'4Basic RH (female and male)','Causes of Infertility'         , 7,21,1, 2, 0,'fer_causes'  ,1,3,'','','Causes of Infertility');
INSERT INTO layout_options VALUES ('LBFsrh','fer_treat'  ,'4Basic RH (female and male)','Infertility Treatment'         , 8,21,1, 2, 0,'fer_treat'   ,1,3,'','','Infertility Treatment');
INSERT INTO layout_options VALUES ('LBFsrh','usertext21' ,'4Basic RH (female and male)','Urology Basic History'         , 9,21,1, 0, 0,'genurohist'  ,1,1,'','H','');
INSERT INTO layout_options VALUES ('LBFsrh','uro_exams'  ,'4Basic RH (female and male)','Urology Tests'                 ,10,23,1, 0, 0,'uro_exams'   ,1,1,'','','Urology test results');
INSERT INTO layout_options VALUES ('LBFsrh','uro_disease','4Basic RH (female and male)','Male Genitourinary diseases'   ,11,21,1, 2, 0,'uro_disease' ,1,3,'','','Male Genitourinary diseases');
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
INSERT INTO `openemr_postcalendar_categories` VALUES (12,'3 Counselling Only','#FFFFCC','Counselling',1,NULL,'a:5:{s:17:\"event_repeat_freq\";s:1:\"1\";s:22:\"event_repeat_freq_type\";s:1:\"4\";s:19:\"event_repeat_on_num\";s:1:\"1\";s:19:\"event_repeat_on_day\";s:1:\"0\";s:20:\"event_repeat_on_freq\";s:1:\"0\";}',0,900,0,3,2,0,0);
#EndIf
#IfNotRow openemr_postcalendar_categories pc_catid 13
INSERT INTO `openemr_postcalendar_categories` VALUES (13,'4 Supply/Re-Supply','#CCCCCC','Supply/Re-Supply',1,NULL,'a:5:{s:17:\"event_repeat_freq\";s:1:\"1\";s:22:\"event_repeat_freq_type\";s:1:\"4\";s:19:\"event_repeat_on_num\";s:1:\"1\";s:19:\"event_repeat_on_day\";s:1:\"0\";s:20:\"event_repeat_on_freq\";s:1:\"0\";}',0,900,0,3,2,0,0);
#EndIf
#IfNotRow openemr_postcalendar_categories pc_catid 14
INSERT INTO `openemr_postcalendar_categories` VALUES (14,'5 Administrative','#FFFFFF','Supply/Re-Supply',1,NULL,'a:5:{s:17:\"event_repeat_freq\";s:1:\"1\";s:22:\"event_repeat_freq_type\";s:1:\"4\";s:19:\"event_repeat_on_num\";s:1:\"1\";s:19:\"event_repeat_on_day\";s:1:\"0\";s:20:\"event_repeat_on_freq\";s:1:\"0\";}',0,900,0,3,2,0,0);
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
INSERT INTO layout_options VALUES ('LBFgcac','client_status','1Basic Information','Client Status'               , 1,27,2, 0, 0,'clientstatus',1,1,'','' ,'Client Status');
INSERT INTO layout_options VALUES ('LBFgcac','ab_location'  ,'1Basic Information','Type of Visit'               , 2,27,2, 0, 0,'ab_location' ,1,1,'','' ,'Nature of this visit');
INSERT INTO layout_options VALUES ('LBFgcac','in_ab_proc'   ,'1Basic Information','Associated Induced Procedure', 3,27,1, 0, 0,'in_ab_proc'  ,1,3,'','' ,'Applies regardless of when or where done');
INSERT INTO layout_options VALUES ('LBFgcac','complications','2Complications','Complications'                   , 1,21,1, 2, 0,'complication',1,3,'','' ,'Post-Abortion Complications');
INSERT INTO layout_options VALUES ('LBFgcac','main_compl'   ,'2Complications','Main Complication'               , 2, 1,1, 2, 0,'complication',1,3,'','' ,'Primary Complication');
INSERT INTO layout_options VALUES ('LBFgcac','gc_rreason'   ,'3IPPA CAC Section','Reason when Rejected/Referred', 1, 1,0, 0, 0,'gc_rreason'   ,1,3,'','','Reason for rejecting or referring services');
INSERT INTO layout_options VALUES ('LBFgcac','gc_reason'    ,'3IPPA CAC Section','Main Reason for MR Services'  , 1, 1,0, 0, 0,'gc_reason'   ,1,3,'','' ,'Main reason for requesting MR services');
INSERT INTO layout_options VALUES ('LBFgcac','gc_condition' ,'3IPPA CAC Section','Aborted Conception Condition' , 2, 1,0, 0 ,0,'gc_condition',1,3,'','' ,'Condition of Aborted Conception');
INSERT INTO layout_options VALUES ('LBFgcac','gc_efforts'   ,'3IPPA CAC Section','Efforts Prior to Visit'       , 3, 1,0, 0, 0,'gc_efforts'  ,1,3,'','' ,'Other efforts conducted before visiting the clinic');
INSERT INTO layout_options VALUES ('LBFgcac','gc_complaint' ,'3IPPA CAC Section','Complaint from Client'        , 4, 1,0, 0, 0,'gc_complaint',1,3,'','' ,'Complaint from Client');
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
INSERT INTO layout_options VALUES ('REF','refer_date'        ,'1Referral','Referral Date'                  , 5, 4,2, 0,  0,''         ,1,1,'C','D','Date of referral');
INSERT INTO layout_options VALUES ('REF','refer_from'        ,'1Referral','Referred By'                    ,10,14,2, 0,  0,''         ,1,1,'' ,'' ,'Referral By');
INSERT INTO layout_options VALUES ('REF','refer_external'    ,'1Referral','Referral Type'                  ,15, 1,2, 0,  0,'reftype'  ,1,1,'' ,'' ,'Type of referral');
INSERT INTO layout_options VALUES ('REF','refer_to'          ,'1Referral','Referred To'                    ,20,14,2, 0,  0,''         ,1,1,'' ,'' ,'Referral To');
INSERT INTO layout_options VALUES ('REF','body'              ,'1Referral','Reason'                         ,25, 3,2,30,  3,''         ,1,1,'' ,'' ,'Reason for referral');
INSERT INTO layout_options VALUES ('REF','refer_risk_level'  ,'1Referral','Risk Level'                     ,30, 1,1, 0,  0,'risklevel',1,1,'' ,'' ,'Level of urgency');
INSERT INTO layout_options VALUES ('REF','refer_vitals'      ,'1Referral','Include Vital Signs'            ,35, 1,1, 0,  0,'boolean'  ,1,1,'' ,'' ,'Include vitals data?');
INSERT INTO layout_options VALUES ('REF','refer_reply_date'  ,'1Referral','Expected Reply Date'            ,40, 4,2, 0,  0,''         ,1,1,'' ,'D','Expected date of reply');
INSERT INTO layout_options VALUES ('REF','refer_related_code','1Referral','Requested Service'              ,45,15,2,30,255,''         ,1,1,'' ,'' ,'Billing Code for Requested Service');
INSERT INTO layout_options VALUES ('REF','refer_diag'        ,'1Referral','Preliminary Diagnosis'          ,50, 2,1,30,255,''         ,1,1,'' ,'X','Referrer diagnosis');
INSERT INTO layout_options VALUES ('REF','reply_date'        ,'2Counter-Referral','Reply Date'             , 5, 4,1, 0,  0,''         ,1,1,'' ,'D','Date of reply');
INSERT INTO layout_options VALUES ('REF','reply_from'        ,'2Counter-Referral','Reply From'             ,10, 2,1,30,255,''         ,1,1,'' ,'' ,'Who replied?');
INSERT INTO layout_options VALUES ('REF','reply_init_diag'   ,'2Counter-Referral','Presumed Diagnosis'     ,15, 2,0,30,255,''         ,1,1,'' ,'' ,'Presumed diagnosis by specialist');
INSERT INTO layout_options VALUES ('REF','reply_final_diag'  ,'2Counter-Referral','Final Diagnosis'        ,20, 2,1,30,255,''         ,1,1,'' ,'' ,'Final diagnosis by specialist');
INSERT INTO layout_options VALUES ('REF','reply_documents'   ,'2Counter-Referral','Documents'              ,25, 2,1,30,255,''         ,1,1,'' ,'' ,'Where may related scanned or paper documents be found?');
INSERT INTO layout_options VALUES ('REF','reply_findings'    ,'2Counter-Referral','Findings'               ,30, 3,1,30,  3,''         ,1,1,'' ,'' ,'Findings by specialist');
INSERT INTO layout_options VALUES ('REF','reply_services'    ,'2Counter-Referral','Services Provided'      ,35, 3,0,30,  3,''         ,1,1,'' ,'' ,'Service provided by specialist');
INSERT INTO layout_options VALUES ('REF','reply_related_code','2Counter-Referral','Service Provided'       ,40,15,1,30,255,''         ,1,1,'' ,'' ,'Billing Code for actual services provided');
INSERT INTO layout_options VALUES ('REF','reply_recommend'   ,'2Counter-Referral','Recommendations'        ,45, 3,1,30,  3,''         ,1,1,'' ,'' ,'Recommendations by specialist');
INSERT INTO layout_options VALUES ('REF','reply_rx_refer'    ,'2Counter-Referral','Prescriptions/Referrals',50, 3,1,30,  3,''         ,1,1,'' ,'' ,'Prescriptions and/or referrals by specialist');
#EndIf

#IfMissingColumn patient_data usertext11
ALTER TABLE patient_data
  ADD usertext11 varchar(255) NOT NULL DEFAULT '';
#EndIf
#IfMissingColumn patient_data usertext12
ALTER TABLE patient_data
  ADD usertext12 varchar(255) NOT NULL DEFAULT '';
#EndIf
#IfMissingColumn patient_data usertext13
ALTER TABLE patient_data
  ADD usertext13 varchar(255) NOT NULL DEFAULT '';
#EndIf
#IfMissingColumn patient_data usertext14
ALTER TABLE patient_data
  ADD usertext14 varchar(255) NOT NULL DEFAULT '';
#EndIf
#IfMissingColumn patient_data usertext15
ALTER TABLE patient_data
  ADD usertext15 varchar(255) NOT NULL DEFAULT '';
#EndIf
#IfMissingColumn patient_data usertext16
ALTER TABLE patient_data
  ADD usertext16 varchar(255) NOT NULL DEFAULT '';
#EndIf
#IfMissingColumn patient_data usertext17
ALTER TABLE patient_data
  ADD usertext17 varchar(255) NOT NULL DEFAULT '';
#EndIf
#IfMissingColumn patient_data usertext18
ALTER TABLE patient_data
  ADD usertext18 varchar(255) NOT NULL DEFAULT '';
#EndIf
#IfMissingColumn patient_data usertext19
ALTER TABLE patient_data
  ADD usertext19 varchar(255) NOT NULL DEFAULT '';
#EndIf
#IfMissingColumn patient_data usertext20
ALTER TABLE patient_data
  ADD usertext20 varchar(255) NOT NULL DEFAULT '';
#EndIf

#IfNotRow2D layout_options form_id DEM field_id usertext11
INSERT INTO `layout_options` VALUES ('DEM', 'usertext11', '6Misc', 'User Defined Text 11', 8,2,0,10,63,'',1,1,'','','User Defined');
#EndIf
#IfNotRow2D layout_options form_id DEM field_id usertext12
INSERT INTO `layout_options` VALUES ('DEM', 'usertext12', '6Misc', 'User Defined Text 12', 8,2,0,10,63,'',1,1,'','','User Defined');
#EndIf
#IfNotRow2D layout_options form_id DEM field_id usertext13
INSERT INTO `layout_options` VALUES ('DEM', 'usertext13', '6Misc', 'User Defined Text 13', 8,2,0,10,63,'',1,1,'','','User Defined');
#EndIf
#IfNotRow2D layout_options form_id DEM field_id usertext14
INSERT INTO `layout_options` VALUES ('DEM', 'usertext14', '6Misc', 'User Defined Text 14', 8,2,0,10,63,'',1,1,'','','User Defined');
#EndIf
#IfNotRow2D layout_options form_id DEM field_id usertext15
INSERT INTO `layout_options` VALUES ('DEM', 'usertext15', '6Misc', 'User Defined Text 15', 8,2,0,10,63,'',1,1,'','','User Defined');
#EndIf
#IfNotRow2D layout_options form_id DEM field_id usertext16
INSERT INTO `layout_options` VALUES ('DEM', 'usertext16', '6Misc', 'User Defined Text 16', 8,2,0,10,63,'',1,1,'','','User Defined');
#EndIf
#IfNotRow2D layout_options form_id DEM field_id usertext17
INSERT INTO `layout_options` VALUES ('DEM', 'usertext17', '6Misc', 'User Defined Text 17', 8,2,0,10,63,'',1,1,'','','User Defined');
#EndIf
#IfNotRow2D layout_options form_id DEM field_id usertext18
INSERT INTO `layout_options` VALUES ('DEM', 'usertext18', '6Misc', 'User Defined Text 18', 8,2,0,10,63,'',1,1,'','','User Defined');
#EndIf
#IfNotRow2D layout_options form_id DEM field_id usertext19
INSERT INTO `layout_options` VALUES ('DEM', 'usertext19', '6Misc', 'User Defined Text 19', 8,2,0,10,63,'',1,1,'','','User Defined');
#EndIf
#IfNotRow2D layout_options form_id DEM field_id usertext20
INSERT INTO `layout_options` VALUES ('DEM', 'usertext20', '6Misc', 'User Defined Text 20', 8,2,0,10,63,'',1,1,'','','User Defined');
#EndIf

#IfNotRow code_types ct_key REF
INSERT INTO code_types (ct_key, ct_id, ct_seq, ct_mod, ct_just, ct_fee, ct_rel, ct_nofs, ct_diag) VALUES ('REF',16, 5, 0, '', 0, 1, 1, 0);
#EndIf

#IfNotRow2D list_options list_id lists option_id actorest
INSERT INTO list_options ( list_id, option_id, title, seq, is_default ) VALUES ('lists','actorest','Actual or Estimated', 1,0);
INSERT INTO list_options ( list_id, option_id, title, seq, is_default ) VALUES ('actorest','act'  ,'Actual'   ,10,1);
INSERT INTO list_options ( list_id, option_id, title, seq, is_default ) VALUES ('actorest','est'  ,'Estimated',20,0);
UPDATE layout_options SET group_name = '1Who', title='', seq = 7, data_type = 1,
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
  1,  1, 2, 0, 0, 'boolean'    , 1, 3, '', '', 'Is this the first contraceptive accepted at this clinic?');
INSERT INTO layout_options VALUES ('LBFccicon', 'curmethod', '1', 'Current Method',
  2,  1, 1, 0, 0, 'contrameth' , 1, 3, '', '', 'Method in use at start of visit');
INSERT INTO layout_options VALUES ('LBFccicon', 'pastmodern','1', 'Previous modern contraceptive use?',
  3,  1, 1, 0, 0, 'boolean'    , 1, 3, '', '', 'Was a modern contraceptive method used at some time in the past?');
INSERT INTO layout_options VALUES ('LBFccicon', 'reqmethod', '1', 'Requested Method',
  4,  1, 1, 0, 0, 'contrameth' , 1, 3, '', '', 'Method requested by the client');
INSERT INTO layout_options VALUES ('LBFccicon', 'newmethod', '1', 'Adopted Method',
  5,  1, 1, 0, 0, 'ippfconmeth', 1, 3, '', '', 'Method adopted in this visit');
INSERT INTO layout_options VALUES ('LBFccicon', 'provider' , '1', 'Service Provider',
  6, 10, 1, 0, 0, ''           , 1, 3, '', '', 'Provider of this initial consultation');
INSERT INTO layout_options VALUES ('LBFccicon', 'mcreason' , '1', 'Reason for Method Change',
  7,  1, 1, 0, 0, 'mcreason'   , 1, 3, '', '', 'Reason for method change');

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

#IfNotRow code_types ct_id 31

# Add IPPF2 code set (numeric type 31).
INSERT INTO code_types (ct_key,ct_id,ct_seq,ct_mod,ct_just,ct_fee,ct_rel,ct_nofs,ct_diag) VALUES ('IPPF2',31,7,0,'',0,0,1,0);
INSERT INTO codes ( code_type, code, modifier, code_text ) VALUES ( 31, '1110010900000', '', 'Contraceptives - Counselling - General' );
INSERT INTO codes ( code_type, code, modifier, code_text ) VALUES ( 31, '1121120000000', '', 'Oral Contraceptives - Consultation' );
INSERT INTO codes ( code_type, code, modifier, code_text ) VALUES ( 31, '1122120000000', '', 'Injectable - Consultation' );
INSERT INTO codes ( code_type, code, modifier, code_text ) VALUES ( 31, '1123020000000', '', 'Patch / Ring - Consultation' );
INSERT INTO codes ( code_type, code, modifier, code_text ) VALUES ( 31, '1124020000800', '', 'Male / Female condom - unable to categorise' );
INSERT INTO codes ( code_type, code, modifier, code_text ) VALUES ( 31, '1124120000000', '', 'Male condom - Consultation' );
INSERT INTO codes ( code_type, code, modifier, code_text ) VALUES ( 31, '1124220000000', '', 'Female condom - Consultation' );
INSERT INTO codes ( code_type, code, modifier, code_text ) VALUES ( 31, '1125020000000', '', 'Diaphragm / Cervical cap - Consultation' );
INSERT INTO codes ( code_type, code, modifier, code_text ) VALUES ( 31, '1126020000000', '', 'Spermicides - Consultation' );
INSERT INTO codes ( code_type, code, modifier, code_text ) VALUES ( 31, '1131020211000', '', 'Implant - Consultation - Removal' );
INSERT INTO codes ( code_type, code, modifier, code_text ) VALUES ( 31, '1131120000000', '', 'Implant - Consultation' );
INSERT INTO codes ( code_type, code, modifier, code_text ) VALUES ( 31, '1132020211000', '', 'IUD - Consultation - Removal' );
INSERT INTO codes ( code_type, code, modifier, code_text ) VALUES ( 31, '1132120000000', '', 'IUD - Consultation' );
INSERT INTO codes ( code_type, code, modifier, code_text ) VALUES ( 31, '1141000000800', '', 'F / MVSC - unable to categorise' );
INSERT INTO codes ( code_type, code, modifier, code_text ) VALUES ( 31, '1141110000000', '', 'FVSC - Counselling' );
INSERT INTO codes ( code_type, code, modifier, code_text ) VALUES ( 31, '1141120000000', '', 'FVSC - Consultation' );
INSERT INTO codes ( code_type, code, modifier, code_text ) VALUES ( 31, '1141130302101', '', 'FVSC - Management - Surgical - Reversal' );
INSERT INTO codes ( code_type, code, modifier, code_text ) VALUES ( 31, '1141130302102', '', 'FVSC - Management - Surgical - Minilaparotomy' );
INSERT INTO codes ( code_type, code, modifier, code_text ) VALUES ( 31, '1141130302103', '', 'FVSC - Management - Surgical - Laparoscopy' );
INSERT INTO codes ( code_type, code, modifier, code_text ) VALUES ( 31, '1141130302104', '', 'FVSC - Management - Surgical - Laparotomy' );
INSERT INTO codes ( code_type, code, modifier, code_text ) VALUES ( 31, '1141130302105', '', 'FVSC - Management - Surgical - Hysteroscopy (ESSURE®)' );
INSERT INTO codes ( code_type, code, modifier, code_text ) VALUES ( 31, '1141130302106', '', 'FVSC - Management - Surgical - Trans vaginal tubal ligation' );
INSERT INTO codes ( code_type, code, modifier, code_text ) VALUES ( 31, '1141130302107', '', 'FVSC - Management - Surgical - FVSC follow up' );
INSERT INTO codes ( code_type, code, modifier, code_text ) VALUES ( 31, '1141130000800', '', 'FVSC - Management - unable to categorise' );
INSERT INTO codes ( code_type, code, modifier, code_text ) VALUES ( 31, '1142010000000', '', 'MVSC - Counselling' );
INSERT INTO codes ( code_type, code, modifier, code_text ) VALUES ( 31, '1142020000000', '', 'MVSC - Consultation' );
INSERT INTO codes ( code_type, code, modifier, code_text ) VALUES ( 31, '1142030302101', '', 'MVSC - Management - Surgical - Reversal' );
INSERT INTO codes ( code_type, code, modifier, code_text ) VALUES ( 31, '1142030302201', '', 'MVSC - Management - Surgical - Incisional Vasectomy' );
INSERT INTO codes ( code_type, code, modifier, code_text ) VALUES ( 31, '1142030302202', '', 'MVSC - Management - Surgical - No-scalpel Vasectomy' );
INSERT INTO codes ( code_type, code, modifier, code_text ) VALUES ( 31, '1142030302203', '', 'MVSC - Management - Surgical - MVSC follow up' );
INSERT INTO codes ( code_type, code, modifier, code_text ) VALUES ( 31, '1142030000800', '', 'MVSC - Management - unable to categorise' );
INSERT INTO codes ( code_type, code, modifier, code_text ) VALUES ( 31, '1151010000000', '', 'EC - Counselling' );
INSERT INTO codes ( code_type, code, modifier, code_text ) VALUES ( 31, '1151020000000', '', 'EC - Consultation' );
INSERT INTO codes ( code_type, code, modifier, code_text ) VALUES ( 31, '1210020000000', '', 'FAB - Consultation' );
INSERT INTO codes ( code_type, code, modifier, code_text ) VALUES ( 31, '2111010121000', '', 'Abortion - Counselling - Pre-abortion / Options Counselling' );
INSERT INTO codes ( code_type, code, modifier, code_text ) VALUES ( 31, '2111010122000', '', 'Abortion - Counselling - Post-abortion' );
INSERT INTO codes ( code_type, code, modifier, code_text ) VALUES ( 31, '2111010000800', '', 'Abortion - Counselling - unable to categorise' );
INSERT INTO codes ( code_type, code, modifier, code_text ) VALUES ( 31, '2112020200000', '', 'Abortion - Consultation' );
INSERT INTO codes ( code_type, code, modifier, code_text ) VALUES ( 31, '2112020201101', '', 'Abortion - Consultation - Initial consultation - Harm reduction model' );
INSERT INTO codes ( code_type, code, modifier, code_text ) VALUES ( 31, '2112020202101', '', 'Abortion - Consultation - Follow up consultation - Harm reduction model' );
INSERT INTO codes ( code_type, code, modifier, code_text ) VALUES ( 31, '2112020000800', '', 'Abortion - unable to categorise' );
INSERT INTO codes ( code_type, code, modifier, code_text ) VALUES ( 31, '2113130301101', '', 'Abortion - Management - Medical - Misoprostol' );
INSERT INTO codes ( code_type, code, modifier, code_text ) VALUES ( 31, '2113130301102', '', 'Abortion - Management - Medical - Mifepristone and misoprostol' );
INSERT INTO codes ( code_type, code, modifier, code_text ) VALUES ( 31, '2113130301103', '', 'Abortion - Management - Medical - Methotrexate and misoprostol' );
INSERT INTO codes ( code_type, code, modifier, code_text ) VALUES ( 31, '2113130301110', '', 'Abortion - Management - Medical - Treatment of complications' );
INSERT INTO codes ( code_type, code, modifier, code_text ) VALUES ( 31, '2113130301800', '', 'Abortion - Management - Medical -  Unable to categorise' );
INSERT INTO codes ( code_type, code, modifier, code_text ) VALUES ( 31, '2113130301104', '', 'Abortion - Management - Medical - follow up' );
INSERT INTO codes ( code_type, code, modifier, code_text ) VALUES ( 31, '2113230302301', '', 'Abortion - Management - Surgical - D&C' );
INSERT INTO codes ( code_type, code, modifier, code_text ) VALUES ( 31, '2113230302302', '', 'Abortion - Management - Surgical - D&E' );
INSERT INTO codes ( code_type, code, modifier, code_text ) VALUES ( 31, '2113230302304', '', 'Abortion - Management - Surgical - Vacuum aspiration' );
INSERT INTO codes ( code_type, code, modifier, code_text ) VALUES ( 31, '2113230302305', '', 'Abortion - Management - Surgical - Ethacridine lactate' );
INSERT INTO codes ( code_type, code, modifier, code_text ) VALUES ( 31, '2113230302310', '', 'Abortion - Management - Surgical - Treatment of complications' );
INSERT INTO codes ( code_type, code, modifier, code_text ) VALUES ( 31, '2113230302800', '', 'Abortion - Management - Surgical - unable to categorise' );
INSERT INTO codes ( code_type, code, modifier, code_text ) VALUES ( 31, '2113230302307', '', 'Abortion - Management - Surgical - follow up' );
INSERT INTO codes ( code_type, code, modifier, code_text ) VALUES ( 31, '2114030301101', '', 'Incomplete abortion - Management - Medical - Misoprostol' );
INSERT INTO codes ( code_type, code, modifier, code_text ) VALUES ( 31, '2114030302303', '', 'Incomplete abortion - Management - Surgical - D&C or D&E' );
INSERT INTO codes ( code_type, code, modifier, code_text ) VALUES ( 31, '2114030302304', '', 'Incomplete abortion - Management - Surgical - Vacuum aspiration' );
INSERT INTO codes ( code_type, code, modifier, code_text ) VALUES ( 31, '2114030302800', '', 'Incomplete abortion - Management - unable to categorise' );
INSERT INTO codes ( code_type, code, modifier, code_text ) VALUES ( 31, '2121010111000', '', 'HIV and AIDS - Counselling - Pre-test' );
INSERT INTO codes ( code_type, code, modifier, code_text ) VALUES ( 31, '2121010112000', '', 'HIV and AIDS - Counselling - Post-test' );
INSERT INTO codes ( code_type, code, modifier, code_text ) VALUES ( 31, '2121010123000', '', 'HIV and AIDS - Counselling - Risk reduction' );
INSERT INTO codes ( code_type, code, modifier, code_text ) VALUES ( 31, '2121010124000', '', 'HIV and AIDS - Counselling - Psycho-social support' );
INSERT INTO codes ( code_type, code, modifier, code_text ) VALUES ( 31, '2121010000800', '', 'HIV and AIDS - Counselling - unable to categorise' );
INSERT INTO codes ( code_type, code, modifier, code_text ) VALUES ( 31, '2122020200000', '', 'HIV and AIDS - Consultation' );
INSERT INTO codes ( code_type, code, modifier, code_text ) VALUES ( 31, '2123030301301', '', 'HIV and AIDS - Management - Medical - ARVs' );
INSERT INTO codes ( code_type, code, modifier, code_text ) VALUES ( 31, '2123030301501', '', 'HIV and AIDS - Management - Medical - OI (TB)' );
INSERT INTO codes ( code_type, code, modifier, code_text ) VALUES ( 31, '2123030301502', '', 'HIV and AIDS - Management - Medical - OI (Malaria)' );
INSERT INTO codes ( code_type, code, modifier, code_text ) VALUES ( 31, '2123030301509', '', 'HIV and AIDS - Management - Medical - OI (Other)' );
INSERT INTO codes ( code_type, code, modifier, code_text ) VALUES ( 31, '2123030000800', '', 'HIV and AIDS - Management - unable to categorise' );
INSERT INTO codes ( code_type, code, modifier, code_text ) VALUES ( 31, '2124040401101', '', 'HIV and AIDS - Prevention - Prophylaxis - ARVs' );
INSERT INTO codes ( code_type, code, modifier, code_text ) VALUES ( 31, '2125050502000', '', 'HIV and AIDS - Investigation - Examination' );
INSERT INTO codes ( code_type, code, modifier, code_text ) VALUES ( 31, '2125050503101', '', 'HIV and AIDS - Investigation - Lab test - Diagnostic Ab test' );
INSERT INTO codes ( code_type, code, modifier, code_text ) VALUES ( 31, '2125050503102', '', 'HIV and AIDS - Investigation - Lab test - Diagnostic Ag test' );
INSERT INTO codes ( code_type, code, modifier, code_text ) VALUES ( 31, '2125050503103', '', 'HIV and AIDS - Investigation - Lab test - Diagnostic PCR test' );
INSERT INTO codes ( code_type, code, modifier, code_text ) VALUES ( 31, '2125050503104', '', 'HIV and AIDS - Investigation - Lab test - Diagnostic Rapid test' );
INSERT INTO codes ( code_type, code, modifier, code_text ) VALUES ( 31, '2125050503105', '', 'HIV and AIDS - Investigation - Lab test - Monitoring viral load test' );
INSERT INTO codes ( code_type, code, modifier, code_text ) VALUES ( 31, '2125050503106', '', 'HIV and AIDS - Investigation - Lab test - Monitoring CD4 count test' );
INSERT INTO codes ( code_type, code, modifier, code_text ) VALUES ( 31, '2125050503888', '', 'HIV and AIDS - Investigation - Lab test - unable to categorise' );
INSERT INTO codes ( code_type, code, modifier, code_text ) VALUES ( 31, '2125050504000', '', 'HIV and AIDS - Investigation - Sampling procedure' );
INSERT INTO codes ( code_type, code, modifier, code_text ) VALUES ( 31, '2131010111000', '', 'STI / RTI - Counselling - Pre-test' );
INSERT INTO codes ( code_type, code, modifier, code_text ) VALUES ( 31, '2131010112000', '', 'STI / RTI - Counselling - Post-test' );
INSERT INTO codes ( code_type, code, modifier, code_text ) VALUES ( 31, '2131010123000', '', 'STI / RTI - Counselling - Risk reduction' );
INSERT INTO codes ( code_type, code, modifier, code_text ) VALUES ( 31, '2131010000800', '', 'STI / RTI - Counselling - unable to categorise' );
INSERT INTO codes ( code_type, code, modifier, code_text ) VALUES ( 31, '2132020200000', '', 'STI / RTI - Consultation' );
INSERT INTO codes ( code_type, code, modifier, code_text ) VALUES ( 31, '2133130303000', '', 'STI/RTI - Management - Syndromic' );
INSERT INTO codes ( code_type, code, modifier, code_text ) VALUES ( 31, '2133230304000', '', 'STI/RTI - Management - Etiological - Other' );
INSERT INTO codes ( code_type, code, modifier, code_text ) VALUES ( 31, '2133230304101', '', 'STI/RTI - Management - Etiological - Chlamydia' );
INSERT INTO codes ( code_type, code, modifier, code_text ) VALUES ( 31, '2133230304102', '', 'STI/RTI - Management - Etiological - Chancroid' );
INSERT INTO codes ( code_type, code, modifier, code_text ) VALUES ( 31, '2133230304103', '', 'STI/RTI - Management - Etiological - Gonorrhoea' );
INSERT INTO codes ( code_type, code, modifier, code_text ) VALUES ( 31, '2133230304104', '', 'STI/RTI - Management - Etiological - Syphilis' );
INSERT INTO codes ( code_type, code, modifier, code_text ) VALUES ( 31, '2133230304105', '', 'STI/RTI - Management - Etiological - Human Papillomavirus (HPV)' );
INSERT INTO codes ( code_type, code, modifier, code_text ) VALUES ( 31, '2133230304800', '', 'STI/RTI - Management - Etiological - unable to categorise' );
INSERT INTO codes ( code_type, code, modifier, code_text ) VALUES ( 31, '2134040401201', '', 'STI/RTI - Prevention - Prophylaxis - Hep A vaccination' );
INSERT INTO codes ( code_type, code, modifier, code_text ) VALUES ( 31, '2134040401202', '', 'STI/RTI - Prevention - Prophylaxis - Hep B vaccination' );
INSERT INTO codes ( code_type, code, modifier, code_text ) VALUES ( 31, '2134040401203', '', 'STI/RTI - Prevention - Prophylaxis - HPV vaccination' );
INSERT INTO codes ( code_type, code, modifier, code_text ) VALUES ( 31, '2134040401800', '', 'STI / RTI - Prevention - unable to categorise' );
INSERT INTO codes ( code_type, code, modifier, code_text ) VALUES ( 31, '2135050502000', '', 'STI/RTI - Investigation - Examination' );
INSERT INTO codes ( code_type, code, modifier, code_text ) VALUES ( 31, '2135050503000', '', 'STI/RTI - Investigation - Lab test' );
INSERT INTO codes ( code_type, code, modifier, code_text ) VALUES ( 31, '2135050504000', '', 'STI/RTI - Investigation - Sampling procedure' );
INSERT INTO codes ( code_type, code, modifier, code_text ) VALUES ( 31, '2135050000800', '', 'STI / RTI - Investigation - unable to categorise' );
INSERT INTO codes ( code_type, code, modifier, code_text ) VALUES ( 31, '2141010111101', '', 'Gynecology - Counselling - Pre test - Cervical cancer' );
INSERT INTO codes ( code_type, code, modifier, code_text ) VALUES ( 31, '2141010112101', '', 'Gynecology - Counselling - Post test - Cervical cancer' );
INSERT INTO codes ( code_type, code, modifier, code_text ) VALUES ( 31, '2141010900102', '', 'Gynecology - Counselling - General - Breast cancer' );
INSERT INTO codes ( code_type, code, modifier, code_text ) VALUES ( 31, '2141010900999', '', 'Gynecology - Counselling - General - other' );
INSERT INTO codes ( code_type, code, modifier, code_text ) VALUES ( 31, '2141010000800', '', 'Gynecology - Counselling - unable to categorise' );
INSERT INTO codes ( code_type, code, modifier, code_text ) VALUES ( 31, '2142020200000', '', 'Gynecology - Consultation' );
INSERT INTO codes ( code_type, code, modifier, code_text ) VALUES ( 31, '2142020000800', '', 'Gynecology - Consultation - unable to categorise' );
INSERT INTO codes ( code_type, code, modifier, code_text ) VALUES ( 31, '2143130301201', '', 'Gynecology - Management - Medical - Menstrual Regulation' );
INSERT INTO codes ( code_type, code, modifier, code_text ) VALUES ( 31, '2143130301202', '', 'Gynecology - Management - Medical - Erratic mensturation' );
INSERT INTO codes ( code_type, code, modifier, code_text ) VALUES ( 31, '2143130301999', '', 'Gynecology - Management - Medical - Other' );
INSERT INTO codes ( code_type, code, modifier, code_text ) VALUES ( 31, '2143130000800', '', 'Gynecology - Management - Medical - unable to categorise' );
INSERT INTO codes ( code_type, code, modifier, code_text ) VALUES ( 31, '2143230302401', '', 'Gynecology - Management - Surgical - Cryosurgery' );
INSERT INTO codes ( code_type, code, modifier, code_text ) VALUES ( 31, '2143230302402', '', 'Gynecology - Management - Surgical - Cauterisation' );
INSERT INTO codes ( code_type, code, modifier, code_text ) VALUES ( 31, '2143230302701', '', 'Gynecology - Management - Surgical - Breast cancer' );
INSERT INTO codes ( code_type, code, modifier, code_text ) VALUES ( 31, '2143230302306', '', 'Gynecology - Management - Surgical - Menstrual Regulation' );
INSERT INTO codes ( code_type, code, modifier, code_text ) VALUES ( 31, '2143230302999', '', 'Gynecology - Management - Surgical - other' );
INSERT INTO codes ( code_type, code, modifier, code_text ) VALUES ( 31, '2143230302800', '', 'Gynecology - Management - Surgical - unable to categorise' );
INSERT INTO codes ( code_type, code, modifier, code_text ) VALUES ( 31, '2144040402101', '', 'Gynecology - Prevention - Screening - PAP (sampling procedure)' );
INSERT INTO codes ( code_type, code, modifier, code_text ) VALUES ( 31, '2144040402102', '', 'Gynecology - Prevention - Screening - PAP (lab test)' );
INSERT INTO codes ( code_type, code, modifier, code_text ) VALUES ( 31, '2144040402103', '', 'Gynecology - Prevention - Screening - Visual inspection (VIA or VILI)' );
INSERT INTO codes ( code_type, code, modifier, code_text ) VALUES ( 31, '2144040402800', '', 'Gynecology - Prevention - Screening - unable to categorise' );
INSERT INTO codes ( code_type, code, modifier, code_text ) VALUES ( 31, '2145050501000', '', 'Gynecology - Investigation - Diagnostic Imaging - other' );
INSERT INTO codes ( code_type, code, modifier, code_text ) VALUES ( 31, '2145050501101', '', 'Gynecology - Investigation - Diagnostic Imaging - Mamography' );
INSERT INTO codes ( code_type, code, modifier, code_text ) VALUES ( 31, '2145050501102', '', 'Gynecology - Investigation - Diagnostic Imaging - Colposcopy' );
INSERT INTO codes ( code_type, code, modifier, code_text ) VALUES ( 31, '2145050502101', '', 'Gynecology - Investigation - Examination - Manual breast exam' );
INSERT INTO codes ( code_type, code, modifier, code_text ) VALUES ( 31, '2145050502102', '', 'Gynecology - Investigation - Examination - Bimanual pelvic exam' );
INSERT INTO codes ( code_type, code, modifier, code_text ) VALUES ( 31, '2145050502999', '', 'Gynecology - Investigation - Examination - other' );
INSERT INTO codes ( code_type, code, modifier, code_text ) VALUES ( 31, '2145050503000', '', 'Gynecology - Investigation - Lab test' );
INSERT INTO codes ( code_type, code, modifier, code_text ) VALUES ( 31, '2145050504000', '', 'Gynecology - Investigation - Sampling procedure' );
INSERT INTO codes ( code_type, code, modifier, code_text ) VALUES ( 31, '2145050000800', '', 'Gynecology - Investigation - unable to categorise' );
INSERT INTO codes ( code_type, code, modifier, code_text ) VALUES ( 31, '2150000000000', '', 'SRH non-CONTRACEPTION - OBSTETRIC SERVICES' );
INSERT INTO codes ( code_type, code, modifier, code_text ) VALUES ( 31, '2151010131000', '', 'Obstetrics - Counselling - Ante natal' );
INSERT INTO codes ( code_type, code, modifier, code_text ) VALUES ( 31, '2151010132000', '', 'Obstetrics - Counselling - Post natal' );
INSERT INTO codes ( code_type, code, modifier, code_text ) VALUES ( 31, '2151010000800', '', 'Obstetrics - Counselling - unable to categorise' );
INSERT INTO codes ( code_type, code, modifier, code_text ) VALUES ( 31, '2152020221000', '', 'Obstetrics - Consultation - Ante natal' );
INSERT INTO codes ( code_type, code, modifier, code_text ) VALUES ( 31, '2152020222000', '', 'Obstetrics - Consultation - Post natal' );
INSERT INTO codes ( code_type, code, modifier, code_text ) VALUES ( 31, '2152020000800', '', 'Obstetrics - Consultation - unable to categorise' );
INSERT INTO codes ( code_type, code, modifier, code_text ) VALUES ( 31, '2153130301401', '', 'Obstetrics -Management - Medical - Vaginal Delivery' );
INSERT INTO codes ( code_type, code, modifier, code_text ) VALUES ( 31, '2153130301402', '', 'Obstetrics -Management - Medical - EmOC' );
INSERT INTO codes ( code_type, code, modifier, code_text ) VALUES ( 31, '2153130301800', '', 'Obstetrics - management - Medical - unable to categorise' );
INSERT INTO codes ( code_type, code, modifier, code_text ) VALUES ( 31, '2153230302501', '', 'Obstetrics - Management - Surgical - C-Section' );
INSERT INTO codes ( code_type, code, modifier, code_text ) VALUES ( 31, '2153230302800', '', 'Obstetrics - Management - Surgical - unable to categorise' );
INSERT INTO codes ( code_type, code, modifier, code_text ) VALUES ( 31, '2154040401301', '', 'Obstetrics - Prevention - Prophylaxis - Ante-natal vaccinations' );
INSERT INTO codes ( code_type, code, modifier, code_text ) VALUES ( 31, '2155050501201', '', 'Obstetrics - Investigations - Diagnostic imaging - Ante natal ultrasound' );
INSERT INTO codes ( code_type, code, modifier, code_text ) VALUES ( 31, '2155050501202', '', 'Obstetrics - Investigations - Diagnostic imaging - Post natal ultrasound' );
INSERT INTO codes ( code_type, code, modifier, code_text ) VALUES ( 31, '2152088000000', '', 'Obstetrics - Investigations - unable to categorise' );
INSERT INTO codes ( code_type, code, modifier, code_text ) VALUES ( 31, '2155050502201', '', 'Obstetrics - Investigations - Examination - Ante natal' );
INSERT INTO codes ( code_type, code, modifier, code_text ) VALUES ( 31, '2155050503202', '', 'Obstetrics - Investigations - Examination - Post natal' );
INSERT INTO codes ( code_type, code, modifier, code_text ) VALUES ( 31, '2155050503301', '', 'Obstetrics - Investigations - Lab tests - Pregnancy test' );
INSERT INTO codes ( code_type, code, modifier, code_text ) VALUES ( 31, '2155050503401', '', 'Obstetrics - Investigations - Lab tests - Ante natal' );
INSERT INTO codes ( code_type, code, modifier, code_text ) VALUES ( 31, '2155050503402', '', 'Obstetrics - Investigations - Lab tests - Post natal' );
INSERT INTO codes ( code_type, code, modifier, code_text ) VALUES ( 31, '2155050504000', '', 'Obstetrics - Investigations - Sampling procedure' );
INSERT INTO codes ( code_type, code, modifier, code_text ) VALUES ( 31, '2161010100000', '', 'Urology - Counselling' );
INSERT INTO codes ( code_type, code, modifier, code_text ) VALUES ( 31, '2162020200000', '', 'Urology - Consultation' );
INSERT INTO codes ( code_type, code, modifier, code_text ) VALUES ( 31, '2163130301000', '', 'Urology - Management - Medical' );
INSERT INTO codes ( code_type, code, modifier, code_text ) VALUES ( 31, '2163230302601', '', 'Urology - Management - Surgery - Male Circumcision' );
INSERT INTO codes ( code_type, code, modifier, code_text ) VALUES ( 31, '2163230302999', '', 'Urology - Management - Surgery - other' );
INSERT INTO codes ( code_type, code, modifier, code_text ) VALUES ( 31, '2163230000800', '', 'Urology - Management - unable to categorise' );
INSERT INTO codes ( code_type, code, modifier, code_text ) VALUES ( 31, '2164040402201', '', 'Urology - Prevention - Screening - Prostate cancer' );
INSERT INTO codes ( code_type, code, modifier, code_text ) VALUES ( 31, '2165050501000', '', 'Urology - Investigations - Diagnostic Imaging' );
INSERT INTO codes ( code_type, code, modifier, code_text ) VALUES ( 31, '2165050502000', '', 'Urology - Investigations - Examination' );
INSERT INTO codes ( code_type, code, modifier, code_text ) VALUES ( 31, '2165050503000', '', 'Urology - Investigations - Lab test' );
INSERT INTO codes ( code_type, code, modifier, code_text ) VALUES ( 31, '2165050504000', '', 'Urology - Investigations - Sampling procedure' );
INSERT INTO codes ( code_type, code, modifier, code_text ) VALUES ( 31, '2165050000800', '', 'Urology - Investigations - unable to categorise' );
INSERT INTO codes ( code_type, code, modifier, code_text ) VALUES ( 31, '2171110000000', '', 'Subfertility - Counselling' );
INSERT INTO codes ( code_type, code, modifier, code_text ) VALUES ( 31, '2171110000800', '', 'Subfertility - unable to categorise' );
INSERT INTO codes ( code_type, code, modifier, code_text ) VALUES ( 31, '2172120000000', '', 'Subfertility - Consultation' );
INSERT INTO codes ( code_type, code, modifier, code_text ) VALUES ( 31, '2173130301203', '', 'Subfertility - Management - Medical - Hormone / ovulation therapy' );
INSERT INTO codes ( code_type, code, modifier, code_text ) VALUES ( 31, '2173130301403', '', 'Subfertility - Management - Medical - Assisted Conception' );
INSERT INTO codes ( code_type, code, modifier, code_text ) VALUES ( 31, '2173130301800', '', 'Subfertility - Management - unable to categorise' );
INSERT INTO codes ( code_type, code, modifier, code_text ) VALUES ( 31, '2173230302000', '', 'Subfertility - Management - Surgery' );
INSERT INTO codes ( code_type, code, modifier, code_text ) VALUES ( 31, '2174040501000', '', 'Subfertility - Investigations - Diagnostic imaging' );
INSERT INTO codes ( code_type, code, modifier, code_text ) VALUES ( 31, '2174040502000', '', 'Subfertility - Investigations - Examination' );
INSERT INTO codes ( code_type, code, modifier, code_text ) VALUES ( 31, '2174040503000', '', 'Subfertility - Investigations - Lab test' );
INSERT INTO codes ( code_type, code, modifier, code_text ) VALUES ( 31, '2174040504000', '', 'Subfertility - Investigations - Sampling procedure' );
INSERT INTO codes ( code_type, code, modifier, code_text ) VALUES ( 31, '2174040000800', '', 'Subfertility - Investigations - unable to categorise' );
INSERT INTO codes ( code_type, code, modifier, code_text ) VALUES ( 31, '2181110141000', '', 'Specialised SRH services - Counselling - GBV' );
INSERT INTO codes ( code_type, code, modifier, code_text ) VALUES ( 31, '2181210142000', '', 'Specialised SRH services - Counselling - Relationship' );
INSERT INTO codes ( code_type, code, modifier, code_text ) VALUES ( 31, '2181310143000', '', 'Specialised SRH services - Counselling - Sexuality' );
INSERT INTO codes ( code_type, code, modifier, code_text ) VALUES ( 31, '2184140100301', '', 'Specialised SRH services - Prevention - Screening - GBV' );
INSERT INTO codes ( code_type, code, modifier, code_text ) VALUES ( 31, '2184100000800', '', 'Specialised SRH Services - unable to categorise' );
INSERT INTO codes ( code_type, code, modifier, code_text ) VALUES ( 31, '2191010000000', '', 'Paediatrics - Counselling' );
INSERT INTO codes ( code_type, code, modifier, code_text ) VALUES ( 31, '2192020000000', '', 'Paediatrics - Consultation' );
INSERT INTO codes ( code_type, code, modifier, code_text ) VALUES ( 31, '2192020900401', '', 'Paediatrics - Consultation - General - obesity' );
INSERT INTO codes ( code_type, code, modifier, code_text ) VALUES ( 31, '2192020900999', '', 'Paediatrics - Consultation - General - all other non-obesity' );
INSERT INTO codes ( code_type, code, modifier, code_text ) VALUES ( 31, '2193030000000', '', 'Paediatrics - Management' );
INSERT INTO codes ( code_type, code, modifier, code_text ) VALUES ( 31, '2193030101205', '', 'Paediatrics - Management - Medical - Asthma' );
INSERT INTO codes ( code_type, code, modifier, code_text ) VALUES ( 31, '2193030101999', '', 'Paediatrics - Management - Medical - all other non-asthma' );
INSERT INTO codes ( code_type, code, modifier, code_text ) VALUES ( 31, '2194040000000', '', 'Paediatrics - Prevention' );
INSERT INTO codes ( code_type, code, modifier, code_text ) VALUES ( 31, '2194040401401', '', 'Paediatrics - Prevention - Prophylaxis - Vaccination' );
INSERT INTO codes ( code_type, code, modifier, code_text ) VALUES ( 31, '2195050000000', '', 'Paediatrics - Investigations' );
INSERT INTO codes ( code_type, code, modifier, code_text ) VALUES ( 31, '2196000000800', '', 'Paediatrics - unable to categorise' );
INSERT INTO codes ( code_type, code, modifier, code_text ) VALUES ( 31, '2201010000000', '', 'SRH - Other - Counselling' );
INSERT INTO codes ( code_type, code, modifier, code_text ) VALUES ( 31, '2202020000000', '', 'SRH - Other - Consultation' );
INSERT INTO codes ( code_type, code, modifier, code_text ) VALUES ( 31, '2203030000000', '', 'SRH - Other - Management' );
INSERT INTO codes ( code_type, code, modifier, code_text ) VALUES ( 31, '2204040000000', '', 'SRH - Other - Prevention' );
INSERT INTO codes ( code_type, code, modifier, code_text ) VALUES ( 31, '2205050000000', '', 'SRH - Other - Investigation' );
INSERT INTO codes ( code_type, code, modifier, code_text ) VALUES ( 31, '4100000000000', '', 'Administration' );
INSERT INTO codes ( code_type, code, modifier, code_text ) VALUES ( 31, '3110010000000', '', 'Non-SRH Medical - Counselling' );
INSERT INTO codes ( code_type, code, modifier, code_text ) VALUES ( 31, '3110010900201', '', 'Non-SRH Medical - Counselling - General - Diabetes' );
INSERT INTO codes ( code_type, code, modifier, code_text ) VALUES ( 31, '3110010900202', '', 'Non-SRH Medical - Counselling - General - Hypertension' );
INSERT INTO codes ( code_type, code, modifier, code_text ) VALUES ( 31, '3110010900203', '', 'Non-SRH Medical - Counselling - General - Hyperlipidemia' );
INSERT INTO codes ( code_type, code, modifier, code_text ) VALUES ( 31, '3110010900301', '', 'Non-SRH Medical - Counselling - General - Mental health' );
INSERT INTO codes ( code_type, code, modifier, code_text ) VALUES ( 31, '3110010900401', '', 'Non-SRH Medical - Counselling - General - Obesity' );
INSERT INTO codes ( code_type, code, modifier, code_text ) VALUES ( 31, '3110010123201', '', 'Non-SRH Medical - Counselling - Risk reduction - Diabetes' );
INSERT INTO codes ( code_type, code, modifier, code_text ) VALUES ( 31, '3110010123202', '', 'Non-SRH Medical - Counselling - Risk reduction - Hypertension' );
INSERT INTO codes ( code_type, code, modifier, code_text ) VALUES ( 31, '3110010123203', '', 'Non-SRH Medical - Counselling - Risk reduction - Hyperlipidemia' );
INSERT INTO codes ( code_type, code, modifier, code_text ) VALUES ( 31, '3110010123204', '', 'Non-SRH Medical - Counselling - Risk reduction - COPD' );
INSERT INTO codes ( code_type, code, modifier, code_text ) VALUES ( 31, '3110010123501', '', 'Non-SRH Medical - Counselling - Risk reduction - Alcohol' );
INSERT INTO codes ( code_type, code, modifier, code_text ) VALUES ( 31, '3120020000000', '', 'Non-SRH Medical - Consultation' );
INSERT INTO codes ( code_type, code, modifier, code_text ) VALUES ( 31, '3120020900201', '', 'Non-SRH Medical - Consultation - General - Diabetes' );
INSERT INTO codes ( code_type, code, modifier, code_text ) VALUES ( 31, '3120020900202', '', 'Non-SRH Medical - Consultation - General - Hypertension' );
INSERT INTO codes ( code_type, code, modifier, code_text ) VALUES ( 31, '3120020900203', '', 'Non-SRH Medical - Consultation - General - Hyperlipidemia' );
INSERT INTO codes ( code_type, code, modifier, code_text ) VALUES ( 31, '3130030000000', '', 'Non-SRH Medical - Management' );
INSERT INTO codes ( code_type, code, modifier, code_text ) VALUES ( 31, '3130030101201', '', 'Non-SRH Medical - Management - Medical - Diabetes' );
INSERT INTO codes ( code_type, code, modifier, code_text ) VALUES ( 31, '3130030101202', '', 'Non-SRH Medical - Management - Medical - Hypertension' );
INSERT INTO codes ( code_type, code, modifier, code_text ) VALUES ( 31, '3130030101203', '', 'Non-SRH Medical - Management - Medical - Hyperlipidemia' );
INSERT INTO codes ( code_type, code, modifier, code_text ) VALUES ( 31, '3130030101204', '', 'Non-SRH Medical - Management - Medical - COPD' );
INSERT INTO codes ( code_type, code, modifier, code_text ) VALUES ( 31, '3130030101301', '', 'Non-SRH Medical - Management - Medical - Mental health' );
INSERT INTO codes ( code_type, code, modifier, code_text ) VALUES ( 31, '3140040000000', '', 'Non-SRH Medical - Prevention' );
INSERT INTO codes ( code_type, code, modifier, code_text ) VALUES ( 31, '3140040402201', '', 'Non-SRH Medical - Prevention - Screening - Diabetes' );
INSERT INTO codes ( code_type, code, modifier, code_text ) VALUES ( 31, '3140040402202', '', 'Non-SRH Medical - Prevention - Screening - Hypertension' );
INSERT INTO codes ( code_type, code, modifier, code_text ) VALUES ( 31, '3140040402203', '', 'Non-SRH Medical - Prevention - Screening - Hyperlipidemia' );
INSERT INTO codes ( code_type, code, modifier, code_text ) VALUES ( 31, '3150050000000', '', 'Non-SRH Medical - Investigation' );
INSERT INTO codes ( code_type, code, modifier, code_text ) VALUES ( 31, '3150000000800', '', 'Non-SRH Medical - unable to categorise' );

# Update products and MA/REF services, adding IPPF2 related codes deduced from existing IPPF related codes.
UPDATE codes SET related_code = CONCAT(related_code, ';IPPF2:1000000000000') WHERE related_code IS NOT NULL AND related_code LIKE '%IPPF:110000000%' AND related_code NOT LIKE '%IPPF2:%';
UPDATE drugs SET related_code = CONCAT(related_code, ';IPPF2:1000000000000') WHERE related_code IS NOT NULL AND related_code LIKE '%IPPF:110000000%' AND related_code NOT LIKE '%IPPF2:%';
UPDATE codes SET related_code = CONCAT(related_code, ';IPPF2:1100010000000') WHERE related_code IS NOT NULL AND related_code LIKE '%IPPF:141200000%' AND related_code NOT LIKE '%IPPF2:%';
UPDATE drugs SET related_code = CONCAT(related_code, ';IPPF2:1100010000000') WHERE related_code IS NOT NULL AND related_code LIKE '%IPPF:141200000%' AND related_code NOT LIKE '%IPPF2:%';
UPDATE codes SET related_code = CONCAT(related_code, ';IPPF2:1110010900000') WHERE related_code IS NOT NULL AND related_code LIKE '%IPPF:141200118%' AND related_code NOT LIKE '%IPPF2:%';
UPDATE drugs SET related_code = CONCAT(related_code, ';IPPF2:1110010900000') WHERE related_code IS NOT NULL AND related_code LIKE '%IPPF:141200118%' AND related_code NOT LIKE '%IPPF2:%';
UPDATE codes SET related_code = CONCAT(related_code, ';IPPF2:1110010900000') WHERE related_code IS NOT NULL AND related_code LIKE '%IPPF:141200218%' AND related_code NOT LIKE '%IPPF2:%';
UPDATE drugs SET related_code = CONCAT(related_code, ';IPPF2:1110010900000') WHERE related_code IS NOT NULL AND related_code LIKE '%IPPF:141200218%' AND related_code NOT LIKE '%IPPF2:%';
UPDATE codes SET related_code = CONCAT(related_code, ';IPPF2:1110010900000') WHERE related_code IS NOT NULL AND related_code LIKE '%IPPF:141200999%' AND related_code NOT LIKE '%IPPF2:%';
UPDATE drugs SET related_code = CONCAT(related_code, ';IPPF2:1110010900000') WHERE related_code IS NOT NULL AND related_code LIKE '%IPPF:141200999%' AND related_code NOT LIKE '%IPPF2:%';
UPDATE codes SET related_code = CONCAT(related_code, ';IPPF2:1110010900000') WHERE related_code IS NOT NULL AND related_code LIKE '%IPPF:111100119%' AND related_code NOT LIKE '%IPPF2:%';
UPDATE drugs SET related_code = CONCAT(related_code, ';IPPF2:1110010900000') WHERE related_code IS NOT NULL AND related_code LIKE '%IPPF:111100119%' AND related_code NOT LIKE '%IPPF2:%';
UPDATE codes SET related_code = CONCAT(related_code, ';IPPF2:1110010900000') WHERE related_code IS NOT NULL AND related_code LIKE '%IPPF:111110119%' AND related_code NOT LIKE '%IPPF2:%';
UPDATE drugs SET related_code = CONCAT(related_code, ';IPPF2:1110010900000') WHERE related_code IS NOT NULL AND related_code LIKE '%IPPF:111110119%' AND related_code NOT LIKE '%IPPF2:%';
UPDATE codes SET related_code = CONCAT(related_code, ';IPPF2:1110010900000') WHERE related_code IS NOT NULL AND related_code LIKE '%IPPF:111120119%' AND related_code NOT LIKE '%IPPF2:%';
UPDATE drugs SET related_code = CONCAT(related_code, ';IPPF2:1110010900000') WHERE related_code IS NOT NULL AND related_code LIKE '%IPPF:111120119%' AND related_code NOT LIKE '%IPPF2:%';
UPDATE codes SET related_code = CONCAT(related_code, ';IPPF2:1110010900000') WHERE related_code IS NOT NULL AND related_code LIKE '%IPPF:111132119%' AND related_code NOT LIKE '%IPPF2:%';
UPDATE drugs SET related_code = CONCAT(related_code, ';IPPF2:1110010900000') WHERE related_code IS NOT NULL AND related_code LIKE '%IPPF:111132119%' AND related_code NOT LIKE '%IPPF2:%';
UPDATE codes SET related_code = CONCAT(related_code, ';IPPF2:1110010900000') WHERE related_code IS NOT NULL AND related_code LIKE '%IPPF:111133119%' AND related_code NOT LIKE '%IPPF2:%';
UPDATE drugs SET related_code = CONCAT(related_code, ';IPPF2:1110010900000') WHERE related_code IS NOT NULL AND related_code LIKE '%IPPF:111133119%' AND related_code NOT LIKE '%IPPF2:%';
UPDATE codes SET related_code = CONCAT(related_code, ';IPPF2:1110010900000') WHERE related_code IS NOT NULL AND related_code LIKE '%IPPF:112141119%' AND related_code NOT LIKE '%IPPF2:%';
UPDATE drugs SET related_code = CONCAT(related_code, ';IPPF2:1110010900000') WHERE related_code IS NOT NULL AND related_code LIKE '%IPPF:112141119%' AND related_code NOT LIKE '%IPPF2:%';
UPDATE codes SET related_code = CONCAT(related_code, ';IPPF2:1110010900000') WHERE related_code IS NOT NULL AND related_code LIKE '%IPPF:112142119%' AND related_code NOT LIKE '%IPPF2:%';
UPDATE drugs SET related_code = CONCAT(related_code, ';IPPF2:1110010900000') WHERE related_code IS NOT NULL AND related_code LIKE '%IPPF:112142119%' AND related_code NOT LIKE '%IPPF2:%';
UPDATE codes SET related_code = CONCAT(related_code, ';IPPF2:1110010900000') WHERE related_code IS NOT NULL AND related_code LIKE '%IPPF:112150119%' AND related_code NOT LIKE '%IPPF2:%';
UPDATE drugs SET related_code = CONCAT(related_code, ';IPPF2:1110010900000') WHERE related_code IS NOT NULL AND related_code LIKE '%IPPF:112150119%' AND related_code NOT LIKE '%IPPF2:%';
UPDATE codes SET related_code = CONCAT(related_code, ';IPPF2:1110010900000') WHERE related_code IS NOT NULL AND related_code LIKE '%IPPF:112160119%' AND related_code NOT LIKE '%IPPF2:%';
UPDATE drugs SET related_code = CONCAT(related_code, ';IPPF2:1110010900000') WHERE related_code IS NOT NULL AND related_code LIKE '%IPPF:112160119%' AND related_code NOT LIKE '%IPPF2:%';
UPDATE codes SET related_code = CONCAT(related_code, ';IPPF2:1110010900000') WHERE related_code IS NOT NULL AND related_code LIKE '%IPPF:113170119%' AND related_code NOT LIKE '%IPPF2:%';
UPDATE drugs SET related_code = CONCAT(related_code, ';IPPF2:1110010900000') WHERE related_code IS NOT NULL AND related_code LIKE '%IPPF:113170119%' AND related_code NOT LIKE '%IPPF2:%';
UPDATE codes SET related_code = CONCAT(related_code, ';IPPF2:1110010900000') WHERE related_code IS NOT NULL AND related_code LIKE '%IPPF:131191119%' AND related_code NOT LIKE '%IPPF2:%';
UPDATE drugs SET related_code = CONCAT(related_code, ';IPPF2:1110010900000') WHERE related_code IS NOT NULL AND related_code LIKE '%IPPF:131191119%' AND related_code NOT LIKE '%IPPF2:%';
UPDATE codes SET related_code = CONCAT(related_code, ';IPPF2:1110010900000') WHERE related_code IS NOT NULL AND related_code LIKE '%IPPF:111130999%' AND related_code NOT LIKE '%IPPF2:%';
UPDATE drugs SET related_code = CONCAT(related_code, ';IPPF2:1110010900000') WHERE related_code IS NOT NULL AND related_code LIKE '%IPPF:111130999%' AND related_code NOT LIKE '%IPPF2:%';
UPDATE codes SET related_code = CONCAT(related_code, ';IPPF2:1121000000000') WHERE related_code IS NOT NULL AND related_code LIKE '%IPPF:111100000%' AND related_code NOT LIKE '%IPPF2:%';
UPDATE drugs SET related_code = CONCAT(related_code, ';IPPF2:1121000000000') WHERE related_code IS NOT NULL AND related_code LIKE '%IPPF:111100000%' AND related_code NOT LIKE '%IPPF2:%';
UPDATE codes SET related_code = CONCAT(related_code, ';IPPF2:1121120000000') WHERE related_code IS NOT NULL AND related_code LIKE '%IPPF:111100999%' AND related_code NOT LIKE '%IPPF2:%';
UPDATE drugs SET related_code = CONCAT(related_code, ';IPPF2:1121120000000') WHERE related_code IS NOT NULL AND related_code LIKE '%IPPF:111100999%' AND related_code NOT LIKE '%IPPF2:%';
UPDATE codes SET related_code = CONCAT(related_code, ';IPPF2:1121120000000') WHERE related_code IS NOT NULL AND related_code LIKE '%IPPF:111101110%' AND related_code NOT LIKE '%IPPF2:%';
UPDATE drugs SET related_code = CONCAT(related_code, ';IPPF2:1121120000000') WHERE related_code IS NOT NULL AND related_code LIKE '%IPPF:111101110%' AND related_code NOT LIKE '%IPPF2:%';
UPDATE codes SET related_code = CONCAT(related_code, ';IPPF2:1121120000000') WHERE related_code IS NOT NULL AND related_code LIKE '%IPPF:111101111%' AND related_code NOT LIKE '%IPPF2:%';
UPDATE drugs SET related_code = CONCAT(related_code, ';IPPF2:1121120000000') WHERE related_code IS NOT NULL AND related_code LIKE '%IPPF:111101111%' AND related_code NOT LIKE '%IPPF2:%';
UPDATE codes SET related_code = CONCAT(related_code, ';IPPF2:1121120000000') WHERE related_code IS NOT NULL AND related_code LIKE '%IPPF:111101999%' AND related_code NOT LIKE '%IPPF2:%';
UPDATE drugs SET related_code = CONCAT(related_code, ';IPPF2:1121120000000') WHERE related_code IS NOT NULL AND related_code LIKE '%IPPF:111101999%' AND related_code NOT LIKE '%IPPF2:%';
UPDATE codes SET related_code = CONCAT(related_code, ';IPPF2:1122000000000') WHERE related_code IS NOT NULL AND related_code LIKE '%IPPF:111110000%' AND related_code NOT LIKE '%IPPF2:%';
UPDATE drugs SET related_code = CONCAT(related_code, ';IPPF2:1122000000000') WHERE related_code IS NOT NULL AND related_code LIKE '%IPPF:111110000%' AND related_code NOT LIKE '%IPPF2:%';
UPDATE codes SET related_code = CONCAT(related_code, ';IPPF2:1122120000000') WHERE related_code IS NOT NULL AND related_code LIKE '%IPPF:111110999%' AND related_code NOT LIKE '%IPPF2:%';
UPDATE drugs SET related_code = CONCAT(related_code, ';IPPF2:1122120000000') WHERE related_code IS NOT NULL AND related_code LIKE '%IPPF:111110999%' AND related_code NOT LIKE '%IPPF2:%';
UPDATE codes SET related_code = CONCAT(related_code, ';IPPF2:1122120000000') WHERE related_code IS NOT NULL AND related_code LIKE '%IPPF:111111110%' AND related_code NOT LIKE '%IPPF2:%';
UPDATE drugs SET related_code = CONCAT(related_code, ';IPPF2:1122120000000') WHERE related_code IS NOT NULL AND related_code LIKE '%IPPF:111111110%' AND related_code NOT LIKE '%IPPF2:%';
UPDATE codes SET related_code = CONCAT(related_code, ';IPPF2:1122120000000') WHERE related_code IS NOT NULL AND related_code LIKE '%IPPF:111111111%' AND related_code NOT LIKE '%IPPF2:%';
UPDATE drugs SET related_code = CONCAT(related_code, ';IPPF2:1122120000000') WHERE related_code IS NOT NULL AND related_code LIKE '%IPPF:111111111%' AND related_code NOT LIKE '%IPPF2:%';
UPDATE codes SET related_code = CONCAT(related_code, ';IPPF2:1122120000000') WHERE related_code IS NOT NULL AND related_code LIKE '%IPPF:111111999%' AND related_code NOT LIKE '%IPPF2:%';
UPDATE drugs SET related_code = CONCAT(related_code, ';IPPF2:1122120000000') WHERE related_code IS NOT NULL AND related_code LIKE '%IPPF:111111999%' AND related_code NOT LIKE '%IPPF2:%';
UPDATE codes SET related_code = CONCAT(related_code, ';IPPF2:1122120000000') WHERE related_code IS NOT NULL AND related_code LIKE '%IPPF:111112110%' AND related_code NOT LIKE '%IPPF2:%';
UPDATE drugs SET related_code = CONCAT(related_code, ';IPPF2:1122120000000') WHERE related_code IS NOT NULL AND related_code LIKE '%IPPF:111112110%' AND related_code NOT LIKE '%IPPF2:%';
UPDATE codes SET related_code = CONCAT(related_code, ';IPPF2:1122120000000') WHERE related_code IS NOT NULL AND related_code LIKE '%IPPF:111112111%' AND related_code NOT LIKE '%IPPF2:%';
UPDATE drugs SET related_code = CONCAT(related_code, ';IPPF2:1122120000000') WHERE related_code IS NOT NULL AND related_code LIKE '%IPPF:111112111%' AND related_code NOT LIKE '%IPPF2:%';
UPDATE codes SET related_code = CONCAT(related_code, ';IPPF2:1122120000000') WHERE related_code IS NOT NULL AND related_code LIKE '%IPPF:111112999%' AND related_code NOT LIKE '%IPPF2:%';
UPDATE drugs SET related_code = CONCAT(related_code, ';IPPF2:1122120000000') WHERE related_code IS NOT NULL AND related_code LIKE '%IPPF:111112999%' AND related_code NOT LIKE '%IPPF2:%';
UPDATE codes SET related_code = CONCAT(related_code, ';IPPF2:1122120000000') WHERE related_code IS NOT NULL AND related_code LIKE '%IPPF:111113110%' AND related_code NOT LIKE '%IPPF2:%';
UPDATE drugs SET related_code = CONCAT(related_code, ';IPPF2:1122120000000') WHERE related_code IS NOT NULL AND related_code LIKE '%IPPF:111113110%' AND related_code NOT LIKE '%IPPF2:%';
UPDATE codes SET related_code = CONCAT(related_code, ';IPPF2:1122120000000') WHERE related_code IS NOT NULL AND related_code LIKE '%IPPF:111113111%' AND related_code NOT LIKE '%IPPF2:%';
UPDATE drugs SET related_code = CONCAT(related_code, ';IPPF2:1122120000000') WHERE related_code IS NOT NULL AND related_code LIKE '%IPPF:111113111%' AND related_code NOT LIKE '%IPPF2:%';
UPDATE codes SET related_code = CONCAT(related_code, ';IPPF2:1122120000000') WHERE related_code IS NOT NULL AND related_code LIKE '%IPPF:111113999%' AND related_code NOT LIKE '%IPPF2:%';
UPDATE drugs SET related_code = CONCAT(related_code, ';IPPF2:1122120000000') WHERE related_code IS NOT NULL AND related_code LIKE '%IPPF:111113999%' AND related_code NOT LIKE '%IPPF2:%';
UPDATE codes SET related_code = CONCAT(related_code, ';IPPF2:1123000000000') WHERE related_code IS NOT NULL AND related_code LIKE '%IPPF:111132000%' AND related_code NOT LIKE '%IPPF2:%';
UPDATE drugs SET related_code = CONCAT(related_code, ';IPPF2:1123000000000') WHERE related_code IS NOT NULL AND related_code LIKE '%IPPF:111132000%' AND related_code NOT LIKE '%IPPF2:%';
UPDATE codes SET related_code = CONCAT(related_code, ';IPPF2:1123020000000') WHERE related_code IS NOT NULL AND related_code LIKE '%IPPF:111132110%' AND related_code NOT LIKE '%IPPF2:%';
UPDATE drugs SET related_code = CONCAT(related_code, ';IPPF2:1123020000000') WHERE related_code IS NOT NULL AND related_code LIKE '%IPPF:111132110%' AND related_code NOT LIKE '%IPPF2:%';
UPDATE codes SET related_code = CONCAT(related_code, ';IPPF2:1123020000000') WHERE related_code IS NOT NULL AND related_code LIKE '%IPPF:111132111%' AND related_code NOT LIKE '%IPPF2:%';
UPDATE drugs SET related_code = CONCAT(related_code, ';IPPF2:1123020000000') WHERE related_code IS NOT NULL AND related_code LIKE '%IPPF:111132111%' AND related_code NOT LIKE '%IPPF2:%';
UPDATE codes SET related_code = CONCAT(related_code, ';IPPF2:1123020000000') WHERE related_code IS NOT NULL AND related_code LIKE '%IPPF:111132999%' AND related_code NOT LIKE '%IPPF2:%';
UPDATE drugs SET related_code = CONCAT(related_code, ';IPPF2:1123020000000') WHERE related_code IS NOT NULL AND related_code LIKE '%IPPF:111132999%' AND related_code NOT LIKE '%IPPF2:%';
UPDATE codes SET related_code = CONCAT(related_code, ';IPPF2:1123020000000') WHERE related_code IS NOT NULL AND related_code LIKE '%IPPF:111133110%' AND related_code NOT LIKE '%IPPF2:%';
UPDATE drugs SET related_code = CONCAT(related_code, ';IPPF2:1123020000000') WHERE related_code IS NOT NULL AND related_code LIKE '%IPPF:111133110%' AND related_code NOT LIKE '%IPPF2:%';
UPDATE codes SET related_code = CONCAT(related_code, ';IPPF2:1123020000000') WHERE related_code IS NOT NULL AND related_code LIKE '%IPPF:111133111%' AND related_code NOT LIKE '%IPPF2:%';
UPDATE drugs SET related_code = CONCAT(related_code, ';IPPF2:1123020000000') WHERE related_code IS NOT NULL AND related_code LIKE '%IPPF:111133111%' AND related_code NOT LIKE '%IPPF2:%';
UPDATE codes SET related_code = CONCAT(related_code, ';IPPF2:1123020000000') WHERE related_code IS NOT NULL AND related_code LIKE '%IPPF:111133999%' AND related_code NOT LIKE '%IPPF2:%';
UPDATE drugs SET related_code = CONCAT(related_code, ';IPPF2:1123020000000') WHERE related_code IS NOT NULL AND related_code LIKE '%IPPF:111133999%' AND related_code NOT LIKE '%IPPF2:%';
UPDATE codes SET related_code = CONCAT(related_code, ';IPPF2:1124000000000') WHERE related_code IS NOT NULL AND related_code LIKE '%IPPF:112140000%' AND related_code NOT LIKE '%IPPF2:%';
UPDATE drugs SET related_code = CONCAT(related_code, ';IPPF2:1124000000000') WHERE related_code IS NOT NULL AND related_code LIKE '%IPPF:112140000%' AND related_code NOT LIKE '%IPPF2:%';
UPDATE codes SET related_code = CONCAT(related_code, ';IPPF2:1124020000800') WHERE related_code IS NOT NULL AND related_code LIKE '%IPPF:112140999%' AND related_code NOT LIKE '%IPPF2:%';
UPDATE drugs SET related_code = CONCAT(related_code, ';IPPF2:1124020000800') WHERE related_code IS NOT NULL AND related_code LIKE '%IPPF:112140999%' AND related_code NOT LIKE '%IPPF2:%';
UPDATE codes SET related_code = CONCAT(related_code, ';IPPF2:1124100000000') WHERE related_code IS NOT NULL AND related_code LIKE '%IPPF:112141000%' AND related_code NOT LIKE '%IPPF2:%';
UPDATE drugs SET related_code = CONCAT(related_code, ';IPPF2:1124100000000') WHERE related_code IS NOT NULL AND related_code LIKE '%IPPF:112141000%' AND related_code NOT LIKE '%IPPF2:%';
UPDATE codes SET related_code = CONCAT(related_code, ';IPPF2:1124120000000') WHERE related_code IS NOT NULL AND related_code LIKE '%IPPF:112141110%' AND related_code NOT LIKE '%IPPF2:%';
UPDATE drugs SET related_code = CONCAT(related_code, ';IPPF2:1124120000000') WHERE related_code IS NOT NULL AND related_code LIKE '%IPPF:112141110%' AND related_code NOT LIKE '%IPPF2:%';
UPDATE codes SET related_code = CONCAT(related_code, ';IPPF2:1124120000000') WHERE related_code IS NOT NULL AND related_code LIKE '%IPPF:112141111%' AND related_code NOT LIKE '%IPPF2:%';
UPDATE drugs SET related_code = CONCAT(related_code, ';IPPF2:1124120000000') WHERE related_code IS NOT NULL AND related_code LIKE '%IPPF:112141111%' AND related_code NOT LIKE '%IPPF2:%';
UPDATE codes SET related_code = CONCAT(related_code, ';IPPF2:1124120000000') WHERE related_code IS NOT NULL AND related_code LIKE '%IPPF:112141999%' AND related_code NOT LIKE '%IPPF2:%';
UPDATE drugs SET related_code = CONCAT(related_code, ';IPPF2:1124120000000') WHERE related_code IS NOT NULL AND related_code LIKE '%IPPF:112141999%' AND related_code NOT LIKE '%IPPF2:%';
UPDATE codes SET related_code = CONCAT(related_code, ';IPPF2:1124200000000') WHERE related_code IS NOT NULL AND related_code LIKE '%IPPF:112142000%' AND related_code NOT LIKE '%IPPF2:%';
UPDATE drugs SET related_code = CONCAT(related_code, ';IPPF2:1124200000000') WHERE related_code IS NOT NULL AND related_code LIKE '%IPPF:112142000%' AND related_code NOT LIKE '%IPPF2:%';
UPDATE codes SET related_code = CONCAT(related_code, ';IPPF2:1124220000000') WHERE related_code IS NOT NULL AND related_code LIKE '%IPPF:112142110%' AND related_code NOT LIKE '%IPPF2:%';
UPDATE drugs SET related_code = CONCAT(related_code, ';IPPF2:1124220000000') WHERE related_code IS NOT NULL AND related_code LIKE '%IPPF:112142110%' AND related_code NOT LIKE '%IPPF2:%';
UPDATE codes SET related_code = CONCAT(related_code, ';IPPF2:1124220000000') WHERE related_code IS NOT NULL AND related_code LIKE '%IPPF:112142111%' AND related_code NOT LIKE '%IPPF2:%';
UPDATE drugs SET related_code = CONCAT(related_code, ';IPPF2:1124220000000') WHERE related_code IS NOT NULL AND related_code LIKE '%IPPF:112142111%' AND related_code NOT LIKE '%IPPF2:%';
UPDATE codes SET related_code = CONCAT(related_code, ';IPPF2:1124220000000') WHERE related_code IS NOT NULL AND related_code LIKE '%IPPF:112142999%' AND related_code NOT LIKE '%IPPF2:%';
UPDATE drugs SET related_code = CONCAT(related_code, ';IPPF2:1124220000000') WHERE related_code IS NOT NULL AND related_code LIKE '%IPPF:112142999%' AND related_code NOT LIKE '%IPPF2:%';
UPDATE codes SET related_code = CONCAT(related_code, ';IPPF2:1125000000000') WHERE related_code IS NOT NULL AND related_code LIKE '%IPPF:112150000%' AND related_code NOT LIKE '%IPPF2:%';
UPDATE drugs SET related_code = CONCAT(related_code, ';IPPF2:1125000000000') WHERE related_code IS NOT NULL AND related_code LIKE '%IPPF:112150000%' AND related_code NOT LIKE '%IPPF2:%';
UPDATE codes SET related_code = CONCAT(related_code, ';IPPF2:1125020000000') WHERE related_code IS NOT NULL AND related_code LIKE '%IPPF:112150999%' AND related_code NOT LIKE '%IPPF2:%';
UPDATE drugs SET related_code = CONCAT(related_code, ';IPPF2:1125020000000') WHERE related_code IS NOT NULL AND related_code LIKE '%IPPF:112150999%' AND related_code NOT LIKE '%IPPF2:%';
UPDATE codes SET related_code = CONCAT(related_code, ';IPPF2:1125020000000') WHERE related_code IS NOT NULL AND related_code LIKE '%IPPF:112151110%' AND related_code NOT LIKE '%IPPF2:%';
UPDATE drugs SET related_code = CONCAT(related_code, ';IPPF2:1125020000000') WHERE related_code IS NOT NULL AND related_code LIKE '%IPPF:112151110%' AND related_code NOT LIKE '%IPPF2:%';
UPDATE codes SET related_code = CONCAT(related_code, ';IPPF2:1125020000000') WHERE related_code IS NOT NULL AND related_code LIKE '%IPPF:112151111%' AND related_code NOT LIKE '%IPPF2:%';
UPDATE drugs SET related_code = CONCAT(related_code, ';IPPF2:1125020000000') WHERE related_code IS NOT NULL AND related_code LIKE '%IPPF:112151111%' AND related_code NOT LIKE '%IPPF2:%';
UPDATE codes SET related_code = CONCAT(related_code, ';IPPF2:1125020000000') WHERE related_code IS NOT NULL AND related_code LIKE '%IPPF:112151999%' AND related_code NOT LIKE '%IPPF2:%';
UPDATE drugs SET related_code = CONCAT(related_code, ';IPPF2:1125020000000') WHERE related_code IS NOT NULL AND related_code LIKE '%IPPF:112151999%' AND related_code NOT LIKE '%IPPF2:%';
UPDATE codes SET related_code = CONCAT(related_code, ';IPPF2:1125020000000') WHERE related_code IS NOT NULL AND related_code LIKE '%IPPF:112152010%' AND related_code NOT LIKE '%IPPF2:%';
UPDATE drugs SET related_code = CONCAT(related_code, ';IPPF2:1125020000000') WHERE related_code IS NOT NULL AND related_code LIKE '%IPPF:112152010%' AND related_code NOT LIKE '%IPPF2:%';
UPDATE codes SET related_code = CONCAT(related_code, ';IPPF2:1125020000000') WHERE related_code IS NOT NULL AND related_code LIKE '%IPPF:112152011%' AND related_code NOT LIKE '%IPPF2:%';
UPDATE drugs SET related_code = CONCAT(related_code, ';IPPF2:1125020000000') WHERE related_code IS NOT NULL AND related_code LIKE '%IPPF:112152011%' AND related_code NOT LIKE '%IPPF2:%';
UPDATE codes SET related_code = CONCAT(related_code, ';IPPF2:1125020000000') WHERE related_code IS NOT NULL AND related_code LIKE '%IPPF:112152999%' AND related_code NOT LIKE '%IPPF2:%';
UPDATE drugs SET related_code = CONCAT(related_code, ';IPPF2:1125020000000') WHERE related_code IS NOT NULL AND related_code LIKE '%IPPF:112152999%' AND related_code NOT LIKE '%IPPF2:%';
UPDATE codes SET related_code = CONCAT(related_code, ';IPPF2:1126000000000') WHERE related_code IS NOT NULL AND related_code LIKE '%IPPF:112160000%' AND related_code NOT LIKE '%IPPF2:%';
UPDATE drugs SET related_code = CONCAT(related_code, ';IPPF2:1126000000000') WHERE related_code IS NOT NULL AND related_code LIKE '%IPPF:112160000%' AND related_code NOT LIKE '%IPPF2:%';
UPDATE codes SET related_code = CONCAT(related_code, ';IPPF2:1126020000000') WHERE related_code IS NOT NULL AND related_code LIKE '%IPPF:112160999%' AND related_code NOT LIKE '%IPPF2:%';
UPDATE drugs SET related_code = CONCAT(related_code, ';IPPF2:1126020000000') WHERE related_code IS NOT NULL AND related_code LIKE '%IPPF:112160999%' AND related_code NOT LIKE '%IPPF2:%';
UPDATE codes SET related_code = CONCAT(related_code, ';IPPF2:1126020000000') WHERE related_code IS NOT NULL AND related_code LIKE '%IPPF:112161110%' AND related_code NOT LIKE '%IPPF2:%';
UPDATE drugs SET related_code = CONCAT(related_code, ';IPPF2:1126020000000') WHERE related_code IS NOT NULL AND related_code LIKE '%IPPF:112161110%' AND related_code NOT LIKE '%IPPF2:%';
UPDATE codes SET related_code = CONCAT(related_code, ';IPPF2:1126020000000') WHERE related_code IS NOT NULL AND related_code LIKE '%IPPF:112161111%' AND related_code NOT LIKE '%IPPF2:%';
UPDATE drugs SET related_code = CONCAT(related_code, ';IPPF2:1126020000000') WHERE related_code IS NOT NULL AND related_code LIKE '%IPPF:112161111%' AND related_code NOT LIKE '%IPPF2:%';
UPDATE codes SET related_code = CONCAT(related_code, ';IPPF2:1126020000000') WHERE related_code IS NOT NULL AND related_code LIKE '%IPPF:112161999%' AND related_code NOT LIKE '%IPPF2:%';
UPDATE drugs SET related_code = CONCAT(related_code, ';IPPF2:1126020000000') WHERE related_code IS NOT NULL AND related_code LIKE '%IPPF:112161999%' AND related_code NOT LIKE '%IPPF2:%';
UPDATE codes SET related_code = CONCAT(related_code, ';IPPF2:1126020000000') WHERE related_code IS NOT NULL AND related_code LIKE '%IPPF:112162110%' AND related_code NOT LIKE '%IPPF2:%';
UPDATE drugs SET related_code = CONCAT(related_code, ';IPPF2:1126020000000') WHERE related_code IS NOT NULL AND related_code LIKE '%IPPF:112162110%' AND related_code NOT LIKE '%IPPF2:%';
UPDATE codes SET related_code = CONCAT(related_code, ';IPPF2:1126020000000') WHERE related_code IS NOT NULL AND related_code LIKE '%IPPF:112162111%' AND related_code NOT LIKE '%IPPF2:%';
UPDATE drugs SET related_code = CONCAT(related_code, ';IPPF2:1126020000000') WHERE related_code IS NOT NULL AND related_code LIKE '%IPPF:112162111%' AND related_code NOT LIKE '%IPPF2:%';
UPDATE codes SET related_code = CONCAT(related_code, ';IPPF2:1126020000000') WHERE related_code IS NOT NULL AND related_code LIKE '%IPPF:112162999%' AND related_code NOT LIKE '%IPPF2:%';
UPDATE drugs SET related_code = CONCAT(related_code, ';IPPF2:1126020000000') WHERE related_code IS NOT NULL AND related_code LIKE '%IPPF:112162999%' AND related_code NOT LIKE '%IPPF2:%';
UPDATE codes SET related_code = CONCAT(related_code, ';IPPF2:1126020000000') WHERE related_code IS NOT NULL AND related_code LIKE '%IPPF:112163110%' AND related_code NOT LIKE '%IPPF2:%';
UPDATE drugs SET related_code = CONCAT(related_code, ';IPPF2:1126020000000') WHERE related_code IS NOT NULL AND related_code LIKE '%IPPF:112163110%' AND related_code NOT LIKE '%IPPF2:%';
UPDATE codes SET related_code = CONCAT(related_code, ';IPPF2:1126020000000') WHERE related_code IS NOT NULL AND related_code LIKE '%IPPF:112163111%' AND related_code NOT LIKE '%IPPF2:%';
UPDATE drugs SET related_code = CONCAT(related_code, ';IPPF2:1126020000000') WHERE related_code IS NOT NULL AND related_code LIKE '%IPPF:112163111%' AND related_code NOT LIKE '%IPPF2:%';
UPDATE codes SET related_code = CONCAT(related_code, ';IPPF2:1126020000000') WHERE related_code IS NOT NULL AND related_code LIKE '%IPPF:112163999%' AND related_code NOT LIKE '%IPPF2:%';
UPDATE drugs SET related_code = CONCAT(related_code, ';IPPF2:1126020000000') WHERE related_code IS NOT NULL AND related_code LIKE '%IPPF:112163999%' AND related_code NOT LIKE '%IPPF2:%';
UPDATE codes SET related_code = CONCAT(related_code, ';IPPF2:1126020000000') WHERE related_code IS NOT NULL AND related_code LIKE '%IPPF:112164110%' AND related_code NOT LIKE '%IPPF2:%';
UPDATE drugs SET related_code = CONCAT(related_code, ';IPPF2:1126020000000') WHERE related_code IS NOT NULL AND related_code LIKE '%IPPF:112164110%' AND related_code NOT LIKE '%IPPF2:%';
UPDATE codes SET related_code = CONCAT(related_code, ';IPPF2:1126020000000') WHERE related_code IS NOT NULL AND related_code LIKE '%IPPF:112164111%' AND related_code NOT LIKE '%IPPF2:%';
UPDATE drugs SET related_code = CONCAT(related_code, ';IPPF2:1126020000000') WHERE related_code IS NOT NULL AND related_code LIKE '%IPPF:112164111%' AND related_code NOT LIKE '%IPPF2:%';
UPDATE codes SET related_code = CONCAT(related_code, ';IPPF2:1126020000000') WHERE related_code IS NOT NULL AND related_code LIKE '%IPPF:112164999%' AND related_code NOT LIKE '%IPPF2:%';
UPDATE drugs SET related_code = CONCAT(related_code, ';IPPF2:1126020000000') WHERE related_code IS NOT NULL AND related_code LIKE '%IPPF:112164999%' AND related_code NOT LIKE '%IPPF2:%';
UPDATE codes SET related_code = CONCAT(related_code, ';IPPF2:1126020000000') WHERE related_code IS NOT NULL AND related_code LIKE '%IPPF:112165110%' AND related_code NOT LIKE '%IPPF2:%';
UPDATE drugs SET related_code = CONCAT(related_code, ';IPPF2:1126020000000') WHERE related_code IS NOT NULL AND related_code LIKE '%IPPF:112165110%' AND related_code NOT LIKE '%IPPF2:%';
UPDATE codes SET related_code = CONCAT(related_code, ';IPPF2:1126020000000') WHERE related_code IS NOT NULL AND related_code LIKE '%IPPF:112165111%' AND related_code NOT LIKE '%IPPF2:%';
UPDATE drugs SET related_code = CONCAT(related_code, ';IPPF2:1126020000000') WHERE related_code IS NOT NULL AND related_code LIKE '%IPPF:112165111%' AND related_code NOT LIKE '%IPPF2:%';
UPDATE codes SET related_code = CONCAT(related_code, ';IPPF2:1126020000000') WHERE related_code IS NOT NULL AND related_code LIKE '%IPPF:112165999%' AND related_code NOT LIKE '%IPPF2:%';
UPDATE drugs SET related_code = CONCAT(related_code, ';IPPF2:1126020000000') WHERE related_code IS NOT NULL AND related_code LIKE '%IPPF:112165999%' AND related_code NOT LIKE '%IPPF2:%';
UPDATE codes SET related_code = CONCAT(related_code, ';IPPF2:1131000000000') WHERE related_code IS NOT NULL AND related_code LIKE '%IPPF:111120000%' AND related_code NOT LIKE '%IPPF2:%';
UPDATE drugs SET related_code = CONCAT(related_code, ';IPPF2:1131000000000') WHERE related_code IS NOT NULL AND related_code LIKE '%IPPF:111120000%' AND related_code NOT LIKE '%IPPF2:%';
UPDATE codes SET related_code = CONCAT(related_code, ';IPPF2:1131020211000') WHERE related_code IS NOT NULL AND related_code LIKE '%IPPF:111120112%' AND related_code NOT LIKE '%IPPF2:%';
UPDATE drugs SET related_code = CONCAT(related_code, ';IPPF2:1131020211000') WHERE related_code IS NOT NULL AND related_code LIKE '%IPPF:111120112%' AND related_code NOT LIKE '%IPPF2:%';
UPDATE codes SET related_code = CONCAT(related_code, ';IPPF2:1131120000000') WHERE related_code IS NOT NULL AND related_code LIKE '%IPPF:111120999%' AND related_code NOT LIKE '%IPPF2:%';
UPDATE drugs SET related_code = CONCAT(related_code, ';IPPF2:1131120000000') WHERE related_code IS NOT NULL AND related_code LIKE '%IPPF:111120999%' AND related_code NOT LIKE '%IPPF2:%';
UPDATE codes SET related_code = CONCAT(related_code, ';IPPF2:1131120000000') WHERE related_code IS NOT NULL AND related_code LIKE '%IPPF:111122110%' AND related_code NOT LIKE '%IPPF2:%';
UPDATE drugs SET related_code = CONCAT(related_code, ';IPPF2:1131120000000') WHERE related_code IS NOT NULL AND related_code LIKE '%IPPF:111122110%' AND related_code NOT LIKE '%IPPF2:%';
UPDATE codes SET related_code = CONCAT(related_code, ';IPPF2:1131120000000') WHERE related_code IS NOT NULL AND related_code LIKE '%IPPF:111122111%' AND related_code NOT LIKE '%IPPF2:%';
UPDATE drugs SET related_code = CONCAT(related_code, ';IPPF2:1131120000000') WHERE related_code IS NOT NULL AND related_code LIKE '%IPPF:111122111%' AND related_code NOT LIKE '%IPPF2:%';
UPDATE codes SET related_code = CONCAT(related_code, ';IPPF2:1131120000000') WHERE related_code IS NOT NULL AND related_code LIKE '%IPPF:111122999%' AND related_code NOT LIKE '%IPPF2:%';
UPDATE drugs SET related_code = CONCAT(related_code, ';IPPF2:1131120000000') WHERE related_code IS NOT NULL AND related_code LIKE '%IPPF:111122999%' AND related_code NOT LIKE '%IPPF2:%';
UPDATE codes SET related_code = CONCAT(related_code, ';IPPF2:1131120000000') WHERE related_code IS NOT NULL AND related_code LIKE '%IPPF:111123110%' AND related_code NOT LIKE '%IPPF2:%';
UPDATE drugs SET related_code = CONCAT(related_code, ';IPPF2:1131120000000') WHERE related_code IS NOT NULL AND related_code LIKE '%IPPF:111123110%' AND related_code NOT LIKE '%IPPF2:%';
UPDATE codes SET related_code = CONCAT(related_code, ';IPPF2:1131120000000') WHERE related_code IS NOT NULL AND related_code LIKE '%IPPF:111123111%' AND related_code NOT LIKE '%IPPF2:%';
UPDATE drugs SET related_code = CONCAT(related_code, ';IPPF2:1131120000000') WHERE related_code IS NOT NULL AND related_code LIKE '%IPPF:111123111%' AND related_code NOT LIKE '%IPPF2:%';
UPDATE codes SET related_code = CONCAT(related_code, ';IPPF2:1131120000000') WHERE related_code IS NOT NULL AND related_code LIKE '%IPPF:111123999%' AND related_code NOT LIKE '%IPPF2:%';
UPDATE drugs SET related_code = CONCAT(related_code, ';IPPF2:1131120000000') WHERE related_code IS NOT NULL AND related_code LIKE '%IPPF:111123999%' AND related_code NOT LIKE '%IPPF2:%';
UPDATE codes SET related_code = CONCAT(related_code, ';IPPF2:1131120000000') WHERE related_code IS NOT NULL AND related_code LIKE '%IPPF:111124110%' AND related_code NOT LIKE '%IPPF2:%';
UPDATE drugs SET related_code = CONCAT(related_code, ';IPPF2:1131120000000') WHERE related_code IS NOT NULL AND related_code LIKE '%IPPF:111124110%' AND related_code NOT LIKE '%IPPF2:%';
UPDATE codes SET related_code = CONCAT(related_code, ';IPPF2:1131120000000') WHERE related_code IS NOT NULL AND related_code LIKE '%IPPF:111124111%' AND related_code NOT LIKE '%IPPF2:%';
UPDATE drugs SET related_code = CONCAT(related_code, ';IPPF2:1131120000000') WHERE related_code IS NOT NULL AND related_code LIKE '%IPPF:111124111%' AND related_code NOT LIKE '%IPPF2:%';
UPDATE codes SET related_code = CONCAT(related_code, ';IPPF2:1131120000000') WHERE related_code IS NOT NULL AND related_code LIKE '%IPPF:111124999%' AND related_code NOT LIKE '%IPPF2:%';
UPDATE drugs SET related_code = CONCAT(related_code, ';IPPF2:1131120000000') WHERE related_code IS NOT NULL AND related_code LIKE '%IPPF:111124999%' AND related_code NOT LIKE '%IPPF2:%';
UPDATE codes SET related_code = CONCAT(related_code, ';IPPF2:1132000000000') WHERE related_code IS NOT NULL AND related_code LIKE '%IPPF:113170000%' AND related_code NOT LIKE '%IPPF2:%';
UPDATE drugs SET related_code = CONCAT(related_code, ';IPPF2:1132000000000') WHERE related_code IS NOT NULL AND related_code LIKE '%IPPF:113170000%' AND related_code NOT LIKE '%IPPF2:%';
UPDATE codes SET related_code = CONCAT(related_code, ';IPPF2:1132020211000') WHERE related_code IS NOT NULL AND related_code LIKE '%IPPF:113170112%' AND related_code NOT LIKE '%IPPF2:%';
UPDATE drugs SET related_code = CONCAT(related_code, ';IPPF2:1132020211000') WHERE related_code IS NOT NULL AND related_code LIKE '%IPPF:113170112%' AND related_code NOT LIKE '%IPPF2:%';
UPDATE codes SET related_code = CONCAT(related_code, ';IPPF2:1132120000000') WHERE related_code IS NOT NULL AND related_code LIKE '%IPPF:113170999%' AND related_code NOT LIKE '%IPPF2:%';
UPDATE drugs SET related_code = CONCAT(related_code, ';IPPF2:1132120000000') WHERE related_code IS NOT NULL AND related_code LIKE '%IPPF:113170999%' AND related_code NOT LIKE '%IPPF2:%';
UPDATE codes SET related_code = CONCAT(related_code, ';IPPF2:1132120000000') WHERE related_code IS NOT NULL AND related_code LIKE '%IPPF:113171110%' AND related_code NOT LIKE '%IPPF2:%';
UPDATE drugs SET related_code = CONCAT(related_code, ';IPPF2:1132120000000') WHERE related_code IS NOT NULL AND related_code LIKE '%IPPF:113171110%' AND related_code NOT LIKE '%IPPF2:%';
UPDATE codes SET related_code = CONCAT(related_code, ';IPPF2:1132120000000') WHERE related_code IS NOT NULL AND related_code LIKE '%IPPF:113171111%' AND related_code NOT LIKE '%IPPF2:%';
UPDATE drugs SET related_code = CONCAT(related_code, ';IPPF2:1132120000000') WHERE related_code IS NOT NULL AND related_code LIKE '%IPPF:113171111%' AND related_code NOT LIKE '%IPPF2:%';
UPDATE codes SET related_code = CONCAT(related_code, ';IPPF2:1132120000000') WHERE related_code IS NOT NULL AND related_code LIKE '%IPPF:113171999%' AND related_code NOT LIKE '%IPPF2:%';
UPDATE drugs SET related_code = CONCAT(related_code, ';IPPF2:1132120000000') WHERE related_code IS NOT NULL AND related_code LIKE '%IPPF:113171999%' AND related_code NOT LIKE '%IPPF2:%';
UPDATE codes SET related_code = CONCAT(related_code, ';IPPF2:1132120000000') WHERE related_code IS NOT NULL AND related_code LIKE '%IPPF:113172110%' AND related_code NOT LIKE '%IPPF2:%';
UPDATE drugs SET related_code = CONCAT(related_code, ';IPPF2:1132120000000') WHERE related_code IS NOT NULL AND related_code LIKE '%IPPF:113172110%' AND related_code NOT LIKE '%IPPF2:%';
UPDATE codes SET related_code = CONCAT(related_code, ';IPPF2:1132120000000') WHERE related_code IS NOT NULL AND related_code LIKE '%IPPF:113172111%' AND related_code NOT LIKE '%IPPF2:%';
UPDATE drugs SET related_code = CONCAT(related_code, ';IPPF2:1132120000000') WHERE related_code IS NOT NULL AND related_code LIKE '%IPPF:113172111%' AND related_code NOT LIKE '%IPPF2:%';
UPDATE codes SET related_code = CONCAT(related_code, ';IPPF2:1132120000000') WHERE related_code IS NOT NULL AND related_code LIKE '%IPPF:113172999%' AND related_code NOT LIKE '%IPPF2:%';
UPDATE drugs SET related_code = CONCAT(related_code, ';IPPF2:1132120000000') WHERE related_code IS NOT NULL AND related_code LIKE '%IPPF:113172999%' AND related_code NOT LIKE '%IPPF2:%';
UPDATE codes SET related_code = CONCAT(related_code, ';IPPF2:1141000000000') WHERE related_code IS NOT NULL AND related_code LIKE '%IPPF:120180000%' AND related_code NOT LIKE '%IPPF2:%';
UPDATE drugs SET related_code = CONCAT(related_code, ';IPPF2:1141000000000') WHERE related_code IS NOT NULL AND related_code LIKE '%IPPF:120180000%' AND related_code NOT LIKE '%IPPF2:%';
UPDATE codes SET related_code = CONCAT(related_code, ';IPPF2:1141080000000') WHERE related_code IS NOT NULL AND related_code LIKE '%IPPF:120180999%' AND related_code NOT LIKE '%IPPF2:%';
UPDATE drugs SET related_code = CONCAT(related_code, ';IPPF2:1141080000000') WHERE related_code IS NOT NULL AND related_code LIKE '%IPPF:120180999%' AND related_code NOT LIKE '%IPPF2:%';
UPDATE codes SET related_code = CONCAT(related_code, ';IPPF2:1141100000000') WHERE related_code IS NOT NULL AND related_code LIKE '%IPPF:121181000%' AND related_code NOT LIKE '%IPPF2:%';
UPDATE drugs SET related_code = CONCAT(related_code, ';IPPF2:1141100000000') WHERE related_code IS NOT NULL AND related_code LIKE '%IPPF:121181000%' AND related_code NOT LIKE '%IPPF2:%';
UPDATE codes SET related_code = CONCAT(related_code, ';IPPF2:1141110000000') WHERE related_code IS NOT NULL AND related_code LIKE '%IPPF:121181119%' AND related_code NOT LIKE '%IPPF2:%';
UPDATE drugs SET related_code = CONCAT(related_code, ';IPPF2:1141110000000') WHERE related_code IS NOT NULL AND related_code LIKE '%IPPF:121181119%' AND related_code NOT LIKE '%IPPF2:%';
UPDATE codes SET related_code = CONCAT(related_code, ';IPPF2:1141130302107') WHERE related_code IS NOT NULL AND related_code LIKE '%IPPF:121181211%' AND related_code NOT LIKE '%IPPF2:%';
UPDATE drugs SET related_code = CONCAT(related_code, ';IPPF2:1141130302107') WHERE related_code IS NOT NULL AND related_code LIKE '%IPPF:121181211%' AND related_code NOT LIKE '%IPPF2:%';
UPDATE codes SET related_code = CONCAT(related_code, ';IPPF2:1141130302107') WHERE related_code IS NOT NULL AND related_code LIKE '%IPPF:121181311%' AND related_code NOT LIKE '%IPPF2:%';
UPDATE drugs SET related_code = CONCAT(related_code, ';IPPF2:1141130302107') WHERE related_code IS NOT NULL AND related_code LIKE '%IPPF:121181311%' AND related_code NOT LIKE '%IPPF2:%';
UPDATE codes SET related_code = CONCAT(related_code, ';IPPF2:1141130302107') WHERE related_code IS NOT NULL AND related_code LIKE '%IPPF:121181411%' AND related_code NOT LIKE '%IPPF2:%';
UPDATE drugs SET related_code = CONCAT(related_code, ';IPPF2:1141130302107') WHERE related_code IS NOT NULL AND related_code LIKE '%IPPF:121181411%' AND related_code NOT LIKE '%IPPF2:%';
UPDATE codes SET related_code = CONCAT(related_code, ';IPPF2:1141130302101') WHERE related_code IS NOT NULL AND related_code LIKE '%IPPF:121181112%' AND related_code NOT LIKE '%IPPF2:%';
UPDATE drugs SET related_code = CONCAT(related_code, ';IPPF2:1141130302101') WHERE related_code IS NOT NULL AND related_code LIKE '%IPPF:121181112%' AND related_code NOT LIKE '%IPPF2:%';
UPDATE codes SET related_code = CONCAT(related_code, ';IPPF2:1141130302102') WHERE related_code IS NOT NULL AND related_code LIKE '%IPPF:121181213%' AND related_code NOT LIKE '%IPPF2:%';
UPDATE drugs SET related_code = CONCAT(related_code, ';IPPF2:1141130302102') WHERE related_code IS NOT NULL AND related_code LIKE '%IPPF:121181213%' AND related_code NOT LIKE '%IPPF2:%';
UPDATE codes SET related_code = CONCAT(related_code, ';IPPF2:1141130302103') WHERE related_code IS NOT NULL AND related_code LIKE '%IPPF:121181413%' AND related_code NOT LIKE '%IPPF2:%';
UPDATE drugs SET related_code = CONCAT(related_code, ';IPPF2:1141130302103') WHERE related_code IS NOT NULL AND related_code LIKE '%IPPF:121181413%' AND related_code NOT LIKE '%IPPF2:%';
UPDATE codes SET related_code = CONCAT(related_code, ';IPPF2:1141130302104') WHERE related_code IS NOT NULL AND related_code LIKE '%IPPF:121181313%' AND related_code NOT LIKE '%IPPF2:%';
UPDATE drugs SET related_code = CONCAT(related_code, ';IPPF2:1141130302104') WHERE related_code IS NOT NULL AND related_code LIKE '%IPPF:121181313%' AND related_code NOT LIKE '%IPPF2:%';
UPDATE codes SET related_code = CONCAT(related_code, ';IPPF2:1141130000800') WHERE related_code IS NOT NULL AND related_code LIKE '%IPPF:121181999%' AND related_code NOT LIKE '%IPPF2:%';
UPDATE drugs SET related_code = CONCAT(related_code, ';IPPF2:1141130000800') WHERE related_code IS NOT NULL AND related_code LIKE '%IPPF:121181999%' AND related_code NOT LIKE '%IPPF2:%';
UPDATE codes SET related_code = CONCAT(related_code, ';IPPF2:1142000000000') WHERE related_code IS NOT NULL AND related_code LIKE '%IPPF:122182000%' AND related_code NOT LIKE '%IPPF2:%';
UPDATE drugs SET related_code = CONCAT(related_code, ';IPPF2:1142000000000') WHERE related_code IS NOT NULL AND related_code LIKE '%IPPF:122182000%' AND related_code NOT LIKE '%IPPF2:%';
UPDATE codes SET related_code = CONCAT(related_code, ';IPPF2:1142010000000') WHERE related_code IS NOT NULL AND related_code LIKE '%IPPF:122182119%' AND related_code NOT LIKE '%IPPF2:%';
UPDATE drugs SET related_code = CONCAT(related_code, ';IPPF2:1142010000000') WHERE related_code IS NOT NULL AND related_code LIKE '%IPPF:122182119%' AND related_code NOT LIKE '%IPPF2:%';
UPDATE codes SET related_code = CONCAT(related_code, ';IPPF2:1142030302203') WHERE related_code IS NOT NULL AND related_code LIKE '%IPPF:122182211%' AND related_code NOT LIKE '%IPPF2:%';
UPDATE drugs SET related_code = CONCAT(related_code, ';IPPF2:1142030302203') WHERE related_code IS NOT NULL AND related_code LIKE '%IPPF:122182211%' AND related_code NOT LIKE '%IPPF2:%';
UPDATE codes SET related_code = CONCAT(related_code, ';IPPF2:1142030302203') WHERE related_code IS NOT NULL AND related_code LIKE '%IPPF:122182311%' AND related_code NOT LIKE '%IPPF2:%';
UPDATE drugs SET related_code = CONCAT(related_code, ';IPPF2:1142030302203') WHERE related_code IS NOT NULL AND related_code LIKE '%IPPF:122182311%' AND related_code NOT LIKE '%IPPF2:%';
UPDATE codes SET related_code = CONCAT(related_code, ';IPPF2:1142030302101') WHERE related_code IS NOT NULL AND related_code LIKE '%IPPF:122182112%' AND related_code NOT LIKE '%IPPF2:%';
UPDATE drugs SET related_code = CONCAT(related_code, ';IPPF2:1142030302101') WHERE related_code IS NOT NULL AND related_code LIKE '%IPPF:122182112%' AND related_code NOT LIKE '%IPPF2:%';
UPDATE codes SET related_code = CONCAT(related_code, ';IPPF2:1142030302201') WHERE related_code IS NOT NULL AND related_code LIKE '%IPPF:122182213%' AND related_code NOT LIKE '%IPPF2:%';
UPDATE drugs SET related_code = CONCAT(related_code, ';IPPF2:1142030302201') WHERE related_code IS NOT NULL AND related_code LIKE '%IPPF:122182213%' AND related_code NOT LIKE '%IPPF2:%';
UPDATE codes SET related_code = CONCAT(related_code, ';IPPF2:1142030302202') WHERE related_code IS NOT NULL AND related_code LIKE '%IPPF:122182313%' AND related_code NOT LIKE '%IPPF2:%';
UPDATE drugs SET related_code = CONCAT(related_code, ';IPPF2:1142030302202') WHERE related_code IS NOT NULL AND related_code LIKE '%IPPF:122182313%' AND related_code NOT LIKE '%IPPF2:%';
UPDATE codes SET related_code = CONCAT(related_code, ';IPPF2:1142030000800') WHERE related_code IS NOT NULL AND related_code LIKE '%IPPF:122182999%' AND related_code NOT LIKE '%IPPF2:%';
UPDATE drugs SET related_code = CONCAT(related_code, ';IPPF2:1142030000800') WHERE related_code IS NOT NULL AND related_code LIKE '%IPPF:122182999%' AND related_code NOT LIKE '%IPPF2:%';
UPDATE codes SET related_code = CONCAT(related_code, ';IPPF2:1151000000000') WHERE related_code IS NOT NULL AND related_code LIKE '%IPPF:145210000%' AND related_code NOT LIKE '%IPPF2:%';
UPDATE drugs SET related_code = CONCAT(related_code, ';IPPF2:1151000000000') WHERE related_code IS NOT NULL AND related_code LIKE '%IPPF:145210000%' AND related_code NOT LIKE '%IPPF2:%';
UPDATE codes SET related_code = CONCAT(related_code, ';IPPF2:1151010000000') WHERE related_code IS NOT NULL AND related_code LIKE '%IPPF:145211119%' AND related_code NOT LIKE '%IPPF2:%';
UPDATE drugs SET related_code = CONCAT(related_code, ';IPPF2:1151010000000') WHERE related_code IS NOT NULL AND related_code LIKE '%IPPF:145211119%' AND related_code NOT LIKE '%IPPF2:%';
UPDATE codes SET related_code = CONCAT(related_code, ';IPPF2:1151010000000') WHERE related_code IS NOT NULL AND related_code LIKE '%IPPF:145211999%' AND related_code NOT LIKE '%IPPF2:%';
UPDATE drugs SET related_code = CONCAT(related_code, ';IPPF2:1151010000000') WHERE related_code IS NOT NULL AND related_code LIKE '%IPPF:145211999%' AND related_code NOT LIKE '%IPPF2:%';
UPDATE codes SET related_code = CONCAT(related_code, ';IPPF2:1151020000000') WHERE related_code IS NOT NULL AND related_code LIKE '%IPPF:145212110%' AND related_code NOT LIKE '%IPPF2:%';
UPDATE drugs SET related_code = CONCAT(related_code, ';IPPF2:1151020000000') WHERE related_code IS NOT NULL AND related_code LIKE '%IPPF:145212110%' AND related_code NOT LIKE '%IPPF2:%';
UPDATE codes SET related_code = CONCAT(related_code, ';IPPF2:1151020000000') WHERE related_code IS NOT NULL AND related_code LIKE '%IPPF:145212111%' AND related_code NOT LIKE '%IPPF2:%';
UPDATE drugs SET related_code = CONCAT(related_code, ';IPPF2:1151020000000') WHERE related_code IS NOT NULL AND related_code LIKE '%IPPF:145212111%' AND related_code NOT LIKE '%IPPF2:%';
UPDATE codes SET related_code = CONCAT(related_code, ';IPPF2:1151020000000') WHERE related_code IS NOT NULL AND related_code LIKE '%IPPF:145212210%' AND related_code NOT LIKE '%IPPF2:%';
UPDATE drugs SET related_code = CONCAT(related_code, ';IPPF2:1151020000000') WHERE related_code IS NOT NULL AND related_code LIKE '%IPPF:145212210%' AND related_code NOT LIKE '%IPPF2:%';
UPDATE codes SET related_code = CONCAT(related_code, ';IPPF2:1151020000000') WHERE related_code IS NOT NULL AND related_code LIKE '%IPPF:145212211%' AND related_code NOT LIKE '%IPPF2:%';
UPDATE drugs SET related_code = CONCAT(related_code, ';IPPF2:1151020000000') WHERE related_code IS NOT NULL AND related_code LIKE '%IPPF:145212211%' AND related_code NOT LIKE '%IPPF2:%';
UPDATE codes SET related_code = CONCAT(related_code, ';IPPF2:1151020000000') WHERE related_code IS NOT NULL AND related_code LIKE '%IPPF:145212310%' AND related_code NOT LIKE '%IPPF2:%';
UPDATE drugs SET related_code = CONCAT(related_code, ';IPPF2:1151020000000') WHERE related_code IS NOT NULL AND related_code LIKE '%IPPF:145212310%' AND related_code NOT LIKE '%IPPF2:%';
UPDATE codes SET related_code = CONCAT(related_code, ';IPPF2:1151020000000') WHERE related_code IS NOT NULL AND related_code LIKE '%IPPF:145212311%' AND related_code NOT LIKE '%IPPF2:%';
UPDATE drugs SET related_code = CONCAT(related_code, ';IPPF2:1151020000000') WHERE related_code IS NOT NULL AND related_code LIKE '%IPPF:145212311%' AND related_code NOT LIKE '%IPPF2:%';
UPDATE codes SET related_code = CONCAT(related_code, ';IPPF2:1151020000000') WHERE related_code IS NOT NULL AND related_code LIKE '%IPPF:145212410%' AND related_code NOT LIKE '%IPPF2:%';
UPDATE drugs SET related_code = CONCAT(related_code, ';IPPF2:1151020000000') WHERE related_code IS NOT NULL AND related_code LIKE '%IPPF:145212410%' AND related_code NOT LIKE '%IPPF2:%';
UPDATE codes SET related_code = CONCAT(related_code, ';IPPF2:1151020000000') WHERE related_code IS NOT NULL AND related_code LIKE '%IPPF:145212411%' AND related_code NOT LIKE '%IPPF2:%';
UPDATE drugs SET related_code = CONCAT(related_code, ';IPPF2:1151020000000') WHERE related_code IS NOT NULL AND related_code LIKE '%IPPF:145212411%' AND related_code NOT LIKE '%IPPF2:%';
UPDATE codes SET related_code = CONCAT(related_code, ';IPPF2:1151020000000') WHERE related_code IS NOT NULL AND related_code LIKE '%IPPF:145212999%' AND related_code NOT LIKE '%IPPF2:%';
UPDATE drugs SET related_code = CONCAT(related_code, ';IPPF2:1151020000000') WHERE related_code IS NOT NULL AND related_code LIKE '%IPPF:145212999%' AND related_code NOT LIKE '%IPPF2:%';
UPDATE codes SET related_code = CONCAT(related_code, ';IPPF2:1151020000000') WHERE related_code IS NOT NULL AND related_code LIKE '%IPPF:145210999%' AND related_code NOT LIKE '%IPPF2:%';
UPDATE drugs SET related_code = CONCAT(related_code, ';IPPF2:1151020000000') WHERE related_code IS NOT NULL AND related_code LIKE '%IPPF:145210999%' AND related_code NOT LIKE '%IPPF2:%';
UPDATE codes SET related_code = CONCAT(related_code, ';IPPF2:1210000000000') WHERE related_code IS NOT NULL AND related_code LIKE '%IPPF:130190000%' AND related_code NOT LIKE '%IPPF2:%';
UPDATE drugs SET related_code = CONCAT(related_code, ';IPPF2:1210000000000') WHERE related_code IS NOT NULL AND related_code LIKE '%IPPF:130190000%' AND related_code NOT LIKE '%IPPF2:%';
UPDATE codes SET related_code = CONCAT(related_code, ';IPPF2:1210020000000') WHERE related_code IS NOT NULL AND related_code LIKE '%IPPF:130190999%' AND related_code NOT LIKE '%IPPF2:%';
UPDATE drugs SET related_code = CONCAT(related_code, ';IPPF2:1210020000000') WHERE related_code IS NOT NULL AND related_code LIKE '%IPPF:130190999%' AND related_code NOT LIKE '%IPPF2:%';
UPDATE codes SET related_code = CONCAT(related_code, ';IPPF2:1210020000000') WHERE related_code IS NOT NULL AND related_code LIKE '%IPPF:131191210%' AND related_code NOT LIKE '%IPPF2:%';
UPDATE drugs SET related_code = CONCAT(related_code, ';IPPF2:1210020000000') WHERE related_code IS NOT NULL AND related_code LIKE '%IPPF:131191210%' AND related_code NOT LIKE '%IPPF2:%';
UPDATE codes SET related_code = CONCAT(related_code, ';IPPF2:1210020000000') WHERE related_code IS NOT NULL AND related_code LIKE '%IPPF:131191211%' AND related_code NOT LIKE '%IPPF2:%';
UPDATE drugs SET related_code = CONCAT(related_code, ';IPPF2:1210020000000') WHERE related_code IS NOT NULL AND related_code LIKE '%IPPF:131191211%' AND related_code NOT LIKE '%IPPF2:%';
UPDATE codes SET related_code = CONCAT(related_code, ';IPPF2:1210020000000') WHERE related_code IS NOT NULL AND related_code LIKE '%IPPF:131191310%' AND related_code NOT LIKE '%IPPF2:%';
UPDATE drugs SET related_code = CONCAT(related_code, ';IPPF2:1210020000000') WHERE related_code IS NOT NULL AND related_code LIKE '%IPPF:131191310%' AND related_code NOT LIKE '%IPPF2:%';
UPDATE codes SET related_code = CONCAT(related_code, ';IPPF2:1210020000000') WHERE related_code IS NOT NULL AND related_code LIKE '%IPPF:131191311%' AND related_code NOT LIKE '%IPPF2:%';
UPDATE drugs SET related_code = CONCAT(related_code, ';IPPF2:1210020000000') WHERE related_code IS NOT NULL AND related_code LIKE '%IPPF:131191311%' AND related_code NOT LIKE '%IPPF2:%';
UPDATE codes SET related_code = CONCAT(related_code, ';IPPF2:1210020000000') WHERE related_code IS NOT NULL AND related_code LIKE '%IPPF:131191410%' AND related_code NOT LIKE '%IPPF2:%';
UPDATE drugs SET related_code = CONCAT(related_code, ';IPPF2:1210020000000') WHERE related_code IS NOT NULL AND related_code LIKE '%IPPF:131191410%' AND related_code NOT LIKE '%IPPF2:%';
UPDATE codes SET related_code = CONCAT(related_code, ';IPPF2:1210020000000') WHERE related_code IS NOT NULL AND related_code LIKE '%IPPF:131191411%' AND related_code NOT LIKE '%IPPF2:%';
UPDATE drugs SET related_code = CONCAT(related_code, ';IPPF2:1210020000000') WHERE related_code IS NOT NULL AND related_code LIKE '%IPPF:131191411%' AND related_code NOT LIKE '%IPPF2:%';
UPDATE codes SET related_code = CONCAT(related_code, ';IPPF2:1210020000000') WHERE related_code IS NOT NULL AND related_code LIKE '%IPPF:131191510%' AND related_code NOT LIKE '%IPPF2:%';
UPDATE drugs SET related_code = CONCAT(related_code, ';IPPF2:1210020000000') WHERE related_code IS NOT NULL AND related_code LIKE '%IPPF:131191510%' AND related_code NOT LIKE '%IPPF2:%';
UPDATE codes SET related_code = CONCAT(related_code, ';IPPF2:1210020000000') WHERE related_code IS NOT NULL AND related_code LIKE '%IPPF:131191511%' AND related_code NOT LIKE '%IPPF2:%';
UPDATE drugs SET related_code = CONCAT(related_code, ';IPPF2:1210020000000') WHERE related_code IS NOT NULL AND related_code LIKE '%IPPF:131191511%' AND related_code NOT LIKE '%IPPF2:%';
UPDATE codes SET related_code = CONCAT(related_code, ';IPPF2:1210020000000') WHERE related_code IS NOT NULL AND related_code LIKE '%IPPF:131191610%' AND related_code NOT LIKE '%IPPF2:%';
UPDATE drugs SET related_code = CONCAT(related_code, ';IPPF2:1210020000000') WHERE related_code IS NOT NULL AND related_code LIKE '%IPPF:131191610%' AND related_code NOT LIKE '%IPPF2:%';
UPDATE codes SET related_code = CONCAT(related_code, ';IPPF2:1210020000000') WHERE related_code IS NOT NULL AND related_code LIKE '%IPPF:131191611%' AND related_code NOT LIKE '%IPPF2:%';
UPDATE drugs SET related_code = CONCAT(related_code, ';IPPF2:1210020000000') WHERE related_code IS NOT NULL AND related_code LIKE '%IPPF:131191611%' AND related_code NOT LIKE '%IPPF2:%';
UPDATE codes SET related_code = CONCAT(related_code, ';IPPF2:1210020000000') WHERE related_code IS NOT NULL AND related_code LIKE '%IPPF:131191999%' AND related_code NOT LIKE '%IPPF2:%';
UPDATE drugs SET related_code = CONCAT(related_code, ';IPPF2:1210020000000') WHERE related_code IS NOT NULL AND related_code LIKE '%IPPF:131191999%' AND related_code NOT LIKE '%IPPF2:%';
UPDATE codes SET related_code = CONCAT(related_code, ';IPPF2:2000000000000') WHERE related_code IS NOT NULL AND related_code LIKE '%IPPF:250000000%' AND related_code NOT LIKE '%IPPF2:%';
UPDATE drugs SET related_code = CONCAT(related_code, ';IPPF2:2000000000000') WHERE related_code IS NOT NULL AND related_code LIKE '%IPPF:250000000%' AND related_code NOT LIKE '%IPPF2:%';
UPDATE codes SET related_code = CONCAT(related_code, ';IPPF2:2110000000000') WHERE related_code IS NOT NULL AND related_code LIKE '%IPPF:252220000%' AND related_code NOT LIKE '%IPPF2:%';
UPDATE drugs SET related_code = CONCAT(related_code, ';IPPF2:2110000000000') WHERE related_code IS NOT NULL AND related_code LIKE '%IPPF:252220000%' AND related_code NOT LIKE '%IPPF2:%';
UPDATE codes SET related_code = CONCAT(related_code, ';IPPF2:2111010121000') WHERE related_code IS NOT NULL AND related_code LIKE '%IPPF:252221129%' AND related_code NOT LIKE '%IPPF2:%';
UPDATE drugs SET related_code = CONCAT(related_code, ';IPPF2:2111010121000') WHERE related_code IS NOT NULL AND related_code LIKE '%IPPF:252221129%' AND related_code NOT LIKE '%IPPF2:%';
UPDATE codes SET related_code = CONCAT(related_code, ';IPPF2:2111010121000') WHERE related_code IS NOT NULL AND related_code LIKE '%IPPF:252221229%' AND related_code NOT LIKE '%IPPF2:%';
UPDATE drugs SET related_code = CONCAT(related_code, ';IPPF2:2111010121000') WHERE related_code IS NOT NULL AND related_code LIKE '%IPPF:252221229%' AND related_code NOT LIKE '%IPPF2:%';
UPDATE codes SET related_code = CONCAT(related_code, ';IPPF2:2111010121000') WHERE related_code IS NOT NULL AND related_code LIKE '%IPPF:252221999%' AND related_code NOT LIKE '%IPPF2:%';
UPDATE drugs SET related_code = CONCAT(related_code, ';IPPF2:2111010121000') WHERE related_code IS NOT NULL AND related_code LIKE '%IPPF:252221999%' AND related_code NOT LIKE '%IPPF2:%';
UPDATE codes SET related_code = CONCAT(related_code, ';IPPF2:2111010121000') WHERE related_code IS NOT NULL AND related_code LIKE '%IPPF:256263229%' AND related_code NOT LIKE '%IPPF2:%';
UPDATE drugs SET related_code = CONCAT(related_code, ';IPPF2:2111010121000') WHERE related_code IS NOT NULL AND related_code LIKE '%IPPF:256263229%' AND related_code NOT LIKE '%IPPF2:%';
UPDATE codes SET related_code = CONCAT(related_code, ';IPPF2:2111010122000') WHERE related_code IS NOT NULL AND related_code LIKE '%IPPF:252227129%' AND related_code NOT LIKE '%IPPF2:%';
UPDATE drugs SET related_code = CONCAT(related_code, ';IPPF2:2111010122000') WHERE related_code IS NOT NULL AND related_code LIKE '%IPPF:252227129%' AND related_code NOT LIKE '%IPPF2:%';
UPDATE codes SET related_code = CONCAT(related_code, ';IPPF2:2111010122000') WHERE related_code IS NOT NULL AND related_code LIKE '%IPPF:252227999%' AND related_code NOT LIKE '%IPPF2:%';
UPDATE drugs SET related_code = CONCAT(related_code, ';IPPF2:2111010122000') WHERE related_code IS NOT NULL AND related_code LIKE '%IPPF:252227999%' AND related_code NOT LIKE '%IPPF2:%';
UPDATE codes SET related_code = CONCAT(related_code, ';IPPF2:2112020200000') WHERE related_code IS NOT NULL AND related_code LIKE '%IPPF:252226120%' AND related_code NOT LIKE '%IPPF2:%';
UPDATE drugs SET related_code = CONCAT(related_code, ';IPPF2:2112020200000') WHERE related_code IS NOT NULL AND related_code LIKE '%IPPF:252226120%' AND related_code NOT LIKE '%IPPF2:%';
UPDATE codes SET related_code = CONCAT(related_code, ';IPPF2:2112020201101') WHERE related_code IS NOT NULL AND related_code LIKE '%IPPF:252221329%' AND related_code NOT LIKE '%IPPF2:%';
UPDATE drugs SET related_code = CONCAT(related_code, ';IPPF2:2112020201101') WHERE related_code IS NOT NULL AND related_code LIKE '%IPPF:252221329%' AND related_code NOT LIKE '%IPPF2:%';
UPDATE codes SET related_code = CONCAT(related_code, ';IPPF2:2112020202101') WHERE related_code IS NOT NULL AND related_code LIKE '%IPPF:252227229%' AND related_code NOT LIKE '%IPPF2:%';
UPDATE drugs SET related_code = CONCAT(related_code, ';IPPF2:2112020202101') WHERE related_code IS NOT NULL AND related_code LIKE '%IPPF:252227229%' AND related_code NOT LIKE '%IPPF2:%';
UPDATE codes SET related_code = CONCAT(related_code, ';IPPF2:2112020000800') WHERE related_code IS NOT NULL AND related_code LIKE '%IPPF:252220999%' AND related_code NOT LIKE '%IPPF2:%';
UPDATE drugs SET related_code = CONCAT(related_code, ';IPPF2:2112020000800') WHERE related_code IS NOT NULL AND related_code LIKE '%IPPF:252220999%' AND related_code NOT LIKE '%IPPF2:%';
UPDATE codes SET related_code = CONCAT(related_code, ';IPPF2:2113130301000') WHERE related_code IS NOT NULL AND related_code LIKE '%IPPF:252224000%' AND related_code NOT LIKE '%IPPF2:%';
UPDATE drugs SET related_code = CONCAT(related_code, ';IPPF2:2113130301000') WHERE related_code IS NOT NULL AND related_code LIKE '%IPPF:252224000%' AND related_code NOT LIKE '%IPPF2:%';
UPDATE codes SET related_code = CONCAT(related_code, ';IPPF2:2113130301101') WHERE related_code IS NOT NULL AND related_code LIKE '%IPPF:252224222%' AND related_code NOT LIKE '%IPPF2:%';
UPDATE drugs SET related_code = CONCAT(related_code, ';IPPF2:2113130301101') WHERE related_code IS NOT NULL AND related_code LIKE '%IPPF:252224222%' AND related_code NOT LIKE '%IPPF2:%';
UPDATE codes SET related_code = CONCAT(related_code, ';IPPF2:2113130301102') WHERE related_code IS NOT NULL AND related_code LIKE '%IPPF:252224122%' AND related_code NOT LIKE '%IPPF2:%';
UPDATE drugs SET related_code = CONCAT(related_code, ';IPPF2:2113130301102') WHERE related_code IS NOT NULL AND related_code LIKE '%IPPF:252224122%' AND related_code NOT LIKE '%IPPF2:%';
UPDATE codes SET related_code = CONCAT(related_code, ';IPPF2:2113130301110') WHERE related_code IS NOT NULL AND related_code LIKE '%IPPF:252226999%' AND related_code NOT LIKE '%IPPF2:%';
UPDATE drugs SET related_code = CONCAT(related_code, ';IPPF2:2113130301110') WHERE related_code IS NOT NULL AND related_code LIKE '%IPPF:252226999%' AND related_code NOT LIKE '%IPPF2:%';
UPDATE codes SET related_code = CONCAT(related_code, ';IPPF2:2113130301800') WHERE related_code IS NOT NULL AND related_code LIKE '%IPPF:252224999%' AND related_code NOT LIKE '%IPPF2:%';
UPDATE drugs SET related_code = CONCAT(related_code, ';IPPF2:2113130301800') WHERE related_code IS NOT NULL AND related_code LIKE '%IPPF:252224999%' AND related_code NOT LIKE '%IPPF2:%';
UPDATE codes SET related_code = CONCAT(related_code, ';IPPF2:2113230302000') WHERE related_code IS NOT NULL AND related_code LIKE '%IPPF:252223000%' AND related_code NOT LIKE '%IPPF2:%';
UPDATE drugs SET related_code = CONCAT(related_code, ';IPPF2:2113230302000') WHERE related_code IS NOT NULL AND related_code LIKE '%IPPF:252223000%' AND related_code NOT LIKE '%IPPF2:%';
UPDATE codes SET related_code = CONCAT(related_code, ';IPPF2:2113230302301') WHERE related_code IS NOT NULL AND related_code LIKE '%IPPF:252223123%' AND related_code NOT LIKE '%IPPF2:%';
UPDATE drugs SET related_code = CONCAT(related_code, ';IPPF2:2113230302301') WHERE related_code IS NOT NULL AND related_code LIKE '%IPPF:252223123%' AND related_code NOT LIKE '%IPPF2:%';
UPDATE codes SET related_code = CONCAT(related_code, ';IPPF2:2113230302302') WHERE related_code IS NOT NULL AND related_code LIKE '%IPPF:252223223%' AND related_code NOT LIKE '%IPPF2:%';
UPDATE drugs SET related_code = CONCAT(related_code, ';IPPF2:2113230302302') WHERE related_code IS NOT NULL AND related_code LIKE '%IPPF:252223223%' AND related_code NOT LIKE '%IPPF2:%';
UPDATE codes SET related_code = CONCAT(related_code, ';IPPF2:2113230302304') WHERE related_code IS NOT NULL AND related_code LIKE '%IPPF:252223323%' AND related_code NOT LIKE '%IPPF2:%';
UPDATE drugs SET related_code = CONCAT(related_code, ';IPPF2:2113230302304') WHERE related_code IS NOT NULL AND related_code LIKE '%IPPF:252223323%' AND related_code NOT LIKE '%IPPF2:%';
UPDATE codes SET related_code = CONCAT(related_code, ';IPPF2:2113230302310') WHERE related_code IS NOT NULL AND related_code LIKE '%IPPF:252226999%' AND related_code NOT LIKE '%IPPF2:%';
UPDATE drugs SET related_code = CONCAT(related_code, ';IPPF2:2113230302310') WHERE related_code IS NOT NULL AND related_code LIKE '%IPPF:252226999%' AND related_code NOT LIKE '%IPPF2:%';
UPDATE codes SET related_code = CONCAT(related_code, ';IPPF2:2113230302800') WHERE related_code IS NOT NULL AND related_code LIKE '%IPPF:252223999%' AND related_code NOT LIKE '%IPPF2:%';
UPDATE drugs SET related_code = CONCAT(related_code, ';IPPF2:2113230302800') WHERE related_code IS NOT NULL AND related_code LIKE '%IPPF:252223999%' AND related_code NOT LIKE '%IPPF2:%';
UPDATE codes SET related_code = CONCAT(related_code, ';IPPF2:2114030000000') WHERE related_code IS NOT NULL AND related_code LIKE '%IPPF:252225000%' AND related_code NOT LIKE '%IPPF2:%';
UPDATE drugs SET related_code = CONCAT(related_code, ';IPPF2:2114030000000') WHERE related_code IS NOT NULL AND related_code LIKE '%IPPF:252225000%' AND related_code NOT LIKE '%IPPF2:%';
UPDATE codes SET related_code = CONCAT(related_code, ';IPPF2:2114030301101') WHERE related_code IS NOT NULL AND related_code LIKE '%IPPF:252225722%' AND related_code NOT LIKE '%IPPF2:%';
UPDATE drugs SET related_code = CONCAT(related_code, ';IPPF2:2114030301101') WHERE related_code IS NOT NULL AND related_code LIKE '%IPPF:252225722%' AND related_code NOT LIKE '%IPPF2:%';
UPDATE codes SET related_code = CONCAT(related_code, ';IPPF2:2114030302303') WHERE related_code IS NOT NULL AND related_code LIKE '%IPPF:252225123%' AND related_code NOT LIKE '%IPPF2:%';
UPDATE drugs SET related_code = CONCAT(related_code, ';IPPF2:2114030302303') WHERE related_code IS NOT NULL AND related_code LIKE '%IPPF:252225123%' AND related_code NOT LIKE '%IPPF2:%';
UPDATE codes SET related_code = CONCAT(related_code, ';IPPF2:2114030302304') WHERE related_code IS NOT NULL AND related_code LIKE '%IPPF:252225223%' AND related_code NOT LIKE '%IPPF2:%';
UPDATE drugs SET related_code = CONCAT(related_code, ';IPPF2:2114030302304') WHERE related_code IS NOT NULL AND related_code LIKE '%IPPF:252225223%' AND related_code NOT LIKE '%IPPF2:%';
UPDATE codes SET related_code = CONCAT(related_code, ';IPPF2:2114030302800') WHERE related_code IS NOT NULL AND related_code LIKE '%IPPF:252225999%' AND related_code NOT LIKE '%IPPF2:%';
UPDATE drugs SET related_code = CONCAT(related_code, ';IPPF2:2114030302800') WHERE related_code IS NOT NULL AND related_code LIKE '%IPPF:252225999%' AND related_code NOT LIKE '%IPPF2:%';
UPDATE codes SET related_code = CONCAT(related_code, ';IPPF2:2112020000800') WHERE related_code IS NOT NULL AND related_code LIKE '%IPPF:252222121%' AND related_code NOT LIKE '%IPPF2:%';
UPDATE drugs SET related_code = CONCAT(related_code, ';IPPF2:2112020000800') WHERE related_code IS NOT NULL AND related_code LIKE '%IPPF:252222121%' AND related_code NOT LIKE '%IPPF2:%';
UPDATE codes SET related_code = CONCAT(related_code, ';IPPF2:2112020000800') WHERE related_code IS NOT NULL AND related_code LIKE '%IPPF:252222221%' AND related_code NOT LIKE '%IPPF2:%';
UPDATE drugs SET related_code = CONCAT(related_code, ';IPPF2:2112020000800') WHERE related_code IS NOT NULL AND related_code LIKE '%IPPF:252222221%' AND related_code NOT LIKE '%IPPF2:%';
UPDATE codes SET related_code = CONCAT(related_code, ';IPPF2:2112020000800') WHERE related_code IS NOT NULL AND related_code LIKE '%IPPF:252222321%' AND related_code NOT LIKE '%IPPF2:%';
UPDATE drugs SET related_code = CONCAT(related_code, ';IPPF2:2112020000800') WHERE related_code IS NOT NULL AND related_code LIKE '%IPPF:252222321%' AND related_code NOT LIKE '%IPPF2:%';
UPDATE codes SET related_code = CONCAT(related_code, ';IPPF2:2112020000800') WHERE related_code IS NOT NULL AND related_code LIKE '%IPPF:252222421%' AND related_code NOT LIKE '%IPPF2:%';
UPDATE drugs SET related_code = CONCAT(related_code, ';IPPF2:2112020000800') WHERE related_code IS NOT NULL AND related_code LIKE '%IPPF:252222421%' AND related_code NOT LIKE '%IPPF2:%';
UPDATE codes SET related_code = CONCAT(related_code, ';IPPF2:2112020000800') WHERE related_code IS NOT NULL AND related_code LIKE '%IPPF:252222521%' AND related_code NOT LIKE '%IPPF2:%';
UPDATE drugs SET related_code = CONCAT(related_code, ';IPPF2:2112020000800') WHERE related_code IS NOT NULL AND related_code LIKE '%IPPF:252222521%' AND related_code NOT LIKE '%IPPF2:%';
UPDATE codes SET related_code = CONCAT(related_code, ';IPPF2:2112020000800') WHERE related_code IS NOT NULL AND related_code LIKE '%IPPF:252222999%' AND related_code NOT LIKE '%IPPF2:%';
UPDATE drugs SET related_code = CONCAT(related_code, ';IPPF2:2112020000800') WHERE related_code IS NOT NULL AND related_code LIKE '%IPPF:252222999%' AND related_code NOT LIKE '%IPPF2:%';
UPDATE codes SET related_code = CONCAT(related_code, ';IPPF2:2120000000000') WHERE related_code IS NOT NULL AND related_code LIKE '%IPPF:253230000%' AND related_code NOT LIKE '%IPPF2:%';
UPDATE drugs SET related_code = CONCAT(related_code, ';IPPF2:2120000000000') WHERE related_code IS NOT NULL AND related_code LIKE '%IPPF:253230000%' AND related_code NOT LIKE '%IPPF2:%';
UPDATE codes SET related_code = CONCAT(related_code, ';IPPF2:2121010000000') WHERE related_code IS NOT NULL AND related_code LIKE '%IPPF:253235000%' AND related_code NOT LIKE '%IPPF2:%';
UPDATE drugs SET related_code = CONCAT(related_code, ';IPPF2:2121010000000') WHERE related_code IS NOT NULL AND related_code LIKE '%IPPF:253235000%' AND related_code NOT LIKE '%IPPF2:%';
UPDATE codes SET related_code = CONCAT(related_code, ';IPPF2:2121010111000') WHERE related_code IS NOT NULL AND related_code LIKE '%IPPF:253235129%' AND related_code NOT LIKE '%IPPF2:%';
UPDATE drugs SET related_code = CONCAT(related_code, ';IPPF2:2121010111000') WHERE related_code IS NOT NULL AND related_code LIKE '%IPPF:253235129%' AND related_code NOT LIKE '%IPPF2:%';
UPDATE codes SET related_code = CONCAT(related_code, ';IPPF2:2121010112000') WHERE related_code IS NOT NULL AND related_code LIKE '%IPPF:253235229%' AND related_code NOT LIKE '%IPPF2:%';
UPDATE drugs SET related_code = CONCAT(related_code, ';IPPF2:2121010112000') WHERE related_code IS NOT NULL AND related_code LIKE '%IPPF:253235229%' AND related_code NOT LIKE '%IPPF2:%';
UPDATE codes SET related_code = CONCAT(related_code, ';IPPF2:2121010112000') WHERE related_code IS NOT NULL AND related_code LIKE '%IPPF:253235329%' AND related_code NOT LIKE '%IPPF2:%';
UPDATE drugs SET related_code = CONCAT(related_code, ';IPPF2:2121010112000') WHERE related_code IS NOT NULL AND related_code LIKE '%IPPF:253235329%' AND related_code NOT LIKE '%IPPF2:%';
UPDATE codes SET related_code = CONCAT(related_code, ';IPPF2:2121010112000') WHERE related_code IS NOT NULL AND related_code LIKE '%IPPF:253235429%' AND related_code NOT LIKE '%IPPF2:%';
UPDATE drugs SET related_code = CONCAT(related_code, ';IPPF2:2121010112000') WHERE related_code IS NOT NULL AND related_code LIKE '%IPPF:253235429%' AND related_code NOT LIKE '%IPPF2:%';
UPDATE codes SET related_code = CONCAT(related_code, ';IPPF2:2121010112000') WHERE related_code IS NOT NULL AND related_code LIKE '%IPPF:253235529%' AND related_code NOT LIKE '%IPPF2:%';
UPDATE drugs SET related_code = CONCAT(related_code, ';IPPF2:2121010112000') WHERE related_code IS NOT NULL AND related_code LIKE '%IPPF:253235529%' AND related_code NOT LIKE '%IPPF2:%';
UPDATE codes SET related_code = CONCAT(related_code, ';IPPF2:2121010123000') WHERE related_code IS NOT NULL AND related_code LIKE '%IPPF:253234129%' AND related_code NOT LIKE '%IPPF2:%';
UPDATE drugs SET related_code = CONCAT(related_code, ';IPPF2:2121010123000') WHERE related_code IS NOT NULL AND related_code LIKE '%IPPF:253234129%' AND related_code NOT LIKE '%IPPF2:%';
UPDATE codes SET related_code = CONCAT(related_code, ';IPPF2:2121010123000') WHERE related_code IS NOT NULL AND related_code LIKE '%IPPF:253234999%' AND related_code NOT LIKE '%IPPF2:%';
UPDATE drugs SET related_code = CONCAT(related_code, ';IPPF2:2121010123000') WHERE related_code IS NOT NULL AND related_code LIKE '%IPPF:253234999%' AND related_code NOT LIKE '%IPPF2:%';
UPDATE codes SET related_code = CONCAT(related_code, ';IPPF2:2121010124000') WHERE related_code IS NOT NULL AND related_code LIKE '%IPPF:253231422%' AND related_code NOT LIKE '%IPPF2:%';
UPDATE drugs SET related_code = CONCAT(related_code, ';IPPF2:2121010124000') WHERE related_code IS NOT NULL AND related_code LIKE '%IPPF:253231422%' AND related_code NOT LIKE '%IPPF2:%';
UPDATE codes SET related_code = CONCAT(related_code, ';IPPF2:2121010000800') WHERE related_code IS NOT NULL AND related_code LIKE '%IPPF:253235999%' AND related_code NOT LIKE '%IPPF2:%';
UPDATE drugs SET related_code = CONCAT(related_code, ';IPPF2:2121010000800') WHERE related_code IS NOT NULL AND related_code LIKE '%IPPF:253235999%' AND related_code NOT LIKE '%IPPF2:%';
UPDATE codes SET related_code = CONCAT(related_code, ';IPPF2:2122020200000') WHERE related_code IS NOT NULL AND related_code LIKE '%IPPF:253230999%' AND related_code NOT LIKE '%IPPF2:%';
UPDATE drugs SET related_code = CONCAT(related_code, ';IPPF2:2122020200000') WHERE related_code IS NOT NULL AND related_code LIKE '%IPPF:253230999%' AND related_code NOT LIKE '%IPPF2:%';
UPDATE codes SET related_code = CONCAT(related_code, ';IPPF2:2123030000000') WHERE related_code IS NOT NULL AND related_code LIKE '%IPPF:253231000%' AND related_code NOT LIKE '%IPPF2:%';
UPDATE drugs SET related_code = CONCAT(related_code, ';IPPF2:2123030000000') WHERE related_code IS NOT NULL AND related_code LIKE '%IPPF:253231000%' AND related_code NOT LIKE '%IPPF2:%';
UPDATE codes SET related_code = CONCAT(related_code, ';IPPF2:2123030301301') WHERE related_code IS NOT NULL AND related_code LIKE '%IPPF:253231122%' AND related_code NOT LIKE '%IPPF2:%';
UPDATE drugs SET related_code = CONCAT(related_code, ';IPPF2:2123030301301') WHERE related_code IS NOT NULL AND related_code LIKE '%IPPF:253231122%' AND related_code NOT LIKE '%IPPF2:%';
UPDATE codes SET related_code = CONCAT(related_code, ';IPPF2:2123030301509') WHERE related_code IS NOT NULL AND related_code LIKE '%IPPF:253231222%' AND related_code NOT LIKE '%IPPF2:%';
UPDATE drugs SET related_code = CONCAT(related_code, ';IPPF2:2123030301509') WHERE related_code IS NOT NULL AND related_code LIKE '%IPPF:253231222%' AND related_code NOT LIKE '%IPPF2:%';
UPDATE codes SET related_code = CONCAT(related_code, ';IPPF2:2123030000800') WHERE related_code IS NOT NULL AND related_code LIKE '%IPPF:253231522%' AND related_code NOT LIKE '%IPPF2:%';
UPDATE drugs SET related_code = CONCAT(related_code, ';IPPF2:2123030000800') WHERE related_code IS NOT NULL AND related_code LIKE '%IPPF:253231522%' AND related_code NOT LIKE '%IPPF2:%';
UPDATE codes SET related_code = CONCAT(related_code, ';IPPF2:2123030000800') WHERE related_code IS NOT NULL AND related_code LIKE '%IPPF:253231999%' AND related_code NOT LIKE '%IPPF2:%';
UPDATE drugs SET related_code = CONCAT(related_code, ';IPPF2:2123030000800') WHERE related_code IS NOT NULL AND related_code LIKE '%IPPF:253231999%' AND related_code NOT LIKE '%IPPF2:%';
UPDATE codes SET related_code = CONCAT(related_code, ';IPPF2:2124040401101') WHERE related_code IS NOT NULL AND related_code LIKE '%IPPF:253231322%' AND related_code NOT LIKE '%IPPF2:%';
UPDATE drugs SET related_code = CONCAT(related_code, ';IPPF2:2124040401101') WHERE related_code IS NOT NULL AND related_code LIKE '%IPPF:253231322%' AND related_code NOT LIKE '%IPPF2:%';
UPDATE codes SET related_code = CONCAT(related_code, ';IPPF2:2125050000000') WHERE related_code IS NOT NULL AND related_code LIKE '%IPPF:253232000%' AND related_code NOT LIKE '%IPPF2:%';
UPDATE drugs SET related_code = CONCAT(related_code, ';IPPF2:2125050000000') WHERE related_code IS NOT NULL AND related_code LIKE '%IPPF:253232000%' AND related_code NOT LIKE '%IPPF2:%';
UPDATE codes SET related_code = CONCAT(related_code, ';IPPF2:2125050503101') WHERE related_code IS NOT NULL AND related_code LIKE '%IPPF:253232121%' AND related_code NOT LIKE '%IPPF2:%';
UPDATE drugs SET related_code = CONCAT(related_code, ';IPPF2:2125050503101') WHERE related_code IS NOT NULL AND related_code LIKE '%IPPF:253232121%' AND related_code NOT LIKE '%IPPF2:%';
UPDATE codes SET related_code = CONCAT(related_code, ';IPPF2:2125050503101') WHERE related_code IS NOT NULL AND related_code LIKE '%IPPF:253232221%' AND related_code NOT LIKE '%IPPF2:%';
UPDATE drugs SET related_code = CONCAT(related_code, ';IPPF2:2125050503101') WHERE related_code IS NOT NULL AND related_code LIKE '%IPPF:253232221%' AND related_code NOT LIKE '%IPPF2:%';
UPDATE codes SET related_code = CONCAT(related_code, ';IPPF2:2125050503101') WHERE related_code IS NOT NULL AND related_code LIKE '%IPPF:253232321%' AND related_code NOT LIKE '%IPPF2:%';
UPDATE drugs SET related_code = CONCAT(related_code, ';IPPF2:2125050503101') WHERE related_code IS NOT NULL AND related_code LIKE '%IPPF:253232321%' AND related_code NOT LIKE '%IPPF2:%';
UPDATE codes SET related_code = CONCAT(related_code, ';IPPF2:2125050503101') WHERE related_code IS NOT NULL AND related_code LIKE '%IPPF:253232521%' AND related_code NOT LIKE '%IPPF2:%';
UPDATE drugs SET related_code = CONCAT(related_code, ';IPPF2:2125050503101') WHERE related_code IS NOT NULL AND related_code LIKE '%IPPF:253232521%' AND related_code NOT LIKE '%IPPF2:%';
UPDATE codes SET related_code = CONCAT(related_code, ';IPPF2:2125050503101') WHERE related_code IS NOT NULL AND related_code LIKE '%IPPF:253233121%' AND related_code NOT LIKE '%IPPF2:%';
UPDATE drugs SET related_code = CONCAT(related_code, ';IPPF2:2125050503101') WHERE related_code IS NOT NULL AND related_code LIKE '%IPPF:253233121%' AND related_code NOT LIKE '%IPPF2:%';
UPDATE codes SET related_code = CONCAT(related_code, ';IPPF2:2125050503104') WHERE related_code IS NOT NULL AND related_code LIKE '%IPPF:253232421%' AND related_code NOT LIKE '%IPPF2:%';
UPDATE drugs SET related_code = CONCAT(related_code, ';IPPF2:2125050503104') WHERE related_code IS NOT NULL AND related_code LIKE '%IPPF:253232421%' AND related_code NOT LIKE '%IPPF2:%';
UPDATE codes SET related_code = CONCAT(related_code, ';IPPF2:2125050503105') WHERE related_code IS NOT NULL AND related_code LIKE '%IPPF:253233221%' AND related_code NOT LIKE '%IPPF2:%';
UPDATE drugs SET related_code = CONCAT(related_code, ';IPPF2:2125050503105') WHERE related_code IS NOT NULL AND related_code LIKE '%IPPF:253233221%' AND related_code NOT LIKE '%IPPF2:%';
UPDATE codes SET related_code = CONCAT(related_code, ';IPPF2:2125050503106') WHERE related_code IS NOT NULL AND related_code LIKE '%IPPF:253233321%' AND related_code NOT LIKE '%IPPF2:%';
UPDATE drugs SET related_code = CONCAT(related_code, ';IPPF2:2125050503106') WHERE related_code IS NOT NULL AND related_code LIKE '%IPPF:253233321%' AND related_code NOT LIKE '%IPPF2:%';
UPDATE codes SET related_code = CONCAT(related_code, ';IPPF2:2125050503888') WHERE related_code IS NOT NULL AND related_code LIKE '%IPPF:253232999%' AND related_code NOT LIKE '%IPPF2:%';
UPDATE drugs SET related_code = CONCAT(related_code, ';IPPF2:2125050503888') WHERE related_code IS NOT NULL AND related_code LIKE '%IPPF:253232999%' AND related_code NOT LIKE '%IPPF2:%';
UPDATE codes SET related_code = CONCAT(related_code, ';IPPF2:2125050503888') WHERE related_code IS NOT NULL AND related_code LIKE '%IPPF:253233999%' AND related_code NOT LIKE '%IPPF2:%';
UPDATE drugs SET related_code = CONCAT(related_code, ';IPPF2:2125050503888') WHERE related_code IS NOT NULL AND related_code LIKE '%IPPF:253233999%' AND related_code NOT LIKE '%IPPF2:%';
UPDATE codes SET related_code = CONCAT(related_code, ';IPPF2:2130000000000') WHERE related_code IS NOT NULL AND related_code LIKE '%IPPF:254240000%' AND related_code NOT LIKE '%IPPF2:%';
UPDATE drugs SET related_code = CONCAT(related_code, ';IPPF2:2130000000000') WHERE related_code IS NOT NULL AND related_code LIKE '%IPPF:254240000%' AND related_code NOT LIKE '%IPPF2:%';
UPDATE codes SET related_code = CONCAT(related_code, ';IPPF2:2131010112000') WHERE related_code IS NOT NULL AND related_code LIKE '%IPPF:254241229%' AND related_code NOT LIKE '%IPPF2:%';
UPDATE drugs SET related_code = CONCAT(related_code, ';IPPF2:2131010112000') WHERE related_code IS NOT NULL AND related_code LIKE '%IPPF:254241229%' AND related_code NOT LIKE '%IPPF2:%';
UPDATE codes SET related_code = CONCAT(related_code, ';IPPF2:2131010112000') WHERE related_code IS NOT NULL AND related_code LIKE '%IPPF:254241999%' AND related_code NOT LIKE '%IPPF2:%';
UPDATE drugs SET related_code = CONCAT(related_code, ';IPPF2:2131010112000') WHERE related_code IS NOT NULL AND related_code LIKE '%IPPF:254241999%' AND related_code NOT LIKE '%IPPF2:%';
UPDATE codes SET related_code = CONCAT(related_code, ';IPPF2:2131010123000') WHERE related_code IS NOT NULL AND related_code LIKE '%IPPF:254241129%' AND related_code NOT LIKE '%IPPF2:%';
UPDATE drugs SET related_code = CONCAT(related_code, ';IPPF2:2131010123000') WHERE related_code IS NOT NULL AND related_code LIKE '%IPPF:254241129%' AND related_code NOT LIKE '%IPPF2:%';
UPDATE codes SET related_code = CONCAT(related_code, ';IPPF2:2131010000800') WHERE related_code IS NOT NULL AND related_code LIKE '%IPPF:254240999%' AND related_code NOT LIKE '%IPPF2:%';
UPDATE drugs SET related_code = CONCAT(related_code, ';IPPF2:2131010000800') WHERE related_code IS NOT NULL AND related_code LIKE '%IPPF:254240999%' AND related_code NOT LIKE '%IPPF2:%';
UPDATE codes SET related_code = CONCAT(related_code, ';IPPF2:2132020000000') WHERE related_code IS NOT NULL AND related_code LIKE '%IPPF:254242000%' AND related_code NOT LIKE '%IPPF2:%';
UPDATE drugs SET related_code = CONCAT(related_code, ';IPPF2:2132020000000') WHERE related_code IS NOT NULL AND related_code LIKE '%IPPF:254242000%' AND related_code NOT LIKE '%IPPF2:%';
UPDATE codes SET related_code = CONCAT(related_code, ';IPPF2:2132020200000') WHERE related_code IS NOT NULL AND related_code LIKE '%IPPF:254242120%' AND related_code NOT LIKE '%IPPF2:%';
UPDATE drugs SET related_code = CONCAT(related_code, ';IPPF2:2132020200000') WHERE related_code IS NOT NULL AND related_code LIKE '%IPPF:254242120%' AND related_code NOT LIKE '%IPPF2:%';
UPDATE codes SET related_code = CONCAT(related_code, ';IPPF2:2132020200000') WHERE related_code IS NOT NULL AND related_code LIKE '%IPPF:254242999%' AND related_code NOT LIKE '%IPPF2:%';
UPDATE drugs SET related_code = CONCAT(related_code, ';IPPF2:2132020200000') WHERE related_code IS NOT NULL AND related_code LIKE '%IPPF:254242999%' AND related_code NOT LIKE '%IPPF2:%';
UPDATE codes SET related_code = CONCAT(related_code, ';IPPF2:2133130303000') WHERE related_code IS NOT NULL AND related_code LIKE '%IPPF:254245122%' AND related_code NOT LIKE '%IPPF2:%';
UPDATE drugs SET related_code = CONCAT(related_code, ';IPPF2:2133130303000') WHERE related_code IS NOT NULL AND related_code LIKE '%IPPF:254245122%' AND related_code NOT LIKE '%IPPF2:%';
UPDATE codes SET related_code = CONCAT(related_code, ';IPPF2:2133230000000') WHERE related_code IS NOT NULL AND related_code LIKE '%IPPF:254246000%' AND related_code NOT LIKE '%IPPF2:%';
UPDATE drugs SET related_code = CONCAT(related_code, ';IPPF2:2133230000000') WHERE related_code IS NOT NULL AND related_code LIKE '%IPPF:254246000%' AND related_code NOT LIKE '%IPPF2:%';
UPDATE codes SET related_code = CONCAT(related_code, ';IPPF2:2133230304000') WHERE related_code IS NOT NULL AND related_code LIKE '%IPPF:254245222%' AND related_code NOT LIKE '%IPPF2:%';
UPDATE drugs SET related_code = CONCAT(related_code, ';IPPF2:2133230304000') WHERE related_code IS NOT NULL AND related_code LIKE '%IPPF:254245222%' AND related_code NOT LIKE '%IPPF2:%';
UPDATE codes SET related_code = CONCAT(related_code, ';IPPF2:2133230304000') WHERE related_code IS NOT NULL AND related_code LIKE '%IPPF:254246122%' AND related_code NOT LIKE '%IPPF2:%';
UPDATE drugs SET related_code = CONCAT(related_code, ';IPPF2:2133230304000') WHERE related_code IS NOT NULL AND related_code LIKE '%IPPF:254246122%' AND related_code NOT LIKE '%IPPF2:%';
UPDATE codes SET related_code = CONCAT(related_code, ';IPPF2:2133230304000') WHERE related_code IS NOT NULL AND related_code LIKE '%IPPF:254246222%' AND related_code NOT LIKE '%IPPF2:%';
UPDATE drugs SET related_code = CONCAT(related_code, ';IPPF2:2133230304000') WHERE related_code IS NOT NULL AND related_code LIKE '%IPPF:254246222%' AND related_code NOT LIKE '%IPPF2:%';
UPDATE codes SET related_code = CONCAT(related_code, ';IPPF2:2133230304000') WHERE related_code IS NOT NULL AND related_code LIKE '%IPPF:254247122%' AND related_code NOT LIKE '%IPPF2:%';
UPDATE drugs SET related_code = CONCAT(related_code, ';IPPF2:2133230304000') WHERE related_code IS NOT NULL AND related_code LIKE '%IPPF:254247122%' AND related_code NOT LIKE '%IPPF2:%';
UPDATE codes SET related_code = CONCAT(related_code, ';IPPF2:2133230304000') WHERE related_code IS NOT NULL AND related_code LIKE '%IPPF:254247422%' AND related_code NOT LIKE '%IPPF2:%';
UPDATE drugs SET related_code = CONCAT(related_code, ';IPPF2:2133230304000') WHERE related_code IS NOT NULL AND related_code LIKE '%IPPF:254247422%' AND related_code NOT LIKE '%IPPF2:%';
UPDATE codes SET related_code = CONCAT(related_code, ';IPPF2:2133230304000') WHERE related_code IS NOT NULL AND related_code LIKE '%IPPF:254247522%' AND related_code NOT LIKE '%IPPF2:%';
UPDATE drugs SET related_code = CONCAT(related_code, ';IPPF2:2133230304000') WHERE related_code IS NOT NULL AND related_code LIKE '%IPPF:254247522%' AND related_code NOT LIKE '%IPPF2:%';
UPDATE codes SET related_code = CONCAT(related_code, ';IPPF2:2133230304000') WHERE related_code IS NOT NULL AND related_code LIKE '%IPPF:254247622%' AND related_code NOT LIKE '%IPPF2:%';
UPDATE drugs SET related_code = CONCAT(related_code, ';IPPF2:2133230304000') WHERE related_code IS NOT NULL AND related_code LIKE '%IPPF:254247622%' AND related_code NOT LIKE '%IPPF2:%';
UPDATE codes SET related_code = CONCAT(related_code, ';IPPF2:2133230304000') WHERE related_code IS NOT NULL AND related_code LIKE '%IPPF:254247999%' AND related_code NOT LIKE '%IPPF2:%';
UPDATE drugs SET related_code = CONCAT(related_code, ';IPPF2:2133230304000') WHERE related_code IS NOT NULL AND related_code LIKE '%IPPF:254247999%' AND related_code NOT LIKE '%IPPF2:%';
UPDATE codes SET related_code = CONCAT(related_code, ';IPPF2:2133230304101') WHERE related_code IS NOT NULL AND related_code LIKE '%IPPF:254246422%' AND related_code NOT LIKE '%IPPF2:%';
UPDATE drugs SET related_code = CONCAT(related_code, ';IPPF2:2133230304101') WHERE related_code IS NOT NULL AND related_code LIKE '%IPPF:254246422%' AND related_code NOT LIKE '%IPPF2:%';
UPDATE codes SET related_code = CONCAT(related_code, ';IPPF2:2133230304102') WHERE related_code IS NOT NULL AND related_code LIKE '%IPPF:254246322%' AND related_code NOT LIKE '%IPPF2:%';
UPDATE drugs SET related_code = CONCAT(related_code, ';IPPF2:2133230304102') WHERE related_code IS NOT NULL AND related_code LIKE '%IPPF:254246322%' AND related_code NOT LIKE '%IPPF2:%';
UPDATE codes SET related_code = CONCAT(related_code, ';IPPF2:2133230304103') WHERE related_code IS NOT NULL AND related_code LIKE '%IPPF:254246522%' AND related_code NOT LIKE '%IPPF2:%';
UPDATE drugs SET related_code = CONCAT(related_code, ';IPPF2:2133230304103') WHERE related_code IS NOT NULL AND related_code LIKE '%IPPF:254246522%' AND related_code NOT LIKE '%IPPF2:%';
UPDATE codes SET related_code = CONCAT(related_code, ';IPPF2:2133230304104') WHERE related_code IS NOT NULL AND related_code LIKE '%IPPF:254247322%' AND related_code NOT LIKE '%IPPF2:%';
UPDATE drugs SET related_code = CONCAT(related_code, ';IPPF2:2133230304104') WHERE related_code IS NOT NULL AND related_code LIKE '%IPPF:254247322%' AND related_code NOT LIKE '%IPPF2:%';
UPDATE codes SET related_code = CONCAT(related_code, ';IPPF2:2133230304105') WHERE related_code IS NOT NULL AND related_code LIKE '%IPPF:254247222%' AND related_code NOT LIKE '%IPPF2:%';
UPDATE drugs SET related_code = CONCAT(related_code, ';IPPF2:2133230304105') WHERE related_code IS NOT NULL AND related_code LIKE '%IPPF:254247222%' AND related_code NOT LIKE '%IPPF2:%';
UPDATE codes SET related_code = CONCAT(related_code, ';IPPF2:2133230304800') WHERE related_code IS NOT NULL AND related_code LIKE '%IPPF:254245999%' AND related_code NOT LIKE '%IPPF2:%';
UPDATE drugs SET related_code = CONCAT(related_code, ';IPPF2:2133230304800') WHERE related_code IS NOT NULL AND related_code LIKE '%IPPF:254245999%' AND related_code NOT LIKE '%IPPF2:%';
UPDATE codes SET related_code = CONCAT(related_code, ';IPPF2:2134040000000') WHERE related_code IS NOT NULL AND related_code LIKE '%IPPF:254245000%' AND related_code NOT LIKE '%IPPF2:%';
UPDATE drugs SET related_code = CONCAT(related_code, ';IPPF2:2134040000000') WHERE related_code IS NOT NULL AND related_code LIKE '%IPPF:254245000%' AND related_code NOT LIKE '%IPPF2:%';
UPDATE codes SET related_code = CONCAT(related_code, ';IPPF2:2134040401201') WHERE related_code IS NOT NULL AND related_code LIKE '%IPPF:254245322%' AND related_code NOT LIKE '%IPPF2:%';
UPDATE drugs SET related_code = CONCAT(related_code, ';IPPF2:2134040401201') WHERE related_code IS NOT NULL AND related_code LIKE '%IPPF:254245322%' AND related_code NOT LIKE '%IPPF2:%';
UPDATE codes SET related_code = CONCAT(related_code, ';IPPF2:2134040401202') WHERE related_code IS NOT NULL AND related_code LIKE '%IPPF:254245422%' AND related_code NOT LIKE '%IPPF2:%';
UPDATE drugs SET related_code = CONCAT(related_code, ';IPPF2:2134040401202') WHERE related_code IS NOT NULL AND related_code LIKE '%IPPF:254245422%' AND related_code NOT LIKE '%IPPF2:%';
UPDATE codes SET related_code = CONCAT(related_code, ';IPPF2:2134040401203') WHERE related_code IS NOT NULL AND related_code LIKE '%IPPF:254245522%' AND related_code NOT LIKE '%IPPF2:%';
UPDATE drugs SET related_code = CONCAT(related_code, ';IPPF2:2134040401203') WHERE related_code IS NOT NULL AND related_code LIKE '%IPPF:254245522%' AND related_code NOT LIKE '%IPPF2:%';
UPDATE codes SET related_code = CONCAT(related_code, ';IPPF2:2135050000000') WHERE related_code IS NOT NULL AND related_code LIKE '%IPPF:254243000%' AND related_code NOT LIKE '%IPPF2:%';
UPDATE drugs SET related_code = CONCAT(related_code, ';IPPF2:2135050000000') WHERE related_code IS NOT NULL AND related_code LIKE '%IPPF:254243000%' AND related_code NOT LIKE '%IPPF2:%';
UPDATE codes SET related_code = CONCAT(related_code, ';IPPF2:2135050503000') WHERE related_code IS NOT NULL AND related_code LIKE '%IPPF:254243121%' AND related_code NOT LIKE '%IPPF2:%';
UPDATE drugs SET related_code = CONCAT(related_code, ';IPPF2:2135050503000') WHERE related_code IS NOT NULL AND related_code LIKE '%IPPF:254243121%' AND related_code NOT LIKE '%IPPF2:%';
UPDATE codes SET related_code = CONCAT(related_code, ';IPPF2:2135050503000') WHERE related_code IS NOT NULL AND related_code LIKE '%IPPF:254243221%' AND related_code NOT LIKE '%IPPF2:%';
UPDATE drugs SET related_code = CONCAT(related_code, ';IPPF2:2135050503000') WHERE related_code IS NOT NULL AND related_code LIKE '%IPPF:254243221%' AND related_code NOT LIKE '%IPPF2:%';
UPDATE codes SET related_code = CONCAT(related_code, ';IPPF2:2135050503000') WHERE related_code IS NOT NULL AND related_code LIKE '%IPPF:254243321%' AND related_code NOT LIKE '%IPPF2:%';
UPDATE drugs SET related_code = CONCAT(related_code, ';IPPF2:2135050503000') WHERE related_code IS NOT NULL AND related_code LIKE '%IPPF:254243321%' AND related_code NOT LIKE '%IPPF2:%';
UPDATE codes SET related_code = CONCAT(related_code, ';IPPF2:2135050503000') WHERE related_code IS NOT NULL AND related_code LIKE '%IPPF:254243421%' AND related_code NOT LIKE '%IPPF2:%';
UPDATE drugs SET related_code = CONCAT(related_code, ';IPPF2:2135050503000') WHERE related_code IS NOT NULL AND related_code LIKE '%IPPF:254243421%' AND related_code NOT LIKE '%IPPF2:%';
UPDATE codes SET related_code = CONCAT(related_code, ';IPPF2:2135050503000') WHERE related_code IS NOT NULL AND related_code LIKE '%IPPF:254243521%' AND related_code NOT LIKE '%IPPF2:%';
UPDATE drugs SET related_code = CONCAT(related_code, ';IPPF2:2135050503000') WHERE related_code IS NOT NULL AND related_code LIKE '%IPPF:254243521%' AND related_code NOT LIKE '%IPPF2:%';
UPDATE codes SET related_code = CONCAT(related_code, ';IPPF2:2135050503000') WHERE related_code IS NOT NULL AND related_code LIKE '%IPPF:254243999%' AND related_code NOT LIKE '%IPPF2:%';
UPDATE drugs SET related_code = CONCAT(related_code, ';IPPF2:2135050503000') WHERE related_code IS NOT NULL AND related_code LIKE '%IPPF:254243999%' AND related_code NOT LIKE '%IPPF2:%';
UPDATE codes SET related_code = CONCAT(related_code, ';IPPF2:2135050503000') WHERE related_code IS NOT NULL AND related_code LIKE '%IPPF:254244121%' AND related_code NOT LIKE '%IPPF2:%';
UPDATE drugs SET related_code = CONCAT(related_code, ';IPPF2:2135050503000') WHERE related_code IS NOT NULL AND related_code LIKE '%IPPF:254244121%' AND related_code NOT LIKE '%IPPF2:%';
UPDATE codes SET related_code = CONCAT(related_code, ';IPPF2:2135050503000') WHERE related_code IS NOT NULL AND related_code LIKE '%IPPF:254244221%' AND related_code NOT LIKE '%IPPF2:%';
UPDATE drugs SET related_code = CONCAT(related_code, ';IPPF2:2135050503000') WHERE related_code IS NOT NULL AND related_code LIKE '%IPPF:254244221%' AND related_code NOT LIKE '%IPPF2:%';
UPDATE codes SET related_code = CONCAT(related_code, ';IPPF2:2135050503000') WHERE related_code IS NOT NULL AND related_code LIKE '%IPPF:254244321%' AND related_code NOT LIKE '%IPPF2:%';
UPDATE drugs SET related_code = CONCAT(related_code, ';IPPF2:2135050503000') WHERE related_code IS NOT NULL AND related_code LIKE '%IPPF:254244321%' AND related_code NOT LIKE '%IPPF2:%';
UPDATE codes SET related_code = CONCAT(related_code, ';IPPF2:2135050503000') WHERE related_code IS NOT NULL AND related_code LIKE '%IPPF:254244421%' AND related_code NOT LIKE '%IPPF2:%';
UPDATE drugs SET related_code = CONCAT(related_code, ';IPPF2:2135050503000') WHERE related_code IS NOT NULL AND related_code LIKE '%IPPF:254244421%' AND related_code NOT LIKE '%IPPF2:%';
UPDATE codes SET related_code = CONCAT(related_code, ';IPPF2:2135050503000') WHERE related_code IS NOT NULL AND related_code LIKE '%IPPF:254244521%' AND related_code NOT LIKE '%IPPF2:%';
UPDATE drugs SET related_code = CONCAT(related_code, ';IPPF2:2135050503000') WHERE related_code IS NOT NULL AND related_code LIKE '%IPPF:254244521%' AND related_code NOT LIKE '%IPPF2:%';
UPDATE codes SET related_code = CONCAT(related_code, ';IPPF2:2135050503000') WHERE related_code IS NOT NULL AND related_code LIKE '%IPPF:254244621%' AND related_code NOT LIKE '%IPPF2:%';
UPDATE drugs SET related_code = CONCAT(related_code, ';IPPF2:2135050503000') WHERE related_code IS NOT NULL AND related_code LIKE '%IPPF:254244621%' AND related_code NOT LIKE '%IPPF2:%';
UPDATE codes SET related_code = CONCAT(related_code, ';IPPF2:2135050503000') WHERE related_code IS NOT NULL AND related_code LIKE '%IPPF:254244721%' AND related_code NOT LIKE '%IPPF2:%';
UPDATE drugs SET related_code = CONCAT(related_code, ';IPPF2:2135050503000') WHERE related_code IS NOT NULL AND related_code LIKE '%IPPF:254244721%' AND related_code NOT LIKE '%IPPF2:%';
UPDATE codes SET related_code = CONCAT(related_code, ';IPPF2:2135050503000') WHERE related_code IS NOT NULL AND related_code LIKE '%IPPF:254244999%' AND related_code NOT LIKE '%IPPF2:%';
UPDATE drugs SET related_code = CONCAT(related_code, ';IPPF2:2135050503000') WHERE related_code IS NOT NULL AND related_code LIKE '%IPPF:254244999%' AND related_code NOT LIKE '%IPPF2:%';
UPDATE codes SET related_code = CONCAT(related_code, ';IPPF2:2140000000000') WHERE related_code IS NOT NULL AND related_code LIKE '%IPPF:255250000%' AND related_code NOT LIKE '%IPPF2:%';
UPDATE drugs SET related_code = CONCAT(related_code, ';IPPF2:2140000000000') WHERE related_code IS NOT NULL AND related_code LIKE '%IPPF:255250000%' AND related_code NOT LIKE '%IPPF2:%';
UPDATE codes SET related_code = CONCAT(related_code, ';IPPF2:2141010000000') WHERE related_code IS NOT NULL AND related_code LIKE '%IPPF:255258000%' AND related_code NOT LIKE '%IPPF2:%';
UPDATE drugs SET related_code = CONCAT(related_code, ';IPPF2:2141010000000') WHERE related_code IS NOT NULL AND related_code LIKE '%IPPF:255258000%' AND related_code NOT LIKE '%IPPF2:%';
UPDATE codes SET related_code = CONCAT(related_code, ';IPPF2:2141010111101') WHERE related_code IS NOT NULL AND related_code LIKE '%IPPF:255258229%' AND related_code NOT LIKE '%IPPF2:%';
UPDATE drugs SET related_code = CONCAT(related_code, ';IPPF2:2141010111101') WHERE related_code IS NOT NULL AND related_code LIKE '%IPPF:255258229%' AND related_code NOT LIKE '%IPPF2:%';
UPDATE codes SET related_code = CONCAT(related_code, ';IPPF2:2141010112101') WHERE related_code IS NOT NULL AND related_code LIKE '%IPPF:255258329%' AND related_code NOT LIKE '%IPPF2:%';
UPDATE drugs SET related_code = CONCAT(related_code, ';IPPF2:2141010112101') WHERE related_code IS NOT NULL AND related_code LIKE '%IPPF:255258329%' AND related_code NOT LIKE '%IPPF2:%';
UPDATE codes SET related_code = CONCAT(related_code, ';IPPF2:2141010112101') WHERE related_code IS NOT NULL AND related_code LIKE '%IPPF:255258629%' AND related_code NOT LIKE '%IPPF2:%';
UPDATE drugs SET related_code = CONCAT(related_code, ';IPPF2:2141010112101') WHERE related_code IS NOT NULL AND related_code LIKE '%IPPF:255258629%' AND related_code NOT LIKE '%IPPF2:%';
UPDATE codes SET related_code = CONCAT(related_code, ';IPPF2:2141010900102') WHERE related_code IS NOT NULL AND related_code LIKE '%IPPF:255258429%' AND related_code NOT LIKE '%IPPF2:%';
UPDATE drugs SET related_code = CONCAT(related_code, ';IPPF2:2141010900102') WHERE related_code IS NOT NULL AND related_code LIKE '%IPPF:255258429%' AND related_code NOT LIKE '%IPPF2:%';
UPDATE codes SET related_code = CONCAT(related_code, ';IPPF2:2141010900999') WHERE related_code IS NOT NULL AND related_code LIKE '%IPPF:255258529%' AND related_code NOT LIKE '%IPPF2:%';
UPDATE drugs SET related_code = CONCAT(related_code, ';IPPF2:2141010900999') WHERE related_code IS NOT NULL AND related_code LIKE '%IPPF:255258529%' AND related_code NOT LIKE '%IPPF2:%';
UPDATE codes SET related_code = CONCAT(related_code, ';IPPF2:2141010900999') WHERE related_code IS NOT NULL AND related_code LIKE '%IPPF:255258129%' AND related_code NOT LIKE '%IPPF2:%';
UPDATE drugs SET related_code = CONCAT(related_code, ';IPPF2:2141010900999') WHERE related_code IS NOT NULL AND related_code LIKE '%IPPF:255258129%' AND related_code NOT LIKE '%IPPF2:%';
UPDATE codes SET related_code = CONCAT(related_code, ';IPPF2:2141010900999') WHERE related_code IS NOT NULL AND related_code LIKE '%IPPF:255258999%' AND related_code NOT LIKE '%IPPF2:%';
UPDATE drugs SET related_code = CONCAT(related_code, ';IPPF2:2141010900999') WHERE related_code IS NOT NULL AND related_code LIKE '%IPPF:255258999%' AND related_code NOT LIKE '%IPPF2:%';
UPDATE codes SET related_code = CONCAT(related_code, ';IPPF2:2142020200000') WHERE related_code IS NOT NULL AND related_code LIKE '%IPPF:255254421%' AND related_code NOT LIKE '%IPPF2:%';
UPDATE drugs SET related_code = CONCAT(related_code, ';IPPF2:2142020200000') WHERE related_code IS NOT NULL AND related_code LIKE '%IPPF:255254421%' AND related_code NOT LIKE '%IPPF2:%';
UPDATE codes SET related_code = CONCAT(related_code, ';IPPF2:2142020000800') WHERE related_code IS NOT NULL AND related_code LIKE '%IPPF:255250999%' AND related_code NOT LIKE '%IPPF2:%';
UPDATE drugs SET related_code = CONCAT(related_code, ';IPPF2:2142020000800') WHERE related_code IS NOT NULL AND related_code LIKE '%IPPF:255250999%' AND related_code NOT LIKE '%IPPF2:%';
UPDATE codes SET related_code = CONCAT(related_code, ';IPPF2:2143130000000') WHERE related_code IS NOT NULL AND related_code LIKE '%IPPF:255256000%' AND related_code NOT LIKE '%IPPF2:%';
UPDATE drugs SET related_code = CONCAT(related_code, ';IPPF2:2143130000000') WHERE related_code IS NOT NULL AND related_code LIKE '%IPPF:255256000%' AND related_code NOT LIKE '%IPPF2:%';
UPDATE codes SET related_code = CONCAT(related_code, ';IPPF2:2143130301201') WHERE related_code IS NOT NULL AND related_code LIKE '%IPPF:255256222%' AND related_code NOT LIKE '%IPPF2:%';
UPDATE drugs SET related_code = CONCAT(related_code, ';IPPF2:2143130301201') WHERE related_code IS NOT NULL AND related_code LIKE '%IPPF:255256222%' AND related_code NOT LIKE '%IPPF2:%';
UPDATE codes SET related_code = CONCAT(related_code, ';IPPF2:2143130301202') WHERE related_code IS NOT NULL AND related_code LIKE '%IPPF:255256422%' AND related_code NOT LIKE '%IPPF2:%';
UPDATE drugs SET related_code = CONCAT(related_code, ';IPPF2:2143130301202') WHERE related_code IS NOT NULL AND related_code LIKE '%IPPF:255256422%' AND related_code NOT LIKE '%IPPF2:%';
UPDATE codes SET related_code = CONCAT(related_code, ';IPPF2:2143130301999') WHERE related_code IS NOT NULL AND related_code LIKE '%IPPF:255256122%' AND related_code NOT LIKE '%IPPF2:%';
UPDATE drugs SET related_code = CONCAT(related_code, ';IPPF2:2143130301999') WHERE related_code IS NOT NULL AND related_code LIKE '%IPPF:255256122%' AND related_code NOT LIKE '%IPPF2:%';
UPDATE codes SET related_code = CONCAT(related_code, ';IPPF2:2143130301999') WHERE related_code IS NOT NULL AND related_code LIKE '%IPPF:255256322%' AND related_code NOT LIKE '%IPPF2:%';
UPDATE drugs SET related_code = CONCAT(related_code, ';IPPF2:2143130301999') WHERE related_code IS NOT NULL AND related_code LIKE '%IPPF:255256322%' AND related_code NOT LIKE '%IPPF2:%';
UPDATE codes SET related_code = CONCAT(related_code, ';IPPF2:2143130301999') WHERE related_code IS NOT NULL AND related_code LIKE '%IPPF:255256999%' AND related_code NOT LIKE '%IPPF2:%';
UPDATE drugs SET related_code = CONCAT(related_code, ';IPPF2:2143130301999') WHERE related_code IS NOT NULL AND related_code LIKE '%IPPF:255256999%' AND related_code NOT LIKE '%IPPF2:%';
UPDATE codes SET related_code = CONCAT(related_code, ';IPPF2:2143230000000') WHERE related_code IS NOT NULL AND related_code LIKE '%IPPF:255257000%' AND related_code NOT LIKE '%IPPF2:%';
UPDATE drugs SET related_code = CONCAT(related_code, ';IPPF2:2143230000000') WHERE related_code IS NOT NULL AND related_code LIKE '%IPPF:255257000%' AND related_code NOT LIKE '%IPPF2:%';
UPDATE codes SET related_code = CONCAT(related_code, ';IPPF2:2143230302401') WHERE related_code IS NOT NULL AND related_code LIKE '%IPPF:255257123%' AND related_code NOT LIKE '%IPPF2:%';
UPDATE drugs SET related_code = CONCAT(related_code, ';IPPF2:2143230302401') WHERE related_code IS NOT NULL AND related_code LIKE '%IPPF:255257123%' AND related_code NOT LIKE '%IPPF2:%';
UPDATE codes SET related_code = CONCAT(related_code, ';IPPF2:2143230302402') WHERE related_code IS NOT NULL AND related_code LIKE '%IPPF:255257223%' AND related_code NOT LIKE '%IPPF2:%';
UPDATE drugs SET related_code = CONCAT(related_code, ';IPPF2:2143230302402') WHERE related_code IS NOT NULL AND related_code LIKE '%IPPF:255257223%' AND related_code NOT LIKE '%IPPF2:%';
UPDATE codes SET related_code = CONCAT(related_code, ';IPPF2:2143230302999') WHERE related_code IS NOT NULL AND related_code LIKE '%IPPF:255257323%' AND related_code NOT LIKE '%IPPF2:%';
UPDATE drugs SET related_code = CONCAT(related_code, ';IPPF2:2143230302999') WHERE related_code IS NOT NULL AND related_code LIKE '%IPPF:255257323%' AND related_code NOT LIKE '%IPPF2:%';
UPDATE codes SET related_code = CONCAT(related_code, ';IPPF2:2143230302999') WHERE related_code IS NOT NULL AND related_code LIKE '%IPPF:255257999%' AND related_code NOT LIKE '%IPPF2:%';
UPDATE drugs SET related_code = CONCAT(related_code, ';IPPF2:2143230302999') WHERE related_code IS NOT NULL AND related_code LIKE '%IPPF:255257999%' AND related_code NOT LIKE '%IPPF2:%';
UPDATE codes SET related_code = CONCAT(related_code, ';IPPF2:2143230302999') WHERE related_code IS NOT NULL AND related_code LIKE '%IPPF:255252523%' AND related_code NOT LIKE '%IPPF2:%';
UPDATE drugs SET related_code = CONCAT(related_code, ';IPPF2:2143230302999') WHERE related_code IS NOT NULL AND related_code LIKE '%IPPF:255252523%' AND related_code NOT LIKE '%IPPF2:%';
UPDATE codes SET related_code = CONCAT(related_code, ';IPPF2:2143230302999') WHERE related_code IS NOT NULL AND related_code LIKE '%IPPF:255252623%' AND related_code NOT LIKE '%IPPF2:%';
UPDATE drugs SET related_code = CONCAT(related_code, ';IPPF2:2143230302999') WHERE related_code IS NOT NULL AND related_code LIKE '%IPPF:255252623%' AND related_code NOT LIKE '%IPPF2:%';
UPDATE codes SET related_code = CONCAT(related_code, ';IPPF2:2143230302999') WHERE related_code IS NOT NULL AND related_code LIKE '%IPPF:255252723%' AND related_code NOT LIKE '%IPPF2:%';
UPDATE drugs SET related_code = CONCAT(related_code, ';IPPF2:2143230302999') WHERE related_code IS NOT NULL AND related_code LIKE '%IPPF:255252723%' AND related_code NOT LIKE '%IPPF2:%';
UPDATE codes SET related_code = CONCAT(related_code, ';IPPF2:2143230302999') WHERE related_code IS NOT NULL AND related_code LIKE '%IPPF:255252823%' AND related_code NOT LIKE '%IPPF2:%';
UPDATE drugs SET related_code = CONCAT(related_code, ';IPPF2:2143230302999') WHERE related_code IS NOT NULL AND related_code LIKE '%IPPF:255252823%' AND related_code NOT LIKE '%IPPF2:%';
UPDATE codes SET related_code = CONCAT(related_code, ';IPPF2:2144040000000') WHERE related_code IS NOT NULL AND related_code LIKE '%IPPF:255254000%' AND related_code NOT LIKE '%IPPF2:%';
UPDATE drugs SET related_code = CONCAT(related_code, ';IPPF2:2144040000000') WHERE related_code IS NOT NULL AND related_code LIKE '%IPPF:255254000%' AND related_code NOT LIKE '%IPPF2:%';
UPDATE codes SET related_code = CONCAT(related_code, ';IPPF2:2144040402101') WHERE related_code IS NOT NULL AND related_code LIKE '%IPPF:255254621%' AND related_code NOT LIKE '%IPPF2:%';
UPDATE drugs SET related_code = CONCAT(related_code, ';IPPF2:2144040402101') WHERE related_code IS NOT NULL AND related_code LIKE '%IPPF:255254621%' AND related_code NOT LIKE '%IPPF2:%';
UPDATE codes SET related_code = CONCAT(related_code, ';IPPF2:2144040402101') WHERE related_code IS NOT NULL AND related_code LIKE '%IPPF:255254721%' AND related_code NOT LIKE '%IPPF2:%';
UPDATE drugs SET related_code = CONCAT(related_code, ';IPPF2:2144040402101') WHERE related_code IS NOT NULL AND related_code LIKE '%IPPF:255254721%' AND related_code NOT LIKE '%IPPF2:%';
UPDATE codes SET related_code = CONCAT(related_code, ';IPPF2:2144040402102') WHERE related_code IS NOT NULL AND related_code LIKE '%IPPF:255254321%' AND related_code NOT LIKE '%IPPF2:%';
UPDATE drugs SET related_code = CONCAT(related_code, ';IPPF2:2144040402102') WHERE related_code IS NOT NULL AND related_code LIKE '%IPPF:255254321%' AND related_code NOT LIKE '%IPPF2:%';
UPDATE codes SET related_code = CONCAT(related_code, ';IPPF2:2144040402102') WHERE related_code IS NOT NULL AND related_code LIKE '%IPPF:255255321%' AND related_code NOT LIKE '%IPPF2:%';
UPDATE drugs SET related_code = CONCAT(related_code, ';IPPF2:2144040402102') WHERE related_code IS NOT NULL AND related_code LIKE '%IPPF:255255321%' AND related_code NOT LIKE '%IPPF2:%';
UPDATE codes SET related_code = CONCAT(related_code, ';IPPF2:2144040402103') WHERE related_code IS NOT NULL AND related_code LIKE '%IPPF:255254521%' AND related_code NOT LIKE '%IPPF2:%';
UPDATE drugs SET related_code = CONCAT(related_code, ';IPPF2:2144040402103') WHERE related_code IS NOT NULL AND related_code LIKE '%IPPF:255254521%' AND related_code NOT LIKE '%IPPF2:%';
UPDATE codes SET related_code = CONCAT(related_code, ';IPPF2:2145050501000') WHERE related_code IS NOT NULL AND related_code LIKE '%IPPF:255252223%' AND related_code NOT LIKE '%IPPF2:%';
UPDATE drugs SET related_code = CONCAT(related_code, ';IPPF2:2145050501000') WHERE related_code IS NOT NULL AND related_code LIKE '%IPPF:255252223%' AND related_code NOT LIKE '%IPPF2:%';
UPDATE codes SET related_code = CONCAT(related_code, ';IPPF2:2145050501000') WHERE related_code IS NOT NULL AND related_code LIKE '%IPPF:255252323%' AND related_code NOT LIKE '%IPPF2:%';
UPDATE drugs SET related_code = CONCAT(related_code, ';IPPF2:2145050501000') WHERE related_code IS NOT NULL AND related_code LIKE '%IPPF:255252323%' AND related_code NOT LIKE '%IPPF2:%';
UPDATE codes SET related_code = CONCAT(related_code, ';IPPF2:2145050501000') WHERE related_code IS NOT NULL AND related_code LIKE '%IPPF:255252423%' AND related_code NOT LIKE '%IPPF2:%';
UPDATE drugs SET related_code = CONCAT(related_code, ';IPPF2:2145050501000') WHERE related_code IS NOT NULL AND related_code LIKE '%IPPF:255252423%' AND related_code NOT LIKE '%IPPF2:%';
UPDATE codes SET related_code = CONCAT(related_code, ';IPPF2:2145050501000') WHERE related_code IS NOT NULL AND related_code LIKE '%IPPF:255252999%' AND related_code NOT LIKE '%IPPF2:%';
UPDATE drugs SET related_code = CONCAT(related_code, ';IPPF2:2145050501000') WHERE related_code IS NOT NULL AND related_code LIKE '%IPPF:255252999%' AND related_code NOT LIKE '%IPPF2:%';
UPDATE codes SET related_code = CONCAT(related_code, ';IPPF2:2145050501000') WHERE related_code IS NOT NULL AND related_code LIKE '%IPPF:255253321%' AND related_code NOT LIKE '%IPPF2:%';
UPDATE drugs SET related_code = CONCAT(related_code, ';IPPF2:2145050501000') WHERE related_code IS NOT NULL AND related_code LIKE '%IPPF:255253321%' AND related_code NOT LIKE '%IPPF2:%';
UPDATE codes SET related_code = CONCAT(related_code, ';IPPF2:2145050501000') WHERE related_code IS NOT NULL AND related_code LIKE '%IPPF:255253421%' AND related_code NOT LIKE '%IPPF2:%';
UPDATE drugs SET related_code = CONCAT(related_code, ';IPPF2:2145050501000') WHERE related_code IS NOT NULL AND related_code LIKE '%IPPF:255253421%' AND related_code NOT LIKE '%IPPF2:%';
UPDATE codes SET related_code = CONCAT(related_code, ';IPPF2:2145050501000') WHERE related_code IS NOT NULL AND related_code LIKE '%IPPF:255253521%' AND related_code NOT LIKE '%IPPF2:%';
UPDATE drugs SET related_code = CONCAT(related_code, ';IPPF2:2145050501000') WHERE related_code IS NOT NULL AND related_code LIKE '%IPPF:255253521%' AND related_code NOT LIKE '%IPPF2:%';
UPDATE codes SET related_code = CONCAT(related_code, ';IPPF2:2145050501000') WHERE related_code IS NOT NULL AND related_code LIKE '%IPPF:255253999%' AND related_code NOT LIKE '%IPPF2:%';
UPDATE drugs SET related_code = CONCAT(related_code, ';IPPF2:2145050501000') WHERE related_code IS NOT NULL AND related_code LIKE '%IPPF:255253999%' AND related_code NOT LIKE '%IPPF2:%';
UPDATE codes SET related_code = CONCAT(related_code, ';IPPF2:2145050501000') WHERE related_code IS NOT NULL AND related_code LIKE '%IPPF:255253121%' AND related_code NOT LIKE '%IPPF2:%';
UPDATE drugs SET related_code = CONCAT(related_code, ';IPPF2:2145050501000') WHERE related_code IS NOT NULL AND related_code LIKE '%IPPF:255253121%' AND related_code NOT LIKE '%IPPF2:%';
UPDATE codes SET related_code = CONCAT(related_code, ';IPPF2:2145050501101') WHERE related_code IS NOT NULL AND related_code LIKE '%IPPF:255253221%' AND related_code NOT LIKE '%IPPF2:%';
UPDATE drugs SET related_code = CONCAT(related_code, ';IPPF2:2145050501101') WHERE related_code IS NOT NULL AND related_code LIKE '%IPPF:255253221%' AND related_code NOT LIKE '%IPPF2:%';
UPDATE codes SET related_code = CONCAT(related_code, ';IPPF2:2145050501102') WHERE related_code IS NOT NULL AND related_code LIKE '%IPPF:255252123%' AND related_code NOT LIKE '%IPPF2:%';
UPDATE drugs SET related_code = CONCAT(related_code, ';IPPF2:2145050501102') WHERE related_code IS NOT NULL AND related_code LIKE '%IPPF:255252123%' AND related_code NOT LIKE '%IPPF2:%';
UPDATE codes SET related_code = CONCAT(related_code, ';IPPF2:2145050502101') WHERE related_code IS NOT NULL AND related_code LIKE '%IPPF:255254221%' AND related_code NOT LIKE '%IPPF2:%';
UPDATE drugs SET related_code = CONCAT(related_code, ';IPPF2:2145050502101') WHERE related_code IS NOT NULL AND related_code LIKE '%IPPF:255254221%' AND related_code NOT LIKE '%IPPF2:%';
UPDATE codes SET related_code = CONCAT(related_code, ';IPPF2:2145050502102') WHERE related_code IS NOT NULL AND related_code LIKE '%IPPF:255254121%' AND related_code NOT LIKE '%IPPF2:%';
UPDATE drugs SET related_code = CONCAT(related_code, ';IPPF2:2145050502102') WHERE related_code IS NOT NULL AND related_code LIKE '%IPPF:255254121%' AND related_code NOT LIKE '%IPPF2:%';
UPDATE codes SET related_code = CONCAT(related_code, ';IPPF2:2145050502999') WHERE related_code IS NOT NULL AND related_code LIKE '%IPPF:255254999%' AND related_code NOT LIKE '%IPPF2:%';
UPDATE drugs SET related_code = CONCAT(related_code, ';IPPF2:2145050502999') WHERE related_code IS NOT NULL AND related_code LIKE '%IPPF:255254999%' AND related_code NOT LIKE '%IPPF2:%';
UPDATE codes SET related_code = CONCAT(related_code, ';IPPF2:2145050503000') WHERE related_code IS NOT NULL AND related_code LIKE '%IPPF:255255121%' AND related_code NOT LIKE '%IPPF2:%';
UPDATE drugs SET related_code = CONCAT(related_code, ';IPPF2:2145050503000') WHERE related_code IS NOT NULL AND related_code LIKE '%IPPF:255255121%' AND related_code NOT LIKE '%IPPF2:%';
UPDATE codes SET related_code = CONCAT(related_code, ';IPPF2:2145050503000') WHERE related_code IS NOT NULL AND related_code LIKE '%IPPF:255255221%' AND related_code NOT LIKE '%IPPF2:%';
UPDATE drugs SET related_code = CONCAT(related_code, ';IPPF2:2145050503000') WHERE related_code IS NOT NULL AND related_code LIKE '%IPPF:255255221%' AND related_code NOT LIKE '%IPPF2:%';
UPDATE codes SET related_code = CONCAT(related_code, ';IPPF2:2145050503000') WHERE related_code IS NOT NULL AND related_code LIKE '%IPPF:255255999%' AND related_code NOT LIKE '%IPPF2:%';
UPDATE drugs SET related_code = CONCAT(related_code, ';IPPF2:2145050503000') WHERE related_code IS NOT NULL AND related_code LIKE '%IPPF:255255999%' AND related_code NOT LIKE '%IPPF2:%';
UPDATE codes SET related_code = CONCAT(related_code, ';IPPF2:2145050504000') WHERE related_code IS NOT NULL AND related_code LIKE '%IPPF:255251123%' AND related_code NOT LIKE '%IPPF2:%';
UPDATE drugs SET related_code = CONCAT(related_code, ';IPPF2:2145050504000') WHERE related_code IS NOT NULL AND related_code LIKE '%IPPF:255251123%' AND related_code NOT LIKE '%IPPF2:%';
UPDATE codes SET related_code = CONCAT(related_code, ';IPPF2:2145050504000') WHERE related_code IS NOT NULL AND related_code LIKE '%IPPF:255251223%' AND related_code NOT LIKE '%IPPF2:%';
UPDATE drugs SET related_code = CONCAT(related_code, ';IPPF2:2145050504000') WHERE related_code IS NOT NULL AND related_code LIKE '%IPPF:255251223%' AND related_code NOT LIKE '%IPPF2:%';
UPDATE codes SET related_code = CONCAT(related_code, ';IPPF2:2145050504000') WHERE related_code IS NOT NULL AND related_code LIKE '%IPPF:255251323%' AND related_code NOT LIKE '%IPPF2:%';
UPDATE drugs SET related_code = CONCAT(related_code, ';IPPF2:2145050504000') WHERE related_code IS NOT NULL AND related_code LIKE '%IPPF:255251323%' AND related_code NOT LIKE '%IPPF2:%';
UPDATE codes SET related_code = CONCAT(related_code, ';IPPF2:2145050504000') WHERE related_code IS NOT NULL AND related_code LIKE '%IPPF:255251423%' AND related_code NOT LIKE '%IPPF2:%';
UPDATE drugs SET related_code = CONCAT(related_code, ';IPPF2:2145050504000') WHERE related_code IS NOT NULL AND related_code LIKE '%IPPF:255251423%' AND related_code NOT LIKE '%IPPF2:%';
UPDATE codes SET related_code = CONCAT(related_code, ';IPPF2:2145050504000') WHERE related_code IS NOT NULL AND related_code LIKE '%IPPF:255251999%' AND related_code NOT LIKE '%IPPF2:%';
UPDATE drugs SET related_code = CONCAT(related_code, ';IPPF2:2145050504000') WHERE related_code IS NOT NULL AND related_code LIKE '%IPPF:255251999%' AND related_code NOT LIKE '%IPPF2:%';
UPDATE codes SET related_code = CONCAT(related_code, ';IPPF2:2150000000000') WHERE related_code IS NOT NULL AND related_code LIKE '%IPPF:256260000%' AND related_code NOT LIKE '%IPPF2:%';
UPDATE drugs SET related_code = CONCAT(related_code, ';IPPF2:2150000000000') WHERE related_code IS NOT NULL AND related_code LIKE '%IPPF:256260000%' AND related_code NOT LIKE '%IPPF2:%';
UPDATE codes SET related_code = CONCAT(related_code, ';IPPF2:2151010131000') WHERE related_code IS NOT NULL AND related_code LIKE '%IPPF:256263129%' AND related_code NOT LIKE '%IPPF2:%';
UPDATE drugs SET related_code = CONCAT(related_code, ';IPPF2:2151010131000') WHERE related_code IS NOT NULL AND related_code LIKE '%IPPF:256263129%' AND related_code NOT LIKE '%IPPF2:%';
UPDATE codes SET related_code = CONCAT(related_code, ';IPPF2:2151010131000') WHERE related_code IS NOT NULL AND related_code LIKE '%IPPF:256263999%' AND related_code NOT LIKE '%IPPF2:%';
UPDATE drugs SET related_code = CONCAT(related_code, ';IPPF2:2151010131000') WHERE related_code IS NOT NULL AND related_code LIKE '%IPPF:256263999%' AND related_code NOT LIKE '%IPPF2:%';
UPDATE codes SET related_code = CONCAT(related_code, ';IPPF2:2151010131000') WHERE related_code IS NOT NULL AND related_code LIKE '%IPPF:256263329%' AND related_code NOT LIKE '%IPPF2:%';
UPDATE drugs SET related_code = CONCAT(related_code, ';IPPF2:2151010131000') WHERE related_code IS NOT NULL AND related_code LIKE '%IPPF:256263329%' AND related_code NOT LIKE '%IPPF2:%';
UPDATE codes SET related_code = CONCAT(related_code, ';IPPF2:2151010132000') WHERE related_code IS NOT NULL AND related_code LIKE '%IPPF:256269129%' AND related_code NOT LIKE '%IPPF2:%';
UPDATE drugs SET related_code = CONCAT(related_code, ';IPPF2:2151010132000') WHERE related_code IS NOT NULL AND related_code LIKE '%IPPF:256269129%' AND related_code NOT LIKE '%IPPF2:%';
UPDATE codes SET related_code = CONCAT(related_code, ';IPPF2:2151010132000') WHERE related_code IS NOT NULL AND related_code LIKE '%IPPF:256269229%' AND related_code NOT LIKE '%IPPF2:%';
UPDATE drugs SET related_code = CONCAT(related_code, ';IPPF2:2151010132000') WHERE related_code IS NOT NULL AND related_code LIKE '%IPPF:256269229%' AND related_code NOT LIKE '%IPPF2:%';
UPDATE codes SET related_code = CONCAT(related_code, ';IPPF2:2151010132000') WHERE related_code IS NOT NULL AND related_code LIKE '%IPPF:256269999%' AND related_code NOT LIKE '%IPPF2:%';
UPDATE drugs SET related_code = CONCAT(related_code, ';IPPF2:2151010132000') WHERE related_code IS NOT NULL AND related_code LIKE '%IPPF:256269999%' AND related_code NOT LIKE '%IPPF2:%';
UPDATE codes SET related_code = CONCAT(related_code, ';IPPF2:2151010132000') WHERE related_code IS NOT NULL AND related_code LIKE '%IPPF:256269329%' AND related_code NOT LIKE '%IPPF2:%';
UPDATE drugs SET related_code = CONCAT(related_code, ';IPPF2:2151010132000') WHERE related_code IS NOT NULL AND related_code LIKE '%IPPF:256269329%' AND related_code NOT LIKE '%IPPF2:%';
UPDATE codes SET related_code = CONCAT(related_code, ';IPPF2:2152020221000') WHERE related_code IS NOT NULL AND related_code LIKE '%IPPF:256262999%' AND related_code NOT LIKE '%IPPF2:%';
UPDATE drugs SET related_code = CONCAT(related_code, ';IPPF2:2152020221000') WHERE related_code IS NOT NULL AND related_code LIKE '%IPPF:256262999%' AND related_code NOT LIKE '%IPPF2:%';
UPDATE codes SET related_code = CONCAT(related_code, ';IPPF2:2152020222000') WHERE related_code IS NOT NULL AND related_code LIKE '%IPPF:256268120%' AND related_code NOT LIKE '%IPPF2:%';
UPDATE drugs SET related_code = CONCAT(related_code, ';IPPF2:2152020222000') WHERE related_code IS NOT NULL AND related_code LIKE '%IPPF:256268120%' AND related_code NOT LIKE '%IPPF2:%';
UPDATE codes SET related_code = CONCAT(related_code, ';IPPF2:2152020222000') WHERE related_code IS NOT NULL AND related_code LIKE '%IPPF:256268999%' AND related_code NOT LIKE '%IPPF2:%';
UPDATE drugs SET related_code = CONCAT(related_code, ';IPPF2:2152020222000') WHERE related_code IS NOT NULL AND related_code LIKE '%IPPF:256268999%' AND related_code NOT LIKE '%IPPF2:%';
UPDATE codes SET related_code = CONCAT(related_code, ';IPPF2:2152020000800') WHERE related_code IS NOT NULL AND related_code LIKE '%IPPF:256260999%' AND related_code NOT LIKE '%IPPF2:%';
UPDATE drugs SET related_code = CONCAT(related_code, ';IPPF2:2152020000800') WHERE related_code IS NOT NULL AND related_code LIKE '%IPPF:256260999%' AND related_code NOT LIKE '%IPPF2:%';
UPDATE codes SET related_code = CONCAT(related_code, ';IPPF2:2153130301401') WHERE related_code IS NOT NULL AND related_code LIKE '%IPPF:256267123%' AND related_code NOT LIKE '%IPPF2:%';
UPDATE drugs SET related_code = CONCAT(related_code, ';IPPF2:2153130301401') WHERE related_code IS NOT NULL AND related_code LIKE '%IPPF:256267123%' AND related_code NOT LIKE '%IPPF2:%';
UPDATE codes SET related_code = CONCAT(related_code, ';IPPF2:2153130301402') WHERE related_code IS NOT NULL AND related_code LIKE '%IPPF:256267323%' AND related_code NOT LIKE '%IPPF2:%';
UPDATE drugs SET related_code = CONCAT(related_code, ';IPPF2:2153130301402') WHERE related_code IS NOT NULL AND related_code LIKE '%IPPF:256267323%' AND related_code NOT LIKE '%IPPF2:%';
UPDATE codes SET related_code = CONCAT(related_code, ';IPPF2:2153230302501') WHERE related_code IS NOT NULL AND related_code LIKE '%IPPF:256267223%' AND related_code NOT LIKE '%IPPF2:%';
UPDATE drugs SET related_code = CONCAT(related_code, ';IPPF2:2153230302501') WHERE related_code IS NOT NULL AND related_code LIKE '%IPPF:256267223%' AND related_code NOT LIKE '%IPPF2:%';
UPDATE codes SET related_code = CONCAT(related_code, ';IPPF2:2153230302800') WHERE related_code IS NOT NULL AND related_code LIKE '%IPPF:256267999%' AND related_code NOT LIKE '%IPPF2:%';
UPDATE drugs SET related_code = CONCAT(related_code, ';IPPF2:2153230302800') WHERE related_code IS NOT NULL AND related_code LIKE '%IPPF:256267999%' AND related_code NOT LIKE '%IPPF2:%';
UPDATE codes SET related_code = CONCAT(related_code, ';IPPF2:2154040401301') WHERE related_code IS NOT NULL AND related_code LIKE '%IPPF:256262422%' AND related_code NOT LIKE '%IPPF2:%';
UPDATE drugs SET related_code = CONCAT(related_code, ';IPPF2:2154040401301') WHERE related_code IS NOT NULL AND related_code LIKE '%IPPF:256262422%' AND related_code NOT LIKE '%IPPF2:%';
UPDATE codes SET related_code = CONCAT(related_code, ';IPPF2:2155050501201') WHERE related_code IS NOT NULL AND related_code LIKE '%IPPF:256261121%' AND related_code NOT LIKE '%IPPF2:%';
UPDATE drugs SET related_code = CONCAT(related_code, ';IPPF2:2155050501201') WHERE related_code IS NOT NULL AND related_code LIKE '%IPPF:256261121%' AND related_code NOT LIKE '%IPPF2:%';
UPDATE codes SET related_code = CONCAT(related_code, ';IPPF2:2155050501201') WHERE related_code IS NOT NULL AND related_code LIKE '%IPPF:256261221%' AND related_code NOT LIKE '%IPPF2:%';
UPDATE drugs SET related_code = CONCAT(related_code, ';IPPF2:2155050501201') WHERE related_code IS NOT NULL AND related_code LIKE '%IPPF:256261221%' AND related_code NOT LIKE '%IPPF2:%';
UPDATE codes SET related_code = CONCAT(related_code, ';IPPF2:2152050000800') WHERE related_code IS NOT NULL AND related_code LIKE '%IPPF:256261999%' AND related_code NOT LIKE '%IPPF2:%';
UPDATE drugs SET related_code = CONCAT(related_code, ';IPPF2:2152050000800') WHERE related_code IS NOT NULL AND related_code LIKE '%IPPF:256261999%' AND related_code NOT LIKE '%IPPF2:%';
UPDATE codes SET related_code = CONCAT(related_code, ';IPPF2:2155050502201') WHERE related_code IS NOT NULL AND related_code LIKE '%IPPF:256261321%' AND related_code NOT LIKE '%IPPF2:%';
UPDATE drugs SET related_code = CONCAT(related_code, ';IPPF2:2155050502201') WHERE related_code IS NOT NULL AND related_code LIKE '%IPPF:256261321%' AND related_code NOT LIKE '%IPPF2:%';
UPDATE codes SET related_code = CONCAT(related_code, ';IPPF2:2155050502201') WHERE related_code IS NOT NULL AND related_code LIKE '%IPPF:256262121%' AND related_code NOT LIKE '%IPPF2:%';
UPDATE drugs SET related_code = CONCAT(related_code, ';IPPF2:2155050502201') WHERE related_code IS NOT NULL AND related_code LIKE '%IPPF:256262121%' AND related_code NOT LIKE '%IPPF2:%';
UPDATE codes SET related_code = CONCAT(related_code, ';IPPF2:2155050502201') WHERE related_code IS NOT NULL AND related_code LIKE '%IPPF:256262221%' AND related_code NOT LIKE '%IPPF2:%';
UPDATE drugs SET related_code = CONCAT(related_code, ';IPPF2:2155050502201') WHERE related_code IS NOT NULL AND related_code LIKE '%IPPF:256262221%' AND related_code NOT LIKE '%IPPF2:%';
UPDATE codes SET related_code = CONCAT(related_code, ';IPPF2:2155050503301') WHERE related_code IS NOT NULL AND related_code LIKE '%IPPF:256264121%' AND related_code NOT LIKE '%IPPF2:%';
UPDATE drugs SET related_code = CONCAT(related_code, ';IPPF2:2155050503301') WHERE related_code IS NOT NULL AND related_code LIKE '%IPPF:256264121%' AND related_code NOT LIKE '%IPPF2:%';
UPDATE codes SET related_code = CONCAT(related_code, ';IPPF2:2155050503301') WHERE related_code IS NOT NULL AND related_code LIKE '%IPPF:256264221%' AND related_code NOT LIKE '%IPPF2:%';
UPDATE drugs SET related_code = CONCAT(related_code, ';IPPF2:2155050503301') WHERE related_code IS NOT NULL AND related_code LIKE '%IPPF:256264221%' AND related_code NOT LIKE '%IPPF2:%';
UPDATE codes SET related_code = CONCAT(related_code, ';IPPF2:2155050503301') WHERE related_code IS NOT NULL AND related_code LIKE '%IPPF:256264999%' AND related_code NOT LIKE '%IPPF2:%';
UPDATE drugs SET related_code = CONCAT(related_code, ';IPPF2:2155050503301') WHERE related_code IS NOT NULL AND related_code LIKE '%IPPF:256264999%' AND related_code NOT LIKE '%IPPF2:%';
UPDATE codes SET related_code = CONCAT(related_code, ';IPPF2:2155050503401') WHERE related_code IS NOT NULL AND related_code LIKE '%IPPF:256261421%' AND related_code NOT LIKE '%IPPF2:%';
UPDATE drugs SET related_code = CONCAT(related_code, ';IPPF2:2155050503401') WHERE related_code IS NOT NULL AND related_code LIKE '%IPPF:256261421%' AND related_code NOT LIKE '%IPPF2:%';
UPDATE codes SET related_code = CONCAT(related_code, ';IPPF2:2155050503401') WHERE related_code IS NOT NULL AND related_code LIKE '%IPPF:256265121%' AND related_code NOT LIKE '%IPPF2:%';
UPDATE drugs SET related_code = CONCAT(related_code, ';IPPF2:2155050503401') WHERE related_code IS NOT NULL AND related_code LIKE '%IPPF:256265121%' AND related_code NOT LIKE '%IPPF2:%';
UPDATE codes SET related_code = CONCAT(related_code, ';IPPF2:2155050503401') WHERE related_code IS NOT NULL AND related_code LIKE '%IPPF:256265221%' AND related_code NOT LIKE '%IPPF2:%';
UPDATE drugs SET related_code = CONCAT(related_code, ';IPPF2:2155050503401') WHERE related_code IS NOT NULL AND related_code LIKE '%IPPF:256265221%' AND related_code NOT LIKE '%IPPF2:%';
UPDATE codes SET related_code = CONCAT(related_code, ';IPPF2:2155050503401') WHERE related_code IS NOT NULL AND related_code LIKE '%IPPF:256265321%' AND related_code NOT LIKE '%IPPF2:%';
UPDATE drugs SET related_code = CONCAT(related_code, ';IPPF2:2155050503401') WHERE related_code IS NOT NULL AND related_code LIKE '%IPPF:256265321%' AND related_code NOT LIKE '%IPPF2:%';
UPDATE codes SET related_code = CONCAT(related_code, ';IPPF2:2155050503401') WHERE related_code IS NOT NULL AND related_code LIKE '%IPPF:256265421%' AND related_code NOT LIKE '%IPPF2:%';
UPDATE drugs SET related_code = CONCAT(related_code, ';IPPF2:2155050503401') WHERE related_code IS NOT NULL AND related_code LIKE '%IPPF:256265421%' AND related_code NOT LIKE '%IPPF2:%';
UPDATE codes SET related_code = CONCAT(related_code, ';IPPF2:2155050503401') WHERE related_code IS NOT NULL AND related_code LIKE '%IPPF:256265521%' AND related_code NOT LIKE '%IPPF2:%';
UPDATE drugs SET related_code = CONCAT(related_code, ';IPPF2:2155050503401') WHERE related_code IS NOT NULL AND related_code LIKE '%IPPF:256265521%' AND related_code NOT LIKE '%IPPF2:%';
UPDATE codes SET related_code = CONCAT(related_code, ';IPPF2:2155050503401') WHERE related_code IS NOT NULL AND related_code LIKE '%IPPF:256265621%' AND related_code NOT LIKE '%IPPF2:%';
UPDATE drugs SET related_code = CONCAT(related_code, ';IPPF2:2155050503401') WHERE related_code IS NOT NULL AND related_code LIKE '%IPPF:256265621%' AND related_code NOT LIKE '%IPPF2:%';
UPDATE codes SET related_code = CONCAT(related_code, ';IPPF2:2155050503401') WHERE related_code IS NOT NULL AND related_code LIKE '%IPPF:256265721%' AND related_code NOT LIKE '%IPPF2:%';
UPDATE drugs SET related_code = CONCAT(related_code, ';IPPF2:2155050503401') WHERE related_code IS NOT NULL AND related_code LIKE '%IPPF:256265721%' AND related_code NOT LIKE '%IPPF2:%';
UPDATE codes SET related_code = CONCAT(related_code, ';IPPF2:2155050503401') WHERE related_code IS NOT NULL AND related_code LIKE '%IPPF:256265821%' AND related_code NOT LIKE '%IPPF2:%';
UPDATE drugs SET related_code = CONCAT(related_code, ';IPPF2:2155050503401') WHERE related_code IS NOT NULL AND related_code LIKE '%IPPF:256265821%' AND related_code NOT LIKE '%IPPF2:%';
UPDATE codes SET related_code = CONCAT(related_code, ';IPPF2:2155050503401') WHERE related_code IS NOT NULL AND related_code LIKE '%IPPF:256265999%' AND related_code NOT LIKE '%IPPF2:%';
UPDATE drugs SET related_code = CONCAT(related_code, ';IPPF2:2155050503401') WHERE related_code IS NOT NULL AND related_code LIKE '%IPPF:256265999%' AND related_code NOT LIKE '%IPPF2:%';
UPDATE codes SET related_code = CONCAT(related_code, ';IPPF2:2160000000000') WHERE related_code IS NOT NULL AND related_code LIKE '%IPPF:257270000%' AND related_code NOT LIKE '%IPPF2:%';
UPDATE drugs SET related_code = CONCAT(related_code, ';IPPF2:2160000000000') WHERE related_code IS NOT NULL AND related_code LIKE '%IPPF:257270000%' AND related_code NOT LIKE '%IPPF2:%';
UPDATE codes SET related_code = CONCAT(related_code, ';IPPF2:2162020200000') WHERE related_code IS NOT NULL AND related_code LIKE '%IPPF:257270999%' AND related_code NOT LIKE '%IPPF2:%';
UPDATE drugs SET related_code = CONCAT(related_code, ';IPPF2:2162020200000') WHERE related_code IS NOT NULL AND related_code LIKE '%IPPF:257270999%' AND related_code NOT LIKE '%IPPF2:%';
UPDATE codes SET related_code = CONCAT(related_code, ';IPPF2:2163230000000') WHERE related_code IS NOT NULL AND related_code LIKE '%IPPF:257274000%' AND related_code NOT LIKE '%IPPF2:%';
UPDATE drugs SET related_code = CONCAT(related_code, ';IPPF2:2163230000000') WHERE related_code IS NOT NULL AND related_code LIKE '%IPPF:257274000%' AND related_code NOT LIKE '%IPPF2:%';
UPDATE codes SET related_code = CONCAT(related_code, ';IPPF2:2163230302601') WHERE related_code IS NOT NULL AND related_code LIKE '%IPPF:257274223%' AND related_code NOT LIKE '%IPPF2:%';
UPDATE drugs SET related_code = CONCAT(related_code, ';IPPF2:2163230302601') WHERE related_code IS NOT NULL AND related_code LIKE '%IPPF:257274223%' AND related_code NOT LIKE '%IPPF2:%';
UPDATE codes SET related_code = CONCAT(related_code, ';IPPF2:2163230302999') WHERE related_code IS NOT NULL AND related_code LIKE '%IPPF:257274323%' AND related_code NOT LIKE '%IPPF2:%';
UPDATE drugs SET related_code = CONCAT(related_code, ';IPPF2:2163230302999') WHERE related_code IS NOT NULL AND related_code LIKE '%IPPF:257274323%' AND related_code NOT LIKE '%IPPF2:%';
UPDATE codes SET related_code = CONCAT(related_code, ';IPPF2:2163230302999') WHERE related_code IS NOT NULL AND related_code LIKE '%IPPF:257274999%' AND related_code NOT LIKE '%IPPF2:%';
UPDATE drugs SET related_code = CONCAT(related_code, ';IPPF2:2163230302999') WHERE related_code IS NOT NULL AND related_code LIKE '%IPPF:257274999%' AND related_code NOT LIKE '%IPPF2:%';
UPDATE codes SET related_code = CONCAT(related_code, ';IPPF2:2164040402201') WHERE related_code IS NOT NULL AND related_code LIKE '%IPPF:257273221%' AND related_code NOT LIKE '%IPPF2:%';
UPDATE drugs SET related_code = CONCAT(related_code, ';IPPF2:2164040402201') WHERE related_code IS NOT NULL AND related_code LIKE '%IPPF:257273221%' AND related_code NOT LIKE '%IPPF2:%';
UPDATE codes SET related_code = CONCAT(related_code, ';IPPF2:2165050501000') WHERE related_code IS NOT NULL AND related_code LIKE '%IPPF:257271123%' AND related_code NOT LIKE '%IPPF2:%';
UPDATE drugs SET related_code = CONCAT(related_code, ';IPPF2:2165050501000') WHERE related_code IS NOT NULL AND related_code LIKE '%IPPF:257271123%' AND related_code NOT LIKE '%IPPF2:%';
UPDATE codes SET related_code = CONCAT(related_code, ';IPPF2:2165050501000') WHERE related_code IS NOT NULL AND related_code LIKE '%IPPF:257271223%' AND related_code NOT LIKE '%IPPF2:%';
UPDATE drugs SET related_code = CONCAT(related_code, ';IPPF2:2165050501000') WHERE related_code IS NOT NULL AND related_code LIKE '%IPPF:257271223%' AND related_code NOT LIKE '%IPPF2:%';
UPDATE codes SET related_code = CONCAT(related_code, ';IPPF2:2165050501000') WHERE related_code IS NOT NULL AND related_code LIKE '%IPPF:257271999%' AND related_code NOT LIKE '%IPPF2:%';
UPDATE drugs SET related_code = CONCAT(related_code, ';IPPF2:2165050501000') WHERE related_code IS NOT NULL AND related_code LIKE '%IPPF:257271999%' AND related_code NOT LIKE '%IPPF2:%';
UPDATE codes SET related_code = CONCAT(related_code, ';IPPF2:2165050501000') WHERE related_code IS NOT NULL AND related_code LIKE '%IPPF:257272121%' AND related_code NOT LIKE '%IPPF2:%';
UPDATE drugs SET related_code = CONCAT(related_code, ';IPPF2:2165050501000') WHERE related_code IS NOT NULL AND related_code LIKE '%IPPF:257272121%' AND related_code NOT LIKE '%IPPF2:%';
UPDATE codes SET related_code = CONCAT(related_code, ';IPPF2:2165050501000') WHERE related_code IS NOT NULL AND related_code LIKE '%IPPF:257272999%' AND related_code NOT LIKE '%IPPF2:%';
UPDATE drugs SET related_code = CONCAT(related_code, ';IPPF2:2165050501000') WHERE related_code IS NOT NULL AND related_code LIKE '%IPPF:257272999%' AND related_code NOT LIKE '%IPPF2:%';
UPDATE codes SET related_code = CONCAT(related_code, ';IPPF2:2165050501000') WHERE related_code IS NOT NULL AND related_code LIKE '%IPPF:257273321%' AND related_code NOT LIKE '%IPPF2:%';
UPDATE drugs SET related_code = CONCAT(related_code, ';IPPF2:2165050501000') WHERE related_code IS NOT NULL AND related_code LIKE '%IPPF:257273321%' AND related_code NOT LIKE '%IPPF2:%';
UPDATE codes SET related_code = CONCAT(related_code, ';IPPF2:2165050502000') WHERE related_code IS NOT NULL AND related_code LIKE '%IPPF:257273121%' AND related_code NOT LIKE '%IPPF2:%';
UPDATE drugs SET related_code = CONCAT(related_code, ';IPPF2:2165050502000') WHERE related_code IS NOT NULL AND related_code LIKE '%IPPF:257273121%' AND related_code NOT LIKE '%IPPF2:%';
UPDATE codes SET related_code = CONCAT(related_code, ';IPPF2:2165050504000') WHERE related_code IS NOT NULL AND related_code LIKE '%IPPF:257274123%' AND related_code NOT LIKE '%IPPF2:%';
UPDATE drugs SET related_code = CONCAT(related_code, ';IPPF2:2165050504000') WHERE related_code IS NOT NULL AND related_code LIKE '%IPPF:257274123%' AND related_code NOT LIKE '%IPPF2:%';
UPDATE codes SET related_code = CONCAT(related_code, ';IPPF2:2165050000800') WHERE related_code IS NOT NULL AND related_code LIKE '%IPPF:257273421%' AND related_code NOT LIKE '%IPPF2:%';
UPDATE drugs SET related_code = CONCAT(related_code, ';IPPF2:2165050000800') WHERE related_code IS NOT NULL AND related_code LIKE '%IPPF:257273421%' AND related_code NOT LIKE '%IPPF2:%';
UPDATE codes SET related_code = CONCAT(related_code, ';IPPF2:2165050000800') WHERE related_code IS NOT NULL AND related_code LIKE '%IPPF:257273999%' AND related_code NOT LIKE '%IPPF2:%';
UPDATE drugs SET related_code = CONCAT(related_code, ';IPPF2:2165050000800') WHERE related_code IS NOT NULL AND related_code LIKE '%IPPF:257273999%' AND related_code NOT LIKE '%IPPF2:%';
UPDATE codes SET related_code = CONCAT(related_code, ';IPPF2:2170000000000') WHERE related_code IS NOT NULL AND related_code LIKE '%IPPF:258280000%' AND related_code NOT LIKE '%IPPF2:%';
UPDATE drugs SET related_code = CONCAT(related_code, ';IPPF2:2170000000000') WHERE related_code IS NOT NULL AND related_code LIKE '%IPPF:258280000%' AND related_code NOT LIKE '%IPPF2:%';
UPDATE codes SET related_code = CONCAT(related_code, ';IPPF2:2171010000000') WHERE related_code IS NOT NULL AND related_code LIKE '%IPPF:258289000%' AND related_code NOT LIKE '%IPPF2:%';
UPDATE drugs SET related_code = CONCAT(related_code, ';IPPF2:2171010000000') WHERE related_code IS NOT NULL AND related_code LIKE '%IPPF:258289000%' AND related_code NOT LIKE '%IPPF2:%';
UPDATE codes SET related_code = CONCAT(related_code, ';IPPF2:2171110000000') WHERE related_code IS NOT NULL AND related_code LIKE '%IPPF:258289129%' AND related_code NOT LIKE '%IPPF2:%';
UPDATE drugs SET related_code = CONCAT(related_code, ';IPPF2:2171110000000') WHERE related_code IS NOT NULL AND related_code LIKE '%IPPF:258289129%' AND related_code NOT LIKE '%IPPF2:%';
UPDATE codes SET related_code = CONCAT(related_code, ';IPPF2:2171110000000') WHERE related_code IS NOT NULL AND related_code LIKE '%IPPF:258289999%' AND related_code NOT LIKE '%IPPF2:%';
UPDATE drugs SET related_code = CONCAT(related_code, ';IPPF2:2171110000000') WHERE related_code IS NOT NULL AND related_code LIKE '%IPPF:258289999%' AND related_code NOT LIKE '%IPPF2:%';
UPDATE codes SET related_code = CONCAT(related_code, ';IPPF2:2171110000800') WHERE related_code IS NOT NULL AND related_code LIKE '%IPPF:258280999%' AND related_code NOT LIKE '%IPPF2:%';
UPDATE drugs SET related_code = CONCAT(related_code, ';IPPF2:2171110000800') WHERE related_code IS NOT NULL AND related_code LIKE '%IPPF:258280999%' AND related_code NOT LIKE '%IPPF2:%';
UPDATE codes SET related_code = CONCAT(related_code, ';IPPF2:2172020000000') WHERE related_code IS NOT NULL AND related_code LIKE '%IPPF:258288000%' AND related_code NOT LIKE '%IPPF2:%';
UPDATE drugs SET related_code = CONCAT(related_code, ';IPPF2:2172020000000') WHERE related_code IS NOT NULL AND related_code LIKE '%IPPF:258288000%' AND related_code NOT LIKE '%IPPF2:%';
UPDATE codes SET related_code = CONCAT(related_code, ';IPPF2:2172120000000') WHERE related_code IS NOT NULL AND related_code LIKE '%IPPF:258288120%' AND related_code NOT LIKE '%IPPF2:%';
UPDATE drugs SET related_code = CONCAT(related_code, ';IPPF2:2172120000000') WHERE related_code IS NOT NULL AND related_code LIKE '%IPPF:258288120%' AND related_code NOT LIKE '%IPPF2:%';
UPDATE codes SET related_code = CONCAT(related_code, ';IPPF2:2172120000000') WHERE related_code IS NOT NULL AND related_code LIKE '%IPPF:258288999%' AND related_code NOT LIKE '%IPPF2:%';
UPDATE drugs SET related_code = CONCAT(related_code, ';IPPF2:2172120000000') WHERE related_code IS NOT NULL AND related_code LIKE '%IPPF:258288999%' AND related_code NOT LIKE '%IPPF2:%';
UPDATE codes SET related_code = CONCAT(related_code, ';IPPF2:2173130000000') WHERE related_code IS NOT NULL AND related_code LIKE '%IPPF:258286000%' AND related_code NOT LIKE '%IPPF2:%';
UPDATE drugs SET related_code = CONCAT(related_code, ';IPPF2:2173130000000') WHERE related_code IS NOT NULL AND related_code LIKE '%IPPF:258286000%' AND related_code NOT LIKE '%IPPF2:%';
UPDATE codes SET related_code = CONCAT(related_code, ';IPPF2:2173130301203') WHERE related_code IS NOT NULL AND related_code LIKE '%IPPF:258286122%' AND related_code NOT LIKE '%IPPF2:%';
UPDATE drugs SET related_code = CONCAT(related_code, ';IPPF2:2173130301203') WHERE related_code IS NOT NULL AND related_code LIKE '%IPPF:258286122%' AND related_code NOT LIKE '%IPPF2:%';
UPDATE codes SET related_code = CONCAT(related_code, ';IPPF2:2173130301403') WHERE related_code IS NOT NULL AND related_code LIKE '%IPPF:258286222%' AND related_code NOT LIKE '%IPPF2:%';
UPDATE drugs SET related_code = CONCAT(related_code, ';IPPF2:2173130301403') WHERE related_code IS NOT NULL AND related_code LIKE '%IPPF:258286222%' AND related_code NOT LIKE '%IPPF2:%';
UPDATE codes SET related_code = CONCAT(related_code, ';IPPF2:2173130301403') WHERE related_code IS NOT NULL AND related_code LIKE '%IPPF:258286322%' AND related_code NOT LIKE '%IPPF2:%';
UPDATE drugs SET related_code = CONCAT(related_code, ';IPPF2:2173130301403') WHERE related_code IS NOT NULL AND related_code LIKE '%IPPF:258286322%' AND related_code NOT LIKE '%IPPF2:%';
UPDATE codes SET related_code = CONCAT(related_code, ';IPPF2:2173130301403') WHERE related_code IS NOT NULL AND related_code LIKE '%IPPF:258286422%' AND related_code NOT LIKE '%IPPF2:%';
UPDATE drugs SET related_code = CONCAT(related_code, ';IPPF2:2173130301403') WHERE related_code IS NOT NULL AND related_code LIKE '%IPPF:258286422%' AND related_code NOT LIKE '%IPPF2:%';
UPDATE codes SET related_code = CONCAT(related_code, ';IPPF2:2173130301403') WHERE related_code IS NOT NULL AND related_code LIKE '%IPPF:258286522%' AND related_code NOT LIKE '%IPPF2:%';
UPDATE drugs SET related_code = CONCAT(related_code, ';IPPF2:2173130301403') WHERE related_code IS NOT NULL AND related_code LIKE '%IPPF:258286522%' AND related_code NOT LIKE '%IPPF2:%';
UPDATE codes SET related_code = CONCAT(related_code, ';IPPF2:2173130301403') WHERE related_code IS NOT NULL AND related_code LIKE '%IPPF:258286622%' AND related_code NOT LIKE '%IPPF2:%';
UPDATE drugs SET related_code = CONCAT(related_code, ';IPPF2:2173130301403') WHERE related_code IS NOT NULL AND related_code LIKE '%IPPF:258286622%' AND related_code NOT LIKE '%IPPF2:%';
UPDATE codes SET related_code = CONCAT(related_code, ';IPPF2:2173130301403') WHERE related_code IS NOT NULL AND related_code LIKE '%IPPF:258286722%' AND related_code NOT LIKE '%IPPF2:%';
UPDATE drugs SET related_code = CONCAT(related_code, ';IPPF2:2173130301403') WHERE related_code IS NOT NULL AND related_code LIKE '%IPPF:258286722%' AND related_code NOT LIKE '%IPPF2:%';
UPDATE codes SET related_code = CONCAT(related_code, ';IPPF2:2173130301800') WHERE related_code IS NOT NULL AND related_code LIKE '%IPPF:258286999%' AND related_code NOT LIKE '%IPPF2:%';
UPDATE drugs SET related_code = CONCAT(related_code, ';IPPF2:2173130301800') WHERE related_code IS NOT NULL AND related_code LIKE '%IPPF:258286999%' AND related_code NOT LIKE '%IPPF2:%';
UPDATE codes SET related_code = CONCAT(related_code, ';IPPF2:2174040501000') WHERE related_code IS NOT NULL AND related_code LIKE '%IPPF:258282123%' AND related_code NOT LIKE '%IPPF2:%';
UPDATE drugs SET related_code = CONCAT(related_code, ';IPPF2:2174040501000') WHERE related_code IS NOT NULL AND related_code LIKE '%IPPF:258282123%' AND related_code NOT LIKE '%IPPF2:%';
UPDATE codes SET related_code = CONCAT(related_code, ';IPPF2:2174040501000') WHERE related_code IS NOT NULL AND related_code LIKE '%IPPF:258282223%' AND related_code NOT LIKE '%IPPF2:%';
UPDATE drugs SET related_code = CONCAT(related_code, ';IPPF2:2174040501000') WHERE related_code IS NOT NULL AND related_code LIKE '%IPPF:258282223%' AND related_code NOT LIKE '%IPPF2:%';
UPDATE codes SET related_code = CONCAT(related_code, ';IPPF2:2174040501000') WHERE related_code IS NOT NULL AND related_code LIKE '%IPPF:258282999%' AND related_code NOT LIKE '%IPPF2:%';
UPDATE drugs SET related_code = CONCAT(related_code, ';IPPF2:2174040501000') WHERE related_code IS NOT NULL AND related_code LIKE '%IPPF:258282999%' AND related_code NOT LIKE '%IPPF2:%';
UPDATE codes SET related_code = CONCAT(related_code, ';IPPF2:2174040501000') WHERE related_code IS NOT NULL AND related_code LIKE '%IPPF:258283121%' AND related_code NOT LIKE '%IPPF2:%';
UPDATE drugs SET related_code = CONCAT(related_code, ';IPPF2:2174040501000') WHERE related_code IS NOT NULL AND related_code LIKE '%IPPF:258283121%' AND related_code NOT LIKE '%IPPF2:%';
UPDATE codes SET related_code = CONCAT(related_code, ';IPPF2:2174040501000') WHERE related_code IS NOT NULL AND related_code LIKE '%IPPF:258283221%' AND related_code NOT LIKE '%IPPF2:%';
UPDATE drugs SET related_code = CONCAT(related_code, ';IPPF2:2174040501000') WHERE related_code IS NOT NULL AND related_code LIKE '%IPPF:258283221%' AND related_code NOT LIKE '%IPPF2:%';
UPDATE codes SET related_code = CONCAT(related_code, ';IPPF2:2174040501000') WHERE related_code IS NOT NULL AND related_code LIKE '%IPPF:258283321%' AND related_code NOT LIKE '%IPPF2:%';
UPDATE drugs SET related_code = CONCAT(related_code, ';IPPF2:2174040501000') WHERE related_code IS NOT NULL AND related_code LIKE '%IPPF:258283321%' AND related_code NOT LIKE '%IPPF2:%';
UPDATE codes SET related_code = CONCAT(related_code, ';IPPF2:2174040501000') WHERE related_code IS NOT NULL AND related_code LIKE '%IPPF:258283999%' AND related_code NOT LIKE '%IPPF2:%';
UPDATE drugs SET related_code = CONCAT(related_code, ';IPPF2:2174040501000') WHERE related_code IS NOT NULL AND related_code LIKE '%IPPF:258283999%' AND related_code NOT LIKE '%IPPF2:%';
UPDATE codes SET related_code = CONCAT(related_code, ';IPPF2:2174040503000') WHERE related_code IS NOT NULL AND related_code LIKE '%IPPF:258284121%' AND related_code NOT LIKE '%IPPF2:%';
UPDATE drugs SET related_code = CONCAT(related_code, ';IPPF2:2174040503000') WHERE related_code IS NOT NULL AND related_code LIKE '%IPPF:258284121%' AND related_code NOT LIKE '%IPPF2:%';
UPDATE codes SET related_code = CONCAT(related_code, ';IPPF2:2174040503000') WHERE related_code IS NOT NULL AND related_code LIKE '%IPPF:258284221%' AND related_code NOT LIKE '%IPPF2:%';
UPDATE drugs SET related_code = CONCAT(related_code, ';IPPF2:2174040503000') WHERE related_code IS NOT NULL AND related_code LIKE '%IPPF:258284221%' AND related_code NOT LIKE '%IPPF2:%';
UPDATE codes SET related_code = CONCAT(related_code, ';IPPF2:2174040503000') WHERE related_code IS NOT NULL AND related_code LIKE '%IPPF:258284321%' AND related_code NOT LIKE '%IPPF2:%';
UPDATE drugs SET related_code = CONCAT(related_code, ';IPPF2:2174040503000') WHERE related_code IS NOT NULL AND related_code LIKE '%IPPF:258284321%' AND related_code NOT LIKE '%IPPF2:%';
UPDATE codes SET related_code = CONCAT(related_code, ';IPPF2:2174040503000') WHERE related_code IS NOT NULL AND related_code LIKE '%IPPF:258284421%' AND related_code NOT LIKE '%IPPF2:%';
UPDATE drugs SET related_code = CONCAT(related_code, ';IPPF2:2174040503000') WHERE related_code IS NOT NULL AND related_code LIKE '%IPPF:258284421%' AND related_code NOT LIKE '%IPPF2:%';
UPDATE codes SET related_code = CONCAT(related_code, ';IPPF2:2174040503000') WHERE related_code IS NOT NULL AND related_code LIKE '%IPPF:258284521%' AND related_code NOT LIKE '%IPPF2:%';
UPDATE drugs SET related_code = CONCAT(related_code, ';IPPF2:2174040503000') WHERE related_code IS NOT NULL AND related_code LIKE '%IPPF:258284521%' AND related_code NOT LIKE '%IPPF2:%';
UPDATE codes SET related_code = CONCAT(related_code, ';IPPF2:2174040503000') WHERE related_code IS NOT NULL AND related_code LIKE '%IPPF:258284621%' AND related_code NOT LIKE '%IPPF2:%';
UPDATE drugs SET related_code = CONCAT(related_code, ';IPPF2:2174040503000') WHERE related_code IS NOT NULL AND related_code LIKE '%IPPF:258284621%' AND related_code NOT LIKE '%IPPF2:%';
UPDATE codes SET related_code = CONCAT(related_code, ';IPPF2:2174040503000') WHERE related_code IS NOT NULL AND related_code LIKE '%IPPF:258284721%' AND related_code NOT LIKE '%IPPF2:%';
UPDATE drugs SET related_code = CONCAT(related_code, ';IPPF2:2174040503000') WHERE related_code IS NOT NULL AND related_code LIKE '%IPPF:258284721%' AND related_code NOT LIKE '%IPPF2:%';
UPDATE codes SET related_code = CONCAT(related_code, ';IPPF2:2174040503000') WHERE related_code IS NOT NULL AND related_code LIKE '%IPPF:258284821%' AND related_code NOT LIKE '%IPPF2:%';
UPDATE drugs SET related_code = CONCAT(related_code, ';IPPF2:2174040503000') WHERE related_code IS NOT NULL AND related_code LIKE '%IPPF:258284821%' AND related_code NOT LIKE '%IPPF2:%';
UPDATE codes SET related_code = CONCAT(related_code, ';IPPF2:2174040503000') WHERE related_code IS NOT NULL AND related_code LIKE '%IPPF:258284921%' AND related_code NOT LIKE '%IPPF2:%';
UPDATE drugs SET related_code = CONCAT(related_code, ';IPPF2:2174040503000') WHERE related_code IS NOT NULL AND related_code LIKE '%IPPF:258284921%' AND related_code NOT LIKE '%IPPF2:%';
UPDATE codes SET related_code = CONCAT(related_code, ';IPPF2:2174040503000') WHERE related_code IS NOT NULL AND related_code LIKE '%IPPF:258284999%' AND related_code NOT LIKE '%IPPF2:%';
UPDATE drugs SET related_code = CONCAT(related_code, ';IPPF2:2174040503000') WHERE related_code IS NOT NULL AND related_code LIKE '%IPPF:258284999%' AND related_code NOT LIKE '%IPPF2:%';
UPDATE codes SET related_code = CONCAT(related_code, ';IPPF2:2174040504000') WHERE related_code IS NOT NULL AND related_code LIKE '%IPPF:258281123%' AND related_code NOT LIKE '%IPPF2:%';
UPDATE drugs SET related_code = CONCAT(related_code, ';IPPF2:2174040504000') WHERE related_code IS NOT NULL AND related_code LIKE '%IPPF:258281123%' AND related_code NOT LIKE '%IPPF2:%';
UPDATE codes SET related_code = CONCAT(related_code, ';IPPF2:2174040504000') WHERE related_code IS NOT NULL AND related_code LIKE '%IPPF:258281223%' AND related_code NOT LIKE '%IPPF2:%';
UPDATE drugs SET related_code = CONCAT(related_code, ';IPPF2:2174040504000') WHERE related_code IS NOT NULL AND related_code LIKE '%IPPF:258281223%' AND related_code NOT LIKE '%IPPF2:%';
UPDATE codes SET related_code = CONCAT(related_code, ';IPPF2:2174040504000') WHERE related_code IS NOT NULL AND related_code LIKE '%IPPF:258281999%' AND related_code NOT LIKE '%IPPF2:%';
UPDATE drugs SET related_code = CONCAT(related_code, ';IPPF2:2174040504000') WHERE related_code IS NOT NULL AND related_code LIKE '%IPPF:258281999%' AND related_code NOT LIKE '%IPPF2:%';
UPDATE codes SET related_code = CONCAT(related_code, ';IPPF2:2180000000000') WHERE related_code IS NOT NULL AND related_code LIKE '%IPPF:260290000%' AND related_code NOT LIKE '%IPPF2:%';
UPDATE drugs SET related_code = CONCAT(related_code, ';IPPF2:2180000000000') WHERE related_code IS NOT NULL AND related_code LIKE '%IPPF:260290000%' AND related_code NOT LIKE '%IPPF2:%';
UPDATE codes SET related_code = CONCAT(related_code, ';IPPF2:2181110141000') WHERE related_code IS NOT NULL AND related_code LIKE '%IPPF:261291129%' AND related_code NOT LIKE '%IPPF2:%';
UPDATE drugs SET related_code = CONCAT(related_code, ';IPPF2:2181110141000') WHERE related_code IS NOT NULL AND related_code LIKE '%IPPF:261291129%' AND related_code NOT LIKE '%IPPF2:%';
UPDATE codes SET related_code = CONCAT(related_code, ';IPPF2:2181110141000') WHERE related_code IS NOT NULL AND related_code LIKE '%IPPF:261291229%' AND related_code NOT LIKE '%IPPF2:%';
UPDATE drugs SET related_code = CONCAT(related_code, ';IPPF2:2181110141000') WHERE related_code IS NOT NULL AND related_code LIKE '%IPPF:261291229%' AND related_code NOT LIKE '%IPPF2:%';
UPDATE codes SET related_code = CONCAT(related_code, ';IPPF2:2181110141000') WHERE related_code IS NOT NULL AND related_code LIKE '%IPPF:261291329%' AND related_code NOT LIKE '%IPPF2:%';
UPDATE drugs SET related_code = CONCAT(related_code, ';IPPF2:2181110141000') WHERE related_code IS NOT NULL AND related_code LIKE '%IPPF:261291329%' AND related_code NOT LIKE '%IPPF2:%';
UPDATE codes SET related_code = CONCAT(related_code, ';IPPF2:2181110141000') WHERE related_code IS NOT NULL AND related_code LIKE '%IPPF:261291429%' AND related_code NOT LIKE '%IPPF2:%';
UPDATE drugs SET related_code = CONCAT(related_code, ';IPPF2:2181110141000') WHERE related_code IS NOT NULL AND related_code LIKE '%IPPF:261291429%' AND related_code NOT LIKE '%IPPF2:%';
UPDATE codes SET related_code = CONCAT(related_code, ';IPPF2:2181110141000') WHERE related_code IS NOT NULL AND related_code LIKE '%IPPF:261291529%' AND related_code NOT LIKE '%IPPF2:%';
UPDATE drugs SET related_code = CONCAT(related_code, ';IPPF2:2181110141000') WHERE related_code IS NOT NULL AND related_code LIKE '%IPPF:261291529%' AND related_code NOT LIKE '%IPPF2:%';
UPDATE codes SET related_code = CONCAT(related_code, ';IPPF2:2181110141000') WHERE related_code IS NOT NULL AND related_code LIKE '%IPPF:261291629%' AND related_code NOT LIKE '%IPPF2:%';
UPDATE drugs SET related_code = CONCAT(related_code, ';IPPF2:2181110141000') WHERE related_code IS NOT NULL AND related_code LIKE '%IPPF:261291629%' AND related_code NOT LIKE '%IPPF2:%';
UPDATE codes SET related_code = CONCAT(related_code, ';IPPF2:2181110141000') WHERE related_code IS NOT NULL AND related_code LIKE '%IPPF:261291729%' AND related_code NOT LIKE '%IPPF2:%';
UPDATE drugs SET related_code = CONCAT(related_code, ';IPPF2:2181110141000') WHERE related_code IS NOT NULL AND related_code LIKE '%IPPF:261291729%' AND related_code NOT LIKE '%IPPF2:%';
UPDATE codes SET related_code = CONCAT(related_code, ';IPPF2:2181110141000') WHERE related_code IS NOT NULL AND related_code LIKE '%IPPF:262292129%' AND related_code NOT LIKE '%IPPF2:%';
UPDATE drugs SET related_code = CONCAT(related_code, ';IPPF2:2181110141000') WHERE related_code IS NOT NULL AND related_code LIKE '%IPPF:262292129%' AND related_code NOT LIKE '%IPPF2:%';
UPDATE codes SET related_code = CONCAT(related_code, ';IPPF2:2181110141000') WHERE related_code IS NOT NULL AND related_code LIKE '%IPPF:262296329%' AND related_code NOT LIKE '%IPPF2:%';
UPDATE drugs SET related_code = CONCAT(related_code, ';IPPF2:2181110141000') WHERE related_code IS NOT NULL AND related_code LIKE '%IPPF:262296329%' AND related_code NOT LIKE '%IPPF2:%';
UPDATE codes SET related_code = CONCAT(related_code, ';IPPF2:2181110141000') WHERE related_code IS NOT NULL AND related_code LIKE '%IPPF:261291999%' AND related_code NOT LIKE '%IPPF2:%';
UPDATE drugs SET related_code = CONCAT(related_code, ';IPPF2:2181110141000') WHERE related_code IS NOT NULL AND related_code LIKE '%IPPF:261291999%' AND related_code NOT LIKE '%IPPF2:%';
UPDATE codes SET related_code = CONCAT(related_code, ';IPPF2:2181110141000') WHERE related_code IS NOT NULL AND related_code LIKE '%IPPF:262292999%' AND related_code NOT LIKE '%IPPF2:%';
UPDATE drugs SET related_code = CONCAT(related_code, ';IPPF2:2181110141000') WHERE related_code IS NOT NULL AND related_code LIKE '%IPPF:262292999%' AND related_code NOT LIKE '%IPPF2:%';
UPDATE codes SET related_code = CONCAT(related_code, ';IPPF2:2181210142000') WHERE related_code IS NOT NULL AND related_code LIKE '%IPPF:262293129%' AND related_code NOT LIKE '%IPPF2:%';
UPDATE drugs SET related_code = CONCAT(related_code, ';IPPF2:2181210142000') WHERE related_code IS NOT NULL AND related_code LIKE '%IPPF:262293129%' AND related_code NOT LIKE '%IPPF2:%';
UPDATE codes SET related_code = CONCAT(related_code, ';IPPF2:2181210142000') WHERE related_code IS NOT NULL AND related_code LIKE '%IPPF:262293229%' AND related_code NOT LIKE '%IPPF2:%';
UPDATE drugs SET related_code = CONCAT(related_code, ';IPPF2:2181210142000') WHERE related_code IS NOT NULL AND related_code LIKE '%IPPF:262293229%' AND related_code NOT LIKE '%IPPF2:%';
UPDATE codes SET related_code = CONCAT(related_code, ';IPPF2:2181210142000') WHERE related_code IS NOT NULL AND related_code LIKE '%IPPF:262293329%' AND related_code NOT LIKE '%IPPF2:%';
UPDATE drugs SET related_code = CONCAT(related_code, ';IPPF2:2181210142000') WHERE related_code IS NOT NULL AND related_code LIKE '%IPPF:262293329%' AND related_code NOT LIKE '%IPPF2:%';
UPDATE codes SET related_code = CONCAT(related_code, ';IPPF2:2181210142000') WHERE related_code IS NOT NULL AND related_code LIKE '%IPPF:262294129%' AND related_code NOT LIKE '%IPPF2:%';
UPDATE drugs SET related_code = CONCAT(related_code, ';IPPF2:2181210142000') WHERE related_code IS NOT NULL AND related_code LIKE '%IPPF:262294129%' AND related_code NOT LIKE '%IPPF2:%';
UPDATE codes SET related_code = CONCAT(related_code, ';IPPF2:2181210142000') WHERE related_code IS NOT NULL AND related_code LIKE '%IPPF:262294229%' AND related_code NOT LIKE '%IPPF2:%';
UPDATE drugs SET related_code = CONCAT(related_code, ';IPPF2:2181210142000') WHERE related_code IS NOT NULL AND related_code LIKE '%IPPF:262294229%' AND related_code NOT LIKE '%IPPF2:%';
UPDATE codes SET related_code = CONCAT(related_code, ';IPPF2:2181210142000') WHERE related_code IS NOT NULL AND related_code LIKE '%IPPF:262295129%' AND related_code NOT LIKE '%IPPF2:%';
UPDATE drugs SET related_code = CONCAT(related_code, ';IPPF2:2181210142000') WHERE related_code IS NOT NULL AND related_code LIKE '%IPPF:262295129%' AND related_code NOT LIKE '%IPPF2:%';
UPDATE codes SET related_code = CONCAT(related_code, ';IPPF2:2181210142000') WHERE related_code IS NOT NULL AND related_code LIKE '%IPPF:262293999%' AND related_code NOT LIKE '%IPPF2:%';
UPDATE drugs SET related_code = CONCAT(related_code, ';IPPF2:2181210142000') WHERE related_code IS NOT NULL AND related_code LIKE '%IPPF:262293999%' AND related_code NOT LIKE '%IPPF2:%';
UPDATE codes SET related_code = CONCAT(related_code, ';IPPF2:2181210142000') WHERE related_code IS NOT NULL AND related_code LIKE '%IPPF:262294999%' AND related_code NOT LIKE '%IPPF2:%';
UPDATE drugs SET related_code = CONCAT(related_code, ';IPPF2:2181210142000') WHERE related_code IS NOT NULL AND related_code LIKE '%IPPF:262294999%' AND related_code NOT LIKE '%IPPF2:%';
UPDATE codes SET related_code = CONCAT(related_code, ';IPPF2:2181310143000') WHERE related_code IS NOT NULL AND related_code LIKE '%IPPF:263297129%' AND related_code NOT LIKE '%IPPF2:%';
UPDATE drugs SET related_code = CONCAT(related_code, ';IPPF2:2181310143000') WHERE related_code IS NOT NULL AND related_code LIKE '%IPPF:263297129%' AND related_code NOT LIKE '%IPPF2:%';
UPDATE codes SET related_code = CONCAT(related_code, ';IPPF2:2181310143000') WHERE related_code IS NOT NULL AND related_code LIKE '%IPPF:262296129%' AND related_code NOT LIKE '%IPPF2:%';
UPDATE drugs SET related_code = CONCAT(related_code, ';IPPF2:2181310143000') WHERE related_code IS NOT NULL AND related_code LIKE '%IPPF:262296129%' AND related_code NOT LIKE '%IPPF2:%';
UPDATE codes SET related_code = CONCAT(related_code, ';IPPF2:2181310143000') WHERE related_code IS NOT NULL AND related_code LIKE '%IPPF:262296229%' AND related_code NOT LIKE '%IPPF2:%';
UPDATE drugs SET related_code = CONCAT(related_code, ';IPPF2:2181310143000') WHERE related_code IS NOT NULL AND related_code LIKE '%IPPF:262296229%' AND related_code NOT LIKE '%IPPF2:%';
UPDATE codes SET related_code = CONCAT(related_code, ';IPPF2:2181310143000') WHERE related_code IS NOT NULL AND related_code LIKE '%IPPF:262295229%' AND related_code NOT LIKE '%IPPF2:%';
UPDATE drugs SET related_code = CONCAT(related_code, ';IPPF2:2181310143000') WHERE related_code IS NOT NULL AND related_code LIKE '%IPPF:262295229%' AND related_code NOT LIKE '%IPPF2:%';
UPDATE codes SET related_code = CONCAT(related_code, ';IPPF2:2181310143000') WHERE related_code IS NOT NULL AND related_code LIKE '%IPPF:262294329%' AND related_code NOT LIKE '%IPPF2:%';
UPDATE drugs SET related_code = CONCAT(related_code, ';IPPF2:2181310143000') WHERE related_code IS NOT NULL AND related_code LIKE '%IPPF:262294329%' AND related_code NOT LIKE '%IPPF2:%';
UPDATE codes SET related_code = CONCAT(related_code, ';IPPF2:2181310143000') WHERE related_code IS NOT NULL AND related_code LIKE '%IPPF:262295429%' AND related_code NOT LIKE '%IPPF2:%';
UPDATE drugs SET related_code = CONCAT(related_code, ';IPPF2:2181310143000') WHERE related_code IS NOT NULL AND related_code LIKE '%IPPF:262295429%' AND related_code NOT LIKE '%IPPF2:%';
UPDATE codes SET related_code = CONCAT(related_code, ';IPPF2:2184140100301') WHERE related_code IS NOT NULL AND related_code LIKE '%IPPF:261291829%' AND related_code NOT LIKE '%IPPF2:%';
UPDATE drugs SET related_code = CONCAT(related_code, ';IPPF2:2184140100301') WHERE related_code IS NOT NULL AND related_code LIKE '%IPPF:261291829%' AND related_code NOT LIKE '%IPPF2:%';
UPDATE codes SET related_code = CONCAT(related_code, ';IPPF2:2184140100301') WHERE related_code IS NOT NULL AND related_code LIKE '%IPPF:262292229%' AND related_code NOT LIKE '%IPPF2:%';
UPDATE drugs SET related_code = CONCAT(related_code, ';IPPF2:2184140100301') WHERE related_code IS NOT NULL AND related_code LIKE '%IPPF:262292229%' AND related_code NOT LIKE '%IPPF2:%';
UPDATE codes SET related_code = CONCAT(related_code, ';IPPF2:2184100000800') WHERE related_code IS NOT NULL AND related_code LIKE '%IPPF:262295329%' AND related_code NOT LIKE '%IPPF2:%';
UPDATE drugs SET related_code = CONCAT(related_code, ';IPPF2:2184100000800') WHERE related_code IS NOT NULL AND related_code LIKE '%IPPF:262295329%' AND related_code NOT LIKE '%IPPF2:%';
UPDATE codes SET related_code = CONCAT(related_code, ';IPPF2:2184100000800') WHERE related_code IS NOT NULL AND related_code LIKE '%IPPF:262295999%' AND related_code NOT LIKE '%IPPF2:%';
UPDATE drugs SET related_code = CONCAT(related_code, ';IPPF2:2184100000800') WHERE related_code IS NOT NULL AND related_code LIKE '%IPPF:262295999%' AND related_code NOT LIKE '%IPPF2:%';
UPDATE codes SET related_code = CONCAT(related_code, ';IPPF2:2184100000800') WHERE related_code IS NOT NULL AND related_code LIKE '%IPPF:262296999%' AND related_code NOT LIKE '%IPPF2:%';
UPDATE drugs SET related_code = CONCAT(related_code, ';IPPF2:2184100000800') WHERE related_code IS NOT NULL AND related_code LIKE '%IPPF:262296999%' AND related_code NOT LIKE '%IPPF2:%';
UPDATE codes SET related_code = CONCAT(related_code, ';IPPF2:2184100000800') WHERE related_code IS NOT NULL AND related_code LIKE '%IPPF:263297999%' AND related_code NOT LIKE '%IPPF2:%';
UPDATE drugs SET related_code = CONCAT(related_code, ';IPPF2:2184100000800') WHERE related_code IS NOT NULL AND related_code LIKE '%IPPF:263297999%' AND related_code NOT LIKE '%IPPF2:%';
UPDATE codes SET related_code = CONCAT(related_code, ';IPPF2:2184100000800') WHERE related_code IS NOT NULL AND related_code LIKE '%IPPF:260290999%' AND related_code NOT LIKE '%IPPF2:%';
UPDATE drugs SET related_code = CONCAT(related_code, ';IPPF2:2184100000800') WHERE related_code IS NOT NULL AND related_code LIKE '%IPPF:260290999%' AND related_code NOT LIKE '%IPPF2:%';
UPDATE codes SET related_code = CONCAT(related_code, ';IPPF2:2190000000000') WHERE related_code IS NOT NULL AND related_code LIKE '%IPPF:373641000%' AND related_code NOT LIKE '%IPPF2:%';
UPDATE drugs SET related_code = CONCAT(related_code, ';IPPF2:2190000000000') WHERE related_code IS NOT NULL AND related_code LIKE '%IPPF:373641000%' AND related_code NOT LIKE '%IPPF2:%';
UPDATE codes SET related_code = CONCAT(related_code, ';IPPF2:2192020000000') WHERE related_code IS NOT NULL AND related_code LIKE '%IPPF:373641130%' AND related_code NOT LIKE '%IPPF2:%';
UPDATE drugs SET related_code = CONCAT(related_code, ';IPPF2:2192020000000') WHERE related_code IS NOT NULL AND related_code LIKE '%IPPF:373641130%' AND related_code NOT LIKE '%IPPF2:%';
UPDATE codes SET related_code = CONCAT(related_code, ';IPPF2:2192020900401') WHERE related_code IS NOT NULL AND related_code LIKE '%IPPF:373641432%' AND related_code NOT LIKE '%IPPF2:%';
UPDATE drugs SET related_code = CONCAT(related_code, ';IPPF2:2192020900401') WHERE related_code IS NOT NULL AND related_code LIKE '%IPPF:373641432%' AND related_code NOT LIKE '%IPPF2:%';
UPDATE codes SET related_code = CONCAT(related_code, ';IPPF2:2193030000000') WHERE related_code IS NOT NULL AND related_code LIKE '%IPPF:373641632%' AND related_code NOT LIKE '%IPPF2:%';
UPDATE drugs SET related_code = CONCAT(related_code, ';IPPF2:2193030000000') WHERE related_code IS NOT NULL AND related_code LIKE '%IPPF:373641632%' AND related_code NOT LIKE '%IPPF2:%';
UPDATE codes SET related_code = CONCAT(related_code, ';IPPF2:2193030000000') WHERE related_code IS NOT NULL AND related_code LIKE '%IPPF:373641732%' AND related_code NOT LIKE '%IPPF2:%';
UPDATE drugs SET related_code = CONCAT(related_code, ';IPPF2:2193030000000') WHERE related_code IS NOT NULL AND related_code LIKE '%IPPF:373641732%' AND related_code NOT LIKE '%IPPF2:%';
UPDATE codes SET related_code = CONCAT(related_code, ';IPPF2:2193030000000') WHERE related_code IS NOT NULL AND related_code LIKE '%IPPF:373641833%' AND related_code NOT LIKE '%IPPF2:%';
UPDATE drugs SET related_code = CONCAT(related_code, ';IPPF2:2193030000000') WHERE related_code IS NOT NULL AND related_code LIKE '%IPPF:373641833%' AND related_code NOT LIKE '%IPPF2:%';
UPDATE codes SET related_code = CONCAT(related_code, ';IPPF2:2194040401401') WHERE related_code IS NOT NULL AND related_code LIKE '%IPPF:373641532%' AND related_code NOT LIKE '%IPPF2:%';
UPDATE drugs SET related_code = CONCAT(related_code, ';IPPF2:2194040401401') WHERE related_code IS NOT NULL AND related_code LIKE '%IPPF:373641532%' AND related_code NOT LIKE '%IPPF2:%';
UPDATE codes SET related_code = CONCAT(related_code, ';IPPF2:2195050000000') WHERE related_code IS NOT NULL AND related_code LIKE '%IPPF:373641231%' AND related_code NOT LIKE '%IPPF2:%';
UPDATE drugs SET related_code = CONCAT(related_code, ';IPPF2:2195050000000') WHERE related_code IS NOT NULL AND related_code LIKE '%IPPF:373641231%' AND related_code NOT LIKE '%IPPF2:%';
UPDATE codes SET related_code = CONCAT(related_code, ';IPPF2:2195050000000') WHERE related_code IS NOT NULL AND related_code LIKE '%IPPF:373641331%' AND related_code NOT LIKE '%IPPF2:%';
UPDATE drugs SET related_code = CONCAT(related_code, ';IPPF2:2195050000000') WHERE related_code IS NOT NULL AND related_code LIKE '%IPPF:373641331%' AND related_code NOT LIKE '%IPPF2:%';
UPDATE codes SET related_code = CONCAT(related_code, ';IPPF2:2196000000800') WHERE related_code IS NOT NULL AND related_code LIKE '%IPPF:373641999%' AND related_code NOT LIKE '%IPPF2:%';
UPDATE drugs SET related_code = CONCAT(related_code, ';IPPF2:2196000000800') WHERE related_code IS NOT NULL AND related_code LIKE '%IPPF:373641999%' AND related_code NOT LIKE '%IPPF2:%';
UPDATE codes SET related_code = CONCAT(related_code, ';IPPF2:2202020000000') WHERE related_code IS NOT NULL AND related_code LIKE '%IPPF:269298999%' AND related_code NOT LIKE '%IPPF2:%';
UPDATE drugs SET related_code = CONCAT(related_code, ';IPPF2:2202020000000') WHERE related_code IS NOT NULL AND related_code LIKE '%IPPF:269298999%' AND related_code NOT LIKE '%IPPF2:%';
UPDATE codes SET related_code = CONCAT(related_code, ';IPPF2:2202020000000') WHERE related_code IS NOT NULL AND related_code LIKE '%IPPF:269298120%' AND related_code NOT LIKE '%IPPF2:%';
UPDATE drugs SET related_code = CONCAT(related_code, ';IPPF2:2202020000000') WHERE related_code IS NOT NULL AND related_code LIKE '%IPPF:269298120%' AND related_code NOT LIKE '%IPPF2:%';
UPDATE codes SET related_code = CONCAT(related_code, ';IPPF2:2203030000000') WHERE related_code IS NOT NULL AND related_code LIKE '%IPPF:269298322%' AND related_code NOT LIKE '%IPPF2:%';
UPDATE drugs SET related_code = CONCAT(related_code, ';IPPF2:2203030000000') WHERE related_code IS NOT NULL AND related_code LIKE '%IPPF:269298322%' AND related_code NOT LIKE '%IPPF2:%';
UPDATE codes SET related_code = CONCAT(related_code, ';IPPF2:2203030000000') WHERE related_code IS NOT NULL AND related_code LIKE '%IPPF:269298423%' AND related_code NOT LIKE '%IPPF2:%';
UPDATE drugs SET related_code = CONCAT(related_code, ';IPPF2:2203030000000') WHERE related_code IS NOT NULL AND related_code LIKE '%IPPF:269298423%' AND related_code NOT LIKE '%IPPF2:%';
UPDATE codes SET related_code = CONCAT(related_code, ';IPPF2:2205050000000') WHERE related_code IS NOT NULL AND related_code LIKE '%IPPF:269298221%' AND related_code NOT LIKE '%IPPF2:%';
UPDATE drugs SET related_code = CONCAT(related_code, ';IPPF2:2205050000000') WHERE related_code IS NOT NULL AND related_code LIKE '%IPPF:269298221%' AND related_code NOT LIKE '%IPPF2:%';
UPDATE codes SET related_code = CONCAT(related_code, ';IPPF2:4100000000000') WHERE related_code IS NOT NULL AND related_code LIKE '%IPPF:380911999%' AND related_code NOT LIKE '%IPPF2:%';
UPDATE drugs SET related_code = CONCAT(related_code, ';IPPF2:4100000000000') WHERE related_code IS NOT NULL AND related_code LIKE '%IPPF:380911999%' AND related_code NOT LIKE '%IPPF2:%';
UPDATE codes SET related_code = CONCAT(related_code, ';IPPF2:4100000000000') WHERE related_code IS NOT NULL AND related_code LIKE '%IPPF:381912150%' AND related_code NOT LIKE '%IPPF2:%';
UPDATE drugs SET related_code = CONCAT(related_code, ';IPPF2:4100000000000') WHERE related_code IS NOT NULL AND related_code LIKE '%IPPF:381912150%' AND related_code NOT LIKE '%IPPF2:%';
UPDATE codes SET related_code = CONCAT(related_code, ';IPPF2:4100000000000') WHERE related_code IS NOT NULL AND related_code LIKE '%IPPF:381912250%' AND related_code NOT LIKE '%IPPF2:%';
UPDATE drugs SET related_code = CONCAT(related_code, ';IPPF2:4100000000000') WHERE related_code IS NOT NULL AND related_code LIKE '%IPPF:381912250%' AND related_code NOT LIKE '%IPPF2:%';
UPDATE codes SET related_code = CONCAT(related_code, ';IPPF2:4100000000000') WHERE related_code IS NOT NULL AND related_code LIKE '%IPPF:381912350%' AND related_code NOT LIKE '%IPPF2:%';
UPDATE drugs SET related_code = CONCAT(related_code, ';IPPF2:4100000000000') WHERE related_code IS NOT NULL AND related_code LIKE '%IPPF:381912350%' AND related_code NOT LIKE '%IPPF2:%';
UPDATE codes SET related_code = CONCAT(related_code, ';IPPF2:4100000000000') WHERE related_code IS NOT NULL AND related_code LIKE '%IPPF:381913999%' AND related_code NOT LIKE '%IPPF2:%';
UPDATE drugs SET related_code = CONCAT(related_code, ';IPPF2:4100000000000') WHERE related_code IS NOT NULL AND related_code LIKE '%IPPF:381913999%' AND related_code NOT LIKE '%IPPF2:%';
UPDATE codes SET related_code = CONCAT(related_code, ';IPPF2:4100000000000') WHERE related_code IS NOT NULL AND related_code LIKE '%IPPF:382914450%' AND related_code NOT LIKE '%IPPF2:%';
UPDATE drugs SET related_code = CONCAT(related_code, ';IPPF2:4100000000000') WHERE related_code IS NOT NULL AND related_code LIKE '%IPPF:382914450%' AND related_code NOT LIKE '%IPPF2:%';
UPDATE codes SET related_code = CONCAT(related_code, ';IPPF2:4100000000000') WHERE related_code IS NOT NULL AND related_code LIKE '%IPPF:382914450%' AND related_code NOT LIKE '%IPPF2:%';
UPDATE drugs SET related_code = CONCAT(related_code, ';IPPF2:4100000000000') WHERE related_code IS NOT NULL AND related_code LIKE '%IPPF:382914450%' AND related_code NOT LIKE '%IPPF2:%';
UPDATE codes SET related_code = CONCAT(related_code, ';IPPF2:4100000000000') WHERE related_code IS NOT NULL AND related_code LIKE '%IPPF:382915999%' AND related_code NOT LIKE '%IPPF2:%';
UPDATE drugs SET related_code = CONCAT(related_code, ';IPPF2:4100000000000') WHERE related_code IS NOT NULL AND related_code LIKE '%IPPF:382915999%' AND related_code NOT LIKE '%IPPF2:%';
UPDATE codes SET related_code = CONCAT(related_code, ';IPPF2:4100000000000') WHERE related_code IS NOT NULL AND related_code LIKE '%IPPF:491990190%' AND related_code NOT LIKE '%IPPF2:%';
UPDATE drugs SET related_code = CONCAT(related_code, ';IPPF2:4100000000000') WHERE related_code IS NOT NULL AND related_code LIKE '%IPPF:491990190%' AND related_code NOT LIKE '%IPPF2:%';
UPDATE codes SET related_code = CONCAT(related_code, ';IPPF2:4100000000000') WHERE related_code IS NOT NULL AND related_code LIKE '%IPPF:491990290%' AND related_code NOT LIKE '%IPPF2:%';
UPDATE drugs SET related_code = CONCAT(related_code, ';IPPF2:4100000000000') WHERE related_code IS NOT NULL AND related_code LIKE '%IPPF:491990290%' AND related_code NOT LIKE '%IPPF2:%';
UPDATE codes SET related_code = CONCAT(related_code, ';IPPF2:4100000000000') WHERE related_code IS NOT NULL AND related_code LIKE '%IPPF:491990999%' AND related_code NOT LIKE '%IPPF2:%';
UPDATE drugs SET related_code = CONCAT(related_code, ';IPPF2:4100000000000') WHERE related_code IS NOT NULL AND related_code LIKE '%IPPF:491990999%' AND related_code NOT LIKE '%IPPF2:%';
UPDATE codes SET related_code = CONCAT(related_code, ';IPPF2:4100000000000') WHERE related_code IS NOT NULL AND related_code LIKE '%IPPF:492992090%' AND related_code NOT LIKE '%IPPF2:%';
UPDATE drugs SET related_code = CONCAT(related_code, ';IPPF2:4100000000000') WHERE related_code IS NOT NULL AND related_code LIKE '%IPPF:492992090%' AND related_code NOT LIKE '%IPPF2:%';
UPDATE codes SET related_code = CONCAT(related_code, ';IPPF2:4100000000000') WHERE related_code IS NOT NULL AND related_code LIKE '%IPPF:492992999%' AND related_code NOT LIKE '%IPPF2:%';
UPDATE drugs SET related_code = CONCAT(related_code, ';IPPF2:4100000000000') WHERE related_code IS NOT NULL AND related_code LIKE '%IPPF:492992999%' AND related_code NOT LIKE '%IPPF2:%';
UPDATE codes SET related_code = CONCAT(related_code, ';IPPF2:3100000000000') WHERE related_code IS NOT NULL AND related_code LIKE '%IPPF:370000000%' AND related_code NOT LIKE '%IPPF2:%';
UPDATE drugs SET related_code = CONCAT(related_code, ';IPPF2:3100000000000') WHERE related_code IS NOT NULL AND related_code LIKE '%IPPF:370000000%' AND related_code NOT LIKE '%IPPF2:%';
UPDATE codes SET related_code = CONCAT(related_code, ';IPPF2:3110010000000') WHERE related_code IS NOT NULL AND related_code LIKE '%IPPF:375831539%' AND related_code NOT LIKE '%IPPF2:%';
UPDATE drugs SET related_code = CONCAT(related_code, ';IPPF2:3110010000000') WHERE related_code IS NOT NULL AND related_code LIKE '%IPPF:375831539%' AND related_code NOT LIKE '%IPPF2:%';
UPDATE codes SET related_code = CONCAT(related_code, ';IPPF2:3110010000000') WHERE related_code IS NOT NULL AND related_code LIKE '%IPPF:371361129%' AND related_code NOT LIKE '%IPPF2:%';
UPDATE drugs SET related_code = CONCAT(related_code, ';IPPF2:3110010000000') WHERE related_code IS NOT NULL AND related_code LIKE '%IPPF:371361129%' AND related_code NOT LIKE '%IPPF2:%';
UPDATE codes SET related_code = CONCAT(related_code, ';IPPF2:3110010900401') WHERE related_code IS NOT NULL AND related_code LIKE '%IPPF:373601729%' AND related_code NOT LIKE '%IPPF2:%';
UPDATE drugs SET related_code = CONCAT(related_code, ';IPPF2:3110010900401') WHERE related_code IS NOT NULL AND related_code LIKE '%IPPF:373601729%' AND related_code NOT LIKE '%IPPF2:%';
UPDATE codes SET related_code = CONCAT(related_code, ';IPPF2:3110010900401') WHERE related_code IS NOT NULL AND related_code LIKE '%IPPF:373601829%' AND related_code NOT LIKE '%IPPF2:%';
UPDATE drugs SET related_code = CONCAT(related_code, ';IPPF2:3110010900401') WHERE related_code IS NOT NULL AND related_code LIKE '%IPPF:373601829%' AND related_code NOT LIKE '%IPPF2:%';
UPDATE codes SET related_code = CONCAT(related_code, ';IPPF2:3120020000000') WHERE related_code IS NOT NULL AND related_code LIKE '%IPPF:371301130%' AND related_code NOT LIKE '%IPPF2:%';
UPDATE drugs SET related_code = CONCAT(related_code, ';IPPF2:3120020000000') WHERE related_code IS NOT NULL AND related_code LIKE '%IPPF:371301130%' AND related_code NOT LIKE '%IPPF2:%';
UPDATE codes SET related_code = CONCAT(related_code, ';IPPF2:3120020000000') WHERE related_code IS NOT NULL AND related_code LIKE '%IPPF:371311130%' AND related_code NOT LIKE '%IPPF2:%';
UPDATE drugs SET related_code = CONCAT(related_code, ';IPPF2:3120020000000') WHERE related_code IS NOT NULL AND related_code LIKE '%IPPF:371311130%' AND related_code NOT LIKE '%IPPF2:%';
UPDATE codes SET related_code = CONCAT(related_code, ';IPPF2:3120020000000') WHERE related_code IS NOT NULL AND related_code LIKE '%IPPF:371331130%' AND related_code NOT LIKE '%IPPF2:%';
UPDATE drugs SET related_code = CONCAT(related_code, ';IPPF2:3120020000000') WHERE related_code IS NOT NULL AND related_code LIKE '%IPPF:371331130%' AND related_code NOT LIKE '%IPPF2:%';
UPDATE codes SET related_code = CONCAT(related_code, ';IPPF2:3120020000000') WHERE related_code IS NOT NULL AND related_code LIKE '%IPPF:371341130%' AND related_code NOT LIKE '%IPPF2:%';
UPDATE drugs SET related_code = CONCAT(related_code, ';IPPF2:3120020000000') WHERE related_code IS NOT NULL AND related_code LIKE '%IPPF:371341130%' AND related_code NOT LIKE '%IPPF2:%';
UPDATE codes SET related_code = CONCAT(related_code, ';IPPF2:3120020000000') WHERE related_code IS NOT NULL AND related_code LIKE '%IPPF:371351130%' AND related_code NOT LIKE '%IPPF2:%';
UPDATE drugs SET related_code = CONCAT(related_code, ';IPPF2:3120020000000') WHERE related_code IS NOT NULL AND related_code LIKE '%IPPF:371351130%' AND related_code NOT LIKE '%IPPF2:%';
UPDATE codes SET related_code = CONCAT(related_code, ';IPPF2:3120020000000') WHERE related_code IS NOT NULL AND related_code LIKE '%IPPF:372501130%' AND related_code NOT LIKE '%IPPF2:%';
UPDATE drugs SET related_code = CONCAT(related_code, ';IPPF2:3120020000000') WHERE related_code IS NOT NULL AND related_code LIKE '%IPPF:372501130%' AND related_code NOT LIKE '%IPPF2:%';
UPDATE codes SET related_code = CONCAT(related_code, ';IPPF2:3120020000000') WHERE related_code IS NOT NULL AND related_code LIKE '%IPPF:372511131%' AND related_code NOT LIKE '%IPPF2:%';
UPDATE drugs SET related_code = CONCAT(related_code, ';IPPF2:3120020000000') WHERE related_code IS NOT NULL AND related_code LIKE '%IPPF:372511131%' AND related_code NOT LIKE '%IPPF2:%';
UPDATE codes SET related_code = CONCAT(related_code, ';IPPF2:3120020000000') WHERE related_code IS NOT NULL AND related_code LIKE '%IPPF:372521131%' AND related_code NOT LIKE '%IPPF2:%';
UPDATE drugs SET related_code = CONCAT(related_code, ';IPPF2:3120020000000') WHERE related_code IS NOT NULL AND related_code LIKE '%IPPF:372521131%' AND related_code NOT LIKE '%IPPF2:%';
UPDATE codes SET related_code = CONCAT(related_code, ';IPPF2:3120020000000') WHERE related_code IS NOT NULL AND related_code LIKE '%IPPF:372551130%' AND related_code NOT LIKE '%IPPF2:%';
UPDATE drugs SET related_code = CONCAT(related_code, ';IPPF2:3120020000000') WHERE related_code IS NOT NULL AND related_code LIKE '%IPPF:372551130%' AND related_code NOT LIKE '%IPPF2:%';
UPDATE codes SET related_code = CONCAT(related_code, ';IPPF2:3120020000000') WHERE related_code IS NOT NULL AND related_code LIKE '%IPPF:372561130%' AND related_code NOT LIKE '%IPPF2:%';
UPDATE drugs SET related_code = CONCAT(related_code, ';IPPF2:3120020000000') WHERE related_code IS NOT NULL AND related_code LIKE '%IPPF:372561130%' AND related_code NOT LIKE '%IPPF2:%';
UPDATE codes SET related_code = CONCAT(related_code, ';IPPF2:3120020000000') WHERE related_code IS NOT NULL AND related_code LIKE '%IPPF:373621130%' AND related_code NOT LIKE '%IPPF2:%';
UPDATE drugs SET related_code = CONCAT(related_code, ';IPPF2:3120020000000') WHERE related_code IS NOT NULL AND related_code LIKE '%IPPF:373621130%' AND related_code NOT LIKE '%IPPF2:%';
UPDATE codes SET related_code = CONCAT(related_code, ';IPPF2:3120020000000') WHERE related_code IS NOT NULL AND related_code LIKE '%IPPF:373661130%' AND related_code NOT LIKE '%IPPF2:%';
UPDATE drugs SET related_code = CONCAT(related_code, ';IPPF2:3120020000000') WHERE related_code IS NOT NULL AND related_code LIKE '%IPPF:373661130%' AND related_code NOT LIKE '%IPPF2:%';
UPDATE codes SET related_code = CONCAT(related_code, ';IPPF2:3120020000000') WHERE related_code IS NOT NULL AND related_code LIKE '%IPPF:373671231%' AND related_code NOT LIKE '%IPPF2:%';
UPDATE drugs SET related_code = CONCAT(related_code, ';IPPF2:3120020000000') WHERE related_code IS NOT NULL AND related_code LIKE '%IPPF:373671231%' AND related_code NOT LIKE '%IPPF2:%';
UPDATE codes SET related_code = CONCAT(related_code, ';IPPF2:3120020000000') WHERE related_code IS NOT NULL AND related_code LIKE '%IPPF:373671130%' AND related_code NOT LIKE '%IPPF2:%';
UPDATE drugs SET related_code = CONCAT(related_code, ';IPPF2:3120020000000') WHERE related_code IS NOT NULL AND related_code LIKE '%IPPF:373671130%' AND related_code NOT LIKE '%IPPF2:%';
UPDATE codes SET related_code = CONCAT(related_code, ';IPPF2:3120020000000') WHERE related_code IS NOT NULL AND related_code LIKE '%IPPF:374701130%' AND related_code NOT LIKE '%IPPF2:%';
UPDATE drugs SET related_code = CONCAT(related_code, ';IPPF2:3120020000000') WHERE related_code IS NOT NULL AND related_code LIKE '%IPPF:374701130%' AND related_code NOT LIKE '%IPPF2:%';
UPDATE codes SET related_code = CONCAT(related_code, ';IPPF2:3120020000000') WHERE related_code IS NOT NULL AND related_code LIKE '%IPPF:374721130%' AND related_code NOT LIKE '%IPPF2:%';
UPDATE drugs SET related_code = CONCAT(related_code, ';IPPF2:3120020000000') WHERE related_code IS NOT NULL AND related_code LIKE '%IPPF:374721130%' AND related_code NOT LIKE '%IPPF2:%';
UPDATE codes SET related_code = CONCAT(related_code, ';IPPF2:3120020000000') WHERE related_code IS NOT NULL AND related_code LIKE '%IPPF:374741130%' AND related_code NOT LIKE '%IPPF2:%';
UPDATE drugs SET related_code = CONCAT(related_code, ';IPPF2:3120020000000') WHERE related_code IS NOT NULL AND related_code LIKE '%IPPF:374741130%' AND related_code NOT LIKE '%IPPF2:%';
UPDATE codes SET related_code = CONCAT(related_code, ';IPPF2:3120020000000') WHERE related_code IS NOT NULL AND related_code LIKE '%IPPF:374761130%' AND related_code NOT LIKE '%IPPF2:%';
UPDATE drugs SET related_code = CONCAT(related_code, ';IPPF2:3120020000000') WHERE related_code IS NOT NULL AND related_code LIKE '%IPPF:374761130%' AND related_code NOT LIKE '%IPPF2:%';
UPDATE codes SET related_code = CONCAT(related_code, ';IPPF2:3120020000000') WHERE related_code IS NOT NULL AND related_code LIKE '%IPPF:374781130%' AND related_code NOT LIKE '%IPPF2:%';
UPDATE drugs SET related_code = CONCAT(related_code, ';IPPF2:3120020000000') WHERE related_code IS NOT NULL AND related_code LIKE '%IPPF:374781130%' AND related_code NOT LIKE '%IPPF2:%';
UPDATE codes SET related_code = CONCAT(related_code, ';IPPF2:3120020000000') WHERE related_code IS NOT NULL AND related_code LIKE '%IPPF:375801130%' AND related_code NOT LIKE '%IPPF2:%';
UPDATE drugs SET related_code = CONCAT(related_code, ';IPPF2:3120020000000') WHERE related_code IS NOT NULL AND related_code LIKE '%IPPF:375801130%' AND related_code NOT LIKE '%IPPF2:%';
UPDATE codes SET related_code = CONCAT(related_code, ';IPPF2:3120020000000') WHERE related_code IS NOT NULL AND related_code LIKE '%IPPF:375811130%' AND related_code NOT LIKE '%IPPF2:%';
UPDATE drugs SET related_code = CONCAT(related_code, ';IPPF2:3120020000000') WHERE related_code IS NOT NULL AND related_code LIKE '%IPPF:375811130%' AND related_code NOT LIKE '%IPPF2:%';
UPDATE codes SET related_code = CONCAT(related_code, ';IPPF2:3120020000000') WHERE related_code IS NOT NULL AND related_code LIKE '%IPPF:375821130%' AND related_code NOT LIKE '%IPPF2:%';
UPDATE drugs SET related_code = CONCAT(related_code, ';IPPF2:3120020000000') WHERE related_code IS NOT NULL AND related_code LIKE '%IPPF:375821130%' AND related_code NOT LIKE '%IPPF2:%';
UPDATE codes SET related_code = CONCAT(related_code, ';IPPF2:3120020000000') WHERE related_code IS NOT NULL AND related_code LIKE '%IPPF:375831130%' AND related_code NOT LIKE '%IPPF2:%';
UPDATE drugs SET related_code = CONCAT(related_code, ';IPPF2:3120020000000') WHERE related_code IS NOT NULL AND related_code LIKE '%IPPF:375831130%' AND related_code NOT LIKE '%IPPF2:%';
UPDATE codes SET related_code = CONCAT(related_code, ';IPPF2:3120020000000') WHERE related_code IS NOT NULL AND related_code LIKE '%IPPF:371361230%' AND related_code NOT LIKE '%IPPF2:%';
UPDATE drugs SET related_code = CONCAT(related_code, ';IPPF2:3120020000000') WHERE related_code IS NOT NULL AND related_code LIKE '%IPPF:371361230%' AND related_code NOT LIKE '%IPPF2:%';
UPDATE codes SET related_code = CONCAT(related_code, ';IPPF2:3120020000000') WHERE related_code IS NOT NULL AND related_code LIKE '%IPPF:371371130%' AND related_code NOT LIKE '%IPPF2:%';
UPDATE drugs SET related_code = CONCAT(related_code, ';IPPF2:3120020000000') WHERE related_code IS NOT NULL AND related_code LIKE '%IPPF:371371130%' AND related_code NOT LIKE '%IPPF2:%';
UPDATE codes SET related_code = CONCAT(related_code, ';IPPF2:3120020000000') WHERE related_code IS NOT NULL AND related_code LIKE '%IPPF:371381130%' AND related_code NOT LIKE '%IPPF2:%';
UPDATE drugs SET related_code = CONCAT(related_code, ';IPPF2:3120020000000') WHERE related_code IS NOT NULL AND related_code LIKE '%IPPF:371381130%' AND related_code NOT LIKE '%IPPF2:%';
UPDATE codes SET related_code = CONCAT(related_code, ';IPPF2:3120020000000') WHERE related_code IS NOT NULL AND related_code LIKE '%IPPF:371391130%' AND related_code NOT LIKE '%IPPF2:%';
UPDATE drugs SET related_code = CONCAT(related_code, ';IPPF2:3120020000000') WHERE related_code IS NOT NULL AND related_code LIKE '%IPPF:371391130%' AND related_code NOT LIKE '%IPPF2:%';
UPDATE codes SET related_code = CONCAT(related_code, ';IPPF2:3120020000000') WHERE related_code IS NOT NULL AND related_code LIKE '%IPPF:371401130%' AND related_code NOT LIKE '%IPPF2:%';
UPDATE drugs SET related_code = CONCAT(related_code, ';IPPF2:3120020000000') WHERE related_code IS NOT NULL AND related_code LIKE '%IPPF:371401130%' AND related_code NOT LIKE '%IPPF2:%';
UPDATE codes SET related_code = CONCAT(related_code, ';IPPF2:3120020000000') WHERE related_code IS NOT NULL AND related_code LIKE '%IPPF:371411130%' AND related_code NOT LIKE '%IPPF2:%';
UPDATE drugs SET related_code = CONCAT(related_code, ';IPPF2:3120020000000') WHERE related_code IS NOT NULL AND related_code LIKE '%IPPF:371411130%' AND related_code NOT LIKE '%IPPF2:%';
UPDATE codes SET related_code = CONCAT(related_code, ';IPPF2:3120020000000') WHERE related_code IS NOT NULL AND related_code LIKE '%IPPF:371421130%' AND related_code NOT LIKE '%IPPF2:%';
UPDATE drugs SET related_code = CONCAT(related_code, ';IPPF2:3120020000000') WHERE related_code IS NOT NULL AND related_code LIKE '%IPPF:371421130%' AND related_code NOT LIKE '%IPPF2:%';
UPDATE codes SET related_code = CONCAT(related_code, ';IPPF2:3120020000000') WHERE related_code IS NOT NULL AND related_code LIKE '%IPPF:371431130%' AND related_code NOT LIKE '%IPPF2:%';
UPDATE drugs SET related_code = CONCAT(related_code, ';IPPF2:3120020000000') WHERE related_code IS NOT NULL AND related_code LIKE '%IPPF:371431130%' AND related_code NOT LIKE '%IPPF2:%';
UPDATE codes SET related_code = CONCAT(related_code, ';IPPF2:3120020000000') WHERE related_code IS NOT NULL AND related_code LIKE '%IPPF:371441130%' AND related_code NOT LIKE '%IPPF2:%';
UPDATE drugs SET related_code = CONCAT(related_code, ';IPPF2:3120020000000') WHERE related_code IS NOT NULL AND related_code LIKE '%IPPF:371441130%' AND related_code NOT LIKE '%IPPF2:%';
UPDATE codes SET related_code = CONCAT(related_code, ';IPPF2:3120020000000') WHERE related_code IS NOT NULL AND related_code LIKE '%IPPF:372501130%' AND related_code NOT LIKE '%IPPF2:%';
UPDATE drugs SET related_code = CONCAT(related_code, ';IPPF2:3120020000000') WHERE related_code IS NOT NULL AND related_code LIKE '%IPPF:372501130%' AND related_code NOT LIKE '%IPPF2:%';
UPDATE codes SET related_code = CONCAT(related_code, ';IPPF2:3120020000000') WHERE related_code IS NOT NULL AND related_code LIKE '%IPPF:373671999%' AND related_code NOT LIKE '%IPPF2:%';
UPDATE drugs SET related_code = CONCAT(related_code, ';IPPF2:3120020000000') WHERE related_code IS NOT NULL AND related_code LIKE '%IPPF:373671999%' AND related_code NOT LIKE '%IPPF2:%';
UPDATE codes SET related_code = CONCAT(related_code, ';IPPF2:3120020000000') WHERE related_code IS NOT NULL AND related_code LIKE '%IPPF:371321332%' AND related_code NOT LIKE '%IPPF2:%';
UPDATE drugs SET related_code = CONCAT(related_code, ';IPPF2:3120020000000') WHERE related_code IS NOT NULL AND related_code LIKE '%IPPF:371321332%' AND related_code NOT LIKE '%IPPF2:%';
UPDATE codes SET related_code = CONCAT(related_code, ';IPPF2:3120020000000') WHERE related_code IS NOT NULL AND related_code LIKE '%IPPF:371321432%' AND related_code NOT LIKE '%IPPF2:%';
UPDATE drugs SET related_code = CONCAT(related_code, ';IPPF2:3120020000000') WHERE related_code IS NOT NULL AND related_code LIKE '%IPPF:371321432%' AND related_code NOT LIKE '%IPPF2:%';
UPDATE codes SET related_code = CONCAT(related_code, ';IPPF2:3130030000000') WHERE related_code IS NOT NULL AND related_code LIKE '%IPPF:371301332%' AND related_code NOT LIKE '%IPPF2:%';
UPDATE drugs SET related_code = CONCAT(related_code, ';IPPF2:3130030000000') WHERE related_code IS NOT NULL AND related_code LIKE '%IPPF:371301332%' AND related_code NOT LIKE '%IPPF2:%';
UPDATE codes SET related_code = CONCAT(related_code, ';IPPF2:3130030000000') WHERE related_code IS NOT NULL AND related_code LIKE '%IPPF:371301433%' AND related_code NOT LIKE '%IPPF2:%';
UPDATE drugs SET related_code = CONCAT(related_code, ';IPPF2:3130030000000') WHERE related_code IS NOT NULL AND related_code LIKE '%IPPF:371301433%' AND related_code NOT LIKE '%IPPF2:%';
UPDATE codes SET related_code = CONCAT(related_code, ';IPPF2:3130030000000') WHERE related_code IS NOT NULL AND related_code LIKE '%IPPF:371311332%' AND related_code NOT LIKE '%IPPF2:%';
UPDATE drugs SET related_code = CONCAT(related_code, ';IPPF2:3130030000000') WHERE related_code IS NOT NULL AND related_code LIKE '%IPPF:371311332%' AND related_code NOT LIKE '%IPPF2:%';
UPDATE codes SET related_code = CONCAT(related_code, ';IPPF2:3130030000000') WHERE related_code IS NOT NULL AND related_code LIKE '%IPPF:371311433%' AND related_code NOT LIKE '%IPPF2:%';
UPDATE drugs SET related_code = CONCAT(related_code, ';IPPF2:3130030000000') WHERE related_code IS NOT NULL AND related_code LIKE '%IPPF:371311433%' AND related_code NOT LIKE '%IPPF2:%';
UPDATE codes SET related_code = CONCAT(related_code, ';IPPF2:3130030000000') WHERE related_code IS NOT NULL AND related_code LIKE '%IPPF:371321232%' AND related_code NOT LIKE '%IPPF2:%';
UPDATE drugs SET related_code = CONCAT(related_code, ';IPPF2:3130030000000') WHERE related_code IS NOT NULL AND related_code LIKE '%IPPF:371321232%' AND related_code NOT LIKE '%IPPF2:%';
UPDATE codes SET related_code = CONCAT(related_code, ';IPPF2:3130030000000') WHERE related_code IS NOT NULL AND related_code LIKE '%IPPF:371321533%' AND related_code NOT LIKE '%IPPF2:%';
UPDATE drugs SET related_code = CONCAT(related_code, ';IPPF2:3130030000000') WHERE related_code IS NOT NULL AND related_code LIKE '%IPPF:371321533%' AND related_code NOT LIKE '%IPPF2:%';
UPDATE codes SET related_code = CONCAT(related_code, ';IPPF2:3130030000000') WHERE related_code IS NOT NULL AND related_code LIKE '%IPPF:371331332%' AND related_code NOT LIKE '%IPPF2:%';
UPDATE drugs SET related_code = CONCAT(related_code, ';IPPF2:3130030000000') WHERE related_code IS NOT NULL AND related_code LIKE '%IPPF:371331332%' AND related_code NOT LIKE '%IPPF2:%';
UPDATE codes SET related_code = CONCAT(related_code, ';IPPF2:3130030000000') WHERE related_code IS NOT NULL AND related_code LIKE '%IPPF:371331433%' AND related_code NOT LIKE '%IPPF2:%';
UPDATE drugs SET related_code = CONCAT(related_code, ';IPPF2:3130030000000') WHERE related_code IS NOT NULL AND related_code LIKE '%IPPF:371331433%' AND related_code NOT LIKE '%IPPF2:%';
UPDATE codes SET related_code = CONCAT(related_code, ';IPPF2:3130030000000') WHERE related_code IS NOT NULL AND related_code LIKE '%IPPF:371341332%' AND related_code NOT LIKE '%IPPF2:%';
UPDATE drugs SET related_code = CONCAT(related_code, ';IPPF2:3130030000000') WHERE related_code IS NOT NULL AND related_code LIKE '%IPPF:371341332%' AND related_code NOT LIKE '%IPPF2:%';
UPDATE codes SET related_code = CONCAT(related_code, ';IPPF2:3130030000000') WHERE related_code IS NOT NULL AND related_code LIKE '%IPPF:371341433%' AND related_code NOT LIKE '%IPPF2:%';
UPDATE drugs SET related_code = CONCAT(related_code, ';IPPF2:3130030000000') WHERE related_code IS NOT NULL AND related_code LIKE '%IPPF:371341433%' AND related_code NOT LIKE '%IPPF2:%';
UPDATE codes SET related_code = CONCAT(related_code, ';IPPF2:3130030000000') WHERE related_code IS NOT NULL AND related_code LIKE '%IPPF:371351332%' AND related_code NOT LIKE '%IPPF2:%';
UPDATE drugs SET related_code = CONCAT(related_code, ';IPPF2:3130030000000') WHERE related_code IS NOT NULL AND related_code LIKE '%IPPF:371351332%' AND related_code NOT LIKE '%IPPF2:%';
UPDATE codes SET related_code = CONCAT(related_code, ';IPPF2:3130030000000') WHERE related_code IS NOT NULL AND related_code LIKE '%IPPF:371351433%' AND related_code NOT LIKE '%IPPF2:%';
UPDATE drugs SET related_code = CONCAT(related_code, ';IPPF2:3130030000000') WHERE related_code IS NOT NULL AND related_code LIKE '%IPPF:371351433%' AND related_code NOT LIKE '%IPPF2:%';
UPDATE codes SET related_code = CONCAT(related_code, ';IPPF2:3130030000000') WHERE related_code IS NOT NULL AND related_code LIKE '%IPPF:371361432%' AND related_code NOT LIKE '%IPPF2:%';
UPDATE drugs SET related_code = CONCAT(related_code, ';IPPF2:3130030000000') WHERE related_code IS NOT NULL AND related_code LIKE '%IPPF:371361432%' AND related_code NOT LIKE '%IPPF2:%';
UPDATE codes SET related_code = CONCAT(related_code, ';IPPF2:3130030000000') WHERE related_code IS NOT NULL AND related_code LIKE '%IPPF:371371332%' AND related_code NOT LIKE '%IPPF2:%';
UPDATE drugs SET related_code = CONCAT(related_code, ';IPPF2:3130030000000') WHERE related_code IS NOT NULL AND related_code LIKE '%IPPF:371371332%' AND related_code NOT LIKE '%IPPF2:%';
UPDATE codes SET related_code = CONCAT(related_code, ';IPPF2:3130030000000') WHERE related_code IS NOT NULL AND related_code LIKE '%IPPF:371371433%' AND related_code NOT LIKE '%IPPF2:%';
UPDATE drugs SET related_code = CONCAT(related_code, ';IPPF2:3130030000000') WHERE related_code IS NOT NULL AND related_code LIKE '%IPPF:371371433%' AND related_code NOT LIKE '%IPPF2:%';
UPDATE codes SET related_code = CONCAT(related_code, ';IPPF2:3130030000000') WHERE related_code IS NOT NULL AND related_code LIKE '%IPPF:371381332%' AND related_code NOT LIKE '%IPPF2:%';
UPDATE drugs SET related_code = CONCAT(related_code, ';IPPF2:3130030000000') WHERE related_code IS NOT NULL AND related_code LIKE '%IPPF:371381332%' AND related_code NOT LIKE '%IPPF2:%';
UPDATE codes SET related_code = CONCAT(related_code, ';IPPF2:3130030000000') WHERE related_code IS NOT NULL AND related_code LIKE '%IPPF:371381433%' AND related_code NOT LIKE '%IPPF2:%';
UPDATE drugs SET related_code = CONCAT(related_code, ';IPPF2:3130030000000') WHERE related_code IS NOT NULL AND related_code LIKE '%IPPF:371381433%' AND related_code NOT LIKE '%IPPF2:%';
UPDATE codes SET related_code = CONCAT(related_code, ';IPPF2:3130030000000') WHERE related_code IS NOT NULL AND related_code LIKE '%IPPF:371391332%' AND related_code NOT LIKE '%IPPF2:%';
UPDATE drugs SET related_code = CONCAT(related_code, ';IPPF2:3130030000000') WHERE related_code IS NOT NULL AND related_code LIKE '%IPPF:371391332%' AND related_code NOT LIKE '%IPPF2:%';
UPDATE codes SET related_code = CONCAT(related_code, ';IPPF2:3130030000000') WHERE related_code IS NOT NULL AND related_code LIKE '%IPPF:371391433%' AND related_code NOT LIKE '%IPPF2:%';
UPDATE drugs SET related_code = CONCAT(related_code, ';IPPF2:3130030000000') WHERE related_code IS NOT NULL AND related_code LIKE '%IPPF:371391433%' AND related_code NOT LIKE '%IPPF2:%';
UPDATE codes SET related_code = CONCAT(related_code, ';IPPF2:3130030000000') WHERE related_code IS NOT NULL AND related_code LIKE '%IPPF:371401332%' AND related_code NOT LIKE '%IPPF2:%';
UPDATE drugs SET related_code = CONCAT(related_code, ';IPPF2:3130030000000') WHERE related_code IS NOT NULL AND related_code LIKE '%IPPF:371401332%' AND related_code NOT LIKE '%IPPF2:%';
UPDATE codes SET related_code = CONCAT(related_code, ';IPPF2:3130030000000') WHERE related_code IS NOT NULL AND related_code LIKE '%IPPF:371401433%' AND related_code NOT LIKE '%IPPF2:%';
UPDATE drugs SET related_code = CONCAT(related_code, ';IPPF2:3130030000000') WHERE related_code IS NOT NULL AND related_code LIKE '%IPPF:371401433%' AND related_code NOT LIKE '%IPPF2:%';
UPDATE codes SET related_code = CONCAT(related_code, ';IPPF2:3130030000000') WHERE related_code IS NOT NULL AND related_code LIKE '%IPPF:371411332%' AND related_code NOT LIKE '%IPPF2:%';
UPDATE drugs SET related_code = CONCAT(related_code, ';IPPF2:3130030000000') WHERE related_code IS NOT NULL AND related_code LIKE '%IPPF:371411332%' AND related_code NOT LIKE '%IPPF2:%';
UPDATE codes SET related_code = CONCAT(related_code, ';IPPF2:3130030000000') WHERE related_code IS NOT NULL AND related_code LIKE '%IPPF:371411433%' AND related_code NOT LIKE '%IPPF2:%';
UPDATE drugs SET related_code = CONCAT(related_code, ';IPPF2:3130030000000') WHERE related_code IS NOT NULL AND related_code LIKE '%IPPF:371411433%' AND related_code NOT LIKE '%IPPF2:%';
UPDATE codes SET related_code = CONCAT(related_code, ';IPPF2:3130030000000') WHERE related_code IS NOT NULL AND related_code LIKE '%IPPF:371421332%' AND related_code NOT LIKE '%IPPF2:%';
UPDATE drugs SET related_code = CONCAT(related_code, ';IPPF2:3130030000000') WHERE related_code IS NOT NULL AND related_code LIKE '%IPPF:371421332%' AND related_code NOT LIKE '%IPPF2:%';
UPDATE codes SET related_code = CONCAT(related_code, ';IPPF2:3130030000000') WHERE related_code IS NOT NULL AND related_code LIKE '%IPPF:371421433%' AND related_code NOT LIKE '%IPPF2:%';
UPDATE drugs SET related_code = CONCAT(related_code, ';IPPF2:3130030000000') WHERE related_code IS NOT NULL AND related_code LIKE '%IPPF:371421433%' AND related_code NOT LIKE '%IPPF2:%';
UPDATE codes SET related_code = CONCAT(related_code, ';IPPF2:3130030000000') WHERE related_code IS NOT NULL AND related_code LIKE '%IPPF:371431332%' AND related_code NOT LIKE '%IPPF2:%';
UPDATE drugs SET related_code = CONCAT(related_code, ';IPPF2:3130030000000') WHERE related_code IS NOT NULL AND related_code LIKE '%IPPF:371431332%' AND related_code NOT LIKE '%IPPF2:%';
UPDATE codes SET related_code = CONCAT(related_code, ';IPPF2:3130030000000') WHERE related_code IS NOT NULL AND related_code LIKE '%IPPF:371431433%' AND related_code NOT LIKE '%IPPF2:%';
UPDATE drugs SET related_code = CONCAT(related_code, ';IPPF2:3130030000000') WHERE related_code IS NOT NULL AND related_code LIKE '%IPPF:371431433%' AND related_code NOT LIKE '%IPPF2:%';
UPDATE codes SET related_code = CONCAT(related_code, ';IPPF2:3130030000000') WHERE related_code IS NOT NULL AND related_code LIKE '%IPPF:371441332%' AND related_code NOT LIKE '%IPPF2:%';
UPDATE drugs SET related_code = CONCAT(related_code, ';IPPF2:3130030000000') WHERE related_code IS NOT NULL AND related_code LIKE '%IPPF:371441332%' AND related_code NOT LIKE '%IPPF2:%';
UPDATE codes SET related_code = CONCAT(related_code, ';IPPF2:3130030000000') WHERE related_code IS NOT NULL AND related_code LIKE '%IPPF:371441433%' AND related_code NOT LIKE '%IPPF2:%';
UPDATE drugs SET related_code = CONCAT(related_code, ';IPPF2:3130030000000') WHERE related_code IS NOT NULL AND related_code LIKE '%IPPF:371441433%' AND related_code NOT LIKE '%IPPF2:%';
UPDATE codes SET related_code = CONCAT(related_code, ';IPPF2:3130030000000') WHERE related_code IS NOT NULL AND related_code LIKE '%IPPF:372531232%' AND related_code NOT LIKE '%IPPF2:%';
UPDATE drugs SET related_code = CONCAT(related_code, ';IPPF2:3130030000000') WHERE related_code IS NOT NULL AND related_code LIKE '%IPPF:372531232%' AND related_code NOT LIKE '%IPPF2:%';
UPDATE codes SET related_code = CONCAT(related_code, ';IPPF2:3130030000000') WHERE related_code IS NOT NULL AND related_code LIKE '%IPPF:372541232%' AND related_code NOT LIKE '%IPPF2:%';
UPDATE drugs SET related_code = CONCAT(related_code, ';IPPF2:3130030000000') WHERE related_code IS NOT NULL AND related_code LIKE '%IPPF:372541232%' AND related_code NOT LIKE '%IPPF2:%';
UPDATE codes SET related_code = CONCAT(related_code, ';IPPF2:3130030000000') WHERE related_code IS NOT NULL AND related_code LIKE '%IPPF:372541333%' AND related_code NOT LIKE '%IPPF2:%';
UPDATE drugs SET related_code = CONCAT(related_code, ';IPPF2:3130030000000') WHERE related_code IS NOT NULL AND related_code LIKE '%IPPF:372541333%' AND related_code NOT LIKE '%IPPF2:%';
UPDATE codes SET related_code = CONCAT(related_code, ';IPPF2:3130030000000') WHERE related_code IS NOT NULL AND related_code LIKE '%IPPF:372551332%' AND related_code NOT LIKE '%IPPF2:%';
UPDATE drugs SET related_code = CONCAT(related_code, ';IPPF2:3130030000000') WHERE related_code IS NOT NULL AND related_code LIKE '%IPPF:372551332%' AND related_code NOT LIKE '%IPPF2:%';
UPDATE codes SET related_code = CONCAT(related_code, ';IPPF2:3130030000000') WHERE related_code IS NOT NULL AND related_code LIKE '%IPPF:373621332%' AND related_code NOT LIKE '%IPPF2:%';
UPDATE drugs SET related_code = CONCAT(related_code, ';IPPF2:3130030000000') WHERE related_code IS NOT NULL AND related_code LIKE '%IPPF:373621332%' AND related_code NOT LIKE '%IPPF2:%';
UPDATE codes SET related_code = CONCAT(related_code, ';IPPF2:3130030000000') WHERE related_code IS NOT NULL AND related_code LIKE '%IPPF:373661332%' AND related_code NOT LIKE '%IPPF2:%';
UPDATE drugs SET related_code = CONCAT(related_code, ';IPPF2:3130030000000') WHERE related_code IS NOT NULL AND related_code LIKE '%IPPF:373661332%' AND related_code NOT LIKE '%IPPF2:%';
UPDATE codes SET related_code = CONCAT(related_code, ';IPPF2:3130030000000') WHERE related_code IS NOT NULL AND related_code LIKE '%IPPF:373661433%' AND related_code NOT LIKE '%IPPF2:%';
UPDATE drugs SET related_code = CONCAT(related_code, ';IPPF2:3130030000000') WHERE related_code IS NOT NULL AND related_code LIKE '%IPPF:373661433%' AND related_code NOT LIKE '%IPPF2:%';
UPDATE codes SET related_code = CONCAT(related_code, ';IPPF2:3130030000000') WHERE related_code IS NOT NULL AND related_code LIKE '%IPPF:373681333%' AND related_code NOT LIKE '%IPPF2:%';
UPDATE drugs SET related_code = CONCAT(related_code, ';IPPF2:3130030000000') WHERE related_code IS NOT NULL AND related_code LIKE '%IPPF:373681333%' AND related_code NOT LIKE '%IPPF2:%';
UPDATE codes SET related_code = CONCAT(related_code, ';IPPF2:3130030000000') WHERE related_code IS NOT NULL AND related_code LIKE '%IPPF:374701332%' AND related_code NOT LIKE '%IPPF2:%';
UPDATE drugs SET related_code = CONCAT(related_code, ';IPPF2:3130030000000') WHERE related_code IS NOT NULL AND related_code LIKE '%IPPF:374701332%' AND related_code NOT LIKE '%IPPF2:%';
UPDATE codes SET related_code = CONCAT(related_code, ';IPPF2:3130030000000') WHERE related_code IS NOT NULL AND related_code LIKE '%IPPF:374721332%' AND related_code NOT LIKE '%IPPF2:%';
UPDATE drugs SET related_code = CONCAT(related_code, ';IPPF2:3130030000000') WHERE related_code IS NOT NULL AND related_code LIKE '%IPPF:374721332%' AND related_code NOT LIKE '%IPPF2:%';
UPDATE codes SET related_code = CONCAT(related_code, ';IPPF2:3130030000000') WHERE related_code IS NOT NULL AND related_code LIKE '%IPPF:375811232%' AND related_code NOT LIKE '%IPPF2:%';
UPDATE drugs SET related_code = CONCAT(related_code, ';IPPF2:3130030000000') WHERE related_code IS NOT NULL AND related_code LIKE '%IPPF:375811232%' AND related_code NOT LIKE '%IPPF2:%';
UPDATE codes SET related_code = CONCAT(related_code, ';IPPF2:3130030000000') WHERE related_code IS NOT NULL AND related_code LIKE '%IPPF:375821232%' AND related_code NOT LIKE '%IPPF2:%';
UPDATE drugs SET related_code = CONCAT(related_code, ';IPPF2:3130030000000') WHERE related_code IS NOT NULL AND related_code LIKE '%IPPF:375821232%' AND related_code NOT LIKE '%IPPF2:%';
UPDATE codes SET related_code = CONCAT(related_code, ';IPPF2:3130030000000') WHERE related_code IS NOT NULL AND related_code LIKE '%IPPF:375821333%' AND related_code NOT LIKE '%IPPF2:%';
UPDATE drugs SET related_code = CONCAT(related_code, ';IPPF2:3130030000000') WHERE related_code IS NOT NULL AND related_code LIKE '%IPPF:375821333%' AND related_code NOT LIKE '%IPPF2:%';
UPDATE codes SET related_code = CONCAT(related_code, ';IPPF2:3130030000000') WHERE related_code IS NOT NULL AND related_code LIKE '%IPPF:375831332%' AND related_code NOT LIKE '%IPPF2:%';
UPDATE drugs SET related_code = CONCAT(related_code, ';IPPF2:3130030000000') WHERE related_code IS NOT NULL AND related_code LIKE '%IPPF:375831332%' AND related_code NOT LIKE '%IPPF2:%';
UPDATE codes SET related_code = CONCAT(related_code, ';IPPF2:3130030000000') WHERE related_code IS NOT NULL AND related_code LIKE '%IPPF:375831433%' AND related_code NOT LIKE '%IPPF2:%';
UPDATE drugs SET related_code = CONCAT(related_code, ';IPPF2:3130030000000') WHERE related_code IS NOT NULL AND related_code LIKE '%IPPF:375831433%' AND related_code NOT LIKE '%IPPF2:%';
UPDATE codes SET related_code = CONCAT(related_code, ';IPPF2:3130030000000') WHERE related_code IS NOT NULL AND related_code LIKE '%IPPF:372511232%' AND related_code NOT LIKE '%IPPF2:%';
UPDATE drugs SET related_code = CONCAT(related_code, ';IPPF2:3130030000000') WHERE related_code IS NOT NULL AND related_code LIKE '%IPPF:372511232%' AND related_code NOT LIKE '%IPPF2:%';
UPDATE codes SET related_code = CONCAT(related_code, ';IPPF2:3130030000000') WHERE related_code IS NOT NULL AND related_code LIKE '%IPPF:372521232%' AND related_code NOT LIKE '%IPPF2:%';
UPDATE drugs SET related_code = CONCAT(related_code, ';IPPF2:3130030000000') WHERE related_code IS NOT NULL AND related_code LIKE '%IPPF:372521232%' AND related_code NOT LIKE '%IPPF2:%';
UPDATE codes SET related_code = CONCAT(related_code, ';IPPF2:3130030000000') WHERE related_code IS NOT NULL AND related_code LIKE '%IPPF:375801232%' AND related_code NOT LIKE '%IPPF2:%';
UPDATE drugs SET related_code = CONCAT(related_code, ';IPPF2:3130030000000') WHERE related_code IS NOT NULL AND related_code LIKE '%IPPF:375801232%' AND related_code NOT LIKE '%IPPF2:%';
UPDATE codes SET related_code = CONCAT(related_code, ';IPPF2:3130030000000') WHERE related_code IS NOT NULL AND related_code LIKE '%IPPF:375821999%' AND related_code NOT LIKE '%IPPF2:%';
UPDATE drugs SET related_code = CONCAT(related_code, ';IPPF2:3130030000000') WHERE related_code IS NOT NULL AND related_code LIKE '%IPPF:375821999%' AND related_code NOT LIKE '%IPPF2:%';
UPDATE codes SET related_code = CONCAT(related_code, ';IPPF2:3130030000000') WHERE related_code IS NOT NULL AND related_code LIKE '%IPPF:373681232%' AND related_code NOT LIKE '%IPPF2:%';
UPDATE drugs SET related_code = CONCAT(related_code, ';IPPF2:3130030000000') WHERE related_code IS NOT NULL AND related_code LIKE '%IPPF:373681232%' AND related_code NOT LIKE '%IPPF2:%';
UPDATE codes SET related_code = CONCAT(related_code, ';IPPF2:3130030000000') WHERE related_code IS NOT NULL AND related_code LIKE '%IPPF:373691140%' AND related_code NOT LIKE '%IPPF2:%';
UPDATE drugs SET related_code = CONCAT(related_code, ';IPPF2:3130030000000') WHERE related_code IS NOT NULL AND related_code LIKE '%IPPF:373691140%' AND related_code NOT LIKE '%IPPF2:%';
UPDATE codes SET related_code = CONCAT(related_code, ';IPPF2:3130030000000') WHERE related_code IS NOT NULL AND related_code LIKE '%IPPF:373691241%' AND related_code NOT LIKE '%IPPF2:%';
UPDATE drugs SET related_code = CONCAT(related_code, ';IPPF2:3130030000000') WHERE related_code IS NOT NULL AND related_code LIKE '%IPPF:373691241%' AND related_code NOT LIKE '%IPPF2:%';
UPDATE codes SET related_code = CONCAT(related_code, ';IPPF2:3140040000000') WHERE related_code IS NOT NULL AND related_code LIKE '%IPPF:376101132%' AND related_code NOT LIKE '%IPPF2:%';
UPDATE drugs SET related_code = CONCAT(related_code, ';IPPF2:3140040000000') WHERE related_code IS NOT NULL AND related_code LIKE '%IPPF:376101132%' AND related_code NOT LIKE '%IPPF2:%';
UPDATE codes SET related_code = CONCAT(related_code, ';IPPF2:3140040000000') WHERE related_code IS NOT NULL AND related_code LIKE '%IPPF:376101232%' AND related_code NOT LIKE '%IPPF2:%';
UPDATE drugs SET related_code = CONCAT(related_code, ';IPPF2:3140040000000') WHERE related_code IS NOT NULL AND related_code LIKE '%IPPF:376101232%' AND related_code NOT LIKE '%IPPF2:%';
UPDATE codes SET related_code = CONCAT(related_code, ';IPPF2:3140040000000') WHERE related_code IS NOT NULL AND related_code LIKE '%IPPF:376101999%' AND related_code NOT LIKE '%IPPF2:%';
UPDATE drugs SET related_code = CONCAT(related_code, ';IPPF2:3140040000000') WHERE related_code IS NOT NULL AND related_code LIKE '%IPPF:376101999%' AND related_code NOT LIKE '%IPPF2:%';
UPDATE codes SET related_code = CONCAT(related_code, ';IPPF2:3140040402201') WHERE related_code IS NOT NULL AND related_code LIKE '%IPPF:373601431%' AND related_code NOT LIKE '%IPPF2:%';
UPDATE drugs SET related_code = CONCAT(related_code, ';IPPF2:3140040402201') WHERE related_code IS NOT NULL AND related_code LIKE '%IPPF:373601431%' AND related_code NOT LIKE '%IPPF2:%';
UPDATE codes SET related_code = CONCAT(related_code, ';IPPF2:3140040402202') WHERE related_code IS NOT NULL AND related_code LIKE '%IPPF:373601131%' AND related_code NOT LIKE '%IPPF2:%';
UPDATE drugs SET related_code = CONCAT(related_code, ';IPPF2:3140040402202') WHERE related_code IS NOT NULL AND related_code LIKE '%IPPF:373601131%' AND related_code NOT LIKE '%IPPF2:%';
UPDATE codes SET related_code = CONCAT(related_code, ';IPPF2:3140040402203') WHERE related_code IS NOT NULL AND related_code LIKE '%IPPF:373601631%' AND related_code NOT LIKE '%IPPF2:%';
UPDATE drugs SET related_code = CONCAT(related_code, ';IPPF2:3140040402203') WHERE related_code IS NOT NULL AND related_code LIKE '%IPPF:373601631%' AND related_code NOT LIKE '%IPPF2:%';
UPDATE codes SET related_code = CONCAT(related_code, ';IPPF2:3150050000000') WHERE related_code IS NOT NULL AND related_code LIKE '%IPPF:371361331%' AND related_code NOT LIKE '%IPPF2:%';
UPDATE drugs SET related_code = CONCAT(related_code, ';IPPF2:3150050000000') WHERE related_code IS NOT NULL AND related_code LIKE '%IPPF:371361331%' AND related_code NOT LIKE '%IPPF2:%';
UPDATE codes SET related_code = CONCAT(related_code, ';IPPF2:3150050000000') WHERE related_code IS NOT NULL AND related_code LIKE '%IPPF:371301231%' AND related_code NOT LIKE '%IPPF2:%';
UPDATE drugs SET related_code = CONCAT(related_code, ';IPPF2:3150050000000') WHERE related_code IS NOT NULL AND related_code LIKE '%IPPF:371301231%' AND related_code NOT LIKE '%IPPF2:%';
UPDATE codes SET related_code = CONCAT(related_code, ';IPPF2:3150050000000') WHERE related_code IS NOT NULL AND related_code LIKE '%IPPF:371331231%' AND related_code NOT LIKE '%IPPF2:%';
UPDATE drugs SET related_code = CONCAT(related_code, ';IPPF2:3150050000000') WHERE related_code IS NOT NULL AND related_code LIKE '%IPPF:371331231%' AND related_code NOT LIKE '%IPPF2:%';
UPDATE codes SET related_code = CONCAT(related_code, ';IPPF2:3150050000000') WHERE related_code IS NOT NULL AND related_code LIKE '%IPPF:371341231%' AND related_code NOT LIKE '%IPPF2:%';
UPDATE drugs SET related_code = CONCAT(related_code, ';IPPF2:3150050000000') WHERE related_code IS NOT NULL AND related_code LIKE '%IPPF:371341231%' AND related_code NOT LIKE '%IPPF2:%';
UPDATE codes SET related_code = CONCAT(related_code, ';IPPF2:3150050000000') WHERE related_code IS NOT NULL AND related_code LIKE '%IPPF:371351231%' AND related_code NOT LIKE '%IPPF2:%';
UPDATE drugs SET related_code = CONCAT(related_code, ';IPPF2:3150050000000') WHERE related_code IS NOT NULL AND related_code LIKE '%IPPF:371351231%' AND related_code NOT LIKE '%IPPF2:%';
UPDATE codes SET related_code = CONCAT(related_code, ';IPPF2:3150050000000') WHERE related_code IS NOT NULL AND related_code LIKE '%IPPF:371371231%' AND related_code NOT LIKE '%IPPF2:%';
UPDATE drugs SET related_code = CONCAT(related_code, ';IPPF2:3150050000000') WHERE related_code IS NOT NULL AND related_code LIKE '%IPPF:371371231%' AND related_code NOT LIKE '%IPPF2:%';
UPDATE codes SET related_code = CONCAT(related_code, ';IPPF2:3150050000000') WHERE related_code IS NOT NULL AND related_code LIKE '%IPPF:371381231%' AND related_code NOT LIKE '%IPPF2:%';
UPDATE drugs SET related_code = CONCAT(related_code, ';IPPF2:3150050000000') WHERE related_code IS NOT NULL AND related_code LIKE '%IPPF:371381231%' AND related_code NOT LIKE '%IPPF2:%';
UPDATE codes SET related_code = CONCAT(related_code, ';IPPF2:3150050000000') WHERE related_code IS NOT NULL AND related_code LIKE '%IPPF:372541131%' AND related_code NOT LIKE '%IPPF2:%';
UPDATE drugs SET related_code = CONCAT(related_code, ';IPPF2:3150050000000') WHERE related_code IS NOT NULL AND related_code LIKE '%IPPF:372541131%' AND related_code NOT LIKE '%IPPF2:%';
UPDATE codes SET related_code = CONCAT(related_code, ';IPPF2:3150050000000') WHERE related_code IS NOT NULL AND related_code LIKE '%IPPF:372551231%' AND related_code NOT LIKE '%IPPF2:%';
UPDATE drugs SET related_code = CONCAT(related_code, ';IPPF2:3150050000000') WHERE related_code IS NOT NULL AND related_code LIKE '%IPPF:372551231%' AND related_code NOT LIKE '%IPPF2:%';
UPDATE codes SET related_code = CONCAT(related_code, ';IPPF2:3150050000000') WHERE related_code IS NOT NULL AND related_code LIKE '%IPPF:372561231%' AND related_code NOT LIKE '%IPPF2:%';
UPDATE drugs SET related_code = CONCAT(related_code, ';IPPF2:3150050000000') WHERE related_code IS NOT NULL AND related_code LIKE '%IPPF:372561231%' AND related_code NOT LIKE '%IPPF2:%';
UPDATE codes SET related_code = CONCAT(related_code, ';IPPF2:3150050000000') WHERE related_code IS NOT NULL AND related_code LIKE '%IPPF:373621231%' AND related_code NOT LIKE '%IPPF2:%';
UPDATE drugs SET related_code = CONCAT(related_code, ';IPPF2:3150050000000') WHERE related_code IS NOT NULL AND related_code LIKE '%IPPF:373621231%' AND related_code NOT LIKE '%IPPF2:%';
UPDATE codes SET related_code = CONCAT(related_code, ';IPPF2:3150050000000') WHERE related_code IS NOT NULL AND related_code LIKE '%IPPF:373661231%' AND related_code NOT LIKE '%IPPF2:%';
UPDATE drugs SET related_code = CONCAT(related_code, ';IPPF2:3150050000000') WHERE related_code IS NOT NULL AND related_code LIKE '%IPPF:373661231%' AND related_code NOT LIKE '%IPPF2:%';
UPDATE codes SET related_code = CONCAT(related_code, ';IPPF2:3150050000000') WHERE related_code IS NOT NULL AND related_code LIKE '%IPPF:374701231%' AND related_code NOT LIKE '%IPPF2:%';
UPDATE drugs SET related_code = CONCAT(related_code, ';IPPF2:3150050000000') WHERE related_code IS NOT NULL AND related_code LIKE '%IPPF:374701231%' AND related_code NOT LIKE '%IPPF2:%';
UPDATE codes SET related_code = CONCAT(related_code, ';IPPF2:3150050000000') WHERE related_code IS NOT NULL AND related_code LIKE '%IPPF:374721231%' AND related_code NOT LIKE '%IPPF2:%';
UPDATE drugs SET related_code = CONCAT(related_code, ';IPPF2:3150050000000') WHERE related_code IS NOT NULL AND related_code LIKE '%IPPF:374721231%' AND related_code NOT LIKE '%IPPF2:%';
UPDATE codes SET related_code = CONCAT(related_code, ';IPPF2:3150050000000') WHERE related_code IS NOT NULL AND related_code LIKE '%IPPF:374741231%' AND related_code NOT LIKE '%IPPF2:%';
UPDATE drugs SET related_code = CONCAT(related_code, ';IPPF2:3150050000000') WHERE related_code IS NOT NULL AND related_code LIKE '%IPPF:374741231%' AND related_code NOT LIKE '%IPPF2:%';
UPDATE codes SET related_code = CONCAT(related_code, ';IPPF2:3150050000000') WHERE related_code IS NOT NULL AND related_code LIKE '%IPPF:374761231%' AND related_code NOT LIKE '%IPPF2:%';
UPDATE drugs SET related_code = CONCAT(related_code, ';IPPF2:3150050000000') WHERE related_code IS NOT NULL AND related_code LIKE '%IPPF:374761231%' AND related_code NOT LIKE '%IPPF2:%';
UPDATE codes SET related_code = CONCAT(related_code, ';IPPF2:3150050000000') WHERE related_code IS NOT NULL AND related_code LIKE '%IPPF:374781231%' AND related_code NOT LIKE '%IPPF2:%';
UPDATE drugs SET related_code = CONCAT(related_code, ';IPPF2:3150050000000') WHERE related_code IS NOT NULL AND related_code LIKE '%IPPF:374781231%' AND related_code NOT LIKE '%IPPF2:%';
UPDATE codes SET related_code = CONCAT(related_code, ';IPPF2:3150050000000') WHERE related_code IS NOT NULL AND related_code LIKE '%IPPF:375811331%' AND related_code NOT LIKE '%IPPF2:%';
UPDATE drugs SET related_code = CONCAT(related_code, ';IPPF2:3150050000000') WHERE related_code IS NOT NULL AND related_code LIKE '%IPPF:375811331%' AND related_code NOT LIKE '%IPPF2:%';
UPDATE codes SET related_code = CONCAT(related_code, ';IPPF2:3150050000000') WHERE related_code IS NOT NULL AND related_code LIKE '%IPPF:375831231%' AND related_code NOT LIKE '%IPPF2:%';
UPDATE drugs SET related_code = CONCAT(related_code, ';IPPF2:3150050000000') WHERE related_code IS NOT NULL AND related_code LIKE '%IPPF:375831231%' AND related_code NOT LIKE '%IPPF2:%';
UPDATE codes SET related_code = CONCAT(related_code, ';IPPF2:3150050000000') WHERE related_code IS NOT NULL AND related_code LIKE '%IPPF:372531131%' AND related_code NOT LIKE '%IPPF2:%';
UPDATE drugs SET related_code = CONCAT(related_code, ';IPPF2:3150050000000') WHERE related_code IS NOT NULL AND related_code LIKE '%IPPF:372531131%' AND related_code NOT LIKE '%IPPF2:%';
UPDATE codes SET related_code = CONCAT(related_code, ';IPPF2:3150050000000') WHERE related_code IS NOT NULL AND related_code LIKE '%IPPF:373681131%' AND related_code NOT LIKE '%IPPF2:%';
UPDATE drugs SET related_code = CONCAT(related_code, ';IPPF2:3150050000000') WHERE related_code IS NOT NULL AND related_code LIKE '%IPPF:373681131%' AND related_code NOT LIKE '%IPPF2:%';
UPDATE codes SET related_code = CONCAT(related_code, ';IPPF2:3150050000000') WHERE related_code IS NOT NULL AND related_code LIKE '%IPPF:371311231%' AND related_code NOT LIKE '%IPPF2:%';
UPDATE drugs SET related_code = CONCAT(related_code, ';IPPF2:3150050000000') WHERE related_code IS NOT NULL AND related_code LIKE '%IPPF:371311231%' AND related_code NOT LIKE '%IPPF2:%';
UPDATE codes SET related_code = CONCAT(related_code, ';IPPF2:3150050000000') WHERE related_code IS NOT NULL AND related_code LIKE '%IPPF:371321131%' AND related_code NOT LIKE '%IPPF2:%';
UPDATE drugs SET related_code = CONCAT(related_code, ';IPPF2:3150050000000') WHERE related_code IS NOT NULL AND related_code LIKE '%IPPF:371321131%' AND related_code NOT LIKE '%IPPF2:%';
UPDATE codes SET related_code = CONCAT(related_code, ';IPPF2:3150050000000') WHERE related_code IS NOT NULL AND related_code LIKE '%IPPF:371391231%' AND related_code NOT LIKE '%IPPF2:%';
UPDATE drugs SET related_code = CONCAT(related_code, ';IPPF2:3150050000000') WHERE related_code IS NOT NULL AND related_code LIKE '%IPPF:371391231%' AND related_code NOT LIKE '%IPPF2:%';
UPDATE codes SET related_code = CONCAT(related_code, ';IPPF2:3150050000000') WHERE related_code IS NOT NULL AND related_code LIKE '%IPPF:371401231%' AND related_code NOT LIKE '%IPPF2:%';
UPDATE drugs SET related_code = CONCAT(related_code, ';IPPF2:3150050000000') WHERE related_code IS NOT NULL AND related_code LIKE '%IPPF:371401231%' AND related_code NOT LIKE '%IPPF2:%';
UPDATE codes SET related_code = CONCAT(related_code, ';IPPF2:3150050000000') WHERE related_code IS NOT NULL AND related_code LIKE '%IPPF:371411231%' AND related_code NOT LIKE '%IPPF2:%';
UPDATE drugs SET related_code = CONCAT(related_code, ';IPPF2:3150050000000') WHERE related_code IS NOT NULL AND related_code LIKE '%IPPF:371411231%' AND related_code NOT LIKE '%IPPF2:%';
UPDATE codes SET related_code = CONCAT(related_code, ';IPPF2:3150050000000') WHERE related_code IS NOT NULL AND related_code LIKE '%IPPF:371421231%' AND related_code NOT LIKE '%IPPF2:%';
UPDATE drugs SET related_code = CONCAT(related_code, ';IPPF2:3150050000000') WHERE related_code IS NOT NULL AND related_code LIKE '%IPPF:371421231%' AND related_code NOT LIKE '%IPPF2:%';
UPDATE codes SET related_code = CONCAT(related_code, ';IPPF2:3150050000000') WHERE related_code IS NOT NULL AND related_code LIKE '%IPPF:371431231%' AND related_code NOT LIKE '%IPPF2:%';
UPDATE drugs SET related_code = CONCAT(related_code, ';IPPF2:3150050000000') WHERE related_code IS NOT NULL AND related_code LIKE '%IPPF:371431231%' AND related_code NOT LIKE '%IPPF2:%';
UPDATE codes SET related_code = CONCAT(related_code, ';IPPF2:3150050000000') WHERE related_code IS NOT NULL AND related_code LIKE '%IPPF:371441231%' AND related_code NOT LIKE '%IPPF2:%';
UPDATE drugs SET related_code = CONCAT(related_code, ';IPPF2:3150050000000') WHERE related_code IS NOT NULL AND related_code LIKE '%IPPF:371441231%' AND related_code NOT LIKE '%IPPF2:%';
UPDATE codes SET related_code = CONCAT(related_code, ';IPPF2:3150050000000') WHERE related_code IS NOT NULL AND related_code LIKE '%IPPF:372501231%' AND related_code NOT LIKE '%IPPF2:%';
UPDATE drugs SET related_code = CONCAT(related_code, ';IPPF2:3150050000000') WHERE related_code IS NOT NULL AND related_code LIKE '%IPPF:372501231%' AND related_code NOT LIKE '%IPPF2:%';
UPDATE codes SET related_code = CONCAT(related_code, ';IPPF2:3150050000000') WHERE related_code IS NOT NULL AND related_code LIKE '%IPPF:373601231%' AND related_code NOT LIKE '%IPPF2:%';
UPDATE drugs SET related_code = CONCAT(related_code, ';IPPF2:3150050000000') WHERE related_code IS NOT NULL AND related_code LIKE '%IPPF:373601231%' AND related_code NOT LIKE '%IPPF2:%';
UPDATE codes SET related_code = CONCAT(related_code, ';IPPF2:3150050000000') WHERE related_code IS NOT NULL AND related_code LIKE '%IPPF:373601331%' AND related_code NOT LIKE '%IPPF2:%';
UPDATE drugs SET related_code = CONCAT(related_code, ';IPPF2:3150050000000') WHERE related_code IS NOT NULL AND related_code LIKE '%IPPF:373601331%' AND related_code NOT LIKE '%IPPF2:%';
UPDATE codes SET related_code = CONCAT(related_code, ';IPPF2:3150050000000') WHERE related_code IS NOT NULL AND related_code LIKE '%IPPF:373601531%' AND related_code NOT LIKE '%IPPF2:%';
UPDATE drugs SET related_code = CONCAT(related_code, ';IPPF2:3150050000000') WHERE related_code IS NOT NULL AND related_code LIKE '%IPPF:373601531%' AND related_code NOT LIKE '%IPPF2:%';
UPDATE codes SET related_code = CONCAT(related_code, ';IPPF2:3150000000800') WHERE related_code IS NOT NULL AND related_code LIKE '%IPPF:371300999%' AND related_code NOT LIKE '%IPPF2:%';
UPDATE drugs SET related_code = CONCAT(related_code, ';IPPF2:3150000000800') WHERE related_code IS NOT NULL AND related_code LIKE '%IPPF:371300999%' AND related_code NOT LIKE '%IPPF2:%';
UPDATE codes SET related_code = CONCAT(related_code, ';IPPF2:3150000000800') WHERE related_code IS NOT NULL AND related_code LIKE '%IPPF:371301999%' AND related_code NOT LIKE '%IPPF2:%';
UPDATE drugs SET related_code = CONCAT(related_code, ';IPPF2:3150000000800') WHERE related_code IS NOT NULL AND related_code LIKE '%IPPF:371301999%' AND related_code NOT LIKE '%IPPF2:%';
UPDATE codes SET related_code = CONCAT(related_code, ';IPPF2:3150000000800') WHERE related_code IS NOT NULL AND related_code LIKE '%IPPF:371311999%' AND related_code NOT LIKE '%IPPF2:%';
UPDATE drugs SET related_code = CONCAT(related_code, ';IPPF2:3150000000800') WHERE related_code IS NOT NULL AND related_code LIKE '%IPPF:371311999%' AND related_code NOT LIKE '%IPPF2:%';
UPDATE codes SET related_code = CONCAT(related_code, ';IPPF2:3150000000800') WHERE related_code IS NOT NULL AND related_code LIKE '%IPPF:371321999%' AND related_code NOT LIKE '%IPPF2:%';
UPDATE drugs SET related_code = CONCAT(related_code, ';IPPF2:3150000000800') WHERE related_code IS NOT NULL AND related_code LIKE '%IPPF:371321999%' AND related_code NOT LIKE '%IPPF2:%';
UPDATE codes SET related_code = CONCAT(related_code, ';IPPF2:3150000000800') WHERE related_code IS NOT NULL AND related_code LIKE '%IPPF:371331999%' AND related_code NOT LIKE '%IPPF2:%';
UPDATE drugs SET related_code = CONCAT(related_code, ';IPPF2:3150000000800') WHERE related_code IS NOT NULL AND related_code LIKE '%IPPF:371331999%' AND related_code NOT LIKE '%IPPF2:%';
UPDATE codes SET related_code = CONCAT(related_code, ';IPPF2:3150000000800') WHERE related_code IS NOT NULL AND related_code LIKE '%IPPF:371341999%' AND related_code NOT LIKE '%IPPF2:%';
UPDATE drugs SET related_code = CONCAT(related_code, ';IPPF2:3150000000800') WHERE related_code IS NOT NULL AND related_code LIKE '%IPPF:371341999%' AND related_code NOT LIKE '%IPPF2:%';
UPDATE codes SET related_code = CONCAT(related_code, ';IPPF2:3150000000800') WHERE related_code IS NOT NULL AND related_code LIKE '%IPPF:371351999%' AND related_code NOT LIKE '%IPPF2:%';
UPDATE drugs SET related_code = CONCAT(related_code, ';IPPF2:3150000000800') WHERE related_code IS NOT NULL AND related_code LIKE '%IPPF:371351999%' AND related_code NOT LIKE '%IPPF2:%';
UPDATE codes SET related_code = CONCAT(related_code, ';IPPF2:3150000000800') WHERE related_code IS NOT NULL AND related_code LIKE '%IPPF:371361999%' AND related_code NOT LIKE '%IPPF2:%';
UPDATE drugs SET related_code = CONCAT(related_code, ';IPPF2:3150000000800') WHERE related_code IS NOT NULL AND related_code LIKE '%IPPF:371361999%' AND related_code NOT LIKE '%IPPF2:%';
UPDATE codes SET related_code = CONCAT(related_code, ';IPPF2:3150000000800') WHERE related_code IS NOT NULL AND related_code LIKE '%IPPF:371371999%' AND related_code NOT LIKE '%IPPF2:%';
UPDATE drugs SET related_code = CONCAT(related_code, ';IPPF2:3150000000800') WHERE related_code IS NOT NULL AND related_code LIKE '%IPPF:371371999%' AND related_code NOT LIKE '%IPPF2:%';
UPDATE codes SET related_code = CONCAT(related_code, ';IPPF2:3150000000800') WHERE related_code IS NOT NULL AND related_code LIKE '%IPPF:371381999%' AND related_code NOT LIKE '%IPPF2:%';
UPDATE drugs SET related_code = CONCAT(related_code, ';IPPF2:3150000000800') WHERE related_code IS NOT NULL AND related_code LIKE '%IPPF:371381999%' AND related_code NOT LIKE '%IPPF2:%';
UPDATE codes SET related_code = CONCAT(related_code, ';IPPF2:3150000000800') WHERE related_code IS NOT NULL AND related_code LIKE '%IPPF:371391999%' AND related_code NOT LIKE '%IPPF2:%';
UPDATE drugs SET related_code = CONCAT(related_code, ';IPPF2:3150000000800') WHERE related_code IS NOT NULL AND related_code LIKE '%IPPF:371391999%' AND related_code NOT LIKE '%IPPF2:%';
UPDATE codes SET related_code = CONCAT(related_code, ';IPPF2:3150000000800') WHERE related_code IS NOT NULL AND related_code LIKE '%IPPF:371401999%' AND related_code NOT LIKE '%IPPF2:%';
UPDATE drugs SET related_code = CONCAT(related_code, ';IPPF2:3150000000800') WHERE related_code IS NOT NULL AND related_code LIKE '%IPPF:371401999%' AND related_code NOT LIKE '%IPPF2:%';
UPDATE codes SET related_code = CONCAT(related_code, ';IPPF2:3150000000800') WHERE related_code IS NOT NULL AND related_code LIKE '%IPPF:371411999%' AND related_code NOT LIKE '%IPPF2:%';
UPDATE drugs SET related_code = CONCAT(related_code, ';IPPF2:3150000000800') WHERE related_code IS NOT NULL AND related_code LIKE '%IPPF:371411999%' AND related_code NOT LIKE '%IPPF2:%';
UPDATE codes SET related_code = CONCAT(related_code, ';IPPF2:3150000000800') WHERE related_code IS NOT NULL AND related_code LIKE '%IPPF:371421999%' AND related_code NOT LIKE '%IPPF2:%';
UPDATE drugs SET related_code = CONCAT(related_code, ';IPPF2:3150000000800') WHERE related_code IS NOT NULL AND related_code LIKE '%IPPF:371421999%' AND related_code NOT LIKE '%IPPF2:%';
UPDATE codes SET related_code = CONCAT(related_code, ';IPPF2:3150000000800') WHERE related_code IS NOT NULL AND related_code LIKE '%IPPF:371431999%' AND related_code NOT LIKE '%IPPF2:%';
UPDATE drugs SET related_code = CONCAT(related_code, ';IPPF2:3150000000800') WHERE related_code IS NOT NULL AND related_code LIKE '%IPPF:371431999%' AND related_code NOT LIKE '%IPPF2:%';
UPDATE codes SET related_code = CONCAT(related_code, ';IPPF2:3150000000800') WHERE related_code IS NOT NULL AND related_code LIKE '%IPPF:371441999%' AND related_code NOT LIKE '%IPPF2:%';
UPDATE drugs SET related_code = CONCAT(related_code, ';IPPF2:3150000000800') WHERE related_code IS NOT NULL AND related_code LIKE '%IPPF:371441999%' AND related_code NOT LIKE '%IPPF2:%';
UPDATE codes SET related_code = CONCAT(related_code, ';IPPF2:3150000000800') WHERE related_code IS NOT NULL AND related_code LIKE '%IPPF:372500999%' AND related_code NOT LIKE '%IPPF2:%';
UPDATE drugs SET related_code = CONCAT(related_code, ';IPPF2:3150000000800') WHERE related_code IS NOT NULL AND related_code LIKE '%IPPF:372500999%' AND related_code NOT LIKE '%IPPF2:%';
UPDATE codes SET related_code = CONCAT(related_code, ';IPPF2:3150000000800') WHERE related_code IS NOT NULL AND related_code LIKE '%IPPF:372501999%' AND related_code NOT LIKE '%IPPF2:%';
UPDATE drugs SET related_code = CONCAT(related_code, ';IPPF2:3150000000800') WHERE related_code IS NOT NULL AND related_code LIKE '%IPPF:372501999%' AND related_code NOT LIKE '%IPPF2:%';
UPDATE codes SET related_code = CONCAT(related_code, ';IPPF2:3150000000800') WHERE related_code IS NOT NULL AND related_code LIKE '%IPPF:372511999%' AND related_code NOT LIKE '%IPPF2:%';
UPDATE drugs SET related_code = CONCAT(related_code, ';IPPF2:3150000000800') WHERE related_code IS NOT NULL AND related_code LIKE '%IPPF:372511999%' AND related_code NOT LIKE '%IPPF2:%';
UPDATE codes SET related_code = CONCAT(related_code, ';IPPF2:3150000000800') WHERE related_code IS NOT NULL AND related_code LIKE '%IPPF:372521999%' AND related_code NOT LIKE '%IPPF2:%';
UPDATE drugs SET related_code = CONCAT(related_code, ';IPPF2:3150000000800') WHERE related_code IS NOT NULL AND related_code LIKE '%IPPF:372521999%' AND related_code NOT LIKE '%IPPF2:%';
UPDATE codes SET related_code = CONCAT(related_code, ';IPPF2:3150000000800') WHERE related_code IS NOT NULL AND related_code LIKE '%IPPF:372531999%' AND related_code NOT LIKE '%IPPF2:%';
UPDATE drugs SET related_code = CONCAT(related_code, ';IPPF2:3150000000800') WHERE related_code IS NOT NULL AND related_code LIKE '%IPPF:372531999%' AND related_code NOT LIKE '%IPPF2:%';
UPDATE codes SET related_code = CONCAT(related_code, ';IPPF2:3150000000800') WHERE related_code IS NOT NULL AND related_code LIKE '%IPPF:372541999%' AND related_code NOT LIKE '%IPPF2:%';
UPDATE drugs SET related_code = CONCAT(related_code, ';IPPF2:3150000000800') WHERE related_code IS NOT NULL AND related_code LIKE '%IPPF:372541999%' AND related_code NOT LIKE '%IPPF2:%';
UPDATE codes SET related_code = CONCAT(related_code, ';IPPF2:3150000000800') WHERE related_code IS NOT NULL AND related_code LIKE '%IPPF:372551999%' AND related_code NOT LIKE '%IPPF2:%';
UPDATE drugs SET related_code = CONCAT(related_code, ';IPPF2:3150000000800') WHERE related_code IS NOT NULL AND related_code LIKE '%IPPF:372551999%' AND related_code NOT LIKE '%IPPF2:%';
UPDATE codes SET related_code = CONCAT(related_code, ';IPPF2:3150000000800') WHERE related_code IS NOT NULL AND related_code LIKE '%IPPF:372561999%' AND related_code NOT LIKE '%IPPF2:%';
UPDATE drugs SET related_code = CONCAT(related_code, ';IPPF2:3150000000800') WHERE related_code IS NOT NULL AND related_code LIKE '%IPPF:372561999%' AND related_code NOT LIKE '%IPPF2:%';
UPDATE codes SET related_code = CONCAT(related_code, ';IPPF2:3150000000800') WHERE related_code IS NOT NULL AND related_code LIKE '%IPPF:373600999%' AND related_code NOT LIKE '%IPPF2:%';
UPDATE drugs SET related_code = CONCAT(related_code, ';IPPF2:3150000000800') WHERE related_code IS NOT NULL AND related_code LIKE '%IPPF:373600999%' AND related_code NOT LIKE '%IPPF2:%';
UPDATE codes SET related_code = CONCAT(related_code, ';IPPF2:3150000000800') WHERE related_code IS NOT NULL AND related_code LIKE '%IPPF:373601999%' AND related_code NOT LIKE '%IPPF2:%';
UPDATE drugs SET related_code = CONCAT(related_code, ';IPPF2:3150000000800') WHERE related_code IS NOT NULL AND related_code LIKE '%IPPF:373601999%' AND related_code NOT LIKE '%IPPF2:%';
UPDATE codes SET related_code = CONCAT(related_code, ';IPPF2:3150000000800') WHERE related_code IS NOT NULL AND related_code LIKE '%IPPF:373661999%' AND related_code NOT LIKE '%IPPF2:%';
UPDATE drugs SET related_code = CONCAT(related_code, ';IPPF2:3150000000800') WHERE related_code IS NOT NULL AND related_code LIKE '%IPPF:373661999%' AND related_code NOT LIKE '%IPPF2:%';
UPDATE codes SET related_code = CONCAT(related_code, ';IPPF2:3150000000800') WHERE related_code IS NOT NULL AND related_code LIKE '%IPPF:373681999%' AND related_code NOT LIKE '%IPPF2:%';
UPDATE drugs SET related_code = CONCAT(related_code, ';IPPF2:3150000000800') WHERE related_code IS NOT NULL AND related_code LIKE '%IPPF:373681999%' AND related_code NOT LIKE '%IPPF2:%';
UPDATE codes SET related_code = CONCAT(related_code, ';IPPF2:3150000000800') WHERE related_code IS NOT NULL AND related_code LIKE '%IPPF:374700999%' AND related_code NOT LIKE '%IPPF2:%';
UPDATE drugs SET related_code = CONCAT(related_code, ';IPPF2:3150000000800') WHERE related_code IS NOT NULL AND related_code LIKE '%IPPF:374700999%' AND related_code NOT LIKE '%IPPF2:%';
UPDATE codes SET related_code = CONCAT(related_code, ';IPPF2:3150000000800') WHERE related_code IS NOT NULL AND related_code LIKE '%IPPF:374701999%' AND related_code NOT LIKE '%IPPF2:%';
UPDATE drugs SET related_code = CONCAT(related_code, ';IPPF2:3150000000800') WHERE related_code IS NOT NULL AND related_code LIKE '%IPPF:374701999%' AND related_code NOT LIKE '%IPPF2:%';
UPDATE codes SET related_code = CONCAT(related_code, ';IPPF2:3150000000800') WHERE related_code IS NOT NULL AND related_code LIKE '%IPPF:374721999%' AND related_code NOT LIKE '%IPPF2:%';
UPDATE drugs SET related_code = CONCAT(related_code, ';IPPF2:3150000000800') WHERE related_code IS NOT NULL AND related_code LIKE '%IPPF:374721999%' AND related_code NOT LIKE '%IPPF2:%';
UPDATE codes SET related_code = CONCAT(related_code, ';IPPF2:3150000000800') WHERE related_code IS NOT NULL AND related_code LIKE '%IPPF:374751999%' AND related_code NOT LIKE '%IPPF2:%';
UPDATE drugs SET related_code = CONCAT(related_code, ';IPPF2:3150000000800') WHERE related_code IS NOT NULL AND related_code LIKE '%IPPF:374751999%' AND related_code NOT LIKE '%IPPF2:%';
UPDATE codes SET related_code = CONCAT(related_code, ';IPPF2:3150000000800') WHERE related_code IS NOT NULL AND related_code LIKE '%IPPF:374761999%' AND related_code NOT LIKE '%IPPF2:%';
UPDATE drugs SET related_code = CONCAT(related_code, ';IPPF2:3150000000800') WHERE related_code IS NOT NULL AND related_code LIKE '%IPPF:374761999%' AND related_code NOT LIKE '%IPPF2:%';
UPDATE codes SET related_code = CONCAT(related_code, ';IPPF2:3150000000800') WHERE related_code IS NOT NULL AND related_code LIKE '%IPPF:374781999%' AND related_code NOT LIKE '%IPPF2:%';
UPDATE drugs SET related_code = CONCAT(related_code, ';IPPF2:3150000000800') WHERE related_code IS NOT NULL AND related_code LIKE '%IPPF:374781999%' AND related_code NOT LIKE '%IPPF2:%';
UPDATE codes SET related_code = CONCAT(related_code, ';IPPF2:3150000000800') WHERE related_code IS NOT NULL AND related_code LIKE '%IPPF:375800999%' AND related_code NOT LIKE '%IPPF2:%';
UPDATE drugs SET related_code = CONCAT(related_code, ';IPPF2:3150000000800') WHERE related_code IS NOT NULL AND related_code LIKE '%IPPF:375800999%' AND related_code NOT LIKE '%IPPF2:%';
UPDATE codes SET related_code = CONCAT(related_code, ';IPPF2:3150000000800') WHERE related_code IS NOT NULL AND related_code LIKE '%IPPF:375801999%' AND related_code NOT LIKE '%IPPF2:%';
UPDATE drugs SET related_code = CONCAT(related_code, ';IPPF2:3150000000800') WHERE related_code IS NOT NULL AND related_code LIKE '%IPPF:375801999%' AND related_code NOT LIKE '%IPPF2:%';
UPDATE codes SET related_code = CONCAT(related_code, ';IPPF2:3150000000800') WHERE related_code IS NOT NULL AND related_code LIKE '%IPPF:375811999%' AND related_code NOT LIKE '%IPPF2:%';
UPDATE drugs SET related_code = CONCAT(related_code, ';IPPF2:3150000000800') WHERE related_code IS NOT NULL AND related_code LIKE '%IPPF:375811999%' AND related_code NOT LIKE '%IPPF2:%';
UPDATE codes SET related_code = CONCAT(related_code, ';IPPF2:3150000000800') WHERE related_code IS NOT NULL AND related_code LIKE '%IPPF:375831999%' AND related_code NOT LIKE '%IPPF2:%';
UPDATE drugs SET related_code = CONCAT(related_code, ';IPPF2:3150000000800') WHERE related_code IS NOT NULL AND related_code LIKE '%IPPF:375831999%' AND related_code NOT LIKE '%IPPF2:%';
UPDATE codes SET related_code = CONCAT(related_code, ';IPPF2:3150000000800') WHERE related_code IS NOT NULL AND related_code LIKE '%IPPF:373691999%' AND related_code NOT LIKE '%IPPF2:%';
UPDATE drugs SET related_code = CONCAT(related_code, ';IPPF2:3150000000800') WHERE related_code IS NOT NULL AND related_code LIKE '%IPPF:373691999%' AND related_code NOT LIKE '%IPPF2:%';
UPDATE codes SET related_code = CONCAT(related_code, ';IPPF2:3150000000800') WHERE related_code IS NOT NULL AND related_code LIKE '%IPPF:373621999%' AND related_code NOT LIKE '%IPPF2:%';
UPDATE drugs SET related_code = CONCAT(related_code, ';IPPF2:3150000000800') WHERE related_code IS NOT NULL AND related_code LIKE '%IPPF:373621999%' AND related_code NOT LIKE '%IPPF2:%';

#EndIf

#IfNotRow code_types ct_id 32

# Add the IPPFCM code set to represent contraceptive methods. This obsoletes the "ippfconmeth" list.
INSERT INTO code_types (ct_key,ct_id,ct_seq,ct_mod,ct_just,ct_fee,ct_rel,ct_nofs,ct_diag) VALUES ('IPPFCM',32,8,0,'',0,0,1,0);
INSERT INTO codes (code_type,code,modifier,cyp_factor,code_text_short,code_text) VALUES (32,'4360','', 0.066667,'or' ,'Oral Contraceptives COC&POC');
INSERT INTO codes (code_type,code,modifier,cyp_factor,code_text_short,code_text) VALUES (32,'4370','', 0.076923,'inj','Injectable Contraceptives CICs');
INSERT INTO codes (code_type,code,modifier,cyp_factor,code_text_short,code_text) VALUES (32,'4380','', 0.166667,'inj','Injectable Contraceptives POIs - 2 mo');
INSERT INTO codes (code_type,code,modifier,cyp_factor,code_text_short,code_text) VALUES (32,'4390','', 0.250000,'inj','Injectable Contraceptives POIs - 3 mo');
INSERT INTO codes (code_type,code,modifier,cyp_factor,code_text_short,code_text) VALUES (32,'4400','', 3.800000,'imp','Subdermal Implants Implants 6 rods');
INSERT INTO codes (code_type,code,modifier,cyp_factor,code_text_short,code_text) VALUES (32,'4410','', 3.200000,'imp','Subdermal Implants Implants 2 rods');
INSERT INTO codes (code_type,code,modifier,cyp_factor,code_text_short,code_text) VALUES (32,'4420','', 2.500000,'imp','Subdermal Implants Implants 1 rod');
INSERT INTO codes (code_type,code,modifier,cyp_factor,code_text_short,code_text) VALUES (32,'4430','', 0.066667,'pat','Other Hormonal Transdermal Patch');
INSERT INTO codes (code_type,code,modifier,cyp_factor,code_text_short,code_text) VALUES (32,'4440','', 0.066667,'pat','Other Hormonal Vaginal Ring');
INSERT INTO codes (code_type,code,modifier,cyp_factor,code_text_short,code_text) VALUES (32,'4450','', 0.008333,'con','Condoms - Male');
INSERT INTO codes (code_type,code,modifier,cyp_factor,code_text_short,code_text) VALUES (32,'4460','', 0.008333,'con','Condoms - Female');
INSERT INTO codes (code_type,code,modifier,cyp_factor,code_text_short,code_text) VALUES (32,'4470','', 1.000000,'dia','Diapraghms - Totals');
INSERT INTO codes (code_type,code,modifier,cyp_factor,code_text_short,code_text) VALUES (32,'4480','', 1.000000,'cap','Caps - Totals');
INSERT INTO codes (code_type,code,modifier,cyp_factor,code_text_short,code_text) VALUES (32,'4490','', 0.133333,'sp' ,'Spermicides Foams Tabs');
INSERT INTO codes (code_type,code,modifier,cyp_factor,code_text_short,code_text) VALUES (32,'4500','', 0.133333,'sp' ,'Spermicides Foam Strips');
INSERT INTO codes (code_type,code,modifier,cyp_factor,code_text_short,code_text) VALUES (32,'4510','', 0.133333,'sp' ,'Spermicides Foam Cans');
INSERT INTO codes (code_type,code,modifier,cyp_factor,code_text_short,code_text) VALUES (32,'4520','', 0.133333,'sp' ,'Spermicides Creams & Jellies');
INSERT INTO codes (code_type,code,modifier,cyp_factor,code_text_short,code_text) VALUES (32,'4530','', 0.133333,'sp' ,'Spermicides Pessaries - Film');
INSERT INTO codes (code_type,code,modifier,cyp_factor,code_text_short,code_text) VALUES (32,'4540','', 3.300000,'iud','IUD Hormone 5 yr');
INSERT INTO codes (code_type,code,modifier,cyp_factor,code_text_short,code_text) VALUES (32,'4550','', 4.600000,'iud','IUD Copper 10 yr');
INSERT INTO codes (code_type,code,modifier,cyp_factor,code_text_short,code_text) VALUES (32,'4560','',10.000000,'vsc','Voluntary Surgical Contraception - Female');
INSERT INTO codes (code_type,code,modifier,cyp_factor,code_text_short,code_text) VALUES (32,'4570','',10.000000,'vsc','Voluntary Surgical Contraception - Male');
INSERT INTO codes (code_type,code,modifier,cyp_factor,code_text_short,code_text) VALUES (32,'4580','', 0.000000,'fab','Awareness-Based Methods - CMM');
INSERT INTO codes (code_type,code,modifier,cyp_factor,code_text_short,code_text) VALUES (32,'4590','', 0.000000,'fab','Awareness-Based Methods - CBM');
INSERT INTO codes (code_type,code,modifier,cyp_factor,code_text_short,code_text) VALUES (32,'4600','', 0.000000,'fab','Awareness-Based Methods - STM');
INSERT INTO codes (code_type,code,modifier,cyp_factor,code_text_short,code_text) VALUES (32,'4610','', 0.000000,'fab','Awareness-Based Methods - SDM');
INSERT INTO codes (code_type,code,modifier,cyp_factor,code_text_short,code_text) VALUES (32,'4620','', 0.050000,'ec' ,'Emergency Contraception - Therapy');

# This transforms "newmethod" values in LBFccicon forms from IPPF codes to IPPFCM codes.
UPDATE forms AS f, lbf_data AS ld SET ld.field_value = 'IPPFCM:4360' WHERE ld.field_value LIKE '111101%' AND f.formdir = 'LBFccicon' AND f.deleted = 0 AND ld.form_id = f.form_id AND ld.field_id = 'newmethod';
UPDATE forms AS f, lbf_data AS ld SET ld.field_value = 'IPPFCM:4370' WHERE ld.field_value LIKE '111111%' AND f.formdir = 'LBFccicon' AND f.deleted = 0 AND ld.form_id = f.form_id AND ld.field_id = 'newmethod';
UPDATE forms AS f, lbf_data AS ld SET ld.field_value = 'IPPFCM:4380' WHERE ld.field_value LIKE '111112%' AND f.formdir = 'LBFccicon' AND f.deleted = 0 AND ld.form_id = f.form_id AND ld.field_id = 'newmethod';
UPDATE forms AS f, lbf_data AS ld SET ld.field_value = 'IPPFCM:4390' WHERE ld.field_value LIKE '111113%' AND f.formdir = 'LBFccicon' AND f.deleted = 0 AND ld.form_id = f.form_id AND ld.field_id = 'newmethod';
UPDATE forms AS f, lbf_data AS ld SET ld.field_value = 'IPPFCM:4400' WHERE ld.field_value LIKE '111122%' AND f.formdir = 'LBFccicon' AND f.deleted = 0 AND ld.form_id = f.form_id AND ld.field_id = 'newmethod';
UPDATE forms AS f, lbf_data AS ld SET ld.field_value = 'IPPFCM:4410' WHERE ld.field_value LIKE '111123%' AND f.formdir = 'LBFccicon' AND f.deleted = 0 AND ld.form_id = f.form_id AND ld.field_id = 'newmethod';
UPDATE forms AS f, lbf_data AS ld SET ld.field_value = 'IPPFCM:4420' WHERE ld.field_value LIKE '111124%' AND f.formdir = 'LBFccicon' AND f.deleted = 0 AND ld.form_id = f.form_id AND ld.field_id = 'newmethod';
UPDATE forms AS f, lbf_data AS ld SET ld.field_value = 'IPPFCM:4430' WHERE ld.field_value LIKE '111132%' AND f.formdir = 'LBFccicon' AND f.deleted = 0 AND ld.form_id = f.form_id AND ld.field_id = 'newmethod';
UPDATE forms AS f, lbf_data AS ld SET ld.field_value = 'IPPFCM:4440' WHERE ld.field_value LIKE '111133%' AND f.formdir = 'LBFccicon' AND f.deleted = 0 AND ld.form_id = f.form_id AND ld.field_id = 'newmethod';
UPDATE forms AS f, lbf_data AS ld SET ld.field_value = 'IPPFCM:4450' WHERE ld.field_value LIKE '112141%' AND f.formdir = 'LBFccicon' AND f.deleted = 0 AND ld.form_id = f.form_id AND ld.field_id = 'newmethod';
UPDATE forms AS f, lbf_data AS ld SET ld.field_value = 'IPPFCM:4460' WHERE ld.field_value LIKE '112142%' AND f.formdir = 'LBFccicon' AND f.deleted = 0 AND ld.form_id = f.form_id AND ld.field_id = 'newmethod';
UPDATE forms AS f, lbf_data AS ld SET ld.field_value = 'IPPFCM:4470' WHERE ld.field_value LIKE '112151%' AND f.formdir = 'LBFccicon' AND f.deleted = 0 AND ld.form_id = f.form_id AND ld.field_id = 'newmethod';
UPDATE forms AS f, lbf_data AS ld SET ld.field_value = 'IPPFCM:4480' WHERE ld.field_value LIKE '112152%' AND f.formdir = 'LBFccicon' AND f.deleted = 0 AND ld.form_id = f.form_id AND ld.field_id = 'newmethod';
UPDATE forms AS f, lbf_data AS ld SET ld.field_value = 'IPPFCM:4490' WHERE ld.field_value LIKE '112161%' AND f.formdir = 'LBFccicon' AND f.deleted = 0 AND ld.form_id = f.form_id AND ld.field_id = 'newmethod';
UPDATE forms AS f, lbf_data AS ld SET ld.field_value = 'IPPFCM:4500' WHERE ld.field_value LIKE '112162%' AND f.formdir = 'LBFccicon' AND f.deleted = 0 AND ld.form_id = f.form_id AND ld.field_id = 'newmethod';
UPDATE forms AS f, lbf_data AS ld SET ld.field_value = 'IPPFCM:4510' WHERE ld.field_value LIKE '112163%' AND f.formdir = 'LBFccicon' AND f.deleted = 0 AND ld.form_id = f.form_id AND ld.field_id = 'newmethod';
UPDATE forms AS f, lbf_data AS ld SET ld.field_value = 'IPPFCM:4520' WHERE ld.field_value LIKE '112164%' AND f.formdir = 'LBFccicon' AND f.deleted = 0 AND ld.form_id = f.form_id AND ld.field_id = 'newmethod';
UPDATE forms AS f, lbf_data AS ld SET ld.field_value = 'IPPFCM:4530' WHERE ld.field_value LIKE '112165%' AND f.formdir = 'LBFccicon' AND f.deleted = 0 AND ld.form_id = f.form_id AND ld.field_id = 'newmethod';
UPDATE forms AS f, lbf_data AS ld SET ld.field_value = 'IPPFCM:4540' WHERE ld.field_value LIKE '113171%' AND f.formdir = 'LBFccicon' AND f.deleted = 0 AND ld.form_id = f.form_id AND ld.field_id = 'newmethod';
UPDATE forms AS f, lbf_data AS ld SET ld.field_value = 'IPPFCM:4550' WHERE ld.field_value LIKE '113172%' AND f.formdir = 'LBFccicon' AND f.deleted = 0 AND ld.form_id = f.form_id AND ld.field_id = 'newmethod';
UPDATE forms AS f, lbf_data AS ld SET ld.field_value = 'IPPFCM:4560' WHERE ld.field_value LIKE '121181%' AND f.formdir = 'LBFccicon' AND f.deleted = 0 AND ld.form_id = f.form_id AND ld.field_id = 'newmethod';
UPDATE forms AS f, lbf_data AS ld SET ld.field_value = 'IPPFCM:4570' WHERE ld.field_value LIKE '122182%' AND f.formdir = 'LBFccicon' AND f.deleted = 0 AND ld.form_id = f.form_id AND ld.field_id = 'newmethod';
UPDATE forms AS f, lbf_data AS ld SET ld.field_value = 'IPPFCM:4620' WHERE ld.field_value LIKE '145212%' AND f.formdir = 'LBFccicon' AND f.deleted = 0 AND ld.form_id = f.form_id AND ld.field_id = 'newmethod';

# This updates MA products and services, adding IPPFCM related codes deduced from legacy IPPF codes.
UPDATE codes SET related_code = CONCAT(related_code, ';IPPFCM:4360') WHERE code_type = 12 AND related_code IS NOT NULL AND related_code LIKE '%IPPF:111101%' AND related_code NOT LIKE '%IPPFCM:%';
UPDATE drugs SET related_code = CONCAT(related_code, ';IPPFCM:4360') WHERE related_code IS NOT NULL AND related_code LIKE '%IPPF:111101%' AND related_code NOT LIKE '%IPPFCM:%';
UPDATE codes SET related_code = CONCAT(related_code, ';IPPFCM:4370') WHERE code_type = 12 AND related_code IS NOT NULL AND related_code LIKE '%IPPF:111111%' AND related_code NOT LIKE '%IPPFCM:%';
UPDATE drugs SET related_code = CONCAT(related_code, ';IPPFCM:4370') WHERE related_code IS NOT NULL AND related_code LIKE '%IPPF:111111%' AND related_code NOT LIKE '%IPPFCM:%';
UPDATE codes SET related_code = CONCAT(related_code, ';IPPFCM:4380') WHERE code_type = 12 AND related_code IS NOT NULL AND related_code LIKE '%IPPF:111112%' AND related_code NOT LIKE '%IPPFCM:%';
UPDATE drugs SET related_code = CONCAT(related_code, ';IPPFCM:4380') WHERE related_code IS NOT NULL AND related_code LIKE '%IPPF:111112%' AND related_code NOT LIKE '%IPPFCM:%';
UPDATE codes SET related_code = CONCAT(related_code, ';IPPFCM:4390') WHERE code_type = 12 AND related_code IS NOT NULL AND related_code LIKE '%IPPF:111113%' AND related_code NOT LIKE '%IPPFCM:%';
UPDATE drugs SET related_code = CONCAT(related_code, ';IPPFCM:4390') WHERE related_code IS NOT NULL AND related_code LIKE '%IPPF:111113%' AND related_code NOT LIKE '%IPPFCM:%';
UPDATE codes SET related_code = CONCAT(related_code, ';IPPFCM:4400') WHERE code_type = 12 AND related_code IS NOT NULL AND related_code LIKE '%IPPF:111122%' AND related_code NOT LIKE '%IPPFCM:%';
UPDATE drugs SET related_code = CONCAT(related_code, ';IPPFCM:4400') WHERE related_code IS NOT NULL AND related_code LIKE '%IPPF:111122%' AND related_code NOT LIKE '%IPPFCM:%';
UPDATE codes SET related_code = CONCAT(related_code, ';IPPFCM:4410') WHERE code_type = 12 AND related_code IS NOT NULL AND related_code LIKE '%IPPF:111123%' AND related_code NOT LIKE '%IPPFCM:%';
UPDATE drugs SET related_code = CONCAT(related_code, ';IPPFCM:4410') WHERE related_code IS NOT NULL AND related_code LIKE '%IPPF:111123%' AND related_code NOT LIKE '%IPPFCM:%';
UPDATE codes SET related_code = CONCAT(related_code, ';IPPFCM:4420') WHERE code_type = 12 AND related_code IS NOT NULL AND related_code LIKE '%IPPF:111124%' AND related_code NOT LIKE '%IPPFCM:%';
UPDATE drugs SET related_code = CONCAT(related_code, ';IPPFCM:4420') WHERE related_code IS NOT NULL AND related_code LIKE '%IPPF:111124%' AND related_code NOT LIKE '%IPPFCM:%';
UPDATE codes SET related_code = CONCAT(related_code, ';IPPFCM:4430') WHERE code_type = 12 AND related_code IS NOT NULL AND related_code LIKE '%IPPF:111132%' AND related_code NOT LIKE '%IPPFCM:%';
UPDATE drugs SET related_code = CONCAT(related_code, ';IPPFCM:4430') WHERE related_code IS NOT NULL AND related_code LIKE '%IPPF:111132%' AND related_code NOT LIKE '%IPPFCM:%';
UPDATE codes SET related_code = CONCAT(related_code, ';IPPFCM:4440') WHERE code_type = 12 AND related_code IS NOT NULL AND related_code LIKE '%IPPF:111133%' AND related_code NOT LIKE '%IPPFCM:%';
UPDATE drugs SET related_code = CONCAT(related_code, ';IPPFCM:4440') WHERE related_code IS NOT NULL AND related_code LIKE '%IPPF:111133%' AND related_code NOT LIKE '%IPPFCM:%';
UPDATE codes SET related_code = CONCAT(related_code, ';IPPFCM:4450') WHERE code_type = 12 AND related_code IS NOT NULL AND related_code LIKE '%IPPF:112141%' AND related_code NOT LIKE '%IPPFCM:%';
UPDATE drugs SET related_code = CONCAT(related_code, ';IPPFCM:4450') WHERE related_code IS NOT NULL AND related_code LIKE '%IPPF:112141%' AND related_code NOT LIKE '%IPPFCM:%';
UPDATE codes SET related_code = CONCAT(related_code, ';IPPFCM:4460') WHERE code_type = 12 AND related_code IS NOT NULL AND related_code LIKE '%IPPF:112142%' AND related_code NOT LIKE '%IPPFCM:%';
UPDATE drugs SET related_code = CONCAT(related_code, ';IPPFCM:4460') WHERE related_code IS NOT NULL AND related_code LIKE '%IPPF:112142%' AND related_code NOT LIKE '%IPPFCM:%';
UPDATE codes SET related_code = CONCAT(related_code, ';IPPFCM:4470') WHERE code_type = 12 AND related_code IS NOT NULL AND related_code LIKE '%IPPF:112151%' AND related_code NOT LIKE '%IPPFCM:%';
UPDATE drugs SET related_code = CONCAT(related_code, ';IPPFCM:4470') WHERE related_code IS NOT NULL AND related_code LIKE '%IPPF:112151%' AND related_code NOT LIKE '%IPPFCM:%';
UPDATE codes SET related_code = CONCAT(related_code, ';IPPFCM:4480') WHERE code_type = 12 AND related_code IS NOT NULL AND related_code LIKE '%IPPF:112152%' AND related_code NOT LIKE '%IPPFCM:%';
UPDATE drugs SET related_code = CONCAT(related_code, ';IPPFCM:4480') WHERE related_code IS NOT NULL AND related_code LIKE '%IPPF:112152%' AND related_code NOT LIKE '%IPPFCM:%';
UPDATE codes SET related_code = CONCAT(related_code, ';IPPFCM:4490') WHERE code_type = 12 AND related_code IS NOT NULL AND related_code LIKE '%IPPF:112161%' AND related_code NOT LIKE '%IPPFCM:%';
UPDATE drugs SET related_code = CONCAT(related_code, ';IPPFCM:4490') WHERE related_code IS NOT NULL AND related_code LIKE '%IPPF:112161%' AND related_code NOT LIKE '%IPPFCM:%';
UPDATE codes SET related_code = CONCAT(related_code, ';IPPFCM:4500') WHERE code_type = 12 AND related_code IS NOT NULL AND related_code LIKE '%IPPF:112162%' AND related_code NOT LIKE '%IPPFCM:%';
UPDATE drugs SET related_code = CONCAT(related_code, ';IPPFCM:4500') WHERE related_code IS NOT NULL AND related_code LIKE '%IPPF:112162%' AND related_code NOT LIKE '%IPPFCM:%';
UPDATE codes SET related_code = CONCAT(related_code, ';IPPFCM:4510') WHERE code_type = 12 AND related_code IS NOT NULL AND related_code LIKE '%IPPF:112163%' AND related_code NOT LIKE '%IPPFCM:%';
UPDATE drugs SET related_code = CONCAT(related_code, ';IPPFCM:4510') WHERE related_code IS NOT NULL AND related_code LIKE '%IPPF:112163%' AND related_code NOT LIKE '%IPPFCM:%';
UPDATE codes SET related_code = CONCAT(related_code, ';IPPFCM:4520') WHERE code_type = 12 AND related_code IS NOT NULL AND related_code LIKE '%IPPF:112164%' AND related_code NOT LIKE '%IPPFCM:%';
UPDATE drugs SET related_code = CONCAT(related_code, ';IPPFCM:4520') WHERE related_code IS NOT NULL AND related_code LIKE '%IPPF:112164%' AND related_code NOT LIKE '%IPPFCM:%';
UPDATE codes SET related_code = CONCAT(related_code, ';IPPFCM:4530') WHERE code_type = 12 AND related_code IS NOT NULL AND related_code LIKE '%IPPF:112165%' AND related_code NOT LIKE '%IPPFCM:%';
UPDATE drugs SET related_code = CONCAT(related_code, ';IPPFCM:4530') WHERE related_code IS NOT NULL AND related_code LIKE '%IPPF:112165%' AND related_code NOT LIKE '%IPPFCM:%';
UPDATE codes SET related_code = CONCAT(related_code, ';IPPFCM:4540') WHERE code_type = 12 AND related_code IS NOT NULL AND related_code LIKE '%IPPF:113171%' AND related_code NOT LIKE '%IPPFCM:%';
UPDATE drugs SET related_code = CONCAT(related_code, ';IPPFCM:4540') WHERE related_code IS NOT NULL AND related_code LIKE '%IPPF:113171%' AND related_code NOT LIKE '%IPPFCM:%';
UPDATE codes SET related_code = CONCAT(related_code, ';IPPFCM:4550') WHERE code_type = 12 AND related_code IS NOT NULL AND related_code LIKE '%IPPF:113172%' AND related_code NOT LIKE '%IPPFCM:%';
UPDATE drugs SET related_code = CONCAT(related_code, ';IPPFCM:4550') WHERE related_code IS NOT NULL AND related_code LIKE '%IPPF:113172%' AND related_code NOT LIKE '%IPPFCM:%';
UPDATE codes SET related_code = CONCAT(related_code, ';IPPFCM:4560') WHERE code_type = 12 AND related_code IS NOT NULL AND related_code LIKE '%IPPF:121181%' AND related_code NOT LIKE '%IPPFCM:%';
UPDATE drugs SET related_code = CONCAT(related_code, ';IPPFCM:4560') WHERE related_code IS NOT NULL AND related_code LIKE '%IPPF:121181%' AND related_code NOT LIKE '%IPPFCM:%';
UPDATE codes SET related_code = CONCAT(related_code, ';IPPFCM:4570') WHERE code_type = 12 AND related_code IS NOT NULL AND related_code LIKE '%IPPF:122182%' AND related_code NOT LIKE '%IPPFCM:%';
UPDATE drugs SET related_code = CONCAT(related_code, ';IPPFCM:4570') WHERE related_code IS NOT NULL AND related_code LIKE '%IPPF:122182%' AND related_code NOT LIKE '%IPPFCM:%';
UPDATE codes SET related_code = CONCAT(related_code, ';IPPFCM:4620') WHERE code_type = 12 AND related_code IS NOT NULL AND related_code LIKE '%IPPF:145212%' AND related_code NOT LIKE '%IPPFCM:%';
UPDATE drugs SET related_code = CONCAT(related_code, ';IPPFCM:4620') WHERE related_code IS NOT NULL AND related_code LIKE '%IPPF:145212%' AND related_code NOT LIKE '%IPPFCM:%';

# This updates MA services, setting "Initial Consult" indicators deduced from legacy IPPF codes.
UPDATE codes SET cyp_factor = 0 WHERE code_type = 12;
UPDATE codes SET cyp_factor = 1 WHERE code_type = 12 AND related_code IS NOT NULL AND related_code LIKE '%IPPF:11____110%';
UPDATE codes SET cyp_factor = 1 WHERE code_type = 12 AND related_code IS NOT NULL AND related_code LIKE '%IPPF:11___1999%';
UPDATE codes SET cyp_factor = 1 WHERE code_type = 12 AND related_code IS NOT NULL AND related_code LIKE '%IPPF:11___2999%';
UPDATE codes SET cyp_factor = 1 WHERE code_type = 12 AND related_code IS NOT NULL AND related_code LIKE '%IPPF:11___3999%';
UPDATE codes SET cyp_factor = 1 WHERE code_type = 12 AND related_code IS NOT NULL AND related_code LIKE '%IPPF:11___4999%';
UPDATE codes SET cyp_factor = 1 WHERE code_type = 12 AND related_code IS NOT NULL AND related_code LIKE '%IPPF:11___5999%';
UPDATE codes SET cyp_factor = 1 WHERE code_type = 12 AND related_code IS NOT NULL AND related_code LIKE '%IPPF:112152010%';
UPDATE codes SET cyp_factor = 1 WHERE code_type = 12 AND related_code IS NOT NULL AND related_code LIKE '%IPPF:113171111%';
UPDATE codes SET cyp_factor = 1 WHERE code_type = 12 AND related_code IS NOT NULL AND related_code LIKE '%IPPF:113172111%';
UPDATE codes SET cyp_factor = 1 WHERE code_type = 12 AND related_code IS NOT NULL AND related_code LIKE '%IPPF:121181_13%';
UPDATE codes SET cyp_factor = 1 WHERE code_type = 12 AND related_code IS NOT NULL AND related_code LIKE '%IPPF:121182_13%';
UPDATE codes SET cyp_factor = 1 WHERE code_type = 12 AND related_code IS NOT NULL AND related_code LIKE '%IPPF:121181999%';
UPDATE codes SET cyp_factor = 1 WHERE code_type = 12 AND related_code IS NOT NULL AND related_code LIKE '%IPPF:122182_13%';
UPDATE codes SET cyp_factor = 1 WHERE code_type = 12 AND related_code IS NOT NULL AND related_code LIKE '%IPPF:122182999%';

# Modify LBFccicon form to use IPPFCM codes instead of the ippfconmeth list.
UPDATE layout_options SET data_type = 15, list_id = '', description = 'IPPFCM' WHERE form_id = 'LBFccicon' AND field_id = 'newmethod';

# Change New Acceptor Policy if it is set to the obsolete "New Users to IPPF/Association".
update globals SET gl_value = 3 WHERE gl_name = 'gbl_new_acceptor_policy' AND gl_value = 2;

#EndIf

