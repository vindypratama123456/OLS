<!-- START BREADCRUMB -->
<ul class="breadcrumb">
    <li><a href="<?php echo base_url(BACKMIN_PATH); ?>">Dasbor</a></li>
    <li><a href="<?php echo base_url(BACKMIN_PATH.'/gudangrequeststockpartial'); ?>">Permintaan Stok</a></li>
    <li class="active">Detil Data</li>
</ul>
<!-- END BREADCRUMB -->
<!-- PAGE TITLE -->
<div class="page-title">                    
    <h2><span class="glyphicon glyphicon-bookmark"></span> Permintaan Stok</h2>
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
                    <h4><?php echo $gudang['nama_gudang']; ?></h4>
                    <p><?php echo $gudang['alamat_gudang']; ?></p>
                    <p>Tanggal Permintaan: <?php echo $detail['created_date']; ?></p>
                    <h6>Status</h6>
                    <p>Status Permintaan Stok Pengirim : <?php echo $status; ?>
                    <!-- <br>
                    Pengiriman : <?php echo $is_tag; ?> -->
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
                                        <th class="text-center" width="58%">Judul Buku</th>
                                        <th class="text-center" width="15%">Kategori</th>
                                        <th class="text-center" width="15%">Kelas</th>
                                        <th class="text-center" width="10%">Jumlah Pesan</th>
                                        <th class="text-center" width="10%">Sisa</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php 
                                        $i=1; 
                                        $tot_item = 0;
                                        $tot_sisa = 0;
                                        foreach($listproducts as $row) { 
                                    ?>
                                    <tr id="trow_<?php echo $i; ?>">
                                        <td class="text-center"><?php echo $i; ?></td>
                                        <td><?php echo '[ <b>'.$row->kode_buku.'</b> ] &nbsp;'.$row->product_name.' <br> (ISBN: '.$row->isbn.')'; ?></td>
                                        <td class="text-center"><?php echo $row->type; ?></td>
                                        <td class="text-center"><?php echo $row->kelas; ?></td>
                                        <td class="text-center"><?php echo $row->product_quantity; ?></td>
                                        <td class="text-center"><?php echo $row->sisa; ?></td>
                                    </tr>
                                    <?php 
                                            $i++;
                                            $tot_item += $row->product_quantity;
                                            $tot_sisa += $row->sisa;
                                        }
                                    ?>
                                    <tr>
                                        <td colspan="4" class="text-right"><b>Total Jumlah</b></td>
                                        <td class="text-center"><b><?php echo $tot_item; ?></b></td>
                                        <td class="text-center"><b><?php echo $tot_sisa; ?></b></td>
                                    </tr>
                                </tbody>
                            </table>
                            <?php } ?>
                        </div>               

                    
                        <?php if(in_array($adm_level, $this->backmin_scm_area)) { ?>
                        <a href="<?php echo base_url(ADMIN_PATH.'/gudangrequeststockpartial/addLog/'.$detail['id_request']); ?>" class="btn btn-success" data-toggle="modal" data-target="#myModal2"><i class="fa fa-plus-square"></i> Input Log Book</a>
                        <?php }  ?>

                        <h4>&nbsp;&nbsp;Log Book Request</h4>
                        <div class="table-responsive">
                            <table class="table table-bordered">
                                <thead>
                                    <th class="text-center" width="25%">Tanggal/Waktu</th>
                                    <th class="text-center" width="75%">Catatan Log</th>
                                </thead>
                                <tbody>
                                <?php if($listlog) { foreach ($listlog as $log) { ?>
                                    <tr>
                                        <td class="text-center"><?php echo $log->created_at; ?></td>
                                        <td><?php echo $log->notes; ?></td>
                                    </tr>
                                <?php } } ?>
                                </tbody>
                            </table>
                        </div>

                        <div class="form-group panel-footer">
                            <?php if($detail['status'] <= 5) {?>
                            <div class="pull-left">
                                <a data-toggle="modal" data-target="#myModal" href="<?php echo base_url(BACKMIN_PATH.'/gudangrequeststockpartial/close_request_stock/'.$detail['id_request']); ?>" class="btn btn-success">Tutup Permintaan</a>
                            </div>
                            <?php } ?>
                            <div class="pull-right">
                                <a href="<?php echo base_url(BACKMIN_PATH.'/gudangrequeststockpartial'); ?>" class="btn btn-primary"><i class="fa fa-arrow-left"></i> Kembali</a>
                            </div>
                        </div>
                    <?php echo form_close(); ?>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- END PAGE CONTENT WRAPPER -->

<div class="modal fade" id="myModal" role="dialog" aria-labelledby="myModalLabel" data-keyboard="false" data-backdrop="static">
    <div class="modal-dialog modal-lg">
      <div class="modal-content">
      </div>
    </div>
</div>