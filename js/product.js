$(document).ready(function() {
    loadCategories();
    loadBrands();
    loadProducts();

    let isEditMode = false;

    // Load categories for dropdown
    function loadCategories() {
        $.ajax({
            url: '../actions/fetch_category_action.php',
            method: 'GET',
            dataType: 'json',
            success: function(res) {
                if (res.status === 'success') {
                    let opts = '<option value="">Select Category</option>';
                    res.data.forEach(function(cat) {
                        opts += `<option value="${cat.cat_id}">${cat.cat_name}</option>`;
                    });
                    $('#cat_id').html(opts);
                }
            },
            error: function() {
                Swal.fire('Error', 'Failed to load categories', 'error');
            }
        });
    }

    // Load brands for dropdown
    function loadBrands() {
        $.ajax({
            url: '../actions/fetch_brand_action.php',
            method: 'GET',
            dataType: 'json',
            success: function(res) {
                if (res.status === 'success') {
                    let opts = '<option value="">Select Brand</option>';
                    res.data.forEach(function(brand) {
                        opts += `<option value="${brand.brand_id}">${brand.brand_name}</option>`;
                    });
                    $('#brand_id').html(opts);
                }
            },
            error: function() {
                Swal.fire('Error', 'Failed to load brands', 'error');
            }
        });
    }

    // Load products and render as cards
    function loadProducts() {
        $.ajax({
            url: '../actions/fetch_product_action.php',
            method: 'GET',
            dataType: 'json',
            success: function(res) {
                if (res.status === 'success') {
                    renderProducts(res.data);
                }
            },
            error: function() {
                Swal.fire('Error', 'Failed to load products', 'error');
            }
        });
    }

    // Render products as cards
    function renderProducts(products) {
        const grid = $('#products-grid');
        grid.empty();

        if (products.length === 0) {
            grid.html(`
                <div style="grid-column: 1/-1; text-align: center; padding: 60px 20px;">
                    <i class="fas fa-box-open" style="font-size: 64px; color: #bdc3c7; margin-bottom: 20px;"></i>
                    <p class="text-muted" style="font-size: 18px;">No products found. Click "Add New Product" to get started!</p>
                </div>
            `);
            return;
        }

        products.forEach(function(product) {
            const imageSrc = product.product_image
                ? `../${product.product_image}`
                : 'https://via.placeholder.com/300x200?text=No+Image';

            const card = `
                <div class="product-card">
                    <img src="${imageSrc}" alt="${product.product_title}" onerror="this.src='https://via.placeholder.com/300x200?text=No+Image'">
                    <div class="product-card-body">
                        <div class="product-title">${product.product_title}</div>
                        <div class="product-meta">
                            <i class="fas fa-list"></i> ${product.cat_name || 'N/A'}
                        </div>
                        <div class="product-meta">
                            <i class="fas fa-tag"></i> ${product.brand_name || 'N/A'}
                        </div>
                        <div class="product-price">GH₵ ${parseFloat(product.product_price).toFixed(2)}</div>
                        ${product.product_desc ? `<p class="text-muted small mb-2" style="margin-top: 8px;">${product.product_desc.substring(0, 80)}${product.product_desc.length > 80 ? '...' : ''}</p>` : ''}
                        <div class="product-actions">
                            <button class="btn btn-sm btn-primary edit-btn"
                                data-id="${product.product_id}"
                                data-cat="${product.product_cat}"
                                data-brand="${product.product_brand}"
                                data-title="${product.product_title}"
                                data-price="${product.product_price}"
                                data-desc="${product.product_desc || ''}"
                                data-image="${product.product_image || ''}"
                                data-keywords="${product.product_keywords || ''}">
                                <i class="fas fa-edit"></i> Edit
                            </button>
                            <button class="btn btn-sm btn-danger delete-btn" data-id="${product.product_id}">
                                <i class="fas fa-trash"></i> Delete
                            </button>
                        </div>
                    </div>
                </div>
            `;
            grid.append(card);
        });
    }

    // Open Add Product Modal
    $('#add-product-btn').on('click', function() {
        isEditMode = false;
        $('#modalTitle').text('Add Product');
        $('#product-form')[0].reset();
        $('#product_id').val('');
        $('#existing_image').val('');
        $('#image-preview').html('');
        $('#submit-btn').text('Save Product');

        let modal = new bootstrap.Modal(document.getElementById('productModal'));
        modal.show();
    });

    // Open Edit Product Modal
    $(document).on('click', '.edit-btn', function() {
        isEditMode = true;
        $('#modalTitle').text('Edit Product');

        $('#product_id').val($(this).data('id'));
        $('#cat_id').val($(this).data('cat'));
        $('#brand_id').val($(this).data('brand'));
        $('#product_title').val($(this).data('title'));
        $('#product_price').val($(this).data('price'));
        $('#product_desc').val($(this).data('desc'));
        $('#product_keywords').val($(this).data('keywords'));
        $('#existing_image').val($(this).data('image'));

        // Show existing image preview
        if ($(this).data('image')) {
            $('#image-preview').html(`<img src="../${$(this).data('image')}" style="width: 80px; height: 80px; object-fit: cover; border-radius: 5px;" alt="Current image">`);
        } else {
            $('#image-preview').html('');
        }

        $('#submit-btn').text('Update Product');

        let modal = new bootstrap.Modal(document.getElementById('productModal'));
        modal.show();
    });

    // Image preview on file select
    $('#product_image').on('change', function(e) {
        if (e.target.files && e.target.files[0]) {
            let reader = new FileReader();
            reader.onload = function(e) {
                $('#image-preview').html(`<img src="${e.target.result}" style="width: 80px; height: 80px; object-fit: cover; border-radius: 5px;" alt="Preview">`);
            }
            reader.readAsDataURL(e.target.files[0]);
        }
    });

    // Submit product form (Add or Edit)
    $('#product-form').on('submit', function(e) {
        e.preventDefault();

        let catId = $('#cat_id').val();
        let brandId = $('#brand_id').val();
        let title = $('#product_title').val().trim();
        let price = $('#product_price').val();

        // Validation
        if (catId === '' || brandId === '' || title === '' || price === '' || price <= 0) {
            Swal.fire('Error', 'Please fill all required fields correctly', 'error');
            return;
        }

        // Check if image needs to be uploaded
        let imageFile = $('#product_image')[0].files[0];

        if (imageFile) {
            // Upload image first
            uploadImage(imageFile, function(imagePath) {
                if (imagePath) {
                    submitProductData(imagePath);
                } else {
                    Swal.fire('Error', 'Failed to upload image', 'error');
                }
            });
        } else {
            // No new image, use existing or empty
            let imagePath = $('#existing_image').val();
            submitProductData(imagePath);
        }
    });

    // Upload image function
    function uploadImage(file, callback) {
        let formData = new FormData();
        formData.append('product_image', file);
        formData.append('product_id', $('#product_id').val() || 0);

        $.ajax({
            url: '../actions/upload_product_image_action.php',
            method: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            dataType: 'json',
            success: function(res) {
                if (res.status === 'success') {
                    callback(res.path);
                } else {
                    callback(null);
                }
            },
            error: function() {
                callback(null);
            }
        });
    }

    // Submit product data
    function submitProductData(imagePath) {
        let url = isEditMode ? '../actions/update_product_action.php' : '../actions/add_product_action.php';

        let data = {
            product_id: $('#product_id').val(),
            cat_id: $('#cat_id').val(),
            brand_id: $('#brand_id').val(),
            product_title: $('#product_title').val().trim(),
            product_price: $('#product_price').val(),
            product_desc: $('#product_desc').val().trim(),
            product_keywords: $('#product_keywords').val().trim(),
            image_path: imagePath
        };

        $.ajax({
            url: url,
            method: 'POST',
            data: data,
            dataType: 'json',
            success: function(res) {
                if (res.status === 'success') {
                    Swal.fire('Success', res.message, 'success');
                    bootstrap.Modal.getInstance(document.getElementById('productModal')).hide();
                    $('#product-form')[0].reset();
                    loadProducts();
                } else {
                    Swal.fire('Error', res.message, 'error');
                }
            },
            error: function() {
                Swal.fire('Error', 'Failed to save product', 'error');
            }
        });
    }

    // Delete product
    $(document).on('click', '.delete-btn', function() {
        let id = $(this).data('id');

        Swal.fire({
            title: 'Are you sure?',
            text: "You won't be able to revert this!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: 'Yes, delete it!'
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: '../actions/delete_product_action.php',
                    method: 'POST',
                    data: { product_id: id },
                    dataType: 'json',
                    success: function(res) {
                        if (res.status === 'success') {
                            Swal.fire('Deleted!', res.message, 'success');
                            loadProducts();
                        } else {
                            Swal.fire('Error', res.message, 'error');
                        }
                    },
                    error: function() {
                        Swal.fire('Error', 'Failed to delete product', 'error');
                    }
                });
            }
        });
    });
});