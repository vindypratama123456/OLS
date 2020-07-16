<div class="container-fluid">

    <!-- Page Heading -->
    <div class="row">
        <div class="col-lg-12">
            <h1 class="page-header">
                Regional
            </h1>
            <ol class="breadcrumb">
                <li>
                    <a href="<?php echo base_url() . ADMIN_PATH; ?>">Beranda</a>
                </li>
                <li class="active">
                    Regional
                </li>
            </ol>
        </div>
    </div>
    <!-- /.row -->

    <div class="row">
        <div class="col-lg-12">
            <p>
                <a href="<?php echo base_url() . ADMIN_PATH; ?>/region/add" class="btn btn-success btn-lg" title="Tambah">Tambah Data</a>
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
                            <th class="text-center" width="65%">Nama Regional</th>
                            <th class="text-center" width="30%">Opsi</th>
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
                            <td class="text-center">
                                <a href="<?=base_url(ADMIN_PATH.'/region/edit/'.$row->id_group)?>">Ubah</a> |  
                                <a href="#" class="del_data" data-id="<?=$row->id_group?>">Hapus</a>
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