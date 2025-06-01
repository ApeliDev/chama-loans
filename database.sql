-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jun 01, 2025 at 11:45 AM
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
-- Database: `kenai`
--

-- --------------------------------------------------------

--
-- Table structure for table `adminusers`
--

CREATE TABLE `adminusers` (
  `admin_id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `email` varchar(100) NOT NULL,
  `remember_token` varchar(255) DEFAULT NULL,
  `otp_code` varchar(10) DEFAULT NULL,
  `otp_expires_at` datetime DEFAULT NULL,
  `is_verified` tinyint(1) DEFAULT 0,
  `reset_token` varchar(64) DEFAULT NULL,
  `reset_token_expires_at` datetime DEFAULT NULL,
  `status` enum('active','inactive') NOT NULL DEFAULT 'active',
  `role` enum('super_admin','loan_officer','savings_officer','admin') NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `adminusers`
--

INSERT INTO `adminusers` (`admin_id`, `username`, `password`, `email`, `remember_token`, `otp_code`, `otp_expires_at`, `is_verified`, `reset_token`, `reset_token_expires_at`, `status`, `role`, `created_at`, `updated_at`) VALUES
(5, 'juliet', '$2y$10$tVowN..IGH7znQoNmgF9uOBoGBPDsQoMvfQTa7ZCFq4S7MgKs93wW', 'julietakoth@gmail.com', NULL, NULL, NULL, 0, NULL, NULL, 'active', 'admin', '2024-12-03 02:10:41', '2024-12-03 02:10:41'),
(7, 'livingstone', '$2y$10$3PWWbYqj0Gw8zsCqNtPsxOJ1sFgrU32qjteg4cOU0SYfKwRQ/T586', 'livingstoneapeli@gmail.com', NULL, NULL, NULL, 0, NULL, NULL, 'active', 'admin', '2025-06-01 09:44:43', '2025-06-01 09:44:43');

-- --------------------------------------------------------

--
-- Table structure for table `attendance`
--

CREATE TABLE `attendance` (
  `id` int(11) NOT NULL,
  `student_id` int(11) NOT NULL,
  `class_id` int(11) NOT NULL,
  `class_start_time` datetime NOT NULL,
  `attendance_time` timestamp NOT NULL DEFAULT current_timestamp(),
  `status` enum('present','late','absent') DEFAULT 'absent'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `classes`
--

CREATE TABLE `classes` (
  `id` int(11) NOT NULL,
  `class_name` varchar(255) NOT NULL,
  `unit` varchar(255) NOT NULL,
  `start_time` datetime NOT NULL,
  `end_time` datetime NOT NULL,
  `attendance_deadline` datetime DEFAULT NULL,
  `status` enum('active','ended') DEFAULT 'active',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `qr_code` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `classes`
--

INSERT INTO `classes` (`id`, `class_name`, `unit`, `start_time`, `end_time`, `attendance_deadline`, `status`, `created_at`, `qr_code`) VALUES
(18, 'Computer Models', 'Machine Learning', '2024-11-17 06:37:00', '2024-11-17 08:38:00', '2024-11-17 08:38:00', 'active', '2024-11-17 03:38:24', 'qrcodes/class_18.png'),
(19, 'ttt', 'tttt', '2024-11-17 06:57:00', '2024-11-17 06:59:00', '2024-11-17 06:59:00', 'active', '2024-11-17 03:57:38', 'qrcodes/class_19.png'),
(20, 'yuf', 'ijjofokf', '2024-11-17 07:04:00', '2024-11-17 07:05:00', '2024-11-17 07:05:00', 'active', '2024-11-17 04:02:47', 'qrcodes/class_20.png');

-- --------------------------------------------------------

--
-- Table structure for table `contact_messages`
--

CREATE TABLE `contact_messages` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `message` text NOT NULL,
  `submitted_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `contact_messages`
--

INSERT INTO `contact_messages` (`id`, `name`, `email`, `message`, `submitted_at`) VALUES
(1, 'livingstone Apeli', 'livingstoneapeli@gmain.com', 'hello there', '2024-11-17 00:46:19');

-- --------------------------------------------------------

--
-- Table structure for table `contributions`
--

CREATE TABLE `contributions` (
  `id` int(11) NOT NULL,
  `member_id` int(11) NOT NULL,
  `contribution_amount` decimal(10,2) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `contributions`
--

INSERT INTO `contributions` (`id`, `member_id`, `contribution_amount`, `created_at`) VALUES
(1, 3, 40000.00, '2024-11-13 21:42:02'),
(2, 3, 3000.00, '2024-11-13 22:27:57'),
(3, 4, 6000.00, '2024-11-14 11:40:02'),
(4, 3, 565666.00, '2024-11-15 01:11:02'),
(5, 3, 1000.00, '2024-11-15 12:17:48'),
(6, 3, 1000.00, '2024-11-15 12:18:49'),
(7, 3, 1000.00, '2024-11-15 12:31:13'),
(8, 6, 1000.00, '2024-11-16 18:52:47'),
(9, 3, 1000.00, '2024-11-18 21:30:31'),
(10, 8, 1000.00, '2024-11-20 00:00:36'),
(11, 8, 3000.00, '2024-11-20 00:01:25'),
(12, 10, 60000.00, '2024-12-05 17:21:14'),
(13, 12, 5555.00, '2025-06-01 08:55:37');

-- --------------------------------------------------------

--
-- Table structure for table `loanrepayments`
--

CREATE TABLE `loanrepayments` (
  `repayment_id` int(11) NOT NULL,
  `loan_id` int(11) DEFAULT NULL,
  `member_id` int(11) DEFAULT NULL,
  `repayment_amount` decimal(10,2) NOT NULL,
  `repayment_date` date NOT NULL,
  `remaining_balance` decimal(10,2) NOT NULL,
  `status` enum('pending','completed','overdue') NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `loanrepayments`
--

INSERT INTO `loanrepayments` (`repayment_id`, `loan_id`, `member_id`, `repayment_amount`, `repayment_date`, `remaining_balance`, `status`) VALUES
(1, 2, 3, 1000.00, '2024-11-15', 2000.00, 'completed'),
(2, 1, 3, 10000.00, '2024-11-15', 20000.00, 'completed'),
(3, 2, 3, 1000.00, '2024-11-15', 1000.00, 'completed'),
(4, 2, 3, 1000.00, '2024-11-15', 0.00, 'completed'),
(5, 1, 3, 4000.00, '2024-11-15', 16000.00, 'completed'),
(6, 1, 3, 1000.00, '2024-11-15', 15000.00, 'completed'),
(7, 1, 3, 1000.00, '2024-11-15', 14000.00, 'completed'),
(8, 1, 3, 1000.00, '2024-11-15', 13000.00, 'completed'),
(9, 1, 3, 1000.00, '2024-11-15', 12000.00, 'completed'),
(10, 1, 3, 2000.00, '2024-11-15', 10000.00, 'completed'),
(11, 5, 6, 10000.00, '2024-11-16', 30000.00, 'completed'),
(12, 7, 7, 20000.00, '2024-11-17', 20000.00, 'completed'),
(13, 1, 3, 1000.00, '2024-11-18', 9000.00, 'completed'),
(14, 10, 8, 23000.00, '2024-11-20', 17000.00, 'completed'),
(15, 11, 10, 40000.00, '2024-12-05', 40000.00, 'completed');

-- --------------------------------------------------------

--
-- Table structure for table `loanrequests`
--

CREATE TABLE `loanrequests` (
  `request_id` int(11) NOT NULL,
  `member_id` int(11) DEFAULT NULL,
  `loan_amount_requested` decimal(10,2) NOT NULL,
  `loan_purpose` varchar(255) NOT NULL,
  `interest_rate` decimal(5,2) NOT NULL,
  `loan_term` int(11) NOT NULL,
  `monthly_repayment` decimal(10,2) NOT NULL,
  `status` enum('pending','approved','rejected','disbursed') NOT NULL DEFAULT 'pending',
  `requested_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `approved_at` timestamp NULL DEFAULT NULL,
  `return_date` date DEFAULT NULL,
  `disbursed_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `loanrequests`
--

INSERT INTO `loanrequests` (`request_id`, `member_id`, `loan_amount_requested`, `loan_purpose`, `interest_rate`, `loan_term`, `monthly_repayment`, `status`, `requested_at`, `approved_at`, `return_date`, `disbursed_at`) VALUES
(1, NULL, 5000.00, 'Fees', 5.00, 4, 1312.00, 'pending', '2024-11-13 22:19:53', NULL, NULL, NULL),
(2, NULL, 33.00, 'edd', 5.00, 3, 11.00, 'pending', '2024-11-13 22:36:06', NULL, NULL, NULL),
(3, NULL, 3000.00, 'Pay fees', 5.00, 3, 1050.00, 'pending', '2024-11-13 23:13:21', NULL, NULL, NULL),
(4, 3, 3000.00, 'Fee payment', 5.00, 2, 1575.00, 'approved', '2024-11-13 23:19:38', NULL, NULL, NULL),
(5, 3, 3333.00, '3rfffc', 5.00, 3, 1166.00, 'approved', '2024-11-13 23:23:58', NULL, NULL, NULL),
(6, 4, 5000.00, 'To pay fees', 5.00, 3, 1750.00, 'approved', '2024-11-14 11:40:42', NULL, NULL, NULL),
(7, 3, 30000.00, 'Enjoyment', 5.00, 2, 15750.00, 'approved', '2024-11-14 23:57:31', '2024-11-14 23:58:15', NULL, NULL),
(8, 3, 3000.00, 'fees', 5.00, 3, 1050.00, 'approved', '2024-11-15 00:45:15', '2024-11-15 00:45:27', NULL, NULL),
(9, 3, 3000.00, 'pay fees', 5.00, 3, 1050.00, 'approved', '2024-11-15 08:11:29', '2024-11-15 14:04:37', NULL, NULL),
(10, 3, 300.00, 'fees', 5.00, 3, 105.00, 'approved', '2024-11-15 11:50:18', '2024-11-16 02:27:22', NULL, NULL),
(11, 6, 40000.00, 'fees payment', 5.00, 3, 14000.00, 'approved', '2024-11-16 18:53:54', '2024-11-16 18:54:39', NULL, NULL),
(12, 6, 2000.00, 'fees', 5.00, 1, 2100.00, 'approved', '2024-11-16 18:55:44', '2024-11-16 18:55:57', NULL, NULL),
(13, 7, 40000.00, 'fees', 5.00, 4, 10500.00, 'approved', '2024-11-17 11:40:18', '2024-11-17 11:41:41', NULL, NULL),
(14, 7, 30000.00, 'fees', 5.00, 4, 7875.00, 'approved', '2024-11-17 11:44:07', '2024-11-17 11:44:27', NULL, NULL),
(15, 3, 10000.00, 'to pay fees', 5.00, 4, 2625.00, 'approved', '2024-11-18 21:33:24', '2024-11-18 21:33:56', NULL, NULL),
(16, 8, 40000.00, 'pay fees', 5.00, 4, 10500.00, 'approved', '2024-11-20 00:01:54', '2024-11-20 00:02:19', NULL, NULL),
(17, 10, 80000.00, 'to pay fees', 5.00, 6, 14000.00, 'approved', '2024-12-05 17:22:57', '2024-12-05 17:23:24', NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `loans`
--

CREATE TABLE `loans` (
  `loan_id` int(11) NOT NULL,
  `member_id` int(11) DEFAULT NULL,
  `loan_amount` decimal(10,2) NOT NULL,
  `interest_rate` decimal(5,2) NOT NULL,
  `loan_term` int(11) NOT NULL,
  `monthly_repayment` decimal(10,2) NOT NULL,
  `outstanding_balance` decimal(10,2) NOT NULL,
  `loan_status` enum('active','paid','defaulted') NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `loans`
--

INSERT INTO `loans` (`loan_id`, `member_id`, `loan_amount`, `interest_rate`, `loan_term`, `monthly_repayment`, `outstanding_balance`, `loan_status`, `created_at`, `updated_at`) VALUES
(1, 3, 30000.00, 5.00, 2, 15750.00, 9000.00, 'active', '2024-11-14 23:58:15', '2024-11-18 21:31:06'),
(2, 3, 3000.00, 5.00, 3, 1050.00, 0.00, 'paid', '2024-11-15 00:45:27', '2024-11-15 01:16:33'),
(3, 3, 3000.00, 5.00, 3, 1050.00, 3000.00, 'active', '2024-11-15 14:04:36', '2024-11-15 14:04:36'),
(4, 3, 300.00, 5.00, 3, 105.00, 300.00, 'active', '2024-11-16 02:27:22', '2024-11-16 02:27:22'),
(5, 6, 40000.00, 5.00, 3, 14000.00, 30000.00, 'active', '2024-11-16 18:54:39', '2024-11-16 18:56:41'),
(6, 6, 2000.00, 5.00, 1, 2100.00, 2000.00, 'active', '2024-11-16 18:55:57', '2024-11-16 18:55:57'),
(7, 7, 40000.00, 5.00, 4, 10500.00, 20000.00, 'active', '2024-11-17 11:41:40', '2024-11-17 11:42:48'),
(8, 7, 30000.00, 5.00, 4, 7875.00, 30000.00, 'active', '2024-11-17 11:44:27', '2024-11-17 11:44:27'),
(9, 3, 10000.00, 5.00, 4, 2625.00, 10000.00, 'active', '2024-11-18 21:33:56', '2024-11-18 21:33:56'),
(10, 8, 40000.00, 5.00, 4, 10500.00, 17000.00, 'active', '2024-11-20 00:02:19', '2024-11-20 00:10:33'),
(11, 10, 80000.00, 5.00, 6, 14000.00, 40000.00, 'active', '2024-12-05 17:23:24', '2024-12-05 17:24:34');

-- --------------------------------------------------------

--
-- Table structure for table `members`
--

CREATE TABLE `members` (
  `member_id` int(11) NOT NULL,
  `full_name` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `phone_number` varchar(15) NOT NULL,
  `national_id` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `reset_token` varchar(255) DEFAULT NULL,
  `profile_image` blob DEFAULT NULL,
  `savings_balance` decimal(10,2) DEFAULT 0.00,
  `loan_balance` decimal(10,2) DEFAULT 0.00,
  `total_contributions` decimal(10,2) DEFAULT 0.00,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `members`
--

INSERT INTO `members` (`member_id`, `full_name`, `email`, `phone_number`, `national_id`, `password`, `reset_token`, `profile_image`, `savings_balance`, `loan_balance`, `total_contributions`, `created_at`) VALUES
(1, 'tony apeli', 'livingstoneapeli@gmail.com', '0703416091', '40194335', '$2y$10$ePiZMuIZRioV4WahTarjt.RNGTL4Du6eAvFQV9qEf59JBITGlgKte', NULL, NULL, 0.00, 0.00, 0.00, '2024-11-11 22:55:44'),
(3, 'Livingstone Apeli', 'tonnytrevix@gmail.com', '0703416091', '364787328238', '$2y$10$7QuBheL9KfUZCYo.nUGrceUUKkYUvOkcSsE8/0Ier7BdREUr5aAyO', NULL, NULL, 0.00, 0.00, 0.00, '2024-11-11 23:21:47'),
(4, 'Nesh', 'nesheshandrey12@gmail.com', '0703416091', '758474', '$2y$10$pW14YeUpE0Q/IE6RYNE/oODLFyFaUBTjuP1n0CKKaZ0Wm/Md6gcbG', NULL, NULL, 0.00, 0.00, 0.00, '2024-11-14 11:38:55'),
(6, 'Naruto ', 'support@apelisolutions.com', '07456454567', '401943354', '$2y$10$ARNdTHsoOhLrpBgLYzfcL.sTKc3sxw4Wbip1IsEHvwwIQ5YsiOkKC', NULL, NULL, 0.00, 0.00, 0.00, '2024-11-16 18:51:55'),
(7, 'marwa stano', 'marwastano@gmail.com', '0799744783', '54746378', '$2y$10$THGBvz5MWEsCxLAePAdy1ek4D/MVHZEY0HFW10jghm2J1UJH3vH/6', NULL, NULL, 0.00, 0.00, 0.00, '2024-11-17 11:26:12'),
(8, 'Teddy', 'teddy@gmail.com', '0703416091', '546353627282', '$2y$10$Xbt0qa/gLI6u2QZfWNJQXOF65AoQCPy7BPKqs5xGVYeWH3mTmt4Yi', NULL, NULL, 0.00, 0.00, 0.00, '2024-11-19 23:59:54'),
(10, 'Ivy', 'ivybgekonge@gmail.com', '0703416091', '401943355', '$2y$10$/QKVPIW.DJHdXsxkBzllseNyI8bu7k4/uIBAxwVli8vfxpT2tgDI6', NULL, NULL, 0.00, 0.00, 0.00, '2024-12-05 17:20:47'),
(12, 'Livingstone Apeli', 'livingstoneapeli@stepakash.com', '0703416090', '40194334', '$2y$10$t1g4tIEK/co.ihkFlEcg5.UFKkGFjObdygjdF5mhrGW6uRAGLHqTW', NULL, NULL, 0.00, 0.00, 0.00, '2025-06-01 08:52:32');

-- --------------------------------------------------------

--
-- Table structure for table `messages`
--

CREATE TABLE `messages` (
  `message_id` int(11) NOT NULL,
  `sender_id` int(11) NOT NULL,
  `recipient_id` int(11) NOT NULL,
  `subject` varchar(255) NOT NULL,
  `message_text` text NOT NULL,
  `sent_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `status` enum('unread','read') DEFAULT 'unread'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `savings`
--

CREATE TABLE `savings` (
  `saving_id` int(11) NOT NULL,
  `member_id` int(11) DEFAULT NULL,
  `saving_amount` decimal(10,2) NOT NULL,
  `interest_rate` decimal(5,2) DEFAULT NULL,
  `total_balance` decimal(10,2) NOT NULL,
  `saving_status` enum('active','closed','dormant') NOT NULL DEFAULT 'active',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `savings`
--

INSERT INTO `savings` (`saving_id`, `member_id`, `saving_amount`, `interest_rate`, `total_balance`, `saving_status`, `created_at`, `updated_at`) VALUES
(1, 3, 3000.00, NULL, 3000.00, 'active', '2024-11-12 23:00:30', '2024-11-12 23:00:30'),
(2, 3, 300.00, NULL, 300.00, 'active', '2024-11-12 23:01:53', '2024-11-12 23:01:53'),
(3, 3, 40000.00, NULL, 40000.00, 'active', '2024-11-12 23:07:59', '2024-11-12 23:07:59'),
(4, 3, 3999.00, NULL, 0.00, 'active', '2024-11-12 23:11:21', '2024-11-12 23:11:21'),
(5, 3, 333.00, NULL, 0.00, 'active', '2024-11-12 23:11:41', '2024-11-12 23:11:41'),
(6, 4, 50000.00, NULL, 50000.00, 'active', '2024-11-14 11:45:14', '2024-11-14 11:45:14'),
(7, 3, 400.00, NULL, 400.00, 'active', '2024-11-15 12:31:47', '2024-11-15 12:31:47'),
(8, 3, 3000.00, NULL, 3000.00, 'active', '2024-11-15 12:36:14', '2024-11-15 12:36:14'),
(9, 3, 30000.00, NULL, 30000.00, 'active', '2024-11-15 12:40:33', '2024-11-15 12:40:33');

-- --------------------------------------------------------

--
-- Table structure for table `settings`
--

CREATE TABLE `settings` (
  `id` int(11) NOT NULL,
  `setting_name` varchar(255) NOT NULL,
  `setting_value` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `settings`
--

INSERT INTO `settings` (`id`, `setting_name`, `setting_value`) VALUES
(1, 'maintenance_mode', 'on');

-- --------------------------------------------------------

--
-- Table structure for table `students`
--

CREATE TABLE `students` (
  `id` int(11) NOT NULL,
  `first_name` varchar(255) NOT NULL,
  `last_name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `phone` varchar(20) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `reg_number` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `students`
--

INSERT INTO `students` (`id`, `first_name`, `last_name`, `email`, `phone`, `created_at`, `reg_number`) VALUES
(1, 'Ivy', 'Apeli', 'livingstoneapeli@gmail.com', '0703416091', '2024-11-16 20:41:21', 'HDB222-0431/2021');

-- --------------------------------------------------------

--
-- Table structure for table `transactions`
--

CREATE TABLE `transactions` (
  `transaction_id` int(11) NOT NULL,
  `member_id` int(11) DEFAULT NULL,
  `activity_type` varchar(255) DEFAULT NULL,
  `amount` decimal(10,2) DEFAULT NULL,
  `balance_after` decimal(10,2) DEFAULT NULL,
  `transaction_date` datetime DEFAULT current_timestamp(),
  `remarks` text DEFAULT NULL,
  `status` enum('completed','pending','failed') DEFAULT 'completed'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `transactions`
--

INSERT INTO `transactions` (`transaction_id`, `member_id`, `activity_type`, `amount`, `balance_after`, `transaction_date`, `remarks`, `status`) VALUES
(1, 3, 'Loan Applied', 300.00, 0.00, '2024-11-15 14:50:18', NULL, 'pending'),
(2, 3, 'loan repayment', 1000.00, 12000.00, '2024-11-15 13:09:42', 'Loan repayment of KSh 1000 for loan ID: 1', 'completed'),
(3, 3, 'contribution', 1000.00, 3000.00, '2024-11-15 13:17:49', 'Contribution of KSh 1000', 'completed'),
(4, 3, 'contribution', 1000.00, 3000.00, '2024-11-15 13:18:49', 'Contribution of KSh 1000', 'completed'),
(5, 3, 'loan repayment', 2000.00, 10000.00, '2024-11-15 13:19:43', 'Loan repayment of KSh 2000 for loan ID: 1', 'completed'),
(6, 3, 'contribution', 1000.00, 611666.00, '2024-11-15 13:31:13', 'Contribution of KSh 1000', 'completed'),
(7, 3, 'Savings deposit', 400.00, 3000.00, '2024-11-15 13:31:47', 'Deposit of KSh 400', 'completed'),
(8, 3, 'Savings deposit', 3000.00, 3000.00, '2024-11-15 13:36:14', 'Deposit of KSh 3000', 'completed'),
(9, 3, 'Savings deposit', 30000.00, 81032.00, '2024-11-15 13:40:33', 'Deposit of KSh 30000', 'completed'),
(10, 6, 'contribution', 1000.00, 1000.00, '2024-11-16 19:52:47', 'Contribution of KSh 1000', 'completed'),
(11, 6, 'Loan Applied', 40000.00, 0.00, '2024-11-16 21:53:54', NULL, 'pending'),
(12, 6, 'Loan Applied', 2000.00, 0.00, '2024-11-16 21:55:44', NULL, 'pending'),
(13, 6, 'loan repayment', 10000.00, 30000.00, '2024-11-16 19:56:41', 'Loan repayment of KSh 10000 for loan ID: 5', 'completed'),
(14, 7, 'Loan Applied', 40000.00, 0.00, '2024-11-17 14:40:18', NULL, 'pending'),
(15, 7, 'loan repayment', 20000.00, 20000.00, '2024-11-17 12:42:48', 'Loan repayment of KSh 20000 for loan ID: 7', 'completed'),
(16, 7, 'Loan Applied', 30000.00, 0.00, '2024-11-17 14:44:07', NULL, 'pending'),
(17, 3, 'contribution', 1000.00, 612666.00, '2024-11-18 22:30:31', 'Contribution of KSh 1000', 'completed'),
(18, 3, 'loan repayment', 1000.00, 9000.00, '2024-11-18 22:31:06', 'Loan repayment of KSh 1000 for loan ID: 1', 'completed'),
(19, 3, 'Loan Applied', 10000.00, 0.00, '2024-11-19 00:33:24', NULL, 'pending'),
(20, 8, 'contribution', 1000.00, 1000.00, '2024-11-20 01:00:36', 'Contribution of KSh 1000', 'completed'),
(21, 8, 'contribution', 3000.00, 4000.00, '2024-11-20 01:01:25', 'Contribution of KSh 3000', 'completed'),
(22, 8, 'Loan Applied', 40000.00, 0.00, '2024-11-20 03:01:54', NULL, 'pending'),
(23, 8, 'loan repayment', 23000.00, 17000.00, '2024-11-20 01:10:33', 'Loan repayment of KSh 23000 for loan ID: 10', 'completed'),
(24, 10, 'contribution', 60000.00, 60000.00, '2024-12-05 18:21:14', 'Contribution of KSh 60000', 'completed'),
(25, 10, 'Loan Applied', 80000.00, 0.00, '2024-12-05 20:22:57', NULL, 'pending'),
(26, 10, 'loan repayment', 40000.00, 40000.00, '2024-12-05 18:24:34', 'Loan repayment of KSh 40000 for loan ID: 11', 'completed'),
(27, 12, 'contribution', 5555.00, 5555.00, '2025-06-01 10:55:37', 'Contribution of KSh 5555', 'completed');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `adminusers`
--
ALTER TABLE `adminusers`
  ADD PRIMARY KEY (`admin_id`);

--
-- Indexes for table `attendance`
--
ALTER TABLE `attendance`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_student_id` (`student_id`),
  ADD KEY `idx_class_id` (`class_id`);

--
-- Indexes for table `classes`
--
ALTER TABLE `classes`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `contact_messages`
--
ALTER TABLE `contact_messages`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `contributions`
--
ALTER TABLE `contributions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `member_id` (`member_id`);

--
-- Indexes for table `loanrepayments`
--
ALTER TABLE `loanrepayments`
  ADD PRIMARY KEY (`repayment_id`),
  ADD KEY `loan_id` (`loan_id`),
  ADD KEY `member_id` (`member_id`);

--
-- Indexes for table `loanrequests`
--
ALTER TABLE `loanrequests`
  ADD PRIMARY KEY (`request_id`),
  ADD KEY `member_id` (`member_id`);

--
-- Indexes for table `loans`
--
ALTER TABLE `loans`
  ADD PRIMARY KEY (`loan_id`),
  ADD KEY `member_id` (`member_id`);

--
-- Indexes for table `members`
--
ALTER TABLE `members`
  ADD PRIMARY KEY (`member_id`),
  ADD UNIQUE KEY `email` (`email`),
  ADD UNIQUE KEY `national_id` (`national_id`);

--
-- Indexes for table `messages`
--
ALTER TABLE `messages`
  ADD PRIMARY KEY (`message_id`),
  ADD KEY `sender_id` (`sender_id`),
  ADD KEY `recipient_id` (`recipient_id`);

--
-- Indexes for table `savings`
--
ALTER TABLE `savings`
  ADD PRIMARY KEY (`saving_id`),
  ADD KEY `member_id` (`member_id`);

--
-- Indexes for table `settings`
--
ALTER TABLE `settings`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `students`
--
ALTER TABLE `students`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `transactions`
--
ALTER TABLE `transactions`
  ADD PRIMARY KEY (`transaction_id`),
  ADD KEY `member_id` (`member_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `adminusers`
--
ALTER TABLE `adminusers`
  MODIFY `admin_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `attendance`
--
ALTER TABLE `attendance`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `classes`
--
ALTER TABLE `classes`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- AUTO_INCREMENT for table `contact_messages`
--
ALTER TABLE `contact_messages`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `contributions`
--
ALTER TABLE `contributions`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT for table `loanrepayments`
--
ALTER TABLE `loanrepayments`
  MODIFY `repayment_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT for table `loanrequests`
--
ALTER TABLE `loanrequests`
  MODIFY `request_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

--
-- AUTO_INCREMENT for table `loans`
--
ALTER TABLE `loans`
  MODIFY `loan_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `members`
--
ALTER TABLE `members`
  MODIFY `member_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `messages`
--
ALTER TABLE `messages`
  MODIFY `message_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `savings`
--
ALTER TABLE `savings`
  MODIFY `saving_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `settings`
--
ALTER TABLE `settings`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `students`
--
ALTER TABLE `students`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `transactions`
--
ALTER TABLE `transactions`
  MODIFY `transaction_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=28;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `attendance`
--
ALTER TABLE `attendance`
  ADD CONSTRAINT `attendance_ibfk_1` FOREIGN KEY (`student_id`) REFERENCES `students` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `attendance_ibfk_2` FOREIGN KEY (`class_id`) REFERENCES `classes` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `contributions`
--
ALTER TABLE `contributions`
  ADD CONSTRAINT `contributions_ibfk_1` FOREIGN KEY (`member_id`) REFERENCES `members` (`member_id`) ON DELETE CASCADE;

--
-- Constraints for table `loanrepayments`
--
ALTER TABLE `loanrepayments`
  ADD CONSTRAINT `loanrepayments_ibfk_1` FOREIGN KEY (`loan_id`) REFERENCES `loans` (`loan_id`),
  ADD CONSTRAINT `loanrepayments_ibfk_2` FOREIGN KEY (`member_id`) REFERENCES `members` (`member_id`);

--
-- Constraints for table `loanrequests`
--
ALTER TABLE `loanrequests`
  ADD CONSTRAINT `loanrequests_ibfk_1` FOREIGN KEY (`member_id`) REFERENCES `members` (`member_id`);

--
-- Constraints for table `loans`
--
ALTER TABLE `loans`
  ADD CONSTRAINT `loans_ibfk_1` FOREIGN KEY (`member_id`) REFERENCES `members` (`member_id`);

--
-- Constraints for table `messages`
--
ALTER TABLE `messages`
  ADD CONSTRAINT `messages_ibfk_1` FOREIGN KEY (`sender_id`) REFERENCES `members` (`member_id`),
  ADD CONSTRAINT `messages_ibfk_2` FOREIGN KEY (`recipient_id`) REFERENCES `members` (`member_id`);

--
-- Constraints for table `savings`
--
ALTER TABLE `savings`
  ADD CONSTRAINT `savings_ibfk_1` FOREIGN KEY (`member_id`) REFERENCES `members` (`member_id`);

--
-- Constraints for table `transactions`
--
ALTER TABLE `transactions`
  ADD CONSTRAINT `transactions_ibfk_1` FOREIGN KEY (`member_id`) REFERENCES `members` (`member_id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
