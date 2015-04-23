-- phpMyAdmin SQL Dump
-- version 4.1.12
-- http://www.phpmyadmin.net
--
-- Host: 127.0.0.1
-- Generation Time: Apr 20, 2015 at 10:27 PM
-- Server version: 5.6.16
-- PHP Version: 5.5.11

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
  `apply_time` int(11) DEFAULT NULL,
  PRIMARY KEY (`version`)
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
  `orderID` int(10) NOT NULL AUTO_INCREMENT,
  `userID` int(10) NOT NULL,
  `productID` int(10) NOT NULL,
  `record` datetime NOT NULL,
  PRIMARY KEY (`orderID`),
  KEY `userID` (`userID`),
  KEY `productID` (`productID`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=9 ;

--
-- Dumping data for table `order`
--

INSERT INTO `order` (`orderID`, `userID`, `productID`, `record`) VALUES
(1, 1, 1, '2015-01-31 09:01:16'),
(2, 1, 4, '2015-02-01 06:02:11'),
(3, 1, 9, '2015-02-01 06:02:22'),
(4, 3, 9, '2015-02-01 07:02:28'),
(5, 3, 3, '2015-02-01 07:02:35'),
(6, 3, 7, '2015-02-01 07:02:16'),
(7, 5, 4, '2015-02-02 04:02:02'),
(8, 5, 3, '2015-02-02 04:02:09');

-- --------------------------------------------------------

--
-- Table structure for table `product`
--

CREATE TABLE IF NOT EXISTS `product` (
  `productID` int(10) NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL,
  `price` int(10) NOT NULL,
  PRIMARY KEY (`productID`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=11 ;

--
-- Dumping data for table `product`
--

INSERT INTO `product` (`productID`, `name`, `price`) VALUES
(1, 'nokia', 10000),
(2, 'xolo ', 12000),
(3, 'Apple i4S', 35000),
(4, 'Apple I5', 35000),
(5, 'Galaxy Y', 7000),
(6, 'Moto E', 12000),
(7, 'Moto K', 12000),
(8, 'Moto G', 10000),
(9, 'Samsung Star', 15000),
(10, 'Samsung Note', 35000);

-- --------------------------------------------------------

--
-- Table structure for table `profile`
--

CREATE TABLE IF NOT EXISTS `profile` (
  `profileID` int(10) NOT NULL AUTO_INCREMENT,
  `userID` int(11) NOT NULL,
  `firstName` varchar(32) NOT NULL,
  `middleName` varchar(32) NOT NULL,
  `lastName` varchar(32) NOT NULL,
  `gender` enum('male','female') NOT NULL,
  PRIMARY KEY (`profileID`),
  KEY `userID` (`userID`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `review`
--

CREATE TABLE IF NOT EXISTS `review` (
  `reviewID` int(10) NOT NULL AUTO_INCREMENT,
  `userID` int(10) NOT NULL,
  `productID` int(10) NOT NULL,
  `review` varchar(150) NOT NULL,
  PRIMARY KEY (`reviewID`),
  KEY `userID` (`userID`),
  KEY `productID` (`productID`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=8 ;

--
-- Dumping data for table `review`
--

INSERT INTO `review` (`reviewID`, `userID`, `productID`, `review`) VALUES
(1, 1, 1, 'it is a good product. best for those who just want basic functionalities.'),
(2, 1, 9, 'It has a good quality camera, and battery life is great too. Quite difficult to hold as the phone size is big. but big screen is good for video viewin'),
(3, 1, 4, 'great phone. It has excellent 12 MP camera and 1600 dpi screen. sound quality is just excellent. Screen size is good and the processor is fastest amon'),
(4, 5, 4, 'it has an excellent Retina display. battery life is not good but can be better. The camera is great and has 12 MP. The sound is ok but I use headphone'),
(5, 5, 3, 'Not better than apple i4S but still good. The camera is better and the battery life is good as well. sound quality is great but lacks clarity. Process'),
(6, 3, 9, 'Samsung star lacks a good camera and perhaps can make improvement in the sound quality as well. the body is not that good and is not hand friendly. ba'),
(7, 3, 3, 'Its a very good iPhone. being i4s the processor is not better than I5 but still it is quite fast. Camera is great and is capable of taking high-qualit');

-- --------------------------------------------------------

--
-- Table structure for table `user`
--

CREATE TABLE IF NOT EXISTS `user` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `auth_key` varchar(32) COLLATE utf8_unicode_ci NOT NULL,
  `password_hash` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `password_reset_token` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `email` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `status` smallint(6) NOT NULL DEFAULT '10',
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `username` (`username`),
  UNIQUE KEY `email` (`email`),
  KEY `id` (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=6 ;

--
-- Dumping data for table `user`
--

INSERT INTO `user` (`id`, `username`, `auth_key`, `password_hash`, `password_reset_token`, `email`, `status`, `created_at`, `updated_at`) VALUES
(1, 'ashutosh', 'QTRcQZKDMBt7GWjdZdpryhmEqit7ktF5', '$2y$13$Rwf4JWy/A.hdvj1uTPruhOUXX/Yz8TK3MQKzs2JFULk4hQpfOMrle', NULL, 'ashutosh@gmail.com', 10, '2015-01-18 02:35:52', '2015-01-18 02:35:52'),
(2, 'ashu', 'eNE0Xi9kM8cG70g3gtJD9ANQIlxWva7L', '$2y$13$iX1Q/usRxtVqS4UNl98V6.lhoQBNjyKRRbhUYwfr6UY2Fe/xYcSx.', NULL, 'ashu@gmail.com', 10, '2015-01-18 02:42:13', '2015-01-18 02:42:13'),
(3, 'aditya', 'xUYEaKXWQiFzvXhLuxZXiQy4Kt9E8LZx', '$2y$13$JKzyUwXICAQ0Ii01ThQAqONpbngjcL98NJsPuN.rFC1lpw4nol8Zu', NULL, 'aditya@gmail.com', 10, '2015-01-18 02:55:35', '2015-01-18 02:55:35'),
(4, 'maulik', 'LIoYUF6WpceKaUrWzLuVSGb_jOSYfKUt', '$2y$13$ZCfVi0pymaPFOofQt1sLLeD7xJ29aKA7gEGdRUm5SPHsW9wY2yO5a', NULL, 'maulik@gmail.com', 10, '2015-01-18 02:57:18', '2015-01-18 02:57:18'),
(5, 'devansh', 'nEwuIbg6irV_MmKZN4gneOQw336J8lJt', '$2y$13$HvsCpQerO2Nq.XFqPAeFwul36lLDEnpDzp7njZ4EzGdEsHB8IbkgO', NULL, 'devansh@gmail.com', 10, '2015-02-02 20:46:41', '2015-02-02 20:46:41');

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
