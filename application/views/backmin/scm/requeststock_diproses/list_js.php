
<script type="text/javascript" src="<?php echo assets_url_backmin('js'); ?>/plugins/bootstrap/bootstrap-select.js"></script>
<script type="text/javascript" src="<?php echo assets_url_backmin('js'); ?>/plugins/datatables/jquery.dataTables.min.js"></script>
<script type="text/javascript" src="<?php echo assets_url_backmin('js'); ?>/plugins/tableexport/tableExport.js"></script>
<script type="text/javascript" src="<?php echo assets_url_backmin('js'); ?>/plugins/tableexport/jquery.base64.js"></script>
<script type="text/javascript" src="<?php echo assets_url_backmin('js'); ?>/plugins/tableexport/html2canvas.js"></script>
<script type="text/javascript" src="<?php echo assets_url_backmin('js'); ?>/plugins/tableexport/jspdf/libs/sprintf.js"></script>
<script type="text/javascript" src="<?php echo assets_url_backmin('js'); ?>/plugins/tableexport/jspdf/jspdf.js"></script>
<script type="text/javascript" src="<?php echo assets_url_backmin('js'); ?>/plugins/tableexport/jspdf/libs/base64.js"></script>  
<script type="text/javascript">
    var tableListRequestStock = {
        url: "<?php echo base_url().BACKMIN_PATH.'/scmrequeststock/listRequestStockDiproses'; ?>",
        columns: [
            { "data": "id_request" },
            { "data": "gudang_pengirim" },
            { "data": "nama_gudang" },
            { "data": "total_jumlah" },
            { "data": "date_add" },
            { "data": "status_tag" },
            { "data": "status_intan" },
            { "data": "status" },
            { "data": "detail" },
        ],
        columnDefs: [{
            targets: 8,
            orderable: false,
        }],
        sort: [4,'desc']
    };
</script>
