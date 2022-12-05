-- phpMyAdmin SQL Dump
-- version 5.0.2
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 05-12-2022 a las 06:32:41
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
(5, 1, 'CARSO INFRAESTRUCTURA Y CONSTRUCCION', 'CIC991214L94', NULL, 'fiscal@condumex.com.mx', '(555) 249-8900', 'CALLE LAGO ZURICH 245 EDIFICIO FRISCO', 'PISO 2', 'AMPLIACION GRANADA ENTRE CALLE MIGUEL DE CERVANTES SAAVEDRA Y AVENIDA RIO SAN JOAQUIN', 11529, 'DELEG. MIGUEL HIDALGO', 9, 623, 'OTROS TRABAJOS ESPECIALIZADOS PARA LA CONSTRUCCIóN', 99, '1999-12-14', '526027.46', NULL, 5, '2022-10-24 07:54:44'),
(6, 2, 'UNIVERSIDAD ROBOTICA ESPAÑOLA', 'URE180429TM6', NULL, 'sin@correo.com.mx', '9999999999', 'DOMICILIO CONOCIDO SUR', '200', 'CONOCIDO', 65000, 'Anáhuac', 19, 603, '221 Preparación y envasado de té 40%\r\n2304 Actividades para la reproducción de especies en protección y peligro de extinción 60%', 99, '2022-10-29', '100000.00', '2022-10-29 00:06:30', 5, '2022-10-29 10:11:13');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `complementodepago`
--

CREATE TABLE `complementodepago` (
  `id` int(5) UNSIGNED ZEROFILL NOT NULL,
  `seriecp` char(1) COLLATE utf8_spanish_ci DEFAULT NULL,
  `fechaelaboracion` date NOT NULL,
  `rfcemisor` varchar(13) COLLATE utf8_spanish_ci NOT NULL,
  `cpemisor` int(5) UNSIGNED NOT NULL,
  `rfcreceptor` varchar(13) COLLATE utf8_spanish_ci NOT NULL,
  `fechapago` datetime DEFAULT NULL,
  `formadepago` int(2) NOT NULL,
  `totalpagado` int(11) NOT NULL,
  `idmoneda` tinyint(3) DEFAULT NULL,
  `cuentaordenante` varchar(20) COLLATE utf8_spanish_ci NOT NULL,
  `cuentabeneficiario` varchar(20) COLLATE utf8_spanish_ci NOT NULL,
  `doctosrelacionados` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`doctosrelacionados`)),
  `idimpuesto` int(3) UNSIGNED ZEROFILL NOT NULL,
  `subtotal` decimal(10,2) NOT NULL,
  `tasa` decimal(8,2) NOT NULL,
  `totalimpuesto` decimal(10,2) NOT NULL,
  `totalrecibo` decimal(10,2) NOT NULL,
  `ultusuario` int(3) DEFAULT NULL,
  `at_update` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci COMMENT='Tabla para complemto de pago Ver. 2.0 CFDI 4.0';

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `c_exportacion`
--

CREATE TABLE `c_exportacion` (
  `id` int(2) UNSIGNED ZEROFILL NOT NULL,
  `descripcion` varchar(100) COLLATE utf8_spanish_ci DEFAULT NULL,
  `fechainiciovigencia` date DEFAULT NULL,
  `fechafinvigencia` date DEFAULT NULL,
  `version` decimal(4,2) NOT NULL,
  `ultusuario` int(3) UNSIGNED NOT NULL,
  `update_at` datetime NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci COMMENT='Catálogo exportación. SAT V.4.0';

--
-- Volcado de datos para la tabla `c_exportacion`
--

INSERT INTO `c_exportacion` (`id`, `descripcion`, `fechainiciovigencia`, `fechafinvigencia`, `version`, `ultusuario`, `update_at`) VALUES
(01, 'No aplica', '2022-01-01', '0000-00-00', '4.00', 5, '2022-10-27 17:30:56'),
(02, 'Definitiva con clave A1', '2022-01-01', '0000-00-00', '4.00', 5, '2022-10-27 17:30:56'),
(03, 'Temporal', '2022-01-01', '0000-00-00', '4.00', 5, '2022-10-27 17:30:56'),
(04, 'Definitiva con clave distinta a A1 o cuando no existe enajenación en términos del CFF', '2022-02-25', '0000-00-00', '4.00', 5, '2022-10-27 17:30:56');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `c_formapago`
--

CREATE TABLE `c_formapago` (
  `id` int(11) NOT NULL,
  `descripcionformapago` varchar(50) COLLATE utf8_spanish_ci DEFAULT NULL,
  `bancarizado` varchar(2) COLLATE utf8_spanish_ci DEFAULT NULL,
  `numerodeoperacion` varchar(15) COLLATE utf8_spanish_ci DEFAULT NULL,
  `rfcdelemisordelactaord` varchar(15) COLLATE utf8_spanish_ci DEFAULT NULL,
  `cuentaordenante` varchar(15) COLLATE utf8_spanish_ci DEFAULT NULL,
  `patroncuentaordenante` varchar(60) COLLATE utf8_spanish_ci DEFAULT NULL,
  `rfcdelemisorctabenef` varchar(15) COLLATE utf8_spanish_ci DEFAULT NULL,
  `cuentabeneficiario` varchar(15) COLLATE utf8_spanish_ci DEFAULT NULL,
  `patronctabeneficiaria` varchar(60) COLLATE utf8_spanish_ci DEFAULT NULL,
  `tipocadenapago` varchar(15) COLLATE utf8_spanish_ci DEFAULT NULL,
  `nombancoemisorctaordext` varchar(100) COLLATE utf8_spanish_ci DEFAULT NULL,
  `fechainiciovigencia` date NOT NULL,
  `fechafinvigencia` date DEFAULT NULL,
  `version` decimal(4,2) NOT NULL,
  `ultusuario` int(3) UNSIGNED NOT NULL,
  `create_at` datetime NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci COMMENT='Catálogo de formas de pago. SAT Version 4.0';

--
-- Volcado de datos para la tabla `c_formapago`
--

INSERT INTO `c_formapago` (`id`, `descripcionformapago`, `bancarizado`, `numerodeoperacion`, `rfcdelemisordelactaord`, `cuentaordenante`, `patroncuentaordenante`, `rfcdelemisorctabenef`, `cuentabeneficiario`, `patronctabeneficiaria`, `tipocadenapago`, `nombancoemisorctaordext`, `fechainiciovigencia`, `fechafinvigencia`, `version`, `ultusuario`, `create_at`) VALUES
(1, 'Efectivo', 'No', 'Opcional', 'No', 'No', 'No', 'No', 'No', 'No', 'No', 'No', '2017-01-01', '0000-00-00', '4.00', 5, '2022-10-24 23:24:59'),
(2, 'Cheque nominativo', 'Sí', 'Opcional', 'Opcional', 'Opcional', '[0-9]{11}|[0-9]{18}', 'Opcional', 'Opcional', '[0-9]{10,11}|[0-9]{15,16}|[0-9]{18}|[A-Z0-9_]{10,50}', 'No', 'Si el RFC del emisor de la cuenta ordenante es XEXX010101000, este campo es obligatorio.', '2017-01-01', '0000-00-00', '4.00', 5, '2022-10-24 23:24:59'),
(3, 'Transferencia electrónica de fondos', 'Sí', 'Opcional', 'Opcional', 'Opcional', '[0-9]{10}|[0-9]{16}|[0-9]{18}', 'Opcional', 'Opcional', '[0-9]{10}|[0-9]{18}', 'Opcional', 'Si el RFC del emisor de la cuenta ordenante es XEXX010101000, este campo es obligatorio.', '2017-01-01', '0000-00-00', '4.00', 5, '2022-10-24 23:24:59'),
(4, 'Tarjeta de crédito', 'Sí', 'Opcional', 'Opcional', 'Opcional', '[0-9]{16}', 'Opcional', 'Opcional', '[0-9]{10,11}|[0-9]{15,16}|[0-9]{18}|[A-Z0-9_]{10,50}', 'No', 'Si el RFC del emisor de la cuenta ordenante es XEXX010101000, este campo es obligatorio.', '2017-01-01', '0000-00-00', '4.00', 5, '2022-10-24 23:24:59'),
(5, 'Monedero electrónico', 'Sí', 'Opcional', 'Opcional', 'Opcional', '[0-9]{10,11}|[0-9]{15,16}|[0-9]{18}|[A-Z0-9_]{10,50}', 'Opcional', 'Opcional', '[0-9]{10,11}|[0-9]{15,16}|[0-9]{18}|[A-Z0-9_]{10,50}', 'No', 'No', '2017-01-01', '0000-00-00', '4.00', 5, '2022-10-24 23:24:59'),
(6, 'Dinero electrónico', 'Sí', 'Opcional', 'Opcional', 'Opcional', '[0-9]{10}', 'No', 'No', 'No', 'No', 'No', '2017-01-01', '0000-00-00', '4.00', 5, '2022-10-24 23:24:59'),
(8, 'Vales de despensa', 'No', 'Opcional', 'No', 'No', 'No', 'No', 'No', 'No', 'No', 'No', '2017-01-01', '0000-00-00', '4.00', 5, '2022-10-24 23:24:59'),
(12, 'Dación en pago', 'No', 'Opcional', 'No', 'No', 'No', 'No', 'No', 'No', 'No', 'No', '2017-01-01', '0000-00-00', '4.00', 5, '2022-10-24 23:24:59'),
(13, 'Pago por subrogación', 'No', 'Opcional', 'No', 'No', 'No', 'No', 'No', 'No', 'No', 'No', '2017-01-01', '0000-00-00', '4.00', 5, '2022-10-24 23:25:00'),
(14, 'Pago por consignación', 'No', 'Opcional', 'No', 'No', 'No', 'No', 'No', 'No', 'No', 'No', '2017-01-01', '0000-00-00', '4.00', 5, '2022-10-24 23:25:00'),
(15, 'Condonación', 'No', 'Opcional', 'No', 'No', 'No', 'No', 'No', 'No', 'No', 'No', '2017-01-01', '0000-00-00', '4.00', 5, '2022-10-24 23:25:00'),
(17, 'Compensación', 'No', 'Opcional', 'No', 'No', 'No', 'No', 'No', 'No', 'No', 'No', '2017-01-01', '0000-00-00', '4.00', 5, '2022-10-24 23:25:00'),
(23, 'Novación', 'No', 'Opcional', 'No', 'No', 'No', 'No', 'No', 'No', 'No', 'No', '2017-01-01', '0000-00-00', '4.00', 5, '2022-10-24 23:25:00'),
(24, 'Confusión', 'No', 'Opcional', 'No', 'No', 'No', 'No', 'No', 'No', 'No', 'No', '2017-01-01', '0000-00-00', '4.00', 5, '2022-10-24 23:25:00'),
(25, 'Remisión de deuda', 'No', 'Opcional', 'No', 'No', 'No', 'No', 'No', 'No', 'No', 'No', '2017-01-01', '0000-00-00', '4.00', 5, '2022-10-24 23:25:00'),
(26, 'Prescripción o caducidad', 'No', 'Opcional', 'No', 'No', 'No', 'No', 'No', 'No', 'No', 'No', '2017-01-01', '0000-00-00', '4.00', 5, '2022-10-24 23:25:00'),
(27, 'A satisfacción del acreedor', 'No', 'Opcional', 'No', 'No', 'No', 'No', 'No', 'No', 'No', 'No', '2017-01-01', '0000-00-00', '4.00', 5, '2022-10-24 23:25:00'),
(28, 'Tarjeta de débito', 'Sí', 'Opcional', 'Opcional', 'Opcional', '[0-9]{16}', 'Opcional', 'Opcional', '[0-9]{10,11}|[0-9]{15,16}|[0-9]{18}|[A-Z0-9_]{10,50}', 'No', 'Si el RFC del emisor de la cuenta ordenante es XEXX010101000, este campo es obligatorio.', '2017-01-01', '0000-00-00', '4.00', 5, '2022-10-24 23:25:00'),
(29, 'Tarjeta de servicios', 'Sí', 'Opcional', 'Opcional', 'Opcional', '[0-9]{15,16}', 'Opcional', 'Opcional', '[0-9]{10,11}|[0-9]{15,16}|[0-9]{18}|[A-Z0-9_]{10,50}', 'No', 'Si el RFC del emisor de la cuenta ordenante es XEXX010101000, este campo es obligatorio.', '2017-01-01', '0000-00-00', '4.00', 5, '2022-10-24 23:25:00'),
(30, 'Aplicación de anticipos', 'No', 'Opcional', 'No', 'No', 'No', 'No', 'No', 'No', 'No', 'No', '2017-08-13', '0000-00-00', '4.00', 5, '2022-10-24 23:25:00'),
(31, 'Intermediario pagos', 'No', 'Opcional', 'No', 'No', 'No', 'No', 'No', 'No', 'No', 'No', '2017-12-05', '0000-00-00', '4.00', 5, '2022-10-24 23:25:00'),
(99, 'Por definir', 'Op', 'Opcional', 'Opcional', 'Opcional', 'Opcional', 'Opcional', 'Opcional', 'Opcional', 'Opcional', 'Opcional', '2017-01-01', '0000-00-00', '4.00', 5, '2022-10-24 23:25:00');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `c_impuesto`
--

CREATE TABLE `c_impuesto` (
  `id` int(3) UNSIGNED ZEROFILL NOT NULL,
  `descripcion` varchar(5) COLLATE utf8_spanish_ci NOT NULL,
  `tasa` decimal(8,6) DEFAULT NULL,
  `retencion` varchar(2) COLLATE utf8_spanish_ci NOT NULL,
  `traslado` varchar(2) COLLATE utf8_spanish_ci NOT NULL,
  `localofederal` varchar(10) COLLATE utf8_spanish_ci NOT NULL,
  `fechainiciovigencia` date NOT NULL,
  `fechafinvigencia` date DEFAULT NULL,
  `version` decimal(4,2) NOT NULL,
  `ultusuario` int(3) NOT NULL,
  `at_update` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci COMMENT='Catalogo de impuesto SAT Version 4.0 México';

--
-- Volcado de datos para la tabla `c_impuesto`
--

INSERT INTO `c_impuesto` (`id`, `descripcion`, `tasa`, `retencion`, `traslado`, `localofederal`, `fechainiciovigencia`, `fechafinvigencia`, `version`, `ultusuario`, `at_update`) VALUES
(001, 'ISR', '0.350000', 'Si', 'No', 'Federal', '2022-01-01', NULL, '4.00', 5, '2022-12-04 22:55:44'),
(002, 'IVA', '0.160000', 'Si', 'Si', 'Federal', '2022-01-01', NULL, '4.00', 5, '2022-12-04 22:55:21'),
(003, 'IEPS', '0.265000', 'Si', 'Si', 'Federal', '2022-01-01', NULL, '4.00', 5, '2022-12-04 22:56:23');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `c_metodopago`
--

CREATE TABLE `c_metodopago` (
  `id` int(11) NOT NULL,
  `id_metodopago` varchar(3) COLLATE utf8_spanish_ci NOT NULL,
  `descripcionmp` varchar(50) COLLATE utf8_spanish_ci NOT NULL,
  `fechainiciovigencia` date NOT NULL,
  `fechafinvigencia` date DEFAULT NULL,
  `version` decimal(4,2) DEFAULT NULL,
  `ultusuario` int(3) UNSIGNED NOT NULL,
  `create_at` datetime NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci COMMENT='Catálogo de Método de Pago SAT v.4.0';

--
-- Volcado de datos para la tabla `c_metodopago`
--

INSERT INTO `c_metodopago` (`id`, `id_metodopago`, `descripcionmp`, `fechainiciovigencia`, `fechafinvigencia`, `version`, `ultusuario`, `create_at`) VALUES
(1, 'PUE', 'Pago en una sola exhibición', '2022-01-01', NULL, '4.00', 5, '2022-10-25 11:49:16'),
(2, 'PPD', 'Pago en parcialidades o diferido', '2022-01-01', NULL, '4.00', 5, '2022-10-25 11:49:16');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `c_moneda`
--

CREATE TABLE `c_moneda` (
  `id` int(3) NOT NULL,
  `id_moneda` varchar(3) COLLATE utf8_spanish_ci DEFAULT NULL,
  `descripcion` varchar(100) COLLATE utf8_spanish_ci NOT NULL,
  `decimales` tinyint(1) DEFAULT NULL,
  `porcentajevariacion` int(3) UNSIGNED DEFAULT NULL,
  `fechainiciodevigencia` date DEFAULT NULL,
  `fechafindevigencia` date DEFAULT NULL,
  `version` decimal(4,2) DEFAULT NULL,
  `ultusuario` int(5) UNSIGNED NOT NULL,
  `create_at` datetime NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci COMMENT='catálogo de monedas SAT Mexico';

--
-- Volcado de datos para la tabla `c_moneda`
--

INSERT INTO `c_moneda` (`id`, `id_moneda`, `descripcion`, `decimales`, `porcentajevariacion`, `fechainiciodevigencia`, `fechafindevigencia`, `version`, `ultusuario`, `create_at`) VALUES
(1, 'AED', 'Dirham de EAU', 2, 500, '2022-01-01', NULL, '4.00', 5, '2022-09-23 13:25:50'),
(2, 'AFN', 'Afghani', 2, 500, '2022-01-01', NULL, '4.00', 5, '2022-09-23 13:25:50'),
(3, 'ALL', 'Lek', 2, 500, '2022-01-01', NULL, '4.00', 5, '2022-09-23 13:25:50'),
(4, 'AMD', 'Dram armenio', 2, 500, '2022-01-01', NULL, '4.00', 5, '2022-09-23 13:25:50'),
(5, 'ANG', 'Florín antillano neerlandés', 2, 500, '2022-01-01', NULL, '4.00', 5, '2022-09-23 13:25:50'),
(6, 'AOA', 'Kwanza', 2, 500, '2022-01-01', NULL, '4.00', 5, '2022-09-23 13:25:50'),
(7, 'ARS', 'Peso Argentino', 2, 500, '2022-01-01', NULL, '4.00', 5, '2022-09-23 13:25:50'),
(8, 'AUD', 'Dólar Australiano', 2, 500, '2022-01-01', NULL, '4.00', 5, '2022-09-23 13:25:50'),
(9, 'AWG', 'Aruba Florin', 2, 500, '2022-01-01', NULL, '4.00', 5, '2022-09-23 13:25:50'),
(10, 'AZN', 'Azerbaijanian Manat', 2, 500, '2022-01-01', NULL, '4.00', 5, '2022-09-23 13:25:50'),
(11, 'BAM', 'Convertibles marca', 2, 500, '2022-01-01', NULL, '4.00', 5, '2022-09-23 13:25:50'),
(12, 'BBD', 'Dólar de Barbados', 2, 500, '2022-01-01', NULL, '4.00', 5, '2022-09-23 13:25:50'),
(13, 'BDT', 'Taka', 2, 500, '2022-01-01', NULL, '4.00', 5, '2022-09-23 13:25:50'),
(14, 'BGN', 'Lev búlgaro', 2, 500, '2022-01-01', NULL, '4.00', 5, '2022-09-23 13:25:50'),
(15, 'BHD', 'Dinar de Bahrein', 3, 500, '2022-01-01', NULL, '4.00', 5, '2022-09-23 13:25:50'),
(16, 'BIF', 'Burundi Franc', 0, 500, '2022-01-01', NULL, '4.00', 5, '2022-09-23 13:25:50'),
(17, 'BMD', 'Dólar de Bermudas', 2, 500, '2022-01-01', NULL, '4.00', 5, '2022-09-23 13:25:50'),
(18, 'BND', 'Dólar de Brunei', 2, 500, '2022-01-01', NULL, '4.00', 5, '2022-09-23 13:25:50'),
(19, 'BOB', 'Boliviano', 2, 500, '2022-01-01', NULL, '4.00', 5, '2022-09-23 13:25:50'),
(20, 'BOV', 'Mvdol', 2, 500, '2022-01-01', NULL, '4.00', 5, '2022-09-23 13:25:50'),
(21, 'BRL', 'Real brasileño', 2, 500, '2022-01-01', NULL, '4.00', 5, '2022-09-23 13:25:50'),
(22, 'BSD', 'Dólar de las Bahamas', 2, 500, '2022-01-01', NULL, '4.00', 5, '2022-09-23 13:25:50'),
(23, 'BTN', 'Ngultrum', 2, 500, '2022-01-01', NULL, '4.00', 5, '2022-09-23 13:25:50'),
(24, 'BWP', 'Pula', 2, 500, '2022-01-01', NULL, '4.00', 5, '2022-09-23 13:25:50'),
(25, 'BYR', 'Rublo bielorruso', 0, 500, '2022-01-01', NULL, '4.00', 5, '2022-09-23 13:25:50'),
(26, 'BZD', 'Dólar de Belice', 2, 500, '2022-01-01', NULL, '4.00', 5, '2022-09-23 13:25:50'),
(27, 'CAD', 'Dólar Canadiense', 2, 500, '2022-01-01', NULL, '4.00', 5, '2022-09-23 13:25:50'),
(28, 'CDF', 'Franco congoleño', 2, 500, '2022-01-01', NULL, '4.00', 5, '2022-09-23 13:25:50'),
(29, 'CHE', 'WIR Euro', 2, 500, '2022-01-01', NULL, '4.00', 5, '2022-09-23 13:25:50'),
(30, 'CHF', 'Franco Suizo', 2, 500, '2022-01-01', NULL, '4.00', 5, '2022-09-23 13:25:50'),
(31, 'CHW', 'Franc WIR', 2, 500, '2022-01-01', NULL, '4.00', 5, '2022-09-23 13:25:50'),
(32, 'CLF', 'Unidad de Fomento', 4, 500, '2022-01-01', NULL, '4.00', 5, '2022-09-23 13:25:50'),
(33, 'CLP', 'Peso chileno', 0, 500, '2022-01-01', NULL, '4.00', 5, '2022-09-23 13:25:50'),
(34, 'CNY', 'Yuan Renminbi', 2, 500, '2022-01-01', NULL, '4.00', 5, '2022-09-23 13:25:50'),
(35, 'COP', 'Peso Colombiano', 2, 500, '2022-01-01', NULL, '4.00', 5, '2022-09-23 13:25:50'),
(36, 'COU', 'Unidad de Valor real', 2, 500, '2022-01-01', NULL, '4.00', 5, '2022-09-23 13:25:50'),
(37, 'CRC', 'Colón costarricense', 2, 500, '2022-01-01', NULL, '4.00', 5, '2022-09-23 13:25:50'),
(38, 'CUC', 'Peso Convertible', 2, 500, '2022-01-01', NULL, '4.00', 5, '2022-09-23 13:25:50'),
(39, 'CUP', 'Peso Cubano', 2, 500, '2022-01-01', NULL, '4.00', 5, '2022-09-23 13:25:50'),
(40, 'CVE', 'Cabo Verde Escudo', 2, 500, '2022-01-01', NULL, '4.00', 5, '2022-09-23 13:25:50'),
(41, 'CZK', 'Corona checa', 2, 500, '2022-01-01', NULL, '4.00', 5, '2022-09-23 13:25:50'),
(42, 'DJF', 'Franco de Djibouti', 0, 500, '2022-01-01', NULL, '4.00', 5, '2022-09-23 13:25:50'),
(43, 'DKK', 'Corona danesa', 2, 500, '2022-01-01', NULL, '4.00', 5, '2022-09-23 13:25:50'),
(44, 'DOP', 'Peso Dominicano', 2, 500, '2022-01-01', NULL, '4.00', 5, '2022-09-23 13:25:50'),
(45, 'DZD', 'Dinar argelino', 2, 500, '2022-01-01', NULL, '4.00', 5, '2022-09-23 13:25:50'),
(46, 'EGP', 'Libra egipcia', 2, 500, '2022-01-01', NULL, '4.00', 5, '2022-09-23 13:25:50'),
(47, 'ERN', 'Nakfa', 2, 500, '2022-01-01', NULL, '4.00', 5, '2022-09-23 13:25:50'),
(48, 'ETB', 'Birr etíope', 2, 500, '2022-01-01', NULL, '4.00', 5, '2022-09-23 13:25:50'),
(49, 'EUR', 'Euro', 2, 500, '2022-01-01', NULL, '4.00', 5, '2022-09-23 13:25:50'),
(50, 'FJD', 'Dólar de Fiji', 2, 500, '2022-01-01', NULL, '4.00', 5, '2022-09-23 13:25:50'),
(51, 'FKP', 'Libra malvinense', 2, 500, '2022-01-01', NULL, '4.00', 5, '2022-09-23 13:25:50'),
(52, 'GBP', 'Libra Esterlina', 2, 500, '2022-01-01', NULL, '4.00', 5, '2022-09-23 13:25:51'),
(53, 'GEL', 'Lari', 2, 500, '2022-01-01', NULL, '4.00', 5, '2022-09-23 13:25:51'),
(54, 'GHS', 'Cedi de Ghana', 2, 500, '2022-01-01', NULL, '4.00', 5, '2022-09-23 13:25:51'),
(55, 'GIP', 'Libra de Gibraltar', 2, 500, '2022-01-01', NULL, '4.00', 5, '2022-09-23 13:25:51'),
(56, 'GMD', 'Dalasi', 2, 500, '2022-01-01', NULL, '4.00', 5, '2022-09-23 13:25:51'),
(57, 'GNF', 'Franco guineano', 0, 500, '2022-01-01', NULL, '4.00', 5, '2022-09-23 13:25:51'),
(58, 'GTQ', 'Quetzal', 2, 500, '2022-01-01', NULL, '4.00', 5, '2022-09-23 13:25:51'),
(59, 'GYD', 'Dólar guyanés', 2, 500, '2022-01-01', NULL, '4.00', 5, '2022-09-23 13:25:51'),
(60, 'HKD', 'Dólar De Hong Kong', 2, 500, '2022-01-01', NULL, '4.00', 5, '2022-09-23 13:25:51'),
(61, 'HNL', 'Lempira', 2, 500, '2022-01-01', NULL, '4.00', 5, '2022-09-23 13:25:51'),
(62, 'HRK', 'Kuna', 2, 500, '2022-01-01', NULL, '4.00', 5, '2022-09-23 13:25:51'),
(63, 'HTG', 'Gourde', 2, 500, '2022-01-01', NULL, '4.00', 5, '2022-09-23 13:25:51'),
(64, 'HUF', 'Florín', 2, 500, '2022-01-01', NULL, '4.00', 5, '2022-09-23 13:25:51'),
(65, 'IDR', 'Rupia', 2, 500, '2022-01-01', NULL, '4.00', 5, '2022-09-23 13:25:51'),
(66, 'ILS', 'Nuevo Shekel Israelí', 2, 500, '2022-01-01', NULL, '4.00', 5, '2022-09-23 13:25:51'),
(67, 'INR', 'Rupia india', 2, 500, '2022-01-01', NULL, '4.00', 5, '2022-09-23 13:25:51'),
(68, 'IQD', 'Dinar iraquí', 3, 500, '2022-01-01', NULL, '4.00', 5, '2022-09-23 13:25:51'),
(69, 'IRR', 'Rial iraní', 2, 500, '2022-01-01', NULL, '4.00', 5, '2022-09-23 13:25:51'),
(70, 'ISK', 'Corona islandesa', 0, 500, '2022-01-01', NULL, '4.00', 5, '2022-09-23 13:25:51'),
(71, 'JMD', 'Dólar Jamaiquino', 2, 500, '2022-01-01', NULL, '4.00', 5, '2022-09-23 13:25:51'),
(72, 'JOD', 'Dinar jordano', 3, 500, '2022-01-01', NULL, '4.00', 5, '2022-09-23 13:25:51'),
(73, 'JPY', 'Yen', 0, 500, '2022-01-01', NULL, '4.00', 5, '2022-09-23 13:25:51'),
(74, 'KES', 'Chelín keniano', 2, 500, '2022-01-01', NULL, '4.00', 5, '2022-09-23 13:25:51'),
(75, 'KGS', 'Som', 2, 500, '2022-01-01', NULL, '4.00', 5, '2022-09-23 13:25:51'),
(76, 'KHR', 'Riel', 2, 500, '2022-01-01', NULL, '4.00', 5, '2022-09-23 13:25:51'),
(77, 'KMF', 'Franco Comoro', 0, 500, '2022-01-01', NULL, '4.00', 5, '2022-09-23 13:25:51'),
(78, 'KPW', 'Corea del Norte ganó', 2, 500, '2022-01-01', NULL, '4.00', 5, '2022-09-23 13:25:51'),
(79, 'KRW', 'Won', 0, 500, '2022-01-01', NULL, '4.00', 5, '2022-09-23 13:25:51'),
(80, 'KWD', 'Dinar kuwaití', 3, 500, '2022-01-01', NULL, '4.00', 5, '2022-09-23 13:25:51'),
(81, 'KYD', 'Dólar de las Islas Caimán', 2, 500, '2022-01-01', NULL, '4.00', 5, '2022-09-23 13:25:51'),
(82, 'KZT', 'Tenge', 2, 500, '2022-01-01', NULL, '4.00', 5, '2022-09-23 13:25:51'),
(83, 'LAK', 'Kip', 2, 500, '2022-01-01', NULL, '4.00', 5, '2022-09-23 13:25:51'),
(84, 'LBP', 'Libra libanesa', 2, 500, '2022-01-01', NULL, '4.00', 5, '2022-09-23 13:25:51'),
(85, 'LKR', 'Rupia de Sri Lanka', 2, 500, '2022-01-01', NULL, '4.00', 5, '2022-09-23 13:25:51'),
(86, 'LRD', 'Dólar liberiano', 2, 500, '2022-01-01', NULL, '4.00', 5, '2022-09-23 13:25:51'),
(87, 'LSL', 'Loti', 2, 500, '2022-01-01', NULL, '4.00', 5, '2022-09-23 13:25:51'),
(88, 'LYD', 'Dinar libio', 3, 500, '2022-01-01', NULL, '4.00', 5, '2022-09-23 13:25:51'),
(89, 'MAD', 'Dirham marroquí', 2, 500, '2022-01-01', NULL, '4.00', 5, '2022-09-23 13:25:51'),
(90, 'MDL', 'Leu moldavo', 2, 500, '2022-01-01', NULL, '4.00', 5, '2022-09-23 13:25:51'),
(91, 'MGA', 'Ariary malgache', 2, 500, '2022-01-01', NULL, '4.00', 5, '2022-09-23 13:25:51'),
(92, 'MKD', 'Denar', 2, 500, '2022-01-01', NULL, '4.00', 5, '2022-09-23 13:25:51'),
(93, 'MMK', 'Kyat', 2, 500, '2022-01-01', NULL, '4.00', 5, '2022-09-23 13:25:51'),
(94, 'MNT', 'Tugrik', 2, 500, '2022-01-01', NULL, '4.00', 5, '2022-09-23 13:25:51'),
(95, 'MOP', 'Pataca', 2, 500, '2022-01-01', NULL, '4.00', 5, '2022-09-23 13:25:51'),
(96, 'MRO', 'Ouguiya', 2, 500, '2022-01-01', NULL, '4.00', 5, '2022-09-23 13:25:51'),
(97, 'MUR', 'Rupia de Mauricio', 2, 500, '2022-01-01', NULL, '4.00', 5, '2022-09-23 13:25:51'),
(98, 'MVR', 'Rupia', 2, 500, '2022-01-01', NULL, '4.00', 5, '2022-09-23 13:25:51'),
(99, 'MWK', 'Kwacha', 2, 500, '2022-01-01', NULL, '4.00', 5, '2022-09-23 13:25:51'),
(100, 'MXN', 'Peso Mexicano', 2, 500, '2022-01-01', NULL, '4.00', 5, '2022-09-23 13:25:51'),
(101, 'MXV', 'México Unidad de Inversión (UDI)', 2, 500, '2022-01-01', NULL, '4.00', 5, '2022-09-23 13:25:51'),
(102, 'MYR', 'Ringgit malayo', 2, 500, '2022-01-01', NULL, '4.00', 5, '2022-09-23 13:25:51'),
(103, 'MZN', 'Mozambique Metical', 2, 500, '2022-01-01', NULL, '4.00', 5, '2022-09-23 13:25:51'),
(104, 'NAD', 'Dólar de Namibia', 2, 500, '2022-01-01', NULL, '4.00', 5, '2022-09-23 13:25:51'),
(105, 'NGN', 'Naira', 2, 500, '2022-01-01', NULL, '4.00', 5, '2022-09-23 13:25:51'),
(106, 'NIO', 'Córdoba Oro', 2, 500, '2022-01-01', NULL, '4.00', 5, '2022-09-23 13:25:51'),
(107, 'NOK', 'Corona noruega', 2, 500, '2022-01-01', NULL, '4.00', 5, '2022-09-23 13:25:51'),
(108, 'NPR', 'Rupia nepalí', 2, 500, '2022-01-01', NULL, '4.00', 5, '2022-09-23 13:25:51'),
(109, 'NZD', 'Dólar de Nueva Zelanda', 2, 500, '2022-01-01', NULL, '4.00', 5, '2022-09-23 13:25:51'),
(110, 'OMR', 'Rial omaní', 3, 500, '2022-01-01', NULL, '4.00', 5, '2022-09-23 13:25:51'),
(111, 'PAB', 'Balboa', 2, 500, '2022-01-01', NULL, '4.00', 5, '2022-09-23 13:25:51'),
(112, 'PEN', 'Nuevo Sol', 2, 500, '2022-01-01', NULL, '4.00', 5, '2022-09-23 13:25:51'),
(113, 'PGK', 'Kina', 2, 500, '2022-01-01', NULL, '4.00', 5, '2022-09-23 13:25:51'),
(114, 'PHP', 'Peso filipino', 2, 500, '2022-01-01', NULL, '4.00', 5, '2022-09-23 13:25:51'),
(115, 'PKR', 'Rupia de Pakistán', 2, 500, '2022-01-01', NULL, '4.00', 5, '2022-09-23 13:25:51'),
(116, 'PLN', 'Zloty', 2, 500, '2022-01-01', NULL, '4.00', 5, '2022-09-23 13:25:51'),
(117, 'PYG', 'Guaraní', 0, 500, '2022-01-01', NULL, '4.00', 5, '2022-09-23 13:25:51'),
(118, 'QAR', 'Qatar Rial', 2, 500, '2022-01-01', NULL, '4.00', 5, '2022-09-23 13:25:51'),
(119, 'RON', 'Leu rumano', 2, 500, '2022-01-01', NULL, '4.00', 5, '2022-09-23 13:25:51'),
(120, 'RSD', 'Dinar serbio', 2, 500, '2022-01-01', NULL, '4.00', 5, '2022-09-23 13:25:52'),
(121, 'RUB', 'Rublo ruso', 2, 500, '2022-01-01', NULL, '4.00', 5, '2022-09-23 13:25:52'),
(122, 'RWF', 'Franco ruandés', 0, 500, '2022-01-01', NULL, '4.00', 5, '2022-09-23 13:25:52'),
(123, 'SAR', 'Riyal saudí', 2, 500, '2022-01-01', NULL, '4.00', 5, '2022-09-23 13:25:52'),
(124, 'SBD', 'Dólar de las Islas Salomón', 2, 500, '2022-01-01', NULL, '4.00', 5, '2022-09-23 13:25:52'),
(125, 'SCR', 'Rupia de Seychelles', 2, 500, '2022-01-01', NULL, '4.00', 5, '2022-09-23 13:25:52'),
(126, 'SDG', 'Libra sudanesa', 2, 500, '2022-01-01', NULL, '4.00', 5, '2022-09-23 13:25:52'),
(127, 'SEK', 'Corona sueca', 2, 500, '2022-01-01', NULL, '4.00', 5, '2022-09-23 13:25:52'),
(128, 'SGD', 'Dólar De Singapur', 2, 500, '2022-01-01', NULL, '4.00', 5, '2022-09-23 13:25:52'),
(129, 'SHP', 'Libra de Santa Helena', 2, 500, '2022-01-01', NULL, '4.00', 5, '2022-09-23 13:25:52'),
(130, 'SLL', 'Leona', 2, 500, '2022-01-01', NULL, '4.00', 5, '2022-09-23 13:25:52'),
(131, 'SOS', 'Chelín somalí', 2, 500, '2022-01-01', NULL, '4.00', 5, '2022-09-23 13:25:52'),
(132, 'SRD', 'Dólar de Suriname', 2, 500, '2022-01-01', NULL, '4.00', 5, '2022-09-23 13:25:52'),
(133, 'SSP', 'Libra sudanesa Sur', 2, 500, '2022-01-01', NULL, '4.00', 5, '2022-09-23 13:25:52'),
(134, 'STD', 'Dobra', 2, 500, '2022-01-01', NULL, '4.00', 5, '2022-09-23 13:25:52'),
(135, 'SVC', 'Colon El Salvador', 2, 500, '2022-01-01', NULL, '4.00', 5, '2022-09-23 13:25:52'),
(136, 'SYP', 'Libra Siria', 2, 500, '2022-01-01', NULL, '4.00', 5, '2022-09-23 13:25:52'),
(137, 'SZL', 'Lilangeni', 2, 500, '2022-01-01', NULL, '4.00', 5, '2022-09-23 13:25:52'),
(138, 'THB', 'Baht', 2, 500, '2022-01-01', NULL, '4.00', 5, '2022-09-23 13:25:52'),
(139, 'TJS', 'Somoni', 2, 500, '2022-01-01', NULL, '4.00', 5, '2022-09-23 13:25:52'),
(140, 'TMT', 'Turkmenistán nuevo manat', 2, 500, '2022-01-01', NULL, '4.00', 5, '2022-09-23 13:25:52'),
(141, 'TND', 'Dinar tunecino', 3, 500, '2022-01-01', NULL, '4.00', 5, '2022-09-23 13:25:52'),
(142, 'TOP', 'Pa\'anga', 2, 500, '2022-01-01', NULL, '4.00', 5, '2022-09-23 13:25:52'),
(143, 'TRY', 'Lira turca', 2, 500, '2022-01-01', NULL, '4.00', 5, '2022-09-23 13:25:52'),
(144, 'TTD', 'Dólar de Trinidad y Tobago', 2, 500, '2022-01-01', NULL, '4.00', 5, '2022-09-23 13:25:52'),
(145, 'TWD', 'Nuevo dólar de Taiwán', 2, 500, '2022-01-01', NULL, '4.00', 5, '2022-09-23 13:25:52'),
(146, 'TZS', 'Shilling tanzano', 2, 500, '2022-01-01', NULL, '4.00', 5, '2022-09-23 13:25:52'),
(147, 'UAH', 'Hryvnia', 2, 500, '2022-01-01', NULL, '4.00', 5, '2022-09-23 13:25:52'),
(148, 'UGX', 'Shilling de Uganda', 0, 500, '2022-01-01', NULL, '4.00', 5, '2022-09-23 13:25:52'),
(149, 'USD', 'Dólar americano', 2, 500, '2022-01-01', NULL, '4.00', 5, '2022-09-23 13:25:52'),
(150, 'USN', 'Dólar estadounidense (día siguiente)', 2, 500, '2022-01-01', NULL, '4.00', 5, '2022-09-23 13:25:52'),
(151, 'UYI', 'Peso Uruguay en Unidades Indexadas (URUIURUI)', 0, 500, '2022-01-01', NULL, '4.00', 5, '2022-09-23 13:25:52'),
(152, 'UYU', 'Peso Uruguayo', 2, 500, '2022-01-01', NULL, '4.00', 5, '2022-09-23 13:25:52'),
(153, 'UZS', 'Uzbekistán Sum', 2, 500, '2022-01-01', NULL, '4.00', 5, '2022-09-23 13:25:52'),
(154, 'VEF', 'Bolívar', 2, 500, '2022-01-01', NULL, '4.00', 5, '2022-09-23 13:25:52'),
(155, 'VND', 'Dong', 0, 500, '2022-01-01', NULL, '4.00', 5, '2022-09-23 13:25:52'),
(156, 'VUV', 'Vatu', 0, 500, '2022-01-01', NULL, '4.00', 5, '2022-09-23 13:25:52'),
(157, 'WST', 'Tala', 2, 500, '2022-01-01', NULL, '4.00', 5, '2022-09-23 13:25:52'),
(158, 'XAF', 'Franco CFA BEAC', 0, 500, '2022-01-01', NULL, '4.00', 5, '2022-09-23 13:25:52'),
(159, 'XAG', 'Plata', 0, 500, '2022-01-01', NULL, '4.00', 5, '2022-09-23 13:25:52'),
(160, 'XAU', 'Oro', 0, 500, '2022-01-01', NULL, '4.00', 5, '2022-09-23 13:25:52'),
(161, 'XBA', 'Unidad de Mercados de Bonos Unidad Europea Composite (EURCO)', 0, 500, '2022-01-01', NULL, '4.00', 5, '2022-09-23 13:25:52'),
(162, 'XBB', 'Unidad Monetaria de Bonos de Mercados Unidad Europea (UEM-6)', 0, 500, '2022-01-01', NULL, '4.00', 5, '2022-09-23 13:25:52'),
(163, 'XBC', 'Mercados de Bonos Unidad Europea unidad de cuenta a 9 (UCE-9)', 0, 500, '2022-01-01', NULL, '4.00', 5, '2022-09-23 13:25:52'),
(164, 'XBD', 'Mercados de Bonos Unidad Europea unidad de cuenta a 17 (UCE-17)', 0, 500, '2022-01-01', NULL, '4.00', 5, '2022-09-23 13:25:52'),
(165, 'XCD', 'Dólar del Caribe Oriental', 2, 500, '2022-01-01', NULL, '4.00', 5, '2022-09-23 13:25:52'),
(166, 'XDR', 'DEG (Derechos Especiales de Giro)', 0, 500, '2022-01-01', NULL, '4.00', 5, '2022-09-23 13:25:52'),
(167, 'XOF', 'Franco CFA BCEAO', 0, 500, '2022-01-01', NULL, '4.00', 5, '2022-09-23 13:25:52'),
(168, 'XPD', 'Paladio', 0, 500, '2022-01-01', NULL, '4.00', 5, '2022-09-23 13:25:52'),
(169, 'XPF', 'Franco CFP', 0, 500, '2022-01-01', NULL, '4.00', 5, '2022-09-23 13:25:52'),
(170, 'XPT', 'Platino', 0, 500, '2022-01-01', NULL, '4.00', 5, '2022-09-23 13:25:52'),
(171, 'XSU', 'Sucre', 0, 500, '2022-01-01', NULL, '4.00', 5, '2022-09-23 13:25:52'),
(172, 'XTS', 'Códigos reservados específicamente para propósitos de prueba', 0, 0, '2022-01-01', NULL, '4.00', 5, '2022-09-23 13:25:52'),
(173, 'XUA', 'Unidad ADB de Cuenta', 0, 500, '2022-01-01', NULL, '4.00', 5, '2022-09-23 13:25:52'),
(174, 'XXX', 'Los códigos asignados para las transacciones en que intervenga ninguna moneda', 0, 0, '2022-01-01', NULL, '4.00', 5, '2022-09-23 13:25:52'),
(175, 'YER', 'Rial yemení', 2, 500, '2022-01-01', NULL, '4.00', 5, '2022-09-23 13:25:52'),
(176, 'ZAR', 'Rand', 2, 500, '2022-01-01', NULL, '4.00', 5, '2022-09-23 13:25:52'),
(177, 'ZMW', 'Kwacha zambiano', 2, 500, '2022-01-01', NULL, '4.00', 5, '2022-09-23 13:25:52'),
(178, 'ZWL', 'Zimbabwe Dólar', 2, 500, '2022-01-01', NULL, '4.00', 5, '2022-09-23 13:25:52');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `c_objetoimp`
--

CREATE TABLE `c_objetoimp` (
  `id` int(2) UNSIGNED ZEROFILL NOT NULL,
  `descripcion` varchar(50) COLLATE utf8_spanish_ci NOT NULL,
  `fechainiciovigencia` date NOT NULL,
  `fechafinvigencia` date DEFAULT NULL,
  `version` decimal(4,2) NOT NULL,
  `ultusuario` int(3) NOT NULL,
  `at_update` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci COMMENT='Catálogo Objeto impuesto. version 4.0 SAT';

--
-- Volcado de datos para la tabla `c_objetoimp`
--

INSERT INTO `c_objetoimp` (`id`, `descripcion`, `fechainiciovigencia`, `fechafinvigencia`, `version`, `ultusuario`, `at_update`) VALUES
(01, 'No objeto de impuesto.', '2022-01-01', NULL, '4.00', 5, '2022-12-05 04:18:00'),
(02, 'Sí objeto de impuesto.', '2022-01-01', NULL, '4.00', 5, '2022-12-05 04:17:46'),
(03, 'Sí objeto del impuesto y no obligado al desglose.', '2022-01-01', NULL, '4.00', 5, '2022-12-05 04:17:46'),
(04, 'Sí objeto del impuesto y no causa impuesto.', '2022-01-01', NULL, '4.00', 5, '2022-12-05 04:17:46');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `c_regimenfiscal`
--

CREATE TABLE `c_regimenfiscal` (
  `id` int(11) NOT NULL,
  `descripcion` varchar(100) COLLATE utf8_spanish_ci DEFAULT NULL,
  `fisica` varchar(2) COLLATE utf8_spanish_ci NOT NULL,
  `moral` varchar(2) COLLATE utf8_spanish_ci NOT NULL,
  `fechadeiniciodevigencia` date DEFAULT NULL,
  `fechadefindevigencia` date DEFAULT NULL,
  `version` decimal(4,2) NOT NULL,
  `ultusuario` int(3) NOT NULL,
  `create_at` datetime NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci COMMENT='catalogo regimen fiscal del SAT V.4.0';

--
-- Volcado de datos para la tabla `c_regimenfiscal`
--

INSERT INTO `c_regimenfiscal` (`id`, `descripcion`, `fisica`, `moral`, `fechadeiniciodevigencia`, `fechadefindevigencia`, `version`, `ultusuario`, `create_at`) VALUES
(601, 'General de Ley Personas Morales', 'No', 'Sí', '2016-11-12', NULL, '4.00', 5, '2022-10-25 21:49:22'),
(603, 'Personas Morales con Fines no Lucrativos', 'No', 'Sí', '2016-11-12', NULL, '4.00', 5, '2022-10-25 21:49:22'),
(605, 'Sueldos y Salarios e Ingresos Asimilados a Salarios', 'Sí', 'No', '2016-11-12', NULL, '4.00', 5, '2022-10-25 21:49:22'),
(606, 'Arrendamiento', 'Sí', 'No', '2016-11-12', NULL, '4.00', 5, '2022-10-25 21:49:22'),
(607, 'Régimen de Enajenación o Adquisición de Bienes', 'Sí', 'No', '2016-11-12', NULL, '4.00', 5, '2022-10-25 21:49:22'),
(608, 'Demás ingresos', 'Sí', 'No', '2016-11-12', NULL, '4.00', 5, '2022-10-25 21:49:22'),
(609, 'Consolidación', 'No', 'Sí', '2016-11-12', '2019-12-31', '4.00', 5, '2022-10-25 21:49:42'),
(610, 'Residentes en el Extranjero sin Establecimiento Permanente en México', 'Sí', 'Sí', '2016-11-12', NULL, '4.00', 5, '2022-10-25 21:49:22'),
(611, 'Ingresos por Dividendos (socios y accionistas)', 'Sí', 'No', '2016-11-12', NULL, '4.00', 5, '2022-10-25 21:49:22'),
(612, 'Personas Físicas con Actividades Empresariales y Profesionales', 'Sí', 'No', '2016-11-12', NULL, '4.00', 5, '2022-10-25 21:49:22'),
(614, 'Ingresos por intereses', 'Sí', 'No', '2016-11-12', NULL, '4.00', 5, '2022-10-25 21:49:22'),
(615, 'Régimen de los ingresos por obtención de premios', 'Sí', 'No', '2016-11-12', NULL, '4.00', 5, '2022-10-25 21:49:22'),
(616, 'Sin obligaciones fiscales', 'Sí', 'No', '2016-11-12', NULL, '4.00', 5, '2022-10-25 21:49:22'),
(620, 'Sociedades Cooperativas de Producción que optan por diferir sus ingresos', 'No', 'Sí', '2016-11-12', NULL, '4.00', 5, '2022-10-25 21:49:22'),
(621, 'Incorporación Fiscal', 'Sí', 'No', '2016-11-12', NULL, '4.00', 5, '2022-10-25 21:49:22'),
(622, 'Actividades Agrícolas, Ganaderas, Silvícolas y Pesqueras', 'No', 'Sí', '2016-11-12', NULL, '4.00', 5, '2022-10-25 21:49:22'),
(623, 'Opcional para Grupos de Sociedades', 'No', 'Sí', '2016-11-12', NULL, '4.00', 5, '2022-10-25 21:49:22'),
(624, 'Coordinados', 'No', 'Sí', '2016-11-12', NULL, '4.00', 5, '2022-10-25 21:49:22'),
(625, 'Régimen de las Actividades Empresariales con ingresos a través de Plataformas Tecnológicas', 'Sí', 'No', '2020-06-01', NULL, '4.00', 5, '2022-10-25 21:49:22'),
(626, 'Régimen Simplificado de Confianza', 'Sí', 'Sí', '2022-01-01', NULL, '4.00', 5, '2022-10-25 21:49:22'),
(628, 'Hidrocarburos', 'No', 'Sí', '2024-01-01', NULL, '4.00', 5, '2022-10-25 21:49:22'),
(629, 'De los Regímenes Fiscales Preferentes y de las Empresas Multinacionales', 'Sí', 'No', '2024-01-01', NULL, '4.00', 5, '2022-10-25 21:49:22'),
(630, 'Enajenación de acciones en bolsa de valores', 'Sí', 'No', '2024-01-01', NULL, '4.00', 5, '2022-10-25 21:49:22');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `c_tasaocuota`
--

CREATE TABLE `c_tasaocuota` (
  `id` int(10) UNSIGNED NOT NULL,
  `rangofijo` varchar(5) COLLATE utf8_spanish_ci NOT NULL,
  `valorminimo` decimal(8,6) DEFAULT NULL,
  `valormaximo` decimal(8,6) DEFAULT NULL,
  `impuesto` varchar(40) COLLATE utf8_spanish_ci NOT NULL,
  `factor` varchar(5) COLLATE utf8_spanish_ci NOT NULL,
  `traslado` varchar(2) COLLATE utf8_spanish_ci NOT NULL,
  `retencion` varchar(2) COLLATE utf8_spanish_ci NOT NULL,
  `fechainiciovigencia` date NOT NULL,
  `fechafinvigencia` date DEFAULT NULL,
  `version` decimal(4,2) DEFAULT NULL,
  `ultusuario` int(2) UNSIGNED NOT NULL,
  `at_update` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci COMMENT='Catálogo de tasas o cuotas de impuestos. SAT 4.0 México';

--
-- Volcado de datos para la tabla `c_tasaocuota`
--

INSERT INTO `c_tasaocuota` (`id`, `rangofijo`, `valorminimo`, `valormaximo`, `impuesto`, `factor`, `traslado`, `retencion`, `fechainiciovigencia`, `fechafinvigencia`, `version`, `ultusuario`, `at_update`) VALUES
(1, 'Fijo', NULL, '0.000000', 'IVA', 'Tasa', 'Si', 'No', '2022-01-01', NULL, '4.00', 5, '2022-11-26 17:48:17'),
(2, 'Fijo', NULL, '0.160000', 'IVA', 'Tasa', 'Si', 'No', '2022-01-01', NULL, '4.00', 5, '2022-11-26 17:48:17'),
(3, 'Rango', '0.000000', '0.160000', 'IVA', 'Tasa', 'No', 'Si', '2022-01-01', NULL, '4.00', 5, '2022-11-26 17:48:17'),
(4, 'Fijo', NULL, '0.080000', 'IVA Credito aplicado del 50%', 'Tasa', 'Si', 'No', '2022-01-01', NULL, '4.00', 5, '2022-11-26 17:48:17'),
(5, 'Fijo', NULL, '0.265000', 'IEPS', 'Tasa', 'Si', 'Si', '2022-01-01', NULL, '4.00', 5, '2022-11-26 17:48:17'),
(6, 'Fijo', NULL, '0.300000', 'IEPS', 'Tasa', 'Si', 'Si', '2022-01-01', NULL, '4.00', 5, '2022-11-26 17:48:17'),
(7, 'Fijo', NULL, '0.530000', 'IEPS', 'Tasa', 'Si', 'Si', '2022-01-01', NULL, '4.00', 5, '2022-11-26 17:48:17'),
(8, 'Fijo', NULL, '0.500000', 'IEPS', 'Tasa', 'Si', 'Si', '2022-01-01', NULL, '4.00', 5, '2022-11-26 17:48:17'),
(9, 'Fijo', NULL, '1.600000', 'IEPS', 'Tasa', 'Si', 'Si', '2022-01-01', NULL, '4.00', 5, '2022-11-26 17:48:17'),
(10, 'Fijo', NULL, '0.304000', 'IEPS', 'Tasa', 'Si', 'Si', '2022-01-01', NULL, '4.00', 5, '2022-11-26 17:48:17'),
(11, 'Fijo', NULL, '0.250000', 'IEPS', 'Tasa', 'Si', 'Si', '2022-01-01', NULL, '4.00', 5, '2022-11-26 17:48:17'),
(12, 'Fijo', NULL, '0.090000', 'IEPS', 'Tasa', 'Si', 'Si', '2022-01-01', NULL, '4.00', 5, '2022-11-26 17:48:17'),
(13, 'Fijo', NULL, '0.080000', 'IEPS', 'Tasa', 'Si', 'Si', '2022-01-01', NULL, '4.00', 5, '2022-11-26 17:48:17'),
(14, 'Fijo', NULL, '0.070000', 'IEPS', 'Tasa', 'Si', 'Si', '2022-01-01', NULL, '4.00', 5, '2022-11-26 17:48:17'),
(15, 'Fijo', NULL, '0.060000', 'IEPS', 'Tasa', 'Si', 'Si', '2022-01-01', NULL, '4.00', 5, '2022-11-26 17:48:17'),
(16, 'Fijo', NULL, '0.030000', 'IEPS', 'Tasa', 'Si', 'No', '2022-01-01', NULL, '4.00', 5, '2022-11-26 17:48:17'),
(17, 'Fijo', NULL, '0.000000', 'IEPS', 'Tasa', 'Si', 'No', '2022-01-01', NULL, '4.00', 5, '2022-11-26 17:48:17'),
(18, 'Rango', '0.000000', '59.144900', 'IEPS', 'Cuota', 'Si', 'Si', '2022-01-01', NULL, '4.00', 5, '2022-11-26 17:48:17'),
(19, 'Rango', '0.000000', '0.350000', 'ISR', 'Tasa', 'No', 'Si', '2022-01-01', NULL, '4.00', 5, '2022-11-26 17:48:17');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `c_tipofactor`
--

CREATE TABLE `c_tipofactor` (
  `id` tinyint(1) UNSIGNED NOT NULL,
  `tipofactor` varchar(10) COLLATE utf8_spanish_ci DEFAULT NULL,
  `fechainiciovigencia` date DEFAULT NULL,
  `fechafinvigencia` date DEFAULT NULL,
  `version` decimal(4,2) NOT NULL,
  `ultusuario` int(3) NOT NULL,
  `at_update` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci COMMENT='Catálogo tipo factor. V. 4.0 SAT MEXICO';

--
-- Volcado de datos para la tabla `c_tipofactor`
--

INSERT INTO `c_tipofactor` (`id`, `tipofactor`, `fechainiciovigencia`, `fechafinvigencia`, `version`, `ultusuario`, `at_update`) VALUES
(1, 'Tasa', '2022-01-01', NULL, '4.00', 5, '2022-11-26 05:08:31'),
(2, 'Cuota', '2022-01-01', NULL, '4.00', 5, '2022-11-26 05:08:31'),
(3, 'Exento', '2022-01-01', NULL, '4.00', 5, '2022-11-26 05:08:31');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `c_tiposdecomprobantes`
--

CREATE TABLE `c_tiposdecomprobantes` (
  `id` int(2) NOT NULL COMMENT 'Clave primaria',
  `idtipodecomprobante` char(1) COLLATE utf8_spanish_ci NOT NULL COMMENT 'id de tipo comprobante',
  `descripcion` varchar(10) COLLATE utf8_spanish_ci NOT NULL COMMENT 'Descripcion del t. de comprobante',
  `valor_maximo` decimal(25,6) NOT NULL COMMENT 'valor maximo',
  `vigencia_desde` date DEFAULT NULL,
  `vigencia_hasta` date DEFAULT NULL,
  `version` decimal(4,2) DEFAULT NULL,
  `ultusuario` int(5) UNSIGNED DEFAULT NULL,
  `create_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci COMMENT='tabla de tipo de comprobantes SAT Mexico Ver.4.0';

--
-- Volcado de datos para la tabla `c_tiposdecomprobantes`
--

INSERT INTO `c_tiposdecomprobantes` (`id`, `idtipodecomprobante`, `descripcion`, `valor_maximo`, `vigencia_desde`, `vigencia_hasta`, `version`, `ultusuario`, `create_at`) VALUES
(1, 'I', 'Ingreso', '999999999999999999.999999', '2022-01-01', '0000-00-00', '4.00', 5, '2022-09-23 21:17:54'),
(2, 'E', 'Egreso', '999999999999999999.999999', '2022-01-01', '0000-00-00', '4.00', 5, '2022-09-23 21:17:54'),
(3, 'T', 'Traslado', '0.000000', '2022-01-01', '0000-00-00', '4.00', 5, '2022-09-23 21:17:54'),
(4, 'N', 'Nómina', '999999999999999999.999999', '2022-01-01', '0000-00-00', '4.00', 5, '2022-09-23 21:17:54'),
(5, 'P', 'Pago', '999999999999999999.999999', '2022-01-01', '0000-00-00', '4.00', 5, '2022-09-23 21:17:54');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `c_tiposrelaciones`
--

CREATE TABLE `c_tiposrelaciones` (
  `id_tiporelacion` int(11) NOT NULL,
  `descripcion` varchar(100) COLLATE utf8_spanish_ci NOT NULL,
  `vigencia_desde` date DEFAULT NULL,
  `vigencia_hasta` date DEFAULT NULL,
  `version` decimal(4,2) DEFAULT NULL,
  `ultusuario` int(5) UNSIGNED DEFAULT NULL,
  `create_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci COMMENT='catálogo de tipos de relaciónes SAT Mexico';

--
-- Volcado de datos para la tabla `c_tiposrelaciones`
--

INSERT INTO `c_tiposrelaciones` (`id_tiporelacion`, `descripcion`, `vigencia_desde`, `vigencia_hasta`, `version`, `ultusuario`, `create_at`) VALUES
(1, 'Nota de crédito de los documentos relacionados', '2022-01-01', '0000-00-00', '4.00', 5, '2022-09-24 19:03:07'),
(2, 'Nota de débito de los documentos relacionados', '2022-01-01', '0000-00-00', '4.00', 5, '2022-09-24 19:03:07'),
(3, 'Devolución de mercancía sobre facturas o traslados previos', '2022-01-01', '0000-00-00', '4.00', 5, '2022-09-24 19:03:07'),
(4, 'Sustitución de los CFDI previos', '2022-01-01', '0000-00-00', '4.00', 5, '2022-09-24 19:03:07'),
(5, 'Traslados de mercancias facturados previamente', '2022-01-01', '0000-00-00', '4.00', 5, '2022-09-24 19:03:07'),
(6, 'Factura generada por los traslados previos', '2022-01-01', '0000-00-00', '4.00', 5, '2022-09-24 19:03:07'),
(7, 'CFDI por aplicación de anticipo', '2022-01-01', '0000-00-00', '4.00', 5, '2022-09-24 19:03:07'),
(8, 'Factura generada por pagos en parcialidades', '2022-01-01', '2022-06-30', '4.00', 5, '2022-09-24 19:03:07'),
(9, 'Factura generada por pagos diferidos', '2022-01-01', '2022-06-30', '4.00', 5, '2022-09-24 19:03:07');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `c_usocfdi`
--

CREATE TABLE `c_usocfdi` (
  `id` int(2) UNSIGNED NOT NULL,
  `id_cfdi` varchar(3) COLLATE utf8_spanish_ci NOT NULL,
  `descripcion` varchar(100) COLLATE utf8_spanish_ci NOT NULL,
  `aplica_fisica` tinyint(1) UNSIGNED NOT NULL,
  `aplica_moral` tinyint(1) UNSIGNED NOT NULL,
  `vigencia_desde` date DEFAULT NULL,
  `vigencia_hasta` date DEFAULT NULL,
  `reg_fiscales_recep` text COLLATE utf8_spanish_ci NOT NULL,
  `version` decimal(4,2) DEFAULT NULL,
  `ultusuario` int(5) UNSIGNED DEFAULT NULL,
  `create_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci COMMENT='catálogo de usos de CFDI SAT Mexico';

--
-- Volcado de datos para la tabla `c_usocfdi`
--

INSERT INTO `c_usocfdi` (`id`, `id_cfdi`, `descripcion`, `aplica_fisica`, `aplica_moral`, `vigencia_desde`, `vigencia_hasta`, `reg_fiscales_recep`, `version`, `ultusuario`, `create_at`) VALUES
(1, 'G01', 'Adquisición de mercancías.', 1, 1, '2022-01-01', '0000-00-00', '601, 603, 606, 612, 620, 621, 622, 623, 624, 625,626', '4.00', 5, '2022-09-24 19:21:24'),
(2, 'G02', 'Devoluciones, descuentos o bonificaciones.', 1, 1, '2022-01-01', '0000-00-00', '601, 603, 606, 612, 620, 621, 622, 623, 624, 625,626', '4.00', 5, '2022-09-24 19:21:24'),
(3, 'G03', 'Gastos en general.', 1, 1, '2022-01-01', '0000-00-00', '601, 603, 606, 612, 620, 621, 622, 623, 624, 625, 626', '4.00', 5, '2022-09-24 19:21:24'),
(4, 'I01', 'Construcciones.', 1, 1, '2022-01-01', '0000-00-00', '601, 603, 606, 612, 620, 621, 622, 623, 624, 625, 626', '4.00', 5, '2022-09-24 19:21:24'),
(5, 'I02', 'Mobiliario y equipo de oficina por inversiones.', 1, 1, '2022-01-01', '0000-00-00', '601, 603, 606, 612, 620, 621, 622, 623, 624, 625, 626', '4.00', 5, '2022-09-24 19:21:24'),
(6, 'I03', 'Equipo de transporte.', 1, 1, '2022-01-01', '0000-00-00', '601, 603, 606, 612, 620, 621, 622, 623, 624, 625, 626', '4.00', 5, '2022-09-24 19:21:24'),
(7, 'I04', 'Equipo de computo y accesorios.', 1, 1, '2022-01-01', '0000-00-00', '601, 603, 606, 612, 620, 621, 622, 623, 624, 625, 626', '4.00', 5, '2022-09-24 19:21:24'),
(8, 'I05', 'Dados, troqueles, moldes, matrices y herramental.', 1, 1, '2022-01-01', '0000-00-00', '601, 603, 606, 612, 620, 621, 622, 623, 624, 625, 626', '4.00', 5, '2022-09-24 19:21:24'),
(9, 'I06', 'Comunicaciones telefónicas.', 1, 1, '2022-01-01', '0000-00-00', '601, 603, 606, 612, 620, 621, 622, 623, 624, 625, 626', '4.00', 5, '2022-09-24 19:21:24'),
(10, 'I07', 'Comunicaciones satelitales.', 1, 1, '2022-01-01', '0000-00-00', '601, 603, 606, 612, 620, 621, 622, 623, 624, 625, 626', '4.00', 5, '2022-09-24 19:21:24'),
(11, 'I08', 'Otra maquinaria y equipo.', 1, 1, '2022-01-01', '0000-00-00', '601, 603, 606, 612, 620, 621, 622, 623, 624, 625, 626', '4.00', 5, '2022-09-24 19:21:24'),
(12, 'D01', 'Honorarios médicos, dentales y gastos hospitalarios.', 1, 0, '2022-01-01', '0000-00-00', '605, 606, 608, 611, 612, 614, 607, 615, 625', '4.00', 5, '2022-09-24 19:21:24'),
(13, 'D02', 'Gastos médicos por incapacidad o discapacidad.', 1, 0, '2022-01-01', '0000-00-00', '605, 606, 608, 611, 612, 614, 607, 615, 625', '4.00', 5, '2022-09-24 19:21:24'),
(14, 'D03', 'Gastos funerales.', 1, 0, '2022-01-01', '0000-00-00', '605, 606, 608, 611, 612, 614, 607, 615, 625', '4.00', 5, '2022-09-24 19:21:24'),
(15, 'D04', 'Donativos.', 1, 0, '2022-01-01', '0000-00-00', '605, 606, 608, 611, 612, 614, 607, 615, 625', '4.00', 5, '2022-09-24 19:21:24'),
(16, 'D05', 'Intereses reales efectivamente pagados por créditos hipotecarios (casa habitación).', 1, 0, '2022-01-01', '0000-00-00', '605, 606, 608, 611, 612, 614, 607, 615, 625', '4.00', 5, '2022-09-24 19:21:24'),
(17, 'D06', 'Aportaciones voluntarias al SAR.', 1, 0, '2022-01-01', '0000-00-00', '605, 606, 608, 611, 612, 614, 607, 615, 625', '4.00', 5, '2022-09-24 19:21:24'),
(18, 'D07', 'Primas por seguros de gastos médicos.', 1, 0, '2022-01-01', '0000-00-00', '605, 606, 608, 611, 612, 614, 607, 615, 625', '4.00', 5, '2022-09-24 19:21:24'),
(19, 'D08', 'Gastos de transportación escolar obligatoria.', 1, 0, '2022-01-01', '0000-00-00', '605, 606, 608, 611, 612, 614, 607, 615, 625', '4.00', 5, '2022-09-24 19:21:24'),
(20, 'D09', 'Depósitos en cuentas para el ahorro, primas que tengan como base planes de pensiones.', 1, 0, '2022-01-01', '0000-00-00', '605, 606, 608, 611, 612, 614, 607, 615, 625', '4.00', 5, '2022-09-24 19:21:24'),
(21, 'D10', 'Pagos por servicios educativos (colegiaturas).', 1, 0, '2022-01-01', '0000-00-00', '605, 606, 608, 611, 612, 614, 607, 615, 625', '4.00', 5, '2022-09-24 19:21:24'),
(22, 'S01', 'Sin efectos fiscales.', 1, 1, '2022-01-01', '0000-00-00', '601, 603, 605, 606, 608, 610, 611, 612, 614, 616, 620, 621, 622, 623, 624, 607, 615, 625, 626', '4.00', 5, '2022-09-24 19:21:24'),
(23, 'CP0', 'Pagos', 1, 1, '2022-01-01', '0000-00-00', '601, 603, 605, 606, 608, 610, 611, 612, 614, 616, 620, 621, 622, 623, 624, 607, 615, 625, 626', '4.00', 5, '2022-09-24 19:21:24'),
(24, 'CN0', 'Nómina', 1, 0, '2022-01-01', '0000-00-00', '605', '4.00', 5, '2022-09-24 19:21:24');

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

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `clientes`
--
ALTER TABLE `clientes`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `complementodepago`
--
ALTER TABLE `complementodepago`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `c_exportacion`
--
ALTER TABLE `c_exportacion`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `c_formapago`
--
ALTER TABLE `c_formapago`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `c_impuesto`
--
ALTER TABLE `c_impuesto`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `c_metodopago`
--
ALTER TABLE `c_metodopago`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `c_moneda`
--
ALTER TABLE `c_moneda`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `id_moneda` (`id_moneda`);

--
-- Indices de la tabla `c_objetoimp`
--
ALTER TABLE `c_objetoimp`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `c_regimenfiscal`
--
ALTER TABLE `c_regimenfiscal`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `c_tasaocuota`
--
ALTER TABLE `c_tasaocuota`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `c_tipofactor`
--
ALTER TABLE `c_tipofactor`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `c_tiposdecomprobantes`
--
ALTER TABLE `c_tiposdecomprobantes`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `idtipodecomprobante` (`idtipodecomprobante`),
  ADD KEY `descripcion` (`descripcion`);

--
-- Indices de la tabla `c_tiposrelaciones`
--
ALTER TABLE `c_tiposrelaciones`
  ADD PRIMARY KEY (`id_tiporelacion`);

--
-- Indices de la tabla `c_usocfdi`
--
ALTER TABLE `c_usocfdi`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `empresa`
--
ALTER TABLE `empresa`
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
-- AUTO_INCREMENT de la tabla `complementodepago`
--
ALTER TABLE `complementodepago`
  MODIFY `id` int(5) UNSIGNED ZEROFILL NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `c_exportacion`
--
ALTER TABLE `c_exportacion`
  MODIFY `id` int(2) UNSIGNED ZEROFILL NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT de la tabla `c_formapago`
--
ALTER TABLE `c_formapago`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=100;

--
-- AUTO_INCREMENT de la tabla `c_impuesto`
--
ALTER TABLE `c_impuesto`
  MODIFY `id` int(3) UNSIGNED ZEROFILL NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT de la tabla `c_metodopago`
--
ALTER TABLE `c_metodopago`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT de la tabla `c_moneda`
--
ALTER TABLE `c_moneda`
  MODIFY `id` int(3) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=179;

--
-- AUTO_INCREMENT de la tabla `c_objetoimp`
--
ALTER TABLE `c_objetoimp`
  MODIFY `id` int(2) UNSIGNED ZEROFILL NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT de la tabla `c_regimenfiscal`
--
ALTER TABLE `c_regimenfiscal`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=631;

--
-- AUTO_INCREMENT de la tabla `c_tasaocuota`
--
ALTER TABLE `c_tasaocuota`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;

--
-- AUTO_INCREMENT de la tabla `c_tipofactor`
--
ALTER TABLE `c_tipofactor`
  MODIFY `id` tinyint(1) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT de la tabla `c_tiposdecomprobantes`
--
ALTER TABLE `c_tiposdecomprobantes`
  MODIFY `id` int(2) NOT NULL AUTO_INCREMENT COMMENT 'Clave primaria', AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT de la tabla `c_tiposrelaciones`
--
ALTER TABLE `c_tiposrelaciones`
  MODIFY `id_tiporelacion` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT de la tabla `c_usocfdi`
--
ALTER TABLE `c_usocfdi`
  MODIFY `id` int(2) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=25;

--
-- AUTO_INCREMENT de la tabla `empresa`
--
ALTER TABLE `empresa`
  MODIFY `id` tinyint(1) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
