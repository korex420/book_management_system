<?php
require_once 'config/config.php';
require_once 'includes/database.php';
require_once 'includes/functions.php';
require_once 'includes/auth.php';

$db = new Database();
$books = [];
$search_performed = false;

if ($_SERVER['REQUEST_METHOD'] === 'POST' || isset($_GET['search'])) {
    $search_performed = true;
    
    // Build search query dynamically
    $sql = "SELECT * FROM books WHERE 1=1";
    $params = [];
    
    if (!empty($_POST['title'] ?? $_GET['title'] ?? '')) {
        $title = sanitize_input($_POST['title'] ?? $_GET['title'] ?? '');
        $sql .= " AND title LIKE :title";
        $params[':title'] = "%$title%";
    }
    
    if (!empty($_POST['author'] ?? $_GET['author'] ?? '')) {
        $author = sanitize_input($_POST['author'] ?? $_GET['author'] ?? '');
        $sql .= " AND author LIKE :author";
        $params[':author'] = "%$author%";
    }
    
    if (!empty($_POST['genre'] ?? $_GET['genre'] ?? '')) {
        $genre = sanitize_input($_POST['genre'] ?? $_GET['genre'] ?? '');
        $sql .= " AND genre = :genre";
        $params[':genre'] = $genre;
    }
    
    if (!empty($_POST['publication_year'] ?? $_GET['publication_year'] ?? '')) {
        $year = (int)sanitize_input($_POST['publication_year'] ?? $_GET['publication_year'] ?? '');
        $sql .= " AND publication_year = :publication_year";
        $params[':publication_year'] = $year;
    }
    
    if (!empty($_POST['min_year'] ?? $_GET['min_year'] ?? '')) {
        $min_year = (int)sanitize_input($_POST['min_year'] ?? $_GET['min_year'] ?? '');
        $sql .= " AND publication_year >= :min_year";
        $params[':min_year'] = $min_year;
    }
    
    if (!empty($_POST['max_year'] ?? $_GET['max_year'] ?? '')) {
        $max_year = (int)sanitize_input($_POST['max_year'] ?? $_GET['max_year'] ?? '');
        $sql .= " AND publication_year <= :max_year";
        $params[':max_year'] = $max_year;
    }
    
    if (!empty($_POST['min_price'] ?? $_GET['min_price'] ?? '')) {
        $min_price = (float)sanitize_input($_POST['min_price'] ?? $_GET['min_price'] ?? '');
        $sql .= " AND price >= :min_price";
        $params[':min_price'] = $min_price;
    }
    
    if (!empty($_POST['max_price'] ?? $_GET['max_price'] ?? '')) {
        $max_price = (float)sanitize_input($_POST['max_price'] ?? $_GET['max_price'] ?? '');
        $sql .= " AND price <= :max_price";
        $params[':max_price'] = $max_price;
    }
    
    $sql .= " ORDER BY title";
    
    // Execute search
    $db->query($sql);
    foreach ($params as $key => $value) {
        $db->bind($key, $value);
    }
    
    $books = $db->resultSet();
}

$title = 'Advanced Book Search';
require_once 'templates/header.tpl.php';
?>

<div class="row">
    <div class="col-md-4">
        <h1 class="mb-4">Advanced Search</h1>
        
        <form method="POST" id="searchForm">
            <div class="card">
                <div class="card-body">
                    <div class="mb-3">
                        <label for="title" class="form-label">Title</label>
                        <input type="text" class="form-control" id="title" name="title" 
                               value="<?php echo $_POST['title'] ?? $_GET['title'] ?? ''; ?>">
                    </div>
                    
                    <div class="mb-3">
                        <label for="author" class="form-label">Author</label>
                        <input type="text" class="form-control" id="author" name="author" 
                               value="<?php echo $_POST['author'] ?? $_GET['author'] ?? ''; ?>">
                    </div>
                    
                    <div class="mb-3">
                        <label for="genre" class="form-label">Genre</label>
                        <select class="form-select" id="genre" name="genre">
                            <option value="">All Genres</option>
                            <option value="Sci-Fi" <?php echo ($_POST['genre'] ?? $_GET['genre'] ?? '') == 'Sci-Fi' ? 'selected' : ''; ?>>Sci-Fi</option>
                            <option value="Fiction" <?php echo ($_POST['genre'] ?? $_GET['genre'] ?? '') == 'Fiction' ? 'selected' : ''; ?>>Fiction</option>
                            <option value="Mystery" <?php echo ($_POST['genre'] ?? $_GET['genre'] ?? '') == 'Mystery' ? 'selected' : ''; ?>>Mystery</option>
                            <option value="Thriller" <?php echo ($_POST['genre'] ?? $_GET['genre'] ?? '') == 'Thriller' ? 'selected' : ''; ?>>Thriller</option>
                            <option value="Romance" <?php echo ($_POST['genre'] ?? $_GET['genre'] ?? '') == 'Romance' ? 'selected' : ''; ?>>Romance</option>
                            <option value="Fantasy" <?php echo ($_POST['genre'] ?? $_GET['genre'] ?? '') == 'Fantasy' ? 'selected' : ''; ?>>Fantasy</option>
                        </select>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="min_year" class="form-label">Min Year</label>
                                <input type="number" class="form-control" id="min_year" name="min_year" 
                                       min="1900" max="<?php echo date('Y'); ?>" 
                                       value="<?php echo $_POST['min_year'] ?? $_GET['min_year'] ?? ''; ?>">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="max_year" class="form-label">Max Year</label>
                                <input type="number" class="form-control" id="max_year" name="max_year" 
                                       min="1900" max="<?php echo date('Y'); ?>" 
                                       value="<?php echo $_POST['max_year'] ?? $_GET['max_year'] ?? ''; ?>">
                            </div>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="min_price" class="form-label">Min Price (£)</label>
                                <input type="number" step="0.01" class="form-control" id="min_price" name="min_price" 
                                       value="<?php echo $_POST['min_price'] ?? $_GET['min_price'] ?? ''; ?>">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="max_price" class="form-label">Max Price (£)</label>
                                <input type="number" step="0.01" class="form-control" id="max_price" name="max_price" 
                                       value="<?php echo $_POST['max_price'] ?? $_GET['max_price'] ?? ''; ?>">
                            </div>
                        </div>
                    </div>
                    
                    <div class="d-grid gap-2">
                        <button type="submit" class="btn btn-primary">Search</button>
                        <a href="search.php" class="btn btn-secondary">Reset</a>
                    </div>
                </div>
            </div>
        </form>
    </div>
    
    <div class="col-md-8">
        <h2 class="mb-4">Search Results</h2>
        
        <?php if ($search_performed): ?>
            <?php if (empty($books)): ?>
                <div class="alert alert-info">
                    No books found matching your criteria.
                </div>
            <?php else: ?>
                <div class="alert alert-success">
                    Found <?php echo count($books); ?> book(s) matching your criteria.
                </div>
                
                <div class="table-responsive">
                    <table class="table table-striped table-hover">
                        <thead class="table-dark">
                            <tr>
                                <th>Title</th>
                                <th>Author</th>
                                <th>Genre</th>
                                <th>Year</th>
                                <th>Price</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($books as $book): ?>
                                <tr>
                                    <td><?php echo htmlspecialchars($book['title']); ?></td>
                                    <td><?php echo htmlspecialchars($book['author']); ?></td>
                                    <td><?php echo htmlspecialchars($book['genre']); ?></td>
                                    <td><?php echo htmlspecialchars($book['publication_year']); ?></td>
                                    <td>£<?php echo number_format($book['price'], 2); ?></td>
                                    <td>
                                        <a href="edit_book.php?id=<?php echo $book['id']; ?>" class="btn btn-sm btn-warning">Edit</a>
                                        <a href="delete_book.php?id=<?php echo $book['id']; ?>" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure?')">Delete</a>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            <?php endif; ?>
        <?php else: ?>
            <div class="alert alert-info">
                Use the form to search for books. You can search by title, author, genre, publication year, and price range.
            </div>
            
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Example Searches:</h5>
                    <ul>
                        <li><a href="search.php?genre=Sci-Fi&min_year=2020">Sci-Fi books published since 2020</a></li>
                        <li><a href="search.php?author=Andy Weir">Books by Andy Weir</a></li>
                        <li><a href="search.php?max_price=15">Books under £15</a></li>
                        <li><a href="search.php?genre=Sci-Fi&min_year=2021&max_year=2023">Sci-Fi books published between 2021-2023</a></li>
                    </ul>
                </div>
            </div>
        <?php endif; ?>
    </div>
</div>

<?php require_once 'templates/footer.tpl.php'; ?>