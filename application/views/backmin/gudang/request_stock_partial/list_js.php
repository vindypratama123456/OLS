
<script type="text/javascript" src="<?php echo assets_url_backmin('js'); ?>/plugins/bootstrap/bootstrap-select.js"></script>
<script type="text/javascript" src="<?php echo assets_url_backmin('js'); ?>/plugins/datatables/jquery.dataTables.min.js"></script>
<script type="text/javascript">
    var tableListRequestStock = {
        url: "<?php echo base_url().BACKMIN_PATH.'/gudangrequeststockpartial/listRequestStock'; ?>",
        columns: [
            { "data": "id_request" },
            { "data": "total_jumlah" },
            { "data": "date_add" },
            // { "data": "status_tag" },
            { "data": "status" },
            { "data": "detail" },
        ],
        columnDefs: [{
            targets: 4,
            orderable: false,
        }],
        sort: [2,'desc']
    };
</script>
