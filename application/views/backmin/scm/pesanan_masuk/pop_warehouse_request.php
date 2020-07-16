<div class="modal-header">
    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
    <h4 class="modal-title">Pilih Gudang</h4>
</div>
<div class="modal-body">
    <div class="row">
        <h4>Judul Buku: <?php echo $product['name']; ?></h4>
        <h4>ISBN: <?php echo $product['reference']; ?></h4>
        <h4>Jumlah Pesan: <?php echo $request; ?></h4>
        <hr>
        <div class="table-responsive">
            <?php if($list_warehouse) { ?>
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th class="text-center" width="60%">Nama Gudang</th>
                        <th class="text-center" width="20%">Stok</th>
                        <th class="text-center" width="20%">Opsi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach($list_warehouse as $row) { ?>                                       
                    <tr>
                        <td><?php echo $row->nama_gudang; ?></td>
                        <td class="text-center"><?php echo $row->stok; ?></td>
                        <td class="text-center">
                            <?php if($request<=$row->stok) { ?>
                            <button class="btn btn-default btn-rounded btn-condensed btn-sm pilih_gudang" data-dismiss="modal" data-id_order="<?php echo $id_order; ?>" data-id_product="<?php echo $product['id_product']; ?>" data-id_gudang="<?php echo $row->id_gudang; ?>" data-nama_gudang="<?php echo $row->nama_gudang; ?>"><span class="fa fa-check"></span></button>
                            <?php } else { echo "-"; } ?>
                        </td>
                    </tr>
                    <?php } ?>
                </tbody>
            </table>
            <?php } ?>
        </div>
    </div>
</div>
<div class="modal-footer">
    <button type="button" class="btn btn-primary" data-dismiss="modal">Tutup</button>
</div>
<script type="text/javascript">
    $(document).ready(function(){
        $(".pilih_gudang").click( function(){
            a = $(this).data('id_order');
            b = $(this).data('id_product');
            c = $(this).data('nama_gudang');
            d = $(this).data('id_gudang');
            
            $('#sp_'+a+'_'+b).html(c);
            $('#gd_'+a+'_'+b).val(d);
            $('input#ps_'+a+'_'+b).remove();

            if ($(".need_stock").length) {
                $("#submitDetail").hide();
            }
            else {
                $("#submitDetail").show();  
            }
        });
    });
</script>