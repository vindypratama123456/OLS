<?php $this->load->view("tshops/header"); ?>
    <link href="<?php echo CaptchaUrls::LayoutStylesheetUrl(); ?>" type="text/css" rel="stylesheet"/>

    <div class="container main-container headerOffset">
        <div class="row">
            <div class="breadcrumbDiv col-lg-12">
                <ul class="breadcrumb">
                    <li><a href="<?php echo base_url(); ?>">Beranda</a></li>
                    <li class="active"> Login</li>
                </ul>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-12 col-md-12  col-sm-12">
                <h1 class="section-title-inner"><span><i class="fa fa-lock"></i> Masuk</span></h1>


                <?php if ($this->session->flashdata('success')): ?>
                    <div role="alert" class="alert alert-success">
                        <button data-dismiss="alert" class="close" type="button"><span aria-hidden="true">×</span><span
                                    class="sr-only">Close</span></button>
                        <span class="glyphicon glyphicon-ok-circle"></span>&nbsp; <?php echo $this->session->flashdata('success'); ?>
                    </div>
                <?php endif; ?>
                <?php if ($this->session->flashdata('error')): ?>
                    <div role="alert" class="alert alert-danger">
                        <button data-dismiss="alert" class="close" type="button"><span aria-hidden="true">×</span><span
                                    class="sr-only">Close</span></button>
                        <span class="glyphicon glyphicon-info-sign"></span>&nbsp; <?php echo $this->session->flashdata('error'); ?>
                    </div>
                <?php endif; ?>

                <div class="row userInfo">
                    <div class="col-xs-12 col-sm-4">
                        <?php echo form_open('',
                            'class="form-signin" method="post" id="login_form" autocomplete="off"'); ?>
                        <div class="form-group">
                            <label>Email Dapodik</label>
                            <input type="email" class="form-control" placeholder="Masukkan Email Dapodik" name="u_email"
                                   id="u_email" value="<?php echo $this->session->flashdata('userEmail'); ?>" required>
                        </div>
                        <div class="form-group">
                            <label>N P S N</label>
                            <input type="text" class="form-control" placeholder="Masukkan NPSN" name="u_npsn"
                                   id="u_npsn" value="<?php echo $this->session->flashdata('userNPSN'); ?>" required>
                        </div>
                        <div class="form-group">
                            <label>No Telepon</label>
                            <input type="text" class="form-control" placeholder="Masukkan No Telepon Sekolah"
                                   name="u_telp" id="u_telp"
                                   value="<?php echo $this->session->flashdata('userTelp'); ?>" required>
                        </div>
                        <div class="form-group" style="padding-top: 20px;">
                            <?php echo $captchaHtml; ?>
                            <label for="CaptchaCode"></label>
                            <div class="col-xs-12 col-sm-6 col-md-6">
                                <input type="text" class="form-control input-sm" name="CaptchaCode" id="CaptchaCode"
                                       value="" size="50" maxlength="10" style="margin-left: -15px;" required>
                            </div>
                            <div class="clearfix">
                            </div>
                        </div>
                        <div class="form-group">
                            <!-- <label>Belum terdaftar? Silahkan klik <a href="<?php base_url(); ?>register" style="color: #4ec67f;">di sini</a></label> -->
                            <label>Belum terdaftar? Silahkan klik <a onclick="register()" style="color: #4ec67f;">di
                                    sini</a></label>
                        </div>
                        <div class="form-group">
                            <button type="submit" id="submitLogin" class="btn btn-primary btn-lg"><i
                                        class="fa fa-sign-in"></i>&nbsp; Login
                            </button>
                        </div>
                        <?php echo form_close(); ?>
                    </div>
                </div>
            </div>
        </div>
        <div style="clear:both"></div>
    </div>

    <script src="<?php echo js_url('login_school.js?v='.date('YmdHis')); ?>"></script>
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

<?php $this->load->view("tshops/footer"); ?>