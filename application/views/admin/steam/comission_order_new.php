<div class="container-fluid">
    <!-- Page Heading -->
    <div class="row">
        <div class="col-lg-12">
            <h1 class="page-header">
                Pesanan Baru
            </h1>
            <ol class="breadcrumb">
                <li>
                    <a href="<?php echo base_url() . ADMIN_PATH; ?>">Beranda</a>
                </li>
                <li>
                    <a href="#">Komisi</a>
                </li>
                <li class="active">
                    Pesanan Baru
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
        <!-- <button type="button" class="btn btn-success">Upload Pesanan</button> -->
        <div class="col-lg-12">
            <div class="table-responsive">
                <table class="table table-striped dt-responsive wrap table-loader" data-table-def="datatableNew" id="datatableNew">
                    <thead>
                        <tr>
                            <!-- <th class="text-center"><input type="checkbox" id="check_all_order" value=""></th> -->
                            <th class="text-center">Kode Pesanan</th>
                            <th class="text-center">Nama Pelanggan</th>
                            <th class="text-center">Tgl Pesanan</th>
                            <th class="text-center">Total Harga</th>
                            <th class="text-center">Sales</th>
                            <th class="text-center">Komisi (%)</th>
                            <th class="text-center">PPh (%)</th>
                            <th class="text-center">Komisi (Rp.)</th>
                        </tr>
                    </thead>
                </table>
                <!-- <button class="btn btn-success btn-pilih" type="button">Proses Komisi</button> -->
            </div>
        </div>
    </div>
    <!-- /.row -->
</div>
<!-- /.container-fluid -->