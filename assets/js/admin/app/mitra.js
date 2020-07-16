$(document).ready(function(){
    $("#code_korwil, #code_referral, #bank_account_type").select2();
    var datas = [];
    if ($('#datatableMitra').length>0){
        datas['selector'] = 'datatableMitra';
        datas['url'] = BASE_URL + 'mitra/listMitra';
        datas['columns'] = [
            { 'data': 'kode' },
            { 'data': 'identitas' },
            { 'data': 'email' },
            { 'data': 'nama_mitra' },
            { 'data': 'jekel' },
            { 'data': 'alamat_mitra' },
            { 'data': 'telpon' },
            { 'data': 'name_npwp' },
            { 'data': 'no_npwp' },
            { 'data': 'alamat_npwp' },
            { 'data': 'bank_nama' },
            { 'data': 'bank_no' },
            { 'data': 'bank_an' },
            { 'data': 'kode_korwil' },
            { 'data': 'referensi' },
            { 'data': 'aktifasi' },
            { 'data': 'status' },
            { 'data': 'status_kontrak' }
        ];
        datas['columnDefs'] = [
            { className: 'text-center', targets: [0, 4, 6, 7] }
        ];
        datas['sort'] = [0, 'asc'];
        datatableMitra = myDatatables(datas);
        commonTools(datas['selector'], datatableMitra);
    }
    $('#mitra_form').validate({
        errorClass: "has-error",
        errorElement: "span",
        rules: {
            name: {
                required: true
            },
            telp: {
                required: true,
                digits: true
            },
            identity_code: {
                required: true,
                digits: true,
                minlength: 16,
                maxlength: 16,
            },
            name_npwp: {
                required: true,
                minlength: 2
            },
            no_npwp: {
                required: true,
                digits: true,
                minlength: 15,
                maxlength: 15
            },
            address_npwp: {
                required: true,
                minlength: 3
            },
            bank_account_type: {
                required: true
            },
            bank_account_number: {
                required: true,
                digits: true,
                minlength: 6,
                maxlength: 25
            },
            bank_account_name: {
                required: true,
                minlength: 3,
                maxlength: 100
            },
            percent_comission: {
                required: true,
                number: true,
                min: 0.1,
                max: 15
            }
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
                        $('#myloader').show();
                        $('button').attr('disabled', true);
                    },
                    success:function(datas){
                        if(datas.success === 'true') {
                            window.location.href = BASE_URL+'mitra';
                        }
                        else {
                            bootAlert(datas.message);
                            $('#myloader').hide();
                            $('button').attr('disabled', false);
                            // window.location.href = BASE_URL+'mitra';
                        }
                    }
                });
                return false;
            }
        }
    });
});
