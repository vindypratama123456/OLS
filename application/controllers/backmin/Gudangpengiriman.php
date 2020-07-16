<?php
defined('BASEPATH') or exit('No direct script access allowed');

/**
 * @property Datatables $datatables
 * @property Excel $excel
 * @property Mod_general $mod_general
 * @property Mod_gudang $mod_gudang
 */
class Gudangpengiriman extends MY_Controller
{
    public function __construct()
    {
        parent::__construct();
        if (!in_array($this->adm_level, $this->backmin_gudang_area)) {
            redirect(BACKMIN_PATH);
        }
        $this->load->model('mod_general');
        $this->load->model('mod_gudang');
    }

    public function index()
    {
        $data['page_title'] = 'List Daftar Pengiriman';
        $data['content'] = $this->load->view(BACKMIN_PATH . '/gudang/pengiriman/list', $data, true);
        $data['script_js'] = $this->load->view(BACKMIN_PATH . '/gudang/pengiriman/list_js', '', true);
        $this->load->view(BACKMIN_PATH . '/main', $data);
    }

    public function listPengiriman()
    {
        if ( ! $this->input->is_ajax_request()) {
            return false;
        }
        $this->load->library('datatables');
        $this->output->set_header('Content-Type:application/json; charset=utf-8');
        $this->datatables->select('a.id_spk AS id_spk, a.kode_spk AS kode_spk, a.total_jumlah AS total_jumlah, a.total_berat AS total_berat, b.nama AS nama_ekspedisi, CONCAT("<center>",a.created_date,"</center>") AS date_add, CASE a.status WHEN 1 THEN CONCAT("<center><span class=\'label label-default\'>Dibuat</span></center>") WHEN 2 THEN CONCAT("<center><span class=\'label label-primary\'>Dikirim</span></center>") WHEN 3 THEN CONCAT("<center><span class=\'label label-error\'>Dibatalkan</span></center>") WHEN 4 THEN CONCAT("<center><span class=\'label label-success\'>Telah Sampai</span></center>") END AS status, (SELECT GROUP_CONCAT(if(l.is_to_school=1, concat(l.`kode_pesanan`, "-", m.school_name, "-", IF(ISNULL(l.status_transaksi),"Dibuat",CASE l.status_transaksi WHEN 1 THEN "Dibuat" WHEN 2 THEN "Diproses" WHEN 3 THEN "Menunggu TAG" WHEN 4 THEN "SPK Dibuat" WHEN 5 THEN "Dikirim Ekspedisi" WHEN 6 THEN "Telah Sampai" END)), n.nama_gudang) SEPARATOR "<br>") FROM spk_detail k LEFT JOIN transaksi l ON k.id_transaksi = l.id_transaksi LEFT JOIN customer m ON l.tujuan = m.id_customer LEFT JOIN master_gudang n ON l.tujuan = n.id_gudang WHERE k.id_spk = a.id_spk) AS tujuan');
        $this->datatables->from('spk a');
        $this->datatables->join('ekspeditur b', 'a.id_ekspeditur = b.id', 'inner');
        $this->datatables->where('a.gudang_asal', $this->adm_id_gudang);
        $this->datatables->edit_column('kode_spk', '<a href="' . base_url(BACKMIN_PATH . '/gudangpengiriman/detailPengiriman/$1') . '">$2</a>', 'id_spk, kode_spk');
        $this->datatables->edit_column('total_jumlah', '<center>$1</center>', 'total_jumlah');
        $this->datatables->edit_column('total_berat', '<center>$1</center>', 'total_berat');
        $this->datatables->edit_column('tujuan', '$1', 'tujuan');
        $this->datatables->add_column('detail', '<center><a href="' . base_url(BACKMIN_PATH . '/gudangpengiriman/detailPengiriman/$1') . '" class="btn btn-default btn-rounded btn-condensed btn-sm"><span class="fa fa-search"></span></a></center>', 'id_spk');
        $this->output->set_output($this->datatables->generate());
    }

    public function detailPengiriman($id)
    {
        if ($id && is_numeric($id)) {
            $data['page_title'] = 'Detil Surat Jalan Ekspeditur';
            $data['detail'] = $this->mod_general->detailData('spk', 'id_spk', $id);
            $data['ekspeditur'] = $this->mod_general->detailData('ekspeditur', 'id', $data['detail']['id_ekspeditur']);
            $data['list_transaksi'] = $this->mod_gudang->getListTransaksiBySPK($id);
            $data['status'] = '';
            switch ($data['detail']['status']) {
                case 1:
                    $data['status'] = 'Dibuat';
                    break;
                case 2:
                    $data['status'] = 'Dikirim';
                    break;
                case 3:
                    $data['status'] = 'Dibatalkan';
                    break;
                case 4:
                    $data['status'] = 'Telah Sampai';
                    break;
            }
			
            $data['content'] = $this->load->view(BACKMIN_PATH . '/gudang/pengiriman/detil', $data, true);
            $data['script_js'] = $this->load->view(BACKMIN_PATH . '/gudang/pengiriman/detil_js', '', true);
            $this->load->view(BACKMIN_PATH . '/main', $data);
        } else {
            redirect(BACKMIN_PATH . '/gudangpengiriman/index');
        }
    }

    public function add()
    {
        $data['page_title'] = 'Buat Surat Jalan Ekspeditur';
        $data['id_gudang_asal'] = $this->adm_id_gudang;
        $data['ekspeditur'] = $this->mod_gudang->getAllEkspeditur();
        $data['content'] = $this->load->view(BACKMIN_PATH . '/gudang/pengiriman/form_add', $data, true);
        $data['script_js'] = $this->load->view(BACKMIN_PATH . '/gudang/pengiriman/form_add_js', '', true);
        $this->load->view(BACKMIN_PATH . '/main', $data);
    }

    public function listTransaksi()
    {
        if ( ! $this->input->is_ajax_request()) {
            return false;
        }
        $this->load->library('datatables');
        $this->output->set_header('Content-Type:application/json; charset=utf-8');
        $this->datatables->select('
                            a.id_transaksi AS id_transaksi, 
                            a.total_jumlah AS total_jumlah, 
                            a.total_berat AS total_berat, 
                            IF(a.is_to_school=1, a.kode_pesanan, a.id_request) AS kode_transaksi,
                            IF(a.is_to_school=1, b.school_name, c.nama_gudang) AS tujuan, 
                            IF(a.is_to_school=1, CONCAT(b.alamat,", ",b.desa,", ",b.kecamatan,", ",b.kabupaten,", ",b.provinsi," - ",b.kodepos), c.alamat_gudang) AS alamat, 
                            a.id_pesanan AS id_pesanan,
                            b.id_customer AS id_customer'
                        );
        $this->datatables->from('transaksi a');
        $this->datatables->join('customer b', 'b.id_customer = a.tujuan', 'left');
        $this->datatables->join('master_gudang c', 'c.id_gudang = a.tujuan', 'left');
        $this->datatables->where('a.asal', $this->adm_id_gudang);
        $this->datatables->where('a.status_transaksi', 2);
        $this->datatables->edit_column('id_transaksi', '<center><a href="' . base_url(BACKMIN_PATH . '/gudangpesanan/detailPesananDiproses/$1') . '" target="_blank">$2</a></center>', 'id_pesanan, id_transaksi');
        $this->datatables->edit_column('kode_transaksi', '<strong>#$1</strong>', 'kode_transaksi');
        $this->datatables->edit_column('tujuan', '$1<br>$2', 'tujuan, alamat');
        $this->datatables->edit_column('total_jumlah', '<center>$1</center>', 'total_jumlah');
        $this->datatables->edit_column('total_berat', '<center>$1</center>', 'total_berat');
        $this->datatables->add_column('action', '<center><input type="checkbox" id="transaksi_$1" value="$1##$2##$3##$4##$5" class="check_transaksi"></center>', 'id_transaksi, total_jumlah, total_berat, id_customer, id_pesanan');
        $this->output->set_output($this->datatables->generate());
    }

    ## TODO : Buat log dan auditor
    public function prosesAddEkspeditur()
    {
        if ( ! $this->input->is_ajax_request()) {
            redirect(BACKMIN_PATH . '/gudangpengiriman/add', 'refresh');
        }
        
        if (in_array($this->adm_level, $this->auditor_area)) {
            // $this->session->set_flashdata('error', 'Maaf, anda tidak dapat melakukan proses ini.');
            $callBack   = [   
                "success"       => "false",
                "message"       => "Maaf, anda tidak dapat melakukan proses ini.",
            ];
        } else {
            $this->db->trans_begin();
            $data['nama']       = $this->input->post('nama_ekspeditur');
            $data['alamat']     = $this->input->post('alamat_ekspeditur');
            $data['telpon']     = $this->input->post('no_telpon_ekspeditur');

            $id = $this->mod_gudang->add('ekspeditur', $data);

            if ($this->db->trans_status() === true) {
                ## ACTION LOG USER
                $logs['id_ekspeditur'] = $id;
                $this->logger->logAction('Proses Tambah Ekspeditur', $logs);
                
                $this->db->trans_commit();
                $callBack = [
                    "success"   => "true",
                    "message"   => "Ekspeditur berhasil ditambahkan",
                    "redirect"  => "backmin/gudangpengiriman/add"
                ];
            } else {
                $this->db->trans_rollback();
                // $this->session->set_flashdata('error', 'Gagal melakukan proses ini.');
                $callBack = [
                    "success"   => "false",
                    "message"   => "Gagal menambah ekspeditur",
                ];
            }
        }
        echo json_encode($callBack);
    }

    ## TODO : Buat log dan auditor
    public function prosesAddSPK()
    {
        if ( ! $this->input->is_ajax_request()) {
            redirect(BACKMIN_PATH . '/gudangpengiriman/add', 'refresh');
        }

        if (in_array($this->adm_level, $this->auditor_area)) {
            $this->session->set_flashdata('error', 'Maaf, anda tidak dapat melakukan proses ini.');
            $callBack   = [   
                "success"       => "false",
                "message"       => "Tidak dapat melakukan proses ini.",
                "redirect"      => "backmin/gudangpengiriman/add",
            ];
        } else {
            $error = [];
            if (empty($this->input->post('ekspeditur'))) {
                $error[]    = "<strong>Ekspeditur</strong> tidak boleh kosong!";
            }
            if (empty($this->input->post('nopol'))) {
                $error[]    = "<strong>No polisi</strong> tidak boleh kosong!";
            }
            if (empty($this->input->post('nama_supir'))) {
                $error[]    = "<strong>Nama supir</strong> tidak boleh kosong!";
            }
            if (empty($this->input->post('hp_supir'))) {
                $error[]    = "<strong>No hp supir</strong> tidak boleh kosong!";
            }
            if (empty($this->input->post('transaksi'))) {
                $error[]    = "<strong>Transaksi</strong> harus dipilih!";
            }

            if (count($error) > 0) {

                $this->session->set_flashdata('error', $error);
                $callBack   = ["success" => "false"];

            } else {
                $this->db->trans_begin();
                $year           = date('y');
                $id_gudang      = sprintf("%02s", $this->adm_id_gudang);
                $combine        = $year . $id_gudang;
                $isExistSPK     = $this->mod_gudang->checkSPKNumber($combine);

                if ($isExistSPK > 0) {
                    $lastSPK    = $this->mod_gudang->getSPKNumber($combine);
                    $numberSPK  = $lastSPK->last_number + 1;
                    $spk        = "SPK-" . $combine . sprintf("%05s", $numberSPK);
                } else {
                    $spk        = "SPK-" . $combine . "00001";
                }

                $dataSPK['kode_spk']        = $spk;
                $dataSPK['gudang_asal']     = $this->adm_id_gudang;
                $dataSPK['id_ekspeditur']   = $this->input->post('ekspeditur');
                $dataSPK['nopol']           = $this->input->post('nopol');
                $dataSPK['nama_supir']      = $this->input->post('nama_supir');
                $dataSPK['hp_supir']        = $this->input->post('hp_supir');
                $dataSPK['status']          = 1;
                $dataSPK['created_date']    = date('Y-m-d H:i:s');
                $dataSPK['created_by']      = $this->adm_id;
                $dataSPK['modified_date']   = date('Y-m-d H:i:s');
                $dataSPK['modified_by']     = $this->adm_id;
                
                $id = $this->mod_gudang->add('spk', $dataSPK);

                $detailSPK['id_transaksi']  = explode(',', $this->input->post('transaksi'));
                $detailSPK['berat']         = explode(',', $this->input->post('berat'));
                $detailSPK['jumlah']        = explode(',', $this->input->post('jumlah'));
                $dataDetailSPK              = [];
                foreach ($detailSPK as $field => $data) {
                    foreach ($data as $row => $value) {
                        $dataDetailSPK[$row]['id_spk']          = $id;
                        $dataDetailSPK[$row][$field]            = $value;
                        $dataDetailSPK[$row]['status']          = 1;
                        $dataDetailSPK[$row]['created_date']    = date('Y-m-d H:i:s');
                        $dataDetailSPK[$row]['created_by']      = $this->adm_id;
                        $dataDetailSPK[$row]['modified_date']   = date('Y-m-d H:i:s');
                        $dataDetailSPK[$row]['modified_by']     = $this->adm_id;
                    }
                }

                $dataTambahanSPK['total_jumlah']    = 0;
                $dataTambahanSPK['total_berat']     = 0;

                foreach ($dataDetailSPK as $rows => $values) {
                    $exist_transaction  = $this->mod_gudang->getRow("spk_detail", "id_transaksi = " . $values['id_transaksi']);
                    if ($exist_transaction == 0) {
                        $this->mod_gudang->addDetail('spk_detail', $values);
                        
                        $transaksiStatus['status_transaksi']    = 4;
                        $transaksiStatus['updated_date']        = date('Y-m-d H:i:s');
                        $transaksiStatus['updated_by']          = $this->adm_id;

                        $this->mod_gudang->edit('transaksi', 'id_transaksi =' . $values['id_transaksi'], $transaksiStatus);
                        $this->mod_gudang->addTransaksiHistory($values['id_transaksi'], 4);

                        $id_request = $this->mod_general->detailData('transaksi', 'id_transaksi', $values['id_transaksi'])['id_request'];
                        // jika transaksi adalah permintaan buku
                        if ($id_request != '') {
                            // vindy 2019-12-03 
                            // -- REMOVE
                            // $request['status']                  = 4;
                            // $request['updated_date']            = date('Y-m-d H:i:s');
                            // $request['updated_by']              = $this->adm_id;
                            
                            // $this->mod_gudang->edit('request_stock', 'id_request =' . $id_request, $request);
                            // -- END REMOVE
                        }

                        $dataTambahanSPK['total_jumlah']        += $values['jumlah'];
                        $dataTambahanSPK['total_berat']         += $values['berat'];
                    }
                }

                $this->mod_gudang->edit('spk', 'id_spk =' . $id, $dataTambahanSPK);

                if ($this->db->trans_status() === true) {
                    ## ACTION LOG USER
                    $logs['id_spk'] = $id;
                    $this->logger->logAction('Proses Pembuatan SPK', $logs);
                    
                    $this->db->trans_commit();
                    $this->session->set_flashdata('success', 'SPK #<b>' . $spk . '</b> berhasil dibuat.');
                    $callBack = [
                        "success" => "true",
                        "message" => "SPK telah berhasil dibuat",
                        "redirect" => "backmin/gudangpengiriman/index"
                    ];
                } else {
                    $this->db->trans_rollback();
                    $this->session->set_flashdata('error', 'Gagal melakukan proses ini.');
                    $callBack = [
                        "success"   => "false",
                        "message"   => "Gagal melakukan proses",
                        "redirect"  => "backmin/gudangpengiriman/add",
                    ];
                }
            }
        }
        echo json_encode($callBack);
    }

    ## TODO : Buat log dan auditor
    public function prosesSPK()
    {
        if ( ! $this->input->is_ajax_request()) {
            redirect(BACKMIN_PATH . '/gudangpengiriman', 'refresh');
        }

        $id_spk         = $this->input->post('id_spk');
        if (in_array($this->adm_level, $this->auditor_area)) {
            $this->session->set_flashdata('error', 'Maaf, anda tidak dapat melakukan proses ini.');
            $callBack   = [   
                "success"       => "false",
                "message"       => "Tidak dapat melakukan proses ini.",
                "redirect"      => "backmin/gudangpengiriman/detailPengiriman/$id_spk",
            ];
        } else {
            $this->db->trans_begin();
            $data['status']             = 2;
            $data['modified_date']      = date('Y-m-d H:i:s');
            $data['modified_by']        = $this->adm_id;

            $this->mod_gudang->edit('spk', 'id_spk = ' . $id_spk, $data);
            $this->mod_gudang->edit('spk_detail', 'id_spk = ' . $id_spk, $data);

            $transaksi['status_transaksi']      = 5;
            $transaksi['updated_date']          = date('Y-m-d H:i:s');
            $transaksi['updated_by']            = $this->adm_id;

            $stock_status                       = true;
            foreach ($this->input->post('id_transaksi') as $key => $id_transaksi) {
                $this->mod_gudang->edit('transaksi', 'id_transaksi = ' . $id_transaksi, $transaksi);
                $this->mod_gudang->addTransaksiHistory($id_transaksi, 5);

                $dataTransaksi      = $this->mod_general->detailData('transaksi', 'id_transaksi', $id_transaksi);
                $detailTransaksi    = $this->mod_gudang->getAll('transaksi_detail', '*', 'id_transaksi =' . $id_transaksi);
                if ($dataTransaksi['id_request'] != '') {
                    ## PESANAN TAG
                    $periodeRequest = $this->mod_gudang->getAll('request_stock', 'periode', 'id_request = ' . $dataTransaksi['id_request'])[0]->periode;
                    if ($periodeRequest == $this->periode) {
                        foreach ($detailTransaksi as $row) {
                            if ($dataTransaksi['ref_id']) {
                                // Change stock shipper
                                $stokPengirim                           = $this->mod_gudang->getStok($dataTransaksi['asal'], $row->id_produk, 'stok_fisik, stok_booking');
                                $dataStockPengirim['stok_fisik']        = $stokPengirim->stok_fisik - $row->jumlah;
                                $dataStockPengirim['stok_booking']      = $stokPengirim->stok_booking - $row->jumlah;
                                // Change stock consignee
                                $stokPenerima                           = $this->mod_gudang->getStok($dataTransaksi['tujuan'], $row->id_produk, 'stok_fisik, stok_booking');
                                $dataStockPenerima['stok_fisik']        = $stokPenerima->stok_fisik + $row->jumlah;
                                $dataStockPenerima['stok_booking']      = $stokPenerima->stok_booking + $row->jumlah;

                                ## NOTES : Must get stock status method here if TAG for orders active
                            } else {
                                // Change stock shipper
                                $stokPengirim                           = $this->mod_gudang->getStok($dataTransaksi['asal'], $row->id_produk, 'stok_fisik, stok_booking');
                                $dataStockPengirim['stok_fisik']        = $stokPengirim->stok_fisik - $row->jumlah;
                                $dataStockPengirim['stok_booking']      = $stokPengirim->stok_booking - $row->jumlah;
                                // Change stock consignee
                                $stokPenerima                           = $this->mod_gudang->getStok($dataTransaksi['tujuan'], $row->id_produk, 'stok_fisik, stok_available');
                                $dataStockPenerima['stok_fisik']        = $stokPenerima->stok_fisik + $row->jumlah;
                                $dataStockPenerima['stok_available']    = $stokPenerima->stok_available + $row->jumlah;

                                $stock_status = $this->addReportStockStatus($dataTransaksi['asal'], $dataTransaksi['tujuan'], $row->id_produk, $row->jumlah);
                            }
    
                            $this->mod_gudang->updateStok($dataTransaksi['asal'], $row->id_produk, $dataStockPengirim);
                            $this->mod_gudang->updateStok($dataTransaksi['tujuan'], $row->id_produk, $dataStockPenerima);
                        }
                    }

                    // VINDY 2019-12-03 
                    // -- REMOVE
                    // Untuk Request
                    // $request['status']              = 5;
                    // $request['updated_date']        = date('Y-m-d H:i:s');
                    // $request['updated_by']          = $this->adm_id;

                    // $this->mod_gudang->edit('request_stock', 'id_request =' . $dataTransaksi['id_request'], $request);
                    // -- END REMOVE
                } elseif ($dataTransaksi['id_pesanan'] != '') {
                    ## PESANAN SEKOLAH
                    $periodeOrder = $this->mod_gudang->getAll('orders', 'periode', 'id_order = ' . $dataTransaksi['id_pesanan'])[0]->periode;

                    if ($periodeOrder == $this->periode) {
                        foreach ($detailTransaksi as $row) {
                            // Change stock shipper
                            $stokGudang                     = $this->mod_gudang->getStok($this->adm_id_gudang, $row->id_produk, 'stok_fisik, stok_booking');
                            $dataStock['stok_fisik']        = $stokGudang->stok_fisik - $row->jumlah;
                            $dataStock['stok_booking']      = $stokGudang->stok_booking - $row->jumlah;

                            $this->mod_gudang->updateStok($this->adm_id_gudang, $row->id_produk, $dataStock);
                        }
                    }

                    // tambahkan validasi 
                    // jika jumlah row pada detail transaksi = jumlah row buku pada detail pesanan
                    // maka update tabel order dan tabel scm
                    // 

                    $data_product = $this->mod_gudang->check_list_product_leftover($dataTransaksi['id_pesanan'], $dataTransaksi['asal']);
                    if(count($data_product) == 0)
                    {
                        // Untuk Order
                        $order['current_state']         = 6;
                        $order['date_upd']              = date('Y-m-d H:i:s');

                        $this->mod_gudang->edit('orders', 'id_order =' . $dataTransaksi['id_pesanan'], $order);
                        $this->mod_gudang->addOrderHistory($dataTransaksi['id_pesanan'], 6);

                        // Untuk Order SCM
                        $orderSCM['status']             = 3;
                        $orderSCM['date_modified']      = date('Y-m-d H:i:s');

                        $this->mod_gudang->edit('order_scm', 'id_order =' . $dataTransaksi['id_pesanan'], $orderSCM);
                    }
                }
            }
            
            if ($stock_status) {
                if ($this->db->trans_status() === true) {
                    ## ACTION LOG USER
                    $logs['id_spk'] = $id_spk;
                    $this->logger->logAction('Proses Pesanan Kirim', $logs);
                    
                    $this->db->trans_commit();
                    $this->session->set_flashdata('success', 'Status SPK #<b>' . $this->input->post('kode_spk') . '</b> berhasil diubah.');
                    $callBack = [
                        "success" => "true",
                        "message" => "Status SPK telah diubah",
                        "redirect" => "backmin/gudangpengiriman/index"
                    ];
                } else {
                    $this->db->trans_rollback();
                    $this->session->set_flashdata('error', 'Gagal melakukan proses ini.');
                    $callBack = [
                        "success"   => "false",
                        "message"   => $e->message(),
                        "redirect"  => "backmin/gudangpengiriman/detailPengiriman/$id_spk",
                    ];
                }
            } else {
                $this->db->trans_rollback();
                $this->session->set_flashdata('error', 'Gagal update laporan stock status.');
                $callBack = [
                    "success"   => "false",
                    "message"   => "Gagal update laporan stock status.",
                    "redirect"  => "backmin/gudangpengiriman/detailPengiriman/$id_spk",
                ];
            }
        }
        echo json_encode($callBack);
    }

    ## TODO : Buat log dan auditor
    public function batalPengiriman($idSPK, $idTransaksi)
    {
        if (!$this->input->is_ajax_request()) {
            redirect(BACKMIN_PATH . '/gudangpengiriman/detailPengiriman/'.$idSPK, 'refresh');
        }
        try {
            if (in_array($this->adm_level, $this->auditor_area)) {
                $this->session->set_flashdata('error', 'Maaf, anda tidak dapat melakukan proses ini.');
                $callBack   = [   
                    "success"       => "false",
                    "message"       => "Tidak dapat melakukan proses ini.",
                    "redirect"      => "backmin/gudangpengiriman/detailPengiriman/$idSPK",
                ];
            } else {
                $this->db->trans_begin();

                $data = $this->mod_gudang->getAll('transaksi', '*', 'id_transaksi = '.$idTransaksi)[0];
                
                $transaksi = [
                    'status_transaksi'  => 2,
                    'updated_date'      => date('Y-m-d H:i:s'),
                    'updated_by'        => $this->adm_id
                ];
                
                $this->mod_gudang->edit('transaksi', 'id_transaksi =' . $idTransaksi, $transaksi);
                $this->mod_gudang->delete('transaksi_history', 'id_transaksi = '.$idTransaksi.' and (status_transaksi = 2 or status_transaksi > 3)');
                $this->mod_gudang->addTransaksiHistory($idTransaksi, 2);

                $this->mod_gudang->delete('spk_detail', 'id_transaksi = '.$idTransaksi.' and id_spk = '.$idSPK);
                $total_spk = $this->mod_gudang->getAll('spk_detail', 'sum(jumlah) as total_jumlah, sum(berat) as total_berat', 'id_spk = '.$idSPK)[0];

                $spk = [
                    'total_jumlah'      => $total_spk->total_jumlah,
                    'total_berat'       => $total_spk->total_berat
                ];
                $this->mod_gudang->edit('spk', 'id_spk =' . $idSPK, $spk);

                if ($this->db->trans_status() === true) {
                    ## ACTION LOG USER
                    $logs['id_transaksi']   = $idTransaksi;
                    $logs['id_spk']         = $idSPK;
                    $this->logger->logAction('Proses Pesanan Batal Kirim', $logs);
                    
                    $this->db->trans_commit();
                    $this->session->set_flashdata('success', 'Kode pesanan <b>#'.$data->kode_pesanan.'</b> berhasil dibatalkan!');
                    $callBack = [
                        "success"   => "true",
                        "message"   => "pesanan telah dibatalkan",
                        "redirect"  => "backmin/gudangpengiriman/detailPengiriman/".$idSPK
                    ];
                } else {
                    $this->db->trans_rollback();
                    $this->session->set_flashdata('error', 'Kode pesanan <b>#'.$data->kode_pesanan.'</b> gagal dibatalkan!');
                    $callBack = [
                        "success"   => "false",
                        "message"   => $e->message(),
                        "redirect"  => "backmin/gudangpengiriman/detailPengiriman/$idSPK",
                    ];
                }
            }
            echo json_encode($callBack);
        } catch (Exception $e) {
            redirect(BACKMIN_PATH . '/gudangpengiriman/detailPengiriman/'.$idSPK, 'refresh');
        }
    }

    public function cetakBAST($idTransaksi, $idOrder)
    {
        if (!in_array($this->adm_level, $this->backmin_gudang_area) && !$idTransaksi) {
            redirect(BACKMIN_PATH . '/gudangpengiriman', 'refresh');
        }

        $orders                 = $this->mod_general->detailData('orders', 'id_order', $idOrder);
        $data['order']          = $orders;
        $data['detil']          = $this->mod_general->detailData('transaksi', 'id_transaksi', $idTransaksi);
        $data['customer']       = $this->mod_general->detailData('customer', 'id_customer', $orders['id_customer']);
        $data['listproducts']   = $this->mod_gudang->getListOrderProduct($idTransaksi, $idOrder);
        
        $this->load->view('backmin/gudang/pengiriman/print_bast', $data);
    }

    public function cetakBASTFull($idTransaksi, $idOrder)
    {
        if (!in_array($this->adm_level, $this->backmin_gudang_area) && !$idTransaksi) {
            redirect(BACKMIN_PATH . '/gudangpengiriman', 'refresh');
        }

        $orders                 = $this->mod_general->detailData('orders', 'id_order', $idOrder);
        $data['order']          = $orders;
        $data['detil']          = $this->mod_general->detailData('transaksi', 'id_transaksi', $idTransaksi);
        $data['customer']       = $this->mod_general->detailData('customer', 'id_customer', $orders['id_customer']);
        $data['listproducts']   = $this->mod_gudang->getListProductBASTFull($idOrder);
        
        $this->load->view('backmin/gudang/pengiriman/print_bast', $data);
    }

    public function cetakNotaJual($idTransaksi, $idOrder)
    {
        if (!in_array($this->adm_level, $this->backmin_gudang_area) && !$idTransaksi) {
            redirect(BACKMIN_PATH . '/gudangpengiriman', 'refresh');
        }

        $orders                 = $this->mod_general->detailData('orders', 'id_order', $idOrder);
        $data['order']          = $orders;
        $data['detil']          = $this->mod_general->detailData('transaksi', 'id_transaksi', $idTransaksi);
        $data['customer']       = $this->mod_general->detailData('customer', 'id_customer', $orders['id_customer']);
        $data['listproducts']   = $this->mod_gudang->getListOrderProduct($idTransaksi, $idOrder);

        $this->load->view('backmin/gudang/pengiriman/cetak_nota_penjualan', $data);
    }

    public function cetakTagihan($idTransaksi, $idOrder)
    {
        if (!in_array($this->adm_level, $this->backmin_gudang_area) && !$idTransaksi) {
            redirect(BACKMIN_PATH . '/gudangpengiriman', 'refresh');
        }

        $orders                 = $this->mod_general->detailData('orders', 'id_order', $idOrder);
        $data['order']          = $orders;
        $data['detil']          = $this->mod_general->detailData('transaksi', 'id_transaksi', $idTransaksi);
        $data['customer']       = $this->mod_general->detailData('customer', 'id_customer', $orders['id_customer']);
        $data['listproducts']   = $this->mod_gudang->getListOrderProduct($idTransaksi, $idOrder);

        $this->load->view('backmin/gudang/pengiriman/cetak_tagihan', $data);
    }

    public function cetakSJE($id)
    {
        if ($id) {
            $data['detail']             = $this->mod_general->detailData('spk', 'id_spk', $id);
            $data['ekspeditur']         = $this->mod_general->detailData('ekspeditur', 'id', $data['detail']['id_ekspeditur']);
            $data['list_transaksi']     = $this->mod_gudang->getListTransaksiBySPK($id);

            $this->load->view(BACKMIN_PATH . '/gudang/pengiriman/cetak_surat_jalan', $data);
        } else {
            redirect(BACKMIN_PATH . '/gudangpengiriman');
        }
    }

    public function cetakSJE_TAG($id)
    {
        if ($id) {
            $data['detail']             = $this->mod_general->detailData('spk', 'id_spk', $id);
            $data['ekspeditur']         = $this->mod_general->detailData('ekspeditur', 'id', $data['detail']['id_ekspeditur']);
            $data['list_transaksi']     = $this->mod_gudang->getListTransaksiBySPK_TAG($id);

            $this->load->view(BACKMIN_PATH . '/gudang/pengiriman/cetak_surat_jalan_tag', $data);
        } else {
            redirect(BACKMIN_PATH . '/gudangpengiriman');
        }
    }

    public function eksporExcel()
    {
        $this->load->library('excel');
        $data['list_transaksi']     = $this->mod_gudang->getTransaksiSiapKirim();
        $data['nama_gudang']        = $this->session->userdata('nama_gudang');

        $this->load->view(BACKMIN_PATH . '/gudang/pengiriman/cetak_list_transaksi', $data);
    }

    public function addReportStockStatus($id_gudang_asal, $id_gudang_tujuan, $id_produk, $jumlah)
    {
        $this->db->trans_begin();
        if ($id_gudang_asal && $id_gudang_tujuan && $id_produk) {

            $today      = date('Y-m-d H:i:s');
            $month      = date('n');
            $year       = date('Y');

            #################
            ## GUDANG ASAL ##
            #################
            $stock_status_asal                  = $this->mod_gudang->getLastStockStatus($now = 1, $id_gudang_asal, $id_produk, $month, $year);
            $report_asal                        = [];
            if ($stock_status_asal) {
                // In same month and year
                $total_qty_asal                 = (int)($stock_status_asal['stok_fisik'] - $jumlah);
                $new_stok_booking_asal          = (int)($stock_status_asal['stok_booking'] - $jumlah);
                $new_total_cost_asal            = $total_qty_asal * $stock_status_asal['average_cost'];
                $new_allocated_cost_asal        = $new_stok_booking_asal * $stock_status_asal['average_cost'];

                $report_asal = [
                    'stok_fisik'                => $total_qty_asal,
                    'stok_booking'              => $new_stok_booking_asal,
                    'total_cost'                => $new_total_cost_asal,
                    'allocated_cost'            => $new_allocated_cost_asal,
                    'updated_date'              => $today
                ];

                $this->mod_gudang->edit("report_stock_status", "id = " . $stock_status_asal['id'], $report_asal);
            } else {
                // In different month and year
                $last_stock_status_asal         = $this->mod_gudang->getLastStockStatus($now = 0, $id_gudang_asal, $id_produk, $month, $year);

                $report_asal = [
                    'id_gudang'                 => $id_gudang_asal,
                    'id_produk'                 => $id_produk,
                    'bulan'                     => $month,
                    'tahun'                     => $year
                ];

                if ($last_stock_status_asal) {
                    // Have record below this month
                    $total_qty_asal             = (int)($last_stock_status_asal['stok_fisik'] - $jumlah);
                    $new_stok_booking_asal      = (int)($last_stock_status_asal['stok_booking'] - $jumlah);
                    $new_total_cost_asal        = $total_qty_asal * $last_stock_status_asal['average_cost'];
                    $new_allocated_cost_asal    = $new_stok_booking_asal * $last_stock_status_asal['average_cost'];

                    $report_asal += [
                        'id_periode'            => $last_stock_status_asal['id_periode'],
                        'tgl_transaksi'         => $last_stock_status_asal['tgl_transaksi'],
                        'stok_fisik'            => $total_qty_asal,
                        'stok_booking'          => $new_stok_booking_asal,
                        'stok_available'        => (int)$last_stock_status_asal['stok_available'],
                        'average_cost'          => $last_stock_status_asal['average_cost'],
                        'total_cost'            => $new_total_cost_asal,
                        'allocated_cost'        => $new_allocated_cost_asal,
                        'created_date'          => $today
                    ];
                    
                    $this->mod_gudang->add("report_stock_status", $report_asal);
                }
            }

            ###################
            ## GUDANG TUJUAN ##
            ###################
            $stock_status_tujuan                = $this->mod_gudang->getLastStockStatus($now = 1, $id_gudang_tujuan, $id_produk, $month, $year);
            $report_tujuan                      = [];
            if ($stock_status_tujuan) {
                // In same month and year
                $total_qty_tujuan               = (int)($stock_status_tujuan['stok_fisik'] + $jumlah);
                $new_total_cost_tujuan          = $total_qty_tujuan * $stock_status_tujuan['average_cost'];

                $report_tujuan = [
                    'stok_fisik'                => $total_qty_tujuan,
                    'stok_available'            => (int)($stock_status_tujuan['stok_available'] + $jumlah),
                    'total_cost'                => $new_total_cost_tujuan,
                    'updated_date'              => $today
                ];

                $this->mod_gudang->edit("report_stock_status", "id = " . $stock_status_tujuan['id'], $report_tujuan);
            } else {
                // In different month and year
                $last_stock_status_tujuan       = $this->mod_gudang->getLastStockStatus($now = 0, $id_gudang_tujuan, $id_produk, $month, $year);

                $report_tujuan = [
                    'id_gudang'                 => $id_gudang_tujuan,
                    'id_produk'                 => $id_produk,
                    'bulan'                     => $month,
                    'tahun'                     => $year
                ];

                if ($last_stock_status_tujuan) {
                    // Have record below this month
                    $total_qty_tujuan           = (int)($last_stock_status_tujuan['stok_fisik'] + $jumlah);
                    $new_total_cost_tujuan      = $total_qty_tujuan * $last_stock_status_tujuan['average_cost'];

                    $report_tujuan += [
                        'id_periode'            => $last_stock_status_tujuan['id_periode'],
                        'tgl_transaksi'         => $last_stock_status_tujuan['tgl_transaksi'],
                        'stok_fisik'            => $total_qty_tujuan,
                        'stok_booking'          => (int)$last_stock_status_tujuan['stok_booking'],
                        'stok_available'        => (int)($last_stock_status_tujuan['stok_available'] + $jumlah),
                        'average_cost'          => $last_stock_status_tujuan['average_cost'],
                        'total_cost'            => $new_total_cost_tujuan,
                        'allocated_cost'        => $last_stock_status_tujuan['allocated_cost'],
                        'created_date'          => $today
                    ];
                    
                    $this->mod_gudang->add("report_stock_status", $report_tujuan);
                }
            }
            
            if ($this->db->trans_status() === true) {
                $this->db->trans_commit();
                return true;
            } else {
                $this->db->trans_rollback();
                return false;
            }
        } else {
            $this->db->trans_rollback();
            return false;
        }
    }

    public function checkStatusBayar()
    {
        // $id_customer = '305';
        $id_customer = $this->input->post('id_customer');
        $query = $this->mod_gudang->checkStatusBayar($id_customer);
        // echo $this->db->last_query();
        echo json_encode($query->result());
    }

    public function checkPersetujuanRSM()
    {
        // $id_customer = '305';
        $id_order = $this->input->post('id_order');
        $query = $this->mod_gudang->checkPersetujuanRSM($id_order);
        // echo $this->db->last_query();
        echo json_encode($query->result());
    }

    function statusDikirimToSpkDibuat($id_spk)
    {

        $this->db->trans_begin();

        $id_spk = $id_spk;
        $spk['status']             = 1;
        $spk['modified_date']      = date('Y-m-d H:i:s');
        $spk['modified_by']        = $this->adm_id;             
        $this->mod_gudang->edit('spk', 'id_spk =' . $id_spk, $spk);
        $this->mod_gudang->edit('spk_detail', 'id_spk =' . $id_spk, $spk);

        $dataDetailSPK = $this->mod_gudang->getAll('spk_detail', '*', 'id_spk =' . $id_spk);

        foreach($dataDetailSPK as $d)
        {
            $id_transaksi = $d->id_transaksi;
            $transaksi['status_transaksi']      = 4;
            $transaksi['updated_date']          = date('Y-m-d H:i:s');
            $transaksi['updated_by']            = $this->adm_id; 
            $this->mod_gudang->edit('transaksi', 'id_transaksi = ' . $id_transaksi, $transaksi);
            $this->mod_gudang->addTransaksiHistory($id_transaksi, 4, "Mengembalikan status"); 

            $dataTransaksi      = $this->mod_general->detailData('transaksi', 'id_transaksi', $id_transaksi);

            $detailTransaksi    = $this->mod_gudang->getAll('transaksi_detail', '*', 'id_transaksi =' . $id_transaksi);

            $orders = $this->mod_gudang->getAll('orders', '*', 'id_order = ' . $dataTransaksi['id_pesanan']);
            $periodeOrder = $orders[0]->periode;

            if ($periodeOrder == $this->periode) {
                foreach ($detailTransaksi as $row) {
                    // Change stock shipper
                    // $stokGudang                     = $this->mod_gudang->getStok($this->adm_id_gudang, $row->id_produk, 'stok_fisik, stok_booking');
                    $stokGudang                     = $this->mod_gudang->getStok($dataTransaksi["asal"], $row->id_produk, 'stok_fisik, stok_booking');

                    $dataStock['stok_fisik']        = $stokGudang->stok_fisik + $row->jumlah;
                    $dataStock['stok_booking']      = $stokGudang->stok_booking + $row->jumlah;
                    $this->mod_gudang->updateStok($dataTransaksi["asal"], $row->id_produk, $dataStock); // superadmin
                }
            }

            // Untuk Order
            // ======================
            //         echo "<br>";
            $order['current_state']         = 5;
            $order['date_upd']              = date('Y-m-d H:i:s');
            $this->mod_gudang->edit('orders', 'id_order =' . $orders[0]->id_order, $order);

            $this->mod_gudang->delete("order_history", $orders[0]->id_order = 6);

            // Untuk Order SCM
            // =====================
            $orderSCM['status']             = 2;
            $orderSCM['date_modified']      = date('Y-m-d H:i:s');
            $this->mod_gudang->edit('order_scm', 'id_order =' . $orders[0]->id_order, $orderSCM);
        }

        if ($this->db->trans_status() == true) 
        {
            // echo "berhasil";
            $this->db->trans_commit();
            $this->session->set_flashdata('success', 'Proses pembatalan pengiriman berhasil.');
            redirect('backmin/gudangpengiriman/index','refresh');
        }
        else
        {
            // echo "gagal";
            $this->db->trans_rollback();
            $this->session->set_flashdata('error', 'Gagal melakukan pembatalan pengiriman.');
            redirect('backmin/gudangpengiriman/detailPengiriman/'.$id_spk,'refresh');
        }
    }

    public function download_bast()
    {
        $data['page_title'] = 'Download BAST Siplah';
        $data['content'] = $this->load->view(BACKMIN_PATH.'/gudang/pengiriman/download_bast', '', true);
        $data['script_js'] = ''; 
        $this->load->view(BACKMIN_PATH . '/main', $data);
    }

    public function download_bast_process()
    {
        // $no_po = '000031072';
        $no_po = $this->input->post('no_po');
        $params = "?no_po=".$no_po."&seller_id=89&api_key=f21a297cadf045d8a36e950ac7585e81";
        // $data = file_get_contents("http://".getenv('SIPLAH_API_BAST').$params);

        $link = getenv('SIPLAH_API_BAST').$params;

        $data = $this->curl_download($link);
        if(trim($data) == trim('"Tidak ada data ditemukan"'))
        {
            redirect(BACKMIN_PATH.'/gudangpengiriman/download_bast','refresh');
        }
        else
        {
            header('Cache-Control: public'); 
            header('Content-type: application/pdf');
            header('Content-disposition: attachment; filename="'.$no_po.'.pdf"');

            echo $data;
        }
    }

    public function download_bast_siplah($no_po)
    {
        // $no_po = '000031072';
        // $no_po = $this->input->post('no_po');
        $params = "?no_po=".$no_po."&seller_id=89&api_key=f21a297cadf045d8a36e950ac7585e81";
        // $data = file_get_contents("http://".getenv('SIPLAH_API_BAST').$params);

        $link = getenv('SIPLAH_API_BAST').$params;

        $data = $this->curl_download($link);
        if(trim($data) == trim('"Tidak ada data ditemukan"'))
        {
            redirect(BACKMIN_PATH.'/gudangpengiriman/download_bast','refresh');
        }
        else
        {
            header('Cache-Control: public'); 
            header('Content-type: application/pdf');
            header('Content-disposition: attachment; filename="'.$no_po.'.pdf"');

            echo $data;
        }
    }

	public function cetak_invoice_siplah($no_po)
    {
        $dbsiplah = $this->load->database('dbsiplah', true);

        $order_id = $dbsiplah->query("select entity_id from sales_order where increment_id='".$no_po."'")->row_array()['entity_id'];
        $params = "?toko=89&nota=".$order_id;

        redirect(getenv('SIPLAH_API_PRINT_INVOICE').$params);
        
    }


    public function curl_download($url){
        $ch = curl_init(); 
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "GET");
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); 
        $result = curl_exec($ch);

        return $result;
    }
	
    // function statusDikirimToSpkDibuat($id_reference)
    // {

    //     $this->db->trans_begin();
    //     // $kode_pesanan = "ZXZA6RP9M"; // ID Reference
    //     $kode_pesanan = $id_reference; // ID Reference
    //     // echo $kode_pesanan;

    //     $id_transaksi = $this->mod_general->detailData('transaksi', 'kode_pesanan', $kode_pesanan)['id_transaksi'];
    //     // print_r($id_transaksi);
    //     // $id_transaksi = '74256';
        
    //     $dataTransaksi      = $this->mod_general->detailData('transaksi', 'id_transaksi', $id_transaksi);
    //     // select * from transaksi where id_transaksi='66579';

    //     $detailTransaksi    = $this->mod_gudang->getAll('transaksi_detail', '*', 'id_transaksi =' . $id_transaksi);
    //     // select * from transaksi_detail where id_transaksi='66579';
        
    //     $orders = $this->mod_gudang->getAll('orders', 'periode', 'id_order = ' . $dataTransaksi['id_pesanan']);
    //     $periodeOrder = $orders[0]->periode;
    //     // select periode from orders where id_order='';

    //     $transaksi['status_transaksi']      = 4;
    //     $transaksi['updated_date']          = date('Y-m-d H:i:s');
    //     $transaksi['updated_by']            = $this->adm_id; 
    //     $this->mod_gudang->edit('transaksi', 'id_transaksi = ' . $id_transaksi, $transaksi);
    //     $this->mod_gudang->addTransaksiHistory($id_transaksi, 4, "Mengembalikan status");       

    //     // echo $periodeOrder; 
    //     if ($periodeOrder == $this->periode) {
    //         foreach ($detailTransaksi as $row) {
    //             // Change stock shipper
                
    //             // $stokGudang                     = $this->mod_gudang->getStok($this->adm_id_gudang, $row->id_produk, 'stok_fisik, stok_booking');
    //             $stokGudang                     = $this->mod_gudang->getStok($dataTransaksi["asal"], $row->id_produk, 'stok_fisik, stok_booking');
    //             // echo "<br>";
    //             // echo "select stok_fisik, stok_booking from info_gudang where id_gudang='6' and id_produk='".$row->id_produk."' and periode='".$this->periode."';"; 
    //             // echo "<br>";

    //             // $stok_fisik = $row->jumlah;
    //             // $stok_booking = $row->jumlah;
    //             // $this->mod_gudang->updateStok($this->adm_id_gudang, $row->id_produk, $dataStock);
    //             // echo "update info_gudang set stok_fisik=stok_fisik+".$stok_fisik.", stok_booking=stok_booking+".$stok_booking." where id_gudang='6' and id_produk='".$row->id_produk."' and periode='".$this->periode."';";
                

    //             $dataStock['stok_fisik']        = $stokGudang->stok_fisik + $row->jumlah;
    //             $dataStock['stok_booking']      = $stokGudang->stok_booking + $row->jumlah;
    //             $this->mod_gudang->updateStok($dataTransaksi["asal"], $row->id_produk, $dataStock); // superadmin

    //             // $stok_fisik = $stokGudang->stok_fisik + $row->jumlah;
    //             // $stok_booking = $stokGudang->stok_booking + $row->jumlah;
    //             // $this->mod_gudang->updateStok($this->adm_id_gudang, $row->id_produk, $dataStock); // gudang
    //             // echo "update info_gudang set stock_fisik='".$stok_fisik."', stok_booking='".$stok_booking."' where id_gudang='6' and id_produk='".$row->id_produk."' and periode='".$this->periode."'";
                                
    //             // echo "<br>";
    //         }
    //     }

    //     // Untuk Order
    //     // ======================
    //     //         echo "<br>";
    //     // echo "update orders set current_state='5', date_upd=now() where id_order='".$dataTransaksi['id_pesanan']."';";
    //     //         echo "<br>";
    //     $order['current_state']         = 5;
    //     $order['date_upd']              = date('Y-m-d H:i:s');
    //     $this->mod_gudang->edit('orders', 'id_order =' . $dataTransaksi['id_pesanan'], $order);

    //     // //Untuk order history
    //     //         echo "<br>";
    //     // echo "delete from `order_history` where `id_order`='".$dataTransaksi['id_pesanan']."' and `id_order_state`='6';";
    //     //         echo "<br>";
    //     $this->mod_gudang->delete("order_history", $dataTransaksi['id_pesanan'] = 6);

    //     // Untuk Order SCM
    //     // =====================
    //     // echo "<br>";
    //     // echo "update order_scm set status='2', date_modified=now() where id_order='".$dataTransaksi['id_pesanan']."';";
    //     // echo "<br>";
    //     $orderSCM['status']             = 2;
    //     $orderSCM['date_modified']      = date('Y-m-d H:i:s');
    //     $this->mod_gudang->edit('order_scm', 'id_order =' . $dataTransaksi['id_pesanan'], $orderSCM);
                            
    //     $id_spk = $this->mod_general->detailData('spk_detail', 'id_transaksi', $id_transaksi)['id_spk'];
    //     $spk['status']             = 1;
    //     $spk['modified_date']      = date('Y-m-d H:i:s');
    //     $spk['modified_by']        = $this->adm_id;             
    //     $this->mod_gudang->edit('spk', 'id_spk =' . $id_spk, $spk);
    //     $this->mod_gudang->edit('spk_detail', 'id_spk =' . $id_spk, $spk);

    //     // echo "<br>";
    //     // echo "update spk set status='1', modified_date=now() where id_spk='".$id_spk."';";
    //     // echo "<br>";
    //     // echo "<br>";
    //     // echo "update spk_detail, modified_date=now() set status='1' where id_spk='".$id_spk."';";
    //     // echo "<br>";

    //     if ($this->db->trans_status() == true) 
    //     {
    //         // echo "berhasil";
    //         $this->db->trans_commit();
    //         $this->session->set_flashdata('success', 'Proses pembatalan pengiriman berhasil.');
    //         redirect('backmin/scmpesanan/indexPesananDiproses','refresh');
    //     }
    //     else
    //     {
    //         // echo "gagal";
    //         $this->db->trans_rollback();
    //         $this->session->set_flashdata('error', 'Gagal melakukan pembatalan pengiriman.');
    //         redirect('backmin/scmpesanan/detailPesananDiproses/'.$orders[0]->id_order,'refresh');
    //     }
    // }
}
