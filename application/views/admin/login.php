<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Halaman Login</title>
        <link rel="shortcut icon" type="image/x-icon" href="<?php echo assets_url('img/favicon.ico'); ?>" />
        <link href="<?php echo css_url('admin/bootstrap.css?v='.date('YmdHis')); ?>" rel="stylesheet">
        <link href="<?php echo css_url('admin/login.css?v='.date('YmdHis')); ?>" rel="stylesheet">
    </head>
    <body>
        <div id="myloader" style="display:none;"></div>
        <div class="container">
            <div class="row">
                <div class="col-sm-6 col-md-4 col-md-offset-4">
                    <h1 class="text-center login-title">Login Halaman Administrasi</h1>
                    <div class="account-wall">
                        <img class="profile-img" src="<?php echo assets_url('img/logo_gmi.png'); ?>" title="Gramedia.com" alt="Gramedia.com" />
                        <?php echo form_open('', 'class="form-signin" id="login_form" autocomplete="off"'); ?>
                            <div id="alert-area"></div>
                            <div class="form-group">
                                <input type="email" class="form-control" placeholder="Email" name="u_name" id="u_name" required autofocus>
                            </div>
                            <div class="form-group">
                                <input type="password" class="form-control" placeholder="Kata Sandi" name="u_pass" id="u_pass" required>
                            </div>
                            <button class="btn btn-lg btn-success btn-block" type="submit" id="btn-login">Masuk</button>
                        <?php echo form_close(); ?>
                        <p class="text-center" style="margin-top:30px;">
                            <a href="<?php echo base_url('others/forgotpassword'); ?>">Lupa kata sandi?</a>
                        </p>
                    </div>
                </div>
            </div>
        </div>
        <script type="text/javascript">
            var BASE_URI = '<?php echo base_url(); ?>';
            var BASE_URL = '<?php echo base_url() . ADMIN_PATH; ?>/';
            var CSRF_NAME = '<?php echo $this->security->get_csrf_token_name(); ?>';
            var CSRF_HASH = '<?php echo $this->security->get_csrf_hash(); ?>';
        </script>
        <script src="<?php echo js_url('admin/jquery.js?v='.date('YmdHis')); ?>"></script>
        <script src="<?php echo js_url('admin/bootstrap.min.js?v='.date('YmdHis')); ?>"></script>
        <script src="<?php echo js_url('jquery-validation/jquery.validate.min.js?v='.date('YmdHis')); ?>"></script>
        <script src="<?php echo js_url('jquery-validation/localization/messages_id.js?v='.date('YmdHis'));?>"></script>
        <script src="<?php echo js_url('admin/bootbox.min.js?v='.date('YmsHis')); ?>"></script>
        <script src="<?php echo js_url('admin/plugins/select2/js/select2.min.js?v='.date('YmdHis')); ?>"></script>
        <script src="<?php echo js_url('admin/bootstrap-datepicker.min.js?v='.date('YmdHis')); ?>"></script>
        <script src="<?php echo js_url('admin/common.js?v='.date('YmdHis')); ?>"></script>
        <script src="<?php echo js_url('admin/app/login.js?v='.date('YmdHis')); ?>"></script>
    </body>
</html>