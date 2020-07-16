
<script type="text/javascript" src="<?php echo assets_url_backmin('js'); ?>/plugins/bootstrap/bootstrap-select.js"></script>
<script type="text/javascript" src="<?php echo assets_url_backmin('js'); ?>/plugins/datatables/jquery.dataTables.min.js"></script>
<script type="text/javascript" src="<?php echo assets_url_backmin('js'); ?>/plugins/tableexport/tableExport.js"></script>
<script type="text/javascript" src="<?php echo assets_url_backmin('js'); ?>/plugins/tableexport/jquery.base64.js"></script>
<script type="text/javascript" src="<?php echo assets_url_backmin('js'); ?>/plugins/tableexport/html2canvas.js"></script>
<script type="text/javascript" src="<?php echo assets_url_backmin('js'); ?>/plugins/tableexport/jspdf/libs/sprintf.js"></script>
<script type="text/javascript" src="<?php echo assets_url_backmin('js'); ?>/plugins/tableexport/jspdf/jspdf.js"></script>
<script type="text/javascript" src="<?php echo assets_url_backmin('js'); ?>/plugins/tableexport/jspdf/libs/base64.js"></script>  
<script type="text/javascript">
    var tableListDaftarPengiriman = {
        url: "<?php echo base_url().BACKMIN_PATH.'/gudangpengiriman/listPengiriman'; ?>",
        columns: [
            { "data": "kode_spk" },
            { "data": "nama_ekspedisi" },
            { "data": "tujuan" },
            { "data": "total_jumlah" },
            { "data": "total_berat" },
            { "data": "date_add" },
            { "data": "status" },
            { "data": "detail" },
        ],
        columnDefs: [{
            targets: 7,
            orderable: false,
        }],
        sort: [5,'desc']
    };
</script>
