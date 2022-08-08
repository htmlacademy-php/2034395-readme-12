<?php
require_once 'init.php';

if (!$is_auth) {
    header("Location: /");
    exit();
}
