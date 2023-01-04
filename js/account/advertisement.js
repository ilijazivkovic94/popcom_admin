jQuery(document).ready(function () {

    $('#form_account').validate({
        errorElement: 'span',
        errorPlacement: function (error, element) {
            if ( (element.attr("name") == "file" || element.attr("name") == "filename") && jQuery('#ad_type').val() != '') {
                $("#image-error").append('Please select file.').addClass('error');
            }
          
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
        ignore: "",
        rules: {
            ad_title: {
                required: true,
                remote:{
                    url : base_url+"/app/advertisement/checkname",
                    type:'POST',
                    data:{
                        _token : jQuery("input[name='_token']").val(),
                        ad_title:function(){ 
                            return $("#ad_title").val(); 
                        }
                    }
                },
            },
            filename: {
                required: true,
            }
        },
        messages: {
            ad_title: {
                required: "Please enter advertisement name.",
                remote: "Advertisement name already exist.",
            },
        },
    });

    $('#form_account2').validate({
        errorElement: 'span',
        errorPlacement: function (error, element) {
            if ( (element.attr("name") == "file" || element.attr("name") == "filename") && jQuery('#ad_type').val() != '') {
                $("#image-error").append('Please select file.').addClass('error');
            }

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
        ignore: "",
        rules: {
            filename: {
                required: true,
            }
        },
    });

    $(document).on('change', '#ad_type',function(){
        var ad_type = $(this).val();
        if(ad_type == 'image') {
            $(".imageFile").show();
            $(".videoFile").hide();
        }else{
            $(".videoFile").show();
            $(".imageFile").hide();
        } 
    });

    //Delete
    jQuery(document).on('click', '.delImage', function (e ) {
        var adsID   = jQuery.trim( jQuery(this).attr('data-id') );        
        var token   = jQuery("input[name='_token']").val();
        $.ajax({
            url : base_url+"/app/advertisement/deleteImage",
            type: 'POST',
            data: { 
                '_token'    : token,
                'adsID'     : adsID,
            },
            dataType: "JSON",
            success: function(response) {
                if(response.status == true){
                    jQuery('#new_file').val('');                        
                    jQuery('#new_filename').val('');
                    jQuery('.imageDiv').remove();

                }else{
                    toastr.error(response.message);
                }
            }
        });
    });

    if (document.getElementById("tbl_account")) {
        var Advertlist = jQuery('#tbl_account').DataTable({ 
            processing: true,
            serverSide: true,
            bAutoWidth: true,
            bLengthChange: true,
            // ajax: base_url+"/app/advertisement/list",
            ajax: {
                url: base_url+"/app/advertisement/list",
                data: function ( data ) {
                    data.ad_type        = jQuery('#ad_type').val();
                    data.ad_status      = jQuery('#ad_status').val();
                    data.ad_gender      = jQuery('#ad_gender').val();
                    data.ad_age_group   = jQuery('#ad_age_group').val();
                    data.sub_account_id = jQuery('#sub_account_id').val();
                }      
            },
            "columns": [
                {data: 'ad_data', name: 'ad_data', orderable: false, searchable: false},
                {data: 'ad_title', name: 'ad_title'},
                {data: 'ad_type', name: 'ad_type'},
                {data: 'ad_gender', name: 'ad_gender'},
                {data: 'ad_age_group', name: 'ad_age_group'},
                {data: 'ad_status', name: 'ad_status'},
                {data: 'action', name: 'action', orderable: false, searchable: false},
            ]
        });

        jQuery('.advert-filter').on('change', function(){
            Advertlist.draw();
        });

        //Delete
        jQuery(document).on('click', '.delProduct', function (e ) {
            var productID   = jQuery.trim( jQuery(this).attr('data-id') );
            bootbox.confirm({
                message: jQuery('#advertDeleteWarMsg').val(),
                buttons: {
                    confirm: {
                        label: 'Yes',
                        className: 'btn-primary'
                    },
                    cancel: {
                        label: 'No',
                        className: 'btn-danger'
                    }
                },
                callback: function (result) {
                    if(result == true){
                        var token = jQuery("input[name='_token']").val();
                        $.ajax({
                            url : base_url+"/app/advertisement/delete",
                            type: 'POST',
                            data: { 
                                '_token'    : token,
                                'adsID'     : productID,
                            },
                            dataType: "JSON",
                            success: function(response) {
                                if(response.status == true){
                                    toastr.success(response.message, 'Advertisement');
                                    var oTable = jQuery('#tbl_account').dataTable();
                                    oTable.fnDraw(false);  
                                }else{
                                    toastr.error(response.message);
                                }
                            }
                        });
                    }
                }                    
            });                  
        });
    }
});