<script src="<?php echo js_url('jquery-validation/jquery.validate.min.js?v='.date('YmdHis')); ?>"></script>
<script src="<?php echo js_url('jquery-validation/localization/messages_id.js?v='.date('YmdHis')); ?>"></script>
<script src="<?php echo js_url('admin/common.js?v='.date('YmdHis')); ?>"></script>
<script src="<?php echo js_url('admin/moment.js?v='.date('YmdHis')); ?>"></script>
<script src="<?php echo js_url('admin/collapse.js?v='.date('YmdHis')); ?>"></script>
<script src="<?php echo js_url('admin/transition.js?v='.date('YmdHis')); ?>"></script>
<script src="<?php echo js_url('admin/bootstrap-datetimepicker.min.js?v='.date('YmdHis')); ?>"></script>
<script>
	$(document).ready(function(){
		var datas = [];
	    if($('#datatableOnline').length>0){
	        datas['selector'] = 'datatableOnline';
	        datas['url'] = BASE_URL+'pengiriman_parsial/list';
	        datas['columns'] = [
	            { 'data': 'kode' },
	            { 'data': 'nama_sekolah' },
	            { 'data': 'propinsi' },
	            { 'data': 'kabupaten' },
	            { 'data': 'kecamatan'  },
	            { 'data': 'kelas' },
	            { 'data': 'tipe'  },
	            { 'data': 'semester'  },
	            { 'data': 'tgl_pesan' },
	            { 'data': 'total_harga', render: $.fn.dataTable.render.number( ',', '.', 0 ) },
	            { 'data': 'status' },
	            { 'data': 'mitra' }
	        ];
	        datas['columnDefs'] = [
	            { className: "text-center", targets: [0, 4, 5, 6, 8] },
	            { className: "text-right", targets: 7 }
	        ];
	        datas['sort'] = [7, 'desc'];
	        datatableOnline = myDatatables(datas);
	        commonTools(datas['selector'], datatableOnline);
	    }
	    if($('#datatableOffline').length>0){
	        datas['selector'] = 'datatableOffline';
	        datas['url'] = BASE_URL+'orders/list_orders/2';
	        datas['columns'] = [
	            { 'data': 'kode' },
	            { 'data': 'nama_sekolah' },
	            { 'data': 'propinsi' },
	            { 'data': 'kabupaten' },
	            { 'data': 'kelas' },
	            { 'data': 'tipe'  },
	            { 'data': 'tgl_pesan' },
	            { 'data': 'total_harga', render: $.fn.dataTable.render.number( ',', '.', 0 ) },
	            { 'data': 'status' }
	        ];
	        datas['columnDefs'] = [
	            { className: "text-center", targets: [0, 4, 5, 6, 8] },
	            { className: "text-right", targets: 7 }
	        ];
	        datas['sort'] = [6, 'desc'];
	        datatableOffline = myDatatables(datas);
	        commonTools(datas['selector'], datatableOffline);
	    }
	});

    $("#btn_konfirmasi").on("click",function(){
        var conf = confirm('Yakin ingin mengkonfirmasi request pengiriman pesanan secara parsial ?');
        if(conf) {
            var panel = $(".page-content-wrap");
            var uri = $("#frmKonfirmasiPengirimanParsial").data('uri_konfirmasi');
            $.ajax({
                type: "POST",
                data: $("#frmKonfirmasiPengirimanParsial").serialize(),
                dataType: "json",
                url: BASE_URL+uri,
                beforeSend: function(){
                    // loading_button("btn_konfirmasi");
                    // panel_refresh(panel,"shown");
                },
                success: function(e){
                    // setTimeout(function(){
                        // panel_refresh(panel,"hidden");
                        if(e.success=="true") {
                            window.location.href = BASE_URL+e.redirect;
                        }
                        else 
                        {
                            reset_button("btn_konfirmasi","P r o s e s");
                            window.location.href = BASE_URL+e.redirect;
                        }
                    // },500);
                }
            });
            return false;
        }
        else
        {
            return false;
        }
    });

    $("#btn_tolak").on("click",function(){
        var conf = confirm('Yakin ingin menolak request pengiriman pesanan secara parsial ?');
        if(conf) {
            var panel = $(".page-content-wrap");
            var uri = $("#frmKonfirmasiPengirimanParsial").data('uri_tolak');
            $.ajax({
                type: "POST",
                data: $("#frmKonfirmasiPengirimanParsial").serialize(),
                dataType: "json",
                url: BASE_URL+uri,
                beforeSend: function(){
                    // loading_button("btn_tolak");
                    // panel_refresh(panel,"shown");
                },
                success: function(e){
                    // setTimeout(function(){
                    //     panel_refresh(panel,"hidden");
                        if(e.success=="true") {
                            window.location.href = BASE_URL+e.redirect;
                        }
                        else 
                        {
                            reset_button("btn_tolak","P r o s e s");
                            window.location.href = BASE_URL+e.redirect;
                        }
                    // },500);
                }
            });
            return false;
        }
        else
        {
            return false;
        }
    });
</script>