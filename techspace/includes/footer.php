<?php
/**
 * TechSpace Footer
 */
defined('TECHSPACE') or exit('Direct access not allowed');
?>
    </main>

    <!-- Footer -->
    <footer class="site-footer">
        <div class="container">
            <div class="row">
                <!-- About Column -->
                <div class="col-lg-4 col-md-6 mb-4">
                    <div class="footer-widget">
                        <h3 class="footer-title">
                            <i class="fas fa-microchip me-2"></i>TechSpace
                        </h3>
                        <p><?php echo getSetting('footer_info', 'TechSpace - Your trusted technology partner in Pakistan since 2024.'); ?></p>
                        <div class="social-links mt-3">
                            <a href="<?php echo getSetting('facebook_url', '#'); ?>" class="social-link" target="_blank" rel="noopener">
                                <i class="fab fa-facebook-f"></i>
                            </a>
                            <a href="<?php echo getSetting('instagram_url', '#'); ?>" class="social-link" target="_blank" rel="noopener">
                                <i class="fab fa-instagram"></i>
                            </a>
                            <a href="<?php echo getSetting('tiktok_url', '#'); ?>" class="social-link" target="_blank" rel="noopener">
                                <i class="fab fa-tiktok"></i>
                            </a>
                            <a href="<?php echo getSetting('youtube_url', '#'); ?>" class="social-link" target="_blank" rel="noopener">
                                <i class="fab fa-youtube"></i>
                            </a>
                        </div>
                    </div>
                </div>

                <!-- Customer Service -->
                <div class="col-lg-2 col-md-6 mb-4">
                    <div class="footer-widget">
                        <h4 class="footer-title">Customer Service</h4>
                        <ul class="footer-links">
                            <li><a href="<?php echo SITE_URL; ?>/contact.php">Contact Us</a></li>
                            <li><a href="<?php echo SITE_URL; ?>/page.php?slug=shipping">Shipping Info</a></li>
                            <li><a href="<?php echo SITE_URL; ?>/page.php?slug=returns">Returns Policy</a></li>
                            <li><a href="<?php echo SITE_URL; ?>/page.php?slug=warranty">Warranty</a></li>
                            <li><a href="<?php echo SITE_URL; ?>/page.php?slug=faq">FAQs</a></li>
                        </ul>
                    </div>
                </div>

                <!-- Account -->
                <div class="col-lg-2 col-md-6 mb-4">
                    <div class="footer-widget">
                        <h4 class="footer-title">Account</h4>
                        <ul class="footer-links">
                            <?php if (isLoggedIn()): ?>
                            <li><a href="<?php echo SITE_URL; ?>/account.php">My Account</a></li>
                            <li><a href="<?php echo SITE_URL; ?>/orders.php">My Orders</a></li>
                            <li><a href="<?php echo SITE_URL; ?>/wishlist.php">Wishlist</a></li>
                            <li><a href="<?php echo SITE_URL; ?>/logout.php">Logout</a></li>
                            <?php else: ?>
                            <li><a href="<?php echo SITE_URL; ?>/login.php">Login</a></li>
                            <li><a href="<?php echo SITE_URL; ?>/register.php">Register</a></li>
                            <li><a href="<?php echo SITE_URL; ?>/account.php">My Account</a></li>
                            <li><a href="<?php echo SITE_URL; ?>/orders.php">Order History</a></li>
                            <?php endif; ?>
                        </ul>
                    </div>
                </div>

                <!-- Categories -->
                <div class="col-lg-2 col-md-6 mb-4">
                    <div class="footer-widget">
                        <h4 class="footer-title">Categories</h4>
                        <ul class="footer-links">
                            <?php 
                            $categories = getCategories();
                            foreach (array_slice($categories, 0, 8) as $cat): 
                            ?>
                            <li>
                                <a href="<?php echo SITE_URL; ?>/shop.php?category=<?php echo htmlspecialchars($cat['slug']); ?>">
                                    <?php echo htmlspecialchars($cat['name']); ?>
                                </a>
                            </li>
                            <?php endforeach; ?>
                        </ul>
                    </div>
                </div>

                <!-- Contact Info -->
                <div class="col-lg-2 col-md-6 mb-4">
                    <div class="footer-widget">
                        <h4 class="footer-title">Contact</h4>
                        <ul class="footer-links">
                            <li>
                                <i class="fas fa-phone me-2"></i>
                                <?php echo getSetting('store_phone', '+92 300 1234567'); ?>
                            </li>
                            <li>
                                <i class="fas fa-envelope me-2"></i>
                                <?php echo getSetting('store_email', 'info@techspace.pk'); ?>
                            </li>
                            <li>
                                <i class="fas fa-map-marker-alt me-2"></i>
                                <?php echo getSetting('store_address', 'Islamabad, Pakistan'); ?>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>

            <!-- Footer Bottom -->
            <div class="footer-bottom">
                <div class="row align-items-center">
                    <div class="col-md-6 text-center text-md-start">
                        <p class="mb-0">&copy; <?php echo date('Y'); ?> TechSpace. All rights reserved.</p>
                    </div>
                    <div class="col-md-6 text-center text-md-end mt-2 mt-md-0">
                        <img src="data:image/svg+xml,<svg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 60 20'><rect fill='%23ffffff' opacity='0.2' width='60' height='20'/><text fill='%23ffffff' x='50%25' y='50%25' dominant-baseline='middle' text-anchor='middle' font-size='8'>Payment Icons</text></svg>" alt="Payment Methods" style="height: 30px;">
                    </div>
                </div>
            </div>
        </div>
    </footer>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
    <!-- Custom JS -->
    <script src="<?php echo SITE_URL; ?>/assets/js/script.js"></script>
</body>
</html>
