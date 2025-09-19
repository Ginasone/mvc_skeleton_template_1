<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Login - Taste of Africa</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css" rel="stylesheet">
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
        }

        .highlight {
            color: #D19C97;
            transition: color 0.3s;
        }

        .highlight:hover {
            color: #b77a7a;
        }

        body {
            /* Base background color */
            background-color: #f8f9fa;

            /* Gradient-like grid using repeating-linear-gradients */
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

            /* Blend the gradients for a subtle overlay effect */
            background-blend-mode: overlay;

            /* Define the size of the grid */
            background-size: 20px 20px;

            /* Ensure the background covers the entire viewport */
            min-height: 100vh;
            margin: 0;
            padding: 0;
            font-family: Arial, sans-serif;
        }

        .login-container {
            margin-top: 100px;
        }

        .card {
            border: none;
            border-radius: 15px;
            overflow: hidden;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }

        .card-header {
            background-color: #D19C97;
            color: #fff;
        }

        .animate-pulse-custom {
            animation: pulse 2s infinite;
        }

        @keyframes pulse {
            0% {
                transform: scale(1);
            }

            50% {
                transform: scale(1.05);
            }

            100% {
                transform: scale(1);
            }
        }

        /* Additional Styling for Enhanced Appearance */
        .form-label i {
            margin-left: 5px;
            color: #b77a7a;
        }

        .alert-info {
            animation: fadeIn 1s;
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
            }

            to {
                opacity: 1;
            }
        }

        .demo-info {
            background: linear-gradient(135deg, rgba(209, 156, 151, 0.1), rgba(183, 122, 122, 0.1));
            border: 1px solid #D19C97;
            border-radius: 10px;
            padding: 15px;
            margin-bottom: 20px;
        }
    </style>
</head>

<body>
    <div class="container login-container">
        <div class="row justify-content-center animate__animated animate__fadeInDown">
            <div class="col-md-6">
                <div class="card animate__animated animate__zoomIn">
                    <div class="card-header text-center highlight">
                        <h4>Welcome Back</h4>
                        <p class="mb-0">Sign in to your account</p>
                    </div>
                    <div class="card-body">
                        <!-- Info about the current state -->
                        <div class="demo-info text-center">
                            <i class="fa fa-info-circle fa-2x highlight mb-2"></i>
                            <h6>Login Coming Soon!</h6>
                            <p class="mb-0">Login functionality will be implemented in future labs. For now, you can register a new account to test the registration system.</p>
                        </div>

                        <!-- Login form placeholder (matching database structure) -->
                        <form method="POST" action="" class="mt-4" id="login-form">
                            <div class="mb-3">
                                <label for="customer_email" class="form-label">Email Address <i class="fa fa-envelope"></i></label>
                                <input type="email" class="form-control animate__animated animate__fadeInUp" id="customer_email" name="email" required maxlength="50" placeholder="Enter your registered email">
                                <div class="form-text">The email you used when registering</div>
                            </div>
                            <div class="mb-4">
                                <label for="customer_pass" class="form-label">Password <i class="fa fa-lock"></i></label>
                                <input type="password" class="form-control animate__animated animate__fadeInUp" id="customer_pass" name="password" required placeholder="Enter your password">
                                <div class="form-text">Your secure password (minimum 6 characters)</div>
                            </div>
                            
                            <div class="mb-3">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" id="remember_me">
                                    <label class="form-check-label" for="remember_me">
                                        Keep me logged in
                                    </label>
                                </div>
                            </div>
                            
                            <button type="button" class="btn btn-custom w-100 animate-pulse-custom" onclick="showComingSoon()">
                                <i class="fa fa-sign-in-alt me-2"></i>Sign In to My Account
                            </button>
                            
                            <div class="text-center mt-3">
                                <a href="#" class="highlight text-decoration-none" onclick="showComingSoon()">
                                    <i class="fa fa-key me-1"></i>Forgot your password?
                                </a>
                            </div>
                        </form>
                    </div>
                    <div class="card-footer text-center">
                        Don't have an account yet? <a href="register.php" class="highlight">Join our community here</a>.
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    
    <script>
        // Temporary function to show that login functionality is coming soon
        function showComingSoon() {
            Swal.fire({
                icon: 'info',
                title: 'Feature Coming Soon',
                html: `
                    <p>Login functionality will be implemented in the next lab assignment.</p>
                    <p>For now, you can:</p>
                    <ul style="text-align: left; display: inline-block;">
                        <li>Register a new account to test the system</li>
                        <li>Verify that registration works properly</li>
                        <li>Check the database to see stored customers</li>
                    </ul>
                `,
                confirmButtonColor: '#D19C97',
                confirmButtonText: 'Create New Account',
                showCancelButton: true,
                cancelButtonText: 'Stay Here',
                cancelButtonColor: '#6c757d'
            }).then((result) => {
                if (result.isConfirmed) {
                    window.location.href = 'register.php';
                }
            });
        }
        
        // Add visual feedback for form interactions
        $('input[type="email"], input[type="password"]').on('focus', function() {
            $(this).parent().addClass('focused');
        }).on('blur', function() {
            $(this).parent().removeClass('focused');
        });

        // Show database field mapping info
        $(document).ready(function() {
            console.log('Login form fields mapped to database:');
            console.log('customer_email -> customer.customer_email (VARCHAR 50)');
            console.log('customer_pass -> customer.customer_pass (VARCHAR 150, hashed)');
        });
    </script>
</body>

</html>