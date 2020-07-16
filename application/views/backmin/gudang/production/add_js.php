
<script type="text/javascript">
$(document).ready(function(){

    $("#request_id_produk").val("");
    $("#request_berat").val("");
    $("#request_jumlah").val("");

    $("#frmRequestStock").submit(function(){
        var conf = confirm('Yakin ingin melanjutkan proses pesanan?');
        if(conf)
        {
            var id_produk = [];
            var jumlah = [];

            $(".pqty").each(function(){
                var qty = $(this).val();
                var data = $(this).data('id').split('##');
                if(qty > 0)
                {
                    id_produk.push(data[0]);
                    jumlah.push(qty);
                }
            });
            id_produk = id_produk.join();
            jumlah = jumlah.join();

            var no_oef = [];

            $(".no_oef").each(function(){
                var _no_oef = $(this).val();
                // var no_oef = $(this).val();
                // console.log(no_oef);
                if(_no_oef != '')
                {
                    no_oef.push(_no_oef);
                //     berat.push(data[1]);
                //     jumlah.push(qty);
                }
            });
            no_oef = no_oef.join();

            var id_gudang = [];

            $(".id_gudang").each(function(){
                var _id_gudang = $(this).val();
                // var no_oef = $(this).val();
                // console.log(no_oef);
                if(_id_gudang != '')
                {
                    id_gudang.push(_id_gudang);
                //     berat.push(data[1]);
                //     jumlah.push(qty);
                }
            });
            id_gudang = id_gudang.join();

            // console.log(id_produk);
            // console.log(no_oef);
            // return false;

            if (jumlah !== "" || id_produk !== "" || no_oef !== "" || id_gudang !== "")
            {
                // var tag_type = $('input[name="is_tag"]:checked').val();
                // $("#is_tags").val(tag_type);
                
                $("#request_id_produk").val(id_produk);
                $("#request_jumlah").val(jumlah);
                $("#request_no_oef").val(no_oef);
                $("#request_id_gudang").val(id_gudang);

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

    $("#continueRequest").on("click",function(){
        var conf = confirm('Yakin ingin melanjutkan proses pesanan?');
        if(conf)
        {
            // var id_produk = [];
            // var kode_buku = [];
            // var judul = [];
            var jumlah = [];
            $(".pqty_request").each(function(){
                var qty = $(this).val();
                var data = $(this).data('id').split('##');
                if(qty > 0)
                {
                    // id_produk.push(data[0]);
                    // kode_buku.push(data[1]);
                    // judul.push(data[2]);
                    jumlah.push(qty);
                }
            });
            // id_produk = id_produk.join();
            // kode_buku = kode_buku.join();
            // judul = judul.join();
            jumlah = jumlah.join();

            // var no_oef = [];

            // $(".no_oef").each(function(){
            //     var _no_oef = $(this).val();
            //     // var no_oef = $(this).val();
            //     // console.log(no_oef);
            //     if(_no_oef != '')
            //     {
            //         no_oef.push(_no_oef);
            //     //     berat.push(data[1]);
            //     //     jumlah.push(qty);
            //     }
            // });
            // no_oef = no_oef.join();

            // var id_gudang = [];

            // $(".id_gudang").each(function(){
            //     var _id_gudang = $(this).val();
            //     // var no_oef = $(this).val();
            //     // console.log(no_oef);
            //     if(_id_gudang != '')
            //     {
            //         id_gudang.push(_id_gudang);
            //     //     berat.push(data[1]);
            //     //     jumlah.push(qty);
            //     }
            // });
            // id_gudang = id_gudang.join();

            if(jumlah !== ""){
                var panel = $(".page-content-wrap");
                var uri = $("#frmContinueRequestStock").data('uri');
                $.ajax({
                    type: "POST",
                    // data: $("#frmContinueRequestStock").serialize()+"&id_produk="+id_produk+"&kode_buku="+kode_buku+"&judul="+judul+"&jumlah="+jumlah+"&no_oef="+no_oef+"&id_gudang="+id_gudang,
                    data: $("#frmContinueRequestStock").serializeArray(),
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
                                reset_button("continueRequest", "L a n j u t k a n");
                                window.location.href = BASE_URL+e.redirect;
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

    // $("#continueRequest").on("click",function(){
    //     var conf = confirm('Yakin ingin melanjutkan proses pesanan?');
    //     if(conf)
    //     {
    //         var id_produk = [];
    //         var kode_buku = [];
    //         var judul = [];
    //         var jumlah = [];
    //         $(".pqty_request").each(function(){
    //             var qty = $(this).val();
    //             var data = $(this).data('id').split('##');
    //             if(qty > 0)
    //             {
    //                 id_produk.push(data[0]);
    //                 kode_buku.push(data[1]);
    //                 judul.push(data[2]);
    //                 jumlah.push(qty);
    //             }
    //         });
    //         id_produk = id_produk.join();
    //         kode_buku = kode_buku.join();
    //         judul = judul.join();
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

    //         var id_gudang = [];

    //         $(".id_gudang").each(function(){
    //             var _id_gudang = $(this).val();
    //             // var no_oef = $(this).val();
    //             // console.log(no_oef);
    //             if(_id_gudang != '')
    //             {
    //                 id_gudang.push(_id_gudang);
    //             //     berat.push(data[1]);
    //             //     jumlah.push(qty);
    //             }
    //         });
    //         id_gudang = id_gudang.join();

    //         if(jumlah !== ""){
    //             var panel = $(".page-content-wrap");
    //             var uri = $("#frmContinueRequestStock").data('uri');
    //             $.ajax({
    //                 type: "POST",
    //                 data: $("#frmContinueRequestStock").serialize()+"&id_produk="+id_produk+"&kode_buku="+kode_buku+"&judul="+judul+"&jumlah="+jumlah+"&no_oef="+no_oef+"&id_gudang="+id_gudang,
    //                 dataType: "json",
    //                 url: BASE_URL+uri,
    //                 beforeSend: function(){
    //                     loading_button("continueRequest");
    //                     panel_refresh(panel,"shown");
    //                 },
    //                 success: function(e){
    //                     setTimeout(function(){
    //                         panel_refresh(panel,"hidden");
    //                         if(e.success=="true") {
    //                             window.location.href = BASE_URL+e.redirect;
    //                         }
    //                         else 
    //                         {
    //                             reset_button("continueRequest", "L a n j u t k a n");
    //                             window.location.href = BASE_URL+e.redirect;
    //                         }
    //                     },500);
    //                 }
    //             });
    //             return false;
    //         }
    //         else {
    //             alert('Total jumlah permintaan tidak boleh kosong.');
    //             return false;
    //         }
    //     }
    //     else
    //     {
    //         return false;
    //     }
    // });

    $(".no_oef").on("change",function(){
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
                url: BASE_URL+'backmin/gudangproduction/check_oef_limit',
                success: function(data){
                    if(typeof(data[0]) === "undefined")
                    {
                        // alert("No. EOF tidak ditemukan. Silahkan periksa kembali.");
                        // elem.focus();
                    }
                    else
                    {
                        alert("No. EOF sudah ada. Silahkan periksa kembali.");
                        elem.focus();
                    }
                }
            });
        }
    });
});
</script>
