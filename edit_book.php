<?php
require_once 'config/config.php';
require_once 'includes/database.php';
require_once 'includes/functions.php';
require_once 'includes/auth.php';

// Require login for editing books
$auth->requireLogin();

$db = new Database();
$errors = [];
$book = null;

// Get book ID from URL
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    set_flash_message('danger', 'Invalid book ID');
    redirect('index.php');
}

$book_id = (int)$_GET['id'];

// Fetch existing book data
$db->query("SELECT * FROM books WHERE id = :id");
$db->bind(':id', $book_id);
$book = $db->single();

if (!$book) {
    set_flash_message('danger', 'Book not found');
    redirect('index.php');
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $csrf_token = $_POST['csrf_token'] ?? '';
    
    if (!validate_csrf_token($csrf_token)) {
        die('CSRF token validation failed');
    }

    // Sanitize and validate input
    $form_data = sanitize_array($_POST);
    
    // Validation
    if (empty($form_data['title'])) {
        $errors['title'] = 'Title is required';
    }
    
    if (empty($form_data['author'])) {
        $errors['author'] = 'Author is required';
    }
    
    if (empty($form_data['genre'])) {
        $errors['genre'] = 'Genre is required';
    }
    
    if (empty($form_data['publication_year']) || !is_numeric($form_data['publication_year'])) {
        $errors['publication_year'] = 'Valid publication year is required';
    }
    
    if (empty($form_data['price']) || !is_numeric($form_data['price'])) {
        $errors['price'] = 'Valid price is required';
    }

    // If no errors, update database
    if (empty($errors)) {
        $db->query("UPDATE books SET title = :title, author = :author, genre = :genre, 
                   publication_year = :publication_year, isbn = :isbn, description = :description, 
                   price = :price WHERE id = :id");
        
        $db->bind(':title', $form_data['title']);
        $db->bind(':author', $form_data['author']);
        $db->bind(':genre', $form_data['genre']);
        $db->bind(':publication_year', $form_data['publication_year']);
        $db->bind(':isbn', $form_data['isbn']);
        $db->bind(':description', $form_data['description']);
        $db->bind(':price', $form_data['price']);
        $db->bind(':id', $book_id);
        
        if ($db->execute()) {
            set_flash_message('success', 'Book updated successfully!');
            redirect('index.php');
        } else {
            $errors['database'] = 'Failed to update book. Please try again.';
        }
    }
} else {
    // Pre-populate form with existing data
    $form_data = $book;
}

$title = 'Edit Book - ' . htmlspecialchars($book['title']);
$csrf_token = generate_csrf_token();
require_once 'templates/book_form.tpl.php';
require_once 'templates/header.tpl.php';
?>

<div class="row justify-content-center">
    <div class="col-md-8">
        <h1 class="mb-4">Edit Book</h1>
        <?php displayBookForm($form_data, $errors, true); ?>
    </div>
</div>

<?php require_once 'templates/footer.tpl.php'; ?>