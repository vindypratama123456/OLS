$(document).ready(function(){
    // $('#reg_korwil,').select2();
    $('#form_registrasi_ec').validate({
        ignore: [],
        errorClass: 'has-error',
        errorElement: 'span',
        rules: {
            email: {
                required: true,
                email: true,
                remote: {
                    type: 'POST',
                    url: BASE_URL+'ecregistrasi/checkDupplicateValidation',
                    data: {
                        values: function(){
                            return $('#email').val();
                        },
                        table: 'employee',
                        select: 'email'
                    }
                }
            },
            password: {
                required: true,
                minlength: 6
            },
            confirm_password: {
                required: true,
                minlength: 6,
                equalTo: password
            },
            name: {
                required: true,
                minlength: 3,
                remote: {
                    type: 'POST',
                    url: BASE_URL+'ecregistrasi/checkDupplicateValidation',
                    data: {
                        values: function(){
                            return $('#name').val();
                        },
                        table: 'employee',
                        select: 'name'
                    }
                }
            },
            telp: {
                required: true,
                digits: true,
                minlength: 6,
                maxlength: 20
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

                   //  var data = new FormData(form);
                   //  console.log(data);
                   //  for (var value of data) {
                   //      alert(value);
                   // }
                   
                    if(result) {
                        $.ajax({
                            type: 'POST',
                            data: new FormData(form),
                            dataType: 'json',
                            url: BASE_URL+'ecregistrasi/add',
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

                                    console.log(data.kabupaten);
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

    // $("#korwil").change(function(){
    //     var korwil = $('#korwil').val();
    //     alert(korwil);
    //     $.ajax({
    //         type : "POST",
    //         url  : BASE_URL + 'ecregistrasi/getKabupatenByKorwil',
    //         dataType : "json",
    //         data : {korwil:korwil},
    //         success: function(result){
    //             console.log(result.row[0].id_employee);
    //         }
    //     });
    // });
    
    
});
