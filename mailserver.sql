-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Nov 26, 2025 at 04:13 AM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `mailserver`
--

-- --------------------------------------------------------

--
-- Table structure for table `virtual_aliases`
--

CREATE TABLE `virtual_aliases` (
  `id` int(11) NOT NULL,
  `domain_id` int(11) NOT NULL,
  `source` varchar(100) NOT NULL,
  `destination` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `virtual_aliases`
--

INSERT INTO `virtual_aliases` (`id`, `domain_id`, `source`, `destination`) VALUES
(1, 1, 'abuse@domain1.com', 'postmaster@domain1.com'),
(2, 2, 'abuse@domain2.net', 'postmaster@domain2.net');

-- --------------------------------------------------------

--
-- Table structure for table `virtual_domains`
--

CREATE TABLE `virtual_domains` (
  `id` int(11) NOT NULL,
  `name` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `virtual_domains`
--

INSERT INTO `virtual_domains` (`id`, `name`) VALUES
(1, 'mydomain1.com'),
(2, 'mydomain2.net');

-- --------------------------------------------------------

--
-- Table structure for table `virtual_users`
--

CREATE TABLE `virtual_users` (
  `id` int(11) NOT NULL,
  `domain_id` int(11) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `virtual_users`
--

INSERT INTO `virtual_users` (`id`, `domain_id`, `email`, `password`) VALUES
(1, 1, 'postmaster@mydomain1.com', '{SHA512-CRYPT}$6$037d09a81139418a$CMLRztsGcUTodgAIYlmeig46B2VynH3Uy3lnT9PtLh4lAMwth5jpsl.1igTPn35H6pAhooSYS8qDXjRYW9.dh1'),
(2, 1, 'admin@mydomain1.com', '{SHA512-CRYPT}$6$29fe927f03415aea$CqNJUlvcTtQbuTX/c40sceQ6fCA8eMuTqQuiIhWX7qOzCZ6yp7wiAUTpBBb0oBTSDPjS1Bj1lU9FOEnMz6KU11'),
(3, 2, 'postmaster@mydomain2.net', '{SHA512-CRYPT}$6$e95a3d7457375391$yuJzU/BdocmXXqEttlIdEU7H6xeHLJUmC3zfZB8gK1XQQ4lzhHwelVSFpBtYB1Gaj.Uu0zx0MF2ZL3W9R0GbI.'),
(4, 2, 'admin@mydomain2.net', '{SHA512-CRYPT}$6$9c94c67ec8c6dbff$WcTyvXOFISNfiiNZ6oRrL7Zq0aInoljTT4FnsCsfvxTetSObZH4PiMoZuGj6pDdCtP2epjz6iwxCEdia0udlY1');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `virtual_aliases`
--
ALTER TABLE `virtual_aliases`
  ADD PRIMARY KEY (`id`),
  ADD KEY `domain_id` (`domain_id`);

--
-- Indexes for table `virtual_domains`
--
ALTER TABLE `virtual_domains`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `virtual_users`
--
ALTER TABLE `virtual_users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`),
  ADD KEY `domain_id` (`domain_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `virtual_aliases`
--
ALTER TABLE `virtual_aliases`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `virtual_domains`
--
ALTER TABLE `virtual_domains`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `virtual_users`
--
ALTER TABLE `virtual_users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `virtual_aliases`
--
ALTER TABLE `virtual_aliases`
  ADD CONSTRAINT `virtual_aliases_ibfk_1` FOREIGN KEY (`domain_id`) REFERENCES `virtual_domains` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `virtual_users`
--
ALTER TABLE `virtual_users`
  ADD CONSTRAINT `virtual_users_ibfk_1` FOREIGN KEY (`domain_id`) REFERENCES `virtual_domains` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
