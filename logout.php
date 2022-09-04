<?php
require_once 'init.php';

set_user_data_cookies("", "", time() - 3600);

header("Location: /");
exit();
