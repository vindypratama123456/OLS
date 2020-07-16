<!DOCTYPE html>
<html>
    <head>
        <style type="text/css">
            body {
                font-family: Verdana,Helvetica,'Arial','Georgia',Serif;
                margin: 0;
                padding: 0;
            }
            span.judul {
                font-size: 15px;
                font-weight: bold;
            }
            p {
                margin: 20px 0;
            }
        </style>
    </head>
    <body>
        <div align="center" style="margin-top:20px;">
            <span class="judul">SURAT PERNYATAAN KESANGGUPAN</span>
        </div>
        <p>Yang bertanda tangan di bawah ini:</p>
        <table width="100%" border="0">
            <tr>
                <td width="25%">Nama</td><td width="1%">:</td><td width="74%"><?php echo $customer['name']; ?></td>
            </tr>
            <tr>
                <td>Nama Sekolah</td><td>:</td><td><?php echo $customer['school_name']; ?></td>
            </tr>
            <tr>
                <td>NIP</td><td>:</td><td><?php echo $customer['nip_kepsek']?:'-'; ?></td>
            </tr>
            <tr>
                <td valign="top">Alamat</td><td valign="top">:</td><td valign="top">
                    <?php echo $customer['alamat'].'<br>'.$customer['desa'].', '.$customer['kecamatan'].', '.$customer['provinsi'].', '.$customer['kodepos']; ?>
                </td>
            </tr>
            <tr>
                <td>Jabatan</td><td>:</td><td>Kepala Sekolah</td>
            </tr>
            <tr>
                <td>Telp/Fax/Email</td><td>:</td><td><?php echo $customer['phone'].' / '.$customer['email']; ?></td>
            </tr>
        </table>
        <br /><br />
        <p>Dengan ini menyatakan kesanggupan untuk melunasi pembayaran pembelian <?php echo $category['name']." (".$category['alias'].")"?> Kode Pesanan <b>#<?php echo $detil['reference']; ?></b> dengan nominal sebesar <b><?php echo toRupiah($detil['total_paid']); ?></b> (<?php echo terbilang($detil['total_paid']); ?>) menggunakan dana BOS (Bantuan Operasional Sekolah) paling lambat 1 minggu setelah termin pencairan dana BOS ke rekening:</p>
        <table width="100%" border="0">
            <tr>
                <td width="35%">Bank</td><td width="1%">:</td><td width="64%"><b>BRI</b></td>
            </tr>
            <tr>
                <td>Nama Pemilik Rekening</td><td>:</td><td><b>PT Gramedia</b></td>
            </tr>
            <tr>
                <td>Nomor Virtual Account BRI</td><td>:</td><td><b>
				<?php
				$tahunPesan = substr($detil['date_add'], 0, 4);
				$prefixVA = $tahunPesan == '2019' ? config_item('va_men') : config_item('va_grm');
				echo config_item('va_grm').$customer['no_npsn'];
				?>
				
				</b></td>
            </tr>
        </table>
        <br /><br />
        <p>Demikian, surat pernyataan kesanggupan ini saya buat dengan sesungguhnya, dan atas kemauan sendiri tanpa ada paksaan dari pihak manapun.</p>
        <br /><br /><br />
        <p style="margin-left:350px;">
            Pembuat Penyataan,
            <br /><br /><br />
            <span style="font-size:9px;">Stempel Sekolah</span><br />
            <span style="font-size:9px;">Materai 6000</span>
            <br /><br /><br />
            <u><?php echo $customer['name']; ?></u><br />
            Nip: <?php echo $customer['nip_kepsek']; ?>
        </p>
        <br /><br /><br /><br />
        <p style="font-size:11px;">
            <u>Catatan:</u><br />
            Bukti Pembayaran dengan mencantumkan <b>Kode Pesanan</b> dapat dikirimkan ke PT Gramedia melalui:<br />
            Email: ar@gramediaprinting.com<br />
            Fax: 021-5323662
        </p>
    </body>
</html>