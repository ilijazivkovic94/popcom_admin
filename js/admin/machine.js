 $(document).on('click', '.status', function(e) {
    if(confirm("Are you sure you want to change the status?")){
       $("#page_loader").show();
      var token = $("input[name='_token']").val();
      $.ajax({
          url : base_url+"/admin/machine/update-status",
          type: 'POST',
          data : { '_token': token,
                  'id':$(this).data('id'),
                  'type': $(this).data('type'),
              },
          dataType: "json",
          success: function(data) {
             $("#page_loader").hide();
            if(data.title == 'Error'){
               toastr.error(data.message, data.title);
            }else{
              toastr.success(data.message, data.title);
              setTimeout(function(){ window.location.reload(); }, 1000);
            }
          }
      })
    }
});
$(document).ready(function () {
  $('#form').validate({
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
  if (document.getElementById("tbl_machine")) {
    var id = $("#uid").val();
    $('#tbl_machine').DataTable({ 
      	processing: true,
        serverSide: true,
        ajax: base_url+"/admin/machine/list/"+id,
        "columns": [
            {data: 'kiosk_serial_no'},
            {data: 'kiosk_identifier'},
            {data: 'kiosk_street'},
            {data: 'kiosk_city'},
            {data: 'kiosks_state'},
            {data: 'kiosk_country'},
            {data: 'kiosk_zip'},
            {data: 'pos_min_age'},
            {data: 'model_name'},
            {data: 'template_name'},
            {data: 'kiosk_status'},
            {data: 'action', name: 'action', orderable: false, searchable: false},
            {data: 'status', visible: false}
        ],
        initComplete: function () {
          // Apply the search
          this.api().columns(8).every( function () {
              var column = this;
              $('#machine_model').on( 'change', function () {
                  var val = $.fn.dataTable.util.escapeRegex($(this).val());
                  column
                      .search( val ? '^'+val+'$' : '', true, false )
                      .draw();
              });
          });
          this.api().columns(12).every( function () {
              var column = this;
              $('#status').on( 'change', function () {
                  var val = $.fn.dataTable.util.escapeRegex($(this).val());
                  column
                      .search( val ? '^'+val+'$' : '', true, false )
                      .draw();
              });
          });
        },
    });
  }
  $('#promo').select2();
  var currentProm = $('#promo').val();
  var currentPromText = $("#promo option:selected").text();
  var current_promo_id = $("#current_promo_id").val();
  if(currentProm != undefined && currentProm != ''){
    var opt = '';
    for(var p=0;currentProm.length > p; p++ ){
      var txt = $('#promo option[value="'+currentProm[p]+'"]').text();
      console.log(txt);
      opt += '<option  value="'+currentProm[p]+'">'+txt+'</option>';
    }
    if(opt != ""){
      $('#optin_promo_id').append(opt);
      $('#promo_id').removeClass('hidden');
      if(current_promo_id != ''){
        $('#optin_promo_id').val(current_promo_id);
      }
    }
  }
  $('#promo').on('select2:select', function (e) {
    console.log(e.params.data);
    var find = $('#optin_promo_id option[value="'+e.params.data.id+'"]');
    console.log(find.length);
    if(find.length === 0){
      var opt = '<option value="'+e.params.data.id+'">'+e.params.data.text+'</option>';
      $('#promo_id').removeClass('hidden');
      $('#optin_promo_id').append(opt);
    }
    // console.log(find.le);
  });
  $('#promo').on('select2:unselect', function (e) {
    console.log(e.params.data);
    var find = $('#optin_promo_id option[value="'+e.params.data.id+'"]');
    console.log(find);
    if(find.length !== 0){
      $('#optin_promo_id option[value="'+e.params.data.id+'"]').remove();
      /* var opt = '<option value="'+e.params.data.id+'">'+e.params.data.text+'</option>';
      $('#optin_promo_id').append(opt); */
    }
    // console.log(find.le);
  });
}); 