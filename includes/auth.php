<?php
// Simple authentication system (can be enhanced for production)
require_once __DIR__ . '/database.php';

class Auth {
    private $db;
    
    public function __construct() {
        $this->db = new Database();
    }
    
    // Check if user is logged in (session-based)
    public function isLoggedIn() {
        return isset($_SESSION['user_id']) && !empty($_SESSION['user_id']);
    }
    
    // Simple login function (for demonstration)
    public function login($username, $password) {
        // In a real application, you would verify against database
        // For demo purposes, using simple hardcoded credentials
        $valid_users = [
            'admin' => password_hash('admin123', PASSWORD_DEFAULT),
            'user' => password_hash('user123', PASSWORD_DEFAULT)
        ];
        
        if (array_key_exists($username, $valid_users) && password_verify($password, $valid_users[$username])) {
            $_SESSION['user_id'] = $username;
            $_SESSION['user_role'] = ($username === 'admin') ? 'admin' : 'user';
            return true;
        }
        
        return false;
    }
    
    // Logout function
    public function logout() {
        session_unset();
        session_destroy();
        session_start(); // Start fresh session for flash messages
    }
    
    // Check if user has admin privileges
    public function isAdmin() {
        return $this->isLoggedIn() && $_SESSION['user_role'] === 'admin';
    }
    
    // Protect page - redirect if not logged in
    public function requireLogin() {
        if (!$this->isLoggedIn()) {
            set_flash_message('danger', 'Please log in to access this page.');
            redirect('login.php');
        }
    }
    
    // Protect admin pages
    public function requireAdmin() {
        $this->requireLogin();
        if (!$this->isAdmin()) {
            set_flash_message('danger', 'Admin access required.');
            redirect('index.php');
        }
    }
}

// Initialize auth system
$auth = new Auth();
?>