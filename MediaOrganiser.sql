-- phpMyAdmin SQL Dump
-- version 4.9.6
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Generation Time: Dec 14, 2021 at 02:16 PM
-- Server version: 10.3.27-MariaDB-0+deb10u1
-- PHP Version: 7.3.27-1~deb10u1

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `MediaOrganiser`
--

-- --------------------------------------------------------

--
-- Table structure for table `Categories`
--

CREATE TABLE `Categories` (
  `categoryID` int(255) NOT NULL,
  `category` varchar(100) CHARACTER SET utf8 NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `Categories`
--

INSERT INTO `Categories` (`categoryID`, `category`) VALUES
(1, 'Rock'),
(2, 'Blues'),
(3, 'Country'),
(4, 'Easy listening'),
(5, 'Electronic'),
(6, 'Contemporary folk'),
(7, 'Hip hop'),
(8, 'Jazz'),
(9, 'Pop'),
(10, 'R&B'),
(11, 'Metal'),
(12, 'Punk'),
(13, 'Reggae');

-- --------------------------------------------------------

--
-- Table structure for table `Images`
--

CREATE TABLE `Images` (
  `imageID` int(255) NOT NULL,
  `image` varchar(100) CHARACTER SET utf8 NOT NULL,
  `imagePath` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--

-- --------------------------------------------------------

--
-- Table structure for table `MediaFiles`
--

CREATE TABLE `MediaFiles` (
  `fileID` int(255) NOT NULL,
  `title` varchar(100) CHARACTER SET utf8 NOT NULL,
  `filepath` varchar(1000) CHARACTER SET utf8 NOT NULL,
  `typeID` int(10) NOT NULL,
  `comment` varchar(50) CHARACTER SET utf8 DEFAULT NULL,
  `categoryID` int(255) DEFAULT NULL,
  `imageID` int(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--

-- --------------------------------------------------------

--
-- Table structure for table `Playlists`
--

CREATE TABLE `Playlists` (
  `playlistID` int(255) NOT NULL,
  `playlist` varchar(100) CHARACTER SET utf8 NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--

-- --------------------------------------------------------

--
-- Table structure for table `PlaylistsFiles`
--

CREATE TABLE `PlaylistsFiles` (
  `id` int(255) NOT NULL,
  `fileID` int(255) NOT NULL,
  `playlistID` int(255) NOT NULL,
  `orderID` int(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--

-- --------------------------------------------------------

--
-- Table structure for table `Types`
--

CREATE TABLE `Types` (
  `typeID` int(10) NOT NULL,
  `type` varchar(100) CHARACTER SET utf8 NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--

--
-- Indexes for dumped tables
--

--
-- Indexes for table `Categories`
--
ALTER TABLE `Categories`
  ADD PRIMARY KEY (`categoryID`);

--
-- Indexes for table `Images`
--
ALTER TABLE `Images`
  ADD PRIMARY KEY (`imageID`);

--
-- Indexes for table `MediaFiles`
--
ALTER TABLE `MediaFiles`
  ADD PRIMARY KEY (`fileID`);

--
-- Indexes for table `Playlists`
--
ALTER TABLE `Playlists`
  ADD PRIMARY KEY (`playlistID`);

--
-- Indexes for table `PlaylistsFiles`
--
ALTER TABLE `PlaylistsFiles`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `Types`
--
ALTER TABLE `Types`
  ADD PRIMARY KEY (`typeID`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `Categories`
--
ALTER TABLE `Categories`
  MODIFY `categoryID` int(255) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1;

--
-- AUTO_INCREMENT for table `Images`
--
ALTER TABLE `Images`
  MODIFY `imageID` int(255) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1;

--
-- AUTO_INCREMENT for table `MediaFiles`
--
ALTER TABLE `MediaFiles`
  MODIFY `fileID` int(255) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1;

--
-- AUTO_INCREMENT for table `Playlists`
--
ALTER TABLE `Playlists`
  MODIFY `playlistID` int(255) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1;

--
-- AUTO_INCREMENT for table `PlaylistsFiles`
--
ALTER TABLE `PlaylistsFiles`
  MODIFY `id` int(255) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1;

--
-- AUTO_INCREMENT for table `Types`
--
ALTER TABLE `Types`
  MODIFY `typeID` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
