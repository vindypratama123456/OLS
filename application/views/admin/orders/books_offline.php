<div class="container-fluid">

  <!-- Page Heading -->
  <div class="row">
    <div class="col-lg-12">
      <h1 class="page-header">
        Pesanan (Offline)
      </h1>
      <ol class="breadcrumb">
        <li>
          <a href="<?php echo base_url() . ADMIN_PATH; ?>">Beranda</a>
        </li>
        <li class="active">
          <a href="<?php echo base_url() . ADMIN_PATH; ?>/orders/offline">Pesanan Offline</a>
        </li>
        <li class="active">
          Pilih Buku Dipesan
        </li>
      </ol>
    </div>
  </div>
  <!-- /.row -->

  <div class="row">
      <div class="col-lg-12">

        <?php
if ($this->session->flashdata('msg_success')) {
    echo notif('success', $this->session->flashdata('msg_success'));
}
?>

        <div class="panel panel-default">
          <div class="panel-heading"><h4>Pelanggan</h4></div>
          <div class="panel-body">
            <!-- List group -->
            <ul class="list-group">
                <li class="list-group-item">NPSN: <?php echo $customer['no_npsn']; ?></li>
                <li class="list-group-item">Sekolah: <?php echo $customer['school_name']; ?></li>
                <li class="list-group-item">Zona: <?php echo $customer['zona']; ?></li>
                <li class="list-group-item">Email Sekolah: <?php echo $customer['email']; ?></li>
                <li class="list-group-item">Telpon Sekolah: <?php echo $customer['phone']; ?></li>
                <li class="list-group-item"></li>
                <li class="list-group-item">Nama Kepala Sekolah: <?php echo $customer['name']; ?></li>
                <li class="list-group-item">Email Kepala Sekolah: <?php echo $customer['email_kepsek']; ?></li>
                <li class="list-group-item">Telpon Kepala Sekolah: <?php echo $customer['phone_kepsek']; ?></li>
                <li class="list-group-item"></li>
                <li class="list-group-item">Nama Operator: <?php echo $customer['operator']; ?></li>
                <li class="list-group-item">Email Operator: <?php echo $customer['email_operator']; ?></li>
                <li class="list-group-item">Telpon Operator: <?php echo $customer['hp_operator']; ?></li>
            </ul>
        </div>
        </div>

        <div class="panel panel-default">
          <!-- Default panel contents -->
          <div class="panel-heading"><h4>Pilih Judul Buku</h4></div>
          <div class="panel-body" id="list-products">
            <?php echo form_open('', 'data-action="' . base_url() . ADMIN_PATH . '/orders/offlineBooksPost" id="offline_books_form" autocomplete="off" role="form"'); ?>

              <input type="hidden" name="id_customer" value="<?=$id_customer?>" />
              <input type="hidden" name="jenjang" value="<?=$jenjang?>" />
              <input type="hidden" name="zona" value="<?=$zona?>" />
              <input type="hidden" name="kabupaten" value="<?=$customer['kabupaten']?>" />

              <div class="form-group">
                <div class="col-sm-12">
                  <div class="table-responsive">
                  <?php
                  $row = 0;
                  foreach ($listbooks as $category => $value) {
                    echo "<h3>".$category."</h3>";

                    $rows[$row] = 0;
                    foreach ($value as $classes => $data) {
                        $category_id = $data[0]['category_id'];
                  ?>
                      <div class="panel-group" id="accordion">
                        <div class="panel panel-default">
                          <div class="panel-heading">
                            <h4 class="panel-title">
                              <a data-toggle="collapse" data-parent="#accordion" href="#<?php echo $category_id ?>">
                                <?php echo $classes ?> &nbsp;<i>(Klik kelas atau panah)</i>
                                <span id="accordion_icon_<?php echo $category_id ?>" style="float:right;" class="glyphicon glyphicon-chevron-down"></span>
                              </a>
                            </h4>
                          </div>
                          <div id="<?php echo $category_id ?>" class="panel-collapse collapse">
                            <div class="panel-body">
                              <table class="table" width="100%">
                                <thead>
                                  <td width="80%" colspan="3" style="text-align:right;">Silahkan Masukkan Jumlah Pesanan (Buku Siswa)</td>
                                  <td width="5%"><input size="4" style="text-align:center;" type="text" value="0" id="setAllQty<?php echo $category_id ?>" class="setAllQty"></td>
                                  <td width="15%"><a href="#" style="text-align:center;" onclick="setAllQty(<?php echo $category_id ?>)" class="btn btn-primary">Set Jumlah</a></td>
                                </thead>
                              </table>
                              <table class="table table-bordered">
                                <thead>
                                  <th class="text-center">Kode Buku</th>
                                  <th class="text-center">Judul Buku</th>
                                  <th class="text-center">Kelas</th>
                                  <th class="text-center">Harga Satuan</th>
                                  <th class="text-center">Jumlah</th>
                                </thead>
                                <tbody>
                                  <?php
                                  foreach ($data as $values) {
                                    $judul = $values['judul'];
                                    $class = (substr($judul, 0, 9) == 'Buku Guru') ? '' : ' jumlah_buku' . $category_id;
                                  ?>
                                  <tr>
                                    <td class="text-center"><?php echo $values['kode_buku']; ?></td>
                                    <td><?php echo $values['judul']; ?></td>
                                    <td class="text-center"><?php echo $values['kelas']; ?></td>
                                    <td class="text-right"><?php echo toRupiah($values['harga']); ?></td>
                                    <td class="text-center"><input type="number" class="qty<?=$class?>" min="0" style="text-align:center;width:60px;" name="qty-<?php echo $values['id_product']; ?>"></td>
                                  </tr>
                                  <?php } ?>
                                </tbody>
                              </table>
                            </div>
                          </div>
                          <script type="text/javascript">
                            $('#<?php echo $category_id ?>').on('shown.bs.collapse', function () {
                              $('#accordion_icon_<?php echo $category_id ?>').removeClass("glyphicon-chevron-down");
                              $('#accordion_icon_<?php echo $category_id ?>').addClass("glyphicon-chevron-up");
                            });
                            $('#<?php echo $category_id ?>').on('hidden.bs.collapse', function () {
                              $('#accordion_icon_<?php echo $category_id ?>').removeClass("glyphicon-chevron-up");
                              $('#accordion_icon_<?php echo $category_id ?>').addClass("glyphicon-chevron-down");
                            });
                          </script>
                        </div>
                      </div>
                  <?php
                      $rows[$row]++;
                    }

                    $row++;
                  }
                  ?>
                  </div>
                  <div class="pull-left" style="padding-top: 15px;">
                    <button type="submit" class="btn btn-success">Simpan</button>
                  </div>
                  <div class="pull-right" style="padding-top: 15px;">
                    <a href="#" onclick="setAllNull()" class="btn btn-danger"><i class="fa fa-refresh"></i> &nbsp; Reset Jumlah</a>
                  <div class="pull-left" style="padding-top: 15px;">
                  </div>
                </div>
              </div>
            <?php echo form_close(); ?>
          </div>
        </div>

      </div>
  </div>

</div>