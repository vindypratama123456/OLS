<div class="container-fluid">

    <!-- Page Heading -->
    <div class="row">
        <div class="col-lg-12">
            <h1 class="page-header">
                Import Data Pesanan dari Siplah
            </h1>
            <ol class="breadcrumb">
                <li>
                    <a href="<?php echo base_url() . ADMIN_PATH; ?>">Beranda</a>
                </li>
                <li class="active">
                    Import Data Pesanan  dari Siplah
                </li>
            </ol>
        </div>
    </div>
    <!-- /.row -->

    <div class="row">
        <div class="col-lg-12">
            <div class="row">
                <!-- <form data-action="https://siplah.id/API/V2/getListOrder.php" id="form-get-data-siplah" method="POST" accept-charset="utf-8"> -->
                <!-- <form data-action="<?php echo base_url(); ?>pesananblanja/testing2" id="form-get-data-siplah" method="POST" accept-charset="utf-8"> -->
                <form data-action="<?php echo base_url(); ?>backoffice/pesananblanja/prosespesanansiplah" id="form-get-data-siplah" method="POST" accept-charset="utf-8">
                   
                    <!-- <input type="hidden" name="seller_id" value="89">
                    <input type="hidden" name="api_key" value="f21a297cadf045d8a36e950ac7585e81"> -->
                    <div class="col-md-3">
                        <p>Silahkan pilih tanggal awal</p>
                        <div class="form-group">
                            <div class="input-group date" id="datetimepicker6">
                                <input type="text" class="form-control" name="start_date" />
                                <span class="input-group-addon">
                                    <span class="glyphicon glyphicon-calendar"></span>
                                </span>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <p>Silahkan pilih tanggal akhir</p>
                        <div class="form-group">
                            <div class="input-group date" id="datetimepicker7">
                                <input type="text" class="form-control" name="end_date" />
                                <span class="input-group-addon">
                                    <span class="glyphicon glyphicon-calendar"></span>
                                </span>
                            </div>
                        </div>
                    </div>
                    <!-- <div class="col-md-4">
                        <p>Pilih Wilayah</p>
                        <div class="form-group">
                            <select name="kabupaten" class="form-control" id="kabupaten">
                                <?php 
                                if(in_array($this->adm_level, $this->backoffice_admin_area)) {
                                    $selected = !$this->session->userdata('sess_wil') ? ' selected' : '';
                                    echo '<option value="all"'.$selected.'>Semua Wilayah</option>';
                                }
                                
                                foreach($listwilayah as $row){
                                    $selected = ($this->session->userdata('sess_wil') && $this->session->userdata('sess_wil')==$row->kabupaten) ? ' selected' : '';
                                    echo '<option value="'.$row->kabupaten.'"'. $selected.'>'.$row->kabupaten.'</option>';
                                }
                                ?>
                            </select>
                        </div>
                    </div> -->
                    <div class="col-md-2">
                        <p><br /></p>
                        <input type="submit" class="btn btn-primary" id="cari-report" value="Cari" />
                    </div>
                </form>
            </div>
            <br />
            <!-- <div class="table-responsive">
                <table class="table table-striped dt-responsive nowrap" id="datatableSiplah">
                    <thead>
                        <tr>
                            <th class="text-center">Nomor PO</th>
                            <th class="text-center">TANGGAL DIBUAT</th>
                            <th class="text-center">STATUS</th>
                            <th class="text-center">SKU</th>
                            <th class="text-center">NAMA BUKU</th>
                            <th class="text-center">QTY</th>
                            <th class="text-center">HARGA</th>
                            <th class="text-center">NAMA SEKOLAH</th>
                            <th class="text-center">KABUPATEN</th>
                        </tr>
                    </thead>
                </table>
            </div> -->
        </div>
    </div>
    <!-- /.row -->

</div>
<!-- /.container-fluid -->
<script type="text/javascript">
    $(document).ready(function(){

        var datas=[];
        if($('#datatableBank').length>0){
            datas['selector'] = 'datatableBank';
            datas['url'] = BASE_URL+'bank/list_bank';
            datas['columns'] = [
                { 'data': 'bank_code' },
                { 'data': 'bank_name' },
                { 'data': 'bank_alias' },
                { 'data': 'status' },
                { 'data': 'aksi' }
            ];
            datas['columnDefs'] = [
                { className: 'text-center', targets: [0,4] }
            ];
            datas['sort'] = [1,'asc'];
            tableBank = myDatatables(datas);
            commonTools(datas['selector'], tableBank);
        }

        var tombol=$('#cari-report'), frmSiplah=$('#form-get-data-siplah'), elDt1=$('#datetimepicker6'), elDt2=$('#datetimepicker7');
        $(elDt1).datetimepicker({
            format: 'YYYY-MM-DD'
        }).on('dp.change', function (e) {
            $(elDt2).data('DateTimePicker').minDate(e.date);
        });
        $(elDt2).datetimepicker({
            useCurrent: false, //Important! See issue #1075
            format: 'YYYY-MM-DD'
        }).on('dp.change', function (e) {
            $(elDt1).data('DateTimePicker').maxDate(e.date);
        });
        $('#kabupaten').select2();


        frmSiplah.validate({
            errorClass: 'has-error',
            errorElement: 'span',
            rules: {
                tgl_mulai:{
                    required: true
                },
                tgl_akhir: {
                    required: true
                }
            },
            highlight: function (element, errorClass) {
                var elem = $(element);
                elem.parents('.form-group').addClass(errorClass);
                elem.addClass(errorClass);
            }, 
            unhighlight: function (element, errorClass) {
                var elem = $(element);
                elem.parents('.has-error').removeClass(errorClass); 
                elem.removeClass(errorClass);
            },
            submitHandler: function() {
                $.ajax({
                    type: 'POST',
                    data: frmSiplah.serialize(),
                    dataType: 'json',
                    url: frmSiplah.data('action'),
                    async: true,
                    timeout: 180000,
                    beforeSend: function() {
                        tombol.attr('disabled', true);
                        tombol.val('Memproses...', true);
                        $('#result-area').html('');
                    },
                    success:function(datas){
                        console.log(datas);
                        var linkRedirectTrue = "orders";
                        var linkRedirectFalse = "pesananblanja/getdatasiplah";
                        var linkRedirectError = "pesananblanja/viewerror";
                        tombol.attr('disabled', false);
                        tombol.val('Cari');
                        if(datas.success == true) {
                            if(datas.error == true)
                            {
                                bootAlertRedirect(datas.message, linkRedirectError);
                            }
                            else
                            {
                                bootAlertRedirect("Berhasil mengimport data dari Siplah ke OLS Buku Sekolah.", linkRedirectTrue);
                            }

                        }
                        else {
                            bootAlertRedirect(datas.message, linkRedirectFalse);
                        }
                        $('input[name=csrftokenbs]').val(datas.csrf_token);
                    
                        //Load  datatable

                        // var oTblReport = $("#datatableSiplah");
                        // oTblReport.DataTable ({
                        //     retrieve: true,
                        //     reload: true,
                        //     "data" : datas.data,
                        //     "columns" : [
                        //         { "data" : "po_number" },
                        //         { "data" : "created_at" },
                        //         { "data" : "order_status" },
                        //         { "data" : "sku" },
                        //         { "data" : "nama_produk" },
                        //         { "data" : "qty" },
                        //         { "data" : "harga_katalog" },
                        //         { "data" : "nama_sekolah" },
                        //         { "data" : "kab" },
                        //     ]
                        // });                       
                    },
                    error: function(jX, err, errT) {
                        bootAlert(jX.status + '\n' + err + '\n' + errT);
                        // bootAlert("Terjadi kesalahan ketika meng-import data dari Siplah ke OLS. Silahkan coba beberapa saat lagi.");
                        // $('input[name=csrftokenbs]').val($.parseJSON(jqXHR.responseText).csrf_token);
                    }
                });
                return false;
            }
        });
    });
</script>