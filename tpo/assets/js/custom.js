$(function () {
  $(document).on('click', '#deleteJob', function (e) {
    e.preventDefault();

    const jobId = $(this).val();

    swal({
      title: 'Are you sure?',
      text: 'Once deleted, you will not be able to recover!',
      icon: 'warning',
      buttons: true,
      dangerMode: true,
    }).then((willDelete) => {
      if (willDelete) {
        $.ajax({
          method: 'POST',
          url: 'actions.php',
          data: {
            jobId: jobId,
            deleteJob: true,
          },
          success: function (response) {
            const res = JSON.parse(response);

            if (res.status == 200) {
              swal('Success', res.message, 'success');
            } else {
              swal('Error', res.message, 'error');
            }

            $('#jobTable').load(location.href + ' #jobTable');
          },
        });
      }
    });
  });

  $(document).on('click', '#deleteCompany', function (e) {
    e.preventDefault();

    const companyId = $(this).val();

    swal({
      title: 'Are you sure?',
      text: 'Once deleted, you will not be able to recover!',
      icon: 'warning',
      buttons: true,
      dangerMode: true,
    }).then((willDelete) => {
      if (willDelete) {
        $.ajax({
          method: 'POST',
          url: 'actions.php',
          data: {
            companyId: companyId,
            deleteCompany: true,
          },
          success: function (response) {
            const res = JSON.parse(response);

            if (res.status == 200) {
              swal('Success', res.message, 'success');
            } else {
              swal('Error', res.message, 'error');
            }

            $('#companyTable').load(location.href + ' #companyTable');
          },
        });
      }
    });
  });
});
