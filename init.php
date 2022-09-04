<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);

require_once 'vendor/autoload.php';
require_once 'dbHelpers.php';
$db = require_once 'db.php';

$link = mysqli_connect($db['host'], $db['user'], $db['password'], $db['database']);

if (!$link) {
    print(mysqli_connect_error());
    die();
}

mysqli_set_charset($link, "utf8mb4");

$user = [];
$email = $_COOKIE['user_email'] ?? '';
$password = $_COOKIE['user_password'] ?? '';

$sql = "SELECT * FROM users WHERE email = ?";

$user = db_query_prepare_stmt($link, $sql, [$email]);

if (count($user) === 1) {
    $user = $user[0];

    if (!password_verify($user['password'], $password)) {
        set_user_data_cookies("", "", time() - 3600);
        header("Location: /");
        exit();
    }
}
