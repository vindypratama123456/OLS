<style type="text/css">
    div.DTTT { margin-bottom: 0.5em; float: right; }
    div.dataTables_wrapper { clear: both; }
</style>
<div class="container-fluid">
    <!-- Page Heading -->
    <div class="row">
        <div class="col-lg-12">
            <h1 class="page-header">
                Komisi Disetujui
            </h1>
            <ol class="breadcrumb">
                <li>
                    <a href="<?php echo base_url() . ADMIN_PATH; ?>">Beranda</a>
                </li>
                <li>
                    <a href="#">Komisi</a>
                </li>
                <li class="active">
                    Disetujui
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
            if($this->session->flashdata('msg_error_commision')) {
                echo notif('danger',$this->session->flashdata('msg_error_commision'));
            }
            ?>
        </div>
    </div>
    <div class="row">
        <div class="col-lg-12">
            <div class="table-responsive">
                <p>
                    <button type="button" class="btn btn-primary btn-lg" id="processBatch" onclick="processBatch()" style="display:none;">
                        <i class="glyphicon glyphicon-th-list"></i>&nbsp;&nbsp;<b>Proses Batch</b>
                    </button>
                    <div id="total-sum" class="alert alert-success" style="display:none"></div>
                </p>
                <table class="table table-striped dt-responsive wrap table-loader" data-table-def="datatableToProcessed" id="datatableToProcessed">
                    <thead>
                        <tr>
                            <th class="text-center">
                                <input type="checkbox" id="payout_check" class="payout_check">
                            </th>
                            <th class="text-center">Kode</th>
                            <th class="text-center">Nama Sekolah</th>
                            <th class="text-center">Kelas</th>
                            <th class="text-center">Provinsi</th>
                            <th class="text-center">Kab/Kota</th>
                            <th class="text-center">Tgl Pesanan</th>
                            <th class="text-center">Total Harga</th>
                            <th class="text-center">Mitra</th>
                            <th class="text-center">Komisi (%)</th>
                            <th class="text-center">PPh (%)</th>
                            <th class="text-center nilai-komisi">Komisi (Rp.)</th>
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