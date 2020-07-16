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
                    Ubah
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
            ?>
           
           <div class="panel panel-default">
                <!-- Default panel contents -->
                <div class="panel-heading"><h4>Data Profil Sekolah</h4></div>
                <div class="panel-body">
                    <!-- List group -->
                    <ul class="list-group">
                        <li class="list-group-item">NPSN: <?php echo $detil['no_npsn']; ?></li>
                        <li class="list-group-item">Nama Sekolah: <?php echo $detil['school_name']; ?></li>
                        <li class="list-group-item">Jenjang: <?php echo ($detil['jenjang']=='1-6') ? 'SD' : (($detil['jenjang']=='7-9') ? 'SMP' : 'SMA/SMK'); ?></li>
                        <li class="list-group-item">Telpon: <?php echo $detil['phone']; ?></li>
                        <li class="list-group-item">Email: <?php echo $detil['email']; ?></li>
                        <li class="list-group-item">Zona: <?php echo $detil['zona']; ?></li>
                    </ul>
                    <ul class="list-group">
                        <li class="list-group-item">Alamat: <?php echo $detil['alamat']; ?></li>
                        <li class="list-group-item">Provinsi: <?php echo $detil['provinsi']; ?></li>
                        <li class="list-group-item">Kab/Kota: <?php echo $detil['kabupaten']; ?></li>
                        <li class="list-group-item">Kecamatan: <?php echo $detil['kecamatan']; ?></li>
                        <li class="list-group-item">Desa/Kelurahan: <?php echo $detil['desa']; ?></li>
                        <li class="list-group-item">Kodepos: <?php echo $detil['kodepos']; ?></li>
                    </ul>
                    <ul class="list-group">
                        <li class="list-group-item">Nama Kepala Sekolah: <?php echo $detil['name']; ?></li>
                        <li class="list-group-item">NIP: <?php echo $detil['nip_kepsek']; ?></li>
                        <li class="list-group-item">Telpon/HP: <?php echo $detil['phone_kepsek']; ?></li>
                        <li class="list-group-item">Email: <?php echo $detil['email_kepsek']; ?></li>
                    </ul>
                    <ul class="list-group">
                        <li class="list-group-item">Nama Operator: <?php echo $detil['operator']; ?></li>
                        <li class="list-group-item">Telpon/HP: <?php echo $detil['hp_operator']; ?></li>
                        <li class="list-group-item">Email: <?php echo $detil['email_operator']; ?></li>
                    </ul>
                    <a href="<?php echo base_url().ADMIN_PATH; ?>/customer" class="btn btn-primary pull-right">Kembali</a> &nbsp;
                    <a href="<?php echo base_url(ADMIN_PATH.'/customer/editPopup/'.$detil['id_customer']); ?>" class="btn btn-warning pull-left" data-toggle="modal" data-target="#myModal">Ubah Data</a>
                </div>
            </div>

        </div>
    </div>
    <!-- /.row -->

</div>
<!-- /.container-fluid -->

<div class="modal fade" id="myModal" role="dialog" aria-labelledby="myModalLabel" data-keyboard="false" data-backdrop="static">
    <div class="modal-dialog" style="width:50%;">
      <div class="modal-content">
      </div>
    </div>
</div>