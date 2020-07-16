<!-- START BREADCRUMB -->
<ul class="breadcrumb">
    <li><a href="<?php echo base_url(BACKMIN_PATH); ?>">Dasbor</a></li>
    <li><a href="<?php echo base_url(BACKMIN_PATH.'/gudangpengiriman'); ?>">Pengiriman</a></li>
    <li class="active">Detil Surat Jalan</li>
</ul>
<!-- END BREADCRUMB -->
<!-- PAGE TITLE -->
<div class="page-title">
    <h2><span class="glyphicon glyphicon-bookmark"></span> Kode: #<?php echo $detail['kode_spk']; ?></h2>
</div>
<!-- END PAGE TITLE -->
<!-- PAGE CONTENT WRAPPER -->
<div class="page-content-wrap">
    <div class="row">
        <div class="col-md-12">

            <?php if ($this->session->flashdata('success')): ?>
                <div role="alert" class="alert alert-success">
                    <button data-dismiss="alert" class="close" type="button"><span aria-hidden="true">×</span><span
                                class="sr-only">Close</span></button>
                    <?php echo $this->session->flashdata('success'); ?>
                </div>
            <?php endif; ?>
            <?php if ($this->session->flashdata('error')): ?>
                <div role="alert" class="alert alert-danger">
                    <button data-dismiss="alert" class="close" type="button"><span aria-hidden="true">×</span><span
                                class="sr-only">Close</span></button>
                    <?php echo $this->session->flashdata('error'); ?>
                </div>
            <?php endif; ?>

            <div class="panel panel-primary">
                <div class="panel-heading">
                    <h3 class="panel-title">Info Ekspeditur</h3>
                </div>
                <div class="panel-body">
                    <h4><b><?php echo $ekspeditur['nama']; ?></b></h4>
                    <p>
                        No. Kendaraan : <?php echo $detail['nopol']; ?><br>
                        Nama Supir : <?php echo $detail['nama_supir']; ?><br>
                        Telpon/Hp Supir : <?php echo $detail['hp_supir']; ?>
                    </p>
                    <p>Tanggal SJE : <?php echo $detail['created_date']; ?></p>
                    <h6>Status</h6>
                    <p>Status SPK: <?php echo $status; ?></p>
                </div>
            </div>
            <div class="panel panel-primary">
                <div class="panel-heading">
                    <h3 class="panel-title">Daftar Transaksi</h3>
                </div>
                <div class="panel-body panel-body-table">
                    <?php echo form_open(
                        '',
                        'id="frmDetilSPK" class="form-horizontal" data-uri="'.BACKMIN_PATH.'/gudangpengiriman/prosesSPK" role="form" autocomplete="off"'
                    ); ?>
                    <input type="hidden" name="id_spk" value="<?php echo $detail['id_spk']; ?>">
                    <input type="hidden" name="kode_spk" value="<?php echo $detail['kode_spk']; ?>">
                    <input type="hidden" name="status" value="<?php echo $detail['status']; ?>">
                    <div class="table-responsive" id="product-area">
                        <?php if ($list_transaksi) { ?>
                            <table class="table table-bordered table-striped">
                                <thead>
                                <tr>
                                    <th width="5%" class="text-center">No</th>
                                    <th width="10%" class="text-center">Kode Pesanan</th>
                                    <th width="50%" class="text-center">Tujuan</th>
                                    <th width="10%" class="text-center">Total Berat (Kg)</th>
                                    <th width="10%" class="text-center">Total Jumlah</th>
                                    <th width="15%" class="text-center">Aksi</th>
                                </tr>
                                </thead>
                                <tbody>
                                <?php
                                $i = 1;
                                $tot_berat = 0;
                                $tot_jumlah = 0;
                                $tot_row = count($list_transaksi);
                                foreach ($list_transaksi as $row) {
                                    ?>
                                    <tr id="trow_<?php echo $i; ?>">
                                        <td class="text-center"><?php echo $i; ?></td>
                                        <td class="text-center">
                                            <?php
                                            if ($row->is_to_school == 1) {
                                                if($row->kirim_parsial_request_by_id == null || $row->kirim_parsial_request_by_id == "")
                                                {
                                                    echo '<a href="'.base_url().BACKMIN_PATH.'/gudangpesanan/detailPesananDiproses/'.$row->id_pesanan.'" target="_blank">'.$row->detail_kode.'</a>';
                                                }
                                                else
                                                {
                                                    echo '<a href="'.base_url().BACKMIN_PATH.'/gudangpesanan/detailPesananDiprosesparsial/'.$row->id_transaksi.'" target="_blank">'.$row->detail_kode.'</a>';
                                                }
                                            } else {
                                                echo $row->detail_kode;
                                            }
                                            ?>
                                        </td>
                                        <td class="text-left"><?php echo $row->tujuan.'<br>'.$row->alamat; ?></td>
                                        <td class="text-center"><?php echo $row->berat; ?></td>
                                        <td class="text-center"><?php echo $row->jumlah; ?></td>
                                        <td class="text-center">
                                            <?php 
                                            if($row->reference_other != null || $row->reference_other_from != '')
                                            {
                                            ?>
                                                <a href="#" onclick="printBAST_siplah('<?php echo $row->reference_other ?>')" class="btn btn-warning" style="margin-bottom: 5px;">BAST Siplah</a><br>
                                            <?php
                                            }
                                            ?>

                                            <?php
                                            $product_left = array();
                                            $product_left = $this->mod_gudang->check_list_product_leftover($row->id_pesanan, $this->adm_id_gudang);
                                            $product_left_count = count($product_left);

                                            if($row->kirim_parsial_request_by_id != null || $row->kirim_parsial_request_by_id != '')
                                            {
                                                if($product_left_count == 0)
                                                {
                                            ?>
                                                <a href="#" onclick="printBASTFull(<?php echo $row->id_transaksi ?>, <?php echo $row->detail_id ?>)" class="btn btn-warning" style="margin-bottom: 5px;">Cetak BAST</a><br>
                                            <?php
                                                }
                                            }
                                            
                                            if ($row->is_to_school == 1) {
                                                if ($detail['status'] == 1) {
                                                    ?>
                                                    <a href="#"
                                                       onclick="printBAST(<?php echo $row->id_transaksi ?>, <?php echo $row->detail_id ?>)"
                                                       class="btn btn-warning">Cetak BAST
                                                        <?php
                                                            if($row->kirim_parsial_request_by_id != null || $row->kirim_parsial_request_by_id != '')
                                                            {
                                                                echo "Parsial";
                                                            }
                                                        ?>
                                                    </a><br>
                                                    <a href="#"
                                                       onclick="printNotaJual(<?php echo $row->id_transaksi ?>, <?php echo $row->detail_id ?>)"
                                                       class="btn btn-warning" style="margin-top:5px;">Cetak Nota</a>
                                                    <br>
                                                    <a href="#"
                                                       onclick="printTagihan(<?php echo $row->id_transaksi ?>, <?php echo $row->detail_id ?>)"
                                                       class="btn btn-warning" style="margin-top:5px;">Cetak Tagihan</a>
                                                    <br>
													<?php 
													if($row->reference_other != null || $row->reference_other_from != '')
													{
                                                        if($row->reference_other_from == 'Siplah.id')
                                                        {
													?>
														<a href="#" onclick="printInvoice_siplah('<?php echo $row->reference_other ?>')" class="btn btn-warning" style="margin-top: 5px;">Invoice Siplah</a><br>
													<?php
                                                        }
													}
													?>
                                                    <br>
                                                    <?php if ($tot_row > 1) { ?>
                                                        <button type="button"
                                                                onclick="cancelOrder(<?php echo $row->id_transaksi ?>)"
                                                                data-uri="<?php echo base_url(
                                                                    BACKMIN_PATH.'/gudangpengiriman/batalPengiriman/'.$row->id_spk.'/'.$row->id_transaksi
                                                                ); ?>" id="cancelOrder_<?php echo $row->id_transaksi ?>"
                                                                class="btn btn-danger cancelOrder"
                                                                style="margin-top:5px;">Batalkan
                                                        </button>
                                                    <?php }

                                                } elseif ($detail['status'] == 2 || $detail['status'] == 4) {
                                                    ?>
                                                    <a href="#"
                                                       onclick="printBAST(<?php echo $row->id_transaksi ?>, <?php echo $row->detail_id ?>)"
                                                       class="btn btn-warning">Cetak BAST 
                                                        <?php
                                                            if($row->kirim_parsial_request_by_id != null || $row->kirim_parsial_request_by_id != '')
                                                            {
                                                                echo "Parsial";
                                                            }
                                                        ?>
                                                   </a><br>
                                                    <a href="#"
                                                       onclick="printNotaJual(<?php echo $row->id_transaksi ?>, <?php echo $row->detail_id ?>)"
                                                       class="btn btn-warning" style="margin-top:5px;">Cetak Nota</a>
                                                    <br>
                                                    <a href="#"
                                                       onclick="printTagihan(<?php echo $row->id_transaksi ?>, <?php echo $row->detail_id ?>)"
                                                       class="btn btn-warning" style="margin-top:5px;">Cetak Tagihan</a>
													<br>
													<?php 
													if($row->reference_other != null || $row->reference_other_from != '')
													{
                                                        if($row->reference_other == 'Siplah.id')
                                                        {
													?>
														<a href="#" onclick="printInvoice_siplah('<?php echo $row->reference_other ?>')" class="btn btn-warning" style="margin-top: 5px;">Invoice Siplah</a><br>
													<?php
                                                        }
													}
													?>
                                                    <hr>
                                                    <?php
                                                    if ($row->status_transaksi == 5) {
                                                        if($row->kirim_parsial_request_by_id == null || $row->kirim_parsial_request_by_id == '')
                                                        {
                                                        ?>
                                                        <a href="<?php echo base_url(
                                                            BACKMIN_PATH.'/gudangpesanan/detailPesananDiproses/'.$row->detail_id
                                                        ); ?>" target="_blank" class="btn btn-success">Terima Barang</a>
                                                    <?php
                                                        }
                                                        else
                                                        {
                                                    ?>    
                                                        <a href="<?php echo base_url(
                                                            BACKMIN_PATH.'/gudangpesanan/detailPesananDiprosesparsial/'.$row->id_transaksi
                                                        ); ?>" target="_blank" class="btn btn-success">Terima Barang</a>
                                                    <?php
                                                        }
                                                    } 
                                                    else 
                                                    {
                                                        echo "Barang Sudah Diterima";
                                                    }
                                                }
                                            } else {
                                                echo ($detail['status'] == 1) ? 'Barang Siap Kirim' : (($detail['status'] < 4) ? 'Menunggu Proses Terima Barang' : 'Pesanan Sudah Diterima');
                                            }
                                            ?>
                                        </td>
                                    </tr>
                                    <input type="hidden" name="id_transaksi[]"
                                           value="<?php echo $row->id_transaksi; ?>">
                                    <?php
                                    $i++;
                                    $tot_jumlah += $row->berat;
                                    $tot_berat += $row->jumlah;
                                }
                                ?>
                                <tr>
                                    <td colspan="3" class="text-right"><b>T o t a l</b></td>
                                    <td class="text-center"><b><?php echo $tot_jumlah; ?></b></td>
                                    <td class="text-center"><b><?php echo $tot_berat; ?></b></td>
                                    <?php if ($detail['status'] < 4) {
                                        echo '<td></td>';
                                    } ?>
                                </tr>
                                </tbody>
                            </table>
                        <?php } ?>
                    </div>
                    <div class="form-group panel-footer">
                        <div class="pull-left">
                            <?php if ($detail['status'] == 1) { ?>
                                <button class="btn btn-success" id="submitDetail">Kirim</button>
                            <?php } ?>
                            <a href="#" class="btn btn-warning" style="margin-left:10px;" onclick="return printSJE();">Cetak</a>


                            <a href="#" class="btn btn-warning" style="margin-left:10px;" onclick="return printSJE_tag();">Cetak TAG</a>

                            <?php if ($this->adm_uname == "superadmin.gudang@gramediaprinting.com" && $detail['status']==2)
                                    {
                                ?>
                                <a href="<?php echo base_url(BACKMIN_PATH . "/gudangpengiriman/statusdikirimtospkdibuat/" . $detail['id_spk']); ?>" class="btn btn-primary" onClick="return confirm('Anda akan membatalkan pengiriman');" style="margin-left:10px;">Batalkan Pengiriman</a>
                            <?php   } ?>
                        </div>
                        <div class="pull-right">
                            <a href="<?php echo base_url(BACKMIN_PATH.'/gudangpengiriman'); ?>" class="btn btn-primary"><i
                                        class="fa fa-arrow-left"></i> Kembali</a>
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
    function printSJE() {
        window.open('<?php echo base_url(
            BACKMIN_PATH."/gudangpengiriman/cetakSJE/".$detail['id_spk']
        ); ?>', 'page', 'toolbar=0,scrollbars=1,location=0,statusbar=0,menubar=0,resizable=0,width=750,height=600,left=50,top=50,titlebar=no');
    }
    function printSJE_tag() {
        window.open('<?php echo base_url(
            BACKMIN_PATH."/gudangpengiriman/cetakSJE_tag/".$detail['id_spk']
        ); ?>', 'page', 'toolbar=0,scrollbars=1,location=0,statusbar=0,menubar=0,resizable=0,width=750,height=600,left=50,top=50,titlebar=no');
    }
    function printBAST_siplah(reference_siplah) {
        window.open('<?php echo base_url('backmin/gudangpengiriman/download_bast_siplah/') ?>'+reference_siplah, 'page', 'toolbar=0,scrollbars=1,location=0,statusbar=0,menubar=0,resizable=0,width=750,height=600,left=50,top=50,titlebar=no');
    }
	function printInvoice_siplah(reference_siplah) {	
		var reference = reference_siplah.split("_");
        console.log(reference_siplah);

		var link = "<?php echo base_url(BACKMIN_PATH."/gudangpengiriman/cetak_invoice_siplah/") ?>";
		console.log(link)
		
		window.open(link+reference_siplah, 'page', 'toolbar=0,scrollbars=1,location=0,statusbar=0,menubar=0,resizable=0,width=750,height=600,left=50,top=50,titlebar=no');
	
    }
</script>
