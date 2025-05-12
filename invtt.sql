-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 12-05-2025 a las 23:38:25
-- Versión del servidor: 10.4.27-MariaDB
-- Versión de PHP: 8.2.0

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `invtt`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `categorias`
--

CREATE TABLE `categorias` (
  `id` int(11) NOT NULL,
  `categoria` text NOT NULL,
  `fecha` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

--
-- Volcado de datos para la tabla `categorias`
--

INSERT INTO `categorias` (`id`, `categoria`, `fecha`) VALUES
(1, 'Equipos Electromecánicos', '2017-12-21 20:53:29'),
(2, 'Taladros', '2017-12-21 20:53:29'),
(3, 'Andamios', '2017-12-21 20:53:29'),
(4, 'Generadores de energía', '2017-12-21 20:53:29'),
(5, 'Equipos para construcción', '2017-12-21 20:53:29'),
(6, 'Martillos mecánicos', '2017-12-21 23:06:40');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `clientes`
--

CREATE TABLE `clientes` (
  `id` int(11) NOT NULL,
  `nombre` text NOT NULL,
  `documento` int(11) NOT NULL,
  `tipo_documento` varchar(20) NOT NULL,
  `email` text NOT NULL,
  `telefono` text NOT NULL,
  `direccion` text NOT NULL,
  `fecha_nacimiento` date NOT NULL,
  `compras` int(11) NOT NULL,
  `ultima_compra` datetime NOT NULL,
  `fecha` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

--
-- Volcado de datos para la tabla `clientes`
--

INSERT INTO `clientes` (`id`, `nombre`, `documento`, `tipo_documento`, `email`, `telefono`, `direccion`, `fecha_nacimiento`, `compras`, `ultima_compra`, `fecha`) VALUES
(3, 'Juan Villegas', 2147483647, '', 'juan@hotmail.com', '(300) 341-2345', 'Calle 23 # 45 - 56', '1980-11-02', 3, '2025-05-10 10:44:24', '2025-05-10 20:22:38'),
(4, 'Pedro Pérez', 2147483647, '', 'pedro@gmail.com', '(399) 876-5432', 'Calle 34 N33 -56', '1970-08-07', 3, '2025-05-10 10:43:08', '2025-05-10 20:22:38'),
(5, 'Miguel Murillo', 325235235, '', 'miguel@hotmail.com', '(254) 545-3446', 'calle 34 # 34 - 23', '1976-03-04', 3, '2017-12-26 17:27:13', '2025-05-10 20:22:38'),
(6, 'Margarita Londoño', 34565432, '', 'margarita@hotmail.com', '(344) 345-6678', 'Calle 45 # 34 - 56', '1976-11-30', 2, '2017-12-26 17:26:51', '2025-05-10 20:22:38'),
(7, 'Julian Ramirez', 786786545, '', 'julian@hotmail.com', '(675) 674-5453', 'Carrera 45 # 54 - 56', '1980-04-05', 2, '2017-12-26 17:26:28', '2025-05-10 20:22:38'),
(8, 'Stella Jaramillo', 65756735, '', 'stella@gmail.com', '(435) 346-3463', 'Carrera 34 # 45- 56', '1956-06-05', 2, '2017-12-26 17:25:55', '2025-05-10 20:22:38'),
(9, 'Eduardo López', 2147483647, '', 'eduardo@gmail.com', '(534) 634-6565', 'Carrera 67 # 45sur', '1978-03-04', 2, '2017-12-26 17:25:33', '2025-05-10 20:22:38'),
(10, 'Ximena Restrepo', 436346346, '', 'ximena@gmail.com', '(543) 463-4634', 'calle 45 # 23 - 45', '1956-03-04', 2, '2017-12-26 17:25:08', '2025-05-10 20:22:38'),
(11, 'David Guzman', 43634643, '', 'david@hotmail.com', '(354) 574-5634', 'carrera 45 # 45 ', '1967-05-04', 2, '2017-12-26 17:24:50', '2025-05-10 20:22:38'),
(12, 'Gonzalo Pérez', 436346346, '', 'gonzalo@yahoo.com', '(235) 346-3464', 'Carrera 34 # 56 - 34', '1967-08-09', 2, '2017-12-25 17:24:24', '2025-05-10 20:22:38'),
(13, 'No registrado ', 545522122, 'Cédula', 'noregistrado@gmail.com', '(829) 380-8296', 'km20', '2025-10-05', 3, '2025-05-10 16:33:07', '2025-05-10 20:33:17'),
(14, 'Juan Pedro', 2147483647, 'Cedula', 'juan@gmail.com', '(829) 380-7545', 'km22', '2007-10-25', 1, '2025-05-10 16:28:44', '2025-05-10 20:29:21');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `productos`
--

CREATE TABLE `productos` (
  `id` int(11) NOT NULL,
  `id_categoria` int(11) NOT NULL,
  `codigo` text NOT NULL,
  `descripcion` text NOT NULL,
  `imagen` text NOT NULL,
  `stock` int(11) NOT NULL,
  `precio_compra` float NOT NULL,
  `precio_venta` float NOT NULL,
  `ventas` int(11) NOT NULL,
  `fecha` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

--
-- Volcado de datos para la tabla `productos`
--

INSERT INTO `productos` (`id`, `id_categoria`, `codigo`, `descripcion`, `imagen`, `stock`, `precio_compra`, `precio_venta`, `ventas`, `fecha`) VALUES
(1, 1, '101', 'Aspiradora Industrial ', 'vistas/img/productos/101/105.png', 12, 1000, 1200, 3, '2025-05-10 20:33:07'),
(2, 1, '102', 'Plato Flotante para Allanadora', 'vistas/img/productos/102/940.jpg', 5, 4500, 6300, 4, '2025-05-10 20:33:07'),
(3, 1, '103', 'Compresor de Aire para pintura', 'vistas/img/productos/103/763.jpg', 7, 3000, 4200, 13, '2025-05-10 20:33:07'),
(4, 1, '104', 'Cortadora de Adobe sin Disco ', 'vistas/img/productos/104/957.jpg', 15, 4000, 5600, 5, '2025-05-10 20:33:07'),
(5, 1, '105', 'Cortadora de Piso sin Disco ', 'vistas/img/productos/105/630.jpg', 12, 1540, 2156, 8, '2025-05-10 20:33:07'),
(6, 1, '106', 'Disco Punta Diamante ', 'vistas/img/productos/106/635.jpg', 14, 1100, 1540, 6, '2025-05-10 20:33:07'),
(7, 1, '107', 'Extractor de Aire ', 'vistas/img/productos/107/848.jpg', 11, 1540, 2156, 9, '2025-05-10 20:33:07'),
(8, 1, '108', 'Guadañadora ', 'vistas/img/productos/108/163.jpg', 12, 1540, 2156, 8, '2025-05-10 20:33:07'),
(9, 1, '109', 'Hidrolavadora Eléctrica ', 'vistas/img/productos/109/769.jpg', 14, 2600, 3640, 6, '2025-05-10 20:33:07'),
(10, 1, '110', 'Hidrolavadora Gasolina', 'vistas/img/productos/110/582.jpg', 17, 2210, 3094, 3, '2025-05-10 20:33:07'),
(11, 1, '111', 'Motobomba a Gasolina', 'vistas/img/productos/default/anonymous.png', 19, 2860, 4004, 1, '2025-05-10 20:33:07'),
(12, 1, '112', 'Motobomba El?ctrica', 'vistas/img/productos/default/anonymous.png', 19, 2400, 3360, 1, '2025-05-10 20:33:07'),
(13, 1, '113', 'Sierra Circular ', 'vistas/img/productos/default/anonymous.png', 19, 1100, 1540, 1, '2025-05-10 20:33:07'),
(14, 1, '114', 'Disco de tugsteno para Sierra circular', 'vistas/img/productos/default/anonymous.png', 19, 4500, 6300, 1, '2025-05-10 20:33:07'),
(15, 1, '115', 'Soldador Electrico ', 'vistas/img/productos/default/anonymous.png', 19, 1980, 2772, 1, '2025-05-10 20:33:07'),
(16, 1, '116', 'Careta para Soldador', 'vistas/img/productos/default/anonymous.png', 19, 4200, 5880, 1, '2025-05-10 20:33:07'),
(17, 1, '117', 'Torre de iluminacion ', 'vistas/img/productos/default/anonymous.png', 19, 1800, 2520, 1, '2025-05-10 20:33:07'),
(18, 2, '201', 'Martillo Demoledor de Piso 110V', 'vistas/img/productos/default/anonymous.png', 19, 5600, 7840, 1, '2025-05-10 20:33:07'),
(19, 2, '202', 'Muela o cincel martillo demoledor piso', 'vistas/img/productos/default/anonymous.png', 19, 9600, 13440, 1, '2025-05-10 20:33:07'),
(20, 2, '203', 'Taladro Demoledor de muro 110V', 'vistas/img/productos/default/anonymous.png', 19, 3850, 5390, 1, '2025-05-10 20:33:07'),
(21, 2, '204', 'Muela o cincel martillo demoledor muro', 'vistas/img/productos/default/anonymous.png', 19, 9600, 13440, 1, '2025-05-10 20:33:07'),
(22, 2, '205', 'Taladro Percutor de 1/2 Madera y Metal', 'vistas/img/productos/default/anonymous.png', 19, 8000, 11200, 1, '2025-05-10 20:33:07'),
(23, 2, '206', 'Taladro Percutor SDS Plus 110V', 'vistas/img/productos/default/anonymous.png', 19, 3900, 5460, 1, '2025-05-10 20:33:07'),
(24, 2, '207', 'Taladro Percutor SDS Max 110V (Mineria)', 'vistas/img/productos/default/anonymous.png', 19, 4600, 6440, 1, '2025-05-10 20:33:07'),
(25, 3, '301', 'Andamio colgante', 'vistas/img/productos/default/anonymous.png', 19, 1440, 2016, 1, '2025-05-10 20:33:07'),
(26, 3, '302', 'Distanciador andamio colgante', 'vistas/img/productos/default/anonymous.png', 19, 1600, 2240, 1, '2025-05-10 20:33:07'),
(27, 3, '303', 'Marco andamio modular ', 'vistas/img/productos/default/anonymous.png', 19, 900, 1260, 1, '2025-05-10 20:33:07'),
(28, 3, '304', 'Marco andamio tijera', 'vistas/img/productos/default/anonymous.png', 19, 100, 140, 1, '2025-05-10 20:33:07'),
(29, 3, '305', 'Tijera para andamio', 'vistas/img/productos/default/anonymous.png', 19, 162, 226, 1, '2025-05-10 20:33:07'),
(30, 3, '306', 'Escalera interna para andamio', 'vistas/img/productos/default/anonymous.png', 19, 270, 378, 1, '2025-05-10 20:33:07'),
(31, 3, '307', 'Pasamanos de seguridad', 'vistas/img/productos/default/anonymous.png', 19, 75, 105, 1, '2025-05-10 20:33:07'),
(32, 3, '308', 'Rueda giratoria para andamio', 'vistas/img/productos/default/anonymous.png', 19, 168, 235, 1, '2025-05-10 20:33:07'),
(33, 3, '309', 'Arnes de seguridad', 'vistas/img/productos/default/anonymous.png', 19, 1750, 2450, 1, '2025-05-10 20:33:07'),
(34, 3, '310', 'Eslinga para arnes', 'vistas/img/productos/default/anonymous.png', 19, 175, 245, 1, '2025-05-10 20:33:07'),
(35, 3, '311', 'Plataforma Met?lica', 'vistas/img/productos/default/anonymous.png', 19, 420, 588, 1, '2025-05-10 20:33:07'),
(36, 4, '401', 'Planta Electrica Diesel 6 Kva', 'vistas/img/productos/default/anonymous.png', 19, 3500, 4900, 1, '2025-05-10 20:33:07'),
(37, 4, '402', 'Planta Electrica Diesel 10 Kva', 'vistas/img/productos/default/anonymous.png', 19, 3550, 4970, 1, '2025-05-10 20:33:07'),
(38, 4, '403', 'Planta Electrica Diesel 20 Kva', 'vistas/img/productos/default/anonymous.png', 19, 3600, 5040, 1, '2025-05-10 20:33:07'),
(39, 4, '404', 'Planta Electrica Diesel 30 Kva', 'vistas/img/productos/default/anonymous.png', 19, 3650, 5110, 1, '2025-05-10 20:33:07'),
(40, 4, '405', 'Planta Electrica Diesel 60 Kva', 'vistas/img/productos/default/anonymous.png', 19, 3700, 5180, 1, '2025-05-10 20:33:07'),
(41, 4, '406', 'Planta Electrica Diesel 75 Kva', 'vistas/img/productos/default/anonymous.png', 19, 3750, 5250, 1, '2025-05-10 20:33:06'),
(42, 4, '407', 'Planta Electrica Diesel 100 Kva', 'vistas/img/productos/default/anonymous.png', 19, 3800, 5320, 1, '2025-05-10 20:33:06'),
(43, 4, '408', 'Planta Electrica Diesel 120 Kva', 'vistas/img/productos/default/anonymous.png', 19, 3850, 5390, 1, '2025-05-10 20:33:06'),
(44, 5, '501', 'Escalera de Tijera Aluminio ', 'vistas/img/productos/default/anonymous.png', 19, 350, 490, 1, '2025-05-10 20:33:06'),
(45, 5, '502', 'Extension Electrica ', 'vistas/img/productos/default/anonymous.png', 19, 370, 518, 1, '2025-05-10 20:33:06'),
(46, 5, '503', 'Gato tensor', 'vistas/img/productos/default/anonymous.png', 19, 380, 532, 1, '2025-05-10 20:33:06'),
(47, 5, '504', 'Lamina Cubre Brecha ', 'vistas/img/productos/default/anonymous.png', 19, 380, 532, 1, '2025-05-10 20:33:06'),
(48, 5, '505', 'Llave de Tubo', 'vistas/img/productos/default/anonymous.png', 19, 480, 672, 1, '2025-05-10 20:33:06'),
(49, 5, '506', 'Manila por Metro', 'vistas/img/productos/default/anonymous.png', 19, 600, 840, 1, '2025-05-10 20:33:06'),
(50, 5, '507', 'Polea 2 canales', 'vistas/img/productos/default/anonymous.png', 19, 900, 1260, 1, '2025-05-10 20:33:06'),
(51, 5, '508', 'Tensor', 'vistas/img/productos/508/500.jpg', 18, 100, 140, 2, '2025-05-10 20:33:06'),
(52, 5, '509', 'Bascula ', 'vistas/img/productos/509/878.jpg', 11, 130, 182, 9, '2025-05-10 20:33:06'),
(53, 5, '510', 'Bomba Hidrostatica', 'vistas/img/productos/510/870.jpg', 7, 770, 1078, 13, '2025-05-10 20:33:06'),
(54, 5, '511', 'Chapeta', 'vistas/img/productos/511/239.jpg', 15, 660, 924, 5, '2025-05-10 20:33:06'),
(55, 5, '512', 'Cilindro muestra de concreto', 'vistas/img/productos/512/266.jpg', 15, 400, 560, 5, '2025-05-10 20:33:06'),
(56, 5, '513', 'Cizalla de Palanca', 'vistas/img/productos/513/445.jpg', 2, 450, 630, 18, '2025-05-10 20:33:06'),
(57, 5, '514', 'Cizalla de Tijera', 'vistas/img/productos/514/249.jpg', 18, 580, 812, 15, '2025-05-10 20:33:06'),
(58, 5, '515', 'Coche llanta neumatica', 'vistas/img/productos/515/174.jpg', 12, 420, 588, 10, '2025-05-10 20:33:06'),
(59, 5, '516', 'Cono slump', 'vistas/img/productos/516/228.jpg', 8, 140, 196, 16, '2025-05-10 20:33:06'),
(60, 5, '517', 'Cortadora de Baldosin', 'vistas/img/productos/517/746.jpg', 1, 930, 1302, 23, '2025-05-10 20:33:06');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `usuarios`
--

CREATE TABLE `usuarios` (
  `id` int(11) NOT NULL,
  `nombre` text NOT NULL,
  `usuario` text NOT NULL,
  `password` text NOT NULL,
  `perfil` text NOT NULL,
  `foto` text NOT NULL,
  `estado` int(11) NOT NULL,
  `ultimo_login` datetime NOT NULL,
  `fecha` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

--
-- Volcado de datos para la tabla `usuarios`
--

INSERT INTO `usuarios` (`id`, `nombre`, `usuario`, `password`, `perfil`, `foto`, `estado`, `ultimo_login`, `fecha`) VALUES
(1, 'Administrador', 'admin', '$2a$07$asxx54ahjppf45sd87a5auXBm1Vr2M1NV5t/zNQtGHGpS5fFirrbG', 'Administrador', 'vistas/img/usuarios/admin/191.jpg', 1, '2025-05-12 17:35:16', '2025-05-12 21:35:16'),
(57, 'Juan Fernando Urrego', 'juan', '$2a$07$asxx54ahjppf45sd87a5auwRi.z6UsW7kVIpm0CUEuCpmsvT2sG6O', 'Vendedor', 'vistas/img/usuarios/juan/461.jpg', 1, '2018-02-06 16:58:50', '2018-02-06 21:58:50'),
(58, 'Julio Gómez', 'julio', '$2a$07$asxx54ahjppf45sd87a5auQhldmFjGsrgUipGlmQgDAcqevQZSAAC', 'Especial', 'vistas/img/usuarios/julio/100.png', 1, '2018-02-06 17:09:22', '2018-02-06 22:09:22'),
(59, 'Ana Gonzalez', 'ana', '$2a$07$asxx54ahjppf45sd87a5auLd2AxYsA/2BbmGKNk2kMChC3oj7V0Ca', 'Vendedor', 'vistas/img/usuarios/ana/260.png', 1, '2017-12-26 19:21:40', '2017-12-27 00:21:40'),
(60, 'Edgar De la Rosa', 'edgar1234', '$2y$10$ghwJ3inX.etVSD8p91Dnqe0dtr0Y0ynurPG/0x4GKf13m/ezAzrLC', 'Administrador', '', 1, '2025-05-06 20:28:15', '2025-05-07 00:28:15');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `ventas`
--

CREATE TABLE `ventas` (
  `id` int(11) NOT NULL,
  `codigo` int(11) NOT NULL,
  `id_cliente` int(11) NOT NULL,
  `id_vendedor` int(11) NOT NULL,
  `productos` text NOT NULL,
  `impuesto` float NOT NULL,
  `neto` float NOT NULL,
  `total` float NOT NULL,
  `metodo_pago` text NOT NULL,
  `fecha` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

--
-- Volcado de datos para la tabla `ventas`
--

INSERT INTO `ventas` (`id`, `codigo`, `id_cliente`, `id_vendedor`, `productos`, `impuesto`, `neto`, `total`, `metodo_pago`, `fecha`) VALUES
(17, 10001, 3, 1, '[{\"id\":\"1\",\"descripcion\":\"Aspiradora Industrial \",\"cantidad\":\"2\",\"stock\":\"13\",\"precio\":\"1200\",\"total\":\"2400\"},{\"id\":\"2\",\"descripcion\":\"Plato Flotante para Allanadora\",\"cantidad\":\"2\",\"stock\":\"7\",\"precio\":\"6300\",\"total\":\"12600\"},{\"id\":\"3\",\"descripcion\":\"Compresor de Aire para pintura\",\"cantidad\":\"1\",\"stock\":\"19\",\"precio\":\"4200\",\"total\":\"4200\"}]', 3648, 19200, 22848, 'Efectivo', '2018-02-02 01:11:04'),
(18, 10002, 4, 59, '[{\"id\":\"5\",\"descripcion\":\"Cortadora de Piso sin Disco \",\"cantidad\":\"2\",\"stock\":\"18\",\"precio\":\"2156\",\"total\":\"4312\"},{\"id\":\"4\",\"descripcion\":\"Cortadora de Adobe sin Disco \",\"cantidad\":\"1\",\"stock\":\"19\",\"precio\":\"5600\",\"total\":\"5600\"},{\"id\":\"6\",\"descripcion\":\"Disco Punta Diamante \",\"cantidad\":\"1\",\"stock\":\"19\",\"precio\":\"1540\",\"total\":\"1540\"},{\"id\":\"7\",\"descripcion\":\"Extractor de Aire \",\"cantidad\":\"1\",\"stock\":\"19\",\"precio\":\"2156\",\"total\":\"2156\"}]', 2585.52, 13608, 16193.5, 'TC-34346346346', '2018-02-02 14:57:20'),
(19, 10003, 5, 59, '[{\"id\":\"8\",\"descripcion\":\"Guadañadora \",\"cantidad\":\"1\",\"stock\":\"19\",\"precio\":\"2156\",\"total\":\"2156\"},{\"id\":\"9\",\"descripcion\":\"Hidrolavadora Eléctrica \",\"cantidad\":\"1\",\"stock\":\"19\",\"precio\":\"3640\",\"total\":\"3640\"},{\"id\":\"7\",\"descripcion\":\"Extractor de Aire \",\"cantidad\":\"1\",\"stock\":\"18\",\"precio\":\"2156\",\"total\":\"2156\"}]', 1510.88, 7952, 9462.88, 'Efectivo', '2018-01-18 14:57:40'),
(20, 10004, 5, 59, '[{\"id\":\"3\",\"descripcion\":\"Compresor de Aire para pintura\",\"cantidad\":\"5\",\"stock\":\"14\",\"precio\":\"4200\",\"total\":\"21000\"},{\"id\":\"4\",\"descripcion\":\"Cortadora de Adobe sin Disco \",\"cantidad\":\"1\",\"stock\":\"18\",\"precio\":\"5600\",\"total\":\"5600\"},{\"id\":\"5\",\"descripcion\":\"Cortadora de Piso sin Disco \",\"cantidad\":\"1\",\"stock\":\"17\",\"precio\":\"2156\",\"total\":\"2156\"}]', 5463.64, 28756, 34219.6, 'TD-454475467567', '2018-01-25 14:58:09'),
(21, 10005, 6, 57, '[{\"id\":\"4\",\"descripcion\":\"Cortadora de Adobe sin Disco \",\"cantidad\":\"1\",\"stock\":\"17\",\"precio\":\"5600\",\"total\":\"5600\"},{\"id\":\"5\",\"descripcion\":\"Cortadora de Piso sin Disco \",\"cantidad\":\"1\",\"stock\":\"16\",\"precio\":\"2156\",\"total\":\"2156\"},{\"id\":\"3\",\"descripcion\":\"Compresor de Aire para pintura\",\"cantidad\":\"5\",\"stock\":\"9\",\"precio\":\"4200\",\"total\":\"21000\"}]', 5463.64, 28756, 34219.6, 'TC-6756856867', '2018-01-09 14:59:07'),
(22, 10006, 10, 1, '[{\"id\":\"3\",\"descripcion\":\"Compresor de Aire para pintura\",\"cantidad\":\"1\",\"stock\":\"8\",\"precio\":\"4200\",\"total\":\"4200\"},{\"id\":\"4\",\"descripcion\":\"Cortadora de Adobe sin Disco \",\"cantidad\":\"1\",\"stock\":\"16\",\"precio\":\"5600\",\"total\":\"5600\"},{\"id\":\"5\",\"descripcion\":\"Cortadora de Piso sin Disco \",\"cantidad\":\"3\",\"stock\":\"13\",\"precio\":\"2156\",\"total\":\"6468\"},{\"id\":\"6\",\"descripcion\":\"Disco Punta Diamante \",\"cantidad\":\"1\",\"stock\":\"18\",\"precio\":\"1540\",\"total\":\"1540\"}]', 3383.52, 17808, 21191.5, 'Efectivo', '2018-01-26 15:03:22'),
(23, 10007, 9, 1, '[{\"id\":\"6\",\"descripcion\":\"Disco Punta Diamante \",\"cantidad\":\"1\",\"stock\":\"17\",\"precio\":\"1540\",\"total\":\"1540\"},{\"id\":\"7\",\"descripcion\":\"Extractor de Aire \",\"cantidad\":\"1\",\"stock\":\"17\",\"precio\":\"2156\",\"total\":\"2156\"},{\"id\":\"8\",\"descripcion\":\"Guadañadora \",\"cantidad\":\"6\",\"stock\":\"13\",\"precio\":\"2156\",\"total\":\"12936\"},{\"id\":\"9\",\"descripcion\":\"Hidrolavadora Eléctrica \",\"cantidad\":\"1\",\"stock\":\"18\",\"precio\":\"3640\",\"total\":\"3640\"}]', 3851.68, 20272, 24123.7, 'TC-357547467346', '2017-11-30 15:03:53'),
(24, 10008, 12, 1, '[{\"id\":\"2\",\"descripcion\":\"Plato Flotante para Allanadora\",\"cantidad\":\"1\",\"stock\":\"6\",\"precio\":\"6300\",\"total\":\"6300\"},{\"id\":\"7\",\"descripcion\":\"Extractor de Aire \",\"cantidad\":\"5\",\"stock\":\"12\",\"precio\":\"2156\",\"total\":\"10780\"},{\"id\":\"6\",\"descripcion\":\"Disco Punta Diamante \",\"cantidad\":\"1\",\"stock\":\"16\",\"precio\":\"1540\",\"total\":\"1540\"},{\"id\":\"9\",\"descripcion\":\"Hidrolavadora Eléctrica \",\"cantidad\":\"1\",\"stock\":\"17\",\"precio\":\"3640\",\"total\":\"3640\"}]', 4229.4, 22260, 26489.4, 'TD-35745575', '2017-12-25 15:04:11'),
(25, 10009, 11, 1, '[{\"id\":\"10\",\"descripcion\":\"Hidrolavadora Gasolina\",\"cantidad\":\"1\",\"stock\":\"19\",\"precio\":\"3094\",\"total\":\"3094\"},{\"id\":\"9\",\"descripcion\":\"Hidrolavadora Eléctrica \",\"cantidad\":\"1\",\"stock\":\"16\",\"precio\":\"3640\",\"total\":\"3640\"},{\"id\":\"6\",\"descripcion\":\"Disco Punta Diamante \",\"cantidad\":\"1\",\"stock\":\"15\",\"precio\":\"1540\",\"total\":\"1540\"}]', 1572.06, 8274, 9846.06, 'TD-5745745745', '2017-08-15 15:04:38'),
(26, 10010, 8, 1, '[{\"id\":\"9\",\"descripcion\":\"Hidrolavadora Eléctrica \",\"cantidad\":\"1\",\"stock\":\"15\",\"precio\":\"3640\",\"total\":\"3640\"},{\"id\":\"10\",\"descripcion\":\"Hidrolavadora Gasolina\",\"cantidad\":\"1\",\"stock\":\"18\",\"precio\":\"3094\",\"total\":\"3094\"}]', 1279.46, 6734, 8013.46, 'Efectivo', '2017-12-07 15:05:09'),
(27, 10011, 7, 1, '[{\"id\":\"60\",\"descripcion\":\"Cortadora de Baldosin\",\"cantidad\":\"1\",\"stock\":\"19\",\"precio\":\"1302\",\"total\":\"1302\"},{\"id\":\"59\",\"descripcion\":\"Cono slump\",\"cantidad\":\"1\",\"stock\":\"19\",\"precio\":\"196\",\"total\":\"196\"},{\"id\":\"58\",\"descripcion\":\"Coche llanta neumatica\",\"cantidad\":\"1\",\"stock\":\"19\",\"precio\":\"588\",\"total\":\"588\"},{\"id\":\"57\",\"descripcion\":\"Cizalla de Tijera\",\"cantidad\":\"1\",\"stock\":\"19\",\"precio\":\"812\",\"total\":\"812\"}]', 550.62, 2898, 3448.62, 'Efectivo', '2017-12-25 22:23:38'),
(28, 10012, 12, 57, '[{\"id\":\"59\",\"descripcion\":\"Cono slump\",\"cantidad\":\"1\",\"stock\":\"18\",\"precio\":\"196\",\"total\":\"196\"},{\"id\":\"58\",\"descripcion\":\"Coche llanta neumatica\",\"cantidad\":\"1\",\"stock\":\"18\",\"precio\":\"588\",\"total\":\"588\"},{\"id\":\"54\",\"descripcion\":\"Chapeta\",\"cantidad\":\"1\",\"stock\":\"19\",\"precio\":\"924\",\"total\":\"924\"},{\"id\":\"53\",\"descripcion\":\"Bomba Hidrostatica\",\"cantidad\":\"1\",\"stock\":\"19\",\"precio\":\"1078\",\"total\":\"1078\"}]', 529.34, 2786, 3315.34, 'TC-3545235235', '2017-12-25 22:24:24'),
(29, 10013, 11, 57, '[{\"id\":\"54\",\"descripcion\":\"Chapeta\",\"cantidad\":\"1\",\"stock\":\"18\",\"precio\":\"924\",\"total\":\"924\"},{\"id\":\"59\",\"descripcion\":\"Cono slump\",\"cantidad\":\"1\",\"stock\":\"17\",\"precio\":\"196\",\"total\":\"196\"},{\"id\":\"60\",\"descripcion\":\"Cortadora de Baldosin\",\"cantidad\":\"5\",\"stock\":\"14\",\"precio\":\"1302\",\"total\":\"6510\"}]', 1449.7, 7630, 9079.7, 'TC-425235235235', '2017-12-26 22:24:50'),
(30, 10014, 10, 57, '[{\"id\":\"59\",\"descripcion\":\"Cono slump\",\"cantidad\":\"1\",\"stock\":\"16\",\"precio\":\"196\",\"total\":\"196\"},{\"id\":\"54\",\"descripcion\":\"Chapeta\",\"cantidad\":\"1\",\"stock\":\"17\",\"precio\":\"924\",\"total\":\"924\"},{\"id\":\"53\",\"descripcion\":\"Bomba Hidrostatica\",\"cantidad\":\"10\",\"stock\":\"9\",\"precio\":\"1078\",\"total\":\"10780\"}]', 2261, 11900, 14161, 'Efectivo', '2017-12-26 22:25:09'),
(31, 10015, 9, 57, '[{\"id\":\"57\",\"descripcion\":\"Cizalla de Tijera\",\"cantidad\":\"3\",\"stock\":\"16\",\"precio\":\"812\",\"total\":\"2436\"}]', 462.84, 2436, 2898.84, 'Efectivo', '2017-12-26 22:25:33'),
(32, 10016, 8, 57, '[{\"id\":\"58\",\"descripcion\":\"Coche llanta neumatica\",\"cantidad\":\"1\",\"stock\":\"17\",\"precio\":\"588\",\"total\":\"588\"},{\"id\":\"57\",\"descripcion\":\"Cizalla de Tijera\",\"cantidad\":\"5\",\"stock\":\"11\",\"precio\":\"812\",\"total\":\"4060\"},{\"id\":\"56\",\"descripcion\":\"Cizalla de Palanca\",\"cantidad\":\"1\",\"stock\":\"19\",\"precio\":\"630\",\"total\":\"630\"}]', 1002.82, 5278, 6280.82, 'TD-4523523523', '2017-12-26 22:25:55'),
(33, 10017, 7, 57, '[{\"id\":\"57\",\"descripcion\":\"Cizalla de Tijera\",\"cantidad\":\"4\",\"stock\":\"7\",\"precio\":\"812\",\"total\":\"3248\"},{\"id\":\"52\",\"descripcion\":\"Bascula \",\"cantidad\":\"3\",\"stock\":\"17\",\"precio\":\"182\",\"total\":\"546\"},{\"id\":\"55\",\"descripcion\":\"Cilindro muestra de concreto\",\"cantidad\":\"2\",\"stock\":\"18\",\"precio\":\"560\",\"total\":\"1120\"},{\"id\":\"56\",\"descripcion\":\"Cizalla de Palanca\",\"cantidad\":\"1\",\"stock\":\"18\",\"precio\":\"630\",\"total\":\"630\"}]', 1053.36, 5544, 6597.36, 'Efectivo', '2017-12-26 22:26:28'),
(34, 10018, 6, 57, '[{\"id\":\"51\",\"descripcion\":\"Tensor\",\"cantidad\":\"1\",\"stock\":\"19\",\"precio\":\"140\",\"total\":\"140\"},{\"id\":\"52\",\"descripcion\":\"Bascula \",\"cantidad\":\"5\",\"stock\":\"12\",\"precio\":\"182\",\"total\":\"910\"},{\"id\":\"53\",\"descripcion\":\"Bomba Hidrostatica\",\"cantidad\":\"1\",\"stock\":\"8\",\"precio\":\"1078\",\"total\":\"1078\"}]', 404.32, 2128, 2532.32, 'Efectivo', '2017-12-26 22:26:51'),
(35, 10019, 5, 57, '[{\"id\":\"56\",\"descripcion\":\"Cizalla de Palanca\",\"cantidad\":\"15\",\"stock\":\"3\",\"precio\":\"630\",\"total\":\"9450\"},{\"id\":\"55\",\"descripcion\":\"Cilindro muestra de concreto\",\"cantidad\":\"1\",\"stock\":\"17\",\"precio\":\"560\",\"total\":\"560\"}]', 1901.9, 10010, 11911.9, 'Efectivo', '2017-12-26 22:27:13'),
(36, 10020, 4, 57, '[{\"id\":\"55\",\"descripcion\":\"Cilindro muestra de concreto\",\"cantidad\":\"1\",\"stock\":\"16\",\"precio\":\"560\",\"total\":\"560\"},{\"id\":\"54\",\"descripcion\":\"Chapeta\",\"cantidad\":\"1\",\"stock\":\"16\",\"precio\":\"924\",\"total\":\"924\"}]', 281.96, 1484, 1765.96, 'TC-46346346346', '2017-12-26 22:27:42'),
(37, 10021, 3, 1, '[{\"id\":\"60\",\"descripcion\":\"Cortadora de Baldosin\",\"cantidad\":\"1\",\"stock\":\"13\",\"precio\":\"1302\",\"total\":\"1302\"},{\"id\":\"59\",\"descripcion\":\"Cono slump\",\"cantidad\":\"1\",\"stock\":\"15\",\"precio\":\"196\",\"total\":\"196\"}]', 149.8, 1498, 1647.8, 'Efectivo', '2018-02-06 22:47:02'),
(38, 10022, 4, 1, '[{\"id\":\"60\",\"descripcion\":\"Cortadora de Baldosin\",\"cantidad\":\"1\",\"stock\":\"6\",\"precio\":\"1302\",\"total\":\"1302\"},{\"id\":\"59\",\"descripcion\":\"Cono slump\",\"cantidad\":\"1\",\"stock\":\"12\",\"precio\":\"196\",\"total\":\"196\"}]', 269.64, 1498, 1767.64, 'Efectivo', '2025-05-10 14:43:08'),
(39, 10023, 3, 1, '[{\"id\":\"60\",\"descripcion\":\"Cortadora de Baldosin\",\"cantidad\":\"1\",\"stock\":\"5\",\"precio\":\"1302\",\"total\":\"1302\"},{\"id\":\"59\",\"descripcion\":\"Cono slump\",\"cantidad\":\"1\",\"stock\":\"11\",\"precio\":\"196\",\"total\":\"196\"},{\"id\":\"58\",\"descripcion\":\"Coche llanta neumatica\",\"cantidad\":\"1\",\"stock\":\"15\",\"precio\":\"588\",\"total\":\"588\"}]', 375.48, 2086, 2461.48, 'Efectivo', '2025-05-10 14:44:24'),
(40, 10024, 13, 1, '[{\"id\":\"60\",\"descripcion\":\"Cortadora de Baldosin\",\"cantidad\":\"1\",\"stock\":\"4\",\"precio\":\"1302\",\"total\":\"1302\"},{\"id\":\"59\",\"descripcion\":\"Cono slump\",\"cantidad\":\"1\",\"stock\":\"10\",\"precio\":\"196\",\"total\":\"196\"},{\"id\":\"58\",\"descripcion\":\"Coche llanta neumatica\",\"cantidad\":\"1\",\"stock\":\"14\",\"precio\":\"588\",\"total\":\"588\"}]', 375.48, 2086, 2461.48, 'Efectivo', '2025-05-10 15:13:10'),
(41, 10025, 13, 1, '[{\"id\":\"60\",\"descripcion\":\"Cortadora de Baldosin\",\"cantidad\":\"1\",\"stock\":\"3\",\"precio\":\"1302\",\"total\":\"1302\"},{\"id\":\"59\",\"descripcion\":\"Cono slump\",\"cantidad\":\"1\",\"stock\":\"9\",\"precio\":\"196\",\"total\":\"196\"},{\"id\":\"58\",\"descripcion\":\"Coche llanta neumatica\",\"cantidad\":\"1\",\"stock\":\"13\",\"precio\":\"588\",\"total\":\"588\"},{\"id\":\"57\",\"descripcion\":\"Cizalla de Tijera\",\"cantidad\":\"1\",\"stock\":\"19\",\"precio\":\"812\",\"total\":\"812\"}]', 521.64, 2898, 3419.64, 'Efectivo', '2025-05-10 15:22:37'),
(43, 10027, 14, 1, '[{\"id\":\"60\",\"descripcion\":\"Cortadora de Baldosin\",\"cantidad\":\"1\",\"stock\":\"1\",\"precio\":\"1302\",\"total\":\"1302\"}]', 234.36, 1302, 1536.36, 'Efectivo', '2025-05-10 20:28:44'),
(44, 10028, 13, 1, '[{\"id\":\"60\",\"descripcion\":\"Cortadora de Baldosin\",\"cantidad\":\"1\",\"stock\":\"1\",\"precio\":\"1302\",\"total\":\"1302\"},{\"id\":\"59\",\"descripcion\":\"Cono slump\",\"cantidad\":\"1\",\"stock\":\"8\",\"precio\":\"196\",\"total\":\"196\"},{\"id\":\"58\",\"descripcion\":\"Coche llanta neumatica\",\"cantidad\":\"1\",\"stock\":\"12\",\"precio\":\"588\",\"total\":\"588\"},{\"id\":\"57\",\"descripcion\":\"Cizalla de Tijera\",\"cantidad\":\"1\",\"stock\":\"18\",\"precio\":\"812\",\"total\":\"812\"},{\"id\":\"56\",\"descripcion\":\"Cizalla de Palanca\",\"cantidad\":\"1\",\"stock\":\"2\",\"precio\":\"630\",\"total\":\"630\"},{\"id\":\"55\",\"descripcion\":\"Cilindro muestra de concreto\",\"cantidad\":\"1\",\"stock\":\"15\",\"precio\":\"560\",\"total\":\"560\"},{\"id\":\"54\",\"descripcion\":\"Chapeta\",\"cantidad\":\"1\",\"stock\":\"15\",\"precio\":\"924\",\"total\":\"924\"},{\"id\":\"53\",\"descripcion\":\"Bomba Hidrostatica\",\"cantidad\":\"1\",\"stock\":\"7\",\"precio\":\"1078\",\"total\":\"1078\"},{\"id\":\"52\",\"descripcion\":\"Bascula \",\"cantidad\":\"1\",\"stock\":\"11\",\"precio\":\"182\",\"total\":\"182\"},{\"id\":\"51\",\"descripcion\":\"Tensor\",\"cantidad\":\"1\",\"stock\":\"18\",\"precio\":\"140\",\"total\":\"140\"},{\"id\":\"50\",\"descripcion\":\"Polea 2 canales\",\"cantidad\":\"1\",\"stock\":\"19\",\"precio\":\"1260\",\"total\":\"1260\"},{\"id\":\"49\",\"descripcion\":\"Manila por Metro\",\"cantidad\":\"1\",\"stock\":\"19\",\"precio\":\"840\",\"total\":\"840\"},{\"id\":\"48\",\"descripcion\":\"Llave de Tubo\",\"cantidad\":\"1\",\"stock\":\"19\",\"precio\":\"672\",\"total\":\"672\"},{\"id\":\"47\",\"descripcion\":\"Lamina Cubre Brecha \",\"cantidad\":\"1\",\"stock\":\"19\",\"precio\":\"532\",\"total\":\"532\"},{\"id\":\"46\",\"descripcion\":\"Gato tensor\",\"cantidad\":\"1\",\"stock\":\"19\",\"precio\":\"532\",\"total\":\"532\"},{\"id\":\"45\",\"descripcion\":\"Extension Electrica \",\"cantidad\":\"1\",\"stock\":\"19\",\"precio\":\"518\",\"total\":\"518\"},{\"id\":\"44\",\"descripcion\":\"Escalera de Tijera Aluminio \",\"cantidad\":\"1\",\"stock\":\"19\",\"precio\":\"490\",\"total\":\"490\"},{\"id\":\"43\",\"descripcion\":\"Planta Electrica Diesel 120 Kva\",\"cantidad\":\"1\",\"stock\":\"19\",\"precio\":\"5390\",\"total\":\"5390\"},{\"id\":\"42\",\"descripcion\":\"Planta Electrica Diesel 100 Kva\",\"cantidad\":\"1\",\"stock\":\"19\",\"precio\":\"5320\",\"total\":\"5320\"},{\"id\":\"41\",\"descripcion\":\"Planta Electrica Diesel 75 Kva\",\"cantidad\":\"1\",\"stock\":\"19\",\"precio\":\"5250\",\"total\":\"5250\"},{\"id\":\"40\",\"descripcion\":\"Planta Electrica Diesel 60 Kva\",\"cantidad\":\"1\",\"stock\":\"19\",\"precio\":\"5180\",\"total\":\"5180\"},{\"id\":\"39\",\"descripcion\":\"Planta Electrica Diesel 30 Kva\",\"cantidad\":\"1\",\"stock\":\"19\",\"precio\":\"5110\",\"total\":\"5110\"},{\"id\":\"38\",\"descripcion\":\"Planta Electrica Diesel 20 Kva\",\"cantidad\":\"1\",\"stock\":\"19\",\"precio\":\"5040\",\"total\":\"5040\"},{\"id\":\"37\",\"descripcion\":\"Planta Electrica Diesel 10 Kva\",\"cantidad\":\"1\",\"stock\":\"19\",\"precio\":\"4970\",\"total\":\"4970\"},{\"id\":\"36\",\"descripcion\":\"Planta Electrica Diesel 6 Kva\",\"cantidad\":\"1\",\"stock\":\"19\",\"precio\":\"4900\",\"total\":\"4900\"},{\"id\":\"35\",\"descripcion\":\"Plataforma Met?lica\",\"cantidad\":\"1\",\"stock\":\"19\",\"precio\":\"588\",\"total\":\"588\"},{\"id\":\"34\",\"descripcion\":\"Eslinga para arnes\",\"cantidad\":\"1\",\"stock\":\"19\",\"precio\":\"245\",\"total\":\"245\"},{\"id\":\"33\",\"descripcion\":\"Arnes de seguridad\",\"cantidad\":\"1\",\"stock\":\"19\",\"precio\":\"2450\",\"total\":\"2450\"},{\"id\":\"32\",\"descripcion\":\"Rueda giratoria para andamio\",\"cantidad\":\"1\",\"stock\":\"19\",\"precio\":\"235\",\"total\":\"235\"},{\"id\":\"31\",\"descripcion\":\"Pasamanos de seguridad\",\"cantidad\":\"1\",\"stock\":\"19\",\"precio\":\"105\",\"total\":\"105\"},{\"id\":\"30\",\"descripcion\":\"Escalera interna para andamio\",\"cantidad\":\"1\",\"stock\":\"19\",\"precio\":\"378\",\"total\":\"378\"},{\"id\":\"29\",\"descripcion\":\"Tijera para andamio\",\"cantidad\":\"1\",\"stock\":\"19\",\"precio\":\"226\",\"total\":\"226\"},{\"id\":\"28\",\"descripcion\":\"Marco andamio tijera\",\"cantidad\":\"1\",\"stock\":\"19\",\"precio\":\"140\",\"total\":\"140\"},{\"id\":\"27\",\"descripcion\":\"Marco andamio modular \",\"cantidad\":\"1\",\"stock\":\"19\",\"precio\":\"1260\",\"total\":\"1260\"},{\"id\":\"26\",\"descripcion\":\"Distanciador andamio colgante\",\"cantidad\":\"1\",\"stock\":\"19\",\"precio\":\"2240\",\"total\":\"2240\"},{\"id\":\"25\",\"descripcion\":\"Andamio colgante\",\"cantidad\":\"1\",\"stock\":\"19\",\"precio\":\"2016\",\"total\":\"2016\"},{\"id\":\"24\",\"descripcion\":\"Taladro Percutor SDS Max 110V (Mineria)\",\"cantidad\":\"1\",\"stock\":\"19\",\"precio\":\"6440\",\"total\":\"6440\"},{\"id\":\"23\",\"descripcion\":\"Taladro Percutor SDS Plus 110V\",\"cantidad\":\"1\",\"stock\":\"19\",\"precio\":\"5460\",\"total\":\"5460\"},{\"id\":\"22\",\"descripcion\":\"Taladro Percutor de 1/2 Madera y Metal\",\"cantidad\":\"1\",\"stock\":\"19\",\"precio\":\"11200\",\"total\":\"11200\"},{\"id\":\"21\",\"descripcion\":\"Muela o cincel martillo demoledor muro\",\"cantidad\":\"1\",\"stock\":\"19\",\"precio\":\"13440\",\"total\":\"13440\"},{\"id\":\"20\",\"descripcion\":\"Taladro Demoledor de muro 110V\",\"cantidad\":\"1\",\"stock\":\"19\",\"precio\":\"5390\",\"total\":\"5390\"},{\"id\":\"19\",\"descripcion\":\"Muela o cincel martillo demoledor piso\",\"cantidad\":\"1\",\"stock\":\"19\",\"precio\":\"13440\",\"total\":\"13440\"},{\"id\":\"18\",\"descripcion\":\"Martillo Demoledor de Piso 110V\",\"cantidad\":\"1\",\"stock\":\"19\",\"precio\":\"7840\",\"total\":\"7840\"},{\"id\":\"17\",\"descripcion\":\"Torre de iluminacion \",\"cantidad\":\"1\",\"stock\":\"19\",\"precio\":\"2520\",\"total\":\"2520\"},{\"id\":\"16\",\"descripcion\":\"Careta para Soldador\",\"cantidad\":\"1\",\"stock\":\"19\",\"precio\":\"5880\",\"total\":\"5880\"},{\"id\":\"15\",\"descripcion\":\"Soldador Electrico \",\"cantidad\":\"1\",\"stock\":\"19\",\"precio\":\"2772\",\"total\":\"2772\"},{\"id\":\"14\",\"descripcion\":\"Disco de tugsteno para Sierra circular\",\"cantidad\":\"1\",\"stock\":\"19\",\"precio\":\"6300\",\"total\":\"6300\"},{\"id\":\"13\",\"descripcion\":\"Sierra Circular \",\"cantidad\":\"1\",\"stock\":\"19\",\"precio\":\"1540\",\"total\":\"1540\"},{\"id\":\"11\",\"descripcion\":\"Motobomba a Gasolina\",\"cantidad\":\"1\",\"stock\":\"19\",\"precio\":\"4004\",\"total\":\"4004\"},{\"id\":\"12\",\"descripcion\":\"Motobomba El?ctrica\",\"cantidad\":\"1\",\"stock\":\"19\",\"precio\":\"3360\",\"total\":\"3360\"},{\"id\":\"10\",\"descripcion\":\"Hidrolavadora Gasolina\",\"cantidad\":\"1\",\"stock\":\"17\",\"precio\":\"3094\",\"total\":\"3094\"},{\"id\":\"9\",\"descripcion\":\"Hidrolavadora Eléctrica \",\"cantidad\":\"1\",\"stock\":\"14\",\"precio\":\"3640\",\"total\":\"3640\"},{\"id\":\"8\",\"descripcion\":\"Guadañadora \",\"cantidad\":\"1\",\"stock\":\"12\",\"precio\":\"2156\",\"total\":\"2156\"},{\"id\":\"7\",\"descripcion\":\"Extractor de Aire \",\"cantidad\":\"1\",\"stock\":\"11\",\"precio\":\"2156\",\"total\":\"2156\"},{\"id\":\"6\",\"descripcion\":\"Disco Punta Diamante \",\"cantidad\":\"1\",\"stock\":\"14\",\"precio\":\"1540\",\"total\":\"1540\"},{\"id\":\"5\",\"descripcion\":\"Cortadora de Piso sin Disco \",\"cantidad\":\"1\",\"stock\":\"12\",\"precio\":\"2156\",\"total\":\"2156\"},{\"id\":\"4\",\"descripcion\":\"Cortadora de Adobe sin Disco \",\"cantidad\":\"1\",\"stock\":\"15\",\"precio\":\"5600\",\"total\":\"5600\"},{\"id\":\"3\",\"descripcion\":\"Compresor de Aire para pintura\",\"cantidad\":\"1\",\"stock\":\"7\",\"precio\":\"4200\",\"total\":\"4200\"},{\"id\":\"2\",\"descripcion\":\"Plato Flotante para Allanadora\",\"cantidad\":\"1\",\"stock\":\"5\",\"precio\":\"6300\",\"total\":\"6300\"},{\"id\":\"1\",\"descripcion\":\"Aspiradora Industrial \",\"cantidad\":\"1\",\"stock\":\"12\",\"precio\":\"1200\",\"total\":\"1200\"}]', 33106.9, 183927, 217034, 'Efectivo', '2025-05-10 20:33:07');

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `categorias`
--
ALTER TABLE `categorias`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `clientes`
--
ALTER TABLE `clientes`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `productos`
--
ALTER TABLE `productos`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `ventas`
--
ALTER TABLE `ventas`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `categorias`
--
ALTER TABLE `categorias`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT de la tabla `clientes`
--
ALTER TABLE `clientes`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT de la tabla `productos`
--
ALTER TABLE `productos`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=61;

--
-- AUTO_INCREMENT de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=61;

--
-- AUTO_INCREMENT de la tabla `ventas`
--
ALTER TABLE `ventas`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=45;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
