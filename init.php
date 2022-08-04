<?php
require_once 'helpers.php';
$db = require_once 'db.php';

$link = mysqli_connect($db['host'], $db['user'], $db['password'], $db['database']);
mysqli_set_charset($link, "utf8mb4");

$user = null;

$is_auth = false;

if (isset($_COOKIE['user_email']) && isset($_COOKIE['user_password'])) {
    $email = $_COOKIE['user_email'];
    $password = $_COOKIE['user_password'];

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

    $is_auth = $user['password'] == $password;
}
