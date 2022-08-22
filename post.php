<?php
require_once 'requires_guest.php';

$post_id = $_GET['id'] ?? null;
$comment_data = $_POST;

if (isset($comment_data['comment'])) {
    addComment($link, $comment_data['comment'], $post_id, $user['id']);
}

$post = getPostById($link, $post_id);
$comments = getComments($link, $post_id);

$content = include_template('post-details.php', [
    'post' => $post,
    'comments' => $comments,
    'link' => $link,
    'user' => $user
]);
$layout = include_template('layout.php', [
    "content" => $content,
    "title" => "readme: просмотр поста",
    "user" => $user,
    "is_auth" => $is_auth,
]);

print($layout);
