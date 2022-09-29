<?php
require 'requires_auth.php';

/**
 * @var mysqli $link
 * @var array $user
 */

$action = $_GET["action"] ?? null;
$address = $_GET["address"] ?? null;
$profile_id = $_GET["profile_id"] ?? null;
$post_id = $_GET["post_id"] ?? null;
$post_author = $_GET["post_author"] ?? null;

if (!isset($address)) {
    header('Location: /');
    exit();
}

$target_id = $profile_id ?? $post_author;

$is_subscribed = check_is_user_subscribed($link, $user['id'], $target_id);

$sql = $is_subscribed ? "DELETE FROM subscriptions s WHERE s.user = ? AND s.subscriber = ?"
    : "INSERT INTO subscriptions (user, subscriber) VALUES (?, ?)";

db_query_prepare_stmt($link, $sql, [$target_id, $user['id']]);

match ($address) {
    'profile' => header("Location: /$address" . ".php?id=$profile_id"),
    'post' => header("Location: /$address" . ".php?id=$post_id" . "&author=$post_author"),
    default => header("Location: /")
};
