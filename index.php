<?php
// Start session to check if user is logged in
session_start();

// Check if user is logged in (matches database field names)
$is_logged_in = isset($_SESSION['customer_id']) && !empty($_SESSION['customer_id']);
$customer_name = isset($_SESSION['customer_name']) ? $_SESSION['customer_name'] : 'User';
$user_role = isset($_SESSION['user_role']) ? (int)$_SESSION['user_role'] : 2; // 1=admin, 2=customer
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Taste of Africa - Authentic African Cuisine</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <style>
        .btn-custom {
            background-color: #D19C97;
            border-color: #D19C97;
            color: #fff;
            transition: background-color 0.3s, border-color 0.3s;
        }

        .btn-custom:hover {
            background-color: #b77a7a;
            border-color: #b77a7a;
            color: #fff;
        }

        .highlight {
            color: #D19C97;
        }

        body {
            background-color: #f8f9fa;
            background-image:
                repeating-linear-gradient(0deg,
                    #b77a7a,
                    #b77a7a 1px,
                    transparent 1px,
                    transparent 20px),
                repeating-linear-gradient(90deg,
                    #b77a7a,
                    #b77a7a 1px,
                    transparent 1px,
                    transparent 20px),
                linear-gradient(rgba(183, 122, 122, 0.1),
                    rgba(183, 122, 122, 0.1));
            background-blend-mode: overlay;
            background-size: 20px 20px;
            min-height: 100vh;
        }

        .navbar-brand {
            font-weight: bold;
            font-size: 1.5rem;
        }

        .hero-section {
            background: linear-gradient(135deg, rgba(209, 156, 151, 0.9), rgba(183, 122, 122, 0.9));
            color: white;
            padding: 100px 0;
            margin-top: -56px;
            padding-top: 156px;
        }

        .feature-card {
            transition: transform 0.3s ease;
            border: none;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }

        .feature-card:hover {
            transform: translateY(-5px);
        }

        .status-badge {
            background: linear-gradient(135deg, rgba(40, 167, 69, 0.1), rgba(40, 167, 69, 0.2));
            border: 1px solid #28a745;
            border-radius: 20px;
            padding: 5px 15px;
            font-size: 0.85rem;
            color: #28a745;
            display: inline-block;
        }
    </style>
</head>

<body>
    <!-- Navigation Bar -->
    <nav class="navbar navbar-expand-lg navbar-light bg-white fixed-top shadow-sm">
        <div class="container">
            <a class="navbar-brand highlight" href="index.php">
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
                    <li class="nav-item">
                        <a class="nav-link" href="#contact">Contact</a>
                    </li>
                </ul>
                
                <!-- Authentication Menu -->
                <ul class="navbar-nav">
                    <?php if (!$is_logged_in): ?>
                        <!-- Show Register/Login buttons when user is not logged in -->
                        <li class="nav-item me-2">
                            <a class="btn btn-outline-secondary" href="login/login.php">
                                <i class="fa fa-sign-in-alt me-1"></i>Sign In
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="btn btn-custom" href="login/register.php">
                                <i class="fa fa-user-plus me-1"></i>Join Us
                            </a>
                        </li>
                    <?php else: ?>
                        <!-- Show user menu when logged in (for future implementation) -->
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button" data-bs-toggle="dropdown">
                                <i class="fa fa-user-circle me-1"></i>
                                <?php echo htmlspecialchars($customer_name); ?>
                                <?php if ($user_role == 1): ?>
                                    <span class="status-badge ms-2">Owner</span>
                                <?php endif; ?>
                            </a>
                            <ul class="dropdown-menu">
                                <li><h6 class="dropdown-header">
                                    <?php echo $user_role == 1 ? 'Restaurant Owner' : 'Customer'; ?> Account
                                </h6></li>
                                <li><a class="dropdown-item" href="#"><i class="fa fa-user me-2"></i>My Profile</a></li>
                                <li><a class="dropdown-item" href="#"><i class="fa fa-shopping-bag me-2"></i>My Orders</a></li>
                                <?php if ($user_role == 1): ?>
                                <li><hr class="dropdown-divider"></li>
                                <li><a class="dropdown-item" href="#"><i class="fa fa-store me-2"></i>Manage Restaurant</a></li>
                                <li><a class="dropdown-item" href="#"><i class="fa fa-chart-bar me-2"></i>Sales Dashboard</a></li>
                                <?php endif; ?>
                                <li><hr class="dropdown-divider"></li>
                                <li><a class="dropdown-item" href="#" onclick="showLogoutSoon()"><i class="fa fa-sign-out-alt me-2"></i>Sign Out</a></li>
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
            <h1 class="display-4 mb-4">
                <?php if ($is_logged_in): ?>
                    Welcome Back, <?php echo htmlspecialchars($customer_name); ?>!
                <?php else: ?>
                    Welcome to Taste of Africa
                <?php endif; ?>
            </h1>
            
            <p class="lead mb-5">
                <?php if ($is_logged_in): ?>
                    Ready to explore authentic African cuisine? Your culinary adventure continues here.
                <?php else: ?>
                    Experience authentic African cuisine delivered to your doorstep. Join our community today!
                <?php endif; ?>
            </p>
            
            <?php if (!$is_logged_in): ?>
                <div class="mb-4">
                    <a href="login/register.php" class="btn btn-light btn-lg me-3">
                        <i class="fa fa-user-plus me-2"></i>Start Your Journey
                    </a>
                    <a href="login/login.php" class="btn btn-outline-light btn-lg">
                        <i class="fa fa-sign-in-alt me-2"></i>Already a Member?
                    </a>
                </div>
            <?php else: ?>
                <div class="mb-4">
                    <a href="#menu" class="btn btn-light btn-lg me-3">
                        <i class="fa fa-utensils me-2"></i>Browse Menu
                    </a>
                    <a href="#" class="btn btn-outline-light btn-lg">
                        <i class="fa fa-shopping-cart me-2"></i>Start Ordering
                    </a>
                </div>
            <?php endif; ?>
        </div>
    </section>

    <!-- Features Section -->
    <section class="py-5" id="about">
        <div class="container">
            <div class="row text-center mb-5">
                <div class="col">
                    <h2 class="highlight">Why Choose Our Platform?</h2>
                    <p class="text-muted">Discover what makes Taste of Africa special</p>
                </div>
            </div>
            
            <div class="row">
                <div class="col-md-4 mb-4">
                    <div class="card feature-card h-100">
                        <div class="card-body text-center">
                            <i class="fa fa-globe-africa fa-3x highlight mb-3"></i>
                            <h5 class="card-title">Authentic Experience</h5>
                            <p class="card-text">Real African flavors prepared by passionate chefs using traditional recipes and the freshest ingredients available.</p>
                        </div>
                    </div>
                </div>
                
                <div class="col-md-4 mb-4">
                    <div class="card feature-card h-100">
                        <div class="card-body text-center">
                            <i class="fa fa-users fa-3x highlight mb-3"></i>
                            <h5 class="card-title">Community Driven</h5>
                            <p class="card-text">Connect with fellow food lovers and restaurant owners in our growing community of African cuisine enthusiasts.</p>
                        </div>
                    </div>
                </div>
                
                <div class="col-md-4 mb-4">
                    <div class="card feature-card h-100">
                        <div class="card-body text-center">
                            <i class="fa fa-shield-alt fa-3x highlight mb-3"></i>
                            <h5 class="card-title">Secure & Reliable</h5>
                            <p class="card-text">Your information is protected with industry-standard security. Passwords are encrypted and data is handled safely.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Development Status Section -->
    <section class="py-5 bg-light">
        <div class="container text-center">
            <h3 class="highlight mb-3">Development Progress</h3>
            <div class="row justify-content-center">
                <div class="col-md-8">
                    <div class="card">
                        <div class="card-body">
                            <h5 class="card-title">Authentication Lab - Part 1</h5>
                            <div class="progress mb-3">
                                <div class="progress-bar bg-success" role="progressbar" style="width: 100%" aria-valuenow="100" aria-valuemax="100">100%</div>
                            </div>
                            <ul class="list-unstyled">
                                <li class="text-success"><i class="fa fa-check-circle me-2"></i>Customer Registration System</li>
                                <li class="text-success"><i class="fa fa-check-circle me-2"></i>MVC Architecture Implementation</li>
                                <li class="text-success"><i class="fa fa-check-circle me-2"></i>Database Integration (customer table)</li>
                                <li class="text-success"><i class="fa fa-check-circle me-2"></i>Form Validation (Client & Server)</li>
                                <li class="text-success"><i class="fa fa-check-circle me-2"></i>Password Encryption</li>
                                <li class="text-muted"><i class="fa fa-clock me-2"></i>Login System (Coming in Next Lab)</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Call to Action Section (only show if not logged in) -->
    <?php if (!$is_logged_in): ?>
    <section class="py-5" id="cta">
        <div class="container text-center">
            <h3 class="highlight mb-3">Ready to Join Our Community?</h3>
            <p class="text-muted mb-4">Thousands of food lovers have already discovered authentic African flavors with us</p>
            <a href="login/register.php" class="btn btn-custom btn-lg">
                <i class="fa fa-rocket me-2"></i>Create Your Account - It's Free!
            </a>
        </div>
    </section>
    <?php endif; ?>

    <!-- Footer -->
    <footer class="bg-dark text-white py-4 mt-5">
        <div class="container">
            <div class="row">
                <div class="col-md-6">
                    <h5 class="highlight">Taste of Africa</h5>
                    <p>Bringing authentic African cuisine to your table through technology.</p>
                    <?php if ($is_logged_in): ?>
                    <p class="text-success"><i class="fa fa-user-check me-2"></i>Logged in as: <?php echo htmlspecialchars($customer_name); ?></p>
                    <?php endif; ?>
                </div>
                <div class="col-md-6 text-md-end">
                    <div class="social-links">
                        <a href="#" class="text-white me-3"><i class="fab fa-facebook"></i></a>
                        <a href="#" class="text-white me-3"><i class="fab fa-twitter"></i></a>
                        <a href="#" class="text-white me-3"><i class="fab fa-instagram"></i></a>
                        <a href="#" class="text-white"><i class="fab fa-linkedin"></i></a>
                    </div>
                    <p class="mt-2">&copy; 2025 Taste of Africa. All rights reserved.</p>
                    <small class="text-muted">Database: shoppn | Authentication Lab Complete</small>
                </div>
            </div>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
        // Function to show logout will be available soon
        function showLogoutSoon() {
            Swal.fire({
                icon: 'info',
                title: 'Feature Coming Soon',
                text: 'Logout functionality will be implemented when login is complete in the next lab.',
                confirmButtonColor: '#D19C97'
            });
        }

        // Console info for developers
        console.log('Session Management Ready');
        console.log('Database Fields Mapped:');
        console.log('- customer_id (Primary Key)');
        console.log('- customer_name (VARCHAR 100)');
        console.log('- customer_email (VARCHAR 50, Unique)');
        console.log('- customer_pass (VARCHAR 150, Encrypted)');
        console.log('- user_role (INT: 1=Admin/Owner, 2=Customer)');
        
        <?php if ($is_logged_in): ?>
        console.log('User Status: Logged In');
        console.log('Customer ID: <?php echo $_SESSION["customer_id"]; ?>');
        console.log('User Role: <?php echo $user_role == 1 ? "Admin/Owner" : "Customer"; ?>');
        <?php else: ?>
        console.log('User Status: Guest (Not Logged In)');
        <?php endif; ?>
    </script>
</body>

</html>