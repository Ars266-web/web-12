<?php
session_start();

// Check if admin is logged in
if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header('Location: login.php');
    exit();
}

require_once '../config/database.php';

// Fetch dashboard statistics
try {
    // Total Sales
    $stmt = $pdo->query("SELECT COALESCE(SUM(total_amount), 0) as total_sales FROM orders WHERE status != 'cancelled'");
    $total_sales = $stmt->fetchColumn();
    
    // Today's Sales
    $stmt = $pdo->query("SELECT COALESCE(SUM(total_amount), 0) as today_sales FROM orders WHERE DATE(created_at) = CURDATE() AND status != 'cancelled'");
    $today_sales = $stmt->fetchColumn();
    
    // Total Orders
    $stmt = $pdo->query("SELECT COUNT(*) as total_orders FROM orders");
    $total_orders = $stmt->fetchColumn();
    
    // Pending Orders
    $stmt = $pdo->query("SELECT COUNT(*) as pending_orders FROM orders WHERE status IN ('pending', 'confirmed')");
    $pending_orders = $stmt->fetchColumn();
    
    // Total Customers
    $stmt = $pdo->query("SELECT COUNT(*) as total_customers FROM users WHERE status = 1");
    $total_customers = $stmt->fetchColumn();
    
    // Total Products
    $stmt = $pdo->query("SELECT COUNT(*) as total_products FROM products WHERE status = 1");
    $total_products = $stmt->fetchColumn();
    
    // Low Stock Products
    $stmt = $pdo->query("SELECT COUNT(*) as low_stock FROM products WHERE stock_quantity <= 5 AND status = 1");
    $low_stock = $stmt->fetchColumn();
    
    // Recent Orders
    $stmt = $pdo->query("SELECT o.*, u.name as customer_name, u.email as customer_email 
                         FROM orders o 
                         LEFT JOIN users u ON o.user_id = u.id 
                         ORDER BY o.created_at DESC LIMIT 5");
    $recent_orders = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    // Top Products
    $stmt = $pdo->query("SELECT p.name, p.image, SUM(oi.quantity) as total_sold 
                         FROM order_items oi 
                         JOIN products p ON oi.product_id = p.id 
                         GROUP BY oi.product_id 
                         ORDER BY total_sold DESC LIMIT 5");
    $top_products = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
} catch (PDOException $e) {
    error_log($e->getMessage());
    $total_sales = $today_sales = $total_orders = $pending_orders = $total_customers = $total_products = $low_stock = 0;
    $recent_orders = [];
    $top_products = [];
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - TechSpace Admin</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="../assets/css/style.css">
    <style>
        :root {
            --primary-color: #0a192f;
            --secondary-color: #112240;
            --accent-color: #64ffda;
            --text-light: #ccd6f6;
        }
        body {
            background: #f4f6f9;
        }
        .sidebar {
            background: linear-gradient(180deg, var(--primary-color) 0%, var(--secondary-color) 100%);
            min-height: 100vh;
            position: fixed;
            width: 260px;
            left: 0;
            top: 0;
            z-index: 1000;
            transition: all 0.3s;
        }
        .sidebar-brand {
            padding: 20px;
            color: white;
            font-size: 22px;
            font-weight: 700;
            border-bottom: 1px solid rgba(255,255,255,0.1);
        }
        .sidebar-brand i {
            color: var(--accent-color);
        }
        .sidebar-menu {
            list-style: none;
            padding: 20px 0;
            margin: 0;
        }
        .sidebar-menu li a {
            display: flex;
            align-items: center;
            padding: 12px 20px;
            color: var(--text-light);
            text-decoration: none;
            transition: all 0.3s;
        }
        .sidebar-menu li a:hover,
        .sidebar-menu li a.active {
            background: rgba(100, 255, 218, 0.1);
            color: var(--accent-color);
            border-left: 3px solid var(--accent-color);
        }
        .sidebar-menu li a i {
            width: 25px;
            margin-right: 10px;
        }
        .main-content {
            margin-left: 260px;
            padding: 20px;
        }
        .top-navbar {
            background: white;
            padding: 15px 30px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.05);
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 30px;
            border-radius: 10px;
        }
        .stat-card {
            background: white;
            border-radius: 10px;
            padding: 25px;
            box-shadow: 0 2px 15px rgba(0,0,0,0.05);
            transition: transform 0.3s;
            height: 100%;
        }
        .stat-card:hover {
            transform: translateY(-5px);
        }
        .stat-icon {
            width: 60px;
            height: 60px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 24px;
            margin-bottom: 15px;
        }
        .stat-value {
            font-size: 28px;
            font-weight: 700;
            color: var(--primary-color);
            margin-bottom: 5px;
        }
        .stat-label {
            color: #6c757d;
            font-size: 14px;
        }
        .bg-primary-soft { background: rgba(10, 25, 47, 0.1); color: var(--primary-color); }
        .bg-success-soft { background: rgba(40, 167, 69, 0.1); color: #28a745; }
        .bg-warning-soft { background: rgba(255, 193, 7, 0.1); color: #ffc107; }
        .bg-info-soft { background: rgba(23, 162, 184, 0.1); color: #17a2b8; }
        .bg-danger-soft { background: rgba(220, 53, 69, 0.1); color: #dc3545; }
        .bg-purple-soft { background: rgba(111, 66, 193, 0.1); color: #6f42c1; }
        .bg-pink-soft { background: rgba(232, 62, 140, 0.1); color: #e83e8c; }
        
        .table-card {
            background: white;
            border-radius: 10px;
            padding: 25px;
            box-shadow: 0 2px 15px rgba(0,0,0,0.05);
            margin-top: 30px;
        }
        .table thead th {
            background: #f8f9fa;
            border-bottom: 2px solid #dee2e6;
            font-weight: 600;
            color: var(--primary-color);
        }
        .badge-status {
            padding: 5px 12px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 600;
        }
        .status-pending { background: #fff3cd; color: #856404; }
        .status-confirmed { background: #cce5ff; color: #004085; }
        .status-processing { background: #d6d8d9; color: #1b1e21; }
        .status-shipped { background: #d1ecf1; color: #0c5460; }
        .status-delivered { background: #d4edda; color: #155724; }
        .status-cancelled { background: #f8d7da; color: #721c24; }
        
        @media (max-width: 768px) {
            .sidebar {
                transform: translateX(-100%);
            }
            .sidebar.active {
                transform: translateX(0);
            }
            .main-content {
                margin-left: 0;
            }
        }
    </style>
</head>
<body>
    <!-- Sidebar -->
    <div class="sidebar" id="sidebar">
        <div class="sidebar-brand">
            <i class="fas fa-microchip"></i> TechSpace Admin
        </div>
        <ul class="sidebar-menu">
            <li><a href="dashboard.php" class="active"><i class="fas fa-tachometer-alt"></i> Dashboard</a></li>
            <li><a href="products.php"><i class="fas fa-box"></i> Products</a></li>
            <li><a href="categories.php"><i class="fas fa-tags"></i> Categories</a></li>
            <li><a href="orders.php"><i class="fas fa-shopping-cart"></i> Orders</a></li>
            <li><a href="customers.php"><i class="fas fa-users"></i> Customers</a></li>
            <li><a href="inventory.php"><i class="fas fa-warehouse"></i> Inventory</a></li>
            <li><a href="coupons.php"><i class="fas fa-ticket-alt"></i> Coupons</a></li>
            <li><a href="reviews.php"><i class="fas fa-star"></i> Reviews</a></li>
            <li><a href="payments.php"><i class="fas fa-credit-card"></i> Payments</a></li>
            <li><a href="reports.php"><i class="fas fa-chart-line"></i> Reports</a></li>
            <li><a href="settings.php"><i class="fas fa-cog"></i> Settings</a></li>
            <li><a href="../index.php" target="_blank"><i class="fas fa-external-link-alt"></i> View Site</a></li>
            <li><a href="logout.php"><i class="fas fa-sign-out-alt"></i> Logout</a></li>
        </ul>
    </div>

    <!-- Main Content -->
    <div class="main-content">
        <!-- Top Navbar -->
        <div class="top-navbar">
            <button class="btn btn-outline-secondary d-md-none" id="sidebarToggle">
                <i class="fas fa-bars"></i>
            </button>
            <h4 class="mb-0">Dashboard</h4>
            <div class="d-flex align-items-center">
                <span class="me-3">Welcome, <strong><?php echo htmlspecialchars($_SESSION['admin_name']); ?></strong></span>
                <div class="dropdown">
                    <button class="btn btn-sm btn-outline-primary dropdown-toggle" data-bs-toggle="dropdown">
                        <i class="fas fa-user-circle"></i> Admin
                    </button>
                    <ul class="dropdown-menu dropdown-menu-end">
                        <li><a class="dropdown-item" href="settings.php"><i class="fas fa-cog"></i> Settings</a></li>
                        <li><hr class="dropdown-divider"></li>
                        <li><a class="dropdown-item" href="logout.php"><i class="fas fa-sign-out-alt"></i> Logout</a></li>
                    </ul>
                </div>
            </div>
        </div>

        <!-- Stats Cards -->
        <div class="row g-4 mb-4">
            <div class="col-xl-3 col-md-6">
                <div class="stat-card">
                    <div class="stat-icon bg-primary-soft">
                        <i class="fas fa-rupee-sign"></i>
                    </div>
                    <div class="stat-value">Rs. <?php echo number_format($total_sales); ?></div>
                    <div class="stat-label">Total Sales</div>
                </div>
            </div>
            <div class="col-xl-3 col-md-6">
                <div class="stat-card">
                    <div class="stat-icon bg-success-soft">
                        <i class="fas fa-calendar-day"></i>
                    </div>
                    <div class="stat-value">Rs. <?php echo number_format($today_sales); ?></div>
                    <div class="stat-label">Today's Sales</div>
                </div>
            </div>
            <div class="col-xl-3 col-md-6">
                <div class="stat-card">
                    <div class="stat-icon bg-info-soft">
                        <i class="fas fa-shopping-bag"></i>
                    </div>
                    <div class="stat-value"><?php echo number_format($total_orders); ?></div>
                    <div class="stat-label">Total Orders</div>
                </div>
            </div>
            <div class="col-xl-3 col-md-6">
                <div class="stat-card">
                    <div class="stat-icon bg-warning-soft">
                        <i class="fas fa-clock"></i>
                    </div>
                    <div class="stat-value"><?php echo number_format($pending_orders); ?></div>
                    <div class="stat-label">Pending Orders</div>
                </div>
            </div>
            <div class="col-xl-3 col-md-6">
                <div class="stat-card">
                    <div class="stat-icon bg-purple-soft">
                        <i class="fas fa-users"></i>
                    </div>
                    <div class="stat-value"><?php echo number_format($total_customers); ?></div>
                    <div class="stat-label">Total Customers</div>
                </div>
            </div>
            <div class="col-xl-3 col-md-6">
                <div class="stat-card">
                    <div class="stat-icon bg-pink-soft">
                        <i class="fas fa-box-open"></i>
                    </div>
                    <div class="stat-value"><?php echo number_format($total_products); ?></div>
                    <div class="stat-label">Total Products</div>
                </div>
            </div>
            <div class="col-xl-3 col-md-6">
                <div class="stat-card">
                    <div class="stat-icon bg-danger-soft">
                        <i class="fas fa-exclamation-triangle"></i>
                    </div>
                    <div class="stat-value"><?php echo number_format($low_stock); ?></div>
                    <div class="stat-label">Low Stock Alerts</div>
                </div>
            </div>
        </div>

        <!-- Recent Orders -->
        <div class="table-card">
            <h5 class="mb-4"><i class="fas fa-list"></i> Recent Orders</h5>
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>Order #</th>
                            <th>Customer</th>
                            <th>Amount</th>
                            <th>Status</th>
                            <th>Date</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($recent_orders)): ?>
                            <tr>
                                <td colspan="6" class="text-center py-4">No orders found</td>
                            </tr>
                        <?php else: ?>
                            <?php foreach ($recent_orders as $order): ?>
                                <tr>
                                    <td><strong>#<?php echo htmlspecialchars($order['order_number']); ?></strong></td>
                                    <td>
                                        <?php echo htmlspecialchars($order['customer_name'] ?? 'Guest'); ?><br>
                                        <small class="text-muted"><?php echo htmlspecialchars($order['customer_email'] ?? ''); ?></small>
                                    </td>
                                    <td>Rs. <?php echo number_format($order['total_amount']); ?></td>
                                    <td><span class="badge-status status-<?php echo $order['status']; ?>"><?php echo ucfirst($order['status']); ?></span></td>
                                    <td><?php echo date('M d, Y', strtotime($order['created_at'])); ?></td>
                                    <td>
                                        <a href="order-details.php?id=<?php echo $order['id']; ?>" class="btn btn-sm btn-outline-primary">
                                            <i class="fas fa-eye"></i> View
                                        </a>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Top Products -->
        <div class="table-card">
            <h5 class="mb-4"><i class="fas fa-fire"></i> Top Selling Products</h5>
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>Product</th>
                            <th>Total Sold</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($top_products)): ?>
                            <tr>
                                <td colspan="2" class="text-center py-4">No sales data yet</td>
                            </tr>
                        <?php else: ?>
                            <?php foreach ($top_products as $product): ?>
                                <tr>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <?php if ($product['image']): ?>
                                                <img src="../uploads/products/<?php echo htmlspecialchars($product['image']); ?>" 
                                                     alt="<?php echo htmlspecialchars($product['name']); ?>" 
                                                     style="width: 50px; height: 50px; object-fit: cover; border-radius: 8px; margin-right: 15px;">
                                            <?php else: ?>
                                                <div style="width: 50px; height: 50px; background: #e9ecef; border-radius: 8px; margin-right: 15px; display: flex; align-items: center; justify-content: center;">
                                                    <i class="fas fa-image text-muted"></i>
                                                </div>
                                            <?php endif; ?>
                                            <strong><?php echo htmlspecialchars($product['name']); ?></strong>
                                        </div>
                                    </td>
                                    <td><strong><?php echo number_format($product['total_sold']); ?></strong> units</td>
                                </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Sidebar toggle for mobile
        document.getElementById('sidebarToggle')?.addEventListener('click', function() {
            document.getElementById('sidebar').classList.toggle('active');
        });
    </script>
</body>
</html>
