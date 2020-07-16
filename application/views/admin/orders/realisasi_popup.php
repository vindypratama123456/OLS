<?php echo form_open('', 'class="form-horizontal" id="realisasi_books_form" autocomplete="off"'); ?>
<div class="modal-header">
    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span>
    </button>
    <h4 class="modal-title">Realisasi Pesanan Buku</h4>
</div>
<div class="modal-body">
    <div class="row">
        <div class="col-lg-12">
            <input type="hidden" name="id_order" value="<?= $id_order ?>"/>
            <div class="form-group">
                <div class="col-sm-12">
                    <div class="table-responsive">
                        <table class="table table-bordered">
                            <thead>
                            <th class="text-center">Kode Buku</th>
                            <th class="text-center">Judul Buku</th>
                            <th class="text-center">Kelas</th>
                            <th class="text-center">Jumlah</th>
                            <th class="text-center">Realisasi</th>
                            </thead>
                            <tbody>
                            <?php foreach ($listproducts as $row) { ?>
                                <tr>
                                    <td class="text-center"><?php echo $row->kode_buku; ?></td>
                                    <td><?php echo $row->product_name; ?></td>
                                    <td class="text-center"><?php echo $row->kelas; ?></td>
                                    <td class="text-center"><?php echo $row->product_quantity; ?></td>
                                    <td class="text-center">
                                        <input type="hidden" name="id[]" value="<?php echo $row->id_order_detail; ?>">
                                        <input type="number" class="qty" min="0" style="text-align:center;width:60px;"
                                               name="realisasi[]" value="<?php echo $row->quantity_fullfil; ?>">
                                    </td>
                                </tr>
                            <?php } ?>
                            </tbody>
                        </table>
                    </div>
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
        $('#realisasi_books_form').submit(function (e) {
            e.preventDefault();
            var conf = confirm('Yakin dengan inputan realisasi pesanan buku?');
            if (conf) {
                $('button').attr('disabled', true);
                $.ajax({
                    type: "POST",
                    data: $("#realisasi_books_form").serialize(),
                    dataType: "json",
                    url: BASE_URL + 'orders/updateRealisasiBooksPost',
                    success: function (datas) {
                        if (datas.success == 'true') {
                            $('.modal').modal('hide').data('bs.modal', null);
                            window.location.reload(true);
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