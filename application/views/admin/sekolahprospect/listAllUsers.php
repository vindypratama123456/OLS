<div class="container-fluid">
    <div class="row">
        <div class="col-lg-12">
            <h1 class="page-header">
                <?php echo $page_title; ?>
            </h1>
            <ol class="breadcrumb">
                <li>
                    <a href="<?php echo base_url() . ADMIN_PATH; ?>">Beranda</a>
                </li>
                <li class="active">
                    <?php echo $page_title; ?>
                </li>
            </ol>
        </div>
    </div>
    <div class="row">
        <div class="col-lg-12">
            <?php echo $this->session->flashdata('message') ?>
            <div class="table-responsive">
                <table class="table table-striped dt-responsive nowrap table-loader" id="datatableAllUsers">
                    <thead>
                        <tr>
                            <th class="text-center">NPSN</th>
                            <th class="text-center">Nama Sekolah</th>
                            <th class="text-center">Propinsi</th>
                            <th class="text-center">Kab/Kota</th>
                            <th class="text-center">Status</th>
                            <th class="text-center">Nama Mitra</th>
                            <th class="text-center">Tanggal<br/>Expired</th>
                        </tr>
                    </thead>
                </table>
            </div>
        </div>
    </div>
</div>