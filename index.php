<?php
require_once 'init.php';
require_once 'helpers.php';

/**
 * @var array $link
 */

$isEmailWrong = false;
$isPasswordWrong = false;

$sql = "SELECT * FROM users WHERE email = ?";

$email = $_SERVER['REQUEST_METHOD'] === 'POST' ?
    $_POST['email'] ?? null :
    $_COOKIE['user_email'] ?? null;

$password = $_SERVER['REQUEST_METHOD'] === 'POST' ?
    $_POST['password'] ?? null :
    $_COOKIE['user_password'] ?? null;

$user = db_query_prepare_stmt($link, $sql, [$email]);

if (count($user) === 1) {
    $user = $user[0];
}

if (!empty($user)) {
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        if ($password === $user['password']) {
            $expires = strtotime('+1 month', time());

            set_user_data_cookies($email, password_hash($password, PASSWORD_DEFAULT), $expires);
            header("Location: /popular.php?tab=all&page=1&sort=views");
            exit();
        } else {
            $isPasswordWrong = true;
        }
    } else {
        if (password_verify($user['password'], $password)) {
            header("Location: /popular.php?tab=all&page=1&sort=views");
            exit();
        } else {
            set_user_data_cookies("", "", time() - 3600);
        }
    }
} else if ($email) {
    $isEmailWrong = true;
}

$content = include_template('main.php', [
    'isEmailWrong' => $isEmailWrong,
    'isPasswordWrong' => $isPasswordWrong,
]);

print($content);
