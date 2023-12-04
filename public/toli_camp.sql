-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 16-11-2023 a las 12:51:18
-- Versión del servidor: 10.4.28-MariaDB
-- Versión de PHP: 8.0.28

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
(1, 'dsdacc');

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
  `documento` int(11) NOT NULL,
  `fecha_ingre` date NOT NULL,
  `hora_ingre` time NOT NULL,
  `fecha_sali` date NOT NULL,
  `hora_sali` time NOT NULL,
  `durac` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Volcado de datos para la tabla `ingreso`
--

INSERT INTO `ingreso` (`id_ingreso`, `documento`, `fecha_ingre`, `hora_ingre`, `fecha_sali`, `hora_sali`, `durac`) VALUES
(1, 1106632525, '2023-10-26', '01:26:22', '0000-00-00', '00:00:00', 0),
(2, 1106632525, '2023-10-26', '01:30:47', '0000-00-00', '00:00:00', 0),
(3, 1106632525, '2023-10-26', '01:31:07', '0000-00-00', '00:00:00', 0),
(4, 1106632118, '2023-10-26', '10:41:05', '0000-00-00', '00:00:00', 0),
(5, 1106632118, '2023-10-26', '10:44:24', '0000-00-00', '00:00:00', 0),
(6, 1106632513, '2023-10-26', '10:47:24', '0000-00-00', '00:00:00', 0),
(7, 1106632525, '2023-10-31', '06:21:58', '0000-00-00', '00:00:00', 0),
(8, 1106632525, '2023-10-31', '06:22:08', '0000-00-00', '00:00:00', 0),
(9, 1106632525, '2023-10-31', '06:23:04', '0000-00-00', '00:00:00', 0),
(10, 1106632525, '2023-10-31', '06:23:31', '0000-00-00', '00:00:00', 0),
(11, 1106632525, '2023-11-03', '06:30:39', '0000-00-00', '00:00:00', 0),
(12, 1106632525, '2023-11-07', '06:20:42', '0000-00-00', '00:00:00', 0),
(13, 1106632517, '2023-11-07', '06:58:12', '0000-00-00', '00:00:00', 0),
(14, 1106632525, '2023-11-14', '10:04:50', '0000-00-00', '00:00:00', 0);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `productos`
--

CREATE TABLE `productos` (
  `id_producto` int(11) NOT NULL,
  `nom_produc` varchar(50) NOT NULL,
  `descrip` varchar(150) NOT NULL,
  `precio_compra` decimal(10,2) NOT NULL,
  `disponibles` int(11) NOT NULL,
  `id_categoria` int(11) NOT NULL,
  `cantidad` smallint(5) NOT NULL,
  `id_embala` int(11) NOT NULL,
  `foto` longblob NOT NULL,
  `precio_ven` decimal(10,2) NOT NULL,
  `documento` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `productos`
--

INSERT INTO `productos` (`id_producto`, `nom_produc`, `descrip`, `precio_compra`, `disponibles`, `id_categoria`, `cantidad`, `id_embala`, `foto`, `precio_ven`, `documento`) VALUES
(1, 'tomateeee', 'Se encuentra variedad de tomates grandes y pequeños', 5000.00, 50, 1, 60, 1, 0x70726f647563746f5f313639393937363836392e77656270, 0.00, 1106632525),
(2, 'asdasd', 'sadas', 99999999.99, 12312, 1, 2132, 1, 0x4172726179, 66666.00, 1106632525),
(3, 'uhuhuh', 'uhuhuh', 6666.00, 777, 1, 6666, 1, 0x4172726179, 67766.00, 1106632525),
(4, 'sadasasdas', 'sadasd', 1212.00, 1212, 1, 32767, 1, 0x70726f647563746f5f313639393937363931372e6a7067, 1212.00, 1106632525);

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
  `tipoocu` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `trig_pass`
--

CREATE TABLE `trig_pass` (
  `id_trigpass` int(11) NOT NULL,
  `documento` int(10) NOT NULL,
  `correo_electronico` varchar(50) NOT NULL,
  `contraseña` varchar(500) NOT NULL,
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
(28544314, 'jeidy', 'joven', '$2y$12$aQ7vTJirCTJHQyN3wKz4uu3zJmNBrr.FKJX6PRj1CBgDs9607bhmm', 'Jeidy13@gmail.com', '3133130948', 'Manzana Q casa 5 barrio: bosque baja', 2, 3, 1, '', 0),
(1106632118, 'yudy', 'rico', '$2y$12$f2gTqfFDE5duw6dtfAuLMeU/5ibEtCqm/FuMz5OQbOiqmwwZE26Tu', 'yerico8@misena.edu.co', '3203020256', 'Carrera 8d #131-25 barrio montecarlo', 2, 2, 1, '', 0),
(1106632517, 'kevin', 'jaimes', '$2y$12$5rN8m2svxD9ueNZFOqFJkulXC24LC5Y7PDr4NAJfA0px86IaZv11O', 'kajaimes51@misena.edu.co', '3245245253', 'el salado', 1, 4, 1, '', 0),
(1106632525, 'jhoen', 'ramos', '$2y$12$GprzLgvGSFU.O.rP4ra.v.9FalCCzrBrv.cKtza8jzYAHpde.nnoq', 'sahileth96@gmail.com', '3227825320', 'Manzana Q casa 5 barrio: bosque baja', 2, 1, 1, '', 0);

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
  MODIFY `id_compra` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `det_compra`
--
ALTER TABLE `det_compra`
  MODIFY `id_detcompra` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `det_venta`
--
ALTER TABLE `det_venta`
  MODIFY `id_det_venta` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT de la tabla `embalaje`
--
ALTER TABLE `embalaje`
  MODIFY `id_embala` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

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
  MODIFY `id_ingreso` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT de la tabla `productos`
--
ALTER TABLE `productos`
  MODIFY `id_producto` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT de la tabla `roles`
--
ALTER TABLE `roles`
  MODIFY `id_rol` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT de la tabla `tipdocu`
--
ALTER TABLE `tipdocu`
  MODIFY `id_tipdocu` int(11) NOT NULL AUTO_INCREMENT;

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
