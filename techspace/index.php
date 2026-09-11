<?php
/**
 * TechSpace E-Commerce Platform
 * Homepage
 */

session_start();
define('TECHSPACE', true);

require_once __DIR__ . '/config/database.php';
require_once __DIR__ . '/includes/functions.php';

// Get data for homepage
$categories = getCategories();
$featuredProducts = getFeaturedProducts(8);
$newProducts = getNewProducts(8);

$pageTitle = 'TechSpace - Your Space for Smarter Technology';
$pageDescription = 'Discover the latest technology products in Pakistan. Laptops, smartphones, gaming gear, accessories and more at unbeatable prices.';

include __DIR__ . '/includes/header.php';
?>

<!-- Hero Section -->
<section class="hero-section">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-lg-6 hero-content">
                <h1 class="hero-title">Upgrade Your Tech Life</h1>
                <p class="hero-subtitle">Discover laptops, smartphones, gaming gear, accessories and more from Pakistan's most trusted technology store.</p>
                <div class="hero-buttons">
                    <a href="shop.php" class="btn btn-primary btn-lg">
                        <i class="fas fa-shopping-bag"></i> Shop Now
                    </a>
                    <a href="shop.php?sale=1" class="btn btn-secondary btn-lg">
                        <i class="fas fa-tag"></i> Explore Deals
                    </a>
                </div>
            </div>
            <div class="col-lg-6 text-center">
                <img src="assets/images/banners/hero-tech.png" alt="Tech Products" class="img-fluid" style="max-height: 400px;" onerror="this.src='data:image/svg+xml,<svg xmlns=%22http://www.w3.org/2000/svg%22 viewBox=%220 0 400 300%22><rect fill=%22%230066ff%22 width=%22400%22 height=%22300%22/><text fill=%22white%22 x=%2250%25%22 y=%2250%25%22 dominant-baseline=%22middle%22 text-anchor=%22middle%22 font-size=%2224%22>TechSpace Hero</text></svg>'">
            </div>
        </div>
    </div>
</section>

<!-- Categories Section -->
<section class="section">
    <div class="container">
        <div class="section-header">
            <div>
                <h2 class="section-title">Shop by Category</h2>
                <p class="section-subtitle">Browse our wide range of technology categories</p>
            </div>
            <a href="shop.php" class="view-all-link">
                View All <i class="fas fa-arrow-right"></i>
            </a>
        </div>
        
        <div class="row g-4">
            <?php foreach (array_slice($categories, 0, 8) as $category): ?>
            <div class="col-6 col-md-4 col-lg-3">
                <a href="shop.php?category=<?php echo htmlspecialchars($category['slug']); ?>" class="category-card">
                    <div class="category-icon">
                        <i class="fas fa-<?php echo getCategoryIcon($category['slug']); ?>"></i>
                    </div>
                    <h3 class="category-name mb-0"><?php echo htmlspecialchars($category['name']); ?></h3>
                </a>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<!-- Featured Products Section -->
<section class="section" style="background: white;">
    <div class="container">
        <div class="section-header">
            <div>
                <h2 class="section-title">Featured Products</h2>
                <p class="section-subtitle">Handpicked technology products just for you</p>
            </div>
            <a href="shop.php?featured=1" class="view-all-link">
                View All <i class="fas fa-arrow-right"></i>
            </a>
        </div>
        
        <div class="row g-4">
            <?php if (count($featuredProducts) > 0): ?>
                <?php foreach ($featuredProducts as $product): ?>
                <div class="col-6 col-md-4 col-lg-3">
                    <?php include __DIR__ . '/includes/product-card.php'; ?>
                </div>
                <?php endforeach; ?>
            <?php else: ?>
                <div class="col-12 text-center py-5">
                    <i class="fas fa-box-open" style="font-size: 4rem; color: var(--text-muted);"></i>
                    <p class="mt-3 text-muted">No featured products available</p>
                </div>
            <?php endif; ?>
        </div>
    </div>
</section>

<!-- New Arrivals Section -->
<section class="section">
    <div class="container">
        <div class="section-header">
            <div>
                <h2 class="section-title">New Arrivals</h2>
                <p class="section-subtitle">Check out the latest products in our store</p>
            </div>
            <a href="shop.php?new=1" class="view-all-link">
                View All <i class="fas fa-arrow-right"></i>
            </a>
        </div>
        
        <div class="row g-4">
            <?php if (count($newProducts) > 0): ?>
                <?php foreach ($newProducts as $product): ?>
                <div class="col-6 col-md-4 col-lg-3">
                    <?php include __DIR__ . '/includes/product-card.php'; ?>
                </div>
                <?php endforeach; ?>
            <?php else: ?>
                <div class="col-12 text-center py-5">
                    <i class="fas fa-box-open" style="font-size: 4rem; color: var(--text-muted);"></i>
                    <p class="mt-3 text-muted">No new products available</p>
                </div>
            <?php endif; ?>
        </div>
    </div>
</section>

<!-- Why Choose Us Section -->
<section class="section" style="background: white;">
    <div class="container">
        <div class="section-header text-center">
            <h2 class="section-title">Why Choose TechSpace?</h2>
            <p class="section-subtitle">We're committed to providing the best shopping experience</p>
        </div>
        
        <div class="row g-4">
            <div class="col-md-3 text-center">
                <div class="p-3">
                    <i class="fas fa-shipping-fast" style="font-size: 3rem; color: var(--secondary-color); margin-bottom: 1rem;"></i>
                    <h4>Fast Delivery</h4>
                    <p class="text-muted">Quick delivery across Pakistan within 3-7 business days</p>
                </div>
            </div>
            <div class="col-md-3 text-center">
                <div class="p-3">
                    <i class="fas fa-shield-alt" style="font-size: 3rem; color: var(--secondary-color); margin-bottom: 1rem;"></i>
                    <h4>Genuine Products</h4>
                    <p class="text-muted">100% authentic products with official warranties</p>
                </div>
            </div>
            <div class="col-md-3 text-center">
                <div class="p-3">
                    <i class="fas fa-headset" style="font-size: 3rem; color: var(--secondary-color); margin-bottom: 1rem;"></i>
                    <h4>Expert Support</h4>
                    <p class="text-muted">Dedicated customer support team ready to help</p>
                </div>
            </div>
            <div class="col-md-3 text-center">
                <div class="p-3">
                    <i class="fas fa-undo" style="font-size: 3rem; color: var(--secondary-color); margin-bottom: 1rem;"></i>
                    <h4>Easy Returns</h4>
                    <p class="text-muted">Hassle-free return policy for your peace of mind</p>
                </div>
            </div>
        </div>
    </div>
</section>

<?php include __DIR__ . '/includes/footer.php'; ?>

<?php
// Helper function for category icons
function getCategoryIcon($slug) {
    $icons = [
        'laptops' => 'laptop',
        'smartphones' => 'mobile-alt',
        'tablets' => 'tablet-alt',
        'gaming' => 'gamepad',
        'pc-components' => 'microchip',
        'monitors' => 'desktop',
        'keyboards' => 'keyboard',
        'mice' => 'mouse',
        'headphones' => 'headphones',
        'smart-watches' => 'clock',
        'cameras' => 'camera',
        'storage' => 'hdd',
        'networking' => 'wifi',
        'mobile-accessories' => 'plug',
        'power-banks' => 'battery-full'
    ];
    return $icons[$slug] ?? 'box';
}
?>
