$(document).ready(function() {
    
    // validation patterns
    var emailPattern = /^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/;
    
    // validate email field
    function validateEmail(email) {
        var field = $('#customer_email');
        
        field.removeClass('is-valid is-invalid');
        field.next('.invalid-feedback, .valid-feedback').remove();
        
        if (email.trim() === '') {
            return false;
        }
        
        if (emailPattern.test(email)) {
            field.addClass('is-valid');
            field.after('<div class="valid-feedback">Valid email</div>');
            return true;
        } else {
            field.addClass('is-invalid');
            field.after('<div class="invalid-feedback">Please enter valid email</div>');
            return false;
        }
    }
    
    // validate password field
    function validatePassword(password) {
        var field = $('#customer_pass');
        
        field.removeClass('is-valid is-invalid');
        field.next('.invalid-feedback, .valid-feedback').remove();
        
        if (password.trim() === '') {
            return false;
        }
        
        if (password.length >= 1) {
            field.addClass('is-valid');
            field.after('<div class="valid-feedback">Password entered</div>');
            return true;
        } else {
            field.addClass('is-invalid');
            field.after('<div class="invalid-feedback">Password required</div>');
            return false;
        }
    }
    
    // check if form is valid
    function isFormValid() {
        var email = $('#customer_email').val();
        var password = $('#customer_pass').val();
        var valid = true;
        
        if (email.trim() === '') {
            $('#customer_email').addClass('is-invalid');
            $('#customer_email').after('<div class="invalid-feedback">Email required</div>');
            valid = false;
        } else if (!validateEmail(email)) {
            valid = false;
        }
        
        if (password.trim() === '') {
            $('#customer_pass').addClass('is-invalid');
            $('#customer_pass').after('<div class="invalid-feedback">Password required</div>');
            valid = false;
        }
        
        return valid;
    }
    
    // show loading
    function showLoading() {
        var btn = $('#login-form button[type="submit"]');
        btn.prop('disabled', true);
        btn.html('<span class="spinner-border spinner-border-sm"></span> Signing in...');
    }
    
    // hide loading
    function hideLoading() {
        var btn = $('#login-form button[type="submit"]');
        btn.prop('disabled', false);
        btn.html('<i class="fa fa-sign-in-alt me-2"></i>Sign In to My Account');
    }
    
    // field validation
    $('#customer_email').on('blur', function() {
        validateEmail($(this).val());
    });
    
    $('#customer_pass').on('blur', function() {
        validatePassword($(this).val());
    });
    
    // password toggle
    $('#password-toggle').on('click', function() {
        var passwordField = $('#customer_pass');
        var icon = $(this);
        
        if (passwordField.attr('type') === 'password') {
            passwordField.attr('type', 'text');
            icon.removeClass('fa-eye').addClass('fa-eye-slash');
        } else {
            passwordField.attr('type', 'password');
            icon.removeClass('fa-eye-slash').addClass('fa-eye');
        }
    });
    
    // form submit
    $('#login-form').on('submit', function(e) {
        e.preventDefault();
        
        if (!isFormValid()) {
            alert('Please enter valid email and password');
            return;
        }
        
        showLoading();
        
        var formData = {
            email: $('#customer_email').val().trim().toLowerCase(),
            password: $('#customer_pass').val()
        };
        
        $.ajax({
            url: '../actions/login_customer_action.php',
            type: 'POST',
            data: formData,
            dataType: 'json',
            success: function(response) {
                hideLoading();
                
                if (response.status === 'success') {
                    alert('Login successful!');
                    window.location.href = response.redirect || '../index.php';
                } else {
                    alert(response.message);
                    $('#customer_pass').val(''); // clear password
                }
            },
            error: function() {
                hideLoading();
                alert('Login failed. Please try again.');
                $('#customer_pass').val(''); // clear password
            }
        });
    });
    
    // focus email field
    $('#customer_email').focus();
    
});