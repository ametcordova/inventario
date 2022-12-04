-- phpMyAdmin SQL Dump
-- version 5.0.2
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 02-12-2022 a las 05:52:15
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
-- Estructura de tabla para la tabla `c_impuesto`
--

CREATE TABLE `c_impuesto` (
  `id` int(3) UNSIGNED ZEROFILL NOT NULL,
  `descripcion` varchar(5) COLLATE utf8_spanish_ci NOT NULL,
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

INSERT INTO `c_impuesto` (`id`, `descripcion`, `retencion`, `traslado`, `localofederal`, `fechainiciovigencia`, `fechafinvigencia`, `version`, `ultusuario`, `at_update`) VALUES
(001, 'ISR', 'Si', 'No', 'Federal', '2022-01-01', NULL, '4.00', 5, '2022-12-02 04:50:27'),
(002, 'IVA', 'Si', 'Si', 'Federal', '2022-01-01', NULL, '4.00', 5, '2022-12-02 04:50:27'),
(003, 'IEPS', 'Si', 'Si', 'Federal', '2022-01-01', NULL, '4.00', 5, '2022-12-02 04:51:03');

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `c_impuesto`
--
ALTER TABLE `c_impuesto`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `c_impuesto`
--
ALTER TABLE `c_impuesto`
  MODIFY `id` int(3) UNSIGNED ZEROFILL NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
