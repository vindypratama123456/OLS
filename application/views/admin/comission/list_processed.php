<style type="text/css">
    div.DTTT { margin-bottom: 0.5em; float: right; }
    div.dataTables_wrapper { clear: both; }
</style>
<div class="container-fluid">
    <!-- Page Heading -->
    <div class="row">
        <div class="col-lg-12">
            <h1 class="page-header">
                Komisi Diproses
            </h1>
            <ol class="breadcrumb">
                <li>
                    <a href="<?php echo base_url() . ADMIN_PATH; ?>">Beranda</a>
                </li>
                <li>
                    <a href="#">Komisi</a>
                </li>
                <li class="active">
                    Diproses
                </li>
            </ol>
        </div>
    </div>
    <!-- /.row -->
    <div class="row">
        <div class="col-lg-12">
            <?php 
            if($this->session->flashdata('msg_success_commision')) {
                echo notif('success',$this->session->flashdata('msg_success_commision'));
            }
            ?>
        </div>
    </div>
    <div class="row">
        <div class="col-lg-12">
            <div class="table-responsive">
                <table class="table table-striped dt-responsive wrap table-loader" id="datatableProcessed">
                    <thead>
                        <tr>
                            <th class="text-center">Kode</th>
                            <th class="text-center">No. PD</th>
                            <th class="text-center">Nama Sekolah</th>
                            <th class="text-center">Kelas</th>
                            <th class="text-center">Provinsi</th>
                            <th class="text-center">Kab/Kota</th>
                            <th class="text-center">Tgl Pesanan</th>
                            <th class="text-center">Total Harga</th>
                            <th class="text-center">Mitra</th>
                            <th class="text-center">Komisi (%)</th>
                            <th class="text-center">PPh (%)</th>
                            <th class="text-center">Komisi (Rp.)</th>
                            <th class="text-center">Tgl Pengajuan</th>
                        </tr>
                    </thead>
                </table>
            </div>
        </div>
    </div>
    <!-- /.row -->
</div>
<!-- /.container-fluid -->