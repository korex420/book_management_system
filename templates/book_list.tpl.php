<?php
// Book list template for reusable book display
function displayBookList($books, $show_actions = true) {
    global $auth;
    if (empty($books)): ?>
        <div class="alert alert-info">
            No books found. <?php if ($show_actions && isset($auth) && $auth->isLoggedIn()): ?>
                <a href="add_book.php" class="alert-link">Add the first book</a>
            <?php endif; ?>
        </div>
    <?php else: ?>
        <div class="table-responsive">
            <table class="table table-striped table-hover">
                <thead class="table-dark">
                    <tr>
                        <th>Title</th>
                        <th>Author</th>
                        <th>Genre</th>
                        <th>Year</th>
                        <th>ISBN</th>
                        <th>Price</th>
                        <?php if ($show_actions): ?>
                            <th>Actions</th>
                        <?php endif; ?>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($books as $book): ?>
                        <tr>
                            <td>
                                <strong><?php echo htmlspecialchars($book['title']); ?></strong>
                                <?php if (!empty($book['description'])): ?>
                                    <br><small class="text-muted"><?php echo htmlspecialchars(substr($book['description'], 0, 100)); ?>...</small>
                                <?php endif; ?>
                            </td>
                            <td><?php echo htmlspecialchars($book['author']); ?></td>
                            <td>
                                <span class="badge bg-primary"><?php echo htmlspecialchars($book['genre']); ?></span>
                            </td>
                            <td><?php echo htmlspecialchars($book['publication_year']); ?></td>
                            <td><code><?php echo htmlspecialchars($book['isbn']); ?></code></td>
                            <td>£<?php echo number_format($book['price'], 2); ?></td>
                            <?php if ($show_actions): ?>
                                <td>
                                    <div class="btn-group btn-group-sm">
                                        <a href="view_book.php?id=<?php echo $book['id']; ?>" class="btn btn-info">View</a>
                                        <?php if (isset($auth) && $auth->isLoggedIn()): ?>
                                            <a href="edit_book.php?id=<?php echo $book['id']; ?>" class="btn btn-warning">Edit</a>
                                            <a href="delete_book.php?id=<?php echo $book['id']; ?>" class="btn btn-danger" 
                                               onclick="return confirm('Are you sure you want to delete this book?')">Delete</a>
                                        <?php endif; ?>
                                    </div>
                                </td>
                            <?php endif; ?>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    <?php endif;
}
?>