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
        $this->datatables->select('a.id_transaksi as id_transaksi,a.catatan AS catatan, a.total_jumlah AS total_jumlah, IF(a.status_transaksi=1,"Dibuat", "Selesai") AS status_transaksi, a.created_date AS tanggal');
        $this->datatables->from('transaksi a');
        $this->datatables->join('master_gudang b', 'a.asal=b.id_gudang', 'inner');
        $this->datatables->where('a.id_tipe', '3');
        $this->datatables->where('a.status_transaksi', '1');
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
        // $tanggal            = $this->input->post("tanggal");
        $tanggal            = date('Y-m-d H:i:s');

        $transaksi = array();
        $transaksi['catatan'] = $catatan;
        $transaksi['created_date'] = date('Y-m-d H:i:s', strtotime($tanggal));
        $transaksi['created_by'] = $this->adm_id;

        $transaksi['id_tipe'] = $id_tipe;
        $transaksi['asal'] = $asal;
        $transaksi['have_tag'] = $have_tag;
        $transaksi['is_to_school'] = $is_to_school;
        $transaksi['is_forward'] = $is_forward;
        $transaksi['status_transaksi'] = $status_transaksi;

        $id_transaksi = $this->mod_adjusment->add('transaksi', $transaksi);

        // TRANSAKSI HISTORY
        $transaksi_history = array();
        $transaksi_history["id_employee"] = $this->adm_id;
        $transaksi_history["id_transaksi"] = $id_transaksi;
        $transaksi_history["status_transaksi"] = "1";
        $transaksi_history["date_add"] = date('Y-m-d');
        $this->mod_adjusment->add('transaksi_history', $transaksi_history);
        

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
                $this->session->set_flashdata('error', 'Data adjusment gagal ditambahkan.');
                redirect('backmin/gudangadjusment');
        }
    }

    public function edit($id, $qty)
    {
        if ( ! $this->input->is_ajax_request()) {
            return false;
        }

        $data['old_qty'] = $qty;
        $data['detil'] = $this->mod_general->detailData('transaksi_detail', 'id', $id);
        $data['product'] = $this->mod_general->detailData('product', 'id_product', $data['detil']['id_produk']);
        $this->load->view(BACKMIN_PATH . '/gudang/adjusment/edit_detail', $data);
    }

    public function edit_post()
    {
         if ( ! $this->input->is_ajax_request()) {
            redirect(ADMIN_PATH.'/orders');
        }

        try {
            if (in_array($this->adm_level, $this->auditor_area)) {
                $callBack = [
                    "success" => "false",
                    "message" => "Maaf, anda tidak dapat melakukan proses ini.",
                ];
            } else {
                $id = $this->input->post('id');
                $id_transaksi = $this->input->post('id_transaksi');
                $id_produk = $this->input->post('product_id');
                $productName = $this->input->post('product_name');
                $productQuantity = $this->input->post('product_quantity');
                $oldQty = $this->input->post('old_qty');

                // jika quantity tidak berubah
                if ($productQuantity == $oldQty) {
                    $callBack = [
                        'success' => 'true',
                        'message' => 'Data successfully updated.',
                        'id_transaksi' => $id_transaksi,
                    ];
                    echo json_encode($callBack, true);
                    exit();
                }

                // insert ke tabel order_detail_revisi
                $data1 = [
                    'id_transaksi_detail' => $id,
                    'id_transaksi' => $id_transaksi,
                    'id_produk' => $id_produk,
                    'jumlah_awal' => $oldQty,
                    'jumlah_akhir' => $productQuantity,
                    'created_date' => date('Y-m-d H:i:s'),
                    'created_by' => $this->adm_id,
                ];
                $proc1 = $this->mod_general->addData('transaksi_detail_history', $data1);
                if ($proc1) {
                    if ($productQuantity == 0) {
                        $proc2 = $this->mod_general->deleteData('transaksi_detail', 'id', $id);
                    } else {
                        // update tabel order_detail
                        $data2 = [
                            'jumlah' => $productQuantity
                        ];
                        $proc2 = $this->mod_general->updateData('transaksi_detail', $data2, 'id',
                            $id);
                    }

                    $data_transaksi_detail = $this->mod_general->getAll("transaksi_detail", "*", array('id_transaksi' => $id_transaksi));
                    $total_jumlah = 0;
                    $total_berat = 0;
                    $total_harga = 0;

                    foreach($data_transaksi_detail as $d)
                    {
                        $total_jumlah   += $d->jumlah;
                        $total_berat    += ($d->jumlah * $d->berat);
                        $total_harga    += ($d->jumlah * $d->harga);
                    }

                    $update_transaksi = array();
                    $update_transaksi['total_jumlah']   = $total_jumlah;
                    $update_transaksi['total_berat']    = $total_berat;
                    $update_transaksi['total_harga']    = $total_harga;

                    $this->mod_adjusment->edit('transaksi',array('id_transaksi' => $id_transaksi), $update_transaksi);
                    
                    if ($proc2) {
                        $callBack = [
                            'success' => 'true',
                            'message' => 'Data successfully updated.',
                            'id_transaksi' => $id_transaksi,
                        ];
                        $this->session->set_flashdata('msg_success',
                            'Data detil adjusment berhasil <b>DIPERBARUI</b></p>');
                    } else {
                        $callBack = [
                            'success' => 'false',
                            'message' => 'Failed to update transaksi_detail',
                        ];
                    }
                } else {
                    $callBack = [
                        'success' => 'false',
                        'message' => 'Failed to insert data.',
                    ];
                }
            }
            echo json_encode($callBack, true);
        } catch (Exception $e) {
            $callBack = [
                'success' => 'false',
                'message' => 'Caught exception: '.$e->getMessage(),
            ];
            echo json_encode($callBack, true);
        }
    }

    public function add_books($id_transaksi)
    {
        $data['id_transaksi'] = $id_transaksi;
        $this->load->view(BACKMIN_PATH . '/gudang/adjusment/add_book', $data);
    }

    public function add_books_post()
    {
        $this->db->trans_begin();

        $id_transaksi = $this->input->post("id_transaksi");

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

                $id_transaksi_detail = $this->mod_adjusment->add('transaksi_detail', $transaksi_detail);
                
                $transaksi_detail_history['id_transaksi_detail']    = $id_transaksi_detail;
                $transaksi_detail_history['id_transaksi']           = $id_transaksi;
                $transaksi_detail_history['id_produk']              = $d;
                $transaksi_detail_history['jumlah_awal']            = 0;
                $transaksi_detail_history['jumlah_akhir']           = $qty[$count];
                $transaksi_detail_history['created_date']           = date('Y-m-d H:i:S');
                $transaksi_detail_history['created_by']             = $this->adm_id;


            }
            $count++;
        }

        $data_transaksi_detail = $this->mod_general->getAll("transaksi_detail", "*", array('id_transaksi' => $id_transaksi));
        $total_jumlah = 0;
        $total_berat = 0;
        $total_harga = 0;

        foreach($data_transaksi_detail as $d)
        {
            $total_jumlah   += $d->jumlah;
            $total_berat    += ($d->jumlah * $d->berat);
            $total_harga    += ($d->jumlah * $d->harga);
        }

        $update_transaksi = array();
        $update_transaksi['total_jumlah']   = $total_jumlah;
        $update_transaksi['total_berat']    = $total_berat;
        $update_transaksi['total_harga']    = $total_harga;

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
                $this->session->set_flashdata('error', 'Data adjusment gagal ditambahkan.');
                redirect('backmin/gudangadjusment');
        }
    }

    public function get_data_product()
    {
        $kode_buku = $this->input->post("kode_buku");
        // $query['buku'] = $this->mod_general->getAll('product', '*', array("kode_buku" => $kode_buku));
        $query['buku'] = $this->mod_adjusment->get_data_product(array("kode_buku" => $kode_buku));

        $id_gudang = $this->adm_id_gudang;
        if(!empty($id_gudang) or $id_gudang != "")
        {
            $query['gudang'] = $this->mod_general->getAll('master_gudang', '*', array("id_gudang" => $id_gudang));
        }
        else 
        {
            $query['gudang'] = "";
        }
        echo json_encode($query);
    }

    public function index_adjusment_diproses()
    {
        $data['page_title'] = 'Pesanan Sekolah - Masuk';
        $data['content']    = $this->load->view(BACKMIN_PATH . '/gudang/adjusment/list_diproses', $data, true);
        $data['script_js']  = $this->load->view(BACKMIN_PATH . '/gudang/adjusment/list_diproses_js', '', true);
        $this->load->view(BACKMIN_PATH . '/main', $data);
    }

    public function list_data_adjusment_diproses()
    {
        if (!$this->input->is_ajax_request()) {
            return false;
        }
        $this->load->library('datatables');
        $this->output->set_header('Content-Type:application/json; charset=utf-8');
        $this->datatables->select('a.id_transaksi as id_transaksi,a.catatan AS catatan, a.total_jumlah AS total_jumlah, IF(a.status_transaksi=1,"Dibuat", "Selesai") AS status_transaksi, a.created_date AS tanggal');
        $this->datatables->from('transaksi a');
        $this->datatables->join('master_gudang b', 'a.asal=b.id_gudang', 'inner');
        $this->datatables->where('a.id_tipe', '3');
        $this->datatables->where('a.status_transaksi > ', '1');
        $this->datatables->where('a.asal', $this->adm_id_gudang);
        $this->datatables->edit_column('id_transaksi', '<a href="' . base_url(BACKMIN_PATH . '/gudangadjusment/detail_adjusment/$1') . '">$1</a>', 'id_transaksi');
        $this->output->set_output($this->datatables->generate());
    }

    public function get_stock()
    {
        $id_produk = $this->input->post("id_buku");
        $where = array(
            "id_produk"     => $id_produk,
            "id_gudang"     => $this->adm_id_gudang,
            "periode"       => $this->periode
        );

        $info_gudang = $this->mod_adjusment->getAll("info_gudang", "*", $where);
        echo json_encode($info_gudang);
    }
    
}
