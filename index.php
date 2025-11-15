<?php
require_once 'config/config.php';
require_once 'includes/database.php';
require_once 'includes/functions.php';
require_once 'includes/auth.php';

$db = new Database();
$search_query = '';

// Handle search if provided
if (isset($_GET['search']) && !empty($_GET['search'])) {
    $search_query = sanitize_input($_GET['search']);
    $db->query("SELECT * FROM books WHERE title LIKE :search OR author LIKE :search OR genre LIKE :search ORDER BY title");
    $db->bind(':search', "%$search_query%");
} else {
    $db->query("SELECT * FROM books ORDER BY created_at DESC");
}

$books = $db->resultSet();

$title = 'Book Management System - Home';
require_once 'templates/header.tpl.php';
require_once 'templates/book_list.tpl.php';
?>

<div class="row mb-4">
    <div class="col-md-8">
        <h1>Book Collection</h1>
        <p class="text-muted">Manage your book library with ease.</p>
    </div>
    <div class="col-md-4">
        <form method="GET" class="d-flex">
            <input type="text" name="search" class="form-control me-2" placeholder="Search books..." value="<?php echo $search_query; ?>">
            <button type="submit" class="btn btn-primary">Search</button>
        </form>
    </div>
</div>

<?php displayBookList($books, $auth->isLoggedIn()); ?>

<div class="mt-4">
    <div class="row">
        <div class="col-md-4">
            <div class="card text-center">
                <div class="card-body">
                    <h5 class="card-title">Total Books</h5>
                    <h2 class="text-primary"><?php echo count($books); ?></h2>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card text-center">
                <div class="card-body">
                    <h5 class="card-title">Genres</h5>
                    <h2 class="text-success">
                        <?php 
                        $db->query("SELECT COUNT(DISTINCT genre) as genre_count FROM books");
                        $result = $db->single();
                        echo $result['genre_count'];
                        ?>
                    </h2>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card text-center">
                <div class="card-body">
                    <h5 class="card-title">Latest Addition</h5>
                    <h2 class="text-info">
                        <?php 
                        $db->query("SELECT COUNT(*) as today_count FROM books WHERE DATE(created_at) = CURDATE()");
                        $result = $db->single();
                        echo $result['today_count'];
                        ?>
                    </h2>
                </div>
            </div>
        </div>
    </div>
</div>

<?php require_once 'templates/footer.tpl.php'; ?>