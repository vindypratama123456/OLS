$(function() {
    $('#login_form').validate({
        ignore: [],
        rules: {
            u_npsn: {
                required: true,
                minlength: 4,
                number: true
            },
            u_email: {
                email: true,
                required: true,
                minlength: 4
            },
            u_telp: {
                required: true,
                minlength: 3
            },
            CaptchaCode: {
                required: true
            }
        },
        submitHandler: function(form){
            bootbox.confirm({
                title: 'Konfirmasi',
                message: 'Yakin semua isian anda sudah benar?',
                callback: function (result) {
                    if (result) {
                        disableBtn('submitLogin');
                        $.ajax({
                            type: 'POST',
                            data: new FormData(form),
                            dataType: 'json',
                            url:  BASE_URL+'akunsaya/processLogin',
                            async: true,
                            contentType: false,
                            processData: false,
                            beforeSend: function() {
                                $('#myloader').show();
                                $('.bootbox').modal('hide').data('bs.modal', null);
                                disableBtn('submitLogin');
                            },
                            success:function(datas){
                                if (datas.success === true) {
                                    window.location.href = BASE_URL+'pesanan/formpesanan';
                                } else {
                                    bootAlert(datas.message);
                                    $('#myloader').hide();
                                    enableBtn('submitLogin');
                                    $('#CaptchaCode').get(0).Captcha.ReloadImage();
                                }
                            },
                            error: function( jqXHR, exception ){
                                // var msg = 'Error';
                                // if (jqXHR.status === 0) {
                                //     msg = 'Not connect.\n Verify Network.';
                                // } else if (jqXHR.status === 404) {
                                //     msg = 'Requested page not found. [404]';
                                // } else if (jqXHR.status === 400) {
                                //     respond = $.parseJSON(jqXHR.responseText);
                                //     msg = respond.message;
                                // } else if (jqXHR.status === 500) {
                                //     msg = 'Internal Server Error [500].';
                                // } else if (exception === 'parsererror') {
                                //     msg = 'Requested JSON parse failed.';
                                // } else if (exception === 'timeout') {
                                //     msg = 'Time out error.';
                                // } else if (exception === 'abort') {
                                //     msg = 'Ajax request aborted.';
                                // } else {
                                //     msg = 'Uncaught Error: ' + jqXHR.responseText;
                                // }
                                // bootAlert(msg);
                                $('#myloader').hide();
                                enableBtn('submitLogin');
                                location.reload();
                            }
                        });
                        return false;
                    }
                }
            });
        }
    });
});

function register()
{
    var form_data = {
        u_email : $("#u_email").val(),
        u_npsn : $("#u_npsn").val(),
        u_telp : $("#u_telp").val()
    };

    $.ajax({
        type: "POST",
        data: form_data,
        dataType: "json",
        url: BASE_URL + 'akunsaya/registerLink',
        success:function(datas){
            window.location.href = datas.redirectURL;
        }
    });
}
