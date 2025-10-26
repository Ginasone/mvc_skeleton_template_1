<?php
require_once '../settings/core.php';
require_once '../controllers/category_controller.php';
require_once '../controllers/brand_controller.php';
require_once '../controllers/product_controller.php';

// check if user is logged in and is admin
require_admin();

$user_name = get_user_name();
$user_id = get_user_id();

// get user categories and brands for dropdowns
$categories = get_user_categories_ctr($user_id);
$brands = get_user_brands_ctr($user_id);
$products = get_user_products_ctr($user_id);

// check if editing a product
$editing = false;
$edit_product = null;
if (isset($_GET['edit']) && !empty($_GET['edit'])) {
    $product_id = (int)$_GET['edit'];
    $edit_product = get_product_by_id_ctr($product_id, $user_id);
    if ($edit_product) {
        $editing = true;
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Product Management - Admin</title>
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
        .product-card {
            transition: transform 0.2s;
        }
        .product-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 4px 8px rgba(0,0,0,0.1);
        }
        .product-image {
            width: 100%;
            height: 200px;
            object-fit: cover;
        }
        .image-preview {
            max-width: 200px;
            max-height: 200px;
            margin-top: 10px;
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
                <a class="btn btn-outline-light btn-sm me-2" href="category.php">Categories</a>
                <a class="btn btn-outline-light btn-sm me-2" href="brand.php">Brands</a>
                <a class="btn btn-outline-light btn-sm me-2" href="../index.php">Home</a>
                <a class="btn btn-outline-danger btn-sm" href="#" onclick="logout()">Logout</a>
            </div>
        </div>
    </nav>

    <div class="container mt-4">
        <div class="row">
            <!-- Product Form -->
            <div class="col-md-4">
                <div class="card">
                    <div class="card-header <?php echo $editing ? 'bg-warning' : 'bg-success'; ?> text-white">
                        <h6 class="mb-0">
                            <i class="fa fa-<?php echo $editing ? 'edit' : 'plus'; ?> me-2"></i>
                            <?php echo $editing ? 'Edit Product' : 'Add New Product'; ?>
                        </h6>
                    </div>
                    <div class="card-body">
                        <?php if (!$categories || count($categories) == 0): ?>
                            <div class="alert alert-warning">
                                <i class="fa fa-exclamation-triangle me-2"></i>
                                Please create at least one category first.
                                <a href="category.php" class="alert-link">Go to Categories</a>
                            </div>
                        <?php elseif (!$brands || count($brands) == 0): ?>
                            <div class="alert alert-warning">
                                <i class="fa fa-exclamation-triangle me-2"></i>
                                Please create at least one brand first.
                                <a href="brand.php" class="alert-link">Go to Brands</a>
                            </div>
                        <?php else: ?>
                            <form id="product-form" enctype="multipart/form-data">
                                <?php if ($editing): ?>
                                    <input type="hidden" id="product_id" name="product_id" value="<?php echo $edit_product['product_id']; ?>">
                                    <input type="hidden" id="existing_image" value="<?php echo htmlspecialchars($edit_product['product_image']); ?>">
                                <?php endif; ?>
                                
                                <div class="mb-3">
                                    <label for="cat_id" class="form-label">Category</label>
                                    <select class="form-select" id="cat_id" name="cat_id" required>
                                        <option value="">Select Category</option>
                                        <?php foreach ($categories as $category): ?>
                                            <option value="<?php echo $category['cat_id']; ?>" 
                                                <?php echo ($editing && $edit_product['product_cat'] == $category['cat_id']) ? 'selected' : ''; ?>>
                                                <?php echo htmlspecialchars($category['cat_name']); ?>
                                            </option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                                
                                <div class="mb-3">
                                    <label for="brand_id" class="form-label">Brand</label>
                                    <select class="form-select" id="brand_id" name="brand_id" required>
                                        <option value="">Select Brand</option>
                                        <?php foreach ($brands as $brand): ?>
                                            <option value="<?php echo $brand['brand_id']; ?>" 
                                                data-cat="<?php echo $brand['cat_id']; ?>"
                                                <?php echo ($editing && $edit_product['product_brand'] == $brand['brand_id']) ? 'selected' : ''; ?>>
                                                <?php echo htmlspecialchars($brand['brand_name']); ?> (<?php echo htmlspecialchars($brand['cat_name']); ?>)
                                            </option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                                
                                <div class="mb-3">
                                    <label for="product_title" class="form-label">Product Title</label>
                                    <input type="text" class="form-control" id="product_title" name="product_title" required 
                                        value="<?php echo $editing ? htmlspecialchars($edit_product['product_title']) : ''; ?>">
                                </div>
                                
                                <div class="mb-3">
                                    <label for="product_price" class="form-label">Price</label>
                                    <input type="number" step="0.01" class="form-control" id="product_price" name="product_price" required 
                                        value="<?php echo $editing ? $edit_product['product_price'] : ''; ?>">
                                </div>
                                
                                <div class="mb-3">
                                    <label for="product_desc" class="form-label">Description</label>
                                    <textarea class="form-control" id="product_desc" name="product_desc" rows="3" required><?php echo $editing ? htmlspecialchars($edit_product['product_desc']) : ''; ?></textarea>
                                </div>
                                
                                <div class="mb-3">
                                    <label for="product_keywords" class="form-label">Keywords</label>
                                    <input type="text" class="form-control" id="product_keywords" name="product_keywords" 
                                        placeholder="e.g., african, food, spicy"
                                        value="<?php echo $editing ? htmlspecialchars($edit_product['product_keywords']) : ''; ?>">
                                    <div class="form-text">Separate keywords with commas</div>
                                </div>
                                
                                <div class="mb-3">
                                    <label for="product_image_file" class="form-label">Product Image</label>
                                    <input type="file" class="form-control" id="product_image_file" accept="image/*">
                                    <input type="hidden" id="product_image" name="product_image" value="<?php echo $editing ? htmlspecialchars($edit_product['product_image']) : ''; ?>">
                                    <div class="form-text">Max size: 5MB (JPG, PNG, GIF)</div>
                                    <div id="image-preview-container"></div>
                                    <?php if ($editing && !empty($edit_product['product_image'])): ?>
                                        <div class="mt-2">
                                            <small class="text-muted">Current image:</small><br>
                                            <img src="../<?php echo htmlspecialchars($edit_product['product_image']); ?>" class="image-preview" alt="Current product">
                                        </div>
                                    <?php endif; ?>
                                </div>
                                
                                <button type="submit" class="btn <?php echo $editing ? 'btn-warning' : 'btn-success'; ?> w-100">
                                    <i class="fa fa-<?php echo $editing ? 'save' : 'plus'; ?> me-2"></i>
                                    <?php echo $editing ? 'Update Product' : 'Add Product'; ?>
                                </button>
                                
                                <?php if ($editing): ?>
                                    <a href="product.php" class="btn btn-secondary w-100 mt-2">
                                        <i class="fa fa-times me-2"></i>Cancel Edit
                                    </a>
                                <?php endif; ?>
                            </form>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
            
            <!-- Products List -->
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header bg-primary text-white">
                        <h5 class="mb-0">
                            <i class="fa fa-box me-2"></i>Product Management
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="row" id="products-list">
                            <?php if (!$products || count($products) == 0): ?>
                                <div class="col-12">
                                    <div class="alert alert-info">
                                        <i class="fa fa-info-circle me-2"></i>No products found. Add your first product!
                                    </div>
                                </div>
                            <?php else: ?>
                                <?php foreach ($products as $product): ?>
                                    <div class="col-md-6 mb-3">
                                        <div class="card product-card h-100">
                                            <?php if (!empty($product['product_image'])): ?>
                                                <img src="../<?php echo htmlspecialchars($product['product_image']); ?>" 
                                                     class="card-img-top product-image" 
                                                     alt="<?php echo htmlspecialchars($product['product_title']); ?>">
                                            <?php else: ?>
                                                <div class="card-img-top product-image bg-secondary d-flex align-items-center justify-content-center">
                                                    <i class="fa fa-image fa-3x text-white"></i>
                                                </div>
                                            <?php endif; ?>
                                            <div class="card-body">
                                                <h6 class="card-title"><?php echo htmlspecialchars($product['product_title']); ?></h6>
                                                <p class="card-text">
                                                    <small class="text-muted">
                                                        <i class="fa fa-tag me-1"></i><?php echo htmlspecialchars($product['cat_name']); ?> | 
                                                        <i class="fa fa-tags me-1"></i><?php echo htmlspecialchars($product['brand_name']); ?>
                                                    </small>
                                                </p>
                                                <p class="card-text"><strong>$<?php echo number_format($product['product_price'], 2); ?></strong></p>
                                                <p class="card-text"><small><?php echo substr(htmlspecialchars($product['product_desc']), 0, 100); ?>...</small></p>
                                            </div>
                                            <div class="card-footer">
                                                <a href="product.php?edit=<?php echo $product['product_id']; ?>" class="btn btn-sm btn-warning">
                                                    <i class="fa fa-edit"></i> Edit
                                                </a>
                                                <button class="btn btn-sm btn-danger" onclick="deleteProduct(<?php echo $product['product_id']; ?>, '<?php echo addslashes($product['product_title']); ?>')">
                                                    <i class="fa fa-trash"></i> Delete
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="../fontawesome/js/product.js"></script>
    
    <script>
        function logout() {
            if (confirm('Are you sure you want to logout?')) {
                window.location.href = '../index.php?logout=1';
            }
        }
        
        function deleteProduct(productId, productTitle) {
            if (confirm('Are you sure you want to delete "' + productTitle + '"? This action cannot be undone.')) {
                // This will be handled in product.js
                $.ajax({
                    url: '../actions/delete_product_action.php',
                    type: 'POST',
                    data: { product_id: productId },
                    dataType: 'json',
                    success: function(response) {
                        if (response.status === 'success') {
                            alert('Product deleted successfully!');
                            location.reload();
                        } else {
                            alert(response.message);
                        }
                    },
                    error: function() {
                        alert('Failed to delete product. Please try again.');
                    }
                });
            }
        }
    </script>
</body>
</html>