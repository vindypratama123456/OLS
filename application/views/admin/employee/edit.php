<div class="container-fluid">

    <!-- Page Heading -->
    <div class="row">
        <div class="col-lg-12">
            <h1 class="page-header">
                Pengguna
            </h1>
            <ol class="breadcrumb">
                <li>
                    <a href="<?php echo base_url() . ADMIN_PATH; ?>">Beranda</a>
                </li>
                <li class="active">
                    <a href="<?php echo base_url() . ADMIN_PATH; ?>/employee">Pengguna</a>
                </li>
                <li class="active">
                    Ubah
                </li>
            </ol>
        </div>
    </div>
    <!-- /.row -->

    <div class="row">
        <div class="col-lg-12">

            <?php echo form_open('', 'data-action="' . base_url() . ADMIN_PATH . '/employee/editPost" id="employee_form" autocomplete="off"'); ?>

                <input type="hidden" name="id_employee" value="<?=$detil['id_employee']?>" />

                <div class="form-group">
                    <div class="col-md-8">
                        <label>Nama Pengguna</label>
                        <input type="text" class="form-control" name="name" id="name" value="<?=$detil['name']?>">
                    </div>
                </div>

                <div class="form-group">
                    <div class="col-md-8">
                        <label>Alamat Email</label>
                        <input type="email" class="form-control" name="email" id="email" value="<?=$detil['email']?>">
                    </div>
                </div>

                <div class="form-group">
                    <div class="col-md-6">
                        <label>Kata Sandi</label>
                        <input type="password" class="form-control" name="password" id="password">
                    </div>
                </div>

                <?php if(!in_array($detil['level'], $this->backoffice_superadmin_area)) { ?>

                <div class="form-group">
                    <div class="col-md-8">
                        <label>Tipe</label><br />
                        <label class="radio-inline">
                            <input name="level" value="2" type="radio" id="radio_admin"<?php if($detil['level']==2) echo ' checked'; ?>>Admin
                        </label>
                        <label class="radio-inline">
                            <input name="level" value="3" type="radio" id="radio_operator"<?php if($detil['level']==3) echo ' checked'; ?>>Operator
                        </label>
                    </div>
                </div>

                <div class="form-group"<?php if($detil['level']!=3) echo ' style="display:none;" '; ?>id="regional_area">
                    <div class="col-sm-6">
                        <label>Regional</label>
                        <select class="form-control" name="regional" id="regional">
                            <option value="">- Pilih Satu -</option>
                            <?php 
                            if($regional) { 
                                foreach ($regional as $row) {
                                    $selected = ($row->id_group==$detil['regional']) ? ' selected' : '';
                                    echo '<option value="'.$row->id_group.'"'.$selected.'>'.$row->name.'</option>';
                                }
                            }
                            ?>
                        </select>
                    </div>
                </div>

                <?php } ?>

                <div class="form-group">
                    <div class="col-md-8">
                        <label>Status</label><br />
                        <label class="radio-inline">
                            <input name="active" value="1" type="radio"<?php if($detil['active']==1) echo ' checked'; ?>>Aktif
                        </label>
                        <label class="radio-inline">
                            <input name="active" value="0" type="radio"<?php if($detil['active']==0) echo ' checked'; ?>>Non-aktif
                        </label>
                    </div>
                </div>

                <div class="form-group">
                    <div class="col-md-12"><br />
                        <?php if(in_array($this->adm_level, $this->backoffice_superadmin_area)) { ?>
                            <button type="submit" class="btn btn-success pull-left">Simpan</button>
                        <?php } ?>
                        <a href="<?php echo base_url().ADMIN_PATH; ?>/employee" class="btn btn-primary <?php echo (in_array($this->adm_level, $this->backoffice_superadmin_area)) ? 'pull-right' : 'pull-left'; ?>">Kembali</a>
                    </div>
                </div>

            <?php echo form_close(); ?>

        </div>
    </div>
    <!-- /.row -->

</div>
<!-- /.container-fluid -->