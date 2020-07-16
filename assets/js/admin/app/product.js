$(document).ready(function(){
    var datas = [];
    if ($('#datatableProduct').length>0){
        datas['selector'] = 'datatableProduct';
        datas['url'] = BASE_URL + 'product/listProduct';
        datas['columns'] = [
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
        ];
        datas['columnDefs'] = [
            { className: 'text-center', targets: [0, 4, 6, 7] }
        ];
        datas['sort'] = [0, 'asc'];
        datatableProduct = myDatatables(datas);
        commonTools(datas['selector'], datatableProduct);
    }

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
                            var url = BASE_URL+'product';
                            install(url);
                        }
                        else {
                            bootAlert(datas.message);
                            $('#myloader').hide();
                            $('button').attr('disabled', false);
                            // window.location.href = BASE_URL+'mitra';
                        }
                        console.log(datas);
                    }
                });
                return false;
                // alert("testing submit form");
            }
        }
    });

    function install(url)
    {
                var uri1 = BASE_URI + 'home/generateJson2013/1-6/1';
                var uri2 = BASE_URI + 'home/generateJsonAllTeks/1-6';
                var uri3 = BASE_URI + 'home/generateJsonAllTeksKonfirmasi/1-6';
                var uri4 = BASE_URI + 'home/generateJson2013/7-9/1';
                var uri5 = BASE_URI + 'home/generateJson2006/7-9';
                var uri6 = BASE_URI + 'home/generateJsonAllTeks/7-9';
                var uri7 = BASE_URI + 'home/generateJsonAllTeksKonfirmasi/7-9';
                var uri8 = BASE_URI + 'home/generateJson2013/10-12/1';
                var uri9 = BASE_URI + 'home/generateJson2006/10-12';
                var uri10 = BASE_URI + 'home/generateJsonPeminatan/10-12';
                var uri11 = BASE_URI + 'home/generateJsonAllTeks/10-12';
                var uri12 = BASE_URI + 'home/generateJsonAllTeksKonfirmasi/10-12';
                var uri13 = BASE_URI + 'home/generateJsonLiterasi/1-6';
                var uri14 = BASE_URI + 'home/generateJsonLiterasi/7-9';
                var uri15 = BASE_URI + 'home/generateJsonLiterasi/10-12';
                var uri16 = BASE_URI + 'home/generateJsonPengayaan/1-6';
                var uri17 = BASE_URI + 'home/generateJsonPengayaan/7-9';
                var uri18 = BASE_URI + 'home/generateJsonPengayaan/10-12';
                var uri19 = BASE_URI + 'home/generateJsonReferensi/1-6';
                var uri20 = BASE_URI + 'home/generateJsonReferensi/7-9';
                var uri21 = BASE_URI + 'home/generateJsonReferensi/10-12';
                var uri22 = BASE_URI + 'home/generateJsonPandik/1-6';
                var uri23 = BASE_URI + 'home/generateJsonPandik/7-9';
                var uri24 = BASE_URI + 'home/generateJsonPandik/10-12';
                var uri25 = BASE_URI + 'home/generateJsonPendampingk13/1-6';
                var uri26 = BASE_URI + 'home/generateJsonPendampingk13/7-9';
                var uri27 = BASE_URI + 'home/generateJsonPendampingk13/10-12';
                var uri28 = BASE_URI + 'home/generateJsonPeminatanSmaMa/1-6';
                var uri29 = BASE_URI + 'home/generateJsonPeminatanSmaMa/7-9';
                var uri30 = BASE_URI + 'home/generateJsonPeminatanSmaMa/10-12';
                var uri31 = BASE_URI + 'home/generateJsonHetk13/1-6';
                var uri32 = BASE_URI + 'home/generateJsonHetk13/7-9';
                var uri33 = BASE_URI + 'home/generateJsonHetk13/10-12';
                var uri34 = BASE_URI + 'home/generateJsonProductIt/1-6';
                var uri35 = BASE_URI + 'home/generateJsonProductIt/7-9';
                var uri36 = BASE_URI + 'home/generateJsonProductIt/10-12';
                var uri37 = BASE_URI + 'home/generateJsonProductCovid/1-6';
                var uri38 = BASE_URI + 'home/generateJsonProductCovid/7-9';
                var uri39 = BASE_URI + 'home/generateJsonProductCovid/10-12';
                var uri40 = BASE_URI + 'home/generateJsonAlatTulis/1-6';
                var uri41 = BASE_URI + 'home/generateJsonAlatTulis/7-9';
                var uri42 = BASE_URI + 'home/generateJsonAlatTulis/10-12';

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
                            req25();
                        }
                    });
                }

                function req25(){
                    $.ajax({
                        url: uri25,
                        dataType: "json",
                        success: function(e25){
                            console.log(e25.message);
                            $("#alert").append("25. " + e25.message + " <b>OK</b> <br/>");
                            req26();
                        }
                    });
                }

                function req26(){
                    $.ajax({
                        url: uri26,
                        dataType: "json",
                        success: function(e26){
                            console.log(e26.message);
                            $("#alert").append("26. " + e26.message + " <b>OK</b> <br/>");
                            req27();
                        }
                    });
                }

                function req27(){
                    $.ajax({
                        url: uri27,
                        dataType: "json",
                        success: function(e27){
                            console.log(e27.message);
                            $("#alert").append("27. " + e27.message + " <b>OK</b> <br/>");
                            req28();
                        }
                    });
                }

                function req28(){
                    $.ajax({
                        url: uri28,
                        dataType: "json",
                        success: function(e28){
                            console.log(e28.message);
                            $("#alert").append("28. " + e28.message + " <b>OK</b> <br/>");
                            req29();
                        }
                    });
                }

                function req29(){
                    $.ajax({
                        url: uri29,
                        dataType: "json",
                        success: function(e29){
                            console.log(e29.message);
                            $("#alert").append("29. " + e29.message + " <b>OK</b> <br/>");
                            req30();
                        }
                    });
                }

                function req30(){
                    $.ajax({
                        url: uri30,
                        dataType: "json",
                        success: function(e30){
                            console.log(e30.message);
                            $("#alert").append("30. " + e30.message + " <b>OK</b> <br/>");
                            req31();
                        }
                    });
                }

                function req31(){
                    $.ajax({
                        url: uri31,
                        dataType: "json",
                        success: function(e31){
                            console.log(e31.message);
                            $("#alert").append("31. " + e31.message + " <b>OK</b> <br/>");
                            req32();
                        }
                    });
                }

                function req32(){
                    $.ajax({
                        url: uri32,
                        dataType: "json",
                        success: function(e32){
                            console.log(e32.message);
                            $("#alert").append("32. " + e32.message + " <b>OK</b> <br/>");
                            req33();
                        }
                    });
                }

                function req33(){
                    $.ajax({
                        url: uri33,
                        dataType: "json",
                        success: function(e33){
                            console.log(e33.message);
                            $("#alert").append("33. " + e33.message + " <b>OK</b> <br/>");
                            req34();
                        }
                    });
                }

                function req34(){
                    $.ajax({
                        url: uri34,
                        dataType: "json",
                        success: function(e34){
                            console.log(e34.message);
                            $("#alert").append("34. " + e34.message + " <b>OK</b> <br/>");
                            req35();
                        }
                    });
                }

                function req35(){
                    $.ajax({
                        url: uri35,
                        dataType: "json",
                        success: function(e35){
                            console.log(e35.message);
                            $("#alert").append("35. " + e35.message + " <b>OK</b> <br/>");
                            req36();
                        }
                    });
                }

                function req36(){
                    $.ajax({
                        url: uri36,
                        dataType: "json",
                        success: function(e36){
                            console.log(e36.message);
                            $("#alert").append("36. " + e36.message + " <b>OK</b> <br/>");
                            req37();
                        }
                    });
                }

                function req37(){
                    $.ajax({
                        url: uri37,
                        dataType: "json",
                        success: function(e37){
                            console.log(e37.message);
                            $("#alert").append("37. " + e37.message + " <b>OK</b> <br/>");
                            req38();
                        }
                    });
                }

                function req38(){
                    $.ajax({
                        url: uri38,
                        dataType: "json",
                        success: function(e38){
                            console.log(e38.message);
                            $("#alert").append("38. " + e38.message + " <b>OK</b> <br/>");
                            req39();
                        }
                    });
                }

                function req39(){
                    $.ajax({
                        url: uri39,
                        dataType: "json",
                        success: function(e39){
                            console.log(e39.message);
                            $("#alert").append("39. " + e39.message + " <b>OK</b> <br/>");
                            req40();
                        }
                    });
                }

                function req40(){
                    $.ajax({
                        url: uri40,
                        dataType: "json",
                        success: function(e40){
                            console.log(e40.message);
                            $("#alert").append("40. " + e40.message + " <b>OK</b> <br/>");
                            req41();
                        }
                    });
                }

                function req41(){
                    $.ajax({
                        url: uri41,
                        dataType: "json",
                        success: function(e41){
                            console.log(e41.message);
                            $("#alert").append("41. " + e41.message + " <b>OK</b> <br/>");
                            req42();
                        }
                    });
                }

                function req42(){
                    $.ajax({
                        url: uri42,
                        dataType: "json",
                        success: function(e42){
                            console.log(e42.message);
                            $("#alert").append("42. " + e42.message + " <b>OK</b> <br/>");
                            $("#alert").append("<h3>Generate JSON file success.</h3>");

                            if(typeof url !== 'undefined')
                            {
                                window.location.href = url;
                            }
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

    $('#datatableProduct').on('mouseover', '#imgView', function(e) {

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
        consVarWinSizeY = windowSizeY/6;
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
        
        $('#wrapper').append("<span id='imgLarge' style='width:300px;position:absolute;z-index:88888;top:"+ topPos +"px;left:"+ sidePos +"px;border: 1px solid #000;'><img width='300px' src="+ $(this).attr('src') +"></span>");
    });

    $('#datatableProduct').on('mouseout', '#imgView', function() {
        $('#wrapper').find('#imgLarge').remove();
    });
});
