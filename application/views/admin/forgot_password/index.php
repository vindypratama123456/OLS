<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Lupa Kata Sandi</title>
    <link rel="shortcut icon" type="image/x-icon" href="<?php echo assets_url('img/favicon.ico'); ?>" />
    <link href="<?php echo css_url('admin/bootstrap.css'); ?>" rel="stylesheet">
    <link href="<?php echo css_url('admin/login.css'); ?>" rel="stylesheet">
</head>
<body>
<div class="container">
    <div class="row">
        <div class="col-sm-6 col-md-4 col-md-offset-4">
            <h1 class="text-center login-title">Lupa Kata Sandi</h1>
            <div id="msg-area"></div>
            <div class="account-wall">
                <img class="profile-img" src="<?php echo assets_url('img/logo_gmi.png'); ?>" title="Gramedia.com" alt="Gramedia.com" />
                <div id="forgotpass-message"></div>
                <?php echo form_open('', 'class="form-signin" id="reset_form" autocomplete="off"'); ?>
                    <p>Masukkan alamat email anda yang valid</p>
                    <div class="form-group">
                        <input type="email" class="form-control" placeholder="Email" name="email" id="email" required autofocus>
                    </div>
                    <button class="btn btn-lg btn-success btn-block" type="submit">K i r i m</button>
                <?php echo form_close(); ?>
                <p class="text-center" style="margin-top:30px;">
                    <a href="<?php echo base_url('backoffice/login'); ?>">Halaman Login</a>
                </p>
            </div>
        </div>
    </div>
</div>
<script type="text/javascript">
    var BASE_URL = '<?php echo base_url(); ?>';
    var CSRF_NAME = '<?php echo $this->security->get_csrf_token_name(); ?>';
    var CSRF_HASH = '<?php echo $this->security->get_csrf_hash(); ?>';
</script>
<script src="<?php echo js_url('admin/jquery.js'); ?>"></script>
<script src="<?php echo js_url('admin/bootstrap.min.js'); ?>"></script>
<script src="<?php echo js_url('jquery-validation/jquery.validate.min.js'); ?>"></script>
<script src="<?php echo js_url('jquery-validation/localization/messages_id.js'); ?>"></script>
<script src="<?php echo js_url('forgot_password.js'); ?>"></script>
</body>
</html>