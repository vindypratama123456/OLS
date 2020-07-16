$(document).ready(function(){
    var datas=[], table1='datatableCustomer', table2='datatableCustomerHasOrder', table3='datatableCustomerHasNoOrder';
    if($('#'+table1).length>0){
        datas['selector'] = table1;
        datas['url'] = BASE_URL+'customer/list_customer';
        datas['columns'] = [
            { 'data': 'npsn' },
            { 'data': 'nama_sekolah' },
            { 'data': 'propinsi' },
            { 'data': 'kabupaten' },
            { 'data': 'telpon' }
        ];
        datas['columnDefs'] = [
            { className: 'text-center', targets: [0] }
        ];
        datas['sort'] = [1,'asc'];
        tableCustomer = myDatatables(datas);
        commonTools(datas['selector'], tableCustomer);
    }
    if($('#'+table2).length>0){
        datas['selector'] = table2;
        datas['url'] = BASE_URL+'customer/list_customer_has_order';
        datas['columns'] = [
            { 'data': 'kode' },
            { 'data': 'npsn' },
            { 'data': 'nama_sekolah' },
            { 'data': 'propinsi' },
            { 'data': 'kabupaten' },
            { 'data': 'kecamatan' },
            { 'data': 'alamat' },
            { 'data': 'telpon' },
            { 'data': 'email' },
            { 'data': 'nama_kepsek' },
            { 'data': 'phone_kepsek' },
            { 'data': 'email_kepsek' },
            { 'data': 'operator' },
            { 'data': 'hp_operator' },
            { 'data': 'email_operator' },
            { 'data': 'tgl_pesan' },
            { 'data': 'total_harga' },
            { 'data': 'status' }
        ];
        datas['columnDefs'] = [
            { className: 'text-center', targets: [0, 1, 15, 17] },
            { className: 'text-right', targets: 16, render: $.fn.dataTable.render.number( ',', '.', 0 ) }
        ];
        datas['sort'] = [15,'asc'];
        tableCustomerHasOrder = myDatatables(datas);
        commonTools(datas['selector'], tableCustomerHasOrder);
    }
    if($('#'+table3).length>0){
        datas['selector'] = table3;
        datas['url'] = BASE_URL+'customer/list_customer_no_order';
        datas['columns'] = [
            { 'data': 'npsn' },
            { 'data': 'nama_sekolah' },
            { 'data': 'propinsi' },
            { 'data': 'kabupaten' },
            { 'data': 'kecamatan' },
            { 'data': 'alamat' },
            { 'data': 'telpon' },
            { 'data': 'email' },
            { 'data': 'nama_kepsek' },
            { 'data': 'phone_kepsek' },
            { 'data': 'email_kepsek' },
            { 'data': 'operator' },
            { 'data': 'hp_operator' },
            { 'data': 'email_operator' }
        ];
        datas['columnDefs'] = [
            { className: 'text-center', targets: 0 }
        ];
        datas['sort'] = [1, 'asc'];
        tableCustomerHasNoOrder = myDatatables(datas);
        commonTools(datas['selector'], tableCustomerHasNoOrder);
    }
    var frmCustomer = $('#customer_form');
    if(frmCustomer.length > 0) {
        frmCustomer.validate({
            onkeyup: function(element) {$(element).valid()},
            onfocusout: function(element) {$(element).valid()},
            ignore: [],
            errorClass: "has-error",
            errorElement: "span",
            rules: {
                no_npsn:{
                    required: true,
                    minlength: 3
                },
                jenjang:{
                    required: true
                },
                school_name:{
                    required: true,
                    minlength: 3
                },
                id_gender:{
                    required: true
                },
                email:{
                    required: true,
                    email: true
                },
                name:{
                    required: true,
                    minlength: 3
                },
                id_group:{
                    required: true
                },
                passwd:{
                    required: true,
                    minlength: 4
                }
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
                    data: frmCustomer.serialize(),
                    dataType: "json",
                    url: frmCustomer.data('action'),
                    beforeSend: function() {
                        $('button').attr('disabled', true);
                    },
                    success:function(datas){
                        if(datas.success==='true') {
                            window.location.href = BASE_URL+'customer';
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
                url: BASE_URL+'customer/delete',
                success:function(datas){
                    window.location.href = BASE_URL+'customer';
                }
            });
            return false;
        }
    });
});