function showPassword() {
    var x = document.getElementById("password");
    if(x.value!=''){
        if (x.type === "password") {
            x.type = "text";
            $('.togglePassword').addClass('far fa-eye');
            $('.togglePassword').removeClass('fas fa-eye-slash');
        } else {
            x.type = "password";
            $('.togglePassword').removeClass('far fa-eye');
            $('.togglePassword').addClass('fas fa-eye-slash');
        }
    }
}
function randomPassword(){
    if(confirm("CAUTION: Changing the machine password will cause the POS to fail and requires a manual update to the machine. Do you want to proceed?")){
        const result = Math.random().toString(36).substring(2,10);
        $("#password").val(result);
    }
}

function generatePin(){
    if(confirm("Are you sure you want to regenerate the PIN?")){
        const result = Math.random().toString(36).substring(2,10);
        $("#pos_pin").val(result);
    }
}

$("input[name='pos_age_regulation']").click(function(){
    console.log($('input:radio[name=pos_age_regulation]:checked').val());
    if($('input:radio[name=pos_age_regulation]:checked').val() == "Y"){
        $("#pos_min_age").attr('disabled', false);
        $('#pos_min_age').val(18);
        $("#consumption").show();
    }
    else{
    	$("#consumption").hide();
        $('#pos_min_age').val(0);
        $("#pos_min_age").attr('disabled', true);
    }
});

$(document).on('click', '.show_popup', function(e) {
    $('#common_modal').modal('show');
    $("#modal_content").html('<div class="modal-header"><h5 class="modal-title">'+$(this).data('title')+'</h5><button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button></div><div class="modal-body"><p>'+$(this).data('message')+'</p></div><div class="modal-footer"><a href='+$(this).data('url')+' type="button" class="btn btn-primary">Continue</a><button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button></div>');
    $(".modal-backdrop").hide();
});


$(document).ready(function(){
    var myDate = new Date();
    var hrs = myDate.getHours();
    var greet;
    if (hrs < 12)
        greet = 'Good Morning!';
    else if (hrs >= 12 && hrs <= 17)
        greet = 'Good Afternoon!';
    else if (hrs >= 17 && hrs <= 24)
        greet = 'Good Evening!';
    jQuery('.timemsg').html(greet);
})


function checkMachineNameConfirmation(){
    if($("#kiosk_identifier").hasClass( "confirm" ))
    {
        if (confirm("CAUTION: Changing the machine name will cause the POS to fail and requires a manual update to the machine. Do you want to proceed?") == true) 
        {
           $("#kiosk_identifier").removeClass( "confirm" )
        }
    }
}

function checkPConfirmation(){
    if($("#password").hasClass( "confirm" ))
    {
        if (confirm("CAUTION: Changing the machine password will cause the POS to fail and requires a manual update to the machine. Do you want to proceed?") == true) 
        {
           $("#password").removeClass( "confirm" )
        }
    }
}
