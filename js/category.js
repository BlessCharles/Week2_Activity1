$(document).ready(function() {
    loadCategories();

    $('#add-category-form').submit(function(e) {
        e.preventDefault();
        addCategory();
    });

    $('#edit-category-form').submit(function(e) {
        e.preventDefault();
        updateCategory();
    });
});

function loadCategories() {
    $.ajax({
        url: '../actions/fetch_category_action.php',
        method: 'GET',
        dataType: 'json',
        success: function(res) {
            if (res.status === 'success') {
                renderCategories(res.data);
            } else {
                Swal.fire('Error', res.message || 'Could not fetch categories', 'error');
            }
        },
        error: function() {
            Swal.fire('Error', 'An error occurred while fetching categories', 'error');
        }
    });
}

function renderCategories(items) {
    const tbody = $('#categories-table tbody').empty();
    if (!items || items.length === 0) {
        tbody.append('<tr><td colspan="3" class="text-center">No categories yet</td></tr>');
        return;
    }
    items.forEach(function(it) {
        const tr = $('<tr>');
        tr.append($('<td>').text(it.cat_id));
        tr.append($('<td>').text(it.cat_name));
        const actions = $('<td>');
        const editBtn = $('<button class="btn btn-sm btn-outline-primary me-2">Edit</button>').click(function() {
            openEditModal(it.cat_id, it.cat_name);
        });
        const delBtn = $('<button class="btn btn-sm btn-outline-danger">Delete</button>').click(function() {
            deleteCategory(it.cat_id);
        });
        actions.append(editBtn).append(delBtn);
        tr.append(actions);
        tbody.append(tr);
    });
}

function addCategory() {
    const name = $('#cat_name').val().trim();
    if (name === '') {
        Swal.fire('Oops', 'Please enter a category name', 'error');
        return;
    }
    $.ajax({
        url: '../actions/add_category_action.php',
        method: 'POST',
        data: { cat_name: name },
        dataType: 'json',
        success: function(res) {
            if (res.status === 'success') {
                Swal.fire('Success', res.message, 'success');
                $('#cat_name').val('');
                loadCategories();
            } else {
                Swal.fire('Error', res.message || 'Failed to add category', 'error');
            }
        },
        error: function() {
            Swal.fire('Error', 'An error occurred while adding category', 'error');
        }
    });
}

function openEditModal(id, name) {
    $('#edit_cat_id').val(id);
    $('#edit_cat_name').val(name);
    const modal = new bootstrap.Modal(document.getElementById('editModal'));
    modal.show();
}

function updateCategory() {
    const id = parseInt($('#edit_cat_id').val(), 10);
    const name = $('#edit_cat_name').val().trim();
    if (!id || name === '') {
        Swal.fire('Error','Invalid data','error');
        return;
    }
    $.ajax({
        url: '../actions/update_category_action.php',
        method: 'POST',
        data: { cat_id: id, cat_name: name },
        dataType: 'json',
        success: function(res) {
            if (res.status === 'success') {
                Swal.fire('Success', res.message, 'success');
                const modalEl = document.getElementById('editModal');
                const modal = bootstrap.Modal.getInstance(modalEl);
                modal.hide();
                loadCategories();
            } else {
                Swal.fire('Error', res.message || 'Failed to update category', 'error');
            }
        },
        error: function() {
            Swal.fire('Error', 'An error occurred while updating category', 'error');
        }
    });
}

function deleteCategory(id) {
    Swal.fire({
        title: 'Are you sure?',
        text: "This will permanently delete the category.",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: 'Yes, delete it'
    }).then((result) => {
        if (result.isConfirmed) {
            $.ajax({
                url: '../actions/delete_category_action.php',
                method: 'POST',
                data: { cat_id: id },
                dataType: 'json',
                success: function(res) {
                    if (res.status === 'success') {
                        Swal.fire('Deleted', res.message, 'success');
                        loadCategories();
                    } else {
                        Swal.fire('Error', res.message || 'Failed to delete', 'error');
                    }
                },
                error: function() {
                    Swal.fire('Error', 'An error occurred while deleting category', 'error');
                }
            });
        }
    });
}
