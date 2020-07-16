<?php
defined('BASEPATH') or exit('No direct script access allowed');

/**
 * @property Datatables $datatables
 * @property Mod_general $mod_general
 * @property Mod_adjusment $mod_adjusment
 */
class Gudangadjusment extends MY_Controller
{
    public function __construct()
    {
        parent::__construct();
        if (!in_array($this->adm_level, $this->backmin_gudang_area)) {
            redirect(BACKMIN_PATH);
        }
        $this->load->model('mod_general');
        $this->load->model('mod_adjusment');
    }

    public function index()
    {
        redirect(BACKMIN_PATH . '/Gudangadjusment/index_adjusment');
    }

    public function index_adjusment()
    {
        $data['page_title'] = 'Pesanan Sekolah - Masuk';
        $data['content']    = $this->load->view(BACKMIN_PATH . '/gudang/adjusment/list', $data, true);
        $data['script_js']  = $this->load->view(BACKMIN_PATH . '/gudang/adjusment/list_js', '', true);
        $this->load->view(BACKMIN_PATH . '/main', $data);
    }

    public function list_data_adjusment()
    {
        if (!$this->input->is_ajax_request()) {
            return false;
        }
        $this->load->library('datatables');
        $this->output->set_header('Content-Type:application/json; charset=utf-8');


        // $this->datatables->select('a.id AS id, a.id_order AS id_order, a.reference AS reference, c.school_name AS school_name, b.category AS class_name, b.type AS type_name, c.provinsi AS provinsi, c.kabupaten AS kabupaten, b.date_add AS date_add, DATE_FORMAT(ADDDATE(b.tgl_konfirmasi, IF(b.jangka_waktu <> "", b.jangka_waktu, 0)), "%Y-%m-%d") AS target_kirim');
        // $this->datatables->from('order_scm a');
        // $this->datatables->join('orders b', 'b.id_order=a.id_order', 'inner');
        // $this->datatables->join('customer c', 'c.id_customer=a.id_customer', 'inner');
        // $this->datatables->where('a.status', '1');
        // $this->datatables->where('a.id_gudang', $this->adm_id_gudang);
        // $this->datatables->edit_column('reference', '<a href="' . base_url(BACKMIN_PATH . '/gudangpesanan/detailPesananMasuk/$1') . '">$2</a>', 'id_order, reference');
        // $this->datatables->add_column('detail', '<center><a href="' . base_url(BACKMIN_PATH . '/gudangpesanan/detailPesananMasuk/$1') . '" class="btn btn-default btn-rounded btn-condensed btn-sm"><span class="fa fa-search"></span></a></center>', 'id_order');

        $this->datatables->select('a.id_transaksi as id_transaksi,a.catatan AS catatan, a.total_jumlah AS total_jumlah, IF(a.status_transaksi=1,"Dibuat", "Selesai") AS status_transaksi, a.created_date AS tanggal');
        $this->datatables->from('transaksi a');
        $this->datatables->join('master_gudang b', 'a.asal=b.id_gudang', 'inner');
        $this->datatables->where('a.id_tipe', '3');
        $this->datatables->where('a.asal', $this->adm_id_gudang);
        $this->datatables->edit_column('id_transaksi', '<a href="' . base_url(BACKMIN_PATH . '/gudangadjusment/detail_adjusment/$1') . '">$1</a>', 'id_transaksi');
        $this->output->set_output($this->datatables->generate());
    }

    public function detail_adjusment($id_transaksi)
    {
        if ($id_transaksi && is_numeric($id_transaksi)) {
            $data['adjusment'] = $this->mod_adjusment->getAll('transaksi', '*', array("id_transaksi" => $id_transaksi));
            
            $id_transaksi = $data['adjusment'][0]->id_transaksi;
            $data['adjusment_detail'] = $this->mod_adjusment->get_detail_adjusment($id_transaksi);

            $data['gudang'] = $this->mod_adjusment->getAll('master_gudang', '*', array("id_gudang" => $data['adjusment'][0]->asal));

            // print_r($data['adjusment']);
            // echo "<br><br>";
            // print_r($data['adjusment_detail']);
            $data['content']        = $this->load->view(BACKMIN_PATH . '/gudang/adjusment/detil', $data, true);
            $data['script_js']      = $this->load->view(BACKMIN_PATH . '/gudang/adjusment/detil_js', '', true);
            $this->load->view(BACKMIN_PATH . '/main', $data);
        }
        else
        {
            redirect(BACKMIN_PATH . '/gudangadjusment');
        }
    }

    public function detailPesananMasuk($id)
    {
        if ($id && is_numeric($id)) {
            $data['page_title']     = 'Detil Pesanan Sekolah - Masuk';
            $data['detail']         = $this->mod_general->detailData('orders', 'id_order', $id);
            $data['customer']       = $this->mod_general->detailData('customer', 'id_customer', $data['detail']['id_customer']);
            $data['listproducts']   = $this->mod_gudang->getListProductStock($id, $this->adm_id_gudang);
            $data['sales']          = $this->mod_gudang->getAll("employee", "id_employee, level, name, email, active, telp", "email='" . $data['detail']['sales_referer'] . "'")[0];
            $getEmployeeKabupaten   = $this->mod_gudang->getAll("employee_kabupaten_kota", "*", "kabupaten_kota='" . $data['customer']['kabupaten'] . "'");
            $employeeId             = [];
            foreach ($getEmployeeKabupaten as $row => $value) {
                $employeeId[]       = $value->id_employee;
            }
            $employeeId             = implode(',', $employeeId);
            $data['korwil']         = $this->mod_gudang->getAll("employee", "id_employee, level, name, email, active, telp", "level = 3 and id_employee in (" . $employeeId . ")")[0];
            $data['content']        = $this->load->view(BACKMIN_PATH . '/gudang/pesanan_masuk/detil', $data, true);
            $data['script_js']      = $this->load->view(BACKMIN_PATH . '/gudang/pesanan_masuk/detil_js', '', true);
            $this->load->view(BACKMIN_PATH . '/main', $data);
        } else {
            redirect(BACKMIN_PATH . '/gudangpesanan');
        }
    }

    public function add()
    {
        $data['test'] = "";
        $data['content']            = $this->load->view(BACKMIN_PATH . '/gudang/adjusment/add', $data, true);
        $data['script_js']          = $this->load->view(BACKMIN_PATH . '/gudang/adjusment/add_js', '', true);
        $this->load->view(BACKMIN_PATH . '/main', $data);
    }

    public function add_post()
    {
        $this->db->trans_begin();
        // HEADER
        $id_transaksi       = "";
        $id_tipe            = "3";
        $asal               = $this->adm_id_gudang;
        $total_jumlah       = 0;
        $total_berat        = 0;
        $total_harga        = 0;
        $have_tag           = "0";
        $is_to_school       = "0";
        $is_forward         = "0";
        $status_transaksi   = "1";

        $catatan            = $this->input->post("catatan");
        $tanggal            = $this->input->post("tanggal");;

        $transaksi = array();
        $transaksi['catatan'] = $catatan;
        $transaksi['created_date'] = date('Y-m-d', strtotime($tanggal));
        $transaksi['created_by'] = $this->adm_id;

        $transaksi['id_tipe'] = $id_tipe;
        $transaksi['asal'] = $asal;
        $transaksi['have_tag'] = $have_tag;
        $transaksi['is_to_school'] = $is_to_school;
        $transaksi['is_forward'] = $is_forward;
        $transaksi['status_transaksi'] = $status_transaksi;

        print_r($transaksi);
                echo "<br>";
                echo "<br>";

        $id_transaksi = $this->mod_adjusment->add('transaksi', $transaksi);

        //DETAIL
        $id_buku            = $this->input->post("id_buku");
        $kode_buku          = $this->input->post("kode_buku");
        $judul              = $this->input->post("judul_buku");
        $berat              = $this->input->post("berat_buku");
        $harga              = $this->input->post("harga_buku");
        $qty                = $this->input->post("qty");

        $count = 0;

        $transaksi_detail = array();
        foreach($id_buku as $d)
        {
            if($d != "" || !empty($d))
            {
                $transaksi_detail['id_transaksi']   = $id_transaksi;
                $transaksi_detail['id_produk']      = $d;
                $transaksi_detail['jumlah']         = $qty[$count];
                $transaksi_detail['berat']          = $berat[$count];
                $transaksi_detail['harga']          = $harga[$count];

                $this->mod_adjusment->addDetail('transaksi_detail', $transaksi_detail);

                $total_jumlah   = $total_jumlah + $transaksi_detail['jumlah'];
                $total_berat    = $total_berat + ($transaksi_detail['jumlah'] * $transaksi_detail['berat']);
                $total_harga    = $total_harga + ($transaksi_detail['jumlah'] * $transaksi_detail['harga']);
                print_r($transaksi_detail);
                echo "<br>";

            }
            $count++;
        }

        $update_transaksi = array();
        $update_transaksi['total_jumlah']   = $total_jumlah;
        $update_transaksi['total_berat']    = $total_berat;
        $update_transaksi['total_harga']    = $total_harga;
                echo "<br>";
        print_r($update_transaksi);

        $this->mod_adjusment->edit('transaksi',array('id_transaksi' => $id_transaksi), $update_transaksi);

        if ($this->db->trans_status() === TRUE)
        {
                $this->db->trans_commit();
                $this->session->set_flashdata('success', 'Data adjusment telah berhasil ditambahkan.');
                redirect('backmin/gudangadjusment');
        }
        else
        {
                $this->db->trans_rollback();
                $this->session->set_flashdata('success', 'Data adjusment gagal ditambahkan.');
                redirect('backmin/gudangadjusment');
        }
    }

    public function get_data_product()
    {
        $kode_buku = $this->input->post("kode_buku");
        $query = $this->mod_general->getAll('product', '*', array("kode_buku" => $kode_buku));
        echo json_encode($query);
    }

    ## TODO : Buat log dan auditor
    public function processPesananMasuk()
    {
        if (!$this->input->is_ajax_request()) {
            redirect(BACKMIN_PATH . '/gudangpesanan', 'refresh');
        }
        
        $id_pesanan             = $this->input->post('id_order');
        if (in_array($this->adm_level, $this->auditor_area)) {
            $this->session->set_flashdata('error', 'Maaf, anda tidak dapat melakukan proses ini.');
            $callBack   = [   
                "success"       => "false",
                "message"       => "Tidak dapat melakukan proses ini.",
                "redirect"      => "backmin/gudangpesanan/detailPesananMasuk/$id_pesanan",
            ];
        } else {
            $checkStatusOrder = $this->mod_gudang->getAll("orders","*","id_order='$id_pesanan'");
            if($checkStatusOrder[0]->current_state == 2)
            {
                $this->session->set_flashdata('error', 'Maaf, anda tidak dapat melakukan proses ini. Pesanan telah dibatalkan.');
                $callBack   = [   
                    "success"       => "false",
                    "message"       => "Tidak dapat melakukan proses ini. Pesanan telah dibatalkan.",
                    "redirect"      => "backmin/gudangpesanan/indexPesananMasuk",
                ];
            }
            else
            {
            $this->db->trans_begin();
            $kode_pesanan           = $this->input->post('kode_pesanan');
            $id_customer            = $this->input->post('id_customer');
            $id_produk              = $this->input->post('id_product');
            $jumlah                 = $this->input->post('product_quantity');
            $berat                  = $this->input->post('weight');
            $harga                  = $this->input->post('price');
            $periode_order          = (int)$this->input->post('periode_order');
            $gudang_asal            = $this->adm_id_gudang;
            $haveTAG                = 0;
            $status_transaksi       = 2;

            $where_exist            = "id_pesanan = $id_pesanan and have_tag = $haveTAG and is_to_school = 1 and is_forward = 0";
            $exist_order            = $this->mod_gudang->getRow('transaksi', $where_exist);

            if ($exist_order == 0) {
                $stock_status       = true;
                $available_stock    = true;

                if ($periode_order == $this->periode) {
                    $lunas              = 0;
                    $data_pesanan       = $this->mod_gudang->getAll("orders", "total_paid, sts_bayar, nilai_dibayar", "id_order = $id_pesanan")[0];
                    if ($data_pesanan->sts_bayar == 2 || $data_pesanan->nilai_dibayar >= $data_pesanan->total_paid) {
                        $lunas          = 1;
                    }
    
                    foreach ($id_produk as $num => $row) {
                        $quantity       = $jumlah[$num];
                        $stok           = $this->mod_gudang->getStok($gudang_asal, $row, "stok_booking, stok_available");

                        if ($quantity > $stok->stok_available) {
                            $available_stock = false;
                        } else {
                            $updateStok['stok_booking']     = $stok->stok_booking + $quantity;
                            $updateStok['stok_available']   = $stok->stok_available - $quantity;
    
                            // UPDATE WAREHOUSE STOCK
                            $this->mod_gudang->updateStok($gudang_asal, $row, $updateStok);
                            // UPDATE REPORT STOCK STATUS
                            $stock_status   = $this->addReportStockStatus($gudang_asal, $row, $quantity, $lunas);
                        }
                    }
                }

                $pesanan['id_pesanan']              = $id_pesanan;
                $pesanan['kode_pesanan']            = $kode_pesanan;
                $pesanan['id_tipe']                 = 2;
                $pesanan['asal']                    = $this->adm_id_gudang;
                $pesanan['tujuan']                  = $id_customer;
                $pesanan['have_tag']                = $haveTAG;
                $pesanan['is_to_school']            = 1;
                $pesanan['status_transaksi']        = $status_transaksi;
                $pesanan['created_date']            = date('Y-m-d H:i:s');
                $pesanan['created_by']              = $this->adm_id;
                $pesanan['updated_date']            = date('Y-m-d H:i:s');
                $pesanan['updated_by']              = $this->adm_id;

                // TRANSAKSI
                $idTransaksi = $this->mod_gudang->add('transaksi', $pesanan);
                // HISTORY TRANSAKSI
                $this->mod_gudang->addTransaksiHistory($idTransaksi, 1);
                $this->mod_gudang->addTransaksiHistory($idTransaksi, $status_transaksi);

                $pesananDetail['id_produk']         = $id_produk;
                $pesananDetail['jumlah']            = $jumlah;
                $pesananDetail['berat']             = $berat;
                $pesananDetail['harga']             = $harga;
                $dataPesananDetail                  = [];

                foreach ($pesananDetail as $field => $data) {
                    foreach ($data as $key => $value) {
                        $dataPesananDetail[$key][$field] = $value;
                    }
                }

                $pesananTambahan['total_jumlah']    = 0;
                $pesananTambahan['total_berat']     = 0;
                $pesananTambahan['total_harga']     = 0;

                foreach ($dataPesananDetail as $rows => $values) {
                    $dataDetail['id_transaksi']     = $idTransaksi;
                    $dataDetail['id_produk']        = $values['id_produk'];
                    $dataDetail['berat']            = $values['berat'] * $values['jumlah'];
                    $dataDetail['jumlah']           = $values['jumlah'];
                    $dataDetail['harga']            = $values['harga'];

                    // TRANSAKSI DETAIL
                    $this->mod_gudang->addDetail('transaksi_detail', $dataDetail);

                    $pesananTambahan['total_jumlah']    += $values['jumlah'];
                    $pesananTambahan['total_berat']     += $values['berat'] * $values['jumlah'];
                    $pesananTambahan['total_harga']     += $values['harga'];
                }

                // EDIT TRANSAKSI
                $this->mod_gudang->edit("transaksi", "id_transaksi = " . $idTransaksi, $pesananTambahan);

                if ($available_stock) {
                    if ($stock_status) {
                        if ($this->db->trans_status() === true) {
                            $status['status'] = 2;
                            $this->mod_gudang->edit("order_scm", "id_order = " . $id_pesanan, $status);

                            ## ACTION LOG USER
                            $logs['id_order'] = $id_pesanan;
                            $this->logger->logAction('Proses Pesanan Masuk', $logs);
                            
                            $this->db->trans_commit();
                            $this->session->set_flashdata('success', 'Pesanan #<b>' . $kode_pesanan . '</b> berhasil dibuat.');
                            $callBack = [
                                "success"   => "true",
                                "message"   => "Transaski telah berhasil dibuat",
                                "redirect"  => "backmin/gudangpesanan/indexPesananMasuk",
                            ];
                        } else {
                            $this->db->trans_rollback();
                            $this->session->set_flashdata('error', 'Gagal melakukan proses ini.');
                            $callBack = [
                                "success"   => "false",
                                "message"   => "Gagal melakukan proses.",
                                "redirect"  => "backmin/gudangpesanan/detailPesananMasuk/$id_pesanan",
                            ];
                        }
                    } else {
                        $this->db->trans_rollback();
                        $this->session->set_flashdata('error', 'Gagal update laporan stock status.');
                        $callBack = [
                            "success"   => "false",
                            "message"   => "Gagal update laporan stock status.",
                            "redirect"  => "backmin/gudangpesanan/detailPesananMasuk/$id_pesanan",
                        ];
                    }
                } else {
                    $this->db->trans_rollback();
                    $this->session->set_flashdata('error', 'Maaf, stok produk tidak mencukupi.');
                    $callBack = [
                        "success"   => "false",
                        "message"   => "Maaf, stok produk tidak mencukupi.",
                        "redirect"  => "backmin/gudangpesanan/detailPesananMasuk/$id_pesanan",
                    ];
                }
            } else {
                $this->db->trans_rollback();
                $this->session->set_flashdata('error', 'Pesanan sudah diproses.');
                $callBack = [
                    "success"       => "false",
                    "message"       => "Pesanan sudah diproses.",
                    "redirect"      => "backmin/gudangpesanan/detailPesananMasuk/$id_pesanan",
                ];
            }
            }
        }
        echo json_encode($callBack, true);
    }

    public function detailPesananForward($idTransaksi, $idGudangForward)
    {
        if ($idTransaksi && $idGudangForward) {
            $data['transaksi']      = $this->mod_gudang->getAll("transaksi", "*", "id_transaksi = " . $idTransaksi)[0];
            $data['page_title']     = 'Detil Forward Pesanan Sekolah';
            $data['detail']         = $this->mod_general->detailData('orders', 'id_order', $data['transaksi']->id_pesanan);
            $data['customer']       = $this->mod_general->detailData('customer', 'id_customer', $data['detail']['id_customer']);
            $data['forward_gudang'] = $this->mod_gudang->getAll("master_gudang", "*", "id_gudang = " . $idGudangForward)[0];
            $data['listproducts']   = $this->mod_gudang->getListProductStock($data['transaksi']->id_pesanan, $idGudangForward);
            $data['sales']          = $this->mod_gudang->getAll("employee", "id_employee, level, name, email, active, telp", "email='" . $data['detail']['sales_referer'] . "'")[0];
            $getEmployeeKabupaten   = $this->mod_gudang->getAll("employee_kabupaten_kota", "*", "kabupaten_kota='" . $data['customer']['kabupaten'] . "'");
            $employeeId             = [];
            foreach ($getEmployeeKabupaten as $row => $value) {
                $employeeId[] = $value->id_employee;
            }
            $employeeId        = implode(',', $employeeId);
            $data['korwil']    = $this->mod_gudang->getAll("employee", "id_employee, level, name, email, active, telp", "level = 3 and id_employee in (" . $employeeId . ")")[0];
            $data['content']   = $this->load->view(BACKMIN_PATH . '/gudang/pesanan_masuk/detil_forward', $data, true);
            $data['script_js'] = $this->load->view(BACKMIN_PATH . '/gudang/pesanan_masuk/detil_forward_js', '', true);
            $this->load->view(BACKMIN_PATH . '/main', $data);
        } else {
            redirect(BACKMIN_PATH . '/gudangpesanan');
        }
    }

    public function popWarehouse($idTransaksi, $idGudang, $idSite)
    {
        if (!$this->input->is_ajax_request()) {
            return false;
        }
        if ($idTransaksi && $idGudang && $idSite) {
            $data['list_warehouse'] = $this->mod_gudang->getAll("master_gudang", "*", "status='1' AND id_gudang<>'" . $idGudang . "'", "nama_gudang ASC");
            $data['id_transaksi']   = $idTransaksi;
            echo $this->load->view(BACKMIN_PATH . '/gudang/pesanan_masuk/pop_warehouse', $data, true);
        }
    }

    ## TODO : Buat log dan auditor
    public function processPesananForward()
    {
        if (!$this->input->is_ajax_request()) {
            redirect(BACKMIN_PATH . '/gudangpesanan', 'refresh');
        }

        $currentIdTransaksi     = $this->input->post('id_transaksi');
        $idGudangForward        = $this->input->post('id_gudang_forward');
        if (in_array($this->adm_level, $this->auditor_area)) {
            $this->session->set_flashdata('error', 'Maaf, anda tidak dapat melakukan proses ini.');
            $callBack   = [   
                "success"       => "false",
                "message"       => "Tidak dapat melakukan proses ini.",
                "redirect"      => "backmin/gudangpesanan/detailPesananForward/$currentIdTransaksi/$idGudangForward",
            ];
        } else {
            $this->db->trans_begin();
            $currentIdTransaksi             = $this->input->post('id_transaksi');
            $id_pesanan                     = $this->input->post('id_pesanan');
            $kode_pesanan                   = $this->input->post('kode_pesanan');
            $idGudangAsal                   = $this->adm_id_gudang;
            $idGudangForward                = $this->input->post('id_gudang_forward');
            $id_customer                    = $this->input->post('id_customer');
            $idProducts                     = $this->input->post('id_produk');
            $berat                          = $this->input->post('berat');
            $harga                          = $this->input->post('harga');
            $jumlah                         = $this->input->post('jumlah');
            $isTAG                          = $this->input->post('is_tag');
            $namaGudangForward              = $this->input->post('nama_gudang_forward');
            $periode_order                  = (int)$this->input->post('periode_order');
            $detail                         = [];
            $detailTAG                      = [];
            $totalJumlah                    = 0;
            $totalBerat                     = 0;
            $totalHarga                     = 0;
            $totalJumlahTAG                 = 0;
            $totalBeratTAG                  = 0;
            $haveTAG                        = 0;
            $status_transaksi               = 1;
            foreach ($idProducts as $row => $id_produk) {
                $detail[$row]['id_produk']  = $id_produk;
                $detail[$row]['jumlah']     = $jumlah[$row];
                $detail[$row]['berat']      = $berat[$row] * $jumlah[$row];
                $detail[$row]['harga']      = $harga[$row];
                $currentStock               = $this->mod_gudang->getStok($idGudangAsal, $id_produk, "stok_booking, stok_available");
                $newStock                   = $this->mod_gudang->getStok($idGudangForward, $id_produk, "stok_booking, stok_available");
                $jumlahStock                = $jumlah[$row];
                if ($isTAG[$row] > 0) {
                    $haveTAG                      = 1;
                    $status_transaksi             = 3;
                    $jumlahStock                  = $newStock->stok_available;
                    $jumlahStockTAG               = $jumlah[$row] - $newStock->stok_available;
                    $detailTAG[$row]['id_produk'] = $id_produk;
                    $detailTAG[$row]['jumlah']    = $jumlahStockTAG;
                    $detailTAG[$row]['berat']     = $berat[$row] * $jumlahStockTAG;

                    $totalJumlahTAG     += $jumlahStockTAG;
                    $totalBeratTAG      += $berat[$row] * $jumlahStockTAG;
                }
                // Restore stock of current warehouse
                $updateStok['stok_booking']   = $currentStock->stok_booking - $jumlahStock;
                $updateStok['stok_available'] = $currentStock->stok_available + $jumlahStock;
                if ($periode_order == $this->periode) {
                    $this->mod_gudang->updateStok($idGudangAsal, $id_produk, $updateStok);
                }
                // Adding stock of new warehouse
                $updateStokNew['stok_booking']   = $newStock->stok_booking + $jumlahStock;
                $updateStokNew['stok_available'] = $newStock->stok_available - $jumlahStock;
                if ($periode_order == $this->periode) {
                    $this->mod_gudang->updateStok($idGudangForward, $id_produk, $updateStokNew);
                }
                $totalJumlah    += $jumlah[$row];
                $totalBerat     += $berat[$row] * $jumlah[$row];
                $totalHarga     += $harga[$row];
            }
            // For Current Transaksi
            $currentTransaksi['is_forward']         = 1;
            $currentTransaksi['status_transaksi']   = 6;
            $currentTransaksi['updated_date']       = date('Y-m-d H:i:s');
            $currentTransaksi['updated_by']         = $this->adm_id;
            $this->mod_gudang->edit("transaksi", "id_transaksi = " . $currentIdTransaksi, $currentTransaksi);
            $this->mod_gudang->addTransaksiHistory($currentIdTransaksi, 6, 'Pindah ke ' . $this->input->post('nama_gudang_forward'));
            // Buat transaksi baru
            $data['id_pesanan']       = $id_pesanan;
            $data['kode_pesanan']     = $kode_pesanan;
            $data['id_tipe']          = 2;
            $data['asal']             = $idGudangForward;
            $data['tujuan']           = $id_customer;
            $data['total_jumlah']     = $totalJumlah;
            $data['total_berat']      = $totalBerat;
            $data['total_harga']      = $totalHarga;
            $data['have_tag']         = $haveTAG;
            $data['is_to_school']     = 1;
            $data['status_transaksi'] = $status_transaksi;
            $data['created_date']     = date('Y-m-d H:i:s');
            $data['created_by']       = $this->adm_id;
            $data['updated_date']     = date('Y-m-d H:i:s');
            $data['updated_by']       = $this->adm_id;
            $newIdTransaksi           = $this->mod_gudang->add('transaksi', $data);
            $this->mod_gudang->addTransaksiHistory($newIdTransaksi, $status_transaksi);
            foreach ($detail as $rows => $values) {
                $dataDetail['id_transaksi'] = $newIdTransaksi;
                $dataDetail['id_produk']    = $values['id_produk'];
                $dataDetail['jumlah']       = $values['jumlah'];
                $dataDetail['berat']        = $values['berat'];
                $dataDetail['harga']        = $values['harga'];
                $this->mod_gudang->addDetail('transaksi_detail', $dataDetail);
            }
            // IF have tag
            if ($haveTAG > 0) {
                // Buat transaksi baru TAG
                $dataTAG['id_tipe']          = 2;
                $dataTAG['ref_id']           = $newIdTransaksi;
                $dataTAG['asal']             = $idGudangAsal;
                $dataTAG['tujuan']           = $idGudangForward;
                $dataTAG['total_jumlah']     = $totalJumlahTAG;
                $dataTAG['total_berat']      = $totalBeratTAG;
                $dataTAG['have_tag']         = 0;
                $dataTAG['is_to_school']     = 0;
                $dataTAG['status_transaksi'] = 1;
                $dataTAG['created_date']     = date('Y-m-d H:i:s');
                $dataTAG['created_by']       = $this->adm_id;
                $dataTAG['updated_date']     = date('Y-m-d H:i:s');
                $dataTAG['updated_by']       = $this->adm_id;
                $idTransaksiTAG              = $this->mod_gudang->add('transaksi', $dataTAG);
                $this->mod_gudang->addTransaksiHistory($idTransaksiTAG, 1);
                foreach ($detailTAG as $rowsTAG => $valuesTAG) {
                    $dataDetailTAG['id_transaksi'] = $idTransaksiTAG;
                    $dataDetailTAG['id_produk']    = $valuesTAG['id_produk'];
                    $dataDetailTAG['jumlah']       = $valuesTAG['jumlah'];
                    $dataDetailTAG['berat']        = $valuesTAG['berat'];
                    $this->mod_gudang->addDetail('transaksi_detail', $dataDetailTAG);
                }
            }
            if ($this->db->trans_status() === true) {
                ## ACTION LOG USER
                $logs['id_transaksi']       = $currentIdTransaksi;
                $logs['id_gudang_forward']  = $idGudangForward;
                $this->logger->logAction('Proses Pesanan Forward', $logs);
                
                $this->db->trans_commit();
                $this->session->set_flashdata('success', 'Pesanan #<b>' . $kode_pesanan . '</b> berhasil dipindahkan ke ' . $namaGudangForward);
                $callBack = [
                    "success"   => "true",
                    "message"   => "Pesanan berhasil dipindahkan",
                    "redirect"  => "backmin/gudangpesanan/indexPesananMasuk",
                ];
            } else {
                $this->db->trans_rollback();
                $this->session->set_flashdata('error', 'Gagal melakukan proses ini.');
                $callBack = [
                    "success"   => "false",
                    "message"   => "Gagal melakukan proses.",
                    "redirect"  => "backmin/gudangpesanan/detailPesananForward/$currentIdTransaksi/$idGudangForward",
                ];
            }
        }
        echo json_encode($callBack);
    }

    public function indexPesananDiproses()
    {
        $data['page_title'] = 'Pesanan Sekolah - Diproses';
        $data['content']    = $this->load->view(BACKMIN_PATH . '/gudang/pesanan_diproses/list', $data, true);
        $data['script_js']  = $this->load->view(BACKMIN_PATH . '/gudang/pesanan_diproses/list_js', '', true);
        $this->load->view(BACKMIN_PATH . '/main', $data);
    }

    public function listDataPesananDiproses()
    {
        if (!$this->input->is_ajax_request()) {
            return false;
        }
        $this->load->library('datatables');
        $this->output->set_header('Content-Type:application/json; charset=utf-8');
        $this->datatables->select('a.id AS id, a.id_order AS id_order, a.reference AS reference, b.id_transaksi, c.school_name AS school_name, d.category AS class_name, d.type AS type_name, c.provinsi AS provinsi, c.kabupaten AS kabupaten, a.date_created AS date_add, DATE_FORMAT(ADDDATE(d.tgl_konfirmasi, IF(d.jangka_waktu <> "", d.jangka_waktu, 0)), "%Y-%m-%d") AS target_kirim, CASE b.status_transaksi WHEN 1 THEN CONCAT("<span class=\'label label-default\'>Dibuat</span>") WHEN 2 THEN CONCAT("<span class=\'label label-warning\'>Diproses</span>") WHEN 3 THEN CONCAT("<span class=\'label label-warning\'>Menunggu TAG</span>") WHEN 4 THEN CONCAT("<span class=\'label label-warning\'>SPK Dibuat</span>") WHEN 5 THEN CONCAT("<span class=\'label label-primary\'>Dikirim Ekspedisi</span>") WHEN 6 THEN CONCAT("<span class=\'label label-success\'>Telah Sampai</span>") END AS status_transaksi');
        $this->datatables->from('order_scm a');
        $this->datatables->join('transaksi b', 'b.id_pesanan=a.id_order', 'inner');
        $this->datatables->join('customer c', 'c.id_customer=a.id_customer', 'inner');
        $this->datatables->join('orders d', 'd.id_order=a.id_order', 'inner');
        $this->datatables->where('b.asal', $this->adm_id_gudang);
        $this->datatables->where('a.status >', 1);
        $this->datatables->where('(b.status_transaksi = 2 OR b.status_transaksi > 3)');
        $this->datatables->edit_column('reference', '<a href="' . base_url(BACKMIN_PATH . '/gudangpesanan/detailPesananDiproses/$1') . '">$2</a>', 'id_order, reference');
        $this->output->set_output($this->datatables->generate());
    }

    public function detailPesananDiproses($id)
    {
        if ($id && is_numeric($id)) {
            $data['page_title']            = 'Detil Pesanan Sekolah - Diproses';
            $data['detail']                = $this->mod_general->detailData('orders', 'id_order', $id);
            $data['customer']              = $this->mod_general->detailData('customer', 'id_customer', $data['detail']['id_customer']);
            $data['recommended_warehouse'] = $this->mod_gudang->getAll("master_gudang", "*", "id_gudang = " . $this->adm_id_gudang)[0];
            $data['listproducts']          = $this->mod_gudang->getListProductStock($id, $this->adm_id_gudang);
            $data['sales']                 = $this->mod_gudang->getAll("employee", "id_employee, level, name, email, active, telp", "email='" . $data['detail']['sales_referer'] . "'")[0];
            $getEmployeeKabupaten          = $this->mod_gudang->getAll("employee_kabupaten_kota", "*", "kabupaten_kota='" . $data['customer']['kabupaten'] . "'");
            $employeeId                    = [];
            foreach ($getEmployeeKabupaten as $row => $value) {
                $employeeId[] = $value->id_employee;
            }
            $employeeId              = implode(',', $employeeId);
            $data['korwil']          = $this->mod_gudang->getAll("employee", "id_employee, level, name, email, active, telp", "level = 3 and id_employee in (" . $employeeId . ")")[0];
            $data['transaksi']       = $this->mod_gudang->getTransaksi($id, $this->adm_id_gudang);
            $data['list_gudang_tag'] = false;
            if ($data['transaksi']->have_tag > 0) {
                $data['list_gudang_tag'] = $this->mod_gudang->getListGudangTAG($data['transaksi']->id_transaksi);
            }
            if ($data['transaksi']->is_forward > 0) {
                $transaksiForward       = $this->mod_gudang->getAll("transaksi", "asal, created_date", "id_pesanan=" . $id, "id_transaksi desc")[0];
                $data['gudang_forward'] = $this->mod_gudang->getAll("master_gudang", "*", "id_gudang=" . $transaksiForward->asal)[0];
                $data['date_forward']   = $transaksiForward->created_date;
            }
            $data['status_transaksi'] = '';
            switch ($data['transaksi']->status_transaksi) {
                case 1:
                    $data['status_transaksi'] = 'Dibuat';
                    break;
                case 2:
                    $data['status_transaksi'] = 'Diproses';
                    break;
                case 3:
                    $data['status_transaksi'] = 'Menunggu TAG';
                    break;
                case 4:
                    $data['status_transaksi'] = 'SPK Dibuat';
                    break;
                case 5:
                    $data['status_transaksi'] = 'Dikirim Ekspedisi';
                    break;
                case 6:
                    $data['status_transaksi'] = 'Telah Sampai';
                    break;
            }
            // FOR TRANSAKSI HISTORY
            $transaksiHistory          = $this->mod_gudang->getAll("transaksi_history", "*", "id_transaksi = " . $data['transaksi']->id_transaksi, "id asc");
            $data['transaksi_history'] = [];
            foreach ($transaksiHistory as $row => $dataHistory) {
                // status
                $status = $this->mod_gudang->getAll("transaksi_state", "*", "is_active = 1 and id_transaksi_state = " . $dataHistory->status_transaksi)[0];
                // employee
                $employee = $this->mod_gudang->getAll("employee", "*", "id_employee = " . $dataHistory->id_employee)[0];
                // status SPK
                $notes = "";
                if ($dataHistory->status_transaksi >= 4) {
                    $idSPK = $this->mod_gudang->getAll("spk_detail", "id_spk", "id_transaksi = " . $data['transaksi']->id_transaksi)[0]->id_spk;
                    $spk   = $this->mod_gudang->getAll("spk", "*", "id_spk = " . $idSPK)[0];
                    if ($dataHistory->status_transaksi == 4) {
                        $notes = 'Kode SPK &nbsp; : &nbsp; <a href="' . base_url(BACKMIN_PATH . '/gudangpengiriman/detailPengiriman/' . $idSPK) . '"><b>' . $spk->kode_spk . "</b></a><br>";
                    } elseif ($dataHistory->status_transaksi == 5) {
                        $ekspeditur = $this->mod_gudang->getAll("ekspeditur", "nama", "id = " . $spk->id_ekspeditur)[0];
                        $notes      = 'Ekspeditur &nbsp; : &nbsp; <b>' . $ekspeditur->nama . "</b><br>";
                    }
                }
                $data['transaksi_history'][$row]['id_state']     = $dataHistory->status_transaksi;
                $data['transaksi_history'][$row]['state']        = $status->name;
                $data['transaksi_history'][$row]['state_label']  = $status->label;
                $data['transaksi_history'][$row]['id_employee']  = $dataHistory->id_employee;
                $data['transaksi_history'][$row]['employee']     = $employee->name;
                $data['transaksi_history'][$row]['date_history'] = $dataHistory->date_add;
                $data['transaksi_history'][$row]['notes']        = $notes . $dataHistory->notes;
            }
            $data['content']    = $this->load->view(BACKMIN_PATH . '/gudang/pesanan_diproses/detil', $data, true);
            $data['script_css'] = $this->load->view(BACKMIN_PATH . '/gudang/pesanan_diproses/detil_css', '', true);
            $data['script_js']  = $this->load->view(BACKMIN_PATH . '/gudang/pesanan_diproses/detil_js', '', true);
            $this->load->view(BACKMIN_PATH . '/main', $data);
        } else {
            redirect(BACKMIN_PATH . '/gudangpesanan');
        }
    }

    ## TODO : Buat log dan auditor
    public function processPesananTerimaBarang()
    {
        if (!$this->input->is_ajax_request()) {
            redirect(BACKMIN_PATH . '/gudangpesanan/indexPesananDiproses', 'refresh');
        }

        $idOrder    = $this->input->post('id_order');
        try {
            if (in_array($this->adm_level, $this->auditor_area)) {
                $this->session->set_flashdata('error', 'Maaf, anda tidak dapat melakukan proses ini.');
                $callBack   = [   
                    "success"       => "false",
                    "message"       => "Tidak dapat melakukan proses ini.",
                    "redirect"      => "backmin/gudangpesanan/detailPesananDiproses/$idOrder",
                ];
                echo json_encode($callBack);
                exit;
            } else {
                $reference          = $this->input->post('reference');
                $idTransaksi        = $this->input->post('id_transaksi');
                $stsBayar           = $this->input->post('sts_bayar');
                $id_produk          = $this->input->post('id_produk');
                $jumlah             = $this->input->post('jml_produk');
                $harga              = $this->input->post('harga_produk');
                $periode_order      = (int)$this->input->post('periode_order');
                
                $param  = [
                    'path'          => 'bast/',
                    'fieldname'     => 'file_bast',
                    'nama_file'     => $reference,
                ];

                $uploading = $this->myUpload($param);
                if ($uploading['status'] == 0) {
                    $this->session->set_flashdata('error', 'Gagal melakukan upload. ' . $uploading['pesan']);
                    $callBack = [
                        'success'   => 'false',
                        'message'   => $uploading['pesan'],
                        "redirect"  => "backmin/gudangpesanan/detailPesananDiproses/$idOrder",
                    ];
                    echo json_encode($callBack);
                    exit;
                } else {
                    $this->db->trans_begin();

                    // FOR ORDER
                    $current_state = 8;
                    if ($stsBayar == 2) {
                        $current_state = 9;
                    }

                    $dataOrders['current_state']        = $current_state;
                    $dataOrders['tgl_sampai']           = date_format(date_create($this->input->post('tanggal_terima')), 'Y-m-d');
                    $dataOrders['nama_penerima']        = $this->input->post('nama_penerima');
                    $dataOrders['tgl_terima']           = date_format(date_create($this->input->post('tanggal_terima')), 'Y-m-d');
                    $dataOrders['nomor_surat']          = $this->input->post('reference');
                    $dataOrders['tanggal_surat']        = date_format(date_create($this->input->post('tanggal_terima')), 'Y-m-d');
                    $dataOrders['file_bast']            = $uploading['datafile'];
                    
                    $this->mod_gudang->edit('orders', 'id_order = ' . $idOrder, $dataOrders);
                    $this->mod_gudang->addOrderHistory($idOrder, 7);
                    $this->mod_gudang->addOrderHistory($idOrder, 8);

                    // FOR SPK DETAIL
                    $dataDetailSPK['status']            = 4;
                    $dataDetailSPK['modified_date']     = date('Y-m-d H:i:s');
                    $dataDetailSPK['modified_by']       = $this->adm_id;

                    $this->mod_gudang->edit('spk_detail', 'id_transaksi = ' . $idTransaksi, $dataDetailSPK);

                    // FOR ORDER SCM
                    $dataOrderSCM['status']             = 4;
                    $dataOrderSCM['date_modified']      = date('Y-m-d H:i:s');

                    $this->mod_gudang->edit('order_scm', 'id_order = ' . $idOrder, $dataOrderSCM);

                    // FOR TRANSAKSI
                    $dataTransaksi['status_transaksi']  = 6;
                    $dataTransaksi['updated_date']      = date('Y-m-d H:i:s');
                    $dataTransaksi['updated_by']        = $this->adm_id;

                    $this->mod_gudang->edit('transaksi', 'id_transaksi = ' . $idTransaksi, $dataTransaksi);
                    $this->mod_gudang->addTransaksiHistory($idTransaksi, 6);

                    $idSPK  = $this->mod_general->detailData('spk_detail', 'id_transaksi', $idTransaksi)['id_spk'];

                    if ($this->mod_gudang->checkStatusPengiriman($idSPK) == 0) {
                        $statusSPK['status']            = 4;
                        $statusSPK['modified_date']     = date('Y-m-d H:i:s');
                        $statusSPK['modified_by']       = $this->adm_id;
                        $this->mod_gudang->edit('spk', 'id_spk =' . $idSPK, $statusSPK);
                    }

                    if ($this->db->trans_status() === true) {
                        ## ACTION LOG USER
                        $logs['id_transaksi'] = $idTransaksi;
                        $this->logger->logAction('Proses Pesanan Terima Barang', $logs);
                        
                        $this->db->trans_commit();
                        $this->session->set_flashdata('success', 'Kode Pesanan #<b>' . $this->input->post('reference') . '</b> berhasil diterima.');
                        $callBack = [
                            "success"   => "true",
                            "message"   => "Status order telah diubah",
                            "redirect"  => "backmin/gudangpesanan/indexPesananDiproses",
                        ];
                    } else {
                        $this->db->trans_rollback();
                        $this->session->set_flashdata('error', 'Gagal melakukan proses ini.');
                        $callBack = [
                            "success"   => "false",
                            "message"   => "Gagal melakukan proses.",
                            "redirect"  => "backmin/gudangpesanan/detailPesananDiproses/$idOrder",
                        ];
                    }
                    echo json_encode($callBack);
                }
            }
        } catch (Exception $e) {
            $this->session->set_flashdata('error', 'Gagal melakukan proses ini.');
            $callBack = [
                'success' => 'false',
                'message' => 'Caught exception: ' . $e->getMessage(),
                "redirect"  => "backmin/gudangpesanan/detailPesananDiproses/$idOrder",
            ];
            echo json_encode($callBack, true);
        }
    }

    public function cetakPesanan($id)
    {
        if ($id) {
            $data['detail']       = $this->mod_general->detailData('orders', 'id_order', $id);
            $data['customer']     = $this->mod_general->detailData('customer', 'id_customer', $data['detail']['id_customer']);
            $data['listproducts'] = $this->mod_gudang->getListProductStock($id, $this->adm_id_gudang);
            // $data['transaksi'] = $this->mod_gudang->getTransaksi($id, $this->adm_id_gudang);
            $this->load->view(BACKMIN_PATH . '/gudang/cetak_pesanan', $data);
        } else {
            redirect(BACKMIN_PATH . '/gudangpesanan/indexPesananMasuk');
        }
    }
    
    public function cetakExcelPesanan($id)
    {
        try {
            if ($id) {
                $data_order     = $this->mod_gudang->getAll("orders", "*", "id_order = $id")[0];
                $data_category  = $this->mod_gudang->getAll("category", "name, alias", "alias = '" . $data_order->type . "'")[0];
                $data_customer  = $this->mod_gudang->getAll("customer", "*", "id_customer = '" . $data_order->id_customer . "'")[0];
                $data_detail    = $this->mod_gudang->getListProductStock($id, $this->adm_id_gudang);

                $title              = 'DETIL PESANAN';
                $sub_title1         = strtoupper($data_category->name) . '(' . strtoupper($data_category->alias) . ')';
                $sub_title2         = 'Kode Pesanan #' . $data_order->reference;

                if ($data_order->jangka_waktu !== null) {
                    $jangka_waktu   = date('Y-m-d', strtotime($data_order->tgl_konfirmasi . ' + ' . $data_order->jangka_waktu . ' days'));
                } else {
                    $jangka_waktu   = '-';
                }
                
                $this->load->library('excel');
                $worksheet = $this->excel->getActiveSheet();
                $this->excel->setActiveSheetIndex(0);
                $worksheet->setTitle('Detil Pesanan ' . $data_order->reference);

                // $objDrawing = new PHPExcel_Worksheet_Drawing();
                // $objDrawing->setName('Logo Printing');
                // $objDrawing->setDescription('Logo Printing');
                // if ($data_order->type == 'Peminatan SMK') {
                //     $logo_printing  = FCPATH.'/assets/backmin/img/logo-mitra-edukasi-nusantara.jpeg';
                // } else {
                //     $logo_printing  = FCPATH.'/assets/backmin/img/logo-printing.png';
                // }
                // $objDrawing->setPath($logo_printing);
                // $objDrawing->setCoordinates('B1');
                // $objDrawing->setHeight(75);
                // // $objDrawing->setWidth(80);
                // $objDrawing->setWorksheet($this->excel->getActiveSheet());
                // $worksheet->getRowDimension('1')->setRowHeight(40);
                
                $worksheet
                    ->setCellValue('B2', strtoupper($title))
                    ->mergeCells('B2:I2')
                    ->setCellValue('B3', strtoupper($sub_title1))
                    ->mergeCells('B3:I3')
                    ->setCellValue('B4', $sub_title2)
                    ->mergeCells('B4:I4');
                    
                $worksheet
                    ->getStyle('B2:I4')
                    ->getFont()
                    ->setBold(true);

                $worksheet
                    ->getStyle('B2:I4')
                    ->getAlignment()
                    ->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);

                $worksheet
                    ->setCellValue('B6', strtoupper($data_customer->school_name))
                    ->mergeCells('B6:I6')
                    ->setCellValue('B7', $data_customer->alamat)
                    ->mergeCells('B7:I7')
                    ->setCellValue('B8', $data_customer->desa . ', ' . $data_customer->kecamatan . ', ' . $data_customer->kabupaten)
                    ->mergeCells('B8:I8')
                    ->setCellValue('B9', $data_customer->provinsi . ' - ' . $data_customer->kodepos)
                    ->mergeCells('B9:I9')
                    ->setCellValue('B11', 'Tanggal Pesan : ' . $data_order->date_add)
                    ->mergeCells('B11:I11')
                    ->setCellValue('B12', 'Target Kirim : ' . $jangka_waktu)
                    ->mergeCells('B12:I12');

                $worksheet
                    ->getStyle('B6')
                    ->getFont()
                    ->setBold(true);

                $worksheet
                    ->setCellValue('B14', 'No')
                    ->setCellValue('C14', 'Judul Buku')
                    ->setCellValue('D14', 'Kelas')
                    ->setCellValue('E14', 'Total Eks')
                    ->setCellValue('F14', 'Eks/Koli')
                    ->setCellValue('G14', 'Koli Utuh')
                    ->setCellValue('H14', 'Sisa Eks')
                    ->setCellValue('I14', 'Ket.');
                
                $worksheet
                    ->getStyle('B14:I14')
                    ->getFont()
                    ->setBold(true);

                $worksheet
                    ->getStyle('B14:I14')
                    ->getAlignment()
                    ->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
                
                $total_jumlah       = 0;
                $total_berat        = 0;
                $rows               = 15;
                foreach ($data_detail as $row => $val) {
                    $tot_eks        = $val->product_quantity;
                    $eks_koli       = $val->koli ? $val->koli : 0;
                    $koli_utuh      = 0;
                    $eks_ekor       = 0;

                    if($eks_koli !== 0)
                    {
                        $koli_utuh  = floor($tot_eks / $eks_koli);
                        $eks_ekor   = $tot_eks - ($koli_utuh * $eks_koli);
                    }

                    $worksheet
                        ->setCellValue('B' . $rows, ($row + 1))
                        ->setCellValue('C' . $rows, '[' . $val->kode_buku . '] ' . $val->product_name . ' (ISBN: ' . $val->isbn . ')')
                        ->setCellValue('D' . $rows, $val->kelas)
                        ->setCellValue('E' . $rows, rupiah($tot_eks), 0, 2)
                        ->setCellValue('F' . $rows, rupiah($eks_koli), 0, 2)
                        ->setCellValue('G' . $rows, rupiah($koli_utuh), 0, 2)
                        ->setCellValue('H' . $rows, rupiah($eks_ekor), 0, 2)
                        ->setCellValue('I' . $rows, '');

                    $total_jumlah   += $tot_eks;
                    $total_berat    += ($val->weight * $tot_eks);
                    $rows++;
                }
                
                $worksheet
                    ->getStyle('B15:B' . ($rows - 1))
                    ->getAlignment()
                    ->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
                
                $worksheet
                    ->getStyle('D15:H' . ($rows - 1))
                    ->getAlignment()
                    ->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);

                $worksheet
                    ->setCellValue('B' . $rows, 'Jumlah')
                    ->mergeCells('B' . $rows . ':D' . $rows)
                    ->setCellValue('B' . ($rows + 1), 'Berat')
                    ->mergeCells('B' . ($rows + 1) . ':D' . ($rows + 1));

                $worksheet
                    ->setCellValue('E' . $rows, rupiah($total_jumlah, 0, 2))
                    ->setCellValue('E' . ($rows + 1), rupiah($total_berat, 2, 2) . ' Kg');
                
                $worksheet
                    ->getStyle('B' . $rows . ':I' . ($rows + 1))
                    ->getFont()
                    ->setBold(true);
                
                $worksheet
                    ->getStyle('B' . $rows . ':I' . ($rows + 1))
                    ->getAlignment()
                    ->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
                
                $worksheet
                    ->setCellValue('F' . $rows, '')
                    ->mergeCells('F' . $rows . ':I' . $rows)
                    ->setCellValue('F' . ($rows + 1), '')
                    ->mergeCells('F' . ($rows + 1) . ':I' . ($rows + 1));
                        
                $worksheet
                    ->getStyle('B14:I' . ($rows + 1))
                    ->applyFromArray(
                        array(
                            'borders' => array(
                                'allborders' => array(
                                    'style' => PHPExcel_Style_Border::BORDER_THIN
                                )
                            )
                        )
                    );

                foreach(range('B','I') as $columnID) {
                    $worksheet
                        ->getColumnDimension($columnID)
                        ->setAutoSize(true);
                }
                
                $filename = 'Detil Pesanan #' . $data_order->reference . '.xls';
                header('Content-Type: application/vnd.ms-excel');
                header('Content-Disposition: attachment;filename="' . $filename . '"');
                header('Cache-Control: max-age=0'); //no cache
                $objWriter = PHPExcel_IOFactory::createWriter($this->excel, 'Excel5');
                $objWriter->save('php://output');
            } else {
                return false;
            }
        } catch (Exception $e) {
            return $e->getMessage();
        }
    }
    
    public function addReportStockStatus($id_gudang, $id_produk, $jumlah, $lunas)
    {
        $this->db->trans_begin();
        if ($id_gudang && $id_produk) {

            $today  = date('Y-m-d H:i:s');
            $month  = date('n');
            $year   = date('Y');

            $stock_status   = $this->mod_gudang->getLastStockStatus($now = 1, $id_gudang, $id_produk, $month, $year);
            $report         = [];

            if ($stock_status) {
                // In same month and year
                if ($lunas > 0) {
                    $new_stok_fisik         = (int)($stock_status['stok_fisik'] - $jumlah);
                    $new_stok_available     = (int)($stock_status['stok_available'] - $jumlah);
                    $new_total_cost         = $new_stok_fisik * $stock_status['average_cost'];
    
                    $report = [
                        'stok_fisik'        => $new_stok_fisik,
                        'stok_available'    => $new_stok_available,
                        'total_cost'        => $new_total_cost,
                        'updated_date'      => $today
                    ];
                } else {
                    $new_stok_booking       = (int)($stock_status['stok_booking'] + $jumlah);
                    $new_stok_available     = (int)($stock_status['stok_available'] - $jumlah);
                    $new_allocated_cost     = $new_stok_booking * $stock_status['average_cost'];
    
                    $report = [
                        'stok_booking'      => $new_stok_booking,
                        'stok_available'    => $new_stok_available,
                        'allocated_cost'    => $new_allocated_cost,
                        'updated_date'      => $today
                    ];
                }

                $this->mod_gudang->edit("report_stock_status", "id = " . $stock_status['id'], $report);
            } else {
                // In different month and year
                $last_stock_status  = $this->mod_gudang->getLastStockStatus($now = 0, $id_gudang, $id_produk, $month, $year);
			
                $report = [
                    'id_periode'        => $last_stock_status['id_periode'],
                    'id_gudang'         => $id_gudang,
                    'id_produk'         => $id_produk,
                    'bulan'             => $month,
                    'tahun'             => $year
                ];

                if ($last_stock_status) {
                    // Have record below this month
                    if ($lunas > 0) {
                        $new_stok_fisik         = (int)($last_stock_status['stok_fisik'] - $jumlah);
                        $new_stok_available     = (int)($last_stock_status['stok_available'] - $jumlah);
                        $new_total_cost         = $new_stok_fisik * $last_stock_status['average_cost'];
                        
                        $report += [
                            'tgl_transaksi'     => $last_stock_status['tgl_transaksi'],
                            'stok_fisik'        => (int)$new_stok_fisik,
                            'stok_booking'      => (int)$last_stock_status['stok_booking'],
                            'stok_available'    => (int)$new_stok_available,
                            'average_cost'      => $last_stock_status['average_cost'],
                            'total_cost'        => $new_total_cost,
                            'allocated_cost'    => $last_stock_status['allocated_cost'],
                            'created_date'      => $today
                        ];
                    } else {
                        $new_stok_booking       = (int)($last_stock_status['stok_booking'] + $jumlah);
                        $new_stok_available     = (int)($last_stock_status['stok_available'] - $jumlah);
                        $new_allocated_cost     = $new_stok_booking * $last_stock_status['average_cost'];
                        
                        $report += [
                            'tgl_transaksi'     => $last_stock_status['tgl_transaksi'],
                            'stok_fisik'        => (int)$last_stock_status['stok_fisik'],
                            'stok_booking'      => $new_stok_booking,
                            'stok_available'    => $new_stok_available,
                            'average_cost'      => $last_stock_status['average_cost'],
                            'total_cost'        => $last_stock_status['total_cost'],
                            'allocated_cost'    => $new_allocated_cost,
                            'created_date'      => $today
                        ];
                    }

                    $this->mod_gudang->add("report_stock_status", $report);
                } else {
                    // Don't have record below this month
                    $this->db->trans_rollback();
                    return false;
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
    
}
