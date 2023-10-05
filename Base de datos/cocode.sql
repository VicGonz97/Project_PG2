

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `usuario`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `administrador`
--

CREATE TABLE `administrador` (
  `id_admin` int(11) NOT NULL,
  `nombre_completo` varchar(100) NOT NULL,
  `nombre_admin` varchar(60) NOT NULL,
  `clave` text NOT NULL,
  `email_admin` varchar(100) NOT NULL,
  `telefono` text COLLATE utf8_spanish2_ci NOT NULL,
  `dpi` text COLLATE utf8_spanish2_ci NOT NULL,
   `rol` varchar(30) COLLATE utf8_spanish2_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Volcado de datos para la tabla `administrador`
--

INSERT INTO `administrador` (`id_admin`, `nombre_completo`, `nombre_admin`, `clave`, `email_admin`, `telefono`, `dpi`, `rol`) VALUES
(1, 'Victor Gonzalez', 'Cocode2023', '2a2e9a58102784ca18e2605a4e727b5f', 'vgonzalez.vgl57@gmail.com', '50424169', '1111111111111', 'Administrador');

-- -------------------------------------------------
--
-- Estructura de tabla para la tabla `cliente`
--

CREATE TABLE `usuario` (
  `id_usuario` int(11) NOT NULL,
  `nombre_completo` varchar(100) COLLATE utf8_spanish2_ci NOT NULL,
  `nombre_usuario` varchar(100) COLLATE utf8_spanish2_ci NOT NULL,
  `clave` text COLLATE utf8_spanish2_ci NOT NULL,
  `email_usuario` varchar(100) COLLATE utf8_spanish2_ci NOT NULL,
  `telefono` text COLLATE utf8_spanish2_ci NOT NULL,
  `dpi` text COLLATE utf8_spanish2_ci NOT NULL,
   `rol` varchar(30) COLLATE utf8_spanish2_ci NOT NULL
  
  
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish2_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `Cocode`
--

CREATE TABLE `cocode` (
  `id_cocode` int(11) NOT NULL,
  `nombre` varchar(100) COLLATE utf8_spanish2_ci NOT NULL,
  `apellido` varchar(100) COLLATE utf8_spanish2_ci NOT NULL,
  `telefono` text COLLATE utf8_spanish2_ci NOT NULL,
  `dpi` text COLLATE utf8_spanish2_ci NOT NULL
   `cargo` varchar(30) COLLATE utf8_spanish2_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish2_ci;



--
-- Estructura de tabla para la tabla `Contribuyente`
--

CREATE TABLE `contribuyente` (
  `id_contr` int(11) NOT NULL,
  `nombre` varchar(100) COLLATE utf8_spanish2_ci NOT NULL,
  `apellido` varchar(100) COLLATE utf8_spanish2_ci NOT NULL,
  `fecha_nacimiento` DATE NOT NULL,
  `edad` int(3) NOT NULL,
  `observaciones` varchar(100) COLLATE utf8_spanish2_ci NOT NULL,
  `telefono` text COLLATE utf8_spanish2_ci NOT NULL,
  `dpi` text COLLATE utf8_spanish2_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish2_ci;




CREATE TABLE asistencia (
    id_asistencia INT AUTO_INCREMENT PRIMARY KEY,
    nombre varchar(100) COLLATE utf8_spanish2_ci NOT NULL,
    apellido varchar(100) COLLATE utf8_spanish2_ci NOT NULL,
    dpi text COLLATE utf8_spanish2_ci NOT NULL,
    asistio TINYINT(1),  -- Puedes usar un campo booleano (0 o 1) para registrar si el contribuyente asistió o no
    fecha_registro TIMESTAMP DEFAULT CURRENT_TIMESTAMP  -- Puedes usar un campo de fecha y hora para registrar cuándo se tomó la asistencia
);

CREATE TABLE contabilidad (
    id_contabilidad INT AUTO_INCREMENT PRIMARY KEY,
    nombre varchar(100) COLLATE utf8_spanish2_ci NOT NULL,
    apellido varchar(100) COLLATE utf8_spanish2_ci NOT NULL,
    dpi text COLLATE utf8_spanish2_ci NOT NULL,
    cantidad DECIMAL(10, 2) NOT NULL,
    fecha_registro TIMESTAMP DEFAULT CURRENT_TIMESTAMP  -- Puedes usar un campo de fecha y hora 
);


--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `administrador`
--
ALTER TABLE `administrador`
  ADD PRIMARY KEY (`id_admin`);


--
-- Indices de la tabla `usuario`
--
ALTER TABLE `usuario`
  ADD PRIMARY KEY (`id_usuario`);


--
-- Indices de la tabla `cocode`
--
ALTER TABLE `cocode`
  ADD PRIMARY KEY (`id_cocode`);

--
-- Indices de la tabla `Contribuyente`
--
ALTER TABLE `contribuyente`
  ADD PRIMARY KEY (`id_contr`);
--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `administrador`
--
ALTER TABLE `administrador`
  MODIFY `id_admin` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
--
-- AUTO_INCREMENT de la tabla `usuario`
--
ALTER TABLE `usuario`
  MODIFY `id_usuario` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT de la tabla `cocode`
--
ALTER TABLE `cocode`
  MODIFY `id_cocode` int(11) NOT NULL AUTO_INCREMENT;

  -- AUTO_INCREMENT de la tabla `contribuyente`
--
ALTER TABLE `contribuyente`
  MODIFY `id_contr` int(11) NOT NULL AUTO_INCREMENT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

