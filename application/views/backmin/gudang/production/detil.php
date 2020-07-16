<!-- START BREADCRUMB -->
<ul class="breadcrumb">
    <li><a href="<?php echo base_url(BACKMIN_PATH); ?>">Dasbor</a></li>
    <li><a href="<?php echo base_url(BACKMIN_PATH.'/gudangrequeststock'); ?>">Permintaan Stok</a></li>
    <li class="active">Detil Data</li>
</ul>
<!-- END BREADCRUMB -->
<!-- PAGE TITLE -->
<div class="page-title">                    
    <h2><span class="glyphicon glyphicon-bookmark"></span> Production Order</h2>
</div>
<!-- END PAGE TITLE -->                
<!-- PAGE CONTENT WRAPPER -->
<div class="page-content-wrap">
    <div class="row">                        
        <div class="col-md-12">
            <?php if($this->session->flashdata('success')): ?>
            <div role="alert" class="alert alert-success">
                <button data-dismiss="alert" class="close" type="button"><span aria-hidden="true">×</span><span class="sr-only">Close</span></button>
                <?php echo $this->session->flashdata('success'); ?>
            </div>
            <?php endif; ?>
            <?php if($this->session->flashdata('error')): ?>
            <div role="alert" class="alert alert-danger">
                <button data-dismiss="alert" class="close" type="button"><span aria-hidden="true">×</span><span class="sr-only">Close</span></button>
                <?php echo $this->session->flashdata('error'); ?>
            </div>
            <?php endif; ?>

            <div class="panel panel-primary">
                <div class="panel-heading">
                    <h3 class="panel-title">Production Order</h3>
                </div>
                <div class="panel-body">
                    <ul class="list-group">
                        <li class="list-group-item">No. OEF : <?php echo $detail['no_oef']; ?></li>
                        <li class="list-group-item">Gudang : <?php echo $gudang['nama_gudang']; ?></li>
                        <li class="list-group-item">Kode Buku : <?php echo $detail['kode_buku']; ?></li>
                        <li class="list-group-item">Judul : <?php echo $detail['judul']; ?></li>
                        <li class="list-group-item">Jumlah request : <?php echo $detail['jumlah_request']; ?></li>
                        <li class="list-group-item">Jumlah kirim : <?php echo $detail['jumlah_kirim']; ?></li>
                        <li class="list-group-item">Alokasi : <?php if($detail['catatan_alokasi']==""){echo "-";}else{echo $detail['catatan_alokasi'];} ?></li>
                        <li class="list-group-item">Status : <?php echo $status; ?></li>
                    </ul>

                    <br/>
                    <?php if($detail['catatan_alokasi']==""){ ?>
                    <a href="<?php echo base_url(BACKMIN_PATH.'/gudangproduction/detailOrderUpdate/'.$detail['id']); ?>" class="btn btn-warning pull-left" data-toggle="modal" data-target="#myModal">Ubah Data</a>
                    <?php } ?>
                </div>
            </div>
            <?php echo form_open(base_url(BACKMIN_PATH . '/gudangproduction/detailorderpost'), 'data-action="' . base_url(BACKMIN_PATH . '/gudangproduction/detailorderpost') . '" id="orders_form" autocomplete="off" enctype="multipart/form-data"'); ?>
                <div class="panel panel-primary">
                    <div class="panel-heading">
                        <h3 class="panel-title">Status</h3>
                    </div>
                    <div class="panel-body panel-body-table">
                        <div class="table-responsive">
                            <table class="table table-bordered table-striped">
                                <tr>
                                    <th class="text-center">Status</th>
                                    <th class="text-center">Pengguna</th>
                                    <th class="text-center">Tanggal</th>
                                    <th class="text-center">Waktu Sistem</th>
                                    <th class="text-center">Keterangan</th>
                                </tr>
                                <tr>
                                    <td>Active</td>
                                    <td><?php echo $admin['name']; ?></td>
                                    <td></td>
                                    <td class="text-center"><?php echo $detail['created_date']; ?></td>
                                    <td></td>
                                </tr>
                                <?php 
                                    foreach($history as $d)
                                    {
                                ?>
                                <tr>
                                    <td><?php echo $d['status']; ?></td>
                                    <td><?php echo $d['name']; ?></td>
                                    <td></td>
                                    <td class="text-center"><?php echo $d['created_date']; ?></td>
                                    <td><?php echo $d['notes']; ?></td>
                                </tr>
                                <?php
                                    }
                                ?>
                                <!-- <?php if ($detil['current_state']==2 && count($liststatus)<1) { ?>
                                <tr>
                                    <td>Dibatalkan</td>
                                    <td><?php echo $customer['school_name']; ?></td>
                                    <td></td>
                                    <td class="text-center"><?php echo $detil['date_upd']; ?></td>
                                    <td><?php echo $detil['alasan_batal']; ?></td>
                                </tr>
                                <?php
                                }
                                ?> -->
                            </table>
                        </div>
                    </div>
                </div>
                <div class="panel panel-default">
                    <div class="panel-body">
                        <!-- <input type="hidden" name="id_order" value="<?=$detil['id_order']?>" /> -->
                        <div class="form-group">
                            <div class="col-md-12">
                                <label>Ubah Status</label>
                                <input type="hidden" name="id" value="<?=$detail['id']?>">
                                <input type="hidden" name="no_oef" value="<?=$detail['no_oef']?>">
                                <select class="form-control" name="status" id="status">
                                    <option value="0" <?=$detail['status']==0 ? 'selected':'';?>>Canceled</option>
                                    <option value="1" <?=$detail['status']==1 ? 'selected':'';?>>Active</option>
                                    <option value="2" <?=$detail['status']==2 ? 'selected':'';?>>Closed</option>
                                </select>
                            </div>
                        </div>
                        <div class="form-group" id="form-catatan">
                            <div class="col-md-12"><br/>
                                <label>Keterangan</label>
                                <input type="text" class="form-control" id="catatan" name="catatan" value="<?php echo $detail['notes']; ?>">
                            </div>
                        </div>
                    </div>
                </div>
                <div class="panel panel-default">
                    <div class="panel-body">
                        <div class="col-md-12">
                            <button type="submit" class="btn btn-success btn-lg pull-left" id="btn_status">Simpan</button>
                            <a href="<?php echo base_url().BACKMIN_PATH.'/gudangproduction'; ?>" class="btn btn-primary btn-lg pull-right">Kembali</a>
                        </div>
                    </div>
                </div>
            <?php echo form_close(); ?>
        </div>
    </div>
</div>
<!-- END PAGE CONTENT WRAPPER -->

<div class="modal fade" id="myModal" role="dialog" aria-labelledby="myModalLabel" data-keyboard="false" data-backdrop="static">
    <div class="modal-dialog" style="width:50%;">
      <div class="modal-content">
      </div>
    </div>
</div>