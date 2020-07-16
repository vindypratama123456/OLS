<!DOCTYPE html>
<html moznomarginboxes mozdisallowselectionprint>
    <head>
        <title>Surat Jalan #<?php echo $detail['kode_spk']; ?></title>
        <link rel="stylesheet" type="text/css" href="<?php echo assets_url_backmin('css/cetak.css'); ?>"/>
    </head>
    <body onload="window.print();">
        <div id="logo-content">
            <img src="<?php echo assets_url('backmin/img/logo-printing.png'); ?>" id="logo-kiri" width="180" height="85"/>
            <img src="<?php echo assets_url('backmin/img/logo-kg-gom.png'); ?>" id="logo-kanan" width="200" height="30"/>
        </div>
        <br><br><br><br><br>
        <div align="center">
            <span class="judul-1">SURAT JALAN EKSPEDITUR</span><br />
            <span class="judul-2">Kode: <b>#<?php echo $detail['kode_spk']; ?></b></span><br ?>
            <span class="judul-3">Tgl Cetak: <?php echo date('Y-m-d H:i:s'); ?></span>
        </div>
        <hr>
        <h4>Info Ekspeditur</h4>
        <div>
            <p>
                Ekspeditur : <b><?php echo $ekspeditur['nama']; ?></b><br>
                No. Kendaraan : <?php echo $detail['nopol']; ?><br>
                Nama Supir : <?php echo $detail['nama_supir']; ?><br>
                Telpon/Hp Supir : <?php echo $detail['hp_supir']; ?><br>
                Tanggal SJE : <?php echo $detail['created_date']; ?>
            </p>
        </div>
        <h4>Daftar Transaksi</h4>
        <?php if($list_transaksi) { ?> 
        <table class="table-products" border="1" border-collapse="collapse" width="100%">
            
            <thead>
                <tr>
                    <th>NO</th>
                    <th>KODE BUKU</th>
                    <th>JUDUL BUKU</th>
                    <th>BERAT (KG)</th>
                    <th>KOLI</th>
                    <th>JUMLAH</th>
                    <th>TOTAL BERAT</th>
                    <th>JUMLAH PER KOLI</th>
                    <th>BUNTUT</th>
                </tr>
            </thead>
            <tbody>
            <?php 
                $i=1; 
                $tot_berat = 0;
                $tot_jumlah = 0;
                $tot_total_berat = 0;
                $tot_jumlah_per_koli = 0;
                $tot_jumlah_sisa = 0;
                foreach($list_transaksi as $row) { 
            ?>
                <tr>
                    <td><center><?php echo $i; ?></center></td>
                    <td><center><?php echo $row->kode_buku; ?></center></td>
                    <td><?php echo $row->judul_buku; ?></td>
                    <td><center><?php echo $row->berat; ?></center></td>
                    <td><center><?php echo $row->koli; ?></center></td>
                    <td><center><?php echo $row->jumlah; ?></center></td>
                    <td><center><?php echo $row->total_berat; ?></center></td>
                    <td><center><?php echo $row->jumlah_per_koli; ?></center></td>
                    <td><center><?php echo $row->jumlah_sisa; ?></center></td>
                </tr>
            <?php 
                $i++;
                $tot_berat += $row->berat;
                $tot_jumlah += $row->jumlah;
                $tot_total_berat += $row->total_berat;
                $tot_jumlah_per_koli += $row->jumlah_per_koli;
                $tot_jumlah_sisa += $row->jumlah_sisa;
                } 
            ?>
                <tr>
                    <td colspan="3" style="text-align:right;"><strong>T o t a l&nbsp;</strong></td>
                    <!-- <td><center><strong><?php echo $tot_berat; ?></strong></center></td> -->
                    <td></td>
                    <td></td>
                    <td><center><strong><?php echo $tot_jumlah; ?></strong></center></td>
                    <td><center><strong><?php echo $tot_total_berat; ?></strong></center></td>
                    <td><center><strong><?php echo $tot_jumlah_per_koli; ?></strong></center></td>
                    <td><center><strong><?php echo $tot_jumlah_sisa; ?></strong></center></td>
                </tr>
            </tbody>
        </table>
        <?php } ?>
        <br /><br />
        <table class="footer" width="100%">
            <tr>
                <td align="center" width="25%">Petugas Ekspeditur</td>
                <td align="center" width="50%">Petugas Gudang</td>
                <td align="center" width="25%">Penerima</td>
            </tr>
            <tr><td colspan="2"><br /><br /><br /><br /></td></tr>
            <tr>
                <td align="center">( ................................. )</td>
                <td align="center">( ................................. )</td>
                <td align="center">( ................................. )</td>
            </tr>
        </table>
        <div style="font-size:9px;margin-top:50px;">
            Asli: Ekspeditur untuk penagihan<br>
            Copy 1: Arsip Gudang Pengirim<br>
            Copy 2: Security<br>
            Copy 3: Penerima
        </div>
    </body>
</html>