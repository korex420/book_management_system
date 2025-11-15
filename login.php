<?php
require_once 'config/config.php';
require_once 'includes/database.php';
require_once 'includes/functions.php';
require_once 'includes/auth.php';

// Redirect if already logged in
if ($auth->isLoggedIn()) {
    redirect('index.php');
}

$errors = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $csrf_token = $_POST['csrf_token'] ?? '';
    
    if (!validate_csrf_token($csrf_token)) {
        die('CSRF token validation failed');
    }
    
    $username = sanitize_input($_POST['username'] ?? '');
    $password = $_POST['password'] ?? '';
    
    if (empty($username)) {
        $errors['username'] = 'Username is required';
    }
    
    if (empty($password)) {
        $errors['password'] = 'Password is required';
    }
    
    if (empty($errors)) {
        if ($auth->login($username, $password)) {
            set_flash_message('success', 'Login successful!');
            redirect('index.php');
        } else {
            $errors['login'] = 'Invalid username or password';
        }
    }
}

$title = 'Login';
$csrf_token = generate_csrf_token();
require_once 'templates/header.tpl.php';
?>

<div class="row justify-content-center">
    <div class="col-md-6 col-lg-5">
        <div class="card shadow-lg">
            <div class="card-header">
                <h2 class="text-center mb-0">Login</h2>
            </div>
            <div class="card-body">
                <?php if (isset($errors['login'])): ?>
                    <div class="alert alert-danger"><?php echo $errors['login']; ?></div>
                <?php endif; ?>
                
                <form method="POST">
                    <input type="hidden" name="csrf_token" value="<?php echo $csrf_token; ?>">
                    
                    <div class="mb-3">
                        <label for="username" class="form-label">Username</label>
                        <input type="text" class="form-control <?php echo isset($errors['username']) ? 'is-invalid' : ''; ?>" 
                               id="username" name="username" value="<?php echo $_POST['username'] ?? ''; ?>" required>
                        <?php if (isset($errors['username'])): ?>
                            <div class="invalid-feedback"><?php echo $errors['username']; ?></div>
                        <?php endif; ?>
                    </div>
                    
                    <div class="mb-3">
                        <label for="password" class="form-label">Password</label>
                        <input type="password" class="form-control <?php echo isset($errors['password']) ? 'is-invalid' : ''; ?>" 
                               id="password" name="password" required>
                        <?php if (isset($errors['password'])): ?>
                            <div class="invalid-feedback"><?php echo $errors['password']; ?></div>
                        <?php endif; ?>
                    </div>
                    
                    <div class="d-grid">
                        <button type="submit" class="btn btn-primary">Login</button>
                    </div>
                </form>
                
                <div class="mt-4 pt-3 border-top">
                    <p class="text-center text-muted mb-2">
                        <strong>Demo Credentials:</strong>
                    </p>
                    <div class="text-center">
                        <small class="d-block mb-1">
                            <code>admin</code> / <code>admin123</code>
                        </small>
                        <small class="d-block">
                            <code>user</code> / <code>user123</code>
                        </small>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php require_once 'templates/footer.tpl.php'; ?>