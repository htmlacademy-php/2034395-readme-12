<?php
require_once 'requires_auth.php';

$data = $_POST;

function authUser($data, $link): bool {
    $email = $data['email'] ?? null;
    $password = $data['password'] ?? null;

    if (!$link) {
        $error = mysqli_connect_error();
        print($error);
        die();
    }

    $sql = "SELECT * FROM `users` u WHERE u.email = ?";

    $stmt = db_get_prepare_stmt($link, $sql, [$email]);

    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);

    $user = mysqli_fetch_assoc($result);

    if (!password_verify($password, $user['password'])) return false;

    $now = time();
    $expires = strtotime('+1 month', $now);

    setUserDataCookies($email, $user['password'], $expires);

    return true;
}

if (isset($data['email'])) {
    $is_auth = authUser($data, $link);

    if ($is_auth) {
        header("Location: /popular.php");
        exit();
    }
}

$content = include_template('main.php');

print($content);
