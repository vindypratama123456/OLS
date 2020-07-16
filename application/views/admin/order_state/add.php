<div class="container-fluid">

    <!-- Page Heading -->
    <div class="row">
        <div class="col-lg-12">
            <h1 class="page-header">
                Status
            </h1>
            <ol class="breadcrumb">
                <li>
                    <a href="<?php echo base_url() . ADMIN_PATH; ?>">Beranda</a>
                </li>
                <li class="active">
                    <a href="<?php echo base_url() . ADMIN_PATH; ?>/order_state">Status</a>
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

            <?php echo form_open('', 'data-action="' . base_url() . ADMIN_PATH . '/order_state/addPost" id="order_state_form" autocomplete="off"'); ?>

                <div class="form-group">
                    <div class="col-md-8">
                        <label>Nama Status</label>
                        <input type="text" class="form-control" name="name" id="name">
                    </div>
                </div>

                <div class="form-group">
                    <div class="col-md-12"><br />
                        <button type="submit" class="btn btn-success pull-left">Simpan</button>
                        <a href="<?php echo base_url().ADMIN_PATH; ?>/order_state" class="btn btn-primary pull-right">Kembali</a>
                    </div>
                </div>

            <?php echo form_close(); ?>

        </div>
    </div>
    <!-- /.row -->

</div>
<!-- /.container-fluid -->