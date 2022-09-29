<?php
require_once 'requires_auth.php';
require_once 'helpers.php';
require_once 'validateHelpers.php';

/**
 * @var mysqli $link
 * @var array $user
 */

$data = $_POST;
$post_type = $_GET['type'] ?? 'photo';

$post_data = ['errors' => []];

if (!empty($data)) {
    $post_data = validate_post_data($data, $link, $post_type, $user);

    if (count($post_data['errors']) === 0) {
        $sql = "INSERT INTO `posts` (`date`, `title`, `content`, `cite_author`, `content_type`, `author`, `image_url`, `video_url`, `site_url`)" .
            " VALUES (NOW(), ?, ?, ?, ?, ?, ?, ?, ?)";

        db_query_prepare_stmt($link, $sql, $post_data['data']);

        $sql = "SELECT * FROM posts";

        $result = db_query_prepare_stmt($link, $sql);

        $new_post_id = $result[count($result) - 1]['id'] ?? null;

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
