<!-- START BREADCRUMB -->
<ul class="breadcrumb">
    <li><a href="<?php echo base_url(); ?>">Dasbor</a></li>
    <li><a href="<?php echo base_url(BACKMIN_PATH.'/gudangrequestintan'); ?>">Permintaan Stok Intan</a></li>
    <li class="active">Detil Data</li>
</ul>
<!-- END BREADCRUMB -->
<!-- PAGE TITLE -->
<div class="page-title">                    
    <h2><span class="glyphicon glyphicon-bookmark"></span> Permintaan Stok Intan</h2>
</div>
<!-- END PAGE TITLE -->                
<!-- PAGE CONTENT WRAPPER -->
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
            
            <div class="panel panel-primary">
                <div class="panel-heading">
                    <h3 class="panel-title">Info Gudang Pengirim</h3>
                    <ul class="panel-controls">
                        <li><a href="#" class="panel-collapse"><span class="fa fa-angle-down"></span></a></li>
                    </ul>
                </div>
                <div class="panel-body">
                    <h4><?php echo $gudang['nama_gudang']; ?></h4>
                    <p><?php echo $gudang['alamat_gudang']; ?></p>
                    <p>Tanggal Permintaan: <?php echo $detail['created_date']; ?></p>
                    <h6>Status</h6>
                    <p>Status Permintaan Stok : <?php echo $status; ?><br>
                    Pengiriman : Kiriman ke Intan
                    </p>
                </div>                            
            </div>
            <div class="panel panel-primary">
                <div class="panel-heading">
                    <h3 class="panel-title">Daftar Buku</h3>
                </div>
                <div class="panel-body panel-body-table">
                    <?php echo form_open('', 'id="frmDetilPermintaanStok" class="form-horizontal" role="form" autocomplete="off"'); ?>
                        <input type="hidden" name="id_request" value="<?php echo $detail['id_request']; ?>">
                        <div class="table-responsive" id="product-area">
                            <?php if($listproducts) { ?>
                            <table class="table table-bordered table-striped">
                                <thead>
                                    <tr>
                                        <th class="text-center" width="2%">No</th>
                                        <th class="text-center" width="48%">Judul Buku</th>
                                        <th class="text-center" width="15%">Kategori</th>
                                        <th class="text-center" width="15%">Kelas</th>
                                        <th class="text-center" width="10%">Jumlah Pesan</th>
                                        <th class="text-center" width="10%">Status Stok</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php 
                                        $i=1; 
                                        $tot_item = 0;
                                        foreach($listproducts as $row) { 
                                    ?>
                                    <tr id="trow_<?php echo $i; ?>">
                                        <td class="text-center"><?php echo $i; ?></td>
                                        <td><?php echo '[ <b>'.$row->kode_buku.'</b> ] &nbsp;'.$row->product_name.' <br> (ISBN: '.$row->isbn.')'; ?></td>
                                        <td class="text-center"><?php echo $row->type; ?></td>
                                        <td class="text-center"><?php echo $row->kelas; ?></td>
                                        <td class="text-center"><?php echo $row->product_quantity; ?></td>
                                        <td class="text-center">
                                            <?php if($row->stok_available >= $row->product_quantity) { ?>
                                                <span class="fa fa-check text-success"></span>
                                            <?php } else { ?>
                                                <span class="fa fa-remove text-danger"></span>
                                            <?php } ?>
                                        </td>
                                    </tr>
                                    <?php 
                                            $i++;
                                            $tot_item += $row->product_quantity;
                                        }
                                    ?>
                                    <tr>
                                        <td colspan="3" class="text-right"><b>Total Jumlah</b></td>
                                        <td class="text-center"><b><?php echo $tot_item; ?></b></td>
                                    </tr>
                                </tbody>
                            </table>
                            <?php } ?>
                        </div>                                
                        <div class="form-group panel-footer">
                            <div class="pull-right">
                                <a href="<?php echo base_url(BACKMIN_PATH.'/gudangrequestintan'); ?>" class="btn btn-primary"><i class="fa fa-arrow-left"></i> Kembali</a>
                            </div>
                        </div>
                    <?php echo form_close(); ?>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- END PAGE CONTENT WRAPPER -->