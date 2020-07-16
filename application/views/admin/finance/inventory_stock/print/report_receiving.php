<!DOCTYPE html>
<html moznomarginboxes mozdisallowselectionprint>
    <head>
        <title>Laporan Penerimaan Barang Total Posting</title>
        <link rel="stylesheet" type="text/css" href="<?php echo css_url('admin/inventory_stock.css'); ?>"/>
    </head>
    <body onload="window.print();">

        <div class="row header" align="center" style="padding-bottom: 10px;">
            <span class="judul-1">Laporan Penerimaan Barang</span><br>
            <span class="judul-2">TRANSACTION DATE&nbsp;:&nbsp;<?php echo $start_date . '&nbsp;s/d&nbsp;' . $end_date; ?></span>
        </div>

        <div class="row">
            <table class="table table-receiving" cellpadding="5">
                <thead>
                    <tr>
                        <th>ITEM NO</th>
                        <th>DESCRIPTION</th>
                        <th>QUANTITY</th>
                        <th>UNIT COST</th>
                        <th>BY.MATERIAL</th>
                        <th>BY.JASA</th>
                        <th>TAX</th>
                        <th>TOTAL</th>
                    </tr>
                </thead>
                <tbody>
                    <?php 
                        $grand_total_qty    = 0; 
                        $grand_total        = 0; 
                        foreach ($report as $id_periode => $periode) { 
                    ?>
                    <tr class="tr_header">
                        <td colspan="8"><strong>PERIODE</strong> &nbsp; : &nbsp; <?php echo strtoupper($periode['nama_periode']); ?></td>
                    </tr>
                    <?php 
                        foreach ($periode['row1'] as $id_gudang => $gudang) { 
                            $subtotal_qty   = 0;
                            $subtotal       = 0;
                    ?>
                    <tr class="tr_header">
                        <td colspan="8"><strong>LOKASI</strong> &nbsp; : &nbsp; <?php echo $id_gudang . ' - ' . strtoupper(substr($gudang['nama_gudang'], 7)); ?></td>
                    </tr>
                    <?php foreach ($gudang['row2'] as $id_bulan => $bulan) { ?>
                    <tr class="tr_header">
                        <td colspan="8"><strong>BULAN</strong> &nbsp; : &nbsp; <?php echo strtoupper($bulan['nama_bulan']); ?></td>
                    </tr>
                    <?php foreach ($bulan['row3'] as $rows => $detail) { ?>
                    <tr class="tr_detail">
                        <td><?php echo $detail['kode_buku'] ?></td>
                        <td><?php echo strtoupper($detail['judul_buku']) ?></td>
                        <td class="text-right"><?php echo rupiah($detail['jumlah_buku'], 0) ?></td>
                        <td class="text-right"><?php echo rupiah($detail['unit_cost'], 2) ?></td>
                        <td class="text-right"><?php echo rupiah($detail['total_cost'], 2) ?></td>
                        <td class="text-right"><?php echo '0' ?></td>
                        <td class="text-right"><?php echo '0' ?></td>
                        <td class="text-right"><?php echo rupiah($detail['total_cost'], 2) ?></td>
                    </tr>
                    <?php   
                            $subtotal_qty       += $detail['jumlah_buku'];
                            $subtotal           += $detail['total_cost'];
                            $grand_total_qty    += $detail['jumlah_buku'];
                            $grand_total        += $detail['total_cost'];
                                }
                            }
                    ?>
                    <tr class="tr_subtotal">
                        <td colspan="2" class="text-right"><strong>Sub Total</strong></td>
                        <td colspan="" class="text-right"><?php echo rupiah($subtotal_qty, 0); ?></td>
                        <td colspan="" class="text-right">-</td>
                        <td colspan="" class="text-right"><?php echo rupiah($subtotal, 2); ?></td>
                        <td colspan="" class="text-right">0</td>
                        <td colspan="" class="text-right">0</td>
                        <td colspan="" class="text-right"><?php echo rupiah($subtotal, 2); ?></td>
                    </tr>
                    <?php
                        }
                    }
                    ?>
                    <tr class="tr_grand">
                        <td colspan="2" class="text-right"><strong>Grand Total</strong></td>
                        <td colspan="" class="text-right"><?php echo rupiah($grand_total_qty, 0); ?></td>
                        <td colspan="" class="text-right">-</td>
                        <td colspan="" class="text-right"><?php echo rupiah($grand_total, 2); ?></td>
                        <td colspan="" class="text-right">0</td>
                        <td colspan="" class="text-right">0</td>
                        <td colspan="" class="text-right"><?php echo rupiah($grand_total, 2); ?></td>
                    </tr>
                </tbody>
            </table>
        </div>
    </body>
</html>
