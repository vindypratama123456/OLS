<?php /* <script type="text/javascript" src="<?php echo assets_url_backmin('js'); ?>/bootstrap.min.js"></script> */ ?>
<script type="text/javascript" src="<?php echo assets_url_backmin('js'); ?>/plugins/jquery/jquery-ui.min.js"></script>
<script type='text/javascript' src='<?php echo assets_url_backmin('js'); ?>/plugins/validate/jquery.validate.min.js'></script>
<script type="text/javascript" src="<?php echo assets_url_backmin('js/plugins/daterangepicker/daterangepicker.js'); ?>"></script>
<script type='text/javascript' src='<?php echo assets_url('js'); ?>/jquery-validation/localization/messages_id.js'></script>
<script type="text/javascript" src="<?php echo assets_url_backmin('js'); ?>/plugins/datatables/jquery.dataTables.min.js"></script>

<script type="text/javascript">
    $(document).ready(function(){
        $("#table_transaction").DataTable();
    });
    $(function(){
        // $("#print_pdf").addClass('display_none');
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
                // id_gudang   : $("#id_gudang").val()
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
                        // var row = '';
                        if (Object.keys(response.data).length > 0) {
                            console.log(response.data);

                            var t = $('#table_transaction').DataTable();
                            t.clear().draw();
                            $.each(response.data, function(key, value) {
                            t.row.add( [
                                value.kode_transaksi,
                                value.tgl_transaksi,
                                value.status_transaksi,
                                value.keterangan,
                                value.asal,
                                value.tujuan,
                                value.kode_buku,
                                value.qty
                            ] ).draw();

                            });
                            
                        } else {
                            var t = $('#table_transaction').DataTable();
                            t.clear().draw();
                        }
                        
                        // $("#pagination").html(response.pagination);
                        // $("#print_pdf").removeClass('display_none');
                        $("#print_excel").removeClass('display_none');
                        $("#slug").val(start_date + '/' + end_date);
                        $('#myloader').hide();
                        // $("#table_transaction").DataTable();
                    } else {
                        $('#myloader').hide();
                        // $("#print_pdf").addClass('display_none');
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

    // function printReceiving() {
    //     var slug = $("#slug").val();
    //     window.open('<?php echo base_url(ADMIN_PATH."/finance/printReceiving/"); ?>' + slug,'page','toolbar=0,scrollbars=1,location=0,statusbar=0,menubar=0,resizable=0,width=1024,height=600,left=50,top=50,titlebar=no');
    // }

    function printReceivingExcel() {
        var slug = $("#slug").val();
        window.location.href = '<?php echo base_url(BACKMIN_PATH."/scmlaporan/printtransaksiexcel/"); ?>' + slug;
    }

</script>
