<div class="container-fluid">
    <div class="row">
        <div class="col-lg-12">
            <h1 class="page-header">Profil Pengguna</h1>
            <ol class="breadcrumb">
                <li><a href="<?php echo base_url() . ADMIN_PATH; ?>">Beranda</a></li>
                <li class="active">Profil</li>
            </ol>
        </div>
    </div>
    <div class="row">
        <div class="col-lg-12">
            <?php
            if ($this->session->flashdata('msg_success')) {
                echo notif('success', $this->session->flashdata('msg_success'));
            }
            if ($this->session->flashdata('msg_failed')) {
                echo notif('danger', $this->session->flashdata('msg_failed'));
            }
            ?>
            <?php echo form_open('', 'data-action="' . base_url() . ADMIN_PATH . '/profile/updateMitra" id="mitra_form" autocomplete="off"'); ?>
                <?php if ($detil['photo']) { ?>
                <div class="form-group">
                    <div class="col-md-8">
                        <label>Foto Profil</label><br>
                        <img src="<?php echo base_url().config_item('upload_path') . 'mitra/'.$detil['photo']; ?>" class="img-thumbnail img-responsive" alt="<?php echo $detil['name']; ?>" style="max-height:250px;">
                    </div>
                </div>
                <?php } ?>
                <div class="form-group">
                    <div class="col-md-8">
                        <label>Email</label>
                        <input type="email" class="form-control" name="email" value="<?=$detil['email']?>" readonly>
                    </div>
                </div>
                <div class="form-group">
                    <div class="col-md-8">
                        <label>Nama Lengkap</label>
                        <input type="text" class="form-control" name="name" id="name" value="<?=$detil['name']?>">
                    </div>
                </div>
                <div class="form-group">
                    <div class="col-md-8">
                        <label>No. KTP/SIM/Paspor</label>
                        <input type="text" class="form-control" name="identity_code" id="identity_code" value="<?=$detil['identity_code']?>">
                    </div>
                </div>
                <div class="form-group">
                    <div class="col-md-8">
                        <label>Name NPWP</label>
                        <input type="text" class="form-control" name="name_npwp" id="name_npwp" value="<?=$detil['name_npwp']?>">
                    </div>
                </div>
                <div class="form-group">
                    <div class="col-md-8">
                        <label>No. NPWP</label>
                        <input type="text" class="form-control" name="no_npwp" id="no_npwp" value="<?=$detil['no_npwp']?>">
                    </div>
                </div>
                <div class="form-group">
                    <div class="col-md-8">
                        <label>Alamat NPWP</label>
                        <textarea class="form-control" name="address_npwp" id="address_npwp"><?=$detil['address_npwp']?></textarea>
                    </div>
                </div>
                <div class="form-group">
                    <div class="col-md-8">
                        <label>Jenis Kelamin</label><br />
                        <label class="radio-inline">
                            <input name="gender" value="L" type="radio"<?php if ($detil['gender']=='L') {
                                echo ' checked';
                                                                       } ?>>Laki-laki
                        </label>
                        <label class="radio-inline">
                            <input name="gender" value="P" type="radio"<?php if ($detil['gender']=='P') {
                                echo ' checked';
                                                                       } ?>>Perempuan
                        </label>
                    </div>
                </div>
                <div class="form-group">
                    <div class="col-md-8">
                        <label>Alamat</label>
                        <textarea class="form-control" name="address" id="address"><?=$detil['address']?></textarea>
                    </div>
                </div>
                <div class="form-group">
                    <div class="col-md-8">
                        <label>No. Telpon/HP</label>
                        <input type="text" class="form-control" name="telp" id="telp" value="<?=$detil['telp']?>">
                    </div>
                </div>
                <div class="form-group">
                    <div class="col-sm-8">
                        <label>Korwil/EC</label>
                        <input type="text" class="form-control" value="<?=$detil['korwil']?>" disabled>
                    </div>
                </div>
                <div class="form-group">
                    <div class="col-sm-8">
                        <label>Referensi</label>
                        <input type="text" class="form-control" value="<?=$detil['nama_referensi']?>" disabled>
                    </div>
                </div>
                <div class="form-group">
                    <div class="col-md-12">
                        <hr>
                    </div>
                </div>
                <div class="form-group">
                    <div class="col-md-8">
                        <label>Nama Bank</label>
                        <select id="bank_account_type" name="bank_account_type" class="form-control">
                            <?php
                            foreach ($listBank as $item) {
                                $selected = ($item->id == $detil['bank_account_type']) ? ' selected' : '';
                                echo '<option value="' . $item->id . '" ' . $selected . '>' . $item->bank_name . '</option>';
                            }
                            ?>
                        </select>
                    </div>
                </div>
                <div class="form-group">
                    <div class="col-md-8">
                        <label>Nomor Rekening Bank</label>
                        <input type="text" class="form-control" name="bank_account_number" id="bank_account_number" value="<?=$detil['bank_account_number']?>">
                    </div>
                </div>
                <div class="form-group">
                    <div class="col-md-8">
                        <label>Nama Pemilik Rekening</label>
                        <input type="text" class="form-control" name="bank_account_name" id="bank_account_name" value="<?=$detil['bank_account_name']?>">
                    </div>
                </div>
                <div class="form-group">
                    <div class="col-md-12">
                        <hr>
                    </div>
                </div>
                <div class="form-group">
                    <div class="col-md-8">
                        <h4><u>Ubah Kata Sandi</u></h4>
                    </div>
                </div>
                <div class="form-group">
                    <div class="col-md-8">
                        <label>Kata Sandi Baru</label>
                        <input type="password" class="form-control" name="passwd" id="passwd">
                    </div>
                </div>
                <div class="form-group">
                    <div class="col-md-8">
                        <label>Konfirmasi Kata Sandi Baru</label>
                        <input type="password" class="form-control" name="passwd_conf" id="passwd_conf">
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
</div>
