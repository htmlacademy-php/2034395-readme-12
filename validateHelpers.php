<?php

/**
 * Проверяет переданную дату на соответствие формату 'ГГГГ-ММ-ДД'
 *
 * Примеры использования:
 * is_date_valid('2019-01-01'); // true
 * is_date_valid('2016-02-29'); // true
 * is_date_valid('2019-04-31'); // false
 * is_date_valid('10.10.2010'); // false
 * is_date_valid('10/10/2010'); // false
 *
 * @param string $date Дата в виде строки
 *
 * @return bool True при совпадении с форматом 'ГГГГ-ММ-ДД', иначе false
 */
function is_date_valid(string $date): bool
{
    $format_to_check = 'Y-m-d';
    $dateTimeObj = date_create_from_format($format_to_check, $date);

    return $dateTimeObj !== false && array_sum(date_get_last_errors()) === 0;
}

/**
 * Проверяет переданную информацию о файле и перемещает его из временного хранилища
 *
 * @param array $file
 * @param string $path
 *
 * @return array|bool
 */
function validate_file(array $file, string $path): array|bool
{
    if (!$file['name']) {
        return ['target' => 'file', 'text' => 'Прикрепите или укажите ссылку на изображение.'];
    }

    $mime = $file['type'];
    $name = $file['name'];
    $tmp_name = $file['tmp_name'];

    if ($mime != 'image/gif' && $mime != 'image/jpeg' && $mime != 'image/png') {
        return [
            'target' => 'file',
            'text' => 'Вы можете загрузить файлы только в следующих форматах: .png, .jpeg, .gif.'
        ];
    }

    move_uploaded_file($tmp_name, $path . $name);
    return false;
}

/**
 * Возвращает сокращенный текст, если его длина более 300 (по стандарту) символов
 *
 * @param string $text
 * @param int $maxSymbols
 *
 * @return array
 */
function show_data(string $text, int $maxSymbols = 300): array
{
    $array = explode(' ', $text);
    $result = [
        'text' => null,
        'isLong' => 0
    ];

    $symbols = 0;

    foreach ($array as $word) {
        $symbols += strlen($word);

        if ($symbols < $maxSymbols) {
            $result['text'] .= ' ' . $word;
        } else {
            $result['text'] .= '...';
            $result['isLong'] = 1;
            break;
        }
    }

    return $result;
}

/**
 * Возвращает корректную дату
 *
 * @param string $date
 *
 * @return string
 */
function normalize_date(string $date): string
{
    $postUnix = strtotime($date);
    $interval = floor((time() - $postUnix) / 60);
    $type = "";
    $types = [
        "minutes" => ["минуту", "минуты", "минут"],
        "hours" => ["час", "часа", "часов"],
        "days" => ["день", "дня", "дней"],
        "weeks" => ["неделю", "недели", "недель"],
        "months" => ["месяц", "месяца", "месяцев"],
        "years" => ["год", "года", "лет"]
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
    } else if ($interval / 60 / 24 / 7 / 5 < 12) {
        $type = "months";
        $interval = floor($interval / 60 / 24 / 7 / 5);
    } else {
        $type = "years";
        $interval = floor($interval / 60 / 24 / 7 / 5 / 12);
    }

    $correctWord = get_noun_plural_form($interval, $types[$type][0], $types[$type][1], $types[$type][2]);

    return "$interval $correctWord";
}
