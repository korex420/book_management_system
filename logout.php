<?php
require_once 'config/config.php';
require_once 'includes/functions.php';
require_once 'includes/auth.php';

$auth->logout();
set_flash_message('success', 'You have been logged out successfully.');
redirect('index.php');
?>