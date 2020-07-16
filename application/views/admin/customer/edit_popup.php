<?php echo form_open('', 'class="form-horizontal" id="edit_school" autocomplete="off"'); ?>

<div class="modal-header">
    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span>
    </button>
    <h4 class="modal-title">Profil Sekolah</h4>
</div>
<div class="modal-body">
    <div class="row">
        <div class="col-lg-12">
            <input type="hidden" name="id_customer" value="<?= $detil['id_customer'] ?>"/>
            <div class="form-group">
                <div class="col-sm-12">
                    <div class="form-group">
                        <div class="col-md-8">
                            <label>NPSN</label>
                            <input type="text" class="form-control" name="no_npsn" value="<?= $detil['no_npsn'] ?>" >
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="col-md-12">
                            <label>Nama Sekolah</label>
                            <input type="text" class="form-control" name="school_name" id="school_name"
                                   value="<?= $detil['school_name'] ?>">
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="col-md-12">
                            <label>Bentuk Pendidikan</label><br>
                            <select id="bentuk" name="bentuk" class="form-control">
                                <option value=''>- Pilih Bentuk Pendidikan -</option>
                                <?php foreach ($bentuk as $data) { ?>
                                <option value="<?php echo $data->bentuk; ?>"> <?php echo $data->bentuk; ?> </option>
                                <?php } ?>
                            </select>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="col-md-12">
                            <label>Jenjang Pendidikan</label><br>
                            <select id="jenjang" name="jenjang" class="form-control">
                                <option value=''>- Pilih Jenjang Pendidikan -</option>
                                <?php foreach ($jenjang as $data) { ?>
                                <option value="<?php echo $data->jenjang ?>"> Kelas <?php echo $data->jenjang ?> </option>
                                <?php } ?>
                            </select>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="col-md-6">
                            <label>Telpon Sekolah</label>
                            <input type="text" class="form-control" name="phone" id="phone"
                                   value="<?= $detil['phone'] ?>">
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="col-md-6">
                            <label>Email Sekolah</label>
                            <input type="text" class="form-control" name="email" id="email"
                                   value="<?= $detil['email'] ?>">
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="col-md-12">
                            <label>Zona</label>
                            <select id="zona" name="zona" class="form-control">
                                <option value=''>- Pilih Zona -</option>
                                <?php foreach ($zona as $data) { ?>
                                <option value="<?php echo $data->id_site ?>"> <?php echo $data->id_site ?> </option>
                                <?php } ?>
                            </select>
                        </div>
                    </div>

                    <hr/>
                    <div class="form-group">
                        <div class="col-md-12">
                            <label>Alamat</label>
                            <textarea class="form-control" name="alamat" id="alamat"><?= $detil['alamat'] ?></textarea>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="col-md-12">
                            <label>Provinsi</label><br>
                            <select id="provinsi" name="provinsi" class="form-control">
                                <option value=''>- Pilih Provinsi -</option>
                                <?php foreach ($provinsi as $data) { ?>
                                <option value="<?php echo $data->provinsi ?>"> <?php echo $data->provinsi ?> </option>
                                <?php } ?>
                            </select>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="col-md-12">
                            <label>Kabupaten / Kota</label><br>
                            <select id="kabupaten" name="kabupaten" class="form-control">
                                <option value=''>- Pilih Kabupaten -</option>
                            </select>
                        </div>
                    </div>


                    <div class="form-group">
                        <div class="col-md-6">
                            <label>Kecamatan</label>
                            <input type="text" class="form-control" name="kecamatan" id="kecamatan" value="<?= $detil['kecamatan'] ?>">
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="col-md-6">
                            <label>Kelurahan/Desa</label>
                            <input type="text" class="form-control" name="desa" id="desa" value="<?= $detil['desa'] ?>">
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="col-md-4">
                            <label>Kodepos</label>
                            <input type="text" class="form-control" name="kodepos" id="kodepos"
                                   value="<?= $detil['kodepos'] ?>" maxlength="5">
                        </div>
                    </div>
                    <hr/>
                    <div class="form-group">
                        <div class="col-md-8">
                            <label>Nama Kepala Sekolah</label>
                            <input type="text" class="form-control" name="name" id="name" value="<?= $detil['name'] ?>">
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="col-md-6">
                            <label>NIP Kepala Sekolah</label>
                            <input type="text" class="form-control" name="nip_kepsek" id="nip_kepsek"
                                   value="<?= $detil['nip_kepsek'] ?>">
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="col-md-6">
                            <label>Telpon/HP Kepala Sekolah</label>
                            <input type="text" class="form-control" name="phone_kepsek" id="phone_kepsek"
                                   value="<?= $detil['phone_kepsek'] ?>">
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="col-md-6">
                            <label>Email Kepala Sekolah</label>
                            <input type="text" class="form-control" name="email_kepsek" id="email_kepsek"
                                   value="<?= $detil['email_kepsek'] ?>">
                        </div>
                    </div>
                    <hr/>
                    <div class="form-group">
                        <div class="col-md-8">
                            <label>Nama Bendahara</label>
                            <input type="text" class="form-control" name="nama_bendahara" id="nama_bendahara"
                                   value="<?= $detil['nama_bendahara'] ?>">
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="col-md-6">
                            <label>NIP Bendahara</label>
                            <input type="text" class="form-control" name="nip_bendahara" id="nip_bendahara"
                                   value="<?= $detil['nip_bendahara'] ?>">
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="col-md-6">
                            <label>Telpon/HP Bendahara</label>
                            <input type="text" class="form-control" name="phone_bendahara" id="phone_bendahara"
                                   value="<?= $detil['phone_bendahara'] ?>">
                        </div>
                    </div>
                    <hr/>
                    <div class="form-group">
                        <div class="col-md-8">
                            <label>Nama Operator</label>
                            <input type="text" class="form-control" name="operator" id="operator"
                                   value="<?= $detil['operator'] ?>">
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="col-md-6">
                            <label>Telpon/HP Operator</label>
                            <input type="text" class="form-control" name="hp_operator" id="hp_operator"
                                   value="<?= $detil['hp_operator'] ?>">
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="col-md-6">
                            <label>Email Operator</label>
                            <input type="text" class="form-control" name="email_operator" id="email_operator"
                                   value="<?= $detil['email_operator'] ?>">
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
        $('#edit_school').submit(function (e) {
            e.preventDefault();
            var conf = confirm('Yakin ingin memperbarui profil sekolah?');
            if (conf) {
                $('button').attr('disabled', true);
                $.ajax({
                    type: "POST",
                    data: $("#edit_school").serialize(),
                    dataType: "json",
                    url: BASE_URL + 'customer/editPost',
                    success: function (datas) {
                        if (datas.success == 'true') {
                            $('.modal').modal('hide').data('bs.modal', null);
                            window.location.reload(true);
                            // window.location = BASE_URL+datas.redirect;
                        } else {
                            bootAlert(datas.message);
                            $('button').attr('disabled', false);
                        }
                    }
                });
                return false;
            }
        });

        $('#bentuk option[value="<?= $detil['bentuk'] ?>"]').attr('selected','selected');
        $('#jenjang option[value="<?= $detil['jenjang'] ?>"]').attr('selected','selected');
        $('#provinsi option[value="<?= $detil['provinsi'] ?>"]').attr('selected','selected');
        $('#zona option[value="<?= $detil['zona'] ?>"]').attr('selected','selected');

        $('#provinsi').on('change', function(){
            if ($(this).val() === '') {
                $('#kabupaten').empty().append('<option value="">- Pilih Kabupaten -</option>');
            }
            else {
                $('#kabupaten').empty().append('<option value="">- Pilih Kabupaten -</option>');

                var data = {
                    provinsi: $(this).val(),
                    csrftokenbs: $("input[name=csrftokenbs]").val()
                };

                $.ajax({
                    url: BASE_URL+'customer/getKabupatenByProvinsi',
                    data: data,
                    dataType: 'json',
                    type: 'POST',
                    async: true,
                    success: function(data) {
                        $('input[name=csrftokenbs]').val(data.csrfHash);
                        if (data.success === true) {
                            $.each(data.row, function (i, item) {
                                $('#kabupaten').append($('<option>', {
                                    value: item.kabupaten,
                                    text : item.kabupaten
                                }));
                            });
                            $('#kabupaten option[value="<?= $detil['kabupaten'] ?>"]').attr('selected','selected');
                        } else {
                            $('#kabupaten').empty().append('<option value="">- Pilih Kabupaten -</option>');
                        }
                    },
                    error: function (xhr, ajaxOptions, thrownError) {
                        alert(xhr.status + " : " + thrownError);
                    }
                });
            }
        }).change();
    });
</script>