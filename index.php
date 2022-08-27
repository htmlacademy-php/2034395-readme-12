<?php
require_once 'requires_auth.php';

$data = $_POST;

function authUser($data, $link): bool
{
    $email = $data['email'] ?? null;
    $password = $data['password'] ?? null;

    $sql = "SELECT * FROM `users` u WHERE u.email = ?";

    $user = db_query_prepare_stmt($link, $sql, [$email], QUERY_ASSOC);

    if (!password_verify($password, $user[0]['password'] ?? '')) {
        return false;
    }

    $now = time();
    $expires = strtotime('+1 month', $now);

    setUserDataCookies($email, $user[0]['password'], $expires);

    return true;
}

if (isset($data['email'])) {
    $is_auth = authUser($data, $link);

    if ($is_auth) {
        header("Location: /popular.php?tab=all&page=1&sort=views");
        exit();
    }
}

$content = include_template('main.php');

print($content);
