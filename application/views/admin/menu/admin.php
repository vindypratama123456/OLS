<li<?php if($this->uri->segment(2)=='' || $this->uri->segment(2)=='dashboard') echo ' class="active"'; ?>>
    <a href="<?php echo base_url() . ADMIN_PATH; ?>/dashboard"><i class="fa fa-fw fa-dashboard"></i> Beranda</a>
</li>
<li>
    <a href="javascript:;" data-toggle="collapse" data-target="#pelanggan"><i class="fa fa-fw fa-users"></i> Pelanggan <i class="fa fa-fw fa-caret-down"></i></a>
    <ul id="pelanggan" class="collapse<?php if($this->uri->segment(2)=='customer') echo ' in'; ?>">
        <li><a href="<?php echo base_url() . ADMIN_PATH; ?>/customer">Daftar Pelanggan</a></li>
        <li><a href="<?php echo base_url() . ADMIN_PATH; ?>/customer/no_order">Belum Order</a></li>
    </ul>
</li>
<li<?php if ($this->uri->segment(2)=='mitra') { echo ' class="active"'; } ?>>
    <a href="<?php echo base_url() . ADMIN_PATH; ?>/kabupaten_zona"><i class="fa fa-fw fa-globe"></i> Kabupaten Zona</a>
</li>
<li>
    <a href="javascript:;" data-toggle="collapse" data-target="#pesanan"><i class="fa fa-fw fa-shopping-cart"></i> Pesanan <i class="fa fa-fw fa-caret-down"></i></a>
    <ul id="pesanan" class="collapse<?php if($this->uri->segment(2)=='orders' || $this->uri->segment(2)=='buku') echo ' in'; ?>">
        <li><a href="<?php echo base_url() . ADMIN_PATH; ?>/orders">Online</a></li>
        <li><a href="<?php echo base_url() . ADMIN_PATH; ?>/orders/books">Buku Dipesan</a></li>
    </ul>
</li>
<li>
    <a href="javascript:;" data-toggle="collapse" data-target="#sekolah_prospect"><i class="fa fa-fw fa-briefcase"></i> Mitra Penjualan <i class="fa fa-fw fa-caret-down"></i></a>
    <ul id="sekolah_prospect" class="collapse<?php if($this->uri->segment(2)=='sekolahprospect') echo ' in'; ?>">
        <li><a href="<?php echo base_url() . ADMIN_PATH; ?>/sekolahprospect">List Sekolah</a></li>
    </ul>
</li>