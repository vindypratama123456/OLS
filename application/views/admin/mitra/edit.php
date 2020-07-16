<div class="container-fluid">
    <!-- Page Heading -->
    <div class="row">
        <div class="col-lg-12">
            <h1 class="page-header">Mitra</h1>
            <ol class="breadcrumb">
                <li><a href="<?php echo base_url() . ADMIN_PATH; ?>">Beranda</a></li>
                <li><a href="<?php echo base_url() . ADMIN_PATH; ?>/mitra">Mitra</a></li>
                <li class="active">Detil</li>
            </ol>
        </div>
    </div>
    <!-- /.row -->
    <div class="row">
        <div class="col-lg-12">
            <?php echo form_open('', 'data-action="' . base_url() . ADMIN_PATH . '/mitra/updatePost" id="mitra_form" autocomplete="off"'); ?>
                <input type="hidden" name="id_employee" value="<?=$detil['id_employee']?>">
                <input type="hidden" name="id_korwil" value="<?=$detil['id_korwil']?>">
                <input type="hidden" name="email_korwil" value="<?=$detil['email_korwil']?>">
                <input type="hidden" name="current_status" value="<?=$detil['active']?>">
                <input type="hidden" name="is_activated" value="<?=$detil['is_activated']?>">
                <?php if ($detil['photo']) { ?>
                <div class="form-group">
                        <label>Foto Profil</label>
                        <img src="<?php echo base_url().config_item('upload_path') . 'mitra/'.$detil['photo']; ?>" class="img-thumbnail img-responsive" alt="<?php echo $detil['name']; ?>" style="max-height:250px;">
                </div>
                <?php } ?>
                <div class="form-group">
                        <label>Kode</label>
                        <input type="text" class="form-control" value="<?=$detil['code']?>" disabled>
                </div>
                <div class="form-group">
                        <label>Email</label>
                        <input type="email" class="form-control" name="email" value="<?=$detil['email']?>"<?php if (1==$detil['is_activated']) echo ' readonly'; ?>>
                </div>
                <div class="form-group">
                        <label>Nama Lengkap</label>
                        <input type="text" class="form-control" name="name" id="name" value="<?=$detil['name']?>">
                </div>
                <div class="form-group">
                    <div class="col-md-8">
                        <label>No. KTP</label>
                        <input type="text" class="form-control" name="identity_code" id="identity_code" value="<?=$detil['identity_code']?>">
                </div>
                <div class="form-group">
                        <label>Name NPWP</label>
                        <input type="text" class="form-control" name="name_npwp" id="name_npwp" value="<?=$detil['name_npwp']?>">
                </div>
                <div class="form-group">
                        <label>No. NPWP</label>
                        <input type="text" class="form-control" name="no_npwp" id="no_npwp" value="<?=$detil['no_npwp']?>">
                </div>
                <div class="form-group">
                        <label>Alamat NPWP</label>
                        <textarea class="form-control" name="address_npwp" id="address_npwp"><?=$detil['address_npwp']?></textarea>
                </div>
                <div class="form-group">
                        <label>Jenis Kelamin</label><br />
                        <label class="radio-inline">
                            <input name="gender" value="L" type="radio"<?php if ($detil['gender']=='L') { echo ' checked'; } ?>>Laki-laki
                        </label>
                        <label class="radio-inline">
                            <input name="gender" value="P" type="radio"<?php if ($detil['gender']=='P') { echo ' checked'; } ?>>Perempuan
                        </label>
                </div>
                <div class="form-group">
                        <label>Alamat</label>
                        <textarea class="form-control" name="address" id="address"><?=$detil['address']?></textarea>
                </div>
                <div class="form-group">
                        <label>No. Telpon/HP</label>
                        <input type="text" class="form-control" name="telp" id="telp" value="<?=$detil['telp']?>">
                </div>
                <div class="form-group">
                        <label>Korwil/EC</label>
                        <input type="text" class="form-control" value="<?=$detil['korwil']?>" disabled>
                </div>
                <div class="form-group">
                        <label>Referensi</label>
                        <?php if (in_array($adm_level, $this->backoffice_superadmin_area) || $adm_level == 3 || $adm_level == 8) { ?>
                        <select id="code_referral" name="code_referral" class="form-control">
                            <option value="">- Silahkan Pilih Referensi -</option>
                            <?php
                            foreach ($referensi as $item) {
                                if ($item->code == $detil['code'])
                                    continue;
                                $selected = ($item->code == $detil['code_referral']) ? ' selected' : '';
                                echo '<option value="' . $item->code . '" ' . $selected . '>' . $item->code . ' - ' . $item->name . ' (' . $item->email . ')</option>';
                            }
                            ?>
                        </select>
                        <?php } else { ?>
                        <input type="text" class="form-control" value="<?=$detil['nama_referensi']?>" disabled>
                        <?php } ?>
                </div>
                <div class="form-group">
                        <hr>
                </div>
                <div class="form-group">
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
                <div class="form-group">
                        <label>Nomor Rekening Bank</label>
                        <input type="text" class="form-control" name="bank_account_number" id="bank_account_number" value="<?=$detil['bank_account_number']?>">
                </div>
                <div class="form-group">
                        <label>Nama Pemilik Rekening</label>
                        <input type="text" class="form-control" name="bank_account_name" id="bank_account_name" value="<?=$detil['bank_account_name']?>">
                </div>

                <div class="form-group">
                        <hr>
                </div>
                <div class="form-group">
                        <label>Persentase Komisi (%)</label>
                        <input type="text" class="form-control" name="percent_comission" id="percent_comission" value="<?=$detil['percent_comission']*100?>" max="15" maxlength="5">
                </div>
                <div class="form-group">
                        <label>PPh (%)</label>
                        <select class="form-control" name="percent_tax" id="percent_tax">
                            <option value="2"<?php if ($detil['percent_tax']*100 == 2) echo ' selected'; ?>>2</option>
                            <option value="2.5"<?php if ($detil['percent_tax']*100 == 2.5) echo ' selected'; ?>>2.5</option>
                            <option value="3"<?php if ($detil['percent_tax']*100 == 3) echo ' selected'; ?>>3</option>
                        </select>
                </div>
                <div class="form-group">
                        <hr>
                </div>
                <div class="form-group">
                        <label>Status</label><br />
                        <label class="radio-inline">
                            <input name="active" value="1" type="radio"<?php if ($detil['active']==1) { echo ' checked'; } ?>>Aktif
                        </label>
                        <label class="radio-inline">
                            <input name="active" value="0" type="radio"<?php if ($detil['active']==0) { echo ' checked'; } ?>>Non-aktif
                        </label>
                </div>
                <div class="form-group">
                    <div class="col-md-12">
                        <hr>
                    </div>
                </div>
                <div class="form-group">
                        <?php if ($adm_level != 6) { ?>
                        <button type="submit" class="btn btn-success pull-left">Simpan</button>
                        <?php } ?>
                        <a href="<?php echo base_url().ADMIN_PATH; ?>/mitra/detail/<?=$detil['id_employee']?>" class="btn btn-primary pull-<?php echo ($adm_level == 6) ? 'left' : 'right'; ?>">Kembali</a>
                </div>
            <?php echo form_close(); ?>
        </div>
    </div>
    <!-- /.row -->
</div>
<!-- /.container-fluid -->
