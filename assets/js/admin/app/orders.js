(function( $ ){
   $.fn.mySuggest = function(e) {
        $(this).autocomplete({
            minLength:1,
            delay:0,
            source:BASE_URL+'orders/autoComplete/'+e+'/'+$(this).val(),
            select:function(event, ui){
                $(this).val(ui.item.output);
            }
        });
   }; 
})( jQuery );
function setAllQty(kelas){
    var qty = $('#setAllQty'+kelas).val();
    if(qty === 0 || qty === null) {
        bootAlert('Mohon masukkan jumlah yang valid!');
        $('#setAllQty'+kelas).focus();
    }
    else {
        $('.jumlah_buku'+kelas).val(qty);
    }
}
function setAllNull(){
    $('.setAllQty').val(0);
    $('.qty').val(0);
}

/* Fungsi formatRupiah */
function formatRupiah(angka, prefix){
    var number_string = angka.replace(/[^,\d]/g, '').toString(),
    split           = number_string.split(','),
    sisa            = split[0].length % 3,
    rupiah          = split[0].substr(0, sisa),
    ribuan          = split[0].substr(sisa).match(/\d{3}/gi);
 
    // tambahkan titik jika yang di input sudah menjadi angka ribuan
    if(ribuan){
        separator = sisa ? '.' : '';
        rupiah += separator + ribuan.join('.');
    }
 
    rupiah = split[1] != undefined ? rupiah + ',' + split[1] : rupiah;
    return prefix == undefined ? rupiah : (rupiah ? 'Rp. ' + rupiah : '');
}

$(document).ready(function(){
    var datas = [], elRegProp='#reg_provinsi', elRegKab='#reg_kabupaten', frmOrder='#orders_form', frmTugaskanSales='#formtugaskansales', frmSchool='#school_form', frmCekNPSN='#form_cek_npsn', frmOfflineBooks='#offline_books_form';
    $('#reg_provinsi, #reg_kabupaten, #reg_kecamatan, #emailsales').select2();
    if($('#datatableOnline').length>0){
        datas['selector'] = 'datatableOnline';
        datas['url'] = BASE_URL+'orders/list_orders';
        datas['columns'] = [
            { 'data': 'kode' },
            { 'data': 'nama_sekolah' },
            { 'data': 'propinsi' },
            { 'data': 'kabupaten' },
            { 'data': 'kecamatan'  },
            { 'data': 'kelas' },
            { 'data': 'tipe'  },
            { 'data': 'semester'  },
            { 'data': 'tgl_pesan' },
            { 'data': 'total_harga', render: $.fn.dataTable.render.number( ',', '.', 0 ) },
            { 'data': 'status' },
            { 'data': 'mitra' },
            { 'data': 'reference_other_from' },
            { 'data': 'reference_other' },
            { 'data': 'no_npsn' },
        ];
        datas['columnDefs'] = [
            { className: "text-center", targets: [0, 4, 5, 6, 8] },
            { className: "text-right", targets: 8 }
        ];
        datas['sort'] = [8, 'desc'];
        datatableOnline = myDatatables(datas);
        commonTools(datas['selector'], datatableOnline);
    }
    if($('#datatableOffline').length>0){
        datas['selector'] = 'datatableOffline';
        datas['url'] = BASE_URL+'orders/list_orders/2';
        datas['columns'] = [
            { 'data': 'kode' },
            { 'data': 'nama_sekolah' },
            { 'data': 'propinsi' },
            { 'data': 'kabupaten' },
            { 'data': 'kelas' },
            { 'data': 'tipe'  },
            { 'data': 'tgl_pesan' },
            { 'data': 'total_harga', render: $.fn.dataTable.render.number( ',', '.', 0 ) },
            { 'data': 'status' }
        ];
        datas['columnDefs'] = [
            { className: "text-center", targets: [0, 4, 5, 6, 8] },
            { className: "text-right", targets: 7 }
        ];
        datas['sort'] = [6, 'desc'];
        datatableOffline = myDatatables(datas);
        commonTools(datas['selector'], datatableOffline);
    }
    $(frmOrder).validate({
        errorClass: 'has-error',
        errorElement: 'span',
        rules: {
            jangka_waktu:{
                required: true,
                number: true
            },
            kesepakatan_sampai:{
                required: true,
                number: true
            },
            is_intan:{
                required: true
            },
            
            tgl_sampai: {
                required: true
            },
            nama_penerima:{
                required: true
            },
            tgl_terima: {
                required: true
            },
            nomor_surat:{
                required: true
            },
            tanggal_surat:{
                required: true
            },
            tgl_bayar: {
                required: true
            },
            nama_bank: {
                required: true
            },
            nama_pembayar: {
                required: true
            },
            jumlah_bayar:{
                required: true
            }
        },
        highlight: function (element, errorClass, validClass) {
            var elem = $(element);
            elem.parents('.form-group').addClass(errorClass);
            elem.addClass(errorClass);
        }, 
        unhighlight: function (element, errorClass, validClass) {
            var elem = $(element);
            elem.parents('.has-error').removeClass(errorClass); 
            elem.removeClass(errorClass);
        },
        submitHandler: function(form) {
            bootbox.confirm({
                title: 'Konfirmasi',
                message: 'Yakin ingin memperbarui STATUS pesanan ini?',
                callback: function (result) {
                    if (result) {
                        $('button').attr('disabled', true);
                        $.ajax({
                            type: 'POST',
                            data: new FormData(form),
                            dataType: 'json',
                            url:  $(frmOrder).data('action'),
                            async: true,
                            contentType: false,
                            processData: false,
                            beforeSend: function() {
                                $('.bootbox').modal('hide').data('bs.modal', null);
                                $('#myloader').show();
                                $('button').attr('disabled', true);
                            },
                            success:function(datas){
                                if(datas.success === 'true') {
                                    window.location.href = BASE_URL+'orders';
                                }
                                else {
                                    bootAlert(datas.message);
                                    $('#myloader').hide();
                                    $('button').attr('disabled', false);
                                }
                            }
                        });
                        return false;
                    }
                }
            });
        }
    });
    $(frmTugaskanSales).validate({
        errorClass: 'has-error',
        errorElement: 'span',
        rules: {
            emailsales:{
                required: true
            }
        },
        highlight: function (element, errorClass, validClass) {
            var elem = $(element);
            elem.parents('.form-group').addClass(errorClass);
            elem.addClass(errorClass);
        }, 
        unhighlight: function (element, errorClass, validClass) {
            var elem = $(element);
            elem.parents('.has-error').removeClass(errorClass); 
            elem.removeClass(errorClass);
        },
        submitHandler: function(form) {
            bootbox.confirm({
                title: 'Konfirmasi',
                message: 'Yakin sudah benar dengan sales yang anda pilih?',
                callback: function (result) {
                    if (result) {
                        $('button').attr('disabled', true);
                        $.ajax({
                            type: 'POST',
                            data: $(frmTugaskanSales).serialize(),
                            dataType: 'json',
                            url:  $(frmTugaskanSales).data('action'),
                            async: true,
                            beforeSend: function() {
                                $('.bootbox').modal('hide').data('bs.modal', null);
                                $('#myloader').show();
                                $('button').attr('disabled', true);
                            },
                            success:function(datas){
                                if(datas.success === 'true') {
                                    window.location.href = BASE_URL+'orders';
                                }
                                else {
                                    bootAlert(datas.message);
                                    $('#myloader').hide();
                                    $('button').attr('disabled', false);
                                }
                            }
                        });
                        return false;
                    }
                }
            });
        }
    });

    var path_name = window.location.pathname;
    var n = path_name.indexOf("detail");
    if(n > 0)
    {
        var current_state = $(this).val();
        var id_cust = '';
        id_cust = document.getElementById('id_customer').value;

        // alert("id customer : " + id_cust);
        // alert($('#hidden_persetujuan_keterangan').val());
        
        $.ajax({
            type : "POST",
            url  : BASE_URL + 'orders/checkStatusBayar',
            dataType : "json",
            data : {id_customer:id_cust},
            success: function(result){
                // Kode untuk memberitahukan bahwa pembayaran transaksi sebelumnya dalam jangka waktu lebih dari 180 hari (6 bulan) belum lunas
                if(result.length > 0)
                {
                    var nilai_piutang_temp1 = result[0].nilai_piutang;
                    var nilai_piutang_temp2 = nilai_piutang_temp1.replace(',','');
                    var nilai_piutang_var = nilai_piutang_temp2.replace('.',',');

                    if($('#hidden_persetujuan_keterangan').val() == '' || $('#hidden_persetujuan_keterangan').val() == null)
                    {
                        alert('Sekolah belum melunasi pesanan sebelumnya dengan nilai piutang ' + formatRupiah(nilai_piutang_var, 'Rp. ') + '. Pesanan ini tidak dapat diproses.');
                    }

                    /**
                     * vindy 2019-06-12
                     * Jika belum melunasi pembayaran transaksi sebelumnya dalam jangkan waktu lebih dari 180 hari (6 bulan), maka :
                     * attribut pilihan status berubah menjadi readonly
                     * Tampilkan input text keterangan persetujuan
                     * Awal
                     */
                    $('#id_order_state').attr("readonly", "readonly");
                    $('#div-persetujuan').show();
                    /**
                     * Akhir
                     */
                    
                    /**
                     * vindy 2019-06-12
                     * Fungsi untuk mengubah attribut select option
                     * jika belum ada keterangan, attribut readonly
                     * jika sudah ada keterangan, attribut writable 
                     */
                    if($('#hidden_persetujuan_keterangan').val() == '' || $('#hidden_persetujuan_keterangan').val() == null)
                    {
                        $('#id_order_state').attr("readonly", "readonly");
                        $('#div-keterangan').hide();
                    }
                    else
                    {
                        $('#id_order_state').removeAttr("readonly");
                        $('#div-keterangan').show();
                    }
                }
            }
        });
    }

    // VINDY 2019-06-11
    // Fungsi pada input keterangan, 
    // Jika telah melunasi pembayaran 6 bulan sebelumnya, 
    // jika kosong, attribut pilihan status berubah menjadi readonly, sembunyikan form referer
    // jika berisi, aktifkan pilihan status berubah menjadi writable
    // Awal
    // To do : Check untuk user EC dan RSM jike pembayaran belum lunas dan sudah lunas
    $('#persetujuan_keterangan').on('input',function(){
        if($('#persetujuan_keterangan').val() == '' || $('#persetujuan_keterangan').val() == null)
        {
            if($('#id_order_state').val()==5)
            {
                $('#id_order_state').val('5').trigger('change.select2');
                $('#id_order_state').attr("readonly", "readonly");
                $('#div-referer').hide();
            }
            else
            {
                $('#id_order_state').val('1').trigger('change.select2');
                $('#id_order_state').attr("readonly", "readonly");
                $('#div-referer').hide();
            }
        }
        else
        {
            $('#id_order_state').removeAttr("readonly");
        }
    });
    // Akhir 

    $('#id_order_state').change(function() {
        var current_state = $(this).val();

        if(current_state === '3') {
            $('#div-referer').show();
        }
        else {
            $('#div-referer').hide();
        }
        if(current_state === '5') {
            $('#div-logistik').show();
        }
        else {
            $('#div-logistik').hide();
        }
        if(current_state === '6') {
            $('#div-jangka-waktu').show();
        }
        else {
            $('#div-jangka-waktu').hide();
        }
        if(current_state === '7') {
            $('#div-sampai').show();
        }
        else {
            $('#div-sampai').hide();
        }
        if(current_state === '8') {
            $('#div-penerima').show();
        }
        else {
            $('#div-penerima').hide();   
        }
        if(current_state === '9') {
            $('#div-bayar').show();
        }
        else {
            $('#div-bayar').hide();
        }
    });
    $(frmSchool).validate({
        errorClass: 'has-error',
        errorElement: 'span',
        rules: {
            reg_no_npsn:{
                number: true,
                minlength: 8,
                maxlength: 8,
                required: true
            },
            reg_school_name:{
                required: true,
                minlength: 3
            },
            reg_jenjang: {
                required: true
            },
            reg_zona:{
                required: true
            },
            reg_user_k13: {
                required: true
            },
            reg_provinsi:{
                required: true
            },
            reg_kabupaten:{
                required: true
            },
            reg_kecamatan:{
                minlength: 1
            },
            reg_desa:{
                minlength: 3
            },
            reg_alamat:{
                required: true,
                minlength: 5
            },
            reg_kodepos:{
                minlength: 5,
                maxlength: 5,
                number: true
            },
            reg_email: {
                email: true
            },
            reg_kepsek_name: {
                required: true,
                minlength: 3
            },
            reg_kepsek_phone: {
                required: true,
                minlength: 6
            },
            reg_kepsek_email: {
                email: true
            },
            reg_operator_name: {
                minlength: 3
            },
            reg_operator_email: {
                email: true,
                required: true
                // remote: {
                //     type: 'POST',
                //     url: BASE_URL+'orders/checkDupplicateValidation',
                //     data: {
                //         values: function(){
                //             return $('#reg_operator_email').val();
                //         },
                //         table: 'customer',
                //         select: 'email_operator'
                //     }
                // }
            },
            reg_operator_phone: {
                minlength: 6
            }
        },
        errorPlacement: function(error, element) {
            if(element.attr('id') === 'reg_provinsi' || element.attr('id') === 'reg_kabupaten' || element.attr('id') === 'reg_kecamatan') {
                element.parent().append(error);
            } else {
                error.insertAfter(element);
            }
        },
        highlight: function (element, errorClass) {
            var elem = $(element);
            elem.parents('.form-group').addClass(errorClass);
            elem.addClass(errorClass);
        }, 
        unhighlight: function (element, errorClass) {
            var elem = $(element);
            elem.parents('.has-error').removeClass(errorClass); 
            elem.removeClass(errorClass);
        },
        submitHandler: function(form) {
            bootbox.confirm({
                title: 'Konfirmasi',
                message: 'Yakin semua isian sudah benar?',
                callback: function (result) {
                    if (result) {
                        $('button').attr('disabled', true);
                        $.ajax({
                            type: 'POST',
                            data: $(frmSchool).serialize(),
                            dataType: 'json',
                            url:  $(frmSchool).data('action'),
                            async: true,
                            beforeSend: function() {
                                $('.bootbox').modal('hide').data('bs.modal', null);
                                $('#myloader').show();
                                $('button').attr('disabled', true);
                            },
                            success:function(datas){
                                if(datas.success === 'true') {
                                    window.location.href = BASE_URL+datas.redirect;
                                }
                                else {
                                    bootAlert(datas.message);
                                    $('#myloader').hide();
                                    $('button').attr('disabled', false);
                                }
                            }
                        });
                        return false;
                    }
                }
            });
        }
    });
    $(frmCekNPSN).validate({
        errorClass: 'has-error',
        errorElement: 'span',
        rules: {
            cek_no_npsn:{
                number: true,
                minlength: 8,
                maxlength: 8,
                required: true
            }
        },
        highlight: function (element, errorClass) {
            var elem = $(element);
            elem.parents('.form-group').addClass(errorClass);
            elem.addClass(errorClass);
        }, 
        unhighlight: function (element, errorClass) {
            var elem = $(element);
            elem.parents('.has-error').removeClass(errorClass); 
            elem.removeClass(errorClass);
        },
        submitHandler: function(form) {
            $('#messages').addClass('display_none');
            $.ajax({
                type: 'POST',
                data: $(frmCekNPSN).serialize(),
                dataType: 'json',
                url:  $(frmCekNPSN).data('action'),
                async: true,
                success:function(datas){
                    if (datas.is_exist > 0) {
                        $('#messages').removeClass('display_none');
                    } else {
                        $('#messages').addClass('display_none');
                    }
                }
            });
            return false;
        }
    });
    $(frmOfflineBooks).submit(function(e){
        e.preventDefault();
        var count = $('.qty').filter(function(){ return $(this).val(); }).length;
        if(count>0) {
            bootbox.confirm({
                title: 'Konfirmasi',
                message: 'Yakin dengan semua isi pesanan buku?',
                callback: function (result) {
                    if (result) {
                        $('button').attr('disabled', true);
                        $.ajax({
                            type: 'POST',
                            data: $(frmOfflineBooks).serialize(),
                            dataType: 'json',
                            url: BASE_URL+'orders/offlineBooksPost',
                            beforeSend: function() {
                                $('.bootbox').modal('hide').data('bs.modal', null);
                                $('#myloader').show();
                                $('button').attr('disabled', true);
                            },
                            success:function(datas){
                                if(datas.success === 'true') {
                                    window.location = BASE_URL+datas.redirect;
                                }
                                else {
                                    bootAlert(datas.message);
                                    $('#myloader').hide();
                                    $('button').attr('disabled', false);
                                }
                            }
                        });
                        return false;
                    }
                }
            });
        }
        else {
          bootAlert('Mohon masukkan jumlah yang diinginkan!');
        }
    });
    $(elRegProp).mySuggest('provinsi');
    $(elRegKab).mySuggest('kabupaten');
    $('#reg_kecamatan').mySuggest('kecamatan');
    $('#reg_desa').mySuggest('desa');
    $(elRegProp).on('change', function(){
        $('#reg_kecamatan').empty().append('<option value="">- Pilih Kecamatan -</option>');
        if ($(this).val() === '') {
            $('#reg_kabupaten').empty().append('<option value="">- Pilih Kabupaten / Kota -</option>');
        } else {
            $('#reg_kabupaten').empty().append('<option value="">- Pilih Kabupaten / Kota -</option>');
            var data = {
                provinsi: $(this).val()
            };
            $.ajax({
                url: BASE_URL + 'orders/getKabupatenByProvinsi',
                data: data,
                dataType: 'json',
                type: 'POST',
                async: true,
                success: function(data) {
                    if (data.success === true) {
                        $.each(data.row, function (i, item) {
                            $('#reg_kabupaten').append($('<option>', {
                                value: item.kabupaten,
                                text : item.kabupaten
                            }));
                        });
                    } else {
                        $('#reg_kabupaten').empty().append('<option value="">- Pilih Kabupaten / Kota -</option>');
                    }
                }
            });
        }
    });
    $(elRegKab).on('change', function(){
        if ($(this).val() === '') {
            $('#reg_kecamatan').empty().append('<option value="">- Pilih Kecamatan -</option>');
        } else {
            $('#reg_kecamatan').empty().append('<option value="">- Pilih Kecamatan -</option>');
            var data = {
                kabupaten: $(this).val()
            };
            $.ajax({
                url: BASE_URL + 'orders/getKecamatanByKabupaten',
                data: data,
                dataType: 'json',
                type: 'POST',
                async: true,
                success: function(data) {
                    if (data.success === true) {
                        $.each(data.row, function (i, item) {
                            $('#reg_kecamatan').append($('<option>', {
                                value: item.kecamatan,
                                text : item.kecamatan
                            }));
                        });
                    } else {
                        $('#reg_kecamatan').empty().append('<option value="">- Pilih Kecamatan -</option>');
                    }
                }
            });
        }
    });
    $('#log_gramedia').prop('checked', true);
    $('#log_gramedia').on('click', function () {
        $(this).prop('checked', true);
        $('#log_intan').prop('checked', false);
    });
    $('#log_intan').on('click', function () {
        $(this).prop('checked', true);
        $('#log_gramedia').prop('checked', false);
    });

    // if($('#datatableOnlineFilter').length>0){
    //     datas['selector'] = 'datatableOnlineFilter';
    //     datas['url'] = BASE_URL+'orders/list_filter';
    //     datas['columns'] = [
    //         { 'data': 'kode' },
    //         { 'data': 'nama_sekolah' },
    //         { 'data': 'propinsi' },
    //         { 'data': 'kabupaten' },
    //         { 'data': 'kecamatan'  },
    //         { 'data': 'kelas' },
    //         { 'data': 'tipe'  },
    //         { 'data': 'semester'  },
    //         { 'data': 'tgl_pesan' },
    //         { 'data': 'total_harga', render: $.fn.dataTable.render.number( ',', '.', 0 ) },
    //         { 'data': 'status' },
    //         { 'data': 'mitra' },
    //         { 'data': 'reference_other_from' },
    //         { 'data': 'reference_other' },
    //         { 'data': 'no_npsn' },
    //     ];
    //     datas['columnDefs'] = [
    //         { className: "text-center", targets: [0, 4, 5, 6, 8] },
    //         { className: "text-right", targets: 8 }
    //     ];
    //     datas['sort'] = [8, 'desc'];
    //     datatableOnline = myDatatables(datas);
    //     commonTools(datas['selector'], datatableOnline);
    // }

    if($('#datatableOnlineFilter').length>0){
        $('#datatableOnlineFilter').DataTable().clear().destroy();

        datas['selector'] = 'datatableOnlineFilter';
        datatableOnline = emptyDatatables(datas)
        commonTools(datas['selector'], datatableOnline);
    }

    $('#dtPicker1').datetimepicker({
        format: 'YYYY-MM-DD'
    }).on('dp.change', function (e) {
        $('#dtPicker2').data('DateTimePicker').minDate(e.date);
    });
    $('#dtPicker2').datetimepicker({
        useCurrent: false, //Important! See issue #1075
        format: 'YYYY-MM-DD'
    }).on('dp.change', function (e) {
        $('#dtPicker1').data('DateTimePicker').maxDate(e.date);
    });

    $('#startDate').on("blur", function(){
        if($('#startDate').val() != "" || $('#endDate').val() != "")
        {
            $("#txtCari").val("");
            $("#txtCari").attr("disabled", true);
        }
        else
        {
            $("#txtCari").attr("disabled", false);
        }
    });
    $('#endDate').on("blur", function(){
        if($('#startDate').val() != "" || $('#endDate').val() != "")
        {
            $("#txtCari").val("");
            $("#txtCari").attr("disabled", true);
        }
        else
        {
            $("#txtCari").attr("disabled", false);
        }
    });
    $('#startDate').on("keyup", function(){
        if($('#startDate').val() == "" && $('#endDate').val() == "")
        {
            $("#txtCari").attr("disabled", false);
        }
    });
    $('#endDate').on("keyup", function(){
        if($('#startDate').val() == "" && $('#endDate').val() == "")
        {
            $("#txtCari").attr("disabled", false);
        }
    });
    
    $("#txtCari").on("keyup", function(){
        if($("#txtCari").val() == "")
        {
            $("#startDate").attr('disabled', false);
            $("#endDate").attr('disabled', false);
        }
        else
        {
            $("#startDate").val("");
            $("#endDate").val("");
            $("#startDate").attr('disabled', true);
            $("#endDate").attr('disabled', true);
        }
    });

    $('#btn_search').on("click", function(){
        var startDate = $('#startDate').val();
        var endDate = $('#endDate').val();
        var cari = $('#txtCari').val();

        // alert(start_date + " | " + end_date + " | " + cari);
        // return false;
        if($('#datatableOnlineFilter').length>0){
            $('#datatableOnlineFilter').DataTable().clear().destroy();
            datas['selector'] = 'datatableOnlineFilter';
            datas['url'] = BASE_URL+'orders/list_filter';
            datas['columns'] = [
            { 'data': 'kode' },
            { 'data': 'nama_sekolah' },
            { 'data': 'propinsi' },
            { 'data': 'kabupaten' },
            { 'data': 'kecamatan'  },
            { 'data': 'kelas' },
            { 'data': 'tipe'  },
            { 'data': 'semester'  },
            { 'data': 'tgl_pesan' },
            { 'data': 'total_harga', render: $.fn.dataTable.render.number( ',', '.', 0 ) },
            { 'data': 'status' },
            { 'data': 'mitra' },
            { 'data': 'reference_other_from' },
            { 'data': 'reference_other' },
            { 'data': 'no_npsn' },
            ];
            datas['columnDefs'] = [
            { className: "text-center", targets: [0, 4, 5, 6, 8] },
            { className: "text-right", targets: 8 }
            ];
            datas['sort'] = [8, 'desc'];
            datatableOnline = myDatatables(datas, startDate, endDate, cari);
            commonTools(datas['selector'], datatableOnline);
        }
    });
});
