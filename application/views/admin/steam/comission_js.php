<script src="<?php echo js_url('jquery-validation/jquery.validate.min.js?v='.date('YmdHis')); ?>"></script>
<script src="<?php echo js_url('jquery-validation/localization/messages_id.js?v='.date('YmdHis')); ?>"></script>
<script src="<?php echo js_url('admin/common.js?v='.date('YmdHis')); ?>"></script>
<script src="<?php echo js_url('admin/moment.js?v='.date('YmdHis')); ?>"></script>
<script src="<?php echo js_url('admin/collapse.js?v='.date('YmdHis')); ?>"></script>
<script src="<?php echo js_url('admin/transition.js?v='.date('YmdHis')); ?>"></script>
<script src="<?php echo js_url('admin/bootstrap-datetimepicker.min.js?v='.date('YmdHis')); ?>"></script>
<!-- <script src="<?php echo js_url('admin/app/product.js?v='.date('YmdHis')); ?>"></script> -->

<script type="text/javascript">
	
	$(document).ready(function(){
	    var datas = [];

	    let datatableNew;
	    if ($('#datatableNew').length > 0) {
	        datas['selector'] = 'datatableNew';
	        datas['url'] = BASE_URL + 'steam/comission_order_new_list';
	        datas['columns'] = [
	            // {'data': 'action'},
	            {'data': 'reference'},
	            {'data': 'school_name'},
	            {'data': 'date_add'},
	            {'data': 'total_paid', render: $.fn.dataTable.render.number(',', '.', 0)},
	            {'data': 'sales_person'},
	            {'data': 'percent_comission'},
	            {'data': 'percent_tax'},
	            {'data': 'amount_comission', render: $.fn.dataTable.render.number(',', '.', 0)}
	        ];
	        // datas['columnDefs'] = [
	        //     {className: 'text-center', targets: [0, 2, 5, 6, 7]},
	        //     {className: 'text-right', targets: [4, 8]},
	        //     {searchable: false, orderable: false, targets: [0]}
	        // ];
	        // datas['sort'] = [3, 'asc'];
	        datas['columnDefs'] = [
	            {className: 'text-center', targets: [1, 4, 5, 6]},
	            {className: 'text-right', targets: [3, 7]},
	            {searchable: false, orderable: false, targets: [0]}
	        ];
	        datas['sort'] = [2, 'asc'];
	        datatableNew = myDatatables(datas);
	        commonTools(datas['selector'], datatableNew);
	    }

	    let datatableProposed;
	    if ($('#datatableProposed').length > 0) {
	        datas['selector'] = 'datatableProposed';
	        datas['url'] = BASE_URL + 'steam/comission_list';
	        datas['columns'] = [
	            {'data': 'action'},
	            {'data': 'reference'},
	            {'data': 'school_name'},
	            {'data': 'date_add'},
	            {'data': 'total_paid', render: $.fn.dataTable.render.number(',', '.', 0)},
	            {'data': 'sales_person'},
	            {'data': 'percent_comission'},
	            {'data': 'percent_tax'},
	            {'data': 'amount_comission', render: $.fn.dataTable.render.number(',', '.', 0)},
	            {'data': 'date_proposed'}
	        ];
	        datas['columnDefs'] = [
	            {className: 'text-center', targets: [0, 2, 5, 6, 7, 9]},
	            {className: 'text-right', targets: [4, 8]},
	            {searchable: false, orderable: false, targets: [0]}
	        ];
	        datas['sort'] = [9, 'asc'];
	        datatableProposed = myDatatables(datas);
	        commonTools(datas['selector'], datatableProposed);
	    }

	    let datatableComissionSap;
	    if ($('#datatableComissionSap').length > 0) {
	        datas['selector'] = 'datatableComissionSap';
	        datas['url'] = BASE_URL + 'steam/comission_sap_list';
	        datas['columns'] = [
	            // {'data': 'action'},
	            {'data': 'sap_no'},
	            {'data': 'sap_date'},
	            // {'data': 'reference'},
	            // {'data': 'school_name'},
	            // {'data': 'date_add'},
	            {'data': 'total_paid', render: $.fn.dataTable.render.number(',', '.', 0)},
	            // {'data': 'sales_person'},
	            // {'data': 'percent_comission'},
	            // {'data': 'percent_tax'},
	            {'data': 'amount_comission', render: $.fn.dataTable.render.number(',', '.', 0)},
	            // {'data': 'date_proposed'}
	            {'data': 'notes'}
	        ];
	        datas['columnDefs'] = [
	            {className: 'text-center', targets: [0, 1]},
	            {className: 'text-right', targets: [2, 3]},
	            // {searchable: false, orderable: false, targets: [0]}
	        ];
	        datas['sort'] = [0, 'asc'];
	        datatableProposed = myDatatables(datas);
	        commonTools(datas['selector'], datatableProposed);
	    }

	    let datatableComissionSapProcess;
	    if ($('#datatableComissionSapProcess').length > 0) {
	        datas['selector'] = 'datatableComissionSapProcess';
	        datas['url'] = BASE_URL + 'steam/comission_sap_process_list';
	        datas['columns'] = [
	            // {'data': 'action'},
	            {'data': 'sap_no'},
	            {'data': 'sap_date'},
	            // {'data': 'reference'},
	            // {'data': 'school_name'},
	            // {'data': 'date_add'},
	            {'data': 'total_paid', render: $.fn.dataTable.render.number(',', '.', 0)},
	            // {'data': 'sales_person'},
	            // {'data': 'percent_comission'},
	            // {'data': 'percent_tax'},
	            {'data': 'amount_comission', render: $.fn.dataTable.render.number(',', '.', 0)},
	            // {'data': 'date_proposed'}
	            {'data': 'notes'}
	        ];
	        datas['columnDefs'] = [
	            {className: 'text-center', targets: [0, 1]},
	            {className: 'text-right', targets: [2, 3]},
	            // {searchable: false, orderable: false, targets: [0]}
	        ];
	        datas['sort'] = [0, 'asc'];
	        datatableProposed = myDatatables(datas);
	        commonTools(datas['selector'], datatableProposed);
	    }

	    $(".dataTables_wrapper").find(".dt-buttons").remove();
		// $("div.dt-buttons").remove();
		// $("#datatableNew_wrapper").find("#datatableNew_filter").removeClass("dt-buttons");
	});

	$(".btn-pilih").click(function(){
		var id_comission = [];
		var count = 0;
		$("input[type=checkbox]:checked").each(function () {
			if($(this).val() != "")
			{
				id_comission.push($(this).val());	
			}
		});

		var data = {
			"id_comission": id_comission
		};
		
		if(id_comission.length > 0)
		{
			$.ajax({
				type: "POST",
				data: data,
				dataType: "json",
				url:  BASE_URL + 'steam/comission_list_proses',
				async: true,
				// contentType: false,
				// processData: false,
				beforeSend: function() 
				{
					$('#myloader').show();
					$('button').attr('disabled', true);
				},
				success:function(datas)
				{
					// alert(datas.success);
					if(datas.success == true) 
					{
						$('#myloader').hide();
						$('button').attr('disabled', false);
						// window.location.href = BASE_URI+"/"+datas.link;
						
						// window.open(BASE_URI+"/"+datas.link,
						//   '_blank' // <- This is what makes it open in a new window.
						// );
						// document.location.reload(true);

						var url = BASE_URL + "steam/comission_sap_detail/" + datas.sap_no;
          				$(location).attr('href',url);
					}
					else 
					{
						bootAlert(datas.message);
						$('#myloader').hide();
						$('button').attr('disabled', false);
                    }

     				// window.location.href = BASE_URI+"/"+datas.link;
                    console.log(datas);
                }
            });
			return false;
		}
		else
		{
			alert("Silahkan pilih data yang akan diproses");
		}
	});

	$("#check_all").change(
		function(){
			if(this.checked)
			{
				// alert("checked");
				$("input[type=checkbox]").prop("checked", true);

			}
			else
			{
				// alert("unchecked");
				$("input[type=checkbox]").prop("checked", false);
			}
		}
	);

	$("#check_all_order").change(
		function(){
			if(this.checked)
			{
				// alert("checked");
				$("input[type=checkbox]").prop("checked", true);

			}
			else
			{
				// alert("unchecked");
				$("input[type=checkbox]").prop("checked", false);
			}
		}
	);

    myAjax('frm-proposed', 'steam/comission_order_new_post', 'Yakin ingin mengajukan komisi untuk pesanan ini?', 'steam/comission_order_new');
    // myAjax('frm-approve', 'comission/approvePost', 'Yakin ingin menyetujui komisi untuk pesanan ini?', 'comission/proposed');
    // myAjax('frm-processed', 'comission/processedPost', 'Yakin ingin memproses pengajuan komisi ini?', 'comission/proposed');
</script>