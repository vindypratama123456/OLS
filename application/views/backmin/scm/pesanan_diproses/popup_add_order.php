<?php echo form_open('', 'id="formAddOrder" class="form-horizontal" data-uri="' . BACKMIN_PATH . '/scmpesanan/processAddOrder" role="form" autocomplete="off"'); ?>
    <input type="hidden" name="id_transaksi" value="<?php echo $id_transaksi; ?>">
    <input type="hidden" name="id_category" value="<?php echo $id_category; ?>">
    <input type="hidden" name="zona" value="<?php echo $zona; ?>">
    <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Tutup</span></button>
        <h4 class="modal-title">Tambah Pesanan</h4>
    </div>
    <div class="modal-body">
        <?php if ($messages) { ?>
        <div class="row">
            <div role="alert" class="alert alert-danger">
                <i class="fa fa-info-circle"></i>&nbsp; <?php echo $messages ?>
            </div>
        </div>
        <?php } ?>
        <div class="row">
            <table class="table table-bordered table-striped">
                <thead>
                    <tr>
                        <th class="text-center">No</th>
                        <th class="text-center">Kode Buku</th>
                        <th class="text-center">Judul Buku</th>
                        <th class="text-center">Ketersediaan Stok</th>
                        <th class="text-center">Jumlah</th>
                    </tr>
                </thead>
                <tbody>
                    <?php 
                    $no = 1;
                    foreach ($list_product as $row) { 
                    ?>
                    <tr>
                        <td class="text-center"><?php echo $no; ?></td>
                        <td><?php echo '<b>'.$row->kode_buku.'</b>'; ?></td>
                        <td><?php echo $row->judul_buku; ?></td>
                        <td class="text-right"><?php echo $row->stok_available; ?></td>
                        <td>
                            <input type="number" min="0" class="form-control input-sm" class="new_qty" id="new_qty" name="new_qty[<?php echo $row->id_produk; ?>]" value="0" <?php if ($row->stok_available == 0) { echo "readonly='true'"; } ?>>
                        </td>
                    </tr>
                    <?php $no++; } ?>
                </tbody>
            </table>
            <div class="form-group">
                <div class="col-sm-12">
                    <textarea class="form-control" name="alasan" id="alasan" rows="3" placeholder="Masukkan alasan perubahan pada pesanan ini."></textarea>
                </div>
            </div>
        </div>
    </div>
    <div class="modal-footer">
        <div class="pull-left">
            <button type="button" class="btn btn-success" id="submitAddOrder" <?php if ($payout > 0) {echo "disabled='true'";} ?>>S u b m i t</button>
        </div>
        <div class="pull-right">
            <button type="button" class="btn btn-primary" data-dismiss="modal">Tutup</button>
        </div>
    </div>
<?php echo form_close(); ?>

<script type="text/javascript">
    var frmAddOrder = $('#formAddOrder');
    var btnSubmit = $('#submitAddOrder');
    $('input[id="new_qty"]').keydown(function (e) {
        if ($.inArray(e.keyCode, [46, 8, 9, 27, 13, 110]) !== -1 ||
            ((e.keyCode === 65 || e.keyCode === 67 || e.keyCode === 86) && (e.ctrlKey === true || e.metaKey === true)) ||
            (e.keyCode >= 35 && e.keyCode <= 40)) {
                return;
        }
        if ((e.shiftKey || (e.keyCode < 48 || e.keyCode > 57)) && (e.keyCode < 96 || e.keyCode > 105)) {
            e.preventDefault();
        }
    });
    btnSubmit.on('click',function() {
        var conf = '';
        <?php if ($messages_confirm) { ?>
            conf = confirm('<?php echo $messages_confirm ?>');
        <?php } else { ?>
            conf = confirm('Yakin menambah buku pada pesanan ini?');
        <?php } ?>
        if(conf) {
            var uri = frmAddOrder.data('uri');
            $.ajax({
                type: 'POST',
                data: frmAddOrder.serialize(),
                dataType: 'json',
                url: BASE_URL + uri,
                beforeSend: function(){
                    btnSubmit.attr('disabled', '');
                },
                success: function(response) {
                    window.location.href = BASE_URL + response.redirect;
                }
            });
            return false;
        } else {
            return false;
        }
    });
</script>