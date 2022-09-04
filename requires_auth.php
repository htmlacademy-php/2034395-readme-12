<?php
require_once 'init.php';

/**
 * @var array|null $user
 */

if ($user) {
    header("Location: /popular.php?tab=all&page=1&sort=views");
    exit();
}
