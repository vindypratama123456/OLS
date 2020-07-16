$(document).ready(function(){
    var datas = [];
    var elFilterData = $('#reset-filter-date');
    var elStartData = $('#start_date');
    if($('#datatablePiutang').length>0){
        datas['selector'] = 'datatablePiutang';
        datas['url'] = BASE_URL+'finance/list_orders';
        datas['columns'] = [
            { 'data': 'reference' },
            { 'data': 'school_name' },
            { 'data': 'category' },
            { 'data': 'type' },
            { 'data': 'date_add' },
            { 'data': 'order_state' },
            { 'data': 'total_paid', render: $.fn.dataTable.render.number( ',', '.', 0 ) },
            { 'data': 'nilai_dibayar', render: $.fn.dataTable.render.number( ',', '.', 0 ) },
            { 'data': 'nilai_piutang', render: $.fn.dataTable.render.number( ',', '.', 0 ) },
            { 'data': 'korwil_name' },
            { 'data': 'phone' },
            { 'data': 'operator' },
            { 'data': 'hp_operator' },
            { 'data': 'name' },
            { 'data': 'phone_kepsek' },
            { 'data': 'nama_mitra' },
            { 'data': 'nama_rsm' },
            { 'data': 'hasil_konfirmasi' },
            { 'data': 'tanggal_konfirmasi' }
        ];
        datas['columnDefs'] = [
            { className: 'text-center', targets: [0, 2, 3, 4, 5] },
            { className: 'text-right', targets: [6, 7, 8] }
        ];
        datas['sort'] = [4,'asc'];
        function fetchDatatablePiutang(a, b) {
            setTimeout(function(){
                tableAllOrder = myDatatables(datas, a, b);
                commonTools(datas['selector'], tableAllOrder);
            }, 10);
        }
        function resetDatatablePiutang() {
            $('input[type="text"], .dataTables_filter input[type="search"]').val('');
            tableAllOrder.state.clear();
            tableAllOrder.clear().destroy();
            fetchDatatablePiutang();
        }
        fetchDatatablePiutang();
        $('#search').click(function(){
            var start_date = elStartData.val();
            var end_date = $('#end_date').val();
            if(start_date !== '' && end_date !=='') {
                tableAllOrder.state.clear();
                tableAllOrder.clear().destroy();
                fetchDatatablePiutang(start_date, end_date);
            } else {
                elStartData.focus();
            }
        });
        elFilterData.on('click', function(){
            resetDatatablePiutang();
        });
    }
    if($('#datatableLunas').length>0){
        datas['selector'] = 'datatableLunas';
        datas['url'] = BASE_URL+'finance/list_orders_complete';
        datas['columns'] = [
            { 'data': 'reference' },
            { 'data': 'school_name' },
            { 'data': 'category' },
            { 'data': 'provinsi' },
            { 'data': 'kabupaten' },
            { 'data': 'korwil_name' },
            { 'data': 'name' },
            { 'data': 'phone' },
            { 'data': 'phone_kepsek' },
            { 'data': 'total_paid', render: $.fn.dataTable.render.number( ',', '.', 0 ) },
            { 'data': 'nilai_dibayar', render: $.fn.dataTable.render.number( ',', '.', 0 ) },
            { 'data': 'date_add' },
            { 'data': 'tgl_lunas' }
        ];
        datas['columnDefs'] = [
            { className: "text-center", targets: [0, 2, 10, 11, 12] },
            { className: "text-right", targets: [9, 10] }
        ];
        datas['sort'] = [12,'desc'];
        setTimeout(function(){
            datatableLunas = myDatatables(datas);
            commonTools(datas['selector'], datatableLunas);
        }, 10);
    }
    if($('#datatableAllInput').length>0){
        datas['selector'] = 'datatableAllInput';
        datas['url'] = BASE_URL+'finance/list_orders_all_input';
        datas['columns'] = [
            { 'data': 'tanggal_sistem' },
            { 'data': 'tanggal_bayar' },
            { 'data': 'jumlah_bayar', render: $.fn.dataTable.render.number( ',', '.', 0 ) },
            { 'data': 'catatan' },
            { 'data': 'no_pesanan' },
            { 'data': 'tgl_pesan' },
            { 'data': 'npsn' },
            { 'data': 'nama_sekolah' },
            { 'data': 'category' },
            { 'data': 'type'  },
            { 'data': 'jenis_pesanan' },
            { 'data': 'order_state' }
        ];
        datas['columnDefs'] = [
            { className: "text-center", targets: [0, 1, 4, 5, 6, 8, 9, 10, 11] },
            { className: "text-right", targets: [2] }
        ];
        datas['sort'] = [0,'desc'];
        setTimeout(function(){
            datatableAllInput = myDatatables(datas);
            commonTools(datas['selector'], datatableAllInput);
        }, 10);
    }
    if($('#datatableAllOrder').length>0){
        datas['selector'] = 'datatableAllOrder';
        datas['url'] = BASE_URL+'finance/list_orders_all';
        datas['columns'] = [
            { 'data': 'reference' },
            { 'data': 'school_name' },
            { 'data': 'provinsi' },
            { 'data': 'kabupaten' },
            { 'data': 'category' },
            { 'data': 'type' },
            { 'data': 'date_add' },
            { 'data': 'order_state' },
            { 'data': 'total_paid', render: $.fn.dataTable.render.number( ',', '.', 0 ) },
            { 'data': 'nilai_dibayar', render: $.fn.dataTable.render.number( ',', '.', 0 ) },
            { 'data': 'nilai_piutang', render: $.fn.dataTable.render.number( ',', '.', 0 ) },
            { 'data': 'mitra_name'},
            { 'data': 'phone' },
            { 'data': 'operator' },
            { 'data': 'hp_operator' },
            { 'data': 'name' },
            { 'data': 'phone_kepsek' }
        ];
        datas['columnDefs'] = [
            { className: 'text-center', targets: [0, 4, 5, 6, 7] },
            { className: 'text-right', targets: [8, 9, 10] }
        ];
        datas['sort'] = [6,'asc'];
        function fetchDatatableAllOrder(a, b) {
            setTimeout(function(){
                tableAllOrder = myDatatables(datas, a, b);
                commonTools(datas['selector'], tableAllOrder);
            }, 10);
        }
        function resetDatatableAllOrder() {
            $('input[type="text"], .dataTables_filter input[type="search"]').val('');
            tableAllOrder.state.clear();
            tableAllOrder.clear().destroy();
            fetchDatatableAllOrder();
        }
        fetchDatatableAllOrder();
        $('#search').click(function(){
            var start_date = elStartData.val();
            var end_date = $('#end_date').val();
            if(start_date !== '' && end_date !=='') {
                tableAllOrder.state.clear();
                tableAllOrder.clear().destroy();
                fetchDatatableAllOrder(start_date, end_date);
            } else {
                elStartData.focus();
            }
        });
        elFilterData.on('click', function(){
            resetDatatableAllOrder();
        });
    }

    $(".btnDelete").on("click", function(){
        var id = $(this).val();
        // alert(id);
        // var confirm = confirm("Yakin akan menghapus data pembayaran ?");
        if(confirm("Yakin akan menghapus data pembayaran ?"))
        {
            var csrfName = '<?php echo $this->security->get_csrf_token_name(); ?>',
                csrfHash = '<?php echo $this->security->get_csrf_hash(); ?>';
            var data = {
                    id : id
                    ,csrfName : csrfHash
                };
            $.ajax({
                url: BASE_URL + 'finance/amountDelete',
                type: "POST",
                data: data,
                dataType : "json",
                success : function(data){
                    // get_csrf_hash = data['csrftokenbs'];
                    // console.log(data);
                    location.reload();
                }
            });
        }
    });
});