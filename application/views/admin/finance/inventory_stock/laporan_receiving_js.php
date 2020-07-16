<?php /* <script type="text/javascript" src="<?php echo assets_url_backmin('js'); ?>/bootstrap.min.js"></script> */ ?>
<script type="text/javascript" src="<?php echo assets_url_backmin('js'); ?>/plugins/jquery/jquery-ui.min.js"></script>
<script type='text/javascript' src='<?php echo assets_url_backmin('js'); ?>/plugins/validate/jquery.validate.min.js'></script>
<script type="text/javascript" src="<?php echo assets_url_backmin('js/plugins/daterangepicker/daterangepicker.js'); ?>"></script>
<script type='text/javascript' src='<?php echo assets_url('js'); ?>/jquery-validation/localization/messages_id.js'></script>

<script type="text/javascript">
    $(function(){
        $("#print_pdf").addClass('display_none');
        $("#print_excel").addClass('display_none');

        $('input[name="datefilter"]').daterangepicker({
            autoUpdateInput: false,
            locale: {
                cancelLabel: 'Clear'
            }
        });

        $('input[name="datefilter"]').on('apply.daterangepicker', function(ev, picker) {
            $(this).val(picker.startDate.format('YYYY/MM/DD') + ' - ' + picker.endDate.format('YYYY/MM/DD'));
        });

        $('input[name="datefilter"]').on('cancel.daterangepicker', function(ev, picker) {
            $(this).val('');
        });
    });

    $("#datefilter").on('change', function(){
        var dates = $(this).val().split(' - ');
        var diff_month = monthDiff(dates[0], dates[1]);
        
        // if (diff_month > 3) {
        //     $("#submitReport").attr('disabled', '');
        //     $("#message-daterange").removeClass('display-none');
        // } else 
        if ($(this).val() == '') {
            $("#submitReport").attr('disabled', '');
        } else {
            $("#submitReport").removeAttr('disabled');
            $("#message-daterange").addClass('display-none');
        }
    });

    function monthDiff(startDate, endDate) {
        var months;
        var startDate = new Date(startDate);
        var endDate = new Date(endDate);
        startDate.setHours(0,0,0,1);
        endDate.setHours(23,59,59,999);
        
        months = (endDate.getFullYear() - startDate.getFullYear()) * 12;
        months -= startDate.getMonth();
        months += endDate.getMonth();
        return months <= 0 ? 1 : months + 1;
    }


    $("#formReportReceiving").validate({
        ignore: [],
        rules: {
            datefilter: {
                required: true,
            }
        }
    });
    
    $("#submitReport").on("click",function() {
        if ($("#formReportReceiving").valid() == true) {
            var dates = $("#datefilter").val().split(' - ');
            var diff_month = monthDiff(dates[0], dates[1]);
            
            var start_date = dates[0].replace(/\//g, '-');
            var end_date = dates[1].replace(/\//g, '-');

            // if (diff_month <= 3) {
            var panel = $(".panel-result");
            var uri = $("#formReportReceiving").data('uri');

            var form_data = {
                start_date  : start_date,
                end_date    : end_date,
                id_gudang   : $("#id_gudang").val()
            }

            $.ajax({
                type: "POST",
                data: form_data,
                dataType: "json",
                url: uri,
                beforeSend: function(){
                    $('#myloader').show();
                    $("#submitReport").attr('disabled', '');
                },
                success: function(response){
                    $("#submitReport").removeAttr('disabled');
                    if(response.success == "true") {
                        var row = '';
                        if (Object.keys(response.data).length > 0) {
                            var grand_total_qty     = 0;
                            var grand_total         = 0;
                            $.each(response.data, function(key, value) {
                                row += '<tr>';
                                row += '<td colspan="10"><strong>Periode : ' + value.nama_periode + '</strong></td>';
                                row += '</tr>';
                                $.each(value.row1, function(key1, value1) {
                                    var subtotal_qty    = 0;
                                    var subtotal        = 0;
                                    row += '<tr>';
                                    row += '<td colspan="10"><strong>Lokasi : ' + value1.nama_gudang.substring(7) + '</strong></td>';
                                    row += '</tr>';
                                    $.each(value1.row2, function(key2, value2) {
                                        $.each(value2.row3, function(key3, value3) {
                                            row += '<tr>';
                                            if (key3 == 0) {
                                                row += '<td class="text-center text-middle" rowspan="' + Object.keys(value2.row3).length + '">' + value2.nama_bulan + '</td>';
                                            }
                                            row += '<td>' + value3.kode_buku + '</td>';
                                            row += '<td>' + value3.judul_buku + '</td>';
                                            row += '<td class="text-right">' + numberFormat(value3.jumlah_buku, 0) + '</td>';
                                            row += '<td class="text-right">' + numberFormat(value3.unit_cost) + '</td>';
                                            row += '<td class="text-right">' + numberFormat(value3.total_cost) + '</td>';
                                            row += '<td class="text-right">0</td>';
                                            row += '<td class="text-right">0</td>';
                                            row += '<td class="text-right">' + numberFormat(value3.total_cost) + '</td>';
                                            row += '</tr>';

                                            subtotal_qty    += parseInt(value3.jumlah_buku);
                                            subtotal        += value3.total_cost;
                                            grand_total_qty += parseInt(value3.jumlah_buku);
                                            grand_total     += value3.total_cost;
                                        });
                                    });
                                    row += '<tr class="tr_total">';
                                    row += '<td colspan="3" class="text-right"><strong>Sub Total</strong></td>';
                                    row += '<td class="text-right">' + numberFormat(subtotal_qty, 0) + '</td>';
                                    row += '<td class="text-right">-</td>';
                                    row += '<td class="text-right">' + numberFormat(subtotal) + '</td>';
                                    row += '<td class="text-right">0</td>';
                                    row += '<td class="text-right">0</td>';
                                    row += '<td class="text-right">' + numberFormat(subtotal) + '</td>';
                                    row += '</tr>';
                                });
                            });
                            row += '<tr class="tr_total">';
                            row += '<td colspan="3" class="text-right"><strong>Grand Total</strong></td>';
                            row += '<td class="text-right">' + numberFormat(grand_total_qty, 0) + '</td>';
                            row += '<td class="text-right">-</td>';
                            row += '<td class="text-right">' + numberFormat(grand_total) + '</td>';
                            row += '<td class="text-right">0</td>';
                            row += '<td class="text-right">0</td>';
                            row += '<td class="text-right">' + numberFormat(grand_total) + '</td>';
                            row += '</tr>';

                            $("#dataTable").html(row);
                        } else {
                            row += '<tr>';
                            row += '<td colspan="8"><center>Data tidak tersedia</center></td>';
                            row += '</tr>';
                            $("#dataTable").html(row);
                        }
                        
                        // $("#pagination").html(response.pagination);
                        $("#print_pdf").removeClass('display_none');
                        $("#print_excel").removeClass('display_none');
                        $("#slug").val(start_date + '/' + end_date + '/' + $("#id_gudang").val());
                        $('#myloader').hide();
                    } else {
                        $('#myloader').hide();
                        $("#print_pdf").addClass('display_none');
                        $("#print_excel").addClass('display_none');
                        bootAlert('Maaf, gagal untuk menampilkan laporan');
                        return false;
                    }
                }
            });
            return false;
            // } else {
            //     return false;
            // }
        } else {
            return false;
        }
    });

    function numberFormat(x, comma = 1) {
        if (comma == 1) {
            return x.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".") + ',00';
        } else {
            return x.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".");
        }
    }

    function printReceiving() {
        var slug = $("#slug").val();
        window.open('<?php echo base_url(ADMIN_PATH."/finance/printReceiving/"); ?>' + slug,'page','toolbar=0,scrollbars=1,location=0,statusbar=0,menubar=0,resizable=0,width=1024,height=600,left=50,top=50,titlebar=no');
    }

    function printReceivingExcel() {
        var slug = $("#slug").val();
        window.location.href = '<?php echo base_url(ADMIN_PATH."/finance/printReceivingExcel/"); ?>' + slug;
    }

</script>
