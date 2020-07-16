<style type="text/css">
    .display_none {
        display: none;
    }
</style>

<div class="container-fluid">

    <div class="row">
        <div class="col-lg-12">
            <h1 class="page-header">
                Pelanggan (Offline)
            </h1>
            <ol class="breadcrumb">
                <li>
                    <a href="<?php echo base_url() . ADMIN_PATH; ?>">Beranda</a>
                </li>
                <li class="active">
                    <a href="<?php echo base_url() . ADMIN_PATH; ?>/orders/offline">Pesanan Offline</a>
                </li>
                <li class="active">
                    Tambah Sekolah
                </li>
            </ol>
        </div>
    </div>

    <div class="row" style="padding-bottom: 20px;">
        <div class="col-lg-12">
            <?php echo form_open('', 'data-action="' . base_url() . ADMIN_PATH . '/orders/offlineSchoolPost" class="form-horizontal col-md-12" id="school_form" role="form" autocomplete="off"'); ?>
                <div>
                    <h3>Data Sekolah</h3>
                    <hr>
                </div>
                <div class="form-group">
                    <label class="control-label col-sm-3">NPSN</label>
                    <div class="col-sm-4">
                        <input type="text" class="form-control" name="reg_no_npsn" id="reg_no_npsn">
                    </div>
                    <div class="col-sm-3">
                        <a href="#" data-toggle="modal" data-target="#cekNPSN" class="btn btn-default btn-sm"><span class="fa fa-search"></span> &nbsp; Cek NPSN</a>
                    </div>
                </div>
                <div class="form-group">
                    <label class="control-label col-sm-3">Nama Sekolah</label>
                    <div class="col-sm-8">
                        <input type="text" class="form-control" name="reg_school_name" id="reg_school_name">
                    </div>
                </div>
                <div class="form-group">
                    <label class="control-label col-sm-3">Bentuk / Jenjang</label>
                    <div class="col-sm-8">
                        <label class="radio-inline">
                            <input name="reg_jenjang" value="7-9" type="radio" checked="true">SMP
                        </label>
                        <label class="radio-inline">
                            <input name="reg_jenjang" value="10-12" type="radio">SMA / SMK
                        </label>
                    </div>
                </div>
                <div class="form-group">
                    <label class="control-label col-sm-3">Pengguna K13</label>
                    <div class="col-sm-8">
                        <label class="radio-inline">
                            <input name="reg_user_k13" value="Ya" type="radio" checked="true">Ya
                        </label>
                        <label class="radio-inline">
                            <input name="reg_user_k13" value="Tidak" type="radio">Tidak
                        </label>
                    </div>
                </div>
                <div class="form-group">
                    <label class="control-label col-sm-3">Provinsi</label>
                    <div class="col-sm-8">
                        <select id="reg_provinsi" name="reg_provinsi" class="form-control" style="width:100%">
                            <option value=''>- Pilih Provinsi -</option>
                            <?php foreach ($provinsi as $data) { ?>
                            <option value="<?php echo $data->provinsi ?>"> <?php echo $data->provinsi ?> </option>
                            <?php } ?>
                        </select>
                    </div>
                </div>
                <div class="form-group">
                    <label class="control-label col-sm-3">Kabupaten / Kota</label>
                    <div class="col-sm-8">
                        <select id="reg_kabupaten" name="reg_kabupaten" class="form-control" style="width:100%">
                            <option value=''>- Pilih Kabupaten / Kota -</option>
                        </select>
                    </div>
                </div>
                <div class="form-group">
                    <label class="control-label col-sm-3">Kecamatan</label>
                    <div class="col-sm-8">
                        <select id="reg_kecamatan" name="reg_kecamatan" class="form-control" style="width:100%">
                            <option value=''>- Pilih Kecamatan -</option>
                        </select>
                    </div>
                </div>
                <div class="form-group">
                    <label class="control-label col-sm-3">Kelurahan / Desa</label>
                    <div class="col-sm-8">
                        <input type="text" class="form-control" name="reg_desa" id="reg_desa">
                    </div>
                </div>
                <div class="form-group">
                    <label class="control-label col-sm-3">Alamat</label>
                    <div class="col-sm-8">
                        <textarea type="text" class="form-control" name="reg_alamat" id="reg_alamat" style="resize: none;"></textarea>
                    </div>
                </div>            <div class="form-group">
                    <label class="control-label col-sm-3">Kodepos</label>
                    <div class="col-sm-4">
                        <input type="text" class="form-control" name="reg_kodepos" id="reg_kodepos">
                    </div>
                </div>
                <div class="form-group">
                    <label class="control-label col-sm-3">No. Telpon Sekolah</label>
                    <div class="col-sm-4">
                        <input type="text" class="form-control" name="reg_phone" id="reg_phone">
                    </div>
                </div>

                <div class="form-group">
                    <label class="control-label col-sm-3">Email Sekolah</label>
                    <div class="col-sm-4">
                        <input type="text" class="form-control" name="reg_email" id="reg_email">
                    </div>
                </div>
                <br>
                <div>
                    <h3>Data Kepala Sekolah</h3>
                    <hr>
                </div>
                <div class="form-group">
                    <label class="control-label col-sm-3">Nama Kepala Sekolah</label>
                    <div class="col-sm-8">
                        <input type="text" class="form-control" name="reg_kepsek_name" id="reg_kepsek_name">
                    </div>
                </div>
                <div class="form-group">
                    <label class="control-label col-sm-3">NIP Kepala Sekolah</label>
                    <div class="col-sm-4">
                        <input type="text" class="form-control" name="reg_kepsek_nip" id="reg_kepsek_nip">
                    </div>
                </div>
                <div class="form-group">
                    <label class="control-label col-sm-3">Telpon / HP Kepala Sekolah</label>
                    <div class="col-sm-4">
                        <input type="text" class="form-control" name="reg_kepsek_phone" id="reg_kepsek_phone">
                    </div>
                </div>
                <div class="form-group">
                    <label class="control-label col-sm-3">Email Kepala Sekolah</label>
                    <div class="col-sm-8">
                        <input type="text" class="form-control" name="reg_kepsek_email" id="reg_kepsek_email">
                    </div>
                </div>
                <br>
                <div>
                    <h3>Data Bendahara</h3>
                    <hr>
                </div>
                <div class="form-group">
                    <label class="control-label col-sm-3">Nama Bendahara</label>
                    <div class="col-sm-8">
                        <input type="text" class="form-control" name="reg_bendahara_name" id="reg_bendahara_name">
                    </div>
                </div>
                <div class="form-group">
                    <label class="control-label col-sm-3">NIP Bendahara</label>
                    <div class="col-sm-4">
                        <input type="text" class="form-control" name="reg_bendahara_nip" id="reg_bendahara_nip">
                    </div>
                </div>
                <div class="form-group">
                    <label class="control-label col-sm-3">Telpon / HP Bendahara</label>
                    <div class="col-sm-4">
                        <input type="text" class="form-control" name="reg_bendahara_phone" id="reg_bendahara_phone">
                    </div>
                </div>
                <br>
                <div>
                    <h3>Data Operator</h3>
                    <hr>
                </div>
                <div class="form-group">
                    <label class="control-label col-sm-3">Nama Operator</label>
                    <div class="col-sm-8">
                        <input type="text" class="form-control" name="reg_operator_name" id="reg_operator_name">
                    </div>
                </div>
                <div class="form-group">
                    <label class="control-label col-sm-3">Email Operator</label>
                    <div class="col-sm-8">
                        <input type="text" class="form-control" name="reg_operator_email" id="reg_operator_email">
                    </div>
                </div>
                <div class="form-group">
                    <label class="control-label col-sm-3">Telpon / HP Operator</label>
                    <div class="col-sm-4">
                        <input type="text" class="form-control" name="reg_operator_phone" id="reg_operator_phone">
                    </div>
                </div>
                <br>
                <hr>
                <div class="form-group">
                    <div class="col-sm-12">
                        <button type="submit" class="btn btn-success pull-left"><b>Simpan</b></button>
                        <a href="<?php echo base_url().ADMIN_PATH; ?>/orders/offline" class="btn btn-danger pull-right"><b>Kembali</b></a>
                    </div>
                </div>
            <?php echo form_close(); ?>
        </div>
    </div>

    <div class="modal fade" id="cekNPSN" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Tutup</span></button>
                    <h4 class="modal-title">Cek Ketersediaan NPSN</h4>
                </div>
                <?php echo form_open('', 'id="form_cek_npsn" class="form-horizontal" data-action="' . base_url() . ADMIN_PATH . '/orders/cekNPSN" role="form" autocomplete="off"'); ?>
                    <div class="modal-body">
                        <div class="row">
                            <div class="form-group">
                                <label class="col-md-4 control-label" style="text-align: right !important;">No NPSN</label>
                                <div class="col-md-6">
                                    <input type="text" id="cek_no_npsn" name="cek_no_npsn" class="form-control cek_no_npsn" value=""/>
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="col-md-4"></div>
                                <div class="col-md-8">
                                    <span id="messages" class="display_none" style="font-weight: bold; color: #d9534f;">No NSPN yang anda masukkan sudah ada.</span>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <div class="pull-left">
                            <button type="submit" class="btn btn-warning" id="submitDetail">C e k</button>
                        </div>
                        <div class="pull-right">
                            <button type="button" class="btn btn-danger" data-dismiss="modal">Tutup</button>
                        </div>
                    </div>

                <?php echo form_close(); ?>
            </div>
        </div>
    </div>

</div>
