<?php echo form_open('', 'class="form-horizontal" id="orders_cancel_form" autocomplete="off"'); ?>
  <div class="modal-header">
    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
    <h4 class="modal-title">Pembatalan Pesanan</h4>
  </div>
  <div class="modal-body">
    <div class="row">
      <div class="col-lg-12">
          <input type="hidden" name="id_order" value="<?=$detil['id_order']?>" />
          <input type="hidden" name="reference" value="<?=$detil['reference']?>" />
          <div class="form-group">
              <div class="col-sm-12">
                  <label>Tuliskan Alasan Pembatalan</label>
                  <textarea id="alasan_batal" name="alasan_batal" class="form-control" style="resize:none;"></textarea>
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
  $(document).ready(function(){
    $('#orders_cancel_form').submit(function(e){
        e.preventDefault();
        var reason = $("#alasan_batal").val();
        if(reason!=='') {
          var conf = confirm('Yakin ingin melakukan pembatalan pesanan?');
          if(conf) {
            $('button').attr('disabled', true);
            $.ajax({
              type: "POST",
              data: $("#orders_cancel_form").serialize(),
              dataType: "json",
              url: BASE_URL+'orders/deletePost',
              success:function(datas){
                if(datas.success==='true') {
                  window.location.href = BASE_URL+'orders';
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
          bootAlert("Mohon tuliskan alasan pembatalan pesanan.");
          $("#alasan_batal").focus();
        }
    });
  });
</script>