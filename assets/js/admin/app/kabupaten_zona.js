$(document).ready(function(){
    var datas=[];
    if($('#datatableKabupatenZona').length>0){
        datas['selector'] = 'datatableKabupatenZona';
        datas['url'] = BASE_URL+'kabupaten_zona/list_kabupaten';
        datas['columns'] = [
            { 'data': 'id' },
            { 'data': 'kabupaten' },
            { 'data': 'id_site' },
            { 'data': 'sd_aktif' },
            { 'data': 'aksi' }
        ];
        datas['columnDefs'] = [
            { className: 'text-center', targets: [0,2,3] }
        ];
        datas['sort'] = [0,'asc'];
        tableKabupatenZona = myDatatables(datas);
        commonTools(datas['selector'], tableKabupatenZona);
    }

    if($('#kabupaten_zona_form').length > 0) {
        $('#kabupaten_zona_form').validate({
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
                    data: $("#kabupaten_zona_form").serialize(),
                    dataType: "json",
                    url: $('#kabupaten_zona_form').data('action'),
                    beforeSend: function() {
                        $('button').attr('disabled', true);
                    },
                    success:function(datas){
                        if(datas.success==='true') {
                            window.location.href = BASE_URL+'kabupaten_zona';
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

    $('#del_data').click(function(){
        console.log('button delete');
        var conf = confirm('Yakin ingin menghapus data ini?');
        if(conf) {
            $.ajax({
                type: "POST",
                data: "id="+$(this).data('id'),
                dataType: "json",
                url: BASE_URL+'kabupaten_zona/delete',
                success:function(datas){
                    window.location.href = BASE_URL+'kabupaten_zona';
                }
            });
            return false;
        }
    });
});