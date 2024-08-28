-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 27-08-2024 a las 20:02:11
-- Versión del servidor: 10.4.32-MariaDB
-- Versión de PHP: 8.0.30

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `ticket_system`
--

DELIMITER $$
--
-- Procedimientos
--
CREATE DEFINER=`root`@`localhost` PROCEDURE `AsignarTicket` (IN `ticketId` INT, IN `departamentoId` INT)   BEGIN
    UPDATE tickets
    SET id_departamento = departamentoId
    WHERE id = ticketId;
END$$

DELIMITER ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `colonias`
--

CREATE TABLE `colonias` (
  `id_colonia` int(11) NOT NULL,
  `nombre_colonia` varchar(255) NOT NULL,
  `sector` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `colonias`
--

INSERT INTO `colonias` (`id_colonia`, `nombre_colonia`, `sector`) VALUES
(1, '1o. DE MAYO, COL.', NULL),
(2, '78 - 80, FRACC.', NULL),
(3, 'AGAPITO BARRERA, COL.', NULL),
(4, 'ÁLAMO, FRACC.', NULL),
(5, 'ANZALDÚA, FRACC.', NULL),
(6, 'ARNULFO MARTÍNEZ, COL.', NULL),
(7, 'AZTECA, FRACC.', NULL),
(8, 'BENITO JUÁREZ, COL.', NULL),
(9, 'BENITO JUÁREZ, COL. AMPL.', NULL),
(10, 'BRISAS DEL CAMPO 1, FRACC.', NULL),
(11, 'BRISAS DEL CAMPO 2, FRACC.', NULL),
(12, 'BRISAS DEL NORTE, COL.', NULL),
(13, 'BUGAMBILIAS, FRACC.', NULL),
(14, 'CAMPESTRE, COL.', NULL),
(15, 'CAMPESTRE DEL RÍO, COL.', NULL),
(16, 'CELANESE, COL.', NULL),
(17, 'CONALEP, COL.', NULL),
(18, 'CONALEP, COL. AMPL.', NULL),
(19, 'CONDESA, COL.', NULL),
(20, 'CONQUISTADORES, INF.', NULL),
(21, 'CONSTITUCIÓN, COL.', NULL),
(22, 'CRUZ DEL FRANCO, COL.', NULL),
(23, 'CUAUHTÉMOC, COL.', NULL),
(24, 'DEL BOSQUE, COL.', NULL),
(25, 'DEL BOSQUE, FRACC.', NULL),
(26, 'DEL CARMEN, COL.', NULL),
(27, 'DEL MAESTRO, COL.', NULL),
(28, 'DEL RÍO, FRACC.', NULL),
(29, 'DEL VALLE, COL.', NULL),
(30, 'DEL VALLE, FRACC.', NULL),
(31, 'DURANGO, COL.', NULL),
(32, 'EMILIO MARTÍNEZ MANATOU, COL.', NULL),
(33, 'EMILIO PORTES GIL, COL.', NULL),
(34, 'ESPERANZA, COL.', NULL),
(35, 'ESTANISLAO GARCÍA, COL.', NULL),
(36, 'ESTELITA ANZALDÚA, COL.', NULL),
(37, 'ESTERO, COL.', NULL),
(38, 'ESTERO, COL. AMPL.', NULL),
(39, 'FERROCARRIL 1, COL.', NULL),
(40, 'FERROCARRIL 2, COL.', NULL),
(41, 'FERROCARRIL 3, COL.', NULL),
(42, 'FERROCARRIL 4, COL.', NULL),
(43, 'FERROCARRIL CENTRO, COL.', NULL),
(44, 'FIDEL VELÁZQUEZ, INF.', NULL),
(45, 'FOVISSSTE, FRACC.', NULL),
(46, 'FRANCISCO I. MADERO, COL.', NULL),
(47, 'FUNDADORES, COL.', NULL),
(48, 'GRACIANO SÁNCHEZ, COL.', NULL),
(49, 'GUERRERO, COL.', NULL),
(50, 'HACIENDA LAS BRISAS 1, FRACC.', NULL),
(51, 'HACIENDA LAS BRISAS 2, FRACC.', NULL),
(52, 'HACIENDA LAS BRISAS 3, FRACC.', NULL),
(53, 'HIJOS DE EJIDATARIOS 1o. DE MAYO, COL.', NULL),
(54, 'HIJOS DE EJIDATARIOS, COL.', NULL),
(55, 'HIJOS DE EJIDATARIOS, COL. AMPL.', NULL),
(56, 'INDEPENDENCIA, COL.', NULL),
(57, 'INTEGRACIÓN FAMILIAR, COL.', NULL),
(58, 'INVASIÓN 2000 1, COL.', NULL),
(59, 'INVASIÓN 2000 2, COL.', NULL),
(60, 'INVASIÓN 2000 3, COL.', NULL),
(61, 'JUAN BÁEZ GUERRA, COL.', NULL),
(62, 'LA PAZ, COL.', NULL),
(63, 'LA PAZ, INF.', NULL),
(64, 'LA SAUTEÑA, COL.', NULL),
(65, 'LA SAUTEÑA, INF.', NULL),
(66, 'LAS AMÉRICAS, COL.', NULL),
(67, 'LAS CUMBRES, COL.', NULL),
(68, 'LAS FLORES, INF.', NULL),
(69, 'LAS MARGARITAS, FRACC.', NULL),
(70, 'LAS TORRES, FRACC.', NULL),
(71, 'LÁZARO CÁRDENAS, FRACC.', NULL),
(72, 'LAS LOMAS, COL.', NULL),
(73, 'LOS PINOS, COL.', NULL),
(74, 'LOS PORTALES, FRACC.', NULL),
(75, 'LUIS DONALDO COLOSIO, COL.', NULL),
(76, 'LUIS ECHEVERRÍA, COL.', NULL),
(77, 'MANUEL CAVAZOS LERMA, COL.', NULL),
(78, 'MANUEL RAMÍREZ, COL.', NULL),
(79, 'MÉXICO, COL.', NULL),
(80, 'MÉXICO ASENTAMIENTOS HUMANOS, COL.', NULL),
(81, 'MIGUEL HIDALGO, COL.', NULL),
(82, 'MIGUEL HIDALGO, COL. AMPL.', NULL),
(83, 'MISIONES DEL PUENTE ANZALDÚA, FRACC.', NULL),
(84, 'MONTERREAL, COL.', NULL),
(85, 'MORELOS, COL.', NULL),
(86, 'NIÑOS HÉROES, COL.', NULL),
(87, 'NOÉ GARZA MARTÍNEZ, COL.', NULL),
(88, 'NUEVO AMANECER, COL.', NULL),
(89, 'NUEVO LEÓN, COL.', NULL),
(90, 'NUEVO LEÓN, COL. AMPL.', NULL),
(91, 'OCTAVIO SILVA, COL.', NULL),
(92, 'OCTAVIO SILVA, COL. AMPL.', NULL),
(93, 'PARAÍSO, COL.', NULL),
(94, 'PARAÍSO NORTE, COL.', NULL),
(95, 'PASEO DEL VALLE, FRACC.', NULL),
(96, 'POPULAR, COL.', NULL),
(97, 'POPULAR BUENA VISTA, FRACC.', NULL),
(98, 'POPULAR DEL NORTE, COL.', NULL),
(99, 'POPULAR LOMA LINDA, FRACC.', NULL),
(100, 'POPULAR LOMAS DEL VALLE, FRACC.', NULL),
(101, 'POPULAR SAN FRANCISCO, FRACC.', NULL),
(102, 'POPULAR SAN GREGORIO, FRACC.', NULL),
(103, 'POPULAR SAN JUAN, FRACC.', NULL),
(104, 'POPULAR SAN JUAN, FRACC.', NULL),
(105, 'VILLA POPULAR HERMOSA, FRACC.', NULL),
(106, 'VILLAS POPULARES DE GARCÍA, FRACC.', NULL),
(107, 'VILLAS POPULARES DE SAN RAMÓN, FRACC.', NULL),
(108, 'PRADERAS DEL SOL, FRACC.', NULL),
(109, 'RIOBRAVENSE, COL.', NULL),
(110, 'RÍO BRAVO SECC. 1, FRACC.', NULL),
(111, 'RÍO BRAVO SECC. 2, FRACC.', NULL),
(112, 'RÍO BRAVO SECC. 3, FRACC.', NULL),
(113, 'RIVERAS DEL BRAVO, FRACC.', NULL),
(114, 'ROBERTO GUERRA, COL.', NULL),
(115, 'RUBÉN CAVAZOS, COL.', NULL),
(116, 'SANTO TOMÁS, COL.', NULL),
(117, 'SAN ANDRÉS, COL.', NULL),
(118, 'SAN ANDRÉS, COL. AMPL.', NULL),
(119, 'SAN AGUSTÍN, COL.', NULL),
(120, 'SAN FRANCISCO, COL.', NULL),
(121, 'SAN FRANCISCO, COL. AMPL.', NULL),
(122, 'SAN JUAN, COL.', NULL),
(123, 'SAN JUAN, COL. AMPL.', NULL),
(124, 'SAN LUIS, COL.', NULL),
(125, 'SAN MANUEL, COL.', NULL),
(126, 'SAN PEDRO, COL.', NULL),
(127, 'SANTA ANA, COL.', NULL),
(128, 'SANTA CRUZ, COL.', NULL),
(129, 'SANTA CRUZ, COL. AMPL.', NULL),
(130, 'SANTA MARÍA, COL.', NULL),
(131, 'SANTA MARÍA, COL. AMPL.', NULL),
(132, 'SANTA MONICA, COL.', NULL),
(133, 'SANTA MONICA, FRACC.', NULL),
(134, 'ZONA INDUSTRIAL', NULL);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `departamentos`
--

CREATE TABLE `departamentos` (
  `id` int(11) NOT NULL,
  `nombre` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `departamentos`
--

INSERT INTO `departamentos` (`id`, `nombre`) VALUES
(1, 'Educacion'),
(2, 'Obras');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `estatus_tickets`
--

CREATE TABLE `estatus_tickets` (
  `id_estatus` int(11) NOT NULL,
  `nombre_estatus` varchar(50) NOT NULL,
  `color_estatico` varchar(20) NOT NULL,
  `tiempo_maximo` int(11) DEFAULT NULL,
  `color_dinamico` varchar(20) DEFAULT NULL,
  `prioridad` int(11) DEFAULT 1,
  `descripcion` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `estatus_tickets`
--

INSERT INTO `estatus_tickets` (`id_estatus`, `nombre_estatus`, `color_estatico`, `tiempo_maximo`, `color_dinamico`, `prioridad`, `descripcion`) VALUES
(1, 'PENDIENTE_AUTORIZACION', 'YELLOW', 72, NULL, 2, 'Tickets que han sido creados y están pendientes de autorización'),
(2, 'RECHAZADO', 'RED', NULL, NULL, 4, 'Tickets que han sido rechazados o tienen más de 72 horas sin ser atendidos'),
(3, 'EN_PROCESO', 'BLUE', NULL, NULL, 3, 'Tickets que están en proceso de resolución'),
(4, 'CONCLUIDO', 'GREEN', NULL, NULL, 1, 'Tickets que ya han sido resueltos con éxito');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `peticiones`
--

CREATE TABLE `peticiones` (
  `id` int(11) NOT NULL,
  `tipo_peticion` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `peticiones`
--

INSERT INTO `peticiones` (`id`, `tipo_peticion`) VALUES
(1, 'BOYAS PARA TOPES'),
(2, 'RECOGER RAMAS'),
(3, 'REPARACION DE LUMINARIAS'),
(4, 'REPARACION DE SEMAFOROS'),
(5, 'SOLICITA TAMBOS DE BASURA (PROGRAMAS)');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tickets`
--

CREATE TABLE `tickets` (
  `id` int(11) NOT NULL,
  `nombre_solicitante` varchar(255) NOT NULL,
  `direccion_solicitante` varchar(255) NOT NULL,
  `tipo_telefono` enum('CASA','CELULAR','OTRO') DEFAULT NULL,
  `telefono` varchar(15) NOT NULL,
  `tipo_solicitante` enum('ASOCIACIONES','CIUDADANIA','EJIDOS','ESCUELAS','IGLESIAS','OTROS') DEFAULT NULL,
  `fecha_reporte` datetime DEFAULT current_timestamp(),
  `direccion_problematica` varchar(255) NOT NULL,
  `problematica` text NOT NULL,
  `colonia` varchar(255) DEFAULT NULL,
  `id_departamento` int(11) DEFAULT NULL,
  `estatus_ticket` varchar(50) DEFAULT NULL,
  `id_tipo_peticion` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `tickets`
--

INSERT INTO `tickets` (`id`, `nombre_solicitante`, `direccion_solicitante`, `tipo_telefono`, `telefono`, `tipo_solicitante`, `fecha_reporte`, `direccion_problematica`, `problematica`, `colonia`, `id_departamento`, `estatus_ticket`, `id_tipo_peticion`) VALUES
(37, 'escareño', 'Tomás Urbina', 'CASA', '8994463020', 'CIUDADANIA', '2024-08-22 16:34:59', 'Tomas Urbina', 'wewew', 'CONDESA, COL.', 1, NULL, 1),
(38, 'Karla', 'Tomas Urbina', 'CASA', '8994463020', 'EJIDOS', '2024-08-22 16:42:57', 'Tomas Urbina', 'culo', 'ARNULFO MARTÍNEZ, COL.', 1, NULL, 4),
(39, 'Jose', 'Tomas Urbina', 'CELULAR', '8994463020', 'ESCUELAS', '2024-08-22 16:49:40', 'Tomas Urbina', 'asdasd', 'SAN ANDRÉS, COL.', 2, NULL, 5),
(40, 'escareño', 'Tomas Urbina', 'CELULAR', '8994463020', 'CIUDADANIA', '2024-08-22 16:55:02', 'Tomas Urbina', 'lole', 'PRADERAS DEL SOL, FRACC.', 2, NULL, 1),
(41, 'escareño', 'Tomas Urbina', 'CASA', '8994463020', 'CIUDADANIA', '2024-08-22 17:01:26', 'Tomas Urbina', 'kug', 'HACIENDA LAS BRISAS 2, FRACC.', 2, NULL, 4);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `usuarios`
--

CREATE TABLE `usuarios` (
  `id_usuario` int(11) NOT NULL,
  `usuario` varchar(50) NOT NULL,
  `contrasena` varchar(255) NOT NULL,
  `rol` enum('ADMINISTRADOR','SECRETARIA','EMPLEADO') NOT NULL,
  `nombre` varchar(100) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  `telefono` varchar(15) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `usuarios`
--

INSERT INTO `usuarios` (`id_usuario`, `usuario`, `contrasena`, `rol`, `nombre`, `email`, `telefono`) VALUES
(1, '123', '123', 'ADMINISTRADOR', 'Jose', 'jescaren7', '8994463020');

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `estatus_tickets`
--
ALTER TABLE `estatus_tickets`
  ADD PRIMARY KEY (`id_estatus`),
  ADD UNIQUE KEY `nombre_estatus` (`nombre_estatus`);

--
-- Indices de la tabla `tickets`
--
ALTER TABLE `tickets`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_tickets_colonias` (`colonia`),
  ADD KEY `fk_departamento` (`id_departamento`),
  ADD KEY `fk_tipo_peticion` (`id_tipo_peticion`);

--
-- Indices de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  ADD PRIMARY KEY (`id_usuario`),
  ADD UNIQUE KEY `usuario` (`usuario`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
