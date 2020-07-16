<!-- START BREADCRUMB -->
<ul class="breadcrumb">
    <li><a href="<?php echo base_url(BACKMIN_PATH); ?>">Dasbor</a></li>
    <li><a href="<?php echo base_url(BACKMIN_PATH.'/scmrequeststock/indexRequestStockMasuk'); ?>">Permintaan</a></li>
    <li><a href="<?php echo base_url(BACKMIN_PATH.'/scmrequeststock/indexRequestStockMasuk'); ?>">Permintaan Masuk</a></li>
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
                        $is_tag = "Pengisian Stok Sendiri";
                    }
                    ?>
                    <h4>
                        <b><?php echo $gudang['nama_gudang']; ?></b> 
                        <?php if ($gudang['is_utama'] == 1) { ?>
                            (Gudang Utama)
                        <?php } ?>
                    </h4>
                    <p><?php echo $gudang['alamat_gudang']; ?></p>
                    <p>Tanggal Permintaan: <?php echo $detail['created_date']; ?><br>
                    Pengiriman : <?php echo $is_tag; ?></p>
                    <?php if($detail['is_intan'] == 1) { ?>
                    <br>
                    <p>Tujuan Pengiriman : <b>Intan Pariwara</b></p>
                    <?php } ?>
                </div>                            
            </div>
            <div class="panel panel-primary">
                <div class="panel-heading">
                    <h3 class="panel-title">Daftar Buku</h3>
                </div>
                <?php echo form_open('', 'id="frmDetilPermintaanStok" class="form-horizontal" data-uri="' . BACKMIN_PATH . '/scmrequeststock/processRequestStockMasuk" role="form" autocomplete="off"'); ?>
                    <div class="panel-body panel-body-table">
                        <input type="hidden" name="id_request" id="id_request" value="<?php echo $detail['id_request']; ?>">
                        <input type="hidden" name="id_gudang_request" value="<?php echo $gudang['id_gudang']; ?>">
                        <input type="hidden" name="id_site_request" value="<?php echo $gudang['id_site']; ?>">
                        <input type="hidden" name="is_tag" value="<?php echo $detail['is_tag']; ?>">
                        <input type="hidden" name="is_intan" value="<?php echo $detail['is_intan']; ?>">
                        <input type="hidden" name="periode_request" value="<?php echo $detail['periode']; ?>">
                        <div class="table-responsive" id="product-area">
                            <?php if($listproducts) { ?>
                            <table class="table table-bordered table-striped">
                                <thead>
                                    <tr>
                                        <!--////////////////// JIKA BUKAN GUDANG UTAMA //////////////////-->
                                        <?php if ($detail['is_intan'] == 1) { ?>
                                            <th class="text-center" width="2%">No</th>
                                            <th class="text-center" width="58%">Judul Buku</th>
                                            <th class="text-center" width="10%">Kategori</th>
                                            <th class="text-center" width="10%">Kelas</th>
                                            <th class="text-center" width="10%">Permintaan Stok</th>
                                            <th class="text-center" width="15%">Status Stok</th>
                                        <?php } else { if ($detail['is_tag'] == 1) { ?>
                                            <th class="text-center" width="2%">No</th>
                                            <th class="text-center" width="58%">Judul Buku</th>
                                            <th class="text-center" width="10%">Kategori</th>
                                            <th class="text-center" width="10%">Kelas</th>
                                            <th class="text-center" width="10%">Permintaan Stok</th>
                                            <th class="text-center" width="15%">Opsi</th>
                                        <?php } else { ?>
                                            <th class="text-center" width="2%">No</th>
                                            <th class="text-center" width="58%">Judul Buku</th>
                                            <th class="text-center" width="15%">Kategori</th>
                                            <th class="text-center" width="15%">Kelas</th>
                                            <th class="text-center" width="15%">Permintaan Stok</th>
                                        <?php } } ?>
                                        <!-- TODO : Buat if intan jadi ada status stok -->
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

                                        <!--////////////////// JIKA BUKAN GUDANG UTAMA //////////////////-->
                                        <?php if ($detail['is_intan'] == 1) { ?>
                                            <td class="text-center">
                                                <?php if($row->stok_available >= $row->product_quantity) { ?>
                                                    <span class="fa fa-check text-success"></span>
                                                <?php } else { ?>
                                                    <input type="hidden" class="need_stock">
                                                    <span class="fa fa-remove text-danger"></span>
                                                <?php } ?>
                                            </td>
                                        <?php } else { if ($detail['is_tag'] == 1) { ?>
                                            <td class="text-center">
                                                <a href="<?php echo base_url(BACKMIN_PATH.'/scmrequeststock/popWarehouse/'.$detail["id_gudang"].'/'.$row->id_produk.'/'.$row->product_quantity.'/'.$detail["id_request"]); ?>" class="btn btn-default btn-rounded btn-condensed btn-sm" data-toggle="modal" data-target="#modal_large"><span class="fa fa-search"></span></a>
                                                <input type="hidden" class="need_stock" id="ps_<?php echo $detail['id_request'].'_'.$row->id_produk; ?>">
                                                <input type="hidden" name="id_gudang_to[]" id="gd_<?php echo $detail['id_request'].'_'.$row->id_produk; ?>" value="<?php echo $detail["id_gudang"]; ?>">
                                                <div id="sp_<?php echo $detail['id_request'].'_'.$row->id_produk; ?>"></div>
                                            </td>
                                        <?php } } ?>
                                        
                                    </tr>
                                    <?php 
                                            $i++;
                                            $tot_item += $row->product_quantity;
                                        }
                                    ?>
                                    <tr>
                                        <td colspan="4" class="text-right"><b>Total Jumlah</b></td>
                                        <td class="text-center"><b><?php echo $tot_item; ?></b></td>

                                        <!--////////////////// JIKA BUKAN GUDANG UTAMA //////////////////-->
                                        <?php if ($detail['is_tag'] == 1 || $detail['is_intan'] == 1) { ?>
                                            <td colspan="2"></td>
                                        <?php } ?>

                                    </tr>
                                </tbody>
                            </table>
                            <div class="modal" id="modal_large" tabindex="-1" role="dialog" aria-labelledby="largeModalHead" aria-hidden="true" data-keyboard="false" data-backdrop="static">
                                <div class="modal-dialog modal-lg">
                                    <div class="modal-content">
                                    </div>
                                </div>
                            </div>
                            <?php } ?>
                        </div>
                    </div>
                    <div class="panel-footer">
                        <?php if ($detail['is_tag'] == 2 && $detail['is_intan'] == 2) { ?>
                        <div class="form-group">
                            <div class="col-sm-3">
                                <label class="control-label" style="text-align: left;">Masukkan tanggal produksi :</label>
                                <div class="input-group date" id="datepicker_tgl_transaksi">
                                    <input type="text" id="tgl_transaksi" name="tgl_transaksi" class="form-control datepicker tgl_transaksi" value="" required/>
                                    <span class="input-group-addon add-on"><span class="glyphicon glyphicon-calendar"></span></span>
                                </div>
                            </div>
                        </div>
                        <?php } ?>
                        <div class="pull-left form-group">
                            <button class="btn btn-success" id="submitDetail">P r o s e s</button> &nbsp;
                            <button class="btn btn-danger" id="cancelDetail">B a t a l k a n</button>
                        </div>
                        <div class="pull-right">
                            <a href="<?php echo base_url(BACKMIN_PATH.'/scmrequeststock/indexRequestStockMasuk'); ?>" class="btn btn-primary"><i class="fa fa-arrow-left"></i> Kembali</a>
                        </div>
                    </div>
                <?php echo form_close(); ?>
            </div>
        </div>
    </div>
</div>
<!-- END PAGE CONTENT WRAPPER -->