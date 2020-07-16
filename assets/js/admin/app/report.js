$(document).ready(function(){
    var tombol=$('#cari-report'), frmReport=$('#form-report'), elDt1=$('#datetimepicker6'), elDt2=$('#datetimepicker7');
    $(elDt1).datetimepicker({
        format: 'YYYY-MM-DD'
    }).on('dp.change', function (e) {
        $(elDt2).data('DateTimePicker').minDate(e.date);
    });
    $(elDt2).datetimepicker({
        useCurrent: false, //Important! See issue #1075
        format: 'YYYY-MM-DD'
    }).on('dp.change', function (e) {
        $(elDt1).data('DateTimePicker').maxDate(e.date);
    });
    $('#kabupaten').select2();
    frmReport.validate({
        errorClass: 'has-error',
        errorElement: 'span',
        rules: {
            tgl_mulai:{
                required: true
            },
            tgl_akhir: {
                required: true
            }
        },
        highlight: function (element, errorClass) {
            var elem = $(element);
            elem.parents('.form-group').addClass(errorClass);
            elem.addClass(errorClass);
        }, 
        unhighlight: function (element, errorClass) {
            var elem = $(element);
            elem.parents('.has-error').removeClass(errorClass); 
            elem.removeClass(errorClass);
        },
        submitHandler: function() {
            $.ajax({
                type: 'POST',
                data: frmReport.serialize(),
                dataType: 'json',
                url: frmReport.data('action'),
                async: true,
                timeout: 180000,
                beforeSend: function() {
                    tombol.attr('disabled', true);
                    tombol.val('Memproses...', true);
                    $('#result-area').html('');
                },
                success:function(datas){
                    tombol.attr('disabled', false);
                    tombol.val('Cari');
                    if(datas.success === 'true') {
                        $('#result-area').html(datas.content);
                    }
                    else {
                        bootAlert(datas.message);
                    }
                    $('input[name=csrftokenbs]').val(datas.csrf_token);
                },
                error: function(jX, err, errT) {
                    bootAlert(jX.status + '\n' + err + '\n' + errT);
                    $('input[name=csrftokenbs]').val($.parseJSON(jqXHR.responseText).csrf_token);
                }
            });
            return false;
        }
    });
});