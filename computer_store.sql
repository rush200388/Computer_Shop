-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Sep 17, 2025 at 10:55 AM
-- Server version: 8.0.43
-- PHP Version: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `computer_store`
--

-- --------------------------------------------------------

--
-- Table structure for table `admins`
--

CREATE TABLE `admins` (
  `id` int NOT NULL,
  `name` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `admins`
--

INSERT INTO `admins` (`id`, `name`, `email`, `password`, `created_at`) VALUES
(1, 'rikka', 'ravindusandeepa000@gmail.com', '$2y$10$40FQZfE4p4jOoN2dBnQRSeZ2Q7yUQgTQzSO9aYeXP.dhQm8K9Es6a', '2025-09-11 09:38:50');

-- --------------------------------------------------------

--
-- Table structure for table `cart`
--

CREATE TABLE `cart` (
  `id` int NOT NULL,
  `user_id` int NOT NULL,
  `product_id` int NOT NULL,
  `quantity` int DEFAULT '1',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `orders`
--

CREATE TABLE `orders` (
  `id` int NOT NULL,
  `user_id` int DEFAULT NULL,
  `customer_name` varchar(255) DEFAULT NULL,
  `address` text,
  `phone` varchar(20) DEFAULT NULL,
  `total` decimal(10,2) DEFAULT NULL,
  `status` varchar(50) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `orders`
--

INSERT INTO `orders` (`id`, `user_id`, `customer_name`, `address`, `phone`, `total`, `status`, `created_at`) VALUES
(1, 2, 'thiththalapitige Ravindu Peiris', '338/A Kamaragoda,Dewalapola', '0710929859', 300000.00, 'Completed', '2025-09-16 17:31:04'),
(3, 2, 'thiththalapitige Ravindu Peiris', '338/A Kamaragoda,Dewalapola', '0710929859', 300000.00, 'Completed', '2025-09-16 17:38:12'),
(4, 2, 'thiththalapitige Ravindu Peiris', '338/A Kamaragoda,Dewalapola', '0710929859', 3456666.00, 'Completed', '2025-09-16 17:43:13'),
(5, 2, 'thiththalapitige Ravindu Peiris', '338/A Kamaragoda,Dewalapola', '0710929859', 23000.00, 'Completed', '2025-09-17 04:47:10');

-- --------------------------------------------------------

--
-- Table structure for table `order_items`
--

CREATE TABLE `order_items` (
  `id` int NOT NULL,
  `order_id` int DEFAULT NULL,
  `product_id` int DEFAULT NULL,
  `quantity` int DEFAULT NULL,
  `price` decimal(10,2) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `order_items`
--

INSERT INTO `order_items` (`id`, `order_id`, `product_id`, `quantity`, `price`) VALUES
(1, 1, 23, 1, 300000.00),
(2, 3, 23, 1, 300000.00),
(3, 4, 22, 1, 3456666.00),
(4, 5, 21, 1, 23000.00);

-- --------------------------------------------------------

--
-- Table structure for table `products`
--

CREATE TABLE `products` (
  `id` int NOT NULL,
  `name` varchar(100) NOT NULL,
  `price` decimal(10,2) NOT NULL,
  `stock` int NOT NULL,
  `image` varchar(255) DEFAULT NULL,
  `category` varchar(100) DEFAULT NULL,
  `brand` varchar(50) DEFAULT NULL,
  `type` varchar(50) DEFAULT NULL,
  `description` text,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `products`
--

INSERT INTO `products` (`id`, `name`, `price`, `stock`, `image`, `category`, `brand`, `type`, `description`, `created_at`) VALUES
(25, 'MSI L22i-40 21.5', 2700000.00, 10, '1758084993_c7dbc3d7ee0b52bb05696e4943222a4c.png', 'Brand New', 'MSI', 'Monitors', 'BRAND\r\nLenovo\r\nDISPLAY SIZE\r\n21.5\'\'\r\nPANEL TYPE\r\nIPS Technology\r\nRESOLUTION\r\n1920 x 1080\r\nRESOLUTION TYPE\r\nFHD (Full HD)\r\nREFRESH RATE\r\n75Hz\r\nPORTS\r\nVGA: 1 -HDMI 1.4: 1 -Power in: DC Socket\r\nWARRANTY\r\n03 Years Hardware Warranty', '2025-09-17 04:56:33'),
(26, 'Asus VZ22EHE 22\'\' IPS 75HZ Eye Care Monitor', 29000.00, 7, '1758085174_6e4434b17af3ab1e7bd9a4894b4f37f3.png', 'Brand New', 'Asus', 'Monitors', 'BRAND Lenovo DISPLAY SIZE 21.5\'\' PANEL TYPE IPS Technology RESOLUTION 1920 x 1080 RESOLUTION TYPE FHD (Full HD) REFRESH RATE 75Hz PORTS VGA: 1 -HDMI 1.4: 1 -Power in: DC Socket WARRANTY 03 Years Hardware Warranty', '2025-09-17 04:59:34'),
(27, 'Acer EK251Q E 24.5\" 120Hz Super Comfort EK1 Monitor', 30000.00, 13, '1758085225_8c3e295451dc89112332627a9ed1ffe3.png', 'Brand New', 'Asus', 'Monitors', 'BRAND Lenovo DISPLAY SIZE 21.5\'\' PANEL TYPE IPS Technology RESOLUTION 1920 x 1080 RESOLUTION TYPE FHD (Full HD) REFRESH RATE 75Hz PORTS VGA: 1 -HDMI 1.4: 1 -Power in: DC Socket WARRANTY 03 Years Hardware Warranty', '2025-09-17 05:00:25'),
(28, 'Asus TUF Gaming VG249Q3R 23.8” Inch FHD IPS 180Hz 1ms Amd Freesync Frameless Monitor', 55000.00, 6, '1758085295_f384a62bb0e81f2fb3a0e3e2e8db5632.png', 'Brand New', 'Asus', 'Monitors', 'BRAND ASUS DISPLAY SIZE 21.5\'\' PANEL TYPE IPS Technology RESOLUTION 1920 x 1080 RESOLUTION TYPE FHD (Full HD) REFRESH RATE 75Hz PORTS VGA: 1 -HDMI 1.4: 1 -Power in: DC Socket WARRANTY 03 Years Hardware Warranty', '2025-09-17 05:01:35'),
(29, 'ASUS ROG Strix XG49WCR 49\" Double QHD USB-C 165HZ OC Gaming Monitor', 160000.00, 13, '1758085487_21393937dfea1f721816ef1259ce3466.png', 'Brand New', 'Asus', 'Monitors', '03 YEARS WARRANTY\r\n\r\nMODEL :- ROG Strix XG49WCR\r\n\r\nDISPLAY :- \r\nPanel Size (inch) : 49\r\nAspect Ratio : 32:9\r\nDisplay Viewing Area (H x V) : 1191.936 (H) x 335.232 (V) mm\r\nDisplay Surface : Anti-Glare\r\nBacklight Type : LED\r\nPanel Type : VA\r\nViewing Angle (CR≧10, H/V) : 178°/ 178°\r\nCurvature : 1800R\r\nPixel Pitch : 0.233mm\r\nResolution : 5120x1440\r\nColor Space (sRGB) : 120%\r\nColor Space (DCI-P3) : 90%\r\nBrightness (HDR, Peak) : 550 cd/㎡\r\nBrightness (Typ.) : 450cd/㎡\r\nContrast Ratio (Typ.) : 3000:1\r\nDisplay Colors : 1073.7M (10 bit)\r\nResponse Time : 1ms MPRT\r\nRefresh Rate (Max) : 165Hz\r\nFlicker-free : Yes\r\n', '2025-09-17 05:04:47'),
(30, 'MSI MAG 274QRFW 27\" Inch WQHD (2560 X 1440) 180HZ IPS AMD FreeSync White Gaming Monitor', 109000.00, 5, '1758085569_49f80980454fcbc01a54d3d56867c80f.png', 'Brand New', 'MSI', 'Monitors', '03 YEARS WARRANTY\r\n', '2025-09-17 05:06:09'),
(31, 'KOORUI 27\" G2711P 200Hz IPS FHD HDR400 Display 90% DCI-P3', 55000.00, 8, '1758085629_82899ef50c072890b44fe4f3ab15a917.png', 'Brand New', 'MSI', 'Monitors', 'MODEL\r\nG2711P\r\nDISPLAY SIZE\r\n27 inches\r\nPANEL TYPE\r\nIPS\r\nRESOLUTION\r\n1920×1080 (Full HD), 16:9 aspect ratio\r\nREFRESH RATE\r\n200Hz\r\nRESPONSE TIME\r\n1ms (MPRT)\r\nBRIGHTNESS\r\n350 cd/m²\r\nCONTRAST RATIO\r\n1000:1 (static), 20,000,000:1 (dynamic)\r\nVIEWING ANGLES\r\n178° horizontal / 178° vertical', '2025-09-17 05:07:09'),
(32, 'Dell 22', 18000.00, 8, '1758085753_f9a96868d6adbc91496ef1e87c465508.png', 'Brand New', 'MSI', 'Monitors', '03 MONTHS WARRANTY\r\n\r\nProduct Specification\r\n*	Display: 21.5\" Full HD (1920 x 1080) resolution with antiglare LED panel.\r\n*	Response Time: 6 ms GTG.\r\n*	Refresh Rate: 60 Hz.\r\n*	Inputs: HDMI, VGA, and DisplayPort.\r\n*	USB Ports: 2 USB 2.0 ports.', '2025-09-17 05:09:13'),
(33, 'Redmi 13 8/256 GB pink', 50000.00, 8, '1758085962_cb9c8d8313df042a819919c8b502f826.png', 'Brand New', 'Apple', 'Mobile', '1year warantiy', '2025-09-17 05:12:42'),
(34, 'I phone 15 pro', 360000.00, 6, '1758086046_14d7aaedd6a41bddd1fabd4d39a14436.png', 'Brand New', 'Apple', 'Mobile', '1year warantiy', '2025-09-17 05:14:06'),
(35, 'Redmi 14', 56000.00, 5, '1758086090_743cacef87083996146f9c4594f460d8.png', 'Brand New', 'Apple', 'Mobile', '1 year warantiy', '2025-09-17 05:14:50'),
(36, 'Smart watch', 15000.00, 7, '1758086141_4afe285dd29ee292fcd34d28b21ca1d2.png', 'Brand New', 'Apple', 'Mobile', '1 year waranties', '2025-09-17 05:15:41'),
(37, 'Asus pro book ', 67000.00, 8, '1758086417_2fef4b5ec309faa2ec7fb19539a28461.png', 'Brand New', 'Asus', 'Laptop', '3 years waranty', '2025-09-17 05:20:17'),
(38, 'Asus mate', 90000.00, 7, '1758086463_d51f6fd2519926dcd1af8da6bee9b56b.png', 'Brand New', 'Asus', 'Laptop', '3 years waranty', '2025-09-17 05:21:03');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int NOT NULL,
  `name` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `mobile` varchar(20) DEFAULT NULL,
  `address` varchar(255) DEFAULT NULL,
  `birthday` date DEFAULT NULL,
  `profile_photo` varchar(255) DEFAULT NULL,
  `password` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `reset_token` varchar(255) DEFAULT NULL,
  `reset_expires` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `mobile`, `address`, `birthday`, `profile_photo`, `password`, `created_at`, `reset_token`, `reset_expires`) VALUES
(2, 'ravindu', 'ravindusandeepa000@gmail.com', '0779112435', '338/A Kamaragoda,Dewalapola', NULL, NULL, '$2y$10$c4u3Gcw.X3jIp7Y5AlQpiOOSoskyjWcXOr7L8ch/cspMAPIXG6smi', '2025-09-13 11:05:33', 'a2c7b64dd7e3ddd1d1ebc1e2f24c7b8fa83218c88753b6198a9b52c2884ebbb7b6b12fb133aa6ea313a998e23cd34ca06e0f', '2025-09-16 13:01:30'),
(3, 'rikka', 'thumodyasandeepani000@gmail.com', '0743093277', NULL, NULL, NULL, '$2y$10$pMC4bItYCragMqoNmhWJ2uAVThfFakGEXoW4fWz7wSeIG0.hAlUOy', '2025-09-16 06:36:37', '7fc54488dce5fe090c8566afbc541004c66c1644459ad98cdc7abe65a92ac92cfcd3b75dee01e42ec05ef7e4942e893b5d54', '2025-09-16 13:55:33');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `admins`
--
ALTER TABLE `admins`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Indexes for table `cart`
--
ALTER TABLE `cart`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `product_id` (`product_id`);

--
-- Indexes for table `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `order_items`
--
ALTER TABLE `order_items`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `products`
--
ALTER TABLE `products`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `admins`
--
ALTER TABLE `admins`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `cart`
--
ALTER TABLE `cart`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=31;

--
-- AUTO_INCREMENT for table `orders`
--
ALTER TABLE `orders`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `order_items`
--
ALTER TABLE `order_items`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `products`
--
ALTER TABLE `products`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=39;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `cart`
--
ALTER TABLE `cart`
  ADD CONSTRAINT `cart_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `cart_ibfk_2` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
