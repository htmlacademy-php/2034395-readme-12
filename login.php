<?php
require_once 'init.php';

$data = $_POST;

if (!empty($data['email']) && !empty($data['password'])) {
    $email = $data['email'];
    $password = password_hash($data['password'], PASSWORD_DEFAULT);
    $expires = strtotime('+1 month', time());

    set_user_data_cookies($email, $password, $expires);
}

header("Location: /");
exit();
