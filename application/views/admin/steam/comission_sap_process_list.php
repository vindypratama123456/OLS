<style type="text/css">
    div.DTTT { margin-bottom: 0.5em; float: right; }
    div.dataTables_wrapper { clear: both; }
</style>
<div class="container-fluid">
    <!-- Page Heading -->
    <div class="row">
        <div class="col-lg-12">
            <h1 class="page-header">
                Daftar Proses SAP
            </h1>
            <ol class="breadcrumb">
                <li>
                    <a href="<?php echo base_url() . ADMIN_PATH; ?>">Beranda</a>
                </li>
                <li>
                    <a href="#">Komisi</a>
                </li>
                <li class="active">
                    Daftar Proses SAP
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
            if($this->session->flashdata('msg_error_commision')) {
                echo notif('danger',$this->session->flashdata('msg_error_commision'));
            }
            ?>
        </div>
    </div>
    <div class="row">
        <div class="col-lg-12">
            <div class="table-responsive">
                <?php if (in_array($this->adm_level, $this->backoffice_superadmin_area)) { ?>
                <button type="button" class="btn btn-primary btn-large" onclick="processBatch()">
                    <i class="glyphicon glyphicon-th-list"></i>&nbsp;&nbsp;<b>Proses Batch</b>
                </button>
                <?php } ?>
                <table class="table table-striped dt-responsive wrap table-loader" data-table-def="datatableComissionSapProcess" id="datatableComissionSapProcess">
                    <thead>
                        <tr>
                            <!-- <th class="text-center"><input type="checkbox" id="check_all" value=""></th> -->
                            <th class="text-center">No. Proses</th>
                            <th class="text-center">Tanggal Proses</th>
                            <th class="text-center">Total Omset</th>
                            <th class="text-center">Total Komisi</th>
                            <th class="text-center">Catatan</th>
                            <!-- <th class="text-center">Total Harga</th>
                            <th class="text-center">Sales</th>
                            <th class="text-center">Komisi (%)</th>
                            <th class="text-center">PPh (%)</th>
                            <th class="text-center">Komisi (Rp.)</th>
                            <th class="text-center">Tgl Pengajuan</th> -->
                        </tr>
                    </thead>
                </table>
                <!-- <button class="btn btn-success btn-pilih" type="button">Proses SAP</button> -->
            </div>
        </div>
    </div>
    <!-- /.row -->
</div>
<!-- /.container-fluid -->
<script type="text/javascript">
    window.onload = function() {
        var reloading = sessionStorage.getItem("reloading");
        if (reloading) {
            sessionStorage.removeItem("reloading");
            download_sap(sessionStorage.getItem("link_download"));
        }
    }   

    function download_sap(link)
    {
        sessionStorage.removeItem("link_download");
        window.open(link,
          '_blank' 
          );
    } 
</script>