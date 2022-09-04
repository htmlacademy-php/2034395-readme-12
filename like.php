<?php
require_once 'requires_guest.php';

/**
 * @var mysqli $link
 * @var array $user
 */

$address = $_GET['address'] ?? null;
$profile_id = $_GET['profile_id'] ?? null;
$post_id = $_GET['post_id'] ?? null;
$tab = $_GET['tab'] ?? 'all';
$page = $_GET['page'] ?? 1;
$sort = $_GET['sort'] ?? 'views';

if (!isset($address)) {
    header('Location: /');
    exit();
}

$is_liked = is_post_liked($link, $user['id'], $post_id);

$sql = $is_liked ? 'DELETE FROM likes s WHERE s.user = ? AND s.post = ?'
    : 'INSERT INTO likes (user, post) VALUES (?, ?)';

db_query_prepare_stmt($link, $sql, [$user['id'], $post_id]);

match ($address) {
    'popular' => header('Location: /popular.php?tab=' . $tab . '&page=' . $page . '&sort=' . $sort),
    'profile' => header('Location: /' . $address . '.php?id=' . $profile_id),
    'post' => header('Location: /' . $address . '.php?id=' . $post_id),
    default => header('Location: /')
};
