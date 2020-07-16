<li class="<?php if($this->uri->segment(2)=='' || $this->uri->segment(2)=='dashboard') echo ' active'; ?>">
    <a href="<?php echo base_url().BACKMIN_PATH; ?>" data-toggle="tooltip" data-placement="top" title="Dasbor"><span class="fa fa-desktop"></span> <span class="xn-text">Dasbor</span></a>
</li>

<li class="xn-openable<?php if($this->uri->segment(2)=='scmpesanan') echo ' active'; ?>">
    <a href="#" title="Pesanan" data-toggle="tooltip" data-placement="top"><span class="fa fa-list-ul"></span> <span class="xn-text">Pesanan</span></a>
    <ul>
        <li<?php if($this->uri->segment(3)=='indexPesananMasuk') echo ' class="active"'; ?>>
            <a href="<?php echo base_url(BACKMIN_PATH.'/scmpesanan/indexPesananMasuk'); ?>">Pesanan Masuk</a>
        </li>
        <li<?php if($this->uri->segment(3)=='indexPesananDiproses') echo ' class="active"'; ?>>
            <a href="<?php echo base_url(BACKMIN_PATH.'/scmpesanan/indexPesananDiproses'); ?>">Pesanan Diproses</a>
        </li>
    </ul>
</li>
<!-- <li class="xn-openable<?php if($this->uri->segment(2)=='scmrequeststock') echo ' active'; ?>">
    <a href="#" title="Permintaan Stok" data-toggle="tooltip" data-placement="top"><span class="fa fa-exchange"></span> <span class="xn-text">Permintaan Stok</span></a>
    <ul>
        <li<?php if($this->uri->segment(3)=='indexRequestStockMasuk') echo ' class="active"'; ?>>
            <a href="<?php echo base_url(BACKMIN_PATH.'/scmrequeststock/indexRequestStockMasuk'); ?>">Permintaan Masuk</a>
        </li>
        <li<?php if($this->uri->segment(3)=='indexRequestStockDiproses') echo ' class="active"'; ?>>
            <a href="<?php echo base_url(BACKMIN_PATH.'/scmrequeststock/indexRequestStockDiproses'); ?>">Permintaan Diproses</a>
        </li>
        <li<?php if($this->uri->segment(3)=='indexRequestStockReport') echo ' class="active"'; ?>>
            <a href="<?php echo base_url(BACKMIN_PATH.'/scmrequeststock/indexRequestStockReport'); ?>">Rekapitulasi Permintaan</a>
        </li>
    </ul>
</li> -->
<li class="xn-openable<?php if($this->uri->segment(2)=='scmrequeststockpartial') echo ' active'; ?>">
    <a href="#" title="Permintaan Stok" data-toggle="tooltip" data-placement="top"><span class="fa fa-exchange"></span> <span class="xn-text">Permintaan TAG</span></a>
    <ul>
        <li<?php if($this->uri->segment(3)=='indexrequeststockmasuk') echo ' class="active"'; ?>>
            <a href="<?php echo base_url(BACKMIN_PATH.'/scmrequeststockpartial/indexrequeststockmasuk'); ?>">Permintaan Masuk</a>
        </li>
        <li<?php if($this->uri->segment(3)=='indexrequeststockdiproses') echo ' class="active"'; ?>>
            <a href="<?php echo base_url(BACKMIN_PATH.'/scmrequeststockpartial/indexrequeststockdiproses'); ?>">Permintaan Diproses</a>
        </li>
        <li<?php if($this->uri->segment(3)=='indexRekapitulasiRequestStockProcess') echo ' class="active"'; ?>>
            <a href="<?php echo base_url(BACKMIN_PATH.'/scmrequeststockpartial/indexRekapitulasiRequestStockProcess'); ?>">Rekapitulasi Permintaan Proses</a>
        </li>
        <li<?php if($this->uri->segment(3)=='indexRekapitulasiPemenuhanRequestStock') echo ' class="active"'; ?>>
            <a href="<?php echo base_url(BACKMIN_PATH.'/scmrequeststockpartial/indexRekapitulasiPemenuhanRequestStock'); ?>">Rekapitulasi Pemenuhan Permintaan Stok</a>
        </li>
    </ul>
</li>
<li class="xn-openable<?php if($this->uri->segment(2)=='gudangproduction') echo ' active'; ?>">
    <a href="#" title="Production Order" data-toggle="tooltip" data-placement="top"><span class="fa fa-dropbox"></span> <span class="xn-text">Production Order</span></a>
    <ul>
        <li<?php if($this->uri->segment(3)=='order') echo ' class="active"'; ?>>
            <a href="<?php echo base_url(BACKMIN_PATH.'/gudangproduction'); ?>">List Production Order</a>
        </li>
        <li<?php if($this->uri->segment(3)=='indexProductionReport') echo ' class="active"'; ?>>
            <a href="<?php echo base_url(BACKMIN_PATH.'/gudangproduction/indexProductionReport'); ?>">Rekapitulasi Production</a>
        </li>
        <li<?php if($this->uri->segment(3)=='indexReceivingReport') echo ' class="active"'; ?>>
            <a href="<?php echo base_url(BACKMIN_PATH.'/gudangproduction/indexReceivingReport'); ?>">Rekapitulasi Stock Receiving</a>
        </li>
    </ul>
</li>
<li class="xn-openable<?php if($this->uri->segment(2)=='scmadjusment') echo ' active'; ?>">
    <a href="#" title="Pesanan" data-toggle="tooltip" data-placement="top"><span class="fa fa-balance-scale"></span> <span class="xn-text">Adjusment</span></a>
    <ul>
        <li<?php if($this->uri->segment(3)=='index_adjusment') echo ' class="active"'; ?>>
            <a href="<?php echo base_url(BACKMIN_PATH.'/scmadjusment/index_adjusment'); ?>">List Adjusment</a>
        </li>
        <li<?php if($this->uri->segment(3)=='index_adjusment_diproses') echo ' class="active"'; ?>>
            <a href="<?php echo base_url(BACKMIN_PATH.'/scmadjusment/index_adjusment_diproses'); ?>">Adjusment Diproses</a>
        </li>
        <li<?php if($this->uri->segment(3)=='index_adjusment_report') echo ' class="active"'; ?>>
            <a href="<?php echo base_url(BACKMIN_PATH.'/scmadjusment/index_adjusment_report'); ?>">Rekapitulasi Adjusment</a>
        </li>
    </ul>
</li>
<li class="xn-openable<?php if($this->uri->segment(2)=='scmlaporan') echo ' active'; ?>">
    <a href="#" title="Laporan" data-toggle="tooltip" data-placement="top"><span class="fa fa-bar-chart-o"></span> <span class="xn-text">Laporan</span></a>
    <ul>
        <li<?php if($this->uri->segment(3)=='' || $this->uri->segment(3)=='indexStok') echo ' class="active"'; ?>><a href="<?php echo base_url().BACKMIN_PATH.'/scmlaporan/indexStok'; ?>">Lihat Summary Stok</a></li>
        <li<?php if($this->uri->segment(3)=='' || $this->uri->segment(3)=='indexSupplyChain') echo ' class="active"'; ?>><a href="<?php echo base_url().BACKMIN_PATH.'/scmlaporan/indexSupplyChain'; ?>">Lihat Supply Chain</a></li>
        <li<?php if($this->uri->segment(3)=='' || $this->uri->segment(3)=='indexStokWarehouse') echo ' class="active"'; ?>><a href="<?php echo base_url().BACKMIN_PATH.'/scmlaporan/indexStokWarehouse'; ?>">Lihat Stok Barang</a></li>
        <li<?php if($this->uri->segment(3)=='' || $this->uri->segment(3)=='index_report_transaction') echo ' class="active"'; ?>><a href="<?php echo base_url().BACKMIN_PATH.'/scmlaporan/index_report_transaction'; ?>">Lihat Transaksi</a></li>
        <?php /* <li<?php if($this->uri->segment(3)=='' || $this->uri->segment(3)=='indexOmset') echo ' class="active"'; ?>><a href="<?php echo base_url().BACKMIN_PATH.'/scmlaporan/indexOmset'; ?>">Lihat Omset</a></li>
        <li<?php if($this->uri->segment(3)=='' || $this->uri->segment(3)=='indexStokGudang') echo ' class="active"'; ?>><a href="<?php echo base_url().BACKMIN_PATH.'/scmlaporan/indexStokGudang'; ?>">Lihat Stok Gudang</a></li> */ ?>
    </ul>
</li>
<!--
<li class="xn-openable">
    <a href="#" title="Perintah Pengiriman" data-toggle="tooltip" data-placement="top" title="Perintah Pengiriman"><span class="fa fa-truck"></span> <span class="xn-text">Pengiriman</span></a>
    <ul>
        <li><a href="#">Ke Sekolah</a></li>
        <li><a href="#">Transfer Antar Gudang</a></li>
    </ul>
</li>
<li class="xn-openable">
    <a href="#" title="Laporan" data-toggle="tooltip" data-placement="top" title="Laporan"><span class="fa fa-bar-chart-o"></span> <span class="xn-text">Laporan</span></a>
    <ul>
        <li><a href="#">Jumlah Stok</a></li>
        <li><a href="#">Jumlah Pesanan</a></li>
        <li><a href="#">Pesanan vs Stok</a></li>
    </ul>
</li>
-->