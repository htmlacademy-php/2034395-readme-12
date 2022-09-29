<?php
require_once 'requires_auth.php';
require_once 'helpers.php';
require_once 'validateHelpers.php';

/**
 * @var mysqli $link
 * @var array $user
 */

$search_data = $_GET['search'] ?? null;

$posts = [];

if ($search_data) {
    $sql = "SELECT p.*, u.avatar_url, u.login FROM posts p"
        . " JOIN users u ON p.author = u.id"
        . " WHERE MATCH(`title`, content) AGAINST(?)";

    $posts = db_query_prepare_stmt($link, $sql, [$search_data]);
}

$content = include_template('search-result.php', [
    "search_data" => $search_data,
    "posts" => $posts,
    "link" => $link,
]);

if (count($posts) === 0) {
    $content = include_template('no-results.php', ["search_data" => $search_data]);
}

$layout = include_template('layout.php', [
    "content" => $content,
    "title" => "readme: поиск",
    "user" => $user,
]);

print($layout);
