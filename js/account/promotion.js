 $(document).on('click', '.status', function(e) {
    if(confirm("Are you sure you want to change the status?")){
        var token = $("input[name='_token']").val();
        $.ajax({
            url  : base_url+"/app/promotion/update-status",
            type : 'POST',
            data : { 
                '_token': token,
                'id'    : $(this).data('id'),
                'type'  : $(this).data('type'),
            },
            dataType: "json",
            success: function(data) {
                toastr.success(data.message, data.title);
                setTimeout(function(){ window.location.reload(); }, 1000);
            }
        });
    }
});

$(document).ready(function () {
    $('#form').validate({
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
        }
    });

    $('#tbl_promotion').DataTable({ 
        processing: true,
        serverSide: true,
        ajax: base_url+"/app/promotion/list",
        "columns": [
            {data: 'promo_id', name: 'promo_id'},
            {data: 'promo_code', name: 'promo_code'},
            {data: 'promo_discount', name: 'promo_discount'},
            {data: 'machineID', name: 'machineID', orderable: false, searchable: false},
            {data: 'promo_status', name: 'promo_status'},
            {data: 'action', name: 'action', orderable: false, searchable: false},
            {data: 'status', visible: false},
        ],
        initComplete: function () {
            // Apply the search
            this.api().columns(6).every( function () {
                var column = this;
                $('#status').on( 'change', function () {
                    var val = $.fn.dataTable.util.escapeRegex($(this).val());
                    column.search( val ? '^'+val+'$' : '', true, false).draw();
                });
            });
        },
        "order": [0, 'DESC'],
    });

}); 