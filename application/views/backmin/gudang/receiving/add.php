<!-- START BREADCRUMB -->
<ul class="breadcrumb">
    <li><a href="<?php echo base_url(BACKMIN_PATH); ?>">Dasbor</a></li>
    <li><a href="<?php echo base_url(BACKMIN_PATH.'/gudangreceiving'); ?>">Stock receiving</a></li>
    <li class="active">Buat Stock receiving</li>
</ul>
<!-- END BREADCRUMB -->
<!-- PAGE TITLE -->
<div class="page-title">                    
    <h2><span class="glyphicon glyphicon-bookmark"></span> Buat Stock receiving</h2>
    
    <?php if($this->session->flashdata('success')): ?>
    <div role="alert" class="alert alert-success">
        <button data-dismiss="alert" class="close" type="button"><span aria-hidden="true">×</span><span class="sr-only">Close</span></button>
        <?php echo $this->session->flashdata('success'); ?>
    </div>
    <?php endif; ?>
    <?php if($this->session->flashdata('error')): ?>
    <div role="alert" class="alert alert-danger">
        <button data-dismiss="alert" class="close" type="button"><span aria-hidden="true">×</span><span class="sr-only">Close</span></button>
        <?php echo $this->session->flashdata('error'); ?>
    </div>
    <?php endif; ?>
    
</div>
<!-- END PAGE TITLE -->                
<!-- PAGE CONTENT WRAPPER -->
<div class="page-content-wrap">
    <div class="row">                        
        <div class="col-md-12">
            <div id="errorPlace"></div>
            <?php echo form_open(base_url(BACKMIN_PATH.'/gudangreceiving/addConfirmation'), 'id="frmRequestStock" class="form-horizontal" role="form" method="post" enctype="multipart/form-data" autocomplete="off"'); ?>
                <input type="hidden" id="request_id_produk" name="request_id_produk">
                <input type="hidden" id="request_berat" name="request_berat">
                <input type="hidden" id="request_jumlah" name="request_jumlah">
                <input type="hidden" id="is_tags" name="is_tags">
                <input type="hidden" id="request_no_oef" name="request_no_oef">
                
                <div class="panel panel-primary panel-toggled">
                    <div class="panel-heading">
                        <h3 class="panel-title">Buku Teks 2013 (SD) &nbsp; <i class="info-collapse">(Klik tombol panah di samping kanan)</i></h3>
                        <ul class="panel-controls">
                            <li><a href="#" class="panel-collapse"><span class="fa fa-angle-up"></span></a></li>
                        </ul>
                    </div>
                    <div class="panel-body panel-body-table">
                        <div>
                            <?php if($listBukuSD) { ?>
                            <table class="table table-bordered table-striped" width="100%">
                                <thead>
                                    <tr>
                                        <th class="text-center" width="52%">Judul Buku</th>
                                        <th class="text-center" width="15%">Kelas</th>
                                        <th class="text-center" width="16%">No. OEF</th>
                                        <th class="text-center" width="17%">Jumlah</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach($listBukuSD as $row) { ?>
                                    <tr>
                                        <td>
                                            <?php echo '[ <b>'.$row->kode_buku.'</b> ] &nbsp;'.$row->judul; ?>
                                            <input type="hidden" name="id_product[]" value="<?php echo $row->id_product; ?>">
                                            <input type="hidden" name="weight[]" value="<?php echo $row->weight; ?>">
                                        </td>
                                        <td class="text-center">
                                            <?php echo $row->kelas ?>
                                        </td>
                                        <!-- <td class="text-center">
                                            <input class="form-control no_oef" type="text"  data-id="<?php echo $row->id_product; ?>##<?php echo $row->weight; ?>##<?php echo $row->kode_buku; ?>" name="no_oef[]">
                                        </td> -->
                                        <td class="text-center">
                                            <select class="form-control no_oef" data-id="<?php echo $row->id_product; ?>##<?php echo $row->weight; ?>##<?php echo $row->kode_buku; ?>"  name="no_oef[]">
                                                <option value="">- Pilih Satu -</option>
                                                <?php 
                                                if($listOef) { 
                                                    foreach ($listOef as $dt) {
                                                        if($row->kode_buku == $dt->kode_buku)
                                                        echo '<option value="'.$dt->no_oef.'">'.$dt->no_oef.'</option>';
                                                    }
                                                }
                                                ?>
                                            </select>
                                        </td>
                                        <td class="text-center">
                                            <input class="form-control pqty" type="number" data-id="<?php echo $row->id_product; ?>##<?php echo $row->weight; ?>##<?php echo $row->kode_buku; ?>" name="product_quantity[]" min="0">
                                        </td>
                                    </tr>
                                    <?php } ?>
                                </tbody>
                            </table>
                            <?php } ?>
                        </div>
                    </div>                            
                </div>
                <div class="panel panel-primary panel-toggled">
                    <div class="panel-heading">
                        <h3 class="panel-title">Buku Teks 2013 (SMP) &nbsp; <i class="info-collapse">(Klik tombol panah di samping kanan)</i></h3>
                        <ul class="panel-controls">
                            <li><a href="#" class="panel-collapse"><span class="fa fa-angle-up"></span></a></li>
                        </ul>
                    </div>
                    <div class="panel-body panel-body-table">
                        <div>
                            <?php if($listBukuSMP) { ?>
                            <table class="table table-bordered table-striped" width="100%">
                                <thead>
                                    <tr>
                                        <th class="text-center" width="52%">Judul Buku</th>
                                        <th class="text-center" width="15%">Kelas</th>
                                        <th class="text-center" width="16%">No. OEF</th>
                                        <th class="text-center" width="17%">Jumlah</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach($listBukuSMP as $row) { ?>
                                    <tr>
                                        <td>
                                            <?php echo '[ <b>'.$row->kode_buku.'</b> ] &nbsp;'.$row->judul; ?>
                                            <input type="hidden" name="id_product[]" value="<?php echo $row->id_product; ?>">
                                            <input type="hidden" name="weight[]" value="<?php echo $row->weight; ?>">
                                        </td>
                                        <td class="text-center">
                                            <?php echo $row->kelas ?>
                                        </td>
                                        <!-- <td class="text-center">
                                            <input class="form-control no_oef" type="text"  data-id="<?php echo $row->id_product; ?>##<?php echo $row->weight; ?>##<?php echo $row->kode_buku; ?>" name="no_oef[]">
                                        </td> -->
                                        <td class="text-center">
                                            <select class="form-control no_oef" data-id="<?php echo $row->id_product; ?>##<?php echo $row->weight; ?>##<?php echo $row->kode_buku; ?>"  name="no_oef[]">
                                                <option value="">- Pilih Satu -</option>
                                                <?php 
                                                if($listOef) { 
                                                    foreach ($listOef as $dt) {
                                                        if($row->kode_buku == $dt->kode_buku)
                                                        echo '<option value="'.$dt->no_oef.'">'.$dt->no_oef.'</option>';
                                                    }
                                                }
                                                ?>
                                            </select>
                                        </td>
                                        <td class="text-center">
                                            <input class="form-control pqty" type="number" data-id="<?php echo $row->id_product; ?>##<?php echo $row->weight; ?>##<?php echo $row->kode_buku; ?>" name="product_quantity[]" min="0">
                                        </td>
                                    </tr>
                                    <?php } ?>
                                </tbody>
                            </table>
                            <?php } ?>
                        </div>
                    </div>                            
                </div>
                <div class="panel panel-primary panel-toggled">
                    <div class="panel-heading">
                        <h3 class="panel-title">Buku Teks 2013 (SMA) &nbsp; <i class="info-collapse">(Klik tombol panah di samping kanan)</i></h3>
                        <ul class="panel-controls">
                            <li><a href="#" class="panel-collapse"><span class="fa fa-angle-up"></span></a></li>
                        </ul>
                    </div>
                    <div class="panel-body panel-body-table">
                        <div>
                            <?php if($listBukuSMA) { ?>
                            <table class="table table-bordered table-striped" width="100%">
                                <thead>
                                    <tr>
                                        <th class="text-center" width="52%">Judul Buku</th>
                                        <th class="text-center" width="15%">Kelas</th>
                                        <th class="text-center" width="16%">No. OEF</th>
                                        <th class="text-center" width="17%">Jumlah</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach($listBukuSMA as $row) { ?>
                                    <tr>
                                        <td>
                                            <?php echo '[ <b>'.$row->kode_buku.'</b> ] &nbsp;'.$row->judul; ?>
                                            <input type="hidden" name="id_product[]" value="<?php echo $row->id_product; ?>">
                                            <input type="hidden" name="weight[]" value="<?php echo $row->weight; ?>">
                                        </td>
                                        <td class="text-center">
                                            <?php echo $row->kelas ?>
                                        </td>
                                        <!-- <td class="text-center">
                                            <input class="form-control no_oef" type="text"  data-id="<?php echo $row->id_product; ?>##<?php echo $row->weight; ?>##<?php echo $row->kode_buku; ?>" name="no_oef[]">
                                        </td> -->
                                        <td class="text-center">
                                            <select class="form-control no_oef" data-id="<?php echo $row->id_product; ?>##<?php echo $row->weight; ?>##<?php echo $row->kode_buku; ?>"  name="no_oef[]">
                                                <option value="">- Pilih Satu -</option>
                                                <?php 
                                                if($listOef) { 
                                                    foreach ($listOef as $dt) {
                                                        if($row->kode_buku == $dt->kode_buku)
                                                        echo '<option value="'.$dt->no_oef.'">'.$dt->no_oef.'</option>';
                                                    }
                                                }
                                                ?>
                                            </select>
                                        </td>
                                        <td class="text-center">
                                            <input class="form-control pqty" type="number" data-id="<?php echo $row->id_product; ?>##<?php echo $row->weight; ?>##<?php echo $row->kode_buku; ?>" name="product_quantity[]" min="0">
                                        </td>
                                    </tr>
                                    <?php } ?>
                                </tbody>
                            </table>
                            <?php } ?>
                        </div>
                    </div>                            
                </div>
                <div class="panel panel-primary panel-toggled">
                    <div class="panel-heading">
                        <h3 class="panel-title">Buku Teks 2006 (SMP) &nbsp; <i class="info-collapse">(Klik tombol panah di samping kanan)</i></h3>
                        <ul class="panel-controls">
                            <li><a href="#" class="panel-collapse"><span class="fa fa-angle-up"></span></a></li>
                        </ul>
                    </div>
                    <div class="panel-body panel-body-table">
                        <div>
                            <?php if($listBukuSMP_ktsp) { ?>
                            <table class="table table-bordered table-striped" width="100%">
                                <thead>
                                    <tr>
                                        <th class="text-center" width="52%">Judul Buku</th>
                                        <th class="text-center" width="15%">Kelas</th>
                                        <th class="text-center" width="16%">No. OEF</th>
                                        <th class="text-center" width="17%">Jumlah</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach($listBukuSMP_ktsp as $row) { ?>
                                    <tr>
                                        <td>
                                            <?php echo '[ <b>'.$row->kode_buku.'</b> ] &nbsp;'.$row->judul; ?>
                                            <input type="hidden" name="id_product[]" value="<?php echo $row->id_product; ?>">
                                            <input type="hidden" name="weight[]" value="<?php echo $row->weight; ?>">
                                        </td>
                                        <td class="text-center">
                                            <?php echo $row->kelas ?>
                                        </td>
                                        <!-- <td class="text-center">
                                            <input class="form-control no_oef" type="text"  data-id="<?php echo $row->id_product; ?>##<?php echo $row->weight; ?>##<?php echo $row->kode_buku; ?>" name="no_oef[]">
                                        </td> -->
                                        <td class="text-center">
                                            <select class="form-control no_oef" data-id="<?php echo $row->id_product; ?>##<?php echo $row->weight; ?>##<?php echo $row->kode_buku; ?>"  name="no_oef[]">
                                                <option value="">- Pilih Satu -</option>
                                                <?php 
                                                if($listOef) { 
                                                    foreach ($listOef as $dt) {
                                                        if($row->kode_buku == $dt->kode_buku)
                                                        echo '<option value="'.$dt->no_oef.'">'.$dt->no_oef.'</option>';
                                                    }
                                                }
                                                ?>
                                            </select>
                                        </td>
                                        <td class="text-center">
                                            <input class="form-control pqty" type="number" data-id="<?php echo $row->id_product; ?>##<?php echo $row->weight; ?>##<?php echo $row->kode_buku; ?>" name="product_quantity[]" min="0">
                                        </td>
                                    </tr>
                                    <?php } ?>
                                </tbody>
                            </table>
                            <?php } ?>
                        </div>
                    </div>                            
                </div>
                <div class="panel panel-primary panel-toggled">
                    <div class="panel-heading">
                        <h3 class="panel-title">Buku Peminatan (SMK) &nbsp; <i class="info-collapse">(Klik tombol panah di samping kanan)</i></h3>
                        <ul class="panel-controls">
                            <li><a href="#" class="panel-collapse"><span class="fa fa-angle-up"></span></a></li>
                        </ul>
                    </div>
                    <div class="panel-body panel-body-table">
                        <div>
                            <?php if($listBukuSMK) { ?>
                            <table class="table table-bordered table-striped" width="100%">
                                <thead>
                                    <tr>
                                        <th class="text-center" width="52%">Judul Buku</th>
                                        <th class="text-center" width="15%">Kelas</th>
                                        <th class="text-center" width="16%">No. OEF</th>
                                        <th class="text-center" width="17%">Jumlah</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach($listBukuSMK as $row) { ?>
                                    <tr>
                                        <td>
                                            <?php echo '[ <b>'.$row->kode_buku.'</b> ] &nbsp;'.$row->judul; ?>
                                            <input type="hidden" name="id_product[]" value="<?php echo $row->id_product; ?>">
                                            <input type="hidden" name="weight[]" value="<?php echo $row->weight; ?>">
                                        </td>
                                        <td class="text-center">
                                            <?php echo $row->kelas ?>
                                        </td>
                                        <!-- <td class="text-center">
                                            <input class="form-control no_oef" type="text"  data-id="<?php echo $row->id_product; ?>##<?php echo $row->weight; ?>##<?php echo $row->kode_buku; ?>" name="no_oef[]">
                                        </td> -->
                                        <td class="text-center">
                                            <select class="form-control no_oef" data-id="<?php echo $row->id_product; ?>##<?php echo $row->weight; ?>##<?php echo $row->kode_buku; ?>"  name="no_oef[]">
                                                <option value="">- Pilih Satu -</option>
                                                <?php 
                                                if($listOef) { 
                                                    foreach ($listOef as $dt) {
                                                        if($row->kode_buku == $dt->kode_buku)
                                                        echo '<option value="'.$dt->no_oef.'">'.$dt->no_oef.'</option>';
                                                    }
                                                }
                                                ?>
                                            </select>
                                        </td>
                                        <td class="text-center">
                                            <input class="form-control pqty" type="number" data-id="<?php echo $row->id_product; ?>##<?php echo $row->weight; ?>##<?php echo $row->kode_buku; ?>" name="product_quantity[]" min="0">
                                        </td>
                                    </tr>
                                    <?php } ?>
                                </tbody>
                            </table>
                            <?php } ?>
                        </div>
                    </div>                            
                </div>
                <?php if(!empty($listBukuLiterasi)){ ?>
                <div class="panel panel-primary panel-toggled">
                    <div class="panel-heading">
                        <h3 class="panel-title">Buku Literasi &nbsp; <i class="info-collapse">(Klik tombol panah di samping kanan)</i></h3>
                        <ul class="panel-controls">
                            <li><a href="#" class="panel-collapse"><span class="fa fa-angle-up"></span></a></li>
                        </ul>
                    </div>
                    <div class="panel-body panel-body-table">
                        <div>
                            <?php if($listBukuLiterasi) { ?>
                            <table class="table table-bordered table-striped" width="100%">
                                <thead>
                                    <tr>
                                        <th class="text-center" width="52%">Judul Buku</th>
                                        <th class="text-center" width="15%">Kelas</th>
                                        <th class="text-center" width="16%">No. OEF</th>
                                        <th class="text-center" width="17%">Jumlah</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach($listBukuLiterasi as $row) { ?>
                                    <tr>
                                        <td>
                                            <?php echo '[ <b>'.$row->kode_buku.'</b> ] &nbsp;'.$row->judul; ?>
                                            <input type="hidden" name="id_product[]" value="<?php echo $row->id_product; ?>">
                                            <input type="hidden" name="weight[]" value="<?php echo $row->weight; ?>">
                                        </td>
                                        <td class="text-center">
                                            <?php echo $row->kelas ?>
                                        </td>
                                        <!-- <td class="text-center">
                                            <input class="form-control no_oef" type="text"  data-id="<?php echo $row->id_product; ?>##<?php echo $row->weight; ?>##<?php echo $row->kode_buku; ?>" name="no_oef[]">
                                        </td> -->
                                        <td class="text-center">
                                            <select class="form-control no_oef" data-id="<?php echo $row->id_product; ?>##<?php echo $row->weight; ?>##<?php echo $row->kode_buku; ?>"  name="no_oef[]">
                                                <option value="">- Pilih Satu -</option>
                                                <?php 
                                                if($listOef) { 
                                                    foreach ($listOef as $dt) {
                                                        if($row->kode_buku == $dt->kode_buku)
                                                        echo '<option value="'.$dt->no_oef.'">'.$dt->no_oef.'</option>';
                                                    }
                                                }
                                                ?>
                                            </select>
                                        </td>
                                        <td class="text-center">
                                            <input class="form-control pqty" type="number" data-id="<?php echo $row->id_product; ?>##<?php echo $row->weight; ?>##<?php echo $row->kode_buku; ?>" name="product_quantity[]" min="0">
                                        </td>
                                    </tr>
                                    <?php } ?>
                                </tbody>
                            </table>
                            <?php } ?>
                        </div>
                    </div>                            
                </div>
                <?php } ?>
                <?php if(!empty($listBukuPengayaan)){ ?>
                <div class="panel panel-primary panel-toggled">
                    <div class="panel-heading">
                        <h3 class="panel-title">Buku Pengayaan &nbsp; <i class="info-collapse">(Klik tombol panah di samping kanan)</i></h3>
                        <ul class="panel-controls">
                            <li><a href="#" class="panel-collapse"><span class="fa fa-angle-up"></span></a></li>
                        </ul>
                    </div>
                    <div class="panel-body panel-body-table">
                        <div>
                            <?php if($listBukuPengayaan) { ?>
                            <table class="table table-bordered table-striped" width="100%">
                                <thead>
                                    <tr>
                                        <th class="text-center" width="52%">Judul Buku</th>
                                        <th class="text-center" width="15%">Kelas</th>
                                        <th class="text-center" width="16%">No. OEF</th>
                                        <th class="text-center" width="17%">Jumlah</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach($listBukuPengayaan as $row) { ?>
                                    <tr>
                                        <td>
                                            <?php echo '[ <b>'.$row->kode_buku.'</b> ] &nbsp;'.$row->judul; ?>
                                            <input type="hidden" name="id_product[]" value="<?php echo $row->id_product; ?>">
                                            <input type="hidden" name="weight[]" value="<?php echo $row->weight; ?>">
                                        </td>
                                        <td class="text-center">
                                            <?php echo $row->kelas ?>
                                        </td>
                                        <!-- <td class="text-center">
                                            <input class="form-control no_oef" type="text"  data-id="<?php echo $row->id_product; ?>##<?php echo $row->weight; ?>##<?php echo $row->kode_buku; ?>" name="no_oef[]">
                                        </td> -->
                                        <td class="text-center">
                                            <select class="form-control no_oef" data-id="<?php echo $row->id_product; ?>##<?php echo $row->weight; ?>##<?php echo $row->kode_buku; ?>"  name="no_oef[]">
                                                <option value="">- Pilih Satu -</option>
                                                <?php 
                                                if($listOef) { 
                                                    foreach ($listOef as $dt) {
                                                        if($row->kode_buku == $dt->kode_buku)
                                                        echo '<option value="'.$dt->no_oef.'">'.$dt->no_oef.'</option>';
                                                    }
                                                }
                                                ?>
                                            </select>
                                        </td>
                                        <td class="text-center">
                                            <input class="form-control pqty" type="number" data-id="<?php echo $row->id_product; ?>##<?php echo $row->weight; ?>##<?php echo $row->kode_buku; ?>" name="product_quantity[]" min="0">
                                        </td>
                                    </tr>
                                    <?php } ?>
                                </tbody>
                            </table>
                            <?php } ?>
                        </div>
                    </div>                            
                </div>
                <?php } ?>
                <?php if(!empty($listBukuReferensi)){ ?>
                <div class="panel panel-primary panel-toggled">
                    <div class="panel-heading">
                        <h3 class="panel-title">Buku Referensi &nbsp; <i class="info-collapse">(Klik tombol panah di samping kanan)</i></h3>
                        <ul class="panel-controls">
                            <li><a href="#" class="panel-collapse"><span class="fa fa-angle-up"></span></a></li>
                        </ul>
                    </div>
                    <div class="panel-body panel-body-table">
                        <div>
                            <?php if($listBukuReferensi) { ?>
                            <table class="table table-bordered table-striped" width="100%">
                                <thead>
                                    <tr>
                                        <th class="text-center" width="52%">Judul Buku</th>
                                        <th class="text-center" width="15%">Kelas</th>
                                        <th class="text-center" width="16%">No. OEF</th>
                                        <th class="text-center" width="17%">Jumlah</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach($listBukuReferensi as $row) { ?>
                                    <tr>
                                        <td>
                                            <?php echo '[ <b>'.$row->kode_buku.'</b> ] &nbsp;'.$row->judul; ?>
                                            <input type="hidden" name="id_product[]" value="<?php echo $row->id_product; ?>">
                                            <input type="hidden" name="weight[]" value="<?php echo $row->weight; ?>">
                                        </td>
                                        <td class="text-center">
                                            <?php echo $row->kelas ?>
                                        </td>
                                        <!-- <td class="text-center">
                                            <input class="form-control no_oef" type="text"  data-id="<?php echo $row->id_product; ?>##<?php echo $row->weight; ?>##<?php echo $row->kode_buku; ?>" name="no_oef[]">
                                        </td> -->
                                        <td class="text-center">
                                            <select class="form-control no_oef" data-id="<?php echo $row->id_product; ?>##<?php echo $row->weight; ?>##<?php echo $row->kode_buku; ?>"  name="no_oef[]">
                                                <option value="">- Pilih Satu -</option>
                                                <?php 
                                                if($listOef) { 
                                                    foreach ($listOef as $dt) {
                                                        if($row->kode_buku == $dt->kode_buku)
                                                        echo '<option value="'.$dt->no_oef.'">'.$dt->no_oef.'</option>';
                                                    }
                                                }
                                                ?>
                                            </select>
                                        </td>
                                        <td class="text-center">
                                            <input class="form-control pqty" type="number" data-id="<?php echo $row->id_product; ?>##<?php echo $row->weight; ?>##<?php echo $row->kode_buku; ?>" name="product_quantity[]" min="0">
                                        </td>
                                    </tr>
                                    <?php } ?>
                                </tbody>
                            </table>
                            <?php } ?>
                        </div>
                    </div>                            
                </div>
                <?php } ?>
                <?php if(!empty($listBukuPandik)){ ?>
                <div class="panel panel-primary panel-toggled">
                    <div class="panel-heading">
                        <h3 class="panel-title">Buku Panduan Pendidikan &nbsp; <i class="info-collapse">(Klik tombol panah di samping kanan)</i></h3>
                        <ul class="panel-controls">
                            <li><a href="#" class="panel-collapse"><span class="fa fa-angle-up"></span></a></li>
                        </ul>
                    </div>
                    <div class="panel-body panel-body-table">
                        <div>
                            <?php if($listBukuPandik) { ?>
                            <table class="table table-bordered table-striped" width="100%">
                                <thead>
                                    <tr>
                                        <th class="text-center" width="52%">Judul Buku</th>
                                        <th class="text-center" width="15%">Kelas</th>
                                        <th class="text-center" width="16%">No. OEF</th>
                                        <th class="text-center" width="17%">Jumlah</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach($listBukuPandik as $row) { ?>
                                    <tr>
                                        <td>
                                            <?php echo '[ <b>'.$row->kode_buku.'</b> ] &nbsp;'.$row->judul; ?>
                                            <input type="hidden" name="id_product[]" value="<?php echo $row->id_product; ?>">
                                            <input type="hidden" name="weight[]" value="<?php echo $row->weight; ?>">
                                        </td>
                                        <td class="text-center">
                                            <?php echo $row->kelas ?>
                                        </td>
                                        <!-- <td class="text-center">
                                            <input class="form-control no_oef" type="text"  data-id="<?php echo $row->id_product; ?>##<?php echo $row->weight; ?>##<?php echo $row->kode_buku; ?>" name="no_oef[]">
                                        </td> -->
                                        <td class="text-center">
                                            <select class="form-control no_oef" data-id="<?php echo $row->id_product; ?>##<?php echo $row->weight; ?>##<?php echo $row->kode_buku; ?>"  name="no_oef[]">
                                                <option value="">- Pilih Satu -</option>
                                                <?php 
                                                if($listOef) { 
                                                    foreach ($listOef as $dt) {
                                                        if($row->kode_buku == $dt->kode_buku)
                                                        echo '<option value="'.$dt->no_oef.'">'.$dt->no_oef.'</option>';
                                                    }
                                                }
                                                ?>
                                            </select>
                                        </td>
                                        <td class="text-center">
                                            <input class="form-control pqty" type="number" data-id="<?php echo $row->id_product; ?>##<?php echo $row->weight; ?>##<?php echo $row->kode_buku; ?>" name="product_quantity[]" min="0">
                                        </td>
                                    </tr>
                                    <?php } ?>
                                </tbody>
                            </table>
                            <?php } ?>
                        </div>
                    </div>                          
                </div>
                <?php } ?>  
                <?php if(!empty($listProductIt)){ ?>
                <div class="panel panel-primary panel-toggled">
                    <div class="panel-heading">
                        <h3 class="panel-title">Produk IT &nbsp; <i class="info-collapse">(Klik tombol panah di samping kanan)</i></h3>
                        <ul class="panel-controls">
                            <li><a href="#" class="panel-collapse"><span class="fa fa-angle-up"></span></a></li>
                        </ul>
                    </div>
                    <div class="panel-body panel-body-table">
                        <div>
                            <?php if($listProductIt) { ?>
                            <table class="table table-bordered table-striped" width="100%">
                                <thead>
                                    <tr>
                                        <th class="text-center" width="52%">Judul Buku</th>
                                        <th class="text-center" width="15%">Kelas</th>
                                        <th class="text-center" width="16%">No. OEF</th>
                                        <th class="text-center" width="17%">Jumlah</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach($listProductIt as $row) { ?>
                                    <tr>
                                        <td>
                                            <?php echo '[ <b>'.$row->kode_buku.'</b> ] &nbsp;'.$row->judul; ?>
                                            <input type="hidden" name="id_product[]" value="<?php echo $row->id_product; ?>">
                                            <input type="hidden" name="weight[]" value="<?php echo $row->weight; ?>">
                                        </td>
                                        <td class="text-center">
                                            <?php echo $row->kelas ?>
                                        </td>
                                        <!-- <td class="text-center">
                                            <input class="form-control no_oef" type="text"  data-id="<?php echo $row->id_product; ?>##<?php echo $row->weight; ?>##<?php echo $row->kode_buku; ?>" name="no_oef[]">
                                        </td> -->
                                        <td class="text-center">
                                            <select class="form-control no_oef" data-id="<?php echo $row->id_product; ?>##<?php echo $row->weight; ?>##<?php echo $row->kode_buku; ?>"  name="no_oef[]">
                                                <option value="">- Pilih Satu -</option>
                                                <?php 
                                                if($listOef) { 
                                                    foreach ($listOef as $dt) {
                                                        if($row->kode_buku == $dt->kode_buku)
                                                        echo '<option value="'.$dt->no_oef.'">'.$dt->no_oef.'</option>';
                                                    }
                                                }
                                                ?>
                                            </select>
                                        </td>
                                        <td class="text-center">
                                            <input class="form-control pqty" type="number" data-id="<?php echo $row->id_product; ?>##<?php echo $row->weight; ?>##<?php echo $row->kode_buku; ?>" name="product_quantity[]" min="0">
                                        </td>
                                    </tr>
                                    <?php } ?>
                                </tbody>
                            </table>
                            <?php } ?>
                        </div>
                    </div>                          
                </div>
                <?php } ?>  
                <?php if(!empty($listProductCovid)){ ?>
                <div class="panel panel-primary panel-toggled">
                    <div class="panel-heading">
                        <h3 class="panel-title">Produk Covid &nbsp; <i class="info-collapse">(Klik tombol panah di samping kanan)</i></h3>
                        <ul class="panel-controls">
                            <li><a href="#" class="panel-collapse"><span class="fa fa-angle-up"></span></a></li>
                        </ul>
                    </div>
                    <div class="panel-body panel-body-table">
                        <div>
                            <?php if($listProductCovid) { ?>
                            <table class="table table-bordered table-striped" width="100%">
                                <thead>
                                    <tr>
                                        <th class="text-center" width="52%">Judul Buku</th>
                                        <th class="text-center" width="15%">Kelas</th>
                                        <th class="text-center" width="16%">No. OEF</th>
                                        <th class="text-center" width="17%">Jumlah</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach($listProductCovid as $row) { ?>
                                    <tr>
                                        <td>
                                            <?php echo '[ <b>'.$row->kode_buku.'</b> ] &nbsp;'.$row->judul; ?>
                                            <input type="hidden" name="id_product[]" value="<?php echo $row->id_product; ?>">
                                            <input type="hidden" name="weight[]" value="<?php echo $row->weight; ?>">
                                        </td>
                                        <td class="text-center">
                                            <?php echo $row->kelas ?>
                                        </td>
                                        <!-- <td class="text-center">
                                            <input class="form-control no_oef" type="text"  data-id="<?php echo $row->id_product; ?>##<?php echo $row->weight; ?>##<?php echo $row->kode_buku; ?>" name="no_oef[]">
                                        </td> -->
                                        <td class="text-center">
                                            <select class="form-control no_oef" data-id="<?php echo $row->id_product; ?>##<?php echo $row->weight; ?>##<?php echo $row->kode_buku; ?>"  name="no_oef[]">
                                                <option value="">- Pilih Satu -</option>
                                                <?php 
                                                if($listOef) { 
                                                    foreach ($listOef as $dt) {
                                                        if($row->kode_buku == $dt->kode_buku)
                                                        echo '<option value="'.$dt->no_oef.'">'.$dt->no_oef.'</option>';
                                                    }
                                                }
                                                ?>
                                            </select>
                                        </td>
                                        <td class="text-center">
                                            <input class="form-control pqty" type="number" data-id="<?php echo $row->id_product; ?>##<?php echo $row->weight; ?>##<?php echo $row->kode_buku; ?>" name="product_quantity[]" min="0">
                                        </td>
                                    </tr>
                                    <?php } ?>
                                </tbody>
                            </table>
                            <?php } ?>
                        </div>
                    </div>                          
                </div>
                <?php } ?>  
                <?php if(!empty($listAlatTulis)){ ?>
                <div class="panel panel-primary panel-toggled">
                    <div class="panel-heading">
                        <h3 class="panel-title">Alat Tulis &nbsp; <i class="info-collapse">(Klik tombol panah di samping kanan)</i></h3>
                        <ul class="panel-controls">
                            <li><a href="#" class="panel-collapse"><span class="fa fa-angle-up"></span></a></li>
                        </ul>
                    </div>
                    <div class="panel-body panel-body-table">
                        <div>
                            <?php if($listAlatTulis) { ?>
                            <table class="table table-bordered table-striped" width="100%">
                                <thead>
                                    <tr>
                                        <th class="text-center" width="52%">Judul Buku</th>
                                        <th class="text-center" width="15%">Kelas</th>
                                        <th class="text-center" width="16%">No. OEF</th>
                                        <th class="text-center" width="17%">Jumlah</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach($listAlatTulis as $row) { ?>
                                    <tr>
                                        <td>
                                            <?php echo '[ <b>'.$row->kode_buku.'</b> ] &nbsp;'.$row->judul; ?>
                                            <input type="hidden" name="id_product[]" value="<?php echo $row->id_product; ?>">
                                            <input type="hidden" name="weight[]" value="<?php echo $row->weight; ?>">
                                        </td>
                                        <td class="text-center">
                                            <?php echo $row->kelas ?>
                                        </td>
                                        <!-- <td class="text-center">
                                            <input class="form-control no_oef" type="text"  data-id="<?php echo $row->id_product; ?>##<?php echo $row->weight; ?>##<?php echo $row->kode_buku; ?>" name="no_oef[]">
                                        </td> -->
                                        <td class="text-center">
                                            <select class="form-control no_oef" data-id="<?php echo $row->id_product; ?>##<?php echo $row->weight; ?>##<?php echo $row->kode_buku; ?>"  name="no_oef[]">
                                                <option value="">- Pilih Satu -</option>
                                                <?php 
                                                if($listOef) { 
                                                    foreach ($listOef as $dt) {
                                                        if($row->kode_buku == $dt->kode_buku)
                                                        echo '<option value="'.$dt->no_oef.'">'.$dt->no_oef.'</option>';
                                                    }
                                                }
                                                ?>
                                            </select>
                                        </td>
                                        <td class="text-center">
                                            <input class="form-control pqty" type="number" data-id="<?php echo $row->id_product; ?>##<?php echo $row->weight; ?>##<?php echo $row->kode_buku; ?>" name="product_quantity[]" min="0">
                                        </td>
                                    </tr>
                                    <?php } ?>
                                </tbody>
                            </table>
                            <?php } ?>
                        </div>
                    </div>                          
                </div>
                <?php } ?>   
                <?php if(!empty($listSmartLibrary)){ ?>
                <div class="panel panel-primary panel-toggled">
                    <div class="panel-heading">
                        <h3 class="panel-title">Smart Library &nbsp; <i class="info-collapse">(Klik tombol panah di samping kanan)</i></h3>
                        <ul class="panel-controls">
                            <li><a href="#" class="panel-collapse"><span class="fa fa-angle-up"></span></a></li>
                        </ul>
                    </div>
                    <div class="panel-body panel-body-table">
                        <div>
                            <?php if($listSmartLibrary) { ?>
                            <table class="table table-bordered table-striped" width="100%">
                                <thead>
                                    <tr>
                                        <th class="text-center" width="52%">Judul Buku</th>
                                        <th class="text-center" width="15%">Kelas</th>
                                        <th class="text-center" width="16%">No. OEF</th>
                                        <th class="text-center" width="17%">Jumlah</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach($listSmartLibrary as $row) { ?>
                                    <tr>
                                        <td>
                                            <?php echo '[ <b>'.$row->kode_buku.'</b> ] &nbsp;'.$row->judul; ?>
                                            <input type="hidden" name="id_product[]" value="<?php echo $row->id_product; ?>">
                                            <input type="hidden" name="weight[]" value="<?php echo $row->weight; ?>">
                                        </td>
                                        <td class="text-center">
                                            <?php echo $row->kelas ?>
                                        </td>
                                        <!-- <td class="text-center">
                                            <input class="form-control no_oef" type="text"  data-id="<?php echo $row->id_product; ?>##<?php echo $row->weight; ?>##<?php echo $row->kode_buku; ?>" name="no_oef[]">
                                        </td> -->
                                        <td class="text-center">
                                            <select class="form-control no_oef" data-id="<?php echo $row->id_product; ?>##<?php echo $row->weight; ?>##<?php echo $row->kode_buku; ?>"  name="no_oef[]">
                                                <option value="">- Pilih Satu -</option>
                                                <?php 
                                                if($listOef) { 
                                                    foreach ($listOef as $dt) {
                                                        if($row->kode_buku == $dt->kode_buku)
                                                        echo '<option value="'.$dt->no_oef.'">'.$dt->no_oef.'</option>';
                                                    }
                                                }
                                                ?>
                                            </select>
                                        </td>
                                        <td class="text-center">
                                            <input class="form-control pqty" type="number" data-id="<?php echo $row->id_product; ?>##<?php echo $row->weight; ?>##<?php echo $row->kode_buku; ?>" name="product_quantity[]" min="0">
                                        </td>
                                    </tr>
                                    <?php } ?>
                                </tbody>
                            </table>
                            <?php } ?>
                        </div>
                    </div>                          
                </div>
                <?php } ?> 
                <?php if(!empty($listBukuPendampingK13SD)){ ?>
                <div class="panel panel-primary panel-toggled">
                    <div class="panel-heading">
                        <h3 class="panel-title">Buku Pendamping K-13 (SD) &nbsp; <i class="info-collapse">(Klik tombol panah di samping kanan)</i></h3>
                        <ul class="panel-controls">
                            <li><a href="#" class="panel-collapse"><span class="fa fa-angle-up"></span></a></li>
                        </ul>
                    </div>
                    <div class="panel-body panel-body-table">
                        <div>
                            <?php if($listBukuPendampingK13SD) { ?>
                            <table class="table table-bordered table-striped" width="100%">
                                <thead>
                                    <tr>
                                        <th class="text-center" width="52%">Judul Buku</th>
                                        <th class="text-center" width="15%">Kelas</th>
                                        <th class="text-center" width="16%">No. OEF</th>
                                        <th class="text-center" width="17%">Jumlah</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach($listBukuPendampingK13SD as $row) { ?>
                                    <tr>
                                        <td>
                                            <?php echo '[ <b>'.$row->kode_buku.'</b> ] &nbsp;'.$row->judul; ?>
                                            <input type="hidden" name="id_product[]" value="<?php echo $row->id_product; ?>">
                                            <input type="hidden" name="weight[]" value="<?php echo $row->weight; ?>">
                                        </td>
                                        <td class="text-center">
                                            <?php echo $row->kelas ?>
                                        </td>
                                        <!-- <td class="text-center">
                                            <input class="form-control no_oef" type="text"  data-id="<?php echo $row->id_product; ?>##<?php echo $row->weight; ?>##<?php echo $row->kode_buku; ?>" name="no_oef[]">
                                        </td> -->
                                        <td class="text-center">
                                            <select class="form-control no_oef" data-id="<?php echo $row->id_product; ?>##<?php echo $row->weight; ?>##<?php echo $row->kode_buku; ?>"  name="no_oef[]">
                                                <option value="">- Pilih Satu -</option>
                                                <?php 
                                                if($listOef) { 
                                                    foreach ($listOef as $dt) {
                                                        if($row->kode_buku == $dt->kode_buku)
                                                        echo '<option value="'.$dt->no_oef.'">'.$dt->no_oef.'</option>';
                                                    }
                                                }
                                                ?>
                                            </select>
                                        </td>
                                        <td class="text-center">
                                            <input class="form-control pqty" type="number" data-id="<?php echo $row->id_product; ?>##<?php echo $row->weight; ?>##<?php echo $row->kode_buku; ?>" name="product_quantity[]" min="0">
                                        </td>
                                    </tr>
                                    <?php } ?>
                                </tbody>
                            </table>
                            <?php } ?>
                        </div>
                    </div>                          
                </div>
                <?php } ?>  
                <?php if(!empty($listBukuPendampingK13SMP)){ ?>
                <div class="panel panel-primary panel-toggled">
                    <div class="panel-heading">
                        <h3 class="panel-title">Buku Pendamping K-13 (SMP) &nbsp; <i class="info-collapse">(Klik tombol panah di samping kanan)</i></h3>
                        <ul class="panel-controls">
                            <li><a href="#" class="panel-collapse"><span class="fa fa-angle-up"></span></a></li>
                        </ul>
                    </div>
                    <div class="panel-body panel-body-table">
                        <div>
                            <?php if($listBukuPendampingK13SMP) { ?>
                            <table class="table table-bordered table-striped" width="100%">
                                <thead>
                                    <tr>
                                        <th class="text-center" width="52%">Judul Buku</th>
                                        <th class="text-center" width="15%">Kelas</th>
                                        <th class="text-center" width="16%">No. OEF</th>
                                        <th class="text-center" width="17%">Jumlah</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach($listBukuPendampingK13SMP as $row) { ?>
                                    <tr>
                                        <td>
                                            <?php echo '[ <b>'.$row->kode_buku.'</b> ] &nbsp;'.$row->judul; ?>
                                            <input type="hidden" name="id_product[]" value="<?php echo $row->id_product; ?>">
                                            <input type="hidden" name="weight[]" value="<?php echo $row->weight; ?>">
                                        </td>
                                        <td class="text-center">
                                            <?php echo $row->kelas ?>
                                        </td>
                                        <!-- <td class="text-center">
                                            <input class="form-control no_oef" type="text"  data-id="<?php echo $row->id_product; ?>##<?php echo $row->weight; ?>##<?php echo $row->kode_buku; ?>" name="no_oef[]">
                                        </td> -->
                                        <td class="text-center">
                                            <select class="form-control no_oef" data-id="<?php echo $row->id_product; ?>##<?php echo $row->weight; ?>##<?php echo $row->kode_buku; ?>"  name="no_oef[]">
                                                <option value="">- Pilih Satu -</option>
                                                <?php 
                                                if($listOef) { 
                                                    foreach ($listOef as $dt) {
                                                        if($row->kode_buku == $dt->kode_buku)
                                                        echo '<option value="'.$dt->no_oef.'">'.$dt->no_oef.'</option>';
                                                    }
                                                }
                                                ?>
                                            </select>
                                        </td>
                                        <td class="text-center">
                                            <input class="form-control pqty" type="number" data-id="<?php echo $row->id_product; ?>##<?php echo $row->weight; ?>##<?php echo $row->kode_buku; ?>" name="product_quantity[]" min="0">
                                        </td>
                                    </tr>
                                    <?php } ?>
                                </tbody>
                            </table>
                            <?php } ?>
                        </div>
                    </div>                          
                </div>
                <?php } ?>  
                <?php if(!empty($listBukuPendampingK13SMA)){ ?>
                <div class="panel panel-primary panel-toggled">
                    <div class="panel-heading">
                        <h3 class="panel-title">Buku Pendamping K-13 (SMA) &nbsp; <i class="info-collapse">(Klik tombol panah di samping kanan)</i></h3>
                        <ul class="panel-controls">
                            <li><a href="#" class="panel-collapse"><span class="fa fa-angle-up"></span></a></li>
                        </ul>
                    </div>
                    <div class="panel-body panel-body-table">
                        <div>
                            <?php if($listBukuPendampingK13SMA) { ?>
                            <table class="table table-bordered table-striped" width="100%">
                                <thead>
                                    <tr>
                                        <th class="text-center" width="52%">Judul Buku</th>
                                        <th class="text-center" width="15%">Kelas</th>
                                        <th class="text-center" width="16%">No. OEF</th>
                                        <th class="text-center" width="17%">Jumlah</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach($listBukuPendampingK13SMA as $row) { ?>
                                    <tr>
                                        <td>
                                            <?php echo '[ <b>'.$row->kode_buku.'</b> ] &nbsp;'.$row->judul; ?>
                                            <input type="hidden" name="id_product[]" value="<?php echo $row->id_product; ?>">
                                            <input type="hidden" name="weight[]" value="<?php echo $row->weight; ?>">
                                        </td>
                                        <td class="text-center">
                                            <?php echo $row->kelas ?>
                                        </td>
                                        <!-- <td class="text-center">
                                            <input class="form-control no_oef" type="text"  data-id="<?php echo $row->id_product; ?>##<?php echo $row->weight; ?>##<?php echo $row->kode_buku; ?>" name="no_oef[]">
                                        </td> -->
                                        <td class="text-center">
                                            <select class="form-control no_oef" data-id="<?php echo $row->id_product; ?>##<?php echo $row->weight; ?>##<?php echo $row->kode_buku; ?>"  name="no_oef[]">
                                                <option value="">- Pilih Satu -</option>
                                                <?php 
                                                if($listOef) { 
                                                    foreach ($listOef as $dt) {
                                                        if($row->kode_buku == $dt->kode_buku)
                                                        echo '<option value="'.$dt->no_oef.'">'.$dt->no_oef.'</option>';
                                                    }
                                                }
                                                ?>
                                            </select>
                                        </td>
                                        <td class="text-center">
                                            <input class="form-control pqty" type="number" data-id="<?php echo $row->id_product; ?>##<?php echo $row->weight; ?>##<?php echo $row->kode_buku; ?>" name="product_quantity[]" min="0">
                                        </td>
                                    </tr>
                                    <?php } ?>
                                </tbody>
                            </table>
                            <?php } ?>
                        </div>
                    </div>                          
                </div>
                <?php } ?>  
                <?php if(!empty($listBukuPeminatanSmaMa)){ ?>
                <div class="panel panel-primary panel-toggled">
                    <div class="panel-heading">
                        <h3 class="panel-title">Buku Peminatan SMA / MA &nbsp; <i class="info-collapse">(Klik tombol panah di samping kanan)</i></h3>
                        <ul class="panel-controls">
                            <li><a href="#" class="panel-collapse"><span class="fa fa-angle-up"></span></a></li>
                        </ul>
                    </div>
                    <div class="panel-body panel-body-table">
                        <div>
                            <?php if($listBukuPeminatanSmaMa) { ?>
                            <table class="table table-bordered table-striped" width="100%">
                                <thead>
                                    <tr>
                                        <th class="text-center" width="52%">Judul Buku</th>
                                        <th class="text-center" width="15%">Kelas</th>
                                        <th class="text-center" width="16%">No. OEF</th>
                                        <th class="text-center" width="17%">Jumlah</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach($listBukuPeminatanSmaMa as $row) { ?>
                                    <tr>
                                        <td>
                                            <?php echo '[ <b>'.$row->kode_buku.'</b> ] &nbsp;'.$row->judul; ?>
                                            <input type="hidden" name="id_product[]" value="<?php echo $row->id_product; ?>">
                                            <input type="hidden" name="weight[]" value="<?php echo $row->weight; ?>">
                                        </td>
                                        <td class="text-center">
                                            <?php echo $row->kelas ?>
                                        </td>
                                        <!-- <td class="text-center">
                                            <input class="form-control no_oef" type="text"  data-id="<?php echo $row->id_product; ?>##<?php echo $row->weight; ?>##<?php echo $row->kode_buku; ?>" name="no_oef[]">
                                        </td> -->
                                        <td class="text-center">
                                            <select class="form-control no_oef" data-id="<?php echo $row->id_product; ?>##<?php echo $row->weight; ?>##<?php echo $row->kode_buku; ?>"  name="no_oef[]">
                                                <option value="">- Pilih Satu -</option>
                                                <?php 
                                                if($listOef) { 
                                                    foreach ($listOef as $dt) {
                                                        if($row->kode_buku == $dt->kode_buku)
                                                        echo '<option value="'.$dt->no_oef.'">'.$dt->no_oef.'</option>';
                                                    }
                                                }
                                                ?>
                                            </select>
                                        </td>
                                        <td class="text-center">
                                            <input class="form-control pqty" type="number" data-id="<?php echo $row->id_product; ?>##<?php echo $row->weight; ?>##<?php echo $row->kode_buku; ?>" name="product_quantity[]" min="0">
                                        </td>
                                    </tr>
                                    <?php } ?>
                                </tbody>
                            </table>
                            <?php } ?>
                        </div>
                    </div>                          
                </div>
                <?php } ?>  
                <?php if(!empty($listBukuHetK13SD)){ ?>
                <div class="panel panel-primary panel-toggled">
                    <div class="panel-heading">
                        <h3 class="panel-title">Buku HET Baru K-13 (SD) &nbsp; <i class="info-collapse">(Klik tombol panah di samping kanan)</i></h3>
                        <ul class="panel-controls">
                            <li><a href="#" class="panel-collapse"><span class="fa fa-angle-up"></span></a></li>
                        </ul>
                    </div>
                    <div class="panel-body panel-body-table">
                        <div>
                            <?php if($listBukuHetK13SD) { ?>
                            <table class="table table-bordered table-striped" width="100%">
                                <thead>
                                    <tr>
                                        <th class="text-center" width="52%">Judul Buku</th>
                                        <th class="text-center" width="15%">Kelas</th>
                                        <th class="text-center" width="16%">No. OEF</th>
                                        <th class="text-center" width="17%">Jumlah</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach($listBukuHetK13SD as $row) { ?>
                                    <tr>
                                        <td>
                                            <?php echo '[ <b>'.$row->kode_buku.'</b> ] &nbsp;'.$row->judul; ?>
                                            <input type="hidden" name="id_product[]" value="<?php echo $row->id_product; ?>">
                                            <input type="hidden" name="weight[]" value="<?php echo $row->weight; ?>">
                                        </td>
                                        <td class="text-center">
                                            <?php echo $row->kelas ?>
                                        </td>
                                        <!-- <td class="text-center">
                                            <input class="form-control no_oef" type="text"  data-id="<?php echo $row->id_product; ?>##<?php echo $row->weight; ?>##<?php echo $row->kode_buku; ?>" name="no_oef[]">
                                        </td> -->
                                        <td class="text-center">
                                            <select class="form-control no_oef" data-id="<?php echo $row->id_product; ?>##<?php echo $row->weight; ?>##<?php echo $row->kode_buku; ?>"  name="no_oef[]">
                                                <option value="">- Pilih Satu -</option>
                                                <?php 
                                                if($listOef) { 
                                                    foreach ($listOef as $dt) {
                                                        if($row->kode_buku == $dt->kode_buku)
                                                        echo '<option value="'.$dt->no_oef.'">'.$dt->no_oef.'</option>';
                                                    }
                                                }
                                                ?>
                                            </select>
                                        </td>
                                        <td class="text-center">
                                            <input class="form-control pqty" type="number" data-id="<?php echo $row->id_product; ?>##<?php echo $row->weight; ?>##<?php echo $row->kode_buku; ?>" name="product_quantity[]" min="0">
                                        </td>
                                    </tr>
                                    <?php } ?>
                                </tbody>
                            </table>
                            <?php } ?>
                        </div>
                    </div>                          
                </div>
                <?php } ?>  
                <?php if(!empty($listBukuHetK13SMP)){ ?>
                <div class="panel panel-primary panel-toggled">
                    <div class="panel-heading">
                        <h3 class="panel-title">Buku HET Baru K-13 (SMP) &nbsp; <i class="info-collapse">(Klik tombol panah di samping kanan)</i></h3>
                        <ul class="panel-controls">
                            <li><a href="#" class="panel-collapse"><span class="fa fa-angle-up"></span></a></li>
                        </ul>
                    </div>
                    <div class="panel-body panel-body-table">
                        <div>
                            <?php if($listBukuHetK13SMP) { ?>
                            <table class="table table-bordered table-striped" width="100%">
                                <thead>
                                    <tr>
                                        <th class="text-center" width="52%">Judul Buku</th>
                                        <th class="text-center" width="15%">Kelas</th>
                                        <th class="text-center" width="16%">No. OEF</th>
                                        <th class="text-center" width="17%">Jumlah</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach($listBukuHetK13SMP as $row) { ?>
                                    <tr>
                                        <td>
                                            <?php echo '[ <b>'.$row->kode_buku.'</b> ] &nbsp;'.$row->judul; ?>
                                            <input type="hidden" name="id_product[]" value="<?php echo $row->id_product; ?>">
                                            <input type="hidden" name="weight[]" value="<?php echo $row->weight; ?>">
                                        </td>
                                        <td class="text-center">
                                            <?php echo $row->kelas ?>
                                        </td>
                                        <!-- <td class="text-center">
                                            <input class="form-control no_oef" type="text"  data-id="<?php echo $row->id_product; ?>##<?php echo $row->weight; ?>##<?php echo $row->kode_buku; ?>" name="no_oef[]">
                                        </td> -->
                                        <td class="text-center">
                                            <select class="form-control no_oef" data-id="<?php echo $row->id_product; ?>##<?php echo $row->weight; ?>##<?php echo $row->kode_buku; ?>"  name="no_oef[]">
                                                <option value="">- Pilih Satu -</option>
                                                <?php 
                                                if($listOef) { 
                                                    foreach ($listOef as $dt) {
                                                        if($row->kode_buku == $dt->kode_buku)
                                                        echo '<option value="'.$dt->no_oef.'">'.$dt->no_oef.'</option>';
                                                    }
                                                }
                                                ?>
                                            </select>
                                        </td>
                                        <td class="text-center">
                                            <input class="form-control pqty" type="number" data-id="<?php echo $row->id_product; ?>##<?php echo $row->weight; ?>##<?php echo $row->kode_buku; ?>" name="product_quantity[]" min="0">
                                        </td>
                                    </tr>
                                    <?php } ?>
                                </tbody>
                            </table>
                            <?php } ?>
                        </div>
                    </div>                          
                </div>
                <?php } ?>  
                <?php if(!empty($listBukuHetK13SMA)){ ?>
                <div class="panel panel-primary panel-toggled">
                    <div class="panel-heading">
                        <h3 class="panel-title">Buku HET Baru K-13 (SMA) &nbsp; <i class="info-collapse">(Klik tombol panah di samping kanan)</i></h3>
                        <ul class="panel-controls">
                            <li><a href="#" class="panel-collapse"><span class="fa fa-angle-up"></span></a></li>
                        </ul>
                    </div>
                    <div class="panel-body panel-body-table">
                        <div>
                            <?php if($listBukuHetK13SMA) { ?>
                            <table class="table table-bordered table-striped" width="100%">
                                <thead>
                                    <tr>
                                        <th class="text-center" width="52%">Judul Buku</th>
                                        <th class="text-center" width="15%">Kelas</th>
                                        <th class="text-center" width="16%">No. OEF</th>
                                        <th class="text-center" width="17%">Jumlah</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach($listBukuHetK13SMA as $row) { ?>
                                    <tr>
                                        <td>
                                            <?php echo '[ <b>'.$row->kode_buku.'</b> ] &nbsp;'.$row->judul; ?>
                                            <input type="hidden" name="id_product[]" value="<?php echo $row->id_product; ?>">
                                            <input type="hidden" name="weight[]" value="<?php echo $row->weight; ?>">
                                        </td>
                                        <td class="text-center">
                                            <?php echo $row->kelas ?>
                                        </td>
                                        <!-- <td class="text-center">
                                            <input class="form-control no_oef" type="text"  data-id="<?php echo $row->id_product; ?>##<?php echo $row->weight; ?>##<?php echo $row->kode_buku; ?>" name="no_oef[]">
                                        </td> -->
                                        <td class="text-center">
                                            <select class="form-control no_oef" data-id="<?php echo $row->id_product; ?>##<?php echo $row->weight; ?>##<?php echo $row->kode_buku; ?>"  name="no_oef[]">
                                                <option value="">- Pilih Satu -</option>
                                                <?php 
                                                if($listOef) { 
                                                    foreach ($listOef as $dt) {
                                                        if($row->kode_buku == $dt->kode_buku)
                                                        echo '<option value="'.$dt->no_oef.'">'.$dt->no_oef.'</option>';
                                                    }
                                                }
                                                ?>
                                            </select>
                                        </td>
                                        <td class="text-center">
                                            <input class="form-control pqty" type="number" data-id="<?php echo $row->id_product; ?>##<?php echo $row->weight; ?>##<?php echo $row->kode_buku; ?>" name="product_quantity[]" min="0">
                                        </td>
                                    </tr>
                                    <?php } ?>
                                </tbody>
                            </table>
                            <?php } ?>
                        </div>
                    </div>                          
                </div>
                <?php } ?>  
                <br /><br />

                <div class="panel panel-default">
                    <!-- <?php if($tipeGudang->is_utama==1) { ?>
                    <div class="panel-body">
                        <div class="form-group">
                            <label class="col-md-2">Jenis / Tipe: </label>
                            <div class="col-md-10">
                                <label>
                                    <input id="is_tag1" value="1" type="radio" name="is_tag" class="is_tag" checked> Transfer Antar Gudang
                                </label>
                                &nbsp; &nbsp; &nbsp;
                                <label>
                                    <input id="is_tag2" value="2" type="radio" name="is_tag" class="is_tag"> Site Sendiri
                                </label>
                            </div>
                        </div>
                    </div>
                    <?php } else { echo '<input type="hidden" value="1" type="radio" name="is_tag" class="is_tag" checked>' ; } ?> -->

                    <div class="form-group panel-footer">
                        <div class="pull-left">
                            <button class="btn btn-success" id="submitRequest">P r o s e s</button>
                        </div>
                        <div class="pull-right">
                            <a href="<?php echo base_url(BACKMIN_PATH.'/gudangrequeststock'); ?>" class="btn btn-primary"><i class="fa fa-arrow-left"></i> Kembali</a>
                        </div>
                    </div>
                </div>
                <br />
            <?php echo form_close(); ?>
        </div>
    </div>
</div>
<!-- END PAGE CONTENT WRAPPER -->