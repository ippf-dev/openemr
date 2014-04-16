#IfMissingColumn procedure_report review_status
ALTER TABLE `procedure_report` ADD COLUMN `review_status` varchar(31) NOT NULL default '';
#EndIf
