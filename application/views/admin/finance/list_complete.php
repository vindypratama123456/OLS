<div class="container-fluid">
    <!-- Page Heading -->
    <div class="row">
        <div class="col-lg-12">
            <h1 class="page-header">
                Pesanan Sekolah (Lunas)
            </h1>
            <ol class="breadcrumb">
                <li>
                    <a href="<?php echo base_url() . ADMIN_PATH; ?>">Beranda</a>
                </li>
                <li class="active">
                    Pesanan Sekolah (Lunas)
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
            ?>
            <div class="row">
                <div class="col-lg-4 pull-right">
                    <div class="panel panel-green">
                        <div class="panel-heading">
                        <div class="row">
                            <div class="col-xs-3">
                                <i class="fa fa-money fa-5x"></i>
                            </div>
                            <div class="col-xs-9 text-right">
                                <div style="font-size:24px;"><?php echo toRupiah($nilai_dibayar); ?></div>
                                    <div>Nilai Dibayar</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-lg-12">
            <div class="table-responsive">
                <table class="table table-striped dt-responsive nowrap table-loader" id="datatableLunas">
                    <thead>
                        <tr>
                            <th class="text-center">Kode</th>
                            <th class="text-center">Nama Sekolah</th>
                            <th class="text-center">Kategori</th>
                            <th class="text-center">Propinsi</th>
                            <th class="text-center">Kabupaten/Kota</th>
                            <th class="text-center">Nama Korwil / EC</th>
                            <th class="text-center">Nama Kepsek</th>
                            <th class="text-center">Telpon Sekolah</th>
                            <th class="text-center">HP Kepsek</th>
                            <th class="text-center">Total Harga</th>
                            <th class="text-center">Nilai Dibayar</th>
                            <th class="text-center">Tgl Pesan</th>
                            <th class="text-center">Tgl Lunas</th>
                        </tr>
                    </thead>
                </table>
            </div>
        </div>
    </div>
    <!-- /.row -->
</div>
<!-- /.container-fluid -->
