$(function () {

  // cache
  const $tbody = $('#brandTable tbody');
  const $catSel = $('#brand_cat');

  // initial load
  fetchAll();

  // CREATE
  $('#addBrandForm').on('submit', function (e) {
    e.preventDefault();

    const name = $('#brand_name').val().trim();
    const cat  = parseInt($catSel.val(), 10);

    if (!name || !cat) {
      Swal.fire('Error', 'Brand name and category are required.', 'error');
      return;
    }

    $.ajax({
      url: '../actions/add_brand_action.php',
      type: 'POST',
      dataType: 'json',
      data: { brand_name: name, brand_cat: cat },
      success: (res) => {
        if (res.status === 'success') {
          $('#brand_name').val('');
          fetchAll();
          Swal.fire('Success', res.message, 'success');
        } else {
          Swal.fire('Error', res.message, 'error');
        }
      },
      error: (xhr) => {
        Swal.fire('Error', 'Server error occurred!', 'error');
        console.error(xhr.responseText);
      }
    });
  });

  // EDIT -> open modal
  $(document).on('click', '.edit-btn', function () {
    $('#edit_brand_id').val($(this).data('id'));
    $('#edit_brand_name').val($(this).data('name'));
    $('#editBrandModal').modal('show');
  });

  // UPDATE (name only)
  $('#editBrandForm').on('submit', function (e) {
    e.preventDefault();

    const id   = $('#edit_brand_id').val();
    const name = $('#edit_brand_name').val().trim();

    if (!name) {
      Swal.fire('Error', 'Brand name cannot be empty!', 'error');
      return;
    }

    $.ajax({
      url: '../actions/update_brand_action.php',
      type: 'POST',
      dataType: 'json',
      data: { brand_id: id, brand_name: name },
      success: (res) => {
        if (res.status === 'success') {
          $('#editBrandModal').modal('hide');
          fetchAll();
          Swal.fire('Updated', res.message, 'success');
        } else {
          Swal.fire('Error', res.message, 'error');
        }
      },
      error: (xhr) => {
        Swal.fire('Error', 'Server error occurred!', 'error');
        console.error(xhr.responseText);
      }
    });
  });

  // DELETE
  $(document).on('click', '.delete-btn', function () {
    const id = $(this).data('id');

    Swal.fire({
      title: 'Delete this brand?',
      icon: 'warning',
      showCancelButton: true,
      confirmButtonText: 'Delete'
    }).then((r) => {
      if (!r.isConfirmed) return;

      $.ajax({
        url: '../actions/delete_brand_action.php',
        type: 'POST',
        dataType: 'json',
        data: { brand_id: id },
        success: (res) => {
          if (res.status === 'success') {
            fetchAll();
            Swal.fire('Deleted', res.message, 'success');
          } else {
            Swal.fire('Error', res.message, 'error');
          }
        },
        error: (xhr) => {
          Swal.fire('Error', 'Server error occurred!', 'error');
          console.error(xhr.responseText);
        }
      });
    });
  });

  // RETRIEVE both brands + categories
  function fetchAll() {
    $.ajax({
      url: '../actions/fetch_brand_action.php',
      type: 'GET',
      dataType: 'json',
      success: (res) => {
        if (res.status !== 'success') {
          Swal.fire('Error', res.message || 'Failed to load', 'error');
          return;
        }

        const brands = res.data.brands || [];
        const cats   = res.data.categories || [];

        // fill category select
        $catSel.empty().append('<option value="">-- Select category --</option>');
        cats.forEach(c => {
          $catSel.append(`<option value="${c.cat_id}">${c.cat_name}</option>`);
        });

        // table rows grouped by category (simple: sorted + separators)
        $tbody.empty();
        if (!brands.length) {
          $tbody.append('<tr><td colspan="4" class="text-center">No brands yet</td></tr>');
          return;
        }

        let lastCat = '__NONE__';
        brands.forEach(b => {
          const catLabel = b.cat_name || 'Uncategorised';
          if (catLabel !== lastCat) {
            $tbody.append(
              `<tr class="table-light">
                 <td colspan="4"><strong>${catLabel}</strong></td>
               </tr>`
            );
            lastCat = catLabel;
          }

          $tbody.append(
            `<tr>
               <td>${b.brand_id}</td>
               <td>${b.brand_name}</td>
               <td>${catLabel}</td>
               <td>
                 <button class="btn btn-sm btn-warning edit-btn" data-id="${b.brand_id}" data-name="${b.brand_name}">
                   <i class="fa fa-edit"></i>
                 </button>
                 <button class="btn btn-sm btn-danger delete-btn" data-id="${b.brand_id}">
                   <i class="fa fa-trash"></i>
                 </button>
               </td>
             </tr>`
          );
        });
      },
      error: (xhr) => {
        Swal.fire('Error', 'Server error occurred!', 'error');
        console.error(xhr.responseText);
      }
    });
  }
});
