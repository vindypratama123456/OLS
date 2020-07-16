<!DOCTYPE html>
<html lang="en">
    <head>
        <title><?php echo isset($page_title) ? $page_title : 'Aplikasi SCM Printing'; ?></title>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <meta http-equiv="X-UA-Compatible" content="IE=edge" />
        <meta name="viewport" content="width=device-width, initial-scale=1" />
        <link rel="icon" href="<?php echo base_url(); ?>assets/img/favicon.ico" type="image/x-icon" />
        <script src="<?php echo assets_url_backmin('js/jquery-3.3.1.js'); ?>" type="text/javascript"></script>
        <script src="<?php echo assets_url_backmin('js/plugins/jquery/jquery.min.js'); ?>" type="text/javascript"></script>
        <script type="text/javascript" src="<?php echo assets_url_backmin('js/plugins/fullcalendar/moment.min.js'); ?>"></script>
        <link href="<?php echo assets_url_backmin('css/bootstrap.min.css'); ?>" rel="stylesheet">
        <link href="<?php echo assets_url_backmin('font-awesome/css/font-awesome.css') ?>" rel="stylesheet">
        <link href="<?php echo assets_url_backmin('css/theme-default.css'); ?>" rel="stylesheet" type="text/css" id="theme">
        <link href="<?php echo assets_url_backmin('css/animate.css'); ?>" rel="stylesheet">
        <link href="<?php echo assets_url_backmin('css/custom.css'); ?>" rel="stylesheet" type="text/css">
        <?php if(isset($script_css)) echo $script_css; ?>
    </head>
    <body>
        <div class="page-container page-navigation-top-fixed">
            <div class="page-sidebar page-sidebar-fixed scroll">
                <ul class="x-navigation" id="main-nav">
                    <li class="xn-logo">
                        <a href="<?php echo base_url(BACKMIN_PATH); ?>">Panel Web</a>
                        <a href="#" class="x-navigation-control"></a>
                    </li>                                                                      
                    <li class="xn-title">Navigasi</li>
                    <?php
                        $level = $this->session->userdata('adm_level');
                        switch ($level) {
                            case 9:
                            case 12:
                                $this->load->view(BACKMIN_PATH.'/menu/scm');
                                break;
                            case 10:
                            case 13:
                                $this->load->view(BACKMIN_PATH.'/menu/gudang');
                                break;
                            // case 11:
                            //     $this->load->view(BACKMIN_PATH.'/menu/ekspeditur');
                            //     break;
                        }
                    ?>
                    <li><a href="#" class="mb-control" data-box="#mb-signout" data-toggle="tooltip" data-placement="right" title="Keluar"><span class="fa fa-sign-out"></span> <span class="xn-text">Keluar</span></a></li>
                </ul>
            </div>
            
            <div class="page-content">
                <ul class="x-navigation x-navigation-horizontal x-navigation-panel">
                    <li class="xn-icon-button">
                        <a href="#" class="x-navigation-minimize" data-toggle="tooltip" data-placement="bottom" title="Menu"><span class="fa fa-dedent"></span></a>
                    </li>
                    <li class="xn-icon-button pull-right last">
                        <a href="#" data-toggle="tooltip" data-placement="bottom" title="Keluar"><span class="fa fa-user"></span></a>
                        <ul class="xn-drop-left animated zoomIn">
                            <li><a href="<?php echo base_url('backmin/profile'); ?>" ><span class="fa fa-user-circle-o"></span> Profile </a></li>
                            <li><a href="#" class="mb-control" data-box="#mb-signout"><span class="fa fa-sign-out"></span> Keluar </a></li>
                        </ul>                        
                    </li> 
                    <li class="xn-icon-button pull-right">
                        <div style="color:#fff;margin:8px 5px 0 0;text-align:right;">
                            <?php 
                                $level = '';
                                switch ($this->session->userdata('adm_level')) {
                                    case 9:
                                    case 12:
                                        $level = ' [Supply Chain]';
                                        break;
                                    case 10:
                                    case 13:
                                        $level = ' ['.$this->session->userdata('nama_gudang').']';
                                        break;
                                }
                                echo $this->session->userdata('adm_uname').'<br>'.$level;
                            ?>
                        </div>
                    </li>
                </ul>
                
                <?php if(isset($content)) echo $content; ?>
                
            </div>            
        </div>
        
        <div class="modal fade" id="confirm-delete" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                        <h4 class="modal-title" id="myModalLabel">Konfirmasi Hapus</h4>
                    </div>
                    <div class="modal-body">
                        <p>Anda akan menghapus data <b><i class="title"></i></b>, ini tidak dapat dikembalikan.</p>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-primary" data-dismiss="modal">Batal</button>
                        <button type="button" class="btn btn-danger btn-ok" id="destroy">Hapus</button>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="message-box animated fadeIn" data-sound="alert" id="mb-signout">
            <div class="mb-container">
                <div class="mb-middle">
                    <div class="mb-title"><span class="fa fa-sign-out"></span> <strong>Akhiri Sesi</strong> anda ?</div>
                    <div class="mb-content">
                        <p>Apakah anda yakin ingin keluar dari aplikasi?</p>                    
                        <p>Tekan <strong>Tidak</strong> jika ingin tetap bekerja. Tekan <strong>Yakin</strong> jika ingin mengakhiri sesi anda.</p>
                    </div>
                    <div class="mb-footer">
                        <div class="pull-right">
                            <a href="<?php echo base_url(); ?>backmin/logout" class="btn btn-danger btn-lg">Yakin</a> &nbsp; 
                            <button class="btn btn-default btn-lg mb-control-close">Tidak</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="modal fade" id="modal_large" tabindex="-1" role="dialog" aria-labelledby="largeModalHead" aria-hidden="true" data-keyboard="false" data-backdrop="static">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                </div>
            </div>
        </div>
        
        <script type="text/javascript">
            var BASE_URI = "https://"+window.location.hostname+"/";
            var BASE_URL = '<?php echo base_url(); ?>';
            var CSRF_NAME = '<?php echo $this->security->get_csrf_token_name(); ?>';
            var CSRF_HASH = '<?php echo $this->security->get_csrf_hash(); ?>';
        </script>
        <script type='text/javascript' src="<?php echo js_url('jquery.csrf.js?v='.date('YmdHis')); ?>"></script>
        <script type="text/javascript" src="<?php echo assets_url_backmin('js'); ?>/bootstrap.min.js"></script>
        <script type="text/javascript" src="<?php echo assets_url_backmin('js'); ?>/plugins/jquery/jquery-ui.min.js"></script>
        <script type="text/javascript" src="<?php echo assets_url_backmin('js'); ?>/js.cookie.js"></script>
        <script type='text/javascript' src='<?php echo assets_url_backmin('js'); ?>/plugins/validate/jquery.validate.min.js'></script>
        <script type='text/javascript' src='<?php echo assets_url('js'); ?>/jquery-validation/localization/messages_id.js'></script>
        <script type="text/javascript" src="<?php echo assets_url_backmin('js'); ?>/plugins.js"></script>
        <script type="text/javascript" src="<?php echo assets_url_backmin('js'); ?>/actions.js"></script>
        <script type="text/javascript" src="<?php echo assets_url_backmin('js'); ?>/plugins/mcustomscrollbar/jquery.mCustomScrollbar.min.js"></script>
        <script type="text/javascript" src="<?php echo assets_url_backmin('js'); ?>/plugins/scrolltotop/scrolltopcontrol.js"></script>
        <script>$(function($){$(document).CsrfAjaxSet();});</script>
        <audio id="audio-alert" src="<?php echo assets_url_backmin('audio'); ?>/alert.mp3" preload="auto"></audio>
        <audio id="audio-fail" src="<?php echo assets_url_backmin('audio'); ?>/fail.mp3" preload="auto"></audio>
        <!--<script type="text/javascript" src="<?php // echo assets_url_backmin("js"); ?>/plugins/blueimp/jquery.blueimp-gallery.min.js"></script>-->
        <?php if(isset($script_js)) echo $script_js; ?>
    </body>
</html>