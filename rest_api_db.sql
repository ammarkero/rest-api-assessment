-- -------------------------------------------------------------
-- TablePlus 3.11.0(352)
--
-- https://tableplus.com/
--
-- Database: digitas_db
-- Generation Time: 2023-06-23 16:38:44.1680
-- -------------------------------------------------------------


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;


DROP TABLE IF EXISTS `external_data`;
CREATE TABLE `external_data` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `data` json DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`),
  CONSTRAINT `external_data_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=21 DEFAULT CHARSET=utf8mb4;

DROP TABLE IF EXISTS `images`;
CREATE TABLE `images` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `image_path` varchar(255) NOT NULL,
  `imageable_type` varchar(255) NOT NULL,
  `imageable_id` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4;

DROP TABLE IF EXISTS `posts`;
CREATE TABLE `posts` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(255) NOT NULL,
  `content` text NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4;

DROP TABLE IF EXISTS `roles`;
CREATE TABLE `roles` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4;

DROP TABLE IF EXISTS `user_logs`;
CREATE TABLE `user_logs` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `login_timestamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `logout_timestamp` timestamp NULL DEFAULT NULL,
  `token` varchar(255) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`),
  CONSTRAINT `user_logs_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4;

DROP TABLE IF EXISTS `user_roles`;
CREATE TABLE `user_roles` (
  `user_id` int(11) NOT NULL,
  `role_id` int(11) NOT NULL,
  KEY `user_id` (`user_id`),
  KEY `role_id` (`role_id`),
  CONSTRAINT `user_roles_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`),
  CONSTRAINT `user_roles_ibfk_2` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

DROP TABLE IF EXISTS `users`;
CREATE TABLE `users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4;

INSERT INTO `external_data` (`id`, `user_id`, `data`) VALUES
('1', '2', '{\"id\": 21, \"title\": \"suscipit repellat esse quibusdam voluptatem incidunt\", \"completed\": false}'),
('2', '2', '{\"id\": 22, \"title\": \"distinctio vitae autem nihil ut molestias quo\", \"completed\": true}'),
('3', '2', '{\"id\": 23, \"title\": \"et itaque necessitatibus maxime molestiae qui quas velit\", \"completed\": false}'),
('4', '2', '{\"id\": 24, \"title\": \"adipisci non ad dicta qui amet quaerat doloribus ea\", \"completed\": false}'),
('5', '2', '{\"id\": 25, \"title\": \"voluptas quo tenetur perspiciatis explicabo natus\", \"completed\": true}'),
('6', '2', '{\"id\": 26, \"title\": \"aliquam aut quasi\", \"completed\": true}'),
('7', '2', '{\"id\": 27, \"title\": \"veritatis pariatur delectus\", \"completed\": true}'),
('8', '2', '{\"id\": 28, \"title\": \"nesciunt totam sit blanditiis sit\", \"completed\": false}'),
('9', '2', '{\"id\": 29, \"title\": \"laborum aut in quam\", \"completed\": false}'),
('10', '2', '{\"id\": 30, \"title\": \"nemo perspiciatis repellat ut dolor libero commodi blanditiis omnis\", \"completed\": true}'),
('11', '2', '{\"id\": 31, \"title\": \"repudiandae totam in est sint facere fuga\", \"completed\": false}'),
('12', '2', '{\"id\": 32, \"title\": \"earum doloribus ea doloremque quis\", \"completed\": false}'),
('13', '2', '{\"id\": 33, \"title\": \"sint sit aut vero\", \"completed\": false}'),
('14', '2', '{\"id\": 34, \"title\": \"porro aut necessitatibus eaque distinctio\", \"completed\": false}'),
('15', '2', '{\"id\": 35, \"title\": \"repellendus veritatis molestias dicta incidunt\", \"completed\": true}'),
('16', '2', '{\"id\": 36, \"title\": \"excepturi deleniti adipisci voluptatem et neque optio illum ad\", \"completed\": true}'),
('17', '2', '{\"id\": 37, \"title\": \"sunt cum tempora\", \"completed\": false}'),
('18', '2', '{\"id\": 38, \"title\": \"totam quia non\", \"completed\": false}'),
('19', '2', '{\"id\": 39, \"title\": \"doloremque quibusdam asperiores libero corrupti illum qui omnis\", \"completed\": false}'),
('20', '2', '{\"id\": 40, \"title\": \"totam atque quo nesciunt\", \"completed\": true}');

INSERT INTO `images` (`id`, `image_path`, `imageable_type`, `imageable_id`) VALUES
('1', 'cat.jpg', 'Post', '1'),
('2', 'banana.png', 'Post', '1'),
('3', 'unicorn.jpg', 'Post', '1'),
('4', 'person.jpg', 'User', '2');

INSERT INTO `posts` (`id`, `title`, `content`) VALUES
('1', 'Hello World', 'lorem ipsum dolor sit amet'),
('2', 'Today is the Day', 'lorem ipsum dolor sit amet'),
('3', '5 Best Lakes in the World', 'lorem ipsum dolor sit amet');

INSERT INTO `roles` (`id`, `title`) VALUES
('1', 'Admin'),
('2', 'Customer'),
('3', 'Sales');

INSERT INTO `user_logs` (`id`, `user_id`, `login_timestamp`, `logout_timestamp`, `token`) VALUES
('1', '6', '2023-06-23 16:34:24', '2023-06-23 16:34:24', 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpc3MiOiJsb2NhbGhvc3Q6ODg4OCIsInN1YiI6NiwiaWF0IjoxNjg3NTA5MjI4LCJleHAiOjE2ODc1MDk1Mjh9.5kH7pjWcONvx0aFOpzzEHvPjS9D4ExKGosDonJmfG7Y');

INSERT INTO `user_roles` (`user_id`, `role_id`) VALUES
('1', '2'),
('2', '1'),
('3', '1'),
('4', '2'),
('5', '3'),
('6', '1');

INSERT INTO `users` (`id`, `name`, `email`, `password`) VALUES
('1', 'Maurine Terry', 'qwyman@bradtke.com', '$2y$10$iWcUrUyFYgBx5MVnGZHJpu9NbTf9PO1B5bIZg3ih4g8p1SgzCgMBG'),
('2', 'Prof. Dino Kling V', 'walton.parker@prohaska.com', '$2y$10$NYkm0CJPM3P.bMJ.XSLaOuOsQgxqr4gKkaRz/9Qy859.pEaaLXAba'),
('3', 'Lacey Hermiston DDS', 'tre46@yahoo.com', '$2y$10$7J/AgXwoE1/FEalT/mcbv.yTNyw6Fno1jIs9fkwectsHp4viI2Lsa'),
('4', 'Maryjane Bernier', 'bonita30@hotmail.com', '$2y$10$Jc0XVjBBO/S2bnxrttlkg.teNJThPD9ZBr/yh.XrvtQ3pHgFSECpy'),
('5', 'Ms. Tatyana Kuvalis I', 'lgutkowski@douglas.com', '$2y$10$iYpbY7IWDw2U6n9hphMx7uADPduoAL4I2J1bUyAK/c9JLFaXlZyNi'),
('6', 'Xavier', 'hello@xavier.com', '$2y$10$hSXbF152UtcrfPl0meRdGu//MopV3ux14omSxENKGe7R25NhOeP3O');



/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;