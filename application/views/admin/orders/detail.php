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
                <div class="panel-heading"><h4>No. Reference</h4></div>
                <div class="panel-body">
                    <?php
                        if($detil["reference_other"] == null || $detil["reference_other"] == "")
                        {
                            $button_text = "Tambah Reference";
                        } 
                        else
                        {
                            echo "No. Reference : ".$detil["reference_other"];
                            echo "<br>";
                            echo "Reference dari : ".$detil["reference_other_from"];
                            echo "<br><br>";
                            $button_text = "Ubah Reference";
                        }
                    ?>
                    <?php if (in_array($adm_level, $arrAccess) || ($detil['current_state']==1 && $adm_level==4)) { ?>
                        <a href="<?php echo base_url(ADMIN_PATH.'/orders/update_reference/'.$detil['id_order']); ?>" class="btn btn-primary" data-toggle="modal" data-target="#myModal2"><i class="fa fa-plus-square"></i> <?php echo $button_text; ?></a>
                    <?php }  ?>
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
                        <?php if (!in_array($detil['current_state'], [2, 4, 8])) { ?>
                        <div class="form-group">
                            <div class="col-md-12">
                                <label>Ubah Status Pesanan</label>
                                <select class="form-control" name="id_order_state" id="id_order_state">
                                    <?php
                                    if ($order_states) {
                                        if ($adm_level==4) {
                                            foreach ($order_states as $row) {
                                                if ((in_array($row->id_order_state, [1, 3])) && $detil['current_state']==1) {
                                                    echo '<option value="'.$row->id_order_state.'"'.$selected.'>'.$row->name.'</option>';
                                                }
                                            }
                                        } else {
                                            if (in_array($adm_level, $arrAccess)) {
                                                foreach ($order_states as $row) {
                                                    if ($detil['current_state']==1) {
                                                        if (!in_array($row->id_order_state, [1, 3])) {
                                                            continue;
                                                        }
                                                    }
                                                    if ($detil['current_state']==2) {
                                                        if ($row->id_order_state<>2) {
                                                            continue;
                                                        }
                                                    }
                                                    if ($detil['current_state']==3) {
                                                        if (!in_array($row->id_order_state, [3, 5])) {
                                                            continue;
                                                        }
                                                    }
                                                    if (in_array($detil['current_state'], [5, 6, 7, 8])) {
                                                        if ($detil['is_intan']==1) {
                                                            if ($row->id_order_state<>$detil['current_state'] && $row->id_order_state<>($detil['current_state']+1)) {
                                                                continue;
                                                            }
                                                        } else {
                                                            if ($row->id_order_state<>$detil['current_state']) {
                                                                continue;
                                                            }
                                                        }
                                                    }
                                                    if ($detil['current_state']==9) {
                                                        if ($row->id_order_state<>9) {
                                                            continue;
                                                        }
                                                    }
                                                    $selected = ($row->id_order_state==$detil['current_state']) ? ' selected' : '';

                                                    echo '<option value="'.$row->id_order_state.'"'.$selected.'>'.$row->name.'</option>';
                                                }
                                            } elseif ($adm_level==5) {
                                                foreach ($order_states as $row) {
                                                    if ($detil['current_state']==1) {
                                                        if (!in_array($row->id_order_state, [1, 3])) {
                                                            continue;
                                                        }
                                                    }
                                                    if ($detil['current_state']==2) {
                                                        if ($row->id_order_state<>2) {
                                                            continue;
                                                        }
                                                    }
                                                    if ($detil['current_state']==3) {
                                                        if (!in_array($row->id_order_state, [3, 5])) {
                                                            continue;
                                                        }
                                                    }
                                                    if (in_array($detil['current_state'], [5, 6, 7, 8])) {
                                                        if ($row->id_order_state<>$detil['current_state'] && $row->id_order_state<>($detil['current_state']+1)) {
                                                            continue;
                                                        }
                                                    }
                                                    if ($detil['current_state']==9) {
                                                        if ($row->id_order_state<>9) {
                                                            continue;
                                                        }
                                                    }
                                                    $selected = ($row->id_order_state==$detil['current_state']) ? ' selected' : '';
                                                    echo '<option value="'.$row->id_order_state.'"'.$selected.'>'.$row->name.'</option>';
                                                }
                                            } elseif ($adm_level==8) {
                                                foreach ($order_states as $row) {
                                                    if ($detil['current_state']==1) {
                                                        if (!in_array($row->id_order_state, [1, 3])) {
                                                            continue;
                                                        }
                                                    }
                                                    if ($detil['current_state']==2) {
                                                        if ($row->id_order_state<>2) {
                                                            continue;
                                                        }
                                                    }
                                                    if ($detil['current_state']==3) {
                                                        if (!in_array($row->id_order_state, [3, 5])) {
                                                            continue;
                                                        }
                                                    }
                                                    if (in_array($detil['current_state'], [5, 6, 7, 8, 9])) {
                                                        if ($row->id_order_state<>$detil['current_state']) {
                                                            continue;
                                                        }
                                                    }
                                                    $selected = ($row->id_order_state==$detil['current_state']) ? ' selected' : '';
                                                    echo '<option value="'.$row->id_order_state.'"'.$selected.'>'.$row->name.'</option>';
                                                }
                                            } else {
                                                foreach ($order_states as $row) {
                                                    if ($row->id_order_state <> 3) {
                                                        if ($detil['current_state']==1) {
                                                            if (!in_array($row->id_order_state, [1, 3])) {
                                                                continue;
                                                            }
                                                        }
                                                        if ($detil['current_state']==2) {
                                                            if ($row->id_order_state<>2) {
                                                                continue;
                                                            }
                                                        }
                                                        if ($detil['current_state']==3) {
                                                            if (!in_array($row->id_order_state, [3, 5])) {
                                                                continue;
                                                            }
                                                        }
                                                        if (in_array($detil['current_state'], [5, 6, 7, 8])) {
                                                            if ($row->id_order_state<>$detil['current_state'] && $row->id_order_state<>($detil['current_state']+1)) {
                                                                continue;
                                                            }
                                                        }
                                                        if ($detil['current_state']==9) {
                                                            if ($row->id_order_state<>9) {
                                                                continue;
                                                            }
                                                        }
                                                        $selected = ($row->id_order_state==$detil['current_state']) ? ' selected' : '';
                                                        echo '<option value="'.$row->id_order_state.'"'.$selected.'>'.$row->name.'</option>';
                                                    }
                                                }
                                            }
                                        }
                                    }
                                    ?>
                                </select>
                            </div>
                        </div>
                        <div class="form-group" id="div-referer" style="display:none;">
                            <?php if ($detil['current_state']==1) { ?>
                            <div class="col-md-12">
                                <br>
                                <label>Jangka Waktu Pengiriman (Hari)</label>
                                <input type="number" class="form-control" name="jangka_waktu" id="jangka_waktu" min="1">
                                <br>
                                <label>Kesepakatan Buku Sampai di Sekolah (Hari)</label>
                                <input type="number" class="form-control" name="kesepakatan_sampai" id="kesepakatan_sampai" min="1">
                                <br>
                                <label>Sales Representative</label>
                                <input type="text" readonly="readonly" class="form-control" name="sales_referer" id="sales_referer" placeholder="Nama sales atau toko">
                            </div>
                            <?php } ?>
                        </div>

                        <input type="hidden" id="hidden_persetujuan_keterangan" value="<?php echo $detil['persetujuan_keterangan']; ?>">

                        <div class="form-group" id="div-persetujuan" style="display:none;">
                            <?php if (in_array($detil['current_state'], [1, 3, 5]) && $adm_level==8) { ?>
                            <div class="col-md-12">
                                <br>
                                <label>Keterangan Persetujuan</label>
                                <input type="text" class="form-control" name="persetujuan_keterangan" id="persetujuan_keterangan" placeholder="Keterangan Persetujuan" value="<?php echo $detil['persetujuan_keterangan']; ?>">
                            </div>
                            <?php } ?>
                        </div>

                        <?php if (in_array($detil['current_state'], [1, 3, 5]) && ($adm_level==3 || $adm_level==8)) { ?>
                        <div class="form-group" id="div-keterangan" style="display: none;">
                            <div class="col-md-12">
                                Transaksi telah disetujui oleh <?php echo $detil['persetujuan_rsm']; ?> dengan keterangan <?php echo $detil['persetujuan_keterangan']; ?>
                            </div>
                        </div>
                        <?php } ?>

                        <div class="form-group" id="div-logistik" style="display:none;">
                            <?php
                            if ($detil['current_state']==3) {
                                $disabled_logistic = "";
                                if (!$isCoverageArea) {
                                    $disabled_logistic = ' disabled="disabled"';
                                } else {
                                    // if ($customer['jenjang'] == '1-6') {
                                    //     $disabled_logistic = ' disabled="disabled"';
                                    // }
                                }
                            ?>
                            <div class="col-md-12">
                                <br />
                                <label>Pilih Logistik</label><br />
                                <label class="radio-inline">
                                  <input type="radio" id="log_gramedia" name="is_intan" value="2"<?php echo $disabled_logistic; ?>>PT. Gramedia
                                </label>
                                <label class="radio-inline">
                                  <input type="radio" id="log_intan" name="is_intan" value="1">Intan Pariwara
                                </label>
                            </div>
                            <?php } ?>
                        </div>
                        <div class="form-group" id="div-sampai" style="display:none;">
                            <?php if ($detil['current_state']==6) { ?>
                            <div class="col-md-12">
                                <label>Tanggal Sampai</label>
                                <div class="input-group date" id="datetimepicker6">
                                    <input type="text" class="form-control" id="tgl_sampai" name="tgl_sampai" />
                                    <span class="input-group-addon">
                                        <span class="glyphicon glyphicon-calendar"></span>
                                    </span>
                                </div>
                            </div>
                            <div class="col-md-12">
                                <label>Nama Penerima</label>
                                <input type="text" class="form-control" name="nama_penerima" id="nama_penerima" placeholder="Nama penerima barang">
                            </div>
                            <?php } ?>
                        </div>
                        <div id="div-penerima" style="display:none;">
                            <?php if ($detil['current_state']==7) { ?>
                            <div class="form-group">
                                <div class="col-md-12">
                                    <label>Tanggal Terima</label>
                                    <div class="input-group date" id="datetimepicker6">
                                        <input type="text" class="form-control" name="tgl_terima" />
                                        <span class="input-group-addon">
                                            <span class="glyphicon glyphicon-calendar"></span>
                                        </span>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="col-md-12">
                                    <label>Berkas BAST</label>
                                    <input type="file" class="form-control" name="file_bast" id="file_bast">
                                </div>
                            </div>
                            <?php /*
                            <div class="form-group">
                                <div class="col-md-12">
                                    <label>Nomor Surat</label>
                                    <input type="text" class="form-control" name="nomor_surat" id="nomor_surat">
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="col-md-12">
                                    <label>Tanggal Surat</label>
                                    <div class="input-group date" id="datetimepicker7">
                                        <input type="text" class="form-control" name="tanggal_surat" />
                                        <span class="input-group-addon">
                                            <span class="glyphicon glyphicon-calendar"></span>
                                        </span>
                                    </div>
                                </div>
                            </div>
                            */ ?>
                            <?php } ?>
                        </div>
                        <div class="form-group" id="div-bayar" style="display:none;">
                            <?php if ($detil['current_state']==8) { ?>
                            <div class="col-md-12">
                                <label>Tanggal Bayar</label>
                                <div class="input-group date" id="datetimepicker6">
                                    <input type="text" class="form-control" id="tgl_bayar" name="tgl_bayar" />
                                    <span class="input-group-addon">
                                        <span class="glyphicon glyphicon-calendar"></span>
                                    </span>
                                </div>
                            </div>
                            <div class="col-md-12">
                                <label>Nama Bank</label>
                                <input type="text" class="form-control" name="nama_bank" id="nama_bank" placeholder="Ex: BRI, BNI, BPD, dll...">
                            </div>
                            <div class="col-md-12">
                                <label>Nama Pemilik Rekening</label>
                                <input type="text" class="form-control" name="nama_pembayar" id="nama_pembayar" placeholder="Nama rekening">
                            </div>
                            <div class="col-md-12">
                                <label>Jumlah Bayar</label>
                                <input type="text" class="form-control" name="jumlah_bayar" id="jumlah_bayar">
                            </div>
                            <?php } ?>
                        </div>
                        <?php } ?>
                    </div>
                </div>
                <div class="panel panel-default">
                    <div class="panel-body">
                        <div class="col-md-12">
                            <?php
                            if ($adm_level==4) {
                                if ($detil['current_state']==1) {
                            ?>
                                <button type="submit" class="btn btn-success btn-lg pull-left" id="btn_status">Simpan</button>
                            <?php
                                } if (!in_array($detil['current_state'], [2, 4])) {
                            ?>
                                <a href="#" class="btn btn-warning btn-lg pull-left" style="margin-left:8px;" onclick="printPesanan()">Cetak Pesanan</a>
                                <?php
                                    if($detil["reference_other"] != null || $detil["reference_other"] != "")
                                    {
                                        if($detil['reference_other_from'] == 'Siplah.id')
                                        {
                                ?>
                                <a href="#" class="btn btn-warning btn-lg pull-left" style="margin-left:8px;" onclick="printPesananSiplah()">Cetak Pesanan Siplah</a>
                                <?php
                                        }
                                    }
                                ?>
                            <?php
                                }
                            } else {
                            ?>
                                <?php
                                if (!in_array($detil['current_state'], [2, 4, 8, 9])) {
                                    if ($detil['current_state']==7) {
                                        if ($detil['is_intan'] == 1) { ?>
                                        <button type="submit" class="btn btn-success btn-lg pull-left" id="btn_status">Simpan</button>
                                        <?php }
                                    } else { ?>
                                        <button type="submit" class="btn btn-success btn-lg pull-left" id="btn_status">Simpan</button>
                                    <?php }
                                } if (in_array($adm_level, $arrAccess) && !in_array($detil['current_state'], [2, 4, 9]) && !$isInSCMProcess && $detil['sts_bayar']<>2) { ?>
                                &nbsp;&nbsp;<a data-toggle="modal" href="<?php echo base_url(ADMIN_PATH.'/orders/cancel/'.$detil['id_order']); ?>" data-target="#myModal" class="btn btn-lg btn-danger">Batalkan</a>
                                <?php } if (in_array($adm_level, array_merge($this->backoffice_admin_area, [8])) && $detil['current_state']>5 && $detil['sts_bayar']>0) { ?>
                                <a href="#" class="btn btn-warning btn-lg pull-left" style="margin-left:8px;" onclick="printKwintansi()">Cetak Kwintansi</a>
                                <?php } if (!in_array($detil['current_state'], [2, 4])) { ?>
                                <a href="#" class="btn btn-warning btn-lg pull-left" style="margin-left:8px;" onclick="printPesanan()">Cetak Pesanan</a>
                                
                                <?php
                                    if($detil["reference_other"] != null || $detil["reference_other"] != "")
                                    {
                                        if($detil['reference_other_from'] == 'Siplah.id')
                                        {
                                ?>
                                <a href="#" class="btn btn-warning btn-lg pull-left" style="margin-left:8px;" onclick="printPesananSiplah()">Cetak Pesanan Siplah</a>
                                <?php
                                        }
                                    }
                                ?>
                            <?php
                                }
                            }
                            $urlBack = ($detil['is_offline']==1) ? '/orders/offline' : '/orders';
                            ?>
                                <a href="<?php echo base_url().ADMIN_PATH.$urlBack; ?>" class="btn btn-primary btn-lg pull-right">Kembali</a>
                        </div>
                    </div>
                </div>
            <?php echo form_close(); ?>

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
<div class="modal fade" id="myModal2" role="dialog" aria-labelledby="myModalLabel2" data-keyboard="false" data-backdrop="static">
    <div class="modal-dialog modal-lg">
      <div class="modal-content">
      </div>
    </div>
</div>
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
function printPesananSiplah(){
    window.open('<?php echo base_url(ADMIN_PATH."/orders/print_pesanan_siplah/".$detil['reference_other']); ?>','page','toolbar=0,scrollbars=1,location=0,statusbar=0,menubar=0,resizable=0,width=750,height=600,left=50,top=50,titlebar=no');
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
