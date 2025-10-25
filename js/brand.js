$(document).ready(function () {

    // Fetch brands when page loads
    fetchBrands();

    // =========================
    // ADD BRAND
    // =========================
    $('#addBrandForm').submit(function (e) {
        e.preventDefault();

        let brandName = $('#brand_name').val().trim();

        if (brandName === '') {
            Swal.fire({
                icon: 'error',
                title: 'Oops...',
                text: 'Brand name cannot be empty!',
            });
            return;
        }

        $.ajax({
            url: '../actions/add_brand_action.php',
            type: 'POST',
            dataType: 'json',
            data: { brand_name: brandName },
            success: function (response) {
                if (response.status === 'success') {
                    Swal.fire('Success', response.message, 'success');
                    $('#brand_name').val('');
                    fetchBrands(); // refresh table
                } else {
                    Swal.fire('Error', response.message, 'error');
                }
            },
            error: function (xhr) {
                Swal.fire('Error', 'Server error occurred!', 'error');
                console.error(xhr.responseText);
            }
        });
    });

    // =========================
    // FETCH BRANDS
    // =========================
    function fetchBrands() {
        $.ajax({
            url: '../actions/fetch_brand_action.php',
            type: 'GET',
            dataType: 'json',
            success: function (response) {
                let tbody = $('#brandTable tbody');
                tbody.empty();

                if (response.status === 'success' && response.data.length > 0) {
                    $.each(response.data, function (index, brand) {
                        tbody.append(`
                            <tr>
                                <td>${brand.brand_id}</td>
                                <td>${brand.brand_name}</td>
                                <td>
                                    <button class="btn btn-sm btn-warning edit-btn" data-id="${brand.brand_id}" data-name="${brand.brand_name}"><i class="fa fa-edit"></i></button>
                                    <button class="btn btn-sm btn-danger delete-btn" data-id="${brand.brand_id}"><i class="fa fa-trash"></i></button>
                                </td>
                            </tr>
                        `);
                    });
                } else {
                    tbody.append('<tr><td colspan="3" class="text-center">No brands found</td></tr>');
                }
            },
            error: function (xhr) {
                console.error('Fetch error:', xhr.responseText);
            }
        });
    }

    // =========================
    // EDIT BRAND (SHOW MODAL)
    // =========================
    $(document).on('click', '.edit-btn', function () {
        let brandId = $(this).data('id');
        let brandName = $(this).data('name');

        $('#edit_brand_id').val(brandId);
        $('#edit_brand_name').val(brandName);

        $('#editBrandModal').modal('show');
    });

    // =========================
    // UPDATE BRAND
    // =========================
    $('#editBrandForm').submit(function (e) {
        e.preventDefault();

        let brandId = $('#edit_brand_id').val();
        let newBrandName = $('#edit_brand_name').val().trim();

        if (newBrandName === '') {
            Swal.fire('Error', 'Brand name cannot be empty!', 'error');
            return;
        }

        $.ajax({
            url: '../actions/update_brand_action.php',
            type: 'POST',
            dataType: 'json',
            data: {
                brand_id: brandId,
                brand_name: newBrandName
            },
            success: function (response) {
                if (response.status === 'success') {
                    Swal.fire('Updated', response.message, 'success');
                    $('#editBrandModal').modal('hide');
                    fetchBrands(); // refresh table
                } else {
                    Swal.fire('Error', response.message, 'error');
                }
            },
            error: function (xhr) {
                Swal.fire('Error', 'Server error occurred!', 'error');
                console.error(xhr.responseText);
            }
        });
    });

    // =========================
    // DELETE BRAND
    // =========================
    $(document).on('click', '.delete-btn', function () {
        let brandId = $(this).data('id');

        Swal.fire({
            title: 'Are you sure?',
            text: "This will permanently delete the brand.",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes, delete it!'
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: '../actions/delete_brand_action.php',
                    type: 'POST',
                    dataType: 'json',
                    data: { brand_id: brandId },
                    success: function (response) {
                        if (response.status === 'success') {
                            Swal.fire('Deleted!', response.message, 'success');
                            fetchBrands();
                        } else {
                            Swal.fire('Error', response.message, 'error');
                        }
                    },
                    error: function (xhr) {
                        Swal.fire('Error', 'Server error occurred!', 'error');
                        console.error(xhr.responseText);
                    }
                });
            }
        });
    });
});
