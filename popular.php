<?php
require_once 'requires_auth.php';
require_once 'helpers.php';
require_once 'validateHelpers.php';

/**
 * @var mysqli $link
 * @var array $user
 */

$tab = $_GET['tab'] ?? 'all';
$page = $_GET['page'] ?? 1;
$sort = $_GET['sort'] ?? 'views';

$posts = get_posts_list($link, $page, $tab, $sort);

$sql = "SELECT * FROM posts";

$allPosts = db_query_prepare_stmt($link, $sql);

$content = include_template('popular-page.php', [
    'posts' => $posts,
    'posts_count' => count($allPosts),
    'page' => $page,
    'tab' => $tab,
    'sort' => $sort,
    'link' => $link,
    'user' => $user
]);
$layout = include_template('layout.php', [
    'content' => $content,
    'title' => 'readme: популярное',
    'user' => $user,
    'target' => 'popular'
]);

print($layout);
