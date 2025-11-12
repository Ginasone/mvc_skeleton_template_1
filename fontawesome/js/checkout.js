$(document).ready(function() {
    
    // Show payment modal when "Proceed to Payment" is clicked
    $('#proceed-to-payment-btn').on('click', function() {
        $('#paymentModal').modal('show');
    });
    
    // Handle "Yes, I've Paid" button click
    $('#confirm-payment-btn').on('click', function() {
        processCheckout();
    });
    
    // Handle "Cancel" button click
    $('#cancel-payment-btn').on('click', function() {
        $('#paymentModal').modal('hide');
    });
    
    // Process checkout function
    function processCheckout() {
        // Disable button to prevent double submission
        var btn = $('#confirm-payment-btn');
        btn.prop('disabled', true).html('<span class="spinner-border spinner-border-sm me-2"></span>Processing...');
        
        $.ajax({
            url: '../actions/process_checkout_action.php',
            type: 'POST',
            dataType: 'json',
            success: function(response) {
                btn.prop('disabled', false).html('Yes, I\'ve Paid');
                
                if (response.status === 'success') {
                    // Hide payment modal
                    $('#paymentModal').modal('hide');
                    
                    // Show success modal
                    showSuccessModal(response);
                } else {
                    // Show error
                    alert('Checkout failed: ' + response.message);
                }
            },
            error: function() {
                btn.prop('disabled', false).html('Yes, I\'ve Paid');
                alert('Checkout failed. Please try again.');
            }
        });
    }
    
    // Show success modal with order details
    function showSuccessModal(response) {
        var modalHtml = `
            <div class="modal fade" id="successModal" tabindex="-1">
                <div class="modal-dialog modal-dialog-centered">
                    <div class="modal-content">
                        <div class="modal-header bg-success text-white">
                            <h5 class="modal-title">
                                <i class="fa fa-check-circle me-2"></i>Payment Successful!
                            </h5>
                        </div>
                        <div class="modal-body text-center">
                            <i class="fa fa-check-circle text-success" style="font-size: 64px;"></i>
                            <h4 class="mt-3">Thank you for your order!</h4>
                            <p class="text-muted">Your order has been placed successfully.</p>
                            
                            <div class="alert alert-info mt-3">
                                <strong>Order Reference:</strong> ${response.order_reference}<br>
                                <strong>Order ID:</strong> #${response.order_id}<br>
                                <strong>Total Amount:</strong> $${response.total}<br>
                                <strong>Items:</strong> ${response.items_count}
                            </div>
                            
                            <p class="mt-3">
                                <small>A confirmation email has been sent to your registered email address.</small>
                            </p>
                        </div>
                        <div class="modal-footer">
                            <a href="../view/all_product.php" class="btn btn-secondary">Continue Shopping</a>
                            <a href="../view/my_orders.php" class="btn btn-primary">View My Orders</a>
                        </div>
                    </div>
                </div>
            </div>
        `;
        
        // Remove existing success modal if any
        $('#successModal').remove();
        
        // Append new modal
        $('body').append(modalHtml);
        
        // Show the modal
        $('#successModal').modal('show');
        
        // Redirect after modal is closed
        $('#successModal').on('hidden.bs.modal', function() {
            window.location.href = '../view/all_product.php';
        });
    }
    
    // Calculate and display totals
    function calculateTotals() {
        var subtotal = 0;
        
        $('.checkout-item').each(function() {
            var price = parseFloat($(this).data('price'));
            var qty = parseInt($(this).data('qty'));
            var itemTotal = price * qty;
            
            subtotal += itemTotal;
        });
        
        var tax = subtotal * 0.15; // 15% tax (adjust as needed)
        var shipping = subtotal > 50 ? 0 : 10; // Free shipping over $50
        var total = subtotal + tax + shipping;
        
        // Update display
        $('#checkout-subtotal').text('$' + subtotal.toFixed(2));
        $('#checkout-tax').text('$' + tax.toFixed(2));
        $('#checkout-shipping').text('$' + shipping.toFixed(2));
        $('#checkout-total').text('$' + total.toFixed(2));
    }
    
    // Calculate totals on page load
    calculateTotals();
    
});