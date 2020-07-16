<div class="container-fluid">
    <!-- Page Heading -->
    <div class="row">
        <div class="col-lg-12">
            <h1 class="page-header">Kontrak</h1>
            <ol class="breadcrumb">
                <li><a href="<?php echo base_url() . ADMIN_PATH; ?>">Beranda</a></li>
                <li><a href="<?php echo base_url() . ADMIN_PATH; ?>/Kontrak">Kontrak</a></li>
                <li class="active">Detil</li>
            </ol>
        </div>
    </div>
    <!-- /.row -->
    <div class="row">
        <div class="col-lg-12">
            <?php echo form_open('', 'data-action="' . base_url() . ADMIN_PATH . '/kontrak/updatePost" id="kontrak_form" autocomplete="off"'); ?>
                <input type="hidden" name="mikon_employee_id" value="<?=$detil['id_employee']?>">
                <div class="form-group">
                    <div class="col-md-6">
                        <label>Kode</label>
                        <input type="text" class="form-control" name="code" id="code" value="<?=$detil['code']?>" disabled>
                    </div>
                </div>
                <div class="form-group">
                    <div class="col-md-8">
                        <label>Email</label>
                        <input type="email" class="form-control" name="email" value="<?=$detil['email']?>" readonly >
                    </div>
                </div>
                <div class="form-group">
                    <div class="col-md-8">
                        <label>Nama Lengkap</label>
                        <input type="text" class="form-control" name="name" id="name" value="<?=$detil['name']?>" readonly>
                    </div>
                </div>
                <div class="form-group">
                    <div class="col-sm-8">
                        <label>Tanggal</label>
                        <input type="text" class="form-control datepicker" name="mikon_tanggal" id="mikon_tanggal" value="<?=$detil['mikon_tanggal']?>">
                    </div>
                </div>
                <div class="form-group">
                    <div class="col-sm-8">
                        <label>Periode</label>
                        <input type="text" class="form-control" name="mikon_periode" id="mikon_periode" value="<?=$detil['mikon_periode']?>">
                    </div>
                </div>
                <div class="form-group">
                    <div class="col-md-12">
                        <hr>
                    </div>
                </div>

                <?php if ($detil['mikon_file']) { ?>
                <div class="form-group">
                    <div class="col-md-8">
                        <label>Document</label><br>
                        <img src="<?php echo base_url().'uploads/kontrak/'.$detil['mikon_file']; ?>" class="img-thumbnail img-responsive" alt="<?php echo $detil['name']; ?>" style="max-height:250px;">
                        <input type="hidden" name="mikon_file_temp" value="<?= $detil['mikon_file']; ?>">
                        <input type="file" name="mikon_file">
                    </div>
                </div>
                <?php }else{ ?>
                <div class="form-group">
                    <div class="col-md-8">
                        <label>Document</label><br>
                        <img src="<?php echo base_url().'uploads/kontrak/no_image.png'; ?>" class="img-thumbnail img-responsive" alt="<?php echo $detil['name']; ?>" style="max-height:250px;">
                        <input type="file" name="mikon_file">
                    </div>
                </div>
                <?php } ?>

                <div class="form-group">
                    <div class="col-md-12"><br />
                        <?php if ($adm_level != 6) { ?>
                        <button type="submit" class="btn btn-success pull-left">Simpan</button>
                        <?php } ?>
                        <a href="<?php echo base_url().ADMIN_PATH; ?>/kontrak" class="btn btn-primary pull-<?php echo ($adm_level == 6) ? 'left' : 'right'; ?>">Kembali</a>
                    </div>
                </div>
            <?php echo form_close(); ?>
        </div>
    </div>
    <!-- /.row -->
</div>
<!-- /.container-fluid -->

<script type="text/javascript">
    $(document).ready(function(){
        $("#mikon_tanggal").datepicker({
            format: "dd-mm-yyyy",
            autoclose: true,
            todayHighlight: true,
            toggleActive: true
        }).on("changeDate", function(e) {
            $("#mikon_tanggal label.error").hide();
            $("#mikon_tanggal .error").removeClass("error");
        });
    });
</script>