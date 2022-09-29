<?php
require_once 'init.php';
require_once 'helpers.php';

/**
 * @var mysqli $link
 */

$email = $_COOKIE['user_email'] ?? null;

$sql = "SELECT * FROM users WHERE email = ?";

$user = db_query_prepare_stmt($link, $sql, [$email]);

if ($user) {
    header("Location: /popular.php?tab=all&page=1&sort=views");
    exit();
}

$data = $_POST;

if (!empty($data)) {
    $reg_data = validate_registration_data($link, $data);

    $sql = "INSERT INTO users (email, login, password, registration_date) VALUES (?, ?, ?, NOW())";

    if (count($reg_data['errors']) === 0 && db_query_prepare_stmt($link, $sql, $data['data'])) {
        $expires = strtotime('+1 month', time());

        set_user_data_cookies(
            $reg_data['data']['email'],
            password_hash($reg_data['data']['password'], PASSWORD_DEFAULT),
            $expires
        );

        header("Location: /popular.php?tab=all&page=1&sort=views");
        exit();
    }
}

$content = include_template('registration.php', ["errors" => $reg_data['errors'] ?? []]);
$layout = include_template('layout.php', [
    "content" => $content,
    "title" => "readme: регистрация",
    "user" => $user,
]);

print($layout);
