<ul class="breadcrumb">
    <li><a href="<?php echo base_url(BACKMIN_PATH); ?>">Dasbor</a></li>
    <li  class="active">Download BAST Siplah</li>
</ul>

<div class="page-title">                    
    <h2><span class="glyphicon glyphicon-bookmark"></span> Download BAST Siplah</h2>
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
                    <?php echo form_open(base_url() . BACKMIN_PATH . '/gudangpengiriman/download_bast_process', 'data-action="' . base_url() . BACKMIN_PATH . '/gudangpengiriman/download_bast_process" id="product_form" autocomplete="off" enctype="multipart/form-data" method="POST"'); ?>
                	<div class="form-group">
                    	<label for="no_po">Kode Pesanan</label>
		            	<input type="text" class="form-control" id="no_po" name="no_po">
		        	</div>
                    <div class="form-group">
                        <button type="submit" class="btn btn-success pull-left">Download</button>
                    </div>
                    <?php echo form_close(); ?>
                </div>
            </div>
        </div>
    </div>                               
</div>