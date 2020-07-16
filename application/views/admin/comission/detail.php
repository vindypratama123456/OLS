<div class="container-fluid">
    <div class="row">
        <div class="col-lg-12">
            <h1 class="page-header">
                Pesanan: <a href="<?php echo base_url() . ADMIN_PATH . '/orders/detail/' . $order['id_order']; ?>" target="_blank">#<?php echo $order['reference']; ?></a>
            </h1>
            <ol class="breadcrumb">
                <li>
                    <a href="<?php echo base_url() . ADMIN_PATH; ?>">Beranda</a>
                </li>
                <li>
                    <a href="<?php echo $url_back; ?>">Komisi</a>
                </li>
                <li class="active">
                    Detil
                </li>
            </ol>
        </div>
    </div>
    <div class="row">
        <div class="col-lg-12">
            <div class="panel panel-default">
                <div class="panel-heading"><h4>Pelanggan</h4></div>
                <div class="panel-body">
                    <ul class="list-group">
                        <li class="list-group-item">Data Sekolah</li>
                        <li class="list-group-item">
                            NPSN: <?php echo $customer['no_npsn']; ?><br>
                            Nama Sekolah: <b><?php echo $customer['school_name']; ?></b><br>
                            Email Sekolah: <?php echo $customer['email']; ?>
                        </li>
                        <li class="list-group-item">Alamat</li>
                        <li class="list-group-item">
                            <?php
                            echo $customer['alamat'] . '<br />';
                            echo $customer['desa'] . ', ';
                            echo $customer['kecamatan'] . ', ';
                            echo $customer['kabupaten'] . ', ';
                            echo $customer['provinsi'];
                            echo ($customer['kodepos']) ? ' - ' . $customer['kodepos'] : '';
                            echo '<br>';
                            echo ($customer['phone']) ? 'Telpon: ' . $customer['phone'] : '';
                            ?>
                        </li>
                        <li class="list-group-item">Kepala Sekolah</li>
                        <li class="list-group-item">
                            Nama: <?php echo $customer['name']; ?><br>
                            Telpon: <?php echo $customer['phone_kepsek']; ?><br>
                        </li>
                        <li class="list-group-item">Operator Sekolah</li>
                        <li class="list-group-item">
                            Nama: <?php echo $customer['operator']; ?><br>
                            Email: <?php echo $customer['email_operator']; ?><br>
                            Telpon: <?php echo $customer['hp_operator']; ?>
                        </li>
                        <li class="list-group-item"></li>
                        <li class="list-group-item">
                            Total Harga: <strong><?php echo toRupiah($order['total_paid']); ?></strong>
                        </li>
                    </ul>
                </div>
            </div>
            <?php
            if ($this->session->flashdata('msg_success_percentage')) {
                echo '<div id="komisi-area">';
                echo notif('success', $this->session->flashdata('msg_success_percentage'));
                echo '</div>';
            }
            ?>
            <div class="panel panel-default">
                <div class="panel-heading"><h4>Komisi</h4></div>
                <div class="panel-body">
                    <li class="list-group-item">
                        <p>
                            <?php echo $comission['type'] . ' (' . $comission['comission_percent'] * 100 . '%)'; ?>: <?php echo toRupiah($comission['comission_amount']); ?><br>
                            PPh (<?php echo $comission['tax_percent']; ?>%): <?php echo toRupiah($comission['tax_amount']); ?><br><br>
                            Atas Nama: <?php echo $mitra['name']; ?><br>
                            Alamat Email: <?php echo $mitra['email']; ?><br>
                            No. Telpon/Hp: <?php echo $mitra['telp']; ?>
                            <?php if (($payout['status'] == 1 && in_array($adm_level, [3, 8])) || (in_array($payout['status'], [2, 3]) && in_array($adm_level, array_merge($this->backoffice_superadmin_area, [8])))) {?>
                                <br><br>
                                <a href="<?php echo base_url(ADMIN_PATH . '/comission/popupPercentage/' . $payout['id_order']); ?>" class="btn btn-warning" data-toggle="modal" data-target="#myModal">Ubah Persentase</a>
                            <?php }?>
                        </p>
                    </li>
                    <li class="list-group-item">
                        <p>
                            Total Komisi: <strong><?php echo toRupiah($comission['final_comission']); ?></strong>
                        </p>
                    </li>
                    <div class="modal fade" id="myModal" role="dialog" aria-labelledby="myModalLabel" data-keyboard="false" data-backdrop="static">
                        <div class="modal-dialog">
                            <div class="modal-content">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <?php if (isset($history_payout) && $history_payout) {?>
            <div class="panel panel-default">
                <div class="panel-heading"><h4>Riwayat</h4></div>
                <div class="panel-body">
                    <div class="table-responsive">
                            <table class="table table-bordered">
                                <thead>
                                <th class="text-center">Tanggal & Waktu</th>
                                <th class="text-center">Status</th>
                                <th class="text-center">Pengguna</th>
                                </thead>
                                <tbody>
                                <?php
                                foreach ($history_payout as $history) {
                                    $labelName = get_data(
                                        [
                                            'field' => 'name',
                                            'table' => 'payout_state',
                                            'key' => 'id',
                                            'data' => $history->id_payout_status,
                                        ]
                                    );
                                    $labelStatus = get_data(
                                        [
                                            'field' => 'label',
                                            'table' => 'payout_state',
                                            'key' => 'id',
                                            'data' => $history->id_payout_status,
                                        ]
                                    );
                                    $namaPengguna = get_data(
                                        [
                                            'field' => 'name',
                                            'table' => 'employee',
                                            'key' => 'id_employee',
                                            'data' => $history->id_employee,
                                        ]
                                    );
                                    ?>
                                    <tr>
                                        <td class="text-center"><?php echo $history->created_date; ?></td>
                                        <td class="text-center">
                                            <span class="label <?php echo $labelStatus ?>"><?php echo $labelName; ?></span>
                                            <?php if ($history->notes) {
                                                echo '<p style="margin-top:8px;">' . $history->notes . '</p>';
                                            }
                                            ?>
                                        </td>
                                        <td class="text-center"><?php echo $namaPengguna; ?></td>
                                    </tr>
                                <?php }?>
                                </tbody>
                            </table>
                        </div>
                </div>
            </div>
            <?php } if ($adm_level == 8 && $payout['status'] == 1) { ?>
            <?php echo form_open('', 'id="frm-approve"'); ?>
                <input type="hidden" name="id" id="id" value="<?php echo $payout['id']; ?>">
                <input type="hidden" name="percentage" id="percentage" value="<?php echo $payout['percentage']; ?>">
                <input type="hidden" name="reference" id="reference" value="<?php echo $order['reference']; ?>">
                <button type="submit" class="btn btn-success btn-lg pull-left" id="btn-ajukan">Setujui</button>
            <?php echo form_close(); ?>
            <?php } ?>
            <a href="<?php echo $url_back; ?>" class="btn btn-primary btn-lg pull-right">Kembali</a>
        </div>
    </div>
</div>
