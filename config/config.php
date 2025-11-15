<?php
// School server database configuration
define('DB_HOST', 'localhost');
define('DB_NAME', '2370207_book_management'); // School server format
define('DB_USER', '2370207');
define('DB_PASS', ''); // Your school MySQL password

// School server URL
define('BASE_URL', 'http://mi-linux.wlv.ac.uk/~2370207/book_management_system/');
define('SITE_NAME', 'Book Management System');

// Error reporting (might need to disable on school server)
error_reporting(E_ALL);
ini_set('display_errors', 1);

session_start();
?>