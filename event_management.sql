-- phpMyAdmin SQL Dump
-- version 5.2.2
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Nov 02, 2025 at 05:40 AM
-- Server version: 8.4.3
-- PHP Version: 8.3.16

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `event_management`
--

-- --------------------------------------------------------

--
-- Table structure for table `attendance`
--
CREATE DATABASE IF NOT EXISTS `event_management`;
USE `event_management`;
CREATE TABLE `attendance` (
  `attendance_id` int UNSIGNED NOT NULL,
  `event_id` int UNSIGNED NOT NULL,
  `user_id` int UNSIGNED NOT NULL,
  `checkin_time` datetime DEFAULT NULL,
  `checkout_time` datetime DEFAULT NULL,
  `qr_code` varchar(100) DEFAULT NULL,
  `location` varchar(255) DEFAULT NULL,
  `status` enum('Có mặt','Vắng','Trễ','Xin phép') DEFAULT 'Có mặt',
  `image_url` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `attendance`
--

INSERT INTO `attendance` (`attendance_id`, `event_id`, `user_id`, `checkin_time`, `checkout_time`, `qr_code`, `location`, `status`, `image_url`) VALUES
(9, 18, 19, '2025-11-01 12:52:30', NULL, NULL, NULL, 'Có mặt', 'http://127.0.0.1:8000/storage/attendance/user_19/19_20251101_125230.jpg'),
(10, 19, 17, '2025-11-01 16:23:29', NULL, NULL, NULL, 'Có mặt', 'http://127.0.0.1:8000/storage/attendance/user_17/17_20251101_162329.jpg'),
(11, 19, 19, '2025-11-01 16:24:28', NULL, NULL, NULL, 'Có mặt', 'http://127.0.0.1:8000/storage/attendance/user_19/19_20251101_162428.jpg');

-- --------------------------------------------------------

--
-- Table structure for table `evaluations`
--

CREATE TABLE `evaluations` (
  `evaluation_id` int UNSIGNED NOT NULL,
  `event_id` int UNSIGNED NOT NULL,
  `user_id` int UNSIGNED NOT NULL,
  `score` decimal(5,2) DEFAULT NULL,
  `comment` text,
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `evaluations`
--

INSERT INTO `evaluations` (`evaluation_id`, `event_id`, `user_id`, `score`, `comment`, `created_at`) VALUES
(4, 18, 19, 2.00, NULL, '2025-11-01 14:10:36');

-- --------------------------------------------------------

--
-- Table structure for table `events`
--

CREATE TABLE `events` (
  `event_id` int UNSIGNED NOT NULL,
  `event_code` varchar(20) DEFAULT NULL,
  `event_name` varchar(150) NOT NULL,
  `organizer` varchar(100) DEFAULT NULL,
  `manager_id` int UNSIGNED DEFAULT NULL,
  `level` enum('Cấp trường','Cấp khoa','Cấp đơn vị') DEFAULT NULL,
  `semester` varchar(10) DEFAULT NULL,
  `academic_year` varchar(15) DEFAULT NULL,
  `start_time` datetime NOT NULL,
  `end_time` datetime NOT NULL,
  `registration_deadline` datetime DEFAULT NULL,
  `location` varchar(255) DEFAULT NULL,
  `description` text,
  `image_url` varchar(500) DEFAULT NULL COMMENT 'Đường dẫn ảnh sự kiện',
  `is_recruiting` tinyint(1) NOT NULL DEFAULT '0',
  `max_participants` int DEFAULT NULL,
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `events`
--

INSERT INTO `events` (`event_id`, `event_code`, `event_name`, `organizer`, `manager_id`, `level`, `semester`, `academic_year`, `start_time`, `end_time`, `registration_deadline`, `location`, `description`, `image_url`, `is_recruiting`, `max_participants`, `created_at`) VALUES
(18, '2025HKI001', '1', 'CLB Kỹ năng mềm', 1, 'Cấp trường', 'HKI', '2025', '2025-11-01 12:50:00', '2025-11-01 14:00:00', '2025-11-01 12:48:00', 'sfds', 'adasd', 'http://127.0.0.1:8000/storage/events/wVrsnxsxgBARI9fcs9CfpvCuB531zBTjd020TJf1.jpg', 0, 12, '2025-11-01 12:45:47'),
(19, '2025HKHE001', '2', 'CLB Kỹ năng mềm', 1, 'Cấp trường', 'HKHE', '2025', '2025-11-01 16:25:00', '2025-11-01 16:26:00', '2025-11-01 16:24:00', 'hn', 'fdsfsdf', 'http://127.0.0.1:8000/storage/events/pnVnpDsf5zK3wpkAqkXzna6eFrNwzBd4vKFPjZmW.jpg', 0, 12, '2025-11-01 16:20:26'),
(20, '2025HKII001', '3', 'fdf', 1, 'Cấp đơn vị', 'HKII', '2025', '2025-11-01 17:00:00', '2025-11-01 20:57:00', '2025-11-01 16:59:00', 'hn', 'ad', 'http://127.0.0.1:8000/storage/events/1wAXfOP99hDhPhGMrDITNdxnYQfSLXQyqTUO2k2B.jpg', 0, 12, '2025-11-01 16:57:34');

-- --------------------------------------------------------

--
-- Table structure for table `event_registration`
--

CREATE TABLE `event_registration` (
  `registration_id` int UNSIGNED NOT NULL,
  `event_id` int UNSIGNED NOT NULL,
  `user_id` int UNSIGNED NOT NULL,
  `register_date` datetime DEFAULT CURRENT_TIMESTAMP,
  `status` enum('Đã đăng ký','Đã tham gia','Đã hủy') DEFAULT 'Đã đăng ký',
  `note` text
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `event_registration`
--

INSERT INTO `event_registration` (`registration_id`, `event_id`, `user_id`, `register_date`, `status`, `note`) VALUES
(10, 18, 19, '2025-11-01 12:46:23', 'Đã đăng ký', NULL),
(11, 19, 19, '2025-11-01 16:21:26', 'Đã đăng ký', NULL),
(12, 19, 17, '2025-11-01 16:22:11', 'Đã đăng ký', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `feedbacks`
--

CREATE TABLE `feedbacks` (
  `feedback_id` int UNSIGNED NOT NULL,
  `event_id` int UNSIGNED NOT NULL,
  `user_id` int UNSIGNED NOT NULL,
  `content` text NOT NULL,
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `feedbacks`
--

INSERT INTO `feedbacks` (`feedback_id`, `event_id`, `user_id`, `content`, `created_at`) VALUES
(4, 18, 19, 'ádasd', '2025-11-01 12:53:11');

-- --------------------------------------------------------

--
-- Table structure for table `feedback_replies`
--

CREATE TABLE `feedback_replies` (
  `reply_id` int UNSIGNED NOT NULL,
  `feedback_id` int UNSIGNED NOT NULL,
  `sender_id` int UNSIGNED NOT NULL,
  `receiver_id` int UNSIGNED NOT NULL,
  `content` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `feedback_replies`
--

INSERT INTO `feedback_replies` (`reply_id`, `feedback_id`, `sender_id`, `receiver_id`, `content`, `created_at`) VALUES
(5, 4, 1, 1, 'adas', '2025-11-01 05:54:14'),
(6, 4, 1, 1, 'ads', '2025-11-01 07:10:10'),
(7, 4, 19, 1, 'đâs', '2025-11-01 07:14:55'),
(8, 4, 1, 1, '4', '2025-11-01 07:21:32'),
(9, 4, 1, 1, 'zcxczx', '2025-11-01 07:27:22'),
(10, 4, 19, 1, 'adasd', '2025-11-01 07:28:09'),
(11, 4, 19, 1, 'adad', '2025-11-01 07:30:22'),
(12, 4, 1, 1, 'adasdas', '2025-11-01 07:31:03'),
(13, 4, 1, 19, 'adsadasd', '2025-11-01 08:01:59'),
(14, 4, 19, 1, 'adadsad', '2025-11-01 08:02:23');

-- --------------------------------------------------------

--
-- Table structure for table `migrations`
--

CREATE TABLE `migrations` (
  `id` int UNSIGNED NOT NULL,
  `migration` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `batch` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `migrations`
--

INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES
(1, '2025_10_30_094137_create_notifications_table', 1),
(2, '2025_10_31_103204_create_feedback_replies_table', 2),
(3, '2025_10_31_132526_add_is_recruiting_to_events_table', 3),
(4, '2025_10_31_142641_add_registration_deadline_to_events_table', 4),
(5, '2025_11_01_105537_add_image_url_to_events_table', 5),
(6, '2025_11_01_123906_add_event_id_to_notifications_table', 6);

-- --------------------------------------------------------

--
-- Table structure for table `notifications`
--

CREATE TABLE `notifications` (
  `id` bigint UNSIGNED NOT NULL,
  `user_id` int UNSIGNED DEFAULT NULL,
  `event_id` bigint UNSIGNED DEFAULT NULL,
  `title` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `message` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `type` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'general',
  `is_read` tinyint(1) NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `notifications`
--

INSERT INTO `notifications` (`id`, `user_id`, `event_id`, `title`, `message`, `type`, `is_read`, `created_at`, `updated_at`) VALUES
(14, 19, 18, 'Phản hồi mới', 'System Admin12 đã gửi phản hồi trong sự kiện \'1\'.', 'feedback', 0, '2025-11-01 07:27:22', '2025-11-01 07:27:22'),
(15, 1, 18, 'Phản hồi mới', 'Nguyễn Văn d đã gửi phản hồi trong sự kiện \'1\'.', 'feedback', 0, '2025-11-01 07:28:09', '2025-11-01 07:28:09'),
(16, 1, 18, 'Phản hồi mới', 'Nguyễn Văn d đã gửi phản hồi trong sự kiện1 \'1\'.', 'feedback', 0, '2025-11-01 07:30:22', '2025-11-01 07:30:22'),
(17, 19, 18, 'Phản hồi mới', 'System Admin12 đã gửi phản hồi trong sự kiện1 \'1\'.', 'feedback', 0, '2025-11-01 07:31:03', '2025-11-01 07:31:03'),
(18, 19, 18, 'Phản hồi mới từ giáo viên', 'System Admin12 đã gửi phản hồi trong sự kiện \'1\'.', 'teacher_feedback', 0, '2025-11-01 08:01:59', '2025-11-01 08:01:59'),
(19, 1, 18, 'Phản hồi mới từ sinh viên', 'Nguyễn Văn d đã gửi phản hồi trong sự kiện \'1\'.', 'feedback', 0, '2025-11-01 08:02:24', '2025-11-01 08:02:24'),
(20, NULL, 19, '🎉 Sự kiện mới: 2', 'Giáo viên vừa tạo sự kiện \"2\". Hãy xem chi tiết và đăng ký tham gia nhé!', 'new_event', 0, '2025-11-01 09:20:26', '2025-11-01 09:20:26'),
(21, NULL, 20, '🎉 Sự kiện mới: 3', 'Giáo viên vừa tạo sự kiện \"3\". Hãy xem chi tiết và đăng ký tham gia nhé!', 'new_event', 0, '2025-11-01 09:57:34', '2025-11-01 09:57:34');

-- --------------------------------------------------------

--
-- Table structure for table `notification_reads`
--

CREATE TABLE `notification_reads` (
  `id` bigint UNSIGNED NOT NULL,
  `notification_id` bigint UNSIGNED NOT NULL,
  `user_id` bigint UNSIGNED NOT NULL,
  `read_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `notification_reads`
--

INSERT INTO `notification_reads` (`id`, `notification_id`, `user_id`, `read_at`, `created_at`, `updated_at`) VALUES
(25, 14, 19, '2025-11-02 04:55:13', NULL, NULL),
(26, 15, 1, '2025-11-02 05:00:27', NULL, NULL),
(27, 16, 1, '2025-11-02 05:00:27', NULL, NULL),
(28, 17, 19, '2025-11-02 04:55:13', NULL, NULL),
(29, 18, 19, '2025-11-02 04:55:13', NULL, NULL),
(30, 19, 1, '2025-11-02 05:00:27', NULL, NULL),
(31, 20, 19, '2025-11-02 04:55:13', NULL, NULL),
(32, 20, 17, '2025-11-01 09:22:08', NULL, NULL),
(33, 20, 1, '2025-11-02 05:00:27', NULL, NULL),
(34, 21, 1, '2025-11-02 05:00:27', NULL, NULL),
(35, 21, 19, '2025-11-02 04:55:13', NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `reports`
--

CREATE TABLE `reports` (
  `report_id` int UNSIGNED NOT NULL,
  `created_by` int UNSIGNED NOT NULL,
  `type` enum('Theo sự kiện','Theo khoa','Theo lớp','Theo sinh viên') NOT NULL,
  `parameters` text,
  `result_url` varchar(255) DEFAULT NULL,
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Table structure for table `tasks`
--

CREATE TABLE `tasks` (
  `task_id` int UNSIGNED NOT NULL,
  `event_id` int UNSIGNED NOT NULL,
  `task_name` varchar(100) NOT NULL,
  `description` text,
  `required_number` int DEFAULT NULL,
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Table structure for table `task_assignment`
--

CREATE TABLE `task_assignment` (
  `assignment_id` int UNSIGNED NOT NULL,
  `task_id` int UNSIGNED NOT NULL,
  `user_id` int UNSIGNED NOT NULL,
  `assigned_at` datetime DEFAULT CURRENT_TIMESTAMP,
  `status` enum('Chưa làm','Đang làm','Hoàn thành') DEFAULT 'Chưa làm'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `user_id` int UNSIGNED NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `full_name` varchar(100) NOT NULL,
  `dob` date DEFAULT NULL,
  `gender` enum('Nam','Nữ','Khác') DEFAULT NULL,
  `phone` varchar(15) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  `class` varchar(50) DEFAULT NULL,
  `faculty` varchar(100) DEFAULT NULL,
  `role` enum('admin','student') NOT NULL DEFAULT 'student',
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`user_id`, `username`, `password`, `full_name`, `dob`, `gender`, `phone`, `email`, `class`, `faculty`, `role`, `created_at`) VALUES
(1, 'admin', '7c222fb2927d828af22f592134e8932480637c0d', 'System Admin12', '1990-01-01', 'Nam', '0900000001', 'admin@example.com', NULL, 'IT', 'admin', '2025-10-18 09:29:16'),
(15, 'sv0031', '7c222fb2927d828af22f592134e8932480637c0d', 'Lê Văn f', NULL, NULL, '905556666', 'c@gmail.com', 'CNTT3', 'CNTT', 'student', '2025-10-18 21:56:29'),
(16, 'sv1', '7c222fb2927d828af22f592134e8932480637c0d', 'Nguyễn Văn a', NULL, NULL, '901112222', 'a@gmail.com', 'KTPM1', 'CNTT', 'student', '2025-10-30 12:35:46'),
(17, 'sv2', '7c222fb2927d828af22f592134e8932480637c0d', 'Trần Thị b', NULL, NULL, '903334444', 'b@gmail.com', 'QTKD2', 'Kinh tế', 'student', '2025-10-30 12:35:46'),
(18, 'sv3', '7c222fb2927d828af22f592134e8932480637c0d', 'Lê Văn c', NULL, NULL, '905556666', 'c@gmail.com', 'CNTT3', 'CNTT', 'student', '2025-10-30 12:35:46'),
(19, 'sv4', '7c222fb2927d828af22f592134e8932480637c0d', 'Nguyễn Văn d', NULL, NULL, '901112222', 'a@gmail.com', 'KTPM1', 'CNTT', 'student', '2025-10-30 12:35:46'),
(34, 'sv5', '7c222fb2927d828af22f592134e8932480637c0d', 'Trần Thị e', NULL, NULL, '903334444', 'b@gmail.com', 'QTKD2', 'Kinh tế', 'student', '2025-10-31 13:03:31'),
(35, 'sv6', '7c222fb2927d828af22f592134e8932480637c0d', 'Lê Văn f', NULL, NULL, '905556666', 'c@gmail.com', 'CNTT3', 'CNTT', 'student', '2025-10-31 13:03:31'),
(36, 'sv7', '7c222fb2927d828af22f592134e8932480637c0d', 'Nguyễn Văn g', NULL, NULL, '901112222', 'a@gmail.com', 'KTPM1', 'CNTT', 'student', '2025-10-31 13:03:31'),
(37, 'sv8', '7c222fb2927d828af22f592134e8932480637c0d', 'Trần Thị h', NULL, NULL, '903334444', 'b@gmail.com', 'QTKD2', 'Kinh tế', 'student', '2025-10-31 13:03:31'),
(38, 'sv9', '7c222fb2927d828af22f592134e8932480637c0d', 'Lê Văn i', NULL, NULL, '905556666', 'c@gmail.com', 'CNTT3', 'CNTT', 'student', '2025-10-31 13:03:31'),
(39, 'sv10', '7c222fb2927d828af22f592134e8932480637c0d', 'Nguyễn Văn j', NULL, NULL, '901112222', 'a@gmail.com', 'KTPM1', 'CNTT', 'student', '2025-10-31 13:03:31'),
(40, 'sv11', '7c222fb2927d828af22f592134e8932480637c0d', 'Trần Thị e', NULL, NULL, '903334444', 'b@gmail.com', 'QTKD2', 'Kinh tế', 'student', '2025-10-31 13:03:31'),
(41, 'sv12', '7c222fb2927d828af22f592134e8932480637c0d', 'Lê Văn w', NULL, NULL, '905556666', 'hai.dt.2232@aptechlearning.edu.vn', 'CNTT3', 'CNTT', 'student', '2025-10-31 13:03:31');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `attendance`
--
ALTER TABLE `attendance`
  ADD PRIMARY KEY (`attendance_id`),
  ADD KEY `event_id` (`event_id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `idx_attendance_status` (`status`);

--
-- Indexes for table `evaluations`
--
ALTER TABLE `evaluations`
  ADD PRIMARY KEY (`evaluation_id`),
  ADD KEY `event_id` (`event_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `events`
--
ALTER TABLE `events`
  ADD PRIMARY KEY (`event_id`),
  ADD UNIQUE KEY `event_code` (`event_code`),
  ADD KEY `manager_id` (`manager_id`),
  ADD KEY `idx_event_start_time` (`start_time`);

--
-- Indexes for table `event_registration`
--
ALTER TABLE `event_registration`
  ADD PRIMARY KEY (`registration_id`),
  ADD UNIQUE KEY `unique_event_user` (`event_id`,`user_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `feedbacks`
--
ALTER TABLE `feedbacks`
  ADD PRIMARY KEY (`feedback_id`),
  ADD KEY `event_id` (`event_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `feedback_replies`
--
ALTER TABLE `feedback_replies`
  ADD PRIMARY KEY (`reply_id`),
  ADD KEY `feedback_replies_feedback_id_foreign` (`feedback_id`),
  ADD KEY `feedback_replies_sender_id_foreign` (`sender_id`),
  ADD KEY `feedback_replies_receiver_id_foreign` (`receiver_id`);

--
-- Indexes for table `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `notifications`
--
ALTER TABLE `notifications`
  ADD PRIMARY KEY (`id`),
  ADD KEY `notifications_user_id_foreign` (`user_id`);

--
-- Indexes for table `notification_reads`
--
ALTER TABLE `notification_reads`
  ADD PRIMARY KEY (`id`),
  ADD KEY `notification_reads_notification_id_foreign` (`notification_id`);

--
-- Indexes for table `reports`
--
ALTER TABLE `reports`
  ADD PRIMARY KEY (`report_id`),
  ADD KEY `created_by` (`created_by`);

--
-- Indexes for table `tasks`
--
ALTER TABLE `tasks`
  ADD PRIMARY KEY (`task_id`),
  ADD KEY `event_id` (`event_id`);

--
-- Indexes for table `task_assignment`
--
ALTER TABLE `task_assignment`
  ADD PRIMARY KEY (`assignment_id`),
  ADD KEY `task_id` (`task_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`user_id`),
  ADD UNIQUE KEY `username` (`username`),
  ADD KEY `idx_user_faculty` (`faculty`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `attendance`
--
ALTER TABLE `attendance`
  MODIFY `attendance_id` int UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `evaluations`
--
ALTER TABLE `evaluations`
  MODIFY `evaluation_id` int UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `events`
--
ALTER TABLE `events`
  MODIFY `event_id` int UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- AUTO_INCREMENT for table `event_registration`
--
ALTER TABLE `event_registration`
  MODIFY `registration_id` int UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `feedbacks`
--
ALTER TABLE `feedbacks`
  MODIFY `feedback_id` int UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `feedback_replies`
--
ALTER TABLE `feedback_replies`
  MODIFY `reply_id` int UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT for table `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `notifications`
--
ALTER TABLE `notifications`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=22;

--
-- AUTO_INCREMENT for table `notification_reads`
--
ALTER TABLE `notification_reads`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=36;

--
-- AUTO_INCREMENT for table `reports`
--
ALTER TABLE `reports`
  MODIFY `report_id` int UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tasks`
--
ALTER TABLE `tasks`
  MODIFY `task_id` int UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `task_assignment`
--
ALTER TABLE `task_assignment`
  MODIFY `assignment_id` int UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `user_id` int UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=49;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `attendance`
--
ALTER TABLE `attendance`
  ADD CONSTRAINT `attendance_ibfk_1` FOREIGN KEY (`event_id`) REFERENCES `events` (`event_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `attendance_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `evaluations`
--
ALTER TABLE `evaluations`
  ADD CONSTRAINT `evaluations_ibfk_1` FOREIGN KEY (`event_id`) REFERENCES `events` (`event_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `evaluations_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `events`
--
ALTER TABLE `events`
  ADD CONSTRAINT `events_ibfk_1` FOREIGN KEY (`manager_id`) REFERENCES `users` (`user_id`) ON DELETE SET NULL ON UPDATE CASCADE;

--
-- Constraints for table `event_registration`
--
ALTER TABLE `event_registration`
  ADD CONSTRAINT `event_registration_ibfk_1` FOREIGN KEY (`event_id`) REFERENCES `events` (`event_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `event_registration_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `feedbacks`
--
ALTER TABLE `feedbacks`
  ADD CONSTRAINT `feedbacks_ibfk_1` FOREIGN KEY (`event_id`) REFERENCES `events` (`event_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `feedbacks_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `feedback_replies`
--
ALTER TABLE `feedback_replies`
  ADD CONSTRAINT `feedback_replies_feedback_id_foreign` FOREIGN KEY (`feedback_id`) REFERENCES `feedbacks` (`feedback_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `feedback_replies_receiver_id_foreign` FOREIGN KEY (`receiver_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `feedback_replies_sender_id_foreign` FOREIGN KEY (`sender_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE;

--
-- Constraints for table `notifications`
--
ALTER TABLE `notifications`
  ADD CONSTRAINT `notifications_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE;

--
-- Constraints for table `notification_reads`
--
ALTER TABLE `notification_reads`
  ADD CONSTRAINT `notification_reads_notification_id_foreign` FOREIGN KEY (`notification_id`) REFERENCES `notifications` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `reports`
--
ALTER TABLE `reports`
  ADD CONSTRAINT `reports_ibfk_1` FOREIGN KEY (`created_by`) REFERENCES `users` (`user_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `tasks`
--
ALTER TABLE `tasks`
  ADD CONSTRAINT `tasks_ibfk_1` FOREIGN KEY (`event_id`) REFERENCES `events` (`event_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `task_assignment`
--
ALTER TABLE `task_assignment`
  ADD CONSTRAINT `task_assignment_ibfk_1` FOREIGN KEY (`task_id`) REFERENCES `tasks` (`task_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `task_assignment_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
