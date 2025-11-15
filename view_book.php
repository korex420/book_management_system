<?php
require_once 'config/config.php';
require_once 'includes/database.php';
require_once 'includes/functions.php';
require_once 'includes/auth.php';

$db = new Database();

// Get book ID from URL
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    set_flash_message('danger', 'Invalid book ID');
    redirect('index.php');
}

$book_id = (int)$_GET['id'];

// Fetch book data
$db->query("SELECT * FROM books WHERE id = :id");
$db->bind(':id', $book_id);
$book = $db->single();

if (!$book) {
    set_flash_message('danger', 'Book not found');
    redirect('index.php');
}

$title = 'View Book - ' . htmlspecialchars($book['title']);
require_once 'templates/header.tpl.php';
?>

<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header bg-primary text-white">
                <div class="d-flex justify-content-between align-items-center">
                    <h2 class="mb-0">Book Details</h2>
                    <div>
                        <?php if ($auth->isLoggedIn()): ?>
                            <a href="edit_book.php?id=<?php echo $book['id']; ?>" class="btn btn-warning btn-sm">Edit</a>
                            <a href="delete_book.php?id=<?php echo $book['id']; ?>" class="btn btn-danger btn-sm" 
                               onclick="return confirm('Are you sure?')">Delete</a>
                        <?php endif; ?>
                        <a href="index.php" class="btn btn-secondary btn-sm">Back to List</a>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-8">
                        <h3 class="text-primary"><?php echo htmlspecialchars($book['title']); ?></h3>
                        <h5 class="text-muted">by <?php echo htmlspecialchars($book['author']); ?></h5>
                        
                        <div class="mt-4">
                            <table class="table table-bordered">
                                <tr>
                                    <th width="30%">Genre</th>
                                    <td>
                                        <span class="badge bg-primary fs-6"><?php echo htmlspecialchars($book['genre']); ?></span>
                                    </td>
                                </tr>
                                <tr>
                                    <th>Publication Year</th>
                                    <td><?php echo htmlspecialchars($book['publication_year']); ?></td>
                                </tr>
                                <tr>
                                    <th>ISBN</th>
                                    <td><code><?php echo htmlspecialchars($book['isbn']); ?></code></td>
                                </tr>
                                <tr>
                                    <th>Price</th>
                                    <td class="h5 text-success">£<?php echo number_format($book['price'], 2); ?></td>
                                </tr>
                                <tr>
                                    <th>Added On</th>
                                    <td><?php echo date('F j, Y', strtotime($book['created_at'])); ?></td>
                                </tr>
                                <tr>
                                    <th>Last Updated</th>
                                    <td><?php echo date('F j, Y', strtotime($book['updated_at'])); ?></td>
                                </tr>
                            </table>
                        </div>
                    </div>
                    <div class="col-md-4 text-center">
                        <div class="bg-light p-4 rounded">
                            <div class="display-1 text-muted">📚</div>
                            <div class="mt-3">
                                <strong>Book ID:</strong> #<?php echo $book['id']; ?>
                            </div>
                        </div>
                    </div>
                </div>
                
                <?php if (!empty($book['description'])): ?>
                    <div class="mt-4">
                        <h5>Description</h5>
                        <div class="p-3 bg-light rounded">
                            <?php echo nl2br(htmlspecialchars($book['description'])); ?>
                        </div>
                    </div>
                <?php endif; ?>
            </div>
        </div>
        
        <div class="mt-3 text-center">
            <a href="index.php" class="btn btn-secondary">Back to Book List</a>
            <?php if ($auth->isLoggedIn()): ?>
                <a href="edit_book.php?id=<?php echo $book['id']; ?>" class="btn btn-warning">Edit This Book</a>
            <?php endif; ?>
        </div>
    </div>
</div>

<?php require_once 'templates/footer.tpl.php'; ?>