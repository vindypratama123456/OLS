<?php echo form_open('', 'class="form-horizontal" id="update_reference_form" autocomplete="off"'); ?>
  <div class="modal-header">
    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
    <h4 class="modal-title"><?php echo $title_text; ?></h4>
  </div>
  <div class="modal-body">
    <div class="row">
      <div class="col-lg-12">
          <input type="hidden" name="id_order" value="<?=$id_order?>" />

              <div class="col-sm-12">
          <div class="form-group">
            <label for="reference_other">No Reference :</label>
            <input type="text" class="form-control" id="reference_other" name="reference_other" value="<?php echo $reference_other; ?>">
          </div>
          <div class="form-group">
            <label for="reference_other_from">Order dari :</label>

            <select id="reference_other_from" name="reference_other_from" class="form-control" style="width:100%;">
              <option value="0">SILAHKAN PILIH</option>}
              option
              <?php
              foreach ($partner as $item) {
                $selected = ($item->name == $detil['reference_other_from']) ? ' selected' : '';
                echo '<option value="' . $item->id . ':'. $item->name. '" ' . $selected . '>' . $item->name . '</option>';
              }
              ?>
            </select>
            <!-- <input type="text" class="form-control" id="reference_other_from" name="reference_other_from" value="<?php echo $reference_other_from; ?>"> -->
          </div>
        </div>
      </div>
    </div>
  </div>
  <div class="modal-footer">
    <button type="submit" class="btn btn-success pull-left"><?php echo $button_text; ?></button>
    <button type="button" class="btn btn-primary" data-dismiss="modal">Tutup</button>
  </div>
<?php echo form_close(); ?>
<script type="text/javascript">
  $(document).ready(function(){
    $('#reference_other_from').select2({
      dropdownAutoWidth : true
    }).on('change', function (e) {
      $(this).valid()
    });
    $('#update_reference_form').submit(function(e){
      e.preventDefault();
      var reference_other = $("#reference_other").val();
      if(reference_other!="" || reference_other!=null)
      {
        if($("#reference_other_from").val() == 0)
        {
          alert("Silahkan pilih order dari !");
          return false;
        }
      }
      var conf = confirm('<?php echo $pesan; ?>');
      if(conf) {
        $('button').attr('disabled', true);
        $.ajax({
          type: "POST",
          data: $("#update_reference_form").serialize(),
          dataType: "json",
          url: BASE_URL+'orders/update_reference_post',
          success:function(datas){
            if(datas.success==='true') {
              $('.modal').modal('hide').data('bs.modal', null);
              window.location.reload(true);
              window.location = BASE_URL+datas.redirect;
            }
            else 
            {
              bootAlert(datas.message);
              $('button').attr('disabled', false);
            }
          }
        });
        return false;
      }
    });

    $('#reference_other').on("change", function(){
      var elem = $(this);
      var reference_other = elem.val();
      var data = {
        "reference_other" : reference_other
      }
      $.ajax({
        type: "POST",
        data: data,
        dataType: "json",
        url: BASE_URL+'orders/check_reference',
        success:function(datas){
          if(datas.success === 'true') {
            alert(datas.message);
            // elem.val("");
            elem.focus();
          }
        }
      });
    }); 
  });
 
</script>