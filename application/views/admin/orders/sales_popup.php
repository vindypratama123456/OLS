<?php echo form_open('', 'class="form-horizontal" id="orders_sales_form" autocomplete="off"'); ?>
<div class="modal-header">
    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span>
    </button>
    <h4 class="modal-title">Ubah Sales Referer</h4>
</div>
<div class="modal-body">
    <div class="row">
        <div class="col-lg-12">
            <input type="hidden" name="id_order" value="<?= $detil['id_order'] ?>"/>
            <div class="form-group">
                <div class="col-sm-12">
                    <label>Pilih Sales</label>
                    <select id="emailsales" name="emailsales" class="form-control" style="width:100%;">
                        <option value="">- Silahkan pilih satu -</option>
                        <option value="<?php echo $korwil['email']; ?>"><?php echo $korwil['code'].' - '.$korwil['name'].' ('.$korwil['email'].')'; ?></option>
                        <?php
                        foreach ($listsales as $itemsales) {
                            echo '<option value="'.$itemsales->email.'">'.$itemsales->code.' - '.$itemsales->name.' ('.$itemsales->email.')</option>';
                        } ?>
                    </select>
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
        var frmOrderSales = $('#orders_sales_form');
        var emailSales = $('#emailsales');
        emailSales.select2();
        frmOrderSales.submit(function (e) {
            e.preventDefault();
            var reason = emailSales.val();
            if (reason !== '') {
                var conf = confirm('Yakin ingin mengubah sales representatif?');
                if (conf) {
                    var ido = "<?php echo $detil['id_order']; ?>";
                    $('button').attr('disabled', true);
                    $.ajax({
                        type: "POST",
                        data: frmOrderSales.serialize(),
                        dataType: "json",
                        url: BASE_URL + 'orders/changeSalesPost',
                        success: function (datas) {
                            if (datas.success == 'true') {
                                window.location.href = BASE_URL + 'orders/detail/' + ido;
                            } else {
                                bootAlert(datas.message);
                                $('button').attr('disabled', false);
                            }
                        }
                    });
                    return false;
                }
            } else {
                bootAlert("Silahkan tentukan nama sales.");
                emailSales.focus();
            }
        });
    });
</script>
