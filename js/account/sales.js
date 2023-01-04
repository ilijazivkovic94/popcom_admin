
jQuery(document).ready(function () {

// set default dates
    // var start = new Date();
    // // set end date to max one year period:
    // var end = new Date(new Date().setYear(start.getFullYear()+1));

    // $('#fromDate').datepicker({
    //     startDate : start,
    //     endDate   : end
    // // update "toDate" defaults whenever "fromDate" changes
    // }).on('changeDate', function(){
    //     // set the "toDate" start to not be later than "fromDate" ends:
    //     $('#toDate').datepicker('setStartDate', new Date($(this).val()));
    // }); 

    // $('#toDate').datepicker({
    //     startDate : start,
    //     endDate   : end
    // // update "fromDate" defaults whenever "toDate" changes
    // }).on('changeDate', function(){
    //     // set the "fromDate" end to not be later than "toDate" starts:
    //     $('#fromDate').datepicker('setEndDate', new Date($(this).val()));
    //     salesTable.draw();
    // });

    

    if (document.getElementById("tbl_sales")) {
        var accountNameVisible = $("#subaccount").length > 0 ? true : false;
        
        if($("#subaccount").length > 0){
            var columns =  [
                {data: 'order_id', name: 'order_id'},
                {data: 'created_on', name: 'created_on'},
                {data: 'account_name', name: 'account_name'},
                {data: 'kiosk_identifier', name: 'kiosk_identifier'},
                {data: 'customer_email', name: 'customer_email'},
                {data: 'product_qty', name: 'product_qty'},
                {data: 'order_subtotal', name: 'order_subtotal'},
                {data: 'order_tax', name: 'order_tax'},
                {data: 'order_total', name: 'order_total'},
                {data: 'order_discount_value', name: 'order_discount_value'},
                {data: 'promo_code', name: 'promo_code'},
            ];
            var dom = '<"top"fi>rt<"top"lp><"clear">';
        }else{
            var columns =  [
                {data: 'order_id', name: 'order_id'},
                {data: 'created_on', name: 'created_on'},
                {data: 'kiosk_identifier', name: 'kiosk_identifier'},
                {data: 'customer_email', name: 'customer_email'},
                {data: 'product_qty', name: 'product_qty'},
                {data: 'order_subtotal', name: 'order_subtotal'},
                {data: 'order_tax', name: 'order_tax'},
                {data: 'order_total', name: 'order_total'},
                {data: 'order_discount_value', name: 'order_discount_value'},
                {data: 'promo_code', name: 'promo_code'},
            ];
            var dom = '<"top"fi>rt<"top"lp><"clear">';
        }

        var salesTable = jQuery('#tbl_sales').DataTable({ 
            processing: true,
            serverSide: true,
            bAutoWidth: true,
            bLengthChange: true,
            order: [0],
            //sDom: '<"toolbar"irt<"bottom"lp><"clear">',
            dom: dom,
            fnInitComplete: function(){
              $("#tbl_sales_info").wrap( "<div class='total_count_box'></div>" )
              $("#tbl_sales_info").after('<span style="margin-right:20px" class="qty-total">Total Products: 0</span><span style="margin-right:20px" class="sub-total">Sub-Total: $ 0.00</span><span style="margin-right:20px" class="tax">Tax: $ 0.00</span><span style="margin-right:20px" class="total">Total: $ 0.00</span>'); 
            
            },
            language:{
                "info": "Total Orders: _TOTAL_ ",
                "infoEmpty": "Total Orders: 0"
            },
            ajax: {
                url: base_url+"/app/sales/list",
                data: function ( data ) {
                    data.kiosk_id = jQuery('#kiosk_id').val();
                    data.timeperiod = jQuery('#data-time').val();
                    data.cart_event = jQuery('#cart_events').val();
                    data.datepick = jQuery('input[name="daterange"]').val();
                    data.accountId = $("#subaccount").length > 0 ? $("#subaccount").val() : null;
                    data.type='datatable';
                    data.email = $('#email_id').val();
                }      
            },
            "columns":columns,
            "footerCallback" :  function ( row, data, start, end, display ) {
                update_all_sale_table_head();
            }

        });
                    
        jQuery('#kiosk_id').on('change', function(){
            salesTable.draw();
        });  
        jQuery('#data-time').on('change', function(){
            $('input[name="daterange"]').val('');
            salesTable.draw();
        });  
        jQuery('#cart_events').on('change', function(){
            salesTable.draw();
        });  

        $('input[name="daterange"]').daterangepicker({
          opens: 'center',
          autoUpdateInput: false,
          maxDate: moment(new Date()),
        }, function(start, end, label) {
          $('input[name="daterange"]').val(start.format('YYYY-MM-DD')+' to '+end.format('YYYY-MM-DD'));
          salesTable.draw();
          console.log("A new date selection was made: " + start.format('YYYY-MM-DD') + ' to ' + end.format('YYYY-MM-DD'));
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
                        for(var m of data.machines){
                            optHtml += '<option value="'+m.kiosk_id+'">'+m.kiosk_identifier+'</option>';
                        }
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
                var optHtml = '<option value="">Select machines</option><option value="all">All machines</option>';
                $("#kiosk_id").html(optHtml);
            }
            salesTable.draw();
        });  
              
    }

    function update_all_sale_table_head(){
        $.ajax({
                method: "POST",
                url:base_url+"/app/sales/getSalesTotal",
                data:{kiosk_id : jQuery('#kiosk_id').val(),
                    timeperiod : jQuery('#data-time').val(),
                    cart_event : jQuery('#cart_events').val(),
                    datepick : $('input[name="daterange"]').val(),
                    accountId : $("#subaccount").length > 0 ? $("#subaccount").val() : null,
                    type:'total',
                    _token:jQuery('#_t').val(),
                    email : $('#email_id').val()
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