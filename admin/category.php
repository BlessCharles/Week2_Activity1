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
    <title>Category Management</title>
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
    </style>
</head>

<body>
    <!-- Sidebar -->
    <div class="sidebar">
        <h4><i class="fas fa-shopping-bag"></i> Admin Panel</h4>
        <a href="category.php" class="active">
            <i class="fas fa-list"></i> Categories
        </a>
        <a href="brand.php">
            <i class="fas fa-tag"></i> Brands
        </a>
        <a href="product.php">
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
        <h2><i class="fas fa-list"></i> Category Management</h2>

        <div class="card my-4">
            <div class="card-body">
                <h5 class="card-title">Add New Category</h5>
                <form id="add-category-form" class="d-flex gap-2">
                    <input type="text" id="cat_name" name="cat_name" class="form-control" placeholder="New category name" required maxlength="100">
                    <button class="btn btn-primary" type="submit">
                        <i class="fas fa-plus"></i> Add Category
                    </button>
                </form>
            </div>
        </div>

        <div class="card">
            <div class="card-body">
                <h5 class="card-title">All Categories</h5>
                <table class="table table-striped" id="categories-table">
                    <thead>
                        <tr>
                            <th style="width: 70%;">Name</th>
                            <th style="width: 30%;">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <!-- Populated by JS -->
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Edit Modal -->
    <div class="modal fade" id="editModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <form id="edit-category-form">
                    <div class="modal-header">
                        <h5 class="modal-title">Edit Category</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <input type="hidden" id="edit_cat_id" name="cat_id">
                        <div class="mb-3">
                            <label class="form-label">Category name</label>
                            <input type="text" id="edit_cat_name" name="cat_name" class="form-control" maxlength="100" required>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button class="btn btn-primary" type="submit">Save changes</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Scripts -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="../js/category.js"></script>
</body>
</html>