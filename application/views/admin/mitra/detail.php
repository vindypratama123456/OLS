<div class="container-fluid">
    <!-- Page Heading -->
    <div class="row">
        <div class="col-lg-12">
            <h1 class="page-header">Mitra</h1>
            <ol class="breadcrumb">
                <li><a href="<?php echo base_url() . ADMIN_PATH; ?>">Beranda</a></li>
                <li><a href="<?php echo base_url() . ADMIN_PATH; ?>/mitra">Mitra</a></li>
                <li class="active">Detil</li>
            </ol>
        </div>
    </div>
    <!-- /.row -->
    <div class="row">
        <div class="col-lg-12">
            <div class="panel panel-default">
                <div class="panel-heading">
                    <h4>Data Mitra</h4>
                </div>
                <div class="panel-body">
                    <ul class="list-group">
                        <li class="list-group-item"><b>Email:</b> <?=$detil['email']?></li>
                        <li class="list-group-item"><b>Nama Lengkap:</b> <?=$detil['name']?></li>
                        <li class="list-group-item"><b>No. KTP/SIM/Paspor:</b> <?=$detil['identity_code']?></li>
                        <li class="list-group-item"><b>Nama NPWP:</b> <?=$detil['name_npwp']?></li>
                        <li class="list-group-item"><b>No. NPWP:</b> <?=$detil['no_npwp']?></li>
                        <li class="list-group-item"><b>Alamat NPWP:</b> <?=$detil['address_npwp']?></li>
                        <li class="list-group-item"><b>Jenis Kelamin:</b> <?php if($detil['gender']=='L'){ echo 'Laki-laki'; } else { echo "Perempuan"; } ?></li>
                        <li class="list-group-item"><b>Alamat:</b> <?=$detil['address']?></li>
                        <li class="list-group-item"><b>No. Telpon/HP:</b> <?=$detil['telp']?></li>
                        <li class="list-group-item"><b>Korwil/EC:</b> <?=$detil['korwil']?></li>
                        <li class="list-group-item"><b>Referensi:</b> <?=$detil['nama_referensi']?></li>
                        <li class="list-group-item"><b>Nama Bank:</b> <?=$detil['bank_account_type']?></li>
                        <li class="list-group-item"><b>Nomor Rekening Bank:</b> <?=$detil['bank_account_number']?></li>
                        <li class="list-group-item"><b>Nama Pemilik Rekening:</b> <?=$detil['bank_account_name']?></li>
                        <li class="list-group-item"><b>Persentase Komisi (%):</b> <?=$detil['percent_comission']*100?></li>
                        <li class="list-group-item"><b>PPh (%):</b> <?=$detil['percent_tax']*100?></li>
                        <li class="list-group-item"><b>Status:</b> <?php if ($detil['active']==1) { echo 'Aktif'; }else{ echo 'Non-aktif'; } ?></li>
                    </ul>
                    <!-- <a href="<?php echo base_url(ADMIN_PATH.'/mitra/update/').$detil['id_employee']; ?>" class="btn btn-warning"></i> Update</a> -->
                    <a href="<?php echo base_url(ADMIN_PATH.'/mitra/update/').$detil['id_employee']; ?>" class="btn btn-warning" data-toggle="modal" data-target="#myModal"><i class="fa fa-pencil"></i> Update</a>
                </div>
            </div>
            <div class="panel panel-default">
                <div class="panel-heading">
                    <h4>Data Kontrak</h4>
                </div>
                <div class="panel-body">
                    <ul class="list-group">
                        <li class="list-group-item"><b>Tanggal Mulai:</b> <?=$data_kontrak['mikon_tanggal']?></li>
                        <li class="list-group-item"><b>Tanggal Berakhir:</b> <?=$data_kontrak['mikon_tanggal_akhir']?></li>
                        <li class="list-group-item"><b>File:</b> </li>
                        <li class="list-group-item"><a href="<?php echo base_url().'uploads/kontrak/'.$data_kontrak['mikon_file']; ?>" target="_blank"><img src="<?php echo base_url().'uploads/kontrak/'.$data_kontrak['mikon_file']; ?>" width="200px"></a></li>
                </div>
            </div>
            <div class="form-group">
                    <a href="<?php echo base_url().ADMIN_PATH; ?>/mitra" class="btn btn-primary pull-right">Kembali</a>
            </div>
            <div class="modal fade" id="myModal" role="dialog" aria-labelledby="myModalLabel" data-keyboard="false" data-backdrop="static">
                <div class="modal-dialog modal-md">
                    <div class="modal-content">
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- /.row -->
</div>
<!-- /.container-fluid -->
