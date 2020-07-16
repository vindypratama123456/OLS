
<?php echo form_open('', 'data-action="' . base_url() . ADMIN_PATH . '/steam/order_add_post" id="order_steam_form" autocomplete="off"'); ?>
<div class="modal-header">
    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
    <h4 class="modal-title">Input Influencer</h4>
</div>
<div class="modal-body">
    <div class="row">
        <div class="col-lg-12">
            <div class="form-group">
                <div class="col-md-12">
                    <input type="hidden" name="id_order" value="<?= $id_order; ?>">
                    <label>Influencer</label>
                    <select id="influencer" name="influencer" style="width:100%;">
                        <option value="">Silahkan pilih influencer</option>
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
    $(document).ready(function(){
        $("#influencer").select2({
            minimumInputLength: 3,
            tags: [],
            ajax: {
                url: BASE_URL+'steam/get_sales',
                dataType: 'json',
                type: "POST",
                quietMillis: 50,
                data: function (params) {
                    return {
                        search: params.term
                    };
                },
                chace: true
            }
        });

        $('#order_steam_form').submit(function(e){
            e.preventDefault();
            var conf = confirm('Yakin ingin menambahkan pesanan buku?');
            if(conf) {
                $('button').attr('disabled', true);
                $.ajax({
                    type: "POST",
                    data: $("#order_steam_form").serialize(),
                    dataType: "json",
                    url: BASE_URL+'steam/influencer_popup_post',
                    success:function(datas){
                      // if(datas.success==='true') {
                        //   $('.modal').modal('hide').data('bs.modal', null);
                        //   window.location.reload(true);
                        //   window.location = BASE_URL+datas.redirect;
                        // }
                        // else {
                          //   bootAlert(datas.message);
                          //   $('button').attr('disabled', false);
                          // }
                      }
                  });
                return false;
            }
        });
    });
</script>