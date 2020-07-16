$(document).ready(function(){
    $('#reg_korwil, #reg_referral, #reg_bank_name').select2();
    $('#form_registrasi_mitra').validate({
        ignore: [],
        errorClass: 'has-error',
        errorElement: 'span',
        rules: {
            reg_email: {
                required: true,
                email: true,
                remote: {
                    type: 'POST',
                    url: BASE_URL+'mitraregistrasi/checkDupplicateValidation',
                    data: {
                        values: function(){
                            return $('#reg_email').val();
                        },
                        table: 'employee',
                        select: 'email'
                    }
                }
            },
            reg_password: {
                required: true,
                minlength: 6
            },
            reg_confirm_password: {
                required: true,
                minlength: 6,
                equalTo: reg_password
            },
            reg_name: {
                required: true,
                minlength: 3
            },
            reg_identity: {
                required: true,
                digits: true,
                minlength: 16,
                maxlength: 16,
                remote: {
                    type: 'POST',
                    url: BASE_URL+'mitraregistrasi/checkDupplicateValidation',
                    data: {
                        values: function(){
                            return $('#reg_identity').val();
                        },
                        table: 'mitra_profile',
                        select: 'identity_code'
                    }
                }
            },
            reg_name_npwp: {
                required: true,
                minlength: 2
            },
            reg_npwp: {
                required: true,
                digits: true,
                minlength: 15,
                maxlength: 15
            },
            reg_address_npwp: {
                required:true,
                minlength: 3
            },
            reg_address: {
                required: true,
                minlength: 3
            },
            reg_phone: {
                required: true,
                digits: true,
                minlength: 6,
                maxlength: 20
            },
            reg_photo: {
                required: true
            },
            reg_korwil: {
                required: true
            },
            reg_bank_name: {
                required: true
            },
            reg_account_number: {
                required: true,
                digits: true,
                minlength: 6,
                maxlength: 25
            },
            reg_account_name: {
                required: true,
                minlength: 3,
                maxlength: 100
            },
            CaptchaCode: {
                required: true
            }
        },
        unhighlight: function (element, errorClass) {
            var elem = $(element);
            if (elem.hasClass('select2-hidden-accessible')) {
                $('#select2-' + elem.attr('id') + '-container').parent().removeClass(errorClass);
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
                callback: function(result){
                    if(result) {
                        $.ajax({
                            type: 'POST',
                            data: new FormData(form),
                            dataType: 'json',
                            url: BASE_URL+'mitraregistrasi/add',
                            async: true,
                            contentType: false,
                            processData: false,
                            beforeSend: function() {
                                disableBtn('submit');
                                $('.bootbox').modal('hide').data('bs.modal', null);
                                $('#myloader').show();
                            },
                            success:function(data, statusText, xhr){
                                $('input[name=csrftokenbs]').val(data.csrf_token);
                                if (data.success === true) {
                                    bootAlert(data.message);
                                    $('#myloader').hide();
                                    $('#content_form').addClass('hidden');
                                    $('#content_confirm').removeClass('hidden');
                                }
                            },
                            error: function( jqXHR, exception ){
                                $('input[name=csrftokenbs]').val($.parseJSON(jqXHR.responseText).csrf_token);
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
                                enableBtn('submit');
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
