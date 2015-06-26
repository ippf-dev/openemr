
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
                                     ,('215','SRH - OBSTETRIC  ')
                                     ,('216','SRH - UROLOGY')
                                     ,('217','SRH - SUBFERTILITY')
                                     ,('218','SRH- SPECIALISED SRH SERVICES')
                                     ,('219','SRH - PEDIATRICS')
                                     ,('220','SRH - OTHER')
                                     ,('4','NON-CLINICAL - ADMINISTRATION')
                                     ,('31','NON-SRH - MEDICAL');

#EndIf