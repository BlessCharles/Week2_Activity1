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

    // Load products
    function loadProducts() {
        $.ajax({
            url: '../actions/fetch_product_action.php',
            method: 'GET',
            dataType: 'json',
            success: function(res) {
                if (res.status === 'success') {
                    let rows = '';
                    if (res.data.length === 0) {
                        rows = '<tr><td colspan="6" class="text-center">No products yet</td></tr>';
                    } else {
                        res.data.forEach(function(product) {
                            let imageHtml = product.product_image
                                ? `<img src="../${product.product_image}" class="product-image" alt="${product.product_title}">`
                                : '<span class="text-muted">No image</span>';

                            rows += `
                                <tr>
                                    <td>${imageHtml}</td>
                                    <td>${product.product_title}</td>
                                    <td>${product.cat_name || 'N/A'}</td>
                                    <td>${product.brand_name || 'N/A'}</td>
                                    <td>$${parseFloat(product.product_price).toFixed(2)}</td>
                                    <td>
                                        <button class="btn btn-sm btn-outline-primary edit-btn"
                                            data-id="${product.product_id}"
                                            data-cat="${product.product_cat}"
                                            data-brand="${product.product_brand}"
                                            data-title="${product.product_title}"
                                            data-price="${product.product_price}"
                                            data-desc="${product.product_desc || ''}"
                                            data-image="${product.product_image || ''}"
                                            data-keywords="${product.product_keywords || ''}">
                                            Edit
                                        </button>
                                        <button class="btn btn-sm btn-outline-danger delete-btn" data-id="${product.product_id}">
                                            Delete
                                        </button>
                                    </td>
                                </tr>
                            `;
                        });
                    }
                    $('#products-table tbody').html(rows);
                }
            },
            error: function() {
                Swal.fire('Error', 'Failed to load products', 'error');
            }
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
            $('#image-preview').html(`<img src="../${$(this).data('image')}" class="product-image" alt="Current image">`);
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
                $('#image-preview').html(`<img src="${e.target.result}" class="product-image" alt="Preview">`);
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