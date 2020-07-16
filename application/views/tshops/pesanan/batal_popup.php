<?php echo form_open('', 'class="form-horizontal" id="orders_cancel_form" autocomplete="off"'); ?>
<div class="modal-header">
    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span>
    </button>
    <h4 class="modal-title">Pembatalan Pesanan</h4>
</div>
<div class="modal-body">
    <div class="row">
        <div class="col-lg-12">
            <input type="hidden" name="id_order" value="<?= $detil['id_order'] ?>"/>
            <input type="hidden" name="reference" value="<?= $detil['reference'] ?>"/>
            <div class="form-group">
                <div class="col-sm-12">
                    <label>Tuliskan Alasan Pembatalan</label>
                    <textarea id="alasan_batal" name="alasan_batal" class="form-control"
                              style="resize:none;"></textarea>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="modal-footer">
    <button type="submit" class="btn btn-danger pull-left">Batalkan</button>
    <button type="button" class="btn btn-primary" data-dismiss="modal">Tutup</button>
</div>
<?php echo form_close(); ?>
<script type="text/javascript">
    $(document).ready(function () {
        var formCancel = $('#orders_cancel_form');
        var textareaBatal = $('#alasan_batal');
        formCancel.submit(function (e) {
            e.preventDefault();
            var reason = textareaBatal.val();
            if (reason.replace(/\s/g, '').length >= 3) {
                bootbox.confirm({
                    title: 'Konfirmasi',
                    message: 'Yakin ingin melakukan pembatalan pesanan?',
                    callback: function (result) {
                        if (result) {
                            $('button').attr('disabled', true);
                            $.ajax({
                                type: "POST",
                                data: formCancel.serialize(),
                                dataType: "json",
                                url: BASE_URL + 'pesanan/batal',
                                success: function (datas) {
                                    if (datas.success == 'true') {
                                        window.location.href = BASE_URL + 'pesanan';
                                    } else {
                                        bootAlert(datas.message);
                                        $('button').attr('disabled', false);
                                    }
                                }
                            });
                            return false;
                        }
                    }
                });
            } else {
                bootAlert('Mohon tuliskan alasan pembatalan pesanan!');
                textareaBatal.focus();
            }
        });
    });
</script>