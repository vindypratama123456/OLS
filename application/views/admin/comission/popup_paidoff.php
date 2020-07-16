<?php echo form_open('', 'class="form-horizontal" id="frmUpdatePaidoff" autocomplete="off"'); ?>
<div class="modal-header">
    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span>
    </button>
    <h4 class="modal-title">Rencana Transfer Komisi</h4>
</div>
<div class="modal-body">
    <div class="row">
        <div class="col-lg-12">
            <input type="hidden" name="no_pd" value="<?php echo $noPD; ?>"/>
            <div class="form-group">
                <div class="col-sm-12">
                    <label>Masukkan tanggal pembayaran</label>
                    <div class="input-group date" id="dtpicker_paid">
                        <input type="text" class="form-control" id="paid_date" name="paid_date"
                               placeholder="YYYY-MM-DD">
                        <span class="input-group-addon">
                        <span class="glyphicon glyphicon-calendar"></span>
                    </span>
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
<script src="<?php echo js_url('jquery-validation/jquery.validate.min.js'); ?>"></script>
<script src="<?php echo js_url('jquery-validation/additional-methods.js'); ?>"></script>
<script src="<?php echo js_url('jquery-validation/localization/messages_id.js'); ?>"></script>
<script src="<?php echo js_url('admin/bootstrap-datetimepicker.min.js'); ?>"></script>
<script src="<?php echo js_url('admin/bootbox.min.js?v='.date('YmdHis')); ?>"></script>
<script src="<?php echo js_url('admin/common.js?v='.date('YmdHis')); ?>"></script>
<script type="text/javascript">
    $(document).ready(function () {
        var tomorrow = moment().add(1, 'days');
        $('#dtpicker_paid, #paid_date').datetimepicker({
            format: 'YYYY-MM-DD',
            ignoreReadonly: true,
            minDate: tomorrow,
            daysOfWeekDisabled: [0, 6]
            // useCurrent: false
        });
        var myForm = $('#frmUpdatePaidoff');
        var myField = $('#paid_date');
        $(myForm).validate({
            errorClass: "has-error",
            errorElement: "span",
            rules: {
                paid_date: {
                    required: true,
                    date: true
                }
            },
            highlight: function (element, errorClass) {
                var elem = $(element);
                elem.parents(".form-group").addClass(errorClass);
                elem.addClass(errorClass);
            },
            unhighlight: function (element, errorClass) {
                var elem = $(element);
                elem.parents(".has-error").removeClass(errorClass);
                elem.removeClass(errorClass);
            },
            submitHandler: function () {
                var paid_date = myField.val();
                if (paid_date !== '') {
                    bootbox.confirm({
                        title: 'Konfirmasi',
                        message: 'Yakin dengan tanggal yang anda input?',
                        callback: function (result) {
                            if (result) {
                                $('button').attr('disabled', true);
                                $.ajax({
                                    type: 'POST',
                                    data: myForm.serialize(),
                                    dataType: 'json',
                                    url: BASE_URL + 'comission/updatePaidoff',
                                    beforeSend: function () {
                                        $('#myloader').show();
                                        $('button').attr('disabled', true);
                                    },
                                    success: function (datas) {
                                        if (datas.success == 'true') {
                                            $('.modal').modal('hide').data('bs.modal', null);
                                            window.location.reload();
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
                } else {
                    bootAlert("Mohon masukkan tanggal rencana transfer komisi.");
                    myField.focus();
                }
            }
        });
    });
</script>