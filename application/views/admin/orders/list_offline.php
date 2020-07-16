<div class="container-fluid">
    <!-- Page Heading -->
    <div class="row">
        <div class="col-lg-12">
            <h1 class="page-header">
                Pesanan (Offline)
            </h1>
            <ol class="breadcrumb">
                <li>
                    <a href="<?php echo base_url().ADMIN_PATH; ?>">Beranda</a>
                </li>
                <li class="active">
                    Pesanan
                </li>
                <li class="active">
                    Offline
                </li>
            </ol>
        </div>
    </div>
    <!-- /.row -->
    <div class="row">
        <div class="col-lg-12">
            <?php
            if ($this->session->flashdata('msg_success')) {
                echo notif('success', $this->session->flashdata('msg_success'));
            }
            ?>
        </div>
    </div>
    <div class="row">
        <?php if (in_array($this->adm_level, [3, 8])) { ?>
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
            <?php if (in_array($this->adm_level,
                    array_merge($this->backoffice_superadmin_area, [3, 8])) && env('ORDER_OFFLINE') == 'true') { ?>
                <a href="<?php echo base_url().ADMIN_PATH; ?>/orders/offlineAdd" class="btn btn-success"
                   title="Tambah Data">
                    <i class="fa fa-plus-square"></i> Tambah Data
                </a>
            <?php } ?>
            <div class="table-responsive">
                <table class="table table-striped dt-responsive nowrap table-loader" id="datatableOffline">
                    <thead>
                    <tr>
                        <th class="text-center">Kode</th>
                        <th class="text-center">Nama Sekolah</th>
                        <th class="text-center">Provinsi</th>
                        <th class="text-center">Kab/Kota</th>
                        <th class="text-center">Kelas</th>
                        <th class="text-center">Kategori</th>
                        <th class="text-center">Tgl Pesanan</th>
                        <th class="text-center">Total Harga</th>
                        <th class="text-center">Status</th>
                    </tr>
                    </thead>
                </table>
            </div>
        </div>
    </div>
    <!-- /.row -->
</div>
<!-- /.container-fluid -->