<div class="container-fluid">
    <!-- Page Heading -->
    <div class="row">
        <div class="col-lg-12">
            <h1 class="page-header">
                Status Bayar Pesanan Sekolah
            </h1>
            <ol class="breadcrumb">
                <li>
                    <a href="<?php echo base_url() . ADMIN_PATH; ?>">Beranda</a>
                </li>
                <li class="active">
                    Status Bayar Pesanan Sekolah
                </li>
            </ol>
        </div>
    </div>
    <!-- /.row -->
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
                <table class="table table-striped dt-responsive nowrap table-loader" id="datatableAllOrder">
                    <thead>
                        <tr>
                            <th class="text-center">Kode</th>
                            <th class="text-center">Nama Sekolah</th>
                            <th class="text-center">Provinsi</th>
                            <th class="text-center">Kab/Kota</th>
                            <th class="text-center">Kelas</th>
                            <th class="text-center">Kategori</th>
                            <th class="text-center">Tgl Pesan</th>
                            <th class="text-center">Status</th>
                            <th class="text-center">Nilai Pesanan</th>
                            <th class="text-center">Nilai Dibayar</th>
                            <th class="text-center">Nilai Piutang</th>
                            <th class="text-center">Nama Mitra</th>
                            <th class="text-center">Telp. Sekolah</th>
                            <th class="text-center">Nama Operator</th>
                            <th class="text-center">HP Operator</th>
                            <th class="text-center">Nama Kepsek</th>
                            <th class="text-center">HP Kepsek</th>
                        </tr>
                    </thead>
                </table>
            </div>
        </div>
    </div>
    <!-- /.row -->
</div>
<!-- /.container-fluid -->