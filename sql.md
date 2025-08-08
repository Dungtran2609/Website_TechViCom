-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Aug 07, 2025 at 10:20 PM
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
(1, 'Màu sắc', 'mau-sac', 'color', NULL, NULL, '2025-08-07 22:19:27', '2025-08-07 22:19:27'),
(2, 'RAM', 'ram', 'text', NULL, NULL, '2025-08-07 22:19:27', '2025-08-07 22:19:27'),
(3, 'Bộ nhớ trong', 'bo-nho-trong', 'text', NULL, NULL, '2025-08-07 22:19:27', '2025-08-07 22:19:27');

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
(1, 1, 'uploads/banners/banner1.jpg', '2025-07-29 05:19:27', '2025-08-28 05:19:27', 'https://techvicom.vn/', '2025-08-07 22:19:27', '2025-08-07 22:19:27'),
(2, 2, 'uploads/banners/banner2.jpg', '2025-08-03 05:19:27', '2025-09-02 05:19:27', 'https://techvicom.vn/khuyen-mai', '2025-08-07 22:19:27', '2025-08-07 22:19:27'),
(3, 3, 'uploads/banners/banner3.jpg', '2025-08-07 05:19:27', '2025-09-07 05:19:27', NULL, '2025-08-07 22:19:27', '2025-08-07 22:19:27');

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
(1, 'Apple', 'brands/apple.png', 'apple', 'Chuyên các sản phẩm iPhone, MacBook, iPad.', 1, '2025-08-07 22:19:27', '2025-08-07 22:19:27', NULL),
(2, 'Samsung', 'brands/samsung.png', 'samsung', 'Thương hiệu điện thoại Android và thiết bị gia dụng.', 1, '2025-08-07 22:19:27', '2025-08-07 22:19:27', NULL),
(3, 'ASUS', 'brands/asus.png', 'asus', 'Chuyên laptop văn phòng, gaming, bo mạch chủ.', 1, '2025-08-07 22:19:27', '2025-08-07 22:19:27', NULL),
(4, 'Xiaomi', 'brands/xiaomi.png', 'xiaomi', 'Điện thoại thông minh và thiết bị IoT giá rẻ.', 1, '2025-08-07 22:19:27', '2025-08-07 22:19:27', NULL),
(5, 'Dell', 'brands/dell.png', 'dell', 'Laptop doanh nhân và máy chủ hiệu suất cao.', 1, '2025-08-07 22:19:27', '2025-08-07 22:19:27', NULL),
(6, 'HP', 'brands/hp.png', 'hp', 'Thương hiệu máy tính và thiết bị in ấn phổ biến.', 1, '2025-08-07 22:19:27', '2025-08-07 22:19:27', NULL),
(7, 'Lenovo', 'brands/lenovo.png', 'lenovo', 'Máy tính văn phòng, gaming và máy trạm.', 1, '2025-08-07 22:19:27', '2025-08-07 22:19:27', NULL),
(8, 'Sony', 'brands/sony.png', 'sony', 'Thiết bị giải trí, PlayStation và âm thanh cao cấp.', 1, '2025-08-07 22:19:27', '2025-08-07 22:19:27', NULL),
(9, 'MSI', 'brands/msi.png', 'msi', 'Chuyên laptop và linh kiện gaming cao cấp.', 1, '2025-08-07 22:19:27', '2025-08-07 22:19:27', NULL),
(10, 'Acer', 'brands/acer.png', 'acer', 'Laptop học sinh, sinh viên và văn phòng giá rẻ.', 1, '2025-08-07 22:19:27', '2025-08-07 22:19:27', NULL);

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
(1, NULL, 'Laptop', 'laptop', NULL, 1, '2025-08-07 22:19:27', '2025-08-07 22:19:27', NULL),
(2, NULL, 'Điện thoại', 'dien-thoai', NULL, 1, '2025-08-07 22:19:27', '2025-08-07 22:19:27', NULL),
(3, 1, 'Laptop Gaming', 'laptop-gaming', NULL, 1, '2025-08-07 22:19:27', '2025-08-07 22:19:27', NULL),
(4, 1, 'Laptop Văn phòng', 'laptop-van-phong', NULL, 0, '2025-08-07 22:19:27', '2025-08-07 22:19:27', NULL),
(5, 2, 'Điện thoại Apple', 'dien-thoai-apple', NULL, 1, '2025-08-07 22:19:27', '2025-08-07 22:19:27', NULL);

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
(1, 'Nguyễn Văn A', 'vana@example.com', '0909123456', 'Hỏi về sản phẩm', 'Cho tôi hỏi sản phẩm này còn hàng không?', 13, NULL, 'pending', NULL, NULL, 0, '2025-08-07 22:19:27', '2025-08-07 22:19:27'),
(2, 'Trần Thị B', 'thib@example.com', '0911222333', 'Thắc mắc giao hàng', 'Tôi muốn biết khi nào đơn hàng được giao.', 10, NULL, 'pending', NULL, NULL, 1, '2025-08-07 22:19:27', '2025-08-07 22:19:27'),
(3, 'Lê Văn C', 'vanc@example.com', '0922333444', 'Hủy đơn hàng', 'Tôi muốn hủy đơn hàng vừa đặt.', NULL, NULL, 'pending', NULL, NULL, 0, '2025-08-07 22:19:27', '2025-08-07 22:19:27'),
(4, 'Phạm Thị D', 'thid@example.com', '0933444555', 'Phản hồi dịch vụ', 'Dịch vụ chăm sóc khách hàng rất tốt.', NULL, NULL, 'pending', NULL, NULL, 1, '2025-08-07 22:19:27', '2025-08-07 22:19:27'),
(5, 'Đỗ Minh E', 'minhe@example.com', '0944555666', 'Đổi hàng', 'Tôi muốn đổi sản phẩm vì bị lỗi.', 9, NULL, 'pending', NULL, NULL, 0, '2025-08-07 22:19:27', '2025-08-07 22:19:27'),
(6, 'Hoàng Thị F', 'thif@example.com', '0955666777', 'Cần tư vấn', 'Bạn có thể tư vấn giúp tôi sản phẩm phù hợp?', NULL, NULL, 'pending', NULL, NULL, 1, '2025-08-07 22:19:27', '2025-08-07 22:19:27'),
(7, 'Ngô Văn G', 'vang@example.com', '0966777888', 'Góp ý', 'Website của bạn rất dễ sử dụng.', NULL, NULL, 'pending', NULL, NULL, 0, '2025-08-07 22:19:27', '2025-08-07 22:19:27'),
(8, 'Vũ Thị H', 'thih@example.com', '0977888999', 'Thanh toán', 'Tôi muốn đổi phương thức thanh toán.', 7, NULL, 'pending', NULL, NULL, 1, '2025-08-07 22:19:27', '2025-08-07 22:19:27'),
(9, 'Bùi Văn I', 'vani@example.com', '0988999000', 'Khuyến mãi', 'Cửa hàng hiện có chương trình khuyến mãi nào?', NULL, NULL, 'pending', NULL, NULL, 0, '2025-08-07 22:19:27', '2025-08-07 22:19:27'),
(10, 'Lý Thị K', 'thik@example.com', '0999000111', 'Đặt hàng lỗi', 'Tôi không thể đặt hàng trên website.', 2, NULL, 'pending', NULL, NULL, 1, '2025-08-07 22:19:27', '2025-08-07 22:19:27');

-- --------------------------------------------------------

--
-- Table structure for table `coupons`
--

CREATE TABLE `coupons` (
  `id` bigint UNSIGNED NOT NULL,
  `code` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `discount_type` enum('percent','fixed') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'percent',
  `value` decimal(10,2) NOT NULL,
  `max_discount_amount` decimal(10,2) DEFAULT NULL,
  `min_order_value` decimal(10,2) DEFAULT NULL,
  `max_order_value` decimal(10,2) DEFAULT NULL,
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
(1, 'DISCOUNT10', 'percent', '10.00', '100000.00', '500000.00', '5000000.00', 5, '2025-07-29', '2025-09-08', 1, NULL, NULL, NULL),
(2, 'SALE50', 'percent', '50.00', '100000.00', '200000.00', '1000000.00', 2, '2025-08-07', '2025-09-07', 1, NULL, NULL, NULL),
(3, 'SALE100', 'percent', '50.00', '100000.00', '200000.00', '1000000.00', 2, '2025-08-07', '2025-09-07', 1, NULL, NULL, NULL);

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
(36, '2025_08_08_013142_create_spatie_pivot_tables', 1);

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
(1, 1, 'Giảm giá 50% cho đơn hàng đầu tiên', 'Hãy nhanh tay nhận ưu đãi 50% khi mua hàng lần đầu tiên tại cửa hàng chúng tôi.', 'uploads/news/default.jpg', 10, 'published', '2025-08-07 22:19:27', '2025-08-07 22:19:27', '2025-08-07 22:19:27', NULL),
(2, 1, 'Mua 1 tặng 1 cuối tuần', 'Chương trình mua 1 tặng 1 áp dụng từ thứ 6 đến chủ nhật hàng tuần.', 'uploads/news/default.jpg', 9, 'published', '2025-08-07 22:19:27', '2025-08-07 22:19:27', '2025-08-07 22:19:27', NULL),
(3, 2, 'iPhone 15 chính thức ra mắt', 'Apple đã giới thiệu iPhone 15 với nhiều cải tiến về hiệu năng và camera.', 'uploads/news/default.jpg', 13, 'published', '2025-08-07 22:19:27', '2025-08-07 22:19:27', '2025-08-07 22:19:27', NULL),
(4, 2, 'Samsung trình làng Galaxy Z Flip6', 'Samsung tiếp tục đẩy mạnh phân khúc điện thoại gập với Galaxy Z Flip6.', 'uploads/news/default.jpg', 7, 'published', '2025-08-07 22:19:27', '2025-08-07 22:19:27', '2025-08-07 22:19:27', NULL),
(5, 3, 'Hướng dẫn sử dụng máy ép chậm', 'Bài viết sẽ giúp bạn hiểu rõ cách sử dụng máy ép chậm để giữ nguyên dưỡng chất.', 'uploads/news/default.jpg', 9, 'published', '2025-08-07 22:19:27', '2025-08-07 22:19:27', '2025-08-07 22:19:27', NULL),
(6, 3, 'Cách bảo quản tai nghe không dây', 'Giữ gìn tai nghe đúng cách giúp kéo dài tuổi thọ và giữ âm thanh tốt.', 'uploads/news/default.jpg', 2, 'published', '2025-08-07 22:19:27', '2025-08-07 22:19:27', '2025-08-07 22:19:27', NULL),
(7, 4, 'Đánh giá laptop Asus Zenbook 14', 'Asus Zenbook 14 nổi bật với thiết kế mỏng nhẹ, pin trâu và hiệu năng ổn định.', 'uploads/news/default.jpg', 2, 'published', '2025-08-07 22:19:27', '2025-08-07 22:19:27', '2025-08-07 22:19:27', NULL),
(8, 4, 'So sánh Xiaomi Redmi Note 12 và Realme 11', 'Cùng so sánh hai sản phẩm tầm trung hot nhất hiện nay.', 'uploads/news/default.jpg', 9, 'published', '2025-08-07 22:19:27', '2025-08-07 22:19:27', '2025-08-07 22:19:27', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `news_categories`
--

CREATE TABLE `news_categories` (
  `category_id` bigint UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `slug` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `news_categories`
--

INSERT INTO `news_categories` (`category_id`, `name`, `slug`, `created_at`, `updated_at`) VALUES
(1, 'Khuyến mãi', 'khuyen-mai', '2025-08-07 22:19:27', '2025-08-07 22:19:27'),
(2, 'Tin tức công nghệ', 'tin-tuc-cong-nghe', '2025-08-07 22:19:27', '2025-08-07 22:19:27'),
(3, 'Hướng dẫn sử dụng sản phẩm', 'huong-dan-su-dung-san-pham', '2025-08-07 22:19:27', '2025-08-07 22:19:27'),
(4, 'Đánh giá sản phẩm', 'danh-gia-san-pham', '2025-08-07 22:19:27', '2025-08-07 22:19:27'),
(5, 'Mẹo vặt công nghệ', 'meo-vat-cong-nghe', '2025-08-07 22:19:27', '2025-08-07 22:19:27'),
(6, 'Sự kiện và ra mắt sản phẩm', 'su-kien-ra-mat-san-pham', '2025-08-07 22:19:27', '2025-08-07 22:19:27'),
(7, 'Review cửa hàng', 'review-cua-hang', '2025-08-07 22:19:27', '2025-08-07 22:19:27'),
(8, 'Chăm sóc khách hàng', 'cham-soc-khach-hang', '2025-08-07 22:19:27', '2025-08-07 22:19:27'),
(9, 'Mua sắm trực tuyến', 'mua-sam-truc-tuyen', '2025-08-07 22:19:27', '2025-08-07 22:19:27'),
(10, 'Sản phẩm mới', 'san-pham-moi', '2025-08-07 22:19:27', '2025-08-07 22:19:27');

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
(1, 9, 1, NULL, 'Mình sẽ giới thiệu bài viết này cho bạn bè.', 1, 9, '2025-08-07 22:19:27', '2025-08-07 22:19:27'),
(2, 13, 1, 1, '↪ Mình sẽ giới thiệu bài viết này cho bạn bè.', 1, 5, '2025-08-07 22:19:27', '2025-08-07 22:19:27'),
(3, 7, 1, NULL, 'Rất thích nội dung kiểu này.', 0, 3, '2025-08-07 22:19:27', '2025-08-07 22:19:27'),
(4, 7, 1, NULL, 'Thông tin chi tiết và rõ ràng.', 0, 6, '2025-08-07 22:19:27', '2025-08-07 22:19:27'),
(5, 7, 1, NULL, 'Có thể giải thích thêm phần này được không?', 0, 5, '2025-08-07 22:19:27', '2025-08-07 22:19:27'),
(6, 10, 1, 5, '↪ Tôi đã áp dụng và thấy hiệu quả ngay.', 0, 4, '2025-08-07 22:19:27', '2025-08-07 22:19:27'),
(7, 13, 1, NULL, 'Sản phẩm này mình đã dùng, rất ok.', 0, 2, '2025-08-07 22:19:27', '2025-08-07 22:19:27'),
(8, 7, 2, NULL, 'Rất thích nội dung kiểu này.', 0, 6, '2025-08-07 22:19:27', '2025-08-07 22:19:27'),
(9, 10, 2, 8, '↪ Bài viết hay nhưng nên bổ sung thêm ví dụ.', 0, 0, '2025-08-07 22:19:27', '2025-08-07 22:19:27'),
(10, 7, 2, 8, '↪ Thông tin chi tiết và rõ ràng.', 0, 5, '2025-08-07 22:19:27', '2025-08-07 22:19:27'),
(11, 9, 2, NULL, 'Bài viết hay nhưng nên bổ sung thêm ví dụ.', 1, 2, '2025-08-07 22:19:27', '2025-08-07 22:19:27'),
(12, 13, 2, 11, '↪ Cảm ơn bạn đã chia sẻ!', 0, 5, '2025-08-07 22:19:27', '2025-08-07 22:19:27'),
(13, 2, 2, 11, '↪ Rất mong có thêm bài viết tương tự.', 0, 1, '2025-08-07 22:19:27', '2025-08-07 22:19:27'),
(14, 9, 2, NULL, 'Rất mong có thêm bài viết tương tự.', 1, 0, '2025-08-07 22:19:27', '2025-08-07 22:19:27'),
(15, 7, 2, 14, '↪ Bài viết hay nhưng nên bổ sung thêm ví dụ.', 1, 2, '2025-08-07 22:19:27', '2025-08-07 22:19:27'),
(16, 9, 2, 14, '↪ Bài viết hay nhưng nên bổ sung thêm ví dụ.', 1, 3, '2025-08-07 22:19:27', '2025-08-07 22:19:27'),
(17, 7, 3, NULL, 'Có thể giải thích thêm phần này được không?', 0, 2, '2025-08-07 22:19:27', '2025-08-07 22:19:27'),
(18, 2, 3, NULL, 'Cảm ơn bạn đã chia sẻ!', 0, 4, '2025-08-07 22:19:27', '2025-08-07 22:19:27'),
(19, 13, 3, NULL, 'Có thể giải thích thêm phần này được không?', 0, 5, '2025-08-07 22:19:27', '2025-08-07 22:19:27'),
(20, 9, 3, 19, '↪ Mình sẽ giới thiệu bài viết này cho bạn bè.', 1, 0, '2025-08-07 22:19:27', '2025-08-07 22:19:27'),
(21, 2, 3, 19, '↪ Tôi đã áp dụng và thấy hiệu quả ngay.', 1, 0, '2025-08-07 22:19:27', '2025-08-07 22:19:27'),
(22, 13, 3, NULL, 'Mình sẽ giới thiệu bài viết này cho bạn bè.', 0, 0, '2025-08-07 22:19:27', '2025-08-07 22:19:27'),
(23, 2, 4, NULL, 'Rất thích nội dung kiểu này.', 0, 3, '2025-08-07 22:19:27', '2025-08-07 22:19:27'),
(24, 9, 4, 23, '↪ Rất mong có thêm bài viết tương tự.', 0, 1, '2025-08-07 22:19:27', '2025-08-07 22:19:27'),
(25, 10, 4, 23, '↪ Tôi đã áp dụng và thấy hiệu quả ngay.', 1, 5, '2025-08-07 22:19:27', '2025-08-07 22:19:27'),
(26, 7, 4, NULL, 'Rất mong có thêm bài viết tương tự.', 1, 10, '2025-08-07 22:19:27', '2025-08-07 22:19:27'),
(27, 2, 4, NULL, 'Rất thích nội dung kiểu này.', 0, 10, '2025-08-07 22:19:27', '2025-08-07 22:19:27'),
(28, 2, 5, NULL, 'Bài viết rất hữu ích, cảm ơn bạn!', 1, 0, '2025-08-07 22:19:27', '2025-08-07 22:19:27'),
(29, 10, 5, NULL, 'Rất mong có thêm bài viết tương tự.', 1, 2, '2025-08-07 22:19:27', '2025-08-07 22:19:27'),
(30, 13, 5, NULL, 'Tôi đã áp dụng và thấy hiệu quả ngay.', 0, 10, '2025-08-07 22:19:27', '2025-08-07 22:19:27'),
(31, 7, 5, NULL, 'Bài viết rất hữu ích, cảm ơn bạn!', 1, 0, '2025-08-07 22:19:27', '2025-08-07 22:19:27'),
(32, 2, 5, 31, '↪ Sản phẩm này mình đã dùng, rất ok.', 0, 0, '2025-08-07 22:19:27', '2025-08-07 22:19:27'),
(33, 9, 5, 31, '↪ Bài viết hay nhưng nên bổ sung thêm ví dụ.', 1, 5, '2025-08-07 22:19:27', '2025-08-07 22:19:27'),
(34, 2, 6, NULL, 'Có thể giải thích thêm phần này được không?', 1, 9, '2025-08-07 22:19:27', '2025-08-07 22:19:27'),
(35, 10, 6, NULL, 'Rất thích nội dung kiểu này.', 0, 0, '2025-08-07 22:19:27', '2025-08-07 22:19:27'),
(36, 13, 6, 35, '↪ Cảm ơn bạn đã chia sẻ!', 0, 2, '2025-08-07 22:19:27', '2025-08-07 22:19:27'),
(37, 2, 6, 35, '↪ Mình sẽ giới thiệu bài viết này cho bạn bè.', 0, 4, '2025-08-07 22:19:27', '2025-08-07 22:19:27'),
(38, 13, 6, NULL, 'Tôi đã áp dụng và thấy hiệu quả ngay.', 1, 7, '2025-08-07 22:19:27', '2025-08-07 22:19:27'),
(39, 13, 6, NULL, 'Thông tin chi tiết và rõ ràng.', 0, 4, '2025-08-07 22:19:27', '2025-08-07 22:19:27'),
(40, 7, 6, 39, '↪ Sản phẩm này mình đã dùng, rất ok.', 1, 0, '2025-08-07 22:19:27', '2025-08-07 22:19:27'),
(41, 9, 6, 39, '↪ Cảm ơn bạn đã chia sẻ!', 1, 3, '2025-08-07 22:19:27', '2025-08-07 22:19:27'),
(42, 7, 7, NULL, 'Tôi đã áp dụng và thấy hiệu quả ngay.', 1, 3, '2025-08-07 22:19:27', '2025-08-07 22:19:27'),
(43, 2, 7, NULL, 'Sản phẩm này mình đã dùng, rất ok.', 0, 9, '2025-08-07 22:19:27', '2025-08-07 22:19:27'),
(44, 10, 7, 43, '↪ Bài viết hay nhưng nên bổ sung thêm ví dụ.', 0, 2, '2025-08-07 22:19:27', '2025-08-07 22:19:27'),
(45, 13, 7, NULL, 'Bài viết rất hữu ích, cảm ơn bạn!', 0, 5, '2025-08-07 22:19:27', '2025-08-07 22:19:27'),
(46, 9, 8, NULL, 'Cảm ơn bạn đã chia sẻ!', 1, 4, '2025-08-07 22:19:27', '2025-08-07 22:19:27'),
(47, 2, 8, 46, '↪ Rất thích nội dung kiểu này.', 1, 2, '2025-08-07 22:19:27', '2025-08-07 22:19:27'),
(48, 7, 8, NULL, 'Bài viết hay nhưng nên bổ sung thêm ví dụ.', 1, 5, '2025-08-07 22:19:27', '2025-08-07 22:19:27'),
(49, 10, 8, 48, '↪ Mình sẽ giới thiệu bài viết này cho bạn bè.', 0, 2, '2025-08-07 22:19:27', '2025-08-07 22:19:27'),
(50, 13, 8, NULL, 'Rất mong có thêm bài viết tương tự.', 0, 4, '2025-08-07 22:19:27', '2025-08-07 22:19:27'),
(51, 13, 8, 50, '↪ Mình sẽ giới thiệu bài viết này cho bạn bè.', 1, 1, '2025-08-07 22:19:27', '2025-08-07 22:19:27'),
(52, 7, 8, NULL, 'Cảm ơn bạn đã chia sẻ!', 0, 7, '2025-08-07 22:19:27', '2025-08-07 22:19:27'),
(53, 9, 8, 52, '↪ Rất mong có thêm bài viết tương tự.', 1, 3, '2025-08-07 22:19:27', '2025-08-07 22:19:27');

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

INSERT INTO `orders` (`id`, `user_id`, `guest_name`, `guest_email`, `guest_phone`, `address_id`, `payment_method`, `coupon_id`, `coupon_code`, `discount_amount`, `shipping_fee`, `total_amount`, `final_total`, `status`, `payment_status`, `recipient_name`, `recipient_phone`, `recipient_address`, `shipped_at`, `created_at`, `updated_at`, `shipping_method_id`, `deleted_at`) VALUES
(1, 12, NULL, NULL, NULL, 10, 'bank_transfer', NULL, NULL, '31.30', '16', '292', '277', 'pending', 'pending', 'Nicholaus Kulas', '952.543.5891', '84076 Sauer Lodge Apt. 064\nEast Lionel, IA 73148', '2025-05-19 23:10:49', '2025-04-18 00:48:31', '2025-08-07 22:19:27', NULL, NULL),
(2, 11, NULL, NULL, NULL, 7, 'bank_transfer', NULL, NULL, '32.55', '9', '440', '416', 'pending', 'pending', 'Marcellus Braun', '+1-947-938-8020', '3413 Nicolas Knoll Suite 028\nSouth Granthaven, AR 16675-0528', '2025-03-30 08:48:19', '2025-03-19 15:27:32', '2025-08-07 22:19:27', 2, NULL),
(3, 13, NULL, NULL, NULL, 33, 'bank_transfer', 3, 'MFMTVJKD', '43.68', '19', '401', '377', 'pending', 'pending', 'Dr. Alexandra Feeney III', '(405) 805-4946', '8064 West Oval Suite 757\nWest Mavis, NY 18830', '2025-07-31 11:09:29', '2025-05-04 04:54:21', '2025-08-07 22:19:27', NULL, NULL),
(4, 6, NULL, NULL, NULL, 29, 'bank_transfer', NULL, NULL, '30.63', '7', '177', '154', 'pending', 'pending', 'Shayne Olson', '463.232.2198', '144 Ortiz Parkways\nLake Dangelohaven, HI 95276', '2025-05-30 22:03:36', '2025-05-01 22:51:21', '2025-08-07 22:19:27', NULL, NULL),
(5, 4, NULL, NULL, NULL, 14, 'bank_transfer', NULL, NULL, '44.31', '9', '376', '341', 'pending', 'pending', 'Miss Reanna Bartell II', '(341) 933-1425', '197 White Drives Suite 051\nZemlakport, FL 52042', NULL, '2025-06-15 01:48:21', '2025-08-07 22:19:27', NULL, NULL),
(6, 3, NULL, NULL, NULL, 16, 'bank_transfer', 3, 'JPEJQ8YA', '46.40', '13', '489', '455', 'pending', 'pending', 'Prof. Marion Littel DDS', '1-248-839-3286', '47172 Nash Burgs\nDickensmouth, VA 19916', NULL, '2025-07-23 04:18:44', '2025-08-07 22:19:27', 16, NULL),
(7, 4, NULL, NULL, NULL, 15, 'bank_transfer', NULL, NULL, '32.15', '11', '140', '119', 'pending', 'pending', 'Lilian Dooley', '+1-223-787-1801', '3215 Walter Springs Suite 648\nSouth Caterina, DE 18707-9727', NULL, '2025-05-27 19:10:52', '2025-08-07 22:19:27', 12, NULL),
(8, 11, NULL, NULL, NULL, 37, 'bank_transfer', NULL, NULL, '44.15', '17', '299', '271', 'pending', 'pending', 'Xavier Hudson', '+1.747.288.7646', '1710 Rene Forges Suite 747\nKossmouth, SC 86151', NULL, '2025-04-17 06:49:14', '2025-08-07 22:19:27', NULL, NULL),
(9, 1, NULL, NULL, NULL, 2, 'paypal', NULL, NULL, '26.51', '10', '429', '412', 'pending', 'pending', 'Keegan Morar', '775.789.3175', '3907 Bailey Shores\nEast Jacey, WY 65730-3293', '2025-07-01 17:59:02', '2025-03-03 05:03:48', '2025-08-07 22:19:27', 3, NULL),
(10, 10, NULL, NULL, NULL, 26, 'bank_transfer', 3, 'HBSMWL6B', '30.23', '10', '140', '120', 'pending', 'pending', 'Armand Smith', '+1.325.213.2775', '480 Dannie Greens Apt. 254\nEast Alexandremouth, IA 66573-9974', '2025-08-01 22:46:38', '2025-07-30 13:43:45', '2025-08-07 22:19:27', 16, NULL),
(11, 11, NULL, NULL, NULL, 38, 'paypal', NULL, NULL, '15.61', '10', '160', '155', 'pending', 'pending', 'Mrs. Cordie Ferry DVM', '(854) 865-0027', '52382 Runolfsdottir Burg\nNorth Mertieburgh, FL 96352', NULL, '2025-06-23 19:41:28', '2025-08-07 22:19:27', 8, NULL),
(12, 13, NULL, NULL, NULL, 1, 'bank_transfer', NULL, NULL, '3.16', '15', '287', '299', 'pending', 'pending', 'Shaun Shanahan', '+12706773887', '36708 Carroll Key\nNew Davonte, SD 75404', NULL, '2025-04-24 19:22:31', '2025-08-07 22:19:27', 1, NULL),
(13, 3, NULL, NULL, NULL, 33, 'bank_transfer', 3, 'P1XJLWVA', '28.76', '11', '182', '164', 'pending', 'pending', 'Prof. Nikita Krajcik', '+1-931-834-0086', '993 Raphael Neck\nNew Daleshire, HI 93778-3008', '2025-07-06 15:33:16', '2025-02-18 16:57:12', '2025-08-07 22:19:27', NULL, NULL),
(14, 2, NULL, NULL, NULL, 37, 'paypal', NULL, NULL, '33.67', '7', '497', '470', 'pending', 'pending', 'Peter Toy', '+1.432.724.3396', '1562 Wisoky Inlet\nMarquardtbury, UT 09674-1886', NULL, '2025-02-13 16:54:04', '2025-08-07 22:19:27', 8, NULL),
(15, 11, NULL, NULL, NULL, 19, 'paypal', NULL, NULL, '15.26', '17', '391', '393', 'pending', 'pending', 'Lon Conroy', '660.819.5688', '1391 McDermott Knolls\nSouth Nannie, NH 67809-9278', NULL, '2025-03-25 04:20:44', '2025-08-07 22:19:27', NULL, NULL),
(16, 2, NULL, NULL, NULL, 2, 'paypal', NULL, NULL, '37.22', '16', '490', '468', 'pending', 'pending', 'Federico Prosacco Sr.', '(580) 510-0582', '128 Mattie Inlet\nKuhicton, MD 88608-5489', '2025-06-11 05:20:00', '2025-05-06 07:05:56', '2025-08-07 22:19:27', NULL, NULL),
(17, 1, NULL, NULL, NULL, 28, 'credit_card', NULL, NULL, '33.04', '20', '112', '99', 'pending', 'pending', 'Art Dach MD', '1-539-718-5944', '92900 Hirthe Haven Apt. 339\nNorth Onie, NY 89282', '2025-07-01 11:56:47', '2025-06-21 22:59:10', '2025-08-07 22:19:27', NULL, NULL),
(18, 6, NULL, NULL, NULL, 18, 'paypal', NULL, NULL, '36.58', '17', '193', '174', 'pending', 'pending', 'Bridgette Schimmel', '+1 (805) 785-5117', '12067 Jacobs Locks Apt. 762\nSouth Lilyanland, TN 23183-0755', NULL, '2025-06-30 15:15:11', '2025-08-07 22:19:27', 16, NULL),
(19, 13, NULL, NULL, NULL, 21, 'bank_transfer', NULL, NULL, '19.29', '12', '90', '82', 'pending', 'pending', 'Erich Koch', '+1.262.221.4473', '77411 Klocko Forges\nWest Owen, WI 37254-0093', '2025-07-27 10:14:28', '2025-06-18 14:24:04', '2025-08-07 22:19:27', 17, NULL),
(20, 3, NULL, NULL, NULL, 3, 'bank_transfer', NULL, NULL, '6.60', '19', '209', '222', 'pending', 'pending', 'Jude Kohler', '757-388-8400', '68391 Dickens Bypass\nSouth Huntermouth, WA 44028-4274', '2025-06-11 23:50:55', '2025-05-04 17:52:21', '2025-08-07 22:19:27', 6, NULL);

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
(1, 19, 6, 3, 'iPhone SE 2024', NULL, 4, '185.44', '741.76', '2025-08-07 22:19:27', '2025-08-07 22:19:27'),
(2, 6, 1, 4, 'Laptop Asus Zenbook 14 OLED', NULL, 1, '41.86', '41.86', '2025-08-07 22:19:27', '2025-08-07 22:19:27'),
(3, 14, 1, 2, 'Laptop Gaming ROG Zephyrus G16', NULL, 4, '37.61', '150.44', '2025-08-07 22:19:27', '2025-08-07 22:19:27'),
(4, 9, 3, 1, 'Điện thoại Flagship XYZ 2025', NULL, 3, '40.88', '122.64', '2025-08-07 22:19:27', '2025-08-07 22:19:27'),
(5, 11, 3, 2, 'Laptop Gaming ROG Zephyrus G16', NULL, 1, '106.60', '106.60', '2025-08-07 22:19:27', '2025-08-07 22:19:27'),
(6, 13, 5, 1, 'Điện thoại Flagship XYZ 2025', NULL, 3, '40.96', '122.88', '2025-08-07 22:19:27', '2025-08-07 22:19:27'),
(7, 6, 2, 3, 'iPhone SE 2024', NULL, 3, '45.05', '135.15', '2025-08-07 22:19:27', '2025-08-07 22:19:27'),
(8, 15, 2, 2, 'Laptop Gaming ROG Zephyrus G16', NULL, 2, '10.90', '21.80', '2025-08-07 22:19:27', '2025-08-07 22:19:27'),
(9, 8, 4, 4, 'Laptop Asus Zenbook 14 OLED', NULL, 1, '92.71', '92.71', '2025-08-07 22:19:27', '2025-08-07 22:19:27'),
(10, 3, 6, 4, 'Laptop Asus Zenbook 14 OLED', NULL, 5, '190.01', '950.05', '2025-08-07 22:19:27', '2025-08-07 22:19:27'),
(11, 9, 3, 4, 'Laptop Asus Zenbook 14 OLED', NULL, 1, '106.11', '106.11', '2025-08-07 22:19:27', '2025-08-07 22:19:27'),
(12, 12, 4, 1, 'Điện thoại Flagship XYZ 2025', NULL, 3, '112.15', '336.45', '2025-08-07 22:19:27', '2025-08-07 22:19:27'),
(13, 2, 1, 4, 'Laptop Asus Zenbook 14 OLED', NULL, 4, '83.18', '332.72', '2025-08-07 22:19:27', '2025-08-07 22:19:27'),
(14, 9, 3, 3, 'iPhone SE 2024', NULL, 2, '28.96', '57.92', '2025-08-07 22:19:27', '2025-08-07 22:19:27'),
(15, 19, 6, 1, 'Điện thoại Flagship XYZ 2025', NULL, 4, '143.47', '573.88', '2025-08-07 22:19:27', '2025-08-07 22:19:27'),
(16, 6, 1, 1, 'Điện thoại Flagship XYZ 2025', NULL, 3, '169.85', '509.55', '2025-08-07 22:19:27', '2025-08-07 22:19:27'),
(17, 12, 6, 4, 'Laptop Asus Zenbook 14 OLED', NULL, 5, '168.16', '840.80', '2025-08-07 22:19:27', '2025-08-07 22:19:27'),
(18, 17, 6, 2, 'Laptop Gaming ROG Zephyrus G16', NULL, 5, '113.08', '565.40', '2025-08-07 22:19:27', '2025-08-07 22:19:27'),
(19, 14, 6, 1, 'Điện thoại Flagship XYZ 2025', NULL, 1, '138.81', '138.81', '2025-08-07 22:19:27', '2025-08-07 22:19:27'),
(20, 13, 2, 2, 'Laptop Gaming ROG Zephyrus G16', NULL, 1, '38.01', '38.01', '2025-08-07 22:19:27', '2025-08-07 22:19:27');

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
(1, 20, 'Không đúng mô tả', 'approved', 'return', '2025-07-12 08:29:46', NULL, 'Voluptatem pariatur et quia odit ut.', 'Est impedit explicabo fuga.', '2025-08-07 22:19:27', '2025-08-07 22:19:27'),
(2, 9, 'Sản phẩm lỗi', 'rejected', 'cancel', '2025-07-14 11:13:05', '2025-07-26 08:29:55', 'Aut quibusdam asperiores cumque non sit aut dolorem.', NULL, '2025-08-07 22:19:27', '2025-08-07 22:19:27'),
(3, 19, 'Sản phẩm lỗi', 'rejected', 'cancel', '2025-07-10 19:34:23', '2025-07-27 18:49:07', 'Pariatur rerum temporibus et veritatis voluptatem eius.', 'Quam maxime qui quia.', '2025-08-07 22:19:27', '2025-08-07 22:19:27'),
(4, 18, 'Không cần nữa', 'approved', 'cancel', '2025-07-31 08:52:19', NULL, NULL, 'Sunt quod voluptas tempora beatae omnis accusamus cumque.', '2025-08-07 22:19:27', '2025-08-07 22:19:27'),
(5, 1, 'Giao nhầm hàng', 'rejected', 'return', '2025-07-09 23:14:05', '2025-07-29 02:45:56', NULL, NULL, '2025-08-07 22:19:27', '2025-08-07 22:19:27'),
(6, 16, 'Giao nhầm hàng', 'rejected', 'return', '2025-08-03 19:57:46', '2025-08-06 07:45:55', NULL, NULL, '2025-08-07 22:19:27', '2025-08-07 22:19:27'),
(7, 20, 'Không đúng mô tả', 'approved', 'return', '2025-07-17 23:23:43', '2025-08-07 04:19:43', 'Maxime et rerum quod quia.', NULL, '2025-08-07 22:19:27', '2025-08-07 22:19:27'),
(8, 10, NULL, 'rejected', 'return', '2025-07-25 16:18:35', '2025-08-06 14:12:40', NULL, 'Fuga minus amet iusto commodi molestiae.', '2025-08-07 22:19:27', '2025-08-07 22:19:27'),
(9, 12, 'Không cần nữa', 'pending', 'return', '2025-07-15 23:22:25', '2025-07-25 11:02:04', NULL, 'Quam et provident suscipit.', '2025-08-07 22:19:27', '2025-08-07 22:19:27'),
(10, 1, 'Không cần nữa', 'rejected', 'cancel', '2025-07-11 06:24:09', NULL, 'Non itaque asperiores enim non.', NULL, '2025-08-07 22:19:27', '2025-08-07 22:19:27'),
(11, 19, 'Không cần nữa', 'pending', 'cancel', '2025-07-28 15:07:43', '2025-08-04 14:50:23', NULL, 'Nihil aut et vel hic rem.', '2025-08-07 22:19:27', '2025-08-07 22:19:27'),
(12, 8, 'Sản phẩm lỗi', 'rejected', 'return', '2025-07-13 16:16:14', NULL, NULL, NULL, '2025-08-07 22:19:27', '2025-08-07 22:19:27'),
(13, 12, 'Sản phẩm lỗi', 'rejected', 'cancel', '2025-07-13 20:30:32', '2025-07-25 10:03:02', 'Odit expedita aut porro ut.', NULL, '2025-08-07 22:19:27', '2025-08-07 22:19:27'),
(14, 10, 'Sản phẩm lỗi', 'pending', 'return', '2025-08-05 06:45:06', '2025-08-06 07:54:33', 'Ullam ducimus dolorem et ut quos.', 'Qui incidunt dicta labore.', '2025-08-07 22:19:27', '2025-08-07 22:19:27'),
(15, 1, 'Không cần nữa', 'pending', 'return', '2025-08-06 20:11:02', '2025-08-06 21:40:37', NULL, 'Id ea et aut consequuntur qui.', '2025-08-07 22:19:27', '2025-08-07 22:19:27'),
(16, 3, 'Sản phẩm lỗi', 'rejected', 'cancel', '2025-08-07 10:14:54', NULL, NULL, 'Corporis iure ratione ea non corrupti natus eius.', '2025-08-07 22:19:27', '2025-08-07 22:19:27'),
(17, 5, 'Không đúng mô tả', 'pending', 'return', '2025-07-12 01:38:08', NULL, NULL, 'Corporis quibusdam consequatur id voluptas.', '2025-08-07 22:19:27', '2025-08-07 22:19:27'),
(18, 2, NULL, 'pending', 'return', '2025-07-29 17:49:20', NULL, 'Rerum sed necessitatibus sunt saepe aut quibusdam consectetur placeat.', 'Distinctio quia dicta rem animi.', '2025-08-07 22:19:27', '2025-08-07 22:19:27'),
(19, 1, 'Giao nhầm hàng', 'rejected', 'return', '2025-08-05 16:12:04', '2025-08-07 16:10:43', NULL, NULL, '2025-08-07 22:19:27', '2025-08-07 22:19:27'),
(20, 4, 'Giao nhầm hàng', 'approved', 'return', '2025-07-17 18:44:53', NULL, 'Voluptates illum perferendis facere temporibus unde.', 'Quasi esse consectetur sit aut sit natus.', '2025-08-07 22:19:27', '2025-08-07 22:19:27');

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
(1, 'view_users', 'Xem danh sách người dùng', '2025-08-07 22:19:27', '2025-08-07 22:19:27', NULL),
(2, 'edit_users', 'Chỉnh sửa người dùng', '2025-08-07 22:19:27', '2025-08-07 22:19:27', NULL),
(3, 'delete_users', 'Xoá người dùng', '2025-08-07 22:19:27', '2025-08-07 22:19:27', NULL),
(4, 'manage_roles', 'Quản lý vai trò', '2025-08-07 22:19:27', '2025-08-07 22:19:27', NULL),
(5, 'manage_content', 'Quản lý nội dung', '2025-08-07 22:19:27', '2025-08-07 22:19:27', NULL),
(6, 'manage_coupons', 'Quản lý mã giảm giá', '2025-08-07 22:19:27', '2025-08-07 22:19:27', NULL);

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
(1, 3, 1, '2025-08-07 22:19:27', '2025-08-07 22:19:27'),
(2, 2, 1, '2025-08-07 22:19:27', '2025-08-07 22:19:27'),
(3, 5, 1, '2025-08-07 22:19:27', '2025-08-07 22:19:27'),
(4, 6, 1, '2025-08-07 22:19:27', '2025-08-07 22:19:27'),
(5, 4, 1, '2025-08-07 22:19:27', '2025-08-07 22:19:27'),
(6, 1, 1, '2025-08-07 22:19:27', '2025-08-07 22:19:27'),
(7, 2, 2, '2025-08-07 22:19:27', '2025-08-07 22:19:27'),
(8, 5, 2, '2025-08-07 22:19:27', '2025-08-07 22:19:27'),
(9, 6, 2, '2025-08-07 22:19:27', '2025-08-07 22:19:27'),
(10, 1, 2, '2025-08-07 22:19:27', '2025-08-07 22:19:27'),
(11, 1, 3, '2025-08-07 22:19:27', '2025-08-07 22:19:27');

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
(1, 5, 1, 'Điện thoại Flagship XYZ 2025', 'dien-thoai-flagship-xyz-2025', 'variable', 'Siêu phẩm công nghệ với màn hình Super Retina và chip A20 Bionic.', 'Chi tiết về các công nghệ đột phá, camera siêu nét và thời lượng pin vượt trội của Điện thoại Flagship XYZ 2025.', NULL, 'active', 1, 1500, '2025-08-07 22:19:27', '2025-08-07 22:19:27', NULL),
(2, 3, 3, 'Laptop Gaming ROG Zephyrus G16', 'laptop-gaming-rog-zephyrus-g16', 'variable', 'Mạnh mẽ trong thân hình mỏng nhẹ, màn hình Nebula HDR tuyệt đỉnh.', 'Trải nghiệm gaming và sáng tạo không giới hạn với CPU Intel Core Ultra 9 và card đồ họa NVIDIA RTX 4080.', NULL, 'active', 1, 950, '2025-08-07 22:19:27', '2025-08-07 22:19:27', NULL),
(3, 5, 1, 'iPhone SE 2024', 'iphone-se-2024', 'simple', 'Sức mạnh đáng kinh ngạc trong một thiết kế nhỏ gọn, quen thuộc.', 'iPhone SE 2024 trang bị chip A17 Bionic mạnh mẽ, kết nối 5G và camera tiên tiến. Một lựa chọn tuyệt vời với mức giá phải chăng.', NULL, 'active', 0, 12500, '2025-08-07 22:19:27', '2025-08-07 22:19:27', NULL),
(4, 3, 3, 'Laptop Asus Zenbook 14 OLED', 'laptop-asus-zenbook-14-oled', 'simple', 'Mỏng nhẹ tinh tế, màn hình OLED 2.8K rực rỡ, chuẩn Intel Evo.', 'Asus Zenbook 14 OLED là sự kết hợp hoàn hảo giữa hiệu năng và tính di động, lý tưởng cho các chuyên gia sáng tạo và doanh nhân năng động.', NULL, 'active', 0, 3100, '2025-08-07 22:19:27', '2025-08-07 22:19:27', NULL);

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
(1, 3, 12, 'Bình luận mẫu số 1 cho sản phẩm.', 4, 'approved', NULL, NULL, '2025-07-26 22:19:27', '2025-08-07 22:19:27'),
(2, 3, 8, 'Bình luận mẫu số 2 cho sản phẩm.', 4, 'approved', NULL, NULL, '2025-07-20 22:19:27', '2025-08-07 22:19:27'),
(3, 4, 2, 'Bình luận mẫu số 3 cho sản phẩm.', 5, 'approved', NULL, NULL, '2025-07-17 22:19:27', '2025-08-07 22:19:27'),
(4, 3, 10, 'Bình luận mẫu số 4 cho sản phẩm.', 3, 'approved', NULL, NULL, '2025-07-15 22:19:27', '2025-08-07 22:19:27'),
(5, 2, 12, 'Bình luận mẫu số 5 cho sản phẩm.', 5, 'approved', NULL, NULL, '2025-07-20 22:19:27', '2025-08-07 22:19:27'),
(6, 4, 6, 'Bình luận mẫu số 6 cho sản phẩm.', 3, 'approved', NULL, NULL, '2025-08-04 22:19:27', '2025-08-07 22:19:27'),
(7, 3, 7, 'Bình luận mẫu số 7 cho sản phẩm.', 4, 'approved', NULL, NULL, '2025-07-28 22:19:27', '2025-08-07 22:19:27'),
(8, 1, 1, 'Bình luận mẫu số 8 cho sản phẩm.', 5, 'approved', NULL, NULL, '2025-07-16 22:19:27', '2025-08-07 22:19:27'),
(9, 2, 4, 'Bình luận mẫu số 9 cho sản phẩm.', 3, 'approved', NULL, NULL, '2025-07-16 22:19:27', '2025-08-07 22:19:27'),
(10, 3, 4, 'Bình luận mẫu số 10 cho sản phẩm.', 5, 'approved', NULL, NULL, '2025-07-08 22:19:27', '2025-08-07 22:19:27'),
(11, 3, 7, 'Trả lời cho bình luận 1', NULL, 'approved', 1, NULL, '2025-07-29 22:19:27', '2025-08-07 22:19:27'),
(12, 4, 1, 'Trả lời cho bình luận 2', NULL, 'approved', 2, NULL, '2025-08-04 22:19:27', '2025-08-07 22:19:27'),
(13, 3, 3, 'Trả lời cho bình luận 3', NULL, 'approved', 3, NULL, '2025-07-28 22:19:27', '2025-08-07 22:19:27');

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
(1, 1, 'DT-XYZ-DO-8G', 25990000, NULL, NULL, NULL, NULL, NULL, NULL, 50, NULL, 1, '2025-08-07 22:19:27', '2025-08-07 22:19:27', NULL),
(2, 1, 'DT-XYZ-XANH-16G', 28990000, NULL, NULL, NULL, NULL, NULL, NULL, 45, NULL, 1, '2025-08-07 22:19:27', '2025-08-07 22:19:27', NULL),
(3, 2, 'ROG-G16-8G', 52000000, NULL, NULL, NULL, NULL, NULL, NULL, 25, NULL, 1, '2025-08-07 22:19:27', '2025-08-07 22:19:27', NULL),
(4, 2, 'ROG-G16-16G', 58500000, NULL, NULL, NULL, NULL, NULL, NULL, 15, NULL, 1, '2025-08-07 22:19:27', '2025-08-07 22:19:27', NULL),
(5, 3, 'IP-SE-2024', 12490000, NULL, NULL, NULL, NULL, NULL, NULL, 400, NULL, 1, '2025-08-07 22:19:27', '2025-08-07 22:19:27', NULL),
(6, 4, 'AS-ZEN14-OLED', 26490000, NULL, NULL, NULL, NULL, NULL, NULL, 80, NULL, 1, '2025-08-07 22:19:27', '2025-08-07 22:19:27', NULL);

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
(6, 4, 7, NULL, NULL);

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
(1, 'admin', 'admin', NULL, '2025-08-07 22:19:27', '2025-08-07 22:19:27'),
(2, 'staff', 'staff', NULL, '2025-08-07 22:19:27', '2025-08-07 22:19:27'),
(3, 'user', 'user', NULL, '2025-08-07 22:19:27', '2025-08-07 22:19:27');

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
(1, 'Giao hàng tận nơi', 'Ut deleniti harum expedita esse vitae doloribus.', '2025-08-07 22:19:27', '2025-08-07 22:19:27'),
(2, 'Nhận hàng tại cửa hàng', NULL, '2025-08-07 22:19:27', '2025-08-07 22:19:27'),
(3, 'Phương thức giao hàng #3', 'Ipsam qui maxime ipsam veniam enim.', '2025-08-07 22:19:27', '2025-08-07 22:19:27'),
(4, 'Phương thức giao hàng #4', 'Autem quis corporis autem assumenda quo quis adipisci.', '2025-08-07 22:19:27', '2025-08-07 22:19:27'),
(5, 'Phương thức giao hàng #5', NULL, '2025-08-07 22:19:27', '2025-08-07 22:19:27'),
(6, 'Phương thức giao hàng #6', NULL, '2025-08-07 22:19:27', '2025-08-07 22:19:27'),
(7, 'Phương thức giao hàng #7', NULL, '2025-08-07 22:19:27', '2025-08-07 22:19:27'),
(8, 'Phương thức giao hàng #8', 'Reprehenderit repudiandae in repudiandae sed ipsam.', '2025-08-07 22:19:27', '2025-08-07 22:19:27'),
(9, 'Phương thức giao hàng #9', NULL, '2025-08-07 22:19:27', '2025-08-07 22:19:27'),
(10, 'Phương thức giao hàng #10', 'Voluptas ipsum soluta quidem ut molestiae.', '2025-08-07 22:19:27', '2025-08-07 22:19:27'),
(11, 'Phương thức giao hàng #11', 'Et id ut quis totam omnis consequatur.', '2025-08-07 22:19:27', '2025-08-07 22:19:27'),
(12, 'Phương thức giao hàng #12', NULL, '2025-08-07 22:19:27', '2025-08-07 22:19:27'),
(13, 'Phương thức giao hàng #13', NULL, '2025-08-07 22:19:27', '2025-08-07 22:19:27'),
(14, 'Phương thức giao hàng #14', 'Blanditiis accusantium dolorem voluptate quas molestiae unde.', '2025-08-07 22:19:27', '2025-08-07 22:19:27'),
(15, 'Phương thức giao hàng #15', 'Aut laboriosam molestiae placeat et nihil et.', '2025-08-07 22:19:27', '2025-08-07 22:19:27'),
(16, 'Phương thức giao hàng #16', 'Eaque iure quos quia in saepe.', '2025-08-07 22:19:27', '2025-08-07 22:19:27'),
(17, 'Phương thức giao hàng #17', NULL, '2025-08-07 22:19:27', '2025-08-07 22:19:27'),
(18, 'Phương thức giao hàng #18', NULL, '2025-08-07 22:19:27', '2025-08-07 22:19:27'),
(19, 'Phương thức giao hàng #19', 'Architecto aut soluta natus tempora consequatur dignissimos velit.', '2025-08-07 22:19:27', '2025-08-07 22:19:27'),
(20, 'Phương thức giao hàng #20', NULL, '2025-08-07 22:19:27', '2025-08-07 22:19:27');

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
(1, 'John Doe', 'johndoe@example.com', '$2y$12$FDzVhJq.W057aKpQSUwCUel5B5qq7oxhdYlaCUSkKLdGKT/VQDKu.', NULL, '123456789', 'profile1.jpg', 1, '1990-01-01', 'male', '2025-08-07 22:19:24', '2025-08-07 22:19:24', NULL, NULL),
(2, 'Jane Smith', 'jane@example.com', '$2y$12$.NyXbL5VrG7KITAio7RDcOpOWmrFT8PF.Fu/.gdZAsYzZXZQXKYY2', NULL, '987654321', 'profile2.jpg', 1, '1992-05-15', 'female', '2025-08-07 22:19:24', '2025-08-07 22:19:24', NULL, NULL),
(3, 'Nguyen Van A', 'nguyenvana@example.com', '$2y$12$jVeplxQ00ZKFLVRP1vXhYeEWzton5nlpu1mtoOkZFA6cFGhYO8p5q', NULL, '0901111111', 'profile3.jpg', 1, '1995-03-10', 'male', '2025-08-07 22:19:24', '2025-08-07 22:19:24', NULL, NULL),
(4, 'Tran Thi B', 'tranthib@example.com', '$2y$12$AJSDyhVRNgxIXJHRe46XKuLaFmhZ7JcX2CRG3bfdVFsZDCXi4ULDy', NULL, '0902222222', 'profile4.jpg', 1, '1996-07-21', 'female', '2025-08-07 22:19:25', '2025-08-07 22:19:25', NULL, NULL),
(5, 'Le Van C', 'levanc@example.com', '$2y$12$8xUrtmy8uXgBVbBFG6eBpO3ppan0a1.fozPBLNaDkePhbaTpoMm2y', NULL, '0903333333', 'profile5.jpg', 1, '1993-11-05', 'male', '2025-08-07 22:19:25', '2025-08-07 22:19:25', NULL, NULL),
(6, 'Pham Thi D', 'phamthid@example.com', '$2y$12$Bx8A3Sht6cua.sJDaB0b9es5Rb5bZB7UKGBFCcTDCqHy6xslGQ/ny', NULL, '0904444444', 'profile6.jpg', 1, '1994-02-14', 'female', '2025-08-07 22:19:25', '2025-08-07 22:19:25', NULL, NULL),
(7, 'Hoang Van E', 'hoangvane@example.com', '$2y$12$EYwskC.7HD4hv8jQ8PgeoebVieQcwW6Ai.THxsubnj8wa0K6NDwBW', NULL, '0905555555', 'profile7.jpg', 1, '1991-09-09', 'male', '2025-08-07 22:19:25', '2025-08-07 22:19:25', NULL, NULL),
(8, 'Vu Thi F', 'vuthif@example.com', '$2y$12$R0TXA9FaxF8Os5D8lpdzb.iMkzFjfAHoXa1c8ufeDd/4fBFk4Y.zi', NULL, '0906666666', 'profile8.jpg', 1, '1997-12-12', 'female', '2025-08-07 22:19:25', '2025-08-07 22:19:25', NULL, NULL),
(9, 'Do Van G', 'dovang@example.com', '$2y$12$kIegGdWOwXtp335gX3kzrO8uwDANQOd7OV55gYtRsCaPn948wtMD2', NULL, '0907777777', 'profile9.jpg', 1, '1998-04-18', 'male', '2025-08-07 22:19:26', '2025-08-07 22:19:26', NULL, NULL),
(10, 'Bui Thi H', 'buithih@example.com', '$2y$12$k1x8.EvoI8J2/NO.UvxNGuGINrLxyS8O/mxd.pvNxKGk/tvqyI0U2', NULL, '0908888888', 'profile10.jpg', 1, '1999-06-25', 'female', '2025-08-07 22:19:26', '2025-08-07 22:19:26', NULL, NULL),
(11, 'Pham Van I', 'phamvani@example.com', '$2y$12$752.hkJNnhE9l6ufUfMJ.etClKKOhm4aX1fI37dlTV6906k.U4xfK', NULL, '0909999999', 'profile11.jpg', 1, '1992-08-30', 'male', '2025-08-07 22:19:26', '2025-08-07 22:19:26', NULL, NULL),
(12, 'Nguyen Thi K', 'nguyenthik@example.com', '$2y$12$bXQIhCD9txqvma3afsqux.F08yi.l4heWeXcxG2LojtnGCdjPfiDi', NULL, '0910000000', 'profile12.jpg', 1, '1993-10-11', 'female', '2025-08-07 22:19:26', '2025-08-07 22:19:26', NULL, NULL),
(13, 'Admin', 'admin@gmail.com', '$2y$12$gK7K4c0ktKXP9rfz8mZb6.CN7TyJTltVHOaripgKRvsUzvrvqNUgq', NULL, '0999999999', 'admin.jpg', 1, '1990-01-01', 'male', '2025-08-07 22:19:27', '2025-08-07 22:19:27', NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `user_addresses`
--

CREATE TABLE `user_addresses` (
  `id` bigint UNSIGNED NOT NULL,
  `user_id` bigint UNSIGNED NOT NULL,
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

INSERT INTO `user_addresses` (`id`, `user_id`, `address_line`, `ward`, `district`, `city`, `is_default`, `created_at`, `updated_at`, `deleted_at`) VALUES
(1, 13, '544 Mante Hollow', 'Ô Chợ Dừa', 'Thanh Xuân', 'Hà Nội', 1, '2025-08-07 22:19:27', '2025-08-07 22:19:27', NULL),
(2, 13, '68712 Gleason Parks', 'Nghĩa Tân', 'Hai Bà Trưng', 'Hà Nội', 0, '2025-08-07 22:19:27', '2025-08-07 22:19:27', NULL),
(3, 13, '482 Jamarcus Ports Suite 401', 'Điện Biên', 'Ba Đình', 'Hà Nội', 0, '2025-08-07 22:19:27', '2025-08-07 22:19:27', NULL),
(4, 10, '6798 Wintheiser Circle Suite 809', 'Láng Hạ', 'Hoàng Mai', 'Hà Nội', 1, '2025-08-07 22:19:27', '2025-08-07 22:19:27', NULL),
(5, 10, '52625 Gleichner Canyon Suite 328', 'Giáp Bát', 'Long Biên', 'Hà Nội', 0, '2025-08-07 22:19:27', '2025-08-07 22:19:27', NULL),
(6, 10, '1502 Altenwerth Drive Apt. 224', 'Phúc Xá', 'Hoàng Mai', 'Hà Nội', 0, '2025-08-07 22:19:27', '2025-08-07 22:19:27', NULL),
(7, 9, '4089 Ludie Islands', 'Chương Dương', 'Long Biên', 'Hà Nội', 1, '2025-08-07 22:19:27', '2025-08-07 22:19:27', NULL),
(8, 9, '614 Davis Gardens', 'Hàng Bài', 'Hoàng Mai', 'Hà Nội', 0, '2025-08-07 22:19:27', '2025-08-07 22:19:27', NULL),
(9, 9, '43907 Howell Port', 'Yên Hòa', 'Hoàn Kiếm', 'Hà Nội', 0, '2025-08-07 22:19:27', '2025-08-07 22:19:27', NULL),
(10, 7, '8105 Mills Brooks Apt. 466', 'Hoàng Văn Thụ', 'Cầu Giấy', 'Hà Nội', 1, '2025-08-07 22:19:27', '2025-08-07 22:19:27', NULL),
(11, 7, '950 Theo Ports Apt. 210', 'Phúc Xá', 'Tây Hồ', 'Hà Nội', 0, '2025-08-07 22:19:27', '2025-08-07 22:19:27', NULL),
(12, 7, '18280 Rodrigo Shores Suite 851', 'Hàng Trống', 'Tây Hồ', 'Hà Nội', 0, '2025-08-07 22:19:27', '2025-08-07 22:19:27', NULL),
(13, 2, '1126 Williamson Tunnel Suite 828', 'Giáp Bát', 'Hoàng Mai', 'Hà Nội', 1, '2025-08-07 22:19:27', '2025-08-07 22:19:27', NULL),
(14, 2, '8556 Kohler Fall Apt. 908', 'Phúc Tân', 'Tây Hồ', 'Hà Nội', 0, '2025-08-07 22:19:27', '2025-08-07 22:19:27', NULL),
(15, 2, '514 Heaney Pass Suite 478', 'Chương Dương', 'Ba Đình', 'Hà Nội', 0, '2025-08-07 22:19:27', '2025-08-07 22:19:27', NULL),
(16, 1, '81451 Beulah Spring', 'Điện Biên', 'Hoàng Mai', 'Hà Nội', 1, '2025-08-07 22:19:27', '2025-08-07 22:19:27', NULL),
(17, 1, '895 Leuschke Lakes', 'Giáp Bát', 'Thanh Xuân', 'Hà Nội', 0, '2025-08-07 22:19:27', '2025-08-07 22:19:27', NULL),
(18, 1, '19965 Rowe River', 'Giáp Bát', 'Hoàn Kiếm', 'Hà Nội', 0, '2025-08-07 22:19:27', '2025-08-07 22:19:27', NULL),
(19, 5, '962 Lowe Manor', 'Quan Hoa', 'Hoàng Mai', 'Hà Nội', 1, '2025-08-07 22:19:27', '2025-08-07 22:19:27', NULL),
(20, 5, '853 Cayla Point Suite 783', 'Vĩnh Phúc', 'Đống Đa', 'Hà Nội', 0, '2025-08-07 22:19:27', '2025-08-07 22:19:27', NULL),
(21, 5, '29019 Bechtelar Circles Apt. 490', 'Đội Cấn', 'Hoàng Mai', 'Hà Nội', 0, '2025-08-07 22:19:27', '2025-08-07 22:19:27', NULL),
(22, 12, '1790 Kutch Drives Apt. 474', 'Thổ Quan', 'Tây Hồ', 'Hà Nội', 1, '2025-08-07 22:19:27', '2025-08-07 22:19:27', NULL),
(23, 12, '238 Dach Walks', 'Ô Chợ Dừa', 'Đống Đa', 'Hà Nội', 0, '2025-08-07 22:19:27', '2025-08-07 22:19:27', NULL),
(24, 12, '49768 Huels Burg', 'Quan Hoa', 'Hoàng Mai', 'Hà Nội', 0, '2025-08-07 22:19:27', '2025-08-07 22:19:27', NULL),
(25, 3, '51819 Kuphal Islands Apt. 807', 'Hàng Trống', 'Cầu Giấy', 'Hà Nội', 1, '2025-08-07 22:19:27', '2025-08-07 22:19:27', NULL),
(26, 3, '9748 Wolff Underpass Suite 618', 'Láng Hạ', 'Cầu Giấy', 'Hà Nội', 0, '2025-08-07 22:19:27', '2025-08-07 22:19:27', NULL),
(27, 3, '9544 Ortiz Route', 'Phúc Xá', 'Cầu Giấy', 'Hà Nội', 0, '2025-08-07 22:19:27', '2025-08-07 22:19:27', NULL),
(28, 6, '68474 Cronin Expressway', 'Hoàng Văn Thụ', 'Thanh Xuân', 'Hà Nội', 1, '2025-08-07 22:19:27', '2025-08-07 22:19:27', NULL),
(29, 6, '63122 Clifton Trafficway', 'Điện Biên', 'Long Biên', 'Hà Nội', 0, '2025-08-07 22:19:27', '2025-08-07 22:19:27', NULL),
(30, 6, '7156 Satterfield Summit Suite 346', 'Phúc Tân', 'Long Biên', 'Hà Nội', 0, '2025-08-07 22:19:27', '2025-08-07 22:19:27', NULL),
(31, 11, '9511 Thaddeus Mountains Suite 261', 'Thổ Quan', 'Hoàng Mai', 'Hà Nội', 1, '2025-08-07 22:19:27', '2025-08-07 22:19:27', NULL),
(32, 11, '74999 Derrick Highway', 'Nghĩa Tân', 'Thanh Xuân', 'Hà Nội', 0, '2025-08-07 22:19:27', '2025-08-07 22:19:27', NULL),
(33, 11, '2928 Willis Ramp Suite 386', 'Yên Sở', 'Cầu Giấy', 'Hà Nội', 0, '2025-08-07 22:19:27', '2025-08-07 22:19:27', NULL),
(34, 4, '2542 Kautzer Views Apt. 868', 'Nghĩa Tân', 'Long Biên', 'Hà Nội', 1, '2025-08-07 22:19:27', '2025-08-07 22:19:27', NULL),
(35, 4, '9142 Terrell Underpass Suite 218', 'Vĩnh Phúc', 'Ba Đình', 'Hà Nội', 0, '2025-08-07 22:19:27', '2025-08-07 22:19:27', NULL),
(36, 4, '2815 Hellen Crossroad', 'Đội Cấn', 'Hoàng Mai', 'Hà Nội', 0, '2025-08-07 22:19:27', '2025-08-07 22:19:27', NULL),
(37, 8, '39759 Fletcher Causeway Apt. 509', 'Trúc Bạch', 'Tây Hồ', 'Hà Nội', 1, '2025-08-07 22:19:27', '2025-08-07 22:19:27', NULL),
(38, 8, '500 Greyson Circles', 'Hàng Bài', 'Ba Đình', 'Hà Nội', 0, '2025-08-07 22:19:27', '2025-08-07 22:19:27', NULL),
(39, 8, '9088 Luz Spur Suite 345', 'Ô Chợ Dừa', 'Hoàng Mai', 'Hà Nội', 0, '2025-08-07 22:19:27', '2025-08-07 22:19:27', NULL);

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
  ADD PRIMARY KEY (`category_id`),
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
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `contacts`
--
ALTER TABLE `contacts`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `coupons`
--
ALTER TABLE `coupons`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

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
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=37;

--
-- AUTO_INCREMENT for table `news`
--
ALTER TABLE `news`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `news_categories`
--
ALTER TABLE `news_categories`
  MODIFY `category_id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `news_comments`
--
ALTER TABLE `news_comments`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=54;

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
-- AUTO_INCREMENT for table `products`
--
ALTER TABLE `products`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

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
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `product_variant_attribute_values`
--
ALTER TABLE `product_variant_attribute_values`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

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
