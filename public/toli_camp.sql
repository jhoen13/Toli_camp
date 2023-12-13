-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 13-12-2023 a las 00:05:51
-- Versión del servidor: 10.4.28-MariaDB
-- Versión de PHP: 8.2.4

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `toli_camp`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `categoria`
--

CREATE TABLE `categoria` (
  `id_categoria` int(11) NOT NULL,
  `categoria` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `categoria`
--

INSERT INTO `categoria` (`id_categoria`, `categoria`) VALUES
(1, 'verduras'),
(2, 'carnes'),
(3, 'frutas');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `compras`
--

CREATE TABLE `compras` (
  `id_compra` int(11) NOT NULL,
  `fecha` datetime NOT NULL,
  `docu_ven` int(11) NOT NULL,
  `docu_clien` int(11) NOT NULL,
  `total` decimal(10,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Volcado de datos para la tabla `compras`
--

INSERT INTO `compras` (`id_compra`, `fecha`, `docu_ven`, `docu_clien`, `total`) VALUES
(1, '2023-12-11 09:35:12', 1106632118, 1234567890, 22900.00),
(2, '2023-12-11 09:36:50', 1106632118, 1234567890, 22900.00),
(3, '2023-12-12 03:29:43', 1106632118, 1234567890, 30000.00),
(4, '2023-12-12 03:30:27', 1106632118, 1234567890, 30000.00),
(5, '2023-12-12 03:31:08', 1106632118, 1234567890, 30000.00),
(6, '2023-12-12 03:34:59', 1106632118, 1234567890, 30000.00),
(7, '2023-12-12 03:36:35', 1106632118, 1234567890, 17200.00),
(8, '2023-12-12 06:59:51', 1106632118, 1234567890, 7900.00),
(9, '2023-12-12 07:08:47', 1106632118, 1234567890, 4400.00),
(10, '2023-12-12 18:04:07', 1106632118, 1234567890, 17200.00);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `det_compra`
--

CREATE TABLE `det_compra` (
  `id_detcompra` int(11) NOT NULL,
  `id_compra` int(11) NOT NULL,
  `id_producto` int(11) NOT NULL,
  `cantidad` int(5) NOT NULL,
  `sub_tot` decimal(10,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Volcado de datos para la tabla `det_compra`
--

INSERT INTO `det_compra` (`id_detcompra`, `id_compra`, `id_producto`, `cantidad`, `sub_tot`) VALUES
(1, 6, 120, 1, 15000.00),
(2, 6, 120, 1, 15000.00),
(3, 7, 120, 1, 15000.00),
(4, 7, 118, 1, 2200.00),
(5, 8, 118, 1, 2200.00),
(6, 8, 118, 1, 2200.00),
(7, 8, 117, 1, 3500.00),
(8, 9, 118, 1, 2200.00),
(9, 9, 118, 1, 2200.00),
(10, 10, 118, 1, 2200.00),
(11, 10, 120, 1, 15000.00);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `det_venta`
--

CREATE TABLE `det_venta` (
  `id_det_venta` int(11) NOT NULL,
  `id_venta` int(11) NOT NULL,
  `id_producto` int(11) NOT NULL,
  `cantidad` int(5) NOT NULL,
  `sub_tot` decimal(10,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `det_venta`
--

INSERT INTO `det_venta` (`id_det_venta`, `id_venta`, `id_producto`, `cantidad`, `sub_tot`) VALUES
(1, 1, 1, 1106632525, 10000.00);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `embalaje`
--

CREATE TABLE `embalaje` (
  `id_embala` int(11) NOT NULL,
  `embalaje` char(30) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Volcado de datos para la tabla `embalaje`
--

INSERT INTO `embalaje` (`id_embala`, `embalaje`) VALUES
(1, 'bolsa'),
(2, 'cajas');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `entrada`
--

CREATE TABLE `entrada` (
  `id_entrada` int(11) NOT NULL,
  `docu_mayo` int(11) NOT NULL,
  `id_producto` int(11) NOT NULL,
  `cantidad` int(5) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `estado`
--

CREATE TABLE `estado` (
  `id_estado` int(11) NOT NULL,
  `estado` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `estado`
--

INSERT INTO `estado` (`id_estado`, `estado`) VALUES
(1, 'ACTIVO'),
(2, 'VENCIDO');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `genero`
--

CREATE TABLE `genero` (
  `id_genero` int(11) NOT NULL,
  `genero` char(15) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `genero`
--

INSERT INTO `genero` (`id_genero`, `genero`) VALUES
(1, 'masculino'),
(2, 'femenino'),
(3, 'otro');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `ingreso`
--

CREATE TABLE `ingreso` (
  `id_ingreso` int(11) NOT NULL,
  `codi_ingre` int(11) NOT NULL,
  `documento` int(11) NOT NULL,
  `fecha_ingre` date NOT NULL,
  `hora_ingre` time NOT NULL,
  `fecha_sali` date NOT NULL,
  `hora_sali` time NOT NULL,
  `durac` time NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Volcado de datos para la tabla `ingreso`
--

INSERT INTO `ingreso` (`id_ingreso`, `codi_ingre`, `documento`, `fecha_ingre`, `hora_ingre`, `fecha_sali`, `hora_sali`, `durac`) VALUES
(74, 74, 1106632525, '2023-12-10', '12:19:01', '2023-12-10', '12:19:29', '00:00:28'),
(76, 76, 1106632118, '2023-12-10', '12:47:33', '2023-12-10', '13:57:43', '01:10:10'),
(77, 77, 1106632118, '2023-12-10', '15:50:52', '2023-12-10', '15:51:25', '00:00:33'),
(78, 78, 1106632118, '2023-12-10', '16:04:17', '2023-12-10', '16:41:00', '00:36:43'),
(79, 79, 1106632517, '2023-12-10', '16:44:53', '2023-12-10', '17:17:31', '00:32:38'),
(80, 80, 1106632118, '2023-12-10', '17:22:09', '0000-00-00', '00:00:00', '00:00:00'),
(81, 81, 1106632525, '2023-12-10', '17:31:09', '2023-12-10', '21:44:29', '04:13:20'),
(94, 94, 1106632525, '2023-12-12', '16:37:57', '0000-00-00', '00:00:00', '00:00:00'),
(95, 95, 1106632525, '2023-12-12', '16:41:37', '0000-00-00', '00:00:00', '00:00:00'),
(96, 96, 1106632525, '2023-12-12', '16:42:32', '0000-00-00', '00:00:00', '00:00:00'),
(97, 97, 1106632525, '2023-12-12', '16:44:29', '0000-00-00', '00:00:00', '00:00:00'),
(98, 98, 1106632525, '2023-12-12', '16:45:29', '2023-12-12', '18:01:48', '01:16:19'),
(99, 99, 1106632118, '2023-12-12', '18:03:06', '0000-00-00', '00:00:00', '00:00:00');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `productos`
--

CREATE TABLE `productos` (
  `id_producto` int(11) NOT NULL,
  `barcode` int(11) NOT NULL,
  `nom_produc` varchar(50) NOT NULL,
  `descrip` varchar(150) NOT NULL,
  `precio_compra` decimal(10,2) NOT NULL,
  `id_categoria` int(11) NOT NULL,
  `cantidad` smallint(5) NOT NULL,
  `id_embala` int(11) NOT NULL,
  `foto` varchar(255) NOT NULL,
  `precio_ven` decimal(10,2) NOT NULL,
  `documento` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `productos`
--

INSERT INTO `productos` (`id_producto`, `barcode`, `nom_produc`, `descrip`, `precio_compra`, `id_categoria`, `cantidad`, `id_embala`, `foto`, `precio_ven`, `documento`) VALUES
(49, 49, 'mango', 'paquete grande', 3000.00, 2, 20, 1, 'producto_1702247700.jpg', 3500.00, 1106632525),
(50, 50, 'manzana', 'roja grande', 1500.00, 3, 20, 1, 'producto_1702249329.jpg', 2200.00, 1106632118),
(53, 53, 'fresas', 'son grandes, estan frescas', 6000.00, 3, 20, 1, 'producto_1702247725.jpg', 70000.00, 1106632517),
(117, 117, 'pera', 'paquete con 5 unidades', 3000.00, 2, 19, 1, 'producto_1702249244.jpg', 3500.00, 0),
(118, 118, 'pitalla', 'ofrecemos de color verde, rojas, moradas', 1500.00, 1, 14, 1, 'producto_1702249617.jpg', 2200.00, 1106632118),
(119, 119, 'papaya', 'paquete con 5 unidades', 3000.00, 3, 20, 1, 'producto_1702249283.jpg', 3500.00, 1106632525),
(120, 120, 'pollo', 'frescos y grandes', 12000.00, 2, 16, 2, 'producto_1702258387.jpg', 15000.00, 1106632118),
(121, 121, 'carne de res ', 'producto fresco', 15000.00, 2, 20, 1, 'producto_1702258502.jpg', 18000.00, 1106632517);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `roles`
--

CREATE TABLE `roles` (
  `id_rol` int(11) NOT NULL,
  `tipo_rol` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `roles`
--

INSERT INTO `roles` (`id_rol`, `tipo_rol`) VALUES
(1, 'administrador'),
(2, 'usuario'),
(3, 'vendedor'),
(4, 'campesino');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tipdocu`
--

CREATE TABLE `tipdocu` (
  `id_tipdocu` int(11) NOT NULL,
  `tipdocu` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Volcado de datos para la tabla `tipdocu`
--

INSERT INTO `tipdocu` (`id_tipdocu`, `tipdocu`) VALUES
(1, 'T.I'),
(2, 'C.C');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `trig_pass`
--

CREATE TABLE `trig_pass` (
  `id_trigpass` int(11) NOT NULL,
  `documento` int(10) NOT NULL,
  `correo_electronico` varchar(50) NOT NULL,
  `contraseÃ±a` varchar(500) NOT NULL,
  `date` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `usuarios`
--

CREATE TABLE `usuarios` (
  `documento` int(11) NOT NULL,
  `nombre` varchar(50) NOT NULL,
  `apellido` varchar(50) NOT NULL,
  `password` varchar(500) NOT NULL,
  `correo_electronico` varchar(50) NOT NULL,
  `celular` varchar(10) NOT NULL,
  `direccion` varchar(50) NOT NULL,
  `id_genero` int(11) NOT NULL,
  `id_rol` int(11) NOT NULL,
  `id_estado` int(11) NOT NULL,
  `foto` varchar(255) NOT NULL,
  `id_tipdocu` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `usuarios`
--

INSERT INTO `usuarios` (`documento`, `nombre`, `apellido`, `password`, `correo_electronico`, `celular`, `direccion`, `id_genero`, `id_rol`, `id_estado`, `foto`, `id_tipdocu`) VALUES
(1106632118, 'yudy elizabeth', 'rico ramirez', '$2y$12$aEw7r1xcZJqBj/sfVSd3LuzvN9hgm0pzNPjWaP0Vxx9ex86WRkOTu', 'yerico8@misena.edu.co', '3267893468', 'manzana Q casa #5 barrio: el salado', 2, 3, 1, 'usuario_1702230255.jpg', 2),
(1106632517, 'kevin', 'jaimes', '$2y$12$5rN8m2svxD9ueNZFOqFJkulXC24LC5Y7PDr4NAJfA0px86IaZv11O', 'kajaimes51@misena.edu.co', '3245245253', 'el salado', 1, 4, 1, 'usuario_1701839392.png', 2),
(1106632525, 'jhoen sahileth', 'ramos', '$2y$12$XjCO3IvqPA3QE75t8JoWduknjllD8eAWHjB.pIkEoAUVIFg36u8my', 'sahileth96@gmail.com', '3227825320', 'Manzana Q casa 5 barrio: bosque baja', 2, 1, 1, 'usuario_1702248632.jpg', 2),
(1234567890, 'juan', 'gomez', '$2y$12$fjYJLEdzA0281Ji2S/.KxOPo6Fl3Ur3fu9cTWL9sQCSCSCCLHN3hO', 'juan10@gmail.com', '1234567890', 'noe', 1, 2, 2, 'usuario_1702248582.jpg', 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `ventas`
--

CREATE TABLE `ventas` (
  `id_venta` int(11) NOT NULL,
  `fecha` datetime NOT NULL,
  `tot_ven` decimal(10,2) NOT NULL,
  `docu_ven` int(11) NOT NULL,
  `docu_clien` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `categoria`
--
ALTER TABLE `categoria`
  ADD PRIMARY KEY (`id_categoria`);

--
-- Indices de la tabla `compras`
--
ALTER TABLE `compras`
  ADD PRIMARY KEY (`id_compra`);

--
-- Indices de la tabla `det_compra`
--
ALTER TABLE `det_compra`
  ADD PRIMARY KEY (`id_detcompra`);

--
-- Indices de la tabla `det_venta`
--
ALTER TABLE `det_venta`
  ADD PRIMARY KEY (`id_det_venta`),
  ADD KEY `id_producto` (`id_producto`),
  ADD KEY `id_venta` (`id_venta`),
  ADD KEY `documento` (`cantidad`);

--
-- Indices de la tabla `embalaje`
--
ALTER TABLE `embalaje`
  ADD PRIMARY KEY (`id_embala`);

--
-- Indices de la tabla `entrada`
--
ALTER TABLE `entrada`
  ADD PRIMARY KEY (`id_entrada`);

--
-- Indices de la tabla `estado`
--
ALTER TABLE `estado`
  ADD PRIMARY KEY (`id_estado`);

--
-- Indices de la tabla `genero`
--
ALTER TABLE `genero`
  ADD PRIMARY KEY (`id_genero`);

--
-- Indices de la tabla `ingreso`
--
ALTER TABLE `ingreso`
  ADD PRIMARY KEY (`id_ingreso`),
  ADD KEY `documento` (`documento`);

--
-- Indices de la tabla `productos`
--
ALTER TABLE `productos`
  ADD PRIMARY KEY (`id_producto`),
  ADD KEY `id_categoria` (`id_categoria`);

--
-- Indices de la tabla `roles`
--
ALTER TABLE `roles`
  ADD PRIMARY KEY (`id_rol`);

--
-- Indices de la tabla `tipdocu`
--
ALTER TABLE `tipdocu`
  ADD PRIMARY KEY (`id_tipdocu`);

--
-- Indices de la tabla `trig_pass`
--
ALTER TABLE `trig_pass`
  ADD PRIMARY KEY (`id_trigpass`);

--
-- Indices de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  ADD PRIMARY KEY (`documento`),
  ADD KEY `id_estado` (`id_estado`),
  ADD KEY `id_genero` (`id_genero`),
  ADD KEY `id_rol` (`id_rol`);

--
-- Indices de la tabla `ventas`
--
ALTER TABLE `ventas`
  ADD PRIMARY KEY (`id_venta`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `categoria`
--
ALTER TABLE `categoria`
  MODIFY `id_categoria` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT de la tabla `compras`
--
ALTER TABLE `compras`
  MODIFY `id_compra` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT de la tabla `det_compra`
--
ALTER TABLE `det_compra`
  MODIFY `id_detcompra` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT de la tabla `det_venta`
--
ALTER TABLE `det_venta`
  MODIFY `id_det_venta` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT de la tabla `embalaje`
--
ALTER TABLE `embalaje`
  MODIFY `id_embala` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT de la tabla `entrada`
--
ALTER TABLE `entrada`
  MODIFY `id_entrada` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `estado`
--
ALTER TABLE `estado`
  MODIFY `id_estado` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT de la tabla `genero`
--
ALTER TABLE `genero`
  MODIFY `id_genero` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT de la tabla `ingreso`
--
ALTER TABLE `ingreso`
  MODIFY `id_ingreso` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=100;

--
-- AUTO_INCREMENT de la tabla `productos`
--
ALTER TABLE `productos`
  MODIFY `id_producto` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=122;

--
-- AUTO_INCREMENT de la tabla `roles`
--
ALTER TABLE `roles`
  MODIFY `id_rol` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT de la tabla `tipdocu`
--
ALTER TABLE `tipdocu`
  MODIFY `id_tipdocu` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT de la tabla `trig_pass`
--
ALTER TABLE `trig_pass`
  MODIFY `id_trigpass` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `ventas`
--
ALTER TABLE `ventas`
  MODIFY `id_venta` int(11) NOT NULL AUTO_INCREMENT;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
