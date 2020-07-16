<?php echo form_open('', 'class="form-horizontal" id="orders_edit_form" autocomplete="off"'); ?>
<div class="modal-header">
    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span>
    </button>
    <h4 class="modal-title">Ubah Jumlah</h4>
</div>
<div class="modal-body">
    <div class="row">
        <div class="col-lg-12">

            <input type="hidden" name="id_transaksi_detail" value="<?= $detil['id_transaksi_detail'] ?>"/>
            <input type="hidden" name="id_transaksi" value="<?= $detil['id_transaksi'] ?>"/>
            <input type="hidden" name="id_produk" value="<?= $detil['id_product'] ?>"/>
            <input type="hidden" name="weight" value="<?= $detil['berat'] ?>"/>
            <input type="hidden" name="old_qty" value="<?= $old_qty ?>"/>

            <div class="form-group">
                <div class="col-sm-12">
                    <label>Nama Produk</label>
                    <input type="text" class="form-control" name="product_name" id="product_name"
                           value="<?= $detil['judul_buku'] ?>" readonly>
                </div>
            </div>

            <div class="form-group">
                <div class="col-sm-12">
                    <label>Berat</label>
                    <input type="text" class="form-control" name="weight" id="weight"
                           value="<?= $detil['berat'] ?>" readonly>
                </div>
            </div>

            <div class="form-group">
                <div class="col-sm-4">
                    <label>Jumlah</label>
                    <input type="number" class="form-control" name="jumlah" id="jumlah"
                           value="<?= $detil['jumlah'] ?>" min="0">
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
            var conf = confirm('Yakin ingin memperbarui detil barang keluar ini?');
            if (conf) {
                $('button').attr('disabled', true);
                $.ajax({
                    type: "POST",
                    data: $("#orders_edit_form").serialize(),
                    dataType: "json",
                    url: BASE_URL + 'backmin/gudangpermintaanpartial/detailBarangKeluarEditPost',
                    success: function (datas) {
                        if (datas.success == 'true') {
                            window.location.href = BASE_URL + 'backmin/gudangpermintaanpartial/detailBarangKeluar/' + datas.id_transaksi;
                        } else {
                            bootAlert(datas.message);
                            $('button').attr('disabled', false);
                        }
                    }
                });
                return false;
            }
        });
    });
</script>