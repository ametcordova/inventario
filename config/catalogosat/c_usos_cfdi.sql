DROP TABLE IF EXISTS `c_usocfdi`;
CREATE TABLE IF NOT EXISTS `c_usocfdi` (
  `id` int(2) UNSIGNED NOT NULL AUTO_INCREMENT,
  `id_cfdi` varchar(3) COLLATE utf8_spanish_ci NOT NULL,
  `descripcion` varchar(100) COLLATE utf8_spanish_ci NOT NULL,
  `aplica_fisica` tinyint(1) UNSIGNED not null,
  `aplica_moral` tinyint(1) UNSIGNED not null,
  `vigencia_desde` date DEFAULT NULL,
  `vigencia_hasta` date DEFAULT NULL,
  `reg_fiscales_recep` text not null,
  `version` decimal(4,2) DEFAULT NULL,
  `ultusuario` int(5) UNSIGNED DEFAULT NULL,
  `create_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci COMMENT='catálogo de usos de CFDI SAT Mexico';


INSERT INTO c_usocfdi VALUES(null,'G01','Adquisición de mercancías.',1,1,'2022-01-01','','601, 603, 606, 612, 620, 621, 622, 623, 624, 625,626','4.00',5,now());
INSERT INTO c_usocfdi VALUES(null,'G02','Devoluciones, descuentos o bonificaciones.',1,1,'2022-01-01','','601, 603, 606, 612, 620, 621, 622, 623, 624, 625,626','4.00',5,now());
INSERT INTO c_usocfdi VALUES(null,'G03','Gastos en general.',1,1,'2022-01-01','','601, 603, 606, 612, 620, 621, 622, 623, 624, 625, 626','4.00',5,now());
INSERT INTO c_usocfdi VALUES(null,'I01','Construcciones.',1,1,'2022-01-01','','601, 603, 606, 612, 620, 621, 622, 623, 624, 625, 626','4.00',5,now());
INSERT INTO c_usocfdi VALUES(null,'I02','Mobiliario y equipo de oficina por inversiones.',1,1,'2022-01-01','','601, 603, 606, 612, 620, 621, 622, 623, 624, 625, 626','4.00',5,now());
INSERT INTO c_usocfdi VALUES(null,'I03','Equipo de transporte.',1,1,'2022-01-01','','601, 603, 606, 612, 620, 621, 622, 623, 624, 625, 626','4.00',5,now());
INSERT INTO c_usocfdi VALUES(null,'I04','Equipo de computo y accesorios.',1,1,'2022-01-01','','601, 603, 606, 612, 620, 621, 622, 623, 624, 625, 626','4.00',5,now());
INSERT INTO c_usocfdi VALUES(null,'I05','Dados, troqueles, moldes, matrices y herramental.',1,1,'2022-01-01','','601, 603, 606, 612, 620, 621, 622, 623, 624, 625, 626','4.00',5,now());
INSERT INTO c_usocfdi VALUES(null,'I06','Comunicaciones telefónicas.',1,1,'2022-01-01','','601, 603, 606, 612, 620, 621, 622, 623, 624, 625, 626','4.00',5,now());
INSERT INTO c_usocfdi VALUES(null,'I07','Comunicaciones satelitales.',1,1,'2022-01-01','','601, 603, 606, 612, 620, 621, 622, 623, 624, 625, 626','4.00',5,now());
INSERT INTO c_usocfdi VALUES(null,'I08','Otra maquinaria y equipo.',1,1,'2022-01-01','','601, 603, 606, 612, 620, 621, 622, 623, 624, 625, 626','4.00',5,now());
INSERT INTO c_usocfdi VALUES(null,'D01','Honorarios médicos, dentales y gastos hospitalarios.',1,'','2022-01-01','','605, 606, 608, 611, 612, 614, 607, 615, 625','4.00',5,now());
INSERT INTO c_usocfdi VALUES(null,'D02','Gastos médicos por incapacidad o discapacidad.',1,'','2022-01-01','','605, 606, 608, 611, 612, 614, 607, 615, 625','4.00',5,now());
INSERT INTO c_usocfdi VALUES(null,'D03','Gastos funerales.',1,'','2022-01-01','','605, 606, 608, 611, 612, 614, 607, 615, 625','4.00',5,now());
INSERT INTO c_usocfdi VALUES(null,'D04','Donativos.',1,'','2022-01-01','','605, 606, 608, 611, 612, 614, 607, 615, 625','4.00',5,now());
INSERT INTO c_usocfdi VALUES(null,'D05','Intereses reales efectivamente pagados por créditos hipotecarios (casa habitación).',1,'','2022-01-01','','605, 606, 608, 611, 612, 614, 607, 615, 625','4.00',5,now());
INSERT INTO c_usocfdi VALUES(null,'D06','Aportaciones voluntarias al SAR.',1,'','2022-01-01','','605, 606, 608, 611, 612, 614, 607, 615, 625','4.00',5,now());
INSERT INTO c_usocfdi VALUES(null,'D07','Primas por seguros de gastos médicos.',1,'','2022-01-01','','605, 606, 608, 611, 612, 614, 607, 615, 625','4.00',5,now());
INSERT INTO c_usocfdi VALUES(null,'D08','Gastos de transportación escolar obligatoria.',1,'','2022-01-01','','605, 606, 608, 611, 612, 614, 607, 615, 625','4.00',5,now());
INSERT INTO c_usocfdi VALUES(null,'D09','Depósitos en cuentas para el ahorro, primas que tengan como base planes de pensiones.',1,'','2022-01-01','','605, 606, 608, 611, 612, 614, 607, 615, 625','4.00',5,now());
INSERT INTO c_usocfdi VALUES(null,'D10','Pagos por servicios educativos (colegiaturas).',1,'','2022-01-01','','605, 606, 608, 611, 612, 614, 607, 615, 625','4.00',5,now());
INSERT INTO c_usocfdi VALUES(null,'S01','Sin efectos fiscales.',1,1,'2022-01-01','','601, 603, 605, 606, 608, 610, 611, 612, 614, 616, 620, 621, 622, 623, 624, 607, 615, 625, 626','4.00',5,now());
INSERT INTO c_usocfdi VALUES(null,'CP01','Pagos',1,1,'2022-01-01','','601, 603, 605, 606, 608, 610, 611, 612, 614, 616, 620, 621, 622, 623, 624, 607, 615, 625, 626','4.00',5,now());
INSERT INTO c_usocfdi VALUES(null,'CN01','Nómina',1,'','2022-01-01','','605','4.00',5,now());
