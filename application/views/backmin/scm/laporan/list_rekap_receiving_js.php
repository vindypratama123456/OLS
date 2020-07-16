<script type="text/javascript" src="<?php echo assets_url_backmin('js/plugins/daterangepicker/daterangepicker.js'); ?>"></script>

<script type="text/javascript">
    $(function(){
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
        
        if (diff_month > 3) {
            $("#submitExport").attr('disabled', '');
            $("#message-daterange").removeClass('display-none');
        } else {
            $("#submitExport").removeAttr('disabled');
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


    $("#formExportReceivingReport").validate({
        ignore: [],
        rules: {
            datefilter: {
                required: true,
            }
        }
    });
    
    $("#submitExport").on("click",function(){
        if ($("#formExportReceivingReport").valid() == true) {
            var dates = $("#datefilter").val().split(' - ');
            var diff_month = monthDiff(dates[0], dates[1]);

            if (diff_month <= 3) {
                var panel = $(".panel-result");
                var uri = $("#formExportReceivingReport").data('uri');

                var form_data = {
                    start_date  : dates[0],
                    end_date    : dates[1],
                    id_gudang   : $("#id_gudang").val()
                }

                $.ajax({
                    type: "POST",
                    data: form_data,
                    dataType: "json",
                    url: BASE_URL+uri,
                    beforeSend: function(){
                        panel_refresh(panel,"shown");
                        $("#submitExport").attr('disabled', '');
                    },
                    success: function(e){
                        panel_refresh(panel,"hidden");
                        $("#submitExport").removeAttr('disabled');
                        if(e.success == "true") {
                            window.location = BASE_URL + e.pathfile;
                        } else {
                            alert('Maaf, export excel anda gagal');
                            return false;
                        }
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

    // function workingDaysBetweenDates(startDate, endDate) {
    //     startDate = new Date(startDate);
    //     endDate = new Date(endDate);

    //     // Validate input
    //     if (endDate < startDate)
    //         return 0;

    //     // Calculate days between dates
    //     var millisecondsPerDay = 86400 * 1000; // Day in milliseconds
    //     startDate.setHours(0,0,0,1);  // Start just after midnight
    //     endDate.setHours(23,59,59,999);  // End just before midnight
    //     var diff = endDate - startDate;  // Milliseconds between datetime objects
    //     var days = Math.ceil(diff / millisecondsPerDay);

    //     return days;
    // }
</script>
