function changeImage(input) {
    $('#preview').show();
    var reader;

    if (input.files && input.files[0]) {
        reader = new FileReader();
        reader.onload = function(e) {
            preview.setAttribute('src', e.target.result);
        }
        reader.readAsDataURL(input.files[0]);
        // document.getElementById("fileFlag").value = 1;
    }
}

jQuery('.receipt_custom_text_1').summernote({
    height: 200,
    dialogsInBody: true,
    toolbar: [
        ['style', ['bold', 'italic', 'underline', 'clear']],
        ['font', ['strikethrough', 'superscript', 'subscript']],
        ['color', ['color']],
        ['para', ['ul', 'ol', 'paragraph']],
        ['fontsize', ['fontsize']],
        ['fontname', ['fontname']],
        ['height', ['height']],
        ['insert', ['link']],
    ],
    callbacks: {
        onChange: function(contents, $editable) {
            jQuery("#custom_text_1_para").html(contents);
        }
    }
}); 

jQuery('.receipt_custom_text_2').summernote({
    height: 200,
    dialogsInBody: true,
    toolbar: [
        ['style', ['bold', 'italic', 'underline', 'clear']],
        ['font', ['strikethrough', 'superscript', 'subscript']],
        ['color', ['color']],
        ['para', ['ul', 'ol', 'paragraph']],
        ['fontsize', ['fontsize']],
        ['fontname', ['fontname']],
        ['height', ['height']],
        ['insert', ['link']],
    ],
    callbacks: {
        onChange: function(contents, $editable) {
            jQuery("#custom_text_2_para").html(contents);
        }
    }
}); 

jQuery(document).ready(function () {

    jQuery.validator.addMethod("valueNotEqualsAbout", function () {
        var text = $("#receipt_custom_text_1").summernote("code").replace(/&nbsp;|<\/?[^>]+(>|$)/g, "").trim();
        if (text.length == 0) {
            return false;
        } else {
            return true;
        }
    }, 'Please enter receipt text.');

    jQuery.validator.addMethod("valueNotEqualsAbout2", function () {
        var text = $("#receipt_custom_text_2").summernote("code").replace(/&nbsp;|<\/?[^>]+(>|$)/g, "").trim();
        if (text.length == 0) {
            return false;
        } else {
            return true;
        }
    }, 'Please enter receipt text.');

    jQuery.validator.addMethod('wyzerr', function (value, element) {
        if(value.replace(/\s/g, '').length > 1){
            var result = value.search(new RegExp('wyzerr', "i"));
            if (result > 0)
                return true;
            else
                return false;  
        }else{
            return true;  
        }
    }, "Please enter a valid Wyzerr survey URL");

    if (document.getElementById("form_account")) {

        var fileTag = document.getElementById("inputGroupFile01"),
        preview = document.getElementById("preview");
        
        fileTag.addEventListener("change", function() {
            changeImage(this);
        });

        $.fn.extend({
            toggleAttr: function (attr, turnOn) {
                var justToggle = (turnOn === undefined);
                return this.each(function () {
                    if ((justToggle && !$(this).is("[" + attr + "]")) ||
                        (!justToggle && turnOn)) {
                        $(this).attr(attr, attr);
                    } else {
                        $(this).removeAttr(attr);
                    }
                });
            }
        });

        jQuery(".edit_sender_email").click(function(){
            jQuery("#account_contact_email").toggleAttr("disabled");
        }); 

        $('#form_account').validate({
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
            rules: {
                account_survey_url: {
                    required: function(element) { 
                        if($('#account_survey_url').val().replace(/\s/g, '').length > 1){
                            return true;
                        }else{
                            return false;
                        }                      
                    },
                    wyzerr: true,
                },
                account_contact_email: {
                    email: true,
                    require_from_group: [1, '.mygroup'],
                },
                account_contact_phone: {
                    minlength: 10,
                    maxlength: 10,
                    require_from_group: [1, '.mygroup'],
                }
            },
            messages: {
                account_survey_url: {
                    wyzerr: "Please enter a valid Wyzerr survey URL.",
                },
            },
        });

        jQuery("#userFact").on("change",function(){
            if($(this).is(":checked")){
                jQuery("#FactModal").modal({backdrop: false, keyboard: false});
                jQuery("#FactModal #step2").hide();
                jQuery("#FactModal #check_otp").hide();
                jQuery("#FactModal").modal('show');
            }
        });

        jQuery('#FactModal .closeFact').click(function(){
            jQuery('#userFact').bootstrapToggle('off');
            jQuery("#userFact").prop("checked", false);
            jQuery("#FactModal").modal('hide');
        });

        jQuery("#update_number").click(function(){
            var token               = jQuery("input[name='_token']").val();
            var user_phone_no       = jQuery("#user_phone_no").val();
            var current_password    = jQuery("#current_password").val();
            var country_code        = jQuery("#country_code").val();
            
            if(user_phone_no == ''){      
                jQuery('#user_phone_no').focus();
                jQuery('#user_phone_no').addClass('is-invalid');                    
                toastr.error('Please enter your phone number.');
                return false;
            }else if(current_password == ''){
                jQuery('#current_password').focus();
                jQuery('#current_password').addClass('is-invalid');                    
                toastr.error('Please enter password.');
                return false;
            }
            
            $.ajax({
                url : base_url+"/app/setting/sendOTP",
                type: 'POST',
                data: { 
                    '_token'            : token,
                    'user_phone_no'     : user_phone_no,
                    'current_password'  : current_password,
                    'country_code'      : country_code,
                },
                dataType: "JSON",
                success: function(response) {
                    if(response.status == true){
                        jQuery("#update_number").hide();
                        jQuery("#step1").hide();
                        
                        jQuery("#FactModal #step2").show();
                        jQuery("#FactModal #check_otp").show();

                        toastr.success(response.message, 'Setting');
                        return false;
                    }else{
                        toastr.error(response.message);
                        return false;
                    }                
                }
            });
        });
        
        jQuery("#check_otp").click(function(){
            var token       = jQuery("input[name='_token']").val();
            var user_otp    = jQuery("#user_otp").val();
            
            if(user_otp == ''){            
                jQuery('#user_otp').focus();
                jQuery('#user_otp').addClass('is-invalid');                    
                toastr.error('Please enter OTP.');
                return false;
            }else if(user_otp.length != 6){
                jQuery('#user_otp').focus();
                jQuery('#user_otp').addClass('is-invalid');                    
                toastr.error('Please enter 6 digit OTP.');
                return false;
            }

            $.ajax({
                url : base_url+"/app/setting/checkOTP",
                type: 'POST',
                data: { 
                    '_token'    : token,
                    'user_otp'  : user_otp,
                },
                dataType: "JSON",
                success: function(response) {
                    if(response.status == true){
                        toastr.success(response.message, 'Setting');
                        location.reload(true);
                    }else{
                        toastr.error(response.message);
                        return false;
                    }                
                }
            });
        });
    }

    if (document.getElementById("receipt_form")) {
        $.fn.extend({
            toggleAttr: function (attr, turnOn) {
                var justToggle = (turnOn === undefined);
                return this.each(function () {
                    if ((justToggle && !$(this).is("[" + attr + "]")) ||
                        (!justToggle && turnOn)) {
                        $(this).attr(attr, attr);
                    } else {
                        $(this).removeAttr(attr);
                    }
                });
            }
        });

        jQuery(".edit_sender_email").click(function(){
            jQuery("#receipt_sender_email").toggleAttr("disabled");
        }); 

        jQuery(document).on('click', '#includeSurveyUrl',function(){
            if(jQuery("#includeSurveyUrl").prop("checked")) {
                jQuery(".receipt_survey_url").show();
                jQuery("#include_survey_url").val('Y');
                jQuery("#receipt_survey_url").prop('required', true);
            } else {
                jQuery(".receipt_survey_url").hide();
                jQuery("#include_survey_url").val('N');
                jQuery("#receipt_survey_url").prop('required', false);
            }
        });

        jQuery(".sendMail").click(function(){
            jQuery("#sendMail").val("1");
        }); 

        $('#receipt_form').validate({
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
            ignore: ".note-editor *",
            rules: {
                receipt_custom_text_1: {
                    required: true,
                    valueNotEqualsAbout: true,
                },
                receipt_custom_text_2: {
                    required: true,
                    valueNotEqualsAbout2: true,
                },
                receipt_survey_url: {
                    required: function(element) { 
                        if($('#receipt_survey_url').val().replace(/\s/g, '').length > 1){
                            return true;
                        }else{
                            return false;
                        }                      
                    },
                    wyzerr: true,
                },
            },
            messages: {
                receipt_custom_text_1: {
                    required: "Please enter receipt text.",
                    valueNotEqualsAbout: "Please enter receipt text.",
                },
                receipt_custom_text_2: {
                    required: "Please enter receipt text.",
                    valueNotEqualsAbout2: "Please enter receipt text.",
                },
                receipt_survey_url: {
                    wyzerr: "Please enter a valid Wyzerr survey URL.",
                },
            },  
            submitHandler: function (form) {
                jQuery('#receipt_sender_email').removeAttr('disabled');
                form.submit();
            }          
        });
    }

});