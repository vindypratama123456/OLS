<!DOCTYPE html>
<html moznomarginboxes mozdisallowselectionprint>
    <head>
        <title>Pesanan #<?php echo $detail['reference']; ?></title>
        <link rel="stylesheet" type="text/css" href="<?php echo assets_url_backmin('css/cetak.css'); ?>"/>
    </head>
    <body onload="window.print();">
        <div id="logo-content">
            <?php if ($detail['type']=='Peminatan SMK') { ?>
            <img src="<?php echo assets_url('backmin/img/logo-mitra-edukasi-nusantara.jpeg'); ?>" id="logo-kiri" width="120" height="120"/>
            <?php } else { ?>
            <img src="<?php echo assets_url('backmin/img/logo-printing.png'); ?>" id="logo-kiri" width="180" height="85"/>
            <?php } ?>
            <img src="<?php echo assets_url('backmin/img/logo-kg-gom.png'); ?>" id="logo-kanan" width="200" height="30"/>
        </div>
        <br><br><br><br><br>
        <div align="center">
            <span class="judul-1">DETIL PESANAN</span><br />
            <span class="judul-1"><?php echo strtoupper($listproducts[0]->type . ' (' . $listproducts[0]->type_alias . ')'); ?></span><br />
            <span class="judul-2">Kode Pesanan: <b>#<?php echo $detail['reference']; ?></b></span>
        </div>
        <hr>
        <div>
            <p><?php echo '<b>'.$customer['school_name'].'</b><br>'.$customer['alamat'].'<br />'.$customer['desa'].', '.$customer['kecamatan'].', '.$customer['kabupaten'].'<br />'.$customer['provinsi'].' - '.$customer['kodepos']; ?></p>
            <p>Tanggal Pesan: <?php echo $detail['date_add']; ?><br>
            <?php 
                $jangka_waktu = ($detail['jangka_waktu']!==null) ? $detail['jangka_waktu'] : false;
                if($jangka_waktu) {
            ?>
            Target Kirim: <?php echo '<b>'.date('Y-m-d', strtotime($detail['tgl_konfirmasi'].'+'.$jangka_waktu.' days')).'</b>'; } ?></p>
        </div>
        <?php if($listproducts) { ?> 
        <table class="table-products" border="1" border-collapse="collapse" width="100%">
            <thead>
                <tr>
                    <th>No</th>
                    <th>Judul Buku</th>
                    <th>Kelas</th>
                    <th>Total<br>Eks</th>
                    <th>Eks/<br>Koli</th>
                    <th>Koli<br>Utuh</th>
                    <th>Sisa<br>Eks</th>
                    <th>Ket.</th>
                </tr>
            </thead>
            <tbody>
            <?php
                $i=1; 
                $tot_item = 0;
                $tot_weight = 0;
                foreach($listproducts as $row) {
                    $tot_eks = $row->product_quantity;
                    $eks_koli = $row->koli ? $row->koli : 0;
                    $koli_utuh = 0;
                    $eks_ekor = 0;
                    if($eks_koli !== 0)
                    {
                        $koli_utuh = floor($tot_eks/$eks_koli);
                        $eks_ekor = $tot_eks - ($koli_utuh*$eks_koli);
                    }
            ?>
                <tr>
                    <td><center><?php echo $i; ?></center></td>
                    <td><?php echo $row->product_name.' [<b>'.$row->kode_buku.'</b>]<br />(ISBN: '.$row->isbn.')'; ?></td>
                    <td><center><?php echo $row->kelas; ?></center></td>
                    <td><center><?php echo $tot_eks; ?></center></td>
                    <td><center><?php echo $eks_koli; ?></center></td>
                    <td><center><?php echo $koli_utuh; ?></center></td>
                    <td><center><?php echo $eks_ekor; ?></center></td>
                    <td></td>
                </tr>
            <?php 
                $i++;
                $tot_item += $tot_eks;
                $tot_weight += ($row->weight * $tot_eks);
                } 
            ?>
                <tr>
                    <td colspan="3"><strong><center>Jumlah</center></strong></td>
                    <td><center><strong><?php echo $tot_item; ?></strong></center></td>
                    <td colspan="4"></td>
                </tr>
                <tr>
                    <td colspan="3"><strong><center>Berat</center></strong></td>
                    <td><center><strong><?php echo number_format(($tot_weight), 2, ',', '.'); ?> Kg</strong></center></td>
                    <td colspan="4"></td>
                </tr>
            </tbody>
        </table>
        <?php } ?>
    </body>
</html>