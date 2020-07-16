
<script type="text/javascript" src="<?php echo assets_url_backmin('js'); ?>/plugins/bootstrap/bootstrap-select.js"></script>
<script type="text/javascript" src="<?php echo assets_url_backmin('js'); ?>/plugins/datatables/jquery.dataTables.min.js"></script>
<script type="text/javascript">
    var tableListRequestStock = {
        url: "<?php echo base_url().BACKMIN_PATH.'/scmrequeststock/listRequestStockMasuk'; ?>",
        columns: [
            { "data": "id_request" },
            { "data": "nama_gudang" },
            { "data": "total_jumlah" },
            { "data": "date_add" },
            { "data": "status_tag" },
            { "data": "status_intan" },
            { "data": "detail" },
        ],
        columnDefs: [{
            targets: 6,
            orderable: false,
        }],
        sort: [3,'desc']
    };
</script>
