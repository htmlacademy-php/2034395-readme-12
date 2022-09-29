<?php

/**
 * Проверяет переданную дату на соответствие формату 'ГГГГ-ММ-ДД'
 *
 * Примеры использования:
 * is_date_valid('2019-01-01'); // true
 * is_date_valid('2016-02-29'); // true
 * is_date_valid('2019-04-31'); // false
 * is_date_valid('10.10.2010'); // false
 * is_date_valid('10/10/2010'); // false
 *
 * @param string $date Дата в виде строки
 *
 * @return bool True при совпадении с форматом 'ГГГГ-ММ-ДД', иначе false
 */
function is_date_valid(string $date): bool
{
    $format_to_check = 'Y-m-d';
    $dateTimeObj = date_create_from_format($format_to_check, $date);

    return $dateTimeObj !== false && array_sum(date_get_last_errors()) === 0;
}

/**
 * Валидация электронной почты
 *
 * @param mysqli $link
 * @param string $email
 *
 * @return array|false
 */
function validate_email(mysqli $link, string $email): array|false
{
    $sql = "SELECT * FROM users WHERE email = ?";

    $result = db_query_prepare_stmt($link, $sql, [$email]);

    $isEmailUsed = count($result) > 0;

    if ($isEmailUsed) {
        return 'Указанный адрес электронной почты уже зарегистрирован.';
    }

    return false;
}

/**
 * Валидация регистрационных данных
 *
 * @param mysqli $link
 * @param array $data
 *
 * @return array
 */
function validate_registration_data(mysqli $link, array $data): array
{
    $files_path = __DIR__ . '/uploads/';

    $errors = [];

    $login = $data['login'] ?? null;
    $email = $data['email'] ?? null;
    $password = $data['password'] ?? null;
    $re_password = $data['password-repeat'] ?? null;
    $file = $_FILES['userpic-file'] ?? null;

    if (strlen($login) === 0) {
        $errors[] = 'Придумайте логин.';
    }
    if (strlen($email) === 0) {
        $errors[] = 'Укажите адрес своей электронной почты.';
    }
    if (validate_email($link, $email)) {
        $errors[] = validate_email($link, $email);
    }
    if (strlen($password) === 0) {
        $errors[] = 'Придумайте пароль.';
    }
    if (strlen($re_password) === 0) {
        $errors[] = 'Повторите придуманный пароль.';
    }
    if ($password != $re_password) {
        $errors[] = 'Пароли не совпадают.';
    }
    if ($file['name'] && validate_file($file, $files_path)) {
        $errors[] = validate_file($file, $files_path);
    }

    return [
        "data" => [
            "email" => $email,
            "login" => $login,
            "password" => $password,
        ],
        "errors" => $errors
    ];
}

/**
 * Проверяет переданную информацию о файле и перемещает его из временного хранилища
 *
 * @param array $file
 * @param string $path
 *
 * @return array|bool
 */
function validate_file(array $file, string $path): string|bool
{
    $mime = $file['type'];
    $name = $file['name'];
    $tmp_name = $file['tmp_name'];

    if ($mime != 'image/gif' && $mime != 'image/jpeg' && $mime != 'image/png') {
        return 'Вы можете загрузить файлы только в следующих форматах: .png, .jpeg, .gif.';
    }

    move_uploaded_file($tmp_name, $path . $name);
    return false;
}

/**
 * Возвращает сокращенный текст, если его длина более 300 (по стандарту) символов
 *
 * @param string $text
 * @param int $maxSymbols
 *
 * @return array
 */
function show_data(string $text, int $maxSymbols = 300): array
{
    $array = explode(' ', $text);
    $result = [
        'text' => null,
        'isLong' => 0
    ];

    $symbols = 0;

    foreach ($array as $word) {
        $symbols += strlen($word);

        if ($symbols < $maxSymbols) {
            $result['text'] .= ' ' . $word;
        } else {
            $result['text'] .= '...';
            $result['isLong'] = 1;
            break;
        }
    }

    return $result;
}

/**
 * Проверяет все отправленные данные и возвращает подготовленный массив
 *
 * @param array $data
 * @param mysqli $link
 * @param string $type
 * @param array $user
 *
 * @return array
 */
function validate_post_data(array $data, mysqli $link, string $type, array $user): array
{
    $content_type = null;

    $sql = "SELECT ct.id FROM content_types ct WHERE `name` = ?";

    $result = db_query_prepare_stmt($link, $sql, [$type]);

    if (count($result) === 1) {
        $content_type = $result[0]['id'];
    }

    $files_path = __DIR__ . '/uploads/';

    $errors = [];

    $title = $data[$type . '-heading'] ?? null;
    $content = $data[$type . '-content'] ?? null;
    $author = $data[$type . '-author'] ?? null;
    $image_url = $data['photo-url'] ?? null;
    $video_url = $data['video-url'] ?? null;
    $site_url = $data[$type . '-url'] ?? null;
    $file = $_FILES['userpic-file-photo'] ?? null;

    $url = match ($type) {
        'photo' => strlen($file['name']) > 0 ? $files_path . $file['name'] : $image_url,
        'video' => $video_url,
        'link' => $site_url,
        default => null
    };

    if (strlen($title) === 0) {
        $errors[] = 'Укажите заголовок.';
    }
    if (strlen($title) > 70) {
        $errors[] = 'Заголовок не может превышать 70 символов.';
    }
    if ($type === 'text' && strlen($content) === 0) {
        $errors[] = 'Напишите текст поста';
    }
    if ($type === 'quote') {
        if (strlen($content) === 0) {
            $errors[] = 'Укажите текст цитаты';
        }

        if (strlen($author) === 0) {
            $errors[] = 'Укажите автора цитаты';
        }
    }
    if ($type === 'video' || $type === 'link') {
        $isUrlValid = filter_var($url, FILTER_VALIDATE_URL);

        if (strlen($url) === 0 || !$isUrlValid) {
            $errors[] = 'Укажите корректную ссылку на источник.';
        }

        if ($type === 'video' && !check_youtube_url($url)) {
            $errors[] = 'Указанная в ссылке видеозапись недоступна.';
        }
    }
    if ($type === 'photo') {
        if (strlen($file['name']) > 0) {
            $errors[] = validate_file($file, $files_path);
        }

        if (strlen($url) > 0 && !filter_var($url, FILTER_VALIDATE_URL)) {
            $errors[] = 'Укажите корректную ссылку на изображение';
        }

        if (strlen($file['name']) === 0 && strlen($url) === 0) {
            $errors[] = 'Прикрепите файл или укажите ссылку на изображение.';
        }
    }

    return [
        "data" => [
            "title" => $title,
            "content" => $content,
            "cite_author" => $author,
            "content_type" => $content_type,
            "author" => $user['id'],
            "image_url" => $image_url,
            "video_url" => $video_url,
            "site_url" => $site_url,
        ],
        "errors" => $errors
    ];
}

/**
 * Возвращает корректную дату
 *
 * @param string $date
 *
 * @return string
 */
function normalize_date(string $date): string
{
    $postUnix = strtotime($date);
    $interval = floor((time() - $postUnix) / 60);
    $type = "";
    $types = [
        "minutes" => ["минуту", "минуты", "минут"],
        "hours" => ["час", "часа", "часов"],
        "days" => ["день", "дня", "дней"],
        "weeks" => ["неделю", "недели", "недель"],
        "months" => ["месяц", "месяца", "месяцев"],
        "years" => ["год", "года", "лет"]
    ];

    if ($interval < 60) {
        $type = "minutes";
    } else if ($interval / 60 < 24) {
        $type = "hours";
        $interval = floor($interval / 60);
    } else if ($interval / 60 / 24 < 7) {
        $type = "days";
        $interval = floor($interval / 60 / 24);
    } else if ($interval / 60 / 24 / 7 < 5) {
        $type = "weeks";
        $interval = floor($interval / 60 / 24 / 7);
    } else if ($interval / 60 / 24 / 7 / 5 < 12) {
        $type = "months";
        $interval = floor($interval / 60 / 24 / 7 / 5);
    } else {
        $type = "years";
        $interval = floor($interval / 60 / 24 / 7 / 5 / 12);
    }

    $correctWord = get_noun_plural_form($interval, $types[$type][0], $types[$type][1], $types[$type][2]);

    return "$interval $correctWord";
}
