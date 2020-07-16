<!-- START BREADCRUMB -->
<ul class="breadcrumb">
    <li><a href="<?php echo base_url(BACKMIN_PATH); ?>">Dasbor</a></li>
    <li><a href="<?php echo base_url(BACKMIN_PATH.'/gudangpermintaanpartial'); ?>">Permintaan</a></li>
    <li class="active">Barang Masuk</li>
</ul>
<!-- END BREADCRUMB -->
<!-- PAGE TITLE -->
<div class="page-title">                    
    <h2><span class="glyphicon glyphicon-bookmark"></span> List Barang Masuk</h2>
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
                        <table class="display table table-striped responsive datatable data-table" data-table-def="tableListBarangMasuk" id="tableListBarangMasuk" width="100%">
                            <thead>
                                <tr>
                                    <th class="text-center" width="10%">ID Transaksi</th>
                                    <th class="text-center" width="10%">ID Request / Kode Pesanan</th>
                                    <th class="text-center" width="40%">Gudang Asal</th>
                                    <th class="text-center" width="10%">Total Jumlah</th>
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