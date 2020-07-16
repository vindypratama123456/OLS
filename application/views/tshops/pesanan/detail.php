<?php $this->load->view("tshops/header"); ?>
    <div class="container main-container headerOffset">
        <div class="row">
            <div class="breadcrumbDiv col-lg-12">
                <ul class="breadcrumb">
                    <li><a href="<?php echo base_url(); ?>">Beranda</a></li>
                    <li><a href="<?php echo base_url(); ?>pesanan">Pesanan Saya</a></li>
                    <li class="active"> Detil Pesanan</li>
                </ul>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-12 col-md-12 col-sm-12">
                <h1 class="section-title-inner"><span><i
                                class="glyphicon glyphicon-shopping-cart"></i> Detil Pesanan</span></h1>
            </div>
        </div>
        <div class="row">

            <div class="col-lg-12 col-md-12 col-sm-12">
                <div class="row userInfo">
                    <div class="col-xs-12 col-sm-12">
                        <?php echo form_open(base_url().'pesanan/prosespesanan',
                            'name="konfirmasi-pesanan" method="post"'); ?>
                        <div class="cartContent w100">
                            <div style="padding:10px;" class="CartProduct cartTableHeader">Detil Pesanan</div>
                            <div style="margin:10px;">
                                <h2>Kode Pesanan: <?php echo $pesanan[0]->reference; ?></h2>
                                <h4>Status: <b><?php echo $pesanan[0]->order_state_name; ?></b></h4>
                                <h4>Tanggal Pesan: <?php echo $pesanan[0]->tgl_pesan; ?></h4>
                                <h4>Kategori: <?php echo $pesanan[0]->type.' / '.$pesanan[0]->category; ?></h4>
                                <?php if ($pesanan[0]->current_state > 2 && $pesanan[0]->current_state !== 4) { ?>
                                    <p>
                                        <a href="#" class="btn btn-primary" onclick="printInvoice()"><i
                                                    class="fa fa-envelope"></i> Cetak Pesanan</a>
                                        &nbsp;&nbsp;
                                        <a href="<?php echo base_url().'pesanan/cetakPernyataan/'.$pesanan[0]->reference; ?>"
                                           class="btn btn-warning" target="_blank"><i class="fa fa-envelope"></i> Unduh
                                            Surat Pernyataan</a>
                                    </p>
                                <?php } ?>
                            </div>
                            <table class="cartTable table-responsive" style="width:100%">
                                <tbody>

                                <tr class="CartProduct cartTableHeader">
                                    <td style="text-align:left; padding-left:10px;">Judul Buku</td>
                                    <td style="text-align:center">ISBN</td>
                                    <td style="text-align:right">Harga Satuan</td>
                                    <td>Jumlah</td>
                                    <td>Harga Total</td>
                                </tr>
                                <?php
                                foreach ($detailpesanan as $datadetailpesanan) { ?>
                                    <tr class="CartProduct">
                                        <td style="text-align:left; padding-left:10px;"><?php echo $datadetailpesanan->product_name; ?></td>
                                        <td style="text-align:center"><?php echo $datadetailpesanan->reference; ?></td>
                                        <td style="text-align:right"><?php echo toRupiah($datadetailpesanan->unit_price); ?></td>
                                        <td style="text-align:center"><?php echo $datadetailpesanan->product_quantity; ?></td>
                                        <td><?php echo toRupiah($datadetailpesanan->total_price); ?></td>
                                    </tr>
                                <?php } ?>
                                <tr class="CartProduct cartTableHeader">
                                    <td colspan="3">&nbsp;</td>
                                    <td style="text-align:center;">Total bayar :</td>
                                    <td class="price"><?php echo toRupiah($pesanan[0]->total_paid) ?></td>
                                </tr>
                                <tr>
                                    <td colspan="6" style="text-align:right;"><i>Terbilang:
                                            <b><?php echo terbilang($pesanan[0]->total_paid); ?></b></i></td>
                                </tr>
                                </tbody>
                            </table>
                            <br/>
                            <table width="40%" class="cartTable table-responsive" style="margin:20px 0;">
                                <tbody>
                                <tr style="text-align:left;">
                                    <?php
                                    $tahunPesan = $result = substr($pesanan[0]->tgl_pesan, 0, 4);
                                    $prefixVA = $tahunPesan == '2019' ? config_item('va_men') : config_item('va_grm');
                                    ?>
                                    <td>Mohon untuk melakukan pembayaran melalui transfer bank ke nomor rekening
                                        <b>BRI</b> <i><u>Virtual Account</u></i>
                                        <b><?php echo $prefixVA.$this->session->userdata('data_user')['npsn']; ?></b>
                                        atas nama <b>PT. Gramedia</b></td>
                                </tr>
                                <tr style="text-align:left;">
                                    <td><br/>Untuk mengetahui tata cara pembayaran ke <b>Nomor Virtual Account BRI</b>,
                                        silahkan <b><u><a
                                                        href="<?php echo base_url(); ?>halaman/tata-cara-pembayaran-bri-virtual-account"
                                                        target="_blank">KLIK TAUTAN INI</a></u></b>.
                                    </td>
                                </tr>
                                </tbody>
                            </table>
                        </div>
                        <?php echo form_close(); ?>

                        <?php if ($pesanan[0]->current_state == 1) { ?>
                            <a href="<?php echo base_url('pesanan/popup_batal/'.$pesanan[0]->id_order); ?>"
                               class="btn btn-danger" data-toggle="modal" data-target="#myModal"><i
                                        class="fa fa-exclamation-circle"></i> Batalkan</a>
                        <?php } ?>

                        <?php if ($pesanan[0]->current_state > 1) { ?>
                            <div style="margin:10px 0;padding:10px;" class="CartProduct cartTableHeader">Riwayat
                                Pesanan
                            </div>
                            <table class="table table-bordered">
                                <tr>
                                    <th class="text-center">Status</th>
                                    <th class="text-center">Pengguna</th>
                                    <th class="text-center">Tanggal</th>
                                    <th class="text-center">Waktu Sistem</th>
                                    <th class="text-center">Keterangan</th>
                                </tr>
                                <tr>
                                    <td>Dibuat</td>
                                    <td><?php echo $pesanan[0]->school_name; ?></td>
                                    <td></td>
                                    <td class="text-center"><?php echo $pesanan[0]->tgl_pesan; ?></td>
                                    <td></td>
                                </tr>
                                <?php if ($pesanan[0]->current_state == 2) { ?>
                                    <tr>
                                        <td>Dibatalkan</td>
                                        <td><?php echo $pesanan[0]->school_name; ?></td>
                                        <td></td>
                                        <td class="text-center"><?php echo $pesanan[0]->tgl_update; ?></td>
                                        <td><?php echo $pesanan[0]->alasan_batal; ?></td>
                                    </tr>
                                    <?php
                                }
                                if ($liststatus) {
                                    foreach ($liststatus as $row) {
                                        if ($row->id_state == 3) {
                                            ?>
                                            <tr>
                                                <td><?php echo $row->order_state; ?></td>
                                                <td><?php echo $row->employee; ?></td>
                                                <td></td>
                                                <td class="text-center"><?php echo $row->tanggal; ?></td>
                                                <td>
                                                    <?php if ($pesanan[0]->jangka_waktu) {
                                                        echo 'Jangka Waktu Pengiriman: '.$pesanan[0]->jangka_waktu.' Hari<br>';
                                                    } ?>
                                                    <?php if ($pesanan[0]->kesepakatan_sampai) {
                                                        echo 'Kesepakatan Buku Sampai di Sekolah: '.$pesanan[0]->kesepakatan_sampai.' Hari';
                                                    } ?>
                                                </td>
                                            </tr>
                                        <?php }
                                        if ($row->id_state == 5) { ?>
                                            <tr>
                                                <td><?php echo $row->order_state; ?></td>
                                                <td><?php echo $row->employee; ?></td>
                                                <td></td>
                                                <td class="text-center"><?php echo $row->tanggal; ?></td>
                                                <!--<td>Logistik: <?php // echo (1==$pesanan[0]->is_intan) ? 'Intan Pariwara' : 'Gramedia'; ?></td>-->
                                                <!--<td>Logistik: <?php // echo $pesanan[0]->is_intan; ?></td>-->
                                                <td>Logistik: PT. Gramedia</td>
                                            </tr>
                                        <?php }
                                        if ($row->id_state == 6) { ?>
                                            <tr>
                                                <td><?php echo $row->order_state; ?></td>
                                                <td><?php echo $row->employee; ?></td>
                                                <td class="text-center"><?php echo substr($pesanan[0]->tgl_kirim, 0,
                                                        10); ?></td>
                                                <td class="text-center"><?php echo $row->tanggal; ?></td>
                                                <td></td>
                                            </tr>
                                        <?php }
                                        if ($row->id_state == 7) { ?>
                                            <tr>
                                                <td><?php echo $row->order_state; ?></td>
                                                <td><?php echo $row->employee; ?></td>
                                                <td class="text-center"><?php echo substr($pesanan[0]->tgl_sampai, 0,
                                                        10); ?></td>
                                                <td class="text-center"><?php echo $row->tanggal; ?></td>
                                                <td><?php echo $pesanan[0]->nama_penerima; ?></td>
                                            </tr>
                                        <?php }
                                        if ($row->id_state == 8) { ?>
                                            <tr>
                                                <td><?php echo $row->order_state; ?></td>
                                                <td><?php echo $row->employee; ?></td>
                                                <td class="text-center"><?php echo substr($pesanan[0]->tgl_terima, 0,
                                                        10); ?></td>
                                                <td class="text-center"><?php echo $row->tanggal; ?></td>
                                                <td><?php echo $pesanan[0]->nomor_surat.' :: '.$pesanan[0]->tanggal_surat; ?></td>
                                            </tr>
                                        <?php }
                                        if ($row->id_state == 9) { ?>
                                            <tr>
                                                <td><?php echo $row->order_state; ?></td>
                                                <td><?php echo $row->employee; ?></td>
                                                <td class="text-center"><?php echo substr($pesanan[0]->tgl_bayar, 0,
                                                        10); ?></td>
                                                <td class="text-center"><?php echo $row->tanggal; ?></td>
                                                <td><?php echo toRupiah($pesanan[0]->jumlah_bayar); ?></td>
                                            </tr>
                                        <?php }
                                    }
                                } ?>
                            </table>
                        <?php } ?>

                        <?php if ($pesanan[0]->current_state <> 1 && $pesanan[0]->current_state <> 2 && $pesanan[0]->current_state <> 4) { ?>
                            <h1 class="section-title-inner">
                                <span><i class="fa fa-comment" aria-hidden="true"></i> Komentar Anda</span>
                            </h1>
                            <br/>
                            <?php if ($isCommented == 0) { ?>
                                <?php echo form_open(base_url().'pesanan/feedback', 'name="feedback" method="post"'); ?>
                                <div class="form-group">
                                    <label>Silahkan isi komentar</label>
                                    <textarea name="feedback" class="form-control" style="resize:none;"></textarea>
                                </div>
                                <div class="form-group">
                                    <label>Penilaian</label>
                                    <br/>
                                    <input name="rating" type="radio" value="1">&nbsp;Sangat Mengecewakan&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input
                                            name="rating" type="radio" value="2">&nbsp;Mengecewakan&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input
                                            name="rating" type="radio" value="3">&nbsp;Cukup Memuaskan&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input
                                            name="rating" type="radio" value="4">&nbsp;Memuaskan&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input
                                            name="rating" type="radio" checked="" value="5">&nbsp;Sangat Memuaskan&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                </div>
                                <input type="hidden" value="<?php echo $pesanan[0]->id_order; ?>" name="id_order">
                                <button type="submit" class="btn btn-primary"><i class="fa fa-send"></i> Kirim Komentar
                                </button>
                                <?php echo form_close(); ?>
                            <?php } else { ?>
                                <div class="form-group">
                                    <label>Komentar</label>
                                    <textarea readonly=""
                                              class="form-control"><?php echo $feedback[0]->comment ?></textarea>
                                </div>
                                <div class="form-group">
                                    <label>Penilaian: <strong
                                                style="color:#00FF00;"><?php if ($feedback[0]->rating == 1) {
                                                echo 'Sangat Mengecewakan';
                                            } elseif ($feedback[0]->rating == 2) {
                                                echo 'Mengecewakan';
                                            } elseif ($feedback[0]->rating == 3) {
                                                echo 'Cukup Memuaskan';
                                            } elseif ($feedback[0]->rating == 4) {
                                                echo 'Memuaskan';
                                            } elseif ($feedback[0]->rating == 5) {
                                                echo 'Sangat Memuaskan';
                                            } ?></strong></label>
                                </div>
                            <?php }
                        } ?>
                        <div class="pull-right">
                            <a href="<?php echo base_url('pesanan'); ?>" class="btn btn-inverse"><i
                                        class="fa fa-arrow-left"></i> &nbsp; Kembali</a>
                        </div>
                    </div>
                </div>
                <br/>
            </div>
        </div>
    </div>
    <div class="modal fade" id="myModal" role="dialog" aria-labelledby="myModalLabel" data-keyboard="false"
         data-backdrop="static">
        <div class="modal-dialog">
            <div class="modal-content">
            </div>
        </div>
    </div>
    <style>
        select {
            padding: 10px;
            background: #fafafa;
            border: 1px solid #eaeaea;
            font-size: 15px;
        }
    </style>
    <script type="text/javascript">
        // var s5_taf_parent = window.location;
        function printInvoice() {
            window.open('<?php echo base_url("pesanan/cetakInvoice/".$pesanan[0]->id_order); ?>', 'page', 'toolbar=0,scrollbars=1,location=0,statusbar=0,menubar=0,resizable=0,width=750,height=600,left=50,top=50,titlebar=no');
        }
    </script>
<?php $this->load->view("tshops/footer"); ?>