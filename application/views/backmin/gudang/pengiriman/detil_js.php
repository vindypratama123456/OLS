<script type="text/javascript">
    $(document).ready(function () {
        $("#submitDetail").on("click", function () {
            var conf = confirm('Yakin ingin melanjutkan proses pengiriman?');
            if (conf) {
                var panel = $(".page-content-wrap");
                var uri = $("#frmDetilSPK").data('uri');
                $.ajax({
                    type: "POST",
                    data: $("#frmDetilSPK").serialize(),
                    dataType: "json",
                    url: BASE_URL + uri,
                    beforeSend: function () {
                        loading_button("submitDetail");
                        panel_refresh(panel, "shown");
                    },
                    success: function (e) {
                        setTimeout(function () {
                            panel_refresh(panel, "hidden");
                            if (e.success == "true") {
                                window.location.href = BASE_URL + e.redirect;
                            } else {
                                reset_button("submitDetail", "Kirim");
                                window.location.href = BASE_URL + e.redirect;
                            }
                        }, 500);
                    }
                });
                return false;
            } else {
                return false;
            }
        });
    });


    function cancelOrder(idTransaksi) {
        var conf = confirm('Yakin ingin membatalkan pesanan ini?');
        if (conf) {
            var panel = $(".page-content-wrap");
            var uri = $("#cancelOrder_" + idTransaksi).data('uri');
            $.ajax({
                type: "POST",
                dataType: "json",
                url: uri,
                beforeSend: function () {
                    loading_button("submitDetail");
                    panel_refresh(panel, "shown");
                },
                success: function (e) {
                    setTimeout(function () {
                        panel_refresh(panel, "hidden");
                        window.location.href = BASE_URL + e.redirect;
                    }, 500);
                }
            });
            return false;
        } else {
            return false;
        }
    }

    function printBAST(idTransaksi, idOrder) {
        window.open('<?php echo base_url(BACKMIN_PATH.'/gudangpengiriman/cetakBAST'); ?>/' + idTransaksi + '/' + idOrder, 'page', 'toolbar=0,scrollbars=1,location=0,statusbar=0,menubar=0,resizable=0,width=750,height=600,left=50,top=50,titlebar=no');
    }

    function printBASTFull(idTransaksi, idOrder) {
        window.open('<?php echo base_url(BACKMIN_PATH.'/gudangpengiriman/cetakBASTFull'); ?>/' + idTransaksi + '/' + idOrder, 'page', 'toolbar=0,scrollbars=1,location=0,statusbar=0,menubar=0,resizable=0,width=750,height=600,left=50,top=50,titlebar=no');
    }

    function printNotaJual(idTransaksi, idOrder) {
        window.open('<?php echo base_url(BACKMIN_PATH.'/gudangpengiriman/cetakNotaJual'); ?>/' + idTransaksi + '/' + idOrder, 'page', 'toolbar=0,scrollbars=1,location=0,statusbar=0,menubar=0,resizable=0,width=750,height=600,left=50,top=50,titlebar=no');
    }

    function printTagihan(idTransaksi, idOrder) {
        window.open('<?php echo base_url(BACKMIN_PATH.'/gudangpengiriman/cetakTagihan'); ?>/' + idTransaksi + '/' + idOrder, 'page', 'toolbar=0,scrollbars=1,location=0,statusbar=0,menubar=0,resizable=0,width=750,height=600,left=50,top=50,titlebar=no');
    }
</script>