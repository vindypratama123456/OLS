<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta content="IE=edge" http-equiv="X-UA-Compatible">
    <meta content="width=device-width, initial-scale=1" name="viewport">
    <title><?php echo $page_title; ?></title>
    <link href="<?php echo assets_url('img/favicon.ico'); ?>" rel="shortcut icon" type="image/x-icon"/>
    <link href="<?php echo css_url('admin/bootstrap.css'); ?>" rel="stylesheet">
    <link href="<?php echo assets_url('font-awesome/css/font-awesome.min.css'); ?>" rel="stylesheet" type="text/css">
    <link href="<?php echo assets_url('js/jquery-ui-1.12.0/jquery-ui.min.css'); ?>" rel="stylesheet">
    <link href="<?php echo assets_url('js/jquery-ui-1.12.0/jquery-ui.structure.min.css'); ?>" rel="stylesheet">
    <link href="<?php echo assets_url('js/jquery-ui-1.12.0/jquery-ui.theme.min.css'); ?>" rel="stylesheet">
    <link href="<?php echo assets_url('js/admin/plugins/select2/css/select2.min.css'); ?>" rel="stylesheet">
    <link href="<?php echo CaptchaUrls::LayoutStylesheetUrl(); ?>" type="text/css" rel="stylesheet"/>
    <style type="text/css">
        #myloader {
            position: fixed;
            left: 0px;
            top: 0px;
            width: 100%;
            height: 100%;
            z-index: 9999;
            background: url('../../../assets/img/loader.gif') center no-repeat #fff;
            filter: alpha(opacity=90);
            zoom: 1;
            opacity: 0.9;
        }
    </style>
</head>
<body background="<?php echo assets_url('img/pattern/greyzz.png'); ?>">
<div id="myloader" style="display:none;"></div>
<div id="wrapper" style="padding-bottom: 100px;">
    <div id="page-wrapper">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <img height="200" src="<?php echo assets_url('img/logo_gmi.png') ?>" alt="Logo"/>
                </div>
            </div>
            <div class="row">
                <div class="col-lg-12">
                    <h1 class="page-header"><?php echo $page_title; ?></h1>
                </div>
            </div>
            <div class="row hidden" id="content_confirm">
                <div class="col-lg-12 text-center">
                    <br><br>
                    <h3 style="line-height: 30pt;">
                        Terima kasih telah melakukan registrasi<br>
                        Akun anda akan segera kami verifkasi dan konfirmasi<br><br>
                        Silahkan klik tautan dibawah ini untuk kembali ke halaman registrasi:<br>
                        <b>[ <a href="<?php echo base_url(); ?>ecregistrasi">Registrasi EC</a> ]</b>
                    </h3>
                </div>
            </div>
            <div class="row" id="content_form">
                <?php if ($this->session->flashdata('alert_error') !== null) { ?>
                    <div class="col-lg-12">
                        <div class="alert alert-danger">
                            <h3 style="margin-left: 23px;">
                                Terdapat <?php echo $this->session->flashdata('error_count') ?> isian yang salah!</h3>
                            <ol>
                                <?php foreach ($this->session->flashdata('alert_error') as $data) { ?>
                                    <li style="padding:2px 0;"><?php echo $data ?></li>
                                <?php } ?>
                            </ol>
                        </div>
                    </div>
                <?php } ?>
                <div class="col-lg-12">
                    <?php echo form_open('',
                        'class="form form-horizontal" id="form_registrasi_ec" role="form" autocomplete="off"'); ?>
                    <div class="form-group">
                        <label class="col-sm-2 control-label">Email</label>
                        <div class="col-sm-7">
                            <input class="form-control" id="email" name="email"
                                   placeholder="Masukkan alamat email" type="email">
                        </div>
                        <div class="clearfix"></div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-2 control-label">Kata Sandi</label>
                        <div class="col-sm-7">
                            <input class="form-control" id="password" name="password"
                                   placeholder="Masukkan kata sandi" type="password">
                        </div>
                        <div class="clearfix"></div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-2 control-label">Konfirmasi Kata Sandi</label>
                        <div class="col-sm-7">
                            <input class="form-control" id="confirm_password" name="confirm_password"
                                   placeholder="Masukkan konfirmasi kata sandi" type="password">
                        </div>
                        <div class="clearfix"></div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-2 control-label">Nama Lengkap</label>
                        <div class="col-sm-7">
                            <input class="form-control" id="name" name="name"
                                   placeholder="Masukkan nama lengkap" type="text">
                        </div>
                        <div class="clearfix"></div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-2 control-label">No. Telpon/HP</label>
                        <div class="col-sm-7">
                            <input class="form-control" id="telp" name="telp"
                                   placeholder="Masukkan nomor telpon/hp" type="text">
                        </div>
                        <div class="clearfix"></div>
                    </div>

                    <!--
                        /**
                         * Kata kunci : korwil
                         * Vindy 2019-06-27
                         * Korwil / Kode Wilayah
                         * Mungkin dibutuhkan untuk kedepannya
                         * Awal
                         * $data['wilayah'] = $this->mod_general->getAll("wilayah");
                         * Akhir
                         */
                    <div class="form-group">
                        <label class="col-sm-2 control-label">Wilayah</label>
                        <div class="col-sm-7">
                            <select id="wilayah" name="wilayah" class="form-control">
                                <option value=''>- Silahkan Pilih Wilayah -</option>
                                <?php foreach ($wilayah as $data) { ?>
                                    <option value="<?php echo $data->wil_id; ?>"> <?php echo $data->wil_id." ".$data->wil_name; ?> </option>
                                <?php } ?>
                            </select>
                        </div>
                        <div class="clearfix"></div>
                    </div>
                    -->
                    <div class="form-group">
                        <label class="col-sm-2 control-label">Kabupaten</label>
                        <div class="col-sm-7">
                            <select id="kabupaten" name="kabupaten[]" class="form-control" multiple="multiple">
                                <option value='' selected disabled hidden>- Silahkan Pilih Kabupaten -</option>
                                <?php foreach ($kabupaten as $data) { ?>
                                    <option value="<?php echo $data->kabupaten; ?>"> <?php echo $data->kabupaten; ?> </option>
                                <?php } ?>
                            </select>
                        </div>
                        <div class="clearfix"></div>
                    </div> 
                    <div class="form-group">
                        <label class="col-sm-2 control-label">Mitra</label>
                        <div class="col-sm-7">
                            <select id="mitra" name="mitra[]" class="form-control" multiple="multiple" placeholder="Silahkan Pilih Data Mitra">
                                <option value=''  selected disabled hidden>- Silahkan Pilih Data Mitra -</option>
                                <?php foreach ($mitra as $data) { ?>
                                    <option value="<?php echo $data->id_employee; ?>"> <?php echo $data->code . " " . $data->name . " ( " . $data->email . " ) "; ?> </option>
                                <?php } ?>
                            </select>
                        </div>
                        <div class="clearfix"></div>
                    </div>
                    <!-- <div class="form-group">
                        <label class="col-sm-2 control-label">Unggah Foto Diri</label>
                        <div class="col-sm-7">
                            <input accept="image/*" class="reg_photo" id="reg_photo" name="reg_photo" type="file">
                        </div>
                    </div> -->
                    <hr/>
                    <div class="form-group">
                        <div class="col-sm-offset-2 col-sm-10">
                            <?php echo $captchaHtml; ?>
                            <label for="CaptchaCode"></label>
                            <div class="col-xs-8 col-sm-4 col-md-4">
                                <input type="text" class="form-control" name="CaptchaCode" id="CaptchaCode" value=""
                                       size="50" maxlength="10" style="margin-left:-15px; margin-top:5px;clear:bottom;">
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="col-sm-offset-2 col-sm-10">
                            <button class="btn btn-info btn-large" id="submit" type="submit">Proses Registrasi</button>
                        </div>
                    </div>
                    <?php echo form_close(); ?>
                </div>
            </div>
        </div>
    </div>
</div>
<script type="text/javascript">
    var BASE_URI = '<?php echo base_url(); ?>';
    var BASE_URL = '<?php echo base_url(); ?>';
    var CSRF_NAME = '<?php echo $this->security->get_csrf_token_name(); ?>';
    var CSRF_HASH = '<?php echo $this->security->get_csrf_hash(); ?>';
</script>
<script src="<?php echo js_url('admin/jquery.js?v='.date('YmsHis')); ?>"></script>
<script src="<?php echo js_url('admin/bootstrap.min.js?v='.date('YmsHis')); ?>"></script>
<script src="<?php echo js_url('jquery-ui-1.12.0/jquery-ui.min.js?v='.date('YmsHis')); ?>"></script>
<script src="<?php echo js_url('jquery-validation/jquery.validate.min.js?v='.date('YmsHis')); ?>"></script>
<script src="<?php echo js_url('jquery-validation/localization/messages_id.js?v='.date('YmsHis')); ?>"></script>
<script src="<?php echo js_url('admin/plugins/select2/js/select2.min.js?v='.date('YmsHis')); ?>"></script>
<script src="<?php echo js_url('admin/bootbox.min.js?v='.date('YmsHis')); ?>"></script>
<script src="<?php echo js_url('admin/bootstrap-datepicker.min.js?v='.date('YmdHis')); ?>"></script>
<script src="<?php echo js_url('admin/moment.js?v='.date('YmsHis')); ?>"></script>
<script src="<?php echo js_url('admin/common.js?v='.date('YmsHis')); ?>"></script>
<script src="<?php echo js_url('ec.js?v='.date('YmsHis')); ?>"></script>
<?php if (getenv('CI_ENV') == 'production') { ?>
    <script>
        (function (i, s, o, g, r, a, m) {
            i['GoogleAnalyticsObject'] = r;
            i[r] = i[r] || function () {
                (i[r].q = i[r].q || []).push(arguments)
            }, i[r].l = 1 * new Date();
            a = s.createElement(o),
                m = s.getElementsByTagName(o)[0];
            a.async = 1;
            a.src = g;
            m.parentNode.insertBefore(a, m)
        })(window, document, 'script', 'https://www.google-analytics.com/analytics.js', 'ga');
        //ga('create', 'UA-80245043-1', 'auto');
        ga('create', 'UA-133449783-1', 'auto');
        ga('send', 'pageview');
    </script>
<?php } ?>
</body>
</html>
