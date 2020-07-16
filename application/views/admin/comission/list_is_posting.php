<div class="container-fluid">
    <!-- Page Heading -->
    <div class="row">
        <div class="col-lg-12">
            <h1 class="page-header">
                Komisi Diposting
            </h1>
            <ol class="breadcrumb">
                <li>
                    <a href="<?php echo base_url() . ADMIN_PATH; ?>">Beranda</a>
                </li>
                <li>
                    <a href="#">Komisi</a>
                </li>
                <li class="active">
                    Diposting
                </li>
            </ol>
        </div>
    </div>
    <!-- /.row -->
    <div class="row">
        <div class="col-lg-12">
            <?php 
            if($this->session->flashdata('msg_success_commision')) {
                echo notif('success',$this->session->flashdata('msg_success_commision'));
            }
            ?>
        </div>
    </div>
    <div class="row">
        <div class="col-lg-12">
            <div class="table-responsive">
                <table class="table-striped dt-responsive data-table" data-table-def="datatableIsPosting" id="datatableIsPosting">
                    <thead>
                        <tr>
                            <th class="text-center">No. PD</th>
                            <th class="text-center sum">Total Mitra</th>
                            <th class="text-center">Tipe Bank</th>
                            <th class="text-center">Perusahaan</th>
                            <th class="text-center sum">Total Komisi</th>
                            <th class="text-center">Tgl Transfer</th>
                        </tr>
                    </thead>
                </table>
            </div>
        </div>
    </div>
    <!-- /.row -->
</div>
<!-- /.container-fluid -->