jQuery(document).ready(function () {

    jQuery.validator.addMethod("validate_email", function(value, element) {
        if (/^([a-zA-Z0-9_\.\-])+\@(([a-zA-Z0-9\-])+\.)+([a-zA-Z0-9]{2,4})+$/.test(value)) {
            return true;
        } else {
            return false;
        }
    }, "Please enter valid email");

    $('#form_account').validate({
        rules: {
            email: {
                required: true,
                email: true,
                validate_email: true,
            },
        },
        messages: {
            email: {
                required: "Please enter email address",
                email: "Please enter valid email",
                validate_email: "Please enter valid email",
            },
        },
        errorElement: 'span',
        errorPlacement: function (error, element) {
          error.addClass('invalid-feedback');
          element.closest('.form-group').append(error);
        },
        highlight: function (element, errorClass, validClass) {
            $(element).addClass('is-invalid');
        },
        unhighlight: function (element, errorClass, validClass) {
            $(element).removeClass('is-invalid');
            $(element).addClass('is-valid');
        },
    });  

    if (document.getElementById("tbl_account")) {
        var productlist = jQuery('#tbl_account').DataTable({ 
            processing: true,
            serverSide: true,
            bAutoWidth: true,
            bLengthChange: true,
            order: [0, 'DESC'],
            ajax: base_url+"/app/accounts/list",
            "columns": [
                {data: 'account_id', name: 'account_id'},
                {data: 'account_name', name: 'account_name'},
                {data: 'email', name: 'email'},
                {data: 'full_name', name: 'full_name'},
                {data: 'created_at', name: 'created_at'},
                {data: 'user_active_yn', name: 'user_active_yn'},
                {data: 'action', name: 'action', orderable: false, searchable: false},
            ]
        });
    }
});