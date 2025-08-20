-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Aug 17, 2025 at 05:41 PM
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
(1, 'Màu sắc', 'mau-sac', 'color', NULL, NULL, '2025-08-12 22:18:51', '2025-08-12 22:18:51'),
(2, 'RAM', 'ram', 'text', NULL, NULL, '2025-08-12 22:18:51', '2025-08-12 22:18:51'),
(3, 'Bộ nhớ trong', 'bo-nho-trong', 'text', NULL, NULL, '2025-08-12 22:18:51', '2025-08-12 22:18:51');

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
(1, 1, 'https://picsum.photos/200/400', '2025-08-03 05:18:51', '2025-09-02 05:18:51', 'https://techvicom.vn/', '2025-08-12 22:18:51', '2025-08-12 22:18:51'),
(2, 2, 'https://picsum.photos/200/400', '2025-08-08 05:18:51', '2025-09-07 05:18:51', 'https://techvicom.vn/khuyen-mai', '2025-08-12 22:18:51', '2025-08-12 22:18:51'),
(3, 3, 'https://picsum.photos/200/400', '2025-08-12 05:18:51', '2025-09-12 05:18:51', NULL, '2025-08-12 22:18:51', '2025-08-12 22:18:51');

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
(1, 'Apple', 'brands/apple.png', 'apple', 'Chuyên các sản phẩm iPhone, MacBook, iPad.', 1, '2025-08-12 22:18:51', '2025-08-12 22:18:51', NULL),
(2, 'Samsung', 'brands/samsung.png', 'samsung', 'Thương hiệu điện thoại Android và thiết bị gia dụng.', 1, '2025-08-12 22:18:51', '2025-08-12 22:18:51', NULL),
(3, 'ASUS', 'brands/asus.png', 'asus', 'Chuyên laptop văn phòng, gaming, bo mạch chủ.', 1, '2025-08-12 22:18:51', '2025-08-12 22:18:51', NULL),
(4, 'Xiaomi', 'brands/xiaomi.png', 'xiaomi', 'Điện thoại thông minh và thiết bị IoT giá rẻ.', 1, '2025-08-12 22:18:51', '2025-08-12 22:18:51', NULL),
(5, 'Dell', 'brands/dell.png', 'dell', 'Laptop doanh nhân và máy chủ hiệu suất cao.', 1, '2025-08-12 22:18:51', '2025-08-12 22:18:51', NULL),
(6, 'HP', 'brands/hp.png', 'hp', 'Thương hiệu máy tính và thiết bị in ấn phổ biến.', 1, '2025-08-12 22:18:51', '2025-08-12 22:18:51', NULL),
(7, 'Lenovo', 'brands/lenovo.png', 'lenovo', 'Máy tính văn phòng, gaming và máy trạm.', 1, '2025-08-12 22:18:51', '2025-08-12 22:18:51', NULL),
(8, 'Sony', 'brands/sony.png', 'sony', 'Thiết bị giải trí, PlayStation và âm thanh cao cấp.', 1, '2025-08-12 22:18:51', '2025-08-12 22:18:51', NULL),
(9, 'MSI', 'brands/msi.png', 'msi', 'Chuyên laptop và linh kiện gaming cao cấp.', 1, '2025-08-12 22:18:51', '2025-08-12 22:18:51', NULL),
(10, 'Acer', 'brands/acer.png', 'acer', 'Laptop học sinh, sinh viên và văn phòng giá rẻ.', 1, '2025-08-12 22:18:51', '2025-08-12 22:18:51', NULL);

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
  `product_variant_id` bigint UNSIGNED DEFAULT NULL,
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
(1, NULL, 'Laptop', 'laptop', NULL, 1, '2025-08-12 22:18:51', '2025-08-12 22:18:51', NULL),
(2, NULL, 'Điện thoại', 'dien-thoai', NULL, 1, '2025-08-12 22:18:51', '2025-08-12 22:18:51', NULL),
(3, NULL, 'Tablet', 'tablet', NULL, 1, '2025-08-12 22:18:51', '2025-08-12 22:18:51', NULL),
(4, NULL, 'Phụ kiện', 'phu-kien', NULL, 1, '2025-08-12 22:18:51', '2025-08-12 22:18:51', NULL),
(5, 1, 'Laptop Gaming', 'laptop-gaming', NULL, 1, '2025-08-12 22:18:51', '2025-08-12 22:18:51', NULL),
(6, 1, 'Laptop Văn phòng', 'laptop-van-phong', NULL, 1, '2025-08-12 22:18:51', '2025-08-12 22:18:51', NULL),
(7, 1, 'MacBook', 'macbook', NULL, 1, '2025-08-12 22:18:51', '2025-08-12 22:18:51', NULL),
(8, 2, 'iPhone', 'iphone', NULL, 1, '2025-08-12 22:18:51', '2025-08-12 22:18:51', NULL),
(9, 2, 'Samsung Galaxy', 'samsung-galaxy', NULL, 1, '2025-08-12 22:18:51', '2025-08-12 22:18:51', NULL),
(10, 2, 'Xiaomi', 'xiaomi', NULL, 1, '2025-08-12 22:18:51', '2025-08-12 22:18:51', NULL),
(11, 3, 'iPad', 'ipad', NULL, 1, '2025-08-12 22:18:51', '2025-08-12 22:18:51', NULL),
(12, 3, 'Samsung Tab', 'samsung-tab', NULL, 1, '2025-08-12 22:18:51', '2025-08-12 22:18:51', NULL),
(13, 4, 'Tai nghe', 'tai-nghe', NULL, 1, '2025-08-12 22:18:51', '2025-08-12 22:18:51', NULL),
(14, 4, 'Sạc và cáp', 'sac-va-cap', NULL, 1, '2025-08-12 22:18:51', '2025-08-12 22:18:51', NULL);

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
(1, 'Nguyễn Văn A', 'vana@example.com', '0909123456', 'Hỏi về sản phẩm', 'Cho tôi hỏi sản phẩm này còn hàng không?', 13, NULL, 'pending', NULL, NULL, 0, '2025-08-12 22:18:51', '2025-08-12 22:18:51'),
(2, 'Trần Thị B', 'thib@example.com', '0911222333', 'Thắc mắc giao hàng', 'Tôi muốn biết khi nào đơn hàng được giao.', 10, NULL, 'pending', NULL, NULL, 1, '2025-08-12 22:18:51', '2025-08-12 22:18:51'),
(3, 'Lê Văn C', 'vanc@example.com', '0922333444', 'Hủy đơn hàng', 'Tôi muốn hủy đơn hàng vừa đặt.', NULL, NULL, 'pending', NULL, NULL, 0, '2025-08-12 22:18:51', '2025-08-12 22:18:51'),
(4, 'Phạm Thị D', 'thid@example.com', '0933444555', 'Phản hồi dịch vụ', 'Dịch vụ chăm sóc khách hàng rất tốt.', NULL, NULL, 'pending', NULL, NULL, 1, '2025-08-12 22:18:51', '2025-08-12 22:18:51'),
(5, 'Đỗ Minh E', 'minhe@example.com', '0944555666', 'Đổi hàng', 'Tôi muốn đổi sản phẩm vì bị lỗi.', 9, NULL, 'pending', NULL, NULL, 0, '2025-08-12 22:18:51', '2025-08-12 22:18:51'),
(6, 'Hoàng Thị F', 'thif@example.com', '0955666777', 'Cần tư vấn', 'Bạn có thể tư vấn giúp tôi sản phẩm phù hợp?', NULL, NULL, 'pending', NULL, NULL, 1, '2025-08-12 22:18:51', '2025-08-12 22:18:51'),
(7, 'Ngô Văn G', 'vang@example.com', '0966777888', 'Góp ý', 'Website của bạn rất dễ sử dụng.', NULL, NULL, 'pending', NULL, NULL, 0, '2025-08-12 22:18:51', '2025-08-12 22:18:51'),
(8, 'Vũ Thị H', 'thih@example.com', '0977888999', 'Thanh toán', 'Tôi muốn đổi phương thức thanh toán.', 7, NULL, 'pending', NULL, NULL, 1, '2025-08-12 22:18:51', '2025-08-12 22:18:51'),
(9, 'Bùi Văn I', 'vani@example.com', '0988999000', 'Khuyến mãi', 'Cửa hàng hiện có chương trình khuyến mãi nào?', NULL, NULL, 'pending', NULL, NULL, 0, '2025-08-12 22:18:51', '2025-08-12 22:18:51'),
(10, 'Lý Thị K', 'thik@example.com', '0999000111', 'Đặt hàng lỗi', 'Tôi không thể đặt hàng trên website.', 2, NULL, 'pending', NULL, NULL, 1, '2025-08-12 22:18:51', '2025-08-12 22:18:51');

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
(1, 'DISCOUNT10', 'percent', 10, 100000, 500000, 5000000, 5, '2025-08-03', '2025-09-13', 1, NULL, NULL, NULL),
(2, 'BIGSALE10', 'percent', 10, 20000000, 50000000, 1000000000, 1, '2025-08-12', '2025-09-13', 1, NULL, NULL, NULL),
(3, 'VIPFIXED', 'fixed', 50000000, NULL, 200000000, 2000000000, 1, '2025-08-12', '2025-09-13', 1, NULL, NULL, NULL),
(4, 'MEGAVIP', 'percent', 50, 100000000, 500000000, 5000000000, 1, '2025-08-12', '2025-09-13', 1, NULL, NULL, NULL),
(5, 'SALE50', 'percent', 50, 100000, 200000, 1000000, 2, '2025-08-12', '2025-09-12', 1, NULL, NULL, NULL),
(6, 'SALE100', 'percent', 50, 100000, 200000, 1000000, 2, '2025-08-12', '2025-09-12', 1, NULL, NULL, NULL);

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
(40, '2025_08_12_125500_add_missing_columns_to_user_addresses_table', 1);

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
(1, 1, 'Giảm giá 50% cho đơn hàng đầu tiên', 'Hãy nhanh tay nhận ưu đãi 50% khi mua hàng lần đầu tiên tại cửa hàng chúng tôi.', 'uploads/news/default.jpg', 7, 'published', '2025-08-12 22:18:51', '2025-08-12 22:18:51', '2025-08-12 22:18:51', NULL),
(2, 1, 'Mua 1 tặng 1 cuối tuần', 'Chương trình mua 1 tặng 1 áp dụng từ thứ 6 đến chủ nhật hàng tuần.', 'uploads/news/default.jpg', 9, 'published', '2025-08-12 22:18:51', '2025-08-12 22:18:51', '2025-08-12 22:18:51', NULL),
(3, 2, 'iPhone 15 chính thức ra mắt', 'Apple đã giới thiệu iPhone 15 với nhiều cải tiến về hiệu năng và camera.', 'uploads/news/default.jpg', 7, 'published', '2025-08-12 22:18:51', '2025-08-12 22:18:51', '2025-08-12 22:18:51', NULL),
(4, 2, 'Samsung trình làng Galaxy Z Flip6', 'Samsung tiếp tục đẩy mạnh phân khúc điện thoại gập với Galaxy Z Flip6.', 'uploads/news/default.jpg', 10, 'published', '2025-08-12 22:18:51', '2025-08-12 22:18:51', '2025-08-12 22:18:51', NULL),
(5, 3, 'Hướng dẫn sử dụng máy ép chậm', 'Bài viết sẽ giúp bạn hiểu rõ cách sử dụng máy ép chậm để giữ nguyên dưỡng chất.', 'uploads/news/default.jpg', 2, 'published', '2025-08-12 22:18:51', '2025-08-12 22:18:51', '2025-08-12 22:18:51', NULL),
(6, 3, 'Cách bảo quản tai nghe không dây', 'Giữ gìn tai nghe đúng cách giúp kéo dài tuổi thọ và giữ âm thanh tốt.', 'uploads/news/default.jpg', 7, 'published', '2025-08-12 22:18:51', '2025-08-12 22:18:51', '2025-08-12 22:18:51', NULL),
(7, 4, 'Đánh giá laptop Asus Zenbook 14', 'Asus Zenbook 14 nổi bật với thiết kế mỏng nhẹ, pin trâu và hiệu năng ổn định.', 'uploads/news/default.jpg', 13, 'published', '2025-08-12 22:18:51', '2025-08-12 22:18:51', '2025-08-12 22:18:51', NULL),
(8, 4, 'So sánh Xiaomi Redmi Note 12 và Realme 11', 'Cùng so sánh hai sản phẩm tầm trung hot nhất hiện nay.', 'uploads/news/default.jpg', 10, 'published', '2025-08-12 22:18:51', '2025-08-12 22:18:51', '2025-08-12 22:18:51', NULL);

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
(1, 'Khuyến mãi', 'khuyen-mai', '2025-08-12 22:18:51', '2025-08-12 22:18:51'),
(2, 'Tin tức công nghệ', 'tin-tuc-cong-nghe', '2025-08-12 22:18:51', '2025-08-12 22:18:51'),
(3, 'Hướng dẫn sử dụng sản phẩm', 'huong-dan-su-dung-san-pham', '2025-08-12 22:18:51', '2025-08-12 22:18:51'),
(4, 'Đánh giá sản phẩm', 'danh-gia-san-pham', '2025-08-12 22:18:51', '2025-08-12 22:18:51'),
(5, 'Mẹo vặt công nghệ', 'meo-vat-cong-nghe', '2025-08-12 22:18:51', '2025-08-12 22:18:51'),
(6, 'Sự kiện và ra mắt sản phẩm', 'su-kien-ra-mat-san-pham', '2025-08-12 22:18:51', '2025-08-12 22:18:51'),
(7, 'Review cửa hàng', 'review-cua-hang', '2025-08-12 22:18:51', '2025-08-12 22:18:51'),
(8, 'Chăm sóc khách hàng', 'cham-soc-khach-hang', '2025-08-12 22:18:51', '2025-08-12 22:18:51'),
(9, 'Mua sắm trực tuyến', 'mua-sam-truc-tuyen', '2025-08-12 22:18:51', '2025-08-12 22:18:51'),
(10, 'Sản phẩm mới', 'san-pham-moi', '2025-08-12 22:18:51', '2025-08-12 22:18:51');

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
(1, 2, 1, NULL, 'Tôi đã áp dụng và thấy hiệu quả ngay.', 0, 4, '2025-08-12 22:18:51', '2025-08-12 22:18:51'),
(2, 13, 1, NULL, 'Sản phẩm này mình đã dùng, rất ok.', 0, 4, '2025-08-12 22:18:51', '2025-08-12 22:18:51'),
(3, 2, 1, 2, '↪ Sản phẩm này mình đã dùng, rất ok.', 0, 0, '2025-08-12 22:18:51', '2025-08-12 22:18:51'),
(4, 10, 1, 2, '↪ Có thể giải thích thêm phần này được không?', 1, 4, '2025-08-12 22:18:51', '2025-08-12 22:18:51'),
(5, 7, 1, NULL, 'Bài viết rất hữu ích, cảm ơn bạn!', 1, 5, '2025-08-12 22:18:51', '2025-08-12 22:18:51'),
(6, 10, 1, 5, '↪ Rất mong có thêm bài viết tương tự.', 1, 0, '2025-08-12 22:18:51', '2025-08-12 22:18:51'),
(7, 2, 2, NULL, 'Rất mong có thêm bài viết tương tự.', 1, 3, '2025-08-12 22:18:51', '2025-08-12 22:18:51'),
(8, 7, 2, 7, '↪ Mình sẽ giới thiệu bài viết này cho bạn bè.', 0, 2, '2025-08-12 22:18:51', '2025-08-12 22:18:51'),
(9, 9, 2, 7, '↪ Bài viết rất hữu ích, cảm ơn bạn!', 0, 0, '2025-08-12 22:18:51', '2025-08-12 22:18:51'),
(10, 7, 2, NULL, 'Rất mong có thêm bài viết tương tự.', 1, 2, '2025-08-12 22:18:51', '2025-08-12 22:18:51'),
(11, 10, 2, 10, '↪ Mình sẽ giới thiệu bài viết này cho bạn bè.', 1, 3, '2025-08-12 22:18:51', '2025-08-12 22:18:51'),
(12, 2, 2, 10, '↪ Có thể giải thích thêm phần này được không?', 0, 5, '2025-08-12 22:18:51', '2025-08-12 22:18:51'),
(13, 7, 2, NULL, 'Sản phẩm này mình đã dùng, rất ok.', 0, 10, '2025-08-12 22:18:51', '2025-08-12 22:18:51'),
(14, 10, 2, NULL, 'Có thể giải thích thêm phần này được không?', 0, 7, '2025-08-12 22:18:51', '2025-08-12 22:18:51'),
(15, 9, 2, 14, '↪ Rất mong có thêm bài viết tương tự.', 1, 0, '2025-08-12 22:18:51', '2025-08-12 22:18:51'),
(16, 13, 3, NULL, 'Thông tin chi tiết và rõ ràng.', 1, 3, '2025-08-12 22:18:51', '2025-08-12 22:18:51'),
(17, 9, 3, NULL, 'Mình sẽ giới thiệu bài viết này cho bạn bè.', 0, 0, '2025-08-12 22:18:51', '2025-08-12 22:18:51'),
(18, 9, 3, NULL, 'Sản phẩm này mình đã dùng, rất ok.', 1, 0, '2025-08-12 22:18:51', '2025-08-12 22:18:51'),
(19, 2, 3, 18, '↪ Bài viết rất hữu ích, cảm ơn bạn!', 0, 5, '2025-08-12 22:18:51', '2025-08-12 22:18:51'),
(20, 13, 3, 18, '↪ Cảm ơn bạn đã chia sẻ!', 1, 5, '2025-08-12 22:18:51', '2025-08-12 22:18:51'),
(21, 7, 3, NULL, 'Thông tin chi tiết và rõ ràng.', 1, 4, '2025-08-12 22:18:51', '2025-08-12 22:18:51'),
(22, 7, 3, NULL, 'Có thể giải thích thêm phần này được không?', 1, 10, '2025-08-12 22:18:51', '2025-08-12 22:18:51'),
(23, 7, 4, NULL, 'Tôi đã áp dụng và thấy hiệu quả ngay.', 1, 0, '2025-08-12 22:18:51', '2025-08-12 22:18:51'),
(24, 13, 4, 23, '↪ Thông tin chi tiết và rõ ràng.', 0, 2, '2025-08-12 22:18:51', '2025-08-12 22:18:51'),
(25, 2, 4, 23, '↪ Sản phẩm này mình đã dùng, rất ok.', 1, 2, '2025-08-12 22:18:51', '2025-08-12 22:18:51'),
(26, 7, 4, NULL, 'Thông tin chi tiết và rõ ràng.', 0, 10, '2025-08-12 22:18:51', '2025-08-12 22:18:51'),
(27, 7, 4, 26, '↪ Thông tin chi tiết và rõ ràng.', 0, 2, '2025-08-12 22:18:51', '2025-08-12 22:18:51'),
(28, 2, 4, NULL, 'Có thể giải thích thêm phần này được không?', 1, 5, '2025-08-12 22:18:51', '2025-08-12 22:18:51'),
(29, 13, 4, 28, '↪ Có thể giải thích thêm phần này được không?', 0, 3, '2025-08-12 22:18:51', '2025-08-12 22:18:51'),
(30, 13, 4, NULL, 'Sản phẩm này mình đã dùng, rất ok.', 1, 8, '2025-08-12 22:18:51', '2025-08-12 22:18:51'),
(31, 13, 4, NULL, 'Rất thích nội dung kiểu này.', 1, 0, '2025-08-12 22:18:51', '2025-08-12 22:18:51'),
(32, 10, 5, NULL, 'Tôi đã áp dụng và thấy hiệu quả ngay.', 1, 2, '2025-08-12 22:18:51', '2025-08-12 22:18:51'),
(33, 13, 5, NULL, 'Tôi đã áp dụng và thấy hiệu quả ngay.', 0, 1, '2025-08-12 22:18:51', '2025-08-12 22:18:51'),
(34, 10, 5, 33, '↪ Mình sẽ giới thiệu bài viết này cho bạn bè.', 1, 5, '2025-08-12 22:18:51', '2025-08-12 22:18:51'),
(35, 2, 5, NULL, 'Tôi đã áp dụng và thấy hiệu quả ngay.', 1, 5, '2025-08-12 22:18:51', '2025-08-12 22:18:51'),
(36, 10, 6, NULL, 'Mình sẽ giới thiệu bài viết này cho bạn bè.', 1, 1, '2025-08-12 22:18:51', '2025-08-12 22:18:51'),
(37, 10, 6, 36, '↪ Rất thích nội dung kiểu này.', 0, 2, '2025-08-12 22:18:51', '2025-08-12 22:18:51'),
(38, 10, 6, 36, '↪ Rất mong có thêm bài viết tương tự.', 1, 5, '2025-08-12 22:18:51', '2025-08-12 22:18:51'),
(39, 2, 6, NULL, 'Rất thích nội dung kiểu này.', 1, 0, '2025-08-12 22:18:51', '2025-08-12 22:18:51'),
(40, 2, 6, NULL, 'Sản phẩm này mình đã dùng, rất ok.', 0, 9, '2025-08-12 22:18:51', '2025-08-12 22:18:51'),
(41, 10, 6, 40, '↪ Cảm ơn bạn đã chia sẻ!', 1, 5, '2025-08-12 22:18:51', '2025-08-12 22:18:51'),
(42, 2, 6, NULL, 'Bài viết hay nhưng nên bổ sung thêm ví dụ.', 1, 5, '2025-08-12 22:18:51', '2025-08-12 22:18:51'),
(43, 7, 6, 42, '↪ Sản phẩm này mình đã dùng, rất ok.', 1, 0, '2025-08-12 22:18:51', '2025-08-12 22:18:51'),
(44, 2, 6, NULL, 'Mình sẽ giới thiệu bài viết này cho bạn bè.', 1, 9, '2025-08-12 22:18:51', '2025-08-12 22:18:51'),
(45, 9, 7, NULL, 'Thông tin chi tiết và rõ ràng.', 0, 9, '2025-08-12 22:18:51', '2025-08-12 22:18:51'),
(46, 2, 7, 45, '↪ Tôi đã áp dụng và thấy hiệu quả ngay.', 1, 2, '2025-08-12 22:18:51', '2025-08-12 22:18:51'),
(47, 7, 7, 45, '↪ Cảm ơn bạn đã chia sẻ!', 0, 2, '2025-08-12 22:18:51', '2025-08-12 22:18:51'),
(48, 13, 7, NULL, 'Bài viết hay nhưng nên bổ sung thêm ví dụ.', 0, 0, '2025-08-12 22:18:51', '2025-08-12 22:18:51'),
(49, 13, 7, 48, '↪ Rất thích nội dung kiểu này.', 0, 1, '2025-08-12 22:18:51', '2025-08-12 22:18:51'),
(50, 13, 7, 48, '↪ Thông tin chi tiết và rõ ràng.', 1, 0, '2025-08-12 22:18:51', '2025-08-12 22:18:51'),
(51, 10, 7, NULL, 'Tôi đã áp dụng và thấy hiệu quả ngay.', 0, 9, '2025-08-12 22:18:51', '2025-08-12 22:18:51'),
(52, 2, 8, NULL, 'Tôi đã áp dụng và thấy hiệu quả ngay.', 0, 4, '2025-08-12 22:18:51', '2025-08-12 22:18:51'),
(53, 7, 8, 52, '↪ Rất thích nội dung kiểu này.', 1, 0, '2025-08-12 22:18:51', '2025-08-12 22:18:51'),
(54, 7, 8, 52, '↪ Mình sẽ giới thiệu bài viết này cho bạn bè.', 1, 1, '2025-08-12 22:18:51', '2025-08-12 22:18:51'),
(55, 7, 8, NULL, 'Rất thích nội dung kiểu này.', 1, 10, '2025-08-12 22:18:51', '2025-08-12 22:18:51'),
(56, 13, 8, 55, '↪ Rất thích nội dung kiểu này.', 1, 4, '2025-08-12 22:18:51', '2025-08-12 22:18:51'),
(57, 13, 8, 55, '↪ Rất mong có thêm bài viết tương tự.', 1, 5, '2025-08-12 22:18:51', '2025-08-12 22:18:51'),
(58, 10, 8, NULL, 'Rất mong có thêm bài viết tương tự.', 1, 5, '2025-08-12 22:18:51', '2025-08-12 22:18:51'),
(59, 9, 8, 58, '↪ Mình sẽ giới thiệu bài viết này cho bạn bè.', 0, 1, '2025-08-12 22:18:51', '2025-08-12 22:18:51');

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
  `guest_name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `guest_email` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `guest_phone` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `address_id` bigint UNSIGNED DEFAULT NULL,
  `payment_method` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `coupon_id` bigint UNSIGNED DEFAULT NULL,
  `coupon_code` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `discount_amount` decimal(10,2) DEFAULT '0.00',
  `shipping_fee` decimal(15,0) NOT NULL,
  `total_amount` decimal(15,0) NOT NULL,
  `final_total` decimal(15,0) NOT NULL,
  `status` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'pending',
  `payment_status` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'pending',
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

INSERT INTO `orders` (`id`, `user_id`, `guest_name`, `guest_email`, `guest_phone`, `address_id`, `payment_method`, `coupon_id`, `coupon_code`, `discount_amount`, `shipping_fee`, `total_amount`, `final_total`, `status`, `payment_status`, `recipient_name`, `recipient_phone`, `recipient_email`, `recipient_address`, `shipped_at`, `created_at`, `updated_at`, `shipping_method_id`, `deleted_at`) VALUES
(1, 3, NULL, NULL, NULL, 19, 'paypal', NULL, NULL, '25.94', '12', '73', '59', 'pending', 'pending', 'Randi Mante', '463-566-3493', NULL, '408 Ephraim Burg Apt. 537\nFayefurt, MA 45343', NULL, '2025-02-15 17:34:29', '2025-08-12 22:18:51', NULL, NULL),
(2, 10, NULL, NULL, NULL, 23, 'credit_card', NULL, NULL, '9.79', '18', '454', '462', 'pending', 'pending', 'Maria Tillman', '1-986-444-7608', NULL, '6340 Patrick Landing\nLambertborough, AL 44909-1075', '2025-03-13 22:44:21', '2025-03-07 07:02:15', '2025-08-12 22:18:51', NULL, NULL),
(3, 7, NULL, NULL, NULL, 30, 'paypal', NULL, NULL, '20.70', '16', '93', '88', 'pending', 'pending', 'Raphaelle Kub', '870.403.1115', NULL, '777 Bauch Turnpike\nLake Darienstad, OR 62911-9999', '2025-07-25 14:21:07', '2025-06-10 15:57:45', '2025-08-12 22:18:51', NULL, NULL),
(4, 12, NULL, NULL, NULL, 18, 'credit_card', 1, 'HWR4SSDG', '27.23', '11', '129', '113', 'pending', 'pending', 'Ms. Amber Morar MD', '+1.248.591.6419', NULL, '9309 McCullough Mills\nEast Antwonview, LA 26977', NULL, '2025-08-08 07:13:12', '2025-08-12 22:18:51', 17, NULL),
(5, 9, NULL, NULL, NULL, 16, 'bank_transfer', NULL, NULL, '6.28', '9', '399', '402', 'pending', 'pending', 'Orrin Weber V', '(640) 213-6546', NULL, '12196 Isom Row Suite 326\nEstherside, MT 76950', '2025-07-12 13:48:57', '2025-07-07 14:33:39', '2025-08-12 22:18:51', NULL, NULL),
(6, 4, NULL, NULL, NULL, 11, 'paypal', NULL, NULL, '7.08', '10', '491', '495', 'pending', 'pending', 'Prof. Weston Will', '(925) 515-0586', NULL, '41348 Aniyah Street\nSouth Audie, DC 78288-2398', '2025-06-11 06:24:41', '2025-06-05 23:21:35', '2025-08-12 22:18:51', NULL, NULL),
(7, 7, NULL, NULL, NULL, 14, 'paypal', 3, 'RUSBWQA9', '16.42', '6', '490', '479', 'pending', 'pending', 'Robb Kihn', '(404) 792-4522', NULL, '22843 McDermott Mills Apt. 231\nHahnview, KY 88171-6769', '2025-07-23 02:53:26', '2025-06-26 02:36:28', '2025-08-12 22:18:51', 10, NULL),
(8, 5, NULL, NULL, NULL, 32, 'bank_transfer', NULL, NULL, '28.46', '16', '329', '317', 'pending', 'pending', 'Dr. Naomie Gutmann I', '+1.458.837.2824', NULL, '5087 Mills Green\nTerrybury, WA 98709', '2025-07-10 09:11:28', '2025-05-05 18:25:04', '2025-08-12 22:18:51', 16, NULL),
(9, 4, NULL, NULL, NULL, 11, 'bank_transfer', NULL, NULL, '33.68', '10', '143', '120', 'pending', 'pending', 'Dr. Fanny Hegmann Jr.', '402.415.6716', NULL, '37646 Meghan Coves\nPort Milford, HI 47477', NULL, '2025-03-12 00:21:35', '2025-08-12 22:18:51', 6, NULL),
(10, 2, NULL, NULL, NULL, 38, 'bank_transfer', NULL, NULL, '4.81', '19', '398', '412', 'pending', 'pending', 'Dr. Eldred Pfeffer', '956-865-0064', NULL, '616 Jarrell Fields Suite 234\nWest Jessikabury, UT 31328-4102', NULL, '2025-02-27 01:56:38', '2025-08-12 22:18:51', NULL, NULL),
(11, 5, NULL, NULL, NULL, 1, 'credit_card', 6, '4AAC8D5T', '18.72', '17', '247', '246', 'pending', 'pending', 'Dr. Laury Williamson III', '(703) 359-7032', NULL, '1477 Timmy Port\nWizamouth, WV 69219', '2025-07-19 13:59:27', '2025-05-14 17:16:56', '2025-08-12 22:18:51', NULL, NULL),
(12, 7, NULL, NULL, NULL, 9, 'bank_transfer', NULL, NULL, '16.16', '12', '355', '351', 'pending', 'pending', 'Kane Jacobi', '+1-820-916-2913', NULL, '1257 Aditya Roads\nLake Charlotteburgh, CO 89958', '2025-03-18 04:53:13', '2025-03-02 06:08:27', '2025-08-12 22:18:51', NULL, NULL),
(13, 12, NULL, NULL, NULL, 26, 'paypal', 5, 'Z2LLFGMX', '31.67', '7', '340', '316', 'pending', 'pending', 'Della Wehner', '1-864-450-7459', NULL, '794 Roosevelt Underpass\nWest Maida, MA 71302', '2025-08-03 01:15:00', '2025-07-01 16:57:56', '2025-08-12 22:18:51', NULL, NULL),
(14, 4, NULL, NULL, NULL, 6, 'bank_transfer', NULL, NULL, '6.03', '15', '96', '105', 'pending', 'pending', 'Raul Raynor', '1-512-508-1714', NULL, '53962 Margaretta Trail Suite 595\nSouth Nicholasshire, MI 21736', '2025-07-15 06:22:33', '2025-07-09 06:02:19', '2025-08-12 22:18:51', NULL, NULL),
(15, 10, NULL, NULL, NULL, 4, 'credit_card', NULL, NULL, '33.30', '8', '315', '290', 'pending', 'pending', 'Mrs. Mellie Graham', '+1-667-794-5772', NULL, '896 Abbott Villages\nSouth Denis, ID 77554', '2025-08-05 21:00:09', '2025-03-17 20:45:04', '2025-08-12 22:18:51', NULL, NULL),
(16, 9, NULL, NULL, NULL, 39, 'paypal', 1, 'RDM6CLOA', '33.78', '9', '180', '155', 'pending', 'pending', 'Jalon Goodwin', '906-986-7398', NULL, '349 Lisette Springs\nPort Ceasarshire, DC 55976-5471', NULL, '2025-03-13 17:59:49', '2025-08-12 22:18:51', NULL, NULL),
(17, 11, NULL, NULL, NULL, 14, 'bank_transfer', NULL, NULL, '23.50', '10', '318', '304', 'pending', 'pending', 'Cleora Nienow', '+1 (551) 846-3116', NULL, '219 Robyn Ranch Suite 155\nJastmouth, KY 64367-7892', '2025-07-14 18:06:29', '2025-04-15 11:44:54', '2025-08-12 22:18:51', 15, NULL),
(18, 11, NULL, NULL, NULL, 32, 'bank_transfer', 3, 'G4TCLPZB', '17.06', '9', '149', '141', 'pending', 'pending', 'Agustina Ledner', '+1 (910) 829-1879', NULL, '280 Heller Ports\nLake Damion, CT 61633', NULL, '2025-07-02 06:45:05', '2025-08-12 22:18:51', NULL, NULL),
(19, 2, NULL, NULL, NULL, 7, 'paypal', NULL, NULL, '5.64', '13', '126', '133', 'pending', 'pending', 'Jaquan Rodriguez', '954.469.0806', NULL, '196 Floyd Skyway\nJenkinston, WA 34384', '2025-07-24 02:19:12', '2025-07-10 15:54:14', '2025-08-12 22:18:51', 3, NULL),
(20, 7, NULL, NULL, NULL, 3, 'paypal', NULL, NULL, '32.62', '19', '167', '154', 'pending', 'pending', 'Dr. Jarvis Bergstrom MD', '+1.531.427.4664', NULL, '4334 Celine Ridges Apt. 531\nLeastad, MS 24419-7925', '2025-05-08 19:30:07', '2025-04-30 08:31:18', '2025-08-12 22:18:51', 18, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `order_items`
--

CREATE TABLE `order_items` (
  `id` bigint UNSIGNED NOT NULL,
  `order_id` bigint UNSIGNED NOT NULL,
  `variant_id` bigint UNSIGNED NOT NULL,
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
(1, 5, 3, 10, 'Xiaomi Redmi Note 13 Pro', NULL, 5, '115.55', '577.75', '2025-08-12 22:18:51', '2025-08-12 22:18:51'),
(2, 17, 11, 6, 'MacBook Pro M3 14inch', NULL, 1, '106.45', '106.45', '2025-08-12 22:18:51', '2025-08-12 22:18:51'),
(3, 16, 7, 1, 'Điện thoại Flagship XYZ 2025', NULL, 2, '46.86', '93.72', '2025-08-12 22:18:51', '2025-08-12 22:18:51'),
(4, 12, 14, 11, 'Tai nghe Xiaomi Buds 4 Pro', NULL, 4, '130.75', '523.00', '2025-08-12 22:18:51', '2025-08-12 22:18:51'),
(5, 1, 8, 9, 'Samsung Tab S9 Ultra', NULL, 3, '102.78', '308.34', '2025-08-12 22:18:51', '2025-08-12 22:18:51'),
(6, 9, 13, 3, 'iPhone SE 2024', NULL, 3, '93.73', '281.19', '2025-08-12 22:18:51', '2025-08-12 22:18:51'),
(7, 7, 4, 7, 'Samsung Galaxy S24 Ultra', NULL, 5, '78.53', '392.65', '2025-08-12 22:18:51', '2025-08-12 22:18:51'),
(8, 5, 11, 6, 'MacBook Pro M3 14inch', NULL, 4, '133.19', '532.76', '2025-08-12 22:18:51', '2025-08-12 22:18:51'),
(9, 15, 5, 4, 'Laptop Asus Zenbook 14 OLED', NULL, 4, '100.64', '402.56', '2025-08-12 22:18:51', '2025-08-12 22:18:51'),
(10, 6, 14, 6, 'MacBook Pro M3 14inch', NULL, 3, '160.13', '480.39', '2025-08-12 22:18:51', '2025-08-12 22:18:51'),
(11, 12, 13, 5, 'iPad Pro M2 11inch', NULL, 2, '92.42', '184.84', '2025-08-12 22:18:51', '2025-08-12 22:18:51'),
(12, 13, 10, 5, 'iPad Pro M2 11inch', NULL, 2, '151.07', '302.14', '2025-08-12 22:18:51', '2025-08-12 22:18:51'),
(13, 10, 5, 6, 'MacBook Pro M3 14inch', NULL, 4, '175.04', '700.16', '2025-08-12 22:18:51', '2025-08-12 22:18:51'),
(14, 20, 7, 11, 'Tai nghe Xiaomi Buds 4 Pro', NULL, 4, '21.54', '86.16', '2025-08-12 22:18:51', '2025-08-12 22:18:51'),
(15, 16, 9, 7, 'Samsung Galaxy S24 Ultra', NULL, 3, '92.39', '277.17', '2025-08-12 22:18:51', '2025-08-12 22:18:51'),
(16, 1, 1, 8, 'Tai nghe Sony WH-1000XM5', NULL, 4, '108.37', '433.48', '2025-08-12 22:18:51', '2025-08-12 22:18:51'),
(17, 4, 10, 2, 'Laptop Gaming ROG Zephyrus G16', NULL, 1, '161.08', '161.08', '2025-08-12 22:18:51', '2025-08-12 22:18:51'),
(18, 3, 4, 3, 'iPhone SE 2024', NULL, 2, '13.29', '26.58', '2025-08-12 22:18:51', '2025-08-12 22:18:51'),
(19, 15, 2, 9, 'Samsung Tab S9 Ultra', NULL, 2, '51.77', '103.54', '2025-08-12 22:18:51', '2025-08-12 22:18:51'),
(20, 6, 10, 8, 'Tai nghe Sony WH-1000XM5', NULL, 4, '94.38', '377.52', '2025-08-12 22:18:51', '2025-08-12 22:18:51');

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
(1, 10, 'Sản phẩm lỗi', 'rejected', 'return', '2025-08-07 02:28:02', '2025-08-08 18:08:49', 'Ab repellendus dolorem qui aperiam vel vitae odit.', 'Quo quis neque dolor quas impedit voluptates aliquid.', '2025-08-12 22:18:51', '2025-08-12 22:18:51'),
(2, 20, 'Giao nhầm hàng', 'pending', 'return', '2025-08-01 05:18:16', NULL, NULL, NULL, '2025-08-12 22:18:51', '2025-08-12 22:18:51'),
(3, 4, 'Không đúng mô tả', 'rejected', 'return', '2025-07-20 18:26:16', NULL, NULL, NULL, '2025-08-12 22:18:51', '2025-08-12 22:18:51'),
(4, 1, 'Không cần nữa', 'approved', 'cancel', '2025-07-18 04:07:55', '2025-08-08 19:34:23', NULL, 'Deserunt omnis totam recusandae.', '2025-08-12 22:18:51', '2025-08-12 22:18:51'),
(5, 20, 'Không cần nữa', 'pending', 'cancel', '2025-07-19 22:38:36', '2025-08-12 00:13:28', 'Aut quas quia minus et neque laudantium tempore a.', NULL, '2025-08-12 22:18:51', '2025-08-12 22:18:51'),
(6, 13, 'Không cần nữa', 'approved', 'return', '2025-07-27 06:17:28', NULL, NULL, 'Enim est sit fuga dolores voluptatem consequatur inventore.', '2025-08-12 22:18:51', '2025-08-12 22:18:51'),
(7, 10, 'Không đúng mô tả', 'approved', 'cancel', '2025-07-29 14:52:36', NULL, NULL, 'Dolor fuga nesciunt tenetur maiores.', '2025-08-12 22:18:51', '2025-08-12 22:18:51'),
(8, 3, 'Không đúng mô tả', 'rejected', 'cancel', '2025-07-27 13:20:52', NULL, 'Nesciunt voluptates fugit consectetur tenetur nulla necessitatibus eaque eius.', 'Voluptas odit perspiciatis necessitatibus officia non.', '2025-08-12 22:18:51', '2025-08-12 22:18:51'),
(9, 10, 'Sản phẩm lỗi', 'approved', 'return', '2025-08-05 13:16:20', NULL, 'Est provident est inventore autem praesentium ullam et.', 'Tenetur et perspiciatis at quo fuga quia.', '2025-08-12 22:18:51', '2025-08-12 22:18:51'),
(10, 1, 'Không cần nữa', 'rejected', 'cancel', '2025-07-18 23:56:51', '2025-08-10 04:35:21', 'Suscipit eius fugit voluptatem esse saepe eaque est.', 'Mollitia et ducimus non vitae et doloribus.', '2025-08-12 22:18:51', '2025-08-12 22:18:51'),
(11, 16, 'Sản phẩm lỗi', 'approved', 'cancel', '2025-07-29 06:42:09', NULL, 'Qui et pariatur sit dignissimos consequatur rem totam.', 'Reprehenderit ea distinctio vel molestias voluptatem adipisci et.', '2025-08-12 22:18:51', '2025-08-12 22:18:51'),
(12, 3, 'Giao nhầm hàng', 'approved', 'return', '2025-07-27 19:53:36', '2025-08-05 06:02:40', 'Impedit esse nostrum iste et consequatur.', NULL, '2025-08-12 22:18:51', '2025-08-12 22:18:51'),
(13, 8, NULL, 'pending', 'cancel', '2025-08-09 17:42:50', NULL, 'Expedita non pariatur quis illo deleniti.', NULL, '2025-08-12 22:18:51', '2025-08-12 22:18:51'),
(14, 11, 'Không đúng mô tả', 'pending', 'return', '2025-07-23 23:48:50', '2025-08-01 11:44:46', 'Est non et distinctio autem et.', 'Et aliquid sit velit est.', '2025-08-12 22:18:51', '2025-08-12 22:18:51'),
(15, 17, 'Khách thay đổi ý định', 'rejected', 'cancel', '2025-08-05 06:17:57', '2025-08-12 08:27:07', NULL, 'Ab omnis libero vero quidem delectus qui.', '2025-08-12 22:18:51', '2025-08-12 22:18:51'),
(16, 12, 'Giao nhầm hàng', 'approved', 'cancel', '2025-08-08 22:46:44', NULL, 'A ea nihil excepturi aperiam esse cumque.', 'Enim rerum recusandae hic sed delectus.', '2025-08-12 22:18:51', '2025-08-12 22:18:51'),
(17, 15, 'Giao nhầm hàng', 'rejected', 'return', '2025-07-26 10:01:06', NULL, 'Rerum architecto eligendi odit alias expedita eaque nostrum quia.', NULL, '2025-08-12 22:18:51', '2025-08-12 22:18:51'),
(18, 10, 'Khách thay đổi ý định', 'rejected', 'return', '2025-07-23 16:55:07', '2025-08-03 20:56:42', 'Natus pariatur aut at exercitationem tempore voluptatum.', 'Quo voluptatem cupiditate harum soluta est voluptates.', '2025-08-12 22:18:51', '2025-08-12 22:18:51'),
(19, 6, NULL, 'pending', 'return', '2025-08-03 20:24:32', NULL, NULL, NULL, '2025-08-12 22:18:51', '2025-08-12 22:18:51'),
(20, 8, 'Khách thay đổi ý định', 'pending', 'return', '2025-07-28 12:14:15', '2025-08-07 14:00:05', 'Est quasi laboriosam facere veritatis blanditiis aut eligendi.', 'Velit et iusto aut.', '2025-08-12 22:18:51', '2025-08-12 22:18:51');

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
(1, 'view_users', 'Xem danh sách người dùng', '2025-08-12 22:18:51', '2025-08-12 22:18:51', NULL),
(2, 'edit_users', 'Chỉnh sửa người dùng', '2025-08-12 22:18:51', '2025-08-12 22:18:51', NULL),
(3, 'delete_users', 'Xoá người dùng', '2025-08-12 22:18:51', '2025-08-12 22:18:51', NULL),
(4, 'manage_roles', 'Quản lý vai trò', '2025-08-12 22:18:51', '2025-08-12 22:18:51', NULL),
(5, 'manage_content', 'Quản lý nội dung', '2025-08-12 22:18:51', '2025-08-12 22:18:51', NULL),
(6, 'manage_coupons', 'Quản lý mã giảm giá', '2025-08-12 22:18:51', '2025-08-12 22:18:51', NULL);

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
(1, 3, 1, '2025-08-12 22:18:51', '2025-08-12 22:18:51'),
(2, 2, 1, '2025-08-12 22:18:51', '2025-08-12 22:18:51'),
(3, 5, 1, '2025-08-12 22:18:51', '2025-08-12 22:18:51'),
(4, 6, 1, '2025-08-12 22:18:51', '2025-08-12 22:18:51'),
(5, 4, 1, '2025-08-12 22:18:51', '2025-08-12 22:18:51'),
(6, 1, 1, '2025-08-12 22:18:51', '2025-08-12 22:18:51'),
(7, 2, 2, '2025-08-12 22:18:51', '2025-08-12 22:18:51'),
(8, 5, 2, '2025-08-12 22:18:51', '2025-08-12 22:18:51'),
(9, 6, 2, '2025-08-12 22:18:51', '2025-08-12 22:18:51'),
(10, 1, 2, '2025-08-12 22:18:51', '2025-08-12 22:18:51'),
(11, 1, 3, '2025-08-12 22:18:51', '2025-08-12 22:18:51');

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
(1, 8, 1, 'Điện thoại Flagship XYZ 2025', 'dien-thoai-flagship-xyz-2025', 'variable', 'Siêu phẩm công nghệ với màn hình Super Retina và chip A20 Bionic.', 'Chi tiết về các công nghệ đột phá, camera siêu nét và thời lượng pin vượt trội của Điện thoại Flagship XYZ 2025.', NULL, 'active', 1, 1500, '2025-08-12 22:18:51', '2025-08-12 22:18:51', NULL),
(2, 5, 3, 'Laptop Gaming ROG Zephyrus G16', 'laptop-gaming-rog-zephyrus-g16', 'variable', 'Mạnh mẽ trong thân hình mỏng nhẹ, màn hình Nebula HDR tuyệt đỉnh.', 'Trải nghiệm gaming và sáng tạo không giới hạn với CPU Intel Core Ultra 9 và card đồ họa NVIDIA RTX 4080.', NULL, 'active', 1, 950, '2025-08-12 22:18:51', '2025-08-12 22:18:51', NULL),
(3, 8, 1, 'iPhone SE 2024', 'iphone-se-2024', 'simple', 'Sức mạnh đáng kinh ngạc trong một thiết kế nhỏ gọn, quen thuộc.', 'iPhone SE 2024 trang bị chip A17 Bionic mạnh mẽ, kết nối 5G và camera tiên tiến. Một lựa chọn tuyệt vời với mức giá phải chăng.', NULL, 'active', 0, 12500, '2025-08-12 22:18:51', '2025-08-12 22:18:51', NULL),
(4, 5, 3, 'Laptop Asus Zenbook 14 OLED', 'laptop-asus-zenbook-14-oled', 'simple', 'Mỏng nhẹ tinh tế, màn hình OLED 2.8K rực rỡ, chuẩn Intel Evo.', 'Asus Zenbook 14 OLED là sự kết hợp hoàn hảo giữa hiệu năng và tính di động, lý tưởng cho các chuyên gia sáng tạo và doanh nhân năng động.', NULL, 'active', 0, 3100, '2025-08-12 22:18:51', '2025-08-12 22:18:51', NULL),
(5, 3, 1, 'iPad Pro M2 11inch', 'ipad-pro-m2-11inch', 'variable', 'Màn hình Liquid Retina, chip M2 mạnh mẽ.', 'iPad Pro M2 11inch dành cho công việc sáng tạo và giải trí.', NULL, 'active', 1, 2100, '2025-08-12 22:18:51', '2025-08-12 22:18:51', NULL),
(6, 7, 1, 'MacBook Pro M3 14inch', 'macbook-pro-m3-14inch', 'variable', 'Hiệu năng đỉnh cao, màn hình mini-LED.', 'MacBook Pro M3 14inch dành cho lập trình viên và designer.', NULL, 'active', 1, 1800, '2025-08-12 22:18:51', '2025-08-12 22:18:51', NULL),
(7, 8, 2, 'Samsung Galaxy S24 Ultra', 'samsung-galaxy-s24-ultra', 'simple', 'Camera 200MP, pin 5000mAh.', 'Flagship Android mạnh mẽ nhất của Samsung.', NULL, 'active', 1, 3200, '2025-08-12 22:18:51', '2025-08-12 22:18:51', NULL),
(8, 13, 8, 'Tai nghe Sony WH-1000XM5', 'tai-nghe-sony-wh-1000xm5', 'simple', 'Chống ồn chủ động, pin 30h.', 'Tai nghe cao cấp dành cho audiophile và dân văn phòng.', NULL, 'active', 0, 900, '2025-08-12 22:18:51', '2025-08-12 22:18:51', NULL),
(9, 12, 2, 'Samsung Tab S9 Ultra', 'samsung-tab-s9-ultra', 'variable', 'Màn hình AMOLED 14.6 inch, S Pen đi kèm.', 'Tablet Android mạnh mẽ nhất của Samsung.', NULL, 'active', 0, 1100, '2025-08-12 22:18:51', '2025-08-12 22:18:51', NULL),
(10, 8, 4, 'Xiaomi Redmi Note 13 Pro', 'xiaomi-redmi-note-13-pro', 'simple', 'Camera 200MP, pin 5000mAh, sạc nhanh 120W.', 'Điện thoại tầm trung cấu hình mạnh, giá tốt.', NULL, 'active', 0, 2100, '2025-08-12 22:18:51', '2025-08-12 22:18:51', NULL),
(11, 13, 4, 'Tai nghe Xiaomi Buds 4 Pro', 'tai-nghe-xiaomi-buds-4-pro', 'simple', 'Chống ồn chủ động, pin 38h.', 'Tai nghe true wireless giá rẻ, chất lượng tốt.', NULL, 'active', 0, 700, '2025-08-12 22:18:51', '2025-08-12 22:18:51', NULL);

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
(1, 5, 4, 'Bình luận mẫu số 1 cho sản phẩm.', 4, 'approved', NULL, NULL, '2025-07-13 22:18:51', '2025-08-12 22:18:51'),
(2, 9, 12, 'Bình luận mẫu số 2 cho sản phẩm.', 3, 'approved', NULL, NULL, '2025-07-21 22:18:51', '2025-08-12 22:18:51'),
(3, 9, 13, 'Bình luận mẫu số 3 cho sản phẩm.', 3, 'approved', NULL, NULL, '2025-08-05 22:18:51', '2025-08-12 22:18:51'),
(4, 1, 11, 'Bình luận mẫu số 4 cho sản phẩm.', 4, 'approved', NULL, NULL, '2025-08-09 22:18:51', '2025-08-12 22:18:51'),
(5, 1, 9, 'Bình luận mẫu số 5 cho sản phẩm.', 3, 'approved', NULL, NULL, '2025-07-25 22:18:51', '2025-08-12 22:18:51'),
(6, 5, 10, 'Bình luận mẫu số 6 cho sản phẩm.', 4, 'approved', NULL, NULL, '2025-07-17 22:18:51', '2025-08-12 22:18:51'),
(7, 4, 4, 'Bình luận mẫu số 7 cho sản phẩm.', 4, 'approved', NULL, NULL, '2025-07-24 22:18:51', '2025-08-12 22:18:51'),
(8, 7, 6, 'Bình luận mẫu số 8 cho sản phẩm.', 3, 'approved', NULL, NULL, '2025-08-03 22:18:51', '2025-08-12 22:18:51'),
(9, 10, 8, 'Bình luận mẫu số 9 cho sản phẩm.', 4, 'approved', NULL, NULL, '2025-07-17 22:18:51', '2025-08-12 22:18:51'),
(10, 7, 10, 'Bình luận mẫu số 10 cho sản phẩm.', 3, 'approved', NULL, NULL, '2025-07-26 22:18:51', '2025-08-12 22:18:51'),
(11, 9, 2, 'Trả lời cho bình luận 1', NULL, 'approved', 1, NULL, '2025-08-06 22:18:51', '2025-08-12 22:18:51'),
(12, 9, 10, 'Trả lời cho bình luận 2', NULL, 'approved', 2, NULL, '2025-08-02 22:18:51', '2025-08-12 22:18:51'),
(13, 1, 9, 'Trả lời cho bình luận 3', NULL, 'approved', 3, NULL, '2025-08-05 22:18:51', '2025-08-12 22:18:51');

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
(1, 1, 'DT-XYZ-DO-8G', 25990000, NULL, NULL, NULL, NULL, NULL, NULL, 50, NULL, 1, '2025-08-12 22:18:51', '2025-08-12 22:18:51', NULL),
(2, 1, 'DT-XYZ-XANH-16G', 28990000, NULL, NULL, NULL, NULL, NULL, NULL, 45, NULL, 1, '2025-08-12 22:18:51', '2025-08-12 22:18:51', NULL),
(3, 2, 'ROG-G16-8G', 52000000, NULL, NULL, NULL, NULL, NULL, NULL, 25, NULL, 1, '2025-08-12 22:18:51', '2025-08-12 22:18:51', NULL),
(4, 2, 'ROG-G16-16G', 58500000, NULL, NULL, NULL, NULL, NULL, NULL, 15, NULL, 1, '2025-08-12 22:18:51', '2025-08-12 22:18:51', NULL),
(5, 3, 'IP-SE-2024', 12490000, NULL, NULL, NULL, NULL, NULL, NULL, 400, NULL, 1, '2025-08-12 22:18:51', '2025-08-12 22:18:51', NULL),
(6, 4, 'AS-ZEN14-OLED', 26490000, NULL, NULL, NULL, NULL, NULL, NULL, 80, NULL, 1, '2025-08-12 22:18:51', '2025-08-12 22:18:51', NULL),
(7, 5, 'IPAD-M2-128GB', 21990000, NULL, NULL, NULL, NULL, NULL, NULL, 100, NULL, 1, '2025-08-12 22:18:51', '2025-08-12 22:18:51', NULL),
(8, 5, 'IPAD-M2-256GB', 24990000, NULL, NULL, NULL, NULL, NULL, NULL, 100, NULL, 1, '2025-08-12 22:18:51', '2025-08-12 22:18:51', NULL),
(9, 6, 'MBP-M3-256GB', 45990000, NULL, NULL, NULL, NULL, NULL, NULL, 100, NULL, 1, '2025-08-12 22:18:51', '2025-08-12 22:18:51', NULL),
(10, 6, 'MBP-M3-512GB', 52990000, NULL, NULL, NULL, NULL, NULL, NULL, 100, NULL, 1, '2025-08-12 22:18:51', '2025-08-12 22:18:51', NULL),
(11, 7, 'SGS24U', 33990000, NULL, NULL, NULL, NULL, NULL, NULL, 100, NULL, 1, '2025-08-12 22:18:51', '2025-08-12 22:18:51', NULL),
(12, 8, 'SONY-XM5', 8490000, NULL, NULL, NULL, NULL, NULL, NULL, 100, NULL, 1, '2025-08-12 22:18:51', '2025-08-12 22:18:51', NULL),
(13, 9, 'TAB-S9U-256GB', 27990000, NULL, NULL, NULL, NULL, NULL, NULL, 100, NULL, 1, '2025-08-12 22:18:51', '2025-08-12 22:18:51', NULL),
(14, 9, 'TAB-S9U-512GB', 31990000, NULL, NULL, NULL, NULL, NULL, NULL, 100, NULL, 1, '2025-08-12 22:18:51', '2025-08-12 22:18:51', NULL),
(15, 10, 'RN13PRO', 8990000, NULL, NULL, NULL, NULL, NULL, NULL, 100, NULL, 1, '2025-08-12 22:18:51', '2025-08-12 22:18:51', NULL),
(16, 11, 'BUDS4PRO', 2490000, NULL, NULL, NULL, NULL, NULL, NULL, 100, NULL, 1, '2025-08-12 22:18:51', '2025-08-12 22:18:51', NULL);

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
(1, 'admin', 'admin', NULL, '2025-08-12 22:18:51', '2025-08-12 22:18:51'),
(2, 'staff', 'staff', NULL, '2025-08-12 22:18:51', '2025-08-12 22:18:51'),
(3, 'user', 'user', NULL, '2025-08-12 22:18:51', '2025-08-12 22:18:51');

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
('RbFwwlySuKPmXMmPtVXJK2o6Av306IqxRHAs2fZ3', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/138.0.0.0 Safari/537.36', 'YTo0OntzOjY6Il90b2tlbiI7czo0MDoieHhHVWlqWGlhWXVmVGdiRXNNMVE2Zll5RU5aZFpROHR5UmpGazFySiI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6MzU6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMC9hcGkvd2FyZHMvMDAxIjt9czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319czo0OiJjYXJ0IjthOjE6e3M6MzoiMV8yIjthOjQ6e3M6MTA6InByb2R1Y3RfaWQiO2k6MTtzOjEwOiJ2YXJpYW50X2lkIjtpOjI7czo4OiJxdWFudGl0eSI7aToxO3M6NzoicHJvZHVjdCI7YToxOTp7czoyOiJpZCI7aToxO3M6MTE6ImNhdGVnb3J5X2lkIjtpOjg7czo4OiJicmFuZF9pZCI7aToxO3M6NDoibmFtZSI7czozMzoixJBp4buHbiB0aG/huqFpIEZsYWdzaGlwIFhZWiAyMDI1IjtzOjQ6InNsdWciO3M6Mjg6ImRpZW4tdGhvYWktZmxhZ3NoaXAteHl6LTIwMjUiO3M6NDoidHlwZSI7czo4OiJ2YXJpYWJsZSI7czoxNzoic2hvcnRfZGVzY3JpcHRpb24iO3M6NzY6IlNpw6p1IHBo4bqpbSBjw7RuZyBuZ2jhu4cgduG7m2kgbcOgbiBow6xuaCBTdXBlciBSZXRpbmEgdsOgIGNoaXAgQTIwIEJpb25pYy4iO3M6MTY6ImxvbmdfZGVzY3JpcHRpb24iO3M6MTQ0OiJDaGkgdGnhur90IHbhu4EgY8OhYyBjw7RuZyBuZ2jhu4cgxJHhu5l0IHBow6EsIGNhbWVyYSBzacOqdSBuw6l0IHbDoCB0aOG7nWkgbMaw4bujbmcgcGluIHbGsOG7o3QgdHLhu5lpIGPhu6dhIMSQaeG7h24gdGhv4bqhaSBGbGFnc2hpcCBYWVogMjAyNS4iO3M6OToidGh1bWJuYWlsIjtOO3M6Njoic3RhdHVzIjtzOjY6ImFjdGl2ZSI7czoxMToiaXNfZmVhdHVyZWQiO2I6MTtzOjEwOiJ2aWV3X2NvdW50IjtpOjE1MDA7czoxMDoiY3JlYXRlZF9hdCI7czoyNzoiMjAyNS0wOC0xMlQyMjoxODo1MS4wMDAwMDBaIjtzOjEwOiJ1cGRhdGVkX2F0IjtzOjI3OiIyMDI1LTA4LTEyVDIyOjE4OjUxLjAwMDAwMFoiO3M6MTA6ImRlbGV0ZWRfYXQiO047czoxMToicHJpY2VfcmFuZ2UiO3M6MjY6IjI1LDk5MCwwMDAgLSAyOCw5OTAsMDAwIMSRIjtzOjExOiJ0b3RhbF9zdG9jayI7aTo5NTtzOjE4OiJwcm9kdWN0X2FsbF9pbWFnZXMiO2E6MDp7fXM6ODoidmFyaWFudHMiO2E6Mjp7aTowO2E6MTY6e3M6MjoiaWQiO2k6MTtzOjEwOiJwcm9kdWN0X2lkIjtpOjE7czozOiJza3UiO3M6MTI6IkRULVhZWi1ETy04RyI7czo1OiJwcmljZSI7aToyNTk5MDAwMDtzOjEwOiJzYWxlX3ByaWNlIjtOO3M6NToiaW1hZ2UiO047czo2OiJ3ZWlnaHQiO047czo2OiJsZW5ndGgiO047czo1OiJ3aWR0aCI7TjtzOjY6ImhlaWdodCI7TjtzOjU6InN0b2NrIjtpOjUwO3M6MTY6Imxvd19zdG9ja19hbW91bnQiO047czo5OiJpc19hY3RpdmUiO2I6MTtzOjEwOiJjcmVhdGVkX2F0IjtzOjI3OiIyMDI1LTA4LTEyVDIyOjE4OjUxLjAwMDAwMFoiO3M6MTA6InVwZGF0ZWRfYXQiO3M6Mjc6IjIwMjUtMDgtMTJUMjI6MTg6NTEuMDAwMDAwWiI7czoxMDoiZGVsZXRlZF9hdCI7Tjt9aToxO2E6MTY6e3M6MjoiaWQiO2k6MjtzOjEwOiJwcm9kdWN0X2lkIjtpOjE7czozOiJza3UiO3M6MTU6IkRULVhZWi1YQU5ILTE2RyI7czo1OiJwcmljZSI7aToyODk5MDAwMDtzOjEwOiJzYWxlX3ByaWNlIjtOO3M6NToiaW1hZ2UiO047czo2OiJ3ZWlnaHQiO047czo2OiJsZW5ndGgiO047czo1OiJ3aWR0aCI7TjtzOjY6ImhlaWdodCI7TjtzOjU6InN0b2NrIjtpOjQ1O3M6MTY6Imxvd19zdG9ja19hbW91bnQiO047czo5OiJpc19hY3RpdmUiO2I6MTtzOjEwOiJjcmVhdGVkX2F0IjtzOjI3OiIyMDI1LTA4LTEyVDIyOjE4OjUxLjAwMDAwMFoiO3M6MTA6InVwZGF0ZWRfYXQiO3M6Mjc6IjIwMjUtMDgtMTJUMjI6MTg6NTEuMDAwMDAwWiI7czoxMDoiZGVsZXRlZF9hdCI7Tjt9fX19fX0=', 1755389582),
('WcLkESo54dGsLXpDl4VCiaGK1ehDoHLZ2kZtHLu0', 13, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/138.0.0.0 Safari/537.36', 'YTo0OntzOjY6Il90b2tlbiI7czo0MDoiQUpLUnhYRVFsRzlnVGQzRFhQamYxMVpXTkJoMmhiNFRZMWFDODhnRSI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6MzU6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMC9hZG1pbi9jb3Vwb25zIjt9czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319czo1MDoibG9naW5fd2ViXzU5YmEzNmFkZGMyYjJmOTQwMTU4MGYwMTRjN2Y1OGVhNGUzMDk4OWQiO2k6MTM7fQ==', 1755452413);

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
(1, 'Giao hàng tận nơi', 'Sequi quaerat autem impedit soluta.', '2025-08-12 22:18:51', '2025-08-12 22:18:51'),
(2, 'Nhận hàng tại cửa hàng', 'Nam dolor autem quisquam consequatur facere magni et.', '2025-08-12 22:18:51', '2025-08-12 22:18:51'),
(3, 'Phương thức giao hàng #3', NULL, '2025-08-12 22:18:51', '2025-08-12 22:18:51'),
(4, 'Phương thức giao hàng #4', NULL, '2025-08-12 22:18:51', '2025-08-12 22:18:51'),
(5, 'Phương thức giao hàng #5', NULL, '2025-08-12 22:18:51', '2025-08-12 22:18:51'),
(6, 'Phương thức giao hàng #6', 'Voluptatibus vero est fugiat dicta dolore expedita.', '2025-08-12 22:18:51', '2025-08-12 22:18:51'),
(7, 'Phương thức giao hàng #7', 'Eligendi ipsam praesentium amet sapiente.', '2025-08-12 22:18:51', '2025-08-12 22:18:51'),
(8, 'Phương thức giao hàng #8', 'Fugiat ab autem eveniet sed molestias.', '2025-08-12 22:18:51', '2025-08-12 22:18:51'),
(9, 'Phương thức giao hàng #9', 'Laudantium vel molestias ipsa maiores quos.', '2025-08-12 22:18:51', '2025-08-12 22:18:51'),
(10, 'Phương thức giao hàng #10', NULL, '2025-08-12 22:18:51', '2025-08-12 22:18:51'),
(11, 'Phương thức giao hàng #11', 'Minus velit harum corporis quo.', '2025-08-12 22:18:51', '2025-08-12 22:18:51'),
(12, 'Phương thức giao hàng #12', NULL, '2025-08-12 22:18:51', '2025-08-12 22:18:51'),
(13, 'Phương thức giao hàng #13', NULL, '2025-08-12 22:18:51', '2025-08-12 22:18:51'),
(14, 'Phương thức giao hàng #14', NULL, '2025-08-12 22:18:51', '2025-08-12 22:18:51'),
(15, 'Phương thức giao hàng #15', 'Qui autem tempore qui asperiores et est aut.', '2025-08-12 22:18:51', '2025-08-12 22:18:51'),
(16, 'Phương thức giao hàng #16', 'Tempore velit omnis consequatur in ipsam vel quae.', '2025-08-12 22:18:51', '2025-08-12 22:18:51'),
(17, 'Phương thức giao hàng #17', 'Totam quia ipsam et velit dolorum.', '2025-08-12 22:18:51', '2025-08-12 22:18:51'),
(18, 'Phương thức giao hàng #18', 'Et consequatur necessitatibus aliquid repellat incidunt.', '2025-08-12 22:18:51', '2025-08-12 22:18:51'),
(19, 'Phương thức giao hàng #19', 'Corporis aut at quo et.', '2025-08-12 22:18:51', '2025-08-12 22:18:51'),
(20, 'Phương thức giao hàng #20', NULL, '2025-08-12 22:18:51', '2025-08-12 22:18:51');

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
(1, 'John Doe', 'johndoe@example.com', '$2y$12$7Vsm7WEY.iSubNYj7Z2Fs./eBVsN3plC4tFmSt7aA7VqpQJVxnFc6', NULL, '123456789', 'profile1.jpg', 1, '1990-01-01', 'male', '2025-08-12 22:18:48', '2025-08-12 22:18:48', NULL, NULL),
(2, 'Jane Smith', 'jane@example.com', '$2y$12$eBH3ipQcqVR0mKdRJwxEwurWC0NXo3./Gy45afH9kmUM22j8OkZNu', NULL, '987654321', 'profile2.jpg', 1, '1992-05-15', 'female', '2025-08-12 22:18:48', '2025-08-12 22:18:48', NULL, NULL),
(3, 'Nguyen Van A', 'nguyenvana@example.com', '$2y$12$0tzPWK74m/M7G1X1FOJnTet.14oQXgAO2mKsSmcwEY4guKn1kHPpm', NULL, '0901111111', 'profile3.jpg', 1, '1995-03-10', 'male', '2025-08-12 22:18:49', '2025-08-12 22:18:49', NULL, NULL),
(4, 'Tran Thi B', 'tranthib@example.com', '$2y$12$z12iSCN21cQU/ydKEOekHuQFhY031UNK8FhdKG7CwdpQczjuj159G', NULL, '0902222222', 'profile4.jpg', 1, '1996-07-21', 'female', '2025-08-12 22:18:49', '2025-08-12 22:18:49', NULL, NULL),
(5, 'Le Van C', 'levanc@example.com', '$2y$12$lSawI8ILheONvcimPVjT3OPDJvCDfnpdB6NvxeIjb17c9owS/K2eu', NULL, '0903333333', 'profile5.jpg', 1, '1993-11-05', 'male', '2025-08-12 22:18:49', '2025-08-12 22:18:49', NULL, NULL),
(6, 'Pham Thi D', 'phamthid@example.com', '$2y$12$KZ3IyYyDGkYeoNWgiYLbL.h4hq3soHxYiWR9OEP4PR17Yv3Oev4x.', NULL, '0904444444', 'profile6.jpg', 1, '1994-02-14', 'female', '2025-08-12 22:18:49', '2025-08-12 22:18:49', NULL, NULL),
(7, 'Hoang Van E', 'hoangvane@example.com', '$2y$12$YTBhRzOBp1FvZaedCPolTu.Iu2WcCr18OB8cUpTVzcA8YEjzw9Z1W', NULL, '0905555555', 'profile7.jpg', 1, '1991-09-09', 'male', '2025-08-12 22:18:50', '2025-08-12 22:18:50', NULL, NULL),
(8, 'Vu Thi F', 'vuthif@example.com', '$2y$12$TWtpkQD8.TwJgDGUyQfq2uwBhR3IA6uYSunB8tQWt3tBAbCr9gJ42', NULL, '0906666666', 'profile8.jpg', 1, '1997-12-12', 'female', '2025-08-12 22:18:50', '2025-08-12 22:18:50', NULL, NULL),
(9, 'Do Van G', 'dovang@example.com', '$2y$12$OJXnqdM2o9DEQsRCDn7kce0yngXuvN7XFcIZru6oUwubHKCpW62Yq', NULL, '0907777777', 'profile9.jpg', 1, '1998-04-18', 'male', '2025-08-12 22:18:50', '2025-08-12 22:18:50', NULL, NULL),
(10, 'Bui Thi H', 'buithih@example.com', '$2y$12$V2xf7YBQQrozvtNnGvE74.608o4T20SoGuV97q6Qdcu/nEJEMbtTG', NULL, '0908888888', 'profile10.jpg', 1, '1999-06-25', 'female', '2025-08-12 22:18:50', '2025-08-12 22:18:50', NULL, NULL),
(11, 'Pham Van I', 'phamvani@example.com', '$2y$12$3zohMI625XcOD17i1ntoFOmzgJDg34nnVISKeE2sFWe99pR2czMl2', NULL, '0909999999', 'profile11.jpg', 1, '1992-08-30', 'male', '2025-08-12 22:18:50', '2025-08-12 22:18:50', NULL, NULL),
(12, 'Nguyen Thi K', 'nguyenthik@example.com', '$2y$12$yZLcpedv6KDEN9DyhVus.OJYb1tjRq0HsROpGuay7axrEoqbPBBp2', NULL, '0910000000', 'profile12.jpg', 1, '1993-10-11', 'female', '2025-08-12 22:18:51', '2025-08-12 22:18:51', NULL, NULL),
(13, 'Admin', 'admin@gmail.com', '$2y$12$pKYOtIXOsCSzTmuoCX.kcerYuNEkciHRV8d3qesoxwJyG5xwQzgUi', NULL, '0999999999', 'admin.jpg', 1, '1990-01-01', 'male', '2025-08-12 22:18:51', '2025-08-12 22:18:51', NULL, NULL);

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
(1, 13, NULL, NULL, NULL, '55481 Jaylin Motorway', 'Hoàng Văn Thụ', 'Hoàn Kiếm', 'Hà Nội', 1, '2025-08-12 22:18:51', '2025-08-12 22:18:51', NULL),
(2, 13, NULL, NULL, NULL, '16445 Annamae Point', 'Trúc Bạch', 'Hai Bà Trưng', 'Hà Nội', 0, '2025-08-12 22:18:51', '2025-08-12 22:18:51', NULL),
(3, 13, NULL, NULL, NULL, '910 Macejkovic Heights Suite 294', 'Vĩnh Phúc', 'Ba Đình', 'Hà Nội', 0, '2025-08-12 22:18:51', '2025-08-12 22:18:51', NULL),
(4, 10, NULL, NULL, NULL, '56998 Franecki Lodge', 'Phúc Xá', 'Hoàng Mai', 'Hà Nội', 1, '2025-08-12 22:18:51', '2025-08-12 22:18:51', NULL),
(5, 10, NULL, NULL, NULL, '6959 Ondricka Groves Suite 674', 'Chương Dương', 'Hai Bà Trưng', 'Hà Nội', 0, '2025-08-12 22:18:51', '2025-08-12 22:18:51', NULL),
(6, 10, NULL, NULL, NULL, '95560 Murray Flats', 'Quan Hoa', 'Hoàng Mai', 'Hà Nội', 0, '2025-08-12 22:18:51', '2025-08-12 22:18:51', NULL),
(7, 9, NULL, NULL, NULL, '251 Rolfson Way', 'Ô Chợ Dừa', 'Đống Đa', 'Hà Nội', 1, '2025-08-12 22:18:51', '2025-08-12 22:18:51', NULL),
(8, 9, NULL, NULL, NULL, '6285 Mann Island Apt. 811', 'Yên Sở', 'Hoàng Mai', 'Hà Nội', 0, '2025-08-12 22:18:51', '2025-08-12 22:18:51', NULL),
(9, 9, NULL, NULL, NULL, '4764 Johanna Lodge', 'Yên Hòa', 'Ba Đình', 'Hà Nội', 0, '2025-08-12 22:18:51', '2025-08-12 22:18:51', NULL),
(10, 7, NULL, NULL, NULL, '57927 Natalie Viaduct Suite 595', 'Kim Liên', 'Cầu Giấy', 'Hà Nội', 1, '2025-08-12 22:18:51', '2025-08-12 22:18:51', NULL),
(11, 7, NULL, NULL, NULL, '7883 Arlie Curve', 'Yên Hòa', 'Long Biên', 'Hà Nội', 0, '2025-08-12 22:18:51', '2025-08-12 22:18:51', NULL),
(12, 7, NULL, NULL, NULL, '4756 Durgan Garden', 'Láng Hạ', 'Hoàn Kiếm', 'Hà Nội', 0, '2025-08-12 22:18:51', '2025-08-12 22:18:51', NULL),
(13, 2, NULL, NULL, NULL, '5954 Vicky Mountain', 'Yên Hòa', 'Ba Đình', 'Hà Nội', 1, '2025-08-12 22:18:51', '2025-08-12 22:18:51', NULL),
(14, 2, NULL, NULL, NULL, '8286 Murazik Prairie', 'Hàng Bài', 'Tây Hồ', 'Hà Nội', 0, '2025-08-12 22:18:51', '2025-08-12 22:18:51', NULL),
(15, 2, NULL, NULL, NULL, '4674 Zulauf Ranch', 'Phúc Xá', 'Long Biên', 'Hà Nội', 0, '2025-08-12 22:18:51', '2025-08-12 22:18:51', NULL),
(16, 1, NULL, NULL, NULL, '713 Durward Rapid Apt. 693', 'Vĩnh Phúc', 'Đống Đa', 'Hà Nội', 1, '2025-08-12 22:18:51', '2025-08-12 22:18:51', NULL),
(17, 1, NULL, NULL, NULL, '8191 Monty Mall Suite 882', 'Hàng Trống', 'Hoàn Kiếm', 'Hà Nội', 0, '2025-08-12 22:18:51', '2025-08-12 22:18:51', NULL),
(18, 1, NULL, NULL, NULL, '3405 Lorena Locks Apt. 323', 'Láng Hạ', 'Hoàng Mai', 'Hà Nội', 0, '2025-08-12 22:18:51', '2025-08-12 22:18:51', NULL),
(19, 5, NULL, NULL, NULL, '59724 Smith Trail Apt. 155', 'Yên Hòa', 'Long Biên', 'Hà Nội', 1, '2025-08-12 22:18:51', '2025-08-12 22:18:51', NULL),
(20, 5, NULL, NULL, NULL, '3021 Ron Crest', 'Hoàng Văn Thụ', 'Cầu Giấy', 'Hà Nội', 0, '2025-08-12 22:18:51', '2025-08-12 22:18:51', NULL),
(21, 5, NULL, NULL, NULL, '2351 Lynch Run Apt. 647', 'Ô Chợ Dừa', 'Hai Bà Trưng', 'Hà Nội', 0, '2025-08-12 22:18:51', '2025-08-12 22:18:51', NULL),
(22, 12, NULL, NULL, NULL, '26243 Lazaro Summit', 'Trúc Bạch', 'Hai Bà Trưng', 'Hà Nội', 1, '2025-08-12 22:18:51', '2025-08-12 22:18:51', NULL),
(23, 12, NULL, NULL, NULL, '5974 Mertz Plains Suite 509', 'Láng Hạ', 'Hoàn Kiếm', 'Hà Nội', 0, '2025-08-12 22:18:51', '2025-08-12 22:18:51', NULL),
(24, 12, NULL, NULL, NULL, '2585 O\'Conner Road Suite 816', 'Trúc Bạch', 'Long Biên', 'Hà Nội', 0, '2025-08-12 22:18:51', '2025-08-12 22:18:51', NULL),
(25, 3, NULL, NULL, NULL, '7673 Price Field', 'Tân Mai', 'Cầu Giấy', 'Hà Nội', 1, '2025-08-12 22:18:51', '2025-08-12 22:18:51', NULL),
(26, 3, NULL, NULL, NULL, '19208 Grant Light', 'Giáp Bát', 'Thanh Xuân', 'Hà Nội', 0, '2025-08-12 22:18:51', '2025-08-12 22:18:51', NULL),
(27, 3, NULL, NULL, NULL, '40192 William Rapid Suite 795', 'Thổ Quan', 'Hoàn Kiếm', 'Hà Nội', 0, '2025-08-12 22:18:51', '2025-08-12 22:18:51', NULL),
(28, 6, NULL, NULL, NULL, '45521 Gorczany Forest', 'Dịch Vọng', 'Hai Bà Trưng', 'Hà Nội', 1, '2025-08-12 22:18:51', '2025-08-12 22:18:51', NULL),
(29, 6, NULL, NULL, NULL, '44420 Modesto Shores Suite 407', 'Thổ Quan', 'Hoàn Kiếm', 'Hà Nội', 0, '2025-08-12 22:18:51', '2025-08-12 22:18:51', NULL),
(30, 6, NULL, NULL, NULL, '7353 Cronin Stravenue Apt. 890', 'Yên Hòa', 'Long Biên', 'Hà Nội', 0, '2025-08-12 22:18:51', '2025-08-12 22:18:51', NULL),
(31, 11, NULL, NULL, NULL, '93414 Tracey Plaza Apt. 455', 'Chương Dương', 'Đống Đa', 'Hà Nội', 1, '2025-08-12 22:18:51', '2025-08-12 22:18:51', NULL),
(32, 11, NULL, NULL, NULL, '98858 Vesta Motorway Suite 108', 'Dịch Vọng', 'Hoàng Mai', 'Hà Nội', 0, '2025-08-12 22:18:51', '2025-08-12 22:18:51', NULL),
(33, 11, NULL, NULL, NULL, '364 Runolfsdottir View', 'Điện Biên', 'Đống Đa', 'Hà Nội', 0, '2025-08-12 22:18:51', '2025-08-12 22:18:51', NULL),
(34, 4, NULL, NULL, NULL, '14459 Harry Freeway', 'Phúc Tân', 'Tây Hồ', 'Hà Nội', 1, '2025-08-12 22:18:51', '2025-08-12 22:18:51', NULL),
(35, 4, NULL, NULL, NULL, '9796 Sipes Inlet Suite 484', 'Quan Hoa', 'Hoàn Kiếm', 'Hà Nội', 0, '2025-08-12 22:18:51', '2025-08-12 22:18:51', NULL),
(36, 4, NULL, NULL, NULL, '454 Sylvan Keys', 'Thổ Quan', 'Thanh Xuân', 'Hà Nội', 0, '2025-08-12 22:18:51', '2025-08-12 22:18:51', NULL),
(37, 8, NULL, NULL, NULL, '876 Alyce Via', 'Nghĩa Tân', 'Tây Hồ', 'Hà Nội', 1, '2025-08-12 22:18:51', '2025-08-12 22:18:51', NULL),
(38, 8, NULL, NULL, NULL, '2156 Trey Shores Suite 937', 'Đội Cấn', 'Cầu Giấy', 'Hà Nội', 0, '2025-08-12 22:18:51', '2025-08-12 22:18:51', NULL),
(39, 8, NULL, NULL, NULL, '35398 Hermiston Loaf Apt. 897', 'Dịch Vọng', 'Hoàn Kiếm', 'Hà Nội', 0, '2025-08-12 22:18:51', '2025-08-12 22:18:51', NULL);

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
  ADD KEY `carts_product_variant_id_foreign` (`product_variant_id`);

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
  ADD KEY `orders_address_id_foreign` (`address_id`);

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
-- AUTO_INCREMENT for table `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=41;

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
  ADD CONSTRAINT `carts_product_variant_id_foreign` FOREIGN KEY (`product_variant_id`) REFERENCES `product_variants` (`id`) ON DELETE SET NULL,
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
