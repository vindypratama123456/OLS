<!DOCTYPE html>
<html>
<head>
    <title>Cetak Invoice #<?php echo $pesanan[0]->reference; ?></title>
    <style type="text/css">
        body {
            font-family: Tahoma;
            margin: 0;
            padding: 0;
        }

        table.table-products, table.table-products th, table.table-products td {
            border: 1px solid black;
            border-collapse: collapse;
        }

        table.footer, table.footer tr, table.footer td {
            border: 0;
            border-collapse: collapse;
        }

        span.judul-1 {
            font-size: 15px;
            font-weight: bold;
        }

        span.judul-2 {
            font-size: 15px;
        }
    </style>
</head>
<body onload="window.print();">

<div align="center">
    <span class="judul-1">TANDA BUKTI PEMESANAN</span><br/>
    <?php
    $order_type = "";
    if ($detailpesanan) {
        $order_type = $detailpesanan[0]->parent_name;
        if (isset($detailpesanan[0]->type_alias) && $order_type != $detailpesanan[0]->type_alias) {
            $order_type = $order_type." (".$detailpesanan[0]->type_alias.")";
        }
    }
    ?>
    <span class="judul-2"><?php echo $order_type ?></span><br/>
    <center>PT. GRAMEDIA</center>
    <br/><br/>
</div>
<table width="100%" border="0" style="margin-bottom:10px;">
    <tr>
        <td width="70%">
            Kode Pesanan: <b>#<?php echo $pesanan[0]->reference; ?></b><br>
            Tanggal Pesan: <?php echo $pesanan[0]->tgl_pesan; ?><br>
            Tanggal Konfirmasi: <?php echo $pesanan[0]->tgl_konfirmasi; ?>
            <?php if ($pesanan[0]->jangka_waktu) {
                echo '<br>Jangka Waktu Pengiriman: '.$pesanan[0]->jangka_waktu.' Hari<br>';
            } ?>
            <?php if ($pesanan[0]->kesepakatan_sampai) {
                echo 'Kesepakatan Buku Sampai di Sekolah: '.$pesanan[0]->kesepakatan_sampai.' Hari';
            } ?>
        </td>
        <td width="70%">
            <?php echo $pesanan[0]->school_name; ?><br>
            NPSN: <?php echo $pesanan[0]->no_npsn; ?><br>
            <?php echo $pesanan[0]->alamat; ?><br>
            Kodepos: <?php echo $pesanan[0]->kodepos; ?><br>
            Telpon: <?php echo $pesanan[0]->phone; ?>
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
                <td class="text-center">
                    <center><?php echo $i; ?></center>
                </td>
                <td><?php echo $row->product_name; ?></td>
                <td class="text-center">
                    <center><?php echo $row->reference; ?></center>
                </td>
                <td class="text-center">
                    <center><?php echo $row->category; ?></center>
                </td>
                <td style="text-align:right;"><?php echo toRupiah($row->unit_price); ?></td>
                <td class="text-center">
                    <center><?php echo $row->product_quantity; ?></center>
                </td>
                <td style="text-align:right;"><?php echo toRupiah($row->total_price); ?></td>
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
        $tahunPesan = $result = substr($pesanan[0]->tgl_pesan, 0, 4);
        $prefixVA = $tahunPesan == '2019' ? config_item('va_men') : config_item('va_grm');
        ?>
        <td>Mohon untuk melakukan pembayaran melalui transfer bank ke nomor rekening <b>BRI</b> <i><u>Virtual
                    Account</u></i>
            <b><?php echo $prefixVA.$this->session->userdata('data_user')['npsn']; ?></b atas nama <b>PT. Gramedia</b>
        </td>
    </tr>
</table>

</body>
</html>