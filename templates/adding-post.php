<main class="page__main page__main--adding-post">
    <div class="page__main-section">
        <div class="container">
            <h1 class="page__title page__title--adding-post">Добавить публикацию</h1>
        </div>
        <div class="adding-post container">
            <div class="adding-post__tabs-wrapper tabs">
                <div class="adding-post__tabs filters">
                    <ul class="adding-post__tabs-list filters__list tabs__list">
                        <li class="adding-post__tabs-item filters__item">
                            <a
                                class="
                                    adding-post__tabs-link
                                    filters__button
                                    filters__button--photo
                                    <?php if ($post_type == 'photo'): ?>
                                        filters__button--active
                                        tabs__item--active
                                    <?php endif; ?>
                                    tabs__item
                                    button
                                "
                                href="../add.php?type=photo"
                            >
                                <svg class="filters__icon" width="22" height="18">
                                    <use xlink:href="#icon-filter-photo"></use>
                                </svg>
                                <span>Фото</span>
                            </a>
                        </li>
                        <li class="adding-post__tabs-item filters__item">
                            <a
                                class="
                                    adding-post__tabs-link
                                    filters__button
                                    filters__button--video
                                    <?php if ($post_type == 'video'): ?>
                                        filters__button--active
                                        tabs__item--active
                                    <?php endif; ?>
                                    tabs__item
                                    button
                                "
                                href="../add.php?type=video"
                            >
                                <svg class="filters__icon" width="24" height="16">
                                    <use xlink:href="#icon-filter-video"></use>
                                </svg>
                                <span>Видео</span>
                            </a>
                        </li>
                        <li class="adding-post__tabs-item filters__item">
                            <a
                                class="
                                    adding-post__tabs-link
                                    filters__button
                                    filters__button--text
                                    <?php if ($post_type == 'text'): ?>
                                        filters__button--active
                                        tabs__item--active
                                    <?php endif; ?>
                                    tabs__item
                                    button
                                "
                                href="../add.php?type=text"
                            >
                                <svg class="filters__icon" width="20" height="21">
                                    <use xlink:href="#icon-filter-text"></use>
                                </svg>
                                <span>Текст</span>
                            </a>
                        </li>
                        <li class="adding-post__tabs-item filters__item">
                            <a
                                class="
                                    adding-post__tabs-link
                                    filters__button
                                    filters__button--quote
                                    <?php if ($post_type == 'quote'): ?>
                                        filters__button--active
                                        tabs__item--active
                                    <?php endif; ?>
                                    tabs__item
                                    button
                                "
                                href="../add.php?type=quote"
                            >
                                <svg class="filters__icon" width="21" height="20">
                                    <use xlink:href="#icon-filter-quote"></use>
                                </svg>
                                <span>Цитата</span>
                            </a>
                        </li>
                        <li class="adding-post__tabs-item filters__item">
                            <a
                                class="
                                    adding-post__tabs-link
                                    filters__button
                                    filters__button--link
                                    <?php if ($post_type == 'link'): ?>
                                        filters__button--active
                                        tabs__item--active
                                    <?php endif; ?>
                                    tabs__item
                                    button
                                "
                                href="../add.php?type=link"
                            >
                                <svg class="filters__icon" width="21" height="18">
                                    <use xlink:href="#icon-filter-link"></use>
                                </svg>
                                <span>Ссылка</span>
                            </a>
                        </li>
                    </ul>
                </div>
                <div class="adding-post__tab-content">
                    <section class="
                        adding-post__photo
                        tabs__content
                        <?php if ($post_type == "photo"): ?>
                            tabs__content--active
                        <?php endif; ?>
                    ">
                        <h2 class="visually-hidden">Форма добавления фото</h2>
                        <form class="adding-post__form form" action="../add.php?type=<?= $post_type ?>" method="post"
                              enctype="multipart/form-data">
                            <div class="form__text-inputs-wrapper">
                                <div class="form__text-inputs">
                                    <div class="adding-post__input-wrapper form__input-wrapper">
                                        <label class="adding-post__label form__label" for="photo-heading">Заголовок
                                            <span class="form__input-required">*</span></label>
                                        <div class="form__input-section">
                                            <input class="adding-post__input form__input" id="photo-heading" type="text"
                                                   name="photo-heading" placeholder="Введите заголовок">
                                            <button class="form__error-button button" type="button">!<span
                                                    class="visually-hidden">Информация об ошибке</span></button>
                                            <div class="form__error-text">
                                                <h3 class="form__error-title">Заголовок сообщения</h3>
                                                <p class="form__error-desc">Текст сообщения об ошибке, подробно
                                                    объясняющий, что не так.</p>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="adding-post__input-wrapper form__input-wrapper">
                                        <label class="adding-post__label form__label" for="photo-url">Ссылка из
                                            интернета</label>
                                        <div class="form__input-section">
                                            <input class="adding-post__input form__input" id="photo-url" type="text"
                                                   name="photo-url" placeholder="Введите ссылку">
                                            <button class="form__error-button button" type="button">!<span
                                                    class="visually-hidden">Информация об ошибке</span></button>
                                            <div class="form__error-text">
                                                <h3 class="form__error-title">Заголовок сообщения</h3>
                                                <p class="form__error-desc">Текст сообщения об ошибке, подробно
                                                    объясняющий, что не так.</p>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="adding-post__input-wrapper form__input-wrapper">
                                        <label class="adding-post__label form__label" for="photo-tags">Теги</label>
                                        <div class="form__input-section">
                                            <input class="adding-post__input form__input" id="photo-tags" type="text"
                                                   name="photo-tags" placeholder="Введите теги">
                                            <button class="form__error-button button" type="button">!<span
                                                    class="visually-hidden">Информация об ошибке</span></button>
                                            <div class="form__error-text">
                                                <h3 class="form__error-title">Заголовок сообщения</h3>
                                                <p class="form__error-desc">Текст сообщения об ошибке, подробно
                                                    объясняющий, что не так.</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <?php if (count($errors) > 0): ?>
                                    <div class="form__invalid-block">
                                        <b class="form__invalid-slogan">Пожалуйста, исправьте следующие ошибки:</b>
                                        <ul class="form__invalid-list">
                                            <?php foreach ($errors as $el): ?>
                                                <li class="form__invalid-item"><?= $el['text'] ?></li>
                                            <?php endforeach; ?>
                                        </ul>
                                    </div>
                                <?php endif; ?>
                            </div>
                            <div
                                class="adding-post__input-file-container form__input-container form__input-container--file">
                                <div class="adding-post__input-file-wrapper form__input-file-wrapper">
                                    <div
                                        class="adding-post__file-zone adding-post__file-zone--photo form__file-zone dropzone">
                                        <input class="adding-post__input-file form__input-file" id="userpic-file-photo"
                                               type="file" name="userpic-file-photo" title=" ">
                                        <div class="form__file-zone-text">
                                            <span>Перетащите фото сюда</span>
                                        </div>
                                    </div>
                                    <button
                                        class="adding-post__input-file-button form__input-file-button form__input-file-button--photo button"
                                        type="button">
                                        <span>Выбрать фото</span>
                                        <svg class="adding-post__attach-icon form__attach-icon" width="10" height="20">
                                            <use xlink:href="#icon-attach"></use>
                                        </svg>
                                    </button>
                                </div>
                                <div class="adding-post__file adding-post__file--photo form__file dropzone-previews">

                                </div>
                            </div>
                            <div class="adding-post__buttons">
                                <button class="adding-post__submit button button--main" type="submit">Опубликовать
                                </button>
                                <a class="adding-post__close" href="../popular.php?tab=all">Закрыть</a>
                            </div>
                        </form>
                    </section>

                    <section class="
                        adding-post__video
                        tabs__content
                        <?php if ($post_type == "video"): ?>
                            tabs__content--active
                        <?php endif; ?>
                    ">
                        <h2 class="visually-hidden">Форма добавления видео</h2>
                        <form class="adding-post__form form" action="../add.php?type=<?= $post_type ?>" method="post"
                              enctype="multipart/form-data">
                            <div class="form__text-inputs-wrapper">
                                <div class="form__text-inputs">
                                    <div class="adding-post__input-wrapper form__input-wrapper">
                                        <label class="adding-post__label form__label" for="video-heading">Заголовок
                                            <span class="form__input-required">*</span></label>
                                        <div class="form__input-section">
                                            <input class="adding-post__input form__input" id="video-heading" type="text"
                                                   name="video-heading" placeholder="Введите заголовок">
                                            <button class="form__error-button button" type="button">!<span
                                                    class="visually-hidden">Информация об ошибке</span></button>
                                            <div class="form__error-text">
                                                <h3 class="form__error-title">Заголовок сообщения</h3>
                                                <p class="form__error-desc">Текст сообщения об ошибке, подробно
                                                    объясняющий, что не так.</p>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="adding-post__input-wrapper form__input-wrapper">
                                        <label class="adding-post__label form__label" for="video-url">Ссылка youtube
                                            <span class="form__input-required">*</span></label>
                                        <div class="form__input-section">
                                            <input class="adding-post__input form__input" id="video-url" type="text"
                                                   name="video-url" placeholder="Введите ссылку">
                                            <button class="form__error-button button" type="button">!<span
                                                    class="visually-hidden">Информация об ошибке</span></button>
                                            <div class="form__error-text">
                                                <h3 class="form__error-title">Заголовок сообщения</h3>
                                                <p class="form__error-desc">Текст сообщения об ошибке, подробно
                                                    объясняющий, что не так.</p>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="adding-post__input-wrapper form__input-wrapper">
                                        <label class="adding-post__label form__label" for="video-tags">Теги</label>
                                        <div class="form__input-section">
                                            <input class="adding-post__input form__input" id="video-tags" type="text"
                                                   name="video-tags" placeholder="Введите ссылку">
                                            <button class="form__error-button button" type="button">!<span
                                                    class="visually-hidden">Информация об ошибке</span></button>
                                            <div class="form__error-text">
                                                <h3 class="form__error-title">Заголовок сообщения</h3>
                                                <p class="form__error-desc">Текст сообщения об ошибке, подробно
                                                    объясняющий, что не так.</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <?php if (count($errors) > 0): ?>
                                    <div class="form__invalid-block">
                                        <b class="form__invalid-slogan">Пожалуйста, исправьте следующие ошибки:</b>
                                        <ul class="form__invalid-list">
                                            <?php foreach ($errors as $el): ?>
                                                <li class="form__invalid-item"><?= $el['text'] ?></li>
                                            <?php endforeach; ?>
                                        </ul>
                                    </div>
                                <?php endif; ?>
                            </div>

                            <div class="adding-post__buttons">
                                <button class="adding-post__submit button button--main" type="submit">Опубликовать
                                </button>
                                <a class="adding-post__close" href="../popular.php?tab=all">Закрыть</a>
                            </div>
                        </form>
                    </section>

                    <section class="
                        adding-post__text
                        tabs__content
                        <?php if ($post_type == "text"): ?>
                            tabs__content--active
                        <?php endif; ?>
                    ">
                        <h2 class="visually-hidden">Форма добавления текста</h2>
                        <form class="adding-post__form form" action="../add.php?type=<?= $post_type ?>" method="post">
                            <div class="form__text-inputs-wrapper">
                                <div class="form__text-inputs">
                                    <div class="adding-post__input-wrapper form__input-wrapper">
                                        <label class="adding-post__label form__label" for="text-heading">Заголовок <span
                                                class="form__input-required">*</span></label>
                                        <div class="form__input-section">
                                            <input class="adding-post__input form__input" id="text-heading" type="text"
                                                   name="text-heading" placeholder="Введите заголовок">
                                            <button class="form__error-button button" type="button">!<span
                                                    class="visually-hidden">Информация об ошибке</span></button>
                                            <div class="form__error-text">
                                                <h3 class="form__error-title">Заголовок сообщения</h3>
                                                <p class="form__error-desc">Текст сообщения об ошибке, подробно
                                                    объясняющий, что не так.</p>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="adding-post__textarea-wrapper form__textarea-wrapper">
                                        <label class="adding-post__label form__label" for="text-content">Текст поста <span
                                                class="form__input-required">*</span></label>
                                        <div class="form__input-section">
                                            <textarea class="adding-post__textarea form__textarea form__input"
                                                      id="text-content" name="text-content" placeholder="Введите текст публикации"></textarea>
                                            <button class="form__error-button button" type="button">!<span
                                                    class="visually-hidden">Информация об ошибке</span></button>
                                            <div class="form__error-text">
                                                <h3 class="form__error-title">Заголовок сообщения</h3>
                                                <p class="form__error-desc">Текст сообщения об ошибке, подробно
                                                    объясняющий, что не так.</p>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="adding-post__input-wrapper form__input-wrapper">
                                        <label class="adding-post__label form__label" for="text-tags">Теги</label>
                                        <div class="form__input-section">
                                            <input class="adding-post__input form__input" id="text-tags" type="text"
                                                   name="text-tags" placeholder="Введите теги">
                                            <button class="form__error-button button" type="button">!<span
                                                    class="visually-hidden">Информация об ошибке</span></button>
                                            <div class="form__error-text">
                                                <h3 class="form__error-title">Заголовок сообщения</h3>
                                                <p class="form__error-desc">Текст сообщения об ошибке, подробно
                                                    объясняющий, что не так.</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <?php if (count($errors) > 0): ?>
                                    <div class="form__invalid-block">
                                        <b class="form__invalid-slogan">Пожалуйста, исправьте следующие ошибки:</b>
                                        <ul class="form__invalid-list">
                                            <?php foreach ($errors as $el): ?>
                                                <li class="form__invalid-item"><?= $el['text'] ?></li>
                                            <?php endforeach; ?>
                                        </ul>
                                    </div>
                                <?php endif; ?>
                            </div>
                            <div class="adding-post__buttons">
                                <button class="adding-post__submit button button--main" type="submit">Опубликовать
                                </button>
                                <a class="adding-post__close" href="../popular.php?tab=all">Закрыть</a>
                            </div>
                        </form>
                    </section>

                    <section class="
                        adding-post__quote
                        tabs__content
                        <?php if ($post_type == "quote"): ?>
                            tabs__content--active
                        <?php endif; ?>
                    ">
                        <h2 class="visually-hidden">Форма добавления цитаты</h2>
                        <form class="adding-post__form form" action="../add.php?type=<?= $post_type ?>" method="post">
                            <div class="form__text-inputs-wrapper">
                                <div class="form__text-inputs">
                                    <div class="adding-post__input-wrapper form__input-wrapper">
                                        <label class="adding-post__label form__label" for="quote-heading">Заголовок
                                            <span class="form__input-required">*</span></label>
                                        <div class="form__input-section">
                                            <input class="adding-post__input form__input" id="quote-heading" type="text"
                                                   name="quote-heading" placeholder="Введите заголовок">
                                            <button class="form__error-button button" type="button">!<span
                                                    class="visually-hidden">Информация об ошибке</span></button>
                                            <div class="form__error-text">
                                                <h3 class="form__error-title">Заголовок сообщения</h3>
                                                <p class="form__error-desc">Текст сообщения об ошибке, подробно
                                                    объясняющий, что не так.</p>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="adding-post__input-wrapper form__textarea-wrapper">
                                        <label class="adding-post__label form__label" for="quote-content">Текст цитаты <span
                                                class="form__input-required">*</span></label>
                                        <div class="form__input-section">
                                            <textarea
                                                class="adding-post__textarea adding-post__textarea--quote form__textarea form__input"
                                                id="quote-content" name="quote-content" placeholder="Текст цитаты"></textarea>
                                            <button class="form__error-button button" type="button">!<span
                                                    class="visually-hidden">Информация об ошибке</span></button>
                                            <div class="form__error-text">
                                                <h3 class="form__error-title">Заголовок сообщения</h3>
                                                <p class="form__error-desc">Текст сообщения об ошибке, подробно
                                                    объясняющий, что не так.</p>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="adding-post__textarea-wrapper form__input-wrapper">
                                        <label class="adding-post__label form__label" for="quote-author">Автор <span
                                                class="form__input-required">*</span></label>
                                        <div class="form__input-section">
                                            <input class="adding-post__input form__input" id="quote-author" type="text"
                                                   name="quote-author" placeholder="Имя автора">
                                            <button class="form__error-button button" type="button">!<span
                                                    class="visually-hidden">Информация об ошибке</span></button>
                                            <div class="form__error-text">
                                                <h3 class="form__error-title">Заголовок сообщения</h3>
                                                <p class="form__error-desc">Текст сообщения об ошибке, подробно
                                                    объясняющий, что не так.</p>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="adding-post__input-wrapper form__input-wrapper">
                                        <label class="adding-post__label form__label" for="quote-tags">Теги</label>
                                        <div class="form__input-section">
                                            <input class="adding-post__input form__input" id="quote-tags" type="text"
                                                   name="quote-tags" placeholder="Введите теги">
                                            <button class="form__error-button button" type="button">!<span
                                                    class="visually-hidden">Информация об ошибке</span></button>
                                            <div class="form__error-text">
                                                <h3 class="form__error-title">Заголовок сообщения</h3>
                                                <p class="form__error-desc">Текст сообщения об ошибке, подробно
                                                    объясняющий, что не так.</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <?php if (count($errors) > 0): ?>
                                    <div class="form__invalid-block">
                                        <b class="form__invalid-slogan">Пожалуйста, исправьте следующие ошибки:</b>
                                        <ul class="form__invalid-list">
                                            <?php foreach ($errors as $el): ?>
                                                <li class="form__invalid-item"><?= $el['text'] ?></li>
                                            <?php endforeach; ?>
                                        </ul>
                                    </div>
                                <?php endif; ?>
                            </div>
                            <div class="adding-post__buttons">
                                <button class="adding-post__submit button button--main" type="submit">Опубликовать
                                </button>
                                <a class="adding-post__close" href="../popular.php?tab=all">Закрыть</a>
                            </div>
                        </form>
                    </section>

                    <section class="
                        adding-post__link
                        tabs__content
                        <?php if ($post_type == "link"): ?>
                            tabs__content--active
                        <?php endif; ?>
                    ">
                        <h2 class="visually-hidden">Форма добавления ссылки</h2>
                        <form class="adding-post__form form" action="../add.php?type=<?= $post_type ?>" method="post">
                            <div class="form__text-inputs-wrapper">
                                <div class="form__text-inputs">
                                    <div class="adding-post__input-wrapper form__input-wrapper">
                                        <label class="adding-post__label form__label" for="link-heading">Заголовок <span
                                                class="form__input-required">*</span></label>
                                        <div class="form__input-section">
                                            <input class="adding-post__input form__input" id="link-heading" type="text"
                                                   name="link-heading" placeholder="Введите заголовок">
                                            <button class="form__error-button button" type="button">!<span
                                                    class="visually-hidden">Информация об ошибке</span></button>
                                            <div class="form__error-text">
                                                <h3 class="form__error-title">Заголовок сообщения</h3>
                                                <p class="form__error-desc">Текст сообщения об ошибке, подробно
                                                    объясняющий, что не так.</p>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="adding-post__textarea-wrapper form__input-wrapper">
                                        <label class="adding-post__label form__label" for="link-url">Ссылка <span
                                                class="form__input-required">*</span></label>
                                        <div class="form__input-section">
                                            <input class="adding-post__input form__input" id="link-url" type="text"
                                                   name="link-url" placeholder="Введите ссылку">
                                            <button class="form__error-button button" type="button">!<span
                                                    class="visually-hidden">Информация об ошибке</span></button>
                                            <div class="form__error-text">
                                                <h3 class="form__error-title">Заголовок сообщения</h3>
                                                <p class="form__error-desc">Текст сообщения об ошибке, подробно
                                                    объясняющий, что не так.</p>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="adding-post__input-wrapper form__input-wrapper">
                                        <label class="adding-post__label form__label" for="link-tags">Теги</label>
                                        <div class="form__input-section">
                                            <input class="adding-post__input form__input" id="link-tags" type="text"
                                                   name="photo-heading" placeholder="Введите ссылку">
                                            <button class="form__error-button button" type="button">!<span
                                                    class="visually-hidden">Информация об ошибке</span></button>
                                            <div class="form__error-text">
                                                <h3 class="form__error-title">Заголовок сообщения</h3>
                                                <p class="form__error-desc">Текст сообщения об ошибке, подробно
                                                    объясняющий, что не так.</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <?php if (count($errors) > 0): ?>
                                    <div class="form__invalid-block">
                                        <b class="form__invalid-slogan">Пожалуйста, исправьте следующие ошибки:</b>
                                        <ul class="form__invalid-list">
                                            <?php foreach ($errors as $el): ?>
                                                <li class="form__invalid-item"><?= $el['text'] ?></li>
                                            <?php endforeach; ?>
                                        </ul>
                                    </div>
                                <?php endif; ?>
                            </div>
                            <div class="adding-post__buttons">
                                <button class="adding-post__submit button button--main" type="submit">Опубликовать
                                </button>
                                <a class="adding-post__close" href="../popular.php?tab=all">Закрыть</a>
                            </div>
                        </form>
                    </section>
                </div>
            </div>
        </div>
    </div>
</main>

<div class="modal modal--adding">
    <div class="modal__wrapper">
        <button class="modal__close-button button" type="button">
            <svg class="modal__close-icon" width="18" height="18">
                <use xlink:href="#icon-close"></use>
            </svg>
            <span class="visually-hidden">Закрыть модальное окно</span></button>
        <div class="modal__content">
            <h1 class="modal__title">Пост добавлен</h1>
            <p class="modal__desc">
                Озеро Байкал – огромное древнее озеро в горах Сибири к северу от монгольской границы. Байкал считается
                самым глубоким озером в мире. Он окружен сетью пешеходных маршрутов, называемых Большой байкальской
                тропой. Деревня Листвянка, расположенная на западном берегу озера, – популярная отправная точка для
                летних экскурсий.
            </p>
            <div class="modal__buttons">
                <a class="modal__button button button--main" href="#">Синяя кнопка</a>
                <a class="modal__button button button--gray" href="#">Серая кнопка</a>
            </div>
        </div>
    </div>
</div>
