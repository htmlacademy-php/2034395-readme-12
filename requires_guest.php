<?php
require_once 'init.php';

/**
 * @var array|null $user
 */

if (!$user) {
    header("Location: /");
    exit();
}
