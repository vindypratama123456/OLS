<script type="text/javascript">
$(document).ready(function(){

    if ($(".need_stock").val() > 0) {
        $("#submitDetail").hide();
        $("#avaiableMessage").show();
    }
    else {
        $("#submitDetail").show();
        $("#avaiableMessage").hide();
    }

    $("#submitDetail").on("click",function(){
        var conf = confirm('Yakin ingin melanjutkan proses pesanan?');
        if(conf) {
            var panel = $(".page-content-wrap");
            var uri = $("#frmDetilPesananMasuk").data('uri');
            $.ajax({
                type: "POST",
                data: $("#frmDetilPesananMasuk").serialize(),
                dataType: "json",
                url: BASE_URL+uri,
                beforeSend: function(){
                    loading_button("submitDetail");
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
                            reset_button("submitDetail","P r o s e s");
                            window.location.href = BASE_URL+e.redirect;
                        }
                    },500);
                }
            });
            return false;
        }
        else
        {
            return false;
        }
    });

    $("#submitDetailParsial").on("click",function(){
        var conf = confirm('Yakin ingin melanjutkan proses pesanan secara parsial ?');
        if(conf) {
            var panel = $(".page-content-wrap");
            var uri = $("#frmDetilPesananMasuk").data('uri_parsial');
            $.ajax({
                type: "POST",
                data: $("#frmDetilPesananMasuk").serialize(),
                dataType: "json",
                url: BASE_URL+uri,
                beforeSend: function(){
                    loading_button("submitDetail");
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
                            reset_button("submitDetail","P r o s e s");
                            window.location.href = BASE_URL+e.redirect;
                        }
                    },500);
                }
            });
            return false;
        }
        else
        {
            return false;
        }
    });

    $("#submitRequestParsial").on("click",function(){
        var conf = confirm('Yakin ingin request pengiriman pesanan secara parsial ?');
        if(conf) {
            var panel = $(".page-content-wrap");
            var uri = $("#frmDetilPesananMasuk").data('uri_parsial_request');
            $.ajax({
                type: "POST",
                data: $("#frmDetilPesananMasuk").serialize(),
                dataType: "json",
                url: BASE_URL+uri,
                beforeSend: function(){
                    loading_button("submitRequestParsial");
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
                            reset_button("submitRequestParsial","P r o s e s");
                            window.location.href = BASE_URL+e.redirect;
                        }
                    },500);
                }
            });
            return false;
        }
        else
        {
            return false;
        }
    });
    
});
</script>