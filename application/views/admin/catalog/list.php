<div class="container-fluid">
    <!-- Page Heading -->
    <div class="row">
        <div class="col-lg-12">
            <h1 class="page-header">
                Katalog
            </h1>
            <ol class="breadcrumb">
                <li>
                    <a href="<?php echo base_url() . ADMIN_PATH; ?>">Beranda</a>
                </li>
                <li class="active">
                    Katalog
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
            if($this->session->flashdata('msg_failed')) {
                echo notif('danger',$this->session->flashdata('msg_failed'));
            }
            ?>
        </div>
    </div>
    <div class="row">
        <div class="col-lg-12">
            <div class="table-responsive">
                <table class="table table-striped dt-responsive nowrap table-loader" id="datatableCatalog">
                    <thead>
                        <tr>
                            <th class="text-center">Kode Buku</th>
                            <th class="text-center">ISBN</th>
                            <th class="text-center">Judul Buku</th>
                            <th class="text-center">Kelas</th>
                            <th class="text-center">Tipe</th>
                            <th class="text-center">Zona 1</th>
                            <th class="text-center">Zona 2</th>
                            <th class="text-center">Zona 3</th>
                            <th class="text-center">Zona 4</th>
                            <th class="text-center">Zona 5</th>
                        </tr>
                    </thead>
                </table>
            </div>
        </div>
    </div>
    <!-- /.row -->
</div>
<!-- /.container-fluid -->