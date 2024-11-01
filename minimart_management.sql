/*
 Navicat Premium Dump SQL

 Source Server         : test
 Source Server Type    : MySQL
 Source Server Version : 80300 (8.3.0)
 Source Host           : localhost:3306
 Source Schema         : minimart_management

 Target Server Type    : MySQL
 Target Server Version : 80300 (8.3.0)
 File Encoding         : 65001

 Date: 27/10/2024 08:44:41
*/

SET NAMES utf8mb4;
SET FOREIGN_KEY_CHECKS = 0;

-- ----------------------------
-- Table structure for blog_posts
-- ----------------------------
DROP TABLE IF EXISTS `blog_posts`;
CREATE TABLE `blog_posts`  (
  `post_id` int NOT NULL AUTO_INCREMENT,
  `title` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `image_url` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NULL DEFAULT NULL,
  `post_description` text CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NULL,
  `comments_count` int NULL DEFAULT 0,
  `status` enum('available','unavailable','pending') CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NULL DEFAULT NULL,
  `post_by_id` int UNSIGNED NOT NULL,
  `post_date` date NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`post_id`) USING BTREE,
  INDEX `post_by_id`(`post_by_id` ASC) USING BTREE,
  CONSTRAINT `blog_posts_ibfk_1` FOREIGN KEY (`post_by_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE = InnoDB AUTO_INCREMENT = 12 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_0900_ai_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of blog_posts
-- ----------------------------
INSERT INTO `blog_posts` VALUES (9, 'វិធីធ្វើទឹកក្រូច', 'blog-1.jpg', 'បកសំបកក្រូច និងក្រឡុកនឹងទឹកឃ្មុំឬស្ករ បន្ទាប់មកច្រោះយកកាកនិងធ្វើការប្រឡាក់លើទឹកកករហូតដល់ត្រជាក់ស្រួលផឹក។\r\n\r\n(Peel the oranges and blend with honey or sugar, then strain out the pulp and pour over ice until chilled.)', 1000, 'available', 22, '2024-10-21', '2024-10-21 17:51:43');
INSERT INTO `blog_posts` VALUES (10, 'វិធីធ្វើទឹកប៉ោម', 'blog-3.jpg', 'បកសំបកប៉ោម កាត់ជាដុំតូចៗ ហើយក្រឡុកជាមួយទឹកស្អាត និងស្ករ បន្ទាប់មកច្រោះកាក ទាញយកទឹកលើទឹកកកឱ្យត្រជាក់។\r\n\r\n(Peel the apples, cut them into small pieces, blend with water and sugar, then strain the pulp and serve over ice.)', 0, 'available', 22, '2024-10-21', '2024-10-21 17:52:50');
INSERT INTO `blog_posts` VALUES (11, 'វិធីធ្វើទឹកស្ត្របឺរី', 'blog-6.jpg', 'លាងស្ត្របឺរីឱ្យស្អាត កាត់ជាដុំតូចៗ ហើយក្រឡុកជាមួយស្ករ និងទឹកបន្តិច បន្ទាប់មកច្រោះកាក និងទាញយកទឹកលើទឹកកកឱ្យត្រជាក់។\r\n\r\n(Wash the strawberries, cut them into small pieces, blend with sugar and a little water, then strain the pulp and serve over ice.)', 20, 'available', 22, '2024-10-21', '2024-10-21 17:54:26');

-- ----------------------------
-- Table structure for brands
-- ----------------------------
DROP TABLE IF EXISTS `brands`;
CREATE TABLE `brands`  (
  `brand_id` int UNSIGNED NOT NULL AUTO_INCREMENT,
  `brand_name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `create_date` timestamp NULL DEFAULT NULL,
  `update_date` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`brand_id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 10 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_0900_ai_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of brands
-- ----------------------------
INSERT INTO `brands` VALUES (7, 'fresh fruite', '2024-10-13 02:10:20', '2024-10-13 02:14:11');
INSERT INTO `brands` VALUES (8, 'Orano', '2024-10-20 16:45:56', '2024-10-20 16:45:56');
INSERT INTO `brands` VALUES (9, 'lalala', '2024-10-20 16:46:09', '2024-10-20 16:46:09');

-- ----------------------------
-- Table structure for categories
-- ----------------------------
DROP TABLE IF EXISTS `categories`;
CREATE TABLE `categories`  (
  `category_id` int NOT NULL AUTO_INCREMENT,
  `category_name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `description` text CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NULL,
  `status` enum('active','inactive') CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL DEFAULT 'active',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`category_id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 19 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_0900_ai_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of categories
-- ----------------------------
INSERT INTO `categories` VALUES (3, 'បន្លែ', 'បន្លែស្រស់ៗល្អណាស់ៗ', 'inactive', '2024-09-27 18:28:12', '2024-10-21 17:29:47');
INSERT INTO `categories` VALUES (5, 'ភេសជ្ជ', '', 'inactive', '2024-09-29 14:33:58', '2024-10-21 17:29:54');
INSERT INTO `categories` VALUES (7, 'Beer', '', 'inactive', '2024-10-13 01:16:34', '2024-10-21 17:30:00');
INSERT INTO `categories` VALUES (9, 'tt', '', '', '2024-10-13 01:48:04', '2024-10-13 01:48:04');
INSERT INTO `categories` VALUES (11, 'ផ្លែឈើ', '', 'inactive', '2024-10-16 14:20:19', '2024-10-21 17:30:05');
INSERT INTO `categories` VALUES (12, 'មុខម្ហូប ', 'រួមបញ្ចូលនូវអាហារជាច្រើនប្រភេទដែលត្រូវបានកំណត់សម្រាប់ការទទួលទានប្រចាំថ្ងៃជាមួយនឹងអាហារបំប៉ននិងចំណីផ្សេងៗ។\r\n(Includes a variety of everyday foods, nutritious items, and snacks.)', 'active', '2024-10-21 17:31:26', '2024-10-21 17:31:26');
INSERT INTO `categories` VALUES (13, 'ភេសជ្ជៈ', 'មានទឹកផ្លែឈើ, ទឹកសុទ្ធ, ទឹកក្រូចកំប៉ុង, កាហ្វេ, និងភេសជ្ជៈច្រើនទៀតសម្រាប់បំពេញការស្រកទឹក។\r\n(Offers juices, bottled water, soft drinks, coffee, and other beverages to quench thirst.)', 'active', '2024-10-21 17:31:58', '2024-10-21 17:31:58');
INSERT INTO `categories` VALUES (14, 'សម្ភារៈអនាម័យ', 'រួមបញ្ចូលនូវផលិតផលសម្រាប់ការរស់នៅអនាម័យដូចជាក្រដាសអនាម័យ, សាប៊ូនាងភ្លឺ, និងទឹកលាងដៃ។\r\n(Hygiene products including tissue paper, soap, and hand sanitizers.)', 'active', '2024-10-21 17:33:01', '2024-10-21 17:33:01');
INSERT INTO `categories` VALUES (15, 'ផលិតផលទឹកជ្រលក់ ', 'ជម្រើសទឹកជ្រលក់និងទឹកក្រឡុកដូចជាទឹកត្រី, ទឹកស៊ីអ៊ីវ, ទឹកប៉េងប៉ោះ និងទឹកជ្រលក់ផ្លែឈើ។\r\n(Variety of sauces and condiments such as fish sauce, soy sauce, ketchup, and fruit dips.)', 'active', '2024-10-21 17:34:19', '2024-10-21 17:34:19');
INSERT INTO `categories` VALUES (16, 'ផ្លែឈើ', '', 'active', '2024-10-21 17:37:36', '2024-10-21 17:37:36');
INSERT INTO `categories` VALUES (17, 'បន្លែ', '', 'active', '2024-10-21 17:37:50', '2024-10-21 17:37:50');

-- ----------------------------
-- Table structure for inventory
-- ----------------------------
DROP TABLE IF EXISTS `inventory`;
CREATE TABLE `inventory`  (
  `inventory_id` int NOT NULL AUTO_INCREMENT,
  `product_id` int NULL DEFAULT NULL,
  `quantity` int UNSIGNED NOT NULL,
  `reorder_level` int NULL DEFAULT 10,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`inventory_id`) USING BTREE,
  INDEX `product_id`(`product_id` ASC) USING BTREE,
  CONSTRAINT `inventory_ibfk_1` FOREIGN KEY (`product_id`) REFERENCES `products` (`product_id`) ON DELETE RESTRICT ON UPDATE RESTRICT
) ENGINE = InnoDB AUTO_INCREMENT = 15 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_0900_ai_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of inventory
-- ----------------------------
INSERT INTO `inventory` VALUES (1, 39, 40, 10, '2024-10-26 23:36:19');
INSERT INTO `inventory` VALUES (2, 35, 0, 10, '2024-10-21 13:18:36');
INSERT INTO `inventory` VALUES (3, 36, 2, 10, '2024-10-20 22:59:12');
INSERT INTO `inventory` VALUES (4, 37, 0, 10, '2024-10-20 22:59:53');
INSERT INTO `inventory` VALUES (5, 40, 0, 10, '2024-10-27 00:02:21');
INSERT INTO `inventory` VALUES (6, 41, 20, 10, '2024-10-21 17:39:32');
INSERT INTO `inventory` VALUES (7, 42, 20, 10, '2024-10-21 17:40:08');
INSERT INTO `inventory` VALUES (8, 43, 20, 10, '2024-10-21 17:47:03');
INSERT INTO `inventory` VALUES (9, 44, 30, 10, '2024-10-21 17:48:35');
INSERT INTO `inventory` VALUES (10, 45, 10, 10, '2024-10-21 17:49:34');
INSERT INTO `inventory` VALUES (11, 46, 5, 10, '2024-10-21 18:24:47');
INSERT INTO `inventory` VALUES (12, 47, 0, 10, '2024-10-27 00:00:19');
INSERT INTO `inventory` VALUES (13, 48, 10, 10, '2024-10-26 23:34:43');
INSERT INTO `inventory` VALUES (14, 49, 10, 10, '2024-10-27 00:06:50');

-- ----------------------------
-- Table structure for products
-- ----------------------------
DROP TABLE IF EXISTS `products`;
CREATE TABLE `products`  (
  `product_id` int NOT NULL AUTO_INCREMENT,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `category_id` int NOT NULL,
  `img` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `description` text CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NULL,
  `features` text CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NULL,
  `specifications` text CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NULL,
  `price` decimal(10, 2) NOT NULL,
  `base_price` decimal(10, 2) UNSIGNED NOT NULL,
  `brand_id` int UNSIGNED NOT NULL,
  `status` enum('available','unavailable','pending') CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL DEFAULT 'available',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`product_id`) USING BTREE,
  INDEX `brand_id`(`brand_id` ASC) USING BTREE,
  INDEX `category_id`(`category_id` ASC) USING BTREE,
  CONSTRAINT `products_ibfk_1` FOREIGN KEY (`brand_id`) REFERENCES `brands` (`brand_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `products_ibfk_2` FOREIGN KEY (`category_id`) REFERENCES `categories` (`category_id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE = InnoDB AUTO_INCREMENT = 50 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_0900_ai_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of products
-- ----------------------------
INSERT INTO `products` VALUES (35, 'ផ្លែចេក', 15, '8.jpg', 'ផ្លែចេកធម្មជាតិៗ', NULL, NULL, 0.29, 0.37, 7, 'unavailable', '2024-10-13 13:10:21');
INSERT INTO `products` VALUES (36, 'apple', 13, '3.jpg', '', NULL, NULL, 1.00, 3.00, 8, 'available', '2024-10-13 22:27:23');
INSERT INTO `products` VALUES (37, 'ក្រូច', 13, '38.jpg', '', NULL, NULL, 1.00, 2.00, 7, 'unavailable', '2024-10-16 14:05:38');
INSERT INTO `products` VALUES (39, 'នំប័ុង', 12, '10.jpg', '', NULL, NULL, 2.00, 3.00, 9, 'available', '2024-10-19 13:32:45');
INSERT INTO `products` VALUES (40, 'ប្លូប៊ីរី', 16, '9.jpg', '', NULL, NULL, 10.00, 11.00, 7, 'unavailable', '2024-10-20 12:12:22');
INSERT INTO `products` VALUES (41, 'ផ្លែក្រូច', 12, '4.jpg', '', NULL, NULL, 3.00, 4.00, 7, 'available', '2024-10-21 17:39:32');
INSERT INTO `products` VALUES (42, 'ផ្លែចេក', 12, '8.jpg', '', NULL, NULL, 2.00, 2.50, 7, 'available', '2024-10-21 17:40:08');
INSERT INTO `products` VALUES (43, 'ក្រដាសអនាម័យ', 14, '40.jpg', '', NULL, NULL, 1.00, 1.00, 8, 'available', '2024-10-21 17:47:03');
INSERT INTO `products` VALUES (44, 'ទឹកខ្មេះ', 15, '14.jpg', '', NULL, NULL, 20.00, 21.00, 9, 'available', '2024-10-21 17:48:35');
INSERT INTO `products` VALUES (45, 'ទឹកក្រូច', 13, '16.jpg', '', NULL, NULL, 10.00, 11.00, 7, 'available', '2024-10-21 17:49:34');
INSERT INTO `products` VALUES (46, 'Orange', 16, '19.jpg', 'nothing', NULL, NULL, 5.00, 10.00, 7, 'available', '2024-10-21 18:24:47');
INSERT INTO `products` VALUES (47, 'banaa', 17, '22.jpg', '', NULL, NULL, 5.00, 10.00, 7, 'unavailable', '2024-10-21 19:13:25');
INSERT INTO `products` VALUES (48, 'នំប័ុង', 12, '5.jpg', '', NULL, NULL, 10.00, 11.00, 9, 'pending', '2024-10-26 23:34:43');
INSERT INTO `products` VALUES (49, 'apple', 16, '3.jpg', '', NULL, NULL, 12.00, 13.00, 7, 'available', '2024-10-27 00:06:50');

-- ----------------------------
-- Table structure for reports
-- ----------------------------
DROP TABLE IF EXISTS `reports`;
CREATE TABLE `reports`  (
  `report_id` int NOT NULL AUTO_INCREMENT,
  `report_type` enum('sales','inventory','finance') CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `report_date` date NULL DEFAULT NULL,
  `total_amount` decimal(10, 2) NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`report_id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_0900_ai_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of reports
-- ----------------------------

-- ----------------------------
-- Table structure for sales
-- ----------------------------
DROP TABLE IF EXISTS `sales`;
CREATE TABLE `sales`  (
  `sale_id` int NOT NULL AUTO_INCREMENT,
  `product_id` int NOT NULL,
  `user_id` int UNSIGNED NOT NULL,
  `quantity_sold` int NOT NULL,
  `total_amount` decimal(10, 2) NOT NULL,
  `discount` decimal(10, 2) NULL DEFAULT NULL,
  `sale_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`sale_id`) USING BTREE,
  INDEX `product_id`(`product_id` ASC) USING BTREE,
  INDEX `user_id`(`user_id` ASC) USING BTREE,
  CONSTRAINT `sales_ibfk_1` FOREIGN KEY (`product_id`) REFERENCES `products` (`product_id`) ON DELETE RESTRICT ON UPDATE RESTRICT,
  CONSTRAINT `sales_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`) ON DELETE RESTRICT ON UPDATE RESTRICT
) ENGINE = InnoDB AUTO_INCREMENT = 16 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_0900_ai_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of sales
-- ----------------------------
INSERT INTO `sales` VALUES (1, 35, 22, 3, 0.87, 0.00, '2024-10-20 22:54:42');
INSERT INTO `sales` VALUES (2, 36, 22, 3, 3.00, 0.00, '2024-10-20 22:59:12');
INSERT INTO `sales` VALUES (3, 37, 22, 2, 2.00, 0.00, '2024-10-20 22:59:53');
INSERT INTO `sales` VALUES (4, 40, 22, 2, 20.00, 0.00, '2024-10-21 13:11:35');
INSERT INTO `sales` VALUES (5, 35, 22, 2, 0.58, 0.00, '2024-10-21 13:18:36');
INSERT INTO `sales` VALUES (6, 39, 27, 8, 16.00, 0.00, '2024-10-21 18:07:22');
INSERT INTO `sales` VALUES (7, 39, 27, 8, 16.00, 0.00, '2024-10-21 18:08:33');
INSERT INTO `sales` VALUES (8, 39, 27, 4, 8.00, 0.00, '2024-10-21 18:08:50');
INSERT INTO `sales` VALUES (9, 40, 27, 3, 30.00, 0.00, '2024-10-21 18:08:50');
INSERT INTO `sales` VALUES (10, 40, 27, 3, 30.00, 0.00, '2024-10-21 18:10:13');
INSERT INTO `sales` VALUES (11, 47, 22, 3, 15.00, 0.00, '2024-10-21 19:15:03');
INSERT INTO `sales` VALUES (12, 39, 30, 1, 2.00, 0.00, '2024-10-26 23:16:25');
INSERT INTO `sales` VALUES (13, 40, 30, 2, 20.00, 0.00, '2024-10-26 23:17:25');
INSERT INTO `sales` VALUES (14, 47, 22, 3, 15.00, 0.00, '2024-10-27 00:00:19');
INSERT INTO `sales` VALUES (15, 40, 22, 2, 20.00, 0.00, '2024-10-27 00:02:21');

-- ----------------------------
-- Table structure for staff
-- ----------------------------
DROP TABLE IF EXISTS `staff`;
CREATE TABLE `staff`  (
  `staff_id` int NOT NULL AUTO_INCREMENT,
  `full_name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NULL DEFAULT NULL,
  `position` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NULL DEFAULT NULL,
  `salary` decimal(10, 2) NULL DEFAULT NULL,
  `phone` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NULL DEFAULT NULL,
  `email` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NULL DEFAULT NULL,
  `address` text CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NULL,
  `hire_date` date NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`staff_id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_0900_ai_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of staff
-- ----------------------------

-- ----------------------------
-- Table structure for suppliers
-- ----------------------------
DROP TABLE IF EXISTS `suppliers`;
CREATE TABLE `suppliers`  (
  `supplier_id` int NOT NULL AUTO_INCREMENT,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `contact_person` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NULL DEFAULT NULL,
  `phone` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NULL DEFAULT NULL,
  `email` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NULL DEFAULT NULL,
  `address` text CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`supplier_id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_0900_ai_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of suppliers
-- ----------------------------

-- ----------------------------
-- Table structure for users
-- ----------------------------
DROP TABLE IF EXISTS `users`;
CREATE TABLE `users`  (
  `user_id` int UNSIGNED NOT NULL AUTO_INCREMENT,
  `first_name` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `last_name` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `email` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `password` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `dob` date NULL DEFAULT NULL,
  `img` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NULL DEFAULT NULL,
  `gender` enum('male','female','other') CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL DEFAULT 'male',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `role` enum('normal','admin') CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NULL DEFAULT NULL,
  `status` enum('active','inactive') CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  PRIMARY KEY (`user_id`) USING BTREE,
  UNIQUE INDEX `email`(`email` ASC) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 33 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_0900_ai_ci ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Records of users
-- ----------------------------
INSERT INTO `users` VALUES (22, 'បញ្ញា', 'orano', 'panhayom007@gmail.com', '$2y$10$1l6LikDAnMozJ.Z9oE5tauw8IsTBmtashwz5844yRXzlUZMR6n/YW', '2013-01-18', 'rb_2147880409.png', 'female', '2024-10-17 18:44:13', 'admin', 'active');
INSERT INTO `users` VALUES (24, 'Panha', 'Yom', 'panhayom1234@gmail.com', '$2y$10$WnzdenlOp309EBcPYsqYJ.XpGz/IhZG0aNmIAdVCFAKdYb7A92cZe', NULL, NULL, 'male', '2024-10-17 19:18:37', 'normal', 'active');
INSERT INTO `users` VALUES (26, 'Panha', 'Yom', 'panhayom01020@gmail.com', '$2y$10$elRnYsSzbnjhw9DdpNFhqO1TYZk07YxwIkBnH1GweWY9XWeVETvKu', NULL, NULL, 'male', '2024-10-20 14:28:25', 'normal', 'active');
INSERT INTO `users` VALUES (27, 'មនុស្ស', 'ស្មោះ', 'Mnussmos@gmail.com', '$2y$10$u5qN8QHEoLPROg00HngWnuODBKbLA4Cy5QSWdmpWF9F3tgeV7sizK', NULL, NULL, 'male', '2024-10-21 18:04:29', 'normal', 'active');
INSERT INTO `users` VALUES (28, 'Thannak', 'ven', 'thannk@gamail.com', '$2y$10$NhGnGKt3M5sl/ogO/2Y0cusT.WAkRCGSKc3kb3gFz/V1qLW1j8IRa', NULL, NULL, 'male', '2024-10-21 18:17:06', 'normal', 'active');
INSERT INTO `users` VALUES (29, 'Panha', 'Yom22', 'panha01@gmail.com', '$2y$10$vEW.9yXUqfYrTknPbFZwWulPc75US9cx6CFK45q7m7ldXmgmusE2a', NULL, NULL, 'male', '2024-10-25 19:07:35', 'normal', 'active');
INSERT INTO `users` VALUES (30, 'test', 'Yom', 'test001@gmail.com', '$2y$10$CQvgClZlCzbJ2vRtdMGTRe.WsSdtzmlye4oJ9z3lYH5iAh6DqIhda', '2021-07-13', 'rb_2147880409.png', 'male', '2024-10-26 23:14:43', 'normal', 'active');
INSERT INTO `users` VALUES (32, 'Panha', 'Yom', 'test1@gmail.com', '$2y$10$TdwlesJpKrGVlk6/cLlGs.THsV/bOG/5et67mDl7OFEXKW6ipKx26', NULL, NULL, 'male', '2024-10-27 00:04:10', 'normal', 'inactive');

SET FOREIGN_KEY_CHECKS = 1;
