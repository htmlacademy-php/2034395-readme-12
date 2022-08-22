<?php
require_once 'vendor/autoload.php';
require_once 'helpers.php';
$db = require_once 'db.php';

$link = mysqli_connect($db['host'], $db['user'], $db['password'], $db['database']);
mysqli_set_charset($link, "utf8mb4");

$user = null;

$is_auth = false;

if (isset($_COOKIE['user_email']) && isset($_COOKIE['user_password'])) {
    $email = $_COOKIE['user_email'];
    $password = $_COOKIE['user_password'];

    $user = getUserData($link, 'email', $email);

    $is_auth = $user['password'] == $password;
}
