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
                <li class="active">
                    Bank
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
            <a href="<?php echo base_url() . ADMIN_PATH . '/bank/add'; ?>">
                <button class="btn btn-success"><i class="glyphicon glyphicon-plus"></i> Tambah Bank</button>
            </a>
            <br/>
            <br/>
            <div class="table-responsive">
                <table class="table table-striped dt-responsive nowrap table-loader" id="datatableBank">
                    <thead>
                        <tr>
                            <th class="text-center">Kode Bank</th>
                            <th class="text-center">Nama Bank</th>
                            <th class="text-center">Alias</th>
                            <th class="text-center">Status</th>
                            <th class="text-center">Aksi</th>
                        </tr>
                    </thead>
                </table>
            </div>
        </div>
    </div>
    <!-- /.row -->

</div>
<!-- /.container-fluid -->