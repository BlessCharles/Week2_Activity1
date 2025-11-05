$(document).ready(function() {
    loadCategories();
    loadBrands();

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

    // Load brands
    function loadBrands() {
        $.ajax({
            url: '../actions/fetch_brand_action.php',
            method: 'GET',
            dataType: 'json',
            success: function(res) {
                if (res.status === 'success') {
                    let rows = '';
                    if (res.data.length === 0) {
                        rows = '<tr><td colspan="3" class="text-center">No brands yet</td></tr>';
                    } else {
                        res.data.forEach(function(brand) {
                            rows += `
                                <tr>
                                    <td>${brand.brand_name}</td>
                                    <td>${brand.cat_name || 'N/A'}</td>
                                    <td>
                                        <button class="btn btn-sm btn-outline-primary edit-btn" data-id="${brand.brand_id}" data-name="${brand.brand_name}">

                                            Edit
                                        </button>
                                        <button class="btn btn-sm btn-outline-danger delete-btn" data-id="${brand.brand_id}">

                                            Delete
                                        </button>
                                    </td>
                                </tr>
                            `;
                        });
                    }
                    $('#brands-table tbody').html(rows);
                }
            },
            error: function() {
                Swal.fire('Error', 'Failed to load brands', 'error');
            }
        });
    }

    // Add brand form submit
    $('#add-brand-form').on('submit', function(e) {
        e.preventDefault();

        let brandName = $('#brand_name').val().trim();
        let catId = $('#cat_id').val();

        if (brandName === '' || catId === '') {
            Swal.fire('Error', 'Please fill all fields', 'error');
            return;
        }

        $.ajax({
            url: '../actions/add_brand_action.php',
            method: 'POST',
            data: {
                brand_name: brandName,
                cat_id: catId
            },
            dataType: 'json',
            success: function(res) {
                if (res.status === 'success') {
                    Swal.fire('Success', res.message, 'success');
                    $('#add-brand-form')[0].reset();
                    loadBrands();
                } else {
                    Swal.fire('Error', res.message, 'error');
                }
            },
            error: function() {
                Swal.fire('Error', 'Failed to add brand', 'error');
            }
        });
    });

    // Edit button click
    $(document).on('click', '.edit-btn', function() {
        let id = $(this).data('id');
        let name = $(this).data('name');

        $('#edit_brand_id').val(id);
        $('#edit_brand_name').val(name);

        let modal = new bootstrap.Modal(document.getElementById('editModal'));
        modal.show();
    });

    // Edit form submit
    $('#edit-brand-form').on('submit', function(e) {
        e.preventDefault();

        let brandId = $('#edit_brand_id').val();
        let brandName = $('#edit_brand_name').val().trim();

        if (brandName === '') {
            Swal.fire('Error', 'Brand name cannot be empty', 'error');
            return;
        }

        $.ajax({
            url: '../actions/update_brand_action.php',
            method: 'POST',
            data: {
                brand_id: brandId,
                brand_name: brandName
            },
            dataType: 'json',
            success: function(res) {
                if (res.status === 'success') {
                    Swal.fire('Success', res.message, 'success');
                    bootstrap.Modal.getInstance(document.getElementById('editModal')).hide();
                    loadBrands();
                } else {
                    Swal.fire('Error', res.message, 'error');
                }
            },
            error: function() {
                Swal.fire('Error', 'Failed to update brand', 'error');
            }
        });
    });

    // Delete button click
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
                    url: '../actions/delete_brand_action.php',
                    method: 'POST',
                    data: { brand_id: id },
                    dataType: 'json',
                    success: function(res) {
                        if (res.status === 'success') {
                            Swal.fire('Deleted!', res.message, 'success');
                            loadBrands();
                        } else {
                            Swal.fire('Error', res.message, 'error');
                        }
                    },
                    error: function() {
                        Swal.fire('Error', 'Failed to delete brand', 'error');
                    }
                });
            }
        });
    });
});