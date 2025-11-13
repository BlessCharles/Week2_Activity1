$(document).ready(function() {
    loadOrders();

    function loadOrders() {
        $.ajax({
            url: '../actions/get_orders_action.php',
            method: 'GET',
            dataType: 'json',
            success: function(res) {
                if (res.status === 'success') {
                    if (res.data.length === 0) {
                        showNoOrders();
                    } else {
                        renderOrders(res.data);
                    }
                } else {
                    Swal.fire('Error', res.message, 'error');
                }
            },
            error: function() {
                Swal.fire('Error', 'Failed to load orders', 'error');
            }
        });
    }

    function showNoOrders() {
        $('#orders-container').html(`
            <div class="text-center py-5">
                <i class="fas fa-box-open fa-4x text-muted mb-3"></i>
                <h4>No orders yet</h4>
                <p class="text-muted">Start shopping to see your orders here!</p>
                <a href="all_products.php" class="btn btn-primary mt-3">
                    <i class="fas fa-shopping-bag"></i> Browse Products
                </a>
            </div>
        `);
    }

    function renderOrders(orders) {
        let html = '';
        
        orders.forEach(function(order) {
            const statusClass = 'badge-' + order.order_status.toLowerCase();
            const orderDate = new Date(order.order_date).toLocaleDateString();
            
            html += `
                <div class="order-card">
                    <div class="order-header">
                        <div class="row">
                            <div class="col-md-8">
                                <h5>Order #${order.invoice_no}</h5>
                                <p class="text-muted mb-0">
                                    <small><i class="fas fa-calendar"></i> ${orderDate}</small>
                                </p>
                            </div>
                            <div class="col-md-4 text-end">
                                <span class="badge ${statusClass}">${order.order_status}</span>
                                <h5 class="mt-2">${order.currency} ${parseFloat(order.amt).toFixed(2)}</h5>
                            </div>
                        </div>
                    </div>
                    <div class="order-body">
                        <p><i class="fas fa-box"></i> ${order.total_items} item(s)</p>
                        <button class="btn btn-sm btn-outline-primary view-details-btn" 
                                data-order-id="${order.order_id}">
                            <i class="fas fa-eye"></i> View Details
                        </button>
                    </div>
                </div>
            `;
        });
        
        $('#orders-container').html(html);
    }

    // View order details (you can implement this later)
    $(document).on('click', '.view-details-btn', function() {
        const orderId = $(this).data('order-id');
        Swal.fire('Coming Soon','Watch out for more information');
    });
});