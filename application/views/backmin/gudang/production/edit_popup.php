<?php echo form_open('', 'class="form-horizontal" id="edit_production_order" autocomplete="off"'); ?>

<div class="modal-header">
    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span>
    </button>
    <h4 class="modal-title">Profil Sekolah</h4>
</div>
<div class="modal-body">
    <div class="row">
        <div class="col-lg-12">
            <div class="form-group">
                <div class="col-sm-12">
                    <div class="form-group">
                        <div class="col-md-8">
                            <label>No. OEF</label>
                            <input type="hidden" name="id" value="<?= $detail['id'] ?>"/>
                            <input type="text" name="no_oef" class="form-control" value="<?= $detail['no_oef'] ?>" readonly>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="col-md-8">
                            <label>Gudang</label>
                            <select id="id_gudang" name="id_gudang" class="form-control">
                                <option value=''>- Pilih Gudang -</option>
                                <?php foreach ($gudang as $data) { ?>
                                <option value="<?php echo $data->id_gudang; ?>" <?php if($data->id_gudang == $detail['id_gudang']){ echo "selected"; } ?>> <?php echo $data->nama_gudang; ?> </option>
                                <?php } ?>
                            </select>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="col-md-12">
                            <label>Kode Buku</label>
                            <input type="text" class="form-control" name="kode_buku" id="kode_buku" value="<?= $detail['kode_buku'] ?>">
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="col-md-12">
                            <label>Judul</label><br>
                            <input type="text" class="form-control" name="judul" id="judul" value="<?= $detail['judul'] ?>" readonly>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="col-md-12">
                            <label>Jumlah Request</label><br>
                            <input type="text" class="form-control" name="jumlah_request" value="<?= $detail['jumlah_request'] ?>">
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="col-md-6">
                            <label>Jumlah Produksi</label>
                            <input type="text" class="form-control" name="jumlah_kirim" id="jumlah_kirim" value="<?= $detail['jumlah_kirim'] ?>" readonly>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="modal-footer">
    <button type="submit" class="btn btn-success pull-left">Simpan</button>
    <button type="button" class="btn btn-primary" data-dismiss="modal">Tutup</button>
</div>
<?php echo form_close(); ?>

<script type="text/javascript">
    
    $(document).ready(function () {
        $('#edit_production_order').on('keyup keypress', function(e) {
            var keyCode = e.keyCode || e.which;
            if (keyCode === 13) { 
                e.preventDefault();
                return false;
            }
        });

        $("#kode_buku").on('keyup keypress', function(e) {
            var keyCode = e.keyCode || e.which;
            if (keyCode === 13) { 
                e.preventDefault();
                var data = {
                    kode_buku : $(this).val()
                }
                $.ajax({
                    type: "POST",
                    data: data,
                    dataType: "json",
                    url: BASE_URL + "backmin/gudangproduction/get_data_buku",
                    success: function (datas) {
                        if(datas != undefined)
                        {
                            $("#judul").val(datas.name);
                        }
                        else
                        {
                            alert("Maaf data order produksi tidak ditemukan.");
                            return false;
                        }
                    }
                    
                });
            }
        });

        $("#kode_buku").on('change', function(e){
            e.preventDefault();
            var data = {
                kode_buku : $(this).val()
            }

            $.ajax({
                type: "POST",
                data: data,
                dataType: "json",
                url: BASE_URL + "backmin/gudangproduction/get_data_buku",
                success: function (datas) {
                    if(datas != undefined)
                    {
                        $("#judul").val(datas.name);
                    }
                    else
                    {
                        alert("Maaf data buku tidak ditemukan.");
                        return false;
                    }
                }
                
            });
        });

        $('#edit_production_order').submit(function (e) {
            e.preventDefault();
            var data = {
                kode_buku : $("#kode_buku").val()
            }

            $.ajax({
                type: "POST",
                data: data,
                dataType: "json",
                url: BASE_URL + "backmin/gudangproduction/get_data_buku",
                success: function (datas) {
                    if(datas != undefined)
                    {
                        $("#judul").val(datas.name);
                        var conf = confirm('Yakin ingin memperbarui data order produksi?');
                        if (conf) {
                            $('button').attr('disabled', true);
                            $.ajax({
                                type: "POST",
                                data: $("#edit_production_order").serialize(),
                                dataType: "json",
                                url: BASE_URL + 'backmin/gudangproduction/detailOrderUpdatePost',
                                success: function (datas) {
                                    console.log(datas);
                                    if (datas.success == 'true') {
                                        $('.modal').modal('hide').data('bs.modal', null);
                                        window.location.reload(true);
                                        // window.location = BASE_URL+datas.redirect;
                                    } else {
                                        // bootAlert(datas.message);
                                        $('button').attr('disabled', false);
                                    }
                                }
                            });
                            return false;
                        }
                    }
                    else
                    {
                        alert("Maaf data buku tidak ditemukan.");
                        $("#kode_buku").focus();
                        return false;
                    }
                }
                
            });
            
        });

    });
</script>