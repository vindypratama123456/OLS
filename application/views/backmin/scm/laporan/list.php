<!-- START BREADCRUMB -->
<ul class="breadcrumb">
    <li><a href="<?php echo base_url(BACKMIN_PATH); ?>">Dasbor</a></li>
    <li><a href="#">Laporan</a></li>
    <li class="active">Summary Stok</li>
</ul>
<!-- END BREADCRUMB -->
<!-- PAGE TITLE -->
<div class="page-title">                    
    <h2><span class="glyphicon glyphicon-bookmark"></span> Summary Stok Barang</h2>
    <div style="float:right;">
        <?php echo form_open(base_url(BACKMIN_PATH.'/scmlaporan/indexStok'), 'class="form-inline" role="form" method="POST" id="formSearchItem"'); ?>
            <div class="form-group">
                <input type="text" id="search_input" name="search_input" class="form-control search_input" placeholder="Cari buku atau kelas" value="<?php echo $term; ?>"/>
            </div>
            &nbsp;
            <button type="submit" class="btn btn-danger" id="btn_search_item" onclick="searchItem()">
                <span class="fa fa-search"></span> Cari
            </button>
            &nbsp;
            <button type="button" class="btn btn-primary" id="btn_reset_item" onclick="resetSearch()">
                <span class="fa fa-refresh"></span> Reset
            </button>
        <?php echo form_close(); ?>
    </div>
</div>
<!-- END PAGE TITLE -->                
<!-- PAGE CONTENT WRAPPER -->
<div class="page-content-wrap">
    <div class="row">                        
        <div class="col-md-12">
            <div class="panel panel-default">
                <div class="panel-body">
                    <div class="page-tabs" id="myTab">
                        <a href="#stok-fisik" class="active">Stok Fisik</a>
                        <a href="#stok-booking">Stok Booking</a>
                        <a href="#stok-available">Stok Available</a>
                        <a href="#stok-ip">Diambil IP</a>
                        <a href="#stok-kirim">Stok Kirim</a>
                        <a href="#stok-belum-kirim">Stok Belum Kirim</a>
                    </div>

                    <div class="page-content-wrap page-tabs-item active" id="stok-fisik">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="panel panel-default">
                                    <div class="panel-body">
                                        <?php if($liststok_fisik) { ?>
                                        <table class="table table-striped table-condensed">
                                            <thead>
                                                <tr>
                                                    <th>No.</th>
                                                    <th>Judul Buku</th>
                                                    <th class="text-center">Kelas</th>
                                                    <th class="text-center">Gudang<br>Medan</th>
                                                    <th class="text-center">Gudang<br>Palmerah</th>
                                                    <th class="text-center">Gudang<br>Bawen</th>
                                                    <th class="text-center">Gudang<br>Bandung</th>
                                                    <th class="text-center">Gudang<br>Cikarang</th>
                                                    <th class="text-center">Gudang<br>Surabaya</th>
                                                    <th class="text-center">Gudang<br>Gianyar</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php $no=1; foreach ($liststok_fisik as $val) { ?>
                                                <tr>
                                                    <td><?php echo $no; ?></td>
                                                    <td><?php echo $val->judul_buku.' [<b>'.$val->kode_buku.'</b>]'; ?></td>
                                                    <td class="text-center"><?php echo $val->kelas; ?></td>
                                                    <td class="text-center"><span class="label label-<?php echo ($val->gudang_medan<1) ? 'danger' : 'info'; ?> label-form"><?php echo $val->gudang_medan; ?></span></td>
                                                    <td class="text-center"><span class="label label-<?php echo ($val->gudang_palmerah<1) ? 'danger' : 'info'; ?> label-form"><?php echo $val->gudang_palmerah; ?></span></td>
                                                    <td class="text-center"><span class="label label-<?php echo ($val->gudang_bawen<1) ? 'danger' : 'info'; ?> label-form"><?php echo $val->gudang_bawen; ?></span></td>
                                                    <td class="text-center"><span class="label label-<?php echo ($val->gudang_bandung<1) ? 'danger' : 'info'; ?> label-form"><?php echo $val->gudang_bandung; ?></span></td>
                                                    <td class="text-center"><span class="label label-<?php echo ($val->gudang_cikarang<1) ? 'danger' : 'info'; ?> label-form"><?php echo $val->gudang_cikarang; ?></span></td>
                                                    <td class="text-center"><span class="label label-<?php echo ($val->gudang_surabaya<1) ? 'danger' : 'info'; ?> label-form"><?php echo $val->gudang_surabaya; ?></span></td>
                                                    <td class="text-center"><span class="label label-<?php echo ($val->gudang_gianyar<1) ? 'danger' : 'info'; ?> label-form"><?php echo $val->gudang_gianyar; ?></span></td>
                                                </tr>
                                                <?php $no++; } ?>
                                            </tbody>
                                        </table>
                                        <?php } else { echo 'Maaf, data tidak tersedia :('; } ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="page-content-wrap page-tabs-item" id="stok-booking">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="panel panel-default">
                                    <div class="panel-body">
                                        <?php if($liststok_booking) { ?>
                                        <table class="table table-striped table-condensed">
                                            <thead>
                                                <tr>
                                                    <th>No.</th>
                                                    <th>Judul Buku</th>
                                                    <th class="text-center">Kelas</th>
                                                    <th class="text-center">Gudang<br>Medan</th>
                                                    <th class="text-center">Gudang<br>Palmerah</th>
                                                    <th class="text-center">Gudang<br>Bawen</th>
                                                    <th class="text-center">Gudang<br>Bandung</th>
                                                    <th class="text-center">Gudang<br>Cikarang</th>
                                                    <th class="text-center">Gudang<br>Surabaya</th>
                                                    <th class="text-center">Gudang<br>Gianyar</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php $no=1; foreach ($liststok_booking as $val) { ?>
                                                <tr>
                                                    <td><?php echo $no; ?></td>
                                                    <td><?php echo $val->judul_buku.' [<b>'.$val->kode_buku.'</b>]'; ?></td>
                                                    <td class="text-center"><?php echo $val->kelas; ?></td>
                                                    <td class="text-center"><span class="label label-<?php echo ($val->gudang_medan<1) ? 'success' : 'warning'; ?> label-form"><?php echo $val->gudang_medan; ?></span></td>
                                                    <td class="text-center"><span class="label label-<?php echo ($val->gudang_palmerah<1) ? 'success' : 'warning'; ?> label-form"><?php echo $val->gudang_palmerah; ?></span></td>
                                                    <td class="text-center"><span class="label label-<?php echo ($val->gudang_bawen<1) ? 'success' : 'warning'; ?> label-form"><?php echo $val->gudang_bawen; ?></span></td>
                                                    <td class="text-center"><span class="label label-<?php echo ($val->gudang_bandung<1) ? 'success' : 'warning'; ?> label-form"><?php echo $val->gudang_bandung; ?></span></td>
                                                    <td class="text-center"><span class="label label-<?php echo ($val->gudang_cikarang<1) ? 'success' : 'warning'; ?> label-form"><?php echo $val->gudang_cikarang; ?></span></td>
                                                    <td class="text-center"><span class="label label-<?php echo ($val->gudang_surabaya<1) ? 'success' : 'warning'; ?> label-form"><?php echo $val->gudang_surabaya; ?></span></td>
                                                    <td class="text-center"><span class="label label-<?php echo ($val->gudang_gianyar<1) ? 'success' : 'warning'; ?> label-form"><?php echo $val->gudang_gianyar; ?></span></td>
                                                </tr>
                                                <?php $no++; } ?>
                                            </tbody>
                                        </table>
                                        <?php } else { echo 'Maaf, data tidak tersedia :('; } ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="page-content-wrap page-tabs-item" id="stok-available">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="panel panel-default">
                                    <div class="panel-body">
                                        <?php if($liststok_available) { ?>
                                        <table class="table table-striped table-condensed">
                                            <thead>
                                                <tr>
                                                    <th>No.</th>
                                                    <th>Judul Buku</th>
                                                    <th class="text-center">Kelas</th>
                                                    <th class="text-center">Gudang<br>Medan</th>
                                                    <th class="text-center">Gudang<br>Palmerah</th>
                                                    <th class="text-center">Gudang<br>Bawen</th>
                                                    <th class="text-center">Gudang<br>Bandung</th>
                                                    <th class="text-center">Gudang<br>Cikarang</th>
                                                    <th class="text-center">Gudang<br>Surabaya</th>
                                                    <th class="text-center">Gudang<br>Gianyar</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php $no=1; foreach ($liststok_available as $val) { ?>
                                                <tr>
                                                    <td><?php echo $no; ?></td>
                                                    <td><?php echo $val->judul_buku.' [<b>'.$val->kode_buku.'</b>]'; ?></td>
                                                    <td class="text-center"><?php echo $val->kelas; ?></td>
                                                    <td class="text-center"><span class="label label-<?php echo ($val->gudang_medan<1) ? 'danger' : 'info'; ?> label-form"><?php echo $val->gudang_medan; ?></span></td>
                                                    <td class="text-center"><span class="label label-<?php echo ($val->gudang_palmerah<1) ? 'danger' : 'info'; ?> label-form"><?php echo $val->gudang_palmerah; ?></span></td>
                                                    <td class="text-center"><span class="label label-<?php echo ($val->gudang_bawen<1) ? 'danger' : 'info'; ?> label-form"><?php echo $val->gudang_bawen; ?></span></td>
                                                    <td class="text-center"><span class="label label-<?php echo ($val->gudang_bandung<1) ? 'danger' : 'info'; ?> label-form"><?php echo $val->gudang_bandung; ?></span></td>
                                                    <td class="text-center"><span class="label label-<?php echo ($val->gudang_cikarang<1) ? 'danger' : 'info'; ?> label-form"><?php echo $val->gudang_cikarang; ?></span></td>
                                                    <td class="text-center"><span class="label label-<?php echo ($val->gudang_surabaya<1) ? 'danger' : 'info'; ?> label-form"><?php echo $val->gudang_surabaya; ?></span></td>
                                                    <td class="text-center"><span class="label label-<?php echo ($val->gudang_gianyar<1) ? 'danger' : 'info'; ?> label-form"><?php echo $val->gudang_gianyar; ?></span></td>
                                                </tr>
                                                <?php $no++; } ?>
                                            </tbody>
                                        </table>
                                        <?php } else { echo 'Maaf, data tidak tersedia :('; } ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="page-content-wrap page-tabs-item" id="stok-ip">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="panel panel-default">
                                    <div class="panel-body">
                                        <?php if($liststok_diambil_ip) { ?>
                                        <table class="table table-striped table-condensed">
                                            <thead>
                                                <tr>
                                                    <th>No.</th>
                                                    <th>Judul Buku</th>
                                                    <th class="text-center">Kelas</th>
                                                    <th class="text-center">Gudang<br>Medan</th>
                                                    <th class="text-center">Gudang<br>Palmerah</th>
                                                    <th class="text-center">Gudang<br>Bawen</th>
                                                    <th class="text-center">Gudang<br>Bandung</th>
                                                    <th class="text-center">Gudang<br>Cikarang</th>
                                                    <th class="text-center">Gudang<br>Surabaya</th>
                                                    <th class="text-center">Gudang<br>Gianyar</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php $no=1; foreach ($liststok_diambil_ip as $val) { ?>
                                                <tr>
                                                    <td><?php echo $no; ?></td>
                                                    <td><?php echo $val->judul_buku.' [<b>'.$val->kode_buku.'</b>]'; ?></td>
                                                    <td class="text-center"><?php echo $val->kelas; ?></td>
                                                    <td class="text-center"><span class="label label-<?php echo ($val->gudang_medan<1) ? 'success' : 'warning'; ?> label-form"><?php echo $val->gudang_medan ? $val->gudang_medan : 0; ?></span></td>
                                                    <td class="text-center"><span class="label label-<?php echo ($val->gudang_palmerah<1) ? 'success' : 'warning'; ?> label-form"><?php echo $val->gudang_palmerah ? $val->gudang_palmerah : 0; ?></span></td>
                                                    <td class="text-center"><span class="label label-<?php echo ($val->gudang_bawen<1) ? 'success' : 'warning'; ?> label-form"><?php echo $val->gudang_bawen ? $val->gudang_bawen : 0; ?></span></td>
                                                    <td class="text-center"><span class="label label-<?php echo ($val->gudang_bandung<1) ? 'success' : 'warning'; ?> label-form"><?php echo $val->gudang_bandung ? $val->gudang_bandung : 0; ?></span></td>
                                                    <td class="text-center"><span class="label label-<?php echo ($val->gudang_cikarang<1) ? 'success' : 'warning'; ?> label-form"><?php echo $val->gudang_cikarang ? $val->gudang_cikarang : 0; ?></span></td>
                                                    <td class="text-center"><span class="label label-<?php echo ($val->gudang_surabaya<1) ? 'success' : 'warning'; ?> label-form"><?php echo $val->gudang_surabaya ? $val->gudang_surabaya : 0; ?></span></td>
                                                    <td class="text-center"><span class="label label-<?php echo ($val->gudang_gianyar<1) ? 'success' : 'warning'; ?> label-form"><?php echo $val->gudang_gianyar ? $val->gudang_gianyar : 0; ?></span></td>
                                                </tr>
                                                <?php $no++; } ?>
                                            </tbody>
                                        </table>
                                        <?php } else { echo 'Maaf, data tidak tersedia :('; } ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="page-content-wrap page-tabs-item" id="stok-kirim">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="panel panel-default">
                                    <div class="panel-body">
                                        <?php if($liststok_diambil_ip) { ?>
                                        <table class="table table-striped table-condensed">
                                            <thead>
                                                <tr>
                                                    <th>No.</th>
                                                    <th>Judul Buku</th>
                                                    <th class="text-center">Kelas</th>
                                                    <th class="text-center">Gudang<br>Medan</th>
                                                    <th class="text-center">Gudang<br>Palmerah</th>
                                                    <th class="text-center">Gudang<br>Bawen</th>
                                                    <th class="text-center">Gudang<br>Bandung</th>
                                                    <th class="text-center">Gudang<br>Cikarang</th>
                                                    <th class="text-center">Gudang<br>Surabaya</th>
                                                    <th class="text-center">Gudang<br>Gianyar</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php $no=1; foreach ($liststok_kirim as $val) { ?>
                                                <tr>
                                                    <td><?php echo $no; ?></td>
                                                    <td><?php echo $val->judul_buku.' [<b>'.$val->kode_buku.'</b>]'; ?></td>
                                                    <td class="text-center"><?php echo $val->kelas; ?></td>
                                                    <td class="text-center"><span class="label label-<?php echo ($val->gudang_medan<1) ? 'danger' : 'info'; ?> label-form"><?php echo $val->gudang_medan ? $val->gudang_medan : 0; ?></span></td>
                                                    <td class="text-center"><span class="label label-<?php echo ($val->gudang_palmerah<1) ? 'danger' : 'info'; ?> label-form"><?php echo $val->gudang_palmerah ? $val->gudang_palmerah : 0; ?></span></td>
                                                    <td class="text-center"><span class="label label-<?php echo ($val->gudang_bawen<1) ? 'danger' : 'info'; ?> label-form"><?php echo $val->gudang_bawen ? $val->gudang_bawen : 0; ?></span></td>
                                                    <td class="text-center"><span class="label label-<?php echo ($val->gudang_bandung<1) ? 'danger' : 'info'; ?> label-form"><?php echo $val->gudang_bandung ? $val->gudang_bandung : 0; ?></span></td>
                                                    <td class="text-center"><span class="label label-<?php echo ($val->gudang_cikarang<1) ? 'danger' : 'info'; ?> label-form"><?php echo $val->gudang_cikarang ? $val->gudang_cikarang : 0; ?></span></td>
                                                    <td class="text-center"><span class="label label-<?php echo ($val->gudang_surabaya<1) ? 'danger' : 'info'; ?> label-form"><?php echo $val->gudang_surabaya ? $val->gudang_surabaya : 0; ?></span></td>
                                                    <td class="text-center"><span class="label label-<?php echo ($val->gudang_gianyar<1) ? 'danger' : 'info'; ?> label-form"><?php echo $val->gudang_gianyar ? $val->gudang_gianyar : 0; ?></span></td>
                                                </tr>
                                                <?php $no++; } ?>
                                            </tbody>
                                        </table>
                                        <?php } else { echo 'Maaf, data tidak tersedia :('; } ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="page-content-wrap page-tabs-item" id="stok-belum-kirim">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="panel panel-default">
                                    <div class="panel-body">
                                        <?php if($liststok_belum_kirim) { ?>
                                        <table class="table table-striped table-condensed">
                                            <thead>
                                                <tr>
                                                    <th>No.</th>
                                                    <th>Judul Buku</th>
                                                    <th class="text-center">Kelas</th>
                                                    <th class="text-center">Gudang<br>Medan</th>
                                                    <th class="text-center">Gudang<br>Palmerah</th>
                                                    <th class="text-center">Gudang<br>Bawen</th>
                                                    <th class="text-center">Gudang<br>Bandung</th>
                                                    <th class="text-center">Gudang<br>Cikarang</th>
                                                    <th class="text-center">Gudang<br>Surabaya</th>
                                                    <th class="text-center">Gudang<br>Gianyar</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php $no=1; foreach ($liststok_belum_kirim as $val) { ?>
                                                <tr>
                                                    <td><?php echo $no; ?></td>
                                                    <td><?php echo $val->judul_buku.' [<b>'.$val->kode_buku.'</b>]'; ?></td>
                                                    <td class="text-center"><?php echo $val->kelas; ?></td>
                                                    <td class="text-center"><span class="label label-<?php echo ($val->gudang_medan<1) ? 'success' : 'warning'; ?> label-form"><?php echo $val->gudang_medan; ?></span></td>
                                                    <td class="text-center"><span class="label label-<?php echo ($val->gudang_palmerah<1) ? 'success' : 'warning'; ?> label-form"><?php echo $val->gudang_palmerah; ?></span></td>
                                                    <td class="text-center"><span class="label label-<?php echo ($val->gudang_bawen<1) ? 'success' : 'warning'; ?> label-form"><?php echo $val->gudang_bawen; ?></span></td>
                                                    <td class="text-center"><span class="label label-<?php echo ($val->gudang_bandung<1) ? 'success' : 'warning'; ?> label-form"><?php echo $val->gudang_bandung; ?></span></td>
                                                    <td class="text-center"><span class="label label-<?php echo ($val->gudang_cikarang<1) ? 'success' : 'warning'; ?> label-form"><?php echo $val->gudang_cikarang; ?></span></td>
                                                    <td class="text-center"><span class="label label-<?php echo ($val->gudang_surabaya<1) ? 'success' : 'warning'; ?> label-form"><?php echo $val->gudang_surabaya; ?></span></td>
                                                    <td class="text-center"><span class="label label-<?php echo ($val->gudang_gianyar<1) ? 'success' : 'warning'; ?> label-form"><?php echo $val->gudang_gianyar; ?></span></td>
                                                </tr>
                                                <?php $no++; } ?>
                                            </tbody>
                                        </table>
                                        <?php } else { echo 'Maaf, data tidak tersedia :('; } ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>
</div>
<!-- END PAGE CONTENT WRAPPER -->