<div class="container-fluid">

    <!-- Page Heading -->
    <div class="row">
        <div class="col-lg-12">
            <h1 class="page-header">
                Pelanggan
            </h1>
            <ol class="breadcrumb">
                <li>
                    <a href="<?php echo base_url() . ADMIN_PATH; ?>">Beranda</a>
                </li>
                <li class="active">
                    <a href="<?php echo base_url() . ADMIN_PATH; ?>/customer">Pelanggan</a>
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

            <?php echo form_open('', 'data-action="' . base_url() . ADMIN_PATH . '/customer/addPost" id="customer_form" autocomplete="off"'); ?>

                <div class="form-group">
                    <div class="col-md-8">
                        <label>No. NPSN</label>
                        <input type="text" class="form-control" name="no_npsn" id="no_npsn">
                    </div>
                </div>

                <div class="form-group">
                    <div class="col-md-6">
                        <label>Jenjang</label>
                        <select class="form-control" name="jenjang" id="jenjang">
                            <option value="">- Pilih Satu -</option>
                            <option value="1-6">Kelas 1-6</option>
                            <option value="7-9">Kelas 7-9</option>
                            <option value="10-12">Kelas 10-12</option>
                        </select>
                    </div>
                </div>

                <div class="form-group">
                    <div class="col-md-8">
                        <label>Nama Sekolah</label>
                        <input type="text" class="form-control" name="school_name" id="school_name">
                    </div>
                </div>

                <div class="form-group">
                    <div class="col-md-6">
                        <label>Regional</label>
                        <select class="form-control" name="id_group" id="id_group">
                            <option value="">- Pilih Satu -</option>
                            <?php 
                            if($groups) { 
                                foreach ($groups as $row) {
                                    echo '<option value="'.$row->id_group.'">'.$row->name.'</option>';
                                }
                            }
                            ?>
                        </select>
                    </div>
                </div>

                <div class="form-group">
                    <div class="col-md-8">
                        <label>Nama Lengkap</label>
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
                        <input type="password" class="form-control" name="passwd" id="passwd">
                    </div>
                </div>

                <div class="form-group">
                    <div class="col-md-8">
                        <label>Jenis Kelamin</label><br />
                        <label class="radio-inline">
                            <input name="id_gender" value="1" type="radio">Pria
                        </label>
                        <label class="radio-inline">
                            <input name="id_gender" value="2" type="radio">Wanita
                        </label>
                    </div>
                </div>

                <div class="form-group">
                    <div class="col-md-12"><br />
                        <button type="submit" class="btn btn-success pull-left">Simpan</button>
                        <a href="<?php echo base_url().ADMIN_PATH; ?>/customer" class="btn btn-primary pull-right">Kembali</a>
                    </div>
                </div>

            <?php echo form_close(); ?>

        </div>
    </div>
    <!-- /.row -->

</div>
<!-- /.container-fluid -->