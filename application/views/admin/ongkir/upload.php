<ul class="breadcrumb">
    <li><a href="<?php echo base_url(ADMIN_PATH); ?>">Dasbor</a></li>
    <li><a href="<?php echo base_url(ADMIN_PATH.'/product'); ?>">Ongkir</a></li>
    <li class="active">Import Batch</li>
</ul>

<div class="page-title">                    
    <h2><span class="glyphicon glyphicon-bookmark"></span> Form Import Batch</h2>
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
					<a href="<?php echo base_url('assets/template/ongkir/'); ?>template_ongkir.xlsx">Download template</a>
                	<br>
					<?php echo form_open(base_url() . ADMIN_PATH . '/ongkir/importPost', 'data-action="' . base_url() . ADMIN_PATH . '/ongkir/importDeletePost" id="product_form" autocomplete="off" enctype="multipart/form-data" method="POST"'); ?>
		        	<div class="form-group">
	                    <div class="col-md-12">
	                        <label>Pilih file excel</label>
	                        <input type="file" name="mikon_file">
	                    </div>
	                </div>
	                <div class="form-group">
	                    <div class="col-md-12"><br /><br />
	                        <button type="submit" class="btn btn-success pull-left">Simpan</button>
	                        <a href="<?php echo base_url().ADMIN_PATH; ?>/product" class="btn btn-primary pull-right">Kembali</a>
	                    </div>
	                </div>
	                <?php echo form_close(); ?>
                </div>
            </div>
        </div>
    </div>                               
</div>