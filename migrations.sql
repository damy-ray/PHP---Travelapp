-- phpMyAdmin SQL Dump
-- version 5.0.2
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Creato il: Giu 08, 2024 alle 17:25
-- Versione del server: 10.4.14-MariaDB
-- Versione PHP: 7.4.10

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `travelapp`
--

-- --------------------------------------------------------

--
-- Struttura della tabella `countries`
--

CREATE TABLE `countries` (
  `id` int(5) UNSIGNED NOT NULL,
  `name` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dump dei dati per la tabella `countries`
--

INSERT INTO `countries` (`id`, `name`) VALUES
(4, 'Belgium'),
(3, 'France'),
(2, 'Germany'),
(8, 'Hungary'),
(1, 'Italy'),
(16, 'Japan'),
(6, 'Portugal'),
(5, 'Spain'),
(7, 'Switzerland');

-- --------------------------------------------------------

--
-- Struttura della tabella `itineraries`
--

CREATE TABLE `itineraries` (
  `id` int(5) UNSIGNED NOT NULL,
  `id_trip` int(5) UNSIGNED NOT NULL,
  `day` date NOT NULL,
  `time` time NOT NULL,
  `place` varchar(50) NOT NULL,
  `details` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dump dei dati per la tabella `itineraries`
--

INSERT INTO `itineraries` (`id`, `id_trip`, `day`, `time`, `place`, `details`) VALUES
(1, 1, '2024-02-05', '15:00:00', 'Messina', 'Duomo of Messina'),
(2, 1, '2024-02-05', '17:00:00', 'Catania', 'Stericoro Square'),
(3, 1, '2024-03-06', '16:30:00', 'Siracusa', 'Ortigia Island'),
(4, 2, '2024-06-11', '15:00:00', 'Paris', 'Louvre Museum'),
(5, 2, '2024-06-12', '17:00:00', 'Paris', 'Tour Eiffel'),
(6, 2, '2024-06-13', '12:00:00', 'Paris', 'Triumphal Arch'),
(7, 2, '2024-06-14', '15:00:00', 'Paris', 'Palace of Versailles'),
(8, 3, '2024-06-23', '17:35:00', 'Berlin', 'Berlin Wall'),
(12, 2, '2024-06-25', '17:00:00', 'Berlin', 'German Museum');

-- --------------------------------------------------------

--
-- Struttura della tabella `travellers`
--

CREATE TABLE `travellers` (
  `id` int(5) UNSIGNED NOT NULL,
  `id_trip` int(5) UNSIGNED NOT NULL,
  `name` varchar(50) NOT NULL,
  `birth_date` date NOT NULL,
  `gender` enum('m','f') NOT NULL,
  `payed` enum('yes','no') NOT NULL DEFAULT 'no'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dump dei dati per la tabella `travellers`
--

INSERT INTO `travellers` (`id`, `id_trip`, `name`, `birth_date`, `gender`, `payed`) VALUES
(1, 1, 'Mario Rossi', '1998-03-22', 'm', 'yes'),
(2, 1, 'Luigi Verdi', '1987-12-05', 'm', 'yes'),
(3, 2, 'Luisa Castani', '1994-11-13', 'f', 'no'),
(4, 2, 'Valeria Marroni', '1996-09-10', 'f', 'no'),
(5, 2, 'Marco Ferretto', '1997-05-11', 'm', 'yes'),
(6, 2, 'Giulio Golia', '1980-01-01', 'm', 'yes'),
(8, 3, 'Davide Calisti', '1998-02-12', 'm', 'yes');

-- --------------------------------------------------------

--
-- Struttura della tabella `trips`
--

CREATE TABLE `trips` (
  `id` int(5) UNSIGNED NOT NULL,
  `id_country` int(5) UNSIGNED NOT NULL,
  `region` varchar(50) DEFAULT NULL,
  `departure_date` date NOT NULL,
  `return_date` date NOT NULL,
  `available_seats` smallint(1) UNSIGNED NOT NULL,
  `price` float UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dump dei dati per la tabella `trips`
--

INSERT INTO `trips` (`id`, `id_country`, `region`, `departure_date`, `return_date`, `available_seats`, `price`) VALUES
(1, 1, 'Sicily', '2024-02-04', '2024-02-07', 20, 570),
(2, 3, 'Ileeeeee de France', '2024-06-10', '2024-06-14', 15, 520),
(3, 2, 'Chöttingen', '2024-06-22', '2024-06-25', 30, 570),
(5, 2, 'Metropolitan Region of Berlin', '2024-06-22', '2024-06-25', 50, 950);

--
-- Indici per le tabelle scaricate
--

--
-- Indici per le tabelle `countries`
--
ALTER TABLE `countries`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `name` (`name`);

--
-- Indici per le tabelle `itineraries`
--
ALTER TABLE `itineraries`
  ADD PRIMARY KEY (`id`),
  ADD KEY `FK_itineraries_id_trip` (`id_trip`);

--
-- Indici per le tabelle `travellers`
--
ALTER TABLE `travellers`
  ADD PRIMARY KEY (`id`),
  ADD KEY `FK_travellers_id_trip` (`id_trip`);

--
-- Indici per le tabelle `trips`
--
ALTER TABLE `trips`
  ADD PRIMARY KEY (`id`),
  ADD KEY `FK_id_country` (`id_country`);

--
-- AUTO_INCREMENT per le tabelle scaricate
--

--
-- AUTO_INCREMENT per la tabella `countries`
--
ALTER TABLE `countries`
  MODIFY `id` int(5) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT per la tabella `itineraries`
--
ALTER TABLE `itineraries`
  MODIFY `id` int(5) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT per la tabella `travellers`
--
ALTER TABLE `travellers`
  MODIFY `id` int(5) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT per la tabella `trips`
--
ALTER TABLE `trips`
  MODIFY `id` int(5) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- Limiti per le tabelle scaricate
--

--
-- Limiti per la tabella `itineraries`
--
ALTER TABLE `itineraries`
  ADD CONSTRAINT `FK_itineraries_id_trip` FOREIGN KEY (`id_trip`) REFERENCES `trips` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Limiti per la tabella `travellers`
--
ALTER TABLE `travellers`
  ADD CONSTRAINT `FK_travellers_id_trip` FOREIGN KEY (`id_trip`) REFERENCES `trips` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Limiti per la tabella `trips`
--
ALTER TABLE `trips`
  ADD CONSTRAINT `FK_id_country` FOREIGN KEY (`id_country`) REFERENCES `countries` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
