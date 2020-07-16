<?php echo form_open('', 'class="form-horizontal" id="log_book_form" autocomplete="off"'); ?>
<div class="modal-header">
    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span>
    </button>
    <h4 class="modal-title">Input Log Book</h4>
</div>
<div class="modal-body">
    <div class="row">
        <div class="col-lg-12">
            <input type="hidden" name="id_order" value="<?= $detil['id_order'] ?>"/>
            <input type="hidden" name="reference" value="<?= $detil['reference'] ?>"/>
            <div class="form-group">
                <div class="col-sm-12">
                    <label>Tuliskan Log Book Penagihan</label>
                    <textarea id="notes" name="notes" class="form-control" style="resize:none;"></textarea>
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
        $('#log_book_form').submit(function (e) {
            e.preventDefault();
            var notes = $("#notes").val();
            if (notes !== '') {
                var conf = confirm('Yakin dengan isian log book anda?');
                if (conf) {
                    $('button').attr('disabled', true);
                    $.ajax({
                        type: "POST",
                        data: $("#log_book_form").serialize(),
                        dataType: "json",
                        url: BASE_URL + 'finance/logPost',
                        success: function (datas) {
                            if (datas.success == 'true') {
                                window.location.href = BASE_URL + 'finance';
                            } else {
                                bootAlert(datas.message);
                                $('button').attr('disabled', false);
                            }
                        }
                    });
                    return false;
                }
            } else {
                bootAlert("Mohon isikan log book penagihan.");
                $("#notes").focus();
            }
        });
    });
</script>