$(document).ready(function() {
    
    // Load cart on page load
    loadCart();
    
    // Update cart count in navbar
    updateCartCount();
    
    // Add to cart (used on product pages)
    window.addToCart = function(productId, qty = 1) {
        $.ajax({
            url: '../actions/add_to_cart_action.php',
            type: 'POST',
            data: {
                product_id: productId,
                qty: qty
            },
            dataType: 'json',
            success: function(response) {
                if (response.status === 'success') {
                    alert(response.message);
                    updateCartCount();
                } else {
                    alert(response.message);
                }
            },
            error: function() {
                alert('Failed to add item to cart. Please try again.');
            }
        });
    };
    
    // Update quantity
    $(document).on('change', '.cart-quantity', function() {
        var cartId = $(this).data('cart-id');
        var qty = $(this).val();
        
        if (qty < 1) {
            alert('Quantity must be at least 1');
            $(this).val(1);
            return;
        }
        
        updateQuantity(cartId, qty);
    });
    
    // Remove from cart
    $(document).on('click', '.remove-from-cart', function() {
        var cartId = $(this).data('cart-id');
        var productName = $(this).data('product-name');
        
        if (confirm('Remove "' + productName + '" from cart?')) {
            removeFromCart(cartId);
        }
    });
    
    // Empty cart
    $('#empty-cart-btn').on('click', function() {
        if (confirm('Are you sure you want to empty your cart?')) {
            emptyCart();
        }
    });
    
    // Function to load cart
    function loadCart() {
        // This is called on cart.php page
        // Cart items are already loaded via PHP
        calculateCartTotal();
    }
    
    // Function to update quantity
    function updateQuantity(cartId, qty) {
        $.ajax({
            url: '../actions/update_quantity_action.php',
            type: 'POST',
            data: {
                cart_id: cartId,
                qty: qty
            },
            dataType: 'json',
            success: function(response) {
                if (response.status === 'success') {
                    // Reload page to show updated prices
                    location.reload();
                } else {
                    alert(response.message);
                }
            },
            error: function() {
                alert('Failed to update quantity. Please try again.');
            }
        });
    }
    
    // Function to remove from cart
    function removeFromCart(cartId) {
        $.ajax({
            url: '../actions/remove_from_cart_action.php',
            type: 'POST',
            data: {
                cart_id: cartId
            },
            dataType: 'json',
            success: function(response) {
                if (response.status === 'success') {
                    alert(response.message);
                    location.reload();
                } else {
                    alert(response.message);
                }
            },
            error: function() {
                alert('Failed to remove item. Please try again.');
            }
        });
    }
    
    // Function to empty cart
    function emptyCart() {
        $.ajax({
            url: '../actions/empty_cart_action.php',
            type: 'POST',
            dataType: 'json',
            success: function(response) {
                if (response.status === 'success') {
                    alert(response.message);
                    location.reload();
                } else {
                    alert(response.message);
                }
            },
            error: function() {
                alert('Failed to empty cart. Please try again.');
            }
        });
    }
    
    // Function to update cart count in navbar
    function updateCartCount() {
        // This can be called from any page
        $.ajax({
            url: '../actions/get_cart_count_action.php',
            type: 'GET',
            dataType: 'json',
            success: function(response) {
                if (response.status === 'success') {
                    $('#cart-count').text(response.count);
                    if (response.count > 0) {
                        $('#cart-count').show();
                    } else {
                        $('#cart-count').hide();
                    }
                }
            },
            error: function() {
                // Silently fail - not critical
            }
        });
    }
    
    // Function to calculate cart total (client-side for display)
    function calculateCartTotal() {
        var total = 0;
        $('.cart-item').each(function() {
            var price = parseFloat($(this).data('price'));
            var qty = parseInt($(this).find('.cart-quantity').val());
            var subtotal = price * qty;
            
            $(this).find('.item-subtotal').text('$' + subtotal.toFixed(2));
            total += subtotal;
        });
        
        $('#cart-total').text('$' + total.toFixed(2));
    }
    
});

// Global function for "Add to Cart" buttons on product pages
function addProductToCart(productId) {
    var qty = $('#product-quantity').val() || 1;
    addToCart(productId, qty);
}