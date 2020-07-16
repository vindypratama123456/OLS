<!-- START BREADCRUMB -->
<ul class="breadcrumb">
    <li><a href="<?php echo base_url(BACKMIN_PATH); ?>">Dasbor</a></li>
    <li><a href="<?php echo base_url(BACKMIN_PATH.'/gudangadjusment/index_adjusment'); ?>">Adjustment</a></li>
    <li class="active">Detail</li>
</ul>
<!-- END BREADCRUMB -->
<!-- PAGE TITLE -->
<div class="page-title">                    
    <h2><span class="glyphicon glyphicon-bookmark"></span> Data Adjustment Detail</h2>
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
                    <h3 class="panel-title">Data Adjustment</h3>
                    <ul class="panel-controls">
                        <li><a href="#" class="panel-collapse"><span class="fa fa-angle-down"></span></a></li>
                    </ul>
                </div>
                <div class="panel-body">
                    <!-- <h4><?php echo $customer['school_name']; ?></h4> -->
                    <p>Tanggal : <?php echo $adjusment[0]->created_date ?></p>
                    <p>Keterangan : <?php echo $adjusment[0]->catatan ?></p>
                </div>                            
            </div>

            <div class="panel panel-primary">
                <div class="panel-heading">
                    <h3 class="panel-title">Daftar Buku</h3>
                </div>
                <div class="panel-body panel-body-table">
                    <?php echo form_open('', 'id="frmDetilPesananMasuk" class="form-horizontal" data-uri="' . BACKMIN_PATH . '/gudangpesanan/processPesananMasuk" role="form" autocomplete="off"'); ?>
                        <!-- <input type="hidden" name="id_order" value="<?php echo $detail['id_order']; ?>">
                        <input type="hidden" name="kode_pesanan" value="<?php echo $detail['reference']; ?>">
                        <input type="hidden" name="id_customer" value="<?php echo $customer['id_customer']; ?>">
                        <input type="hidden" name="periode_order" value="<?php echo $detail['periode']; ?>"> -->
                        <div class="table-responsive" id="product-area">
                            <?php if($adjusment_detail) { ?>
                            <table class="table table-bordered table-striped">
                                <thead>
                                    <tr>
                                        <th class="text-center" width="2%">No</th>
                                        <th class="text-center" width="15%">Kode Buku</th>
                                        <th class="text-center" width="68%">Judul Buku</th>
                                        <th class="text-center" width="68%">Kelas</th>
                                        <th class="text-center" width="15%">qty</th>
                                        <?php if ($adjusment[0]->status_transaksi == 1 ) { ?>
                                        <th class="text-center">Opsi</th>
                                        <?php } ?>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php 
                                    $i=1; 
                                    $tot_item = 0;
                                    $tot_price = 0;
                                    $tot_weight = 0;
                                    $available_item = 0;
                                    foreach($adjusment_detail as $row) { 
                                    ?>
                                    <tr id="trow_<?php echo $i; ?>">
                                        <td class="text-center"><?php echo $i; ?></td>
                                        <td class="text-center"><?php echo $row->kode_buku; ?></td>
                                        <td class="text-left"><?php echo $row->name; ?></td>
                                        <td class="text-left"><?php echo $row->kelas; ?></td>
                                        <td <?php if($row->jumlah < 0) { ?>style="color: red;" <?php } ?> class="text-center"><?php echo $row->jumlah; ?></td>
                                        <?php if ($adjusment[0]->status_transaksi == 1 ) { ?>
                                        <th class="text-center">
                                        <a data-toggle="modal" href="<?php echo base_url(BACKMIN_PATH.'/Gudangadjusment/edit/'.$row->id.'/'.$row->jumlah); ?>" data-target="#myModal">Ubah</a>
                                        </th>
                                        <?php } ?>
                                    </tr>
                                    <?php 
                                        $i++;
                                    }
                                    ?>
                                    
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
                        <div class="form-group panel-footer">
                            <?php if ($adjusment[0]->status_transaksi == 1) { ?>
                                <div class="pull-left">
                                    <!-- <button class="btn btn-success" id="submitDetail">P r o s e s</button> -->
                                    <a href="<?php echo base_url(BACKMIN_PATH.'/gudangadjusment/add_books/'.$adjusment[0]->id_transaksi); ?>" class="btn btn-primary" data-toggle="modal" data-target="#myModal"><i class="fa fa-plus-square"></i> Tambah Buku</a>
                                </div>
                            <?php } ?>
                            <div class="pull-right">
                                <a href="<?php echo base_url(BACKMIN_PATH.'/gudangadjusment/index_adjusment'); ?>" class="btn btn-primary"><i class="fa fa-arrow-left"></i> Kembali</a>
                            </div>
                        </div>
                    <?php echo form_close(); ?>
                </div>
            </div>

        </div>
    </div>
</div>
<!-- END PAGE CONTENT WRAPPER -->

<?php if ($adjusment['status_transaksi'] = 1) { ?>
<div class="modal fade" id="myModal" role="dialog" aria-labelledby="myModalLabel" data-keyboard="false" data-backdrop="static">
    <div class="modal-dialog modal-lg">
      <div class="modal-content">
      </div>
    </div>
</div>
<?php } ?>