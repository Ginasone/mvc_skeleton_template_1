<?php
require_once '../settings/core.php';
require_once '../controllers/product_controller.php';
require_once '../controllers/category_controller.php';
require_once '../controllers/brand_controller.php';

$is_logged_in = check_login();
$customer_name = $is_logged_in ? get_user_name() : '';
$is_admin = check_admin();

// get all products
$products = view_all_products_ctr();

// get all categories and brands for filters
$all_categories = get_all_categories_ctr();
$all_brands = get_all_brands_ctr();

// pagination
$items_per_page = 10;
$total_items = count($products);
$total_pages = ceil($total_items / $items_per_page);
$current_page = isset($_GET['page']) ? max(1, min($total_pages, (int)$_GET['page'])) : 1;
$offset = ($current_page - 1) * $items_per_page;
$products_to_display = array_slice($products, $offset, $items_per_page);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>All Products - Taste of Africa</title>
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
        .product-card {
            transition: transform 0.2s;
            height: 100%;
        }
        .product-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 4px 8px rgba(0,0,0,0.2);
        }
        .product-image {
            width: 100%;
            height: 250px;
            object-fit: cover;
        }
        .filter-section {
            background-color: #f8f9fa;
            padding: 20px;
            border-radius: 10px;
            margin-bottom: 30px;
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
                        <a class="nav-link active" href="all_product.php">All Products</a>
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

    <div class="container mt-4">
        <!-- Filter Section -->
        <div class="filter-section">
            <h5><i class="fa fa-filter me-2"></i>Filter Products</h5>
            <div class="row">
                <div class="col-md-4">
                    <label class="form-label">By Category</label>
                    <select class="form-select" id="filter-category">
                        <option value="">All Categories</option>
                        <?php if ($all_categories): ?>
                            <?php foreach ($all_categories as $category): ?>
                                <option value="<?php echo $category['cat_id']; ?>">
                                    <?php echo htmlspecialchars($category['cat_name']); ?>
                                </option>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </select>
                </div>
                <div class="col-md-4">
                    <label class="form-label">By Brand</label>
                    <select class="form-select" id="filter-brand">
                        <option value="">All Brands</option>
                        <?php if ($all_brands): ?>
                            <?php foreach ($all_brands as $brand): ?>
                                <option value="<?php echo $brand['brand_id']; ?>">
                                    <?php echo htmlspecialchars($brand['brand_name']); ?>
                                </option>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </select>
                </div>
                <div class="col-md-4 d-flex align-items-end">
                    <button class="btn btn-custom w-100" onclick="applyFilters()">
                        <i class="fa fa-filter me-2"></i>Apply Filters
                    </button>
                </div>
            </div>
        </div>

        <h2 class="text-primary mb-4">All Products (<?php echo $total_items; ?>)</h2>

        <!-- Products Grid -->
        <div class="row">
            <?php if (!$products || count($products) == 0): ?>
                <div class="col-12">
                    <div class="alert alert-info">
                        <i class="fa fa-info-circle me-2"></i>No products available at the moment.
                    </div>
                </div>
            <?php else: ?>
                <?php foreach ($products_to_display as $product): ?>
                    <div class="col-md-4 col-lg-3 mb-4">
                        <div class="card product-card">
                            <?php if (!empty($product['product_image'])): ?>
                                <img src="../<?php echo htmlspecialchars($product['product_image']); ?>"
                                     class="card-img-top product-image" 
                                     alt="<?php echo htmlspecialchars($product['product_title']); ?>">
                            <?php else: ?>
                                <div class="card-img-top product-image bg-secondary d-flex align-items-center justify-content-center">
                                    <i class="fa fa-image fa-3x text-white"></i>
                                </div>
                            <?php endif; ?>
                            <div class="card-body">
                                <h6 class="card-title"><?php echo htmlspecialchars($product['product_title']); ?></h6>
                                <p class="card-text">
                                    <small class="text-muted">
                                        <i class="fa fa-tag me-1"></i><?php echo htmlspecialchars($product['cat_name']); ?><br>
                                        <i class="fa fa-tags me-1"></i><?php echo htmlspecialchars($product['brand_name']); ?>
                                    </small>
                                </p>
                                <p class="card-text"><strong class="text-primary">$<?php echo number_format($product['product_price'], 2); ?></strong></p>
                            </div>
                            <div class="card-footer">
                                <a href="single_product.php?id=<?php echo $product['product_id']; ?>" class="btn btn-sm btn-custom w-100 mb-2">
                                    <i class="fa fa-eye me-1"></i>View Details
                                </a>
                                <button class="btn btn-sm btn-outline-success w-100" disabled>
                                    <i class="fa fa-shopping-cart me-1"></i>Add to Cart
                                </button>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>

        <!-- Pagination -->
        <?php if ($total_pages > 1): ?>
            <nav aria-label="Product pagination">
                <ul class="pagination justify-content-center">
                    <?php for ($i = 1; $i <= $total_pages; $i++): ?>
                        <li class="page-item <?php echo ($i == $current_page) ? 'active' : ''; ?>">
                            <a class="page-link" href="?page=<?php echo $i; ?>"><?php echo $i; ?></a>
                        </li>
                    <?php endfor; ?>
                </ul>
            </nav>
        <?php endif; ?>
    </div>

    <!-- Footer -->
    <footer class="bg-dark text-white py-4 mt-5">
        <div class="container text-center">
            <p>&copy; 2025 Taste of Africa. All rights reserved.</p>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    
    <script>
        function logout() {
            if (confirm('Are you sure you want to logout?')) {
                window.location.href = '../index.php?logout=1';
            }
        }
        
        function applyFilters() {
            var category = $('#filter-category').val();
            var brand = $('#filter-brand').val();
            
            var url = 'product_search_result.php?';
            var params = [];
            
            if (category) {
                params.push('category=' + category);
            }
            if (brand) {
                params.push('brand=' + brand);
            }
            
            if (params.length > 0) {
                window.location.href = url + params.join('&');
            } else {
                alert('Please select at least one filter');
            }
        }
    </script>
</body>
</html>