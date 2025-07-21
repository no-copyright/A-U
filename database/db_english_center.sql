-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Máy chủ: 127.0.0.1
-- Thời gian đã tạo: Th7 21, 2025 lúc 03:51 PM
-- Phiên bản máy phục vụ: 10.4.32-MariaDB
-- Phiên bản PHP: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Cơ sở dữ liệu: `db_english_center`
--

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `cache`
--

CREATE TABLE `cache` (
  `key` varchar(255) NOT NULL,
  `value` mediumtext NOT NULL,
  `expiration` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `cache_locks`
--

CREATE TABLE `cache_locks` (
  `key` varchar(255) NOT NULL,
  `owner` varchar(255) NOT NULL,
  `expiration` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `categories`
--

CREATE TABLE `categories` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `slug` varchar(255) NOT NULL,
  `count` int(11) NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `categories`
--

INSERT INTO `categories` (`id`, `name`, `slug`, `count`, `created_at`, `updated_at`) VALUES
(1, 'Kiến thức và kinh nghiệm', 'kien-thuc-va-kinh-nghiem-1', 3, '2025-07-21 03:53:45', '2025-07-21 03:53:45');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `contact`
--

CREATE TABLE `contact` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `address` longtext NOT NULL,
  `phone` varchar(10) NOT NULL,
  `email` varchar(50) NOT NULL,
  `facebook` varchar(50) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `contact`
--

INSERT INTO `contact` (`id`, `address`, `phone`, `email`, `facebook`, `created_at`, `updated_at`) VALUES
(1, '[{\"address\":\"AU L\\u1ea1ng Giang: S\\u1ed1 50.51 khu HDB, t\\u1ed5 d\\u00e2n ph\\u1ed1 To\\u00e0n M\\u1ef9, x\\u00e3 L\\u1ea1ng Giang, t\\u1ec9nh B\\u1eafc Giang.\",\"googlemap\":\"<iframe src=\\\"https:\\/\\/www.google.com\\/maps\\/embed?pb=!1m14!1m8!1m3!1d931.2863620581257!2d105.791486!3d20.986806!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x3135acc8fed35757%3A0x3cd47ebed3bc2642!2zTmcuNjcgxJAuIFBow7luZyBLaG9hbmcsIFRydW5nIFbEg24sIEjDoCBO4buZaSwgVmnhu4d0IE5hbQ!5e0!3m2!1svi!2sus!4v1753104857107!5m2!1svi!2sus\\\" width=\\\"600\\\" height=\\\"450\\\" style=\\\"border:0;\\\" allowfullscreen=\\\"\\\" loading=\\\"lazy\\\" referrerpolicy=\\\"no-referrer-when-downgrade\\\"><\\/iframe>\"},{\"address\":\"AU B\\u1eafc Giang: T\\u1ea7ng 1, nh\\u00e0 B, k\\u00ed t\\u00fac x\\u00e1 sinh vi\\u00ean, \\u0111\\u01b0\\u1eddng Ho\\u00e0ng V\\u0103n Th\\u1ee5, ph\\u01b0\\u1eddng B\\u1eafc Giang, t\\u1ec9nh B\\u1eafc Giang\",\"googlemap\":\"<iframe src=\\\"https:\\/\\/www.google.com\\/maps\\/embed?pb=!1m14!1m8!1m3!1d7447.852086327752!2d105.785161!3d21.035645!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x3135ab4a61a3d107%3A0xceea507e2cc48336!2zVHJ1bmcgdMOibSBuZ2_huqFpIG5n4buvIEEmVSBMYW5ndWFnZSBJbnN0aXR1dGUgKFNhdSB0b8OgIFBpY28p!5e0!3m2!1svi!2sus!4v1753104887940!5m2!1svi!2sus\\\" width=\\\"600\\\" height=\\\"450\\\" style=\\\"border:0;\\\" allowfullscreen=\\\"\\\" loading=\\\"lazy\\\" referrerpolicy=\\\"no-referrer-when-downgrade\\\"><\\/iframe>\"}]', '0986xxxxxx', 'admin@gmail.com', 'https://web.facebook.com', NULL, '2025-07-21 06:35:08');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `customers`
--

CREATE TABLE `customers` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `training_id` bigint(20) UNSIGNED DEFAULT NULL,
  `full_name_parent` varchar(255) NOT NULL,
  `phone` varchar(10) NOT NULL,
  `email` varchar(50) NOT NULL,
  `full_name_children` varchar(255) NOT NULL,
  `status` enum('pending','confirmed','cancelled') NOT NULL DEFAULT 'pending',
  `date_of_birth` date NOT NULL,
  `address` text NOT NULL,
  `note` longtext DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `document`
--

CREATE TABLE `document` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `priority` int(11) NOT NULL DEFAULT 99,
  `name` varchar(50) NOT NULL,
  `src` text NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `failed_jobs`
--

CREATE TABLE `failed_jobs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `uuid` varchar(255) NOT NULL,
  `connection` text NOT NULL,
  `queue` text NOT NULL,
  `payload` longtext NOT NULL,
  `exception` longtext NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `home_page`
--

CREATE TABLE `home_page` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `banners` text NOT NULL,
  `stats` text DEFAULT NULL,
  `fags` text DEFAULT NULL,
  `images` text DEFAULT NULL,
  `link_youtubes` text NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `home_page`
--

INSERT INTO `home_page` (`id`, `banners`, `stats`, `fags`, `images`, `link_youtubes`, `created_at`, `updated_at`) VALUES
(1, '{\"title\":\"Kh\\u01a1i d\\u1eady ti\\u1ec1m n\\u0103ng, v\\u1eefng b\\u01b0\\u1edbc t\\u01b0\\u01a1ng lai c\\u00f9ng AU English\",\"description\":\"M\\u00f4i tr\\u01b0\\u1eddng h\\u1ecdc t\\u1eadp chu\\u1ea9n qu\\u1ed1c t\\u1ebf, gi\\u00fap con t\\u1ef1 tin giao ti\\u1ebfp v\\u00e0 chinh ph\\u1ee5c c\\u00e1c k\\u1ef3 thi.\",\"images\":[\"\\/userfiles\\/images\\/R5AT4211.jpg\",\"\\/userfiles\\/images\\/R5AT4212.jpg\",\"\\/userfiles\\/images\\/R5AT4215.jpg\"]}', '[{\"value\":\"10\",\"description\":\"N\\u0103m kinh nghi\\u1ec7m\",\"images\":\"\\/userfiles\\/images\\/R5AT4219.jpg\"},{\"value\":\"30\",\"description\":\"Gi\\u00e1o vi\\u00ean \\u01b0u t\\u00fa\",\"images\":\"\\/userfiles\\/images\\/R5AT4222.jpg\"}]', '[{\"question\":\"Trung t\\u00e2m c\\u00f3 l\\u1edbp h\\u1ecdc th\\u1eed mi\\u1ec5n ph\\u00ed kh\\u00f4ng?\",\"answer\":\"C\\u00f3, ch\\u00fang t\\u00f4i c\\u00f3 c\\u00e1c bu\\u1ed5i h\\u1ecdc th\\u1eed \\u0111\\u1ecbnh k\\u1ef3. Vui l\\u00f2ng \\u0111\\u1ec3 l\\u1ea1i th\\u00f4ng tin \\u0111\\u1ec3 \\u0111\\u01b0\\u1ee3c t\\u01b0 v\\u1ea5n l\\u1ecbch h\\u1ecdc g\\u1ea7n nh\\u1ea5t.\"},{\"question\":\"L\\u1ed9 tr\\u00ecnh h\\u1ecdc cho b\\u00e9 \\u0111\\u01b0\\u1ee3c x\\u00e2y d\\u1ef1ng nh\\u01b0 th\\u1ebf n\\u00e0o?\",\"answer\":\"M\\u1ed7i h\\u1ecdc vi\\u00ean s\\u1ebd \\u0111\\u01b0\\u1ee3c ki\\u1ec3m tra \\u0111\\u1ea7u v\\u00e0o v\\u00e0 t\\u01b0 v\\u1ea5n l\\u1ed9 tr\\u00ecnh c\\u00e1 nh\\u00e2n h\\u00f3a \\u0111\\u1ec3 \\u0111\\u1ea3m b\\u1ea3o hi\\u1ec7u qu\\u1ea3 h\\u1ecdc t\\u1eadp t\\u1ed1t nh\\u1ea5t.\"},{\"question\":\"\\u0110\\u1ed9i ng\\u0169 gi\\u00e1o vi\\u00ean c\\u1ee7a trung t\\u00e2m c\\u00f3 tr\\u00ecnh \\u0111\\u1ed9 nh\\u01b0 th\\u1ebf n\\u00e0o?\",\"answer\":\"100% gi\\u00e1o vi\\u00ean t\\u1ea1i AU English c\\u00f3 b\\u1eb1ng c\\u1ea5p s\\u01b0 ph\\u1ea1m, ch\\u1ee9ng ch\\u1ec9 gi\\u1ea3ng d\\u1ea1y qu\\u1ed1c t\\u1ebf (TESOL\\/IELTS) v\\u00e0 nhi\\u1ec1u n\\u0103m kinh nghi\\u1ec7m.\"}]', '[\"\\/userfiles\\/images\\/R5AT4240.jpg\",\"\\/userfiles\\/images\\/R5AT4246.jpg\",\"\\/userfiles\\/images\\/R5AT4255.jpg\"]', '[\"https:\\/\\/youtu.be\\/fXXcJJENN9U\",\"https:\\/\\/youtu.be\\/BaR4iCqJFWk\"]', '2025-07-21 03:53:45', '2025-07-21 05:40:36');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `jobs`
--

CREATE TABLE `jobs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `queue` varchar(255) NOT NULL,
  `payload` longtext NOT NULL,
  `attempts` tinyint(3) UNSIGNED NOT NULL,
  `reserved_at` int(10) UNSIGNED DEFAULT NULL,
  `available_at` int(10) UNSIGNED NOT NULL,
  `created_at` int(10) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `job_batches`
--

CREATE TABLE `job_batches` (
  `id` varchar(255) NOT NULL,
  `name` varchar(255) NOT NULL,
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
-- Cấu trúc bảng cho bảng `migrations`
--

CREATE TABLE `migrations` (
  `id` int(10) UNSIGNED NOT NULL,
  `migration` varchar(255) NOT NULL,
  `batch` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `migrations`
--

INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES
(1, '0001_01_01_000000_create_users_table', 1),
(2, '0001_01_01_000001_create_cache_table', 1),
(3, '0001_01_01_000002_create_jobs_table', 1),
(4, '2025_03_26_142803_create_kingexpressbus_schemas', 1);

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `news`
--

CREATE TABLE `news` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `slug` varchar(255) NOT NULL,
  `title` varchar(255) NOT NULL,
  `excerpt` text NOT NULL,
  `thumbnail` varchar(255) NOT NULL,
  `author` varchar(255) NOT NULL,
  `view` int(11) NOT NULL DEFAULT 0,
  `category_id` bigint(20) UNSIGNED NOT NULL,
  `content` longtext NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `news`
--

INSERT INTO `news` (`id`, `slug`, `title`, `excerpt`, `thumbnail`, `author`, `view`, `category_id`, `content`, `created_at`, `updated_at`) VALUES
(1, 'bi-quyet-giup-con-hoc-tieng-anh-tai-nha-1', 'Bí quyết giúp con học tiếng Anh tại nhà', 'Khen ngợi và động viên là liều thuốc tinh thần vô giá. Thay vì chỉ trích lỗi sai, hãy tập trung vào những nỗ lực và tiến bộ của con, dù là nhỏ nhất. Sự công nhận từ cha mẹ sẽ giúp con xây dựng sự tự tin và không sợ mắc lỗi khi học một ngôn ngữ mới.', '/userfiles/images/R5AT3841.jpg', 'Admin', 1456, 1, '<h2>Khám phá phương pháp học hiệu quả cho trẻ</h2><p>Đừng biến việc học thành áp lực. Hãy lồng ghép tiếng Anh vào các trò chơi mà trẻ yêu thích như trốn tìm (đếm số bằng tiếng Anh), board game (dạy về màu sắc, con vật), hoặc các hoạt động nghệ thuật. Khi trẻ cảm thấy vui vẻ, khả năng tiếp thu và ghi nhớ sẽ tăng lên đáng kể.</p><p>Đọc sách truyện song ngữ hoặc truyện tranh tiếng Anh là một cách tuyệt vời để mở rộng vốn từ vựng và làm quen với cấu trúc câu. Hãy bắt đầu với những cuốn sách có hình ảnh minh họa đẹp mắt và nội dung đơn giản, phù hợp với lứa tuổi của con. Cùng con đọc và giải thích những từ mới sẽ giúp tăng cường sự gắn kết gia đình.</p><p>Khen ngợi và động viên là liều thuốc tinh thần vô giá. Thay vì chỉ trích lỗi sai, hãy tập trung vào những nỗ lực và tiến bộ của con, dù là nhỏ nhất. Sự công nhận từ cha mẹ sẽ giúp con xây dựng sự tự tin và không sợ mắc lỗi khi học một ngôn ngữ mới.</p><blockquote><p>Việc học ngoại ngữ sớm không chỉ giúp trẻ phát triển trí não mà còn mở ra nhiều cơ hội trong tương lai.</p></blockquote>', '2025-07-21 03:53:45', '2025-07-21 05:41:02'),
(2, 'bi-quyet-giup-con-hoc-tieng-anh-tai-nha-meo-so-2-2', 'Bí quyết giúp con học tiếng Anh tại nhà - Mẹo số 2', 'Khen ngợi và động viên là liều thuốc tinh thần vô giá. Thay vì chỉ trích lỗi sai, hãy tập trung vào những nỗ lực và tiến bộ của con, dù là nhỏ nhất. Sự công nhận từ cha mẹ sẽ giúp con xây dựng sự tự tin và không sợ mắc lỗi khi học một ngôn ngữ mới.', '/userfiles/images/R5AT3838.jpg', 'AU English', 120, 1, '<h2>Khám phá phương pháp học hiệu quả cho trẻ</h2><p>Một trong những phương pháp hiệu quả nhất để giúp trẻ học tiếng Anh tại nhà là tạo ra một môi trường ngôn ngữ tự nhiên. Phụ huynh có thể dán nhãn các đồ vật trong nhà bằng tiếng Anh, cùng con xem các chương trình hoạt hình hoặc nghe nhạc thiếu nhi bằng tiếng Anh. Việc tiếp xúc thường xuyên sẽ giúp con thẩm thấu ngôn ngữ một cách vô thức.</p><p>Đừng biến việc học thành áp lực. Hãy lồng ghép tiếng Anh vào các trò chơi mà trẻ yêu thích như trốn tìm (đếm số bằng tiếng Anh), board game (dạy về màu sắc, con vật), hoặc các hoạt động nghệ thuật. Khi trẻ cảm thấy vui vẻ, khả năng tiếp thu và ghi nhớ sẽ tăng lên đáng kể.</p><p>Đọc sách truyện song ngữ hoặc truyện tranh tiếng Anh là một cách tuyệt vời để mở rộng vốn từ vựng và làm quen với cấu trúc câu. Hãy bắt đầu với những cuốn sách có hình ảnh minh họa đẹp mắt và nội dung đơn giản, phù hợp với lứa tuổi của con. Cùng con đọc và giải thích những từ mới sẽ giúp tăng cường sự gắn kết gia đình.</p><blockquote>Việc học ngoại ngữ sớm không chỉ giúp trẻ phát triển trí não mà còn mở ra nhiều cơ hội trong tương lai.</blockquote>', '2025-07-20 03:53:45', '2025-07-20 03:53:45'),
(3, 'bi-quyet-giup-con-hoc-tieng-anh-tai-nha-meo-so-3-3', 'Bí quyết giúp con học tiếng Anh tại nhà - Mẹo số 3', 'Đọc sách truyện song ngữ hoặc truyện tranh tiếng Anh là một cách tuyệt vời để mở rộng vốn từ vựng và làm quen với cấu trúc câu. Hãy bắt đầu với những cuốn sách có hình ảnh minh họa đẹp mắt và nội dung đơn giản, phù hợp với lứa tuổi của con. Cùng con đọc và giải thích những từ mới sẽ giúp tăng cường sự gắn kết gia đình.', '/userfiles/images/R5AT3841.jpg', 'AU English', 136, 1, '<h2>Khám phá phương pháp học hiệu quả cho trẻ</h2><p>Đừng biến việc học thành áp lực. Hãy lồng ghép tiếng Anh vào các trò chơi mà trẻ yêu thích như trốn tìm (đếm số bằng tiếng Anh), board game (dạy về màu sắc, con vật), hoặc các hoạt động nghệ thuật. Khi trẻ cảm thấy vui vẻ, khả năng tiếp thu và ghi nhớ sẽ tăng lên đáng kể.</p><p>Khen ngợi và động viên là liều thuốc tinh thần vô giá. Thay vì chỉ trích lỗi sai, hãy tập trung vào những nỗ lực và tiến bộ của con, dù là nhỏ nhất. Sự công nhận từ cha mẹ sẽ giúp con xây dựng sự tự tin và không sợ mắc lỗi khi học một ngôn ngữ mới.</p><p>Một trong những phương pháp hiệu quả nhất để giúp trẻ học tiếng Anh tại nhà là tạo ra một môi trường ngôn ngữ tự nhiên. Phụ huynh có thể dán nhãn các đồ vật trong nhà bằng tiếng Anh, cùng con xem các chương trình hoạt hình hoặc nghe nhạc thiếu nhi bằng tiếng Anh. Việc tiếp xúc thường xuyên sẽ giúp con thẩm thấu ngôn ngữ một cách vô thức.</p><blockquote>Việc học ngoại ngữ sớm không chỉ giúp trẻ phát triển trí não mà còn mở ra nhiều cơ hội trong tương lai.</blockquote>', '2025-07-19 03:53:45', '2025-07-19 03:53:45');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `parents_corner`
--

CREATE TABLE `parents_corner` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `priority` int(11) NOT NULL DEFAULT 99,
  `slug` varchar(255) NOT NULL,
  `image` longtext NOT NULL,
  `rate` text NOT NULL,
  `name` varchar(255) NOT NULL,
  `describe` varchar(50) NOT NULL,
  `content` longtext NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `parents_corner`
--

INSERT INTO `parents_corner` (`id`, `priority`, `slug`, `image`, `rate`, `name`, `describe`, `content`, `created_at`, `updated_at`) VALUES
(1, 1, 'phu-huynh-tra-dan-chuong-0', '/userfiles/images/R5AT4198.jpg', 'Sau khi cho con học tại trung tâm con tôi đã tự tin hơn trước rất nhiều!', 'Phụ huynh Trà Đan Chương', 'Phụ huynh bé Phúc', '<p>Sau một khóa học tại AU, bé nhà mình đã mạnh dạn hơn rất nhiều. Trước đây con rất nhát, không dám nói tiếng Anh, nhưng giờ con có thể tự tin giới thiệu bản thân và hát các bài hát tiếng Anh. Các thầy cô rất nhiệt tình và kiên nhẫn, phương pháp học qua trò chơi thực sự hiệu quả.</p>', '2025-07-21 03:53:45', '2025-07-21 05:44:12'),
(2, 2, 'phu-huynh-anh-tiep-chinh-vy-1', '/userfiles/images/R5AT4200.jpg', 'Chương trình học bài bản, con tiến bộ rõ rệt.', 'Phụ huynh Anh. Tiếp Chính Vỹ', 'Phụ huynh bé Vũ', 'Tôi rất hài lòng với lộ trình học tập tại trung tâm. Con không chỉ được học với giáo viên bản xứ mà còn được củng cố ngữ pháp thường xuyên. Điểm số trên lớp của con đã cải thiện đáng kể, và quan trọng nhất là con tìm thấy niềm yêu thích với môn tiếng Anh.', '2025-07-21 03:53:45', '2025-07-21 03:53:45'),
(3, 3, 'phu-huynh-lo-trung-2', '/userfiles/images/R5AT4202.jpg', 'Trung tâm chuyên nghiệp, giáo viên tận tâm.', 'Phụ huynh Lò Trung', 'Phụ huynh bé Khương', 'Điều tôi ấn tượng nhất là sự chuyên nghiệp và tận tâm của đội ngũ AU. Từ giáo viên đến các bạn trợ giảng đều rất quan tâm đến từng học viên. Trung tâm thường xuyên cập nhật tình hình học tập của con, giúp tôi nắm bắt được sự tiến bộ và phối hợp cùng nhà trường để hỗ trợ con tốt nhất.', '2025-07-21 03:53:45', '2025-07-21 03:53:45');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `password_reset_tokens`
--

CREATE TABLE `password_reset_tokens` (
  `email` varchar(255) NOT NULL,
  `token` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `sessions`
--

CREATE TABLE `sessions` (
  `id` varchar(255) NOT NULL,
  `user_id` bigint(20) UNSIGNED DEFAULT NULL,
  `ip_address` varchar(45) DEFAULT NULL,
  `user_agent` text DEFAULT NULL,
  `payload` longtext NOT NULL,
  `last_activity` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Cấu trúc bảng cho bảng `teachers`
--

CREATE TABLE `teachers` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `priority` int(11) NOT NULL DEFAULT 99,
  `full_name` varchar(255) NOT NULL,
  `role` varchar(255) NOT NULL,
  `qualifications` text NOT NULL,
  `avatar` varchar(255) NOT NULL,
  `slug` varchar(255) NOT NULL,
  `facebook` varchar(255) NOT NULL,
  `email` varchar(50) NOT NULL,
  `description` longtext DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `teachers`
--

INSERT INTO `teachers` (`id`, `priority`, `full_name`, `role`, `qualifications`, `avatar`, `slug`, `facebook`, `email`, `description`, `created_at`, `updated_at`) VALUES
(1, 1, 'Tống Hoàn Tâm', 'Giáo viên Việt Nam', 'Chứng chỉ TESOL, IELTS 8.0+, Sit odio blanditiis.', '/userfiles/images/R5AT4140.jpg', 'tong-hoan-tam-1', 'https://facebook.com/auenglish', 'chu.kiem@example.net', '<h3>Kinh nghiệm giảng dạy</h3><p>Với hơn 5 năm kinh nghiệm giảng dạy, thầy/cô đã giúp đỡ hàng trăm học viên cải thiện trình độ tiếng Anh và đạt được mục tiêu học tập. Phương pháp giảng dạy tập trung vào sự tương tác và truyền cảm hứng cho học viên.</p>', '2025-07-21 03:53:45', '2025-07-21 03:53:45'),
(2, 2, 'Chị. Mẫn Dương', 'Giáo viên Việt Nam', 'Chứng chỉ TESOL, IELTS 8.0+, Officia et harum ipsum.', '/userfiles/images/R5AT4145.jpg', 'chi-man-duong-2', 'https://facebook.com/auenglish', 'nghia.khau@example.org', '<h3>Kinh nghiệm giảng dạy</h3><p>Với hơn 5 năm kinh nghiệm giảng dạy, thầy/cô đã giúp đỡ hàng trăm học viên cải thiện trình độ tiếng Anh và đạt được mục tiêu học tập. Phương pháp giảng dạy tập trung vào sự tương tác và truyền cảm hứng cho học viên.</p>', '2025-07-21 03:53:45', '2025-07-21 03:53:45'),
(3, 3, 'Giả Tuệ Ái', 'Giáo viên Việt Nam', 'Chứng chỉ TESOL, IELTS 8.0+, Nihil et ab.', '/userfiles/images/R5AT4153.jpg', 'gia-tue-ai-3', 'https://facebook.com/auenglish', 'thy68@example.org', '<h3>Kinh nghiệm giảng dạy</h3><p>Với hơn 5 năm kinh nghiệm giảng dạy, thầy/cô đã giúp đỡ hàng trăm học viên cải thiện trình độ tiếng Anh và đạt được mục tiêu học tập. Phương pháp giảng dạy tập trung vào sự tương tác và truyền cảm hứng cho học viên.</p>', '2025-07-21 03:53:45', '2025-07-21 03:53:45');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `trainings`
--

CREATE TABLE `trainings` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `priority` int(11) NOT NULL DEFAULT 99,
  `slug` varchar(255) NOT NULL,
  `title` varchar(255) NOT NULL,
  `age` varchar(255) NOT NULL,
  `description` longtext NOT NULL,
  `thumbnail` varchar(255) NOT NULL,
  `duration` varchar(255) NOT NULL,
  `outcome` varchar(255) NOT NULL,
  `method` varchar(255) NOT NULL,
  `speaking` text NOT NULL,
  `listening` text NOT NULL,
  `reading` text NOT NULL,
  `writing` text NOT NULL,
  `content` longtext DEFAULT NULL,
  `images` text DEFAULT NULL,
  `curriculum` longtext DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `trainings`
--

INSERT INTO `trainings` (`id`, `priority`, `slug`, `title`, `age`, `description`, `thumbnail`, `duration`, `outcome`, `method`, `speaking`, `listening`, `reading`, `writing`, `content`, `images`, `curriculum`, `created_at`, `updated_at`) VALUES
(1, 1, 'tieng-anh-mau-giao-3-6-tuoi-1', 'Tiếng Anh Mẫu giáo (3 - 6 tuổi)', '3 - 6 tuổi', 'Giai đoạn vàng để con bắt đầu học ngôn ngữ mới. Chương trình giúp con tiếp cận tiếng Anh một cách tự nhiên, vui vẻ và hiệu quả, tạo nền tảng vững chắc cho tương lai.', '/userfiles/images/R5AT3881.jpg', '6 tháng', 'Phát âm đúng theo phương pháp ngữ âm quốc tế | Nhận biết và đánh vần lưu loát | Giao tiếp tự tin ngay từ những năm đầu học', 'Học trực tuyến', 'Luyện phát âm chuẩn theo bảng ngữ âm quốc tế (phonics), tập phản xạ giao tiếp qua các bài hát và trò chơi.', 'Nghe và nhận biết các âm, từ vựng quen thuộc thông qua các câu chuyện, bài hát và khẩu lệnh của giáo viên bản xứ.', 'Làm quen với mặt chữ, nhận biết các từ đơn giản qua thẻ từ (flashcards) và các câu chuyện hình ảnh sinh động.', 'Tập tô chữ, sao chép các chữ cái và từ vựng đơn giản, bước đầu hình thành kỹ năng cầm bút và nhận diện chữ viết.', '<!-- Nội dung cho CKEditor --><h1><strong>CHƯƠNG TRÌNH TIẾNG ANH MẪU GIÁO AU</strong></h1><h2>&nbsp;</h2><h2><strong>Nắm bắt độ tuổi vàng</strong></h2><p><strong>3-6 tuổi</strong> là thời kỳ <strong>\"vàng\"</strong> để con bắt đầu học một ngôn ngữ mới. Cơ hội này chỉ đến một lần trong cuộc đời, bỏ lỡ giai đoạn then chốt này, con sẽ khó đạt tới độ phát triển ngôn ngữ tối ưu và toàn diện.</p><p>&nbsp;</p><h2><strong>Điểm nổi bật của chương trình</strong></h2><ol><li><strong>Tập trung nghe – nói:</strong> Giúp con phản xạ nhanh với ngôn ngữ và phát âm chuẩn bản xứ từ nhỏ. <strong>Tiếng Anh mẫu giáo của AU là chương trình ngữ âm tiếng Anh 5 cấp độ</strong>, phù hợp với trẻ từ 3 đến 6 tuổi. Chương trình giúp trẻ làm quen và thành thạo <strong>44 âm trong tiếng Anh</strong> thông qua các hoạt động tương tác như <strong>bài hát, trò chơi và câu đố</strong>.</li><li><strong>Học vui vẻ, hiệu quả:</strong> Chương trình sử dụng <strong>hình ảnh sinh động, trò chơi, bài hát và hoạt động tương tác</strong>, giúp trẻ học tập một cách vui vẻ và hiệu quả. Qua mỗi cấp độ, trẻ dần phát triển kỹ năng ngôn ngữ và <strong>tự tin sử dụng tiếng Anh trong giao tiếp hàng ngày</strong>.</li><li><strong>Tiếp cận tự nhiên:</strong> Chương trình giúp trẻ tiếp cận tiếng Anh một cách tự nhiên thông qua <strong>phương pháp ngữ âm hiện đại</strong>. Trẻ sẽ được làm quen với âm chữ cái, ghép vần và phát âm chuẩn ngay từ những năm đầu đời, tạo <strong>nền tảng vững chắc cho kỹ năng đọc và viết</strong>.</li></ol><h2>&nbsp;</h2><h2><strong>Lợi ích chương trình mang lại</strong></h2><ul><li><strong>Phát âm đúng</strong> theo phương pháp ngữ âm quốc tế</li><li><strong>Nhận biết và đánh vần</strong> lưu loát</li><li><strong>Phát triển kỹ năng đọc và viết</strong> từ sớm</li><li><strong>Giao tiếp tự tin</strong> ngay từ những năm đầu học tiếng Anh</li></ul><p>&nbsp;</p><h2>&nbsp;</h2><h2><strong>KẾT LUẬN</strong></h2><p><strong>Chương trình Tiếng Anh mẫu giáo AU</strong> là lựa chọn hoàn hảo để con bạn khởi đầu hành trình chinh phục tiếng Anh một cách <strong>tự nhiên, vui vẻ và hiệu quả</strong>!</p>', '[\"\\/userfiles\\/images\\/R5AT3893.jpg\",\"\\/userfiles\\/images\\/R5AT3881.jpg\",\"\\/userfiles\\/images\\/R5AT3884.jpg\"]', '[]', '2025-07-21 03:53:45', '2025-07-21 06:48:01'),
(2, 2, 'tieng-anh-tieu-hoc-6-11-tuoi-2', 'Tiếng Anh Tiểu học (6 - 11 tuổi)', '6 - 11 tuổi', 'Tiếng Anh không chỉ là điểm số, mà là kỹ năng sống. Chương trình cung cấp một lộ trình rõ ràng, bài bản, giúp con tự tin giao tiếp và đạt kết quả cao trong học tập.', '/userfiles/images/R5AT3898.jpg', '3 tháng', 'Tự tin giao tiếp với giáo viên bản xứ | Nắm vững ngữ pháp và từ vựng theo chuẩn Cambridge | Cải thiện điểm số trên lớp', 'Học trực tuyến', 'Thực hành nói về các chủ đề quen thuộc như gia đình, trường học, sở thích. Học cách diễn đạt suy nghĩ mạch lạc và tự nhiên.', 'Luyện nghe hiểu các đoạn hội thoại, câu chuyện dài hơn và nắm bắt ý chính, chi tiết quan trọng trong bài.', 'Phát triển kỹ năng đọc hiểu văn bản, truyện ngắn, và trả lời các câu hỏi liên quan đến nội dung đã đọc để củng cố từ vựng.', 'Học cách viết câu hoàn chỉnh, các đoạn văn ngắn mô tả về bản thân, gia đình và các sự vật, hiện tượng xung quanh.', '<!-- Nội dung cho CKEditor - Tiếng Anh Tiểu Học -->\r\n\r\n<h1><strong>KHOÁ HỌC TIẾNG ANH TIỂU HỌC AU</strong></h1>\r\n\r\n<br>\r\n\r\n<h2><strong>Tại sao con cần học Tiếng Anh bài bản ở giai đoạn tiểu học?</strong></h2>\r\n\r\n<p>Giai đoạn con học tiểu học, <strong>Tiếng Anh không chỉ là điểm số, mà là kỹ năng sống</strong>. Phụ huynh cần một lộ trình rõ ràng, đo lường được sự tiến bộ thực sự.</p>\r\n\r\n<br>\r\n\r\n<p>Con cần một <strong>môi trường học tập bài bản, hiệu quả</strong> xứng đáng với sự đầu tư.</p>\r\n\r\n<br><br>\r\n\r\n<h1><strong>ĐẶC ĐIỂM ĐẶC BIỆT CỦA KHOÁ HỌC</strong></h1>\r\n\r\n<br>\r\n\r\n<h2><strong>1. 100% CON HỌC VỚI GIÁO VIÊN NƯỚC NGOÀI</strong></h2>\r\n\r\n<p>Chương trình tập trung vào <strong>phát triển kỹ năng giao tiếp thực tế</strong>, giúp trẻ tự tin nói tiếng Anh ngay từ những buổi học đầu tiên.</p>\r\n\r\n<br>\r\n\r\n<p>Với sự hướng dẫn của <strong>giáo viên bản xứ và trợ giảng tận tâm</strong>, trẻ sẽ được thực hành phát âm chuẩn, học cách diễn đạt suy nghĩ mạch lạc và thể hiện bản thân bằng tiếng Anh một cách tự nhiên nhất.</p>\r\n\r\n<br><br>\r\n\r\n<h2><strong>2. CHƯƠNG TRÌNH HỌC TẬP TÍCH HỢP</strong></h2>\r\n\r\n<p>Tại AU, các em được học song song theo <strong>lộ trình tiếng Anh học thuật bài bản</strong>, bám sát khung chương trình <strong>Cambridge do Nhà xuất bản Đại học Oxford phát triển</strong>.</p>\r\n\r\n<br>\r\n\r\n<p>Bên cạnh đó, AU đặc biệt chú trọng hỗ trợ học sinh nâng cao kết quả học tập tại trường thông qua <strong>các buổi học ngữ pháp bổ trợ miễn phí</strong>, giúp củng cố kiến thức, cải thiện điểm số và tăng sự tự tin trong lớp học chính khóa.</p>\r\n\r\n<br><br>\r\n\r\n<h2><strong>3. LỘ TRÌNH HOÁ CÁ NHÂN</strong></h2>\r\n\r\n<p>Với <strong>lộ trình hoá cá nhân theo khả năng của con</strong> và hệ thống đánh giá kép. Con được điều chỉnh kịp thời nhờ được <strong>đánh giá liên tục</strong>.</p>\r\n\r\n<br>\r\n\r\n<p><strong>Bố mẹ biết chính xác con đang ở đâu, mạnh gì – yếu gì</strong> qua báo cáo chi tiết định kỳ.</p>\r\n\r\n<br><br>\r\n\r\n<h2><strong>4. HỆ THỐNG HỌC LIỆU TOÀN DIỆN</strong></h2>\r\n\r\n<p><strong>Hệ thống giao bài tập online</strong> trên nền tảng trực tuyến của <strong>nhà xuất bản Đại học Oxford</strong> giúp con hào hứng và tiến bộ nhanh trong học tập.</p>\r\n\r\n<br>\r\n\r\n<p>Bên cạnh đó, <strong>Phụ huynh cũng dễ dàng theo dõi điểm số và lộ trình học</strong> của con hơn khi điểm số được chấm tự động trên nền tảng.</p>\r\n\r\n<br><br>\r\n\r\n<hr>\r\n\r\n<br>\r\n\r\n<h2><strong>KẾT LUẬN</strong></h2>\r\n\r\n<p><strong>Khoá học Tiếng Anh Tiểu học AU</strong> mang đến cho con một hành trình học tập <strong>chuyên nghiệp, hiệu quả và đầy thú vị</strong>, giúp con tự tin chinh phục tiếng Anh và chuẩn bị tốt nhất ch', '[\"\\/userfiles\\/images\\/R5AT3879.jpg\",\"\\/userfiles\\/images\\/R5AT3894.jpg\",\"\\/userfiles\\/images\\/R5AT3884.jpg\"]', '[{\"module\":\"N\\u1ed9i dung h\\u1ecdc ph\\u1ea7n m\\u1eabu\",\"content\":\"H\\u1ecdc ph\\u1ea7n 1: My Family and Friends. H\\u1ecdc vi\\u00ean h\\u1ecdc c\\u00e1ch gi\\u1edbi thi\\u1ec7u v\\u1ec1 c\\u00e1c th\\u00e0nh vi\\u00ean trong gia \\u0111\\u00ecnh, b\\u1ea1n b\\u00e8. Th\\u1ef1c h\\u00e0nh \\u0111\\u1eb7t c\\u00e2u h\\u1ecfi v\\u00e0 tr\\u1ea3 l\\u1eddi v\\u1ec1 tu\\u1ed5i t\\u00e1c, ngh\\u1ec1 nghi\\u1ec7p, s\\u1edf th\\u00edch b\\u1eb1ng c\\u00e1c c\\u1ea5u tr\\u00fac c\\u00e2u \\u0111\\u01a1n gi\\u1ea3n v\\u00e0 th\\u00f4ng d\\u1ee5ng.\"}]', '2025-07-21 03:53:45', '2025-07-21 03:53:45'),
(3, 3, 'tieng-anh-thcs-11-13-tuoi-3', 'Tiếng Anh THCS (11 - 13 tuổi)', '11 - 13 tuổi', 'Lộ trình tối ưu giúp học sinh xây dựng nền tảng tiếng Anh học thuật vững chắc, sẵn sàng chinh phục các kỳ thi quan trọng như IELTS ở bậc THPT.', '/userfiles/images/R5AT3898.jpg', '6 tháng', 'Nền tảng Ngữ pháp - Từ vựng học thuật vững chắc | Thành thạo 4 kỹ năng Nghe - Nói - Đọc - Viết | Đạt trình độ tương đương B1-B2 Cambridge', 'Học tại trung tâm', 'Rèn luyện kỹ năng tranh biện, thuyết trình về các chủ đề xã hội và học thuật, phát triển tư duy phản biện bằng tiếng Anh.', 'Luyện nghe các bài giảng, tin tức và hội thoại phức tạp, tập kỹ năng ghi chú (note-taking) và tóm tắt thông tin nghe được.', 'Đọc hiểu các bài báo, văn bản học thuật, phân tích và suy luận để tìm ra ý chính, thông tin ẩn và quan điểm của tác giả.', 'Thực hành viết các đoạn văn nghị luận, email trang trọng và các bài luận ngắn theo cấu trúc chuẩn (mở bài, thân bài, kết luận).', '<!-- Nội dung cho CKEditor - Tiếng Anh THCS -->\r\n\r\n<h1><strong>LỘ TRÌNH TỐI ƯU DÀNH CHO HỌC SINH THCS</strong></h1>\r\n<h2><strong>Sẵn sàng chinh phục IELTS ở bậc THPT</strong></h2>\r\n\r\n<br>\r\n\r\n<h2><strong>Tại sao giai đoạn THCS lại quan trọng?</strong></h2>\r\n\r\n<p>Giai đoạn học THCS <strong>(từ lớp 6 đến lớp 9)</strong> là thời điểm quan trọng để học sinh <strong>xây dựng nền tảng tiếng Anh vững chắc</strong>, chuẩn bị cho các mục tiêu học thuật cao hơn như <strong>IELTS ở cấp 3</strong>.</p>\r\n\r\n<br>\r\n\r\n<p>Tại <strong>AU Bắc Giang</strong>, chương trình học dành cho học sinh THCS được thiết kế với định hướng <strong>tối đa hoá năng lực</strong>, phát triển đều cả về kiến thức, kỹ năng và tư duy ngôn ngữ.</p>\r\n\r\n<br><br>\r\n\r\n<h1><strong>CÁC ĐẶC ĐIỂM NỔI BẬT CỦA LỘ TRÌNH</strong></h1>\r\n\r\n<br>\r\n\r\n<h2><strong>1. Xây nền tảng học thuật vững chắc</strong></h2>\r\n\r\n<p><strong>Hệ thống từ vựng – ngữ pháp – phát âm</strong> được củng cố sâu, giúp học sinh hiểu rõ bản chất ngôn ngữ và ứng dụng thành thạo.</p>\r\n\r\n<br><br>\r\n\r\n<h2><strong>2. Phát triển toàn diện 4 kỹ năng (Nghe – Nói – Đọc – Viết)</strong></h2>\r\n\r\n<p>Thông qua <strong>các chủ đề học thuật và tình huống thực tế</strong>, học sinh được rèn luyện đầy đủ kỹ năng, tạo nền tảng chuyển tiếp mượt mà lên <strong>chương trình IELTS</strong>.</p>\r\n\r\n<br><br>\r\n\r\n<h2><strong>3. Lồng ghép chiến lược làm bài IELTS từ sớm</strong></h2>\r\n\r\n<p><strong>Các dạng bài đọc hiểu, viết luận và kỹ năng phản xạ</strong> được giới thiệu từng bước, giúp học sinh làm quen dần với cách tư duy và cấu trúc bài thi.</p>\r\n\r\n<br><br>\r\n\r\n<h2><strong>4. Học tập theo cấp độ Cambridge – Chuẩn hoá trình độ</strong></h2>\r\n\r\n<p>Chương trình học được thiết kế theo <strong>hệ thống CEFR (A2–B1–B2)</strong>, giúp học sinh xác định rõ mục tiêu và theo dõi được tiến độ phát triển của bản thân.</p>\r\n\r\n<br><br>\r\n\r\n<h2><strong>5. Luyện phản xạ giao tiếp – Tư duy tiếng Anh</strong></h2>\r\n\r\n<p><strong>Giáo viên nước ngoài đồng hành</strong> trong các buổi speaking chuyên biệt, giúp học sinh tự tin nói tiếng Anh và <strong>tư duy bằng tiếng Anh</strong> ngay từ giai đoạn THCS.</p>\r\n\r\n<br><br>\r\n\r\n<h2><strong>6. Theo dõi sát sao – Phản hồi kịp thời</strong></h2>\r\n\r\n<p>Mỗi học sinh đều được <strong>theo dõi tiến độ cá nhân</strong>, nhận phản hồi thường xuyên từ giáo viên để kịp thời điều chỉnh phương pháp học phù hợp.</p>\r\n\r\n<br><br>\r\n\r\n<hr>\r\n\r\n<br>\r\n\r\n<h2><strong>KẾT LUẬN</strong></h2>\r\n\r\n<p><strong>Lộ trình Tiếng Anh THCS tại AU Bắc Giang</strong> là sự chuẩn bị hoàn hảo giúp con <strong>tự tin bước vào chương trình IELTS ở cấp THPT</strong>, với nền tảng vững chắc và phương pháp học hiệu quả!</p>', '[\"\\/userfiles\\/images\\/R5AT3879.jpg\",\"\\/userfiles\\/images\\/R5AT3898.jpg\",\"\\/userfiles\\/images\\/R5AT3881.jpg\"]', '[{\"module\":\"N\\u1ed9i dung h\\u1ecdc ph\\u1ea7n m\\u1eabu\",\"content\":\"H\\u1ecdc ph\\u1ea7n 1: Academic Skills Focus. R\\u00e8n luy\\u1ec7n k\\u1ef9 n\\u0103ng \\u0111\\u1ecdc l\\u01b0\\u1edbt (skimming) v\\u00e0 \\u0111\\u1ecdc qu\\u00e9t (scanning) qua c\\u00e1c b\\u00e0i \\u0111\\u1ecdc v\\u1ec1 ch\\u1ee7 \\u0111\\u1ec1 m\\u00f4i tr\\u01b0\\u1eddng. H\\u1ecdc c\\u00e1ch vi\\u1ebft m\\u1ed9t \\u0111o\\u1ea1n v\\u0103n n\\u00eau quan \\u0111i\\u1ec3m v\\u1edbi c\\u1ea5u tr\\u00fac 3 ph\\u1ea7n r\\u00f5 r\\u00e0ng.\"}]', '2025-07-21 03:53:45', '2025-07-21 03:53:45');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `users`
--

CREATE TABLE `users` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) NOT NULL,
  `remember_token` varchar(100) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `email_verified_at`, `password`, `remember_token`, `created_at`, `updated_at`) VALUES
(1, 'root', 'root@gmail.com', NULL, '$2y$12$meYiEhbWC2ou1YKTHPoMWORHZqFA09EtAYibbQgf5wfJBB8Nv7sya', '0XEonP1TCTCcsVmjhQ2RlCOypFPxm01p8FcgSgvbcNm9krUGRWoFW3c0l48D', NULL, NULL);

--
-- Chỉ mục cho các bảng đã đổ
--

--
-- Chỉ mục cho bảng `cache`
--
ALTER TABLE `cache`
  ADD PRIMARY KEY (`key`);

--
-- Chỉ mục cho bảng `cache_locks`
--
ALTER TABLE `cache_locks`
  ADD PRIMARY KEY (`key`);

--
-- Chỉ mục cho bảng `categories`
--
ALTER TABLE `categories`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `categories_slug_unique` (`slug`);

--
-- Chỉ mục cho bảng `contact`
--
ALTER TABLE `contact`
  ADD PRIMARY KEY (`id`);

--
-- Chỉ mục cho bảng `customers`
--
ALTER TABLE `customers`
  ADD PRIMARY KEY (`id`),
  ADD KEY `customers_training_id_foreign` (`training_id`);

--
-- Chỉ mục cho bảng `document`
--
ALTER TABLE `document`
  ADD PRIMARY KEY (`id`);

--
-- Chỉ mục cho bảng `failed_jobs`
--
ALTER TABLE `failed_jobs`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`);

--
-- Chỉ mục cho bảng `home_page`
--
ALTER TABLE `home_page`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `home_page_banners_unique` (`banners`) USING HASH,
  ADD UNIQUE KEY `home_page_link_youtubes_unique` (`link_youtubes`) USING HASH;

--
-- Chỉ mục cho bảng `jobs`
--
ALTER TABLE `jobs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `jobs_queue_index` (`queue`);

--
-- Chỉ mục cho bảng `job_batches`
--
ALTER TABLE `job_batches`
  ADD PRIMARY KEY (`id`);

--
-- Chỉ mục cho bảng `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`);

--
-- Chỉ mục cho bảng `news`
--
ALTER TABLE `news`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `news_slug_unique` (`slug`),
  ADD KEY `news_category_id_foreign` (`category_id`);

--
-- Chỉ mục cho bảng `parents_corner`
--
ALTER TABLE `parents_corner`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `parents_corner_slug_unique` (`slug`);

--
-- Chỉ mục cho bảng `password_reset_tokens`
--
ALTER TABLE `password_reset_tokens`
  ADD PRIMARY KEY (`email`);

--
-- Chỉ mục cho bảng `sessions`
--
ALTER TABLE `sessions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `sessions_user_id_index` (`user_id`),
  ADD KEY `sessions_last_activity_index` (`last_activity`);

--
-- Chỉ mục cho bảng `teachers`
--
ALTER TABLE `teachers`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `teachers_slug_unique` (`slug`),
  ADD UNIQUE KEY `teachers_email_unique` (`email`);

--
-- Chỉ mục cho bảng `trainings`
--
ALTER TABLE `trainings`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `trainings_slug_unique` (`slug`);

--
-- Chỉ mục cho bảng `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `users_email_unique` (`email`);

--
-- AUTO_INCREMENT cho các bảng đã đổ
--

--
-- AUTO_INCREMENT cho bảng `categories`
--
ALTER TABLE `categories`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT cho bảng `contact`
--
ALTER TABLE `contact`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT cho bảng `customers`
--
ALTER TABLE `customers`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT cho bảng `document`
--
ALTER TABLE `document`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT cho bảng `failed_jobs`
--
ALTER TABLE `failed_jobs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT cho bảng `home_page`
--
ALTER TABLE `home_page`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT cho bảng `jobs`
--
ALTER TABLE `jobs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT cho bảng `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT cho bảng `news`
--
ALTER TABLE `news`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT cho bảng `parents_corner`
--
ALTER TABLE `parents_corner`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT cho bảng `teachers`
--
ALTER TABLE `teachers`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT cho bảng `trainings`
--
ALTER TABLE `trainings`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT cho bảng `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- Các ràng buộc cho các bảng đã đổ
--

--
-- Các ràng buộc cho bảng `customers`
--
ALTER TABLE `customers`
  ADD CONSTRAINT `customers_training_id_foreign` FOREIGN KEY (`training_id`) REFERENCES `trainings` (`id`) ON DELETE SET NULL;

--
-- Các ràng buộc cho bảng `news`
--
ALTER TABLE `news`
  ADD CONSTRAINT `news_category_id_foreign` FOREIGN KEY (`category_id`) REFERENCES `categories` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
