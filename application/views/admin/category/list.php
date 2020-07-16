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
                    Kategori
                </li>
            </ol>
        </div>
    </div>
    <!-- /.row -->

    <div class="row">
        <div class="col-lg-12">
            <p>
                <a href="<?php echo base_url() . ADMIN_PATH; ?>/category/add" class="btn btn-success btn-lg" title="Tambah">Tambah Data</a>
            </p>
            <?php 
            if($this->session->flashdata('msg_success')) {
                echo notif('success',$this->session->flashdata('msg_success'));
            }
            if($this->session->flashdata('msg_failed')) {
                echo notif('danger',$this->session->flashdata('msg_failed'));
            }
            ?>
            <div class="table-responsive">
                <?php if($listdata) { ?>
                <table class="table table-bordered table-hover table-striped">
                    <thead>
                        <tr>
                            <th class="text-center" width="5%">No</th>
                            <th class="text-center" width="60%">Nama Kategori</th>
                            <th class="text-center" width="15%">Status</th>
                            <th class="text-center" width="20%">Opsi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php 
                        $i = $this->uri->segment(4)+1; 
                        foreach($listdata as $row) { 
                        ?>
                        <tr>
                            <td class="text-center"><?php echo $i; ?></td>
                            <td><?php echo $row->name; ?></td>
                            <td class="text-center"><?php echo ($row->active==1) ? '<span class="label label-success">Aktif</span>' : '<span class="label label-danger">Non-aktif</span>' ; ?></td>
                            <td class="text-center">
                                <a href="<?=base_url(ADMIN_PATH.'/category/edit/'.$row->id_category)?>">Ubah</a> |  
                                <a href="#" class="del_data" data-id="<?=$row->id_category?>">Hapus</a>
                            </td>
                        </tr>
                        <?php $i++; } ?>
                    </tbody>
                </table>
                <?php 
                    echo $links; 
                } 
                else {
                    echo '<div class="well well-lg">Maaf, data tidak tersedia.</div>';
                } 
                ?>
            </div>
        </div>
    </div>
    <!-- /.row -->

</div>
<!-- /.container-fluid -->