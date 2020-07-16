$(document).ready(function(){
    var datas = [];
    if ($('#datatableOngkir').length>0){
        datas['selector'] = 'datatableOngkir';
        datas['url'] = BASE_URL + 'ongkir/listOngkir';
        datas['columns'] = [
            { 'data': 'id' },
            { 'data': 'kd_prop' },
            { 'data': 'provinsi' },
            { 'data': 'kd_kab_kota' },
            { 'data': 'kabupaten' },
            { 'data': 'kd_kec' },
            { 'data': 'kecamatan' },
            { 'data': 'tarif_per_kg_komp_eco_min30kg' },
            { 'data': 'tarif_per_kg_komp_reg_min1kg' },
            { 'data': 'tarif_per_kg_lainlain_eco_min30kg' },
            { 'data': 'tarif_per_kg_perlindungandiri_noncair_reg_min1kg' }
        ];
        datas['columnDefs'] = [
            { className: 'text-center', targets: [0, 1, 3, 5] }
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
                            var url = BASE_URL+'ongkir';
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
