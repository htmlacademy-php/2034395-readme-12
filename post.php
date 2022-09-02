<?php
require_once 'requires_guest.php';
//fixme $user might be null

$post_id = $_GET['id'] ?? null;
$action = $_GET['act'] ?? null;
$post_author = $_GET['author'] ?? null;
$post = [];
$comments = [];
$comment_data = $_POST;
$views = 0;

if (isset($post_id)) {
    $post = getPostById($link, $post_id);
    $comments = getComments($link, $post_id);
    $views = getPostViews($link, $post_id)[0]['v'] ?? 0;
    addPostView($link, $user['id'], $post_id);
}

if ($action === 'sub') {
    $author_data = getUserData($link, 'id', $post_author);

    sendEmailNotify($user, $author_data, EMAIL_SUB_TYPE);
}

if (isset($comment_data['comment'])) {
    addComment($link, $comment_data['comment'], $post_id, $user['id']);
}

$content = include_template('post-details.php', [
    'post' => $post,
    'comments' => $comments,
    'link' => $link,
    'user' => $user,
    'views' => $views,
]);
$layout = include_template('layout.php', [
    "content" => $content,
    "title" => "readme: просмотр поста",
    "user" => $user,
    "is_auth" => $is_auth,
]);

print($layout);
