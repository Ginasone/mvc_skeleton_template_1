<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Register - Taste of Africa</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f8f9fa;
            padding-top: 50px;
        }
        .register-container {
            max-width: 600px;
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
    </style>
</head>

<body>
    <div class="container register-container">
        <div class="card">
            <div class="card-header bg-primary text-white text-center">
                <h4>Create Account</h4>
            </div>
            <div class="card-body">
                <form id="register-form">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="customer_name" class="form-label">Full Name</label>
                            <input type="text" class="form-control" id="customer_name" name="name" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="customer_email" class="form-label">Email</label>
                            <input type="email" class="form-control" id="customer_email" name="email" required>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="customer_pass" class="form-label">Password</label>
                            <input type="password" class="form-control" id="customer_pass" name="password" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="customer_contact" class="form-label">Phone Number</label>
                            <input type="text" class="form-control" id="customer_contact" name="contact" required>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="customer_country" class="form-label">Country</label>
                            <input type="text" class="form-control" id="customer_country" name="country" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="customer_city" class="form-label">City</label>
                            <input type="text" class="form-control" id="customer_city" name="city" required>
                        </div>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label">Account Type</label>
                        <div>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" name="role" id="customer" value="2" checked>
                                <label class="form-check-label" for="customer">Customer</label>
                            </div>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" name="role" id="admin" value="1">
                                <label class="form-check-label" for="admin">Restaurant Owner</label>
                            </div>
                        </div>
                    </div>
                    
                    <div class="mb-3">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" id="terms" required>
                            <label class="form-check-label" for="terms">
                                I agree to the terms and conditions
                            </label>
                        </div>
                    </div>
                    
                    <button type="submit" class="btn btn-primary w-100">
                        <i class="fa fa-user-plus me-2"></i>Create My Account
                    </button>
                </form>
            </div>
            <div class="card-footer text-center">
                Already have an account? <a href="login.php" class="text-primary">Login here</a>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="../fontawesome/js/register.js"></script>
</body>
</html>