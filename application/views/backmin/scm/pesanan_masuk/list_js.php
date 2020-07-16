
<script type="text/javascript" src="<?php echo assets_url_backmin('js'); ?>/plugins/bootstrap/bootstrap-select.js"></script>
<script type="text/javascript" src="<?php echo assets_url_backmin('js'); ?>/plugins/datatables/jquery.dataTables.min.js"></script>
<script type="text/javascript">
    var tableListPesananMasuk = {
        url: "<?php echo base_url().BACKMIN_PATH.'/scmpesanan/listDataPesananMasuk'; ?>",
        columns: [
            { "data": "reference" },
            { "data": "school_name" },
            { "data": "class_name" },
            { "data": "type_name" },
            { "data": "provinsi" },
            { "data": "kabupaten" },
            { "data": "target_kirim" },
            { "data": "date_add" },
            { "data": "nama_gudang" }
        ],
        columnDefs: [
            { "className": "text-center", "targets": [0, 2, 3, 6, 7, 8]}
        ],
        sort: [7,'desc']
    };
</script>
