<style type="text/css">
    div.DTTT { margin-bottom: 0.5em; float: right; }
    div.dataTables_wrapper { clear: both; }
</style>

<div class="container-fluid">

    <!-- Page Heading -->
    <div class="row">
        <div class="col-lg-12">
            <h1 class="page-header">
                Pesanan yang harus dikirim
            </h1>
            <ol class="breadcrumb">
                <li>
                    <a href="<?php echo base_url() . ADMIN_PATH; ?>">Beranda</a>
                </li>
                <li class="active">
                    Pesanan yang harus dikirim
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
            if($this->session->flashdata('msg_failed')) {
                echo notif('danger',$this->session->flashdata('msg_failed'));
            }
            ?>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-12">
            <div class="table-responsive">
                <div class="panel panel-default">
                <!-- Default panel contents -->
                <div class="panel-heading"><h4>Daftar Pesanan</h4></div>
                <div class="panel-body">
                    <div class="table-responsive">
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th class="text-center">Kode Pesanan</th>
                                    <th class="text-center">Nama Sekolah</th>
                                    <th class="text-center">Provinsi</th>
                                    <th class="text-center">Kab. / Kota</th>
                                    <th class="text-center">Kecamatan</th>
                                    <th class="text-center">Status</th>
                                    <th class="text-center" width="300px;">Action</th>
                                </tr>
                                <tbody>
                                <?php foreach($order_siap_kirim as $item) { ?>
                                <tr>
                                    <td class="text-center"><?php echo $item->reference; ?></td>
                                    <td class="text-center"><?php echo $item->school_name; ?></td>
                                    <td class="text-center"><?php echo $item->provinsi; ?></td>
                                    <td class="text-center"><?php echo $item->kabupaten; ?></td>
                                    <td class="text-center"><?php echo $item->kecamatan; ?></td>
                                    <td class="text-center"><center><span class="label <?php echo $item->status_label ?>"><?php echo $item->status_name ?></span></center></td>
                                    <td class="text-center"><center><a href="#" onclick="printBAST(<?php echo $item->id_order?>)" class="btn btn-primary">BAST</a>&nbsp;<a href="#" onclick="printKwintansi(<?php echo $item->id_order?>)"  class="btn btn-primary">Kwintansi</a>&nbsp;<a href="#" class="btn btn-danger" id="triggerModalUploadBast" data-toggle="modal" data-target="#uploadBast" data-title="Upload BAST untuk order <?php echo $item->reference ?>" data-reference="<?php echo $item->reference ?>">Upload BAST</a></center></td>
                                    <?php
                                }
                                ?>
                                </tbody>
                            </thead>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- /.row -->

</div>
<!-- Modal -->
<div class="modal fade" id="uploadBast" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="title_modal_upload_bast"></h4>
      </div>
      <?php echo form_open(base_url() . ADMIN_PATH . '/orders/uploadbast', 'name="uploadBast" enctype="multipart/form-data" method="post"'); ?>
      <div class="modal-body">
            <input type="file" name="bast">
            <input type="hidden" name="no_reference" id="no_reference_modal_upload_bast">
      </div>
      <div class="modal-footer">
        <button type="reset" class="btn btn-default" data-dismiss="modal">Close</button>
        <button type="submit" class="btn btn-primary">Save changes</button>
      </div>
      <?php echo form_close(); ?>
    </div>
  </div>
</div>
<!-- /.container-fluid -->

<script>
$(document).ready(function () {
    $("#triggerModalUploadBast").click(function () {
        $('#no_reference_modal_upload_bast').val($(this).data('reference'));
        $('#title_modal_upload_bast').html($(this).data('title'));
    });
});
function printBAST(id){
    window.open('<?php echo base_url(ADMIN_PATH."/orders/cetakBAST"); ?>/'+id,'page','toolbar=0,scrollbars=1,location=0,statusbar=0,menubar=0,resizable=0,width=750,height=600,left=50,top=50,titlebar=no');
}
function printKwintansi(id){
    window.open('<?php echo base_url(ADMIN_PATH."/orders/cetakKwintansi"); ?>/'+id,'page','toolbar=0,scrollbars=1,location=0,statusbar=0,menubar=0,resizable=0,width=750,height=600,left=50,top=50,titlebar=no');
}
</script>