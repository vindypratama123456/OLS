$(document).ready(function(){
    $("#bank_account_type").select2();
    $('#mitra_form').validate({
        errorClass: "has-error",
        errorElement: "span",
        rules: {
            name: {
                required: true,
                minlength: 3
            },
            identity_code: {
                required: true,
                minlength: 8
            },
            no_npwp: {
                minlength: 9
            },
            address: {
                required: true,
                minlength: 3
            },
            telp: {
                required: true,
                minlength: 6
            },
            bank_account_number: {
                required: true,
                minlength: 6
            },
            bank_account_name: {
                required: true,
                minlength: 3
            },
            bank_account_type: {
                required: true
            },
            passwd: {
                minlength: 6
            },
            passwd_conf: {
                minlength: 6,
                equalTo: passwd
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
        submitHandler: function(form) {
            var conf = confirm('Yakin dengan semua isian data ini?');
            if(conf) {
                $('button').attr('disabled', true);
                $.ajax({
                    type: "POST",
                    data: new FormData(form),
                    dataType: "json",
                    url:  $('#mitra_form').data('action'),
                    async: true,
                    contentType: false,
                    processData: false,
                    beforeSend: function() {
                        $("#errorPlace").html("");
                        loading_button("submit");
                        panel_refresh(panel,"shown");
                    },
                    beforeSend: function() {
                        $('#myloader').show();
                        $('button').attr('disabled', true);
                    },
                    success:function(datas){
                        if(datas.success==='true') {
                            window.location.href = BASE_URL+'profile';
                        }
                        else {
                            bootAlert(datas.message);
                            $('#myloader').hide();
                            $('button').attr('disabled', false);
                            window.location.href = BASE_URL+'profile';
                        }
                    }
                });
                return false;
            }
        }
    });
});
