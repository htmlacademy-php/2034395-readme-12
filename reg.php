<?php
require_once 'requires_auth.php';

$data = $_POST;

$reg_data = ['errors' => []];

function validateEmail($email, $link): ?array
{
    $sql = "SELECT * FROM `users` u WHERE u.email = ?";

    $result = db_query_prepare_stmt($link, $sql, [$email], QUERY_ASSOC);

    $isEmailUsed = count($result) > 0;

    if ($isEmailUsed) {
        return ['target' => 'email', 'text' => 'Указанный адрес электронной почты уже зарегистрирован.'];
    }

    return null;
}

function validateData($data, $link)
{
    $files_path = __DIR__ . '/uploads/';

    $errors = [];

    $login = $data['login'] ?? null;
    $email = $data['email'] ?? null;
    $password = $data['password'] ?? null;
    $re_password = $data['password-repeat'] ?? null;
    $file = $_FILES['userpic-file'] ?? null;

    if (strlen($login) == 0) {
        $errors[] = ['target' => 'login', 'text' => 'Придумайте логин.'];
    }
    if (strlen($email) == 0) {
        $errors[] = ['target' => 'login', 'text' => 'Укажите адрес своей электронной почты.'];
    }
    if (validateEmail($email, $link)) {
        $errors[] = validateEmail($email, $link);
    }
    if (strlen($password) == 0) {
        $errors[] = ['target' => 'password', 'text' => 'Придумайте пароль.'];
    }
    if (strlen($re_password) == 0) {
        $errors[] = ['target' => 'password-repeat', 'text' => 'Повторите придуманный пароль.'];
    }
    if ($password != $re_password) {
        $errors[] = ['password-repeat', 'text' => 'Пароли не совпадают.'];
    }
    if ($file['name'] && validateFile($file, $files_path)) {
        $errors[] = validateFile($file, $files_path);
    }

    return [
        "data" => [
            "email" => $email,
            "login" => $login,
            "password" => password_hash($password, PASSWORD_DEFAULT),
        ],
        "errors" => $errors
    ];
}

function registerUser($link, $data)
{
    $sql = "INSERT INTO `users` (`email`, `login`, `password`, `registration_date`) VALUES (?, ?, ?, NOW())";

    return db_query_prepare_stmt($link, $sql, $data['data'], QUERY_EXECUTE);
}

if (count($data) > 0) {
    $reg_data = validateData($data, $link);

    if (count($reg_data['errors']) == 0) {
        registerUser($link, $reg_data);

        $now = time();
        $expires = strtotime('+1 month', $now);

        setUserDataCookies($reg_data['data']['email'], $reg_data['data']['password'], $expires);
        header("Location: /");
        exit();
    }
}

$content = include_template('registration.php', ["errors" => $reg_data['errors']]);
$layout = include_template('layout.php', [
    "content" => $content,
    "title" => "readme: регистрация",
    "user" => $user,
    "is_auth" => $is_auth,
]);

print($layout);
