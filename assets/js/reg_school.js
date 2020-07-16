$(function() {
    $('#reg_bentuk, #reg_jenjang, #reg_provinsi, #reg_kabupaten').select2();
    $('#register').validate({
        ignore: [],
        rules: {
            reg_no_npsn: {
                required: true,
                number: true,
                minlength: 6
            },
            reg_school_name: {
                required: true,
                minlength: 3
            },
            reg_bentuk: {
                required: true
            },
            reg_jenjang: {
                required: true
            },
            reg_alamat: {
                required: true,
                minlength: 3
            },
            reg_provinsi: {
                required: true
            },
            reg_kabupaten: {
                required: true
            },
            reg_phone: {
                required: true,
                minlength: 6
            },
            reg_email: {
                required: true,
                email: true,
                remote: {
                    type: 'POST',
                    url: BASE_URL+'akunsaya/checkDupplicateValidation',
                    data: {
                        values: function(){
                            return $('#reg_email').val();
                        },
                        table: 'customer',
                        select: 'email_operator',
                        where: 'no_npsn',
                        where_value: $('#reg_no_npsn').val(),
                    }
                }
            },
            CaptchaCode: {
                required: true
            }
        },
        unhighlight: function (element, errorClass, validClass) {
            var elem = $(element);
            if (elem.hasClass('select2-hidden-accessible')) {
                $('#select2-' + elem.attr('id') + '-container').parent().removeClass('error');
            } else {
                elem.removeClass(errorClass);
            }
        },
        errorPlacement: function(error, element) {
            var elem = $(element);
            if (elem.hasClass('select2-hidden-accessible')) {
                element = $('#select2-' + elem.attr('id') + '-container').parent();
                error.insertAfter(element);
            } else {
                error.insertAfter(element);
            }
        },
        submitHandler: function(form){
            bootbox.confirm({
                title: 'Konfirmasi',
                message: 'Yakin semua isian anda sudah benar?',
                callback: function (result) {
                    if (result) {
                        disableBtn('submitReg');
                        $.ajax({
                            type: 'POST',
                            data: new FormData(form),
                            dataType: 'json',
                            url:  BASE_URL+'akunsaya/processRegister',
                            async: true,
                            contentType: false,
                            processData: false,
                            beforeSend: function() {
                                $('#myloader').show();
                                $('.bootbox').modal('hide').data('bs.modal', null);
                                disableBtn('submitReg');
                            },
                            success:function(datas){
                                if (datas.success === true) {
                                    window.location.href = BASE_URL+'pesanan/formpesanan';
                                } else {
                                    bootAlert(datas.message);
                                    $('#myloader').hide();
                                    enableBtn('submitReg');
                                    $('#CaptchaCode').get(0).Captcha.ReloadImage();
                                }
                            },
                            error: function( jqXHR, exception ){
                                var msg = 'Error';
                                if (jqXHR.status === 0) {
                                    msg = 'Not connect.\n Verify Network.';
                                } else if (jqXHR.status === 404) {
                                    msg = 'Requested page not found. [404]';
                                } else if (jqXHR.status === 400) {
                                    respond = $.parseJSON(jqXHR.responseText);
                                    msg = respond.message;
                                } else if (jqXHR.status === 500) {
                                    msg = 'Internal Server Error [500].';
                                } else if (exception === 'parsererror') {
                                    msg = 'Requested JSON parse failed.';
                                } else if (exception === 'timeout') {
                                    msg = 'Time out error.';
                                } else if (exception === 'abort') {
                                    msg = 'Ajax request aborted.';
                                } else {
                                    msg = 'Uncaught Error: ' + jqXHR.responseText;
                                }
                                bootAlert(msg);
                                $('#myloader').hide();
                                enableBtn('submitReg');
                                $('#CaptchaCode').get(0).Captcha.ReloadImage();
                            }
                        });
                        return false;
                    }
                }
            });
        }
    });
});
$('#reg_provinsi').on('change', function(){
    if ($(this).val() === '') {
        $('#reg_kabupaten').empty().append('<option value="">- Pilih Kabupaten -</option>');
    }
    else {
        $('#reg_kabupaten').empty().append('<option value="">- Pilih Kabupaten -</option>');
        var data = {
            provinsi: $(this).val()
        };
        $.ajax({
            url: BASE_URL+'akunsaya/getKabupatenByProvinsi',
            data: data,
            dataType: 'json',
            type: 'POST',
            async: true,
            success: function(data) {
                if (data.success === true) {
                    $.each(data.row, function (i, item) {
                        $('#reg_kabupaten').append($('<option>', {
                            value: item.kabupaten,
                            text : item.kabupaten
                        }));
                    });
                } else {
                    $('#reg_kabupaten').empty().append('<option value="">- Pilih Kabupaten -</option>');
                }
            }
        });
    }
});
