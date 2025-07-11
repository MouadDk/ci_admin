-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jul 10, 2025 at 06:45 PM
-- Server version: 10.4.25-MariaDB
-- PHP Version: 7.4.30

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `db_mpe`
--

-- --------------------------------------------------------

--
-- Table structure for table `admins`
--

CREATE TABLE `admins` (
  `id` int(11) NOT NULL,
  `username` varchar(150) NOT NULL COMMENT 'The username for admin login.',
  `password` varchar(255) NOT NULL COMMENT 'Hashed password for the admin platform.'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='Stores administrator login credentials.';

--
-- Dumping data for table `admins`
--

INSERT INTO `admins` (`id`, `username`, `password`) VALUES
(1, 'admin1', '$2y$10$XSh67ffWJjhGZiUFmuu.Leu1V5RgcjE6.B2Y4oVHkicF5ylR4Jimm'),
(2, 'admin2', '$2y$10$XSh67ffWJjhGZiUFmuu.Leu1V5RgcjE6.B2Y4oVHkicF5ylR4Jimm');

-- --------------------------------------------------------

--
-- Table structure for table `clients`
--

CREATE TABLE `clients` (
  `id` int(11) NOT NULL,
  `etablissement` enum('Hotel','Restaurant','Agence de voyage','Transport touristique') NOT NULL,
  `nom_etablissement` varchar(255) DEFAULT NULL,
  `ice` varchar(20) NOT NULL COMMENT 'Identifiant Commun de l''Entreprise (must be unique).',
  `type` enum('Physique','Moral') NOT NULL COMMENT 'The legal type of the client entity.',
  `provenance` enum('FMS','Externe') NOT NULL DEFAULT 'FMS' COMMENT 'Client source: FMS (direct) or Externe (referral)',
  `ville` varchar(100) NOT NULL,
  `region` varchar(100) NOT NULL COMMENT 'The administrative region of the client.',
  `responsable` varchar(100) NOT NULL,
  `telephone1` varchar(20) NOT NULL,
  `telephone2` varchar(20) DEFAULT NULL,
  `referal` varchar(255) DEFAULT NULL COMMENT 'The person or entity who referred an Externe client',
  `is_active` tinyint(1) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `clients`
--

INSERT INTO `clients` (`id`, `etablissement`, `nom_etablissement`, `ice`, `type`, `provenance`, `ville`, `region`, `responsable`, `telephone1`, `telephone2`, `referal`, `is_active`) VALUES
(1, 'Hotel', 'Riad Al Mounia', '001234501000011', 'Moral', '', 'Marrakech', 'Marrakesh-Safi', 'Karim Alami', '0524887766', '0661234567', NULL, 1),
(2, 'Transport touristique', 'Casa Trans Express', '001234502000022', 'Moral', '', 'Casablanca', 'Casablanca-Settat', 'Fatima Bennis', '0522998877', NULL, NULL, 1),
(3, 'Agence de voyage', 'Sahara Discovery Tours', '001234503000033', 'Moral', 'Externe', 'Ouarzazate', 'Drâa-Tafilalet', 'Omar Cherkaoui', '0524885544', '0668112233', NULL, 1),
(7, 'Restaurant', 'Le Palais Bleu', '001234507000077', 'Moral', '', 'Agadir', 'Souss-Massa', 'Imane Chaqroun', '0528321100', '0667788990', NULL, 1),
(8, 'Hotel', 'Ocean View Resort', '001234508000088', 'Moral', 'Externe', 'Dakhla', 'Dakhla-Oued Ed Dahab', 'Yassine Laâroussi', '0528812345', NULL, NULL, 1),
(9, 'Transport touristique', 'Atlas Desert Safaris', '001234509000099', 'Moral', '', 'Errachidia', 'Drâa-Tafilalet', 'Khalid Bensaïd', '0534233333', '0664455566', NULL, 1),
(10, 'Agence de voyage', 'Rabat City Tours', '001234510000010', 'Physique', 'Externe', 'Rabat', 'Rabat-Salé-Kénitra', 'Meriem Zahraoui', '0537800011', '0677123456', NULL, 1),
(11, 'Hotel', 'Dar Zitoune', '001234511000011', 'Moral', 'FMS', 'Fes', 'Marrakesh-Safi', 'Hassan Oumaz', '0524876543', '', '', 1),
(12, 'Restaurant', 'Café des Oudayas', '001234512000012', 'Physique', 'Externe', 'Salé', 'Rabat-Salé-Kénitra', 'Noura El Idrissi', '0537733445', '0662345678', NULL, 1),
(13, 'Agence de voyage', 'Merzouga Camel Trekking', '001234513000013', 'Moral', '', 'Merzouga', 'Drâa-Tafilalet', 'Ahmed Bouziane', '0534656777', NULL, NULL, 1),
(14, 'Transport touristique', 'Casablanca City Shuttle', '001234514000014', 'Physique', 'Externe', 'Casablanca', 'Casablanca-Settat', 'Sara Belkacem', '0522991122', '0663344556', NULL, 1),
(15, 'Restaurant', 'Le Jardin Secret', '001234515000015', 'Moral', '', 'Marrakech', 'Marrakesh-Safi', 'Rachid El Farissi', '0524889001', NULL, NULL, 1),
(16, 'Hotel', 'Palais Jad Mahal', '001234516000016', 'Moral', 'Externe', 'Marrakech', 'Marrakesh-Safi', 'Fatima Zahraoui', '0524881122', '0665566778', NULL, 1);

-- --------------------------------------------------------

--
-- Table structure for table `dossiers`
--

CREATE TABLE `dossiers` (
  `id` int(11) NOT NULL,
  `client_id` int(11) NOT NULL COMMENT 'Foreign key to the client this dossier belongs to.',
  `name` varchar(255) NOT NULL COMMENT 'A descriptive name for the dossier.',
  `status` enum('valide','non valide') NOT NULL DEFAULT 'non valide' COMMENT 'The validation status of the dossier.',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `type` varchar(100) NOT NULL DEFAULT 'default_dossier_type'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='Stores dossier information, which act as containers for files.';

--
-- Dumping data for table `dossiers`
--

INSERT INTO `dossiers` (`id`, `client_id`, `name`, `status`, `created_at`, `updated_at`, `type`) VALUES
(1, 1, 'Dossier 2025 - Riad Al Mounia', 'non valide', '2025-07-09 15:19:29', '2025-07-09 15:19:29', 'default_dossier_type'),
(2, 2, 'Dossier 2025 - Casa Trans Express', 'non valide', '2025-07-09 15:19:29', '2025-07-10 16:34:40', 'default_dossier_type'),
(3, 3, 'Dossier 2025 - Sahara Discovery Tours', 'non valide', '2025-07-09 15:19:29', '2025-07-09 15:19:29', 'default_dossier_type'),
(4, 16, 'Client Documents', 'non valide', '2025-07-09 16:30:25', '2025-07-10 16:29:13', 'default_dossier_type'),
(11, 15, 'Client Documents', 'non valide', '2025-07-10 16:27:50', '2025-07-10 16:27:50', 'default_dossier_type');

-- --------------------------------------------------------

--
-- Table structure for table `files`
--

CREATE TABLE `files` (
  `id` int(11) NOT NULL,
  `sub_dossier_id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL COMMENT 'File or folder name.',
  `document_type` varchar(100) DEFAULT NULL,
  `path` varchar(1024) NOT NULL COMMENT 'The full path to the file on the server.',
  `file_type` varchar(100) DEFAULT NULL COMMENT 'MIME type of the file (e.g., "application/pdf").',
  `file_size` bigint(20) DEFAULT NULL COMMENT 'File size in bytes.',
  `uploaded_by_admin_id` int(11) DEFAULT NULL COMMENT 'FK to admins table if an admin uploaded it.',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='Stores all files and folders, organized within dossiers.';

--
-- Dumping data for table `files`
--

INSERT INTO `files` (`id`, `sub_dossier_id`, `name`, `document_type`, `path`, `file_type`, `file_size`, `uploaded_by_admin_id`, `created_at`, `updated_at`) VALUES
(25, 2, 'Rapport', 'rapport', 'uploads\\dossiers\\palais-jad-mahal\\dossier_2\\94672c7a7f8d8ddff7be32c2a751c17c.sql', 'text/plain', 12, 1, '2025-07-10 15:46:42', '2025-07-10 15:13:52');

-- --------------------------------------------------------

--
-- Table structure for table `sub_dossiers`
--

CREATE TABLE `sub_dossiers` (
  `id` int(11) NOT NULL,
  `dossier_id` int(11) NOT NULL COMMENT 'Link to the parent dossier.',
  `sub_dossier_type` int(1) NOT NULL COMMENT 'Type of sub-dossier (1, 2, or 3).',
  `status` enum('valide','non valide') NOT NULL DEFAULT 'non valide' COMMENT 'Simple validation status of this specific sub-dossier.',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='Represents the three sequential sub-dossiers.';

--
-- Dumping data for table `sub_dossiers`
--

INSERT INTO `sub_dossiers` (`id`, `dossier_id`, `sub_dossier_type`, `status`, `created_at`, `updated_at`) VALUES
(1, 4, 1, 'non valide', '2025-07-10 15:13:51', '2025-07-10 16:11:44'),
(2, 4, 2, 'non valide', '2025-07-10 15:13:51', '2025-07-10 15:15:21'),
(3, 4, 3, 'non valide', '2025-07-10 16:27:12', '2025-07-10 16:27:12'),
(4, 11, 1, 'non valide', '2025-07-10 16:27:50', '2025-07-10 16:27:50'),
(5, 11, 2, 'non valide', '2025-07-10 16:27:50', '2025-07-10 16:27:50'),
(6, 11, 3, 'non valide', '2025-07-10 16:27:50', '2025-07-10 16:27:50');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `client_id` int(11) DEFAULT NULL COMMENT 'Foreign key linking to the clients table.',
  `gmailPME` varchar(150) NOT NULL COMMENT 'The PME email, used for login.',
  `password_pme` varchar(255) NOT NULL,
  `password_gmail` varchar(255) NOT NULL COMMENT 'ENCRYPTED password for the GMAIL account.'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `client_id`, `gmailPME`, `password_pme`, `password_gmail`) VALUES
(1, 1, 'riad.mounia@mpe.com', 'pme_pass_123', 'gmail_pass_123'),
(2, 2, 'casa.trans@mpe.com', 'pme_pass_123', 'gmail_pass_123'),
(3, 3, 'sahara.tours@mpe.com', 'pme_pass_123', 'gmail_pass_123'),
(7, 7, 'palais.bleu@mpe.com', 'pme_pass_101', 'gmail_pass_101'),
(8, 8, 'ocean.view@mpe.com', 'pme_pass_202', 'gmail_pass_202'),
(9, 9, 'atlas.desert@mpe.com', 'pme_pass_303', 'gmail_pass_303'),
(10, 10, 'rabat.tours@mpe.com', 'pme_pass_404', 'gmail_pass_404'),
(11, 11, 'dar.zitoune@mpe.com', 'pme_pass_505', 'gmail_pass_505'),
(12, 12, 'cafe.oudayas@mpe.com', 'pme_pass_606', 'gmail_pass_606'),
(13, 13, 'merzouga.camel@mpe.com', 'pme_pass_707', 'gmail_pass_707'),
(14, 14, 'casashuttle@mpe.com', 'pme_pass_808', 'gmail_pass_808'),
(15, 15, 'jardin.secret@mpe.com', 'pme_pass_909', 'gmail_pass_909'),
(16, 16, 'palais.jad@mpe.com', 'pme_pass_112', 'gmail_pass_112');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `admins`
--
ALTER TABLE `admins`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `unique_username` (`username`);

--
-- Indexes for table `clients`
--
ALTER TABLE `clients`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `unique_ice` (`ice`);

--
-- Indexes for table `dossiers`
--
ALTER TABLE `dossiers`
  ADD PRIMARY KEY (`id`),
  ADD KEY `client_id` (`client_id`);

--
-- Indexes for table `files`
--
ALTER TABLE `files`
  ADD PRIMARY KEY (`id`),
  ADD KEY `uploaded_by_admin_id` (`uploaded_by_admin_id`),
  ADD KEY `fk_file_sub_dossier` (`sub_dossier_id`);

--
-- Indexes for table `sub_dossiers`
--
ALTER TABLE `sub_dossiers`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `unique_dossier_type` (`dossier_id`,`sub_dossier_type`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `unique_gmailPME` (`gmailPME`),
  ADD UNIQUE KEY `unique_client_id` (`client_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `admins`
--
ALTER TABLE `admins`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `clients`
--
ALTER TABLE `clients`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT for table `dossiers`
--
ALTER TABLE `dossiers`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `files`
--
ALTER TABLE `files`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=26;

--
-- AUTO_INCREMENT for table `sub_dossiers`
--
ALTER TABLE `sub_dossiers`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `dossiers`
--
ALTER TABLE `dossiers`
  ADD CONSTRAINT `fk_dossier_client` FOREIGN KEY (`client_id`) REFERENCES `clients` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `files`
--
ALTER TABLE `files`
  ADD CONSTRAINT `fk_file_admin_uploader` FOREIGN KEY (`uploaded_by_admin_id`) REFERENCES `admins` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_file_sub_dossier` FOREIGN KEY (`sub_dossier_id`) REFERENCES `sub_dossiers` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `sub_dossiers`
--
ALTER TABLE `sub_dossiers`
  ADD CONSTRAINT `fk_sub_dossier_parent` FOREIGN KEY (`dossier_id`) REFERENCES `dossiers` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `users`
--
ALTER TABLE `users`
  ADD CONSTRAINT `fk_user_to_client` FOREIGN KEY (`client_id`) REFERENCES `clients` (`id`) ON DELETE SET NULL ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
