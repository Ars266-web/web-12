<?php
/**
 * TechSpace Database Configuration
 * 
 * This file handles database connection using PDO
 * Update these settings according to your XAMPP/WAMP environment
 */

// Prevent direct access
defined('TECHSPACE') or define('TECHSPACE', true);

// Database Configuration
define('DB_HOST', 'localhost');
define('DB_NAME', 'techspace');
define('DB_USER', 'root');
define('DB_PASS', '');
define('DB_CHARSET', 'utf8mb4');

// Application Configuration
define('SITE_URL', 'http://localhost/techspace');
define('ADMIN_URL', SITE_URL . '/admin');
define('UPLOAD_DIR', __DIR__ . '/../uploads/');
define('BASE_PATH', dirname(__DIR__));

// Debug mode (set to false in production)
define('DEBUG', true);

// Initialize database connection
$pdo = null;
try {
    $dsn = "mysql:host=" . DB_HOST . ";dbname=" . DB_NAME . ";charset=" . DB_CHARSET;
    $options = [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        PDO::ATTR_EMULATE_PREPARES => false,
    ];
    $pdo = new PDO($dsn, DB_USER, DB_PASS, $options);
} catch (PDOException $e) {
    if (DEBUG) {
        die("Database Connection Error: " . $e->getMessage());
    } else {
        error_log("Database Connection Error: " . $e->getMessage());
        die("Database connection failed. Please check configuration.");
    }
}
