<?php
require_once 'init.php';

if ($is_auth) {
    header("Location: /popular.php?tab=all&page=1&sort=views");
    exit();
}
