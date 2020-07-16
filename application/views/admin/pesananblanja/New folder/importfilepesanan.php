<?php
$this->load->view("tshops/header");
?>
<div class="container main-container headerOffset">
	<div class="row">
        <div class="breadcrumbDiv col-lg-12">
            <ul class="breadcrumb">
                <li><a href="<?php echo base_url(); ?>">Beranda</a></li>
                <li><a href="<?php echo base_url('pesanan/formpesanan'); ?>">Form Pesanan</a></li>
                <li class="active">Impor file pesanan</li>
            </ul>
        </div>
    </div>
    <div class="row">
        <div class="col-lg-12 col-md-12 col-sm-12">
            <h1 class="section-title-inner"><span><i class="fa fa-file-excel-o"></i> Impor file pesanan</span></h1>
        </div>
    </div>
    <div class="row">

        <div class="col-lg-12 col-md-12 col-sm-12">
            <div class="row userInfo">
                <div class="col-xs-12 col-sm-12">
                    <!-- <br />
                    <a href="<?php echo base_url() ?>assets/template/<?php echo $this->session->userdata('jenjang').'/'.$this->session->userdata('zona'); ?>/template_form_pesanan.xlsx" class="btn btn-primary"><i class="fa fa-download"></i> Unduh file format/contoh excel</a>
                    <br /><br /> -->
                    <p>Silahkan impor file excel pesanan</p>
                    <?php echo form_open(base_url() . 'pesananblanja/konfirmasipesananupload', 'name="import_file_pesanan" enctype="multipart/form-data" method="post"'); ?>
                        <div class="form-group">
                            <label>File excel pesanan</label>
                            <input type="file" style="padding: 10px; border:1px solid #EAEAEA;" name="file_csv_pesanan" id="file_csv_pesanan" required autofocus>
                        </div>
                        <!-- <input type="hidden" name="cust_kabupaten" id="cust_kabupaten" value="<?php echo $customer->kabupaten; ?>">
                        <input type="hidden" name="cust_phone" id="cust_phone" value="<?php echo $customer->phone; ?>"> -->
                        <button type="submit" class="btn btn-primary"><i class="fa fa-upload"></i> Unggah</button>
                	<?php echo form_close(); ?>
                </div>
            </div>
            <br />
        </div>
    </div>
</div>
<style>
    select{
        padding:10px;
        background: #fafafa;
        border: 1px solid #eaeaea;
        font-size: 15px;
    }
</style>
<?php
$this->load->view("tshops/footer");
?>