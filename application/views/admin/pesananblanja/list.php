<div class="container-fluid">
    <!-- Page Heading -->
    <div class="row">
        <div class="col-lg-12">
            <h1 class="page-header">
                DAFTAR PESANAN SIPLAH GAGAL TRANSFER
            </h1>
            <ol class="breadcrumb">
                <li>
                    <a href="<?php echo base_url() . ADMIN_PATH; ?>">Beranda</a>
                </li>
                <li class="active">
                    Daftar Pesanan Siplah gagal transfer
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
            if ($this->session->flashdata('msg_failed')) {
                echo notif('danger', $this->session->flashdata('msg_failed'));
            }
            ?>
        </div>
    </div>
    <div class="row">
        <div class="col-lg-12">
            <div class="table-responsive">
                <table class="table table-striped dt-responsive nowrap table-loader" id="datatableError">
                    <thead>
                        <tr>
                            <th class="text-center">NOMOR PO</th>
                            <th class="text-center">TANGGAL PESANAN</th>
                            <th class="text-center">TANGGAL TRANSFER</th>
                            <th class="text-center">KETERANGAN</th>
                            <th class="text-center">AKSI</th>
                        </tr>
                    </thead>
                </table>
            </div>
        </div>
    </div>
    <!-- /.row -->
</div>
<!-- /.container-fluid -->
