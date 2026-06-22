<?php
require_once 'config/database.php';
setcookie('user_id', '', time() - 3600, '/');
setcookie('nama', '', time() - 3600, '/');
setcookie('role', '', time() - 3600, '/');
redirect('index.php');
?>
