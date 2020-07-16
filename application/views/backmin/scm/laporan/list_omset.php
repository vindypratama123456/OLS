<!-- START BREADCRUMB -->
<ul class="breadcrumb">
    <li><a href="<?php echo base_url(BACKMIN_PATH); ?>">Dasbor</a></li>
    <li><a href="#">Laporan</a></li>
    <li class="active">Rekapitulasi Permintaan Stock</li>
</ul>
<!-- END BREADCRUMB -->
<!-- PAGE TITLE -->
<div class="page-title">                    
    <h2><span class="glyphicon glyphicon-bookmark"></span> <?php echo $page_title ?></h2>
</div>
<!-- END PAGE TITLE -->                
<!-- PAGE CONTENT WRAPPER -->
<div class="page-content-wrap">
    <div class="row">                        
        <div class="col-md-12">
            <div class="panel panel-default">
                <div class="panel-body">
                    <?php echo form_open(base_url(BACKMIN_PATH.'/scmlaporan/searchOmset'), 'class="form-inline" role="form" method="POST" id="frmReportOmset"'); ?>
                        <div class="form-group">
                            <div class="col-md-3">
                                <label>Pilih tanggal awal</label>
                                <div class="input-group date" id="start_date">
                                    <input type="text" id="tanggal_awal" name="tanggal_awal" class="form-control datepicker tanggal_awal" value=""/>
                                    <span class="input-group-addon add-on"><span class="glyphicon glyphicon-calendar"></span></span>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <label>Pilih tanggal akhir</label>
                                <div class="input-group date" id="end_date">
                                    <input type="text" id="tanggal_akhir" name="tanggal_akhir" class="form-control datepicker tanggal_akhir" value=""/>
                                    <span class="input-group-addon add-on"><span class="glyphicon glyphicon-calendar"></span></span>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <label>Pilih Gudang</label>
                                <div>
                                    <select id="gudang" name="gudang" class="form-control gudang">
                                        <option value="0">-- Pilih Gudang --</option>
                                        <?php foreach($listgudang as $rows => $value) { ?>
                                            <option value="<?php echo $value->id_gudang ?>"><?php echo $value->nama_gudang ?></option>
                                        <?php } ?>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-2">
                                <label>&nbsp;</label>
                                <div>
                                    <button type="submit" class="btn btn-info" id="submitSearch">C a r i</button>
                                </div>
                            </div>
                        </div>
                    <?php echo form_close(); ?>
                </div>
            </div>
            <div class="panel panel-default panel-result" id="resultSearch">
                <div class="panel-body">
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th class="text-center">Total Omset</th>
                                <th class="text-center">Total Buku Dipesan</th>
                                <th class="text-center">Total Pesanan</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td class="text-center" id="omset_value">0</td>
                                <td class="text-center" id="buku_value">0</td>
                                <td class="text-center" id="pesanan_value">0</td>
                            </tr>
                        </tbody>
                    </table>
                    <div class="row" id="btn_ekspor" data-ekspor="<?php echo BACKMIN_PATH; ?>/scmlaporan/eksporExcel/" style="text-align: center;">
                        
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- END PAGE CONTENT WRAPPER -->