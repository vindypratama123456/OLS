<script src="<?php echo js_url('jquery-validation/jquery.validate.min.js?v='.date('YmdHis')); ?>"></script>
<script src="<?php echo js_url('jquery-validation/localization/messages_id.js?v='.date('YmdHis')); ?>"></script>
<script src="<?php echo js_url('admin/common.js?v='.date('YmdHis')); ?>"></script>
<script src="<?php echo js_url('admin/moment.js?v='.date('YmdHis')); ?>"></script>
<script src="<?php echo js_url('admin/collapse.js?v='.date('YmdHis')); ?>"></script>
<script src="<?php echo js_url('admin/transition.js?v='.date('YmdHis')); ?>"></script>
<script src="<?php echo js_url('admin/bootstrap-datetimepicker.min.js?v='.date('YmdHis')); ?>"></script>
<script src="<?php echo js_url('admin/app/product.js?v='.date('YmdHis')); ?>"></script>

<script type="text/javascript">
	$(document).ready(function(){
	    var datas = [];
	    if ($('#datatableProduct').length>0){
	        datas['selector'] = 'datatableProduct';
	        datas['url'] = BASE_URL + 'product/listProduct';
	        datas['columns'] = [
	            { 'data': 'kode_buku' },
	            { 'data': 'reference' },
	            { 'data': 'category' },
	            { 'data': 'name' },
	            { 'data': 'image' },
	            { 'data': 'description' },
	            { 'data': 'supplier' },
	            { 'data': 'quantity' },
	            { 'data': 'price_1' },
	            { 'data': 'price_2' },
	            { 'data': 'price_3' },
	            { 'data': 'price_4' },
	            { 'data': 'price_5' },
	            { 'data': 'non_r1' },
	            { 'data': 'non_r2' },
	            { 'data': 'non_r3' },
	            { 'data': 'non_r4' },
	            { 'data': 'non_r5' },
	            { 'data': 'width' },
	            { 'data': 'height' },
	            { 'data': 'weight' },
	            { 'data': 'pages' },
	            { 'data': 'capacity' },
	            { 'data': 'active' },
	            { 'data': 'enable' },
	            { 'data': 'date_add' },
	            { 'data': 'date_upd' }
	        ];
	        datas['columnDefs'] = [
	            { className: 'text-center', targets: [0, 4, 6, 7] }
	        ];
	        datas['sort'] = [0, 'asc'];
	        datatableProduct = myDatatables(datas);
	        commonTools(datas['selector'], datatableProduct);
	    }
	});
</script>