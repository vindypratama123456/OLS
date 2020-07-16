<div class="container-fluid">
    <!-- Page Heading -->
    <div class="row">
        <div class="col-lg-12">
            <h1 class="page-header">
                Mitra
            </h1>
            <ol class="breadcrumb">
                <li>
                    <a href="<?php echo base_url() . ADMIN_PATH; ?>">Beranda</a>
                </li>
                <li class="active">
                    Mitra
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
                <table class="table table-striped dt-responsive nowrap table-loader" id="datatableMitra">
                    <thead>
                        <tr>
                            <th class="text-center">Kode</th>
                            <th class="text-center">No. KTP</th>
                            <th class="text-center">Email</th>
                            <th class="text-center">Nama</th>
                            <th class="text-center">Jenis Kelamin</th>
                            <th class="text-center">Alamat</th>
                            <th class="text-center">Telpon/Hp</th>
                            <th class="text-center">Pemilik NPWP</th>
                            <th class="text-center">No. NPWP</th>
                            <th class="text-center">Alamat NPWP</th>
                            <th class="text-center">Bank</th>
                            <th class="text-center">No. Rekening</th>
                            <th class="text-center">Pemilik</th>
                            <th class="text-center">Nama Korwil</th>
                            <th class="text-center">Referensi</th>
                            <th class="text-center">Aktifasi</th>
                            <th class="text-center">Status</th>
                            <th class="text-center">Status Kontrak</th>
                        </tr>
                    </thead>
                </table>
            </div>
        </div>
    </div>
    <!-- /.row -->
</div>
<!-- /.container-fluid -->
