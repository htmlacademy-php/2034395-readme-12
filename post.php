<?php
require_once 'requires_auth.php';
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
