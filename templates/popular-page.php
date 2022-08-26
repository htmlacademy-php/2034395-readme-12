<?php
$profile = getUserData($link, 'id', $user['id']);

?>

<section class="page__main page__main--popular">
    <div class="container">
        <h1 class="page__title page__title--popular">Популярное</h1>
    </div>
    <div class="popular container">
        <div class="popular__filters-wrapper">
            <div class="popular__sorting sorting">
                <b class="popular__sorting-caption sorting__caption">Сортировка:</b>
                <ul class="popular__sorting-list sorting__list">
                    <li class="sorting__item sorting__item--popular">
                        <a class="sorting__link sorting__link--active" href="#">
                            <span>Популярность</span>
                            <svg class="sorting__icon" width="10" height="12">
                                <use xlink:href="#icon-sort"></use>
                            </svg>
                        </a>
                    </li>
                    <li class="sorting__item">
                        <a class="sorting__link" href="#">
                            <span>Лайки</span>
                            <svg class="sorting__icon" width="10" height="12">
                                <use xlink:href="#icon-sort"></use>
                            </svg>
                        </a>
                    </li>
                    <li class="sorting__item">
                        <a class="sorting__link" href="#">
                            <span>Дата</span>
                            <svg class="sorting__icon" width="10" height="12">
                                <use xlink:href="#icon-sort"></use>
                            </svg>
                        </a>
                    </li>
                </ul>
            </div>
            <div class="popular__filters filters">
                <b class="popular__filters-caption filters__caption">Тип контента:</b>
                <ul class="popular__filters-list filters__list">
                    <li class="popular__filters-item popular__filters-item--all filters__item filters__item--all">
                        <a
                            class="
                                filters__button
                                filters__button--ellipse
                                filters__button--all
                                <?php if ($tab == 'all'): ?>filters__button--active<?php endif; ?>
                            "
                            href="?tab=all"
                        >
                            <span>Все</span>
                        </a>
                    </li>
                    <li class="popular__filters-item filters__item">
                        <a
                            class="
                                filters__button
                                filters__button--photo
                                button
                                <?php if ($tab == 'photo'): ?>filters__button--active<?php endif; ?>
                            "
                            href="?tab=photo"
                        >
                            <span class="visually-hidden">Фото</span>
                            <svg class="filters__icon" width="22" height="18">
                                <use xlink:href="#icon-filter-photo"></use>
                            </svg>
                        </a>
                    </li>
                    <li class="popular__filters-item filters__item">
                        <a
                            class="
                                filters__button
                                filters__button--video
                                button
                                <?php if ($tab == 'video'): ?>filters__button--active<?php endif; ?>
                            "
                            href="?tab=video"
                        >
                            <span class="visually-hidden">Видео</span>
                            <svg class="filters__icon" width="24" height="16">
                                <use xlink:href="#icon-filter-video"></use>
                            </svg>
                        </a>
                    </li>
                    <li class="popular__filters-item filters__item">
                        <a
                            class="
                                filters__button
                                filters__button--text
                                button
                                <?php if ($tab == 'text'): ?>filters__button--active<?php endif; ?>
                            "
                            href="?tab=text"
                        >
                            <span class="visually-hidden">Текст</span>
                            <svg class="filters__icon" width="20" height="21">
                                <use xlink:href="#icon-filter-text"></use>
                            </svg>
                        </a>
                    </li>
                    <li class="popular__filters-item filters__item">
                        <a
                            class="
                                filters__button
                                filters__button--quote
                                button
                                <?php if ($tab == 'quote'): ?>filters__button--active<?php endif; ?>
                            "
                            href="?tab=quote"
                        >
                            <span class="visually-hidden">Цитата</span>
                            <svg class="filters__icon" width="21" height="20">
                                <use xlink:href="#icon-filter-quote"></use>
                            </svg>
                        </a>
                    </li>
                    <li class="popular__filters-item filters__item">
                        <a
                            class="
                                filters__button
                                filters__button--link
                                button
                                <?php if ($tab == 'link'): ?>filters__button--active<?php endif; ?>
                            "
                            href="?tab=link"
                        >
                            <span class="visually-hidden">Ссылка</span>
                            <svg class="filters__icon" width="21" height="18">
                                <use xlink:href="#icon-filter-link"></use>
                            </svg>
                        </a>
                    </li>
                </ul>
            </div>
        </div>
        <div class="popular__posts">
            <?php foreach ($data as $post): ?>
                <?php
                $normalized_date = normalizeDate($post['date']);
                $comments = getComments($link, $post['id']);
                $likes = getPostLikes($link, $post['id']);

                $is_liked = isPostLiked($link, $user['id'], $post['id']);
                ?>
                <article class="popular__post post <?= $post['class_name'] ?>">
                    <header class="post__header">
                        <h2>
                            <a href="post.php?id=<?= $post['id'] ?>">
                                <?= htmlspecialchars($post['title']) ?>
                            </a>
                        </h2>
                    </header>
                    <div class="post__main">
                        <?php if ($post['name'] == 'quote'): ?>
                            <blockquote>
                                <p>
                                    <?= htmlspecialchars($post['content']) ?>
                                </p>
                                <cite><?= htmlspecialchars($post['cite_author']) ?></cite>
                            </blockquote>
                        <?php endif; ?>

                        <?php if ($post['name'] == 'text'): ?>
                            <?php $postTextData = showData($post['content']) ?>
                            <p><?= htmlspecialchars($postTextData['text']) ?></p>
                            <?php if ($postTextData['isLong']): ?>
                                <a class="post-text__more-link" href="post.php?id=<?= $post['id'] ?>">Читать далее</a>
                            <?php endif; ?>
                        <?php endif; ?>

                        <?php if ($post['name'] == 'photo'): ?>
                            <div class="post-photo__image-wrapper">
                                <img src="<?= $post['image_url'] ?>" alt="Фото от пользователя" width="360"
                                     height="240">
                            </div>
                        <?php endif; ?>

                        <?php if ($post['name'] == 'link'): ?>
                            <div class="post-link__wrapper">
                                <a class="post-link__external" target="_blank" href="<?= $post['site_url'] ?>"
                                   title="Перейти по ссылке">
                                    <div class="post-link__info-wrapper">
                                        <div class="post-link__icon-wrapper">
                                            <img src="https://www.google.com/s2/favicons?domain=vitadental.ru"
                                                 alt="Иконка">
                                        </div>
                                        <div class="post-link__info">
                                            <h3><?= htmlspecialchars($post['title']) ?></h3>
                                        </div>
                                    </div>
                                    <span><?= htmlspecialchars($post['site_url']) ?></span>
                                </a>
                            </div>
                        <?php endif; ?>

                        <?php if ($post['name'] == 'video'): ?>
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
                        <div class="post__author">
                            <a class="post__author-link" href="profile.php?id=<?= $post['author'] ?>" title="Автор">
                                <div class="post__avatar-wrapper">
                                    <img class="post__author-avatar"
                                         src="../img/<?= $profile['avatar_url'] ?? 'userpic.jpg' ?>"
                                         alt="Аватар пользователя">
                                </div>
                                <div class="post__info">
                                    <b class="post__author-name"><?= htmlspecialchars($post['login']) ?></b>
                                    <time class="post__time" datetime="<?= $post['date'] ?>"
                                          title="<?= $post['date'] ?>"><?= $normalized_date . " назад" ?></time>
                                </div>
                            </a>
                        </div>
                        <div class="post__indicators">
                            <div class="post__buttons">
                                <?php if (!$is_liked): ?>
                                <a
                                    class="post__indicator post__indicator--likes button"
                                    href="like.php?action=like&address=popular&post_id=<?= $post['id'] ?>"
                                    title="Поставить лайк"
                                >
                                    <?php else: ?>
                                    <a
                                        class="post__indicator post__indicator--likes-active button"
                                        href="like.php?action=unlike&address=popular&post_id=<?= $post['id'] ?>"
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
                                        <span><?= count($likes) ?></span>
                                        <span class="visually-hidden">количество лайков</span>
                                    </a>
                                    <a class="post__indicator post__indicator--comments button"
                                       href="post.php?id=<?= $post['id'] ?>" title="Комментарии">
                                        <svg class="post__indicator-icon" width="19" height="17">
                                            <use xlink:href="#icon-comment"></use>
                                        </svg>
                                        <span><?= count($comments) ?></span>
                                        <span class="visually-hidden">количество комментариев</span>
                                    </a>
                            </div>
                        </div>
                    </footer>
                </article>
            <?php endforeach; ?>
        </div>
        <?php if ($posts_count > 9): ?>
            <div class="popular__page-links">
                <a
                    class="popular__page-link popular__page-link--prev button button--gray"
                    href="popular.php?tab=all&page=<?= max($page - 1, 1) ?>&sort=views"
                >
                    Предыдущая страница
                </a>
                <a
                    class="popular__page-link popular__page-link--next button button--gray"
                    href="popular.php?tab=all&page=<?= min($page + 1, round($posts_count / 6)) ?>&sort=views"
                >
                    Следующая страница
                </a>
            </div>
        <?php endif; ?>
    </div>
</section>
