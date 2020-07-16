<li class="<?php if($this->uri->segment(2)=='' || $this->uri->segment(2)=='dashboard') echo ' active'; ?>">
    <a href="<?php echo base_url().BACKMIN_PATH; ?>" data-toggle="tooltip" data-placement="top" title="Dasbor"><span class="fa fa-desktop"></span> <span class="xn-text">Dasbor</span></a>
</li>

<li class="xn-openable<?php if($this->uri->segment(2)=='product') echo ' active'; ?>">
    <a href="#" title="Produk" data-toggle="tooltip" data-placement="top">
        <span class="fa fa-fw fa-dropbox"></span> <span class="xn-text">Produk</span></a>
    <ul>
        <li <?php if($this->uri->segment(3)=='index') echo ' class="active"'; ?>>
            <a href="<?php echo base_url().BACKMIN_PATH.'/product'; ?>">Daftar Produk</a>
        </li>
    </ul>
</li>
<li class="xn-openable<?php if($this->uri->segment(2)=='gudangpesanan') echo ' active'; ?>">
    <a href="#" title="Pesanan Sekolah" data-toggle="tooltip" data-placement="top"><span class="fa fa-list-ul"></span> <span class="xn-text">Pesanan Sekolah</span></a>
    <ul>
        <li <?php if($this->uri->segment(3)=='indexPesananMasuk') echo ' class="active"'; ?>>
            <a href="<?php echo base_url().BACKMIN_PATH.'/gudangpesanan/indexPesananMasuk'; ?>">Pesanan Masuk</a>
        </li>
        <li <?php if($this->uri->segment(3)=='indexPesananDiproses') echo ' class="active"'; ?>>
            <a href="<?php echo base_url().BACKMIN_PATH.'/gudangpesanan/indexPesananDiproses'; ?>">Pesanan Diproses</a>
        </li>
    </ul>
</li>
<!-- <li class="xn-openable<?php if($this->uri->segment(2)=='gudangrequeststock' || $this->uri->segment(2)=='gudangpermintaan'|| $this->uri->segment(2)=='gudangrequestintan') echo ' active'; ?>">
    <a href="#" title="Permintaan Stok" data-toggle="tooltip" data-placement="top"><span class="fa fa-exchange"></span> <span class="xn-text">Permintaan Stok</span></a>
    <ul>
        <li <?php if($this->uri->segment(3)=='indexRequestStock') echo ' class="active"'; ?>>
            <a href="<?php echo base_url().BACKMIN_PATH.'/gudangrequeststock/indexRequestStock'; ?>">Request Stok</a>
        </li>
        <li <?php if($this->uri->segment(3)=='indexRequestIntan') echo ' class="active"'; ?>>
            <a href="<?php echo base_url().BACKMIN_PATH.'/gudangrequestintan/indexRequestIntan'; ?>">Request Intan</a>
        </li>
        <li <?php if($this->uri->segment(3)=='indexBarangMasuk') echo ' class="active"'; ?>>
            <a href="<?php echo base_url().BACKMIN_PATH.'/gudangpermintaan/indexBarangMasuk'; ?>">Barang Masuk</a>
        </li>
        <li <?php if($this->uri->segment(3)=='indexBarangKeluar') echo ' class="active"'; ?>>
            <a href="<?php echo base_url().BACKMIN_PATH.'/gudangpermintaan/indexBarangKeluar'; ?>">Barang Keluar</a>
        </li>
    </ul>
</li> -->
<li class="xn-openable<?php if($this->uri->segment(2)=='gudangrequeststockpartial' || $this->uri->segment(2)=='gudangpermintaanpartial' || $this->uri->segment(2)=='gudangrequeststock' || $this->uri->segment(2)=='gudangpermintaan'|| $this->uri->segment(2)=='gudangrequestintan') echo ' active'; ?>">
    <a href="#" title="Permintaan Stok" data-toggle="tooltip" data-placement="top"><span class="fa fa-exchange"></span> <span class="xn-text">Permintaan TAG</span></a>
    <ul>
        <li <?php if($this->uri->segment(3)=='indexRequestStock') echo ' class="active"'; ?>>
            <a href="<?php echo base_url().BACKMIN_PATH.'/gudangrequeststockpartial/indexRequestStock'; ?>">Request TAG</a>
        </li>
        <!-- <li <?php if($this->uri->segment(3)=='indexRequestIntan') echo ' class="active"'; ?>>
            <a href="<?php echo base_url().BACKMIN_PATH.'/gudangrequestintan/indexRequestIntan'; ?>">Request Intan</a>
        </li> -->
        <li <?php if($this->uri->segment(3)=='indexBarangMasuk') echo ' class="active"'; ?>>
            <a href="<?php echo base_url().BACKMIN_PATH.'/gudangpermintaanpartial/indexBarangMasuk'; ?>">Barang Masuk</a>
        </li>
        <li <?php if($this->uri->segment(3)=='indexBarangKeluar') echo ' class="active"'; ?>>
            <a href="<?php echo base_url().BACKMIN_PATH.'/gudangpermintaanpartial/indexBarangKeluar'; ?>">Barang Keluar</a>
        </li>
    </ul>
</li>
<li class="xn-openable<?php if($this->uri->segment(2)=='gudangreceiving' || $this->uri->segment(2)=='gudangpermintaan'|| $this->uri->segment(2)=='gudangrequestintan') echo ' active'; ?>">
    <a href="#" title="Stock Receiving" data-toggle="tooltip" data-placement="top"><span class="fa fa-download"></span> <span class="xn-text">Stock Receiving</span></a>
    <ul>
        <li <?php if($this->uri->segment(3)=='indexRequestStock') echo ' class="active"'; ?>>
            <a href="<?php echo base_url().BACKMIN_PATH.'/gudangreceiving/list'; ?>">List Stock Receiving</a>
        </li>
    </ul>
</li>
<li class="xn-openable<?php if($this->uri->segment(2)=='gudangpengiriman') echo ' active'; ?>">
    <a href="#" title="Perintah Pengiriman" data-toggle="tooltip" data-placement="top">
        <span class="fa fa-truck"></span> <span class="xn-text">Pengiriman</span>
    </a>
    <ul>
        <li <?php if($this->uri->segment(3)=='index') echo ' class="active"'; ?>>
            <a href="<?php echo base_url().BACKMIN_PATH.'/gudangpengiriman/index'; ?>">Daftar Pengiriman</a>
        </li>
        <li <?php if($this->uri->segment(3)=='download_bast') echo ' class="active"'; ?>>
            <a href="<?php echo base_url().BACKMIN_PATH.'/gudangpengiriman/download_bast'; ?>">Download Bast Siplah</a>
        </li>
    </ul>
</li>
<li class="xn-openable<?php if($this->uri->segment(2)=='gudangadjusment') echo ' active'; ?>">
    <a href="#" title="adjusment" data-toggle="tooltip" data-placement="top"><span class="fa fa-balance-scale"></span> <span class="xn-text">Adjustment</span></a>
    <ul>
        <li<?php if($this->uri->segment(3)=='index_adjusment') echo ' class="active"'; ?>>
            <a href="<?php echo base_url(BACKMIN_PATH.'/gudangadjusment/index_adjusment'); ?>">List Adjustment</a>
        </li>
        <li<?php if($this->uri->segment(3)=='indexadjusmentdiproses') echo ' class="active"'; ?>>
            <a href="<?php echo base_url(BACKMIN_PATH.'/gudangadjusment/index_adjusment_diproses'); ?>">Adjustment Diproses</a>
      </li>
    </ul>
</li>
<li class="xn-openable<?php if($this->uri->segment(2)=='gudanglaporan') echo ' active'; ?>">
    <a href="#" title="Laporan" data-toggle="tooltip" data-placement="top" title="Laporan"><span class="fa fa-bar-chart-o"></span> <span class="xn-text">Laporan</span></a>
    <ul>
        <li <?php if($this->uri->segment(3)=='' || $this->uri->segment(3)=='indexStok') echo ' class="active"'; ?>><a href="<?php echo base_url().BACKMIN_PATH.'/gudanglaporan/indexStok'; ?>">Lihat Stok</a></li>
        <!--
        <li><a href="#">Barang Masuk</a></li>
        <li><a href="#">Barang Keluar</a></li>
        -->
    </ul>
</li>
