<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);

require_once 'vendor/autoload.php';
require_once 'helpers.php';
$db = require_once 'db.php';

$link = mysqli_connect($db['host'], $db['user'], $db['password'], $db['database']); //fixme link might be null
if (!$link) {
    $error = mysqli_connect_error();
    print($error);
    die();
}
mysqli_set_charset($link, "utf8mb4");

$user = null;
$is_auth = false;

if (isset($_COOKIE['user_email'], $_COOKIE['user_password'])) {
    $email = $_COOKIE['user_email'] ?? null;
    $password = $_COOKIE['user_password'] ?? null;

    $user = getUserData($link, 'email', $email);

    $is_auth = $user['password'] === $password; // fixme password_verify / token
}
