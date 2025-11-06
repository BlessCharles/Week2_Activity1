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
          margin: 0;
       }
       /* Sidebar Styles */
       .sidebar {
          position: fixed;
          left: 0;
          top: 0;
          height: 100vh;
          width: 250px;
          background: #dfca92ff;
          padding: 20px;
          box-shadow: 2px 0 5px rgba(0,0,0,0.1);
          overflow-y: auto;
       }
       .sidebar h4 {
          color: #2c3e50;
          margin-bottom: 30px;
          padding-bottom: 15px;
          border-bottom: 2px solid #c9b570;
       }
       .sidebar a {
          display: block;
          padding: 12px 15px;
          margin-bottom: 8px;
          color: #2c3e50;
          text-decoration: none;
          border-radius: 8px;
          transition: all 0.3s;
       }
       .sidebar a:hover {
          background: #c9b570;
          padding-left: 20px;
       }
       .sidebar a.active {
          background: #b8a55c;
          font-weight: bold;
       }
       .sidebar a i {
          margin-right: 10px;
          width: 20px;
       }
       /* Main Content */
       .main-content {
          margin-left: 250px;
          padding: 30px;
       }
       /* Product Cards */
       .products-grid {
          display: grid;
          grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
          gap: 25px;
          margin-top: 20px;
       }
       .product-card {
          background: white;
          border-radius: 12px;
          overflow: hidden;
          box-shadow: 0 3px 10px rgba(0,0,0,0.1);
          transition: transform 0.2s;
       }
       .product-card:hover {
          transform: translateY(-5px);
          box-shadow: 0 5px 15px rgba(0,0,0,0.15);
       }
       .product-card img {
          width: 100%;
          height: 200px;
          object-fit: cover;
       }
       .product-card-body {
          padding: 15px;
       }
       .product-title {
          font-size: 16px;
          font-weight: bold;
          margin-bottom: 8px;
          color: #2c3e50;
       }
       .product-price {
          font-size: 20px;
          color: #27ae60;
          font-weight: bold;
          margin: 10px 0;
       }
       .product-meta {
          font-size: 13px;
          color: #7f8c8d;
          margin-bottom: 5px;
       }
       .product-actions {
          display: flex;
          gap: 8px;
          margin-top: 12px;
       }
       .product-actions button {
          flex: 1;
          padding: 8px;
          font-size: 14px;
       }
       .header-section {
          display: flex;
          justify-content: space-between;
          align-items: center;
          margin-bottom: 25px;
       }
    </style>
</head>

<body>
    <!-- Sidebar -->
    <div class="sidebar">
        <h4><i class="fas fa-shopping-bag"></i> Admin Panel</h4>
        <a href="category.php">
            <i class="fas fa-list"></i> Categories
        </a>
        <a href="brand.php">
            <i class="fas fa-tag"></i> Brands
        </a>
        <a href="product.php" class="active">
            <i class="fas fa-box"></i> Products
        </a>
        <hr style="border-color: #c9b570; margin: 20px 0;">
        <a href="../index.php">
            <i class="fas fa-home"></i> Back to Home
        </a>
        <a href="../login/logout.php" onclick="return confirm('Are you sure you want to log out?');">
            <i class="fas fa-sign-out-alt"></i> Logout
        </a>
    </div>

    <!-- Main Content -->
    <div class="main-content">
        <div class="header-section">
            <h2><i class="fas fa-box"></i> Product Management</h2>
            <button class="btn btn-success btn-lg" id="add-product-btn">
                <i class="fas fa-plus"></i> Add New Product
            </button>
        </div>

        <!-- Products Grid -->
        <div class="products-grid" id="products-grid">
            <!-- Populated by JS -->
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