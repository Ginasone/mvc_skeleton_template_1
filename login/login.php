<?php
session_start();

// redirect if already logged in
if (isset($_SESSION['customer_id'])) {
    header('Location: ../index.php');
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Login - Taste of Africa</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f8f9fa;
            padding-top: 100px;
        }
        .login-container {
            max-width: 400px;
            margin: 0 auto;
        }
        .card {
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
        .btn-primary {
            background-color: #D19C97;
            border-color: #D19C97;
        }
        .btn-primary:hover {
            background-color: #b77a7a;
            border-color: #b77a7a;
        }
        .text-primary {
            color: #D19C97 !important;
        }
        .password-toggle {
            position: absolute;
            right: 15px;
            top: 50%;
            transform: translateY(-50%);
            cursor: pointer;
            color: #6c757d;
        }
        .password-container {
            position: relative;
        }
    </style>
</head>

<body>
    <div class="container login-container">
        <div class="card">
            <div class="card-header bg-primary text-white text-center">
                <h4>Login</h4>
            </div>
            <div class="card-body">
                <form id="login-form">
                    <div class="mb-3">
                        <label for="customer_email" class="form-label">Email</label>
                        <input type="email" class="form-control" id="customer_email" name="email" required>
                    </div>
                    
                    <div class="mb-3">
                        <label for="customer_pass" class="form-label">Password</label>
                        <div class="password-container">
                            <input type="password" class="form-control" id="customer_pass" name="password" required>
                            <i class="fa fa-eye password-toggle" id="password-toggle"></i>
                        </div>
                    </div>
                    
                    <div class="mb-3">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" id="remember_me">
                            <label class="form-check-label" for="remember_me">
                                Remember me
                            </label>
                        </div>
                    </div>
                    
                    <button type="submit" class="btn btn-primary w-100">
                        <i class="fa fa-sign-in-alt me-2"></i>Sign In to My Account
                    </button>
                </form>
            </div>
            <div class="card-footer text-center">
                Don't have an account? <a href="register.php" class="text-primary">Register here</a>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="../fontawesome/js/login.js"></script>
</body>
</html>