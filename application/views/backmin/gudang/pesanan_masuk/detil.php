<!-- START BREADCRUMB -->
<ul class="breadcrumb">
    <li><a href="<?php echo base_url(BACKMIN_PATH); ?>">Dasbor</a></li>
    <li><a href="<?php echo base_url(BACKMIN_PATH.'/gudangpesanan/indexPesananMasuk'); ?>">Pesanan</a></li>
    <li><a href="<?php echo base_url(BACKMIN_PATH.'/gudangpesanan/indexPesananMasuk'); ?>">Pesanan Masuk</a></li>
    <li class="active">Pesanan <?php echo $detail['reference']; ?></li>
</ul>
<!-- END BREADCRUMB -->
<!-- PAGE TITLE -->
<div class="page-title">                    
    <h2><span class="glyphicon glyphicon-bookmark"></span> Pesanan #<?php echo $detail['reference']; ?></h2>
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
                    <h3 class="panel-title">Pelanggan</h3>
                    <ul class="panel-controls">
                        <li><a href="#" class="panel-collapse"><span class="fa fa-angle-down"></span></a></li>
                    </ul>
                </div>
                <div class="panel-body">
                    <h4><?php echo $customer['school_name']; ?></h4>
                    <p><?php echo $customer['alamat'].'<br />'.$customer['desa'].', '.$customer['kecamatan'].', '.$customer['kabupaten'].', '.$customer['provinsi'].' - '.$customer['kodepos']; ?></p>
                    <?php if($customer['phone']) echo '<p>Telpon: '.$customer['phone'].'</p>'; ?>
                    <p>Kepala Sekolah: <?php echo $customer['name']; ?><br />No. Telpon/Hp: <?php echo $customer['phone_kepsek']; ?></p>
                    <h6>Operator</h6>
                    <p>Nama: <?php echo $customer['operator']; ?><br />Email: <?php echo $customer['email_operator']; ?><br />Telpon/Hp: <?php echo $customer['hp_operator']; ?></p>
                    <h6>Sales</h6>
                    <?php if($sales->id_employee == 2) { ?>
                    <p>Email: <?php echo $sales->email; ?></p>
                    <?php } else { ?>
                    <p>Nama: <?php echo $sales->name; ?><br />Email: <?php echo $sales->email; ?><br />Telpon/Hp: <?php echo $sales->telp; ?></p>
                    <?php } ?>
                    <h6>Korwil</h6>
                    <p>Nama: <?php echo $korwil->name; ?><br />Email: <?php echo $korwil->email; ?><br />Telpon/Hp: <?php echo $korwil->telp; ?></p>
                    <p>Tanggal Pesan: <?php echo $detail['date_add']; ?><br>
                    <?php
                        $jangka_waktu = 0;
                        if ($detail['jangka_waktu'] !== null)
                        {
                            $jangka_waktu = $detail['jangka_waktu'];
                        }
                    ?>
                    Target Kirim: <?php echo date('Y-m-d', strtotime($detail['tgl_konfirmasi'].'+'.$jangka_waktu.' days')); ?></p>

                    <p>
                        Status Pesanan: 
                        <h3>
                        <b>
                        <?php 
                            if($detail['kirim_parsial_accept_by_id'] != null || $detail['kirim_parsial_accept_by_id'] != "")
                            {
                                if(isset($status_parsial)){
                                    if(count($status_parsial) == 0)
                                    {
                                        echo $status_transaksi;
                                    }
                                    else
                                    {
                                        echo $status_parsial['status_transaksi'];   
                                    }
                                }
                                else
                                {
                                    echo $status_transaksi;   
                                }
                            }
                            else
                            {
                                echo $status_transaksi;
                            } 
                        ?>
                        </b>
                        </h3>
                    </p>
                </div>                            
            </div>

            <div class="panel panel-primary">
                <div class="panel-heading">
                    <h3 class="panel-title">Buku Pesanan (<?php echo $listproducts[0]->type_alias; ?>)</h3>
                    <h3 class="panel-title pull-right">Rekomendasi: <b><?php echo $this->adm_nama_gudang; ?></b></h3>
                </div>
                <div class="panel-body panel-body-table">
                    <?php echo form_open('', 'id="frmDetilPesananMasuk" class="form-horizontal" data-uri="' . BACKMIN_PATH . '/gudangpesanan/processPesananMasuk" data-uri_parsial="' . BACKMIN_PATH . '/gudangpesanan/processPesananMasukParsial" data-uri_parsial_request="' . BACKMIN_PATH . '/gudangpesanan/request_parsial" role="form" autocomplete="off"'); ?>
                        <input type="hidden" name="id_order" value="<?php echo $detail['id_order']; ?>">
                        <input type="hidden" name="kode_pesanan" value="<?php echo $detail['reference']; ?>">
                        <input type="hidden" name="id_customer" value="<?php echo $customer['id_customer']; ?>">
                        <input type="hidden" name="periode_order" value="<?php echo $detail['periode']; ?>">
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
                                    $tot_weight = 0;
                                    $available_item = 0;
                                    $item_belum_proses = 0;
                                    $check_ready = 0;
                                    foreach($listproducts as $row) { 
                                    ?>
                                    <tr id="trow_<?php echo $i; ?>">
                                        <td class="text-center"><?php echo $i; ?></td>
                                        <td class="text-left"><?php echo $row->product_name.' [<b>'.$row->kode_buku.'</b>]<br />(ISBN: '.$row->isbn.')'; ?></td>
                                        <td class="text-center"><?php echo $row->kelas; ?></td>
                                        <td class="text-center"><?php echo $row->product_quantity; ?></td>
                                        <td class="text-center"><?php echo $row->stok_available; ?></td>
                                        <td class="text-center">
                                            <?php 
                                                if($row->is_process == 0 ) { 
                                                    if($row->stok_available >= $row->product_quantity) { 
                                            ?>
                                                        <span class="fa fa-check text-success"></span>
                                            <?php 
                                                        $item_belum_proses++;
                                                    }
                                                    else
                                                    { 
                                            ?>
                                                        <span class="fa fa-remove text-danger"></span>
                                            <?php   
                                                        $available_item++; 
                                                    } 
                                                } 
                                                else 
                                                { 
                                            ?>
                                                    <span class="fa fa-check text-success">sudah di proses</span>
                                            <?php 
                                                } 
                                            ?>
                                        </td>

                                        <input type="hidden" name="id_product[]" value="<?php echo $row->product_id; ?>">
                                        <input type="hidden" name="price[]" value="<?php echo $row->total_price; ?>">
                                        <input type="hidden" name="weight[]" value="<?php echo $row->weight; ?>">
                                        <input type="hidden" name="product_quantity[]" value="<?php echo $row->product_quantity; ?>">
                                        <input type="hidden" name="check_availability[]" value="<?php if($row->stok_available >= $row->product_quantity){echo '1';}else{echo '0';} ?>">
                                        <input type="hidden" class="need_stock" id="need_stock" value="<?php echo $available_item ?>">
                                    </tr>
                                    <?php 
                                        $i++;
                                        $tot_item += $row->product_quantity;
                                        $tot_price += $row->total_price;
                                        $tot_weight += ($row->weight * $row->product_quantity);
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
                                        <td colspan="3" class="text-right"><strong>Total Berat</strong></td>
                                        <td class="text-left" colspan="3">
                                            <strong><?php echo number_format(($tot_weight), 2, ',', '.'); ?> Kg</strong>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="text-right" colspan="6">
                                            <i>Terbilang: <b><?php echo terbilang($tot_price); ?></b></i>
                                        </td>
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
                                <?php 
                                if ($listproducts) 
                                {
                                    if($detail['kirim_parsial_request_by_id'] == null) 
                                    {
                                        if ($available_item == 0) 
                                        {
                                ?>
                                            <button class="btn btn-success" id="submitDetail">P r o s e s</button>
                                <?php 
                                        }
                                        elseif($item_belum_proses > 0)
                                        {
                                ?>
                                            <button class="btn btn-success" id="submitRequestParsial">Request Parsial</button>
                                <?php
                                        }
                                    } 
                                    else 
                                    { 
                                        if($detail['kirim_parsial_accept_by_id'] != null) 
                                        {
                                            if($item_belum_proses > 0)
                                            {
                                ?>          
                                                <button class="btn btn-success" id="submitDetailParsial">Proses Parsial</button>
                                <?php   
                                            }
                                        } 
                                    }
                                ?>
                                <!-- <p id="avaiableMessage">Silahkan melakukan request stok untuk memenuhi stok pesanan agar dapat memproses pesanan ini.</p> -->
                                <a href="#" class="btn btn-warning" style="margin-left:10px;" onclick="printPesanan();">Cetak Pesanan</a>
                                <?php } ?>
                            </div>
                            <div class="pull-right">
                                <a href="<?php echo base_url(BACKMIN_PATH.'/gudangpesanan/indexPesananMasuk'); ?>" class="btn btn-primary"><i class="fa fa-arrow-left"></i> Kembali</a>
                            </div>
                        </div>
                    <?php echo form_close(); ?>
                </div>
            </div>

        </div>
    </div>
</div>
<!-- END PAGE CONTENT WRAPPER -->
<script type="text/javascript">
    function printPesanan(){
        window.open('<?php echo base_url(BACKMIN_PATH."/gudangpesanan/cetakPesanan/".$detail['id_order']); ?>','page','toolbar=0,scrollbars=1,location=0,statusbar=0,menubar=0,resizable=0,width=750,height=600,left=50,top=50,titlebar=no');
    }
</script>