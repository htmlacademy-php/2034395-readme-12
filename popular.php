<?php
require_once 'requires_guest.php';
require_once 'helpers.php';
require_once 'validateHelpers.php';

/**
 * @var mysqli $link
 * @var array $user
 */

$tab = $_GET['tab'] ?? 'all';
$page = (int)$_GET['page'] ?? 1;
$sort = $_GET['sort'] ?? 'views';

/**
 * Возвращает список постов из базы данных, исходя из заданных параметров
 *
 * @param mysqli $link
 * @param int $page
 * @param string $sort
 * @param string $tab
 *
 * @return array|false
 */
function get_posts_list(mysqli $link, int $page, string $tab, string $sort): array|false
{
    $offset = $page > 1 ? ($page - 1) * 6 : 0;

    $queryParameters = [];
    $filterQuery = '';

    if ($tab !== 'all') {
        $queryParameters[] = $tab;
        $filterQuery = " WHERE ct.name = ?";
    }

    $queryParameters[] = $offset;

    [$sortingQuery, $orderBy] = match ($sort) {
        'likes' => [
            " LEFT JOIN (SELECT post, COUNT(*) count from likes GROUP BY post) likes ON p.id = likes.post",
            "likes.count"
        ],
        'date' => [
            "",
            "p.id"
        ],
        default => [
            " LEFT JOIN (SELECT post_id, COUNT(*) count from views GROUP BY post_id) views ON p.id = views.post_id",
            "views.count"
        ]
    };

    $sql = "SELECT p.*, u.avatar_url, u.login, ct.name, ct.class_name FROM posts p"
        . " $sortingQuery"
        . " JOIN users u ON p.author = u.id"
        . " JOIN content_types ct ON p.content_type = ct.id"
        . " $filterQuery"
        . " ORDER BY $orderBy DESC"
        . " LIMIT 6 OFFSET ?";

    return db_query_prepare_stmt($link, $sql, $queryParameters);
}

$posts = get_posts_list($link, $page, $tab, $sort);

$content = include_template('popular-page.php', [
    'posts' => $posts,
    'posts_count' => count($posts),
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
