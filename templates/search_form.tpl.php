<?php
// Search form template for reusable search forms
function displaySearchForm($current_params = []) {
    ?>
    <form method="GET" id="searchForm" class="mb-4">
        <div class="card">
            <div class="card-header bg-primary text-white">
                <h5 class="mb-0">Search Books</h5>
            </div>
            <div class="card-body">
                <div class="row g-3">
                    <div class="col-md-6">
                        <label for="title" class="form-label">Title</label>
                        <input type="text" class="form-control" id="title" name="title" 
                               value="<?php echo $current_params['title'] ?? ''; ?>" 
                               placeholder="Search by title...">
                    </div>
                    
                    <div class="col-md-6">
                        <label for="author" class="form-label">Author</label>
                        <input type="text" class="form-control" id="author" name="author" 
                               value="<?php echo $current_params['author'] ?? ''; ?>" 
                               placeholder="Search by author...">
                    </div>
                    
                    <div class="col-md-4">
                        <label for="genre" class="form-label">Genre</label>
                        <select class="form-select" id="genre" name="genre">
                            <option value="">All Genres</option>
                            <option value="Sci-Fi" <?php echo ($current_params['genre'] ?? '') == 'Sci-Fi' ? 'selected' : ''; ?>>Science Fiction</option>
                            <option value="Fiction" <?php echo ($current_params['genre'] ?? '') == 'Fiction' ? 'selected' : ''; ?>>Fiction</option>
                            <option value="Mystery" <?php echo ($current_params['genre'] ?? '') == 'Mystery' ? 'selected' : ''; ?>>Mystery</option>
                            <option value="Thriller" <?php echo ($current_params['genre'] ?? '') == 'Thriller' ? 'selected' : ''; ?>>Thriller</option>
                            <option value="Romance" <?php echo ($current_params['genre'] ?? '') == 'Romance' ? 'selected' : ''; ?>>Romance</option>
                            <option value="Fantasy" <?php echo ($current_params['genre'] ?? '') == 'Fantasy' ? 'selected' : ''; ?>>Fantasy</option>
                            <option value="Biography" <?php echo ($current_params['genre'] ?? '') == 'Biography' ? 'selected' : ''; ?>>Biography</option>
                            <option value="History" <?php echo ($current_params['genre'] ?? '') == 'History' ? 'selected' : ''; ?>>History</option>
                        </select>
                    </div>
                    
                    <div class="col-md-4">
                        <label for="min_year" class="form-label">Min Year</label>
                        <input type="number" class="form-control" id="min_year" name="min_year" 
                               min="1900" max="<?php echo date('Y'); ?>" 
                               value="<?php echo $current_params['min_year'] ?? ''; ?>" 
                               placeholder="From year...">
                    </div>
                    
                    <div class="col-md-4">
                        <label for="max_year" class="form-label">Max Year</label>
                        <input type="number" class="form-control" id="max_year" name="max_year" 
                               min="1900" max="<?php echo date('Y'); ?>" 
                               value="<?php echo $current_params['max_year'] ?? ''; ?>" 
                               placeholder="To year...">
                    </div>
                    
                    <div class="col-md-6">
                        <label for="min_price" class="form-label">Min Price (£)</label>
                        <div class="input-group">
                            <span class="input-group-text">£</span>
                            <input type="number" step="0.01" class="form-control" id="min_price" name="min_price" 
                                   value="<?php echo $current_params['min_price'] ?? ''; ?>" 
                                   placeholder="Minimum price...">
                        </div>
                    </div>
                    
                    <div class="col-md-6">
                        <label for="max_price" class="form-label">Max Price (£)</label>
                        <div class="input-group">
                            <span class="input-group-text">£</span>
                            <input type="number" step="0.01" class="form-control" id="max_price" name="max_price" 
                                   value="<?php echo $current_params['max_price'] ?? ''; ?>" 
                                   placeholder="Maximum price...">
                        </div>
                    </div>
                </div>
                
                <div class="row mt-3">
                    <div class="col-12">
                        <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                            <button type="submit" class="btn btn-primary me-md-2">
                                <i class="bi bi-search"></i> Search Books
                            </button>
                            <a href="search.php" class="btn btn-outline-secondary">Clear Filters</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>
    <?php
}
?>