<?php
require_once 'requires_guest.php';
require_once 'helpers.php';
require_once 'validateHelpers.php';

/**
 * @var mysqli $link
 * @var array $user
 */

$data = $_POST;
$post_type = $_GET['type'] ?? 'photo';

$post_data = ['errors' => []];

/**
 * Возвращает идентификатор категории по ее названию
 *
 * @param mysqli $link
 * @param string $type
 *
 * @return int|false
 */
function get_category_id(mysqli $link, string $type): int|false
{
    $sql = "SELECT ct.id FROM content_types ct WHERE `name` = ?";

    $result = db_query_prepare_stmt($link, $sql, [$type]);

    if (!$result) {
        return false;
    }

    return $result[count($result) - 1]['id'];
}

/**
 * Валидация URL
 *
 * @param string $url
 * @param string $type
 *
 * @return array|false
 */
function validate_url(string $url, string $type): array|false
{
    $isUrlValid = filter_var($url, FILTER_VALIDATE_URL);

    if (strlen($url) === 0 || !$isUrlValid) {
        return ['target' => 'url', 'text' => 'Укажите корректную ссылку на источник.'];
    }

    if ($type === 'video' && !check_youtube_url($url)) {
        return ['target' => 'url', 'text' => 'Указанная в ссылке видеозапись недоступна.'];
    }

    return false;
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
function validate_data(array $data, mysqli $link, string $type, array $user): array
{
    $ct = get_category_id($link, $type);

    $files_path = __DIR__ . '/uploads/';

    $errors = [];

    $title = $data[$type . '-heading'] ?? null;
    $content = $data[$type . '-content'] ?? null;
    $author = $data[$type . '-author'] ?? null;
    $image_url = $data['image-url'] ?? null;
    $video_url = $data['video-url'] ?? null;
    $site_url = $data[$type . '-url'] ?? null;
    $file = $_FILES['userpic-file-photo'] ?? null;

    $url = match ($type) {
        'photo' => $file ? $files_path . $file['name'] : $image_url,
        'video' => $video_url,
        'link' => $site_url,
        default => ''
    };

    if (strlen($title) === 0) {
        $errors[] = ['target' => 'title', 'text' => 'Укажите заголовок.'];
    }
    if (strlen($title) > 70) {
        $errors[] = ['target' => 'title', 'text' => 'Заголовок не может превышать 70 символов.'];
    }
    if ($url && validate_url($url, $type)) {
        $errors[] = validate_url($url, $type);
    }
    if ($file && validate_file($file, $files_path)) {
        $errors[] = validate_file($file, $files_path);
    }

    return [
        "data" => [
            "title" => $title,
            "content" => $content,
            "cite_author" => $author,
            "content_type" => $ct,
            "author" => $user['id'],
            "image_url" => $image_url,
            "video_url" => $video_url,
            "site_url" => $site_url,
        ],
        "errors" => $errors
    ];
}

/**
 * Добавляет новый пост в базу данных
 *
 * @param mysqli $link
 * @param array $post
 *
 * @return array|false
 */
function add_post(mysqli $link, array $post): array|false
{
    $sql = "INSERT INTO `posts` (`date`, `title`, `content`, `cite_author`, `content_type`, `author`, `image_url`, `video_url`, `site_url`)" .
        " VALUES (NOW(), ?, ?, ?, ?, ?, ?, ?, ?)";

    return db_query_prepare_stmt($link, $sql, $post['data']);
}

/**
 * Возвращает общее количество постов в базе данных
 *
 * @param mysqli $link
 *
 * @return int
 */
function get_posts_count(mysqli $link): int
{
    $sql = "SELECT * FROM posts";

    $result = db_query_prepare_stmt($link, $sql);

    if (!$result) {
        return 0;
    }

    return count($result);
}

if (!empty($data)) {
    $post_data = validate_data($data, $link, $post_type, $user);

    $new_post_id = get_posts_count($link) + 1;

    if (count($post_data['errors']) === 0) {
        add_post($link, $post_data);
        header("Location: /post.php?id=" . $new_post_id);
        exit();
    }
}

$content = include_template('adding-post.php', [
    "post_type" => $post_type,
    "errors" => $post_data['errors'],
]);
$layout = include_template('layout.php', [
    "content" => $content,
    "title" => "readme: создание поста",
    "user" => $user,
]);

print($layout);
