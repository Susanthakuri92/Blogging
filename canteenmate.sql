-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Generation Time: Jan 24, 2024 at 02:47 PM
-- Server version: 8.0.36
-- PHP Version: 8.3.2

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `canteenmate`
--

-- --------------------------------------------------------

--
-- Table structure for table `comments`
--

CREATE TABLE `comments` (
  `comment_id` int NOT NULL,
  `post_id` int DEFAULT NULL,
  `comment_text` text COLLATE utf16_unicode_ci,
  `comment_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `user_id` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf16 COLLATE=utf16_unicode_ci;

--
-- Dumping data for table `comments`
--

INSERT INTO `comments` (`comment_id`, `post_id`, `comment_text`, `comment_date`, `user_id`) VALUES
(80, 62, 'dvdvdvdv', '2024-01-01 14:21:37', 1),
(81, 62, 'dvvdv', '2024-01-01 14:25:01', 1),
(82, 61, 'dvdvdv', '2024-01-01 14:41:34', 1),
(83, 61, 'cdcdc', '2024-01-01 14:42:23', 1),
(84, 56, 'dcdcdc', '2024-01-01 14:47:52', 1),
(85, 66, 'cdcdcd', '2024-01-01 15:12:05', 1),
(86, 66, 'dvdvdv', '2024-01-02 14:22:57', 1),
(87, 65, 'vddvdv', '2024-01-03 11:06:59', 1),
(88, 65, 'vdvdvdv', '2024-01-03 11:07:02', 1),
(89, 63, 'scscsc', '2024-01-03 11:13:23', 1),
(90, 69, 'dvdvdvdv', '2024-01-03 12:18:59', 1),
(91, 72, 'cscscsc', '2024-01-10 09:06:56', 1),
(92, 72, 'scscsc', '2024-01-11 09:41:11', 1),
(93, 72, 'dvcddvdv', '2024-01-11 09:41:18', 1),
(94, 73, 'best picture', '2024-01-11 10:16:26', 1),
(95, 73, 'ok', '2024-01-11 10:19:35', 13),
(96, 73, 'cvhsjvchjsvc', '2024-01-12 01:28:36', 10),
(97, 73, 'susan', '2024-01-13 06:44:41', 10),
(98, 87, 'dcddvd', '2024-01-20 15:22:03', 15),
(99, 89, 'susan', '2024-01-21 02:46:42', 10),
(100, 88, 'schschs', '2024-01-21 03:14:10', 10);

-- --------------------------------------------------------

--
-- Table structure for table `posts`
--

CREATE TABLE `posts` (
  `post_id` int NOT NULL,
  `content` text COLLATE utf16_unicode_ci NOT NULL,
  `post_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `love_count` int DEFAULT '0',
  `image_path` varchar(255) COLLATE utf16_unicode_ci DEFAULT NULL,
  `user_id` int DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf16 COLLATE=utf16_unicode_ci;

--
-- Dumping data for table `posts`
--

INSERT INTO `posts` (`post_id`, `content`, `post_date`, `love_count`, `image_path`, `user_id`) VALUES
(81, 'Rising from the heart of Paris, the Eiffel Tower, an intricate lattice of iron and steel, pierces the sky like a modern obelisk. Built for the 1889 World\'s Fair, it was once a controversial creation, denounced by artists as an eyesore. Yet, with its daring curves and soaring height, it quickly captured the hearts of Parisians and the world. Today, it stands as an undisputed symbol of romance, elegance, and French ingenuity. Millions ascend its levels each year, marveling at panoramic views of the City of Lights, sparkling beneath the tower\'s golden glow. From intimate picnics beneath its shadow to extravagant dinners in its sky-high restaurants, the Eiffel Tower offers a slice of Parisian magic for every soul drawn to its iconic silhouette. And as the sun dips below the horizon, its twinkling lights illuminate the night, a vibrant reminder that the Iron Lady is more than just a monument, but a beating heart of the city itself.', '2024-01-20 15:07:22', 0, 'uploads/eiffel.jpg', 10),
(82, 'As we navigate the ever-evolving landscape of technology, one trend that stands out prominently is the rapid integration of Artificial Intelligence (AI) into our daily lives. From smart homes to autonomous vehicles, AI is reshaping the way we live and work. In this blog, we will explore the latest advancements in AI technology, discuss its impact on various industries, and delve into the exciting possibilities that lie ahead. Join us on this journey as we uncover the transformative power of AI and how it is revolutionizing the way we experience the world around us.', '2024-01-20 15:14:21', 0, 'uploads/ai.jpeg', 15),
(83, 'Embark on a culinary adventure with us as we explore the diverse and tantalizing world of global flavors. From the spicy streets of Bangkok to the savory markets of Marrakech, join our gastronomic journey where we discover the unique ingredients, cooking techniques, and cultural influences that shape each region\'s cuisine. Whether you\'re a seasoned chef or a home cook looking to spice up your kitchen, our blog will be your passport to a world of delicious discoveries. Get ready to tantalize your taste buds and elevate your cooking skills to new heights.', '2024-01-20 15:16:27', 0, 'uploads/culinary.jpg', 15),
(84, 'Venture into the cosmos with us as we dive into the mesmerizing world of astrophotography. From capturing the beauty of distant galaxies to mastering the art of star trails, our blog will be your guide to creating stunning celestial images. Whether you\'re a seasoned astrophotographer or a beginner with a passion for the stars, join us on this celestial journey as we share tips, techniques, and awe-inspiring visuals that will ignite your love for the night sky.', '2024-01-20 15:18:29', 0, 'uploads/astro.jpg', 15),
(85, 'In the realm of cutting-edge technology, one frontier stands out – quantum computing. As we peer into the future of computation, quantum mechanics is paving the way for unparalleled advancements. In this comprehensive blog series, we will unravel the complexities of quantum computing, exploring the principles of superposition and entanglement that defy classical understanding. We\'ll delve into the race among tech giants to achieve quantum supremacy, discuss the potential applications that could revolutionize fields from cryptography to drug discovery, and ponder the ethical considerations surrounding this quantum leap in computing power. Join us on this intellectual journey to the quantum frontier, where the boundaries of classical computing are being redefined.', '2024-01-20 15:19:21', 0, 'uploads/quantum.jpg', 15),
(86, 'As humanity sets its sights on the stars once again, we find ourselves on the cusp of a new era in space exploration. In this expansive blog series, we will chart the course of the renaissance in space travel – from the renewed interest in lunar missions to the ambitious plans for manned missions to Mars. Delve into the intricacies of space agencies\' collaborative efforts, private companies pushing the boundaries of space tourism, and the burgeoning field of asteroid mining. Join us in contemplating the profound impact these endeavors may have on our understanding of the universe, the potential for human colonization of other celestial bodies, and the broader implications for the future of our species as we reach for the stars.', '2024-01-20 15:19:53', 0, 'uploads/space.png', 15),
(87, 'Step into the immersive world of virtual reality (VR) as we embark on a journey through the digital renaissance that is reshaping how we perceive and interact with technology. In this expansive blog series, we\'ll explore the evolution of VR from its early days to the current state of cutting-edge advancements. From mind-bending virtual worlds to applications in education, healthcare, and entertainment, we\'ll uncover the myriad ways VR is transforming industries and pushing the boundaries of human experience. Join us in decoding the intricate blend of hardware, software, and creative ingenuity that fuels this technological revolution and discover the potential for a future where the lines between the physical and virtual blur into a seamless, captivating tapestry.', '2024-01-20 15:21:56', 0, 'uploads/digital.jpg', 15),
(88, 'Against the backdrop of environmental challenges, the quest for sustainability has become imperative in shaping a resilient future for our planet. In this comprehensive blog series, we\'ll dive into the multifaceted world of sustainable practices, examining everything from eco-friendly innovations to grassroots initiatives making a difference on a local level. Explore the intersection of technology, renewable energy, and conservation efforts as we showcase inspiring stories of individuals, communities, and businesses committed to fostering a greener tomorrow. Join us in navigating the complex web of environmental issues and discovering actionable steps we can take to contribute to a sustainable, harmonious coexistence with nature.', '2024-01-20 15:23:00', 1, 'uploads/greener.png', 15),
(89, 'hello my name is ssuan', '2024-01-20 15:23:53', 2, 'uploads/human.jpg', 15);

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `user_id` int NOT NULL,
  `username` varchar(50) COLLATE utf16_unicode_ci NOT NULL,
  `email` varchar(100) COLLATE utf16_unicode_ci NOT NULL,
  `password` varchar(255) COLLATE utf16_unicode_ci NOT NULL,
  `profile_image` varchar(255) COLLATE utf16_unicode_ci DEFAULT 'default_profile.jpg'
) ENGINE=InnoDB DEFAULT CHARSET=utf16 COLLATE=utf16_unicode_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`user_id`, `username`, `email`, `password`, `profile_image`) VALUES
(1, 'hello', 'hero@gmail.com', '$2y$10$/V..bhZUl5U6CbExcZOjtOFhuPsvs.2YvM9jljZaYzdQOczGQZ4V2', 'default_profile.jpg'),
(6, 'shreyash', 'Susanthakuri92@gmail.com', '$2y$10$sKQaygpKcEOq4k6ywib9yuwB6zT3/JvXClocIIa7gkKQyEXbP3xk.', 'default_profile.jpg'),
(8, 'Hello', 'hello@gmail.com', '$2y$10$Ty/M3pmTC6yehTpfowEEweQnsctq5kYs2G0eLDklE52Ek8OSAoBi.', 'default_profile.jpg'),
(9, 'ujwal', 'ujwal@gmail.com', '$2y$10$im73E8YGJH8Kia.vUs.s6u8AXRI1IqWpArmxB5IYTm2Q4VzXEqzM2', 'default_profile.jpg'),
(10, 'root', 'Susanthakuri92@gmail.com', '$2y$10$qGCtpepK3AIcHpcZnbRqi.slj1jpWBjWY/gZF3KtKlACXAJaVmM7.', 'uploads/default_profile_1705805251.jpg'),
(11, 'shreyash', 'shreeyash@gmail.com', '$2y$10$AM.k0FmoXC2dCA6pwSJwhOvKmRm10Z/2pDZlT7nuhOl7Bxhs7BnC6', 'default_profile.jpg'),
(12, 'shreyash', 'hero@gmail.com', '$2y$10$CH1nNDJpts8vo11Xm0tl/.Dgq0Ppcr6xTAcddtXKqbOZh2uhRC7Pq', 'default_profile.jpg'),
(13, 'sadhan', 'sadhan@gmail.com', '$2y$10$IesorslTQ3rOSn65cxe9puGH28CNsvTf3kV/kG1YYF01duU5RTZB6', 'uploads/Thackray-Museum-of-Medicine-credit-David-Lindsay-5-1920xx1080-1-1024x576_1704981082.jpeg'),
(15, 'susan', 'hero@gmail.com', '$2y$10$yVRPIpJ.EhXNdpxv9T42ee.TC9eEBtKkPK1g1faj/6N1y2VFBmrby', 'uploads/default_profile_1705763732.jpg');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `comments`
--
ALTER TABLE `comments`
  ADD PRIMARY KEY (`comment_id`),
  ADD KEY `comments_ibfk_1` (`user_id`);

--
-- Indexes for table `posts`
--
ALTER TABLE `posts`
  ADD PRIMARY KEY (`post_id`),
  ADD KEY `fk_user_id` (`user_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`user_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `comments`
--
ALTER TABLE `comments`
  MODIFY `comment_id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=101;

--
-- AUTO_INCREMENT for table `posts`
--
ALTER TABLE `posts`
  MODIFY `post_id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=92;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `user_id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `comments`
--
ALTER TABLE `comments`
  ADD CONSTRAINT `comments_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`);

--
-- Constraints for table `posts`
--
ALTER TABLE `posts`
  ADD CONSTRAINT `fk_user_id` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
