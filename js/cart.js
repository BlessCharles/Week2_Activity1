$(document).ready(function() {
    console.log('Cart.js loaded and ready');
    loadCart();

    // Load cart items
    function loadCart() {
        console.log('Loading cart...');
        $.ajax({
            url: '../actions/fetch_cart_action.php',
            method: 'GET',
            dataType: 'json',
            success: function(res) {
                console.log('Cart response:', res);
                
                if (res.status === 'success') {
                    console.log('Cart items count:', res.data.length);
                    console.log('Cart items:', res.data);
                    
                    if (res.data.length === 0) {
                        console.log('Cart is empty, showing empty message');
                        showEmptyCart();
                    } else {
                        console.log('Rendering cart with items');
                        renderCart(res.data, res.total);
                    }
                } else {
                    console.error('Cart status not success:', res);
                }
            },
            error: function(xhr, status, error) {
                console.error('AJAX Error:', status, error);
                console.error('Response:', xhr.responseText);
                Swal.fire('Error', 'Failed to load cart', 'error');
            }
        });
    }

    // Show empty cart message
    function showEmptyCart() {
        console.log('Showing empty cart message');
        $('#cart-content').addClass('d-none');
        $('#empty-cart-message').removeClass('d-none');
    }

    // Render cart items
    function renderCart(items, total) {
        console.log('renderCart called with:', items, total);
        $('#cart-content').removeClass('d-none');
        $('#empty-cart-message').addClass('d-none');

        let cartHTML = '<div class="col-md-8">';
        
        items.forEach(function(item) {
            console.log('Processing item:', item);
            const imageSrc = item.product_image ? `../${item.product_image}` : 'https://via.placeholder.com/100';
            const subtotal = parseFloat(item.subtotal);
            
            cartHTML += `
                <div class="cart-item" data-pid="${item.p_id}">
                    <div class="row align-items-center">
                        <div class="col-md-2">
                            <img src="${imageSrc}" alt="${item.product_title}" class="img-fluid">
                        </div>
                        <div class="col-md-4">
                            <h5>${item.product_title}</h5>
                            <p class="text-muted mb-0">
                                <small>${item.cat_name || 'N/A'} | ${item.brand_name || 'N/A'}</small>
                            </p>
                        </div>
                        <div class="col-md-2">
                            <p class="mb-0"><strong>GH₵ ${parseFloat(item.product_price).toFixed(2)}</strong></p>
                        </div>
                        <div class="col-md-2">
                            <div class="input-group">
                                <button class="btn btn-outline-secondary btn-sm qty-btn" data-action="decrease" data-pid="${item.p_id}">
                                    <i class="fas fa-minus"></i>
                                </button>
                                <input type="number" class="form-control qty-input" value="${item.qty}" min="1" data-pid="${item.p_id}">
                                <button class="btn btn-outline-secondary btn-sm qty-btn" data-action="increase" data-pid="${item.p_id}">
                                    <i class="fas fa-plus"></i>
                                </button>
                            </div>
                        </div>
                        <div class="col-md-2 text-end">
                            <p class="mb-2"><strong>GH₵ ${subtotal.toFixed(2)}</strong></p>
                            <button class="btn btn-sm btn-danger remove-btn" data-pid="${item.p_id}">
                                <i class="fas fa-trash"></i> Remove
                            </button>
                        </div>
                    </div>
                </div>
            `;
        });

        cartHTML += '</div>';

        // Cart summary
        cartHTML += `
            <div class="col-md-4">
                <div class="cart-summary">
                    <h4 class="mb-3">Cart Summary</h4>
                    <div class="d-flex justify-content-between mb-2">
                        <span>Subtotal:</span>
                        <strong>GH₵ ${parseFloat(total).toFixed(2)}</strong>
                    </div>
                    <hr>
                    <div class="d-flex justify-content-between mb-3">
                        <span><strong>Total:</strong></span>
                        <strong class="text-success">GH₵ ${parseFloat(total).toFixed(2)}</strong>
                    </div>
                    <a href="checkout.php" class="btn btn-success w-100 mb-2">
                        <i class="fas fa-credit-card"></i> Proceed to Checkout
                    </a>
                    <button class="btn btn-outline-secondary w-100 mb-2" id="continue-shopping">
                        <i class="fas fa-shopping-bag"></i> Continue Shopping
                    </button>
                    <button class="btn btn-outline-danger w-100" id="empty-cart-btn">
                        <i class="fas fa-trash"></i> Empty Cart
                    </button>
                </div>
            </div>
        `;

        console.log('Setting cart HTML');
        $('#cart-content').html(cartHTML);
        console.log('Cart rendered successfully');
    }

    // Update quantity with +/- buttons
    $(document).on('click', '.qty-btn', function() {
        const pid = $(this).data('pid');
        const action = $(this).data('action');
        const input = $(`.qty-input[data-pid="${pid}"]`);
        let qty = parseInt(input.val());

        if (action === 'increase') {
            qty++;
        } else if (action === 'decrease' && qty > 1) {
            qty--;
        }

        input.val(qty);
        updateQuantity(pid, qty);
    });

    // Update quantity on input change
    $(document).on('change', '.qty-input', function() {
        const pid = $(this).data('pid');
        let qty = parseInt($(this).val());
        
        if (qty < 1) {
            qty = 1;
            $(this).val(1);
        }
        
        updateQuantity(pid, qty);
    });

    // Update quantity function
    function updateQuantity(pid, qty) {
        $.ajax({
            url: '../actions/update_quantity_action.php',
            method: 'POST',
            data: { p_id: pid, qty: qty },
            dataType: 'json',
            success: function(res) {
                if (res.status === 'success') {
                    loadCart(); // Reload cart to update totals
                } else {
                    Swal.fire('Error', res.message, 'error');
                }
            },
            error: function() {
                Swal.fire('Error', 'Failed to update quantity', 'error');
            }
        });
    }

    // Remove item from cart
    $(document).on('click', '.remove-btn', function() {
        const pid = $(this).data('pid');
        
        Swal.fire({
            title: 'Remove Item?',
            text: 'Are you sure you want to remove this item from your cart?',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: 'Yes, remove it'
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: '../actions/remove_from_cart_action.php',
                    method: 'POST',
                    data: { p_id: pid },
                    dataType: 'json',
                    success: function(res) {
                        if (res.status === 'success') {
                            Swal.fire('Removed!', res.message, 'success');
                            loadCart();
                        } else {
                            Swal.fire('Error', res.message, 'error');
                        }
                    },
                    error: function() {
                        Swal.fire('Error', 'Failed to remove item', 'error');
                    }
                });
            }
        });
    });

    // Empty cart
    $(document).on('click', '#empty-cart-btn', function() {
        Swal.fire({
            title: 'Empty Cart?',
            text: 'Are you sure you want to empty your entire cart?',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: 'Yes, empty it'
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: '../actions/empty_cart_action.php',
                    method: 'POST',
                    dataType: 'json',
                    success: function(res) {
                        if (res.status === 'success') {
                            Swal.fire('Emptied!', res.message, 'success');
                            loadCart();
                        } else {
                            Swal.fire('Error', res.message, 'error');
                        }
                    },
                    error: function() {
                        Swal.fire('Error', 'Failed to empty cart', 'error');
                    }
                });
            }
        });
    });

    // Continue shopping
    $(document).on('click', '#continue-shopping', function() {
        window.location.href = 'all_products.php';
    });
});
