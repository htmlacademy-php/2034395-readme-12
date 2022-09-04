<?php
/**
 * @var array $posts
 * @var mysqli $link
 * @var string $search_data
 */

?>

<main class="page__main page__main--search-results">
    <h1 class="visually-hidden">Страница результатов поиска</h1>
    <section class="search">
        <h2 class="visually-hidden">Результаты поиска</h2>
        <div class="search__query-wrapper">
            <div class="search__query container">
                <span>Вы искали:</span>
                <span class="search__query-text"><?= htmlspecialchars($search_data) ?></span>
            </div>
        </div>
        <div class="search__results-wrapper">
            <div class="container">
                <div class="search__content">
                    <?php foreach ($posts as $post): ?>
                        <?php
                            $content_class = get_content_class_by_id($link, $post["content_type"]);
                            $avatar = $post['avatar_url'] ?? 'userpic.jpg';
                            $normalized_date = normalize_date($post['date']);
                        ?>
                        <article class="search__post post <?= $content_class ?>">
                            <header class="post__header post__author">
                                <a class="post__author-link" href="#" title="Автор">
                                    <div class="post__avatar-wrapper">
                                        <img class="post__author-avatar" src="../img/<?= $avatar ?>"
                                             alt="Аватар пользователя" width="60" height="60">
                                    </div>
                                    <div class="post__info">
                                        <b class="post__author-name"><?= htmlspecialchars($post["login"]) ?></b>
                                        <span class="post__time"><?= $normalized_date . " назад" ?></span>
                                    </div>
                                </a>
                            </header>
                            <div class="post__main">
                                <?php if ($content_class === 'post-photo'): ?>
                                    <h2>
                                        <a href="../post.php?id=<?= $post['id'] ?>"><?= htmlspecialchars($post["title"]) ?></a>
                                    </h2>
                                    <div class="post-photo__image-wrapper">
                                        <img src="../img/<?= $post["image_url"] ?>" alt="Фото от пользователя"
                                             width="760" height="396">
                                    </div>
                                <?php endif; ?>

                                <?php if ($content_class === 'post-text'): ?>
                                    <?php $postTextData = show_data($post['content']) ?>
                                    <h2>
                                        <a href="../post.php?id=<?= $post['id'] ?>"><?= htmlspecialchars($post["title"]) ?></a>
                                    </h2>
                                    <p><?= htmlspecialchars($postTextData['text']) ?></p>
                                    <?php if ($postTextData['isLong']): ?>
                                        <a class="post-text__more-link" href="../post.php?id=<?= $post['id'] ?>">Читать
                                            далее</a>
                                    <?php endif; ?>
                                <?php endif; ?>

                                <?php if ($content_class === 'post-video'): ?>
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

                                <?php if ($content_class === 'post-quote'): ?>
                                    <blockquote>
                                        <p>
                                            <?= htmlspecialchars($post["content"]) ?>
                                        </p>
                                        <cite><?= htmlspecialchars($post["cite_author"]) ?></cite>
                                    </blockquote>
                                <?php endif; ?>

                                <?php if ($content_class === 'post-link'): ?>
                                    <div class="post-link__wrapper">
                                        <a class="post-link__external" target="_blank" href="<?= $post['site_url'] ?>"
                                           title="Перейти по ссылке">
                                            <div class="post-link__icon-wrapper">
                                                <img src="https://www.google.com/s2/favicons?domain=vitadental.ru"
                                                     alt="Иконка">
                                            </div>
                                            <div class="post-link__info">
                                                <h3><?= htmlspecialchars($post["title"]) ?></h3>
                                                <span><?= htmlspecialchars($post["site_url"]) ?></span>
                                            </div>
                                            <svg class="post-link__arrow" width="11" height="16">
                                                <use xlink:href="#icon-arrow-right-ad"></use>
                                            </svg>
                                        </a>
                                    </div>
                                <?php endif; ?>
                            </div>
                            <footer class="post__footer post__indicators">
                                <div class="post__buttons">
                                    <a class="post__indicator post__indicator--likes button" href="#" title="Лайк">
                                        <svg class="post__indicator-icon" width="20" height="17">
                                            <use xlink:href="#icon-heart"></use>
                                        </svg>
                                        <svg class="post__indicator-icon post__indicator-icon--like-active" width="20"
                                             height="17">
                                            <use xlink:href="#icon-heart-active"></use>
                                        </svg>
                                        <span><?= count(get_post_likes($link, $post['id'])) ?></span>
                                        <span class="visually-hidden">количество лайков</span>
                                    </a>
                                    <a
                                        class="post__indicator post__indicator--comments button"
                                        href="../post.php?id=<?= $post['id'] ?>"
                                        title="Комментарии"
                                    >
                                        <svg class="post__indicator-icon" width="19" height="17">
                                            <use xlink:href="#icon-comment"></use>
                                        </svg>
                                        <span><?= count(get_comments($link, $post['id'])) ?></span>
                                        <span class="visually-hidden">количество комментариев</span>
                                    </a>
                                </div>
                            </footer>
                        </article>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
    </section>
</main>
