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
        "title" => "Testing long text",
        "type" => "post-text",
        "content" => "Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Integer malesuada nunc vel risus commodo viverra. Amet porttitor eget dolor morbi non arcu risus quis. Ut faucibus pulvinar elementum integer enim neque volutpat ac. Eget felis eget nunc lobortis. Auctor neque vitae tempus quam pellentesque nec nam aliquam sem. Sagittis purus sit amet volutpat consequat mauris nunc congue. Blandit volutpat maecenas volutpat blandit aliquam etiam erat. Adipiscing elit duis tristique sollicitudin nibh sit amet. At erat pellentesque adipiscing commodo elit at. Non consectetur a erat nam at lectus urna. Etiam erat velit scelerisque in. Nascetur ridiculus mus mauris vitae ultricies leo integer. In massa tempor nec feugiat nisl. Id semper risus in hendrerit gravida rutrum quisque non tellus. Sit amet justo donec enim diam. Ultrices tincidunt arcu non sodales neque sodales. Habitasse platea dictumst quisque sagittis purus sit amet volutpat. Ac orci phasellus egestas tellus rutrum. Enim sit amet venenatis urna cursus eget. In metus vulputate eu scelerisque felis. Tristique senectus et netus et malesuada fames. Aliquam id diam maecenas ultricies. Tortor pretium viverra suspendisse potenti nullam ac tortor. Ultrices in iaculis nunc sed. Dolor sit amet consectetur adipiscing elit ut aliquam purus. Justo laoreet sit amet cursus sit amet dictum sit amet. Tempor orci eu lobortis elementum nibh tellus. Vulputate mi sit amet mauris commodo quis imperdiet massa. Augue eget arcu dictum varius duis at consectetur lorem. Aliquam faucibus purus in massa tempor nec. Id interdum velit laoreet id donec ultrices tincidunt arcu non. Sapien eget mi proin sed libero enim. Velit euismod in pellentesque massa placerat duis ultricies lacus sed. Commodo nulla facilisi nullam vehicula ipsum a arcu. Mi tempus imperdiet nulla malesuada. At ultrices mi tempus imperdiet nulla malesuada. Nulla posuere sollicitudin aliquam ultrices sagittis orci a. Pulvinar neque laoreet suspendisse interdum consectetur libero. Faucibus purus in massa tempor nec feugiat nisl pretium fusce. Magna sit amet purus gravida quis blandit turpis.",
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
