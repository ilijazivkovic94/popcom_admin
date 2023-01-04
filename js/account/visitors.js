
jQuery(document).ready(function () {

// set default dates
   
    var accountId ;

    if (document.getElementById("tbl_visitors")) {
        if($("#subaccount").length > 0){
            var columns =  [
                {data: 'created_at', name: 'created_at', searchable: false},
                {data: 'account_name', name: 'account_name', searchable: false},
                {data: 'kiosk_identifier', name: 'kiosk_identifier', searchable: false},
                {data: 'customer_email', name: 'customer_email', searchable: false},
                {data: 'customer_gender', name: 'customer_gender', searchable: false},
                {data: 'journey_emotion_json', name: 'journey_emotion_json', searchable: false,orderable: false},
                {data: 'customer_age_group', name: 'customer_age_group', searchable: false},
                {data: 'dispensed_yn', name: 'dispensed_yn', searchable: false},
                {data: 'order_total', name: 'order_total', searchable: false}
            ];
        }else{
            var columns =  [
                {data: 'created_at', name: 'created_at', searchable: false},
                {data: 'kiosk_identifier', name: 'kiosk_identifier'},
                {data: 'customer_email', name: 'customer_email'},
                {data: 'customer_gender', name: 'customer_gender'},
                {data: 'journey_emotion_json', name: 'journey_emotion_json', searchable: false,orderable: false},
                {data: 'customer_age_group', name: 'customer_age_group', searchable: false},
                {data: 'dispensed_yn', name: 'dispensed_yn', searchable: false},
                {data: 'order_total', name: 'order_total'}
            ];
        }
        var accountNameVisible = $("#subaccount").length > 0 ? true : false;
        var visitorTable = jQuery('#tbl_visitors').DataTable({ 
            processing: true,
            serverSide: true,
            bAutoWidth: true,
            bLengthChange: true,
            order: [0],
            ajax: {
                url: base_url+"/app/visitors/list",
                data: function ( data ) {
                    data.kiosk_id = jQuery('#kiosk_id').val();
                    data.timeperiod = jQuery('#data-time').val();
                    data.datepick = $('input[name="daterange"]').val();
                    data.accountId = $("#subaccount").length > 0 ? $("#subaccount").val() : null;
                    data.type='datatable';
                }      
            },
            "columns": columns,
            "footerCallback" :  function ( row, data, start, end, display ) {
                //update_all_sale_table_head();
            }

        });
                    
        jQuery('#kiosk_id').on('change', function(){
            visitorTable.draw();
        });  
        jQuery('#data-time').on('change', function(){
            $('input[name="daterange"]').val('');
            visitorTable.draw();
        });  
        jQuery("#subaccount").on('change', function(){
            var accountId =  $(this).val();
            if(accountId != ''){
                accountId = $(this).val();
                $.ajax(
                {
                    url:baseurl+'/app/ajax/getAccountMachine',
                    type: "post",
                    data: {account_id:accountId,"_token": jQuery("#_t").val()},
                    beforeSend: function()
                    {
                          $('#loader').show();
                    }
                })
                .done(function(data)
                {
                    
                    if(data.machines.length > 0){
                        var optHtml = '<option value="">Select machines</option>';
                        optHtml += '<option value="">All machines</option>';
                        for(var m of data.machines){
                            optHtml += '<option value="'+m.kiosk_id+'">'+m.kiosk_identifier+'</option>';
                        }
                        $("#kiosk_id").html(optHtml);
                    }else{
                        var optHtml = '<option value="">All machines</option>';
                        $("#kiosk_id").html(optHtml);
                    }
                    $('#loader').hide();
                    
                })
                .fail(function(jqXHR, ajaxOptions, thrownError)
                {
                      //$("#product-data").append('<p class="text-center">No more products found</p>');
                });
            }else{
                //accountId = null;
                var optHtml = '<option value="">All machines</option>';
                $("#kiosk_id").html(optHtml);
            }
            visitorTable.draw();
        });  
              
    }

    $('input[name="daterange"]').daterangepicker({
      opens: 'center',
      autoUpdateInput: false,
      maxDate: moment(new Date()),
    }, function(start, end, label) {
      $('input[name="daterange"]').val(start.format('YYYY-MM-DD')+' to '+end.format('YYYY-MM-DD'));
        visitorTable.draw();
      //console.log("A new date selection was made: " + start.format('YYYY-MM-DD') + ' to ' + end.format('YYYY-MM-DD'));
    });

    $('input[name="daterange"]').keyup(function(){
        console.log("s");
        if($(this).val() == ''){
             visitorTable.draw();
             $('input[name="daterange"]').trigger('change');
        }
    })

    function update_all_sale_table_head(){
        $.ajax({
                method: "POST",
                url:base_url+"/app/sales/getSalesTotal",
                data:{kiosk_id : jQuery('#kiosk_id').val(),
                    timeperiod : jQuery('#data-time').val(),
                    cart_event : jQuery('#cart_events').val(),
                    fromDate : jQuery('#fromDate').val(),
                    toDate : jQuery('#toDate').val(),
                    type:'total',
                    _token:jQuery('#_t').val(),
                },
                dataType : "json",
                success:function(data)
                {
                    $(".qty-total").text('Total Products: '+data.quantity);
                    $(".sub-total").text('Sub-Total: '+data.subtotal);
                    $(".tax").text('Tax: '+data.tax);
                    $(".total").text('Total: '+data.total);
                }
            });
    }  

    
    

});