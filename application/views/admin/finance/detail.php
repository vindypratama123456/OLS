<div class="container-fluid">

    <!-- Page Heading -->
    <div class="row">
        <div class="col-lg-12">
            <h1 class="page-header">
                Pesanan #<?php echo $detil['reference']; ?>
            </h1>
            <ol class="breadcrumb">
                <li>
                    <a href="<?php echo base_url() . ADMIN_PATH; ?>">Beranda</a>
                </li>
                <?php if($adm_level==3 || $adm_level==4 || $adm_level==8) { ?>
                <li class="active">
                    <a href="<?php echo base_url() . ADMIN_PATH . '/finance'; ?>">Pesanan</a>
                </li>
                <?php } else { ?>
                <li class="active">
                    <?php $urlBack = ($detil['sts_bayar']==2) ? '/finance/complete' : '/finance'; ?>
                    <a href="<?php echo base_url() . ADMIN_PATH.$urlBack; ?>">Pesanan</a>
                </li>
                <?php } ?>
                <li class="active">
                    Detil
                </li>
            </ol>
        </div>
    </div>
    <!-- /.row -->

    <div class="row">
        <div class="col-lg-12">

            <?php 
            if($this->session->flashdata('msg_success')) {
                echo notif('success',$this->session->flashdata('msg_success'));
            }
            ?>

            <div class="panel panel-default">
                <!-- Default panel contents -->
                <?php if ($detil['sales_referer']) { ?>
                <div class="panel-heading">
                    <div class="row">
                        <div class="col-lg-12">
                            Sales Referer: <span id="sales_ref"><?php echo $detil['sales_referer']; ?></span>
                        </div>
                    </div>
                </div>
                <?php } ?>
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
                        <?php if($detil['current_state']>=5) { ?>
                        <li class="list-group-item">
                            Logistik: <b><?php echo (1==$detil['is_intan']) ? "Intan Pariwara" : "Gramedia"; ?></b>
                        </li>
                        <?php } ?>
                    </ul>
                </div>
            </div>

            <div class="panel panel-default">
                <!-- Default panel contents -->
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
                <!-- Default panel contents -->
                <div class="panel-heading"><h4>Produk</h4></div>
                <div class="panel-body" id="list-products">
                    <div class="table-responsive">
                        <p>Kelas : <?php echo $detil['category']?></p>
                        <p>Kategori : <?php echo $detil['type']?></p>
                        <?php if($listproducts) { ?>
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th class="text-center">No</th>
                                    <th class="text-center">Produk</th>
                                    <th class="text-center">Jumlah</th>
                                    <th class="text-center">Harga Satuan</th>
                                    <th class="text-center">Harga Total</th>
                                </tr>
                            </thead>
                            <tbody>
                            <?php 
                                $i=1; 
                                $tot_item = 0;
                                $tot_price = 0;
                                foreach($listproducts as $row) { 
                            ?>
                                <tr>
                                    <td class="text-center"><?php echo $i; ?></td>
                                    <td>
                                        <?php
                                            $params = array(
                                                'field' => 'reference',
                                                'table' => 'product',
                                                'key' => 'id_product',
                                                'data' => $row->product_id,
                                            );
                                            $isbn = get_data($params);
                                            echo $row->product_name;
                                            echo '<br />(ISBN: '.$isbn.')';
                                        ?>
                                    </td>
                                    <td class="text-center"><?php echo $row->product_quantity; ?></td>
                                    <td class="text-right"><?php echo toRupiah($row->unit_price); ?></td>
                                    <td class="text-right"><?php echo toRupiah($row->total_price); ?></td>
                                </tr>
                            <?php 
                                $i++;
                                $tot_item += $row->product_quantity;
                                $tot_price += $row->total_price;
                                } 
                            ?>
                                <tr><td colspan="5"></td></tr>
                                <tr>
                                    <td colspan="4" class="text-right">Total Jumlah</td>
                                    <td class="text-right"><?php echo $tot_item; ?></td>
                                </tr>
                                <tr>
                                    <td colspan="4" class="text-right"><strong>Total Harga</strong></td>
                                    <td class="text-right">
                                        <strong><?php echo toRupiah($tot_price); ?></strong>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="text-right" colspan="5">
                                        <i>Terbilang: <b><?php echo terbilang($tot_price); ?></b></i>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                        <?php } ?>
                    </div>

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
                        </div>
                    </div>
                    
                    <?php if($detil['sts_bayar']<>2 && (in_array($adm_level, $this->backoffice_superadmin_area) || $adm_level==6)) { ?>
                    <a href="<?php echo base_url(ADMIN_PATH.'/finance/addLog/'.$detil['id_order']); ?>" class="btn btn-success" data-toggle="modal" data-target="#myModal2"><i class="fa fa-plus-square"></i> Input Log Book</a>
                    <?php }  ?>

                    <h3>Log Book Penagihan</h3>
                    <div class="table-responsive">
                        <table class="table table-bordered">
                            <thead>
                                <th class="text-center" width="25%">Tanggal/Waktu</th>
                                <th class="text-center" width="75%">Catatan Log</th>
                            </thead>
                            <tbody>
                            <?php if($listlog) { foreach ($listlog as $log) { ?>
                                <tr>
                                    <td class="text-center"><?php echo $log->created_at; ?></td>
                                    <td><?php echo $log->notes; ?></td>
                                </tr>
                            <?php } } ?>
                            </tbody>
                        </table>
                    </div>

                    <?php if($detil['sts_bayar'] <> 2 && in_array($adm_level, array_merge($this->backoffice_superadmin_area, [6, 14]))) { ?>
                    <a href="<?php echo base_url(ADMIN_PATH.'/finance/addAmount/'.$detil['id_order']); ?>" class="btn btn-success" data-toggle="modal" data-target="#myModal"><i class="fa fa-plus-square"></i> Input Pembayaran</a>
                    <?php }  ?>

                    <h3>Riwayat Pembayaran</h3>
                    <div class="table-responsive">
                        <table class="table table-bordered">
                            <thead>
                                <th class="text-center" width="20%">Waktu Sistem</th>
                                <th class="text-center" width="25%">Jumlah Pembayaran</th>
                                <th class="text-center" width="15%">Tanggal</th>
                                <th class="text-center" width="35%">Catatan</th>
                                <th class="text-center" width="5%">Aksi</th>
                            </thead>
                            <tbody>
                            <?php 
                                $countBayar =  count($listpay);
                                $countData = 1; 
                            ?>
                            <?php if($listpay) { foreach ($listpay as $pay) { ?>
                                <tr>
                                    <td class="text-center"><?php echo $pay->created_at; ?></td>
                                    <td class="text-right"><?php echo toRupiah($pay->amount); ?></td>
                                    <td class="text-center"><?php echo $pay->pay_date; ?></td>
                                    <td><?php echo $pay->notes; ?></td>
                                    <?php if ($countBayar == $countData){ ?>
                                        <?php if(in_array($adm_level, array_merge($this->backoffice_superadmin_area, [6, 14]))) { ?>
                                        <td><button type="button" value="<?php echo $pay->id; ?>" class="btnDelete glyphicon glyphicon-trash btn-alert">Delete</button></td>
                                        <?php }  ?>

                                    <?php } ?>
                                    <!-- <td><button type="button" OnClick="alert(<?php echo $pay->id; ?>);" class="glyphicon glyphicon-trash btn-alert">Delete</button></td> -->
                                </tr>
                            <?php $countData++; } } ?>
                            </tbody>
                        </table>
                    </div>


                </div>
            </div>
            <?php if($adm_level==3 || $adm_level==4 || $adm_level==8) { ?>
            <a href="<?php echo base_url() . ADMIN_PATH . '/finance'; ?>" class="btn btn-primary btn-lg pull-left">Kembali</a>
            <?php } else { ?>
            <a href="<?php echo base_url() . ADMIN_PATH . $urlBack; ?>" class="btn btn-primary btn-lg pull-left">Kembali</a>
            <?php } ?>
        </div>
    </div>
    <!-- /.row -->

</div>
<!-- /.container-fluid -->
<div class="modal fade" id="myModal" role="dialog" aria-labelledby="myModalLabel" data-keyboard="false" data-backdrop="static">
    <div class="modal-dialog">
      <div class="modal-content">
      </div>
    </div>
</div>
<div class="modal fade" id="myModal2" role="dialog" aria-labelledby="myModalLabel" data-keyboard="false" data-backdrop="static">
    <div class="modal-dialog">
      <div class="modal-content">
      </div>
    </div>
</div>