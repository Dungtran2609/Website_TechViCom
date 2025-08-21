-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Aug 20, 2025 at 12:36 AM
-- Server version: 8.0.30
-- PHP Version: 8.3.6

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `techvicom_website`
--

-- --------------------------------------------------------

--
-- Table structure for table `attributes`
--

CREATE TABLE `attributes` (
  `id` bigint UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `slug` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `type` enum('text','color','number') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'text',
  `description` text COLLATE utf8mb4_unicode_ci,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `attributes`
--

INSERT INTO `attributes` (`id`, `name`, `slug`, `type`, `description`, `deleted_at`, `created_at`, `updated_at`) VALUES
(1, 'Màu sắc', 'mau-sac', 'color', NULL, NULL, '2025-08-20 00:32:37', '2025-08-20 00:32:37'),
(2, 'RAM', 'ram', 'text', NULL, NULL, '2025-08-20 00:32:37', '2025-08-20 00:32:37'),
(3, 'Bộ nhớ trong', 'bo-nho-trong', 'text', NULL, NULL, '2025-08-20 00:32:37', '2025-08-20 00:32:37');

-- --------------------------------------------------------

--
-- Table structure for table `attribute_values`
--

CREATE TABLE `attribute_values` (
  `id` bigint UNSIGNED NOT NULL,
  `attribute_id` bigint UNSIGNED NOT NULL,
  `value` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `color_code` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `attribute_values`
--

INSERT INTO `attribute_values` (`id`, `attribute_id`, `value`, `color_code`, `deleted_at`, `created_at`, `updated_at`) VALUES
(1, 1, 'Đỏ', '#FF0000', NULL, NULL, NULL),
(2, 1, 'Xanh dương', '#0000FF', NULL, NULL, NULL),
(3, 1, 'Đen', '#000000', NULL, NULL, NULL),
(4, 1, 'Trắng', '#FFFFFF', NULL, NULL, NULL),
(5, 2, '4GB', NULL, NULL, NULL, NULL),
(6, 2, '8GB', NULL, NULL, NULL, NULL),
(7, 2, '16GB', NULL, NULL, NULL, NULL),
(8, 2, '32GB', NULL, NULL, NULL, NULL),
(9, 3, '64GB', NULL, NULL, NULL, NULL),
(10, 3, '128GB', NULL, NULL, NULL, NULL),
(11, 3, '256GB', NULL, NULL, NULL, NULL),
(12, 3, '512GB', NULL, NULL, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `banners`
--

CREATE TABLE `banners` (
  `id` bigint UNSIGNED NOT NULL,
  `stt` int NOT NULL DEFAULT '0',
  `image` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `start_date` datetime NOT NULL,
  `end_date` datetime NOT NULL,
  `link` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `banners`
--

INSERT INTO `banners` (`id`, `stt`, `image`, `start_date`, `end_date`, `link`, `created_at`, `updated_at`) VALUES
(1, 1, 'uploads/banners/banner1.jpg', '2025-08-10 07:32:37', '2025-09-09 07:32:37', 'https://techvicom.vn/', '2025-08-20 00:32:37', '2025-08-20 00:32:37'),
(2, 2, 'uploads/banners/banner2.jpg', '2025-08-15 07:32:37', '2025-09-14 07:32:37', 'https://techvicom.vn/khuyen-mai', '2025-08-20 00:32:37', '2025-08-20 00:32:37'),
(3, 3, 'uploads/banners/banner3.jpg', '2025-08-19 07:32:37', '2025-09-19 07:32:37', NULL, '2025-08-20 00:32:37', '2025-08-20 00:32:37');

-- --------------------------------------------------------

--
-- Table structure for table `brands`
--

CREATE TABLE `brands` (
  `id` bigint UNSIGNED NOT NULL,
  `name` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `image` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `slug` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `description` text COLLATE utf8mb4_unicode_ci,
  `status` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `brands`
--

INSERT INTO `brands` (`id`, `name`, `image`, `slug`, `description`, `status`, `created_at`, `updated_at`, `deleted_at`) VALUES
(1, 'Apple', 'brands/apple.png', 'apple', 'Chuyên các sản phẩm iPhone, MacBook, iPad.', 1, '2025-08-20 00:32:37', '2025-08-20 00:32:37', NULL),
(2, 'Samsung', 'brands/samsung.png', 'samsung', 'Thương hiệu điện thoại Android và thiết bị gia dụng.', 1, '2025-08-20 00:32:37', '2025-08-20 00:32:37', NULL),
(3, 'ASUS', 'brands/asus.png', 'asus', 'Chuyên laptop văn phòng, gaming, bo mạch chủ.', 1, '2025-08-20 00:32:37', '2025-08-20 00:32:37', NULL),
(4, 'Xiaomi', 'brands/xiaomi.png', 'xiaomi', 'Điện thoại thông minh và thiết bị IoT giá rẻ.', 1, '2025-08-20 00:32:37', '2025-08-20 00:32:37', NULL),
(5, 'Dell', 'brands/dell.png', 'dell', 'Laptop doanh nhân và máy chủ hiệu suất cao.', 1, '2025-08-20 00:32:37', '2025-08-20 00:32:37', NULL),
(6, 'HP', 'brands/hp.png', 'hp', 'Thương hiệu máy tính và thiết bị in ấn phổ biến.', 1, '2025-08-20 00:32:37', '2025-08-20 00:32:37', NULL),
(7, 'Lenovo', 'brands/lenovo.png', 'lenovo', 'Máy tính văn phòng, gaming và máy trạm.', 1, '2025-08-20 00:32:37', '2025-08-20 00:32:37', NULL),
(8, 'Sony', 'brands/sony.png', 'sony', 'Thiết bị giải trí, PlayStation và âm thanh cao cấp.', 1, '2025-08-20 00:32:37', '2025-08-20 00:32:37', NULL),
(9, 'MSI', 'brands/msi.png', 'msi', 'Chuyên laptop và linh kiện gaming cao cấp.', 1, '2025-08-20 00:32:37', '2025-08-20 00:32:37', NULL),
(10, 'Acer', 'brands/acer.png', 'acer', 'Laptop học sinh, sinh viên và văn phòng giá rẻ.', 1, '2025-08-20 00:32:37', '2025-08-20 00:32:37', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `cache`
--

CREATE TABLE `cache` (
  `key` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `value` mediumtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `expiration` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `cache_locks`
--

CREATE TABLE `cache_locks` (
  `key` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `owner` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `expiration` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `carts`
--

CREATE TABLE `carts` (
  `id` bigint UNSIGNED NOT NULL,
  `user_id` bigint UNSIGNED NOT NULL,
  `product_id` bigint UNSIGNED NOT NULL,
  `variant_id` bigint UNSIGNED DEFAULT NULL,
  `quantity` int NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `categories`
--

CREATE TABLE `categories` (
  `id` bigint UNSIGNED NOT NULL,
  `parent_id` bigint UNSIGNED DEFAULT NULL,
  `name` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `slug` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `image` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `status` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `categories`
--

INSERT INTO `categories` (`id`, `parent_id`, `name`, `slug`, `image`, `status`, `created_at`, `updated_at`, `deleted_at`) VALUES
(1, NULL, 'Laptop', 'laptop', NULL, 1, '2025-08-20 00:32:37', '2025-08-20 00:32:37', NULL),
(2, NULL, 'Điện thoại', 'dien-thoai', NULL, 1, '2025-08-20 00:32:37', '2025-08-20 00:32:37', NULL),
(3, NULL, 'Tablet', 'tablet', NULL, 1, '2025-08-20 00:32:37', '2025-08-20 00:32:37', NULL),
(4, NULL, 'Phụ kiện', 'phu-kien', NULL, 1, '2025-08-20 00:32:37', '2025-08-20 00:32:37', NULL),
(5, 1, 'Laptop Gaming', 'laptop-gaming', NULL, 1, '2025-08-20 00:32:37', '2025-08-20 00:32:37', NULL),
(6, 1, 'Laptop Văn phòng', 'laptop-van-phong', NULL, 1, '2025-08-20 00:32:37', '2025-08-20 00:32:37', NULL),
(7, 1, 'MacBook', 'macbook', NULL, 1, '2025-08-20 00:32:37', '2025-08-20 00:32:37', NULL),
(8, 2, 'iPhone', 'iphone', NULL, 1, '2025-08-20 00:32:37', '2025-08-20 00:32:37', NULL),
(9, 2, 'Samsung Galaxy', 'samsung-galaxy', NULL, 1, '2025-08-20 00:32:37', '2025-08-20 00:32:37', NULL),
(10, 2, 'Xiaomi', 'xiaomi', NULL, 1, '2025-08-20 00:32:37', '2025-08-20 00:32:37', NULL),
(11, 3, 'iPad', 'ipad', NULL, 1, '2025-08-20 00:32:37', '2025-08-20 00:32:37', NULL),
(12, 3, 'Samsung Tab', 'samsung-tab', NULL, 1, '2025-08-20 00:32:37', '2025-08-20 00:32:37', NULL),
(13, 4, 'Tai nghe', 'tai-nghe', NULL, 1, '2025-08-20 00:32:37', '2025-08-20 00:32:37', NULL),
(14, 4, 'Sạc và cáp', 'sac-va-cap', NULL, 1, '2025-08-20 00:32:37', '2025-08-20 00:32:37', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `contacts`
--

CREATE TABLE `contacts` (
  `id` bigint UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `phone` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `subject` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `message` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `user_id` bigint UNSIGNED DEFAULT NULL,
  `handled_by` bigint UNSIGNED DEFAULT NULL,
  `status` enum('pending','in_progress','responded','rejected') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'pending',
  `response` text COLLATE utf8mb4_unicode_ci,
  `responded_at` timestamp NULL DEFAULT NULL,
  `is_read` tinyint(1) NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `contacts`
--

INSERT INTO `contacts` (`id`, `name`, `email`, `phone`, `subject`, `message`, `user_id`, `handled_by`, `status`, `response`, `responded_at`, `is_read`, `created_at`, `updated_at`) VALUES
(1, 'Nguyễn Văn A', 'vana@example.com', '0909123456', 'Hỏi về sản phẩm', 'Cho tôi hỏi sản phẩm này còn hàng không?', 13, NULL, 'pending', NULL, NULL, 0, '2025-08-20 00:32:37', '2025-08-20 00:32:37'),
(2, 'Trần Thị B', 'thib@example.com', '0911222333', 'Thắc mắc giao hàng', 'Tôi muốn biết khi nào đơn hàng được giao.', 10, NULL, 'pending', NULL, NULL, 1, '2025-08-20 00:32:37', '2025-08-20 00:32:37'),
(3, 'Lê Văn C', 'vanc@example.com', '0922333444', 'Hủy đơn hàng', 'Tôi muốn hủy đơn hàng vừa đặt.', NULL, NULL, 'pending', NULL, NULL, 0, '2025-08-20 00:32:37', '2025-08-20 00:32:37'),
(4, 'Phạm Thị D', 'thid@example.com', '0933444555', 'Phản hồi dịch vụ', 'Dịch vụ chăm sóc khách hàng rất tốt.', NULL, NULL, 'pending', NULL, NULL, 1, '2025-08-20 00:32:37', '2025-08-20 00:32:37'),
(5, 'Đỗ Minh E', 'minhe@example.com', '0944555666', 'Đổi hàng', 'Tôi muốn đổi sản phẩm vì bị lỗi.', 9, NULL, 'pending', NULL, NULL, 0, '2025-08-20 00:32:37', '2025-08-20 00:32:37'),
(6, 'Hoàng Thị F', 'thif@example.com', '0955666777', 'Cần tư vấn', 'Bạn có thể tư vấn giúp tôi sản phẩm phù hợp?', NULL, NULL, 'pending', NULL, NULL, 1, '2025-08-20 00:32:37', '2025-08-20 00:32:37'),
(7, 'Ngô Văn G', 'vang@example.com', '0966777888', 'Góp ý', 'Website của bạn rất dễ sử dụng.', NULL, NULL, 'pending', NULL, NULL, 0, '2025-08-20 00:32:37', '2025-08-20 00:32:37'),
(8, 'Vũ Thị H', 'thih@example.com', '0977888999', 'Thanh toán', 'Tôi muốn đổi phương thức thanh toán.', 7, NULL, 'pending', NULL, NULL, 1, '2025-08-20 00:32:37', '2025-08-20 00:32:37'),
(9, 'Bùi Văn I', 'vani@example.com', '0988999000', 'Khuyến mãi', 'Cửa hàng hiện có chương trình khuyến mãi nào?', NULL, NULL, 'pending', NULL, NULL, 0, '2025-08-20 00:32:37', '2025-08-20 00:32:37'),
(10, 'Lý Thị K', 'thik@example.com', '0999000111', 'Đặt hàng lỗi', 'Tôi không thể đặt hàng trên website.', 2, NULL, 'pending', NULL, NULL, 1, '2025-08-20 00:32:37', '2025-08-20 00:32:37');

-- --------------------------------------------------------

--
-- Table structure for table `coupons`
--

CREATE TABLE `coupons` (
  `id` bigint UNSIGNED NOT NULL,
  `code` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `discount_type` enum('percent','fixed') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'percent',
  `value` bigint NOT NULL,
  `max_discount_amount` bigint DEFAULT NULL,
  `min_order_value` bigint DEFAULT NULL,
  `max_order_value` bigint DEFAULT NULL,
  `max_usage_per_user` int UNSIGNED NOT NULL DEFAULT '1',
  `start_date` date DEFAULT NULL,
  `end_date` date DEFAULT NULL,
  `status` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `promotion_id` bigint UNSIGNED DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `coupons`
--

INSERT INTO `coupons` (`id`, `code`, `discount_type`, `value`, `max_discount_amount`, `min_order_value`, `max_order_value`, `max_usage_per_user`, `start_date`, `end_date`, `status`, `created_at`, `updated_at`, `deleted_at`, `promotion_id`) VALUES
(1, 'DISCOUNT10', 'percent', 10, 100000, 500000, 5000000, 5, '2025-08-10', '2025-09-20', 1, NULL, '2025-08-20 00:33:19', NULL, NULL),
(2, 'BIGSALE10', 'percent', 10, 20000000, 50000000, 1000000000, 1, '2025-08-19', '2025-09-20', 1, NULL, NULL, NULL, 2),
(3, 'VIPFIXED', 'fixed', 50000000, NULL, 200000000, 2000000000, 1, '2025-08-19', '2025-09-20', 1, NULL, NULL, NULL, NULL),
(4, 'MEGAVIP', 'percent', 50, 100000000, 500000000, 5000000000, 1, '2025-08-19', '2025-09-20', 1, NULL, NULL, NULL, NULL),
(5, 'SALE50', 'percent', 50, 100000, 200000, 90000000, 2, '2025-08-19', '2025-09-19', 1, NULL, '2025-08-20 00:35:12', NULL, 1),
(6, 'SALE100', 'percent', 50, 100000, 200000, 1000000, 2, '2025-08-19', '2025-09-19', 1, NULL, NULL, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `failed_jobs`
--

CREATE TABLE `failed_jobs` (
  `id` bigint UNSIGNED NOT NULL,
  `uuid` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `connection` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `queue` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `exception` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `jobs`
--

CREATE TABLE `jobs` (
  `id` bigint UNSIGNED NOT NULL,
  `queue` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `attempts` tinyint UNSIGNED NOT NULL,
  `reserved_at` int UNSIGNED DEFAULT NULL,
  `available_at` int UNSIGNED NOT NULL,
  `created_at` int UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `job_batches`
--

CREATE TABLE `job_batches` (
  `id` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `total_jobs` int NOT NULL,
  `pending_jobs` int NOT NULL,
  `failed_jobs` int NOT NULL,
  `failed_job_ids` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `options` mediumtext COLLATE utf8mb4_unicode_ci,
  `cancelled_at` int DEFAULT NULL,
  `created_at` int NOT NULL,
  `finished_at` int DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `logos`
--

CREATE TABLE `logos` (
  `id` bigint UNSIGNED NOT NULL,
  `type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'client',
  `path` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `alt` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `logos`
--

INSERT INTO `logos` (`id`, `type`, `path`, `alt`, `created_at`, `updated_at`) VALUES
(1, 'client', 'logos/logo_techvicom.png', 'Logo trang chủ', '2025-08-20 00:32:37', '2025-08-20 00:32:37'),
(2, 'admin', 'logos/logo_techvicom.png', 'Logo admin', '2025-08-20 00:32:37', '2025-08-20 00:32:37');

-- --------------------------------------------------------

--
-- Table structure for table `mail_templates`
--

CREATE TABLE `mail_templates` (
  `id` bigint UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `subject` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `content` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `auto_send` tinyint(1) NOT NULL DEFAULT '0',
  `type` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `mail_templates`
--

INSERT INTO `mail_templates` (`id`, `name`, `subject`, `content`, `is_active`, `auto_send`, `type`, `created_at`, `updated_at`, `deleted_at`) VALUES
(1, 'Chào mừng', 'Chào mừng bạn đến với TechViCom!', '<p>Xin chào <b>{{ $user->name }}</b>,<br>Chào mừng bạn đã đăng ký tài khoản tại TechViCom!</p>', 1, 1, 'welcome', '2025-08-20 00:32:37', '2025-08-20 00:32:37', NULL),
(2, 'Gửi mã giảm giá', 'Nhận mã giảm giá đặc biệt từ TechViCom', '<p>Chào {{ $user->name }},<br>Bạn nhận được mã giảm giá: <b>{{ $coupon_code }}</b></p>', 1, 0, 'coupon', '2025-08-20 00:32:37', '2025-08-20 00:32:37', NULL),
(3, 'Chúc mừng sinh nhật', 'TechViCom chúc mừng sinh nhật bạn!', '<p>Chúc mừng sinh nhật {{ $user->name }}!<br>Chúc bạn một ngày thật vui vẻ và nhận nhiều ưu đãi từ TechViCom.</p>', 1, 1, 'birthday', '2025-08-20 00:32:37', '2025-08-20 00:32:37', NULL);

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
(1, '2025_08_04_182945_create_cache_table', 1),
(2, '2025_08_04_182945_create_jobs_table', 1),
(3, '2025_08_04_182945_create_users_table', 1),
(4, '2025_08_04_182946_create_permissions_table', 1),
(5, '2025_08_04_182946_create_roles_table', 1),
(6, '2025_08_04_182946_create_user_roles_table', 1),
(7, '2025_08_04_182947_create_news_table', 1),
(8, '2025_08_04_182947_create_notifications_table', 1),
(9, '2025_08_04_182947_create_permission_role_table', 1),
(10, '2025_08_04_182947_create_user_addresses_table', 1),
(11, '2025_08_04_182948_create_attributes_table', 1),
(12, '2025_08_04_182948_create_brands_table', 1),
(13, '2025_08_04_182948_create_categories_table', 1),
(14, '2025_08_04_182948_create_news_comments_table', 1),
(15, '2025_08_04_182948_create_products_table', 1),
(16, '2025_08_04_182948_create_user_notifications_table', 1),
(17, '2025_08_04_182949_create_product_variants_table', 1),
(18, '2025_08_04_182950_create_attribute_values_table', 1),
(19, '2025_08_04_182950_create_product_all_images_table', 1),
(20, '2025_08_04_182951_create_carts_table', 1),
(21, '2025_08_04_182951_create_coupons_table', 1),
(22, '2025_08_04_182951_create_orders_table', 1),
(23, '2025_08_04_182951_create_product_comments_table', 1),
(24, '2025_08_04_182952_create_order_items_table', 1),
(25, '2025_08_04_182952_create_product_variants_attributes_table', 1),
(26, '2025_08_04_182953_create_banners_table', 1),
(27, '2025_08_04_182953_create_shipping_methods_table', 1),
(28, '2025_08_04_182955_create_settings_table', 1),
(29, '2025_08_04_182956_create_contacts_table', 1),
(30, '2025_08_04_182956_create_new_categories_table', 1),
(31, '2025_08_04_200828_create_order_returns_table', 1),
(32, '2025_08_05_162327_add_shipping_method_and_deleted_at_to_orders_table', 1),
(33, '2025_08_05_162542_add_client_note_to_order_returns_table', 1),
(34, '2025_08_06_023636_enlarge_money_columns_on_orders_table', 1),
(35, '2025_08_08_003259_make_orders_support_guest_users', 1),
(36, '2025_08_08_013142_create_spatie_pivot_tables', 1),
(37, '2025_08_09_013506_create_personal_access_tokens_table', 1),
(38, '2025_08_09_021800_add_recipient_email_to_orders_table', 1),
(39, '2025_08_10_000001_alter_coupons_bigint', 1),
(40, '2025_08_12_125500_add_missing_columns_to_user_addresses_table', 1),
(41, '2025_08_15_022336_add_vnpay_fields_to_orders_table', 1),
(42, '2025_08_15_024219_increase_vnpay_url_length_in_orders_table', 1),
(43, '2025_08_15_141822_alter_variant_id_nullable_in_order_items_table', 1),
(44, '2025_08_15_181227_rename_product_variant_id_to_variant_id_in_carts_table', 1),
(45, '2025_08_17_165602_add_vnp_columns_to_orders_table', 1),
(46, '2025_08_18_000001_create_promotions_table', 1),
(47, '2025_08_18_000002_add_promotion_id_to_coupons_table', 1),
(48, '2025_08_18_000003_create_promotion_category_table', 1),
(49, '2025_08_18_000004_create_promotion_product_table', 1),
(50, '2025_08_18_025406_add_social_columns_to_users_table', 1),
(51, '2025_08_18_061620_create_mail_templates_table', 1),
(52, '2025_08_18_100000_add_discount_type_and_value_to_promotions_table', 1),
(53, '2025_08_18_120000_add_softdeletes_to_mail_templates_table', 1),
(54, '2025_08_19_000000_create_logos_table', 1);

-- --------------------------------------------------------

--
-- Table structure for table `model_has_permissions`
--

CREATE TABLE `model_has_permissions` (
  `permission_id` bigint UNSIGNED NOT NULL,
  `model_type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `model_id` bigint UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `model_has_roles`
--

CREATE TABLE `model_has_roles` (
  `role_id` bigint UNSIGNED NOT NULL,
  `model_type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `model_id` bigint UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `news`
--

CREATE TABLE `news` (
  `id` bigint UNSIGNED NOT NULL,
  `category_id` bigint UNSIGNED DEFAULT NULL,
  `title` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `content` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `image` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `author_id` bigint UNSIGNED DEFAULT NULL,
  `status` enum('published','draft','archived') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'draft',
  `published_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `news`
--

INSERT INTO `news` (`id`, `category_id`, `title`, `content`, `image`, `author_id`, `status`, `published_at`, `created_at`, `updated_at`, `deleted_at`) VALUES
(1, 1, 'Giảm giá 50% cho đơn hàng đầu tiên', 'Hãy nhanh tay nhận ưu đãi 50% khi mua hàng lần đầu tiên tại cửa hàng chúng tôi.', 'uploads/news/default.jpg', 10, 'published', '2025-08-20 00:32:37', '2025-08-20 00:32:37', '2025-08-20 00:32:37', NULL),
(2, 1, 'Mua 1 tặng 1 cuối tuần', 'Chương trình mua 1 tặng 1 áp dụng từ thứ 6 đến chủ nhật hàng tuần.', 'uploads/news/default.jpg', 10, 'published', '2025-08-20 00:32:37', '2025-08-20 00:32:37', '2025-08-20 00:32:37', NULL),
(3, 2, 'iPhone 15 chính thức ra mắt', 'Apple đã giới thiệu iPhone 15 với nhiều cải tiến về hiệu năng và camera.', 'uploads/news/default.jpg', 13, 'published', '2025-08-20 00:32:37', '2025-08-20 00:32:37', '2025-08-20 00:32:37', NULL),
(4, 2, 'Samsung trình làng Galaxy Z Flip6', 'Samsung tiếp tục đẩy mạnh phân khúc điện thoại gập với Galaxy Z Flip6.', 'uploads/news/default.jpg', 10, 'published', '2025-08-20 00:32:37', '2025-08-20 00:32:37', '2025-08-20 00:32:37', NULL),
(5, 3, 'Hướng dẫn sử dụng máy ép chậm', 'Bài viết sẽ giúp bạn hiểu rõ cách sử dụng máy ép chậm để giữ nguyên dưỡng chất.', 'uploads/news/default.jpg', 9, 'published', '2025-08-20 00:32:37', '2025-08-20 00:32:37', '2025-08-20 00:32:37', NULL),
(6, 3, 'Cách bảo quản tai nghe không dây', 'Giữ gìn tai nghe đúng cách giúp kéo dài tuổi thọ và giữ âm thanh tốt.', 'uploads/news/default.jpg', 9, 'published', '2025-08-20 00:32:37', '2025-08-20 00:32:37', '2025-08-20 00:32:37', NULL),
(7, 4, 'Đánh giá laptop Asus Zenbook 14', 'Asus Zenbook 14 nổi bật với thiết kế mỏng nhẹ, pin trâu và hiệu năng ổn định.', 'uploads/news/default.jpg', 2, 'published', '2025-08-20 00:32:37', '2025-08-20 00:32:37', '2025-08-20 00:32:37', NULL),
(8, 4, 'So sánh Xiaomi Redmi Note 12 và Realme 11', 'Cùng so sánh hai sản phẩm tầm trung hot nhất hiện nay.', 'uploads/news/default.jpg', 9, 'published', '2025-08-20 00:32:37', '2025-08-20 00:32:37', '2025-08-20 00:32:37', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `news_categories`
--

CREATE TABLE `news_categories` (
  `id` bigint UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `slug` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `news_categories`
--

INSERT INTO `news_categories` (`id`, `name`, `slug`, `created_at`, `updated_at`) VALUES
(1, 'Khuyến mãi', 'khuyen-mai', '2025-08-20 00:32:37', '2025-08-20 00:32:37'),
(2, 'Tin tức công nghệ', 'tin-tuc-cong-nghe', '2025-08-20 00:32:37', '2025-08-20 00:32:37'),
(3, 'Hướng dẫn sử dụng sản phẩm', 'huong-dan-su-dung-san-pham', '2025-08-20 00:32:37', '2025-08-20 00:32:37'),
(4, 'Đánh giá sản phẩm', 'danh-gia-san-pham', '2025-08-20 00:32:37', '2025-08-20 00:32:37'),
(5, 'Mẹo vặt công nghệ', 'meo-vat-cong-nghe', '2025-08-20 00:32:37', '2025-08-20 00:32:37'),
(6, 'Sự kiện và ra mắt sản phẩm', 'su-kien-ra-mat-san-pham', '2025-08-20 00:32:37', '2025-08-20 00:32:37'),
(7, 'Review cửa hàng', 'review-cua-hang', '2025-08-20 00:32:37', '2025-08-20 00:32:37'),
(8, 'Chăm sóc khách hàng', 'cham-soc-khach-hang', '2025-08-20 00:32:37', '2025-08-20 00:32:37'),
(9, 'Mua sắm trực tuyến', 'mua-sam-truc-tuyen', '2025-08-20 00:32:37', '2025-08-20 00:32:37'),
(10, 'Sản phẩm mới', 'san-pham-moi', '2025-08-20 00:32:37', '2025-08-20 00:32:37');

-- --------------------------------------------------------

--
-- Table structure for table `news_comments`
--

CREATE TABLE `news_comments` (
  `id` bigint UNSIGNED NOT NULL,
  `user_id` bigint UNSIGNED NOT NULL,
  `news_id` bigint UNSIGNED NOT NULL,
  `parent_id` bigint UNSIGNED DEFAULT NULL,
  `content` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `is_hidden` tinyint(1) NOT NULL DEFAULT '0',
  `likes_count` int UNSIGNED NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `news_comments`
--

INSERT INTO `news_comments` (`id`, `user_id`, `news_id`, `parent_id`, `content`, `is_hidden`, `likes_count`, `created_at`, `updated_at`) VALUES
(1, 13, 1, NULL, 'Có thể giải thích thêm phần này được không?', 0, 1, '2025-08-20 00:32:37', '2025-08-20 00:32:37'),
(2, 7, 1, 1, '↪ Thông tin chi tiết và rõ ràng.', 0, 0, '2025-08-20 00:32:37', '2025-08-20 00:32:37'),
(3, 10, 1, 1, '↪ Rất mong có thêm bài viết tương tự.', 0, 3, '2025-08-20 00:32:37', '2025-08-20 00:32:37'),
(4, 9, 1, NULL, 'Thông tin chi tiết và rõ ràng.', 1, 3, '2025-08-20 00:32:37', '2025-08-20 00:32:37'),
(5, 13, 1, NULL, 'Mình sẽ giới thiệu bài viết này cho bạn bè.', 0, 2, '2025-08-20 00:32:37', '2025-08-20 00:32:37'),
(6, 9, 1, 5, '↪ Rất thích nội dung kiểu này.', 1, 2, '2025-08-20 00:32:37', '2025-08-20 00:32:37'),
(7, 2, 1, 5, '↪ Cảm ơn bạn đã chia sẻ!', 0, 2, '2025-08-20 00:32:37', '2025-08-20 00:32:37'),
(8, 7, 1, NULL, 'Bài viết rất hữu ích, cảm ơn bạn!', 0, 0, '2025-08-20 00:32:37', '2025-08-20 00:32:37'),
(9, 9, 1, 8, '↪ Bài viết hay nhưng nên bổ sung thêm ví dụ.', 1, 1, '2025-08-20 00:32:37', '2025-08-20 00:32:37'),
(10, 2, 1, 8, '↪ Bài viết rất hữu ích, cảm ơn bạn!', 0, 4, '2025-08-20 00:32:37', '2025-08-20 00:32:37'),
(11, 9, 1, NULL, 'Có thể giải thích thêm phần này được không?', 0, 3, '2025-08-20 00:32:37', '2025-08-20 00:32:37'),
(12, 2, 2, NULL, 'Có thể giải thích thêm phần này được không?', 1, 0, '2025-08-20 00:32:37', '2025-08-20 00:32:37'),
(13, 10, 2, 12, '↪ Rất thích nội dung kiểu này.', 1, 1, '2025-08-20 00:32:37', '2025-08-20 00:32:37'),
(14, 7, 2, 12, '↪ Tôi đã áp dụng và thấy hiệu quả ngay.', 1, 4, '2025-08-20 00:32:37', '2025-08-20 00:32:37'),
(15, 2, 2, NULL, 'Rất mong có thêm bài viết tương tự.', 1, 4, '2025-08-20 00:32:37', '2025-08-20 00:32:37'),
(16, 9, 2, 15, '↪ Mình sẽ giới thiệu bài viết này cho bạn bè.', 1, 2, '2025-08-20 00:32:37', '2025-08-20 00:32:37'),
(17, 9, 2, 15, '↪ Rất thích nội dung kiểu này.', 0, 3, '2025-08-20 00:32:37', '2025-08-20 00:32:37'),
(18, 13, 2, NULL, 'Bài viết rất hữu ích, cảm ơn bạn!', 1, 2, '2025-08-20 00:32:37', '2025-08-20 00:32:37'),
(19, 2, 3, NULL, 'Bài viết hay nhưng nên bổ sung thêm ví dụ.', 0, 3, '2025-08-20 00:32:37', '2025-08-20 00:32:37'),
(20, 9, 3, NULL, 'Rất mong có thêm bài viết tương tự.', 0, 1, '2025-08-20 00:32:37', '2025-08-20 00:32:37'),
(21, 13, 3, 20, '↪ Sản phẩm này mình đã dùng, rất ok.', 1, 4, '2025-08-20 00:32:37', '2025-08-20 00:32:37'),
(22, 2, 3, NULL, 'Sản phẩm này mình đã dùng, rất ok.', 1, 0, '2025-08-20 00:32:37', '2025-08-20 00:32:37'),
(23, 9, 3, 22, '↪ Cảm ơn bạn đã chia sẻ!', 1, 2, '2025-08-20 00:32:37', '2025-08-20 00:32:37'),
(24, 9, 4, NULL, 'Mình sẽ giới thiệu bài viết này cho bạn bè.', 1, 10, '2025-08-20 00:32:37', '2025-08-20 00:32:37'),
(25, 10, 4, 24, '↪ Sản phẩm này mình đã dùng, rất ok.', 1, 3, '2025-08-20 00:32:37', '2025-08-20 00:32:37'),
(26, 7, 4, NULL, 'Sản phẩm này mình đã dùng, rất ok.', 1, 2, '2025-08-20 00:32:37', '2025-08-20 00:32:37'),
(27, 9, 4, NULL, 'Thông tin chi tiết và rõ ràng.', 1, 0, '2025-08-20 00:32:37', '2025-08-20 00:32:37'),
(28, 9, 4, 27, '↪ Mình sẽ giới thiệu bài viết này cho bạn bè.', 0, 2, '2025-08-20 00:32:37', '2025-08-20 00:32:37'),
(29, 7, 4, NULL, 'Mình sẽ giới thiệu bài viết này cho bạn bè.', 1, 4, '2025-08-20 00:32:37', '2025-08-20 00:32:37'),
(30, 10, 4, NULL, 'Sản phẩm này mình đã dùng, rất ok.', 0, 5, '2025-08-20 00:32:37', '2025-08-20 00:32:37'),
(31, 7, 5, NULL, 'Mình sẽ giới thiệu bài viết này cho bạn bè.', 1, 0, '2025-08-20 00:32:37', '2025-08-20 00:32:37'),
(32, 7, 5, 31, '↪ Có thể giải thích thêm phần này được không?', 1, 3, '2025-08-20 00:32:37', '2025-08-20 00:32:37'),
(33, 13, 5, NULL, 'Tôi đã áp dụng và thấy hiệu quả ngay.', 1, 8, '2025-08-20 00:32:37', '2025-08-20 00:32:37'),
(34, 10, 5, 33, '↪ Sản phẩm này mình đã dùng, rất ok.', 1, 2, '2025-08-20 00:32:37', '2025-08-20 00:32:37'),
(35, 7, 5, 33, '↪ Mình sẽ giới thiệu bài viết này cho bạn bè.', 0, 0, '2025-08-20 00:32:37', '2025-08-20 00:32:37'),
(36, 13, 5, NULL, 'Rất thích nội dung kiểu này.', 1, 10, '2025-08-20 00:32:37', '2025-08-20 00:32:37'),
(37, 2, 5, 36, '↪ Rất thích nội dung kiểu này.', 0, 2, '2025-08-20 00:32:37', '2025-08-20 00:32:37'),
(38, 13, 5, 36, '↪ Có thể giải thích thêm phần này được không?', 1, 1, '2025-08-20 00:32:37', '2025-08-20 00:32:37'),
(39, 9, 5, NULL, 'Cảm ơn bạn đã chia sẻ!', 1, 2, '2025-08-20 00:32:37', '2025-08-20 00:32:37'),
(40, 10, 5, 39, '↪ Có thể giải thích thêm phần này được không?', 0, 0, '2025-08-20 00:32:37', '2025-08-20 00:32:37'),
(41, 13, 6, NULL, 'Rất thích nội dung kiểu này.', 0, 2, '2025-08-20 00:32:37', '2025-08-20 00:32:37'),
(42, 9, 6, NULL, 'Tôi đã áp dụng và thấy hiệu quả ngay.', 1, 8, '2025-08-20 00:32:37', '2025-08-20 00:32:37'),
(43, 7, 6, NULL, 'Tôi đã áp dụng và thấy hiệu quả ngay.', 0, 9, '2025-08-20 00:32:37', '2025-08-20 00:32:37'),
(44, 9, 6, 43, '↪ Mình sẽ giới thiệu bài viết này cho bạn bè.', 0, 1, '2025-08-20 00:32:37', '2025-08-20 00:32:37'),
(45, 13, 6, NULL, 'Sản phẩm này mình đã dùng, rất ok.', 0, 5, '2025-08-20 00:32:37', '2025-08-20 00:32:37'),
(46, 10, 6, NULL, 'Bài viết hay nhưng nên bổ sung thêm ví dụ.', 1, 9, '2025-08-20 00:32:37', '2025-08-20 00:32:37'),
(47, 9, 6, 46, '↪ Sản phẩm này mình đã dùng, rất ok.', 0, 3, '2025-08-20 00:32:37', '2025-08-20 00:32:37'),
(48, 7, 7, NULL, 'Sản phẩm này mình đã dùng, rất ok.', 1, 8, '2025-08-20 00:32:37', '2025-08-20 00:32:37'),
(49, 2, 7, NULL, 'Rất thích nội dung kiểu này.', 0, 0, '2025-08-20 00:32:37', '2025-08-20 00:32:37'),
(50, 2, 7, 49, '↪ Bài viết hay nhưng nên bổ sung thêm ví dụ.', 0, 0, '2025-08-20 00:32:37', '2025-08-20 00:32:37'),
(51, 10, 7, 49, '↪ Tôi đã áp dụng và thấy hiệu quả ngay.', 0, 3, '2025-08-20 00:32:37', '2025-08-20 00:32:37'),
(52, 7, 7, NULL, 'Bài viết hay nhưng nên bổ sung thêm ví dụ.', 1, 0, '2025-08-20 00:32:37', '2025-08-20 00:32:37'),
(53, 9, 8, NULL, 'Cảm ơn bạn đã chia sẻ!', 1, 1, '2025-08-20 00:32:37', '2025-08-20 00:32:37'),
(54, 2, 8, 53, '↪ Thông tin chi tiết và rõ ràng.', 0, 4, '2025-08-20 00:32:37', '2025-08-20 00:32:37'),
(55, 13, 8, NULL, 'Rất thích nội dung kiểu này.', 0, 2, '2025-08-20 00:32:37', '2025-08-20 00:32:37'),
(56, 10, 8, NULL, 'Cảm ơn bạn đã chia sẻ!', 0, 4, '2025-08-20 00:32:37', '2025-08-20 00:32:37'),
(57, 13, 8, NULL, 'Bài viết hay nhưng nên bổ sung thêm ví dụ.', 0, 5, '2025-08-20 00:32:37', '2025-08-20 00:32:37'),
(58, 2, 8, 57, '↪ Có thể giải thích thêm phần này được không?', 0, 3, '2025-08-20 00:32:37', '2025-08-20 00:32:37'),
(59, 13, 8, 57, '↪ Bài viết hay nhưng nên bổ sung thêm ví dụ.', 0, 0, '2025-08-20 00:32:37', '2025-08-20 00:32:37');

-- --------------------------------------------------------

--
-- Table structure for table `notifications`
--

CREATE TABLE `notifications` (
  `id` bigint UNSIGNED NOT NULL,
  `title` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `message` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `type` enum('global','personal') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'global',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `orders`
--

CREATE TABLE `orders` (
  `id` bigint UNSIGNED NOT NULL,
  `user_id` bigint UNSIGNED DEFAULT NULL,
  `address_id` bigint UNSIGNED DEFAULT NULL,
  `guest_name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `guest_email` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `guest_phone` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `payment_method` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `coupon_id` bigint UNSIGNED DEFAULT NULL,
  `coupon_code` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `discount_amount` decimal(10,2) DEFAULT '0.00',
  `shipping_fee` decimal(15,0) NOT NULL,
  `total_amount` decimal(15,0) NOT NULL,
  `final_total` decimal(15,0) NOT NULL,
  `status` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'pending',
  `payment_status` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'pending',
  `vnpay_url` varchar(2000) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `vnp_txn_ref` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `vnp_amount_expected` bigint UNSIGNED DEFAULT NULL,
  `vnpay_transaction_id` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `vnpay_bank_code` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `vnpay_card_type` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `paid_at` timestamp NULL DEFAULT NULL,
  `recipient_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `recipient_phone` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `recipient_email` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `recipient_address` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `shipped_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `shipping_method_id` bigint UNSIGNED DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `orders`
--

INSERT INTO `orders` (`id`, `user_id`, `address_id`, `guest_name`, `guest_email`, `guest_phone`, `payment_method`, `coupon_id`, `coupon_code`, `discount_amount`, `shipping_fee`, `total_amount`, `final_total`, `status`, `payment_status`, `vnpay_url`, `vnp_txn_ref`, `vnp_amount_expected`, `vnpay_transaction_id`, `vnpay_bank_code`, `vnpay_card_type`, `paid_at`, `recipient_name`, `recipient_phone`, `recipient_email`, `recipient_address`, `shipped_at`, `created_at`, `updated_at`, `shipping_method_id`, `deleted_at`) VALUES
(1, 5, 17, NULL, NULL, NULL, 'bank_transfer', NULL, NULL, '1.40', '16', '279', '293', 'pending', 'pending', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Keven Trantow', '445.888.2596', NULL, '2423 Reilly Springs\nLake Wilton, CA 87098', NULL, '2025-07-15 00:52:22', '2025-08-20 00:32:37', NULL, NULL),
(2, 7, 32, NULL, NULL, NULL, 'credit_card', 6, 'XQ9VTG53', '35.28', '20', '332', '317', 'pending', 'pending', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Edmond Kulas', '+1.484.884.5272', NULL, '91188 Yost Drive\nRollinside, OK 85246', NULL, '2025-05-21 06:41:17', '2025-08-20 00:32:37', NULL, NULL),
(3, 3, 31, NULL, NULL, NULL, 'credit_card', NULL, NULL, '19.36', '12', '240', '233', 'pending', 'pending', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Hayden Conroy', '385.964.5255', NULL, '707 Adriel Ports Apt. 371\nStephaniaborough, NJ 49405', NULL, '2025-05-27 17:50:52', '2025-08-20 00:32:37', NULL, NULL),
(4, 9, 14, NULL, NULL, NULL, 'paypal', 5, 'NW3BTUXM', '44.66', '12', '365', '332', 'pending', 'pending', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Alexandrine Daniel', '(952) 573-3111', NULL, '12861 Botsford Fields\nNorth Troy, MI 12056', NULL, '2025-02-22 22:06:20', '2025-08-20 00:32:37', 17, NULL),
(5, 10, 11, NULL, NULL, NULL, 'bank_transfer', 4, 'DYJ0JBHY', '0.41', '16', '418', '433', 'pending', 'pending', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Jazmyn Keebler', '669-365-0998', NULL, '5898 Reynolds Passage Apt. 734\nNew Prestonshire, VT 97839', '2025-08-05 19:03:19', '2025-07-07 20:36:31', '2025-08-20 00:32:37', 16, NULL),
(6, 2, 38, NULL, NULL, NULL, 'paypal', NULL, NULL, '5.86', '10', '61', '65', 'pending', 'pending', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Ruthie Legros MD', '858-230-7822', NULL, '98593 Ziemann Points Suite 124\nNorth Macchester, WV 90958-2239', '2025-05-22 00:07:32', '2025-05-10 03:54:06', '2025-08-20 00:32:37', 8, '2025-07-06 22:25:47'),
(7, 6, 36, NULL, NULL, NULL, 'credit_card', NULL, NULL, '7.97', '9', '209', '209', 'pending', 'pending', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Zack Ullrich', '(743) 800-5682', NULL, '1863 Thompson Crossing Suite 737\nSallymouth, CA 11374', '2025-07-16 14:20:11', '2025-03-16 04:06:20', '2025-08-20 00:32:37', NULL, '2025-05-29 18:48:24'),
(8, 8, 30, NULL, NULL, NULL, 'credit_card', NULL, NULL, '44.79', '18', '307', '280', 'pending', 'pending', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Mr. Armani Stracke DVM', '843.631.3569', NULL, '751 Jasmin Forges Suite 695\nCloydside, MA 87401', '2025-07-14 15:43:25', '2025-06-28 17:01:25', '2025-08-20 00:32:37', 15, NULL),
(9, 12, 13, NULL, NULL, NULL, 'credit_card', NULL, NULL, '26.54', '14', '93', '81', 'pending', 'pending', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Prof. Caden Rogahn PhD', '(484) 889-7576', NULL, '2582 Chris Knolls\nNew Joaniehaven, WA 03785-9451', '2025-08-08 03:35:15', '2025-07-28 02:32:29', '2025-08-20 00:32:37', 13, NULL),
(10, 8, 19, NULL, NULL, NULL, 'paypal', 3, 'C2E4OYEK', '28.23', '17', '175', '163', 'pending', 'pending', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Prof. Genevieve Johnson Sr.', '+1 (628) 852-1720', NULL, '81792 Stark Falls Suite 719\nNew Oran, OK 68865', NULL, '2025-07-13 10:35:27', '2025-08-20 00:32:37', NULL, NULL),
(11, 11, 10, NULL, NULL, NULL, 'bank_transfer', NULL, NULL, '37.22', '6', '145', '113', 'pending', 'pending', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Milan Sporer', '+1 (407) 484-6595', NULL, '7726 Vandervort Isle\nReingerview, MO 50647', NULL, '2025-07-12 13:33:09', '2025-08-20 00:32:37', 4, '2025-08-09 08:49:14'),
(12, 13, 22, NULL, NULL, NULL, 'credit_card', NULL, NULL, '40.56', '8', '343', '310', 'pending', 'pending', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Luna McKenzie', '267-514-0234', NULL, '76321 Towne Lakes\nPagacberg, RI 41140', '2025-08-01 00:38:52', '2025-02-23 03:49:34', '2025-08-20 00:32:37', NULL, NULL),
(13, 8, 38, NULL, NULL, NULL, 'credit_card', NULL, NULL, '27.69', '9', '81', '63', 'pending', 'pending', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Minnie Rodriguez MD', '+1 (283) 595-2162', NULL, '264 Maximus Isle Suite 062\nSouth Anahi, NC 98438', '2025-06-01 20:37:40', '2025-05-02 15:33:00', '2025-08-20 00:32:37', 10, NULL),
(14, 11, 21, NULL, NULL, NULL, 'bank_transfer', NULL, NULL, '36.17', '8', '316', '287', 'pending', 'pending', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Eloisa Borer', '+1-325-279-8980', NULL, '87649 Destiny Neck Apt. 534\nPort Casimer, MN 08780-2677', NULL, '2025-03-20 07:08:51', '2025-08-20 00:32:37', NULL, NULL),
(15, 1, 39, NULL, NULL, NULL, 'bank_transfer', NULL, NULL, '1.81', '10', '414', '423', 'pending', 'pending', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Dorothea Deckow', '+1-801-692-3819', NULL, '4183 Dashawn Gateway\nNew Tobinstad, KY 37139', '2025-06-21 15:36:01', '2025-04-05 06:11:29', '2025-08-20 00:32:37', NULL, NULL),
(16, 10, 14, NULL, NULL, NULL, 'credit_card', 2, 'GOZYEZZZ', '8.26', '10', '228', '230', 'pending', 'pending', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Elena Casper', '726.825.7282', NULL, '3264 Harris Flat\nWest Jayceport, HI 11176-3219', '2025-07-21 22:09:43', '2025-03-16 07:07:32', '2025-08-20 00:32:37', 1, NULL),
(17, 2, 3, NULL, NULL, NULL, 'credit_card', NULL, NULL, '49.06', '7', '257', '215', 'pending', 'pending', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Cheyanne Cummerata', '229.606.8414', NULL, '766 Lemke Forges Suite 276\nBernhardview, NV 05665', '2025-07-13 05:53:21', '2025-04-01 12:29:41', '2025-08-20 00:32:37', NULL, NULL),
(18, 6, 36, NULL, NULL, NULL, 'paypal', NULL, NULL, '23.65', '16', '394', '386', 'pending', 'pending', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Esta Grady', '930.771.5654', NULL, '44057 Mercedes Forge\nEast Quintonport, MD 68889', '2025-06-25 11:44:47', '2025-05-24 22:06:42', '2025-08-20 00:32:37', NULL, NULL),
(19, 10, 21, NULL, NULL, NULL, 'paypal', NULL, NULL, '9.83', '15', '280', '285', 'pending', 'pending', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Leopold Hettinger', '+13416073489', NULL, '9785 Senger Lodge Apt. 174\nNew Yasminechester, OH 61955-0360', NULL, '2025-04-21 20:06:00', '2025-08-20 00:32:37', NULL, NULL),
(20, 8, 6, NULL, NULL, NULL, 'bank_transfer', NULL, NULL, '37.55', '18', '163', '144', 'pending', 'pending', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Luis Carter', '+1-210-650-6635', NULL, '80818 Waylon Flats\nMarcelberg, AL 76746', NULL, '2025-03-29 15:22:11', '2025-08-20 00:32:37', 18, '2025-05-06 00:43:05');

-- --------------------------------------------------------

--
-- Table structure for table `order_items`
--

CREATE TABLE `order_items` (
  `id` bigint UNSIGNED NOT NULL,
  `order_id` bigint UNSIGNED NOT NULL,
  `variant_id` bigint UNSIGNED DEFAULT NULL,
  `product_id` bigint UNSIGNED NOT NULL,
  `name_product` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `image_product` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `quantity` int NOT NULL,
  `price` decimal(10,2) NOT NULL,
  `total_price` decimal(10,2) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `order_items`
--

INSERT INTO `order_items` (`id`, `order_id`, `variant_id`, `product_id`, `name_product`, `image_product`, `quantity`, `price`, `total_price`, `created_at`, `updated_at`) VALUES
(1, 3, 12, 11, 'Tai nghe Xiaomi Buds 4 Pro', NULL, 5, '55.21', '276.05', '2025-08-20 00:32:37', '2025-08-20 00:32:37'),
(2, 10, 8, 4, 'Laptop Asus Zenbook 14 OLED', NULL, 4, '162.09', '648.36', '2025-08-20 00:32:37', '2025-08-20 00:32:37'),
(3, 9, 9, 5, 'iPad Pro M2 11inch', NULL, 2, '31.01', '62.02', '2025-08-20 00:32:37', '2025-08-20 00:32:37'),
(4, 17, 12, 5, 'iPad Pro M2 11inch', NULL, 5, '172.60', '863.00', '2025-08-20 00:32:37', '2025-08-20 00:32:37'),
(5, 2, 2, 9, 'Samsung Tab S9 Ultra', NULL, 2, '100.86', '201.72', '2025-08-20 00:32:37', '2025-08-20 00:32:37'),
(6, 6, 16, 5, 'iPad Pro M2 11inch', NULL, 1, '51.22', '51.22', '2025-08-20 00:32:37', '2025-08-20 00:32:37'),
(7, 5, 15, 7, 'Samsung Galaxy S24 Ultra', NULL, 2, '165.22', '330.44', '2025-08-20 00:32:37', '2025-08-20 00:32:37'),
(8, 19, 8, 1, 'Điện thoại Flagship XYZ 2025', NULL, 3, '161.24', '483.72', '2025-08-20 00:32:37', '2025-08-20 00:32:37'),
(9, 2, 4, 7, 'Samsung Galaxy S24 Ultra', NULL, 1, '44.38', '44.38', '2025-08-20 00:32:37', '2025-08-20 00:32:37'),
(10, 5, 2, 1, 'Điện thoại Flagship XYZ 2025', NULL, 4, '81.86', '327.44', '2025-08-20 00:32:37', '2025-08-20 00:32:37'),
(11, 11, 15, 10, 'Xiaomi Redmi Note 13 Pro', NULL, 4, '152.91', '611.64', '2025-08-20 00:32:37', '2025-08-20 00:32:37'),
(12, 8, 12, 7, 'Samsung Galaxy S24 Ultra', NULL, 5, '67.83', '339.15', '2025-08-20 00:32:37', '2025-08-20 00:32:37'),
(13, 18, 16, 2, 'Laptop Gaming ROG Zephyrus G16', NULL, 5, '173.31', '866.55', '2025-08-20 00:32:37', '2025-08-20 00:32:37'),
(14, 5, 16, 10, 'Xiaomi Redmi Note 13 Pro', NULL, 3, '68.61', '205.83', '2025-08-20 00:32:37', '2025-08-20 00:32:37'),
(15, 14, 2, 10, 'Xiaomi Redmi Note 13 Pro', NULL, 5, '51.86', '259.30', '2025-08-20 00:32:37', '2025-08-20 00:32:37'),
(16, 19, 6, 4, 'Laptop Asus Zenbook 14 OLED', NULL, 4, '89.71', '358.84', '2025-08-20 00:32:37', '2025-08-20 00:32:37'),
(17, 2, 3, 7, 'Samsung Galaxy S24 Ultra', NULL, 4, '153.82', '615.28', '2025-08-20 00:32:37', '2025-08-20 00:32:37'),
(18, 16, 15, 11, 'Tai nghe Xiaomi Buds 4 Pro', NULL, 5, '40.02', '200.10', '2025-08-20 00:32:37', '2025-08-20 00:32:37'),
(19, 19, 12, 11, 'Tai nghe Xiaomi Buds 4 Pro', NULL, 5, '181.45', '907.25', '2025-08-20 00:32:37', '2025-08-20 00:32:37'),
(20, 17, 12, 9, 'Samsung Tab S9 Ultra', NULL, 2, '125.37', '250.74', '2025-08-20 00:32:37', '2025-08-20 00:32:37');

-- --------------------------------------------------------

--
-- Table structure for table `order_returns`
--

CREATE TABLE `order_returns` (
  `id` bigint UNSIGNED NOT NULL,
  `order_id` bigint UNSIGNED NOT NULL,
  `reason` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `status` enum('pending','approved','rejected') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'pending',
  `type` enum('cancel','return') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'return',
  `requested_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `processed_at` datetime DEFAULT NULL,
  `admin_note` text COLLATE utf8mb4_unicode_ci,
  `client_note` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `order_returns`
--

INSERT INTO `order_returns` (`id`, `order_id`, `reason`, `status`, `type`, `requested_at`, `processed_at`, `admin_note`, `client_note`, `created_at`, `updated_at`) VALUES
(1, 19, 'Sản phẩm lỗi', 'rejected', 'return', '2025-07-29 16:52:31', NULL, 'Sint atque voluptatem facilis quo maiores reprehenderit.', 'Quia quia sequi magnam dignissimos illo iusto.', '2025-08-20 00:32:37', '2025-08-20 00:32:37'),
(2, 20, NULL, 'approved', 'return', '2025-07-24 15:35:28', '2025-07-30 11:57:09', NULL, 'Voluptatem molestiae nobis molestiae quo qui.', '2025-08-20 00:32:37', '2025-08-20 00:32:37'),
(3, 19, 'Khách thay đổi ý định', 'rejected', 'cancel', '2025-07-29 21:04:07', '2025-08-17 11:30:32', NULL, 'Rerum aut consequuntur enim accusantium consequatur debitis.', '2025-08-20 00:32:37', '2025-08-20 00:32:37'),
(4, 15, 'Không đúng mô tả', 'approved', 'return', '2025-07-23 16:50:51', '2025-08-10 18:00:06', NULL, 'Error beatae odio esse quia.', '2025-08-20 00:32:37', '2025-08-20 00:32:37'),
(5, 5, 'Khách thay đổi ý định', 'pending', 'return', '2025-08-02 06:20:23', NULL, NULL, 'Consequuntur libero illum et repellendus a.', '2025-08-20 00:32:37', '2025-08-20 00:32:37'),
(6, 1, NULL, 'rejected', 'cancel', '2025-08-09 08:45:22', '2025-08-14 23:04:19', 'Illum porro nihil sunt et dolore.', NULL, '2025-08-20 00:32:37', '2025-08-20 00:32:37'),
(7, 17, 'Giao nhầm hàng', 'pending', 'cancel', '2025-08-15 23:19:29', '2025-08-19 16:53:44', 'Consectetur et odit id qui.', 'Et repellat illo consequatur et ea eligendi qui.', '2025-08-20 00:32:37', '2025-08-20 00:32:37'),
(8, 18, 'Sản phẩm lỗi', 'pending', 'return', '2025-07-26 16:52:37', NULL, 'Occaecati necessitatibus natus praesentium in et rerum at.', 'In soluta veniam perferendis ullam corrupti iusto reiciendis.', '2025-08-20 00:32:37', '2025-08-20 00:32:37'),
(9, 10, 'Không cần nữa', 'pending', 'return', '2025-08-18 04:15:16', '2025-08-19 09:59:23', 'Perferendis omnis itaque repudiandae tempora consequuntur ducimus.', 'Nemo eum voluptas quia sint dolorem.', '2025-08-20 00:32:37', '2025-08-20 00:32:37'),
(10, 16, NULL, 'rejected', 'cancel', '2025-08-07 22:43:10', '2025-08-19 22:02:25', NULL, 'Nesciunt et vero sunt possimus non esse.', '2025-08-20 00:32:37', '2025-08-20 00:32:37'),
(11, 2, 'Không cần nữa', 'approved', 'return', '2025-07-27 20:41:27', '2025-08-04 00:30:07', 'Beatae vel dolor nobis tenetur odio recusandae reprehenderit est.', NULL, '2025-08-20 00:32:37', '2025-08-20 00:32:37'),
(12, 8, 'Khách thay đổi ý định', 'pending', 'cancel', '2025-08-05 16:14:09', NULL, NULL, 'Veniam modi nobis tenetur repellendus saepe.', '2025-08-20 00:32:37', '2025-08-20 00:32:37'),
(13, 16, NULL, 'pending', 'return', '2025-08-08 13:14:00', '2025-08-18 03:06:50', 'Est quia laborum veritatis non et.', 'Eos qui fugiat culpa rerum omnis.', '2025-08-20 00:32:37', '2025-08-20 00:32:37'),
(14, 4, 'Sản phẩm lỗi', 'pending', 'cancel', '2025-08-07 03:31:29', NULL, 'Sed quisquam reprehenderit quaerat nisi.', 'Aut quo quas qui rerum consectetur ut.', '2025-08-20 00:32:37', '2025-08-20 00:32:37'),
(15, 8, 'Giao nhầm hàng', 'approved', 'return', '2025-08-05 12:28:04', '2025-08-11 08:43:22', NULL, 'Ut facilis quia expedita optio illum.', '2025-08-20 00:32:37', '2025-08-20 00:32:37'),
(16, 5, NULL, 'pending', 'return', '2025-08-03 13:11:16', NULL, NULL, 'Dolor sunt rerum sunt et qui ullam numquam nemo.', '2025-08-20 00:32:37', '2025-08-20 00:32:37'),
(17, 10, 'Giao nhầm hàng', 'rejected', 'cancel', '2025-07-27 04:53:06', NULL, 'Vitae mollitia aliquam recusandae sed.', NULL, '2025-08-20 00:32:37', '2025-08-20 00:32:37'),
(18, 15, 'Khách thay đổi ý định', 'pending', 'return', '2025-07-26 06:49:46', NULL, 'Deserunt mollitia sit corrupti aliquid.', NULL, '2025-08-20 00:32:37', '2025-08-20 00:32:37'),
(19, 3, 'Khách thay đổi ý định', 'approved', 'return', '2025-07-24 16:14:12', NULL, NULL, 'Nostrum omnis accusantium nostrum odio.', '2025-08-20 00:32:37', '2025-08-20 00:32:37'),
(20, 15, 'Không cần nữa', 'approved', 'return', '2025-08-16 22:29:06', '2025-08-20 05:12:04', 'Magnam sint dolore voluptate deleniti magnam molestiae vel.', 'Aperiam velit voluptates sint dignissimos.', '2025-08-20 00:32:37', '2025-08-20 00:32:37');

-- --------------------------------------------------------

--
-- Table structure for table `password_reset_tokens`
--

CREATE TABLE `password_reset_tokens` (
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `permissions`
--

CREATE TABLE `permissions` (
  `id` bigint UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `permissions`
--

INSERT INTO `permissions` (`id`, `name`, `description`, `created_at`, `updated_at`, `deleted_at`) VALUES
(1, 'view_users', 'Xem danh sách người dùng', '2025-08-20 00:32:37', '2025-08-20 00:32:37', NULL),
(2, 'edit_users', 'Chỉnh sửa người dùng', '2025-08-20 00:32:37', '2025-08-20 00:32:37', NULL),
(3, 'delete_users', 'Xoá người dùng', '2025-08-20 00:32:37', '2025-08-20 00:32:37', NULL),
(4, 'manage_roles', 'Quản lý vai trò', '2025-08-20 00:32:37', '2025-08-20 00:32:37', NULL),
(5, 'manage_content', 'Quản lý nội dung', '2025-08-20 00:32:37', '2025-08-20 00:32:37', NULL),
(6, 'manage_coupons', 'Quản lý mã giảm giá', '2025-08-20 00:32:37', '2025-08-20 00:32:37', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `permission_role`
--

CREATE TABLE `permission_role` (
  `id` bigint UNSIGNED NOT NULL,
  `permission_id` bigint UNSIGNED NOT NULL,
  `role_id` bigint UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `permission_role`
--

INSERT INTO `permission_role` (`id`, `permission_id`, `role_id`, `created_at`, `updated_at`) VALUES
(1, 3, 1, '2025-08-20 00:32:37', '2025-08-20 00:32:37'),
(2, 2, 1, '2025-08-20 00:32:37', '2025-08-20 00:32:37'),
(3, 5, 1, '2025-08-20 00:32:37', '2025-08-20 00:32:37'),
(4, 6, 1, '2025-08-20 00:32:37', '2025-08-20 00:32:37'),
(5, 4, 1, '2025-08-20 00:32:37', '2025-08-20 00:32:37'),
(6, 1, 1, '2025-08-20 00:32:37', '2025-08-20 00:32:37'),
(7, 2, 2, '2025-08-20 00:32:37', '2025-08-20 00:32:37'),
(8, 5, 2, '2025-08-20 00:32:37', '2025-08-20 00:32:37'),
(9, 6, 2, '2025-08-20 00:32:37', '2025-08-20 00:32:37'),
(10, 1, 2, '2025-08-20 00:32:37', '2025-08-20 00:32:37'),
(11, 1, 3, '2025-08-20 00:32:37', '2025-08-20 00:32:37');

-- --------------------------------------------------------

--
-- Table structure for table `personal_access_tokens`
--

CREATE TABLE `personal_access_tokens` (
  `id` bigint UNSIGNED NOT NULL,
  `tokenable_type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `tokenable_id` bigint UNSIGNED NOT NULL,
  `name` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(64) COLLATE utf8mb4_unicode_ci NOT NULL,
  `abilities` text COLLATE utf8mb4_unicode_ci,
  `last_used_at` timestamp NULL DEFAULT NULL,
  `expires_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `products`
--

CREATE TABLE `products` (
  `id` bigint UNSIGNED NOT NULL,
  `category_id` bigint UNSIGNED DEFAULT NULL,
  `brand_id` bigint UNSIGNED DEFAULT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `slug` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `type` enum('simple','variable') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'simple',
  `short_description` text COLLATE utf8mb4_unicode_ci,
  `long_description` longtext COLLATE utf8mb4_unicode_ci,
  `thumbnail` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `status` enum('active','inactive') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'active',
  `is_featured` tinyint(1) NOT NULL DEFAULT '0',
  `view_count` int UNSIGNED NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `products`
--

INSERT INTO `products` (`id`, `category_id`, `brand_id`, `name`, `slug`, `type`, `short_description`, `long_description`, `thumbnail`, `status`, `is_featured`, `view_count`, `created_at`, `updated_at`, `deleted_at`) VALUES
(1, 8, 1, 'Điện thoại Flagship XYZ 2025', 'dien-thoai-flagship-xyz-2025', 'variable', 'Siêu phẩm công nghệ với màn hình Super Retina và chip A20 Bionic.', 'Chi tiết về các công nghệ đột phá, camera siêu nét và thời lượng pin vượt trội của Điện thoại Flagship XYZ 2025.', NULL, 'active', 1, 1500, '2025-08-20 00:32:37', '2025-08-20 00:32:37', NULL),
(2, 5, 3, 'Laptop Gaming ROG Zephyrus G16', 'laptop-gaming-rog-zephyrus-g16', 'variable', 'Mạnh mẽ trong thân hình mỏng nhẹ, màn hình Nebula HDR tuyệt đỉnh.', 'Trải nghiệm gaming và sáng tạo không giới hạn với CPU Intel Core Ultra 9 và card đồ họa NVIDIA RTX 4080.', NULL, 'active', 1, 950, '2025-08-20 00:32:37', '2025-08-20 00:32:37', NULL),
(3, 8, 1, 'iPhone SE 2024', 'iphone-se-2024', 'simple', 'Sức mạnh đáng kinh ngạc trong một thiết kế nhỏ gọn, quen thuộc.', 'iPhone SE 2024 trang bị chip A17 Bionic mạnh mẽ, kết nối 5G và camera tiên tiến. Một lựa chọn tuyệt vời với mức giá phải chăng.', NULL, 'active', 0, 12500, '2025-08-20 00:32:37', '2025-08-20 00:32:37', NULL),
(4, 5, 3, 'Laptop Asus Zenbook 14 OLED', 'laptop-asus-zenbook-14-oled', 'simple', 'Mỏng nhẹ tinh tế, màn hình OLED 2.8K rực rỡ, chuẩn Intel Evo.', 'Asus Zenbook 14 OLED là sự kết hợp hoàn hảo giữa hiệu năng và tính di động, lý tưởng cho các chuyên gia sáng tạo và doanh nhân năng động.', NULL, 'active', 0, 3100, '2025-08-20 00:32:37', '2025-08-20 00:32:37', NULL),
(5, 3, 1, 'iPad Pro M2 11inch', 'ipad-pro-m2-11inch', 'variable', 'Màn hình Liquid Retina, chip M2 mạnh mẽ.', 'iPad Pro M2 11inch dành cho công việc sáng tạo và giải trí.', NULL, 'active', 1, 2100, '2025-08-20 00:32:37', '2025-08-20 00:32:37', NULL),
(6, 7, 1, 'MacBook Pro M3 14inch', 'macbook-pro-m3-14inch', 'variable', 'Hiệu năng đỉnh cao, màn hình mini-LED.', 'MacBook Pro M3 14inch dành cho lập trình viên và designer.', NULL, 'active', 1, 1800, '2025-08-20 00:32:37', '2025-08-20 00:32:37', NULL),
(7, 8, 2, 'Samsung Galaxy S24 Ultra', 'samsung-galaxy-s24-ultra', 'simple', 'Camera 200MP, pin 5000mAh.', 'Flagship Android mạnh mẽ nhất của Samsung.', NULL, 'active', 1, 3200, '2025-08-20 00:32:37', '2025-08-20 00:32:37', NULL),
(8, 13, 8, 'Tai nghe Sony WH-1000XM5', 'tai-nghe-sony-wh-1000xm5', 'simple', 'Chống ồn chủ động, pin 30h.', 'Tai nghe cao cấp dành cho audiophile và dân văn phòng.', NULL, 'active', 0, 900, '2025-08-20 00:32:37', '2025-08-20 00:32:37', NULL),
(9, 12, 2, 'Samsung Tab S9 Ultra', 'samsung-tab-s9-ultra', 'variable', 'Màn hình AMOLED 14.6 inch, S Pen đi kèm.', 'Tablet Android mạnh mẽ nhất của Samsung.', NULL, 'active', 0, 1100, '2025-08-20 00:32:37', '2025-08-20 00:32:37', NULL),
(10, 8, 4, 'Xiaomi Redmi Note 13 Pro', 'xiaomi-redmi-note-13-pro', 'simple', 'Camera 200MP, pin 5000mAh, sạc nhanh 120W.', 'Điện thoại tầm trung cấu hình mạnh, giá tốt.', NULL, 'active', 0, 2100, '2025-08-20 00:32:37', '2025-08-20 00:32:37', NULL),
(11, 13, 4, 'Tai nghe Xiaomi Buds 4 Pro', 'tai-nghe-xiaomi-buds-4-pro', 'simple', 'Chống ồn chủ động, pin 38h.', 'Tai nghe true wireless giá rẻ, chất lượng tốt.', NULL, 'active', 0, 700, '2025-08-20 00:32:37', '2025-08-20 00:32:37', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `product_all_images`
--

CREATE TABLE `product_all_images` (
  `id` bigint UNSIGNED NOT NULL,
  `product_id` bigint UNSIGNED NOT NULL,
  `image_path` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `sort_order` int NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `product_comments`
--

CREATE TABLE `product_comments` (
  `id` bigint UNSIGNED NOT NULL,
  `product_id` bigint UNSIGNED NOT NULL,
  `user_id` bigint UNSIGNED NOT NULL,
  `content` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `rating` tinyint UNSIGNED DEFAULT NULL,
  `status` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'pending',
  `parent_id` bigint UNSIGNED DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `product_comments`
--

INSERT INTO `product_comments` (`id`, `product_id`, `user_id`, `content`, `rating`, `status`, `parent_id`, `deleted_at`, `created_at`, `updated_at`) VALUES
(1, 5, 4, 'Bình luận mẫu số 1 cho sản phẩm.', 5, 'approved', NULL, NULL, '2025-08-07 00:32:37', '2025-08-20 00:32:37'),
(2, 2, 13, 'Bình luận mẫu số 2 cho sản phẩm.', 3, 'approved', NULL, NULL, '2025-07-21 00:32:37', '2025-08-20 00:32:37'),
(3, 2, 6, 'Bình luận mẫu số 3 cho sản phẩm.', 5, 'approved', NULL, NULL, '2025-08-07 00:32:37', '2025-08-20 00:32:37'),
(4, 3, 13, 'Bình luận mẫu số 4 cho sản phẩm.', 3, 'approved', NULL, NULL, '2025-07-31 00:32:37', '2025-08-20 00:32:37'),
(5, 3, 9, 'Bình luận mẫu số 5 cho sản phẩm.', 4, 'approved', NULL, NULL, '2025-07-24 00:32:37', '2025-08-20 00:32:37'),
(6, 6, 7, 'Bình luận mẫu số 6 cho sản phẩm.', 5, 'approved', NULL, NULL, '2025-07-31 00:32:37', '2025-08-20 00:32:37'),
(7, 9, 9, 'Bình luận mẫu số 7 cho sản phẩm.', 3, 'approved', NULL, NULL, '2025-08-13 00:32:37', '2025-08-20 00:32:37'),
(8, 8, 5, 'Bình luận mẫu số 8 cho sản phẩm.', 3, 'approved', NULL, NULL, '2025-07-23 00:32:37', '2025-08-20 00:32:37'),
(9, 8, 5, 'Bình luận mẫu số 9 cho sản phẩm.', 4, 'approved', NULL, NULL, '2025-08-18 00:32:37', '2025-08-20 00:32:37'),
(10, 10, 4, 'Bình luận mẫu số 10 cho sản phẩm.', 3, 'approved', NULL, NULL, '2025-07-29 00:32:37', '2025-08-20 00:32:37'),
(11, 2, 13, 'Trả lời cho bình luận 1', NULL, 'approved', 1, NULL, '2025-08-14 00:32:37', '2025-08-20 00:32:37'),
(12, 2, 7, 'Trả lời cho bình luận 2', NULL, 'approved', 2, NULL, '2025-08-13 00:32:37', '2025-08-20 00:32:37'),
(13, 3, 7, 'Trả lời cho bình luận 3', NULL, 'approved', 3, NULL, '2025-08-19 00:32:37', '2025-08-20 00:32:37');

-- --------------------------------------------------------

--
-- Table structure for table `product_variants`
--

CREATE TABLE `product_variants` (
  `id` bigint UNSIGNED NOT NULL,
  `product_id` bigint UNSIGNED NOT NULL,
  `sku` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `price` bigint NOT NULL DEFAULT '0',
  `sale_price` bigint DEFAULT NULL,
  `image` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `weight` decimal(8,2) DEFAULT NULL,
  `length` decimal(8,2) DEFAULT NULL,
  `width` decimal(8,2) DEFAULT NULL,
  `height` decimal(8,2) DEFAULT NULL,
  `stock` int UNSIGNED NOT NULL DEFAULT '0',
  `low_stock_amount` int UNSIGNED DEFAULT NULL COMMENT 'Ngưỡng cảnh báo tồn kho',
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `product_variants`
--

INSERT INTO `product_variants` (`id`, `product_id`, `sku`, `price`, `sale_price`, `image`, `weight`, `length`, `width`, `height`, `stock`, `low_stock_amount`, `is_active`, `created_at`, `updated_at`, `deleted_at`) VALUES
(1, 1, 'DT-XYZ-DO-8G', 25990000, NULL, NULL, NULL, NULL, NULL, NULL, 50, NULL, 1, '2025-08-20 00:32:37', '2025-08-20 00:32:37', NULL),
(2, 1, 'DT-XYZ-XANH-16G', 28990000, NULL, NULL, NULL, NULL, NULL, NULL, 45, NULL, 1, '2025-08-20 00:32:37', '2025-08-20 00:32:37', NULL),
(3, 2, 'ROG-G16-8G', 52000000, NULL, NULL, NULL, NULL, NULL, NULL, 25, NULL, 1, '2025-08-20 00:32:37', '2025-08-20 00:32:37', NULL),
(4, 2, 'ROG-G16-16G', 58500000, NULL, NULL, NULL, NULL, NULL, NULL, 15, NULL, 1, '2025-08-20 00:32:37', '2025-08-20 00:32:37', NULL),
(5, 3, 'IP-SE-2024', 12490000, NULL, NULL, NULL, NULL, NULL, NULL, 400, NULL, 1, '2025-08-20 00:32:37', '2025-08-20 00:32:37', NULL),
(6, 4, 'AS-ZEN14-OLED', 26490000, NULL, NULL, NULL, NULL, NULL, NULL, 80, NULL, 1, '2025-08-20 00:32:37', '2025-08-20 00:32:37', NULL),
(7, 5, 'IPAD-M2-128GB', 21990000, NULL, NULL, NULL, NULL, NULL, NULL, 100, NULL, 1, '2025-08-20 00:32:37', '2025-08-20 00:32:37', NULL),
(8, 5, 'IPAD-M2-256GB', 24990000, NULL, NULL, NULL, NULL, NULL, NULL, 100, NULL, 1, '2025-08-20 00:32:37', '2025-08-20 00:32:37', NULL),
(9, 6, 'MBP-M3-256GB', 45990000, NULL, NULL, NULL, NULL, NULL, NULL, 100, NULL, 1, '2025-08-20 00:32:37', '2025-08-20 00:32:37', NULL),
(10, 6, 'MBP-M3-512GB', 52990000, NULL, NULL, NULL, NULL, NULL, NULL, 100, NULL, 1, '2025-08-20 00:32:37', '2025-08-20 00:32:37', NULL),
(11, 7, 'SGS24U', 33990000, NULL, NULL, NULL, NULL, NULL, NULL, 100, NULL, 1, '2025-08-20 00:32:37', '2025-08-20 00:32:37', NULL),
(12, 8, 'SONY-XM5', 8490000, NULL, NULL, NULL, NULL, NULL, NULL, 100, NULL, 1, '2025-08-20 00:32:37', '2025-08-20 00:32:37', NULL),
(13, 9, 'TAB-S9U-256GB', 27990000, NULL, NULL, NULL, NULL, NULL, NULL, 100, NULL, 1, '2025-08-20 00:32:37', '2025-08-20 00:32:37', NULL),
(14, 9, 'TAB-S9U-512GB', 31990000, NULL, NULL, NULL, NULL, NULL, NULL, 100, NULL, 1, '2025-08-20 00:32:37', '2025-08-20 00:32:37', NULL),
(15, 10, 'RN13PRO', 8990000, NULL, NULL, NULL, NULL, NULL, NULL, 100, NULL, 1, '2025-08-20 00:32:37', '2025-08-20 00:32:37', NULL),
(16, 11, 'BUDS4PRO', 2490000, NULL, NULL, NULL, NULL, NULL, NULL, 100, NULL, 1, '2025-08-20 00:32:37', '2025-08-20 00:32:37', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `product_variant_attribute_values`
--

CREATE TABLE `product_variant_attribute_values` (
  `id` bigint UNSIGNED NOT NULL,
  `product_variant_id` bigint UNSIGNED NOT NULL,
  `attribute_value_id` bigint UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `product_variant_attribute_values`
--

INSERT INTO `product_variant_attribute_values` (`id`, `product_variant_id`, `attribute_value_id`, `created_at`, `updated_at`) VALUES
(1, 1, 1, NULL, NULL),
(2, 1, 6, NULL, NULL),
(3, 2, 2, NULL, NULL),
(4, 2, 7, NULL, NULL),
(5, 3, 6, NULL, NULL),
(6, 4, 7, NULL, NULL),
(7, 7, 10, NULL, NULL),
(8, 8, 11, NULL, NULL),
(9, 9, 11, NULL, NULL),
(10, 10, 12, NULL, NULL),
(11, 13, 11, NULL, NULL),
(12, 14, 12, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `promotions`
--

CREATE TABLE `promotions` (
  `id` bigint UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `slug` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text COLLATE utf8mb4_unicode_ci,
  `type` enum('all','category','product') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'all',
  `discount_type` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `discount_value` decimal(15,2) DEFAULT NULL,
  `start_date` datetime DEFAULT NULL,
  `end_date` datetime DEFAULT NULL,
  `status` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `promotions`
--

INSERT INTO `promotions` (`id`, `name`, `slug`, `description`, `type`, `discount_type`, `discount_value`, `start_date`, `end_date`, `status`, `created_at`, `updated_at`, `deleted_at`) VALUES
(1, 'Back to School', 'back-to-school', 'Khuyến mãi mùa tựu trường cho học sinh, sinh viên.', 'product', 'percent', '10.00', '2025-08-15 07:32:00', '2025-08-30 07:32:00', 1, '2025-08-20 00:32:37', '2025-08-20 00:33:19', NULL),
(2, 'Black Friday', 'black-friday-68a517a5cebe6', 'Giảm giá sốc dịp Black Friday cho toàn bộ sản phẩm.', 'all', 'amount', '50000.00', '2025-09-19 07:32:37', '2025-09-24 07:32:37', 1, '2025-08-20 00:32:37', '2025-08-20 00:32:37', NULL),
(3, 'Tết Sale', 'tet-sale-68a517a5cf23d', 'Chương trình khuyến mãi lớn dịp Tết Nguyên Đán.', 'all', 'percent', '15.00', '2025-11-20 07:32:37', '2025-11-30 07:32:37', 0, '2025-08-20 00:32:37', '2025-08-20 00:32:37', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `promotion_category`
--

CREATE TABLE `promotion_category` (
  `id` bigint UNSIGNED NOT NULL,
  `promotion_id` bigint UNSIGNED NOT NULL,
  `category_id` bigint UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `promotion_category`
--

INSERT INTO `promotion_category` (`id`, `promotion_id`, `category_id`, `created_at`, `updated_at`) VALUES
(2, 2, 2, '2025-08-20 00:32:37', '2025-08-20 00:32:37');

-- --------------------------------------------------------

--
-- Table structure for table `promotion_product`
--

CREATE TABLE `promotion_product` (
  `id` bigint UNSIGNED NOT NULL,
  `promotion_id` bigint UNSIGNED NOT NULL,
  `product_id` bigint UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `promotion_product`
--

INSERT INTO `promotion_product` (`id`, `promotion_id`, `product_id`, `created_at`, `updated_at`) VALUES
(3, 2, 3, '2025-08-20 00:32:37', '2025-08-20 00:32:37'),
(4, 1, 10, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `roles`
--

CREATE TABLE `roles` (
  `id` bigint UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `slug` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `roles`
--

INSERT INTO `roles` (`id`, `name`, `slug`, `deleted_at`, `created_at`, `updated_at`) VALUES
(1, 'admin', 'admin', NULL, '2025-08-20 00:32:37', '2025-08-20 00:32:37'),
(2, 'staff', 'staff', NULL, '2025-08-20 00:32:37', '2025-08-20 00:32:37'),
(3, 'user', 'user', NULL, '2025-08-20 00:32:37', '2025-08-20 00:32:37');

-- --------------------------------------------------------

--
-- Table structure for table `role_has_permissions`
--

CREATE TABLE `role_has_permissions` (
  `permission_id` bigint UNSIGNED NOT NULL,
  `role_id` bigint UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `sessions`
--

CREATE TABLE `sessions` (
  `id` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `user_id` bigint UNSIGNED DEFAULT NULL,
  `ip_address` varchar(45) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `user_agent` text COLLATE utf8mb4_unicode_ci,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `last_activity` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `sessions`
--

INSERT INTO `sessions` (`id`, `user_id`, `ip_address`, `user_agent`, `payload`, `last_activity`) VALUES
('6uEkkHlPgKR2jfxHfzUJv7bDPNZxFzkc65JmpSp2', 13, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/138.0.0.0 Safari/537.36', 'YTo2OntzOjY6Il90b2tlbiI7czo0MDoia0VQbW1MM2tjN0gwaVFEcjVOUXhOTDNxZzJqS3I0TGFGVVdQOGFjMiI7czozOiJ1cmwiO2E6MTp7czo4OiJpbnRlbmRlZCI7czo0NToiaHR0cDovLzEyNy4wLjAuMTo4MDAwL2FkbWluL3Byb21vdGlvbnMvMS9lZGl0Ijt9czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6Mjc6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMC9jYXJ0cyI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fXM6NTA6ImxvZ2luX3dlYl81OWJhMzZhZGRjMmIyZjk0MDE1ODBmMDE0YzdmNThlYTRlMzA5ODlkIjtpOjEzO3M6NjoiYnV5bm93IjthOjM6e3M6MTA6InByb2R1Y3RfaWQiO2k6MjtzOjg6InF1YW50aXR5IjtpOjE7czoxMDoidmFyaWFudF9pZCI7aTozO319', 1755650121);

-- --------------------------------------------------------

--
-- Table structure for table `settings`
--

CREATE TABLE `settings` (
  `id` bigint UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `shipping_methods`
--

CREATE TABLE `shipping_methods` (
  `id` bigint UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `shipping_methods`
--

INSERT INTO `shipping_methods` (`id`, `name`, `description`, `created_at`, `updated_at`) VALUES
(1, 'Giao hàng tận nơi', NULL, '2025-08-20 00:32:37', '2025-08-20 00:32:37'),
(2, 'Nhận hàng tại cửa hàng', 'Explicabo architecto placeat quibusdam distinctio et.', '2025-08-20 00:32:37', '2025-08-20 00:32:37'),
(3, 'Phương thức giao hàng #3', 'Occaecati repellendus quis officiis cumque sed.', '2025-08-20 00:32:37', '2025-08-20 00:32:37'),
(4, 'Phương thức giao hàng #4', NULL, '2025-08-20 00:32:37', '2025-08-20 00:32:37'),
(5, 'Phương thức giao hàng #5', 'Sed et illo accusamus facilis accusantium.', '2025-08-20 00:32:37', '2025-08-20 00:32:37'),
(6, 'Phương thức giao hàng #6', NULL, '2025-08-20 00:32:37', '2025-08-20 00:32:37'),
(7, 'Phương thức giao hàng #7', 'Et aliquid sit iste veniam ut.', '2025-08-20 00:32:37', '2025-08-20 00:32:37'),
(8, 'Phương thức giao hàng #8', 'Architecto praesentium voluptas sint quis iste doloribus.', '2025-08-20 00:32:37', '2025-08-20 00:32:37'),
(9, 'Phương thức giao hàng #9', NULL, '2025-08-20 00:32:37', '2025-08-20 00:32:37'),
(10, 'Phương thức giao hàng #10', 'Minus neque magnam temporibus accusamus.', '2025-08-20 00:32:37', '2025-08-20 00:32:37'),
(11, 'Phương thức giao hàng #11', NULL, '2025-08-20 00:32:37', '2025-08-20 00:32:37'),
(12, 'Phương thức giao hàng #12', NULL, '2025-08-20 00:32:37', '2025-08-20 00:32:37'),
(13, 'Phương thức giao hàng #13', 'Quis veniam optio tempora dolores sed exercitationem.', '2025-08-20 00:32:37', '2025-08-20 00:32:37'),
(14, 'Phương thức giao hàng #14', 'Suscipit qui totam hic blanditiis et quo tempore cum.', '2025-08-20 00:32:37', '2025-08-20 00:32:37'),
(15, 'Phương thức giao hàng #15', NULL, '2025-08-20 00:32:37', '2025-08-20 00:32:37'),
(16, 'Phương thức giao hàng #16', 'Quia veritatis commodi iste et hic sequi ipsa.', '2025-08-20 00:32:37', '2025-08-20 00:32:37'),
(17, 'Phương thức giao hàng #17', NULL, '2025-08-20 00:32:37', '2025-08-20 00:32:37'),
(18, 'Phương thức giao hàng #18', 'Voluptas velit porro enim sit nam vero ut.', '2025-08-20 00:32:37', '2025-08-20 00:32:37'),
(19, 'Phương thức giao hàng #19', NULL, '2025-08-20 00:32:37', '2025-08-20 00:32:37'),
(20, 'Phương thức giao hàng #20', NULL, '2025-08-20 00:32:37', '2025-08-20 00:32:37');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` bigint UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `google_id` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `facebook_id` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `password` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `remember_token` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `phone_number` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `image_profile` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `birthday` date DEFAULT NULL,
  `gender` varchar(10) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `google_id`, `facebook_id`, `password`, `remember_token`, `phone_number`, `image_profile`, `is_active`, `birthday`, `gender`, `created_at`, `updated_at`, `email_verified_at`, `deleted_at`) VALUES
(1, 'John Doe', 'johndoe@example.com', NULL, NULL, '$2y$12$MagbixUofDodm1gzYkxPeuobHrH513uphZGJjWfxerfO.YQrZTrwq', NULL, '123456789', 'profile1.jpg', 1, '1990-01-01', 'male', '2025-08-20 00:32:34', '2025-08-20 00:32:34', NULL, NULL),
(2, 'Jane Smith', 'jane@example.com', NULL, NULL, '$2y$12$lyybjs9dmgL7axLaT8gqe.Q4uoodqQAJ7UWm5orL35GUAKqdh4E7m', NULL, '987654321', 'profile2.jpg', 1, '1992-05-15', 'female', '2025-08-20 00:32:35', '2025-08-20 00:32:35', NULL, NULL),
(3, 'Nguyen Van A', 'nguyenvana@example.com', NULL, NULL, '$2y$12$Gt2EcyvehzxeAIzR6wkiGuFtwiZ0P0Hm4SXlDZMVL8rfOhvHJuUGS', NULL, '0901111111', 'profile3.jpg', 1, '1995-03-10', 'male', '2025-08-20 00:32:35', '2025-08-20 00:32:35', NULL, NULL),
(4, 'Tran Thi B', 'tranthib@example.com', NULL, NULL, '$2y$12$CRWYuIPFozuXaiIbHupoIOwBFICs/xRf3FPBNcEv1v3nUIkXATPbK', NULL, '0902222222', 'profile4.jpg', 1, '1996-07-21', 'female', '2025-08-20 00:32:35', '2025-08-20 00:32:35', NULL, NULL),
(5, 'Le Van C', 'levanc@example.com', NULL, NULL, '$2y$12$rwA/HfGY01OTSSzYVfy7mOt9DsEdKu0RcuvSG/4ETveo8Svn03PEa', NULL, '0903333333', 'profile5.jpg', 1, '1993-11-05', 'male', '2025-08-20 00:32:35', '2025-08-20 00:32:35', NULL, NULL),
(6, 'Pham Thi D', 'phamthid@example.com', NULL, NULL, '$2y$12$fn60ACBy6Rxe7xPJpAd1yuNqMdkmM1Vd3XwDRR1VA/sImrXXzIcka', NULL, '0904444444', 'profile6.jpg', 1, '1994-02-14', 'female', '2025-08-20 00:32:35', '2025-08-20 00:32:35', NULL, NULL),
(7, 'Hoang Van E', 'hoangvane@example.com', NULL, NULL, '$2y$12$xVpHW4ZIg19y.1V0dSdb2uS4Mi.fVHpjdFc4Bb1/BRegLlxvg24EC', NULL, '0905555555', 'profile7.jpg', 1, '1991-09-09', 'male', '2025-08-20 00:32:36', '2025-08-20 00:32:36', NULL, NULL),
(8, 'Vu Thi F', 'vuthif@example.com', NULL, NULL, '$2y$12$5ZwmaJ7TgD5buDC23BIWQ.Ql0tl2MD7pZf3.CydphX3ilUu8BGpsq', NULL, '0906666666', 'profile8.jpg', 1, '1997-12-12', 'female', '2025-08-20 00:32:36', '2025-08-20 00:32:36', NULL, NULL),
(9, 'Do Van G', 'dovang@example.com', NULL, NULL, '$2y$12$feQG9FcfAQA.0YjnBJZmzOSy.idBUahLanhZFCzRci6NGwjfXzIDC', NULL, '0907777777', 'profile9.jpg', 1, '1998-04-18', 'male', '2025-08-20 00:32:36', '2025-08-20 00:32:36', NULL, NULL),
(10, 'Bui Thi H', 'buithih@example.com', NULL, NULL, '$2y$12$T.RNE3ldHqNqrO0MblLVNeBcab0NkqxG4NszotZ.vIRAnbGz/Rinm', NULL, '0908888888', 'profile10.jpg', 1, '1999-06-25', 'female', '2025-08-20 00:32:36', '2025-08-20 00:32:36', NULL, NULL),
(11, 'Pham Van I', 'phamvani@example.com', NULL, NULL, '$2y$12$.iZD9YYHrXpyK1PtMOEbju2Pij1X6Rlux8fs2iuM7RnlUw3AkrV.m', NULL, '0909999999', 'profile11.jpg', 1, '1992-08-30', 'male', '2025-08-20 00:32:36', '2025-08-20 00:32:36', NULL, NULL),
(12, 'Nguyen Thi K', 'nguyenthik@example.com', NULL, NULL, '$2y$12$XxyFcx50V3tiZVs8W2dQ1e0pl8DHTpgPiATgPrRMWwNODMUC4M9.e', NULL, '0910000000', 'profile12.jpg', 1, '1993-10-11', 'female', '2025-08-20 00:32:37', '2025-08-20 00:32:37', NULL, NULL),
(13, 'Admin', 'admin@gmail.com', NULL, NULL, '$2y$12$GND2QmITvP7zsMFHyWrYm.0iCGPprZ5W2ggAZ6GpJ5oz06GA72t1K', NULL, '0999999999', 'admin.jpg', 1, '1990-01-01', 'male', '2025-08-20 00:32:37', '2025-08-20 00:32:37', NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `user_addresses`
--

CREATE TABLE `user_addresses` (
  `id` bigint UNSIGNED NOT NULL,
  `user_id` bigint UNSIGNED NOT NULL,
  `recipient_name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `phone` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `address` text COLLATE utf8mb4_unicode_ci,
  `address_line` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `ward` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `district` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `city` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `is_default` tinyint(1) NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `user_addresses`
--

INSERT INTO `user_addresses` (`id`, `user_id`, `recipient_name`, `phone`, `address`, `address_line`, `ward`, `district`, `city`, `is_default`, `created_at`, `updated_at`, `deleted_at`) VALUES
(1, 13, NULL, NULL, NULL, '44961 Langworth Shoal', 'Quan Hoa', 'Ba Đình', 'Hà Nội', 1, '2025-08-20 00:32:37', '2025-08-20 00:32:37', NULL),
(2, 13, NULL, NULL, NULL, '510 Deshaun Villages Apt. 055', 'Vĩnh Phúc', 'Hoàn Kiếm', 'Hà Nội', 0, '2025-08-20 00:32:37', '2025-08-20 00:32:37', NULL),
(3, 13, NULL, NULL, NULL, '85037 Otilia Drives', 'Dịch Vọng', 'Tây Hồ', 'Hà Nội', 0, '2025-08-20 00:32:37', '2025-08-20 00:32:37', NULL),
(4, 10, NULL, NULL, NULL, '832 Cecilia Junctions Suite 984', 'Ô Chợ Dừa', 'Tây Hồ', 'Hà Nội', 1, '2025-08-20 00:32:37', '2025-08-20 00:32:37', NULL),
(5, 10, NULL, NULL, NULL, '6520 Gina Union', 'Phúc Xá', 'Cầu Giấy', 'Hà Nội', 0, '2025-08-20 00:32:37', '2025-08-20 00:32:37', NULL),
(6, 10, NULL, NULL, NULL, '783 Bauch Summit', 'Thổ Quan', 'Cầu Giấy', 'Hà Nội', 0, '2025-08-20 00:32:37', '2025-08-20 00:32:37', NULL),
(7, 9, NULL, NULL, NULL, '17558 Remington Plains Apt. 630', 'Yên Sở', 'Ba Đình', 'Hà Nội', 1, '2025-08-20 00:32:37', '2025-08-20 00:32:37', NULL),
(8, 9, NULL, NULL, NULL, '6794 Ondricka Pine Apt. 418', 'Nghĩa Tân', 'Đống Đa', 'Hà Nội', 0, '2025-08-20 00:32:37', '2025-08-20 00:32:37', NULL),
(9, 9, NULL, NULL, NULL, '1425 Betty Islands', 'Phúc Tân', 'Hoàn Kiếm', 'Hà Nội', 0, '2025-08-20 00:32:37', '2025-08-20 00:32:37', NULL),
(10, 7, NULL, NULL, NULL, '368 Trantow Street Suite 660', 'Hoàng Văn Thụ', 'Long Biên', 'Hà Nội', 1, '2025-08-20 00:32:37', '2025-08-20 00:32:37', NULL),
(11, 7, NULL, NULL, NULL, '61073 Kylie Track', 'Dịch Vọng', 'Hoàng Mai', 'Hà Nội', 0, '2025-08-20 00:32:37', '2025-08-20 00:32:37', NULL),
(12, 7, NULL, NULL, NULL, '91269 Reichel Station', 'Nghĩa Tân', 'Long Biên', 'Hà Nội', 0, '2025-08-20 00:32:37', '2025-08-20 00:32:37', NULL),
(13, 2, NULL, NULL, NULL, '350 Ryan Junctions', 'Trúc Bạch', 'Ba Đình', 'Hà Nội', 1, '2025-08-20 00:32:37', '2025-08-20 00:32:37', NULL),
(14, 2, NULL, NULL, NULL, '68461 Cummings Trail', 'Vĩnh Phúc', 'Long Biên', 'Hà Nội', 0, '2025-08-20 00:32:37', '2025-08-20 00:32:37', NULL),
(15, 2, NULL, NULL, NULL, '831 Williamson Garden Apt. 283', 'Trúc Bạch', 'Hoàng Mai', 'Hà Nội', 0, '2025-08-20 00:32:37', '2025-08-20 00:32:37', NULL),
(16, 1, NULL, NULL, NULL, '310 Columbus Run', 'Phúc Xá', 'Cầu Giấy', 'Hà Nội', 1, '2025-08-20 00:32:37', '2025-08-20 00:32:37', NULL),
(17, 1, NULL, NULL, NULL, '309 Elfrieda Light Apt. 300', 'Hàng Bài', 'Đống Đa', 'Hà Nội', 0, '2025-08-20 00:32:37', '2025-08-20 00:32:37', NULL),
(18, 1, NULL, NULL, NULL, '1374 Mackenzie Bridge Apt. 268', 'Láng Hạ', 'Long Biên', 'Hà Nội', 0, '2025-08-20 00:32:37', '2025-08-20 00:32:37', NULL),
(19, 5, NULL, NULL, NULL, '80097 Briana Underpass', 'Vĩnh Phúc', 'Long Biên', 'Hà Nội', 1, '2025-08-20 00:32:37', '2025-08-20 00:32:37', NULL),
(20, 5, NULL, NULL, NULL, '896 Weissnat Glen Suite 852', 'Đội Cấn', 'Long Biên', 'Hà Nội', 0, '2025-08-20 00:32:37', '2025-08-20 00:32:37', NULL),
(21, 5, NULL, NULL, NULL, '587 Dane Shores Suite 822', 'Phúc Xá', 'Long Biên', 'Hà Nội', 0, '2025-08-20 00:32:37', '2025-08-20 00:32:37', NULL),
(22, 12, NULL, NULL, NULL, '631 Daugherty Lakes', 'Yên Hòa', 'Ba Đình', 'Hà Nội', 1, '2025-08-20 00:32:37', '2025-08-20 00:32:37', NULL),
(23, 12, NULL, NULL, NULL, '37417 Hamill Streets', 'Yên Hòa', 'Thanh Xuân', 'Hà Nội', 0, '2025-08-20 00:32:37', '2025-08-20 00:32:37', NULL),
(24, 12, NULL, NULL, NULL, '4905 Meagan Isle', 'Phúc Xá', 'Hoàn Kiếm', 'Hà Nội', 0, '2025-08-20 00:32:37', '2025-08-20 00:32:37', NULL),
(25, 3, NULL, NULL, NULL, '6779 Herzog Highway Apt. 116', 'Đội Cấn', 'Ba Đình', 'Hà Nội', 1, '2025-08-20 00:32:37', '2025-08-20 00:32:37', NULL),
(26, 3, NULL, NULL, NULL, '9786 Ortiz Ville', 'Điện Biên', 'Hai Bà Trưng', 'Hà Nội', 0, '2025-08-20 00:32:37', '2025-08-20 00:32:37', NULL),
(27, 3, NULL, NULL, NULL, '222 Elinor Burg', 'Ô Chợ Dừa', 'Ba Đình', 'Hà Nội', 0, '2025-08-20 00:32:37', '2025-08-20 00:32:37', NULL),
(28, 6, NULL, NULL, NULL, '966 Missouri Gateway', 'Đội Cấn', 'Hoàng Mai', 'Hà Nội', 1, '2025-08-20 00:32:37', '2025-08-20 00:32:37', NULL),
(29, 6, NULL, NULL, NULL, '25144 Cristopher Flat Apt. 905', 'Chương Dương', 'Ba Đình', 'Hà Nội', 0, '2025-08-20 00:32:37', '2025-08-20 00:32:37', NULL),
(30, 6, NULL, NULL, NULL, '89107 Elnora Bypass Suite 316', 'Điện Biên', 'Cầu Giấy', 'Hà Nội', 0, '2025-08-20 00:32:37', '2025-08-20 00:32:37', NULL),
(31, 11, NULL, NULL, NULL, '50208 Alberto Row Suite 252', 'Phúc Xá', 'Hai Bà Trưng', 'Hà Nội', 1, '2025-08-20 00:32:37', '2025-08-20 00:32:37', NULL),
(32, 11, NULL, NULL, NULL, '66496 Arno Point Suite 514', 'Phúc Xá', 'Hoàng Mai', 'Hà Nội', 0, '2025-08-20 00:32:37', '2025-08-20 00:32:37', NULL),
(33, 11, NULL, NULL, NULL, '12389 Philip Parks', 'Giáp Bát', 'Cầu Giấy', 'Hà Nội', 0, '2025-08-20 00:32:37', '2025-08-20 00:32:37', NULL),
(34, 4, NULL, NULL, NULL, '65661 Betty Cliffs Apt. 378', 'Dịch Vọng', 'Long Biên', 'Hà Nội', 1, '2025-08-20 00:32:37', '2025-08-20 00:32:37', NULL),
(35, 4, NULL, NULL, NULL, '510 Fadel Roads', 'Thổ Quan', 'Cầu Giấy', 'Hà Nội', 0, '2025-08-20 00:32:37', '2025-08-20 00:32:37', NULL),
(36, 4, NULL, NULL, NULL, '2461 Mayert Circles Apt. 433', 'Điện Biên', 'Thanh Xuân', 'Hà Nội', 0, '2025-08-20 00:32:37', '2025-08-20 00:32:37', NULL),
(37, 8, NULL, NULL, NULL, '480 Kailyn Bridge Suite 990', 'Chương Dương', 'Hoàn Kiếm', 'Hà Nội', 1, '2025-08-20 00:32:37', '2025-08-20 00:32:37', NULL),
(38, 8, NULL, NULL, NULL, '5508 Watsica Street Apt. 628', 'Quan Hoa', 'Hai Bà Trưng', 'Hà Nội', 0, '2025-08-20 00:32:37', '2025-08-20 00:32:37', NULL),
(39, 8, NULL, NULL, NULL, '3546 Delia Hollow Suite 332', 'Điện Biên', 'Tây Hồ', 'Hà Nội', 0, '2025-08-20 00:32:37', '2025-08-20 00:32:37', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `user_notifications`
--

CREATE TABLE `user_notifications` (
  `id` bigint UNSIGNED NOT NULL,
  `user_id` bigint UNSIGNED NOT NULL,
  `notification_id` bigint UNSIGNED NOT NULL,
  `is_read` tinyint(1) NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `user_roles`
--

CREATE TABLE `user_roles` (
  `id` bigint UNSIGNED NOT NULL,
  `user_id` bigint UNSIGNED NOT NULL,
  `role_id` bigint UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `user_roles`
--

INSERT INTO `user_roles` (`id`, `user_id`, `role_id`, `created_at`, `updated_at`) VALUES
(1, 1, 1, NULL, NULL),
(2, 2, 2, NULL, NULL),
(3, 3, 3, NULL, NULL),
(4, 13, 1, NULL, NULL);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `attributes`
--
ALTER TABLE `attributes`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `attributes_slug_unique` (`slug`);

--
-- Indexes for table `attribute_values`
--
ALTER TABLE `attribute_values`
  ADD PRIMARY KEY (`id`),
  ADD KEY `attribute_values_attribute_id_foreign` (`attribute_id`);

--
-- Indexes for table `banners`
--
ALTER TABLE `banners`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `brands`
--
ALTER TABLE `brands`
  ADD PRIMARY KEY (`id`);

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
-- Indexes for table `carts`
--
ALTER TABLE `carts`
  ADD PRIMARY KEY (`id`),
  ADD KEY `carts_user_id_foreign` (`user_id`),
  ADD KEY `carts_product_id_foreign` (`product_id`),
  ADD KEY `carts_product_variant_id_foreign` (`variant_id`);

--
-- Indexes for table `categories`
--
ALTER TABLE `categories`
  ADD PRIMARY KEY (`id`),
  ADD KEY `categories_parent_id_foreign` (`parent_id`);

--
-- Indexes for table `contacts`
--
ALTER TABLE `contacts`
  ADD PRIMARY KEY (`id`),
  ADD KEY `contacts_user_id_foreign` (`user_id`),
  ADD KEY `contacts_handled_by_foreign` (`handled_by`);

--
-- Indexes for table `coupons`
--
ALTER TABLE `coupons`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `coupons_code_unique` (`code`),
  ADD KEY `coupons_promotion_id_foreign` (`promotion_id`);

--
-- Indexes for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`);

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
-- Indexes for table `logos`
--
ALTER TABLE `logos`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `mail_templates`
--
ALTER TABLE `mail_templates`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `model_has_permissions`
--
ALTER TABLE `model_has_permissions`
  ADD UNIQUE KEY `model_has_permissions_permission_id_model_id_model_type_unique` (`permission_id`,`model_id`,`model_type`);

--
-- Indexes for table `model_has_roles`
--
ALTER TABLE `model_has_roles`
  ADD UNIQUE KEY `model_has_roles_role_id_model_id_model_type_unique` (`role_id`,`model_id`,`model_type`);

--
-- Indexes for table `news`
--
ALTER TABLE `news`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `news_categories`
--
ALTER TABLE `news_categories`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `news_categories_slug_unique` (`slug`);

--
-- Indexes for table `news_comments`
--
ALTER TABLE `news_comments`
  ADD PRIMARY KEY (`id`),
  ADD KEY `news_comments_user_id_foreign` (`user_id`),
  ADD KEY `news_comments_news_id_foreign` (`news_id`),
  ADD KEY `news_comments_parent_id_foreign` (`parent_id`);

--
-- Indexes for table `notifications`
--
ALTER TABLE `notifications`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`id`),
  ADD KEY `orders_coupon_id_foreign` (`coupon_id`),
  ADD KEY `orders_shipping_method_id_foreign` (`shipping_method_id`),
  ADD KEY `orders_user_id_foreign` (`user_id`),
  ADD KEY `orders_address_id_foreign` (`address_id`),
  ADD KEY `orders_vnp_txn_ref_index` (`vnp_txn_ref`);

--
-- Indexes for table `order_items`
--
ALTER TABLE `order_items`
  ADD PRIMARY KEY (`id`),
  ADD KEY `order_items_order_id_foreign` (`order_id`),
  ADD KEY `order_items_variant_id_foreign` (`variant_id`),
  ADD KEY `order_items_product_id_foreign` (`product_id`);

--
-- Indexes for table `order_returns`
--
ALTER TABLE `order_returns`
  ADD PRIMARY KEY (`id`),
  ADD KEY `order_returns_order_id_foreign` (`order_id`);

--
-- Indexes for table `password_reset_tokens`
--
ALTER TABLE `password_reset_tokens`
  ADD PRIMARY KEY (`email`);

--
-- Indexes for table `permissions`
--
ALTER TABLE `permissions`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `permissions_name_unique` (`name`);

--
-- Indexes for table `permission_role`
--
ALTER TABLE `permission_role`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `permission_role_permission_id_role_id_unique` (`permission_id`,`role_id`),
  ADD KEY `permission_role_role_id_foreign` (`role_id`);

--
-- Indexes for table `personal_access_tokens`
--
ALTER TABLE `personal_access_tokens`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `personal_access_tokens_token_unique` (`token`),
  ADD KEY `personal_access_tokens_tokenable_type_tokenable_id_index` (`tokenable_type`,`tokenable_id`),
  ADD KEY `personal_access_tokens_expires_at_index` (`expires_at`);

--
-- Indexes for table `products`
--
ALTER TABLE `products`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `products_slug_unique` (`slug`),
  ADD KEY `products_category_id_foreign` (`category_id`),
  ADD KEY `products_brand_id_foreign` (`brand_id`);

--
-- Indexes for table `product_all_images`
--
ALTER TABLE `product_all_images`
  ADD PRIMARY KEY (`id`),
  ADD KEY `product_all_images_product_id_foreign` (`product_id`);

--
-- Indexes for table `product_comments`
--
ALTER TABLE `product_comments`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `product_variants`
--
ALTER TABLE `product_variants`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `product_variants_sku_unique` (`sku`),
  ADD KEY `product_variants_product_id_foreign` (`product_id`);

--
-- Indexes for table `product_variant_attribute_values`
--
ALTER TABLE `product_variant_attribute_values`
  ADD PRIMARY KEY (`id`),
  ADD KEY `product_variant_attribute_values_product_variant_id_foreign` (`product_variant_id`),
  ADD KEY `product_variant_attribute_values_attribute_value_id_foreign` (`attribute_value_id`);

--
-- Indexes for table `promotions`
--
ALTER TABLE `promotions`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `promotions_slug_unique` (`slug`);

--
-- Indexes for table `promotion_category`
--
ALTER TABLE `promotion_category`
  ADD PRIMARY KEY (`id`),
  ADD KEY `promotion_category_promotion_id_foreign` (`promotion_id`),
  ADD KEY `promotion_category_category_id_foreign` (`category_id`);

--
-- Indexes for table `promotion_product`
--
ALTER TABLE `promotion_product`
  ADD PRIMARY KEY (`id`),
  ADD KEY `promotion_product_promotion_id_foreign` (`promotion_id`),
  ADD KEY `promotion_product_product_id_foreign` (`product_id`);

--
-- Indexes for table `roles`
--
ALTER TABLE `roles`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `roles_name_unique` (`name`);

--
-- Indexes for table `role_has_permissions`
--
ALTER TABLE `role_has_permissions`
  ADD UNIQUE KEY `role_has_permissions_permission_id_role_id_unique` (`permission_id`,`role_id`),
  ADD KEY `role_has_permissions_role_id_foreign` (`role_id`);

--
-- Indexes for table `sessions`
--
ALTER TABLE `sessions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `sessions_user_id_index` (`user_id`),
  ADD KEY `sessions_last_activity_index` (`last_activity`);

--
-- Indexes for table `settings`
--
ALTER TABLE `settings`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `shipping_methods`
--
ALTER TABLE `shipping_methods`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `users_email_unique` (`email`);

--
-- Indexes for table `user_addresses`
--
ALTER TABLE `user_addresses`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_addresses_user_id_foreign` (`user_id`);

--
-- Indexes for table `user_notifications`
--
ALTER TABLE `user_notifications`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `user_notifications_user_id_notification_id_unique` (`user_id`,`notification_id`),
  ADD KEY `user_notifications_notification_id_foreign` (`notification_id`);

--
-- Indexes for table `user_roles`
--
ALTER TABLE `user_roles`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_roles_user_id_foreign` (`user_id`),
  ADD KEY `user_roles_role_id_foreign` (`role_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `attributes`
--
ALTER TABLE `attributes`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `attribute_values`
--
ALTER TABLE `attribute_values`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `banners`
--
ALTER TABLE `banners`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `brands`
--
ALTER TABLE `brands`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `carts`
--
ALTER TABLE `carts`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `categories`
--
ALTER TABLE `categories`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT for table `contacts`
--
ALTER TABLE `contacts`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `coupons`
--
ALTER TABLE `coupons`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `jobs`
--
ALTER TABLE `jobs`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `logos`
--
ALTER TABLE `logos`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `mail_templates`
--
ALTER TABLE `mail_templates`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=55;

--
-- AUTO_INCREMENT for table `news`
--
ALTER TABLE `news`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `news_categories`
--
ALTER TABLE `news_categories`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `news_comments`
--
ALTER TABLE `news_comments`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=60;

--
-- AUTO_INCREMENT for table `notifications`
--
ALTER TABLE `notifications`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `orders`
--
ALTER TABLE `orders`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- AUTO_INCREMENT for table `order_items`
--
ALTER TABLE `order_items`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- AUTO_INCREMENT for table `order_returns`
--
ALTER TABLE `order_returns`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- AUTO_INCREMENT for table `permissions`
--
ALTER TABLE `permissions`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `permission_role`
--
ALTER TABLE `permission_role`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `personal_access_tokens`
--
ALTER TABLE `personal_access_tokens`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `products`
--
ALTER TABLE `products`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `product_all_images`
--
ALTER TABLE `product_all_images`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `product_comments`
--
ALTER TABLE `product_comments`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT for table `product_variants`
--
ALTER TABLE `product_variants`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT for table `product_variant_attribute_values`
--
ALTER TABLE `product_variant_attribute_values`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `promotions`
--
ALTER TABLE `promotions`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `promotion_category`
--
ALTER TABLE `promotion_category`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `promotion_product`
--
ALTER TABLE `promotion_product`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `roles`
--
ALTER TABLE `roles`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `settings`
--
ALTER TABLE `settings`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `shipping_methods`
--
ALTER TABLE `shipping_methods`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT for table `user_addresses`
--
ALTER TABLE `user_addresses`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=40;

--
-- AUTO_INCREMENT for table `user_notifications`
--
ALTER TABLE `user_notifications`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `user_roles`
--
ALTER TABLE `user_roles`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `attribute_values`
--
ALTER TABLE `attribute_values`
  ADD CONSTRAINT `attribute_values_attribute_id_foreign` FOREIGN KEY (`attribute_id`) REFERENCES `attributes` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `carts`
--
ALTER TABLE `carts`
  ADD CONSTRAINT `carts_product_id_foreign` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `carts_product_variant_id_foreign` FOREIGN KEY (`variant_id`) REFERENCES `product_variants` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `carts_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `categories`
--
ALTER TABLE `categories`
  ADD CONSTRAINT `categories_parent_id_foreign` FOREIGN KEY (`parent_id`) REFERENCES `categories` (`id`);

--
-- Constraints for table `contacts`
--
ALTER TABLE `contacts`
  ADD CONSTRAINT `contacts_handled_by_foreign` FOREIGN KEY (`handled_by`) REFERENCES `users` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `contacts_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `coupons`
--
ALTER TABLE `coupons`
  ADD CONSTRAINT `coupons_promotion_id_foreign` FOREIGN KEY (`promotion_id`) REFERENCES `promotions` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `model_has_permissions`
--
ALTER TABLE `model_has_permissions`
  ADD CONSTRAINT `model_has_permissions_permission_id_foreign` FOREIGN KEY (`permission_id`) REFERENCES `permissions` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `model_has_roles`
--
ALTER TABLE `model_has_roles`
  ADD CONSTRAINT `model_has_roles_role_id_foreign` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `news_comments`
--
ALTER TABLE `news_comments`
  ADD CONSTRAINT `news_comments_news_id_foreign` FOREIGN KEY (`news_id`) REFERENCES `news` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `news_comments_parent_id_foreign` FOREIGN KEY (`parent_id`) REFERENCES `news_comments` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `news_comments_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `orders`
--
ALTER TABLE `orders`
  ADD CONSTRAINT `orders_address_id_foreign` FOREIGN KEY (`address_id`) REFERENCES `user_addresses` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `orders_coupon_id_foreign` FOREIGN KEY (`coupon_id`) REFERENCES `coupons` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `orders_shipping_method_id_foreign` FOREIGN KEY (`shipping_method_id`) REFERENCES `shipping_methods` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `orders_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `order_items`
--
ALTER TABLE `order_items`
  ADD CONSTRAINT `order_items_order_id_foreign` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `order_items_product_id_foreign` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `order_items_variant_id_foreign` FOREIGN KEY (`variant_id`) REFERENCES `product_variants` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `order_returns`
--
ALTER TABLE `order_returns`
  ADD CONSTRAINT `order_returns_order_id_foreign` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `permission_role`
--
ALTER TABLE `permission_role`
  ADD CONSTRAINT `permission_role_permission_id_foreign` FOREIGN KEY (`permission_id`) REFERENCES `permissions` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `permission_role_role_id_foreign` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `products`
--
ALTER TABLE `products`
  ADD CONSTRAINT `products_brand_id_foreign` FOREIGN KEY (`brand_id`) REFERENCES `brands` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `products_category_id_foreign` FOREIGN KEY (`category_id`) REFERENCES `categories` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `product_all_images`
--
ALTER TABLE `product_all_images`
  ADD CONSTRAINT `product_all_images_product_id_foreign` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `product_variants`
--
ALTER TABLE `product_variants`
  ADD CONSTRAINT `product_variants_product_id_foreign` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `product_variant_attribute_values`
--
ALTER TABLE `product_variant_attribute_values`
  ADD CONSTRAINT `product_variant_attribute_values_attribute_value_id_foreign` FOREIGN KEY (`attribute_value_id`) REFERENCES `attribute_values` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `product_variant_attribute_values_product_variant_id_foreign` FOREIGN KEY (`product_variant_id`) REFERENCES `product_variants` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `promotion_category`
--
ALTER TABLE `promotion_category`
  ADD CONSTRAINT `promotion_category_category_id_foreign` FOREIGN KEY (`category_id`) REFERENCES `categories` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `promotion_category_promotion_id_foreign` FOREIGN KEY (`promotion_id`) REFERENCES `promotions` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `promotion_product`
--
ALTER TABLE `promotion_product`
  ADD CONSTRAINT `promotion_product_product_id_foreign` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `promotion_product_promotion_id_foreign` FOREIGN KEY (`promotion_id`) REFERENCES `promotions` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `role_has_permissions`
--
ALTER TABLE `role_has_permissions`
  ADD CONSTRAINT `role_has_permissions_permission_id_foreign` FOREIGN KEY (`permission_id`) REFERENCES `permissions` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `role_has_permissions_role_id_foreign` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `user_addresses`
--
ALTER TABLE `user_addresses`
  ADD CONSTRAINT `user_addresses_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `user_notifications`
--
ALTER TABLE `user_notifications`
  ADD CONSTRAINT `user_notifications_notification_id_foreign` FOREIGN KEY (`notification_id`) REFERENCES `notifications` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `user_notifications_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `user_roles`
--
ALTER TABLE `user_roles`
  ADD CONSTRAINT `user_roles_role_id_foreign` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `user_roles_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
