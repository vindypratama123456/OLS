<div class="container-fluid">

    <div class="row">
        <div class="col-lg-12">
            <h1 class="page-header">
                <?php echo $page_title; ?>
            </h1>
            <ol class="breadcrumb">
                <li>
                    <a href="<?php echo base_url() . ADMIN_PATH; ?>">Beranda</a>
                </li>
                <li class="active">
                    <?php echo $page_title; ?>
                </li>
            </ol>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-12">
            <?php echo $this->session->flashdata('message') ?>
            <div class="table-responsive">
                <table class="table table-striped dt-responsive nowrap table-loader" id="datatableListRequest">
                    <thead>
                        <tr>
                            <th class="text-center">NPSN</th>
                            <th class="text-center">Nama Sekolah</th>
                            <th class="text-center">Propinsi</th>
                            <th class="text-center">Kab/Kota</th>
                            <th class="text-center">Status</th>
                            <th class="text-center">Nama Sales</th>
                            <th class="text-center">Tanggal<br/>Mulai</th>
                            <th class="text-center">Tanggal<br/>Expired</th>
                            <?php if ($this->adm_level== 3) { ?>
                            <th class="text-center">
                                <a data-toggle="modal" data-target="#modalApprove" onclick="approveRequest()" id="btn_approve" class="btn btn-primary" data-toggle="tooltip" title="Setujui permintaan">OK</a>
                            </th>
                            <?php } else { ?>
                            <th class="text-center">Notes</th>
                            <?php } ?>
                        </tr>
                    </thead>
                </table>
            </div>
        </div>
    </div>
    <input type="hidden" name="user_code" id="user_code" value="<?php echo $user_code; ?>">
    <!-- modal -->
    <div class="modal inmodal" id="modalApprove" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content animated bounceInDown">
                <?php echo form_open(base_url() . ADMIN_PATH . '/sekolahprospect/updateMultipleRequest', 'method="post"'); ?>
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal">
                        <span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
                        <h4 class="modal-title">Persetujuan Permintaan Mitra</h4>
                    </div>
                    <div class="modal-body">
                        <input type="hidden" name="id" value="" id="id">
                        <div class="messages"></div>
                    </div>
                    <div class="modal-footer">
                        <input type="reset" name="reset" value="Batal" class="btn btn-danger" data-dismiss="modal" style="float: left;">
                        <input type="submit" name="move" value="Proses" class="btn btn-primary action">
                    </div>
                <?php echo form_close(); ?>
            </div>  
        </div>
    </div>
</div>