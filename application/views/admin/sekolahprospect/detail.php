<div class="container-fluid">
    <!-- Page Heading -->
    <div class="row">
        <div class="col-lg-12">
            <h1 class="page-header">
                <?php echo $customer->school_name; ?>
            </h1>
            <ol class="breadcrumb">
                <li>
                    <a href="<?php echo base_url() . ADMIN_PATH; ?>">Beranda</a>
                </li>
                <li class="active">
                    <a href="<?php echo base_url() . ADMIN_PATH . $link_page; ?>"><?php echo $last_page; ?></a>
                </li>
                <li class="active">
                    Detil
                </li>
            </ol>
        </div>
    </div>
    <!-- /.row -->
    <div class="row">
        <div class="col-lg-12">
            <?php echo $this->session->flashdata('message') ?>
            <div class="panel panel-default">
                <div class="panel-heading"><h4>Data Sekolah</h4></div>
                <div class="panel-body">
                    <ul class="list-group">
                        <li class="list-group-item">NPSN: <?php echo $customer->no_npsn; ?></li>
                        <li class="list-group-item">Sekolah: <?php echo $customer->school_name; ?></li>
                        <li class="list-group-item">Zona: <?php echo $customer->zona; ?></li>
                        <li class="list-group-item">Email Sekolah: <?php echo $customer->email; ?></li>
                        <li class="list-group-item">Telpon Sekolah: <?php echo $customer->phone; ?></li>
                        <li class="list-group-item"></li>
                        <li class="list-group-item">Nama Kepala Sekolah: <?php echo $customer->name; ?></li>
                        <li class="list-group-item">Email Kepala Sekolah: <?php echo $customer->email_kepsek; ?></li>
                        <li class="list-group-item">Telpon Kepala Sekolah: <?php echo $customer->phone_kepsek; ?></li>
                        <li class="list-group-item"></li>
                        <li class="list-group-item">Nama Operator: <?php echo $customer->operator; ?></li>
                        <li class="list-group-item">Email Operator: <?php echo $customer->email_operator; ?></li>
                        <li class="list-group-item">Telpon Operator: <?php echo $customer->hp_operator; ?></li>
                        <li class="list-group-item"></li>
                        <li class="list-group-item">Alamat:</li>
                        <li class="list-group-item">
                            <?php
                                echo $customer->alamat.'<br />';
                                echo $customer->desa.', ';
                                echo $customer->kecamatan.', ';
                                echo $customer->kabupaten.', ';
                                echo $customer->provinsi.' - ';
                                echo $customer->kodepos.'<br />';
                                echo 'Telpon: '.$customer->phone;
                            ?>
                        </li>
                    </ul>
                </div>
            </div>

            <?php if ($this->adm_level == 3 && $request_sales == true && $customer->status_prospect == 2) { ?>
            <div class="panel panel-default">
                <div class="panel-heading"><h4>Request</h4></div>
                <div class="panel-body">
                    <?php if ($customer->status_prospect == 2) { ?>
                    <a href="#" class="btn btn-success" id="btn_accepted" data-toggle="modal" data-target="#modal_korwil">Menyetujui</a>
                    <br/><br/>
                    <?php } ?>
                    <div class="table-responsive">
                        <table class="table table-bordered">
                            <tr>
                                <th class="text-center" width="20%">Nama Sales</th>
                                <th class="text-center" width="20%">Email</th>
                                <th class="text-center" width="15%">No. Telepon</th>
                                <th class="text-center" width="15%">Tanggal Mulai</th>
                                <th class="text-center" width="30%">Keterangan</th>
                            </tr>
                            <tr>
                                <td><?php echo $request_sales_data->name; ?></td>
                                <td><?php echo $request_sales_data->email; ?></td>
                                <td><?php echo $request_sales_data->telp; ?></td>
                                <td class="text-center"><?php echo $request_sales_data->date_prospect_start; ?></td>
                                <td><?php echo $request_sales_data->notes ?></td>
                            </tr>
                        </table>
                    </div>
                </div>
            </div>
            <?php } ?>

            <div class="panel panel-default">
                <div class="panel-heading"><h4>Status</h4></div>
                <div class="panel-body">
                    <?php
                    if ($this->adm_level == 4) {
                        if ($customer->status_prospect != 1) { 
                    ?>
                        <div class="alert alert-danger alert-dismissable">
                            <b><span class="glyphicon glyphicon-info-sign"></span> Sekolah sedang dalam proses prospek sales.</b>
                        </div>
                    <?php 
                        } else {
                    ?>
                        <a href="#" class="btn btn-success" id="btn_request" data-toggle="modal" data-target="#modal_sales">Mengajukan prospek</a>
                        <br/><br/>
                    <?php } 
                    } ?>
                    <div class="table-responsive">
                        <table class="table table-bordered">
                            <tr>
                                <th class="text-center" width="15%">Status</th>
                                <th class="text-center" width="20%">Sales</th>
                                <th class="text-center" width="15%">Tanggal Mulai</th>
                                <th class="text-center" width="15%">Tanggal Expired</th>
                                <th class="text-center" width="35%">Keterangan</th>
                            </tr>
                            <?php if (empty($customer_history)) { ?>
                                <tr>
                                    <td colspan="5" align="center">Belum ada riwayat</td>
                                </tr>
                            <?php
                            } else {
                                $today  = date('Y-m-d');
                                $status = "";
                                foreach ($customer_history as $row) { 
                                    $info = "";
                                    if ($row->status_prospect == 1) {
                                        $info   = "info";
                                        $status = "Diajukan";
                                    } elseif ($row->status_prospect == 2 && strtotime($row->date_finish) >= strtotime($today)) {
                                        $info   = "danger";
                                        $status = "Dalam Proses";
                                    } elseif ($row->status_prospect == 2 && strtotime($row->date_finish) < strtotime($today)) {
                                        $status = "Selesai";
                                    }
                            ?>
                                <tr class="<?php echo $info; ?>">
                                    <td><?php echo $status; ?></td>
                                    <td><?php echo $row->name ?></td>
                                    <td class="text-center"><?php echo $row->date_start; ?></td>
                                    <td class="text-center"><?php if ($row->date_finish == '0000-00-00') {echo '-';} else {echo $row->date_finish;}; ?></td>
                                    <td><?php echo $row->notes ?></td>
                                </tr>
                            <?php }
                            } ?>
                        </table>
                    </div>
                </div>
            </div>

            <div class="panel panel-default">
                <div class="panel-heading"><h4>Riwayat Status</h4></div>
                <div class="panel-body">
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped">
                            <tr>
                                <th class="text-center" width="20%">Pengguna</th>
                                <th class="text-center" width="10%">Level</th>
                                <th class="text-center" width="15%">Status</th>
                                <th class="text-center" width="15%">Tanggal</th>
                                <th class="text-center" width="40%">Keterangan</th>
                            </tr>
                            <?php if (empty($prospect_history)) { ?>
                                <tr>
                                    <td colspan="5" align="center">Belum ada riwayat</td>
                                </tr>
                            <?php
                            } else {
                                $status_history = "";
                                $level = "";
                                $notes = "";
                                foreach ($prospect_history as $row) { 
                                    if ($row->status_prospect == 1) {
                                        $status_history = "Mengajukan";
                                        $level          = "Sales";
                                        $notes          = $row->notes;
                                    } elseif ($row->status_prospect == 2) {
                                        $status_history = "Menyetujui";
                                        $level          = "Korwil / EC";
                                        $notes          = 'Disetujui dengan durasi '.$row->duration_days.' hari';
                                    }
                            ?>
                                <tr>
                                    <td><?php echo $row->name ?></td>
                                    <td><?php echo $level ?></td>
                                    <td><?php echo $status_history; ?></td>
                                    <td class="text-center"><?php echo $row->date_add; ?></td>
                                    <td><?php echo $notes ?></td>
                                </tr>
                            <?php }
                            } ?>
                        </table>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>

<?php 
$customer_address = $customer->alamat."\n".$customer->desa.", ".$customer->kecamatan.", ".$customer->kabupaten.", ".$customer->provinsi.", ".$customer->kodepos;
?>

<div class="modal fade" id="modal_sales" role="dialog" aria-labelledby="myModalLabel" data-keyboard="false" data-backdrop="static">
    <div class="modal-dialog">
        <div class="modal-content">
            <?php echo form_open('', 'class="form-horizontal" data-action="' . base_url() . ADMIN_PATH . '/sekolahprospect/addRequest" id="form_request_sales" autocomplete="off" enctype="multipart/form-data"'); ?>
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h3 class="modal-title">Mengajukan Prospek</h3>
                </div>
                <div class="modal-body">
                    <input type="hidden" id="id_customer" name="id_customer" value="<?php echo $customer->id_customer; ?>">
                    <input type="hidden" id="cust_name" name="cust_name" value="<?php echo $customer->school_name; ?>">
                    <input type="hidden" id="cust_email" name="cust_email" value="<?php echo $customer->email; ?>">
                    <input type="hidden" id="cust_phone" name="cust_phone" value="<?php echo $customer->phone; ?>">
                    <input type="hidden" id="cust_address" name="cust_address" value="<?php echo $customer_address; ?>">
                    <div class="form-group">
                        <label class="col-sm-3 control-label">Tanggal Mulai</label>
                        <div class="col-sm-7">
                            <div class="input-group date" id="datetimepicker_startdate">
                                <input class="form-control datetimepicker" id="req_startdate" name="req_startdate" type="text">
                                <span class="input-group-addon add-on"><span class="glyphicon glyphicon-calendar"></span></span>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-3 control-label">Catatan</label>
                        <div class="col-sm-7">
                            <textarea class="form-control" id="req_notes" name="req_notes" style="resize:none;" type="text"></textarea>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-success" style="float: left;">P r o s e s</button>
                    <button type="submit" class="btn btn-danger" data-dismiss="modal">K e m b a l i</button>
                </div>
            <?php echo form_close(); ?>
        </div>
    </div>
</div>

<div class="modal fade" id="modal_korwil" role="dialog" aria-labelledby="myModalLabel" data-keyboard="false" data-backdrop="static">
    <div class="modal-dialog">
        <div class="modal-content">
            <?php echo form_open('', 'class="form-horizontal" data-action="' . base_url() . ADMIN_PATH . '/sekolahprospect/updateRequest/' . $customer->id_customer . '" id="form_accepted_korwil" autocomplete="off" enctype="multipart/form-data"'); ?>
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h3 class="modal-title">Pengajuan Prospek Sales</h3>
                </div>
                <div class="modal-body">
                    <input type="hidden" id="id_customer" name="id_customer" value="<?php echo $customer->id_customer; ?>">
                    <input type="hidden" id="cust_name" name="cust_name" value="<?php echo $customer->school_name; ?>">
                    <input type="hidden" id="cust_email" name="cust_email" value="<?php echo $customer->email; ?>">
                    <input type="hidden" id="cust_phone" name="cust_phone" value="<?php echo $customer->phone; ?>">
                    <input type="hidden" id="cust_address" name="cust_address" value="<?php echo $customer_address; ?>">
                    <input type="hidden" id="date_start" name="date_start" value="<?php echo $customer->date_prospect_start; ?>">
                    <input type="hidden" id="id_mitra" name="id_mitra" value="<?php echo $customer->id_mitra; ?>">
                    <input type="hidden" id="id_customer_prospect" name="id_customer_prospect" value="<?php echo $request_sales_data->id_customer_prospect; ?>">
                    <div class="form-group">
                        <label class="col-sm-5 control-label">Tanggal Mulai</label>
                        <div class="col-sm-5">
                            <div class="input-group date" id="datetimepicker_accdate">
                                <input class="form-control datetimepicker" id="req_startdate_acc" name="req_startdate_acc" type="text" value="<?php echo $customer->date_prospect_start; ?>">
                                <span class="input-group-addon add-on"><span class="glyphicon glyphicon-calendar"></span></span>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-5 control-label">Lama hari prospek</label>
                        <div class="col-sm-5">
                            <input class="form-control" id="acc_days" name="acc_days" placeholder="Lama hari prospek" type="text" value="7">
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-success" style="float: left;">P r o s e s</button>
                    <button type="submit" class="btn btn-danger" data-dismiss="modal">K e m b a l i</button>
                </div>
            <?php echo form_close(); ?>
        </div>
    </div>
</div>
