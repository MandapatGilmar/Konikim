-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Dec 06, 2023 at 04:37 PM
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
-- Table structure for table `inventory_list`
--

CREATE TABLE `inventory_list` (
  `id` int(11) NOT NULL,
  `po_id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `supplier` varchar(255) NOT NULL,
  `productname` varchar(255) NOT NULL,
  `productunit` varchar(255) NOT NULL,
  `productattributes` varchar(255) NOT NULL,
  `productprice` decimal(10,2) NOT NULL,
  `productquantity` int(11) NOT NULL,
  `unitprice` decimal(10,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `inventory_list`
--

INSERT INTO `inventory_list` (`id`, `po_id`, `product_id`, `supplier`, `productname`, `productunit`, `productattributes`, `productprice`, `productquantity`, `unitprice`) VALUES
(13, 26, 0, 'Blue Corner', 'Paper Vaper', 'Ream', 'Color: Rainbow, Size: Long', 200.00, 5, 0.00),
(14, 35, 0, 'National Bookstore', 'Manila Paper', 'Pieces', 'Color: Chris Brown, Size: Mahaba', 60.00, 5, 0.00),
(15, 36, 0, 'National Bookstore', 'Ruler', 'Pieces', 'Color: Ultra violet, Size: Isang dipa', 170.00, 5, 0.00);

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
(11, 'PRD-00001', 0, 'Blue Corner', 'Paper Vaper', 'Bond Paper', 'Color: Rainbow, Size: Long', 'Ream', 200, '2023-12-03 07:31:44'),
(13, 'PRD-00013', 8, 'National Bookstore', 'Manila Paper', 'Special Paper', 'Color: Chris Brown, Size: Mahaba', 'Pieces', 60, '2023-12-03 07:36:56'),
(14, 'PRD-00014', 0, 'Blue Corner', 'Paper Maker', 'Secret ', 'Color: Less, Size: Sakto lang', 'Box', 160, '2023-12-03 07:37:43'),
(15, 'PRD-00015', 8, 'National Bookstore', 'Ruler', 'Essentials ', 'Color: Ultra violet, Size: Isang dipa', 'Pieces', 170, '2023-12-03 07:38:38'),
(16, 'PRD-00016', 8, 'National Bookstore', 'Plastic Envelope', 'Basta', 'Color: Transparent, Size: Short', 'Bultuhan', 50, '2023-12-03 07:39:41'),
(17, 'PRD-00017', 7, 'Conqueror', 'Bond Paper', 'Papel', 'Color: White, Size: Mahaba', 'Ream', 250, '2023-12-03 07:42:28');

-- --------------------------------------------------------

--
-- Table structure for table `purchase_order_list`
--

CREATE TABLE `purchase_order_list` (
  `id` int(11) NOT NULL,
  `purchasecode` varchar(255) NOT NULL,
  `supplier` varchar(255) DEFAULT NULL,
  `product_id` varchar(255) DEFAULT NULL,
  `productname` varchar(255) NOT NULL,
  `productunit` varchar(255) DEFAULT NULL,
  `productcategory` varchar(255) DEFAULT NULL,
  `productattributes` text DEFAULT NULL,
  `productprice` decimal(10,2) DEFAULT NULL,
  `productquantity` int(11) DEFAULT NULL,
  `productunitprice` decimal(10,2) NOT NULL,
  `poDiscount` decimal(5,2) DEFAULT NULL,
  `poTax` decimal(5,2) DEFAULT NULL,
  `poSubtotal` decimal(10,2) DEFAULT NULL,
  `poGrandtotal` decimal(10,2) DEFAULT NULL,
  `poDiscountTotal` decimal(10,2) DEFAULT NULL,
  `poTaxTotal` decimal(10,2) DEFAULT NULL,
  `status` tinyint(4) NOT NULL,
  `dateCreated` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `purchase_order_list`
--

INSERT INTO `purchase_order_list` (`id`, `purchasecode`, `supplier`, `product_id`, `productname`, `productunit`, `productcategory`, `productattributes`, `productprice`, `productquantity`, `productunitprice`, `poDiscount`, `poTax`, `poSubtotal`, `poGrandtotal`, `poDiscountTotal`, `poTaxTotal`, `status`, `dateCreated`) VALUES
(46, 'PO-00001', 'Blue Corner', '11', 'Paper Vaper', 'Ream', 'Bond Paper', 'Color: Rainbow, Size: Long', 200.00, 5, 1000.00, 4.00, 3.00, 1000.00, 988.80, 40.00, 28.80, 0, '2023-12-05 16:27:54'),
(47, 'PO-00047', 'Blue Corner', '11', 'Paper Vaper', 'Ream', 'Bond Paper', 'Color: Rainbow, Size: Long', 200.00, 5, 1000.00, 0.00, 0.00, 1000.00, 1000.00, 0.00, 0.00, 0, '2023-12-06 07:43:42');

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
(8, 'National Bookstore', 'Mary Grace Mandapat', 924252615, 'mary@yahoo.com', 'Parang Marikina City', '2023-12-03 07:30:04'),
(9, 'STI COLLEGE MARIKINA', 'Mike Bertiz', 28319319023, 'awfawf@yahoo.com', 'Concepcion uno', '2023-12-05 08:33:38');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `employee_name` varchar(100) DEFAULT NULL,
  `user_level` enum('Administrator','Staff') NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `username`, `password`, `employee_name`, `user_level`) VALUES
(6, 'AdminGilmar', '$2y$10$S5rjjf4yQwtdXXsYgn5N3OF4kNVM6eDihNrT4kwNmBxVxqhQGgM2u', 'Gilmar Mandapat', 'Administrator'),
(9, 'Marmar', '$2y$10$sfUldvUqer8nXKGR/TYXXeuOCXmYRpiRplu4gwwVENFbs9WprFJn2', 'Gilmar Mandapat ', 'Staff');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `inventory_list`
--
ALTER TABLE `inventory_list`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `product_list`
--
ALTER TABLE `product_list`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `purchase_order_list`
--
ALTER TABLE `purchase_order_list`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `supplier_list`
--
ALTER TABLE `supplier_list`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `inventory_list`
--
ALTER TABLE `inventory_list`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT for table `product_list`
--
ALTER TABLE `product_list`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

--
-- AUTO_INCREMENT for table `purchase_order_list`
--
ALTER TABLE `purchase_order_list`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=48;

--
-- AUTO_INCREMENT for table `supplier_list`
--
ALTER TABLE `supplier_list`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
