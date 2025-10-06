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
		}
    </style>
</head>

<body class="p-4">
    <div class="container">
        <h2>Category Management</h2>

        <div class="card my-3">
            <div class="card-body">
                <form id="add-category-form" class="d-flex gap-2">
                    <input type="text" id="cat_name" name="cat_name" class="form-control" placeholder="New category name" required maxlength="100">
                    <button class="btn btn-primary" type="submit">Add Category</button>
                </form>
            </div>
        </div>

        <div class="card">
            <div class="card-body">
                <table class="table table-striped" id="categories-table">
                    <thead>
                        <tr>
                            
                            <th style="width: 70%;">Name</th>
                            <th style="width: 30%;">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
            
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

    <!-- scripts -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script src="../js/category.js"></script>
</body>
</html>
