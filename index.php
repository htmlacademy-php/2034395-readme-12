<?php
require_once 'requires_auth.php';
require_once 'helpers.php';

/**
 * @var array $user
 */

if ($user) {
    header("Location: /popular.php?tab=all&page=1&sort=views");
    exit();
}

$content = include_template('main.php');

print($content);
