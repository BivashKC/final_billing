-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jan 16, 2025 at 04:45 AM
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
-- Database: `confirmed_project`
--

-- --------------------------------------------------------

--
-- Table structure for table `bills`
--

CREATE TABLE `bills` (
  `bill_id` int(11) NOT NULL,
  `bill_number` varchar(20) NOT NULL,
  `customer_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `total_amount` decimal(10,2) NOT NULL,
  `discount` decimal(10,2) DEFAULT 0.00,
  `final_amount` decimal(10,2) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `bills`
--

INSERT INTO `bills` (`bill_id`, `bill_number`, `customer_id`, `user_id`, `total_amount`, `discount`, `final_amount`, `created_at`) VALUES
(1, 'BILLBFC67E03', 1, 3, 4700.00, 376.00, 4324.00, '2024-12-23 05:31:14'),
(2, 'BILLAF7BCF19', 2, 3, 3600.00, 144.00, 3456.00, '2024-12-23 05:33:01'),
(3, 'BILL9203A3B1', 2, 3, 1800.00, 90.00, 1710.00, '2024-12-23 05:35:24'),
(4, 'BILL1F2E8B79', 3, 3, 1800.00, 0.00, 1800.00, '2024-12-23 05:37:26'),
(5, 'BILLEA274647', 2, 3, 1800.00, 72.00, 1728.00, '2024-12-23 05:41:09'),
(7, 'BILLC7BF87FE', 2, 3, 1800.00, 72.00, 1728.00, '2024-12-23 05:48:28'),
(8, 'BILLA1380C89', 2, 3, 1800.00, 72.00, 1728.00, '2024-12-23 05:48:44'),
(9, 'BILL805F41C8', 2, 3, 1800.00, 72.00, 1728.00, '2024-12-23 05:48:53'),
(10, 'BILLB2C4453E', 3, 3, 8750.00, 0.00, 8750.00, '2024-12-23 05:49:21'),
(11, 'BILLF0E840A8', 3, 3, 8750.00, 0.00, 8750.00, '2024-12-23 05:51:22'),
(12, 'BILLC9510C33', 4, 3, 16800.00, 1344.00, 15456.00, '2024-12-23 05:51:58'),
(14, 'BILL48E48C44', 5, 3, 1250.00, 0.00, 1250.00, '2024-12-23 05:59:47'),
(15, 'BILL403C40D8', 6, 3, 8750.00, 1312.50, 7437.50, '2024-12-23 06:27:55'),
(17, 'BILL8DADF520', 8, 3, 6400.00, 3200.00, 3200.00, '2024-12-23 10:31:23'),
(19, 'BILLBDE3FE77', 9, 2, 11800.00, 2360.00, 9440.00, '2024-12-25 10:53:34');

-- --------------------------------------------------------

--
-- Table structure for table `bill_details`
--

CREATE TABLE `bill_details` (
  `bill_detail_id` int(11) NOT NULL,
  `bill_id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `product_quantity` int(11) NOT NULL,
  `product_price` decimal(10,2) NOT NULL,
  `product_total` decimal(10,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `bill_details`
--

INSERT INTO `bill_details` (`bill_detail_id`, `bill_id`, `product_id`, `product_quantity`, `product_price`, `product_total`) VALUES
(1, 1, 1, 20, 110.00, 2200.00),
(2, 1, 2, 10, 250.00, 2500.00),
(3, 2, 1, 10, 110.00, 1100.00),
(4, 2, 2, 10, 250.00, 2500.00),
(5, 3, 1, 5, 110.00, 550.00),
(6, 3, 2, 5, 250.00, 1250.00),
(7, 4, 2, 5, 250.00, 1250.00),
(8, 4, 1, 5, 110.00, 550.00),
(9, 5, 2, 5, 250.00, 1250.00),
(10, 5, 1, 5, 110.00, 550.00),
(13, 7, 1, 5, 110.00, 550.00),
(14, 7, 2, 5, 250.00, 1250.00),
(15, 8, 1, 5, 110.00, 550.00),
(16, 8, 2, 5, 250.00, 1250.00),
(17, 9, 1, 5, 110.00, 550.00),
(18, 9, 2, 5, 250.00, 1250.00),
(19, 10, 2, 35, 250.00, 8750.00),
(20, 11, 2, 35, 250.00, 8750.00),
(21, 12, 1, 5, 110.00, 550.00),
(22, 12, 2, 65, 250.00, 16250.00),
(24, 14, 2, 5, 250.00, 1250.00),
(25, 15, 2, 35, 250.00, 8750.00),
(28, 17, 1, 10, 120.00, 1200.00),
(29, 17, 2, 20, 260.00, 5200.00),
(33, 19, 1, 5, 120.00, 600.00),
(34, 19, 2, 20, 260.00, 5200.00),
(35, 19, 4, 40, 150.00, 6000.00);

-- --------------------------------------------------------

--
-- Table structure for table `customers`
--

CREATE TABLE `customers` (
  `customer_id` int(11) NOT NULL,
  `customer_name` varchar(100) NOT NULL,
  `customer_phone` varchar(15) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `customers`
--

INSERT INTO `customers` (`customer_id`, `customer_name`, `customer_phone`) VALUES
(1, 'bivash', '9840131222'),
(7, 'bivash kc', '1234567890'),
(2, 'bivash6000', '9840131222'),
(4, 'Ram Babu', '9840131222'),
(5, 'ram hari', '9840131222'),
(6, 'Ram Karki', '9840131222'),
(8, 'Success Dhital', '1234567890'),
(9, 'Sujal Chapagai', '1234567890'),
(3, 'Sujal Chapagai', '9840131222');

-- --------------------------------------------------------

--
-- Table structure for table `products`
--

CREATE TABLE `products` (
  `product_id` int(11) NOT NULL,
  `product_name` varchar(100) NOT NULL,
  `product_price` decimal(10,2) NOT NULL,
  `product_stock` int(11) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `products`
--

INSERT INTO `products` (`product_id`, `product_name`, `product_price`, `product_stock`, `created_at`, `updated_at`) VALUES
(1, 'Sugar', 120.00, 5, '2024-12-22 12:33:50', '2024-12-25 10:53:34'),
(2, 'Coffee(250 gm)', 260.00, 180, '2024-12-22 12:34:09', '2024-12-25 10:53:34'),
(4, 'Coca Cola', 150.00, 360, '2024-12-23 10:34:47', '2024-12-25 10:53:34');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `user_id` int(11) NOT NULL,
  `user_username` varchar(50) NOT NULL,
  `user_password` varchar(255) NOT NULL,
  `user_role` enum('admin','user') NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`user_id`, `user_username`, `user_password`, `user_role`, `created_at`) VALUES
(1, 'admin_user', '$2y$10$FW0y01L.Qvm3QMGKb9cwHu108B2GsBDrG8d06G9Y4FwamFALL.GSu', 'admin', '2024-12-22 12:24:21'),
(2, 'sujalwa', '$2y$10$i/mW7rIcz6qpY2uEw47lgeP.KPvYmtLiqmhKj3AoxRByohiepQXkG', 'user', '2024-12-22 12:24:21'),
(3, 'bivashkc', '$2y$10$DzQ/VSmigNV6mgJuzKyc3O020nWjBxBtGWvb9qmY03dQQLTJ6sMEK', 'user', '2024-12-23 05:15:51');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `bills`
--
ALTER TABLE `bills`
  ADD PRIMARY KEY (`bill_id`),
  ADD UNIQUE KEY `bill_number` (`bill_number`),
  ADD KEY `customer_id` (`customer_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `bill_details`
--
ALTER TABLE `bill_details`
  ADD PRIMARY KEY (`bill_detail_id`),
  ADD KEY `bill_id` (`bill_id`),
  ADD KEY `product_id` (`product_id`);

--
-- Indexes for table `customers`
--
ALTER TABLE `customers`
  ADD PRIMARY KEY (`customer_id`),
  ADD UNIQUE KEY `customer_name` (`customer_name`,`customer_phone`);

--
-- Indexes for table `products`
--
ALTER TABLE `products`
  ADD PRIMARY KEY (`product_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`user_id`),
  ADD UNIQUE KEY `user_username` (`user_username`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `bills`
--
ALTER TABLE `bills`
  MODIFY `bill_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;

--
-- AUTO_INCREMENT for table `bill_details`
--
ALTER TABLE `bill_details`
  MODIFY `bill_detail_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=36;

--
-- AUTO_INCREMENT for table `customers`
--
ALTER TABLE `customers`
  MODIFY `customer_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `products`
--
ALTER TABLE `products`
  MODIFY `product_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `user_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `bills`
--
ALTER TABLE `bills`
  ADD CONSTRAINT `bills_ibfk_1` FOREIGN KEY (`customer_id`) REFERENCES `customers` (`customer_id`),
  ADD CONSTRAINT `bills_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`);

--
-- Constraints for table `bill_details`
--
ALTER TABLE `bill_details`
  ADD CONSTRAINT `bill_details_ibfk_1` FOREIGN KEY (`bill_id`) REFERENCES `bills` (`bill_id`),
  ADD CONSTRAINT `bill_details_ibfk_2` FOREIGN KEY (`product_id`) REFERENCES `products` (`product_id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
