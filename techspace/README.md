# TechSpace - Pakistani E-Commerce Platform

**Your Space for Smarter Technology**

A complete, modern, professional e-commerce website built specifically for the Pakistani market.

---

## 📋 Table of Contents

1. [Requirements](#requirements)
2. [Installation](#installation)
3. [Database Setup](#database-setup)
4. [Configuration](#configuration)
5. [Running the Website](#running-the-website)
6. [Admin Login](#admin-login)
7. [Project Structure](#project-structure)
8. [Features](#features)
9. [Adding Products](#adding-products)
10. [Store Settings](#store-settings)
11. [Payment Integration](#payment-integration)
12. [Security](#security)
13. [Troubleshooting](#troubleshooting)

---

## ✅ Requirements

### Server Requirements
- PHP 7.4 or higher
- MySQL 5.7 or higher / MariaDB 10.3+
- Apache with mod_rewrite enabled
- PHP Extensions: PDO, PDO_MySQL, GD, fileinfo

### For XAMPP/WAMP
- XAMPP 8.0+ or WAMP Server 3.2+
- Ensure PHP and MySQL services are running

---

## 🚀 Installation

### Step 1: Copy Files to XAMPP/WAMP

1. Copy the entire `techspace` folder to your web root:
   - **XAMPP Windows**: `C:\xampp\htdocs\techspace`
   - **XAMPP Mac/Linux**: `/Applications/XAMPP/htdocs/techspace` or `/opt/lampp/htdocs/techspace`
   - **WAMP**: `C:\wamp64\www\techspace`

### Step 2: Start Services

1. Open XAMPP Control Panel
2. Start **Apache** service
3. Start **MySQL** service

---

## 🗄️ Database Setup

### Step 1: Create Database

1. Open phpMyAdmin in your browser:
   ```
   http://localhost/phpmyadmin
   ```

2. Click on "New" to create a new database

3. Enter database name: `techspace`

4. Select collation: `utf8mb4_unicode_ci`

5. Click "Create"

### Step 2: Import SQL File

1. Select the `techspace` database from the left sidebar

2. Click on "Import" tab

3. Click "Choose File" and select:
   ```
   techspace/database/techspace.sql
   ```

4. Click "Go" to import

5. Wait for success message: "Import has been successfully finished"

### Alternative: Command Line

```bash
mysql -u root -p techspace < /path/to/techspace/database/techspace.sql
```

---

## ⚙️ Configuration

### Database Configuration

Open `config/database.php` and verify settings:

```php
define('DB_HOST', 'localhost');
define('DB_NAME', 'techspace');
define('DB_USER', 'root');
define('DB_PASS', '');  // Empty for XAMPP default
```

### Site URL

If your installation is in a different location, update:

```php
define('SITE_URL', 'http://localhost/techspace');
```

For production:
```php
define('SITE_URL', 'https://yourdomain.com');
```

---

## ▶️ Running the Website

### Access the Frontend

Open your browser and navigate to:
```
http://localhost/techspace
```

### Access Admin Panel

Navigate to:
```
http://localhost/techspace/admin
```

---

## 🔐 Admin Login

### Default Credentials

**⚠️ IMPORTANT: Change these immediately after first login!**

```
Email: admin@techspace.pk
Password: Admin@12345
```

### Changing Admin Password

1. Login to admin panel
2. Go to Settings → Admin Profile
3. Enter current password
4. Enter new password (minimum 8 characters)
5. Click "Update Password"

---

## 📁 Project Structure

```
techspace/
│
├── index.php              # Homepage
├── shop.php               # Product listing with filters
├── product.php            # Single product page
├── cart.php               # Shopping cart
├── checkout.php           # Checkout page
├── login.php              # User login
├── register.php           # User registration
├── account.php            # Customer dashboard
├── orders.php             # Order history
├── order-details.php      # Single order view
├── wishlist.php           # Wishlist page
├── search.php             # Search results
├── contact.php            # Contact form
├── about.php              # About page
│
├── config/
│   └── database.php       # Database configuration & connection
│
├── admin/                 # Admin panel
│   ├── index.php          # Admin redirect
│   ├── login.php          # Admin login
│   ├── dashboard.php      # Admin dashboard
│   ├── products.php       # Product management
│   ├── add-product.php    # Add new product
│   ├── edit-product.php   # Edit product
│   ├── categories.php     # Category management
│   ├── orders.php         # Order management
│   ├── customers.php      # Customer management
│   ├── coupons.php        # Coupon management
│   ├── reviews.php        # Review moderation
│   └── settings.php       # Store settings
│
├── includes/              # Reusable components
│   ├── header.php         # Site header
│   ├── footer.php         # Site footer
│   ├── functions.php      # Helper functions
│   └── product-card.php   # Product card template
│
├── assets/
│   ├── css/
│   │   └── style.css      # Main stylesheet
│   ├── js/
│   │   └── script.js      # Main JavaScript
│   └── images/
│       ├── products/      # Product images
│       ├── banners/       # Banner images
│       └── categories/    # Category images
│
├── uploads/
│   ├── products/          # Uploaded product images
│   └── users/             # User uploaded files
│
├── database/
│   └── techspace.sql      # Database schema & sample data
│
└── README.md              # This file
```

---

## ✨ Features

### Customer Features
- ✅ User Registration & Login
- ✅ Product Browsing & Search
- ✅ Advanced Filtering (Category, Price, Brand)
- ✅ Product Reviews & Ratings
- ✅ Shopping Cart (AJAX)
- ✅ Wishlist
- ✅ Secure Checkout
- ✅ Multiple Payment Methods (COD, Bank Transfer, JazzCash, Easypaisa)
- ✅ Order Tracking
- ✅ Account Dashboard

### Admin Features
- ✅ Sales Dashboard with Analytics
- ✅ Product Management (CRUD)
- ✅ Category Management
- ✅ Order Management with Status Updates
- ✅ Customer Management
- ✅ Coupon System
- ✅ Review Moderation
- ✅ Inventory Management
- ✅ Store Settings
- ✅ Payment Configuration

### Pakistani-Specific Features
- 🇵🇰 PKR Currency (Rs.)
- 🇵🇰 Pakistani Provinces & Cities
- 🇵🇰 Local Payment Methods
- 🇵🇰 Pakistan Phone Format (+92)
- 🇵🇰 Localized Shipping Terms

---

## ➕ Adding Products

### Via Admin Panel

1. Login to admin: `http://localhost/techspace/admin`

2. Navigate to **Products → Add Product**

3. Fill in product details:
   - Product Name
   - SKU (unique identifier)
   - Category
   - Brand
   - Price & Sale Price
   - Stock Quantity
   - Description
   - Upload Images
   - Specifications

4. Set product status:
   - Featured
   - New Arrival
   - Active/Inactive

5. Click "Save Product"

### Product Image Guidelines
- Recommended size: 800x800px minimum
- Supported formats: JPG, PNG, WebP
- Maximum file size: 5MB
- First image will be used as featured image

---

## ⚙️ Store Settings

Access via Admin Panel → Settings

### Store Information
- Store Name
- Tagline
- Contact Email
- Phone Number
- Physical Address

### Social Media Links
- Facebook
- Instagram
- TikTok
- YouTube

### Payment Settings
- Bank Details (Name, Account Title, Number, IBAN)
- JazzCash Number
- Easypaisa Number
- Cash on Delivery Availability

### Shipping Settings
- Standard Shipping Fee (default: Rs. 250)
- Free Shipping Threshold (default: Rs. 5,000)

### Website Settings
- Top Notification Bar Text
- Footer Information
- Homepage Banner

---

## 💳 Payment Integration

### Current Implementation

The system includes placeholder integration for:

1. **Cash on Delivery (COD)** - Fully functional
2. **Bank Transfer** - Shows bank details for manual transfer
3. **JazzCash** - UI ready for API integration
4. **Easypaisa** - UI ready for API integration

### Integrating Real Payment APIs

#### JazzCash API Integration

To integrate real JazzCash payments:

1. Get API credentials from JazzCash merchant portal
2. Update `includes/payment-handlers.php`:

```php
// Example structure for JazzCash integration
$jazzcashConfig = [
    'merchant_id' => 'YOUR_MERCHANT_ID',
    'password' => 'YOUR_PASSWORD',
    'api_key' => 'YOUR_API_KEY',
    'sandbox' => true,  // Set to false for production
];
```

3. Implement payment request in checkout processing
4. Handle callback/webhook for payment confirmation

#### Easypaisa API Integration

Similar process:

1. Register as Easypaisa merchant
2. Obtain API credentials
3. Implement payment flow
4. Handle transaction verification

#### PayFast Integration

For credit/debit card payments:

1. Sign up at PayFast.pk
2. Get merchant credentials
3. Integrate payment gateway
4. Configure webhook URL

---

## 🔒 Security

### Implemented Security Measures

- ✅ Password Hashing (bcrypt via `password_hash()`)
- ✅ Prepared Statements (PDO) - SQL Injection Prevention
- ✅ CSRF Token Protection
- ✅ XSS Prevention (htmlspecialchars)
- ✅ Session Security
- ✅ Input Validation
- ✅ File Upload Validation
- ✅ Access Control (Admin Authentication)

### Production Security Checklist

Before going live:

1. ☐ Change default admin password
2. ☐ Update database credentials
3. ☐ Enable HTTPS/SSL
4. ☐ Set DEBUG to false in config
5. ☐ Configure proper file permissions
6. ☐ Set up regular backups
7. ☐ Enable error logging (not display)
8. ☐ Install SSL certificate
9. ☐ Configure firewall rules
10. ☐ Set up monitoring

---

## 🔧 Troubleshooting

### Database Connection Error

**Problem**: "Database connection failed"

**Solution**:
1. Verify MySQL service is running
2. Check database credentials in `config/database.php`
3. Ensure database `techspace` exists
4. Check user has proper permissions

### Page Not Found (404)

**Problem**: Pages return 404 error

**Solution**:
1. Verify techspace folder is in correct location
2. Check SITE_URL in `config/database.php`
3. Ensure Apache mod_rewrite is enabled

### Images Not Loading

**Problem**: Product images show placeholder

**Solution**:
1. Check `uploads/products/` folder exists
2. Verify folder permissions (755)
3. Re-upload product images via admin

### Cart Not Working

**Problem**: Items not adding to cart

**Solution**:
1. Check browser console for JavaScript errors
2. Verify session is working
3. Clear browser cache
4. Check AJAX endpoint accessibility

### White Screen/Blank Page

**Problem**: Page shows nothing

**Solution**:
1. Check PHP error logs
2. Enable error display temporarily:
   ```php
   ini_set('display_errors', 1);
   error_reporting(E_ALL);
   ```
3. Check for syntax errors in PHP files

---

## 📞 Support

For technical support or questions:

- Email: info@techspace.pk
- Documentation: See inline code comments

---

## 📝 License

This project is proprietary software developed for TechSpace.

---

## 🛣️ Future Enhancements

Planned features for future versions:

- [ ] Multi-vendor marketplace
- [ ] Product variants (size, color, etc.)
- [ ] Advanced analytics dashboard
- [ ] Email notifications
- [ ] SMS notifications
- [ ] WhatsApp integration
- [ ] Courier API integration (TCS, Leopards, etc.)
- [ ] Multiple admin roles
- [ ] Bulk product import/export
- [ ] Advanced reporting
- [ ] Loyalty points system
- [ ] Gift cards
- [ ] Live chat support

---

**Built with ❤️ for Pakistan's Technology Community**

TechSpace - Your Space for Smarter Technology
