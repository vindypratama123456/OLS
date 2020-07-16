<!-- START BREADCRUMB -->
<ul class="breadcrumb">
    <li><a href="<?php echo base_url(); ?>">Dasbor</a></li>
    <li><a href="<?php echo base_url(BACKMIN_PATH.'/gudangrequeststock'); ?>">Permintaan Stok</a></li>
    <li class="active">Buat Permintaan Stok</li>
</ul>
<!-- END BREADCRUMB -->
<!-- PAGE TITLE -->
<div class="page-title">                    
    <h2><span class="glyphicon glyphicon-bookmark"></span> Konfirmasi Permintaan Stok</h2>
    <?php if($this->session->flashdata('error')): ?>
    <div role="alert" class="alert alert-danger">
        <button data-dismiss="alert" class="close" type="button"><span aria-hidden="true">×</span><span class="sr-only">Close</span></button>
        <?php foreach($this->session->flashdata('error') as $data)
        {
            ?><li style="padding:2px 0;"><?php echo $data ?></li><?php
        } ?>
    </div>
    <?php endif; ?>
</div>
<!-- END PAGE TITLE -->                
<!-- PAGE CONTENT WRAPPER -->
<div class="page-content-wrap">
    <div class="row">                        
        <div class="col-md-12">
            <div id="errorPlace"></div>
            <?php echo form_open('', 'id="frmContinueRequestStock" class="form-horizontal" data-uri="' . BACKMIN_PATH . '/gudangproduction/addConfirmationPost" role="form" autocomplete="off"'); ?>
                <div class="panel panel-primary">
                    <div class="panel-heading">
                        <h3 class="panel-title">Daftar Buku</h3>
                        <ul class="panel-controls">
                            <li><a href="#" class="panel-collapse"><span class="fa fa-angle-up"></span></a></li>
                        </ul>
                    </div>
                    <div class="panel-body panel-body-table">
                        <div>
                            <?php if($list_request) { ?>
                            <table class="table table-bordered table-striped" width="100%">
                                <thead>
                                    <tr>
                                        <th class="text-center" width="35%">Judul Buku</th>
                                        <th class="text-center" width="13%">Kategori</th>
                                        <th class="text-center" width="10%">Kelas</th>
                                        <th class="text-center" width="12%">No. OEF</th>
                                        <th class="text-center" width="18%">Gudang</th>
                                        <th class="text-center" width="12%">Jumlah</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach($list_request as $row) { ?>
                                    <tr>
                                        <td>
                                            <?php echo '[ <b>'.$row->kode_buku.'</b> ] &nbsp;'.$row->judul; ?>
                                        </td>
                                        <td class="text-center">
                                            <?php echo $row->type ?>
                                        </td>
                                        <td class="text-center">
                                            <?php echo $row->kelas ?>
                                        </td>
                                        <td class="text-center">
                                            <input class="form-control no_oef" type="text"  name="no_oef[]" value="<?php echo $row->no_oef ?>">
                                        </td>
                                        <td class="text-center">
                                            <input class="form-control id_gudang" type="hidden"  name="id_gudang[]" value="<?php echo $row->id_gudang ?>">
                                            <input class="form-control nama_gudang" type="text"  name="nama_gudang[]" value="<?php echo $row->nama_gudang ?>">

                                            <input type="hidden" name="id_produk[]" value="<?php echo $row->id_product; ?>">
                                            <input type="hidden" name="kode_buku[]" value="<?php echo $row->kode_buku; ?>">
                                            <input type="hidden" name="judul[]" value="<?php echo $row->judul; ?>">
                                            <input type="hidden" name="jumlah[]" value="<?php echo $row->jumlah ?>">
                                        </td>
                                        <td class="text-center">
                                            <input class="form-control pqty_request" type="number" data-id="<?php echo $row->id_product; ?>##<?php echo $row->kode_buku; ?>##<?php echo $row->judul; ?>" name="product_quantity[]" min="0" value="<?php echo $row->jumlah ?>">
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
                            <button class="btn btn-success" id="continueRequest">L a n j u t k a n</button>
                        </div>
                        <div class="pull-right">
                            <a href="<?php echo base_url(BACKMIN_PATH.'/gudangrequeststock/add'); ?>" class="btn btn-primary"><i class="fa fa-arrow-left"></i> Kembali</a>
                        </div>
                    </div>
                </div>
                <br />
            <?php echo form_close(); ?>
        </div>
    </div>
</div>
<!-- END PAGE CONTENT WRAPPER -->