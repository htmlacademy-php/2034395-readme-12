<?php
require_once 'requires_guest.php';
require_once 'helpers.php';
require_once 'validateHelpers.php';

/**
 * @var mysqli $link
 * @var array $user
 */

$search_data = $_GET['search'] ?? null;

/**
 * Возвращает название класса для типа контента по его идентификатору
 *
 * @param mysqli $link
 * @param int $id
 * @return string|false
 */
function get_content_class_by_id(mysqli $link, int $id): string|false
{
    $sql = "SELECT * FROM content_types" .
        " WHERE id = ?";

    $result = db_query_prepare_stmt($link, $sql, [$id]);

    if (!$result) {
        return false;
    }

    return $result[count($result) - 1]["class_name"];
}

/**
 * Фильтр поиска, возвращает посты с совпадениями в контенте или названии
 *
 * @param mysqli $link
 * @param string $filter
 *
 * @return array|false
 */
function posts_search_filter(mysqli $link, string $filter): array|false
{
    $sql = "SELECT p.*, u.avatar_url, u.login FROM posts p"
        . " JOIN users u ON p.author = u.id"
        . " WHERE MATCH(`title`, content) AGAINST(?)";

    return db_query_prepare_stmt($link, $sql, [$filter]);
}

$posts = $search_data ? posts_search_filter($link, $search_data) : [];

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
