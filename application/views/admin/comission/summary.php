<style type="text/css">
    div.DTTT { margin-bottom: 0.5em; float: right; }
    div.dataTables_wrapper { clear: both; }
</style>
<div class="container-fluid">
    <div class="row">
        <div class="col-lg-12">
            <h1 class="page-header">
                Ringkasan Komisi
            </h1>
            <ol class="breadcrumb">
                <li>
                    <a href="<?php echo base_url() . ADMIN_PATH; ?>">Beranda</a>
                </li>
                <li>
                    <a href="#">Komisi</a>
                </li>
                <li class="active">
                    Ringkasan
                </li>
            </ol>
        </div>
    </div>

    <?php if($this->session->userdata('adm_level') == 4) { ?>
    <div class="row">
        <div class="col-lg-6 col-md-6">
            <div class="panel panel-yellow">
                <div class="panel-heading">
                    <div class="row">
                        <div class="col-xs-3">
                            <i class="fa fa-money fa-5x"></i>
                        </div>
                        <div class="col-xs-9 text-right">
                            <div class="huge"><?php echo toRupiah($pending_payout); ?></div>
                            <div>Komisi Tertunda</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-6 col-md-6">
            <div class="panel panel-green">
                <div class="panel-heading">
                    <div class="row">
                        <div class="col-xs-3">
                            <i class="fa fa-money fa-5x"></i>
                        </div>
                        <div class="col-xs-9 text-right">
                            <div class="huge"><?php echo toRupiah($success_payout); ?></div>
                            <div>Komisi Terbayar</div>
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
                    <h3 class="panel-title"><i class="fa fa-money fa-fw"></i> 10 Pengajuan Komisi Terakhir</h3>
                </div>
                <div class="panel-body">
                    <div class="table-responsive">
                        <table class="table table-bordered table-hover table-striped">
                            <thead>
                                <tr>
                                    <th><center>No.</center></th>
                                    <th><center>Kode</center></th>
                                    <th><center>Nama Sekolah</center></th>
                                    <th><center>Provinsi</center></th>
                                    <th><center>Kab/Kota</center></th>
                                    <th><center>Tgl Pesanan</center></th>
                                    <th><center>Total Harga</center></th></th>
                                    <th><center>Status</center></th></th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php 
                                $i=1; 
                                foreach ($payout as $data) {
                                ?>
                                <tr>
                                    <td class="text-center"><?php echo $i ?></td>
                                    <td class="text-center"><a href="<?php echo base_url(ADMIN_PATH.'/comission/payout/'.$data->id); ?>"><?php echo $data->reference; ?></a></td>
                                    <td><?php echo $data->school_name; ?></td>
                                    <td><?php echo $data->provinsi; ?></td>
                                    <td><?php echo $data->kabupaten; ?></td>
                                    <td class="text-center"><?php echo $data->date_order; ?></td>
                                    <td class="text-right"><?php echo toRupiah($data->total_paid); ?></td>
                                    <td class="text-center"><span class="label <?php echo $data->status_label; ?>"><?php echo $data->status; ?></span></td>
                                </tr>
                                <?php $i++; } ?>
                            </tbody>
                        </table>
                    </div>
                    <?php if (count($payout) > 10) { ?>
                    <div class="text-right">
                        <a href="<?php echo base_url() . ADMIN_PATH; ?>/comission">Lihat Semua Pesanan <i class="fa fa-arrow-circle-right"></i></a>
                    </div>
                    <?php } ?>
                </div>
            </div>
        </div>
    </div>
</div>
