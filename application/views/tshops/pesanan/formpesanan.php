<?php $this->load->view("tshops/header"); ?>

<div class="container main-container" style="margin-top:20px;">
    <div class="row" style="position:fixed;z-index:9999;width:100%;">
        <div class="col-lg-12 col-md-12 col-sm-12">
            <ul class="mybreadcrumb">
                <li><a href="<?php echo base_url(); ?>"><i class="fa fa-home"></i> Beranda</a></li>
                <li><a href="#" class="active" id="bc_teks_2013">Form Pesanan Buku</a></li>
                <li><a href="#">Konfirmasi Pesanan</a></li>
                <li><a href="#">Selesai</a></li>
            </ul>
        </div>
    </div>
    <br/><br/><br/>
    <div class="row">
        <?php
        // if ($this->session->userdata('jenjang')=='1-6') {
        ?>
        <!-- <div class="well" style="background-color:yellow;margin:10px 15px 30px 15px;">
            <p>Untuk pemesanan <b style="color:red;font-size:22px;animation: blinker 1s linear infinite;">Buku Semester 2</b>, dapat dilakukan di:<br /><a href="<?php echo base_url('pesanan/semesterDua'); ?>" onclick="ga('send','event','Intan Semester 2','<?php echo $customer->school_name; ?>','<?php echo $customer->school_name." :: ".$customer->alamat." :: ".$customer->kodepos." :: ".$customer->provinsi." :: ".$customer->kabupaten." :: ".$customer->kecamatan." :: ".$customer->desa; ?>',0);"><b><u>PT. Intan Pariwara</u></b></a>, PT. Pesona Edukasi, PT. Masmedia Buana Pustaka, PT. Temprina Media Grafika, PT. Jepe Press Media Utama</p>
        </div> -->
        <?php // } ?>
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <a href="<?php echo base_url() ?>pesanan/importfilepesanan" class="btn btn-primary"><i class="fa fa-upload"></i> &nbsp; Unggah Berkas Excel</a>
        </div>
        <br/><br/><br/>
    </div>
    <div class="row">
        <div class="col-lg-12 col-md-12 col-sm-12">
            <h1 class="section-title-inner"><span><i class="glyphicon glyphicon-shopping-cart"></i> Form Pesanan</span>
            </h1>
        </div>
    </div>
    <div class="row">
        <div class="col-lg-12 col-md-12 col-sm-12">
        <div class="panel panel-default">
            <div class="panel-heading"><h4 class="panel-title">Data Reference</h4></div>
            <div class="panel-body">
                <div class="form-group">
                    <?php echo form_open(base_url().'pesanan/konfirmasipesanan',
                    'name="form-pesanan" id="form-pesanan" method="post" autocomplete="off"'); ?>
                    <label for="reference_other">No Reference :</label>
                    <input type="text" class="form-control" id="reference_other" name="reference_other">
                </div>
                <div class="form-group">
                    <label for="reference_other_from">Order dari :</label>
                    <select id="reference_other_from" name="reference_other_from" class="form-control" style="width:100%;">
                        <option value="0">SILAHKAN PILIH</option>
                        <?php
                        foreach ($partner as $item) {
                            $selected = ($item->name == $detil['reference_other_from']) ? ' selected' : '';
                            echo '<option value="' . $item->id . ':'. $item->name. '" ' . $selected . '>' . $item->name . '</option>';
                        }
                        ?>
                    </select>
                <!-- <input type="text" class="form-control" id="reference_other_from" name="reference_other_from" value="<?php echo $reference_other_from; ?>"> -->
                </div>
            </div>
        </div>
        </div>
    </div>
    <div class="row">
        <div class="col-lg-12 col-md-12 col-sm-12">
            <?php echo $this->session->flashdata('message') ?>
            <div class="row userInfo">
                <div class="col-xs-12 col-sm-12">
                    <div class="cartContent table-responsive w100">
                        <?php
                        $zona = $this->session->userdata('zona');
                        $price = 'price_'.$zona;
                        $row = 0;
                        foreach ($list_books as $category => $value) {
                            ?>
                            <h3><?php echo $category; ?></h3>
                            <?php
                            $rows[$row] = 0;
                            foreach ($value as $classes => $data) {
                                $category_id = $data[0]['category_id'];
                                ?>
                                <div class="panel-group" id="accordion" role="tablist" aria-multiselectable="true">
                                    <div class="panel panel-default">
                                        <div class="panel-heading" role="tab">
                                            <h4 class="panel-title">
                                                <a role="button" class="collapsed" data-toggle="collapse"
                                                   data-parent="#accordion" href="#<?php echo $category_id ?>"
                                                   aria-expanded="true" aria-controls="<?php echo $category_id ?>">
                                                    <?php echo $classes ?> &nbsp;<i>(Klik kelas atau panah)</i>
                                                    <span id="accordion_icon_<?php echo $category_id ?>"
                                                          style="float:right;"
                                                          class="glyphicon glyphicon-chevron-down"></span>
                                                </a>
                                            </h4>
                                        </div>
                                        <div id="<?php echo $category_id ?>" class="panel-collapse collapse"
                                             role="tabpanel">
                                            <div class="panel-body">
                                                <table class="cartTable" style="width:100%">
                                                    <tbody>
                                                    <tr class="CartProduct">
                                                        <th width="78%" style="text-align:right;">Silahkan Masukkan
                                                            Jumlah Pesanan
                                                        </th>
                                                        <th width="8%"><input size="4" style="text-align:center;"
                                                                              type="text" value="0"
                                                                              id="setAllQty<?php echo $category_id ?>"
                                                                              class="setAllQty number" maxlength="4"></th>
                                                        <th width="14%"><a href="#"
                                                                           style="text-align:center; margin-top:-10px;"
                                                                           onclick="setAllQty(<?php echo $category_id ?>)"
                                                                           class="btn btn-primary">Set Jumlah</a></th>
                                                    </tr>
                                                    </tbody>
                                                </table>
                                                <table class="cartTable" style="width:100%">
                                                    <tbody>
                                                    <tr class="CartProduct cartTableHeader">
                                                        <th width="55%">Judul Buku</th>
                                                        <th width="10%">ISBN</th>
                                                        <th width="15%">Kategori</th>
                                                        <th width="5%">Jumlah</th>
                                                        <th width="15%">Harga Satuan</th>
                                                    </tr>
                                                    <?php
                                                    foreach ($data as $values) {
                                                        $judul = $values['name'];
                                                        $class = (strpos($judul,'Buku Guru') === 0) ? '' : 'jumlah_buku'.$category_id;
                                                        ?>
                                                        <tr class="CartProduct row_teks_2013_1">
                                                            <td style="text-align:left;">
                                                                <a
                                                                    href="<?php echo base_url(); ?>buku/detail/<?php echo $values['id_product']."-".str_replace(" ","-", preg_replace("/[^a-zA-Z0-9 ]/", "", $values['name'])); ?>.html"
                                                                    target="_blank">
                                                                    <?php echo $values['name']; ?>
                                                                </a>
                                                            </td>
                                                            <td>
                                                                <center><?php echo $values['isbn']; ?></center>
                                                            </td>
                                                            <td>
                                                                <center><?php echo $values['category']; ?></center>
                                                            </td>
                                                            <td>
                                                                <center><input class="jumlah <?php echo $class; ?> number"
                                                                               size="4" style="text-align:center;"
                                                                               type="text" value="0"
                                                                               name="qty-<?php echo $values['id_product']; ?>"
                                                                               maxlength="4"></center>
                                                            </td>
                                                            <td class="price"><?php echo toRupiah($values[$price]); ?></td>
                                                        </tr>
                                                    <?php } ?>
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                    <script>
                                        $('#<?php echo $category_id ?>').on('shown.bs.collapse', function () {
                                            $('#accordion_icon_<?php echo $category_id ?>').removeClass("glyphicon-chevron-down");
                                            $('#accordion_icon_<?php echo $category_id ?>').addClass("glyphicon-chevron-up");
                                        });
                                        $('#<?php echo $category_id ?>').on('hidden.bs.collapse', function () {
                                            $('#accordion_icon_<?php echo $category_id ?>').removeClass("glyphicon-chevron-up");
                                            $('#accordion_icon_<?php echo $category_id ?>').addClass("glyphicon-chevron-down");
                                        });
                                    </script>
                                </div>
                                <?php
                                $rows[$row]++;
                            }
                            $row++;
                        }
                        ?>
                    </div>
                    <div class="cartFooter w100">
                        <div class="box-footer">
                            <div id="bottom_step_1" class="pull-left">
                                <a href="#" onclick="setAllNull()" class="btn btn-danger"><i class="fa fa-refresh"></i>
                                    &nbsp; Reset Jumlah</a>
                            </div>
                            <div id="bottom_step_1" class="pull-right">
                                <button type="submit" class="btn btn-primary" id="proses"><i class="fa fa-send"></i>
                                    &nbsp; Proses Pesanan
                                </button>
                            </div>
                        </div>
                    </div>
                    <input type="hidden" name="cust_kabupaten" id="cust_kabupaten"
                           value="<?php echo $customer->kabupaten; ?>">
                    <input type="hidden" name="cust_phone" id="cust_phone" value="<?php echo $customer->phone; ?>">
                    <?php echo form_close(); ?>
                </div>
            </div>
            <br/>
        </div>
    </div>
</div>

<div class="modal" id="modal_phone" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true"
     data-keyboard="false" data-backdrop="static" style="padding-top: 50px;">
    <div class="modal-dialog">
        <?php echo form_open(base_url().'pesanan/addPhone',
            'class="form-horizontal" method="post" id="form_add_phone" autocomplete="off" enctype="multipart/form-data"'); ?>
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Masukkan Nomor Telepon Sekolah</h4>
            </div>
            <div class="modal-body">
                <div class="alert alert-danger alert-dismissable">
                    <span class="glyphicon glyphicon-info-sign"></span>
                    &nbsp; Nomor telepon sekolah anda belum terdaftar, silahkan isi nomor telepon sekolah.
                </div>
                <div class="form-group">
                    <label class="col-sm-5 control-label">No Telepon</label>
                    <div class="col-sm-5">
                        <input class="form-control number" id="no_telp" name="no_telp" placeholder="No Telepon Sekolah"
                               type="text">
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="submit" class="btn btn-success">S i m p a n</button>
            </div>
        </div>
        <?php echo form_close(); ?>
    </div>
</div>

<script type="text/javascript">
    $(document).ready(function () {
        // $("#pesan").on("click", function(){
        //     var reference_other = $("#reference_other").val();
        //     if($reference_other != "")
        //     {
        //         var $reference_other_from = $("#reference_other_from").val();
        //         if($reference_other_from == "")
        //         {
        //             alert("Silahkan isi order dari !");
        //             return false;
        //         }
        //     }
        //     return true;
        // });

        $('#reference_other_from').select2({
            dropdownAutoWidth : true
        }).on('change', function (e) {
            $(this).valid()
        });

        $('.navbar').hide();
        $('.number').keydown(function (e) {
            if ($.inArray(e.keyCode, [46, 8, 9, 27, 13, 110]) !== -1 ||
                (e.keyCode === 65 && (e.ctrlKey === true || e.metaKey === true)) ||
                (e.keyCode >= 35 && e.keyCode <= 40)) {
                return;
            }
            if ((e.shiftKey || (e.keyCode < 48 || e.keyCode > 57)) && (e.keyCode < 96 || e.keyCode > 105)) {
                e.preventDefault();
            }
        });
        $("#form-pesanan").submit(function (e) {
            e.preventDefault();
            var reference_other = $("#reference_other").val();
            if(reference_other != "")
            {
                var reference_other_from = $("#reference_other_from").val();
                if(reference_other_from == 0)
                {
                    alert("Silahkan isi order dari !");
                    return false;
                }
            }
            
            var pesananForm = this;
            bootbox.confirm({
                title: "Konfirmasi",
                message: "Yakin semua isian anda sudah benar?",
                callback: function (result) {
                    if (result) {
                        var jumlah = [];
                        $(".jumlah").each(function () {
                            var qty = $(this).val();
                            if (qty > 0) {
                                jumlah.push(qty);
                            }
                        });
                        jumlah = jumlah.join();
                        if (jumlah != "") {
                            if ($("#cust_phone").val() != "") {
                                pesananForm.submit();
                            } else {
                                $("#modal_phone").modal('toggle');
                                return false;
                            }
                        } else {
                            bootAlert('Jumlah buku tidak boleh semua kosong.');
                            return false;
                        }
                    }
                }
            });
        });
        $("#form_add_phone").submit(function () {
            return confirm('Yakin nomor telepon sudah benar?');
        });

        $('#reference_other').on("change", function(){
            var elem = $(this);
            var reference_other_value = elem.val();
            var data = {
                "reference_other" : reference_other_value
            }
            $.ajax({
                type: "POST",
                data: data,
                dataType: "json",
                url: BASE_URL+'backoffice/orders/check_reference',
                success:function(datas){
                    if(datas.success === 'true') {
                        alert(datas.message);
                        elem.val("");
                        elem.focus();
                    }
                }
            });
        }); 
    });

    function setAllQty(kelas) {
        var elQty = $("#setAllQty" + kelas);
        var qty = elQty.val();
        if (qty === 0 || qty === null || qty === '') {
            alert('Mohon masukkan jumlah yang valid!');
            elQty.focus();
        } else {
            $(".jumlah_buku" + kelas).val(qty);
        }
    }

    function setAllNull() {
        $(".setAllQty").val(0);
        $(".jumlah").val(0);
    }
</script>

<?php $this->load->view("tshops/footer"); ?>
