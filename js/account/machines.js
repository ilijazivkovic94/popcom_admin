function getVarient(product_id , i){
    if(product_id != ''){

		$('#varient_type_'+i).prop('required', true);
		$('#varient_name_'+i).prop('required', true);
		$('#varient_price_'+i).prop('required', true);
		$('#quantity_'+i).prop('required', true);

        var token = jQuery("input[name='_token']").val();
		$.ajax({
            type: "POST",
            data: { 
                '_token'    : token,
                'productID' : product_id
            },
            url : base_url+"/app/machines-inventory/getVariant",
            dataType: "JSON",
            success:function(response) {
                $('#varient_type_'+i).children().remove().end();
                $('#varient_type_'+i).append('<option value="">Select Variant Type</option>');
                $('#varient_name_'+i).children().remove().end();
                $('#varient_name_'+i).append('<option value="">Select Variant Name</option>');
                $('#varient_price_'+i).val('');
                $('#quantity_'+i).val('');

                if(response.status == true){
                
                    var unique_list = [];
                    $.each(response.variantData, function(key, val) {   
                        if($.inArray(val.variant_sku, unique_list ) == -1){
                            unique_list.push(val.variant_sku);
                            
                            if(response.variantData.length == 1){
                                $('#varient_type_'+i).append('<option selected value="'+val.product_variant_id+'">'+val.variant_sku+'</option>');
                                $('#varient_name_'+i).append('<option selected value="'+val.product_variant_id+'">'+val.variant_name+'</option>');
                                $('#varient_price_'+i).val(val.variant_price);
                            }else{
                                $('#varient_type_'+i).append('<option value="'+val.product_variant_id+'">'+val.variant_sku+'</option>');
                            }

                        }
                    }); 
                }else{
                    toastr.error(response.message);
                }
  			}
		});
	}else{
		$('#varient_type_'+i).prop('required', false);
		$('#varient_name_'+i).prop('required', false);
		$('#varient_price_'+i).prop('required', false);
		$('#quantity_'+i).prop('required', false);

		$('#varient_type_'+i).children().remove().end();
		$('#varient_type_'+i).append('<option value="">Select Varient Type</option>');
		$('#varient_name_'+i).children().remove().end();
		$('#varient_name_'+i).append('<option value="">Select Varient Name</option>');
		$('#varient_price_'+i).val('');
		$('#quantity_'+i).val('');
	}
}

function getVarientName(variant_type , i){
	var product_id = $('#productID_'+i).val();
    var token = jQuery("input[name='_token']").val();
    if(variant_type.options[variant_type.selectedIndex].value != ''){
        var variant_name = variant_type.options[variant_type.selectedIndex].text;
        var variant_id = variant_type.options[variant_type.selectedIndex].value;
    }else{
        var variant_id = null;
         var variant_name = null;
    }
    console.log();
	$.ajax({
        type: "POST",
        data: { 
            '_token'    : token,
            'productID' : product_id,
            'variant_type' : variant_id,
            'variant_name' : variant_name
        },
        url : base_url+"/app/machines-inventory/getVariant",
        dataType: "JSON",
        success:function(response) {
            $('#varient_name_'+i).children().remove().end();
            $('#varient_name_'+i).append('<option value="">Select Variant Name</option>');
            $('#varient_price_'+i).val('');
            $('#quantity_'+i).val('');
            
            if(response.status == true){
                $.each(response.variantData, function(key, val) {   
                    $('#varient_name_'+i).append('<option value="'+val.product_variant_id+'">'+val.variant_name+'</option>');
                });            
            }else{
                toastr.error(response.message);
            }
        }
    });
}

function getVariantPrice(variant_id, i){
    var product_id = $('#productID_'+i).val();
    var token = jQuery("input[name='_token']").val();
	$.ajax({
	    type: "POST",
        data: { 
            '_token'    : token,
            'productID' : product_id,
            'variant_id' : variant_id
        },
        url : base_url+"/app/machines-inventory/getVarPrice",
        dataType: "JSON",
        success:function(response) {
            if(response.status == true){
                $('#varient_price_'+i).val(response.variantData);
            }else{
                toastr.error(response.message);
            }
        }
    });
}

$(document).ready(function () {
    
    if (document.getElementById("tbl_account")) {
        var table = $('#tbl_account').DataTable({ 
            processing: true,
            serverSide: true,
            bAutoWidth: true,
            bLengthChange: true,
            ajax: base_url+"/app/machines-inventory/list",
            "columns": [
                {data: 'kiosk_identifier', name: 'kiosk_identifier'},
                {data: 'kiosk_city', name: 'kiosk_city'},
                {data: 'kiosks_state', name: 'kiosks_state'},
                {data: 'products_count', name: 'products_count', searchable: false},
                {data: 'kiosk_low_inv_threshold', name: 'kiosk_low_inv_threshold', orderable: false, searchable: false},
                {data: 'assigned_products', name: 'assigned_products', orderable: false, searchable: false},
                {data: 'action', name: 'action', orderable: false, searchable: false},
            ]
        });
    }

    if (document.getElementById("tbl_machines")) {
        var table = $('#tbl_machines').DataTable({ 
            processing: true,
            serverSide: true,
            bAutoWidth: true,
            bLengthChange: true,
            ajax: {
                url: base_url+"/app/machines/list",
                data: function ( data ) {
                    data.account_id = jQuery('#accountID').val();
                } 
            },
            "columns": [
                {data: 'kiosk_serial_no', name: 'kiosk_serial_no'},
                {data: 'kiosk_identifier', name: 'kiosk_identifier'},
                {data: 'kiosk_street', name: 'kiosk_street'},
                {data: 'kiosk_city', name: 'kiosk_city'},
                {data: 'kiosks_state', name: 'kiosks_state'},
                {data: 'kiosk_country', name: 'kiosk_country'},
                {data: 'kiosk_zip', name: 'kiosk_zip'},
                {data: 'pos_min_age', name: 'pos_min_age'},
                {data: 'model_name', name: 'model_name', orderable: true, searchable: false},
                {data: 'template_name', name: 'template_name', orderable: true, searchable: false},
                {data: 'kiosk_status', name: 'kiosk_status', orderable: true, searchable: false},
                {data: 'action', name: 'action', orderable: false, searchable: false},
            ]
        });
    }

   

     $("input[name='productPrice[]']").change(function(){
      
        var price = $(this).val();
        var curProductId,curVarientType,curVarientName;
        var pid = $(this).parents('tr').attr('id');
        $(this).parents('tr').find('select').each(function(){
            if($(this).attr('id').indexOf('productID') !== -1){
                curProductId = $(this).val();
            }else if($(this).attr('id').indexOf('varient_type') !== -1){
                curVarientType = $(this).val();
            }else if($(this).attr('id').indexOf('varient_name') !== -1){
                curVarientName = $(this).val();
            }
        });
        // console.log(curProductId);
        // console.log(curVarientType);
        // console.log(curVarientName);
        $("#machine_inventory tr").each(function(){
           if($(this).attr('id') != pid){
                var self = $(this);
                var tempProductId,tempVarientType,tempVarientName;
                self.find('select').each(function(){
                    if($(this).attr('id').indexOf('productID') !== -1){
                        tempProductId = $(this).val();
                    }else if($(this).attr('id').indexOf('varient_type') !== -1){
                        tempVarientType = $(this).val();
                    }else if($(this).attr('id').indexOf('varient_name') !== -1){
                        tempVarientName = $(this).val();
                    }
                });

                // console.log($(this).attr('id'));
                // console.log(tempProductId,"Temp");
                // console.log(tempVarientType,"Temp");
                // console.log(tempVarientName,"Temp");

                if(tempProductId == curProductId && tempVarientType == curVarientType && tempVarientName == curVarientName){
                    var tempPrice = self.find('input[name="productPrice[]"]').val();
                     console.log(tempPrice,"tempPrice");
                    if(tempPrice != price){
                        alert('Same variants should have same price.');
                        $(".btn-primary").prop('disabled',true);
                        return false;
                    }else{
                        $(".btn-primary").prop('disabled',false);
                    }
                }
            }

        });
        
     });

    $('#form_add_machine').validate({
      
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
});