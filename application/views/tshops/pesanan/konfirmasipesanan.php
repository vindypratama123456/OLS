<?php $this->load->view("tshops/header"); ?>

<link href="<?php echo assets_url('js/admin/plugins/select2/css/select2.min.css?v='.date('YmdHis')); ?>"
      rel="stylesheet">
<div class="container main-container" style="margin-top:20px;">
    <div class="row">
        <div class="col-lg-12 col-md-12 col-sm-12">
            <ul class="mybreadcrumb">
                <li><a href="<?php echo base_url(); ?>"><i class="fa fa-home"></i> Beranda</a></li>
                <li><a href="#" id="bc_teks_2013">Form Pesanan Buku</a></li>
                <li><a href="#" class="active">Konfirmasi Pesanan</a></li>
                <li><a href="#">Selesai</a></li>
            </ul>
        </div>
    </div>
    <br/>

    <div class="row">
        <div class="col-lg-12 col-md-12 col-sm-12">
            <h1 class="section-title-inner"><span><i
                            class="glyphicon glyphicon-shopping-cart"></i> Konfirmasi Pesanan</span></h1>
        </div>
    </div>
    <div class="row">
        <div class="col-lg-12 col-md-12 col-sm-12">
        <div class="panel panel-default">
            <div class="panel-heading"><h4 class="panel-title">Data Reference</h4></div>
            <div class="panel-body">
                <div class="col-md-12">
                    <?php echo form_open('', 'class="form-horizontal" name="frm_confirm" id="frm_confirm"'); ?>
                <div class="form-group">
                    <label for="reference_other">No Reference :</label>
                    <input type="text" class="form-control" id="reference_other" name="reference_other" value="<?php echo $reference_other; ?>">
                </div>
                
                <div class="form-group">
                    <label for="reference_other_from">Order dari :</label>
                    <select id="reference_other_from" name="reference_other_from" class="form-control" style="width:100%;">
                        <option value="0">SILAHKAN PILIH</option>
                        <?php
                        foreach ($partner as $item) {
                            $selected = ($item->name == $reference_other_from) ? ' selected' : '';
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
            <div class="row userInfo">
                <div class="col-xs-12 col-sm-12">
                    <div class="cartContent w100">
                        <table class="cartTable table-responsive" width="100%">
                            <tbody>
                            <tr class="CartProduct cartTableHeader">
                                <td style="text-align:left; padding-left:10px;">Judul Buku</td>
                                <td>ISBN</td>
                                <td>Kategori</td>
                                <td>Harga Satuan</td>
                                <td>Jumlah</td>
                                <td>Harga Total</td>
                            </tr>
                            <?php
                            if ($pesanan) {
                                foreach ($pesanan as $category => $data) { ?>
                                    <tr class="CartProduct cartTableCategoryHeader">
                                        <td colspan="6"
                                            style="text-align:left; padding-left:10px;"><?php echo $category; ?></td>
                                    </tr>
                                    <?php foreach ($data['items'] as $dataPesanan) { ?>
                                        <tr class="CartProduct">
                                            <td style="text-align:left; padding-left:10px;">
                                                <a href="<?php echo base_url(); ?>buku/detail/<?php echo $dataPesanan['product_id']."-".str_replace(" ",
                                                        "-", preg_replace("/[^a-zA-Z0-9 ]/", "",
                                                            $dataPesanan['product_name'])); ?>.html"
                                                   target="_blank"><?php echo $dataPesanan['product_name']; ?></a>
                                            </td>
                                            <td><?php echo $dataPesanan['isbn']; ?></td>
                                            <td><?php echo $dataPesanan['category']; ?></td>
                                            <td><?php echo toRupiah($dataPesanan['unit_price']); ?></td>
                                            <td><input type="number" min="0" class="harga" style="text-align:center;"
                                                       value="<?php echo $dataPesanan['product_quantity']; ?>"
                                                       name="qty-<?php echo $dataPesanan['product_id']; ?>"
                                                       data-tot-price="<?php echo ceil($dataPesanan['unit_price']); ?>"
                                                       maxlength="4" required></td>
                                            <td><?php echo toRupiah($dataPesanan['total_price']); ?></td>
                                        </tr>
                                    <?php } ?>
                                    <?php
                                }
                            } elseif ($this->session->userdata('order')) {
                                foreach ($this->session->userdata('order') as $category => $data) { ?>
                                    <tr class="CartProduct cartTableCategoryHeader">
                                        <td colspan="6"
                                            style="text-align:left; padding-left:10px;"><?php echo $category; ?></td>
                                    </tr>
                                    <?php foreach ($data['items'] as $dataPesanan) { ?>
                                        <tr class="CartProduct">
                                            <td style="text-align:left; padding-left:10px;">
                                                <a href="<?php echo base_url(); ?>buku/detail/<?php echo $dataPesanan['product_id']."-".str_replace(" ",
                                                        "-", preg_replace("/[^a-zA-Z0-9 ]/", "",
                                                            $dataPesanan['product_name'])); ?>.html"
                                                   target="_blank"><?php echo $dataPesanan['product_name']; ?></a>
                                            </td>
                                            <td><?php echo $dataPesanan['isbn']; ?></td>
                                            <td><?php echo $dataPesanan['category']; ?></td>
                                            <td><?php echo toRupiah($dataPesanan['unit_price']); ?></td>
                                            <td><input type="number" min="0" class="harga" style="text-align:center;"
                                                       value="<?php echo $dataPesanan['product_quantity']; ?>"
                                                       name="qty-<?php echo $dataPesanan['product_id']; ?>"
                                                       data-tot-price="<?php echo ceil($dataPesanan['unit_price']); ?>"
                                                       maxlength="4" required></td>
                                            <td><?php echo toRupiah($dataPesanan['total_price']); ?></td>
                                        </tr>
                                    <?php }
                                }
                            } ?>
                            <tr class="CartProduct cartTableHeader">
                                <td colspan="4">&nbsp;</td>
                                <td style="text-align:center;">Total bayar :</td>
                                <?php
                                if ( ! $this->session->userdata('total_pay')) { ?>
                                    <td id="total_pay" class="price"><?php echo toRupiah($total_pay); ?></td>
                                <?php } else { ?>
                                    <td id="total_pay"
                                        class="price"><?php echo toRupiah($this->session->userdata('total_pay')); ?></td>
                                <?php } ?>
                            </tr>
                            <tr>
                                <td colspan="6" id="terbilang" style="font-style:italic;text-align:right;">
                                    Terbilang: <?php echo terbilang(round($total_pay)); ?>
                                </td>
                            </tr>
                            <tr>
                                <td colspan="6">&nbsp;</td>
                            </tr>
                            <?php if ($this->session->userdata('total_pay') > 0) { ?>
                                <tr>
                                    <td colspan="6"><br/></td>
                                </tr>
                                <tr>
                                    <td colspan="6">
                                        <div class="form-group">
                                            <label class="control-label col-sm-9" style="text-align: left;">Apakah ada
                                                <b>Mitra Penjualan</b> yang merekomendasikan untuk memesan ke Gramedia
                                                Mitra Edukasi Indonesia ? </label>
                                            <div class="clearfix"></div>
                                            <div class="col-sm-3">
                                                <div class="radio radio-info">
                                                    <label for="inlineRadio">
                                                        <input checked="" class="ask_mitra" id="inlineRadio_0"
                                                               name="ask_mitra" type="radio" value="0"> Tidak Ada
                                                    </label>
                                                </div>
                                                <div class="radio radio-info">
                                                    <label for="inlineRadio">
                                                        <input class="ask_mitra" id="inlineRadio_1" name="ask_mitra"
                                                               type="radio" value="1"> Ada
                                                    </label>
                                                </div>
                                            </div>
                                            <div class="clearfix"></div>
                                        </div>
                                    </td>
                                </tr>
                                <tr class="content_sales_representatif" style="display: none;">
                                    <td colspan="6">
                                        <div class="form-group">
                                            <label class="control-label col-sm-3" style="text-align: left;">Nama mitra
                                                yang membantu </label>
                                            <div class="col-sm-9">
                                                <select id="sales_representatif" name="sales_representatif"
                                                        class="form-control" style="width: 75%;" disabled="true">
                                                    <option value=''>- Silahkan Pilih Mitra -</option>
                                                    <option value='<?php echo $korwil['id_employee']; ?>'>
                                                        <?php echo $korwil['code'].' - '.strtoupper($korwil['name']); ?>
                                                    </option>
                                                    <?php foreach ($sales_representatif as $row) { ?>
                                                        <option value="<?php echo $row->id_employee ?>"><?php echo $row->code.' - '.strtoupper($row->name); ?></option>
                                                    <?php } ?>
                                                </select>
                                                <br>
                                            </div>
                                            <div class="clearfix"></div>
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <td colspan="6"><br/></td>
                                </tr>
                                <tr>
                                    <td colspan="6">
                                        &nbsp; <input type="checkbox" name="i_agree" id="i_agree"> &nbsp;
                                        <b><i><a href="#" data-toggle="modal" data-target="#modal-agreement">Saya telah
                                                    menyetujui syarat dan ketentuan yang berlaku.</a></i></b><br/><br/>
                                        <div class="modal fade" id="modal-agreement" role="dialog"
                                             aria-labelledby="myModalLabel" data-keyboard="false"
                                             data-backdrop="static">
                                            <div class="modal-dialog">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <button type="button" class="close" data-dismiss="modal"
                                                                aria-label="Close"><span
                                                                    aria-hidden="true">&times;</span></button>
                                                        <h4 class="modal-title">Syarat dan Ketentuan Pemesanan</h4>
                                                    </div>
                                                    <div class="modal-body">
                                                        <div class="row">
                                                            <div class="col-lg-12">
                                                                <div class="well" style="background-color:#fff;">
                                                                    <ul class="list-number">
                                                                        <li><b>Jangka waktu</b> pengiriman buku sesuai
                                                                            dengan <b>kesepakatan</b> yang didapat saat
                                                                            pesanan dikonfirmasi
                                                                        </li>
                                                                        <li>Pesanan buku yang sudah <b>dipesan</b> dan
                                                                            <b>terkonfirmasi</b> tidak dapat dibatalkan
                                                                        </li>
                                                                        <li>Pembayaran dilakukan setelah buku <b>diterima</b>
                                                                            oleh pihak sekolah, melalui transfer ke
                                                                            rekening <b>Bank BRI</b> nomor rekening
                                                                            <i><u>Virtual Account</u></i>
                                                                            <b><?php echo config_item('va_grm').$this->session->userdata('data_user')['npsn']; ?></b>
                                                                            atas nama <b>PT Gramedia</b> dengan
                                                                            menyebutkan kode pesanan
                                                                        </li>
                                                                    </ul>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-primary"
                                                                data-dismiss="modal">Tutup
                                                        </button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                            <?php } ?>
                            </tbody>
                        </table>
                    </div>
                    <div class="cartFooter w100">
                        <div class="box-footer">
                            <div class="pull-left">
                                <?php $url_back = (isset($is_impor) && $is_impor) ? 'importfilepesanan' : 'formpesanan'; ?>
                                <a href="<?php echo base_url('pesanan/'.$url_back); ?>" class="btn btn-inverse"><i
                                            class="fa fa-arrow-left"></i> &nbsp; Kembali</a>
                            </div>
                            <?php if ($this->session->userdata('total_pay') > 0) { ?>
                                <div class="pull-right" id="conf_area">
                                    <input type="hidden" name="order" value="<?php $pesanan; ?>">
                                    <input type="submit" class="btn btn-lg btn-primary" name="conf_button"
                                           value="&nbsp; Konfirmasi Pesanan">
                                </div>
                            <?php } ?>
                        </div>
                    </div>
                    <?php echo form_close(); ?>
                </div>
            </div>
            <br/>
        </div>
    </div>
</div>
<script src="<?php echo js_url('admin/plugins/select2/js/select2.min.js?v='.date('YmdHis')); ?>"></script>
<script type="text/javascript">
    Number.prototype.formatMoney = function (c, d, t) {
        var n = this,
            c = isNaN(c = Math.abs(c)) ? 2 : c,
            d = d == undefined ? "." : d,
            t = t == undefined ? "," : t,
            s = n < 0 ? "-" : "",
            i = parseInt(n = Math.abs(+n || 0).toFixed(c)) + "",
            j = (j = i.length) > 3 ? j % 3 : 0;
        return s + (j ? i.substr(0, j) + t : "") + i.substr(j).replace(/(\d{3})(?=\d)/g, "$1" + t) + (c ? d + Math.abs(n - i).toFixed(c).slice(2) : "");
    };
    $(document).ready(function () {
        $('.navbar').hide();
        var salesRep = $('#sales_representatif');
        var contentSalesRep = $('.content_sales_representatif');
        var elHarga = $('.harga');
        var formConfirm = $('#frm_confirm');

        $('#reference_other_from').select2({
            dropdownAutoWidth : true
        }).on('change', function (e) {
            $(this).valid()
        });

        salesRep.select2({
            dropdownAutoWidth: true
        }).on('change', function (e) {
            $(this).valid()
        });
        $('input[type="radio"]').click(function () {
            var val = $(this).val();
            if (val === 1 || val === '1') {
                contentSalesRep.fadeIn(500);
                salesRep.attr('disabled', false);
                salesRep.select2().on('load', function (e) {
                    $(this).valid();
                });
            } else {
                contentSalesRep.hide();
                salesRep.attr('disabled', true);
                salesRep.select2().on('load', function (e) {
                    $(this).valid();
                });
            }
        });
        elHarga.change(function () {
            var sum = 0;
            elHarga.each(function () {
                subsum = Math.round($(this).val() * $(this).data('tot-price'));
                subsum = (Math.round(subsum)).formatMoney(0, ',', '.');
                $(this).closest('td').next('td').html('Rp. ' + subsum);
                sum += Math.round($(this).val() * $(this).data('tot-price'));
            });
            sum = (Math.round(sum)).formatMoney(0, ',', '.');
            $('#total_pay').html('Rp. ' + sum);
            angka_final = sum.split('.').join('');
            terbilang(angka_final);
            if (angka_final === 0 || angka_final === null || angka_final === '') {
                $('#conf_area').hide();
            }
        });
        elHarga.keydown(function (e) {
            if ($.inArray(e.keyCode, [46, 8, 9, 27, 13, 110]) !== -1 ||
                (e.keyCode === 65 && (e.ctrlKey === true || e.metaKey === true)) ||
                (e.keyCode >= 35 && e.keyCode <= 40)) {
                return;
            }
            if ((e.shiftKey || (e.keyCode < 48 || e.keyCode > 57)) && (e.keyCode < 96 || e.keyCode > 105)) {
                e.preventDefault();
            }
        });
        formConfirm.validate({
            ignore: [],
            rules: {
                sales_representatif: {
                    required: true
                }
            },
            unhighlight: function (element, errorClass) {
                var elem = $(element);
                if (elem.hasClass("select2-hidden-accessible")) {
                    $("#select2-" + elem.attr("id") + "-container").parent().removeClass(errorClass);
                } else {
                    elem.removeClass(errorClass);
                }
            },
            errorPlacement: function (error, element) {
                var elem = $(element);
                if (elem.hasClass("select2-hidden-accessible")) {
                    element = $("#select2-" + elem.attr("id") + "-container").parent();
                    error.insertAfter(element);
                } else {
                    error.insertAfter(element);
                }
            },
            submitHandler: function (form) {
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
                
                var tot_pay = $('#total_pay').html();
                if (tot_pay == 'Rp. 0') {
                    bootAlert('Jumlah total pesanan harus diatas nol (0) !');
                    return false;
                } else {
                    var IsChecked = $('input[name="i_agree"]:checked').length;
                    if (IsChecked > 0) {
                        bootbox.confirm({
                            title: 'Konfirmasi',
                            message: 'Yakin dengan semua pesanan anda?<br><br>Jangka waktu pengiriman buku sesuai dengan kesepakatan yang didapat saat pesanan dikonfirmasi<br><br>Pesanan buku yang sudah dipesan dan terkonfirmasi tidak dapat dibatalkan',
                            callback: function (result) {
                                if (result) {
                                    $('button').attr('disabled', true);
                                    $.ajax({
                                        type: 'POST',
                                        data: formConfirm.serialize(),
                                        dataType: 'json',
                                        url: BASE_URL + 'pesanan/prosespesanan',
                                        async: true,
                                        beforeSend: function () {
                                            $('.bootbox').modal('hide').data('bs.modal', null);
                                            $('#myloader').show();
                                            $('button').attr('disabled', true);
                                        },
                                        success: function (datas) {
                                            if (datas.success == 'true') {
                                                window.location.href = BASE_URL + 'pesanan';
                                            } else {
                                                bootAlert(datas.message);
                                                $('#myloader').hide();
                                                $('button').attr('disabled', false);
                                            }
                                        }
                                    });
                                }
                            }
                        });
                        return false;
                    } else {
                        bootAlert('Mohon setujui (centang) syarat dan ketentuan yang berlaku.');
                        $('#i_agree').focus();
                        return false;
                    }
                }
            }
        });
    });
</script>

<?php $this->load->view("tshops/footer"); ?>
