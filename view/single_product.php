<?php
session_start();
require_once '../controllers/product_controller.php';

$product_id = isset($_GET['id']) ? intval($_GET['id']) : 0;

if ($product_id <= 0) {
    header("Location: all_products.php");
    exit();
}

$product = view_single_product_ctr($product_id);

if (!$product) {
    header("Location: all_products.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($product['product_title']); ?> - Taste of Africa</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #faf3e0;
        }
        .product-image-large {
            width: 100%;
            max-height: 500px;
            object-fit: contain;
            border-radius: 10px;
        }
        .price-tag {
            font-size: 2.5rem;
            font-weight: bold;
            color: #dfca92ff;
        }
        .btn-custom {
            background-color: #dfca92ff;
            border-color: #a58d4aff;
            color: #fff;
            padding: 15px 30px;
            font-size: 1.2rem;
        }
        .btn-custom:hover {
            background-color: #7e6624ff;
            border-color: #7a6321ff;
            color: #fff;
        }
        .product-details {
            background: white;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.05);
        }
        .cart-badge {
            position: absolute;
            top: -8px;
            right: -8px;
            background: #dc3545;
            color: white;
            border-radius: 50%;
            padding: 2px 6px;
            font-size: 11px;
            font-weight: bold;
        }
        .qty-selector {
            display: flex;
            align-items: center;
            gap: 10px;
            margin-bottom: 20px;
        }
        .qty-input {
            width: 80px;
            text-align: center;
        }
    </style>
</head>
<body>
    <!-- Navigation -->
    <nav class="navbar navbar-expand-lg navbar-light bg-light shadow-sm">
        <div class="container">
            <a class="navbar-brand" href="../index.php"><strong>CharlesStop</strong></a>
            <div class="d-flex">
                <a href="cart.php" class="btn btn-sm btn-outline-info me-2 position-relative">
                    <i class="fas fa-shopping-cart"></i> Cart
                    <span class="cart-badge" id="cart-count">0</span>
                </a>
                <a href="all_products.php" class="btn btn-sm btn-outline-secondary me-2">All Products</a>
                <a href="../index.php" class="btn btn-sm btn-outline-secondary">Home</a>
            </div>
        </div>
    </nav>

    <div class="container mt-5">
        <div class="row">
            <!-- Product Image -->
            <div class="col-md-6">
                <?php if ($product['product_image']): ?>
                    <img src="../<?php echo htmlspecialchars($product['product_image']); ?>"
                         class="product-image-large"
                         alt="<?php echo htmlspecialchars($product['product_title']); ?>">
                <?php else: ?>
                    <div class="product-image-large bg-secondary d-flex align-items-center justify-content-center" style="height: 500px;">
                        <i class="fas fa-image fa-5x text-white"></i>
                    </div>
                <?php endif; ?>
            </div>

            <!-- Product Details -->
            <div class="col-md-6">
                <div class="product-details">
                    <div class="mb-3">
                        <span class="badge bg-secondary fs-6"><?php echo htmlspecialchars($product['cat_name']); ?></span>
                        <span class="badge bg-info fs-6"><?php echo htmlspecialchars($product['brand_name']); ?></span>
                    </div>

                    <h1 class="mb-3"><?php echo htmlspecialchars($product['product_title']); ?></h1>

                    <p class="price-tag mb-4">GH₵ <?php echo number_format($product['product_price'], 2); ?></p>

                    <div class="mb-4">
                        <h5>Description</h5>
                        <p class="text-muted">
                            <?php echo $product['product_desc'] ? htmlspecialchars($product['product_desc']) : 'No description available.'; ?>
                        </p>
                    </div>

                    <?php if ($product['product_keywords']): ?>
                        <div class="mb-4">
                            <h6>Keywords</h6>
                            <p class="text-muted">
                                <?php
                                $keywords = explode(',', $product['product_keywords']);
                                foreach ($keywords as $keyword) {
                                    echo '<span class="badge bg-light text-dark me-1">' . htmlspecialchars(trim($keyword)) . '</span>';
                                }
                                ?>
                            </p>
                        </div>
                    <?php endif; ?>

                    <!-- Quantity Selector -->
                    <div class="qty-selector">
                        <label><strong>Quantity:</strong></label>
                        <button class="btn btn-outline-secondary" id="qty-decrease">
                            <i class="fas fa-minus"></i>
                        </button>
                        <input type="number" class="form-control qty-input" id="product-qty" value="1" min="1">
                        <button class="btn btn-outline-secondary" id="qty-increase">
                            <i class="fas fa-plus"></i>
                        </button>
                    </div>

                    <div class="d-grid gap-2">
                        <button class="btn btn-custom" id="add-to-cart-single" data-product-id="<?php echo $product['product_id']; ?>">
                            <i class="fas fa-shopping-cart"></i> Add to Cart
                        </button>
                        <a href="all_products.php" class="btn btn-outline-secondary">
                            <i class="fas fa-arrow-left"></i> Back to Products
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        $(document).ready(function() {
            updateCartCount();

            // Quantity controls
            $('#qty-decrease').on('click', function() {
                let qty = parseInt($('#product-qty').val());
                if (qty > 1) {
                    $('#product-qty').val(qty - 1);
                }
            });

            $('#qty-increase').on('click', function() {
                let qty = parseInt($('#product-qty').val());
                $('#product-qty').val(qty + 1);
            });

            // Add to Cart
            $('#add-to-cart-single').on('click', function() {
                const btn = $(this);
                const productId = btn.data('product-id');
                const qty = parseInt($('#product-qty').val());
                
                if (qty < 1) {
                    Swal.fire('Error', 'Please enter a valid quantity', 'error');
                    return;
                }

                const originalText = btn.html();
                btn.prop('disabled', true).html('<i class="fas fa-spinner fa-spin"></i> Adding...');
                
                $.ajax({
                    url: '../actions/add_to_cart_action.php',
                    method: 'POST',
                    data: { p_id: productId, qty: qty },
                    dataType: 'json',
                    success: function(res) {
                        if (res.status === 'success') {
                            updateCartCount();
                            Swal.fire({
                                icon: 'success',
                                title: 'Added to Cart!',
                                text: res.message,
                                showCancelButton: true,
                                confirmButtonText: 'View Cart',
                                cancelButtonText: 'Continue Shopping'
                            }).then((result) => {
                                if (result.isConfirmed) {
                                    window.location.href = 'cart.php';
                                }
                            });
                            btn.prop('disabled', false).html(originalText);
                            $('#product-qty').val(1); // Reset quantity
                        } else {
                            Swal.fire('Error', res.message, 'error');
                            btn.prop('disabled', false).html(originalText);
                        }
                    },
                    error: function() {
                        Swal.fire('Error', 'Failed to add to cart', 'error');
                        btn.prop('disabled', false).html(originalText);
                    }
                });
            });

            function updateCartCount() {
                $.ajax({
                    url: '../actions/fetch_cart_action.php',
                    method: 'GET',
                    dataType: 'json',
                    success: function(res) {
                        if (res.status === 'success' && $('#cart-count').length) {
                            $('#cart-count').text(res.count || 0);
                        }
                    }
                });
            }
        });
    </script>
</body>
</html>