<div class="container-fluid">
    <!-- Page Heading -->
    <div class="row">
        <div class="col-lg-12">
            <h1 class="page-header">
                Produk
            </h1>
            <ol class="breadcrumb">
                <li>
                    <a href="<?php echo base_url() . ADMIN_PATH; ?>">Beranda</a>
                </li>
                <li class="active">
                    Produk
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
                <table class="display table table-striped responsive datatable data-table" data-table-def="datatableProduct" id="datatableProduct" width="100%">
                    <thead>
                        <tr>
                            <th class="text-center">Kode Buku</th>
                            <th class="text-center">Kode Referensi</th>
                            <th class="text-center">Kategori</th>
                            <th class="text-center">Judul</th>
                            <th class="text-center">Gambar</th>
                            <th class="text-center">Deskripsi</th>
                            <th class="text-center">Supplier</th>
                            <th class="text-center">Quantity</th>
                            <th class="text-center">Harga 1</th>
                            <th class="text-center">Harga 2</th>
                            <th class="text-center">Harga 3</th>
                            <th class="text-center">Harga 4</th>
                            <th class="text-center">Harga 5</th>
                            <th class="text-center">Harga Non 1</th>
                            <th class="text-center">Harga Non 2</th>
                            <th class="text-center">Harga Non 3</th>
                            <th class="text-center">Harga Non 4</th>
                            <th class="text-center">Harga Non 5</th>
                            <th class="text-center">Lebar</th>
                            <th class="text-center">Panjang</th>
                            <th class="text-center">Berat</th>
                            <th class="text-center">Halaman</th>
                            <th class="text-center">Kapasitas</th>
                            <th class="text-center">Gudang Proses</th>
                            <th class="text-center">Sekolah Pesan</th>
                            <th class="text-center">Tanggal Input</th>
                            <th class="text-center">Tanggal Update</th>
                        </tr>
                    </thead>
                </table>
            </div>
        </div>
    </div>
    <!-- /.row -->
</div>
<!-- /.container-fluid -->
