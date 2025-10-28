-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Oct 28, 2025 at 02:04 AM
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
-- Database: `reservation_db`
--

-- --------------------------------------------------------

--
-- Table structure for table `bookings`
--

CREATE TABLE `bookings` (
  `id` int(10) NOT NULL,
  `user_id` varchar(20) NOT NULL,
  `booking_id` varchar(20) NOT NULL,
  `status` varchar(50) NOT NULL,
  `pickup_location` varchar(255) NOT NULL,
  `destination` varchar(255) NOT NULL,
  `pickup_date` date NOT NULL,
  `pickup_time` time NOT NULL,
  `vehicle_name` varchar(100) NOT NULL,
  `vehicle_price` decimal(10,2) NOT NULL,
  `passenger_name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `phone` varchar(50) NOT NULL,
  `payment_method` enum('Cash','Credit Card','QR','Bank Transfer') NOT NULL,
  `payment_status` varchar(50) NOT NULL,
  `tourist_guide` tinyint(1) NOT NULL,
  `luggage_assistance` tinyint(1) NOT NULL,
  `total_amount` decimal(10,2) NOT NULL,
  `created_at` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `bookings`
--

INSERT INTO `bookings` (`id`, `user_id`, `booking_id`, `status`, `pickup_location`, `destination`, `pickup_date`, `pickup_time`, `vehicle_name`, `vehicle_price`, `passenger_name`, `email`, `phone`, `payment_method`, `payment_status`, `tourist_guide`, `luggage_assistance`, `total_amount`, `created_at`) VALUES
(1, '3', 'BK2025-0307', 'Confirmed', 'Kedah', 'Selangor', '2025-11-06', '14:08:00', 'MPV', 80.00, 'intan', 'ninaasofi5@gmail.com', '011-35619291', 'QR', 'Confirmed', 1, 0, 180.00, '2025-10-25 14:08:34'),
(2, '4', 'BK2025-7640', 'Confirmed', 'Pulau Pinang', 'Perlis', '2025-10-21', '23:53:00', 'Bus Persiaran', 200.00, 'liza', 'sofiza73@yahoo.com', '013-4750505', 'QR', 'Confirmed', 1, 0, 300.00, '2025-10-25 23:53:26'),
(3, '5', 'BK2025-6857', 'Confirmed', 'kedah', 'perlis', '2025-10-30', '01:35:00', 'Sedan Car', 350.00, 'nina', 'qisnina@gmail.com', '011-12279925', 'QR', 'Confirmed', 0, 0, 350.00, '2025-10-27 01:35:02'),
(4, '', 'BK2025-5593', 'Confirmed', 'ipoh', 'kl', '2025-10-30', '12:05:00', 'bus', 600.00, 'hurul', 'hurulain@gmail.com', '014-8746135', 'Bank Transfer', '0', 0, 1, 650.00, '2025-10-27 12:05:47'),
(7, '8', 'BK2025-5199', 'Confirmed', 'Jalan Taman Indah', 'Quinsbay, Pulau Pinang', '2025-10-31', '08:28:00', 'Sedan Car', 350.00, 'ahmad', 'ahmad14@gmail.com', '013-2334567', 'Bank Transfer', '0', 1, 1, 500.00, '2025-10-28 08:28:54');

-- --------------------------------------------------------

--
-- Table structure for table `contact_messages`
--

CREATE TABLE `contact_messages` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `subject` varchar(255) NOT NULL,
  `message` text NOT NULL,
  `submitted_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `contact_messages`
--

INSERT INTO `contact_messages` (`id`, `name`, `email`, `subject`, `message`, `submitted_at`) VALUES
(1, 'nina', 'ninaasofi5@gmail.com', 'web programming', 'qwerty', '2025-10-26 17:40:28'),
(2, 'intan', 'qisnina@gmail.com', 'web programming', 'intan nnn', '2025-10-26 17:44:37'),
(3, 'liza', 'sofiza73@yahoo.com', 'qwerty', 'asdfg', '2025-10-26 17:45:10');

-- --------------------------------------------------------

--
-- Table structure for table `settings`
--

CREATE TABLE `settings` (
  `id` int(11) NOT NULL DEFAULT 1,
  `company_name` varchar(255) NOT NULL DEFAULT 'Global Sunrise Transport',
  `contact_email` varchar(255) NOT NULL DEFAULT 'info@globalsunrise.com',
  `phone_number` varchar(50) DEFAULT '+60 12-345 6789',
  `website_url` varchar(255) DEFAULT 'https://globalsunrise.com',
  `company_address` text DEFAULT NULL,
  `advance_booking_days` int(11) NOT NULL DEFAULT 30,
  `cancellation_notice_hours` int(11) NOT NULL DEFAULT 24,
  `booking_confirmation_mode` enum('auto','manual') NOT NULL DEFAULT 'manual',
  `allow_overbooking` tinyint(1) NOT NULL DEFAULT 0,
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `email` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `name` varchar(255) NOT NULL,
  `contact_number` varchar(20) NOT NULL,
  `role` enum('customer','admin') NOT NULL,
  `profile_picture` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `email`, `password`, `name`, `contact_number`, `role`, `profile_picture`) VALUES
(1, 'user@user.com', '$2a$12$jB/u0bslWQxlbXkDhfqNQOFOD15tPQoeqImyxHGuynlsU3GbszCjC', 'user', '', 'customer', NULL),
(2, 'admin@admin.com', '$2y$10$8QnGvw0tGPgguuaxFOAc7OdA8HkQDo1XbnPtzGH16OhTlSPPlLHgu', 'admin', '', 'admin', NULL),
(3, 'ninaasofi5@gmail.com', '$2y$10$F8jTte80FERXemfPX1CSZeal5Owlt0hQxjoAXRlIvnrZarkWTThUq', 'intan', '', 'customer', NULL),
(4, 'sofiza73@yahoo.com', '$2y$10$o.kocbsAh2v4X8XIiyYOhO3Kxk644miPsHTal.VtL6LYuCMvDxPTy', 'liza', '', 'customer', NULL),
(5, 'qisnina@gmail.com', '$2y$10$R1gZdJRKZ32UUwW2umglWOiHBmGcIt.08ncCAIUag7dxqlzGRbTje', 'nina', '', 'customer', NULL),
(6, 'ikhmal@gmail.com', '$2y$10$2OGJNNi5eAp.UiF/r9ykHezRWzW.Cdu1FAbBZwuMMvHJ5/z1PK2He', 'ikhmal', '', 'customer', NULL),
(7, 'hurulain@gmail.com', '$2y$10$MvSndYk4zS714qS6ykmwEOKAu9q5WbrK3f2bhDVd7pgACKo6tkDtO', 'hurul', '', 'customer', NULL),
(8, 'ahmad14@gmail.com', '$2y$10$aYCVi7tbP9ZHe8G.hBnqX.0VzN0ZJ/ou6YrlNNlCIEp/.uS/QqgtK', 'ahmad', '', 'customer', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `vehicles`
--

CREATE TABLE `vehicles` (
  `vehicle_id` int(11) NOT NULL,
  `category` varchar(50) NOT NULL,
  `seater` int(11) NOT NULL,
  `price` decimal(10,2) NOT NULL,
  `status` enum('available','unavailable','maintenance','unknown') NOT NULL DEFAULT 'available',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `vehicles`
--

INSERT INTO `vehicles` (`vehicle_id`, `category`, `seater`, `price`, `status`, `created_at`, `updated_at`) VALUES
(2, 'MPV', 7, 80.00, 'unavailable', '2025-10-14 06:41:43', '2025-10-14 06:41:43'),
(3, 'Alphard', 5, 150.00, 'unavailable', '2025-10-14 06:42:31', '2025-10-14 06:42:31'),
(4, 'Van', 14, 150.00, 'unavailable', '2025-10-14 06:42:54', '2025-10-26 17:58:14'),
(5, 'Bus Persiaran', 44, 200.00, 'available', '2025-10-14 06:43:16', '2025-10-14 06:43:16'),
(8, 'Sedan', 4, 80.00, 'maintenance', '2025-10-28 00:43:48', '2025-10-28 00:43:48');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `bookings`
--
ALTER TABLE `bookings`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_status` (`status`);

--
-- Indexes for table `contact_messages`
--
ALTER TABLE `contact_messages`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Indexes for table `vehicles`
--
ALTER TABLE `vehicles`
  ADD PRIMARY KEY (`vehicle_id`),
  ADD KEY `idx_category` (`category`),
  ADD KEY `idx_status` (`status`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `bookings`
--
ALTER TABLE `bookings`
  MODIFY `id` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `contact_messages`
--
ALTER TABLE `contact_messages`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `vehicles`
--
ALTER TABLE `vehicles`
  MODIFY `vehicle_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
