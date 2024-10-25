-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 21-10-2024 a las 23:41:57
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
  `ciudad` varchar(255) DEFAULT NULL,
  `fecharegistro` date NOT NULL,
  `fotoperfil` varchar(255) DEFAULT NULL,
  `sexo` varchar(50) DEFAULT NULL,
  `verificado` tinyint(1) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `usuario`
--

INSERT INTO `usuario` (`id`, `nombre`, `apellido`, `nombreusuario`, `contrasenia`, `email`, `añonacimiento`, `ciudad`, `fecharegistro`, `fotoperfil`, `sexo`, `verificado`) VALUES
(1, 'julian', 'ker', 'julian', '123', 'julian@gmail.com', '1990-03-15', 'Buenos Aires', '2024-01-01', 'perfil.jpg', 'M', 1),
(2, 'lucas', 'lucas', 'german', '11', 'germanschmuker@gmail.com', '2024-10-09', 'Argentina', '2024-10-21', 'entradas.jpg', 'm', 0),
(3, 'as', 'sd', 'ju', 'e', 'julianschker@gmail.com', '2024-10-03', 'Argentina', '2024-10-21', 'entradas.jpg', 'm', 1);

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `usuario`
--
ALTER TABLE `usuario`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `usuario`
--
ALTER TABLE `usuario`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;


CREATE TABLE `categoria` (
                             `id` tinyint(4) NOT NULL,
                             `nombre` varchar(30) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `categoria`
--

INSERT INTO `categoria` (`id`, `nombre`) VALUES
                                             (1, 'Historia'),
                                             (2, 'Entretenimiento'),
                                             (3, 'Geografia'),
                                             (4, 'Ciencia'),
                                             (5, 'Deporte'),
                                             (6, 'Arte'),
                                             (7, 'Matematica'),
                                             (8, 'Cultura general'),
                                             (9, 'Programacion'),
                                             (10, 'Juegos'),
                                             (11, 'Gastronomia');

CREATE TABLE `partida` (
                           `id` int(11) NOT NULL,
                           `id_jugador_1` int(11) NOT NULL,

    --
    --  FUTUROS VALORES DE LAS PARTIDAS JUGADAS QUE TENGAMOS, FALTAN AGREGAR DATOS A PARTIDA PERO ES PARA EMPEZAR A MODELAR
    --

CREATE TABLE `pregunta` (
  `id` int(11) NOT NULL,
  `pregunta` varchar(255) NOT NULL,
  `id_categoria` tinyint(4) NOT NULL,
  `id_dificultad` tinyint(1) NOT NULL DEFAULT 1,
  `estado` tinyint(1) NOT NULL DEFAULT 1,
  `apariciones` int(11) NOT NULL DEFAULT 0,
  `aciertos` int(11) NOT NULL DEFAULT 0,
  `fecha_alta` date NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

    --
    -- Volcado de datos tabla pregunta
    --

INSERT INTO `pregunta` (`id`, `pregunta`, `id_categoria`, `id_dificultad`, `estado`, `apariciones`, `aciertos`, `fecha_alta`) VALUES
(8, 'Quién es el autor de Mafalda?', 2, 1, 1, 10, 5, '2023-11-14'),
(9, 'Cómo se llama el perro de Casados con Hijos?', 2, 1, 1, 10, 6, '2023-11-14'),
(10, 'Quién es el vocalista de Soda Stereo?', 2, 1, 1, 10, 5, '2023-11-14'),
(11, 'Cómo se llaman los vecinos de Casados con Hijos?', 2, 1, 1, 10, 5, '2023-11-14'),
(12, 'Quién fue el conductor del Muro Infernal?', 2, 2, 0, 12, 8, '2023-11-14'),
(13, 'Cuál película fue protagonizada por Darin que a su vez fue premiada por un Oscar?', 2, 1, 1, 10, 5, '2023-11-14'),
(14, 'Cuál de estas bandas no es de Argentina?', 2, 1, 1, 10, 6, '2023-11-14'),
(15, 'Quién era el enemigo principal de Hijitus?', 2, 1, 1, 10, 5, '2023-11-14'),
(16, 'En qué provincia Argentina se encuentra principalmente Vaca Muerta?', 3, 1, 1, 10, 5, '2023-11-14'),

CREATE TABLE `respuesta` (
`id` int(11) NOT NULL,
`id_pregunta` int(11) NOT NULL,
`descripcion` varchar(255) NOT NULL,
`correcta` tinyint(1) NOT NULL DEFAULT 0,
`name_respuesta` varchar(15) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

    --
    -- Volcado de datos tabla respuesta
    --

INSERT INTO `respuesta` (`id`, `id_pregunta`, `descripcion`, `correcta`, `name_respuesta`) VALUES
(509, 8, 'Pepo', 0, 'invalida-1'),
(510, 8, 'Quintero', 0, 'invalida-2'),
(511, 8, 'Nik', 0, 'invalida-3'),
(512, 8, 'Quino', 1, 'valida'),
(513, 9, 'Pluto', 0, 'invalida-1'),
(514, 9, 'Betun', 0, 'invalida-2'),
(515, 9, 'Fatiga', 1, 'valida'),
(516, 9, 'Chicho', 0, 'invalida-3'),
(517, 10, 'Roberto Musso.', 0, 'invalida-1'),
(518, 10, 'Adrian Dargelos.', 0, 'invalida-2'),
(519, 10, 'Carlos Alberto Solari', 0, 'invalida-3'),
(520, 10, 'Gustavo Cerati', 1, 'valida'),
(521, 11, 'Dardo y Maria Elena', 1, 'valida'),
(522, 11, 'Julian y Barbara', 0, 'invalida-1'),
(523, 11, 'Cacho y Beatriz', 0, 'invalida-2'),
(524, 11, 'Diego y Mariana', 0, 'invalida-3'),
(525, 12, 'Ivan de Pineda', 0, 'invalida-1'),
(526, 12, 'Marley', 1, 'valida'),
(527, 12, 'Santiago del Moro', 0, 'invalida-2'),
(528, 12, 'Guido Kaczka', 0, 'invalida-3'),
(529, 13, 'Un cuento Chino', 0, 'invalida-1'),
(530, 13, 'El Secreto de sus Ojos', 1, 'valida'),
(531, 13, 'El hijo de la Novia.', 0, 'invalida-2'),
(532, 13, 'La Odisea de los Giles', 0, 'invalida-3'),
(533, 14, 'Los Piojos', 0, 'invalida-1'),
(534, 14, 'Babasonicos', 0, 'invalida-2'),
(535, 14, 'El Cuarteto de Nos', 1, 'valida'),
(536, 14, 'Los Redondos', 0, 'invalida-3'),
(537, 15, 'Doctor Doofenshmirtz', 0, 'invalida-1'),
(538, 15, 'Doctor Nefario', 0, 'invalida-2'),
(539, 15, 'Doctor Muerte', 0, 'invalida-3'),
(540, 15, 'Doctor Neurus', 1, 'valida'),
(541, 16, 'Neuquen', 1, 'valida'),
(542, 16, 'Mendoza', 0, 'invalida-1'),
(543, 16, 'La Rioja', 0, 'invalida-2'),
(544, 16, 'Cordoba', 0, 'invalida-3')

CREATE TABLE `respuestas_partida` (
                                      `id_partida` int(11) NOT NULL,
                                      `id_respuesta` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- FUTUROS VALORES DE LAS RESPUESTAS ACERTADAS EN UNA PARTIDA PARA DESPUES CREAR EL PUNTAJE
--


--
-- INDICES
--

ALTER TABLE `categoria`
    ADD PRIMARY KEY (`id`);


ALTER TABLE `partida`
    ADD PRIMARY KEY (`id`),
  ADD KEY `id_jugador_1` (`id_jugador_1`),

ALTER TABLE `pregunta`
    ADD PRIMARY KEY (`id`),
  ADD KEY `id_categoria` (`id_categoria`),

ALTER TABLE `respuesta`
    ADD PRIMARY KEY (`id`),
  ADD KEY `respuesta_ibfk_1` (`id_pregunta`);

ALTER TABLE `respuestas_partida`
    ADD PRIMARY KEY (`id_partida`,`id_respuesta`),
  ADD KEY `respuestas_partida_ibfk_2` (`id_respuesta`);


--
-- Auto Increment
--
ALTER TABLE `partida`
    MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=200;

ALTER TABLE `pregunta`
    MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

ALTER TABLE `respuesta`
    MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;