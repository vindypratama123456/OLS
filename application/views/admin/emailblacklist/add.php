<div class="container-fluid">

    <!-- Page Heading -->
    <div class="row">
        <div class="col-lg-12">
            <h1 class="page-header">
                Form Email Blacklist
            </h1>
            <ol class="breadcrumb">
                <li>
                    <a href="<?php echo base_url() . ADMIN_PATH; ?>">Beranda</a>
                </li>
                <li>
                    <a href="<?php echo base_url() . ADMIN_PATH . '/kabupaten_zona'; ?>">Email Blacklist</a>
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
                //     echo form_open('', 'data-action="' . base_url() . ADMIN_PATH . '/kabupaten_zona/addPost" id="kabupaten_zona_form" autocomplete="off"'); 
                // }
                // else
                // {
                //     echo form_open('', 'data-action="' . base_url() . ADMIN_PATH . '/kabupaten_zona/editPost" id="kabupaten_zona_form" autocomplete="off"'); 
                // }
            ?>

                <!-- <input type="hidden" id="zonaval" name="zonaval" value="<?=$detail['zona']?>">
                <input type="hidden" id="id" name="id" value="<?=$detail['id']?>"> -->
                <?php
                    echo form_open('', 'data-action="' . base_url() . ADMIN_PATH . '/emailblacklist/addPost" id="email_blacklist_form" autocomplete="off"'); 
                ?>
                <div class="form-group">
                    <div class="col-md-8">
                        <label>Email</label>
                        <input type="text" class="form-control" name="email" id="email" value="<?php //$detail['kabupaten']?>">
                    </div>
                </div>

                <div class="form-group">
                    <div class="col-md-12"><br />
                        <button type="submit" class="btn btn-success pull-left">Simpan</button>
                        <a href="<?php echo base_url().ADMIN_PATH; ?>/emailblacklist" class="btn btn-primary pull-right">Kembali</a>
                    </div>
                </div>

            <?php echo form_close(); ?>
            
        </div>
    </div>
    <!-- /.row -->

</div>
<!-- /.container-fluid -->