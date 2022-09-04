<?php

use Symfony\Component\Mailer\Mailer;
use Symfony\Component\Mailer\Transport;
use Symfony\Component\Mailer\Exception\TransportExceptionInterface;
use Symfony\Component\Mime\Email;

const EMAIL_MESSAGE_PRESET = [
    'subject' => 'message title text',
    'content' => '<p>new message</p>'
];
const EMAIL_SUB_PRESET = [
    'subject' => 'subscription title text',
    'content' => '<p>new subscriber</p>'
];

/**
 * Возвращает корректную форму множественного числа
 * Ограничения: только для целых чисел
 *
 * Пример использования:
 * $remaining_minutes = 5;
 * echo "Я поставил таймер на {$remaining_minutes} " .
 *     get_noun_plural_form(
 *         $remaining_minutes,
 *         'минута',
 *         'минуты',
 *         'минут'
 *     );
 * Результат: "Я поставил таймер на 5 минут"
 *
 * @param int $number Число, по которому вычисляем форму множественного числа
 * @param string $one Форма единственного числа: яблоко, час, минута
 * @param string $two Форма множественного числа для 2, 3, 4: яблока, часа, минуты
 * @param string $many Форма множественного числа для остальных чисел
 *
 * @return string Рассчитанная форма множественного числа
 */
function get_noun_plural_form(int $number, string $one, string $two, string $many): string
{
    $mod10 = $number % 10;

    return match (true) {
        $mod10 === 1 => $one,
        $mod10 >= 2 && $mod10 <= 4 => $two,
        default => $many,
    };
}

/**
 * Подключает шаблон, передает туда данные и возвращает итоговый HTML контент
 *
 * @param string $name Путь к файлу шаблона относительно папки templates
 * @param array $data Ассоциативный массив с данными для шаблона
 *
 * @return string|false Итоговый HTML
 */
function include_template(string $name, array $data = []): string|false
{
    $name = 'templates/' . $name;
    $result = '';

    if (!is_readable($name)) {
        return $result;
    }

    ob_start();
    extract($data);
    require $name;

    return ob_get_clean();
}

/**
 * Функция проверяет доступно ли видео по ссылке на youtube
 *
 * @param string $url Ссылка на видео
 *
 * @return bool Ошибку если валидация не прошла
 */
function check_youtube_url(string $url): bool
{
    $id = extract_youtube_id($url);

    set_error_handler(function () {
    }, E_WARNING);
    $headers = get_headers('https://www.youtube.com/oembed?format=json&url=https://www.youtube.com/watch?v=' . $id);
    restore_error_handler();

    if (!is_array($headers)) {
        return false;
    }

    $err_flag = strpos($headers[0], '200') ? 200 : 404;

    return $err_flag === 200;
}

/**
 * Возвращает код iframe для вставки youtube видео на страницу
 *
 * @param string $youtube_url Ссылка на youtube видео
 *
 * @return string
 */
function embed_youtube_video(string $youtube_url): string
{
    $res = "";
    $id = extract_youtube_id($youtube_url);

    if ($id) {
        $src = "https://www.youtube.com/embed/" . $id;
        $res = '<iframe width="760" height="400" src="' . $src . '"></iframe>';
    }

    return $res;
}

/**
 * Возвращает img-тег с обложкой видео для вставки на страницу
 * @param string $youtube_url Ссылка на youtube видео
 *
 * @return string
 */
function embed_youtube_cover(string $youtube_url): string
{
    $res = "";
    $id = extract_youtube_id($youtube_url);

    if ($id) {
        $src = sprintf("https://img.youtube.com/vi/%s/mqdefault.jpg", $id);
        $res = '<img alt="youtube cover" width="320" height="120" src="' . $src . '" />';
    }

    return $res;
}

/**
 * Извлекает из ссылки на youtube видео его уникальный ID
 *
 * @param string $youtube_url Ссылка на youtube видео
 *
 * @return string
 */
function extract_youtube_id(string $youtube_url): string
{
    $parts = parse_url($youtube_url);

    if ($parts['path'] === '/watch') {
        parse_str($parts['query'], $vars);
        return $vars['v'] ?? '';
    }

    return substr($parts['path'], 1);
}

/**
 * Генерирует случайную дату
 *
 * @param int $index
 *
 * @return string
 */
function generate_random_date(int $index): string
{
    $deltas = [['minutes' => 59], ['hours' => 23], ['days' => 6], ['weeks' => 4], ['months' => 11]];
    $deltas_count = count($deltas);

    if ($index < 0) {
        $index = 0;
    }

    if ($index >= $deltas_count) {
        $index = $deltas_count - 1;
    }

    $delta = $deltas[$index];
    $time_val = rand(1, current($delta));
    $time_name = key($delta);

    $timestamp = strtotime("$time_val $time_name ago");

    return date('Y-m-d H:i:s', $timestamp);
}

/**
 * Отправляет уведомление на почту
 *
 * @param array $sender
 * @param array $recipient
 * @param string $subject
 * @param string $body
 *
 * @return void
 */
function sendEmailNotify(array $sender, array $recipient, string $subject, string $body): void
{
    // fixme параметры лучше вынести в отдельный метод инициализации, подгружать из конфига
    $transport = Transport::fromDsn('smtp://parismay.frontend@mail.ru:psswd@smtp.mail.ru:465');
    $mailer = new Mailer($transport);

    $email = (new Email())
        ->from('parismay.frontend@mail.ru')
        ->to($recipient['email'])
        ->subject($subject)
        ->text($sender['login'] . ' ' . $body);

    try {
        $mailer->send($email);
    } catch (TransportExceptionInterface $e) {
        echo("<div class='error'>" . $e->getMessage() . "</div>");
    }
}
