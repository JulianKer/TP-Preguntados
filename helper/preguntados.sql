-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 25-11-2024 a las 23:59:57
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
-- Base de datos: `preguntados`
--
CREATE DATABASE IF NOT EXISTS `preguntados` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;
USE `preguntados`;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `categoria`
--

CREATE TABLE `categoria` (
  `id_categoria` int(11) NOT NULL,
  `nombre` varchar(100) NOT NULL,
  `fecha_alta` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `categoria`
--

INSERT INTO `categoria` (`id_categoria`, `nombre`, `fecha_alta`) VALUES
(1, 'Historia', '2024-10-27 04:10:21'),
(2, 'Entretenimiento', '2024-10-27 04:10:21'),
(3, 'Geografia', '2024-10-27 04:10:21'),
(4, 'Ciencia', '2024-10-27 04:10:21'),
(5, 'Deporte', '2024-10-27 04:10:21'),
(6, 'Arte', '2024-10-27 04:10:21'),
(7, 'Matematica', '2024-10-27 04:10:21'),
(8, 'Cultura general', '2024-10-27 04:10:21'),
(9, 'Programacion', '2024-10-27 04:10:21'),
(10, 'Juegos', '2024-10-27 04:10:21'),
(11, 'Gastronomia', '2024-10-27 04:10:21'),
(12, 'Formula 1', '2024-10-27 04:10:21');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `estado`
--

CREATE TABLE `estado` (
  `id_estado` int(11) NOT NULL,
  `descripcion` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `estado`
--

INSERT INTO `estado` (`id_estado`, `descripcion`) VALUES
(1, 'desactivada'),
(2, 'reportada'),
(3, 'rechazada'),
(4, 'aprobada'),
(5, 'pendiente');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `partida`
--

CREATE TABLE `partida` (
  `id_partida` int(11) NOT NULL,
  `id_usuario` int(11) NOT NULL,
  `puntaje` int(11) NOT NULL,
  `terminada` tinyint(1) NOT NULL,
  `fechapartida` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `partida`
--

INSERT INTO `partida` (`id_partida`, `id_usuario`, `puntaje`, `terminada`, `fechapartida`) VALUES
(268, 10, 20, 1, '2024-11-01'),
(269, 10, 0, 1, '2024-11-02'),
(270, 10, 0, 1, '2024-11-11'),
(271, 10, 0, 1, '2024-11-12'),
(272, 10, 10, 1, '2024-11-25'),
(273, 10, 10, 1, '2024-11-25'),
(274, 10, 10, 1, '2024-11-25'),
(275, 10, 0, 1, '2024-11-25'),
(276, 10, 20, 1, '2024-11-25'),
(277, 31, 60, 1, '2024-11-01'),
(278, 31, 0, 1, '2024-11-25'),
(279, 31, 0, 1, '2024-11-25'),
(280, 31, 0, 1, '2024-11-25'),
(281, 31, 0, 1, '2024-11-25'),
(282, 10, 0, 1, '2024-11-25'),
(283, 31, 20, 1, '2024-11-25'),
(284, 32, 0, 1, '2024-11-25'),
(285, 32, 50, 1, '2024-11-25'),
(286, 32, 10, 1, '2024-11-25'),
(287, 10, 0, 1, '2024-11-25'),
(288, 10, 10, 1, '2024-11-25');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `pregunta`
--

CREATE TABLE `pregunta` (
  `id_pregunta` int(11) NOT NULL,
  `pregunta` varchar(255) NOT NULL,
  `id_categoria` int(11) NOT NULL,
  `dificultad` int(11) NOT NULL DEFAULT 1,
  `estado` int(11) NOT NULL,
  `apariciones` int(11) DEFAULT 0,
  `aciertos` int(11) DEFAULT 0,
  `fecha_alta` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `pregunta`
--

INSERT INTO `pregunta` (`id_pregunta`, `pregunta`, `id_categoria`, `dificultad`, `estado`, `apariciones`, `aciertos`, `fecha_alta`) VALUES
(1, '¿Quién fue el primer presidente de los Estados Unidos?', 1, 1, 4, 51, 50, '2024-03-08 11:33:19'),
(2, '¿En qué año comenzó la Primera Guerra Mundial?', 1, 1, 4, 52, 52, '2024-01-18 11:23:51'),
(3, '¿Cuál fue la civilización que construyó las pirámides de Egipto?', 1, 1, 4, 52, 52, '2024-09-06 19:19:21'),
(4, '¿Qué famoso explorador descubrió América en 1492?', 1, 1, 4, 53, 53, '2024-04-11 11:25:10'),
(5, '¿Cuál fue el evento que marcó el inicio de la Revolución Francesa?', 1, 1, 4, 51, 51, '2024-05-02 20:07:52'),
(6, '¿Cuál es el nombre de la serie de televisión que sigue las aventuras de un grupo de amigos en Nueva York?', 2, 1, 4, 50, 50, '2024-11-05 15:23:49'),
(7, '¿Quién ganó el premio Óscar a la Mejor Película en 2020?', 2, 1, 4, 51, 51, '2024-03-25 13:35:29'),
(8, '¿Qué película de animación presenta a un joven que se convierte en héroe de su pueblo tras descubrir su linaje?', 2, 1, 4, 53, 51, '2024-08-11 18:46:02'),
(9, '¿Cuál es el nombre de la canción que se considera el himno de la libertad en varios países?', 2, 1, 4, 52, 50, '2024-05-15 02:03:42'),
(10, '¿Qué famoso superhéroe es conocido como el Hombre Araña?', 2, 1, 4, 52, 52, '2024-01-04 23:00:27'),
(11, '¿Cuál es el río más largo del mundo?', 3, 1, 2, 52, 50, '2024-12-10 09:51:11'),
(12, '¿En qué continente se encuentra el desierto del Sahara?', 3, 1, 2, 53, 52, '2024-09-07 01:14:33'),
(13, '¿Cuál es la capital de Japón?', 3, 1, 4, 51, 50, '2024-08-04 21:39:17'),
(14, '¿Qué país tiene la mayor cantidad de islas del mundo?', 3, 1, 4, 52, 50, '2024-11-30 05:32:46'),
(15, '¿Cuál es la montaña más alta del mundo?', 3, 1, 4, 50, 50, '2024-10-17 07:45:59'),
(16, '¿Cuál es la fórmula del agua?', 4, 1, 4, 50, 50, '2024-03-24 19:11:41'),
(17, '¿Qué planeta es conocido como el planeta rojo?', 4, 1, 4, 51, 50, '2024-10-04 21:40:27'),
(18, '¿Cuál es el órgano más grande del cuerpo humano?', 4, 1, 4, 50, 50, '2024-02-12 23:47:15'),
(19, '¿Qué tipo de energía se produce mediante la fotosíntesis?', 4, 1, 4, 52, 51, '2024-04-21 02:54:53'),
(20, '¿Cuál es la teoría que explica el origen del universo?', 4, 1, 4, 53, 52, '2024-03-02 12:12:45'),
(21, '¿Cuántos jugadores hay en un equipo de fútbol?', 5, 2, 4, 51, 26, '2024-12-05 01:19:04'),
(22, '¿Cuál es el evento deportivo más visto del mundo?', 5, 2, 4, 51, 26, '2024-02-20 14:57:09'),
(23, '¿Qué país ganó la Copa del Mundo de Fútbol en 2018?', 5, 2, 4, 51, 25, '2024-11-26 19:48:34'),
(24, '¿Cuál es el deporte más practicado del mundo?', 5, 2, 4, 50, 25, '2024-02-12 03:11:24'),
(25, '¿En qué deporte se utiliza una raqueta?', 5, 2, 4, 51, 25, '2024-11-09 01:31:20'),
(26, '¿Quién pintó la Mona Lisa?', 6, 2, 4, 51, 26, '2024-12-09 19:02:28'),
(27, '¿Qué movimiento artístico es Van Gogh asociado?', 6, 2, 4, 50, 25, '2024-02-19 15:38:22'),
(28, '¿Cuál es el famoso monumento de piedra en Perú?', 6, 2, 4, 50, 25, '2024-11-07 18:04:28'),
(29, '¿Qué técnica se utiliza para hacer esculturas en mármol?', 6, 2, 4, 51, 25, '2024-11-10 16:27:15'),
(30, '¿Qué museo alberga la estatua de David de Miguel Ángel?', 6, 2, 4, 51, 25, '2024-09-30 01:02:51'),
(31, '¿Cuál es la raíz cuadrada de 16?', 7, 2, 4, 51, 26, '2024-02-26 00:52:34'),
(32, '¿Qué es π (pi)?', 7, 2, 4, 50, 25, '2024-07-09 22:14:19'),
(33, '¿Cuál es la fórmula del área de un círculo?', 7, 2, 4, 52, 26, '2024-02-26 09:33:54'),
(34, '¿Cuántos lados tiene un hexágono?', 7, 2, 4, 50, 25, '2024-03-15 02:06:33'),
(35, '¿Qué es un número primo?', 7, 2, 4, 51, 25, '2024-07-20 02:51:06'),
(36, '¿Cuál es la capital de Francia?', 8, 2, 4, 51, 25, '2024-02-23 04:55:52'),
(37, '¿Quién escribió \"Cien años de soledad\"?', 8, 2, 4, 50, 25, '2024-01-28 13:06:03'),
(38, '¿Cuál es el río más largo del mundo?', 8, 2, 4, 50, 25, '2024-12-08 23:42:42'),
(39, '¿Qué país tiene la mayor población del mundo?', 8, 2, 4, 50, 25, '2024-06-21 04:15:22'),
(40, '¿En qué año llegó el hombre a la Luna?', 8, 2, 4, 50, 25, '2024-07-15 19:08:45'),
(41, '¿Qué significa HTML?', 9, 3, 4, 50, 0, '2024-04-12 07:57:41'),
(42, '¿Cuál es el lenguaje de programación más popular en 2024?', 9, 3, 4, 50, 0, '2024-10-13 03:22:11'),
(43, '¿Qué es una función en programación?', 9, 3, 4, 50, 0, '2024-01-29 13:57:55'),
(44, '¿Qué es un bucle en programación?', 9, 3, 4, 50, 0, '2024-01-15 08:43:01'),
(45, '¿Qué herramienta se utiliza para el control de versiones?', 9, 3, 4, 50, 0, '2024-12-16 22:41:24'),
(46, '¿Cuál es el objetivo del juego de ajedrez?', 10, 3, 4, 50, 0, '2024-09-08 12:17:58'),
(47, '¿Qué videojuego se lanzó en 1980 y es conocido por su personaje principal, un ladrón de frutas?', 10, 3, 4, 50, 0, '2024-07-23 14:25:41'),
(48, '¿Qué tipo de juego es \"The Legend of Zelda\"?', 10, 3, 4, 50, 0, '2024-09-25 08:14:30'),
(49, '¿Qué se necesita para ganar en \"Monopoly\"?', 10, 3, 4, 50, 0, '2024-12-28 18:55:30'),
(50, '¿Qué es un \"speedrun\"?', 10, 3, 4, 50, 0, '2024-10-05 18:54:01'),
(51, '¿Cuál es el plato típico de España que lleva arroz?', 11, 3, 4, 50, 0, '2024-11-01 10:43:35'),
(52, '¿Qué es el sushi?', 11, 3, 4, 50, 0, '2024-11-21 17:55:55'),
(53, '¿Cuál es el ingrediente principal de la masa de pizza?', 11, 3, 4, 50, 0, '2024-12-13 06:28:51'),
(54, '¿Qué fruta se usa para hacer guacamole?', 11, 3, 4, 50, 0, '2024-01-29 23:36:30'),
(55, '¿Cuál es la bebida alcohólica más consumida en el mundo?', 11, 3, 4, 50, 0, '2024-07-16 23:36:00'),
(56, '¿Quién es considerado el piloto más exitoso de la historia de la F1?', 12, 3, 4, 50, 0, '2024-06-21 20:10:31'),
(57, '¿En qué año se celebró la primera carrera de Fórmula 1?', 12, 3, 4, 50, 0, '2024-09-27 03:04:35'),
(58, '¿Cuál es la escudería más ganadora de la F1?', 12, 3, 4, 50, 0, '2024-04-11 23:51:23'),
(59, '¿Qué es el DRS en Fórmula 1?', 12, 3, 4, 50, 0, '2024-03-05 11:03:11'),
(60, '¿Cuántas vueltas tiene el Gran Premio de Mónaco?', 12, 3, 4, 50, 0, '2024-01-17 04:41:48'),
(61, '¿Que debo tomar si me duele la cabeza?', 8, 1, 5, 0, 0, '2024-11-25 22:17:08');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `preguntapartida`
--

CREATE TABLE `preguntapartida` (
  `id_preguntaPartida` int(11) NOT NULL,
  `id_partida` int(11) NOT NULL,
  `id_pregunta` int(11) NOT NULL,
  `id_usuario` int(11) NOT NULL,
  `respondida` tinyint(1) NOT NULL,
  `acertoElUsuario` tinyint(1) NOT NULL,
  `fechaEntregada` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `preguntapartida`
--

INSERT INTO `preguntapartida` (`id_preguntaPartida`, `id_partida`, `id_pregunta`, `id_usuario`, `respondida`, `acertoElUsuario`, `fechaEntregada`) VALUES
(450, 268, 2, 10, 1, 1, '2024-11-25 19:35:20'),
(451, 268, 10, 10, 1, 1, '2024-11-25 19:35:24'),
(452, 268, 11, 10, 1, 0, '2024-11-25 19:35:28'),
(453, 269, 9, 10, 1, 0, '2024-11-25 19:35:31'),
(454, 270, 8, 10, 1, 0, '2024-11-25 19:37:45'),
(455, 271, 19, 10, 1, 0, '2024-11-25 19:37:49'),
(456, 272, 7, 10, 1, 1, '2024-11-25 19:37:51'),
(457, 272, 14, 10, 1, 0, '2024-11-25 19:37:53'),
(458, 273, 12, 10, 1, 1, '2024-11-25 19:37:55'),
(459, 273, 20, 10, 1, 0, '2024-11-25 19:37:57'),
(460, 274, 4, 10, 1, 1, '2024-11-25 19:37:59'),
(461, 274, 23, 10, 1, 0, '2024-11-25 19:38:01'),
(462, 275, 30, 10, 1, 0, '2024-11-25 19:38:03'),
(463, 276, 26, 10, 1, 1, '2024-11-25 19:38:06'),
(464, 276, 33, 10, 1, 1, '2024-11-25 19:38:08'),
(465, 276, 29, 10, 1, 0, '2024-11-25 19:38:09'),
(466, 277, 12, 31, 1, 1, '2024-11-25 19:38:20'),
(467, 277, 20, 31, 1, 1, '2024-11-25 19:38:22'),
(468, 277, 3, 31, 1, 1, '2024-11-25 19:38:25'),
(469, 277, 4, 31, 1, 1, '2024-11-25 19:38:27'),
(470, 277, 5, 31, 1, 1, '2024-11-25 19:38:31'),
(471, 277, 19, 31, 1, 1, '2024-11-25 19:38:33'),
(472, 277, 13, 31, 1, 0, '2024-11-25 19:38:35'),
(473, 278, 14, 31, 1, 0, '2024-11-25 19:38:40'),
(474, 279, 17, 31, 1, 0, '2024-11-25 19:38:43'),
(475, 280, 9, 31, 1, 0, '2024-11-25 19:38:55'),
(476, 281, 8, 31, 1, 0, '2024-11-25 19:39:05'),
(477, 282, 25, 10, 1, 0, '2024-11-25 20:19:44'),
(478, 283, 21, 31, 1, 1, '2024-11-25 20:58:37'),
(479, 283, 22, 31, 1, 1, '2024-11-25 20:58:44'),
(480, 283, 33, 31, 1, 0, '2024-11-25 20:58:54'),
(481, 284, 12, 32, 1, 0, '2024-11-25 23:13:56'),
(482, 285, 20, 32, 1, 1, '2024-11-25 23:14:25'),
(483, 285, 8, 32, 1, 1, '2024-11-25 23:14:30'),
(484, 285, 3, 32, 1, 1, '2024-11-25 23:14:33'),
(485, 285, 10, 32, 1, 1, '2024-11-25 23:14:36'),
(486, 285, 4, 32, 1, 1, '2024-11-25 23:14:42'),
(487, 285, 11, 32, 1, 0, '2024-11-25 23:14:46'),
(488, 286, 2, 32, 1, 1, '2024-11-25 23:15:50'),
(489, 286, 1, 32, 1, 0, '2024-11-25 23:15:53'),
(490, 287, 35, 10, 1, 0, '2024-11-25 23:31:59'),
(491, 288, 31, 10, 1, 1, '2024-11-25 23:47:19'),
(492, 288, 36, 10, 1, 0, '2024-11-25 23:47:25');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `reporte`
--

CREATE TABLE `reporte` (
  `id_reporte` int(11) NOT NULL,
  `id_usuario` int(11) DEFAULT NULL,
  `id_pregunta` int(11) DEFAULT NULL,
  `descripcion` varchar(255) DEFAULT NULL,
  `fecha_reporte` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `reporte`
--

INSERT INTO `reporte` (`id_reporte`, `id_usuario`, `id_pregunta`, `descripcion`, `fecha_reporte`) VALUES
(4, 32, 12, 'Respuestas equivocadas', '2024-11-25 22:14:10'),
(5, 32, 11, 'Formulación incorrecta', '2024-11-25 22:14:52');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `respuesta`
--

CREATE TABLE `respuesta` (
  `id_respuesta` int(11) NOT NULL,
  `id_pregunta` int(11) NOT NULL,
  `descripcion` varchar(255) NOT NULL,
  `correcta` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `respuesta`
--

INSERT INTO `respuesta` (`id_respuesta`, `id_pregunta`, `descripcion`, `correcta`) VALUES
(5, 1, 'George Washington', 1),
(6, 1, 'Abraham Lincoln', 0),
(7, 1, 'Thomas Jefferson', 0),
(8, 1, 'John Adams', 0),
(9, 2, '1914', 1),
(10, 2, '1918', 0),
(11, 2, '1939', 0),
(12, 2, '1945', 0),
(13, 3, 'Los egipcios', 1),
(14, 3, 'Los romanos', 0),
(15, 3, 'Los griegos', 0),
(16, 3, 'Los mayas', 0),
(17, 4, 'Cristóbal Colón', 1),
(18, 4, 'Ferdinand Magellan', 0),
(19, 4, 'Marco Polo', 0),
(20, 4, 'Vasco da Gama', 0),
(21, 5, 'La toma de la Bastilla', 1),
(22, 5, 'La declaración de independencia', 0),
(23, 5, 'La batalla de Waterloo', 0),
(24, 5, 'La revolución industrial', 0),
(25, 6, 'Friends', 1),
(26, 6, 'Seinfeld', 0),
(27, 6, 'The Big Bang Theory', 0),
(28, 6, 'How I Met Your Mother', 0),
(29, 7, 'Parasite', 1),
(30, 7, '1917', 0),
(31, 7, 'Joker', 0),
(32, 7, 'Once Upon a Time in Hollywood', 0),
(33, 8, 'Moana', 1),
(34, 8, 'Frozen', 0),
(35, 8, 'Zootopia', 0),
(36, 8, 'Toy Story', 0),
(37, 9, 'La Marcha de la Libertad', 1),
(38, 9, 'El Himno Nacional', 0),
(39, 9, 'La Canción de la Alegría', 0),
(40, 9, 'El Canto de los Trabajadores', 0),
(41, 10, 'Spider-Man', 1),
(42, 10, 'Batman', 0),
(43, 10, 'Superman', 0),
(44, 10, 'Iron Man', 0),
(45, 11, 'El Amazonas', 1),
(46, 11, 'El Nilo', 0),
(47, 11, 'El Yangtsé', 0),
(48, 11, 'El Misisipi', 0),
(49, 12, 'África', 1),
(50, 12, 'Asia', 0),
(51, 12, 'América del Sur', 0),
(52, 12, 'Oceanía', 0),
(53, 13, 'Tokio', 1),
(54, 13, 'Seúl', 0),
(55, 13, 'Pekín', 0),
(56, 13, 'Bangkok', 0),
(57, 14, 'Suecia', 1),
(58, 14, 'Noruega', 0),
(59, 14, 'Finlandia', 0),
(60, 14, 'Dinamarca', 0),
(61, 15, 'Everest', 1),
(62, 15, 'K2', 0),
(63, 15, 'Kilimanjaro', 0),
(64, 15, 'Mont Blanc', 0),
(65, 16, 'H2O', 1),
(66, 16, 'CO2', 0),
(67, 16, 'O2', 0),
(68, 16, 'H2', 0),
(69, 17, 'Marte', 1),
(70, 17, 'Venus', 0),
(71, 17, 'Júpiter', 0),
(72, 17, 'Saturno', 0),
(73, 18, 'La piel', 1),
(74, 18, 'El hígado', 0),
(75, 18, 'El corazón', 0),
(76, 18, 'Los pulmones', 0),
(77, 19, 'Química', 1),
(78, 19, 'Física', 0),
(79, 19, 'Biología', 0),
(80, 19, 'Geología', 0),
(81, 20, 'Big Bang', 1),
(82, 20, 'Teoría de la relatividad', 0),
(83, 20, 'Evolución', 0),
(84, 20, 'Teoría cuántica', 0),
(85, 21, '11', 1),
(86, 21, '7', 0),
(87, 21, '5', 0),
(88, 21, '12', 0),
(89, 22, 'La Copa del Mundo de la FIFA', 1),
(90, 22, 'Los Juegos Olímpicos', 0),
(91, 22, 'La Super Bowl', 0),
(92, 22, 'La Champions League', 0),
(93, 23, 'Francia', 1),
(94, 23, 'Alemania', 0),
(95, 23, 'Argentina', 0),
(96, 23, 'Brasil', 0),
(97, 24, 'Fútbol', 1),
(98, 24, 'Baloncesto', 0),
(99, 24, 'Tenis', 0),
(100, 24, 'Golf', 0),
(101, 25, 'Tenis', 1),
(102, 25, 'Béisbol', 0),
(103, 25, 'Fútbol', 0),
(104, 25, 'Rugby', 0),
(105, 26, 'Leonardo da Vinci', 1),
(106, 26, 'Pablo Picasso', 0),
(107, 26, 'Vincent van Gogh', 0),
(108, 26, 'Claude Monet', 0),
(109, 27, 'Impresionismo', 1),
(110, 27, 'Cubismo', 0),
(111, 27, 'Surrealismo', 0),
(112, 27, 'Expresionismo', 0),
(113, 28, 'Machu Picchu', 1),
(114, 28, 'Tikal', 0),
(115, 28, 'Chichen Itza', 0),
(116, 28, 'Stonehenge', 0),
(117, 29, 'Escultura', 1),
(118, 29, 'Pintura', 0),
(119, 29, 'Dibujo', 0),
(120, 29, 'Grabado', 0),
(121, 30, 'Galería de la Academia', 1),
(122, 30, 'El Louvre', 0),
(123, 30, 'El Prado', 0),
(124, 30, 'El Met', 0),
(125, 31, '4', 1),
(126, 31, '3', 0),
(127, 31, '5', 0),
(128, 31, '6', 0),
(129, 32, 'Es una constante matemática', 1),
(130, 32, 'Es un tipo de triángulo', 0),
(131, 32, 'Es una función', 0),
(132, 32, 'Es un número entero', 0),
(133, 33, 'π * r^2', 1),
(134, 33, '2 * π * r', 0),
(135, 33, 'a * b', 0),
(136, 33, 'l^2', 0),
(137, 34, '6', 1),
(138, 34, '5', 0),
(139, 34, '7', 0),
(140, 34, '4', 0),
(141, 35, 'Un número que solo es divisible por 1 y por sí mismo', 1),
(142, 35, 'Un número divisible por 2', 0),
(143, 35, 'Un número que termina en 0', 0),
(144, 35, 'Un número que tiene más de dos divisores', 0),
(145, 36, 'París', 1),
(146, 36, 'Londres', 0),
(147, 36, 'Berlín', 0),
(148, 36, 'Madrid', 0),
(149, 37, 'Gabriel García Márquez', 1),
(150, 37, 'Mario Vargas Llosa', 0),
(151, 37, 'Julio Cortázar', 0),
(152, 37, 'Pablo Neruda', 0),
(153, 38, 'Amazonas', 1),
(154, 38, 'Nilo', 0),
(155, 38, 'Yangtsé', 0),
(156, 38, 'Misisipi', 0),
(157, 39, 'China', 1),
(158, 39, 'India', 0),
(159, 39, 'EEUU', 0),
(160, 39, 'Indonesia', 0),
(161, 40, '1969', 1),
(162, 40, '1971', 0),
(163, 40, '1959', 0),
(164, 40, '1975', 0),
(165, 41, 'HyperText Markup Language', 1),
(166, 41, 'HyperText Markdown Language', 0),
(167, 41, 'HighText Machine Language', 0),
(168, 41, 'HyperTool MultiLanguage', 0),
(169, 42, 'Python', 1),
(170, 42, 'Java', 0),
(171, 42, 'C#', 0),
(172, 42, 'JavaScript', 0),
(173, 43, 'Un bloque de código que realiza una tarea específica', 1),
(174, 43, 'Una variable global', 0),
(175, 43, 'Un tipo de dato', 0),
(176, 43, 'Un operador matemático', 0),
(177, 44, 'Una estructura que permite repetir un bloque de código', 1),
(178, 44, 'Una función', 0),
(179, 44, 'Una variable', 0),
(180, 44, 'Un comentario', 0),
(181, 45, 'Git', 1),
(182, 45, 'SVN', 0),
(183, 45, 'Mercurial', 0),
(184, 45, 'CVS', 0),
(185, 46, 'Dar jaque mate al rey del oponente', 1),
(186, 46, 'Capturar todas las piezas del oponente', 0),
(187, 46, 'Hacer el movimiento más rápido', 0),
(188, 46, 'Controlar el centro del tablero', 0),
(189, 47, 'Pac-Man', 1),
(190, 47, 'Donkey Kong', 0),
(191, 47, 'Space Invaders', 0),
(192, 47, 'Pong', 0),
(193, 48, 'Juego de aventuras', 1),
(194, 48, 'Juego de estrategia', 0),
(195, 48, 'Juego de deportes', 0),
(196, 48, 'Juego de rol', 0),
(197, 49, 'Ser el último jugador en pie', 1),
(198, 49, 'Comprar propiedades', 0),
(199, 49, 'Hacer trampa', 0),
(200, 49, 'Aumentar el dinero', 0),
(201, 50, 'Completar un juego lo más rápido posible', 1),
(202, 50, 'Hacer todos los logros', 0),
(203, 50, 'Jugar en modo difícil', 0),
(204, 50, 'Obtener todas las puntuaciones', 0),
(205, 51, 'Paella', 1),
(206, 51, 'Tortilla', 0),
(207, 51, 'Gazpacho', 0),
(208, 51, 'Pisto', 0),
(209, 52, 'Un plato de arroz y pescado', 1),
(210, 52, 'Un tipo de fideos', 0),
(211, 52, 'Un tipo de postre', 0),
(212, 52, 'Un plato a la parrilla', 0),
(213, 53, 'Harina', 1),
(214, 53, 'Leche', 0),
(215, 53, 'Agua', 0),
(216, 53, 'Aceite', 0),
(217, 54, 'Aguacate', 1),
(218, 54, 'Tomate', 0),
(219, 54, 'Cebolla', 0),
(220, 54, 'Pimiento', 0),
(221, 55, 'Cerveza', 1),
(222, 55, 'Vino', 0),
(223, 55, 'Tequila', 0),
(224, 55, 'Sidra', 0),
(225, 56, 'Lewis Hamilton', 1),
(226, 56, 'Michael Schumacher', 0),
(227, 56, 'Ayrton Senna', 0),
(228, 56, 'Sebastian Vettel', 0),
(229, 57, '1950', 1),
(230, 57, '1965', 0),
(231, 57, '1975', 0),
(232, 57, '1980', 0),
(233, 58, 'Ferrari', 1),
(234, 58, 'Mercedes', 0),
(235, 58, 'Red Bull', 0),
(236, 58, 'McLaren', 0),
(237, 59, 'Drag Reduction System', 1),
(238, 59, 'Dynamic Racing System', 0),
(239, 59, 'Driving Response System', 0),
(240, 59, 'Driver Regulation System', 0),
(241, 60, '78 vueltas', 1),
(242, 60, '60 vueltas', 0),
(243, 60, '90 vueltas', 0),
(244, 60, '100 vueltas', 0),
(245, 61, 'Paracetamol', 1),
(246, 61, 'Ir al medico', 0),
(247, 61, 'Hepatalgina', 0),
(248, 61, 'Buscapina', 0);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `usuario`
--

CREATE TABLE `usuario` (
  `id` int(11) NOT NULL,
  `nombre` varchar(255) NOT NULL,
  `apellido` varchar(255) NOT NULL,
  `nombreusuario` varchar(255) NOT NULL,
  `contrasenia` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `añonacimiento` date NOT NULL,
  `ubicacion` varchar(255) NOT NULL,
  `fecharegistro` date NOT NULL,
  `fotoperfil` varchar(255) DEFAULT NULL,
  `sexo` varchar(50) DEFAULT NULL,
  `verificado` tinyint(1) NOT NULL DEFAULT 0,
  `musica` tinyint(1) NOT NULL DEFAULT 0,
  `sonido` tinyint(1) NOT NULL DEFAULT 0,
  `puntaje_usuario` int(11) DEFAULT 0,
  `rango` int(11) NOT NULL DEFAULT 3,
  `principiante` tinyint(1) NOT NULL DEFAULT 1,
  `dificultad` int(11) NOT NULL DEFAULT 1,
  `respondidasusuario` int(11) DEFAULT 0,
  `aciertosusuario` int(11) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `usuario`
--

INSERT INTO `usuario` (`id`, `nombre`, `apellido`, `nombreusuario`, `contrasenia`, `email`, `añonacimiento`, `ubicacion`, `fecharegistro`, `fotoperfil`, `sexo`, `verificado`, `musica`, `sonido`, `puntaje_usuario`, `rango`, `principiante`, `dificultad`, `respondidasusuario`, `aciertosusuario`) VALUES
(7, 'Victoria', 'Schmuker', 'editor', '123', 'vicky@gmail.com', '2000-01-01', '-34.66903569482507, -58.560749358166504', '2024-10-28', '7.jpg', 'f', 1, 0, 0, 0, 2, 1, 1, 0, 0),
(9, 'Julián Gabriel', 'Schmuker', 'admin', '123', 'test@unlam.edu.ar', '0000-00-00', '-34.689328289275, -58.63649494074707', '2024-10-29', '9.jpg', 'm', 1, 0, 0, 0, 1, 1, 1, 0, 0),
(10, 'Lucas', 'Rios', 'jugador1', '123', 'lucas@gmail.com', '2000-01-01', '-34.657316662962344, -58.58199245356445', '2024-10-30', '10.jpeg', 'm', 1, 0, 1, 310, 3, 1, 2, 20, 8),
(31, 'Facundo', 'Darano', 'jugador2', '123', 'facu@gmail.com', '2000-01-01', '-34.66988278951427, -58.56958991907959', '2024-11-09', '31.jpeg', 'm', 1, 0, 1, 80, 3, 1, 2, 14, 8),
(32, 'Alejandro', 'Noble', 'jugador3', '123', 'aleNoble@gmail.com', '1994-02-02', '-34.62292656745384, -58.37904579066162', '2024-11-22', '32.jpg', 'm', 1, 0, 0, 60, 3, 1, 1, 9, 6),
(33, 'Lola', 'Pérez', 'jugador4', '123', 'lola@gmail.com', '1996-07-11', '-28.073262193664604, -64.1903413', '2024-11-22', '33.jpg', 'm', 1, 1, 0, 0, 3, 1, 1, 0, 0),
(34, 'jugador5', 'perez', 'jugador5', '123', 'perez@gmail.com', '1989-07-20', '-34.67203578286181, -58.55555660151367', '2024-11-25', '34.jpeg', 'x', 1, 1, 1, 0, 3, 1, 1, 0, 0);

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `categoria`
--
ALTER TABLE `categoria`
  ADD PRIMARY KEY (`id_categoria`);

--
-- Indices de la tabla `estado`
--
ALTER TABLE `estado`
  ADD PRIMARY KEY (`id_estado`);

--
-- Indices de la tabla `partida`
--
ALTER TABLE `partida`
  ADD PRIMARY KEY (`id_partida`),
  ADD KEY `id_usuario` (`id_usuario`);

--
-- Indices de la tabla `pregunta`
--
ALTER TABLE `pregunta`
  ADD PRIMARY KEY (`id_pregunta`),
  ADD KEY `id_categoria` (`id_categoria`),
  ADD KEY `fk_estado` (`estado`);

--
-- Indices de la tabla `preguntapartida`
--
ALTER TABLE `preguntapartida`
  ADD PRIMARY KEY (`id_preguntaPartida`),
  ADD KEY `id_partida` (`id_partida`),
  ADD KEY `id_pregunta` (`id_pregunta`);

--
-- Indices de la tabla `reporte`
--
ALTER TABLE `reporte`
  ADD PRIMARY KEY (`id_reporte`),
  ADD KEY `id_usuario` (`id_usuario`),
  ADD KEY `id_pregunta` (`id_pregunta`);

--
-- Indices de la tabla `respuesta`
--
ALTER TABLE `respuesta`
  ADD PRIMARY KEY (`id_respuesta`),
  ADD KEY `id_pregunta` (`id_pregunta`);

--
-- Indices de la tabla `usuario`
--
ALTER TABLE `usuario`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `categoria`
--
ALTER TABLE `categoria`
  MODIFY `id_categoria` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT de la tabla `estado`
--
ALTER TABLE `estado`
  MODIFY `id_estado` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT de la tabla `partida`
--
ALTER TABLE `partida`
  MODIFY `id_partida` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=289;

--
-- AUTO_INCREMENT de la tabla `pregunta`
--
ALTER TABLE `pregunta`
  MODIFY `id_pregunta` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=62;

--
-- AUTO_INCREMENT de la tabla `preguntapartida`
--
ALTER TABLE `preguntapartida`
  MODIFY `id_preguntaPartida` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=493;

--
-- AUTO_INCREMENT de la tabla `reporte`
--
ALTER TABLE `reporte`
  MODIFY `id_reporte` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT de la tabla `respuesta`
--
ALTER TABLE `respuesta`
  MODIFY `id_respuesta` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=249;

--
-- AUTO_INCREMENT de la tabla `usuario`
--
ALTER TABLE `usuario`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=35;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `partida`
--
ALTER TABLE `partida`
  ADD CONSTRAINT `partida_ibfk_1` FOREIGN KEY (`id_usuario`) REFERENCES `usuario` (`id`);

--
-- Filtros para la tabla `pregunta`
--
ALTER TABLE `pregunta`
  ADD CONSTRAINT `fk_estado` FOREIGN KEY (`estado`) REFERENCES `estado` (`id_estado`),
  ADD CONSTRAINT `pregunta_ibfk_1` FOREIGN KEY (`id_categoria`) REFERENCES `categoria` (`id_categoria`) ON DELETE CASCADE;

--
-- Filtros para la tabla `preguntapartida`
--
ALTER TABLE `preguntapartida`
  ADD CONSTRAINT `preguntapartida_ibfk_1` FOREIGN KEY (`id_partida`) REFERENCES `partida` (`id_partida`),
  ADD CONSTRAINT `preguntapartida_ibfk_2` FOREIGN KEY (`id_pregunta`) REFERENCES `pregunta` (`id_pregunta`);

--
-- Filtros para la tabla `reporte`
--
ALTER TABLE `reporte`
  ADD CONSTRAINT `reporte_ibfk_1` FOREIGN KEY (`id_usuario`) REFERENCES `usuario` (`id`),
  ADD CONSTRAINT `reporte_ibfk_2` FOREIGN KEY (`id_pregunta`) REFERENCES `pregunta` (`id_pregunta`);

--
-- Filtros para la tabla `respuesta`
--
ALTER TABLE `respuesta`
  ADD CONSTRAINT `respuesta_ibfk_1` FOREIGN KEY (`id_pregunta`) REFERENCES `pregunta` (`id_pregunta`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
