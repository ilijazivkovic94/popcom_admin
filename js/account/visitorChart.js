jQuery(document).ready(function(){ 
	

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
                        var optHtml = '<option value="">Select machine</option>'+
                                      '<option value="">All machines</option>';
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

            loadVisitorChart(time,null,strtdt,enddt,accountId);

            var data = {kiosk_id: null,accountId:accountId,"_token": jQuery("#_t").val()};
        
    		loadAnalyticData(data);
    });  

	$('input[name="daterange"]').daterangepicker({
      opens: 'center',
      autoUpdateInput: false,
       maxDate: moment(new Date()),
    }, function(start, end, label) {
      var kiosk_ids = ($("#kiosk_id").val() != "") ? $("#kiosk_id").val() : null;
      var accountId = (jQuery("#subaccount").length > 0) ? jQuery("#subaccount").val() : null;
      loadVisitorChart('picker',kiosk_ids,start.format('YYYY-MM-DD'),end.format('YYYY-MM-DD'),accountId);
      $('input[name="daterange"]').val(start.format('YYYY-MM-DD')+' to '+end.format('YYYY-MM-DD'));
      console.log("A new date selection was made: " + start.format('YYYY-MM-DD') + ' to ' + end.format('YYYY-MM-DD'));
    });

    $('input[name="daterange"]').on('cancel.daterangepicker', function(ev, picker) {
          $(this).val('');
          var kiosk_ids = ($("#kiosk_id").val() != "") ? $("#kiosk_id").val() : null;
          var accountId = (jQuery("#subaccount").length > 0) ? jQuery("#subaccount").val() : null;
          loadVisitorChart('today',kiosk_ids,null,null,accountId);
    });

	$("#datePeriod").change(function(){
        var time = $(this).val();
        var kiosk_id = ($("#kiosk_id").val() != '') ? $("#kiosk_id").val() : null;
        var accountId = (jQuery("#subaccount").length > 0) ? jQuery("#subaccount").val() : null;
        loadVisitorChart(time,kiosk_id,null,null,accountId);
         $('input[name="daterange"]').val('');

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

        $(".chartLable").text(ct);

      
    });

    $("#kiosk_id").change(function(){
    	var data = {kiosk_id: $(this).val(),"_token": jQuery("#_t").val()};
        
    	loadAnalyticData(data);
        var kiosk_id = $(this).val();
        var daterange = $('input[name="daterange"]').val();
        if(daterange != ''){
          var dt = daterange.split(' to ');
          var strtdt = dt[0];
          var enddt = dt[1];
          loadVisitorChart('picker',kiosk_ids,strtdt,enddt);
        }else{
        	var time = $("#datePeriod").val();
        	loadVisitorChart(time,kiosk_id);
        }
        //var kiosk_id = ($("#kiosk_id").val() != '') ? $("#kiosk_id").val() : null;
        
    });


    function loadAnalyticData(data){
    	$.ajax(
            {
                url:baseurl+'/app/visitors/analytics',
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
    var gender_chart;
    var age_chart;
    var emotion_chart;
    loadVisitorChart('today');
	function loadVisitorChart(time,kiosk_id=null,startdt=null,enddt=null,accountId=null){
		var token = $("#_t").val();
        var data = {time:time,startdt:startdt,enddt:enddt , _token:token,kiosk_id:kiosk_id,accountId:accountId};
        
        $.post(base_url+'/app/visitors/getAnalyticChartData',data, function(data) { 
            console.log(data);
            var gender_ctx = document.getElementById('genderChart');

            if (gender_chart) {
                gender_chart.destroy();
            }
            gender_chart = new Chart(gender_ctx, {
                type: 'bar',
                data: {labels: data.genderChart.yaxis, datasets: data.genderChart.charData},
                options: {responsive: true,
                    scales: {

                        yAxes:[{stacked: true}]
                    },
                    plugins: { legend: {position: 'top',}}
                  } 
               }
            );

            var age_ctx = document.getElementById('ageChart');
            if (age_chart) {
                age_chart.destroy();
            }
            age_chart = new Chart(age_ctx, {
                type: 'bar',
                data: {labels: data.ageData.yaxis, datasets: data.ageData.charData},
                options: {responsive: true,
                    scales: {
                    	
                        yAxes:[{stacked: true}]
                    },
                    plugins: { legend: {position: 'top',}}
                  } 
               }
            );

             var emotion_ctx = document.getElementById('emotionChart');
             if (emotion_chart) {
                emotion_chart.destroy();
            }
            emotion_chart = new Chart(emotion_ctx, {
                type: 'bar',
                data: {labels: data.emotionData.yaxis, datasets: data.emotionData.charData},
                options: {responsive: true,
                    scales: {
                    	xAxes: [{stacked: true }],
                        yAxes:[{stacked: true}]
                    },
                    plugins: { legend: {position: 'top'}}
                  } 
               }
            );


        });


	}

});