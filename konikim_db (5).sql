-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Dec 13, 2023 at 01:54 PM
-- Server version: 10.4.28-MariaDB
-- PHP Version: 8.2.4

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `konikim_db`
--

-- --------------------------------------------------------

--
-- Table structure for table `adjustment_log`
--

CREATE TABLE `adjustment_log` (
  `id` int(11) NOT NULL,
  `product_id` int(11) DEFAULT NULL,
  `adjustment_quantity` int(11) DEFAULT NULL,
  `reason` varchar(255) DEFAULT NULL,
  `adjusted_by` varchar(100) DEFAULT NULL,
  `adjustment_date` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `adjustment_log`
--

INSERT INTO `adjustment_log` (`id`, `product_id`, `adjustment_quantity`, `reason`, `adjusted_by`, `adjustment_date`) VALUES
(1, 11, 5, 'Kinain ng daga', 'Gilmar ', '2023-12-13 20:27:40'),
(2, 15, 5, 'supot', 'Gilmar ', '2023-12-13 20:40:54');

-- --------------------------------------------------------

--
-- Table structure for table `inventory`
--

CREATE TABLE `inventory` (
  `id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `supplier` varchar(255) NOT NULL,
  `productname` varchar(255) NOT NULL,
  `productunit` varchar(50) NOT NULL,
  `productprice` decimal(10,2) NOT NULL,
  `available_stocks` int(11) NOT NULL,
  `total` decimal(10,2) NOT NULL,
  `last_updated` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `inventory`
--

INSERT INTO `inventory` (`id`, `product_id`, `supplier`, `productname`, `productunit`, `productprice`, `available_stocks`, `total`, `last_updated`) VALUES
(16, 11, 'Blue Corner', 'Paper Vaper', 'Ream', 215.00, 25, 5375.00, '2023-12-13 20:40:08'),
(17, 14, 'Blue Corner', 'Paper Maker', 'Box', 160.00, 5, 800.00, '2023-12-13 20:40:08'),
(18, 13, 'National Bookstore', 'Manila Paper', 'Pieces', 60.00, 5, 300.00, '2023-12-13 20:40:29'),
(19, 15, 'National Bookstore', 'Ruler', 'Pieces', 170.00, 0, 850.00, '2023-12-13 20:40:29');

-- --------------------------------------------------------

--
-- Table structure for table `product_list`
--

CREATE TABLE `product_list` (
  `id` int(11) NOT NULL,
  `productcode` varchar(250) NOT NULL,
  `supplierCode` int(11) NOT NULL,
  `productsupplier` varchar(250) NOT NULL,
  `productname` varchar(250) NOT NULL,
  `productcategory` varchar(250) NOT NULL,
  `productattributes` varchar(250) NOT NULL,
  `productunit` varchar(250) NOT NULL,
  `productprice` float NOT NULL,
  `datecreation` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `product_list`
--

INSERT INTO `product_list` (`id`, `productcode`, `supplierCode`, `productsupplier`, `productname`, `productcategory`, `productattributes`, `productunit`, `productprice`, `datecreation`) VALUES
(11, 'PRD-00001', 0, 'Blue Corner', 'Paper Vaper', 'Bond Paper', 'Color: Rainbow, Size: Long', 'Ream', 215, '2023-12-10 12:09:51'),
(13, 'PRD-00013', 8, 'National Bookstore', 'Manila Paper', 'Special Paper', 'Color: Chris Brown, Size: Mahaba', 'Pieces', 60, '2023-12-03 07:36:56'),
(14, 'PRD-00014', 0, 'Blue Corner', 'Paper Maker', 'Secret ', 'Color: Less, Size: Sakto lang', 'Box', 160, '2023-12-03 07:37:43'),
(15, 'PRD-00015', 8, 'National Bookstore', 'Ruler', 'Essentials ', 'Color: Ultra violet, Size: Isang dipa', 'Pieces', 170, '2023-12-03 07:38:38'),
(16, 'PRD-00016', 8, 'National Bookstore', 'Plastic Envelope', 'Basta', 'Color: Transparent, Size: Short', 'Bultuhan', 50, '2023-12-03 07:39:41'),
(17, 'PRD-00017', 7, 'Conqueror', 'Bond Paper', 'Papel', 'Color: White, Size: Mahaba', 'Ream', 250, '2023-12-03 07:42:28');

-- --------------------------------------------------------

--
-- Table structure for table `purchase_orders`
--

CREATE TABLE `purchase_orders` (
  `id` int(11) NOT NULL,
  `purchasecode` varchar(255) NOT NULL,
  `supplier` varchar(255) NOT NULL,
  `poSubtotal` decimal(10,2) NOT NULL,
  `poDiscount` decimal(10,2) NOT NULL,
  `poDiscountTotal` decimal(10,2) NOT NULL,
  `poTax` decimal(10,2) NOT NULL,
  `poTaxTotal` decimal(10,2) NOT NULL,
  `poGrandtotal` decimal(10,2) NOT NULL,
  `status` tinyint(4) NOT NULL,
  `dateCreated` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `purchase_orders`
--

INSERT INTO `purchase_orders` (`id`, `purchasecode`, `supplier`, `poSubtotal`, `poDiscount`, `poDiscountTotal`, `poTax`, `poTaxTotal`, `poGrandtotal`, `status`, `dateCreated`) VALUES
(32, 'PO-00001', 'Blue Corner', 1075.00, 0.00, 0.00, 0.00, 0.00, 1075.00, 1, '2023-12-13 02:26:42'),
(33, 'PO-00033', 'Blue Corner', 1075.00, 0.00, 0.00, 0.00, 0.00, 1075.00, 1, '2023-12-13 02:39:24'),
(35, 'PO-00034', 'Blue Corner', 1075.00, 0.00, 0.00, 0.00, 0.00, 1075.00, 1, '2023-12-13 02:46:41'),
(37, 'PO-00036', 'Blue Corner', 1075.00, 0.00, 0.00, 0.00, 0.00, 1075.00, 1, '2023-12-13 02:48:47'),
(38, 'PO-00038', 'National Bookstore', 1150.00, 0.00, 0.00, 0.00, 0.00, 1150.00, 1, '2023-12-13 12:40:29'),
(40, 'PO-00039', 'Blue Corner', 1075.00, 0.00, 0.00, 0.00, 0.00, 1075.00, 1, '2023-12-13 12:31:55'),
(41, 'PO-00041', 'Blue Corner', 1875.00, 0.00, 0.00, 0.00, 0.00, 1875.00, 1, '2023-12-13 12:40:08');

-- --------------------------------------------------------

--
-- Table structure for table `purchase_order_items`
--

CREATE TABLE `purchase_order_items` (
  `id` int(11) NOT NULL,
  `purchase_order_id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `productname` varchar(255) NOT NULL,
  `productunit` varchar(100) NOT NULL,
  `productprice` decimal(10,2) NOT NULL,
  `productattributes` varchar(255) NOT NULL,
  `productcategory` varchar(255) NOT NULL,
  `productquantity` int(11) NOT NULL,
  `productunitprice` decimal(10,2) NOT NULL,
  `poDiscount` decimal(10,2) NOT NULL,
  `poTax` decimal(10,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `purchase_order_items`
--

INSERT INTO `purchase_order_items` (`id`, `purchase_order_id`, `product_id`, `productname`, `productunit`, `productprice`, `productattributes`, `productcategory`, `productquantity`, `productunitprice`, `poDiscount`, `poTax`) VALUES
(42, 32, 11, 'Paper Vaper', 'Ream', 215.00, '', '', 5, 1075.00, 0.00, 0.00),
(43, 33, 11, 'Paper Vaper', 'Ream', 215.00, '', '', 5, 1075.00, 0.00, 0.00),
(44, 35, 11, 'Paper Vaper', 'Ream', 215.00, 'Color: Rainbow, Size: Long', 'Bond Paper', 5, 1075.00, 0.00, 0.00),
(46, 37, 11, 'Paper Vaper', 'Ream', 215.00, 'Color: Rainbow, Size: Long', 'Bond Paper', 5, 1075.00, 0.00, 0.00),
(47, 38, 13, 'Manila Paper', 'Pieces', 60.00, 'Color: Chris Brown, Size: Mahaba', 'Special Paper', 5, 300.00, 0.00, 0.00),
(48, 38, 15, 'Ruler', 'Pieces', 170.00, 'Color: Ultra violet, Size: Isang dipa', 'Essentials ', 5, 850.00, 0.00, 0.00),
(49, 40, 11, 'Paper Vaper', 'Ream', 215.00, 'Color: Rainbow, Size: Long', 'Bond Paper', 5, 1075.00, 0.00, 0.00),
(50, 41, 11, 'Paper Vaper', 'Ream', 215.00, 'Color: Rainbow, Size: Long', 'Bond Paper', 5, 1075.00, 0.00, 0.00),
(51, 41, 14, 'Paper Maker', 'Box', 160.00, 'Color: Less, Size: Sakto lang', 'Secret ', 5, 800.00, 0.00, 0.00);

-- --------------------------------------------------------

--
-- Table structure for table `return_orders`
--

CREATE TABLE `return_orders` (
  `id` int(11) NOT NULL,
  `returncode` varchar(255) NOT NULL,
  `supplier` varchar(255) NOT NULL,
  `subtotal` decimal(10,2) NOT NULL,
  `dateCreated` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `return_orders`
--

INSERT INTO `return_orders` (`id`, `returncode`, `supplier`, `subtotal`, `dateCreated`) VALUES
(4, 'RC-00001', 'National Bookstore', 2000.00, '2023-12-13 10:59:37');

-- --------------------------------------------------------

--
-- Table structure for table `return_order_items`
--

CREATE TABLE `return_order_items` (
  `id` int(11) NOT NULL,
  `return_order_id` int(11) DEFAULT NULL,
  `product_id` int(11) DEFAULT NULL,
  `productname` varchar(255) DEFAULT NULL,
  `productunit` varchar(255) DEFAULT NULL,
  `productprice` decimal(10,2) DEFAULT NULL,
  `productattributes` varchar(255) DEFAULT NULL,
  `productcategory` varchar(255) DEFAULT NULL,
  `productquantity` int(11) DEFAULT NULL,
  `productunitprice` decimal(10,2) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `return_order_items`
--

INSERT INTO `return_order_items` (`id`, `return_order_id`, `product_id`, `productname`, `productunit`, `productprice`, `productattributes`, `productcategory`, `productquantity`, `productunitprice`) VALUES
(2, 4, 13, 'Manila Paper', 'Pieces', 60.00, 'Color: Chris Brown, Size: Mahaba', 'Special Paper', 5, 300.00),
(3, 4, 15, 'Ruler', 'Pieces', 170.00, 'Color: Ultra violet, Size: Isang dipa', 'Essentials ', 10, 1700.00);

-- --------------------------------------------------------

--
-- Table structure for table `supplier_list`
--

CREATE TABLE `supplier_list` (
  `id` int(11) NOT NULL,
  `companyname` varchar(250) NOT NULL,
  `staffname` varchar(250) NOT NULL,
  `contactnumber` bigint(20) NOT NULL,
  `email` varchar(250) NOT NULL,
  `address` text NOT NULL,
  `creationdate` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `supplier_list`
--

INSERT INTO `supplier_list` (`id`, `companyname`, `staffname`, `contactnumber`, `email`, `address`, `creationdate`) VALUES
(6, 'Blue Corner', 'Gilmar Mandapat', 9202672314, 'mandapat.093204@marikina.sti.edu.ph', 'Fortune Marikina City', '2023-12-03 07:08:25'),
(7, 'Conqueror', 'Joyce Dizon', 9202672315, 'joyce@yahoo.com', 'Parang Marikina City', '2023-12-03 07:41:53'),
(8, 'National Bookstore', 'Mary Grace Mandapat', 924252615, 'mary@yahoo.com', 'Parang Marikina City', '2023-12-03 07:30:04');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `firstname` varchar(100) NOT NULL,
  `lastname` varchar(100) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `user_type` enum('Administrator','Staff') NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `firstname`, `lastname`, `username`, `password`, `user_type`, `created_at`) VALUES
(2, 'Gilmar ', 'Mandapat', 'Neimar', '$2y$10$u1qcOvG70h/wHGtOIrl8heA2QEiz2RHEWTVcQRwxx/sSZ4xSW6OBm', 'Administrator', '2023-12-11 15:01:50'),
(3, 'Gilmar ', 'Mandapat', 'Gilmar', '$2y$10$jNX1jZb47gF7pVz7howJsuQAAya4MfU0OZK/MbEyiUj06yteQirDy', 'Staff', '2023-12-11 17:52:22');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `adjustment_log`
--
ALTER TABLE `adjustment_log`
  ADD PRIMARY KEY (`id`),
  ADD KEY `product_id` (`product_id`);

--
-- Indexes for table `inventory`
--
ALTER TABLE `inventory`
  ADD PRIMARY KEY (`id`),
  ADD KEY `product_id` (`product_id`);

--
-- Indexes for table `product_list`
--
ALTER TABLE `product_list`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `purchase_orders`
--
ALTER TABLE `purchase_orders`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `purchasecode` (`purchasecode`);

--
-- Indexes for table `purchase_order_items`
--
ALTER TABLE `purchase_order_items`
  ADD PRIMARY KEY (`id`),
  ADD KEY `purchase_order_id` (`purchase_order_id`),
  ADD KEY `product_id` (`product_id`);

--
-- Indexes for table `return_orders`
--
ALTER TABLE `return_orders`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `return_order_items`
--
ALTER TABLE `return_order_items`
  ADD PRIMARY KEY (`id`),
  ADD KEY `return_order_id` (`return_order_id`),
  ADD KEY `product_id` (`product_id`);

--
-- Indexes for table `supplier_list`
--
ALTER TABLE `supplier_list`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `adjustment_log`
--
ALTER TABLE `adjustment_log`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `inventory`
--
ALTER TABLE `inventory`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;

--
-- AUTO_INCREMENT for table `product_list`
--
ALTER TABLE `product_list`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

--
-- AUTO_INCREMENT for table `purchase_orders`
--
ALTER TABLE `purchase_orders`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=42;

--
-- AUTO_INCREMENT for table `purchase_order_items`
--
ALTER TABLE `purchase_order_items`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=52;

--
-- AUTO_INCREMENT for table `return_orders`
--
ALTER TABLE `return_orders`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `return_order_items`
--
ALTER TABLE `return_order_items`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `supplier_list`
--
ALTER TABLE `supplier_list`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `adjustment_log`
--
ALTER TABLE `adjustment_log`
  ADD CONSTRAINT `adjustment_log_ibfk_1` FOREIGN KEY (`product_id`) REFERENCES `inventory` (`product_id`);

--
-- Constraints for table `inventory`
--
ALTER TABLE `inventory`
  ADD CONSTRAINT `inventory_ibfk_1` FOREIGN KEY (`product_id`) REFERENCES `product_list` (`id`);

--
-- Constraints for table `purchase_order_items`
--
ALTER TABLE `purchase_order_items`
  ADD CONSTRAINT `purchase_order_items_ibfk_1` FOREIGN KEY (`purchase_order_id`) REFERENCES `purchase_orders` (`id`),
  ADD CONSTRAINT `purchase_order_items_ibfk_2` FOREIGN KEY (`product_id`) REFERENCES `product_list` (`id`);

--
-- Constraints for table `return_order_items`
--
ALTER TABLE `return_order_items`
  ADD CONSTRAINT `return_order_items_ibfk_1` FOREIGN KEY (`return_order_id`) REFERENCES `return_orders` (`id`),
  ADD CONSTRAINT `return_order_items_ibfk_2` FOREIGN KEY (`product_id`) REFERENCES `product_list` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
