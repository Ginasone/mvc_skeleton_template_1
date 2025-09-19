$(document).ready(function() {
    
    // very simple validation - just check if fields have content
    function isFormValid() {
        var valid = true;
        var fields = [
            {id: '#customer_name', name: 'Name'},
            {id: '#customer_email', name: 'Email'},
            {id: '#customer_pass', name: 'Password'},
            {id: '#customer_country', name: 'Country'},
            {id: '#customer_city', name: 'City'},
            {id: '#customer_contact', name: 'Contact'}
        ];
        
        // clear all previous errors
        $('.is-invalid').removeClass('is-invalid');
        $('.invalid-feedback').remove();
        
        // check each field has content
        for (var i = 0; i < fields.length; i++) {
            var field = $(fields[i].id);
            var value = field.val() ? field.val().trim() : '';
            
            if (value === '') {
                field.addClass('is-invalid');
                field.after('<div class="invalid-feedback">' + fields[i].name + ' is required</div>');
                valid = false;
            } else if (value.length < 2) {
                field.addClass('is-invalid');
                field.after('<div class="invalid-feedback">' + fields[i].name + ' must be at least 2 characters</div>');
                valid = false;
            }
        }
        
        // basic email check
        var email = $('#customer_email').val();
        if (email && !email.includes('@')) {
            $('#customer_email').addClass('is-invalid');
            $('#customer_email').next('.invalid-feedback').remove();
            $('#customer_email').after('<div class="invalid-feedback">Please enter a valid email</div>');
            valid = false;
        }
        
        // check role selected
        if (!$('input[name="role"]:checked').length) {
            valid = false;
            alert('Please select account type');
        }
        
        return valid;
    }
    
    // show loading
    function showLoading() {
        var btn = $('#register-form button[type="submit"]');
        btn.prop('disabled', true);
        btn.html('<span class="spinner-border spinner-border-sm"></span> Creating account...');
    }
    
    // hide loading
    function hideLoading() {
        var btn = $('#register-form button[type="submit"]');
        btn.prop('disabled', false);
        btn.html('<i class="fa fa-user-plus me-2"></i>Create My Account');
    }
    
    // form submit
    $('#register-form').on('submit', function(e) {
        e.preventDefault();
        
        if (!isFormValid()) {
            return;
        }
        
        showLoading();
        
        // get form data
        var formData = {
            name: $('#customer_name').val().trim(),
            email: $('#customer_email').val().trim(),
            password: $('#customer_pass').val(),
            country: $('#customer_country').val().trim(),
            city: $('#customer_city').val().trim(),
            contact: $('#customer_contact').val().trim(),
            role: $('input[name="role"]:checked').val() || '2'
        };
        
        // submit form
        $.ajax({
            url: '../actions/register_customer_action.php',
            type: 'POST',
            data: formData,
            dataType: 'json',
            success: function(response) {
                hideLoading();
                
                if (response.status === 'success') {
                    alert('Registration successful!');
                    window.location.href = response.redirect || 'login.php';
                } else {
                    var errorMsg = response.message;
                    if (response.errors && response.errors.length > 0) {
                        errorMsg += '\n\n' + response.errors.join('\n');
                    }
                    alert(errorMsg);
                }
            },
            error: function(xhr, status, error) {
                hideLoading();
                console.log('Server response:', xhr.responseText);
                alert('Registration failed. Please check the console for details.');
            }
        });
    });
    
    // simple field clearing on focus
    $('.form-control').on('focus', function() {
        $(this).removeClass('is-invalid');
        $(this).next('.invalid-feedback').remove();
    });
    
});