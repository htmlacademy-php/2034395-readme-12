<?php
    $profile = getUserData($link, 'id', $post['author']);

    $sql = "SELECT * FROM `posts` p" .
        " WHERE p.author = ?";

    $user_posts = db_query_prepare_stmt($link, $sql, [$post['author']], QUERY_ASSOC);

    $is_owner = false;
    $is_subscribed = checkIsUserSubscribed($link, $user['id'], $profile['id']);
    $is_liked = isPostLiked($link, $user['id'], $post['id']);

    if ($profile['id'] == $user['id']) $is_owner = true;
?>

<main class="page__main page__main--publication">
    <div class="container">
        <h1 class="page__title page__title--publication"><?= htmlspecialchars($post['title']); ?></h1>
        <section class="post-details">
            <h2 class="visually-hidden">Публикация</h2>
            <div class="post-details__wrapper <?= $post['class_name'] ?>">
                <div class="post-details__main-block post post--details">
                    <?php if ($post['name'] == 'quote'): ?>
                        <div class="post-details__image-wrapper post-quote">
                            <div class="post__main">
                                <blockquote>
                                    <p>
                                        <?= htmlspecialchars($post['content']); ?>
                                    </p>
                                    <cite><?= htmlspecialchars($post['cite_author']) ?></cite>
                                </blockquote>
                            </div>
                        </div>
                    <?php endif; ?>

                    <?php if ($post['name'] == 'text'): ?>
                        <div class="post-details__image-wrapper post-text">
                            <div class="post__main">
                                <p>
                                    <?= htmlspecialchars($post['content']) ?>
                                </p>
                            </div>
                        </div>
                    <?php endif; ?>

                    <?php if ($post['name'] == 'link'): ?>
                        <div class="post__main">
                            <div class="post-link__wrapper">
                                <a class="post-link__external" target="_blank" href="<?= htmlspecialchars($post['site_url']); ?>"
                                   title="Перейти по ссылке">
                                    <div class="post-link__info-wrapper">
                                        <div class="post-link__icon-wrapper">
                                            <img
                                                src="https://www.google.com/s2/favicons?domain=<?= htmlspecialchars($post['site_url']); ?>"
                                                alt="Иконка">
                                        </div>
                                        <div class="post-link__info">
                                            <h3><?= htmlspecialchars($post['title']); ?></h3>
                                        </div>
                                    </div>
                                </a>
                            </div>
                        </div>
                    <?php endif; ?>

                    <?php if ($post['name'] == 'photo'): ?>
                        <div class="post-details__image-wrapper post-photo__image-wrapper">
                            <img src="<?= htmlspecialchars($post['image_url']); ?>" alt="Фото от пользователя"
                                 width="760" height="507">
                        </div>
                    <?php endif; ?>

                    <?php if ($post['name'] == 'video'): ?>
                        <div class="post-details__image-wrapper post-photo__image-wrapper">
                            <?= embed_youtube_video($post['video_url']); ?>
                        </div>
                    <?php endif; ?>
                    <div class="post__indicators">
                        <div class="post__buttons">
                            <?php if (!$is_liked): ?>
                                <a
                                    class="post__indicator post__indicator--likes button"
                                    href="like.php?action=like&address=post&post_id=<?= $post['id'] ?>"
                                    title="Поставить лайк"
                                >
                            <?php else: ?>
                                <a
                                    class="post__indicator post__indicator--likes-active button"
                                    href="like.php?action=unlike&address=post&post_id=<?= $post['id'] ?>"
                                    title="Убрать лайк"
                                >
                            <?php endif; ?>
                                    <svg class="post__indicator-icon" width="20" height="17">
                                        <use xlink:href="#icon-heart"></use>
                                    </svg>
                                    <svg class="post__indicator-icon post__indicator-icon--like-active" width="20"
                                         height="17">
                                        <use xlink:href="#icon-heart-active"></use>
                                    </svg>
                                    <span><?= count(getPostLikes($link, $post['id'])) ?></span>
                                    <span class="visually-hidden">количество лайков</span>
                                </a>
                            <a class="post__indicator post__indicator--comments button" href="#" title="Комментарии">
                                <svg class="post__indicator-icon" width="19" height="17">
                                    <use xlink:href="#icon-comment"></use>
                                </svg>
                                <span>25</span>
                                <span class="visually-hidden">количество комментариев</span>
                            </a>
                            <a class="post__indicator post__indicator--repost button" href="#" title="Репост">
                                <svg class="post__indicator-icon" width="19" height="17">
                                    <use xlink:href="#icon-repost"></use>
                                </svg>
                                <span>5</span>
                                <span class="visually-hidden">количество репостов</span>
                            </a>
                        </div>
                        <span class="post__view"><?= $post['views'] . " " . get_noun_plural_form($post['views'], 'просмотр', 'просмотра', 'просмотров') ?></span>
                    </div>
                    <ul class="post__tags">
                        <li><a href="#">#nature</a></li>
                        <li><a href="#">#globe</a></li>
                        <li><a href="#">#photooftheday</a></li>
                        <li><a href="#">#canon</a></li>
                        <li><a href="#">#landscape</a></li>
                        <li><a href="#">#щикарныйвид</a></li>
                    </ul>
                    <div class="comments">
                        <form class="comments__form form" action="post.php?id=<?= $post['id'] ?>" method="post">
                            <div class="comments__my-avatar">
                                <img class="comments__picture" src="../img/<?= $profile['avatar_url'] ?? 'userpic.jpg' ?>"
                                     alt="Аватар пользователя">
                            </div>
                            <div class="form__input-section">
<!--                                form__input-section--error-->
                                <label>
                                    <textarea
                                        class="comments__textarea form__textarea form__input"
                                        placeholder="Ваш комментарий"
                                        name="comment"
                                    ></textarea>
                                </label>
                                <label class="visually-hidden">Ваш комментарий</label>
                                <button class="form__error-button button" type="button">!</button>
                                <div class="form__error-text">
                                    <h3 class="form__error-title">Ошибка валидации</h3>
                                    <p class="form__error-desc">Это поле обязательно к заполнению</p>
                                </div>
                            </div>
                            <button class="comments__submit button button--green" type="submit">Отправить</button>
                        </form>
                        <div class="comments__list-wrapper">
                            <ul class="comments__list">
                                <?php foreach($comments as $el): ?>
                                    <li class="comments__item user">
                                        <div class="comments__avatar">
                                            <a class="user__avatar-link" href="../img/<?= $el['avatar_url'] ?? 'userpic.jpg' ?>">
                                                <img class="comments__picture" src="../img/<?= $el['avatar_url'] ?? 'userpic.jpg' ?>"
                                                     alt="Аватар пользователя">
                                            </a>
                                        </div>
                                        <div class="comments__info">
                                            <div class="comments__name-wrapper">
                                                <a class="comments__user-name" href="profile.php?id=<?= $post['author'] ?>">
                                                    <span><?= $el['login'] ?></span>
                                                </a>
                                                <time class="comments__time" datetime=<?= $el['date'] ?>>
                                                    <?= normalizeDate($el['date']) . " назад" ?>
                                                </time>
                                            </div>
                                            <p class="comments__text">
                                                <?= htmlspecialchars($el['content']); ?>
                                            </p>
                                        </div>
                                    </li>
                                <?php endforeach; ?>
                            </ul>
                            <?php if(count($comments) > 2): ?>
                                <a class="comments__more-link" href="#">
                                    <span>Показать все комментарии</span>
                                    <sup class="comments__amount"><?= count($comments) ?></sup>
                                </a>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
                <div class="post-details__user user">
                    <div class="post-details__user-info user__info">
                        <div class="post-details__avatar user__avatar">
                            <a class="post-details__avatar-link user__avatar-link" href="profile.php?id=<?= $profile['id'] ?>">
                                <img class="post-details__picture user__picture" src="../img/<?= $profile['avatar_url'] ?? 'userpic.jpg' ?>"
                                     alt="Аватар пользователя">
                            </a>
                        </div>
                        <div class="post-details__name-wrapper user__name-wrapper">
                            <a class="post-details__name user__name" href="profile.php?id=<?= $profile['id'] ?>">
                                <span><?= htmlspecialchars($profile['login']) ?></span>
                            </a>
                            <time class="post-details__time user__time" datetime="2014-03-20">
                                <?= normalizeDate($profile['registration_date']) . " на сайте" ?>
                            </time>
                        </div>
                    </div>
                    <div class="post-details__rating user__rating">
                        <p class="post-details__rating-item user__rating-item user__rating-item--subscribers">
                            <span class="post-details__rating-amount user__rating-amount"><?= count(getSubs($link, $profile['id'])) ?></span>
                            <span class="post-details__rating-text user__rating-text"><?= get_noun_plural_form(count(getSubs($link, $profile['id'])), 'подписчик', 'подписчика', 'подписчиков') ?></span>
                        </p>
                        <p class="post-details__rating-item user__rating-item user__rating-item--publications">
                            <span class="post-details__rating-amount user__rating-amount">
                                <?= count($user_posts) ?>
                            </span>
                            <span class="post-details__rating-text user__rating-text">
                                <?= get_noun_plural_form(count($user_posts), 'публикация', 'публикации', 'публикаций') ?>
                            </span>
                        </p>
                    </div>
                    <?php if (!$is_owner): ?>
                        <div class="post-details__user-buttons user__buttons">
                            <?php if (!$is_subscribed): ?>
                                <a
                                    class="user__button user__button--subscription button button--main"
                                    href="subscription.php?action=sub&address=post&post_id=<?= $post['id'] ?>&post_author=<?= $post['author'] ?>"
                                >
                                    Подписаться
                                </a>
                            <?php else: ?>
                                <a
                                    class="user__button user__button--subscription button button--main"
                                    href="subscription.php?action=unsub&address=post&post_id=<?= $post['id'] ?>&post_author=<?= $post['author'] ?>"
                                >
                                    Отписаться
                                </a>
                            <?php endif; ?>
                            <a class="user__button user__button--writing button button--green" href="#">Сообщение</a>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </section>
    </div>
</main>
