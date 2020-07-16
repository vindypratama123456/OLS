<style>
    .table thead th, .table tbody td { vertical-align: middle !important; }
    .background-head { background-color: #222; }
    .text-head { color: white; }
</style>
<div class="container-fluid">
    <div class="row">
        <div class="col-lg-12">
            <ol class="breadcrumb">
                <li>
                    <a href="<?php echo base_url() . ADMIN_PATH; ?>">Beranda</a>
                </li>
                <li>
                    <a href="<?php echo $url_back; ?>">Komisi</a>
                </li>
                <li class="active">
                    Detil Proses SAP
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
                <div class="panel-heading"><h4>Detail Proses SAP</h4></div>
                <div class="panel-body">
                    <ul class="list-group">
                        <li class="list-group-item">
                            No. Proses: <b><?php echo $payout_comission[0]->sap_no; ?></b><br>
                            Tanggal Proses: <?php echo ($payout_comission[0]->sap_date == '0000-00-00') ? '-' :
                                tgl_indo($payout_comission[0]->sap_date, 5); ?>
                            <!-- <?php
                                if (in_array($adm_level, [6, 7, 14]) && $payout_comission[0]->status==3 && $payout_comission[0]->is_posting==0) {
                                    $judul = (in_array($payout_comission[0]->transfer_date, ['0000-00-00', null])) ? 'Input' : 'Ubah';
                                    echo '<br><a href="' . base_url(ADMIN_PATH.'/comission/popupPaidoff/' .  $payout_comission[0]->no_pd) . '" class="btn btn-default" data-toggle="modal" data-target="#myModal" style="margin-top:5px;"><span class="glyphicon glyphicon-calendar"></span> ' . $judul . ' Tanggal</a>';
                                }
                            ?> -->
                        </li>
                        <!-- <li class="list-group-item">
                            Status Komisi: <?php echo '<span class="label '.$payout_status->label.'">'.$payout_status->name.'</span>'; ?>
                        </li> -->
                        <!-- <?php
                        if (in_array($adm_level, [6, 7, 14]) && $payout_comission[0]->status==3 && !in_array($payout_comission[0]->transfer_date, ['0000-00-00', null]) && $payout_comission[0]->is_posting<>1) {
                            $disabled = $payout_comission[0]->is_posting==-1 ? ' disabled' : '';
                        ?>
                        <li class="list-group-item">
                            <button class="btn btn-lg btn-success" id="btn-posting" data-no-pd="<?php echo $payout_comission[0]->no_pd; ?>" style="margin-top:5px;" <?php echo $disabled; ?>>
                                <span class="glyphicon glyphicon-send"></span>&nbsp; POSTING
                            </button>
                        </li>
                        <?php } if (in_array($adm_level, [6, 7, 14]) && $payout_comission[0]->status==3 && !in_array($payout_comission[0]->transfer_date, ['0000-00-00', null]) && $payout_comission[0]->is_posting==1) { ?>
                        <li class="list-group-item">
                            <button class="btn btn-lg btn-success" id="btn-bayar" data-no-pd="<?php echo $payout_comission[0]->no_pd; ?>" style="margin-top:5px;">
                                <span class="glyphicon glyphicon-ok-sign"></span> Konfirmasi Dibayar
                            </button>
                        </li>
                        <?php } ?> -->
                    </ul>
                    <div class="table-responsive">
                        <table class="table dt-responsive table-bordered">
                            <thead class="background-head">
                                <tr class="text-head">
                                    <th class="text-center">No.</th>
                                    <th class="text-center">Transfer Kepada</th>
                                    <th class="text-center">Nama Rekening</th>
                                    <th class="text-center">Bank/Alamat</th>
                                    <th class="text-center">Sandi BI</th>
                                    <th class="text-center">No. Account</th>
                                    <th class="text-center">Kode Pesanan</th>
                                    <th class="text-center">Nilai Pesanan</th>
                                    <th class="text-center">% Komisi</th>
                                    <th class="text-center">Komisi Awal</th>
                                    <th class="text-center">% PPh</th>
                                    <th class="text-center">Nilai PPh</th>
                                    <th class="text-center">Nilai Komisi</th>
                                    <th class="text-center">Total Komisi</th>
                                    <!-- <th class="text-center">Cetak</th> -->
                                </tr>
                            </thead>
                            <tbody>
                            <?php
                            if (!empty($detail)) {
                                $num = 1;
                                $totPesanan = 0;
                                $totKomisiAwal = 0;
                                $totNilaiPPH = 0;
                                $totNilaiKomisi = 0;
                                $totTransfer = 0;
                                foreach ($detail as $data) {
                                    foreach ($data['orders'] as $row => $value) {
                                        ?>
                                        <tr>
                                            <?php if ($row == 0) { ?>
                                                <td rowspan="<?php echo $data['rows'] ?>" class="text-center">
                                                    <?php echo $num; ?>
                                                </td>
                                                <td rowspan="<?php echo $data['rows'] ?>">
                                                    <?php echo strtoupper($data['nama']); ?>
                                                </td>
                                                <td rowspan="<?php echo $data['rows'] ?>">
                                                    <?php echo strtoupper($data['nama_rekening']); ?>
                                                </td>
                                                <td rowspan="<?php echo $data['rows'] ?>" class="text-center">
                                                    <?php echo strtoupper($data['alias_bank']); ?>
                                                </td>
                                                <td rowspan="<?php echo $data['rows'] ?>" class="text-center">
                                                    <?php echo $data['kode_bank']; ?>
                                                </td>
                                                <td rowspan="<?php echo $data['rows'] ?>" class="text-center">
                                                    <?php echo $data['no_rekening']; ?>
                                                </td>
                                            <?php } ?>
                                            <td class="text-center">
                                                <?php echo $value['kode_pesanan']; ?>
                                            </td>
                                            <td class="text-right">
                                                <?php echo toRupiah($value['nilai_pesanan']); ?>
                                            </td>
                                            <td class="text-center">
                                                <?php echo ($value['persen_komisi'] * 100) . '%'; ?>
                                            </td>
                                            <td class="text-right">
                                                <?php echo toRupiah(round($value['persen_komisi'] * $value['nilai_pesanan'])); ?>
                                            </td>
                                            <td class="text-right">
                                                <?php echo ($value['persen_pph'] * 100) . '%'; ?>
                                            </td>
                                            <td class="text-right">
                                                <?php echo toRupiah($value['nilai_pph']); ?>
                                            </td>
                                            <td class="text-right">
                                                <?php echo toRupiah($value['total_amount']); ?>
                                            </td>
                                            <?php if ($row == 0) { ?>
                                                <td rowspan="<?php echo $data['rows'] ?>" class="text-right">
                                                    <?php echo toRupiah($data['total_amount']); ?>
                                                </td>
                                                <!-- <td rowspan="<?php echo $data['rows'] ?>" class="text-center">
                                                    <?php if ($data['tgl_transfer'] != "0000-00-00") { ?>
                                                        <a href="#" class="btn btn-warning" onclick="printPajak(<?php echo $data['id_mitra_profile']; ?>)">Cetak</a>
                                                    <?php } else { echo "-"; } ?>
                                                </td> -->
                                            <?php } ?>
                                        </tr>
                                        <?php
                                        $totPesanan+=$value['nilai_pesanan'];
                                        $totKomisiAwal+=round($value['persen_komisi'] * $value['nilai_pesanan']);
                                        $totNilaiPPH+=$value['nilai_pph'];
                                        $totNilaiKomisi+=$value['total_amount'];
                                    }
                                    $num++;
                                    $totTransfer+=$data['total_amount'];
                                }
                            }
                            else
                            {
                                echo "<script>alert('Terjadi kesalahan pada data Sales atau Influencer. Mohon dilengkapi.');</script>";      
                                $totPesanan = 0;
                                $totKomisiAwal = 0;
                                $totNilaiPPH = 0;
                                $totNilaiKomisi = 0;
                                $totTransfer = 0;                             
                            }
                            ?>
                                <tr>
                                    <td colspan="7" class="text-center">T O T A L </td>
                                    <td class="text-right"><?php echo toRupiah($totPesanan); ?></td>
                                    <td></td>
                                    <td class="text-right"><?php echo toRupiah($totKomisiAwal); ?></td>
                                    <td></td>
                                    <td class="text-right"><?php echo toRupiah($totNilaiPPH); ?></td>
                                    <td class="text-right"><?php echo toRupiah($totNilaiKomisi); ?></td>
                                    <td class="text-right"><?php echo toRupiah($totTransfer); ?></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <!-- <a href="#" class="btn btn-warning btn-lg pull-left" style="margin-left:8px;" onclick="printPesananDana()">To Excel</a> -->
            <!-- <a href="<?php echo base_url(ADMIN_PATH.'/steam/cetak_komisi/'.$payout_comission[0]->sap_no); ?>" class="btn btn-warning btn-lg pull-left" id="print_excel1" style="margin-left:8px;">CETAK KOMISI</a> -->
            <a href="<?php echo $url_back; ?>" class="btn btn-primary btn-lg pull-right">Kembali</a>
        </div>
        <div class="modal fade" id="myModal" role="dialog" aria-labelledby="myModalLabel" data-keyboard="false" data-backdrop="static">
            <div class="modal-dialog">
                <div class="modal-content"></div>
            </div>
        </div>
    </div>
</div>
<script type="text/javascript">
    function printPesananDana(){
        window.open('<?php echo base_url(ADMIN_PATH . '/comission/printPesananDana/' . $payout_comission[0]->no_pd); ?>', 'page','toolbar=0,scrollbars=1,location=0,statusbar=0,menubar=0,resizable=0,width=750,height=500,left=50,top=50,titlebar=no');
    }
    function printPajak(idMitra){
        window.open('<?php echo base_url(ADMIN_PATH . '/comission/printPajak/' . $payout_comission[0]->no_pd); ?>/'+idMitra,'page','toolbar=0,scrollbars=1,location=0,statusbar=0,menubar=0,resizable=0,width=750,height=600,left=50,top=50,titlebar=no');
    }
</script>
