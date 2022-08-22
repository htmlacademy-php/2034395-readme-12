<?php
require_once 'requires_guest.php';

$search_data = $_GET['search'] ?? null;

function postsSearchFilter($link, $filter): array {
    $sql = "SELECT p.*, u.avatar_url, u.login FROM `posts` p" .
        " JOIN `users` u ON p.author = u.id" .
        " WHERE MATCH(`title`, `content`) AGAINST(?)";

    $stmt = db_get_prepare_stmt($link, $sql, [$filter]);

    mysqli_stmt_execute($stmt);

    $result = mysqli_stmt_get_result($stmt);

    return mysqli_fetch_all($result, MYSQLI_ASSOC);
}

$posts = $search_data ? postsSearchFilter($link, $search_data) : [];

$content = include_template('search-result.php', [
    "search_data" => $search_data,
    "posts" => $posts,
    "link" => $link,
]);

if (count($posts) === 0) $content = include_template('no-results.php', ["search_data" => $search_data]);

$layout = include_template('layout.php', [
    "content" => $content,
    "title" => "readme: поиск",
    "user" => $user,
    "is_auth" => $is_auth,
]);

print($layout);
