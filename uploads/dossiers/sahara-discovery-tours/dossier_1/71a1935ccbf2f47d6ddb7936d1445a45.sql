-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Hôte : 127.0.0.1
-- Généré le : jeu. 10 juil. 2025 à 16:53
-- Version du serveur : 10.4.27-MariaDB
-- Version de PHP : 7.4.33

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données : `db_mpe`
--

-- --------------------------------------------------------

--
-- Structure de la table `admins`
--

CREATE TABLE `admins` (
  `id` int(11) NOT NULL,
  `username` varchar(150) NOT NULL COMMENT 'The username for admin login.',
  `password` varchar(255) NOT NULL COMMENT 'Hashed password for the admin platform.'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci COMMENT='Stores administrator login credentials.';

--
-- Déchargement des données de la table `admins`
--

INSERT INTO `admins` (`id`, `username`, `password`) VALUES
(1, 'admin1', '$2y$10$XSh67ffWJjhGZiUFmuu.Leu1V5RgcjE6.B2Y4oVHkicF5ylR4Jimm'),
(2, 'admin2', '$2y$10$XSh67ffWJjhGZiUFmuu.Leu1V5RgcjE6.B2Y4oVHkicF5ylR4Jimm');

-- --------------------------------------------------------

--
-- Structure de la table `clients`
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
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `clients`
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
-- Structure de la table `dossiers`
--

CREATE TABLE `dossiers` (
  `id` int(11) NOT NULL,
  `client_id` int(11) NOT NULL COMMENT 'Foreign key to the client this dossier belongs to.',
  `name` varchar(255) NOT NULL COMMENT 'A descriptive name for the dossier.',
  `status` enum('valide','non valide') NOT NULL DEFAULT 'non valide' COMMENT 'The validation status of the dossier.',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `type` varchar(100) NOT NULL DEFAULT 'default_dossier_type'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci COMMENT='Stores dossier information, which act as containers for files.';

--
-- Déchargement des données de la table `dossiers`
--

INSERT INTO `dossiers` (`id`, `client_id`, `name`, `status`, `created_at`, `updated_at`, `type`) VALUES
(1, 1, 'Dossier 2025 - Riad Al Mounia', 'non valide', '2025-07-09 15:19:29', '2025-07-09 15:19:29', 'default_dossier_type'),
(2, 2, 'Dossier 2025 - Casa Trans Express', 'valide', '2025-07-09 15:19:29', '2025-07-09 15:19:29', 'default_dossier_type'),
(3, 3, 'Dossier 2025 - Sahara Discovery Tours', 'non valide', '2025-07-09 15:19:29', '2025-07-09 15:19:29', 'default_dossier_type'),
(4, 16, 'Client Documents', 'non valide', '2025-07-09 16:30:25', '2025-07-09 15:34:27', 'default_dossier_type');

-- --------------------------------------------------------

--
-- Structure de la table `dossier_1`
--

CREATE TABLE `dossier_1` (
  `id` int(11) NOT NULL,
  `dossier_id` int(11) NOT NULL,
  `file_id` int(11) NOT NULL,
  `created_at` datetime DEFAULT current_timestamp(),
  `updated_at` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `dossier_1`
--

INSERT INTO `dossier_1` (`id`, `dossier_id`, `file_id`, `created_at`, `updated_at`) VALUES
(1, 4, 24, '2025-07-10 16:41:31', '2025-07-10 16:41:31');

-- --------------------------------------------------------

--
-- Structure de la table `dossier_2`
--

CREATE TABLE `dossier_2` (
  `id` int(11) NOT NULL,
  `dossier_id` int(11) NOT NULL,
  `file_id` int(11) NOT NULL,
  `created_at` datetime DEFAULT current_timestamp(),
  `updated_at` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `dossier_2`
--

INSERT INTO `dossier_2` (`id`, `dossier_id`, `file_id`, `created_at`, `updated_at`) VALUES
(1, 4, 25, '2025-07-10 16:46:42', '2025-07-10 16:46:42');

-- --------------------------------------------------------

--
-- Structure de la table `dossier_3`
--

CREATE TABLE `dossier_3` (
  `id` int(11) NOT NULL,
  `dossier_id` int(11) NOT NULL,
  `file_id` int(11) NOT NULL,
  `created_at` datetime DEFAULT current_timestamp(),
  `updated_at` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Structure de la table `files`
--

CREATE TABLE `files` (
  `id` int(11) NOT NULL,
  `dossier_id` int(11) NOT NULL COMMENT 'Foreign key linking the file to its parent dossier.',
  `parent_id` int(11) DEFAULT NULL COMMENT 'For hierarchical structure. Links to a folder within the same dossier.',
  `name` varchar(255) NOT NULL COMMENT 'File or folder name.',
  `type` enum('file','folder') NOT NULL,
  `document_type` varchar(100) DEFAULT NULL,
  `path` varchar(1024) NOT NULL COMMENT 'The full path to the file on the server.',
  `file_type` varchar(100) DEFAULT NULL COMMENT 'MIME type of the file (e.g., "application/pdf").',
  `file_size` bigint(20) DEFAULT NULL COMMENT 'File size in bytes.',
  `uploaded_by_admin_id` int(11) DEFAULT NULL COMMENT 'FK to admins table if an admin uploaded it.',
  `uploaded_by_user_id` int(11) DEFAULT NULL COMMENT 'FK to users table if a client uploaded it.',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci COMMENT='Stores all files and folders, organized within dossiers.';

--
-- Déchargement des données de la table `files`
--

INSERT INTO `files` (`id`, `dossier_id`, `parent_id`, `name`, `type`, `document_type`, `path`, `file_type`, `file_size`, `uploaded_by_admin_id`, `uploaded_by_user_id`, `created_at`, `updated_at`) VALUES
(24, 4, NULL, 'Chiffre d\'Affaire', 'file', 'chiffre_affaire', 'uploads\\dossiers\\palais-jad-mahal\\dossier_1\\88bb3cc6cd209116d18c617ab06f371a.sql', 'text/plain', 12, 1, NULL, '2025-07-10 15:41:31', '2025-07-10 15:41:31'),
(25, 4, NULL, 'Rapport', 'file', 'rapport', 'uploads\\dossiers\\palais-jad-mahal\\dossier_2\\94672c7a7f8d8ddff7be32c2a751c17c.sql', 'text/plain', 12, 1, NULL, '2025-07-10 15:46:42', '2025-07-10 15:46:42');

-- --------------------------------------------------------

--
-- Structure de la table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `client_id` int(11) DEFAULT NULL COMMENT 'Foreign key linking to the clients table.',
  `gmailPME` varchar(150) NOT NULL COMMENT 'The PME email, used for login.',
  `password_pme` varchar(255) NOT NULL,
  `password_gmail` varchar(255) NOT NULL COMMENT 'ENCRYPTED password for the GMAIL account.'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `users`
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
-- Index pour les tables déchargées
--

--
-- Index pour la table `admins`
--
ALTER TABLE `admins`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `unique_username` (`username`);

--
-- Index pour la table `clients`
--
ALTER TABLE `clients`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `unique_ice` (`ice`);

--
-- Index pour la table `dossiers`
--
ALTER TABLE `dossiers`
  ADD PRIMARY KEY (`id`),
  ADD KEY `client_id` (`client_id`);

--
-- Index pour la table `dossier_1`
--
ALTER TABLE `dossier_1`
  ADD PRIMARY KEY (`id`),
  ADD KEY `dossier_id` (`dossier_id`),
  ADD KEY `file_id` (`file_id`);

--
-- Index pour la table `dossier_2`
--
ALTER TABLE `dossier_2`
  ADD PRIMARY KEY (`id`),
  ADD KEY `dossier_id` (`dossier_id`),
  ADD KEY `file_id` (`file_id`);

--
-- Index pour la table `dossier_3`
--
ALTER TABLE `dossier_3`
  ADD PRIMARY KEY (`id`),
  ADD KEY `dossier_id` (`dossier_id`),
  ADD KEY `file_id` (`file_id`);

--
-- Index pour la table `files`
--
ALTER TABLE `files`
  ADD PRIMARY KEY (`id`),
  ADD KEY `dossier_id` (`dossier_id`),
  ADD KEY `parent_id` (`parent_id`),
  ADD KEY `uploaded_by_admin_id` (`uploaded_by_admin_id`),
  ADD KEY `uploaded_by_user_id` (`uploaded_by_user_id`);

--
-- Index pour la table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `unique_gmailPME` (`gmailPME`),
  ADD UNIQUE KEY `unique_client_id` (`client_id`);

--
-- AUTO_INCREMENT pour les tables déchargées
--

--
-- AUTO_INCREMENT pour la table `admins`
--
ALTER TABLE `admins`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT pour la table `clients`
--
ALTER TABLE `clients`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT pour la table `dossiers`
--
ALTER TABLE `dossiers`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT pour la table `dossier_1`
--
ALTER TABLE `dossier_1`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT pour la table `dossier_2`
--
ALTER TABLE `dossier_2`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT pour la table `dossier_3`
--
ALTER TABLE `dossier_3`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `files`
--
ALTER TABLE `files`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=26;

--
-- AUTO_INCREMENT pour la table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- Contraintes pour les tables déchargées
--

--
-- Contraintes pour la table `dossiers`
--
ALTER TABLE `dossiers`
  ADD CONSTRAINT `fk_dossier_client` FOREIGN KEY (`client_id`) REFERENCES `clients` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Contraintes pour la table `dossier_1`
--
ALTER TABLE `dossier_1`
  ADD CONSTRAINT `dossier_1_ibfk_1` FOREIGN KEY (`dossier_id`) REFERENCES `dossiers` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `dossier_1_ibfk_2` FOREIGN KEY (`file_id`) REFERENCES `files` (`id`) ON DELETE CASCADE;

--
-- Contraintes pour la table `dossier_2`
--
ALTER TABLE `dossier_2`
  ADD CONSTRAINT `dossier_2_ibfk_1` FOREIGN KEY (`dossier_id`) REFERENCES `dossiers` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `dossier_2_ibfk_2` FOREIGN KEY (`file_id`) REFERENCES `files` (`id`) ON DELETE CASCADE;

--
-- Contraintes pour la table `dossier_3`
--
ALTER TABLE `dossier_3`
  ADD CONSTRAINT `dossier_3_ibfk_1` FOREIGN KEY (`dossier_id`) REFERENCES `dossiers` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `dossier_3_ibfk_2` FOREIGN KEY (`file_id`) REFERENCES `files` (`id`) ON DELETE CASCADE;

--
-- Contraintes pour la table `files`
--
ALTER TABLE `files`
  ADD CONSTRAINT `fk_file_admin_uploader` FOREIGN KEY (`uploaded_by_admin_id`) REFERENCES `admins` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_file_dossier` FOREIGN KEY (`dossier_id`) REFERENCES `dossiers` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_file_parent` FOREIGN KEY (`parent_id`) REFERENCES `files` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_file_user_uploader` FOREIGN KEY (`uploaded_by_user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL ON UPDATE CASCADE;

--
-- Contraintes pour la table `users`
--
ALTER TABLE `users`
  ADD CONSTRAINT `fk_user_to_client` FOREIGN KEY (`client_id`) REFERENCES `clients` (`id`) ON DELETE SET NULL ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
