<script type='text/javascript' src='<?php echo assets("js"); ?>/plugins/jquery-validation/jquery.validate.js'></script>
<script type='text/javascript' src='<?php echo assets("js"); ?>/plugins/jquery-validation/additional/accept.js'></script>
<script type='text/javascript' src='<?php echo assets("js"); ?>/plugins/jquery-validation/localization/messages_id.js'></script>
<script type="text/javascript" src="<?php echo assets('js'); ?>/plugins/bootstrap/bootstrap-select.js"></script>
<script type="text/javascript" src="<?php echo assets('js'); ?>/plugins/bootstrap/bootstrap-datepicker.js"></script>
<script type="text/javascript" src="<?php echo assets('js'); ?>/plugins/fileinput/fileinput.min.js"></script>
<script type="text/javascript" src="<?php echo assets('js'); ?>/plugins/ckeditor/ckeditor.js"></script>
<script type="text/javascript" src="<?php echo assets('js'); ?>/plugins/ckeditor/config.js"></script>
<script type="text/javascript">
	$(document).ready(function(){
		$("#foto").fileinput({
	        previewFileType: "image",
	        browseClass: "btn btn-info",
	        browseLabel: "Pilih Foto",
	        browseIcon: "<i class=\"glyphicon glyphicon-picture\"></i> ",
	        removeClass: "btn btn-danger",
	        removeLabel: "Hapus",
	        removeIcon: "<i class=\"glyphicon glyphicon-trash\"></i> ",
	        showUpload: false,
	        showRemove: false,
	    });
	    var filePreview = $("#foto-edit").data('file-preview');
	    $("#foto-edit").fileinput({
	        previewFileType: "image",
	        browseClass: "btn btn-info",
	        browseIcon: "<i class=\"glyphicon glyphicon-picture\"></i> ",
	        removeClass: "btn btn-danger",
	        removeIcon: "<i class=\"glyphicon glyphicon-trash\"></i> ",
	        showUpload: false,
	        showRemove: false,
	        initialPreview: [
	            '<img src="'+filePreview+'" alt="Gambar Foto" class="img-responsive">',
	        ],
	        initialPreviewFileType: 'image',
	    });
	    $("#dp").datepicker({
	        format: "dd-mm-yyyy",
	        autoclose: true,
	        todayHighlight: true,
	        toggleActive: true
	    }).on("changeDate", function(e) {
	        $("#dp label.error").hide();
	        $("#dp .error").removeClass("error");
	    });
	});
</script>
