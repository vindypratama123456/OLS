
<script type="text/javascript" src="<?php echo assets_url_backmin('js'); ?>/plugins/bootstrap/bootstrap-select.js"></script>
<script type="text/javascript" src="<?php echo assets_url_backmin('js'); ?>/plugins/datatables/jquery.dataTables.min.js"></script>
<script type="text/javascript" src="<?php echo assets_url_backmin('js'); ?>/plugins/tableexport/tableExport.js"></script>
<script type="text/javascript" src="<?php echo assets_url_backmin('js'); ?>/plugins/tableexport/jquery.base64.js"></script>
<script type="text/javascript" src="<?php echo assets_url_backmin('js'); ?>/plugins/tableexport/html2canvas.js"></script>
<script type="text/javascript" src="<?php echo assets_url_backmin('js'); ?>/plugins/tableexport/jspdf/libs/sprintf.js"></script>
<script type="text/javascript" src="<?php echo assets_url_backmin('js'); ?>/plugins/tableexport/jspdf/jspdf.js"></script>
<script type="text/javascript" src="<?php echo assets_url_backmin('js'); ?>/plugins/tableexport/jspdf/libs/base64.js"></script> 
<script type="text/javascript">
	$(document).ready(function(){
		// $('#form-catatan').hide();
		if($('#status').val() === '0' || $('#status').val() === '2')
		{
			$('#form-catatan').show();
			$('#status').attr('disabled',true);
		}
		else
		{
			$('#form-catatan').hide();
		}

		$('#status').change(function(){
			if($(this).val() === '0' || $(this).val() === '2')
			{
				$('#form-catatan').show();
			}
			else
			{
				$('#form-catatan').hide();
			}
		});
	});
</script>