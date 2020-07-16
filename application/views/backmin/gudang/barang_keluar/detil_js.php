
<script type="text/javascript">
$(document).ready(function(){
    $("#submitDetail").on("click",function(){
        var conf = confirm('Yakin ingin melanjutkan proses permintaan?');
        if(conf) {
            var panel = $(".page-content-wrap");
            var uri = $("#frmDetilBarangKeluar").data('uri');
            $.ajax({
                type: "POST",
                data: $("#frmDetilBarangKeluar").serialize(),
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
});
</script>