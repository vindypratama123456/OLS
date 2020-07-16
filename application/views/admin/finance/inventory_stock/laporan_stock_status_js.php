<script type="text/javascript" src="<?php echo assets_url_backmin('js'); ?>/plugins/jquery/jquery-ui.min.js"></script>
<script type='text/javascript' src='<?php echo assets_url_backmin('js'); ?>/plugins/validate/jquery.validate.min.js'></script>
<script type="text/javascript" src="<?php echo assets_url_backmin('js'); ?>/plugins/datetimepicker/bootstrap-datetimepicker.js"></script>
<script type='text/javascript' src='<?php echo assets_url('js'); ?>/jquery-validation/localization/messages_id.js'></script>

<script type="text/javascript">
    $(function(){
        $("#print_pdf").addClass('display_none');
        $("#print_excel").addClass('display_none');

        $("#month_date").datetimepicker({
            viewMode: "months",
            format: "YYYY-MM"
        });
    });
    
    $("#formReportStockStatus").validate({
        ignore: [],
        rules: {
            date_month: {
                required: true,
            }
        }
    });
    
    $("#submitReport").on("click",function() {
        if ($("#formReportStockStatus").valid() == true) {
            var dates = $("#date_month").val().split('-');

            var panel = $(".panel-result");
            var uri = $("#formReportStockStatus").data('uri');

            var form_data = {
                month       : dates[1],
                year        : dates[0],
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

                            var grand_total             = 0;
                            var grand_total_allocated   = 0;
                            var grand_total_fisik       = 0;                            
                            var grand_total_booking     = 0;
                            var grand_total_available   = 0;
                            var all_gudang              = 0;

                            if ($("#id_gudang").val() !== '') {
                                all_gudang = 1;
                            }

                            $.each(response.data, function(key, value) {
                                var average_cost    = 0;
                                if (all_gudang == 1) {
                                    average_cost    = value.average_cost;
                                } else {
                                    average_cost    = parseFloat(value.total_cost / value.stok_fisik).toFixed(2);
                                }
                                row += '<tr>';
                                row += '<td>' + value.kode_buku + '</td>';
                                row += '<td>' + value.judul_buku + '</td>';
                                row += '<td class="text-right">' + numberFormat(value.stok_fisik) + '</td>';
                                row += '<td class="text-right">' + numberFormat(value.stok_booking) + '</td>';
                                row += '<td class="text-right">' + numberFormat(value.stok_available) + '</td>';
                                row += '<td class="text-right">' + numberFormat(average_cost) + '</td>';
                                row += '<td class="text-right">' + numberFormat(value.total_cost) + '</td>';
                                row += '<td class="text-right">' + numberFormat(value.allocated_cost ) + '</td>';
                                row += '</tr>';

                                grand_total_fisik       += parseFloat(value.stok_fisik);
                                grand_total_booking     += parseFloat(value.stok_booking);
                                grand_total_available   += parseFloat(value.stok_available);
                                grand_total             += parseFloat(value.total_cost);
                                grand_total_allocated   += parseFloat(value.allocated_cost);
                            });
                            row += '<tr class="tr_total">';
                            row += '<td colspan="2" class="text-right"><strong>Grand Total</strong></td>';
                            row += '<td class="text-right">' + numberFormat(parseFloat(grand_total_fisik)) + '</td>';
                            row += '<td class="text-right">' + numberFormat(parseFloat(grand_total_booking)) + '</td>';
                            row += '<td class="text-right">' + numberFormat(parseFloat(grand_total_available)) + '</td>';
                            row += '<td class="text-right">-</td>';
                            row += '<td class="text-right">' + numberFormat(parseFloat(grand_total).toFixed(2)) + '</td>';
                            row += '<td class="text-right">' + numberFormat(parseFloat(grand_total_allocated).toFixed(2)) + '</td>';
                            row += '</tr>';

                            $("#dataTable").html(row);
                        } else {
                            row += '<tr>';
                            row += '<td colspan="8"><center>Data tidak tersedia</center></td>';
                            row += '</tr>';
                            $("#dataTable").html(row);
                        }

                        // $("#print_pdf").removeClass('display_none');
                        $("#print_excel").removeClass('display_none');
                        $("#slug").val(dates[1] + '/' + dates[0] + '/' + $("#id_gudang").val());
                        $('#myloader').hide();
                    } else {
                        $('#myloader').hide();
                        $("#print_pdf").addClass('display_none');
                        $("#print_excel").addClass('display_none');
                        alert('Maaf, gagal untuk menammpilkan laporan');
                        return false;
                    }
                }
            });
            return false;
        } else {
            return false;
        }
    });

    function numberFormat(x) {
        // return x.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".");
        return x.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",");
    }

    function printStockStatus() {
        var slug = $("#slug").val();
        window.open('<?php echo base_url(ADMIN_PATH."/finance/printStockStatus/"); ?>' + slug,'page','toolbar=0,scrollbars=1,location=0,statusbar=0,menubar=0,resizable=0,width=1024,height=600,left=50,top=50,titlebar=no');
    }

    function printStockStatusExcel() {
        var slug = $("#slug").val();
        window.location.href = '<?php echo base_url(ADMIN_PATH."/finance/printStockStatusExcel/"); ?>' + slug;
    }

</script>
