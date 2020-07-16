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
                    <th>KODE PESANAN</th>
                    <th>TUJUAN</th>
                    <th>BERAT (KG)</th>
                    <th>JUMLAH</th>
                    <th>KET.</th>
                </tr>
            </thead>
            <tbody>
            <?php 
                $i=1; 
                $tot_berat = 0;
                $tot_jumlah = 0;
                foreach($list_transaksi as $row) { 
            ?>
                <tr>
                    <td><center><?php echo $i; ?></center></td>
                    <td><center><?php echo $row->detail_kode; ?></center></td>
                    <td><?php echo $row->tujuan.'<br>'.$row->alamat; ?></td>
                    <td><center><?php echo $row->berat; ?></center></td>
                    <td><center><?php echo $row->jumlah; ?></center></td>
                    <td></td>
                </tr>
            <?php 
                $i++;
                $tot_berat += $row->berat;
                $tot_jumlah += $row->jumlah;
                } 
            ?>
                <tr>
                    <td colspan="3" style="text-align:right;"><strong>T o t a l&nbsp;</strong></td>
                    <td><center><strong><?php echo $tot_berat; ?></strong></center></td>
                    <td><center><strong><?php echo $tot_jumlah; ?></strong></center></td>
                    <td></td>
                </tr>
            </tbody>
        </table>
        <?php } ?>
        <br /><br />
        <!-- <table class="footer" width="100%">
            <tr>
                <td width="50%" style="padding-left:15px;">Petugas Ekspeditur</span></td>
                <td width="49%"><span style="float:right;padding-right:25px;">Petugas Gudang</span></td>
            </tr>
            <tr><td colspan="2"><br /><br /><br /><br /></td></tr>
            <tr>
                <td><span style="margin-left:5px;">( ................................. )</span></td>
                <td><span style="float:right;margin-right:5px;">( ................................. )</span></td>
            </tr>
        </table> -->
        
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