<?php
require_once 'settings/core.php';

// Handle logout
if (isset($_GET['logout']) && $_GET['logout'] == '1') {
    session_destroy();
    header('Location: index.php');
    exit();
}

// check if user is logged in
$is_logged_in = check_login();
$customer_name = $is_logged_in ? get_user_name() : '';
$user_role = $is_logged_in ? get_user_role() : 2;
$is_admin = check_admin();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Taste of Africa</title>
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
        .hero-section {
            background: linear-gradient(135deg, #D19C97, #b77a7a);
            color: white;
            padding: 100px 0;
        }
        .text-primary {
            color: #D19C97 !important;
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
                        <a class="nav-link" href="#home">Home</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#about">About</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#menu">Menu</a>
                    </li>
                </ul>
                
                <ul class="navbar-nav">
                    <?php if (!$is_logged_in): ?>
                        <!-- Not logged in: Register | Login -->
                        <li class="nav-item me-2">
                            <a class="btn btn-outline-secondary" href="login/login.php">Login</a>
                        </li>
                        <li class="nav-item">
                            <a class="btn btn-custom" href="login/register.php">Register</a>
                        </li>
                    <?php elseif ($is_admin): ?>
                        <!-- Logged in and admin: Logout | Category -->
                        <li class="nav-item me-2">
                            <a class="btn btn-success" href="admin/category.php">
                                <i class="fa fa-list me-1"></i>Category
                            </a>
                        </li>
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" href="#" id="adminDropdown" role="button" data-bs-toggle="dropdown">
                                <i class="fa fa-user-shield me-1"></i>
                                <?php echo htmlspecialchars($customer_name); ?>
                                <span class="badge bg-success">Admin</span>
                            </a>
                            <ul class="dropdown-menu">
                                <li><a class="dropdown-item" href="#"><i class="fa fa-user me-2"></i>Profile</a></li>
                                <li><a class="dropdown-item" href="admin/category.php"><i class="fa fa-list me-2"></i>Manage Categories</a></li>
                                <li><hr class="dropdown-divider"></li>
                                <li><a class="dropdown-item" href="#" onclick="logout()"><i class="fa fa-sign-out-alt me-2"></i>Logout</a></li>
                            </ul>
                        </li>
                    <?php else: ?>
                        <!-- Logged in but not admin: Logout only -->
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button" data-bs-toggle="dropdown">
                                <i class="fa fa-user me-1"></i>
                                <?php echo htmlspecialchars($customer_name); ?>
                                <span class="badge bg-secondary">Customer</span>
                            </a>
                            <ul class="dropdown-menu">
                                <li><a class="dropdown-item" href="#"><i class="fa fa-user me-2"></i>Profile</a></li>
                                <li><a class="dropdown-item" href="#"><i class="fa fa-shopping-bag me-2"></i>My Orders</a></li>
                                <li><hr class="dropdown-divider"></li>
                                <li><a class="dropdown-item" href="#" onclick="logout()"><i class="fa fa-sign-out-alt me-2"></i>Logout</a></li>
                            </ul>
                        </li>
                    <?php endif; ?>
                </ul>
            </div>
        </div>
    </nav>

    <!-- Hero Section -->
    <section class="hero-section" id="home">
        <div class="container text-center">
            <?php if ($is_logged_in): ?>
                <h1 class="display-4 mb-4">
                    Welcome back, <?php echo htmlspecialchars($customer_name); ?>!
                    <?php if ($is_admin): ?>
                        <small class="d-block text-light">Administrator Dashboard</small>
                    <?php endif; ?>
                </h1>
                <p class="lead mb-5">
                    <?php if ($is_admin): ?>
                        Manage your e-commerce platform and categories
                    <?php else: ?>
                        Ready to explore delicious African cuisine?
                    <?php endif; ?>
                </p>
                <div class="mb-4">
                    <?php if ($is_admin): ?>
                        <a href="admin/category.php" class="btn btn-light btn-lg me-3">
                            <i class="fa fa-list me-2"></i>Manage Categories
                        </a>
                        <a href="#about" class="btn btn-outline-light btn-lg">View Site</a>
                    <?php else: ?>
                        <a href="#menu" class="btn btn-light btn-lg me-3">View Menu</a>
                        <a href="#" class="btn btn-outline-light btn-lg">Order Now</a>
                    <?php endif; ?>
                </div>
            <?php else: ?>
                <h1 class="display-4 mb-4">Welcome to Taste of Africa</h1>
                <p class="lead mb-5">Experience authentic African cuisine delivered to your door</p>
                <a href="login/register.php" class="btn btn-light btn-lg me-3">Get Started</a>
                <a href="login/login.php" class="btn btn-outline-light btn-lg">Login</a>
            <?php endif; ?>
        </div>
    </section>

    <!-- About Section -->
    <section class="py-5" id="about">
        <div class="container">
            <div class="row text-center mb-5">
                <div class="col">
                    <h2 class="text-primary">Why Choose Us?</h2>
                    <p class="text-muted">Authentic African flavors made with love</p>
                </div>
            </div>
            
            <div class="row">
                <div class="col-md-4 mb-4">
                    <div class="card h-100 text-center">
                        <div class="card-body">
                            <i class="fa fa-globe-africa fa-3x text-primary mb-3"></i>
                            <h5 class="card-title">Authentic Cuisine</h5>
                            <p class="card-text">Real African flavors prepared by experienced chefs using traditional recipes.</p>
                        </div>
                    </div>
                </div>
                
                <div class="col-md-4 mb-4">
                    <div class="card h-100 text-center">
                        <div class="card-body">
                            <i class="fa fa-truck fa-3x text-primary mb-3"></i>
                            <h5 class="card-title">Fast Delivery</h5>
                            <p class="card-text">Quick delivery service to bring hot, fresh food right to your doorstep.</p>
                        </div>
                    </div>
                </div>
                
                <div class="col-md-4 mb-4">
                    <div class="card h-100 text-center">
                        <div class="card-body">
                            <i class="fa fa-heart fa-3x text-primary mb-3"></i>
                            <h5 class="card-title">Made with Love</h5>
                            <p class="card-text">Every dish is prepared with care and attention to bring you the best experience.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Call to Action -->
    <?php if (!$is_logged_in): ?>
    <section class="py-5 bg-light">
        <div class="container text-center">
            <h3 class="text-primary mb-3">Ready to Start?</h3>
            <p class="mb-4">Join us today and discover amazing African cuisine</p>
            <a href="login/register.php" class="btn btn-custom btn-lg">Register Now</a>
        </div>
    </section>
    <?php endif; ?>

    <!-- Footer -->
    <footer class="bg-dark text-white py-4">
        <div class="container">
            <div class="row">
                <div class="col-md-6">
                    <h5 class="text-primary">Taste of Africa</h5>
                    <p>Bringing authentic African cuisine to your table.</p>
                    <?php if ($is_logged_in): ?>
                    <p><small>Logged in as: <?php echo htmlspecialchars($customer_name); ?> 
                        <?php echo $is_admin ? '(Administrator)' : '(Customer)'; ?></small></p>
                    <?php endif; ?>
                </div>
                <div class="col-md-6 text-md-end">
                    <p>&copy; 2025 Taste of Africa. All rights reserved.</p>
                </div>
            </div>
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