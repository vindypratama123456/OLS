
<script type="text/javascript" src="<?php echo assets_url_backmin('js'); ?>/plugins/bootstrap/bootstrap-select.js"></script>
<script type="text/javascript" src="<?php echo assets_url_backmin('js'); ?>/plugins/datatables/jquery.dataTables.min.js"></script>
<script type="text/javascript" src="<?php echo assets_url_backmin('js'); ?>/plugins/tableexport/tableExport.js"></script>
<script type="text/javascript" src="<?php echo assets_url_backmin('js'); ?>/plugins/tableexport/jquery.base64.js"></script>
<script type="text/javascript" src="<?php echo assets_url_backmin('js'); ?>/plugins/tableexport/html2canvas.js"></script>
<script type="text/javascript" src="<?php echo assets_url_backmin('js'); ?>/plugins/tableexport/jspdf/libs/sprintf.js"></script>
<script type="text/javascript" src="<?php echo assets_url_backmin('js'); ?>/plugins/tableexport/jspdf/jspdf.js"></script>
<script type="text/javascript" src="<?php echo assets_url_backmin('js'); ?>/plugins/tableexport/jspdf/libs/base64.js"></script>  
<script type="text/javascript">
    var tableListPesananDiproses = {
        url: "<?php echo base_url().BACKMIN_PATH.'/scmpesanan/listDataPesananDiproses'; ?>",
        columns: [
            { "data": "reference" },
            { "data": "school_name" },
            { "data": "class_name" },
            { "data": "type_name" },
            { "data": "provinsi" },
            { "data": "kabupaten" },
            { "data": "target_kirim" },
            { "data": "date_add" },
            { "data": "nama_gudang" },
            { "data": "is_forward" },
            { "data": "status_transaksi" }
        ],
        columnDefs: [
            { "className": "text-center", "targets": [0, 2, 3, 6, 7, 8, 9, 10]}
        ],
        sort: [7,'desc']
    };
</script>
