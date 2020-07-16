<!DOCTYPE html>
<html>
    <head>
        <title>Cetak BAST #<?php echo $detil['reference']; ?></title>
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
            <span class="judul-1">BERITA ACARA SERAH TERIMA</span><br />
            <span class="judul-2">BUKU KURIKULUM TAHUN 2013</span><br />
            <p>No. <b>#<?php echo $detil['reference']; ?></b></p>
        </div>

        <p style="margin:40px 0 10px 0;">Pada hari ini, <?php tanggalIndo('hari'); ?> Tanggal: <b><?php tanggalIndo('tanggal'); ?></b> Bulan: <b><?php tanggalIndo('bulan'); ?></b> Tahun: <b><?php tanggalIndo('tahun'); ?></b> yang bertanda tangan di bawah ini:</p>

        <table width="100%" border="0">
            <tr>
                <td rowspan="4" valign="top" width="3%">1.</td>
                <td width="25%">Nama</td>
                <td width="1%">:</td>
                <td width="71%"></td>
            </tr>
            <tr>
                <td>NIP/Jabatan</td>
                <td>:</td>
                <td></td>
            </tr>
            <tr>
                <td>Perusahaan</td>
                <td>:</td>
                <td>PT. <?php echo ($detil['type']=='Peminatan SMK') ? 'MITRA EDUKASI NUSANTARA' : 'GRAMEDIA'; ?></td>
            </tr>
            <tr>
                <td valign="top">Alamat</td>
                <td valign="top">:</td>
                <td valign="top">Jl. Palmerah Selatan No. 22 Gelora, Tanah Abang, Jakarta Pusat, DKI Jakarta Raya</td>
            </tr>
            <tr>
                <td colspan="4"><b>Selanjutnya disebut PIHAK KESATU</b></td>
            </tr>
            <tr><td colspan="4"></td></tr>
            <tr>
                <td rowspan="4" valign="top" width="3%">2.</td>
                <td width="25%">Nama</td>
                <td width="1%">:</td>
                <td width="71%"><?php echo $customer['name']; ?></td>
            </tr>
            <tr>
                <td>NIP/Jabatan</td>
                <td>:</td>
                <td>Kepala Sekolah</td>
            </tr>
            <tr>
                <td><b>KABUPATEN/KOTA</b></td>
                <td>:</td>
                <td><?php echo $customer['kabupaten']; ?></td>
            </tr>
            <tr>
                <td valign="top">Alamat</td>
                <td valign="top">:</td>
                <td valign="top"><?php echo $customer['alamat']; ?></td>
            </tr>
            <tr>
                <td colspan="4"><b>Selanjutnya disebut PIHAK KEDUA</b></td>
            </tr>
        </table>

        <p>Selanjutnya berdasarkan Surat Pemesanan Buku Kurikulum 2013 pada TANGGAL: <b><?php echo $detil['date_add']; ?></b> PIHAK KESATU Menyerahkan Buku Kurikulum Tahun 2013 kepada PIHAK KEDUA dengan perincian sbb:</p>

        <?php if($listproducts) { ?> 
        <table class="table table-bordered table-products" border="1" border-collapse="collapse" width="100%">
            <thead>
                <tr>
                    <th>NO</th>
                    <th>JUDUL BUKU</th>
                    <th>JENJANG</th>
                    <th>JUMLAH DITERIMA<br />(Eks)</th>
                    <th>HARGA SATUAN</th>
                    <!--
                    <th>Diskon</th>
                    <th>Harga Akhir</th>
                    -->
                    <th>JUMLAH<br />(Rp)</th>
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
                    <td>
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
                    <td class="text-center"><center><?php echo $jenjang; ?></center></td>
                    <td class="text-center"><center><?php echo $row->product_quantity; ?></center></td>
                    <td style="text-align:right;"><?php echo toRupiah($row->unit_price); ?></td>
                    <?php /*
                    <td class="text-center"><?php echo $row->reduction_percent; ?></center></td>
                    <td class="text-right"><?php echo toRupiah($row->unit_price); ?></td>
                    */ ?>
                    <td style="text-align:right;"><?php echo toRupiah($row->total_price); ?></td>
                </tr>
            <?php 
                $i++;
                $tot_item += $row->product_quantity;
                $tot_price += $row->total_price;
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

        <p style="margin-bottom:50px;">Barang sebagaimana disebut diatas telah diterima dalam keadaan baik oleh PIHAK KEDUA.<br />Demikian Berita Acara Serah Terima BUKU KURIKULUM 2013 ini dibuat dengan sebenarnya dalam rangkap 5 (lima) untuk dapat dipergunakan sebagaimana mestinya.</p>

        <table class="footer" width="100%">
            <tr>
                <td width="50%">Yang Menerima,</span></td>
                <td width="49%"><span style="float:right;">Yang Menyerahkan,</span></td>
            </tr>
            <tr>
                <td>PIHAK KEDUA</td>
                <td></td>
            </tr>
            <tr><td colspan="2"><br /><br /><br /><br /><br /></td></tr>
            <tr>
                <td>( <?php echo $customer['name']; ?> )</td>
                <td><span style="float:right;">(..................................................)</span></td>
            </tr>
            <tr>
                <td><span style="margin-left:5px;">NIP/Jabatan: Kepala Sekolah</span></td>
                <td><span style="float:right;margin-right:5px;">NIP/Jabatan: ..............................</span></td>
            </tr>
        </table>

    </body>
</html>