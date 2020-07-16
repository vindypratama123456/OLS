<script src="<?php echo js_url('jquery-validation/jquery.validate.min.js?v='.date('YmdHis')); ?>"></script>
<script src="<?php echo js_url('jquery-validation/localization/messages_id.js?v='.date('YmdHis')); ?>"></script>
<script src="<?php echo js_url('admin/common.js?v='.date('YmdHis')); ?>"></script>
<script src="<?php echo js_url('admin/moment.js?v='.date('YmdHis')); ?>"></script>
<script src="<?php echo js_url('admin/collapse.js?v='.date('YmdHis')); ?>"></script>
<script src="<?php echo js_url('admin/transition.js?v='.date('YmdHis')); ?>"></script>
<script src="<?php echo js_url('admin/app/comission.js?v='.date('YmdHis')); ?>"></script>
<script type="text/javascript">
	$("#print_excel").on("click", function(){
		alert("testing");

		$.ajax({
			type: "POST",
			data: data,
			dataType: "json",
			url:  BASE_URL + 'steam/export_excel_sap',
			async: true,
			beforeSend: function() 
			{
				$('#myloader').show();
				$('button').attr('disabled', true);
			},
			success:function(datas)
			{
				
				console.log(datas);
			}
		});
	});



	$(".btn-pilih").click(function(){
		var sap_no = ""; 
		sap_no = $(this).data("sapno");

		var data = {
			"sap_no": sap_no
		};

		$.ajax({
			type: "POST",
			// data: data,
			dataType: "json",
			url:  BASE_URL + 'steam/proses_sap_post/'+sap_no,
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

						sessionStorage.setItem("reloading", "true");
						sessionStorage.setItem("link_download", BASE_URI+"/"+datas.link);
						// var url = BASE_URL + "steam/comission_sap_detail/" + datas.sap_no;
						// $(location).attr('href',url);
						$(location).attr('href',BASE_URI+"/"+datas.redirect);
					}
					else 
					{
						bootAlert(datas.message);
						$('#myloader').hide();
						$('button').attr('disabled', false);
					}

     				// window.location.href = BASE_URI+"/"+datas.link;
     				// console.log(datas);
     			}
     		});
	});
</script>