-- phpMyAdmin SQL Dump
-- version 5.2.2
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Nov 21, 2025 at 06:35 PM
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
-- Database: `quanlysukien_moi`
--

-- --------------------------------------------------------

--
-- Table structure for table `attendance`
--

CREATE TABLE `attendance` (
  `attendance_id` int UNSIGNED NOT NULL,
  `event_id` int UNSIGNED NOT NULL,
  `user_id` int UNSIGNED NOT NULL,
  `checkin_time` datetime DEFAULT NULL,
  `checkout_time` datetime DEFAULT NULL,
  `qr_code` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `location` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `status` enum('Có mặt','Vắng','Trễ','Xin phép') COLLATE utf8mb4_unicode_ci DEFAULT 'Có mặt',
  `image_url` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `attendance`
--

INSERT INTO `attendance` (`attendance_id`, `event_id`, `user_id`, `checkin_time`, `checkout_time`, `qr_code`, `location`, `status`, `image_url`) VALUES
(21, 47, 253, '2025-11-19 13:38:42', NULL, NULL, NULL, 'Có mặt', 'http://127.0.0.1:8000/storage/attendance/user_253/253_20251119_133842.jpg'),
(22, 43, 253, '2025-11-20 23:10:33', NULL, NULL, NULL, 'Có mặt', 'http://127.0.0.1:8000/storage/attendance/user_253/253_20251120_231033.jpg'),
(23, 38, 253, '2025-11-20 23:11:51', NULL, NULL, NULL, 'Có mặt', 'http://127.0.0.1:8000/storage/attendance/user_253/253_20251120_231151.jpg'),
(24, 43, 260, '2025-11-20 23:19:06', NULL, NULL, NULL, 'Có mặt', 'http://127.0.0.1:8000/storage/attendance/user_260/260_20251120_231906.jpg'),
(25, 45, 260, '2025-11-20 23:23:03', NULL, NULL, NULL, 'Có mặt', 'http://127.0.0.1:8000/storage/attendance/user_260/260_20251120_232303.jpg'),
(26, 44, 260, '2025-11-20 23:27:08', NULL, NULL, NULL, 'Có mặt', 'http://127.0.0.1:8000/storage/attendance/user_260/260_20251120_232708.png'),
(27, 49, 260, '2025-11-21 19:43:36', NULL, NULL, NULL, 'Có mặt', 'http://127.0.0.1:8000/storage/attendance/user_260/260_20251121_194336.jpg');

-- --------------------------------------------------------

--
-- Table structure for table `evaluations`
--

CREATE TABLE `evaluations` (
  `evaluation_id` int UNSIGNED NOT NULL,
  `event_id` int UNSIGNED NOT NULL,
  `user_id` int UNSIGNED NOT NULL,
  `score` decimal(5,2) DEFAULT NULL,
  `comment` text COLLATE utf8mb4_unicode_ci,
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `evaluations`
--

INSERT INTO `evaluations` (`evaluation_id`, `event_id`, `user_id`, `score`, `comment`, `created_at`) VALUES
(9, 47, 253, 0.00, NULL, '2025-11-19 13:41:10'),
(10, 43, 253, 1.00, NULL, '2025-11-21 19:49:57'),
(11, 43, 255, 1.00, NULL, '2025-11-21 19:49:57'),
(12, 43, 260, 1.00, NULL, '2025-11-21 19:49:57');

-- --------------------------------------------------------

--
-- Table structure for table `events`
--

CREATE TABLE `events` (
  `event_id` int UNSIGNED NOT NULL,
  `event_code` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `event_name` varchar(150) COLLATE utf8mb4_unicode_ci NOT NULL,
  `organizer` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `manager_id` int UNSIGNED DEFAULT NULL,
  `level` enum('Cấp trường','Cấp khoa','Cấp đơn vị') COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `semester` varchar(10) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `academic_year` varchar(15) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `start_time` datetime NOT NULL,
  `end_time` datetime NOT NULL,
  `registration_deadline` datetime DEFAULT NULL,
  `location` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `target_faculty` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `target_class` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `target_gender` enum('Nam','Nữ','Tất cả') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'Tất cả',
  `description` text COLLATE utf8mb4_unicode_ci,
  `image_url` varchar(500) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Đường dẫn ảnh sự kiện',
  `is_recruiting` tinyint(1) NOT NULL DEFAULT '0',
  `max_participants` int DEFAULT NULL,
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `events`
--

INSERT INTO `events` (`event_id`, `event_code`, `event_name`, `organizer`, `manager_id`, `level`, `semester`, `academic_year`, `start_time`, `end_time`, `registration_deadline`, `location`, `target_faculty`, `target_class`, `target_gender`, `description`, `image_url`, `is_recruiting`, `max_participants`, `created_at`) VALUES
(35, '2025HKI004', 'Hội chợ sinh viên', 'Phòng CTSV', 129, NULL, 'HKI', '2025', '2025-11-20 12:36:00', '2025-11-23 13:20:00', '2025-11-19 12:36:00', 'D5', 'Tất cả', NULL, 'Tất cả', NULL, 'http://127.0.0.1:8000/storage/events/9tYXvd9Aeiqk9WSfi7RfjYwp0ho756MUy3mZL9lh.jpg', 1, 5, '2025-11-10 22:18:29'),
(38, '2025HKI007', 'Chào mừng ngày Nhà giáo Việt Nam', 'Đoàn trường', 129, NULL, 'HKI', '2025', '2025-11-18 00:55:00', '2025-12-06 00:55:00', '2025-11-17 00:55:00', 'Hội trường D1', 'Công nghệ thông tin & Kinh tế số', NULL, 'Tất cả', 'Sự kiện mở ra để hưởng ứng phòng trào chào đón ngày 20/11', 'http://127.0.0.1:8000/storage/events/nV3Li0HikNHF3dUkmtY4lD7i4mvilzURqpwQXqZF.jpg', 0, 2, '2025-11-11 00:56:29'),
(41, '2025HKI008', 'Chào mừng ngày Quốc Khánh 2025', 'Đoàn trường', 130, NULL, 'HKI', '2025', '2025-11-14 23:47:00', '2025-11-15 23:16:00', '2025-11-14 23:47:00', 'Hội trường D1', 'Tất cả', NULL, 'Tất cả', NULL, 'http://127.0.0.1:8000/storage/events/afwO9IDSM1eoSBUB5kjnPJgEt3WBRkDEGB2wIahc.jpg', 1, 2, '2025-11-14 23:26:56'),
(42, '2025HKI009', 'Chào tân khóa K28', 'Đoàn trường', 129, NULL, 'HKI', '2025', '2025-11-16 16:19:00', '2025-11-16 16:20:00', '2025-11-15 12:38:00', 'D5', 'Tất cả', NULL, 'Tất cả', 'Những cống hiến thầm lặng và tận tâm của thầy cô là nguồn cảm hứng lớn lao, góp phần xây dựng nên một môi trường học tập sáng tạo và hiện đại.\r\nKính chúc quý thầy cô luôn mạnh khỏe, hạnh phúc và tiếp tục đồng hành cùng sinh viên trên hành trình tri thức.\r\nXin trân trọng cảm ơn và kính chúc ngày 20/11 thật nhiều niềm vui và ý nghĩa.', 'http://127.0.0.1:8000/storage/events/MfpsQqTNwJOTIiOaiWkuTUGjxp2PJVyVfp7yTi4e.webp', 0, 5, '2025-11-16 12:38:12'),
(43, '2025HKI010', 'Giáo dục giới tính học đường', 'Đoàn trường', 129, NULL, 'HKI', '2025', '2025-11-19 23:25:00', '2025-11-20 16:00:00', '2025-11-17 16:00:00', 'D5', 'Tất cả', NULL, 'Nữ', 'Kính chúc quý thầy cô luôn mạnh khỏe, hạnh phúc và tiếp tục đồng hành cùng sinh viên trên hành trình tri thức.\r\nXin trân trọng cảm ơn và kính chúc ngày 20/11 thật nhiều niềm vui và ý nghĩa.', 'http://127.0.0.1:8000/storage/events/9XFPP9BFL8vrERSQ4KHxH1BANY7zyQkeMc6K23al.webp', 0, 5, '2025-11-16 16:01:21'),
(44, '2025HKI011', 'Chào tân khoa Công nghệ thông tin & Kinh tế số khóa 28', 'Khoa CNTT & KTS', 130, NULL, 'HKI', '2025', '2025-11-19 16:14:00', '2025-12-27 16:14:00', '2025-11-12 04:15:00', 'sân D5', 'Công nghệ thông tin & Kinh tế số', NULL, 'Tất cả', 'Chào mừng sinh viên mới', 'http://127.0.0.1:8000/storage/events/MtKnHXq5AeNrG3YQ6t6cBHeGIUccRTcR8tzfRglC.jpg', 1, 5, '2025-11-16 16:16:15'),
(45, '2025HKII001', 'Hội chợ việc làm 2026', 'Đoàn trường', 129, NULL, 'HKII', '2025', '2025-11-19 22:27:00', '2025-11-29 22:27:00', '2025-11-18 22:27:00', 'sân D5', NULL, NULL, 'Tất cả', NULL, 'http://127.0.0.1:8000/storage/events/ewxGRrhxM6G0LzaBnwmodt4VsN7LmpU7Rk5Gd874.webp', 0, 5, '2025-11-18 22:27:55'),
(46, '2025HKI001', 'Giải thể thao sinh viên', 'Đoàn trường', 130, NULL, 'HKI', '2025', '2025-11-18 22:38:00', '2025-11-26 22:39:00', '2025-11-17 22:39:00', 'Hội trường D1', NULL, NULL, 'Tất cả', 'Ngày hội thể thao học sinh – sinh viên là một trong những sự kiện ngoại khóa ý nghĩa, được tổ chức thường niên tại trường học, đại học, cao đẳng hoặc trung tâm đào tạo.Sự kiện bao gồm nhiều hoạt động thể thao, trò chơi vận động và thi đấu giao lưu như: bóng đá, bóng chuyền, cầu lông, kéo co, nhảy bao bố, chạy tiếp sức, v.v.', 'http://127.0.0.1:8000/storage/events/0broVxEAm8cmVSul4SYzbphlVNXJuQNK1xIaYeEq.jpg', 0, 5, '2025-11-18 22:40:07'),
(47, '2025HKII002', 'Hội chợ việc làm - Cầu nối nhân lực 2025', 'Phòng CTSV', 302, NULL, 'HKII', '2025', '2025-11-18 13:31:00', '2025-11-19 01:31:00', '2025-11-17 13:31:00', 'Hội trường D1', 'Tất cả', NULL, 'Tất cả', 'Hội chợ việc làm là sự kiện hàng năm của Học viện Ngân hàng, nơi đây quy tụ nhiều ngân hàng tiềm năng và cơ hội việc làm cho sinh viên', 'http://127.0.0.1:8000/storage/events/oC5ToB1IGzCX95b3aDGAWkQDDFUdzQfiusdOHU7W.webp', 1, 30, '2025-11-19 13:32:57'),
(49, '2025HKII003', 'Giải đáp cơ hội việc làm trong thời đại công nghệ số', 'CTSV', 129, NULL, 'HKII', '2027', '2025-11-16 18:56:00', '2025-11-20 18:56:00', '2025-11-10 18:56:00', 'Hội trường D1', 'Tất cả', NULL, 'Tất cả', NULL, 'http://127.0.0.1:8000/storage/events/C73Dbe6RjAy6CedyVzXIkN9svt5qO2b8DeXHrHTC.webp', 0, 5, '2025-11-21 18:57:45');

-- --------------------------------------------------------

--
-- Table structure for table `event_registration`
--

CREATE TABLE `event_registration` (
  `registration_id` int UNSIGNED NOT NULL,
  `event_id` int UNSIGNED NOT NULL,
  `user_id` int UNSIGNED NOT NULL,
  `register_date` datetime DEFAULT CURRENT_TIMESTAMP,
  `status` enum('Đã đăng ký','Đã tham gia','Đã hủy') COLLATE utf8mb4_unicode_ci DEFAULT 'Đã đăng ký',
  `note` text COLLATE utf8mb4_unicode_ci
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `event_registration`
--

INSERT INTO `event_registration` (`registration_id`, `event_id`, `user_id`, `register_date`, `status`, `note`) VALUES
(27, 47, 253, '2025-11-19 13:37:20', 'Đã đăng ký', NULL),
(28, 43, 253, '2025-11-20 23:09:50', 'Đã đăng ký', NULL),
(29, 38, 253, '2025-11-20 23:10:56', 'Đã đăng ký', NULL),
(30, 43, 260, '2025-11-20 23:15:05', 'Đã đăng ký', NULL),
(31, 45, 260, '2025-11-20 23:22:17', 'Đã đăng ký', NULL),
(32, 44, 260, '2025-11-20 23:25:12', 'Đã đăng ký', NULL),
(33, 49, 260, '2025-11-21 19:05:29', 'Đã đăng ký', NULL),
(34, 43, 255, '2025-11-21 19:48:54', 'Đã đăng ký', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `feedbacks`
--

CREATE TABLE `feedbacks` (
  `feedback_id` int UNSIGNED NOT NULL,
  `event_id` int UNSIGNED NOT NULL,
  `user_id` int UNSIGNED NOT NULL,
  `content` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `feedback_replies`
--

CREATE TABLE `feedback_replies` (
  `reply_id` int UNSIGNED NOT NULL,
  `feedback_id` int UNSIGNED NOT NULL,
  `sender_id` int UNSIGNED NOT NULL,
  `receiver_id` int UNSIGNED NOT NULL,
  `content` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `migrations`
--

CREATE TABLE `migrations` (
  `id` int UNSIGNED NOT NULL,
  `migration` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
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
(6, '2025_11_01_123906_add_event_id_to_notifications_table', 6),
(7, '2025_11_05_010655_add_target_fields_to_events_table', 7);

-- --------------------------------------------------------

--
-- Table structure for table `notifications`
--

CREATE TABLE `notifications` (
  `id` bigint UNSIGNED NOT NULL,
  `user_id` int UNSIGNED DEFAULT NULL,
  `event_id` bigint UNSIGNED DEFAULT NULL,
  `title` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `message` text COLLATE utf8mb4_unicode_ci,
  `type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'general',
  `is_read` tinyint(1) NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `notifications`
--

INSERT INTO `notifications` (`id`, `user_id`, `event_id`, `title`, `message`, `type`, `is_read`, `created_at`, `updated_at`) VALUES
(15, 1, 18, 'Phản hồi mới', 'Nguyễn Văn d đã gửi phản hồi trong sự kiện \'1\'.', 'feedback', 0, '2025-11-01 07:28:09', '2025-11-01 07:28:09'),
(16, 1, 18, 'Phản hồi mới', 'Nguyễn Văn d đã gửi phản hồi trong sự kiện1 \'1\'.', 'feedback', 0, '2025-11-01 07:30:22', '2025-11-01 07:30:22'),
(19, 1, 18, 'Phản hồi mới từ sinh viên', 'Nguyễn Văn d đã gửi phản hồi trong sự kiện \'1\'.', 'feedback', 0, '2025-11-01 08:02:24', '2025-11-01 08:02:24'),
(20, NULL, 19, '🎉 Sự kiện mới: 2', 'Giáo viên vừa tạo sự kiện \"2\". Hãy xem chi tiết và đăng ký tham gia nhé!', 'new_event', 0, '2025-11-01 09:20:26', '2025-11-01 09:20:26'),
(21, NULL, 20, '🎉 Sự kiện mới: 3', 'Giáo viên vừa tạo sự kiện \"3\". Hãy xem chi tiết và đăng ký tham gia nhé!', 'new_event', 0, '2025-11-01 09:57:34', '2025-11-01 09:57:34'),
(144, 253, 47, '📢 Huy động tham gia sự kiện: Hội chợ việc làm - Cầu nối nhân lực 2025', 'Một sự kiện mới dành cho bạn: \"Hội chợ việc làm - Cầu nối nhân lực 2025\" diễn ra tại Hội trường D1', 'recruit_event', 0, '2025-11-19 06:33:25', '2025-11-19 06:33:25'),
(145, 254, 47, '📢 Huy động tham gia sự kiện: Hội chợ việc làm - Cầu nối nhân lực 2025', 'Một sự kiện mới dành cho bạn: \"Hội chợ việc làm - Cầu nối nhân lực 2025\" diễn ra tại Hội trường D1', 'recruit_event', 0, '2025-11-19 06:33:25', '2025-11-19 06:33:25'),
(146, 255, 47, '📢 Huy động tham gia sự kiện: Hội chợ việc làm - Cầu nối nhân lực 2025', 'Một sự kiện mới dành cho bạn: \"Hội chợ việc làm - Cầu nối nhân lực 2025\" diễn ra tại Hội trường D1', 'recruit_event', 0, '2025-11-19 06:33:25', '2025-11-19 06:33:25'),
(147, 256, 47, '📢 Huy động tham gia sự kiện: Hội chợ việc làm - Cầu nối nhân lực 2025', 'Một sự kiện mới dành cho bạn: \"Hội chợ việc làm - Cầu nối nhân lực 2025\" diễn ra tại Hội trường D1', 'recruit_event', 0, '2025-11-19 06:33:25', '2025-11-19 06:33:25'),
(148, 257, 47, '📢 Huy động tham gia sự kiện: Hội chợ việc làm - Cầu nối nhân lực 2025', 'Một sự kiện mới dành cho bạn: \"Hội chợ việc làm - Cầu nối nhân lực 2025\" diễn ra tại Hội trường D1', 'recruit_event', 0, '2025-11-19 06:33:25', '2025-11-19 06:33:25'),
(149, 258, 47, '📢 Huy động tham gia sự kiện: Hội chợ việc làm - Cầu nối nhân lực 2025', 'Một sự kiện mới dành cho bạn: \"Hội chợ việc làm - Cầu nối nhân lực 2025\" diễn ra tại Hội trường D1', 'recruit_event', 0, '2025-11-19 06:33:25', '2025-11-19 06:33:25'),
(150, 259, 47, '📢 Huy động tham gia sự kiện: Hội chợ việc làm - Cầu nối nhân lực 2025', 'Một sự kiện mới dành cho bạn: \"Hội chợ việc làm - Cầu nối nhân lực 2025\" diễn ra tại Hội trường D1', 'recruit_event', 0, '2025-11-19 06:33:25', '2025-11-19 06:33:25'),
(151, 260, 47, '📢 Huy động tham gia sự kiện: Hội chợ việc làm - Cầu nối nhân lực 2025', 'Một sự kiện mới dành cho bạn: \"Hội chợ việc làm - Cầu nối nhân lực 2025\" diễn ra tại Hội trường D1', 'recruit_event', 0, '2025-11-19 06:33:25', '2025-11-19 06:33:25'),
(152, 261, 47, '📢 Huy động tham gia sự kiện: Hội chợ việc làm - Cầu nối nhân lực 2025', 'Một sự kiện mới dành cho bạn: \"Hội chợ việc làm - Cầu nối nhân lực 2025\" diễn ra tại Hội trường D1', 'recruit_event', 0, '2025-11-19 06:33:25', '2025-11-19 06:33:25'),
(153, 262, 47, '📢 Huy động tham gia sự kiện: Hội chợ việc làm - Cầu nối nhân lực 2025', 'Một sự kiện mới dành cho bạn: \"Hội chợ việc làm - Cầu nối nhân lực 2025\" diễn ra tại Hội trường D1', 'recruit_event', 0, '2025-11-19 06:33:25', '2025-11-19 06:33:25'),
(154, 263, 47, '📢 Huy động tham gia sự kiện: Hội chợ việc làm - Cầu nối nhân lực 2025', 'Một sự kiện mới dành cho bạn: \"Hội chợ việc làm - Cầu nối nhân lực 2025\" diễn ra tại Hội trường D1', 'recruit_event', 0, '2025-11-19 06:33:25', '2025-11-19 06:33:25'),
(155, 264, 47, '📢 Huy động tham gia sự kiện: Hội chợ việc làm - Cầu nối nhân lực 2025', 'Một sự kiện mới dành cho bạn: \"Hội chợ việc làm - Cầu nối nhân lực 2025\" diễn ra tại Hội trường D1', 'recruit_event', 0, '2025-11-19 06:33:25', '2025-11-19 06:33:25'),
(156, 265, 47, '📢 Huy động tham gia sự kiện: Hội chợ việc làm - Cầu nối nhân lực 2025', 'Một sự kiện mới dành cho bạn: \"Hội chợ việc làm - Cầu nối nhân lực 2025\" diễn ra tại Hội trường D1', 'recruit_event', 0, '2025-11-19 06:33:25', '2025-11-19 06:33:25'),
(157, 266, 47, '📢 Huy động tham gia sự kiện: Hội chợ việc làm - Cầu nối nhân lực 2025', 'Một sự kiện mới dành cho bạn: \"Hội chợ việc làm - Cầu nối nhân lực 2025\" diễn ra tại Hội trường D1', 'recruit_event', 0, '2025-11-19 06:33:25', '2025-11-19 06:33:25'),
(158, 267, 47, '📢 Huy động tham gia sự kiện: Hội chợ việc làm - Cầu nối nhân lực 2025', 'Một sự kiện mới dành cho bạn: \"Hội chợ việc làm - Cầu nối nhân lực 2025\" diễn ra tại Hội trường D1', 'recruit_event', 0, '2025-11-19 06:33:25', '2025-11-19 06:33:25'),
(159, 268, 47, '📢 Huy động tham gia sự kiện: Hội chợ việc làm - Cầu nối nhân lực 2025', 'Một sự kiện mới dành cho bạn: \"Hội chợ việc làm - Cầu nối nhân lực 2025\" diễn ra tại Hội trường D1', 'recruit_event', 0, '2025-11-19 06:33:25', '2025-11-19 06:33:25'),
(160, 269, 47, '📢 Huy động tham gia sự kiện: Hội chợ việc làm - Cầu nối nhân lực 2025', 'Một sự kiện mới dành cho bạn: \"Hội chợ việc làm - Cầu nối nhân lực 2025\" diễn ra tại Hội trường D1', 'recruit_event', 0, '2025-11-19 06:33:25', '2025-11-19 06:33:25'),
(161, 270, 47, '📢 Huy động tham gia sự kiện: Hội chợ việc làm - Cầu nối nhân lực 2025', 'Một sự kiện mới dành cho bạn: \"Hội chợ việc làm - Cầu nối nhân lực 2025\" diễn ra tại Hội trường D1', 'recruit_event', 0, '2025-11-19 06:33:25', '2025-11-19 06:33:25'),
(162, 271, 47, '📢 Huy động tham gia sự kiện: Hội chợ việc làm - Cầu nối nhân lực 2025', 'Một sự kiện mới dành cho bạn: \"Hội chợ việc làm - Cầu nối nhân lực 2025\" diễn ra tại Hội trường D1', 'recruit_event', 0, '2025-11-19 06:33:25', '2025-11-19 06:33:25'),
(163, 272, 47, '📢 Huy động tham gia sự kiện: Hội chợ việc làm - Cầu nối nhân lực 2025', 'Một sự kiện mới dành cho bạn: \"Hội chợ việc làm - Cầu nối nhân lực 2025\" diễn ra tại Hội trường D1', 'recruit_event', 0, '2025-11-19 06:33:25', '2025-11-19 06:33:25'),
(164, 273, 47, '📢 Huy động tham gia sự kiện: Hội chợ việc làm - Cầu nối nhân lực 2025', 'Một sự kiện mới dành cho bạn: \"Hội chợ việc làm - Cầu nối nhân lực 2025\" diễn ra tại Hội trường D1', 'recruit_event', 0, '2025-11-19 06:33:25', '2025-11-19 06:33:25'),
(165, 274, 47, '📢 Huy động tham gia sự kiện: Hội chợ việc làm - Cầu nối nhân lực 2025', 'Một sự kiện mới dành cho bạn: \"Hội chợ việc làm - Cầu nối nhân lực 2025\" diễn ra tại Hội trường D1', 'recruit_event', 0, '2025-11-19 06:33:25', '2025-11-19 06:33:25'),
(166, 275, 47, '📢 Huy động tham gia sự kiện: Hội chợ việc làm - Cầu nối nhân lực 2025', 'Một sự kiện mới dành cho bạn: \"Hội chợ việc làm - Cầu nối nhân lực 2025\" diễn ra tại Hội trường D1', 'recruit_event', 0, '2025-11-19 06:33:25', '2025-11-19 06:33:25'),
(167, 276, 47, '📢 Huy động tham gia sự kiện: Hội chợ việc làm - Cầu nối nhân lực 2025', 'Một sự kiện mới dành cho bạn: \"Hội chợ việc làm - Cầu nối nhân lực 2025\" diễn ra tại Hội trường D1', 'recruit_event', 0, '2025-11-19 06:33:25', '2025-11-19 06:33:25'),
(168, 277, 47, '📢 Huy động tham gia sự kiện: Hội chợ việc làm - Cầu nối nhân lực 2025', 'Một sự kiện mới dành cho bạn: \"Hội chợ việc làm - Cầu nối nhân lực 2025\" diễn ra tại Hội trường D1', 'recruit_event', 0, '2025-11-19 06:33:25', '2025-11-19 06:33:25'),
(169, 278, 47, '📢 Huy động tham gia sự kiện: Hội chợ việc làm - Cầu nối nhân lực 2025', 'Một sự kiện mới dành cho bạn: \"Hội chợ việc làm - Cầu nối nhân lực 2025\" diễn ra tại Hội trường D1', 'recruit_event', 0, '2025-11-19 06:33:25', '2025-11-19 06:33:25'),
(170, 279, 47, '📢 Huy động tham gia sự kiện: Hội chợ việc làm - Cầu nối nhân lực 2025', 'Một sự kiện mới dành cho bạn: \"Hội chợ việc làm - Cầu nối nhân lực 2025\" diễn ra tại Hội trường D1', 'recruit_event', 0, '2025-11-19 06:33:25', '2025-11-19 06:33:25'),
(171, 280, 47, '📢 Huy động tham gia sự kiện: Hội chợ việc làm - Cầu nối nhân lực 2025', 'Một sự kiện mới dành cho bạn: \"Hội chợ việc làm - Cầu nối nhân lực 2025\" diễn ra tại Hội trường D1', 'recruit_event', 0, '2025-11-19 06:33:25', '2025-11-19 06:33:25'),
(172, 281, 47, '📢 Huy động tham gia sự kiện: Hội chợ việc làm - Cầu nối nhân lực 2025', 'Một sự kiện mới dành cho bạn: \"Hội chợ việc làm - Cầu nối nhân lực 2025\" diễn ra tại Hội trường D1', 'recruit_event', 0, '2025-11-19 06:33:25', '2025-11-19 06:33:25'),
(173, 282, 47, '📢 Huy động tham gia sự kiện: Hội chợ việc làm - Cầu nối nhân lực 2025', 'Một sự kiện mới dành cho bạn: \"Hội chợ việc làm - Cầu nối nhân lực 2025\" diễn ra tại Hội trường D1', 'recruit_event', 0, '2025-11-19 06:33:25', '2025-11-19 06:33:25'),
(174, 283, 47, '📢 Huy động tham gia sự kiện: Hội chợ việc làm - Cầu nối nhân lực 2025', 'Một sự kiện mới dành cho bạn: \"Hội chợ việc làm - Cầu nối nhân lực 2025\" diễn ra tại Hội trường D1', 'recruit_event', 0, '2025-11-19 06:33:25', '2025-11-19 06:33:25'),
(175, 284, 47, '📢 Huy động tham gia sự kiện: Hội chợ việc làm - Cầu nối nhân lực 2025', 'Một sự kiện mới dành cho bạn: \"Hội chợ việc làm - Cầu nối nhân lực 2025\" diễn ra tại Hội trường D1', 'recruit_event', 0, '2025-11-19 06:33:25', '2025-11-19 06:33:25'),
(176, 285, 47, '📢 Huy động tham gia sự kiện: Hội chợ việc làm - Cầu nối nhân lực 2025', 'Một sự kiện mới dành cho bạn: \"Hội chợ việc làm - Cầu nối nhân lực 2025\" diễn ra tại Hội trường D1', 'recruit_event', 0, '2025-11-19 06:33:25', '2025-11-19 06:33:25'),
(177, 286, 47, '📢 Huy động tham gia sự kiện: Hội chợ việc làm - Cầu nối nhân lực 2025', 'Một sự kiện mới dành cho bạn: \"Hội chợ việc làm - Cầu nối nhân lực 2025\" diễn ra tại Hội trường D1', 'recruit_event', 0, '2025-11-19 06:33:25', '2025-11-19 06:33:25'),
(178, 287, 47, '📢 Huy động tham gia sự kiện: Hội chợ việc làm - Cầu nối nhân lực 2025', 'Một sự kiện mới dành cho bạn: \"Hội chợ việc làm - Cầu nối nhân lực 2025\" diễn ra tại Hội trường D1', 'recruit_event', 0, '2025-11-19 06:33:25', '2025-11-19 06:33:25'),
(179, 288, 47, '📢 Huy động tham gia sự kiện: Hội chợ việc làm - Cầu nối nhân lực 2025', 'Một sự kiện mới dành cho bạn: \"Hội chợ việc làm - Cầu nối nhân lực 2025\" diễn ra tại Hội trường D1', 'recruit_event', 0, '2025-11-19 06:33:25', '2025-11-19 06:33:25'),
(180, 289, 47, '📢 Huy động tham gia sự kiện: Hội chợ việc làm - Cầu nối nhân lực 2025', 'Một sự kiện mới dành cho bạn: \"Hội chợ việc làm - Cầu nối nhân lực 2025\" diễn ra tại Hội trường D1', 'recruit_event', 0, '2025-11-19 06:33:25', '2025-11-19 06:33:25'),
(181, 290, 47, '📢 Huy động tham gia sự kiện: Hội chợ việc làm - Cầu nối nhân lực 2025', 'Một sự kiện mới dành cho bạn: \"Hội chợ việc làm - Cầu nối nhân lực 2025\" diễn ra tại Hội trường D1', 'recruit_event', 0, '2025-11-19 06:33:25', '2025-11-19 06:33:25'),
(182, 291, 47, '📢 Huy động tham gia sự kiện: Hội chợ việc làm - Cầu nối nhân lực 2025', 'Một sự kiện mới dành cho bạn: \"Hội chợ việc làm - Cầu nối nhân lực 2025\" diễn ra tại Hội trường D1', 'recruit_event', 0, '2025-11-19 06:33:25', '2025-11-19 06:33:25'),
(183, 292, 47, '📢 Huy động tham gia sự kiện: Hội chợ việc làm - Cầu nối nhân lực 2025', 'Một sự kiện mới dành cho bạn: \"Hội chợ việc làm - Cầu nối nhân lực 2025\" diễn ra tại Hội trường D1', 'recruit_event', 0, '2025-11-19 06:33:25', '2025-11-19 06:33:25'),
(184, 293, 47, '📢 Huy động tham gia sự kiện: Hội chợ việc làm - Cầu nối nhân lực 2025', 'Một sự kiện mới dành cho bạn: \"Hội chợ việc làm - Cầu nối nhân lực 2025\" diễn ra tại Hội trường D1', 'recruit_event', 0, '2025-11-19 06:33:25', '2025-11-19 06:33:25'),
(185, 294, 47, '📢 Huy động tham gia sự kiện: Hội chợ việc làm - Cầu nối nhân lực 2025', 'Một sự kiện mới dành cho bạn: \"Hội chợ việc làm - Cầu nối nhân lực 2025\" diễn ra tại Hội trường D1', 'recruit_event', 0, '2025-11-19 06:33:25', '2025-11-19 06:33:25'),
(186, 295, 47, '📢 Huy động tham gia sự kiện: Hội chợ việc làm - Cầu nối nhân lực 2025', 'Một sự kiện mới dành cho bạn: \"Hội chợ việc làm - Cầu nối nhân lực 2025\" diễn ra tại Hội trường D1', 'recruit_event', 0, '2025-11-19 06:33:25', '2025-11-19 06:33:25'),
(187, 296, 47, '📢 Huy động tham gia sự kiện: Hội chợ việc làm - Cầu nối nhân lực 2025', 'Một sự kiện mới dành cho bạn: \"Hội chợ việc làm - Cầu nối nhân lực 2025\" diễn ra tại Hội trường D1', 'recruit_event', 0, '2025-11-19 06:33:25', '2025-11-19 06:33:25'),
(188, 297, 47, '📢 Huy động tham gia sự kiện: Hội chợ việc làm - Cầu nối nhân lực 2025', 'Một sự kiện mới dành cho bạn: \"Hội chợ việc làm - Cầu nối nhân lực 2025\" diễn ra tại Hội trường D1', 'recruit_event', 0, '2025-11-19 06:33:25', '2025-11-19 06:33:25'),
(189, 298, 47, '📢 Huy động tham gia sự kiện: Hội chợ việc làm - Cầu nối nhân lực 2025', 'Một sự kiện mới dành cho bạn: \"Hội chợ việc làm - Cầu nối nhân lực 2025\" diễn ra tại Hội trường D1', 'recruit_event', 0, '2025-11-19 06:33:25', '2025-11-19 06:33:25'),
(190, 299, 47, '📢 Huy động tham gia sự kiện: Hội chợ việc làm - Cầu nối nhân lực 2025', 'Một sự kiện mới dành cho bạn: \"Hội chợ việc làm - Cầu nối nhân lực 2025\" diễn ra tại Hội trường D1', 'recruit_event', 0, '2025-11-19 06:33:25', '2025-11-19 06:33:25'),
(191, 300, 47, '📢 Huy động tham gia sự kiện: Hội chợ việc làm - Cầu nối nhân lực 2025', 'Một sự kiện mới dành cho bạn: \"Hội chợ việc làm - Cầu nối nhân lực 2025\" diễn ra tại Hội trường D1', 'recruit_event', 0, '2025-11-19 06:33:25', '2025-11-19 06:33:25'),
(192, 301, 47, '📢 Huy động tham gia sự kiện: Hội chợ việc làm - Cầu nối nhân lực 2025', 'Một sự kiện mới dành cho bạn: \"Hội chợ việc làm - Cầu nối nhân lực 2025\" diễn ra tại Hội trường D1', 'recruit_event', 0, '2025-11-19 06:33:25', '2025-11-19 06:33:25');

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
(26, 15, 1, '2025-11-16 09:23:59', NULL, NULL),
(27, 16, 1, '2025-11-16 09:23:59', NULL, NULL),
(30, 19, 1, '2025-11-16 09:23:59', NULL, NULL),
(31, 20, 19, '2025-11-02 04:55:13', NULL, NULL),
(32, 20, 17, '2025-11-01 09:22:08', NULL, NULL),
(33, 20, 1, '2025-11-16 09:23:59', NULL, NULL),
(34, 21, 1, '2025-11-16 09:23:59', NULL, NULL),
(35, 21, 19, '2025-11-02 04:55:13', NULL, NULL),
(36, 20, 49, '2025-11-08 08:25:41', NULL, NULL),
(37, 21, 49, '2025-11-08 08:25:41', NULL, NULL),
(41, 20, 55, '2025-11-08 12:07:51', NULL, NULL),
(42, 21, 55, '2025-11-08 12:07:51', NULL, NULL),
(45, 20, 133, '2025-11-14 16:47:00', NULL, NULL),
(46, 21, 133, '2025-11-14 16:47:00', NULL, NULL),
(48, 20, 182, '2025-11-16 09:25:04', NULL, NULL),
(49, 21, 182, '2025-11-16 09:25:04', NULL, NULL),
(50, 20, 183, '2025-11-17 16:24:37', NULL, NULL),
(51, 21, 183, '2025-11-17 16:24:37', NULL, NULL),
(52, 144, 253, '2025-11-19 06:42:09', NULL, NULL),
(53, 20, 253, '2025-11-19 06:42:09', NULL, NULL),
(54, 21, 253, '2025-11-19 06:42:09', NULL, NULL),
(55, 151, 260, '2025-11-21 11:58:57', NULL, NULL),
(56, 20, 260, '2025-11-21 11:58:57', NULL, NULL),
(57, 21, 260, '2025-11-21 11:58:57', NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `reports`
--

CREATE TABLE `reports` (
  `report_id` int UNSIGNED NOT NULL,
  `created_by` int UNSIGNED NOT NULL,
  `type` enum('Theo sự kiện','Theo khoa','Theo lớp','Theo sinh viên') COLLATE utf8mb4_unicode_ci NOT NULL,
  `parameters` text COLLATE utf8mb4_unicode_ci,
  `result_url` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `tasks`
--

CREATE TABLE `tasks` (
  `task_id` int UNSIGNED NOT NULL,
  `event_id` int UNSIGNED NOT NULL,
  `task_name` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text COLLATE utf8mb4_unicode_ci,
  `required_number` int DEFAULT NULL,
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `task_assignment`
--

CREATE TABLE `task_assignment` (
  `assignment_id` int UNSIGNED NOT NULL,
  `task_id` int UNSIGNED NOT NULL,
  `user_id` int UNSIGNED NOT NULL,
  `assigned_at` datetime DEFAULT CURRENT_TIMESTAMP,
  `status` enum('Chưa làm','Đang làm','Hoàn thành') COLLATE utf8mb4_unicode_ci DEFAULT 'Chưa làm'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `user_id` int UNSIGNED NOT NULL,
  `username` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `password` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `full_name` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `dob` date DEFAULT NULL,
  `gender` enum('Nam','Nữ','Khác') COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `phone` varchar(15) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `email` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `class` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `faculty` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `role` enum('admin','student') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'student',
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`user_id`, `username`, `password`, `full_name`, `dob`, `gender`, `phone`, `email`, `class`, `faculty`, `role`, `created_at`) VALUES
(1, 'admin', '7c222fb2927d828af22f592134e8932480637c0d', 'System Admin12', '1990-01-01', 'Nam', '0900000001', 'admin@example.com', NULL, 'IT', 'admin', '2025-10-18 09:29:16'),
(129, 'CBQL1', '7c222fb2927d828af22f592134e8932480637c0d', 'Bùi Tiến Sơn', NULL, 'Nam', '23299819', 'a@gmail.com', NULL, 'Trưởng phòng', 'admin', '2025-11-10 19:03:43'),
(130, 'CBQL2', '7c222fb2927d828af22f592134e8932480637c0d', 'Bùi Hồng Nhung', NULL, 'Nữ', '23299819', 'a@gmail.com', NULL, 'Trưởng phòng', 'admin', '2025-11-10 19:03:43'),
(131, 'CBQL3', '7c222fb2927d828af22f592134e8932480637c0d', 'Cao Lê Tiến Anh', NULL, 'Nam', '23299819', 'a@gmail.com', NULL, 'Cộng tác viên', 'admin', '2025-11-10 19:03:43'),
(132, 'CBQL4', '7c222fb2927d828af22f592134e8932480637c0d', 'Phạm Nhật Vượng', NULL, 'Nam', '23299819', 'a@gmail.com', NULL, 'Trưởng phòng', 'admin', '2025-11-10 19:03:43'),
(232, 'CBQL6', '7c222fb2927d828af22f592134e8932480637c0d', 'Bùi Văn Hùng', NULL, 'Nam', '23299819', 'a@gmail.com', NULL, 'Trưởng phòng', 'admin', '2025-11-16 16:13:29'),
(253, '25A4041510', '7c222fb2927d828af22f592134e8932480637c0d', 'Nguyễn Thị Thu Huế', NULL, 'Nữ', '901112223', 'huehuebx13082004@gmail.com', 'K26HTTTA', 'Khoa CNTT và Kinh tế số', 'student', '2025-11-19 13:29:42'),
(254, '25A4041483', '7c222fb2927d828af22f592134e8932480637c0d', 'Cao Lê Minh Châu', NULL, 'Nữ', '903334444', 'mchau291004@gmail.com', 'K26HTTTA', 'Khoa CNTT và Kinh tế số', 'student', '2025-11-19 13:29:42'),
(255, '25A4042256', '7c222fb2927d828af22f592134e8932480637c0d', 'Trần Thi Hải Yến', NULL, 'Nữ', '905556666', 'haiyen812204@gmail.com', 'K26HTTTA', 'Khoa CNTT và Kinh tế số', 'student', '2025-11-19 13:29:42'),
(256, '27A4011010', '7c222fb2927d828af22f592134e8932480637c0d', 'Đặng Mai Anh', NULL, 'Nữ', '901112222', 'maianh@gmail.com', 'K26HTTTA', 'Khoa CNTT và Kinh tế số', 'student', '2025-11-19 13:29:42'),
(257, '27A4011011', '7c222fb2927d828af22f592134e8932480637c0d', 'Nguyễn Huy Cương', NULL, 'Nam', '903334444', 'binh@gmail.com', 'K26HTTTA', 'Khoa CNTT và Kinh tế số', 'student', '2025-11-19 13:29:42'),
(258, '27A4011012', '7c222fb2927d828af22f592134e8932480637c0d', 'Nguyễn Thị Dịu', NULL, 'Nữ', '905556666', 'huongnhang@gmail.com', 'K26HTTTA', 'Khoa CNTT và Kinh tế số', 'student', '2025-11-19 13:29:42'),
(259, '27A4011013', '7c222fb2927d828af22f592134e8932480637c0d', 'Phạm Trung Đức', NULL, 'Nam', '901112222', 'huongnhang@gmail.com', 'K27HTTTA', 'Khoa CNTT và Kinh tế số', 'student', '2025-11-19 13:29:42'),
(260, '27A4011014', '7c222fb2927d828af22f592134e8932480637c0d', 'Vũ Tiến Dũng', NULL, 'Nam', '903334444', 'huongnhang@gmail.com', 'K27HTTTA', 'Khoa CNTT và Kinh tế số', 'student', '2025-11-19 13:29:42'),
(261, '27A4011015', '7c222fb2927d828af22f592134e8932480637c0d', 'Lê Thái Dương', NULL, 'Nam', '905556666', 'huongnhang@gmail.com', 'K27HTTTA', 'Khoa CNTT và Kinh tế số', 'student', '2025-11-19 13:29:42'),
(262, '27A4011016', '7c222fb2927d828af22f592134e8932480637c0d', 'Vũ Thị Giang', NULL, 'Nữ', '901112222', 'huongnhang@gmail.com', 'K27HTTTA', 'Khoa CNTT và Kinh tế số', 'student', '2025-11-19 13:29:42'),
(263, '27A4011017', '7c222fb2927d828af22f592134e8932480637c0d', 'Bùi Thu Hà', NULL, 'Nữ', '903334444', 'huonggiang@gmail.com', 'K27HTTTA', 'Khoa CNTT và Kinh tế số', 'student', '2025-11-19 13:29:42'),
(264, '27A4011018', '7c222fb2927d828af22f592134e8932480637c0d', 'Hoàng Thị Ngọc Hà', NULL, 'Nữ', '905556666', 'huonggiang@gmail.com', 'K27HTTTA', 'Khoa CNTT và Kinh tế số', 'student', '2025-11-19 13:29:42'),
(265, '27A4011019', '7c222fb2927d828af22f592134e8932480637c0d', 'Vũ Thị Thu Hiền', NULL, 'Nữ', '901112222', 'huonggiang@gmail.com', 'K22HTTTB', 'Khoa CNTT và Kinh tế số', 'student', '2025-11-19 13:29:42'),
(266, '27A4011020', '7c222fb2927d828af22f592134e8932480637c0d', 'Lê Trung Đức Hiếu', NULL, 'Nam', '903334444', 'huonggiang@gmail.com', 'K22HTTTB', 'Khoa CNTT và Kinh tế số', 'student', '2025-11-19 13:29:42'),
(267, '27A4011021', '7c222fb2927d828af22f592134e8932480637c0d', 'Phạm Trung Hiếu', NULL, 'Nam', '905556666', 'huonggiang@gmail.com', 'K22HTTTC', 'Khoa CNTT và Kinh tế số', 'student', '2025-11-19 13:29:42'),
(268, '27A4011022', '7c222fb2927d828af22f592134e8932480637c0d', 'Nguyễn Ngọc Hưng', NULL, 'Nữ', '901112222', 'huonggiang@gmail.com', 'K22HTTTC', 'Khoa Kế toán - Kiểm toán', 'student', '2025-11-19 13:29:42'),
(269, '27A4011023', '7c222fb2927d828af22f592134e8932480637c0d', 'Đỗ Thị Lan Hương', NULL, 'Nữ', '903334444', 'huonggiang@gmail.com', 'K22HTTTC', 'Khoa Kế toán - Kiểm toán', 'student', '2025-11-19 13:29:42'),
(270, '27A4011024', '7c222fb2927d828af22f592134e8932480637c0d', 'Nguyễn Quang Huy', NULL, 'Nữ', '905556666', 'huonggiang@gmail.com', 'K22HTTTC', 'Khoa Kế toán - Kiểm toán', 'student', '2025-11-19 13:29:42'),
(271, '27A4011025', '7c222fb2927d828af22f592134e8932480637c0d', 'Phạm Khánh Huyền', NULL, 'Nữ', '905556666', 'huonggiang@gmail.com', 'K22HTTTC', 'Khoa Kế toán - Kiểm toán', 'student', '2025-11-19 13:29:42'),
(272, '27A4011026', '7c222fb2927d828af22f592134e8932480637c0d', 'Phạm Thị Thanh Huyền', NULL, 'Nữ', '905556666', 'huonggiang@gmail.com', 'K26CLCG', 'Khoa Kế toán - Kiểm toán', 'student', '2025-11-19 13:29:42'),
(273, '27A4011027', '7c222fb2927d828af22f592134e8932480637c0d', 'Nguyễn Đức Hoài Lam', NULL, 'Nam', '905556666', 'huonggiang@gmail.com', 'K26CLCG', 'Khoa Kế toán - Kiểm toán', 'student', '2025-11-19 13:29:42'),
(274, '27A4011028', '7c222fb2927d828af22f592134e8932480637c0d', 'Phạm Quang Linh', NULL, 'Nam', '905556666', 'huonggiang@gmail.com', 'K26CLCG', 'Khoa Kế toán - Kiểm toán', 'student', '2025-11-19 13:29:42'),
(275, '27A4011029', '7c222fb2927d828af22f592134e8932480637c0d', 'Trần Thùy Linh', NULL, 'Nữ', '905556666', 'huonggiang@gmail.com', 'K26CLCG', 'Khoa Kế toán - Kiểm toán', 'student', '2025-11-19 13:29:42'),
(276, '27A4011030', '7c222fb2927d828af22f592134e8932480637c0d', 'Trương Thị Loan', NULL, 'Nữ', '905556666', 'huonggiang@gmail.com', 'K26CLCG', 'Khoa Kế toán - Kiểm toán', 'student', '2025-11-19 13:29:42'),
(277, '27A4011031', '7c222fb2927d828af22f592134e8932480637c0d', 'Trần Huyền My', NULL, 'Nữ', '903334444', 'huonggiang@gmail.com', 'K26CLCG', 'Khoa Kế toán - Kiểm toán', 'student', '2025-11-19 13:29:42'),
(278, '27A4011032', '7c222fb2927d828af22f592134e8932480637c0d', 'Đỗ Thị Thu Ngân', NULL, 'Nữ', '903334444', 'namnguyen@gmail.com', 'K26CLCG', 'Khoa Kế toán - Kiểm toán', 'student', '2025-11-19 13:29:42'),
(279, '27A4011033', '7c222fb2927d828af22f592134e8932480637c0d', 'Lê Hiếu Ngân', NULL, 'Nữ', '903334444', 'namnguyen@gmail.com', 'K26KDQTA', 'Khoa Kinh doanh quốc tế', 'student', '2025-11-19 13:29:42'),
(280, '22A4010174', '7c222fb2927d828af22f592134e8932480637c0d', 'Nguyễn Thị Hồng Ngọc', NULL, 'Nữ', '903334444', 'namnguyen@gmail.com', 'K26KDQTA', 'Khoa Kinh doanh quốc tế', 'student', '2025-11-19 13:29:42'),
(281, '22A4011099', '7c222fb2927d828af22f592134e8932480637c0d', 'Nguyễn Thảo Nguyên', NULL, 'Nữ', '903334444', 'namnguyen@gmail.com', 'K26KDQTA', 'Khoa Kinh doanh quốc tế', 'student', '2025-11-19 13:29:42'),
(282, '22A4011426', '7c222fb2927d828af22f592134e8932480637c0d', 'Trần Khôi Nguyên', NULL, 'Nam', '903334444', 'namnguyen@gmail.com', 'K26KDQTA', 'Khoa Kinh doanh quốc tế', 'student', '2025-11-19 13:29:42'),
(283, '22A4010003', '7c222fb2927d828af22f592134e8932480637c0d', 'Phạm Thị Yến Nhi', NULL, 'Nữ', '903334444', 'namnguyen@gmail.com', 'K26KDQTA', 'Khoa Kinh doanh quốc tế', 'student', '2025-11-19 13:29:42'),
(284, '22A4010643', '7c222fb2927d828af22f592134e8932480637c0d', 'Vương Hữu Phúc', NULL, 'Nam', '903334444', 'namnguyen@gmail.com', 'K26KDQTA', 'Khoa Kinh doanh quốc tế', 'student', '2025-11-19 13:29:42'),
(285, '22A4010604', '7c222fb2927d828af22f592134e8932480637c0d', 'Nguyễn Hà Phương', NULL, 'Nữ', '903334444', 'namnguyen@gmail.com', 'K26KDQTA', 'Khoa Kinh doanh quốc tế', 'student', '2025-11-19 13:29:42'),
(286, '22A4010193', '7c222fb2927d828af22f592134e8932480637c0d', 'Nguyễn Lan Phương', NULL, 'Nữ', '903334444', 'namnguyen@gmail.com', 'K26NHA', 'Khoa Ngân hàng', 'student', '2025-11-19 13:29:42'),
(287, '22A4010295', '7c222fb2927d828af22f592134e8932480637c0d', 'Nguyễn Hồng Phượng', NULL, 'Nữ', '901112222', 'namnguyen@gmail.com', 'K26NHA', 'Khoa Ngân hàng', 'student', '2025-11-19 13:29:42'),
(288, '22A4010705', '7c222fb2927d828af22f592134e8932480637c0d', 'Phạm Thị Như Quỳnh', NULL, 'Nữ', '901112222', 'namnguyen@gmail.com', 'K26NHA', 'Khoa Ngân hàng', 'student', '2025-11-19 13:29:42'),
(289, '24A4012348', '7c222fb2927d828af22f592134e8932480637c0d', 'Trần Thị Minh Tâm', NULL, 'Nữ', '901112222', 'namnguyen@gmail.com', 'K26NHA', 'Khoa Ngân hàng', 'student', '2025-11-19 13:29:42'),
(290, '24A4012353', '7c222fb2927d828af22f592134e8932480637c0d', 'Đặng Thu Thảo', NULL, 'Nữ', '901112222', 'namnguyen@gmail.com', 'K26NHA', 'Khoa Ngân hàng', 'student', '2025-11-19 13:29:42'),
(291, '24A4012354', '7c222fb2927d828af22f592134e8932480637c0d', 'Nguyễn Phương Thảo', NULL, 'Nữ', '901112222', 'namnguyen@gmail.com', 'K26NHA', 'Khoa Ngân hàng', 'student', '2025-11-19 13:29:42'),
(292, '24A4012679', '7c222fb2927d828af22f592134e8932480637c0d', 'Nguyễn Bảo Thư', NULL, 'Nữ', '901112222', 'namnguyen@gmail.com', 'K26NHA', 'Khoa Ngân hàng', 'student', '2025-11-19 13:29:42'),
(293, '24A4012685', '7c222fb2927d828af22f592134e8932480637c0d', 'Đinh Thị Bích Thùy', NULL, 'Nữ', '901112222', 'namnguyen@gmail.com', 'K27NHA', 'Khoa Ngân hàng', 'student', '2025-11-19 13:29:42'),
(294, '24A4012697', '7c222fb2927d828af22f592134e8932480637c0d', 'Nguyễn Ngọc Uyên Trang', NULL, 'Nữ', '901112222', 'trangnguyen@gmail.com', 'K27NHA', 'Khoa Ngân hàng', 'student', '2025-11-19 13:29:42'),
(295, '24A4012700', '7c222fb2927d828af22f592134e8932480637c0d', 'Nguyễn Thị Tứ', NULL, 'Nữ', '901112222', 'trangnguyen@gmail.com', 'K27NHA', 'Khoa Ngân hàng', 'student', '2025-11-19 13:29:42'),
(296, '24A4012714', '7c222fb2927d828af22f592134e8932480637c0d', 'Vũ Thị Vân', NULL, 'Nữ', '901112222', 'trangnguyen@gmail.com', 'K27NHA', 'Khoa Ngân hàng', 'student', '2025-11-19 13:29:42'),
(297, '24A4012915', '7c222fb2927d828af22f592134e8932480637c0d', 'Đoàn Quang Vinh', NULL, 'Nam', '901112222', 'trangnguyen@gmail.com', 'K27NHA', 'Khoa Ngân hàng', 'student', '2025-11-19 13:29:42'),
(298, '24A4011296', '7c222fb2927d828af22f592134e8932480637c0d', 'Đào Thị Thùy Dung', NULL, 'Nữ', '901112222', 'trangnguyen@gmail.com', 'K27NHA', 'Khoa Ngân hàng', 'student', '2025-11-19 13:29:42'),
(299, '24A4012916', '7c222fb2927d828af22f592134e8932480637c0d', 'Hoàng Thị Thùy Dung', NULL, 'Nữ', '901112222', 'trangnguyen@gmail.com', 'K26KTDTA', 'Khoa Kinh tế', 'student', '2025-11-19 13:29:42'),
(300, '24A4012921', '7c222fb2927d828af22f592134e8932480637c0d', 'Lường Tùng Duy', NULL, 'Nam', '901112222', 'trangnguyen@gmail.com', 'K26KTDTA', 'Khoa Kinh tế', 'student', '2025-11-19 13:29:42'),
(301, '24A4011300', '7c222fb2927d828af22f592134e8932480637c0d', 'Trần Hương Giang', NULL, 'Nữ', '901112222', 'trangnguyen@gmail.com', 'K26KTDTA', 'Khoa Kinh tế', 'student', '2025-11-19 13:29:42'),
(302, 'CBQL7', '6df73cc169278dd6daab5fe7d6cacb1fed537131', 'Dương Hải Yến', '2025-10-01', 'Nữ', '08', 'duongyen@gmail.com', NULL, 'Chuyên viên', 'admin', '2025-11-19 13:30:57');

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
  MODIFY `attendance_id` int UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=28;

--
-- AUTO_INCREMENT for table `evaluations`
--
ALTER TABLE `evaluations`
  MODIFY `evaluation_id` int UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `events`
--
ALTER TABLE `events`
  MODIFY `event_id` int UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=50;

--
-- AUTO_INCREMENT for table `event_registration`
--
ALTER TABLE `event_registration`
  MODIFY `registration_id` int UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=35;

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
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `notifications`
--
ALTER TABLE `notifications`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=193;

--
-- AUTO_INCREMENT for table `notification_reads`
--
ALTER TABLE `notification_reads`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=58;

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
  MODIFY `user_id` int UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=303;

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
