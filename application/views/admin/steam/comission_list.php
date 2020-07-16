<style type="text/css">
    div.DTTT { margin-bottom: 0.5em; float: right; }
    div.dataTables_wrapper { clear: both; }
</style>
<div class="container-fluid">
    <!-- Page Heading -->
    <div class="row">
        <div class="col-lg-12">
            <h1 class="page-header">
                Daftar Komisi
            </h1>
            <ol class="breadcrumb">
                <li>
                    <a href="<?php echo base_url() . ADMIN_PATH; ?>">Beranda</a>
                </li>
                <li>
                    <a href="#">Komisi</a>
                </li>
                <li class="active">
                    Daftar Komisi
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
                <?php if (in_array($this->adm_level, $this->backoffice_superadmin_area)) { ?>
                <button type="button" class="btn btn-primary btn-large" onclick="processBatch()">
                    <i class="glyphicon glyphicon-th-list"></i>&nbsp;&nbsp;<b>Proses Batch</b>
                </button>
                <?php } ?>
                <table class="table table-striped dt-responsive wrap table-loader" data-table-def="datatableProposed" id="datatableProposed">
                    <thead>
                        <tr>
                            <th class="text-center"><input type="checkbox" id="check_all" value=""></th>
                            <th class="text-center">Kode</th>
                            <th class="text-center">Nama Pelanggan</th>
                            <th class="text-center">Tgl Pesanan</th>
                            <th class="text-center">Total Harga</th>
                            <th class="text-center">Sales</th>
                            <th class="text-center">Komisi (%)</th>
                            <th class="text-center">PPh (%)</th>
                            <th class="text-center">Komisi (Rp.)</th>
                            <th class="text-center">Tgl Pengajuan</th>
                        </tr>
                    </thead>
                </table>
                <button class="btn btn-success btn-pilih" type="button">Proses Approval</button>
            </div>
        </div>
    </div>
    <!-- /.row -->
</div>
<!-- /.container-fluid -->