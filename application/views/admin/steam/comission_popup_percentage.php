<?php echo form_open('', 'class="form-horizontal" id="frmUpdatePercentage" autocomplete="off"'); ?>
<div class="modal-header">
    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span>
    </button>
    <h4 class="modal-title">Persentase Komisi</h4>
</div>
<div class="modal-body">
    <div class="row">
        <div class="col-lg-12">
            <input type="hidden" name="id_order" value="<?php echo $id_order; ?>"/>
            <input type="hidden" name="old_percentage" value="<?php echo $percentage * 100; ?>"/>
            <input type="hidden" name="email" value="<?php echo $email; ?>"/>
            <div class="form-group">
                <div class="col-sm-12">
                    <label>Masukkan jumlah persentase</label>
                    <input type="text" name="percentage" id="percentage" value="<?php echo $percentage * 100; ?>"
                           max="15" maxlength="5" style="width:50px;"> %
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
<script src="<?php echo js_url('jquery-validation/jquery.validate.min.js?v='.date('YmdHis')); ?>"></script>
<script src="<?php echo js_url('jquery-validation/localization/messages_id.js?v='.date('YmdHis')); ?>"></script>
<script type="text/javascript">
    $("#percentage").on("keyup", function(){
        var value = $(this).val();
        var new_value = value.replace(',', '.');
        if(value.length == 1 && value == ',')
        {
            $(this).val("");
        }
        else
        {
            $(this).val(new_value);
        }
    });
    $(document).ready(function () {
        var myForm = $('#frmUpdatePercentage');
        myForm.validate({
            errorClass: "has-error",
            errorElement: "div",
            rules: {
                percentage: {
                    number: true,
                    min: 1,
                    max: <?php echo getenv('MAX_COMISSION') ?>,
                    required: true
                }
            },
            submitHandler: function (form) {
                var conf = confirm('Yakin dengan nilai persentase yang diinput?');
                if (conf) {
                    $('button').attr('disabled', true);
                    $.ajax({
                        type: 'POST',
                        data: myForm.serialize(),
                        dataType: 'json',
                        url: BASE_URL + 'steam/comission_update_percentage',
                        beforeSend: function () {
                            $('#myloader').show();
                            $('button').attr('disabled', true);
                        },
                        success: function (datas) {
                            if (datas.success == 'true') {
                                $('.modal').modal('hide').data('bs.modal', null);
                                window.location.reload();
                                window.location = BASE_URL + datas.redirect;
                            } else {
                                bootAlert(datas.message);
                                $('#myloader').hide();
                                $('button').attr('disabled', false);
                            }
                        }
                    });
                    return false;
                }
            }
        });
    });
</script>