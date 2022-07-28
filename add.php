<?php
require_once 'helpers.php';
require_once 'init.php';

$data = $_POST;
$post_type = $_GET['type'];

$post_data = ['errors' => []];

function getCategoryId($link, $type) {
    if (!$link) {
        $error = mysqli_connect_error();
        print($error);
        die();
    }

    $sql = "SELECT ct.id FROM content_types ct WHERE `name` = '$type'";

    $result = mysqli_query($link, $sql);

    if ($result === false) {
        print_r("Ошибка выполнения запроса: " . mysqli_error($link));
        die();
    }

    return mysqli_fetch_all($result, MYSQLI_ASSOC)[0]['id'];
}

function getContentTypes($link): array {
    if (!$link) {
        $error = mysqli_connect_error();
        print($error);
        die();
    }

    $sql = "SELECT * FROM content_types";

    $result = mysqli_query($link, $sql);

    if ($result === false) {
        print_r("Ошибка выполнения запроса: " . mysqli_error($link));
        die();
    }

    return mysqli_fetch_all($result, MYSQLI_ASSOC);
}

function validateUrl($url, $type): array|bool {
    $isUrlValid = filter_var($url, FILTER_VALIDATE_URL);

    if (strlen($url) == 0 || !$isUrlValid) {
        return ['target' => 'url', 'text' => 'Укажите корректную ссылку на источник.'];
    }

    if ($type == 'video' && !check_youtube_url($url)) {
        return ['target' => 'url', 'text' => 'Указанная в ссылке видеозапись недоступна.'];
    }

    return false;
}

function validateData($data, $link, $type): array {
    $ct = getCategoryId($link, $type);

    $files_path = __DIR__ . '/uploads/';

    $errors = [];

    $title = $data[$type . '-heading'] ?? null;
    $content = $data[$type . '-content'] ?? null;
    $author = $data[$type . '-author'] ?? null;
    $image_url = $data['image-url'] ?? null;
    $video_url = $data['video-url'] ?? null;
    $site_url = $data[$type . '-url'] ?? null;
    $tags = $data[$type . '-tags'] ?? null;
    $file = $_FILES['userpic-file-photo'] ?? null;

    $url = null;

    if ($type == 'photo') {
        if ($file) $url = $files_path . $file['name'];
        else $url = $image_url;
    }
    else if ($type == 'video') $url = $video_url;
    else if ($type == 'link') $url = $site_url;

    if (strlen($title) == 0) $errors[] = ['target' => 'title', 'text' => 'Укажите заголовок.'];
    if (strlen($title) > 70) $errors[] = ['target' => 'title', 'text' => 'Заголовок не может превышать 70 символов.'];
    if ($url && validateUrl($url, $type)) $errors[] = validateUrl($url, $type);
    if ($file && validateFile($file, $files_path)) $errors[] = validateFile($file, $files_path);

    return [
        "data" => [
            "title" => $title,
            "content" => $content,
            "cite_author" => $author,
            "content_type" => $ct,
            "image_url" => $image_url,
            "video_url" => $video_url,
            "site_url" => $site_url,
        ],
        "errors" => $errors
    ];
}

function addPost($link, $post) {
    if (!$link) {
        $error = mysqli_connect_error();
        print($error);
        die();
    }

    $sql = "INSERT INTO `posts` (`date`, `title`, `content`, `cite_author`, `content_type`, `author`, `image_url`, `video_url`, `site_url`, `views`)" .
        " VALUES (NOW(), ?, ?, ?, ?, 1, ?, ?, ?, 0)";

    $stmt = db_get_prepare_stmt($link, $sql, $post['data']);

    mysqli_stmt_execute($stmt);
    mysqli_stmt_close($stmt);
}

if (count($data) > 0) {
    $post_data = validateData($data, $link, $post_type);

    if (count($post_data['errors']) == 0) {
        addPost($link, $post_data);
    }
}

$content_types = getContentTypes($link);

$content = include_template('adding-post.php', [
    "content_types" => $content_types,
    "post_type" => $post_type,
    "errors" => $post_data['errors'],
]);
$layout = include_template('layout.php', [
    "content" => $content,
    "title" => "readme: создание поста",
    "user_name" => "Kirill",
    "is_auth" => $is_auth,
]);

print($layout);
