<?php
require_once '../settings/core.php';
require_once '../controllers/cart_controller.php';

// Check if user is logged in
if (!check_login()) {
    header('Location: ../login/login.php');
    exit();
}

$is_logged_in = check_login();
$customer_name = get_user_name();
$is_admin = check_admin();
$customer_id = get_user_id();

// Get cart items
$cart_items = get_user_cart_ctr($customer_id);
$cart_total = get_cart_total_ctr($customer_id);

// Ensure cart_items is an array
if ($cart_items === false || !is_array($cart_items)) {
    $cart_items = array();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Shopping Cart - Taste of Africa</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <style>
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
        .cart-item-image {
            width: 80px;
            height: 80px;
            object-fit: cover;
        }
        .cart-quantity {
            width: 80px;
        }
    </style>
</head>

<body>
    <!-- Navigation -->
    <nav class="navbar navbar-expand-lg navbar-light bg-white shadow-sm">
        <div class="container">
            <a class="navbar-brand text-primary" href="../index.php">
                <i class="fa fa-utensils me-2"></i>Taste of Africa
            </a>
            
            <div class="navbar-nav ms-auto">
                <a class="nav-link" href="../index.php">Home</a>
                <a class="nav-link" href="all_product.php">Products</a>
                <a class="nav-link active" href="cart.php">
                    <i class="fa fa-shopping-cart"></i> Cart
                    <span class="badge bg-danger" id="cart-count"><?php echo count($cart_items); ?></span>
                </a>
                <span class="navbar-text ms-3"><?php echo htmlspecialchars($customer_name); ?></span>
                <a class="btn btn-outline-danger btn-sm ms-2" href="#" onclick="logout()">Logout</a>
            </div>
        </div>
    </nav>

    <div class="container mt-5 mb-5">
        <h2 class="text-primary mb-4">
            <i class="fa fa-shopping-cart me-2"></i>Shopping Cart
        </h2>

        <?php if (count($cart_items) == 0): ?>
            <div class="alert alert-info">
                <i class="fa fa-info-circle me-2"></i>
                Your cart is empty. <a href="all_product.php" class="alert-link">Continue shopping</a>
            </div>
        <?php else: ?>
            <div class="row">
                <div class="col-md-8">
                    <div class="card">
                        <div class="card-body">
                            <table class="table">
                                <thead>
                                    <tr>
                                        <th>Product</th>
                                        <th>Price</th>
                                        <th>Quantity</th>
                                        <th>Subtotal</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($cart_items as $item): ?>
                                        <tr class="cart-item" data-price="<?php echo $item['product_price']; ?>">
                                            <td>
                                                <div class="d-flex align-items-center">
                                                    <?php if (!empty($item['product_image'])): ?>
                                                        <img src="../<?php echo htmlspecialchars($item['product_image']); ?>" 
                                                             class="cart-item-image me-3" 
                                                             alt="<?php echo htmlspecialchars($item['product_title']); ?>">
                                                    <?php else: ?>
                                                        <div class="cart-item-image bg-secondary me-3"></div>
                                                    <?php endif; ?>
                                                    <div>
                                                        <strong><?php echo htmlspecialchars($item['product_title']); ?></strong>
                                                    </div>
                                                </div>
                                            </td>
                                            <td>$<?php echo number_format($item['product_price'], 2); ?></td>
                                            <td>
                                                <input type="number" 
                                                       class="form-control cart-quantity" 
                                                       value="<?php echo $item['qty']; ?>" 
                                                       min="1" 
                                                       data-cart-id="<?php echo $item['cart_id']; ?>">
                                            </td>
                                            <td class="item-subtotal">
                                                $<?php echo number_format($item['product_price'] * $item['qty'], 2); ?>
                                            </td>
                                            <td>
                                                <button class="btn btn-sm btn-danger remove-from-cart" 
                                                        data-cart-id="<?php echo $item['cart_id']; ?>"
                                                        data-product-name="<?php echo htmlspecialchars($item['product_title']); ?>">
                                                    <i class="fa fa-trash"></i>
                                                </button>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <div class="mt-3">
                        <a href="all_product.php" class="btn btn-outline-secondary">
                            <i class="fa fa-arrow-left me-2"></i>Continue Shopping
                        </a>
                        <button class="btn btn-outline-danger" id="empty-cart-btn">
                            <i class="fa fa-trash me-2"></i>Empty Cart
                        </button>
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="card">
                        <div class="card-header bg-primary text-white">
                            <h5 class="mb-0">Cart Summary</h5>
                        </div>
                        <div class="card-body">
                            <div class="d-flex justify-content-between mb-3">
                                <strong>Total Items:</strong>
                                <span><?php echo count($cart_items); ?></span>
                            </div>
                            <div class="d-flex justify-content-between mb-3">
                                <strong>Subtotal:</strong>
                                <span id="cart-total">$<?php echo number_format($cart_total, 2); ?></span>
                            </div>
                            <hr>
                            <div class="d-flex justify-content-between mb-3">
                                <h5>Total:</h5>
                                <h5 class="text-primary">$<?php echo number_format($cart_total, 2); ?></h5>
                            </div>
                            <a href="checkout.php" class="btn btn-custom w-100 btn-lg">
                                <i class="fa fa-credit-card me-2"></i>Proceed to Checkout
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        <?php endif; ?>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="../js/cart.js"></script>
    
    <script>
        function logout() {
            if (confirm('Are you sure you want to logout?')) {
                window.location.href = '../index.php?logout=1';
            }
        }
    </script>
</body>
</html>