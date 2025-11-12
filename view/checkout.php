<?php
require_once '../settings/core.php';
require_once '../controllers/cart_controller.php';

// Check if user is logged in
if (!check_login()) {
    header('Location: ../login/login.php');
    exit();
}

$customer_name = get_user_name();
$customer_id = get_user_id();

// Get cart items
$cart_items = get_user_cart_ctr($customer_id);
$cart_total = get_cart_total_ctr($customer_id);

// Ensure cart_items is an array
if ($cart_items === false || !is_array($cart_items)) {
    $cart_items = array();
}

// Redirect if cart is empty
if (count($cart_items) == 0) {
    header('Location: cart.php');
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Checkout - Taste of Africa</title>
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
        .checkout-item-image {
            width: 60px;
            height: 60px;
            object-fit: cover;
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
                <span class="navbar-text"><?php echo htmlspecialchars($customer_name); ?></span>
            </div>
        </div>
    </nav>

    <div class="container mt-5 mb-5">
        <div class="row">
            <div class="col-md-8">
                <h2 class="text-primary mb-4">
                    <i class="fa fa-credit-card me-2"></i>Checkout
                </h2>

                <div class="card mb-4">
                    <div class="card-header bg-primary text-white">
                        <h5 class="mb-0">Order Summary</h5>
                    </div>
                    <div class="card-body">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>Product</th>
                                    <th>Price</th>
                                    <th>Quantity</th>
                                    <th>Subtotal</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($cart_items as $item): ?>
                                    <tr class="checkout-item" 
                                        data-price="<?php echo $item['product_price']; ?>" 
                                        data-qty="<?php echo $item['qty']; ?>">
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <?php if (!empty($item['product_image'])): ?>
                                                    <img src="../<?php echo htmlspecialchars($item['product_image']); ?>" 
                                                         class="checkout-item-image me-3" 
                                                         alt="<?php echo htmlspecialchars($item['product_title']); ?>">
                                                <?php else: ?>
                                                    <div class="checkout-item-image bg-secondary me-3"></div>
                                                <?php endif; ?>
                                                <span><?php echo htmlspecialchars($item['product_title']); ?></span>
                                            </div>
                                        </td>
                                        <td>$<?php echo number_format($item['product_price'], 2); ?></td>
                                        <td><?php echo $item['qty']; ?></td>
                                        <td>$<?php echo number_format($item['product_price'] * $item['qty'], 2); ?></td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>

                <a href="cart.php" class="btn btn-outline-secondary">
                    <i class="fa fa-arrow-left me-2"></i>Back to Cart
                </a>
            </div>

            <div class="col-md-4">
                <div class="card">
                    <div class="card-header bg-success text-white">
                        <h5 class="mb-0">Payment Summary</h5>
                    </div>
                    <div class="card-body">
                        <div class="d-flex justify-content-between mb-2">
                            <span>Subtotal:</span>
                            <span id="checkout-subtotal">$<?php echo number_format($cart_total, 2); ?></span>
                        </div>
                        <div class="d-flex justify-content-between mb-2">
                            <span>Tax (15%):</span>
                            <span id="checkout-tax">$<?php echo number_format($cart_total * 0.15, 2); ?></span>
                        </div>
                        <div class="d-flex justify-content-between mb-3">
                            <span>Shipping:</span>
                            <span id="checkout-shipping">$<?php echo $cart_total > 50 ? '0.00' : '10.00'; ?></span>
                        </div>
                        <hr>
                        <div class="d-flex justify-content-between mb-4">
                            <h5>Total:</h5>
                            <h5 class="text-primary" id="checkout-total">
                                $<?php 
                                    $tax = $cart_total * 0.15;
                                    $shipping = $cart_total > 50 ? 0 : 10;
                                    echo number_format($cart_total + $tax + $shipping, 2); 
                                ?>
                            </h5>
                        </div>

                        <button class="btn btn-custom w-100 btn-lg" id="proceed-to-payment-btn">
                            <i class="fa fa-lock me-2"></i>Simulate Payment
                        </button>

                        <div class="mt-3 text-center">
                            <small class="text-muted">
                                <i class="fa fa-shield-alt me-1"></i>Secure Payment Simulation
                            </small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Payment Modal -->
    <div class="modal fade" id="paymentModal" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header bg-warning">
                    <h5 class="modal-title">
                        <i class="fa fa-exclamation-triangle me-2"></i>Simulated Payment
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body text-center">
                    <i class="fa fa-credit-card" style="font-size: 64px; color: #D19C97;"></i>
                    <h4 class="mt-3">Payment Simulation</h4>
                    <p class="text-muted">
                        This is a simulated payment process for demonstration purposes only. 
                        No actual payment will be processed.
                    </p>
                    <div class="alert alert-info">
                        <strong>Total Amount:</strong> 
                        $<?php 
                            echo number_format($cart_total + ($cart_total * 0.15) + ($cart_total > 50 ? 0 : 10), 2); 
                        ?>
                    </div>
                    <p>Click "Yes, I've Paid" to simulate a successful payment.</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" id="cancel-payment-btn">Cancel</button>
                    <button type="button" class="btn btn-success" id="confirm-payment-btn">
                        <i class="fa fa-check me-2"></i>Yes, I've Paid
                    </button>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="../fontawesome/js/checkout.js"></script>
</body>
</html>