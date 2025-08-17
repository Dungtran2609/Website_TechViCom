-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Aug 17, 2025 at 09:21 PM
-- Server version: 8.0.30
-- PHP Version: 8.2.20

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
(1, 'Màu sắc', 'mau-sac', 'color', NULL, NULL, '2025-08-17 21:06:09', '2025-08-17 21:06:09'),
(2, 'RAM', 'ram', 'text', NULL, NULL, '2025-08-17 21:06:09', '2025-08-17 21:06:09'),
(3, 'Bộ nhớ trong', 'bo-nho-trong', 'text', NULL, NULL, '2025-08-17 21:06:09', '2025-08-17 21:06:09');

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
(1, 1, 'uploads/banners/banner1.jpg', '2025-08-08 04:06:10', '2025-09-07 04:06:10', 'https://techvicom.vn/', '2025-08-17 21:06:10', '2025-08-17 21:06:10'),
(2, 2, 'uploads/banners/banner2.jpg', '2025-08-13 04:06:10', '2025-09-12 04:06:10', 'https://techvicom.vn/khuyen-mai', '2025-08-17 21:06:10', '2025-08-17 21:06:10'),
(3, 3, 'uploads/banners/banner3.jpg', '2025-08-17 04:06:10', '2025-09-17 04:06:10', NULL, '2025-08-17 21:06:10', '2025-08-17 21:06:10');

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
(1, 'Apple', 'brands/apple.png', 'apple', 'Chuyên các sản phẩm iPhone, MacBook, iPad.', 1, '2025-08-17 21:06:09', '2025-08-17 21:06:09', NULL),
(2, 'Samsung', 'brands/samsung.png', 'samsung', 'Thương hiệu điện thoại Android và thiết bị gia dụng.', 1, '2025-08-17 21:06:09', '2025-08-17 21:06:09', NULL),
(3, 'ASUS', 'brands/asus.png', 'asus', 'Chuyên laptop văn phòng, gaming, bo mạch chủ.', 1, '2025-08-17 21:06:09', '2025-08-17 21:06:09', NULL),
(4, 'Xiaomi', 'brands/xiaomi.png', 'xiaomi', 'Điện thoại thông minh và thiết bị IoT giá rẻ.', 1, '2025-08-17 21:06:09', '2025-08-17 21:06:09', NULL),
(5, 'Dell', 'brands/dell.png', 'dell', 'Laptop doanh nhân và máy chủ hiệu suất cao.', 1, '2025-08-17 21:06:09', '2025-08-17 21:06:09', NULL),
(6, 'HP', 'brands/hp.png', 'hp', 'Thương hiệu máy tính và thiết bị in ấn phổ biến.', 1, '2025-08-17 21:06:09', '2025-08-17 21:06:09', NULL),
(7, 'Lenovo', 'brands/lenovo.png', 'lenovo', 'Máy tính văn phòng, gaming và máy trạm.', 1, '2025-08-17 21:06:09', '2025-08-17 21:06:09', NULL),
(8, 'Sony', 'brands/sony.png', 'sony', 'Thiết bị giải trí, PlayStation và âm thanh cao cấp.', 1, '2025-08-17 21:06:09', '2025-08-17 21:06:09', NULL),
(9, 'MSI', 'brands/msi.png', 'msi', 'Chuyên laptop và linh kiện gaming cao cấp.', 1, '2025-08-17 21:06:09', '2025-08-17 21:06:09', NULL),
(10, 'Acer', 'brands/acer.png', 'acer', 'Laptop học sinh, sinh viên và văn phòng giá rẻ.', 1, '2025-08-17 21:06:09', '2025-08-17 21:06:09', NULL);

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

--
-- Dumping data for table `carts`
--

INSERT INTO `carts` (`id`, `user_id`, `product_id`, `variant_id`, `quantity`, `created_at`, `updated_at`) VALUES
(2, 13, 1, 2, 1, '2025-08-17 21:07:20', '2025-08-17 21:07:20');

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
(1, NULL, 'Laptop', 'laptop', NULL, 1, '2025-08-17 21:06:09', '2025-08-17 21:06:09', NULL),
(2, NULL, 'Điện thoại', 'dien-thoai', NULL, 1, '2025-08-17 21:06:09', '2025-08-17 21:06:09', NULL),
(3, NULL, 'Tablet', 'tablet', NULL, 1, '2025-08-17 21:06:09', '2025-08-17 21:06:09', NULL),
(4, NULL, 'Phụ kiện', 'phu-kien', NULL, 1, '2025-08-17 21:06:09', '2025-08-17 21:06:09', NULL),
(5, 1, 'Laptop Gaming', 'laptop-gaming', NULL, 1, '2025-08-17 21:06:09', '2025-08-17 21:06:09', NULL),
(6, 1, 'Laptop Văn phòng', 'laptop-van-phong', NULL, 1, '2025-08-17 21:06:09', '2025-08-17 21:06:09', NULL),
(7, 1, 'MacBook', 'macbook', NULL, 1, '2025-08-17 21:06:09', '2025-08-17 21:06:09', NULL),
(8, 2, 'iPhone', 'iphone', NULL, 1, '2025-08-17 21:06:09', '2025-08-17 21:06:09', NULL),
(9, 2, 'Samsung Galaxy', 'samsung-galaxy', NULL, 1, '2025-08-17 21:06:09', '2025-08-17 21:06:09', NULL),
(10, 2, 'Xiaomi', 'xiaomi', NULL, 1, '2025-08-17 21:06:09', '2025-08-17 21:06:09', NULL),
(11, 3, 'iPad', 'ipad', NULL, 1, '2025-08-17 21:06:09', '2025-08-17 21:06:09', NULL),
(12, 3, 'Samsung Tab', 'samsung-tab', NULL, 1, '2025-08-17 21:06:09', '2025-08-17 21:06:09', NULL),
(13, 4, 'Tai nghe', 'tai-nghe', NULL, 1, '2025-08-17 21:06:09', '2025-08-17 21:06:09', NULL),
(14, 4, 'Sạc và cáp', 'sac-va-cap', NULL, 1, '2025-08-17 21:06:09', '2025-08-17 21:06:09', NULL);

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
(1, 'Nguyễn Văn A', 'vana@example.com', '0909123456', 'Hỏi về sản phẩm', 'Cho tôi hỏi sản phẩm này còn hàng không?', 13, NULL, 'pending', NULL, NULL, 0, '2025-08-17 21:06:10', '2025-08-17 21:06:10'),
(2, 'Trần Thị B', 'thib@example.com', '0911222333', 'Thắc mắc giao hàng', 'Tôi muốn biết khi nào đơn hàng được giao.', 10, NULL, 'pending', NULL, NULL, 1, '2025-08-17 21:06:10', '2025-08-17 21:06:10'),
(3, 'Lê Văn C', 'vanc@example.com', '0922333444', 'Hủy đơn hàng', 'Tôi muốn hủy đơn hàng vừa đặt.', NULL, NULL, 'pending', NULL, NULL, 0, '2025-08-17 21:06:10', '2025-08-17 21:06:10'),
(4, 'Phạm Thị D', 'thid@example.com', '0933444555', 'Phản hồi dịch vụ', 'Dịch vụ chăm sóc khách hàng rất tốt.', NULL, NULL, 'pending', NULL, NULL, 1, '2025-08-17 21:06:10', '2025-08-17 21:06:10'),
(5, 'Đỗ Minh E', 'minhe@example.com', '0944555666', 'Đổi hàng', 'Tôi muốn đổi sản phẩm vì bị lỗi.', 9, NULL, 'pending', NULL, NULL, 0, '2025-08-17 21:06:10', '2025-08-17 21:06:10'),
(6, 'Hoàng Thị F', 'thif@example.com', '0955666777', 'Cần tư vấn', 'Bạn có thể tư vấn giúp tôi sản phẩm phù hợp?', NULL, NULL, 'pending', NULL, NULL, 1, '2025-08-17 21:06:10', '2025-08-17 21:06:10'),
(7, 'Ngô Văn G', 'vang@example.com', '0966777888', 'Góp ý', 'Website của bạn rất dễ sử dụng.', NULL, NULL, 'pending', NULL, NULL, 0, '2025-08-17 21:06:10', '2025-08-17 21:06:10'),
(8, 'Vũ Thị H', 'thih@example.com', '0977888999', 'Thanh toán', 'Tôi muốn đổi phương thức thanh toán.', 7, NULL, 'pending', NULL, NULL, 1, '2025-08-17 21:06:10', '2025-08-17 21:06:10'),
(9, 'Bùi Văn I', 'vani@example.com', '0988999000', 'Khuyến mãi', 'Cửa hàng hiện có chương trình khuyến mãi nào?', NULL, NULL, 'pending', NULL, NULL, 0, '2025-08-17 21:06:10', '2025-08-17 21:06:10'),
(10, 'Lý Thị K', 'thik@example.com', '0999000111', 'Đặt hàng lỗi', 'Tôi không thể đặt hàng trên website.', 2, NULL, 'pending', NULL, NULL, 1, '2025-08-17 21:06:10', '2025-08-17 21:06:10');

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
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `coupons`
--

INSERT INTO `coupons` (`id`, `code`, `discount_type`, `value`, `max_discount_amount`, `min_order_value`, `max_order_value`, `max_usage_per_user`, `start_date`, `end_date`, `status`, `created_at`, `updated_at`, `deleted_at`) VALUES
(1, 'DISCOUNT10', 'percent', 10, 100000, 500000, 5000000, 5, '2025-08-08', '2025-09-18', 1, NULL, NULL, NULL),
(2, 'BIGSALE10', 'percent', 10, 20000000, 50000000, 1000000000, 1, '2025-08-17', '2025-09-18', 1, NULL, NULL, NULL),
(3, 'VIPFIXED', 'fixed', 50000000, NULL, 200000000, 2000000000, 1, '2025-08-17', '2025-09-18', 1, NULL, NULL, NULL),
(4, 'MEGAVIP', 'percent', 50, 100000000, 500000000, 5000000000, 1, '2025-08-17', '2025-09-18', 1, NULL, NULL, NULL),
(5, 'SALE50', 'percent', 50, 100000, 200000, 1000000, 2, '2025-08-17', '2025-09-17', 1, NULL, NULL, NULL),
(6, 'SALE100', 'percent', 50, 100000, 200000, 1000000, 2, '2025-08-17', '2025-09-17', 1, NULL, NULL, NULL);

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
(45, '2025_08_17_165602_add_vnp_columns_to_orders_table', 1);

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
(1, 1, 'Giảm giá 50% cho đơn hàng đầu tiên', 'Hãy nhanh tay nhận ưu đãi 50% khi mua hàng lần đầu tiên tại cửa hàng chúng tôi.', 'uploads/news/default.jpg', 13, 'published', '2025-08-17 21:06:10', '2025-08-17 21:06:10', '2025-08-17 21:06:10', NULL),
(2, 1, 'Mua 1 tặng 1 cuối tuần', 'Chương trình mua 1 tặng 1 áp dụng từ thứ 6 đến chủ nhật hàng tuần.', 'uploads/news/default.jpg', 13, 'published', '2025-08-17 21:06:10', '2025-08-17 21:06:10', '2025-08-17 21:06:10', NULL),
(3, 2, 'iPhone 15 chính thức ra mắt', 'Apple đã giới thiệu iPhone 15 với nhiều cải tiến về hiệu năng và camera.', 'uploads/news/default.jpg', 10, 'published', '2025-08-17 21:06:10', '2025-08-17 21:06:10', '2025-08-17 21:06:10', NULL),
(4, 2, 'Samsung trình làng Galaxy Z Flip6', 'Samsung tiếp tục đẩy mạnh phân khúc điện thoại gập với Galaxy Z Flip6.', 'uploads/news/default.jpg', 7, 'published', '2025-08-17 21:06:10', '2025-08-17 21:06:10', '2025-08-17 21:06:10', NULL),
(5, 3, 'Hướng dẫn sử dụng máy ép chậm', 'Bài viết sẽ giúp bạn hiểu rõ cách sử dụng máy ép chậm để giữ nguyên dưỡng chất.', 'uploads/news/default.jpg', 7, 'published', '2025-08-17 21:06:10', '2025-08-17 21:06:10', '2025-08-17 21:06:10', NULL),
(6, 3, 'Cách bảo quản tai nghe không dây', 'Giữ gìn tai nghe đúng cách giúp kéo dài tuổi thọ và giữ âm thanh tốt.', 'uploads/news/default.jpg', 7, 'published', '2025-08-17 21:06:10', '2025-08-17 21:06:10', '2025-08-17 21:06:10', NULL),
(7, 4, 'Đánh giá laptop Asus Zenbook 14', 'Asus Zenbook 14 nổi bật với thiết kế mỏng nhẹ, pin trâu và hiệu năng ổn định.', 'uploads/news/default.jpg', 13, 'published', '2025-08-17 21:06:10', '2025-08-17 21:06:10', '2025-08-17 21:06:10', NULL),
(8, 4, 'So sánh Xiaomi Redmi Note 12 và Realme 11', 'Cùng so sánh hai sản phẩm tầm trung hot nhất hiện nay.', 'uploads/news/default.jpg', 13, 'published', '2025-08-17 21:06:10', '2025-08-17 21:06:10', '2025-08-17 21:06:10', NULL);

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
(1, 'Khuyến mãi', 'khuyen-mai', '2025-08-17 21:06:10', '2025-08-17 21:06:10'),
(2, 'Tin tức công nghệ', 'tin-tuc-cong-nghe', '2025-08-17 21:06:10', '2025-08-17 21:06:10'),
(3, 'Hướng dẫn sử dụng sản phẩm', 'huong-dan-su-dung-san-pham', '2025-08-17 21:06:10', '2025-08-17 21:06:10'),
(4, 'Đánh giá sản phẩm', 'danh-gia-san-pham', '2025-08-17 21:06:10', '2025-08-17 21:06:10'),
(5, 'Mẹo vặt công nghệ', 'meo-vat-cong-nghe', '2025-08-17 21:06:10', '2025-08-17 21:06:10'),
(6, 'Sự kiện và ra mắt sản phẩm', 'su-kien-ra-mat-san-pham', '2025-08-17 21:06:10', '2025-08-17 21:06:10'),
(7, 'Review cửa hàng', 'review-cua-hang', '2025-08-17 21:06:10', '2025-08-17 21:06:10'),
(8, 'Chăm sóc khách hàng', 'cham-soc-khach-hang', '2025-08-17 21:06:10', '2025-08-17 21:06:10'),
(9, 'Mua sắm trực tuyến', 'mua-sam-truc-tuyen', '2025-08-17 21:06:10', '2025-08-17 21:06:10'),
(10, 'Sản phẩm mới', 'san-pham-moi', '2025-08-17 21:06:10', '2025-08-17 21:06:10');

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
(1, 13, 1, NULL, 'Thông tin chi tiết và rõ ràng.', 1, 5, '2025-08-17 21:06:10', '2025-08-17 21:06:10'),
(2, 13, 1, 1, '↪ Rất mong có thêm bài viết tương tự.', 1, 1, '2025-08-17 21:06:10', '2025-08-17 21:06:10'),
(3, 9, 1, NULL, 'Bài viết rất hữu ích, cảm ơn bạn!', 1, 10, '2025-08-17 21:06:10', '2025-08-17 21:06:10'),
(4, 7, 1, NULL, 'Rất thích nội dung kiểu này.', 1, 1, '2025-08-17 21:06:10', '2025-08-17 21:06:10'),
(5, 10, 1, 4, '↪ Rất mong có thêm bài viết tương tự.', 1, 5, '2025-08-17 21:06:10', '2025-08-17 21:06:10'),
(6, 10, 2, NULL, 'Bài viết hay nhưng nên bổ sung thêm ví dụ.', 1, 7, '2025-08-17 21:06:10', '2025-08-17 21:06:10'),
(7, 9, 2, NULL, 'Rất thích nội dung kiểu này.', 1, 1, '2025-08-17 21:06:10', '2025-08-17 21:06:10'),
(8, 7, 2, 7, '↪ Rất thích nội dung kiểu này.', 0, 4, '2025-08-17 21:06:10', '2025-08-17 21:06:10'),
(9, 13, 2, 7, '↪ Rất mong có thêm bài viết tương tự.', 1, 5, '2025-08-17 21:06:10', '2025-08-17 21:06:10'),
(10, 7, 2, NULL, 'Có thể giải thích thêm phần này được không?', 1, 0, '2025-08-17 21:06:10', '2025-08-17 21:06:10'),
(11, 2, 3, NULL, 'Rất mong có thêm bài viết tương tự.', 1, 10, '2025-08-17 21:06:10', '2025-08-17 21:06:10'),
(12, 7, 3, NULL, 'Có thể giải thích thêm phần này được không?', 1, 9, '2025-08-17 21:06:10', '2025-08-17 21:06:10'),
(13, 2, 3, 12, '↪ Sản phẩm này mình đã dùng, rất ok.', 0, 2, '2025-08-17 21:06:10', '2025-08-17 21:06:10'),
(14, 10, 3, 12, '↪ Sản phẩm này mình đã dùng, rất ok.', 0, 3, '2025-08-17 21:06:10', '2025-08-17 21:06:10'),
(15, 9, 3, NULL, 'Cảm ơn bạn đã chia sẻ!', 1, 8, '2025-08-17 21:06:10', '2025-08-17 21:06:10'),
(16, 7, 3, NULL, 'Sản phẩm này mình đã dùng, rất ok.', 0, 8, '2025-08-17 21:06:10', '2025-08-17 21:06:10'),
(17, 7, 3, 16, '↪ Tôi đã áp dụng và thấy hiệu quả ngay.', 0, 1, '2025-08-17 21:06:10', '2025-08-17 21:06:10'),
(18, 13, 3, 16, '↪ Mình sẽ giới thiệu bài viết này cho bạn bè.', 0, 0, '2025-08-17 21:06:10', '2025-08-17 21:06:10'),
(19, 7, 4, NULL, 'Thông tin chi tiết và rõ ràng.', 1, 7, '2025-08-17 21:06:10', '2025-08-17 21:06:10'),
(20, 13, 4, NULL, 'Cảm ơn bạn đã chia sẻ!', 1, 1, '2025-08-17 21:06:10', '2025-08-17 21:06:10'),
(21, 2, 4, 20, '↪ Tôi đã áp dụng và thấy hiệu quả ngay.', 1, 4, '2025-08-17 21:06:10', '2025-08-17 21:06:10'),
(22, 7, 4, NULL, 'Thông tin chi tiết và rõ ràng.', 0, 4, '2025-08-17 21:06:10', '2025-08-17 21:06:10'),
(23, 9, 4, 22, '↪ Cảm ơn bạn đã chia sẻ!', 1, 0, '2025-08-17 21:06:10', '2025-08-17 21:06:10'),
(24, 9, 4, NULL, 'Rất mong có thêm bài viết tương tự.', 0, 8, '2025-08-17 21:06:10', '2025-08-17 21:06:10'),
(25, 2, 4, 24, '↪ Cảm ơn bạn đã chia sẻ!', 1, 4, '2025-08-17 21:06:10', '2025-08-17 21:06:10'),
(26, 10, 4, NULL, 'Thông tin chi tiết và rõ ràng.', 1, 3, '2025-08-17 21:06:10', '2025-08-17 21:06:10'),
(27, 7, 4, 26, '↪ Rất mong có thêm bài viết tương tự.', 0, 0, '2025-08-17 21:06:10', '2025-08-17 21:06:10'),
(28, 9, 4, 26, '↪ Rất thích nội dung kiểu này.', 1, 2, '2025-08-17 21:06:10', '2025-08-17 21:06:10'),
(29, 13, 5, NULL, 'Sản phẩm này mình đã dùng, rất ok.', 1, 9, '2025-08-17 21:06:10', '2025-08-17 21:06:10'),
(30, 2, 5, 29, '↪ Bài viết hay nhưng nên bổ sung thêm ví dụ.', 0, 3, '2025-08-17 21:06:10', '2025-08-17 21:06:10'),
(31, 9, 5, NULL, 'Rất mong có thêm bài viết tương tự.', 1, 9, '2025-08-17 21:06:10', '2025-08-17 21:06:10'),
(32, 7, 5, 31, '↪ Bài viết hay nhưng nên bổ sung thêm ví dụ.', 1, 4, '2025-08-17 21:06:10', '2025-08-17 21:06:10'),
(33, 10, 5, 31, '↪ Có thể giải thích thêm phần này được không?', 1, 4, '2025-08-17 21:06:10', '2025-08-17 21:06:10'),
(34, 9, 5, NULL, 'Bài viết hay nhưng nên bổ sung thêm ví dụ.', 0, 5, '2025-08-17 21:06:10', '2025-08-17 21:06:10'),
(35, 10, 5, NULL, 'Cảm ơn bạn đã chia sẻ!', 0, 0, '2025-08-17 21:06:10', '2025-08-17 21:06:10'),
(36, 13, 5, NULL, 'Sản phẩm này mình đã dùng, rất ok.', 0, 10, '2025-08-17 21:06:10', '2025-08-17 21:06:10'),
(37, 2, 5, 36, '↪ Mình sẽ giới thiệu bài viết này cho bạn bè.', 0, 3, '2025-08-17 21:06:10', '2025-08-17 21:06:10'),
(38, 9, 5, 36, '↪ Tôi đã áp dụng và thấy hiệu quả ngay.', 0, 4, '2025-08-17 21:06:10', '2025-08-17 21:06:10'),
(39, 9, 6, NULL, 'Tôi đã áp dụng và thấy hiệu quả ngay.', 1, 1, '2025-08-17 21:06:10', '2025-08-17 21:06:10'),
(40, 9, 6, 39, '↪ Tôi đã áp dụng và thấy hiệu quả ngay.', 1, 5, '2025-08-17 21:06:10', '2025-08-17 21:06:10'),
(41, 9, 6, NULL, 'Có thể giải thích thêm phần này được không?', 0, 8, '2025-08-17 21:06:10', '2025-08-17 21:06:10'),
(42, 7, 6, 41, '↪ Bài viết rất hữu ích, cảm ơn bạn!', 0, 3, '2025-08-17 21:06:10', '2025-08-17 21:06:10'),
(43, 2, 6, 41, '↪ Thông tin chi tiết và rõ ràng.', 0, 1, '2025-08-17 21:06:10', '2025-08-17 21:06:10'),
(44, 2, 6, NULL, 'Sản phẩm này mình đã dùng, rất ok.', 0, 5, '2025-08-17 21:06:10', '2025-08-17 21:06:10'),
(45, 7, 7, NULL, 'Mình sẽ giới thiệu bài viết này cho bạn bè.', 0, 0, '2025-08-17 21:06:10', '2025-08-17 21:06:10'),
(46, 2, 7, NULL, 'Cảm ơn bạn đã chia sẻ!', 0, 8, '2025-08-17 21:06:10', '2025-08-17 21:06:10'),
(47, 10, 7, NULL, 'Cảm ơn bạn đã chia sẻ!', 0, 6, '2025-08-17 21:06:10', '2025-08-17 21:06:10'),
(48, 2, 7, 47, '↪ Thông tin chi tiết và rõ ràng.', 1, 0, '2025-08-17 21:06:10', '2025-08-17 21:06:10'),
(49, 13, 7, NULL, 'Rất mong có thêm bài viết tương tự.', 0, 4, '2025-08-17 21:06:10', '2025-08-17 21:06:10'),
(50, 13, 7, NULL, 'Tôi đã áp dụng và thấy hiệu quả ngay.', 0, 3, '2025-08-17 21:06:10', '2025-08-17 21:06:10'),
(51, 2, 8, NULL, 'Bài viết hay nhưng nên bổ sung thêm ví dụ.', 0, 0, '2025-08-17 21:06:10', '2025-08-17 21:06:10'),
(52, 9, 8, NULL, 'Bài viết rất hữu ích, cảm ơn bạn!', 0, 7, '2025-08-17 21:06:10', '2025-08-17 21:06:10'),
(53, 9, 8, 52, '↪ Cảm ơn bạn đã chia sẻ!', 1, 2, '2025-08-17 21:06:10', '2025-08-17 21:06:10'),
(54, 2, 8, NULL, 'Sản phẩm này mình đã dùng, rất ok.', 0, 3, '2025-08-17 21:06:10', '2025-08-17 21:06:10'),
(55, 9, 8, 54, '↪ Có thể giải thích thêm phần này được không?', 0, 2, '2025-08-17 21:06:10', '2025-08-17 21:06:10'),
(56, 2, 8, 54, '↪ Tôi đã áp dụng và thấy hiệu quả ngay.', 0, 3, '2025-08-17 21:06:10', '2025-08-17 21:06:10'),
(57, 13, 8, NULL, 'Sản phẩm này mình đã dùng, rất ok.', 1, 1, '2025-08-17 21:06:10', '2025-08-17 21:06:10');

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
(1, 11, 6, NULL, NULL, NULL, 'credit_card', 4, 'FV4QKETX', '26.43', '14', '441', '428', 'pending', 'pending', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Dr. Brady Collins II', '681.237.0615', NULL, '791 Kunde Plaza Apt. 417\nMarcville, NV 49779', '2025-07-08 00:02:38', '2025-05-26 08:34:50', '2025-08-17 21:06:10', NULL, NULL),
(2, 11, 3, NULL, NULL, NULL, 'credit_card', NULL, NULL, '44.34', '17', '432', '404', 'pending', 'pending', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Adolphus Boehm I', '+1-402-820-7463', NULL, '27386 Wendy Mill Apt. 428\nLake Estella, LA 16529-6978', '2025-08-12 09:45:51', '2025-07-31 22:08:54', '2025-08-17 21:06:10', NULL, NULL),
(3, 3, 1, NULL, NULL, NULL, 'bank_transfer', NULL, NULL, '37.00', '18', '371', '352', 'pending', 'pending', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Justina Barton', '1-903-572-3136', NULL, '7884 Marge Bridge\nNorth Rodolfo, DC 19744', '2025-08-02 14:57:39', '2025-07-23 15:50:24', '2025-08-17 21:06:10', 16, NULL),
(4, 7, 36, NULL, NULL, NULL, 'bank_transfer', NULL, NULL, '25.66', '17', '280', '271', 'pending', 'pending', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Alivia Smith', '551-387-4400', NULL, '8279 Alene Ports\nRusselport, TN 40873', '2025-05-23 12:07:18', '2025-03-14 09:43:21', '2025-08-17 21:06:10', NULL, NULL),
(5, 7, 25, NULL, NULL, NULL, 'paypal', NULL, NULL, '45.07', '6', '165', '126', 'pending', 'pending', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Dr. Skye Lueilwitz', '814.623.0726', NULL, '285 Hazle Freeway\nJaclynland, FL 77718-2181', '2025-06-15 06:47:30', '2025-05-24 18:34:24', '2025-08-17 21:06:10', NULL, NULL),
(6, 7, 25, NULL, NULL, NULL, 'paypal', NULL, NULL, '11.92', '14', '73', '75', 'pending', 'pending', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Prof. Winston Mann III', '(470) 674-2531', NULL, '377 Lloyd Station\nNew Erick, WI 03776-3162', '2025-05-07 05:06:37', '2025-03-26 05:19:24', '2025-08-17 21:06:10', 9, NULL),
(7, 7, 39, NULL, NULL, NULL, 'credit_card', NULL, NULL, '27.98', '16', '364', '352', 'pending', 'pending', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Sarina Runolfsson', '520-334-4608', NULL, '384 Barrows Drive Suite 127\nSherwoodview, IN 04242-4112', NULL, '2025-03-03 14:03:09', '2025-08-17 21:06:10', NULL, NULL),
(8, 3, 1, NULL, NULL, NULL, 'bank_transfer', NULL, NULL, '20.24', '16', '115', '111', 'pending', 'pending', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Anabelle Hermiston', '(808) 503-6439', NULL, '3210 McLaughlin Mill\nWest Declan, WV 81206', '2025-08-17 16:43:53', '2025-08-17 10:04:52', '2025-08-17 21:06:10', 19, NULL),
(9, 3, 2, NULL, NULL, NULL, 'bank_transfer', NULL, NULL, '45.16', '10', '176', '141', 'pending', 'pending', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Julia Quitzon I', '(352) 844-1084', NULL, '7968 Larkin Crest\nThadshire, ND 35673-2295', '2025-07-27 00:01:02', '2025-07-19 06:49:13', '2025-08-17 21:06:10', 14, NULL),
(10, 2, 18, NULL, NULL, NULL, 'paypal', 2, '1RE11G97', '27.43', '17', '495', '485', 'pending', 'pending', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Kim Kuhlman', '310.345.4860', NULL, '1445 Abbey Flat\nEast Malindaton, CT 97799', NULL, '2025-06-26 17:03:20', '2025-08-17 21:06:10', NULL, '2025-07-03 23:58:44'),
(11, 2, 18, NULL, NULL, NULL, 'credit_card', NULL, NULL, '3.16', '11', '113', '121', 'pending', 'pending', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Kiarra Beatty', '+1-667-970-4776', NULL, '569 Olga Plaza\nLynchfurt, IL 73440-6931', NULL, '2025-08-12 09:59:53', '2025-08-17 21:06:10', NULL, NULL),
(12, 13, 39, NULL, NULL, NULL, 'paypal', 6, 'WPVFYL4P', '16.06', '6', '105', '95', 'pending', 'pending', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Aryanna Conroy', '1-832-388-8661', NULL, '34402 Orn Divide\nLake Aryanna, NE 34590-0420', '2025-06-18 20:12:29', '2025-06-05 02:41:43', '2025-08-17 21:06:10', NULL, NULL),
(13, 5, 21, NULL, NULL, NULL, 'credit_card', NULL, NULL, '8.40', '17', '144', '153', 'pending', 'pending', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Royal Cruickshank', '+1 (951) 620-6856', NULL, '505 Goldner Streets Suite 303\nSouth Allison, UT 31892-5174', '2025-06-26 20:34:35', '2025-05-24 21:26:47', '2025-08-17 21:06:10', 3, NULL),
(14, 10, 15, NULL, NULL, NULL, 'bank_transfer', NULL, NULL, '46.37', '17', '423', '393', 'pending', 'pending', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Alexis Spencer', '(747) 415-1139', NULL, '558 Hegmann Bridge Suite 128\nBrownberg, AL 16578-0251', '2025-03-23 03:28:54', '2025-03-16 05:50:52', '2025-08-17 21:06:10', 11, NULL),
(15, 9, 39, NULL, NULL, NULL, 'credit_card', NULL, NULL, '39.80', '11', '299', '271', 'pending', 'pending', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Ambrose Mante', '(980) 586-1436', NULL, '4336 Kreiger Estates\nDouglasbury, AK 59821-7563', '2025-06-27 01:53:46', '2025-05-07 08:12:28', '2025-08-17 21:06:10', NULL, NULL),
(16, 12, 17, NULL, NULL, NULL, 'bank_transfer', NULL, NULL, '41.59', '8', '154', '120', 'pending', 'pending', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Annetta Jacobson', '(938) 651-7172', NULL, '119 Stracke Estate Apt. 417\nEast Paris, IN 14205-1143', '2025-07-17 07:03:08', '2025-07-03 07:27:23', '2025-08-17 21:06:10', NULL, NULL),
(17, 10, 5, NULL, NULL, NULL, 'paypal', NULL, NULL, '30.06', '15', '264', '249', 'pending', 'pending', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Mrs. Annabelle Hartmann', '806-754-9039', NULL, '793 Klocko Glens Suite 104\nJanessahaven, IL 14229-1782', NULL, '2025-07-27 00:57:15', '2025-08-17 21:06:10', NULL, NULL),
(18, 2, 8, NULL, NULL, NULL, 'paypal', NULL, NULL, '21.46', '17', '73', '68', 'pending', 'pending', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Dr. Geraldine Kessler', '1-657-283-9944', NULL, '40785 Americo Terrace\nSouth Claudieburgh, MN 43922', NULL, '2025-07-13 02:45:02', '2025-08-17 21:06:10', 2, NULL),
(19, 8, 1, NULL, NULL, NULL, 'paypal', NULL, NULL, '10.13', '18', '70', '77', 'pending', 'pending', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Beatrice Koss', '1-719-599-0016', NULL, '6738 Daniel Grove\nWest Kurt, LA 03834', NULL, '2025-04-29 11:11:42', '2025-08-17 21:06:10', NULL, NULL),
(20, 6, 39, NULL, NULL, NULL, 'bank_transfer', 5, 'AGVQF8OD', '30.38', '10', '228', '208', 'pending', 'pending', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Dr. Adriana Daugherty DVM', '234.415.4313', NULL, '24407 Emard Ports\nLeifmouth, MS 60894', NULL, '2025-06-16 06:05:57', '2025-08-17 21:06:10', NULL, NULL),
(21, 13, NULL, NULL, NULL, NULL, 'bank_transfer', NULL, NULL, '0.00', '0', '28990000', '28990000', 'cancelled', 'cancelled', 'https://sandbox.vnpayment.vn/paymentv2/vpcpay.html?vnp_Amount=2899000000&vnp_Bill_Address=2894+Layla+Square+Apt.+588%2C+T%C3%A2n+Mai%2C+Thanh+Xu%C3%A2n%2C+H%C3%A0+N%E1%BB%99i&vnp_Bill_City=Hanoi&vnp_Bill_Country=VN&vnp_Bill_FirstName=Admin&vnp_Bill_Mobile=0999999999&vnp_Command=pay&vnp_CreateDate=20250818040702&vnp_CurrCode=VND&vnp_ExpireDate=20250818042202&vnp_IpAddr=127.0.0.1&vnp_Locale=vn&vnp_OrderInfo=Thanh+toan+don+hang+%2321&vnp_OrderType=other&vnp_ReturnUrl=http%3A%2F%2F127.0.0.1%3A8000%2Fvnpay%2Freturn&vnp_SecureHash=d62e7834f765295617c67a921b152d5ba85047b296c6ea2e074f48eade266dbbda7e78a0a9b7fb5858b88ae4617c62cc4f560b0ad3a2423876b0b6325ad7948c&vnp_SecureHashType=HmacSHA512&vnp_TmnCode=2WZSC2P3&vnp_TxnRef=VNP-21-20250818040702-3406&vnp_Version=2.1.0', 'VNP-21-20250818040702-3406', 2899000000, NULL, NULL, NULL, NULL, 'Admin', '0999999999', NULL, '2894 Layla Square Apt. 588, Tân Mai, Thanh Xuân, Hà Nội', NULL, '2025-08-17 21:07:02', '2025-08-17 21:07:06', NULL, NULL),
(22, 13, NULL, NULL, NULL, NULL, 'bank_transfer', NULL, NULL, '0.00', '0', '28990000', '28990000', 'cancelled', 'cancelled', 'https://sandbox.vnpayment.vn/paymentv2/vpcpay.html?vnp_Amount=2899000000&vnp_Bill_Address=2894+Layla+Square+Apt.+588%2C+T%C3%A2n+Mai%2C+Thanh+Xu%C3%A2n%2C+H%C3%A0+N%E1%BB%99i&vnp_Bill_City=Hanoi&vnp_Bill_Country=VN&vnp_Bill_FirstName=Admin&vnp_Bill_Mobile=0999999999&vnp_Command=pay&vnp_CreateDate=20250818040716&vnp_CurrCode=VND&vnp_ExpireDate=20250818042216&vnp_IpAddr=127.0.0.1&vnp_Locale=vn&vnp_OrderInfo=Thanh+toan+don+hang+%2322&vnp_OrderType=other&vnp_ReturnUrl=http%3A%2F%2F127.0.0.1%3A8000%2Fvnpay%2Freturn&vnp_SecureHash=93e5a78993a93bb161e0bcfc90b4b429b4dc8ec4ac31304a10ae29baa19f191f47a3e1f310dcef72dafb709c49f3b961704d92fbd65e93155a1799a24737ba62&vnp_SecureHashType=HmacSHA512&vnp_TmnCode=2WZSC2P3&vnp_TxnRef=VNP-22-20250818040716-1796&vnp_Version=2.1.0', 'VNP-22-20250818040716-1796', 2899000000, NULL, NULL, NULL, NULL, 'Admin', '0999999999', NULL, '2894 Layla Square Apt. 588, Tân Mai, Thanh Xuân, Hà Nội', NULL, '2025-08-17 21:07:16', '2025-08-17 21:07:20', NULL, NULL),
(23, 13, NULL, NULL, NULL, NULL, 'cod', NULL, NULL, '0.00', '0', '28990000', '28990000', 'pending', 'pending', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Admin', '0999999999', NULL, '2894 Layla Square Apt. 588, Tân Mai, Thanh Xuân, Hà Nội', NULL, '2025-08-17 21:16:27', '2025-08-17 21:16:27', NULL, NULL),
(24, 13, NULL, NULL, NULL, NULL, 'cod', NULL, NULL, '0.00', '50000', '1000000', '1050000', 'pending', 'pending', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Admin', '0999999999', NULL, '2894 Layla Square Apt. 588, Tân Mai, Thanh Xuân, Hà Nội', NULL, '2025-08-17 21:17:53', '2025-08-17 21:17:53', NULL, NULL);

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
(1, 16, 3, 4, 'Laptop Asus Zenbook 14 OLED', NULL, 5, '110.60', '553.00', '2025-08-17 21:06:10', '2025-08-17 21:06:10'),
(2, 17, 9, 6, 'MacBook Pro M3 14inch', NULL, 3, '20.63', '61.89', '2025-08-17 21:06:10', '2025-08-17 21:06:10'),
(3, 4, 11, 4, 'Laptop Asus Zenbook 14 OLED', NULL, 4, '164.92', '659.68', '2025-08-17 21:06:10', '2025-08-17 21:06:10'),
(4, 7, 7, 2, 'Laptop Gaming ROG Zephyrus G16', NULL, 5, '189.17', '945.85', '2025-08-17 21:06:10', '2025-08-17 21:06:10'),
(5, 1, 9, 8, 'Tai nghe Sony WH-1000XM5', NULL, 3, '136.50', '409.50', '2025-08-17 21:06:10', '2025-08-17 21:06:10'),
(6, 1, 10, 5, 'iPad Pro M2 11inch', NULL, 3, '121.63', '364.89', '2025-08-17 21:06:10', '2025-08-17 21:06:10'),
(7, 18, 1, 11, 'Tai nghe Xiaomi Buds 4 Pro', NULL, 2, '173.15', '346.30', '2025-08-17 21:06:10', '2025-08-17 21:06:10'),
(8, 15, 12, 10, 'Xiaomi Redmi Note 13 Pro', NULL, 5, '21.27', '106.35', '2025-08-17 21:06:10', '2025-08-17 21:06:10'),
(9, 5, 1, 6, 'MacBook Pro M3 14inch', NULL, 4, '55.80', '223.20', '2025-08-17 21:06:10', '2025-08-17 21:06:10'),
(10, 7, 11, 3, 'iPhone SE 2024', NULL, 4, '74.31', '297.24', '2025-08-17 21:06:10', '2025-08-17 21:06:10'),
(11, 14, 14, 6, 'MacBook Pro M3 14inch', NULL, 1, '125.31', '125.31', '2025-08-17 21:06:10', '2025-08-17 21:06:10'),
(12, 16, 4, 5, 'iPad Pro M2 11inch', NULL, 2, '132.14', '264.28', '2025-08-17 21:06:10', '2025-08-17 21:06:10'),
(13, 15, 9, 2, 'Laptop Gaming ROG Zephyrus G16', NULL, 2, '45.05', '90.10', '2025-08-17 21:06:10', '2025-08-17 21:06:10'),
(14, 8, 15, 7, 'Samsung Galaxy S24 Ultra', NULL, 4, '101.38', '405.52', '2025-08-17 21:06:10', '2025-08-17 21:06:10'),
(15, 11, 10, 4, 'Laptop Asus Zenbook 14 OLED', NULL, 1, '151.49', '151.49', '2025-08-17 21:06:10', '2025-08-17 21:06:10'),
(16, 9, 1, 6, 'MacBook Pro M3 14inch', NULL, 2, '160.38', '320.76', '2025-08-17 21:06:10', '2025-08-17 21:06:10'),
(17, 6, 5, 10, 'Xiaomi Redmi Note 13 Pro', NULL, 4, '187.95', '751.80', '2025-08-17 21:06:10', '2025-08-17 21:06:10'),
(18, 7, 6, 3, 'iPhone SE 2024', NULL, 1, '68.06', '68.06', '2025-08-17 21:06:10', '2025-08-17 21:06:10'),
(19, 10, 14, 8, 'Tai nghe Sony WH-1000XM5', NULL, 4, '108.49', '433.96', '2025-08-17 21:06:10', '2025-08-17 21:06:10'),
(20, 15, 2, 2, 'Laptop Gaming ROG Zephyrus G16', NULL, 5, '123.01', '615.05', '2025-08-17 21:06:10', '2025-08-17 21:06:10'),
(21, 21, 2, 1, 'Điện thoại Flagship XYZ 2025', 'client_css/images/placeholder.svg', 1, '28990000.00', '28990000.00', '2025-08-17 21:07:02', '2025-08-17 21:07:02'),
(22, 22, 2, 1, 'Điện thoại Flagship XYZ 2025', 'client_css/images/placeholder.svg', 1, '28990000.00', '28990000.00', '2025-08-17 21:07:16', '2025-08-17 21:07:16'),
(23, 23, 2, 1, 'Điện thoại Flagship XYZ 2025', 'storage/products/variants/YNwaEuMgi9WTJyb82qWL0khCvMSUvf4cawAvkEvD.jpg', 1, '28990000.00', '28990000.00', '2025-08-17 21:16:27', '2025-08-17 21:16:27'),
(24, 24, 1, 1, 'Điện thoại Flagship XYZ 2025', 'storage/products/variants/3Erm0Ih2eiSR6ghhuE1S13X4beb9nf1r9VJHKfeO.jpg', 1, '1000000.00', '1000000.00', '2025-08-17 21:17:53', '2025-08-17 21:17:53');

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
(1, 3, 'Khách thay đổi ý định', 'approved', 'cancel', '2025-07-22 03:51:51', '2025-08-08 19:21:32', 'Nihil eos et ullam ratione.', 'Dolor aliquid aut tempora ab distinctio id natus.', '2025-08-17 21:06:10', '2025-08-17 21:06:10'),
(2, 15, 'Không cần nữa', 'rejected', 'cancel', '2025-07-21 14:04:23', '2025-08-17 09:59:29', 'Quia omnis itaque a ducimus qui qui.', 'Officia adipisci cupiditate at dolore.', '2025-08-17 21:06:10', '2025-08-17 21:06:10'),
(3, 13, 'Khách thay đổi ý định', 'pending', 'cancel', '2025-08-08 00:08:32', NULL, NULL, 'Et fugit vel et aut velit.', '2025-08-17 21:06:10', '2025-08-17 21:06:10'),
(4, 20, NULL, 'rejected', 'cancel', '2025-07-27 06:24:28', NULL, NULL, NULL, '2025-08-17 21:06:10', '2025-08-17 21:06:10'),
(5, 6, 'Không đúng mô tả', 'rejected', 'cancel', '2025-07-30 01:59:04', '2025-08-03 03:36:45', 'Ducimus rerum autem aut commodi.', NULL, '2025-08-17 21:06:10', '2025-08-17 21:06:10'),
(6, 20, NULL, 'approved', 'cancel', '2025-08-15 13:19:13', '2025-08-16 03:52:42', 'Sapiente molestias maxime ipsa temporibus omnis omnis dolorem ut.', 'Voluptatem accusamus sunt ut quis dolores sit omnis.', '2025-08-17 21:06:10', '2025-08-17 21:06:10'),
(7, 4, 'Không đúng mô tả', 'approved', 'cancel', '2025-07-30 03:51:51', NULL, 'Sint animi enim inventore.', 'Nobis quia molestiae qui omnis.', '2025-08-17 21:06:10', '2025-08-17 21:06:10'),
(8, 17, 'Khách thay đổi ý định', 'rejected', 'return', '2025-08-06 11:43:10', '2025-08-11 06:09:57', 'Quos excepturi sunt reprehenderit ea aut omnis necessitatibus.', 'Voluptas quis earum veritatis modi in.', '2025-08-17 21:06:10', '2025-08-17 21:06:10'),
(9, 5, 'Khách thay đổi ý định', 'pending', 'return', '2025-07-24 03:55:35', NULL, 'Laboriosam distinctio et laudantium fugit et.', NULL, '2025-08-17 21:06:10', '2025-08-17 21:06:10'),
(10, 19, 'Giao nhầm hàng', 'approved', 'cancel', '2025-08-04 21:59:47', '2025-08-08 15:02:58', 'Laudantium sapiente rem non ea voluptas explicabo omnis.', 'Nesciunt non sunt corrupti odit illo sunt.', '2025-08-17 21:06:10', '2025-08-17 21:06:10'),
(11, 9, NULL, 'approved', 'return', '2025-07-25 11:38:51', NULL, 'Et exercitationem reprehenderit quasi laudantium ratione accusantium.', NULL, '2025-08-17 21:06:10', '2025-08-17 21:06:10'),
(12, 13, NULL, 'approved', 'return', '2025-08-07 22:35:30', NULL, NULL, 'Itaque veniam in in eum hic excepturi sit.', '2025-08-17 21:06:10', '2025-08-17 21:06:10'),
(13, 8, 'Khách thay đổi ý định', 'approved', 'return', '2025-07-30 11:38:13', NULL, 'Commodi natus ea dolores saepe dolorum eaque.', NULL, '2025-08-17 21:06:10', '2025-08-17 21:06:10'),
(14, 8, 'Không đúng mô tả', 'pending', 'cancel', '2025-08-10 16:48:07', NULL, NULL, NULL, '2025-08-17 21:06:10', '2025-08-17 21:06:10'),
(15, 8, NULL, 'pending', 'return', '2025-08-08 14:55:54', NULL, NULL, NULL, '2025-08-17 21:06:10', '2025-08-17 21:06:10'),
(16, 12, 'Sản phẩm lỗi', 'approved', 'cancel', '2025-08-13 04:17:00', '2025-08-17 10:22:37', 'Sapiente voluptas dolorum dolore cum ab aspernatur suscipit.', 'Ut aut ut dolor deleniti ex nulla placeat.', '2025-08-17 21:06:10', '2025-08-17 21:06:10'),
(17, 17, 'Giao nhầm hàng', 'pending', 'cancel', '2025-08-01 10:41:22', '2025-08-05 20:16:17', NULL, NULL, '2025-08-17 21:06:10', '2025-08-17 21:06:10'),
(18, 4, 'Không đúng mô tả', 'pending', 'return', '2025-08-01 02:11:37', '2025-08-03 09:23:39', NULL, NULL, '2025-08-17 21:06:10', '2025-08-17 21:06:10'),
(19, 12, NULL, 'pending', 'return', '2025-08-03 13:32:53', NULL, 'Facilis et et accusamus.', 'Esse et aspernatur autem enim molestiae deleniti.', '2025-08-17 21:06:10', '2025-08-17 21:06:10'),
(20, 7, NULL, 'approved', 'cancel', '2025-07-20 14:06:01', NULL, NULL, 'Velit et mollitia natus voluptates earum consectetur.', '2025-08-17 21:06:10', '2025-08-17 21:06:10');

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
(1, 'view_users', 'Xem danh sách người dùng', '2025-08-17 21:06:09', '2025-08-17 21:06:09', NULL),
(2, 'edit_users', 'Chỉnh sửa người dùng', '2025-08-17 21:06:09', '2025-08-17 21:06:09', NULL),
(3, 'delete_users', 'Xoá người dùng', '2025-08-17 21:06:09', '2025-08-17 21:06:09', NULL),
(4, 'manage_roles', 'Quản lý vai trò', '2025-08-17 21:06:09', '2025-08-17 21:06:09', NULL),
(5, 'manage_content', 'Quản lý nội dung', '2025-08-17 21:06:09', '2025-08-17 21:06:09', NULL),
(6, 'manage_coupons', 'Quản lý mã giảm giá', '2025-08-17 21:06:09', '2025-08-17 21:06:09', NULL);

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
(1, 3, 1, '2025-08-17 21:06:09', '2025-08-17 21:06:09'),
(2, 2, 1, '2025-08-17 21:06:09', '2025-08-17 21:06:09'),
(3, 5, 1, '2025-08-17 21:06:09', '2025-08-17 21:06:09'),
(4, 6, 1, '2025-08-17 21:06:09', '2025-08-17 21:06:09'),
(5, 4, 1, '2025-08-17 21:06:09', '2025-08-17 21:06:09'),
(6, 1, 1, '2025-08-17 21:06:09', '2025-08-17 21:06:09'),
(7, 2, 2, '2025-08-17 21:06:09', '2025-08-17 21:06:09'),
(8, 5, 2, '2025-08-17 21:06:09', '2025-08-17 21:06:09'),
(9, 6, 2, '2025-08-17 21:06:09', '2025-08-17 21:06:09'),
(10, 1, 2, '2025-08-17 21:06:09', '2025-08-17 21:06:09'),
(11, 1, 3, '2025-08-17 21:06:09', '2025-08-17 21:06:09');

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
(1, 8, 1, 'Điện thoại Flagship XYZ 2025', 'dien-thoai-flagship-xyz-2025', 'variable', 'Siêu phẩm công nghệ với màn hình Super Retina và chip A20 Bionic.', 'Chi tiết về các công nghệ đột phá, camera siêu nét và thời lượng pin vượt trội của Điện thoại Flagship XYZ 2025.', 'products/uERgfUBbWtnaN3fZ21Jpz1YaohcKgsHvliFynPRQ.jpg', 'active', 1, 1500, '2025-08-17 21:06:09', '2025-08-17 21:15:54', NULL),
(2, 5, 3, 'Laptop Gaming ROG Zephyrus G16', 'laptop-gaming-rog-zephyrus-g16', 'variable', 'Mạnh mẽ trong thân hình mỏng nhẹ, màn hình Nebula HDR tuyệt đỉnh.', 'Trải nghiệm gaming và sáng tạo không giới hạn với CPU Intel Core Ultra 9 và card đồ họa NVIDIA RTX 4080.', NULL, 'active', 1, 950, '2025-08-17 21:06:09', '2025-08-17 21:06:09', NULL),
(3, 8, 1, 'iPhone SE 2024', 'iphone-se-2024', 'simple', 'Sức mạnh đáng kinh ngạc trong một thiết kế nhỏ gọn, quen thuộc.', 'iPhone SE 2024 trang bị chip A17 Bionic mạnh mẽ, kết nối 5G và camera tiên tiến. Một lựa chọn tuyệt vời với mức giá phải chăng.', NULL, 'active', 0, 12500, '2025-08-17 21:06:09', '2025-08-17 21:06:09', NULL),
(4, 5, 3, 'Laptop Asus Zenbook 14 OLED', 'laptop-asus-zenbook-14-oled', 'simple', 'Mỏng nhẹ tinh tế, màn hình OLED 2.8K rực rỡ, chuẩn Intel Evo.', 'Asus Zenbook 14 OLED là sự kết hợp hoàn hảo giữa hiệu năng và tính di động, lý tưởng cho các chuyên gia sáng tạo và doanh nhân năng động.', NULL, 'active', 0, 3100, '2025-08-17 21:06:09', '2025-08-17 21:06:09', NULL),
(5, 3, 1, 'iPad Pro M2 11inch', 'ipad-pro-m2-11inch', 'variable', 'Màn hình Liquid Retina, chip M2 mạnh mẽ.', 'iPad Pro M2 11inch dành cho công việc sáng tạo và giải trí.', NULL, 'active', 1, 2100, '2025-08-17 21:06:09', '2025-08-17 21:06:09', NULL),
(6, 7, 1, 'MacBook Pro M3 14inch', 'macbook-pro-m3-14inch', 'variable', 'Hiệu năng đỉnh cao, màn hình mini-LED.', 'MacBook Pro M3 14inch dành cho lập trình viên và designer.', NULL, 'active', 1, 1800, '2025-08-17 21:06:09', '2025-08-17 21:06:09', NULL),
(7, 8, 2, 'Samsung Galaxy S24 Ultra', 'samsung-galaxy-s24-ultra', 'simple', 'Camera 200MP, pin 5000mAh.', 'Flagship Android mạnh mẽ nhất của Samsung.', NULL, 'active', 1, 3200, '2025-08-17 21:06:09', '2025-08-17 21:06:09', NULL),
(8, 13, 8, 'Tai nghe Sony WH-1000XM5', 'tai-nghe-sony-wh-1000xm5', 'simple', 'Chống ồn chủ động, pin 30h.', 'Tai nghe cao cấp dành cho audiophile và dân văn phòng.', NULL, 'active', 0, 900, '2025-08-17 21:06:09', '2025-08-17 21:06:09', NULL),
(9, 12, 2, 'Samsung Tab S9 Ultra', 'samsung-tab-s9-ultra', 'variable', 'Màn hình AMOLED 14.6 inch, S Pen đi kèm.', 'Tablet Android mạnh mẽ nhất của Samsung.', NULL, 'active', 0, 1100, '2025-08-17 21:06:09', '2025-08-17 21:06:09', NULL),
(10, 8, 4, 'Xiaomi Redmi Note 13 Pro', 'xiaomi-redmi-note-13-pro', 'simple', 'Camera 200MP, pin 5000mAh, sạc nhanh 120W.', 'Điện thoại tầm trung cấu hình mạnh, giá tốt.', NULL, 'active', 0, 2100, '2025-08-17 21:06:09', '2025-08-17 21:06:09', NULL),
(11, 13, 4, 'Tai nghe Xiaomi Buds 4 Pro', 'tai-nghe-xiaomi-buds-4-pro', 'simple', 'Chống ồn chủ động, pin 38h.', 'Tai nghe true wireless giá rẻ, chất lượng tốt.', NULL, 'active', 0, 700, '2025-08-17 21:06:09', '2025-08-17 21:06:09', NULL);

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
(1, 8, 4, 'Bình luận mẫu số 1 cho sản phẩm.', 3, 'approved', NULL, NULL, '2025-08-11 21:06:10', '2025-08-17 21:06:10'),
(2, 6, 1, 'Bình luận mẫu số 2 cho sản phẩm.', 4, 'approved', NULL, NULL, '2025-08-01 21:06:10', '2025-08-17 21:06:10'),
(3, 7, 2, 'Bình luận mẫu số 3 cho sản phẩm.', 5, 'approved', NULL, NULL, '2025-08-01 21:06:10', '2025-08-17 21:06:10'),
(4, 6, 11, 'Bình luận mẫu số 4 cho sản phẩm.', 4, 'approved', NULL, NULL, '2025-07-19 21:06:10', '2025-08-17 21:06:10'),
(5, 1, 10, 'Bình luận mẫu số 5 cho sản phẩm.', 3, 'approved', NULL, NULL, '2025-08-08 21:06:10', '2025-08-17 21:06:10'),
(6, 11, 3, 'Bình luận mẫu số 6 cho sản phẩm.', 4, 'approved', NULL, NULL, '2025-08-11 21:06:10', '2025-08-17 21:06:10'),
(7, 11, 13, 'Bình luận mẫu số 7 cho sản phẩm.', 4, 'approved', NULL, NULL, '2025-07-28 21:06:10', '2025-08-17 21:06:10'),
(8, 1, 13, 'Bình luận mẫu số 8 cho sản phẩm.', 3, 'approved', NULL, NULL, '2025-07-29 21:06:10', '2025-08-17 21:06:10'),
(9, 5, 2, 'Bình luận mẫu số 9 cho sản phẩm.', 3, 'approved', NULL, NULL, '2025-08-08 21:06:10', '2025-08-17 21:06:10'),
(10, 11, 9, 'Bình luận mẫu số 10 cho sản phẩm.', 3, 'approved', NULL, NULL, '2025-07-21 21:06:10', '2025-08-17 21:06:10'),
(11, 6, 2, 'Trả lời cho bình luận 1', NULL, 'approved', 1, NULL, '2025-08-15 21:06:10', '2025-08-17 21:06:10'),
(12, 7, 3, 'Trả lời cho bình luận 2', NULL, 'approved', 2, NULL, '2025-08-16 21:06:10', '2025-08-17 21:06:10'),
(13, 6, 13, 'Trả lời cho bình luận 3', NULL, 'approved', 3, NULL, '2025-08-07 21:06:10', '2025-08-17 21:06:10');

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
(1, 1, 'DT-XYZ-DO-8G', 25990000, 1000000, 'products/variants/3Erm0Ih2eiSR6ghhuE1S13X4beb9nf1r9VJHKfeO.jpg', NULL, NULL, NULL, NULL, 50, NULL, 1, '2025-08-17 21:06:09', '2025-08-17 21:15:54', NULL),
(2, 1, 'DT-XYZ-XANH-16G', 28990000, NULL, 'products/variants/YNwaEuMgi9WTJyb82qWL0khCvMSUvf4cawAvkEvD.jpg', NULL, NULL, NULL, NULL, 45, NULL, 1, '2025-08-17 21:06:09', '2025-08-17 21:15:54', NULL),
(3, 2, 'ROG-G16-8G', 52000000, NULL, NULL, NULL, NULL, NULL, NULL, 25, NULL, 1, '2025-08-17 21:06:09', '2025-08-17 21:06:09', NULL),
(4, 2, 'ROG-G16-16G', 58500000, NULL, NULL, NULL, NULL, NULL, NULL, 15, NULL, 1, '2025-08-17 21:06:09', '2025-08-17 21:06:09', NULL),
(5, 3, 'IP-SE-2024', 12490000, NULL, NULL, NULL, NULL, NULL, NULL, 400, NULL, 1, '2025-08-17 21:06:09', '2025-08-17 21:06:09', NULL),
(6, 4, 'AS-ZEN14-OLED', 26490000, NULL, NULL, NULL, NULL, NULL, NULL, 80, NULL, 1, '2025-08-17 21:06:09', '2025-08-17 21:06:09', NULL),
(7, 5, 'IPAD-M2-128GB', 21990000, NULL, NULL, NULL, NULL, NULL, NULL, 100, NULL, 1, '2025-08-17 21:06:09', '2025-08-17 21:06:09', NULL),
(8, 5, 'IPAD-M2-256GB', 24990000, NULL, NULL, NULL, NULL, NULL, NULL, 100, NULL, 1, '2025-08-17 21:06:09', '2025-08-17 21:06:09', NULL),
(9, 6, 'MBP-M3-256GB', 45990000, NULL, NULL, NULL, NULL, NULL, NULL, 100, NULL, 1, '2025-08-17 21:06:09', '2025-08-17 21:06:09', NULL),
(10, 6, 'MBP-M3-512GB', 52990000, NULL, NULL, NULL, NULL, NULL, NULL, 100, NULL, 1, '2025-08-17 21:06:09', '2025-08-17 21:06:09', NULL),
(11, 7, 'SGS24U', 33990000, NULL, NULL, NULL, NULL, NULL, NULL, 100, NULL, 1, '2025-08-17 21:06:09', '2025-08-17 21:06:09', NULL),
(12, 8, 'SONY-XM5', 8490000, NULL, NULL, NULL, NULL, NULL, NULL, 100, NULL, 1, '2025-08-17 21:06:09', '2025-08-17 21:06:09', NULL),
(13, 9, 'TAB-S9U-256GB', 27990000, NULL, NULL, NULL, NULL, NULL, NULL, 100, NULL, 1, '2025-08-17 21:06:09', '2025-08-17 21:06:09', NULL),
(14, 9, 'TAB-S9U-512GB', 31990000, NULL, NULL, NULL, NULL, NULL, NULL, 100, NULL, 1, '2025-08-17 21:06:09', '2025-08-17 21:06:09', NULL),
(15, 10, 'RN13PRO', 8990000, NULL, NULL, NULL, NULL, NULL, NULL, 100, NULL, 1, '2025-08-17 21:06:09', '2025-08-17 21:06:09', NULL),
(16, 11, 'BUDS4PRO', 2490000, NULL, NULL, NULL, NULL, NULL, NULL, 100, NULL, 1, '2025-08-17 21:06:09', '2025-08-17 21:06:09', NULL);

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
(1, 'admin', 'admin', NULL, '2025-08-17 21:06:09', '2025-08-17 21:06:09'),
(2, 'staff', 'staff', NULL, '2025-08-17 21:06:09', '2025-08-17 21:06:09'),
(3, 'user', 'user', NULL, '2025-08-17 21:06:09', '2025-08-17 21:06:09');

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
('fl71xoKL1CO9LCDi5ib1F0M8OO75K9j8W4y9XrtX', 13, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/139.0.0.0 Safari/537.36 Edg/139.0.0.0', 'YTo1OntzOjY6Il90b2tlbiI7czo0MDoiQjh3dEFCZmlUZHhlNDZacGJaQTVONGE2MU9JeDBDeUpOSTRTanJidyI7czo2OiJfZmxhc2giO2E6Mjp7czozOiJuZXciO2E6MDp7fXM6Mzoib2xkIjthOjA6e319czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6MzQ6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMC9hZG1pbi9vcmRlcnMiO31zOjUwOiJsb2dpbl93ZWJfNTliYTM2YWRkYzJiMmY5NDAxNTgwZjAxNGM3ZjU4ZWE0ZTMwOTg5ZCI7aToxMztzOjEzOiJsYXN0X29yZGVyX2lkIjtpOjI0O30=', 1755465554);

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
(1, 'Giao hàng tận nơi', NULL, '2025-08-17 21:06:09', '2025-08-17 21:06:09'),
(2, 'Nhận hàng tại cửa hàng', 'Repudiandae tempore similique quia odio provident sed veritatis.', '2025-08-17 21:06:09', '2025-08-17 21:06:09'),
(3, 'Phương thức giao hàng #3', 'Ut suscipit sunt et maiores.', '2025-08-17 21:06:09', '2025-08-17 21:06:09'),
(4, 'Phương thức giao hàng #4', 'Eaque repellendus non sit repudiandae incidunt.', '2025-08-17 21:06:09', '2025-08-17 21:06:09'),
(5, 'Phương thức giao hàng #5', NULL, '2025-08-17 21:06:09', '2025-08-17 21:06:09'),
(6, 'Phương thức giao hàng #6', NULL, '2025-08-17 21:06:09', '2025-08-17 21:06:09'),
(7, 'Phương thức giao hàng #7', NULL, '2025-08-17 21:06:09', '2025-08-17 21:06:09'),
(8, 'Phương thức giao hàng #8', NULL, '2025-08-17 21:06:09', '2025-08-17 21:06:09'),
(9, 'Phương thức giao hàng #9', NULL, '2025-08-17 21:06:09', '2025-08-17 21:06:09'),
(10, 'Phương thức giao hàng #10', NULL, '2025-08-17 21:06:09', '2025-08-17 21:06:09'),
(11, 'Phương thức giao hàng #11', NULL, '2025-08-17 21:06:09', '2025-08-17 21:06:09'),
(12, 'Phương thức giao hàng #12', 'Facilis porro saepe architecto natus maiores optio tempore.', '2025-08-17 21:06:09', '2025-08-17 21:06:09'),
(13, 'Phương thức giao hàng #13', 'Et totam aut voluptas quis numquam et explicabo.', '2025-08-17 21:06:09', '2025-08-17 21:06:09'),
(14, 'Phương thức giao hàng #14', 'Reprehenderit esse et delectus esse.', '2025-08-17 21:06:09', '2025-08-17 21:06:09'),
(15, 'Phương thức giao hàng #15', 'Quia quasi praesentium beatae et.', '2025-08-17 21:06:09', '2025-08-17 21:06:09'),
(16, 'Phương thức giao hàng #16', 'Eius est rem dolor doloremque.', '2025-08-17 21:06:09', '2025-08-17 21:06:09'),
(17, 'Phương thức giao hàng #17', NULL, '2025-08-17 21:06:09', '2025-08-17 21:06:09'),
(18, 'Phương thức giao hàng #18', 'Qui ut et architecto dolore aut sint voluptas.', '2025-08-17 21:06:09', '2025-08-17 21:06:09'),
(19, 'Phương thức giao hàng #19', NULL, '2025-08-17 21:06:09', '2025-08-17 21:06:09'),
(20, 'Phương thức giao hàng #20', NULL, '2025-08-17 21:06:09', '2025-08-17 21:06:09');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` bigint UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
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

INSERT INTO `users` (`id`, `name`, `email`, `password`, `remember_token`, `phone_number`, `image_profile`, `is_active`, `birthday`, `gender`, `created_at`, `updated_at`, `email_verified_at`, `deleted_at`) VALUES
(1, 'John Doe', 'johndoe@example.com', '$2y$12$rAsCG9VzCCKei8DMtCNMAeqKq/npPsYueL3UuaJBJiVPFe.a5m56C', NULL, '123456789', 'profile1.jpg', 1, '1990-01-01', 'male', '2025-08-17 21:06:06', '2025-08-17 21:06:06', NULL, NULL),
(2, 'Jane Smith', 'jane@example.com', '$2y$12$etr7pzz62P.BZPj1oaV.FeNrcNVnYo2Wua8odS4NXRvWoZ7Q401Je', NULL, '987654321', 'profile2.jpg', 1, '1992-05-15', 'female', '2025-08-17 21:06:06', '2025-08-17 21:06:06', NULL, NULL),
(3, 'Nguyen Van A', 'nguyenvana@example.com', '$2y$12$YXptqOZ5yEWwVhyGiqOh7udMldOLGdolYmz67bRYwXtjl8mYDfzfe', NULL, '0901111111', 'profile3.jpg', 1, '1995-03-10', 'male', '2025-08-17 21:06:06', '2025-08-17 21:06:06', NULL, NULL),
(4, 'Tran Thi B', 'tranthib@example.com', '$2y$12$mwdrZcxZSFgjg6BVbuIk/OB2SuE/RsipUi25W7OYCKVgQDx1qu0cC', NULL, '0902222222', 'profile4.jpg', 1, '1996-07-21', 'female', '2025-08-17 21:06:07', '2025-08-17 21:06:07', NULL, NULL),
(5, 'Le Van C', 'levanc@example.com', '$2y$12$5fmXNZAHmrB/SBmW.FWef.Kp5x4Lfd3EEwnWNbF8YjnVU5qQss1Hm', NULL, '0903333333', 'profile5.jpg', 1, '1993-11-05', 'male', '2025-08-17 21:06:07', '2025-08-17 21:06:07', NULL, NULL),
(6, 'Pham Thi D', 'phamthid@example.com', '$2y$12$XA1grMPhRM7l0w0bmAfkFObrr5zgB9ltYp23/Hq1cApPMU.RvF6lK', NULL, '0904444444', 'profile6.jpg', 1, '1994-02-14', 'female', '2025-08-17 21:06:07', '2025-08-17 21:06:07', NULL, NULL),
(7, 'Hoang Van E', 'hoangvane@example.com', '$2y$12$xL4IwlEQlas56xOOWgSVn.vRUv3cLp5qu01s0r3R6hgmlsHzS8Gyu', NULL, '0905555555', 'profile7.jpg', 1, '1991-09-09', 'male', '2025-08-17 21:06:08', '2025-08-17 21:06:08', NULL, NULL),
(8, 'Vu Thi F', 'vuthif@example.com', '$2y$12$5G/d8V7onciQ2wYwMvITTO8OKqLQIYyBxVqggPCXTiEEAcxidVXsy', NULL, '0906666666', 'profile8.jpg', 1, '1997-12-12', 'female', '2025-08-17 21:06:08', '2025-08-17 21:06:08', NULL, NULL),
(9, 'Do Van G', 'dovang@example.com', '$2y$12$qd11sTeYNVfRNKDRJf00AOzTFcHv1q.jwIR3rx3CHrdUNg5lTlxqO', NULL, '0907777777', 'profile9.jpg', 1, '1998-04-18', 'male', '2025-08-17 21:06:08', '2025-08-17 21:06:08', NULL, NULL),
(10, 'Bui Thi H', 'buithih@example.com', '$2y$12$E6bOosfY1v3agg4zoOnnvuprrIux4Y.0p.0P/8E3tn2dIpBTkN1iy', NULL, '0908888888', 'profile10.jpg', 1, '1999-06-25', 'female', '2025-08-17 21:06:08', '2025-08-17 21:06:08', NULL, NULL),
(11, 'Pham Van I', 'phamvani@example.com', '$2y$12$Ru8k9XSbGUoVpnu.zsw5I.N5vUQkaCsYtZe1WNZ97IAYKW49jZ1Ca', NULL, '0909999999', 'profile11.jpg', 1, '1992-08-30', 'male', '2025-08-17 21:06:09', '2025-08-17 21:06:09', NULL, NULL),
(12, 'Nguyen Thi K', 'nguyenthik@example.com', '$2y$12$CHqs8KVDC4P5xM8wmapMl.9Llof8Og9ouzl8NivMdD5qmhTH6owt.', NULL, '0910000000', 'profile12.jpg', 1, '1993-10-11', 'female', '2025-08-17 21:06:09', '2025-08-17 21:06:09', NULL, NULL),
(13, 'Admin', 'admin@gmail.com', '$2y$12$dZ2Iz5PrYfU7IOrze2XtjeU/XRaL23ioeQgnbwLNmMM70WC/F/kKu', NULL, '0999999999', 'admin.jpg', 1, '1990-01-01', 'male', '2025-08-17 21:06:09', '2025-08-17 21:06:09', NULL, NULL);

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
(1, 13, NULL, NULL, NULL, '2894 Layla Square Apt. 588', 'Tân Mai', 'Thanh Xuân', 'Hà Nội', 1, '2025-08-17 21:06:09', '2025-08-17 21:06:09', NULL),
(2, 13, NULL, NULL, NULL, '79913 Macy River', 'Phúc Tân', 'Hai Bà Trưng', 'Hà Nội', 0, '2025-08-17 21:06:09', '2025-08-17 21:06:09', NULL),
(3, 13, NULL, NULL, NULL, '161 Horace Mall', 'Phúc Tân', 'Hai Bà Trưng', 'Hà Nội', 0, '2025-08-17 21:06:09', '2025-08-17 21:06:09', NULL),
(4, 10, NULL, NULL, NULL, '6059 Ephraim Coves', 'Quan Hoa', 'Ba Đình', 'Hà Nội', 1, '2025-08-17 21:06:09', '2025-08-17 21:06:09', NULL),
(5, 10, NULL, NULL, NULL, '55779 Langworth Wall', 'Vĩnh Phúc', 'Tây Hồ', 'Hà Nội', 0, '2025-08-17 21:06:09', '2025-08-17 21:06:09', NULL),
(6, 10, NULL, NULL, NULL, '29702 Schmeler Stravenue Suite 588', 'Yên Sở', 'Hai Bà Trưng', 'Hà Nội', 0, '2025-08-17 21:06:09', '2025-08-17 21:06:09', NULL),
(7, 9, NULL, NULL, NULL, '2325 Luigi Coves', 'Ô Chợ Dừa', 'Hoàn Kiếm', 'Hà Nội', 1, '2025-08-17 21:06:09', '2025-08-17 21:06:09', NULL),
(8, 9, NULL, NULL, NULL, '392 Marina Ports', 'Điện Biên', 'Hoàn Kiếm', 'Hà Nội', 0, '2025-08-17 21:06:09', '2025-08-17 21:06:09', NULL),
(9, 9, NULL, NULL, NULL, '625 Dicki Ramp', 'Ô Chợ Dừa', 'Tây Hồ', 'Hà Nội', 0, '2025-08-17 21:06:09', '2025-08-17 21:06:09', NULL),
(10, 7, NULL, NULL, NULL, '731 Jaida Rapid', 'Đội Cấn', 'Đống Đa', 'Hà Nội', 1, '2025-08-17 21:06:09', '2025-08-17 21:06:09', NULL),
(11, 7, NULL, NULL, NULL, '27451 Shields Causeway Apt. 077', 'Quan Hoa', 'Hoàn Kiếm', 'Hà Nội', 0, '2025-08-17 21:06:09', '2025-08-17 21:06:09', NULL),
(12, 7, NULL, NULL, NULL, '53353 Odie Wells Apt. 408', 'Điện Biên', 'Đống Đa', 'Hà Nội', 0, '2025-08-17 21:06:09', '2025-08-17 21:06:09', NULL),
(13, 2, NULL, NULL, NULL, '4520 Waters Crossroad Suite 121', 'Yên Sở', 'Cầu Giấy', 'Hà Nội', 1, '2025-08-17 21:06:09', '2025-08-17 21:06:09', NULL),
(14, 2, NULL, NULL, NULL, '456 Nicole Mountain', 'Dịch Vọng', 'Thanh Xuân', 'Hà Nội', 0, '2025-08-17 21:06:09', '2025-08-17 21:06:09', NULL),
(15, 2, NULL, NULL, NULL, '16417 Krajcik Stravenue Suite 136', 'Nghĩa Tân', 'Tây Hồ', 'Hà Nội', 0, '2025-08-17 21:06:09', '2025-08-17 21:06:09', NULL),
(16, 1, NULL, NULL, NULL, '30038 Pacocha Trace Suite 897', 'Đội Cấn', 'Tây Hồ', 'Hà Nội', 1, '2025-08-17 21:06:09', '2025-08-17 21:06:09', NULL),
(17, 1, NULL, NULL, NULL, '4065 Kemmer Centers Suite 940', 'Vĩnh Phúc', 'Hoàn Kiếm', 'Hà Nội', 0, '2025-08-17 21:06:09', '2025-08-17 21:06:09', NULL),
(18, 1, NULL, NULL, NULL, '83930 Kub Green Suite 790', 'Láng Hạ', 'Thanh Xuân', 'Hà Nội', 0, '2025-08-17 21:06:09', '2025-08-17 21:06:09', NULL),
(19, 5, NULL, NULL, NULL, '97652 Green Pine', 'Hoàng Văn Thụ', 'Cầu Giấy', 'Hà Nội', 1, '2025-08-17 21:06:09', '2025-08-17 21:06:09', NULL),
(20, 5, NULL, NULL, NULL, '56044 Ryder Dam', 'Hàng Trống', 'Hoàng Mai', 'Hà Nội', 0, '2025-08-17 21:06:09', '2025-08-17 21:06:09', NULL),
(21, 5, NULL, NULL, NULL, '4026 Miller Cape Suite 594', 'Yên Hòa', 'Hai Bà Trưng', 'Hà Nội', 0, '2025-08-17 21:06:09', '2025-08-17 21:06:09', NULL),
(22, 12, NULL, NULL, NULL, '8705 Estell Summit Apt. 243', 'Đội Cấn', 'Đống Đa', 'Hà Nội', 1, '2025-08-17 21:06:09', '2025-08-17 21:06:09', NULL),
(23, 12, NULL, NULL, NULL, '575 Troy Harbor', 'Thổ Quan', 'Cầu Giấy', 'Hà Nội', 0, '2025-08-17 21:06:09', '2025-08-17 21:06:09', NULL),
(24, 12, NULL, NULL, NULL, '74482 Quinten Path', 'Dịch Vọng', 'Hoàn Kiếm', 'Hà Nội', 0, '2025-08-17 21:06:09', '2025-08-17 21:06:09', NULL),
(25, 3, NULL, NULL, NULL, '3546 Laurel Cove', 'Quan Hoa', 'Long Biên', 'Hà Nội', 1, '2025-08-17 21:06:09', '2025-08-17 21:06:09', NULL),
(26, 3, NULL, NULL, NULL, '749 Eryn Camp Suite 515', 'Chương Dương', 'Hai Bà Trưng', 'Hà Nội', 0, '2025-08-17 21:06:09', '2025-08-17 21:06:09', NULL),
(27, 3, NULL, NULL, NULL, '86794 Myrtle Plains Apt. 422', 'Ô Chợ Dừa', 'Long Biên', 'Hà Nội', 0, '2025-08-17 21:06:09', '2025-08-17 21:06:09', NULL),
(28, 6, NULL, NULL, NULL, '636 Jadon Lodge Suite 447', 'Chương Dương', 'Thanh Xuân', 'Hà Nội', 1, '2025-08-17 21:06:09', '2025-08-17 21:06:09', NULL),
(29, 6, NULL, NULL, NULL, '8595 Wilfredo Meadow', 'Điện Biên', 'Thanh Xuân', 'Hà Nội', 0, '2025-08-17 21:06:09', '2025-08-17 21:06:09', NULL),
(30, 6, NULL, NULL, NULL, '72947 McCullough Union Apt. 307', 'Yên Sở', 'Hoàng Mai', 'Hà Nội', 0, '2025-08-17 21:06:09', '2025-08-17 21:06:09', NULL),
(31, 11, NULL, NULL, NULL, '3605 Odie Knoll', 'Đội Cấn', 'Đống Đa', 'Hà Nội', 1, '2025-08-17 21:06:09', '2025-08-17 21:06:09', NULL),
(32, 11, NULL, NULL, NULL, '613 Benedict Manor Suite 142', 'Nghĩa Tân', 'Hai Bà Trưng', 'Hà Nội', 0, '2025-08-17 21:06:09', '2025-08-17 21:06:09', NULL),
(33, 11, NULL, NULL, NULL, '14686 Jailyn Via Suite 337', 'Phúc Xá', 'Đống Đa', 'Hà Nội', 0, '2025-08-17 21:06:09', '2025-08-17 21:06:09', NULL),
(34, 4, NULL, NULL, NULL, '61967 Marvin Manor', 'Dịch Vọng', 'Cầu Giấy', 'Hà Nội', 1, '2025-08-17 21:06:09', '2025-08-17 21:06:09', NULL),
(35, 4, NULL, NULL, NULL, '6145 Lelah Neck', 'Kim Liên', 'Cầu Giấy', 'Hà Nội', 0, '2025-08-17 21:06:09', '2025-08-17 21:06:09', NULL),
(36, 4, NULL, NULL, NULL, '78844 Lueilwitz Ports Suite 604', 'Phúc Xá', 'Cầu Giấy', 'Hà Nội', 0, '2025-08-17 21:06:09', '2025-08-17 21:06:09', NULL),
(37, 8, NULL, NULL, NULL, '9762 Farrell Via', 'Ô Chợ Dừa', 'Đống Đa', 'Hà Nội', 1, '2025-08-17 21:06:09', '2025-08-17 21:06:09', NULL),
(38, 8, NULL, NULL, NULL, '543 Pagac Vista', 'Nghĩa Tân', 'Đống Đa', 'Hà Nội', 0, '2025-08-17 21:06:09', '2025-08-17 21:06:09', NULL),
(39, 8, NULL, NULL, NULL, '41377 Herman Centers Suite 008', 'Thổ Quan', 'Đống Đa', 'Hà Nội', 0, '2025-08-17 21:06:09', '2025-08-17 21:06:09', NULL);

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
  ADD UNIQUE KEY `coupons_code_unique` (`code`);

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
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

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
-- AUTO_INCREMENT for table `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=46;

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
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=58;

--
-- AUTO_INCREMENT for table `notifications`
--
ALTER TABLE `notifications`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `orders`
--
ALTER TABLE `orders`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=25;

--
-- AUTO_INCREMENT for table `order_items`
--
ALTER TABLE `order_items`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=25;

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
