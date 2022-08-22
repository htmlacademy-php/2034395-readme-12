<?php
require 'requires_guest.php';

$action = $_GET["action"] ?? null;
$address = $_GET["address"] ?? null;
$profile_id = $_GET["profile_id"] ?? null;
$post_id = $_GET["post_id"] ?? null;
$post_author = $_GET["post_author"] ?? null;

$profile_data = null;

$error = false;

if (isset($address)) {
    $sql = null;
    $target_id = $profile_id ?? $post_author;

    $profile_data = getUserData($link, 'id', $target_id);

    $is_subscribed = checkIsUserSubscribed($link, $user['id'], $target_id);

    if ($action === 'sub' && !$is_subscribed) {
        $sql = "INSERT INTO `subscriptions` (`user`, `subscriber`) VALUES (?, ?)";
    } else {
        if ($action === 'unsub' && $is_subscribed) {
            $sql = "DELETE FROM `subscriptions` s WHERE s.user = ? AND s.subscriber = ?";
        } else {
            $error = true;
        }
    }

    if (!$error) {
        db_query_prepare_stmt($link, $sql, [$target_id, $user['id']], QUERY_EXECUTE);
        sendEmailNotify($user, $profile_data, EMAIL_SUB_TYPE);
    }

    match ($address) {
        'profile' => header("Location: /src/php/" . $address . ".php?id=" . $profile_id),
        'post' => header("Location: /src/php/" . $address . ".php?id=" . $post_id),
        default => header("Location: /")
    };
}

exit();
