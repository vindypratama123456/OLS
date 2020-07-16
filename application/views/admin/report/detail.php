<div class="container-fluid">

    <!-- Page Heading -->
    <div class="row">
        <div class="col-lg-12">
            <h1 class="page-header">
                Pesanan #<?php echo $detil['reference']; ?>
            </h1>
            <ol class="breadcrumb">
                <li>
                    <a href="<?php echo base_url() . ADMIN_PATH; ?>">Beranda</a>
                </li>
                <li class="active">
                    <a href="<?php echo base_url() . ADMIN_PATH; ?>/report">Laporan</a>
                </li>
                <li class="active">
                    Detil
                </li>
            </ol>
        </div>
    </div>
    <!-- /.row -->

    <div class="row">
        <div class="col-lg-12">

            <div class="panel panel-default">
                <!-- Default panel contents -->
                <div class="panel-heading"><h4>Pelanggan</h4></div>
                <div class="panel-body">
                    <!-- List group -->
                    <ul class="list-group">
                        <li class="list-group-item">NPSN: <?php echo $customer['no_npsn']; ?></li>
                        <li class="list-group-item">Sekolah: <?php echo $customer['school_name']; ?></li>
                        <li class="list-group-item">Zona: <?php echo $customer['zona']; ?></li>
                        <li class="list-group-item">Email: <?php echo $customer['email']; ?></li>
                        <li class="list-group-item"></li>
                        <li class="list-group-item">
                            Status Pesanan: 
                            <?php
                                $param = array(
                                    'field' => 'name',
                                    'table' => 'order_state',
                                    'key' => 'id_order_state',
                                    'data' => $detil['current_state']
                                );
                                echo '<b>'.get_data($param).'</b>'; 
                            ?>                            
                        </li>
                    </ul>
                </div>
            </div>


            <div class="panel panel-default">
                <!-- Default panel contents -->
                <div class="panel-heading"><h4>Alamat</h4></div>
                <div class="panel-body">
                    <?php
                        echo $customer['alamat'].'<br />';
                        echo $customer['desa'].', ';
                        echo $customer['kecamatan'].', ';
                        echo $customer['kabupaten'].', ';
                        echo $customer['provinsi'].' - ';
                        echo $customer['kodepos'].'<br />';
                        echo 'Telpon: '.$customer['phone'];
                    ?>
                </div>
            </div>

            <div class="panel panel-default">
                <!-- Default panel contents -->
                <div class="panel-heading"><h4>Produk</h4></div>
                <div class="panel-body">
                    <div class="table-responsive">
                        <?php if($listproducts) { ?>
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th class="text-center">No</th>
                                    <th class="text-center">Produk</th>
                                    <th class="text-center">Jumlah</th>
                                    <!--
                                    <th class="text-center">Harga Awal</th>
                                    <th class="text-center">Diskon</th>
                                    -->
                                    <th class="text-center">Harga Satuan</th>
                                    <th class="text-center">Harga Total</th>
                                </tr>
                            </thead>
                            <tbody>
                            <?php 
                                $i=1; 
                                $tot_item = 0;
                                $tot_price = 0;
                                foreach($listproducts as $row) { 
                            ?>
                                <tr>
                                    <td class="text-center"><?php echo $i; ?></td>
                                    <td>
                                        <?php
                                            $params = array(
                                                'field' => 'reference',
                                                'table' => 'product',
                                                'key' => 'id_product',
                                                'data' => $row->product_id,
                                            );
                                            $isbn = get_data($params);
                                            echo $row->product_name;
                                            echo '<br />(ISBN: '.$isbn.')';
                                        ?>
                                    </td>
                                    <td class="text-center"><?php echo $row->product_quantity; ?></td>
                                    <?php /*
                                    <td class="text-right"><?php echo toRupiah($row->product_price); ?></td>
                                    <td class="text-center"><?php echo $row->reduction_percent; ?></td>
                                    */ ?>
                                    <td class="text-right"><?php echo toRupiah($row->unit_price); ?></td>
                                    <td class="text-right"><?php echo toRupiah($row->total_price); ?></td>
                                </tr>
                            <?php 
                                $i++;
                                $tot_item += $row->product_quantity;
                                $tot_price += $row->total_price;
                                } 
                            ?>
                                <tr><td colspan="<?php echo ($detil['current_state']<3) ? '6' : '5'; ?>"></td></tr>
                                <tr>
                                    <td colspan="4" class="text-right">Total Jumlah</td>
                                    <td class="text-right"<?php echo ($detil['current_state']<3) ? ' colspan="2"' : ''; ?>><?php echo $tot_item; ?></td>
                                </tr>
                                <tr>
                                    <td colspan="4" class="text-right"><strong>Total Harga</strong></td>
                                    <td class="text-right"<?php echo ($detil['current_state']<3) ? ' colspan="2"' : ''; ?>>
                                        <strong><?php echo toRupiah($tot_price); ?></strong>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="text-right" colspan="<?php echo ($detil['current_state']<3) ? '6' : '5'; ?>">
                                        <i>Terbilang: <b><?php echo terbilang($tot_price); ?></b></i>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                        <?php } ?>
                    </div>
                    <?php if($listhistory) { ?>
                    <p>Riwayat Perubahan Pesanan</p>
                    <table class="table table-bordered">
                        <thead>
                            <th class="text-center">Tanggal/Waktu</th>
                            <th class="text-center">Nama Produk</th>
                            <th class="text-center">Jumlah Sebelum</th>
                            <th class="text-center">Jumlah Sesudah</th>
                            <th class="text-center">Petugas</th>
                        </thead>
                        <tbody>
                        <?php foreach ($listhistory as $history) { ?>
                            <tr>
                                <td class="text-center"><?php echo $history->tanggal; ?></td>
                                <td><?php echo $history->produk; ?></td>
                                <td class="text-center"><?php echo $history->sebelum; ?></td>
                                <td class="text-center"><?php echo $history->setelah; ?></td>
                                <td class="text-center"><?php echo $history->admin; ?></td>
                            </tr>
                        <?php } ?>
                        </tbody>
                    </table>
                    <?php } if($liststatus) { ?>
                    <p>Riwayat Status Pesanan</p>
                    <table class="table table-bordered">
                        <?php foreach ($liststatus as $row) { ?>
                            <tr>
                                <td width="45%"><?php echo $row->order_state; ?></td>
                                <td width="30%"><?php echo $row->employee; ?></td>
                                <td width="25%" class="text-center"><?php echo $row->tanggal; ?></td>
                            </tr>
                        <?php } ?>
                    </table>
                    <?php } ?>
                </div>
            </div>
            
            <div class="panel panel-default">
                <div class="panel-body">
                    <div class="col-md-12">
                        <a href="<?php echo base_url().ADMIN_PATH; ?>/report" class="btn btn-primary btn-lg pull-left">Kembali</a>
                    </div>
                </div>
            </div>

        </div>
    </div>
    <!-- /.row -->

</div>
<!-- /.container-fluid -->