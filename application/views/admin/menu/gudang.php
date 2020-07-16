<li>
    <a href="javascript:;" data-toggle="collapse" data-target="#pesanan"><i class="fa fa-fw fa-shopping-cart"></i> Pesanan <i class="fa fa-fw fa-caret-down"></i></a>
    <ul id="pesanan" class="collapse<?php if($this->uri->segment(2)=='orders' || $this->uri->segment(2)=='buku') echo ' in'; ?>">
        <li><a href="<?php echo base_url() . ADMIN_PATH; ?>/orders">Online</a></li>
    </ul>
</li>