$(document).ready(function() {
    
    // load categories when page loads
    loadCategories();
    
    // add category form submit
    $('#add-category-form').on('submit', function(e) {
        e.preventDefault();
        
        var catName = $('#cat_name').val().trim();
        
        if (catName === '') {
            alert('Please enter a category name');
            return;
        }
        
        if (catName.length < 2) {
            alert('Category name must be at least 2 characters');
            return;
        }
        
        // disable button
        var btn = $(this).find('button[type="submit"]');
        btn.prop('disabled', true).html('<span class="spinner-border spinner-border-sm me-2"></span>Adding...');
        
        $.ajax({
            url: '../actions/add_category_action.php',
            type: 'POST',
            data: { cat_name: catName },
            dataType: 'json',
            success: function(response) {
                btn.prop('disabled', false).html('<i class="fa fa-plus me-2"></i>Add Category');
                
                if (response.status === 'success') {
                    alert('Category added successfully!');
                    $('#cat_name').val('');
                    loadCategories();
                } else {
                    alert(response.message);
                }
            },
            error: function() {
                btn.prop('disabled', false).html('<i class="fa fa-plus me-2"></i>Add Category');
                alert('Failed to add category. Please try again.');
            }
        });
    });
    
    // edit category form submit
    $('#edit-category-form').on('submit', function(e) {
        e.preventDefault();
        
        var catId = $('#edit_cat_id').val();
        var catName = $('#edit_cat_name').val().trim();
        
        if (catName === '') {
            alert('Please enter a category name');
            return;
        }
        
        var btn = $(this).find('button[type="submit"]');
        btn.prop('disabled', true).html('<span class="spinner-border spinner-border-sm me-2"></span>Updating...');
        
        $.ajax({
            url: '../actions/update_category_action.php',
            type: 'POST',
            data: { 
                cat_id: catId,
                cat_name: catName 
            },
            dataType: 'json',
            success: function(response) {
                btn.prop('disabled', false).html('Update Category');
                
                if (response.status === 'success') {
                    alert('Category updated successfully!');
                    $('#editModal').modal('hide');
                    loadCategories();
                } else {
                    alert(response.message);
                }
            },
            error: function() {
                btn.prop('disabled', false).html('Update Category');
                alert('Failed to update category. Please try again.');
            }
        });
    });
    
    // load categories function
    function loadCategories() {
        $.ajax({
            url: '../actions/fetch_category_action.php',
            type: 'GET',
            dataType: 'json',
            success: function(response) {
                if (response.status === 'success') {
                    displayCategories(response.data);
                } else {
                    $('#categories-list').html('<div class="alert alert-danger">' + response.message + '</div>');
                }
            },
            error: function() {
                $('#categories-list').html('<div class="alert alert-danger">Failed to load categories</div>');
            }
        });
    }
    
    // display categories
    function displayCategories(categories) {
        var html = '';
        
        if (categories.length === 0) {
            html = '<div class="alert alert-info"><i class="fa fa-info-circle me-2"></i>No categories found. Add your first category!</div>';
        } else {
            html = '<div class="table-responsive">';
            html += '<table class="table table-striped">';
            html += '<thead><tr><th>Category Name</th><th>Actions</th></tr></thead>';
            html += '<tbody>';
            
            for (var i = 0; i < categories.length; i++) {
                var category = categories[i];
                html += '<tr>';
                html += '<td>' + category.cat_name + '</td>';
                html += '<td>';
                html += '<button class="btn btn-sm btn-primary me-2" onclick="editCategory(' + category.cat_id + ', \'' + category.cat_name + '\')">';
                html += '<i class="fa fa-edit"></i> Edit</button>';
                html += '<button class="btn btn-sm btn-danger" onclick="deleteCategory(' + category.cat_id + ', \'' + category.cat_name + '\')">';
                html += '<i class="fa fa-trash"></i> Delete</button>';
                html += '</td>';
                html += '</tr>';
            }
            
            html += '</tbody></table>';
            html += '</div>';
        }
        
        $('#categories-list').html(html);
    }
    
});

// edit category function
function editCategory(catId, catName) {
    $('#edit_cat_id').val(catId);
    $('#edit_cat_name').val(catName);
    $('#editModal').modal('show');
}

// delete category function
function deleteCategory(catId, catName) {
    if (confirm('Are you sure you want to delete "' + catName + '"? This action cannot be undone.')) {
        $.ajax({
            url: '../actions/delete_category_action.php',
            type: 'POST',
            data: { cat_id: catId },
            dataType: 'json',
            success: function(response) {
                if (response.status === 'success') {
                    alert('Category deleted successfully!');
                    location.reload(); // reload to refresh categories
                } else {
                    alert(response.message);
                }
            },
            error: function() {
                alert('Failed to delete category. Please try again.');
            }
        });
    }
}