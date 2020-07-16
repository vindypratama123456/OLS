
<script src="<?php echo js_url('admin/bootbox.min.js?v='.date('YmdHis')); ?>"></script>
<script type="text/javascript">
$(document).ready(function(){

    $("#request_id_produk").val("");
    $("#request_berat").val("");
    $("#request_jumlah").val("");

    // $("#frmRequestStock").submit(function(){
    //     var conf = confirm('Yakin ingin melanjutkan proses pesanan?');
    //     if(conf)
    //     {
    //         var id_produk = [];
    //         var jumlah = [];
    //         var berat = [];
    //         $(".pqty").each(function(){
    //             var qty = $(this).val();
    //             var data = $(this).data('id').split('##');
    //             if(qty > 0)
    //             {
    //                 id_produk.push(data[0]);
    //                 berat.push(data[1]);
    //                 jumlah.push(qty);
    //             }
    //         });
    //         id_produk = id_produk.join();
    //         berat = berat.join();
    //         jumlah = jumlah.join();

    //         var no_oef = [];

    //         $(".no_oef").each(function(){
    //             var _no_oef = $(this).val();
    //             // var no_oef = $(this).val();
    //             // console.log(no_oef);
    //             if(_no_oef != '')
    //             {
    //                 no_oef.push(_no_oef);
    //             //     berat.push(data[1]);
    //             //     jumlah.push(qty);
    //             }
    //         });
    //         no_oef = no_oef.join();

    //         if (jumlah !== "" || berat !== "" || id_produk !== "" || no_oef !== "")
    //         {
    //             var tag_type = $('input[name="is_tag"]:checked').val();
    //             $("#is_tags").val(tag_type);
                
    //             $("#request_id_produk").val(id_produk);
    //             $("#request_berat").val(berat);
    //             $("#request_jumlah").val(jumlah);
    //             $("#request_no_oef").val(no_oef);

    //             return true;
    //         }
    //         else
    //         {
    //             alert('Total jumlah permintaan tidak boleh kosong.');
    //             return false;
    //         }
    //     }
    //     else
    //     {
    //         return false;
    //     }
    // });
     
    $("#frmRequestStock").submit(function(){
        var conf = confirm('Yakin ingin melanjutkan proses pesanan?');
        if(conf)
        {
            var id_produk = [];
            var jumlah = [];
            var berat = [];
            $(".pqty").each(function(){
                var qty = $(this).val();
                var data = $(this).data('id').split('##');
                if(qty > 0)
                {
                    id_produk.push(data[0]);
                    berat.push(data[1]);
                    jumlah.push(qty);
                }
            });
            id_produk = id_produk.join();
            berat = berat.join();
            jumlah = jumlah.join();

            var no_oef = [];

            $(".no_oef").each(function(){
                var _no_oef = $(this).val();
                if(_no_oef != '')
                {
                    no_oef.push(_no_oef);
                }
            });
            no_oef = no_oef.join();

            if (jumlah !== "" || berat !== "" || id_produk !== "" || no_oef !== "")
            {
                var tag_type = $('input[name="is_tag"]:checked').val();
                $("#is_tags").val(tag_type);
                
                $("#request_id_produk").val(id_produk);
                $("#request_berat").val(berat);
                $("#request_jumlah").val(jumlah);
                $("#request_no_oef").val(no_oef);

                return true;
            }
            else
            {
                alert('Total jumlah permintaan tidak boleh kosong.');
                return false;
            }
        }
        else
        {
            return false;
        }
    });
    
    $("#frmRequestStock_temp").submit(function(){
        // var noOefData;
        // var noOefValue;
        var noOefValueArray = [];
        var noOefValueObj = {};
        var ajaxDataArray = [];
        var promiseDataArray = [];
        var promiseDataObj = [];
        var productQuantityValue;

        var checkNoOef = [];
        var dataArray = [];
        var alertCheckNoOef;

        $(".no_oef").each(function(){
            var noOefData = $(this).data('id');
            var noOefValue = $(this).val();


            if(noOefValue !== "")
            {
                productQuantityValue = $("input[data-id='" + noOefData + "']")[1].value;
                // noOefValueArray[noOefValue] = productQuantityValue;
                noOefValueObj[noOefValue.toUpperCase()] = productQuantityValue;
                // noOefValueArray.push(noOefValue);
            
                var data = {
                    "qty" : productQuantityValue,
                    "no_oef" : noOefValue
                };

                ajaxDataArray.push(
                    $.ajax({
                        type: "POST",
                        data: data,
                        dataType: "json",
                        url: BASE_URL+'backmin/gudangreceiving/check_oef_limit',
                        success : function(data)
                        {
                            dataArray.push(data);
                        }
                    })
                );
            }
        });  
        console.log(ajaxDataArray);

        console.log(ajaxDataArray[0].responseJSON);
        // console.log(dataArray); 
        return false;
    });

    $("#continueRequest").on("click",function(){
        var conf = confirm('Yakin ingin melanjutkan proses pesanan?');
        if(conf)
        {
            var id_produk = [];
            var jumlah = [];
            var berat = [];
            $(".pqty_request").each(function(){
                var qty = $(this).val();
                var data = $(this).data('id').split('##');
                if(qty > 0)
                {
                    id_produk.push(data[0]);
                    berat.push(data[1]);
                    jumlah.push(qty);
                }
            });
            id_produk = id_produk.join();
            berat = berat.join();
            jumlah = jumlah.join();

            var no_oef = [];

            $(".no_oef").each(function(){
                var _no_oef = $(this).val();
                if(_no_oef != '')
                {
                    no_oef.push(_no_oef);
                }
            });
            no_oef = no_oef.join();

            if(jumlah !== ""){
                var panel = $(".page-content-wrap");
                var uri = $("#frmContinueRequestStock").data('uri');
                $.ajax({
                    type: "POST",
                    data: $("#frmContinueRequestStock").serialize()+"&id_produk="+id_produk+"&berat="+berat+"&jumlah="+jumlah+"&no_oef="+no_oef,
                    dataType: "json",
                    url: BASE_URL+uri,
                    beforeSend: function(){
                        loading_button("continueRequest");
                        panel_refresh(panel,"shown");
                    },
                    success: function(e){
                        setTimeout(function(){
                            panel_refresh(panel,"hidden");
                            if(e.success=="true") {
                                window.location.href = BASE_URL+e.redirect;
                            }
                            else 
                            {
                                // bootAlertRedirect(e.message, e.redirect);
                                // reset_button("continueRequest", "L a n j u t k a n");
                                // window.location.href = BASE_URL+e.redirect;
                                bootbox.alert({
                                    title: 'Informasi',
                                    message: e.message,
                                    callback: function () {
                                        $('.bootbox').modal('hide').data('bs.modal', null);
                                        if (e.redirect) {
                                            window.location = BASE_URL + e.redirect;
                                        } else {
                                            window.location.reload(true);
                                        }
                                    }
                                });
                            }
                        },500);
                    }
                });
                return false;
            }
            else {
                alert('Total jumlah permintaan tidak boleh kosong.');
                return false;
            }
        }
        else
        {
            return false;
        }
    });

    $("input[name='product_quantity[]']").on("change", function(){
        var productQuantityData = $(this).data('id');
        var productQuantityValue = $(this).val();
        var kode_buku = productQuantityData.split('##')[2];
        $(".no_oef").each(function(){
            var noOefData = $(this).data('id');
            var noOefValue = $(this).val();
            if(noOefData === productQuantityData)
            {
                // var oefField = $("input[data-id='"+noOefData+"']");
                // if(oefField.val() !== "")
                if(noOefValue !== "")
                {
                    // alert(productQuantityValue);
                    var data = {
                        // "qty" : productQuantityValue,
                        "kode_buku" : kode_buku,
                        "no_oef" : noOefValue
                    };
                    $.ajax({
                        type: "POST",
                        data: data,
                        dataType: "json",
                        url: BASE_URL+'backmin/gudangreceiving/check_oef_limit',
                        success: function(data){
                            var jmlReq;
                            var jmlSend;
                            var totSend;
                            var toleransi;
                            var jmlReqToleransi;
                            var sisa;
                            var persen;
                            if(typeof(data[0]) === "undefined")
                            {
                                alert("No. EOF tidak ditemukan. Silahkan periksa kembali.");
                                $("input[data-id='"+noOefData+"']").first().focus();
                                $("input[data-id='"+noOefData+"']")[1].value="";
                            }
                            else
                            {
                                jmlReq = Number(data[0].jumlah_request);
                                jmlSend = Number(data[0].jumlah_kirim);
                                totSend = Number(jmlSend) + Number(productQuantityValue);
                                persen = 10;
                                toleransi = persen/100;
                                jmlReqToleransi = (jmlReq * toleransi) + jmlReq;
                                // sisa = jmlReq - jmlSend;
                                sisa = jmlReqToleransi - jmlSend;
                                // console.log(jmlReq + " | " + jmlSend + " | " + totSend + " | " + toleransi + " | " + jmlReqToleransi);
                                if(jmlReqToleransi < totSend)
                                // if(jmlReq < totSend)
                                {
                                    // alert("Quota tidak mencukupi. Sisa kuota : " + sisa + ". Toleransi quota " + persen + "% : " + jmlReqToleransi);
                                    alert("Quota tidak mencukupi dan sudah melebihi toleransi. Sisa kuota : " + sisa + ". Toleransi quota " + persen + "%");
                                    return false;
                                } 
                            }
                        }
                    });
                }
            }
        });
    });

    $("input[name='product_quantity[]']").on("input", function(){
        var productQuantityData = $(this).data('id');
        $(".no_oef").each(function(){
            var noOefData = $(this).data('id');
            var noOefValue = $(this).val();
            if(noOefData === productQuantityData)
            {
                if(noOefValue === "")
                {
                    alert("Silahkan input No OEF");
                    $("input[data-id='"+noOefData+"']").first().focus();
                    $("input[data-id='"+noOefData+"']")[1].value="";
                }
            }
        });
        
    });

    $(".no_oef").on("change", function(){
        var noOefData = $(this).data('id');
        var kode_buku = noOefData.split('##')[2];
        var noOefValue = $(this).val();
        var elem = $(this);
        var data = {
            "no_oef" : noOefValue,
            "kode_buku" : kode_buku
        };
        // alert(noOefValue.length);
        if(noOefValue.length > 0)
        {
            $.ajax({
                type: "POST",
                data: data,
                dataType: "json",
                url: BASE_URL+'backmin/gudangreceiving/check_oef_limit',
                success: function(data){
                    if(typeof(data[0]) === "undefined")
                    {
                        alert("No. EOF tidak ditemukan. Silahkan periksa kembali.");
                        elem.focus();
                    }
                }
            });
        }
    });
});
</script>
