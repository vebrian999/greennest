-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Jul 30, 2025 at 08:32 AM
-- Server version: 8.0.30
-- PHP Version: 8.1.10

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `greennest_db`
--

-- --------------------------------------------------------

--
-- Table structure for table `addresses`
--

CREATE TABLE `addresses` (
  `id` int NOT NULL,
  `user_id` int NOT NULL,
  `address` varchar(255) NOT NULL,
  `is_default` tinyint(1) DEFAULT '0',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `addresses`
--

INSERT INTO `addresses` (`id`, `user_id`, `address`, `is_default`, `created_at`) VALUES
(2, 1, 'Jl. Sudirman No. 10, Jakarta Selatan', 1, '2025-07-29 20:12:20'),
(4, 1, 'Pariatur Occaecat n', 0, '2025-07-29 20:57:44');

-- --------------------------------------------------------

--
-- Table structure for table `articles`
--

CREATE TABLE `articles` (
  `id` int NOT NULL,
  `title` varchar(150) DEFAULT NULL,
  `content` text,
  `author_id` int DEFAULT NULL,
  `image_url` varchar(255) DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `category` varchar(100) DEFAULT NULL,
  `excerpt` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `articles`
--

INSERT INTO `articles` (`id`, `title`, `content`, `author_id`, `image_url`, `created_at`, `category`, `excerpt`) VALUES
(1, 'Judul Artikel Contoh', 'Di era digital saat ini, perkembangan teknologi telah mengubah wajah dunia kerja secara signifikan. Banyak pekerjaan baru bermunculan, dan tuntutan keterampilan juga ikut berubah. Oleh karena itu, penting bagi para pencari kerja maupun profesional untuk mampu beradaptasi dan menyiapkan strategi karir yang relevan dengan zaman. Salah satu langkah awal yang bisa dilakukan adalah meningkatkan keterampilan digital. Keterampilan seperti penggunaan Microsoft Office, Google Workspace, software desain, analisis data, hingga pemrograman dasar kini menjadi nilai tambah yang signifikan di berbagai bidang kerja. Berbagai platform pembelajaran online seperti Coursera, Udemy, dan Skillshare dapat dimanfaatkan untuk belajar mandiri dan meningkatkan kemampuan.\n\nSelain itu, membangun personal branding di media sosial juga menjadi strategi yang tak kalah penting. Media seperti LinkedIn dapat menjadi wadah untuk menunjukkan pencapaian, membagikan wawasan, dan membangun koneksi profesional. Dengan personal branding yang baik, bukan tidak mungkin kamu akan dilirik oleh perusahaan bahkan sebelum melamar kerja. Dalam membangun karir, memperluas jaringan atau networking juga sangat membantu. Mengikuti seminar, webinar, dan aktif dalam komunitas industri dapat membuka banyak peluang baru dan memperkuat relasi profesional.\n\nSikap mental juga berperan penting. Memiliki growth mindset, yaitu sikap yang terbuka terhadap pembelajaran dan perubahan, akan membantumu untuk terus berkembang dan tidak mudah menyerah. Terima kritik sebagai kesempatan untuk belajar, dan jangan ragu mencoba hal-hal baru meskipun belum ahli. Terakhir, siapkan portofolio digital yang dapat menampilkan hasil kerja terbaikmu. Portofolio ini bisa berupa tulisan, desain, proyek coding, atau kampanye marketing yang pernah kamu tangani. Portofolio digital tidak hanya menjadi alat pendukung saat melamar pekerjaan, tetapi juga menjadi bukti nyata dari kemampuan dan profesionalitasmu. Dengan menerapkan strategi-strategi ini secara konsisten, kamu akan lebih siap dalam menghadapi persaingan karir di era digital yang serba cepat dan dinamis.\n\n', 1, 'uploads/artikel/contoh.jpg', '2025-07-30 09:55:42', 'Tips Karir', 'Tingkatkan keterampilan digital, bangun personal branding, dan siapkan portofolio profesional untuk menghadapi persaingan karir di era digital yang terus berkembang.'),
(2, 'Membangun Karir di Era Digital', 'Di tengah perkembangan teknologi yang pesat, dunia kerja juga mengalami perubahan yang signifikan. Banyak pekerjaan baru bermunculan dan keterampilan digital menjadi kunci utama untuk bersaing. Untuk itu, penting bagi setiap individu untuk terus belajar dan mengasah keterampilan sesuai dengan kebutuhan industri saat ini. Salah satu langkah penting adalah memahami tren teknologi seperti cloud computing, data analytics, dan artificial intelligence. Selain itu, membangun portofolio digital yang mencerminkan kemampuan dan pengalaman juga sangat penting. Gunakan media sosial dan platform profesional seperti LinkedIn untuk menampilkan keahlianmu dan membangun jaringan dengan profesional lain. Tak kalah penting, attitude dan soft skill seperti komunikasi, manajemen waktu, dan kerja tim juga sangat dihargai oleh perusahaan modern. Dengan kombinasi keterampilan teknis dan soft skill yang kuat, kamu bisa lebih siap menghadapi tantangan karir di era digital.', 1, 'uploads/artikel/contoh.jpg', '2025-07-29 16:15:00', 'Tips Karir', 'Pelajari cara mengembangkan keterampilan digital dan membangun portofolio profesional untuk bersaing di era kerja modern.');

-- --------------------------------------------------------

--
-- Table structure for table `article_comments`
--

CREATE TABLE `article_comments` (
  `id` int NOT NULL,
  `article_id` int DEFAULT NULL,
  `user_id` int DEFAULT NULL,
  `comment` text,
  `created_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Table structure for table `cart`
--

CREATE TABLE `cart` (
  `id` int NOT NULL,
  `user_id` int DEFAULT NULL,
  `created_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Table structure for table `cart_items`
--

CREATE TABLE `cart_items` (
  `id` int NOT NULL,
  `cart_id` int DEFAULT NULL,
  `product_id` int DEFAULT NULL,
  `quantity` int DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Table structure for table `notifications`
--

CREATE TABLE `notifications` (
  `id` int NOT NULL,
  `user_id` int DEFAULT NULL,
  `type` varchar(50) DEFAULT NULL,
  `message` text,
  `is_read` tinyint(1) DEFAULT NULL,
  `created_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Table structure for table `orders`
--

CREATE TABLE `orders` (
  `id` int NOT NULL,
  `user_id` int DEFAULT NULL,
  `order_date` datetime DEFAULT NULL,
  `status` enum('pending','paid','shipped','completed','cancelled') DEFAULT NULL,
  `total_amount` decimal(10,2) DEFAULT NULL,
  `payment_method` enum('transfer','ewallet','cod') DEFAULT NULL,
  `payment_proof` varchar(255) DEFAULT NULL,
  `shipping_address` text,
  `tracking_number` varchar(100) DEFAULT NULL,
  `created_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Table structure for table `order_items`
--

CREATE TABLE `order_items` (
  `id` int NOT NULL,
  `order_id` int DEFAULT NULL,
  `product_id` int DEFAULT NULL,
  `quantity` int DEFAULT NULL,
  `price` decimal(10,2) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Table structure for table `payments`
--

CREATE TABLE `payments` (
  `id` int NOT NULL,
  `order_id` int DEFAULT NULL,
  `method` enum('transfer','ewallet','cod') DEFAULT NULL,
  `account_number` varchar(50) DEFAULT NULL,
  `ewallet_type` varchar(50) DEFAULT NULL,
  `amount` decimal(10,2) DEFAULT NULL,
  `proof_url` varchar(255) DEFAULT NULL,
  `paid_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Table structure for table `products`
--

CREATE TABLE `products` (
  `id` int NOT NULL,
  `name` varchar(100) DEFAULT NULL,
  `description` text,
  `botanical_name` varchar(255) DEFAULT NULL,
  `common_names` text,
  `detail_care` text,
  `whats_included` text,
  `price` decimal(10,2) DEFAULT NULL,
  `stock` int DEFAULT NULL,
  `is_best_seller` tinyint(1) DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  `price_old` decimal(10,2) DEFAULT NULL,
  `plant_size` varchar(20) DEFAULT NULL,
  `pet_friendly` enum('YES','NO') DEFAULT NULL,
  `difficulty` enum('NO-FUSS','MODERATE','EASY') DEFAULT NULL,
  `product_label` enum('BEST SELLER','NEW ARRIVAL','LIMITED STOCK','OUT OF STOCK') DEFAULT NULL,
  `category_name` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `products`
--

INSERT INTO `products` (`id`, `name`, `description`, `botanical_name`, `common_names`, `detail_care`, `whats_included`, `price`, `stock`, `is_best_seller`, `created_at`, `updated_at`, `price_old`, `plant_size`, `pet_friendly`, `difficulty`, `product_label`, `category_name`) VALUES
(3, 'Snake Plant Laurentii', 'With its striking dark green leaves, bold white veins, and sculptural shape, this plant brings instant drama to any space.\n\nSnake Plant Laurentii is a tough yet elegant houseplant, recognized for its tall, upright foliage\n\nOriginating from tropical West Africa, this plant is well-loved for its ability to purify the air\n\n---resources---\nView Snake Plant Care Guide\nView Snake Plant Video', 'Sansevieria trifasciata \'Laurentii\'', 'Snake Plant, Mother-in-law\'s Tongue, Saint George\'s Sword, Viper’s Bowstring Hemp', 'Snake Plant Laurentii (Dracaena trifasciata \'Laurentii\')\r\nLow maintenance and highly adaptable\r\nAir-purifying capabilities\r\nTolerates low light conditions\r\nDrought resistant', '1x Snake Plant Laurentii in your chosen size\nCeramic pot in your selected color\nDetailed care instructions\n30-day plant health guarantee', '49.99', 1922, 1, '2025-07-30 11:18:22', '2025-07-30 00:00:00', '549.99', NULL, NULL, NULL, NULL, NULL),
(4, 'Snake Plant Laurentii', 'With its striking dark green leaves, bold white veins, and sculptural shape, this plant brings instant drama to any space.', 'Sansevieria trifasciata \'Laurentii\'', 'Snake Plant, Mother-in-law\'s Tongue, Saint George\'s Sword, Viper’s Bowstring Hemp', 'Snake Plant Laurentii (Dracaena trifasciata \'Laurentii\')\r\nLow maintenance and highly adaptable\r\nAir-purifying capabilities\r\nTolerates low light conditions\r\nDrought resistant', '1x Snake Plant Laurentii in your chosen size\r\nCeramic pot in your selected color\r\nDetailed care instructions\r\n30-day plant health guarantee', '19.99', 999, 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(5, 'Snake Plant Laurentii', 'With its striking dark green leaves, bold white edges, and upright form, the Snake Plant Laurentii brings architectural interest and air-purifying benefits to any room.', 'Sansevieria trifasciata \'Laurentii\'', 'Snake Plant, Mother-in-law\'s Tongue, Saint George\'s Sword', 'Snake Plant Laurentii (Dracaena trifasciata \'Laurentii\') is one of the toughest and easiest houseplants to grow. Thrives on neglect, tolerates low light, and needs infrequent watering. Avoid overwatering to prevent root rot.', '1x Snake Plant Laurentii in your chosen size\nCeramic pot (optional)\nCare card', '99.99', 1922, 1, '2025-07-30 11:18:22', '2025-07-30 00:00:00', '549.99', NULL, NULL, NULL, NULL, NULL),
(6, 'Snake Plant Laurentii', 'Snake Plant Laurentii is a tough yet elegant houseplant, recognized for its tall, upright foliage with deep green centers and golden-yellow outlines. It grows well in both bright spots and dim corners, making it a versatile choice for any room, whether at home or at work.\r\n\r\nOriginating from tropical West Africa, this plant is well-loved for its ability to purify the air and its impressive durability. It\'s an excellent choice for plant beginners or anyone with a busy lifestyle.', 'Sansevieria trifasciata \'Laurentii\'', 'Snake Plant, Mother-in-law\'s Tongue, Saint George\'s Sword, Viper’s Bowstring Hemp', 'Snake Plant Laurentii (Dracaena trifasciata \'Laurentii\')\r\nLow maintenance and highly adaptable\r\nAir-purifying capabilities\r\nTolerates low light conditions\r\nDrought resistant', '1x Snake Plant Laurentii in your chosen size\r\nCeramic pot in your selected color\r\nDetailed care instructions\r\n30-day plant health guarantee', '100.00', 999, 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(7, 'Snake Plant Laurentii', 'With its striking dark green leaves, bold white edges, and upright form, the Snake Plant Laurentii brings architectural interest and air-purifying benefits to any room.', 'Sansevieria trifasciata \'Laurentii\'', 'Snake Plant, Mother-in-law\'s Tongue, Saint George\'s Sword', 'Snake Plant Laurentii (Dracaena trifasciata \'Laurentii\') is one of the toughest and easiest houseplants to grow. Thrives on neglect, tolerates low light, and needs infrequent watering. Avoid overwatering to prevent root rot.', '1x Snake Plant Laurentii in your chosen size\nCeramic pot (optional)\nCare card', '19.99', 1922, 1, '2025-07-30 11:18:22', '2025-07-30 00:00:00', '549.99', NULL, NULL, NULL, NULL, NULL),
(8, 'Snake Plant Laurentii', 'With its striking dark green leaves, bold white edges, and upright form, the Snake Plant Laurentii brings architectural interest and air-purifying benefits to any room.', 'Sansevieria trifasciata \'Laurentii\'', 'Snake Plant, Mother-in-law\'s Tongue, Saint George\'s Sword', 'Snake Plant Laurentii (Dracaena trifasciata \'Laurentii\') is one of the toughest and easiest houseplants to grow. Thrives on neglect, tolerates low light, and needs infrequent watering. Avoid overwatering to prevent root rot.', '1x Snake Plant Laurentii in your chosen size\nCeramic pot (optional)\nCare card', '99.99', 1922, 1, '2025-07-30 11:18:22', '2025-07-30 00:00:00', '549.99', NULL, NULL, NULL, NULL, NULL),
(9, 'Snake Plant Laurentii', 'With its striking dark green leaves, bold white edges, and upright form, the Snake Plant Laurentii brings architectural interest and air-purifying benefits to any room.', 'Sansevieria trifasciata \'Laurentii\'', 'Snake Plant, Mother-in-law\'s Tongue, Saint George\'s Sword', 'Snake Plant Laurentii (Dracaena trifasciata \'Laurentii\') is one of the toughest and easiest houseplants to grow. Thrives on neglect, tolerates low light, and needs infrequent watering. Avoid overwatering to prevent root rot.', '1x Snake Plant Laurentii in your chosen size\nCeramic pot (optional)\nCare card', '100.00', 1922, 1, '2025-07-30 11:18:22', '2025-07-30 00:00:00', '549.99', NULL, NULL, NULL, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `product_images`
--

CREATE TABLE `product_images` (
  `id` int NOT NULL,
  `product_id` int DEFAULT NULL,
  `image_url` varchar(255) DEFAULT NULL,
  `is_main` tinyint(1) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `product_images`
--

INSERT INTO `product_images` (`id`, `product_id`, `image_url`, `is_main`) VALUES
(13, 3, 'uploads/products/main-img-product (1).png', 1),
(14, 3, 'uploads/products/main-img-product (2).png', 0),
(15, 3, 'uploads/products/main-img-product.png', 0);

-- --------------------------------------------------------

--
-- Table structure for table `product_likes`
--

CREATE TABLE `product_likes` (
  `id` int NOT NULL,
  `product_id` int NOT NULL,
  `user_id` int NOT NULL,
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `product_likes`
--

INSERT INTO `product_likes` (`id`, `product_id`, `user_id`, `created_at`) VALUES
(2, 3, 1, '2025-07-30 12:30:35'),
(3, 3, 2, '2025-07-30 12:52:03');

-- --------------------------------------------------------

--
-- Table structure for table `reviews`
--

CREATE TABLE `reviews` (
  `id` int NOT NULL,
  `product_id` int DEFAULT NULL,
  `user_id` int DEFAULT NULL,
  `rating` int DEFAULT NULL,
  `comment` text,
  `image_url` varchar(255) DEFAULT NULL,
  `created_at` datetime DEFAULT NULL
) ;

--
-- Dumping data for table `reviews`
--

INSERT INTO `reviews` (`id`, `product_id`, `user_id`, `rating`, `comment`, `image_url`, `created_at`) VALUES
(1, 3, 1, 5, 'Tanaman sangat sehat dan pengiriman cepat!', 'uploads/review/review.png', '2025-07-30 11:50:55'),
(2, 3, 1, 5, 'jelek biintang 1', 'uploads/review/review_1753852786_carbon (37).png', '2025-07-30 12:19:46'),
(3, 3, 1, 5, 'gokil brok', 'uploads/review/review_1753852870_carbon (36).png', '2025-07-30 12:21:10'),
(5, 3, 2, 3, 'produk jelek', NULL, '2025-07-30 12:53:28'),
(6, 6, 2, 5, 'how', NULL, '2025-07-30 13:07:21'),
(7, 3, 2, 5, 'goks', 'uploads/review/review_1753856168_mina-rad-eR3W3BouGL4-unsplash.jpg', '2025-07-30 13:16:08'),
(8, 6, 2, 3, 'aw', NULL, '2025-07-30 13:29:00'),
(9, 6, 2, 4, 'ad', NULL, '2025-07-30 13:29:14'),
(10, 6, 2, 1, 'S', NULL, '2025-07-30 13:29:23'),
(23, 3, 1, 5, 'Produk sangat bagus!', 'uploads/review/review_1753852870_carbon (36).png', '2025-07-30 13:41:56'),
(24, 3, 1, 4, 'Tanaman sehat dan segar.', 'uploads/review/review_1753852870_carbon (36).png', '2025-07-30 13:41:56'),
(25, 3, 1, 3, 'Cukup baik, sesuai harga.', '', '2025-07-30 13:41:56'),
(26, 3, 1, 5, 'Rekomendasi banget!', '', '2025-07-30 13:41:56'),
(27, 3, 1, 2, 'Kurang sesuai ekspektasi.', '', '2025-07-30 13:41:56'),
(28, 3, 1, 4, 'Pengiriman cepat.', '', '2025-07-30 13:41:56'),
(29, 3, 1, 5, 'Tanaman tumbuh subur.', '', '2025-07-30 13:41:56'),
(30, 3, 1, 3, 'Biasa saja.', '', '2025-07-30 13:41:56'),
(31, 3, 1, 4, 'Potnya bagus.', '', '2025-07-30 13:41:56'),
(32, 3, 1, 5, 'Sangat memuaskan!', '', '2025-07-30 13:41:56'),
(33, 3, 1, 4, 'Tanaman segar.', '', '2025-07-30 13:41:56'),
(34, 3, 1, 5, 'Kualitas oke!', '', '2025-07-30 13:41:56'),
(35, 3, 2, 5, 'test puki', NULL, '2025-07-30 13:51:32'),
(36, 3, 2, 5, 'ad', NULL, '2025-07-30 13:52:43'),
(37, 3, 2, 5, 'sad', NULL, '2025-07-30 13:52:51'),
(38, 3, 2, 3, 'kontol ayam', 'uploads/review/review_1753859235_carbon (38).png', '2025-07-30 14:07:15'),
(39, 3, 2, 5, 'pukii ayam enak sedap kali', NULL, '2025-07-30 14:25:43'),
(40, 3, 2, 5, 'ASD', NULL, '2025-07-30 15:09:37'),
(41, 8, 2, 5, 'ad', NULL, '2025-07-30 15:10:08');

-- --------------------------------------------------------

--
-- Table structure for table `settings`
--

CREATE TABLE `settings` (
  `id` int NOT NULL,
  `user_id` int DEFAULT NULL,
  `key` varchar(50) DEFAULT NULL,
  `value` text
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int NOT NULL,
  `name` varchar(100) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  `password` varchar(255) DEFAULT NULL,
  `phone` varchar(20) DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  `profile_image` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `password`, `phone`, `created_at`, `updated_at`, `profile_image`) VALUES
(1, 'febrian syusda', 'feb123@gmail.com', '$2y$10$bQgpZy8emOhTIX5Xi0pIL.wY5sVlIq3V4CM61Ubbn6abRcaMNGIZO', '08123123123', '2025-07-30 02:43:57', '2025-07-30 02:43:57', 'uploads/profile/profile_1_1753821989.png'),
(2, 'febrian raja tanaman', 'febrian@gmail.com', '$2y$10$1N2yjB7OXcMExghYOcXXfeE.h2NHfTOdBlq3Y43BraKogoDL4Yvsa', NULL, '2025-07-30 12:41:36', '2025-07-30 12:41:36', NULL);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `addresses`
--
ALTER TABLE `addresses`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `articles`
--
ALTER TABLE `articles`
  ADD PRIMARY KEY (`id`),
  ADD KEY `author_id` (`author_id`);

--
-- Indexes for table `article_comments`
--
ALTER TABLE `article_comments`
  ADD PRIMARY KEY (`id`),
  ADD KEY `article_id` (`article_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `cart`
--
ALTER TABLE `cart`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `cart_items`
--
ALTER TABLE `cart_items`
  ADD PRIMARY KEY (`id`),
  ADD KEY `cart_id` (`cart_id`),
  ADD KEY `product_id` (`product_id`);

--
-- Indexes for table `notifications`
--
ALTER TABLE `notifications`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `order_items`
--
ALTER TABLE `order_items`
  ADD PRIMARY KEY (`id`),
  ADD KEY `order_id` (`order_id`),
  ADD KEY `product_id` (`product_id`);

--
-- Indexes for table `payments`
--
ALTER TABLE `payments`
  ADD PRIMARY KEY (`id`),
  ADD KEY `order_id` (`order_id`);

--
-- Indexes for table `products`
--
ALTER TABLE `products`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `product_images`
--
ALTER TABLE `product_images`
  ADD PRIMARY KEY (`id`),
  ADD KEY `product_id` (`product_id`);

--
-- Indexes for table `product_likes`
--
ALTER TABLE `product_likes`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `unique_like` (`product_id`,`user_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `reviews`
--
ALTER TABLE `reviews`
  ADD PRIMARY KEY (`id`),
  ADD KEY `product_id` (`product_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `settings`
--
ALTER TABLE `settings`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `addresses`
--
ALTER TABLE `addresses`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `articles`
--
ALTER TABLE `articles`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `article_comments`
--
ALTER TABLE `article_comments`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `cart`
--
ALTER TABLE `cart`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `cart_items`
--
ALTER TABLE `cart_items`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `notifications`
--
ALTER TABLE `notifications`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `orders`
--
ALTER TABLE `orders`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `order_items`
--
ALTER TABLE `order_items`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `payments`
--
ALTER TABLE `payments`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `products`
--
ALTER TABLE `products`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `product_images`
--
ALTER TABLE `product_images`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT for table `product_likes`
--
ALTER TABLE `product_likes`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `reviews`
--
ALTER TABLE `reviews`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `settings`
--
ALTER TABLE `settings`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `addresses`
--
ALTER TABLE `addresses`
  ADD CONSTRAINT `addresses_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `articles`
--
ALTER TABLE `articles`
  ADD CONSTRAINT `articles_ibfk_1` FOREIGN KEY (`author_id`) REFERENCES `users` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `article_comments`
--
ALTER TABLE `article_comments`
  ADD CONSTRAINT `article_comments_ibfk_1` FOREIGN KEY (`article_id`) REFERENCES `articles` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `article_comments_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `cart`
--
ALTER TABLE `cart`
  ADD CONSTRAINT `cart_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `cart_items`
--
ALTER TABLE `cart_items`
  ADD CONSTRAINT `cart_items_ibfk_1` FOREIGN KEY (`cart_id`) REFERENCES `cart` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `cart_items_ibfk_2` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `notifications`
--
ALTER TABLE `notifications`
  ADD CONSTRAINT `notifications_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `orders`
--
ALTER TABLE `orders`
  ADD CONSTRAINT `orders_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `order_items`
--
ALTER TABLE `order_items`
  ADD CONSTRAINT `order_items_ibfk_1` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `order_items_ibfk_2` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `payments`
--
ALTER TABLE `payments`
  ADD CONSTRAINT `payments_ibfk_1` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `product_images`
--
ALTER TABLE `product_images`
  ADD CONSTRAINT `product_images_ibfk_1` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `product_likes`
--
ALTER TABLE `product_likes`
  ADD CONSTRAINT `product_likes_ibfk_1` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `product_likes_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `reviews`
--
ALTER TABLE `reviews`
  ADD CONSTRAINT `reviews_ibfk_1` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `reviews_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `settings`
--
ALTER TABLE `settings`
  ADD CONSTRAINT `settings_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
