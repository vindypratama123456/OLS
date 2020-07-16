<?php $this->load->view("tshops/header"); ?>
<div class="container main-container headerOffset">
	<div class="row">
        <div class="breadcrumbDiv col-lg-12">
            <ul class="breadcrumb">
                <li><a href="<?php echo base_url(); ?>">Beranda</a></li>
                <li class="active"> Bantuan</li>
            </ul>
        </div>
    </div>
    <div class="row">
        <div class="col-lg-12 col-md-12  col-sm-12">
            <h1 class="section-title-inner">
                <span>Petunjuk Pemesanan</span>
            </h1>
            <!-- 16:9 aspect ratio -->
            <div class="embed-responsive embed-responsive-16by9">
                <iframe class="embed-responsive-item" src="//www.youtube.com/embed/eQMVOSIlvH4?rel=0&autoplay=1&showinfo=0&modestbranding=1" frameborder="0" allowfullscreen></iframe>
            </div>
        </div>
    </div>
    <div style="clear:both"><br ></div>
</div>
<?php $this->load->view("tshops/footer"); ?>