<?php
require_once 'requires_guest.php';
require_once 'helpers.php';
require_once 'validateHelpers.php';

/**
 * @var mysqli $link
 * @var array $user
 */

$post_id = $_GET['id'] ?? null;
$action = $_GET['act'] ?? null;
$post_author = $_GET['author'] ?? null;
$post = [];
$comments = [];
$comment_data = $_POST;
$views = 0;

/**
 * Проверяет, просматривал ли пользователь пост
 *
 * @param mysqli $link
 * @param int $user_id
 * @param int $post_id
 *
 * @return bool
 */
function check_is_user_view_post(mysqli $link, int $user_id, int $post_id): bool
{
    $sql = "SELECT * FROM views"
        . " WHERE post_id = ? AND user_id = ?";

    $result = db_query_prepare_stmt($link, $sql, [$post_id, $user_id]);

    return (bool)$result;
}

/**
 * Добавляет посту просмотр пользователя
 *
 * @param mysqli $link
 * @param int $user_id
 * @param int $post_id
 *
 * @return array|false
 */
function add_post_view(mysqli $link, int $user_id, int $post_id): array|false
{
    $isUserViewPost = check_is_user_view_post($link, $user_id, $post_id);

    if ($isUserViewPost) {
        return false;
    }

    $sql = "INSERT INTO views (post_id, user_id) VALUES (?, ?)";

    return db_query_prepare_stmt($link, $sql, [$post_id, $user_id]);
}

/**
 * Возвращает массив с данными о просмотре поста
 *
 * @param mysqli $link
 * @param int $post_id
 *
 * @return array|false
 */
function get_post_views(mysqli $link, int $post_id): array|false
{
    $sql = "SELECT COUNT(*) views FROM views"
        . " WHERE post_id = ?";

    return db_query_prepare_stmt($link, $sql, [$post_id]);
}

/**
 * Добавляет комментарий к посту
 *
 * @param mysqli $link
 * @param string $text
 * @param int $post_id
 * @param int $author_id
 *
 * @return array|false
 */
function add_comment(mysqli $link, string $text, int $post_id, int $author_id): array|false
{
    $sql = "INSERT INTO comments (date, content, author, post) VALUES(NOW(), ?, ?, ?)";

    return db_query_prepare_stmt($link, $sql, [$text, $post_id, $author_id]);
}

/**
 * Возвращает пост по его идентификатору, если пост не найден, то направляет на страницу по ошибке 404.
 *
 * @param mysqli $link
 * @param int $post_id
 *
 * @return array|false
 */
function get_post_by_id(mysqli $link, int $post_id): array|false
{
    $sql = "SELECT p.*, ct.name, ct.class_name FROM posts p" .
        " JOIN content_types ct ON p.content_type = ct.id" .
        " WHERE p.id = ?";

    $post = db_query_prepare_stmt($link, $sql, [$post_id]);

    if (!$post) {
        http_response_code(404);
        die();
    }

    return $post;
}

if (isset($post_id)) {
    $post = get_post_by_id($link, $post_id);

    $comments = get_comments($link, $post_id);

    $views = get_post_views($link, $post_id);

    if ($views) {
        $views = $views[count($views) - 1]['views'];
    }

    add_post_view($link, $user['id'], $post_id);
}

if ($action === 'sub') {
    $author_data = get_user_data($link, 'id', $post_author);

    sendEmailNotify($user, $author_data, EMAIL_SUB_PRESET['subject'], EMAIL_SUB_PRESET['content']);
}

if (!empty($comment_data) && isset($post_id)) {
    add_comment($link, $comment_data['comment'], $post_id, $user['id']);
}

$content = include_template('post-details.php', [
    'post' => $post[count($post) - 1],
    'comments' => $comments,
    'link' => $link,
    'user' => $user,
    'views' => $views,
]);
$layout = include_template('layout.php', [
    "content" => $content,
    "title" => "readme: просмотр поста",
    "user" => $user,
]);

print($layout);
