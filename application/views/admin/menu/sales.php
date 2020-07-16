<li>
    <a href="javascript:;" data-toggle="collapse" data-target="#pesanan"><i class="fa fa-fw fa-shopping-cart"></i> Pesanan <i class="fa fa-fw fa-caret-down"></i></a>
    <ul id="pesanan" class="collapse<?php if ($this->uri->segment(2)=='orders' || $this->uri->segment(2)=='buku' || $this->uri->segment(2)=='finance') { echo ' in"'; } ?>">
        <li><a href="<?php echo base_url() . ADMIN_PATH; ?>/orders">Online</a></li>
        <li><a href="<?php echo base_url() . ADMIN_PATH; ?>/orders/offline">Offline</a></li>
        <li><a href="<?php echo base_url() . ADMIN_PATH; ?>/finance/allOrder">Status Bayar</a></li>
    </ul>
</li>
<li>
    <a href="javascript:;" data-toggle="collapse" data-target="#sekolah_prospect"><i class="fa fa-fw fa-briefcase"></i> Mitra Penjualan <i class="fa fa-fw fa-caret-down"></i></a>
    <ul id="sekolah_prospect" class="collapse<?php if ($this->uri->segment(2)=='sekolahprospect') { echo ' in"'; } ?>">
        <li><a href="<?php echo base_url() . ADMIN_PATH; ?>/sekolahprospect">List Sekolah</a></li>
        <li><a href="<?php echo base_url() . ADMIN_PATH; ?>/sekolahprospect/request">List Request</a></li>
    </ul>
</li>
<li>
    <a href="javascript:;" data-toggle="collapse" data-target="#komisi-sales"><i class="fa fa-fw fa-money"></i> Komisi <i class="fa fa-fw fa-caret-down"></i></a>
    <ul id="komisi-sales" class="collapse<?php if($this->uri->segment(2)=='comission') echo ' in'; ?>">
        <li><a href="<?php echo base_url() . ADMIN_PATH; ?>/comission/summary">Ringkasan</a></li>
        <li><a href="<?php echo base_url() . ADMIN_PATH; ?>/comission">Menunggu</a></li>
        <li><a href="<?php echo base_url() . ADMIN_PATH; ?>/comission/proposed">Diajukan</a></li>
        <li><a href="<?php echo base_url() . ADMIN_PATH; ?>/comission/approved">Disetujui</a></li>
        <li><a href="<?php echo base_url() . ADMIN_PATH; ?>/comission/processed">Diproses</a></li>
        <li><a href="<?php echo base_url() . ADMIN_PATH; ?>/comission/paidoff">Dibayar</a></li>
    </ul>
</li>