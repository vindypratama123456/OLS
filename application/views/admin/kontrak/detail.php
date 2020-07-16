<div class="container-fluid">
    <!-- Page Heading -->
    <div class="row">
        <div class="col-lg-12">
            <h1 class="page-header">Kontrak Mitra</h1>
            <ol class="breadcrumb">
                <li><a href="<?php echo base_url() . ADMIN_PATH; ?>">Beranda</a></li>
                <li><a href="<?php echo base_url() . ADMIN_PATH; ?>/Kontrak">Kontrak</a></li>
                <li class="active">Detil</li>
            </ol>
        </div>
    </div>
    <!-- /.row -->
    <div class="row">
        <div class="col-lg-12">
            <div class="panel panel-default">
                <div class="panel-heading"><h4>Data Mitra</h4></div>
                <div class="panel-body">
                    <ul class="list-group">
                        <li class="list-group-item">Email : <?php echo $detil['email']; ?></li>
                        <li class="list-group-item">Nama Lengkap : <?php echo $detil['name']; ?></li>
                    </ul>
                </div>
            </div>
            <div class="panel panel-default">
                <div class="panel-heading">
                    <h4 class="pull-left">Data Kontrak</h4>
                    <a href="<?php echo base_url(ADMIN_PATH.'/kontrak/kontrak_popup_add/'.$detil['code']); ?>" class="btn btn-success" data-toggle="modal" data-target="#myModal" style="margin-left: 1em;"><i class="fa fa-plus-square"></i> Tambah</a>
                </div>
                <div class="panel-body">
                    <div class="table-responsive">
                        <?php 
                        if ($listkontrak) { ?>
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th class="text-center">No</th>
                                    <th class="text-center">No. Kontrak</th>
                                    <th class="text-center">Tempat Kontrak</th>
                                    <th class="text-center">Tanggal Mulai</th>
                                    <th class="text-center">Tanggal Berakhir</th>
                                    <!-- <th class="text-center">Periode</th> -->
                                    <th class="text-center">File</th>
                                    <th class="text-center">aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                    $no = 1;
                                    foreach($listkontrak as $data)
                                    {
                                ?>
                                <tr>
                                    <td class="text-center"><?php echo $no; ?></td>
                                    <td class="text-center"><?php echo $data->mikon_no_kontrak; ?></td>
                                    <td class="text-center"><?php echo $data->mikon_tempat_kontrak; ?></td>
                                    <td class="text-center"><?php echo $data->mikon_tanggal; ?></td>
                                    <td class="text-center"><?php echo $data->mikon_tanggal_akhir; ?></td>
                                    <!-- <td class="text-center"><?php echo $data->mikon_periode; ?></td> -->
                                    <td class="text-center">
                                        <?php 
                                        $img = "";
                                        if($data->mikon_file == null || $data->mikon_file=="")
                                        {
                                            $img = '/uploads/kontrak/no_image.png';
                                        }
                                        else
                                        {
                                            $img = '/uploads/kontrak/'.$data->mikon_file;
                                        }
                                        ?>
                                        <img src="<?php echo base_url($img);?>" height="80px"/>
                                    </td>
                                    <td class="text-center">
                                        <a href="<?php echo base_url(ADMIN_PATH.'/kontrak/kontrak_popup/').$data->mikon_id."/". $detil['code']; ?>" class="btn btn-warning" data-toggle="modal" data-target="#myModal"><i class="fa fa-pencil"></i> ubah</a>
                                    </td>
                                </tr>
                                <?php 
                                        $no+=1;
                                    } 
                                ?>
                            </tbody>
                        </table>
                        <?php } ?>
                    </div>
                </div>
            </div>
            <div class="form-group">
                <div class="col-md-12"><br />
                    <a href="<?php echo base_url().ADMIN_PATH; ?>/kontrak" class="btn btn-primary pull-right">Kembali</a>
                </div>
            </div>
        </div>
    </div>
    <!-- /.row -->
</div>
<!-- /.container-fluid -->
<div class="modal fade" id="myModal" role="dialog" aria-labelledby="myModalLabel" data-keyboard="false" data-backdrop="static">
    <div class="modal-dialog modal-md">
      <div class="modal-content">
      </div>
    </div>
</div>

<script type="text/javascript">
    $(document).ready(function(){
        $("#mikon_tanggal").datepicker({
            format: "dd-mm-yyyy",
            autoclose: true,
            todayHighlight: true,
            toggleActive: true
        }).on("changeDate", function(e) {
            $("#mikon_tanggal label.error").hide();
            $("#mikon_tanggal .error").removeClass("error");
        });
    });
</script>