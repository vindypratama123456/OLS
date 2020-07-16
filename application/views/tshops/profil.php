<?php $this->load->view("tshops/header"); ?>

<div class="container main-container headerOffset">
    <div class="row">
        <div class="breadcrumbDiv col-lg-12">
            <ul class="breadcrumb">
                <li><a href="<?php echo base_url(); ?>">Beranda</a></li>
                <li class="active"> Profil</li>
            </ul>
        </div>
    </div>
    <div class="row">
        <div class="col-lg-12 col-md-12  col-sm-12">
            <?php echo $this->session->flashdata('message') ?>
            <h1 class="section-title-inner"><span><i class="fa fa-lock"></i> Profil Sekolah</span></h1>
            <div class="row userInfo">
                <div class="col-xs-12 col-sm-12">
                    <?php echo form_open(base_url() . 'akunsaya/edit/profil', 'class="form-signin" method="post" id="edit_profil" autocomplete="off"'); ?>
                        <div class="form-group">
                            <label>NPSN</label>
                            <input type="text" class="form-control" value="<?php echo $customer->no_npsn; ?>" disabled>
                        </div>
                        <div class="form-group">
                            <label>Nama Sekolah</label>
                            <input type="text" class="form-control" value="<?php echo $customer->school_name; ?>" disabled>
                        </div>
                        <?php /*
                        <div class="form-group">
                            <label>Bentuk Pendidikan</label>
                            <input type="text" class="form-control" value="<?php echo $customer->bentuk_pendidikan; ?>" disabled>
                        </div>
                        */ ?>
                        <div class="form-group">
                            <label>Alamat</label>
                            <input type="text" class="form-control" value="<?php echo $customer->alamat; ?>" disabled>
                        </div>
                        <div class="form-group">
                            <label>Provinsi</label>
                            <input type="text" class="form-control" value="<?php echo $customer->provinsi; ?>" disabled>
                        </div>
                        <div class="form-group">
                            <label>Kab/Kota</label>
                            <input type="text" class="form-control" value="<?php echo $customer->kabupaten; ?>" disabled>
                        </div>
                        <div class="form-group">
                            <label>Kacamatan</label>
                            <input type="text" class="form-control" value="<?php echo $customer->kecamatan; ?>" disabled>
                        </div>
                        <div class="form-group">
                            <label>Desa/Kelurahan</label>
                            <input type="text" class="form-control" value="<?php echo $customer->desa; ?>" disabled>
                        </div>
                        <div class="form-group">
                            <label>Kodepos</label>
                            <input type="text" class="form-control" value="<?php echo $customer->kodepos; ?>" disabled>
                        </div>
                        <div class="form-group">
                            <label>Nomor Telepon Sekolah</label>
                            <input type="text" class="form-control" value="<?php echo $customer->phone; ?>" name="phone" id="phone">
                        </div>
                        <div class="form-group">
                            <label>Alamat Surel Sekolah (Email)</label>
                            <input type="email" class="form-control" value="<?php echo $customer->email; ?>" name="email" id="email">
                        </div>
                        <hr />
                        <div class="form-group">
                            <label>Nama Kepala Sekolah</label>
                            <input type="text" class="form-control" value="<?php echo $customer->name; ?>" name="nama_lengkap" id="nama_lengkap">
                        </div>
                        <div class="form-group">
                            <label>NIP Kepala Sekolah</label>
                            <input type="text" class="form-control" value="<?php echo $customer->nip_kepsek; ?>" name="nip_kepsek" id="nip_kepsek">
                        </div>
                        <div class="form-group">
                            <label>No. Telpon/handphone Kepala Sekolah</label>
                            <input type="text" class="form-control" value="<?php echo $customer->phone_kepsek; ?>" name="phone_kepsek" id="phone_kepsek">
                        </div>
                        <div class="form-group">
                            <label>Alamat Surel Kepala Sekolah (Email)</label>
                            <input type="email" class="form-control" value="<?php echo $customer->email_kepsek; ?>" name="email_kepsek" id="email_kepsek">
                        </div>
                        <div class="form-group">
                            <label>Nama Bendahara</label>
                            <input type="text" class="form-control" value="<?php echo $customer->nama_bendahara; ?>" name="nama_bendahara" id="nama_bendahara">
                        </div>
                        <div class="form-group">
                            <label>NIP Bendahara</label>
                            <input type="text" class="form-control" value="<?php echo $customer->nip_bendahara; ?>" name="nip_bendahara" id="nip_bendahara">
                        </div>
                        <div class="form-group">
                            <label>No. Telpon/handphone Bendahara</label>
                            <input type="text" class="form-control" value="<?php echo $customer->phone_bendahara; ?>" name="phone_bendahara" id="phone_bendahara">
                        </div>
                        <hr />
                        <div class="form-group">
                            <label>NPWP</label>
                            <input type="text" class="form-control" value="<?php echo $customer->npwp; ?>" name="npwp" id="npwp">
                        </div>
                        <div class="form-group">
                            <label>Nama NPWP</label>
                            <input type="text" class="form-control" value="<?php echo $customer->nama_npwp; ?>" name="nama_npwp" id="nama_npwp">
                        </div>
                        <hr />
                        <div class="form-group">
                            <label>Nama Bank</label>
                            <input type="text" class="form-control" value="<?php echo $customer->nama_bank; ?>" name="nama_bank" id="nama_bank">
                        </div>
                        <div class="form-group">
                            <label>Atas Nama Rekening</label>
                            <input type="text" class="form-control" value="<?php echo $customer->nama_rekening; ?>" name="nama_rekening" id="nama_rekening">
                        </div>
                        <div class="form-group">
                            <label>Nomor Rekening Bank Sekolah</label>
                            <input type="text" class="form-control" value="<?php echo $customer->nomor_rekening; ?>" name="nomor_rekening" id="nomor_rekening">
                        </div>
                        <hr />
                        <div class="form-group">
                            <label>Nama Operator</label>
                            <input type="text" class="form-control" value="<?php echo $customer->operator; ?>" name="operator" id="operator">
                        </div>
                        <div class="form-group">
                            <label>Telpon/Handphone Operator</label>
                            <input type="text" class="form-control" value="<?php echo $customer->hp_operator; ?>" name="hp_operator" id="hp_operator">
                        </div>
                        <div class="form-group">
                            <label>Alamat Surel Operator (Email)</label>
                            <input type="email" class="form-control" value="<?php echo $customer->email_operator; ?>" name="email_operator" id="email_operator">
                        </div>
                        <br />
                        <button type="submit" class="btn btn-primary"><i class="fa fa-sign-in"></i> Perbarui Profil</button>
                    <?php echo form_close(); ?>
                    <br /><br />
                </div>
            </div>
        </div>
    </div>
    <div style="clear:both"></div>
</div>
<script>
    $().ready(function() {
        $("#edit_profil").validate({
            rules: {
                email: {
                    required: true,
                    email: true
                },
                password: {
                    minlength: 6
                },
                nama_sekolah: {
                    required: true,
                    minlength: 4
                },
                phone: {
                    required: true,
                    minlength: 6
                },
                nama_lengkap: {
                    required: true,
                    minlength: 4
                },
                nip_kepsek: {
                    required: true,
                    minlength: 3
                },
                phone_kepsek: {
                    required: true,
                    minlength: 6
                },
                email_kepsek: {
                    email: true
                },
                nama_bendahara: {
                    required: true,
                    minlength: 3
                },
                nip_bendahara: {
                    required: true,
                    minlength: 3
                },
                phone_bendahara: {
                    required: true,
                    minlength: 6
                },
                npwp: {
                    required: true,
                    minlength: 6
                },
                nama_npwp: {
                    required: true,
                    minlength: 3
                },
                nama_bank: {
                    required: true,
                    minlength: 3
                },
                nama_rekening: {
                    required: true,
                    minlength: 6
                },
                nomor_rekening: {
                    required: true,
                    minlength: 3
                },
                operator: {
                    required: true,
                    minlength: 3
                },
                hp_operator: {
                    required: true,
                    minlength: 5
                },
                email_operator: {
                    required: true,
                    email: true
                }
            }
        });
    });
</script>

<?php $this->load->view("tshops/footer"); ?>