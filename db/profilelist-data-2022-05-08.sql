-- phpMyAdmin SQL Dump
-- version 4.8.5
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1:3306
-- Generation Time: May 08, 2022 at 03:09 PM
-- Server version: 5.7.26
-- PHP Version: 7.3.5

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `profilelist`
--

-- --------------------------------------------------------

--
-- Table structure for table `auth-users`
--

DROP TABLE IF EXISTS `auth-users`;
CREATE TABLE IF NOT EXISTS `auth-users` (
  `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT,
  `user` varchar(16) NOT NULL,
  `hash` varchar(256) NOT NULL,
  `creation_date` datetime NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `user` (`user`),
  KEY `hash` (`hash`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;

--
-- Dumping data for table `auth-users`
--

INSERT INTO `auth-users` (`id`, `user`, `hash`, `creation_date`) VALUES
(1, 'admin', '591db633ab58c706ab1a0374c3d2ea44', '2022-05-01 18:41:22');

-- --------------------------------------------------------

--
-- Table structure for table `index_job_titles`
--

DROP TABLE IF EXISTS `index_job_titles`;
CREATE TABLE IF NOT EXISTS `index_job_titles` (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` varchar(32) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `name` (`name`)
) ENGINE=MyISAM AUTO_INCREMENT=4 DEFAULT CHARSET=utf8;

--
-- Dumping data for table `index_job_titles`
--

INSERT INTO `index_job_titles` (`id`, `name`) VALUES
(1, 'Job Title 2'),
(2, 'Job Title 3'),
(3, 'Job Title 1');

-- --------------------------------------------------------

--
-- Table structure for table `index_specializations`
--

DROP TABLE IF EXISTS `index_specializations`;
CREATE TABLE IF NOT EXISTS `index_specializations` (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` varchar(32) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `name` (`name`)
) ENGINE=MyISAM AUTO_INCREMENT=4 DEFAULT CHARSET=utf8;

--
-- Dumping data for table `index_specializations`
--

INSERT INTO `index_specializations` (`id`, `name`) VALUES
(1, 'Specialization 3'),
(2, 'Specialization 1'),
(3, 'Specialization 2');

-- --------------------------------------------------------

--
-- Table structure for table `profiles`
--

DROP TABLE IF EXISTS `profiles`;
CREATE TABLE IF NOT EXISTS `profiles` (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` varchar(30) NOT NULL,
  `middlename` varchar(30) NOT NULL,
  `surname` varchar(30) NOT NULL,
  `img` varchar(128) NOT NULL,
  `job_title_id` int(11) NOT NULL,
  `specialization_id` int(11) NOT NULL,
  `date_added` datetime NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `img` (`img`),
  KEY `job_title_id` (`job_title_id`),
  KEY `specialization_id` (`specialization_id`)
) ENGINE=MyISAM AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;

--
-- Dumping data for table `profiles`
--

INSERT INTO `profiles` (`id`, `name`, `middlename`, `surname`, `img`, `job_title_id`, `specialization_id`, `date_added`) VALUES
(1, 'Stepan1', 'Mikhailovich', 'Petrenko', 'img-profiles/temp.png', 1, 1, '2022-05-04 18:33:07'),
(2, 'sergey', 'ol', 'kud', 'Array', 1, 1, '2022-05-07 17:51:35');
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
