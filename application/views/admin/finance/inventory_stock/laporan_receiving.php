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
            
            <?php echo form_open('', 'id="formReportReceiving" class="form-horizontal" data-uri="' . base_url(ADMIN_PATH . '/finance/reportReceiving') . '" role="form" autocomplete="off"'); ?>
                <div class="form-group">
                    <label class="control-label col-md-2">Pilih tanggal</label>
                    <div class="col-md-4">
                        <input type="text" id="datefilter" name="datefilter" class="form-control datefilter input-sm">
                    </div>
                </div>
                <div class="form-group">
                    <label class="control-label col-md-2">Pilih Gudang</label>
                    <div class="col-md-4">
                        <select id="id_gudang" name="id_gudang" class="form-control id_gudang input-sm">
                            <option value="">-- Semua Gudang --</option>
                            <?php foreach($listgudang as $rows => $value) { ?>
                            <option value="<?php echo $value->id_gudang ?>"><?php echo $value->nama_gudang ?></option>
                            <?php } ?>
                        </select>
                    </div>
                </div>
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
            <button type="button" id="print_pdf" class="btn btn-success print_pdf display_none" onclick="printReceiving()"><i class="fa fa-print"></i> &nbsp;Export PDF</button>
            <button type="button" id="print_excel" class="btn btn-danger print_excel display_none" onclick="printReceivingExcel()"><i class="fa fa-file-text"></i> &nbsp;Export Excel</button>
        </div>
    </div>

    <div class="row">
        <div class="col-md-12">
            <table class="table table-bordered table-stripped">
                <thead>
                    <tr style="font-size: 11pt;">
                        <th class="text-center">Bulan</th>
                        <th class="text-center">Item No</th>
                        <th class="text-center">Description</th>
                        <th class="text-center">Quantity</th>
                        <th class="text-center">Unit Cost</th>
                        <th class="text-center">By Material</th>
                        <th class="text-center">By Jasa</th>
                        <th class="text-center">Tax</th>
                        <th class="text-center">Total</th>
                    </tr>
                </thead>
                <tbody id="dataTable">
                    <tr>
                        <td colspan="8"><center>Data tidak tersedia</center></td>
                    </tr>
                </tbody>
            </table>
            <div class="btn-group dropup">
                <span id="pagination"></span>
            </div>
        </div>
    </div>

    <div class="footer">
        <input type="hidden" id="slug" class="slug">
        <button type="button" id="print_pdf" class="btn btn-success print_pdf display_none" onclick="printReceiving()"><i class="fa fa-print"></i> &nbsp;Export PDF</button>
        <button type="button" id="print_excel" class="btn btn-danger print_excel display_none" onclick="printReceivingExcel()"><i class="fa fa-file-text"></i> &nbsp;Export Excel</button>
    </div>

</div>
