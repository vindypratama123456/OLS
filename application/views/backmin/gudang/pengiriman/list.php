<!-- START BREADCRUMB -->
<ul class="breadcrumb">
    <li><a href="<?php echo base_url(BACKMIN_PATH); ?>">Dasbor</a></li>
    <li><a href="<?php echo base_url(BACKMIN_PATH.'/gudangpengiriman/index'); ?>">Pengiriman</a></li>
    <li class="active">Daftar Pengiriman</li>
</ul>
<!-- END BREADCRUMB -->
<!-- PAGE TITLE -->
<div class="page-title">
    <h2><span class="glyphicon glyphicon-bookmark"></span> List Pengiriman Barang</h2>
    <div class="btn-group pull-right">
        <button class="btn btn-danger dropdown-toggle" data-toggle="dropdown"><i class="fa fa-bars"></i> Export Data</button>
        <ul class="dropdown-menu">
            <li><a href="#" onClick ="$('#tableListDaftarPengiriman').tableExport({type:'excel',escape:'false'});"><img src='<?php echo image_export(); ?>xls.png' width="24"/> XLS</a></li>
        </ul>
    </div>
    <a href="<?php echo base_url(BACKMIN_PATH.'/gudangpengiriman/add'); ?>" class="btn btn-success" style="margin-right: 5px; float: right;">
        <span class="glyphicon glyphicon-plus"></span> Buat Surat Jalan
    </a>
</div>
<!-- END PAGE TITLE -->                
<!-- PAGE CONTENT WRAPPER -->
<div class="page-content-wrap">
    <!-- START RESPONSIVE TABLES -->
    <div class="row">
        <div class="col-md-12">
            
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
            
            <div class="panel panel-default">
                <div class="panel-body">
                    <div class="table-responsive">
                        <table class="display table table-striped responsive datatable data-table" data-table-def="tableListDaftarPengiriman" id="tableListDaftarPengiriman" width="100%">
                            <thead>
                                <tr>
                                    <th class="text-center" width="10%">Kode</th>
                                    <th class="text-center" width="10%">Ekspedisi</th>
                                    <th class="text-center" width="20%">Tujuan</th>
                                    <th class="text-center" width="10%">Total Jumlah</th>
                                    <th class="text-center" width="10%">Total Berat</th>
                                    <th class="text-center" width="20%">Tanggal</th>
                                    <th class="text-center" width="10%">Status</th>
                                    <th class="text-center" width="10%">Detil</th>
                                </tr>
                            </thead>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- END RESPONSIVE TABLES -->
<!-- END PAGE CONTENT WRAPPER -->                                    
</div>