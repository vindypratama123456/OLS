
<script type="text/javascript" src="<?php echo assets_url_backmin('js'); ?>/plugins/blueimp/jquery.blueimp-gallery.min.js"></script>
<script type="text/javascript" src="<?php echo assets_url_backmin('js'); ?>/plugins/dropzone//dropzone.min.js"></script>
<script type="text/javascript" src="<?php echo assets_url_backmin('js'); ?>/plugins/icheck/icheck.min.js"></script>
<script type="text/javascript">
    var frmCancel = $('#formCancelOrder');
    var btnSubmit = $('#submitCancelOrder');
    frmCancel.validate({
        ignore: [],
        rules: {
            kode_pesanan: {
                required: true
            }
        }
    });
	btnSubmit.on('click',function() {
		if (frmCancel.valid() == true) {
			var conf = '';
			<?php if ($detail['sts_bayar'] == 2 || $detail['nilai_dibayar'] >= $detail['total_paid']) { ?>
				conf = confirm('Yakin melanjutkan proses ini? \nPesanan sudah dibayar lunas, pastikan kembali pesanan dapat diproses.');
			<?php } else { ?>
				conf = confirm('Yakin membatalkan pesanan ini?');
			<?php } ?>
			if(conf) {
				var uri = frmCancel.data('uri');
				$.ajax({
					type: 'POST',
					data: frmCancel.serialize(),
					dataType: 'json',
					url: BASE_URL + uri,
					beforeSend: function(){
                        btnSubmit.attr('disabled', '');
					},
					success: function(response) {
						window.location.href = BASE_URL + response.redirect;
					}
				});
			} else {
				return false;
			}
		} else {
			return false;
		}
    });
</script>