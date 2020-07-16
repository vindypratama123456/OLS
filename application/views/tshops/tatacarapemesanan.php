<?php $this->load->view("tshops/header"); ?>

<div class="container main-container headerOffset">
    <div class="row" style="margin-top:-30px;">
        <div class="col-lg-12 col-md-12 col-sm-12">
            <div class="row" style="margin:30px 0;">
                <h1>Tata Cara Pemesanan</h1>
                <div class="well" style="background-color:#fff;">
                    <ul class="list-number">
                        <li>Klik menu <b>'Login'</b> di sebelah kanan atas</li>
                        <li>Masukkan <b>Email</b> dan <b>Password</b> sesuai dengan yang terdaftar di Dapodik</li>
                        <li>Masuk ke form pesanan</li>
                        <li>Selanjutnya, untuk melakukan pemesanan terdapat 2 (dua) cara:
                            <ul class="alpha">
                                <li>Melalui isian jumlah produk yang ingin dipesan dengan cara memasukkan jumlah/kuantitas produk yang ingin dipesan. Anda dapat memasukkan jumlah yang sama untuk semua item. (Hanya berlaku untuk buku non-guru. Untuk buku guru, jumlah diisi manual)</li>
                                <li>Melalui unggah berkas excel form pesanan
                                    <ul class="list">
                                        <li>Klik tombol <b>'Pesan buku via unggah berkas excel form pesanan'</b></li>
                                        <li>Klik tombol <b>'Unduh berkas excel form pesanan'</b> untuk melihat format contoh berkas excel, selanjutnya pada berkas hasil unggahan tersebut buka dan sesuaikan jumlah produk yang ingin dipesan lalu simpan</li>
                                        <li>Klik tombol <b>'Browse...'</b>, pilih lokasi penyimpanan berkas csv yang sudah disiapkan, lalu klik tombol <b>'Unggah'</b></li>
                                        <li>Jika mengalami kendala koneksi internet, form pesanan dapat dikirimkan melalui fax atau email kami. <b><a href="<?php echo base_url(); ?>halaman/hubungi-kami">Klik disini</a></b></li>
                                    </ul>
                                </li>
                            </ul>
                        </li>
                        <li>Klik tombol <b>'Proses Pesanan'</b></li>
                        <li>Setelah pemesanan (jumlah produk yang dipesan) telah sesuai, akan muncul halaman <b>'Konfirmasi Pesanan'</b>, periksa kembali dan pastikan data yang tampil sudah sesuai dengan keinginan anda. Jika sudah benar, centang <b>'saya telah menyetujui syarat dan ketentuan'</b>. Selanjutnya klik tombol <b>'Konfirmasi Pesanan'</b> (kanan bawah)</li>
                        <li>Muncul daftar pesanan yang memuat tulisan 'Pesanan telah berhasil dibuat, kami akan segera menghubungi Anda' dan 'Kode Pesanan'</li>
                        <li>Apabila anda ingin memeriksa detil pesanan, klik tombol 'Detil' yang ada di sebelah kanan</li>
                        <li>Proses pemesanan selesai. Kami akan mengkonfirmasi pesanan Anda. Jika mengalami kendala pemesanan online, Bapak/Ibu dapat menghubungi ke nomor kontak kami <b><a href="<?php echo base_url(); ?>halaman/hubungi-kami">Klik disini</a></b></li>
                        <li>Tahap selanjutnya, pengiriman buku ke sekolah -> buku diterima di sekolah (Berita Acara Serah Terima ditandatangani) -> proses pembayaran</li>
                        <li>Pembayaran dapat dilakukan melalui transfer ke <b>Nomor Virtual Account BRI</b> atas <b>nama PT. Gramedia</b>, untuk lebih detil mengenai tata cara pembayaran, silahkan <b><u><a href="<?php echo base_url(); ?>halaman/tata-cara-pembayaran-bri-virtual-account" target="_blank">KLIK TAUTAN INI</a></u></b></li>
                        <li>Untuk melihat status pesanan, ulangi langkah 1 & 2, pilih menu 'Pesanan Saya', klik kode pesanan atau 'Detil', maka akan mucul detil pesanan anda</li>
                    </ul>
                </div>
				
                <div class="col-lg-12 col-md-12 col-sm-12">
                    <div class="row">
                        <div class="embed-responsive embed-responsive-16by9">
                            <iframe width="560" height="315" src="https://www.youtube.com/embed/6S88F5nkgU4?rel=0&modestbranding=0&showinfo=0" frameborder="0" allowfullscreen></iframe>
                        </div>
                    </div>
                    <br />
                    <div class="row">
                        <center>
                            <a href="https://drive.google.com/open?id=0Bx2ETZKUWrHEejZ0UnhEdTdhb3M" class="btn btn-lg btn-primary" target="_blank">Unduh Video Tutorial</a>
                        </center>
                    </div>
                </div>

                <div class="col-lg-12 col-md-12 col-sm-12">
                    <div class="row">
                        <img src="<?php echo base_url()?>/assets/img/tata-cara-pemesanan.png" class="img-responsive">
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php $this->load->view("tshops/footer"); ?>