jQuery(document).ready(function(){

	jQuery("#data-time").change(function(e){
		getAnalyticData($(this).val());
	});

    $(document).on("change","#machines",function(){
        if($(this).val() != ""){
          var account_id = ($("#subaccount").length > 0 ) ? $("#subaccount").val() : null;
          counterdata($("#data-time").val(),$(this).val(),account_id);
        }
    });
    $(document).on("change","#subaccount",function(){
        
        if($(this).val() != ''){
            var account_id = $(this).val();
            $.ajax(
            {
                url:baseurl+'/app/ajax/getAccountMachine',
                type: "post",
                data: {account_id:account_id,"_token": jQuery("#_t").val()},
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
                    $("#machines").html(optHtml);
                }
                $('#loader').hide();
                
            })
            .fail(function(jqXHR, ajaxOptions, thrownError)
            {
                  //$("#product-data").append('<p class="text-center">No more products found</p>');
            });
        }else{
            var account_id = null;
            var optHtml = '<option value="">Select machines</option><option value="all">All machines</option>';
            $("#machines").html(optHtml);
        }
        
        getAnalyticData($("#data-time").val(),null,account_id);
    });


    function getAnalyticData(time,kiosk_id=null,account_id=null){
        var data = {time: time ,"_token": jQuery("#_t").val(),account_id:account_id};
        $.ajax(
        {
            url:baseurl+'/home',
            type: "post",
            data: data,
            beforeSend: function()
            {
                  $('#loader').show();
            }
        })
        .done(function(data)
        {
            console.log(data.html == "");
            if(data.html == ""){
                $('#loader').hide();
                return;
            }
            $("#home-data").html(data.html);
            $('[data-toggle="tooltip"]').tooltip();
            $('#loader').hide();
            
        })
        .fail(function(jqXHR, ajaxOptions, thrownError)
        {
              //$("#product-data").append('<p class="text-center">No more products found</p>');
        });
    }

    function counterdata(time,kiosk_id,account_id=null){
         var data = {time: time,kiosk_id:kiosk_id ,"_token": jQuery("#_t").val(),account_id:account_id};
        $.ajax(
        {
            url:baseurl+'/home/counterdata',
            type: "post",
            data: data,
            beforeSend: function()
            {
                  $('#loader').show();
            }
        })
        .done(function(data)
        {
            console.log(data.html == "");
            if(data.html == ""){
                $('#loader').hide();
                return;
            }
            $("#countsBlock").html(data.html);
            $('[data-toggle="tooltip"]').tooltip();
            $('#loader').hide();
            
        })
        .fail(function(jqXHR, ajaxOptions, thrownError)
        {
              //$("#product-data").append('<p class="text-center">No more products found</p>');
        });
    }

    $.ajax({
        method: "GET",
        url:baseurl+'/home/get_low_inventory_alert_data',
        data:{"_token": jQuery("#_t").val()},
        dataType : "json",
        success:function(response)
        {
            console.log(response.success);
            if(response.success == true) {
                var data_array  = response.result;
                tr_html = '';
                for(i=0; i < data_array.length ; i++) {
                    tr_html+='<tr><td>'+data_array[i].machine_name+'</td><td>'+data_array[i].bay_no+'</td><td>'+data_array[i].product_name+'</td><td>'+data_array[i].quantity+'</td></tr>'
                }
                $('.low_inventory_tbl tr:last').after(tr_html);
                $("#notificationModal").modal('show');

            }
            
        }
    });

jQuery("#notificationModal .close").click(function(e){
    e.preventDefault();
    confirm_close_notification();
})
    function confirm_close_notification() {
        var is_confirm_notify = confirm('Are you sure you want to hide this notification? You will not see this notification again until inventory for another machine bay goes below the LOW level.');
        if(is_confirm_notify) {
            console.log('confirm');
            $.ajax({
            method: "GET",
            url:baseurl+'/home/disable_notification_alert',
            data:{"_token": jQuery("#_t").val()},
            dataType : "json",
            success:function(response) {
                    console.log(response.success);
                    location.reload();
                }
            });
        } else {

        }
    }
});