<?php
/**
 * Product Card Component
 */
defined('TECHSPACE') or exit('Direct access not allowed');

if (!isset($product)) {
    return;
}

$price = getProductPrice($product);
$originalPrice = $product['price'];
$discount = calculateDiscount($originalPrice, $price);
$rating = getProductRating($product['id']);
?>

<div class="product-card h-100">
    <div class="product-image-wrapper">
        <img src="<?php echo SITE_URL; ?>/uploads/products/<?php echo htmlspecialchars($product['featured_image'] ?? 'placeholder.jpg'); ?>" 
             alt="<?php echo htmlspecialchars($product['name']); ?>" 
             class="product-image"
             onerror="this.src='data:image/svg+xml,<svg xmlns=%22http://www.w3.org/2000/svg%22 viewBox=%220 0 300 300%22><rect fill=%22%23f5f7fa%22 width=%22300%22 height=%22300%22/><text fill=%22%23ccc%22 x=%2250%25%22 y=%2250%25%22 dominant-baseline=%22middle%22 text-anchor=%22middle%22 font-size=%2216%22>No Image</text></svg>'">
        
        <!-- Product Badges -->
        <div class="product-badges">
            <?php if ($discount > 0): ?>
            <span class="product-badge badge-sale">-<?php echo number_format($discount); ?>%</span>
            <?php endif; ?>
            <?php if ($product['is_new']): ?>
            <span class="product-badge badge-new">NEW</span>
            <?php endif; ?>
            <?php if ($product['is_featured']): ?>
            <span class="product-badge badge-featured">FEATURED</span>
            <?php endif; ?>
        </div>
        
        <!-- Product Actions -->
        <div class="product-actions">
            <button class="product-action-btn add-to-wishlist-btn" data-product-id="<?php echo $product['id']; ?>" title="Add to Wishlist">
                <i class="fas fa-heart"></i>
            </button>
            <a href="<?php echo SITE_URL; ?>/product.php?slug=<?php echo htmlspecialchars($product['slug']); ?>" class="product-action-btn" title="Quick View">
                <i class="fas fa-eye"></i>
            </a>
        </div>
    </div>
    
    <div class="product-info">
        <span class="product-category"><?php echo htmlspecialchars($product['category_name'] ?? 'Technology'); ?></span>
        
        <h3 class="product-title">
            <a href="<?php echo SITE_URL; ?>/product.php?slug=<?php echo htmlspecialchars($product['slug']); ?>">
                <?php echo htmlspecialchars($product['name']); ?>
            </a>
        </h3>
        
        <?php if ($rating['count'] > 0): ?>
        <div class="product-rating">
            <div class="stars">
                <?php for ($i = 1; $i <= 5; $i++): ?>
                    <i class="fas fa-star<?php echo $i <= $rating['average'] ? '' : '-o'; ?>"></i>
                <?php endfor; ?>
            </div>
            <span class="rating-count">(<?php echo $rating['count']; ?>)</span>
        </div>
        <?php endif; ?>
        
        <div class="product-price">
            <span class="current-price"><?php echo formatPrice($price); ?></span>
            <?php if ($discount > 0): ?>
            <span class="original-price"><?php echo formatPrice($originalPrice); ?></span>
            <?php endif; ?>
        </div>
        
        <button class="btn btn-primary w-100 mt-2 add-to-cart-btn" data-product-id="<?php echo $product['id']; ?>">
            <i class="fas fa-shopping-cart"></i> Add to Cart
        </button>
    </div>
</div>
