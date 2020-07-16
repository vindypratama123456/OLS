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
	        datas['url'] = BASE_URL+'pengiriman_parsial/list_processed';
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
</script>