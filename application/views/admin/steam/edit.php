<div class="container-fluid">
    <!-- Page Heading -->
    <div class="row">
        <div class="col-lg-12">
            <h1 class="page-header">Produk</h1>
            <ol class="breadcrumb">
                <li><a href="<?php echo base_url() . ADMIN_PATH; ?>">Beranda</a></li>
                <li><a href="<?php echo base_url() . ADMIN_PATH; ?>/product">Produk</a></li>
                <li class="active">Detail</li>
            </ol>
        </div>
    </div>
    <!-- /.row -->
    <div class="row">
        <div class="col-lg-12">
            <?php echo form_open('', 'data-action="' . base_url() . ADMIN_PATH . '/product/detail_post" id="product_form" autocomplete="off"'); ?>
                <input type="hidden" name="id_product" value="<?=$detil['id_product']?>">
                <div class="form-group">
                    <div class="col-md-6"><br />
                        <label>Kode Buku</label>
                        <input type="text" class="form-control" name="kode_buku" value="<?=$detil['kode_buku']?>">
                    </div>
                </div>
                <div class="form-group">
                    <div class="col-md-8"><br />
                        <label>Kode Referensi</label>
                        <input type="text" class="form-control" name="reference" value="<?=$detil['reference']?>">
                    </div>
                <div class="form-group">
                    <div class="col-md-8">
                        <label>Kategori</label>
                        <select id="id_category_default" name="id_category_default" class="form-control">
                            <?php
                            foreach ($kategori as $item) {
                                $selected = ($item->id_category == $detil['id_category_default']) ? ' selected' : '';
                                echo '<option value="' . $item->id_category . '" ' . $selected . '>' . $item->name . '</option>';
                            }
                            ?>
                        </select>
                    </div>
                </div>
                <div class="form-group">
                    <div class="col-md-8"><br />
                        <label>Judul</label>
                        <input type="text" class="form-control" name="name" id="name" value="<?=$detil['name']?>">
                    </div>
                </div>
                <div class="form-group">
                    <div class="col-md-8"><br />
                        <label>Deskripsi</label>
                        <input type="text" class="form-control" name="description" id="description" value="<?=$detil['description']?>">
                    </div>
                </div>
                <div class="form-group">
                    <div class="col-md-8"><br />
                        <label>Supplier</label>
                        <input type="text" class="form-control" name="supplier" id="supplier" value="<?=$detil['supplier']?>">
                    </div>
                </div>
                <div class="form-group">
                    <div class="col-md-8"><br />
                        <label>Quantity</label>
                        <input type="text" class="form-control" name="quantity" id="quantity" value="<?=$detil['quantity']?>">
                    </div>
                </div>
                <div class="form-group">
                    <div class="col-md-8"><br />
                        <label>Harga 1</label>
                        <input type="text" class="form-control" name="price_1" id="price_1" value="<?=$detil['price_1']?>">
                    </div>
                </div>
                <div class="form-group">
                    <div class="col-md-8"><br />
                        <label>Harga 2</label>
                        <input type="text" class="form-control" name="price_2" id="price_2" value="<?=$detil['price_2']?>">
                    </div>
                </div>
                <div class="form-group">
                    <div class="col-md-8"><br />
                        <label>Harga 3</label>
                        <input type="text" class="form-control" name="price_3" id="price_3" value="<?=$detil['price_3']?>">
                    </div>
                </div>
                <div class="form-group">
                    <div class="col-md-8"><br />
                        <label>Harga 4</label>
                        <input type="text" class="form-control" name="price_4" id="price_4" value="<?=$detil['price_4']?>">
                    </div>
                </div>
                <div class="form-group">
                    <div class="col-md-8"><br />
                        <label>Harga 5</label>
                        <input type="text" class="form-control" name="price_5" id="price_5" value="<?=$detil['price_5']?>">
                    </div>
                </div>
                <div class="form-group">
                    <div class="col-md-8"><br />
                        <label>Harga Non 1</label>
                        <input type="text" class="form-control" name="non_r1" id="non_r1" value="<?=$detil['non_r1']?>">
                    </div>
                </div>
                <div class="form-group">
                    <div class="col-md-8"><br />
                        <label>Harga Non 2</label>
                        <input type="text" class="form-control" name="non_r2" id="non_r2" value="<?=$detil['non_r2']?>">
                    </div>
                </div>
                <div class="form-group">
                    <div class="col-md-8"><br />
                        <label>Harga Non 3</label>
                        <input type="text" class="form-control" name="non_r3" id="non_r3" value="<?=$detil['non_r3']?>">
                    </div>
                </div>
                <div class="form-group">
                    <div class="col-md-8"><br />
                        <label>Harga Non 4</label>
                        <input type="text" class="form-control" name="non_r4" id="non_r4" value="<?=$detil['non_r4']?>">
                    </div>
                </div>
                <div class="form-group">
                    <div class="col-md-8"><br />
                        <label>Harga Non 5</label>
                        <input type="text" class="form-control" name="non_r5" id="non_r5" value="<?=$detil['non_r5']?>">
                    </div>
                </div>
                <div class="form-group">
                    <div class="col-md-8"><br />
                        <label>Lebar</label>
                        <input type="text" class="form-control" name="width" id="width" value="<?=$detil['width']?>">
                    </div>
                </div>
                <div class="form-group">
                    <div class="col-md-8"><br />
                        <label>Panjang</label>
                        <input type="text" class="form-control" name="height" id="height" value="<?=$detil['height']?>">
                    </div>
                </div>
                <div class="form-group">
                    <div class="col-md-8"><br />
                        <label>Berat</label>
                        <input type="text" class="form-control" name="weight" id="weight" value="<?=$detil['weight']?>">
                    </div>
                </div>
                <div class="form-group">
                    <div class="col-md-8"><br />
                        <label>Halaman</label>
                        <input type="text" class="form-control" name="pages" id="pages" value="<?=$detil['pages']?>">
                    </div>
                </div>
                <div class="form-group">
                    <div class="col-md-8 hidden-xs"><br />
                        <label>Koli</label>
                        <input type="text" class="form-control" name="capacity" id="capacity" value="<?=$detil['capacity']?>">
                    </div>
                </div>
                <div class="form-group">
                    <div class="col-md-8"><br />
                        <label>Aktif</label><br />
                        <label class="radio-inline">
                            <input name="active" value="1" type="radio"<?php if ($detil['active']==1) { echo ' checked'; } ?>>Aktif
                        </label>
                        <label class="radio-inline">
                            <input name="active" value="0" type="radio"<?php if ($detil['active']==0) { echo ' checked'; } ?>>Nonaktif
                        </label>
                    </div>
                </div>
                <div class="form-group">
                    <div class="col-md-8"><br />
                        <label>Enable</label><br />
                        <label class="radio-inline">
                            <input name="enable" value="1" type="radio"<?php if ($detil['enable']==1) { echo ' checked'; } ?>>Ya
                        </label>
                        <label class="radio-inline">
                            <input name="enable" value="0" type="radio"<?php if ($detil['enable']==0) { echo ' checked'; } ?>>Tidak
                        </label>
                    </div>
                </div>
                <div class="form-group">
                    <div class="col-md-8"><br />
                        <label class="control-label">Gambar</label>
                        <input type="file" name='gambar' class="filestyle" data-icon="false" id="fileInput" >
                    </div>
                </div>
                <div class="form-group">
                    <div class="col-md-8">
                        <small><font color="#f00">Catatan </font>: biarkan kosong jika tidak ingin mengubah gambar product</small>
                        <br /><br />
                        <img id="image-preview" src="<?php echo base_url('assets/img/product/').$detil['id_product'].'.jpg'; ?>" alt="image preview"/>
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
                        <a href="<?php echo base_url().ADMIN_PATH; ?>/product" class="btn btn-primary pull-right">Kembali</a>
                    </div>
                </div>
            <?php echo form_close(); ?>
        </div>
    </div>
    <!-- /.row -->
</div>
<!-- /.container-fluid -->
