update `globals` set gl_value=0 where gl_name in ('esign_individual','lock_esign_individual');

#IfMissingColumn procedure_report review_status
ALTER TABLE `procedure_report` ADD COLUMN `review_status` varchar(31) NOT NULL default '';
#EndIf

update registry set state=0 where directory in ("vitalsM","vitalsigns","misc_billing_options","dictation");

#IfMissingColumn procedure_order procedure_type_id
ALTER TABLE `procedure_order` 
ADD COLUMN `procedure_type_id` BIGINT(20) NOT NULL COMMENT "references procedure_type.procedure_type_id";
#EndIf

#IfMissingColumn procedure_result procedure_type_id
ALTER TABLE `procedure_result` 
ADD COLUMN `procedure_type_id` BIGINT(20) NOT NULL COMMENT "references procedure_type.procedure_type_id";
#EndIf

#IfMissingColumn patient_data education_level
ALTER TABLE `patient_data` ADD COLUMN `education_level` varchar(10) NOT NULL default '';
INSERT INTO `layout_options` (`form_id`,`field_id`,`group_id`,`title`,`seq`,`data_type`,`uor`,`fld_length`,`max_length`,`list_id`,`titlecols`,`datacols`,`default_value`,`edit_options`,`description`,`fld_rows`) VALUES ('DEM', 'education_level', '6', 'Education Level',20, 1, 1, 0, 0, 'education_level', 1, 1, '', '', 'Education Level', 0);
INSERT INTO list_options ( list_id, option_id, title, seq, is_default, mapping ) VALUES ('lists','education_level','Education Level',1,0,'0');
INSERT INTO list_options ( list_id, option_id, title, seq, is_default, mapping ) VALUES ('education_level','1','Illiterate',1,0,'0');
INSERT INTO list_options ( list_id, option_id, title, seq, is_default, mapping ) VALUES ('education_level','2','Basic Schooling',2,0,'1');
INSERT INTO list_options ( list_id, option_id, title, seq, is_default, mapping ) VALUES ('education_level','3','Advanced Schooling',3,0,'2');
#EndIf

#IfMissingColumn patient_data religion
ALTER TABLE `patient_data` ADD COLUMN `religion` varchar(10) NOT NULL default '';
INSERT INTO `layout_options` (`form_id`,`field_id`,`group_id`,`title`,`seq`,`data_type`,`uor`,`fld_length`,`max_length`,`list_id`,`titlecols`,`datacols`,`default_value`,`edit_options`,`description`,`fld_rows`) VALUES ('DEM', 'religion', '6', 'Religion',19, 1, 1, 0, 0, 'religion', 1, 1, '', '', 'Religion', 0);
INSERT INTO list_options ( list_id, option_id, title, seq, is_default, mapping ) VALUES ('lists','religion','Religion',1,0,'0');
INSERT INTO list_options ( list_id, option_id, title, seq, is_default, mapping ) VALUES ('religion','1','Catholic',1,0,'0');
INSERT INTO list_options ( list_id, option_id, title, seq, is_default, mapping ) VALUES ('religion','2','Other',2,0,'1');
#EndIf

#IfMissingColumn patient_data nationality
ALTER TABLE `patient_data` ADD COLUMN `nationality` varchar(10) NOT NULL default '';
INSERT INTO `layout_options` (`form_id`,`field_id`,`group_id`,`title`,`seq`,`data_type`,`uor`,`fld_length`,`max_length`,`list_id`,`titlecols`,`datacols`,`default_value`,`edit_options`,`description`,`fld_rows`) VALUES ('DEM', 'nationality', '6', 'Nationality',21, 1, 1, 0, 0, 'nationality', 1, 1, '', '', 'Nationality', 0);
INSERT INTO list_options ( list_id, option_id, title, seq, is_default, mapping ) VALUES ('lists','nationality','Nationality',1,0,'0');
INSERT INTO list_options ( list_id, option_id, title, seq, is_default, mapping ) VALUES ('nationality','1','Sample 1',1,0,'0');
INSERT INTO list_options ( list_id, option_id, title, seq, is_default, mapping ) VALUES ('nationality','2','Sample 2',2,0,'1');
#EndIf
