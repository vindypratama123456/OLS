<?php 
if ($listdata) {
	switch ($tipe) {
		case 1:
			$urlExport = 'exportExcel/'.$tgl_mulai.'/'.$tgl_akhir.'/'.$wilayah;
			break;
		case 2:
			$urlExport = 'exportExcelLogistik/'.$tgl_mulai.'/'.$tgl_akhir;
			break;
		case 3:
			$urlExport = 'exportExcelKorwil/'.$tgl_mulai.'/'.$tgl_akhir;
			break;
	}
    $awal = strtotime($tgl_mulai);
    $akhir = strtotime($tgl_akhir);
    $selisih = 1 + (date("Y",$akhir)-date("Y",$awal))*12;
    $selisih += date("m",$akhir)-date("m",$awal);
?>
<p>
    Total Omset: <b><?php echo toRupiah($total->total_omset); ?></b><br />
    Total Buku Dipesan: <b><?php echo rupiah($total->total_buku); ?></b> Eksemplar<br />
    Total Pesanan: <b><?php echo rupiah($total->total_pesanan); ?></b>
</p>
<p>
	<?php if ($selisih <= 3) { ?>
		<a href="<?php echo base_url(ADMIN_PATH.'/report/'.$urlExport); ?>" class="btn btn-success" target="_blank">Ekspor ke Excel</a>
	<?php } else { ?>
		<p style="color: red;">Ekspor ke file Excel hanya bisa dilakukan maksimal 3 bulan.</p>
	<?php } ?>
</p>
<?php } else { echo '<div class="well well-lg">Maaf, data tidak tersedia.</div>'; } ?>