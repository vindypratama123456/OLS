$(document).ready(function(){
    // $("#code_korwil, #code_referral, #bank_account_type").select2();
    var datas = [];

    if ($('#datatableMitraKontrak').length>0){
        datas['selector'] = 'datatableMitraKontrak';
        datas['url'] = BASE_URL + 'kontrak/listKontrak';
        datas['columns'] = [
            { 'data': 'code' },
            { 'data': 'name' },
            { 'data': 'email' },
            { 'data': 'mikon_tanggal' },
            { 'data': 'mikon_tanggal_akhir' },
            // { 'data': 'mikon_periode' },
            { 'data': 'mikon_file' },
            { 'data': 'status_kontrak' }
        ];
        datas['columnDefs'] = [
            { className: 'text-center', targets: [0, 3] }
        ];
        datas['sort'] = [0, 'asc'];
        datatableMitraKontrak = myDatatables(datas);
        commonTools(datas['selector'], datatableMitraKontrak);
    }

    $('#kontrak_form').validate({
        errorClass: "has-error",
        errorElement: "span",
        rules: {
            mikon_tanggal: {
                required: true
            },
            mikon_periode: {
                required: true,
                digits: true
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
                    url:  $('#kontrak_form').data('action'),
                    async: true,
                    contentType: false,
                    processData: false,
                    beforeSend: function() {
                        $('#myloader').show();
                        $('button').attr('disabled', true);
                        $('img').EZView();
                    },
                    success:function(datas){
                        if(datas.success === 'true') {
                            window.location.href = BASE_URL+'kontrak';
                        }
                        else 
                        {
                            // bootAlert(datas.message);
                            // $('#myloader').hide();
                            // $('button').attr('disabled', false);
                            // // window.location.href = BASE_URL+'kontrak';
                            
                            bootAlertRedirect(datas.message, false);
                            $('#myloader').hide();
                            $('button').attr('disabled', false);
                        }
                    }
                });
                return false;
            }
        }
    });


    // $(document).on('click','.testing', function(){
    //     $('img').EZView();
    // });
    
});
