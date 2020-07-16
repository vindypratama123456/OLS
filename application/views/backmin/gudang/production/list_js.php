
<script type="text/javascript" src="<?php echo assets_url_backmin('js'); ?>/plugins/bootstrap/bootstrap-select.js"></script>
<script type="text/javascript" src="<?php echo assets_url_backmin('js'); ?>/plugins/datatables/jquery.dataTables.min.js"></script>
<script type="text/javascript">
    var tableListRequestStock = {
        url: "<?php echo base_url().BACKMIN_PATH.'/gudangproduction/listOrder'; ?>",
        columns: [
            { "data": "no_oef" },
            { "data": "kode_buku" },
            { "data": "judul" },
            { "data": "jumlah_request" },
            { "data": "jumlah_kirim" },
            { "data": "catatan_alokasi" },
            { "data": "stat_production" },
            { "data": "created_date" },
        ],
        columnDefs: [{
            targets: 5,
            orderable: false,
        }],
        sort: [7,'desc']
    };
</script>
