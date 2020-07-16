<script type="text/javascript" src="<?php echo assets_url_backmin('js'); ?>/plugins/jquery/jquery-ui.min.js"></script>
<script type='text/javascript' src='<?php echo assets_url_backmin('js'); ?>/plugins/validate/jquery.validate.min.js'></script>
<script type="text/javascript" src="<?php echo assets_url_backmin('js'); ?>/plugins/datetimepicker/bootstrap-datetimepicker.js"></script>
<script type='text/javascript' src='<?php echo assets_url('js'); ?>/jquery-validation/localization/messages_id.js'></script>

<script type="text/javascript">
    $(function(){
        $("#print_excel").addClass('display_none');

        $("#month_date").datetimepicker({
            viewMode: "months",
            format: "YYYY-MM"
        });
    });
    
    $("#formReportSalesAnalysis").validate({
        ignore: [],
        rules: {
            date_month: {
                required: true,
            }
        }
    });
    
    $("#submitReport").on("click",function() {
        if ($("#formReportSalesAnalysis").valid() == true) {
            var dates = $("#date_month").val().split('-');

            var panel = $(".panel-result");
            var uri = $("#formReportSalesAnalysis").data('uri');

            var form_data = {
                month       : dates[1],
                year        : dates[0],
                id_gudang   : $("#id_gudang").val(),
                type        : $("#type").val()
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

                            var grand_total_qty             = 0;
                            var grand_cost                  = 0;
                            var grand_total_price           = 0;
                            var grand_total_year_qty        = 0;
                            var grand_year_cost             = 0;
                            var grand_total_year_price      = 0;
                            var all_gudang              = 0;

                            if ($("#id_gudang").val() !== '') {
                                all_gudang = 1;
                            }

                            $.each(response.data, function(key, value) {
                                var cost        = 0;
                                if (all_gudang == 1) {
                                    cost        = value.cost;
                                } else {
                                    cost        = value.cost_all;
                                }
                                row += '<tr>';
                                row += '<td>' + value.kode_buku + '</td>';
                                row += '<td>' + value.judul_buku + '</td>';
                                row += '<td class="text-right">' + numberFormat(value.qty) + '</td>';
                                row += '<td class="text-right">' + numberFormat(cost) + '</td>';
                                row += '<td class="text-right">' + numberFormat(value.total_price) + '</td>';
                                row += '<td class="text-right"> 0 </td>';
                                row += '<td class="text-right">' + numberFormat(value.total_price) + '</td>';
                                row += '<td class="text-right">' + numberFormat(value.year_qty) + '</td>';
                                row += '<td class="text-right">' + numberFormat(value.year_cost_all) + '</td>';
                                row += '<td class="text-right">' + numberFormat(value.year_total_price) + '</td>';
                                row += '<td class="text-right"> 0 </td>';
                                row += '<td class="text-right">' + numberFormat(value.year_total_price) + '</td>';
                                row += '</tr>';

                                grand_total_qty             += parseFloat(value.qty);
                                grand_cost                  += parseFloat(cost);
                                grand_total_price           += parseFloat(value.total_price);
                                grand_total_year_qty        += parseFloat(value.year_qty);
                                grand_year_cost             += parseFloat(value.year_cost_all);
                                grand_total_year_price      += parseFloat(value.year_total_price);
                            });
                            
                            row += '<tr class="tr_total">';
                            row += '<td colspan="2" class="text-right"><strong>Grand Total</strong></td>';
                            row += '<td class="text-right">' + numberFormat(parseFloat(grand_total_qty)) + '</td>';
                            row += '<td class="text-right">' + numberFormat(parseFloat(grand_cost).toFixed(2)) + '</td>';
                            row += '<td class="text-right">' + numberFormat(parseFloat(grand_total_price).toFixed(2)) + '</td>';
                            row += '<td class="text-right">-</td>';
                            row += '<td class="text-right">' + numberFormat(parseFloat(grand_total_price).toFixed(2)) + '</td>';
                            row += '<td class="text-right">' + numberFormat(parseFloat(grand_total_year_qty)) + '</td>';
                            row += '<td class="text-right">' + numberFormat(parseFloat(grand_year_cost).toFixed(2)) + '</td>';
                            row += '<td class="text-right">' + numberFormat(parseFloat(grand_total_year_price).toFixed(2)) + '</td>';
                            row += '<td class="text-right">-</td>';
                            row += '<td class="text-right">' + numberFormat(parseFloat(grand_total_year_price).toFixed(2)) + '</td>';
                            row += '</tr>';

                            $("#dataTable").html(row);
                        } else {
                            row += '<tr>';
                            row += '<td colspan="12"><center>Data tidak tersedia</center></td>';
                            row += '</tr>';
                            $("#dataTable").html(row);
                        }

                        $("#print_excel").removeClass('display_none');
                        $("#slug").val(dates[1] + '/' + dates[0] + '/' + $("#id_gudang").val());
                        $('#myloader').hide();
                    } else {
                        $('#myloader').hide();
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
        return x.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",");
    }

    function printSalesAnalysisExcel() {
        var slug = $("#slug").val();
        var type = $("#type").val();
        window.location.href = '<?php echo base_url(ADMIN_PATH."/finance/printSalesAnalysisExcel/"); ?>' + type + '/' + slug;
    }

</script>
