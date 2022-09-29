# Заполнение типов существующего контента
INSERT INTO `content_types` (`name`, `class_name`, `title`)
VALUES ('photo', 'post-photo', 'Фото'),
       ('video', 'post-video', 'Видео'),
       ('text', 'post-text', 'Текст'),
       ('quote', 'post-quote', 'Цитата'),
       ('link', 'post-link', 'Ссылка');
