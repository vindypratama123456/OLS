<!-- START BREADCRUMB -->
<ul class="breadcrumb">
    <li><a href="<?php echo base_url(); ?>">Dasbor</a></li>
    <li><a href="<?php echo base_url(BACKMIN_PATH.'/gudangrequestintan'); ?>">Permintaan Stok Intan</a></li>
    <li class="active">Buat Permintaan Stok Intan</li>
</ul>
<!-- END BREADCRUMB -->
<!-- PAGE TITLE -->
<div class="page-title">                    
    <h2><span class="glyphicon glyphicon-bookmark"></span> Buat Permintaan Stok Intan</h2>
   
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
<!-- END PAGE TITLE -->                
<!-- PAGE CONTENT WRAPPER -->
<div class="page-content-wrap">
    <div class="row">                        
        <div class="col-md-12">
            <div id="errorPlace"></div>
            <?php echo form_open(base_url(BACKMIN_PATH.'/gudangrequestintan/addConfirmation'), 'id="frmRequestIntan" class="form-horizontal" role="form" method="post" enctype="multipart/form-data" autocomplete="off"'); ?>
                <input type="hidden" id="request_id_produk" name="request_id_produk">
                <input type="hidden" id="request_berat" name="request_berat">
                <input type="hidden" id="request_jumlah" name="request_jumlah">
                
                <div class="panel panel-primary panel-toggled">
                    <div class="panel-heading">
                        <h3 class="panel-title">Buku Teks 2013 (SD) &nbsp; <i class="info-collapse">(Klik tombol panah di samping kanan)</i></h3>
                        <ul class="panel-controls">
                            <li><a href="#" class="panel-collapse"><span class="fa fa-angle-up"></span></a></li>
                        </ul>
                    </div>
                    <div class="panel-body panel-body-table">
                        <div>
                            <?php if($listBukuSD) { ?>
                            <table class="table table-bordered table-striped" width="100%">
                                <thead>
                                    <tr>
                                        <th class="text-center" width="55%">Judul Buku</th>
                                        <th class="text-center" width="20%">Kelas</th>
                                        <th class="text-center" width="25%">Jumlah</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach($listBukuSD as $row) { ?>
                                    <tr>
                                        <td>
                                            <?php echo '[ <b>'.$row->kode_buku.'</b> ] &nbsp;'.$row->judul; ?>
                                            <input type="hidden" name="id_product[]" value="<?php echo $row->id_product; ?>">
                                            <input type="hidden" name="weight[]" value="<?php echo $row->weight; ?>">
                                        </td>
                                        <td class="text-center">
                                            <?php echo $row->kelas ?>
                                        </td>
                                        <td class="text-center">
                                            <input class="form-control pqty" type="number" data-id="<?php echo $row->id_product; ?>##<?php echo $row->weight; ?>" name="product_quantity[]" min="0">
                                        </td>
                                    </tr>
                                    <?php } ?>
                                </tbody>
                            </table>
                            <?php } ?>
                        </div>
                    </div>                            
                </div>
                <div class="panel panel-primary panel-toggled">
                    <div class="panel-heading">
                        <h3 class="panel-title">Buku Teks 2013 (SMP) &nbsp; <i class="info-collapse">(Klik tombol panah di samping kanan)</i></h3>
                        <ul class="panel-controls">
                            <li><a href="#" class="panel-collapse"><span class="fa fa-angle-up"></span></a></li>
                        </ul>
                    </div>
                    <div class="panel-body panel-body-table">
                        <div>
                            <?php if($listBukuSMP) { ?>
                            <table class="table table-bordered table-striped" width="100%">
                                <thead>
                                    <tr>
                                        <th class="text-center" width="55%">Judul Buku</th>
                                        <th class="text-center" width="20%">Kelas</th>
                                        <th class="text-center" width="25%">Jumlah</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach($listBukuSMP as $row) { ?>
                                    <tr>
                                        <td>
                                            <?php echo '[ <b>'.$row->kode_buku.'</b> ] &nbsp;'.$row->judul; ?>
                                            <input type="hidden" name="id_product[]" value="<?php echo $row->id_product; ?>">
                                            <input type="hidden" name="weight[]" value="<?php echo $row->weight; ?>">
                                        </td>
                                        <td class="text-center">
                                            <?php echo $row->kelas ?>
                                        </td>
                                        <td class="text-center">
                                            <input class="form-control pqty" type="number" data-id="<?php echo $row->id_product; ?>##<?php echo $row->weight; ?>" name="product_quantity[]" min="0">
                                        </td>
                                    </tr>
                                    <?php } ?>
                                </tbody>
                            </table>
                            <?php } ?>
                        </div>
                    </div>                            
                </div>
                <div class="panel panel-primary panel-toggled">
                    <div class="panel-heading">
                        <h3 class="panel-title">Buku Teks 2013 (SMA) &nbsp; <i class="info-collapse">(Klik tombol panah di samping kanan)</i></h3>
                        <ul class="panel-controls">
                            <li><a href="#" class="panel-collapse"><span class="fa fa-angle-up"></span></a></li>
                        </ul>
                    </div>
                    <div class="panel-body panel-body-table">
                        <div>
                            <?php if($listBukuSMA) { ?>
                            <table class="table table-bordered table-striped" width="100%">
                                <thead>
                                    <tr>
                                        <th class="text-center" width="55%">Judul Buku</th>
                                        <th class="text-center" width="20%">Kelas</th>
                                        <th class="text-center" width="25%">Jumlah</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach($listBukuSMA as $row) { ?>
                                    <tr>
                                        <td>
                                            <?php echo '[ <b>'.$row->kode_buku.'</b> ] &nbsp;'.$row->judul; ?>
                                            <input type="hidden" name="id_product[]" value="<?php echo $row->id_product; ?>">
                                            <input type="hidden" name="weight[]" value="<?php echo $row->weight; ?>">
                                        </td>
                                        <td class="text-center">
                                            <?php echo $row->kelas ?>
                                        </td>
                                        <td class="text-center">
                                            <input class="form-control pqty" type="number" data-id="<?php echo $row->id_product; ?>##<?php echo $row->weight; ?>" name="product_quantity[]" min="0">
                                        </td>
                                    </tr>
                                    <?php } ?>
                                </tbody>
                            </table>
                            <?php } ?>
                        </div>
                    </div>                            
                </div>
                <div class="panel panel-primary panel-toggled">
                    <div class="panel-heading">
                        <h3 class="panel-title">Buku Teks 2006 (SMP) &nbsp; <i class="info-collapse">(Klik tombol panah di samping kanan)</i></h3>
                        <ul class="panel-controls">
                            <li><a href="#" class="panel-collapse"><span class="fa fa-angle-up"></span></a></li>
                        </ul>
                    </div>
                    <div class="panel-body panel-body-table">
                        <div>
                            <?php if($listBukuSMP_ktsp) { ?>
                            <table class="table table-bordered table-striped" width="100%">
                                <thead>
                                    <tr>
                                        <th class="text-center" width="55%">Judul Buku</th>
                                        <th class="text-center" width="20%">Kelas</th>
                                        <th class="text-center" width="25%">Jumlah</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach($listBukuSMP_ktsp as $row) { ?>
                                    <tr>
                                        <td>
                                            <?php echo '[ <b>'.$row->kode_buku.'</b> ] &nbsp;'.$row->judul; ?>
                                            <input type="hidden" name="id_product[]" value="<?php echo $row->id_product; ?>">
                                            <input type="hidden" name="weight[]" value="<?php echo $row->weight; ?>">
                                        </td>
                                        <td class="text-center">
                                            <?php echo $row->kelas ?>
                                        </td>
                                        <td class="text-center">
                                            <input class="form-control pqty" type="number" data-id="<?php echo $row->id_product; ?>##<?php echo $row->weight; ?>" name="product_quantity[]" min="0">
                                        </td>
                                    </tr>
                                    <?php } ?>
                                </tbody>
                            </table>
                            <?php } ?>
                        </div>
                    </div>                            
                </div>
                <div class="panel panel-primary panel-toggled">
                    <div class="panel-heading">
                        <h3 class="panel-title">Buku Peminatan (SMK) &nbsp; <i class="info-collapse">(Klik tombol panah di samping kanan)</i></h3>
                        <ul class="panel-controls">
                            <li><a href="#" class="panel-collapse"><span class="fa fa-angle-up"></span></a></li>
                        </ul>
                    </div>
                    <div class="panel-body panel-body-table">
                        <div>
                            <?php if($listBukuSMK) { ?>
                            <table class="table table-bordered table-striped" width="100%">
                                <thead>
                                    <tr>
                                        <th class="text-center" width="55%">Judul Buku</th>
                                        <th class="text-center" width="20%">Kelas</th>
                                        <th class="text-center" width="25%">Jumlah</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach($listBukuSMK as $row) { ?>
                                    <tr>
                                        <td>
                                            <?php echo '[ <b>'.$row->kode_buku.'</b> ] &nbsp;'.$row->judul; ?>
                                            <input type="hidden" name="id_product[]" value="<?php echo $row->id_product; ?>">
                                            <input type="hidden" name="weight[]" value="<?php echo $row->weight; ?>">
                                        </td>
                                        <td class="text-center">
                                            <?php echo $row->kelas ?>
                                        </td>
                                        <td class="text-center">
                                            <input class="form-control pqty" type="number" data-id="<?php echo $row->id_product; ?>##<?php echo $row->weight; ?>" name="product_quantity[]" min="0">
                                        </td>
                                    </tr>
                                    <?php } ?>
                                </tbody>
                            </table>
                            <?php } ?>
                        </div>
                    </div>                            
                </div>
                <div class="panel panel-primary panel-toggled">
                    <div class="panel-heading">
                        <h3 class="panel-title">Buku Literasi &nbsp; <i class="info-collapse">(Klik tombol panah di samping kanan)</i></h3>
                        <ul class="panel-controls">
                            <li><a href="#" class="panel-collapse"><span class="fa fa-angle-up"></span></a></li>
                        </ul>
                    </div>
                    <div class="panel-body panel-body-table">
                        <div>
                            <?php if($listBukuLiterasi) { ?>
                            <table class="table table-bordered table-striped" width="100%">
                                <thead>
                                    <tr>
                                        <th class="text-center" width="55%">Judul Buku</th>
                                        <th class="text-center" width="20%">Kelas</th>
                                        <th class="text-center" width="25%">Jumlah</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach($listBukuLiterasi as $row) { ?>
                                    <tr>
                                        <td>
                                            <?php echo '[ <b>'.$row->kode_buku.'</b> ] &nbsp;'.$row->judul; ?>
                                            <input type="hidden" name="id_product[]" value="<?php echo $row->id_product; ?>">
                                            <input type="hidden" name="weight[]" value="<?php echo $row->weight; ?>">
                                        </td>
                                        <td class="text-center">
                                            <?php echo $row->kelas ?>
                                        </td>
                                        <td class="text-center">
                                            <input class="form-control pqty" type="number" data-id="<?php echo $row->id_product; ?>##<?php echo $row->weight; ?>" name="product_quantity[]" min="0">
                                        </td>
                                    </tr>
                                    <?php } ?>
                                </tbody>
                            </table>
                            <?php } ?>
                        </div>
                    </div>                            
                </div>
                <br /><br />

                <div class="panel panel-default">
                    <div class="form-group panel-footer">
                        <div class="pull-left">
                            <button class="btn btn-success" id="submitRequest">P r o s e s</button>
                        </div>
                        <div class="pull-right">
                            <a href="<?php echo base_url(BACKMIN_PATH.'/gudangrequestintan'); ?>" class="btn btn-primary"><i class="fa fa-arrow-left"></i> Kembali</a>
                        </div>
                    </div>
                </div>
                <br />
            <?php echo form_close(); ?>
        </div>
    </div>
</div>
<!-- END PAGE CONTENT WRAPPER -->