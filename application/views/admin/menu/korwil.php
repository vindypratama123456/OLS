<li<?php if ($this->uri->segment(2)=='' || $this->uri->segment(2)=='dashboard') { echo ' class="active"'; } ?>>
    <a href="<?php echo base_url() . ADMIN_PATH; ?>/dashboard"><i class="fa fa-fw fa-dashboard"></i> Beranda</a>
</li>
<li>
    <a href="javascript:;" data-toggle="collapse" data-target="#pelanggan"><i class="fa fa-fw fa-users"></i> Pelanggan <i class="fa fa-fw fa-caret-down"></i></a>
    <ul id="pelanggan" class="collapse<?php if ($this->uri->segment(2)=='customer') { echo ' in"';} ?>">
        <li><a href="<?php echo base_url() . ADMIN_PATH; ?>/customer">Daftar Pelanggan</a></li>
        <li><a href="<?php echo base_url() . ADMIN_PATH; ?>/customer/no_order">Belum Order</a></li>
    </ul>
</li>
<li>
    <a href="javascript:;" data-toggle="collapse" data-target="#pesanan"><i class="fa fa-fw fa-shopping-cart"></i> Pesanan <i class="fa fa-fw fa-caret-down"></i></a>
    <ul id="pesanan" class="collapse<?php if ($this->uri->segment(2)=='orders' || $this->uri->segment(2)=='buku' || $this->uri->segment(2)=='finance') { echo ' in'; } ?>">
        <li><a href="<?php echo base_url() . ADMIN_PATH; ?>/orders">Online</a></li>
        <li><a href="<?php echo base_url() . ADMIN_PATH; ?>/orders/offline">Offline</a></li>
        <li><a href="<?php echo base_url() . ADMIN_PATH; ?>/finance/allOrder">Status Bayar</a></li>
    </ul>
</li>
<li>
    <a href="javascript:;" data-toggle="collapse" data-target="#sekolah_prospect"><i class="fa fa-fw fa-briefcase"></i> Mitra Penjualan <i class="fa fa-fw fa-caret-down"></i></a>
    <ul id="sekolah_prospect" class="collapse<?php if($this->uri->segment(2)=='sekolahprospect') echo ' in'; ?>">
        <li><a href="<?php echo base_url() . ADMIN_PATH; ?>/sekolahprospect">List Sekolah</a></li>
    </ul>
</li>
<li<?php if ($this->uri->segment(2)=='report') { echo ' class="active"';} ?>>
    <a href="<?php echo base_url() . ADMIN_PATH; ?>/report/korwil">
        <i class="fa fa-fw fa-bar-chart-o"></i>Laporan
    </a>
</li>
<?php if ($this->session->userdata('adm_level') == 3) { ?>
<li<?php if ($this->uri->segment(2)=='mitra') { echo ' class="active"'; } ?>>
    <a href="<?php echo base_url() . ADMIN_PATH; ?>/mitra"><i class="fa fa-fw fa-user"></i> Mitra</a>
</li>
<?php } ?>
<li>
    <a href="javascript:;" data-toggle="collapse" data-target="#komisi-sales"><i class="fa fa-fw fa-certificate"></i> Komisi <i class="fa fa-fw fa-caret-down"></i></a>
    <ul id="komisi-sales" class="collapse<?php if($this->uri->segment(2)=='comission') echo ' in'; ?>">
        <?php if($this->session->userdata('adm_level')==3) { ?>
        <li><a href="<?php echo base_url() . ADMIN_PATH; ?>/comission">Pesanan Baru</a></li>
        <?php } ?>
        <li><a href="<?php echo base_url() . ADMIN_PATH; ?>/comission/proposed">Diajukan</a></li>
        <li><a href="<?php echo base_url() . ADMIN_PATH; ?>/comission/approved">Disetujui</a></li>
        <li><a href="<?php echo base_url() . ADMIN_PATH; ?>/comission/processed">Diproses</a></li>
        <li><a href="<?php echo base_url() . ADMIN_PATH; ?>/comission/paidoff">Dibayar</a></li>
    </ul>
</li>
<li>
    <a href="javascript:;" data-toggle="collapse" data-target="#pengiriman_parsial"><i class="fa fa-fw fa-briefcase"></i> Pengiriman Parsial <i class="fa fa-fw fa-caret-down"></i></a>
    <ul id="pengiriman_parsial" class="collapse<?php if($this->uri->segment(2)=='pengiriman_parsial') echo ' in'; ?>">
        <li><a href="<?php echo base_url() . ADMIN_PATH; ?>/pengiriman_parsial/index">Pengiriman Parsial Request</a></li>
        <li><a href="<?php echo base_url() . ADMIN_PATH; ?>/pengiriman_parsial/index_processed">Pengiriman Parsial Proses</a></li>
    </ul>
</li>
