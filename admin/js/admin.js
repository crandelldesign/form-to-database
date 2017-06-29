jQuery(document).ready(function ($) {
    var table_name = $('.ftd-data-table').data('name');
    $('.ftd-data-table').DataTable( {
        dom: 'Bfrtip',
        buttons: [
            'copyHtml5',
            {
                extend: 'excelHtml5',
                title: table_name
            },
            {
                extend: 'csvHtml5',
                filename: table_name
            },
            {
                extend: 'pdfHtml5',
                title: table_name
            }
        ]
    } );
});
