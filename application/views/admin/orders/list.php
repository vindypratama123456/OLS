<div class="container-fluid">

    <!-- Page Heading -->
    <div class="row">
        <div class="col-lg-12">
            <h1 class="page-header">
                Pesanan (Online)
            </h1>
            <ol class="breadcrumb">
                <li>
                    <a href="<?php echo base_url() . ADMIN_PATH; ?>">Beranda</a>
                </li>
                <li class="active">
                    Pesanan
                </li>
                <li class="active">
                    Online
                </li>
            </ol>
        </div>
    </div>
    <!-- /.row -->

    <div class="row">
        <div class="col-lg-12">
            <?php 
            if($this->session->flashdata('msg_success')) {
                echo notif('success',$this->session->flashdata('msg_success'));
            }
            if($this->session->flashdata('msg_failed')) {
                echo notif('danger',$this->session->flashdata('msg_failed'));
            }
            ?>
        </div>
    </div>

    <div class="row">
        <?php if($this->session->userdata('adm_level')==3 || $this->session->userdata('adm_level')==8) { ?>
        <div class="col-lg-12">
            <div class="col-lg-4 col-md-6">
                <div class="panel panel-green">
                    <div class="panel-heading">
                    <div class="row">
                        <div class="col-xs-3">
                            <i class="fa fa-tasks fa-5x"></i>
                        </div>
                        <div class="col-xs-9 text-right">
                            <div style="font-size:20px;"><?php echo toRupiah($total_omset) ?></div>
                                <div>Total Omset</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-4 col-md-6">
                <div class="panel panel-green">
                    <div class="panel-heading">
                    <div class="row">
                        <div class="col-xs-3">
                            <i class="fa fa-tasks fa-5x"></i>
                        </div>
                        <div class="col-xs-9 text-right">
                            <div style="font-size:20px;"><?php echo $order_terbuat ?></div>
                                <div>Total Order</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-4 col-md-6">
                <div class="panel panel-green">
                    <div class="panel-heading">
                    <div class="row">
                        <div class="col-xs-3">
                            <i class="fa fa-tasks fa-5x"></i>
                        </div>
                        <div class="col-xs-9 text-right">
                            <div style="font-size:20px;"><?php echo $order_terkonfirmasi ?></div>
                                <div>Total Terkonfirmasi</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <?php } ?>
        <div class="col-lg-12">
            <div class="table-responsive">
                <table class="table table-striped dt-responsive nowrap table-loader" id="datatableOnline">
                    <thead>
                        <tr>
                            <th class="text-center">Kode</th>
                            <th class="text-center">Nama Sekolah</th>
                            <th class="text-center">Propinsi</th>
                            <th class="text-center">Kab/Kota</th>
                            <th class="text-center">Kecamatan</th>
                            <th class="text-center">Kelas</th>
                            <th class="text-center">Kategori</th>
                            <th class="text-center">Semester</th>
                            <th class="text-center">Tgl Pesan</th>
                            <th class="text-center">Total Harga</th>
                            <th class="text-center">Status</th>
                            <th class="text-center">Nama Mitra</th>
                            <th class="text-center">Reference Dari</th>
                            <th class="text-center">No Reference</th>
                            <th class="text-center">No NPSN</th>
                        </tr>
                    </thead>
                </table>
            </div>
        </div>
    </div>
    <!-- /.row -->

</div>
<!-- /.container-fluid -->