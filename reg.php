<?php
require_once 'helpers.php';
require_once 'init.php';

$data = $_POST;

$reg_data = ['errors' => []];

function validateEmail($email, $link): array|bool {
    if (!$link) {
        $error = mysqli_connect_error();
        print($error);
        die();
    }

    $sql = "SELECT * FROM `users` u WHERE u.email = '$email'";

    $result = mysqli_query($link, $sql);

    if ($result === false) {
        print_r("Ошибка выполнения запроса: " . mysqli_error($link));
        die();
    }

    $isEmailUsed = count(mysqli_fetch_all($result, MYSQLI_ASSOC)) > 0;

    if ($isEmailUsed) return ['target' => 'email', 'text' => 'Указанный адрес электронной почты уже зарегистрирован.'];

    return false;
}

function validateData($data, $link) {
    $files_path = __DIR__ . '/uploads/';

    $errors = [];

    $login = $data['login'] ?? null;
    $email = $data['email'] ?? null;
    $password = $data['password'] ?? null;
    $re_password = $data['password-repeat'] ?? null;
    $file = $_FILES['userpic-file'] ?? null;

    if (strlen($login) == 0) $errors[] = ['target' => 'login', 'text' => 'Придумайте логин.'];
    if (strlen($email) == 0) $errors[] = ['target' => 'login', 'text' => 'Укажите адрес своей электронной почты.'];
    if (validateEmail($email, $link)) $errors[] = validateEmail($email, $link);
    if (strlen($password) == 0) $errors[] = ['target' => 'password', 'text' => 'Придумайте пароль.'];
    if (strlen($re_password) == 0) $errors[] = ['target' => 'password-repeat', 'text' => 'Повторите придуманный пароль.'];
    if ($password != $re_password) $errors[] = ['password-repeat', 'text' => 'Пароли не совпадают.'];
    if ($file['name'] && validateFile($file, $files_path)) $errors[] = validateFile($file, $files_path);

    return [
        "data" => [
            "email" => $email,
            "login" => $login,
            "password" => password_hash($password, PASSWORD_DEFAULT),
        ],
        "errors" => $errors
    ];
}

function registerUser($link, $data) {
    if (!$link) {
        $error = mysqli_connect_error();
        print($error);
        die();
    }

    $sql = "INSERT INTO `users` (`email`, `login`, `password`)" .
        " VALUES (?, ?, ?)";

    $stmt = db_get_prepare_stmt($link, $sql, $data['data']);

    mysqli_stmt_execute($stmt);
    mysqli_stmt_close($stmt);
}

if (count($data) > 0) {
    $reg_data = validateData($data, $link);

    if (count($reg_data['errors']) == 0) {
        registerUser($link, $reg_data);
        header("Location: /");
        exit();
    }
}

$content = include_template('registration.php', ["errors" => $reg_data['errors']]);
$layout = include_template('layout.php', [
    "content" => $content,
    "title" => "readme: регистрация",
    "user_name" => "Kirill",
    "is_auth" => $is_auth,
]);

print($layout);
