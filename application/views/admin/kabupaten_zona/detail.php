<div class="container-fluid">

    <!-- Page Heading -->
    <div class="row">
        <div class="col-lg-12">
            <h1 class="page-header">
                Profil Pengguna
            </h1>
            <ol class="breadcrumb">
                <li>
                    <a href="<?php echo base_url() . BACKMIN_PATH; ?>">Beranda</a>
                </li>
                <li class="active">
                    Profil
                </li>
            </ol>
        </div>
    </div>
    <!-- /.row -->

    <div class="row">
        <div class="col-lg-12">

            <?php 
                if($this->session->flashdata('msg_success')) {
                    echo notif('success',$this->session->flashdata('msg_success'));
                }
                if($this->session->flashdata('msg_failed')) {
                    echo notif('danger',$this->session->flashdata('msg_failed'));
                }
            ?>

            <?php echo form_open('', 'data-action="' . base_url() . BACKMIN_PATH . '/profile/editPost" id="profile_form" autocomplete="off"'); ?>

                <div class="form-group">
                    <div class="col-md-8">
                        <label>Nama Pengguna</label>
                        <input type="text" class="form-control" name="name" id="name" value="<?=$detil['name']?>">
                    </div>
                </div>

                <div class="form-group">
                    <div class="col-md-8">
                        <label>Alamat Email</label>
                        <input type="email" class="form-control" name="email" id="email" value="<?=$detil['email']?>" readonly>
                    </div>
                </div>
                <br><br><br><br><br><br><br><br>
                <div class="form-group">
                    <div class="col-md-8">
                        <b>Catatan : </b>Kosongkan jika tidak ingin mengubah kata sandi<br>
                        <label>Kata Sandi</label>
                        <input type="password" class="form-control" name="password" id="password">
                    </div>
                </div>

                <div class="form-group">
                    <div class="col-md-8">
                        <label>Kata Sandi</label>
                        <input type="password" class="form-control" name="password_konfirmasi" id="password_konfirmasi">
                    </div>
                </div>

                <div class="form-group">
                    <div class="col-md-12"><br />
                        <button type="submit" class="btn btn-success pull-left">Simpan</button>
                    </div>
                </div>

            <?php echo form_close(); ?>

        </div>
    </div>
    <!-- /.row -->

</div>
<!-- /.container-fluid -->