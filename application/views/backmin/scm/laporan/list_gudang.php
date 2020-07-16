<!-- START BREADCRUMB -->
<ul class="breadcrumb">
    <li><a href="<?php echo base_url(BACKMIN_PATH); ?>">Dasbor</a></li>
    <li><a href="#">Laporan</a></li>
    <li class="active">Gudang</li>
</ul>
<!-- END BREADCRUMB -->
<!-- PAGE TITLE -->
<div class="page-title">                    
    <h2><span class="glyphicon glyphicon-bookmark"></span> Gudang</h2>
    <div style="float:right;">
        <?php echo form_open(base_url(BACKMIN_PATH.'/scmlaporan/indexStokGudang'), 'class="form-inline" role="form" method="POST" id="formSearchItem"'); ?>
            <div class="form-group">
                <input type="text" id="search_input" name="search_input" class="form-control search_input" placeholder="Cari buku atau kelas" value="<?php echo $term; ?>"/>
            </div>
            <button type="submit" class="btn btn-danger" id="btn_search_item" onclick="searchItem()">
                <span class="fa fa-search"></span> Cari
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
                    <?php if($liststok_gudang) { ?>
                    <table class="table table-striped table-condensed">
                        <thead>
                            <tr>
                                <th width="5%">No.</th>
                                <th width="25%">Judul Buku</th>
                                <th class="text-center" width="10%">Kelas</th>
                                <th class="text-center" width="10%">Stok</th>
                                <th class="text-center" width="10%">Diambil IP</th>
                                <th class="text-center" width="10%">Konfirmasi</th>
                                <th class="text-center" width="10%">Kirim</th>
                                <th class="text-center" width="10%">Belum Kirim</th>
                                <th class="text-center" width="10%">Sisa Stok</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $no=1; foreach ($liststok_gudang as $val) { ?>
                            <tr>
                                <td><?php echo $no; ?></td>
                                <td><?php echo $val->judul_buku.' [<b>'.$val->kode_buku.'</b>]'; ?></td>
                                <td class="text-center"><?php echo $val->kelas; ?></td>
                                <td class="text-center"><?php echo number_format($val->stok_fisik, '0', ',', '.'); ?></td>
                                <td class="text-center"><?php echo number_format($val->stok_ip, '0', ',', '.'); ?></td>
                                <td class="text-center"><?php echo number_format($val->stok_konfirmasi, '0', ',', '.'); ?></td>
                                <td class="text-center"><?php echo number_format($val->stok_kirim, '0', ',', '.'); ?></td>
                                <td class="text-center"><?php echo number_format($val->stok_belum_kirim, '0', ',', '.'); ?></td>
                                <td class="text-center"><?php echo number_format($val->stok_available, '0', ',', '.'); ?></td>
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
<!-- END PAGE CONTENT WRAPPER -->