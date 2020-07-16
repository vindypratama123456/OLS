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
            
            <?php echo form_open('', 'id="formReportSalesAnalysis" class="form-horizontal" data-uri="' . base_url(ADMIN_PATH . '/finance/reportSalesAnalysis') . '" role="form" autocomplete="off"'); ?>
                <div class="form-group">
                    <label class="control-label col-md-2">Pilih bulan</label>
                    <div class="col-md-4">
                        <div class="input-group date" id="month_date">
                            <input type="text" id="date_month" name="date_month" class="form-control datepicker date_month input-sm" value=""/>
                            <span class="input-group-addon add-on"><span class="glyphicon glyphicon-calendar"></span></span>
                        </div>
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
                        <button type="submit" class="btn btn-primary" id="submitReport">
                            <strong>Submit</strong>
                        </button>
                    </div>
                </div>
                <input type="hidden" id="type" name="type" value="<?php echo $type; ?>">
            <?php echo form_close(); ?>

        </div>
    </div>

    <div class="row">
        <hr>
    </div>

    <div class="row export">
        <div class="col-md-12">
            <input type="hidden" id="slug" class="slug">
            <button type="button" id="print_excel" class="btn btn-danger print_excel display_none" onclick="printSalesAnalysisExcel()"><i class="fa fa-file-text"></i> &nbsp;Export Excel</button>
        </div>
    </div>

    <div class="row">
        <div class="col-md-12">
            <table class="table table-bordered table-stripped">
                <thead class="table-head">
                    <tr>
                        <th class="text-center" rowspan="2">Kode Buku</th>
                        <th class="text-center" rowspan="2">Judul Buku</th>
                        <th class="text-center" colspan="5">Current</th>
                        <th class="text-center" colspan="5">Year To Date</th>
                    </tr>
                    <tr>
                        <th class="text-center">Qty</th>
                        <th class="text-center">Cost</th>
                        <th class="text-center">Bruto</th>
                        <th class="text-center">Disc</th>
                        <th class="text-center">Netto</th>
                        <th class="text-center">Qty</th>
                        <th class="text-center">Cost</th>
                        <th class="text-center">Bruto</th>
                        <th class="text-center">Disc</th>
                        <th class="text-center">Netto</th>
                    </tr>
                </thead>
                <tbody id="dataTable" class="table-body">
                    <tr>
                        <td colspan="12"><center>Data tidak tersedia</center></td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>

</div>
