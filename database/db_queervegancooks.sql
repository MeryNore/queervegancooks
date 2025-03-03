-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 03-03-2025 a las 22:55:36
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
-- Base de datos: `db_queervegancooks`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `citas`
--

CREATE TABLE `citas` (
  `idCita` int(11) NOT NULL,
  `idUser` int(11) NOT NULL,
  `fecha_cita` date NOT NULL,
  `motivo_cita` mediumtext DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_spanish_ci;

--
-- Volcado de datos para la tabla `citas`
--

INSERT INTO `citas` (`idCita`, `idUser`, `fecha_cita`, `motivo_cita`) VALUES
(1, 1, '2025-03-04', 'Cita programada para el martes 4 de abrir en la que se tratarán de cerrar un acuerdo para los menús que se servirán en el catering de la próxima semana.'),
(3, 2, '2025-03-12', 'Concertar cita para presupuestos'),
(4, 7, '2025-03-14', 'Cita para concretar menús y comensales. Preguntar por extras.'),
(5, 8, '2025-03-06', 'Jueves por la mañana, cita telefónica, sobre las 11h'),
(6, 11, '2025-03-26', 'Ir al establecimiento de Sergio a tomar medidas del local para mesas del catering');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `noticias`
--

CREATE TABLE `noticias` (
  `idNoticia` int(11) NOT NULL,
  `titulo` varchar(200) NOT NULL,
  `imagen` blob NOT NULL DEFAULT 'img',
  `texto` longtext NOT NULL,
  `fecha` date NOT NULL,
  `idUser` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_spanish_ci;

--
-- Volcado de datos para la tabla `noticias`
--

INSERT INTO `noticias` (`idNoticia`, `titulo`, `imagen`, `texto`, `fecha`, `idUser`) VALUES
(1, 'Deporte y Nutrición, optimizar el rendimiento con una dieta 100% vegetal', 0x696d67, 'El veganismo está ganando popularidad entre los deportistas debido a sus beneficios para la salud, el rendimiento y el impacto ambiental positivo. Sin embargo, es crucial llevar una planificación adecuada para garantizar el aporte óptimo de nutrientes esenciales. En este artículo, exploraremos cómo los deportistas pueden optimizar su rendimiento con una dieta vegana, incluyendo planes de alimentación y suplementos recomendados', '2025-03-03', 1),
(4, 'Veganismo/ Una Elección Sostenible para un Futuro Mejor', 0x696d67, 'El veganismo no solo es una decisión ética y de salud, sino que también tiene un impacto ambiental positivo que cada vez más estudios respaldan. La producción de alimentos de origen animal es una de las principales causas de la deforestación, el cambio climático y la contaminación del agua. Adoptar una dieta basada en plantas puede reducir significativamente la huella ecológica individual y colectiva.', '2025-03-17', 1),
(5, 'Mitos sobre el veganismo', 0x696d67, 'El veganismo ha ganado popularidad en los últimos años, pero aún existen numerosos mitos que generan dudas sobre su viabilidad y beneficios. A continuación, desmentimos algunos de los mitos más comunes.', '2025-04-23', 1),
(8, 'Veganismo para principiantes/ 5 recetas fáciles si no sabes cocinar', 0x696d67, 'Adoptar una alimentación vegana puede parecer un desafío, sobre todo si no tienes experiencia en la cocina. Muchas personas creen que ser vegano implica preparar platos elaborados con ingredientes difíciles de encontrar o seguir recetas complicadas. Pero la realidad es que hay muchísimas opciones sencillas, rápidas y deliciosas que cualquiera puede hacer, incluso si nunca has cocinado antes.', '2025-03-04', 3),
(9, 'Ferias y eventos veganos en España', 0x696d67, 'El mundo del veganismo sigue en auge y, cada año, numerosos eventos y ferias dedicadas a este estilo de vida tienen lugar en distintas ciudades. Si te interesa conocer las últimas tendencias en alimentación vegana, moda sostenible y cosmética ecológica, no te puedes perder las siguientes citas imprescindibles de 2025.', '2025-03-08', 13),
(10, 'Moda Vegana y Sostenible/ Viste con Estilo, Sin Cargar el Planeta', 0x696d67, '¿Piensas que estar a la moda significa olvidarte de tus valores o perjudicar el planeta? Para nada. La moda vegana y sostenible está marcando tendencia como nunca antes. Han quedado atrás los días de ropa aburrida y sin personalidad; hoy en día, hay marcas y diseñadores que demuestran que se puede vestir bien, cuidar a los animales y ser más conscientes con el medio ambiente al mismo tiempo.', '2025-03-18', 13),
(11, 'Uno de los mejores restaurantes del mundo se vuelve vegano', 0x696d67, 'La pandemia nos ha hecho replantearnos a todos si nuestros hábitos de consumo son los adecuados. Y gracias a esto, el reconocido chef Daniel Humm, al frente del neoyorkino Eleven Madison Park, uno de los mejores restaurantes del mundo, ha tomado la decisión de convertir su carta en una 100% vegana.\r\nTodo empezó el verano pasado, cuando el chef Humm decidió que no volvería a importar caviar o a estofar raíz de apio en las vejigas de los cerdos para el restaurante que regenta junto a su socio Will Guidara en el número 11 de Madison Avenue. Por la pandemia, tuvieron que cerrar su restaurante y es ahora, 16 meses más tarde, que reabrirán el próximo 10 de junio con un menú completamente plant-based.', '2025-05-08', 13);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `users_data`
--

CREATE TABLE `users_data` (
  `idUser` int(11) NOT NULL,
  `nombre` varchar(45) NOT NULL,
  `apellido` varchar(60) NOT NULL,
  `email` varchar(100) NOT NULL,
  `telefono` varchar(9) NOT NULL,
  `fecha_nac` date NOT NULL,
  `direccion` varchar(300) DEFAULT NULL,
  `sexo` enum('Mujer','Hombre','No Binario','Prefiero no decirlo') DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_spanish_ci;

--
-- Volcado de datos para la tabla `users_data`
--

INSERT INTO `users_data` (`idUser`, `nombre`, `apellido`, `email`, `telefono`, `fecha_nac`, `direccion`, `sexo`) VALUES
(1, 'Maria Paz', 'Noreña Aguirre', 'maria92.aguirre@gmail.com', '653323671', '1992-10-20', 'C/ Vargas 17 4º', 'Mujer'),
(2, 'Álvaro', 'Vega Domínguez', 'alvaro.vega93@email.com', '633666777', '1993-05-25', 'Rambla Cataluña 22, Barcelona', 'Hombre'),
(3, 'Carlos Andres', 'Murieda Pérez', 'carlos90.p@gmail.com', '786896542', '1994-12-07', 'C/ Entre vías, 70 5º 1ª', 'No Binario'),
(4, 'Julia', 'Argumedo Días', 'julia.argumedo12@gmail.com', '647821235', '1988-08-07', 'Ctra. General, bloque 4 portal 2 piso 8 puerta 2 Dcha', 'Prefiero no decirlo'),
(5, 'Cristian', 'Días Gonzáles', 'cristiangon96@gmail.com', '632894258', '1996-03-05', 'C/ Sin rumbo, 96 6º izq', 'Hombre'),
(6, 'Andrea', 'López García', 'andrea.lopez92@email.com', '678123456', '1992-04-12', 'Calle Mayor 15, Santander, Cantabria', 'Mujer'),
(7, 'Daniel', 'Fernández Ruiz', 'dani.fernandez87@email.com', '654987321', '1987-08-22', 'Avenida de la Libertad 30, Bilbao, País Vasco', 'Hombre'),
(8, 'Marta', 'Sánchez Torres', 'marta.sanchez99@email.com', '612345678', '1999-06-05', 'Calle del Prado 7, Madrid', 'No Binario'),
(9, 'Javier', 'Gómez Pérez', 'javi.gomez76@email.com', '699456789', '1976-02-14', 'Plaza España 2, Sevilla', 'Prefiero no decirlo'),
(10, 'Laura', 'Rodríguez Martín', 'laura.rod89@email.com', '644111222', '1989-09-30', 'Avenida de los Pinos 18, Valencia', 'Mujer'),
(11, 'Sergio', 'Jiménez Ortiz', 'sergio.jimenez95@email.com', '655222333', '1995-11-11', 'Calle del Sol 45, Barcelona', 'Hombre'),
(12, 'Natalia', 'Castro Delgado', 'natalia.castro80@email.com', '677333444', '1990-07-18', 'Calle San Juan 9, Zaragoza', 'No Binario'),
(13, 'Pablo', 'Ramírez López', 'pablo.ramirez90@email.com', '688444555', '1990-07-18', 'Paseo de la Castellana 60, Madrid', 'Hombre');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `users_login`
--

CREATE TABLE `users_login` (
  `idLogin` int(11) NOT NULL,
  `idUser` int(11) NOT NULL,
  `usuario` varchar(100) NOT NULL,
  `user_password` varchar(60) NOT NULL,
  `rol` enum('user','admin') NOT NULL DEFAULT 'user'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_spanish_ci;

--
-- Volcado de datos para la tabla `users_login`
--

INSERT INTO `users_login` (`idLogin`, `idUser`, `usuario`, `user_password`, `rol`) VALUES
(1, 1, 'maria92.aguirre@gmail.com', '$2y$10$Zj7VQG.YllxBx9pdIDsGo.grumEU7nYXT8yLG7sj.toaNpdC6kVRu', 'admin'),
(2, 2, 'alvaro.vega93@email.com', '$2y$10$SzPStII8MVbXGyU7GSdj9u3d/MICBL9VN0NwqRiPME6wYisPQcpSa', 'user'),
(3, 3, 'carlos90.p@gmail.com', '$2y$10$qoc5JZ5H11q5Y95Nl2evhu650EF2HABvwr0qfUQ5IOm9FrBrPPbQ.', 'admin'),
(4, 4, 'julia.argumedo12@gmail.com', '$2y$10$8IDjK1T28776EfJmAqa1xOLXAwbq4M5lQjzYGG3sJjtoxpq5k6enK', 'user'),
(5, 5, 'cristiangon96@gmail.com', '$2y$10$dZc/Qgxy8NJS8b/5TOIjs.vFaQJ4pM8z7Tj5Q7euBJV/IWYpCxoya', 'user'),
(6, 6, 'andrea.lopez92@email.com', '$2y$10$9XyEoCnZ70SI8kgIBlgJ7OaD9pIUbGiN/RzTj0XJSGV8tNQuR7vVW', 'user'),
(7, 7, 'dani.fernandez87@email.com', '$2y$10$ym0Cd.yclHqcUdNnyN4aA.RuZXdhJLHWnE0zi4/J0T5YDgzkuu0WC', 'user'),
(8, 8, 'marta.sanchez99@email.com', '$2y$10$DRwXW5.G/jAnzs0Tttpl4Or1DN/gMtWyTAMfnXw//CJGSVEaxcjKG', 'user'),
(9, 9, 'javi.gomez76@email.com', '$2y$10$pJsXARp.CgyJAKWMT6ujCetnGMzG09quOKpaIbGO6KuisFONAID.e', 'user'),
(10, 10, 'laura.rod89@email.com', '$2y$10$fX0V8DkWbR7Wna99xqolkOmBDla7fd0d4YfKT.vTZU6NC27gytFiK', 'user'),
(11, 11, 'sergio.jimenez95@email.com', '$2y$10$Ksw7G5pIQ7Yc3j0rVsb//e02Vmmja2RDPVemeyH1Cf0sFkpkNsMCK', 'user'),
(12, 12, 'natalia.castro80@email.com', '$2y$10$IA9YPeCaKbJsMESkoj5YwedH26jI8v23m0sdKj6.ul1MJ.oR4dwvq', 'user'),
(13, 13, 'pablo.ramirez90@email.com', '$2y$10$EFTp9BNxkLZd8fjLCwsnFu/oOiCE3hwlS24GQWAuVKVLRacThBoVi', 'admin');

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `citas`
--
ALTER TABLE `citas`
  ADD PRIMARY KEY (`idCita`),
  ADD KEY `fk_citas_user` (`idUser`);

--
-- Indices de la tabla `noticias`
--
ALTER TABLE `noticias`
  ADD PRIMARY KEY (`idNoticia`),
  ADD UNIQUE KEY `titulo` (`titulo`),
  ADD KEY `fk_user3` (`idUser`);

--
-- Indices de la tabla `users_data`
--
ALTER TABLE `users_data`
  ADD PRIMARY KEY (`idUser`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Indices de la tabla `users_login`
--
ALTER TABLE `users_login`
  ADD PRIMARY KEY (`idLogin`),
  ADD UNIQUE KEY `idUser` (`idUser`),
  ADD UNIQUE KEY `usuario` (`usuario`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `citas`
--
ALTER TABLE `citas`
  MODIFY `idCita` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT de la tabla `noticias`
--
ALTER TABLE `noticias`
  MODIFY `idNoticia` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT de la tabla `users_data`
--
ALTER TABLE `users_data`
  MODIFY `idUser` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT de la tabla `users_login`
--
ALTER TABLE `users_login`
  MODIFY `idLogin` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `citas`
--
ALTER TABLE `citas`
  ADD CONSTRAINT `fk_citas_user` FOREIGN KEY (`idUser`) REFERENCES `users_data` (`idUser`) ON DELETE NO ACTION ON UPDATE CASCADE;

--
-- Filtros para la tabla `noticias`
--
ALTER TABLE `noticias`
  ADD CONSTRAINT `fk_user3` FOREIGN KEY (`idUser`) REFERENCES `users_data` (`idUser`) ON DELETE NO ACTION ON UPDATE CASCADE;

--
-- Filtros para la tabla `users_login`
--
ALTER TABLE `users_login`
  ADD CONSTRAINT `fk_user` FOREIGN KEY (`idUser`) REFERENCES `users_data` (`idUser`) ON DELETE NO ACTION ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
