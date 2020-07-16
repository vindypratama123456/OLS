<?php $this->load->view("tshops/header"); ?>
<div class="container main-container headerOffset">
   <?php if($this->session->flashdata('order_success')) { ?>
   <div class="row">
        <div class="col-lg-12 col-md-12 col-sm-12">
            <ul class="mybreadcrumb">
                <li><a href="<?php echo base_url(); ?>"><i class="fa fa-home"></i> Beranda</a></li>
                <li><a href="<?php echo base_url(); ?>pesanan/formpesanan" id="bc_teks_2013">Form Pesanan Buku</a></li>
                <li><a href="#">Konfirmasi Pesanan</a></li>
                <li><a href="#" class="active">Selesai</a></li>
            </ul>
        </div>
    </div>
    <br />
    <?php } ?>
    <div class="row">
        <div class="col-lg-12 col-md-12 col-sm-12">
            <h1 class="section-title-inner"><span><i class="fa fa-list"></i> Daftar Pesanan Saya</span></h1>
        </div>
    </div>
    <div class="row">
        <div class="col-lg-12 col-md-12 col-sm-12">
            <?php
                echo $this->session->flashdata('order_success');
                echo $this->session->flashdata('message');
            ?>
            <div class="row userInfo">
                <div class="col-xs-12 col-sm-12">
                    <div class="cartContent w100">
                        <table class="cartTable table-responsive" style="width:100%">
                            <tbody>
                                <tr class="CartProduct cartTableHeader">
                                    <td style="text-align:left; padding-left:10px;">Kode Pesanan</td>
                                    <td style="text-align:center">Kelas</td>
                                    <td style="text-align:center">Kategori</td>
                                    <td style="width:15%">Total Pembayaran</td>
                                    <td style="width:20%">Status Pesanan</td>
                                    <td style="width:20%">Tanggal Pemesanan</td>
                                    <td>Detil</td>
                                </tr>
                                <?php foreach($pesanan as $dataPesanan) { ?>
                                <tr class="CartProduct">
                                    <td style="text-align:left; padding-left:10px;"><?php echo $dataPesanan->reference; ?></td>
                                    <td style="text-align:center"><?php echo $dataPesanan->category; ?></td>
                                    <td style="text-align:center"><?php echo $dataPesanan->type; ?></td>
                                    <td style="text-align:right;"><?php echo toRupiah($dataPesanan->total_paid); ?></td>
                                    <td style="text-align:center;"><span class="label <?php echo $dataPesanan->label; ?>" style="font-size:14px;padding:4px 7px;"><?php echo $dataPesanan->order_state_name; ?></span></td>
                                    <td style="text-align:center;"><?php echo $dataPesanan->date_add; ?></td>
                                    <td><a href="<?php echo base_url()?>pesanan/detail/<?php echo $dataPesanan->id_order; ?>" class="btn btn-inverse">Detil</a></td>
                                </tr>
                                <?php } ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php $this->load->view("tshops/footer"); ?>