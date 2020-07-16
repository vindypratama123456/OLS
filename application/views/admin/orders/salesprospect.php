<style type="text/css">
    div.DTTT { margin-bottom: 0.5em; float: right; }
    div.dataTables_wrapper { clear: both; }
</style>

<div class="container-fluid">

    <!-- Page Heading -->
    <div class="row">
        <div class="col-lg-12">
            <h1 class="page-header">
                Pesanan
            </h1>
            <ol class="breadcrumb">
                <li>
                    <a href="<?php echo base_url() . ADMIN_PATH; ?>">Beranda</a>
                </li>
                <li class="active">
                    Pesanan
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
                <table class="table table-striped dt-responsive nowrap" id="<?php echo (true==$is_operator) ? 'datatable-region' : 'datatable'; ?>">
                    <thead>
                        <tr>
                            <th><center>Kode</center></th>
                            <th><center>Kelas</center></th>
                            <th><center>Kategori</center></th>
                            <th><center>Nama Sekolah</center></th>
                            <th><center>Provinsi</center></th>
                            <th><center>Kab/Kota</center></th>
                            <th><center>Tgl Pesanan</center></th>
                            <th><center>Total Harga</center></th>
                            <th><center>Status</center></th>
                            <th class="sorting_disabled"><center>Detil</center></th>
                        </tr>
                    </thead>
                </table>
            </div>
        </div>
    </div>
    <!-- /.row -->

</div>
<!-- /.container-fluid -->