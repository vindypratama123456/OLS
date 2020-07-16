<div class="container-fluid">
    <!-- Page Heading -->
    <div class="row">
        <div class="col-lg-12">
            <h1 class="page-header">
                Partner <a href="<?php echo base_url(ADMIN_PATH.'/partner/add'); ?>" class="btn btn-primary pull-right" data-toggle="modal" data-target="#myModal"><i class="fa fa-plus-square"></i> Tambah Data</a>
            </h1>
            <ol class="breadcrumb">
                <li>
                    <a href="<?php echo base_url() . ADMIN_PATH; ?>">Beranda</a>
                </li>
                <li class="active">
                    Partner
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
        </div>
    </div>
    <div class="row">
        <div class="col-lg-12">
            <div class="table-responsive">
                <table class="table table-hover dt-responsive nowrap" id="datatableCatalog">
                    <thead class="thead-dark">
                        <tr>
                            <th width="10%">No.</th>
                            <th >Nama</th>
                            <th width="15%">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                    <?php
                    $no=0;
                    foreach($partner as $d) 
                    {
                        $no++;
                    ?>
                        <tr>
                            <td><?php echo $no; ?></td>
                            <td><?php echo $d->name; ?></td>
                            <td>
                                <a href="<?php echo base_url(ADMIN_PATH.'/partner/update/'.$d->id); ?>" class="btn btn-warning m-1" data-toggle="modal" data-target="#myModal">Ubah</a>
                                <a data-href="<?php echo base_url(ADMIN_PATH.'/partner/delete'); ?>" data-id="<?= $d->id; ?>" class="btn btn-danger m-1 btn-delete">Hapus</a>
                            </td>
                        </tr>
                    <?php } ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <!-- /.row -->
</div>
<!-- /.container-fluid -->

<div class="modal fade" id="myModal" role="dialog" aria-labelledby="myModalLabel" data-keyboard="false" data-backdrop="static">
    <div class="modal-dialog modal-lg">
      <div class="modal-content">
      </div>
    </div>
</div>

<script type="text/javascript">
    $(document).on('show.bs.modal','#myModal', function () {
            // $("#name").focus();
        $(this).find('[autofocus]').focus();
    })
    // $('#myModal').on('shown.bs.modal', function() {
    //     $(this).find('[autofocus]').focus();
    // });
    
    $(document).ready(function(){
        $(".btn-delete").on("click", function(){
            if(confirm("Yakin menghapus data partner ?"))
            {
                var elem = $(this);
                var data = {
                    'id': elem.data('id')
                };
                $.ajax({
                    type: "POST",
                    data: data,
                    dataType: "json",
                    url: elem.data('href'),
                    success: function (datas) {
                        console.log(datas);
                        if (datas.success == 'true') {
                            window.location.href = BASE_URL + 'partner';
                        } else {
                            bootAlert(datas.message);
                            $('button').attr('disabled', false);
                        }
                    }
                });
            }
            // alert($(this).attr('href'));
            return false;
        });
    });
</script>