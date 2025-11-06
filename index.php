<?php
session_start();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Home</title>
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
          padding: 80px 20px;
          text-align: center;
       }
       .welcome-card {
          background: white;
          border-radius: 12px;
          padding: 40px;
          max-width: 600px;
          margin: 0 auto;
          box-shadow: 0 4px 15px rgba(0,0,0,0.1);
       }
    </style>
</head>
<body>
    <div class="top-nav">
       <h3><i class="fas fa-shopping-bag"></i> CharlesStop</h3>
       <div class="nav-buttons">
          <?php if (isset($_SESSION['user_id'])): ?>
             <?php if (isset($_SESSION['user_role']) && $_SESSION['user_role'] == 1): ?>
                <a href="admin/category.php" class="btn btn-info">
                   <i class="fas fa-list"></i> Admin Panel
                </a>
             <?php endif; ?>
             <a href="login/logout.php" class="btn btn-danger" onclick="return confirm('Are you sure you want to log out?');">
                <i class="fas fa-sign-out-alt"></i> Logout
             </a>
          <?php else: ?>
             <a href="login/register.php" class="btn btn-primary">
                <i class="fas fa-user-plus"></i> Register
             </a>
             <a href="login/login.php" class="btn btn-secondary">
                <i class="fas fa-sign-in-alt"></i> Login
             </a>
          <?php endif; ?>
       </div>
    </div>

    <div class="welcome-container">
       <div class="welcome-card">
          <h1><i class="fas fa-store"></i> Welcome to CharlesStop</h1>
          <p class="text-muted mt-3">Your one-stop shop for everything you need!</p>
          <?php if (!isset($_SESSION['user_id'])): ?>
             <p class="mt-4">Please register or login to start shopping.</p>
          <?php else: ?>
             <p class="mt-4">
                <?php if (isset($_SESSION['user_role']) && $_SESSION['user_role'] == 1): ?>
                   Welcome Admin! Use the Admin Panel to manage your store.
                <?php else: ?>
                   Welcome back! Start browsing our products.
                <?php endif; ?>
             </p>
          <?php endif; ?>
       </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>