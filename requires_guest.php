<?php
require_once 'init.php';
/**
 * @var bool $is_auth
 * @var array|null $user
 */

if (!$is_auth || $user === null) {
    header("Location: /");
    exit();
}
