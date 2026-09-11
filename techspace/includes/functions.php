<?php
/**
 * TechSpace Helper Functions
 * 
 * Common utility functions used throughout the application
 */

defined('TECHSPACE') or define('TECHSPACE', true);

/**
 * Sanitize input data
 * @param string $data Input data
 * @return string Sanitized data
 */
function sanitize($data) {
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data, ENT_QUOTES, 'UTF-8');
    return $data;
}

/**
 * Redirect to a URL
 * @param string $url URL to redirect to
 */
function redirect($url) {
    header("Location: " . $url);
    exit();
}

/**
 * Check if user is logged in
 * @return bool
 */
function isLoggedIn() {
    return isset($_SESSION['user_id']);
}

/**
 * Get current user ID
 * @return int|null
 */
function getUserId() {
    return $_SESSION['user_id'] ?? null;
}

/**
 * Get current user data
 * @return array|null
 */
function getCurrentUser() {
    if (!isLoggedIn()) {
        return null;
    }
    
    static $currentUser = null;
    if ($currentUser === null) {
        $currentUser = dbFetchOne("SELECT * FROM users WHERE id = ?", [getUserId()]);
    }
    return $currentUser;
}

/**
 * Check if admin is logged in
 * @return bool
 */
function isAdminLoggedIn() {
    return isset($_SESSION['admin_id']);
}

/**
 * Get current admin ID
 * @return int|null
 */
function getAdminId() {
    return $_SESSION['admin_id'] ?? null;
}

/**
 * Get current admin data
 * @return array|null
 */
function getCurrentAdmin() {
    if (!isAdminLoggedIn()) {
        return null;
    }
    
    static $currentAdmin = null;
    if ($currentAdmin === null) {
        $currentAdmin = dbFetchOne("SELECT * FROM admins WHERE id = ?", [getAdminId()]);
    }
    return $currentAdmin;
}

/**
 * Require login - redirect to login page if not logged in
 */
function requireLogin() {
    if (!isLoggedIn()) {
        $_SESSION['redirect_after_login'] = $_SERVER['REQUEST_URI'];
        redirect(SITE_URL . '/login.php');
    }
}

/**
 * Require admin login
 */
function requireAdmin() {
    if (!isAdminLoggedIn()) {
        redirect(ADMIN_URL . '/login.php');
    }
}

/**
 * Generate CSRF token
 * @return string
 */
function generateCSRFToken() {
    if (!isset($_SESSION['csrf_token'])) {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    }
    return $_SESSION['csrf_token'];
}

/**
 * Verify CSRF token
 * @param string $token Token to verify
 * @return bool
 */
function verifyCSRFToken($token) {
    return isset($_SESSION['csrf_token']) && hash_equals($_SESSION['csrf_token'], $token);
}

/**
 * Format price in PKR
 * @param float $price Price value
 * @return string Formatted price
 */
function formatPrice($price) {
    return 'Rs. ' . number_format($price, 0);
}

/**
 * Calculate discount percentage
 * @param float $originalPrice Original price
 * @param float $salePrice Sale price
 * @return float Discount percentage
 */
function calculateDiscount($originalPrice, $salePrice) {
    if ($salePrice >= $originalPrice || $originalPrice <= 0) {
        return 0;
    }
    return round((($originalPrice - $salePrice) / $originalPrice) * 100, 2);
}

/**
 * Get product price (sale price if available)
 * @param array $product Product data
 * @return float Current price
 */
function getProductPrice($product) {
    return $product['sale_price'] ?? $product['price'];
}

/**
 * Generate unique order number
 * @return string Order number
 */
function generateOrderNumber() {
    $year = date('Y');
    $random = strtoupper(substr(uniqid(), -6));
    return "TS-{$year}-{$random}";
}

/**
 * Generate slug from string
 * @param string $string Input string
 * @return string Slug
 */
function generateSlug($string) {
    $string = strtolower(trim($string));
    $string = preg_replace('/[^a-z0-9-]/', '-', $string);
    $string = preg_replace('/-+/', '-', $string);
    return trim($string, '-');
}

/**
 * Upload file
 * @param array $file File from $_FILES
 * @param string $targetDir Target directory
 * @param array $allowedTypes Allowed MIME types
 * @return array Result with success status and filename
 */
function uploadFile($file, $targetDir, $allowedTypes = ['image/jpeg', 'image/png', 'image/gif', 'image/webp']) {
    $result = [
        'success' => false,
        'message' => '',
        'filename' => ''
    ];
    
    if (!isset($file['error']) || !is_int($file['error'])) {
        $result['message'] = 'Unknown upload error';
        return $result;
    }
    
    if ($file['error'] !== UPLOAD_ERR_OK) {
        $result['message'] = 'Upload error code: ' . $file['error'];
        return $result;
    }
    
    // Validate file size (max 5MB)
    if ($file['size'] > 5 * 1024 * 1024) {
        $result['message'] = 'File size exceeds maximum limit (5MB)';
        return $result;
    }
    
    // Validate file type
    $finfo = new finfo(FILEINFO_MIME_TYPE);
    $mimeType = $finfo->file($file['tmp_name']);
    
    if (!in_array($mimeType, $allowedTypes)) {
        $result['message'] = 'Invalid file type. Allowed: JPEG, PNG, GIF, WebP';
        return $result;
    }
    
    // Create target directory if it doesn't exist
    if (!is_dir($targetDir)) {
        mkdir($targetDir, 0755, true);
    }
    
    // Generate unique filename
    $extension = pathinfo($file['name'], PATHINFO_EXTENSION);
    $filename = uniqid() . '_' . time() . '.' . $extension;
    $targetPath = $targetDir . $filename;
    
    if (move_uploaded_file($file['tmp_name'], $targetPath)) {
        $result['success'] = true;
        $result['filename'] = $filename;
    } else {
        $result['message'] = 'Failed to move uploaded file';
    }
    
    return $result;
}

/**
 * Get setting value
 * @param string $key Setting key
 * @param mixed $default Default value if not found
 * @return mixed Setting value
 */
function getSetting($key, $default = null) {
    static $settings = null;
    
    if ($settings === null) {
        $results = dbFetchAll("SELECT setting_key, setting_value FROM settings");
        foreach ($results as $row) {
            $settings[$row['setting_key']] = $row['setting_value'];
        }
    }
    
    return $settings[$key] ?? $default;
}

/**
 * Update setting value
 * @param string $key Setting key
 * @param string $value Setting value
 * @return bool Success status
 */
function updateSetting($key, $value) {
    $result = dbQuery("UPDATE settings SET setting_value = ? WHERE setting_key = ?", [$value, $key]);
    return $result !== false;
}

/**
 * Get cart item count
 * @return int Cart item count
 */
function getCartCount() {
    if (isLoggedIn()) {
        $result = dbFetchOne("SELECT COUNT(*) as count FROM cart WHERE user_id = ?", [getUserId()]);
        return $result['count'] ?? 0;
    } else {
        // Session-based cart
        return isset($_SESSION['cart']) ? count($_SESSION['cart']) : 0;
    }
}

/**
 * Get wishlist item count
 * @return int Wishlist item count
 */
function getWishlistCount() {
    if (isLoggedIn()) {
        $result = dbFetchOne("SELECT COUNT(*) as count FROM wishlist WHERE user_id = ?", [getUserId()]);
        return $result['count'] ?? 0;
    }
    return 0;
}

/**
 * Get all categories
 * @return array Categories
 */
function getCategories($status = 1) {
    return dbFetchAll("SELECT * FROM categories WHERE status = ? ORDER BY sort_order ASC, name ASC", [$status]);
}

/**
 * Get category by slug
 * @param string $slug Category slug
 * @return array|null Category data
 */
function getCategoryBySlug($slug) {
    return dbFetchOne("SELECT * FROM categories WHERE slug = ? AND status = 1", [$slug]);
}

/**
 * Get featured products
 * @param int $limit Number of products
 * @return array Products
 */
function getFeaturedProducts($limit = 8) {
    return dbFetchAll("
        SELECT p.*, c.name as category_name, b.name as brand_name
        FROM products p
        LEFT JOIN categories c ON p.category_id = c.id
        LEFT JOIN brands b ON p.brand_id = b.id
        WHERE p.status = 1 AND p.is_featured = 1
        ORDER BY p.created_at DESC
        LIMIT ?
    ", [$limit]);
}

/**
 * Get new arrival products
 * @param int $limit Number of products
 * @return array Products
 */
function getNewProducts($limit = 8) {
    return dbFetchAll("
        SELECT p.*, c.name as category_name, b.name as brand_name
        FROM products p
        LEFT JOIN categories c ON p.category_id = c.id
        LEFT JOIN brands b ON p.brand_id = b.id
        WHERE p.status = 1 AND p.is_new = 1
        ORDER BY p.created_at DESC
        LIMIT ?
    ", [$limit]);
}

/**
 * Get products by category
 * @param int $categoryId Category ID
 * @param int $limit Number of products
 * @return array Products
 */
function getProductsByCategory($categoryId, $limit = 8) {
    return dbFetchAll("
        SELECT p.*, c.name as category_name, b.name as brand_name
        FROM products p
        LEFT JOIN categories c ON p.category_id = c.id
        LEFT JOIN brands b ON p.brand_id = b.id
        WHERE p.status = 1 AND p.category_id = ?
        ORDER BY p.created_at DESC
        LIMIT ?
    ", [$categoryId, $limit]);
}

/**
 * Get product by slug
 * @param string $slug Product slug
 * @return array|null Product data
 */
function getProductBySlug($slug) {
    return dbFetchOne("
        SELECT p.*, c.name as category_name, c.slug as category_slug, b.name as brand_name
        FROM products p
        LEFT JOIN categories c ON p.category_id = c.id
        LEFT JOIN brands b ON p.brand_id = b.id
        WHERE p.slug = ? AND p.status = 1
    ", [$slug]);
}

/**
 * Get product by ID
 * @param int $id Product ID
 * @return array|null Product data
 */
function getProductById($id) {
    return dbFetchOne("
        SELECT p.*, c.name as category_name, c.slug as category_slug, b.name as brand_name
        FROM products p
        LEFT JOIN categories c ON p.category_id = c.id
        LEFT JOIN brands b ON p.brand_id = b.id
        WHERE p.id = ? AND p.status = 1
    ", [$id]);
}

/**
 * Get product images
 * @param int $productId Product ID
 * @return array Images
 */
function getProductImages($productId) {
    return dbFetchAll("SELECT * FROM product_images WHERE product_id = ? ORDER BY sort_order ASC", [$productId]);
}

/**
 * Get product specifications
 * @param int $productId Product ID
 * @return array Specifications
 */
function getProductSpecs($productId) {
    return dbFetchAll("SELECT spec_name, spec_value FROM product_specs WHERE product_id = ? ORDER BY id", [$productId]);
}

/**
 * Get product reviews
 * @param int $productId Product ID
 * @param bool $approvedOnly Only approved reviews
 * @return array Reviews
 */
function getProductReviews($productId, $approvedOnly = true) {
    $sql = "
        SELECT r.*, u.name as user_name
        FROM reviews r
        JOIN users u ON r.user_id = u.id
        WHERE r.product_id = ?
    ";
    
    if ($approvedOnly) {
        $sql .= " AND r.status = 1";
    }
    
    $sql .= " ORDER BY r.created_at DESC";
    
    return dbFetchAll($sql, [$productId]);
}

/**
 * Get average product rating
 * @param int $productId Product ID
 * @return float Average rating
 */
function getProductRating($productId) {
    $result = dbFetchOne("
        SELECT AVG(rating) as avg_rating, COUNT(*) as review_count
        FROM reviews
        WHERE product_id = ? AND status = 1
    ", [$productId]);
    
    return [
        'average' => round($result['avg_rating'] ?? 0, 1),
        'count' => $result['review_count'] ?? 0
    ];
}

/**
 * Search products
 * @param string $query Search query
 * @param int $page Page number
 * @param int $perPage Items per page
 * @return array Results with products and pagination info
 */
function searchProducts($query, $page = 1, $perPage = 12) {
    $offset = ($page - 1) * $perPage;
    $searchTerm = "%{$query}%";
    
    $countSql = "
        SELECT COUNT(*) as total
        FROM products p
        LEFT JOIN categories c ON p.category_id = c.id
        LEFT JOIN brands b ON p.brand_id = b.id
        WHERE p.status = 1 AND (
            p.name LIKE ? OR
            p.sku LIKE ? OR
            p.description LIKE ? OR
            p.tags LIKE ? OR
            c.name LIKE ? OR
            b.name LIKE ?
        )
    ";
    
    $countResult = dbFetchOne($countSql, [$searchTerm, $searchTerm, $searchTerm, $searchTerm, $searchTerm, $searchTerm]);
    $total = $countResult['total'] ?? 0;
    
    $products = dbFetchAll("
        SELECT p.*, c.name as category_name, b.name as brand_name
        FROM products p
        LEFT JOIN categories c ON p.category_id = c.id
        LEFT JOIN brands b ON p.brand_id = b.id
        WHERE p.status = 1 AND (
            p.name LIKE ? OR
            p.sku LIKE ? OR
            p.description LIKE ? OR
            p.tags LIKE ? OR
            c.name LIKE ? OR
            b.name LIKE ?
        )
        ORDER BY p.created_at DESC
        LIMIT ? OFFSET ?
    ", [$searchTerm, $searchTerm, $searchTerm, $searchTerm, $searchTerm, $searchTerm, $perPage, $offset]);
    
    return [
        'products' => $products,
        'total' => $total,
        'page' => $page,
        'per_page' => $perPage,
        'total_pages' => ceil($total / $perPage)
    ];
}

/**
 * Flash message helper
 * @param string $type Message type (success, error, warning, info)
 * @param string $message Message text
 */
function setFlashMessage($type, $message) {
    $_SESSION['flash_message'] = [
        'type' => $type,
        'message' => $message
    ];
}

/**
 * Get and clear flash message
 * @return array|null Flash message
 */
function getFlashMessage() {
    if (isset($_SESSION['flash_message'])) {
        $message = $_SESSION['flash_message'];
        unset($_SESSION['flash_message']);
        return $message;
    }
    return null;
}

/**
 * Pakistani provinces
 * @return array Provinces
 */
function getPakistanProvinces() {
    return [
        'Punjab',
        'Sindh',
        'Khyber Pakhtunkhwa',
        'Balochistan',
        'Islamabad Capital Territory',
        'Gilgit-Baltistan',
        'Azad Kashmir'
    ];
}

/**
 * Major Pakistani cities
 * @return array Cities
 */
function getPakistanCities() {
    return [
        'Islamabad',
        'Rawalpindi',
        'Lahore',
        'Karachi',
        'Faisalabad',
        'Multan',
        'Peshawar',
        'Quetta',
        'Gujranwala',
        'Sialkot',
        'Hyderabad',
        'Bahawalpur',
        'Abbottabad',
        'Sargodha',
        'Sukkur',
        'Larkana',
        'Sheikhupura',
        'Jhang',
        'Gujrat',
        'Mardan'
    ];
}

/**
 * Order status labels
 * @return array Status labels
 */
function getOrderStatusLabels() {
    return [
        'pending' => 'Pending',
        'confirmed' => 'Confirmed',
        'processing' => 'Processing',
        'packed' => 'Packed',
        'shipped' => 'Shipped',
        'out_for_delivery' => 'Out for Delivery',
        'delivered' => 'Delivered',
        'cancelled' => 'Cancelled',
        'returned' => 'Returned'
    ];
}

/**
 * Payment method labels
 * @return array Method labels
 */
function getPaymentMethodLabels() {
    return [
        'cod' => 'Cash on Delivery',
        'bank_transfer' => 'Bank Transfer',
        'jazzcash' => 'JazzCash',
        'easypaisa' => 'Easypaisa'
    ];
}

/**
 * Time ago helper
 * @param string $datetime DateTime string
 * @return string Time ago text
 */
function timeAgo($datetime) {
    $timestamp = strtotime($datetime);
    $diff = time() - $timestamp;
    
    if ($diff < 60) {
        return 'Just now';
    } elseif ($diff < 3600) {
        $mins = floor($diff / 60);
        return $mins . ' minute' . ($mins > 1 ? 's' : '') . ' ago';
    } elseif ($diff < 86400) {
        $hours = floor($diff / 3600);
        return $hours . ' hour' . ($hours > 1 ? 's' : '') . ' ago';
    } elseif ($diff < 604800) {
        $days = floor($diff / 86400);
        return $days . ' day' . ($days > 1 ? 's' : '') . ' ago';
    } else {
        return date('M j, Y', $timestamp);
    }
}
