<div class="container-fluid">
    <!-- Page Heading -->
    <div class="row">
        <div class="col-lg-12">
            <h1 class="page-header">
                Ongkos Kirim
            </h1>
            <ol class="breadcrumb">
                <li>
                    <a href="<?php echo base_url() . ADMIN_PATH; ?>">Beranda</a>
                </li>
                <li class="active">
                    Ongkos Kirim
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
                <table class="table table-striped dt-responsive nowrap table-loader" id="datatableOngkir">
                    <thead>
                        <tr>
                            <th class="text-center">ID Ongkir</th>
                            <th class="text-center">Kode Provinsi</th>
                            <th class="text-center">Provinsi</th>
                            <th class="text-center">Kode Kabupaten</th>
                            <th class="text-center">Kabupaten</th>
                            <th class="text-center">Kode Kecamatan</th>
                            <th class="text-center">Kecamatan</th>
                            <th class="text-center">Tarif Economy Komputer</th>
                            <th class="text-center">Tarif Regular Komputer</th>
                            <th class="text-center">Tarif Economy Paket Covid</th>
                            <th class="text-center">Tarif Economy Paket Covid Non Disinfectan</th>
                        </tr>
                    </thead>
                </table>
            </div>
        </div>
    </div>
    <!-- /.row -->
</div>
<!-- /.container-fluid -->
