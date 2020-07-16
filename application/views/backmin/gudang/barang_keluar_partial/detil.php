<!-- START BREADCRUMB -->
<ul class="breadcrumb">
    <li><a href="<?php echo base_url(BACKMIN_PATH); ?>">Dasbor</a></li>
    <li><a href="<?php echo base_url(BACKMIN_PATH.'/gudangpermintaanpartial/indexBarangKeluar'); ?>">Permintaan</a></li>
    <li><a href="<?php echo base_url(BACKMIN_PATH.'/gudangpermintaanpartial/indexBarangKeluar'); ?>">Barang Keluar</a></li>
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
                    <?php echo form_open('', 'id="frmDetilBarangKeluar" class="form-horizontal" data-uri="' . BACKMIN_PATH . '/gudangpermintaanpartial/prosesBarangKeluar" role="form" autocomplete="off"'); ?>
                        <input type="hidden" name="id_transaksi" value="<?php echo $detail['id_transaksi']; ?>">
                        <input type="hidden" name="gudang_asal" value="<?php echo $detail['asal']; ?>">
                        <input type="hidden" name="id_request" value="<?php echo $detail['id_request']; ?>">
                        <div class="table-responsive" id="product-area">
                            <?php if($listproducts) { ?>
                            <table class="table table-bordered table-striped">
                                <thead>
                                    <tr>
                                        <th class="text-center" width="5%">No</th>
                                        <th class="text-center" width="45%">Judul Buku</th>
                                        <th class="text-center" width="20%">Kelas</th>
                                        <th class="text-center" width="5%">Jumlah</th>
                                        <th class="text-center" width="5%">Koli</th>
                                        <th class="text-center" width="5%">Total Koli</th>
                                        <th class="text-center" width="5%">Buntut</th>
                                        <th class="text-center" width="5%">Berat</th>
                                        <th class="text-center" width="5%">Total Berat</th>
                                        <?php 
                                            if($detail['status_transaksi']==1)
                                            {
                                        ?>
                                        <th class="text-center" width="5%">Action</th>
                                        <?php 
                                            }
                                        ?>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php 
                                        $i=1; 
                                        $tot_item = 0;
                                        $tot_total_koli = 0;
                                        $tot_sisa_koli = 0;
                                        $tot_total_berat = 0;
                                        foreach($listproducts as $row) { 
                                    ?>
                                    <tr id="trow_<?php echo $i; ?>">
                                        <td class="text-center"><?php echo $i; ?></td>
                                        <td><?php echo $row->judul_buku.' [<b>'.$row->kode_buku.'</b>]<br />(ISBN: '.$row->isbn.')'; ?></td>
                                        <td class="text-center"><?php echo $row->kelas; ?></td>
                                        <td class="text-center"><?php echo $row->jumlah; ?></td>
                                        <td class="text-center"><?php echo $row->koli; ?></td>
                                        <td class="text-center"><?php echo $row->total_koli; ?></td>
                                        <td class="text-center"><?php echo $row->sisa_koli; ?></td>
                                        <td class="text-center"><?php echo $row->berat; ?></td>
                                        <td class="text-center"><?php echo $row->total_berat; ?></td>
                                        <?php 
                                            if($detail['status_transaksi']==1)
                                            {
                                        ?>
                                        <td class="text-center"><a data-toggle="modal" href="<?php echo base_url(BACKMIN_PATH.'/gudangpermintaanpartial/detailbarangkeluaredit/'. $row->id_transaksi_detail . "/" . $row->jumlah); ?>" data-target="#myModal">Ubah</a></td>
                                        <?php
                                            }
                                        ?>
                                    </tr>
                                    <?php 
                                            $i++;
                                            $tot_item += $row->jumlah;
                                            $tot_total_koli += $row->total_koli;
                                            $tot_sisa_koli += $row->sisa_koli;
                                            $tot_total_berat += $row->total_berat;
                                        }
                                    ?>
                                    <tr>
                                        <td colspan="3" class="text-right"><b>Total Jumlah</b></td>
                                        <td class="text-center"><b><?php echo $tot_item; ?></b></td>
                                        <td class="text-center"><b><?php echo $tot_total_koli; ?></b></td>
                                        <td> </td>
                                        <td class="text-center"><b><?php echo $tot_sisa_koli; ?></b></td>
                                        <td class="text-center"><b><?php echo $tot_total_berat; ?></b></td>
                                        <td> </td>
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
                            <div class="pull-left">
                                <a href="#" class="btn btn-warning" style="margin-left:10px;" onclick="return printDetailBarangKeluar();">Cetak</a>
                            </div>
                            <div class="pull-right">
                                <a href="<?php echo base_url(BACKMIN_PATH.'/gudangpermintaanpartial/indexBarangKeluar'); ?>" class="btn btn-primary"><i class="fa fa-arrow-left"></i> Kembali</a>
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

<script type="text/javascript">
    function printDetailBarangKeluar() {
        window.open('<?php echo base_url(
            BACKMIN_PATH."/gudangpermintaanpartial/detail_barang_keluar_print/".$detail['id_transaksi']
        ); ?>', 'page', 'toolbar=0,scrollbars=1,location=0,statusbar=0,menubar=0,resizable=0,width=750,height=600,left=50,top=50,titlebar=no');
    }
</script>