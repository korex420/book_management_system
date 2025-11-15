<?php
require_once 'config/config.php';
require_once 'includes/database.php';
require_once 'includes/functions.php';
require_once 'includes/auth.php';

// Require login for adding books
$auth->requireLogin();

$db = new Database();
$errors = [];
$form_data = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $csrf_token = $_POST['csrf_token'] ?? '';
    
    if (!validate_csrf_token($csrf_token)) {
        die('CSRF token validation failed');
    }

    // Sanitize and validate input
    $form_data = sanitize_array($_POST);
    
    // Validation (same as before)
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

    // If no errors, insert into database
    if (empty($errors)) {
        $db->query("INSERT INTO books (title, author, genre, publication_year, isbn, description, price) 
                   VALUES (:title, :author, :genre, :publication_year, :isbn, :description, :price)");
        
        $db->bind(':title', $form_data['title']);
        $db->bind(':author', $form_data['author']);
        $db->bind(':genre', $form_data['genre']);
        $db->bind(':publication_year', $form_data['publication_year']);
        $db->bind(':isbn', $form_data['isbn']);
        $db->bind(':description', $form_data['description']);
        $db->bind(':price', $form_data['price']);
        
        if ($db->execute()) {
            set_flash_message('success', 'Book added successfully!');
            redirect('index.php');
        } else {
            $errors['database'] = 'Failed to add book. Please try again.';
        }
    }
}

$title = 'Add New Book';
$csrf_token = generate_csrf_token();
require_once 'templates/book_form.tpl.php';
require_once 'templates/header.tpl.php';
?>

<div class="row justify-content-center">
    <div class="col-md-8">
        <h1 class="mb-4">Add New Book</h1>
        <?php displayBookForm($form_data, $errors, false); ?>
    </div>
</div>

<?php require_once 'templates/footer.tpl.php'; ?>