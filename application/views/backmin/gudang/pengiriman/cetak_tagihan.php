<!DOCTYPE html>
<html moznomarginboxes mozdisallowselectionprint>
    <head>
        <title>Tagihan #<?php echo $detil['kode_pesanan']; ?></title>
        <link rel="stylesheet" type="text/css" href="<?php echo assets_url_backmin('css/cetak.css'); ?>"/>
    </head>
    <body onload="window.print();">
        <div id="logo-content">
            <?php if ($order['type']=='Peminatan SMK') { ?>
                <img src="<?php echo assets_url('backmin/img/logo-mitra-edukasi-nusantara.jpeg'); ?>" id="logo-kiri" width="120" height="120"/>
            <?php } else { ?>
				<?php if ($order['date_add'] < '2019-01-01' ) { ?>
                <img src="<?php echo assets_url('backmin/img/logo-printing.png'); ?>" id="logo-kiri" width="180" height="85"/>
				<?php } else { ?>
				<img src="<?php echo assets_url('backmin/img/logo-mitra-edukasi-nusantara.jpeg'); ?>" id="logo-kiri" width="120" height="120"/>
				<?php } ?>
            <?php } ?>
            <img src="<?php echo assets_url('backmin/img/logo-kg-gom.png'); ?>" id="logo-kanan" width="200" height="30"/>
        </div>
        <br><br><br><br><br>
        <div align="center">
            <span class="judul-1">LEMBAR TAGIHAN</span><br />
            <span class="judul-2"><?php echo strtoupper($listproducts[0]->type . ' (' . $listproducts[0]->type_alias . ')'); ?></span><br />
            <span class="judul-2">Kode Pesanan: <b>#<?php echo $detil['kode_pesanan']; ?></b></span>
        </div>
        <hr>
        <p style="margin:20px 0 10px 0;">Pada hari ini, .................. Tanggal: ...... Bulan: ........................ Tahun: <?php echo date('Y'); ?> yang bertanda tangan di bawah ini:</p>
        <table width="100%" border="0">
            <tr>
                <td width="25%"></td>
                <td width="1%"></td>
                <td width="74%"></td>
            </tr>
            <tr>
                <td>Nama</td>
                <td>:</td>
                <td></td>
            </tr>
            <tr>
                <td>Jabatan</td>
                <td>:</td>
                <td></td>
            </tr>
            <tr>
                <td>Perusahaan</td>
                <td>:</td>
                <td>PT. <?php if ($order['date_add'] < '2019-01-01' ) {
					echo ($order['type']=='Peminatan SMK') ? 'MITRA EDUKASI NUSANTARA' : 'GRAMEDIA'; }
					else { 
					echo 'MITRA EDUKASI NUSANTARA';}?></td>
            </tr>
            <tr>
                <td valign="top">Alamat</td>
                <td valign="top">:</td>
                <td valign="top"><?php if ($order['date_add'] < '2019-01-01' ) {
				echo ($order['type']=='Peminatan SMK') ? 'Jl. Agus Salim Ex Terminal B No. 47 Purwodinatan, Semarang, Jawa Tengah' : 'Jl. Palmerah Selatan No. 22 Gelora, Tanah Abang, Jakarta Pusat, DKI Jakarta Raya';} 
				else {
				echo 'Jl. Agus Salim Ex Terminal B No. 47 Purwodinatan, Semarang, Jawa Tengah';}?></td>
            </tr>
        </table>
        <p>Berdasarkan Berita Acara Serah Terima (BAST) tanggal ...... / ........................ / <?php echo date('Y'); ?> mengajukan penagihan atas barang-barang yang sudah diperiksa dan diterima pihak sekolah dengan rincian sebagai berikut:</p>
        <?php if($listproducts) { ?> 
        <table class="table table-bordered table-products" border="1" border-collapse="collapse" width="100%">
            <thead>
                <tr>
                    <th>NO</th>
                    <th>JUDUL BUKU</th>
                    <th>KELAS</th>
                    <th>JUMLAH<br>(Eks)</th>
                    <th>HARGA<br>SATUAN</th>
                    <th>TOTAL</th>
                </tr>
            </thead>
            <tbody>
            <?php 
                $i=1; 
                $tot_item = 0;
                $tot_price = 0;
                foreach($listproducts as $row) { 
            ?>
                <tr>
                    <td class="text-center"><center><?php echo $i; ?></center></td>
                    <td><?php echo $row->judul_buku.'<br>(ISBN: '.$row->isbn.')'; ?></td>
                    <td class="text-center"><center><?php echo $row->kelas; ?></center></td>
                    <td class="text-center"><center><?php echo $row->kuantitas; ?></center></td>
                    <td style="text-align:right;"><?php echo toRupiah($row->harga_satuan); ?></td>
                    <td style="text-align:right;"><?php echo toRupiah($row->total_harga); ?></td>
                </tr>
            <?php 
                $i++;
                $tot_item += $row->kuantitas;
                $tot_price += $row->total_harga;
                } 
            ?>
                <tr>
                    <td colspan="3" class="text-right"><strong><center>GRAND TOTAL</center></strong></td>
                    <td class="text-right"><center><strong><?php echo $tot_item; ?></strong></center></td>
                    <td colspan="2" style="text-align:right;"><strong><?php echo toRupiah($tot_price); ?></strong></td>
                </tr>
            </tbody>
        </table>
        <?php } ?>
        <p style="margin-bottom:20px;">Terbilang: <i><?php echo terbilang($tot_price); ?></i></p>
        <table class="footer" width="100%">
            <tr>
                <td width="50%"><b>PENERIMA</b>,</span></td>
                <td width="49%"><span style="float:right;margin-right:8px;"><b>YANG MENYERAHKAN</b>,</span></td>
            </tr>
            <tr>
                <td width="50%">KEPALA SEKOLAH</span></td>
                <td width="49%"><span style="float:right;margin-right:9px;">PIMPINAN PT. 
					<?php if ($order['date_add'] < '2019-01-01' ) {
					echo ($order['type']=='Peminatan SMK') ? 'MITRA EDUKASI NUSANTARA' : 'GRAMEDIA'; }
					else { 
					echo 'MITRA EDUKASI NUSANTARA';}?></span></td>
            </tr>
            <tr>
                <td></td>
                <td></td>
            </tr>
            <tr><td colspan="2"><br /><br /><br /></td></tr>
            <tr>
                <td>( <?php echo $customer['name'] ? $customer['name'] : '..........................................'; ?> )</td>
                <td><span style="float:right;">( .......................................... )</span></td>
            </tr>
            <tr><td colspan="2"><br /><br /></td></tr>
        </table>
    </body>
</html>