<div class="container-fluid">
    <!-- Page Heading -->
    <div class="row">
        <div class="col-lg-12">
            <h1 class="page-header">Ongkos Kirim</h1>
            <ol class="breadcrumb">
                <li><a href="<?php echo base_url() . ADMIN_PATH; ?>">Beranda</a></li>
                <li><a href="<?php echo base_url() . ADMIN_PATH; ?>/product">Ongkos Kirim</a></li>
                <li class="active">Detail</li>
            </ol>
        </div>
    </div>
    <!-- /.row -->
    <div class="row">
        <div class="col-lg-12">
            <?php echo form_open('', 'data-action="' . base_url() . ADMIN_PATH . '/ongkir/detail_post" id="product_form" autocomplete="off"'); ?>
                <input type="hidden" name="id" value="<?=$detil['id']?>">
                <div class="form-group">
                    <div class="col-md-6"><br />
                        <label>Kode Buku</label>
                        <input type="text" class="form-control" name="kd_prop" value="<?=$detil['kd_prop']?>">
                    </div>
                </div>
                <div class="form-group">
                    <div class="col-md-8"><br />
                        <label>Kode Referensi</label>
                        <input type="text" class="form-control" name="provinsi" value="<?=$detil['provinsi']?>">
                    </div>
                </div>
                <div class="form-group">
                    <div class="col-md-8"><br />
                        <label>Judul</label>
                        <input type="text" class="form-control" name="kd_kab_kota" id="kd_kab_kota" value="<?=$detil['kd_kab_kota']?>">
                    </div>
                </div>
                <div class="form-group">
                    <div class="col-md-8"><br />
                        <label>Deskripsi</label>
                        <input type="text" class="form-control" name="kabupaten" id="kabupaten" value="<?=$detil['kabupaten']?>">
                    </div>
                </div>
                <div class="form-group">
                    <div class="col-md-8"><br />
                        <label>Supplier</label>
                        <input type="text" class="form-control" name="kd_kec" id="kd_kec" value="<?=$detil['kd_kec']?>">
                    </div>
                </div>
                <div class="form-group">
                    <div class="col-md-8"><br />
                        <label>Quantity</label>
                        <input type="text" class="form-control" name="kecamatan" id="kecamatan" value="<?=$detil['kecamatan']?>">
                    </div>
                </div>
                <div class="form-group">
                    <div class="col-md-8"><br />
                        <label>Harga 1</label>
                        <input type="text" class="form-control" name="tarif_per_kg_komp_eco_min30kg" id="tarif_per_kg_komp_eco_min30kg" value="<?=$detil['tarif_per_kg_komp_eco_min30kg']?>">
                    </div>
                </div>
                <div class="form-group">
                    <div class="col-md-8"><br />
                        <label>Harga 2</label>
                        <input type="text" class="form-control" name="tarif_per_kg_komp_reg_min1kg" id="tarif_per_kg_komp_reg_min1kg" value="<?=$detil['tarif_per_kg_komp_reg_min1kg']?>">
                    </div>
                </div>
                <div class="form-group">
                    <div class="col-md-8"><br />
                        <label>Harga 3</label>
                        <input type="text" class="form-control" name="tarif_per_kg_lainlain_eco_min30kg" id="tarif_per_kg_lainlain_eco_min30kg" value="<?=$detil['tarif_per_kg_lainlain_eco_min30kg']?>">
                    </div>
                </div>
                <div class="form-group">
                    <div class="col-md-8"><br />
                        <label>Harga 4</label>
                        <input type="text" class="form-control" name="tarif_per_kg_perlindungandiri_noncair_reg_min1kg" id="tarif_per_kg_perlindungandiri_noncair_reg_min1kg" value="<?=$detil['tarif_per_kg_perlindungandiri_noncair_reg_min1kg']?>">
                    </div>
                </div>
                <div class="form-group">
                    <div class="col-md-12">
                        <hr>
                    </div>
                </div>
                <div class="form-group">
                    <div class="col-md-12"><br />
                        <button type="submit" class="btn btn-success pull-left">Simpan</button>
                        <a href="<?php echo base_url().ADMIN_PATH; ?>/ongkir" class="btn btn-primary pull-right">Kembali</a>
                    </div>
                </div>
            <?php echo form_close(); ?>
        </div>
    </div>
    <!-- /.row -->
</div>
<!-- /.container-fluid -->
