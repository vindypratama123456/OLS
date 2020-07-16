$(document).ready(function(){
    $("#code_korwil, #code_referral, #bank_account_type").select2();
    var datas = [];
    if ($('#datatableEmailBlacklist').length>0){
        datas['selector'] = 'datatableEmailBlacklist';
        datas['url'] = BASE_URL + 'emailblacklist/listEmail';
        datas['columns'] = [
            { 'data': 'email' },
            { 'data': 'aksi' }
        ];
        datas['columnDefs'] = [
            { className: 'text-center', targets: [1] }
        ];
        datas['sort'] = [0, 'asc'];
        datatableEmailBlacklist = myDatatables(datas);
        commonTools(datas['selector'], datatableEmailBlacklist);
    }

    $('#datatableEmailBlacklist tbody').on('click','td #btnDelete', function(){
        // alert($(this).data('value'));

        var data = {
            'id' : $(this).data('value'),
        };
        var conf = confirm("Anda yakin akan menghapus data ini ?");
        if(conf)
        {
            console.log(data);
                $.ajax({
                    type: "POST",
                    data: data,
                    dataType: "json",
                    url:  BASE_URL+'Emailblacklist/delete',
                    async: true,
                    beforeSend: function() {
                        $('#myloader').show();
                        $('button').attr('disabled', true);
                    },
                    success:function(datas){
                        if(datas.success === 'true') {
                            window.location.href = BASE_URL+'Emailblacklist';
                        }
                        else {
                            bootAlert(datas.message);
                            $('#myloader').hide();
                            $('button').attr('disabled', false);
                        }
                    }
                });
        }
    });

    $('#email_blacklist_form').validate({
        errorClass: "has-error",
        errorElement: "span",
        rules: {
            email: {
                required: true,
                email: true,
                remote: {
                    type: 'POST',
                    url: BASE_URL+'emailblacklist/checkDupplicateValidation',
                    data: {
                        values: function(){
                            return $('#email').val();
                        },
                        table: 'email_blacklist',
                        select: 'email'
                    }
                }
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
                    url:  $('#email_blacklist_form').data('action'),
                    async: true,
                    contentType: false,
                    processData: false,
                    beforeSend: function() {
                        $('#myloader').show();
                        $('button').attr('disabled', true);
                    },
                    success:function(datas){
                        if(datas.success === 'true') {
                            window.location.href = BASE_URL+'emailblacklist';
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
