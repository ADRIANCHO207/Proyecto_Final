-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 07-05-2025 a las 22:58:25
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
-- Estructura de tabla para la tabla `clasificacion_trabajo`
--

CREATE TABLE `clasificacion_trabajo` (
  `id` int(11) NOT NULL,
  `Trabajo` varchar(255) NOT NULL,
  `Precio` float NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

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
(17, '', '', '', ''),
(18, '', '', '', ''),
(19, '', '', '', ''),
(20, '', '', '', ''),
(21, '', '', '', ''),
(22, '', '', '', ''),
(23, '', '', '', '');

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
-- Estructura de tabla para la tabla `documentacion`
--

CREATE TABLE `documentacion` (
  `id_documento` varchar(20) NOT NULL,
  `placa` varchar(10) NOT NULL,
  `id_tipo_documento` varchar(50) NOT NULL,
  `Empresa_Tramtie` int(11) NOT NULL,
  `Fecha_expedicion` timestamp NOT NULL DEFAULT current_timestamp(),
  `fecha_inicio` date NOT NULL,
  `fecha_vencimiento` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `empresa_tramite`
--

CREATE TABLE `empresa_tramite` (
  `id` int(11) NOT NULL,
  `Empresa` varchar(60) NOT NULL
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
-- Estructura de tabla para la tabla `llantas`
--

CREATE TABLE `llantas` (
  `id_llanta` int(11) NOT NULL,
  `placa` varchar(10) NOT NULL,
  `estado` varchar(50) NOT NULL,
  `ultimo_cambio` date DEFAULT NULL,
  `fecha_registro` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

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
  `observaciones` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

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
  `placa` varchar(10) NOT NULL,
  `dia` varchar(20) NOT NULL,
  `fecha_registro` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `pico_placa`
--

INSERT INTO `pico_placa` (`id_pico_placa`, `placa`, `dia`, `fecha_registro`) VALUES
(1, 'gfg-545', 'Martes', '2025-05-04 07:12:44'),
(2, 'fsd-656', 'Martes', '2025-05-05 13:06:01');

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
(1, 'Automóvil'),
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
('1104941185', 'Adrian', 'adriancamargo69@gmail.com', '$2y$12$fbY79nD4.d2tcCf5F6Am5eZrWlQt7K6.ufpgFbxNEJ7TUY919CKHm', 3108571293, 1, 2, NULL, NULL, '2025-05-07 16:21:34', 'css/img/1104941185_1746634894.png'),
('1109491416', 'Edwar Farid Gomez Sanchez', 'edwarf_gomez@soy.sena.edu.co', '$2y$10$y8vEAE8cOVNPRiuYGLBJ2eMZHMJycTlGx3qOtbJIuZVSi1wqssePS', 12345, 1, 1, '408ce4fbd0c8dd11bf86754ccc9646cf510b4e0f492eb146907e6867c3fea5c85fafae32d991bbb11f80553309677ce7be9a', '2025-04-15 22:23:07', '2025-04-17 20:26:34', NULL),
('1110174520', 'carlos', 'carlosuj@gmail.co', '$2y$10$Byljp5XLa3yu081zF1rkD.rhuweb.oDAQOptYH3Nh6GTLi7IKtDTW', 3138102150, 1, 2, NULL, NULL, '2025-04-15 19:07:37', NULL),
('111111111', 'cesar', 'cachuchogmasisio@gmail.com', '$2y$10$XJnaC0lGNARu/Ol22k98EOcBFSc1460gjr2016YQePfr.JTBkAiJi', 654564645645, 1, 2, NULL, NULL, '2025-05-02 04:27:08', NULL),
('122313', 'Laura', 'asdassadasd@gmial.com', '$2y$10$UphgQVDrq0uwjJh128EGluTb.VfywB8fkW6aslPYukYlZqObe3DD2', 13211214, 1, 2, NULL, NULL, '2025-04-15 19:07:37', NULL),
('1234', 'Admin', 'ALDJAKJ@GMIAFLAMF.COM', '$2y$10$M3JL1HhDiO.yofUClxPj1u4Sf9J.y1XgjHi6jww5H.4mPo/bKxpP.', 123, 2, 2, NULL, NULL, '2025-04-17 20:22:27', NULL),
('1234567890', 'Edwar Farid Gomez Sanchez admin', 'edwardfaridg@Gmail.com', '$2y$12$v.6mbb8dLOze.xpSwqp6ZOzI9mnQH8pRkQfMF88gh/Nv.bddfxVoO', 3221544673, 1, 2, 'e9e9f62086823c4ebd032ef096a4e2e03cf8109109997dfbb432959aeb64f8affff867c280a7f6ff0c03230faf551f541765', '2025-04-21 07:37:10', '2025-04-21 11:37:10', NULL),
('1234567899', 'edwar', 'edwar@gnail.com', '$2y$10$KwnHWrlGCvZFn5.DQnJZf.ASDRD8mKbsl6hZuH42XL59bw4lovT/2', 12345, 1, 2, NULL, NULL, '2025-05-07 15:08:24', 'css/img/1234567899_1746630504.jpg'),
('9876543210', 'carlos', 'Carlosgo1822@gmail.com', '$2y$10$FUKgva81QgWzPiqDtthX6uRfD7Fv9.wSjOar6lsHLc0HucyJjofQO', 3213213232, 1, 2, NULL, NULL, '2025-05-07 11:39:08', 'css/img/9876543210_1746617948.png'),
('9999999999', 'cesar', 'djsdfksdfk@gmail.com', '$2y$10$XfNcq9UIRLruvtd5aSht5.A2OrbC862Igvkvz91C7QQMYdq4HRxJe', 65465564, 1, 1, NULL, NULL, '2025-05-02 04:28:45', NULL);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `vehiculos`
--

CREATE TABLE `vehiculos` (
  `placa` varchar(10) NOT NULL,
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

INSERT INTO `vehiculos` (`placa`, `Documento`, `id_marca`, `modelo`, `kilometraje_actual`, `id_estado`, `fecha_registro`, `foto_vehiculo`) VALUES
('123-abc', '9876543210', '17', 'mt09', 4384848, '10', '2025-05-06', 'uploads/vehiculos/vehiculo_6818dea97cef5.jpg'),
('AGV099', '9876543210', '32', 'GVCFGFF', 56454545, '5', '2025-05-08', 'vehiculos/listar/guardar_foto_vehiculo/vehiculo_681b527fe9eab.png'),
('ayudame', '9876543210', '42', 'ffgdfg', 3454353, '4', '2025-05-07', 'vehiculos/listar/guardar_foto_vehiculo/sin_foto_carro.png'),
('car-los', '9876543210', '24', 'aksjask', 382938239, '10', '2025-05-05', 'uploads/vehiculos/vehiculo_6818e056de57b.jpg'),
('carlos-elm', '9876543210', '25', '5455', 434344, '5', '2025-05-05', 'vehiculos/guardar_foto_vehiculovehiculo_6818e7a193976.jpg'),
('cdm-656', '9876543210', '18', 'y5', 4545, '6', '2025-05-23', NULL),
('esta-esta', '9876543210', '32', 'dsdsdsd', 344344, '8', '2025-05-06', 'vehiculos/listar/guardar_foto_vehiculo/vehiculo_6818eb2b70e92.jpg'),
('fsd-656', '9876543210', '3', 'jmnhbgb', 6543, '10', '2025-05-04', NULL),
('gfg-545', '9876543210', '16', 'y5', 900000, '10', '2025-05-14', 'vehiculos/listar/guardar_foto_vehiculo/vehiculo_681b78c247e87.jpg'),
('GZM57D', '1104941185', '32', 'jsahasgd', 1123131, '10', '2025-05-07', 'vehiculos/listar/guardar_foto_vehiculo/sin_foto_carro.png'),
('hola-mundo', '9876543210', '55', '4893344', 323233, '7', '2025-05-06', 'vehiculos/listar/guardar_foto_vehiculo/vehiculo_6818e8d007133.jpg'),
('iba-gue', '9876543210', '53', 'turbo', 3493049, '10', '2025-05-05', 'uploads/vehiculos/vehiculo_6818df8d5a267.jpg'),
('kjj-595', '9876543210', '31', 'jfdjf', 504594, '10', '2025-05-07', 'uploads/vehiculos/vehiculo_6818cd5b0d5db.jpg'),
('lamborguin', '9876543210', '31', 'jasskjas', 8932938, '10', '2025-05-05', 'vehiculos/listar/guardar_foto_vehiculo/vehiculo_681b41495100b.jpg'),
('ljk-654', '9876543210', '79', 'hjy', 3453434, '2', '2025-05-06', 'uploads/vehiculos/vehiculo_6818ce0aa7f2f.png'),
('lkj-645', '9876543210', '43', 'ghda', 742892, '4', '2025-05-06', 'uploads/vehiculos/vehiculo_6818cf8be6a8d.png'),
('mam-mit', '9876543210', '3', 'dsdsd', 233233, '4', '2025-05-06', 'uploads/vehiculos/vehiculo_6818e21958e41.jpg'),
('mar-356', '9876543210', '17', 'mt09', 98000, '10', '2025-05-11', NULL),
('me-quiero', '9876543210', '25', 'matar', 3223832, '5', '2025-05-07', 'vehiculos/listar/guardar_foto_vehiculo/vehiculo_6818e84ba07df.jpg'),
('messi-mess', '9876543210', '41', 'sdsdsd', 232323, '2', '2025-05-07', 'listar/guardar_foto_vehiculo/sin_foto_carro.png'),
('product ow', '9876543210', '32', 'sdasdsda', 3232343, '5', '2025-05-07', 'listar/guardar_foto_vehiculo/sin_foto_carro.png'),
('sds-dfd', '9876543210', '31', 'btrrr', 23234, '6', '2025-05-05', NULL),
('sin-fondoo', '9876543210', '19', 'dsdfs', 32323, '2', '2025-05-07', NULL),
('super-gave', '9876543210', '31', '43434', 43434, '7', '2025-05-05', 'guardar_foto_vehiculo/sin_foto_carro.png'),
('tracto', '9876543210', '84', 'asasadsd', 3232332, '3', '2025-05-07', 'guardar_foto_vehiculo/sin_foto_carro.png'),
('zxzxzxzxzx', '9876543210', '43', '3434', 23232, '7', '2025-05-05', 'uploads/vehiculos/vehiculo_6818e4d17744d.jpg');

--
-- Índices para tablas volcadas
--

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
-- Indices de la tabla `documentacion`
--
ALTER TABLE `documentacion`
  ADD PRIMARY KEY (`id_documento`),
  ADD KEY `placa` (`placa`),
  ADD KEY `id_tipo_documento` (`id_tipo_documento`),
  ADD KEY `Empresa_Tramtie` (`Empresa_Tramtie`);

--
-- Indices de la tabla `empresa_tramite`
--
ALTER TABLE `empresa_tramite`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `estado_multa`
--
ALTER TABLE `estado_multa`
  ADD PRIMARY KEY (`id_estado_multa`);

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
  ADD PRIMARY KEY (`id_pico_placa`),
  ADD KEY `placa` (`placa`);

--
-- Indices de la tabla `roles`
--
ALTER TABLE `roles`
  ADD PRIMARY KEY (`id_rol`);

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
  ADD KEY `Documento` (`Documento`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `clasificacion_trabajo`
--
ALTER TABLE `clasificacion_trabajo`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `contacto`
--
ALTER TABLE `contacto`
  MODIFY `id_mensa` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=24;

--
-- AUTO_INCREMENT de la tabla `detalles_mantenimiento_clasificacion`
--
ALTER TABLE `detalles_mantenimiento_clasificacion`
  MODIFY `Id_detalles` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `empresa_tramite`
--
ALTER TABLE `empresa_tramite`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `estado_usuario`
--
ALTER TABLE `estado_usuario`
  MODIFY `id_estado` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT de la tabla `llantas`
--
ALTER TABLE `llantas`
  MODIFY `id_llanta` int(11) NOT NULL AUTO_INCREMENT;

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
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `detalles_mantenimiento_clasificacion`
--
ALTER TABLE `detalles_mantenimiento_clasificacion`
  ADD CONSTRAINT `detalles_mantenimiento_clasificacion_ibfk_1` FOREIGN KEY (`id_mantenimiento`) REFERENCES `mantenimiento` (`id_mantenimiento`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `detalles_mantenimiento_clasificacion_ibfk_2` FOREIGN KEY (`id_trabajo`) REFERENCES `clasificacion_trabajo` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Filtros para la tabla `documentacion`
--
ALTER TABLE `documentacion`
  ADD CONSTRAINT `documentacion_ibfk_1` FOREIGN KEY (`placa`) REFERENCES `vehiculos` (`placa`),
  ADD CONSTRAINT `documentacion_ibfk_2` FOREIGN KEY (`id_tipo_documento`) REFERENCES `tipo_documentacion` (`id_tipo_documento`),
  ADD CONSTRAINT `documentacion_ibfk_3` FOREIGN KEY (`Empresa_Tramtie`) REFERENCES `empresa_tramite` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

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
-- Filtros para la tabla `pico_placa`
--
ALTER TABLE `pico_placa`
  ADD CONSTRAINT `pico_placa_ibfk_1` FOREIGN KEY (`placa`) REFERENCES `vehiculos` (`placa`) ON DELETE CASCADE;

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
  ADD CONSTRAINT `vehiculos_ibfk_3` FOREIGN KEY (`id_estado`) REFERENCES `estado_vehiculo` (`id_estado`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
