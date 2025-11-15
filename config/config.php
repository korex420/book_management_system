<?php
// Database configuration
define('DB_HOST', 'localhost');
define('DB_NAME', 'book_management');
define('DB_USER', 'root');
define('DB_PASS', '');

// Application settings
define('BASE_URL', 'http://localhost/book_management_system/');
define('SITE_NAME', 'Book Management System');

// Error reporting (disable in production)
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Session configuration
session_start();
?>