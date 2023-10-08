-- phpMyAdmin SQL Dump
-- version 4.8.3
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: May 30, 2023 at 05:43 PM
-- Server version: 10.1.36-MariaDB
-- PHP Version: 7.0.32

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `shop`
--

-- --------------------------------------------------------

--
-- Table structure for table `carts`
--

CREATE TABLE `carts` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `total` int(11) NOT NULL,
  `status` tinyint(4) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `carts`
--

INSERT INTO `carts` (`id`, `user_id`, `total`, `status`) VALUES
(13, 13, 210, 1),
(14, 13, 170, 1),
(15, 13, 70, 1),
(16, 13, 114, 1),
(17, 13, 30, 1),
(18, 13, 60, 1);

-- --------------------------------------------------------

--
-- Table structure for table `cart_items`
--

CREATE TABLE `cart_items` (
  `id` int(11) NOT NULL,
  `cart_id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `quantity` int(11) NOT NULL,
  `price` int(11) NOT NULL,
  `total` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `cart_items`
--

INSERT INTO `cart_items` (`id`, `cart_id`, `product_id`, `quantity`, `price`, `total`) VALUES
(45, 13, 7, 1, 35, 35),
(46, 13, 16, 3, 45, 135),
(47, 13, 17, 2, 15, 30),
(48, 13, 18, 1, 10, 10),
(49, 14, 16, 3, 45, 135),
(50, 14, 7, 1, 35, 35),
(51, 15, 7, 1, 35, 35),
(52, 15, 9, 1, 15, 15),
(53, 15, 18, 2, 10, 20),
(54, 16, 15, 1, 24, 24),
(55, 16, 16, 2, 45, 90),
(56, 17, 17, 2, 15, 30),
(57, 18, 16, 1, 45, 45),
(58, 18, 17, 1, 15, 15);

-- --------------------------------------------------------

--
-- Table structure for table `categories`
--

CREATE TABLE `categories` (
  `ID` int(11) NOT NULL COMMENT 'Category ID',
  `Name` varchar(255) NOT NULL COMMENT 'Category Name',
  `Description` text NOT NULL COMMENT 'Category Description',
  `Parent` int(11) NOT NULL,
  `Ordering` int(11) NOT NULL COMMENT 'Category order to appear',
  `Visibility` tinyint(4) NOT NULL DEFAULT '0' COMMENT 'Category Visibility Default (0) Visible  ',
  `Allow_Comment` tinyint(4) NOT NULL DEFAULT '0' COMMENT 'Category Allow_Comment Default (0) allowed ',
  `Allow_Ads` tinyint(4) NOT NULL DEFAULT '0' COMMENT 'Category Allow_Ads Default (0) allowed '
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `categories`
--

INSERT INTO `categories` (`ID`, `Name`, `Description`, `Parent`, `Ordering`, `Visibility`, `Allow_Comment`, `Allow_Ads`) VALUES
(4, 'Hand Made', 'Hand Made Items', 0, 1, 0, 0, 0),
(5, 'Computers ', 'Computers Items', 0, 2, 0, 0, 0),
(6, 'Call Phone ', 'Call Phone Items', 5, 3, 1, 1, 0),
(7, 'Clothing', 'Clothing and Fashion', 0, 4, 0, 0, 0),
(8, 'Home Tools', 'Home Tools', 0, 5, 0, 0, 0),
(9, 'Nokie', 'NEW Nokie', 6, 7, 0, 0, 0),
(10, 'samsung', 'new samsung', 6, 8, 0, 0, 0);

-- --------------------------------------------------------

--
-- Table structure for table `comments`
--

CREATE TABLE `comments` (
  `c_id` int(11) NOT NULL,
  `comment` text NOT NULL,
  `status` tinyint(4) NOT NULL,
  `c_date` date NOT NULL,
  `item_ID` int(11) NOT NULL,
  `user_ID` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `comments`
--

INSERT INTO `comments` (`c_id`, `comment`, `status`, `c_date`, `item_ID`, `user_ID`) VALUES
(1, 'this is an comment', 1, '2023-05-30', 13, 10),
(3, 'ewwerewrw', 1, '2023-05-30', 15, 10),
(4, 'this is a \r\ngood mouse', 1, '2023-05-30', 15, 10),
(6, '                                tshirt all sizes                            ', 0, '2023-05-30', 7, 1),
(7, 'nice shorts', 1, '2023-05-30', 10, 10),
(8, 'asmaa comment', 1, '2023-05-30', 9, 10),
(9, 'nice football!', 1, '2023-05-30', 7, 13);

-- --------------------------------------------------------

--
-- Table structure for table `items`
--

CREATE TABLE `items` (
  `item_id` int(11) NOT NULL,
  `item_name` varchar(255) NOT NULL,
  `item_description` text NOT NULL,
  `item_price` varchar(255) NOT NULL,
  `add_date` date NOT NULL,
  `country_made` varchar(255) NOT NULL,
  `item_image` varchar(255) NOT NULL,
  `item_status` varchar(255) NOT NULL,
  `item_rating` smallint(6) NOT NULL,
  `Approve` tinyint(4) NOT NULL DEFAULT '0',
  `Cat_ID` int(11) NOT NULL,
  `Member_ID` int(11) NOT NULL,
  `tags` varchar(55) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `items`
--

INSERT INTO `items` (`item_id`, `item_name`, `item_description`, `item_price`, `add_date`, `country_made`, `item_image`, `item_status`, `item_rating`, `Approve`, `Cat_ID`, `Member_ID`, `tags`) VALUES
(7, 'Football 2023', 'football for all ', '$35', '2023-05-30', 'egy', '3707Football_Pallo_valmiina-cropped.jpg', '1', 0, 1, 8, 10, 'new , football'),
(8, 'Iphone ', 'new Iphone', '$1500', '2023-05-30', 'egy', '3157download.jpeg-2.jpg', '1', 0, 0, 6, 10, 'iphone , new'),
(9, 'mouse', 'apple mouse', '15', '2023-05-30', 'egy', '', '2', 0, 1, 5, 7, ''),
(10, 'mouse', 'apple mouse', '15', '2023-05-30', 'egy', '', '2', 0, 0, 5, 7, ''),
(11, 'mouse', 'apple mouse', '15', '2023-05-30', 'egy', '', '2', 0, 1, 5, 7, ''),
(12, 'mouse', 'apple mouse', '15', '2023-05-30', 'egy', '', '2', 0, 0, 5, 7, ''),
(13, 'mouse', 'apple mouse', '15', '2023-05-30', 'egy', '', '2', 0, 1, 5, 7, ''),
(15, 'keyboard', 'keyboard keyboard', '$24', '2023-05-30', 'egy', '', '1', 0, 1, 5, 7, ''),
(16, 't-shirt', 'good t-shirt all sizes', '$45', '2023-05-30', 'egy', '772images.jpeg-9.jpg', '1', 0, 1, 7, 10, 'clothes , new '),
(17, 'mouse', 'apple mouse', '15', '2023-05-30', 'egy', '6990images.jpeg-8.jpg', '2', 0, 1, 5, 7, ''),
(18, 'Apple Image', 'funny apple Image ', '$10', '2023-05-30', 'egy', '8013Funny Apple.jpg', '1', 0, 1, 8, 13, 'new , apple'),
(19, 'new one to try image', 'try add an image when Create New Item', '12', '2023-05-30', 'Egy', '7579830f71015b4a7383998416fe7f07c7eb.png', '1', 0, 1, 4, 13, 'new , good');

-- --------------------------------------------------------

--
-- Table structure for table `orders`
--

CREATE TABLE `orders` (
  `id` int(11) NOT NULL,
  `order_code` varchar(55) NOT NULL,
  `user_id` int(11) NOT NULL,
  `fullName` varchar(55) NOT NULL,
  `address` varchar(100) NOT NULL,
  `phone` int(11) NOT NULL,
  `email` varchar(80) NOT NULL,
  `status` int(11) NOT NULL,
  `total` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `orders`
--

INSERT INTO `orders` (`id`, `order_code`, `user_id`, `fullName`, `address`, `phone`, `email`, `status`, `total`) VALUES
(12, '#6363@#$', 13, 'ismael mohamed ahmed', 'my address is here', 2147483647, 'test@gmail.com', 1, 210),
(13, '#5935@#$', 13, 'ismael mohamed ahmed', 'smae as last order', 2147483647, 'test2@gmail.com', 1, 170),
(14, '#9832@#$', 13, 'ismael mohamed ahmed', 'same as all time 24st', 2147483647, 'test3@gmail.com', 2, 70),
(15, '#4945@#$', 13, 'ismael mohamed ahmed', 'you already new where am i', 2147483647, 'testtest@gmail.com', 1, 114),
(16, '#535@#$', 13, 'ismael mohamed ahmed', 'you already new where am i', 2147483647, 'testtest@gmail.com', 2, 30);

-- --------------------------------------------------------

--
-- Table structure for table `order_items`
--

CREATE TABLE `order_items` (
  `id` int(11) NOT NULL,
  `order_id` int(11) NOT NULL,
  `prodeuct_id` int(11) NOT NULL,
  `quntity` int(11) NOT NULL,
  `total` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `order_items`
--

INSERT INTO `order_items` (`id`, `order_id`, `prodeuct_id`, `quntity`, `total`) VALUES
(93, 12, 7, 1, 35),
(94, 12, 16, 3, 135),
(95, 12, 17, 2, 30),
(96, 12, 18, 1, 10),
(97, 13, 16, 3, 135),
(98, 13, 7, 1, 35),
(99, 14, 7, 1, 35),
(100, 14, 9, 1, 15),
(101, 14, 18, 2, 20),
(102, 15, 15, 1, 24),
(103, 15, 16, 2, 90),
(104, 16, 17, 2, 30);

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `UserID` int(11) NOT NULL COMMENT 'To identify user',
  `UserName` varchar(255) NOT NULL COMMENT 'Username to login',
  `Password` varchar(255) NOT NULL COMMENT 'Password to login',
  `Email` varchar(255) NOT NULL,
  `FullName` varchar(255) NOT NULL COMMENT 'User real name',
  `GroupID` int(11) NOT NULL DEFAULT '0' COMMENT 'Identify User Group [admin , user]',
  `Trust Status` int(11) NOT NULL DEFAULT '0' COMMENT 'seller Rank',
  `RegStatus` int(11) NOT NULL DEFAULT '0' COMMENT 'User Approval',
  `Date` date NOT NULL,
  `avatar` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`UserID`, `UserName`, `Password`, `Email`, `FullName`, `GroupID`, `Trust Status`, `RegStatus`, `Date`, `avatar`) VALUES
(1, 'aliahmed', '601f1889667efaebb33b8c12572835da3f027f78', 'aliali@gmail.com', 'ali ahmed mohamed', 1, 0, 1, '0000-00-00', ''),
(2, 'mohmed', '601f1889667efaebb33b8c12572835da3f027f78', 'mohmed@gmail.com', 'mohmedahmed mohamed', 0, 0, 1, '0000-00-00', ''),
(4, 'mostafa', '601f1889667efaebb33b8c12572835da3f027f78', 'mostafa@gmila.com', 'mostafa  ahmed ', 0, 0, 1, '0000-00-00', ''),
(5, 'asmaa		', '601f1889667efaebb33b8c12572835da3f027f78', 'asmaa@gmail.com', 'asmaa ahmed mohamed', 0, 0, 1, '0000-00-00', ''),
(7, 'nournour', '601f1889667efaebb33b8c12572835da3f027f78', 'nour@gmial.com', 'noue osama ali', 0, 0, 1, '2023-05-30', ''),
(9, 'aliali', '123123', 'alialial1@gmail.com', 'ali ali mohamed', 0, 0, 0, '0000-00-00', ''),
(10, 'saadsaad', '601f1889667efaebb33b8c12572835da3f027f78', 'saad@gmail.com', 'saad saad saad', 0, 0, 1, '2023-05-30', ''),
(11, 'saadas', 'da39a3ee5e6b4b0d3255bfef95601890afd80709', 'saadsaad@gmial.com', 'laskdmsaklk', 0, 0, 0, '2023-05-30', ''),
(12, 'eman123', '601f1889667efaebb33b8c12572835da3f027f78', 'eman123@gmail.com', 'eman ahmed ahmed', 0, 0, 1, '2023-05-30', ''),
(13, 'ismaelsmael', '601f1889667efaebb33b8c12572835da3f027f78', 'ismael@gmail.com', 'ismael mohamed ahmed', 0, 0, 1, '2023-05-30', '77216_baby chewbacca.jpg');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `carts`
--
ALTER TABLE `carts`
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
-- Indexes for table `categories`
--
ALTER TABLE `categories`
  ADD PRIMARY KEY (`ID`),
  ADD UNIQUE KEY `Name` (`Name`);

--
-- Indexes for table `comments`
--
ALTER TABLE `comments`
  ADD PRIMARY KEY (`c_id`),
  ADD KEY `item_comment` (`item_ID`),
  ADD KEY `user_comment` (`user_ID`);

--
-- Indexes for table `items`
--
ALTER TABLE `items`
  ADD PRIMARY KEY (`item_id`),
  ADD KEY `cat_1` (`Cat_ID`),
  ADD KEY `member_1` (`Member_ID`);

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
  ADD KEY `prodeuct_id` (`prodeuct_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`UserID`),
  ADD UNIQUE KEY `UserName` (`UserName`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `carts`
--
ALTER TABLE `carts`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=24;

--
-- AUTO_INCREMENT for table `cart_items`
--
ALTER TABLE `cart_items`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=77;

--
-- AUTO_INCREMENT for table `categories`
--
ALTER TABLE `categories`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT COMMENT 'Category ID', AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `comments`
--
ALTER TABLE `comments`
  MODIFY `c_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `items`
--
ALTER TABLE `items`
  MODIFY `item_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;

--
-- AUTO_INCREMENT for table `orders`
--
ALTER TABLE `orders`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT for table `order_items`
--
ALTER TABLE `order_items`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=105;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `UserID` int(11) NOT NULL AUTO_INCREMENT COMMENT 'To identify user', AUTO_INCREMENT=14;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `carts`
--
ALTER TABLE `carts`
  ADD CONSTRAINT `carts_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`UserID`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `cart_items`
--
ALTER TABLE `cart_items`
  ADD CONSTRAINT `cart_items_ibfk_1` FOREIGN KEY (`cart_id`) REFERENCES `carts` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `cart_items_ibfk_2` FOREIGN KEY (`product_id`) REFERENCES `items` (`item_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `comments`
--
ALTER TABLE `comments`
  ADD CONSTRAINT `item_comment` FOREIGN KEY (`item_ID`) REFERENCES `items` (`item_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `user_comment` FOREIGN KEY (`user_ID`) REFERENCES `users` (`UserID`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `items`
--
ALTER TABLE `items`
  ADD CONSTRAINT `cat_1` FOREIGN KEY (`Cat_ID`) REFERENCES `categories` (`ID`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `member_1` FOREIGN KEY (`Member_ID`) REFERENCES `users` (`UserID`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `orders`
--
ALTER TABLE `orders`
  ADD CONSTRAINT `orders_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`UserID`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `order_items`
--
ALTER TABLE `order_items`
  ADD CONSTRAINT `order_items_ibfk_1` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `order_items_ibfk_2` FOREIGN KEY (`prodeuct_id`) REFERENCES `items` (`item_id`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
