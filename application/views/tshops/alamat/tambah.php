<?php
$this->load->view("tshops/header");
?>
<div class="container main-container headerOffset">
	<div class="row">
        <div class="breadcrumbDiv col-lg-12">
            <ul class="breadcrumb">
                <li><a href="<?php echo base_url(); ?>">Beranda</a></li>
                <li class="active"> Tambah alamat</li>
            </ul>
        </div>
    </div>
    <div class="row">
        <div class="col-lg-12 col-md-12  col-sm-12">
            <h1 class="section-title-inner"><span><i class="fa fa-plus"></i> Tambah alamat</span></h1>
            <?php echo $this->session->flashdata('message') ?>
            <div class="row userInfo">
                <div class="col-xs-12 col-sm-6">
                    <?php echo form_open(base_url() . 'alamat/process', 'class="form-signin" method="post" id="tambah_alamat" autocomplete="off"'); ?>
                        <div class="form-group">
	                    	<label>Alias alamat</label>
	                        <input type="text" class="form-control" name="alias" id="alias">
	                    </div>
                        <div class="form-group">
                            <label>Provinsi</label><br />
                            <select name="provinsi" id="provinsi">
                                <option value="0" selected="selected">Silahkan pilih Provinsi!</option>
                                <?php 
                                foreach($provinsi as $data){
                                ?>
                                <option value="<?php echo $data->id_provinsi?>"><?php echo $data->name; ?></option>
                                <?php
                                }
                                ?>
                            </select>
                        </div>
                        <div class="form-group">
                            <label>Kabupaten Kota</label><br />
                            <select name="kabupaten_kota" id="kabupaten_kota">
                                <option value="0">Silahkan pilih Provinsi anda dahulu!</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label>Kecamatan</label><br />
                            <select name="kecamatan" id="kecamatan">
                                <option value="0">Silahkan pilih Kabupaten / Kota anda dahulu!</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label>Kelurahan</label><br />
                            <select name="kelurahan" id="kelurahan">
                                <option value="0">Silahkan pilih Kecamatan anda dahulu!</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label>Alamat</label>
                            <input type="text" class="form-control" name="alamat" id="alamat">
                        </div>
                        <div class="form-group">
                            <label>Kode Pos</label>
                            <input type="text" max-length="5" class="form-control" name="postcode" id="postcode">
                        </div>
                        <div class="form-group">
                            <label>Nomor Handphone</label>
                            <input type="text" max-length="5" class="form-control" name="nomor_handphone" id="nomor_handphone">
                        </div>
                        <input type="hidden" name="id" value="<?php echo $this->session->userdata('id_customer'); ?>">
	                    <button type="submit" class="btn btn-primary"><i class="fa fa-plus"></i> Tambah Alamat</button>
                    <?php echo form_close(); ?>
                    <br /><br />
                </div>
            </div>
        </div>
    </div>
    <div style="clear:both"></div>
</div>
<style>
    select{
        padding:10px;
        background: #fafafa;
        border: 1px solid #eaeaea;
    }
</style>
<script src="<?php echo assets_url_fo()?>js/jquery.min.js?v=<?php echo date('YmdHis'); ?>"></script>
<script text="javascript">
$(function(){
    $("#tambah_alamat").validate({
        rules: {
            alias: {
                required: true,
            },
            alamat: {
                required: true,
            },
            postcode: {
                required: true,
                number: true
            },
            nomor_handphone: {
                required: true,
                number: true
            }
        }
    });
    $("#provinsi").change(function(){
        var id_provinsi = $(this).val();
        if(id_provinsi == 0){
            $("#kabupaten_kota").html('<option value="0">Silahkan pilih Provinsi anda dahulu!</option>');
            $("#kecamatan").html('<option value="0">Silahkan pilih Kabupaten / Kota anda dahulu!</option>');
            $("#kelurahan").html('<option value="0">Silahkan pilih Kabupaten / Kota anda dahulu!</option>');
        }
        else{
            $.ajax({
                type: 'POST',
                dataType: "json",
                url: 'http://bukusekolah.gramedia.com/alamat/getKabupatenKota/', 
                data: 'id_provinsi='+id_provinsi,
                success: function(response){
                    if(response == ''){
                        $("#kabupaten_kota").empty();
                        $("#kabupaten_kota").html('<option value="0">Untuk saat ini tidak ada Kabupaten / Kota di Provinsi yang anda pilih!</option>');
                        $("#kecamatan").empty();
                        $("#kecamatan").html('<option value="0">Silahkan pilih Kabupaten / Kota anda dahulu!</option>');
                        $("#kelurahan").empty();
                        $("#kelurahan").html('<option value="0">Silahkan pilih Kecamatan anda dahulu!</option>');
                    }
                    else{
                        var option = '<option value="0">Silahkan pilih Kabupaten / Kota anda dahulu!</option>';
                        $.each(response, function(key, value){
                            option += '<option value="'+value.id_kab_kota+'">'+value.name+'</option>';
                        });
                        $("#kabupaten_kota").empty().append(option);
                    }
                }
            });
        }
    });
    $("#kabupaten_kota").change(function(){
        var id_kab_kota = $(this).val();
        if(id_kab_kota == 0){
            $("#kecamatan").html('<option value="0">Silahkan pilih Kabupaten / Kota anda dahulu!</option>');
            $("#kelurahan").html('<option value="0">Silahkan pilih Kecamatan anda dahulu!</option>');
        }
        else{
            $.ajax({
                type: 'POST',
                dataType: "json",
                url: 'http://bukusekolah.gramedia.com/alamat/getKecamatan/', 
                data: 'id_kab_kota='+id_kab_kota,
                success: function(response){
                    if(response == ''){
                        $("#kecamatan").empty();
                        $("#kecamatan").html('<option value="0">Untuk saat ini tidak ada Kecamatan di Kabupaten / Kota yang anda pilih!</option>');
                        $("#kelurahan").empty();
                        $("#kelurahan").html('<option value="0">Silahkan pilih Kecamatan anda dahulu!</option>');
                    }
                    else{
                        var option = '<option value="0">Silahkan pilih Kecamatan anda dahulu!</option>';
                        $.each(response, function(key, value){
                            option += '<option value="'+value.id_kecamatan+'">'+value.name+'</option>';
                        });
                        $("#kecamatan").empty().append(option);
                    }
                }
            });
        }
    });
    $("#kecamatan").change(function(){
        var id_kecamatan = $(this).val();
        if(id_kecamatan == 0){
            $("#kelurahan").html('<option value="0">Silahkan pilih Kecamatan anda dahulu!</option>');
        }
        else{
            $.ajax({
                type: 'POST',
                dataType: "json",
                url: 'http://bukusekolah.gramedia.com/alamat/getKelurahan/', 
                data: 'id_kecamatan='+id_kecamatan,
                success: function(response){
                    if(response == ''){
                        $("#kelurahan").empty();
                        $("#kelurahan").html('<option value="0">Untuk saat ini tidak ada Kelurahan di Kecamatan yang anda pilih!</option>');
                    }
                    else{
                        var option = '';
                        $.each(response, function(key, value){
                            option += '<option value="'+value.id_kelurahan+'">'+value.name+'</option>';
                        });
                        $("#kelurahan").empty().append(option);
                    }
                }
            });
        }
    });
});
</script>
<?php
$this->load->view("tshops/footer");
?>