<?php
// Book form template for reusable form display
function displayBookForm($form_data = [], $errors = [], $is_edit = false) {
    global $csrf_token;
    ?>
    <form method="POST" id="bookForm" novalidate>
        <input type="hidden" name="csrf_token" value="<?php echo $csrf_token; ?>">
        
        <?php if (isset($errors['database'])): ?>
            <div class="alert alert-danger"><?php echo $errors['database']; ?></div>
        <?php endif; ?>
        
        <div class="row">
            <div class="col-md-6">
                <div class="mb-3">
                    <label for="title" class="form-label">Title *</label>
                    <input type="text" class="form-control <?php echo isset($errors['title']) ? 'is-invalid' : ''; ?>" 
                           id="title" name="title" value="<?php echo $form_data['title'] ?? ''; ?>" 
                           placeholder="Enter book title" required>
                    <?php if (isset($errors['title'])): ?>
                        <div class="invalid-feedback"><?php echo $errors['title']; ?></div>
                    <?php else: ?>
                        <div class="form-text">The full title of the book.</div>
                    <?php endif; ?>
                </div>
                
                <div class="mb-3">
                    <label for="author" class="form-label">Author *</label>
                    <input type="text" class="form-control <?php echo isset($errors['author']) ? 'is-invalid' : ''; ?>" 
                           id="author" name="author" value="<?php echo $form_data['author'] ?? ''; ?>" 
                           placeholder="Enter author name" required>
                    <?php if (isset($errors['author'])): ?>
                        <div class="invalid-feedback"><?php echo $errors['author']; ?></div>
                    <?php else: ?>
                        <div class="form-text">The author's full name.</div>
                    <?php endif; ?>
                </div>
                
                <div class="mb-3">
                    <label for="genre" class="form-label">Genre *</label>
                    <select class="form-select <?php echo isset($errors['genre']) ? 'is-invalid' : ''; ?>" 
                            id="genre" name="genre" required>
                        <option value="">Select a genre</option>
                        <option value="Sci-Fi" <?php echo ($form_data['genre'] ?? '') == 'Sci-Fi' ? 'selected' : ''; ?>>Science Fiction</option>
                        <option value="Fiction" <?php echo ($form_data['genre'] ?? '') == 'Fiction' ? 'selected' : ''; ?>>Fiction</option>
                        <option value="Mystery" <?php echo ($form_data['genre'] ?? '') == 'Mystery' ? 'selected' : ''; ?>>Mystery</option>
                        <option value="Thriller" <?php echo ($form_data['genre'] ?? '') == 'Thriller' ? 'selected' : ''; ?>>Thriller</option>
                        <option value="Romance" <?php echo ($form_data['genre'] ?? '') == 'Romance' ? 'selected' : ''; ?>>Romance</option>
                        <option value="Fantasy" <?php echo ($form_data['genre'] ?? '') == 'Fantasy' ? 'selected' : ''; ?>>Fantasy</option>
                        <option value="Biography" <?php echo ($form_data['genre'] ?? '') == 'Biography' ? 'selected' : ''; ?>>Biography</option>
                        <option value="History" <?php echo ($form_data['genre'] ?? '') == 'History' ? 'selected' : ''; ?>>History</option>
                        <option value="Non-Fiction" <?php echo ($form_data['genre'] ?? '') == 'Non-Fiction' ? 'selected' : ''; ?>>Non-Fiction</option>
                    </select>
                    <?php if (isset($errors['genre'])): ?>
                        <div class="invalid-feedback"><?php echo $errors['genre']; ?></div>
                    <?php endif; ?>
                </div>
            </div>
            
            <div class="col-md-6">
                <div class="mb-3">
                    <label for="publication_year" class="form-label">Publication Year *</label>
                    <input type="number" class="form-control <?php echo isset($errors['publication_year']) ? 'is-invalid' : ''; ?>" 
                           id="publication_year" name="publication_year" 
                           min="1900" max="<?php echo date('Y'); ?>" 
                           value="<?php echo $form_data['publication_year'] ?? ''; ?>" 
                           placeholder="2024" required>
                    <?php if (isset($errors['publication_year'])): ?>
                        <div class="invalid-feedback"><?php echo $errors['publication_year']; ?></div>
                    <?php else: ?>
                        <div class="form-text">The year the book was published.</div>
                    <?php endif; ?>
                </div>
                
                <div class="mb-3">
                    <label for="isbn" class="form-label">ISBN</label>
                    <input type="text" class="form-control" id="isbn" name="isbn" 
                           value="<?php echo $form_data['isbn'] ?? ''; ?>" 
                           placeholder="978-XXXXXXXXX">
                    <div class="form-text">International Standard Book Number (optional).</div>
                </div>
                
                <div class="mb-3">
                    <label for="price" class="form-label">Price (£) *</label>
                    <div class="input-group">
                        <span class="input-group-text">£</span>
                        <input type="number" step="0.01" class="form-control <?php echo isset($errors['price']) ? 'is-invalid' : ''; ?>" 
                               id="price" name="price" value="<?php echo $form_data['price'] ?? ''; ?>" 
                               placeholder="0.00" required>
                    </div>
                    <?php if (isset($errors['price'])): ?>
                        <div class="invalid-feedback"><?php echo $errors['price']; ?></div>
                    <?php else: ?>
                        <div class="form-text">The retail price of the book.</div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
        
        <div class="mb-3">
            <label for="description" class="form-label">Description</label>
            <textarea class="form-control" id="description" name="description" 
                      rows="4" placeholder="Enter a brief description of the book..."><?php echo $form_data['description'] ?? ''; ?></textarea>
            <div class="form-text">A short summary or description of the book (optional).</div>
        </div>
        
        <div class="d-grid gap-2 d-md-flex justify-content-md-end">
            <a href="index.php" class="btn btn-secondary me-md-2">Cancel</a>
            <button type="submit" class="btn btn-primary">
                <?php echo $is_edit ? 'Update Book' : 'Add Book'; ?>
            </button>
        </div>
    </form>
    <?php
}
?>