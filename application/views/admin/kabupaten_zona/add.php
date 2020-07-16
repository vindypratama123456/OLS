<div class="container-fluid">

    <!-- Page Heading -->
    <div class="row">
        <div class="col-lg-12">
            <h1 class="page-header">
                Kabupaten Zona
            </h1>
            <ol class="breadcrumb">
                <li>
                    <a href="<?php echo base_url() . ADMIN_PATH; ?>">Beranda</a>
                </li>
                <li>
                    <a href="<?php echo base_url() . ADMIN_PATH . '/kabupaten_zona'; ?>">Kabupaten Zona</a>
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
                    echo form_open('', 'data-action="' . base_url() . ADMIN_PATH . '/kabupaten_zona/addPost" id="kabupaten_zona_form" autocomplete="off"'); 
                }
                else
                {
                    echo form_open('', 'data-action="' . base_url() . ADMIN_PATH . '/kabupaten_zona/editPost" id="kabupaten_zona_form" autocomplete="off"'); 
                }
            ?>

                <input type="hidden" id="zonaval" name="zonaval" value="<?=$detail['zona']?>">
                <input type="hidden" id="id" name="id" value="<?=$detail['id']?>">
                <div class="form-group">
                    <div class="col-md-8">
                        <label>Nama Kabupaten</label>
                        <input type="text" class="form-control" name="kabupaten" id="kabupaten" value="<?=$detail['kabupaten']?>">
                    </div>
                </div>

                <div class="form-group">
                    <div class="col-md-6"><br/>
                        <label>Zona Kabupaten</label>
                        <select class="form-control" name="zona" id="zona">
                            <option value="">- Silahkan Pilih Zona-</option>
                            <?php 
                                if($dataZona)
                                {
                                    $selected = '';
                                    foreach ($dataZona as $data) 
                                    {
                                        ?>
                                        <option value="<?php echo $data->id_site; ?>"  <?php echo ($data->id_site==$detail['zona'])? ' selected':''; ?> > <?php echo $data->id_site ?> </option>
                            <?php   }
                                } ?>
                        </select>
                    </div>
                </div>

                <div class="form-group">
                    <div class="col-md-8">
                        <br /><label>SD Status</label><br />
                        <label class="radio-inline">
                            <input name="is_allowed_sd" value="1" type="radio"<?php if($detail['is_allowed_sd']==1) echo ' checked'; ?>>Aktif
                        </label>
                        <label class="radio-inline">
                            <input name="is_allowed_sd" value="0" type="radio"<?php if($detail['is_allowed_sd']==0) echo ' checked'; ?>>Non-aktif
                        </label>
                    </div>
                </div>

                <div class="form-group">
                    <div class="col-md-12"><br />
                        <button type="submit" class="btn btn-success pull-left">Simpan</button>
                        <a href="<?php echo base_url().ADMIN_PATH; ?>/kabupaten_zona" class="btn btn-primary pull-right">Kembali</a>
                    </div>
                </div>

            <?php echo form_close(); ?>
            
        </div>
    </div>
    <!-- /.row -->

</div>
<!-- /.container-fluid -->