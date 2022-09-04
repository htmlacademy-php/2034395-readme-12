<?php

/**
 * Привязывает к подготовленному выражению данные для корректного заполнения запроса
 *
 * @param mysqli_stmt $statement Подготовленное выражение
 * @param array $data Данные для заполнения запроса
 *
 * @return bool Были ли привязаны данные для заполнения запроса
 */
function bind_statement_params(mysqli_stmt $statement, array $data = []): bool
{
    $types = '';
    $statement_data = [];

    foreach ($data as $value) {
        $type = 's';

        if (is_int($value)) {
            $type = 'i';
        } else {
            if (is_double($value)) {
                $type = 'd';
            }
        }

        $types .= $type;
        $statement_data[] = $value;
    }

    $values = array_merge([$statement, $types], $statement_data);

    return mysqli_stmt_bind_param(...$values);
}

/**
 * Создает подготовленное выражение на основе готового SQL запроса и переданных данных
 *
 * @param mysqli $link Ресурс соединения
 * @param string $sql SQL запрос
 * @param array $data Данные для заполнения запроса
 *
 * @return mysqli_stmt Подготовленное выражение
 */
function db_get_prepare_stmt(mysqli $link, string $sql, array $data = []): mysqli_stmt
{
    $statement = mysqli_prepare($link, $sql);

    if (!$statement) {
        $errorMessage = 'Не удалось инициализировать подготовленное выражение: ' . mysqli_error($link);
        die($errorMessage);
    }

    if ($data) {
        bind_statement_params($statement, $data);
    }

    return $statement;
}

/**
 * Формирует подготовленное выражение из запроса и предоставленных данных, отправляет его и, если, запрос успешно
 * обработан, то возвращает массив с данными, либо false. Массив с данными возвращается для запросов, ответ на которые
 * их предполагает (по типу SELECT). False возвращается в случае INSERT и подобных запросов.
 * Также, для проваленных запросов, возвращается false.
 *
 * @param mysqli $link Ресурс соединения
 * @param string $sql SQL запрос
 * @param array $data Данные для заполнения запроса
 *
 * @return array|false В случае успеха возвращает массив или false. В случае провала всегда возвращает false.
 */
function db_query_prepare_stmt(mysqli $link, string $sql, array $data = []): array|false
{
    $statement = db_get_prepare_stmt($link, $sql, $data);

    mysqli_stmt_execute($statement);

    $result = mysqli_stmt_get_result($statement);

    mysqli_stmt_close($statement);

    if (!$result) {
        return $result;
    }

    return mysqli_fetch_all($result, MYSQLI_ASSOC);
}

/**
 * Устанавливает клиенту информацию о его почте и зашифрованном пароле в куки для дальнейшего упрощенного входа
 *
 * @param $email
 * @param $password
 * @param $expires
 *
 * @return void
 */
function set_user_data_cookies($email, $password, $expires): void
{
    setcookie('user_email', $email, $expires);
    setcookie('user_password', $password, $expires);
}

function get_user_data($link, $type, $var): array
{
    $sql = "SELECT * FROM users u WHERE u.id = ?";

    if ($type === 'email') {
        $sql = "SELECT * FROM users u WHERE u.email = ?";
    }

    return db_query_prepare_stmt($link, $sql, [$var]) ?? [];
}

function get_subscriptions($link, $id): array
{
    $sql = "SELECT * FROM subscriptions s WHERE s.user = ?";

    $result = db_query_prepare_stmt($link, $sql, [$id]);

    return $result ?? [];
}

function check_is_user_subscribed($link, $user, $author): array|false
{
    $sql = "SELECT * FROM subscriptions s WHERE s.subscriber = ? AND s.user = ?";

    return db_query_prepare_stmt($link, $sql, [$user, $author]);
}

function get_post_likes($link, $post): array|false
{
    $sql = "SELECT * FROM likes l WHERE l.post = ?";

    return db_query_prepare_stmt($link, $sql, [$post]);
}

function is_post_liked($link, $user, $post): array|false
{
    $sql = "SELECT l.id FROM likes l WHERE l.post = ? AND l.user = ?";

    return db_query_prepare_stmt($link, $sql, [$post, $user]);
}

function get_comments($link, $id): array
{
    $sql = " SELECT * FROM comments c"
        . " JOIN users u ON c.author = u.id"
        . " WHERE c.post = ?"
        . " ORDER BY c.id DESC";

    return db_query_prepare_stmt($link, $sql, [$id]);
}
