<script type="text/javascript" src="<?php echo assets_url_backmin('js'); ?>/plugins/bootstrap/bootstrap-select.js"></script>
<script type="text/javascript" src="<?php echo assets_url_backmin('js'); ?>/plugins/datatables/jquery.dataTables.min.js"></script>
<script type="text/javascript" src="<?php echo assets_url_backmin('js'); ?>/plugins/tableexport/tableExport.js"></script>
<script type="text/javascript" src="<?php echo assets_url_backmin('js'); ?>/plugins/tableexport/jquery.base64.js"></script>
<script type="text/javascript" src="<?php echo assets_url_backmin('js'); ?>/plugins/tableexport/html2canvas.js"></script>
<script type="text/javascript" src="<?php echo assets_url_backmin('js'); ?>/plugins/tableexport/jspdf/libs/sprintf.js"></script>
<script type="text/javascript" src="<?php echo assets_url_backmin('js'); ?>/plugins/tableexport/jspdf/jspdf.js"></script>
<script type="text/javascript" src="<?php echo assets_url_backmin('js'); ?>/plugins/tableexport/jspdf/libs/base64.js"></script>  
<script type="text/javascript">
    // var tableListRequestStock = {
    //     url: "<?php echo base_url().BACKMIN_PATH.'/scmrequeststockpartial/listRequestStockDiproses'; ?>",
    //     columns: [
    //         { "data": "id_request" },
    //         // { "data": "gudang_pengirim" },
    //         { "data": "nama_gudang" },
    //         { "data": "total_jumlah" },
    //         { "data": "sisa_jumlah" },
    //         { "data": "date_created" },
    //         // { "data": "status_tag" },
    //         { "data": "status_intan" },
    //         { "data": "status_transaksi" },
    //         { "data": "detail" },
    //     ],
    //     columnDefs: [{
    //         targets: 7,
    //         orderable: false,
    //     }],
    //     sort: [4,'desc']
    // };

    $(document).ready(function() {
    var table = $('#tableListRequestStock').DataTable({
        "ajax": "<?php echo base_url().BACKMIN_PATH.'/scmrequeststockpartial/listRequestStockDiproses'; ?>",
        "columns": [
            { "data": "id_request" },
            // { "data": "gudang_pengirim" },
            { "data": "nama_gudang" },
            { "data": "total_jumlah" },
            { "data": "sisa_jumlah" },
            { "data": "date_created" },
            // { "data": "status_tag" },
            { "data": "status_intan" },
            { "data": "status_transaksi" },
            { "data": "detail" },
        ],
        "columnDefs": [{
            "targets": 7,
            "orderable": false,
        }],
        "order": [4,'desc'],
        "deferRender": true,
        "dom": 'Blfrtip',
        "lengthMenu": [[25, 100, 500, -1], [25, 100, 500, "All"]],
        "pagingType": "full",
        "processing": true,
        "responsive": true,
    });

    // $('#tableListRequestStock_filter input').unbind();
    // $('#tableListRequestStock_filter input').keyup(function (e) {
    //     if (e.keyCode == 13) /* if enter is pressed */ {
    //         table.search($(this).val()).draw();
    //     }
    // });
} );

</script>
