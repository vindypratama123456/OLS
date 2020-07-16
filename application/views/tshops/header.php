<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="shortcut icon" type="image/x-icon" href="<?php echo assets_url('img/favicon.ico'); ?>" />
    <title><?php echo $title; ?></title>
    <link href="<?php echo assets_url_fo().'bootstrap/css/bootstrap.css?v='.date('YmdHis');?>" rel="stylesheet">
    <link href="<?php echo assets_url_fo().'css/style.css?v='.date('YmdHis');?>" rel="stylesheet">
    <link href="<?php echo assets_url('js/admin/plugins/select2/css/select2.min.css'); ?>" rel="stylesheet">
    <script src="<?php echo assets_url_fo()?>js/jquery.min.js?v=<?php echo date('YmdHis'); ?>"></script>
    <script src="<?php echo assets_url_fo(); ?>bootstrap/js/bootstrap.min.js?v=<?php echo date('YmdHis'); ?>"></script>
    <script src="<?php echo js_url('jquery-validation/jquery.validate.min.js?v='.date('YmdHis')); ?>"></script>
    <script src="<?php echo js_url('jquery-validation/localization/messages_id.js?v='.date('YmdHis')); ?>"></script>
    <script>
        var BASE_URI = '<?php echo base_url(); ?>';
        var BASE_URL = '<?php echo base_url(); ?>';
        var CSRF_NAME = '<?php echo $this->security->get_csrf_token_name(); ?>';
        var CSRF_HASH = '<?php echo $this->security->get_csrf_hash(); ?>';
        paceOptions = { elements: true };
    </script>
</head>
<body>
    <div id="myloader" style="display:none;"></div>
    <div class="navbar navbar-tshop navbar-fixed-top" role="navigation">
        <!-- Fixed navbar -->
        <nav class="navbar navbar-fixed-top navbar-tshop">
            <div class="container">
                <div class="row">
                    <div class="col-lg-6 col-md-6 col-sm-4 col-xs-4">
                        <div class="pull-left">
                            <ul class="userMenu">
                                <li><b><a href="callto:+02144837547" /*href="<?php echo base_url(); ?>halaman/hubungi-kami"*/ title="Butuh Bantuan? Hubungi Kami" style="color:#000;font-size:14px;"><span class="hidden-xs">Hubungi Kami</span><i class="glyphicon glyphicon-info-sign hide visible-xs "></i></a></b></li>
                                <li class="phone-number"><b><a href="callto:+02144837547" title="Hubungi Kami" style="font-size:14px;"><span><i class="glyphicon glyphicon-phone-alt "></i></span><span class="hidden-xs" style="color:#ff0000;margin-left:5px">(021) 5481487</span></a></b></li>
                            </ul>
                        </div>
                    </div>
                    <div class="col-lg-6 col-md-6 col-sm-8 col-xs-8">
                        <div class="pull-right">
                            <ul class="userMenu">
                                <?php if (!empty($this->session->userdata('id_customer'))) { ?>
                                <li><a href="<?php echo base_url('akunsaya/profil'); ?>"><?php echo $this->session->userdata('school_name'); ?></a></li>
                                <li>
                                    <a href="<?php echo base_url(); ?>akunsaya/logout" title="Keluar" style="float:right;font-size:16px;"><span class="hidden-xs">[ Keluar ]</span><i class="glyphicon glyphicon-log-out hide visible-xs "></i></a>
                                </li>
                                <?php } else { ?>
                                <li>
                                    <strong><a href="<?php echo base_url(); ?>akunsaya/loginLocal" title="Tetap Login" style="float:left;font-size:14px;">Tetap Login</a></strong>
                                    <strong><a href="http://data.dikdasmen.kemdikbud.go.id/sso/auth/?response_type=code&amp;client_id=bkk13ad&amp;state=100100&amp;redirect_uri=<?php echo base_url() ?>akunsaya/verify" title="Login" style="float:right;font-size:16px;">[ Login ]</a></strong>
                                </li>
                                <?php /*
                                <li>
                                    <strong><a href="<?php echo base_url(); ?>akunsaya/register" title="Login" style="float:right;font-size:16px;">[ Register ]</a></strong>
                                </li>
                                */ ?>
                                <?php } ?>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
            <div class="container">
                <div class="navbar-header">
                    <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar" aria-expanded="false" aria-controls="navbar">
                        <span class="sr-only">Toggle navigation</span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                    </button>
                    <a class="navbar-brand" href="<?php echo base_url(); ?>"><img src="<?php echo assets_url_fo(); ?>img/logo.png" alt="Gramedia"></a>
                </div>
                <div id="navbar" class="navbar-collapse collapse">
                    <ul class="nav navbar-nav">
                        <li><a href="<?php echo base_url(); ?>">Beranda</a></li>
                        <li><a href="<?php echo base_url(); ?>kategori/buku/3-buku-teks-2013">Katalog</a></li>
                        <li><a href="<?php echo base_url(); ?>testimoni">Testimoni</a></li>
                        <li><a href="<?php echo base_url(); ?>halaman/tatacarapemesanan">Petunjuk Penggunaan</a></li>
                        <li><a href="<?php echo base_url(); ?>halaman/tata-cara-pembayaran-bri-virtual-account">Tata Cara Pembayaran</a></li>
                        <?php if ($this->session->userdata('id_customer')) { ?>
                        <li><a href="<?php echo base_url(); ?>pesanan">Pesanan Saya</a></li>
                        <?php } ?>
                    </ul>
                    <ul class="nav navbar-nav navbar-right">
                        <li>
                            <a href="<?php echo base_url(); ?>pesanan/formpesanan"><i class="fa fa-shopping-cart"></i>&nbsp;&nbsp;&nbsp;Isi Form Pesanan</a>
                        </li>
                    </ul>
                </div>
            </div>
        </nav>
    </div>
