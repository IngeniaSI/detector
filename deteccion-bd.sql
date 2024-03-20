-- --------------------------------------------------------
-- Host:                         127.0.0.1
-- Versión del servidor:         8.0.30 - MySQL Community Server - GPL
-- SO del servidor:              Win64
-- HeidiSQL Versión:             12.1.0.6537
-- --------------------------------------------------------

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET NAMES utf8 */;
/*!50503 SET NAMES utf8mb4 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;


-- Volcando estructura de base de datos para detector
CREATE DATABASE IF NOT EXISTS `detector` /*!40100 DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci */ /*!80016 DEFAULT ENCRYPTION='N' */;
USE `detector`;

-- Volcando estructura para tabla detector.bitacoras
CREATE TABLE IF NOT EXISTS `bitacoras` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `accion` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `url` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `ip` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `tipo` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `user_id` bigint unsigned DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `bitacoras_user_id_foreign` (`user_id`),
  CONSTRAINT `bitacoras_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Volcando datos para la tabla detector.bitacoras: ~0 rows (aproximadamente)

-- Volcando estructura para tabla detector.campanias
CREATE TABLE IF NOT EXISTS `campanias` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `objetivo` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `descripcion` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `nivel` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `fecha_inicio` datetime NOT NULL,
  `fecha_fin` datetime NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Volcando datos para la tabla detector.campanias: ~0 rows (aproximadamente)

-- Volcando estructura para tabla detector.campania_seccions
CREATE TABLE IF NOT EXISTS `campania_seccions` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `campania_id` bigint unsigned NOT NULL,
  `seccion_id` bigint unsigned NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `campania_seccions_campania_id_foreign` (`campania_id`),
  KEY `campania_seccions_seccion_id_foreign` (`seccion_id`),
  CONSTRAINT `campania_seccions_campania_id_foreign` FOREIGN KEY (`campania_id`) REFERENCES `campanias` (`id`),
  CONSTRAINT `campania_seccions_seccion_id_foreign` FOREIGN KEY (`seccion_id`) REFERENCES `seccions` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Volcando datos para la tabla detector.campania_seccions: ~0 rows (aproximadamente)

-- Volcando estructura para tabla detector.colonias
CREATE TABLE IF NOT EXISTS `colonias` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `nombre` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `tipo` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `codigo_postal` int NOT NULL,
  `control` int NOT NULL,
  `seccion_id` bigint unsigned NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `colonias_seccion_id_foreign` (`seccion_id`),
  CONSTRAINT `colonias_seccion_id_foreign` FOREIGN KEY (`seccion_id`) REFERENCES `seccions` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Volcando datos para la tabla detector.colonias: ~0 rows (aproximadamente)

-- Volcando estructura para tabla detector.distrito_federals
CREATE TABLE IF NOT EXISTS `distrito_federals` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `entidad_id` bigint unsigned NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `distrito_federals_entidad_id_foreign` (`entidad_id`),
  CONSTRAINT `distrito_federals_entidad_id_foreign` FOREIGN KEY (`entidad_id`) REFERENCES `entidads` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Volcando datos para la tabla detector.distrito_federals: ~0 rows (aproximadamente)

-- Volcando estructura para tabla detector.distrito_locals
CREATE TABLE IF NOT EXISTS `distrito_locals` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `distrito_federal_id` bigint unsigned NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `distrito_locals_distrito_federal_id_foreign` (`distrito_federal_id`),
  CONSTRAINT `distrito_locals_distrito_federal_id_foreign` FOREIGN KEY (`distrito_federal_id`) REFERENCES `distrito_federals` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Volcando datos para la tabla detector.distrito_locals: ~0 rows (aproximadamente)

-- Volcando estructura para tabla detector.domicilios
CREATE TABLE IF NOT EXISTS `domicilios` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `calle` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `numero_exterior` int NOT NULL,
  `numero_interior` int DEFAULT NULL,
  `latitud` double(8,2) DEFAULT NULL,
  `longitud` double(8,2) DEFAULT NULL,
  `colonia_id` bigint unsigned NOT NULL,
  `identificacion_id` bigint unsigned NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `domicilios_colonia_id_foreign` (`colonia_id`),
  KEY `domicilios_identificacion_id_foreign` (`identificacion_id`),
  CONSTRAINT `domicilios_colonia_id_foreign` FOREIGN KEY (`colonia_id`) REFERENCES `colonias` (`id`),
  CONSTRAINT `domicilios_identificacion_id_foreign` FOREIGN KEY (`identificacion_id`) REFERENCES `identificacions` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Volcando datos para la tabla detector.domicilios: ~0 rows (aproximadamente)

-- Volcando estructura para tabla detector.encuestas
CREATE TABLE IF NOT EXISTS `encuestas` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `folio` bigint NOT NULL,
  `pregunta` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `respuesta` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `tipo` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `nombre_encuestado` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `campania_id` bigint unsigned NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `encuestas_campania_id_foreign` (`campania_id`),
  CONSTRAINT `encuestas_campania_id_foreign` FOREIGN KEY (`campania_id`) REFERENCES `campanias` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Volcando datos para la tabla detector.encuestas: ~0 rows (aproximadamente)

-- Volcando estructura para tabla detector.entidads
CREATE TABLE IF NOT EXISTS `entidads` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `nombre` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Volcando datos para la tabla detector.entidads: ~0 rows (aproximadamente)

-- Volcando estructura para tabla detector.failed_jobs
CREATE TABLE IF NOT EXISTS `failed_jobs` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `uuid` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `connection` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `queue` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `exception` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Volcando datos para la tabla detector.failed_jobs: ~0 rows (aproximadamente)

-- Volcando estructura para tabla detector.identificacions
CREATE TABLE IF NOT EXISTS `identificacions` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `clave_elector` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `curp` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `persona_id` bigint unsigned NOT NULL,
  `seccion_id` bigint unsigned NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `identificacions_persona_id_foreign` (`persona_id`),
  KEY `identificacions_seccion_id_foreign` (`seccion_id`),
  CONSTRAINT `identificacions_persona_id_foreign` FOREIGN KEY (`persona_id`) REFERENCES `personas` (`id`),
  CONSTRAINT `identificacions_seccion_id_foreign` FOREIGN KEY (`seccion_id`) REFERENCES `seccions` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Volcando datos para la tabla detector.identificacions: ~0 rows (aproximadamente)

-- Volcando estructura para tabla detector.localidads
CREATE TABLE IF NOT EXISTS `localidads` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `nombre` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `tipo` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `seccion_id` bigint unsigned NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Volcando datos para la tabla detector.localidads: ~0 rows (aproximadamente)

-- Volcando estructura para tabla detector.manzanas
CREATE TABLE IF NOT EXISTS `manzanas` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `folio` int NOT NULL,
  `status` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `localidad_id` bigint unsigned NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `manzanas_localidad_id_foreign` (`localidad_id`),
  CONSTRAINT `manzanas_localidad_id_foreign` FOREIGN KEY (`localidad_id`) REFERENCES `localidads` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Volcando datos para la tabla detector.manzanas: ~0 rows (aproximadamente)

-- Volcando estructura para tabla detector.migrations
CREATE TABLE IF NOT EXISTS `migrations` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `migration` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `batch` int NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=22 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Volcando datos para la tabla detector.migrations: ~0 rows (aproximadamente)
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES
	(1, '2014_10_12_000000_create_users_table', 1),
	(2, '2014_10_12_100000_create_password_reset_tokens_table', 1),
	(3, '2019_08_19_000000_create_failed_jobs_table', 1),
	(4, '2019_12_14_000001_create_personal_access_tokens_table', 1),
	(5, '2024_03_11_022455_create_sessions_table', 1),
	(6, '2024_03_12_050538_create_bitacoras_table', 1),
	(7, '2024_03_12_062432_create_permission_tables', 1),
	(8, '2024_03_14_012642_create_campanias_table', 1),
	(9, '2024_03_14_012652_create_encuestas_table', 1),
	(10, '2024_03_14_012727_create_entidads_table', 1),
	(11, '2024_03_14_012734_create_distrito_federals_table', 1),
	(12, '2024_03_14_012743_create_distrito_locals_table', 1),
	(13, '2024_03_14_012753_create_municipios_table', 1),
	(14, '2024_03_14_012806_create_seccions_table', 1),
	(15, '2024_03_14_012807_create_campania_seccions_table', 1),
	(16, '2024_03_14_012813_create_localidads_table', 1),
	(17, '2024_03_14_012819_create_manzanas_table', 1),
	(18, '2024_03_14_012833_create_colonias_table', 1),
	(19, '2024_03_14_012946_create_personas_table', 1),
	(20, '2024_03_14_012949_create_identificacions_table', 1),
	(21, '2024_03_14_012957_create_domicilios_table', 1);

-- Volcando estructura para tabla detector.model_has_permissions
CREATE TABLE IF NOT EXISTS `model_has_permissions` (
  `permission_id` bigint unsigned NOT NULL,
  `model_type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `model_id` bigint unsigned NOT NULL,
  PRIMARY KEY (`permission_id`,`model_id`,`model_type`),
  KEY `model_has_permissions_model_id_model_type_index` (`model_id`,`model_type`),
  CONSTRAINT `model_has_permissions_permission_id_foreign` FOREIGN KEY (`permission_id`) REFERENCES `permissions` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Volcando datos para la tabla detector.model_has_permissions: ~0 rows (aproximadamente)

-- Volcando estructura para tabla detector.model_has_roles
CREATE TABLE IF NOT EXISTS `model_has_roles` (
  `role_id` bigint unsigned NOT NULL,
  `model_type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `model_id` bigint unsigned NOT NULL,
  PRIMARY KEY (`role_id`,`model_id`,`model_type`),
  KEY `model_has_roles_model_id_model_type_index` (`model_id`,`model_type`),
  CONSTRAINT `model_has_roles_role_id_foreign` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Volcando datos para la tabla detector.model_has_roles: ~1 rows (aproximadamente)
INSERT INTO `model_has_roles` (`role_id`, `model_type`, `model_id`) VALUES
	(1, 'App\\Models\\User', 1);

-- Volcando estructura para tabla detector.municipios
CREATE TABLE IF NOT EXISTS `municipios` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `nombre` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `distrito_local_id` bigint unsigned NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `municipios_distrito_local_id_foreign` (`distrito_local_id`),
  CONSTRAINT `municipios_distrito_local_id_foreign` FOREIGN KEY (`distrito_local_id`) REFERENCES `distrito_locals` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Volcando datos para la tabla detector.municipios: ~0 rows (aproximadamente)

-- Volcando estructura para tabla detector.password_reset_tokens
CREATE TABLE IF NOT EXISTS `password_reset_tokens` (
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Volcando datos para la tabla detector.password_reset_tokens: ~0 rows (aproximadamente)

-- Volcando estructura para tabla detector.permissions
CREATE TABLE IF NOT EXISTS `permissions` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `guard_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `permissions_name_guard_name_unique` (`name`,`guard_name`)
) ENGINE=InnoDB AUTO_INCREMENT=17 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Volcando datos para la tabla detector.permissions: ~0 rows (aproximadamente)
INSERT INTO `permissions` (`id`, `name`, `guard_name`, `created_at`, `updated_at`) VALUES
	(1, 'crudUsuarios.index', 'web', '2024-03-16 08:23:49', '2024-03-16 08:23:49'),
	(2, 'crudUsuarios.create', 'web', '2024-03-16 08:23:49', '2024-03-16 08:23:49'),
	(3, 'crudUsuarios.edit', 'web', '2024-03-16 08:23:49', '2024-03-16 08:23:49'),
	(4, 'crudUsuarios.delete', 'web', '2024-03-16 08:23:49', '2024-03-16 08:23:49'),
	(5, 'controlUsuarios.index', 'web', '2024-03-16 08:23:49', '2024-03-16 08:23:49'),
	(6, 'controlUsuarios.create', 'web', '2024-03-16 08:23:49', '2024-03-16 08:23:49'),
	(7, 'controlUsuarios.edit', 'web', '2024-03-16 08:23:49', '2024-03-16 08:23:49'),
	(8, 'controlUsuarios.delete', 'web', '2024-03-16 08:23:49', '2024-03-16 08:23:49'),
	(9, 'tablero.index', 'web', '2024-03-16 08:23:49', '2024-03-16 08:23:49'),
	(10, 'tablero.create', 'web', '2024-03-16 08:23:49', '2024-03-16 08:23:49'),
	(11, 'tablero.edit', 'web', '2024-03-16 08:23:49', '2024-03-16 08:23:49'),
	(12, 'tablero.delete', 'web', '2024-03-16 08:23:49', '2024-03-16 08:23:49'),
	(13, 'capturarProspecto.index', 'web', '2024-03-16 08:23:49', '2024-03-16 08:23:49'),
	(14, 'capturarProspecto.create', 'web', '2024-03-16 08:23:49', '2024-03-16 08:23:49'),
	(15, 'capturarProspecto.edit', 'web', '2024-03-16 08:23:49', '2024-03-16 08:23:49'),
	(16, 'capturarProspecto.delete', 'web', '2024-03-16 08:23:50', '2024-03-16 08:23:50');

-- Volcando estructura para tabla detector.personal_access_tokens
CREATE TABLE IF NOT EXISTS `personal_access_tokens` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `tokenable_type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `tokenable_id` bigint unsigned NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(64) COLLATE utf8mb4_unicode_ci NOT NULL,
  `abilities` text COLLATE utf8mb4_unicode_ci,
  `last_used_at` timestamp NULL DEFAULT NULL,
  `expires_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `personal_access_tokens_token_unique` (`token`),
  KEY `personal_access_tokens_tokenable_type_tokenable_id_index` (`tokenable_type`,`tokenable_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Volcando datos para la tabla detector.personal_access_tokens: ~0 rows (aproximadamente)

-- Volcando estructura para tabla detector.personas
CREATE TABLE IF NOT EXISTS `personas` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `nombres` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `apellido_paterno` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `genero` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `telefono_celular` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `telefono_fijo` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `correo` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `nombre_en_facebook` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `escolaridad` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `fecha_nacimiento` datetime DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Volcando datos para la tabla detector.personas: ~0 rows (aproximadamente)

-- Volcando estructura para tabla detector.roles
CREATE TABLE IF NOT EXISTS `roles` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `guard_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `roles_name_guard_name_unique` (`name`,`guard_name`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Volcando datos para la tabla detector.roles: ~2 rows (aproximadamente)
INSERT INTO `roles` (`id`, `name`, `guard_name`, `created_at`, `updated_at`) VALUES
	(1, 'SUPER_ADMINISTRADOR', 'web', '2024-03-16 08:23:49', '2024-03-16 08:23:49'),
	(2, 'ADMINISTRADOR', 'web', '2024-03-16 08:23:49', '2024-03-16 08:23:49'),
	(3, 'SUPERVISOR', 'web', '2024-03-16 08:23:49', '2024-03-16 08:23:49'),
	(4, 'CAPTURISTA', 'web', '2024-03-16 08:23:49', '2024-03-16 08:23:49');

-- Volcando estructura para tabla detector.role_has_permissions
CREATE TABLE IF NOT EXISTS `role_has_permissions` (
  `permission_id` bigint unsigned NOT NULL,
  `role_id` bigint unsigned NOT NULL,
  PRIMARY KEY (`permission_id`,`role_id`),
  KEY `role_has_permissions_role_id_foreign` (`role_id`),
  CONSTRAINT `role_has_permissions_permission_id_foreign` FOREIGN KEY (`permission_id`) REFERENCES `permissions` (`id`) ON DELETE CASCADE,
  CONSTRAINT `role_has_permissions_role_id_foreign` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Volcando datos para la tabla detector.role_has_permissions: ~2 rows (aproximadamente)
INSERT INTO `role_has_permissions` (`permission_id`, `role_id`) VALUES
	(1, 1),
	(2, 1),
	(3, 1),
	(4, 1),
	(5, 1),
	(6, 1),
	(7, 1),
	(8, 1),
	(9, 1),
	(10, 1),
	(11, 1),
	(12, 1),
	(13, 1),
	(14, 1),
	(15, 1),
	(16, 1),
	(1, 2),
	(2, 2),
	(3, 2),
	(4, 2),
	(5, 2),
	(6, 2),
	(7, 2),
	(8, 2),
	(9, 2),
	(10, 2),
	(11, 2),
	(12, 2),
	(13, 2),
	(14, 2),
	(15, 2),
	(16, 2),
	(13, 3),
	(14, 3),
	(15, 3),
	(16, 3),
	(13, 4),
	(14, 4),
	(15, 4);

-- Volcando estructura para tabla detector.seccions
CREATE TABLE IF NOT EXISTS `seccions` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `tipo` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `municipio_id` bigint unsigned NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `seccions_municipio_id_foreign` (`municipio_id`),
  CONSTRAINT `seccions_municipio_id_foreign` FOREIGN KEY (`municipio_id`) REFERENCES `municipios` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Volcando datos para la tabla detector.seccions: ~0 rows (aproximadamente)

-- Volcando estructura para tabla detector.sessions
CREATE TABLE IF NOT EXISTS `sessions` (
  `id` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `user_id` bigint unsigned DEFAULT NULL,
  `ip_address` varchar(45) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `user_agent` text COLLATE utf8mb4_unicode_ci,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `last_activity` int NOT NULL,
  PRIMARY KEY (`id`),
  KEY `sessions_user_id_index` (`user_id`),
  KEY `sessions_last_activity_index` (`last_activity`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Volcando datos para la tabla detector.sessions: ~0 rows (aproximadamente)

-- Volcando estructura para tabla detector.users
CREATE TABLE IF NOT EXISTS `users` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `nombre` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `apellido_paterno` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `apellido_materno` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `remember_token` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `users_email_unique` (`email`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Volcando datos para la tabla detector.users: ~1 rows (aproximadamente)
INSERT INTO `users` (`id`, `nombre`, `apellido_paterno`, `apellido_materno`, `email`, `email_verified_at`, `password`, `remember_token`, `created_at`, `updated_at`, `deleted_at`) VALUES
	(1, 'EMILIO', 'MENDOZA', 'SARMIENTO', 'emiliomendoza20@hotmail.com', '2024-03-16 08:23:50', '$2y$12$uxVU.DAfvhZGfoyXauibiOsbP9V3PIiGhio3y4RZ5GwwE9mQzns9K', NULL, '2024-03-16 08:23:50', '2024-03-16 08:23:50', NULL);

/*!40103 SET TIME_ZONE=IFNULL(@OLD_TIME_ZONE, 'system') */;
/*!40101 SET SQL_MODE=IFNULL(@OLD_SQL_MODE, '') */;
/*!40014 SET FOREIGN_KEY_CHECKS=IFNULL(@OLD_FOREIGN_KEY_CHECKS, 1) */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40111 SET SQL_NOTES=IFNULL(@OLD_SQL_NOTES, 1) */;
