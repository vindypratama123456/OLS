<script type="text/javascript" src="<?php echo assets_url_backmin('js'); ?>/plugins/bootstrap/bootstrap-datepicker.js"></script>

<script type="text/javascript">
$(document).ready(function(){
    <?php if ($detail['is_tag'] == 2 && $detail['is_intan'] == 2) { ?>
        $("#datepicker_tgl_transaksi").datepicker({
            endDate: '+0d',
            format: 'yyyy-mm-dd'
        });

        if ($(".need_stock").length || $("#tgl_transaksi").val() == '') {
            $("#submitDetail").hide();
        } else {
            $("#submitDetail").show();
        }

        $("#tgl_transaksi").on("change", function(){
            if ($(this).val() == '') {
                $("#submitDetail").hide();
            } else {
                $("#submitDetail").show();
            }
        });
    <?php } else { ?>
        if ($(".need_stock").length) {
            $("#submitDetail").hide();
        } else {
            $("#submitDetail").show();
        }
    <?php } ?>

    $("#frmDetilPermintaanStok").validate({
        ignore: [],
        rules: {
            tgl_transaksi: {
                required: true,
            }
        }
    });    

    $("#submitDetail").on("click",function(){
        var conf = confirm('Yakin ingin melanjutkan proses pesanan?');
        if(conf) {
            var panel = $(".page-content-wrap");
            var uri = $("#frmDetilPermintaanStok").data('uri');
            $.ajax({
                type: "POST",
                data: $("#frmDetilPermintaanStok").serialize(),
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
    
    $("#cancelDetail").on("click",function(){
        var conf = confirm('Yakin ingin membatalkan proses pesanan?');
        if(conf) {
            
            var panel = $(".page-content-wrap");
            var uri = '<?php echo BACKMIN_PATH; ?>/scmrequeststock/processCencelRequestStockMasuk';
            var form_data = {
                id_request : $("#id_request").val()
            }

            $.ajax({
                type: "POST",
                data: form_data,
                dataType: "json",
                url: BASE_URL+uri,
                beforeSend: function(){
                    loading_button("cancelDetail");
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