<?php $this->load->view("tshops/header"); ?>

    <div class="row">
        <a href="http://data.dikdasmen.kemdikbud.go.id/sso/auth/?response_type=code&amp;client_id=bkk13ad&amp;state=100100&amp;redirect_uri=<?php echo base_url() ?>akunsaya/verify">
            <img src="<?php echo base_url() ?>assets/img/main-banner-buku-sekolah.jpg"
                 style="display:cover; width:100%; margin-top:80px;" alt="Buku Sekolah Gramedia"> </a>
    </div>
    <div class="container-fluid" style="padding:40px 0px;">
        <div class="row">
            <div class="col-md-1"></div>
            <div class="col-md-2 col-sm-3 col-xs-6"><img
                        src="<?php echo base_url() ?>assets/img/icon-pemesanan-praktis-gramediacom.png"
                        class="img-responsive" alt="Pemesanan Praktis"></div>
            <div class="col-md-2 col-sm-3 col-xs-6"><img
                        src="<?php echo base_url() ?>assets/img/icon-produk-diantar-gramediacom.png"
                        class="img-responsive" alt="Produk Diantar"></div>
            <div class="col-md-2 col-sm-3 col-xs-6"><img
                        src="<?php echo base_url() ?>assets/img/icon-layanan-bantuan-gramediacom.png"
                        class="img-responsive" alt="Layanan Bantuan"></div>
            <div class="col-md-2 col-sm-3 col-xs-6"><img
                        src="<?php echo base_url() ?>assets/img/icon-jaringan-luas-gramediacom.png"
                        class="img-responsive" alt="Jaringan Luas"></div>
            <div class="col-md-2 col-sm-3 col-xs-6"><img src="<?php echo base_url() ?>assets/img/icon bank-1-01.png"
                                                         class="img-responsive" alt="Bank"></div>
            <div class="col-md-1"></div>
        </div>
    </div>
    <div class="row" style="background:url(<?php echo base_url() ?>assets/img/footer.png); background-size: cover;">
        <div class="container main-container headerOffset">
            <div class="col-md-12">
                <h1 style="color:#FFF; text-align: center; font-weight:bold;">Pembelian produk di Buku Sekolah
                    Gramedia<br>sangatlah mudah</h1>
                <br>
                <p style="color:#FFF; font-size: 20px; line-height: 25px; text-align: center;">Pelajari lebih lanjut
                    bagaimana cara<br> mendapatkan produk Buku Sekolah Gramedia di bawah ini</p>
                <br><br>
                <p align="center"><a href="<?php echo base_url() ?>halaman/tatacarapemesanan"
                                     style="text-transform: uppercase; font-size:20px; padding: 15px 25px; background-color:#4ec67f;"
                                     class="btn btn-default btn-lg;">Tata Cara Pemesanan</a></p>
            </div>
        </div>
    </div>

<?php $this->load->view("tshops/footer"); ?>