<!-- <!DOCTYPE html>
<html>
<head>
    <title></title>
</head>
<body>
    <?php echo form_open(base_url() . ADMIN_PATH . '/product/importPost', 'data-action="' . base_url() . ADMIN_PATH . '/product/importPost" id="product_form" autocomplete="off" enctype="multipart/form-data" method="POST"'); ?>
            <input type="file" name="mikon_file">

            <button type="submit" class="btn btn-success pull-left">Simpan</button>


            <?php echo form_close(); ?>
</body>
</html>
 -->


<ul class="breadcrumb">
    <li><a href="<?php echo base_url(BACKMIN_PATH); ?>">Dasbor</a></li>
    <li><a href="<?php echo base_url(BACKMIN_PATH.'/scmrequeststock/indexRequestStockMasuk'); ?>">Email Blacklist</a></li>
    <li class="active">Import</li>
</ul>

<div class="page-title">                    
    <h2><span class="glyphicon glyphicon-bookmark"></span> Import Email Blacklist</h2>
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
                    <?php echo form_open(base_url() . 'backoffice/emailblacklist/importPost', 'data-action="' . base_url() . ADMIN_PATH . '/product/importPost" id="product_form" autocomplete="off" enctype="multipart/form-data" method="POST"'); ?>
                    <div class="form-group">
                        <div class="col-md-8">
                            <a href="<?php echo base_url('assets/template/template_email_blacklist.xlsx'); ?>">Download template</a>
                            <br><br>
                        </div>
                    </div>
                	<div class="form-group form-inline">
                        <div class="col-md-8">
                            <input type="file" name="mikon_file">
                        </div>
		        	</div>
                    <div class="form-group">
                        <div class="col-md-8"><br>
                            <button type="submit" class="btn btn-success pull-left">Simpan</button>
                        </div>
                        <?php echo form_close(); ?>
                    </div>
                </div>
            </div>
        </div>
    </div>                               
</div>