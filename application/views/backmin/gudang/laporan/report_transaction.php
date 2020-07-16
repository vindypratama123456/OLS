<div class="container-fluid container-body">

    <div class="row">
        <div class="col-lg-12">
            <h1 class="page-header">
                <?php echo $page_title; ?>
            </h1>
            <ol class="breadcrumb">
                <li>
                    <a href="<?php echo base_url() . ADMIN_PATH; ?>">Beranda</a>
                </li>
                <li class="active">
                    <?php echo $page_title; ?>
                </li>
            </ol>
        </div>
    </div>
    
    <div class="row">
        <div class="col-md-12">
            
            <?php echo form_open('', 'id="formReportReceiving" class="form-horizontal" data-uri="' . base_url(BACKMIN_PATH . '/scmlaporan/report_transaction') . '" role="form" autocomplete="off"'); ?>
                <div class="form-group">
                    <label class="control-label col-md-2">Pilih tanggal</label>
                    <div class="col-md-4">
                        <input type="text" id="datefilter" name="datefilter" class="form-control datefilter input-sm">
                    </div>
                </div>
                <!-- <div class="form-group">
                    <label class="control-label col-md-2">Pilih Gudang</label>
                    <div class="col-md-4">
                        <select id="id_gudang" name="id_gudang" class="form-control id_gudang input-sm">
                            <option value="">-- Semua Gudang --</option>
                            <?php foreach($listgudang as $rows => $value) { ?>
                            <option value="<?php echo $value->id_gudang ?>"><?php echo $value->nama_gudang ?></option>
                            <?php } ?>
                        </select>
                    </div>
                </div> -->
                <div class="form-group">
                    <label class="control-label col-md-2"></label>
                    <div class="col-md-4">
                        <button type="submit" class="btn btn-primary" id="submitReport" disabled="">
                            <strong>Submit</strong>
                        </button>
                    </div>
                </div>
                <div class="form-group display-none" id="message-daterange">
                    <label class="control-label col-md-2"></label>
                    <div class="col-md-4">
                        <p style="color: red;">Export Excel hanya bisa dilakukan maksimal 3 bulan.</p>
                    </div>
                </div>
            <?php echo form_close(); ?>

        </div>
    </div>

    <div class="row">
        <hr>
    </div>

    <div class="row export">
        <div class="col-md-12">
            <input type="hidden" id="slug" class="slug">
            <!-- <button type="button" id="print_pdf" class="btn btn-success print_pdf display_none" onclick="printReceiving()"><i class="fa fa-print"></i> &nbsp;Export PDF</button> -->
            <button type="button" id="print_excel" class="btn btn-danger print_excel display_none" onclick="printReceivingExcel()"><i class="fa fa-file-text"></i> &nbsp;Export Excel</button>
        </div>
    </div>

    <div class="row">
        <div class="col-md-12">
            <!-- <table class="table table-bordered table-stripped" id="table_transaction">
                <thead>
                    <tr style="font-size: 11pt;">
                        <th class="text-center">Kode Transaksi</th>
                        <th class="text-center">Tanggal</th>
                        <th class="text-center">Status</th>
                        <th class="text-center">Keterangan</th>
                        <th class="text-center">Asal</th>
                        <th class="text-center">Tujuan</th>
                        <th class="text-center">Kode Buku</th>
                        <th class="text-center">QTY</th>
                    </tr>
                </thead>
                <tbody id="dataTable">
                    <tr>
                        <td colspan="8"><center>Data tidak tersedia</center></td>
                    </tr>
                </tbody>
            </table> -->
            <table class="table table-bordered table-stripped" id="table_transaction">
                <thead>
                    <tr>
                        <th class="text-center">Kode Transaksi</th>
                        <th class="text-center">Tanggal</th>
                        <th class="text-center">Status</th>
                        <th class="text-center">Keterangan</th>
                        <th class="text-center">Asal</th>
                        <th class="text-center">Tujuan</th>
                        <th class="text-center">Kode Buku</th>
                        <th class="text-center">QTY</th>
                    </tr>
                </thead>
                <tbody id="dataTable">
                </tbody>
            </table>
            <!-- <div class="btn-group dropup">
                <span id="pagination"></span>
            </div> -->
        </div>
    </div>

    <div class="footer">
        <input type="hidden" id="slug" class="slug">
        <!-- <button type="button" id="print_pdf" class="btn btn-success print_pdf display_none" onclick="printReceiving()"><i class="fa fa-print"></i> &nbsp;Export PDF</button> -->
        <button type="button" id="print_excel" class="btn btn-danger print_excel display_none" onclick="printReceivingExcel()"><i class="fa fa-file-text"></i> &nbsp;Export Excel</button>
    </div>

</div>
