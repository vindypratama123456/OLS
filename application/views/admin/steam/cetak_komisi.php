<!DOCTYPE html>
<html moznomarginboxes mozdisallowselectionprint>
<head>
    <title>Approval Komisi #<?php echo $payout_comission[0]->sap_no; ?></title>
    <link rel="stylesheet" type="text/css" href="<?php echo assets_url_backmin('css/cetak.css'); ?>"/>
</head>
<body onload="window.print();">
    <div id="logo-content">
        <img src="<?php echo assets_url('backmin/img/logo-printing.png'); ?>" id="logo-kiri" width="180" height="85"/>
        <img src="<?php echo assets_url('backmin/img/logo-kg-gom.png'); ?>" id="logo-kanan" width="200" height="30"/>
    </div>
    <br><br><br><br><br>
    <div align="center">
        <span class="judul-1">APPROVAL KOMISI</span><br />
        <span class="judul-2">No. Proses : <b>#<?php echo $payout_comission[0]->sap_no; ?></b></span><br ?>
        <span class="judul-3">Tgl Cetak: <?php echo date('Y-m-d H:i:s'); ?></span>
    </div>
    <hr>

    <h4>Daftar Komisi</h4>
    <table class="table-products" border="1" border-collapse="collapse" width="100%">
        <thead>
            <tr>
                <th>No.</th>
                <th>Transfer Kepada</th>
                <th>Nama Rekening</th>
                <th>Bank/Alamat</th>
                <th>Sandi BI</th>
                <th>No. Account</th>
                <th>Kode Pesanan</th>
                <th>Nilai Pesanan</th>
                <th>% Komisi</th>
                <th>Komisi Awal</th>
                <th>% PPh</th>
                <th>Nilai PPh</th>
                <th>Nilai Komisi</th>
                <th>Total Komisi</th>
            </tr>
        </thead>
        <tbody>
            <?php
            if (!empty($detail)) {
                $num = 1;
                $totPesanan = 0;
                $totKomisiAwal = 0;
                $totNilaiPPH = 0;
                $totNilaiKomisi = 0;
                $totTransfer = 0;
                foreach ($detail as $data) {
                    foreach ($data['orders'] as $row => $value) {
                        ?>
                        <tr>
                            <?php if ($row == 0) { ?>
                                <td rowspan="<?php echo $data['rows'] ?>" align="center">
                                    <?php echo $num; ?>
                                </td>
                                <td rowspan="<?php echo $data['rows'] ?>">
                                    <?php echo strtoupper($data['nama']); ?>
                                </td>
                                <td rowspan="<?php echo $data['rows'] ?>">
                                    <?php echo strtoupper($data['nama_rekening']); ?>
                                </td>
                                <td rowspan="<?php echo $data['rows'] ?>" align="center">
                                    <?php echo strtoupper($data['alias_bank']); ?>
                                </td>
                                <td rowspan="<?php echo $data['rows'] ?>" align="center">
                                    <?php echo $data['kode_bank']; ?>
                                </td>
                                <td rowspan="<?php echo $data['rows'] ?>" align="center">
                                    <?php echo $data['no_rekening']; ?>
                                </td>
                            <?php } ?>
                            <td align="center">
                                <?php echo $value['kode_pesanan']; ?>
                            </td>
                            <td align="right">
                                <?php echo toRupiah($value['nilai_pesanan']); ?>
                            </td>
                            <td align="center">
                                <?php echo ($value['persen_komisi'] * 100) . '%'; ?>
                            </td>
                            <td align="right">
                                <?php echo toRupiah(round($value['persen_komisi'] * $value['nilai_pesanan'])); ?>
                            </td>
                            <td align="center">
                                <?php echo ($value['persen_pph'] * 100) . '%'; ?>
                            </td>
                            <td align="right">
                                <?php echo toRupiah($value['nilai_pph']); ?>
                            </td>
                            <td align="right">
                                <?php echo toRupiah($value['total_amount']); ?>
                            </td>
                            <?php if ($row == 0) { ?>
                                <td rowspan="<?php echo $data['rows'] ?>" align="right">
                                    <?php echo toRupiah($data['total_amount']); ?>
                                </td>
                            <?php } ?>
                        </tr>
                        <?php
                        $totPesanan+=$value['nilai_pesanan'];
                        $totKomisiAwal+=round($value['persen_komisi'] * $value['nilai_pesanan']);
                        $totNilaiPPH+=$value['nilai_pph'];
                        $totNilaiKomisi+=$value['total_amount'];
                    }
                    $num++;
                    $totTransfer+=$data['total_amount'];
                }
            }
            else
            {
                echo "<script>alert('Terjadi kesalahan pada data Sales atau Influencer. Mohon dilengkapi.');</script>";      
                $totPesanan = 0;
                $totKomisiAwal = 0;
                $totNilaiPPH = 0;
                $totNilaiKomisi = 0;
                $totTransfer = 0;                             
            }
            ?>
            <tr>
                <td colspan="7" align="center">T O T A L </td>
                <td align="right"><?php echo toRupiah($totPesanan); ?></td>
                <td></td>
                <td align="right"><?php echo toRupiah($totKomisiAwal); ?></td>
                <td></td>
                <td align="right"><?php echo toRupiah($totNilaiPPH); ?></td>
                <td align="right"><?php echo toRupiah($totNilaiKomisi); ?></td>
                <td align="right"><?php echo toRupiah($totTransfer); ?></td>
            </tr>
        </tbody>
    </table>
    <br /><br />
    <table class="footer" width="100%">
        <tr>
            <td align="center" width="25%"><br>Menyetujui,</td>
            <td align="center" width="50%"><br>Mengetahui,</td>
            <td align="center" width="25%">Jakarta, <?php echo tgl_indo(date('Y-m-d H:i:s'), 2); ?><br>Penyusun</td>
        </tr>
        <tr><td colspan="2"><br /><br /><br /><br /></td></tr>
        <tr>
            <td align="center">( ................................. )</td>
            <td align="center">( ................................. )</td>
            <td align="center">( ................................. )</td>
        </tr>
    </table>
    <!-- <div style="font-size:9px;margin-top:50px;">
        Asli: Ekspeditur untuk penagihan<br>
        Copy 1: Arsip Gudang Pengiriman<br>
        Copy 2: Security
    </div> -->
</body>
</html>