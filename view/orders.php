<?php
session_start();

// Redirect if not logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: ../login/login.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>My Orders</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <style>
       body {
          background-color: #faf3e0;
       }
       .order-card {
          background: white;
          border-radius: 10px;
          padding: 20px;
          margin-bottom: 20px;
          box-shadow: 0 2px 8px rgba(0,0,0,0.1);
       }
       .order-header {
          border-bottom: 2px solid #f0f0f0;
          padding-bottom: 15px;
          margin-bottom: 15px;
       }
       .badge-pending { background-color: #ffc107; }
       .badge-processing { background-color: #17a2b8; }
       .badge-completed { background-color: #28a745; }
       .badge-cancelled { background-color: #dc3545; }
    </style>
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-light bg-light shadow-sm">
        <div class="container">
            <a class="navbar-brand" href="../index.php"><strong>CharlesStop</strong></a>
            <div class="d-flex">
                <a href="cart.php" class="btn btn-sm btn-outline-info me-2">
                    <i class="fas fa-shopping-cart"></i> Cart
                </a>
                <a href="../index.php" class="btn btn-sm btn-outline-secondary">Home</a>
            </div>
        </div>
    </nav>

    <div class="container mt-5">
        <h2 class="mb-4"><i class="fas fa-box"></i> My Orders</h2>
        
        <div id="orders-container">
            <!-- Orders will be loaded here -->
            <div class="text-center py-5">
                <i class="fas fa-spinner fa-spin fa-3x text-muted"></i>
                <p class="mt-3 text-muted">Loading your orders...</p>
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="../js/orders.js"></script>
</body>
</html>