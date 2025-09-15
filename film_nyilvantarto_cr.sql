-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Gép: 127.0.0.1
-- Létrehozás ideje: 2025. Sze 15. 13:52
-- Kiszolgáló verziója: 10.4.32-MariaDB
-- PHP verzió: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Adatbázis: `film_nyilvantarto`
--
CREATE DATABASE IF NOT EXISTS `film_nyilvantarto` DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci;
USE `film_nyilvantarto`;

-- --------------------------------------------------------

--
-- Tábla szerkezet ehhez a táblához `countries`
--

CREATE TABLE `countries` (
  `country_id` int(11) NOT NULL,
  `country` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

-- --------------------------------------------------------

--
-- Tábla szerkezet ehhez a táblához `films`
--

CREATE TABLE `films` (
  `film_id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `release_year` int(11) DEFAULT NULL,
  `duration_minutes` int(11) DEFAULT NULL,
  `description` text DEFAULT NULL,
  `language_id` int(11) DEFAULT NULL,
  `country_id` int(11) DEFAULT NULL,
  `genre_id` int(11) DEFAULT NULL,
  `rating_avg` decimal(3,2) DEFAULT 0.00,
  `subtitles` tinyint(1) DEFAULT 0,
  `studio_id` int(11) DEFAULT NULL,
  `film_cover` blob DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

-- --------------------------------------------------------

--
-- Tábla szerkezet ehhez a táblához `film_people`
--

CREATE TABLE `film_people` (
  `film_id` int(11) NOT NULL,
  `person_id` int(11) NOT NULL,
  `role_id` int(11) NOT NULL,
  `character_name` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

-- --------------------------------------------------------

--
-- Tábla szerkezet ehhez a táblához `genres`
--

CREATE TABLE `genres` (
  `genre_id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

-- --------------------------------------------------------

--
-- Tábla szerkezet ehhez a táblához `languages`
--

CREATE TABLE `languages` (
  `lang_id` int(11) NOT NULL,
  `language` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

-- --------------------------------------------------------

--
-- Tábla szerkezet ehhez a táblához `people`
--

CREATE TABLE `people` (
  `person_id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `birth_date` date DEFAULT NULL,
  `nationality` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

-- --------------------------------------------------------

--
-- Tábla szerkezet ehhez a táblához `roles`
--

CREATE TABLE `roles` (
  `role_id` int(11) NOT NULL,
  `role_name` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

-- --------------------------------------------------------

--
-- Tábla szerkezet ehhez a táblához `studios`
--

CREATE TABLE `studios` (
  `studio_id` int(11) NOT NULL,
  `studio` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Indexek a kiírt táblákhoz
--

--
-- A tábla indexei `countries`
--
ALTER TABLE `countries`
  ADD PRIMARY KEY (`country_id`);

--
-- A tábla indexei `films`
--
ALTER TABLE `films`
  ADD PRIMARY KEY (`film_id`),
  ADD KEY `language_id` (`language_id`),
  ADD KEY `country_id` (`country_id`),
  ADD KEY `genre_id` (`genre_id`),
  ADD KEY `studio_id` (`studio_id`);

--
-- A tábla indexei `film_people`
--
ALTER TABLE `film_people`
  ADD PRIMARY KEY (`film_id`,`person_id`,`role_id`),
  ADD KEY `person_id` (`person_id`),
  ADD KEY `role_id` (`role_id`);

--
-- A tábla indexei `genres`
--
ALTER TABLE `genres`
  ADD PRIMARY KEY (`genre_id`),
  ADD UNIQUE KEY `name` (`name`);

--
-- A tábla indexei `languages`
--
ALTER TABLE `languages`
  ADD PRIMARY KEY (`lang_id`);

--
-- A tábla indexei `people`
--
ALTER TABLE `people`
  ADD PRIMARY KEY (`person_id`);

--
-- A tábla indexei `roles`
--
ALTER TABLE `roles`
  ADD PRIMARY KEY (`role_id`);

--
-- A tábla indexei `studios`
--
ALTER TABLE `studios`
  ADD PRIMARY KEY (`studio_id`);

--
-- A kiírt táblák AUTO_INCREMENT értéke
--

--
-- AUTO_INCREMENT a táblához `countries`
--
ALTER TABLE `countries`
  MODIFY `country_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT a táblához `films`
--
ALTER TABLE `films`
  MODIFY `film_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT a táblához `genres`
--
ALTER TABLE `genres`
  MODIFY `genre_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT a táblához `languages`
--
ALTER TABLE `languages`
  MODIFY `lang_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT a táblához `people`
--
ALTER TABLE `people`
  MODIFY `person_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT a táblához `roles`
--
ALTER TABLE `roles`
  MODIFY `role_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT a táblához `studios`
--
ALTER TABLE `studios`
  MODIFY `studio_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- Megkötések a kiírt táblákhoz
--

--
-- Megkötések a táblához `films`
--
ALTER TABLE `films`
  ADD CONSTRAINT `films_ibfk_1` FOREIGN KEY (`language_id`) REFERENCES `languages` (`lang_id`),
  ADD CONSTRAINT `films_ibfk_2` FOREIGN KEY (`country_id`) REFERENCES `countries` (`country_id`),
  ADD CONSTRAINT `films_ibfk_3` FOREIGN KEY (`genre_id`) REFERENCES `genres` (`genre_id`),
  ADD CONSTRAINT `films_ibfk_4` FOREIGN KEY (`studio_id`) REFERENCES `studios` (`studio_id`);

--
-- Megkötések a táblához `film_people`
--
ALTER TABLE `film_people`
  ADD CONSTRAINT `film_people_ibfk_1` FOREIGN KEY (`film_id`) REFERENCES `films` (`film_id`),
  ADD CONSTRAINT `film_people_ibfk_2` FOREIGN KEY (`person_id`) REFERENCES `people` (`person_id`),
  ADD CONSTRAINT `film_people_ibfk_3` FOREIGN KEY (`role_id`) REFERENCES `roles` (`role_id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
