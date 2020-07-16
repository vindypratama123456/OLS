<!-- START BREADCRUMB -->
<ul class="breadcrumb">
    <li><a href="<?php echo base_url(BACKMIN_PATH); ?>">Dasbor</a></li>
    <li><a href="<?php echo base_url(BACKMIN_PATH.'/gudangadjusment'); ?>">Adjustment</a></li>
    <li class="active">Add</li>
</ul>
<!-- END BREADCRUMB -->
<!-- PAGE TITLE -->
<div class="page-title">                    
    <h2><span class="glyphicon glyphicon-bookmark"></span> Buat Adjustment</h2>
    
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
            <?php echo form_open(base_url(BACKMIN_PATH.'/gudangadjusment/add_post'), 'id="frmadjusment" class="form-horizontal" role="form" method="post" enctype="multipart/form-data" autocomplete="off"'); ?>
                <input type="hidden" id="request_id_produk" name="request_id_produk">
                <input type="hidden" id="request_berat" name="request_berat">
                <input type="hidden" id="request_jumlah" name="request_jumlah">
                <input type="hidden" id="is_tags" name="is_tags">
                
                <div class="panel panel-primary">
                    <div class="panel-heading">
                        <h3 class="panel-title">Input Data Adjustment</h3>
                        <ul class="panel-controls">
                            <li><a href="#" class="panel-collapse"><span class="fa fa-angle-up"></span></a></li>
                        </ul>
                    </div>
                    <div class="panel-body">
                        <input type="hidden" id="id_gudang" name="id_gudang" class="form-control id_gudang" value=""/>
                        <input type="hidden" id="nama_gudang" name="nama_gudang" class="form-control nama_gudang" value=""/>
                        <input type="hidden" id="id_tipe" name="id_tipe" class="form-control id_tipe" value="3"/>
                        <div class="form-group">
                            <div class="col-md-6">
                                <label class="control-label">Keterangan</label>
                                <input type="text" id="catatan" name="catatan" class="form-control catatan" value=""/>
                            </div>
                        </div>
                        <!-- <div class="form-group">
                            <div class="col-md-6">
                                <label class="control-label">Tanggal</label>
                                <div class="input-group date" id="datepicker_terimabarang">
                                    <input type="text" id="tanggal" name="tanggal" class="form-control datepicker tanggal" value=""/>
                                    <span class="input-group-addon add-on"><span class="glyphicon glyphicon-calendar"></span></span>
                                </div>
                            </div>
                        </div> -->
                    </div>                            
                </div>
                <div class="panel panel-primary">
                <!-- <div class="panel panel-primary panel-toggled"> -->
                    <div class="panel-heading">
                        <h3 class="panel-title">Input Data Adjustment Detail</h3>
                        <ul class="panel-controls">
                            <li><a href="#" class="panel-collapse"><span class="fa fa-angle-up"></span></a></li>
                        </ul>
                    </div>
                    <div class="panel-body">
                        <table id="myTable" class="table table-bordered table-striped" width="100%">
                                <thead>
                                    <tr>
                                        <th class="text-center" width="15%">Kode Buku</th>
                                        <th class="text-center" width="50%">Judul Buku</th>
                                        <th class="text-center" width="15%">Kelas</th>
                                        <th class="text-center" width="15%">QTY</th>
                                        <th class="text-center" width="10%">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td>
                                            <input class="form-control kode_buku" data-count="1" type="text" id="kode_buku_1" name="kode_buku[]" value="">
                                        </td>
                                        <td>
                                            <input class="form-control" data-count="1" type="text" id="judul_buku_1" name="judul_buku[]" value="" readonly="true">
                                            <input class="form-control" data-count="1" type="hidden" id="id_buku_1" name="id_buku[]" value="" readonly="true">
                                            <input class="form-control" data-count="1" type="hidden" id="berat_buku_1" name="berat_buku[]" value="" readonly="true">
                                            <input class="form-control" data-count="1" type="hidden" id="harga_buku_1" name="harga_buku[]" value="" readonly="true">
                                        </td>
                                        <td>
                                            <input class="form-control kelas" data-count="1" type="text" id="kelas_1" name="kelas[]" value="">
                                        </td>
                                        <td>
                                            <input class="form-control qty" data-count="1" type="text" id="qty_1" name="qty[]" value="">
                                        </td>
                                        <td>
                                            <button class="btn btn-success btn-add" data-count="1" type="button"><span class="fa fa-pencil"></span></button>
                                        </td>
                                    </tr>
                                </tbody>
                        </table>
                        <!-- <div>
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
                        </div> -->
                    </div>                            
                </div>

                <div class="panel panel-default">

                    <div class="form-group panel-footer">
                        <div class="pull-left">
                            <button class="btn btn-success" id="submitRequest">P r o s e s</button>
                        </div>
                        <div class="pull-right">
                            <a href="<?php echo base_url(BACKMIN_PATH.'/gudangrequeststock'); ?>" class="btn btn-primary"><i class="fa fa-arrow-left"></i> Kembali</a>
                        </div>
                    </div>
                </div>
                <br />
            <?php echo form_close(); ?>
        </div>
    </div>
</div>
<!-- END PAGE CONTENT WRAPPER -->