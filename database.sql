-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host : 127.0.0.1
-- Generated in : Mon. 02 oct. 2023 at 21:32
-- server version : 10.4.28-MariaDB
-- PHP version : 8.2.4

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Data Base : `th_internal`
--

-- --------------------------------------------------------

--
-- `blog` table structure
--

CREATE TABLE `blog` (
  `author` text NOT NULL DEFAULT '[SYSTÈME]',
  `title` text NOT NULL DEFAULT '[Sans titre]',
  `content` text NOT NULL DEFAULT '[Sans contenu]',
  `files` text NOT NULL,
  `published` datetime NOT NULL DEFAULT '2007-08-05 03:47:00'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Setting values of `blog`
--

INSERT INTO `blog` (`author`, `title`, `content`, `files`, `published`) VALUES
('[SYSTEM]', 'Test title', 'Default content', '', '2023-10-02 21:36:00');

-- --------------------------------------------------------

--
-- `login` table structure
--

CREATE TABLE `login` (
  `username` text NOT NULL,
  `password` text NOT NULL,
  `token` text NOT NULL,
  `email` text NOT NULL,
  `Vcode` text NOT NULL DEFAULT '-1',
  `VcodeEx` datetime NOT NULL DEFAULT '2007-08-05 03:47:00'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Setting values of `login`
--

INSERT INTO `login` (`username`, `password`, `token`, `email`, `Vcode`, `VcodeEx`) VALUES
('admin', 'admin', '16651a8c25ed881', 'admin@localhost.com', '-1', '2023-10-03 11:23:49');
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
