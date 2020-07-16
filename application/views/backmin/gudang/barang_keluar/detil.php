<!-- START BREADCRUMB -->
<ul class="breadcrumb">
    <li><a href="<?php echo base_url(BACKMIN_PATH); ?>">Dasbor</a></li>
    <li><a href="<?php echo base_url(BACKMIN_PATH.'/gudangpermintaan/indexBarangKeluar'); ?>">Permintaan</a></li>
    <li><a href="<?php echo base_url(BACKMIN_PATH.'/gudangpermintaan/indexBarangKeluar'); ?>">Barang Keluar</a></li>
    <li class="active">Detil Data</li>
</ul>
<!-- END BREADCRUMB -->
<!-- PAGE TITLE -->
<div class="page-title">                    
    <h2><span class="glyphicon glyphicon-bookmark"></span> ID #<?php echo $detail['id_transaksi']; ?></h2>
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
                    <h3 class="panel-title">Info Gudang Tujuan</h3>
                    <ul class="panel-controls">
                        <li><a href="#" class="panel-collapse"><span class="fa fa-angle-down"></span></a></li>
                    </ul>
                </div>
                <div class="panel-body">
                    <h4><b><?php echo $gudang['nama_gudang']; ?></b></h4>
                    <p><?php echo $gudang['alamat_gudang']; ?></p>
                    <p>Tanggal Permintaan: <?php echo $detail['created_date']; ?></p>
                    <h6>Status</h6>
                    <p>Status Pengiriman: <?php echo $status_transaksi; ?></p>
                </div>                            
            </div>
            <div class="panel panel-primary">
                <div class="panel-heading">
                    <h3 class="panel-title">Daftar Buku</h3>
                </div>
                <div class="panel-body panel-body-table">
                    <?php echo form_open('', 'id="frmDetilBarangKeluar" class="form-horizontal" data-uri="' . BACKMIN_PATH . '/gudangpermintaan/prosesBarangKeluar" role="form" autocomplete="off"'); ?>
                        <input type="hidden" name="id_transaksi" value="<?php echo $detail['id_transaksi']; ?>">
                        <input type="hidden" name="gudang_asal" value="<?php echo $detail['asal']; ?>">
                        <input type="hidden" name="id_request" value="<?php echo $detail['id_request']; ?>">
                        <div class="table-responsive" id="product-area">
                            <?php if($listproducts) { ?>
                            <table class="table table-bordered table-striped">
                                <thead>
                                    <tr>
                                        <th class="text-center" width="5%">No</th>
                                        <th class="text-center" width="65%">Judul Buku</th>
                                        <th class="text-center" width="15%">Kelas</th>
                                        <th class="text-center" width="15%">Jumlah</th>
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
                                        <td><?php echo $row->judul_buku.' [<b>'.$row->kode_buku.'</b>]<br />(ISBN: '.$row->isbn.')'; ?></td>
                                        <td class="text-center"><?php echo $row->kelas; ?></td>
                                        <td class="text-center"><?php echo $row->jumlah; ?></td>
                                    </tr>
                                    <?php 
                                            $i++;
                                            $tot_item += $row->jumlah;
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
                            <?php
                            $status_transaksi = $detail['status_transaksi'];
                            $status = "";
                            switch ($status_transaksi) {
                                case 1:
                                    $status = '<button class="btn btn-success" id="submitDetail">P r o s e s</button>';
                                    break;
                                case 2:
                                    $status = '<a href="../../gudangpengiriman/add" class="btn btn-success">Buat Surat Jalan</a>';
                                    break;
                            }
                            ?>
                            <div class="pull-left">
                                <?php echo $status; ?>
                            </div>
                            <div class="pull-right">
                                <a href="<?php echo base_url(BACKMIN_PATH.'/gudangpermintaan/indexBarangKeluar'); ?>" class="btn btn-primary"><i class="fa fa-arrow-left"></i> Kembali</a>
                            </div>
                        </div>
                    <?php echo form_close(); ?>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- END PAGE CONTENT WRAPPER -->