<?php
require_once 'init.php';

setUserDataCookies("", "", time() - 3600);

header("Location: /index.php");
exit();
