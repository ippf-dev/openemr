update `globals` set gl_value=0 where gl_name in ('esign_individual','lock_esign_individual');

#IfMissingColumn procedure_report review_status
ALTER TABLE `procedure_report` ADD COLUMN `review_status` varchar(31) NOT NULL default '';
#EndIf

update registry set state=0 where directory in ("vitalsM","vitalsigns");

#IfMissingColumn procedure_order procedure_type_id
ALTER TABLE `procedure_order` 
ADD COLUMN `procedure_type_id` BIGINT(20) NOT NULL COMMENT "references procedure_type.procedure_type_id";
#EndIf


#IfMissingColumn procedure_result procedure_type_id
ALTER TABLE `procedure_result` 
ADD COLUMN `procedure_type_id` BIGINT(20) NOT NULL COMMENT "references procedure_type.procedure_type_id";
#EndIf