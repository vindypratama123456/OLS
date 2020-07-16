<?php echo form_open('', 'class="form-horizontal" id="amount_form" autocomplete="off"'); ?>
<div class="modal-header">
    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span>
    </button>
    <h4 class="modal-title">Input Pembayaran</h4>
</div>
<div class="modal-body">
    <div class="row">
        <div class="col-lg-12">
            <input type="hidden" name="id_order" value="<?= $detil['id_order'] ?>"/>
            <input type="hidden" name="reference" value="<?= $detil['reference'] ?>"/>
            <input type="hidden" name="total_paid" value="<?= $detil['total_paid'] ?>"/>
            <input type="hidden" name="current_state" value="<?= $detil['current_state'] ?>"/>
            <input type="hidden" name="periode" value="<?= $detil['periode'] ?>"/>
            <div class="form-group">
                <div class="col-sm-12">
                    <label>Nominal Pembayaran</label>
                    <input type="text" id="amount" name="amount" class="form-control"/>
                    <div class="checkbox">
                        <label class="control-label">
                            <input type="checkbox" id="lunas" name="lunas"><span
                                    style="font-size: 12pt; font-weight: bold;">&nbsp;Lunas</span>
                        </label>
                    </div>
                </div>
            </div>
            <div class="form-group">
                <div class="col-sm-12">
                    <label>Tanggal Bayar</label>
                    <div class="input-group date" id="datetimepicker6">
                        <input type="text" class="form-control" name="pay_date" id="pay_date"/>
                        <span class="input-group-addon">
                    <span class="glyphicon glyphicon-calendar"></span>
                  </span>
                    </div>
                </div>
            </div>
            <div class="form-group">
                <div class="col-sm-12">
                    <label>Catatan/Keterangan</label>
                    <textarea id="notes" name="notes" class="form-control" style="resize:none;"></textarea>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="modal-footer">
    <button type="submit" class="btn btn-success pull-left" id="submitForm">Simpan</button>
    <button type="button" class="btn btn-primary" data-dismiss="modal">Tutup</button>
</div>
<?php echo form_close(); ?>
<script src="<?php echo js_url('jquery-validation/jquery.validate.min.js'); ?>"></script>
<script src="<?php echo js_url('jquery-validation/localization/messages_id.js'); ?>"></script>
<script src="<?php echo js_url('admin/bootstrap-datetimepicker.min.js'); ?>"></script>
<script type="text/javascript">
    $(function () {
        $('#datetimepicker6, #pay_date').datetimepicker({
            format: 'YYYY-MM-DD',
            maxDate: '<?php echo date("Y-m-d") ?>',
            useCurrent: false
        });

        $("input[id='amount']").keydown(function (e) {
            if ($.inArray(e.keyCode, [46, 8, 9, 27, 13, 110]) !== -1 ||
                (e.keyCode === 65 && (e.ctrlKey === true || e.metaKey === true)) ||
                (e.keyCode >= 35 && e.keyCode <= 40)) {
                return;
            }
            if ((e.shiftKey || (e.keyCode < 48 || e.keyCode > 57)) && (e.keyCode < 96 || e.keyCode > 105)) {
                e.preventDefault();
            }
        });

        $('input[name="lunas"]').on('click', function () {
            if ($(this).is(':checked')) {
                $("#amount").val('<?=(int)($detil['total_paid'] - $detil['nilai_dibayar'])?>');
                $("#amount").attr('readonly', 'true');
            } else {
                $("#amount").val('');
                $("#amount").removeAttr('readonly');
            }
        });
    });
    $(document).ready(function () {
        $('#amount_form').validate({
            errorClass: "has-error",
            errorElement: "span",
            rules: {
                amount: {
                    required: true,
                    digits: true
                },
                pay_date: {
                    required: true,
                    date: true
                }
            },
            highlight: function (element, errorClass, validClass) {
                var elem = $(element);
                elem.parents(".form-group").addClass(errorClass);
                elem.addClass(errorClass);
            },
            unhighlight: function (element, errorClass, validClass) {
                var elem = $(element);
                elem.parents(".has-error").removeClass(errorClass);
                elem.removeClass(errorClass);
            },
            submitHandler: function (form) {
                var conf = confirm('Yakin dengan semua inputan anda?');
                if (conf) {
                    $("#submitForm").attr('disabled', true);
                    $('button').attr('disabled', true);
                    $.ajax({
                        type: "POST",
                        data: $("#amount_form").serialize(),
                        dataType: "json",
                        url: BASE_URL + 'finance/amountPost',
                        async: true,
                        beforeSend: function () {
                            $('#myloader').show();
                            $("#submitForm").attr('disabled', true);
                            $('button').attr('disabled', true);
                        },
                        success: function (datas) {
                            if (datas.success == 'true') {
                                window.location.href = BASE_URL + 'finance';
                            } else {
                                bootAlert(datas.message);
                                // window.location.href = BASE_URL+'finance';
                                $('#myloader').hide();
                                $("#submitForm").attr('disabled', false);
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
