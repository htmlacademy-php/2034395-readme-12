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
                            <a class="post__indicator post__indicator--likes button" href="#" title="Лайк">
                                <svg class="post__indicator-icon" width="20" height="17">
                                    <use xlink:href="#icon-heart"></use>
                                </svg>
                                <svg class="post__indicator-icon post__indicator-icon--like-active" width="20"
                                     height="17">
                                    <use xlink:href="#icon-heart-active"></use>
                                </svg>
                                <span>250</span>
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
                        <span class="post__view"><?= normalizeViews($post['views']) ?></span>
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
                        <form class="comments__form form" action="#" method="post">
                            <div class="comments__my-avatar">
                                <img class="comments__picture" src="../img/userpic-medium.jpg"
                                     alt="Аватар пользователя">
                            </div>
                            <div class="form__input-section form__input-section--error">
                                <label>
                                    <textarea class="comments__textarea form__textarea form__input"
                                              placeholder="Ваш комментарий"></textarea>
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
                                            <a class="user__avatar-link" href="../img/<?= $el['avatar_url'] ?>">
                                                <img class="comments__picture" src="../img/<?= $el['avatar_url'] ?>"
                                                     alt="Аватар пользователя">
                                            </a>
                                        </div>
                                        <div class="comments__info">
                                            <div class="comments__name-wrapper">
                                                <a class="comments__user-name" href="#">
                                                    <span><?= $el['login'] ?></span>
                                                </a>
                                                <time class="comments__time" datetime=<?= $el['date'] ?>>
                                                    <?= normalizeDate($el['date']) ?>
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
                            <a class="post-details__avatar-link user__avatar-link" href="#">
                                <img class="post-details__picture user__picture" src="../img/<?= $post['avatar_url'] ?>"
                                     alt="Аватар пользователя">
                            </a>
                        </div>
                        <div class="post-details__name-wrapper user__name-wrapper">
                            <a class="post-details__name user__name" href="#">
                                <span><?= htmlspecialchars($post['login']) ?></span>
                            </a>
                            <time class="post-details__time user__time" datetime="2014-03-20">5 лет на сайте</time>
                        </div>
                    </div>
                    <div class="post-details__rating user__rating">
                        <p class="post-details__rating-item user__rating-item user__rating-item--subscribers">
                            <span class="post-details__rating-amount user__rating-amount"><?= $post['subs_count'] ?></span>
                            <span class="post-details__rating-text user__rating-text"><?= normalizeSubs($post['subs_count']) ?></span>
                        </p>
                        <p class="post-details__rating-item user__rating-item user__rating-item--publications">
                            <span class="post-details__rating-amount user__rating-amount">556</span>
                            <span class="post-details__rating-text user__rating-text">публикаций</span>
                        </p>
                    </div>
                    <div class="post-details__user-buttons user__buttons">
                        <button class="user__button user__button--subscription button button--main" type="button">
                            Подписаться
                        </button>
                        <a class="user__button user__button--writing button button--green" href="#">Сообщение</a>
                    </div>
                </div>
            </div>
        </section>
    </div>
</main>
