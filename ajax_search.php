<?php
require_once 'config/config.php';
require_once 'includes/database.php';
require_once 'includes/functions.php';
require_once 'includes/auth.php';

$db = new Database();

$query = sanitize_input($_GET['query'] ?? '');

if (!empty($query)) {
    $db->query("SELECT * FROM books WHERE title LIKE :query OR author LIKE :query OR genre LIKE :query ORDER BY title LIMIT 20");
    $db->bind(':query', "%$query%");
    $books = $db->resultSet();
} else {
    $db->query("SELECT * FROM books ORDER BY created_at DESC LIMIT 20");
    $books = $db->resultSet();
}

if (empty($books)) {
    echo '<div class="alert alert-info">No books found.</div>';
} else {
    foreach ($books as $book) {
        echo '
        <div class="card mb-2">
            <div class="card-body">
                <h5 class="card-title">' . htmlspecialchars($book['title']) . '</h5>
                <h6 class="card-subtitle mb-2 text-muted">' . htmlspecialchars($book['author']) . ' (' . $book['publication_year'] . ')</h6>
                <p class="card-text">
                    <span class="badge bg-primary">' . htmlspecialchars($book['genre']) . '</span>
                    <span class="badge bg-success">£' . number_format($book['price'], 2) . '</span>
                </p>
                <div>
                    <a href="view_book.php?id=' . $book['id'] . '" class="btn btn-sm btn-info">View</a>' . 
                    ($auth->isLoggedIn() ? '
                    <a href="edit_book.php?id=' . $book['id'] . '" class="btn btn-sm btn-warning">Edit</a>
                    <a href="delete_book.php?id=' . $book['id'] . '" class="btn btn-sm btn-danger" onclick="return confirm(\'Are you sure?\')">Delete</a>' : '') . '
                </div>
            </div>
        </div>';
    }
}
?>