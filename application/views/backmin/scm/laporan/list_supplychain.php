<!-- START BREADCRUMB -->
<ul class="breadcrumb">
    <li><a href="<?php echo base_url(BACKMIN_PATH); ?>">Dasbor</a></li>
    <li><a href="#">Laporan</a></li>
    <li class="active">Supply Chain</li>
</ul>
<!-- END BREADCRUMB -->
<!-- PAGE TITLE -->
<div class="page-title">                    
    <h2><span class="glyphicon glyphicon-bookmark"></span> Supply Chain</h2>
    <div style="float: right; padding-left: 20px;">
        <?php echo form_open(base_url(BACKMIN_PATH.'/scmlaporan/exportExcelSupplyChain'), 'class="form-inline" role="form" method="POST" id="formPrint" target="_blank"'); ?>
            <input type="hidden" name="select_query" value="<?php echo $select_query; ?>">
            <input type="hidden" name="where_query" value="<?php echo $where_query; ?>">
            <input type="submit" name="btn_print" class="btn btn-success" value="Ekspor Excel">
        <?php echo form_close(); ?>
    </div>
    <div style="float:right;">
        <?php echo form_open(base_url(BACKMIN_PATH.'/scmlaporan/indexSupplyChain'), 'class="form-inline" role="form" method="POST" id="formSearchItem"'); ?>
            <div class="form-group">
                <select class="form-control search_gudang" id="search_gudang" name="search_gudang">
                    <option value="" <?php if($term_gudang == ""){ echo "selected"; }?>>-- Semua Gudang --</option>
                    <?php foreach ($listgudang as $row) { ?>
                    <option value="<?php echo $row->id_gudang; ?>" <?php if($term_gudang == $row->id_gudang){ echo "selected"; }?>><?php echo $row->nama_gudang; ?></option>
                    <?php } ?>
                </select>
            </div>
            &nbsp;
            <div class="form-group">
                <input type="text" id="search_input" name="search_input" class="form-control search_input" placeholder="Cari buku atau kelas" value="<?php echo $term; ?>"/>
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
                    <?php if($liststok_supplychain) { ?>
                    <table class="table table-striped table-condensed">
                        <thead>
                            <tr>
                                <th>No.</th>
                                <th>Judul Buku</th>
                                <th class="text-center">Kelas</th>
                                <th class="text-center">Stok</th>
                                <th class="text-center">Diambil IP</th>
                                <th class="text-center">Kirim</th>
                                <th class="text-center">Total<br>Produksi</th>
                                <th class="text-center">Tunggu<br>Konfirmasi SC</th>
                                <th class="text-center">Booking</th>
                                <th class="text-center">Belum<br>Kirim</th>
                                <th class="text-center">Total<br>Pesanan</th>
                                <th class="text-center">Available</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $no=1; foreach ($liststok_supplychain as $val) { ?>
                            <tr>
                                <td><?php echo $no; ?></td>
                                <td><?php echo $val->judul_buku.' [<b>'.$val->kode_buku.'</b>]'; ?></td>
                                <td class="text-center"><?php echo $val->kelas; ?></td>
                                <td class="text-center"><?php echo number_format($val->stok_fisik, '0', ',', '.'); ?></td>
                                <td class="text-center"><?php echo number_format($val->stok_ip, '0', ',', '.'); ?></td>
                                <td class="text-center"><?php echo number_format($val->stok_kirim, '0', ',', '.'); ?></td>
                                <td class="text-center"><?php echo number_format($val->total_produksi, '0', ',', '.'); ?></td>
                                <td class="text-center"><?php echo number_format($val->stok_konfirmasi, '0', ',', '.'); ?></td>
                                <td class="text-center"><?php echo number_format($val->stok_booking, '0', ',', '.'); ?></td>
                                <td class="text-center"><?php echo number_format($val->stok_belum_kirim, '0', ',', '.'); ?></td>
                                <td class="text-center"><?php echo number_format($val->total_pesanan, '0', ',', '.'); ?></td>
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