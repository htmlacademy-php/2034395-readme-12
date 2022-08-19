<main class="page__main page__main--profile">
    <h1 class="visually-hidden">Профиль</h1>
    <div class="profile profile--default">
        <div class="profile__user-wrapper">
            <div class="profile__user user container">
                <div class="profile__user-info user__info">
                    <div class="profile__avatar user__avatar">
                        <img class="profile__picture user__picture" src="../img/userpic-medium.jpg" alt="Аватар пользователя">
                    </div>
                    <div class="profile__name-wrapper user__name-wrapper">
                        <span class="profile__name user__name"><?= htmlspecialchars($profile['login']) ?></span>
                        <time class="profile__user-time user__time" datetime="2014-03-20">
                            <?= normalizeDate($profile['registration_date']) . " на сайте" ?>
                        </time>
                    </div>
                </div>
                <div class="profile__rating user__rating">
                    <p class="profile__rating-item user__rating-item user__rating-item--publications">
                        <span class="user__rating-amount"><?= count($posts) ?></span>
                        <span class="profile__rating-text user__rating-text">
                            <?= get_noun_plural_form(count($posts), 'публикация', 'публикации', 'публикаций') ?>
                        </span>
                    </p>
                    <p class="profile__rating-item user__rating-item user__rating-item--subscribers">
                        <span class="user__rating-amount"><?= count(getSubs($link, $profile['id'])) ?></span>
                        <span class="profile__rating-text user__rating-text"><?= get_noun_plural_form(count(getSubs($link, $profile['id'])), 'подписчик', 'подписчика', 'подписчиков') ?></span>
                    </p>
                </div>
                <?php if (!$is_owner): ?>
                    <div class="profile__user-buttons user__buttons">
                        <?php if (!$is_subscribed): ?>
                            <a
                                class="profile__user-button user__button user__button--subscription button button--main"
                                href="subscription.php?action=sub&address=profile&profile_id=<?= $profile['id'] ?>"
                            >
                                Подписаться
                            </a>
                        <?php else: ?>
                            <a
                                class="profile__user-button user__button user__button--subscription button button--main"
                                href="subscription.php?action=unsub&address=profile&profile_id=<?= $profile['id'] ?>"
                            >
                                Отписаться
                            </a>
                        <?php endif; ?>
                        <a class="profile__user-button user__button user__button--writing button button--green" href="#">Сообщение</a>
                    </div>
                <?php endif; ?>
            </div>
        </div>
        <div class="profile__tabs-wrapper tabs">
            <div class="container">
                <div class="profile__tabs filters">
                    <b class="profile__tabs-caption filters__caption">Показать:</b>
                    <ul class="profile__tabs-list filters__list tabs__list">
                        <li class="profile__tabs-item filters__item">
                            <a class="profile__tabs-link filters__button filters__button--active tabs__item tabs__item--active button">Посты</a>
                        </li>
                        <li class="profile__tabs-item filters__item">
                            <a class="profile__tabs-link filters__button tabs__item button" href="#">Лайки</a>
                        </li>
                        <li class="profile__tabs-item filters__item">
                            <a class="profile__tabs-link filters__button tabs__item button" href="#">Подписки</a>
                        </li>
                    </ul>
                </div>
                <div class="profile__tab-content">
                    <section class="profile__posts tabs__content tabs__content--active">
                        <h2 class="visually-hidden">Публикации</h2>
                        <?php foreach($posts as $post): ?>
                        <?php $is_liked = isPostLiked($link, $user['id'], $post['id']); ?>
                            <article class="profile__post post <?= $post['class_name'] ?>">
                                <header class="post__header">
                                    <h2><a href="post.php?id=<?= $post['id'] ?>"><?= htmlspecialchars($post['title']) ?></a></h2>
                                </header>
                                <div class="post__main">
                                    <?php if($post['name'] == 'quote'): ?>
                                        <blockquote>
                                            <p>
                                                <?= htmlspecialchars($post['content']) ?>
                                            </p>
                                            <cite><?= htmlspecialchars($post['cite_author']) ?></cite>
                                        </blockquote>
                                    <?php endif; ?>

                                    <?php if($post['name'] == 'text'): ?>
                                        <?php $postTextData = showData($post['content']) ?>
                                        <p><?= htmlspecialchars($postTextData['text']) ?></p>
                                        <?php if($postTextData['isLong']): ?>
                                            <a class="post-text__more-link" href="post.php?id=<?= $post['id'] ?>">Читать далее</a>
                                        <?php endif; ?>
                                    <?php endif; ?>

                                    <?php if($post['name'] == 'photo'): ?>
                                        <div class="post-photo__image-wrapper">
                                            <img src="<?= $post['image_url'] ?>" alt="Фото от пользователя" width="360" height="240">
                                        </div>
                                    <?php endif; ?>

                                    <?php if($post['name'] == 'link'): ?>
                                        <div class="post-link__wrapper">
                                            <a class="post-link__external" target="_blank" href="<?= $post['site_url'] ?>" title="Перейти по ссылке">
                                                <div class="post-link__info-wrapper">
                                                    <div class="post-link__icon-wrapper">
                                                        <img src="https://www.google.com/s2/favicons?domain=vitadental.ru" alt="Иконка">
                                                    </div>
                                                    <div class="post-link__info">
                                                        <h3><?= htmlspecialchars($post['title']) ?></h3>
                                                    </div>
                                                </div>
                                                <span><?= htmlspecialchars($post['site_url']) ?></span>
                                            </a>
                                        </div>
                                    <?php endif; ?>

                                    <?php if($post['name'] == 'video'): ?>
                                        <div class="post-video__block">
                                            <div class="post-video__preview">
                                                <?= embed_youtube_cover($post['video_url']); ?>
                                            </div>
                                            <a href="../post.php?id=<?= $post['id'] ?>" class="post-video__play-big button">
                                                <svg class="post-video__play-big-icon" width="14" height="14">
                                                    <use xlink:href="#icon-video-play-big"></use>
                                                </svg>
                                                <span class="visually-hidden">Запустить проигрыватель</span>
                                            </a>
                                        </div>
                                    <?php endif; ?>
                                </div>
                                <footer class="post__footer">
                                    <div class="post__indicators">
                                        <div class="post__buttons">
                                            <?php if (!$is_liked): ?>
                                                <a
                                                    class="post__indicator post__indicator--likes button"
                                                    href="like.php?action=like&address=profile&post_id=<?= $post['id'] ?>&profile_id=<?= $profile['id'] ?>"
                                                    title="Поставить лайк"
                                                >
                                            <?php else: ?>
                                                <a
                                                    class="post__indicator post__indicator--likes-active button"
                                                    href="like.php?action=unlike&address=profile&post_id=<?= $post['id'] ?>&profile_id=<?= $profile['id'] ?>"
                                                    title="Убрать лайк"
                                                >
                                            <?php endif; ?>
                                                <svg class="post__indicator-icon" width="20" height="17">
                                                    <use xlink:href="#icon-heart"></use>
                                                </svg>
                                                <svg class="post__indicator-icon post__indicator-icon--like-active" width="20" height="17">
                                                    <use xlink:href="#icon-heart-active"></use>
                                                </svg>
                                                <span><?= count(getPostLikes($link, $post['id'])) ?></span>
                                                <span class="visually-hidden">количество лайков</span>
                                            </a>
                                            <a class="post__indicator post__indicator--repost button" href="#" title="Репост">
                                                <svg class="post__indicator-icon" width="19" height="17">
                                                    <use xlink:href="#icon-repost"></use>
                                                </svg>
                                                <span>5</span>
                                                <span class="visually-hidden">количество репостов</span>
                                            </a>
                                        </div>
                                        <time class="post__time" datetime="<?= $post['date'] ?>">
                                            <?= normalizeDate($post['date']) . " назад" ?>
                                        </time>
                                    </div>
                                    <ul class="post__tags">
                                        <li><a href="#">#nature</a></li>
                                        <li><a href="#">#globe</a></li>
                                        <li><a href="#">#photooftheday</a></li>
                                        <li><a href="#">#canon</a></li>
                                        <li><a href="#">#landscape</a></li>
                                        <li><a href="#">#щикарныйвид</a></li>
                                    </ul>
                                </footer>
                                <div class="comments">
                                    <a class="comments__button button" href="post.php?id=<?= $post['id'] ?>">Показать комментарии</a>
                                </div>
                            </article>
                        <?php endforeach; ?>
                    </section>

                    <section class="profile__likes tabs__content">
                        <h2 class="visually-hidden">Лайки</h2>
                        <ul class="profile__likes-list">
                            <li class="post-mini post-mini--photo post user">
                                <div class="post-mini__user-info user__info">
                                    <div class="post-mini__avatar user__avatar">
                                        <a class="user__avatar-link" href="#">
                                            <img class="post-mini__picture user__picture" src="../img/userpic-petro.jpg" alt="Аватар пользователя">
                                        </a>
                                    </div>
                                    <div class="post-mini__name-wrapper user__name-wrapper">
                                        <a class="post-mini__name user__name" href="#">
                                            <span>Петр Демин</span>
                                        </a>
                                        <div class="post-mini__action">
                                            <span class="post-mini__activity user__additional">Лайкнул вашу публикацию</span>
                                            <time class="post-mini__time user__additional" datetime="2014-03-20T20:20">5 минут назад</time>
                                        </div>
                                    </div>
                                </div>
                                <div class="post-mini__preview">
                                    <a class="post-mini__link" href="#" title="Перейти на публикацию">
                                        <div class="post-mini__image-wrapper">
                                            <img class="post-mini__image" src="../img/rock-small.png" width="109" height="109" alt="Превью публикации">
                                        </div>
                                        <span class="visually-hidden">Фото</span>
                                    </a>
                                </div>
                            </li>
                            <li class="post-mini post-mini--text post user">
                                <div class="post-mini__user-info user__info">
                                    <div class="post-mini__avatar user__avatar">
                                        <a class="user__avatar-link" href="#">
                                            <img class="post-mini__picture user__picture" src="../img/userpic-petro.jpg" alt="Аватар пользователя">
                                        </a>
                                    </div>
                                    <div class="post-mini__name-wrapper user__name-wrapper">
                                        <a class="post-mini__name user__name" href="#">
                                            <span>Петр Демин</span>
                                        </a>
                                        <div class="post-mini__action">
                                            <span class="post-mini__activity user__additional">Лайкнул вашу публикацию</span>
                                            <time class="post-mini__time user__additional" datetime="2014-03-20T20:05">15 минут назад</time>
                                        </div>
                                    </div>
                                </div>
                                <div class="post-mini__preview">
                                    <a class="post-mini__link" href="#" title="Перейти на публикацию">
                                        <span class="visually-hidden">Текст</span>
                                        <svg class="post-mini__preview-icon" width="20" height="21">
                                            <use xlink:href="#icon-filter-text"></use>
                                        </svg>
                                    </a>
                                </div>
                            </li>
                            <li class="post-mini post-mini--video post user">
                                <div class="post-mini__user-info user__info">
                                    <div class="post-mini__avatar user__avatar">
                                        <a class="user__avatar-link" href="#">
                                            <img class="post-mini__picture user__picture" src="../img/userpic-petro.jpg" alt="Аватар пользователя">
                                        </a>
                                    </div>
                                    <div class="post-mini__name-wrapper user__name-wrapper">
                                        <a class="post-mini__name user__name" href="#">
                                            <span>Петр Демин</span>
                                        </a>
                                        <div class="post-mini__action">
                                            <span class="post-mini__activity user__additional">Лайкнул вашу публикацию</span>
                                            <time class="post-mini__time user__additional" datetime="2014-03-20T18:20">2 часа назад</time>
                                        </div>
                                    </div>
                                </div>
                                <div class="post-mini__preview">
                                    <a class="post-mini__link" href="#" title="Перейти на публикацию">
                                        <div class="post-mini__image-wrapper">
                                            <img class="post-mini__image" src="../img/coast-small.png" width="109" height="109" alt="Превью публикации">
                                            <span class="post-mini__play-big">
                            <svg class="post-mini__play-big-icon" width="12" height="13">
                              <use xlink:href="#icon-video-play-big"></use>
                            </svg>
                          </span>
                                        </div>
                                        <span class="visually-hidden">Видео</span>
                                    </a>
                                </div>
                            </li>
                            <li class="post-mini post-mini--quote post user">
                                <div class="post-mini__user-info user__info">
                                    <div class="post-mini__avatar user__avatar">
                                        <a class="user__avatar-link" href="#">
                                            <img class="post-mini__picture user__picture" src="../img/userpic-petro.jpg" alt="Аватар пользователя">
                                        </a>
                                    </div>
                                    <div class="post-mini__name-wrapper user__name-wrapper">
                                        <a class="post-mini__name user__name" href="#">
                                            <span>Петр Демин</span>
                                        </a>
                                        <div class="post-mini__action">
                                            <span class="post-mini__activity user__additional">Лайкнул вашу публикацию</span>
                                            <time class="post-mini__time user__additional" datetime="2014-03-15T20:05">5 дней назад</time>
                                        </div>
                                    </div>
                                </div>
                                <div class="post-mini__preview">
                                    <a class="post-mini__link" href="#" title="Перейти на публикацию">
                                        <span class="visually-hidden">Цитата</span>
                                        <svg class="post-mini__preview-icon" width="21" height="20">
                                            <use xlink:href="#icon-filter-quote"></use>
                                        </svg>
                                    </a>
                                </div>
                            </li>
                            <li class="post-mini post-mini--link post user">
                                <div class="post-mini__user-info user__info">
                                    <div class="post-mini__avatar user__avatar">
                                        <a class="user__avatar-link" href="#">
                                            <img class="post-mini__picture user__picture" src="../img/userpic-petro.jpg" alt="Аватар пользователя">
                                        </a>
                                    </div>
                                    <div class="post-mini__name-wrapper user__name-wrapper">
                                        <a class="post-mini__name user__name" href="#">
                                            <span>Петр Демин</span>
                                        </a>
                                        <div class="post-mini__action">
                                            <span class="post-mini__activity user__additional">Лайкнул вашу публикацию</span>
                                            <time class="post-mini__time user__additional" datetime="2014-03-20T20:05">в далеком 2007-ом</time>
                                        </div>
                                    </div>
                                </div>
                                <div class="post-mini__preview">
                                    <a class="post-mini__link" href="#" title="Перейти на публикацию">
                                        <span class="visually-hidden">Ссылка</span>
                                        <svg class="post-mini__preview-icon" width="21" height="18">
                                            <use xlink:href="#icon-filter-link"></use>
                                        </svg>
                                    </a>
                                </div>
                            </li>
                        </ul>
                    </section>

                    <section class="profile__subscriptions tabs__content">
                        <h2 class="visually-hidden">Подписки</h2>
                        <ul class="profile__subscriptions-list">
                            <li class="post-mini post-mini--photo post user">
                                <div class="post-mini__user-info user__info">
                                    <div class="post-mini__avatar user__avatar">
                                        <a class="user__avatar-link" href="#">
                                            <img class="post-mini__picture user__picture" src="../img/userpic-petro.jpg" alt="Аватар пользователя">
                                        </a>
                                    </div>
                                    <div class="post-mini__name-wrapper user__name-wrapper">
                                        <a class="post-mini__name user__name" href="#">
                                            <span>Петр Демин</span>
                                        </a>
                                        <time class="post-mini__time user__additional" datetime="2014-03-20T20:20">5 лет на сайте</time>
                                    </div>
                                </div>
                                <div class="post-mini__rating user__rating">
                                    <p class="post-mini__rating-item user__rating-item user__rating-item--publications">
                                        <span class="post-mini__rating-amount user__rating-amount">556</span>
                                        <span class="post-mini__rating-text user__rating-text">публикаций</span>
                                    </p>
                                    <p class="post-mini__rating-item user__rating-item user__rating-item--subscribers">
                                        <span class="post-mini__rating-amount user__rating-amount">1856</span>
                                        <span class="post-mini__rating-text user__rating-text">подписчиков</span>
                                    </p>
                                </div>
                                <div class="post-mini__user-buttons user__buttons">
                                    <button class="post-mini__user-button user__button user__button--subscription button button--main" type="button">Подписаться</button>
                                </div>
                            </li>
                            <li class="post-mini post-mini--photo post user">
                                <div class="post-mini__user-info user__info">
                                    <div class="post-mini__avatar user__avatar">
                                        <a class="user__avatar-link" href="#">
                                            <img class="post-mini__picture user__picture" src="../img/userpic-petro.jpg" alt="Аватар пользователя">
                                        </a>
                                    </div>
                                    <div class="post-mini__name-wrapper user__name-wrapper">
                                        <a class="post-mini__name user__name" href="#">
                                            <span>Петр Демин</span>
                                        </a>
                                        <time class="post-mini__time user__additional" datetime="2014-03-20T20:20">5 лет на сайте</time>
                                    </div>
                                </div>
                                <div class="post-mini__rating user__rating">
                                    <p class="post-mini__rating-item user__rating-item user__rating-item--publications">
                                        <span class="post-mini__rating-amount user__rating-amount">556</span>
                                        <span class="post-mini__rating-text user__rating-text">публикаций</span>
                                    </p>
                                    <p class="post-mini__rating-item user__rating-item user__rating-item--subscribers">
                                        <span class="post-mini__rating-amount user__rating-amount">1856</span>
                                        <span class="post-mini__rating-text user__rating-text">подписчиков</span>
                                    </p>
                                </div>
                                <div class="post-mini__user-buttons user__buttons">
                                    <button class="post-mini__user-button user__button user__button--subscription button button--quartz" type="button">Отписаться</button>
                                </div>
                            </li>
                            <li class="post-mini post-mini--photo post user">
                                <div class="post-mini__user-info user__info">
                                    <div class="post-mini__avatar user__avatar">
                                        <a class="user__avatar-link" href="#">
                                            <img class="post-mini__picture user__picture" src="../img/userpic-petro.jpg" alt="Аватар пользователя">
                                        </a>
                                    </div>
                                    <div class="post-mini__name-wrapper user__name-wrapper">
                                        <a class="post-mini__name user__name" href="#">
                                            <span>Петр Демин</span>
                                        </a>
                                        <time class="post-mini__time user__additional" datetime="2014-03-20T20:20">5 лет на сайте</time>
                                    </div>
                                </div>
                                <div class="post-mini__rating user__rating">
                                    <p class="post-mini__rating-item user__rating-item user__rating-item--publications">
                                        <span class="post-mini__rating-amount user__rating-amount">556</span>
                                        <span class="post-mini__rating-text user__rating-text">публикаций</span>
                                    </p>
                                    <p class="post-mini__rating-item user__rating-item user__rating-item--subscribers">
                                        <span class="post-mini__rating-amount user__rating-amount">1856</span>
                                        <span class="post-mini__rating-text user__rating-text">подписчиков</span>
                                    </p>
                                </div>
                                <div class="post-mini__user-buttons user__buttons">
                                    <button class="post-mini__user-button user__button user__button--subscription button button--main" type="button">Подписаться</button>
                                </div>
                            </li>
                            <li class="post-mini post-mini--photo post user">
                                <div class="post-mini__user-info user__info">
                                    <div class="post-mini__avatar user__avatar">
                                        <a class="user__avatar-link" href="#">
                                            <img class="post-mini__picture user__picture" src="../img/userpic-petro.jpg" alt="Аватар пользователя">
                                        </a>
                                    </div>
                                    <div class="post-mini__name-wrapper user__name-wrapper">
                                        <a class="post-mini__name user__name" href="#">
                                            <span>Петр Демин</span>
                                        </a>
                                        <time class="post-mini__time user__additional" datetime="2014-03-20T20:20">5 лет на сайте</time>
                                    </div>
                                </div>
                                <div class="post-mini__rating user__rating">
                                    <p class="post-mini__rating-item user__rating-item user__rating-item--publications">
                                        <span class="post-mini__rating-amount user__rating-amount">556</span>
                                        <span class="post-mini__rating-text user__rating-text">публикаций</span>
                                    </p>
                                    <p class="post-mini__rating-item user__rating-item user__rating-item--subscribers">
                                        <span class="post-mini__rating-amount user__rating-amount">1856</span>
                                        <span class="post-mini__rating-text user__rating-text">подписчиков</span>
                                    </p>
                                </div>
                                <div class="post-mini__user-buttons user__buttons">
                                    <button class="post-mini__user-button user__button user__button--subscription button button--main" type="button">Подписаться</button>
                                </div>
                            </li>
                        </ul>
                    </section>
                </div>
            </div>
        </div>
    </div>
</main>
