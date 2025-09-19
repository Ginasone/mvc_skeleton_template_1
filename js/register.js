/**
 * Registration Form Helper
 * This makes the registration form smart and user-friendly!
 */

$(document).ready(function() {
    
    // These patterns help us check if the information looks right
    const validationRules = {
        name: /^[a-zA-Z\s]{2,100}$/,
        email: /^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/,
        password: /^.{6,}$/,
        country: /^[a-zA-Z\s]{2,30}$/,
        city: /^[a-zA-Z\s]{2,30}$/,
        contact: /^[\+]?[0-9\s\-\(\)]{10,15}$/
    };
    
    // Friendly messages to help users fix their information
    const helpfulMessages = {
        name: 'Your name should be between 2-100 characters and contain only letters and spaces',
        email: 'Please enter a valid email address (like john@example.com)',
        password: 'Your password should be at least 6 characters long for security',
        country: 'Country name should be 2-30 characters with only letters and spaces',
        city: 'City name should be 2-30 characters with only letters and spaces',
        contact: 'Please enter a valid phone number with 10-15 digits'
    };
    
    // Check each field when the user finishes typing in it
    $('#customer_name').on('blur', function() {
        checkIfFieldLooksGood('name', $(this).val());
    });
    
    $('#customer_email').on('blur', function() {
        checkIfFieldLooksGood('email', $(this).val());
    });
    
    $('#customer_pass').on('blur', function() {
        checkIfFieldLooksGood('password', $(this).val());
    });
    
    $('#customer_country').on('blur', function() {
        checkIfFieldLooksGood('country', $(this).val());
    });
    
    $('#customer_city').on('blur', function() {
        checkIfFieldLooksGood('city', $(this).val());
    });
    
    $('#customer_contact').on('blur', function() {
        checkIfFieldLooksGood('contact', $(this).val());
    });
    
    /**
     * Check if what the user typed looks correct
     * Give them a green checkmark if it's good, or helpful advice if not
     */
    function checkIfFieldLooksGood(fieldName, userInput) {
        const inputLooksGood = validationRules[fieldName].test(userInput.trim());
        let inputField;
        
        // Match the field name to the actual input ID
        switch(fieldName) {
            case 'name':
                inputField = $('#customer_name');
                break;
            case 'email':
                inputField = $('#customer_email');
                break;
            case 'password':
                inputField = $('#customer_pass');
                break;
            case 'country':
                inputField = $('#customer_country');
                break;
            case 'city':
                inputField = $('#customer_city');
                break;
            case 'contact':
                inputField = $('#customer_contact');
                break;
        }
        
        // Clear any previous feedback
        inputField.removeClass('is-valid is-invalid');
        inputField.next('.invalid-feedback, .valid-feedback').remove();
        
        if (userInput.trim() === '') {
            // Don't show anything if they haven't typed yet
            return false;
        }
        
        if (inputLooksGood) {
            // Great! Show them it looks good
            inputField.addClass('is-valid');
            inputField.after('<div class="valid-feedback">Looks great!</div>');
            return true;
        } else {
            // Oops! Give them some helpful advice
            inputField.addClass('is-invalid');
            inputField.after(`<div class="invalid-feedback">${helpfulMessages[fieldName]}</div>`);
            return false;
        }
    }
    
    /**
     * Check if the whole form is ready to submit
     * Make sure everything is filled out correctly
     */
    function isFormReadyToSubmit() {
        let formIsReady = true;
        const fieldsToCheck = ['name', 'email', 'password', 'country', 'city', 'contact'];
        
        fieldsToCheck.forEach(function(field) {
            let fieldValue;
            
            // Get the value from the correct field
            switch(field) {
                case 'name':
                    fieldValue = $('#customer_name').val();
                    break;
                case 'email':
                    fieldValue = $('#customer_email').val();
                    break;
                case 'password':
                    fieldValue = $('#customer_pass').val();
                    break;
                case 'country':
                    fieldValue = $('#customer_country').val();
                    break;
                case 'city':
                    fieldValue = $('#customer_city').val();
                    break;
                case 'contact':
                    fieldValue = $('#customer_contact').val();
                    break;
            }
            
            if (!checkIfFieldLooksGood(field, fieldValue)) {
                formIsReady = false;
            }
        });
        
        // Make sure they picked customer or restaurant owner
        if (!$('input[name="role"]:checked').length) {
            Swal.fire({
                icon: 'question',
                title: 'One More Thing!',
                text: 'Please let us know if you\'re signing up as a customer or restaurant owner'
            });
            formIsReady = false;
        }
        
        return formIsReady;
    }
    
    /**
     * Show the user that we're working on their registration
     */
    function showWorkingOnIt() {
        const submitButton = $('#register-form button[type="submit"]');
        submitButton.prop('disabled', true);
        submitButton.html('<span class="spinner-border spinner-border-sm me-2"></span>Creating your account...');
    }
    
    /**
     * Put the submit button back to normal
     */
    function resetSubmitButton() {
        const submitButton = $('#register-form button[type="submit"]');
        submitButton.prop('disabled', false);
        submitButton.html('<i class="fa fa-user-plus me-2"></i>Create My Account');
    }
    
    // Handle when they click the register button
    $('#register-form').on('submit', function(e) {
        e.preventDefault();
        
        // Make sure everything looks good before we try to register them
        if (!isFormReadyToSubmit()) {
            Swal.fire({
                icon: 'info',
                title: 'Almost There!',
                text: 'Please fix the highlighted fields before we can create your account'
            });
            return false;
        }
        
        // Show them we're working on it
        showWorkingOnIt();
        
        // Gather all their information using the correct field names
        const customerInfo = {
            name: $('#customer_name').val().trim(),
            email: $('#customer_email').val().trim().toLowerCase(),
            password: $('#customer_pass').val(),
            country: $('#customer_country').val().trim(),
            city: $('#customer_city').val().trim(),
            contact: $('#customer_contact').val().trim(),
            role: $('input[name="role"]:checked').val()
        };
        
        // Send their information to our server
        $.ajax({
            url: '../actions/register_customer_action.php',
            type: 'POST',
            data: customerInfo,
            dataType: 'json',
            timeout: 10000, // Give it 10 seconds to work
            success: function(serverResponse) {
                resetSubmitButton();
                
                if (serverResponse.status === 'success') {
                    // Hooray! Their account was created
                    Swal.fire({
                        icon: 'success',
                        title: 'Welcome Aboard!',
                        text: serverResponse.message,
                        showConfirmButton: true,
                        confirmButtonText: 'Take Me to Login',
                        confirmButtonColor: '#D19C97'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            window.location.href = serverResponse.redirect || 'login.php';
                        }
                    });
                } else {
                    // Something went wrong, let's help them fix it
                    let problemDescription = serverResponse.message;
                    
                    if (serverResponse.errors && serverResponse.errors.length > 0) {
                        problemDescription += '\n\n• ' + serverResponse.errors.join('\n• ');
                    }
                    
                    Swal.fire({
                        icon: 'info',
                        title: 'Let\'s Fix This!',
                        text: problemDescription,
                        confirmButtonColor: '#D19C97'
                    });
                }
            },
            error: function(xhr, status, error) {
                resetSubmitButton();
                
                let problemDescription = 'We had trouble connecting to our servers.';
                
                if (status === 'timeout') {
                    problemDescription = 'The request took too long. Please check your internet connection and try again.';
                } else if (xhr.status === 0) {
                    problemDescription = 'Couldn\'t connect to our servers. Please check your internet connection.';
                } else if (xhr.status >= 500) {
                    problemDescription = 'Our servers are having a moment. Please try again in a few minutes.';
                }
                
                Swal.fire({
                    icon: 'error',
                    title: 'Connection Problem',
                    text: problemDescription,
                    confirmButtonColor: '#D19C97'
                });
                
                // Log the error so developers can see what happened
                console.error('Registration failed:', xhr.responseText);
            }
        });
    });
    
    // Optional feature: Check if email is already taken as they type
    let emailCheckTimer;
    $('#customer_email').on('input', function() {
        const emailAddress = $(this).val().trim();
        
        // Clear the previous timer
        clearTimeout(emailCheckTimer);
        
        // Only check if the email format looks good
        if (validationRules.email.test(emailAddress)) {
            emailCheckTimer = setTimeout(function() {
                checkIfEmailIsAvailable(emailAddress);
            }, 800); // Wait a bit after they stop typing
        }
    });
    
    /**
     * Quietly check if this email is already taken
     * We don't want to annoy users, just help them
     */
    function checkIfEmailIsAvailable(emailAddress) {
        $.ajax({
            url: '../actions/check_email_action.php',
            type: 'POST',
            data: { email: emailAddress },
            dataType: 'json',
            success: function(response) {
                const emailField = $('#customer_email');
                
                if (response.exists) {
                    emailField.removeClass('is-valid').addClass('is-invalid');
                    emailField.next('.feedback').remove();
                    emailField.after('<div class="invalid-feedback feedback">This email is already registered. Maybe try logging in?</div>');
                } else if (emailField.hasClass('is-invalid')) {
                    // Re-check if it was previously marked invalid due to email availability
                    checkIfFieldLooksGood('email', emailAddress);
                }
            },
            error: function() {
                // Silently fail - don't bother the user with this
                console.log('Couldn\'t check email availability right now');
            }
        });
    }
    
    // Clear the form if needed (useful function for later)
    function startFresh() {
        $('#register-form')[0].reset();
        $('.is-valid, .is-invalid').removeClass('is-valid is-invalid');
        $('.valid-feedback, .invalid-feedback').remove();
    }
    
    // Add some nice visual touches when users interact with fields
    $('input[type="text"], input[type="email"], input[type="password"]').on('focus', function() {
        $(this).parent().addClass('focused');
    }).on('blur', function() {
        $(this).parent().removeClass('focused');
    });
    
    // Add a little celebration when they complete a field correctly
    $('.form-control').on('input', function() {
        if ($(this).hasClass('is-valid')) {
            $(this).addClass('animate__animated animate__pulse');
            setTimeout(() => {
                $(this).removeClass('animate__animated animate__pulse');
            }, 600);
        }
    });
});
