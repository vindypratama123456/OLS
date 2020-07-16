<li<?php if($this->uri->segment(2)=='' || $this->uri->segment(2)=='dashboard') echo ' class="active"'; ?>>
    <a href="<?php echo base_url() . ADMIN_PATH; ?>/dashboard"><i class="fa fa-fw fa-dashboard"></i> Beranda</a>
</li>
<!-- <li>
    <a href="javascript:;" data-toggle="collapse" data-target="#product"><i class="fa fa-fw fa-shopping-cart"></i> Produk <i class="fa fa-fw fa-caret-down"></i></a>
    <ul id="product" class="collapse<?php if($this->uri->segment(2)=='product' || $this->uri->segment(2)=='buku') echo ' in'; ?>">
        <li><a href="<?php echo base_url() . ADMIN_PATH; ?>/product">Daftar Produk</a></li>
    </ul>
</li>
<li>
    <a href="javascript:;" data-toggle="collapse" data-target="#sales"><i class="fa fa-fw fa-shopping-cart"></i> Sales <i class="fa fa-fw fa-caret-down"></i></a>
    <ul id="sales" class="collapse<?php if($this->uri->segment(2)=='sales' || $this->uri->segment(2)=='buku') echo ' in'; ?>">
        <li><a href="<?php echo base_url() . ADMIN_PATH; ?>/sales">Daftar Sales</a></li>
    </ul>
</li>
<li>
    <a href="javascript:;" data-toggle="collapse" data-target="#pelanggan"><i class="fa fa-fw fa-users"></i> Pelanggan <i class="fa fa-fw fa-caret-down"></i></a>
    <ul id="pelanggan" class="collapse<?php if($this->uri->segment(2)=='customer') echo ' in'; ?>">
        <li><a href="<?php echo base_url() . ADMIN_PATH; ?>/customer">Daftar Pelanggan</a></li>
    </ul>
</li> -->
<li>
    <a href="javascript:;" data-toggle="collapse" data-target="#pesanan"><i class="fa fa-fw fa-shopping-cart"></i> Pesanan <i class="fa fa-fw fa-caret-down"></i></a>
    <ul id="pesanan" class="collapse<?php if($this->uri->segment(2)=='steam') echo ' in'; ?>">
        <li><a href="<?php echo base_url() . ADMIN_PATH; ?>/steam/import_sap">Upload Pesanan SAP</a></li>
        <li><a href="<?php echo base_url() . ADMIN_PATH; ?>/steam/order_add">Input Pesanan</a></li>
        <li><a href="<?php echo base_url() . ADMIN_PATH; ?>/steam/comission_order_new">Daftar Pesanan</a></li>
    </ul>
</li>
<li>
    <a href="javascript:;" data-toggle="collapse" data-target="#komisi"><i class="fa fa-fw fa-money"></i> Komisi <i class="fa fa-fw fa-caret-down"></i></a>
    <ul id="komisi" class="collapse<?php if($this->uri->segment(2)=='steam' || $this->uri->segment(2)=='buku') echo ' in'; ?>">
        <li><a href="<?php echo base_url() . ADMIN_PATH; ?>/steam/comission_index">Daftar Komisi</a></li>
        <li><a href="<?php echo base_url() . ADMIN_PATH; ?>/steam/comission_sap_index">Daftar Proses Approval</a></li>
        <li><a href="<?php echo base_url() . ADMIN_PATH; ?>/steam/comission_sap_process_index">Daftar Proses SAP</a></li>
        <!-- <li><a href="<?php echo base_url() . ADMIN_PATH; ?>/orders">Komisi Baru</a></li>
        <li><a href="<?php echo base_url() . ADMIN_PATH; ?>/orders">Komisi Sudah Diproses</a></li> -->
    </ul>
</li>
