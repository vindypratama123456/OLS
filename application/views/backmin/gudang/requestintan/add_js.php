
<script type="text/javascript">
$(document).ready(function(){

    $("#request_id_produk").val("");
    $("#request_berat").val("");
    $("#request_jumlah").val("");

    $("#frmRequestIntan").submit(function(){
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

            if (jumlah !== "" || berat !== "" || id_produk !== "")
            {
                $("#request_id_produk").val(id_produk);
                $("#request_berat").val(berat);
                $("#request_jumlah").val(jumlah);

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

            if(jumlah !== ""){
                var panel = $(".page-content-wrap");
                var uri = $("#frmContinueRequestIntan").data('uri');
                $.ajax({
                    type: "POST",
                    data: $("#frmContinueRequestIntan").serialize()+"&id_produk="+id_produk+"&berat="+berat+"&jumlah="+jumlah,
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
});
</script>