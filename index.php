<?php
session_start();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Home - CharlesStop</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <style>
       body {
          background-color: #faf3e0;
          margin: 0;
          font-family: Arial, sans-serif;
       }
       .top-nav {
          background: #dfca92ff;
          padding: 15px 30px;
          box-shadow: 0 2px 5px rgba(0,0,0,0.1);
          display: flex;
          justify-content: space-between;
          align-items: center;
       }
       .top-nav h3 {
          margin: 0;
          color: #2c3e50;
       }
       .nav-buttons a {
          margin-left: 10px;
       }
       .welcome-container {
          padding: 60px 20px;
       }
       .welcome-card {
          background: white;
          border-radius: 12px;
          padding: 40px;
          max-width: 800px;
          margin: 0 auto;
          box-shadow: 0 4px 15px rgba(0,0,0,0.1);
       }
       .search-section {
          margin-top: 30px;
          padding: 30px;
          background: #f8f9fa;
          border-radius: 10px;
       }
       .btn-custom {
          background-color: #dfca92ff;
          border-color: #a58d4aff;
          color: #fff;
       }
       .btn-custom:hover {
          background-color: #7e6624ff;
          border-color: #7a6321ff;
          color: #fff;
       }
       .feature-box {
          text-align: center;
          padding: 20px;
          margin-top: 20px;
       }
       .feature-box i {
          font-size: 3rem;
          color: #dfca92ff;
          margin-bottom: 15px;
       }
       .login-prompt {
          background: #fff3cd;
          border: 1px solid #ffc107;
          padding: 20px;
          border-radius: 10px;
          margin-top: 20px;
       }
    </style>
</head>
<body>
    <div class="top-nav">
       <h3><i class="fas fa-shopping-bag"></i> CharlesStop</h3>
       <div class="nav-buttons">
          <?php if (isset($_SESSION['user_id'])): ?>
             <?php if (isset($_SESSION['user_role']) && $_SESSION['user_role'] == 1): ?>
                <!-- Admin Menu -->

                <a href="login/logout.php" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure you want to log out?');">
                   <i class="fas fa-sign-out-alt"></i> Logout
                </a>
             <?php else: ?>
                <!-- Regular User Menu -->
                <a href="view/all_products.php" class="btn btn-sm btn-info">
                   <i class="fas fa-th"></i> All Products
                </a>
                <a href="login/logout.php" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure you want to log out?');">
                   <i class="fas fa-sign-out-alt"></i> Logout
                </a>
             <?php endif; ?>
          <?php else: ?>
             <!-- Not Logged In Menu -->
             <a href="login/register.php" class="btn btn-sm btn-primary">
                <i class="fas fa-user-plus"></i> Register
             </a>
             <a href="login/login.php" class="btn btn-sm btn-secondary">
                <i class="fas fa-sign-in-alt"></i> Login
             </a>
          <?php endif; ?>
       </div>
    </div>

    <div class="welcome-container">
       <div class="welcome-card">
          <h1 class="text-center"><i class="fas fa-store"></i> Welcome to CharlesStop</h1>
          <p class="text-muted text-center mt-3">Your one-stop shop for everything you need!</p>

          <?php if (!isset($_SESSION['user_id'])): ?>
             <!-- NOT LOGGED IN - Show login prompt -->
             <div class="login-prompt text-center">
                <h4><i class="fas fa-lock"></i> Login Required</h4>
                <p class="mb-3">Please register or login to start shopping and browse our products.</p>

             </div>

             <!-- Feature Boxes for non-logged in users -->
             <div class="row mt-4">
                <div class="col-md-4">
                   <div class="feature-box">
                      <i class="fas fa-shipping-fast"></i>
                      <h6>Fast Delivery</h6>
                      <p class="text-muted small">Quick and reliable shipping</p>
                   </div>
                </div>
                <div class="col-md-4">
                   <div class="feature-box">
                      <i class="fas fa-shield-alt"></i>
                      <h6>Secure Payment</h6>
                      <p class="text-muted small">100% secure transactions</p>
                   </div>
                </div>
                <div class="col-md-4">
                   <div class="feature-box">
                      <i class="fas fa-headset"></i>
                      <h6>24/7 Support</h6>
                      <p class="text-muted small">We're here to help</p>
                   </div>
                </div>
             </div>

          <?php elseif (isset($_SESSION['user_role']) && $_SESSION['user_role'] == 1): ?>
             <!-- ADMIN LOGGED IN -->
             <p class="text-center mt-4">
                <strong>Welcome Admin!</strong> Use the features below to manage your store.
             </p>
             <div class="text-center mt-4">
                <a href="admin/category.php" class="btn btn-lg btn-info me-2">
                   <i class="fas fa-cog"></i> Manage Categories
                </a>
                <a href="admin/brand.php" class="btn btn-lg btn-info me-2">
                   <i class="fas fa-tags"></i> Manage Brands
                </a>
                <a href="admin/product.php" class="btn btn-lg btn-success">
                   <i class="fas fa-box"></i> Manage Products
                </a>
             </div>

          <?php else: ?>
             <!-- REGULAR USER LOGGED IN - Show search and browse -->
             <p class="text-center mt-4">
                <strong>Welcome back!</strong> Start browsing our amazing products.
             </p>

             <!-- Search Section -->
             <div class="search-section">
                <h4 class="text-center mb-4"><i class="fas fa-search"></i> Search Products</h4>
                <form action="view/product_search_result.php" method="GET">
                   <div class="input-group input-group-lg">
                      <input type="text" name="q" class="form-control" placeholder="Search for products by name or keywords..." required>
                      <button class="btn btn-custom" type="submit">
                         <i class="fas fa-search"></i> Search
                      </button>
                   </div>
                </form>

                <!-- Browse All Button -->
                <div class="text-center mt-4">
                   <a href="view/all_products.php" class="btn btn-lg btn-custom">
                      <i class="fas fa-shopping-bag"></i> Browse All Products
                   </a>
                </div>
             </div>

             <!-- Feature Boxes -->
             <div class="row mt-4">
                <div class="col-md-4">
                   <div class="feature-box">
                      <i class="fas fa-shipping-fast"></i>
                      <h6>Fast Delivery</h6>
                      <p class="text-muted small">Quick and reliable shipping</p>
                   </div>
                </div>
                <div class="col-md-4">
                   <div class="feature-box">
                      <i class="fas fa-shield-alt"></i>
                      <h6>Secure Payment</h6>
                      <p class="text-muted small">100% secure transactions</p>
                   </div>
                </div>
                <div class="col-md-4">
                   <div class="feature-box">
                      <i class="fas fa-headset"></i>
                      <h6>24/7 Support</h6>
                      <p class="text-muted small">We're here to help</p>
                   </div>
                </div>
             </div>
          <?php endif; ?>
       </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>