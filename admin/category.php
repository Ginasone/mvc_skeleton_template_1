<?php
require_once '../settings/core.php';

// check if user is logged in and is admin
require_admin();

$user_name = get_user_name();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Category Management - Admin</title>
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
    </style>
</head>

<body>
    <!-- Navigation -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <div class="container">
            <a class="navbar-brand" href="../index.php">
                <i class="fa fa-utensils me-2"></i>Taste of Africa - Admin
            </a>
            
            <div class="navbar-nav ms-auto">
                <span class="navbar-text me-3">Welcome, <?php echo htmlspecialchars($user_name); ?></span>
                <a class="btn btn-outline-light btn-sm" href="../index.php">Home</a>
                <a class="btn btn-outline-danger btn-sm ms-2" href="#" onclick="logout()">Logout</a>
            </div>
        </div>
    </nav>

    <div class="container mt-4">
        <div class="row">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header bg-primary text-white">
                        <h5 class="mb-0">
                            <i class="fa fa-list me-2"></i>Category Management
                        </h5>
                    </div>
                    <div class="card-body">
                        <!-- Categories List -->
                        <div id="categories-list">
                            <div class="text-center">
                                <div class="spinner-border text-primary" role="status">
                                    <span class="visually-hidden">Loading...</span>
                                </div>
                                <p class="mt-2">Loading categories...</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="col-md-4">
                <div class="card">
                    <div class="card-header bg-success text-white">
                        <h6 class="mb-0">
                            <i class="fa fa-plus me-2"></i>Add New Category
                        </h6>
                    </div>
                    <div class="card-body">
                        <form id="add-category-form">
                            <div class="mb-3">
                                <label for="cat_name" class="form-label">Category Name</label>
                                <input type="text" class="form-control" id="cat_name" name="cat_name" required maxlength="100">
                                <div class="form-text">Enter a unique category name</div>
                            </div>
                            <button type="submit" class="btn btn-success w-100">
                                <i class="fa fa-plus me-2"></i>Add Category
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Edit Modal -->
    <div class="modal fade" id="editModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Edit Category</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form id="edit-category-form">
                    <div class="modal-body">
                        <input type="hidden" id="edit_cat_id" name="cat_id">
                        <div class="mb-3">
                            <label for="edit_cat_name" class="form-label">Category Name</label>
                            <input type="text" class="form-control" id="edit_cat_name" name="cat_name" required maxlength="100">
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary">Update Category</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="../fontawesome/js/category.js"></script>
    
    <script>
        function logout() {
            if (confirm('Are you sure you want to logout?')) {
                // Simple logout - just redirect to a logout page that destroys session
                window.location.href = '../login/login.php?logout=1';
            }
        }
    </script>
</body>
</html>