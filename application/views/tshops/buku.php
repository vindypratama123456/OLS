<?php
$this->load->view("tshops/header");
?>
<div class="container main-container headerOffset">
    
    <div class="row transitionfx">
        <div class="col-lg-4 col-md-4 col-sm-4">
            <?php if($data_product->images == 1) { ?>
            <img src="<?php echo base_url()."assets/img/product/$data_product->id_product" ?>.jpg" alt="<?php echo $data_product->name; ?>" title="<?php echo $data_product->name; ?>" width="300px;" class="img-responsive">
            <?php } else { ?>
            <img src="<?php echo base_url()."assets/img/product/" ?>no_image.png" alt="<?php echo $data_product->name; ?>" title="<?php echo $data_product->name; ?>" width="300px;" class="img-responsive">
            <?php } ?>
        </div>
        <div class="col-lg-8 col-md-8 col-sm-8">
            <h1 class="product-title"><?php echo $data_product->name; ?></h1>
            <div class="row">
                <?php if(!$this->session->userdata('zona')) { ?>
                <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
                    <p>Harga Pemerintah</p>
                    <ul>
                    <?php
                    for ($i=1; $i <6 ; $i++) {
                        $price = 'price_'.$i;
                        echo '<li>Zona '.$i.': '.toRupiah($data_product->$price).'</li>';
                    } 
                    ?>
                    </ul>
                </div>
                <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
                    <p>Harga Retail</p>
                    <ul>
                    <?php
                    for ($i=1; $i <6 ; $i++) {
                        $price = 'non_r'.$i;
                        echo '<li>Zona '.$i.': '.toRupiah($data_product->$price).'</li>';
                    } 
                    ?>
                    </ul>
                </div>
                <?php } else { $zona = $this->session->userdata('zona'); ?>
                <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
                    <p>Harga Pemerintah</p>
                    <ul>
                    <?php
                        $price_z = 'price_'.$zona;
                        echo '<li>'.toRupiah($data_product->$price_z).'</li>';
                    ?>
                    </ul>
                </div>
                <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
                    <p>Harga Retail</p>
                    <ul>
                    <?php
                        $price_p = 'non_r'.$zona;
                        echo '<li>'.toRupiah($data_product->$price_p).'</li>';
                    ?>
                    </ul>
                </div>
                <?php } ?>
            </div>
            <?php
            if(!empty($data_product->reference) AND empty($this->session->userdata('id_customer'))){
            ?>
            <br />
            <div class="product-price">
                <a href="<?php echo base_url(); ?>pesanan/formpesanan" class="btn btn-lg btn-danger" title="Klik untuk melakukan pemesanan"><i class="fa fa-cart-plus" aria-hidden="true"></i> Pembelian Untuk Sekolah</a>
                &nbsp;&nbsp; 
                <a href="http://www.gramedia.com/search/?q=<?php echo $data_product->name; ?>" target="_blank" class="btn btn-lg btn-warning" title="Klik untuk melakukan pembelian via Gramedia.com"><i class="fa fa-paper-plane" aria-hidden="true"></i> Pembelian Untuk Umum</a>
            </div>
            <?php } ?>
            <br />
            <div class="clear"></div>
            <div class="product-tab w100 clearfix">
                <ul class="nav nav-tabs">
                    <li class="active"><a href="#details" data-toggle="tab">Details</a></li>
                    <li><a href="#deskripsi" data-toggle="tab">Deskripsi</a></li>
                </ul>
                <div class="tab-content">
                    <div class="tab-pane active" id="details">
                        <?php if($data_product->kode_buku!='') { ?><p><b>Kode Buku</b> : <?php echo $data_product->kode_buku; ?></p><?php } ?>
                        <?php if($data_product->reference!='') { ?><p><b>ISBN</b> : <?php echo $data_product->reference; ?></p><?php } ?>
                        <?php if($data_product->width!='' && $data_product->height!='') { ?><p><b>Ukuran</b> : <?php echo $data_product->width.' cm'; ?> x <?php echo $data_product->height.' cm'; ?></p> <?php } ?>
                        <?php if($data_product->pages!='') { ?><p><b>Halaman</b> : <?php echo $data_product->pages; ?></p><?php } ?>
                        <?php if($data_product->supplier!='') { ?><p><b>Penerbit</b> : <?php echo $data_product->supplier; ?></p><?php } ?>
                    </div>
                    <div class="tab-pane" id="deskripsi">
                        <p><?php echo $data_product->description; ?></p>
                    </div>
                </div>
                <br />
            </div>
            <div style="clear:both"></div>
        </div>
    </div>
</div>
<?php
$this->load->view("tshops/footer");
?>