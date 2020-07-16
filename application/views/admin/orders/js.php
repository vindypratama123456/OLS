<script src="<?php echo js_url('jquery-validation/jquery.validate.min.js?v='.date('YmdHis')); ?>"></script>
<script src="<?php echo js_url('jquery-validation/localization/messages_id.js?v='.date('YmdHis')); ?>"></script>
<script src="<?php echo js_url('admin/common.js?v='.date('YmdHis')); ?>"></script>
<script src="<?php echo js_url('admin/moment.js?v='.date('YmdHis')); ?>"></script>
<script src="<?php echo js_url('admin/collapse.js?v='.date('YmdHis')); ?>"></script>
<script src="<?php echo js_url('admin/transition.js?v='.date('YmdHis')); ?>"></script>
<script src="<?php echo js_url('admin/bootstrap-datetimepicker.min.js?v='.date('YmdHis')); ?>"></script>
<script src="<?php echo js_url('admin/app/orders.js?v='.date('YmdHis')); ?>"></script>

<script type="text/javascript">
	var order_state = "<?php echo $detil['current_state']; ?>";
	var check_kontrak = "<?php echo $check_kontrak; ?>";
	if(order_state == 1)
	{
		// if(check_kontrak <= 0)
		// {
		// 	alert("Maaf, tidak bisa melanjutkan proses pesanan. Mitra belum memiliki kontrak atau masa berlaku kontrak habis");
		// 	$('#id_order_state').attr('disabled','disabled');
		// }
    }
</script>
