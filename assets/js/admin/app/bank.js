$(document).ready(function(){
    var datas=[];
    if($('#datatableBank').length>0){
        datas['selector'] = 'datatableBank';
        datas['url'] = BASE_URL+'bank/list_bank';
        datas['columns'] = [
            { 'data': 'bank_code' },
            { 'data': 'bank_name' },
            { 'data': 'bank_alias' },
            { 'data': 'status' },
            { 'data': 'aksi' }
        ];
        datas['columnDefs'] = [
            { className: 'text-center', targets: [0,4] }
        ];
        datas['sort'] = [1,'asc'];
        tableBank = myDatatables(datas);
        commonTools(datas['selector'], tableBank);
    }

    if($('#bank_form').length > 0) {
        $('#bank_form').validate({
            onkeyup: function(element) {$(element).valid()},
            onfocusout: function(element) {$(element).valid()},
            ignore: [],
            errorClass: "has-error",
            errorElement: "span",
            rules: {
                kabupaten:{
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
                    data: $("#bank_form").serialize(),
                    dataType: "json",
                    url: $('#bank_form').data('action'),
                    beforeSend: function() {
                        $('button').attr('disabled', true);
                    },
                    success:function(datas){
                        if(datas.success==='true') {
                            window.location.href = BASE_URL+'bank';
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

    $('#datatableBank').on('click','#del_data',function(){
        console.log('button delete');
        var conf = confirm('Yakin ingin menghapus data ini?');
        if(conf) {
            $.ajax({
                type: "POST",
                data: "id="+$(this).attr('data'),
                dataType: "json",
                url: BASE_URL+'bank/delete',
                success:function(datas){
                    window.location.href = BASE_URL+'bank';
                }
            });
            return false;
        }
    });
});