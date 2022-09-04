<?php
require_once 'requires_auth.php';
require_once 'helpers.php';

/**
 * @var mysqli $link
 * @var array $user
 */

$data = $_POST;

$reg_data = [];

function validateEmail(mysqli $link, string $email): array|false
{
    $sql = "SELECT * FROM users u WHERE u.email = ?";

    $result = db_query_prepare_stmt($link, $sql, [$email]);

    $isEmailUsed = count($result) > 0;

    if ($isEmailUsed) {
        return ['target' => 'email', 'text' => 'Указанный адрес электронной почты уже зарегистрирован.'];
    }

    return false;
}

function validateData(mysqli $link, array $data): array
{
    $files_path = __DIR__ . '/uploads/';

    $errors = [];

    $login = $data['login'] ?? null;
    $email = $data['email'] ?? null;
    $password = $data['password'] ?? null;
    $re_password = $data['password-repeat'] ?? null;
    $file = $_FILES['userpic-file'] ?? null;

    if (strlen($login) === 0) {
        $errors[] = ['target' => 'login', 'text' => 'Придумайте логин.'];
    }
    if (strlen($email) === 0) {
        $errors[] = ['target' => 'login', 'text' => 'Укажите адрес своей электронной почты.'];
    }
    if (validateEmail($link, $email)) {
        $errors[] = validateEmail($link, $email);
    }
    if (strlen($password) === 0) {
        $errors[] = ['target' => 'password', 'text' => 'Придумайте пароль.'];
    }
    if (strlen($re_password) === 0) {
        $errors[] = ['target' => 'password-repeat', 'text' => 'Повторите придуманный пароль.'];
    }
    if ($password != $re_password) {
        $errors[] = ['password-repeat', 'text' => 'Пароли не совпадают.'];
    }
    if ($file['name'] && validate_file($file, $files_path)) {
        $errors[] = validate_file($file, $files_path);
    }

    return [
        "data" => [
            "email" => $email,
            "login" => $login,
            "password" => $password,
        ],
        "errors" => $errors
    ];
}

function registerUser(mysqli $link, array $data): array|false
{
    $sql = "INSERT INTO users (email, login, password, registration_date) VALUES (?, ?, ?, NOW())";

    return db_query_prepare_stmt($link, $sql, $data['data']);
}

if (!empty($data)) {
    $reg_data = validateData($link, $data);

    if (count($reg_data['errors']) === 0) {
        registerUser($link, $reg_data);

        $expires = strtotime('+1 month', time());

        set_user_data_cookies($reg_data['data']['email'], $reg_data['data']['password'], $expires);
        header("Location: /");
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
