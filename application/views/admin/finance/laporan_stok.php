<style type="text/css">
    div.DTTT { margin-bottom: 0.5em; float: right; }
    div.dataTables_wrapper { clear: both; }
</style>

<div class="container-fluid">

    <!-- Page Heading -->
    <div class="row">
        <div class="col-lg-12">
            <h1 class="page-header">
                Laporan Stok
            </h1>
            <ol class="breadcrumb">
                <li>
                    <a href="<?php echo base_url() . ADMIN_PATH; ?>">Beranda</a>
                </li>
                <li class="active">
                    Laporan Stok
                </li>
            </ol>
        </div>
    </div>
    <!-- /.row -->

    <div style="float:right;">
        <?php echo form_open(base_url(ADMIN_PATH . '/finance/reportStock'), 'class="form-inline" role="form" method="POST" id="formSearchItem"'); ?>
            <div class="form-group">
                <input type="text" id="search_input" name="search_input" class="form-control input-sm search_input" placeholder="Cari buku atau kelas" value="<?php echo $term; ?>"/>
            </div>
            <button type="submit" class="btn btn-danger btn-sm" id="btn_search_item" onclick="searchItem()">
                <span class="fa fa-search"></span> Cari
            </button>
        <?php echo form_close(); ?>
    </div>

    <div class="row">
        <div class="col-lg-12">
            <div class="table-responsive">
                <?php if($liststok_finance) { ?>
                <table class="table table-striped dt-responsive wrap" id="datatable-all-input">
                    <thead>
                        <tr>
                            <th>No.</th>
                            <th width="30%">Judul Buku</th>
                            <th class="text-center">Kelas</th>
                            <th class="text-center">Total<br>Produksi</th>
                            <th class="text-center">Stok</th>
                            <th class="text-center">Diambil IP</th>
                            <th class="text-center">Total<br>Pesanan</th>
                            <th class="text-center">Sudah<br>Kirim</th>
                            <th class="text-center">Belum<br>Kirim</th>
                            <th class="text-center">Diterima<br>Sekolah</th>
                            <th class="text-center">Sisa Stok</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $no=1; foreach ($liststok_finance as $val) { ?>
                        <tr>
                            <td><?php echo $no; ?></td>
                            <td><?php echo $val->judul_buku.' [<b>'.$val->kode_buku.'</b>]'; ?></td>
                            <td class="text-center"><?php echo $val->kelas; ?></td>
                            <td class="text-center"><?php echo number_format($val->total_produksi, '0', ',', '.'); ?></td>
                            <td class="text-center"><?php echo number_format($val->stok_fisik, '0', ',', '.'); ?></td>
                            <td class="text-center"><?php echo number_format($val->stok_ip, '0', ',', '.'); ?></td>
                            <td class="text-center"><?php echo number_format($val->total_pesanan, '0', ',', '.'); ?></td>
                            <td class="text-center"><?php echo number_format($val->stok_kirim, '0', ',', '.'); ?></td>
                            <td class="text-center"><?php echo number_format($val->stok_belum_kirim, '0', ',', '.'); ?></td>
                            <td class="text-center"><?php echo number_format($val->stok_diterima_sekolah, '0', ',', '.'); ?></td>
                            <td class="text-center"><?php echo number_format($val->stok_available, '0', ',', '.'); ?></td>
                        </tr>
                        <?php $no++; } ?>
                    </tbody>
                </table>
                <?php } else { echo 'Maaf, data tidak tersedia :('; } ?>
            </div>
        </div>
    </div>
    <!-- /.row -->

</div>
<!-- /.container-fluid -->