<div class="container-fluid">

    <div class="row">
        <div class="col-lg-12">
            <h1 class="page-header">
                <small>Ringkasan Statistik</small>
            </h1>
        </div>
    </div>
    <?php if(in_array($this->adm_level, $this->backoffice_superadmin_area)) { ?>
    <div class="row">
        <div class="col-lg-3 col-md-6">
            <div class="panel panel-primary">
                <div class="panel-heading">
                    <div class="row">
                        <div class="col-xs-3">
                            <i class="fa fa-shopping-cart fa-5x"></i>
                        </div>
                        <div class="col-xs-9 text-right">
                            <div class="huge"><?php echo $totalOrder; ?></div>
                            <div>Pesanan Masuk</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-md-6">
            <div class="panel panel-yellow">
                <div class="panel-heading">
                    <div class="row">
                        <div class="col-xs-3">
                            <i class="fa fa-truck fa-5x"></i>
                        </div>
                        <div class="col-xs-9 text-right">
                            <div class="huge"><?php echo $totalProcess; ?></div>
                            <div>Pesanan Diproses</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-md-6">
            <div class="panel panel-green">
                <div class="panel-heading">
                    <div class="row">
                        <div class="col-xs-3">
                            <i class="fa fa-money fa-5x"></i>
                        </div>
                        <div class="col-xs-9 text-right">
                            <div class="huge"><?php echo $totalPaid; ?></div>
                            <div>Pesanan Dibayar</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-md-6">
            <div class="panel panel-red">
                <div class="panel-heading">
                    <div class="row">
                        <div class="col-xs-3">
                            <i class="fa fa-warning fa-5x"></i>
                        </div>
                        <div class="col-xs-9 text-right">
                            <div class="huge"><?php echo $totalUnpaid; ?></div>
                            <div>Belum Dibayar</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <?php } ?>

    <div class="row">
        <div class="col-lg-12">
            <div class="panel panel-default">
                <div class="panel-heading">
                    <h3 class="panel-title"><i class="fa fa-money fa-fw"></i> 10 Transaksi Terakhir</h3>
                </div>
                <div class="panel-body">
                    <div class="table-responsive">
                        <table class="table table-bordered table-hover table-striped table-dashboard">
                            <thead>
                                <tr>
                                    <th class="text-center">Kode</th>
                                    <th class="text-center">Nama Sekolah</th>
                                    <th class="text-center">Propinsi</th>
                                    <th class="text-center">Kab/Kota</th>
                                    <th class="text-center">Kelas</th>
                                    <th class="text-center">Kategori</th>
                                    <th class="text-center">Tanggal Pesanan</th>
                                    <th class="text-center">Total Harga</th>
                                    <th class="text-center">Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php 
                                $i=1; 
                                foreach ($listData as $val) {
                                    if($i>10) break;
                                ?>
                                <tr>
                                    <td class="text-center"><a href="<?php echo base_url(ADMIN_PATH.'/orders/detail/'.$val->id_order); ?>"><?php echo $val->reference; ?></a></td>
                                    <td><?php echo $val->school_name; ?></td>
                                    <td><?php echo $val->provinsi; ?></td>
                                    <td><?php echo $val->kabupaten; ?></td>
                                    <td class="text-center"><?php echo $val->category; ?></td>
                                    <td class="text-center"><?php echo $val->type; ?></td>
                                    <td class="text-center"><?php echo $val->date_add; ?></td>
                                    <td class="text-right"><?php echo toRupiah($val->total_paid); ?></td>
                                    <td class="text-center"><span class="label <?php echo $val->label; ?>"><?php echo $val->order_state; ?></span></td>
                                </tr>
                                <?php $i++; } ?>
                            </tbody>
                        </table>
                    </div>
                    <div class="text-right">
                        <a href="<?php echo base_url() . ADMIN_PATH; ?>/orders">Lihat Semua Transaksi <i class="fa fa-arrow-circle-right"></i></a>
                    </div>
                </div>
            </div>
        </div>
    </div>

</div>
<!-- /.container-fluid -->