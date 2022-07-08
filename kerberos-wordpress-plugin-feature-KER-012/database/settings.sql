-- phpMyAdmin SQL Dump
-- version 5.1.3
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: May 02, 2022 at 01:32 AM
-- Server version: 5.7.33
-- PHP Version: 7.4.19

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `dental`
--

-- --------------------------------------------------------

--
-- Table structure for table `wp_settings`
--

CREATE TABLE `wp_settings` (
  `id` int(11) NOT NULL DEFAULT '1',
  `icon` mediumtext,
  `opening_days` json DEFAULT NULL,
  `opening_time` time DEFAULT NULL,
  `closing_time` time DEFAULT NULL,
  `created_time` datetime DEFAULT NULL,
  `updated_time` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `wp_settings`
--

INSERT INTO `wp_settings` (`id`, `icon`, `opening_days`, `opening_time`, `closing_time`, `created_time`, `updated_time`) VALUES
(1, 'http://dental.test:85/wp-content/uploads/2022/04/icon1.png', '{\"Friday\": false, \"Monday\": true, \"Sunday\": true, \"Tuesday\": false, \"Saturday\": false, \"Thursday\": true, \"Wednesday\": false}', '11:24:00', '04:24:00', '2022-04-29 03:24:41', '2022-04-29 03:24:41');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `wp_settings`
--
ALTER TABLE `wp_settings`
  ADD PRIMARY KEY (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
