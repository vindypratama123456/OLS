<footer>
    <div class="footer" id="footer">
        <div class="container">
            <div class="row">
                <div class="col-lg-3  col-md-3 col-sm-6 col-xs-12">
                    <h3> Layanan pelanggan </h3>
                    <ul>
                        <li class="supportLi">
                            <h4>Hubungi kami di :</h4><br/>
                            <h4><a class="inline" href="callto:+622144837547"> <strong> <i class="fa fa-phone"> </i>
                                        (021) 5481487</strong> </a></h4>
                            <h4><a class="inline" href="mailto:cs@gramediaprinting.com"> <i
                                            class="fa fa-envelope-o"> </i> cs@gramediaprinting.com </a></h4>
                        </li>
                    </ul>
                </div>
                <div class="col-lg-3  col-md-3 col-sm-6 col-xs-12">
                    <h3> Kategori </h3>
                    <ul>
                        <li><a href="<?php echo base_url(); ?>kategori/buku/3-buku-teks-2013">Katalog</a></li>
                    </ul>
                </div>
                <div class="col-lg-3  col-md-3 col-sm-6 col-xs-12">
                    <h3> Informasi </h3>
                    <ul>
                        <li><a href="<?php echo base_url(); ?>halaman/tatacarapemesanan"> Tata Cara Pemesanan </a></li>
                        <li><a href="<?php echo base_url(); ?>halaman/tata-cara-pembayaran-bri-virtual-account"> Tata
                                Cara Pembayaran </a></li>
                        <li><a href="<?php echo base_url(); ?>halaman/syarat-ketentuan"> Syarat & Ketentuan </a></li>
                    </ul>
                </div>
                <?php if ( ! empty($this->session->userdata('id_customer'))) { ?>
                    <div class="col-lg-3  col-md-3 col-sm-6 col-xs-12">
                        <h3> Pesanan </h3>
                        <ul>
                            <li><a href="<?php echo base_url(); ?>pesanan"> Pesanan Saya </a></li>
                        </ul>
                    </div>
                <?php } ?>
            </div>
        </div>
    </div>
    <div class="footer-bottom">
        <div class="container">
            <p class="pull-left">Gramedia.com &copy; <?php echo date('Y'); ?>. Hak cipta dilindungi undang-undang.</p>
        </div>
    </div>
</footer>
<style>label.error {
        font-size: 12px;
        font-weight: normal;
        color: #FF0000;
    }</style>
<script src="<?php echo assets_url_fo(); ?>js/jquery.parallax-1.1.js?v=<?php echo date('YmdHis'); ?>"></script>
<script src="<?php echo assets_url_fo(); ?>js/helper-plugins/jquery.mousewheel.min.js?v=<?php echo date('YmdHis'); ?>"></script>
<script src="<?php echo assets_url_fo(); ?>js/jquery.mCustomScrollbar.js?v=<?php echo date('YmdHis'); ?>"></script>
<script src="<?php echo assets_url_fo(); ?>js/grids.js?v=<?php echo date('YmdHis'); ?>"></script>
<script src="<?php echo assets_url_fo(); ?>js/owl.carousel.min.js?v=<?php echo date('YmdHis'); ?>"></script>
<script src="<?php echo assets_url_fo(); ?>js/jquery.minimalect.min.js?v=<?php echo date('YmdHis'); ?>"></script>
<script src="<?php echo assets_url_fo(); ?>js/bootstrap.touchspin.js?v=<?php echo date('YmdHis'); ?>"></script>
<script src="<?php echo assets_url_fo(); ?>js/script.js?v=<?php echo date('YmdHis'); ?>"></script>
<script src="<?php echo js_url('admin/plugins/select2/js/select2.min.js?v='.date('YmsHis')); ?>"></script>
<script src="<?php echo js_url('admin/bootstrap-datepicker.min.js?v='.date('YmdHis')); ?>"></script>
<script src="<?php echo js_url('admin/bootbox.min.js?v='.date('YmsHis')); ?>"></script>
<script src="<?php echo js_url('admin/common.js?v='.date('YmsHis')); ?>"></script>
<?php if (getenv('CI_ENV') == 'production') { ?>
    <script>
        (function (i, s, o, g, r, a, m) {
            i['GoogleAnalyticsObject'] = r;
            i[r] = i[r] || function () {
                (i[r].q = i[r].q || []).push(arguments)
            }, i[r].l = 1 * new Date();
            a = s.createElement(o),
                m = s.getElementsByTagName(o)[0];
            a.async = 1;
            a.src = g;
            m.parentNode.insertBefore(a, m)
        })(window, document, 'script', 'https://www.google-analytics.com/analytics.js', 'ga');
        //ga('create', 'UA-80245043-1', 'auto');
        ga('create', 'UA-133449783-1', 'auto');
        ga('send', 'pageview');
    </script>
    <?php if ($this->uri->segment(1) != 'pesanan') { ?>
        <script type="text/javascript">
            var Tawk_API = Tawk_API || {}, Tawk_LoadStart = new Date();
            (function () {
                var s1 = document.createElement("script"), s0 = document.getElementsByTagName("script")[0];
                s1.async = true;
                s1.src = 'https://embed.tawk.to/574157c85327b06d42979ddb/default';
                s1.charset = 'UTF-8';
                s1.setAttribute('crossorigin', '*');
                s0.parentNode.insertBefore(s1, s0);
            })();
        </script>
    <?php }
} ?>
</body>
</html>