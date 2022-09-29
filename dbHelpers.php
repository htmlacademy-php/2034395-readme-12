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

/**
 * Возвращает информацию о пользователе
 *
 * @param $link
 * @param $type
 * @param $var
 *
 * @return array
 */
function get_user_data($link, $type, $var): array
{
    $sql = "SELECT * FROM users u WHERE u.id = ?";

    if ($type === 'email') {
        $sql = "SELECT * FROM users u WHERE u.email = ?";
    }

    return db_query_prepare_stmt($link, $sql, [$var]) ?? [];
}

/**
 * Возвращает всех пользователей, которые на него подписаны
 *
 * @param $link
 * @param $id
 *
 * @return array
 */
function get_subscriptions($link, $id): array
{
    $sql = "SELECT * FROM subscriptions s WHERE s.user = ?";

    $result = db_query_prepare_stmt($link, $sql, [$id]);

    return $result ?? [];
}

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

/**
 * Проверяет, просматривал ли пользователь пост
 *
 * @param mysqli $link
 * @param int $user_id
 * @param int $post_id
 *
 * @return bool
 */
function check_is_user_view_post(mysqli $link, int $user_id, int $post_id): bool
{
    $sql = "SELECT * FROM views"
        . " WHERE post_id = ? AND user_id = ?";

    $result = db_query_prepare_stmt($link, $sql, [$post_id, $user_id]);

    return (bool)$result;
}

/**
 * Добавляет посту просмотр пользователя
 *
 * @param mysqli $link
 * @param int $user_id
 * @param int $post_id
 *
 * @return array|false
 */
function add_post_view(mysqli $link, int $user_id, int $post_id): array|false
{
    $isUserViewPost = check_is_user_view_post($link, $user_id, $post_id);

    if ($isUserViewPost) {
        return false;
    }

    $sql = "INSERT INTO views (post_id, user_id) VALUES (?, ?)";

    return db_query_prepare_stmt($link, $sql, [$post_id, $user_id]);
}

/**
 * Возвращает массив с данными о просмотре поста
 *
 * @param mysqli $link
 * @param int $post_id
 *
 * @return array|false
 */
function get_post_views(mysqli $link, int $post_id): array|false
{
    $sql = "SELECT COUNT(*) views FROM views"
        . " WHERE post_id = ?";

    return db_query_prepare_stmt($link, $sql, [$post_id]);
}

/**
 * Добавляет комментарий к посту
 *
 * @param mysqli $link
 * @param string $text
 * @param int $post_id
 * @param int $author_id
 *
 * @return array|false
 */
function add_comment(mysqli $link, string $text, int $post_id, int $author_id): array|false
{
    $sql = "INSERT INTO comments (date, content, author, post) VALUES(NOW(), ?, ?, ?)";

    return db_query_prepare_stmt($link, $sql, [$text, $post_id, $author_id]);
}

/**
 * Возвращает пост по его идентификатору, если пост не найден, то направляет на страницу по ошибке 404.
 *
 * @param mysqli $link
 * @param int $post_id
 *
 * @return array|false
 */
function get_post_by_id(mysqli $link, int $post_id): array|false
{
    $sql = "SELECT p.*, ct.name, ct.class_name FROM posts p" .
        " JOIN content_types ct ON p.content_type = ct.id" .
        " WHERE p.id = ?";

    $post = db_query_prepare_stmt($link, $sql, [$post_id]);

    if (!$post) {
        http_response_code(404);
        die();
    }

    return $post;
}

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
 * Проверяет, подписан ли пользователь на другого пользователя
 *
 * @param $link
 * @param $user
 * @param $author
 *
 * @return array|false
 */
function check_is_user_subscribed($link, $user, $author): array|false
{
    $sql = "SELECT * FROM subscriptions s WHERE s.subscriber = ? AND s.user = ?";

    return db_query_prepare_stmt($link, $sql, [$user, $author]);
}

/**
 * Возвращает все лайки, поставленные посту
 *
 * @param $link
 * @param $post
 *
 * @return array|false
 */
function get_post_likes($link, $post): array|false
{
    $sql = "SELECT * FROM likes l WHERE l.post = ?";

    return db_query_prepare_stmt($link, $sql, [$post]);
}

/**
 * Проверяет, был ли лайкнут пост
 *
 * @param $link
 * @param $user
 * @param $post
 *
 * @return array|false
 */
function is_post_liked($link, $user, $post): array|false
{
    $sql = "SELECT l.id FROM likes l WHERE l.post = ? AND l.user = ?";

    return db_query_prepare_stmt($link, $sql, [$post, $user]);
}

/**
 * Возвращает комментарии к посту
 *
 * @param $link
 * @param $id
 *
 * @return array
 */
function get_comments($link, $id): array
{
    $sql = " SELECT * FROM comments c"
        . " JOIN users u ON c.author = u.id"
        . " WHERE c.post = ?"
        . " ORDER BY c.id DESC";

    return db_query_prepare_stmt($link, $sql, [$id]);
}
