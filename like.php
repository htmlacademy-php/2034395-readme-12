<?php
require_once 'requires_guest.php';

$action = $_GET["action"] ?? null;
$address = $_GET["address"] ?? null;
$profile_id = $_GET["profile_id"] ?? null;
$post_id = $_GET["post_id"] ?? null;

$error = false;

if (isset($address)) {
    $sql = null;

    $is_liked = isPostLiked($link, $user['id'], $post_id);

    if ($action === 'like' && !$is_liked) {
        $sql = "INSERT INTO `likes` (`user`, `post`) VALUES (?, ?)";
    }
    else if ($action === 'unlike' && $is_liked) {
        $sql = "DELETE FROM `likes` s WHERE s.user = ? AND s.post = ?";
    }
    else {
        $error = true;
    }

    if (!$error) {
        db_query_prepare_stmt($link, $sql, [$user['id'], $post_id], QUERY_EXECUTE);
    }

    match ($address) {
        'popular' => header("Location: /popular.php?tab=all&page=1&sort=views"),
        'profile' => header("Location: /" . $address . ".php?id=" . $profile_id),
        'post' => header("Location: /" . $address . ".php?id=" . $post_id),
        default => header("Location: /")
    };
}

exit();
