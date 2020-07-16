<!DOCTYPE html>
<html moznomarginboxes mozdisallowselectionprint>
<head>
    <title>Bukti Pesanan #<?php echo $detil['reference']; ?></title>
    <link rel="stylesheet" type="text/css" href="<?php echo assets_url_backmin('css/cetak.css'); ?>"/>
</head>
<body onload="print()">
<div align="center">
    <span class="judul-1">TANDA BUKTI PEMESANAN</span><br/>
    <?php
    $order_type = '';
    if ($detailpesanan) {
        $order_type = $detailpesanan[0]->type;
        if (isset($detil['type']) && $order_type !== $detil['type']) {
            $order_type = $order_type.' ('.$detil['type'].')';
        }
    }
    ?>
    <span class="judul-2"><?php echo $order_type ?></span><br/>
    <center>PT. <?php if ($detil['date_add'] < '2019-01-01') {
            echo ($detil['type'] == 'Peminatan SMK') ? 'MITRA EDUKASI NUSANTARA' : 'GRAMEDIA';
        } else {
            echo 'MITRA EDUKASI NUSANTARA';
        }
        ?></center>
    <br/><br/>
</div>
<table width="100%" border="0" style="margin-bottom:10px;">
    <tr>
        <td>
            Kode Pesanan: <b>#<?php echo $detil['reference']; ?></b><br>
            Tanggal Pesan: <?php echo $detil['date_add']; ?><br>
            Tanggal Konfirmasi: <?php echo $detil['tgl_konfirmasi'] ?: '-'; ?><br>
            Jangka Waktu Pengiriman: <?php echo $detil['jangka_waktu'] ? $detil['jangka_waktu'].' Hari' : '-'; ?><br>
            Kesepakatan Buku Sampai di
            Sekolah: <?php echo $detil['kesepakatan_sampai'] ? $detil['kesepakatan_sampai'].' Hari' : '-'; ?>
        </td>
        <td>
            <?php echo $customer['school_name']; ?><br>
            NPSN: <?php echo $customer['no_npsn']; ?><br>
            <?php echo $customer['alamat']; ?><br>
            Kodepos: <?php echo $customer['kodepos']; ?><br>
            Telpon: <?php echo $customer['phone']; ?>
        </td>
    </tr>
</table>
<?php if ($detailpesanan) { ?>
    <table class="table table-bordered table-products" border="1" border-collapse="collapse" width="100%">
        <thead>
        <tr>
            <th>No.</th>
            <th>Judul Buku</th>
            <th>ISBN</th>
            <th>Kelas</th>
            <th>Harga Satuan</th>
            <th>Jumlah</th>
            <th>Harga Total</th>
        </tr>
        </thead>
        <tbody>
        <?php
        $i = 1;
        $tot_item = 0;
        $tot_price = 0;
        foreach ($detailpesanan as $row) {
            ?>
            <tr>
                <td class="text-center" v-align="middle">
                    <center><?php echo $i; ?></center>
                </td>
                <td v-align="middle"><?php echo $row->product_name; ?></td>
                <td class="text-center" v-align="middle">
                    <center><?php echo $row->isbn; ?></center>
                </td>
                <td class="text-center" v-align="middle">
                    <center><?php echo $row->kelas; ?></center>
                </td>
                <td style="text-align:right;" v-align="middle"><?php echo toRupiah($row->unit_price); ?></td>
                <td class="text-center" v-align="middle">
                    <center><?php echo $row->product_quantity; ?></center>
                </td>
                <td style="text-align:right;" v-align="middle"><?php echo toRupiah($row->total_price); ?></td>
            </tr>
            <?php
            $i++;
            $tot_item += $row->product_quantity;
            $tot_price += $row->total_price;
        }
        ?>
        <tr>
            <td colspan="5" class="text-right"><strong>
                    <center>Jumlah</center>
                </strong></td>
            <td class="text-right">
                <center><strong><?php echo $tot_item; ?></strong></center>
            </td>
            <td style="text-align:right;"><strong><?php echo toRupiah($tot_price); ?></strong></td>
        </tr>
        </tbody>
    </table>
<?php } ?>
<table width="50%" class="table-products" style="margin:20px 0;">
    <tr>
        <?php
        $tahunPesan = $result = substr($detil['date_add'], 0, 4);
        $prefixVA = $tahunPesan == '2019' ? config_item('va_men') : config_item('va_grm');
		$NamaPT = $tahunPesan == '2019' ? 'PT. Mitra Edukasi Nusantara' : 'PT. Gramedia' ;
        ?>
        <td>Mohon untuk melakukan pembayaran melalui transfer bank ke <u><i>Nomor Virtual Account BRI</i></u>
            <b><?php echo $prefixVA.$customer['no_npsn']; ?></b> atas nama <b><?php echo $NamaPT; ?></b> setelah buku diterima.
        </td>
    </tr>
</table>
</body>
</html>