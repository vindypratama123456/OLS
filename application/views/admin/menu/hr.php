<li>
    <a href="javascript:;" data-toggle="collapse" data-target="#mitra"><i class="fa fa-fw fa-user"></i> Mitra <i class="fa fa-fw fa-caret-down"></i></a>
    <ul id="mitra" class="collapse<?php if($this->uri->segment(2)=='mitra') echo ' in'; ?>">
        <li><a href="<?php echo base_url() . ADMIN_PATH; ?>/Mitra">List Mitra</a></li>
        <li><a href="<?php echo base_url() . ADMIN_PATH; ?>/kontrak">Kontrak Mitra</a></li>
    </ul>
</li>