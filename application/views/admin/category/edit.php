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
                    Ubah
                </li>
            </ol>
        </div>
    </div>
    <!-- /.row -->

    <div class="row">
        <div class="col-lg-12">

            <?php echo form_open('', 'data-action="' . base_url() . ADMIN_PATH . '/category/editPost" id="category_form" autocomplete="off"'); ?>

                <input type="hidden" name="id_category" value="<?=$detil['id_category']?>" />

                <div class="form-group">
                    <div class="col-md-6">
                        <label>Kategori Induk</label>
                        <select class="form-control" name="parent" id="parent">
                            <option value="">- Silahkan Pilih -</option>
                            <?php 
                            if($parents) { 
                                foreach ($parents as $row) {
                                    if($row->id_category==$detil['id_category'])
                                        continue;
                                    $selected = ($row->id_category==$detil['id_parent']) ? ' selected' : '';
                                    echo '<option value="'.$row->id_category.'"'.$selected.'>'.$row->name.'</option>';
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
                        <input type="text" class="form-control" name="kategori" id="kategori" value="<?=$detil['name']?>">
                    </div>
                </div>

                <div class="form-group">
                    <div class="col-md-8">
                        <br /><label>Status</label><br />
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