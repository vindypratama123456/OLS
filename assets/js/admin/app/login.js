$(document).ready(function(){
    var frmLogin = '#login_form';
    if($(frmLogin).length > 0) {
        $(frmLogin).validate({
        onfocus: function(element) {$(element).valid()},
        ignore: [],
        errorClass: 'has-error',
        errorElement: 'span',
        rules: {
            u_name: {
                required: true,
                email: true
            },
            u_pass: {
                required: true,
                minlength: 6
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
            $.ajax({
                type: 'POST',
                data: $(frmLogin).serialize(),
                dataType: 'json',
                url: BASE_URL + 'login/verify',
                beforeSend: function() {
                    $('#myloader').show();
                    disableBtn('btn-login');
                },
                success: function(datas) {
                  if (datas.success === true) {
                      window.location.href = BASE_URI + datas.redirect;
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
                    $('#alert-area').html('' +
                        '<div class="alert alert-danger alert-dismissable">' +
                        '<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>' +
                        '<i class="fa fa-info-warning"></i>' + msg +
                        '</div>');
                    enableBtn('btn-login');
                }
            });
            return false;
        }
    });
    }
});
