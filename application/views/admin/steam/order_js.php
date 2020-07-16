<script src="<?php echo js_url('jquery-validation/jquery.validate.min.js?v='.date('YmdHis')); ?>"></script>
<script src="<?php echo js_url('jquery-validation/localization/messages_id.js?v='.date('YmdHis')); ?>"></script>
<script src="<?php echo js_url('admin/common.js?v='.date('YmdHis')); ?>"></script>
<script src="<?php echo js_url('admin/moment.js?v='.date('YmdHis')); ?>"></script>
<script src="<?php echo js_url('admin/collapse.js?v='.date('YmdHis')); ?>"></script>
<script src="<?php echo js_url('admin/transition.js?v='.date('YmdHis')); ?>"></script>
<script src="<?php echo js_url('admin/bootstrap-datetimepicker.min.js?v='.date('YmdHis')); ?>"></script>
<script src="<?php echo js_url('admin/app/product.js?v='.date('YmdHis')); ?>"></script>
<script type="text/javascript">
	var tomorrow = moment().add(1, 'days');
	$('#date_add').datetimepicker({
		format: 'YYYY-MM-DD HH:mm:ss',
		ignoreReadonly: true,
    })

    $('#date_add').on('dp.change', function(){
        $(this).data("DateTimePicker").hide();
    });

    $("#customer").select2({
        minimumInputLength: 3,
        tags: [],
        dataCache: [],
        ajax: {
            url: BASE_URL+'steam/get_customer',
            dataType: 'json',
            type: "POST",
            quietMillis: 50,
            data: function (params) {
                return {
                    search: params.term
                };
            }
        }
    });
    $("#sales").select2({
        minimumInputLength: 3,
        tags: [],
        ajax: {
            url: BASE_URL+'steam/get_sales',
            dataType: 'json',
            type: "POST",
            quietMillis: 50,
            data: function (params) {
                return {
                    search: params.term
                };
            },
            chace: true
        }
    });

    $("#id_order").on("change", function(){
        var reference = $(this).val();

        var data = {
            "reference" : reference 
        };

        $.ajax({
            type: "POST",
            data: data,
            dataType: "json",
            url:  BASE_URL + "steam/check_id_order",
            success:function(datas){
                if(datas.success)
                {
                    alert(datas.message);
                    $("#id_order").focus();
                }
                // if(datas.success == true) {
                //     var url = BASE_URL+'steam/comission_order_new';
                //     window.location.href = url;
                // }
                // else 
                // {
                //     bootAlert(datas.message);
                //     $('#myloader').hide();
                //     $('button').attr('disabled', false);
                //     var url = BASE_URL+'steam/order_add';
                //     window.location.href = url;
                // }
            }
        });
    });

    $('#order_steam_form').validate({
        errorClass: "has-error",
        errorElement: "span",

        rules: {
            customer: {
                required: true
            }
            ,sales: {
                required: true
            }
            ,date_add: {
                required: true
            }
            ,total_paid: {
                required: true,
                number: true
            }
        },
        submitHandler: function(form) {
            var conf = confirm('Yakin dengan semua isian data ini?');
            if(conf) {
                $('button').attr('disabled', true);
                $.ajax({
                    type: "POST",
                    data: new FormData(form),
                    dataType: "json",
                    url:  $('#order_steam_form').data('action'),
                    async: true,
                    contentType: false,
                    processData: false,
                    beforeSend: function() {
                        $('#myloader').show();
                        $('button').attr('disabled', true);
                    },
                    success:function(datas){
                        if(datas.success == true) {
                            var url = BASE_URL+'steam/comission_order_new';
                            window.location.href = url;
                        }
                        else 
                        {
                            bootAlert(datas.message);
                            $('#myloader').hide();
                            $('button').attr('disabled', false);
                            var url = BASE_URL+'steam/order_add';
                            window.location.href = url;
                        }
                    }
                });
                return false;
                // alert("testing submit form");
            }
        }
    });
</script>
