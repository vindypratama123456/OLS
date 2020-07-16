<!DOCTYPE html>
<html>
<head>
	<title>Cetak Kwitansi #<?php echo $detil['reference']; ?></title>
	<style type="text/css">
        body {
            font-family: Arial,Tahoma;
            font-size: 14px;
            margin: 0;
            padding: 0;
        }
        table, table th, table td {
            border-collapse: collapse;
        }
        span.title {
        	font-size: 20px;
        	font-weight: bold;
        }
        span.alamat, span.telpon {
        	font-size: 12px;
        }
        h3.kwitansi {
        	font-size: 20px;
        	font-weight: bold;	
        }
    </style>
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
	
	<p align="right">NO. <b><?php echo $detil['reference']; ?></b></p>

	<table width="100%">
		<tr>
			<td width="20%" valign="top">Sudah terima dari</td>
			<td width="80%" valign="top">
				<b><?php echo $customer['name'].' / '.$customer['school_name'].' (NPSN: '.$customer['no_npsn'].')'; ?></b><br />
				<?php echo $customer['alamat']; ?>
			</td>
		</tr>
		<tr>
			<td valign="top">Uang sebanyak</td>
			<td valign="top"><b><i><?php echo terbilang($detil['total_paid']); ?></i></b></td>			
		</tr>
		<tr>
			<td valign="top">Untuk pembayaran</td>
			<td valign="top">
				Nomor Faktur: 080.003.14-33443044<br />
				Buku Kurikulum 2013
			</td>			
		</tr>
		<tr>
			<td colspan="2" align="right">
				<br /><br /><br />
				Jakarta, <?php echo tanggalIndo('tanggal'); ?> <?php tanggalIndo('bulan'); ?> <?php echo date('Y'); ?>
				<br /><br /><br />
			</td>
		</tr>
		<tr>
			<td><b>JUMLAH</b></td>
			<td><b><?php echo toRupiah($detil['total_paid']); ?></b></td>			
		</tr>
	</table>

	<p align="right" style="margin-top:30px;">Hari Susanto Surjotedjo</p>
	
	<p style="font-size:10px;font-style:italic;margin-top:50px;">- Kwitansi ini baru dianggap sah, apabila pembayaran yang dilakukan dengan Giro Cheque sudah diuangkan</p>

</body>
</html>