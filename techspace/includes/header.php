<?php
/**
 * TechSpace Header
 */
defined('TECHSPACE') or exit('Direct access not allowed');

$cartCount = getCartCount();
$wishlistCount = getWishlistCount();
$currentUser = getCurrentUser();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $pageTitle ?? 'TechSpace - Your Space for Smarter Technology'; ?></title>
    <meta name="description" content="<?php echo $pageDescription ?? 'Discover the latest technology products in Pakistan. Laptops, smartphones, gaming gear, accessories and more.'; ?>">
    
    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- Custom Styles -->
    <link rel="stylesheet" href="<?php echo SITE_URL; ?>/assets/css/style.css">
    
    <script>
        const SITE_URL = '<?php echo SITE_URL; ?>';
    </script>
</head>
<body>
    <!-- Top Notification Bar -->
    <div class="top-notification">
        <div class="container">
            <i class="fas fa-truck me-2"></i>
            <?php echo getSetting('top_notification', 'Free delivery on orders above Rs. 5,000'); ?>
        </div>
    </div>

    <!-- Header -->
    <header class="site-header">
        <nav class="navbar navbar-expand-lg">
            <div class="container">
                <!-- Logo -->
                <a class="navbar-brand" href="<?php echo SITE_URL; ?>">
                    <i class="fas fa-microchip"></i>
                    TechSpace
                </a>
                
                <!-- Mobile Menu Toggle -->
                <button class="mobile-menu-toggle" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                    <i class="fas fa-bars"></i>
                </button>
                
                <!-- Navbar Collapse -->
                <div class="collapse navbar-collapse" id="navbarNav">
                    <!-- Main Navigation -->
                    <ul class="navbar-nav mx-auto">
                        <li class="nav-item">
                            <a class="nav-link <?php echo basename($_SERVER['PHP_SELF']) == 'index.php' ? 'active' : ''; ?>" href="<?php echo SITE_URL; ?>">
                                Home
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link <?php echo basename($_SERVER['PHP_SELF']) == 'shop.php' ? 'active' : ''; ?>" href="<?php echo SITE_URL; ?>/shop.php">
                                Shop
                            </a>
                        </li>
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown">
                                Categories
                            </a>
                            <ul class="dropdown-menu">
                                <?php 
                                $categories = getCategories();
                                foreach (array_slice($categories, 0, 10) as $cat): 
                                ?>
                                <li>
                                    <a class="dropdown-item" href="<?php echo SITE_URL; ?>/shop.php?category=<?php echo htmlspecialchars($cat['slug']); ?>">
                                        <?php echo htmlspecialchars($cat['name']); ?>
                                    </a>
                                </li>
                                <?php endforeach; ?>
                                <li><hr class="dropdown-divider"></li>
                                <li><a class="dropdown-item" href="<?php echo SITE_URL; ?>/shop.php">View All Categories</a></li>
                            </ul>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="<?php echo SITE_URL; ?>/shop.php?sale=1">Deals</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="<?php echo SITE_URL; ?>/shop.php?new=1">New Arrivals</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link <?php echo basename($_SERVER['PHP_SELF']) == 'about.php' ? 'active' : ''; ?>" href="<?php echo SITE_URL; ?>/about.php">About</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link <?php echo basename($_SERVER['PHP_SELF']) == 'contact.php' ? 'active' : ''; ?>" href="<?php echo SITE_URL; ?>/contact.php">Contact</a>
                        </li>
                    </ul>
                    
                    <!-- Search Bar -->
                    <form class="d-flex me-3" action="<?php echo SITE_URL; ?>/search.php" method="GET">
                        <div class="position-relative" style="width: 250px;">
                            <input class="form-control search-input" type="search" name="q" placeholder="Search products..." aria-label="Search">
                            <div class="search-results position-absolute w-100" style="top: 100%; z-index: 1000;"></div>
                        </div>
                        <button class="btn btn-primary ms-2" type="submit">
                            <i class="fas fa-search"></i>
                        </button>
                    </form>
                    
                    <!-- User Actions -->
                    <div class="navbar-actions">
                        <?php if ($currentUser): ?>
                        <a href="<?php echo SITE_URL; ?>/account.php" class="nav-action-btn">
                            <i class="fas fa-user"></i>
                            <span class="d-none d-lg-inline"><?php echo htmlspecialchars($currentUser['name']); ?></span>
                        </a>
                        <?php else: ?>
                        <a href="<?php echo SITE_URL; ?>/login.php" class="nav-action-btn">
                            <i class="fas fa-user"></i>
                            <span class="d-none d-lg-inline">Login</span>
                        </a>
                        <?php endif; ?>
                        
                        <a href="<?php echo SITE_URL; ?>/wishlist.php" class="nav-action-btn">
                            <i class="fas fa-heart"></i>
                            <span class="badge wishlist-count"><?php echo $wishlistCount; ?></span>
                        </a>
                        
                        <a href="<?php echo SITE_URL; ?>/cart.php" class="nav-action-btn">
                            <i class="fas fa-shopping-cart"></i>
                            <span class="badge cart-count"><?php echo $cartCount; ?></span>
                        </a>
                    </div>
                </div>
            </div>
        </nav>
    </header>

    <!-- Flash Message Container -->
    <?php $flashMessage = getFlashMessage(); ?>
    <?php if ($flashMessage): ?>
    <div id="flash-message" data-type="<?php echo htmlspecialchars($flashMessage['type']); ?>" data-message="<?php echo htmlspecialchars($flashMessage['message']); ?>" style="display: none;"></div>
    <?php endif; ?>

    <!-- Toast Container -->
    <div class="toast-container"></div>

    <!-- Main Content -->
    <main>
