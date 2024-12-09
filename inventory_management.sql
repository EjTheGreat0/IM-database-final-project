-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Dec 09, 2024 at 05:25 PM
-- Server version: 10.4.25-MariaDB
-- PHP Version: 7.4.30

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `inventory_management`
--

-- --------------------------------------------------------

--
-- Table structure for table `items`
--

CREATE TABLE `items` (
  `item_id` int(11) NOT NULL,
  `item_code` varchar(50) NOT NULL,
  `description` varchar(255) DEFAULT NULL,
  `vendor_id` int(11) DEFAULT NULL,
  `location` varchar(255) DEFAULT NULL,
  `stock_quantity` int(11) DEFAULT NULL,
  `reorder_status` enum('OK','REORDER') DEFAULT NULL,
  `price_per_unit` decimal(10,2) DEFAULT NULL,
  `total_value` decimal(15,2) DEFAULT NULL,
  `last_order_date` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `items`
--

INSERT INTO `items` (`item_id`, `item_code`, `description`, `vendor_id`, `location`, `stock_quantity`, `reorder_status`, `price_per_unit`, `total_value`, `last_order_date`) VALUES
(5, '1', 'qwe', 1, 'qwe', 12, NULL, '12.00', '144.00', NULL),
(7, '1', 'qwe', 2, 'qwe', 5, NULL, '0.00', '0.20', NULL),
(8, '2', 'wqweq', 2, 'qweqweqweq', 123, NULL, '123.00', '15129.00', NULL),
(9, '2', 'wqweq', 2, 'qweqweqweq', 123, NULL, '123.00', '15129.00', NULL),
(10, '2', 'wqweq', 2, 'qweqweqweq', 123, NULL, '123.00', '15129.00', NULL),
(11, '2', 'wqweq', 2, 'qweqweqweq', 123, NULL, '123.00', '15129.00', NULL),
(12, '2', 'wqweq', 2, 'qweqweqweq', 123, NULL, '123.00', '15129.00', NULL),
(13, '2', 'wqweq', 2, 'qweqweqweq', 123, NULL, '123.00', '15129.00', NULL),
(14, '2', 'wqweq', 2, 'qweqweqweq', 123, NULL, '123.00', '15129.00', NULL),
(15, '2', 'wqweq', 2, 'qweqweqweq', 123, NULL, '123.00', '15129.00', NULL),
(16, '2', 'wqweq', 2, 'qweqweqweq', 123, NULL, '123.00', '15129.00', NULL),
(17, '2', 'wqweq', 2, 'qweqweqweq', 123, NULL, '123.00', '15129.00', NULL),
(18, '2', 'wqweq', 2, 'qweqweqweq', 123, NULL, '123.00', '15129.00', NULL),
(19, '2', 'discription', 1, 'murcia', 123, NULL, '123.00', '15129.00', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `user_id` int(11) NOT NULL,
  `first_name` varchar(50) NOT NULL,
  `last_name` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `username` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`user_id`, `first_name`, `last_name`, `password`, `created_at`, `username`) VALUES
(1, 'john', 'baylon', '$2y$10$13ltwo2k9DmBQzwsArLveOOzeDHIGhzGR.ZAR62VmO.J4V4eALrhO', '2024-11-06 14:57:13', 'earl');

-- --------------------------------------------------------

--
-- Table structure for table `vendors`
--

CREATE TABLE `vendors` (
  `vendor_id` int(11) NOT NULL,
  `vendor_name` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `vendors`
--

INSERT INTO `vendors` (`vendor_id`, `vendor_name`) VALUES
(1, 'Vendor 1'),
(2, 'Vendor 2'),
(3, 'Vendor 3'),
(4, 'Vendor 4'),
(5, 'Vendor 5');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `items`
--
ALTER TABLE `items`
  ADD PRIMARY KEY (`item_id`),
  ADD KEY `vendor_id` (`vendor_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`user_id`),
  ADD UNIQUE KEY `username` (`username`);

--
-- Indexes for table `vendors`
--
ALTER TABLE `vendors`
  ADD PRIMARY KEY (`vendor_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `items`
--
ALTER TABLE `items`
  MODIFY `item_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `user_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `vendors`
--
ALTER TABLE `vendors`
  MODIFY `vendor_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `items`
--
ALTER TABLE `items`
  ADD CONSTRAINT `items_ibfk_1` FOREIGN KEY (`vendor_id`) REFERENCES `vendors` (`vendor_id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
