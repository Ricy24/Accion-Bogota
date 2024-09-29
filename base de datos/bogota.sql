-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 29-09-2024 a las 23:41:09
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
-- Base de datos: `bogota`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `comentarios`
--

CREATE TABLE `comentarios` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `noticia_id` int(11) NOT NULL,
  `comentario` text NOT NULL,
  `fecha_comentario` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `noticias`
--

CREATE TABLE `noticias` (
  `id` int(11) NOT NULL,
  `titulo` varchar(255) NOT NULL,
  `contenido` text NOT NULL,
  `resumen` text DEFAULT NULL,
  `fecha_publicacion` timestamp NOT NULL DEFAULT current_timestamp(),
  `imagen` varchar(255) DEFAULT NULL,
  `categoria` varchar(100) NOT NULL DEFAULT 'general'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `noticias`
--

INSERT INTO `noticias` (`id`, `titulo`, `contenido`, `resumen`, `fecha_publicacion`, `imagen`, `categoria`) VALUES
(10, 'Una ciudad sin techo: El fenómeno de las calles bogotanas', 'En Bogotá, los lugares más concurridos por habitantes de calle en 2024 siguen siendo zonas céntricas y sectores de alto tránsito. Algunas de las áreas más conocidas incluyen la Calle del Bronx, el sector de San Victorino, y zonas como el Centro Histórico. Estos espacios han sido reconocidos históricamente por concentrar a personas en situación de calle debido a su alta actividad comercial y accesibilidad a ciertos servicios.\r\n\r\nEl fenómeno de habitabilidad en la calle ha ido en aumento en los últimos años, y se espera que un nuevo censo de habitantes de calle en Bogotá, realizado en 2024, arroje datos actualizados para finales de 2024 o inicios de 2025. Este censo es crucial para el diseño de estrategias sociales y la atención de esta población, que en 2017 ya sumaba más de 9,500 personas, y las estimaciones actuales sugieren un incremento considerable en 2024​ (Integración Social / Bogota.gov).', 'Una ciudad sin techo', '2024-09-10 05:00:00', 'WhatsApp Image 2024-09-10 at 8.05.44 PM.jpeg', 'general'),
(11, 'Mascotas sin hogar: Ciudad Bolivar es la cuna del abandono canino', 'Las cifras exactas de animales en situación de calle pueden variar con el tiempo y entre diferentes fuentes. Sin embargo, según informes de organizaciones y entidades locales, se estima que en Bogotá hay miles de animales en situación de calle, con Ciudad Bolívar siendo una de las localidades más afectadas.\r\n\r\nPor ejemplo, en 2023, el Instituto Distrital de Protección y Bienestar Animal (IDPYBA) reportó un gran número de animales en situación de calle en diferentes localidades. Aunque no siempre se desglosan cifras específicas por localidad, el trabajo en el terreno indica que Ciudad Bolívar y otras áreas con altos niveles de pobreza y falta de acceso a servicios veterinarios tienden a tener mayores problemas con el abandono de mascotas.', 'Mascotas sin hogar', '2024-09-10 05:00:00', 'WhatsApp Image 2024-09-10 at 8.56.29 PM.jpeg', 'general'),
(12, 'Unidos por la belleza de nuestra ciudad: Juntos transformaremos Usme y San Cristobal', 'En Bogotá, los sectores de Usme y San Cristóbal enfrentan serios problemas de limpieza. En Usme, se reporta la acumulación de aproximadamente 25 toneladas de basura semanalmente, principalmente en calles principales y parques. La recolección irregular y la falta de mantenimiento en áreas recreativas agravan la situación, con áreas verdes frecuentemente abarrotadas de desechos.\r\n\r\nEn San Cristóbal, la situación es igualmente preocupante. Se estima que la acumulación de desechos en parques y espacios públicos alcanza las 15 toneladas por semana. La infraestructura de reciclaje es deficiente, con solo 30 puntos de reciclaje para una población de más de 300,000 habitantes. Además, el drenaje inadecuado contribuye a que los desechos se acumulen y contaminen el entorno.\r\n\r\nPara mejorar estas condiciones, se requiere una recolección de basura más frecuente, una expansión de los puntos de reciclaje y la implementación de campañas educativas sobre manejo de residuos. La colaboración entre autoridades locales y la comunidad es esencial para lograr un entorno más limpio y saludable.\r\n\r\n(Cifras tomadas de ambientebogota.gov.co)', 'Unidos por la belleza de nuestra ciudad', '2024-09-10 05:00:00', 'WhatsApp Image 2024-09-10 at 9.28.12 PM.jpeg', 'general');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `pagos`
--

CREATE TABLE `pagos` (
  `id` int(11) NOT NULL,
  `metodo_pago` enum('tarjeta','nequi','daviplata') NOT NULL,
  `detalles_pago` text NOT NULL,
  `fecha_pago` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `pagos`
--

INSERT INTO `pagos` (`id`, `metodo_pago`, `detalles_pago`, `fecha_pago`) VALUES
(1, 'nequi', 'Nequi: 3239219404', '2024-09-26 19:29:20');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `perfiles`
--

CREATE TABLE `perfiles` (
  `id` int(11) NOT NULL,
  `nombre` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `biografia` text DEFAULT NULL,
  `foto_perfil` varchar(255) DEFAULT 'image/avatar_default.jpg'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `perfiles`
--

INSERT INTO `perfiles` (`id`, `nombre`, `email`, `password`, `biografia`, `foto_perfil`) VALUES
(1, 'Andres', 'discordandres20@gmail.com', '$2y$10$gb/7EN/2MLAYk1mAOgjNAODPpOrMo9RvJ45dt9A.QrBMzbqYC74My', 'Soy un entusiasta por las noticias', 'uploads/2f97f05b32547f54ef1bdf99cd207c90.jpg'),
(3, 'Andres', 'anriduco2019@gmail.com', '$2y$10$zGhzjRYcWIiIKcTKC7e2Ju3/InoHzfuksHXqbiEy2qHp8BmOtjwma', 'Soy bueno', 'logo1.png');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `suscripciones_empresas`
--

CREATE TABLE `suscripciones_empresas` (
  `id` int(11) NOT NULL,
  `nombre_empresa` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `icono_empresa` blob NOT NULL,
  `metodo_pago` enum('tarjeta','nequi','daviplata') NOT NULL,
  `info_pago` text DEFAULT NULL,
  `fecha_suscripcion` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `suscripciones_empresas`
--

INSERT INTO `suscripciones_empresas` (`id`, `nombre_empresa`, `email`, `icono_empresa`, `metodo_pago`, `info_pago`, `fecha_suscripcion`) VALUES
(3, 'El tiempo ', 'anriduco2019@gmail.com', 0x4e5545564f5f4c4f474f5f44455f454c5f5449454d504f5f48442e6a7067, 'tarjeta', NULL, '2024-09-29 21:34:18');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `suscriptores`
--

CREATE TABLE `suscriptores` (
  `id` int(11) NOT NULL,
  `email` varchar(255) NOT NULL,
  `fecha_suscripcion` datetime DEFAULT current_timestamp(),
  `estado_suscripcion` enum('activa','inactiva') DEFAULT 'activa',
  `id_pago` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `suscriptores`
--

INSERT INTO `suscriptores` (`id`, `email`, `fecha_suscripcion`, `estado_suscripcion`, `id_pago`) VALUES
(1, 'discordandres20@gmail.com', '2024-09-26 19:29:20', 'activa', 1),
(8, 'anriduco2019@gmail.com', '2024-09-29 16:06:10', 'activa', NULL);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `usuarios`
--

CREATE TABLE `usuarios` (
  `id` int(11) NOT NULL,
  `usuario` varchar(50) NOT NULL,
  `email` varchar(100) NOT NULL,
  `contrasena` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `usuarios`
--

INSERT INTO `usuarios` (`id`, `usuario`, `email`, `contrasena`) VALUES
(1, 'admin', 'noticias@gmail.com', '$2y$10$R/oJlau60CnPmlyZMMJ1pOvxhsVSTMl/ntMGqK546YBS4llHf6cWi');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `voluntarios`
--

CREATE TABLE `voluntarios` (
  `id` int(11) NOT NULL,
  `nombre` varchar(100) NOT NULL,
  `correo` varchar(100) NOT NULL,
  `telefono` varchar(15) DEFAULT NULL,
  `fecha_registro` timestamp NOT NULL DEFAULT current_timestamp(),
  `noticia_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `voluntarios`
--

INSERT INTO `voluntarios` (`id`, `nombre`, `correo`, `telefono`, `fecha_registro`, `noticia_id`) VALUES
(3, 'andres', 'jimmyduran2014@gmail.com', '3239219404', '2024-09-28 01:15:32', 9);

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `comentarios`
--
ALTER TABLE `comentarios`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `noticia_id` (`noticia_id`);

--
-- Indices de la tabla `noticias`
--
ALTER TABLE `noticias`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `pagos`
--
ALTER TABLE `pagos`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `perfiles`
--
ALTER TABLE `perfiles`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Indices de la tabla `suscripciones_empresas`
--
ALTER TABLE `suscripciones_empresas`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `suscriptores`
--
ALTER TABLE `suscriptores`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`),
  ADD KEY `id_pago` (`id_pago`);

--
-- Indices de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `usuario` (`usuario`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Indices de la tabla `voluntarios`
--
ALTER TABLE `voluntarios`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `comentarios`
--
ALTER TABLE `comentarios`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT de la tabla `noticias`
--
ALTER TABLE `noticias`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT de la tabla `pagos`
--
ALTER TABLE `pagos`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT de la tabla `perfiles`
--
ALTER TABLE `perfiles`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT de la tabla `suscripciones_empresas`
--
ALTER TABLE `suscripciones_empresas`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT de la tabla `suscriptores`
--
ALTER TABLE `suscriptores`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT de la tabla `voluntarios`
--
ALTER TABLE `voluntarios`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `comentarios`
--
ALTER TABLE `comentarios`
  ADD CONSTRAINT `comentarios_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `perfiles` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `comentarios_ibfk_2` FOREIGN KEY (`noticia_id`) REFERENCES `noticias` (`id`) ON DELETE CASCADE;

--
-- Filtros para la tabla `suscriptores`
--
ALTER TABLE `suscriptores`
  ADD CONSTRAINT `suscriptores_ibfk_1` FOREIGN KEY (`id_pago`) REFERENCES `pagos` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
