window.addEventListener('offline', function(e) {
    bootAlert('Tidak dapat terhubung ke internet. Silahkan periksa koneksi anda.');
});
$(function($) {
    $.ajaxSetup({
        data: {
            'csrftokenbs': CSRF_HASH
        }
    });
});
$.fn.datepicker.dates['en'] = {
    days: ['Minggu', 'Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu'],
    daysShort: ['Min', 'Sen', 'Sel', 'Rab', 'Kam', 'Jum', 'Sab'],
    daysMin: ['Mi', 'Se', 'Sl', 'Ra', 'Ka', 'Ju', 'Sb'],
    months: ['Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'Nopember', 'Desember'],
    monthsShort: ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Agu', 'Sep', 'Okt', 'Nop', 'Des'],
    today: 'Hari Ini',
    clear: 'Hapus',
    format: 'yyyy-mm-dd',
    titleFormat: 'MM yyyy', /* Leverages same syntax as 'format' */
    weekStart: 1
};
$('body').on('hidden.bs.modal', '.modal', function(){
    $('.modal-content').empty();
    $(this).removeData('bs.modal', null);
    $(this).data('bs.modal', null);
});
$('select').select2({
    dropdownAutoWidth : true
}).on('change', function (e) {
    $(this).valid()
});
$('.input-daterange').datepicker({
    autoclose: true,
    format: 'yyyy-mm-dd',
    language: 'en',
    todayBtn: 'linked',
    weekStart: 1
});
$('a:contains("BotDetect CAPTCHA Library for CodeIgniter")').remove();
var oldExportAction = function (self, e, dt, button, config) {
    if (button[0].className.indexOf('buttons-excel') >= 0) {
        if ($.fn.dataTable.ext.buttons.excelHtml5.available(dt, config)) {
            $.fn.dataTable.ext.buttons.excelHtml5.action.call(self, e, dt, button, config);
        }
    }
    if (button[0].className.indexOf('buttons-csv') >= 0) {
        if ($.fn.dataTable.ext.buttons.excelHtml5.available(dt, config)) {
            $.fn.dataTable.ext.buttons.excelHtml5.action.call(self, e, dt, button, config);
        }
    }
};
var newExportAction = function (e, dt, button, config) {
    var self = this;
    var oldStart = dt.settings()[0]._iDisplayStart;
    dt.one('preXhr', function (e, s, data) {
        // Just this once, load all data from the server...
        data.start = 0;
        data.length = 2147483647;
        dt.one('preDraw', function (e, settings) {
            // Call the original action function
            oldExportAction(self, e, dt, button, config);
            dt.one('preXhr', function (e, s, data) {
                // DataTables thinks the first item displayed is index 0, but we're not drawing that.
                // Set the property to what it was before exporting.
                settings._iDisplayStart = oldStart;
                data.start = oldStart;
            });
            // Reload the grid with the original page. Otherwise, API functions like table.cell(this) don't work properly.
            setTimeout(dt.ajax.reload, 0);
            // Prevent rendering of the full data to the DOM
            return false;
        });
    });
    // Requery the server with the new one-time export settings
    dt.ajax.reload();
};
function bootAlert(message) {
    bootbox.alert({
        title: 'Informasi',
        message: message,
        callback: function () {
            $('.bootbox').modal('hide').data('bs.modal', null);
        }
    });
}
function bootAlertRedirect(message, redirect) {
    bootbox.alert({
        title: 'Informasi',
        message: message,
        callback: function () {
            $('.bootbox').modal('hide').data('bs.modal', null);
            if (redirect) {
                window.location = BASE_URL + redirect;
            } else {
                window.location.reload(true);
            }
        }
    });
}
function commonTools(selector, table) {
    table.buttons().containers().prependTo('.dataTables_filter');
    $('div.dataTables_filter input').unbind().keyup( function (e) {
        if (e.keyCode === 13) {
            table.search( this.value ).draw();
        }
    } );
    $('#reset-filter').on('click', function(){
        $('input[type="text"]').val('');
        table.search('').columns().search('').draw();
    });
}
function disableBtn(elementId) {
    document.getElementById(elementId).disabled = true;
}
function enableBtn(elementId) {
    document.getElementById(elementId).disabled = false;
}
function myAjax(form, url, message, redirect) {
    var myForm = $('#'+form);
    myForm.submit(function(e) {
        e.preventDefault();
        bootbox.confirm({
            title: 'Konfirmasi',
            message: message,
            callback: function (result) {
                if (result) {
                    $('button').attr('disabled', true);
                    $.ajax({
                        type: 'POST',
                        data: myForm.serialize(),
                        dataType: 'json',
                        url: BASE_URL + url,
                        async: true,
                        beforeSend: function() {
                            $('button').attr('disabled', true);
                            $('.bootbox').modal('hide').data('bs.modal', null);
                            $('#myloader').show();
                        },
                        success:function(datas){
                            if(datas.success === 'true') {
                                window.location.reload();
                                window.location = BASE_URL + redirect;
                            }
                            else {
                                bootAlert(datas.message);
                                $('#myloader').hide();
                                $('button').attr('disabled', false);
                            }
                        }
                    });
                    return false;
                }
            }
        });
    });
}
function myDatatables(param, start_date, end_date, cari) {
    var selector = $('#'+param['selector']);
    options = {
        'ajax': {
            'url': param['url'],
            'type': 'POST',
            'data': {
                'start_date': start_date,
                'end_date': end_date,
                'cari': cari
            },
            'dataSrc': function (json) {
                $(document).CsrfAjaxGet();
                return json.data;
            }
        },
        'buttons': [
            {
                extend: 'excelHtml5',
                autoFilter: false,
                text: 'Ekspor Excel &raquo;',
                action: newExportAction,
                customizeData: function ( data ) {
                    for (var i=0; i<data.body.length; i++){
                        for (var j=0; j<data.body[i].length; j++ ){
                            var test = data.body[i][j];

                            if(test.match(/^[0-9]+$/) != null)
                            {
                                if(test.length > 15)
                                {
                                    data.body[i][j] = data.body[i][j] + '\u200C';
                                }
                            }
                            // data.body[i][j] = data.body[i][j] + '\u200C';
                        }
                    }
                } 
            },
            {
                extend: 'csvHtml5',
                autoFilter: false,
                text: 'Ekspor CSV &raquo;',
                action: newExportAction,
                customizeData: function ( data ) {
                    for (var i=0; i<data.body.length; i++){
                        for (var j=0; j<data.body[i].length; j++ ){
                            var test = data.body[i][j];

                            if(test.match(/^[0-9]+$/) != null)
                            {
                                if(test.length > 15)
                                {
                                    data.body[i][j] = data.body[i][j] + '\u200C';
                                }
                            }
                            // data.body[i][j] = data.body[i][j] + '\u200C';
                        }
                    }
                } 
            }
        ],
        'columns': param['columns'],
        'columnDefs': param['columnDefs'],
        'decimal': ',',
        'deferRender': true,
        'dom': 'Blfrtip',
        'drawCallback': param['drawCallback'],
        'initComplete': param['initComplete'],
        'language': {
            'processing': '<span style="width:100%;height:100%;"><img src="'+BASE_URI+'assets/img/preloader.gif"></span>',
            'sLengthMenu': '_MENU_',
            'searchPlaceholder': 'Pencarian...',
            'sZeroRecords': 'Tidak ditemukan data yang sesuai',
            'sInfo': 'Data _START_ - _END_ dari total _TOTAL_',
            'sInfoEmpty': 'Data 0 - 0 dari 0 entri',
            'sInfoFiltered': '(disaring dari _MAX_ data)',
            'sInfoPostFix': '',
            'sSearch': '',
            'sUrl': '',
            'oPaginate': {
                'sFirst': '&laquo;',
                'sPrevious': '&lsaquo;',
                'sNext': '&rsaquo;',
                'sLast': '&raquo;'
            }
        },
        'lengthMenu': [[25, 100, 500], [25, 100, 500]],
        'order': param['sort'],
        'pagingType': 'full',
        'processing': true,
        'responsive': true,
        'serverSide': true,
        'stateSave': true,
        'thousands': '.'
    };
    selector.on('init.dt', function() {
        selector.removeClass('table-loader').show();
    });
    return selector.DataTable(options);
}
function emptyDatatables(param, start_date, end_date) {
    var selector = $('#'+param['selector']);
    options = {
        'buttons': [
            {
                extend: 'excelHtml5',
                text: 'Ekspor Excel &raquo;',
                action: newExportAction
            },
            {
                extend: 'csvHtml5',
                text: 'Ekspor CSV &raquo;',
                action: newExportAction
            }
        ],
        // 'columns': param['columns'],
        'columnDefs': param['columnDefs'],
        'decimal': ',',
        // 'deferRender': true,
        'dom': 'Blfrtip',
        // 'drawCallback': param['drawCallback'],
        // 'initComplete': param['initComplete'],
        'language': {
            'processing': '<span style="width:100%;height:100%;"><img src="'+BASE_URI+'assets/img/preloader.gif"></span>',
            'sLengthMenu': '_MENU_',
            'searchPlaceholder': 'Pencarian...',
            'sZeroRecords': 'Tidak ditemukan data yang sesuai',
            'sInfo': 'Data _START_ - _END_ dari total _TOTAL_',
            'sInfoEmpty': 'Data 0 - 0 dari 0 entri',
            'sInfoFiltered': '(disaring dari _MAX_ data)',
            'sInfoPostFix': '',
            'sSearch': '',
            'sUrl': '',
            'oPaginate': {
                'sFirst': '&laquo;',
                'sPrevious': '&lsaquo;',
                'sNext': '&rsaquo;',
                'sLast': '&raquo;'
            }
        },
        'lengthMenu': [[25, 100, 500], [25, 100, 500]],
        // 'order': param['sort'],
        'pagingType': 'full',
        'processing': true,
        'responsive': true,
        // 'serverSide': true,
        'stateSave': true,
        'thousands': '.'
    };
    selector.on('init.dt', function() {
        selector.removeClass('table-loader').show();
    });
    return selector.DataTable(options);
}
