<?php
session_start();
require_once '../controllers/product_controller.php';
require_once '../controllers/category_controller.php';
require_once '../controllers/brand_controller.php';

// Get filter parameters
$cat_filter = isset($_GET['category']) ? intval($_GET['category']) : 0;
$brand_filter = isset($_GET['brand']) ? intval($_GET['brand']) : 0;

// Fetch products based on filters
if ($cat_filter > 0) {
    $products = filter_products_by_category_ctr($cat_filter);
} elseif ($brand_filter > 0) {
    $products = filter_products_by_brand_ctr($brand_filter);
} else {
    $products = view_all_products_ctr();
}

// Fetch categories and brands for filters
$categories = fetch_categories_ctr();
$brands = fetch_brands_ctr();

// Pagination
$per_page = 10;
$total_products = count($products);
$total_pages = ceil($total_products / $per_page);
$current_page = isset($_GET['page']) ? max(1, intval($_GET['page'])) : 1;
$offset = ($current_page - 1) * $per_page;
$products_page = array_slice($products, $offset, $per_page);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>All Products - Taste of Africa</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #faf3e0;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        .product-card {
            transition: transform 0.3s, box-shadow 0.3s;
            height: 100%;
            border: none;
            border-radius: 10px;
            overflow: hidden;
        }
        .product-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 20px rgba(0,0,0,0.15);
        }
        .product-image {
            width: 100%;
            height: 250px;
            object-fit: cover;
        }
        .price-tag {
            font-size: 1.5rem;
            font-weight: bold;
            color: #dfca92ff;
        }
        .filter-section {
            background: white;
            padding: 20px;
            border-radius: 10px;
            margin-bottom: 30px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.05);
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
                <a href="../index.php" class="btn btn-sm btn-outline-secondary">Home</a>
            </div>
        </div>
    </nav>

    <div class="container mt-5">
        <h1 class="mb-4">All Products</h1>

        <!-- Filters -->
        <div class="filter-section">
            <h5><i class="fas fa-filter"></i> Filter Products</h5>
            <div class="row">
                <div class="col-md-4">
                    <label class="form-label">Category</label>
                    <select class="form-select" id="category-filter">
                        <option value="0">All Categories</option>
                        <?php foreach ($categories as $cat): ?>
                            <option value="<?php echo $cat['cat_id']; ?>" <?php echo $cat_filter == $cat['cat_id'] ? 'selected' : ''; ?>>
                                <?php echo htmlspecialchars($cat['cat_name']); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="col-md-4">
                    <label class="form-label">Brand</label>
                    <select class="form-select" id="brand-filter">
                        <option value="0">All Brands</option>
                        <?php foreach ($brands as $brand): ?>
                            <option value="<?php echo $brand['brand_id']; ?>" <?php echo $brand_filter == $brand['brand_id'] ? 'selected' : ''; ?>>
                                <?php echo htmlspecialchars($brand['brand_name']); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="col-md-4 d-flex align-items-end">
                    <button class="btn btn-custom w-100" onclick="applyFilters()">Apply Filters</button>
                </div>
            </div>
        </div>

        <!-- Products Grid -->
        <div class="row g-4">
            <?php if (empty($products_page)): ?>
                <div class="col-12 text-center">
                    <p class="text-muted">No products found.</p>
                </div>
            <?php else: ?>
                <?php foreach ($products_page as $product): ?>
                    <div class="col-md-4 col-lg-3">
                        <div class="card product-card">
                            <?php if ($product['product_image']): ?>
                                <img src="../<?php echo htmlspecialchars($product['product_image']); ?>"
                                     class="product-image"
                                     alt="<?php echo htmlspecialchars($product['product_title']); ?>">
                            <?php else: ?>
                                <div class="product-image bg-secondary d-flex align-items-center justify-content-center">
                                    <i class="fas fa-image fa-3x text-white"></i>
                                </div>
                            <?php endif; ?>

                            <div class="card-body">
                                <span class="badge bg-secondary mb-2"><?php echo htmlspecialchars($product['cat_name']); ?></span>
                                <span class="badge bg-info mb-2"><?php echo htmlspecialchars($product['brand_name']); ?></span>

                                <h5 class="card-title"><?php echo htmlspecialchars($product['product_title']); ?></h5>
                                <p class="price-tag">GH₵ <?php echo number_format($product['product_price'], 2); ?></p>

                                <div class="d-grid gap-2">
                                    <a href="single_product.php?id=<?php echo $product['product_id']; ?>"
                                       class="btn btn-outline-primary btn-sm">
                                        <i class="fas fa-eye"></i> View Details
                                    </a>
                                    <button class="btn btn-custom btn-sm add-to-cart-btn" 
                                            data-product-id="<?php echo $product['product_id']; ?>" 
                                            data-qty="1">
                                        <i class="fas fa-shopping-cart"></i> Add to Cart
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>

        <!-- Pagination -->
        <?php if ($total_pages > 1): ?>
            <nav class="mt-5">
                <ul class="pagination justify-content-center">
                    <?php for ($i = 1; $i <= $total_pages; $i++): ?>
                        <li class="page-item <?php echo $i == $current_page ? 'active' : ''; ?>">
                            <a class="page-link" href="?page=<?php echo $i; ?><?php echo $cat_filter > 0 ? '&category='.$cat_filter : ''; ?><?php echo $brand_filter > 0 ? '&brand='.$brand_filter : ''; ?>">
                                <?php echo $i; ?>
                            </a>
                        </li>
                    <?php endfor; ?>
                </ul>
            </nav>
        <?php endif; ?>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        function applyFilters() {
            const category = document.getElementById('category-filter').value;
            const brand = document.getElementById('brand-filter').value;

            let url = 'all_products.php?';
            if (category > 0) url += 'category=' + category + '&';
            if (brand > 0) url += 'brand=' + brand;

            window.location.href = url;
        }

        // Add to Cart functionality
        $(document).ready(function() {
            updateCartCount();

            $(document).on('click', '.add-to-cart-btn', function() {
                const btn = $(this);
                const productId = btn.data('product-id');
                const qty = btn.data('qty') || 1;
                
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
                                timer: 2000,
                                showConfirmButton: false
                            });
                            btn.prop('disabled', false).html(originalText);
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