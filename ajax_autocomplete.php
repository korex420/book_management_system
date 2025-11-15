<?php
require_once 'config/config.php';
require_once 'includes/database.php';
require_once 'includes/functions.php';

header('Content-Type: application/json');

$db = new Database();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $type = sanitize_input($_POST['type'] ?? '');
    $query = sanitize_input($_POST['query'] ?? '');
    
    $suggestions = [];
    
    switch ($type) {
        case 'author':
            $db->query("SELECT DISTINCT author FROM books WHERE author LIKE :query ORDER BY author LIMIT 10");
            $db->bind(':query', "%$query%");
            $results = $db->resultSet();
            $suggestions = array_column($results, 'author');
            break;
            
        case 'genre':
            $db->query("SELECT DISTINCT genre FROM books WHERE genre LIKE :query ORDER BY genre LIMIT 10");
            $db->bind(':query', "%$query%");
            $results = $db->resultSet();
            $suggestions = array_column($results, 'genre');
            break;
    }
    
    echo json_encode($suggestions);
}
?>