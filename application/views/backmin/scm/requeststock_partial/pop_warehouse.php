<div class="modal-header">
    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
    <h4 class="modal-title">Pilih Gudang</h4>
</div>
<div class="modal-body">
    <div class="row">
        <h4>Judul Buku: <?php echo $product['name']; ?></h4>
        <h4>ISBN: <?php echo $product['reference']; ?></h4>
        <h4>Jumlah permintaan: <?php echo $request; ?></h4>
        <h4>Jumlah Permintaan sisa: <?php echo $sisa_request; ?></h4>
        <input type="hidden" id="request" name="request" value="<?php echo $request; ?>">
        <input type="hidden" id="sisa_request" name="sisa_request" value="<?php echo $sisa_request; ?>">
        <hr>
        <div class="table-responsive">
            <?php if($list_warehouse) { ?>
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th class="text-center" width="60%">Nama Gudang</th>
                        <th class="text-center" width="20%">Stok</th>
                        <th class="text-center" width="20%">Parsial</th>
                        <th class="text-center" width="20%">Non Parsial</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $total_stok = 0; ?>
                    <?php foreach($list_warehouse as $row) { ?>  
                    <?php $total_stok += $row->stok; ?>                            
                    <tr>
                        <td><?php echo $row->nama_gudang; ?></td>
                        <td class="text-center"><?php echo $row->stok; ?></td>
                        <td class="text-center">
                            <input type="hidden" class="pqty_check" name="pqty_check" data-id="<?php echo $id_order; ?>##<?php echo $product['id_product']; ?>##<?php echo $row->id_gudang; ?>##<?php echo $row->stok; ?>" value="<?php echo $row->stok; ?>">
                            <input type="text" class="form-control pqty" data-id="<?php echo $id_order; ?>##<?php echo $product['id_product']; ?>##<?php echo $row->id_gudang; ?>##<?php echo $row->stok; ?>" data-id_order="<?php echo $id_order; ?>" data-id_product="<?php echo $product['id_product']; ?>" data-id_gudang="<?php echo $row->id_gudang; ?>" data-nama_gudang="<?php echo $row->nama_gudang; ?>">
                        </td>
                        <td class="text-center">
                            <?php if($request<=$row->stok) { ?>
                            <button class="btn btn-default btn-rounded btn-condensed btn-sm pilih_gudang" data-dismiss="modal" data-id_order="<?php echo $id_order; ?>" data-id_product="<?php echo $product['id_product']; ?>" data-id_gudang="<?php echo $row->id_gudang; ?>" data-nama_gudang="<?php echo $row->nama_gudang; ?>"><span class="fa fa-check"></span></button>
                            <?php } else { echo "-"; } ?>
                        </td>
                    </tr>
                    <?php } ?>
                    <input type="hidden" id="total_stok" name="total_stok" = value="<?php echo $total_stok; ?>">
                </tbody>
            </table>
            <?php } ?>
        </div>
    </div>
</div>
<div class="modal-footer">
    <button type="button" class="btn pull-left btn-success btn_proses_warehouse" data-id_order="<?php echo $id_order; ?>" data-id_product="<?php echo $product['id_product']; ?>" data-id_gudang="<?php echo $row->id_gudang; ?>" data-nama_gudang="<?php echo $row->nama_gudang; ?>">Proses</button>
    <button type="button" class="btn btn-primary" data-dismiss="modal">Tutup</button>
</div>
<script type="text/javascript">
    $(document).ready(function(){
        // $(".pilih_gudang").click( function(){
        //     a = $(this).data('id_order');
        //     b = $(this).data('id_product');
        //     c = $(this).data('nama_gudang');
        //     d = $(this).data('id_gudang');
            
        //     $('#sp_'+a+'_'+b).html(c);
        //     $('#gd_'+a+'_'+b).val(d);
        //     $('input#ps_'+a+'_'+b).remove();

        //     if ($(".need_stock").length) {
        //         $("#submitDetail").hide();
        //     }
        //     else {
        //         $("#submitDetail").show();  
        //     }
        // });
        
        $(".pilih_gudang").click( function(){
            var id_gudang = [];
            var qty = [];

            a = $(this).data('id_order');
            b = $(this).data('id_product');
            c = $(this).data('nama_gudang');
            d = $(this).data('id_gudang');

            qty.push($('#sisa_request').val());
            id_gudang.push(d);
            
            $('#sp_'+a+'_'+b).html(c);
            $('#ig_'+a+'_'+b).val(id_gudang);
            $('#qty_'+a+'_'+b).val(qty);
            $('#gd_'+a+'_'+b).val(d);
            $('input#ps_'+a+'_'+b).remove();

            // console.log($(".need_stock").length);
            // console.log($(".qty").length);

            // if ($(".need_stock").length) {
            if (parseInt($(".qty").length ) <= parseInt($(".need_stock").length)) {
                $("#submitDetail").hide();
            }
            else {
                $("#submitDetail").show();  
            }
        });

        $(".btn_proses_warehouse").click(function(){
            a = $(this).data('id_order');
            b = $(this).data('id_product');
            c = $(this).data('nama_gudang');
            d = $(this).data('id_gudang');

            var id_gudang = [];
            var qty = [];
            var nama_gudang = [];
            var totalQty = 0;
            var sp = "";
            $('.pqty').each(function(){
                sp = "";
                if($(this).val()!="")
                {
                    sp = $(this).data('nama_gudang') + " : " + $(this).val();
                    qty.push($(this).val());
                    id_gudang.push($(this).data('id_gudang'));
                    nama_gudang.push(sp);
                    totalQty += parseInt($(this).val());
                }
            });
            // $('#sp_'+a+'_'+b).html(c);
            $('#sp_'+a+'_'+b).html(nama_gudang.join(", "));
            $('#gd_'+a+'_'+b).val(d);
            $('#ig_'+a+'_'+b).val(id_gudang);
            $('#qty_'+a+'_'+b).val(qty);

            // console.log($('#request').val() + " | " + totalQty);
            if($('#total_stok').val() < totalQty)
            {
                if(confirm('Stok belum mencukupi. Batalkan proses ?'))
                {
                    $('#modal_large').modal('hide');
                }
            }
            else
            {
                // console.log(typeof($('#sisa_request').val()));
                // console.log(typeof(totalQty));

                if($('#sisa_request').val() < totalQty)
                {
                    alert("Total qty melebihi permintaan. Periksa kembali qty.")
                }
                else if(parseInt($('#sisa_request').val()) == totalQty)
                {
                    $('input#ps_'+a+'_'+b).remove();
                    $('#modal_large').modal('hide');
                }
                else if(parseInt(totalQty) == 0)
                {
                    // $('input#ps_'+a+'_'+b).remove();
                    alert("Silahkan periksa quantity. quantity tidak boleh 0");
                    // $('#modal_large').modal('hide');
                }
                else
                {
                    // if(confirm('Stok belum mencukupi. Batalkan proses ?'))
                    // {
                    //     $('#modal_large').modal('hide');
                    // }
                    $('input#ps_'+a+'_'+b).remove();
                    $('#modal_large').modal('hide');
                }
            }
            // console.log(id_gudang);
            // console.log(qty);
            // if ($(".need_stock").length) {
            
            // console.log($(".need_stock").length);
            // console.log($(".qty").length);
            if (parseInt($(".qty").length) <= parseInt($(".need_stock").length)) {
                $("#submitDetail").hide();
            }
            else {
                $("#submitDetail").show();  
            }

        });

        $(".pqty").on('change', function(){
            // console.log($(this).data('id'));
            // var pqty_check = $('input[class="pqty_check" data-id="' + $(this).data('id') + '"]').val();
            var pqty_check = $('input.pqty_check[data-id="' + $(this).data('id') + '"]');
            
            if(parseInt($(this).val()) > parseInt(pqty_check.val()))
            {

                $(this).focus();
                $(this).val('');
                alert("Stok tidak mencukupi");
            }
        });
    });
</script>