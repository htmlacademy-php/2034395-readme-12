# Заполнение типов существующего контента
INSERT INTO `content_types` (`name`, `class_name`) VALUES ('quote', 'post-quote');
INSERT INTO `content_types` (`name`, `class_name`) VALUES ('text', 'post-text');
INSERT INTO `content_types` (`name`, `class_name`) VALUES ('photo', 'post-photo');
INSERT INTO `content_types` (`name`, `class_name`) VALUES ('link', 'post-link');
INSERT INTO `content_types` (`name`, `class_name`) VALUES ('video', 'post-video');

# Заполнение тестовых пользователей
INSERT INTO `users` (`email`, `login`, `password`, `avatar_url`)
VALUES
  ('parismay.frontend@mail.ru', 'paris', 'hardPassword', 'userpic.jpg'),
  ('test1@mail.ru', 'Лариса', 'hardPassword', 'userpic-larisa-small.jpg'),
  ('test2@mail.ru', 'Виктор', 'hardPassword', 'userpic-mark.jpg');

# Заполнение тестовых постов
INSERT INTO `posts` (`date`, `title`, `content`, `content_type`, `author`, `views`)
VALUES
  (
    NOW(),
    'Цитата',
    'Мы в жизни любим только раз, а после ищем лишь похожих',
    1,
    2,
    150
  ),
  (
    NOW(),
    'Игра престолов',
    'Не могу дождаться начала финального сезона своего любимого сериала!',
    2,
    1,
    149
  ),
  (
    NOW(),
    'Наконец, обработал фотки',
    'rock-medium.jpg',
    3,
    3,
    148
  ),
  (
    NOW(),
    'Моя мечта',
    'coast-medium.jpg',
    3,
    2,
    147
  ),
  (
    NOW(),
    'Лучшие курсы',
    'www.htmlacademy.ru',
    4,
    1,
    140
  );

# Заполнение тестовых комментариев
INSERT INTO `comments` (`date`, `content`, `author`, `post`)
VALUES
  (
    NOW(),
    'Крутой комментарий к крутому посту!',
    1,
    1
  ),
  (
    NOW(),
    'А этот еще круче!',
    1,
    1
  ),
  (
    NOW(),
    'Тестим комментарии',
    3,
    3
  ),
  (
    NOW(),
    'Кажется, с комментариями пора завязывать',
    3,
    3
  );

# Добавление лайка к тестовому посту
INSERT INTO `likes` (`user`, `post`) VALUES (1, 1);

# Подписка на тестового пользователя
INSERT INTO `subscriptions` (`user`, `subscriber`) VALUES (1, 2);

# Получение ID, типа контента, автора и просмотров из списка тестовых постов, отсортированных по популярности
SELECT * FROM `posts` ORDER BY `views` DESC;

# Получение списка постов, оставленных определенным пользователем
SELECT * FROM `posts` WHERE `author` = 0;

# Получение списка логинов пользователей, которые оставили комментарии для одного поста
SELECT * FROM `comments` WHERE `post` = 0;
