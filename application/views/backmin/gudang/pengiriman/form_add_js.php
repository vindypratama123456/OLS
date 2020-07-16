<script type="text/javascript"
        src="<?php echo assets_url_backmin('js'); ?>/plugins/bootstrap/bootstrap-select.js"></script>
<script type="text/javascript"
        src="<?php echo assets_url_backmin('js'); ?>/plugins/datatables/jquery.dataTables.min.js"></script>
<script type="text/javascript">
    var tableListTransaksi = {
        url: "<?php echo base_url().BACKMIN_PATH.'/gudangpengiriman/listTransaksi'; ?>",
        columns: [
            {"data": "id_transaksi"},
            {"data": "kode_transaksi"},
            {"data": "tujuan"},
            {"data": "total_jumlah"},
            {"data": "total_berat"},
            {"data": "action"}
        ],
        columnDefs: [{
            targets: 5,
            orderable: false
        }],
        sort: [0, 'desc']
    };

    var frmAddSPK = $("#formAddSPK");
    frmAddSPK.validate({
        ignore: [],
        rules: {
            ekspeditur: {
                required: true
            },
            nopol: {
                required: true
            },
            nama_supir: {
                required: true
            },
            hp_supir: {
                required: true
            }
        }
    });

    /* Fungsi formatRupiah */
    function formatRupiah(angka, prefix){
        var number_string = angka.replace(/[^,\d]/g, '').toString(),
        split           = number_string.split(','),
        sisa            = split[0].length % 3,
        rupiah          = split[0].substr(0, sisa),
        ribuan          = split[0].substr(sisa).match(/\d{3}/gi);
     
        // tambahkan titik jika yang di input sudah menjadi angka ribuan
        if(ribuan){
            separator = sisa ? '.' : '';
            rupiah += separator + ribuan.join('.');
        }
     
        rupiah = split[1] != undefined ? rupiah + ',' + split[1] : rupiah;
        return prefix == undefined ? rupiah : (rupiah ? 'Rp. ' + rupiah : '');
    }

    $(document).on('change', '.check_transaksi', function() {
        var data = [];
        // data["count"] = 2;
        var id = this.id;
        var dataTransaksi = $(this).val().split("##");
        var id_cust = dataTransaksi[3];
        var id_order = dataTransaksi[4];
        if(this.checked) {
            $.ajax({
                type : "POST",
                url  : BASE_URL + 'backmin/gudangpengiriman/checkStatusBayar',
                dataType : "json",
                data : {id_customer:id_cust},
                success: function(result){
                    // alert(result[0].id_order_count);
                    // Kode untuk memberitahukan bahwa pesanan sekolah sebelumnya belum lunas
                    // if(result[0].id_order_count > 1){
                    //     alert('Sekolah belum melunasi pesanan sebelumnya');
                    //     $('#'+id).prop("checked", false);
                    //     $('#'+id).prop("disabled", true);
                    // }

                    if(result.length > 0)
                    {
                        /**
                         * vindy
                         * 2019-06-25
                         * Menambahkan fungsi untuk pengecekan order telah disetujui apa belum
                         * Awal
                         */
                        
                        $.ajax({
                            type : "POST",
                            url  : BASE_URL + 'backmin/gudangpengiriman/checkPersetujuanRSM',
                            dataType : "json",
                            data : {id_order:id_order},
                            success: function(result2){
                                var nilai_piutang_temp1 = result2[0].nilai_piutang;
                                var nilai_piutang_temp2 = nilai_piutang_temp1.replace(',','');
                                var nilai_piutang_var = nilai_piutang_temp2.replace('.',',');

                                if(result2[0].persetujuan_keterangan == "" || result2[0].persetujuan_keterangan == null)
                                {
                                    
                                    alert('Sekolah belum melunasi pesanan sebelumnya dengan nilai piutang ' + formatRupiah(nilai_piutang_var, 'Rp. ') + '. Pesanan ini tidak dapat diproses.');
                                    $('#'+id).prop("checked", false);
                                    $('#'+id).prop("disabled", true);
                                }

                                // if(result2[0].persetujuan_keterangan != "" || result2[0].persetujuan_keterangan != null){
                                //     alert('berhasil');
                                // }
                            }
                        });


                        // alert('Sekolah belum melunasi pesanan sebelumnya dengan nilai piutang ' + result[0].nilai_piutang);
                        // $('#'+id).prop("checked", false);
                        // $('#'+id).prop("disabled", true);
                        
                        /**
                         * Akhir
                         */
                    }
                }
            });
        }
    });

    $("#submitForm").on("click", function () {
        if (frmAddSPK.valid() == true) {
            $("#submitForm").attr('disabled', 'true');
            var conf = confirm('Yakin ingin melanjutkan proses ini?');
            if (conf) {
                var panel = $(".page-content-wrap");
                var uri = frmAddSPK.data('uri');
                var id_transaksi = [];
                var total_jumlah = [];
                var total_berat = [];
                $("input[type=checkbox]:checked").each(function () {
                    var dataTransaksi = $(this).val().split("##");
                    id_transaksi.push(dataTransaksi[0]);
                    total_jumlah.push(dataTransaksi[1]);
                    total_berat.push(dataTransaksi[2]);
                });
                id_transaksi = id_transaksi.join();
                total_jumlah = total_jumlah.join();
                total_berat = total_berat.join();
                if (id_transaksi !== "") {
                    $.ajax({
                        type: "POST",
                        data: frmAddSPK.serialize() + '&transaksi=' + id_transaksi + '&jumlah=' + total_jumlah + '&berat=' + total_berat,
                        dataType: "json",
                        url: BASE_URL + uri,
                        beforeSend: function () {
                            loading_button("submitForm");
                            panel_refresh(panel, "shown");
                        },
                        success: function (e) {
                            setTimeout(function () {
                                panel_refresh(panel, "hidden");
                                if (e.success == "true") {
                                    window.location.href = BASE_URL + e.redirect;
                                } else {
                                    reset_button("submitForm", "P r o s e s");
                                    window.location.href = BASE_URL + e.redirect;
                                }
                            }, 500);
                        }
                    });
                    return false;
                } else {
                    alert('Mohon pilih/centang transaksi !!!');
                    $("#submitForm").removeAttr('disabled');
                    return false;
                }
            } else {
                $("#submitForm").removeAttr('disabled');
                return false;
            }
        } else {
            return false;
        }
    });

    $("#formAddEkspeditur").validate({
        ignore: [],
        rules: {
            nama_ekspeditur: {
                required: true,
                minlength: 2
            },
            alamat_ekspeditur: {
                minlength: 3
            }
        }
    });
    $("#submitEkspeditur").on("click", function () {
        $(this).attr('disabled', true);
        var frmAddEkspeditur = $("#formAddEkspeditur");
        if (frmAddEkspeditur.valid() == true) {
            var conf = confirm('Yakin ingin menambahkan ekspeditur?');
            if (conf) {
                var panel = $(".page-content-wrap");
                var uri = frmAddEkspeditur.data('uri');
                $.ajax({
                    type: "POST",
                    data: frmAddEkspeditur.serialize(),
                    dataType: "json",
                    url: BASE_URL + uri,
                    beforeSend: function () {
                        loading_button("submitEkspeditur");
                        panel_refresh(panel, "shown");
                    },
                    success: function (e) {
                        panel_refresh(panel, "hidden");
                        alert(e.message);
                        if (e.success == "true") {
                            $("#select_ekspeditur").load(location.href + ' #select_ekspeditur>*', '');
                            $("#form_ekspeditur").load(location.href + ' #form_ekspeditur>*', '');
                            $("#tambahEkspeditur").modal("hide");
                        }
                        $('#submitEkspeditur').prop("disabled", false);
                        $('#submitEkspeditur').text("P r o s e s");
                    }
                });
                return false;
            } else {
                return false;
            }
        } else {
            return false;
        }
    });
</script>
