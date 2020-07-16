<div class="container-fluid">
    <!-- Page Heading -->
    <div class="row">
        <div class="col-lg-12">
            <h1 class="page-header">
                Katalog #<?php echo $detil['id_product']; ?>
            </h1>
            <ol class="breadcrumb">
                <li>
                    <a href="<?php echo base_url() . ADMIN_PATH; ?>">Beranda</a>
                </li>
                <li class="active">
                    <a href="<?php echo base_url() . ADMIN_PATH; ?>/catalog">Katalog</a>
                </li>
                <li class="active">
                    Detil
                </li>
            </ol>
        </div>
    </div>
    <!-- /.row -->
    <div class="row">
        <div class="col-lg-12">
            <div class="panel panel-default">
                <!-- Default panel contents -->
                <div class="panel-heading"><h4>Data Buku</h4></div>
                <div class="panel-body">
                    <!-- List group -->
                    <ul class="list-group">
                        <li class="list-group-item">ID: <?php echo $detil['id_product']; ?></li>
                        <li class="list-group-item">Kode Buku: <?php echo $detil['kode_buku']; ?></li>
                        <li class="list-group-item">Judul Buku: <?php echo $detil['name']; ?></li>
                        <li class="list-group-item">Stok: <?php echo $detil['quantity']; ?></li>
                        <li class="list-group-item">Harga Zona 1: <?php echo toRupiah($detil['price_1']); ?></li>
                        <li class="list-group-item">Harga Zona 2: <?php echo toRupiah($detil['price_2']); ?></li>
                        <li class="list-group-item">Harga Zona 3: <?php echo toRupiah($detil['price_3']); ?></li>
                        <li class="list-group-item">Harga Zona 4: <?php echo toRupiah($detil['price_4']); ?></li>
                        <li class="list-group-item">Harga Zona 5: <?php echo toRupiah($detil['price_5']); ?></li>
                    </ul>
                </div>
            </div>
            <a href="<?php echo base_url().ADMIN_PATH; ?>/catalog" class="btn btn-primary btn-lg pull-right">Kembali</a>
        </div>
    </div>
    <!-- /.row -->
</div>
<!-- /.container-fluid -->