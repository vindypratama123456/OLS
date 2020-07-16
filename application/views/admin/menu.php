<!-- Sidebar Menu Items - These collapse to the responsive navigation menu on small screens -->
<div class="collapse navbar-collapse navbar-ex1-collapse">
    <ul class="nav navbar-nav side-nav">
        <?php
            $level = $this->session->userdata('adm_level');
            switch ($level) {
                case 1:
                case 11:
                    $this->load->view('admin/menu/superadmin');
                    break;
                case 2:
                    $this->load->view('admin/menu/admin');
                    break;
                case 3:
                case 8:
                    $this->load->view('admin/menu/korwil');
                    break;
                case 4:
                    $this->load->view('admin/menu/sales');
                    break;
                case 5:
                    $this->load->view('admin/menu/logistik');
                    break;
                case 6:
                case 7:
                case 14:
                    $this->load->view('admin/menu/finance');
                    break;
                case 15:
                    $this->load->view('admin/menu/hr');
                    break;
                case 101:
                case 102:
                    $this->load->view('admin/menu/steam');
                    break;

            }
        ?>
        <li>
            <a href="<?php echo base_url().ADMIN_PATH; ?>/logout"><i class="fa fa-fw fa-power-off"></i> Keluar</a>
        </li>
    </ul>
</div>
<!-- /.navbar-collapse -->