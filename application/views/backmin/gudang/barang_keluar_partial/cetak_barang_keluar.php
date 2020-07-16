<!DOCTYPE html>
<html moznomarginboxes mozdisallowselectionprint>
    <head>
        <title>ID #<?php echo $detail['id_transaksi']; ?></title>
        <link rel="stylesheet" type="text/css" href="<?php echo assets_url_backmin('css/cetak.css'); ?>"/>
    </head>
    <body onload="window.print();">
        <div id="logo-content">
            <img src="<?php echo assets_url('backmin/img/logo-printing.png'); ?>" id="logo-kiri" width="180" height="85"/>
            <img src="<?php echo assets_url('backmin/img/logo-kg-gom.png'); ?>" id="logo-kanan" width="200" height="30"/>
        </div>
        <br><br><br><br><br>
        <div align="center">
            <span class="judul-1">DETAIL BARANG KELUAR</span><br />
            <span class="judul-2">ID: <b>#<?php echo $detail['id_transaksi']; ?></b></span><br ?>
            <span class="judul-3">Tgl Cetak: <?php echo date('Y-m-d H:i:s'); ?></span>
        </div>
        <hr>
        <h3>Info Gudang Tujuan</h3>
        <div>
            <h4><b><?php echo $gudang['nama_gudang']; ?></b></h4>
            <p><?php echo $gudang['alamat_gudang']; ?></p>
            <p>Tanggal Permintaan: <?php echo $detail['created_date']; ?></p>
            <h4>Status</h4>
            <p>Status Pengiriman: <?php echo $status_transaksi; ?></p>
        </div>
        <h4>Daftar Buku</h4>
        <?php if($listproducts) { ?> 
        <table class="table-products" border="1" border-collapse="collapse" width="100%">
            <thead>
                <tr>
                    <th width="5%"><center>No</center></th>
                    <th width="45%"><center>Judul Buku</center></th>
                    <th width="8%"><center>Kelas</center></th>
                    <th width="7%"><center>Jumlah</center></th>
                    <th width="7%"><center>Koli</center></th>
                    <th width="7%"><center>Total Koli</center></th>
                    <th width="7%"><center>Buntut</center></th>
                    <th width="7%"><center>Berat</center></th>
                    <th width="7%"><center>Total Berat</center></th>
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
                <tr>
                    <td><center><?php echo $i; ?></center></td>
                    <td><?php echo $row->judul_buku.' [<b>'.$row->kode_buku.'</b>]<br />(ISBN: '.$row->isbn.')'; ?></td>
                    <td><center><?php echo $row->kelas; ?></center></td>
                    <td><center><?php echo $row->jumlah; ?></center></td>
                    <td><center><?php echo $row->koli; ?></center></td>
                    <td><center><?php echo $row->total_koli; ?></center></td>
                    <td><center><?php echo $row->sisa_koli; ?></center></td>
                    <td><center><?php echo $row->berat; ?></center></td>
                    <td><center><?php echo $row->total_berat; ?></center></td>
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
                    <td><center><b><?php echo $tot_item; ?></b></center></td>
                    <td> </td>
                    <td><center><b><?php echo $tot_total_koli; ?></b></center></td>
                    <td><center><b><?php echo $tot_sisa_koli; ?></b></center></td>
                    <td><center><b><?php echo $tot_total_berat; ?></b></center></td>
                    <td> </td>

                </tr>
            </tbody>
        </table>
        <?php } ?>
        <br /><br />
        <table class="footer" width="100%">
            <tr>
                <!-- <td width="50%" style="padding-left:15px;">Petugas Ekspeditur</span></td> -->
                <td width="49%"><span style="float:right;padding-right:25px;">Petugas Gudang</span></td>
            </tr>
            <tr><td colspan="2"><br /><br /><br /><br /></td></tr>
            <tr>
                <!-- <td><span style="margin-left:5px;">( ................................. )</span></td> -->
                <td><span style="float:right;margin-right:5px;">( ................................. )</span></td>
            </tr>
        </table>
        <!-- <div style="font-size:9px;margin-top:50px;">
            Asli: Ekspeditur untuk penagihan<br>
            Copy 1: Arsip Gudang Pengiriman<br>
            Copy 2: Security
        </div> -->
    </body>
</html>