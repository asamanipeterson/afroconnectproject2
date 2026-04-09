-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Generation Time: Apr 09, 2026 at 03:27 PM
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
-- Database: `afroconnect_dbz`
--

-- --------------------------------------------------------

--
-- Table structure for table `bookmarks`
--

CREATE TABLE `bookmarks` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `post_id` bigint(20) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `bookmarks`
--

INSERT INTO `bookmarks` (`id`, `user_id`, `post_id`, `created_at`, `updated_at`) VALUES
(8, 2, 5, '2025-10-27 13:04:28', '2025-10-27 13:04:28');

-- --------------------------------------------------------

--
-- Table structure for table `cache`
--

CREATE TABLE `cache` (
  `key` varchar(191) NOT NULL,
  `value` mediumtext NOT NULL,
  `expiration` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `cache_locks`
--

CREATE TABLE `cache_locks` (
  `key` varchar(191) NOT NULL,
  `owner` varchar(191) NOT NULL,
  `expiration` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `comments`
--

CREATE TABLE `comments` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `post_id` bigint(20) UNSIGNED NOT NULL,
  `parent_id` bigint(20) UNSIGNED DEFAULT NULL,
  `content` text NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `comments`
--

INSERT INTO `comments` (`id`, `user_id`, `post_id`, `parent_id`, `content`, `created_at`, `updated_at`) VALUES
(3, 4, 5, NULL, 'hello', '2025-10-22 12:06:05', '2025-10-22 12:06:05'),
(4, 2, 5, NULL, 'it\'s working', '2025-10-27 12:59:11', '2025-10-27 12:59:11');

-- --------------------------------------------------------

--
-- Table structure for table `conversations`
--

CREATE TABLE `conversations` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `conversations`
--

INSERT INTO `conversations` (`id`, `created_at`, `updated_at`) VALUES
(1, '2025-10-15 21:52:06', '2025-10-27 13:03:30'),
(2, '2025-10-22 11:53:58', '2025-10-22 12:08:05');

-- --------------------------------------------------------

--
-- Table structure for table `failed_jobs`
--

CREATE TABLE `failed_jobs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `uuid` varchar(191) NOT NULL,
  `connection` text NOT NULL,
  `queue` text NOT NULL,
  `payload` longtext NOT NULL,
  `exception` longtext NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `follows`
--

CREATE TABLE `follows` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `follower_id` bigint(20) UNSIGNED NOT NULL,
  `followed_id` bigint(20) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `follows`
--

INSERT INTO `follows` (`id`, `follower_id`, `followed_id`, `created_at`, `updated_at`) VALUES
(4, 4, 2, '2025-10-22 12:07:21', '2025-10-22 12:07:21'),
(5, 2, 4, '2025-10-22 12:12:57', '2025-10-22 12:12:57');

-- --------------------------------------------------------

--
-- Table structure for table `items`
--

CREATE TABLE `items` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `title` varchar(191) NOT NULL,
  `price` decimal(12,2) NOT NULL,
  `category` varchar(191) NOT NULL,
  `condition` varchar(191) NOT NULL,
  `description` text DEFAULT NULL,
  `location` varchar(191) DEFAULT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `items`
--

INSERT INTO `items` (`id`, `title`, `price`, `category`, `condition`, `description`, `location`, `user_id`, `created_at`, `updated_at`) VALUES
(1, 'Car Lord', 300000.00, 'vehicles', 'new', 'very clean', 'Sydney', 4, '2025-10-22 11:45:54', '2025-10-22 11:45:54');

-- --------------------------------------------------------

--
-- Table structure for table `item_photos`
--

CREATE TABLE `item_photos` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `item_id` bigint(20) UNSIGNED NOT NULL,
  `path` varchar(191) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `item_photos`
--

INSERT INTO `item_photos` (`id`, `item_id`, `path`, `created_at`, `updated_at`) VALUES
(1, 1, 'items/0QZHv067PwZ46De1Uk0NEss8EzbRNBb4cX9HZOPp.png', '2025-10-22 11:45:54', '2025-10-22 11:45:54'),
(2, 1, 'items/Q3Jt7biBggoQ3FRp1wbqNOsAa6VF7LnLsOOjks1U.png', '2025-10-22 11:45:54', '2025-10-22 11:45:54'),
(3, 1, 'items/1QST5xgBBuYuPCWb60IGv7UABzfl8IIczoFPM0XJ.jpg', '2025-10-22 11:45:54', '2025-10-22 11:45:54'),
(4, 1, 'items/Ys11x11sUyhMSOD9J1ARXovB3gbnMG880p80Kjwf.jpg', '2025-10-22 11:45:54', '2025-10-22 11:45:54'),
(5, 1, 'items/Ci9HxvDFr4T82lUuUdkU6chWFwlQhPMnzHlJf8vj.jpg', '2025-10-22 11:45:54', '2025-10-22 11:45:54');

-- --------------------------------------------------------

--
-- Table structure for table `jobs`
--

CREATE TABLE `jobs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `queue` varchar(191) NOT NULL,
  `payload` longtext NOT NULL,
  `attempts` tinyint(3) UNSIGNED NOT NULL,
  `reserved_at` int(10) UNSIGNED DEFAULT NULL,
  `available_at` int(10) UNSIGNED NOT NULL,
  `created_at` int(10) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `job_batches`
--

CREATE TABLE `job_batches` (
  `id` varchar(191) NOT NULL,
  `name` varchar(191) NOT NULL,
  `total_jobs` int(11) NOT NULL,
  `pending_jobs` int(11) NOT NULL,
  `failed_jobs` int(11) NOT NULL,
  `failed_job_ids` longtext NOT NULL,
  `options` mediumtext DEFAULT NULL,
  `cancelled_at` int(11) DEFAULT NULL,
  `created_at` int(11) NOT NULL,
  `finished_at` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `likes`
--

CREATE TABLE `likes` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `post_id` bigint(20) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `likes`
--

INSERT INTO `likes` (`id`, `user_id`, `post_id`, `created_at`, `updated_at`) VALUES
(5, 2, 4, '2025-10-22 10:53:51', '2025-10-22 10:53:51'),
(7, 4, 4, '2025-10-22 11:51:18', '2025-10-22 11:51:18'),
(8, 4, 5, '2025-10-22 12:06:20', '2025-10-22 12:06:20'),
(9, 2, 5, '2025-10-27 12:57:59', '2025-10-27 12:57:59'),
(10, 3, 5, '2025-12-31 18:27:06', '2025-12-31 18:27:06');

-- --------------------------------------------------------

--
-- Table structure for table `live_comments`
--

CREATE TABLE `live_comments` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `stream_id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `content` text NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `messages`
--

CREATE TABLE `messages` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `body` text DEFAULT NULL,
  `audio_path` varchar(191) DEFAULT NULL,
  `image_path` varchar(191) DEFAULT NULL,
  `video_path` varchar(191) DEFAULT NULL,
  `type` enum('text','image','video','audio','shared_post') DEFAULT NULL,
  `conversation_id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `read_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `post_id` bigint(20) UNSIGNED DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `messages`
--

INSERT INTO `messages` (`id`, `body`, `audio_path`, `image_path`, `video_path`, `type`, `conversation_id`, `user_id`, `read_at`, `created_at`, `updated_at`, `post_id`) VALUES
(1, 'hello', NULL, NULL, NULL, 'text', 1, 2, NULL, '2025-10-15 21:52:20', '2025-10-15 21:52:20', NULL),
(2, '😇', NULL, NULL, NULL, 'text', 1, 2, NULL, '2025-10-15 21:53:15', '2025-10-15 21:53:15', NULL),
(4, '', NULL, NULL, NULL, 'shared_post', 1, 2, NULL, '2025-10-22 10:58:34', '2025-10-22 10:58:34', 4),
(5, 'hello', NULL, NULL, NULL, 'text', 2, 4, NULL, '2025-10-22 11:54:05', '2025-10-22 11:54:05', NULL),
(6, 'Audio message', 'audio_messages/3UvFyxC9nr6fvwLRJtOdGtNX9bPhfD3VPMSfqkiv.webm', NULL, NULL, 'audio', 2, 4, NULL, '2025-10-22 11:55:43', '2025-10-22 11:55:43', NULL),
(7, '😗', NULL, NULL, NULL, 'text', 2, 4, NULL, '2025-10-22 11:59:42', '2025-10-22 11:59:42', NULL),
(8, '', NULL, NULL, NULL, 'shared_post', 2, 4, NULL, '2025-10-22 12:08:05', '2025-10-22 12:08:05', 5),
(9, '', NULL, NULL, NULL, 'shared_post', 1, 2, NULL, '2025-10-27 13:03:30', '2025-10-27 13:03:30', 5);

-- --------------------------------------------------------

--
-- Table structure for table `migrations`
--

CREATE TABLE `migrations` (
  `id` int(10) UNSIGNED NOT NULL,
  `migration` varchar(191) NOT NULL,
  `batch` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `migrations`
--

INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES
(1, '0001_01_01_000000_create_users_table', 1),
(2, '0001_01_01_000001_create_cache_table', 1),
(3, '0001_01_01_000002_create_jobs_table', 1),
(4, '2025_07_16_120533_create_posts_table', 1),
(5, '2025_07_16_120604_create_comments_table', 1),
(6, '2025_07_16_120632_create_likes_table', 1),
(7, '2025_07_16_120805_add_profile_fields_to_users_table', 1),
(8, '2025_07_16_120917_create_follows_table', 1),
(9, '2025_07_16_121004_create_notifications_table', 1),
(10, '2025_07_16_121047_create_post_media_table', 1),
(11, '2025_07_18_030217_create_stories_table', 1),
(12, '2025_07_25_151234_create_story_views_table', 1),
(13, '2025_08_13_093554_add_user_status_fields_to_users', 1),
(14, '2025_08_13_093659_create_reports_table', 1),
(15, '2025_08_14_020039_create_post_reports_table', 1),
(16, '2025_08_20_032631_create_conversations_table', 1),
(17, '2025_08_20_032700_create_participants_table', 1),
(18, '2025_08_20_032721_create_messages_table', 1),
(19, '2025_08_26_143256_add_audio_fields_to_messages_table', 1),
(20, '2025_08_28_003531_add_media_columns_to_messages_table', 1),
(21, '2025_09_04_224517_create_one_time_pass_codes_table', 1),
(22, '2025_09_05_002929_add_expires_at_to_one_time_pass_codes', 1),
(23, '2025_09_08_104810_create_items_table', 1),
(24, '2025_09_08_105453_create_item_photos_table', 1),
(25, '2025_09_13_021028_create_shared_posts_table', 1),
(26, '2025_09_17_221232_add_post_id_to_messages_table', 1),
(27, '2025_09_17_225240_add_shared_post_to_messages_type_enum', 1),
(28, '2025_09_25_220450_create_streams_table', 1),
(29, '2025_10_03_132321_create_live_comments_table', 1),
(30, '2025_10_11_232229_create_bookmarks_table', 1),
(31, '2025_10_12_012510_create_post_user_tags_table', 1),
(32, '2025_10_13_072511_add_deleted_at_to_users_table', 1);

-- --------------------------------------------------------

--
-- Table structure for table `notifications`
--

CREATE TABLE `notifications` (
  `id` char(36) NOT NULL,
  `type` varchar(191) NOT NULL,
  `notifiable_type` varchar(191) NOT NULL,
  `notifiable_id` bigint(20) UNSIGNED NOT NULL,
  `data` text NOT NULL,
  `read_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `notifications`
--

INSERT INTO `notifications` (`id`, `type`, `notifiable_type`, `notifiable_id`, `data`, `read_at`, `created_at`, `updated_at`) VALUES
('00e0d749-a71d-4096-ab62-0916f6ebcb47', 'App\\Notifications\\PostLikedNotification', 'App\\Models\\User', 4, '{\"type\":\"like\",\"message\":\"lorraine liked your post.\",\"post_id\":5}', NULL, '2025-10-27 12:57:59', '2025-10-27 12:57:59'),
('18002cf6-3584-4686-a8dd-c6b9287c2aa3', 'App\\Notifications\\FollowNotification', 'App\\Models\\User', 1, '{\"message\":\"lorraine has followed you\",\"follower_id\":2,\"show_follow_back_button\":false}', NULL, '2025-11-03 08:58:34', '2025-11-03 08:58:34'),
('259c49dd-f545-4e05-b7b4-0e37a5b5c262', 'App\\Notifications\\FollowNotification', 'App\\Models\\User', 2, '{\"message\":\"preshacks followed you\",\"follower_id\":4,\"show_follow_back_button\":true}', NULL, '2025-10-22 12:07:09', '2025-10-22 12:07:09'),
('40e8efdf-6545-4e35-bb66-4bbc7b1305f8', 'App\\Notifications\\FollowNotification', 'App\\Models\\User', 2, '{\"message\":\"preshacks followed you\",\"follower_id\":4,\"show_follow_back_button\":true}', NULL, '2025-10-22 12:07:21', '2025-10-22 12:07:21'),
('48a46e92-c8cb-4d6d-9172-b954815b5eff', 'App\\Notifications\\FollowNotification', 'App\\Models\\User', 4, '{\"message\":\"lorraine has followed you\",\"follower_id\":2,\"show_follow_back_button\":false}', NULL, '2025-10-22 12:12:57', '2025-10-22 12:12:57'),
('516ea61a-a4a3-4a8b-9e19-9673ed086f88', 'App\\Notifications\\FollowNotification', 'App\\Models\\User', 1, '{\"message\":\"lorraine followed you\",\"follower_id\":2,\"show_follow_back_button\":true}', '2025-10-15 21:54:57', '2025-10-15 21:50:48', '2025-10-15 21:54:57'),
('6b3cc910-9b7a-4752-8df5-8354ce509d3e', 'App\\Notifications\\PostLikedNotification', 'App\\Models\\User', 1, '{\"type\":\"like\",\"message\":\"lorraine liked your post.\",\"post_id\":2}', '2025-10-15 21:54:59', '2025-10-15 21:51:14', '2025-10-15 21:54:59'),
('8964bb4f-68a1-4bc1-85f7-aafd64711ebf', 'App\\Notifications\\PostLikedNotification', 'App\\Models\\User', 2, '{\"type\":\"like\",\"message\":\"preshacks liked your post.\",\"post_id\":4}', NULL, '2025-10-22 11:51:18', '2025-10-22 11:51:18'),
('8b7f8416-9f2c-42b4-a011-3ab30e9dd1fa', 'App\\Notifications\\PostLikedNotification', 'App\\Models\\User', 1, '{\"type\":\"like\",\"message\":\"young liked your post.\",\"post_id\":2}', NULL, '2025-12-31 18:27:39', '2025-12-31 18:27:39'),
('c6dc37e6-97d0-4797-9e2a-1da18f9bbc75', 'App\\Notifications\\PostLikedNotification', 'App\\Models\\User', 4, '{\"type\":\"like\",\"message\":\"young liked your post.\",\"post_id\":5}', NULL, '2025-12-31 18:27:06', '2025-12-31 18:27:06'),
('cc0ce2b5-4c85-47fd-9446-a253acc40a67', 'App\\Notifications\\FollowNotification', 'App\\Models\\User', 2, '{\"message\":\"certified has followed you\",\"follower_id\":1,\"show_follow_back_button\":false}', '2025-10-16 11:03:43', '2025-10-15 21:54:49', '2025-10-16 11:03:43'),
('cdcc28eb-e8b3-47ea-9809-0ae5a9606e9c', 'App\\Notifications\\PostLikedNotification', 'App\\Models\\User', 2, '{\"type\":\"like\",\"message\":\"certified liked your post.\",\"post_id\":4}', '2025-10-18 01:11:15', '2025-10-16 11:14:41', '2025-10-18 01:11:15'),
('f0ed8f1f-3dfc-412b-91a8-f1a8bd04acc0', 'App\\Notifications\\PostLikedNotification', 'App\\Models\\User', 1, '{\"type\":\"like\",\"message\":\"preshacks liked your post.\",\"post_id\":2}', NULL, '2025-10-22 11:51:07', '2025-10-22 11:51:07');

-- --------------------------------------------------------

--
-- Table structure for table `one_time_pass_codes`
--

CREATE TABLE `one_time_pass_codes` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `code` varchar(191) NOT NULL,
  `expires_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `participants`
--

CREATE TABLE `participants` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `conversation_id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `joined_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `participants`
--

INSERT INTO `participants` (`id`, `conversation_id`, `user_id`, `joined_at`, `created_at`, `updated_at`) VALUES
(1, 1, 2, NULL, NULL, NULL),
(3, 2, 4, NULL, NULL, NULL),
(4, 2, 2, NULL, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `password_reset_tokens`
--

CREATE TABLE `password_reset_tokens` (
  `email` varchar(191) NOT NULL,
  `token` varchar(191) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `posts`
--

CREATE TABLE `posts` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `caption` text DEFAULT NULL,
  `post_group_id` varchar(191) DEFAULT NULL,
  `reports_count` int(10) UNSIGNED NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `posts`
--

INSERT INTO `posts` (`id`, `user_id`, `caption`, `post_group_id`, `reports_count`, `created_at`, `updated_at`) VALUES
(4, 2, 'visit Ghana', 'cf7931ff-15bc-4343-90a8-53cb58594874', 0, '2025-10-16 11:01:57', '2025-10-16 11:01:57'),
(5, 4, 'post with Preshacks', '286d2ca3-017f-45ed-8236-bc164a447503', 1, '2025-10-22 12:05:08', '2025-10-27 15:27:04');

-- --------------------------------------------------------

--
-- Table structure for table `post_media`
--

CREATE TABLE `post_media` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `post_id` bigint(20) UNSIGNED NOT NULL,
  `file_path` varchar(191) DEFAULT NULL,
  `file_type` varchar(191) DEFAULT NULL,
  `mime_type` varchar(191) DEFAULT NULL,
  `text_content` text DEFAULT NULL,
  `sound_path` varchar(191) DEFAULT NULL,
  `order` int(11) NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `post_media`
--

INSERT INTO `post_media` (`id`, `post_id`, `file_path`, `file_type`, `mime_type`, `text_content`, `sound_path`, `order`, `created_at`, `updated_at`) VALUES
(43, 4, 'posts/media/EI527RbB7hhkQSj5zS2LsmnQhGMn2PLOhKEji5NB.jpg', 'image', 'image/jpeg', NULL, NULL, 0, '2025-10-16 11:01:57', '2025-10-16 11:01:57'),
(44, 4, 'posts/media/gQAG4T732tn0Aq7uQqhLeMAbbvcGJpFoRsoKLYZq.jpg', 'image', 'image/jpeg', NULL, NULL, 1, '2025-10-16 11:01:57', '2025-10-16 11:01:57'),
(45, 4, 'posts/media/BhEBpdxQbj4RoJX4ya3gyTrnKokQVQSpH9AEjWH9.jpg', 'image', 'image/jpeg', NULL, NULL, 2, '2025-10-16 11:01:57', '2025-10-16 11:01:57'),
(46, 4, 'posts/media/LGdPHWccuvGq2VimWXcQHktaU4AivYpsK5g6TMlm.jpg', 'image', 'image/jpeg', NULL, NULL, 3, '2025-10-16 11:01:57', '2025-10-16 11:01:57'),
(47, 4, 'posts/media/Sq4L4Gd9K8Hp0EjXWpEpc6mlUz8tvtT6DKtwlhbO.jpg', 'image', 'image/jpeg', NULL, NULL, 4, '2025-10-16 11:01:58', '2025-10-16 11:01:58'),
(48, 4, 'posts/media/waOloSxI3Al6o93pxEtHmAe2THSl321LadDeMAdn.jpg', 'image', 'image/jpeg', NULL, NULL, 5, '2025-10-16 11:01:58', '2025-10-16 11:01:58'),
(49, 4, 'posts/media/0JndZKlX0QAtppVf6E3KRxCvMKRbZltomUIA7iFh.jpg', 'image', 'image/jpeg', NULL, NULL, 6, '2025-10-16 11:01:58', '2025-10-16 11:01:58'),
(50, 4, 'posts/media/cs0IUorDAy4fnRJIDvjAzk7vuoRqYAGaC4nRpEcU.jpg', 'image', 'image/jpeg', NULL, NULL, 7, '2025-10-16 11:01:58', '2025-10-16 11:01:58'),
(51, 4, 'posts/media/73VR3Mt20xIuDnnimbqeGqQm0C9OJOyPhFKT5Y5B.jpg', 'image', 'image/jpeg', NULL, NULL, 8, '2025-10-16 11:01:58', '2025-10-16 11:01:58'),
(52, 4, 'posts/media/twOKIbISIb2mtAYpx4mk011Q35QAXhjbMSQk7NYX.jpg', 'image', 'image/jpeg', NULL, NULL, 9, '2025-10-16 11:01:58', '2025-10-16 11:01:58'),
(53, 4, 'posts/media/DF00BtQYtYhqN635qzrzu2FeWRT77JXlLuILSpWq.jpg', 'image', 'image/jpeg', NULL, NULL, 10, '2025-10-16 11:01:58', '2025-10-16 11:01:58'),
(54, 4, 'posts/media/fhFFlcFkPuDs71EObVpDpYvEYHFJKiEVcNiaVxUL.jpg', 'image', 'image/jpeg', NULL, NULL, 11, '2025-10-16 11:01:58', '2025-10-16 11:01:58'),
(55, 4, 'posts/media/sfoilArTsbFPKpNYkaxSUyXXI4oVFA0n5X4TNvO5.jpg', 'image', 'image/jpeg', NULL, NULL, 12, '2025-10-16 11:01:58', '2025-10-16 11:01:58'),
(56, 4, 'posts/media/w1qvFlotlcvJjehyHLEVRyaN8DQDNG8DcFTaK8Aq.mp4', 'video', 'video/mp4', NULL, NULL, 13, '2025-10-16 11:01:58', '2025-10-16 11:01:58'),
(57, 4, 'posts/media/b08WlinHmp8pedGDfD0KpTkoIbNDFJvTjAA1ACOt.mp4', 'video', 'video/mp4', NULL, NULL, 14, '2025-10-16 11:01:58', '2025-10-16 11:01:58'),
(58, 4, 'posts/media/hdmGPyfI6jzMN0VV0PbP9FOPlQAFHvqgaEDnRuEm.jpg', 'image', 'image/jpeg', NULL, NULL, 15, '2025-10-16 11:01:58', '2025-10-16 11:01:58'),
(59, 4, NULL, 'text', NULL, 'visit Ghana', NULL, 16, '2025-10-16 11:01:58', '2025-10-16 11:01:58'),
(60, 5, 'posts/media/tifg477yQSF8EtmoRN6UDsEfKja46yT0BKFUGOJx.jpg', 'image', 'image/jpeg', NULL, NULL, 0, '2025-10-22 12:05:08', '2025-10-22 12:05:08'),
(61, 5, 'posts/media/OIZFgb7xXJDH2qTRggIp4ur82DGx6yink9bEGcsV.jpg', 'image', 'image/jpeg', NULL, NULL, 1, '2025-10-22 12:05:08', '2025-10-22 12:05:08'),
(62, 5, 'posts/media/70nSk0HQHzy92WiMYIlBcQfRx9FrJ4JONb7PfK44.jpg', 'image', 'image/jpeg', NULL, NULL, 2, '2025-10-22 12:05:08', '2025-10-22 12:05:08'),
(63, 5, 'posts/media/WSC0ADivkk6CkznKJC9CgOsAsW12aKdaOwty69Ft.jpg', 'image', 'image/jpeg', NULL, NULL, 3, '2025-10-22 12:05:08', '2025-10-22 12:05:08'),
(64, 5, 'posts/media/Zta41bPYniw3gK2zpwn0duNQDvuBkVkhrIRuVlOa.jpg', 'image', 'image/jpeg', NULL, NULL, 4, '2025-10-22 12:05:08', '2025-10-22 12:05:08'),
(65, 5, 'posts/media/rxwNFSUtASRKQ1K0iHJn6w0LKY3PMGpgywu70PtX.jpg', 'image', 'image/jpeg', NULL, NULL, 5, '2025-10-22 12:05:09', '2025-10-22 12:05:09'),
(66, 5, 'posts/media/cot5YCGf3Rvz7rxx3EE3smi7rV8m9HhAFsTN92pE.mp4', 'video', 'video/mp4', NULL, NULL, 6, '2025-10-22 12:05:09', '2025-10-22 12:05:09'),
(67, 5, NULL, 'text', NULL, 'preshacks media', NULL, 7, '2025-10-22 12:05:09', '2025-10-22 12:05:09');

-- --------------------------------------------------------

--
-- Table structure for table `post_reports`
--

CREATE TABLE `post_reports` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `post_id` bigint(20) UNSIGNED NOT NULL,
  `reporter_id` bigint(20) UNSIGNED NOT NULL,
  `reason` varchar(191) NOT NULL,
  `details` text DEFAULT NULL,
  `status` varchar(191) NOT NULL DEFAULT 'pending',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `post_user_tags`
--

CREATE TABLE `post_user_tags` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `post_id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `reports`
--

CREATE TABLE `reports` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `reported_user_id` bigint(20) UNSIGNED NOT NULL,
  `reporter_id` bigint(20) UNSIGNED NOT NULL,
  `reason` varchar(191) NOT NULL,
  `details` text DEFAULT NULL,
  `status` varchar(191) NOT NULL DEFAULT 'pending',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `sessions`
--

CREATE TABLE `sessions` (
  `id` varchar(191) NOT NULL,
  `user_id` bigint(20) UNSIGNED DEFAULT NULL,
  `ip_address` varchar(45) DEFAULT NULL,
  `user_agent` text DEFAULT NULL,
  `payload` longtext NOT NULL,
  `last_activity` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `sessions`
--

INSERT INTO `sessions` (`id`, `user_id`, `ip_address`, `user_agent`, `payload`, `last_activity`) VALUES
('lwp8FyUu0GKu0nlZUsMwZ4mgruEsTf9rwc1sRWJV', 6, '127.0.0.1', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/26.2 Safari/605.1.15', 'YTo1OntzOjY6Il90b2tlbiI7czo0MDoiOW1CRTNTYmFTWlVqU25kQnR1Tk5QWWpadjJFOU90eElHaUEyc1d1TSI7czozOiJ1cmwiO2E6MTp7czo4OiJpbnRlbmRlZCI7czoyMToiaHR0cDovLzEyNy4wLjAuMTo4MDAxIjt9czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6MjE6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMSI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fXM6NTA6ImxvZ2luX3dlYl81OWJhMzZhZGRjMmIyZjk0MDE1ODBmMDE0YzdmNThlYTRlMzA5ODlkIjtpOjY7fQ==', 1768135832),
('WnhrUFbuLrE3rfiz3LnkNIVZZP0TSRmb2uTGZJyp', NULL, '127.0.0.1', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/26.2 Safari/605.1.15', 'YTo1OntzOjY6Il90b2tlbiI7czo0MDoiSUlKSHpYUmtOd0FxbHdCR05ES3V4T1A3NmpoblpsT0RISEQyMzlmRCI7czozOiJ1cmwiO2E6MTp7czo4OiJpbnRlbmRlZCI7czoyMToiaHR0cDovLzEyNy4wLjAuMTo4MDAxIjt9czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6Mjc6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMS9sb2dpbiI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fXM6MjI6IlBIUERFQlVHQkFSX1NUQUNLX0RBVEEiO2E6MDp7fX0=', 1769620613);

-- --------------------------------------------------------

--
-- Table structure for table `shared_posts`
--

CREATE TABLE `shared_posts` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `sharer_id` bigint(20) UNSIGNED NOT NULL,
  `recipient_id` bigint(20) UNSIGNED NOT NULL,
  `post_id` bigint(20) UNSIGNED NOT NULL,
  `message` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `stories`
--

CREATE TABLE `stories` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `story_type` enum('image','video','text') NOT NULL,
  `media_path` varchar(191) DEFAULT NULL,
  `text_content` text DEFAULT NULL,
  `background` varchar(191) DEFAULT NULL,
  `caption` varchar(191) DEFAULT NULL,
  `expires_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `stories`
--

INSERT INTO `stories` (`id`, `user_id`, `story_type`, `media_path`, `text_content`, `background`, `caption`, `expires_at`, `created_at`, `updated_at`) VALUES
(10, 4, 'image', 'stories/g1jbCoaurMdGz7BBFktv80aNrsja4F0vcQW0oByQ.png', NULL, NULL, NULL, '2025-10-23 12:01:56', '2025-10-22 12:01:56', '2025-10-22 12:01:56'),
(11, 4, 'image', 'stories/dIYoGiqCWJ42LvG4OxssYNf3GypSXpS8ZG9ksyYv.png', NULL, NULL, NULL, '2025-10-23 12:01:56', '2025-10-22 12:01:56', '2025-10-22 12:01:56'),
(12, 4, 'image', 'stories/iDEcBr6peLN8VtnSNtUcSfrnQcsON5p2juyFA0Tr.png', NULL, NULL, NULL, '2025-10-23 12:01:56', '2025-10-22 12:01:56', '2025-10-22 12:01:56'),
(13, 4, 'image', 'stories/rWg5JY90b0nchPIzK0hdKb5sY88gKH4qkPMM8d3g.jpg', NULL, NULL, NULL, '2025-10-23 12:01:56', '2025-10-22 12:01:56', '2025-10-22 12:01:56'),
(14, 4, 'video', 'stories/Ahrne08VLQy52LxvhfWiBMTkKbysKncm4jFq6aY0.mp4', NULL, NULL, NULL, '2025-10-23 12:01:56', '2025-10-22 12:01:56', '2025-10-22 12:01:56'),
(15, 2, 'image', 'stories/aLzwT3pIUrc2kfxV3ucA0tk7RNjmNMQMZuiebLCf.jpg', NULL, NULL, NULL, '2025-10-28 13:02:03', '2025-10-27 13:02:03', '2025-10-27 13:02:03'),
(16, 2, 'image', 'stories/dobAXKDfSUjjuvWZqNeFKT12rz6dOuqLY1YN0VRR.jpg', NULL, NULL, NULL, '2025-10-28 13:02:03', '2025-10-27 13:02:03', '2025-10-27 13:02:03'),
(17, 2, 'image', 'stories/ZwrAhfJNxBYH9ooV1Ezk4j3Sqyx6i4yUkwNIYwvm.jpg', NULL, NULL, NULL, '2025-10-28 13:02:03', '2025-10-27 13:02:03', '2025-10-27 13:02:03'),
(18, 2, 'image', 'stories/vuB6iH7rJe0mtJ7JrQ7Wsq7qhuDlKj67uqcwi0XO.jpg', NULL, NULL, NULL, '2025-10-28 13:02:03', '2025-10-27 13:02:03', '2025-10-27 13:02:03'),
(19, 2, 'video', 'stories/utiU24QE27Brk23om2OxRMh3Kp5p0GgY5pgWpTNh.mp4', NULL, NULL, NULL, '2025-10-28 13:02:03', '2025-10-27 13:02:03', '2025-10-27 13:02:03'),
(20, 2, 'text', NULL, 'hello', '#3B82F6', NULL, '2025-10-28 13:02:03', '2025-10-27 13:02:03', '2025-10-27 13:02:03');

-- --------------------------------------------------------

--
-- Table structure for table `story_views`
--

CREATE TABLE `story_views` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `story_id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `viewed_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `streams`
--

CREATE TABLE `streams` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `channel_name` varchar(191) NOT NULL,
  `title` varchar(191) NOT NULL,
  `description` text DEFAULT NULL,
  `status` varchar(191) NOT NULL DEFAULT 'offline',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `streams`
--

INSERT INTO `streams` (`id`, `user_id`, `channel_name`, `title`, `description`, `status`, `created_at`, `updated_at`) VALUES
(5, 2, 'Ga42rB7qi1', 'hallelujah', NULL, 'offline', '2025-10-18 01:11:38', '2025-10-18 01:12:46'),
(7, 2, 'BkEDVoCEpu', 'hallelujah challenge', NULL, 'offline', '2025-10-20 23:52:48', '2025-10-20 23:53:51'),
(8, 4, 'xollQCLFuo', 'halleluyau', NULL, 'offline', '2025-10-22 11:48:58', '2025-10-22 11:49:38'),
(9, 2, 'Oo1ViQJQCl', 'halleluyah', NULL, 'offline', '2025-10-27 12:55:02', '2025-10-27 12:57:13');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `username` varchar(191) NOT NULL,
  `email` varchar(191) NOT NULL,
  `profile_picture` varchar(191) DEFAULT NULL,
  `bio` text DEFAULT NULL,
  `reports_count` int(10) UNSIGNED NOT NULL DEFAULT 0,
  `cover_picture` varchar(191) DEFAULT NULL,
  `website` varchar(191) DEFAULT NULL,
  `password` varchar(191) NOT NULL,
  `gender` enum('male','female','other','prefer_not_to_say') DEFAULT NULL,
  `language` varchar(191) NOT NULL DEFAULT 'en',
  `location` varchar(191) DEFAULT NULL,
  `phone` varchar(191) DEFAULT NULL,
  `dob` date DEFAULT NULL,
  `user_type` enum('user','admin') NOT NULL DEFAULT 'user',
  `remember_token` varchar(100) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `is_blocked` tinyint(1) NOT NULL DEFAULT 0,
  `is_verified` tinyint(1) NOT NULL DEFAULT 0,
  `is_suspended` tinyint(1) NOT NULL DEFAULT 0,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `username`, `email`, `profile_picture`, `bio`, `reports_count`, `cover_picture`, `website`, `password`, `gender`, `language`, `location`, `phone`, `dob`, `user_type`, `remember_token`, `created_at`, `updated_at`, `is_blocked`, `is_verified`, `is_suspended`, `deleted_at`) VALUES
(2, 'lorraine', 'lorrainegbagbo21@gmail.com', 'profile_pictures/PPDdrHfqWQojz0cuzRUBIzJkbPWvqkiS42yOKzBm.jpg', NULL, 0, NULL, NULL, '$2y$12$wfEyY0iF4CUbT/RNN2MXy.4Bciv6bEvNBjNHVROX9eY8gLwVNRr/a', 'male', 'zh', 'United Arab Emirates', '0550491044', '2004-12-15', 'user', 'FkTpvMDVIRIRFzrru4aOqQgeRlnEWjwHoXkgo641D2xPiwFXqF3ce9e75qnm', '2025-10-15 21:49:21', '2025-10-22 11:21:33', 0, 0, 0, NULL),
(3, 'young', 'greatyoungboy123@gmail.com', NULL, NULL, 0, NULL, NULL, '$2y$12$uBP4861tHNrF66L3FqDFpu6gs46lwgQz/oRoKrjXxb.LgbdsCIZrq', 'male', 'fr', 'Bahamas', '0550491044', '2004-12-15', 'admin', '7Mzu6UO9FcFO7SVLANKWI39vnubLODNwhyCH1cs80Y2mqtoAHvKwQ7XkeWRw', '2025-10-22 10:56:23', '2025-10-22 10:56:23', 0, 0, 0, NULL),
(4, 'preshacks', 'preshacks@gmail.com', 'profile_pictures/YE1GJiCZ2zKUdIcmFBMzbgOt7GL4mBlX4TtUrtua.jpg', NULL, 1, 'cover_pictures/78knCWQNl9kwQsIuKIvluErM4X02pH9ZoVdem3v2.jpg', NULL, '$2y$12$4aLM/KGGl8VNfC5fPuZmJuhc.WFsgDS5xTWwfPD8bcO//syqJDdEy', 'male', 'es', 'United Arab Emirates', '0555544994', '2004-12-15', 'user', NULL, '2025-10-22 11:39:50', '2025-10-27 15:31:19', 0, 0, 0, NULL),
(6, 'greatness', 'certifiedgreatness21@gmail.com', NULL, NULL, 0, NULL, NULL, '$2y$12$tpmCv1Sxiyd3NsA5KH0/ned4QnQ/gfDrgWagzt6QLh1.PWzEDSlTO', 'male', 'es', 'Andorra', '0550491044', '2004-12-15', 'user', 'zj1U2jFdVsHuHNrFGF8Fl4tX3V6Cv63lC70wsd5T4Mp5Bgiux8hbOWl8pu1S', '2026-01-04 14:59:37', '2026-01-04 14:59:37', 0, 0, 0, NULL);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `bookmarks`
--
ALTER TABLE `bookmarks`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `bookmarks_user_id_post_id_unique` (`user_id`,`post_id`),
  ADD KEY `bookmarks_post_id_foreign` (`post_id`);

--
-- Indexes for table `cache`
--
ALTER TABLE `cache`
  ADD PRIMARY KEY (`key`);

--
-- Indexes for table `cache_locks`
--
ALTER TABLE `cache_locks`
  ADD PRIMARY KEY (`key`);

--
-- Indexes for table `comments`
--
ALTER TABLE `comments`
  ADD PRIMARY KEY (`id`),
  ADD KEY `comments_user_id_foreign` (`user_id`),
  ADD KEY `comments_post_id_foreign` (`post_id`),
  ADD KEY `comments_parent_id_foreign` (`parent_id`);

--
-- Indexes for table `conversations`
--
ALTER TABLE `conversations`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`);

--
-- Indexes for table `follows`
--
ALTER TABLE `follows`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `follows_follower_id_followed_id_unique` (`follower_id`,`followed_id`),
  ADD KEY `follows_followed_id_foreign` (`followed_id`);

--
-- Indexes for table `items`
--
ALTER TABLE `items`
  ADD PRIMARY KEY (`id`),
  ADD KEY `items_user_id_foreign` (`user_id`);

--
-- Indexes for table `item_photos`
--
ALTER TABLE `item_photos`
  ADD PRIMARY KEY (`id`),
  ADD KEY `item_photos_item_id_foreign` (`item_id`);

--
-- Indexes for table `jobs`
--
ALTER TABLE `jobs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `jobs_queue_index` (`queue`);

--
-- Indexes for table `job_batches`
--
ALTER TABLE `job_batches`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `likes`
--
ALTER TABLE `likes`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `likes_user_id_post_id_unique` (`user_id`,`post_id`),
  ADD KEY `likes_post_id_foreign` (`post_id`);

--
-- Indexes for table `live_comments`
--
ALTER TABLE `live_comments`
  ADD PRIMARY KEY (`id`),
  ADD KEY `live_comments_stream_id_foreign` (`stream_id`),
  ADD KEY `live_comments_user_id_foreign` (`user_id`);

--
-- Indexes for table `messages`
--
ALTER TABLE `messages`
  ADD PRIMARY KEY (`id`),
  ADD KEY `messages_conversation_id_foreign` (`conversation_id`),
  ADD KEY `messages_user_id_foreign` (`user_id`),
  ADD KEY `messages_post_id_foreign` (`post_id`);

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
  ADD KEY `notifications_notifiable_type_notifiable_id_index` (`notifiable_type`,`notifiable_id`);

--
-- Indexes for table `one_time_pass_codes`
--
ALTER TABLE `one_time_pass_codes`
  ADD PRIMARY KEY (`id`),
  ADD KEY `one_time_pass_codes_user_id_foreign` (`user_id`);

--
-- Indexes for table `participants`
--
ALTER TABLE `participants`
  ADD PRIMARY KEY (`id`),
  ADD KEY `participants_conversation_id_foreign` (`conversation_id`),
  ADD KEY `participants_user_id_foreign` (`user_id`);

--
-- Indexes for table `password_reset_tokens`
--
ALTER TABLE `password_reset_tokens`
  ADD PRIMARY KEY (`email`);

--
-- Indexes for table `posts`
--
ALTER TABLE `posts`
  ADD PRIMARY KEY (`id`),
  ADD KEY `posts_user_id_foreign` (`user_id`),
  ADD KEY `posts_post_group_id_index` (`post_group_id`);

--
-- Indexes for table `post_media`
--
ALTER TABLE `post_media`
  ADD PRIMARY KEY (`id`),
  ADD KEY `post_media_post_id_foreign` (`post_id`);

--
-- Indexes for table `post_reports`
--
ALTER TABLE `post_reports`
  ADD PRIMARY KEY (`id`),
  ADD KEY `post_reports_post_id_foreign` (`post_id`),
  ADD KEY `post_reports_reporter_id_foreign` (`reporter_id`);

--
-- Indexes for table `post_user_tags`
--
ALTER TABLE `post_user_tags`
  ADD PRIMARY KEY (`id`),
  ADD KEY `post_user_tags_post_id_foreign` (`post_id`),
  ADD KEY `post_user_tags_user_id_foreign` (`user_id`);

--
-- Indexes for table `reports`
--
ALTER TABLE `reports`
  ADD PRIMARY KEY (`id`),
  ADD KEY `reports_reported_user_id_foreign` (`reported_user_id`),
  ADD KEY `reports_reporter_id_foreign` (`reporter_id`);

--
-- Indexes for table `sessions`
--
ALTER TABLE `sessions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `sessions_user_id_index` (`user_id`),
  ADD KEY `sessions_last_activity_index` (`last_activity`);

--
-- Indexes for table `shared_posts`
--
ALTER TABLE `shared_posts`
  ADD PRIMARY KEY (`id`),
  ADD KEY `shared_posts_sharer_id_foreign` (`sharer_id`),
  ADD KEY `shared_posts_recipient_id_foreign` (`recipient_id`),
  ADD KEY `shared_posts_post_id_foreign` (`post_id`);

--
-- Indexes for table `stories`
--
ALTER TABLE `stories`
  ADD PRIMARY KEY (`id`),
  ADD KEY `stories_user_id_foreign` (`user_id`),
  ADD KEY `stories_expires_at_index` (`expires_at`);

--
-- Indexes for table `story_views`
--
ALTER TABLE `story_views`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `story_views_story_id_user_id_unique` (`story_id`,`user_id`),
  ADD KEY `story_views_user_id_foreign` (`user_id`);

--
-- Indexes for table `streams`
--
ALTER TABLE `streams`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `streams_channel_name_unique` (`channel_name`),
  ADD KEY `streams_user_id_foreign` (`user_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `users_username_unique` (`username`),
  ADD UNIQUE KEY `users_email_unique` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `bookmarks`
--
ALTER TABLE `bookmarks`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `comments`
--
ALTER TABLE `comments`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `conversations`
--
ALTER TABLE `conversations`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `follows`
--
ALTER TABLE `follows`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `items`
--
ALTER TABLE `items`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `item_photos`
--
ALTER TABLE `item_photos`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `jobs`
--
ALTER TABLE `jobs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `likes`
--
ALTER TABLE `likes`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `live_comments`
--
ALTER TABLE `live_comments`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `messages`
--
ALTER TABLE `messages`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=33;

--
-- AUTO_INCREMENT for table `one_time_pass_codes`
--
ALTER TABLE `one_time_pass_codes`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=37;

--
-- AUTO_INCREMENT for table `participants`
--
ALTER TABLE `participants`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `posts`
--
ALTER TABLE `posts`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `post_media`
--
ALTER TABLE `post_media`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=68;

--
-- AUTO_INCREMENT for table `post_reports`
--
ALTER TABLE `post_reports`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `post_user_tags`
--
ALTER TABLE `post_user_tags`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `reports`
--
ALTER TABLE `reports`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `shared_posts`
--
ALTER TABLE `shared_posts`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `stories`
--
ALTER TABLE `stories`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- AUTO_INCREMENT for table `story_views`
--
ALTER TABLE `story_views`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `streams`
--
ALTER TABLE `streams`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `bookmarks`
--
ALTER TABLE `bookmarks`
  ADD CONSTRAINT `bookmarks_post_id_foreign` FOREIGN KEY (`post_id`) REFERENCES `posts` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `bookmarks_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `comments`
--
ALTER TABLE `comments`
  ADD CONSTRAINT `comments_parent_id_foreign` FOREIGN KEY (`parent_id`) REFERENCES `comments` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `comments_post_id_foreign` FOREIGN KEY (`post_id`) REFERENCES `posts` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `comments_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `follows`
--
ALTER TABLE `follows`
  ADD CONSTRAINT `follows_followed_id_foreign` FOREIGN KEY (`followed_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `follows_follower_id_foreign` FOREIGN KEY (`follower_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `items`
--
ALTER TABLE `items`
  ADD CONSTRAINT `items_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `item_photos`
--
ALTER TABLE `item_photos`
  ADD CONSTRAINT `item_photos_item_id_foreign` FOREIGN KEY (`item_id`) REFERENCES `items` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `likes`
--
ALTER TABLE `likes`
  ADD CONSTRAINT `likes_post_id_foreign` FOREIGN KEY (`post_id`) REFERENCES `posts` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `likes_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `live_comments`
--
ALTER TABLE `live_comments`
  ADD CONSTRAINT `live_comments_stream_id_foreign` FOREIGN KEY (`stream_id`) REFERENCES `streams` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `live_comments_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `messages`
--
ALTER TABLE `messages`
  ADD CONSTRAINT `messages_conversation_id_foreign` FOREIGN KEY (`conversation_id`) REFERENCES `conversations` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `messages_post_id_foreign` FOREIGN KEY (`post_id`) REFERENCES `posts` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `messages_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `one_time_pass_codes`
--
ALTER TABLE `one_time_pass_codes`
  ADD CONSTRAINT `one_time_pass_codes_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `participants`
--
ALTER TABLE `participants`
  ADD CONSTRAINT `participants_conversation_id_foreign` FOREIGN KEY (`conversation_id`) REFERENCES `conversations` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `participants_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `posts`
--
ALTER TABLE `posts`
  ADD CONSTRAINT `posts_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `post_media`
--
ALTER TABLE `post_media`
  ADD CONSTRAINT `post_media_post_id_foreign` FOREIGN KEY (`post_id`) REFERENCES `posts` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `post_reports`
--
ALTER TABLE `post_reports`
  ADD CONSTRAINT `post_reports_post_id_foreign` FOREIGN KEY (`post_id`) REFERENCES `posts` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `post_reports_reporter_id_foreign` FOREIGN KEY (`reporter_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `post_user_tags`
--
ALTER TABLE `post_user_tags`
  ADD CONSTRAINT `post_user_tags_post_id_foreign` FOREIGN KEY (`post_id`) REFERENCES `posts` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `post_user_tags_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `reports`
--
ALTER TABLE `reports`
  ADD CONSTRAINT `reports_reported_user_id_foreign` FOREIGN KEY (`reported_user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `reports_reporter_id_foreign` FOREIGN KEY (`reporter_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `shared_posts`
--
ALTER TABLE `shared_posts`
  ADD CONSTRAINT `shared_posts_post_id_foreign` FOREIGN KEY (`post_id`) REFERENCES `posts` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `shared_posts_recipient_id_foreign` FOREIGN KEY (`recipient_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `shared_posts_sharer_id_foreign` FOREIGN KEY (`sharer_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `stories`
--
ALTER TABLE `stories`
  ADD CONSTRAINT `stories_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `story_views`
--
ALTER TABLE `story_views`
  ADD CONSTRAINT `story_views_story_id_foreign` FOREIGN KEY (`story_id`) REFERENCES `stories` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `story_views_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `streams`
--
ALTER TABLE `streams`
  ADD CONSTRAINT `streams_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
