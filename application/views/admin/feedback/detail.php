<div class="container-fluid">

    <!-- Page Heading -->
    <div class="row">
        <div class="col-lg-12">
            <h1 class="page-header">
                Testimoni
            </h1>
            <ol class="breadcrumb">
                <li>
                    <a href="<?php echo base_url() . ADMIN_PATH; ?>">Beranda</a>
                </li>
                <li class="active">
                    <a href="<?php echo base_url() . ADMIN_PATH; ?>/category">Testimoni</a>
                </li>
                <li class="active">
                    Detil
                </li>
            </ol>
        </div>
    </div>
    <!-- /.row -->

    <div class="row">
        <div class="col-lg-12">

            <?php echo form_open('', 'id="feedback_form" autocomplete="off"'); ?>

                <input type="hidden" name="id_order" value="<?=$detil['id_order']?>" />

                <div class="form-group">
                    <div class="col-md-8">
                        <h3>Kode Pesanan: <a href="<?php echo base_url().ADMIN_PATH.'/orders/detail/'.$detil['id_order']; ?>" target="_blank">#<?php echo $detil['reference']; ?></a></h3>
                    </div>
                </div>

                <div class="form-group">
                    <div class="col-md-12">
                        <label>Nama Sekolah</label>
                        <input type="text" class="form-control" value="<?=$detil['school_name'].' / '.$detil['provinsi'].' / '.$detil['kabupaten']?>" disabled>
                    </div>
                </div>

                <div class="form-group">
                    <div class="col-md-12">
                        <br /><label>Komentar/Testimoni</label>
                        <textarea class="form-control" rows="5" disabled style="resize:none;"><?=$detil['comment']?></textarea>
                    </div>
                </div>

                <div class="form-group">
                    <div class="col-md-12">
                        <br /><label>Rating/Penilaian</label><br />
                        <label class="radio-inline">
                            <input type="radio"<?php if($detil['rating']==1) echo ' checked'; ?> disabled>Sangat Mengecewakan
                        </label>
                        <label class="radio-inline">
                            <input type="radio"<?php if($detil['rating']==2) echo ' checked'; ?> disabled>Mengecewakan
                        </label>
                        <label class="radio-inline">
                            <input type="radio"<?php if($detil['rating']==3) echo ' checked'; ?> disabled>Cukup Memuaskan
                        </label>
                        <label class="radio-inline">
                            <input type="radio"<?php if($detil['rating']==4) echo ' checked'; ?> disabled>Memuaskan
                        </label>
                        <label class="radio-inline">
                            <input type="radio"<?php if($detil['rating']==5) echo ' checked'; ?> disabled>Sangat Memuaskan
                        </label>
                    </div>
                </div>

                <div class="form-group">
                    <div class="col-md-12">
                        <br /><label>Tanggal</label>
                        <input type="text" class="form-control" value="<?=$detil['created_at']?>" disabled>
                    </div>
                </div>

                <div class="form-group">
                    <div class="col-md-12">
                        <br /><label>Status</label><br />
                        <label class="radio-inline">
                            <input name="enable" value="1" type="radio"<?php if($detil['enable']==1) echo ' checked'; ?>>Aktif
                        </label>
                        <label class="radio-inline">
                            <input name="enable" value="0" type="radio"<?php if($detil['enable']==0) echo ' checked'; ?>>Nonaktif
                        </label>
                    </div>
                </div>

                <div class="form-group">
                    <div class="col-md-12"><br />
                        <button type="submit" class="btn btn-success pull-left">Simpan</button>
                        <a href="<?php echo base_url().ADMIN_PATH; ?>/feedback" class="btn btn-primary pull-right">Kembali</a>
                    </div>
                </div>

            <?php echo form_close(); ?>

        </div>
    </div>
    <!-- /.row -->

</div>
<!-- /.container-fluid -->