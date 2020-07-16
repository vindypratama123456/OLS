<?php $this->load->view("tshops/header"); ?>

<link href="<?php echo assets_url('js/admin/plugins/select2/css/select2.min.css?v='.date('YmdHis')); ?>" rel="stylesheet">
<link href="<?php echo CaptchaUrls::LayoutStylesheetUrl(); ?>" type="text/css" rel="stylesheet" />

<div class="container main-container headerOffset">
    <div class="row">
        <div class="breadcrumbDiv col-lg-12">
            <ul class="breadcrumb">
                <li><a href="<?php echo base_url(); ?>">Beranda</a></li>
                <li class="active"> Register</li>
            </ul>
        </div>
    </div>
    <div class="row">
        <div class="col-lg-12 col-md-12  col-sm-12">
            <?php if($this->session->flashdata('success')): ?>
                <div role="alert" class="alert alert-success">
                    <button data-dismiss="alert" class="close" type="button"><span aria-hidden="true">×</span><span class="sr-only">Close</span></button>
                    <span class="glyphicon glyphicon-ok-circle"></span>&nbsp; <?php echo $this->session->flashdata('success'); ?>
                </div>
            <?php endif; ?>
            <?php if($this->session->flashdata('error')): ?>
                <div role="alert" class="alert alert-danger">
                    <button data-dismiss="alert" class="close" type="button"><span aria-hidden="true">×</span><span class="sr-only">Close</span></button>
                    <span class="glyphicon glyphicon-info-sign"></span>&nbsp; <?php echo $this->session->flashdata('error'); ?>
                </div>
            <?php endif; ?>

            <?php if ($this->session->flashdata('alert_error') !== null) { ?>
                <div class="alert alert-danger">
                    <h3>Terdapat <?php echo $this->session->flashdata('error_count') ?> isian yang salah!</h3>
                    <ol>
                    <?php foreach ($this->session->flashdata('alert_error') as $data) { ?>
                        <li style="padding:2px 0;"><?php echo $data ?></li>
                    <?php } ?>
                    </ol>
                </div>
            <?php } ?>

            <h1 class="section-title-inner"><span><i class="fa fa-lock"></i> Registrasi</span></h1>
            <div class="row userInfo">
                <div class="col-xs-12 col-sm-12">
                    <?php echo form_open('', 'class="form-signin" method="post" id="register" autocomplete="off"'); ?>
                        <div class="form-group">
                            <label>NPSN</label>
                            <input type="text" class="form-control" name="reg_no_npsn" id="reg_no_npsn" value="<?php echo $this->session->flashdata('reg_npsn'); ?>">
                        </div>
                        <div class="form-group">
                            <label>Nama Sekolah</label>
                            <input type="text" class="form-control" name="reg_school_name" id="reg_school_name">
                        </div>
                        <div class="form-group">
                            <label>Bentuk Pendidikan</label><br>
                            <select id="reg_bentuk" name="reg_bentuk" class="form-control">
                                <option value=''>- Pilih Bentuk Pendidikan -</option>
                                <?php foreach ($bentuk as $data) { ?>
                                <option value="<?php echo $data->bentuk; ?>"> <?php echo $data->bentuk; ?> </option>
                                <?php } ?>
                            </select>
                        </div>
                        <div class="form-group">
                            <label>Jenjang Pendidikan</label><br>
                            <select id="reg_jenjang" name="reg_jenjang" class="form-control">
                                <option value=''>- Pilih Jenjang Pendidikan -</option>
                                <?php foreach ($jenjang as $data) { ?>
                                <option value="<?php echo $data->jenjang ?>"> Kelas <?php echo $data->jenjang ?> </option>
                                <?php } ?>
                            </select>
                        </div>
                        <hr />
                        <div class="form-group">
                            <label>Alamat</label>
                            <textarea class="form-control" name="reg_alamat" id="reg_alamat"></textarea>
                        </div>
                        <div class="form-group">
                            <label>Provinsi</label><br>
                            <select id="reg_provinsi" name="reg_provinsi" class="form-control">
                                <option value=''>- Pilih Provinsi -</option>
                                <?php foreach ($provinsi as $data) { ?>
                                <option value="<?php echo $data->provinsi ?>"> <?php echo $data->provinsi ?> </option>
                                <?php } ?>
                            </select>
                        </div>
                        <div class="form-group">
                            <label>Kabupaten / Kota</label><br>
                            <select id="reg_kabupaten" name="reg_kabupaten" class="form-control">
                                <option value=''>- Pilih Kabupaten -</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label>Nomor Telepon Sekolah</label>
                            <input type="text" class="form-control" name="reg_phone" id="reg_phone" value="<?php echo $this->session->flashdata('reg_phone'); ?>">
                        </div>
                        <div class="form-group">
                            <label>Alamat Surel Sekolah (Email)</label>
                            <input type="email" class="form-control" name="reg_email" id="reg_email" value="<?php echo $this->session->flashdata('reg_email'); ?>">
                        </div>
                        <hr />
                        <div class="form-group">
                            <?php echo $captchaHtml; ?>
                            <div class="col-xs-8 col-sm-4 col-md-3">
                                <input type="text" class="form-control" name="CaptchaCode" id="CaptchaCode" value="" size="50" maxlength="10" style="margin-left:-15px; margin-top:5px;clear:bottom;">
                            </div>
                        </div>
                        <br><br><br>
                        <button type="submit" id="submitReg" class="btn btn-lg btn-primary"><i class="fa fa-sign-in"></i> &nbsp; D a f t a r</button>
                    <?php echo form_close(); ?>
                    <br /><br />
                </div>
            </div>
        </div>
    </div>
    <div style="clear:both"></div>
</div>

<script src="<?php echo js_url('admin/plugins/select2/js/select2.min.js?v='.date('YmdHis')); ?>"></script>
<script src="<?php echo js_url('admin/bootstrap-datepicker.min.js?v='.date('YmdHis')); ?>"></script>
<script src="<?php echo js_url('admin/bootbox.min.js?v='.date('YmdHis')); ?>"></script>
<script src="<?php echo js_url('admin/common.js?v='.date('YmsHis')); ?>"></script>
<script src="<?php echo js_url('reg_school.js?v='.date('YmdHis')); ?>"></script>

<?php $this->load->view("tshops/footer"); ?>