
jQuery(document).ready(function () {

    if (document.getElementById("tbl_account")) {
        var customerTable = jQuery('#tbl_account').DataTable({ 
            processing: true,
            serverSide: true,
            bAutoWidth: true,
            bLengthChange: true,
            order: [7, 'DESC'],
            ajax: {
                url: base_url+"/app/customer/list",
                data: function ( data ) {
                    data.kiosk_id = jQuery('#kiosk_id').val();
                    data.sub_account = jQuery('#sub_account').val();
                }      
            },
            "columns": [
                {data: 'customer_id', name: 'customer_id'},
                {data: 'customer_email', name: 'customer_email'},
                {data: 'customer_gender', name: 'customer_gender'},
                {data: 'journey_emotion_json', name: 'journey_emotion_json', orderable: false, searchable: false},
                {data: 'customer_age_group', name: 'customer_age_group'},
                {data: 'total_order', name: 'total_order'},
                {data: 'action', name: 'action', orderable: false, searchable: false},
                {data: 'max_order', visible: false},
            ],
        });
                    
        jQuery('#kiosk_id').on('change', function(){
            customerTable.draw();
        }); 
        jQuery('#sub_account').on('change', function(){
            customerTable.draw();
        }); 
    }
});