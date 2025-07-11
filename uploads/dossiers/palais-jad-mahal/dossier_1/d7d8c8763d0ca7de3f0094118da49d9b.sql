-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jul 04, 2025 at 05:42 PM
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
  `username` varchar(150) NOT NULL COMMENT 'The username for admin login (e.g., "admin" or "admin@mpe.com").',
  `password` varchar(255) NOT NULL COMMENT 'Hashed password for the admin platform.'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='Stores administrator login credentials.';

--
-- Dumping data for table `admins`
--

INSERT INTO `admins` (`id`, `username`, `password`) VALUES
(1, 'admin1', '$2y$10$U9.qDR9RgEkdIogXdFLWce/dn22VWSwjj3w5.A8nZS4S5VH6rDTG6'),
(3, 'admin2', '$2y$10$vRn7DHdt1vhDJDHzwkhSeOhd73Fwy6wyDRhlyLkm7oE7WxOhuoHYW');

-- --------------------------------------------------------

--
-- Table structure for table `clients`
--

CREATE TABLE `clients` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL COMMENT 'Foreign key linking to the users table.',
  `etablissement` enum('Hotel','Restaurant','Agence de voyage','Transport touristique') NOT NULL,
  `ville` varchar(100) NOT NULL,
  `responsable` varchar(100) NOT NULL,
  `telephone1` varchar(20) NOT NULL,
  `telephone2` varchar(20) DEFAULT NULL,
  `nom_etablissement` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `clients`
--

INSERT INTO `clients` (`id`, `user_id`, `etablissement`, `ville`, `responsable`, `telephone1`, `telephone2`, `nom_etablissement`) VALUES
(1, 2, 'Hotel', 'Rabat', 'John Doe', '0600000001', '', 'etablissement1'),
(3, 9, 'Agence de voyage', 'Marrakech', 'Amina Mansouri', '0661123456', NULL, 'Marrakech Adventures'),
(22, 11, 'Hotel', 'Marrakech', 'Karim Alami', '0524887766', '0661234567', 'Riad Al Mounia'),
(23, 12, 'Transport touristique', 'Casablanca', 'Fatima Bennis', '0522998877', NULL, 'Casa Trans Express'),
(24, 13, 'Agence de voyage', 'Ouarzazate', 'Omar Cherkaoui', '0524885544', '0668112233', 'Sahara Discovery Tours'),
(25, 14, 'Restaurant', 'Fes', 'Salma Alaoui', '0535636363', NULL, 'Le Gourmet de Fès'),
(26, 15, 'Hotel', 'Agadir', 'Youssef Tazi', '0528223344', '0661445566', 'Hotel Atlas Marina'),
(27, 16, 'Agence de voyage', 'Tangier', 'Nadia Saadi', '0539334455', NULL, 'Tangier City Travels'),
(28, 17, 'Transport touristique', 'Chefchaouen', 'Mohamed Benjelloun', '0650102030', NULL, 'Blue Pearl Express'),
(29, 18, 'Restaurant', 'Marrakech', 'Leila Drissi', '0524430910', '0610203040', 'Le Marocain - La Mamounia'),
(30, 19, 'Hotel', 'Meknes', 'Hassan Ziani', '0535556677', NULL, 'Zaki Suites Hotel & Spa'),
(31, 20, 'Agence de voyage', 'Dakhla', 'Khadija Bouzidi', '0662334455', NULL, 'Dakhla Attitude Voyages'),
(32, 21, 'Restaurant', 'Essaouira', 'Ali Mansouri', '0524784638', '0677889900', 'La Table by Madada'),
(33, 22, 'Transport touristique', 'Rabat', 'Samira Guessous', '0537778899', NULL, 'Royal Transport Rabat'),
(34, 23, 'Hotel', 'Merzouga', 'Said El Fassi', '0661112233', '0662223344', 'Oasis Luxury Camp'),
(35, 24, 'Agence de voyage', 'Fes', 'Yasmine Chraibi', '0535940332', NULL, 'Voyages Impériaux Fès'),
(36, 25, 'Restaurant', 'Casablanca', 'Adil Lahlou', '0522260960', NULL, 'Restaurant La Sqala'),
(37, 26, 'Transport touristique', 'Ifrane', 'Mehdi Filali', '0663556677', '0535862086', 'Ifrane Prestige Cars'),
(38, 27, 'Hotel', 'Oujda', 'Sofia Belhaj', '0536681012', '', 'Atlas Terminus & Spa Oujda');

-- --------------------------------------------------------

--
-- Table structure for table `dossiers`
--

CREATE TABLE `dossiers` (
  `id` int(11) NOT NULL,
  `client_id` int(11) NOT NULL COMMENT 'Foreign key to the client this dossier belongs to.',
  `name` varchar(255) NOT NULL COMMENT 'A descriptive name for the dossier (e.g., "Initial Application 2025").',
  `status` enum('Pending','In Review','Approved','Rejected') NOT NULL DEFAULT 'Pending' COMMENT 'The overall status of the dossier.',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='Stores dossier information, which act as containers for files.';

--
-- Dumping data for table `dossiers`
--

INSERT INTO `dossiers` (`id`, `client_id`, `name`, `status`, `created_at`, `updated_at`) VALUES
(1, 38, 'Client Documents', 'Pending', '2025-07-04 16:27:10', '2025-07-04 16:27:10');

-- --------------------------------------------------------

--
-- Table structure for table `files`
--

CREATE TABLE `files` (
  `id` int(11) NOT NULL,
  `dossier_id` int(11) NOT NULL COMMENT 'Foreign key linking the file to its parent dossier.',
  `parent_id` int(11) DEFAULT NULL COMMENT 'For hierarchical structure. Links to a folder within the same dossier.',
  `name` varchar(255) NOT NULL COMMENT 'File or folder name.',
  `type` enum('file','folder') NOT NULL,
  `path` varchar(1024) NOT NULL COMMENT 'The full path to the file on the server.',
  `file_type` varchar(100) DEFAULT NULL COMMENT 'MIME type of the file (e.g., "application/pdf").',
  `file_size` bigint(20) DEFAULT NULL COMMENT 'File size in bytes.',
  `uploaded_by_admin_id` int(11) DEFAULT NULL COMMENT 'FK to admins table if an admin uploaded it.',
  `uploaded_by_user_id` int(11) DEFAULT NULL COMMENT 'FK to users table if a client uploaded it.',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='Stores all files and folders, organized within dossiers.';

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `gmailPME` varchar(150) NOT NULL COMMENT 'The PME email, used for login.',
  `password_pme` varchar(255) NOT NULL,
  `password_gmail` varchar(255) NOT NULL COMMENT 'ENCRYPTED password for the GMAIL account.'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `gmailPME`, `password_pme`, `password_gmail`) VALUES
(2, 'pme.grandhotel@gmail.com', 'pmePass_HotelRabat', 'gmailPass_JohnDoe'),
(9, 'test@gmail.com', 'pmePass_TestUser', 'gmailPass_Amina'),
(11, 'riad.marrakech@mpe.com', 'pmePass_RiadMounia', 'gmailPass_Karim'),
(12, 'casa.trans@mpe.com', 'pmePass_CasaTrans', 'gmailPass_FatimaB'),
(13, 'sahara.tours@mpe.com', 'pmePass_SaharaTours', 'gmailPass_OmarC'),
(14, 'le.gourmet.fes@mpe.com', 'pmePass_GourmetFes', 'gmailPass_SalmaA'),
(15, 'hotel.atlas.agadir@mpe.com', 'pmePass_AtlasAgadir', 'gmailPass_YoussefT'),
(16, 'tangier.travels@mpe.com', 'pmePass_TangierTravels', 'gmailPass_NadiaS'),
(17, 'chefchaouen.express@mpe.com', 'pmePass_ChefchaouenEx', 'gmailPass_MohamedB'),
(18, 'la.mamounia.resto@mpe.com', 'pmePass_MamouniaResto', 'gmailPass_LeilaD'),
(19, 'meknes.palace@mpe.com', 'pmePass_MeknesPalace', 'gmailPass_HassanZ'),
(20, 'dakhla.adventures@mpe.com', 'pmePass_DakhlaAdv', 'gmailPass_KhadijaB'),
(21, 'essaouira.eats@mpe.com', 'pmePass_EssaouiraEats', 'gmailPass_AliM'),
(22, 'royal.transport.rabat@mpe.com', 'pmePass_RoyalTrans', 'gmailPass_SamiraG'),
(23, 'oasis.hotel.merzouga@mpe.com', 'pmePass_OasisMerzouga', 'gmailPass_SaidF'),
(24, 'voyage.imperial@mpe.com', 'pmePass_VoyageImperial', 'gmailPass_YasmineC'),
(25, 'la.sqala.casablanca@mpe.com', 'pmePass_LaSqala', 'gmailPass_AdilL'),
(26, 'ifrane.prestige.cars@mpe.com', 'pmePass_IfraneCars', 'gmailPass_MehdiF'),
(27, 'oujda.residence@mpe.com', 'pmePass_OujdaRes', 'gmailPass_SofiaB');

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
  ADD UNIQUE KEY `unique_user_id` (`user_id`);

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
  ADD KEY `dossier_id` (`dossier_id`),
  ADD KEY `parent_id` (`parent_id`),
  ADD KEY `uploaded_by_admin_id` (`uploaded_by_admin_id`),
  ADD KEY `uploaded_by_user_id` (`uploaded_by_user_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `unique_gmailPME` (`gmailPME`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `admins`
--
ALTER TABLE `admins`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `clients`
--
ALTER TABLE `clients`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=39;

--
-- AUTO_INCREMENT for table `dossiers`
--
ALTER TABLE `dossiers`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `files`
--
ALTER TABLE `files`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=28;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `clients`
--
ALTER TABLE `clients`
  ADD CONSTRAINT `fk_client_to_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

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
  ADD CONSTRAINT `fk_file_dossier` FOREIGN KEY (`dossier_id`) REFERENCES `dossiers` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_file_parent` FOREIGN KEY (`parent_id`) REFERENCES `files` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_file_user_uploader` FOREIGN KEY (`uploaded_by_user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
