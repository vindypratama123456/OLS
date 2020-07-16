<?php
$this->load->view("tshops/header");
?>
<div class="container main-container headerOffset">
	<div class="row">
        <div class="breadcrumbDiv col-lg-12">
            <ul class="breadcrumb">
                <li><a href="<?php echo base_url(); ?>">Beranda</a></li>
                <li class="active"> Alamat</li>
            </ul>
        </div>
    </div>
    <div class="row">
        <div class="col-lg-12 col-md-12  col-sm-12">
            <h1 class="section-title-inner"><span><i class="fa fa-list"></i> Alamat</span></h1>
            <!--
            <div style="margin-bottom:20px;">
            	<a class="btn btn-primary" href="<?php echo base_url()?>alamat/tambah/"><i class="fa fa-plus"></i> Tambah alamat baru</a>
            </div>
            -->
	    	<?php
	    	foreach($alamat as $data){
	    	?>
	    	<style></style>
	        <div class="col-lg-5 col-md-12  col-sm-12" style="margin-right:50px; margin-bottom:50px; padding:20px; border-radius:4px; border:1px solid #eaeaea; background:#f0f0f0;">
	        	<div style="font-size:20px; line-height:30px; text-transform:uppercase;"><?php echo $data->alias; ?></div>
	        	<div style="font-size:15px; line-height:30px;"><?php echo $data->provinsi; ?>, <?php echo $data->kab_kota; ?></div>
	        	<div style="font-size:13px; line-height:30px;"><?php echo $data->kecamatan; ?>, <?php echo $data->kelurahan; ?></div>
	        	<div style="font-size:11px; line-height:30px;"><?php echo $data->address; ?>, <?php echo $data->postcode; ?></div>
	        	<div style="margin-top:10px;">
	        		<a class="btn btn-info" href="<?php echo base_url()?>alamat/edit/<?php echo $data->id_address?>"> Ubah </a> 
	        		<!--
	        		&nbsp; 
	        		<a class="btn btn-danger" href="<?php echo base_url()?>alamat/hapus/<?php echo $data->id_address?>" onclick="return confirm('Apakah anda yakin akan menghapus alamat <?php echo $data->alias ?>?');">Hapus</a>
	        		-->
	        	</div>
	        </div>
	    	<?php } ?>
	    </div>
	</div>
</div>
<?php
$this->load->view("tshops/footer");
?>