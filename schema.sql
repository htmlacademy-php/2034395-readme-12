CREATE DATABASE readme2
  DEFAULT CHARACTER SET utf8mb4
  DEFAULT COLLATE utf8mb4_general_ci;

use readme2;

CREATE TABLE `users`
(
  `id`                INT AUTO_INCREMENT PRIMARY KEY,
  `email`             VARCHAR(320) UNIQUE,
  `login`             VARCHAR(128) UNIQUE,
  `password`          CHAR(64),
  `avatar_url`        VARCHAR(2048),
  `registration_date` TIMESTAMP
);

CREATE TABLE `hashtags`
(
  `id`   INT PRIMARY KEY,
  `list` TEXT
);

CREATE TABLE `content_types`
(
  `id`         INT AUTO_INCREMENT PRIMARY KEY,
  `name`       CHAR(64),
  `class_name` CHAR(64),
  `title`      CHAR(64)
);

CREATE TABLE `posts_hashtags`
(
  `id`       INT PRIMARY KEY,
  `hashtags` INT,
  FOREIGN KEY (`hashtags`) REFERENCES `hashtags` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
);

CREATE TABLE `posts`
(
  `id`           INT AUTO_INCREMENT PRIMARY KEY,
  `date`         TIMESTAMP,
  `title`        TINYTEXT,
  `content`      TEXT,
  `cite_author`  TEXT,
  `content_type` INT,
  `hashtags`     INT,
  `author`       INT,
  `image_url`    VARCHAR(2048),
  `video_url`    VARCHAR(2048),
  `site_url`     VARCHAR(2048),
  FOREIGN KEY (`author`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  FOREIGN KEY (`content_type`) REFERENCES `content_types` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  FOREIGN KEY (`hashtags`) REFERENCES `posts_hashtags` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
);

CREATE TABLE `views`
(
  `id`      INT AUTO_INCREMENT PRIMARY KEY,
  `post_id` INT,
  `user_id` INT
);

CREATE TABLE `comments`
(
  `id`      INT AUTO_INCREMENT PRIMARY KEY,
  `date`    TIMESTAMP,
  `content` TEXT,
  `author`  INT,
  `post`    INT,
  FOREIGN KEY (`author`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  FOREIGN KEY (`post`) REFERENCES `posts` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
);

CREATE TABLE `likes`
(
  `id`   INT AUTO_INCREMENT PRIMARY KEY,
  `user` INT,
  `post` INT,
  FOREIGN KEY (`user`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  FOREIGN KEY (`post`) REFERENCES `posts` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
);

CREATE TABLE `messages`
(
  `id`        INT AUTO_INCREMENT PRIMARY KEY,
  `date`      TIMESTAMP,
  `text`      TEXT,
  `sender`    INT,
  `recipient` INT,
  FOREIGN KEY (`sender`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  FOREIGN KEY (`recipient`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
);

CREATE TABLE `subscriptions`
(
  `user`       INT,
  `subscriber` INT,
  FOREIGN KEY (`user`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  FOREIGN KEY (`subscriber`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
);

CREATE FULLTEXT INDEX `posts_ft_search` ON `posts` (`title`, `content`);
