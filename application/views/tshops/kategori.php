<?php $this->load->view("tshops/header"); ?>

<div class="container main-container headerOffset">
    <div class="row" style="margin-top:-30px;">
        <div class="col-lg-12 col-md-12 col-sm-12">
            <div class="w100 clearfix category-top">
                <h2><?php echo $title; ?></h2>
            </div>
            <div class="row  categoryProduct xsResponse clearfix">
            	<?php
            	foreach($data_product as $product){
            	?>
            	<div class="col-sm-3 col-lg-3 col-md-3 col-xs-6" style="margin-bottom:30px; min-height:370px;">
				    <div class="product">
				        <div class="image" style="padding-top:20px;">
				            <a href="<?php echo base_url(); ?>buku/detail/<?php echo $product->id_product."-".str_replace(" ", "-", preg_replace("/[^a-zA-Z0-9 ]/", "", $product->name)); ?>.html">
				            	<?php if($product->images == 1) { ?>
				            	<img src="<?php echo base_url()."assets/img/product/$product->id_product" ?>.jpg" alt="<?php echo $product->name; ?>" title="<?php echo $product->name; ?>" class="img-responsive">
				            	<?php
				            	}
				            	else{
				            	?>
				            	<img src="<?php echo base_url()."assets/img/product/" ?>no_image.png" alt="<?php echo $product->name; ?>" title="<?php echo $product->name; ?>" class="img-responsive">	
				            	<?php
				            	}
				            	?>
				            </a>
				        </div>
				        <div class="description">
				            <h4>
				            	<a href="<?php echo base_url(); ?>buku/detail/<?php echo $product->id_product."-".str_replace(" ", "-", preg_replace("/[^a-zA-Z0-9 ]/", "", $product->name)); ?>.html">
				            		<?php echo potongJudul($product->name, 40); ?>
				            	</a>
				           	</h4>
				           	<?php /*
				            <div class="grid-description">
				            	<p><?php echo $product->name; ?></p>
				            </div>
				            */ ?>
				        </div>
				    </div>
				</div>
            	<?php
            	}
            	?>
				<div style="clear:both;"></div>
				<div class="container">
            		<?php echo $links;?>
            	</div>
            </div>
        </div>
    </div>
</div>

<?php $this->load->view("tshops/footer"); ?>