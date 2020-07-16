
<script type="text/javascript">
$(document).ready(function(){
    $("#frmDetilBarangMasuk").validate({
        ignore: [],
        rules: {
            nopol: {
                required: true,
            },
            nama_supir: {
                required: true,
            },
            hp_supir: {
                required: true,
            }
        }
    });

    $("#submitDetail").on("click",function()
    {
        if ($("#frmDetilBarangMasuk").valid() == true)
        {
            var conf = confirm('Yakin ingin melanjutkan proses terima barang?');
            if(conf) {
                var panel = $(".page-content-wrap");
                var uri = $("#frmDetilBarangMasuk").data('uri');
                $.ajax({
                    type: "POST",
                    data: $("#frmDetilBarangMasuk").serialize(),
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
        }
        else
        {
            return false;
        }
    });
    
});
</script>