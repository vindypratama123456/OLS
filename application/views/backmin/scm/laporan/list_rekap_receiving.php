
<ul class="breadcrumb">
    <li><a href="<?php echo base_url(BACKMIN_PATH); ?>">Dasbor</a></li>
    <li><a href="#">Laporan</a></li>
    <li class="active">Rekapitulasi Stock Receiving</li>
</ul>

<div class="page-title">                    
    <h2><span class="glyphicon glyphicon-bookmark"></span> <?php echo $page_title ?></h2>
</div>

<div class="page-content-wrap">
    <div class="row">                        
        <div class="col-md-12">
            <div class="panel panel-default">
                <div class="panel-body">
                    <div class="row">
                        <?php echo form_open('', 'id="formExportReceivingReport" class="form-horizontal" data-uri="' . BACKMIN_PATH . '/gudangproduction/cetakReceivingReport" role="form" autocomplete="off"'); ?>
                            <div class="form-group">
                                <label class="control-label col-md-2">Pilih tanggal</label>
                                <div class="col-md-4">
                                    <input type="text" id="datefilter" name="datefilter" class="form-control datefilter">
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="control-label col-md-2">Pilih Gudang</label>
                                <div class="col-md-4">
                                    <select id="id_gudang" name="id_gudang" class="form-control id_gudang">
                                        <option value="">-- Pilih Gudang --</option>
                                        <?php foreach($listgudang as $rows => $value) { ?>
                                        <option value="<?php echo $value->id_gudang ?>"><?php echo $value->nama_gudang ?></option>
                                        <?php } ?>
                                    </select>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="control-label col-md-2"></label>
                                <div class="col-md-4">
                                    <button type="submit" class="btn btn-primary" id="submitExport" disabled="">
                                        <i class="glyphicon glyphicon-print"></i>&nbsp;Export Excel
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
            </div>
        </div>
    </div>
</div>