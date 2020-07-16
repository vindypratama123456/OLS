<!DOCTYPE html>
<html moznomarginboxes mozdisallowselectionprint lang="id">
<head>
    <title>Pesanan Dana #<?php echo $payout_comission->no_pd; ?></title>
    <link rel="stylesheet" type="text/css" href="<?php echo assets_url_backmin('css/cetak.css'); ?>"/>
</head>
<body onload="window.print();">
<div align="center">
    <span class="judul-1">APLIKASI TRANSFER</span>
</div>
<table width="100%" border="0" style="margin-bottom:10px;">
    <tr>
        <td>
            Pesanan Dana (TRANSFER)<br>
            <?php echo $company; ?><br>
            Beban Unit: <?php echo $company; ?>/Buku Sekolah<br>
        </td>
        <td>
            <br>
            No. Pesanan: <?php echo $payout_comission->no_pd; ?><br>
            Tgl.
            Transfer: <?php echo ($payout_comission->transfer_date == '0000-00-00') ? '-' : tgl_indo($payout_comission->transfer_date,
                5); ?>
        </td>
    </tr>
</table>
<table class="table table-bordered table-products" border="1" border-collapse="collapse" width="100%">
    <thead>
    <tr>
        <th style="text-align:center;">No.</th>
        <th style="text-align:center;">Transfer Kepada</th>
        <th style="text-align:center;">Bank/Alamat</th>
        <th style="text-align:center;">Sandi BI</th>
        <th style="text-align:center;">No. Account</th>
        <th style="text-align:center;">Jml. Kirim</th>
        <th style="text-align:center;">Total Ditransfer</th>
    </tr>
    </thead>
    <tbody>
    <?php
    if ($detail) {
        $num = 1;
        $totTransfer = 0;
        foreach ($detail as $data) {
            foreach ($data['orders'] as $row => $value) {
                ?>
                <tr>
                    <?php if ($row == 0) { ?>
                        <td rowspan="<?php echo $data['rows'] ?>" style="text-align:center;">
                            <?php echo $num; ?>
                        </td>
                        <td rowspan="<?php echo $data['rows'] ?>">
                            <?php echo strtoupper($data['nama_rekening']); ?>
                        </td>
                        <td rowspan="<?php echo $data['rows'] ?>" style="text-align:center;">
                            <?php echo strtoupper($data['alias_bank']); ?>
                        </td>
                        <td rowspan="<?php echo $data['rows'] ?>" style="text-align:center;">
                            <?php echo $data['kode_bank']; ?>
                        </td>
                        <td rowspan="<?php echo $data['rows'] ?>" style="text-align:center;">
                            <?php echo $data['no_rekening']; ?>
                        </td>
                    <?php } ?>
                    <td style="text-align:right;">
                        <?php echo toRupiah($value['total_amount']); ?>
                    </td>
                    <?php if ($row == 0) { ?>
                        <td rowspan="<?php echo $data['rows'] ?>" style="text-align:right;">
                            <?php echo toRupiah($data['total_amount']); ?>
                        </td>
                    <?php } ?>
                </tr>
                <?php
            }
            $num++;
            $totTransfer += $data['total_amount'];
        }
    }
    ?>
    <tr>
        <td colspan="6" style="text-align:center;">TOTAL TRANSFER</td>
        <td style="text-align:right;"><?php echo toRupiah($totTransfer); ?></td>
    </tr>
    </tbody>
</table>
<table width="100%" style="margin-top:10px;">
    <tr>
        <td width="25%" style="text-align:center;">Dir. Keuangan</td>
        <td width="25%" style="text-align:center;">Sales 2 Vice GM</td>
        <td width="25%" style="text-align:center;">Penyusun</td>
        <td width="25%" style="text-align:center;">Pelaksana/Pemesan</td>
    </tr>
    <tr style="height:50px;"></tr>
    <tr>
        <td width="25%" style="text-align:center;">( ....................... )</td>
        <td width="25%" style="text-align:center;">Irawan Sukma</td>
        <td width="25%" style="text-align:center;">Sinta Ekawati</td>
        <td width="25%" style="text-align:center;">( ....................... )</td>
    </tr>
</table>
</body>
</html>