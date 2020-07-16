<div class="container-fluid">

    <!-- Page Heading -->
    <div class="row">
        <div class="col-lg-12">
            <h1 class="page-header">
                Email Blacklist
            </h1>
            <ol class="breadcrumb">
                <li>
                    <a href="<?php echo base_url() . ADMIN_PATH; ?>">Beranda</a>
                </li>
                <li class="active">
                    Email Blacklist
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
            <a href="<?php echo base_url() . ADMIN_PATH . '/emailblacklist/add'; ?>">
                <button class="btn btn-success"><i class="glyphicon glyphicon-plus"></i> Tambah email blacklist</button>
            </a>
            <br/>
            <br/>
            <div class="table-responsive">
                <table class="table table-striped dt-responsive nowrap table-loader" id="datatableEmailBlacklist">
                    <thead>
                        <tr>
                            <th class="text-center">Alamat Email</th>
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
<script type="text/javascript">
    
$(document).ready(function(){
    $('#btnDelete').click(function(){
        alert('testing');
    });
});
</script>