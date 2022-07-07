<?php
require_once 'helpers.php';
$is_auth = rand(0, 1);

$data = [
    [
        "title" => "Цитата",
        "type" => "post-quote",
        "content" => "Мы в жизни любим только раз, а после ищем лишь похожих",
        "authorName" => "Лариса",
        "authorAvatarUrl" => "userpic-larisa-small.jpg",
    ],
    [
        "title" => "Игра престолов",
        "type" => "post-text",
        "content" => "Не могу дождаться начала финального сезона своего любимого сериала!",
        "authorName" => "Владик",
        "authorAvatarUrl" => "userpic.jpg",
    ],
    [
        "title" => "Наконец, обработал фотки!",
        "type" => "post-photo",
        "content" => "rock-medium.jpg",
        "authorName" => "Виктор",
        "authorAvatarUrl" => "userpic-mark.jpg",
    ],
    [
        "title" => "Моя мечта",
        "type" => "post-photo",
        "content" => "coast-medium.jpg",
        "authorName" => "Лариса",
        "authorAvatarUrl" => "userpic-larisa-small.jpg",
    ],
    [
        "title" => "Лучшие курсы",
        "type" => "post-link",
        "content" => "www.htmlacademy.ru",
        "authorName" => "Вадик",
        "authorAvatarUrl" => "userpic.jpg",
    ],
];

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
