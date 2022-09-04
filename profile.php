<?php
require_once 'requires_guest.php';
require_once 'helpers.php';
require_once 'validateHelpers.php';

/**
 * @var mysqli $link
 * @var array $user
 */

$user_id = $_GET["id"] ?? null;
$action = $_GET['act'] ?? null;

$profile_data = null;
$posts_data = null;
$is_owner = false;
$is_subscribed = check_is_user_subscribed($link, $user['id'], $user_id);

if (isset($user_id)) {
    $sql = "SELECT p.*, ct.name, ct.class_name FROM posts p" .
        " JOIN content_types ct ON p.content_type = ct.id" .
        " WHERE p.author = ?";

    $posts_data = db_query_prepare_stmt($link, $sql, [$user_id]);

    $profile_data = get_user_data($link, 'id', $user_id);

    if ($action === 'sub') {
        sendEmailNotify($user, $profile_data, EMAIL_SUB_PRESET['subject'], EMAIL_SUB_PRESET['content']);
    }

    if ($user_id === $user['id']) {
        $is_owner = true;
    }
}

$content = include_template('profile-page.php', [
    "user" => $user,
    "profile" => $profile_data[count($profile_data) - 1],
    "posts" => $posts_data,
    "is_owner" => $is_owner,
    "is_subscribed" => $is_subscribed,
    "link" => $link
]);

$layout = include_template('layout.php', [
    "content" => $content,
    "title" => "readme: поиск",
    "user" => $user,
]);

print($layout);
