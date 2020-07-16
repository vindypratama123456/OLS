$(function($) {
    $.ajaxSetup({
        data: {
            'csrftokenbs': CSRF_HASH
        }
    });
});
$(document).ready(function(){
    var frmReset = $('#reset_form');
    frmReset.validate({
        onfocus: function(element) {$(element).valid()},
        ignore: [],
        errorClass: "has-error",
        errorElement: "span",
        rules: {
            email:{
                required: true,
                email: true
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
        submitHandler: function(){
            $('button').attr('disabled', true);
            $.ajax({
                type: "POST",
                data: frmReset.serialize(),
                dataType: "json",
                url: BASE_URL + 'others/forgotpassword/request',
                beforeSend: function() {
                    $('button').attr('disabled', true);
                },
                success: function(datas){
                    $('input[name=csrftokenbs]').val(datas.csrf_token);
                    if (datas.success === 'true') {
                        $("#reset_form").hide();
                        $("#forgotpass-message").html(datas.message);
                    } else {
                        $('button').attr('disabled', false);
                        $("#msg-area").html(datas.message);
                    }
                }
            });
            return false;
        }
    });
    var frmUpdate = $('#update_form');
    frmUpdate.validate({
        onfocus: function(element) {$(element).valid()},
        errorClass: "has-error",
        errorElement: "span",
        rules: {
            new_pass: {
                required: true,
                minlength: 6,
                maxlength: 25
            },
            conf_new_pass: {
                required: true,
                minlength: 6,
                maxlength: 25,
                equalTo: '#new_pass'
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
        submitHandler: function(form){
            $('button').attr('disabled', true);
            $.ajax({
                type: "POST",
                data: frmUpdate.serialize(),
                dataType: "json",
                url: BASE_URL + 'others/forgotpassword/updatePassword',
                beforeSend: function() {
                    $('button').attr('disabled', true);
                },
                success: function(datas){
                    $('input[name=csrftokenbs]').val(datas.csrf_token);
                    if (datas.success === 'true') {
                        window.location.href = BASE_URL + 'backoffice/login';
                    } else {
                        $('button').attr('disabled', false);
                        $("#msg-area").html(datas.message);
                    }
                }
            });
            return false;
        }
    });
});
