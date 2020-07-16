

<script type="text/javascript" src="<?php echo assets_url_backmin('js'); ?>/plugins/bootstrap/bootstrap-select.js"></script>
<!-- <script type="text/javascript" src="<?php echo assets_url_backmin('js'); ?>/plugins/datatables/jquery.dataTables.min.js"></script> -->
<script src="<?php echo js_url('admin/plugins/dataTables-1.10.11/media/js/jquery.dataTables.min.js?v='.date('YmdHis'));?>"></script>
<script type="text/javascript">
    // var tableListRequestStock = {
    //     url: "<?php echo base_url().BACKMIN_PATH.'/scmrequeststockpartial/listRequestStockMasuk'; ?>",
    //     columns: [
    //         { "data": "id_request" },
    //         { "data": "nama_gudang" },
    //         { "data": "total_jumlah" },
    //         { "data": "sisa_jumlah" },
    //         { "data": "date_created" },
    //         // { "data": "status_tag" },
    //         { "data": "status_intan" },
    //         { "data": "status_transaksi" },
    //         { "data": "detail" },
    //     ],
    //     columnDefs: [
    //         {
    //             targets: 7,
    //             orderable: false,
    //         },
    //         { 
    //             className : "text-right", 
    //             targets: [2, 3]
    //         }
    //     ],
    //     // columnDefs: [
    //     //     { "className": "text-right", "targets": [2, 3]}
    //     // ],
    //     sort: [4,'desc']
    // };

    $(document).ready(function(){
        var table = $('#tableListRequestStock').DataTable({
            "ajax": "<?php echo base_url().BACKMIN_PATH.'/scmrequeststockpartial/listRequestStockMasuk'; ?>",
            "columns": [
                { "data": "id_request" },
                { "data": "nama_gudang" },
                { "data": "total_jumlah" },
                { "data": "sisa_jumlah" },
                { "data": "date_created" },
                // { "data": "status_tag" },
                { "data": "status_intan" },
                { "data": "status_transaksi" },
                { "data": "detail" },
            ],
            "columnDefs": [
                {
                    "targets": 7,
                    "orderable": false,
                },
                { 
                    "className": "text-right", 
                    "targets": [2, 3]
                }
            ],
            "order": [4,"desc"],
            // "decimal": ",",
            "deferRender": true,
            "dom": 'Blfrtip',
            "lengthMenu": [[25, 100, 500, -1], [25, 100, 500, "All"]],
            "pagingType": "full",
            "processing": true,
            "responsive": true,
            // "serverSide": true,
            // "stateSave": true,
            // "thousands": '.'
        });
    });
</script>
