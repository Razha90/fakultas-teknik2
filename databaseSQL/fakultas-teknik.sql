-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: May 26, 2025 at 09:22 AM
-- Server version: 8.0.30
-- PHP Version: 8.3.13

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `fakultas-teknik`
--

-- --------------------------------------------------------

--
-- Table structure for table `banners`
--

CREATE TABLE `banners` (
  `id` char(36) COLLATE utf8mb4_unicode_ci NOT NULL,
  `image` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `videos` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `cache`
--

CREATE TABLE `cache` (
  `key` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `value` mediumtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `expiration` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `cache`
--

INSERT INTO `cache` (`key`, `value`, `expiration`) VALUES
('laravel_cache_15dea2e1a6bf2177526ae3ed1a86670156534123', 'i:1;', 1748248636),
('laravel_cache_15dea2e1a6bf2177526ae3ed1a86670156534123:timer', 'i:1748248636;', 1748248636),
('laravel_cache_5c785c036466adea360111aa28563bfd556b5fba', 'i:1;', 1745837244),
('laravel_cache_5c785c036466adea360111aa28563bfd556b5fba:timer', 'i:1745837244;', 1745837244),
('laravel_cache_5f6c39c81460b55aadeff8fa4075274b9916f527', 'i:1;', 1745846955),
('laravel_cache_5f6c39c81460b55aadeff8fa4075274b9916f527:timer', 'i:1745846955;', 1745846955),
('laravel_cache_6c58e72b7eec4d41d18e1793d8a473d2bd92c606', 'i:1;', 1748234089),
('laravel_cache_6c58e72b7eec4d41d18e1793d8a473d2bd92c606:timer', 'i:1748234089;', 1748234089);

-- --------------------------------------------------------

--
-- Table structure for table `cache_locks`
--

CREATE TABLE `cache_locks` (
  `key` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `owner` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `expiration` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `categories`
--

CREATE TABLE `categories` (
  `id` char(36) COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `categories`
--

INSERT INTO `categories` (`id`, `name`, `created_at`, `updated_at`) VALUES
('01967bba-d923-7334-a3a3-d62c586be22c', 'Teknologia', '2025-04-28 02:29:25', '2025-05-08 07:55:17'),
('01969587-4f21-713d-aa64-8b766b26a923', 'b', '2025-05-03 02:43:14', '2025-05-08 07:48:52'),
('0196a5e3-3ef2-735f-a8c5-f1637cdb2965', 'Pendidikan', '2025-05-06 06:57:35', '2025-05-06 06:57:35'),
('0196bad8-32e6-7286-970c-5102a21617f6', 'FT News', '2025-05-10 08:37:33', '2025-05-10 08:37:33'),
('0196bad8-5991-7059-a15f-79a50914f090', 'MBKM News', '2025-05-10 08:37:43', '2025-05-10 08:37:43'),
('0196bad9-1cc8-71c1-8671-8b7306cb5bad', 'FT Event', '2025-05-10 08:38:33', '2025-05-10 08:38:33'),
('0196bad9-5025-7149-be2c-fb1ba97486b5', 'Pengumuman FT', '2025-05-10 08:38:46', '2025-05-10 08:38:46'),
('0196f72c-803e-7311-ad2c-b011486b2409', 'News', '2025-05-22 01:46:50', '2025-05-22 01:46:50');

-- --------------------------------------------------------

--
-- Table structure for table `category_content`
--

CREATE TABLE `category_content` (
  `content_id` char(36) COLLATE utf8mb4_unicode_ci NOT NULL,
  `category_id` char(36) COLLATE utf8mb4_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `category_content`
--

INSERT INTO `category_content` (`content_id`, `category_id`) VALUES
('42eabce0-c403-4c96-a6fd-aea3ee66274f', '0196a5e3-3ef2-735f-a8c5-f1637cdb2965'),
('60c4f0f6-989f-4db3-bea8-3328d17aabbb', '0196a5e3-3ef2-735f-a8c5-f1637cdb2965'),
('78d49315-357a-4d6e-9113-53174f7efdbe', '0196a5e3-3ef2-735f-a8c5-f1637cdb2965'),
('8888d328-222d-459c-969d-8fc51d404754', '0196a5e3-3ef2-735f-a8c5-f1637cdb2965'),
('d998576f-043b-47f9-8cde-6b64bd3004e4', '0196a5e3-3ef2-735f-a8c5-f1637cdb2965'),
('42eabce0-c403-4c96-a6fd-aea3ee66274f', '0196bad8-32e6-7286-970c-5102a21617f6'),
('60c4f0f6-989f-4db3-bea8-3328d17aabbb', '0196bad8-32e6-7286-970c-5102a21617f6'),
('78d49315-357a-4d6e-9113-53174f7efdbe', '0196bad8-32e6-7286-970c-5102a21617f6'),
('8888d328-222d-459c-969d-8fc51d404754', '0196bad8-32e6-7286-970c-5102a21617f6'),
('d998576f-043b-47f9-8cde-6b64bd3004e4', '0196bad8-32e6-7286-970c-5102a21617f6'),
('f4c4cfcd-9e0c-4f4a-a981-1dc8dfeb105f', '0196bad8-32e6-7286-970c-5102a21617f6'),
('42eabce0-c403-4c96-a6fd-aea3ee66274f', '0196bad8-5991-7059-a15f-79a50914f090'),
('60c4f0f6-989f-4db3-bea8-3328d17aabbb', '0196bad8-5991-7059-a15f-79a50914f090'),
('78d49315-357a-4d6e-9113-53174f7efdbe', '0196bad8-5991-7059-a15f-79a50914f090'),
('8888d328-222d-459c-969d-8fc51d404754', '0196bad8-5991-7059-a15f-79a50914f090'),
('d998576f-043b-47f9-8cde-6b64bd3004e4', '0196bad8-5991-7059-a15f-79a50914f090'),
('f4c4cfcd-9e0c-4f4a-a981-1dc8dfeb105f', '0196bad8-5991-7059-a15f-79a50914f090'),
('42eabce0-c403-4c96-a6fd-aea3ee66274f', '0196bad9-1cc8-71c1-8671-8b7306cb5bad'),
('60c4f0f6-989f-4db3-bea8-3328d17aabbb', '0196bad9-1cc8-71c1-8671-8b7306cb5bad'),
('8888d328-222d-459c-969d-8fc51d404754', '0196bad9-1cc8-71c1-8671-8b7306cb5bad'),
('d998576f-043b-47f9-8cde-6b64bd3004e4', '0196bad9-1cc8-71c1-8671-8b7306cb5bad'),
('f4c4cfcd-9e0c-4f4a-a981-1dc8dfeb105f', '0196bad9-1cc8-71c1-8671-8b7306cb5bad'),
('8888d328-222d-459c-969d-8fc51d404754', '0196bad9-5025-7149-be2c-fb1ba97486b5'),
('d998576f-043b-47f9-8cde-6b64bd3004e4', '0196bad9-5025-7149-be2c-fb1ba97486b5'),
('f4c4cfcd-9e0c-4f4a-a981-1dc8dfeb105f', '0196bad9-5025-7149-be2c-fb1ba97486b5'),
('f4c4cfcd-9e0c-4f4a-a981-1dc8dfeb105f', '0196f72c-803e-7311-ad2c-b011486b2409');

-- --------------------------------------------------------

--
-- Table structure for table `contents`
--

CREATE TABLE `contents` (
  `id` char(36) COLLATE utf8mb4_unicode_ci NOT NULL,
  `title` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `slug` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `description` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `content_types_id` char(36) COLLATE utf8mb4_unicode_ci NOT NULL,
  `users_id` char(36) COLLATE utf8mb4_unicode_ci NOT NULL,
  `categories_id` char(36) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `status` enum('published','unpublished') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'unpublished',
  `image` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `views` bigint DEFAULT '0',
  `published_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `contents`
--

INSERT INTO `contents` (`id`, `title`, `slug`, `description`, `content_types_id`, `users_id`, `categories_id`, `status`, `image`, `views`, `published_at`, `created_at`, `updated_at`) VALUES
('42eabce0-c403-4c96-a6fd-aea3ee66274f', 'dari admin', 'dari-admin', '<p>wdawdawdw</p>\n', '0196bada-32d5-7280-b157-c98fa2a9c25b', '7582a5bc-4647-4454-a445-59ebd7907a6b', '0196a5e3-3ef2-735f-a8c5-f1637cdb2965', 'published', 'contents/fwbUe6Yq0zRVeyepyqp4zcK2Frwrhj1jDkMbupEi.png', 0, '2025-05-25 21:55:10', '2025-05-25 21:55:10', '2025-05-25 21:55:10'),
('60c4f0f6-989f-4db3-bea8-3328d17aabbb', 'testinggg', 'testinggg', '<p>dawdawdwadaw</p>\n', '0196bada-78d0-739b-9374-7c82cb89d362', '7582a5bc-4647-4454-a445-59ebd7907a6b', '0196bad8-32e6-7286-970c-5102a21617f6', 'published', 'contents/rNv4MGE8Ng3zjLPTfNKr0bTvv8MPdhLIwiADNPW2.png', 1, '2025-05-26 01:22:05', '2025-05-26 01:22:05', '2025-05-26 01:22:23'),
('78d49315-357a-4d6e-9113-53174f7efdbe', 'test berita Event', 'test-berita-event', '<p>dawdawdawd</p>\n', '0196f776-6cd7-70ce-b9a1-fc6cd408dbef', '9b5287a2-9e3b-4992-8a8d-b1f7a53199a9', '0196a5e3-3ef2-735f-a8c5-f1637cdb2965', 'published', 'contents/0VSL3iPOJU2WqjEcSTzxyS5bwSnB61QsWbd4UfIg.png', 1, '2025-05-25 21:33:51', '2025-05-25 21:33:51', '2025-05-25 21:34:34'),
('8888d328-222d-459c-969d-8fc51d404754', 'test berita mbkm', 'test-berita-mbkm', '<p>dawdawdawdawdw</p>\n', '0196bada-78d0-739b-9374-7c82cb89d362', '9b5287a2-9e3b-4992-8a8d-b1f7a53199a9', '0196a5e3-3ef2-735f-a8c5-f1637cdb2965', 'published', 'contents/adFeprihTr5ypNSJ35rhhWWFzABHgLVpUAKwQyHv.png', 1, '2025-05-25 21:32:59', '2025-05-25 21:32:59', '2025-05-25 21:34:07'),
('d998576f-043b-47f9-8cde-6b64bd3004e4', 'dwadwada', 'dwadwada', '<p>testing semoga tampil di depan</p>\n', '0196bada-32d5-7280-b157-c98fa2a9c25b', '9b5287a2-9e3b-4992-8a8d-b1f7a53199a9', '0196a5e3-3ef2-735f-a8c5-f1637cdb2965', 'published', 'contents/LMDphaEuMcr7wDGoQXSDAp1I1Fb2VUsjNrWMMR6I.png', 4, '2025-05-22 03:01:45', '2025-05-22 03:01:45', '2025-05-25 21:29:39'),
('f4c4cfcd-9e0c-4f4a-a981-1dc8dfeb105f', 'testing', 'testing', '<p>dawdawdawdawd</p>\n', '0196bada-32d5-7280-b157-c98fa2a9c25b', '9b5287a2-9e3b-4992-8a8d-b1f7a53199a9', '0196bad8-32e6-7286-970c-5102a21617f6', 'published', 'contents/Mxfb3FdYctAE83Z9BtrmJYYJh5jnbXsyZP3nAFyZ.png', 4, '2025-05-22 01:47:44', '2025-05-22 01:47:44', '2025-05-25 21:13:26');

-- --------------------------------------------------------

--
-- Table structure for table `content_types`
--

CREATE TABLE `content_types` (
  `id` char(36) COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `content_types`
--

INSERT INTO `content_types` (`id`, `name`, `created_at`, `updated_at`) VALUES
('019699bf-b614-7019-bf18-90a31296be82', 'Pengumuman FT', '2025-05-03 22:23:20', '2025-05-10 08:40:08'),
('0196a5e3-b10b-7161-a646-61cec92bd851', 'berita', '2025-05-06 06:58:04', '2025-05-06 07:04:49'),
('0196bada-32d5-7280-b157-c98fa2a9c25b', 'FT News', '2025-05-10 08:39:44', '2025-05-10 08:39:44'),
('0196bada-78d0-739b-9374-7c82cb89d362', 'MBKM News', '2025-05-10 08:40:02', '2025-05-10 08:40:02'),
('0196f776-6cd7-70ce-b9a1-fc6cd408dbef', 'FT Event', '2025-05-22 03:07:35', '2025-05-22 03:07:35');

-- --------------------------------------------------------

--
-- Table structure for table `departments`
--

CREATE TABLE `departments` (
  `id` char(36) COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `departments`
--

INSERT INTO `departments` (`id`, `name`, `created_at`, `updated_at`) VALUES
('01967791-8afe-7082-a265-f3c3da76bdf4', 'Pendidikan Teknik Elektro', '2025-04-27 07:05:49', '2025-05-10 08:40:40'),
('019677b4-6c90-73b8-aaa4-ecc46e58fdee', 'Pendidikan Tata Boga', '2025-04-27 07:43:55', '2025-04-27 22:44:16'),
('0196a68a-5e79-70aa-aa34-2a0d9a95d88c', 'Staf Fakultas', '2025-05-06 10:00:08', '2025-05-23 08:13:33');

-- --------------------------------------------------------

--
-- Table structure for table `failed_jobs`
--

CREATE TABLE `failed_jobs` (
  `id` bigint UNSIGNED NOT NULL,
  `uuid` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `connection` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `queue` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `exception` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `jobs`
--

CREATE TABLE `jobs` (
  `id` bigint UNSIGNED NOT NULL,
  `queue` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `attempts` tinyint UNSIGNED NOT NULL,
  `reserved_at` int UNSIGNED DEFAULT NULL,
  `available_at` int UNSIGNED NOT NULL,
  `created_at` int UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `job_batches`
--

CREATE TABLE `job_batches` (
  `id` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `total_jobs` int NOT NULL,
  `pending_jobs` int NOT NULL,
  `failed_jobs` int NOT NULL,
  `failed_job_ids` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `options` mediumtext COLLATE utf8mb4_unicode_ci,
  `cancelled_at` int DEFAULT NULL,
  `created_at` int NOT NULL,
  `finished_at` int DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `migrations`
--

CREATE TABLE `migrations` (
  `id` int UNSIGNED NOT NULL,
  `migration` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `batch` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `migrations`
--

INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES
(10, '0001_01_01_000001_create_cache_table', 1),
(11, '0001_01_01_000002_create_jobs_table', 1),
(12, '0001_01_01_000000_create_users_table', 2),
(13, '2025_04_26_090818_create_departments_table', 2),
(14, '2025_04_26_100030_modify_users-table_structure', 3),
(15, '2025_04_26_090308_create_department_table', 4),
(16, '2025_04_26_102211_create_content_types_table', 5),
(20, '2025_04_26_102645_create_categories_table', 6),
(21, '2025_04_26_103010_create_contents_table', 7),
(22, '2025_04_26_113327_create_banners_table', 7),
(23, '2025_04_28_104235_change_type_phone_number', 8),
(24, '2025_05_04_154025_create_category_content_table', 8),
(25, '2025_05_14_184258_add_views_and_published_at_to_contents_table', 9);

-- --------------------------------------------------------

--
-- Table structure for table `password_reset_tokens`
--

CREATE TABLE `password_reset_tokens` (
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `sessions`
--

CREATE TABLE `sessions` (
  `id` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `user_id` bigint UNSIGNED DEFAULT NULL,
  `ip_address` varchar(45) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `user_agent` text COLLATE utf8mb4_unicode_ci,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `last_activity` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `sessions`
--

INSERT INTO `sessions` (`id`, `user_id`, `ip_address`, `user_agent`, `payload`, `last_activity`) VALUES
('YaDtysLgGJW0lqVJFWWU5xbFyF8WpybLYk8DMZy0', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/136.0.0.0 Safari/537.36', 'YTo0OntzOjY6Il90b2tlbiI7czo0MDoiUlpRTnVvZVpmckZhU2l5UVZURjRqU0p1Y3hybjVJUEdNQlg5UTVEMiI7czozOiJ1cmwiO2E6MTp7czo4OiJpbnRlbmRlZCI7czozMToiaHR0cDovLzEyNy4wLjAuMTo4MDAwL2Rhc2hib2FyZCI7fXM6OToiX3ByZXZpb3VzIjthOjE6e3M6MzoidXJsIjtzOjI3OiJodHRwOi8vMTI3LjAuMC4xOjgwMDAvbG9naW4iO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX19', 1747830026);

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` char(36) COLLATE utf8mb4_unicode_ci NOT NULL,
  `username` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `fullname` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `role` enum('staff','admin') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'admin',
  `id_department` char(36) COLLATE utf8mb4_unicode_ci NOT NULL,
  `position` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `phone_number` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `image` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `remember_token` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `username`, `fullname`, `email`, `role`, `id_department`, `position`, `phone_number`, `image`, `email_verified_at`, `password`, `remember_token`, `created_at`, `updated_at`) VALUES
('7582a5bc-4647-4454-a445-59ebd7907a6b', 'rafi', 'Rafi Hidayat', 'rafi50631@gmail.com', 'admin', '01967791-8afe-7082-a265-f3c3da76bdf4', 'Mahasiswa', '085767677304', 'profile-photos/27bJrKoSfHGBS9CXuALeYK15D1GKblGPRH3ltk6I.png', NULL, '$2y$12$zZ7rl.M1OxxIPVfpsl/wXOy9cdlquNrg77TltFfoerEWmcnshiCsa', NULL, '2025-05-13 07:21:49', '2025-05-26 01:36:19'),
('9b5287a2-9e3b-4992-8a8d-b1f7a53199a9', 'ali akbarre', 'Ali Akbar Lubisss', 'aliakbarlusbis@gmail.com', 'staff', '0196a68a-5e79-70aa-aa34-2a0d9a95d88c', 'Pengajar', '124678419217', 'profile-photos/xtKrfSIgldtmJVfSXjnE6AMIxUkf8zrVDkC1mZCl.jpg', NULL, '$2y$12$/bolZrHH1oHNAH8URHXkWe1CgbyTnDT57D0Ki7FFN4ikUbHxpFfN2', NULL, '2025-04-28 06:28:25', '2025-05-25 21:47:53');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `banners`
--
ALTER TABLE `banners`
  ADD PRIMARY KEY (`id`);

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
-- Indexes for table `categories`
--
ALTER TABLE `categories`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `category_content`
--
ALTER TABLE `category_content`
  ADD PRIMARY KEY (`content_id`,`category_id`),
  ADD KEY `category_content_category_id_foreign` (`category_id`);

--
-- Indexes for table `contents`
--
ALTER TABLE `contents`
  ADD PRIMARY KEY (`id`),
  ADD KEY `contents_content_types_id_foreign` (`content_types_id`),
  ADD KEY `contents_users_id_foreign` (`users_id`),
  ADD KEY `contents_categories_id_foreign` (`categories_id`);

--
-- Indexes for table `content_types`
--
ALTER TABLE `content_types`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `departments`
--
ALTER TABLE `departments`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`);

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
-- Indexes for table `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `password_reset_tokens`
--
ALTER TABLE `password_reset_tokens`
  ADD PRIMARY KEY (`email`);

--
-- Indexes for table `sessions`
--
ALTER TABLE `sessions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `sessions_user_id_index` (`user_id`),
  ADD KEY `sessions_last_activity_index` (`last_activity`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `users_email_unique` (`email`),
  ADD UNIQUE KEY `users_username_unique` (`username`),
  ADD UNIQUE KEY `users_fullname_unique` (`fullname`),
  ADD KEY `users_id_department_foreign` (`id_department`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `jobs`
--
ALTER TABLE `jobs`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=26;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `category_content`
--
ALTER TABLE `category_content`
  ADD CONSTRAINT `category_content_category_id_foreign` FOREIGN KEY (`category_id`) REFERENCES `categories` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `category_content_content_id_foreign` FOREIGN KEY (`content_id`) REFERENCES `contents` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `contents`
--
ALTER TABLE `contents`
  ADD CONSTRAINT `contents_categories_id_foreign` FOREIGN KEY (`categories_id`) REFERENCES `categories` (`id`),
  ADD CONSTRAINT `contents_content_types_id_foreign` FOREIGN KEY (`content_types_id`) REFERENCES `content_types` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `contents_users_id_foreign` FOREIGN KEY (`users_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `users`
--
ALTER TABLE `users`
  ADD CONSTRAINT `users_id_department_foreign` FOREIGN KEY (`id_department`) REFERENCES `departments` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
