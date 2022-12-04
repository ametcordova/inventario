-- phpMyAdmin SQL Dump
-- version 5.0.2
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 02-12-2022 a las 06:10:04
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
-- Estructura de tabla para la tabla `clavesfacturacion`
--

CREATE TABLE `clavesfacturacion` (
  `id` int(3) UNSIGNED ZEROFILL NOT NULL,
  `idprodservicio` bigint(8) UNSIGNED NOT NULL,
  `descripcionsat` varchar(150) COLLATE utf8_spanish_ci NOT NULL,
  `concepto` varchar(250) COLLATE utf8_spanish_ci DEFAULT NULL,
  `objimpuesto` int(2) UNSIGNED ZEROFILL DEFAULT NULL,
  `unidadmedida` varchar(3) COLLATE utf8_spanish_ci DEFAULT NULL,
  `cantidad` decimal(6,2) DEFAULT 1.00,
  `preciounitario` decimal(14,6) DEFAULT NULL,
  `version` decimal(4,2) DEFAULT NULL,
  `tipo` char(1) COLLATE utf8_spanish_ci DEFAULT NULL COMMENT 'Ingreso P comp d pag',
  `ultusuario` int(3) UNSIGNED DEFAULT NULL,
  `create_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci COMMENT='tabla con las claves mas usadas por la empresa';

--
-- Volcado de datos para la tabla `clavesfacturacion`
--

INSERT INTO `clavesfacturacion` (`id`, `idprodservicio`, `descripcionsat`, `concepto`, `objimpuesto`, `unidadmedida`, `cantidad`, `preciounitario`, `version`, `tipo`, `ultusuario`, `create_at`) VALUES
(001, 72151602, 'Servicio de instalación de cables de fibra óptica', 'BAJANTE PROY: ODC: No. REPSE: 09679. CONTRATO: BAJSUR063-01', 02, 'E48', '1.00', '1105.000000', '4.00', 'I', 5, '2022-11-28 17:40:24'),
(002, 84111506, 'Servicios de facturación', 'Pago', 02, 'ACT', '1.00', '0.000000', '4.00', 'P', 5, '2022-12-02 05:09:34');

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `clavesfacturacion`
--
ALTER TABLE `clavesfacturacion`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `clavesfacturacion`
--
ALTER TABLE `clavesfacturacion`
  MODIFY `id` int(3) UNSIGNED ZEROFILL NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
