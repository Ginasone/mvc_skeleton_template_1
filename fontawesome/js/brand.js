$(document).ready(function() {
    
    // load brands when page loads
    loadBrands();
    
    // add brand form submit
    $('#add-brand-form').on('submit', function(e) {
        e.preventDefault();
        
        var brandName = $('#brand_name').val().trim();
        var catId = $('#cat_id').val();
        
        if (catId === '' || catId === '0') {
            alert('Please select a category');
            return;
        }
        
        if (brandName === '') {
            alert('Please enter a brand name');
            return;
        }
        
        if (brandName.length < 2) {
            alert('Brand name must be at least 2 characters');
            return;
        }
        
        // disable button
        var btn = $(this).find('button[type="submit"]');
        btn.prop('disabled', true).html('<span class="spinner-border spinner-border-sm me-2"></span>Adding...');
        
        $.ajax({
            url: '../actions/add_brand_action.php',
            type: 'POST',
            data: { 
                brand_name: brandName,
                cat_id: catId
            },
            dataType: 'json',
            success: function(response) {
                btn.prop('disabled', false).html('<i class="fa fa-plus me-2"></i>Add Brand');
                
                if (response.status === 'success') {
                    alert('Brand added successfully!');
                    $('#brand_name').val('');
                    $('#cat_id').val('');
                    loadBrands();
                } else {
                    alert(response.message);
                }
            },
            error: function() {
                btn.prop('disabled', false).html('<i class="fa fa-plus me-2"></i>Add Brand');
                alert('Failed to add brand. Please try again.');
            }
        });
    });
    
    // edit brand form submit
    $('#edit-brand-form').on('submit', function(e) {
        e.preventDefault();
        
        var brandId = $('#edit_brand_id').val();
        var brandName = $('#edit_brand_name').val().trim();
        var catId = $('#edit_cat_id').val();
        
        if (catId === '' || catId === '0') {
            alert('Please select a category');
            return;
        }
        
        if (brandName === '') {
            alert('Please enter a brand name');
            return;
        }
        
        var btn = $(this).find('button[type="submit"]');
        btn.prop('disabled', true).html('<span class="spinner-border spinner-border-sm me-2"></span>Updating...');
        
        $.ajax({
            url: '../actions/update_brand_action.php',
            type: 'POST',
            data: { 
                brand_id: brandId,
                brand_name: brandName,
                cat_id: catId
            },
            dataType: 'json',
            success: function(response) {
                btn.prop('disabled', false).html('Update Brand');
                
                if (response.status === 'success') {
                    alert('Brand updated successfully!');
                    $('#editModal').modal('hide');
                    loadBrands();
                } else {
                    alert(response.message);
                }
            },
            error: function() {
                btn.prop('disabled', false).html('Update Brand');
                alert('Failed to update brand. Please try again.');
            }
        });
    });
    
    // load brands function
    function loadBrands() {
        $.ajax({
            url: '../actions/fetch_brand_action.php',
            type: 'GET',
            dataType: 'json',
            success: function(response) {
                if (response.status === 'success') {
                    displayBrands(response.data);
                } else {
                    $('#brands-list').html('<div class="alert alert-danger">' + response.message + '</div>');
                }
            },
            error: function() {
                $('#brands-list').html('<div class="alert alert-danger">Failed to load brands</div>');
            }
        });
    }
    
    // display brands grouped by category
    function displayBrands(brands) {
        var html = '';
        
        if (brands.length === 0) {
            html = '<div class="alert alert-info"><i class="fa fa-info-circle me-2"></i>No brands found. Add your first brand!</div>';
        } else {
            // group brands by category
            var groupedBrands = {};
            for (var i = 0; i < brands.length; i++) {
                var brand = brands[i];
                var catName = brand.cat_name || 'Uncategorized';
                
                if (!groupedBrands[catName]) {
                    groupedBrands[catName] = [];
                }
                groupedBrands[catName].push(brand);
            }
            
            // display by category
            for (var category in groupedBrands) {
                html += '<div class="category-group">';
                html += '<div class="category-header"><strong><i class="fa fa-folder me-2"></i>' + category + '</strong></div>';
                html += '<div class="table-responsive">';
                html += '<table class="table table-sm table-hover">';
                html += '<thead><tr><th>Brand Name</th><th>Actions</th></tr></thead>';
                html += '<tbody>';
                
                var categoryBrands = groupedBrands[category];
                for (var j = 0; j < categoryBrands.length; j++) {
                    var brand = categoryBrands[j];
                    html += '<tr>';
                    html += '<td>' + brand.brand_name + '</td>';
                    html += '<td>';
                    html += '<button class="btn btn-sm btn-primary me-2" onclick="editBrand(' + brand.brand_id + ', \'' + escapeHtml(brand.brand_name) + '\', ' + brand.cat_id + ')">';
                    html += '<i class="fa fa-edit"></i> Edit</button>';
                    html += '<button class="btn btn-sm btn-danger" onclick="deleteBrand(' + brand.brand_id + ', \'' + escapeHtml(brand.brand_name) + '\')">';
                    html += '<i class="fa fa-trash"></i> Delete</button>';
                    html += '</td>';
                    html += '</tr>';
                }
                
                html += '</tbody></table>';
                html += '</div>';
                html += '</div>';
            }
        }
        
        $('#brands-list').html(html);
    }
    
    // escape HTML to prevent XSS
    function escapeHtml(text) {
        var map = {
            '&': '&amp;',
            '<': '&lt;',
            '>': '&gt;',
            '"': '&quot;',
            "'": '&#039;'
        };
        return text.replace(/[&<>"']/g, function(m) { return map[m]; });
    }
    
});

// edit brand function
function editBrand(brandId, brandName, catId) {
    $('#edit_brand_id').val(brandId);
    $('#edit_brand_name').val(brandName);
    $('#edit_cat_id').val(catId);
    $('#editModal').modal('show');
}

// delete brand function
function deleteBrand(brandId, brandName) {
    if (confirm('Are you sure you want to delete "' + brandName + '"? This action cannot be undone.')) {
        $.ajax({
            url: '../actions/delete_brand_action.php',
            type: 'POST',
            data: { brand_id: brandId },
            dataType: 'json',
            success: function(response) {
                if (response.status === 'success') {
                    alert('Brand deleted successfully!');
                    location.reload();
                } else {
                    alert(response.message);
                }
            },
            error: function() {
                alert('Failed to delete brand. Please try again.');
            }
        });
    }
}