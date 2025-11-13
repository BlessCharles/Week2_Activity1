$(document).ready(function() {
    let cartTotal = 0;
    
    loadCheckoutItems();

    // Load cart items for checkout
    function loadCheckoutItems() {
        $.ajax({
            url: '../actions/fetch_cart_action.php',
            method: 'GET',
            dataType: 'json',
            success: function(res) {
                if (res.status === 'success') {
                    if (res.data.length === 0) {
                        // Redirect to cart if empty
                        Swal.fire({
                            icon: 'info',
                            title: 'Cart is Empty',
                            text: 'Please add items to your cart first',
                            confirmButtonText: 'Go to Products'
                        }).then(() => {
                            window.location.href = 'all_products.php';
                        });
                    } else {
                        cartTotal = res.total;
                        renderCheckoutItems(res.data, res.total);
                    }
                }
            },
            error: function() {
                Swal.fire('Error', 'Failed to load cart items', 'error');
            }
        });
    }

    // Render checkout items
    function renderCheckoutItems(items, total) {
        let itemsHTML = '';
        
        items.forEach(function(item) {
            const imageSrc = item.product_image ? `../${item.product_image}` : 'https://via.placeholder.com/60';
            const subtotal = parseFloat(item.subtotal);
            
            itemsHTML += `
                <div class="checkout-item">
                    <div class="d-flex align-items-center flex-grow-1">
                        <img src="${imageSrc}" alt="${item.product_title}">
                        <div>
                            <h6 class="mb-1">${item.product_title}</h6>
                            <small class="text-muted">Qty: ${item.qty} × GH₵ ${parseFloat(item.product_price).toFixed(2)}</small>
                        </div>
                    </div>
                    <div>
                        <strong>GH₵ ${subtotal.toFixed(2)}</strong>
                    </div>
                </div>
            `;
        });

        $('#checkout-items').html(itemsHTML);
        $('#subtotal-amount').text('GH₵ ' + parseFloat(total).toFixed(2));
        $('#total-amount').text('GH₵ ' + parseFloat(total).toFixed(2));
        $('#modal-total-amount').text('GH₵ ' + parseFloat(total).toFixed(2));
    }

    // Open payment modal
    $('#simulate-payment-btn').on('click', function() {
        const modal = new bootstrap.Modal(document.getElementById('paymentModal'));
        modal.show();
    });

    // Confirm payment and process checkout
    $('#confirm-payment-btn').on('click', function() {
        const btn = $(this);
        const originalText = btn.html();
        
        // Disable button and show loading
        btn.prop('disabled', true);
        btn.html('<i class="fas fa-spinner fa-spin"></i> Processing...');

        $.ajax({
            url: '../actions/process_checkout_action.php',
            method: 'POST',
            dataType: 'json',
            success: function(res) {
                // Close modal
                bootstrap.Modal.getInstance(document.getElementById('paymentModal')).hide();
                
                if (res.status === 'success') {
                    // Show success message with order details
                    Swal.fire({
                        icon: 'success',
                        title: 'Order Placed Successfully!',
                        html: `
                            <div class="text-start">
                                <p><strong>Order Reference:</strong> ${res.invoice_no}</p>
                                <p><strong>Total Amount:</strong> ${res.currency} ${res.total_amount}</p>
                                <p class="text-muted">Thank you for your purchase!</p>
                            </div>
                        `,
                        confirmButtonText: 'View Orders',
                        showCancelButton: true,
                        cancelButtonText: 'Continue Shopping'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            // Redirect to orders page (you'll need to create this)
                            window.location.href = 'orders.php';
                        } else {
                            // Redirect to products
                            window.location.href = 'all_products.php';
                        }
                    });
                } else {
                    Swal.fire('Error', res.message, 'error');
                    btn.prop('disabled', false);
                    btn.html(originalText);
                }
            },
            error: function() {
                Swal.fire('Error', 'Failed to process checkout. Please try again.', 'error');
                btn.prop('disabled', false);
                btn.html(originalText);
                bootstrap.Modal.getInstance(document.getElementById('paymentModal')).hide();
            }
        });
    });
});