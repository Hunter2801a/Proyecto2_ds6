-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 30-06-2025 a las 20:24:35
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
-- Base de datos: `proy2`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `categorias`
--

CREATE TABLE `categorias` (
  `id` int(11) NOT NULL,
  `nombre` varchar(100) NOT NULL,
  `imagen` varchar(255) DEFAULT NULL,
  `fecha_creacion` datetime DEFAULT current_timestamp(),
  `fecha_actualizacion` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `categorias`
--

INSERT INTO `categorias` (`id`, `nombre`, `imagen`, `fecha_creacion`, `fecha_actualizacion`) VALUES
(5, 'Computadoras', 'image/img_categorias/68433cf633e18_laptopsypc.png', '2025-05-24 15:02:45', '2025-06-06 14:09:42'),
(6, 'Celulares', 'image/img_categorias/68433ce95d214_celulares.png', '2025-05-28 12:30:54', '2025-06-06 14:09:29'),
(9, 'Almacenamiento', 'image/img_categorias/6840dbbaee885_almacenamiento.png', '2025-06-04 18:49:56', '2025-06-04 18:50:18'),
(10, 'Accesorios de Computadora', 'image/img_categorias/6840de0f603de_mesientotriste.png', '2025-06-04 19:00:15', '2025-06-06 14:09:55'),
(11, 'Impresoras y Escaneres', 'image/img_categorias/6840de5d8a0ac_impresorasyescaneres.png', '2025-06-04 19:01:33', '2025-06-06 14:10:01'),
(12, 'Audio y sonido', 'image/img_categorias/684346f576a3b_Aysond.png', '2025-06-06 14:52:21', '2025-06-06 14:52:21'),
(13, 'Consolas y Videojuegos', 'image/img_categorias/6843473f22f77_consolas y videojuegos.png', '2025-06-06 14:52:48', '2025-06-06 14:53:35'),
(14, 'Hogar inteligente', 'image/img_categorias/6843475bd279a_hogarintel.png', '2025-06-06 14:54:03', '2025-06-06 14:54:03'),
(15, 'Monitores y pantallas', 'image/img_categorias/68434934ed490_pantallas.png', '2025-06-06 15:01:56', '2025-06-06 15:01:56'),
(16, 'Energia y Carga', 'image/img_categorias/6843495a44984_energiaYcarga.png', '2025-06-06 15:02:34', '2025-06-06 15:02:34');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `productos`
--

CREATE TABLE `productos` (
  `id` int(11) NOT NULL,
  `nombre` varchar(100) NOT NULL,
  `descripcion` text DEFAULT NULL,
  `precio` decimal(10,2) NOT NULL,
  `imagen` varchar(255) DEFAULT NULL,
  `categoria_id` int(11) NOT NULL,
  `stock` int(11) NOT NULL DEFAULT 0,
  `fecha_creacion` datetime DEFAULT current_timestamp(),
  `fecha_actualizacion` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `productos`
--

INSERT INTO `productos` (`id`, `nombre`, `descripcion`, `precio`, `imagen`, `categoria_id`, `stock`, `fecha_creacion`, `fecha_actualizacion`) VALUES
(1, 'ASUS 2801', 'computadora potente, tan potente que es inexistente', 800.00, 'image/img_productos/6832382d0716a_asusN56VJ.png', 5, 10, '2025-05-24 16:20:45', '2025-06-30 13:23:02'),
(2, 'HP victus', 'Laptop gaming victus más rapida que la mia', 900.00, 'image/img_productos/68433d8876a43_victus.png', 5, 10, '2025-05-28 12:32:11', '2025-06-30 13:23:02'),
(3, 'Huawei Matebook ', 'La mejor laptop al precio más caro', 1500.00, 'image/img_productos/683757079d94f_huawei-matebook.png', 5, 10, '2025-05-28 13:33:43', '2025-06-30 13:23:02'),
(4, 'Laptop BLU Dynamax', 'hecha con materiales de Elon Musk, esta pantalla es de un solo uso y se desecha, cómprala y notarás la diferencia', 1975.00, 'image/img_productos/68375789e263b_pcfuturo.png', 5, 10, '2025-05-28 13:35:53', '2025-06-30 13:23:02'),
(5, 'PC pro + turbo version', 'el mejor pc para los trabajos más delicados', 2000.00, 'image/img_productos/683757de4c171_rrrrr.png', 5, 10, '2025-05-28 13:37:18', '2025-06-30 13:23:02'),
(6, 'lenovo Astrum 5g', 'laptop lenovo, nueva en el mercado a partir del 2027, la calidad de video aumenta gracias a su RTX 9050.', 850.99, 'image/img_productos/683761580a4aa_lenovoPC.png', 5, 10, '2025-05-28 14:17:44', '2025-06-30 13:23:02'),
(8, 'HP pro desk ', 'esta computadora es... no se, preguntenle a chat GPT', 700.99, 'image/img_productos/6840d7bb937ae_hp pro desk.png', 5, 10, '2025-06-04 18:33:15', '2025-06-30 13:23:02'),
(9, 'Motorola G85 5G pro max 2025', '5G, 256GB, cámara dual 50MP + gran angular\r\nGran capacidad y velocidad con estilo moderno.', 299.99, 'image/img_productos/68432f6a48779_motorola.png', 6, 10, '2025-06-06 13:11:54', '2025-06-30 13:23:02'),
(10, 'Samsung Galaxy A54 5G', 'Pantalla Super AMOLED de 6.4\", cámara triple 50MP\r\nPotente, moderno y con conectividad 5G al alcance de tu mano.', 379.99, 'image/img_productos/68432fdc65cee_Galaxy-A54-5G.png', 6, 10, '2025-06-06 13:13:48', '2025-06-30 13:23:02'),
(11, 'iPhone 13', 'Chip A15 Bionic, doble cámara de 12MP, Face ID\r\nElegancia y rendimiento premium en un solo dispositivo.', 799.00, 'image/img_productos/684334d9ee00c_iphone-13.png', 6, 10, '2025-06-06 13:35:05', '2025-06-30 13:23:02'),
(12, 'Xiaomi Redmi Note 13 Pro', 'Pantalla AMOLED 120Hz, Snapdragon 7s Gen 2\r\nRendimiento fluido y pantalla impresionante para todo momento.', 349.99, 'image/img_productos/68433549dd16e_xiaomi13pro.png', 6, 10, '2025-06-06 13:36:57', '2025-06-30 13:23:02'),
(13, 'Seagate Expansion 1TB', 'Disco duro externo portátil USB 3.0\r\nLleva tus archivos a todas partes con seguridad y facilidad.', 49.99, 'image/img_productos/684337cb37bad_expancion1t.png', 9, 10, '2025-06-06 13:47:39', '2025-06-30 13:23:02'),
(14, 'WD My Passport 2TB', 'Diseño compacto, protección por contraseña. Tu información segura y siempre contigo.', 74.99, 'image/img_productos/684338003b60f_WD.png', 9, 10, '2025-06-06 13:48:32', '2025-06-30 13:23:02'),
(15, 'SanDisk Extreme SSD 1TB', 'Velocidad de lectura hasta 1050MB/s, resistente a golpes\r\nUltra rápido y resistente, ideal para los más exigentes.', 119.99, 'image/img_productos/68433872439b5_sandisk.png', 9, 10, '2025-06-06 13:50:26', '2025-06-30 13:23:02'),
(16, 'Kingston DataTraveler 128GB', 'USB 3.2, pequeño y confiable para uso diario\r\nSolución confiable para guardar y mover tus archivos con rapidez.', 18.50, 'image/img_productos/684338944f067_kingston.png', 9, 10, '2025-06-06 13:51:00', '2025-06-30 13:23:02'),
(17, 'Logitech MX Master 3S', 'Mouse inalámbrico ergonómico con múltiples funciones\r\n\r\nControl total y comodidad premium para largas jornadas.', 99.99, 'image/img_productos/68433b990cfec_logitic.png', 10, 10, '2025-06-06 14:03:53', '2025-06-30 13:23:02'),
(18, 'Redragon Kumara K552', 'Teclado mecánico compacto para gaming\r\nHaz que cada pulsación cuente en tus partidas.', 42.00, 'image/img_productos/68433c1339cb0_redragon teclao.png', 10, 10, '2025-06-06 14:05:55', '2025-06-30 13:23:02'),
(19, 'Microsoft Surface Arc ', 'Ultradelgado, plegable, diseño minimalista \r\nDiseño moderno que se adapta a tu estilo en movimiento. (es horrible. att. Frontend)', 79.99, 'image/img_productos/68433c4be7736_microsoft maus.png', 10, 10, '2025-06-06 14:06:51', '2025-06-30 13:23:02'),
(20, 'TP-Link Archer T3U', 'Adaptador Wi-Fi USB AC1300 de doble banda\r\nConéctate rápido y sin cables con señal estable.', 22.99, 'image/img_productos/68433c76295c0_tplink.png', 10, 10, '2025-06-06 14:07:34', '2025-06-30 13:23:02'),
(21, 'Epson EcoTank L3210', 'Multifuncional sin cartuchos, ideal para oficinas\r\nImprime más y ahorra más sin sacrificar calidad.', 189.99, 'image/img_productos/68433ff5a86b5_epson.png', 11, 10, '2025-06-06 14:22:29', '2025-06-30 13:23:02'),
(22, 'HP DeskJet 2775', 'Impresora multifunción económica para el hogar\r\nSolución completa para tu hogar a un gran precio.', 74.99, 'image/img_productos/68434019315f3_HP desk.png', 11, 10, '2025-06-06 14:23:05', '2025-06-30 13:23:02'),
(23, 'Brother HL-L2370DW', 'Impresora láser monocromática, conexión Wi-Fi\r\nImpresión rápida y eficiente para tu productividad.', 149.99, 'image/img_productos/68434039b103a_brother.png', 11, 10, '2025-06-06 14:23:37', '2025-06-30 13:23:02'),
(24, 'Canon CanoScan LiDE 300', 'Escáner plano de alta resolución, diseño compacto\r\nEscanea tus documentos con claridad y estilo.', 79.99, 'image/img_productos/684340ad251a7_Canon.png', 11, 10, '2025-06-06 14:25:33', '2025-06-30 13:23:02'),
(25, 'Sony WH-1000XM5', 'Auriculares inalámbricos con cancelación de ruido y hasta 30h de batería\r\nSumérgete en tu música con el mejor sonido sin distracciones.', 349.99, 'image/img_productos/6849d8710e653_sony-wh-1000xm5.png', 12, 10, '2025-06-11 14:26:41', '2025-06-30 13:23:02'),
(26, 'JBL Flip 6', 'Bocina Bluetooth portátil, resistente al agua, 12h de reproducción\r\nLleva tu música a cualquier parte con estilo y potencia.', 129.00, 'image/img_productos/6849d8e9636b9_jbl.png', 12, 10, '2025-06-11 14:28:41', '2025-06-30 13:23:02'),
(27, 'Apple AirPods Pro (2.ª gen)', 'Cancelación activa de ruido, modo ambiente, estuche MagSafe\r\nSonido envolvente y comodidad total, al estilo Apple.', 249.00, 'image/img_productos/6849d9529e2a3_airbuds.png', 12, 10, '2025-06-11 14:30:26', '2025-06-30 13:23:02'),
(28, 'HyperX Cloud II', 'Auriculares con micrófono para gaming, sonido envolvente 7.1\r\n Juega con audio profesional y comunicación clara.', 99.99, 'image/img_productos/6849d975efa91_HyperX.png', 12, 10, '2025-06-11 14:31:01', '2025-06-30 13:23:02'),
(29, 'Elden Ring GameKey', 'Compra una Key única para descargar este juego, mata tus neuronas hasta las 3 de la mañana y arrepiéntete de esta compra tras morir una y otra vez jugando Elden Ring en multijugador', 25.99, 'image/img_productos/6849dca73ba73_Elden.png', 13, 10, '2025-06-11 14:44:39', '2025-06-30 13:23:02'),
(30, 'PlayStation 5 Standard', 'Consola con unidad de disco, 825GB SSD, DualSense\r\nVive la nueva generación de videojuegos con potencia y realismo.', 499.99, 'image/img_productos/6849dd37e2795_ps5.png', 13, 10, '2025-06-11 14:47:03', '2025-06-30 13:23:02'),
(31, 'Xbox Series S', 'Consola digital, 512GB SSD, rendimiento de nueva generación\r\nAccede al mundo Xbox a un precio accesible y veloz.', 299.99, 'image/img_productos/6849dd7b6e914_xbox.png', 13, 10, '2025-06-11 14:48:11', '2025-06-30 13:23:02'),
(32, 'Nintendo Switch OLED', 'Pantalla OLED de 7\", modo portátil y dock, 64GB\r\nDiversión híbrida donde quieras con una pantalla espectacular.', 349.99, 'image/img_productos/6849de551bcef_nintendo.png', 13, 10, '2025-06-11 14:51:49', '2025-06-30 13:23:02'),
(33, 'Controlador DualSense', 'Compatible con PS5, gatillos adaptativos, retroalimentación háptica\r\n Siente cada momento del juego con este control revolucionario.', 69.99, 'image/img_productos/6849dec828295_dualsense.png', 13, 10, '2025-06-11 14:53:44', '2025-06-30 13:23:02'),
(34, 'Call of Duty MW3', 'el más infravalorado de toda la saga, no lo supieron apreciar, pero era un juegazo, diviértete esperando lo que todo el mundo sabe que pasará (RIP Soap).', 79.99, 'image/img_productos/6849df5087db1_codmw3.png', 13, 10, '2025-06-11 14:56:00', '2025-06-30 13:23:02'),
(35, 'Amazon Echo Dot (5.ª gen)', 'Asistente Alexa, altavoz compacto, sensor de temperatura\r\nControla tu hogar con solo tu voz.', 49.99, 'image/img_productos/684c6c3cd339a_amazonwea.png', 14, 10, '2025-06-13 13:21:48', '2025-06-30 13:23:02'),
(36, 'TP-Link Tapo C200', 'Cámara Wi-Fi HD con visión nocturna, rotación y detección de movimiento\r\nSeguridad inteligente y accesible para tu hogar.', 34.99, 'image/img_productos/684c6c6188fe5_camara.png', 14, 10, '2025-06-13 13:22:25', '2025-06-30 13:23:02'),
(37, 'Google Nest Hub (2.ª gen)', 'Pantalla táctil de 7\", control de dispositivos, reloj inteligente\r\nTu casa conectada y organizada desde un solo lugar.', 99.00, 'image/img_productos/684c6c9bb46e3_Google.png', 14, 10, '2025-06-13 13:23:23', '2025-06-30 13:23:02'),
(38, 'Xiaomi Mi Smart Plug', 'Enchufe inteligente con Wi-Fi y control por app\r\nAutomatiza cualquier equipo eléctrico con tu celular.', 19.99, 'image/img_productos/684c6ce2ef882_xiaomiPlug.png', 14, 10, '2025-06-13 13:24:34', '2025-06-30 13:23:02'),
(39, 'Anker PowerCore 20,000mAh', 'Cargador portátil USB con carga rápida\r\nMantén tus dispositivos cargados todo el día, estés donde estés.', 49.99, 'image/img_productos/684c6df36745f_anker.png', 16, 10, '2025-06-13 13:29:07', '2025-06-30 13:23:02'),
(40, 'Base de carga inalámbrica Belkin', 'Compatible con Qi, hasta 10W, diseño antideslizante\r\nCarga tus dispositivos sin cables y sin complicaciones.', 29.99, 'image/img_productos/684c6e114e1ce_belkin.png', 16, 10, '2025-06-13 13:29:37', '2025-06-30 13:23:02'),
(41, 'Cargador rápido Samsung USB-C 25W', 'Compatible con carga rápida Super Fast Charging\r\nEnergía rápida y segura para tu Galaxy y más.', 19.99, 'image/img_productos/684c6e54db93a_samsungCargador.png', 16, 10, '2025-06-13 13:30:44', '2025-06-30 13:23:02'),
(42, 'Regleta inteligente Kasa', '6 tomas controlables por app, compatible con Alexa y Google\r\nDomina tu energía desde el celular con eficiencia total.', 39.99, 'image/img_productos/684c6e7848bd6_reglaKasa.png', 16, 10, '2025-06-13 13:31:20', '2025-06-30 13:23:02'),
(43, 'LG UltraGear 27GN800-B', 'Monitor gaming 27\", QHD, 144Hz, 1ms\r\nFluidez y detalle para dominar en cada partida.', 299.99, 'image/img_productos/684c6f2ca740c_LGultragear.png', 15, 10, '2025-06-13 13:34:20', '2025-06-30 13:23:02'),
(44, 'Samsung Smart Monitor M8', '32\", 4K UHD, apps integradas, altavoces y cámara\r\nUn monitor todo-en-uno para trabajo, estudio y streaming.', 689.99, 'image/img_productos/684c6f4ec8c4b_samsungM8.png', 15, 10, '2025-06-13 13:34:54', '2025-06-30 13:23:02'),
(45, 'BenQ GW2480', '24\", Full HD, panel IPS, cuidado visual\r\nComodidad visual con diseño sin bordes y calidad de imagen.', 129.99, 'image/img_productos/684c6f904611e_BenQ.png', 15, 10, '2025-06-13 13:36:00', '2025-06-30 13:23:02'),
(46, 'AOC C24G1A', 'Monitor curvo 24\", 165Hz, 1ms, FreeSync\r\nInmersión total con curvatura envolvente y alta velocidad.', 169.99, 'image/img_productos/684c6facbd53b_AOCPantalla.png', 15, 10, '2025-06-13 13:36:28', '2025-06-30 13:23:02');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `usuarios`
--

CREATE TABLE `usuarios` (
  `id` int(11) NOT NULL,
  `nomb_user` varchar(50) NOT NULL,
  `contraseña` varchar(255) NOT NULL,
  `rol` enum('admin','consulta') NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `usuarios`
--

INSERT INTO `usuarios` (`id`, `nomb_user`, `contraseña`, `rol`) VALUES
(1, 'admin', 'Admin123', 'admin'),
(2, 'usuario', 'User123', 'consulta');

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `categorias`
--
ALTER TABLE `categorias`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `productos`
--
ALTER TABLE `productos`
  ADD PRIMARY KEY (`id`),
  ADD KEY `categoria_id` (`categoria_id`);

--
-- Indices de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `nomb_user` (`nomb_user`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `categorias`
--
ALTER TABLE `categorias`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

--
-- AUTO_INCREMENT de la tabla `productos`
--
ALTER TABLE `productos`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=48;

--
-- AUTO_INCREMENT de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `productos`
--
ALTER TABLE `productos`
  ADD CONSTRAINT `productos_ibfk_1` FOREIGN KEY (`categoria_id`) REFERENCES `categorias` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
