
<script type="text/javascript" src="<?php echo assets_url_backmin('js'); ?>/plugins/bootstrap/bootstrap-datepicker.js"></script>

<script type="text/javascript">
    $(function(){
        $("#start_date").datepicker({endDate: '+0d'});
        $("#end_date").datepicker({endDate: '+1d'});
    });

    $("#frmReportOmset").validate({
        ignore: [],
        rules: {
            tanggal_awal: {
                required: true,
            },
            tanggal_akhir: {
                required: true,
            }
        }
    });
    
    $("#submitSearch").on("click",function(){
        if ($("#frmReportOmset").valid() == true) 
        {
            var panel = $(".panel-result");
            var uri = $("#frmReportOmset").data('uri');
            var uri_ekspor = $("#btn_ekspor").data('ekspor');
            var btn_ekspor = '';

            $.ajax({
                type: "POST",
                data: $("#frmReportOmset").serialize(),
                dataType: "json",
                url: BASE_URL+uri,
                beforeSend: function(){
                    // loading_button("submitSearch");
                    panel_refresh(panel,"shown");
                },
                success: function(e){
                    panel_refresh(panel,"hidden");
                    if(e.success=="true") {
                        $("#omset_value").html("Rp " + e.data_omset.total_omset.replace(/\B(?=(\d{3})+(?!\d))/g, ","));
                        $("#buku_value").html(e.data_omset.total_buku.replace(/\B(?=(\d{3})+(?!\d))/g, ","));
                        $("#pesanan_value").html(e.data_omset.total_pesanan.replace(/\B(?=(\d{3})+(?!\d))/g, ","));

                        btn_ekspor = '<a href="'+BASE_URL+uri_ekspor+e.tgl_awal+'/'+e.tgl_akhir+'/'+e.gudang+'" target="_blank" class="btn btn-danger">';
                        btn_ekspor += "<span class='glyphicon glyphicon-print'></span> Ekspor Excel";
                        btn_ekspor += "</a>";
                        $("#btn_ekspor").html(btn_ekspor);
                    }
                }
            });
            return false;
        }
        else 
        {
            return false;
        }
    });

</script>
