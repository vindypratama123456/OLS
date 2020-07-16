
<script type="text/javascript" src="<?php echo assets_url_backmin('js'); ?>/plugins/bootstrap/bootstrap-select.js"></script>
<script type="text/javascript" src="<?php echo assets_url_backmin('js'); ?>/plugins/datatables/jquery.dataTables.min.js"></script>
<script type="text/javascript" src="<?php echo assets_url_backmin('js'); ?>/plugins/tableexport/tableExport.js"></script>
<script type="text/javascript" src="<?php echo assets_url_backmin('js'); ?>/plugins/tableexport/jquery.base64.js"></script>
<script type="text/javascript" src="<?php echo assets_url_backmin('js'); ?>/plugins/tableexport/html2canvas.js"></script>
<script type="text/javascript" src="<?php echo assets_url_backmin('js'); ?>/plugins/tableexport/jspdf/libs/sprintf.js"></script>
<script type="text/javascript" src="<?php echo assets_url_backmin('js'); ?>/plugins/tableexport/jspdf/jspdf.js"></script>
<script type="text/javascript" src="<?php echo assets_url_backmin('js'); ?>/plugins/tableexport/jspdf/libs/base64.js"></script>  
<script type="text/javascript">
    var datatableProduct = {
        url: "<?php echo base_url().BACKMIN_PATH.'/product/listProduct'; ?>",
        columns: [
            { 'data': 'kode_buku' },
            { 'data': 'reference' },
            { 'data': 'category' },
            { 'data': 'name' },
            { 'data': 'image' },
            { 'data': 'description' },
            { 'data': 'supplier' },
            { 'data': 'quantity' },
            { 'data': 'price_1' },
            { 'data': 'price_2' },
            { 'data': 'price_3' },
            { 'data': 'price_4' },
            { 'data': 'price_5' },
            { 'data': 'non_r1' },
            { 'data': 'non_r2' },
            { 'data': 'non_r3' },
            { 'data': 'non_r4' },
            { 'data': 'non_r5' },
            { 'data': 'width' },
            { 'data': 'height' },
            { 'data': 'weight' },
            { 'data': 'pages' },
            { 'data': 'capacity' },
            { 'data': 'active' },
            { 'data': 'enable' },
            { 'data': 'date_add' },
            { 'data': 'date_upd' }
        ],
        columnDefs: [
            { className: 'text-center', targets: [0, 4, 6, 7] }
        ],
        sort: [0,'asc']
    };

$(document).ready(function(){
    $('#product_form').validate({
        errorClass: "has-error",
        errorElement: "span",
        rules: {            
            quantity: {
                required: true,
                number: true
            }
            ,price_1: {
                required: true,
                number: true
            }
            ,price_2: {
                required: true,
                number: true
            }
            ,price_3: {
                required: true,
                number: true
            }
            ,price_4: {
                required: true,
                number: true
            }
            ,price_5: {
                required: true,
                number: true
            }
            ,non_r1: {
                required: true,
                number: true
            }
            ,non_r2: {
                required: true,
                number: true
            }
            ,non_r3: {
                required: true,
                number: true
            }
            ,non_r4: {
                required: true,
                number: true
            }
            ,non_r5: {
                required: true,
                number: true
            }
            ,width: {
                required: true,
                number: true
            }
            ,height: {
                required: true,
                number: true
            }
            ,weight: {
                required: true,
                number: true
            }
            ,pages: {
                required: true,
                number: true
            }
            ,capacity: {
                required: true,
                number: true
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
                    url:  $('#product_form').data('action'),
                    async: true,
                    contentType: false,
                    processData: false,
                    beforeSend: function() {
                        $('#myloader').show();
                        $('button').attr('disabled', true);
                    },
                    success:function(datas){
                        if(datas.success === 'true') {
                            install();

                            window.location.href = "<?php echo base_url().BACKMIN_PATH.'/product'; ?>";
                        }
                        else {
                            bootAlert(datas.message);
                            $('#myloader').hide();
                            $('button').attr('disabled', false);
                            // window.location.href = BASE_URL+'mitra';
                        }
                        // console.log(datas.data);
                    }
                });
                return false;
            }
        }
    });

    function install()
    {
                var uri1 = "<?php echo base_url() ?>" + 'home/generateJson2013/1-6/1';
                var uri2 = "<?php echo base_url() ?>" + 'home/generateJsonAllTeks/1-6';
                var uri3 = "<?php echo base_url() ?>" + 'home/generateJsonAllTeksKonfirmasi/1-6';
                var uri4 = "<?php echo base_url() ?>" + 'home/generateJson2013/7-9/1';
                var uri5 = "<?php echo base_url() ?>" + 'home/generateJson2006/7-9';
                var uri6 = "<?php echo base_url() ?>" + 'home/generateJsonAllTeks/7-9';
                var uri7 = "<?php echo base_url() ?>" + 'home/generateJsonAllTeksKonfirmasi/7-9';
                var uri8 = "<?php echo base_url() ?>" + 'home/generateJson2013/10-12/1';
                var uri9 = "<?php echo base_url() ?>" + 'home/generateJson2006/10-12';
                var uri10 = "<?php echo base_url() ?>" + 'home/generateJsonPeminatan/10-12';
                var uri11 = "<?php echo base_url() ?>" + 'home/generateJsonAllTeks/10-12';
                var uri12 = "<?php echo base_url() ?>" + 'home/generateJsonAllTeksKonfirmasi/10-12';
                var uri13 = "<?php echo base_url() ?>" + 'home/generateJsonLiterasi/1-6';
                var uri14 = "<?php echo base_url() ?>" + 'home/generateJsonLiterasi/7-9';
                var uri15 = "<?php echo base_url() ?>" + 'home/generateJsonLiterasi/10-12';
                var uri16 = "<?php echo base_url() ?>" + 'home/generateJsonPengayaan/1-6';
                var uri17 = "<?php echo base_url() ?>" + 'home/generateJsonPengayaan/7-9';
                var uri18 = "<?php echo base_url() ?>" + 'home/generateJsonPengayaan/10-12';
                var uri19 = "<?php echo base_url() ?>" + 'home/generateJsonReferensi/1-6';
                var uri20 = "<?php echo base_url() ?>" + 'home/generateJsonReferensi/7-9';
                var uri21 = "<?php echo base_url() ?>" + 'home/generateJsonReferensi/10-12';
                var uri22 = "<?php echo base_url() ?>" + 'home/generateJsonPandik/1-6';
                var uri23 = "<?php echo base_url() ?>" + 'home/generateJsonPandik/7-9';
                var uri24 = "<?php echo base_url() ?>" + 'home/generateJsonPandik/10-12';

                $.ajax({
                    url: uri1,
                    dataType: "json",
                    success: function(e1){
                        console.log(e1.message);
                            $("#alert").append("1. " + e1.message + " <b>OK</b> <br/>");
                        req2();
                    }
                });

                function req2(){
                    $.ajax({
                        url: uri2,
                        dataType: "json",
                        success: function(e2){
                            console.log(e2.message);
                            $("#alert").append("2. " + e2.message + " <b>OK</b> <br/>");
                            req3();
                        }
                    });
                }

                function req3(){
                    $.ajax({
                        url: uri3,
                        dataType: "json",
                        success: function(e3){
                            console.log(e3.message);
                            $("#alert").append("3. " + e3.message + " <b>OK</b> <br/>");
                            req4();
                        }
                    });
                }

                function req4(){
                    $.ajax({
                        url: uri4,
                        dataType: "json",
                        success: function(e4){
                            console.log(e4.message);
                            $("#alert").append("4. " + e4.message + " <b>OK</b> <br/>");
                            req5();
                        }
                    });
                }

                function req5(){
                    $.ajax({
                        url: uri5,
                        dataType: "json",
                        success: function(e5){
                            console.log(e5.message);
                            $("#alert").append("5. " + e5.message + " <b>OK</b> <br/>");
                            req6();
                        }
                    });
                }

                function req6(){
                    $.ajax({
                        url: uri6,
                        dataType: "json",
                        success: function(e6){
                            console.log(e6.message);
                            $("#alert").append("6. " + e6.message + " <b>OK</b> <br/>");
                            req7();
                        }
                    });
                }

                function req7(){
                    $.ajax({
                        url: uri7,
                        dataType: "json",
                        success: function(e7){
                            console.log(e7.message);
                            $("#alert").append("7. " + e7.message + " <b>OK</b> <br/>");
                            req8();
                        }
                    });
                }

                function req8(){
                    $.ajax({
                        url: uri8,
                        dataType: "json",
                        success: function(e8){
                            console.log(e8.message);
                            $("#alert").append("8. " + e8.message + " <b>OK</b> <br/>");
                            req9();
                        }
                    });
                }

                function req9(){
                    $.ajax({
                        url: uri9,
                        dataType: "json",
                        success: function(e9){
                            console.log(e9.message);
                            $("#alert").append("9. " + e9.message + " <b>OK</b> <br/>");
                            req10();
                        }
                    });
                }

                function req10(){
                    $.ajax({
                        url: uri10,
                        dataType: "json",
                        success: function(e10){
                            console.log(e10.message);
                            $("#alert").append("10. " + e10.message + " <b>OK</b> <br/>");
                            req11();
                        }
                    });
                }

                function req11(){
                    $.ajax({
                        url: uri11,
                        dataType: "json",
                        success: function(e11){
                            console.log(e11.message);
                            $("#alert").append("11. " + e11.message + " <b>OK</b> <br/>");
                            req12();
                        }
                    });
                }

                function req12(){
                    $.ajax({
                        url: uri12,
                        dataType: "json",
                        success: function(e12){
                            console.log(e12.message);
                            $("#alert").append("12. " + e12.message + " <b>OK</b> <br/>");
                            req13();
                        }
                    });
                }

                function req13(){
                    $.ajax({
                        url: uri13,
                        dataType: "json",
                        success: function(e13){
                            console.log(e13.message);
                            $("#alert").append("13. " + e13.message + " <b>OK</b> <br/>");
                            req14();
                        }
                    });
                }

                function req14(){
                    $.ajax({
                        url: uri14,
                        dataType: "json",
                        success: function(e14){
                            console.log(e14.message);
                            $("#alert").append("14. " + e14.message + " <b>OK</b> <br/>");
                            req15();
                        }
                    });
                }

                function req15(){
                    $.ajax({
                        url: uri15,
                        dataType: "json",
                        success: function(e15){
                            console.log(e15.message);
                            $("#alert").append("15. " + e15.message + " <b>OK</b> <br/>");
                            req16();
                        }
                    });
                }

                function req16(){
                    $.ajax({
                        url: uri16,
                        dataType: "json",
                        success: function(e16){
                            console.log(e16.message);
                            $("#alert").append("16. " + e16.message + " <b>OK</b> <br/>");
                            req17();
                        }
                    });
                }

                function req17(){
                    $.ajax({
                        url: uri17,
                        dataType: "json",
                        success: function(e17){
                            console.log(e17.message);
                            $("#alert").append("17. " + e17.message + " <b>OK</b> <br/>");
                            req18();
                        }
                    });
                }

                function req18(){
                    $.ajax({
                        url: uri18,
                        dataType: "json",
                        success: function(e18){
                            console.log(e18.message);
                            $("#alert").append("18. " + e18.message + " <b>OK</b> <br/>");
                            req19();
                        }
                    });
                }

                function req19(){
                    $.ajax({
                        url: uri19,
                        dataType: "json",
                        success: function(e19){
                            console.log(e19.message);
                            $("#alert").append("19. " + e19.message + " <b>OK</b> <br/>");
                            req20();
                        }
                    });
                }

                function req20(){
                    $.ajax({
                        url: uri20,
                        dataType: "json",
                        success: function(e20){
                            console.log(e20.message);
                            $("#alert").append("20. " + e20.message + " <b>OK</b> <br/>");
                            req21();
                        }
                    });
                }

                function req21(){
                    $.ajax({
                        url: uri21,
                        dataType: "json",
                        success: function(e21){
                            console.log(e21.message);
                            $("#alert").append("21. " + e21.message + " <b>OK</b> <br/>");
                            req22();
                        }
                    });
                }

                function req22(){
                    $.ajax({
                        url: uri22,
                        dataType: "json",
                        success: function(e22){
                            console.log(e22.message);
                            $("#alert").append("22. " + e22.message + " <b>OK</b> <br/>");
                            req23();
                        }
                    });
                }

                function req23(){
                    $.ajax({
                        url: uri23,
                        dataType: "json",
                        success: function(e23){
                            console.log(e23.message);
                            $("#alert").append("23. " + e23.message + " <b>OK</b> <br/>");
                            req24();
                        }
                    });
                }

                function req24(){
                    $.ajax({
                        url: uri24,
                        dataType: "json",
                        success: function(e24){
                            console.log(e24.message);
                            $("#alert").append("24. " + e24.message + " <b>OK</b> <br/>");
                            $("#alert").append("<h3>Generate JSON file success.</h3>");
                        }
                    });
                }
    }

    function readURL(input) {
        if (input.files && input.files[0]) {
            var reader = new FileReader();
        
            reader.onload = function(e) {
                $('#image-preview').attr('src', e.target.result);
            }
        
            reader.readAsDataURL(input.files[0]);
        }
    }

    $('#image-preview').css({"display" : "block", "width" : "150px"});

    $("#fileInput").change(function() {
        readURL(this);
        $('#image-preview').css({"display" : "block", "width" : "150px"});
    });

    $('#datatableProduct tbody').on('mouseover', 'tr #imgView', function(e) {

        var windowSizeY = 0;
        var imgPosScreenY = 0;
        var imgPosY = 0;
        var consVarWinSizeY = 0;
        var screenHalfPosY = 0;
        var topPos = 0;
        var posConstY = 0;

        windowSizeY = $(window).height();
        imgPosScreenY = e.screenY;
        imgPosY = e.pageY;
        consVarWinSizeY = windowSizeY/5;
        screenHalfPosY = windowSizeY/2;

        if(imgPosScreenY > screenHalfPosY)
        {
            posConstY = -(imgPosScreenY - screenHalfPosY);
        }
        else
        {
            posConstY = (screenHalfPosY-imgPosScreenY);
        }
        topPos = imgPosY + posConstY - consVarWinSizeY;


        var windowSizeX = 0;
        var imgPosScreenX = 0;
        var sidePos = 0;
        var screenHalfPosX = 0;

        windowSizeX = $(window).width();
        imgPosScreenX = e.screenX;
        screenHalfPosX = windowSizeX/2;

        if(imgPosScreenX > screenHalfPosX)
        {
            sidePos = imgPosScreenX - 350;
        }
        else
        {
            sidePos = (imgPosScreenX + 100);
        }
        console.log(sidePos);
        
        $('body').append("<span id='imgLarge' style='width:300px;position:absolute;z-index:88888;top:"+ topPos +"px;left:"+ sidePos +"px;border: 1px solid #000;'><img width='300px' src="+ $(this).attr('src') +"></span>");
    });

    $('#datatableProduct tbody').on('mouseout', 'tr #imgView', function() {
        $('body').find('#imgLarge').remove();
    });
});
</script>