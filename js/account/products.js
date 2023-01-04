jQuery('.summernote').summernote({
    height: 200,
    dialogsInBody: true,
    toolbar: [
        ['style', ['bold', 'italic', 'underline', 'clear']],
        ['font', ['strikethrough', 'superscript', 'subscript']],
        ['color', ['color']],
        ['para', ['ul', 'ol', 'paragraph']],
        ['fontsize', ['fontsize']],
        ['fontname', ['fontname']]
    ]
});

function AddDateTime(){
    jQuery("#addVarintRow").append('<tr><td><div class="form-group mb-0"><input type="text" pattern="[A-Za-z0-9/s]{1,15}" class="form-control product_identifier" value="" name="product_identifier[]" ></div></td><td><div class="form-group mb-0"><input type="text" class="form-control" value="" name="variant_type[]" placeholder="Variant type" required=""></div></td><td><div class="form-group mb-0"><input type="text" class="form-control" value="" placeholder="Variant name" name="variant_name[]" required=""></div></td><td><div class="form-group mb-0"><input type="number" placeholder="Price" class="form-control numeric inMachine" min="0" value="" name="price[]" required="" step=".01"></div></td><td><div class="form-group mb-0"><a data-variant-id="0" class="btn btn-sm btn-primary btn-text-primary btn-icon deleteField" href="javascript:"><i class="fas fa-trash-alt fsize13"></i></a></div></td></tr>');
}

jQuery(document).ready(function () {

    //Remove Date Time
    jQuery(document).on("click", "#addVarintRow .deleteField", function(e) {
        e.preventDefault();

        if(jQuery(this).attr('data-variant-id') == '0'){
            var variant_id = jQuery(this).parent('div').parent('td').parent('tr').find('input[name="variartID[]"]').val();
            var trIndex = jQuery(this).parent('div').parent('td').parent().index();            

            if(typeof variant_id !== 'undefined' && variant_id != ''){
                jQuery("#deleteConfirm").find('#variant_id').val(variant_id);
                jQuery("#tr_indx").val(trIndex);
                jQuery("#deleteConfirm").modal('show');
            }else{
                jQuery(this).parent('div').parent('td').parent('tr').remove();
            }
        }else{
            var variantDeleteMsg = jQuery('#variantDeleteMsg').val();
            toastr.error(variantDeleteMsg);
            return false;
        }
    });

    var oldArray = [];
    $("input[name='product_identifier[]'").each(function() {
        oldArray.push($(this).val());
    });
    
    $.validator.addMethod("checkEditIdentifier", function(value, element, param) {            
        // var variantEditId = $("input[name='product_identifier[]']").map(function(){ return $(this).val(); }).get();
        // if(variantEditId.length > 1){
        //     let checkID = variantEditId.length === new Set(variantEditId).size;
        //     if(checkID == false){
        //         return false;
        //     }
        // }
        
        var variantId = ($(element).parent('.form-group').find("input[name='variartID[]']").val() != 'undefined') ? $(element).parent('.form-group').find("input[name='variartID[]']").val() : null;
        console.log(variantId);
        var response;   
        console.log(value);       
        if(value != ''){
            $.ajax({
                type: "POST",
                url: base_url+"/app/product/checkIdentifier",
                data:{
                    _token              : jQuery("input[name='_token']").val(),
                    product_identifier  : value,
                    pid                 : $("#pid").val(),
                    variantId           : variantId
                },
                async:false,
                success:function(result){
                    //console.log(result);
                    if(result.status){
                        response = '1';
                    }else{
                        response = '0';
                    }
                }
            });
            if(response == '1') return true;
            else if(response == '0') return false;
        }else{
           return true;
        }
            
    }, 'Product identifier already exist');

    $.validator.addMethod("checkIdentifier", function(value, element, param) {
        var variantEditId = $("input[name='product_identifier[]']").map(function(){ 
            if($(this).val() != ''){
                return $(this).val(); 
            }
        }).get();
        console.log(variantEditId);
        if(variantEditId.length > 1){
            let checkID = variantEditId.length === new Set(variantEditId).size;
            if(checkID == false){
                return false;
            }
        }
       
        var response;          
        if(value != ''){
            $.ajax({
                type: "POST",
                url: base_url+"/app/product/checkIdentifier",
                data:{
                    _token : jQuery("input[name='_token']").val(),
                    product_identifier:value                    
                },
                async:false,
                success:function(data){
                    if(data.status){
                        response = "1";
                    }else{
                        response = "0";
                    }                
                }
            });
            if(response == '1') return true;
            else if(response == '0') return false;
        }else{
            return true;
        }
            
    }, 'Product identifier already exist');

    $('#form_account').validate({
        errorElement: 'span',
        errorPlacement: function (error, element) {
            if ( element.attr("name") == "file" || element.attr("name") == "filename") {
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
            product_name: {
                required: true,
                remote:{
                    url : base_url+"/app/product/checkname",
                    type:'POST',
                    data:{
                        _token : jQuery("input[name='_token']").val(),
                        product_name:function(){ 
                            return jQuery("#product_name").val(); 
                        }
                    }
                },
            },
            'product_identifier[]':{
                checkIdentifier: true
            },
            filename: {
                required: true,
            }
        },
        messages: {
            product_name: {
                required: "Please enter product name.",
                remote: "Product name already exist.",
            },
        },
    });

    $('#form_account1').validate({
        errorElement: 'span',
        errorPlacement: function (error, element) {
            if ( element.attr("name") == "file" || element.attr("name") == "filename") {
                $("#image-error").append('Please select file.').addClass('error');
            }

            error.addClass('invalid-feedback');
            element.closest('.form-group').append(error);
        },
        // onfocusout: function(element) {$(element).valid()},
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
            },
            'product_identifier[]':{
                checkEditIdentifier:true
            }
        },
        messages: {
            // product_name: {
            //     required: "Please enter product name.",
            //     remote: "Product name already exist.",
            // },
        },
    });    

    jQuery("#delete-btn").click(function(e){
        e.preventDefault();
        var token   = jQuery("input[name='_token']").val();
        var productID = jQuery("#pid").val();
        var variant_id = jQuery("#variant_id").val();
        var trIndex = jQuery("#tr_indx").val();
        jQuery(this).text('Loading...');
        jQuery.ajax({
           url : base_url+"/app/product/deleteVariant",
            type: 'POST',
            data: { 
                '_token'    : token,
                'productID' : productID,
                'variant_id'   : variant_id,
            },
            dataType: "JSON",
            success: function(response) {
                if(response.status){
                    jQuery("#addVarintRow").find('tr:eq('+trIndex+')').remove();
                    jQuery("#deleteConfirm").modal('hide');
                }else{
                    jQuery("#delResult").html(response.message);
                     jQuery("#delete-btn").text('Yes');
                    //jQuery("#deleteConfirm").modal('hide');
                }
            }
        })
    })
    
    //Delete
    jQuery(document).on('click', '.delImage', function (e ) {
        var productID   = jQuery.trim( jQuery(this).attr('data-id') );
        var imageID     = jQuery.trim( jQuery(this).attr('data-subID') );  
        var token   = jQuery("input[name='_token']").val();
        $.ajax({
            url : base_url+"/app/product/deleteImage",
            type: 'POST',
            data: { 
                '_token'    : token,
                'productID' : productID,
                'imageID'   : imageID,
            },
            dataType: "JSON",
            success: function(response) {
                if(response.status == true){
                    
                    if(imageID == ''){
                        jQuery('#new_file').val('');                        
                        jQuery('#new_filename').val('');
                        jQuery('.imageDiv').remove();
                        jQuery('#fine-uploader-gallery').show();
                    }else{
                        jQuery('.imageDiv_'+imageID).remove();
                    }

                }else{
                    toastr.error(response.message);
                }
            }
        });
    });

    if (document.getElementById("tbl_account")) {
        var parentColVisible = $("#accType").val() == 'sub' ? true: false;
        if(parentColVisible){
            var columns = [
                {data: 'product_image', name: 'product_image', orderable: false, searchable: false},
                {data: 'product_name', name: 'product_name'},
                {data: 'product_machine', name: 'product_machine', orderable: false, searchable: false},
                {data: 'parent_name', name: 'parent_name', orderable: false, searchable: false},
                {data: 'product_variant', name: 'product_variant', orderable: false,searchable: true},
                // {data: 'product_status', name: 'product_status', orderable: false, searchable: false},
                {data: 'action', name: 'action', orderable: false, searchable: false},
            ];
        }else{
            var columns = [
                {data: 'product_image', name: 'product_image', orderable: false, searchable: false},
                {data: 'product_name', name: 'product_name'},
                {data: 'product_machine', name: 'product_machine', orderable: false, searchable: false},
                {data: 'product_variant', name: 'product_variant', orderable: false,searchable: true},
                // {data: 'product_status', name: 'product_status', orderable: false, searchable: false},
                {data: 'action', name: 'action', orderable: false, searchable: false},
            ];
        }
        var productlist = jQuery('#tbl_account').DataTable({ 
            processing: true,
            serverSide: true,
            bAutoWidth: true,
            bLengthChange: true,
            ajax: base_url+"/app/product/list",
            "columns": columns,
            order: [[0, 'desc']]
        });

        //Delete
        jQuery(document).on('click', '.delProduct', function (e ) {
            var productID   = jQuery.trim( jQuery(this).attr('data-id') );
            var countID     = jQuery.trim( jQuery(this).attr('data-count') );
            if(countID == 0){
                bootbox.confirm({
                    message: "Are you sure you want to delete this product?",
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
                                url : base_url+"/app/product/delete",
                                type: 'POST',
                                data: { 
                                    '_token'    : token,
                                    'productID' : productID,
                                },
                                dataType: "JSON",
                                success: function(response) {
                                    if(response.status == true){
                                        toastr.success(response.message, 'Product');
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
            }else{
                var productDeleteMsg = jQuery('#productDeleteMsg').val();
                toastr.error(productDeleteMsg);
                return false;
            }                      
        });

        //Retire Product
        jQuery(document).on('click', '.retProduct', function (e ) {
            var productID   = jQuery.trim( jQuery(this).attr('data-id') );
            var retDate     = jQuery.trim( jQuery(this).attr('data-value') );
            
            jQuery('#retireModal #retire_date').val(retDate);
            jQuery('#retireModal #retire_product_id').val(productID);                
            jQuery("#retireModal").modal();
        });

        //Save Retire Product
        jQuery(document).on('click', '.saveRetireModal', function (e ) {
            var productID   = jQuery.trim( jQuery('#retireModal #retire_product_id').val() );
            var retDate     = jQuery.trim( jQuery('#retireModal #retire_date').val() );            
            var token       = jQuery("input[name='_token']").val();

            if(retDate == ''){
                toastr.error('Please enter retire date.');
                jQuery('#retireModal #retire_date').focus();
                return false;
            }

            $.ajax({
                url : base_url+"/app/product/retire",
                type: 'POST',
                data: { 
                    '_token'    : token,
                    'productID' : productID,
                    'retDate'   : retDate,
                },
                dataType: "JSON",
                success: function(response) {
                    if(response.status == true){
                        toastr.success(response.message, 'Product');
                        var oTable = jQuery('#tbl_account').dataTable();
                        oTable.fnDraw(false);  

                        jQuery("#retireModal").modal('hide');
                    }else{
                        toastr.error(response.message);
                        return false;
                    }
                }
            });                    
        });
    }
});
