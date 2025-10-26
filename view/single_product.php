<?php
require_once 'settings/core.php';
require_once 'controllers/product_controller.php';

$is_logged_in = check_login();
$customer_name = $is_logged_in ? get_user_name() : '';
$is_admin = check_admin();

// get product id from URL
$product_id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

if ($product_id <= 0) {
    header('Location: all_product.php');
    exit();
}

// get product details
$product = view_single_product_ctr($product_id);

if (!$product) {
    header('Location: all_product.php');
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title><?php echo htmlspecialchars($product['product_title']); ?> - Taste of Africa</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <style>
        .navbar-brand {
            color: #D19C97 !important;
            font-weight: bold;
        }
        .btn-custom {
            background-color: #D19C97;
            border-color: #D19C97;
            color: white;
        }
        .btn-custom:hover {
            background-color: #b77a7a;
            border-color: #b77a7a;
            color: white;
        }
        .text-primary {
            color: #D19C97 !important;
        }
        .product-image {
            width: 100%;
            max-height: 500px;
            object-fit: contain;
        }
        .keyword-badge {
            background-color: #f0f0f0;
            padding: 5px 10px;
            border-radius: 15px;
            margin-right: 5px;
            margin-bottom: 5px;
            display: inline-block;
        }
    </style>
</head>

<body>
    <!-- Navigation -->
    <nav class="navbar navbar-expand-lg navbar-light bg-white shadow-sm">
        <div class="container">
            <a class="navbar-brand" href="index.php">
                <i class="fa fa-utensils me-2"></i>Taste of Africa
            </a>
            
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav me-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="index.php">Home</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="all_product.php">All Products</a>
                    </li>
                </ul>
                
                <!-- Search Box -->
                <form class="d-flex me-3" action="product_search_result.php" method="GET">
                    <input class="form-control me-2" type="search" name="query" placeholder="Search products..." required>
                    <button class="btn btn-custom" type="submit">
                        <i class="fa fa-search"></i>
                    </button>
                </form>
                
                <ul class="navbar-nav">
                    <?php if (!$is_logged_in): ?>
                        <li class="nav-item me-2">
                            <a class="btn btn-outline-secondary" href="login/login.php">Login</a>
                        </li>
                        <li class="nav-item">
                            <a class="btn btn-custom" href="login/register.php">Register</a>
                        </li>
                    <?php elseif ($is_admin): ?>
                        <li class="nav-item me-2">
                            <a class="btn btn-success" href="admin/product.php">
                                <i class="fa fa-box me-1"></i>Manage
                            </a>
                        </li>
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown">
                                <i class="fa fa-user-shield me-1"></i><?php echo htmlspecialchars($customer_name); ?>
                            </a>
                            <ul class="dropdown-menu">
                                <li><a class="dropdown-item" href="#" onclick="logout()">Logout</a></li>
                            </ul>
                        </li>
                    <?php else: ?>
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown">
                                <i class="fa fa-user me-1"></i><?php echo htmlspecialchars($customer_name); ?>
                            </a>
                            <ul class="dropdown-menu">
                                <li><a class="dropdown-item" href="#" onclick="logout()">Logout</a></li>
                            </ul>
                        </li>
                    <?php endif; ?>
                </ul>
            </div>
        </div>
    </nav>

    <div class="container mt-4 mb-5">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="index.php">Home</a></li>
                <li class="breadcrumb-item"><a href="all_product.php">Products</a></li>
                <li class="breadcrumb-item active"><?php echo htmlspecialchars($product['product_title']); ?></li>
            </ol>
        </nav>

        <div class="row">
            <!-- Product Image -->
            <div class="col-md-6">
                <div class="card">
                    <div class="card-body text-center">
                        <?php if (!empty($product['product_image'])): ?>
                            <img src="<?php echo htmlspecialchars($product['product_image']); ?>" 
                                 class="product-image" 
                                 alt="<?php echo htmlspecialchars($product['product_title']); ?>">
                        <?php else: ?>
                            <div class="product-image bg-secondary d-flex align-items-center justify-content-center">
                                <i class="fa fa-image fa-5x text-white"></i>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>

            <!-- Product Details -->
            <div class="col-md-6">
                <div class="card">
                    <div class="card-body">
                        <h2 class="card-title text-primary"><?php echo htmlspecialchars($product['product_title']); ?></h2>
                        
                        <p class="text-muted">
                            <i class="fa fa-tag me-1"></i>
                            <strong>Category:</strong> <?php echo htmlspecialchars($product['cat_name']); ?>
                        </p>
                        
                        <p class="text-muted">
                            <i class="fa fa-tags me-1"></i>
                            <strong>Brand:</strong> <?php echo htmlspecialchars($product['brand_name']); ?>
                        </p>
                        
                        <h3 class="text-primary mb-4">
                            <i class="fa fa-dollar-sign"></i><?php echo number_format($product['product_price'], 2); ?>
                        </h3>
                        
                        <h5>Description</h5>
                        <p class="mb-4"><?php echo nl2br(htmlspecialchars($product['product_desc'])); ?></p>
                        
                        <?php if (!empty($product['product_keywords'])): ?>
                            <h6>Keywords</h6>
                            <div class="mb-4">
                                <?php 
                                $keywords = explode(',', $product['product_keywords']);
                                foreach ($keywords as $keyword): 
                                    $keyword = trim($keyword);
                                    if (!empty($keyword)):
                                ?>
                                    <span class="keyword-badge">
                                        <i class="fa fa-tag me-1"></i><?php echo htmlspecialchars($keyword); ?>
                                    </span>
                                <?php 
                                    endif;
                                endforeach; 
                                ?>
                            </div>
                        <?php endif; ?>
                        
                        <div class="d-grid gap-2">
                            <button class="btn btn-custom btn-lg" disabled>
                                <i class="fa fa-shopping-cart me-2"></i>Add to Cart (Coming Soon)
                            </button>
                            <a href="all_product.php" class="btn btn-outline-secondary">
                                <i class="fa fa-arrow-left me-2"></i>Back to Products
                            </a>
                        </div>
                        
                        <p class="text-muted mt-3">
                            <small><i class="fa fa-barcode me-1"></i>Product ID: <?php echo $product['product_id']; ?></small>
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Footer -->
    <footer class="bg-dark text-white py-4">
        <div class="container text-center">
            <p>&copy; 2025 Taste of Africa. All rights reserved.</p>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    
    <script>
        function logout() {
            if (confirm('Are you sure you want to logout?')) {
                window.location.href = 'index.php?logout=1';
            }
        }
    </script>
</body>
</html>