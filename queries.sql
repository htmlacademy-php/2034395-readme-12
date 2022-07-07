# Заполнение типов существующего контента
INSERT INTO content_types (`name`, class_name) VALUES ('quote', 'post-quote');
INSERT INTO content_types (`name`, class_name) VALUES ('text', 'post-text');
INSERT INTO content_types (`name`, class_name) VALUES ('photo', 'post-photo');
INSERT INTO content_types (`name`, class_name) VALUES ('link', 'post-link');
INSERT INTO content_types (`name`, class_name) VALUES ('video', 'post-video');

# Заполнение тестовых пользователей
INSERT INTO users (email, login, `password`, avatar_url) VALUES (
                                                                  'parismay.frontend@mail.ru', 'paris', 'hardPassword', 'userpic.jpg'
                                                                );
INSERT INTO users (email, login, `password`, avatar_url) VALUES (
                                                                  'test1@mail.ru', 'Лариса', 'hardPassword', 'userpic-larisa-small.jpg'
                                                                );
INSERT INTO users (email, login, `password`, avatar_url) VALUES (
                                                                  'test2@mail.ru', 'Виктор', 'hardPassword', 'userpic-mark.jpg'
                                                                );

# Заполнение тестовых постов
INSERT INTO posts (`date`, title, content, content_type, hashtags, author, image_url, video_url, site_url, views)
VALUES (
         '1649697848',
         'Цитата',
         'Мы в жизни любим только раз, а после ищем лишь похожих',
         0,
         null,
         1,
         null,
         null,
         null,
         150
       );
INSERT INTO posts (`date`, title, content, content_type, hashtags, author, image_url, video_url, site_url, views)
VALUES (
         '1649634090',
         'Игра престолов',
         'Не могу дождаться начала финального сезона своего любимого сериала!',
         1,
         null,
         0,
         null,
         null,
         null,
         149
       );
INSERT INTO posts (`date`, title, content, content_type, hashtags, author, image_url, video_url, site_url, views)
VALUES (
         '1649632090',
         'Наконец, обработал фотки',
         'rock-medium.jpg',
         2,
         null,
         2,
         null,
         null,
         null,
         148
       );
INSERT INTO posts (`date`, title, content, content_type, hashtags, author, image_url, video_url, site_url, views)
VALUES (
         '1649332090',
         'Моя мечта',
         'coast-medium.jpg',
         2,
         null,
         1,
         null,
         null,
         null,
         147
       );
INSERT INTO posts (`date`, title, content, content_type, hashtags, author, image_url, video_url, site_url, views)
VALUES (
         '1349332090',
         'Лучшие курсы',
         'www.htmlacademy.ru',
         3,
         null,
         0,
         null,
         null,
         null,
         140
       );

# Заполнение тестовых комментариев
INSERT INTO comments (`date`, content, author, post) VALUES (
                                                              '1649697828',
                                                              'Крутой комментарий к крутому посту!',
                                                              0,
                                                              0
                                                            );
INSERT INTO comments (`date`, content, author, post) VALUES (
                                                              '1649697830',
                                                              'А этот еще круче!',
                                                              0,
                                                              0
                                                            );
INSERT INTO comments (`date`, content, author, post) VALUES (
                                                              '1649697848',
                                                              'Тестим комментарии',
                                                              1,
                                                              1
                                                            );
INSERT INTO comments (`date`, content, author, post) VALUES (
                                                              '1649697848',
                                                              'Кажется, с комментариями пора завязывать',
                                                              2,
                                                              2
                                                            );

# Добавление лайка к тестовому посту
INSERT INTO likes (`user`, post) VALUES (0, 0);

# Подписка на тестового пользователя
INSERT INTO subscriptions (`user`, subscriber) VALUES (0, 1);

# Получение ID, типа контента, автора и просмотров из списка тестовых постов, отсортированных по популярности
SELECT (id, content_type, author, views) FROM posts ORDER BY views DESC;

# Получение списка постов, оставленных определенным пользователем
SELECT * FROM posts WHERE author = 0;

# Получение списка логинов пользователей, которые оставили комментарии для одного поста
SELECT * FROM comments WHERE post = 0;
