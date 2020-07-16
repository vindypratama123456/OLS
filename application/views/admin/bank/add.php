<div class="container-fluid">

    <!-- Page Heading -->
    <div class="row">
        <div class="col-lg-12">
            <h1 class="page-header">
                Data Bank
            </h1>
            <ol class="breadcrumb">
                <li>
                    <a href="<?php echo base_url() . ADMIN_PATH; ?>">Beranda</a>
                </li>
                <li>
                    <a href="<?php echo base_url() . ADMIN_PATH . '/bank'; ?>">Data Bank</a>
                </li>
                <li class="active">
                    Add
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

            <?php 
                // If($add==true)
                // {
                //     echo form_open(base_url() . ADMIN_PATH . '/category/addPost1', 'data-action="' . base_url() . ADMIN_PATH . '/category/addPost1" id="category_form" autocomplete="off"'); 
                // }
                // else
                // {
                //     echo form_open(base_url() . ADMIN_PATH . '/category/editPost1', 'data-action="' . base_url() . ADMIN_PATH . '/category/editPost1" id="category_form" autocomplete="off"'); 
                // }

                If($add==true)
                {
                    echo form_open('', 'data-action="' . base_url() . ADMIN_PATH . '/bank/addPost" id="bank_form" autocomplete="off"'); 
                }
                else
                {
                    echo form_open('', 'data-action="' . base_url() . ADMIN_PATH . '/bank/editPost" id="bank_form" autocomplete="off"'); 
                }
            ?>

                <input type="hidden" id="id" name="id" value="<?=$detail['id']?>">

                <div class="form-group">
                    <div class="col-md-8">
                        <label>Kode Bank</label>
                        <input type="text" class="form-control" name="bank_code" id="bank_code" value="<?=$detail['bank_code']?>">
                    </div>
                </div>
                
                <div class="form-group">
                    <div class="col-md-8">
                        <label>Nama Bank</label>
                        <input type="text" class="form-control" name="bank_name" id="bank_name" value="<?=$detail['bank_name']?>">
                    </div>
                </div>
                
                <div class="form-group">
                    <div class="col-md-8">
                        <label>Alias</label>
                        <input type="text" class="form-control" name="bank_alias" id="bank_alias" value="<?=$detail['bank_alias']?>">
                    </div>
                </div>

                <div class="form-group">
                    <div class="col-md-8">
                        <br /><label>Status</label><br />
                        <label class="radio-inline">
                            <input name="status" value="1" type="radio"<?php if($detail['status']==1) echo ' checked'; ?>>Aktif
                        </label>
                        <label class="radio-inline">
                            <input name="status" value="0" type="radio"<?php if($detail['status']==0) echo ' checked'; ?>>Non-aktif
                        </label>
                    </div>
                </div>

                <div class="form-group">
                    <div class="col-md-12"><br />
                        <button type="submit" class="btn btn-success pull-left">Simpan</button>
                        <a href="<?php echo base_url().ADMIN_PATH; ?>/bank" class="btn btn-primary pull-right">Kembali</a>
                    </div>
                </div>

            <?php echo form_close(); ?>
            
        </div>
    </div>
    <!-- /.row -->

</div>
<!-- /.container-fluid -->