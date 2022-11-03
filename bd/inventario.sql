-- phpMyAdmin SQL Dump
-- version 5.0.2
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 03-11-2022 a las 07:34:25
-- Versión del servidor: 10.4.11-MariaDB
-- Versión de PHP: 7.4.5

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `inventario`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `clientes`
--

CREATE TABLE `clientes` (
  `id` int(11) NOT NULL,
  `id_empresa` tinyint(2) UNSIGNED DEFAULT NULL,
  `nombre` text COLLATE utf8_spanish_ci NOT NULL,
  `rfc` varchar(13) COLLATE utf8_spanish_ci NOT NULL,
  `curp` varchar(18) COLLATE utf8_spanish_ci DEFAULT NULL,
  `email` text COLLATE utf8_spanish_ci NOT NULL,
  `telefono` text COLLATE utf8_spanish_ci NOT NULL,
  `direccion` text COLLATE utf8_spanish_ci NOT NULL,
  `num_int_ext` varchar(20) COLLATE utf8_spanish_ci DEFAULT NULL,
  `colonia` varchar(250) COLLATE utf8_spanish_ci DEFAULT NULL,
  `codpostal` int(5) DEFAULT NULL,
  `ciudad` varchar(200) COLLATE utf8_spanish_ci DEFAULT NULL,
  `estado` tinyint(3) DEFAULT NULL,
  `regimenfiscal` smallint(3) UNSIGNED DEFAULT NULL,
  `act_economica` varchar(200) COLLATE utf8_spanish_ci DEFAULT NULL,
  `formadepago` tinyint(3) UNSIGNED DEFAULT NULL,
  `fecha_creacion` date DEFAULT NULL,
  `saldodisponible` decimal(12,2) DEFAULT NULL,
  `ultima_compra` datetime DEFAULT NULL,
  `ultusuario` int(3) UNSIGNED DEFAULT NULL,
  `fecha_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

--
-- Volcado de datos para la tabla `clientes`
--

INSERT INTO `clientes` (`id`, `id_empresa`, `nombre`, `rfc`, `curp`, `email`, `telefono`, `direccion`, `num_int_ext`, `colonia`, `codpostal`, `ciudad`, `estado`, `regimenfiscal`, `act_economica`, `formadepago`, `fecha_creacion`, `saldodisponible`, `ultima_compra`, `ultusuario`, `fecha_at`) VALUES
(1, 1, 'IRP INGENIERIA EN REDES DE PLANTA, S.A. DE C.V.', 'IIE100519CC2', NULL, 'veronicairp@outlook.com', '(662) 218-8215', 'MANZANILLO NO 457 COLONIA LAS TORRES C.P 83139', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2010-05-19', NULL, NULL, 5, '2019-10-18 00:52:59'),
(3, 1, 'OPERADORA CICSA, S.A. DE C.V.', 'OCI810921EI3', NULL, 'mchan@condumex.com.mx', '(999) 945-2513', 'CALLE LAGO ZURICH NO 245 EDIFICIO FRISCO PISO 2 AMPLIACION GRANADA C.P 11529 CIUDAD DE MEXICO, CIUDAD DE MEXICO', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '1981-09-21', NULL, NULL, 5, '2019-10-18 00:56:36'),
(4, 1, 'TELMEX, SAB DE C.V.', 'TLM840101WAT', NULL, 'sin@correo.com', '(961) 248-0768', 'DESCONOCIDO', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '1969-08-10', NULL, NULL, 5, '2019-03-12 01:05:26'),
(5, 1, 'CARSO INFRAESTRUCTURA Y CONSTRUCCION', 'CIC991214L94', NULL, 'fiscal@condumex.com.mx', '(555) 249-8900', 'CALLE LAGO ZURICH 245 EDIFICIO FRISCO', 'PISO 2', 'AMPLIACION GRANADA ENTRE CALLE MIGUEL DE CERVANTES SAAVEDRA Y AVENIDA RIO SAN JOAQUIN', 11529, 'DELEG. MIGUEL HIDALGO', 9, 623, 'OTROS TRABAJOS ESPECIALIZADOS PARA LA CONSTRUCCIóN', 99, '1999-12-14', '242153.60', NULL, 5, '2022-10-24 07:54:44'),
(6, 2, 'UNIVERSIDAD ROBOTICA ESPAÑOLA', 'URE180429TM6', NULL, 'sin@correo.com.mx', '9999999999', 'DOMICILIO CONOCIDO SUR', '200', 'CONOCIDO', 65000, 'Anáhuac', 19, 603, '221 Preparación y envasado de té 40%\r\n2304 Actividades para la reproducción de especies en protección y peligro de extinción 60%', 99, '2022-10-29', '100000.00', '2022-10-29 00:06:30', 5, '2022-10-29 10:11:13');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `datosfacturatimbre`
--

CREATE TABLE `datosfacturatimbre` (
  `id` int(5) UNSIGNED NOT NULL,
  `id_empresa` tinyint(1) DEFAULT NULL,
  `serie` varchar(1) COLLATE utf8_spanish_ci DEFAULT NULL,
  `folio` int(5) UNSIGNED DEFAULT NULL,
  `fechahoratimbre` datetime DEFAULT NULL,
  `numcertificado` varchar(25) COLLATE utf8_spanish_ci DEFAULT NULL,
  `numcertificadosat` varchar(25) COLLATE utf8_spanish_ci DEFAULT NULL,
  `sellodigitalcfdi` text COLLATE utf8_spanish_ci DEFAULT NULL,
  `sellodigitalsat` text COLLATE utf8_spanish_ci DEFAULT NULL,
  `cadenaoriginal` text COLLATE utf8_spanish_ci DEFAULT NULL,
  `codigoqr` text COLLATE utf8_spanish_ci DEFAULT NULL,
  `ultusuario` int(3) DEFAULT NULL,
  `update_at` datetime NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci COMMENT='Datos del timbrado de factura';

--
-- Volcado de datos para la tabla `datosfacturatimbre`
--

INSERT INTO `datosfacturatimbre` (`id`, `id_empresa`, `serie`, `folio`, `fechahoratimbre`, `numcertificado`, `numcertificadosat`, `sellodigitalcfdi`, `sellodigitalsat`, `cadenaoriginal`, `codigoqr`, `ultusuario`, `update_at`) VALUES
(1, 1, 'A', 378, '2022-11-03 00:01:23', '00001000000515088380', '00001000000504204971', 'dhe0vjI+3K73VplLj229S7ONxDuFRJuCF6xZbPj1UVfw9IJml/AjEEvNUyyY99BlGnBwzll/3jAJ5SLBM8CLFPvEbNlFCQMKehReF/VXt7S0rv0ejuq0EIiJCv3d3FbGsJ7CbWxrXNQWU+eNChgKLgtrDNzeaycd//L1LKuXWZEfcAndr2Af/gj45kKlA0AekRPUkBIFgvXPsju1fZ7G2B27HNOl7+IRRcgIfJgVD9vlfzPGc82NvHDm0e2dxqpxIMg/+4h+xGQVf1835Rc7qbT/cjpFvb0XPTwPDAyTxnzCXtjkgmZl46JWvjcbNe/SMJvfsy/ekwD7lWYt2XYFGA==', 'UnkoiCIUf59Nay20NDj2URM/t12Sv2v1fidOTSZkY0ivt+1QDPNxIXV1X43cYCXB5DsNbMwRq3FiuXdrATnAFuULFIsIDlsxIEmo+66yxz26dewswI72ehRHH4ppL03rlxCUl49IscNXkENJ/IKEAo8qGDZW+30BjRah5ZM1ybe9jAT1defEJewz+tdSSMKILFTnY2HEdnYhY0Ly2hxyd8GuWdrWmyRAeKzXIi4HVb/Av9WKVQeoK0reWi3a/eowdFgCwho/KoHh6ZNOt/8eoZusJ+s/hlmjngevRmuyidPdfBlDpQabP7dTPQ1Wec8o2m4K3x/8pe+J3aLTP0zR2g==', '||4.0|A|378|2022-11-02T23:59:21|99|00001000000515088380|NA|28963.00|MXN|1|33597.08|I|01|PPD|29027|DIGB980626MX3|BRUNO DIAZ GORDILLO|621|CIC991214L94|CARSO INFRAESTRUCTURA Y CONSTRUCCION|11529|623|I01|72151602|1|2|E48|Unidad de servicio|BAJANTE AEREO DE 25 M. INSTALACION COMPLETA. MIGRACIONES 27102022/43 SUR1. PROY: CAR057063. ODC: 00072995. No. REPSE: 09679. CONTRATO: BAJSUR063-01|1105.000000|2210|02|2210|002|Tasa|0.160000|353.6|72151602|1|6|E48|Unidad de servicio|BAJANTE AEREO DE 50M. MIGRACIONES 27102022/43 SUR1. PROY: CAR057063. ODC: 00072995. No. REPSE: 09679. CONTRATO: BAJSUR063-01|1105.000000|6630|02|6630|002|Tasa|0.160000|1060.8|72151602|1|5|E48|Unidad de servicio|BAJANTE AEREO DE 75M. MIGRACIONES 27102022/43 SUR1. PROY: CAR057063. ODC: 00072995. No. REPSE: 09679. CONTRATO: BAJSUR063-01|1105.000000|5525|02|5525|002|Tasa|0.160000|884|72151602|1|1|E48|Unidad de servicio|BAJANTE AEREO DE 100M. MIGRACIONES 27102022/43 SUR1. PROY: CAR057063. ODC: 00072995. No. REPSE: 09679. CONTRATO: BAJSUR063-01|1105.000000|1105|02|1105|002|Tasa|0.160000|176.8|72151602|1|6|E48|Unidad de servicio|BAJANTE AEREO DE 125M. MIGRACIONES 27102022/43 SUR1. PROY: CAR057063. ODC: 00072995. No. REPSE: 09679. CONTRATO: BAJSUR063-01|1105.000000|6630|02|6630|002|Tasa|0.160000|1060.8|72151602|1|1|E48|Unidad de servicio|BAJANTE AEREO DE 150M. MIGRACIONES 27102022/43 SUR1. PROY: CAR057063. ODC: 00072995. No. REPSE: 09679. CONTRATO: BAJSUR063-01|1105.000000|1105|02|1105|002|Tasa|0.160000|176.8|72151602|1|1|E48|Unidad de servicio|BAJANTE AEREO DE 175M. MIGRACIONES 27102022/43 SUR1. PROY: CAR057063. ODC: 00072995. No. REPSE: 09679. CONTRATO: BAJSUR063-01|1105.000000|1105|02|1105|002|Tasa|0.160000|176.8|72151602|1|3|E48|Unidad de servicio|BAJANTE AEREO DE 200M. MIGRACIONES 27102022/43 SUR1. PROY: CAR057063. ODC: 00072995. No. REPSE: 09679. CONTRATO: BAJSUR063-01|1105.000000|3315|02|3315|002|Tasa|0.160000|530.4|72151602|1|1|E48|Unidad de servicio|BAJANTE SUBTERRANEO DE 50 M. MIGRACIONES 27102022/43 SUR1. PROY: CAR057063. ODC: 00072995. No. REPSE: 09679. CONTRATO: BAJSUR063-01|1338|1338|02|1338|002|Tasa|0.160000|214.08|28963|002|Tasa|0.160000|4634.08|4634.08||', 'iVBORw0KGgoAAAANSUhEUgAAAJcAAACXAQMAAAAiUVs6AAAABlBMVEUAAAD///+l2Z/dAAAACXBIWXMAAA7EAAAOxAGVKw4bAAADHklEQVRIiZWWMW7rOhBFhyBgdlKbgsBs4adjmnArD3gbkPoPSUZ6eQMP+FtRmrBLtkCAhVup4wMIzh86ZVyMXRjmMSQNry7vDABgNZnwoA0LgI/QPj+Zp/8qGMDFhwLzaq60i5nTfyYwSR+7hziM4QrDI0x9uIK751q6yT3IupPTZQCvY7/6R5inP332pI8KZHn5vTcZA7Djh0t2/Gxfq/vW9CcjKuAc0FFdiUNbVzFzLPHJQeKnaTv1FGGSMywjBWCtKNl58U+8cykDWvoM1sJz1vwvXXUVM0KqJ6OpPucS58VgWsUsRFDvROm8QYz9QVc2ppQ5+y+cXEy0ucLmcsS13GMeoHsJoCt7KPaXDZr5hcyU38fmLAyvXP7aAVo529Lb4jOm49MV6ClHWsXMwe+DdW77Rdz7YJqfhQwsninE0n+FEtXunh5gGeZLIB3VV45Ix3a1013GUbArVqh7Dhg7XnPNUpZ1uvwNvN9ji0S7ybGTs5vEzKrhtzXBU6tPyDhB1AdQ7HeTyjRuT6UTMz5g89/NIqcepjN7Qy9iZnRRRDGq6pHWOdMDLHCGOcfafwL7ubor1rsMUuleIfG1bEJ+j+1aKePQ6kOAW+pxfrnGxQyjooBleHWI6+AjLmJGgMfGFe09cf4pwjiJWRNabSkd7xxkqwrAWSJlDpfJbFQ4NnUc+dZlErMMv+YPbzVRaH0B4HZmfjKT3vYX7rWLyhxaF+JNixlQGU4mxZ62QlVlo3cxy7TM7CFdX5uBD2oZK2We6My+x/Mn2DhyrusHWMRzCMj3Mzp2ioUAOdPpnJ3laMkJl8FE1vkec/hGlDnrjlxgXLzBVcxC+TU5Y2/qpiY29zwp4wTpK+hU/zHWzjxH6FXM2D5j9dxmvyjBXA2y76WMXcG/SuvxtnSdA84wMWPbGgKrKh92zr92zqWM50TaPLWZBu3QwS1f7rA2IwVKbAFfiHse6V3MeDbr33ObzZyGmTb4ntekTIVgE1ca6bK4a+uXcjb5gLQ/B57cLnxmqpi1GfglWzuyueLUtUYvZtBeitfIvUynfSZkPwvZ/9mFZub+ttjfAAAAAElFTkSuQmCC', 5, '2022-11-03 00:03:30');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `empresa`
--

CREATE TABLE `empresa` (
  `id` tinyint(1) UNSIGNED NOT NULL,
  `razonsocial` varchar(50) COLLATE utf8_spanish_ci NOT NULL,
  `rfc` varchar(13) COLLATE utf8_spanish_ci NOT NULL,
  `curp` varchar(18) COLLATE utf8_spanish_ci DEFAULT NULL,
  `regimenfiscalemisor` smallint(3) DEFAULT NULL,
  `numerocertificado` varchar(30) COLLATE utf8_spanish_ci DEFAULT NULL,
  `direccion` varchar(50) COLLATE utf8_spanish_ci NOT NULL,
  `colonia` varchar(50) COLLATE utf8_spanish_ci NOT NULL,
  `num_int` varchar(25) COLLATE utf8_spanish_ci DEFAULT NULL,
  `num_ext` varchar(25) COLLATE utf8_spanish_ci DEFAULT NULL,
  `codpostal` int(5) UNSIGNED NOT NULL,
  `ciudad` varchar(30) COLLATE utf8_spanish_ci DEFAULT NULL,
  `estado` varchar(30) COLLATE utf8_spanish_ci DEFAULT NULL,
  `id_exportacion` tinyint(2) UNSIGNED ZEROFILL DEFAULT NULL,
  `telempresa` varchar(30) COLLATE utf8_spanish_ci DEFAULT NULL,
  `mailempresa` varchar(30) COLLATE utf8_spanish_ci DEFAULT NULL,
  `contacto` varchar(50) COLLATE utf8_spanish_ci DEFAULT NULL,
  `telcontacto` varchar(30) COLLATE utf8_spanish_ci DEFAULT NULL,
  `mailcontacto` varchar(30) COLLATE utf8_spanish_ci DEFAULT NULL,
  `slogan` varchar(60) COLLATE utf8_spanish_ci DEFAULT NULL,
  `iva` decimal(4,2) DEFAULT NULL,
  `seriefacturacion` varchar(2) COLLATE utf8_spanish_ci DEFAULT NULL,
  `imagen` varchar(100) COLLATE utf8_spanish_ci DEFAULT NULL,
  `impresora` varchar(30) COLLATE utf8_spanish_ci DEFAULT NULL,
  `mensajeticket` varchar(100) COLLATE utf8_spanish_ci DEFAULT NULL,
  `rutacertificado` varchar(100) COLLATE utf8_spanish_ci DEFAULT NULL,
  `rutallaveprivada` varchar(100) COLLATE utf8_spanish_ci DEFAULT NULL,
  `rutacontrasenacer` varchar(100) COLLATE utf8_spanish_ci DEFAULT NULL,
  `rutarespaldo` varchar(60) COLLATE utf8_spanish_ci DEFAULT NULL,
  `namedatabase` varchar(12) COLLATE utf8_spanish_ci DEFAULT NULL,
  `status` tinyint(1) UNSIGNED DEFAULT 1,
  `ultusuario` int(3) DEFAULT NULL,
  `ultmodificacion` datetime NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

--
-- Volcado de datos para la tabla `empresa`
--

INSERT INTO `empresa` (`id`, `razonsocial`, `rfc`, `curp`, `regimenfiscalemisor`, `numerocertificado`, `direccion`, `colonia`, `num_int`, `num_ext`, `codpostal`, `ciudad`, `estado`, `id_exportacion`, `telempresa`, `mailempresa`, `contacto`, `telcontacto`, `mailcontacto`, `slogan`, `iva`, `seriefacturacion`, `imagen`, `impresora`, `mensajeticket`, `rutacertificado`, `rutallaveprivada`, `rutacontrasenacer`, `rutarespaldo`, `namedatabase`, `status`, `ultusuario`, `ultmodificacion`) VALUES
(1, 'BRUNO DIAZ GORDILLO', 'DIGB980626MX3', 'DIGB980626HCSZRR09', 621, '00001000000515088380', 'AVENIDA RIO COATAN', 'COLONIA 24 DE JUNIO', '', 'No. 504', 29027, 'TUXTLA GUTIÉRREZ', 'CHIAPAS', 01, '9615805025', 'contacto@fipabide.com.mx', 'LUZ ELIZABETH GORDILLO RAMIREZ', '9615805025', 'leram69@hotmail.com', 'Donde es un gusto atenderle.', '16.00', 'A', 'config/imagenes/logotipo.png', 'EC-PM-5890X', 'Pague aquí CFE, AVON, MEGACABLE, TELMEX, VETV, INFONAVIT, NETFLIX, AMAZON, RECARGAS y muchos más', 'config/Certificado/00001000000515088380.cer.pem', 'config/Certificado/CSD_MATRIZ_DIGB980626MX3_20220913_165347.key.pem', 'config/Certificados/Contrasena.txt', 'D:/RESPALDO', 'inventario', 1, 5, '2022-11-02 20:13:41'),
(2, 'ESCUELA KEMPER URGATE', 'EKU9003173C9', NULL, 601, '30001000000400002434', 'DOMICILIO CONOCIDO', 'DELEGACION', 'SN', '100', 26015, 'CIUDAD NORTE', 'BAJA CALIFORNIA SUR', 01, '9999999999', 'sin@correo.com.mx', NULL, '9999999999', 'sin@correo.com.mx', '.', '16.00', 'A', NULL, NULL, NULL, 'config/Certificado/Pruebas/CSD_EKU9003173C9.cer.pem', 'config/Certificado/Pruebas/CSD_EKU9003173C9.key.pem', 'config/Certificado/Pruebas/Contrasena.txt', 'D:/RESPALDO', 'inventario', 1, 5, '2022-11-02 20:13:44');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `facturaingreso`
--

CREATE TABLE `facturaingreso` (
  `id` int(10) UNSIGNED NOT NULL,
  `id_empresa` tinyint(1) UNSIGNED NOT NULL DEFAULT 1,
  `serie` varchar(2) COLLATE utf8_spanish_ci DEFAULT NULL,
  `folio` int(5) UNSIGNED NOT NULL,
  `uuid` varchar(36) COLLATE utf8_spanish_ci DEFAULT NULL,
  `fechaelaboracion` datetime NOT NULL,
  `fechatimbrado` datetime DEFAULT NULL,
  `fechacancelado` datetime DEFAULT NULL,
  `rfcemisor` varchar(13) COLLATE utf8_spanish_ci NOT NULL,
  `idregimenfiscalemisor` smallint(3) DEFAULT NULL,
  `idtipocomprobante` varchar(1) COLLATE utf8_spanish_ci NOT NULL,
  `idmoneda` tinyint(3) DEFAULT NULL,
  `idlugarexpedicion` int(5) UNSIGNED DEFAULT NULL,
  `idexportacion` tinyint(2) UNSIGNED ZEROFILL DEFAULT NULL,
  `idreceptor` int(11) NOT NULL,
  `idusocfdi` smallint(3) DEFAULT NULL,
  `idformapago` int(2) UNSIGNED ZEROFILL DEFAULT NULL,
  `idmetodopago` varchar(3) COLLATE utf8_spanish_ci DEFAULT NULL,
  `numctapago` varchar(50) COLLATE utf8_spanish_ci DEFAULT NULL,
  `cfdirelacionados` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`cfdirelacionados`)),
  `conceptos` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`conceptos`)),
  `observaciones` varchar(250) COLLATE utf8_spanish_ci DEFAULT NULL,
  `subtotal` decimal(12,2) DEFAULT NULL,
  `tasaimpuesto` decimal(4,2) UNSIGNED ZEROFILL NOT NULL,
  `impuestos` decimal(12,2) DEFAULT NULL,
  `retenciones` decimal(12,2) DEFAULT NULL,
  `totalfactura` decimal(12,2) NOT NULL,
  `rutaguardararchivos` varchar(250) COLLATE utf8_spanish_ci DEFAULT NULL,
  `ultusuario` int(3) NOT NULL,
  `create_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci COMMENT='tbl para facturación electronica sat mexico';

--
-- Volcado de datos para la tabla `facturaingreso`
--

INSERT INTO `facturaingreso` (`id`, `id_empresa`, `serie`, `folio`, `uuid`, `fechaelaboracion`, `fechatimbrado`, `fechacancelado`, `rfcemisor`, `idregimenfiscalemisor`, `idtipocomprobante`, `idmoneda`, `idlugarexpedicion`, `idexportacion`, `idreceptor`, `idusocfdi`, `idformapago`, `idmetodopago`, `numctapago`, `cfdirelacionados`, `conceptos`, `observaciones`, `subtotal`, `tasaimpuesto`, `impuestos`, `retenciones`, `totalfactura`, `rutaguardararchivos`, `ultusuario`, `create_at`) VALUES
(1, 1, 'A', 378, '4E0A09E3-E2A2-4B1E-BCC5-E838698E5A1B', '2022-11-02 23:50:23', '2022-11-03 00:01:23', NULL, 'DIGB980626MX3', 621, 'I', 100, 29027, 01, 5, 4, 99, 'PPD', NULL, NULL, '[{\"ClaveProdServ\":\"72151602\",\"Cantidad\":\"2\",\"ClaveUnidad\":\"E48\",\"Unidad\":\"Unidad de servicio\",\"Descripcion\":\"BAJANTE AEREO DE 25 M. INSTALACION COMPLETA. MIGRACIONES 27102022\\/43 SUR1. PROY:  CAR057063. ODC:  00072995. No. REPSE: 09679. CONTRATO: BAJSUR063-01\",\"ValorUnitario\":\"1105.000000\",\"Importe\":\"2210\",\"ObjetoImp\":\"02\"},{\"ClaveProdServ\":\"72151602\",\"Cantidad\":\"6\",\"ClaveUnidad\":\"E48\",\"Unidad\":\"Unidad de servicio\",\"Descripcion\":\"BAJANTE AEREO DE 50M. MIGRACIONES 27102022\\/43 SUR1. PROY:  CAR057063. ODC:  00072995. No. REPSE: 09679. CONTRATO: BAJSUR063-01\",\"ValorUnitario\":\"1105.000000\",\"Importe\":\"6630\",\"ObjetoImp\":\"02\"},{\"ClaveProdServ\":\"72151602\",\"Cantidad\":\"5\",\"ClaveUnidad\":\"E48\",\"Unidad\":\"Unidad de servicio\",\"Descripcion\":\"BAJANTE AEREO DE 75M. MIGRACIONES 27102022\\/43 SUR1. PROY:  CAR057063. ODC:  00072995. No. REPSE: 09679. CONTRATO: BAJSUR063-01\",\"ValorUnitario\":\"1105.000000\",\"Importe\":\"5525\",\"ObjetoImp\":\"02\"},{\"ClaveProdServ\":\"72151602\",\"Cantidad\":\"1\",\"ClaveUnidad\":\"E48\",\"Unidad\":\"Unidad de servicio\",\"Descripcion\":\"BAJANTE AEREO DE 100M. MIGRACIONES 27102022\\/43 SUR1. PROY:  CAR057063. ODC:  00072995. No. REPSE: 09679. CONTRATO: BAJSUR063-01\",\"ValorUnitario\":\"1105.000000\",\"Importe\":\"1105\",\"ObjetoImp\":\"02\"},{\"ClaveProdServ\":\"72151602\",\"Cantidad\":\"6\",\"ClaveUnidad\":\"E48\",\"Unidad\":\"Unidad de servicio\",\"Descripcion\":\"BAJANTE AEREO DE 125M. MIGRACIONES 27102022\\/43 SUR1. PROY:  CAR057063. ODC:  00072995. No. REPSE: 09679. CONTRATO: BAJSUR063-01\",\"ValorUnitario\":\"1105.000000\",\"Importe\":\"6630\",\"ObjetoImp\":\"02\"},{\"ClaveProdServ\":\"72151602\",\"Cantidad\":\"1\",\"ClaveUnidad\":\"E48\",\"Unidad\":\"Unidad de servicio\",\"Descripcion\":\"BAJANTE AEREO DE 150M. MIGRACIONES 27102022\\/43 SUR1. PROY:  CAR057063. ODC:  00072995. No. REPSE: 09679. CONTRATO: BAJSUR063-01\",\"ValorUnitario\":\"1105.000000\",\"Importe\":\"1105\",\"ObjetoImp\":\"02\"},{\"ClaveProdServ\":\"72151602\",\"Cantidad\":\"1\",\"ClaveUnidad\":\"E48\",\"Unidad\":\"Unidad de servicio\",\"Descripcion\":\"BAJANTE AEREO DE 175M. MIGRACIONES 27102022\\/43 SUR1. PROY:  CAR057063. ODC:  00072995. No. REPSE: 09679. CONTRATO: BAJSUR063-01\",\"ValorUnitario\":\"1105.000000\",\"Importe\":\"1105\",\"ObjetoImp\":\"02\"},{\"ClaveProdServ\":\"72151602\",\"Cantidad\":\"3\",\"ClaveUnidad\":\"E48\",\"Unidad\":\"Unidad de servicio\",\"Descripcion\":\"BAJANTE AEREO DE 200M. MIGRACIONES 27102022\\/43 SUR1. PROY:  CAR057063. ODC:  00072995. No. REPSE: 09679. CONTRATO: BAJSUR063-01\",\"ValorUnitario\":\"1105.000000\",\"Importe\":\"3315\",\"ObjetoImp\":\"02\"},{\"ClaveProdServ\":\"72151602\",\"Cantidad\":\"1\",\"ClaveUnidad\":\"E48\",\"Unidad\":\"Unidad de servicio\",\"Descripcion\":\"BAJANTE SUBTERRANEO DE 50 M. MIGRACIONES 27102022\\/43 SUR1. PROY:  CAR057063. ODC:  00072995. No. REPSE: 09679. CONTRATO: BAJSUR063-01\",\"ValorUnitario\":\"1338\",\"Importe\":\"1338\",\"ObjetoImp\":\"02\"}]', '', '28963.00', '16.00', '4634.08', NULL, '33597.08', NULL, 5, '2022-11-03 06:01:23');

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `clientes`
--
ALTER TABLE `clientes`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `datosfacturatimbre`
--
ALTER TABLE `datosfacturatimbre`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `empresa`
--
ALTER TABLE `empresa`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `facturaingreso`
--
ALTER TABLE `facturaingreso`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `clientes`
--
ALTER TABLE `clientes`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT de la tabla `datosfacturatimbre`
--
ALTER TABLE `datosfacturatimbre`
  MODIFY `id` int(5) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT de la tabla `empresa`
--
ALTER TABLE `empresa`
  MODIFY `id` tinyint(1) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT de la tabla `facturaingreso`
--
ALTER TABLE `facturaingreso`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=379;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
