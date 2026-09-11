<?php
/**
 * TechSpace - Shop Page
 */

session_start();
define('TECHSPACE', true);

require_once __DIR__ . '/config/database.php';
require_once __DIR__ . '/includes/functions.php';

// Pagination
$page = max(1, intval($_GET['page'] ?? 1));
$perPage = 12;
$offset = ($page - 1) * $perPage;

// Filters
$categorySlug = $_GET['category'] ?? '';
$brandId = intval($_GET['brand'] ?? 0);
$minPrice = floatval($_GET['min_price'] ?? 0);
$maxPrice = floatval($_GET['max_price'] ?? 1000000);
$sortBy = $_GET['sort'] ?? 'newest';
$isFeatured = isset($_GET['featured']);
$isNew = isset($_GET['new']);
$isSale = isset($_GET['sale']);
$searchQuery = sanitize($_GET['q'] ?? '');

// Build query conditions
$conditions = ['p.status = 1'];
$params = [];

if ($categorySlug) {
    $category = getCategoryBySlug($categorySlug);
    if ($category) {
        $conditions[] = 'p.category_id = ?';
        $params[] = $category['id'];
    }
}

if ($brandId > 0) {
    $conditions[] = 'p.brand_id = ?';
    $params[] = $brandId;
}

if ($minPrice > 0 || $maxPrice < 1000000) {
    $conditions[] = '(COALESCE(p.sale_price, p.price) BETWEEN ? AND ?)';
    $params[] = $minPrice;
    $params[] = $maxPrice;
}

if ($isFeatured) {
    $conditions[] = 'p.is_featured = 1';
}

if ($isNew) {
    $conditions[] = 'p.is_new = 1';
}

if ($isSale) {
    $conditions[] = 'p.sale_price IS NOT NULL AND p.sale_price < p.price';
}

if ($searchQuery) {
    $conditions[] = '(p.name LIKE ? OR p.description LIKE ? OR p.tags LIKE ? OR p.sku LIKE ?)';
    $searchTerm = "%{$searchQuery}%";
    $params[] = $searchTerm;
    $params[] = $searchTerm;
    $params[] = $searchTerm;
    $params[] = $searchTerm;
}

$whereClause = implode(' AND ', $conditions);

// Sorting
$orderBy = 'p.created_at DESC';
switch ($sortBy) {
    case 'price_asc':
        $orderBy = 'COALESCE(p.sale_price, p.price) ASC';
        break;
    case 'price_desc':
        $orderBy = 'COALESCE(p.sale_price, p.price) DESC';
        break;
    case 'popular':
        $orderBy = 'p.sales_count DESC, p.views DESC';
        break;
    case 'rating':
        $orderBy = 'rating DESC';
        break;
    default:
        $orderBy = 'p.created_at DESC';
}

// Count total products
$countSql = "
    SELECT COUNT(*) as total
    FROM products p
    LEFT JOIN categories c ON p.category_id = c.id
    LEFT JOIN brands b ON p.brand_id = b.id
    WHERE {$whereClause}
";
$totalResult = dbFetchOne($countSql, $params);
$totalProducts = $totalResult['total'] ?? 0;
$totalPages = ceil($totalProducts / $perPage);

// Get products
$sql = "
    SELECT p.*, c.name as category_name, c.slug as category_slug, b.name as brand_name,
           (SELECT AVG(rating) FROM reviews WHERE product_id = p.id AND status = 1) as rating
    FROM products p
    LEFT JOIN categories c ON p.category_id = c.id
    LEFT JOIN brands b ON p.brand_id = b.id
    WHERE {$whereClause}
    ORDER BY {$orderBy}
    LIMIT ? OFFSET ?
";
$params[] = $perPage;
$params[] = $offset;
$products = dbFetchAll($sql, $params);

// Get all categories for filter
$categories = getCategories();

// Get all brands for filter
$brands = dbFetchAll("SELECT * FROM brands WHERE status = 1 ORDER BY name");

$pageTitle = ($category ? htmlspecialchars($category['name']) . ' - ' : '') . 'Shop - TechSpace';
$pageDescription = 'Browse our collection of technology products.';

include __DIR__ . '/includes/header.php';
?>

<div class="container py-4">
    <!-- Breadcrumb -->
    <nav aria-label="breadcrumb" class="mb-4">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="<?php echo SITE_URL; ?>">Home</a></li>
            <li class="breadcrumb-item active">Shop</li>
        </ol>
    </nav>
    
    <div class="row">
        <!-- Sidebar Filters -->
        <aside class="col-lg-3 mb-4">
            <div class="card mb-3">
                <div class="card-body">
                    <h5 class="mb-3"><i class="fas fa-filter me-2"></i>Filters</h5>
                    
                    <!-- Category Filter -->
                    <div class="mb-3">
                        <h6 class="fw-semibold mb-2">Categories</h6>
                        <div class="list-group list-group-flush">
                            <a href="shop.php" class="list-group-item list-group-item-action <?php echo !$categorySlug ? 'active' : ''; ?>">
                                All Categories
                            </a>
                            <?php foreach ($categories as $cat): ?>
                            <a href="shop.php?category=<?php echo htmlspecialchars($cat['slug']); ?>" 
                               class="list-group-item list-group-item-action <?php echo $categorySlug == $cat['slug'] ? 'active' : ''; ?>">
                                <?php echo htmlspecialchars($cat['name']); ?>
                            </a>
                            <?php endforeach; ?>
                        </div>
                    </div>
                    
                    <!-- Price Range -->
                    <div class="mb-3">
                        <h6 class="fw-semibold mb-2">Price Range</h6>
                        <form method="GET" action="shop.php">
                            <?php if ($categorySlug): ?>
                            <input type="hidden" name="category" value="<?php echo htmlspecialchars($categorySlug); ?>">
                            <?php endif; ?>
                            <div class="d-flex gap-2 mb-2">
                                <input type="number" name="min_price" class="form-control form-control-sm" placeholder="Min" value="<?php echo $minPrice; ?>">
                                <span class="align-self-center">-</span>
                                <input type="number" name="max_price" class="form-control form-control-sm" placeholder="Max" value="<?php echo $maxPrice; ?>">
                            </div>
                            <button type="submit" class="btn btn-primary btn-sm w-100">Apply</button>
                        </form>
                    </div>
                    
                    <!-- Special Filters -->
                    <div class="mb-3">
                        <h6 class="fw-semibold mb-2">Special</h6>
                        <div class="form-check mb-2">
                            <input class="form-check-input" type="checkbox" id="filterFeatured" 
                                   onchange="window.location='shop.php?featured=1'" <?php echo $isFeatured ? 'checked' : ''; ?>>
                            <label class="form-check-label" for="filterFeatured">Featured Products</label>
                        </div>
                        <div class="form-check mb-2">
                            <input class="form-check-input" type="checkbox" id="filterNew" 
                                   onchange="window.location='shop.php?new=1'" <?php echo $isNew ? 'checked' : ''; ?>>
                            <label class="form-check-label" for="filterNew">New Arrivals</label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" id="filterSale" 
                                   onchange="window.location='shop.php?sale=1'" <?php echo $isSale ? 'checked' : ''; ?>>
                            <label class="form-check-label" for="filterSale">On Sale</label>
                        </div>
                    </div>
                </div>
            </div>
        </aside>
        
        <!-- Products Grid -->
        <div class="col-lg-9">
            <!-- Toolbar -->
            <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-2">
                <div>
                    <span class="text-muted"><?php echo $totalProducts; ?> products found</span>
                </div>
                
                <div class="d-flex gap-2 align-items-center">
                    <label class="text-muted">Sort by:</label>
                    <select class="form-select form-select-sm" style="width: auto;" onchange="location.href=this.value">
                        <option value="shop.php<?php echo $categorySlug ? '?category='.$categorySlug : ''; ?>" <?php echo $sortBy == 'newest' ? 'selected' : ''; ?>>Newest</option>
                        <option value="shop.php?sort=price_asc<?php echo $categorySlug ? '&category='.$categorySlug : ''; ?>" <?php echo $sortBy == 'price_asc' ? 'selected' : ''; ?>>Price: Low to High</option>
                        <option value="shop.php?sort=price_desc<?php echo $categorySlug ? '&category='.$categorySlug : ''; ?>" <?php echo $sortBy == 'price_desc' ? 'selected' : ''; ?>>Price: High to Low</option>
                        <option value="shop.php?sort=popular<?php echo $categorySlug ? '&category='.$categorySlug : ''; ?>" <?php echo $sortBy == 'popular' ? 'selected' : ''; ?>>Most Popular</option>
                    </select>
                </div>
            </div>
            
            <!-- Products -->
            <?php if (count($products) > 0): ?>
            <div class="row g-4">
                <?php foreach ($products as $product): ?>
                <div class="col-md-6 col-lg-4">
                    <?php include __DIR__ . '/includes/product-card.php'; ?>
                </div>
                <?php endforeach; ?>
            </div>
            
            <!-- Pagination -->
            <?php if ($totalPages > 1): ?>
            <nav class="mt-4">
                <ul class="pagination justify-content-center">
                    <?php if ($page > 1): ?>
                    <li class="page-item">
                        <a class="page-link" href="shop.php?page=<?php echo $page - 1; ?><?php echo $categorySlug ? '&category='.$categorySlug : ''; ?>">Previous</a>
                    </li>
                    <?php endif; ?>
                    
                    <?php for ($i = 1; $i <= $totalPages; $i++): ?>
                    <li class="page-item <?php echo $i == $page ? 'active' : ''; ?>">
                        <a class="page-link" href="shop.php?page=<?php echo $i; ?><?php echo $categorySlug ? '&category='.$categorySlug : ''; ?>"><?php echo $i; ?></a>
                    </li>
                    <?php endfor; ?>
                    
                    <?php if ($page < $totalPages): ?>
                    <li class="page-item">
                        <a class="page-link" href="shop.php?page=<?php echo $page + 1; ?><?php echo $categorySlug ? '&category='.$categorySlug : ''; ?>">Next</a>
                    </li>
                    <?php endif; ?>
                </ul>
            </nav>
            <?php endif; ?>
            
            <?php else: ?>
            <div class="text-center py-5">
                <i class="fas fa-search" style="font-size: 4rem; color: var(--text-muted);"></i>
                <h3 class="mt-3">No products found</h3>
                <p class="text-muted">Try adjusting your filters or search criteria</p>
                <a href="shop.php" class="btn btn-primary mt-2">View All Products</a>
            </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<?php include __DIR__ . '/includes/footer.php'; ?>
