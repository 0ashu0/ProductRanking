-- phpMyAdmin SQL Dump
-- version 4.2.11
-- http://www.phpmyadmin.net
--
-- Host: 127.0.0.1
-- Generation Time: Jan 29, 2015 at 08:15 PM
-- Server version: 5.6.21
-- PHP Version: 5.6.3

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `myecomm`
--

-- --------------------------------------------------------

--
-- Table structure for table `migration`
--

CREATE TABLE IF NOT EXISTS `migration` (
  `version` varchar(180) NOT NULL,
  `apply_time` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `migration`
--

INSERT INTO `migration` (`version`, `apply_time`) VALUES
('m000000_000000_base', 1421504161),
('m130524_201442_init', 1421504167);

-- --------------------------------------------------------

--
-- Table structure for table `order`
--

CREATE TABLE IF NOT EXISTS `order` (
`orderID` int(10) NOT NULL,
  `userID` int(10) NOT NULL,
  `productID` int(10) NOT NULL,
  `record` datetime NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `order`
--

INSERT INTO `order` (`orderID`, `userID`, `productID`, `record`) VALUES
(1, 1, 1, '2015-01-24 19:01:56'),
(2, 1, 5, '2015-01-24 08:01:01');

-- --------------------------------------------------------

--
-- Table structure for table `product`
--

CREATE TABLE IF NOT EXISTS `product` (
`productID` int(10) NOT NULL,
  `name` varchar(100) NOT NULL,
  `price` int(10) NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `product`
--

INSERT INTO `product` (`productID`, `name`, `price`) VALUES
(1, 'Xolo q700i', 10000),
(2, 'Apple 5', 45000),
(3, 'Motorola', 8000),
(4, 'Moto G', 8000),
(5, 'Galaxy Y', 7000),
(6, 'Nokia N96', 20000),
(7, 'Nokia 3315', 3000);

-- --------------------------------------------------------

--
-- Table structure for table `profile`
--

CREATE TABLE IF NOT EXISTS `profile` (
`profileID` int(10) NOT NULL,
  `userID` int(11) NOT NULL,
  `firstName` varchar(32) NOT NULL,
  `middleName` varchar(32) NOT NULL,
  `lastName` varchar(32) NOT NULL,
  `gender` enum('male','female') NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `profile`
--

INSERT INTO `profile` (`profileID`, `userID`, `firstName`, `middleName`, `lastName`, `gender`) VALUES
(1, 3, 'Devansh', 'Hari', 'Trivedi', 'male');

-- --------------------------------------------------------

--
-- Table structure for table `review`
--

CREATE TABLE IF NOT EXISTS `review` (
`reviewID` int(10) NOT NULL,
  `userID` int(10) NOT NULL,
  `productID` int(10) NOT NULL,
  `review` mediumtext NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `review`
--

INSERT INTO `review` (`reviewID`, `userID`, `productID`, `review`) VALUES
(1, 1, 1, 'it a nice product with respect to camera. although the sound quality isn''t great but I can cope with that. the battery life isnt great either. it a good product if you want to waste your money.'),
(2, 1, 5, 'I had bought this mobile a long time ago. it has very small storage capacity for applications. although it was leggy? and small, i still use it.');

-- --------------------------------------------------------

--
-- Table structure for table `user`
--

CREATE TABLE IF NOT EXISTS `user` (
`id` int(11) NOT NULL,
  `username` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `auth_key` varchar(32) COLLATE utf8_unicode_ci NOT NULL,
  `password_hash` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `password_reset_token` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `email` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `status` smallint(6) NOT NULL DEFAULT '10',
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `user`
--

INSERT INTO `user` (`id`, `username`, `auth_key`, `password_hash`, `password_reset_token`, `email`, `status`, `created_at`, `updated_at`) VALUES
(1, 'ashutosh', 'iORRfwSRVy08pSQP_WN1QoqaFDI0Ch0l', '$2y$13$wRKe.R5YZHygUZJT8YPDDujDxmJPWf3V.pdUTMbhaC3.lDG1DsdRy', NULL, 'ashutosh@gmail.com', 10, '2015-01-24 22:20:42', '2015-01-24 22:20:42'),
(2, 'aditya', 'heV2l1kgCBOwyjQcH7dgsQNWpqVWu6L8', '$2y$13$BtCtQvYyY5jH4g2oVk2z9uEiOYyouM950EzyuB8vYhfPpYy8VbLm.', NULL, 'aditya@gmail.com', 10, '2015-01-24 22:23:53', '2015-01-24 22:23:53'),
(3, 'devansh', 'ftVkAN2Jnpq3lgM0aRkEH1pwPRWC5bOO', '$2y$13$mtUeE9CLaK5J94je4VeRXuoN7rQv8IlzNaUOGVRIe4dkNYaDgcsia', NULL, 'devansh@gmail.com', 10, '2015-01-24 22:27:11', '2015-01-24 22:27:11');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `migration`
--
ALTER TABLE `migration`
 ADD PRIMARY KEY (`version`);

--
-- Indexes for table `order`
--
ALTER TABLE `order`
 ADD PRIMARY KEY (`orderID`), ADD KEY `userID` (`userID`), ADD KEY `productID` (`productID`);

--
-- Indexes for table `product`
--
ALTER TABLE `product`
 ADD PRIMARY KEY (`productID`);

--
-- Indexes for table `profile`
--
ALTER TABLE `profile`
 ADD PRIMARY KEY (`profileID`), ADD KEY `userID` (`userID`);

--
-- Indexes for table `review`
--
ALTER TABLE `review`
 ADD PRIMARY KEY (`reviewID`), ADD KEY `userID` (`userID`), ADD KEY `productID` (`productID`);

--
-- Indexes for table `user`
--
ALTER TABLE `user`
 ADD PRIMARY KEY (`id`), ADD UNIQUE KEY `username` (`username`), ADD UNIQUE KEY `email` (`email`), ADD KEY `id` (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `order`
--
ALTER TABLE `order`
MODIFY `orderID` int(10) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=3;
--
-- AUTO_INCREMENT for table `product`
--
ALTER TABLE `product`
MODIFY `productID` int(10) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=8;
--
-- AUTO_INCREMENT for table `profile`
--
ALTER TABLE `profile`
MODIFY `profileID` int(10) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=2;
--
-- AUTO_INCREMENT for table `review`
--
ALTER TABLE `review`
MODIFY `reviewID` int(10) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=3;
--
-- AUTO_INCREMENT for table `user`
--
ALTER TABLE `user`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=4;
--
-- Constraints for dumped tables
--

--
-- Constraints for table `order`
--
ALTER TABLE `order`
ADD CONSTRAINT `order_ibfk_1` FOREIGN KEY (`userID`) REFERENCES `user` (`id`),
ADD CONSTRAINT `order_ibfk_2` FOREIGN KEY (`productID`) REFERENCES `product` (`productID`);

--
-- Constraints for table `profile`
--
ALTER TABLE `profile`
ADD CONSTRAINT `profile_ibfk_1` FOREIGN KEY (`userID`) REFERENCES `user` (`id`);

--
-- Constraints for table `review`
--
ALTER TABLE `review`
ADD CONSTRAINT `review_ibfk_1` FOREIGN KEY (`userID`) REFERENCES `user` (`id`),
ADD CONSTRAINT `review_ibfk_2` FOREIGN KEY (`productID`) REFERENCES `product` (`productID`);

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
