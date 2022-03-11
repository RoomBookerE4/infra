-- phpMyAdmin SQL Dump
-- version 5.1.1
-- https://www.phpmyadmin.net/
--
-- Hôte : 192.168.56.81
-- Généré le : sam. 22 jan. 2022 à 15:09
-- Version du serveur : 10.5.12-MariaDB-0+deb11u1
-- Version de PHP : 8.1.1

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données : `roombooker`
--

-- --------------------------------------------------------

--
-- Structure de la table `Coordinate`
--

CREATE TABLE `roombooker`.`Coordinate` (
  `id` int(11) NOT NULL,
  `x` int(11) NOT NULL,
  `y` int(11) NOT NULL,
  `line` int(11) NOT NULL,
  `idRoom` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Structure de la table `Establishment`
--

CREATE TABLE `roombooker`.`Establishment` (
  `id` int(11) NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `address` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `timeOpen` time NOT NULL,
  `timeClose` time NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `Establishment`
--

INSERT INTO `roombooker`.`Establishment` (`id`, `name`, `address`, `timeOpen`, `timeClose`) VALUES
(1, 'ESEO - Angers', '4 Boulevard Jean Jeanneteau, 49000 Angers', '07:00:00', '18:00:00');

-- --------------------------------------------------------

--
-- Structure de la table `Participant`
--

CREATE TABLE `roombooker`.`Participant` (
  `id` int(11) NOT NULL,
  `isInvitation` tinyint(1) NOT NULL,
  `invitationStatus` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `idReservation` int(11) NOT NULL,
  `idUser` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `Reservation`
--

CREATE TABLE `roombooker`.`Reservation` (
  `id` int(11) NOT NULL,
  `timeStart` datetime NOT NULL,
  `timeEnd` datetime NOT NULL,
  `idRoom` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `Room`
--

CREATE TABLE `roombooker`.`Room` (
  `id` int(11) NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `idNumber` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `timeOpen` time NOT NULL,
  `timeClose` time NOT NULL,
  `isBookable` tinyint(1) NOT NULL,
  `maxTime` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `idEstablishment` int(11) DEFAULT NULL,
  `floor` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `User`
--

CREATE TABLE `roombooker`.`User` (
  `id` int(11) NOT NULL,
  `name` varchar(180) COLLATE utf8mb4_unicode_ci NOT NULL,
  `role` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `password` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `surname` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `establishment_id` int(11) DEFAULT NULL,
  `password_forgotten_at` datetime DEFAULT NULL,
  `reset_token` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `User`
--

INSERT INTO `roombooker`.`User` (`id`, `name`, `role`, `password`, `email`, `surname`, `establishment_id`, `password_forgotten_at`, `reset_token`) VALUES
(1, 'Alexandre', 'student', '$2y$13$Z4Y9aYXGXQf4T2EujHWXKu2lP0ETgsXFnSK8C6Hf9gjBLCQOf1b/G', 'alexandre.halope@reseau.eseo.fr', 'Halopé', 1, NULL, NULL);

--
-- Index pour les tables déchargées
--

--
-- Index pour la table `Coordinate`
--
ALTER TABLE `roombooker`.`Coordinate`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idRoom` (`idRoom`);

--
-- Index pour la table `Establishment`
--
ALTER TABLE `roombooker`.`Establishment`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `Participant`
--
ALTER TABLE `roombooker`.`Participant`
  ADD PRIMARY KEY (`id`),
  ADD KEY `IDX_5103E4C6295B62D` (`idReservation`),
  ADD KEY `IDX_5103E4C6FE6E88D7` (`idUser`),
  ADD KEY `IDX_5103E4C6FE6E88D7295B62D` (`idUser`,`idReservation`);

--
-- Index pour la table `Reservation`
--
ALTER TABLE `roombooker`.`Reservation`
  ADD PRIMARY KEY (`id`),
  ADD KEY `IDX_C454C6821620F05` (`idRoom`);

--
-- Index pour la table `Room`
--
ALTER TABLE `roombooker`.`Room`
  ADD PRIMARY KEY (`id`),
  ADD KEY `IDX_D2ADFEA5110BEDC0` (`idEstablishment`);

--
-- Index pour la table `User`
--
ALTER TABLE `roombooker`.`User`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `UNIQ_2DA17977E7927C74` (`email`),
  ADD KEY `IDX_2DA179778565851` (`establishment_id`);

--
-- AUTO_INCREMENT pour les tables déchargées
--

--
-- AUTO_INCREMENT pour la table `Coordinate`
--
ALTER TABLE `roombooker`.`Coordinate`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `Establishment`
--
ALTER TABLE `roombooker`.`Establishment`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT pour la table `Participant`
--
ALTER TABLE `roombooker`.`Participant`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `Reservation`
--
ALTER TABLE `roombooker`.`Reservation`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `Room`
--
ALTER TABLE `roombooker`.`Room`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `User`
--
ALTER TABLE `roombooker`.`User`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- Contraintes pour les tables déchargées
--

--
-- Contraintes pour la table `Coordinate`
--
ALTER TABLE `roombooker`.`Coordinate`
  ADD CONSTRAINT `Coordinate_ibfk_1` FOREIGN KEY (`idRoom`) REFERENCES `roombooker`.`Room` (`id`);

--
-- Contraintes pour la table `Participant`
--
ALTER TABLE `roombooker`.`Participant`
  ADD CONSTRAINT `FK_5103E4C6295B62D` FOREIGN KEY (`idReservation`) REFERENCES `roombooker`.`Reservation` (`id`),
  ADD CONSTRAINT `FK_5103E4C6FE6E88D7` FOREIGN KEY (`idUser`) REFERENCES `roombooker`.`User` (`id`);

--
-- Contraintes pour la table `Reservation`
--
ALTER TABLE `roombooker`.`Reservation`
  ADD CONSTRAINT `FK_C454C6821620F05` FOREIGN KEY (`idRoom`) REFERENCES `roombooker`.`Room` (`id`);

--
-- Contraintes pour la table `Room`
--
ALTER TABLE `roombooker`.`Room`
  ADD CONSTRAINT `FK_D2ADFEA5110BEDC0` FOREIGN KEY (`idEstablishment`) REFERENCES `roombooker`.`Establishment` (`id`);

--
-- Contraintes pour la table `User`
--
ALTER TABLE `roombooker`.`User`
  ADD CONSTRAINT `FK_2DA179778565851` FOREIGN KEY (`establishment_id`) REFERENCES `roombooker`.`Establishment` (`id`);
COMMIT;

INSERT INTO `roombooker`.`Room` (`id`, `name`, `idNumber`, `timeOpen`, `timeClose`, `isBookable`, `maxTime`, `idEstablishment`, `floor`) VALUES
(2, 'test1', 'xxx', '07:00:00', '18:00:00', 1, '02:00:00', 1, 25),
(3, 'test2', 'yyy', '00:00:00', '00:00:00', 1, '02:00:00', 1, 25),
(5, 'AFRIQUE (SDR) ', 'B110', '08:00:00', '19:00:00', 0, '03:00:00', 1, 1),
(7, 'AMERIQUE (SDR)', 'C109', '08:00:00', '19:00:00', 1, '03:00:00', 1, 1),
(8, 'AMPERE', 'B304', '08:00:00', '19:00:00', 0, '03:00:00', 1, 3),
(9, 'ANJOU (de l’ )', 'DS02', '08:00:00', '19:00:00', 0, '03:00:00', 1, -1),
(10, 'ANTARCTIQUE (SDR)', 'A115', '08:00:00', '19:00:00', 1, '03:00:00', 1, 1),
(11, 'ASIE   (SDR)', 'B213', '08:00:00', '19:00:00', 1, '03:00:00', 1, 2),
(12, 'ATELIER CIRCUITS IMPRIMES ', 'B208', '08:00:00', '19:00:00', 0, '03:00:00', 1, 2),
(13, 'BABBAGE', 'A108', '08:00:00', '19:00:00', 1, '03:00:00', 1, 1),
(14, 'BELL', 'A401', '08:00:00', '19:00:00', 1, '03:00:00', 1, 4),
(15, 'BERNOULLI', 'A402', '08:00:00', '19:00:00', 1, '03:00:00', 1, 4),
(16, 'BLONDEL', 'A205', '08:00:00', '19:00:00', 1, '03:00:00', 1, 2),
(17, 'BODE', 'B204', '08:00:00', '19:00:00', 0, '03:00:00', 1, 2),
(18, 'BOHR', 'A411', '08:00:00', '19:00:00', 1, '03:00:00', 1, 4),
(19, 'BOOLE', 'A209', '08:00:00', '19:00:00', 1, '03:00:00', 1, 2),
(20, 'BRAGG', 'B119', '08:00:00', '19:00:00', 1, '03:00:00', 1, 1),
(21, 'BRANLY', 'B313', '08:00:00', '19:00:00', 1, '03:00:00', 1, 3),
(22, 'BROGLIE', 'B007', '08:00:00', '19:00:00', 1, '03:00:00', 1, 0),
(23, 'CAFETERIA', 'C006', '08:00:00', '19:00:00', 0, '03:00:00', 1, 0),
(24, 'CARNOT', 'B211', '08:00:00', '19:00:00', 1, '03:00:00', 1, 2),
(25, 'CAUCHY', 'B114', '08:00:00', '19:00:00', 1, '03:00:00', 1, 1),
(26, 'CE (Comité d’Entreprise)', 'C107', '08:00:00', '19:00:00', 1, '03:00:00', 1, 1),
(27, 'CEM (RECHERCHE)', 'B217', '08:00:00', '19:00:00', 1, '03:00:00', 1, 2),
(28, 'COLLABORATIVE ROOM', 'A022', '08:00:00', '19:00:00', 1, '03:00:00', 1, 0),
(29, 'COULOMB', 'A403', '08:00:00', '19:00:00', 1, '03:00:00', 1, 4),
(30, 'CURIE', 'A404', '08:00:00', '19:00:00', 1, '03:00:00', 1, 4),
(31, 'DATA CENTER ', 'B109', '08:00:00', '19:00:00', 1, '03:00:00', 1, 1),
(32, 'DESCARTES', 'B116', '08:00:00', '19:00:00', 1, '03:00:00', 1, 1),
(33, 'DICKENS', 'B316', '08:00:00', '19:00:00', 1, '03:00:00', 1, 3),
(34, 'DIRAC', 'B009', '08:00:00', '19:00:00', 1, '03:00:00', 1, 0),
(35, 'EDISON', 'B314', '08:00:00', '19:00:00', 1, '03:00:00', 1, 3),
(36, 'EINSTEIN', 'A307', '08:00:00', '19:00:00', 1, '03:00:00', 1, 3),
(37, 'ESAKI', 'C302', '08:00:00', '19:00:00', 1, '03:00:00', 1, 3),
(38, 'ESPACE SAINT AUBIN (SDR)', 'C304', '08:00:00', '19:00:00', 1, '03:00:00', 1, 3),
(39, 'ESPACE DE COWORKING', 'A106', '08:00:00', '19:00:00', 1, '03:00:00', 1, 1),
(40, 'EULER', 'B005', '08:00:00', '19:00:00', 1, '03:00:00', 1, 0),
(41, 'FARADAY', 'B308', '08:00:00', '19:00:00', 1, '03:00:00', 1, 3),
(42, 'FERMI', 'B008', '08:00:00', '19:00:00', 1, '03:00:00', 1, 0),
(43, 'FLOYD', 'A206', '08:00:00', '19:00:00', 1, '03:00:00', 1, 2),
(44, 'FOURIER', 'B115', '08:00:00', '19:00:00', 1, '03:00:00', 1, 1),
(45, 'FRESNEL', 'B305', '08:00:00', '19:00:00', 1, '03:00:00', 1, 3),
(46, 'GALILEE', 'A413', '08:00:00', '19:00:00', 1, '03:00:00', 1, 4),
(47, 'GALOIS', 'B405', '08:00:00', '19:00:00', 1, '03:00:00', 1, 4),
(48, 'GAUSS', 'B118', '08:00:00', '19:00:00', 1, '03:00:00', 1, 1),
(49, 'HEISENBERG', 'A303', '08:00:00', '19:00:00', 1, '03:00:00', 1, 3),
(50, 'HOARE', 'A207', '08:00:00', '19:00:00', 1, '03:00:00', 1, 2),
(51, 'IMPRIMERIE', 'AS20', '08:00:00', '19:00:00', 1, '03:00:00', 1, -1),
(53, 'JEANNETEAU', 'D002', '08:00:00', '19:00:00', 1, '03:00:00', 1, 0),
(54, 'JOULE', 'B311', '08:00:00', '19:00:00', 1, '03:00:00', 1, 3),
(55, 'KALMAN', 'C207', '08:00:00', '19:00:00', 1, '03:00:00', 1, 2),
(56, 'KELVIN', 'A412', '08:00:00', '19:00:00', 1, '03:00:00', 1, 4),
(57, 'LAENNEC', 'C206', '08:00:00', '19:00:00', 1, '03:00:00', 1, 2),
(58, 'LANDAU', 'A314', '08:00:00', '19:00:00', 1, '03:00:00', 1, 3),
(59, 'LANGEVIN', 'A316', '08:00:00', '19:00:00', 1, '03:00:00', 1, 3),
(60, 'LAPLACE', 'B309', '08:00:00', '19:00:00', 1, '03:00:00', 1, 3),
(61, 'LAUM - UMR CNRS 6613 (RECH.)', 'B212', '08:00:00', '19:00:00', 1, '03:00:00', 1, 2),
(62, 'LEPRINCE RINGUET', 'A304', '08:00:00', '19:00:00', 1, '03:00:00', 1, 3),
(63, 'MARCONI', 'C205', '08:00:00', '19:00:00', 1, '03:00:00', 1, 2),
(64, 'MAXWELL', 'B219', '08:00:00', '19:00:00', 1, '03:00:00', 1, 2),
(65, 'MEITNER', 'A405', '08:00:00', '19:00:00', 1, '03:00:00', 1, 4),
(66, 'MICHELSON', 'B306', '08:00:00', '19:00:00', 1, '03:00:00', 1, 3),
(67, 'MONGE', 'C108', '08:00:00', '19:00:00', 1, '03:00:00', 1, 1),
(68, 'MULTIFONCTION', 'B004', '08:00:00', '19:00:00', 1, '03:00:00', 1, 0),
(69, 'von NEUMANN', 'A208', '08:00:00', '19:00:00', 1, '03:00:00', 1, 2),
(70, 'NEWTON', 'A315', '08:00:00', '19:00:00', 1, '03:00:00', 1, 3),
(71, 'NYQUIST', 'B205', '08:00:00', '19:00:00', 1, '03:00:00', 1, 2),
(72, 'OCEANIE (SDR)', 'C001', '08:00:00', '19:00:00', 1, '03:00:00', 1, 0),
(73, 'PASCAL', 'B113', '08:00:00', '19:00:00', 1, '03:00:00', 1, 1),
(74, 'PLANCK', 'A306', '08:00:00', '19:00:00', 1, '03:00:00', 1, 3),
(75, 'RAMANUJAN', 'B404', '08:00:00', '19:00:00', 1, '03:00:00', 1, 4),
(76, 'RIEMANN', 'B111', '08:00:00', '19:00:00', 1, '03:00:00', 1, 1),
(77, 'RF & HYPER (RECHERCHE)', 'B210', '08:00:00', '19:00:00', 1, '03:00:00', 1, 2),
(78, 'SCHRODINGER', 'A308', '08:00:00', '19:00:00', 1, '03:00:00', 1, 3),
(79, 'SHAKESPEARE', 'B315', '08:00:00', '19:00:00', 1, '03:00:00', 1, 3),
(80, 'SHANNON', 'A305', '08:00:00', '19:00:00', 1, '03:00:00', 1, 3),
(81, 'SIEMENS', 'B312', '08:00:00', '19:00:00', 1, '03:00:00', 1, 3),
(82, 'TAYLOR', 'B108', '08:00:00', '19:00:00', 1, '03:00:00', 1, 1),
(83, 'TESLA', 'B317', '08:00:00', '19:00:00', 1, '03:00:00', 1, 3),
(84, 'TURING', 'A107', '08:00:00', '19:00:00', 1, '03:00:00', 1, 1),
(85, 'VOLTA', 'B209', '08:00:00', '19:00:00', 1, '03:00:00', 1, 2),
(86, 'WATT', 'A204', '08:00:00', '19:00:00', 1, '03:00:00', 1, 2),
(87, 'WEIERSTRASS', 'B006', '08:00:00', '19:00:00', 1, '03:00:00', 1, 0),
(88, 'WIENER', 'B310', '08:00:00', '19:00:00', 1, '03:00:00', 1, 3);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
