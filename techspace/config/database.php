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

// Session Configuration
ini_set('session.cookie_httponly', 1);
ini_set('session.use_strict_mode', 1);

class Database {
    private static $instance = null;
    private $conn;
    
    private function __construct() {
        try {
            $dsn = "mysql:host=" . DB_HOST . ";dbname=" . DB_NAME . ";charset=" . DB_CHARSET;
            $options = [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                PDO::ATTR_EMULATE_PREPARES => false,
            ];
            
            $this->conn = new PDO($dsn, DB_USER, DB_PASS, $options);
        } catch (PDOException $e) {
            // Log error in production, show in development
            if (defined('DEBUG') && DEBUG) {
                die("Database Connection Error: " . $e->getMessage());
            } else {
                error_log("Database Connection Error: " . $e->getMessage());
                die("Database connection failed. Please check configuration.");
            }
        }
    }
    
    public static function getInstance() {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance;
    }
    
    public function getConnection() {
        return $this->conn;
    }
    
    // Prevent cloning
    private function __clone() {}
    public function __wakeup() {
        throw new Exception("Cannot unserialize singleton");
    }
}

/**
 * Get database connection
 * @return PDO
 */
function getDB() {
    return Database::getInstance()->getConnection();
}

/**
 * Execute a prepared statement with parameters
 * @param string $sql SQL query with placeholders
 * @param array $params Parameters to bind
 * @return PDOStatement
 */
function dbQuery($sql, $params = []) {
    try {
        $stmt = getDB()->prepare($sql);
        $stmt->execute($params);
        return $stmt;
    } catch (PDOException $e) {
        error_log("Query Error: " . $e->getMessage());
        if (defined('DEBUG') && DEBUG) {
            die("Query Error: " . $e->getMessage());
        }
        return false;
    }
}

/**
 * Fetch single row
 * @param string $sql SQL query
 * @param array $params Parameters
 * @return array|null
 */
function dbFetchOne($sql, $params = []) {
    $stmt = dbQuery($sql, $params);
    if ($stmt) {
        return $stmt->fetch();
    }
    return null;
}

/**
 * Fetch all rows
 * @param string $sql SQL query
 * @param array $params Parameters
 * @return array
 */
function dbFetchAll($sql, $params = []) {
    $stmt = dbQuery($sql, $params);
    if ($stmt) {
        return $stmt->fetchAll();
    }
    return [];
}

/**
 * Get last insert ID
 * @return string
 */
function dbLastInsertId() {
    return getDB()->lastInsertId();
}

/**
 * Begin transaction
 * @return bool
 */
function dbBeginTransaction() {
    return getDB()->beginTransaction();
}

/**
 * Commit transaction
 * @return bool
 */
function dbCommit() {
    return getDB()->commit();
}

/**
 * Rollback transaction
 * @return bool
 */
function dbRollback() {
    return getDB()->rollBack();
}
