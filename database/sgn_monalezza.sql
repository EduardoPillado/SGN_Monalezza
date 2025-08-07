-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jul 13, 2025 at 06:27 PM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `sgn_monalezza`
--

-- --------------------------------------------------------

--
-- Table structure for table `asistencia`
--

CREATE TABLE `asistencia` (
  `asistencia_pk` bigint(20) UNSIGNED NOT NULL,
  `empleado_fk` bigint(20) UNSIGNED NOT NULL,
  `fecha_asistencia` date NOT NULL,
  `hora_entrada` time NOT NULL,
  `hora_salida` time DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `asistencia`
--

INSERT INTO `asistencia` (`asistencia_pk`, `empleado_fk`, `fecha_asistencia`, `hora_entrada`, `hora_salida`) VALUES
(1, 2, '2024-11-11', '12:00:00', '22:00:00'),
(3, 2, '2024-11-12', '11:59:00', '23:30:00'),
(4, 2, '2024-11-14', '12:30:00', '21:30:00'),
(5, 2, '2024-11-15', '12:05:00', '22:30:00'),
(6, 2, '2024-11-16', '11:50:00', '21:50:00'),
(7, 2, '2024-11-17', '12:11:00', '22:01:00'),
(8, 2, '2024-12-14', '17:28:00', '17:29:00'),
(9, 1, '2025-05-28', '13:20:00', '13:20:00');

-- --------------------------------------------------------

--
-- Table structure for table `cache`
--

CREATE TABLE `cache` (
  `key` varchar(255) NOT NULL,
  `value` mediumtext NOT NULL,
  `expiration` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `cache_locks`
--

CREATE TABLE `cache_locks` (
  `key` varchar(255) NOT NULL,
  `owner` varchar(255) NOT NULL,
  `expiration` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `cliente`
--

CREATE TABLE `cliente` (
  `cliente_pk` bigint(20) UNSIGNED NOT NULL,
  `nombre_cliente` varchar(50) NOT NULL,
  `domicilio_fk` bigint(20) UNSIGNED NOT NULL,
  `telefono_fk` bigint(20) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `cliente`
--

INSERT INTO `cliente` (`cliente_pk`, `nombre_cliente`, `domicilio_fk`, `telefono_fk`) VALUES
(1, 'Edwin Díaz', 1, 1),
(2, 'Carlos', 2, 2),
(3, 'Karla', 3, 3),
(4, 'Manuel', 4, 4);

-- --------------------------------------------------------

--
-- Table structure for table `corte_caja`
--

CREATE TABLE `corte_caja` (
  `corte_caja_pk` bigint(20) UNSIGNED NOT NULL,
  `fecha_corte_inicio` datetime NOT NULL,
  `fecha_corte_fin` datetime NOT NULL,
  `suma_efectivo_inicial` decimal(8,2) NOT NULL,
  `cantidad_ventas` int(11) NOT NULL,
  `ganancia_total` decimal(8,2) NOT NULL,
  `suma_gasto_servicios` decimal(8,2) NOT NULL,
  `utilidad_neta` decimal(8,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `corte_caja`
--

INSERT INTO `corte_caja` (`corte_caja_pk`, `fecha_corte_inicio`, `fecha_corte_fin`, `suma_efectivo_inicial`, `cantidad_ventas`, `ganancia_total`, `suma_gasto_servicios`, `utilidad_neta`) VALUES
(1, '2024-11-18 12:00:00', '2024-11-24 22:00:00', 1520.00, 6, 2670.00, 0.00, 1052.00),
(2, '2025-06-30 11:44:00', '2025-07-03 11:44:00', 10000.90, 0, 0.00, 0.00, 0.00),
(3, '2025-07-01 11:48:00', '2025-07-03 11:48:00', 5720.90, 1, 50.00, 0.00, 50.00),
(4, '2025-06-30 13:05:00', '2025-07-02 13:05:00', 10000.90, 1, 50.00, 0.00, 50.00),
(5, '2025-06-30 13:07:00', '2025-07-02 13:07:00', 10000.90, 1, 50.00, 0.00, 50.00),
(6, '2025-06-30 13:09:00', '2025-07-02 13:09:00', 10000.90, 1, 50.00, 300.00, -250.00),
(7, '2025-06-30 13:13:00', '2025-07-02 13:13:00', 10000.90, 1, 50.00, 300.00, 9750.90);

-- --------------------------------------------------------

--
-- Table structure for table `corte_empleado`
--

CREATE TABLE `corte_empleado` (
  `corte_empleado_pk` bigint(20) UNSIGNED NOT NULL,
  `corte_caja_fk` bigint(20) UNSIGNED NOT NULL,
  `empleado_fk` bigint(20) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `corte_empleado`
--

INSERT INTO `corte_empleado` (`corte_empleado_pk`, `corte_caja_fk`, `empleado_fk`) VALUES
(1, 1, 1),
(2, 3, 1),
(3, 4, 1),
(4, 5, 1),
(5, 6, 1),
(6, 7, 1);

-- --------------------------------------------------------

--
-- Table structure for table `detalle_efectivo`
--

CREATE TABLE `detalle_efectivo` (
  `detalle_efectivo_pk` bigint(20) UNSIGNED NOT NULL,
  `fecha_actual` datetime NOT NULL,
  `efectivo_inicial` decimal(8,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `detalle_efectivo`
--

INSERT INTO `detalle_efectivo` (`detalle_efectivo_pk`, `fecha_actual`, `efectivo_inicial`) VALUES
(1, '2024-11-18 12:00:00', 1520.00),
(2, '2025-07-01 00:00:00', 4050.00),
(3, '2025-07-01 00:00:00', 230.00),
(4, '2025-07-01 16:24:28', 2480.00),
(5, '2025-07-02 10:15:12', 1220.00),
(6, '2025-07-02 10:35:30', 20.00),
(7, '2025-07-02 10:35:38', 0.00),
(8, '2025-07-02 10:36:26', 2000.90),
(9, '2025-07-03 10:56:53', 234.00),
(10, '2025-07-04 11:15:02', 4032.00),
(11, '2025-07-05 08:31:15', 200.00),
(12, '2025-07-07 09:53:36', 354.00),
(13, '2025-07-08 10:15:51', 6788.00),
(14, '2025-07-09 12:48:22', 234.00),
(15, '2025-07-10 10:02:18', 234.00),
(16, '2025-07-12 08:46:50', 200.00),
(17, '2025-07-13 09:01:34', 899.00);

-- --------------------------------------------------------

--
-- Table structure for table `detalle_ingrediente`
--

CREATE TABLE `detalle_ingrediente` (
  `detalle_ingrediente_pk` bigint(20) UNSIGNED NOT NULL,
  `producto_fk` bigint(20) UNSIGNED NOT NULL,
  `ingrediente_fk` bigint(20) UNSIGNED NOT NULL,
  `cantidad_necesaria` decimal(8,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `detalle_ingrediente`
--

INSERT INTO `detalle_ingrediente` (`detalle_ingrediente_pk`, `producto_fk`, `ingrediente_fk`, `cantidad_necesaria`) VALUES
(1, 1, 1, 500.00);

-- --------------------------------------------------------

--
-- Table structure for table `detalle_pedido`
--

CREATE TABLE `detalle_pedido` (
  `detalle_pedido_pk` bigint(20) UNSIGNED NOT NULL,
  `pedido_fk` bigint(20) UNSIGNED NOT NULL,
  `producto_fk` bigint(20) UNSIGNED NOT NULL,
  `cantidad_producto` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `detalle_pedido`
--

INSERT INTO `detalle_pedido` (`detalle_pedido_pk`, `pedido_fk`, `producto_fk`, `cantidad_producto`) VALUES
(1, 1, 1, 1),
(2, 1, 2, 8),
(3, 2, 2, 40),
(4, 3, 2, 13),
(5, 4, 2, 13),
(6, 5, 2, 12),
(7, 6, 2, 12),
(8, 7, 1, 1),
(9, 7, 2, 1),
(10, 8, 1, 1),
(11, 9, 1, 1),
(12, 9, 2, 4),
(13, 10, 2, 1),
(14, 11, 3, 1),
(15, 12, 1, 1),
(16, 13, 1, 1),
(17, 14, 3, 1),
(18, 15, 1, 1),
(19, 16, 1, 1),
(20, 17, 5, 1),
(21, 18, 1, 1),
(22, 19, 3, 1),
(23, 20, 2, 1),
(24, 21, 2, 1),
(25, 22, 2, 1),
(26, 23, 1, 1),
(27, 23, 2, 1),
(28, 23, 3, 1),
(29, 24, 1, 1),
(30, 24, 2, 1),
(31, 24, 4, 1),
(32, 24, 5, 1),
(33, 25, 2, 1);

-- --------------------------------------------------------

--
-- Table structure for table `domicilio`
--

CREATE TABLE `domicilio` (
  `domicilio_pk` bigint(20) UNSIGNED NOT NULL,
  `calle` varchar(50) NOT NULL,
  `numero_externo` int(11) NOT NULL,
  `numero_interno` varchar(11) DEFAULT NULL,
  `referencias` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `domicilio`
--

INSERT INTO `domicilio` (`domicilio_pk`, `calle`, `numero_externo`, `numero_interno`, `referencias`) VALUES
(1, 'Hidalgo', 10, '8', NULL),
(2, 'Allende', 67, 'SN', 'Al lado de la presidencia'),
(3, 'Diamante', 30, 'SN', 'Al lado del club Pelicanos'),
(4, 'Fuentes', 30, 'SN', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `empleado`
--

CREATE TABLE `empleado` (
  `empleado_pk` bigint(20) UNSIGNED NOT NULL,
  `usuario_fk` bigint(20) UNSIGNED NOT NULL,
  `fecha_contratacion` date NOT NULL,
  `estatus_empleado` smallint(6) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `empleado`
--

INSERT INTO `empleado` (`empleado_pk`, `usuario_fk`, `fecha_contratacion`, `estatus_empleado`) VALUES
(1, 1, '2024-10-25', 1),
(2, 2, '2024-11-25', 1),
(3, 1, '2024-10-25', 1);

-- --------------------------------------------------------

--
-- Table structure for table `failed_jobs`
--

CREATE TABLE `failed_jobs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `uuid` varchar(255) NOT NULL,
  `connection` text NOT NULL,
  `queue` text NOT NULL,
  `payload` longtext NOT NULL,
  `exception` longtext NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `ingrediente`
--

CREATE TABLE `ingrediente` (
  `ingrediente_pk` bigint(20) UNSIGNED NOT NULL,
  `nombre_ingrediente` varchar(50) NOT NULL,
  `estatus_ingrediente` smallint(6) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `ingrediente`
--

INSERT INTO `ingrediente` (`ingrediente_pk`, `nombre_ingrediente`, `estatus_ingrediente`) VALUES
(1, 'Salsa de Tomate', 1);

-- --------------------------------------------------------

--
-- Table structure for table `inventario`
--

CREATE TABLE `inventario` (
  `inventario_pk` bigint(20) UNSIGNED NOT NULL,
  `ingrediente_fk` bigint(20) UNSIGNED DEFAULT NULL,
  `producto_fk` bigint(20) UNSIGNED DEFAULT NULL,
  `tipo_gasto_fk` bigint(20) UNSIGNED DEFAULT NULL,
  `proveedor_fk` bigint(20) UNSIGNED NOT NULL,
  `precio_proveedor` decimal(8,2) NOT NULL,
  `fecha_inventario` datetime NOT NULL,
  `cantidad_inventario` decimal(8,2) NOT NULL,
  `cantidad_paquete` decimal(8,2) NOT NULL,
  `cantidad_parcial` decimal(8,2) NOT NULL DEFAULT 0.00,
  `cantidad_inventario_minima` decimal(8,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `inventario`
--

INSERT INTO `inventario` (`inventario_pk`, `ingrediente_fk`, `producto_fk`, `tipo_gasto_fk`, `proveedor_fk`, `precio_proveedor`, `fecha_inventario`, `cantidad_inventario`, `cantidad_paquete`, `cantidad_parcial`, `cantidad_inventario_minima`) VALUES
(1, 1, NULL, 14, 1, 40.00, '2024-11-23 18:02:00', -1.00, 1000.00, 500.00, 2.00),
(2, NULL, 2, 15, 1, 150.00, '2025-07-09 15:23:19', 1.00, 12.00, 11.00, 2.00);

-- --------------------------------------------------------

--
-- Table structure for table `jobs`
--

CREATE TABLE `jobs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `queue` varchar(255) NOT NULL,
  `payload` longtext NOT NULL,
  `attempts` tinyint(3) UNSIGNED NOT NULL,
  `reserved_at` int(10) UNSIGNED DEFAULT NULL,
  `available_at` int(10) UNSIGNED NOT NULL,
  `created_at` int(10) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `job_batches`
--

CREATE TABLE `job_batches` (
  `id` varchar(255) NOT NULL,
  `name` varchar(255) NOT NULL,
  `total_jobs` int(11) NOT NULL,
  `pending_jobs` int(11) NOT NULL,
  `failed_jobs` int(11) NOT NULL,
  `failed_job_ids` longtext NOT NULL,
  `options` mediumtext DEFAULT NULL,
  `cancelled_at` int(11) DEFAULT NULL,
  `created_at` int(11) NOT NULL,
  `finished_at` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `medio_pedido`
--

CREATE TABLE `medio_pedido` (
  `medio_pedido_pk` bigint(20) UNSIGNED NOT NULL,
  `nombre_medio_pedido` varchar(50) NOT NULL,
  `estatus_medio_pedido` smallint(6) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `medio_pedido`
--

INSERT INTO `medio_pedido` (`medio_pedido_pk`, `nombre_medio_pedido`, `estatus_medio_pedido`) VALUES
(1, 'WhatsApp', 1),
(2, 'Messenger', 1),
(3, 'Teléfono', 1),
(4, 'Local', 1);

-- --------------------------------------------------------

--
-- Table structure for table `mesa`
--

CREATE TABLE `mesa` (
  `mesa_pk` bigint(20) UNSIGNED NOT NULL,
  `numero_mesa` int(11) NOT NULL,
  `ubicacion` text DEFAULT NULL,
  `estatus_mesa` smallint(6) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `mesa`
--

INSERT INTO `mesa` (`mesa_pk`, `numero_mesa`, `ubicacion`, `estatus_mesa`) VALUES
(1, 1, 'Primera a la derecha', 0),
(2, 2, 'Segunda a la derecha', 0),
(3, 3, 'Primera a la izquierda', 0),
(4, 4, 'Segunda a la izquierda', 0),
(5, 5, 'Barra', 0),
(6, 6, NULL, 1);

-- --------------------------------------------------------

--
-- Table structure for table `migrations`
--

CREATE TABLE `migrations` (
  `id` int(10) UNSIGNED NOT NULL,
  `migration` varchar(255) NOT NULL,
  `batch` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `migrations`
--

INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES
(1, '0001_01_01_000000_create_users_table', 1),
(2, '0001_01_01_000001_create_cache_table', 1),
(3, '0001_01_01_000002_create_jobs_table', 1),
(4, '2024_09_15_171041_create_rol_table', 1),
(5, '2024_09_15_171049_create_tipo_pago_table', 1),
(6, '2024_09_15_171249_create_tipo_gasto_table', 1),
(7, '2024_09_15_171305_create_telefono_table', 1),
(8, '2024_09_15_171313_create_domicilio_table', 1),
(9, '2024_09_15_171319_create_mesa_table', 1),
(10, '2024_09_15_171326_create_ingrediente_table', 1),
(11, '2024_09_15_171333_create_proveedor_table', 1),
(12, '2024_09_15_171343_create_medio_pedido_table', 1),
(13, '2024_09_15_171351_create_tipo_producto_table', 1),
(14, '2024_09_15_171358_create_usuario_table', 1),
(15, '2024_09_15_171403_create_cliente_table', 1),
(16, '2024_09_15_171407_create_empleado_table', 1),
(17, '2024_09_15_171421_create_nomina_table', 1),
(18, '2024_09_15_171431_create_asistencia_table', 1),
(19, '2024_09_15_171442_create_reserva_table', 1),
(20, '2024_09_15_171443_create_reserva_mesa_table', 1),
(21, '2024_09_15_171448_create_pedido_table', 1),
(22, '2024_09_15_171459_create_producto_table', 1),
(23, '2024_09_15_171460_create_inventario_table', 1),
(24, '2024_09_15_171506_create_detalle_pedido_table', 1),
(25, '2024_09_15_171522_create_detalle_ingrediente_table', 1),
(26, '2024_09_16_191240_create_corte_caja_table', 1),
(27, '2024_09_16_223522_create_corte_empleado_table', 1),
(28, '2024_10_01_222820_add_two_factor_columns_to_users_table', 1),
(29, '2024_10_01_222906_create_personal_access_tokens_table', 1),
(30, '2024_10_25_163226_servicio', 1);

-- --------------------------------------------------------

--
-- Table structure for table `nomina`
--

CREATE TABLE `nomina` (
  `nomina_pk` bigint(20) UNSIGNED NOT NULL,
  `empleado_fk` bigint(20) UNSIGNED NOT NULL,
  `fecha_pago` date NOT NULL,
  `salario_base` decimal(8,2) NOT NULL,
  `horas_extra` decimal(8,2) NOT NULL DEFAULT 0.00,
  `deducciones` decimal(8,2) NOT NULL DEFAULT 0.00,
  `compensacion_extra` decimal(8,2) NOT NULL DEFAULT 0.00,
  `salario_neto` decimal(8,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `nomina`
--

INSERT INTO `nomina` (`nomina_pk`, `empleado_fk`, `fecha_pago`, `salario_base`, `horas_extra`, `deducciones`, `compensacion_extra`, `salario_neto`) VALUES
(1, 2, '2024-11-21', 2100.00, 1.00, -20.00, 0.00, 2170.00);

-- --------------------------------------------------------

--
-- Table structure for table `password_reset_tokens`
--

CREATE TABLE `password_reset_tokens` (
  `email` varchar(255) NOT NULL,
  `token` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `pedido`
--

CREATE TABLE `pedido` (
  `pedido_pk` bigint(20) UNSIGNED NOT NULL,
  `cliente_fk` bigint(20) UNSIGNED DEFAULT NULL,
  `empleado_fk` bigint(20) UNSIGNED NOT NULL,
  `fecha_hora_pedido` datetime NOT NULL,
  `medio_pedido_fk` bigint(20) UNSIGNED NOT NULL,
  `monto_total` decimal(8,2) NOT NULL,
  `numero_transaccion` varchar(50) DEFAULT NULL,
  `tipo_pago_fk` bigint(20) UNSIGNED NOT NULL,
  `notas_remision` text DEFAULT NULL,
  `pago` decimal(8,2) NOT NULL,
  `cambio` decimal(8,2) NOT NULL,
  `estatus_pedido` smallint(6) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `pedido`
--

INSERT INTO `pedido` (`pedido_pk`, `cliente_fk`, `empleado_fk`, `fecha_hora_pedido`, `medio_pedido_fk`, `monto_total`, `numero_transaccion`, `tipo_pago_fk`, `notas_remision`, `pago`, `cambio`, `estatus_pedido`) VALUES
(1, 1, 1, '2024-11-23 18:10:00', 4, 420.00, NULL, 3, NULL, 500.00, 80.00, 0),
(2, 1, 1, '2024-11-24 10:10:00', 4, 1000.00, NULL, 3, NULL, 1000.00, 0.00, 2),
(3, 1, 1, '2024-11-24 10:12:00', 4, 325.00, NULL, 3, NULL, 350.00, 25.00, 0),
(4, 1, 1, '2024-11-24 10:34:00', 4, 325.00, NULL, 3, NULL, 325.00, 0.00, 1),
(5, 1, 1, '2024-11-24 15:51:00', 4, 300.00, NULL, 3, NULL, 300.00, 0.00, 1),
(6, 1, 1, '2024-11-24 15:51:00', 4, 300.00, NULL, 3, NULL, 300.00, 0.00, 1),
(7, 1, 1, '2024-12-01 12:41:00', 4, 245.00, NULL, 3, NULL, 300.00, 55.00, 1),
(8, 1, 1, '2024-12-02 10:26:00', 4, 220.00, NULL, 3, NULL, 300.00, 80.00, 1),
(9, 1, 2, '2024-12-14 17:19:00', 4, 320.00, NULL, 3, NULL, 400.00, 80.00, 2),
(10, 1, 1, '2025-05-28 13:15:00', 1, 25.00, NULL, 1, 'Queso extra', 300.00, 275.00, 0),
(11, 1, 1, '2025-06-08 14:58:00', 3, 250.00, NULL, 3, NULL, 500.00, 250.00, 1),
(12, 1, 1, '2025-06-08 14:58:00', 3, 220.00, NULL, 2, NULL, 400.00, 180.00, 1),
(13, 1, 1, '2025-06-08 14:59:00', 3, 220.00, NULL, 3, NULL, 700.00, 480.00, 1),
(14, 1, 1, '2025-06-22 16:54:00', 1, 30.00, NULL, 1, NULL, 500.00, 470.00, 1),
(15, 1, 1, '2025-06-22 16:54:00', 3, 220.00, NULL, 1, NULL, 400.00, 180.00, 1),
(16, 1, 1, '2025-06-23 14:55:00', 1, 220.00, NULL, 3, NULL, 250.00, 30.00, 1),
(17, 1, 1, '2025-07-02 11:45:00', 1, 50.00, NULL, 1, NULL, 100.00, 50.00, 1),
(18, 2, 1, '2025-07-02 18:26:00', 2, 220.00, NULL, 1, NULL, 400.00, 180.00, 1),
(19, NULL, 1, '2025-07-02 18:33:00', 2, 55.00, NULL, 1, NULL, 100.00, 45.00, 1),
(20, NULL, 1, '2025-07-02 18:34:00', 4, 25.00, NULL, 3, NULL, 25.00, 0.00, 1),
(21, 2, 1, '2025-07-03 17:00:00', 2, 25.00, NULL, 1, NULL, 0.00, 0.00, 1),
(22, NULL, 1, '2025-07-04 11:50:00', 1, 25.00, NULL, 3, NULL, 30.00, 5.00, 1),
(23, NULL, 1, '2025-07-09 15:14:00', 1, 625.00, NULL, 3, NULL, 700.00, 75.00, 1),
(24, NULL, 1, '2025-07-09 15:15:00', 4, 595.00, NULL, 3, NULL, 600.00, 5.00, 1),
(25, 2, 1, '2025-07-09 15:16:00', 4, 25.00, NULL, 1, NULL, 30.00, 5.00, 1);

-- --------------------------------------------------------

--
-- Table structure for table `personal_access_tokens`
--

CREATE TABLE `personal_access_tokens` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `tokenable_type` varchar(255) NOT NULL,
  `tokenable_id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `token` varchar(64) NOT NULL,
  `abilities` text DEFAULT NULL,
  `last_used_at` timestamp NULL DEFAULT NULL,
  `expires_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `producto`
--

CREATE TABLE `producto` (
  `producto_pk` bigint(20) UNSIGNED NOT NULL,
  `nombre_producto` varchar(100) NOT NULL,
  `tipo_producto_fk` bigint(20) UNSIGNED NOT NULL,
  `precio_producto` decimal(8,2) NOT NULL,
  `estatus_producto` smallint(6) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `producto`
--

INSERT INTO `producto` (`producto_pk`, `nombre_producto`, `tipo_producto_fk`, `precio_producto`, `estatus_producto`) VALUES
(1, 'Picasso', 2, 220.00, 1),
(2, 'Refresco', 6, 25.00, 1),
(3, 'Agua fresca', 6, 30.00, 1),
(4, 'Monalezza', 3, 300.00, 1),
(5, 'Brownie', 9, 50.00, 1);

-- --------------------------------------------------------

--
-- Table structure for table `proveedor`
--

CREATE TABLE `proveedor` (
  `proveedor_pk` bigint(20) UNSIGNED NOT NULL,
  `nombre_proveedor` varchar(50) NOT NULL,
  `estatus_proveedor` smallint(6) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `proveedor`
--

INSERT INTO `proveedor` (`proveedor_pk`, `nombre_proveedor`, `estatus_proveedor`) VALUES
(1, 'Proveedor 1', 1);

-- --------------------------------------------------------

--
-- Table structure for table `reserva`
--

CREATE TABLE `reserva` (
  `reserva_pk` bigint(20) UNSIGNED NOT NULL,
  `cliente_fk` bigint(20) UNSIGNED NOT NULL,
  `fecha_hora_reserva` datetime NOT NULL,
  `notas` text DEFAULT NULL,
  `estatus_reserva` smallint(6) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `reserva`
--

INSERT INTO `reserva` (`reserva_pk`, `cliente_fk`, `fecha_hora_reserva`, `notas`, `estatus_reserva`) VALUES
(1, 1, '2024-12-10 16:15:00', NULL, 1);

-- --------------------------------------------------------

--
-- Table structure for table `reserva_mesa`
--

CREATE TABLE `reserva_mesa` (
  `reserva_mesa_pk` bigint(20) UNSIGNED NOT NULL,
  `mesa_fk` bigint(20) UNSIGNED NOT NULL,
  `reserva_fk` bigint(20) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `reserva_mesa`
--

INSERT INTO `reserva_mesa` (`reserva_mesa_pk`, `mesa_fk`, `reserva_fk`) VALUES
(1, 3, 1),
(2, 2, 1);

-- --------------------------------------------------------

--
-- Table structure for table `rol`
--

CREATE TABLE `rol` (
  `rol_pk` bigint(20) UNSIGNED NOT NULL,
  `nombre_rol` varchar(50) NOT NULL,
  `permisos` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `rol`
--

INSERT INTO `rol` (`rol_pk`, `nombre_rol`, `permisos`) VALUES
(1, 'Administrador', 'Agregar, modificar, y dar de baja cualquier dato del sistema, ademas de las mismas funciones que el empleado'),
(2, 'Empleado', 'Realizar ventas, cortes de caja, y revisar el inventario actual, y productos del negocio');

-- --------------------------------------------------------

--
-- Table structure for table `servicio`
--

CREATE TABLE `servicio` (
  `servicio_pk` bigint(20) UNSIGNED NOT NULL,
  `tipo_gasto_fk` bigint(20) UNSIGNED NOT NULL,
  `cantidad_pagada_servicio` decimal(8,2) NOT NULL,
  `fecha_pago_servicio` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `servicio`
--

INSERT INTO `servicio` (`servicio_pk`, `tipo_gasto_fk`, `cantidad_pagada_servicio`, `fecha_pago_servicio`) VALUES
(1, 14, 200.00, '2024-11-23'),
(2, 15, 750.00, '2024-11-23'),
(3, 3, 1800.00, '2024-11-23'),
(5, 15, 750.00, '2024-11-24'),
(6, 1, 300.00, '2025-07-02'),
(7, 15, 12000.00, '2025-07-09');

-- --------------------------------------------------------

--
-- Table structure for table `sessions`
--

CREATE TABLE `sessions` (
  `id` varchar(255) NOT NULL,
  `user_id` bigint(20) UNSIGNED DEFAULT NULL,
  `ip_address` varchar(45) DEFAULT NULL,
  `user_agent` text DEFAULT NULL,
  `payload` longtext NOT NULL,
  `last_activity` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `sessions`
--

INSERT INTO `sessions` (`id`, `user_id`, `ip_address`, `user_agent`, `payload`, `last_activity`) VALUES
('AvzExd8WaSy17VyuWi0gkDnKmt4RhcfWEGqa3Rfj', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/138.0.0.0 Safari/537.36 Edg/138.0.0.0', 'YTo3OntzOjY6Il90b2tlbiI7czo0MDoiY3JPREY4YzYxZGJjTDZGZVphOTFqODBLZmZhbUE3ZzU1ZWVHMXdUeCI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6MzA6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMC9yZXNlcnZhcyI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fXM6MTA6InVzdWFyaW9fcGsiO2k6MTtzOjc6InVzdWFyaW8iO3M6NzoiTcOpbmRleiI7czo2OiJyb2xfcGsiO2k6MTtzOjEwOiJub21icmVfcm9sIjtzOjEzOiJBZG1pbmlzdHJhZG9yIjt9', 1752424068),
('O420CyjAenBkU6bzYf3WYVgPB4HzFAAt1XymGOkd', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/138.0.0.0 Safari/537.36 Edg/138.0.0.0', 'YTo3OntzOjY6Il90b2tlbiI7czo0MDoiNG9JcGVuUVVqQk1ucVU4ZE1WeDRJcXpDTHhVRDNEV0JWMjJVWXVQTiI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6MzA6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMC9yZXNlcnZhcyI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fXM6MTA6InVzdWFyaW9fcGsiO2k6MTtzOjc6InVzdWFyaW8iO3M6NzoiTcOpbmRleiI7czo2OiJyb2xfcGsiO2k6MTtzOjEwOiJub21icmVfcm9sIjtzOjEzOiJBZG1pbmlzdHJhZG9yIjt9', 1752373629),
('wi5Bu5wD6afCGPBokUT7HMNRinJdE53FfLIIH2cX', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/138.0.0.0 Safari/537.36 Edg/138.0.0.0', 'YTo3OntzOjY6Il90b2tlbiI7czo0MDoiMmlvNVUxZXRUNnMzRExwY3I2akgySFZRYjJ6QmtTU3VMRzdEbXhieiI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6MjE6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMCI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fXM6MTA6InVzdWFyaW9fcGsiO2k6MTtzOjc6InVzdWFyaW8iO3M6NzoiTcOpbmRleiI7czo2OiJyb2xfcGsiO2k6MTtzOjEwOiJub21icmVfcm9sIjtzOjEzOiJBZG1pbmlzdHJhZG9yIjt9', 1752348226);

-- --------------------------------------------------------

--
-- Table structure for table `telefono`
--

CREATE TABLE `telefono` (
  `telefono_pk` bigint(20) UNSIGNED NOT NULL,
  `telefono` varchar(15) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `telefono`
--

INSERT INTO `telefono` (`telefono_pk`, `telefono`) VALUES
(1, '3259913034'),
(2, 'SN'),
(3, 'SN'),
(4, 'SN');

-- --------------------------------------------------------

--
-- Table structure for table `tipo_gasto`
--

CREATE TABLE `tipo_gasto` (
  `tipo_gasto_pk` bigint(20) UNSIGNED NOT NULL,
  `nombre_tipo_gasto` varchar(50) NOT NULL,
  `estatus_tipo_gasto` smallint(6) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `tipo_gasto`
--

INSERT INTO `tipo_gasto` (`tipo_gasto_pk`, `nombre_tipo_gasto`, `estatus_tipo_gasto`) VALUES
(1, 'Gas', 1),
(2, 'Agua', 1),
(3, 'Electricidad', 1),
(4, 'Línea de teléfono e internet', 1),
(5, 'Mantenimiento o reparaciones', 1),
(6, 'Decoraciones y adornos', 1),
(7, 'Productos de limpieza', 1),
(8, 'Impuestos', 1),
(9, 'Seguro del negocio', 1),
(10, 'Alquiler', 1),
(11, 'Publicidad y diseño', 1),
(12, 'Membresías', 1),
(13, 'Desechables', 1),
(14, 'Ingredientes', 1),
(15, 'Bebidas', 1);

-- --------------------------------------------------------

--
-- Table structure for table `tipo_pago`
--

CREATE TABLE `tipo_pago` (
  `tipo_pago_pk` bigint(20) UNSIGNED NOT NULL,
  `nombre_tipo_pago` varchar(50) NOT NULL,
  `estatus_tipo_pago` smallint(6) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `tipo_pago`
--

INSERT INTO `tipo_pago` (`tipo_pago_pk`, `nombre_tipo_pago`, `estatus_tipo_pago`) VALUES
(1, 'Transferencia', 1),
(2, 'Tarjeta de crédito', 1),
(3, 'Efectivo', 1);

-- --------------------------------------------------------

--
-- Table structure for table `tipo_producto`
--

CREATE TABLE `tipo_producto` (
  `tipo_producto_pk` bigint(20) UNSIGNED NOT NULL,
  `nombre_tipo_producto` varchar(50) NOT NULL,
  `estatus_tipo_producto` smallint(6) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `tipo_producto`
--

INSERT INTO `tipo_producto` (`tipo_producto_pk`, `nombre_tipo_producto`, `estatus_tipo_producto`) VALUES
(1, 'Pizza mediana', 1),
(2, 'Pizza familiar', 1),
(3, 'Pizza mega', 1),
(4, 'Pizza cuadrada', 1),
(5, 'Aderezo', 1),
(6, 'Bebida', 1),
(7, 'Extra', 1),
(8, 'Ingrediente extra', 1),
(9, 'Postre', 1);

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) NOT NULL,
  `two_factor_secret` text DEFAULT NULL,
  `two_factor_recovery_codes` text DEFAULT NULL,
  `two_factor_confirmed_at` timestamp NULL DEFAULT NULL,
  `remember_token` varchar(100) DEFAULT NULL,
  `current_team_id` bigint(20) UNSIGNED DEFAULT NULL,
  `profile_photo_path` varchar(2048) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `usuario`
--

CREATE TABLE `usuario` (
  `usuario_pk` bigint(20) UNSIGNED NOT NULL,
  `nombre` varchar(100) NOT NULL,
  `rol_fk` bigint(20) UNSIGNED NOT NULL,
  `usuario` varchar(50) NOT NULL,
  `contraseña` varchar(255) NOT NULL,
  `estatus_usuario` smallint(6) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `usuario`
--

INSERT INTO `usuario` (`usuario_pk`, `nombre`, `rol_fk`, `usuario`, `contraseña`, `estatus_usuario`) VALUES
(1, 'Jesús Méndez', 1, 'Méndez', '$2y$10$.XXSz0XIir.jI38PaNeg3OopoX9WLWG8.GAmoVKXjfJZcTSXYV5Bq', 1),
(2, 'Eduardo Manuel Pillado Osuna', 2, 'Pillado', '$2y$10$sj/ZsFmIlC4tw3kcf/LGu.9qFMY6G/sQI4l8m/lkJT3gOoq7AumA.', 1),
(3, 'Jesús Méndez', 1, 'Méndez', '$2y$10$o.o09O.fk5Bj6cYysJwLpO3q22P1jSfe3Qu5O/4anxA.MsWfLyX4y', 1);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `asistencia`
--
ALTER TABLE `asistencia`
  ADD PRIMARY KEY (`asistencia_pk`),
  ADD KEY `asistencia_empleado_fk_foreign` (`empleado_fk`);

--
-- Indexes for table `cache`
--
ALTER TABLE `cache`
  ADD PRIMARY KEY (`key`);

--
-- Indexes for table `cache_locks`
--
ALTER TABLE `cache_locks`
  ADD PRIMARY KEY (`key`);

--
-- Indexes for table `cliente`
--
ALTER TABLE `cliente`
  ADD PRIMARY KEY (`cliente_pk`),
  ADD KEY `cliente_domicilio_fk_foreign` (`domicilio_fk`),
  ADD KEY `cliente_telefono_fk_foreign` (`telefono_fk`);

--
-- Indexes for table `corte_caja`
--
ALTER TABLE `corte_caja`
  ADD PRIMARY KEY (`corte_caja_pk`);

--
-- Indexes for table `corte_empleado`
--
ALTER TABLE `corte_empleado`
  ADD PRIMARY KEY (`corte_empleado_pk`),
  ADD KEY `corte_empleado_corte_caja_fk_foreign` (`corte_caja_fk`),
  ADD KEY `corte_empleado_empleado_fk_foreign` (`empleado_fk`);

--
-- Indexes for table `detalle_efectivo`
--
ALTER TABLE `detalle_efectivo`
  ADD PRIMARY KEY (`detalle_efectivo_pk`);

--
-- Indexes for table `detalle_ingrediente`
--
ALTER TABLE `detalle_ingrediente`
  ADD PRIMARY KEY (`detalle_ingrediente_pk`),
  ADD KEY `detalle_ingrediente_producto_fk_foreign` (`producto_fk`),
  ADD KEY `detalle_ingrediente_ingrediente_fk_foreign` (`ingrediente_fk`);

--
-- Indexes for table `detalle_pedido`
--
ALTER TABLE `detalle_pedido`
  ADD PRIMARY KEY (`detalle_pedido_pk`),
  ADD KEY `detalle_pedido_pedido_fk_foreign` (`pedido_fk`),
  ADD KEY `detalle_pedido_producto_fk_foreign` (`producto_fk`);

--
-- Indexes for table `domicilio`
--
ALTER TABLE `domicilio`
  ADD PRIMARY KEY (`domicilio_pk`);

--
-- Indexes for table `empleado`
--
ALTER TABLE `empleado`
  ADD PRIMARY KEY (`empleado_pk`),
  ADD KEY `empleado_usuario_fk_foreign` (`usuario_fk`);

--
-- Indexes for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`);

--
-- Indexes for table `ingrediente`
--
ALTER TABLE `ingrediente`
  ADD PRIMARY KEY (`ingrediente_pk`);

--
-- Indexes for table `inventario`
--
ALTER TABLE `inventario`
  ADD PRIMARY KEY (`inventario_pk`),
  ADD KEY `inventario_ingrediente_fk_foreign` (`ingrediente_fk`),
  ADD KEY `inventario_producto_fk_foreign` (`producto_fk`),
  ADD KEY `inventario_tipo_gasto_fk_foreign` (`tipo_gasto_fk`),
  ADD KEY `inventario_proveedor_fk_foreign` (`proveedor_fk`);

--
-- Indexes for table `jobs`
--
ALTER TABLE `jobs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `jobs_queue_index` (`queue`);

--
-- Indexes for table `job_batches`
--
ALTER TABLE `job_batches`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `medio_pedido`
--
ALTER TABLE `medio_pedido`
  ADD PRIMARY KEY (`medio_pedido_pk`);

--
-- Indexes for table `mesa`
--
ALTER TABLE `mesa`
  ADD PRIMARY KEY (`mesa_pk`);

--
-- Indexes for table `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `nomina`
--
ALTER TABLE `nomina`
  ADD PRIMARY KEY (`nomina_pk`),
  ADD KEY `nomina_empleado_fk_foreign` (`empleado_fk`);

--
-- Indexes for table `password_reset_tokens`
--
ALTER TABLE `password_reset_tokens`
  ADD PRIMARY KEY (`email`);

--
-- Indexes for table `pedido`
--
ALTER TABLE `pedido`
  ADD PRIMARY KEY (`pedido_pk`),
  ADD KEY `pedido_cliente_fk_foreign` (`cliente_fk`),
  ADD KEY `pedido_empleado_fk_foreign` (`empleado_fk`),
  ADD KEY `pedido_medio_pedido_fk_foreign` (`medio_pedido_fk`),
  ADD KEY `pedido_tipo_pago_fk_foreign` (`tipo_pago_fk`);

--
-- Indexes for table `personal_access_tokens`
--
ALTER TABLE `personal_access_tokens`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `personal_access_tokens_token_unique` (`token`),
  ADD KEY `personal_access_tokens_tokenable_type_tokenable_id_index` (`tokenable_type`,`tokenable_id`);

--
-- Indexes for table `producto`
--
ALTER TABLE `producto`
  ADD PRIMARY KEY (`producto_pk`),
  ADD KEY `producto_tipo_producto_fk_foreign` (`tipo_producto_fk`);

--
-- Indexes for table `proveedor`
--
ALTER TABLE `proveedor`
  ADD PRIMARY KEY (`proveedor_pk`);

--
-- Indexes for table `reserva`
--
ALTER TABLE `reserva`
  ADD PRIMARY KEY (`reserva_pk`),
  ADD KEY `reserva_cliente_fk_foreign` (`cliente_fk`);

--
-- Indexes for table `reserva_mesa`
--
ALTER TABLE `reserva_mesa`
  ADD PRIMARY KEY (`reserva_mesa_pk`),
  ADD KEY `reserva_mesa_mesa_fk_foreign` (`mesa_fk`),
  ADD KEY `reserva_mesa_reserva_fk_foreign` (`reserva_fk`);

--
-- Indexes for table `rol`
--
ALTER TABLE `rol`
  ADD PRIMARY KEY (`rol_pk`);

--
-- Indexes for table `servicio`
--
ALTER TABLE `servicio`
  ADD PRIMARY KEY (`servicio_pk`),
  ADD KEY `servicio_tipo_gasto_fk_foreign` (`tipo_gasto_fk`);

--
-- Indexes for table `sessions`
--
ALTER TABLE `sessions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `sessions_user_id_index` (`user_id`),
  ADD KEY `sessions_last_activity_index` (`last_activity`);

--
-- Indexes for table `telefono`
--
ALTER TABLE `telefono`
  ADD PRIMARY KEY (`telefono_pk`);

--
-- Indexes for table `tipo_gasto`
--
ALTER TABLE `tipo_gasto`
  ADD PRIMARY KEY (`tipo_gasto_pk`);

--
-- Indexes for table `tipo_pago`
--
ALTER TABLE `tipo_pago`
  ADD PRIMARY KEY (`tipo_pago_pk`);

--
-- Indexes for table `tipo_producto`
--
ALTER TABLE `tipo_producto`
  ADD PRIMARY KEY (`tipo_producto_pk`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `users_email_unique` (`email`);

--
-- Indexes for table `usuario`
--
ALTER TABLE `usuario`
  ADD PRIMARY KEY (`usuario_pk`),
  ADD KEY `usuario_rol_fk_foreign` (`rol_fk`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `asistencia`
--
ALTER TABLE `asistencia`
  MODIFY `asistencia_pk` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `cliente`
--
ALTER TABLE `cliente`
  MODIFY `cliente_pk` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `corte_caja`
--
ALTER TABLE `corte_caja`
  MODIFY `corte_caja_pk` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `corte_empleado`
--
ALTER TABLE `corte_empleado`
  MODIFY `corte_empleado_pk` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `detalle_efectivo`
--
ALTER TABLE `detalle_efectivo`
  MODIFY `detalle_efectivo_pk` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

--
-- AUTO_INCREMENT for table `detalle_ingrediente`
--
ALTER TABLE `detalle_ingrediente`
  MODIFY `detalle_ingrediente_pk` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `detalle_pedido`
--
ALTER TABLE `detalle_pedido`
  MODIFY `detalle_pedido_pk` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=34;

--
-- AUTO_INCREMENT for table `domicilio`
--
ALTER TABLE `domicilio`
  MODIFY `domicilio_pk` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `empleado`
--
ALTER TABLE `empleado`
  MODIFY `empleado_pk` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `ingrediente`
--
ALTER TABLE `ingrediente`
  MODIFY `ingrediente_pk` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `inventario`
--
ALTER TABLE `inventario`
  MODIFY `inventario_pk` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `jobs`
--
ALTER TABLE `jobs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `medio_pedido`
--
ALTER TABLE `medio_pedido`
  MODIFY `medio_pedido_pk` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `mesa`
--
ALTER TABLE `mesa`
  MODIFY `mesa_pk` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=31;

--
-- AUTO_INCREMENT for table `nomina`
--
ALTER TABLE `nomina`
  MODIFY `nomina_pk` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `pedido`
--
ALTER TABLE `pedido`
  MODIFY `pedido_pk` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=26;

--
-- AUTO_INCREMENT for table `personal_access_tokens`
--
ALTER TABLE `personal_access_tokens`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `producto`
--
ALTER TABLE `producto`
  MODIFY `producto_pk` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `proveedor`
--
ALTER TABLE `proveedor`
  MODIFY `proveedor_pk` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `reserva`
--
ALTER TABLE `reserva`
  MODIFY `reserva_pk` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `reserva_mesa`
--
ALTER TABLE `reserva_mesa`
  MODIFY `reserva_mesa_pk` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `rol`
--
ALTER TABLE `rol`
  MODIFY `rol_pk` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `servicio`
--
ALTER TABLE `servicio`
  MODIFY `servicio_pk` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `telefono`
--
ALTER TABLE `telefono`
  MODIFY `telefono_pk` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `tipo_gasto`
--
ALTER TABLE `tipo_gasto`
  MODIFY `tipo_gasto_pk` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT for table `tipo_pago`
--
ALTER TABLE `tipo_pago`
  MODIFY `tipo_pago_pk` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `tipo_producto`
--
ALTER TABLE `tipo_producto`
  MODIFY `tipo_producto_pk` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `usuario`
--
ALTER TABLE `usuario`
  MODIFY `usuario_pk` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `asistencia`
--
ALTER TABLE `asistencia`
  ADD CONSTRAINT `asistencia_empleado_fk_foreign` FOREIGN KEY (`empleado_fk`) REFERENCES `empleado` (`empleado_pk`);

--
-- Constraints for table `cliente`
--
ALTER TABLE `cliente`
  ADD CONSTRAINT `cliente_domicilio_fk_foreign` FOREIGN KEY (`domicilio_fk`) REFERENCES `domicilio` (`domicilio_pk`),
  ADD CONSTRAINT `cliente_telefono_fk_foreign` FOREIGN KEY (`telefono_fk`) REFERENCES `telefono` (`telefono_pk`);

--
-- Constraints for table `corte_empleado`
--
ALTER TABLE `corte_empleado`
  ADD CONSTRAINT `corte_empleado_corte_caja_fk_foreign` FOREIGN KEY (`corte_caja_fk`) REFERENCES `corte_caja` (`corte_caja_pk`),
  ADD CONSTRAINT `corte_empleado_empleado_fk_foreign` FOREIGN KEY (`empleado_fk`) REFERENCES `empleado` (`empleado_pk`);

--
-- Constraints for table `detalle_ingrediente`
--
ALTER TABLE `detalle_ingrediente`
  ADD CONSTRAINT `detalle_ingrediente_ingrediente_fk_foreign` FOREIGN KEY (`ingrediente_fk`) REFERENCES `ingrediente` (`ingrediente_pk`),
  ADD CONSTRAINT `detalle_ingrediente_producto_fk_foreign` FOREIGN KEY (`producto_fk`) REFERENCES `producto` (`producto_pk`);

--
-- Constraints for table `detalle_pedido`
--
ALTER TABLE `detalle_pedido`
  ADD CONSTRAINT `detalle_pedido_pedido_fk_foreign` FOREIGN KEY (`pedido_fk`) REFERENCES `pedido` (`pedido_pk`),
  ADD CONSTRAINT `detalle_pedido_producto_fk_foreign` FOREIGN KEY (`producto_fk`) REFERENCES `producto` (`producto_pk`);

--
-- Constraints for table `empleado`
--
ALTER TABLE `empleado`
  ADD CONSTRAINT `empleado_usuario_fk_foreign` FOREIGN KEY (`usuario_fk`) REFERENCES `usuario` (`usuario_pk`);

--
-- Constraints for table `inventario`
--
ALTER TABLE `inventario`
  ADD CONSTRAINT `inventario_ingrediente_fk_foreign` FOREIGN KEY (`ingrediente_fk`) REFERENCES `ingrediente` (`ingrediente_pk`),
  ADD CONSTRAINT `inventario_producto_fk_foreign` FOREIGN KEY (`producto_fk`) REFERENCES `producto` (`producto_pk`),
  ADD CONSTRAINT `inventario_proveedor_fk_foreign` FOREIGN KEY (`proveedor_fk`) REFERENCES `proveedor` (`proveedor_pk`),
  ADD CONSTRAINT `inventario_tipo_gasto_fk_foreign` FOREIGN KEY (`tipo_gasto_fk`) REFERENCES `tipo_gasto` (`tipo_gasto_pk`);

--
-- Constraints for table `nomina`
--
ALTER TABLE `nomina`
  ADD CONSTRAINT `nomina_empleado_fk_foreign` FOREIGN KEY (`empleado_fk`) REFERENCES `empleado` (`empleado_pk`);

--
-- Constraints for table `pedido`
--
ALTER TABLE `pedido`
  ADD CONSTRAINT `pedido_cliente_fk_foreign` FOREIGN KEY (`cliente_fk`) REFERENCES `cliente` (`cliente_pk`),
  ADD CONSTRAINT `pedido_empleado_fk_foreign` FOREIGN KEY (`empleado_fk`) REFERENCES `empleado` (`empleado_pk`),
  ADD CONSTRAINT `pedido_medio_pedido_fk_foreign` FOREIGN KEY (`medio_pedido_fk`) REFERENCES `medio_pedido` (`medio_pedido_pk`),
  ADD CONSTRAINT `pedido_tipo_pago_fk_foreign` FOREIGN KEY (`tipo_pago_fk`) REFERENCES `tipo_pago` (`tipo_pago_pk`);

--
-- Constraints for table `producto`
--
ALTER TABLE `producto`
  ADD CONSTRAINT `producto_tipo_producto_fk_foreign` FOREIGN KEY (`tipo_producto_fk`) REFERENCES `tipo_producto` (`tipo_producto_pk`);

--
-- Constraints for table `reserva`
--
ALTER TABLE `reserva`
  ADD CONSTRAINT `reserva_cliente_fk_foreign` FOREIGN KEY (`cliente_fk`) REFERENCES `cliente` (`cliente_pk`);

--
-- Constraints for table `reserva_mesa`
--
ALTER TABLE `reserva_mesa`
  ADD CONSTRAINT `reserva_mesa_mesa_fk_foreign` FOREIGN KEY (`mesa_fk`) REFERENCES `mesa` (`mesa_pk`),
  ADD CONSTRAINT `reserva_mesa_reserva_fk_foreign` FOREIGN KEY (`reserva_fk`) REFERENCES `reserva` (`reserva_pk`);

--
-- Constraints for table `servicio`
--
ALTER TABLE `servicio`
  ADD CONSTRAINT `servicio_tipo_gasto_fk_foreign` FOREIGN KEY (`tipo_gasto_fk`) REFERENCES `tipo_gasto` (`tipo_gasto_pk`);

--
-- Constraints for table `usuario`
--
ALTER TABLE `usuario`
  ADD CONSTRAINT `usuario_rol_fk_foreign` FOREIGN KEY (`rol_fk`) REFERENCES `rol` (`rol_pk`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
