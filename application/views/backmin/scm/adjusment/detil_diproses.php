<!-- START BREADCRUMB -->
<ul class="breadcrumb">
    <li><a href="<?php echo base_url(BACKMIN_PATH); ?>">Dasbor</a></li>
    <li><a href="<?php echo base_url(BACKMIN_PATH.'/gudangadjusment/index_adjusment'); ?>">Adjusment</a></li>
    <li class="active">Detail</li>
</ul>
<!-- END BREADCRUMB -->
<!-- PAGE TITLE -->
<div class="page-title">                    
    <h2><span class="glyphicon glyphicon-bookmark"></span> Data Adjusment Detail</h2>
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
                    <h3 class="panel-title">Data Adjusment</h3>
                    <ul class="panel-controls">
                        <li><a href="#" class="panel-collapse"><span class="fa fa-angle-down"></span></a></li>
                    </ul>
                </div>
                <div class="panel-body">
                    <!-- <h4><?php echo $customer['school_name']; ?></h4> -->
                    <p>Keterangan &ensp;: <?php echo $adjusment[0]->catatan ?></p>
                    <p>Tanggal &ensp; &ensp; &ensp; : <?php echo $adjusment[0]->created_date ?></p>
                    <p>Gudang &ensp; &ensp; &ensp; : <?php echo $gudang[0]->nama_gudang ?></p>
                </div>                            
            </div>

            <div class="panel panel-primary">
                <div class="panel-heading">
                    <h3 class="panel-title">Daftar Buku</h3>
                </div>
                <div class="panel-body panel-body-table">
                    <?php echo form_open(BACKMIN_PATH . '/scmadjusment/detail_adjusment_post','id="frmDetailScmAdjusment" class="form-horizontal" data-uri="' . BACKMIN_PATH . '/scmadjusment/detail_adjusment_post" role="form" autocomplete="off"'); ?>
                        <input type="hidden" name="id_transaksi" value="<?php echo $adjusment[0]->id_transaksi; ?>">
                        <div class="table-responsive" id="product-area">
                            <?php if($adjusment_detail) { ?>
                            <table class="table table-bordered table-striped">
                                <thead>
                                    <tr>
                                        <th class="text-center" width="2%">No</th>
                                        <th class="text-center" width="15%">Kode Buku</th>
                                        <th class="text-center" width="50%">Judul Buku</th>
                                        <th class="text-center" width="18%">Kelas</th>
                                        <th class="text-center" width="15%">qty</th>
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
                                    </tr>
                                    <?php 
                                        $i++;
                                        $tot_item += $row->jumlah;
                                    }
                                    ?>
                                    <tr>
                                        <td colspan="4" class="text-center"><b>TOTAL</b></td>
                                        <td class="text-center"><?php echo $tot_item;?></td>
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
                        <div class="form-group panel-footer">
                            <div class="pull-left">
                                <!-- <button class="btn btn-success" id="submitDetail">P r o s e s</button> -->
                                <!-- <?php 
                                if ($listproducts) {
                                    if ($available_item == 0) {
                                ?>
                                <button class="btn btn-success" id="submitDetail">P r o s e s</button>
                                <?php } ?>
                                <p id="avaiableMessage">Silahkan melakukan request stok untuk memenuhi stok pesanan agar dapat memproses pesanan ini.</p>
                                <a href="#" class="btn btn-warning" style="margin-left:10px;" onclick="printPesanan();">Cetak Pesanan</a>
                                <?php } ?> -->
                            </div>
                            <div class="pull-right">
                                <a href="<?php echo base_url(BACKMIN_PATH.'/scmadjusment/index_adjusment_diproses'); ?>" class="btn btn-primary"><i class="fa fa-arrow-left"></i> Kembali</a>
                            </div>
                        </div>
                    <?php echo form_close(); ?>
                </div>
            </div>

        </div>
    </div>
</div>
<!-- END PAGE CONTENT WRAPPER -->
<!-- <script type="text/javascript">
    function printPesanan(){
        window.open('<?php echo base_url(BACKMIN_PATH."/gudangpesanan/cetakPesanan/".$detail['id_order']); ?>','page','toolbar=0,scrollbars=1,location=0,statusbar=0,menubar=0,resizable=0,width=750,height=600,left=50,top=50,titlebar=no');
    }
</script> -->