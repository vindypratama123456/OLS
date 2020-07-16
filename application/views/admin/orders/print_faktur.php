<!DOCTYPE html>
<html>
<head>
	<title>Cetak Faktur Pajak #<?php echo $detil['reference']; ?></title>
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
        table.konten, table.konten th, table.konten td {
            border: 1px solid #000;
            padding: 3px;
        }
        table.polos, table.polos th, table.polos td {
            border: 0;
        }
        table.subkonten, table.subkonten tr, table.subkonten th, table.subkonten td {
            border: 1px solid #000;
            padding: 3px;
        }
        .title {
        	font-size: 16px;
        	padding-top: 30px;
        	text-align: center;
        }
    </style>
</head>
<body onload="window.print();">
	<p class="title"><b>FAKTUR PAJAK</b></p>
	<table width="100%" class="konten">
		<tr>
			<td colspan="3">Kode dan Nomor Seri Faktur Pajak: &nbsp;&nbsp;&nbsp; <b>080.003.14-33443044</b> <span style="float:right;"><b>PBS.4408007.14</b></span></td>
		</tr>
		<tr>
			<td colspan="3">Pengusaha Kena Pajak</td>
		</tr>
		<tr>
			<td colspan="3">
				<table class="polos" width="100%">
					<tr><td width="15%">N a m a</td><td width="2%">:</td><td width="83%">PT GRAMEDIA</td></tr>
					<tr><td>Alamat</td><td>:</td><td>Jl. Palmerah Selatan No. 22 Gelora, Tanah Abang, Jakarta Pusat, DKI Jakarta Raya</td></tr>
					<tr><td>NPWP</td><td>:</td><td>01.002.689.6-092.000</td></tr>
				</table>
			</td>
		</tr>
		<tr>
			<td colspan="3">Pembeli Pajak Kena Pajak/Penerima Jasa Kena Pajak</td>
		</tr>
		<tr>
			<td colspan="3">
				<table class="polos" width="100%">
					<tr><td width="15%">N a m a</td><td width="2%">:</td><td width="83%"><?php echo $customer['name']; ?></td></tr>
					<tr><td>Alamat</td><td>:</td><td><?php echo $customer['alamat']; ?></td></tr>
					<tr><td>NPWP</td><td>:</td><td>.........</td></tr>
				</table>
			</td>
		</tr>
		<tr>
			<th width="5%">No. Urut</th>
			<th width="65%">Nama Barang Kena Pajak/Jasa Kena Pajak</th>
			<th width="30%">Harga Jual/Penggantian/Uang Muka/Termin<br />(Rp)</th>
		</tr>
		<?php 
            $i=1; 
            $tot_item = 0;
            $tot_price = 0;
            foreach($listproducts as $row) { 
        ?>
		<tr>
			<td align="center"><?php echo $i ?></td>
			<td>
				<table class="polos" width="100%">
					<tr>
						<td width="85%">
							<?php
			                    $params = array(
			                        'field' => 'reference',
			                        'table' => 'product',
			                        'key' => 'id_product',
			                        'data' => $row->product_id,
			                    );
			                    $isbn = get_data($params);
			                    echo $row->product_name;
			                    echo '<br />(ISBN: '.$isbn.')';
			                ?>
						</td>
						<td align="center" width="2%"><?php echo $row->product_quantity; ?></td>
						<td align="right" width="13%">x <?php echo toRupiah($row->unit_price); ?></td>
					</tr>
				</table>
			</td>
			<td align="right"><?php echo toRupiah($row->total_price); ?></td>
		</tr>
		<?php 
            $i++;
            $tot_item += $row->product_quantity;
            $tot_price += $row->total_price;
            } 
        ?>
		<tr>
			<td colspan="3">
				<table align="center" class="subkonten" width="30%">
					<tr>
						<td align="center">PPN DIBEBASKAN<br >PP No 38 TAHUN 2003</td>
					</tr>
				</table>
			</td>
		</tr>
		<tr>
			<td colspan="2">Harga Jual/<strike>Penggantian/Uang Muka/Termin</strike> *)</td>
			<td align="right"><?php echo toRupiah($tot_price); ?></td>
		</tr>
		<tr>
			<td colspan="2">Dikurangi Potongan Harga</td>
			<td align="right">0</td>
		</tr>
		<tr>
			<td colspan="2">Dikurangi Uang Muka yang sudah diterima</td>
			<td align="right">0</td>
		</tr>
		<tr>
			<td colspan="2">Dasar Pengenaan Pajak</td>
			<td align="right"><?php echo toRupiah($tot_price); ?></td>
		</tr>
		<tr>
			<td colspan="2">PPN = 10% x Dasar Pengenaan Pajak</td>
			<td align="right"><?php echo toRupiah(0.1*$tot_price); ?></td>
		</tr>
		<tr>
			<td colspan="3">
				<table class="polos" width="100%">
					<tr>
						<td width="70%">Pajak Penjualan Atas Barang Mewah</td>
						<td width="30%">Jakarta, <?php echo tanggalIndo('tanggal'); ?> <?php tanggalIndo('bulan'); ?> <?php echo date('Y'); ?></td>
					</tr>
					<tr>
						<td>
							<table class="subkonten" border="1" width="50%">
								<tr>
									<td align="center">Tarif</td>
									<td align="center">DPP</td>
									<td align="center">PPn BBM</td>
								</tr>
								<tr>
									<td align="center">............. %</td>
									<td align="right">Rp .............</td>
									<td align="right">Rp..............</td>
								</tr>
								<tr>
									<td align="center">............. %</td>
									<td align="right">Rp .............</td>
									<td align="right">Rp..............</td>
								</tr>
								<tr>
									<td align="center">............. %</td>
									<td align="right">Rp .............</td>
									<td align="right">Rp..............</td>
								</tr>
								<tr>
									<td align="center">............. %</td>
									<td align="right">Rp .............</td>
									<td align="right">Rp..............</td>
								</tr>
								<tr>
									<td colspan="2">Jumlah</td>
									<td align="right">Rp. ..............</td>
								</tr>
							</table>
							<br /><br /><br /><br />
						</td>
						<td>
							<br /><br /><br />
							Hari Susanto Surjotedjo
						</td>
					</tr>
				</table>
			</td>
		</tr>
		<tr>
			<td colspan="4" style="font-size:10px;font-style:italic;">*) Coret yang tidak perlu</td>
		</tr>
	</table>
</body>
</html>