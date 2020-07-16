<div class="container-fluid">

    <!-- Page Heading -->
    <div class="row">
        <div class="col-lg-12">
            <h1 class="page-header">
                Pelanggan Sudah Pesan
            </h1>
            <ol class="breadcrumb">
                <li>
                    <a href="<?php echo base_url() . ADMIN_PATH; ?>">Beranda</a>
                </li>
                <li class="active">
                    <a href="<?php echo base_url() . ADMIN_PATH.'/customer'; ?>">Pelanggan</a>
                </li>
                <li class="active">
                    Sudah Pesan
                </li>
            </ol>
        </div>
    </div>
    <!-- /.row -->

    <div class="row">
        <div class="col-lg-12">
            <div class="table-responsive">
                <table class="table table-striped dt-responsive nowrap table-loader" id="datatableCustomerHasOrder">
                    <thead>
                        <tr>
                            <th class="text-center">Kode</th>
                            <th class="text-center">NPSN</th>
                            <th class="text-center">Nama Sekolah</th>
                            <th class="text-center">Propinsi</th>
                            <th class="text-center">Kab/Kota</th>
                            <th class="text-center">Kecamatan</th>
                            <th class="text-center">Alamat</th>
                            <th class="text-center">Telpon Sekolah</th>
                            <th class="text-center">Email Sekolah</th>
                            <th class="text-center">Nama Kepsek</th>
                            <th class="text-center">Telpon Kepsek</th>
                            <th class="text-center">Email Kepsek</th>
                            <th class="text-center">Nama Operator</th>
                            <th class="text-center">Telpon Operator</th>
                            <th class="text-center">Email Operator</th>
                            <th class="text-center">Tgl Pesan</th>
                            <th class="text-center">Nilai Pesanan</th>
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