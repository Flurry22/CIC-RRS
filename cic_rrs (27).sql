-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Mar 05, 2025 at 05:06 AM
-- Server version: 10.4.28-MariaDB
-- PHP Version: 8.2.4

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `cic_rrs`
--

-- --------------------------------------------------------

--
-- Table structure for table `academic_administrators`
--

CREATE TABLE `academic_administrators` (
  `id` bigint(2) UNSIGNED NOT NULL,
  `username` varchar(30) NOT NULL,
  `email` varchar(30) NOT NULL,
  `password` varchar(80) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `academic_administrators`
--

INSERT INTO `academic_administrators` (`id`, `username`, `email`, `password`, `created_at`, `updated_at`) VALUES
(1, 'admin', 'admin@usep.edu.ph', '$2y$12$YDUR3z3tqHfkRrP7OfIhjOjPIobGS3F/cMf6VjVo5NtEEYYDmdevW', '2024-12-08 21:42:45', '2024-12-08 21:42:45');

-- --------------------------------------------------------

--
-- Table structure for table `dost_6ps`
--

CREATE TABLE `dost_6ps` (
  `id` bigint(1) UNSIGNED NOT NULL,
  `name` varchar(25) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `dost_6ps`
--

INSERT INTO `dost_6ps` (`id`, `name`, `created_at`, `updated_at`) VALUES
(1, 'Publication', NULL, NULL),
(2, 'Patent', NULL, NULL),
(3, 'Product', NULL, NULL),
(4, 'People Services', NULL, NULL),
(5, 'Places and Partnerships', NULL, NULL),
(6, 'Policies', NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `funding_types`
--

CREATE TABLE `funding_types` (
  `id` bigint(1) UNSIGNED NOT NULL,
  `type` varchar(20) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `funding_types`
--

INSERT INTO `funding_types` (`id`, `type`, `created_at`, `updated_at`) VALUES
(1, 'Internally Funded', NULL, NULL),
(2, 'Externally Funded', NULL, NULL);

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
(1, '2019_12_14_000001_create_personal_access_tokens_table', 1),
(2, '2024_11_14_071443_create_academic_administrators_table', 1),
(3, '2024_11_14_071443_create_research_staff_table', 1),
(4, '2024_11_14_071444_create_researchers_table', 1),
(5, '2024_11_20_135822_add_bio_and_skills_to_researchers_table', 2),
(8, '2024_11_20_144030_create_educational_backgrounds_table', 3),
(9, '2024_11_20_144104_create_workplaces_table', 3),
(10, '2024_11_25_150310_create_funding_types_table', 4),
(11, '2024_11_25_150309_create_programs_table', 5),
(12, '2024_11_25_150308_create_researches_table', 6),
(13, '2024_11_25_150309_create_researcher_research_table', 7),
(14, '2024_11_27_033623_add_title_to_researches_table', 8),
(15, '2024_11_27_043523_update_researches_table_with_funding_type_id', 9),
(16, '2024_11_27_141750_add_google_drive_link_to_researches_table', 10),
(17, '2024_12_01_033838_add_status_to_researches_table', 11),
(18, '2024_12_01_065747_add_certificate_of_utilization_to_researches_table', 12),
(19, '2024_12_01_070703_add_special_order_to_researches_table', 13),
(20, '2024_12_09_023409_add_role_to_researcher_research_table', 14),
(21, '2024_12_10_140830_add_level_to_programs_table', 15),
(22, '2024_12_11_053706_create_school_years_table', 16),
(23, '2024_12_11_073225_add_approved_date_terminal_file_approved_file_to_researches_table', 17),
(24, '2024_12_11_081224_add_date_completed_to_researches_table', 18),
(25, '2024_12_11_173217_add_funded_by_to_researches_table', 19),
(26, '2024_12_12_063946_add_school_year_id_to_researches_table', 20),
(27, '2024_12_12_065317_add_semester_to_researches_table', 21),
(28, '2024_12_16_180417_create_research_files_table', 22),
(29, '2024_12_17_042138_update_research_files_table', 23),
(30, '2024_12_17_044613_create_research_files_table', 24),
(31, '2024_12_19_151607_add_proposal_file_to_researches_table', 25),
(32, '2024_12_19_170303_create_sdgs_table', 26),
(34, '2024_12_19_173146_create_dost_6ps_table', 27),
(37, '2024_12_19_174218_create_research_dost_6p_table', 28),
(38, '2024_12_19_174218_create_research_sdg_table', 28),
(39, '2024_12_20_052510_create_program_researcher_table', 29),
(40, '2025_02_05_065848_create_program_research_table', 30),
(41, '2025_02_20_140237_add_project_duration_to_researches_table', 31);

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
-- Table structure for table `programs`
--

CREATE TABLE `programs` (
  `id` bigint(2) UNSIGNED NOT NULL,
  `name` varchar(50) NOT NULL,
  `level` enum('undergraduate','graduate') NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `programs`
--

INSERT INTO `programs` (`id`, `name`, `level`, `created_at`, `updated_at`) VALUES
(1, 'Bachelor of Science in Information Technology', 'undergraduate', NULL, NULL),
(2, 'Bachelor of Science in Computer Science', 'undergraduate', NULL, NULL),
(3, 'Bachelor of Library Information Science', 'undergraduate', NULL, NULL),
(4, 'Master of Information Technology', 'graduate', NULL, NULL),
(5, 'Doctor of Information Technology', 'graduate', NULL, NULL),
(6, 'Master of Library Information Science', 'graduate', NULL, NULL),
(7, 'Master of Science in Library Information', 'graduate', NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `program_research`
--

CREATE TABLE `program_research` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `research_id` bigint(20) UNSIGNED NOT NULL,
  `program_id` bigint(20) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `program_researcher`
--

CREATE TABLE `program_researcher` (
  `id` bigint(5) UNSIGNED NOT NULL,
  `program_id` bigint(2) UNSIGNED NOT NULL,
  `researcher_id` bigint(2) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `program_researcher`
--

INSERT INTO `program_researcher` (`id`, `program_id`, `researcher_id`, `created_at`, `updated_at`) VALUES
(1, 2, 1, NULL, NULL),
(2, 1, 2, NULL, NULL),
(3, 3, 3, NULL, NULL),
(4, 2, 4, NULL, NULL),
(5, 3, 5, NULL, NULL),
(6, 6, 5, NULL, NULL),
(7, 1, 6, NULL, NULL),
(8, 4, 6, NULL, NULL),
(9, 5, 6, NULL, NULL),
(10, 1, 7, NULL, NULL),
(11, 4, 7, NULL, NULL),
(12, 5, 7, NULL, NULL),
(13, 1, 8, NULL, NULL),
(14, 2, 9, NULL, NULL),
(15, 3, 10, NULL, NULL),
(16, 1, 11, NULL, NULL),
(17, 4, 11, NULL, NULL),
(18, 5, 11, NULL, NULL),
(19, 1, 12, NULL, NULL),
(20, 4, 12, NULL, NULL),
(21, 5, 12, NULL, NULL),
(22, 1, 13, NULL, NULL),
(23, 4, 13, NULL, NULL),
(24, 1, 14, NULL, NULL),
(25, 2, 14, NULL, NULL),
(26, 1, 15, NULL, NULL),
(27, 3, 15, NULL, NULL),
(28, 1, 16, NULL, NULL),
(29, 1, 17, NULL, NULL),
(30, 2, 17, NULL, NULL),
(31, 3, 17, NULL, NULL),
(32, 3, 18, NULL, NULL),
(33, 6, 18, NULL, NULL),
(34, 7, 18, NULL, NULL),
(35, 1, 19, NULL, NULL),
(36, 1, 20, NULL, NULL),
(37, 3, 20, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `researchers`
--

CREATE TABLE `researchers` (
  `id` bigint(2) UNSIGNED NOT NULL,
  `name` varchar(40) NOT NULL,
  `position` varchar(25) NOT NULL,
  `email` varchar(30) NOT NULL,
  `password` varchar(80) NOT NULL,
  `profile_picture` varchar(100) DEFAULT NULL,
  `bio` text DEFAULT NULL,
  `skills` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`skills`)),
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `researchers`
--

INSERT INTO `researchers` (`id`, `name`, `position`, `email`, `password`, `profile_picture`, `bio`, `skills`, `created_at`, `updated_at`) VALUES
(1, 'Hobert Abrigana', 'Instructor', 'haabrigana@usep.edu.ph', '$2y$12$i24i1aiPKa6CseEiSXDBg.eHQK.FSunMydN9sHetX/z.6VWhelqD.', 'profile_pictures/GEYZWwMAGiLt3A7XfoV2WMO5S79K9bcCNKL5qDP4.jpg', NULL, NULL, '2025-03-04 18:31:36', '2025-03-04 18:32:45'),
(2, 'Cheryl Amante', 'Instructor', 'cramante@usep.edu.ph', '$2y$12$0GzMd4BeM4rs5cZf6IdoGuh85br0/wKQjshwvTeZPG2kx0rwO.cx6', 'profile_pictures/nP6vxzJ8E20DDYJtHNGi2qe8n4O3YZsHqesE96s6.png', NULL, NULL, '2025-03-04 18:33:40', '2025-03-04 18:33:40'),
(3, 'Annacel Delima', 'Instructor', 'annacel.delima@usep.edu.ph', '$2y$12$4wGgHZTqPoTh6GqHIOHIYuH3DYMNc1j8PcLJ7UdB3HOUKKMXkKYLm', 'profile_pictures/BIOUbwLq9HTaK874qkFct8huYtyr6zQt39LOX7ng.png', NULL, NULL, '2025-03-04 18:35:36', '2025-03-04 18:35:36'),
(4, 'Cristina Dumdumaya', 'Professor', 'cedumdumaya@usep.edu.ph', '$2y$12$PQttCwDsl1AbcBAQgSNczemrrnshf3muFp3sNpcbJg9wT.kru0gOW', 'profile_pictures/K2zb4Eyjk7f49UkrIvDC6EQFk3kYxnWeTxRgvM8j.jpg', NULL, NULL, '2025-03-04 18:37:29', '2025-03-04 18:37:29'),
(5, 'Gresiel Ferrando', 'Professor', 'gresielferrando@usep.edu.ph', '$2y$12$0gkbqyPs4qMhkCJUwhcwTeC8ScAk1gF31nDfPJtbM0nTWTtpnERgu', 'profile_pictures/QAEP1kFCCP3qyyz0DXPvnheJbRC1o28oMB4nf8KQ.png', NULL, NULL, '2025-03-04 18:38:21', '2025-03-04 18:38:21'),
(6, 'Randy Gamboa', 'Professor', 'rsgamboa@usep.edu.ph', '$2y$12$t2QlsBI.cuGY65FTrBPDS.YUmV2qrnkShU4xCyD/l20cBAggRugXS', 'profile_pictures/ntAaT4epgL1SJLSjpcYfQXMYwRNhVnsJbeMANKow.png', NULL, NULL, '2025-03-04 18:40:40', '2025-03-04 18:40:40'),
(7, 'Ivy Kim Machica', 'Dean', 'ikmachica@usep.edu.ph', '$2y$12$DFRmqnDIOYyjxBVtWZLWS.gxSrtygan2sNNLLS28TA0vU6idtMpOO', 'profile_pictures/2k50EhZAMpxkh8rcanIJtBU880mXoKe8nSso3bnD.jpg', NULL, NULL, '2025-03-04 18:42:17', '2025-03-04 18:42:17'),
(8, 'Michael Machica', 'Instructor', 'michael.machica@usep.edu.ph', '$2y$12$5/TkbKzgEjrGJziiXfAmx.jjn2T74zTbBzllWy8hTUxmDx8M0f8.G', 'profile_pictures/Lfw3leEPxIAl2OG3d3wtYoqq08yWLjYN84uRPyvq.jpg', NULL, NULL, '2025-03-04 18:46:53', '2025-03-04 18:46:53'),
(9, 'Tamara Cher Mercado', 'Professor', 'tammy@usep.edu.ph', '$2y$12$.zfa6eYfok7Yr/31BiqCeObPh4nmZhCdcqTLryVrBSZWQw0n7gPFO', 'profile_pictures/TwYnB4tEvHS3nTAyH1u86iAcVrkyZgTbI8347KrB.png', NULL, NULL, '2025-03-04 18:48:34', '2025-03-04 18:48:34'),
(10, 'Cindy Moldes', 'Instructor', 'cindy.moldes@usep.edu.ph', '$2y$12$eHYfK/J4NiAtPErBFJ9tqOHIJncJ63UVm6E0sSar/1GelmpDuXusS', 'profile_pictures/OCLLMuJENddxTcE4caJGCvT8Tdv5UrygxYdNMWtq.png', NULL, NULL, '2025-03-04 18:49:31', '2025-03-04 18:49:31'),
(11, 'Nancy Mozo', 'Assistant Professor', 'nancy.mozo@usep.edu.ph', '$2y$12$SMq8b2jPLUAfxQy8xo2zEeKmhs/.00CMf/usFTNlikaML1k/I6DI2', 'profile_pictures/iTNzfxPa70XOJsJz2FFtOKQBLV1yVTaLSRZw1wmx.png', NULL, NULL, '2025-03-04 18:50:58', '2025-03-04 18:50:58'),
(12, 'Leah Pelias', 'Assistant Professor', 'leah.pelias@usep.edu.ph', '$2y$12$eYLxgXtlZ/xzabyLxDpYTOhtNtdJ/rEdV38hCfFYg70QfQHjBIvhW', 'profile_pictures/jymudfLGP0pZCvaOSjXt9YDFnkCerb8PFDasDfhl.png', NULL, NULL, '2025-03-04 18:52:09', '2025-03-04 18:52:09'),
(13, 'Ariel Roy Reyes', 'Associate Professor', 'ariel.reyes@usep.edu.ph', '$2y$12$iyL3UdYPuQAaFfSAFRodVu/0nQwgqcQduAv.c9cx0oH75B/ga6At2', 'profile_pictures/62JRF1CX2g4SGY3WnqGxgXoG89Sp3EhrCm0k7PHw.png', NULL, NULL, '2025-03-04 18:53:02', '2025-03-04 18:53:02'),
(14, 'Jamal Kay Rogers', 'Associate Professor', 'jamalkay.rogers@usep.edu.ph', '$2y$12$xYR2hcxI8x/TRgOjzhZkDuDbwItf.R9Cihu8G1T.FewCaOIDazwhG', 'profile_pictures/LvXKewCI5SRLPd5e5jBI3zPwaRKJxvbNe1wKDrSB.png', NULL, NULL, '2025-03-04 18:53:57', '2025-03-04 18:53:57'),
(15, 'Francis Adrian', 'Instructor', 'francis.sanico@usep.edu.ph', '$2y$12$M1JEDmtMEIQQ7.BFxUexzedNkigla37H5vt1ONkuAu.pJ9DlirN7m', 'profile_pictures/k5VADabtkRD70GEac6CBaCrbdV31iWds58FnSCw8.png', NULL, NULL, '2025-03-04 18:55:12', '2025-03-04 19:01:38'),
(16, 'Vera Kim Tequin', 'Instructor', 'vkstequin@usep.edu.ph', '$2y$12$4Lx6Nk1Z.buP/ygG8toyIOEtE.A0THNiHAtcRMSAGsiuU8rIGHSXm', 'profile_pictures/A5IfQzr6ISeFoUPbPYRSR6qmvbWXni7UIqYJ7uPl.png', NULL, NULL, '2025-03-04 18:56:12', '2025-03-04 18:56:12'),
(17, 'Hermoso Tupas Jr.', 'Instructor', 'hermoso.tupas@usep.edu.ph', '$2y$12$8SYD5iiEUyllRj598t8uNeR4BD6wDJ/09nBdvcQDbx0s8m1E.9F52', 'profile_pictures/Kc8vRGJJyGDsEfhiat8A8N1caDXZNgGG9sGypxzu.png', NULL, NULL, '2025-03-04 18:57:01', '2025-03-04 18:57:01'),
(18, 'Maureen Villamor', 'Professor', 'maui@usep.edu.ph', '$2y$12$7e.LS9lcyK4fDH0HFT8AkOTmyevhNVhIR4UBiHIYR/cepFnLWGM7y', 'profile_pictures/utJDnXbLP5RYpJdR26jDYF1AAFMnXH7qcHJL4vJC.png', NULL, NULL, '2025-03-04 18:58:17', '2025-03-04 18:58:17'),
(19, 'Philip Navarez', 'Instructor', 'prnavarez@usep.edu.ph', '$2y$12$0mV0JraKwYO8SPV8EL2rQ.EuLnnpGNsobS3fb7jyV3EpcJWo.wRNi', 'profile_pictures/QVjJmt3K1p0cN2bu1Djh03KXikDFk8fpDGcQYCss.jpg', NULL, NULL, '2025-03-04 19:00:11', '2025-03-04 19:00:11'),
(20, 'Eula Templa', 'Instructor', 'eula.nabong@usep.edu.ph', '$2y$12$FB//IYiG3QUe88/ZKR3gPOjYaF9tZsj1ngej5FlbqTTAzGSeBRYOm', 'profile_pictures/yYPhUMKts7XzBdKKdiIwhDViQC3BTEoAkJOdscQW.png', NULL, NULL, '2025-03-04 19:03:28', '2025-03-04 19:03:28');

-- --------------------------------------------------------

--
-- Table structure for table `researcher_research`
--

CREATE TABLE `researcher_research` (
  `id` bigint(5) UNSIGNED NOT NULL,
  `research_id` bigint(2) UNSIGNED NOT NULL,
  `researcher_id` bigint(2) UNSIGNED NOT NULL,
  `role` varchar(10) NOT NULL DEFAULT 'member',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `researches`
--

CREATE TABLE `researches` (
  `id` bigint(2) UNSIGNED NOT NULL,
  `title` varchar(255) DEFAULT NULL,
  `funding_type_id` bigint(1) UNSIGNED NOT NULL,
  `description` text NOT NULL,
  `budget` bigint(15) UNSIGNED NOT NULL,
  `leader_id` bigint(2) UNSIGNED NOT NULL,
  `type` enum('program','project','study') NOT NULL,
  `deadline` date NOT NULL,
  `project_duration` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `status` enum('On-Going','Finished') NOT NULL DEFAULT 'On-Going',
  `certificate_of_utilization` varchar(255) DEFAULT NULL,
  `special_order` varchar(255) DEFAULT NULL,
  `approved_date` date DEFAULT NULL,
  `terminal_file` varchar(80) DEFAULT NULL,
  `approved_file` varchar(80) DEFAULT NULL,
  `proposal_file` varchar(80) DEFAULT NULL,
  `date_completed` timestamp NULL DEFAULT NULL,
  `funded_by` varchar(30) DEFAULT NULL,
  `school_year_id` bigint(2) UNSIGNED DEFAULT NULL,
  `semester` varchar(20) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `research_dost_6p`
--

CREATE TABLE `research_dost_6p` (
  `id` bigint(5) UNSIGNED NOT NULL,
  `research_id` bigint(2) UNSIGNED NOT NULL,
  `dost_6p_id` bigint(1) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `research_files`
--

CREATE TABLE `research_files` (
  `id` bigint(2) UNSIGNED NOT NULL,
  `title` varchar(100) NOT NULL,
  `research_file` varchar(80) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `research_sdg`
--

CREATE TABLE `research_sdg` (
  `id` bigint(5) UNSIGNED NOT NULL,
  `research_id` bigint(2) UNSIGNED NOT NULL,
  `sdg_id` bigint(2) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `research_staff`
--

CREATE TABLE `research_staff` (
  `id` bigint(2) UNSIGNED NOT NULL,
  `username` varchar(30) NOT NULL,
  `email` varchar(40) NOT NULL,
  `password` varchar(80) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `research_staff`
--

INSERT INTO `research_staff` (`id`, `username`, `email`, `password`, `created_at`, `updated_at`) VALUES
(1, 'staff', 'staff@usep.edu.ph', '$2y$12$M3jq4MUnQBitLG4WnxKVh./TsDQ73oI3EQ7.vZypv3NOXJoasbLuW', '2024-11-19 09:16:48', '2024-11-19 09:16:48');

-- --------------------------------------------------------

--
-- Table structure for table `school_years`
--

CREATE TABLE `school_years` (
  `id` bigint(2) UNSIGNED NOT NULL,
  `school_year` varchar(10) NOT NULL,
  `first_sem_start` date NOT NULL,
  `first_sem_end` date NOT NULL,
  `second_sem_start` date NOT NULL,
  `second_sem_end` date NOT NULL,
  `off_sem_start` date DEFAULT NULL,
  `off_sem_end` date DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `school_years`
--

INSERT INTO `school_years` (`id`, `school_year`, `first_sem_start`, `first_sem_end`, `second_sem_start`, `second_sem_end`, `off_sem_start`, `off_sem_end`, `created_at`, `updated_at`) VALUES
(1, '2024-2025', '2024-08-19', '2024-12-20', '2025-01-12', '2025-05-23', '2025-06-09', '2025-07-25', '2024-12-10 22:21:27', '2024-12-10 23:22:57'),
(2, '2023-2024', '2023-08-14', '2023-12-22', '2024-01-15', '2024-05-24', '2024-06-10', '2024-07-26', '2025-01-05 23:07:07', '2025-01-05 23:07:07'),
(3, '2022-2023', '2022-08-22', '2022-12-20', '2023-01-16', '2023-05-19', '2023-06-13', '2023-07-16', '2025-01-05 23:08:59', '2025-01-05 23:08:59'),
(4, '2021-2022', '2021-08-16', '2021-12-17', '2022-01-17', '2022-05-20', '2022-06-06', '2022-07-15', '2025-01-05 23:10:58', '2025-01-05 23:10:58'),
(5, '2020-2021', '2020-08-17', '2020-12-18', '2021-01-18', '2021-05-21', '2021-06-07', '2021-07-16', '2025-01-05 23:12:26', '2025-01-05 23:12:26'),
(6, '2019-2020', '2019-08-19', '2019-12-21', '2020-01-13', '2020-05-22', '2020-06-08', '2020-07-24', '2025-03-04 19:21:45', '2025-03-04 19:21:45');

-- --------------------------------------------------------

--
-- Table structure for table `sdgs`
--

CREATE TABLE `sdgs` (
  `id` bigint(2) UNSIGNED NOT NULL,
  `name` varchar(50) NOT NULL,
  `goal_number` varchar(2) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `sdgs`
--

INSERT INTO `sdgs` (`id`, `name`, `goal_number`, `created_at`, `updated_at`) VALUES
(1, 'No Poverty', '1', NULL, NULL),
(2, 'Zero Hunger', '2', NULL, NULL),
(3, 'Good Health and Well-being', '3', NULL, NULL),
(4, 'Quality Education', '4', NULL, NULL),
(5, 'Gender Equality', '5', NULL, NULL),
(6, 'Clean Water and Sanitation', '6', NULL, NULL),
(7, 'Affordable and Clean Energy', '7', NULL, NULL),
(8, 'Decent Work and Economic Growth', '8', NULL, NULL),
(9, 'Industry, Innovation, and Infrastructure', '9', NULL, NULL),
(10, 'Reduced Inequalities', '10', NULL, NULL),
(11, 'Sustainable Cities and Communities', '11', NULL, NULL),
(12, 'Responsible Consumption and Production', '12', NULL, NULL),
(13, 'Climate Action', '13', NULL, NULL),
(14, 'Life Below Water', '14', NULL, NULL),
(15, 'Life on Land', '15', NULL, NULL),
(16, 'Peace, Justice, and Strong Institutions', '16', NULL, NULL),
(17, 'Partnerships for the Goals', '17', NULL, NULL);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `academic_administrators`
--
ALTER TABLE `academic_administrators`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `academic_administrators_username_unique` (`username`),
  ADD UNIQUE KEY `academic_administrators_email_unique` (`email`);

--
-- Indexes for table `dost_6ps`
--
ALTER TABLE `dost_6ps`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `dost_6ps_name_unique` (`name`);

--
-- Indexes for table `funding_types`
--
ALTER TABLE `funding_types`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `type` (`type`);

--
-- Indexes for table `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `personal_access_tokens`
--
ALTER TABLE `personal_access_tokens`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `personal_access_tokens_token_unique` (`token`),
  ADD KEY `personal_access_tokens_tokenable_type_tokenable_id_index` (`tokenable_type`,`tokenable_id`);

--
-- Indexes for table `programs`
--
ALTER TABLE `programs`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `program_research`
--
ALTER TABLE `program_research`
  ADD PRIMARY KEY (`id`),
  ADD KEY `program_research_research_id_foreign` (`research_id`),
  ADD KEY `program_research_program_id_foreign` (`program_id`);

--
-- Indexes for table `program_researcher`
--
ALTER TABLE `program_researcher`
  ADD PRIMARY KEY (`id`),
  ADD KEY `program_researcher_program_id_foreign` (`program_id`),
  ADD KEY `program_researcher_researcher_id_foreign` (`researcher_id`);

--
-- Indexes for table `researchers`
--
ALTER TABLE `researchers`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `researchers_email_unique` (`email`);

--
-- Indexes for table `researcher_research`
--
ALTER TABLE `researcher_research`
  ADD PRIMARY KEY (`id`),
  ADD KEY `researcher_research_research_id_foreign` (`research_id`),
  ADD KEY `researcher_research_researcher_id_foreign` (`researcher_id`);

--
-- Indexes for table `researches`
--
ALTER TABLE `researches`
  ADD PRIMARY KEY (`id`),
  ADD KEY `researches_leader_id_foreign` (`leader_id`),
  ADD KEY `researches_funding_type_id_foreign` (`funding_type_id`),
  ADD KEY `researches_school_year_id_foreign` (`school_year_id`);

--
-- Indexes for table `research_dost_6p`
--
ALTER TABLE `research_dost_6p`
  ADD PRIMARY KEY (`id`),
  ADD KEY `research_dost_6p_research_id_foreign` (`research_id`),
  ADD KEY `research_dost_6p_dost_6p_id_foreign` (`dost_6p_id`);

--
-- Indexes for table `research_files`
--
ALTER TABLE `research_files`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `research_sdg`
--
ALTER TABLE `research_sdg`
  ADD PRIMARY KEY (`id`),
  ADD KEY `research_sdg_research_id_foreign` (`research_id`),
  ADD KEY `research_sdg_sdg_id_foreign` (`sdg_id`);

--
-- Indexes for table `research_staff`
--
ALTER TABLE `research_staff`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `research_staff_username_unique` (`username`),
  ADD UNIQUE KEY `research_staff_email_unique` (`email`);

--
-- Indexes for table `school_years`
--
ALTER TABLE `school_years`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `sdgs`
--
ALTER TABLE `sdgs`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `sdgs_name_unique` (`name`),
  ADD UNIQUE KEY `sdgs_goal_number_unique` (`goal_number`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `academic_administrators`
--
ALTER TABLE `academic_administrators`
  MODIFY `id` bigint(2) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `dost_6ps`
--
ALTER TABLE `dost_6ps`
  MODIFY `id` bigint(1) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `funding_types`
--
ALTER TABLE `funding_types`
  MODIFY `id` bigint(1) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=42;

--
-- AUTO_INCREMENT for table `personal_access_tokens`
--
ALTER TABLE `personal_access_tokens`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `programs`
--
ALTER TABLE `programs`
  MODIFY `id` bigint(2) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `program_research`
--
ALTER TABLE `program_research`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `program_researcher`
--
ALTER TABLE `program_researcher`
  MODIFY `id` bigint(5) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=38;

--
-- AUTO_INCREMENT for table `researchers`
--
ALTER TABLE `researchers`
  MODIFY `id` bigint(2) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- AUTO_INCREMENT for table `researcher_research`
--
ALTER TABLE `researcher_research`
  MODIFY `id` bigint(5) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `researches`
--
ALTER TABLE `researches`
  MODIFY `id` bigint(2) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `research_dost_6p`
--
ALTER TABLE `research_dost_6p`
  MODIFY `id` bigint(5) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `research_files`
--
ALTER TABLE `research_files`
  MODIFY `id` bigint(2) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `research_sdg`
--
ALTER TABLE `research_sdg`
  MODIFY `id` bigint(5) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `research_staff`
--
ALTER TABLE `research_staff`
  MODIFY `id` bigint(2) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `school_years`
--
ALTER TABLE `school_years`
  MODIFY `id` bigint(2) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `sdgs`
--
ALTER TABLE `sdgs`
  MODIFY `id` bigint(2) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `program_research`
--
ALTER TABLE `program_research`
  ADD CONSTRAINT `program_research_program_id_foreign` FOREIGN KEY (`program_id`) REFERENCES `programs` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `program_research_research_id_foreign` FOREIGN KEY (`research_id`) REFERENCES `researches` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `program_researcher`
--
ALTER TABLE `program_researcher`
  ADD CONSTRAINT `program_researcher_program_id_foreign` FOREIGN KEY (`program_id`) REFERENCES `programs` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `program_researcher_researcher_id_foreign` FOREIGN KEY (`researcher_id`) REFERENCES `researchers` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `researcher_research`
--
ALTER TABLE `researcher_research`
  ADD CONSTRAINT `researcher_research_ibfk_1` FOREIGN KEY (`researcher_id`) REFERENCES `researchers` (`id`),
  ADD CONSTRAINT `researcher_research_ibfk_2` FOREIGN KEY (`research_id`) REFERENCES `researches` (`id`);

--
-- Constraints for table `researches`
--
ALTER TABLE `researches`
  ADD CONSTRAINT `researches_funding_type_id_foreign` FOREIGN KEY (`funding_type_id`) REFERENCES `funding_types` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `researches_leader_id_foreign` FOREIGN KEY (`leader_id`) REFERENCES `researchers` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `researches_school_year_id_foreign` FOREIGN KEY (`school_year_id`) REFERENCES `school_years` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `research_dost_6p`
--
ALTER TABLE `research_dost_6p`
  ADD CONSTRAINT `research_dost_6p_ibfk_1` FOREIGN KEY (`research_id`) REFERENCES `researches` (`id`),
  ADD CONSTRAINT `research_dost_6p_ibfk_2` FOREIGN KEY (`dost_6p_id`) REFERENCES `dost_6ps` (`id`);

--
-- Constraints for table `research_sdg`
--
ALTER TABLE `research_sdg`
  ADD CONSTRAINT `research_sdg_ibfk_1` FOREIGN KEY (`sdg_id`) REFERENCES `sdgs` (`id`),
  ADD CONSTRAINT `research_sdg_ibfk_2` FOREIGN KEY (`research_id`) REFERENCES `researches` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
