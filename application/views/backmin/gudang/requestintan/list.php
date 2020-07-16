<!-- START BREADCRUMB -->
<ul class="breadcrumb">
    <li><a href="<?php echo base_url(BACKMIN_PATH); ?>">Dasbor</a></li>
    <li class="active">Permintaan Stok Intan</li>
</ul>
<!-- END BREADCRUMB -->
<!-- PAGE TITLE -->
<div class="page-title">                    
    <h2><span class="glyphicon glyphicon-bookmark"></span> List Permintaan Stok Intan</h2>
    <a href="<?php echo base_url(BACKMIN_PATH.'/gudangrequestintan/add'); ?>" class="btn btn-success" style="float: right;">
        <span class="glyphicon glyphicon-plus"></span> Buat Permintaan
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
                        <table class="display table table-striped responsive datatable data-table" data-table-def="tableListRequestIntan" id="tableListRequestIntan" width="100%">
                            <thead>
                                <tr>
                                    <th class="text-center" width="10%">ID Request</th>
                                    <th class="text-center" width="30%">Total Jumlah</th>
                                    <th class="text-center" width="20%">Tanggal</th>
                                    <th class="text-center" width="10%">TAG</th>
                                    <th class="text-center" width="20%">Status</th>
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