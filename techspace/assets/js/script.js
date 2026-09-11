/**
 * TechSpace - Main JavaScript
 * Pakistani Technology E-Commerce Store
 */

document.addEventListener('DOMContentLoaded', function() {
    // Initialize all components
    initMobileMenu();
    initCartFunctions();
    initWishlistFunctions();
    initSearchFunctions();
    initToastNotifications();
    initProductGallery();
    initQuantitySelector();
});

/**
 * Mobile Menu Toggle
 */
function initMobileMenu() {
    const menuToggle = document.querySelector('.mobile-menu-toggle');
    const navbarCollapse = document.querySelector('.navbar-collapse');
    
    if (menuToggle && navbarCollapse) {
        menuToggle.addEventListener('click', function() {
            navbarCollapse.classList.toggle('show');
        });
        
        // Close menu when clicking outside
        document.addEventListener('click', function(e) {
            if (!menuToggle.contains(e.target) && !navbarCollapse.contains(e.target)) {
                navbarCollapse.classList.remove('show');
            }
        });
    }
}

/**
 * Cart Functions with AJAX
 */
function initCartFunctions() {
    // Add to cart buttons
    document.querySelectorAll('.add-to-cart-btn').forEach(btn => {
        btn.addEventListener('click', function(e) {
            e.preventDefault();
            const productId = this.dataset.productId;
            const quantity = this.dataset.quantity || 1;
            addToCart(productId, quantity);
        });
    });
    
    // Cart quantity update
    document.querySelectorAll('.cart-quantity-update').forEach(input => {
        input.addEventListener('change', function() {
            const cartItemId = this.dataset.cartItemId;
            const quantity = this.value;
            updateCartItem(cartItemId, quantity);
        });
    });
    
    // Remove from cart
    document.querySelectorAll('.remove-from-cart').forEach(btn => {
        btn.addEventListener('click', function(e) {
            e.preventDefault();
            const cartItemId = this.dataset.cartItemId;
            removeFromCart(cartItemId);
        });
    });
}

/**
 * Add product to cart
 */
function addToCart(productId, quantity = 1) {
    fetch(SITE_URL + '/ajax/cart.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded',
        },
        body: `action=add&product_id=${productId}&quantity=${quantity}`
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            showToast('success', data.message);
            updateCartCount(data.cartCount);
        } else {
            showToast('error', data.message);
        }
    })
    .catch(error => {
        console.error('Error:', error);
        showToast('error', 'Failed to add item to cart');
    });
}

/**
 * Update cart item
 */
function updateCartItem(cartItemId, quantity) {
    if (quantity < 1) {
        quantity = 1;
    }
    
    fetch(SITE_URL + '/ajax/cart.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded',
        },
        body: `action=update&cart_item_id=${cartItemId}&quantity=${quantity}`
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            location.reload();
        } else {
            showToast('error', data.message);
        }
    })
    .catch(error => {
        console.error('Error:', error);
        showToast('error', 'Failed to update cart');
    });
}

/**
 * Remove from cart
 */
function removeFromCart(cartItemId) {
    if (!confirm('Are you sure you want to remove this item?')) {
        return;
    }
    
    fetch(SITE_URL + '/ajax/cart.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded',
        },
        body: `action=remove&cart_item_id=${cartItemId}`
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            showToast('success', data.message);
            location.reload();
        } else {
            showToast('error', data.message);
        }
    })
    .catch(error => {
        console.error('Error:', error);
        showToast('error', 'Failed to remove item');
    });
}

/**
 * Update cart count display
 */
function updateCartCount(count) {
    document.querySelectorAll('.cart-count').forEach(el => {
        el.textContent = count;
        if (count > 0) {
            el.style.display = 'inline-block';
        } else {
            el.style.display = 'none';
        }
    });
}

/**
 * Wishlist Functions
 */
function initWishlistFunctions() {
    document.querySelectorAll('.add-to-wishlist-btn').forEach(btn => {
        btn.addEventListener('click', function(e) {
            e.preventDefault();
            const productId = this.dataset.productId;
            toggleWishlist(productId);
        });
    });
}

/**
 * Toggle wishlist item
 */
function toggleWishlist(productId) {
    fetch(SITE_URL + '/ajax/wishlist.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded',
        },
        body: `action=toggle&product_id=${productId}`
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            showToast('success', data.message);
            updateWishlistCount(data.count);
        } else {
            showToast('error', data.message);
        }
    })
    .catch(error => {
        console.error('Error:', error);
        showToast('error', 'Please login to use wishlist');
    });
}

/**
 * Update wishlist count
 */
function updateWishlistCount(count) {
    document.querySelectorAll('.wishlist-count').forEach(el => {
        el.textContent = count;
    });
}

/**
 * Search Functions
 */
function initSearchFunctions() {
    const searchInput = document.querySelector('.search-input');
    const searchResults = document.querySelector('.search-results');
    
    if (searchInput && searchResults) {
        let debounceTimer;
        
        searchInput.addEventListener('input', function() {
            clearTimeout(debounceTimer);
            const query = this.value.trim();
            
            if (query.length >= 2) {
                debounceTimer = setTimeout(() => {
                    performSearch(query);
                }, 300);
            } else {
                searchResults.classList.add('hidden');
            }
        });
        
        // Hide results when clicking outside
        document.addEventListener('click', function(e) {
            if (!searchInput.contains(e.target) && !searchResults.contains(e.target)) {
                searchResults.classList.add('hidden');
            }
        });
    }
}

/**
 * Perform search
 */
function performSearch(query) {
    fetch(SITE_URL + `/ajax/search.php?q=${encodeURIComponent(query)}`)
    .then(response => response.json())
    .then(data => {
        const searchResults = document.querySelector('.search-results');
        if (data.products && data.products.length > 0) {
            let html = '<div class="search-dropdown">';
            data.products.forEach(product => {
                html += `
                    <a href="${SITE_URL}/product.php?slug=${product.slug}" class="search-item">
                        <img src="${SITE_URL}/uploads/products/${product.featured_image || 'placeholder.jpg'}" alt="${product.name}">
                        <div class="search-item-info">
                            <span class="search-item-name">${product.name}</span>
                            <span class="search-item-price">${formatPrice(product.sale_price || product.price)}</span>
                        </div>
                    </a>
                `;
            });
            html += '</div>';
            searchResults.innerHTML = html;
            searchResults.classList.remove('hidden');
        } else {
            searchResults.classList.add('hidden');
        }
    })
    .catch(error => {
        console.error('Search error:', error);
    });
}

/**
 * Toast Notifications
 */
function initToastNotifications() {
    // Check for flash messages from PHP
    const flashMessage = document.getElementById('flash-message');
    if (flashMessage) {
        const type = flashMessage.dataset.type;
        const message = flashMessage.dataset.message;
        showToast(type, message);
        flashMessage.remove();
    }
}

/**
 * Show toast notification
 */
function showToast(type, message) {
    const container = document.querySelector('.toast-container') || createToastContainer();
    
    const toast = document.createElement('div');
    toast.className = `toast toast-${type}`;
    toast.innerHTML = `
        <i class="fas fa-${getToastIcon(type)}"></i>
        <span>${message}</span>
    `;
    
    container.appendChild(toast);
    
    // Auto remove after 4 seconds
    setTimeout(() => {
        toast.style.animation = 'slideInRight 0.3s ease reverse';
        setTimeout(() => toast.remove(), 300);
    }, 4000);
}

/**
 * Create toast container if not exists
 */
function createToastContainer() {
    const container = document.createElement('div');
    container.className = 'toast-container';
    document.body.appendChild(container);
    return container;
}

/**
 * Get toast icon based on type
 */
function getToastIcon(type) {
    const icons = {
        success: 'check-circle',
        error: 'exclamation-circle',
        warning: 'exclamation-triangle',
        info: 'info-circle'
    };
    return icons[type] || 'info-circle';
}

/**
 * Product Gallery
 */
function initProductGallery() {
    const thumbnails = document.querySelectorAll('.product-thumbnail');
    const mainImage = document.querySelector('.product-main-image');
    
    if (thumbnails.length && mainImage) {
        thumbnails.forEach(thumb => {
            thumb.addEventListener('click', function() {
                const imageSrc = this.dataset.image;
                mainImage.src = imageSrc;
                
                thumbnails.forEach(t => t.classList.remove('active'));
                this.classList.add('active');
            });
        });
    }
}

/**
 * Quantity Selector
 */
function initQuantitySelector() {
    document.querySelectorAll('.quantity-selector').forEach(selector => {
        const minusBtn = selector.querySelector('.qty-minus');
        const plusBtn = selector.querySelector('.qty-plus');
        const input = selector.querySelector('.qty-input');
        
        if (minusBtn && plusBtn && input) {
            minusBtn.addEventListener('click', function() {
                let value = parseInt(input.value) || 1;
                if (value > 1) {
                    input.value = value - 1;
                    input.dispatchEvent(new Event('change'));
                }
            });
            
            plusBtn.addEventListener('click', function() {
                let value = parseInt(input.value) || 1;
                input.value = value + 1;
                input.dispatchEvent(new Event('change'));
            });
        }
    });
}

/**
 * Format price helper
 */
function formatPrice(price) {
    return 'Rs. ' + Number(price).toLocaleString();
}

/**
 * Confirm dialog helper
 */
function confirmAction(message, callback) {
    if (confirm(message)) {
        callback();
    }
}

/**
 * Form validation helper
 */
function validateForm(formId) {
    const form = document.getElementById(formId);
    if (!form) return false;
    
    let isValid = true;
    const requiredFields = form.querySelectorAll('[required]');
    
    requiredFields.forEach(field => {
        if (!field.value.trim()) {
            field.classList.add('is-invalid');
            isValid = false;
        } else {
            field.classList.remove('is-invalid');
        }
        
        // Email validation
        if (field.type === 'email' && field.value) {
            const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
            if (!emailRegex.test(field.value)) {
                field.classList.add('is-invalid');
                isValid = false;
            }
        }
    });
    
    return isValid;
}

/**
 * Coupon code application
 */
function applyCouponCode() {
    const couponCode = document.getElementById('coupon-code')?.value;
    if (!couponCode) {
        showToast('error', 'Please enter a coupon code');
        return;
    }
    
    fetch(SITE_URL + '/ajax/coupon.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded',
        },
        body: `action=apply&code=${couponCode}`
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            showToast('success', data.message);
            location.reload();
        } else {
            showToast('error', data.message);
        }
    })
    .catch(error => {
        console.error('Error:', error);
        showToast('error', 'Failed to apply coupon');
    });
}

/**
 * Order status update (admin)
 */
function updateOrderStatus(orderId, status) {
    fetch(SITE_URL + '/admin/ajax/orders.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded',
        },
        body: `action=update_status&order_id=${orderId}&status=${status}`
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            showToast('success', 'Order status updated');
            location.reload();
        } else {
            showToast('error', data.message);
        }
    })
    .catch(error => {
        console.error('Error:', error);
        showToast('error', 'Failed to update order status');
    });
}

// Make functions available globally
window.addToCart = addToCart;
window.updateCartItem = updateCartItem;
window.removeFromCart = removeFromCart;
window.toggleWishlist = toggleWishlist;
window.showToast = showToast;
window.applyCouponCode = applyCouponCode;
window.updateOrderStatus = updateOrderStatus;
window.validateForm = validateForm;
