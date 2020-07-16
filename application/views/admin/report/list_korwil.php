<div class="container-fluid">

    <!-- Page Heading -->
    <div class="row">
        <div class="col-lg-12">
            <h1 class="page-header">
                Laporan
            </h1>
            <ol class="breadcrumb">
                <li>
                    <a href="<?php echo base_url() . ADMIN_PATH; ?>">Beranda</a>
                </li>
                <li class="active">
                    Laporan
                </li>
            </ol>
        </div>
    </div>
    <!-- /.row -->

    <div class="row">
        <div class="col-lg-12">
            <div class="row">
                <?php echo form_open('', 'id="form-report" data-action="' . base_url(ADMIN_PATH . '/report/searchKorwil') . '" accept-charset="utf-8"'); ?>
                    <div class="col-md-5">
                        <p>Silahkan pilih tanggal awal</p>
                        <div class="form-group">
                            <div class="input-group date" id="datetimepicker6">
                                <input type="text" class="form-control" name="tgl_mulai" id="tgl_mulai" />
                                <span class="input-group-addon">
                                    <span class="glyphicon glyphicon-calendar"></span>
                                </span>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-5">
                        <p>Silahkan pilih tanggal akhir</p>
                        <div class="form-group">
                            <div class="input-group date" id="datetimepicker7">
                                <input type="text" class="form-control" name="tgl_akhir" id="tgl_akhir" required />
                                <span class="input-group-addon">
                                    <span class="glyphicon glyphicon-calendar"></span>
                                </span>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-2">
                        <p><br /></p>
                        <input type="submit" class="btn btn-primary" id="cari-report" value="Cari" />
                    </div>
                <?php echo form_close(); ?>
            </div>
            <br />
            <div class="table-responsive" id="result-area"></div>
        </div>
    </div>
    <!-- /.row -->

</div>
<!-- /.container-fluid -->