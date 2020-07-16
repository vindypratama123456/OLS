<div class="container-fluid">
    <!-- Page Heading -->
    <div class="row">
        <div class="col-lg-12">
            <h1 class="page-header">
                <?php echo $page_title; ?>
            </h1>
            <ol class="breadcrumb">
                <li>
                    <a href="<?php echo base_url() . ADMIN_PATH; ?>">Beranda</a>
                </li>
                <li class="active">
                    <?php echo $page_title; ?>
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
        </div>
    </div>
    <div class="row">
        <div class="col-lg-12">
            <div class="panel panel-default">
                <div class="panel-heading">
                    <h3 class="panel-title">Filter tanggal bukti potong</h3>
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
                <table class="table table-striped dt-responsive nowrap table-loader" id="datatableAmountPPh">
                    <thead>
                        <tr>
                            <!-- <th class="text-center">No PD</th>z
                            <th class="text-center">Nama</th>
                            <th class="text-center">NPWP</th>
                            <th class="text-center">Nilai Komisi</th>
                            <th class="text-center">% PPh</th>
                            <th class="text-center">Nilai PPh</th>
                            <th class="text-center">Tgl Bukti Potong</th> -->

                            <th class="text-center">No PD</th>
                            <th class="text-center">Masa Pajak</th>
                            <th class="text-center">Tahun Pajak</th>
                            <th class="text-center">Pembetulan</th>
                            <th class="text-center">No. Bukti Potong</th>
                            <th class="text-center">Periode</th>
                            <th class="text-center">No. NPWP</th>
                            <th class="text-center">KTP</th>
                            <th class="text-center">Nama</th>
                            <th class="text-center">Alamat</th>
                            <th class="text-center">Wajib Pajak Luar Negeri</th>
                            <th class="text-center">Kode Negara</th>
                            <th class="text-center">Kode Pajak</th>
                            <th class="text-center">Nilai Komisi</th>
                            <th class="text-center">Jumlah DPP</th>
                            <th class="text-center">Tarif</th>
                            <th class="text-center">Nilai PPh</th>
                            <th class="text-center">NPWP Pemotong</th>
                            <th class="text-center">Nama Pemotong</th>
                            <th class="text-center">Tgl Bukti Potong</th>
                        </tr>
                    </thead>
                </table>
            </div>
        </div>
    </div>
    <!-- /.row -->
</div>
<!-- /.container-fluid -->