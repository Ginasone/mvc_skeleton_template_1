$(document).ready(function() {
    
    var uploadedImagePath = '';
    var isEditing = $('#product_id').length > 0;
    
    // handle file selection and preview
    $('#product_image_file').on('change', function(e) {
        var file = e.target.files[0];
        
        if (!file) {
            return;
        }
        
        // validate file type
        var validTypes = ['image/jpeg', 'image/jpg', 'image/png', 'image/gif'];
        if (!validTypes.includes(file.type)) {
            alert('Invalid file type. Please select a JPG, PNG, or GIF image.');
            $(this).val('');
            return;
        }
        
        // validate file size (5MB)
        var maxSize = 5 * 1024 * 1024;
        if (file.size > maxSize) {
            alert('File size too large. Maximum size is 5MB.');
            $(this).val('');
            return;
        }
        
        // show preview
        var reader = new FileReader();
        reader.onload = function(e) {
            $('#image-preview-container').html(
                '<img src="' + e.target.result + '" class="image-preview mt-2" alt="Preview">'
            );
        };
        reader.readAsDataURL(file);
        
        // upload image
        uploadImage(file);
    });
    
    // upload image function
    function uploadImage(file) {
        var formData = new FormData();
        formData.append('product_image', file);
        formData.append('product_id', isEditing ? $('#product_id').val() : 0);
        
        $.ajax({
            url: '../actions/upload_product_image_action.php',
            type: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            dataType: 'json',
            success: function(response) {
                if (response.status === 'success') {
                    uploadedImagePath = response.file_path;
                    $('#product_image').val(uploadedImagePath);
                    console.log('Image uploaded:', uploadedImagePath);
                } else {
                    alert('Image upload failed: ' + response.message);
                    $('#product_image_file').val('');
                    $('#image-preview-container').html('');
                }
            },
            error: function() {
                alert('Image upload failed. Please try again.');
                $('#product_image_file').val('');
                $('#image-preview-container').html('');
            }
        });
    }
    
    // filter brands by selected category
    $('#cat_id').on('change', function() {
        var selectedCat = $(this).val();
        var brandSelect = $('#brand_id');
        
        // show all brands if no category selected
        if (!selectedCat) {
            brandSelect.find('option').show();
            return;
        }
        
        // filter brands by category
        brandSelect.find('option').each(function() {
            var brandCat = $(this).data('cat');
            if (brandCat == selectedCat || $(this).val() === '') {
                $(this).show();
            } else {
                $(this).hide();
            }
        });
        
        // reset brand selection if current brand doesn't match category
        var currentBrand = brandSelect.find('option:selected');
        if (currentBrand.data('cat') != selectedCat && currentBrand.val() !== '') {
            brandSelect.val('');
        }
    });
    
    // product form submit
    $('#product-form').on('submit', function(e) {
        e.preventDefault();
        
        var catId = $('#cat_id').val();
        var brandId = $('#brand_id').val();
        var productTitle = $('#product_title').val().trim();
        var productPrice = $('#product_price').val();
        var productDesc = $('#product_desc').val().trim();
        var productKeywords = $('#product_keywords').val().trim();
        var productImage = $('#product_image').val();
        
        // validation
        if (!catId || catId === '0') {
            alert('Please select a category');
            return;
        }
        
        if (!brandId || brandId === '0') {
            alert('Please select a brand');
            return;
        }
        
        if (productTitle === '' || productTitle.length < 3) {
            alert('Product title must be at least 3 characters');
            return;
        }
        
        if (!productPrice || parseFloat(productPrice) < 0) {
            alert('Please enter a valid price');
            return;
        }
        
        if (productDesc === '' || productDesc.length < 10) {
            alert('Product description must be at least 10 characters');
            return;
        }
        
        // disable button
        var btn = $(this).find('button[type="submit"]');
        var btnText = isEditing ? 'Updating...' : 'Adding...';
        btn.prop('disabled', true).html('<span class="spinner-border spinner-border-sm me-2"></span>' + btnText);
        
        // prepare data
        var actionUrl = isEditing ? '../actions/update_product_action.php' : '../actions/add_product_action.php';
        var postData = {
            cat_id: catId,
            brand_id: brandId,
            product_title: productTitle,
            product_price: productPrice,
            product_desc: productDesc,
            product_image: productImage,
            product_keywords: productKeywords
        };
        
        if (isEditing) {
            postData.product_id = $('#product_id').val();
            // if no new image uploaded, use existing
            if (!productImage) {
                postData.product_image = $('#existing_image').val();
            }
        }
        
        $.ajax({
            url: actionUrl,
            type: 'POST',
            data: postData,
            dataType: 'json',
            success: function(response) {
                var successIcon = isEditing ? 'fa-save' : 'fa-plus';
                var successText = isEditing ? 'Update Product' : 'Add Product';
                btn.prop('disabled', false).html('<i class="fa ' + successIcon + ' me-2"></i>' + successText);
                
                if (response.status === 'success') {
                    alert(response.message);
                    window.location.href = 'product.php';
                } else {
                    alert(response.message);
                }
            },
            error: function() {
                var successIcon = isEditing ? 'fa-save' : 'fa-plus';
                var successText = isEditing ? 'Update Product' : 'Add Product';
                btn.prop('disabled', false).html('<i class="fa ' + successIcon + ' me-2"></i>' + successText);
                alert('Failed to save product. Please try again.');
            }
        });
    });
    
    // trigger category change on page load to filter brands
    if (isEditing) {
        $('#cat_id').trigger('change');
    }
    
});