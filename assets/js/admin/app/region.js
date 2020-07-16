$(document).ready(function(){
    if($('#region_form').length > 0) {
        $('#region_form').validate({
            onkeyup: function(element) {$(element).valid()},
            onfocusout: function(element) {$(element).valid()},
            ignore: [],
            errorClass: "has-error",
            errorElement: "span",
            rules: {
                name:{
                    required: true,
                    minlength: 3
                },
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
                $.ajax({
                    type: "POST",
                    data: $("#region_form").serialize(),
                    dataType: "json",
                    url: $('#region_form').data('action'),
                    beforeSend: function() {
                        $('button').attr('disabled', true);
                    },
                    success:function(datas){
                        if(datas.success==='true') {
                            window.location.href = BASE_URL+'region';
                        }
                        else {
                            bootAlert(datas.message);
                            $('button').attr("disabled", false);
                        }
                    }
                });
                return false;
            }
        });
    }
    $('.del_data').click(function(){
        var conf = confirm('Yakin ingin menghapus data ini?');
        if(conf) {
            $.ajax({
                type: "POST",
                data: "id="+$(this).data('id'),
                dataType: "json",
                url: BASE_URL+'region/delete',
                success:function(datas){
                    window.location.href = BASE_URL+'region';
                }
            });
            return false;
        }
    });
});