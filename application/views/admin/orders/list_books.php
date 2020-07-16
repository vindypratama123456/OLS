<div class="container-fluid">

    <!-- Page Heading -->
    <div class="row">
        <div class="col-lg-12">
            <h1 class="page-header">
                Buku Dipesan
            </h1>
            <ol class="breadcrumb">
                <li>
                    <a href="<?php echo base_url() . ADMIN_PATH; ?>">Beranda</a>
                </li>
                <li>
                    <a href="<?php echo base_url() . ADMIN_PATH; ?>/orders">Pesanan</a>
                </li>
                <li class="active">
                    Buku Dipesan
                </li>
            </ol>
        </div>
    </div>
    <!-- /.row -->

    <div class="row">
        <div class="col-lg-12">
            <div class="table-responsive">
                <?php if($listdata) { ?>
                <table class="table table-bordered table-hover table-striped">
                    <thead>
                        <tr>
                            <th class="text-center" width="10%">Kode Buku</th>
                            <th class="text-center" width="58%">Judul Buku</th>
                            <th class="text-center" width="15%">ISBN</th>
                            <th class="text-center" width="12%">Kategori</th>
                            <th class="text-center" width="10%">Total</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach($listdata as $row) { ?>
                        <tr>
                            <td class="text-center"><?php echo $row->kode_buku; ?></td>
                            <td><?php echo $row->judul_buku; ?></td>
                            <td class="text-center"><?php echo $row->isbn; ?></td>
                            <td class="text-center"><?php echo $row->kategori; ?></td>
                            <td class="text-center"><b><?php echo $row->total; ?></b></td>
                        </tr>
                        <?php } ?>
                    </tbody>
                </table>
                <?php
                } else {
                    echo '<div class="well well-lg">Maaf, data tidak tersedia.</div>';
                } 
                ?>
            </div>
        </div>
    </div>
    <!-- /.row -->

</div>
<!-- /.container-fluid -->