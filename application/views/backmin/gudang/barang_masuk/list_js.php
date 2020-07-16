
<script type="text/javascript" src="<?php echo assets_url_backmin('js'); ?>/plugins/bootstrap/bootstrap-select.js"></script>
<script type="text/javascript" src="<?php echo assets_url_backmin('js'); ?>/plugins/datatables/jquery.dataTables.min.js"></script>
<script type="text/javascript">
    var tableListBarangMasuk = {
        url: "<?php echo base_url().BACKMIN_PATH.'/gudangpermintaan/listBarangMasuk'; ?>",
        columns: [
            { "data": "id_transaksi" },
            { "data": "nama_gudang" },
            { "data": "total_jumlah" },
            { "data": "date_add" },
            { "data": "status_transaksi" },
            { "data": "detail" },
        ],
        columnDefs: [{
            targets: 5,
            orderable: false,
        }],
        sort: [3,'asc']
    };
</script>
