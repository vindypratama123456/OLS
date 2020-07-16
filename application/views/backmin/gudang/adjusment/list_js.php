
<script type="text/javascript" src="<?php echo assets_url_backmin('js'); ?>/plugins/bootstrap/bootstrap-select.js"></script>
<script type="text/javascript" src="<?php echo assets_url_backmin('js'); ?>/plugins/datatables/jquery.dataTables.min.js"></script>
<script type="text/javascript" src="<?php echo assets_url_backmin('js'); ?>/plugins/tableexport/tableExport.js"></script>
<script type="text/javascript" src="<?php echo assets_url_backmin('js'); ?>/plugins/tableexport/jquery.base64.js"></script>
<script type="text/javascript" src="<?php echo assets_url_backmin('js'); ?>/plugins/tableexport/html2canvas.js"></script>
<script type="text/javascript" src="<?php echo assets_url_backmin('js'); ?>/plugins/tableexport/jspdf/libs/sprintf.js"></script>
<script type="text/javascript" src="<?php echo assets_url_backmin('js'); ?>/plugins/tableexport/jspdf/jspdf.js"></script>
<script type="text/javascript" src="<?php echo assets_url_backmin('js'); ?>/plugins/tableexport/jspdf/libs/base64.js"></script>  
<script type="text/javascript">
    var tableListAdjusment = {
        url: "<?php echo base_url().BACKMIN_PATH.'/gudangadjusment/list_data_adjusment'; ?>",
        columns: [
            { "data": "id_transaksi" },
            { "data": "catatan" },
            { "data": "total_jumlah" },
            { "data": "status_transaksi" },
            { "data": "tanggal" }
        ],
        columnDefs: [
            { className: "text-center", targets: [0, 2, 3, 4]}
        ],
        sort: [4,'desc']
    };
</script>
