<?php
session_start();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Shopping Cart</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <style>
       body {
          background-color: #faf3e0;
       }
       .top-nav {
          background: #dfca92ff;
          padding: 15px 30px;
          box-shadow: 0 2px 5px rgba(0,0,0,0.1);
          margin-bottom: 30px;
       }
       .cart-item {
          background: white;
          border-radius: 10px;
          padding: 20px;
          margin-bottom: 15px;
          box-shadow: 0 2px 8px rgba(0,0,0,0.1);
       }
       .cart-item img {
          width: 100px;
          height: 100px;
          object-fit: cover;
          border-radius: 8px;
       }
       .qty-input {
          width: 80px;
          text-align: center;
       }
       .cart-summary {
          background: white;
          border-radius: 10px;
          padding: 25px;
          box-shadow: 0 2px 8px rgba(0,0,0,0.1);
          position: sticky;
          top: 20px;
       }
       .empty-cart {
          text-align: center;
          padding: 80px 20px;
       }
    </style>
</head>
<body>
    <div class="top-nav d-flex justify-content-between align-items-center">
       <h3><i class="fas fa-shopping-bag"></i>CharlesStop</h3>
       <div>
          <a href="../index.php" class="btn btn-outline-secondary">
             <i class="fas fa-home"></i> Home
          </a>
          <?php if (isset($_SESSION['user_id'])): ?>
             <a href="../login/logout.php" class="btn btn-outline-danger">
                <i class="fas fa-sign-out-alt"></i> Logout
             </a>
          <?php endif; ?>
       </div>
    </div>

    <div class="container">
        <h2 class="mb-4"><i class="fas fa-shopping-cart"></i> Shopping Cart</h2>
        
        <div class="row" id="cart-content">
            <!-- Cart items will be loaded here -->
        </div>

        <!-- Empty Cart Message -->
        <div class="empty-cart d-none" id="empty-cart-message">
            <i class="fas fa-shopping-cart" style="font-size: 80px; color: #bdc3c7;"></i>
            <h3 class="mt-3">Your cart is empty</h3>
            <p class="text-muted">Add some products to get started!</p>
            <a href="all_products.php" class="btn btn-primary btn-lg mt-3">
                <i class="fas fa-shopping-bag"></i> Continue Shopping
            </a>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="../js/cart.js"></script>
</body>
</html>