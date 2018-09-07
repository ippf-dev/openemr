--
--  Comment Meta Language Constructs:
--
--  #IfNotTable
--    argument: table_name
--    behavior: if the table_name does not exist,  the block will be executed

--  #IfTable
--    argument: table_name
--    behavior: if the table_name does exist, the block will be executed

--  #IfMissingColumn
--    arguments: table_name colname
--    behavior:  if the table exists but the column does not,  the block will be executed

--  #IfNotColumnType
--    arguments: table_name colname value
--    behavior:  If the table table_name does not have a column colname with a data type equal to value, then the block will be executed

--  #IfNotRow
--    arguments: table_name colname value
--    behavior:  If the table table_name does not have a row where colname = value, the block will be executed.

--  #IfNotRow2D
--    arguments: table_name colname value colname2 value2
--    behavior:  If the table table_name does not have a row where colname = value AND colname2 = value2, the block will be executed.

--  #IfNotRow3D
--    arguments: table_name colname value colname2 value2 colname3 value3
--    behavior:  If the table table_name does not have a row where colname = value AND colname2 = value2 AND colname3 = value3, the block will be executed.

--  #IfNotRow4D
--    arguments: table_name colname value colname2 value2 colname3 value3 colname4 value4
--    behavior:  If the table table_name does not have a row where colname = value AND colname2 = value2 AND colname3 = value3 AND colname4 = value4, the block will be executed.

--  #IfNotRow2Dx2
--    desc:      This is a very specialized function to allow adding items to the list_options table to avoid both redundant option_id and title in each element.
--    arguments: table_name colname value colname2 value2 colname3 value3
--    behavior:  The block will be executed if both statements below are true:
--               1) The table table_name does not have a row where colname = value AND colname2 = value2.
--               2) The table table_name does not have a row where colname = value AND colname3 = value3.

--  #IfRow2D
--    arguments: table_name colname value colname2 value2
--    behavior:  If the table table_name does have a row where colname = value AND colname2 = value2, the block will be executed.

--  #IfIndex
--    desc:      This function is most often used for dropping of indexes/keys.
--    arguments: table_name colname
--    behavior:  If the table and index exist the relevant statements are executed, otherwise not.

--  #IfNotIndex
--    desc:      This function will allow adding of indexes/keys.
--    arguments: table_name colname
--    behavior:  If the index does not exist, it will be created

--  #EndIf
--    all blocks are terminated with a #EndIf statement.

#IfNotRow4D supported_external_dataloads load_type ICD9 load_source CMS load_release_date 2013-10-01 load_filename cmsv31-master-descriptions.zip
INSERT INTO `supported_external_dataloads` (`load_type`, `load_source`, `load_release_date`, `load_filename`, `load_checksum`) VALUES ('ICD9', 'CMS', '2013-10-01', 'cmsv31-master-descriptions.zip', 'fe0d7f9a5338f5ff187683b4737ad2b7');
#EndIf

#IfNotRow4D supported_external_dataloads load_type ICD10 load_source CMS load_release_date 2012-10-01 load_filename 2013_PCS_long_and_abbreviated_titles.zip
INSERT INTO `supported_external_dataloads` (`load_type`, `load_source`, `load_release_date`, `load_filename`, `load_checksum`) VALUES ('ICD10', 'CMS', '2012-10-01', '2013_PCS_long_and_abbreviated_titles.zip', '04458ed0631c2c122624ee0a4ca1c475');
#EndIf

#IfNotRow4D supported_external_dataloads load_type ICD10 load_source CMS load_release_date 2012-10-01 load_filename 2013-DiagnosisGEMs.zip
INSERT INTO `supported_external_dataloads` (`load_type`, `load_source`, `load_release_date`, `load_filename`, `load_checksum`) VALUES ('ICD10', 'CMS', '2012-10-01', '2013-DiagnosisGEMs.zip', '773aac2a675d6aefd1d7dd149883be51');
#EndIf

#IfNotRow4D supported_external_dataloads load_type ICD10 load_source CMS load_release_date 2012-10-01 load_filename ICD10CMOrderFiles_2013.zip
INSERT INTO `supported_external_dataloads` (`load_type`, `load_source`, `load_release_date`, `load_filename`, `load_checksum`) VALUES ('ICD10', 'CMS', '2012-10-01', 'ICD10CMOrderFiles_2013.zip', '1c175a858f833485ef8f9d3e66b4d8bd');
#EndIf

#IfNotRow4D supported_external_dataloads load_type ICD10 load_source CMS load_release_date 2012-10-01 load_filename ProcedureGEMs_2013.zip
INSERT INTO `supported_external_dataloads` (`load_type`, `load_source`, `load_release_date`, `load_filename`, `load_checksum`) VALUES ('ICD10', 'CMS', '2012-10-01', 'ProcedureGEMs_2013.zip', '92aa7640e5ce29b9629728f7d4fc81db');
#EndIf

#IfNotRow4D supported_external_dataloads load_type ICD10 load_source CMS load_release_date 2012-10-01 load_filename 2013-ReimbursementMapping_dx.zip
INSERT INTO `supported_external_dataloads` (`load_type`, `load_source`, `load_release_date`, `load_filename`, `load_checksum`) VALUES ('ICD10', 'CMS', '2012-10-01', '2013-ReimbursementMapping_dx.zip', '0d5d36e3f4519bbba08a9508576787fb');
#EndIf

#IfNotRow4D supported_external_dataloads load_type ICD10 load_source CMS load_release_date 2012-10-01 load_filename ReimbursementMapping_pr_2013.zip
INSERT INTO `supported_external_dataloads` (`load_type`, `load_source`, `load_release_date`, `load_filename`, `load_checksum`) VALUES ('ICD10', 'CMS', '2012-10-01', 'ReimbursementMapping_pr_2013.zip', '4c3920fedbcd9f6af54a1dc9069a11ca');
#EndIf

#IfNotRow4D supported_external_dataloads load_type ICD10 load_source CMS load_release_date 2013-10-01 load_filename 2014-PCS-long-and-abbreviated-titles.zip
INSERT INTO `supported_external_dataloads` (`load_type`, `load_source`, `load_release_date`, `load_filename`, `load_checksum`) VALUES ('ICD10', 'CMS', '2013-10-01', '2014-PCS-long-and-abbreviated-titles.zip', '2d03514a0c66d92cf022a0bc28c83d38');
#EndIf

#IfNotRow4D supported_external_dataloads load_type ICD10 load_source CMS load_release_date 2013-10-01 load_filename DiagnosisGEMs-2014.zip
INSERT INTO `supported_external_dataloads` (`load_type`, `load_source`, `load_release_date`, `load_filename`, `load_checksum`) VALUES ('ICD10', 'CMS', '2013-10-01', 'DiagnosisGEMs-2014.zip', '3ed7b7c5a11c766102b12d97d777a11b');
#EndIf

#IfNotRow4D supported_external_dataloads load_type ICD10 load_source CMS load_release_date 2013-10-01 load_filename 2014-ICD10-Code-Descriptions.zip
INSERT INTO `supported_external_dataloads` (`load_type`, `load_source`, `load_release_date`, `load_filename`, `load_checksum`) VALUES ('ICD10', 'CMS', '2013-10-01', '2014-ICD10-Code-Descriptions.zip', '5458b95f6f37228b5cdfa03aefc6c8bb');
#EndIf

#IfNotRow4D supported_external_dataloads load_type ICD10 load_source CMS load_release_date 2013-10-01 load_filename ProcedureGEMs-2014.zip
INSERT INTO `supported_external_dataloads` (`load_type`, `load_source`, `load_release_date`, `load_filename`, `load_checksum`) VALUES ('ICD10', 'CMS', '2013-10-01', 'ProcedureGEMs-2014.zip', 'be46de29f4f40f97315d04821273acf9');
#EndIf

#IfNotRow4D supported_external_dataloads load_type ICD10 load_source CMS load_release_date 2013-10-01 load_filename 2014-Reimbursement-Mappings-DX.zip
INSERT INTO `supported_external_dataloads` (`load_type`, `load_source`, `load_release_date`, `load_filename`, `load_checksum`) VALUES ('ICD10', 'CMS', '2013-10-01', '2014-Reimbursement-Mappings-DX.zip', '614b3957304208e3ef7d3ba8b3618888');
#EndIf

#IfNotRow4D supported_external_dataloads load_type ICD10 load_source CMS load_release_date 2013-10-01 load_filename 2014-Reimbursement-Mappings-PR.zip
INSERT INTO `supported_external_dataloads` (`load_type`, `load_source`, `load_release_date`, `load_filename`, `load_checksum`) VALUES ('ICD10', 'CMS', '2013-10-01', '2014-Reimbursement-Mappings-PR.zip', 'f306a0e8c9edb34d28fd6ce8af82b646');
#EndIf

#IfMissingColumn patient_data email_direct
ALTER TABLE `patient_data` ADD COLUMN `email_direct` varchar(255) NOT NULL default '';
INSERT INTO `layout_options` (`form_id`, `field_id`, `group_name`, `title`, `seq`, `data_type`, `uor`, `fld_length`, `max_length`, `list_id`, `titlecols`, `datacols`, `default_value`, `edit_options`, `description`, `fld_rows`) VALUES('DEM', 'email_direct', '2Contact', 'Trusted Email', 14, 2, 1, 30, 95, '', 1, 1, '', '', 'Trusted (Direct) Email Address', 0);
#EndIf

#IfMissingColumn users email_direct
ALTER TABLE `users` ADD COLUMN `email_direct` varchar(255) NOT NULL default '';
#EndIf

#IfNotTable erx_ttl_touch
CREATE TABLE `erx_ttl_touch` (
  `patient_id` BIGINT(20) UNSIGNED NOT NULL COMMENT 'Patient record Id', 
  `process` ENUM('allergies','medications') NOT NULL COMMENT 'NewCrop eRx SOAP process',
  `updated` DATETIME NOT NULL COMMENT 'Date and time of last process update for patient', 
  PRIMARY KEY (`patient_id`, `process`) ) 
ENGINE = InnoDB COMMENT = 'Store records last update per patient data process';
#EndIf

#IfMissingColumn form_misc_billing_options box_14_date_qual
ALTER TABLE `form_misc_billing_options` 
ADD COLUMN `box_14_date_qual` CHAR(3) NULL DEFAULT NULL;
#EndIf

#IfMissingColumn form_misc_billing_options box_15_date_qual
ALTER TABLE `form_misc_billing_options` 
ADD COLUMN `box_15_date_qual` CHAR(3) NULL DEFAULT NULL;
#EndIf

#IfNotTable esign_signatures
CREATE TABLE `esign_signatures` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `tid` int(11) NOT NULL COMMENT 'Table row ID for signature',
  `table` varchar(255) NOT NULL COMMENT 'table name for the signature',
  `uid` int(11) NOT NULL COMMENT 'user id for the signing user',
  `datetime` datetime NOT NULL COMMENT 'datetime of the signature action',
  `is_lock` tinyint(1) NOT NULL DEFAULT '0' COMMENT 'sig, lock or amendment',
  `amendment` text COMMENT 'amendment text, if any',
  `hash` varchar(255) NOT NULL COMMENT 'hash of signed data',
  `signature_hash` varchar(255) NOT NULL COMMENT 'hash of signature itself',
  PRIMARY KEY (`id`),
  KEY `tid` (`tid`),
  KEY `table` (`table`)
) ENGINE=InnoDB AUTO_INCREMENT=1 ;
#EndIf

#IfNotTable shared_attributes
CREATE TABLE `shared_attributes` (
  `pid`          bigint(20)   NOT NULL,
  `encounter`    bigint(20)   NOT NULL COMMENT '0 if patient attribute, else encounter attribute',
  `field_id`     varchar(31)  NOT NULL COMMENT 'references layout_options.field_id',
  `last_update`  datetime     NOT NULL COMMENT 'time of last update',
  `user_id`      bigint(20)   NOT NULL COMMENT 'user who last updated',
  `field_value`  TEXT         NOT NULL,
  PRIMARY KEY (`pid`, `encounter`, `field_id`)
);
#EndIf

#IfMissingColumn layout_options source
ALTER TABLE `layout_options` ADD COLUMN `source` char(1) NOT NULL default 'F'
  COMMENT 'F=Form, D=Demographics, H=History, E=Encounter';
#EndIf

#IfMissingColumn codes sex
ALTER TABLE `codes` ADD COLUMN
  `sex` TINYINT(1) DEFAULT 4 COMMENT '4 = All, 1 = Women Only, 2 = Men Only, 3 = Other Only';
#EndIf

#IfMissingColumn layout_options conditions
ALTER TABLE `layout_options` ADD COLUMN
  `conditions` text NOT NULL DEFAULT '' COMMENT 'serialized array of skip conditions';
#EndIf

#IfNotRow globals gl_name gbl_visit_sensitivity
INSERT INTO `globals` (gl_name, gl_index, gl_value) VALUES ('gbl_visit_sensitivity', 0, '1');
#EndIf

#IfNotRow globals gl_name gbl_visit_voucher_number
INSERT INTO `globals` (gl_name, gl_index, gl_value) VALUES ('gbl_visit_voucher_number', 0, '1');
#EndIf

#IfNotRow globals gl_name gbl_visit_shift
INSERT INTO `globals` (gl_name, gl_index, gl_value) VALUES ('gbl_visit_shift', 0, '1');
#EndIf

#IfNotIndex drug_sales sale_date
CREATE INDEX `sale_date` ON `drug_sales` (`sale_date`);
#EndIf

#IfMissingColumn users_facility warehouse_id
ALTER TABLE `users_facility` ADD COLUMN `warehouse_id` varchar(31) NOT NULL default '';
ALTER TABLE `users_facility` DROP PRIMARY KEY, ADD PRIMARY KEY (`tablename`,`table_id`,`facility_id`,`warehouse_id`);
#EndIf

#IfNotTable ippf2_categories
CREATE TABLE `ippf2_categories` (
  `category_header` varchar(3) DEFAULT NULL,
  `category_name` varchar(255) DEFAULT NULL
) ENGINE=MyISAM ;
INSERT INTO `ippf2_categories` VALUES ('1','CONTRACEPTIVE SERVICES')
                                     ,('211','SRH - ABORTION')
                                     ,('212','SRH - HIV AND AIDS')
                                     ,('213','SRH - STI/RTI')
                                     ,('214','SRH - GYNECOLOGY')
                                     ,('215','SRH - OBSTETRIC')
                                     ,('216','SRH - UROLOGY')
                                     ,('217','SRH - SUBFERTILITY')
                                     ,('218','SRH - SPECIALISED SRH SERVICES')
                                     ,('219','SRH - PEDIATRICS')
                                     ,('220','SRH - OTHER')
                                     ,('4','NON-CLINICAL - ADMINISTRATION')
                                     ,('31','NON-SRH - MEDICAL');

#EndIf


#IfMissingColumn ippf2_categories exclude
ALTER TABLE `ippf2_categories`	ADD COLUMN `exclude` BIT NOT NULL DEFAULT b'0';

UPDATE `ippf2_categories` set `exclude`=b'1' WHERE `category_header`='4';
#EndIf

#IfIndex lang_constants cons_name
ALTER TABLE `lang_constants` DROP INDEX cons_name;
#EndIf

#IfNotIndex lang_constants constant_name
CREATE INDEX `constant_name` ON `lang_constants` (`constant_name`(100));
#EndIf

#IfNotColumnType lang_constants constant_name mediumtext
ALTER TABLE `lang_constants` DROP INDEX constant_name;
ALTER TABLE `lang_constants` CHANGE `constant_name` `constant_name` mediumtext BINARY NOT NULL default '';
CREATE INDEX `constant_name` ON `lang_constants` (`constant_name`(100));
#EndIf

#IfNotColumnType lang_custom constant_name mediumtext
ALTER TABLE `lang_custom` CHANGE `constant_name` `constant_name` mediumtext BINARY NOT NULL default '';
#EndIf

#IfNotIndex lang_definitions cons_lang
CREATE INDEX `cons_lang` ON `lang_definitions` (`cons_id`, `lang_id`);
#EndIf

#IfNotColumnType facility country_code varchar(30)
ALTER TABLE `facility` CHANGE `country_code` `country_code` varchar(30) NOT NULL default '';
#EndIf

#IfNotColumnType layout_options group_name varchar(255)
ALTER TABLE `layout_options` CHANGE `group_name` `group_name` varchar(255) NOT NULL default '';
#EndIf

#IfMissingColumn drug_sales bill_date
ALTER TABLE `drug_sales` ADD COLUMN `bill_date` datetime default NULL;
UPDATE drug_sales AS s, billing     AS b SET s.bill_date = b.bill_date WHERE s.billed = 1 AND s.bill_date IS NULL AND b.pid = s.pid AND b.encounter = s.encounter AND b.bill_date IS NOT NULL AND b.activity = 1;
UPDATE drug_sales AS s, ar_activity AS a SET s.bill_date = a.post_time WHERE s.billed = 1 AND s.bill_date IS NULL AND a.pid = s.pid AND a.encounter = s.encounter;
UPDATE drug_sales AS s SET s.bill_date = s.sale_date WHERE s.billed = 1 AND s.bill_date IS NULL;
#EndIf

#IfNotColumnType billing units int(11)
ALTER TABLE `billing` CHANGE `units` `units` int(11) DEFAULT NULL;
#EndIf

#IfMissingColumn billing pricelevel
ALTER TABLE `billing` ADD COLUMN `pricelevel` varchar(31) default '';
# Fill in missing price levels where possible. Specific to IPPF but will not hurt anyone else.
UPDATE billing AS b, codes AS c, prices AS p
  SET b.pricelevel = p.pr_level WHERE
  b.code_type = 'MA' AND b.activity = 1 AND b.pricelevel = '' AND b.units = 1 AND b.fee > 0.00 AND
  c.code_type = '12' AND c.code = b.code AND c.modifier = b.modifier AND
  p.pr_id = c.id AND p.pr_selector = '' AND p.pr_price = b.fee;
#EndIf

#IfMissingColumn drug_sales pricelevel
ALTER TABLE `drug_sales` ADD COLUMN `pricelevel` varchar(31) default '';
#EndIf

#IfMissingColumn drug_sales selector
ALTER TABLE `drug_sales` ADD COLUMN `selector` varchar(255) default '' comment 'references drug_templates.selector';
# Fill in missing selector values where not ambiguous.
UPDATE drug_sales AS s, drug_templates AS t
  SET s.selector = t.selector WHERE
  s.pid != 0 AND s.selector = '' AND t.drug_id = s.drug_id AND
  (SELECT COUNT(*) FROM drug_templates AS t2 WHERE t2.drug_id = s.drug_id) = 1;
# Fill in missing price levels where not ambiguous.
UPDATE drug_sales AS s, drug_templates AS t, prices AS p
  SET s.pricelevel = p.pr_level WHERE
  s.pid != 0 AND s.selector != '' AND s.pricelevel = '' AND
  t.drug_id = s.drug_id AND t.selector = s.selector AND t.quantity = s.quantity AND
  p.pr_id = s.drug_id AND p.pr_selector = s.selector AND p.pr_price = s.fee;
#EndIf

###IfNotRow2D list_options list_id transactions option_id LBTptreq

#IfNotTable lbt_data

UPDATE list_options SET title = 'Layout-Based Transaction Forms', seq = 9 WHERE list_id = 'lists' AND option_id = 'transactions';
UPDATE list_options SET option_id = 'LBTref'   WHERE list_id = 'transactions' AND option_id = 'Referral';
UPDATE list_options SET option_id = 'LBTptreq' WHERE list_id = 'transactions' AND option_id = 'Patient Request';
UPDATE list_options SET option_id = 'LBTphreq' WHERE list_id = 'transactions' AND option_id = 'Physician Request';
UPDATE list_options SET option_id = 'LBTlegal' WHERE list_id = 'transactions' AND option_id = 'Legal';
UPDATE list_options SET option_id = 'LBTbill'  WHERE list_id = 'transactions' AND option_id = 'Billing';
UPDATE transactions SET title     = 'LBTref'   WHERE title = 'Referral';
UPDATE transactions SET title     = 'LBTptreq' WHERE title = 'Patient Request';
UPDATE transactions SET title     = 'LBTphreq' WHERE title = 'Physician Request';
UPDATE transactions SET title     = 'LBTlegal' WHERE title = 'Legal';
UPDATE transactions SET title     = 'LBTbill'  WHERE title = 'Billing';
UPDATE layout_options SET form_id = 'LBTref'   WHERE form_id = 'REF';

INSERT INTO `layout_options` (`form_id`,`field_id`,`group_name`,`title`,`seq`,`data_type`,`uor`,`fld_length`,
  `max_length`,`list_id`,`titlecols`,`datacols`,`default_value`,`edit_options`,`description`,`fld_rows`)
  VALUES ('LBTptreq','body','1','Details',10,3,2,30,0,'',1,3,'','','Content',5);

INSERT INTO `layout_options` (`form_id`,`field_id`,`group_name`,`title`,`seq`,`data_type`,`uor`,`fld_length`,
  `max_length`,`list_id`,`titlecols`,`datacols`,`default_value`,`edit_options`,`description`,`fld_rows`)
  VALUES ('LBTphreq','body','1','Details',10,3,2,30,0,'',1,3,'','','Content',5);

INSERT INTO `layout_options` (`form_id`,`field_id`,`group_name`,`title`,`seq`,`data_type`,`uor`,`fld_length`,
  `max_length`,`list_id`,`titlecols`,`datacols`,`default_value`,`edit_options`,`description`,`fld_rows`)
  VALUES ('LBTlegal','body','1','Details',10,3,2,30,0,'',1,3,'','','Content',5);

INSERT INTO `layout_options` (`form_id`,`field_id`,`group_name`,`title`,`seq`,`data_type`,`uor`,`fld_length`,
  `max_length`,`list_id`,`titlecols`,`datacols`,`default_value`,`edit_options`,`description`,`fld_rows`)
  VALUES ('LBTbill' ,'body','1','Details',10,3,2,30,0,'',1,3,'','','Content',5);

###EndIf
###IfNotTable lbt_data

CREATE TABLE `lbt_data` (
  `form_id`     bigint(20)   NOT NULL COMMENT 'references transactions.id',
  `field_id`    varchar(31)  NOT NULL COMMENT 'references layout_options.field_id',
  `field_value` TEXT         NOT NULL,
  PRIMARY KEY (`form_id`,`field_id`)
) ENGINE=MyISAM COMMENT='contains all data from layout-based transactions';
#EndIf

#IfColumn transactions                                body
INSERT INTO lbt_data SELECT id, 'body'              , body               FROM transactions WHERE body               != '';
ALTER TABLE transactions DROP COLUMN                  body;
#EndIf
#IfColumn transactions                                refer_date
INSERT INTO lbt_data SELECT id, 'refer_date'        , refer_date         FROM transactions WHERE refer_date         IS NOT NULL;
ALTER TABLE transactions DROP COLUMN                  refer_date;
#EndIf
#IfColumn transactions                                refer_from
INSERT INTO lbt_data SELECT id, 'refer_from'        , refer_from         FROM transactions WHERE refer_from         != 0;
ALTER TABLE transactions DROP COLUMN                  refer_from;
#EndIf
#IfColumn transactions                                refer_to
INSERT INTO lbt_data SELECT id, 'refer_to'          , refer_to           FROM transactions WHERE refer_to           != 0;
ALTER TABLE transactions DROP COLUMN                  refer_to;
#EndIf
#IfColumn transactions                                refer_diag
INSERT INTO lbt_data SELECT id, 'refer_diag'        , refer_diag         FROM transactions WHERE refer_diag         != '';
ALTER TABLE transactions DROP COLUMN                  refer_diag;
#EndIf
#IfColumn transactions                                refer_risk_level
INSERT INTO lbt_data SELECT id, 'refer_risk_level'  , refer_risk_level   FROM transactions WHERE refer_risk_level   != '';
ALTER TABLE transactions DROP COLUMN                  refer_risk_level;
#EndIf
#IfColumn transactions                                refer_vitals
INSERT INTO lbt_data SELECT id, 'refer_vitals'      , refer_vitals       FROM transactions WHERE refer_vitals       != 0;
ALTER TABLE transactions DROP COLUMN                  refer_vitals;
#EndIf
#IfColumn transactions                                refer_external
INSERT INTO lbt_data SELECT id, 'refer_external'    , refer_external     FROM transactions WHERE refer_external     != 0;
ALTER TABLE transactions DROP COLUMN                  refer_external;
#EndIf
#IfColumn transactions                                refer_related_code
INSERT INTO lbt_data SELECT id, 'refer_related_code', refer_related_code FROM transactions WHERE refer_related_code != '';
ALTER TABLE transactions DROP COLUMN                  refer_related_code;
#EndIf
#IfColumn transactions                                refer_reply_date
INSERT INTO lbt_data SELECT id, 'refer_reply_date'  , refer_reply_date   FROM transactions WHERE refer_reply_date   IS NOT NULL;
ALTER TABLE transactions DROP COLUMN                  refer_reply_date;
#EndIf
#IfColumn transactions                                reply_date
INSERT INTO lbt_data SELECT id, 'reply_date'        , reply_date         FROM transactions WHERE reply_date         IS NOT NULL;
ALTER TABLE transactions DROP COLUMN                  reply_date;
#EndIf
#IfColumn transactions                                reply_from
INSERT INTO lbt_data SELECT id, 'reply_from'        , reply_from         FROM transactions WHERE reply_from         != '';
ALTER TABLE transactions DROP COLUMN                  reply_from;
#EndIf
#IfColumn transactions                                reply_init_diag
INSERT INTO lbt_data SELECT id, 'reply_init_diag'   , reply_init_diag    FROM transactions WHERE reply_init_diag    != '';
ALTER TABLE transactions DROP COLUMN                  reply_init_diag;
#EndIf
#IfColumn transactions                                reply_final_diag
INSERT INTO lbt_data SELECT id, 'reply_final_diag'  , reply_final_diag   FROM transactions WHERE reply_final_diag   != '';
ALTER TABLE transactions DROP COLUMN                  reply_final_diag;
#EndIf
#IfColumn transactions                                reply_documents
INSERT INTO lbt_data SELECT id, 'reply_documents'   , reply_documents    FROM transactions WHERE reply_documents    != '';
ALTER TABLE transactions DROP COLUMN                  reply_documents;
#EndIf
#IfColumn transactions                                reply_findings
INSERT INTO lbt_data SELECT id, 'reply_findings'    , reply_findings     FROM transactions WHERE reply_findings     != '';
ALTER TABLE transactions DROP COLUMN                  reply_findings;
#EndIf
#IfColumn transactions                                reply_services
INSERT INTO lbt_data SELECT id, 'reply_services'    , reply_services     FROM transactions WHERE reply_services     != '';
ALTER TABLE transactions DROP COLUMN                  reply_services;
#EndIf
#IfColumn transactions                                reply_recommend
INSERT INTO lbt_data SELECT id, 'reply_recommend'   , reply_recommend    FROM transactions WHERE reply_recommend    != '';
ALTER TABLE transactions DROP COLUMN                  reply_recommend;
#EndIf
#IfColumn transactions                                reply_rx_refer
INSERT INTO lbt_data SELECT id, 'reply_rx_refer'    , reply_rx_refer     FROM transactions WHERE reply_rx_refer     != '';
ALTER TABLE transactions DROP COLUMN                  reply_rx_refer;
#EndIf
#IfColumn transactions                                reply_related_code
INSERT INTO lbt_data SELECT id, 'reply_related_code', reply_related_code FROM transactions WHERE reply_related_code != '';
ALTER TABLE transactions DROP COLUMN                  reply_related_code;
#EndIf

# Conversion of transaction referrals to LBF referrals, 2016-03-11:

#IfNotRow layout_options form_id LBFref

INSERT INTO list_options (list_id,option_id,title,seq,option_value) VALUES ('lbfnames','LBFref','Referral',1,0);

# Create forms table entries to match what is in transactions.
INSERT INTO forms
  (date, encounter, form_name, form_id, pid, user, groupname, authorized, formdir)
  SELECT date, 0, id, 0, pid, user, groupname, authorized, 'LBFref'
  FROM transactions WHERE title = 'LBTref';

# form_name is now transactions.id and also lbt_data.form_id.
# Next generate form_id values by creating one lbf_data entry per form.
INSERT INTO lbf_data (field_id, field_value)
  SELECT '#LBTref#', id FROM forms WHERE formdir = 'LBFref' AND encounter = 0 AND deleted = 0;

# Copy these new form_id values to the forms table.
UPDATE forms AS f, lbf_data AS fd
  SET f.form_id = fd.form_id WHERE
  fd.field_id = '#LBTref#' AND fd.field_value = f.id;

# Remove the dummy lbf_data rows.
DELETE FROM lbf_data WHERE field_id = '#LBTref#';

# Now create real lbf_data entries from lbt_data and the information in the forms table.
INSERT INTO lbf_data (form_id, field_id, field_value)
  SELECT f.form_id, td.field_id, td.field_value
  FROM forms AS f, lbt_data AS td WHERE
  f.formdir = 'LBFref' AND f.encounter = 0 AND f.deleted = 0 AND td.form_id = f.form_name;

# Fix forms.form_name which held the old form ID.
UPDATE forms AS f
  SET f.form_name = 'Referral' WHERE
  f.formdir = 'LBFref' AND f.encounter = 0 AND f.deleted = 0;

# Fix forms.encounter for inbound referrals.
# Try the most recent encounter on or after the referral creation date.
UPDATE forms AS f
  SET f.encounter = COALESCE((
    SELECT fe.encounter FROM form_encounter AS fe, lbf_data AS fd
    WHERE
    fe.pid = f.pid AND fe.date >= f.date AND fd.field_value > '3' AND
    fd.form_id = f.form_id AND fd.field_id = 'refer_external'
    ORDER BY fe.date, fe.encounter LIMIT 1
  ), 0)
  WHERE f.formdir = 'LBFref' AND f.encounter = 0 AND f.deleted = 0;

# Fix forms.encounter for remaining referrals.
# Try the most recent encounter dated on or before the referral creation date.
UPDATE forms AS f
  SET f.encounter = COALESCE((
  SELECT fe.encounter FROM form_encounter AS fe WHERE
  fe.pid = f.pid AND fe.date <= f.date
  ORDER BY fe.date DESC, fe.encounter DESC LIMIT 1
  ), 0)
  WHERE f.formdir = 'LBFref' AND f.encounter = 0 AND f.deleted = 0;

# Where that did not work try the first encounter for the patient.
UPDATE forms AS f
  SET f.encounter =  COALESCE((
  SELECT fe.encounter FROM form_encounter AS fe WHERE
  fe.pid = f.pid
  ORDER BY fe.date, fe.encounter LIMIT 1
  ), 0)
  WHERE f.formdir = 'LBFref' AND f.encounter = 0 AND f.deleted = 0;

# Create form_encounter table entries for referrals that still have no encounter.
SELECT @i:=(SELECT id FROM sequences);
INSERT INTO form_encounter (date, reason, pid, encounter)
  SELECT DATE(f.date), 'Dummy visit for referral', f.pid, @i:=@i+1
  FROM forms AS f
  WHERE f.formdir = 'LBFref' AND f.encounter = 0 AND f.deleted = 0;
UPDATE sequences set id = @i;

# Create the 'newpatient' forms table rows for the new form_encounter rows.
INSERT INTO forms (date, encounter, form_name, form_id, pid, user, groupname, authorized, formdir)
  SELECT fe.date, fe.encounter, 'New Patient Encounter', fe.id, fe.pid, 'admin', 'Default', '1', 'newpatient'
  FROM form_encounter AS fe
  WHERE fe.reason = 'Dummy visit for referral' AND
  (SELECT COUNT(*) FROM forms WHERE pid = fe.pid AND encounter = fe.encounter AND formdir = 'newpatient' and deleted = 0) = 0;

# Link remaining referrals to these created encounters.
UPDATE forms AS f
  SET f.encounter = COALESCE((
  SELECT fe.encounter FROM form_encounter AS fe WHERE
  fe.pid = f.pid
  ORDER BY fe.date, fe.encounter LIMIT 1
  ), 0)
  WHERE f.formdir = 'LBFref' AND f.encounter = 0 AND f.deleted = 0;

# TBD: Might need to assign a facility to the new encounters.

DELETE FROM layout_options WHERE form_id = 'LBFref';
UPDATE layout_options SET form_id = 'LBFref' WHERE form_id = 'LBTref';

# Finally, delete all traces of referral transactions.
DELETE FROM lbt_data WHERE
  (SELECT id FROM transactions AS t WHERE t.id = lbt_data.form_id AND t.title = 'LBTref')
  IS NOT NULL;
DELETE FROM transactions WHERE title = 'LBTref';
DELETE FROM list_options WHERE list_id = 'transactions' AND option_id = 'LBTref';

#EndIf

#IfMissingColumn forms issue_id
ALTER TABLE `forms` ADD COLUMN `issue_id` bigint(20) NOT NULL default 0 COMMENT 'references lists.id to identify a case';
#EndIf

#IfMissingColumn forms provider_id
ALTER TABLE `forms` ADD COLUMN `provider_id` bigint(20) NOT NULL default 0 COMMENT 'references users.id to identify a provider';
#EndIf

#IfNotColumnType list_options notes VARCHAR(4095)
ALTER TABLE `list_options` CHANGE `notes` `notes` VARCHAR(4095) NOT NULL DEFAULT '';
#EndIf

#IfNotRow2D issue_types category ippf_specific type cervical_cancer
INSERT INTO issue_types(`ordering`,`category`,`type`,`plural`,`singular`,`abbreviation`,`style`,`force_show`) VALUES
  ('65','ippf_specific','cervical_cancer','Cervical Cancer','Cervical Cancer','CC','0','0');
#EndIf

#IfMissingColumn list_options activity
ALTER TABLE `list_options` ADD COLUMN `activity` TINYINT DEFAULT 1 NOT NULL;
#EndIf

#IfMissingColumn voids reason
ALTER TABLE `voids` ADD COLUMN `reason` VARCHAR(31) default '';
#EndIf

#IfMissingColumn voids notes
ALTER TABLE `voids` ADD COLUMN `notes` VARCHAR(255) default '';
#EndIf

#IfNotIndex log patient_id
CREATE INDEX `patient_id` ON `log` (`patient_id`);
#EndIf

#IfNotTable patient_tracker
CREATE TABLE `patient_tracker` (
  `id`                     bigint(20)   NOT NULL auto_increment,
  `date`                   datetime     DEFAULT NULL,
  `apptdate`               date         DEFAULT NULL,
  `appttime`               time         DEFAULT NULL,
  `eid`                    bigint(20)   NOT NULL default '0',
  `pid`                    bigint(20)   NOT NULL default '0',
  `original_user`          varchar(255) NOT NULL default '' COMMENT 'This is the user that created the original record',
  `encounter`              bigint(20)   NOT NULL default '0',
  `lastseq`                varchar(4)   NOT NULL default '' COMMENT 'The element file should contain this number of elements',
  `random_drug_test`       TINYINT(1)   DEFAULT NULL COMMENT 'NULL if not randomized. If randomized, 0 is no, 1 is yes', 
  `drug_screen_completed`  TINYINT(1)   NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY (`eid`),
  KEY (`pid`)
) ENGINE=MyISAM AUTO_INCREMENT=1;
#EndIf

#IfNotTable patient_tracker_element
CREATE TABLE `patient_tracker_element` (
  `pt_tracker_id`      bigint(20)   NOT NULL default '0' COMMENT 'maps to id column in patient_tracker table',
  `start_datetime`     datetime     DEFAULT NULL,
  `room`               varchar(20)  NOT NULL default '',
  `status`             varchar(31)  NOT NULL default '',
  `seq`                varchar(4)   NOT NULL default '' COMMENT 'This is a numerical sequence for this pt_tracker_id events',
  `user`               varchar(255) NOT NULL default '' COMMENT 'This is the user that created this element',
  KEY  (`pt_tracker_id`,`seq`)
) ENGINE=MyISAM;
#EndIf

#IfMissingColumn openemr_postcalendar_events pc_room
ALTER TABLE `openemr_postcalendar_events` ADD `pc_room` varchar(20) NOT NULL DEFAULT '' ;
#EndIf

#IfMissingColumn list_options toggle_setting_1
ALTER TABLE `list_options` ADD COLUMN `toggle_setting_1` tinyint(1) NOT NULL default '0';
UPDATE `list_options` SET `notes`='FF2414|10' , `toggle_setting_1`='1' WHERE `option_id`='@' AND `list_id` = 'apptstat';
UPDATE `list_options` SET `notes`='FF6619|10' , `toggle_setting_1`='1' WHERE `option_id`='~' AND `list_id` = 'apptstat';
#EndIf
 
#IfMissingColumn list_options toggle_setting_2
ALTER TABLE `list_options` ADD COLUMN `toggle_setting_2` tinyint(1) NOT NULL DEFAULT '0';
UPDATE `list_options` SET `notes`='0BBA34|0' , `toggle_setting_2`='1' WHERE `option_id`='!' AND `list_id` = 'apptstat';
UPDATE `list_options` SET `notes`='FEFDCF|0' , `toggle_setting_2`='1' WHERE `option_id`='>' AND `list_id` = 'apptstat';
UPDATE `list_options` SET `notes`='FEFDCF|0' WHERE `option_id`='-' AND `list_id` = 'apptstat';
UPDATE `list_options` SET `notes`='FFC9F8|0' WHERE `option_id`='*' AND `list_id` = 'apptstat';
UPDATE `list_options` SET `notes`='87FF1F|0' WHERE `option_id`='+' AND `list_id` = 'apptstat';
UPDATE `list_options` SET `notes`='BFBFBF|0' WHERE `option_id`='x' AND `list_id` = 'apptstat';
UPDATE `list_options` SET `notes`='BFBFBF|0' WHERE `option_id`='?' AND `list_id` = 'apptstat';
UPDATE `list_options` SET `notes`='FFFF2B|0' WHERE `option_id`='#' AND `list_id` = 'apptstat';
UPDATE `list_options` SET `notes`='52D9DE|10' WHERE `option_id`='<' AND `list_id` = 'apptstat';
UPDATE `list_options` SET `notes`='C0FF96|0' WHERE `option_id`='$' AND `list_id` = 'apptstat';
UPDATE `list_options` SET `notes`='BFBFBF|0' WHERE `option_id`='%' AND `list_id` = 'apptstat';
#EndIf

#IfNotRow2D list_options list_id lists option_id void_reasons
INSERT INTO list_options (list_id, option_id, title, seq, is_default) VALUES ('lists','void_reasons','Void Reasons', 1,0);
INSERT INTO `list_options` (`list_id`, `option_id`, `title`, `seq`, `is_default`, `option_value`, `mapping`, `notes`, `codes`, `activity`, `toggle_setting_1`, `toggle_setting_2`) VALUES ('void_reasons','ADJ01','Adj - Reason Corrected',50,0,0,'','','',1,0,0);
INSERT INTO `list_options` (`list_id`, `option_id`, `title`, `seq`, `is_default`, `option_value`, `mapping`, `notes`, `codes`, `activity`, `toggle_setting_1`, `toggle_setting_2`) VALUES ('void_reasons','ADJ02','Adj - Amount Corrected',51,0,0,'','','',1,0,0);
INSERT INTO `list_options` (`list_id`, `option_id`, `title`, `seq`, `is_default`, `option_value`, `mapping`, `notes`, `codes`, `activity`, `toggle_setting_1`, `toggle_setting_2`) VALUES ('void_reasons','DEF01','Default Price Level Modified',40,0,0,'','','',1,0,0);
INSERT INTO `list_options` (`list_id`, `option_id`, `title`, `seq`, `is_default`, `option_value`, `mapping`, `notes`, `codes`, `activity`, `toggle_setting_1`, `toggle_setting_2`) VALUES ('void_reasons','DEF02','Default Provider Modified',41,0,0,'','','',1,0,0);
INSERT INTO `list_options` (`list_id`, `option_id`, `title`, `seq`, `is_default`, `option_value`, `mapping`, `notes`, `codes`, `activity`, `toggle_setting_1`, `toggle_setting_2`) VALUES ('void_reasons','OTH','Other',80,0,0,'','','',1,0,0);
INSERT INTO `list_options` (`list_id`, `option_id`, `title`, `seq`, `is_default`, `option_value`, `mapping`, `notes`, `codes`, `activity`, `toggle_setting_1`, `toggle_setting_2`) VALUES ('void_reasons','PAY01','Payment - Amount Modified',60,0,0,'','','',1,0,0);
INSERT INTO `list_options` (`list_id`, `option_id`, `title`, `seq`, `is_default`, `option_value`, `mapping`, `notes`, `codes`, `activity`, `toggle_setting_1`, `toggle_setting_2`) VALUES ('void_reasons','PAY02','Payment - Method Modified',61,0,0,'','','',1,0,0);
INSERT INTO `list_options` (`list_id`, `option_id`, `title`, `seq`, `is_default`, `option_value`, `mapping`, `notes`, `codes`, `activity`, `toggle_setting_1`, `toggle_setting_2`) VALUES ('void_reasons','PAY03','Payment - Posting Date Correction',62,0,0,'','','',1,0,0);
INSERT INTO `list_options` (`list_id`, `option_id`, `title`, `seq`, `is_default`, `option_value`, `mapping`, `notes`, `codes`, `activity`, `toggle_setting_1`, `toggle_setting_2`) VALUES ('void_reasons','PROD01','Product - Added',20,0,0,'','','',1,0,0);
INSERT INTO `list_options` (`list_id`, `option_id`, `title`, `seq`, `is_default`, `option_value`, `mapping`, `notes`, `codes`, `activity`, `toggle_setting_1`, `toggle_setting_2`) VALUES ('void_reasons','PROD02','Product - Deleted and Replaced',21,0,0,'','','',1,0,0);
INSERT INTO `list_options` (`list_id`, `option_id`, `title`, `seq`, `is_default`, `option_value`, `mapping`, `notes`, `codes`, `activity`, `toggle_setting_1`, `toggle_setting_2`) VALUES ('void_reasons','PROD03','Product - Deleted; Not provided',22,0,0,'','','',1,0,0);
INSERT INTO `list_options` (`list_id`, `option_id`, `title`, `seq`, `is_default`, `option_value`, `mapping`, `notes`, `codes`, `activity`, `toggle_setting_1`, `toggle_setting_2`) VALUES ('void_reasons','PROD04','Product - Price Level Modified ',30,0,0,'','','',1,0,0);
INSERT INTO `list_options` (`list_id`, `option_id`, `title`, `seq`, `is_default`, `option_value`, `mapping`, `notes`, `codes`, `activity`, `toggle_setting_1`, `toggle_setting_2`) VALUES ('void_reasons','PROD05','Product - Quantity Modified',31,0,0,'','','',1,0,0);
INSERT INTO `list_options` (`list_id`, `option_id`, `title`, `seq`, `is_default`, `option_value`, `mapping`, `notes`, `codes`, `activity`, `toggle_setting_1`, `toggle_setting_2`) VALUES ('void_reasons','PROD06','Product - Warehouse Modified',32,0,0,'','','',1,0,0);
INSERT INTO `list_options` (`list_id`, `option_id`, `title`, `seq`, `is_default`, `option_value`, `mapping`, `notes`, `codes`, `activity`, `toggle_setting_1`, `toggle_setting_2`) VALUES ('void_reasons','SRV01','Service - Added',1,0,0,'','','',1,0,0);
INSERT INTO `list_options` (`list_id`, `option_id`, `title`, `seq`, `is_default`, `option_value`, `mapping`, `notes`, `codes`, `activity`, `toggle_setting_1`, `toggle_setting_2`) VALUES ('void_reasons','SRV02','Service - Deleted and Replaced',2,0,0,'','','',1,0,0);
INSERT INTO `list_options` (`list_id`, `option_id`, `title`, `seq`, `is_default`, `option_value`, `mapping`, `notes`, `codes`, `activity`, `toggle_setting_1`, `toggle_setting_2`) VALUES ('void_reasons','SRV03','Service - Deleted; Not provided',3,0,0,'','','',1,0,0);
INSERT INTO `list_options` (`list_id`, `option_id`, `title`, `seq`, `is_default`, `option_value`, `mapping`, `notes`, `codes`, `activity`, `toggle_setting_1`, `toggle_setting_2`) VALUES ('void_reasons','SRV04','Service - Provider Modified',4,0,0,'','','',1,0,0);
INSERT INTO `list_options` (`list_id`, `option_id`, `title`, `seq`, `is_default`, `option_value`, `mapping`, `notes`, `codes`, `activity`, `toggle_setting_1`, `toggle_setting_2`) VALUES ('void_reasons','SRV05','Service - Quanitity Modified',5,0,0,'','','',1,0,0);
#EndIf

#IfNotRow2D list_options list_id lists option_id patient_flow_board_rooms
INSERT INTO list_options (list_id,option_id,title) VALUES ('lists','patient_flow_board_rooms','Patient Flow Board Rooms');
INSERT INTO list_options (list_id,option_id,title,seq) VALUES ('patient_flow_board_rooms', '1', 'Room 1', 10);
INSERT INTO list_options (list_id,option_id,title,seq) VALUES ('patient_flow_board_rooms', '2', 'Room 2', 20);
INSERT INTO list_options (list_id,option_id,title,seq) VALUES ('patient_flow_board_rooms', '3', 'Room 3', 30);
#EndIf

#IfNotColumnType user_settings setting_label varchar(100)
ALTER TABLE `user_settings` CHANGE `setting_label` `setting_label` varchar(100) NOT NULL;
#EndIf

#IfMissingColumn ar_activity deleted
ALTER TABLE `ar_activity` ADD COLUMN `deleted` datetime DEFAULT NULL COMMENT 'NULL if active, otherwise when voided';
#EndIf

#IfNotColumnType facility facility_npi varchar(50)
ALTER TABLE `facility` CHANGE `facility_npi` `facility_npi` varchar(50) NOT NULL default '';
#EndIf

#IfNotColumnType drugs form varchar(31)
ALTER TABLE `drugs` CHANGE `form`  `form`  varchar(31) NOT NULL default '0';
#EndIf
#IfNotColumnType drugs unit varchar(31)
ALTER TABLE `drugs` CHANGE `unit`  `unit`  varchar(31) NOT NULL default '0';
#EndIf
#IfNotColumnType drugs route varchar(31)
ALTER TABLE `drugs` CHANGE `route` `route` varchar(31) NOT NULL default '0';
#EndIf

#IfNotColumnType lbf_data field_value longtext
ALTER TABLE `lbf_data` CHANGE `field_value` `field_value` longtext NOT NULL;
#EndIf

#IfMissingColumn drug_templates pkgqty
ALTER TABLE `drug_templates` ADD COLUMN `pkgqty` float NOT NULL DEFAULT 1.0 COMMENT 'Number of product items per template item';
#EndIf

#IfMissingColumn patient_data gender
ALTER TABLE `patient_data` ADD COLUMN `gender` varchar(31) NOT NULL default '';
INSERT INTO `layout_options` (`form_id`,`field_id`,`group_name`,`title`,`seq`,`data_type`,`uor`,`fld_length`,
  `max_length`,`list_id`,`titlecols`,`datacols`,`default_value`,`edit_options`,`description`,`fld_rows`)
  VALUES ('DEM', 'gender', '1Who', 'Gender', 8, 1, 1, 0, 0, 'Gender', 1, 1, '', 'N', '', 0);
#EndIf

#IfMissingColumn patient_data sexual_orientation
ALTER TABLE `patient_data` ADD COLUMN `sexual_orientation` varchar(31) NOT NULL default '';
INSERT INTO `layout_options` (`form_id`,`field_id`,`group_name`,`title`,`seq`,`data_type`,`uor`,`fld_length`,
  `max_length`,`list_id`,`titlecols`,`datacols`,`default_value`,`edit_options`,`description`,`fld_rows`)
  VALUES ('DEM', 'sexual_orientation', '1Who', 'Sexual Orientation', 8, 1, 1, 0, 0, 'Sexual_Orientation', 1, 1, '', 'N', '', 0);
#EndIf

#IfNotRow2D list_options list_id lists option_id Gender
INSERT INTO list_options (list_id, option_id, title, seq, is_default) VALUES ('lists','Gender','Gender', 1,0);
INSERT INTO `list_options` (`list_id`, `option_id`, `title`, `seq`, `is_default`) VALUES ('Gender','Fem' ,'Female',10,1);
INSERT INTO `list_options` (`list_id`, `option_id`, `title`, `seq`, `is_default`) VALUES ('Gender','Male','Male'  ,20,0);
INSERT INTO `list_options` (`list_id`, `option_id`, `title`, `seq`, `is_default`) VALUES ('Gender','FTM' ,'FTM'   ,30,0);
INSERT INTO `list_options` (`list_id`, `option_id`, `title`, `seq`, `is_default`) VALUES ('Gender','MTF' ,'MTF'   ,40,0);
INSERT INTO `list_options` (`list_id`, `option_id`, `title`, `seq`, `is_default`) VALUES ('Gender','Oth' ,'Other' ,50,0);
INSERT INTO `list_options` (`list_id`, `option_id`, `title`, `seq`, `is_default`) VALUES ('Gender','NS','Not Specified',60,0);
#EndIf

#IfNotRow2D list_options list_id lists option_id Sexual_Orientation
INSERT INTO list_options (list_id, option_id, title, seq, is_default) VALUES ('lists','Sexual_Orientation','Sexual Orientation', 1,0);
INSERT INTO `list_options` (`list_id`, `option_id`, `title`, `seq`, `is_default`) VALUES ('Sexual_Orientation','Bis','Bisexual'     ,10,0);
INSERT INTO `list_options` (`list_id`, `option_id`, `title`, `seq`, `is_default`) VALUES ('Sexual_Orientation','Gay','Gay'          ,20,0);
INSERT INTO `list_options` (`list_id`, `option_id`, `title`, `seq`, `is_default`) VALUES ('Sexual_Orientation','Het','Heterosexual' ,30,0);
INSERT INTO `list_options` (`list_id`, `option_id`, `title`, `seq`, `is_default`) VALUES ('Sexual_Orientation','Les','Lesbian'      ,40,0);
INSERT INTO `list_options` (`list_id`, `option_id`, `title`, `seq`, `is_default`) VALUES ('Sexual_Orientation','Msm','MSM'          ,50,0);
INSERT INTO `list_options` (`list_id`, `option_id`, `title`, `seq`, `is_default`) VALUES ('Sexual_Orientation','Oth','Other'        ,60,0);
INSERT INTO `list_options` (`list_id`, `option_id`, `title`, `seq`, `is_default`) VALUES ('Sexual_Orientation','NS' ,'Not Specified',70,0);
#EndIf

#IfNotRow globals gl_name gbl_fac_warehouse_restrictions
INSERT INTO `globals` (gl_name, gl_index, gl_value) SELECT 'gbl_fac_warehouse_restrictions', gl_index, gl_value
  FROM globals WHERE gl_name = 'restrict_user_facility';
#EndIf

#IfMissingColumn patient_data dupscore
ALTER TABLE `patient_data` ADD COLUMN `dupscore` INT NOT NULL default -9;
#EndIf

#IfMissingColumn facility related_code
ALTER TABLE `facility` ADD COLUMN `related_code` VARCHAR(255) NOT NULL default '';
#EndIf

#IfNotTable layout_group_properties
CREATE TABLE `layout_group_properties` (
  grp_form_id     varchar(31)    not null,
  grp_group_id    varchar(31)    not null default '' comment 'empty when representing the whole form',
  grp_title       varchar(63)    not null default '' comment 'descriptive name of the form or group',
  grp_subtitle    varchar(63)    not null default '' comment 'for display under the title',
  grp_mapping     varchar(31)    not null default '' comment 'the form category',
  grp_seq         int(11)        not null default 0  comment 'optional order within mapping',
  grp_activity    tinyint(1)     not null default 1,
  grp_repeats     int(11)        not null default 0,
  grp_columns     int(11)        not null default 0,
  grp_size        int(11)        not null default 0,
  grp_issue_type  varchar(75)    not null default '',
  grp_aco_spec    varchar(63)    not null default '',
  grp_services    varchar(4095)  not null default '',
  grp_products    varchar(4095)  not null default '',
  grp_diags       varchar(4095)  not null default '',
  PRIMARY KEY (grp_form_id, grp_group_id)
) ENGINE=MyISAM;
ALTER TABLE layout_options ADD COLUMN group_id VARCHAR(31) NOT NULL default '' AFTER group_name;
#ConvertLayoutProperties
ALTER TABLE layout_options DROP COLUMN group_name;
DELETE FROM list_options WHERE list_id = 'lbfnames';
DELETE FROM list_options WHERE list_id = 'lists' AND option_id = 'lbfnames';
DELETE FROM list_options WHERE list_id = 'transactions';
DELETE FROM list_options WHERE list_id = 'lists' AND option_id = 'transactions';
#EndIf

#IfMissingColumn layout_group_properties grp_save_close
ALTER TABLE `layout_group_properties` ADD COLUMN `grp_save_close` tinyint(1) not null default 0;
#EndIf

#IfMissingColumn layout_group_properties grp_init_open
ALTER TABLE `layout_group_properties` ADD COLUMN `grp_init_open` tinyint(1) not null default 0;
UPDATE layout_group_properties AS p, layout_options AS o SET p.grp_init_open = 1 WHERE
  o.form_id = p.grp_form_id AND o.group_id = p.grp_group_id AND o.uor > 0 AND o.edit_options LIKE '%I%';
UPDATE layout_group_properties AS p SET p.grp_init_open = 1 WHERE p.grp_group_id = '1' AND
  (SELECT count(*) FROM layout_options AS o WHERE o.form_id = p.grp_form_id AND o.uor > 0 AND o.edit_options LIKE '%I%') = 0;
#EndIf

#IfMissingColumn patient_data prefername
ALTER TABLE `patient_data` ADD COLUMN `prefername` text NOT NULL;
INSERT INTO `layout_options` (`form_id`,`field_id`,`group_id`,`title`,`seq`,`data_type`,`uor`,`fld_length`,
  `max_length`,`list_id`,`titlecols`,`datacols`,`default_value`,`edit_options`,`description`,`conditions`)
  VALUES ('DEM', 'prefername', '1', 'Preferred Name', 6, 2, 0, 15, 50, '', 1, 1, '', '', '', '');
#EndIf

#IfMissingColumn billing chargecat
ALTER TABLE `billing` ADD COLUMN `chargecat` varchar(31) default '';
#EndIf

#IfMissingColumn drug_sales chargecat
ALTER TABLE `drug_sales` ADD COLUMN `chargecat` varchar(31) default '';
#EndIf

#IfNotRow2D list_options list_id lists option_id chargecats
INSERT INTO `list_options` (`list_id`, `option_id`, `title`, `seq`, `is_default`)
  VALUES ('lists','chargecats','Charge Categories', 1,0);
INSERT INTO list_options (`list_id`, `option_id`, `title`, `seq`, `is_default`) 
  SELECT 'chargecats', option_id, title, seq, is_default FROM list_options WHERE
  list_id = 'adjreason' AND activity = 1 AND (option_id = 'C00001' OR notes LIKE '%=Ins%');
DELETE FROM list_options WHERE
  list_id = 'adjreason' AND activity = 1 AND (option_id = 'C00001' OR notes LIKE '%=Ins%');
UPDATE billing AS b, ar_activity AS a, list_options AS l
  SET b.chargecat = a.memo WHERE b.chargecat = '' AND
  a.pid = b.pid AND a.encounter = b.encounter AND a.deleted IS NULL AND
  ( a.pay_amount = 0 OR a.adj_amount != 0 ) AND
  ( a.code_type = '' OR ( a.code_type = b.code_type AND a.code = b.code )) AND
  l.list_id = 'chargecats' AND l.option_id = a.memo and l.activity = 1;
UPDATE drug_sales AS s, ar_activity AS a, list_options AS l
  SET s.chargecat = a.memo WHERE s.chargecat = '' AND
  a.pid = s.pid AND a.encounter = s.encounter AND a.deleted IS NULL AND
  ( a.pay_amount = 0 OR a.adj_amount != 0 ) AND
  ( a.code_type = '' OR ( a.code_type = 'PROD' AND a.code = s.drug_id )) AND
  l.list_id = 'chargecats' AND l.option_id = a.memo and l.activity = 1;
DELETE FROM ar_activity WHERE pay_amount = 0 AND adj_amount = 0 AND deleted IS NULL AND
  memo IN (SELECT option_id FROM list_options WHERE list_id = 'chargecats' AND activity = 1);
#EndIf

#IfMissingColumn facility related_code_2
ALTER TABLE `facility` ADD COLUMN `related_code_2` VARCHAR(255) NOT NULL default '';
#EndIf

#IfMissingColumn lang_languages lang_is_rtl
ALTER TABLE `lang_languages` ADD COLUMN `lang_is_rtl` TINYINT DEFAULT 0;
UPDATE `lang_languages` SET `lang_is_rtl` = 1 WHERE `lang_code` IN ('he','ar') OR `lang_description` IN ('Hebrew','Arabic');
#EndIf

#IfNotRow lang_languages lang_code ar
INSERT INTO `lang_languages` (`lang_id`, `lang_code`, `lang_description`, `lang_is_rtl`) VALUES (19,'ar','Arabic', 1);
#EndIf

#---------- The following for challenge questions at login. ----------#

#IfNotRow2D list_options list_id lists option_id login_security_questions
INSERT INTO `list_options` (`list_id`, `option_id`, `title`, `seq`) VALUES ('lists','login_security_questions','Login Security Questions', 1);
INSERT INTO `list_options` (`list_id`, `option_id`, `title`, `seq`) VALUES ('login_security_questions','mmname'  ,'What is your mother''s maiden name?' ,10);
INSERT INTO `list_options` (`list_id`, `option_id`, `title`, `seq`) VALUES ('login_security_questions','firstpet','What was the name of your first pet?',20);
#EndIf

#IfNotTable login_mfa_registrations
CREATE TABLE `login_mfa_registrations` (
  `user_id`         bigint(20)     NOT NULL,
  `name`            varchar(256)   NOT NULL,
  `last_challenge`  datetime       DEFAULT NULL,
  `method`          varchar(31)    NOT NULL COMMENT 'Q&A, U2F, TOTP etc.',
  `var1`            varchar(4096)  NOT NULL DEFAULT '' COMMENT 'Question, U2F registration etc.',
  `var2`            varchar(256)   NOT NULL DEFAULT '' COMMENT 'Answer, last U2F count etc.',
  PRIMARY KEY (`user_id`, `name`)
) ENGINE=MyISAM;
#EndIf

#IfMissingColumn users_secure last_challenge_response
ALTER TABLE `users_secure` ADD COLUMN `last_challenge_response` datetime DEFAULT NULL;
#EndIf

#IfMissingColumn users_secure num_failed_logins
ALTER TABLE `users_secure` ADD COLUMN `num_failed_logins` int(11) NOT NULL DEFAULT 0;
#EndIf

#IfMissingColumn users_secure login_work_area
ALTER TABLE `users_secure` ADD COLUMN `login_work_area` varchar(32768) NOT NULL DEFAULT '';
#EndIf
