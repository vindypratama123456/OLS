<div class="container-fluid">
    <!-- Page Heading -->
    <div class="row">
        <div class="col-lg-12">
            <h1 class="page-header">Form Order</h1>
            <ol class="breadcrumb">
                <li><a href="<?php echo base_url() . ADMIN_PATH; ?>">Beranda</a></li>
                <li><a href="<?php echo base_url() . ADMIN_PATH; ?>/product">Order</a></li>
                <li class="active">Add</li>
            </ol>
        </div>
    </div>
    <!-- /.row -->
    <div class="row">
        <div class="col-lg-12">
            <?php echo form_open('', 'data-action="' . base_url() . ADMIN_PATH . '/steam/order_add_post" id="order_steam_form" autocomplete="off"'); ?>
                <!-- <input type="hidden" name="id_product" value="<?=$detil['id_product']?>"> -->

                <div class="form-group">
                    <div class="col-md-8"><br>
                        <label class="control-label">Kode Pesanan</label>
                        <input class="form-control" id="id_order" name="id_order" placeholder="Masukkan kode pesanan" type="text">
                    </div>
                </div>
                <div class="form-group">
                    <div class="col-md-8"><br>
                        <label>Customer</label>
                        <select id="customer" name="customer" class="form-control">
                            <option value="">Silahkan pilih customer</option>
                        </select>
                    </div>
                </div>
                <div class="form-group"> 
                    <div class="col-md-8"><br>
                        <label>Sales</label>
                        <select id="sales" name="sales" class="form-control">
                            <option value="">Silahkan pilih sales</option>
                        </select>
                    </div>
                </div>
                <div class="form-group">
                    <div class="col-md-8"><br>
                        <label class="control-label">Total Order ( Rp. )</label>
                        <input class="form-control" id="total_paid" name="total_paid" placeholder="Masukkan total order" type="text">
                    </div>
                </div>
                <div class="form-group">
                    <div class="col-md-8"><br>
                        <label>Tanggal Order</label>
                        <div class="input-group date" id="dtpicker_paid">
                            <input type="text" class="form-control" id="date_add" name="date_add" placeholder="YYYY-MM-DD HH:mm:ss">
                            <span class="input-group-addon">
                                <span class="glyphicon glyphicon-calendar"></span>
                            </span>
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <div class="col-md-8"><br>
                        <button class="btn btn-success btn-large" id="submit" type="submit">Proses Order</button><br><br><br>
                    </div>
                </div>
                
            <?php echo form_close(); ?>
        </div>
    </div>
    <!-- /.row -->
</div>
<!-- /.container-fluid -->

