<!-- START BREADCRUMB -->
<ul class="breadcrumb">
    <li><a href="<?php echo base_url(BACKMIN_PATH); ?>">Dasbor</a></li>
    <li><a href="#">Laporan</a></li>
    <li class="active">Stok Barang</li>
</ul>
<!-- END BREADCRUMB -->
<!-- PAGE TITLE -->
<div class="page-title">                    
    <h2><span class="glyphicon glyphicon-bookmark"></span> Stok Barang</h2>
    <div style="float:right;">
        <?php echo form_open(base_url(BACKMIN_PATH . '/gudanglaporan/indexStok'), 'class="form-inline" role="form" method="POST" id="formSearchItem"'); ?>
            <div class="form-group">
                <input type="text" id="search_input" name="search_input" class="form-control search_input" oninput="submitSearch()" placeholder="Cari buku atau kelas" value="<?php echo $term; ?>"/>
            </div>
            &nbsp;
            <button type="submit" class="btn btn-danger" id="btn_search_item">
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
                    <?php if($list_stok) { ?>
                    <table class="table table-striped table-condensed">
                        <thead>
                            <tr>
                                <th width="5%">No.</th>
                                <th width="55%">Judul Buku</th>
                                <th class="text-center" width="10%">Stok<br>Fisik</th>
                                <th class="text-center" width="10%">Stok<br>Booking</th>
                                <th class="text-center" width="10%">Stok<br>Available</th>
                                <th class="text-center" width="10%">Stok<br>Belum Proses</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($list_stok as $parent_category => $value) { 
                                    foreach ($value as $parent => $data) { ?>
                            <tr>
                                <td colspan="6"><p class="parent-category"><?php echo $parent_category.'&nbsp; - &nbsp;'.$parent ?></p></td>
                            </tr>
                            <?php $no=1; foreach ($data as $val) { ?>
                            <tr>
                                <td class="text-center"><?php echo $no; ?></td>
                                <td><?php echo $val->judul_buku.' [<b>'.$val->kode_buku.'</b>]'; ?></td>
                                <td class="text-center"><span class="label label-<?php echo ($val->stok_fisik<1) ? 'warning' : 'primary'; ?> label-form"><?php echo $val->stok_fisik; ?></span></td>
                                <td class="text-center"><span class="label label-<?php echo ($val->stok_booking<1) ? 'warning' : 'primary'; ?> label-form"><?php echo $val->stok_booking; ?></span></td>
                                <td class="text-center"><span class="label label-<?php echo ($val->stok_available<1) ? 'warning' : 'primary'; ?> label-form"><?php echo $val->stok_available; ?></span></td>
                                <td class="text-center"><span class="label label-<?php echo ($val->stok_belum_kirim<1) ? 'warning' : 'primary'; ?> label-form"><?php echo $val->stok_belum_kirim; ?></span></td>
                            </tr>
                            <?php   $no++; 
                                    }
                                } 
                            } ?>
                        </tbody>
                    </table>
                    <?php } else { echo 'Maaf, data tidak tersedia :('; } ?>
                </div>
            </div>
        </div>
    </div>
</div>
