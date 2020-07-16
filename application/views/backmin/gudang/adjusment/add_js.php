
<script type='text/javascript' src='<?php echo assets_url('js'); ?>/jquery-validation/additional/accept.js'></script>
<script type="text/javascript" src="<?php echo assets_url_backmin('js'); ?>/plugins/jquery-filer/js/jquery.filer.js"></script>
<script type="text/javascript" src="<?php echo assets_url_backmin('js'); ?>/plugins/bootstrap/bootstrap-datepicker.js"></script>
<script type="text/javascript" src="<?php echo assets_url_backmin('js'); ?>/plugins/blueimp/jquery.blueimp-gallery.min.js"></script>
<script type="text/javascript" src="<?php echo assets_url_backmin('js'); ?>/plugins/dropzone//dropzone.min.js"></script>
<script type="text/javascript" src="<?php echo assets_url_backmin('js'); ?>/plugins/icheck/icheck.min.js"></script>

<script type="text/javascript">

// function testing(elem)
// {
//     $('#'+elem.id).on('keypress',function(e){
//         alert(e.keyCode);
//     });
//     console.log(elem);
//     //     $(elem.id).keyup(function (e) {
//     //         if (e.keyCode == 13) {
//     //             // Do something
//     //             alert("testing 2");
//     //         }
//     //     });
// }

$(document).ready(function(){

    $("#tanggal_terima").datepicker({endDate: '+0d'});

    $("#frmadjusment").on("keydown",".kode_buku",function(event){
        if(event.keyCode == 13) {
            event.preventDefault();

            var dataCount = event.target.dataset.count;
            var kode_buku = $("#kode_buku_" + dataCount).val();
            var qty = $("#qty_" + dataCount);

            var id_buku = $("#id_buku_" + dataCount);
            var judul_buku = $("#judul_buku_" + dataCount);
            var berat_buku = $("#berat_buku_" + dataCount);
            var harga_buku = $("#harga_buku_" + dataCount);
            var kelas = $("#kelas_" + dataCount);

            var data = {
                "kode_buku"     : kode_buku
            };

            if(kode_buku === "")
            {
                alert("Input kode buku !");
            }
            else
            {

            $.ajax({
                type: 'POST',
                data: data,
                dataType: 'json',
                url:  "<?php echo base_url(BACKMIN_PATH.'/gudangadjusment/get_data_product') ?>",
                success:function(datas){
                    if(datas['buku'].length > 0){
                        id_buku.val(datas['buku'][0].id_product);
                        judul_buku.val(datas['buku'][0].name);
                        berat_buku.val(datas['buku'][0].weight);
                        kelas.val(datas['buku'][0].kelas);
                        if(datas['gudang'][0].id_site == 1)
                        {
                            harga_buku.val(datas['buku'][0].price_1);    
                        }
                        else if(datas['gudang'][0].id_site == 2)
                        {
                            harga_buku.val(datas['buku'][0].price_2);    
                        }
                        else if(datas['gudang'][0].id_site == 3)
                        {
                            harga_buku.val(datas['buku'][0].price_3);    
                        }
                        else if(datas['gudang'][0].id_site == 4)
                        {
                            harga_buku.val(datas['buku'][0].price_4);    
                        }
                        else if(datas['gudang'][0].id_site == 5)
                        {
                            harga_buku.val(datas['buku'][0].price_5);    
                        }
                        
                        qty.focus();
                    }
                    else
                    {
                        alert("Maaf data tidak ditemukan.");
                        judul_buku.val("");
                    }
                },
                error: function( jqXHR, exception ){
                    console.log(exception);
                }
            });
            }

            return false;
        }
    });

    $("#frmadjusment").on("change",".kode_buku",function(event){
        // if(event.keyCode == 13) {
            event.preventDefault();

            var dataCount = event.target.dataset.count;
            var kode_buku = $("#kode_buku_" + dataCount).val();
            var qty = $("#qty_" + dataCount);

            var id_buku = $("#id_buku_" + dataCount);
            var judul_buku = $("#judul_buku_" + dataCount);
            var berat_buku = $("#berat_buku_" + dataCount);
            var harga_buku = $("#harga_buku_" + dataCount);
            var kelas = $("#kelas_" + dataCount);

            var data = {
                "kode_buku"     : kode_buku,
            };

            if(kode_buku === "")
            {
                alert("Input kode buku !");
            }
            else
            {

            $.ajax({
                type: 'POST',
                data: data,
                dataType: 'json',
                url:  "<?php echo base_url(BACKMIN_PATH.'/gudangadjusment/get_data_product') ?>",
                success:function(datas){
                    if(datas['buku'].length > 0){
                        id_buku.val(datas['buku'][0].id_product);
                        judul_buku.val(datas['buku'][0].name);
                        berat_buku.val(datas['buku'][0].weight);
                        kelas.val(datas['buku'][0].kelas);
                        if(datas['gudang'][0].id_site == 1)
                        {
                            harga_buku.val(datas['buku'][0].price_1);    
                        }
                        else if(datas['gudang'][0].id_site == 2)
                        {
                            harga_buku.val(datas['buku'][0].price_2);    
                        }
                        else if(datas['gudang'][0].id_site == 3)
                        {
                            harga_buku.val(datas['buku'][0].price_3);    
                        }
                        else if(datas['gudang'][0].id_site == 4)
                        {
                            harga_buku.val(datas['buku'][0].price_4);    
                        }
                        else if(datas['gudang'][0].id_site == 5)
                        {
                            harga_buku.val(datas['buku'][0].price_5);    
                        }
                        
                        qty.focus();
                    }
                    else
                    {
                        alert("Maaf data tidak ditemukan.");
                        judul_buku.val("");
                    }
                },
                error: function( jqXHR, exception ){
                    console.log(exception);
                }
            });
            }

            return false;
        // }
    });

    $("#frmadjusment").on("keydown",".qty",function(event){
        if(event.keyCode == 13) {
            event.preventDefault();

            var dataCount = event.target.dataset.count;
            var id_buku = $("#id_buku_" + dataCount).val();
            var kode_buku = $("#kode_buku_" + dataCount).val();
            var qty = $("#qty_" + dataCount);

            if(qty.val() === '')
            {
                alert("Input qty !");
            }
            else
            {
                var data = {
                    id_buku : id_buku
                }

                $.ajax({
                    type: 'POST',
                    data: data,
                    dataType: 'json',
                    url:  "<?php echo base_url(BACKMIN_PATH.'/gudangadjusment/get_stock') ?>",
                    success:function(datas){
                        console.log(datas);
                        var total = Number(qty.val()) + Number(datas[0].stok_fisik);
                        if(total < 0)
                        {
                            alert("Maaf stok tidak mencukupi. sisa stok : " + datas[0].stok_fisik);
                        }
                        else
                        {
                            $('.btn-add').focus(); 
                        }
                    }
                });
            }

            return false;
        }
    });

    $("#frmadjusment").on("change",".qty",function(event){
        // if(event.keyCode == 13) {
            event.preventDefault();

            var dataCount = event.target.dataset.count;
            var id_buku = $("#id_buku_" + dataCount).val();
            var kode_buku = $("#kode_buku_" + dataCount).val();
            var qty = $("#qty_" + dataCount);

            if(qty.val() === '')
            {
                alert("Input qty !");
            }
            else
            {
                var data = {
                    id_buku : id_buku
                }

                $.ajax({
                    type: 'POST',
                    data: data,
                    dataType: 'json',
                    url:  "<?php echo base_url(BACKMIN_PATH.'/gudangadjusment/get_stock') ?>",
                    success:function(datas){
                        console.log(datas);
                        var total = Number(qty.val()) + Number(datas[0].stok_fisik);
                        if(total < 0)
                        {
                            alert("Maaf stok tidak mencukupi. sisa stok : " + datas[0].stok_fisik);
                            qty.focus();
                        }
                        else
                        {
                            // if(Number(qty) < 0 )
                            // {
                            //     qty.css({"background":"red"});
                            // }
                            $('.btn-add').focus(); 
                        }
                    }
                });
            }

            return false;
        // }
    });

    // $(".btn-add").click(function(e){
    $(document).on("click",".btn-add", function(e){
        var dataCount = $(this).data('count');
        var eCountNew = dataCount+1;

        var kode_buku = $("#kode_buku_" + dataCount).val();
        var qty = $("#qty_" + dataCount).val();

        if(kode_buku == "")
        {
            alert("Data buku belum diinput.");
        }
        else if(qty == "")
        {
            alert("Jumlah buku belum diinput.");
        }
        else
        {

        // REMOVE CLASS BUTTON
        $(this).removeClass('btn-success btn-add');
        $(this).addClass('btn-danger btn-delete');
        $(this).html('<span class="fa fa-trash"></span>');

        // ADD TABLE ROW
        $('#myTable > tbody:last-child').append('<tr><td><input class="form-control kode_buku" data-count="' + eCountNew + '" type="text" id="kode_buku_' + eCountNew + '" name="kode_buku[]" value=""></td><td><input class="form-control" data-count="' + eCountNew + '" type="hidden" id="id_buku_' + eCountNew + '" name="id_buku[]" value="" readonly="true"><input class="form-control" data-count="' + eCountNew + '" type="text" id="judul_buku_' + eCountNew + '" name="judul_buku[]" value="" readonly="true"><input class="form-control" data-count="' + eCountNew + '" type="hidden" id="berat_buku_' + eCountNew + '" name="berat_buku[]" value="" readonly="true"><input class="form-control" data-count="' + eCountNew + '" type="hidden" id="harga_buku_' + eCountNew + '" name="harga_buku[]" value="" readonly="true"></td><td><input class="form-control kelas" data-count="' + eCountNew + '" type="text" id="kelas_' + eCountNew + '" name="kelas[]" value=""></td><td><input class="form-control qty" data-count="' + eCountNew + '" type="text" id="qty_' + eCountNew + '" name="qty[]" value=""></td><td><button class="btn btn-success btn-add" data-count="' + eCountNew + '" type="button"><span class="fa fa-pencil"></button></td></tr>');
        }

        // SET FOCUS
        $("#kode_buku_" + eCountNew).focus();
    });

    $(document).on("click",".btn-delete", function(e){
        if(confirm("Yakin akan menghapus data ? ") == true)
        {
            $(this).closest('tr').remove();
        }
    });

    // $("#frmadjusment").submit(function(){
    //     var conf = confirm('Yakin ingin melanjutkan proses pesanan?');
    //     if(conf)
    //     {

    //     }
    //     return false;
    // });

    $("#frmadjusment").validate({
        rules : {
            catatan : "required",
            // tanggal : "required"
        },
        submitHandler: function(form) {
            
            var conf = confirm('Yakin ingin melanjutkan proses pesanan ?');
            if(conf)
            {
                // $.ajax({
                //     url: form.action,
                //     type: form.method,
                //     data: $(form).serialize(),
                //     success: function(response) {
                //         $('#answers').html(response);
                //     }            
                // });
                return true;
            }
            return false;
        }
    });

    // $("#request_id_produk").val("");
    // $("#request_berat").val("");
    // $("#request_jumlah").val("");

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

    //         if (jumlah !== "" || berat !== "" || id_produk !== "")
    //         {
    //             var tag_type = $('input[name="is_tag"]:checked').val();
    //             $("#is_tags").val(tag_type);
                
    //             $("#request_id_produk").val(id_produk);
    //             $("#request_berat").val(berat);
    //             $("#request_jumlah").val(jumlah);

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

    // $("#continueRequest").on("click",function(){
    //     var conf = confirm('Yakin ingin melanjutkan proses pesanan?');
    //     if(conf)
    //     {
    //         var id_produk = [];
    //         var jumlah = [];
    //         var berat = [];
    //         $(".pqty_request").each(function(){
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

    //         if(jumlah !== ""){
    //             var panel = $(".page-content-wrap");
    //             var uri = $("#frmContinueRequestStock").data('uri');
    //             $.ajax({
    //                 type: "POST",
    //                 data: $("#frmContinueRequestStock").serialize()+"&id_produk="+id_produk+"&berat="+berat+"&jumlah="+jumlah,
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
});
</script>
