<li<?php if ($this->uri->segment(2)=='' || $this->uri->segment(2)=='dashboard') { echo ' class="active"'; } ?>>
    <a href="<?php echo base_url() . ADMIN_PATH; ?>/dashboard"><i class="fa fa-fw fa-dashboard"></i> Beranda</a>
</li>
<li>
    <a href="javascript:;" data-toggle="collapse" data-target="#pelanggan"><i class="fa fa-fw fa-users"></i> Pelanggan <i class="fa fa-fw fa-caret-down"></i></a>
    <ul id="pelanggan" class="collapse<?php if ($this->uri->segment(2)=='customer') { echo ' in'; } ?>">
        <li><a href="<?php echo base_url() . ADMIN_PATH; ?>/customer">Daftar Pelanggan</a></li>
        <li><a href="<?php echo base_url() . ADMIN_PATH; ?>/customer/has_order">Sudah Order</a></li>
        <li><a href="<?php echo base_url() . ADMIN_PATH; ?>/customer/no_order">Belum Order</a></li>
    </ul>
</li>
<li>
    <a href="javascript:;" data-toggle="collapse" data-target="#ec"><i class="fa fa-fw fa-users"></i> Koordinator Wilayah (EC) <i class="fa fa-fw fa-caret-down"></i></a>
    <ul id="ec" class="collapse<?php if ($this->uri->segment(2)=='customer') { echo ' in'; } ?>">
        <li><a href="<?php echo base_url(); ?>ecregistrasi">Registrasi EC</a></li>
        <li><a href="<?php echo base_url(); ?>ecregistrasi/ecpindah">Perpindahan EC</a></li>
    </ul>
</li>
<li<?php if ($this->uri->segment(2)=='kabupaten_zona') { echo ' class="active"'; } ?>>
    <a href="<?php echo base_url() . ADMIN_PATH; ?>/kabupaten_zona"><i class="fa fa-fw fa-globe"></i> Kabupaten Zona</a>
</li>

<li>
    <a href="javascript:;" data-toggle="collapse" data-target="#produk"><i class="fa fa-fw fa-dropbox"></i> Produk <i class="fa fa-fw fa-caret-down"></i></a>
    <ul id="produk" class="collapse<?php if ($this->uri->segment(2)=='customer') { echo ' in'; } ?>">
        <li><a href="<?php echo base_url() . ADMIN_PATH; ?>/product">Daftar Produk</a></li>
        <li><a href="<?php echo base_url() . ADMIN_PATH; ?>/product/importProduct">Import Produk Batch</a></li>
    </ul>
</li>
<li>
    <a href="javascript:;" data-toggle="collapse" data-target="#pesanan"><i class="fa fa-fw fa-shopping-cart"></i> Pesanan <i class="fa fa-fw fa-caret-down"></i></a>
    <ul id="pesanan" class="collapse<?php if ($this->uri->segment(2)=='orders' || $this->uri->segment(2)=='buku') { echo ' in'; } ?>">
        <li><a href="<?php echo base_url() . ADMIN_PATH; ?>/orders">Online</a></li>
        <li><a href="<?php echo base_url() . ADMIN_PATH; ?>/orders/offline">Offline</a></li>
        <li><a href="<?php echo base_url() . ADMIN_PATH; ?>/orders/list_filter_index">Filter</a></li>
    </ul>
</li>
<li>
    <a href="javascript:;" data-toggle="collapse" data-target="#report"><i class="fa fa-fw fa-bar-chart-o"></i> Laporan <i class="fa fa-fw fa-caret-down"></i></a>
    <ul id="report" class="collapse<?php if ($this->uri->segment(2)=='report') { echo ' in'; } ?>">
        <li><a href="<?php echo base_url() . ADMIN_PATH; ?>/report">Omset</a></li>
    </ul>
</li>
<li>
    <a href="javascript:;" data-toggle="collapse" data-target="#finance"><i class="fa fa-fw fa-money"></i> Finance <i class="fa fa-fw fa-caret-down"></i></a>
    <ul id="finance" class="collapse<?php if ($this->uri->segment(2)=='finance') { echo ' in'; } ?>">
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
    </ul>
</li>
<li>
    <a href="javascript:;" data-toggle="collapse" data-target="#komisi-sales"><i class="fa fa-fw fa-certificate"></i> Komisi <i class="fa fa-fw fa-caret-down"></i></a>
    <ul id="komisi-sales" class="collapse<?php if($this->uri->segment(2)=='comission') echo ' in'; ?>">
        <li><a href="<?php echo base_url() . ADMIN_PATH; ?>/comission/toprocessed">Disetujui</a></li>
        <li><a href="<?php echo base_url() . ADMIN_PATH; ?>/comission/processed">Diproses</a></li>
        <li><a href="<?php echo base_url() . ADMIN_PATH; ?>/comission/paidoff">Dibayar</a></li>
    </ul>
</li>
<li>
    <a href="javascript:;" data-toggle="collapse" data-target="#mitra"><i class="fa fa-fw fa-user"></i> Mitra <i class="fa fa-fw fa-caret-down"></i></a>
    <ul id="mitra" class="collapse<?php if($this->uri->segment(2)=='mitra') echo ' in'; ?>">
        <li><a href="<?php echo base_url() . ADMIN_PATH; ?>/Mitra">List Mitra</a></li>
        <li><a href="<?php echo base_url() . ADMIN_PATH; ?>/kontrak">Kontrak Mitra</a></li>
    </ul>
</li>
<li<?php if ($this->uri->segment(2)=='bank') { echo ' class="active"'; } ?>>
    <a href="<?php echo base_url() . ADMIN_PATH; ?>/bank"><i class="fa fa-fw fa-bank"></i> Bank</a>
</li>
<li<?php if ($this->uri->segment(2)=='partner') { echo ' class="active"'; } ?>>
    <a href="<?php echo base_url() . ADMIN_PATH; ?>/partner"><i class="fa fa-fw fa-handshake-o"></i> Partner</a>
</li>
<li>
    <a href="javascript:;" data-toggle="collapse" data-target="#emailblacklist"><i class="fa fa-fw fa-envelope"></i> Email Blacklist <i class="fa fa-fw fa-caret-down"></i></a>
    <ul id="emailblacklist" class="collapse<?php if($this->uri->segment(2)=='emailblacklist') echo ' in'; ?>">
        <li><a href="<?php echo base_url() . ADMIN_PATH; ?>/emailblacklist">Email Blacklist</a></li>
        <li><a href="<?php echo base_url() . ADMIN_PATH; ?>/emailblacklist/import">Upload Batch</a></li>
    </ul>
</li>
<li>
    <a href="javascript:;" data-toggle="collapse" data-target="#siplah"><i class="fa fa-fw fa-certificate"></i> Siplah <i class="fa fa-fw fa-caret-down"></i></a>
    <ul id="siplah" class="collapse<?php if($this->uri->segment(2)=='pesananblanja') echo ' in'; ?>">
        <li><a href="<?php echo base_url() . ADMIN_PATH; ?>/pesananblanja/getdatasiplah">Import Siplah ke OLS</a></li>
        <li><a href="<?php echo base_url() . ADMIN_PATH; ?>/pesananblanja/viewError">Siplah tidak terproses</a></li>
    </ul>
</li>
<!-- <li<?php if ($this->uri->segment(2)=='mitra') { echo ' class="active"'; } ?>>
    <a href="<?php echo base_url() . ADMIN_PATH; ?>/pesananblanja/getdatasiplah"><i class="fa fa-fw fa-user"></i> Import Siplah ke OLS</a>
</li> -->
<li<?php if ($this->uri->segment(2)=='feedback') { echo ' class="active"'; } ?>>
    <a href="<?php echo base_url() . ADMIN_PATH; ?>/feedback"><i class="fa fa-fw fa-comment"></i> Testimoni</a>
</li>