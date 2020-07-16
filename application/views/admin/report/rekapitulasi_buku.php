<style type="text/css">
    div.DTTT { margin-bottom: 0.5em; float: right; }
    div.dataTables_wrapper { clear: both; }
</style>

<div class="container-fluid">

    <!-- Page Heading -->
    <div class="row">
        <div class="col-lg-12">
            <h1 class="page-header">
                Laporan Rekapitulasi Jumlah Oplah Buku
            </h1>
            <ol class="breadcrumb">
                <li>
                    <a href="<?php echo base_url() . ADMIN_PATH; ?>">Beranda</a>
                </li>
                <li class="active">
                    Laporan Rekapitulasi Jumlah Oplah Buku
                </li>
            </ol>
        </div>
    </div>
    <!-- /.row -->


    <div class="row">
        <div class="col-lg-12">
            <h3>TRANSAKSI KEMENDIKBUD (DAPODIK)</h3>
            <div class="table-responsive">
                <?php if($dapodik_1) { ?>
                <h4>1) Semester 1 Periode awal transaksi s.d. 28 Februari 2017</h4>
                <table class="table table-striped table-bordered dt-responsive wrap" id="datatable-all-input">
                    <thead>
                        <tr>
                            <th class="text-center" width="5%" rowspan="2" style="vertical-align: middle;">NO.</th>
                            <th class="text-center" rowspan="2" style="vertical-align: middle;">BENTUK<br>PENDIDIKAN</th>
                            <th class="text-center" colspan="2">PESAN</th>
                            <th class="text-center" colspan="2">KIRIM</th>
                            <th class="text-center">BAST</th>
                            <th class="text-center" colspan="3">BAYAR</th>
                        </tr>
                        <tr>
                            <th class="text-center">TOTAL<br>BUKU</th>
                            <th class="text-center">TOTAL<br>HARGA</th>
                            <th class="text-center">TOTAL<br>BUKU</th>
                            <th class="text-center">TOTAL<br>HARGA</th>
                            <th class="text-center">TOTAL<br>BUKU</th>
                            <th class="text-center">TOTAL<br>TAGIHAN</th>
                            <th class="text-center">TOTAL<br>TERBAYAR</th>
                            <th class="text-center">SISA<br>PEMBAYARAN</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $no=1; foreach ($dapodik_1 as $bentuk => $val) { ?>
                        <tr>
                            <td class="text-center"><?php echo $no; ?></td>
                            <td class="text-center"><?php echo strtoupper($bentuk); ?></td>
                            <td class="text-center"><?php echo number_format($val['pesan_buku'], '0', ',', '.'); ?></td>
                            <td class="text-center"><?php echo number_format($val['pesan_harga'], '0', ',', '.'); ?></td>
                            <td class="text-center"><?php echo number_format($val['kirim_buku'], '0', ',', '.'); ?></td>
                            <td class="text-center"><?php echo number_format($val['kirim_harga'], '0', ',', '.'); ?></td>
                            <td class="text-center"><?php echo number_format($val['bast'], '0', ',', '.'); ?></td>
                            <td class="text-center"><?php echo number_format($val['bayar_tagihan'], '0', ',', '.'); ?></td>
                            <td class="text-center"><?php echo number_format($val['bayar_terbayar'], '0', ',', '.'); ?></td>
                            <td class="text-center"><?php echo number_format($val['bayar_sisa'], '0', ',', '.'); ?></td>
                        </tr>
                        <?php $no++; } ?>
                    </tbody>
                </table>
                <?php } else { echo 'Maaf, data tidak tersedia :('; } ?>
            </div>

            <div class="table-responsive">
                <?php if($dapodik_2) { ?>
                <h4>2) Semester 1 Periode 1 Maret 2017 s.d. 26 Mei 2017</h4>
                <table class="table table-striped table-bordered dt-responsive wrap" id="datatable-all-input">
                    <thead>
                        <tr>
                            <th class="text-center" width="5%" rowspan="2" style="vertical-align: middle;">NO.</th>
                            <th class="text-center" rowspan="2" style="vertical-align: middle;">BENTUK<br>PENDIDIKAN</th>
                            <th class="text-center" colspan="2">PESAN</th>
                            <th class="text-center" colspan="2">KIRIM</th>
                            <th class="text-center">BAST</th>
                            <th class="text-center" colspan="3">BAYAR</th>
                        </tr>
                        <tr>
                            <th class="text-center">TOTAL<br>BUKU</th>
                            <th class="text-center">TOTAL<br>HARGA</th>
                            <th class="text-center">TOTAL<br>BUKU</th>
                            <th class="text-center">TOTAL<br>HARGA</th>
                            <th class="text-center">TOTAL<br>BUKU</th>
                            <th class="text-center">TOTAL<br>TAGIHAN</th>
                            <th class="text-center">TOTAL<br>TERBAYAR</th>
                            <th class="text-center">SISA<br>PEMBAYARAN</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $no=1; foreach ($dapodik_2 as $bentuk => $val) { ?>
                        <tr>
                            <td class="text-center"><?php echo $no; ?></td>
                            <td class="text-center"><?php echo strtoupper($bentuk); ?></td>
                            <td class="text-center"><?php echo number_format($val['pesan_buku'], '0', ',', '.'); ?></td>
                            <td class="text-center"><?php echo number_format($val['pesan_harga'], '0', ',', '.'); ?></td>
                            <td class="text-center"><?php echo number_format($val['kirim_buku'], '0', ',', '.'); ?></td>
                            <td class="text-center"><?php echo number_format($val['kirim_harga'], '0', ',', '.'); ?></td>
                            <td class="text-center"><?php echo number_format($val['bast'], '0', ',', '.'); ?></td>
                            <td class="text-center"><?php echo number_format($val['bayar_tagihan'], '0', ',', '.'); ?></td>
                            <td class="text-center"><?php echo number_format($val['bayar_terbayar'], '0', ',', '.'); ?></td>
                            <td class="text-center"><?php echo number_format($val['bayar_sisa'], '0', ',', '.'); ?></td>
                        </tr>
                        <?php $no++; } ?>
                    </tbody>
                </table>
                <?php } else { echo 'Maaf, data tidak tersedia :('; } ?>
            </div>

            <div class="table-responsive">
                <?php if($dapodik_3) { ?>
                <h4>3) Semester 2 Periode awal transaksi s.d. 26 Mei 2017</h4>
                <table class="table table-striped table-bordered dt-responsive wrap" id="datatable-all-input">
                    <thead>
                        <tr>
                            <th class="text-center" width="5%" rowspan="2" style="vertical-align: middle;">NO.</th>
                            <th class="text-center" rowspan="2" style="vertical-align: middle;">BENTUK<br>PENDIDIKAN</th>
                            <th class="text-center" colspan="2">PESAN</th>
                            <th class="text-center" colspan="2">KIRIM</th>
                            <th class="text-center">BAST</th>
                            <th class="text-center" colspan="3">BAYAR</th>
                        </tr>
                        <tr>
                            <th class="text-center">TOTAL<br>BUKU</th>
                            <th class="text-center">TOTAL<br>HARGA</th>
                            <th class="text-center">TOTAL<br>BUKU</th>
                            <th class="text-center">TOTAL<br>HARGA</th>
                            <th class="text-center">TOTAL<br>BUKU</th>
                            <th class="text-center">TOTAL<br>TAGIHAN</th>
                            <th class="text-center">TOTAL<br>TERBAYAR</th>
                            <th class="text-center">SISA<br>PEMBAYARAN</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $no=1; foreach ($dapodik_3 as $bentuk => $val) { ?>
                        <tr>
                            <td class="text-center"><?php echo $no; ?></td>
                            <td class="text-center"><?php echo strtoupper($bentuk); ?></td>
                            <td class="text-center"><?php echo number_format($val['pesan_buku'], '0', ',', '.'); ?></td>
                            <td class="text-center"><?php echo number_format($val['pesan_harga'], '0', ',', '.'); ?></td>
                            <td class="text-center"><?php echo number_format($val['kirim_buku'], '0', ',', '.'); ?></td>
                            <td class="text-center"><?php echo number_format($val['kirim_harga'], '0', ',', '.'); ?></td>
                            <td class="text-center"><?php echo number_format($val['bast'], '0', ',', '.'); ?></td>
                            <td class="text-center"><?php echo number_format($val['bayar_tagihan'], '0', ',', '.'); ?></td>
                            <td class="text-center"><?php echo number_format($val['bayar_terbayar'], '0', ',', '.'); ?></td>
                            <td class="text-center"><?php echo number_format($val['bayar_sisa'], '0', ',', '.'); ?></td>
                        </tr>
                        <?php $no++; } ?>
                    </tbody>
                </table>
                <?php } else { echo 'Maaf, data tidak tersedia :('; } ?>
            </div>
        </div>
    </div>
    <!-- /.row -->
    
    <div class="row">
        <div class="col-lg-12">
            <h3>TRANSAKSI NON-KEMENDIKBUD (NON-DAPODIK)</h3>
            <div class="table-responsive">
                <?php if($non_dapodik_1) { ?>
                <h4>1) Semester 1 Periode awal transaksi s.d. 28 Februari 2017</h4>
                <table class="table table-striped table-bordered dt-responsive wrap" id="datatable-all-input">
                    <thead>
                        <tr>
                            <th class="text-center" width="5%" rowspan="2" style="vertical-align: middle;">NO.</th>
                            <th class="text-center" rowspan="2" style="vertical-align: middle;">BENTUK<br>PENDIDIKAN</th>
                            <th class="text-center" colspan="2">PESAN</th>
                            <th class="text-center" colspan="2">KIRIM</th>
                            <th class="text-center">BAST</th>
                            <th class="text-center" colspan="3">BAYAR</th>
                        </tr>
                        <tr>
                            <th class="text-center">TOTAL<br>BUKU</th>
                            <th class="text-center">TOTAL<br>HARGA</th>
                            <th class="text-center">TOTAL<br>BUKU</th>
                            <th class="text-center">TOTAL<br>HARGA</th>
                            <th class="text-center">TOTAL<br>BUKU</th>
                            <th class="text-center">TOTAL<br>TAGIHAN</th>
                            <th class="text-center">TOTAL<br>TERBAYAR</th>
                            <th class="text-center">SISA<br>PEMBAYARAN</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $no=1; foreach ($non_dapodik_1 as $bentuk => $val) { ?>
                        <tr>
                            <td class="text-center"><?php echo $no; ?></td>
                            <td class="text-center"><?php echo strtoupper($bentuk); ?></td>
                            <td class="text-center"><?php echo number_format($val['pesan_buku'], '0', ',', '.'); ?></td>
                            <td class="text-center"><?php echo number_format($val['pesan_harga'], '0', ',', '.'); ?></td>
                            <td class="text-center"><?php echo number_format($val['kirim_buku'], '0', ',', '.'); ?></td>
                            <td class="text-center"><?php echo number_format($val['kirim_harga'], '0', ',', '.'); ?></td>
                            <td class="text-center"><?php echo number_format($val['bast'], '0', ',', '.'); ?></td>
                            <td class="text-center"><?php echo number_format($val['bayar_tagihan'], '0', ',', '.'); ?></td>
                            <td class="text-center"><?php echo number_format($val['bayar_terbayar'], '0', ',', '.'); ?></td>
                            <td class="text-center"><?php echo number_format($val['bayar_sisa'], '0', ',', '.'); ?></td>
                        </tr>
                        <?php $no++; } ?>
                    </tbody>
                </table>
                <?php } else { echo 'Maaf, data tidak tersedia :('; } ?>
            </div>

            <div class="table-responsive">
                <?php if($non_dapodik_2) { ?>
                <h4>2) Semester 1 Periode 1 Maret 2017 s.d. 26 Mei 2017</h4>
                <table class="table table-striped table-bordered dt-responsive wrap" id="datatable-all-input">
                    <thead>
                        <tr>
                            <th class="text-center" width="5%" rowspan="2" style="vertical-align: middle;">NO.</th>
                            <th class="text-center" rowspan="2" style="vertical-align: middle;">BENTUK<br>PENDIDIKAN</th>
                            <th class="text-center" colspan="2">PESAN</th>
                            <th class="text-center" colspan="2">KIRIM</th>
                            <th class="text-center">BAST</th>
                            <th class="text-center" colspan="3">BAYAR</th>
                        </tr>
                        <tr>
                            <th class="text-center">TOTAL<br>BUKU</th>
                            <th class="text-center">TOTAL<br>HARGA</th>
                            <th class="text-center">TOTAL<br>BUKU</th>
                            <th class="text-center">TOTAL<br>HARGA</th>
                            <th class="text-center">TOTAL<br>BUKU</th>
                            <th class="text-center">TOTAL<br>TAGIHAN</th>
                            <th class="text-center">TOTAL<br>TERBAYAR</th>
                            <th class="text-center">SISA<br>PEMBAYARAN</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $no=1; foreach ($non_dapodik_2 as $bentuk => $val) { ?>
                        <tr>
                            <td class="text-center"><?php echo $no; ?></td>
                            <td class="text-center"><?php echo strtoupper($bentuk); ?></td>
                            <td class="text-center"><?php echo number_format($val['pesan_buku'], '0', ',', '.'); ?></td>
                            <td class="text-center"><?php echo number_format($val['pesan_harga'], '0', ',', '.'); ?></td>
                            <td class="text-center"><?php echo number_format($val['kirim_buku'], '0', ',', '.'); ?></td>
                            <td class="text-center"><?php echo number_format($val['kirim_harga'], '0', ',', '.'); ?></td>
                            <td class="text-center"><?php echo number_format($val['bast'], '0', ',', '.'); ?></td>
                            <td class="text-center"><?php echo number_format($val['bayar_tagihan'], '0', ',', '.'); ?></td>
                            <td class="text-center"><?php echo number_format($val['bayar_terbayar'], '0', ',', '.'); ?></td>
                            <td class="text-center"><?php echo number_format($val['bayar_sisa'], '0', ',', '.'); ?></td>
                        </tr>
                        <?php $no++; } ?>
                    </tbody>
                </table>
                <?php } else { echo 'Maaf, data tidak tersedia :('; } ?>
            </div>

            <div class="table-responsive">
                <?php if($non_dapodik_3) { ?>
                <h4>3) Semester 2 Periode awal transaksi s.d. 26 Mei 2017</h4>
                <table class="table table-striped table-bordered dt-responsive wrap" id="datatable-all-input">
                    <thead>
                        <tr>
                            <th class="text-center" width="5%" rowspan="2" style="vertical-align: middle;">NO.</th>
                            <th class="text-center" rowspan="2" style="vertical-align: middle;">BENTUK<br>PENDIDIKAN</th>
                            <th class="text-center" colspan="2">PESAN</th>
                            <th class="text-center" colspan="2">KIRIM</th>
                            <th class="text-center">BAST</th>
                            <th class="text-center" colspan="3">BAYAR</th>
                        </tr>
                        <tr>
                            <th class="text-center">TOTAL<br>BUKU</th>
                            <th class="text-center">TOTAL<br>HARGA</th>
                            <th class="text-center">TOTAL<br>BUKU</th>
                            <th class="text-center">TOTAL<br>HARGA</th>
                            <th class="text-center">TOTAL<br>BUKU</th>
                            <th class="text-center">TOTAL<br>TAGIHAN</th>
                            <th class="text-center">TOTAL<br>TERBAYAR</th>
                            <th class="text-center">SISA<br>PEMBAYARAN</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $no=1; foreach ($non_dapodik_3 as $bentuk => $val) { ?>
                        <tr>
                            <td class="text-center"><?php echo $no; ?></td>
                            <td class="text-center"><?php echo strtoupper($bentuk); ?></td>
                            <td class="text-center"><?php echo number_format($val['pesan_buku'], '0', ',', '.'); ?></td>
                            <td class="text-center"><?php echo number_format($val['pesan_harga'], '0', ',', '.'); ?></td>
                            <td class="text-center"><?php echo number_format($val['kirim_buku'], '0', ',', '.'); ?></td>
                            <td class="text-center"><?php echo number_format($val['kirim_harga'], '0', ',', '.'); ?></td>
                            <td class="text-center"><?php echo number_format($val['bast'], '0', ',', '.'); ?></td>
                            <td class="text-center"><?php echo number_format($val['bayar_tagihan'], '0', ',', '.'); ?></td>
                            <td class="text-center"><?php echo number_format($val['bayar_terbayar'], '0', ',', '.'); ?></td>
                            <td class="text-center"><?php echo number_format($val['bayar_sisa'], '0', ',', '.'); ?></td>
                        </tr>
                        <?php $no++; } ?>
                    </tbody>
                </table>
                <?php } else { echo 'Maaf, data tidak tersedia :('; } ?>
            </div>
        </div>
    </div>
    <!-- /.row -->

</div>
<!-- /.container-fluid -->