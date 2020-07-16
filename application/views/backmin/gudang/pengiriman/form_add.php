<!-- START BREADCRUMB -->
<ul class="breadcrumb">
    <li><a href="<?php echo base_url(BACKMIN_PATH); ?>">Dasbor</a></li>
    <li><a href="<?php echo base_url(BACKMIN_PATH.'/gudangpengiriman/index'); ?>">Pengiriman</a></li>
    <li class="active">Buat Surat Jalan</li>
</ul>


<div class="page-title">
    <h2><span class="glyphicon glyphicon-bookmark"></span> Buat Surat Jalan</h2>
    <?php if($this->session->flashdata('error_form')): ?>
    <div role="alert" class="alert alert-danger">
        <button data-dismiss="alert" class="close" type="button"><span aria-hidden="true">×</span><span class="sr-only">Tutup</span></button>
        <?php foreach($this->session->flashdata('error_form') as $data)
        {
            ?><li style="padding:2px 0;"><?php echo $data ?></li><?php
        } ?>
    </div>
    <?php endif; ?>
    
    <?php if($this->session->flashdata('success')): ?>
    <div role="alert" class="alert alert-success">
        <button data-dismiss="alert" class="close" type="button"><span aria-hidden="true">×</span><span class="sr-only">Close</span></button>
        <?php echo $this->session->flashdata('success'); ?>
    </div>
    <?php endif; ?>
    <?php if($this->session->flashdata('error')): ?>
    <div role="alert" class="alert alert-danger">
        <button data-dismiss="alert" class="close" type="button"><span aria-hidden="true">×</span><span class="sr-only">Close</span></button>
        <?php echo $this->session->flashdata('error'); ?>
    </div>
    <?php endif; ?>
    
</div>

<div class="page-content-wrap">
    <div class="row">
        <div class="col-md-12">
            
            <div class="panel panel-primary">
                <div class="panel-heading">
                    <h3 class="panel-title">Daftar Transaksi</h3>
                    <div style="float: right;">
                        <a href="<?php echo base_url(BACKMIN_PATH.'/gudangpengiriman/eksporExcel'); ?>" target="_blank" class="btn btn-success">
                            <span class="glyphicon glyphicon-print"></span> Ekspor Excel
                        </a>
                    </div>
                </div>
                <div class="panel-body">
                    <!--/////////////////// ADD LIST TRANSACTION HERE ///////////////////-->
                    <div class="table-responsive">
                        <table class="display table table-striped responsive datatable data-table nowrap" data-table-def="tableListTransaksi" id="tableListTransaksi" width="100%">
                            <thead>
                                <tr>
                                    <th width="10%" class="text-center">ID</th>
                                    <th width="15%" class="text-center">Kode</th>
                                    <th width="40%" class="text-center">Tujuan</th>
                                    <th width="15%" class="text-center">Jumlah</th>
                                    <th width="15%" class="text-center">Berat (Kg)</th>
                                    <th width="5%" class="text-center">Pilih</th>
                                </tr>
                            </thead>
                        </table>
                    </div>
                </div>
            </div>

            <div class="panel panel-primary">
                <div class="panel-heading">
                    <h3 class="panel-title">Informasi Ekspeditur</h3>
                </div>
                <div class="panel-body">
                    <?php echo form_open('', 'id="formAddSPK" class="form-horizontal" data-uri="' . BACKMIN_PATH . '/gudangpengiriman/prosesAddSPK" role="form" autocomplete="off"'); ?>
                        <div class="form-group">
                            <label class="col-md-3 control-label">Ekspeditur</label>
                            <div class="col-md-5" id="select_ekspeditur">
                                <select id="ekspeditur" name="ekspeditur" class="form-control ekspeditur" data-live-search="true">
                                    <option value="">-- Pilih Ekspeditur --</option>
                                    <?php 
                                    foreach ($ekspeditur as $list) {
                                    ?>
                                        <option value="<?php echo $list->id ?>"><?php echo $list->nama ?></option>
                                    <?php } ?>
                                </select>
                            </div>
                            <div class="col-md-1">
                                <a href="#" id="btn_tambah_ekspeditur" class="btn btn-warning btn-rounded btn-condensed btn-sm" data-toggle="modal" data-target="#tambahEkspeditur">
                                    <span class="fa fa-plus"></span> &nbsp; Ekspeditur
                                </a>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-md-3 control-label">No. Kendaraan</label>
                            <div class="col-md-5">
                                <input type="text" id="nopol" name="nopol" class="form-control nopol" value=""/>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-md-3 control-label">Nama Supir</label>
                            <div class="col-md-5">
                                <input type="text" id="nama_supir" name="nama_supir" class="form-control nama_supir" value=""/>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-md-3 control-label">Telpon/Hp Supir</label>
                            <div class="col-md-5">
                                <input type="text" id="hp_supir" name="hp_supir" class="form-control hp_supir" value=""/>
                            </div>
                        </div>
                        <br><br>
                        <div class="form-group">
                            <div class="pull-left">
                                <button class="btn btn-success" id="submitForm">P r o s e s</button>
                            </div>
                            <div class="pull-right">
                                <a href="<?php echo base_url(BACKMIN_PATH.'/gudangpengiriman/index'); ?>" class="btn btn-primary"><i class="fa fa-arrow-left"></i> Kembali</a>
                            </div>
                        </div>
                    <?php echo form_close(); ?>
                </div>
            </div>

            <div class="modal fade" id="tambahEkspeditur" tabindex="-1" role="dialog" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Tutup</span></button>
                            <h4 class="modal-title">Tambah Ekspeditur</h4>
                        </div>
                        <?php echo form_open('', 'id="formAddEkspeditur" class="form-horizontal" data-uri="' . BACKMIN_PATH . '/gudangpengiriman/prosesAddEkspeditur" role="form" autocomplete="off"'); ?>
                            <div class="modal-body" id="form_ekspeditur">
                                <div class="row">
                                    <div class="form-group">
                                        <label class="col-md-4 control-label">Nama</label>
                                        <div class="col-md-6">
                                            <input type="text" id="nama_ekspeditur" name="nama_ekspeditur" class="form-control nama_ekspeditur" value=""/>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-md-4 control-label">Alamat</label>
                                        <div class="col-md-6">
                                            <input type="text" id="alamat_ekspeditur" name="alamat_ekspeditur" class="form-control alamat_ekspeditur" value=""/>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-md-4 control-label">No Telepon</label>
                                        <div class="col-md-6">
                                            <input type="text" id="no_telpon_ekspeditur" name="no_telpon_ekspeditur" class="form-control no_telpon_ekspeditur" value=""/>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <div class="pull-left">
                                    <button type="button" class="btn btn-success" id="submitEkspeditur">P r o s e s</button>
                                </div>
                                <div class="pull-right">
                                    <button type="button" class="btn btn-primary" data-dismiss="modal">Tutup</button>
                                </div>
                            </div>
                        <?php echo form_close(); ?>
                    </div>
                </div>
            </div>

        </div>
    </div>                       
</div>
