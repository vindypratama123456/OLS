<!DOCTYPE html>
<html moznomarginboxes mozdisallowselectionprint>
<head>
	<title>Cetak Kwitansi #<?php echo $detil->reference; ?></title>
	<link rel="stylesheet" type="text/css" href="<?php echo assets_url_backmin('css/cetak.css'); ?>"/>
</head>
<body onload="window.print();">
	<table width="100%">
		<tr>
			<td width="5%" valign="top">
				<img src="<?php echo assets_url('img/logo-kwintansi.png'); ?>">
			</td>
			<td width="95%" valign="top">
				<span class="title">PT. GRAMEDIA</span><br />
				<span class="alamat">Jl. Palmerah Selatan No. 22 Gelora, Tanah Abang, Jakarta Pusat, DKI Jakarta Raya</span><br />
				<span class="telpon">Phone: +6221-5483008, 5480888,  Fax: +6221-5482774</span>
			</td>
		</tr>
	</table>
	
	<h3 align="center" class="kwitansi">KWITANSI</h3>
	
	<p align="right">NO. <b><?php echo $detil->reference; ?></b></p>

	<table width="100%">
		<tr>
			<td valign="top" colspan="2">
				<b><?php echo $customer->school_name; ?></b><br>
				<?php echo $customer->alamat.", ".$customer->desa.", ".$customer->kecamatan."<br>".$customer->kabupaten.", ".$customer->provinsi.", ".$customer->kodepos; ?>
			</td>
		</tr>
		<tr><td colspan="2"><br></td></tr>
		<tr>
			<td valign="top" colspan="2">
				<b><i>.................................................................................................................................</i></b>
			</td>
		</tr>
		<tr><td colspan="2"><br></td></tr>
		<tr>
			<td valign="top" colspan="2">
				Pembayaran ke- .......... <?php echo strtoupper($product[0]->type . ' (' . $product[0]->type_alias . ')'); ?>
			</td>			
		</tr>
		<tr><td colspan="2"><br><br><br><br></td></tr>
		<tr>
			<td align="right" valign="top" colspan="2">
				.............. , ...............................
			</td>
		</tr>
		<tr><td colspan="2"><br><br><br><br></td></tr>
		<tr>
			<td align="left" valign="top" width="50%">
				<h2><b>Rp. .............................</b></h2>
			</td>
			<td align="right" valign="middle" width="50%">
				<b><?php echo $korwil['name'] ?></b>
			</td>
		</tr>
	</table>
	
	<p style="font-size:8pt; font-style:italic; margin-top:25px; line-height: 18px;">
		Panduan pembayaran Virtual Account <br>
		Melalui ATM BRI : <br>
		1. Masukan kartu ATM BRI <br>
		2. Pilih "Bahasa Indonesia" <br>
		3. Pilih " Lanjutkan <br>
		4. Masukan PIN <br>
		5. Pilih "Transaksi Lain" <br>
		6. Pilih "Pembayaran" <br>
		7. Pilih "Lainnya" <br>
		8. Pilih "BRIVA" <br>
		9. Masukan 15 angka virtual account "6002300+8 digit NPSN SEKOLAH" lalu pilih "BENAR" <br>
		10. Jika sudah sesuai, pilih "YA" <br>
		<b>Sistem akan memverifikasi data yang dimasukan ke sekolah seperti tampak pada layar. <br>
		Jika sekolah sudah meyakini data yang ditampilkan layar telah sesuai maka pilih YA untuk memproses pembayaran.</b> <br>
		<br>
		Melalui ATM selain BRI <br>
		1. Memasukan kartu ATM dan pin <br>
		2. Pilih "Transaksi Lainnya" <br>
		3. Pilih "Transfer" <br>
		4. Pilih "Ke Rek Bank Lain" <br>
		5. Masukan kode bank 3 digit (Catatan kode bank BRI : 002) Pilih "benar" <br>
		6. Masukan jumlah yang ingin ditransfer, lalu pilih "Benar" <br>
	</p>

</body>
</html>