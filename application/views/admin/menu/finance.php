<li>
    <a href="javascript:;" data-toggle="collapse" data-target="#finance"><i class="fa fa-fw fa-money"></i> Finance <i class="fa fa-fw fa-caret-down"></i></a>
    <ul id="finance" class="collapse<?php if($this->uri->segment(2)=='finance') echo ' in'; ?>">
        <li><a href="<?php echo base_url() . ADMIN_PATH; ?>/finance">Sekolah Belum Lunas</a></li>
        <li><a href="<?php echo base_url() . ADMIN_PATH; ?>/finance/complete">Sekolah Lunas</a></li>
        <li><a href="<?php echo base_url() . ADMIN_PATH; ?>/finance/allInput">Semua Inputan</a></li>
    </ul>
</li>
<li>
    <a href="javascript:;" data-toggle="collapse" data-target="#laporan-finance"><i class="fa fa-fw fa-file-text-o"></i> Laporan Finance <i class="fa fa-fw fa-caret-down"></i></a>
    <ul id="laporan-finance" class="collapse<?php if($this->uri->segment(2)=='finance') echo ' in'; ?>">
        <li><a href="<?php echo base_url() . ADMIN_PATH; ?>/finance/reportStock">Laporan Stok</a></li>
        <li><a href="<?php echo base_url() . ADMIN_PATH; ?>/finance/reportStockRupiah">Laporan Stok Rupiah</a></li>
        <li><a href="<?php echo base_url() . ADMIN_PATH; ?>/finance/indexReportReceiving">Laporan Receiving</a></li>
        <li><a href="<?php echo base_url() . ADMIN_PATH; ?>/finance/indexReportStockStatus">Laporan Stock Status</a></li>
        <li><a href="<?php echo base_url() . ADMIN_PATH; ?>/finance/indexReportSalesAnalysis">Laporan Sales Analysis</a></li>
        <li><a href="<?php echo base_url() . ADMIN_PATH; ?>/finance/indexReportSalesAnalysis/1">Laporan Sales Analysis Belum Kirim</a></li>
    </ul>
</li>
<li>
    <a href="javascript:;" data-toggle="collapse" data-target="#komisi-sales"><i class="fa fa-fw fa-certificate"></i> Komisi <i class="fa fa-fw fa-caret-down"></i></a>
    <ul id="komisi-sales" class="collapse<?php if($this->uri->segment(2)=='comission') echo ' in'; ?>">
        <li><a href="<?php echo base_url() . ADMIN_PATH; ?>/comission/processedBatch">Diproses</a></li>
        <li><a href="<?php echo base_url() . ADMIN_PATH; ?>/comission/isPosting">Diposting</a></li>
        <li><a href="<?php echo base_url() . ADMIN_PATH; ?>/comission/processedBatchFailed">Gagal Posting</a></li>
        <li><a href="<?php echo base_url() . ADMIN_PATH; ?>/comission/paidBatch">Dibayar</a></li>
        <li><a href="<?php echo base_url() . ADMIN_PATH; ?>/comission/indexPPhAmount">Nilai PPh</a></li>
    </ul>
</li>
<li <?php if ($this->uri->segment(2)=='mitra') { echo ' class="active"'; } ?>>
    <a href="<?php echo base_url() . ADMIN_PATH; ?>/mitra"><i class="fa fa-fw fa-user"></i> Mitra</a>
</li>
