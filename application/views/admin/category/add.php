<div class="container-fluid">

    <!-- Page Heading -->
    <div class="row">
        <div class="col-lg-12">
            <h1 class="page-header">
                Kategori
            </h1>
            <ol class="breadcrumb">
                <li>
                    <a href="<?php echo base_url() . ADMIN_PATH; ?>">Beranda</a>
                </li>
                <li class="active">
                    <a href="<?php echo base_url() . ADMIN_PATH; ?>/category">Kategori</a>
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
            <?php echo form_open('', 'data-action="' . base_url() . ADMIN_PATH . '/category/addPost" id="category_form" autocomplete="off"'); ?>

                <div class="form-group">
                    <div class="col-md-6">
                        <label>Kategori Induk</label>
                        <select class="form-control" name="parent" id="parent">
                            <option value="">- Silahkan Pilih -</option>
                            <?php 
                            if($parents) { 
                                foreach ($parents as $row) {
                                    echo '<option value="'.$row->id_category.'">'.$row->name.'</option>';
                                }
                            }
                            ?>
                        </select>
                        <p class="help-block">Tidak wajib dipilih</p>
                    </div>
                </div>

                <div class="form-group">
                    <div class="col-md-8">
                        <label>Nama Kategori</label>
                        <input type="text" class="form-control" name="kategori" id="kategori">
                    </div>
                </div>

                <div class="form-group">
                    <div class="col-md-12"><br />
                        <button type="submit" class="btn btn-success pull-left">Simpan</button>
                        <a href="<?php echo base_url().ADMIN_PATH; ?>/category" class="btn btn-primary pull-right">Kembali</a>
                    </div>
                </div>

            <?php echo form_close(); ?>

        </div>
    </div>
    <!-- /.row -->

</div>
<!-- /.container-fluid -->