
<ul class="breadcrumb">
    <li><a href="<?php echo base_url(BACKMIN_PATH); ?>">Dasbor</a></li>
    <li><a href="<?php echo base_url(BACKMIN_PATH.'/scmrequeststockpartial/indexrequeststockmasuk'); ?>">Permintaan TAG</a></li>
    <li class="active">Permintaan Masuk</li>
</ul>

<div class="page-title">                    
    <h2><span class="glyphicon glyphicon-bookmark"></span> List Permintaan Stok Masuk</h2>
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
                        <!-- <table class="display table table-striped responsive datatable data-table" data-table-def="tableListRequestStock" id="tableListRequestStock" width="100%"> -->

                <table class="table table-striped dt-responsive responsive" id="tableListRequestStock">
                            <thead>
                                <tr>
                                    <th class="text-center" width="10%">ID Request</th>
                                    <th class="text-center" width="25%">Nama Gudang</th>
                                    <th class="text-center" width="10%">Total Jumlah</th>
                                    <th class="text-center" width="10%">Sisa Jumlah</th>
                                    <th class="text-center" width="20%">Tanggal</th>
                                    <!-- <th class="text-center" width="10%">TAG</th> -->
                                    <th class="text-center" width="15%">Pesanan Intan</th>
                                    <th class="text-center" width="15%">Status</th>
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