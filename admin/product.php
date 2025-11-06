<?php

require_once '../settings/core.php';

if (!isLoggedIn() || !isAdmin()) {
    header("Location: ../login/login.php");
    exit();
}

?>

<!doctype html>
<html>
<head>
    <meta charset="utf-8">
    <title>Product Management</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <style>
       body {
          background-color: #faf3e0;
       }
       .product-image {
          width: 80px;
          height: 80px;
          object-fit: cover;
          border-radius: 5px;
       }
    </style>
</head>

<body class="p-4">
    <div class="container">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h2>Product Management</h2>
            <div>
                <button class="btn btn-success" id="add-product-btn">
                    <i class="fas fa-plus"></i> Add Product
                </button>
                <a href="../index.php" class="btn btn-secondary">
                    <i class="fas fa-home"></i> Home
                </a>
            </div>
        </div>

        <div class="card">
            <div class="card-body">
                <h5 class="card-title">All Products</h5>
                <div class="table-responsive">
                    <table class="table table-striped" id="products-table">
                        <thead>
                            <tr>
                                <th>Image</th>
                                <th>Title</th>
                                <th>Category</th>
                                <th>Brand</th>
                                <th>Price</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <!-- Populated by JS -->
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Add/Edit Product Modal -->
    <div class="modal fade" id="productModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content">
                <form id="product-form" enctype="multipart/form-data">
                    <div class="modal-header">
                        <h5 class="modal-title" id="modalTitle">Add Product</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <input type="hidden" id="product_id" name="product_id">
                        <input type="hidden" id="existing_image" name="existing_image">

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Category *</label>
                                <select id="cat_id" name="cat_id" class="form-select" required>
                                    <option value="">Select Category</option>
                                </select>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Brand *</label>
                                <select id="brand_id" name="brand_id" class="form-select" required>
                                    <option value="">Select Brand</option>
                                </select>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Product Title *</label>
                            <input type="text" id="product_title" name="product_title" class="form-control" required maxlength="200">
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Price *</label>
                            <input type="number" id="product_price" name="product_price" class="form-control" step="0.01" min="0" required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Description</label>
                            <textarea id="product_desc" name="product_desc" class="form-control" rows="3" maxlength="500"></textarea>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Product Image</label>
                            <input type="file" id="product_image" name="product_image" class="form-control" accept="image/*">
                            <small class="text-muted">Leave empty to keep existing image when editing</small>
                            <div id="image-preview" class="mt-2"></div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Keywords</label>
                            <input type="text" id="product_keywords" name="product_keywords" class="form-control" maxlength="100" placeholder="e.g., shoes, running, sports">
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button class="btn btn-primary" type="submit" id="submit-btn">Save Product</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Scripts -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="../js/product.js"></script>
</body>
</html>