jQuery(document).ready(function(){
    var options = [], labels=[];
      

    $("#kiosk_id").change(function(){
       var data = {kiosk_id: $(this).val(),"_token": jQuery("#_t").val(),accountId:null};
         getAnalyticData(data);
    });

    jQuery("#subaccount").on('change', function(){
            if( $(this).val() != ''){
                var accountId =  $(this).val();
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
                var accountId = null;
                var optHtml = '<option value="">Select machines</option><option value="">All machines</option>';
                $("#kiosk_id").html(optHtml);
            }
            //visitorTable.draw();
          var daterange = $('input[name="daterange"]').val();
          if(daterange != ''){
            var dt = daterange.split(' to ');
            var strtdt = dt[0];
            var enddt = dt[1];
            var time = 'picker';
          }else{
            var time = $("#datePeriod").val();
            var strtdt = null;
            var enddt = null;
          }

        var data = {kiosk_id: null,"_token": jQuery("#_t").val(),accountId:accountId};
        getAnalyticData(data);


       if(jQuery("#productSalePicker").val() == ''){
          loadProductChart($("#product-datePeriod").val(),null,null,accountId);
       }else{
           var daterange = $('#productSalePicker').val();
           var dt = daterange.split(' to ');
           var strtdt = dt[0];
            var enddt = dt[1];
          loadProductChart('picker',start,enddt,accountId);
       }

       if(jQuery("#genderSalePicker").val() == ''){
         loadCustomerChart($("#gender-datePeriod").val(),'gender',null,null,accountId);
       }else{
            var daterange = $('#genderSalePicker').val();
           var dt = daterange.split(' to ');
           var strtdt = dt[0];
            var enddt = dt[1];
          loadCustomerChart('picker','gender',start,enddt,accountId);
       }
       
       if(jQuery("#ageGroupPicker").val() == ''){
           loadCustomerChart($("#agegroup-datePeriod").val(),'agegroup',null,null,accountId);
       }else{
           var daterange = $('#ageGroupPicker').val();
           var dt = daterange.split(' to ');
           var strtdt = dt[0];
           var enddt = dt[1];
          loadCustomerChart('picker','agegroup',start,enddt,accountId);
       }

       if($("#productListPicker").val()==''){
          loadList(jQuery("#productlist-datePeriod").val(),null,null,accountId);
       }else{
           var daterange = $('#productListPicker').val();
           var dt = daterange.split(' to ');
           var strtdt = dt[0];
           var enddt = dt[1];
          loadList('picker',start,enddt,accountId);
       }

      
       
    });  

    function getAnalyticData(data){
      $.ajax(
            {
                url:baseurl+'/app/sales/analytics',
                type: "get",
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
              $('#loader').hide();
                
            })
            .fail(function(jqXHR, ajaxOptions, thrownError)
            {
                  //$("#product-data").append('<p class="text-center">No more products found</p>');
            });
    }

    $('input[name="daterange"]').daterangepicker({
      opens: 'center',
      autoUpdateInput: false,
      maxDate: moment(new Date()),
    }, function(start, end, label) {
      var kiosk_ids = ($("#kiosk_ids").val() != "") ? $("#kiosk_ids").val() : null;
      loadChart('picker',kiosk_ids,start.format('YYYY-MM-DD'),end.format('YYYY-MM-DD'),true);
      $('input[name="daterange"]').val(start.format('YYYY-MM-DD')+' to '+end.format('YYYY-MM-DD'));
      console.log("A new date selection was made: " + start.format('YYYY-MM-DD') + ' to ' + end.format('YYYY-MM-DD'));
    });

    $('.daterange').daterangepicker({
      opens: 'center',
      autoUpdateInput: false,
      maxDate: moment(new Date()),
    }, function(start, end, label) {
      if(this.element.attr('id') == 'productSalePicker') {
         loadProductChart('picker',start.format('YYYY-MM-DD'),end.format('YYYY-MM-DD'));
       }else if(this.element.attr('id') == 'genderSalePicker'){
         loadCustomerChart('picker','gender',start.format('YYYY-MM-DD'),end.format('YYYY-MM-DD'));
       }else if(this.element.attr('id') == 'ageGroupPicker'){
          loadCustomerChart('picker','agegroup',start.format('YYYY-MM-DD'),end.format('YYYY-MM-DD'));
       } else{
         loadList('picker',start.format('YYYY-MM-DD'),end.format('YYYY-MM-DD'));
       }
        this.element.val(start.format('YYYY-MM-DD')+' to '+end.format('YYYY-MM-DD'));
        console.log(this.element.val());
    });

    $( '.dropdown-menu a' ).on( 'click', function( event ) {
       var $target = $( event.currentTarget ),
           val = $target.attr( 'data-value' ),
           label = $target.find('span').text(),
           $inp = $target.find( 'input' ),
           idx;

       if ( ( idx = options.indexOf( val ) ) > -1 ) {
          options.splice( idx, 1 );
          labels.splice(label,1) ;
          setTimeout( function() { $inp.prop( 'checked', false ) }, 0);
       } else {
          options.push( val );
          labels.push(label) ;
          setTimeout( function() { $inp.prop( 'checked', true ) }, 0);
       }

       $( event.target ).blur();
          
       //console.log( labels );
       if(labels.length > 0){
        $(".machine-label").text(labels.join(','));
       }else{
        $(".machine-label").text('All Machine');
       }
       
       $("#kiosk_ids").val(options);
       var dates = $('input[name="daterange"]').val();
       
       if(dates != ""){
          var dt = dates.split(' to ');
           var strtdt = dt[0];
          var enddt = dt[1];
          var time = 'picker';
       }else{
        var strtdt = null;
        var enddt = null;
        var time = 'today';
       }
       loadChart(time,options,strtdt,enddt);
       return false;
    });
    var line_chart;
    loadChart('today');
    function loadChart(times,kiosk_id=null,startdt=null,enddt=null,destroy=false){
        var token = $("#_t").val();
        var data = {time:times,kiosk_id:kiosk_id,startdt:startdt, enddt:enddt , _token:token};
        var bar_chart ;
        var machineData= {} ;
        $.post(base_url+'/app/sales/getSalesChart',data, function(data) { 
            console.log(data);
            var line_ctx = document.getElementById('saleChart');
            if (line_chart) {
                line_chart.destroy();
            }
            line_chart = new Chart(line_ctx, {
               type: 'line',
                data: {
                    labels: data[0].yaxis,
                    datasets: data[0].charData
                },
                options: {
                    responsive: true,
                    scales: {
                        yAxes: [{
                            ticks: {
                                // Include a dollar sign in the ticks
                                callback: function(value, index, values) {
                                    return '$' + value;
                                }
                            }
                        }]
                    },
                    plugins: {
                      legend: {
                        position: 'top',
                      }
                    }
                  } // options
               }
            );
            
            let allMachineTotal=0;
            var allTotalHtml = '<h3>ALL Machines</h3>';

            var htmlData = '<h3>Selected Machines</h3>';
            htmlData += '<div class="listmachine">'+
                        '<ul>';
            for(var machine of data[0].charData){
                     var mTotal = machine.data.reduce(sumTotal);
                     var price = new Intl.NumberFormat('en-US', { style: 'currency', currency: 'USD' }).format(mTotal);
                     htmlData += '<li>'+
                                  '<span class="machineTitle text-capitalize">'+machine.label+'</span>'+
                                  '<span>'+price+'</span>'+
                                '</li>';
                  allMachineTotal = allMachineTotal + mTotal;
                
            }
            htmlData += '</ul></div>';
            allTotalHtml += '<h6>'+new Intl.NumberFormat('en-US', { style: 'currency', currency: 'USD' }).format(allMachineTotal)+'</h6>';
            
            $(".allmachineTotal").html(allTotalHtml);
            if(kiosk_id != null){
              $(".selectedmachines").html(htmlData);
            }

        });
    }

    function sumTotal(total, num){
        return total + num;
    } 

    function getChartLabel(time){
      var ct = '';
       if(time=='today')
        {
          ct = "Today";
        }
        else if(time=='week')
        {
           ct = 'Current Week';
        }
        else if(time=='month')
        {
           ct = 'Current Month';
        }
        else if(time=='year')
        {
           ct = 'Current Year';
        }
        else if(time=='lastyear')
        {
           ct = 'Last Year';
        }

        return ct;
    }

    loadProductChart('today');
    $("#product-datePeriod").change(function(){
        $('#productSalePicker').val('');
        var time = $(this).val();
        var label = getChartLabel(time);
        $(".productSaleChartLabel").text(label);
        loadProductChart(time);
    });
    $("#productlist-datePeriod").change(function(){
        $('#productListPicker').val('');
        var time = $(this).val();
       
        loadList(time);
    });
    $("#gender-datePeriod").change(function(){
        $('#genderSalePicker').val('');
        var time = $(this).val();
        var label = getChartLabel(time);
        $(".saleGenderLabel").text(label);
        loadCustomerChart(time,'gender');
    });
    $("#agegroup-datePeriod").change(function(){
        $('#ageGroupPicker').val('');
        var time = $(this).val();
        var label = getChartLabel(time);
        $(".saleAgeGroupLabel").text(label);
        loadCustomerChart(time,'agegroup');
    });

    $(".cancelBtn").click(function(){
      console.log(this.closest('input'));
        $(this).val('');
    });
   
    var pbar_chart;
     function loadProductChart(times,startdt=null,enddt=null,accountId=null){
        var token = $("#_t").val();
        var data = {time:times,startdt:startdt, enddt:enddt , _token:token,type:'chart',accountId:accountId};
        
        $.post(base_url+'/app/sales/getProductSalesChart',data, function(data) { 
            console.log(data);
            var bar_ctx = document.getElementById('productSaleChart');
             if (pbar_chart) {
                pbar_chart.destroy();
            }
            pbar_chart = new Chart(bar_ctx, {
               type: 'bar',
                data: {
                    labels: data[0].yaxis,
                    datasets: data[0].charData
                },
                options: {
                    responsive: true,
                    scales: {
                        xAxes: [{
                            stacked: true
                        }],
                        yAxes: [{
                            stacked: true,
                            ticks: {
                                // Include a dollar sign in the ticks
                                callback: function(value, index, values) {
                                    return '$' + value;
                                }
                            }
                        }]
                    },
                    plugins: {
                      legend: {
                        position: 'top',
                      }
                    }
                  } // options
               }
            );
        });
     }

     loadCustomerChart('today','gender');
     loadCustomerChart('today','agegroup');
    
   
     function loadCustomerChart(times,type,startdt=null,enddt=null,accountId=null){
        var token = $("#_t").val();
        var data = {time:times,startdt:startdt, enddt:enddt , _token:token,type:type,accountId:accountId};
        $.ajax({
          url:base_url+'/app/sales/getCustomerSalesData',
          data:data,
          type:'POST',
          async:false,
          success:function(data){
           
             if(type == 'gender'){
                var id = 'saleGenderChart';
              
                genderChartRender(data,id);
              }else{
                var id = 'saleAgeGroupChart';
                ageChartRender(data,id);
              }
          }
        })
        // $.post(,data, function(data) { 
           
           
        // });
     }

      var bar_chart;
     function genderChartRender(data,id){
         
         var bar_ctx = document.getElementById(id);
            if (bar_chart) {
                bar_chart.destroy();
            }
            bar_chart = new Chart(bar_ctx, {
               type: 'line',
                data: {
                    labels: data[0].yaxis,
                    datasets: data[0].charData
                },
                options: {
                    responsive: true,
                    scales: {
                        yAxes: [{
                            stacked: true,
                            ticks: {
                                // Include a dollar sign in the ticks
                                callback: function(value, index, values) {
                                    return '$' + value;
                                }
                            }
                        }]
                    },
                    plugins: {
                      legend: {
                        position: 'top',
                      }
                    }
                  } // options
               }
            );
     }
      var agebar_chart;
     function ageChartRender(data,id){
         var bar_ctx = document.getElementById(id);
            if (agebar_chart) {
                agebar_chart.destroy();
            }
            agebar_chart = new Chart(bar_ctx, {
               type: 'line',
                data: {
                    labels: data[0].yaxis,
                    datasets: data[0].charData
                },
                options: {
                    responsive: true,
                    scales: {
                        yAxes: [{
                            stacked: true,
                            ticks: {
                                // Include a dollar sign in the ticks
                                callback: function(value, index, values) {
                                    return '$' + value;
                                }
                            }
                        }]
                    },
                    plugins: {
                      legend: {
                        position: 'top',
                      }
                    }
                  } // options
               }
            );
     }

     function loadList(times,startdt=null,enddt=null,accountId=null){
        var token = $("#_t").val();
        var data = {time:times,startdt:startdt, enddt:enddt , _token:token,type:'list',accountId:accountId};
        $.post(base_url+'/app/sales/getProductSalesChart',data, function(data) { 
            var listhtml = '';
            if(data[0].length > 0){
                for(var prod of data[0]){
                  var price = new Intl.NumberFormat('en-US', { style: 'currency', currency: 'USD' }).format(prod.total_sale);
                
                  listhtml += '<tr>'+
                              '<td><span class="produt_img"><img src="'+prod.product_image+'" /></span>'+prod.product_name+'</td>'+
                              '<td>'+price+'</td>'+
                              '<td>'+prod.sale_percentage+'%</td>'+
                           '</tr>';
                }
                
                $("#listTable tbody").html(listhtml);
            }else{
               $("#listTable tbody").html('');
            }
        })
     }
});