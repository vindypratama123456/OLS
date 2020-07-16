<?php echo form_open('', 'class="form-horizontal" id="add_books_form" autocomplete="off"'); ?>
  <div class="modal-header">
    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
    <h4 class="modal-title">Tambah Judul Buku <?php foreach ($category as $row) { echo $row->name; } ?></h4>
  </div>
  <div class="modal-body">
    <div class="row">
      <div class="col-lg-12">
          <input type="hidden" name="id_order" value="<?=$id_order?>" />
          <input type="hidden" name="jenjang" value="<?=$jenjang?>" />
          <input type="hidden" name="zona" value="<?=$zona?>" />
          <div class="form-group">
              <div class="col-sm-12">
                  <div class="table-responsive">
                    <table class="table table-bordered">
                      <thead>
                        <th class="text-center">Kode Buku</th>
                        <th class="text-center">Judul Buku</th>
                        <th class="text-center">Kelas</th>
                        <th class="text-center">Harga Satuan</th>
                        <th class="text-center">Jumlah</th>
                      </thead>
                      <tbody>
                        <?php foreach ($listbooks as $row) { ?>
                        <tr>
                          <td><?php echo $row->kode_buku; ?></td>
                          <td><?php echo $row->judul; ?></td>
                          <td><?php echo $row->kelas; ?></td>
                          <td><?php echo toRupiah($row->harga); ?></td>
                          <td><input type="number" class="qty" min="0" style="text-align:center;width:60px;" name="qty-<?php echo $row->id_product; ?>"></td>
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
    <button type="submit" class="btn btn-success pull-left">Tambahkan</button>
    <button type="button" class="btn btn-primary" data-dismiss="modal">Tutup</button>
  </div>
<?php echo form_close(); ?>
<script type="text/javascript">
  $(document).ready(function(){
    $('#add_books_form').submit(function(e){
        e.preventDefault();
        var count = $('.qty').filter(function(){ return $(this).val(); }).length;
        if(count>0) {
          var conf = confirm('Yakin ingin menambahkan pesanan buku?');
          if(conf) {
            $('button').attr('disabled', true);
            $.ajax({
              type: "POST",
              data: $("#add_books_form").serialize(),
              dataType: "json",
              url: BASE_URL+'orders/addBooksPost',
              success:function(datas){
                if(datas.success==='true') {
                  $('.modal').modal('hide').data('bs.modal', null);
                  window.location.reload(true);
                  window.location = BASE_URL+datas.redirect;
                }
                else {
                  bootAlert(datas.message);
                  $('button').attr('disabled', false);
                }
              }
            });
            return false;
          }
        }
        else {
          bootAlert('Mohon masukkan jumlah yang diinginkan!');
        }
    });
  });
</script>