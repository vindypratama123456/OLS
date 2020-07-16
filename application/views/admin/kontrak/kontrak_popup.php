<?php echo form_open('', 'data-action="' . base_url() . ADMIN_PATH . '/kontrak/kontrak_popup_post" id="kontrak_form" autocomplete="off"'); ?>
<div class="modal-header">
    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
    <h4 class="modal-title">Ubah Data Kontrak</h4>
</div>
<div class="modal-body">
    <div class="row">
        <div class="col-sm-12">
            <input type="hidden" class="form-control datepicker" name="mikon_id" id="mikon_id" value="<?=$mikon_id?>">
            <input type="hidden" class="form-control datepicker" name="code" id="code" value="<?=$code?>">
            <div class="form-group">
                <label>No. Kontrak</label>
                <input type="text" class="form-control datepicker" name="mikon_no_kontrak" id="mikon_no_kontrak" value="<?=$detil['mikon_no_kontrak']?>">
            </div>
            <div class="form-group">
                <label>Tempat Kontrak</label>
                <input type="text" class="form-control datepicker" name="mikon_tempat_kontrak" id="mikon_tempat_kontrak" value="<?=$detil['mikon_tempat_kontrak']?>">
            </div>
            <div class="form-group">
                    <label>Tanggal mulai</label>
                    <input type="text" class="form-control datepicker" name="mikon_tanggal" id="mikon_tanggal" value="<?=$detil['mikon_tanggal']?>">
            </div>
            <div class="form-group">
                    <label>Tanggal berakhir</label>
                    <input type="text" class="form-control datepicker" name="mikon_tanggal_akhir" id="mikon_tanggal_akhir" value="<?=$detil['mikon_tanggal_akhir']?>">
            </div>
            <!-- <div class="form-group">
                <div class="col-sm-8">
                    <label>Periode</label>
                    <input type="text" class="form-control" name="mikon_periode" id="mikon_periode" value="<?=$detil['mikon_periode']?>">
                </div>
            </div> -->
            
            <?php if ($detil['mikon_file']) { ?>
                <div class="form-group">
                        <label>Document</label> 
                        <br>* biarkan kosong, jika tidak diubah
                        <input type="file" class="form-control" name="mikon_file">
                </div>
                <div class="form-group">
                        <img src="<?php echo base_url().'uploads/kontrak/'.$detil['mikon_file']; ?>" class="img-thumbnail img-responsive" alt="image" style="max-height:250px;">
                        <input type="hidden" name="mikon_file_temp" value="<?= $detil['mikon_file']; ?>">
                </div>
            <?php }else{ ?>
                <div class="form-group">
                        <label>Document</label>
                        <input type="file" class="form-control" name="mikon_file">
                </div>
                <div class="form-group">
                        <img src="<?php echo base_url().'uploads/kontrak/no_image.png'; ?>" class="img-thumbnail img-responsive form-control" alt="<?php echo $detil['name']; ?>" style="max-height:250px;">
                </div>
            <?php } ?>
            <!-- <div class="form-group">
                <div class="col-md-8">
                    <br /><label>Status</label><br />
                    <label class="radio-inline">
                        <input name="active" value="1" type="radio"<?php if($detil['active']==1) echo ' checked'; ?>>Aktif
                    </label>
                    <label class="radio-inline">
                        <input name="active" value="0" type="radio"<?php if($detil['active']==0) echo ' checked'; ?>>Non-aktif
                    </label>
                </div>
            </div> -->
        </div>
    </div>
</div>
<div class="modal-footer">
    <button type="submit" class="btn btn-success pull-left">Ubah</button>
    <button type="button" class="btn btn-primary" data-dismiss="modal">Tutup</button>
</div>
<?php echo form_close(); ?>
<script type="text/javascript">
    $(document).ready(function(){
        $("#mikon_tanggal").datepicker({
            format: "yyyy-mm-dd",
            autoclose: true,
            todayHighlight: true,
            toggleActive: true
        })
        $("#mikon_tanggal_akhir").datepicker({
            format: "yyyy-mm-dd",
            autoclose: true,
            todayHighlight: true,
            toggleActive: true
        })
    });
</script>
<script type="text/javascript">
    $(document).ready(function(){
        $('#kontrak_form').submit(function(e){
            e.preventDefault();
            var conf = confirm('Yakin ingin mengubah data kontrak?');
            if(conf) {
                var form = document.getElementById("kontrak_form");
                $('button').attr('disabled', true);
                $.ajax({
                    url: BASE_URL+'kontrak/kontrak_popup_post',
                    type: "POST",
                    data: new FormData(form),
                    processData: false,
                    contentType: false,
                    dataType: "json",
                    success:function(datas, textStatus, jqXHR){
                        if(datas.success==='true') {
                            $('.modal').modal('hide').data('bs.modal', null);
                            window.location.reload(true);
                            window.location = BASE_URL+datas.redirect;
                        }
                        else {
                            bootAlert(datas.message);
                            $('button').attr('disabled', false);
                        }
                    },
                    error: function(jqXHR, textStatus, errorThrown){
                        //if fails     
                    }
                });
                return false;
            }
        });
    });
</script>