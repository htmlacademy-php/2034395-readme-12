<?php
require_once 'helpers.php';
require_once 'init.php';

$tab = $_GET['tab'] ?? 'all';

function showData($text, $maxSymbols = 300): array
{
    $array = explode(' ', $text);
    $result = [
        'text' => null,
        'isLong' => 0
    ];

    $symbols = 0;

    foreach($array as $word) {
        $symbols = $symbols + strlen($word);

        if ($symbols < $maxSymbols) {
            $result['text'] = $result['text'] . ' ' . $word;
        } else {
            $result['text'] = $result['text'] . '...';
            $result['isLong'] = 1;
            break;
        }
    }

    return $result;
}

function getPostsList($link) {
    if (!$link) {
        $error = mysqli_connect_error();
        print($error);
        die();
    }

    $sql = "SELECT p.*, u.avatar_url, u.login, ct.name, ct.class_name FROM posts p"
        .   " JOIN users u ON p.author = u.id"
        .   " JOIN content_types ct ON p.content_type = ct.id"
        .   " ORDER BY views DESC";

    $result = mysqli_query($link, $sql);

    if ($result === false) {
        print_r("Ошибка выполнения запроса: " . mysqli_error($link));
        die();
    }

    return mysqli_fetch_all($result, MYSQLI_ASSOC);
}

function filterPosts($post) {
    return $post['name'] === $_GET['tab'];
}

$data = getPostsList($link);

if ($tab !== 'all') {
    $data = array_filter($data, "filterPosts");
}

$is_auth = rand(0, 1);

$content = include_template('main.php', ['data' => $data, 'tab' => $tab]);
$layout = include_template('layout.php', [
    "content" => $content,
    "title" => "readme: популярное",
    "user_name" => "Kirill",
    "is_auth" => $is_auth,
]);

print($layout);
