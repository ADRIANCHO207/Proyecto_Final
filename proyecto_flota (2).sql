-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 12-06-2025 a las 15:36:21
-- Versión del servidor: 10.4.32-MariaDB
-- Versión de PHP: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `proyecto_flota`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `aseguradoras_soat`
--

CREATE TABLE `aseguradoras_soat` (
  `id_asegura` int(11) NOT NULL,
  `nombre` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `aseguradoras_soat`
--

INSERT INTO `aseguradoras_soat` (`id_asegura`, `nombre`) VALUES
(20, 'Aseguradora Solidaria de Colombia Ltda.'),
(21, 'AXA Colpatria Seguros S.A.'),
(22, 'La Equidad Seguros Generales'),
(23, 'Liberty Seguros S.A.'),
(24, 'La Previsora S.A. Compañía de Seguros'),
(25, 'Seguros Bolívar S.A.'),
(26, 'Seguros Mundial'),
(27, 'Seguros del Estado S.A.'),
(28, 'Seguros Generales Suramericana S.A.'),
(29, 'HDI Seguros Colombia S.A.'),
(30, 'Mapfre Seguros Generales de Colombia S.A.');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `categoria_licencia`
--

CREATE TABLE `categoria_licencia` (
  `id_categoria` int(11) NOT NULL,
  `nombre_categoria` text NOT NULL,
  `id_servicio` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `categoria_licencia`
--

INSERT INTO `categoria_licencia` (`id_categoria`, `nombre_categoria`, `id_servicio`) VALUES
(1, 'A1 - Motocicletas hasta 125cc', 1),
(2, 'A2 - Motocicletas mayores a 125cc y vehículos similares', 1),
(3, 'B1 - Automóviles, camperos, camionetas y vans de servicio particular', 1),
(4, 'B2 - Camiones rígidos, buses y busetas de servicio particular', 1),
(5, 'B3 - Vehículos articulados de servicio particular', 1),
(6, 'C1 - Automóviles, camperos, camionetas y vans de servicio público', 2),
(7, 'C2 - Camiones rígidos, buses y busetas de servicio público', 2),
(8, 'C3 - Vehículos articulados de servicio público', 2);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `centro_rtm`
--

CREATE TABLE `centro_rtm` (
  `id_centro` int(11) NOT NULL,
  `centro_revision` varchar(60) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `centro_rtm`
--

INSERT INTO `centro_rtm` (`id_centro`, `centro_revision`) VALUES
(1, 'DIAGNOSTICENTRO DEL NORTE MARIQUITA LTDA'),
(2, 'C.D.A. DIAGNOSTICAR'),
(3, 'IVESUR COLOMBIA - IBAGUE'),
(4, 'C.D.A. DIAGNOSTI-MOTOS ESPINAL'),
(5, 'CDA PEÑAS DEL RIO'),
(6, 'CDA MOTO CLUB AMBALA SAS'),
(7, 'CENTRO DE DIAGNOSTICO Y REVISIÓN DE VEHÍCULOS AUTOMOTORES “C'),
(8, 'CDA DEL TOLIMA'),
(9, 'CENTRO DE DIAGNOSTICO AUTOMOTRIZ TECNI MOTORS IBAGUE'),
(10, 'CENTRO DE DIAGNOSTICO AUTOMOTOR CDA BETANIA'),
(11, 'CENTRO DE DIAGNOSTICO AUTOMOTOR EL CARMEN S.A.S.'),
(12, 'CDA AUTOMOTOS DEL TOLIMA'),
(13, 'CDA MOTOS IBAGUE S.A.S'),
(14, 'CDA DEL CENTRO SAS'),
(15, 'CDA LA REVISION SAS'),
(16, 'CDA MOTOS DE LA SEXTA'),
(17, 'CENTRO DE DIAGNÓSTICO AUTOMOTRIZ DEL ESPINAL S.A.S.'),
(18, 'CDA REVIEXPRESS S.A.S'),
(19, 'CDA CEDITRANS S.A.'),
(20, 'CDA DIAGNOSTILISTO S.A.S.'),
(21, 'CDA DIAGNOSTI-CAR'),
(22, 'CDA TECNIMOTO AUTOS ESPINAL S.A.S');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `clasificacion_trabajo`
--

CREATE TABLE `clasificacion_trabajo` (
  `id` int(11) NOT NULL,
  `Trabajo` varchar(255) NOT NULL,
  `Precio` float NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `clasificacion_trabajo`
--

INSERT INTO `clasificacion_trabajo` (`id`, `Trabajo`, `Precio`) VALUES
(1, 'Aceite 5W-30 4L', 120000),
(2, 'Cambio de pastillas de freno', 150000),
(3, 'Alineación y balanceo', 80000),
(4, 'Revisión de suspensión', 100000),
(5, 'Cambio de batería', 200000);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `contacto`
--

CREATE TABLE `contacto` (
  `id_mensa` int(11) NOT NULL,
  `nom` text NOT NULL,
  `apellido` text NOT NULL,
  `email` varchar(100) NOT NULL,
  `mensaje` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `contacto`
--

INSERT INTO `contacto` (`id_mensa`, `nom`, `apellido`, `email`, `mensaje`) VALUES
(15, 'adrian', 'asasa', 'aasas@gmail.com', 'aaaaaaaaaa'),
(16, 'adrian', 'sadsa', 'sadsadas@gmail.com', 'asasasasasa'),
(24, 'Adrian', 'camargo', 'adriancamargo69@gmail.com', 'hola grupito'),
(25, 'Edwar', 'Gomez ', 'Edwar@gmail.com', 'Necesito una cuenta de administrador'),
(26, 'eder', 'moyano', 'edermoyano@gmail.com', 'necesito algo');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `detalles_mantenimiento_clasificacion`
--

CREATE TABLE `detalles_mantenimiento_clasificacion` (
  `Id_detalles` int(11) NOT NULL,
  `id_mantenimiento` int(11) NOT NULL,
  `id_trabajo` int(11) NOT NULL,
  `cantidad` float NOT NULL,
  `subtotal` float NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `estado_multa`
--

CREATE TABLE `estado_multa` (
  `id_estado_multa` varchar(50) NOT NULL,
  `descripcion` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `estado_soat`
--

CREATE TABLE `estado_soat` (
  `id_stado` int(11) NOT NULL,
  `soat_est` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `estado_soat`
--

INSERT INTO `estado_soat` (`id_stado`, `soat_est`) VALUES
(1, 'Vigente'),
(2, 'Vencido');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `estado_usuario`
--

CREATE TABLE `estado_usuario` (
  `id_estado` int(11) NOT NULL,
  `tipo_stade` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `estado_usuario`
--

INSERT INTO `estado_usuario` (`id_estado`, `tipo_stade`) VALUES
(1, 'Activo'),
(2, 'Inactivo');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `estado_vehiculo`
--

CREATE TABLE `estado_vehiculo` (
  `id_estado` varchar(50) NOT NULL,
  `estado` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `estado_vehiculo`
--

INSERT INTO `estado_vehiculo` (`id_estado`, `estado`) VALUES
('1', 'Activo'),
('10', 'En uso'),
('2', 'Inactivo'),
('3', 'Mantenimiento'),
('4', 'Revisión'),
('5', 'Retirado'),
('6', 'Accidentado'),
('7', 'Pendiente'),
('8', 'Disponible'),
('9', 'Bloqueado');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `licencias`
--

CREATE TABLE `licencias` (
  `id_documento` varchar(20) NOT NULL,
  `id_licencia` int(11) NOT NULL,
  `id_categoria` int(11) NOT NULL,
  `fecha_expedicion` date NOT NULL,
  `fecha_vencimiento` date NOT NULL,
  `id_servicio` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `licencias`
--

INSERT INTO `licencias` (`id_documento`, `id_licencia`, `id_categoria`, `fecha_expedicion`, `fecha_vencimiento`, `id_servicio`) VALUES
('1234567890', 1, 2, '2014-02-12', '2024-02-12', 1),
('1234567890', 2, 2, '2025-06-12', '2035-06-12', 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `llantas`
--

CREATE TABLE `llantas` (
  `id_llanta` int(11) NOT NULL,
  `placa` varchar(10) NOT NULL,
  `estado` varchar(50) NOT NULL,
  `ultimo_cambio` date DEFAULT NULL,
  `fecha_registro` timestamp NOT NULL DEFAULT current_timestamp(),
  `presion_llantas` decimal(4,1) DEFAULT NULL,
  `kilometraje_actual` int(11) DEFAULT NULL,
  `proximo_cambio_km` int(11) DEFAULT NULL,
  `proximo_cambio_fecha` date DEFAULT NULL,
  `notas` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `llantas`
--

INSERT INTO `llantas` (`id_llanta`, `placa`, `estado`, `ultimo_cambio`, `fecha_registro`, `presion_llantas`, `kilometraje_actual`, `proximo_cambio_km`, `proximo_cambio_fecha`, `notas`) VALUES
(1, 'GAS900', 'Malo', '2025-06-10', '2025-06-11 13:14:33', 30.0, 12345, 1233, '2025-07-08', 'cambios');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `mantenimiento`
--

CREATE TABLE `mantenimiento` (
  `id_mantenimiento` int(11) NOT NULL,
  `placa` varchar(10) NOT NULL,
  `id_tipo_mantenimiento` varchar(50) NOT NULL,
  `fecha_programada` date NOT NULL,
  `fecha_realizada` date DEFAULT NULL,
  `observaciones` text DEFAULT NULL,
  `kilometraje_actual` int(11) DEFAULT NULL,
  `proximo_cambio_km` int(11) DEFAULT NULL,
  `proximo_cambio_fecha` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `mantenimiento`
--

INSERT INTO `mantenimiento` (`id_mantenimiento`, `placa`, `id_tipo_mantenimiento`, `fecha_programada`, `fecha_realizada`, `observaciones`, `kilometraje_actual`, `proximo_cambio_km`, `proximo_cambio_fecha`) VALUES
(1, 'GAS900', '2', '2025-06-24', '2025-06-02', 'skdasjhdajsdkjasjd', 1234567, 1234567, '2025-06-29');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `marca`
--

CREATE TABLE `marca` (
  `id_marca` int(11) NOT NULL,
  `nombre_marca` varchar(50) NOT NULL,
  `id_tipo_vehiculo` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `marca`
--

INSERT INTO `marca` (`id_marca`, `nombre_marca`, `id_tipo_vehiculo`) VALUES
(1, 'Chevrolet', 1),
(2, 'Toyota', 1),
(3, 'Mazda', 1),
(4, 'Kia', 1),
(5, 'Renault', 1),
(6, 'Hyundai', 1),
(7, 'Volkswagen', 1),
(8, 'Nissan', 1),
(9, 'Ford', 1),
(10, 'Honda', 1),
(11, 'Peugeot', 1),
(12, 'Fiat', 1),
(13, 'Skoda', 1),
(14, 'Subaru', 1),
(15, 'Lada', 1),
(16, 'AKT', 2),
(17, 'Yamaha', 2),
(18, 'Suzuki', 2),
(19, 'Honda', 2),
(20, 'Bajaj', 2),
(21, 'KTM', 2),
(22, 'TVS', 2),
(23, 'Royal Enfield', 2),
(24, 'Hero', 2),
(25, 'Benelli', 2),
(26, 'Harley-Davidson', 2),
(27, 'Aprilia', 2),
(28, 'Ducati', 2),
(29, 'BMW Motorrad', 2),
(30, 'Toyota', 3),
(31, 'Mazda', 3),
(32, 'Chevrolet', 3),
(33, 'Nissan', 3),
(34, 'Ford', 3),
(35, 'Hyundai', 3),
(36, 'Mitsubishi', 3),
(37, 'Volkswagen', 3),
(38, 'Jeep', 3),
(39, 'Kia', 3),
(40, 'Freightliner', 4),
(41, 'Kenworth', 4),
(42, 'Volvo', 4),
(43, 'Hino', 4),
(44, 'International', 4),
(45, 'Isuzu', 4),
(46, 'Mack', 4),
(47, 'Scania', 4),
(48, 'Mercedes-Benz', 4),
(49, 'MAN', 4),
(50, 'Mercedes-Benz', 5),
(51, 'Chevrolet', 5),
(52, 'Volkswagen', 5),
(53, 'Hino', 5),
(54, 'Renault', 5),
(55, 'Hyundai', 5),
(56, 'Toyota', 5),
(57, 'Nissan', 5),
(58, 'Volvo', 5),
(59, 'Scania', 5),
(60, 'Ford', 6),
(61, 'Toyota', 6),
(62, 'Chevrolet', 6),
(63, 'Nissan', 6),
(64, 'Mitsubishi', 6),
(65, 'Jeep', 7),
(66, 'Ford', 7),
(67, 'Toyota', 7),
(68, 'Hyundai', 7),
(69, 'Mazda', 7),
(70, 'Chevrolet', 7),
(71, 'Kia', 8),
(72, 'Hyundai', 8),
(73, 'Nissan', 8),
(74, 'Subaru', 8),
(75, 'Toyota', 8),
(76, 'Mazda', 8),
(77, 'Chevrolet', 9),
(78, 'Renault', 9),
(79, 'Hyundai', 9),
(80, 'Ford', 9),
(81, 'Toyota', 9),
(82, 'Kenworth', 10),
(83, 'Volvo', 10),
(84, 'Freightliner', 10),
(85, 'Scania', 10),
(86, 'International', 10);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `multas`
--

CREATE TABLE `multas` (
  `id_multa` varchar(20) NOT NULL,
  `placa` varchar(10) NOT NULL,
  `documento_usuario` varchar(20) NOT NULL,
  `fecha_multa` date NOT NULL,
  `descripcion` varchar(255) NOT NULL,
  `monto` decimal(10,2) NOT NULL,
  `id_estado_multa` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `otros`
--

CREATE TABLE `otros` (
  `id_otros` int(11) NOT NULL,
  `id_mantenimiento` int(11) NOT NULL,
  `descripcion` varchar(255) NOT NULL,
  `Factura` varchar(500) NOT NULL,
  `Total` float NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `pico_placa`
--

CREATE TABLE `pico_placa` (
  `id_pico_placa` int(11) NOT NULL,
  `anio` int(11) NOT NULL,
  `semestre` enum('1','2') NOT NULL,
  `dia` enum('Lunes','Martes','Miercoles','Jueves','Viernes') NOT NULL,
  `digitos_restringidos` varchar(5) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `roles`
--

CREATE TABLE `roles` (
  `id_rol` int(11) NOT NULL,
  `tip_rol` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `roles`
--

INSERT INTO `roles` (`id_rol`, `tip_rol`) VALUES
(1, 'Administrador'),
(2, 'Usuario');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `servicios_licencias`
--

CREATE TABLE `servicios_licencias` (
  `id_servicio` int(11) NOT NULL,
  `nombre_servicios` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `servicios_licencias`
--

INSERT INTO `servicios_licencias` (`id_servicio`, `nombre_servicios`) VALUES
(1, 'Particular'),
(2, 'Publico');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `soat`
--

CREATE TABLE `soat` (
  `id_soat` int(11) NOT NULL,
  `id_placa` varchar(10) NOT NULL,
  `fecha_expedicion` date NOT NULL,
  `fecha_vencimiento` date NOT NULL,
  `id_aseguradora` int(11) NOT NULL,
  `id_estado` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `soat`
--

INSERT INTO `soat` (`id_soat`, `id_placa`, `fecha_expedicion`, `fecha_vencimiento`, `id_aseguradora`, `id_estado`) VALUES
(1, 'ASD231', '2025-06-03', '2026-06-03', 26, 1),
(2, 'ASD231', '2024-06-02', '2025-06-02', 23, 2),
(3, 'GZM57D', '2025-06-06', '2026-06-06', 26, 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tecnomecanica`
--

CREATE TABLE `tecnomecanica` (
  `id_rtm` int(11) NOT NULL,
  `id_placa` varchar(11) NOT NULL,
  `id_centro_revision` int(11) NOT NULL,
  `fecha_expedicion` date NOT NULL,
  `fecha_vencimiento` date NOT NULL,
  `id_estado` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `tecnomecanica`
--

INSERT INTO `tecnomecanica` (`id_rtm`, `id_placa`, `id_centro_revision`, `fecha_expedicion`, `fecha_vencimiento`, `id_estado`) VALUES
(1, 'ASD231', 2, '2025-06-05', '2026-06-05', 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tipo_documentacion`
--

CREATE TABLE `tipo_documentacion` (
  `id_tipo_documento` varchar(50) NOT NULL,
  `descripcion` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tipo_gasto`
--

CREATE TABLE `tipo_gasto` (
  `id_tipo_gasto` varchar(50) NOT NULL,
  `descripcion` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tipo_mantenimiento`
--

CREATE TABLE `tipo_mantenimiento` (
  `id_tipo_mantenimiento` varchar(50) NOT NULL,
  `descripcion` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `tipo_mantenimiento`
--

INSERT INTO `tipo_mantenimiento` (`id_tipo_mantenimiento`, `descripcion`) VALUES
('1', 'Preventivo'),
('2', 'Correctivo');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tipo_vehiculo`
--

CREATE TABLE `tipo_vehiculo` (
  `id_tipo_vehiculo` int(11) NOT NULL,
  `vehiculo` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `tipo_vehiculo`
--

INSERT INTO `tipo_vehiculo` (`id_tipo_vehiculo`, `vehiculo`) VALUES
(1, 'Automovil'),
(2, 'Motocicleta'),
(3, 'Camioneta'),
(4, 'Camión'),
(5, 'Bus'),
(6, 'Pickup'),
(7, 'SUV'),
(8, 'Crossover'),
(9, 'Van'),
(10, 'Tractomula');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `usuarios`
--

CREATE TABLE `usuarios` (
  `documento` varchar(20) NOT NULL,
  `nombre_completo` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(500) NOT NULL,
  `telefono` bigint(20) NOT NULL,
  `id_estado_usuario` int(11) NOT NULL,
  `id_rol` int(11) NOT NULL,
  `reset_token` varchar(255) DEFAULT NULL,
  `reset_expira` datetime DEFAULT NULL,
  `joined_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `foto_perfil` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `usuarios`
--

INSERT INTO `usuarios` (`documento`, `nombre_completo`, `email`, `password`, `telefono`, `id_estado_usuario`, `id_rol`, `reset_token`, `reset_expira`, `joined_at`, `foto_perfil`) VALUES
('1104941185', 'Adrian', 'adriancamargo69@gmail.com', '$2y$12$fbY79nD4.d2tcCf5F6Am5eZrWlQt7K6.ufpgFbxNEJ7TUY919CKHm', 3108571293, 1, 1, NULL, NULL, '2025-05-29 11:33:52', '/proyecto/roles/usuario/css/img/perfil.jpg'),
('1109491416', 'Edwar Farid Gomez Sanchez', 'edwarf_gomez@soy.sena.edu.co', '$2y$10$y8vEAE8cOVNPRiuYGLBJ2eMZHMJycTlGx3qOtbJIuZVSi1wqssePS', 12345, 1, 1, '408ce4fbd0c8dd11bf86754ccc9646cf510b4e0f492eb146907e6867c3fea5c85fafae32d991bbb11f80553309677ce7be9a', '2025-04-15 22:23:07', '2025-04-17 20:26:34', NULL),
('1234567890', 'Instructor cesar', 'instructor@gmail.com', '$2y$12$AlVGKX55fPkNLa7LbRzAjeaWFg2hUmVeoCf8T0FB2P6YoOAc79FEi', 3117829929, 1, 2, NULL, NULL, '2025-05-30 12:31:15', NULL),
('9876543210', 'carlos', 'Carlosgo1822@gmail.com', '$2y$10$FUKgva81QgWzPiqDtthX6uRfD7Fv9.wSjOar6lsHLc0HucyJjofQO', 3213213232, 1, 2, NULL, NULL, '2025-05-30 12:08:20', '/proyecto/roles/usuario/css/img/perfil.jpg');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `vehiculos`
--

CREATE TABLE `vehiculos` (
  `placa` varchar(10) NOT NULL,
  `tipo_vehiculo` int(11) NOT NULL,
  `Documento` varchar(20) NOT NULL,
  `id_marca` varchar(50) NOT NULL,
  `modelo` varchar(50) NOT NULL,
  `kilometraje_actual` bigint(20) NOT NULL,
  `id_estado` varchar(50) NOT NULL,
  `fecha_registro` date NOT NULL,
  `foto_vehiculo` varchar(255) DEFAULT NULL COMMENT 'Ruta de la imagen del vehículo'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `vehiculos`
--

INSERT INTO `vehiculos` (`placa`, `tipo_vehiculo`, `Documento`, `id_marca`, `modelo`, `kilometraje_actual`, `id_estado`, `fecha_registro`, `foto_vehiculo`) VALUES
('AAS232', 1, '1234567890', '2', '2021', 21233213, '10', '2025-06-11', '../vehiculos/listar/guardar_foto_vehiculo/sin_foto_carro.png'),
('AGS21G', 2, '1234567890', '16', '2020', 12345678, '10', '2025-06-11', '../vehiculos/listar/guardar_foto_vehiculo/sin_foto_carro.png'),
('AHS212', 1, '1104941185', '1', '2021', 1231232321, '10', '2025-05-29', '../vehiculos/listar/guardar_foto_vehiculo/vehiculo_683866ca34803.jpeg'),
('ASD213', 1, '1234567890', '3', '2022', 1234567, '10', '2025-06-11', '../vehiculos/listar/guardar_foto_vehiculo/sin_foto_carro.png'),
('ASD231', 1, '1234567890', '1', '2021', 100029, '10', '2025-06-01', '../vehiculos/listar/guardar_foto_vehiculo/sin_foto_carro.png'),
('ASD321', 1, '1104941185', '1', '2021', 102010, '10', '2025-05-30', '../vehiculos/listar/guardar_foto_vehiculo/sin_foto_carro.png'),
('DAS231', 8, '1234567890', '73', '2020', 1123456, '10', '2025-06-11', '../vehiculos/listar/guardar_foto_vehiculo/sin_foto_carro.png'),
('DHS121', 1, '1234567890', '2', '2020', 123456, '10', '2025-06-11', '../vehiculos/listar/guardar_foto_vehiculo/sin_foto_carro.png'),
('GAS900', 3, '1234567890', '36', '2025', 12345678, '10', '2025-06-11', '../vehiculos/listar/guardar_foto_vehiculo/sin_foto_carro.png'),
('GZM57D', 2, '1234567890', '17', '2022', 50000, '10', '2025-06-09', '../vehiculos/listar/guardar_foto_vehiculo/sin_foto_carro.png'),
('KSK234', 1, '9876543210', '4', '2021', 53672822, '1', '2025-05-30', '../vehiculos/listar/guardar_foto_vehiculo/vehiculo_68399d04bb05d.png');

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `aseguradoras_soat`
--
ALTER TABLE `aseguradoras_soat`
  ADD PRIMARY KEY (`id_asegura`);

--
-- Indices de la tabla `categoria_licencia`
--
ALTER TABLE `categoria_licencia`
  ADD PRIMARY KEY (`id_categoria`),
  ADD KEY `id_servicio` (`id_servicio`);

--
-- Indices de la tabla `centro_rtm`
--
ALTER TABLE `centro_rtm`
  ADD PRIMARY KEY (`id_centro`);

--
-- Indices de la tabla `clasificacion_trabajo`
--
ALTER TABLE `clasificacion_trabajo`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `contacto`
--
ALTER TABLE `contacto`
  ADD PRIMARY KEY (`id_mensa`);

--
-- Indices de la tabla `detalles_mantenimiento_clasificacion`
--
ALTER TABLE `detalles_mantenimiento_clasificacion`
  ADD PRIMARY KEY (`Id_detalles`),
  ADD KEY `id_mantenimiento` (`id_mantenimiento`),
  ADD KEY `id_trabajo` (`id_trabajo`);

--
-- Indices de la tabla `estado_multa`
--
ALTER TABLE `estado_multa`
  ADD PRIMARY KEY (`id_estado_multa`);

--
-- Indices de la tabla `estado_soat`
--
ALTER TABLE `estado_soat`
  ADD PRIMARY KEY (`id_stado`);

--
-- Indices de la tabla `estado_usuario`
--
ALTER TABLE `estado_usuario`
  ADD PRIMARY KEY (`id_estado`);

--
-- Indices de la tabla `estado_vehiculo`
--
ALTER TABLE `estado_vehiculo`
  ADD PRIMARY KEY (`id_estado`);

--
-- Indices de la tabla `licencias`
--
ALTER TABLE `licencias`
  ADD PRIMARY KEY (`id_licencia`),
  ADD KEY `id_documento` (`id_documento`),
  ADD KEY `id_categoria` (`id_categoria`),
  ADD KEY `id_servicio` (`id_servicio`);

--
-- Indices de la tabla `llantas`
--
ALTER TABLE `llantas`
  ADD PRIMARY KEY (`id_llanta`),
  ADD KEY `placa` (`placa`);

--
-- Indices de la tabla `mantenimiento`
--
ALTER TABLE `mantenimiento`
  ADD PRIMARY KEY (`id_mantenimiento`),
  ADD KEY `placa` (`placa`),
  ADD KEY `id_tipo_mantenimiento` (`id_tipo_mantenimiento`);

--
-- Indices de la tabla `marca`
--
ALTER TABLE `marca`
  ADD PRIMARY KEY (`id_marca`),
  ADD KEY `fk_marca_tipo` (`id_tipo_vehiculo`);

--
-- Indices de la tabla `multas`
--
ALTER TABLE `multas`
  ADD PRIMARY KEY (`id_multa`),
  ADD KEY `placa` (`placa`),
  ADD KEY `documento_usuario` (`documento_usuario`),
  ADD KEY `id_estado_multa` (`id_estado_multa`);

--
-- Indices de la tabla `otros`
--
ALTER TABLE `otros`
  ADD PRIMARY KEY (`id_otros`),
  ADD KEY `id_mantenimiento` (`id_mantenimiento`);

--
-- Indices de la tabla `pico_placa`
--
ALTER TABLE `pico_placa`
  ADD PRIMARY KEY (`id_pico_placa`);

--
-- Indices de la tabla `roles`
--
ALTER TABLE `roles`
  ADD PRIMARY KEY (`id_rol`);

--
-- Indices de la tabla `servicios_licencias`
--
ALTER TABLE `servicios_licencias`
  ADD PRIMARY KEY (`id_servicio`);

--
-- Indices de la tabla `soat`
--
ALTER TABLE `soat`
  ADD PRIMARY KEY (`id_soat`),
  ADD KEY `id_placa` (`id_placa`),
  ADD KEY `id_aseguradora` (`id_aseguradora`),
  ADD KEY `id-estado` (`id_estado`);

--
-- Indices de la tabla `tecnomecanica`
--
ALTER TABLE `tecnomecanica`
  ADD PRIMARY KEY (`id_rtm`),
  ADD KEY `id_centro_revision` (`id_centro_revision`),
  ADD KEY `id_estado` (`id_estado`),
  ADD KEY `id_placa` (`id_placa`);

--
-- Indices de la tabla `tipo_documentacion`
--
ALTER TABLE `tipo_documentacion`
  ADD PRIMARY KEY (`id_tipo_documento`);

--
-- Indices de la tabla `tipo_gasto`
--
ALTER TABLE `tipo_gasto`
  ADD PRIMARY KEY (`id_tipo_gasto`);

--
-- Indices de la tabla `tipo_mantenimiento`
--
ALTER TABLE `tipo_mantenimiento`
  ADD PRIMARY KEY (`id_tipo_mantenimiento`);

--
-- Indices de la tabla `tipo_vehiculo`
--
ALTER TABLE `tipo_vehiculo`
  ADD PRIMARY KEY (`id_tipo_vehiculo`);

--
-- Indices de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  ADD PRIMARY KEY (`documento`),
  ADD KEY `id_estado_usuario` (`id_estado_usuario`),
  ADD KEY `id_rol` (`id_rol`);

--
-- Indices de la tabla `vehiculos`
--
ALTER TABLE `vehiculos`
  ADD PRIMARY KEY (`placa`),
  ADD KEY `id_marca` (`id_marca`),
  ADD KEY `id_estado` (`id_estado`),
  ADD KEY `Documento` (`Documento`),
  ADD KEY `tipo_vehiculo` (`tipo_vehiculo`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `aseguradoras_soat`
--
ALTER TABLE `aseguradoras_soat`
  MODIFY `id_asegura` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=31;

--
-- AUTO_INCREMENT de la tabla `categoria_licencia`
--
ALTER TABLE `categoria_licencia`
  MODIFY `id_categoria` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT de la tabla `centro_rtm`
--
ALTER TABLE `centro_rtm`
  MODIFY `id_centro` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=23;

--
-- AUTO_INCREMENT de la tabla `clasificacion_trabajo`
--
ALTER TABLE `clasificacion_trabajo`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT de la tabla `contacto`
--
ALTER TABLE `contacto`
  MODIFY `id_mensa` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=27;

--
-- AUTO_INCREMENT de la tabla `detalles_mantenimiento_clasificacion`
--
ALTER TABLE `detalles_mantenimiento_clasificacion`
  MODIFY `Id_detalles` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `estado_soat`
--
ALTER TABLE `estado_soat`
  MODIFY `id_stado` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT de la tabla `estado_usuario`
--
ALTER TABLE `estado_usuario`
  MODIFY `id_estado` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT de la tabla `licencias`
--
ALTER TABLE `licencias`
  MODIFY `id_licencia` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT de la tabla `llantas`
--
ALTER TABLE `llantas`
  MODIFY `id_llanta` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT de la tabla `mantenimiento`
--
ALTER TABLE `mantenimiento`
  MODIFY `id_mantenimiento` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT de la tabla `marca`
--
ALTER TABLE `marca`
  MODIFY `id_marca` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=87;

--
-- AUTO_INCREMENT de la tabla `otros`
--
ALTER TABLE `otros`
  MODIFY `id_otros` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `pico_placa`
--
ALTER TABLE `pico_placa`
  MODIFY `id_pico_placa` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT de la tabla `roles`
--
ALTER TABLE `roles`
  MODIFY `id_rol` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT de la tabla `servicios_licencias`
--
ALTER TABLE `servicios_licencias`
  MODIFY `id_servicio` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT de la tabla `soat`
--
ALTER TABLE `soat`
  MODIFY `id_soat` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT de la tabla `tecnomecanica`
--
ALTER TABLE `tecnomecanica`
  MODIFY `id_rtm` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `categoria_licencia`
--
ALTER TABLE `categoria_licencia`
  ADD CONSTRAINT `categoria_licencia_ibfk_1` FOREIGN KEY (`id_servicio`) REFERENCES `servicios_licencias` (`id_servicio`);

--
-- Filtros para la tabla `detalles_mantenimiento_clasificacion`
--
ALTER TABLE `detalles_mantenimiento_clasificacion`
  ADD CONSTRAINT `detalles_mantenimiento_clasificacion_ibfk_1` FOREIGN KEY (`id_mantenimiento`) REFERENCES `mantenimiento` (`id_mantenimiento`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `detalles_mantenimiento_clasificacion_ibfk_2` FOREIGN KEY (`id_trabajo`) REFERENCES `clasificacion_trabajo` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Filtros para la tabla `licencias`
--
ALTER TABLE `licencias`
  ADD CONSTRAINT `licencias_ibfk_1` FOREIGN KEY (`id_documento`) REFERENCES `usuarios` (`documento`),
  ADD CONSTRAINT `licencias_ibfk_2` FOREIGN KEY (`id_categoria`) REFERENCES `categoria_licencia` (`id_categoria`),
  ADD CONSTRAINT `licencias_ibfk_3` FOREIGN KEY (`id_servicio`) REFERENCES `servicios_licencias` (`id_servicio`);

--
-- Filtros para la tabla `llantas`
--
ALTER TABLE `llantas`
  ADD CONSTRAINT `llantas_ibfk_1` FOREIGN KEY (`placa`) REFERENCES `vehiculos` (`placa`) ON DELETE CASCADE;

--
-- Filtros para la tabla `mantenimiento`
--
ALTER TABLE `mantenimiento`
  ADD CONSTRAINT `mantenimiento_ibfk_1` FOREIGN KEY (`placa`) REFERENCES `vehiculos` (`placa`),
  ADD CONSTRAINT `mantenimiento_ibfk_2` FOREIGN KEY (`id_tipo_mantenimiento`) REFERENCES `tipo_mantenimiento` (`id_tipo_mantenimiento`);

--
-- Filtros para la tabla `marca`
--
ALTER TABLE `marca`
  ADD CONSTRAINT `fk_marca_tipo` FOREIGN KEY (`id_tipo_vehiculo`) REFERENCES `tipo_vehiculo` (`id_tipo_vehiculo`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Filtros para la tabla `multas`
--
ALTER TABLE `multas`
  ADD CONSTRAINT `multas_ibfk_1` FOREIGN KEY (`placa`) REFERENCES `vehiculos` (`placa`),
  ADD CONSTRAINT `multas_ibfk_2` FOREIGN KEY (`documento_usuario`) REFERENCES `usuarios` (`documento`),
  ADD CONSTRAINT `multas_ibfk_3` FOREIGN KEY (`id_estado_multa`) REFERENCES `estado_multa` (`id_estado_multa`);

--
-- Filtros para la tabla `otros`
--
ALTER TABLE `otros`
  ADD CONSTRAINT `otros_ibfk_1` FOREIGN KEY (`id_mantenimiento`) REFERENCES `mantenimiento` (`id_mantenimiento`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Filtros para la tabla `soat`
--
ALTER TABLE `soat`
  ADD CONSTRAINT `soat_ibfk_1` FOREIGN KEY (`id_placa`) REFERENCES `vehiculos` (`placa`),
  ADD CONSTRAINT `soat_ibfk_2` FOREIGN KEY (`id_aseguradora`) REFERENCES `aseguradoras_soat` (`id_asegura`),
  ADD CONSTRAINT `soat_ibfk_3` FOREIGN KEY (`id_estado`) REFERENCES `estado_soat` (`id_stado`);

--
-- Filtros para la tabla `tecnomecanica`
--
ALTER TABLE `tecnomecanica`
  ADD CONSTRAINT `tecnomecanica_ibfk_1` FOREIGN KEY (`id_centro_revision`) REFERENCES `centro_rtm` (`id_centro`),
  ADD CONSTRAINT `tecnomecanica_ibfk_2` FOREIGN KEY (`id_estado`) REFERENCES `estado_soat` (`id_stado`),
  ADD CONSTRAINT `tecnomecanica_ibfk_3` FOREIGN KEY (`id_placa`) REFERENCES `vehiculos` (`placa`);

--
-- Filtros para la tabla `usuarios`
--
ALTER TABLE `usuarios`
  ADD CONSTRAINT `usuarios_ibfk_1` FOREIGN KEY (`id_estado_usuario`) REFERENCES `estado_usuario` (`id_estado`),
  ADD CONSTRAINT `usuarios_ibfk_2` FOREIGN KEY (`id_rol`) REFERENCES `roles` (`id_rol`);

--
-- Filtros para la tabla `vehiculos`
--
ALTER TABLE `vehiculos`
  ADD CONSTRAINT `fk_vehiculos_documento` FOREIGN KEY (`Documento`) REFERENCES `usuarios` (`documento`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `vehiculos_ibfk_3` FOREIGN KEY (`id_estado`) REFERENCES `estado_vehiculo` (`id_estado`),
  ADD CONSTRAINT `vehiculos_ibfk_4` FOREIGN KEY (`tipo_vehiculo`) REFERENCES `tipo_vehiculo` (`id_tipo_vehiculo`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
