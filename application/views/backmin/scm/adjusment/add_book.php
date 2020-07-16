<?php echo form_open(BACKMIN_PATH.'/scmadjusment/add_books_post', 'class="form-horizontal" id="frmadjusment" autocomplete="off"'); ?>
<div class="modal-header">
    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span>
    </button>
    <h4 class="modal-title">Tambah Buku</h4>
</div>
<div class="modal-body">
    <div class="row">
        <div class="col-lg-12">

            <!-- <input type="hidden" name="id" value="<?= $detil['id'] ?>"/> -->
            <input type="hidden" name="id_transaksi" value="<?= $id_transaksi ?>"/>
            <!-- <input type="hidden" name="old_qty" value="<?= $old_qty ?>"/> -->

            <table id="myTable" class="table table-bordered table-striped" width="100%">
                <thead>
                    <tr>
                        <th class="text-center" width="15%">Kode Buku</th>
                        <th class="text-center" width="50%">Judul Buku</th>
                        <th class="text-center" width="15%">Kelas</th>
                        <th class="text-center" width="15%">QTY</th>
                        <th class="text-center" width="10%">Action</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>
                            <input class="form-control kode_buku" data-count="1" type="text" id="kode_buku_1" name="kode_buku[]" value="">
                        </td>
                        <td>
                            <input class="form-control" data-count="1" type="text" id="judul_buku_1" name="judul_buku[]" value="" readonly="true">
                            <input class="form-control" data-count="1" type="hidden" id="id_buku_1" name="id_buku[]" value="" readonly="true">
                            <input class="form-control" data-count="1" type="hidden" id="berat_buku_1" name="berat_buku[]" value="" readonly="true">
                            <input class="form-control" data-count="1" type="hidden" id="harga_buku_1" name="harga_buku[]" value="" readonly="true">
                        </td>
                        <td>
                            <input class="form-control kelas" data-count="1" type="text" id="kelas_1" name="kelas[]" value="">
                        </td>
                        <td>
                            <input class="form-control qty" data-count="1" type="text" id="qty_1" name="qty[]" value="">
                        </td>
                        <td>
                            <button class="btn btn-success btn-add" data-count="1" type="button"><span class="fa fa-pencil"></span></button>
                        </td>
                    </tr>
                </tbody>
            </table>

        </div>
    </div>
</div>
<div class="modal-footer">
    <button type="submit" class="btn btn-success pull-left">Simpan</button>
    <button type="button" class="btn btn-primary" data-dismiss="modal">Tutup</button>
</div>
<?php echo form_close(); ?>

<script type='text/javascript' src='<?php echo assets_url('js'); ?>/jquery-validation/additional/accept.js'></script>
<script type="text/javascript" src="<?php echo assets_url_backmin('js'); ?>/plugins/jquery-filer/js/jquery.filer.js"></script>
<script type="text/javascript" src="<?php echo assets_url_backmin('js'); ?>/plugins/bootstrap/bootstrap-datepicker.js"></script>
<script type="text/javascript" src="<?php echo assets_url_backmin('js'); ?>/plugins/blueimp/jquery.blueimp-gallery.min.js"></script>
<script type="text/javascript" src="<?php echo assets_url_backmin('js'); ?>/plugins/dropzone//dropzone.min.js"></script>
<script type="text/javascript" src="<?php echo assets_url_backmin('js'); ?>/plugins/icheck/icheck.min.js"></script>

<script type="text/javascript">

$(document).ready(function(){
    $("#frmadjusment").on("keydown",".kode_buku",function(event){
        if(event.keyCode == 13) {
            event.preventDefault();

            var dataCount = event.target.dataset.count;
            var kode_buku = $("#kode_buku_" + dataCount).val();
            var qty = $("#qty_" + dataCount);

            var id_buku = $("#id_buku_" + dataCount);
            var judul_buku = $("#judul_buku_" + dataCount);
            var berat_buku = $("#berat_buku_" + dataCount);
            var harga_buku = $("#harga_buku_" + dataCount);
            var kelas = $("#kelas_" + dataCount);

            var data = {
                "kode_buku"     : kode_buku
            };

            if(kode_buku === "")
            {
                alert("Input kode buku !");
            }
            else
            {

            $.ajax({
                type: 'POST',
                data: data,
                dataType: 'json',
                url:  "<?php echo base_url(BACKMIN_PATH.'/scmadjusment/get_data_product') ?>",
                success:function(datas){
                    if(datas['buku'].length > 0){
                        id_buku.val(datas['buku'][0].id_product);
                        judul_buku.val(datas['buku'][0].name);
                        berat_buku.val(datas['buku'][0].weight);
                        kelas.val(datas['buku'][0].kelas);
                        if(datas['gudang'][0].id_site == 1)
                        {
                            harga_buku.val(datas['buku'][0].price_1);    
                        }
                        else if(datas['gudang'][0].id_site == 2)
                        {
                            harga_buku.val(datas['buku'][0].price_2);    
                        }
                        else if(datas['gudang'][0].id_site == 3)
                        {
                            harga_buku.val(datas['buku'][0].price_3);    
                        }
                        else if(datas['gudang'][0].id_site == 4)
                        {
                            harga_buku.val(datas['buku'][0].price_4);    
                        }
                        else if(datas['gudang'][0].id_site == 5)
                        {
                            harga_buku.val(datas['buku'][0].price_5);    
                        }
                        
                        qty.focus();
                    }
                    else
                    {
                        alert("Maaf data tidak ditemukan.");
                        judul_buku.val("");
                    }
                },
                error: function( jqXHR, exception ){
                    console.log(exception);
                }
            });
            }

            return false;
        }
    });

    $("#frmadjusment").on("change",".kode_buku",function(event){
        // if(event.keyCode == 13) {
            event.preventDefault();

            var dataCount = event.target.dataset.count;
            var kode_buku = $("#kode_buku_" + dataCount).val();
            var qty = $("#qty_" + dataCount);

            var id_buku = $("#id_buku_" + dataCount);
            var judul_buku = $("#judul_buku_" + dataCount);
            var berat_buku = $("#berat_buku_" + dataCount);
            var harga_buku = $("#harga_buku_" + dataCount);
            var kelas = $("#kelas_" + dataCount);

            var data = {
                "kode_buku"     : kode_buku,
            };

            if(kode_buku === "")
            {
                alert("Input kode buku !");
            }
            else
            {

            $.ajax({
                type: 'POST',
                data: data,
                dataType: 'json',
                url:  "<?php echo base_url(BACKMIN_PATH.'/scmadjusment/get_data_product') ?>",
                success:function(datas){
                    if(datas['buku'].length > 0){
                        id_buku.val(datas['buku'][0].id_product);
                        judul_buku.val(datas['buku'][0].name);
                        berat_buku.val(datas['buku'][0].weight);
                        kelas.val(datas['buku'][0].kelas);
                        if(datas['gudang'][0].id_site == 1)
                        {
                            harga_buku.val(datas['buku'][0].price_1);    
                        }
                        else if(datas['gudang'][0].id_site == 2)
                        {
                            harga_buku.val(datas['buku'][0].price_2);    
                        }
                        else if(datas['gudang'][0].id_site == 3)
                        {
                            harga_buku.val(datas['buku'][0].price_3);    
                        }
                        else if(datas['gudang'][0].id_site == 4)
                        {
                            harga_buku.val(datas['buku'][0].price_4);    
                        }
                        else if(datas['gudang'][0].id_site == 5)
                        {
                            harga_buku.val(datas['buku'][0].price_5);    
                        }
                        
                        qty.focus();
                    }
                    else
                    {
                        alert("Maaf data tidak ditemukan.");
                        judul_buku.val("");
                    }
                },
                error: function( jqXHR, exception ){
                    console.log(exception);
                }
            });
            }

            return false;
        // }
    });

    $("#frmadjusment").on("keydown",".qty",function(event){
        if(event.keyCode == 13) {
            event.preventDefault();

            var dataCount = event.target.dataset.count;
            var id_buku = $("#id_buku_" + dataCount).val();
            var kode_buku = $("#kode_buku_" + dataCount).val();
            var qty = $("#qty_" + dataCount);

            if(qty.val() === '')
            {
                alert("Input qty !");
            }
            else
            {
                var data = {
                    id_buku : id_buku
                }

                $.ajax({
                    type: 'POST',
                    data: data,
                    dataType: 'json',
                    url:  "<?php echo base_url(BACKMIN_PATH.'/scmadjusment/get_stock') ?>",
                    success:function(datas){
                        // console.log(datas);
                        var total = Number(qty.val()) + Number(datas[0].stok_fisik);
                        if(total < 0)
                        {
                            alert("Maaf stok tidak mencukupi. sisa stok : " + datas[0].stok_fisik);
                        }
                        else
                        {
                            $('.btn-add').focus(); 
                        }
                    }
                });
            }

            return false;
        }
    });

    $("#frmadjusment").on("change",".qty",function(event){
            event.preventDefault();

            var dataCount = event.target.dataset.count;
            var id_buku = $("#id_buku_" + dataCount).val();
            var kode_buku = $("#kode_buku_" + dataCount).val();
            var qty = $("#qty_" + dataCount);

            if(qty.val() === '')
            {
                alert("Input qty !");
            }
            else
            {
                var data = {
                    id_buku : id_buku
                }

                $.ajax({
                    type: 'POST',
                    data: data,
                    dataType: 'json',
                    url:  "<?php echo base_url(BACKMIN_PATH.'/scmadjusment/get_stock') ?>",
                    success:function(datas){
                        console.log(datas);
                        var total = Number(qty.val()) + Number(datas[0].stok_fisik);
                        if(total < 0)
                        {
                            alert("Maaf stok tidak mencukupi. sisa stok : " + datas[0].stok_fisik);
                            qty.focus();
                        }
                        else
                        {
                            $('.btn-add').focus(); 
                        }
                    }
                });
            }

            return false;
    });

    $(document).on("click",".btn-add", function(e){
        var dataCount = $(this).data('count');
        var eCountNew = dataCount+1;

        var kode_buku = $("#kode_buku_" + dataCount).val();
        var qty = $("#qty_" + dataCount).val();

        if(kode_buku == "")
        {
            alert("Data buku belum diinput.");
        }
        else if(qty == "")
        {
            alert("Jumlah buku belum diinput.");
        }
        else
        {

        // REMOVE CLASS BUTTON
        $(this).removeClass('btn-success btn-add');
        $(this).addClass('btn-danger btn-delete');
        $(this).html('<span class="fa fa-trash"></span>');

        // ADD TABLE ROW
        $('#myTable > tbody:last-child').append('<tr><td><input class="form-control kode_buku" data-count="' + eCountNew + '" type="text" id="kode_buku_' + eCountNew + '" name="kode_buku[]" value=""></td><td><input class="form-control" data-count="' + eCountNew + '" type="hidden" id="id_buku_' + eCountNew + '" name="id_buku[]" value="" readonly="true"><input class="form-control" data-count="' + eCountNew + '" type="text" id="judul_buku_' + eCountNew + '" name="judul_buku[]" value="" readonly="true"><input class="form-control" data-count="' + eCountNew + '" type="hidden" id="berat_buku_' + eCountNew + '" name="berat_buku[]" value="" readonly="true"><input class="form-control" data-count="' + eCountNew + '" type="hidden" id="harga_buku_' + eCountNew + '" name="harga_buku[]" value="" readonly="true"></td><td><input class="form-control kelas" data-count="' + eCountNew + '" type="text" id="kelas_' + eCountNew + '" name="kelas[]" value=""></td><td><input class="form-control qty" data-count="' + eCountNew + '" type="text" id="qty_' + eCountNew + '" name="qty[]" value=""></td><td><button class="btn btn-success btn-add" data-count="' + eCountNew + '" type="button"><span class="fa fa-pencil"></button></td></tr>');
        }

        // SET FOCUS
        $("#kode_buku_" + eCountNew).focus();
    });

    $(document).on("click",".btn-delete", function(e){
        if(confirm("Yakin akan menghapus data ? ") == true)
        {
            $(this).closest('tr').remove();
        }
    });

    // $("#frmadjusment").submit(function(){
    //     var conf = confirm('Yakin ingin melanjutkan proses pesanan?');
    //     if(conf)
    //     {

    //     }
    //     return false;
    // });

    $("#frmadjusment").validate({
        rules : {
            // catatan : "required",
            // tanggal : "required"
        },
        submitHandler: function(form) {
            
            var conf = confirm('Yakin ingin melanjutkan proses pesanan ?');
            if(conf)
            {
                return true;
            }
            return false;
        }
    });
});
</script>
