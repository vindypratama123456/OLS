<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title><?php echo  (isset($page_title)) ? $page_title : 'Halaman Administrasi Buku Sekolah'; ?></title>

        <link rel="shortcut icon" type="image/x-icon" href="<?php echo assets_url('img/favicon.ico'); ?>" />
        <!-- Bootstrap Core CSS -->
        <link href="<?php echo css_url('admin/bootstrap.css?v='.date('YmdHis')); ?>" rel="stylesheet">
        <!-- Custom CSS -->
        <link href="<?php echo css_url('admin/sb-admin.css?v='.date('YmdHis')); ?>" rel="stylesheet">
        <!-- Custom Fonts -->
        <link href="<?php echo assets_url('font-awesome/css/font-awesome.min.css?v='.date('YmdHis')); ?>" rel="stylesheet" type="text/css">
        <link href="<?php echo js_url('admin/plugins/dataTables-1.10.11/media/css/jquery.dataTables.min.css?v='.date('YmdHis'));?>" rel="stylesheet">
        <link href="<?php echo js_url('admin/plugins/dataTables-1.10.11/media/css/dataTables.bootstrap.min.css?v='.date('YmdHis'));?>" rel="stylesheet">
        <link href="<?php echo js_url('admin/plugins/dataTables-1.10.11/media/css/dataTables.material.min.css?v='.date('YmdHis'));?>" rel="stylesheet">
        <link href="<?php echo js_url('admin/plugins/dataTables-1.10.11/media/css/dataTables.tableTools.css?v='.date('YmdHis'));?>" rel="stylesheet">
        <link href="<?php echo js_url('admin/plugins/dataTables-1.10.11/media/css/buttons.dataTables.min.css?v='.date('YmdHis'));?>" rel="stylesheet">
        <link href="<?php echo css_url('admin/bootstrap-datepicker3.min.css?v='.date('YmdHis'));?>" rel="stylesheet">
        <link href="<?php echo assets_url('js/admin/plugins/select2/css/select2.min.css'); ?>" rel="stylesheet">

        <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
        <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
        <!--[if lt IE 9]>
            <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
            <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
        <![endif]-->
        <?php if(isset($script_css)) echo $script_css; ?>
        <script type="text/javascript">
            var BASE_URI = '<?php echo base_url(); ?>';
            var BASE_URL = '<?php echo base_url() . ADMIN_PATH; ?>/';
            var CSRF_NAME = '<?php echo $this->security->get_csrf_token_name(); ?>';
            var CSRF_HASH = '<?php echo $this->security->get_csrf_hash(); ?>';
        </script>
        <!-- jQuery -->
        <script src="<?php echo js_url('admin/jquery.js?v='.date('YmdHis')); ?>"></script>
    </head>
    <body>
        <div id="myloader" style="display:none;"></div>
        <div id="wrapper">
            <!-- Navigation -->
            <nav class="navbar navbar-inverse navbar-fixed-top" role="navigation">
                <?php
                    $this->load->view('admin/header');
                    $this->load->view('admin/menu');
                ?>
            </nav>
            <div id="page-wrapper">
                <?php echo $content; ?>
            </div>
            <!-- /#page-wrapper -->
        </div>
        <!-- /#wrapper -->
        <!-- Bootstrap Core JavaScript -->
        <script src="<?php echo js_url('jquery.csrf.js?v='.date('YmdHis')); ?>"></script>
        <script src="<?php echo js_url('admin/bootstrap.min.js?v='.date('YmdHis')); ?>"></script>
        <script src="<?php echo base_url('assets/js/jquery-ui-1.12.0/jquery-ui.min.js?v='.date('YmdHis')); ?>"></script>
        <script src="<?php echo js_url('admin/plugins/dataTables-1.10.11/media/js/jquery.dataTables.min.js?v='.date('YmdHis'));?>"></script>
        <script src="<?php echo js_url('admin/plugins/dataTables-1.10.11/media/js/dataTables.bootstrap.js?v='.date('YmdHis'));?>"></script>
        <script src="<?php echo js_url('admin/plugins/dataTables-1.10.11/media/js/dataTables.buttons.min.js?v='.date('YmdHis'));?>"></script>
        <script src="<?php echo js_url('admin/plugins/dataTables-1.10.11/media/js/jszip.min.js?v='.date('YmdHis'));?>"></script>
        <script src="<?php echo js_url('admin/plugins/dataTables-1.10.11/media/js/vfs_fonts.js?v='.date('YmdHis'));?>"></script>
        <script src="<?php echo js_url('admin/plugins/dataTables-1.10.11/media/js/buttons.html5.min.js?v='.date('YmdHis'));?>"></script>
        <script src="<?php echo js_url('admin/plugins/select2/js/select2.min.js?v='.date('YmdHis')); ?>"></script>
        <script src="<?php echo js_url('admin/bootstrap-datepicker.min.js?v='.date('YmdHis')); ?>"></script>
        <script src="<?php echo js_url('admin/bootbox.min.js?v='.date('YmdHis')); ?>"></script>
        <script>$(function($){$(document).CsrfAjaxSet();});</script>
        <?php if(isset($script_js)) echo $script_js; ?>
    </body>
</html>