<ul class="breadcrumb">
    <li><a href="<?php echo base_url(BACKMIN_PATH); ?>">Dasbor</a></li>
    <li class="active">Permintaan Stok</li>
</ul>

<div class="page-title">
    <h2><span class="glyphicon glyphicon-bookmark"></span> List Permintaan Stok</h2>
    <a href="<?php echo base_url(BACKMIN_PATH . '/gudangrequeststock/add'); ?>" class="btn btn-success" style="float: right;">
        <span class="glyphicon glyphicon-plus"></span> Buat Permintaan
    </a>
</div>

<div class="page-content-wrap">
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
                        <table class="display table table-striped responsive datatable data-table" data-table-def="tableListRequestStock" id="tableListRequestStock" width="100%">
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
</div>