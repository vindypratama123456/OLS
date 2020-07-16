<?php echo form_open('', 'class="form-horizontal" id="orders_edit_form" autocomplete="off"'); ?>
<div class="modal-header">
    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span>
    </button>
    <h4 class="modal-title">Tutup Permintaan</h4>
</div>
<div class="modal-body">
    <div class="row">
        <div class="col-lg-12">

            <input type="hidden" name="id_request" value="<?= $id_request ?>"/>

            <div class="form-group">
                <div class="col-sm-12">
                    <label>Keterangan </label>
                    <input type="text" class="form-control" name="catatan" id="catatan" value="" >
                </div>
            </div>

        </div>
    </div>
</div>
<div class="modal-footer">
    <button type="submit" class="btn btn-success pull-left">Simpan</button>
    <button type="button" class="btn btn-primary" data-dismiss="modal">Tutup</button>
</div>
<?php echo form_close(); ?>
<script type="text/javascript">
    $(document).ready(function () {
        $('#orders_edit_form').submit(function (e) {
            e.preventDefault();
            var conf = confirm('Yakin ingin menutup permintaan ini?');
            if (conf) {
                $('button').attr('disabled', true);
                $.ajax({
                    type: "POST",
                    data: $("#orders_edit_form").serialize(),
                    dataType: "json",
                    url: BASE_URL + 'backmin/gudangrequeststockpartial/close_request_stock_post',
                    success: function (datas) {
                        console.log(datas);
                        if (datas.success == 'true') {
                            window.location.href = BASE_URL + datas.redirect;
                        } else {
                            // bootAlert(datas.message);
                            // $('button').attr('disabled', false);
                            window.location.href = BASE_URL + datas.redirect;
                        }
                    }
                });
                return false;
            }
        });
    });
</script>