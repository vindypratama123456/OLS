<div class="container-fluid">
    <div class="row">
        <div class="col-lg-12">
            <h1 class="page-header">
                Pesanan #<?php echo $detil['reference']; ?>
            </h1>
            <ol class="breadcrumb">
                <li>
                    <a href="<?php echo base_url() . ADMIN_PATH; ?>">Beranda</a>
                </li>
                <li class="active">
                    <a href="<?php echo base_url() . ADMIN_PATH; ?>/orders">Pesanan</a>
                </li>
                <li class="active">
                    Detil
                </li>
            </ol>
        </div>
    </div>
    <div class="row">
        <div class="col-lg-12">
            <?php
            if ($this->session->flashdata('msg_success')) {
                echo notif('success', $this->session->flashdata('msg_success'));
            }
            if (!$isCoverageArea) {
            ?>
            <div class="alert alert-danger alert-dismissable">
                <b>MOHON DIPERHATIKAN !!!</b><br />Sekolah tidak masuk dalam cakupan area pengiriman PT. Gramedia.
            </div>
            <?php } 
            
            $arrAccess = array_merge($this->backoffice_superadmin_area, [3, 8]);
            ?>
            <div class="panel panel-default">
                <div class="panel-heading">
                    <div class="row">
                        <?php if (empty($detil['sales_referer']) && $detil['current_state']==1 && in_array($adm_level, $arrAccess)) { ?>
                        <?php echo form_open('', 'data-action="' . base_url(ADMIN_PATH . '/orders/tugaskansales') . '" id="formtugaskansales" autocomplete="off"'); ?>
                            <?php
                            if (isset($detil['recommended_sales']) && $detil['recommended_sales']) {
                                $params = [
                                    'field' => 'name',
                                    'table' => 'employee',
                                    'key' => 'email',
                                    'data' => $detil['recommended_sales']
                                ];
                                $salesName = get_data($params);
                            ?>
                            <div class="col-lg-2" style="margin-top:7px;">Rekomendasi sales: </div>
                            <div class="col-lg-10" style="margin-top:7px;"><b><?php echo $salesName . ' (' . $detil['recommended_sales'] . ')'; ?></b></div><br><br>
                            <?php } ?>
                            <div class="col-lg-2" style="margin-top:7px;">Pilih sales: </div>
                            <div class="col-lg-6">
                                <input type="hidden" name="id_order" value="<?php echo $detil['id_order']; ?>">
                                <input type="hidden" name="reference" value="<?php echo $detil['reference']; ?>">
                                <input type="hidden" name="sekolah_nama" value="<?php echo $customer['school_name']; ?>">
                                <input type="hidden" name="sekolah_propinsi" value="<?php echo $customer['provinsi']; ?>">
                                <input type="hidden" name="sekolah_kabkota" value="<?php echo $customer['kabupaten']; ?>">
                                <select id="emailsales" name="emailsales" class="form-control">
                                    <option value="">- Silahkan pilih satu -</option>
                                    <?php
                                    foreach ($listsales as $itemsales) {
                                        echo '<option value="'.$itemsales->email.'">'.$itemsales->code.' - '.$itemsales->name.' ('.$itemsales->email.')</option>';
                                    } ?>
                                </select>
                            </div>
                            <div class="col-lg-2"><input type="submit" value="Tugaskan" class="btn btn-primary"></div>
                            <div class="col-lg-4"></div>
                        <?php echo form_close(); ?>
                        <?php } else { ?>
                        <div class="col-lg-12">
                            Sales Referer: <span id="sales_ref"><?php echo $detil['sales_referer']; ?></span>
                            <?php if (in_array($adm_level, $arrAccess) && !in_array($detil['current_state'], [2,4]) && $isInComission==false) { ?>
                            <br /><a data-toggle="modal" href="<?php echo base_url(ADMIN_PATH.'/orders/changeSales/'.$detil['id_order']); ?>" data-target="#modalLarge" class="btn btn-primary">Ganti Sales</a>
                            <?php } ?>
                        </div>
                        <?php } ?>
                    </div>
                </div>
                <div class="panel-heading"><h4>Pelanggan</h4></div>
                <div class="panel-body">
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
                        <li class="list-group-item"></li>
                        <li class="list-group-item">
                            Jenis Pesanan: <b><?php echo ($detil['is_offline']==1) ? 'Offline' : 'Online'; ?></b>
                        </li>
                        <li class="list-group-item"></li>
                        <li class="list-group-item">
                            Status Pesanan:
                            <?php
                                $status = array(
                                    'field' => 'name',
                                    'table' => 'order_state',
                                    'key' => 'id_order_state',
                                    'data' => $detil['current_state']
                                );
                                $label = array(
                                    'field' => 'label',
                                    'table' => 'order_state',
                                    'key' => 'id_order_state',
                                    'data' => $detil['current_state']
                                );
                                echo '<span class="label '.get_data($label).'">'.get_data($status).'</span>';
                            ?>
                        </li>
                        <?php if ($detil['current_state']>=5) { ?>
                        <li class="list-group-item">
                            Logistik: <b><?php echo (1==$detil['is_intan']) ? "Intan Pariwara" : "Gramedia"; ?></b>
                        </li>
                        <?php } ?>
                    </ul>
                    <?php if (in_array($adm_level, $this->backoffice_admin_area)) { ?>
                    <a href="<?php echo base_url(ADMIN_PATH.'/customer/editPopup/'.$detil['id_customer']); ?>" class="btn btn-warning" data-toggle="modal" data-target="#modalLarge">Ubah Data Sekolah</a>
                    <?php } ?>
                </div>
            </div>
            <div class="panel panel-default">
                <div class="panel-heading"><h4>Alamat</h4></div>
                <div class="panel-body">
                    <?php
                        echo $customer['alamat'].'<br />';
                        echo $customer['desa'].', ';
                        echo $customer['kecamatan'].', ';
                        echo $customer['kabupaten'].', ';
                        echo $customer['provinsi'].' - ';
                        echo $customer['kodepos'].'<br />';
                        echo 'Telpon: '.$customer['phone'];
                    ?>
                </div>
            </div>
            <div class="panel panel-default">
                <div class="panel-heading"><h4>Buku Pesanan</h4></div>
                <div class="panel-body" id="list-products">
                    <div class="table-responsive">
                        <h4>Kategori: <?php echo $detil['category'] . ' (' . $detil['type'] . ')'; ?></h4>
                        <?php if ($listproducts) { ?>
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th class="text-center">No</th>
                                    <th class="text-center">Judul Buku</th>
                                    <th class="text-center">Jumlah</th>
                                    <th class="text-center">Harga Satuan</th>
                                    <th class="text-center">Harga Total</th>
                                    <?php if ((in_array($detil['current_state'], [1, 3, 5, 6]) && !$isInSCMProcess && $detil['sts_bayar']<>2 && in_array($adm_level, $arrAccess)) || ($detil['current_state']==1 && $adm_level==4)) { ?>
                                    <th class="text-center">Opsi</th>
                                    <?php } ?>
                                </tr>
                            </thead>
                            <tbody>
                            <?php
                                $i=1;
                                $tot_item = 0;
                                $tot_price = 0;
                            foreach ($listproducts as $row) {
                            ?>
                            <tr>
                            <td class="text-center"><?php echo $i; ?></td>
                            <td><?php echo $row->product_name.' [<b>'.$row->pkode_buku.'</b>]<br />(ISBN: '.$row->isbn.')'; ?></td>
                            <td class="text-center"><?php echo $row->product_quantity; ?></td>
                            <td class="text-right"><?php echo toRupiah($row->unit_price); ?></td>
                            <td class="text-right"><?php echo toRupiah($row->total_price); ?></td>
                            <?php if ((in_array($detil['current_state'], [1, 3, 5, 6]) && !$isInSCMProcess && $detil['sts_bayar']<>2 && in_array($adm_level, $arrAccess)) || ($detil['current_state']==1 && $adm_level==4)) { ?>
                                    <th class="text-center">
                                        <a data-toggle="modal" href="<?php echo base_url(ADMIN_PATH.'/orders/edit/'.$row->id_order_detail.'/'.$row->product_quantity); ?>" data-target="#myModal">Ubah</a>
                                    </th>
                            <?php } ?>
                            </tr>
                            <?php
                            $i++;
                            $tot_item += $row->product_quantity;
                            $tot_price += $row->total_price;
                            }
                            ?>
                                <tr><td colspan="<?php echo ((in_array($detil['current_state'], [1, 3, 5, 6]) && !$isInSCMProcess && $detil['sts_bayar']<>2 && in_array($adm_level, $arrAccess)) || ($detil['current_state']==1 && $adm_level==4)) ? '6' : '5'; ?>"></td></tr>
                                <tr>
                                    <td colspan="4" class="text-right">Total Jumlah</td>
                                    <td class="text-right"<?php echo ((in_array($detil['current_state'], [1, 3, 5, 6]) && !$isInSCMProcess && $detil['sts_bayar']<>2 && in_array($adm_level, $arrAccess)) || ($detil['current_state']==1 && $adm_level==4)) ? ' colspan="2"' : ''; ?>><?php echo $tot_item; ?></td>
                                </tr>
                                <tr>
                                    <td colspan="4" class="text-right"><strong>Total Harga</strong></td>
                                    <td class="text-right"<?php echo ((in_array($detil['current_state'], [1, 3, 5, 6]) && !$isInSCMProcess && $detil['sts_bayar']<>2 && in_array($adm_level, $arrAccess)) || ($detil['current_state']==1 && $adm_level==4)) ? ' colspan="2"' : ''; ?>>
                                        <strong><?php echo toRupiah($tot_price); ?></strong>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="text-right" colspan="<?php echo ((in_array($detil['current_state'], [1, 3, 5, 6]) && !$isInSCMProcess && $detil['sts_bayar']<>2 && in_array($adm_level, $arrAccess)) || ($detil['current_state']==1 && $adm_level==4)) ? '6' : '5'; ?>">
                                        <i>Terbilang: <b><?php echo terbilang($tot_price); ?></b></i>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                        <?php if ((in_array($detil['current_state'], [1, 3, 5, 6]) && !$isInSCMProcess && $detil['sts_bayar']<>2 && in_array($adm_level, $arrAccess)) || ($detil['current_state']==1 && $adm_level==4)) { ?>
                        <a href="<?php echo base_url(ADMIN_PATH.'/orders/listBooks/'.$detil['id_order'].'/'.$customer['jenjang'].'/'.$customer['zona'].'/'.$category_books.'/'.$class_books); ?>" class="btn btn-primary" data-toggle="modal" data-target="#myModal"><i class="fa fa-plus-square"></i> Tambah Buku</a>
                        <?php } } ?>
                    </div>
                    <?php if ($listhistory) { ?>
                    <p>Riwayat Perubahan Pesanan</p>
                    <div class="table-responsive">
                        <table class="table table-bordered">
                            <thead>
                                <th class="text-center">Tanggal/Waktu</th>
                                <th class="text-center">Nama Produk</th>
                                <th class="text-center">Jumlah Sebelum</th>
                                <th class="text-center">Jumlah Sesudah</th>
                                <th class="text-center">Petugas</th>
                            </thead>
                            <tbody>
                            <?php foreach ($listhistory as $history) { ?>
                                <tr>
                                    <td class="text-center"><?php echo $history->tanggal; ?></td>
                                    <td><?php echo $history->produk; ?></td>
                                    <td class="text-center"><?php echo $history->sebelum; ?></td>
                                    <td class="text-center"><?php echo $history->setelah; ?></td>
                                    <td class="text-center"><?php echo $history->admin; ?></td>
                                </tr>
                            <?php } ?>
                            </tbody>
                        </table>
                    </div>
                    <?php } ?>
                </div>
            </div>
            <?php echo form_open('', 'data-action="' . base_url(ADMIN_PATH . '/orders/editPost') . '" id="orders_form" autocomplete="off" enctype="multipart/form-data"'); ?>
                <div class="panel panel-default">
                    <div class="panel-heading"><h4>Status</h4></div>
                    <div class="panel-body">
                        <div class="table-responsive">
                            <table class="table table-bordered">
                                <tr>
                                    <th class="text-center">Status</th>
                                    <th class="text-center">Pengguna</th>
                                    <th class="text-center">Tanggal</th>
                                    <th class="text-center">Waktu Sistem</th>
                                    <th class="text-center">Keterangan</th>
                                </tr>
                                <tr>
                                    <td>Dibuat</td>
                                    <td><?php echo $customer['school_name']; ?></td>
                                    <td></td>
                                    <td class="text-center"><?php echo $detil['date_add']; ?></td>
                                    <td></td>
                                </tr>
                                <?php if ($detil['current_state']==2 && count($liststatus)<1) { ?>
                                <tr>
                                    <td>Dibatalkan</td>
                                    <td><?php echo $customer['school_name']; ?></td>
                                    <td></td>
                                    <td class="text-center"><?php echo $detil['date_upd']; ?></td>
                                    <td><?php echo $detil['alasan_batal']; ?></td>
                                </tr>
                                <?php
                                }
                                if ($liststatus) {
                                    foreach ($liststatus as $row) { ?>
                                    <?php if ($row->id_state==2) { ?>
                                    <tr>
                                        <td><?php echo $row->order_state; ?></td>
                                        <td><?php echo $row->employee; ?></td>
                                        <td></td>
                                        <td class="text-center"><?php echo $row->tanggal; ?></td>
                                        <td><?php echo $detil['alasan_batal']; ?></td>
                                    </tr>
                                    <?php } if ($row->id_state==3) { ?>
                                    <tr>
                                        <td><?php echo $row->order_state; ?></td>
                                        <td><?php echo $row->employee; ?></td>
                                        <td></td>
                                        <td class="text-center"><?php echo $row->tanggal; ?></td>
                                        <td>
                                        <?php if ($detil['jangka_waktu']) {
                                            echo 'Jangka Waktu Pengiriman: '.$detil['jangka_waktu'].' hari';
                                        } if ($detil['kesepakatan_sampai']) {
                                            echo ', Kesepakatan Sampai di Sekolah: '.$detil['kesepakatan_sampai'].' hari';
                                        } ?>
                                        </td>
                                    </tr>
                                    <?php } if ($row->id_state==5) { ?>
                                    <tr>
                                        <td><?php echo $row->order_state; ?></td>
                                        <td><?php echo $row->employee; ?></td>
                                        <td></td>
                                        <td class="text-center"><?php echo $row->tanggal; ?></td>
                                        <td>Logistik: <?php echo (1==$detil['is_intan']) ? 'Intan Pariwara' : 'Gramedia'; ?></td>
                                    </tr>
                                    <?php } if ($row->id_state==6) { ?>
                                    <tr>
                                        <td><?php echo $row->order_state; ?></td>
                                        <td><?php echo $row->employee; ?></td>
                                        <td class="text-center"><?php echo substr($detil['tgl_kirim'], 0, 10); ?></td>
                                        <td class="text-center"><?php echo $row->tanggal; ?></td>
                                        <td></td>
                                    </tr>
                                    <?php } if ($row->id_state==7) { ?>
                                    <tr>
                                        <td><?php echo $row->order_state; ?></td>
                                        <td><?php echo $row->employee; ?></td>
                                        <td class="text-center"><?php echo substr($detil['tgl_sampai'], 0, 10); ?></td>
                                        <td class="text-center"><?php echo $row->tanggal; ?></td>
                                        <td><?php echo $detil['nama_penerima']; ?></td>
                                    </tr>
                                    <?php } if ($row->id_state==8) { ?>
                                    <tr>
                                        <td><?php echo $row->order_state; ?></td>
                                        <td><?php echo $row->employee; ?></td>
                                        <td class="text-center"><?php echo substr($detil['tgl_terima'], 0, 10); ?></td>
                                        <td class="text-center"><?php echo $row->tanggal; ?></td>
                                        <td><?php echo '<a href="'. base_url() .'uploads/bast/' . $detil['file_bast'] . '" target="_blank">' . $detil['nomor_surat'] . '</a> :: ' . $detil['tanggal_surat']; ?></td>
                                    </tr>
                                    <?php } if ($row->id_state==9) { ?>
                                    <tr>
                                        <td><?php echo $row->order_state; ?></td>
                                        <td><?php echo $row->employee; ?></td>
                                        <td class="text-center"><?php echo substr($detil['tgl_bayar'], 0, 10); ?></td>
                                        <td class="text-center"><?php echo $row->tanggal; ?></td>
                                        <td><?php echo toRupiah($detil['jumlah_bayar']); ?></td>
                                    </tr>
                                    <?php
                                        }
                                    }
                                }
                                ?>
                            </table>
                        </div>
                        <input type="hidden" name="id_order" value="<?=$detil['id_order']?>" />
                        <input type="hidden" name="id_customer" value="<?=$detil['id_customer']?>" id="id_customer"/>
                        <input type="hidden" name="customer_kabkota" value="<?php echo $customer['kabupaten']; ?>">
                        <input type="hidden" name="reference" value="<?=$detil['reference']?>" />
                        <input type="hidden" name="current_state" value="<?=$detil['current_state']?>" />
                        <?php if ($detil['current_state']==6) { ?>
                        <input type="hidden" name="tgl_kirim" value="<?=$detil['tgl_kirim']?>" />
                        <?php } ?>
                        <input type="hidden" name="email_sekolah" value="<?php echo $customer['email']; ?>" />
                        <input type="hidden" name="email_kepsek" value="<?php echo $customer['email_kepsek']; ?>" />
                        <input type="hidden" name="email_operator" value="<?php echo $customer['email_operator']; ?>" />
                    </div>
                </div>

            <?php echo form_close(); ?>
                <div class="panel panel-default">
                    <div class="panel-body">
                        <div class="col-md-12">
                            <?php
                                if($detil['kirim_parsial_accept_by_id'] == null || $detil['kirim_parsial_accept_by_id'] == "")
                                {
                            ?>
                            <?php echo form_open('', 'id="frmKonfirmasiPengirimanParsial" class="form-horizontal" data-uri_konfirmasi="' . '/pengiriman_parsial/accept_parsial" data-uri_tolak="' . '/pengiriman_parsial/denied_parsial" role="form" autocomplete="off"'); ?>
                            <input type="hidden" name="id_order" value="<?php echo $detil['id_order'];?>">
                            <button type="submit" class="btn btn-success btn-lg pull-left" id="btn_konfirmasi">KONFIRMASI</button>
                            <button type="submit" style="margin-left:0.5em;" class="btn btn-warning btn-lg pull-left" id="btn_tolak">TOLAK</button>
                            <?php
                                echo form_close();
                            ?>
                            <?php
                                }
                            $urlBack = ($detil['is_offline']==1) ? '/pengiriman_parsial/offline' : '/pengiriman_parsial';
                            ?>
                                <a href="<?php echo base_url().ADMIN_PATH.$urlBack; ?>" class="btn btn-primary btn-lg pull-right">Kembali</a>
                        </div>
                    </div>
                </div>

        </div>
    </div>
</div>
<?php if (in_array($detil['current_state'], [1, 3, 5, 6])) { ?>
<div class="modal fade" id="myModal" role="dialog" aria-labelledby="myModalLabel" data-keyboard="false" data-backdrop="static">
    <div class="modal-dialog modal-lg">
      <div class="modal-content">
      </div>
    </div>
</div>
<?php } ?>
<div class="modal fade" id="modalLarge" role="dialog" aria-labelledby="modalLargeLabel" data-keyboard="false" data-backdrop="static">
    <div class="modal-dialog" style="width:50%;">
      <div class="modal-content">
      </div>
    </div>
</div>
<script type="text/javascript">
var sales = $("#sales_ref").text();
var sess_name = "<?php echo $this->session->userdata('adm_uname'); ?>";
var ss = sales ? sales : sess_name;
$("#id_order_state").change(function() {
    $('#sales_referer').val(ss);
});
function printPesanan(){
    window.open('<?php echo base_url(ADMIN_PATH."/orders/cetakPesanan/".$detil['reference']); ?>','page','toolbar=0,scrollbars=1,location=0,statusbar=0,menubar=0,resizable=0,width=750,height=600,left=50,top=50,titlebar=no');
}
function printBAST(){
    window.open('<?php echo base_url(ADMIN_PATH."/orders/cetakBAST/".$detil['id_order']); ?>','page','toolbar=0,scrollbars=1,location=0,statusbar=0,menubar=0,resizable=0,width=750,height=600,left=50,top=50,titlebar=no');
}
function printKwintansi(){
    window.open('<?php echo base_url(ADMIN_PATH."/orders/cetakKwintansi/".$detil['id_order']); ?>','page','toolbar=0,scrollbars=1,location=0,statusbar=0,menubar=0,resizable=0,width=750,height=600,left=50,top=50,titlebar=no');
}
function printFaktur(){
    window.open('<?php echo base_url(ADMIN_PATH."/orders/cetakFaktur/".$detil['id_order']); ?>','page','toolbar=0,scrollbars=1,location=0,statusbar=0,menubar=0,resizable=0,width=750,height=600,left=50,top=50,titlebar=no');
}
$(function () {
    $('#datetimepicker6, #datetimepicker7').datetimepicker({
        format: 'YYYY-MM-DD',
        maxDate : 'now'
    });
});
</script>
