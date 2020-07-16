$(document).ready(function(){
    var datas = [];
    if ($('#datatableAllUsers').length>0){
        datas['selector'] = 'datatableAllUsers';
        datas['url'] = BASE_URL + 'sekolahprospect/listAllUsers';
        datas['columns'] = [
            { 'data': 'npsn' },
            { 'data': 'nama_sekolah' },
            { 'data': 'propinsi' },
            { 'data': 'kabupaten' },
            { 'data': 'status_prospek' },
            { 'data': 'nama_mitra' },
            { 'data': 'tgl_expired' }
        ];
        datas['columnDefs'] = [
            { className: 'text-center', targets: [0, 4, 6] }
        ];
        datas['sort'] = [1, 'asc'];
        datatableAllUsers = myDatatables(datas);
        commonTools(datas['selector'], datatableAllUsers);
    }
    if ($('#datatableListRequest').length>0){
        var userCode = $('#user_code').val();
        datas['selector'] = 'datatableListRequest';
        datas['url'] = BASE_URL + 'sekolahprospect/listRequest/' + userCode;
        datas['columns'] = [
            { 'data': 'npsn' },
            { 'data': 'nama_sekolah' },
            { 'data': 'propinsi' },
            { 'data': 'kabupaten' },
            { 'data': 'status_prospek' },
            { 'data': 'nama_mitra' },
            { 'data': 'tgl_awal' },
            { 'data': 'tgl_akhir' },
            { 'data': 'notes' }
        ];
        datas['columnDefs'] = [
            { className: 'text-center', targets: [0, 4, 6, 7, 8] }
        ];
        datas['sort'] = [6, 'asc'];
        datatableListRequest = myDatatables(datas);
        commonTools(datas['selector'], datatableListRequest);
    }
    var today = new Date();
    $("#datetimepicker_startdate").datetimepicker({
        format: 'YYYY-MM-DD',
        minDate : 'now',
        maxDate : maxDate(today),
    });
    $("#datetimepicker_accdate").datetimepicker({
        format: 'YYYY-MM-DD',
        minDate : 'now',
        maxDate : maxDate(today),
    });
});
function maxDate(date) {
    var d = new Date(date),
        month = '' + (d.getMonth() + 2),
        day = '' + d.getDate(),
        year = d.getFullYear();
    if (month.length < 2) month = '0' + month;
    if (day.length < 2) day = '0' + day;
    return [year, month, day].join('-');
}
function approveRequest()
{
    var id = [];
    $("input[type=checkbox]:checked").each(function(){
        id.push($(this).val());
    });
    id = id.join();
    if(id == ""){
        $('.messages').html("Anda tidak memililh permintaan mitra, silahkan klik batal dan pilih permintaan!");
        $('.action').hide();
    }
    else{
        $("#id").val(id);
        $('.action').show();
        $(".messages").html('Apakah anda yakin untuk melanjutkan proses meyetujui permintaan mitra ini dengan durasi 7 hari?');
    }
}
$("#form_request_sales").validate({
    errorClass: "has-error",
    errorElement: "div",
    rules: {
        req_startdate: {
            required: true
        }
    },
    highlight: function (element, errorClass, validClass){
        var elem = $(element);
        elem.parents(".form-group").addClass(errorClass);
        elem.addClass(errorClass);
    },
    unhighlight: function (element, errorClass, validClass){
        var elem = $(element);
        elem.parents(".has-error").removeClass(errorClass);
        elem.removeClass(errorClass);
    },
    errorPlacement: function(error, element) {
        if(element.parent('.input-group').length) {
            error.insertAfter(element.parent());
        } else {
            error.insertAfter(element);
        }
    },
    submitHandler: function(form){
        var conf = confirm('Yakin melanjutkan pengajuan prospek?');
        if (conf) {
            $('button').attr('disabled', true);
            $.ajax({
                type: "POST",
                data: new FormData(form),
                dataType: "json",
                url:  $('#form_request_sales').data('action'),
                async: true,
                contentType: false,
                processData: false,
                beforeSend: function() {
                    $('#myloader').show();
                    $('button').attr('disabled', true);
                },
                success:function(datas){
                    if (datas.success === true) {
                        $('button').attr('disabled', false);
                        window.location.href = BASE_URL+'sekolahprospect/detail/'+$("#id_customer").val();
                    } else if (datas.success === false) {
                        bootAlert(datas.message);
                        $('#myloader').hide();
                        $('button').attr('disabled', false);
                        window.location.href = BASE_URL+'sekolahprospect/detail/'+$("#id_customer").val();
                    }
                }
            });
            return false;
        }
    }
});
$("#form_accepted_korwil").validate({
    errorClass: "has-error",
    errorElement: "div",
    rules: {
        acc_days: {
            required: true,
            number: true
        }
    },
    highlight: function (element, errorClass, validClass){
        var elem = $(element);
        elem.parents(".form-group").addClass(errorClass);
        elem.addClass(errorClass);
    },
    unhighlight: function (element, errorClass, validClass){
        var elem = $(element);
        elem.parents(".has-error").removeClass(errorClass);
        elem.removeClass(errorClass);
    },
    submitHandler: function(form){
        var conf = confirm('Yakin menyetujui pengajuan prospek sales?');
        if (conf) {
            $('button').attr('disabled', true);
            $.ajax({
                type: "POST",
                data: new FormData(form),
                dataType: "json",
                url:  $('#form_accepted_korwil').data('action'),
                async: true,
                contentType: false,
                processData: false,
                beforeSend: function() {
                    $('#myloader').show();
                    $('button').attr('disabled', true);
                },
                success:function(datas){
                    if (datas.success === true) {
                        $('#myloader').hide();
                        $('button').attr('disabled', false);
                        window.location.href = BASE_URL+'sekolahprospect/detail/'+$("#id_customer").val();
                    } else if (datas.success === false) {
                        bootAlert(datas.message);
                        $('#myloader').hide();
                        $('button').attr('disabled', false);
                        window.location.href = BASE_URL+'sekolahprospect/detail/'+$("#id_customer").val();
                    }
                }
            });
            return false;
        }
    }
});
