<!-- START BREADCRUMB -->
<ul class="breadcrumb">
    <li><a href="<?php echo base_url(BACKMIN_PATH); ?>">Dasbor</a></li>
    <li><a href="<?php echo base_url(BACKMIN_PATH.'/scmpesanan/indexPesananMasuk'); ?>">Pesanan</a></li>
    <li class="active">Pesanan Masuk</li>
</ul>
<!-- END BREADCRUMB -->
<!-- PAGE TITLE -->
<div class="page-title">                    
    <h2><span class="glyphicon glyphicon-bookmark"></span> List Pesanan Masuk</h2>
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
                        <table class="display table table-striped responsive datatable data-table" data-table-def="tableListPesananMasuk" id="tableListPesananMasuk" width="100%">
                            <thead>
                                <tr>
                                    <th class="text-center">Kode</th>
                                    <th class="text-center">Nama Sekolah</th>
                                    <th class="text-center">Kelas</th>
                                    <th class="text-center">Kategori</th>
                                    <th class="text-center">Propinsi</th>
                                    <th class="text-center">Kabupaten</th>
                                    <th class="text-center">Target Kirim</th>
                                    <th class="text-center">Tanggal Pesan</th>
                                    <th class="text-center">Gudang</th>
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