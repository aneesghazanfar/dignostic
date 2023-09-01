-- phpMyAdmin SQL Dump
-- version 5.0.4
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jul 19, 2023 at 02:32 PM
-- Server version: 10.4.16-MariaDB
-- PHP Version: 7.4.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `diagnostic`
--

-- --------------------------------------------------------

--
-- Table structure for table `center_test_cat`
--

CREATE TABLE `center_test_cat` (
  `id` int(11) NOT NULL,
  `center_id` int(11) NOT NULL,
  `test_type` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `center_test_cat`
--

INSERT INTO `center_test_cat` (`id`, `center_id`, `test_type`) VALUES
(1, 1, 1);

-- --------------------------------------------------------

--
-- Table structure for table `diagnostic_center`
--

CREATE TABLE `diagnostic_center` (
  `id` int(11) NOT NULL,
  `name` varchar(50) NOT NULL,
  `location` varchar(250) NOT NULL,
  `phone_no` varchar(30) NOT NULL,
  `email` varchar(40) NOT NULL,
  `rating` int(11) NOT NULL DEFAULT 0,
  `rate_no` int(11) NOT NULL,
  `user_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `diagnostic_center`
--

INSERT INTO `diagnostic_center` (`id`, `name`, `location`, `phone_no`, `email`, `rating`, `rate_no`, `user_id`) VALUES
(1, 'Iqbal Madical', 'Jauharabad', '1235478', 'iqbal_madical@gmail.com', 4, 1, 3);

-- --------------------------------------------------------

--
-- Table structure for table `feedback`
--

CREATE TABLE `feedback` (
  `id` int(11) NOT NULL,
  `sender_id` int(11) NOT NULL,
  `receiver_id` int(11) NOT NULL,
  `message` varchar(5000) NOT NULL,
  `timestamp` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `feedback`
--

INSERT INTO `feedback` (`id`, `sender_id`, `receiver_id`, `message`, `timestamp`) VALUES
(1, 2, 3, 'every thing is ok', '2023-07-19 12:24:43'),
(2, 3, 2, 'thanks', '2023-07-19 12:29:29');

-- --------------------------------------------------------

--
-- Table structure for table `invoice`
--

CREATE TABLE `invoice` (
  `id` int(11) NOT NULL,
  `test_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `invoices_path` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `invoice`
--

INSERT INTO `invoice` (`id`, `test_id`, `user_id`, `invoices_path`) VALUES
(1, 2, 2, '64b7d2f78924c.pdf');

-- --------------------------------------------------------

--
-- Table structure for table `messages`
--

CREATE TABLE `messages` (
  `id` int(11) NOT NULL,
  `sender_id` int(11) NOT NULL,
  `receiver_id` int(11) NOT NULL,
  `message` varchar(5000) NOT NULL,
  `timestamp` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `messages`
--

INSERT INTO `messages` (`id`, `sender_id`, `receiver_id`, `message`, `timestamp`) VALUES
(1, 2, 3, 'first masage to dc manager from patient', '2023-07-19 11:58:49'),
(2, 3, 2, 'replay from dc manager to patient', '2023-07-19 12:05:28'),
(3, 2, 1, 'massage to admin', '2023-07-19 12:30:16'),
(6, 1, 2, 'reply from admin', '2023-07-19 12:31:43');

-- --------------------------------------------------------

--
-- Table structure for table `patient_rating`
--

CREATE TABLE `patient_rating` (
  `id` int(11) NOT NULL,
  `center_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `rating` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `patient_rating`
--

INSERT INTO `patient_rating` (`id`, `center_id`, `user_id`, `rating`) VALUES
(1, 1, 2, 4);

-- --------------------------------------------------------

--
-- Table structure for table `reports`
--

CREATE TABLE `reports` (
  `id` int(11) NOT NULL,
  `test_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `reports_path` varchar(200) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `reports`
--

INSERT INTO `reports` (`id`, `test_id`, `user_id`, `reports_path`) VALUES
(1, 2, 2, '64b7d3cf8ab60.pdf');

-- --------------------------------------------------------

--
-- Table structure for table `test_categories`
--

CREATE TABLE `test_categories` (
  `id` int(11) NOT NULL,
  `type` varchar(15) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `test_categories`
--

INSERT INTO `test_categories` (`id`, `type`) VALUES
(1, 'blood ');

-- --------------------------------------------------------

--
-- Table structure for table `test_detail`
--

CREATE TABLE `test_detail` (
  `id` int(11) NOT NULL,
  `category` varchar(20) NOT NULL,
  `name` varchar(20) NOT NULL,
  `cost` int(11) NOT NULL,
  `reporting_time` int(11) NOT NULL,
  `center_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `test_detail`
--

INSERT INTO `test_detail` (`id`, `category`, `name`, `cost`, `reporting_time`, `center_id`) VALUES
(1, '1', 'suger', 250, 1, 1);

-- --------------------------------------------------------

--
-- Table structure for table `test_requests`
--

CREATE TABLE `test_requests` (
  `id` int(11) NOT NULL,
  `test_name` varchar(20) NOT NULL,
  `test_type` varchar(20) NOT NULL,
  `center` varchar(20) NOT NULL,
  `book_by` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `date` date NOT NULL,
  `time` time NOT NULL,
  `status` varchar(20) NOT NULL DEFAULT 'Not Accepted',
  `rating` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `test_requests`
--

INSERT INTO `test_requests` (`id`, `test_name`, `test_type`, `center`, `book_by`, `user_id`, `date`, `time`, `status`, `rating`) VALUES
(2, 'suger', 'blood ', '1', 1, 2, '2023-07-19', '16:50:00', 'Completed', 5);

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `name` varchar(50) NOT NULL,
  `phone_no` varchar(11) NOT NULL,
  `username` varchar(20) NOT NULL,
  `password` varchar(250) NOT NULL,
  `role` varchar(15) NOT NULL,
  `email` varchar(40) NOT NULL,
  `approved` int(11) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `phone_no`, `username`, `password`, `role`, `email`, `approved`) VALUES
(1, 'Muhammad Anees Ghazanfar', '03461884135', 'admin', '$2y$10$g4g.i7epsUhSLlBN5XOI4unvvzS.h34llepoBoH65OCkONEC7pVEK', 'admin', 'cyberexpert1122@gmail.com', 1),
(2, 'Ali', '456987123', 'patient', '$2y$10$PrZBia1GY9QMxYXSyhAzQOjIFHdjyUxqv2JOC.x.iR3EK6JwelGuO', 'patient', 'patient@gmail.com', 0),
(3, 'Asim', '1458712', 'dc_manager', '$2y$10$PA.7smEm3Mq61cmv7xAiBe9TLgiiRU9SkI8N.p/7Q81oICpO50uSm', 'dc_manager', 'dc_manager@gmail.com', 1);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `center_test_cat`
--
ALTER TABLE `center_test_cat`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `diagnostic_center`
--
ALTER TABLE `diagnostic_center`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `feedback`
--
ALTER TABLE `feedback`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `invoice`
--
ALTER TABLE `invoice`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `messages`
--
ALTER TABLE `messages`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `patient_rating`
--
ALTER TABLE `patient_rating`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `reports`
--
ALTER TABLE `reports`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `test_categories`
--
ALTER TABLE `test_categories`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `test_detail`
--
ALTER TABLE `test_detail`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `test_requests`
--
ALTER TABLE `test_requests`
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
-- AUTO_INCREMENT for table `center_test_cat`
--
ALTER TABLE `center_test_cat`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `diagnostic_center`
--
ALTER TABLE `diagnostic_center`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `feedback`
--
ALTER TABLE `feedback`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `invoice`
--
ALTER TABLE `invoice`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `messages`
--
ALTER TABLE `messages`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `patient_rating`
--
ALTER TABLE `patient_rating`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `reports`
--
ALTER TABLE `reports`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `test_categories`
--
ALTER TABLE `test_categories`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `test_detail`
--
ALTER TABLE `test_detail`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `test_requests`
--
ALTER TABLE `test_requests`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
