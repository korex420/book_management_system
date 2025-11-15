<?php
require_once 'config/config.php';
require_once 'includes/database.php';
require_once 'includes/functions.php';
require_once 'includes/auth.php';

// Require login for deleting books
$auth->requireLogin();

$db = new Database();

// Get book ID from URL
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    set_flash_message('danger', 'Invalid book ID');
    redirect('index.php');
}

$book_id = (int)$_GET['id'];

// Check if book exists
$db->query("SELECT * FROM books WHERE id = :id");
$db->bind(':id', $book_id);
$book = $db->single();

if (!$book) {
    set_flash_message('danger', 'Book not found');
    redirect('index.php');
}

// Delete the book
$db->query("DELETE FROM books WHERE id = :id");
$db->bind(':id', $book_id);

if ($db->execute()) {
    set_flash_message('success', 'Book deleted successfully!');
} else {
    set_flash_message('danger', 'Failed to delete book. Please try again.');
}

redirect('index.php');
?>