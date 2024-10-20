-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 20-10-2024 a las 04:24:50
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
-- Base de datos: `pokedexmvc`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `pokemon`
--

CREATE TABLE `pokemon` (
  `id` int(11) NOT NULL,
  `number` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `image` varchar(255) NOT NULL,
  `type_image` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `pokemon`
--

INSERT INTO `pokemon` (`id`, `number`, `name`, `image`, `type_image`) VALUES
(1, 151, 'Bulbasaur', '001.png', 'grass.png'),
(2, 152, 'Ivysaur', '002.png', 'grass.png'),
(3, 153, 'Venusaur', '003.png', 'grass.png'),
(4, 154, 'Charmander', '004.png', 'fire.png'),
(5, 445, 'JULIANN', '001.png', '');

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `pokemon`
--
ALTER TABLE `pokemon`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `pokemon`
--
ALTER TABLE `pokemon`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
