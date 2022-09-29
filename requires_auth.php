<?php
require_once 'init.php';

/**
 * @var mysqli $link
 */

$user = false;

if (isset($_COOKIE['user_email']) && isset($_COOKIE['user_password'])) {
    $email = $_COOKIE['user_email'] ?? null;
    $password = $_COOKIE['user_password'] ?? null;

    $sql = "SELECT * FROM users WHERE email = ?";

    $user = db_query_prepare_stmt($link, $sql, [$email]);

    if (count($user) === 1) {
        $user = $user[0];

        if (!password_verify($user['password'], $password)) {
            header("Location: /");
            exit();
        }
    }
} else {
    header("Location: /");
    exit();
}
