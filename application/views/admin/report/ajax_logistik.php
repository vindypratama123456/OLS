<div class="table-responsive">
    <?php if($listdata) { ?>
    <p>
        Total Omset: <b><?php echo toRupiah($total->total_omset); ?></b><br />
        Total Buku Dipesan: <b><?php echo rupiah($total->total_buku); ?></b> Eksemplar<br />
        Total Pesanan: <b><?php echo rupiah($total->total_pesanan); ?></b>
    </p>
    <?php if(count($listdata>0) && $excel) { ?>
    <p>
        <a href="<?php echo base_url($excel); ?>" class="btn btn-success" target="_blank">Ekspor ke Excel</a>
    </p>
    <?php } } else { echo '<div class="well well-lg">Maaf, data tidak tersedia.</div>'; } ?>
</div>