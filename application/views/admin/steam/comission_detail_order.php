<div class="container-fluid">
    <div class="row">
        <div class="col-lg-12">
            <h1 class="page-header">
                <!-- Pesanan: <a href="<?php echo base_url() . ADMIN_PATH . '/orders/detail/' . $detil['id_order']; ?>" target="_blank">#<?php echo $detil['reference']; ?></a> -->

                Pesanan: #<?php echo $detil['reference']; ?>
            </h1>
            <ol class="breadcrumb">
                <li>
                    <a href="<?php echo base_url() . ADMIN_PATH; ?>">Beranda</a>
                </li>
                <li>
                    <a href="<?php echo base_url() . ADMIN_PATH; ?>/comission">Komisi</a>
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
            if($this->session->flashdata('msg_failed')) {
                echo notif('danger',$this->session->flashdata('msg_failed'));
            }
            ?>
            <div class="panel panel-default">
                <div class="panel-heading"><h4>Pelanggan</h4></div>
                <div class="panel-body">
                    <ul class="list-group">
                        <li class="list-group-item">
                            <!-- NPSN: <?php echo $customer['no_npsn']; ?><br> -->
                            Nama Pelanggan: <b><?php echo $customer['school_name']; ?></b><br>
                            Email: <?php echo $customer['email']; ?><br>
                            Telpon: <?php echo $customer['phone']; ?><br>
                        </li>
                        <li class="list-group-item">Alamat</li>
                        <li class="list-group-item">
                            <?php echo $customer['alamat']; ?>
                        </li>
                        <li class="list-group-item">
                            Total Pesanan: <strong><?php echo toRupiah($detil['total_paid']); ?></strong>
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
                <div class="panel-heading">
                    <h4 class="pull-left">Komisi</h4>&nbsp;&nbsp;
                    <!-- <a href="<?php echo base_url(ADMIN_PATH.'/steam/influencer_popup/'.$detil['id_order']); ?>" class="btn btn-primary" data-toggle="modal" data-target="#myModal"><i class="fa fa-plus-square"></i> Input Influencer</a> -->
                </div>
                <div class="panel-body">
                    <li class="list-group-item">
                        <p>
                            Sales (<?php echo $comission['direct']['percentage'] * 100;  ?>%): <strong><?php echo toRupiah($comission['direct']['amount']); ?></strong><br>
                            PPh (<?php echo $comission['direct']['tax'] * 100; ?>%): <?php echo toRupiah($comission['direct']['tax_value']); ?><br>
                            Nilai Akhir: <strong><?php echo toRupiah($comission['direct']['payout']); ?></strong><br><br>
                            Atas Nama: <?php echo $uDirect['nama'] ; ?><br>
                            Alamat Email: <?php echo $uDirect['email'] ; ?><br>
                            No. Telpon/Hp: <?php echo $uDirect['telpon'] ; ?>
                            <?php if ( (!isset($data_payout) || $data_payout['id_payout_status']==1) && $adm_level==102) { ?>
                            <br><br>
                            <a href="<?php echo base_url(ADMIN_PATH.'/steam/comission_popup_percentage/'.$detil['id_order']); ?>" class="btn btn-warning" data-toggle="modal" data-target="#myModal">Ubah Persentase</a>
                            <?php }  ?>
                        </p>
                    </li>
                    <!-- <?php if ($comission['referral']['amount']) { ?>
                    <li class="list-group-item">
                        <p>
                            Influencer (<?php echo $referral['percentage'] * 100 ; ?>%): <strong><?php echo toRupiah($comission['referral']['amount']); ?></strong><br>
                            PPh (<?php echo $comission['referral']['tax'] * 100; ?>%): <?php echo toRupiah($comission['referral']['tax_value']); ?><br>
                            Nilai Akhir: <strong><?php echo toRupiah($comission['referral']['payout']); ?></strong><br><br>
                            Atas Nama: <?php echo $referral['nama'] ; ?><br>
                            Alamat Email: <?php echo $referral['email'] ; ?><br>
                            No. Telpon/Hp: <?php echo $referral['telpon'] ; ?>
                            <br><br>
                            <a href="<?php echo base_url(ADMIN_PATH.'/steam/comission_popup_percentage_influencer/'.$detil['id_order']); ?>" class="btn btn-warning" data-toggle="modal" data-target="#myModal">Ubah Persentase</a>

                            <!-- <?php if (isset($data_payout) && $data_payout['referral_status'] == 0 && $data_payout['id_payout_status'] == 2 && $adm_level == 6) { ?>
                            <br><br>
                            <a href="<?php echo base_url(ADMIN_PATH.'/comission/popupPaidoff/' . $detil['id_order'] . '/2/' . $detil['total_paid']); ?>" class="btn btn-warning" data-toggle="modal" data-target="#myModal">Konfirmasi Pembayaran</a>
                            <?php } ?> -->
                        </p>
                    </li>
                    <?php } ?> -->
                    <li class="list-group-item">
                        <p>
                            <!-- Total Komisi: <strong><?php echo toRupiah($comission['direct']['payout'] + $comission['referral']['payout']); ?></strong> -->
                            Total Komisi: <strong><?php echo toRupiah($comission['direct']['payout']); ?></strong>
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
            <?php if ( ! isset($data_payout) && $adm_level == 102) { ?>
            <?php echo form_open('', 'id="frm-proposed"'); ?>
                <input type="hidden" name="id_order" id="id_order" value="<?php echo $detil['id_order']; ?>">
                <input type="hidden" name="order_reference" id="order_reference" value="<?php echo $detil['reference']; ?>">
                <input type="hidden" name="direct_email" id="direct_email" value="<?php echo $uDirect['email']; ?>">
                <input type="hidden" name="direct_percent" id="direct_percent" value="<?php echo $comission['direct']['percentage']; ?>">
                <input type="hidden" name="direct_tax" id="direct_tax" value="<?php echo $comission['direct']['tax']; ?>">
                <!-- <?php if ($comission['referral']['amount']) { ?>
                <input type="hidden" name="referral_email" id="referral_email" value="<?php echo $referral['email']; ?>">
                <input type="hidden" name="referral_tax" id="referral_tax" value="<?php echo $comission['referral']['tax']; ?>">
                <?php } ?> -->
                <?php if ( ! isset($data_payout)) { ?>
                <button type="submit" class="btn btn-success btn-lg pull-left" id="btn-ajukan">Diajukan</button>
                <?php } ?>
            <?php echo form_close(); ?>
            <?php }  ?>
            <a href="<?php echo $url_back; ?>" class="btn btn-primary btn-lg pull-right">Kembali</a>
        </div>
    </div>
</div>
<div class="modal fade" id="myModal" role="dialog" aria-labelledby="myModalLabel" data-keyboard="false" data-backdrop="static">
    <div class="modal-dialog modal-lg">
      <div class="modal-content">
      </div>
    </div>
</div>
