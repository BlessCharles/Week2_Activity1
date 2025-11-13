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
    <title>Checkout</title>
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
       .checkout-item {
          background: white;
          border-radius: 8px;
          padding: 15px;
          margin-bottom: 10px;
          display: flex;
          justify-content: space-between;
          align-items: center;
       }
       .checkout-item img {
          width: 60px;
          height: 60px;
          object-fit: cover;
          border-radius: 5px;
          margin-right: 15px;
       }
       .order-summary {
          background: white;
          border-radius: 10px;
          padding: 25px;
          box-shadow: 0 2px 8px rgba(0,0,0,0.1);
       }
       .summary-row {
          display: flex;
          justify-content: space-between;
          padding: 10px 0;
          border-bottom: 1px solid #eee;
       }
       .summary-row.total {
          border-bottom: none;
          font-size: 1.3em;
          font-weight: bold;
          color: #27ae60;
       }
    </style>
</head>
<body>
    <div class="top-nav d-flex justify-content-between align-items-center">
       <h3><i class="fas fa-shopping-bag"></i> My Shop</h3>
       <div>
          <a href="cart.php" class="btn btn-outline-secondary">
             <i class="fas fa-arrow-left"></i> Back to Cart
          </a>
       </div>
    </div>

    <div class="container">
        <h2 class="mb-4"><i class="fas fa-credit-card"></i> Checkout</h2>
        
        <div class="row">
            <!-- Order Items -->
            <div class="col-md-7">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">Order Items</h5>
                        <div id="checkout-items">
                            <!-- Items will be loaded here -->
                        </div>
                    </div>
                </div>
            </div>

            <!-- Order Summary -->
            <div class="col-md-5">
                <div class="order-summary">
                    <h5 class="mb-3">Order Summary</h5>
                    <div class="summary-row">
                        <span>Subtotal:</span>
                        <span id="subtotal-amount">GH₵ 0.00</span>
                    </div>
                    <div class="summary-row total">
                        <span>Total:</span>
                        <span id="total-amount">GH₵ 0.00</span>
                    </div>
                    
                    <button class="btn btn-success btn-lg w-100 mt-4" id="simulate-payment-btn">
                        <i class="fas fa-lock"></i> Simulate Payment
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Payment Modal -->
    <div class="modal fade" id="paymentModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header bg-success text-white">
                    <h5 class="modal-title"><i class="fas fa-credit-card"></i> Simulate Payment</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body text-center py-4">
                    <i class="fas fa-money-bill-wave" style="font-size: 60px; color: #27ae60;"></i>
                    <h4 class="mt-3">Total Amount</h4>
                    <h2 class="text-success" id="modal-total-amount">GH₵ 0.00</h2>
                    <p class="text-muted mt-3">This is a simulated payment. Click confirm to complete your order.</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-success" id="confirm-payment-btn">
                        <i class="fas fa-check"></i> Yes, I've Paid
                    </button>
                </div>
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="../js/checkout.js"></script>
</body>
</html>