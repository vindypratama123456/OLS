<?php echo form_open('', 'class="form-horizontal" id="partner_form" autocomplete="off"'); ?>
<div class="modal-header">
    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span>
    </button>
    <h4 class="modal-title">Update Partner</h4>
</div>
<div class="modal-body">
    <div class="row">
        <div class="col-lg-12">
            <input type="hidden" name="id" value="<?= $detil['id'] ?>"/>

            <div class="form-group">
                <div class="col-sm-12">
                    <label>Nama Partner</label>
                    <input type="text" class="form-control text-uppercase" name="name" id="name" value="<?= $detil['name'] ?>">
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
        $("#name").focus();
        $('#partner_form').submit(function (e) {
            e.preventDefault();
            var conf = confirm('Yakin akan meng-update data Partner ?');
            if (conf) {
                $('button').attr('disabled', true);
                $.ajax({
                    type: "POST",
                    data: $("#partner_form").serialize(),
                    dataType: "json",
                    url: BASE_URL + 'partner/update_post',
                    success: function (datas) {
                        if (datas.success == 'true') {
                            window.location.href = BASE_URL + 'partner';
                            return true;
                        } else {
                            bootAlert(datas.message);
                            $('button').attr('disabled', false);
                            return false;
                        }
                    }
                });
                return false;
            }
        });
    });
</script>