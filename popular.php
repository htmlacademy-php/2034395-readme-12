<?php
require_once 'requires_guest.php';

$tab = $_GET['tab'] ?? 'all';
$page = $_GET['page'] ?? 1;
$sort = $_GET['sort'] ?? 'views';

function getPostsList($link, $page = 1, $sort = 'views')
{
    $sql = "SELECT p.*, u.avatar_url, u.login, ct.name, ct.class_name FROM `posts` p"
        . " JOIN `users` u ON p.author = u.id"
        . " JOIN `content_types` ct ON p.content_type = ct.id"
        . " ORDER BY `views` DESC";

    $result = db_query_prepare_stmt($link, $sql, [], QUERY_ASSOC);

    $offset = 0;

    if ($page > 1) {
        $offset = ($page - 1) * 6;
    }

    return ["posts" => array_slice($result, $offset, 6), "count" => count($result)];
}

function filterPosts($post)
{
    return $post['name'] === $_GET['tab'];
}

$data = getPostsList($link, $page);

if ($tab !== 'all') {
    $data = array_filter($data, "filterPosts");
}

$content = include_template('popular-page.php', [
    'data' => $data['posts'],
    'posts_count' => $data['count'],
    "page" => $page,
    'tab' => $tab,
    "link" => $link,
    "user" => $user
]);
$layout = include_template('layout.php', [
    "content" => $content,
    "title" => "readme: популярное",
    "user" => $user,
    "is_auth" => $is_auth,
    "target" => "popular"
]);

print($layout);
