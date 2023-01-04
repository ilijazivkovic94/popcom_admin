 $(document).on('click', '.status', function(e) {
    if(confirm("Are you sure you want to change the status?")){
      // show loader
      $("#page_loader").show();
      var token = $("input[name='_token']").val();
      $.ajax({
          url : base_url+"/admin/account/update-status",
          type: 'POST',
          data : { '_token': token,
                  'id':$(this).data('id'),
                  'type': $(this).data('type'),
              },
          dataType: "json",
          success: function(data) {
             $("#page_loader").hide();
            toastr.success(data.message, data.title);
            setTimeout(function(){ window.location.reload(); }, 1000);
          }
      })
    }
});
function changeImage(input) {
    var reader;

    if (input.files && input.files[0]) {
        reader = new FileReader(); 
        reader.onload = function(e) {
            preview.setAttribute('src', e.target.result);
        }
        reader.readAsDataURL(input.files[0]);
    }
}
$(document).ready(function () {
  jQuery.validator.addMethod("validate_email", function(value, element) {
      if (/^([a-zA-Z0-9_\.\-])+\@(([a-zA-Z0-9\-])+\.)+([a-zA-Z0-9]{2,4})+$/.test(value)) {
          return true;
      } else {
          return false;
      }
  }, "Please enter valid email");
  $('#form_account').validate({
      rules: {
          email: {
              required: true,
              email: true,
              validate_email: true,
          },
      },
      messages: {
          email: {
              required: "Please enter email address",
              email: "Please enter valid email",
              validate_email: "Please enter valid email",
          },
      },
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
      }
  });

  if (document.getElementById("inputGroupFile01")) {
    var fileTag = document.getElementById("inputGroupFile01"),
    preview = document.getElementById("preview");
    
    fileTag.addEventListener("change", function() {
        changeImage(this);
    });
  }

  $("#bypass_subscription").click(function () {
      if ($(this).is(":checked")) {
          $("#plans").hide();
      } else {
          $("#plans").show();
      }
  });

  $("body").on('click','.bypass',function(e){
    e.preventDefault();
    alert('I’m sorry, but there is not a credit card on file for this account. Please contact the account user and ask them to add a valid credit card in Settings/Account Status first.')
  });

  var new_password = Math.random().toString(36).substring(2,10);
  // Ref: update account > when change status
  $("#user_account_status").change(function () {
      var val = this.value;
      var previous_pass = $("#password").val();
      if(val == 'Y'){
        if(previous_pass==''){
          $("#password").val(new_password);
        }
      }else{
        if(previous_pass == new_password){
          $('#password').val('');  
        }
      }
  });

  if (document.getElementById("tbl_account")) {
    $('#tbl_account').DataTable({ 
      	processing: true,
        serverSide: true,
        order: [0, 'DESC'],
        ajax: base_url+"/admin/account/list",
        "columns": [
            {data: 'account_id'},
            {data: 'account_details.account_name'},
            {data: 'email'},
            {data: 'account_type'},
            {data: 'parent'},
            {data: 'stripe_plan'}, 
            {data: 'created_at'},
            {data: 'user_active_yn'},
            {data: 'action', name: 'action', orderable: false, searchable: false},
            {data: 'status', visible: false}
        ],
        initComplete: function () {
          // Apply the search
          this.api().columns(3).every( function () {
              var column = this;
              $('#account').on( 'change', function () {
                  var val = $.fn.dataTable.util.escapeRegex($(this).val());
                  column
                      .search( val ? '^'+val+'$' : '', true, false )
                      .draw();
              });
          });
          this.api().columns(9).every( function () {
              var column = this;
              $('#status').on( 'change', function () {
                  var val = $.fn.dataTable.util.escapeRegex($(this).val());
                  column
                      .search( val ? '^'+val+'$' : '', true, false )
                      .draw();
              });
          });

           this.api().columns(5).every( function () {
              var column = this;
              $('#plan').on( 'change', function () {
                  var val = $.fn.dataTable.util.escapeRegex($(this).val());
                  column
                      .search( val ? '^'+val+'$' : '', true, false )
                      .draw();
              });
          });
      },
    });
  }
}); 