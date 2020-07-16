<!-- START BREADCRUMB -->
<ul class="breadcrumb">
    <li><a href="<?php echo base_url(BACKMIN_PATH); ?>">Dasbor</a></li>
    <li><a href="<?php echo base_url(BACKMIN_PATH.'/scmrequeststock/indexRequestStockDiproses'); ?>">Permintaan</a></li>
    <li><a href="<?php echo base_url(BACKMIN_PATH.'/scmrequeststock/indexRequestStockDiproses'); ?>">Permintaan Diproses</a></li>
    <li class="active">Permintaan</li>
</ul>
<!-- END BREADCRUMB -->
<!-- PAGE TITLE -->
<div class="page-title">                    
    <h2><span class="glyphicon glyphicon-bookmark"></span> Permintaan Stok #<?php echo $detail['id_request']; ?></h2>
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
                    <h3 class="panel-title">Info Gudang Pemesan</h3>
                    <ul class="panel-controls">
                        <li><a href="#" class="panel-collapse"><span class="fa fa-angle-down"></span></a></li>
                    </ul>
                </div>
                <div class="panel-body">
                    <?php
                    $is_tag = "";
                    if ($detail['is_tag'] == 1) 
                    {
                        $is_tag = "Transfer Antar Gudang";
                    }
                    else
                    {
                        if ($detail['is_intan'] == 1) {
                            $is_tag = "Permintaan Intan";
                        } else {
                            $is_tag = "Pengisian Stok Sendiri";
                        }
                    }
                    ?>
                    <h4>
                        <b><?php echo $gudang['nama_gudang']; ?></b> 
                        <?php if ($gudang['is_utama'] == 1) { ?>
                            (Gudang Utama)
                        <?php } ?>
                    </h4>
                    <p><?php echo $gudang['alamat_gudang']; ?></p>
                    <p>Tanggal Permintaan: <?php echo $detail['created_date']; ?></p>
                    <h6>Status</h6>
                    <p>Status Pemenuhan Stok Pengirim : <?php echo $status; ?>
                    <!-- <br> -->
                    <!--<p>Status Pemenuhan Stok: <?php echo $status; ?><br>!-->
                    <!-- Pengiriman : <?php echo $is_tag; ?> -->
                    </p>
                    <?php if($detail['is_intan'] == 1) { ?>
                    <br>
                    <p>Tujuan Pengiriman : <b>Intan Pariwara</b></p>
                    <?php 
                    } else { 
                        if($detail['is_tag'] == 1) {
                    ?>
                    <br>
                    <!--<p>Gudang Pengirim : <b><?php echo $gudang_pengirim; ?></b></p>!-->

                    <?php } 
                    } ?>
                </div>                            
            </div>
            <div class="panel panel-primary">
                <div class="panel-heading">
                    <h3 class="panel-title">Daftar Buku</h3>
                </div>
                <div class="panel-body panel-body-table">
                    <?php echo form_open('', 'id="frmDetilPermintaanStok" class="form-horizontal" data-uri="' . BACKMIN_PATH . '/scmrequeststock/processRequestStockDiproses" role="form" autocomplete="off"'); ?>
                        <input type="hidden" name="id_request" value="<?php echo $detail['id_request']; ?>">
                        <input type="hidden" name="id_gudang_request" value="<?php echo $gudang['id_gudang']; ?>">
                        <input type="hidden" name="gudang_utama" value="<?php echo $gudang['is_utama']; ?>">
                        <div class="table-responsive" id="product-area">
                            <?php if($listproducts) { ?>
                            <table class="table table-bordered table-striped">
                                <thead>
                                    <tr>
                                        <th class="text-center" width="2%">No</th>
                                        <th class="text-center" width="58%">Judul Buku</th>
                                        <th class="text-center" width="15%">Kategori</th>
                                        <th class="text-center" width="15%">Kelas</th>
                                        <th class="text-center" width="15%">Permintaan Stok</th>
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
                                        <td>
                                            <?php echo '[ <b>'.$row->kode_buku.'</b> ] &nbsp;'.$row->product_name.' <br> (ISBN: '.$row->isbn.')'; ?>
                                            <input type="hidden" name="id_product[]" value="<?php echo $row->id_produk; ?>">
                                            <input type="hidden" name="weight[]" value="<?php echo $row->weight; ?>">
                                        </td>
                                        <td class="text-center"><?php echo $row->type; ?></td>
                                        <td class="text-center"><?php echo $row->kelas; ?></td>
                                        <td class="text-center">
                                            <?php echo $row->product_quantity; ?>
                                            <input type="hidden" name="product_quantity[]" value="<?php echo $row->product_quantity; ?>">
                                        </td>
                                    </tr>
                                    <?php 
                                            $i++;
                                            $tot_item += $row->product_quantity;
                                        }
                                    ?>
                                    <tr>
                                        <td colspan="4" class="text-right"><b>Total Jumlah</b></td>
                                        <td class="text-center"><b><?php echo $tot_item; ?></b></td>
                                    </tr>
                                </tbody>
                            </table>
                            <?php } ?>
                        </div>                                
                        <div class="form-group panel-footer">
                            <div class="pull-left">
                                <a href="<?php echo base_url(BACKMIN_PATH."/scmrequeststock/cetakRequest/".$detail['id_request']); ?>" class="btn btn-success" style="margin-left:10px;">Ekspor Excel</a>
                            </div>
                            <div class="pull-right">
                                <a href="<?php echo base_url(BACKMIN_PATH.'/scmrequeststockpartial/indexRequestStockDiproses'); ?>" class="btn btn-primary"><i class="fa fa-arrow-left"></i> Kembali</a>
                            </div>
                        </div>
                    <?php echo form_close(); ?>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- END PAGE CONTENT WRAPPER -->