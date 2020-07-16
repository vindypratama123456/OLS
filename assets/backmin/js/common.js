$('body').on('hidden.bs.modal', '.modal', function() {
    $('.modal-content').empty();
    $(this).removeData('bs.modal', null);
    $(this).data('bs.modal', null);
});
function set_list(param){
	var opt = {
		"oLanguage":{
			"sSearch": "",
			"sInfo": "Hasil _START_ - _END_ dari _TOTAL_ data",
			"sLengthMenu": "_MENU_",
			"sZeroRecords": "Maaf, data tidak tersedia.",
			"sInfoEmpty": "Tampilkan 0 - 0 dari 0 data",
			"sInfoFiltered": "(disaring dari total _MAX_ data)",
			"oPaginate": {
                "sFirst": "&laquo;",
                "sPrevious": "&lsaquo;",
                "sNext": "&rsaquo;",
                "sLast": "&raquo;"
			}
		},
		"ordering": false,
		"bProcessing": true,
		"bServerSide": true,
		"bJQueryUI": true,
		"responsive": true,  
		"stateSave": true,
		// "lengthMenu": [[10, 25, 50, 100, 500], [10, 25, 50, 100, 500]],
		"lengthMenu": [[10, 100, 500, -1], [10, 100, 500, "All"]],
		"sAjaxDataProp": "data",
		'fnServerData': function(sSource, aoData, fnCallback){
	  		$.ajax({
				'dataType': 'json',
				'type'    : 'POST',
				'url'     : sSource,
				'data'    : aoData,
				'success' : fnCallback
		  	});
		}
	};
	opt.sAjaxSource=param['url'];
	return $(param['selector']).DataTable(opt);
}