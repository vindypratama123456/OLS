<!-- START BREADCRUMB -->
<ul class="breadcrumb">
    <li><a href="<?php echo base_url(BACKMIN_PATH); ?>">Dasbor</a></li>
    <li><a href="<?php echo base_url(BACKMIN_PATH.'/scmpesanan/indexPesananMasuk'); ?>">Pesanan</a></li>
    <li><a href="<?php echo base_url(BACKMIN_PATH.'/scmpesanan/indexPesananMasuk'); ?>">Pesanan Masuk</a></li>
    <li class="active">Pesanan <?php echo $detail['reference']; ?></li>
</ul>
<!-- END BREADCRUMB -->
<!-- PAGE TITLE -->
<div class="page-title">                    
    <h2><span class="glyphicon glyphicon-bookmark"></span> Pemindahan Pesanan #<?php echo $detail['reference']; ?></h2>
</div>
<!-- END PAGE TITLE -->                
<!-- PAGE CONTENT WRAPPER -->
<div class="page-content-wrap">
    <div class="row">                        
        <div class="col-md-12">
            <div id="errorPlace"></div>
            <div class="panel panel-primary">
                <div class="panel-heading">
                    <h3 class="panel-title">Pelanggan</h3>
                    <ul class="panel-controls">
                        <li><a href="#" class="panel-collapse"><span class="fa fa-angle-down"></span></a></li>
                    </ul>
                </div>
                <div class="panel-body">
                    <h4><?php echo $customer['school_name']; ?></h4>
                    <p><?php echo $customer['alamat'].'<br />'.$customer['desa'].', '.$customer['kecamatan'].', '.$customer['kabupaten'].', '.$customer['provinsi'].' - '.$customer['kodepos']; ?></p>
                    <?php if($customer['phone']) echo '<p>Telpon: '.$customer['phone'].'</p>'; ?>
                    <h6>Sales</h6>
                    <?php if($sales && $sales->id_employee == 2) { ?>
                        <p>Email: <?php echo $sales->email; ?></p>
                        <?php } else { ?>
                        <p>Nama: <?php echo $sales->name ? $sales->name : '-'; ?><br />Email: <?php echo $sales->email ? $sales->email : '-'; ?><br/>Telpon/Hp: <?php echo $sales->telp ? $sales->telp : '-'; ?></p>
                    <?php } ?>
                    <h6>Korwil</h6>
                    <p>Nama: <?php echo $korwil->name ? $korwil->name : '-'; ?><br />Email: <?php echo $korwil->email ? $korwil->email : '-';?><br/>Telpon/Hp: <?php echo $korwil->telp ? $korwil->telp : '-'; ?></p>
                    <p>Tanggal Pesan: <?php echo $detail['date_add']; ?><br>
                    <?php
                        $jangka_waktu = 0;
                        if ($detail['jangka_waktu'] !== null)
                        {
                            $jangka_waktu = $detail['jangka_waktu'];
                        }
                    ?>
                    Target Kirim: <?php echo date('Y-m-d', strtotime($detail['tgl_konfirmasi'].'+'.$jangka_waktu.' days')); ?></p>
                </div>                            
            </div>
            <div class="panel panel-primary">
                <div class="panel-heading">
                    <h3 class="panel-title">Gudang Tujuan</h3>
                    <ul class="panel-controls">
                        <li><a href="#" class="panel-collapse"><span class="fa fa-angle-down"></span></a></li>
                    </ul>
                </div>
                <div class="panel-body">
                    <h4><?php echo $forward_gudang->nama_gudang; ?></h4>
                    <p><?php echo $forward_gudang->alamat_gudang; ?></p>
                </div>                            
            </div>
            <div class="panel panel-primary">
                <div class="panel-heading">
                    <h3 class="panel-title">Buku Pesanan</h3>
                </div>
                <?php echo form_open('', 'id="frmDetilPesananForward" class="form-horizontal" data-uri="' . BACKMIN_PATH . '/scmpesanan/processPesananForward" role="form" autocomplete="off"'); ?>
                    <div class="panel-body panel-body-table">
                        <input type="hidden" name="id_gudang_forward" value="<?php echo $forward_gudang->id_gudang; ?>">
                        <input type="hidden" name="nama_gudang_forward" value="<?php echo $forward_gudang->nama_gudang; ?>">
                        <input type="hidden" name="id_customer" value="<?php echo $customer['id_customer']; ?>">
                        <input type="hidden" name="id_order" value="<?php echo $detail['id_order']; ?>">
                        <input type="hidden" name="reference" value="<?php echo $detail['reference']; ?>">

                        <div class="table-responsive" id="product-area">
                            <?php if($listproducts) { ?>
                            <table class="table table-bordered table-striped">
                                <thead>
                                    <tr>
                                        <th class="text-center" width="2%">No</th>
                                        <th class="text-center" width="58%">Judul Buku</th>
                                        <th class="text-center" width="10%">Kelas</th>
                                        <th class="text-center" width="10%">Jumlah Pesan</th>
                                        <th class="text-center" width="10%">Stok</th>
                                        <th class="text-center" width="15%">Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php 
                                    $i=1; 
                                    $tot_item = 0;
                                    $tot_price = 0;
                                    $is_waiting = 0;
                                    foreach($listproducts as $row) {
                                    ?>
                                    <input type="hidden" name="id_produk[]" value="<?php echo $row->product_id; ?>">
                                    <input type="hidden" name="harga[]" value="<?php echo $row->total_price; ?>">
                                    <input type="hidden" name="berat[]" value="<?php echo $row->weight; ?>">
                                    <input type="hidden" name="jumlah[]" value="<?php echo $row->product_quantity; ?>">

                                    <tr id="trow_<?php echo $i; ?>">
                                        <td class="text-center"><?php echo $i; ?></td>
                                        <td><?php echo $row->product_name.' [<b>'.$row->kode_buku.'</b>]<br />(ISBN: '.$row->isbn.')'; ?></td>
                                        <td class="text-center"><?php echo $row->kelas; ?></td>
                                        <td class="text-center"><?php echo $row->product_quantity; ?></td>
                                        <td class="text-center"><?php echo $row->stok_available; ?></td>
                                        <td class="text-center"><b>
                                            <?php
                                            if ($row->product_quantity > $row->stok_available)
                                            {
                                                $is_waiting = 1;
                                            ?>
                                                <span class="fa fa-remove text-danger"></span>
                                            <?php
                                            }
                                            else
                                            {
                                            ?>
                                                <span class="fa fa-check text-success"></span>
                                            <?php
                                            }
                                            ?>
                                            </b>
                                        </td>
                                    </tr>
                                    <?php 
                                        $i++;
                                        $tot_item += $row->product_quantity;
                                        $tot_price += $row->total_price;
                                    }
                                    ?>
                                    <tr>
                                        <td colspan="3" class="text-right"><b>Total Jumlah</b></td>
                                        <td class="text-center"><b><?php echo $tot_item; ?></b></td>
                                        <td colspan="2"></td>
                                    </tr>
                                    <tr>
                                        <td colspan="3" class="text-right"><strong>Total Harga</strong></td>
                                        <td class="text-left" colspan="3">
                                            <strong><?php echo toRupiah($tot_price); ?></strong>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="text-right" colspan="6">
                                            <i>Terbilang: <b><?php echo terbilang($tot_price); ?></b></i>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                            <?php } ?>
                        </div>
                    </div>
                    <div class="form-group panel-footer">
                        <?php if($is_waiting > 0) { ?>
                            <p><i class="glyphicon glyphicon-info-sign"></i>&nbsp;&nbsp;<b>Kekurangan barang yang ada pada gudang yang dituju menjadi tanggung jawab gudang tersebut untuk melakukan pemenuhan stok pesanan.</b></p>
                        <?php } ?>
                        <div class="pull-left">
                            <button class="btn btn-success" id="submitDetail">L a n j u t k a n</button>
                        </div>  
                        <div class="pull-right">
                            <a href="<?php echo base_url(BACKMIN_PATH.'/scmpesanan/detailPesananMasuk/'.$detail['id_order']); ?>" class="btn btn-primary"><i class="fa fa-arrow-left"></i> Kembali</a>
                        </div>
                    </div>
                <?php echo form_close(); ?>
            </div>
        </div>
    </div>
</div>
<!-- END PAGE CONTENT WRAPPER -->