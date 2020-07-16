$(document).ready(function () {
    let datas = [];
    let elFilterData = $('#reset-filter-date');
    let elStartData = $('#start_date');
    let datatableNew;
    if ($('#datatableNew').length > 0) {
        datas['selector'] = 'datatableNew';
        datas['url'] = BASE_URL + 'comission/listOrderNew';
        datas['columns'] = [
            {'data': 'reference'},
            {'data': 'school_name'},
            {'data': 'class_name'},
            {'data': 'provinsi'},
            {'data': 'kabupaten'},
            {'data': 'date_add'},
            {'data': 'total_paid', render: $.fn.dataTable.render.number(',', '.', 0)},
            {'data': 'sales_person'},
            {'data': 'percent_comission'},
            {'data': 'percent_tax'},
            {'data': 'amount_comission', render: $.fn.dataTable.render.number(',', '.', 0)}
        ];
        datas['columnDefs'] = [
            {className: 'text-center', targets: [0, 2, 5, 8, 9]},
            {className: 'text-right', targets: [6, 10]}
        ];
        datas['sort'] = [5, 'asc'];
        datatableNew = myDatatables(datas);
        commonTools(datas['selector'], datatableNew);
    }
    let datatableProposed;
    if ($('#datatableProposed').length > 0) {
        datas['selector'] = 'datatableProposed';
        datas['url'] = BASE_URL + 'comission/listProposed';
        datas['columns'] = [
            {'data': 'reference'},
            {'data': 'school_name'},
            {'data': 'class_name'},
            {'data': 'provinsi'},
            {'data': 'kabupaten'},
            {'data': 'date_add'},
            {'data': 'total_paid', render: $.fn.dataTable.render.number(',', '.', 0)},
            {'data': 'sales_person'},
            {'data': 'percent_comission'},
            {'data': 'percent_tax'},
            {'data': 'amount_comission', render: $.fn.dataTable.render.number(',', '.', 0)},
            {'data': 'date_proposed'}
        ];
        datas['columnDefs'] = [
            {className: 'text-center', targets: [0, 2, 5, 8, 9, 11]},
            {className: 'text-right', targets: [6, 10]}
        ];
        datas['sort'] = [11, 'asc'];
        datatableProposed = myDatatables(datas);
        commonTools(datas['selector'], datatableProposed);
    }
    let datatableApproved;
    if ($('#datatableApproved').length > 0) {
        datas['selector'] = 'datatableApproved';
        datas['url'] = BASE_URL + 'comission/listApproved';
        datas['columns'] = [
            {'data': 'reference'},
            {'data': 'school_name'},
            {'data': 'class_name'},
            {'data': 'provinsi'},
            {'data': 'kabupaten'},
            {'data': 'date_add'},
            {'data': 'total_paid', render: $.fn.dataTable.render.number(',', '.', 0)},
            {'data': 'sales_person'},
            {'data': 'percent_comission'},
            {'data': 'percent_tax'},
            {'data': 'amount_comission', render: $.fn.dataTable.render.number(',', '.', 0)},
            {'data': 'date_proposed'}
        ];
        datas['columnDefs'] = [
            {className: 'text-center', targets: [0, 2, 5, 8, 9, 11]},
            {className: 'text-right', targets: [6, 10]}
        ];
        datas['sort'] = [11, 'asc'];
        datatableApproved = myDatatables(datas);
        commonTools(datas['selector'], datatableApproved);
    }
    let datatableToProcessed;
    if ($('#datatableToProcessed').length > 0) {
        datas['selector'] = 'datatableToProcessed';
        datas['url'] = BASE_URL + 'comission/listToProcessed';
        datas['columns'] = [
            {'data': 'id'},
            {'data': 'reference'},
            {'data': 'school_name'},
            {'data': 'class_name'},
            {'data': 'provinsi'},
            {'data': 'kabupaten'},
            {'data': 'date_add'},
            {'data': 'total_paid', render: $.fn.dataTable.render.number(',', '.', 0)},
            {'data': 'sales_person'},
            {'data': 'percent_comission'},
            {'data': 'percent_tax'},
            {'data': 'amount_comission', render: $.fn.dataTable.render.number(',', '.', 0)},
            {'data': 'date_proposed'}
        ];
        datas['columnDefs'] = [
            {orderable: false, targets: [0]},
            {className: 'text-center', targets: [0, 1, 3, 6, 9, 10, 12]},
            {className: 'text-right', targets: [7, 11]}
        ];
        datas['sort'] = [12, 'asc'];
        datatableToProcessed = myDatatables(datas);
        commonTools(datas['selector'], datatableToProcessed);
    }
    let datatableProcessed;
    if ($('#datatableProcessed').length > 0) {
        datas['selector'] = 'datatableProcessed';
        datas['url'] = BASE_URL + 'comission/listProcessed';
        datas['columns'] = [
            {'name': 'reference', 'data': 'reference'},
            {'name': 'no_pd', 'data': 'no_pd'},
            {'name': 'school_name', 'data': 'school_name'},
            {'name': 'class_name', 'data': 'class_name'},
            {'name': 'provinsi', 'data': 'provinsi'},
            {'name': 'kabupaten', 'data': 'kabupaten'},
            {'name': 'date_add', 'data': 'date_add'},
            {'name': 'total_paid', 'data': 'total_paid', render: $.fn.dataTable.render.number(',', '.', 0)},
            {'name': 'sales_person', 'data': 'sales_person'},
            {'name': 'percent_comission', 'data': 'percent_comission'},
            {'name': 'percent_tax', 'data': 'percent_tax'},
            {'name': 'amount_comission', 'data': 'amount_comission', render: $.fn.dataTable.render.number(',', '.', 0)},
            {'name': 'date_proposed', 'data': 'date_proposed'}
        ];
        datas['columnDefs'] = [
            {className: 'text-center', targets: [0, 1, 3, 6, 9, 10, 12]},
            {className: 'text-right', targets: [7, 11]}
        ];
        datas['sort'] = [12, 'asc'];
        datatableProcessed = myDatatables(datas);
        commonTools(datas['selector'], datatableProcessed);
    }
    let datatablePaidOff;
    if ($('#datatablePaidOff').length > 0) {
        datas['selector'] = 'datatablePaidOff';
        datas['url'] = BASE_URL + 'comission/listPaidoff';
        datas['columns'] = [
            {'data': 'reference'},
            {'data': 'no_pd'},
            {'data': 'school_name'},
            {'data': 'class_name'},
            {'data': 'provinsi'},
            {'data': 'kabupaten'},
            {'data': 'date_add'},
            {'data': 'total_paid', render: $.fn.dataTable.render.number(',', '.', 0)},
            {'data': 'sales_person'},
            {'data': 'percent_comission'},
            {'data': 'percent_tax'},
            {'data': 'amount_comission', render: $.fn.dataTable.render.number(',', '.', 0)},
            {'data': 'date_transfered'}
        ];
        datas['columnDefs'] = [
            {className: 'text-center', targets: [0, 1, 3, 6, 9, 10, 12]},
            {className: 'text-right', targets: [7, 11]}
        ];
        datas['sort'] = [12, 'asc'];
        datatablePaidOff = myDatatables(datas);
        commonTools(datas['selector'], datatablePaidOff);
    }
    let datatableProcessedBatch;
    if ($('#datatableProcessedBatch').length > 0) {
        datas['selector'] = 'datatableProcessedBatch';
        datas['url'] = BASE_URL + 'comission/listProcessedBatch';
        datas['columns'] = [
            {'data': 'no_pd'},
            {'data': 'total_mitra', searchable: false},
            {'data': 'tipe'},
            {'data': 'company'},
            {'data': 'total_amount', searchable: false, render: $.fn.dataTable.render.number(',', '.', 0)},
            {'data': 'tgl_diproses'},
            {'data': 'date_transfered'}
        ];
        datas['columnDefs'] = [
            {className: 'text-center', targets: [0, 1, 2, 3, 5, 6]},
            {className: 'text-right', targets: [4]}
        ];
        datas['drawCallback'] = function () {
            let api = this.api();
            api.columns('.sum', {
                page: 'current'
            }).every(function () {
                let sum = this
                    .data()
                    .reduce(function (a, b) {
                        let x = parseFloat(a) || 0;
                        let y = parseFloat(b) || 0;
                        return x + y;
                    }, 0);
                $(this.footer()).html(numFormat(sum));
            });
        };
        datas['sort'] = [0, 'desc'];
        datatableProcessedBatch = myDatatables(datas);
        commonTools(datas['selector'], datatableProcessedBatch);
    }
    let datatableProcessedBatchFailed;
    if ($('#datatableProcessedBatchFailed').length > 0) {
        datas['selector'] = 'datatableProcessedBatchFailed';
        datas['url'] = BASE_URL + 'comission/listProcessedBatchFailed';
        datas['columns'] = [
            {'data': 'no_pd'},
            {'data': 'total_mitra', searchable: false},
            {'data': 'tipe'},
            {'data': 'company'},
            {'data': 'total_amount', searchable: false, render: $.fn.dataTable.render.number(',', '.', 0)},
            {'data': 'tgl_diproses'},
            {'data': 'date_transfered'}
        ];
        datas['columnDefs'] = [
            {className: 'text-center', targets: [0, 1, 2, 3, 5, 6]},
            {className: 'text-right', targets: [4]}
        ];
        datas['drawCallback'] = function () {
            let api = this.api();
            api.columns('.sum', {
                page: 'current'
            }).every(function () {
                let sum = this
                    .data()
                    .reduce(function (a, b) {
                        let x = parseFloat(a) || 0;
                        let y = parseFloat(b) || 0;
                        return x + y;
                    }, 0);
                $(this.footer()).html(numFormat(sum));
            });
        };
        datas['sort'] = [0, 'desc'];
        datatableProcessedBatchFailed = myDatatables(datas);
        commonTools(datas['selector'], datatableProcessedBatchFailed);
    }
    let datatableIsPosting;
    if ($('#datatableIsPosting').length > 0) {
        datas['selector'] = 'datatableIsPosting';
        datas['url'] = BASE_URL + 'comission/listIsPosting';
        datas['columns'] = [
            {'data': 'no_pd'},
            {'data': 'total_mitra', searchable: false},
            {'data': 'tipe'},
            {'data': 'company'},
            {'data': 'total_amount', searchable: false, render: $.fn.dataTable.render.number(',', '.', 0)},
            {'data': 'date_transfered'}
        ];
        datas['columnDefs'] = [
            {className: 'text-center', targets: [0, 1, 2, 3, 5]},
            {className: 'text-right', targets: [4]}
        ];
        datas['sort'] = [0, 'desc'];
        datatableIsPosting = myDatatables(datas);
        commonTools(datas['selector'], datatableIsPosting);
    }
    let datatablePaidBatch;
    if ($('#datatablePaidBatch').length > 0) {
        datas['selector'] = 'datatablePaidBatch';
        datas['url'] = BASE_URL + 'comission/listPaidBatch';
        datas['columns'] = [
            {'data': 'no_pd'},
            {'data': 'total_mitra', searchable: false},
            {'data': 'tipe'},
            {'data': 'company'},
            {'data': 'total_amount', searchable: false, render: $.fn.dataTable.render.number(',', '.', 0)},
            {'data': 'tgl_diproses'},
            {'data': 'date_transfered'}
        ];
        datas['columnDefs'] = [
            {className: 'text-center', targets: [0, 1, 2, 3, 5, 6]},
            {className: 'text-right', targets: [4]}
        ];
        datas['drawCallback'] = function () {
            let api = this.api();
            api.columns('.sum', {
                page: 'current'
            }).every(function () {
                let sum = this
                    .data()
                    .reduce(function (a, b) {
                        let x = parseFloat(a) || 0;
                        let y = parseFloat(b) || 0;
                        return x + y;
                    }, 0);
                $(this.footer()).html(numFormat(sum));
            });
        };
        datas['sort'] = [4, 'asc'];
        setTimeout(function () {
            datatablePaidBatch = myDatatables(datas);
            commonTools(datas['selector'], datatablePaidBatch);
        }, 10);
    }
    let datatableAmountPPh;
    if ($('#datatableAmountPPh').length > 0) {
        datas['selector'] = 'datatableAmountPPh';
        datas['url'] = BASE_URL + 'comission/listPPhAmount';
        datas['columns'] = [
            {'data': 'no_pd'},
            {'data': 'masa_pajak'},
            {'data': 'periode'},
            {'data': 'pembetulan'},
            {'data': 'no_bukti_potong'},
            {'data': 'periode'},
            {'data': 'no_npwp'},
            {'data': 'ktp'},
            {'data': 'nama'},
            {'data': 'alamat'},
            {'data': 'wp_luar_negeri'},
            {'data': 'kode_negara'},
            {'data': 'kode_pajak'},
            {'data': 'nilai_komisi', render: $.fn.dataTable.render.number(',', '.', 0), searchable: false},
            {'data': 'jumlah_dpp', searchable: false},
            {'data': 'tarif'},
            {'data': 'nilai_pph', render: $.fn.dataTable.render.number(',', '.', 0), searchable: false},
            {'data': 'npwp_pemotong'},
            {'data': 'nama_pemotong'},
            {'data': 'tgl_transfer'}
        ];
        datas['columnDefs'] = [
            {className: 'text-center', targets: [0, 4, 6]},
            {className: 'text-right', targets: [3, 5]}
        ];
        datas['sort'] = [0, 'asc'];

        function fetchDatatableAmountPPh(a, b) {
            setTimeout(function () {
                datatableAmountPPh = myDatatables(datas, a, b);
                commonTools(datas['selector'], datatableAmountPPh);
            }, 10);
        }

        function resetDatatableAmountPPh() {
            $('input[type="text"], .dataTables_filter input[type="search"]').val('');
            datatableAmountPPh.state.clear();
            datatableAmountPPh.clear().destroy();
            fetchDatatableAmountPPh();
        }

        fetchDatatableAmountPPh();
        $('#search').click(function () {
            let start_date = elStartData.val();
            let end_date = $('#end_date').val();
            if (start_date !== '' && end_date !== '') {
                datatableAmountPPh.state.clear();
                datatableAmountPPh.clear().destroy();
                fetchDatatableAmountPPh(start_date, end_date);
            } else {
                elStartData.focus();
            }
        });
        elFilterData.on('click', function () {
            resetDatatableAmountPPh();
        });
    }
    let numFormat = $.fn.dataTable.render.number(',', '.', 0).display;
    $('#btn-posting').on('click', function (e) {
        e.preventDefault();
        let noPD = $(this).data('no-pd');
        bootbox.confirm({
            title: 'Konfirmasi',
            message: 'Yakin ingin mem-POSTING Pesanan Dana ini?',
            callback: function (result) {
                if (result) {
                    $.ajax({
                        type: 'POST',
                        data: 'no_pd=' + noPD + '&csrftokenbs=' + CSRF_HASH,
                        url: BASE_URL + 'comission/sendPesananDana',
                        beforeSend: function () {
                            disableBtn('btn-posting');
                            $('.bootbox').modal('hide').data('bs.modal', null);
                            $('#myloader').show();
                        },
                        success: function (datas) {
                            bootAlertRedirect(datas.message, datas.redirect);
                            $('#myloader').hide();
                        },
                        error: function (jqXHR, exception) {
                            let msg = 'Error';
                            let respond;
                            if (jqXHR.status === 0) {
                                msg = 'Not connect.\n Verify Network.';
                            } else if (jqXHR.status === 404) {
                                msg = 'Requested page not found. [404]';
                            } else if (jqXHR.status === 400) {
                                respond = $.parseJSON(jqXHR.responseText);
                                msg = respond.message;
                            } else if (jqXHR.status === 500) {
                                msg = 'Internal Server Error [500].';
                            } else if (exception === 'parsererror') {
                                msg = 'Requested JSON parse failed.';
                            } else if (exception === 'timeout') {
                                msg = 'Time out error.';
                            } else if (exception === 'abort') {
                                msg = 'Ajax request aborted.';
                            } else {
                                msg = 'Uncaught Error: ' + jqXHR.responseText;
                            }
                            bootAlertRedirect(msg);
                            $('#myloader').hide();
                            enableBtn('btn-posting');
                        }
                    });
                    return false;
                }
            }
        });
    });
    $('#btn-bayar').on('click', function (e) {
        e.preventDefault();
        let noPD = $(this).data('no-pd');
        bootbox.confirm({
            title: 'Konfirmasi',
            message: 'Yakin Pesanan Dana ini sudah <b>DIBAYAR</b>?',
            callback: function (result) {
                if (result) {
                    $.ajax({
                        type: 'POST',
                        data: 'no_pd=' + noPD + '&csrftokenbs=' + CSRF_HASH,
                        url: BASE_URL + 'comission/setKomisiDibayar',
                        beforeSend: function () {
                            disableBtn('btn-bayar');
                            $('.bootbox').modal('hide').data('bs.modal', null);
                            $('#myloader').show();
                        },
                        success: function (datas) {
                            bootAlertRedirect(datas.message, datas.redirect);
                            $('#myloader').hide();
                        },
                        error: function (jqXHR, exception) {
                            let msg = 'Error';
                            let respond;
                            if (jqXHR.status === 0) {
                                msg = 'Not connect.\n Verify Network.';
                            } else if (jqXHR.status === 404) {
                                msg = 'Requested page not found. [404]';
                            } else if (jqXHR.status === 400) {
                                respond = $.parseJSON(jqXHR.responseText);
                                msg = respond.message;
                            } else if (jqXHR.status === 500) {
                                msg = 'Internal Server Error [500].';
                            } else if (exception === 'parsererror') {
                                msg = 'Requested JSON parse failed.';
                            } else if (exception === 'timeout') {
                                msg = 'Time out error.';
                            } else if (exception === 'abort') {
                                msg = 'Ajax request aborted.';
                            } else {
                                msg = 'Uncaught Error: ' + jqXHR.responseText;
                            }
                            bootAlertRedirect(msg);
                            $('#myloader').hide();
                            enableBtn('btn-bayar');
                        }
                    });
                    return false;
                }
            }
        });
    });
    $('#btn-bayar-pdfailed').on('click', function (e) {
        e.preventDefault();
        let noPD = $(this).data('no-pd');
        bootbox.confirm({
            title: 'Konfirmasi',
            message: 'Yakin Pesanan Dana ini sudah <b>DIBAYAR</b>?',
            callback: function (result) {
                if (result) {
                    $.ajax({
                        type: 'POST',
                        data: 'no_pd=' + noPD + '&csrftokenbs=' + CSRF_HASH,
                        url: BASE_URL + 'comission/setKomisiDibayarPdFailed',
                        beforeSend: function () {
                            disableBtn('btn-bayar-pdfailed');
                            $('.bootbox').modal('hide').data('bs.modal', null);
                            $('#myloader').show();
                        },
                        success: function (datas) {
                            bootAlertRedirect(datas.message, datas.redirect);
                            $('#myloader').hide();
                        },
                        error: function (jqXHR, exception) {
                            let msg = 'Error';
                            let respond;
                            if (jqXHR.status === 0) {
                                msg = 'Not connect.\n Verify Network.';
                            } else if (jqXHR.status === 404) {
                                msg = 'Requested page not found. [404]';
                            } else if (jqXHR.status === 400) {
                                respond = $.parseJSON(jqXHR.responseText);
                                msg = respond.message;
                            } else if (jqXHR.status === 500) {
                                msg = 'Internal Server Error [500].';
                            } else if (exception === 'parsererror') {
                                msg = 'Requested JSON parse failed.';
                            } else if (exception === 'timeout') {
                                msg = 'Time out error.';
                            } else if (exception === 'abort') {
                                msg = 'Ajax request aborted.';
                            } else {
                                msg = 'Uncaught Error: ' + jqXHR.responseText;
                            }
                            bootAlertRedirect(msg);
                            $('#myloader').hide();
                            enableBtn('btn-bayar-pdfailed');
                        }
                    });
                    // return false;
                }
            }
        });
    });
    $('#payout_check').on('click', function () {
        if ($(this).prop('checked') === true) {
            checkAll();
        } else {
            uncheckAll();
        }
    });
    myAjax('frm-proposed', 'comission/proposedPost', 'Yakin ingin mengajukan komisi untuk pesanan ini?', 'comission');
    myAjax('frm-approve', 'comission/approvePost', 'Yakin ingin menyetujui komisi untuk pesanan ini?', 'comission/proposed');
    myAjax('frm-processed', 'comission/processedPost', 'Yakin ingin memproses pengajuan komisi ini?', 'comission/proposed');

    $('#btn-ajukan').on('click', function(){
        // alert('test');
        // return false;
    });
});

let elCheckbox = 'input[class="checkc_batch"]';

function processBatch() {
    let id = [];
    $(elCheckbox + ':checked').each(function () {
        id.push($(this).val());
    });
    id = id.join();
    if (id !== "") {
        bootbox.confirm({
            title: 'Konfirmasi',
            message: 'Anda yakin dengan kode pesanan yang dipilih?',
            callback: function (result) {
                if (result) {
                    $('button').attr('disabled', true);
                    $.ajax({
                        type: 'POST',
                        data: 'payout_id=' + id + '&csrftokenbs=' + CSRF_HASH,
                        dataType: 'json',
                        url: BASE_URL + 'comission/processBatch',
                        async: true,
                        beforeSend: function () {
                            $('#myloader').show();
                            $('.bootbox').modal('hide').data('bs.modal', null);
                            $('button').attr('disabled', true);
                        },
                        success: function (datas) {
                            if (datas.success === 'true') {
                                $('#myloader').hide();
                                window.location = BASE_URL + 'comission/processed';
                            } else {
                                bootAlert(datas.message);
                                $('button').attr('disabled', false);
                                $('#myloader').hide();
                            }
                        }
                    });
                    return false;
                }
            }
        });
    } else {
        bootAlert('Silahkan pilih (centang) pesanan terlebih dahulu!');
    }
}

function checkAll() {
    $(elCheckbox).prop('checked', true);
    $('#processBatch').show();
    updateCheckbox();
}

function uncheckAll() {
    $(elCheckbox).prop('checked', false);
    $('#processBatch').hide();
    $('#processBatch, #total-sum').hide();
    let totalPrice = 0;
}

function updateCheckbox() {
    let totRows = $(elCheckbox).length;
    let totChecked = $(elCheckbox + ':checked').length;
    let payoutChecked = $('#payout_check');
    if (totChecked > 0) {
        payoutChecked.prop('indeterminate', true);
        $('#processBatch, #total-sum').show();
        getTotal();
    } else {
        $('#processBatch, #total-sum').hide();
        $('#total-sum').html('');
    }
    if (totChecked === totRows) {
        payoutChecked.prop('checked', true);
        payoutChecked.prop('indeterminate', false);
        getTotal();
    } else {
        payoutChecked.prop('checked', false);
    }
}

function getTotal() {
    let totalPrice = 0;
    $(elCheckbox + ':checked').closest('tr').find('td:eq(11)').each(
        function () {
            let rowVal = $(this).text().replace(/\,/g, '');
            totalPrice += parseFloat(rowVal);
            let displayedValue = totalPrice.toLocaleString(
                'de-DE',
                {minimumFractionDigits: 0}
            );
            $('#total-sum').html('Total Komisi dipilih: <strong>Rp. ' + displayedValue + '</strong>');
        }
    );
}

$(document).on('click', elCheckbox, function () {
    updateCheckbox();
});
$(document).on('click', '.paginate_button', function () {
    $('#payout_check').prop('checked', false);
    $('#processBatch, #total-sum').hide();
    let totalPrice = 0;
});
