<div class="container-fluid">
    <!-- Page Heading -->
    <div class="row">
        <div class="col-lg-12">
            <h1 class="page-header">
                Pesanan Sekolah (Belum Lunas)
            </h1>
            <ol class="breadcrumb">
                <li>
                    <a href="<?php echo base_url() . ADMIN_PATH; ?>">Beranda</a>
                </li>
                <li class="active">
                    Pesanan Sekolah (Belum Lunas)
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
                <div class="col-lg-4">
                    <div class="panel panel-yellow">
                        <div class="panel-heading">
                        <div class="row">
                            <div class="col-xs-3">
                                <i class="fa fa-money fa-5x"></i>
                            </div>
                            <div class="col-xs-9 text-right">
                                <div style="font-size:22px;"><?php echo toRupiah($nilai_pesanan); ?></div>
                                    <div>Nilai Total Pesanan</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4">
                    <div class="panel panel-green">
                        <div class="panel-heading">
                        <div class="row">
                            <div class="col-xs-3">
                                <i class="fa fa-money fa-5x"></i>
                            </div>
                            <div class="col-xs-9 text-right">
                                <div style="font-size:22px;"><?php echo toRupiah($nilai_diinput); ?></div>
                                    <div>Total Uang Masuk Yang Diinput</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4">
                    <div class="panel panel-red">
                        <div class="panel-heading">
                        <div class="row">
                            <div class="col-xs-3">
                                <i class="fa fa-money fa-5x"></i>
                            </div>
                            <div class="col-xs-9 text-right">
                                <div style="font-size:22px;"><?php echo toRupiah($nilai_piutang); ?></div>
                                    <div>Nilai Pesanan Belum Dibayar</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4">
                    <div class="panel panel-primary">
                        <div class="panel-heading">
                        <div class="row">
                            <div class="col-xs-3">
                                <i class="fa fa-money fa-5x"></i>
                            </div>
                            <div class="col-xs-9 text-right">
                                <div style="font-size:22px;"><?php echo toRupiah($nilai_lunas); ?></div>
                                    <div>Total Pesanan Lunas</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4">
                    <div class="panel panel-warning">
                        <div class="panel-heading">
                        <div class="row">
                            <div class="col-xs-3">
                                <i class="fa fa-money fa-5x"></i>
                            </div>
                            <div class="col-xs-9 text-right">
                                <div style="font-size:22px;"><?php echo toRupiah($nilai_diangsur); ?></div>
                                    <div>Total Pesanan Masih Mengangsur</div>
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
            <div class="panel panel-default">
                <div class="panel-heading">
                    <h3 class="panel-title">Filter tanggal pesanan</h3>
                </div>
                <div class="panel-body">
                    <div class="col-md-8">
                        <div class="input-group input-daterange">
                            <input type="text" name="start_date" id="start_date" class="form-control" placeholder="Pilih tanggal awal" readonly />
                            <div class="input-group-addon">-</div>
                            <input type="text" name="end_date" id="end_date" class="form-control" placeholder="Pilih tanggal akhir" readonly />
                        </div>
                    </div>
                    <div class="col-md-4">
                        <button name="search" id="search" class="btn btn-primary">Terapkan</button> &nbsp;
                        <button name="reset-filter-date" id="reset-filter-date" class="btn btn-default">Set Ulang</button>
                    </div>
                </div>
            </div>
            <div class="table-responsive">
                <table class="table table-striped dt-responsive nowrap table-loader" id="datatablePiutang">
                    <thead>
                        <tr>
                            <th class="text-center">Kode</th>
                            <th class="text-center">Nama Sekolah</th>
                            <th class="text-center">Kelas</th>
                            <th class="text-center">Kategori</th>
                            <th class="text-center">Tgl Pesan</th>
                            <th class="text-center">Status</th>
                            <th class="text-center">Nilai Pesanan</th>
                            <th class="text-center">Nilai Dibayar</th>
                            <th class="text-center">Nilai Piutang</th>
                            <th class="text-center">Nama Korwil / EC</th>
                            <th class="text-center">Telp. Sekolah</th>
                            <th class="text-center">Nama Operator</th>
                            <th class="text-center">HP Operator</th>
                            <th class="text-center">Nama Kepsek</th>
                            <th class="text-center">HP Kepsek</th>
                            <th class="text-center">Nama Mitra</th>
                            <th class="text-center">Nama RSM</th>
                            <th class="text-center">Hasil Konfirmasi</th>
                            <th class="text-center">Tanggal Konfirmasi</th>
                        </tr>
                    </thead>
                </table>
            </div>
        </div>
    </div>
    <!-- /.row -->
</div>
<!-- /.container-fluid -->