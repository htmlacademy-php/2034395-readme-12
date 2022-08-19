<?php
require_once 'requires_guest.php';

$user_id = $_GET["id"] ?? null;

$profile_data = null;
$posts_data = null;
$is_owner = false;
$is_subscribed = checkIsUserSubscribed($link, $user['id'], $user_id);

if (isset($user_id)) {
    $sql = "SELECT p.*, ct.name, ct.class_name FROM `posts` p" .
        " JOIN `content_types` ct ON p.content_type = ct.id" .
        " WHERE p.author = ?";

    $posts_data = db_query_prepare_stmt($link, $sql, [$user_id], 'assoc');

    $profile_data = getUserData($link, 'id', $user_id);

    if ($user_id == $user['id']) $is_owner = true;
}

$content = include_template('profile-page.php', [
    "user" => $user,
    "profile" => $profile_data,
    "posts" => $posts_data,
    "is_owner" => $is_owner,
    "is_subscribed" => $is_subscribed,
    "link" => $link
]);

$layout = include_template('layout.php', [
    "content" => $content,
    "title" => "readme: поиск",
    "user" => $user,
    "is_auth" => $is_auth,
]);

print($layout);
