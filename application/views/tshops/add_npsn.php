<?php $this->load->view("tshops/header"); ?>

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
        <div class="col-lg-12 col-md-12 col-sm-12">
            <h1 class="section-title-inner"><span><i class="fa fa-lock"></i> Lengkapi NPSN</span></h1>
            <?php echo (isset($error)) ? '<br />'.$error : ''; ?>
            <?php echo $this->session->flashdata('message') ?>
            <div class="row userInfo">
                <div class="col-xs-12 col-sm-4">
                    <?php echo form_open(base_url() . 'akunsaya/verifyNPSN', 'class="form-signin" method="post" id="login_form" autocomplete="off"'); ?>
                        <div class="form-group">
                            <label>N P S N</label>
                            <input type="text" class="form-control" placeholder="Masukkan NPSN Sekolah" name="u_npsn" id="u_npsn">
                        </div>
                        <?php foreach ($tempData as $field => $value) { ?>
                            <input type="hidden" name="tempData[<?php echo $field; ?>]" value="<?php echo $value; ?>">
                        <?php } ?>
                       <button type="submit" class="btn btn-primary btn-lg"><i class="fa fa-sign-in"></i> Lanjut Login</button>
                    <?php echo form_close(); ?>
                </div>
            </div>
        </div>
    </div>
    <div style="clear:both"></div>
</div>
<script>
    var BASE_URL = '<?php echo base_url(); ?>';

    $().ready(function() {
        $("#login_form").validate({
            rules: {
                u_npsn: {
                    required: true,
                    minlength: 4,
                    number: true
                },
            }
        });
    });
</script>

<?php $this->load->view("tshops/footer"); ?>