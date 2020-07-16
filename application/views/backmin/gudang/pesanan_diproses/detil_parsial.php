<!-- START BREADCRUMB -->
<ul class="breadcrumb">
    <li><a href="<?php echo base_url(BACKMIN_PATH); ?>">Dasbor</a></li>
    <li><a href="<?php echo base_url(BACKMIN_PATH.'/gudangpesanan/indexPesananMasuk'); ?>">Pesanan</a></li>
    <li><a href="<?php echo base_url(BACKMIN_PATH.'/gudangpesanan/indexPesananDiproses'); ?>">Pesanan Diproses</a></li>
    <li class="active">Pesanan <?php echo $detail['reference']; ?></li>
</ul>
<!-- END BREADCRUMB -->
<!-- PAGE TITLE -->
<div class="page-title">                    
    <h2><span class="glyphicon glyphicon-bookmark"></span> Pesanan #<?php echo $detail['reference']; ?> <?php if($kode_spk != null || $kode_spk != "") { echo '#'.$kode_spk; } ?></h2>
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
                    <h6>Status</h6>
                    <!-- <p>Status Pesanan: <b><?php echo $status_transaksi; ?></b></p> -->


                    <p>
                        Status Pesanan: 
                        <b>
                        <?php 
                            // if($detail['kirim_parsial_accept_by_id'] != null || $detail['kirim_parsial_accept_by_id'] != "")
                            // {
                            //     if(count($status_parsial) == 0)
                            //     {
                            //         echo $status_transaksi;
                            //     }
                            //     else
                            //     {
                            //         echo $status_parsial['status_transaksi'];   
                            //     }
                            // }
                            // else
                            // {
                            //     echo $status_transaksi;
                            // } 
                            echo $status_transaksi;
                        ?>
                        </b>
                    </p>

                    <?php if ($transaksi->status_transaksi == 6) { ?>
                    <h6>File BAST</h6>
                    <div class="gallery" id="links">
                        <a class="gallery-item" href="<?php echo base_url(); ?>uploads/bast/<?php echo $transaksi->file_bast ?>" title="Kode Pesanan <?php echo $detail['reference'] ?>" data-gallery>
                            <div class="image">
                                <img src="<?php echo base_url(); ?>uploads/bast/<?php echo $transaksi->file_bast ?>" alt="Kode Pesanan <?php echo $detail['reference'] ?>"/>
                            </div>
                        </a>
                    </div>
                    <div id="blueimp-gallery" class="blueimp-gallery blueimp-gallery-controls">
                        <div class="slides"></div>
                        <h3 class="title"></h3>
                        <a class="close">×</a>
                    </div>
                    <?php } ?>
                </div>                            
            </div>
            <?php if($transaksi->is_forward > 0) { ?>
            <div class="panel panel-primary">
                <div class="panel-heading">
                    <h3 class="panel-title">Pindah Gudang</h3>
                    <ul class="panel-controls">
                        <li><a href="#" class="panel-collapse"><span class="fa fa-angle-down"></span></a></li>
                    </ul>
                </div>
                <div class="panel-body">
                    <h4><?php echo $gudang_forward->nama_gudang; ?></h4>
                    <p><?php echo $gudang_forward->alamat_gudang; ?></p>
                    <p>Tanggal Pindah: <?php echo $date_forward; ?></p>
                </div>                            
            </div>
            <?php } ?>

            <?php echo form_open('', 'id="frmDetilPesananMasuk" class="form-horizontal" data-uri="' . BACKMIN_PATH . '/gudangpesanan/processPesananMasuk" role="form" autocomplete="off"'); ?>
                <input type="hidden" name="id_transaksi" value="<?php echo $transaksi->id_transaksi; ?>">
                <input type="hidden" name="id_order" value="<?php echo $detail['id_order']; ?>">
                <input type="hidden" name="periode_order" value="<?php echo $detail['periode']; ?>">
                <div class="panel panel-primary">
                    <div class="panel-heading">
                        <h3 class="panel-title">Buku Pesanan (<?php echo $listproducts[0]->type_alias;  ?>)</h3>
                        <h3 class="panel-title pull-right">Pengirim: <b><?php echo $recommended_warehouse->nama_gudang; ?></b></h3>
                    </div>
                    <div class="panel-body panel-body-table">
                        <div class="table-responsive" id="product-area">
                            <?php if($listproducts) { ?>
                            <table class="table table-bordered table-striped">
                                <thead>
                                    <tr>
                                        <?php if($transaksi->status_transaksi != 6) { ?>
                                            <th class="text-center" width="2%">No</th>
                                            <th class="text-center" width="58%">Judul Buku</th>
                                            <th class="text-center" width="15%">Kelas</th>
                                            <th class="text-center" width="15%">Jumlah Pesan</th>
                                            <th class="text-center" width="15%">Status</th>
                                        <?php } else { ?>
                                            <th class="text-center" width="2%">No</th>
                                            <th class="text-center" width="63%">Judul Buku</th>
                                            <th class="text-center" width="20%">Kelas</th>
                                            <th class="text-center" width="20%">Jumlah Pesan</th>
                                        <?php } ?>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php 
                                        $i=1; 
                                        $tot_item = 0;
                                        $tot_price = 0;
                                        foreach($listproducts as $row) { 
                                    ?>
                                    <tr id="trow_<?php echo $i; ?>">
                                        <input type="hidden" class="id_produk" name="id_produk[]" id="id_produk[]" value="<?php echo $row->product_id; ?>">
                                        <input type="hidden" class="jml_produk" name="jml_produk[]" id="jml_produk[]" value="<?php echo $row->product_quantity; ?>">
                                        <input type="hidden" class="harga_produk" name="harga_produk[]" id="harga_produk[]" value="<?php echo $row->unit_price; ?>">

                                        <td class="text-center"><?php echo $i; ?></td>
                                        <td><?php echo $row->product_name.' [<b>'.$row->kode_buku.'</b>]<br />(ISBN: '.$row->isbn.')'; ?></td>
                                        <td class="text-center"><?php echo $row->kelas; ?></td>
                                        <td class="text-center"><?php echo $row->product_quantity; ?></td>
                                        <?php if($transaksi->status_transaksi != 6) { ?>
                                            <td class="text-center">
                                                <b><?php echo ($row->product_quantity > $row->stok_booking) ? 'Menunggu TAG' : 'Sudah Siap'; ?></b>
                                            </td>
                                        <?php } ?>
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
                                        <?php if($transaksi->status_transaksi != 6) { ?>
                                            <td colspan="2"></td>
                                        <?php } ?>
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
                </div>

                <?php if($list_gudang_tag) { ?>
                <div class="panel panel-primary">
                    <div class="panel-heading">
                        <h3 class="panel-title">List Gudang TAG</h3>
                    </div>
                    <div class="panel-body panel-body-table">
                        <div class="table-responsive" id="product-area">
                            <table class="table table-bordered table-striped">
                                <thead>
                                    <tr>
                                        <th class="text-center" width="2%">No</th>
                                        <th class="text-center" width="58%">Judul Buku</th>
                                        <th class="text-center" width="10%">Kelas</th>
                                        <th class="text-center" width="10%">Jumlah Pesan</th>
                                        <th class="text-center" width="15%">Gudang TAG</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php 
                                        $i=1; 
                                        foreach($list_gudang_tag as $row) { 
                                    ?>
                                    <tr id="trow_<?php echo $i; ?>">
                                        <td class="text-center"><?php echo $i; ?></td>
                                        <td><?php echo $row->judul_buku.' [<b>'.$row->kode_buku.'</b>]<br />(ISBN: '.$row->isbn.')'; ?></td>
                                        <td class="text-center"><?php echo $row->kelas; ?></td>
                                        <td class="text-center"><?php echo $row->jumlah; ?></td>
                                        <td class="text-center"><?php echo $row->nama_gudang; ?></td>
                                    </tr>
                                    <?php $i++; } ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                <?php } ?>
            
                <div class="panel panel-primary">
                    <div class="panel-heading">
                        <h3 class="panel-title">History Transaksi</h3>
                    </div>
                    <div class="panel-body panel-body-table">
                        <div class="table-responsive" id="">
                            <table class="table table-bordered table-striped">
                                <thead>
                                    <tr>
                                        <th class="text-center" width="5%">No</th>
                                        <th class="text-center" width="15%">Status</th>
                                        <th class="text-center" width="20%">Pengguna</th>
                                        <th class="text-center" width="20%">Waktu</th>
                                        <th class="text-center" width="40%">Keterangan</th>
                                    </tr>
                                </thead>
                                <tbody>
                                <?php 
                                $no = 1;
                                foreach ($transaksi_history as $rows) { 
                                ?>
                                    <tr>
                                        <td class="text-center"><?php echo $no ?></td>
                                        <td class="text-center"><span class="btn btn-<?php echo $rows['state_label'] ?>"><?php echo $rows['state'] ?></span></td>
                                        <td><?php echo $rows['employee'] ?></td>
                                        <td class="text-center"><?php echo date("d-m-Y H:i:s", strtotime($rows['date_history'])); ?></td>
                                        <td><?php echo $rows['notes'] ?></td>
                                    </tr>
                                <?php $no++; } ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="form-group panel-footer">
                            <?php
                            $status_transaksi = $transaksi->status_transaksi;
                            $status = "";
                            // if($detail['kirim_parsial_request_by_id'] == null || $detail['kirim_parsial_request_by_id'] == '')
                            // {
                            switch ($status_transaksi) {
                                case 2:
                                    $status = '<a href="../../gudangpengiriman/add" class="btn btn-success">Buat Surat Jalan</a>';
                                    break;
                                case 5:
                                    $status = '<a href="#" id="btn_terima_barang" class="btn btn-success" data-toggle="modal" data-target="#terimaBarang">Barang Diterima</a>';
                                    break;
                                default:
                                    $status = '';
                                    break;
                            // }
                            }
                            ?>
                            <div class="pull-left">
                                <?php 
                                    echo $status; 
                                    if($status_transaksi<5) {
                                ?>
                                <a href="#" class="btn btn-warning" style="margin-left:10px;" onclick="return printPesanan();">Cetak PDF</a>
                                <a href="#" class="btn btn-info" style="margin-left:10px;" onclick="return printExcelPesanan();">Cetak Excel</a>
                                <?php } ?>

                                <!-- <?php 
                                    if($detail['current_state'] > 6)
                                    {
                                ?>
                                        <a href="#" class="btn btn-warning" style="margin-left:10px;" onclick="return printBAST();">Cetak BAST</a>
                                <?php
                                    }
                                ?> -->
                            </div>
                            <div class="pull-right">
                                <a href="<?php echo base_url(BACKMIN_PATH.'/gudangpesanan/indexPesananDiproses'); ?>" class="btn btn-primary"><i class="fa fa-arrow-left"></i> Kembali</a>
                            </div>
                        </div>
                </div>

            <?php echo form_close(); ?>

            <div class="modal fade" id="terimaBarang" tabindex="-1" role="dialog" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Tutup</span></button>
                            <h4 class="modal-title">Info Terima Barang</h4>
                        </div>
                        <?php 
                            // logika untuk di proses parsial, check detail transaksi, jika tidak sama dengan detail order maka parsial
                            // jika pesanan di proses parsial
                            // maka proses pesanan parsial
                            if($detail['kirim_parsial_request_by_id'] == null || $detail['kirim_parsial_request_by_id'] == '')
                            {
                                echo form_open('', 'id="frmProsesTerimaBarang" class="form-horizontal" data-uri="' . BACKMIN_PATH . '/gudangpesanan/processPesananTerimaBarang" role="form" autocomplete="off"'); 
                            }
                            else
                            {
                                echo form_open('', 'id="frmProsesTerimaBarang" class="form-horizontal" data-uri="' . BACKMIN_PATH . '/gudangpesanan/processPesananTerimaBarangParsial" role="form" autocomplete="off"'); 
                            }
                        ?>
                            <input type="hidden" name="reference" value="<?php echo $detail['reference']; ?>">
                            <input type="hidden" name="sts_bayar" value="<?php echo $detail['sts_bayar']; ?>">


                            <div class="modal-body">
                                <div class="row">
                                    <div class="form-group">
                                        <label class="col-md-4 control-label">Nama Penerima</label>
                                        <div class="col-md-6">
                                            <input type="text" id="nama_penerima" name="nama_penerima" class="form-control nama_penerima" value=""/>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-md-4 control-label">Tanggal Terima</label>
                                        <div class="col-md-6">
                                            <div class="input-group date" id="datepicker_terimabarang">
                                                <input type="text" id="tanggal_terima" name="tanggal_terima" class="form-control datepicker tanggal_terima" value=""/>
                                                <span class="input-group-addon add-on"><span class="glyphicon glyphicon-calendar"></span></span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-md-4 control-label">Bukti BAST Parsial</label>
                                        <div class="col-md-6">
                                            <div class="photo">
                                                <input type="file" name="file_bast" id="filer_input2"/>
                                            </div>
                                        </div>
                                    </div>
                                    <?php
                                        if(count($data_product) == 0)
                                        {
                                            $check_status_transaksi = 0;
                                            if(count($data_product) == 0)
                                            {
                                                foreach($data_transaksi as $d)
                                                {
                                                    if($d->status_transaksi != 6)
                                                    {
                                                        $check_status_transaksi++;
                                                    }
                                                }
                                            }
                                            else
                                            {
                                                $check_status_transaksi++;
                                            }
                                            
                                            if($check_status_transaksi == 1)
                                            {
                                    ?>
                                    <div class="form-group">
                                        <label class="col-md-4 control-label">Bukti BAST Full</label>
                                        <div class="col-md-6">
                                            <div class="photo">
                                                <input type="file" name="file_bast_full" id="filer_input3"/>
                                            </div>
                                        </div>
                                    </div>
                                    <?php
                                            }
                                        }
                                    ?>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <div class="pull-left">
                                    <button class="btn btn-success" id="submitDetail">P r o s e s</button>
                                </div>
                                <div class="pull-right">
                                    <button type="button" class="btn btn-primary" data-dismiss="modal">Tutup</button>
                                </div>
                            </div>
                        <?php echo form_close(); ?>
                    </div>
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
    function printExcelPesanan(){
        window.location.href = '<?php echo base_url(BACKMIN_PATH."/gudangpesanan/cetakExcelPesanan/".$detail['id_order']); ?>';
    }
</script>