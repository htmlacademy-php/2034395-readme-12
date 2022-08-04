<?php
require_once 'requires_guest.php';

$post_id = $_GET['id'];

function normalizeViews($var): string {
    $arr = str_split($var);
    $arr = array_slice($arr, -1);

    $lastSymbol = $arr[0];

    $word = 'просмотров';

    if ($lastSymbol == 1) $word = 'просмотр';
    else if ($lastSymbol > 1 && $lastSymbol < 5) $word = 'просмотра';

    return $var . ' ' . $word;
}

function normalizeSubs($var): string {
    $arr = str_split($var);
    $arr = array_slice($arr, -1);

    $lastSymbol = $arr[0];

    $word = 'подписчиков';

    if ($lastSymbol == 1) $word = 'подписчик';
    else if ($lastSymbol > 1 && $lastSymbol < 5) $word = 'подписчика';

    return $word;
}

function getPostById($link, $id) {
    if (!$link) {
        $error = mysqli_connect_error();
        print($error);
        die();
    }

    $sql = " SELECT *, COUNT(subscriber) subs_count FROM posts p" .
        " JOIN users u ON p.author = u.id" .
        " JOIN content_types ct ON p.content_type = ct.id" .
        " LEFT OUTER JOIN subscriptions s ON u.id = s.user" .
        " WHERE p.id = $id";

    $result = mysqli_query($link, $sql);

    if ($result === false) {
        print_r("Ошибка выполнения запроса: " . mysqli_error($link));
        die();
    }

    $post = mysqli_fetch_all($result, MYSQLI_ASSOC);

    if (isset($post[0]['id'])) return $post;
    else {
        http_response_code(404);
        die();
    }
}

function getComments($link, $id): array {
    $sql = " SELECT * FROM comments c" .
        " JOIN users u ON c.author = u.id" .
        " WHERE c.post = $id";

    $result = mysqli_query($link, $sql);

    if ($result === false) {
        print_r("Ошибка выполнения запроса: " . mysqli_error($link));
        die();
    }

    return mysqli_fetch_all($result, MYSQLI_ASSOC);
}

$post = getPostById($link, $post_id);
$comments = getComments($link, $post_id);

$content = include_template('post-details.php', [
    'post' => $post[0],
    'comments' => $comments,
]);
$layout = include_template('layout.php', [
    "content" => $content,
    "title" => "readme: просмотр поста",
    "user" => $user,
    "is_auth" => $is_auth,
]);

print($layout);
