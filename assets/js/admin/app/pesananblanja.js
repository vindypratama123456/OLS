$(document).ready(function(){
    // $("#code_korwil, #code_referral, #bank_account_type").select2();
    var datas = [];
    if ($('#datatableError').length>0){
        datas['selector'] = 'datatableError';
        datas['url'] = BASE_URL + 'pesananblanja/listError';
        datas['columns'] = [
            { 'data': 'po_number' },
            { 'data': 'created_date' },
            { 'data': 'transfered_date' },
            { 'data': 'notes_error' },
            { 'data': 'aksi' }
        ];
        // datas['columnDefs'] = [
        //     { className: 'text-center', targets: [0, 4, 6, 7] }
        // ];
        datas['sort'] = [0, 'asc'];
        datatableError = myDatatables(datas);
        commonTools(datas['selector'], datatableError);
    }

    $('#datatableError').on('click', '#btn-transfer', function (){
        // alert(this.value);
        var conf = confirm('Data akan ditransfer ke OLS, yakin akan diproses ?');
        var data = {
            'po_number' : this.value
        };
            if(conf) {
                $('button').attr('disabled', true);
                $.ajax({
                    type: "POST",
                    data: data,
                    dataType: "json",
                    url:  BASE_URL + 'pesananblanja/listErrorPost',
                    // async: true,
                    // contentType: false,
                    // processData: false,
                    // beforeSend: function() {
                    //     $('#myloader').show();
                    //     $('button').attr('disabled', true);
                    // },
                    success:function(datas){
                        console.log(datas);
                        var linkRedirectTrue = "orders";
                        var linkRedirectFalse = "pesananblanja/getdatasiplah";
                        var linkRedirectError = "pesananblanja/viewerror";
                        if(datas.success == true) {
                            if(datas.error == true)
                            {
                                bootAlertRedirect(datas.message, linkRedirectError);
                            }
                            else
                            {
                                bootAlertRedirect("Berhasil mengimport data dari Siplah ke OLS Buku Sekolah.", linkRedirectTrue);
                            }

                        }
                        else {
                            bootAlertRedirect(datas.message, linkRedirectFalse);
                        }
                        $('input[name=csrftokenbs]').val(datas.csrf_token);
                        // if(datas.success === 'true') {
                        //     window.location.href = BASE_URL+'mitra';
                        // }
                        // else {
                        //     bootAlert(datas.message);
                        //     $('#myloader').hide();
                        //     $('button').attr('disabled', false);
                        //     // window.location.href = BASE_URL+'mitra';
                        // }
                    }
                });
                return false;
            }
    });

    // $('#mitra_form').validate({
    //     errorClass: "has-error",
    //     errorElement: "span",
    //     rules: {
    //         name: {
    //             required: true
    //         },
    //         telp: {
    //             required: true,
    //             digits: true
    //         },
    //         identity_code: {
    //             digits: true,
    //             minlength: 10,
    //             maxlength: 25
    //         },
    //         name_npwp: {
    //             minlength: 2
    //         },
    //         no_npwp: {
    //             digits: true,
    //             minlength: 15,
    //             maxlength: 15
    //         },
    //         address_npwp: {
    //             minlength: 3
    //         },
    //         bank_account_type: {
    //             required: true
    //         },
    //         bank_account_number: {
    //             required: true,
    //             digits: true,
    //             minlength: 6,
    //             maxlength: 25
    //         },
    //         bank_account_name: {
    //             required: true,
    //             minlength: 3,
    //             maxlength: 100
    //         },
    //         percent_comission: {
    //             required: true,
    //             number: true,
    //             min: 0.1,
    //             max: 15
    //         }
    //     },
    //     submitHandler: function(form) {
    //         var conf = confirm('Yakin dengan semua isian data ini?');
    //         if(conf) {
    //             $('button').attr('disabled', true);
    //             $.ajax({
    //                 type: "POST",
    //                 data: new FormData(form),
    //                 dataType: "json",
    //                 url:  $('#mitra_form').data('action'),
    //                 async: true,
    //                 contentType: false,
    //                 processData: false,
    //                 beforeSend: function() {
    //                     $('#myloader').show();
    //                     $('button').attr('disabled', true);
    //                 },
    //                 success:function(datas){
    //                     if(datas.success === 'true') {
    //                         window.location.href = BASE_URL+'mitra';
    //                     }
    //                     else {
    //                         bootAlert(datas.message);
    //                         $('#myloader').hide();
    //                         $('button').attr('disabled', false);
    //                         // window.location.href = BASE_URL+'mitra';
    //                     }
    //                 }
    //             });
    //             return false;
    //         }
    //     }
    // });
});
