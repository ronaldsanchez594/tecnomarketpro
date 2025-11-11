-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 11-11-2025 a las 18:10:17
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
-- Base de datos: `tecnomarket_db`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `detalle_venta`
--

CREATE TABLE `detalle_venta` (
  `id` int(11) NOT NULL,
  `id_venta` int(11) NOT NULL,
  `id_producto` int(11) NOT NULL,
  `cantidad` int(11) NOT NULL,
  `precio_unitario` decimal(10,2) NOT NULL,
  `subtotal` decimal(10,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `detalle_venta`
--

INSERT INTO `detalle_venta` (`id`, `id_venta`, `id_producto`, `cantidad`, `precio_unitario`, `subtotal`) VALUES
(1, 57, 4, 1, 20.00, 20.00),
(2, 58, 4, 1, 20.00, 20.00),
(3, 59, 5, 1, 2100.00, 2100.00),
(4, 60, 1, 1, 2000.00, 2000.00);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `productos`
--

CREATE TABLE `productos` (
  `id` int(11) NOT NULL,
  `nombre` varchar(100) DEFAULT NULL,
  `descripcion` text DEFAULT NULL,
  `precio` decimal(10,2) DEFAULT 0.00,
  `stock` int(11) DEFAULT 0,
  `categoria` varchar(50) DEFAULT NULL,
  `imagen` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `productos`
--

INSERT INTO `productos` (`id`, `nombre`, `descripcion`, `precio`, `stock`, `categoria`, `imagen`) VALUES
(1, 'Laptop HP 15', 'Laptop HP 15\" pantalla FullHD, Intel i5, 8GB RAM, 512GB SSD', 2000.00, 5, 'Laptops', 'laptop_hp.jpg'),
(2, 'Teclado Gamer RGB', 'Teclado mecánico RGB, switches azules, retroiluminación', 180.00, 50, 'Accesorios', 'teclado_gamer.jpg'),
(3, 'Mouse Logitech Wireless', 'Mouse inalámbrico ergonómico con sensor de alta precisión', 120.00, 50, 'Accesorios', 'mouse_logitech.jpg'),
(4, 'teclado', 'rojo', 20.00, 33, 'Accesorios', ''),
(5, 'laptop lenovo idepad1 ', 'con raizen 7 ,5000 series ,tamaño(15\'\',7)) , ultra delgada', 2100.00, 9, 'laptops', '1762448615_Captura de pantalla 2025-05-16 082948.png');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `proveedores`
--

CREATE TABLE `proveedores` (
  `id` int(11) NOT NULL,
  `nombre` varchar(100) NOT NULL,
  `contacto` varchar(100) DEFAULT NULL,
  `telefono` varchar(20) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  `direccion` text DEFAULT NULL,
  `estado` enum('activo','inactivo') DEFAULT 'activo',
  `fecha_registro` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `proveedores`
--

INSERT INTO `proveedores` (`id`, `nombre`, `contacto`, `telefono`, `email`, `direccion`, `estado`, `fecha_registro`) VALUES
(1, 'TecnoImport S.A.', 'Carlos Rodríguez', '+51 987654321', 'carlos@tecnoimport.com', 'Av. Tecnología 123, Lima', 'activo', '2025-11-07 15:53:43'),
(2, 'ElectroSupply Perú', 'María González', '+51 987654322', 'mgonzalez@electrosupply.pe', 'Jr. Componentes 456, Arequipa', 'activo', '2025-11-07 15:53:43'),
(3, 'Distribuidora TechWorld', 'Roberto Silva', '+51 987654323', 'ventas@techworld.com', 'Calle Innovación 789, Trujillo', 'activo', '2025-11-07 15:53:43'),
(4, 'Global Electronics', 'Ana Mendoza', '+51 987654324', 'ana.mendoza@globalelectronics.com', 'Av. Digital 321, Lima', 'activo', '2025-11-07 15:53:43'),
(5, 'Componentes Premium SAC', 'Javier López', '+51 987654325', 'jlopez@componentespremium.com', 'Urb. Industrial Mz. L Lt. 15, Callao', 'activo', '2025-11-07 15:53:43');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `proveedor_precios`
--

CREATE TABLE `proveedor_precios` (
  `id` int(11) NOT NULL,
  `proveedor_id` int(11) DEFAULT NULL,
  `producto_id` int(11) DEFAULT NULL,
  `precio_compra` decimal(10,2) NOT NULL,
  `stock_disponible` int(11) DEFAULT 0,
  `tiempo_entrega` int(11) DEFAULT NULL COMMENT 'Días para entrega'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `proveedor_precios`
--

INSERT INTO `proveedor_precios` (`id`, `proveedor_id`, `producto_id`, `precio_compra`, `stock_disponible`, `tiempo_entrega`) VALUES
(193, 1, 1, 850.00, 50, 3),
(194, 2, 1, 870.00, 30, 2),
(195, 3, 1, 840.00, 25, 5),
(196, 4, 1, 860.00, 40, 4),
(197, 1, 2, 650.00, 35, 3),
(198, 2, 2, 630.00, 20, 2),
(199, 3, 2, 645.00, 15, 5),
(200, 1, 3, 420.00, 100, 3),
(201, 2, 3, 430.00, 80, 2),
(202, 4, 3, 415.00, 60, 4),
(203, 5, 3, 425.00, 70, 3),
(204, 2, 4, 280.00, 120, 2),
(205, 3, 4, 275.00, 90, 5),
(206, 5, 4, 270.00, 110, 3),
(207, 3, 5, 2000.00, 5, 3);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `usuarios`
--

CREATE TABLE `usuarios` (
  `id` int(11) NOT NULL,
  `nombre` varchar(100) DEFAULT NULL,
  `correo` varchar(100) DEFAULT NULL,
  `clave` varchar(255) DEFAULT NULL,
  `rol` enum('admin','vendedor','cliente') DEFAULT 'cliente'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `usuarios`
--

INSERT INTO `usuarios` (`id`, `nombre`, `correo`, `clave`, `rol`) VALUES
(4, 'ronald', 'admin@gmail.com', '$2y$10$stx4TyTBcRbGNdM42abFlODKUuPtQt1mNMo3oGnplFTvCC5e7v/5S', 'admin'),
(5, 'cliente', 'cliente@gmail', '$2y$10$H55TUoqvuC6WX/2dVX/UG.p/Ws5R3RTLu5FVxny8.duTtfnoSuytu', 'cliente');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `ventas`
--

CREATE TABLE `ventas` (
  `id` int(11) NOT NULL,
  `id_usuario` int(11) DEFAULT NULL,
  `fecha` datetime DEFAULT current_timestamp(),
  `total` decimal(10,2) DEFAULT 0.00,
  `metodo_pago` varchar(50) DEFAULT NULL,
  `status` enum('pendiente','pagado') DEFAULT 'pagado',
  `metodo_envio` varchar(50) NOT NULL DEFAULT 'shalom',
  `tipo_documento` varchar(20) NOT NULL DEFAULT 'boleta',
  `numero_documento` varchar(20) NOT NULL DEFAULT '00000000',
  `nombre_completo` varchar(100) NOT NULL DEFAULT 'Cliente',
  `telefono` varchar(15) NOT NULL DEFAULT '000000000',
  `email` varchar(100) NOT NULL DEFAULT 'cliente@email.com',
  `direccion` text NOT NULL DEFAULT 'Dirección no especificada',
  `departamento` varchar(50) NOT NULL DEFAULT 'Lima',
  `provincia` varchar(50) NOT NULL DEFAULT 'Lima',
  `distrito` varchar(50) NOT NULL DEFAULT 'Lima',
  `referencia` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `ventas`
--

INSERT INTO `ventas` (`id`, `id_usuario`, `fecha`, `total`, `metodo_pago`, `status`, `metodo_envio`, `tipo_documento`, `numero_documento`, `nombre_completo`, `telefono`, `email`, `direccion`, `departamento`, `provincia`, `distrito`, `referencia`) VALUES
(1, NULL, '2025-10-15 10:30:00', 2180.00, 'tarjeta', 'pagado', 'shalom', 'boleta', '00000000', 'Cliente', '000000000', 'cliente@email.com', 'Dirección no especificada', 'Lima', 'Lima', 'Lima', NULL),
(2, NULL, '2025-10-18 14:45:00', 120.00, 'yape', 'pagado', 'shalom', 'boleta', '00000000', 'Cliente', '000000000', 'cliente@email.com', 'Dirección no especificada', 'Lima', 'Lima', 'Lima', NULL),
(3, 4, '2025-10-22 02:39:58', 360.00, 'yape', 'pagado', 'shalom', 'boleta', '00000000', 'Cliente', '000000000', 'cliente@email.com', 'Dirección no especificada', 'Lima', 'Lima', 'Lima', NULL),
(4, 4, '2025-10-23 03:08:43', 40.00, 'contra_entrega', 'pagado', 'shalom', 'boleta', '00000000', 'Cliente', '000000000', 'cliente@email.com', 'Dirección no especificada', 'Lima', 'Lima', 'Lima', NULL),
(5, 4, '2025-10-23 03:09:20', 20.00, 'yape', 'pagado', 'shalom', 'boleta', '00000000', 'Cliente', '000000000', 'cliente@email.com', 'Dirección no especificada', 'Lima', 'Lima', 'Lima', NULL),
(6, 4, '2025-10-23 03:15:48', 540.00, 'tarjeta', 'pagado', 'shalom', 'boleta', '00000000', 'Cliente', '000000000', 'cliente@email.com', 'Dirección no especificada', 'Lima', 'Lima', 'Lima', NULL),
(7, 4, '2025-10-23 03:19:31', 120.00, 'contra_entrega', 'pagado', 'shalom', 'boleta', '00000000', 'Cliente', '000000000', 'cliente@email.com', 'Dirección no especificada', 'Lima', 'Lima', 'Lima', NULL),
(8, 5, '2025-10-23 11:49:28', 240.00, 'tarjeta', 'pagado', 'shalom', 'boleta', '00000000', 'Cliente', '000000000', 'cliente@email.com', 'Dirección no especificada', 'Lima', 'Lima', 'Lima', NULL),
(9, 5, '2025-10-23 12:13:25', 20.00, 'yape', 'pagado', 'shalom', 'boleta', '00000000', 'Cliente', '000000000', 'cliente@email.com', 'Dirección no especificada', 'Lima', 'Lima', 'Lima', NULL),
(10, 5, '2025-10-23 12:15:45', 20.00, 'tarjeta', 'pagado', 'shalom', 'boleta', '00000000', 'Cliente', '000000000', 'cliente@email.com', 'Dirección no especificada', 'Lima', 'Lima', 'Lima', NULL),
(11, 5, '2025-10-23 12:16:37', 20.00, 'tarjeta', 'pagado', 'shalom', 'boleta', '00000000', 'Cliente', '000000000', 'cliente@email.com', 'Dirección no especificada', 'Lima', 'Lima', 'Lima', NULL),
(12, 5, '2025-10-23 12:20:53', 20.00, 'tarjeta', 'pagado', 'shalom', 'boleta', '00000000', 'Cliente', '000000000', 'cliente@email.com', 'Dirección no especificada', 'Lima', 'Lima', 'Lima', NULL),
(13, 5, '2025-10-23 12:25:17', 20.00, 'tarjeta', 'pagado', 'shalom', 'boleta', '00000000', 'Cliente', '000000000', 'cliente@email.com', 'Dirección no especificada', 'Lima', 'Lima', 'Lima', NULL),
(14, 5, '2025-10-23 12:25:38', 20.00, 'yape', 'pagado', 'shalom', 'boleta', '00000000', 'Cliente', '000000000', 'cliente@email.com', 'Dirección no especificada', 'Lima', 'Lima', 'Lima', NULL),
(15, 5, '2025-10-23 12:27:04', 120.00, 'yape', 'pagado', 'shalom', 'boleta', '00000000', 'Cliente', '000000000', 'cliente@email.com', 'Dirección no especificada', 'Lima', 'Lima', 'Lima', NULL),
(16, 5, '2025-10-23 12:47:47', 120.00, 'yape', 'pagado', 'shalom', 'boleta', '00000000', 'Cliente', '000000000', 'cliente@email.com', 'Dirección no especificada', 'Lima', 'Lima', 'Lima', NULL),
(17, 5, '2025-10-23 12:50:48', 120.00, 'yape', 'pagado', 'shalom', 'boleta', '00000000', 'Cliente', '000000000', 'cliente@email.com', 'Dirección no especificada', 'Lima', 'Lima', 'Lima', NULL),
(18, 5, '2025-10-23 12:53:01', 120.00, 'yape', 'pagado', 'shalom', 'boleta', '00000000', 'Cliente', '000000000', 'cliente@email.com', 'Dirección no especificada', 'Lima', 'Lima', 'Lima', NULL),
(19, 5, '2025-10-23 12:59:18', 120.00, 'yape', 'pagado', 'shalom', 'boleta', '00000000', 'Cliente', '000000000', 'cliente@email.com', 'Dirección no especificada', 'Lima', 'Lima', 'Lima', NULL),
(20, 5, '2025-10-23 12:59:31', 120.00, 'yape', 'pagado', 'shalom', 'boleta', '00000000', 'Cliente', '000000000', 'cliente@email.com', 'Dirección no especificada', 'Lima', 'Lima', 'Lima', NULL),
(21, 5, '2025-10-23 13:00:08', 120.00, 'yape', 'pagado', 'shalom', 'boleta', '00000000', 'Cliente', '000000000', 'cliente@email.com', 'Dirección no especificada', 'Lima', 'Lima', 'Lima', NULL),
(22, 5, '2025-10-23 13:02:37', 120.00, 'yape', 'pagado', 'shalom', 'boleta', '00000000', 'Cliente', '000000000', 'cliente@email.com', 'Dirección no especificada', 'Lima', 'Lima', 'Lima', NULL),
(23, 5, '2025-10-23 13:04:22', 180.00, 'yape', 'pagado', 'shalom', 'boleta', '00000000', 'Cliente', '000000000', 'cliente@email.com', 'Dirección no especificada', 'Lima', 'Lima', 'Lima', NULL),
(24, 5, '2025-10-23 13:06:26', 120.00, 'yape', 'pagado', 'shalom', 'boleta', '00000000', 'Cliente', '000000000', 'cliente@email.com', 'Dirección no especificada', 'Lima', 'Lima', 'Lima', NULL),
(25, 5, '2025-10-23 13:09:12', 120.00, 'yape', 'pagado', 'shalom', 'boleta', '00000000', 'Cliente', '000000000', 'cliente@email.com', 'Dirección no especificada', 'Lima', 'Lima', 'Lima', NULL),
(26, 5, '2025-10-23 13:10:55', 120.00, 'yape', 'pagado', 'shalom', 'boleta', '00000000', 'Cliente', '000000000', 'cliente@email.com', 'Dirección no especificada', 'Lima', 'Lima', 'Lima', NULL),
(27, 5, '2025-10-23 13:12:38', 120.00, 'yape', 'pagado', 'shalom', 'boleta', '00000000', 'Cliente', '000000000', 'cliente@email.com', 'Dirección no especificada', 'Lima', 'Lima', 'Lima', NULL),
(28, 5, '2025-10-23 13:14:03', 120.00, 'yape', 'pagado', 'shalom', 'boleta', '00000000', 'Cliente', '000000000', 'cliente@email.com', 'Dirección no especificada', 'Lima', 'Lima', 'Lima', NULL),
(29, 5, '2025-10-23 13:15:33', 120.00, 'yape', 'pagado', 'shalom', 'boleta', '00000000', 'Cliente', '000000000', 'cliente@email.com', 'Dirección no especificada', 'Lima', 'Lima', 'Lima', NULL),
(30, 5, '2025-10-23 13:18:02', 120.00, 'yape', 'pagado', 'shalom', 'boleta', '00000000', 'Cliente', '000000000', 'cliente@email.com', 'Dirección no especificada', 'Lima', 'Lima', 'Lima', NULL),
(31, 5, '2025-10-26 01:27:01', 2360.00, 'yape', 'pagado', 'shalom', 'boleta', '00000000', 'Cliente', '000000000', 'cliente@email.com', 'Dirección no especificada', 'Lima', 'Lima', 'Lima', NULL),
(32, 5, '2025-10-26 01:54:04', 420.00, 'tarjeta', 'pagado', 'shalom', 'boleta', '00000000', 'Cliente', '000000000', 'cliente@email.com', 'Dirección no especificada', 'Lima', 'Lima', 'Lima', NULL),
(33, 5, '2025-10-26 01:56:46', 120.00, 'tarjeta', 'pagado', 'shalom', 'boleta', '00000000', 'Cliente', '000000000', 'cliente@email.com', 'Dirección no especificada', 'Lima', 'Lima', 'Lima', NULL),
(34, 5, '2025-10-26 02:03:29', 180.00, 'yape', 'pagado', 'shalom', 'boleta', '00000000', 'Cliente', '000000000', 'cliente@email.com', 'Dirección no especificada', 'Lima', 'Lima', 'Lima', NULL),
(35, 5, '2025-10-26 02:08:59', 180.00, 'contra_entrega', 'pagado', 'shalom', 'boleta', '00000000', 'Cliente', '000000000', 'cliente@email.com', 'Dirección no especificada', 'Lima', 'Lima', 'Lima', NULL),
(36, 4, '2025-10-26 02:19:14', 180.00, 'yape', 'pagado', 'shalom', 'boleta', '00000000', 'Cliente', '000000000', 'cliente@email.com', 'Dirección no especificada', 'Lima', 'Lima', 'Lima', NULL),
(37, 4, '2025-10-26 02:19:53', 180.00, 'yape', 'pagado', 'shalom', 'boleta', '00000000', 'Cliente', '000000000', 'cliente@email.com', 'Dirección no especificada', 'Lima', 'Lima', 'Lima', NULL),
(38, 4, '2025-10-26 02:20:08', 180.00, 'yape', 'pagado', 'shalom', 'boleta', '00000000', 'Cliente', '000000000', 'cliente@email.com', 'Dirección no especificada', 'Lima', 'Lima', 'Lima', NULL),
(39, 5, '2025-10-28 10:53:24', 140.00, 'contra_entrega', 'pagado', 'shalom', 'boleta', '00000000', 'Cliente', '000000000', 'cliente@email.com', 'Dirección no especificada', 'Lima', 'Lima', 'Lima', NULL),
(40, 5, '2025-10-28 11:33:01', 220.00, 'yape', 'pagado', 'shalom', 'boleta', '00000000', 'Cliente', '000000000', 'cliente@email.com', 'Dirección no especificada', 'Lima', 'Lima', 'Lima', NULL),
(41, 5, '2025-10-28 11:37:49', 100.00, 'contra_entrega', 'pagado', 'shalom', 'boleta', '00000000', 'Cliente', '000000000', 'cliente@email.com', 'Dirección no especificada', 'Lima', 'Lima', 'Lima', NULL),
(42, 5, '2025-10-28 11:42:17', 20.00, 'yape', 'pagado', 'shalom', 'boleta', '00000000', 'Cliente', '000000000', 'cliente@email.com', 'Dirección no especificada', 'Lima', 'Lima', 'Lima', NULL),
(56, 5, '2025-11-05 14:18:51', 20.00, 'yape', 'pagado', 'shalom', 'factura', '20603178188', 'ronald sanchez monja', '930184266', 'ronaldsanchezmonja594@gmail.com', 'olmos', 'lambayeque', 'lambayeque', 'olmos', 'cerca al colegio'),
(57, 5, '2025-11-05 14:20:55', 20.00, 'yape', 'pagado', 'shalom', 'factura', '20603178188', 'ronald sanchez monja', '930184266', 'ronaldsanchezmonja594@gmail.com', 'olmos', 'lambayeque', 'lambayeque', 'olmos', 'cerca al colegio'),
(58, 5, '2025-11-05 14:22:13', 20.00, 'yape', 'pagado', 'shalom', 'factura', '20603178188', 'ronald sanchez monja', '930184266', 'ronaldsanchezmonja594@gmail.com', 'olmos', 'lambayeque', 'lambayeque', 'olmos', 'cerca al colegio'),
(59, 5, '2025-11-06 12:01:19', 2100.00, 'yape', 'pagado', 'shalom', 'boleta', '61702373', 'ronald sanchez monja', '930184266', 'ronaldsanchezmonja594@gmail.com', 'olmos', 'lambayeque', 'lambayeque', 'olmos', 'cerca al colegio'),
(60, 5, '2025-11-11 11:51:35', 2000.00, 'yape', 'pagado', 'shalom', 'boleta', '61702373', 'ronald sanchez monja', '930184266', 'ronaldsanchezmonja594@gmail.com', 'olmos', 'lambayeque', 'lambayeque', 'olmos', 'cerca al colegio');

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `detalle_venta`
--
ALTER TABLE `detalle_venta`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_venta` (`id_venta`),
  ADD KEY `id_producto` (`id_producto`);

--
-- Indices de la tabla `productos`
--
ALTER TABLE `productos`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `proveedores`
--
ALTER TABLE `proveedores`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `proveedor_precios`
--
ALTER TABLE `proveedor_precios`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `unique_proveedor_producto` (`proveedor_id`,`producto_id`),
  ADD KEY `producto_id` (`producto_id`);

--
-- Indices de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `correo` (`correo`);

--
-- Indices de la tabla `ventas`
--
ALTER TABLE `ventas`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_usuario` (`id_usuario`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `detalle_venta`
--
ALTER TABLE `detalle_venta`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT de la tabla `productos`
--
ALTER TABLE `productos`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT de la tabla `proveedores`
--
ALTER TABLE `proveedores`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT de la tabla `proveedor_precios`
--
ALTER TABLE `proveedor_precios`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=208;

--
-- AUTO_INCREMENT de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT de la tabla `ventas`
--
ALTER TABLE `ventas`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=61;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `detalle_venta`
--
ALTER TABLE `detalle_venta`
  ADD CONSTRAINT `detalle_venta_ibfk_1` FOREIGN KEY (`id_venta`) REFERENCES `ventas` (`id`),
  ADD CONSTRAINT `detalle_venta_ibfk_2` FOREIGN KEY (`id_producto`) REFERENCES `productos` (`id`);

--
-- Filtros para la tabla `proveedor_precios`
--
ALTER TABLE `proveedor_precios`
  ADD CONSTRAINT `proveedor_precios_ibfk_1` FOREIGN KEY (`proveedor_id`) REFERENCES `proveedores` (`id`),
  ADD CONSTRAINT `proveedor_precios_ibfk_2` FOREIGN KEY (`producto_id`) REFERENCES `productos` (`id`);

--
-- Filtros para la tabla `ventas`
--
ALTER TABLE `ventas`
  ADD CONSTRAINT `ventas_ibfk_1` FOREIGN KEY (`id_usuario`) REFERENCES `usuarios` (`id`) ON DELETE SET NULL;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
