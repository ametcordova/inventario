-- phpMyAdmin SQL Dump
-- version 5.0.3
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 24-09-2022 a las 20:49:31
-- Versión del servidor: 10.4.14-MariaDB
-- Versión de PHP: 7.4.11

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";



--
-- Base de datos: `inventario`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `c_tiposrelaciones`
--

DROP TABLE IF EXISTS `c_tiposrelaciones`;
CREATE TABLE IF NOT EXISTS `c_tiposrelaciones` (
  `id_tiporelacion` int(2) UNSIGNED ZEROFILL NOT NULL AUTO_INCREMENT,
  `descripcion` varchar(100) COLLATE utf8_spanish_ci NOT NULL,
  `vigencia_desde` date DEFAULT NULL,
  `vigencia_hasta` date DEFAULT NULL,
  `version` decimal(4,2) DEFAULT NULL,
  `ultusuario` int(5) UNSIGNED DEFAULT NULL,
  `create_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id_tiporelacion`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci COMMENT='catálogo de tipos de relaciónes SAT Mexico';


INSERT INTO c_tiposrelaciones VALUES('01','Nota de crédito de los documentos relacionados','2022-01-01','','4.00',5,now());
INSERT INTO c_tiposrelaciones VALUES('02','Nota de débito de los documentos relacionados','2022-01-01','','4.00',5,now());
INSERT INTO c_tiposrelaciones VALUES('03','Devolución de mercancía sobre facturas o traslados previos','2022-01-01','','4.00',5,now());
INSERT INTO c_tiposrelaciones VALUES('04','Sustitución de los CFDI previos','2022-01-01','','4.00',5,now());
INSERT INTO c_tiposrelaciones VALUES('05','Traslados de mercancias facturados previamente','2022-01-01','','4.00',5,now());
INSERT INTO c_tiposrelaciones VALUES('06','Factura generada por los traslados previos','2022-01-01','','4.00',5,now());
INSERT INTO c_tiposrelaciones VALUES('07','CFDI por aplicación de anticipo','2022-01-01','','4.00',5,now());
INSERT INTO c_tiposrelaciones VALUES('08','Factura generada por pagos en parcialidades','2022-01-01','2022-06-30','4.00',5,now());
INSERT INTO c_tiposrelaciones VALUES('09','Factura generada por pagos diferidos','2022-01-01','2022-06-30','4.00',5,now());