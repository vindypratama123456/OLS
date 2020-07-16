<?php $this->load->view("tshops/header"); ?>

<div class="container main-container headerOffset">
    <div class="row" style="margin-top:-30px;">
        <div class="col-lg-12 col-md-12 col-sm-12">
            <div class="row" style="margin:30px 0;">
                <h1>Testimoni Pembeli</h1>
                <?php
                foreach($testimoni as $itemTestimoni){
                ?>
                <div class="well">
                    <h2><?php echo $itemTestimoni->school_name; ?></h2>
                    <?php  if ($itemTestimoni->comment) echo '<p>Komentar: ' . $itemTestimoni->comment . '</p>'; ?>
                    <p>Rating:  <strong style="color:#00FF00;"><?php if($itemTestimoni->rating == 1) { echo 'Sangat Megecewakan'; } elseif($itemTestimoni->rating == 2) { echo 'Megecewakan'; } elseif($itemTestimoni->rating == 3) { echo 'Cukup Memuaskan'; }  elseif($itemTestimoni->rating == 4) { echo 'Memuaskan'; }  elseif($itemTestimoni->rating == 5) { echo 'Sangat Memuaskan'; } ?></strong></p>
                    <p>Pada: <?php echo $itemTestimoni->created_at; ?></p>
                </div>
                <?php
                }
                ?>
            </div>
            <div style="clear:both;"></div>
            <div class="container">
                <?php echo $links;?>
            </div>
        </div>
    </div>
</div>

<?php $this->load->view("tshops/footer"); ?>