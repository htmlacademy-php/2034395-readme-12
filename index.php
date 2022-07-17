<?php
require_once 'helpers.php';
require_once 'init.php';

if (!$link) {
    $error = mysqli_connect_error();
    print($error);
    die();
}

// Получение списка постов
$sql = 'SELECT * FROM posts p'
    .   ' JOIN users u ON p.author = u.id'
    .   ' JOIN content_types ct ON p.content_type = ct.id'
    .   ' ORDER BY views DESC';
$result = mysqli_query($link, $sql);

if ($result === false) {
    print_r("Ошибка выполнения запроса: " . mysqli_error($link));
    die();
}

$data = mysqli_fetch_all($result, MYSQLI_ASSOC);

$is_auth = rand(0, 1);

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

function normalizeDate($date): string {
    $postUnix = strtotime($date);
    $interval = floor((time() - $postUnix) / 60);
    $type = "";
    $types = [
        "minutes" => ["минуту", "минуты", "минут"],
        "hours" => ["час", "часа", "часов"],
        "days" => ["день", "дня", "дней"],
        "weeks" => ["неделю", "недели", "недель"],
        "months" => ["месяц", "месяца", "месяцев"],
    ];

    if ($interval < 60) {
        $type = "minutes";
    } else if ($interval / 60 < 24) {
        $type = "hours";
        $interval = floor($interval / 60);
    } else if ($interval / 60 / 24 < 7) {
        $type = "days";
        $interval = floor($interval / 60 / 24);
    } else if ($interval / 60 / 24 / 7 < 5) {
        $type = "weeks";
        $interval = floor($interval / 60 / 24 / 7);
    } else {
        $type = "months";
        $interval = floor($interval / 60 / 24 / 7 / 5);
    }

    $correctWord = get_noun_plural_form($interval, $types[$type][0], $types[$type][1], $types[$type][2]);

    return "$interval $correctWord назад";
}

$content = include_template('main.php', ['data' => $data]);
$layout = include_template('layout.php', [
    "content" => $content,
    "title" => "readme: популярное",
    "user_name" => "Kirill",
    "is_auth" => $is_auth,
]);

print($layout);
