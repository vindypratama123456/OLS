<!-- START BREADCRUMB -->
<ul class="breadcrumb">
    <li><a href="<?php echo base_url(BACKMIN_PATH); ?>">Dasbor</a></li>
    <li><a href="<?php echo base_url(BACKMIN_PATH.'/gudangpermintaan/indexBarangMasuk'); ?>">Permintaan</a></li>
    <li><a href="<?php echo base_url(BACKMIN_PATH.'/gudangpermintaan/indexBarangMasuk'); ?>">Barang Masuk</a></li>
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
                    <h3 class="panel-title">Info Gudang Asal</h3>
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
            <?php if (count($customer) > 0) { ?>
            <div class="panel panel-primary">
                <div class="panel-heading">
                    <h3 class="panel-title">Pelanggan</h3>
                    <ul class="panel-controls">
                        <li><a href="#" class="panel-collapse"><span class="fa fa-angle-down"></span></a></li>
                    </ul>
                </div>
                <div class="panel-body">
                    <h4><?php echo $customer['school_name']; ?></h4>
                    <?php if($pesanan->status_transaksi == 1 || $pesanan->status_transaksi == 3) { ?>
                        <h5><a href="<?php echo base_url().BACKMIN_PATH.'/gudangpesanan/detailPesananMasuk/'.$pesanan->id_pesanan ?>" target="_blank">#<?php echo $pesanan->kode_pesanan; ?></a></h5>
                    <?php } else { ?>
                        <h5><a href="<?php echo base_url().BACKMIN_PATH.'/gudangpesanan/detailPesananDiproses/'.$pesanan->id_pesanan ?>" target="_blank">#<?php echo $pesanan->kode_pesanan; ?></a></h5>
                    <?php } ?>
                    <p><?php echo $customer['alamat'].'<br />'.$customer['desa'].', '.$customer['kecamatan'].', '.$customer['kabupaten'].', '.$customer['provinsi'].' - '.$customer['kodepos']; ?></p>
                </div>                            
            </div>
            <?php } ?>
            <div class="panel panel-primary">
                <div class="panel-heading">
                    <h3 class="panel-title">Daftar Buku</h3>
                </div>
                <div class="panel-body panel-body-table">
                    <?php echo form_open('', 'id="frmDetilBarangMasuk" class="form-horizontal" data-uri="' . BACKMIN_PATH . '/gudangpermintaan/prosesBarangMasuk" role="form" autocomplete="off"'); ?>
                        <input type="hidden" name="id_transaksi" value="<?php echo $detail['id_transaksi']; ?>">
                        <input type="hidden" name="status" value="<?php echo $detail['status_transaksi']; ?>">
                        <input type="hidden" name="ref_id" value="<?php echo $detail['ref_id']; ?>">
                        <input type="hidden" name="gudang_asal" value="<?php echo $detail['asal']; ?>">
                        <input type="hidden" name="gudang_tujuan" value="<?php echo $detail['tujuan']; ?>">
                        <input type="hidden" name="periode_request" value="<?php echo $periode; ?>">
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
                                        <input type="hidden" name="id_produk[]" value="<?php echo $row->id_product; ?>">
                                        <input type="hidden" name="jumlah[]" value="<?php echo $row->jumlah; ?>">
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
                                case 5:
                                    $status = '<a href="#" id="btn_terima_barang" class="btn btn-success" data-toggle="modal" data-target="#terimaBarang">Terima Barang</a>';
                                    break;
                                default:
                                    $status = '';
                                    break;
                            }
                            ?>
                            <div class="pull-left">
                                <?php echo $status; ?>
                            </div>
                            <div class="pull-right">
                                <a href="<?php echo base_url(BACKMIN_PATH.'/gudangpermintaan/indexBarangMasuk'); ?>" class="btn btn-primary"><i class="fa fa-arrow-left"></i> Kembali</a>
                            </div>
                        </div>

                        <div class="modal fade" id="terimaBarang" tabindex="-1" role="dialog" aria-hidden="true">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
                                        <h4 class="modal-title">Info Ekspeditur</h4>
                                    </div>
                                    <div class="modal-body">
                                        <div class="row">
                                            <div class="form-group">
                                                <label class="col-md-4 control-label">No Polisi</label>
                                                <div class="col-md-6">
                                                    <input type="text" id="nopol" name="nopol" class="form-control nopol" value=""/>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label class="col-md-4 control-label">Nama Supir</label>
                                                <div class="col-md-6">
                                                    <input type="text" id="nama_supir" name="nama_supir" class="form-control nama_supir" value=""/>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label class="col-md-4 control-label">No Telepon Supir</label>
                                                <div class="col-md-6">
                                                    <input type="text" id="hp_supir" name="hp_supir" class="form-control hp_supir" value=""/>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="modal-footer">
                                        <div class="pull-left">
                                            <button class="btn btn-success" id="submitDetail">P r o s e s</button>
                                        </div>
                                        <div class="pull-right">
                                            <button type="button" class="btn btn-primary" data-dismiss="modal">Close</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php echo form_close(); ?>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- END PAGE CONTENT WRAPPER -->