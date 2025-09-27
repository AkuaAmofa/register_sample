$(document).ready(function () {
    // Fetch categories when page loads
    fetchCategories();

    // Add category
    $('#addCategoryForm').submit(function (e) {
        e.preventDefault();

        let catName = $('#cat_name').val().trim();
        if (catName === '') {
            Swal.fire('Error', 'Category name is required', 'error');
            return;
        }

        $.ajax({
            url: '../actions/add_category_action.php',
            type: 'POST',
            dataType: 'json',
            data: { cat_name: catName },
            success: function (response) {
                if (response.status === 'success') {
                    Swal.fire('Success', response.message, 'success');
                    $('#addCategoryForm')[0].reset();
                    fetchCategories();
                } else {
                    Swal.fire('Error', response.message, 'error');
                }
            },
            error: function () {
                Swal.fire('Error', 'Server error while adding category', 'error');
            }
        });
    });

    // Fetch categories
    function fetchCategories() {
        $.ajax({
            url: '../actions/fetch_category_action.php',
            type: 'GET',
            dataType: 'json',
            success: function (response) {
                let tbody = $('#categoryTable tbody');
                tbody.empty();

                if (response.status === 'success' && response.data.length > 0) {
                    $.each(response.data, function (index, category) {
                        tbody.append(`
                            <tr>
                                <td>${category.cat_id}</td>
                                <td>
                                    <span class="cat-name">${category.cat_name}</span>
                                    <input type="text" class="form-control d-none edit-input" value="${category.cat_name}">
                                </td>
                                <td>
                                    <button class="btn btn-sm btn-warning edit-btn" data-id="${category.cat_id}">Edit</button>
                                    <button class="btn btn-sm btn-success save-btn d-none" data-id="${category.cat_id}">Save</button>
                                    <button class="btn btn-sm btn-danger delete-btn" data-id="${category.cat_id}">Delete</button>
                                </td>
                            </tr>
                        `);
                    });
                } else {
                    tbody.append(`<tr><td colspan="3" class="text-center">No categories found</td></tr>`);
                }
            },
            error: function () {
                Swal.fire('Error', 'Server error while fetching categories', 'error');
            }
        });
    }

    // Enable edit mode
    $(document).on('click', '.edit-btn', function () {
        let row = $(this).closest('tr');
        row.find('.cat-name').addClass('d-none');
        row.find('.edit-input').removeClass('d-none');
        row.find('.edit-btn').addClass('d-none');
        row.find('.save-btn').removeClass('d-none');
    });

    // Save updated category
    $(document).on('click', '.save-btn', function () {
        let row = $(this).closest('tr');
        let catId = $(this).data('id');
        let newName = row.find('.edit-input').val().trim();

        if (newName === '') {
            Swal.fire('Error', 'Category name cannot be empty', 'error');
            return;
        }

        $.ajax({
            url: '../actions/update_category_action.php',
            type: 'POST',
            dataType: 'json',
            data: { cat_id: catId, cat_name: newName },
            success: function (response) {
                if (response.status === 'success') {
                    Swal.fire('Success', response.message, 'success');
                    fetchCategories();
                } else {
                    Swal.fire('Error', response.message, 'error');
                }
            },
            error: function () {
                Swal.fire('Error', 'Server error while updating category', 'error');
            }
        });
    });

    // Delete category
    $(document).on('click', '.delete-btn', function () {
        let catId = $(this).data('id');

        Swal.fire({
            title: 'Are you sure?',
            text: 'This category will be deleted permanently!',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Yes, delete it',
            cancelButtonText: 'Cancel'
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: '../actions/delete_category_action.php',
                    type: 'POST',
                    dataType: 'json',
                    data: { cat_id: catId },
                    success: function (response) {
                        if (response.status === 'success') {
                            Swal.fire('Deleted', response.message, 'success');
                            fetchCategories();
                        } else {
                            Swal.fire('Error', response.message, 'error');
                        }
                    },
                    error: function () {
                        Swal.fire('Error', 'Server error while deleting category', 'error');
                    }
                });
            }
        });
    });
});
