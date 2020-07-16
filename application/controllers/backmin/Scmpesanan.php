<?php
defined('BASEPATH') or exit('No direct script access allowed');

/**
 * @property Datatables $datatables
 * @property Mod_general $mod_general
 * @property Mod_scm $mod_scm
 */
class Scmpesanan extends MY_Controller
{
    public function __construct()
    {
        parent::__construct();
        if ( ! in_array($this->adm_level, $this->backmin_scm_area)) {
            redirect(BACKMIN_PATH);
        }
        $this->load->model('mod_general');
        $this->load->model('mod_scm');
    }

    public function index()
    {
        redirect(BACKMIN_PATH.'/scmpesanan/indexPesananMasuk');
    }

    public function indexPesananMasuk()
    {
        $data['page_title'] = 'Pesanan Sekolah - Masuk';
        $data['content'] = $this->load->view(BACKMIN_PATH.'/scm/pesanan_masuk/list', $data, true);
        $data['script_js'] = $this->load->view(BACKMIN_PATH.'/scm/pesanan_masuk/list_js', '', true);
        $this->load->view(BACKMIN_PATH.'/main', $data);
    }

    public function listDataPesananMasuk()
    {
        if ( ! $this->input->is_ajax_request()) {
            return false;
        }
        $this->load->library('datatables');
        $this->output->set_header('Content-Type:application/json; charset=utf-8');
        $this->datatables->select('a.id AS id, 
                                        a.id_order AS id_order, 
                                        a.reference AS reference, 
                                        c.school_name AS school_name, 
                                        b.category AS class_name, 
                                        b.type AS type_name, 
                                        c.provinsi AS provinsi, 
                                        c.kabupaten AS kabupaten, 
                                        b.date_add AS date_add, 
                                        DATE_FORMAT(ADDDATE(b.tgl_konfirmasi, IF(b.jangka_waktu <> "", b.jangka_waktu, 0)), "%Y-%m-%d") AS target_kirim, 
                                        substr(d.nama_gudang, 7) as nama_gudang');
        $this->datatables->from('order_scm a');
        $this->datatables->join('orders b', 'b.id_order=a.id_order', 'inner');
        $this->datatables->join('customer c', 'c.id_customer=a.id_customer', 'inner');
        $this->datatables->join('master_gudang d', 'a.id_gudang=d.id_gudang', 'inner');
        $this->datatables->where('a.status', '1');
        $this->datatables->edit_column('reference',
            '<a href="'.base_url(BACKMIN_PATH.'/scmpesanan/detailPesananMasuk/$1').'">$2</a>', 'id_order, reference');
        $this->output->set_output($this->datatables->generate());
    }

    public function detailPesananMasuk($id)
    {
        if ($id && is_numeric($id)) {
            $data['page_title'] = 'Detil Pesanan Sekolah - Masuk';
            $data['detail'] = $this->mod_general->detailData('orders', 'id_order', $id);
            $data['customer'] = $this->mod_general->detailData('customer', 'id_customer',
                $data['detail']['id_customer']);
            $order_scm = $this->mod_general->detailData('order_scm', 'id_order', $id);

            $data['recommended_warehouse'] = [];
            if ($order_scm['id_gudang']) {
                $data['recommended_warehouse'] = $this->mod_scm->getNewRecommendedWarehouse($id);
            } else {
                $data['recommended_warehouse'] = $this->mod_scm->getRecommendedWarehouse($data['customer']['kabupaten']);
            }

            $data['listproducts'] = $this->mod_scm->getListProductStock($id, $data['recommended_warehouse']->id_gudang);
            $data['sales'] = $this->mod_scm->getAll("employee", "id_employee, level, name, email, active, telp",
                "email='".$data['detail']['sales_referer']."'")[0];
            $getEmployeeKabupaten = $this->mod_scm->getAll("employee_kabupaten_kota", "*",
                "kabupaten_kota='".$data['customer']['kabupaten']."'");
            $employeeId = [];
            foreach ($getEmployeeKabupaten as $row => $value) {
                $employeeId[] = $value->id_employee;
            }
            $employeeId = implode(',', $employeeId);
            $data['korwil'] = $this->mod_scm->getAll("employee", "id_employee, level, name, email, active, telp",
                "level = 3 and id_employee in (".$employeeId.")")[0];
            $data['content'] = $this->load->view(BACKMIN_PATH.'/scm/pesanan_masuk/detil', $data, true);
            $data['script_js'] = $this->load->view(BACKMIN_PATH.'/scm/pesanan_masuk/detil_js', '', true);

            $this->load->view(BACKMIN_PATH.'/main', $data);
        } else {
            redirect(BACKMIN_PATH.'/scmpesanan/indexPesananMasuk');
        }
    }

    public function indexPesananDiproses()
    {
        $data['page_title'] = 'Pesanan Sekolah - Diproses';
        $data['content'] = $this->load->view(BACKMIN_PATH.'/scm/pesanan_diproses/list', $data, true);
        $data['script_js'] = $this->load->view(BACKMIN_PATH.'/scm/pesanan_diproses/list_js', '', true);

        $this->load->view(BACKMIN_PATH.'/main', $data);
    }

    public function listDataPesananDiproses()
    {
        if ( ! $this->input->is_ajax_request()) {
            return false;
        }
        $this->load->library('datatables');
        $this->output->set_header('Content-Type:application/json; charset=utf-8');
        $this->datatables->select('
            a.id AS id, 
            a.id_order AS id_order, 
            d.id_transaksi as id_transaksi, 
            a.reference AS reference, 
            c.school_name AS school_name, 
            b.category AS class_name, 
            b.type AS type_name, 
            c.provinsi AS provinsi, 
            c.kabupaten AS kabupaten, 
            b.date_add AS date_add, 
            DATE_FORMAT(ADDDATE(b.tgl_konfirmasi, IF(b.jangka_waktu <> "", b.jangka_waktu, 0)), "%Y-%m-%d") AS target_kirim, 
            CASE d.is_forward WHEN 1 THEN "<span class=\'glyphicon glyphicon-ok\'></span>" WHEN 0 THEN "<span class=\'glyphicon glyphicon-remove\'></span>" END AS is_forward, 
            CASE status_transaksi WHEN 1 THEN CONCAT("<span class=\'label label-default\'>Dibuat</span>") WHEN 2 THEN CONCAT("<span class=\'label label-warning\'>Diproses</span>") WHEN 3 THEN CONCAT("<span class=\'label label-warning\'>Menunggu TAG</span>") WHEN 4 THEN CONCAT("<span class=\'label label-warning\'>SPK Dibuat</span>") WHEN 5 THEN CONCAT("<span class=\'label label-primary\'>Dikirim Ekspedisi</span>") WHEN 6 THEN CONCAT("<span class=\'label label-success\'>Telah Sampai</span>") END AS status_transaksi, 
            COALESCE((select substr(x.nama_gudang, 7) from master_gudang x where x.id_gudang = a.id_gudang), "-") as nama_gudang
        ');
        $this->datatables->from('order_scm a');
        $this->datatables->join('orders b', 'b.id_order=a.id_order', 'inner');
        $this->datatables->join('customer c', 'c.id_customer=a.id_customer', 'inner');
        $this->datatables->join('transaksi d', 'a.id_order=d.id_pesanan', 'inner');
        $this->datatables->where('a.status !=', 1);
        $this->datatables->edit_column('reference',
            '<a href="'.base_url(BACKMIN_PATH.'/scmpesanan/detailPesananDiproses/$1').'">$2</a>',
            'id_transaksi, reference');
        $this->output->set_output($this->datatables->generate());
    }

    public function detailPesananDiproses($id)
    {
        if ($id && is_numeric($id)) {
            $data['transaksi'] = $this->mod_scm->getAll("transaksi", "*", "id_transaksi = ".$id)[0];
            $data['page_title'] = 'Detil Pesanan Sekolah - Diproses';
            $data['detail'] = $this->mod_general->detailData('orders', 'id_order', $data['transaksi']->id_pesanan);
            $data['customer'] = $this->mod_general->detailData('customer', 'id_customer',
                $data['detail']['id_customer']);
            $order_scm = $this->mod_general->detailData('order_scm', 'id_order', $data['transaksi']->id_pesanan);

            $data['recommended_warehouse'] = [];
            if ($order_scm['id_gudang']) {
                $data['recommended_warehouse'] = $this->mod_scm->getNewRecommendedWarehouse($data['transaksi']->id_pesanan);
            } else {
                $data['recommended_warehouse'] = $this->mod_scm->getRecommendedWarehouse($data['customer']['kabupaten']);
            }

            $data['listproducts'] = $this->mod_scm->getListProductStock($data['transaksi']->id_pesanan,
                $data['recommended_warehouse']->id_gudang);
            $data['sales'] = $this->mod_scm->getAll("employee", "id_employee, level, name, email, active, telp",
                "email='".$data['detail']['sales_referer']."'")[0];
            $getEmployeeKabupaten = $this->mod_scm->getAll("employee_kabupaten_kota", "*",
                "kabupaten_kota='".$data['customer']['kabupaten']."'");
            $employeeId = [];
            foreach ($getEmployeeKabupaten as $value) {
                $employeeId[] = $value->id_employee;
            }

            $employeeId = implode(',', $employeeId);
            $data['korwil'] = $this->mod_scm->getAll("employee", "id_employee, level, name, email, active, telp",
                "level = 3 and id_employee in (".$employeeId.")")[0];
            $data['list_gudang_tag'] = false;
            if ($data['transaksi']->have_tag > 0) {
                $data['list_gudang_tag'] = $this->mod_scm->getListGudangTAG($id);
            }

            $data['is_forward'] = false;
            if ($data['transaksi']->is_forward == 1) {
                $gudangForward = $this->mod_scm->getAll("transaksi", "asal",
                    "is_forward = 0 and id_pesanan = ".$data['transaksi']->id_pesanan)[0]->asal;
                $data['is_forward'] = $this->mod_scm->getAll("master_gudang", "nama_gudang",
                    "id_gudang = ".$gudangForward)[0]->nama_gudang;
            }

            $data['status_transaksi'] = $this->mod_scm->getAll("transaksi_state", "*",
                "is_active = 1 and id_transaksi_state = ".$data['transaksi']->status_transaksi)[0]->name;
            $transaksi_history = $this->mod_scm->getAll("transaksi_history", "*", "id_transaksi = ".$id, "id asc");
            $data['transaksi_history'] = [];
            foreach ($transaksi_history as $row => $dataHistory) {
                // status
                $status = $this->mod_scm->getAll("transaksi_state", "*",
                    "is_active = 1 and id_transaksi_state = ".$dataHistory->status_transaksi)[0];
                // employee
                $employee = $this->mod_scm->getAll("employee", "*",
                    "active = 1 and id_employee = ".$dataHistory->id_employee)[0];
                // status SPK
                $notes = "";
                if ($dataHistory->status_transaksi >= 4 && $data['is_forward'] == false) {
                    $idSPK = $this->mod_scm->getAll("spk_detail", "id_spk", "id_transaksi = ".$id)[0]->id_spk;
                    $spk = $this->mod_scm->getAll("spk", "*", "id_spk = ".$idSPK)[0];

                    if ($dataHistory->status_transaksi == 4) {
                        $notes = 'Kode SPK &nbsp; : &nbsp; <b>'.$spk->kode_spk."</b><br>";
                    } elseif ($dataHistory->status_transaksi == 5) {
                        $ekspeditur = $this->mod_scm->getAll("ekspeditur", "nama", "id = ".$spk->id_ekspeditur)[0];
                        $notes = 'Ekspeditur &nbsp; : &nbsp; <b>'.$ekspeditur->nama."</b><br>";
                    }
                }
                $data['transaksi_history'][$row]['id_state'] = $dataHistory->status_transaksi;
                $data['transaksi_history'][$row]['state'] = $status->name;
                $data['transaksi_history'][$row]['state_label'] = $status->label;
                $data['transaksi_history'][$row]['id_employee'] = $dataHistory->id_employee;
                $data['transaksi_history'][$row]['employee'] = $employee->name;
                $data['transaksi_history'][$row]['date_history'] = $dataHistory->date_add;
                $data['transaksi_history'][$row]['notes'] = $notes.$dataHistory->notes;
            }

            $data['payout'] = $this->mod_scm->getRow("payout_detail",
                "status > 2 AND id_order = ".$data['transaksi']->id_pesanan);
            $data['messages'] = null;
            $data['messages_confirm'] = null;
            if ($data['detail']['sts_bayar'] == 2 || $data['detail']['nilai_dibayar'] >= $data['detail']['total_paid']) {
                if ($data['payout'] > 0) {
                    $data['messages'] = 'Pesanan sudah <b>LUNAS</B> dan <b>KOMISI SUDAH DIBAYARKAN</b>.';
                    $data['messages_confirm'] = 'Yakin melanjutkan proses ini? \nPesanan sudah dibayar lunas dan komisi sudah dibayarkan, pastikan kembali pesanan dapat diproses.';
                } else {
                    $data['messages'] = 'Pesanan sudah <b>LUNAS</B>, harap konfirmasi terlebih dahulu ke bagian <b>Finance</b>.';
                    $data['messages_confirm'] = 'Yakin melanjutkan proses ini? \nPesanan sudah dibayar lunas, pastikan kembali pesanan dapat diproses.';
                }
            }

            $data['content'] = $this->load->view(BACKMIN_PATH.'/scm/pesanan_diproses/detil', $data, true);
            $data['script_js'] = $this->load->view(BACKMIN_PATH.'/scm/pesanan_diproses/detil_js', '', true);

            $this->load->view(BACKMIN_PATH.'/main', $data);
        } else {
            redirect(BACKMIN_PATH.'/scmpesanan/indexPesananDiproses');
        }
    }

    public function popWarehouseRequest($idGudang, $idProduct, $request, $idOrder)
    {
        if ( ! $this->input->is_ajax_request()) {
            return false;
        }
        if ($idGudang && $idProduct && $request && $idOrder) {
            $data['id_order'] = $idOrder;
            $data['request'] = $request;
            $data['product'] = $this->mod_general->detailData('product', 'id_product', $idProduct);
            $data['list_warehouse'] = $this->mod_scm->getListWarehouse($idGudang, $idProduct);

            echo $this->load->view(BACKMIN_PATH.'/scm/pesanan_masuk/pop_warehouse_request', $data, true);
        }
    }

    public function popWarehouse($idOrder, $idGudang, $idSite)
    {
        if ( ! $this->input->is_ajax_request()) {
            return false;
        }
        if ($idOrder && $idGudang && $idSite) {
            $data['list_warehouse'] = $this->mod_scm->getAll("master_gudang", "*",
                "status='1' AND id_gudang<>'".$idGudang."'", "nama_gudang ASC");
            $data['id_order'] = $idOrder;

            echo $this->load->view(BACKMIN_PATH.'/scm/pesanan_masuk/pop_warehouse', $data, true);
        }
    }

    public function detailPesananForward($idOrder, $idGudangForward)
    {
        if ($idOrder && $idGudangForward) {
            $data['detail'] = $this->mod_general->detailData('orders', 'id_order', $idOrder);
            $data['customer'] = $this->mod_general->detailData('customer', 'id_customer',
                $data['detail']['id_customer']);
            $data['forward_gudang'] = $this->mod_scm->getAll("master_gudang", "*", "id_gudang = ".$idGudangForward)[0];
            $data['listproducts'] = $this->mod_scm->getListProductStock($idOrder, $idGudangForward);
            $data['sales'] = $this->mod_scm->getAll("employee", "id_employee, level, name, email, active, telp",
                "email='".$data['detail']['sales_referer']."'")[0];
            $getEmployeeKabupaten = $this->mod_scm->getAll("employee_kabupaten_kota", "*",
                "kabupaten_kota='".$data['customer']['kabupaten']."'");
            $employeeId = [];

            foreach ($getEmployeeKabupaten as $row => $value) {
                $employeeId[] = $value->id_employee;
            }

            $employeeId = implode(',', $employeeId);
            $data['korwil'] = $this->mod_scm->getAll("employee", "id_employee, level, name, email, active, telp",
                "level = 3 and id_employee in (".$employeeId.")")[0];

            $data['page_title'] = 'Detil Forward Pesanan Sekolah';
            $data['content'] = $this->load->view(BACKMIN_PATH.'/scm/pesanan_masuk/detil_forward', $data, true);
            $data['script_js'] = $this->load->view(BACKMIN_PATH.'/scm/pesanan_masuk/detil_forward_js', '', true);

            $this->load->view(BACKMIN_PATH.'/main', $data);
        } else {
            redirect(BACKMIN_PATH.'/scmpesanan');
        }
    }

    public function processPesananMasuk()
    {
        if ( ! $this->input->is_ajax_request()) {
            redirect(BACKMIN_PATH.'/scmpesanan/indexPesananMasuk', 'refresh');
        }

        $id_pesanan = $this->input->post('id_order');
        if (in_array($this->adm_level, $this->auditor_area, true)) {
            $this->session->set_flashdata('error', 'Maaf, anda tidak dapat melakukan proses ini.');
            $callBack = [
                'success' => 'false',
                'message' => 'Tidak dapat melakukan proses ini.',
                'redirect' => 'backmin/scmpesanan/detailPesananMasuk/'.$id_pesanan,
            ];
        } else {
            $this->db->trans_begin();
            $kode_pesanan = $this->input->post('kode_pesanan');
            $id_customer = $this->input->post('id_customer');
            $gudang_asal = $this->input->post('gudang_asal');
            $id_produk = $this->input->post('id_product');
            $jumlah = $this->input->post('product_quantity');
            $berat = $this->input->post('weight');
            $harga = $this->input->post('price');
            $periode_order = (int)$this->input->post('periode_order');
            $haveTAG = 0;
            $status_transaksi = 2;

            $where_exist = "id_pesanan = $id_pesanan and have_tag = $haveTAG and is_to_school = 1 and is_forward = 0";
            $exist_order = $this->mod_scm->getRow('transaksi', $where_exist);

            if ($exist_order == 0) {
                $stock_status = true;
                $available_stock = true;

                if ($periode_order == $this->periode) {
                    $lunas = 0;
                    $data_pesanan = $this->mod_scm->getAll("orders", "total_paid, sts_bayar, nilai_dibayar",
                        "id_order = $id_pesanan")[0];
                    if ($data_pesanan->sts_bayar == 2 || $data_pesanan->nilai_dibayar >= $data_pesanan->total_paid) {
                        $lunas = 1;
                    }

                    foreach ($id_produk as $num => $row) {
                        $quantity = $jumlah[$num];
                        $stok = $this->mod_scm->getStok($gudang_asal, $row, "stok_booking, stok_available");

                        if ($quantity > $stok->stok_available) {
                            $available_stock = false;
                        } else {
                            $updateStok['stok_booking'] = $stok->stok_booking + $quantity;
                            $updateStok['stok_available'] = $stok->stok_available - $quantity;

                            // UPDATE WAREHOUSE STOCK
                            $this->mod_scm->updateStok($gudang_asal, $row, $updateStok);
                            // UPDATE REPORT STOCK STATUS
                            $stock_status = $this->addReportStockStatus($gudang_asal, $row, $quantity, $lunas);
                        }
                    }
                }

                $pesanan['id_pesanan'] = $id_pesanan;
                $pesanan['kode_pesanan'] = $kode_pesanan;
                $pesanan['id_tipe'] = 2;
                $pesanan['asal'] = $gudang_asal;
                $pesanan['tujuan'] = $id_customer;
                $pesanan['have_tag'] = $haveTAG;
                $pesanan['is_to_school'] = 1;
                $pesanan['status_transaksi'] = $status_transaksi;
                $pesanan['created_date'] = date('Y-m-d H:i:s');
                $pesanan['created_by'] = $this->adm_id;
                $pesanan['updated_date'] = date('Y-m-d H:i:s');
                $pesanan['updated_by'] = $this->adm_id;

                // TRANSAKSI
                $idTransaksi = $this->mod_scm->add('transaksi', $pesanan);
                // HISTORY TRANSAKSI
                $this->mod_scm->addTransaksiHistory($idTransaksi, 1);
                $this->mod_scm->addTransaksiHistory($idTransaksi, $status_transaksi);

                $pesananDetail['id_produk'] = $id_produk;
                $pesananDetail['jumlah'] = $jumlah;
                $pesananDetail['berat'] = $berat;
                $pesananDetail['harga'] = $harga;
                $dataPesananDetail = [];

                foreach ($pesananDetail as $field => $data) {
                    foreach ($data as $key => $value) {
                        $dataPesananDetail[$key][$field] = $value;
                    }
                }

                $pesananTambahan['total_jumlah'] = 0;
                $pesananTambahan['total_berat'] = 0;
                $pesananTambahan['total_harga'] = 0;

                foreach ($dataPesananDetail as $rows => $values) {
                    $dataDetail['id_transaksi'] = $idTransaksi;
                    $dataDetail['id_produk'] = $values['id_produk'];
                    $dataDetail['berat'] = $values['berat'] * $values['jumlah'];
                    $dataDetail['jumlah'] = $values['jumlah'];
                    $dataDetail['harga'] = $values['harga'];

                    // TRANSAKSI DETAIL
                    $this->mod_scm->addDetail('transaksi_detail', $dataDetail);

                    $pesananTambahan['total_jumlah'] += $values['jumlah'];
                    $pesananTambahan['total_berat'] += $values['berat'] * $values['jumlah'];
                    $pesananTambahan['total_harga'] += $values['harga'];
                }

                // EDIT TRANSAKSI
                $this->mod_scm->edit($idTransaksi, $pesananTambahan);

                if ($available_stock) {
                    if ($stock_status) {
                        if ($this->db->trans_status() == true) {
                            $status['status'] = 2;
                            $this->mod_scm->update("order_scm", "id_order = ".$id_pesanan, $status);

                            ## ACTION LOG USER
                            $logs['id_order'] = $id_pesanan;
                            $this->logger->logAction('Proses Pesanan Masuk', $logs);

                            $this->db->trans_commit();
                            $this->session->set_flashdata('success',
                                'Pesanan #<b>'.$kode_pesanan.'</b> berhasil dibuat.');
                            $callBack = [
                                'success' => 'true',
                                'message' => 'Transaski telah berhasil dibuat',
                                'redirect' => 'backmin/scmpesanan/indexPesananMasuk',
                            ];
                        } else {
                            $this->db->trans_rollback();
                            $this->session->set_flashdata('error', 'Gagal melakukan proses ini.');
                            $callBack = [
                                'success' => 'false',
                                'message' => 'Gagal melakukan proses.',
                                'redirect' => 'backmin/scmpesanan/detailPesananMasuk/'.$id_pesanan,
                            ];
                        }
                    } else {
                        $this->db->trans_rollback();
                        $this->session->set_flashdata('error', 'Gagal update laporan stock status.');
                        $callBack = [
                            'success' => 'false',
                            'message' => 'Gagal update laporan stock status.',
                            'redirect' => 'backmin/scmpesanan/detailPesananMasuk/'.$id_pesanan,
                        ];
                    }
                } else {
                    $this->db->trans_rollback();
                    $this->session->set_flashdata('error', 'Maaf, stok produk tidak mencukupi.');
                    $callBack = [
                        'success' => 'false',
                        'message' => 'Maaf, stok produk tidak mencukupi.',
                        'redirect' => 'backmin/scmpesanan/detailPesananMasuk/$id_pesanan',
                    ];
                }
            } else {
                $this->db->trans_rollback();
                $this->session->set_flashdata('error', 'Pesanan sudah diproses.');
                $callBack = [
                    'success' => 'false',
                    'message' => 'Pesanan sudah diproses.',
                    'redirect' => 'backmin/scmpesanan/detailPesananMasuk/$id_pesanan',
                ];
            }
        }
        echo json_encode($callBack, true);
    }

    public function addReportStockStatus($id_gudang, $id_produk, $jumlah, $lunas)
    {
        $this->db->trans_begin();
        if ($id_gudang && $id_produk) {

            $today = date('Y-m-d H:i:s');
            $month = date('n');
            $year = date('Y');

            $stock_status = $this->mod_scm->getLastStockStatus($now = 1, $id_gudang, $id_produk, $month, $year);

            if ($stock_status) {
                // In same month and year
                if ($lunas > 0) {
                    $new_stok_fisik = (int)($stock_status['stok_fisik'] - $jumlah);
                    $new_stok_available = (int)($stock_status['stok_available'] - $jumlah);
                    $new_total_cost = $new_stok_fisik * $stock_status['average_cost'];

                    $report = [
                        'stok_fisik' => $new_stok_fisik,
                        'stok_available' => $new_stok_available,
                        'total_cost' => $new_total_cost,
                        'updated_date' => $today,
                    ];
                } else {
                    $new_stok_booking = (int)($stock_status['stok_booking'] + $jumlah);
                    $new_stok_available = (int)($stock_status['stok_available'] - $jumlah);
                    $new_allocated_cost = $new_stok_booking * $stock_status['average_cost'];

                    $report = [
                        'stok_booking' => $new_stok_booking,
                        'stok_available' => $new_stok_available,
                        'allocated_cost' => $new_allocated_cost,
                        'updated_date' => $today,
                    ];
                }

                $this->mod_scm->update("report_stock_status", "id = ".$stock_status['id'], $report);
            } else {
                // In different month and year
                $last_stock_status = $this->mod_scm->getLastStockStatus($now = 0, $id_gudang, $id_produk, $month,
                    $year);

                $report = [
                    'id_periode' => $last_stock_status['id_periode'],
                    'id_gudang' => $id_gudang,
                    'id_produk' => $id_produk,
                    'bulan' => $month,
                    'tahun' => $year,
                ];

                if ($last_stock_status) {
                    // Have record below this month
                    if ($lunas > 0) {
                        $new_stok_fisik = (int)($last_stock_status['stok_fisik'] - $jumlah);
                        $new_stok_available = (int)($last_stock_status['stok_available'] - $jumlah);
                        $new_total_cost = $new_stok_fisik * $last_stock_status['average_cost'];

                        $report += [
                            'tgl_transaksi' => $last_stock_status['tgl_transaksi'],
                            'stok_fisik' => (int)$new_stok_fisik,
                            'stok_booking' => (int)$last_stock_status['stok_booking'],
                            'stok_available' => (int)$new_stok_available,
                            'average_cost' => $last_stock_status['average_cost'],
                            'total_cost' => $new_total_cost,
                            'allocated_cost' => $last_stock_status['allocated_cost'],
                            'created_date' => $today,
                        ];
                    } else {
                        $new_stok_booking = (int)($last_stock_status['stok_booking'] + $jumlah);
                        $new_stok_available = (int)($last_stock_status['stok_available'] - $jumlah);
                        $new_allocated_cost = $new_stok_booking * $last_stock_status['average_cost'];

                        $report += [
                            'tgl_transaksi' => $last_stock_status['tgl_transaksi'],
                            'stok_fisik' => (int)$last_stock_status['stok_fisik'],
                            'stok_booking' => $new_stok_booking,
                            'stok_available' => $new_stok_available,
                            'average_cost' => $last_stock_status['average_cost'],
                            'total_cost' => $last_stock_status['total_cost'],
                            'allocated_cost' => $new_allocated_cost,
                            'created_date' => $today,
                        ];
                    }

                    $this->mod_scm->add("report_stock_status", $report);
                } else {
                    // Don't have record below this month
                    $this->db->trans_rollback();

                    return false;
                }
            }

            if ($this->db->trans_status() == true) {
                $this->db->trans_commit();

                return true;
            }

            $this->db->trans_rollback();

            return false;
        }

        $this->db->trans_rollback();

        return false;
    }

    public function processPesananForward()
    {
        if ( ! $this->input->is_ajax_request()) {
            redirect(BACKMIN_PATH.'/scmpesanan', 'refresh');
        }

        $id_pesanan = $this->input->post('id_order');
        if (in_array($this->adm_level, $this->auditor_area, true)) {
            $this->session->set_flashdata('error', 'Maaf, anda tidak dapat melakukan proses ini.');
            $callBack = [
                'success' => 'false',
                'message' => 'Tidak dapat melakukan proses ini.',
                'redirect' => 'backmin/scmpesanan/detailPesananMasuk/$id_pesanan',
            ];
        } else {
            $this->db->trans_begin();
            $order_scm['id_gudang'] = $this->input->post('id_gudang_forward');
            $this->mod_scm->update("order_scm", "id_order = ".$id_pesanan, $order_scm);

            if ($this->db->trans_status() == true) {
                $this->db->trans_commit();
                $this->session->set_flashdata('success',
                    'Pesanan #<b>'.$this->input->post('reference').'</b> berhasil dipindahkan ke '.$this->input->post('nama_gudang_forward'));
                $callBack = [
                    "success" => "true",
                    "message" => "Pesanan berhasil dipindahkan",
                    "redirect" => "backmin/scmpesanan",
                ];
            } else {
                $this->db->trans_rollback();
                $this->session->set_flashdata('error', 'Gagal melakukan proses ini.');
                $callBack = [
                    "success" => "false",
                    "message" => "Gagal melakukan proses.",
                    "redirect" => "backmin/scmpesanan/detailPesananMasuk/$id_pesanan",
                ];
            }
        }
        echo json_encode($callBack);
    }

    public function cetakPesanan($id, $idGudang)
    {
        if ($id) {
            $data['detail'] = $this->mod_general->detailData('orders', 'id_order', $id);
            $data['customer'] = $this->mod_general->detailData('customer', 'id_customer',
                $data['detail']['id_customer']);
            $data['listproducts'] = $this->mod_scm->getListProductStock($id, $idGudang);
            $this->load->view(BACKMIN_PATH.'/scm/cetak_pesanan', $data);
        } else {
            redirect(BACKMIN_PATH.'/scmpesanan/indexPesananMasuk');
        }
    }

    public function changeOrder($id_transaksi, $zona)
    {
        if ( ! $this->input->is_ajax_request()) {
            return false;
        }
        $data_transaksi = $this->mod_scm->getAll("transaksi", "id_pesanan, asal", "id_transaksi = $id_transaksi")[0];
        $data_order = $this->mod_scm->getAll("orders", "*", "id_order = $data_transaksi->id_pesanan")[0];
        $data['payout'] = $this->mod_scm->getRow("payout_detail",
            "status > 2 AND id_order = $data_transaksi->id_pesanan");
        $data['messages'] = null;
        $data['messages_confirm'] = null;

        $data['id_transaksi'] = $id_transaksi;
        $data['zona'] = $zona;
        $data['detail_order'] = $this->mod_scm->getListProductStock($data_transaksi->id_pesanan, $data_transaksi->asal);

        if ($data_order->sts_bayar == 2 || $data_order->nilai_dibayar >= $data_order->total_paid) {
            if ($data['payout'] > 0) {
                $data['messages'] = 'Pesanan sudah <b>LUNAS</B> dan <b>KOMISI SUDAH DIBAYARKAN</b>, harap konfirmasi terlebih dahulu ke bagian <b>Finance</b>.';
                $data['messages_confirm'] = 'Yakin melanjutkan proses ini? \nPesanan sudah dibayar lunas dan komisi sudah dibayarkan, pastikan kembali pesanan dapat diproses.';
            } else {
                $data['messages'] = 'Pesanan sudah <b>LUNAS</B>, harap konfirmasi terlebih dahulu ke bagian <b>Finance</b>.';
                $data['messages_confirm'] = 'Yakin melanjutkan proses ini? \nPesanan sudah dibayar lunas, pastikan kembali pesanan dapat diproses.';
            }
        }
        $this->load->view('backmin/scm/pesanan_diproses/popup_change_order', $data);
    }

    public function processChangeOrder()
    {
        if ( ! $this->input->is_ajax_request()) {
            return false;
        }
        $id_transaksi = $this->input->post('id_transaksi');
        if (in_array($this->adm_level, $this->auditor_area, true)) {
            $this->session->set_flashdata('error', 'Maaf, anda tidak dapat melakukan proses ini.');
            $callBack = [
                'success' => 'false',
                'message' => 'Tidak dapat melakukan proses ini.',
                'redirect' => BACKMIN_PATH.'/scmpesanan/detailPesananDiproses/'.$id_transaksi,
            ];
        } else {
            $this->db->trans_begin();
            $zona = $this->input->post('zona');
            $qty = $this->input->post('new_qty');
            $alasan = $this->input->post('alasan');

            $data_transaksi = $this->mod_scm->getAll("transaksi", "*", "id_transaksi = $id_transaksi")[0];
            $detail_transaksi = $this->mod_scm->getAll("transaksi_detail", "*", "id_transaksi = $id_transaksi");
            $detail_order = $this->mod_scm->getAll("order_detail", "*", "id_order = ".$data_transaksi->id_pesanan);
            $data_order = $this->mod_scm->getAll("orders", "*", "id_order = ".$data_transaksi->id_pesanan)[0];

            ## UPDATE DETAIL TRANSAKSI
            $change_qty = [];
            $change_transaksi = [];
            $add_history_transaksi = [];
            foreach ($detail_transaksi as $row_transaksi) {
                $old_qty = $row_transaksi->jumlah;
                $new_qty = $qty[$row_transaksi->id_produk];

                if ($old_qty !== $new_qty) {
                    if ($old_qty == 0) {
                        $data_produk = $this->mod_scm->getAll("product", "price_$zona AS harga, weight AS berat",
                            "id_product = ".$row_transaksi->id_produk)[0];
                        $new_berat = $data_produk->berat * $new_qty;
                        $new_harga = $data_produk->harga * $new_qty;
                    } else {
                        $new_berat = ($row_transaksi->berat / $old_qty) * $new_qty;
                        $new_harga = ($row_transaksi->harga / $old_qty) * $new_qty;
                    }

                    $change_transaksi[] = [
                        'id' => $row_transaksi->id,
                        'jumlah' => $new_qty,
                        'berat' => $new_berat,
                        'harga' => $new_harga,
                    ];

                    $change_qty[] = [
                        'id_produk' => $row_transaksi->id_produk,
                        'old_qty' => $old_qty,
                        'new_qty' => $new_qty,
                    ];

                    $add_history_transaksi[] = [
                        'id_transaksi_detail' => $row_transaksi->id,
                        'id_transaksi' => $id_transaksi,
                        'id_produk' => $row_transaksi->id_produk,
                        'jumlah_awal' => $old_qty,
                        'jumlah_akhir' => $new_qty,
                        'created_date' => date('Y-m-d H:i:s'),
                        'created_by' => $this->adm_id,
                    ];
                }
            }
            $this->mod_scm->updateBatch('transaksi_detail', $change_transaksi, 'id');
            $this->mod_scm->addBatch('transaksi_detail_history', $add_history_transaksi);

            ## UPDATE DETAIL ORDERS
            $change_order = [];
            $add_history_order = [];
            $change_detail_order = [];
            foreach ($detail_order as $row_order) {
                $old_qty = $row_order->product_quantity;
                $new_qty = $qty[$row_order->product_id];

                if ($old_qty !== $new_qty) {
                    $new_harga = $row_order->unit_price * $new_qty;

                    $change_order[] = [
                        'id_order_detail' => $row_order->id_order_detail,
                        'product_quantity' => $new_qty,
                        'total_price' => $new_harga,
                    ];

                    $add_history_order[] = [
                        'id_order' => $row_order->id_order,
                        'id_order_detail' => $row_order->id_order_detail,
                        'kode_buku' => $row_order->kode_buku,
                        'product_id' => $row_order->product_id,
                        'product_name' => $row_order->product_name,
                        'quantity_before' => $old_qty,
                        'quantity_after' => $new_qty,
                        'created_at' => date('Y-m-d H:i:s'),
                        'created_by' => $this->adm_id,
                    ];

                    $change_detail_order[] = [
                        'kode_buku' => $row_order->kode_buku,
                        'nama_buku' => $row_order->product_name,
                        'jml_awal' => $old_qty,
                        'jml_akhir' => $new_qty,
                    ];
                }
            }
            $this->mod_scm->updateBatch('order_detail', $change_order, 'id_order_detail');
            $this->mod_scm->addBatch('order_detail_history', $add_history_order);

            $payout_status = '';
            $order_status = $this->mod_scm->getAll('order_state', 'name',
                'id_order_state = '.$data_order->current_state)[0]->name;
            $check_comission = $this->mod_scm->getRow('payout_detail', 'id_order = '.$data_order->id_order);
            if ($check_comission > 0) {
                $id_payout_status = $this->mod_scm->getAll('payout_detail', 'status',
                    'id_order = '.$data_order->id_order, 'status desc')[0]->status;
                $payout_status = $this->mod_scm->getAll('payout_state', 'name', 'id = '.$id_payout_status)[0]->name;
            }

            $data_send_mail = [
                'id_order' => $data_order->id_order,
                'reference' => $data_order->reference,
                'order_status' => $order_status,
                'payment_status' => $data_order->sts_bayar == 0 ? 'Belum Bayar' : ($data_order->sts_bayar == 1 ? 'Cicilan' : 'Lunas'),
                'comission_status' => $payout_status,
                'korwil_email' => $data_order->korwil_email,
                'reasons' => $alasan,
                'change_detail' => $change_detail_order,
                'revision_date' => date('Y-m-d H:i:s'),
                'change_type' => 1,
            ];

            ## UPDATE TOTAL ORDER, TRANSAKSI, SPK DETAIL, DAN SPK
            $summary_order = $this->updateSummaryOrder($id_transaksi);
            $stock_status = true;

            if ($summary_order['success']) {
                if ($this->periode == $data_order->periode) {
                    $lunas = 0;
                    if ($data_order->sts_bayar == 2 && $data_order->nilai_dibayar >= $data_order->total_paid) {
                        $lunas = 1;
                    }
                    foreach ($change_qty as $row) {
                        $data_stock = $this->mod_scm->getAll("info_gudang", "*",
                            "periode = ".$this->periode." and id_gudang = ".$data_transaksi->asal." and id_produk = ".$row['id_produk'])[0];
                        if ($row['old_qty'] > $row['new_qty']) {
                            $selisih = $row['old_qty'] - $row['new_qty'];
                            $difference = 0; // MINUS QUANTITY :

                            if ($data_transaksi->status_transaksi < 5) {
                                $update_stock = [
                                    'stok_booking' => $data_stock->stok_booking - $selisih,
                                    'stok_available' => $data_stock->stok_available + $selisih,
                                ];
                            } elseif ($data_transaksi->status_transaksi >= 5) {
                                $update_stock = [
                                    'stok_fisik' => $data_stock->stok_fisik + $selisih,
                                    'stok_available' => $data_stock->stok_available + $selisih,
                                ];
                            }
                        } elseif ($row['old_qty'] < $row['new_qty']) {
                            $selisih = $row['new_qty'] - $row['old_qty'];
                            $difference = 1; // PLUS QUANTITY

                            if ($data_transaksi->status_transaksi < 5) {
                                $update_stock = [
                                    'stok_booking' => $data_stock->stok_booking + $selisih,
                                    'stok_available' => $data_stock->stok_available - $selisih,
                                ];
                            } elseif ($data_transaksi->status_transaksi >= 5) {
                                $update_stock = [
                                    'stok_fisik' => $data_stock->stok_fisik - $selisih,
                                    'stok_available' => $data_stock->stok_available - $selisih,
                                ];
                            }
                        }

                        $change_paid_status = $summary_order['change_paid_status'];
                        if ($change_paid_status > 0) {
                            $stock_status = $this->updateReportStockStatusPaidStatus($data_transaksi->asal,
                                $row['id_produk'], $row['old_qty'], $row['new_qty'], $change_paid_status);
                        } else {
                            $stock_status = $this->updateReportStockStatus($data_transaksi->asal, $row['id_produk'],
                                $selisih, $lunas, $difference);
                        }

                        $this->mod_scm->update("info_gudang",
                            "periode = ".$this->periode." and id_gudang = ".$data_transaksi->asal." and id_produk = ".$row['id_produk'],
                            $update_stock);
                    }
                }

                if ($stock_status) {
                    if ($this->db->trans_status() == true) {
                        $send_mail = $this->sendChangeOrderMail($data_send_mail);
                        if ($send_mail) {
                            ## ACTION LOG USER
                            $logs['id_transaksi'] = $id_transaksi;
                            $this->logger->logAction('Proses Pesanan Diubah', $logs);

                            $this->db->trans_commit();
                            $this->session->set_flashdata('success',
                                'Pesanan #<b>'.$data_transaksi->kode_pesanan.'</b> berhasil diubah.');
                            $callBack = [
                                'success' => true,
                                'message' => 'Pesanan #'.$data_transaksi->kode_pesanan.' berhasil diubah.',
                                'redirect' => BACKMIN_PATH.'/scmpesanan/detailPesananDiproses/'.$id_transaksi,
                            ];
                        } else {
                            $this->db->trans_rollback();
                            $this->session->set_flashdata('error', 'Gagal mengirimkan email.');
                            $callBack = [
                                'success' => false,
                                'message' => 'Gagal mengirimkan email.',
                                'redirect' => BACKMIN_PATH.'/scmpesanan/detailPesananDiproses/'.$id_transaksi,
                            ];
                        }
                    } else {
                        $this->db->trans_rollback();
                        $this->session->set_flashdata('error',
                            'Pesanan #<b>'.$data_transaksi->kode_pesanan.'</b> gagal diubah.');
                        $callBack = [
                            'success' => false,
                            'message' => 'Pesanan #'.$data_transaksi->kode_pesanan.' gagal diubah.',
                            'redirect' => BACKMIN_PATH.'/scmpesanan/detailPesananDiproses/'.$id_transaksi,
                        ];
                    }
                } else {
                    $this->db->trans_rollback();
                    $this->session->set_flashdata('error', 'Gagal update laporan stock status.');
                    $callBack = [
                        'success' => false,
                        'message' => 'Gagal update laporan stock status.',
                        'redirect' => BACKMIN_PATH.'/scmpesanan/detailPesananDiproses/'.$id_transaksi,
                    ];
                }
            } else {
                $this->db->trans_rollback();
                $this->session->set_flashdata('error',
                    'Pesanan #<b>'.$data_transaksi->kode_pesanan.'</b> gagal diubah.');
                $callBack = [
                    'success' => false,
                    'message' => 'Pesanan #'.$data_transaksi->kode_pesanan.' gagal diubah.',
                    'redirect' => BACKMIN_PATH.'/scmpesanan/detailPesananDiproses/'.$id_transaksi,
                ];
            }
        }
        echo json_encode($callBack);
    }

    public function updateSummaryOrder($id_transaksi)
    {
        $this->db->trans_begin();
        $change_paid_status = 0;
        $data_transaksi = $this->mod_scm->getAll("transaksi", "*", "id_transaksi = $id_transaksi")[0];

        ## UPDATE SUMMARY ORDERS
        $total_order = $this->mod_scm->getAll("order_detail", "SUM(total_price) AS total_paid",
            "id_order = ".$data_transaksi->id_pesanan)[0];
        $data_bayar = $this->mod_scm->getAll("orders", "current_state, sts_bayar, nilai_dibayar",
            "id_order = ".$data_transaksi->id_pesanan)[0];

        $data_total_order = [
            'total_paid' => $total_order->total_paid,
        ];

        if ($data_bayar->sts_bayar > 0 || $data_bayar->nilai_dibayar > 0) {
            $paid_history = $this->mod_scm->getRow('order_history',
                'id_order_state = 9 and id_order = '.$data_transaksi->id_pesanan);

            if ($data_bayar->nilai_dibayar >= $total_order->total_paid) {
                $tgl_lunas = $this->mod_scm->getAll('finance_history', 'pay_date',
                    'id_order = '.$data_transaksi->id_pesanan, 'id DESC')[0]->pay_date;

                $data_total_order['tgl_lunas'] = $tgl_lunas;
                $data_total_order['sts_bayar'] = 2;
                $data_total_order['nilai_piutang'] = $total_order->total_paid - $data_bayar->nilai_dibayar;

                ## NOTES : IF ORDER CHANGES TO PAID ORDER
                if ($data_bayar->sts_bayar == 1) {
                    $change_paid_status = 2;
                }

                if ($data_bayar->current_state == 8) {
                    $data_total_order['current_state'] = 9;
                    $this->sendPaidOrderMail($data_transaksi->id_pesanan);
                }

                ## NOTES : CHECK HISTORY PAID ORDER
                if ($paid_history == 0) {
                    $data_paid_history = [
                        'id_employee' => 3,
                        'id_order' => $data_transaksi->id_pesanan,
                        'id_order_state' => 9,
                    ];
                    $this->mod_scm->add('order_history', $data_paid_history);
                }
            } else {
                $data_total_order['tgl_lunas'] = '0000-00-00';
                $data_total_order['sts_bayar'] = 1;
                $data_total_order['nilai_piutang'] = $total_order->total_paid - $data_bayar->nilai_dibayar;

                ## NOTES : IF ORDER CHANGES TO UNPAID ORDER
                if ($data_bayar->sts_bayar == 2) {
                    $change_paid_status = 1;
                }

                if ($data_bayar->current_state == 9) {
                    $data_total_order['current_state'] = 8;
                }

                ## NOTES : CHECK HISTORY PAID ORDER
                if ($paid_history > 0) {
                    $this->mod_scm->delete('order_history', 'id_order', $data_transaksi->id_pesanan,
                        'id_order_state = 9');
                }
            }
        }
        $this->mod_scm->update("orders", "id_order = ".$data_transaksi->id_pesanan, $data_total_order);

        ## UPDATE SUMMARY TRANSAKSI
        $total_transaksi = $this->mod_scm->getAll("transaksi_detail",
            "SUM(jumlah) AS total_jumlah, SUM(berat) AS total_berat, SUM(harga) AS total_harga",
            "id_transaksi = $id_transaksi")[0];
        $data_total_transaksi = [
            'total_jumlah' => $total_transaksi->total_jumlah,
            'total_berat' => $total_transaksi->total_berat,
            'total_harga' => $total_transaksi->total_harga,
        ];
        $this->mod_scm->update("transaksi", "id_transaksi = $id_transaksi", $data_total_transaksi);

        ## UPDATE SPK
        if ($data_transaksi->status_transaksi > 3) {
            $check_spk = $this->mod_scm->getRow("spk_detail", "id_transaksi = $id_transaksi");
            if ($check_spk > 0) {
                $data_spk = $this->mod_scm->getAll("spk_detail", "*", "id_transaksi = $id_transaksi")[0];

                $data_total_transaksi_spk = [
                    'jumlah' => $total_transaksi->total_jumlah,
                    'berat' => $total_transaksi->total_berat,
                ];
                $this->mod_scm->update("spk_detail", "id_transaksi = $id_transaksi", $data_total_transaksi_spk);

                $total_spk = $this->mod_scm->getAll("spk_detail",
                    "SUM(jumlah) AS total_jumlah, SUM(berat) AS total_berat", "id_spk = ".$data_spk->id_spk)[0];
                $data_total_spk = [
                    'total_jumlah' => $total_spk->total_jumlah,
                    'total_berat' => $total_spk->total_berat,
                ];
                $this->mod_scm->update("spk", "id_spk = ".$data_spk->id_spk, $data_total_spk);
            }
        }

        if ($this->db->trans_status() == true) {
            $this->db->trans_commit();
            $response = [
                'success' => true,
                'change_paid_status' => $change_paid_status,
            ];

            return $response;
        }

        $this->db->trans_rollback();
        $response = [
            'success' => false,
        ];

        return $response;
    }

    public function sendPaidOrderMail($id)
    {
        if ($id) {
            $detil = $this->mod_general->detailData('orders', 'id_order', $id);
            if ($detil) {
                /* Ditutup. Fa, 20200319
				$customer = $this->mod_general->detailData('customer', 'id_customer', $detil['id_customer']);
                $listproducts = $this->mod_scm->getListProduct($id);
                $postfix = strtolower(str_replace(" ", "_", str_replace(".", "", $customer['kabupaten'])));

                $this->load->library('excel');
                $this->excel->setActiveSheetIndex(0);
                $worksheet = $this->excel->getActiveSheet();

                $worksheet->setTitle('#'.$detil['reference']);
                $worksheet->setCellValue('B1', 'Kode Pesanan =')->setCellValue('B2', 'Perwakilan =')->setCellValue('B3',
                        'Kode Kab =')->setCellValue('B4', 'Kabupaten =')->setCellValue('B5',
                        'Kode Kec =')->setCellValue('B6', 'Kecamatan =')->setCellValue('B7',
                        'Sales =')->setCellValue('B8', 'No Dapodik/NPSN =')->setCellValue('B9',
                        'Nama Sekolah =')->setCellValue('B10', 'Alamat =')->setCellValue('B11',
                        'Desa =')->setCellValue('B12', 'Kode Pos =')->setCellValue('B13',
                        'Bendahara =')->setCellValue('B14', 'Nip Bendahara =')->setCellValue('B15',
                        'Kepala Sekolah =')->setCellValue('B16', 'Nip KepSek =')->setCellValue('B17',
                        'Hp KepSek =')->setCellValue('B18', 'Nama Operator =')->setCellValue('B19',
                        'Hp Operator =')->setCellValue('B20', 'Email =')->setCellValue('B21',
                        'Cara Bayar =')->setCellValue('B22', 'Tanggal Pesan =')->setCellValue('B23',
                        'Tanggal Lunas =')->setCellValue('B24', 'Total Bayar =')->setCellValue('B25', 'Perwakilan');
                $this->excel->getActiveSheet()->getStyle('B1:B25')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
                $worksheet->setCellValue('C1', $detil['reference'])->setCellValue('C2',
                        'Perwakilan')->setCellValue('C4', $customer['kabupaten'])->setCellValue('C5',
                        '')->setCellValue('C6', $customer['kecamatan'])->setCellValue('C7',
                        $detil['sales_referer'])->setCellValue('C9', $customer['school_name'])->setCellValue('C10',
                        $customer['alamat'])->setCellValue('C11', $customer['desa'])->setCellValue('C13',
                        $customer['nama_bendahara'])->setCellValue('C15', $customer['name'])->setCellValue('C18',
                        $customer['operator'])->setCellValue('C20', $customer['email'])->setCellValue('C21',
                        'Transfer')->setCellValue('C22', tglFaktur($detil['date_add']))->setCellValue('C23',
                        tglFaktur($detil['tgl_lunas']))->setCellValue('C25', 'Perwakilan');
                $worksheet->setCellValueExplicit('C3', $customer['kd_kab_kota'], PHPExcel_Cell_DataType::TYPE_STRING);
                $worksheet->setCellValueExplicit('C8', $customer['no_npsn'], PHPExcel_Cell_DataType::TYPE_STRING);
                $worksheet->setCellValueExplicit('C12', $customer['kodepos'], PHPExcel_Cell_DataType::TYPE_STRING);
                $worksheet->setCellValueExplicit('C14', $customer['nip_bendahara'],
                    PHPExcel_Cell_DataType::TYPE_STRING);
                $worksheet->setCellValueExplicit('C16', $customer['nip_kepsek'], PHPExcel_Cell_DataType::TYPE_STRING);
                $worksheet->setCellValueExplicit('C17', $customer['phone_kepsek'], PHPExcel_Cell_DataType::TYPE_STRING);
                $worksheet->setCellValueExplicit('C19', $customer['hp_operator'], PHPExcel_Cell_DataType::TYPE_STRING);
                $worksheet->setCellValueExplicit('C24', toRupiah($detil['nilai_dibayar']),
                    PHPExcel_Cell_DataType::TYPE_STRING);
                $worksheet->setCellValue('A26', 'NO')->setCellValue('B26', 'ISBN')->setCellValue('C26',
                        'JUDUL')->setCellValue('D26', 'JENJANG')->setCellValue('E26', 'PENERBIT')->setCellValue('F26',
                        'PENGARANG')->setCellValue('G26', 'KELAS')->setCellValue('H26', 'QTY')->setCellValue('I26',
                        'HARGA')->setCellValue('J26', 'KODE BUKU');

                $rowNumber = 27;
                $nomor = 1;
                foreach ($listproducts as $row) {
                    $worksheet->setCellValue('A'.$rowNumber, $nomor);
                    $worksheet->setCellValue('B'.$rowNumber, $row['isbn']);
                    $worksheet->setCellValue('C'.$rowNumber, $row['judul']);
                    $worksheet->setCellValue('D'.$rowNumber, $row['jenjang']);
                    $worksheet->setCellValue('E'.$rowNumber, $row['penerbit']);
                    $worksheet->setCellValue('F'.$rowNumber, $row['pengarang']);
                    $worksheet->setCellValue('G'.$rowNumber, $row['kelas']);
                    $worksheet->setCellValue('H'.$rowNumber, $row['qty']);
                    $worksheet->setCellValue('I'.$rowNumber, $row['harga']);
                    $worksheet->setCellValue('J'.$rowNumber, $row['kode_buku']);
                    $rowNumber++;
                    $nomor++;
                }
                $filename = $detil['reference'].'_'.$postfix.'.xls';
                $pathfile = 'assets/data/orders/lunas/';

                if ( ! is_dir($pathfile)) {
                    if ( ! mkdir($pathfile, 0777, true) && ! is_dir($pathfile)) {
                        throw new \RuntimeException(sprintf('Directory "%s" was not created', $pathfile));
                    }
                    chmod($pathfile, 0777);
                } else {
                    chmod($pathfile, 0777);
                }
				
                $objWriter = PHPExcel_IOFactory::createWriter($this->excel, 'Excel5');
                $objWriter->save($pathfile.$filename);
                $subject = 'Kode Pesanan #'.$detil['reference'].' Telah Dibayar Lunas';
                $to = ['infolunas@gramediaprinting.com'];
                $content = '<p>Terlampir Detil Data Kode Pesanan <b>#'.$detil['reference'].'</b> di PT. Gramedia yang telah dibayar lunas.</p><p>Terima Kasih</p>';
                $attach = $pathfile.$filename;

                $this->load->library('mymail');
                $this->mymail->send($subject, $to, $content, $attach);
				*/
                return true;
            }

            return false;
        }
    }

    public function updateReportStockStatusPaidStatus(
        $id_gudang,
        $id_produk,
        $jumlah_awal,
        $jumlah_akhir,
        $change_paid_status
    ) {
        $this->db->trans_begin();
        if ($id_gudang && $id_produk && $change_paid_status) {
            ## NOTES : $change_paid_status -> 1 = lunas jadi cicilan, 2 = cicilan jadi lunas
            $today = date('Y-m-d H:i:s');
            $month = date('n');
            $year = date('Y');

            $stock_status = $this->mod_scm->getLastStockStatus($now = 1, $id_gudang, $id_produk, $month, $year);
            if ($stock_status) {
                // In same month and year
                if ($change_paid_status == 1) {
                    // FROM PAID TO UNPAID
                    $new_stok_available = (int)($stock_status['stok_available'] + $jumlah_awal);
                    $new_stok_fisik = (int)($stock_status['stok_fisik'] + $jumlah_awal);
                    $new_total_cost = $new_stok_fisik * $stock_status['average_cost'];

                    $report = [
                        'stok_fisik' => $new_stok_fisik,
                        'stok_available' => $new_stok_available,
                        'total_cost' => $new_total_cost,
                        'updated_date' => $today,
                    ];

                    $clean_stock = $this->mod_scm->update("report_stock_status", "id = ".$stock_status['id'], $report);

                    if ($clean_stock > 0) {
                        $stock_status_update = $this->mod_scm->getLastStockStatus($now = 1, $id_gudang, $id_produk,
                            $month, $year);

                        $update_stok_booking = (int)($stock_status_update['stok_booking'] + $jumlah_akhir);
                        $update_stok_available = (int)($stock_status_update['stok_available'] - $jumlah_akhir);
                        $update_allocated_cost = $update_stok_booking * $stock_status_update['average_cost'];

                        $report_final = [
                            'stok_booking' => $update_stok_booking,
                            'stok_available' => $update_stok_available,
                            'allocated_cost' => $update_allocated_cost,
                            'updated_date' => $today,
                        ];

                        $this->mod_scm->update("report_stock_status", "id = ".$stock_status_update['id'],
                            $report_final);
                    }
                } else {
                    // FROM UNPAID TO PAID
                    $new_stok_booking = (int)($stock_status['stok_booking'] - $jumlah_awal);
                    $new_stok_available = (int)($stock_status['stok_available'] + $jumlah_awal);
                    $new_allocated_cost = $new_stok_booking * $stock_status['average_cost'];

                    $report = [
                        'stok_booking' => $new_stok_booking,
                        'stok_available' => $new_stok_available,
                        'allocated_cost' => $new_allocated_cost,
                        'updated_date' => $today,
                    ];

                    $clean_stock = $this->mod_scm->update("report_stock_status", "id = ".$stock_status['id'], $report);

                    if ($clean_stock > 0) {
                        $stock_status_update = $this->mod_scm->getLastStockStatus($now = 1, $id_gudang, $id_produk,
                            $month, $year);

                        $update_stok_available = (int)($stock_status_update['stok_available'] - $jumlah_akhir);
                        $update_stok_fisik = (int)($stock_status_update['stok_fisik'] - $jumlah_akhir);
                        $update_total_cost = $update_stok_fisik * $stock_status_update['average_cost'];

                        $report_final = [
                            'stok_fisik' => $update_stok_fisik,
                            'stok_available' => $update_stok_available,
                            'total_cost' => $update_total_cost,
                            'updated_date' => $today,
                        ];

                        $this->mod_scm->update("report_stock_status", "id = ".$stock_status_update['id'],
                            $report_final);
                    }
                }
            } else {
                // In different month and year
                $last_stock_status = $this->mod_scm->getLastStockStatus($now = 0, $id_gudang, $id_produk, $month,
                    $year);

                $report = [
                    'id_periode' => $last_stock_status['id_periode'],
                    'id_gudang' => $id_gudang,
                    'id_produk' => $id_produk,
                    'bulan' => $month,
                    'tahun' => $year,
                ];

                if ($last_stock_status) {
                    // Have record below this month
                    if ($change_paid_status == 1) {
                        // FROM PAID TO UNPAID
                        $new_stok_available = (int)($last_stock_status['stok_available'] + $jumlah_awal);
                        $new_stok_fisik = (int)($last_stock_status['stok_fisik'] + $jumlah_awal);
                        $new_total_cost = $new_stok_fisik * $last_stock_status['average_cost'];

                        $report += [
                            'tgl_transaksi' => $last_stock_status['tgl_transaksi'],
                            'stok_fisik' => $new_stok_fisik,
                            'stok_booking' => (int)$last_stock_status['stok_booking'],
                            'stok_available' => $new_stok_available,
                            'average_cost' => $last_stock_status['average_cost'],
                            'total_cost' => $new_total_cost,
                            'allocated_cost' => $last_stock_status['allocated_cost'],
                            'created_date' => $today,
                        ];

                        $id_report_stock_status = $this->mod_scm->add("report_stock_status", $report);

                        if ($id_report_stock_status) {
                            $stock_status_update = $this->mod_scm->getLastStockStatus($now = 1, $id_gudang, $id_produk,
                                $month, $year);

                            $update_stok_booking = (int)($stock_status_update['stok_booking'] + $jumlah_akhir);
                            $update_stok_available = (int)($stock_status_update['stok_available'] - $jumlah_akhir);
                            $update_allocated_cost = $update_stok_booking * $stock_status_update['average_cost'];

                            $report_final = [
                                'stok_booking' => $update_stok_booking,
                                'stok_available' => $update_stok_available,
                                'allocated_cost' => $update_allocated_cost,
                                'updated_date' => $today,
                            ];

                            $this->mod_scm->update("report_stock_status", "id = ".$stock_status_update['id'],
                                $report_final);
                        }
                    } else {
                        // FROM UNPAID TO PAID
                        $new_stok_booking = (int)($last_stock_status['stok_booking'] - $jumlah_awal);
                        $new_stok_available = (int)($last_stock_status['stok_available'] + $jumlah_awal);
                        $new_allocated_cost = $new_stok_booking * $last_stock_status['average_cost'];

                        $report += [
                            'tgl_transaksi' => $last_stock_status['tgl_transaksi'],
                            'stok_fisik' => (int)$last_stock_status['stok_fisik'],
                            'stok_booking' => $new_stok_booking,
                            'stok_available' => $new_stok_available,
                            'average_cost' => $last_stock_status['average_cost'],
                            'total_cost' => $last_stock_status['total_cost'],
                            'allocated_cost' => $new_allocated_cost,
                            'created_date' => $today,
                        ];

                        $id_report_stock_status = $this->mod_scm->add("report_stock_status", $report);

                        if ($id_report_stock_status) {
                            $stock_status_update = $this->mod_scm->getLastStockStatus($now = 1, $id_gudang, $id_produk,
                                $month, $year);

                            $update_stok_available = (int)($stock_status_update['stok_available'] - $jumlah_akhir);
                            $update_stok_fisik = (int)($stock_status_update['stok_fisik'] - $jumlah_akhir);
                            $update_total_cost = $update_stok_fisik * $stock_status_update['average_cost'];

                            $report_final = [
                                'stok_fisik' => $update_stok_fisik,
                                'stok_available' => $update_stok_available,
                                'total_cost' => $update_total_cost,
                                'updated_date' => $today,
                            ];

                            $this->mod_scm->update("report_stock_status", "id = ".$stock_status_update['id'],
                                $report_final);
                        }
                    }
                } else {
                    // Don't have record below this month
                    $this->db->trans_rollback();

                    return false;
                }
            }

            if ($this->db->trans_status() == true) {
                $this->db->trans_commit();

                return true;
            }

            $this->db->trans_rollback();

            return false;
        }

        $this->db->trans_rollback();

        return false;
    }

    public function updateReportStockStatus($id_gudang, $id_produk, $jumlah, $lunas, $difference)
    {
        $this->db->trans_begin();
        if ($id_gudang && $id_produk) {
            ## NOTES : $difference -> 0 = mengurangi jumlah, 2 = menambah jumlah
            $today = date('Y-m-d H:i:s');
            $month = date('n');
            $year = date('Y');
            $stock_status = $this->mod_scm->getLastStockStatus($now = 1, $id_gudang, $id_produk, $month, $year);

            if ($stock_status) {
                // In same month and year
                if ($lunas > 0) {
                    if ($difference > 0) {
                        $new_stok_available = (int)($stock_status['stok_available'] - $jumlah);
                        $new_stok_fisik = (int)($stock_status['stok_fisik'] - $jumlah);
                    } else {
                        $new_stok_available = (int)($stock_status['stok_available'] + $jumlah);
                        $new_stok_fisik = (int)($stock_status['stok_fisik'] + $jumlah);
                    }
                    $new_total_cost = $new_stok_fisik * $stock_status['average_cost'];
                    $report = [
                        'stok_fisik' => $new_stok_fisik,
                        'stok_available' => $new_stok_available,
                        'total_cost' => $new_total_cost,
                        'updated_date' => $today,
                    ];
                } else {
                    if ($difference > 0) {
                        $new_stok_booking = (int)($stock_status['stok_booking'] + $jumlah);
                        $new_stok_available = (int)($stock_status['stok_available'] - $jumlah);
                    } else {
                        $new_stok_booking = (int)($stock_status['stok_booking'] - $jumlah);
                        $new_stok_available = (int)($stock_status['stok_available'] + $jumlah);
                    }
                    $new_allocated_cost = $new_stok_booking * $stock_status['average_cost'];
                    $report = [
                        'stok_booking' => $new_stok_booking,
                        'stok_available' => $new_stok_available,
                        'allocated_cost' => $new_allocated_cost,
                        'updated_date' => $today,
                    ];
                }
                $this->mod_scm->update("report_stock_status", "id = ".$stock_status['id'], $report);
            } else {
                // In different month and year
                $last_stock_status = $this->mod_scm->getLastStockStatus($now = 0, $id_gudang, $id_produk, $month,
                    $year);
                $report = [
                    'id_periode' => $last_stock_status['id_periode'],
                    'id_gudang' => $id_gudang,
                    'id_produk' => $id_produk,
                    'bulan' => $month,
                    'tahun' => $year,
                ];

                if ($last_stock_status) {
                    // Have record below this month
                    if ($lunas > 0) {
                        if ($difference > 0) {
                            $new_stok_available = (int)($last_stock_status['stok_available'] - $jumlah);
                            $new_stok_fisik = (int)($last_stock_status['stok_fisik'] - $jumlah);
                        } else {
                            $new_stok_available = (int)($last_stock_status['stok_available'] + $jumlah);
                            $new_stok_fisik = (int)($last_stock_status['stok_fisik'] + $jumlah);
                        }
                        $new_total_cost = $new_stok_fisik * $last_stock_status['average_cost'];

                        $report += [
                            'tgl_transaksi' => $last_stock_status['tgl_transaksi'],
                            'stok_fisik' => (int)$new_stok_fisik,
                            'stok_booking' => (int)$last_stock_status['stok_booking'],
                            'stok_available' => (int)$new_stok_available,
                            'average_cost' => $last_stock_status['average_cost'],
                            'total_cost' => $new_total_cost,
                            'allocated_cost' => $last_stock_status['allocated_cost'],
                            'created_date' => $today,
                        ];
                    } else {
                        if ($difference > 0) {
                            $new_stok_booking = (int)($last_stock_status['stok_booking'] + $jumlah);
                            $new_stok_available = (int)($last_stock_status['stok_available'] - $jumlah);
                        } else {
                            $new_stok_booking = (int)($last_stock_status['stok_booking'] - $jumlah);
                            $new_stok_available = (int)($last_stock_status['stok_available'] + $jumlah);
                        }
                        $new_allocated_cost = $new_stok_booking * $last_stock_status['average_cost'];

                        $report += [
                            'tgl_transaksi' => $last_stock_status['tgl_transaksi'],
                            'stok_fisik' => (int)$last_stock_status['stok_fisik'],
                            'stok_booking' => $new_stok_booking,
                            'stok_available' => $new_stok_available,
                            'average_cost' => $last_stock_status['average_cost'],
                            'total_cost' => $last_stock_status['total_cost'],
                            'allocated_cost' => $new_allocated_cost,
                            'created_date' => $today,
                        ];
                    }

                    $this->mod_scm->add('report_stock_status', $report);
                } else {
                    // Don't have record below this month
                    $this->db->trans_rollback();

                    return false;
                }
            }

            if ($this->db->trans_status() == true) {
                $this->db->trans_commit();

                return true;
            }

            $this->db->trans_rollback();

            return false;
        }

        $this->db->trans_rollback();

        return false;
    }

    public function sendChangeOrderMail($data)
    {
        if ($data) {
            /* Ditutup. Fa, 20200319
			$this->load->library('excel');
            $this->excel->setActiveSheetIndex(0);
            $worksheet = $this->excel->getActiveSheet();

            $worksheet->setTitle('#'.$data['reference']);
            $worksheet->setCellValue('B2', 'Kode Pesanan =')->setCellValue('B3', 'Status Order =')->setCellValue('B4',
                    'Status Pembayaran =')->setCellValue('B5', 'Status Komisi =')->setCellValue('B6',
                    'Tanggal Revisi =')->setCellValue('B7', 'Alasan =');
            $this->excel->getActiveSheet()->getStyle('B2:B7')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
            $worksheet->setCellValue('C2', $data['reference'])->setCellValue('C3',
                    $data['order_status'])->setCellValue('C4', $data['payment_status'])->setCellValue('C5',
                    $data['comission_status'])->setCellValue('C6', $data['revision_date'])->setCellValue('C7',
                    $data['reasons']);

            $worksheet->setCellValue('A9', 'NO')->setCellValue('B9', 'KODE BUKU')->setCellValue('C9',
                    'JUDUL BUKU')->setCellValue('D9', 'QTY AWAL')->setCellValue('E9', 'QTY AKHIR');

            $rowNumber = 10;
            $nomor = 1;
            foreach ($data['change_detail'] as $row) {
                $worksheet->setCellValue('A'.$rowNumber, $nomor);
                $worksheet->setCellValue('B'.$rowNumber, $row['kode_buku']);
                $worksheet->setCellValue('C'.$rowNumber, $row['nama_buku']);
                $worksheet->setCellValue('D'.$rowNumber, rupiah($row['jml_awal'], 0, 2));
                $worksheet->setCellValue('E'.$rowNumber, rupiah($row['jml_akhir'], 0, 2));
                $rowNumber++;
                $nomor++;
            }

            $today = date('Y-m-d');
            $time = strtotime(date('Y-m-d H:i:s'));
            $pathfile = "uploads".DIRECTORY_SEPARATOR."scm".DIRECTORY_SEPARATOR."change_order".DIRECTORY_SEPARATOR;

            if ( ! is_dir($pathfile)) {
                if ( ! mkdir($pathfile, 0777, true) && ! is_dir($pathfile)) {
                    throw new \RuntimeException(sprintf('Directory "%s" was not created', $pathfile));
                }
                chmod($pathfile, 0777);
            } else {
                chmod($pathfile, 0777);
            }

            ## NOTE : change_type == 1 ?? 'Perubahan pesanan' : 'Tambahan Pesanan'
            if ($data['change_type'] == 1) {
                $subject = 'Ubah Pesanan #'.$data['reference'];
                $content = ' <p>Pesanan <b>#'.$data['reference'].'</b> telah diubah.</p>
                                <p>Terlampir detil perubahan dari pesanan tersebut.</p>
                                <br/><p>Terima Kasih</p>';

                $filename = "Perubahan_".$data['reference'].'_'.$today.'_'.$time.'.xls';
            } else {
                $subject = 'Tambah Pesanan #'.$data['reference'];
                $content = ' <p>Pesanan <b>#'.$data['reference'].'</b> telah ditambahkan.</p>
                                <p>Terlampir detil tambahan buku dari pesanan tersebut.</p>
                                <br/><p>Terima Kasih</p>';

                $filename = "Penambahan_".$data['reference'].'_'.$today.'_'.$time.'.xls';
            }

            $objWriter = PHPExcel_IOFactory::createWriter($this->excel, 'Excel5');
            $objWriter->save($pathfile.$filename);
            $to = [
                'ar@gramediaprinting.com',
                'martina@gramediaprinting.com',
                'hermanwu@gramediaprinting.com',
                $data['korwil_email'],
            ];
            $attach = $pathfile.$filename;

            $this->load->library('mymail');
            $this->mymail->send($subject, $to, $content, $attach);
			*/
            return true;
        }

        return false;
    }

    public function addOrder($id_transaksi, $id_category, $zona)
    {
        if ( ! $this->input->is_ajax_request()) {
            return false;
        }
        $data_transaksi = $this->mod_scm->getAll("transaksi", "id_pesanan, asal", "id_transaksi = $id_transaksi")[0];
        $data_order = $this->mod_scm->getAll("orders", "*", "id_order = $data_transaksi->id_pesanan")[0];
        $data['payout'] = $this->mod_scm->getRow("payout_detail",
            "status > 2 AND id_order = $data_transaksi->id_pesanan");
        $data['messages'] = null;
        $data['messages_confirm'] = null;

        $data['id_transaksi'] = $id_transaksi;
        $data['id_category'] = $id_category;
        $data['zona'] = $zona;
        $data['list_product'] = $this->mod_scm->getListBooks($data_transaksi->id_pesanan, $data_transaksi->asal,
            $id_category, $zona);

        if ($data_order->sts_bayar == 2 || $data_order->nilai_dibayar >= $data_order->total_paid) {
            if ($data['payout'] > 0) {
                $data['messages'] = 'Pesanan sudah <b>LUNAS</B> dan <b>KOMISI SUDAH DIBAYARKAN</b>, harap konfirmasi terlebih dahulu ke bagian <b>Finance</b>.';
                $data['messages_confirm'] = 'Yakin melanjutkan proses ini? \nPesanan sudah dibayar lunas, pastikan kembali pesanan dapat diproses.';
            } else {
                $data['messages'] = 'Pesanan sudah <b>LUNAS</B>, harap konfirmasi terlebih dahulu ke bagian <b>Finance</b>.';
                $data['messages_confirm'] = 'Yakin melanjutkan proses ini? \nPesanan sudah dibayar lunas, pastikan kembali pesanan dapat diproses.';
            }
        }
        $this->load->view('backmin/scm/pesanan_diproses/popup_add_order', $data);
    }

    public function processAddOrder()
    {
        if ( ! $this->input->is_ajax_request()) {
            return false;
        }
        $id_transaksi = $this->input->post('id_transaksi');
        if (in_array($this->adm_level, $this->auditor_area, true)) {
            $this->session->set_flashdata('error', 'Maaf, anda tidak dapat melakukan proses ini.');
            $callBack = [
                'success' => false,
                'message' => 'Tidak dapat melakukan proses ini.',
                'redirect' => BACKMIN_PATH.'/scmpesanan/detailPesananDiproses/'.$id_transaksi,
            ];
        } else {
            $this->db->trans_begin();
            $id_category = $this->input->post('id_category');
            $zona = $this->input->post('zona');
            $new_data = $this->input->post('new_qty');
            $alasan = $this->input->post('alasan');

            $data_transaksi = $this->mod_scm->getAll("transaksi", "*", "id_transaksi = $id_transaksi")[0];
            $data_detail = $this->mod_scm->getAll("transaksi_detail", "*", "id_transaksi = $id_transaksi");
            $data_order = $this->mod_scm->getAll("orders", "*", "id_order = ".$data_transaksi->id_pesanan)[0];
            $data_product = $this->mod_scm->getListBooks($data_transaksi->id_pesanan, $data_transaksi->asal,
                $id_category, $zona);

            ## ADD TO ORDER DETAIL & TRANSAKSI DETAIL
            $add_order = [];
            $add_transaksi = [];
            $add_detail_order = [];
            foreach ($data_product as $row_order) {
                $new_qty = $new_data[$row_order->id_produk];
                if ($new_qty > 0) {
                    $add_order[] = [
                        'id_order' => $data_transaksi->id_pesanan,
                        'kode_buku' => $row_order->kode_buku,
                        'product_id' => $row_order->id_produk,
                        'product_name' => $row_order->judul_buku,
                        'product_quantity' => $new_qty,
                        'unit_price' => $row_order->harga_buku,
                        'total_price' => $new_qty * $row_order->harga_buku,
                    ];

                    $add_transaksi[] = [
                        'id_transaksi' => $id_transaksi,
                        'id_produk' => $row_order->id_produk,
                        'jumlah' => $new_qty,
                        'berat' => $new_qty * $row_order->berat_buku,
                        'harga' => $new_qty * $row_order->harga_buku,
                    ];

                    $add_detail_order[] = [
                        'kode_buku' => $row_order->kode_buku,
                        'nama_buku' => $row_order->judul_buku,
                        'jml_awal' => 0,
                        'jml_akhir' => $new_qty,
                    ];
                }
            }
            $this->mod_scm->addBatch("order_detail", $add_order);
            $this->mod_scm->addBatch("transaksi_detail", $add_transaksi);

            $payout_status = '';
            $order_status = $this->mod_scm->getAll('order_state', 'name',
                'id_order_state = '.$data_order->current_state)[0]->name;
            $check_comission = $this->mod_scm->getRow('payout_detail', 'id_order = '.$data_order->id_order);
            if ($check_comission > 0) {
                $id_payout_status = $this->mod_scm->getAll('payout_detail', 'status',
                    'id_order = '.$data_order->id_order, 'status desc')[0]->status;
                $payout_status = $this->mod_scm->getAll('payout_state', 'name', 'id = '.$id_payout_status)[0]->name;
            }

            $data_send_mail = [
                'id_order' => $data_order->id_order,
                'reference' => $data_order->reference,
                'order_status' => $order_status,
                'payment_status' => $data_order->sts_bayar == 0 ? 'Belum Bayar' : ($data_order->sts_bayar == 1 ? 'Cicilan' : 'Lunas'),
                'comission_status' => $payout_status,
                'korwil_email' => $data_order->korwil_email,
                'reasons' => $alasan,
                'change_detail' => $add_detail_order,
                'revision_date' => date('Y-m-d H:i:s'),
                'change_type' => 2,
            ];

            ## UPDATE TOTAL ORDER, TRANSAKSI, SPK DETAIL, DAN SPK
            $summary_order = $this->updateSummaryOrder($id_transaksi);
            $stock_status = true;

            if ($summary_order['success']) {
                if ($this->periode == $data_order->periode) {
                    $change_paid_status = $summary_order['change_paid_status'];
                    $difference = 1;
                    $lunas = 0;

                    if ($change_paid_status > 0) {
                        foreach ($data_detail as $rows) {
                            $stock_status_last = $this->updateReportStockStatusPaidStatus($data_transaksi->asal,
                                $rows->id_produk, $rows->jumlah, $rows->jumlah, $change_paid_status);

                            if ($stock_status_last == false) {
                                $this->db->trans_rollback();
                                $this->session->set_flashdata('error', 'Gagal update laporan stock status.');
                                $callBack = [
                                    'success' => 'false',
                                    'message' => 'Gagal update laporan stock status.',
                                    'redirect' => BACKMIN_PATH.'/scmpesanan/detailPesananDiproses/'.$id_transaksi,
                                ];
                                echo json_encode($callBack);
                                exit();
                            }
                        }

                        if ($change_paid_status == 2) {
                            $lunas = 1;
                        }
                    } elseif ($data_order->sts_bayar == 2 && $data_order->nilai_dibayar >= $data_order->total_paid) {
                        $lunas = 1;
                    }

                    foreach ($add_transaksi as $row) {
                        $data_stock = $this->mod_scm->getAll("info_gudang", "*",
                            "periode = ".$this->periode." and id_gudang = ".$data_transaksi->asal." and id_produk = ".$row['id_produk'])[0];

                        if ($data_transaksi->status_transaksi < 5) {
                            $update_stock = [
                                'stok_booking' => $data_stock->stok_booking + $row['jumlah'],
                                'stok_available' => $data_stock->stok_available - $row['jumlah'],
                            ];
                        } elseif ($data_transaksi->status_transaksi >= 5) {
                            $update_stock = [
                                'stok_fisik' => $data_stock->stok_fisik - $row['jumlah'],
                                'stok_available' => $data_stock->stok_available - $row['jumlah'],
                            ];
                        }

                        $stock_status = $this->updateReportStockStatus($data_transaksi->asal, $row['id_produk'],
                            $row['jumlah'], $lunas, $difference);

                        $this->mod_scm->update("info_gudang",
                            "periode = ".$this->periode." and id_gudang = ".$data_transaksi->asal." and id_produk = ".$row['id_produk'],
                            $update_stock);
                    }
                }

                if ($stock_status) {
                    if ($this->db->trans_status() == true) {
                        $send_mail = $this->sendChangeOrderMail($data_send_mail);

                        if ($send_mail) {
                            $this->db->trans_commit();
                            ## ACTION LOG USER
                            $logs['id_transaksi'] = $id_transaksi;
                            $this->logger->logAction('Proses Pesanan Ditambah', $logs);

                            $this->session->set_flashdata('success',
                                'Pesanan #<b>'.$data_transaksi->kode_pesanan.'</b> berhasil ditambah.');
                            $callBack = [
                                'success' => true,
                                'message' => 'Pesanan #'.$data_transaksi->kode_pesanan.' berhasil ditambah.',
                                'redirect' => BACKMIN_PATH.'/scmpesanan/detailPesananDiproses/'.$id_transaksi,
                            ];
                        } else {
                            $this->db->trans_rollback();
                            $this->session->set_flashdata('error', 'Gagal mengirimkan email.');
                            $callBack = [
                                'success' => false,
                                'message' => 'Gagal mengirimkan email.',
                                'redirect' => BACKMIN_PATH.'/scmpesanan/detailPesananDiproses/'.$id_transaksi,
                            ];
                        }
                    } else {
                        $this->db->trans_rollback();
                        $this->session->set_flashdata('error',
                            'Pesanan #<b>'.$data_transaksi->kode_pesanan.'</b> gagal ditambah.');
                        $callBack = [
                            'success' => false,
                            'message' => 'Pesanan #'.$data_transaksi->kode_pesanan.' gagal ditambah.',
                            'redirect' => BACKMIN_PATH.'/scmpesanan/detailPesananDiproses/'.$id_transaksi,
                        ];
                    }
                } else {
                    $this->db->trans_rollback();
                    $this->session->set_flashdata('error', 'Gagal update laporan stock status.');
                    $callBack = [
                        'success' => false,
                        'message' => 'Gagal update laporan stock status.',
                        'redirect' => BACKMIN_PATH.'/scmpesanan/detailPesananDiproses/'.$id_transaksi,
                    ];
                }
            } else {
                $this->db->trans_rollback();
                $this->session->set_flashdata('error',
                    'Pesanan #<b>'.$data_transaksi->kode_pesanan.'</b> gagal ditambah.');
                $callBack = [
                    'success' => false,
                    'message' => 'Pesanan #'.$data_transaksi->kode_pesanan.' gagal ditambah.',
                    'redirect' => BACKMIN_PATH.'/scmpesanan/detailPesananDiproses/'.$id_transaksi,
                ];
            }
        }
        echo json_encode($callBack);
    }

    public function processCancelOrder()
    {
        $id_transaksi = $this->input->post('id_transaksi');
        if (in_array($this->adm_level, $this->auditor_area)) {
            $this->session->set_flashdata('error', 'Maaf, anda tidak dapat melakukan proses ini.');
            $callBack = [
                "success" => "false",
                "message" => "Tidak dapat melakukan proses ini.",
                "redirect" => BACKMIN_PATH."/scmpesanan/detailPesananDiproses/".$id_transaksi,
            ];
        } else {
            $this->db->trans_begin();
            $kode_pesanan = $this->input->post('kode_pesanan');
            $alasan_batal = $this->input->post('alasan');

            $data_transaksi = $this->mod_scm->getAll("transaksi", "*", "id_transaksi = $id_transaksi")[0];
            $detail_transaksi = $this->mod_scm->getAll("transaksi_detail", "*", "id_transaksi = $id_transaksi");
            $data_order = $this->mod_scm->getAll("orders", "*", "id_order = ".$data_transaksi->id_pesanan)[0];

            if (strtoupper($kode_pesanan) == $data_transaksi->kode_pesanan) {
                $stock_status = true;
                ## UPDATE STOCK
                if ($this->periode == $data_order->periode) {
                    $lunas = 0;
                    $difference = 0;
                    if ($data_order->sts_bayar == 2 && $data_order->nilai_dibayar >= $data_order->total_paid) {
                        $lunas = 1;
                    }

                    foreach ($detail_transaksi as $row) {
                        $data_stock = $this->mod_scm->getAll("info_gudang", "*",
                            "periode = ".$this->periode." and id_gudang = ".$data_transaksi->asal." and id_produk = ".$row->id_produk)[0];

                        if ($data_transaksi->status_transaksi < 5) {
                            $update_stock = [
                                'stok_booking' => $data_stock->stok_booking - $row->jumlah,
                                'stok_available' => $data_stock->stok_available + $row->jumlah,
                            ];
                        } elseif ($data_transaksi->status_transaksi >= 5) {
                            $update_stock = [
                                'stok_fisik' => $data_stock->stok_fisik + $row->jumlah,
                                'stok_available' => $data_stock->stok_available + $row->jumlah,
                            ];
                        }

                        $stock_status = $this->updateReportStockStatus($data_transaksi->asal, $row->id_produk,
                            $row->jumlah, $lunas, $difference);

                        $this->mod_scm->update("info_gudang",
                            "periode = ".$this->periode." and id_gudang = ".$data_transaksi->asal." and id_produk = ".$row->id_produk,
                            $update_stock);
                    }
                }

                $payout_status = '';
                $order_status = $this->mod_scm->getAll('order_state', 'name',
                    'id_order_state = '.$data_order->current_state)[0]->name;
                $check_comission = $this->mod_scm->getRow('payout_detail', 'id_order = '.$data_order->id_order);
                if ($check_comission > 0) {
                    $id_payout_status = $this->mod_scm->getAll('payout_detail', 'status',
                        'id_order = '.$data_order->id_order, 'status desc')[0]->status;
                    $payout_status = $this->mod_scm->getAll('payout_state', 'name', 'id = '.$id_payout_status)[0]->name;
                }

                $data_send_mail = [
                    'id_order' => $data_order->id_order,
                    'reference' => $data_order->reference,
                    'order_status' => $order_status,
                    'payment_status' => $data_order->sts_bayar == 0 ? 'Belum Bayar' : ($data_order->sts_bayar == 1 ? 'Cicilan' : 'Lunas'),
                    'comission_status' => $payout_status,
                    'korwil_email' => $data_order->korwil_email,
                    'reasons' => $alasan_batal,
                    'revision_date' => date('Y-m-d H:i:s'),
                ];

                ## DELETE TRANSAKSI & TRANSAKSI DETAIL & TRANSAKSI HISTORY, SPK & SPK DETAIL (IF STATUS > 3)
                if ($data_transaksi->status_transaksi > 3) {
                    $check_spk = $this->mod_scm->getRow("spk_detail", "id_transaksi = $id_transaksi");

                    if ($check_spk > 0) {
                        $data_spk = $this->mod_scm->getAll("spk_detail", "*",
                            "id_spk = (SELECT x.id_spk FROM spk_detail x WHERE x.id_transaksi = $id_transaksi)");
                        $id_spk = $data_spk[0]->id_spk;

                        if (count($data_spk) > 1) {
                            $this->mod_scm->delete('spk_detail', 'id_transaksi', $id_transaksi);

                            $total_spk = $this->mod_scm->getAll("spk_detail",
                                "SUM(jumlah) AS total_jumlah, SUM(berat) AS total_berat", "id_spk = ".$id_spk)[0];
                            $data_total_spk = [
                                'total_jumlah' => $total_spk->total_jumlah,
                                'total_berat' => $total_spk->total_berat,
                            ];
                            $this->mod_scm->update("spk", "id_spk = ".$id_spk, $data_total_spk);
                        } else {
                            $this->mod_scm->delete('spk', 'id_spk', $id_spk);
                            $this->mod_scm->delete('spk_detail', 'id_spk', $id_spk);
                        }

                    }
                }

                $this->mod_scm->delete('transaksi', 'id_transaksi', $id_transaksi);
                $this->mod_scm->delete('transaksi_detail', 'id_transaksi', $id_transaksi);
                $this->mod_scm->delete('transaksi_history', 'id_transaksi', $id_transaksi);
                $this->mod_scm->delete('transaksi_detail_history', 'id_transaksi', $id_transaksi);
                $this->mod_scm->delete('order_scm', 'id_order', $data_transaksi->id_pesanan);

                ## UPDATE ORDER STATUS
                $update_order = [
                    'current_state' => 2,
                    'alasan_batal' => $alasan_batal,
                    'is_intan' => 0,
                    'date_upd' => date('Y-m-d H:i:s'),
                ];
                $this->mod_scm->update("orders", "id_order = ".$data_transaksi->id_pesanan, $update_order);

                ## ADD HISTORY ORDER
                $add_order_history = [
                    'id_employee' => $this->adm_id,
                    'id_order' => $data_transaksi->id_pesanan,
                    'id_order_state' => 2,
                    'notes' => $alasan_batal,
                    'date_add' => date('Y-m-d H:i:s'),
                ];
                $this->mod_scm->add("order_history", $add_order_history);

                if ($stock_status) {
                    if ($this->db->trans_status() == true) {
                        $send_mail = $this->sendCancelOrderMail($data_send_mail);

                        if ($send_mail) {
                            $this->db->trans_commit();
                            ## ACTION LOG USER
                            $logs['id_transaksi'] = $id_transaksi;
                            $this->logger->logAction('Proses Pesanan Dibatalkan', $logs);

                            $this->session->set_flashdata('success',
                                'Pesanan #<b>'.$data_transaksi->kode_pesanan.'</b> berhasil dibatalkan.');
                            $callBack = [
                                "success" => "true",
                                "message" => "Pesanan #".$data_transaksi->kode_pesanan." berhasil dibatalkan.",
                                "redirect" => BACKMIN_PATH."/scmpesanan/indexPesananDiproses",
                            ];
                        } else {
                            $this->db->trans_rollback();
                            $this->session->set_flashdata('error', 'Gagal mengirimkan email.');
                            $callBack = [
                                "success" => "false",
                                "message" => "Gagal mengirimkan email.",
                                "redirect" => BACKMIN_PATH."/scmpesanan/detailPesananDiproses/".$id_transaksi,
                            ];
                        }
                    } else {
                        $this->db->trans_rollback();
                        $this->session->set_flashdata('error',
                            'Pesanan #<b>'.$data_transaksi->kode_pesanan.'</b> gagal dibatalkan.');
                        $callBack = [
                            "success" => "false",
                            "message" => "Pesanan #".$data_transaksi->kode_pesanan." gagal dibatalkan.",
                            "redirect" => BACKMIN_PATH."/scmpesanan/detailPesananDiproses/".$id_transaksi,
                        ];
                    }
                } else {
                    $this->db->trans_rollback();
                    $this->session->set_flashdata('error', 'Gagal update laporan stock status.');
                    $callBack = [
                        "success" => "false",
                        "message" => "Gagal update laporan stock status.",
                        "redirect" => BACKMIN_PATH."/scmpesanan/detailPesananDiproses/".$id_transaksi,
                    ];
                }
            } else {
                $this->db->trans_rollback();
                $this->session->set_flashdata('error', 'Maaf, kode pesanan tidak sesuai.');
                $callBack = [
                    "success" => "false",
                    "message" => "Maaf, kode pesanan tidak sesuai.",
                    "redirect" => BACKMIN_PATH."/scmpesanan/detailPesananDiproses/".$id_transaksi,
                ];
            }
        }
        echo json_encode($callBack);
    }

    public function sendCancelOrderMail($data)
    {
        if ($data) {
            /* Ditutup. Fa, 20200319
			$subject = 'Pembatalan Pesanan #'.$data['reference'];
            $to = [
                'ar@gramediaprinting.com',
                'martina@gramediaprinting.com',
                'hermanwu@gramediaprinting.com',
                $data['korwil_email'],
            ];
            $content = '<p>Pesanan <b>#'.$data['reference'].'</b> telah dibatalkan.</p>
                            <table>
                                <tr>
                                    <td>Status Order</td>
                                    <td>:</td>
                                    <td>'.$data['order_status'].'</td>
                                </tr>
                                <tr>
                                    <td>Status Pembayaran</td>
                                    <td>:</td>
                                    <td>'.$data['payment_status'].'</td>
                                </tr>
                                <tr>
                                    <td>Status Komisi</td>
                                    <td>:</td>
                                    <td>'.$data['comission_status'].'</td>
                                </tr>
                                <tr>
                                    <td>Tanggal Pembatalan</td>
                                    <td>:</td>
                                    <td>'.$data['revision_date'].'</td>
                                </tr>
                                <tr>
                                    <td>Alasan Pembatalan</td>
                                    <td>:</td>
                                    <td>'.$data['reasons'].'</td>
                                </tr>
                            </table>
                            <br/><p>Terima Kasih</p>';

            $this->load->library('mymail');
            $this->mymail->send($subject, $to, $content);
			*/
            return true;
        }

        return false;
    }
}
