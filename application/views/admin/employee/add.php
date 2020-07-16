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
                    Tambah
                </li>
            </ol>
        </div>
    </div>
    <!-- /.row -->

    <div class="row">
        <div class="col-lg-12">

            <?php echo form_open('', 'data-action="' . base_url() . ADMIN_PATH . '/employee/addPost" id="employee_form" autocomplete="off"'); ?>

                <div class="form-group">
                    <div class="col-md-8">
                        <label>Nama Pengguna</label>
                        <input type="text" class="form-control" name="name" id="name">
                    </div>
                </div>

                <div class="form-group">
                    <div class="col-md-8">
                        <label>Alamat Email</label>
                        <input type="email" class="form-control" name="email" id="email">
                    </div>
                </div>

                <div class="form-group">
                    <div class="col-md-6">
                        <label>Kata Sandi</label>
                        <input type="password" class="form-control" name="password" id="password">
                    </div>
                </div>

                <div class="form-group">
                    <div class="col-md-8">
                        <label>Tipe</label><br />
                        <label class="radio-inline">
                            <input name="level" value="2" type="radio" id="radio_admin">Admin
                        </label>
                        <label class="radio-inline">
                            <input name="level" value="3" type="radio" id="radio_operator">Operator
                        </label>
                    </div>
                </div>

                <div class="form-group" style="display:none;" id="regional_area">
                    <div class="col-sm-6">
                        <label>Regional</label>
                        <select class="form-control" name="regional" id="regional">
                            <option value="">- Pilih Satu -</option>
                            <?php 
                            if($regional) { 
                                foreach ($regional as $row) {
                                    echo '<option value="'.$row->id_group.'">'.$row->name.'</option>';
                                }
                            }
                            ?>
                        </select>
                    </div>
                </div>

                <div class="form-group">
                    <div class="col-md-12"><br />
                        <button type="submit" class="btn btn-success pull-left">Simpan</button>
                        <a href="<?php echo base_url().ADMIN_PATH; ?>/employee" class="btn btn-primary pull-right">Kembali</a>
                    </div>
                </div>

            <?php echo form_close(); ?>

        </div>
    </div>
    <!-- /.row -->

</div>
<!-- /.container-fluid -->