-- phpMyAdmin SQL Dump
-- version 4.8.5
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Generation Time: Dec 14, 2020 at 01:40 PM
-- Server version: 10.3.21-MariaDB-log
-- PHP Version: 7.3.13

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `bakeside`
--

-- --------------------------------------------------------

--
-- Table structure for table `donations`
--

CREATE TABLE `donations` (
  `id` int(11) NOT NULL,
  `name` varchar(120) NOT NULL,
  `amount` double(10,2) NOT NULL,
  `clientid` varchar(120) NOT NULL,
  `mmtransid` varchar(120) DEFAULT NULL,
  `wallet_network` varchar(120) DEFAULT NULL,
  `wallet_number` varchar(120) DEFAULT NULL,
  `payment_date` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `mm_transactions`
--

CREATE TABLE `mm_transactions` (
  `id` int(11) NOT NULL,
  `clienttransid` varchar(120) NOT NULL,
  `clientreference` varchar(120) NOT NULL,
  `telcotransid` varchar(120) NOT NULL,
  `transactionid` varchar(120) NOT NULL,
  `status` varchar(120) NOT NULL,
  `statusdate` varchar(120) NOT NULL,
  `reason` char(120) NOT NULL,
  `recorded_date` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `progress`
--

CREATE TABLE `progress` (
  `ID` varchar(15) NOT NULL,
  `sessionId` varchar(100) NOT NULL,
  `option` varchar(30) DEFAULT NULL,
  `donor_name` varchar(120) DEFAULT NULL,
  `amount` varchar(20) DEFAULT NULL,
  `network` varchar(22) NOT NULL,
  `walletno` varchar(15) DEFAULT NULL,
  `volunteer_name` varchar(120) DEFAULT NULL,
  `age` varchar(20) DEFAULT NULL,
  `constituency` varchar(120) DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `tracking`
--

CREATE TABLE `tracking` (
  `ID` varchar(15) NOT NULL,
  `sessionId` varchar(100) NOT NULL,
  `mode` varchar(100) NOT NULL,
  `username` varchar(100) NOT NULL,
  `time` datetime NOT NULL,
  `userData` varchar(10) NOT NULL,
  `track` int(100) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `transactions`
--

CREATE TABLE `transactions` (
  `id` int(11) NOT NULL,
  `clientid` varchar(120) NOT NULL,
  `donor_name` varchar(120) NOT NULL,
  `amount` double(10,2) NOT NULL,
  `status` varchar(120) NOT NULL,
  `reference` varchar(120) DEFAULT NULL,
  `telcotransid` varchar(120) DEFAULT NULL,
  `wallet_num` varchar(120) DEFAULT NULL,
  `wallet_network` varchar(120) NOT NULL,
  `trans_date` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `volunteers`
--

CREATE TABLE `volunteers` (
  `id` int(11) NOT NULL,
  `full_name` varchar(120) DEFAULT NULL,
  `mobile_number` varchar(120) NOT NULL,
  `age` int(10) DEFAULT NULL,
  `constituency` varchar(120) NOT NULL,
  `volunteered_at` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `donations`
--
ALTER TABLE `donations`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `mm_transactions`
--
ALTER TABLE `mm_transactions`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `progress`
--
ALTER TABLE `progress`
  ADD PRIMARY KEY (`ID`);

--
-- Indexes for table `tracking`
--
ALTER TABLE `tracking`
  ADD PRIMARY KEY (`ID`);

--
-- Indexes for table `transactions`
--
ALTER TABLE `transactions`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `volunteers`
--
ALTER TABLE `volunteers`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `donations`
--
ALTER TABLE `donations`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `mm_transactions`
--
ALTER TABLE `mm_transactions`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `transactions`
--
ALTER TABLE `transactions`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `volunteers`
--
ALTER TABLE `volunteers`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
