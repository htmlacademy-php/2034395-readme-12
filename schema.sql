CREATE DATABASE readme
  DEFAULT CHARACTER SET utf8mb4
  DEFAULT COLLATE utf8mb4_general_ci;

CREATE TABLE `users` (
                       `id` INT AUTO_INCREMENT PRIMARY KEY,
                       `email` VARCHAR(320) UNIQUE,
                       `login` VARCHAR(128) UNIQUE,
                       `password` CHAR(64),
                       `avatar_url` VARCHAR(2048)
);

CREATE TABLE `hashtags` (
                          `id` INT PRIMARY KEY,
                          `list` TEXT
);

CREATE TABLE `content_types` (
                               `id` INT AUTO_INCREMENT PRIMARY KEY,
                               `name` CHAR(64),
                               `class_name` CHAR(64)
);

CREATE TABLE `posts_hashtags` (
                                `id` INT PRIMARY KEY,
                                `hashtags` INT,
                                FOREIGN KEY (`hashtags`) REFERENCES `hashtags` (`id`)
);

CREATE TABLE `posts` (
                       `id` INT AUTO_INCREMENT PRIMARY KEY,
                       `date` TIMESTAMP,
                       `title` TINYTEXT,
                       `content` TEXT,
                       `content_type` INT,
                       `hashtags` INT,
                       `author` INT,
                       `image_url` VARCHAR(2048),
                       `video_url` VARCHAR(2048),
                       `site_url` VARCHAR(2048),
                       `views` INT,
                       FOREIGN KEY (`author`) REFERENCES `users` (`id`),
                       FOREIGN KEY (`content_type`) REFERENCES `content_types` (`id`),
                       FOREIGN KEY (`hashtags`) REFERENCES `posts_hashtags` (`id`)
);

CREATE TABLE `comments` (
                          `id` INT AUTO_INCREMENT PRIMARY KEY,
                          `date` TIMESTAMP,
                          `content` TEXT,
                          `author` INT,
                          `post` INT,
                          FOREIGN KEY (`author`) REFERENCES `users` (`id`),
                          FOREIGN KEY (`post`) REFERENCES `posts` (`id`)
);

CREATE TABLE `likes` (
                       `id` INT AUTO_INCREMENT PRIMARY KEY,
                       `user` INT,
                       `post` INT,
                       FOREIGN KEY (`user`) REFERENCES `users` (`id`),
                       FOREIGN KEY (`post`) REFERENCES `posts` (`id`)
);

CREATE TABLE `messages` (
                          `id` INT AUTO_INCREMENT PRIMARY KEY,
                          `date` TIMESTAMP,
                          `text` TEXT,
                          `sender` INT,
                          `recipient` INT,
                          FOREIGN KEY (`sender`) REFERENCES `users` (`id`),
                          FOREIGN KEY (`recipient`) REFERENCES `users` (`id`)
);

CREATE TABLE `subscriptions` (
                               `user` INT,
                               `subscriber` INT,
                               FOREIGN KEY (`user`) REFERENCES `users` (`id`),
                               FOREIGN KEY (`subscriber`) REFERENCES `users` (`id`)
);
