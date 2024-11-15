-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Aug 28, 2024 at 07:51 AM
-- Server version: 10.4.28-MariaDB
-- PHP Version: 8.0.28

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `bwsdb`
--

-- --------------------------------------------------------

--
-- Table structure for table `services`
--

CREATE TABLE `services` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `category_id` int(11) DEFAULT NULL,
  `image` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `services`
--

INSERT INTO `services` (`id`, `name`, `description`, `category_id`, `image`) VALUES
(11, 'Swedish Massage', 'abot', 12, '../bws_ui/services_images/uploads/WIN_20230322_20_00_09_Pro.jpg'),
(12, 'hilotin', 'tseterte', 12, '../bws_ui/services_images/uploads/WIN_20230322_20_00_09_Pro.jpg');

-- --------------------------------------------------------

--
-- Table structure for table `service_categories`
--

CREATE TABLE `service_categories` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `image` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `service_categories`
--

INSERT INTO `service_categories` (`id`, `name`, `description`, `image`) VALUES
(12, 'Massage Services', 'Enjoy a classic Swedish massage that utilizes long, flowing strokes to enhance circulation, reduce muscle tension, and promote relaxation. Perfect for those seeking a gentle and soothing experience.', '../bws_ui/images/uploadsmassage.jpg'),
(13, 'foot services', 'Enjoy a relaxing foot massage that targets key areas to relieve tension and improve circulation. Our skilled therapists use a combination of techniques and soothing oils to provide a calming and restorative experience.', '../bws_ui/images/uploadsfoot.jpg'),
(14, 'Facial Servives', 'Experience a timeless classic facial that includes cleansing, exfoliation, extraction, and a soothing mask. This treatment is perfect for maintaining healthy, glowing skin and is suitable for all skin types.', '../bws_ui/images/uploadsfacial.jpg'),
(15, 'Pregnancy Massage', 'Experience comfort and relief with our pregnancy massage, specifically designed to ease pregnancy-related discomforts such as back pain, swelling, and fatigue.', '../bws_ui/images/uploadsbontit.jpg'),
(16, 'Massage Services', 'zsxzxzx', '../bws_ui/images/uploadsc_1.jpg');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `services`
--
ALTER TABLE `services`
  ADD PRIMARY KEY (`id`),
  ADD KEY `category_id` (`category_id`);

--
-- Indexes for table `service_categories`
--
ALTER TABLE `service_categories`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `services`
--
ALTER TABLE `services`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `service_categories`
--
ALTER TABLE `service_categories`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `services`
--
ALTER TABLE `services`
  ADD CONSTRAINT `services_ibfk_1` FOREIGN KEY (`category_id`) REFERENCES `service_categories` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
