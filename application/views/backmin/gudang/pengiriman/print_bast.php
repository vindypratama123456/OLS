<!DOCTYPE html>
<html moznomarginboxes mozdisallowselectionprint>
    <head>
        <title>BAST #<?php echo $detil['kode_pesanan']; ?></title>
        <link rel="stylesheet" type="text/css" href="<?php echo assets_url_backmin('css/cetak.css'); ?>"/>
        <style>
            @page { size 8.5in 11in; margin: 2cm }
            div.page { page-break-after: always }
        </style>
    </head>
    <body onload="window.print();">
        <div class="page">
            <div id="logo-content">
                <?php if ($order['type']=='Peminatan SMK') { ?>
                    <img src="<?php echo assets_url('backmin/img/logo-mitra-edukasi-nusantara.jpeg'); ?>" id="logo-kiri" width="120" height="120"/>
                <?php } else { ?>
					<?php if ($order['date_add'] < '2019-01-01' ) { ?>
                    <img src="<?php echo assets_url('backmin/img/logo-printing.png'); ?>" id="logo-kiri" width="180" height="85"/>
					<?php } else { ?>
					<img src="<?php echo assets_url('backmin/img/logo-mitra-edukasi-nusantara.jpeg'); ?>" id="logo-kiri" width="120" height="120"/>
					<?php } ?>	
                <?php } ?>
                <img src="<?php echo assets_url('backmin/img/logo-kg-gom.png'); ?>" id="logo-kanan" width="200" height="30"/>
            </div>
            <br><br><br><br><br><br>
            <div align="center">
                <span class="judul-1">BERITA ACARA SERAH TERIMA
                 <?php if($order['kirim_parsial_accept_by_id'] != null || $order['kirim_parsial_accept_by_id'] != ''){ echo 'PARSIAL'; }?></span><br />
                <span class="judul-2"><?php echo strtoupper($listproducts[0]->type . ' (' . $listproducts[0]->type_alias . ')'); ?></span><br />
                <p style="margin-top:0;">Kode Pesanan: <b>#<?php echo $detil['kode_pesanan']; ?></b></p>
            </div>
            <hr>
            <p style="margin:10px 0 10px 0;">Pada hari ini, .............. Tanggal: ...... Bulan: ...... Tahun: ...... yang bertanda tangan di bawah ini:</p>
            <table width="100%" border="0">
                <tr>
                    <td rowspan="4" valign="top" width="3%">1.</td>
                    <td width="25%">Nama</td>
                    <td width="1%">:</td>
                    <td width="71%"></td>
                </tr>
                <tr>
                    <td>Jabatan</td>
                    <td>:</td>
                    <td></td>
                </tr>
                <tr>
                    <td>Perusahaan</td>
                    <td>:</td>
                    <td>PT. <?php if ($order['date_add'] < '2019-01-01' ) {
					echo ($order['type']=='Peminatan SMK') ? 'MITRA EDUKASI NUSANTARA' : 'GRAMEDIA'; }
					else { 
					echo 'MITRA EDUKASI NUSANTARA';}?></td>
                </tr>
                <tr>
                    <td valign="top">Alamat</td>
                    <td valign="top">:</td>
                    <td valign="top"><?php if ($order['date_add'] < '2019-01-01' ) {
					echo ($order['type']=='Peminatan SMK') ? 'Jl. Agus Salim Ex Terminal B No. 47 Purwodinatan, Semarang, Jawa Tengah' : 'Jl. Palmerah Selatan No. 22 Gelora, Tanah Abang, Jakarta Pusat, DKI Jakarta Raya'; }
					else {
					echo 'Jl. Agus Salim Ex Terminal B No. 47 Purwodinatan, Semarang, Jawa Tengah';}?></td>
                </tr>
                <tr>
                    <td colspan="4">Selanjutnya disebut <b>PIHAK KESATU</b></td>
                </tr>
                <tr><td colspan="4"></td></tr>
                <tr>
                    <td rowspan="5" valign="top" width="3%">2.</td>
                    <td width="25%"></td>
                    <td width="1%"></td>
                    <td width="71%"></td>
                </tr>
                <tr>
                    <td>NIP/Jabatan</td>
                    <td>:</td>
                    <td>Kepala Sekolah</td>
                </tr>
                <tr>
                    <td>Sekolah</td>
                    <td>:</td>
                    <td><?php echo $customer['school_name']; ?></td>
                </tr>
                <tr>
                    <td valign="top">Alamat</td>
                    <td valign="top">:</td>
                    <td valign="top"><?php echo $customer['alamat']; ?></td>
                </tr>
                <tr>
                    <td>Kabupaten/Kota</td>
                    <td>:</td>
                    <td><?php echo $customer['kabupaten']; ?></td>
                </tr>
                <tr>
                    <td colspan="4">Selanjutnya disebut <b>PIHAK KEDUA</b></td>
                </tr>
            </table>
            <p>Selanjutnya berdasarkan Surat Pemesanan <?php echo $listproducts[0]->type . ' (' . $listproducts[0]->type_alias . ')'; ?> pada TANGGAL: <?php echo tgl_indo($order['date_add'], 2); ?>, <b>PIHAK KESATU</b> menyerahkan <?php echo $listproducts[0]->type . ' (' . $listproducts[0]->type_alias . ')'; ?> kepada <b>PIHAK KEDUA</b> dengan perincian sbb:</p>
            <?php if($listproducts) { ?>
                <table class="table table-bordered table-products" border="1" border-collapse="collapse" width="100%">
                    <thead>
                    <tr>
                        <th>NO</th>
                        <th>JUDUL BUKU</th>
                        <th>KELAS</th>
                        <th>JUMLAH<br>(Eks)</th>
                        <th>HARGA<br>SATUAN</th>
                        <th>TOTAL</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php
                    $i=1;
                    $tot_item = 0;
                    $tot_price = 0;
                    foreach($listproducts as $row) {
                        ?>
                        <tr>
                            <td class="text-center"><center><?php echo $i; ?></center></td>
                            <td><?php echo $row->judul_buku.'<br>(ISBN: '.$row->isbn.')'; ?></td>
                            <td class="text-center"><center><?php echo $row->kelas; ?></center></td>
                            <td class="text-center"><center><?php echo $row->kuantitas; ?></center></td>
                            <td style="text-align:right;"><?php echo toRupiah($row->harga_satuan); ?></td>
                            <td style="text-align:right;"><?php echo toRupiah($row->total_harga); ?></td>
                        </tr>
                        <?php
                        $i++;
                        $tot_item += $row->kuantitas;
                        $tot_price += $row->total_harga;
                    }
                    ?>
                    <tr>
                        <td colspan="3" class="text-right"><strong><center>Jumlah</center></strong></td>
                        <td class="text-right"><center><strong><?php echo $tot_item; ?></strong></center></td>
                        <td colspan="2" style="text-align:right;"><strong><?php echo toRupiah($tot_price); ?></strong></td>
                    </tr>
                    </tbody>
                </table>
            <?php } ?>
            <p style="margin-bottom:10px;">Barang sebagaimana disebut diatas telah diterima dalam keadaan baik oleh <b>PIHAK KEDUA</b>.<br />Demikian Berita Acara Serah Terima <?php echo $listproducts[0]->type . ' (' . $listproducts[0]->type_alias . ')'; ?> ini dibuat dengan sebenarnya dalam rangkap 2 (dua) untuk dapat dipergunakan sebagaimana mestinya.</p>
            <table class="footer" width="100%">
                <tr>
                    <td width="50%"><b>PENERIMA</b>,</span></td>
                    <td width="49%"><span style="float:right;margin-right:8px;"><b>YANG MENYERAHKAN</b>,</span></td>
                </tr>
                <tr>
                    <td width="50%">KEPALA SEKOLAH</span></td>
                    <td width="49%"><span style="float:right;margin-right:9px;">PIMPINAN PT. 
					<?php if ($order['date_add'] < '2019-01-01' ) {
					echo ($order['type']=='Peminatan SMK') ? 'MITRA EDUKASI NUSANTARA' : 'GRAMEDIA'; }
					else { 
					echo 'MITRA EDUKASI NUSANTARA';}?></span></td>
                </tr>
                <tr>
                    <td></td>
                    <td></td>
                </tr>
                <tr><td colspan="2"><br /><br /><br /></td></tr>
                <tr>
                    <td>( <?php echo $customer['name'] ? $customer['name'] : '..........................................'; ?> )</td>
                    <td><span style="float:right;">( .......................................... )</span></td>
                </tr>
                <tr><td colspan="2"><br /><br /></td></tr>
            </table>
        </div>
        <div class="page">
            <div id="logo-content">
                <?php if ($order['type']=='Peminatan SMK') { ?>
                <img src="<?php echo assets_url('backmin/img/logo-mitra-edukasi-nusantara.jpeg'); ?>" id="logo-kiri" width="120" height="120"/>
                <?php } else { ?>
				
				<?php if ($order['date_add'] < '2019-01-01' ) { ?>
                <img src="<?php echo assets_url('backmin/img/logo-printing.png'); ?>" id="logo-kiri" width="180" height="85"/>
				<?php } else { ?>
				<img src="<?php echo assets_url('backmin/img/logo-mitra-edukasi-nusantara.jpeg'); ?>" id="logo-kiri" width="120" height="120"/>
				<?php } ?>
				
                <?php } ?>
                <img src="<?php echo assets_url('backmin/img/logo-kg-gom.png'); ?>" id="logo-kanan" width="200" height="30"/>
            </div>
            <br><br><br><br><br><br><br><br>
            <div align="center">
                <span class="judul-1">TIDAK MENERIMA PEMBAYARAN SECARA TUNAI</span><br><br><br>
                <span class="judul-2">Hanya menerima pembayaran melalui BRI Virtual Account<br>atau transfer ke rekening Bank atas nama PT. 
                    <?php 
                        if ($order['date_add'] < '2019-01-01' ) 
                        {
                            echo ($order['type']=='Peminatan SMK') ? 'Mitra Edukasi Nusantara' : 'Gramedia'; 
                        }
                        else 
                        { 
                            echo 'Mitra Edukasi Nusantara';
                        }
                    ?>
                </span>
            </div>
            <br><br>
            <p align="center"><b>LANGKAH-LANGKAH PEMBAYARAN MELALUI VIRTUAL ACCOUNT</b></p>
            <ol>
                <li><b>Melalui ATM BRI</b>
                    <ul>
                        <li>Masukan PIN</li>
                        <li>Pilih menu -> Transaksi lain -> Pembayaran -> Lainnya -> BRIVA</li>
                        <li>Masukan nomor BRI Virtual Account 
                        <?php 
                            if ($order['date_add'] < '2019-01-01' ) 
                            {
                                echo ($order['type']=='Peminatan SMK') ? '8002300' : '6002300'; 
                            }
                            else 
                            { 
                                echo '8002300';
                            }
                        ?>
                          + 8 digit NPSN sekolah</li>
                        <li>Masukan jumlah pembayaran</li>
                    </ul>
                </li>
                <li><b>Melalui ATM Bank Lain</b>
                    <ul>
                        <li>Masukan PIN</li>
                        <li>Pilih menu -> Transaksi lain -> Ke rek. Bank lain</li>
                        <li>Masukan kode Bank BRI ‘002’ dan nomor Virtual Account 
                        <?php 
                            if ($order['date_add'] < '2019-01-01' ) 
                            {
                                echo ($order['type']=='Peminatan SMK') ? '8002300' : '6002300'; 
                            }
                            else 
                            { 
                                echo '8002300';
                            }
                        ?>
                          + 8 digit NPSN sekolah</li>
                        <li>Masukan jumlah pembayaran</li>
                    </ul>
                </li>
                <li><b>Melalui Teller Bank BRI</b>
                    <ul>
                        <li>Menggunakan slip penyetoran (warna biru)</li>
                        <li>Pada bagian "Disetor ke" silahkan isi Nomor Rekening = 
                        <?php 
                            if ($order['date_add'] < '2019-01-01' ) 
                            {
                                echo ($order['type']=='Peminatan SMK') ? '8002300' : '6002300'; 
                            }
                            else 
                            { 
                                echo '8002300';
                            }
                        ?>
                         + 8 digit NPSN sekolah, dan Nama = [nama sekolah]</li>
                    </ul>
                </li>
            </ol>
            <br><br>
            <div align="center">
                <p>
                    Jika membutuhkan informasi rekening Bank atas nama PT. 
                    <?php 
                        if ($order['date_add'] < '2019-01-01' ) 
                        {
                            echo ($order['type']=='Peminatan SMK') ? 'Mitra Edukasi Nusantara' : 'Gramedia'; 
                        }
                        else 
                        { 
                            echo 'Mitra Edukasi Nusantara';
                        }
                    ?>
                    ,<br>silahkan menghubungi Bagian Keuangan <b>0819 0537 8533</b> atau <b>0812 1034 5812</b> (Whatsapp/telpon)</p><br>
                <p>
                    <?php 
                        if ($order['date_add'] < '2019-01-01' ) 
                        {
				            echo ($order['type']=='Peminatan SMK') ? 'Mitra Edukasi Nusantara' : 'Gramedia Mitra Edukasi Indonesia';
                        }
				        else 
                        {
                            echo 'Mitra Edukasi Nusantara';				
				        }
                    ?> 
                tidak bertanggung jawab atas pembayaran yang dilakukan melalui tunai<br>ataupun melalui rekening lain selain atas nama PT. 
                    <?php 
                        if ($order['date_add'] < '2019-01-01' ) 
                        {
                            echo ($order['type']=='Peminatan SMK') ? 'Mitra Edukasi Nusantara' : 'Gramedia'; 
                        }
                        else 
                        { 
                            echo 'Mitra Edukasi Nusantara';
                        }
                    ?>
                </p>
            </div>
        </div>
    </body>
</html>