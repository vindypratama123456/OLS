<?php
defined('BASEPATH') or exit('No direct script access allowed');

require_once APPPATH . 'third_party/PhpExportExcel.php';
require_once APPPATH . 'third_party/xlsxwriter.class.php';

/**
 * @property Datatables $datatables
 * @property Mod_general $mod_general
 * @property Mod_adjusment $mod_adjusment
 */
class Scmadjusment extends MY_Controller
{
    public function __construct()
    {
        parent::__construct();
        if ( ! in_array($this->adm_level, $this->backmin_scm_area)) {
            redirect(BACKMIN_PATH);
        }
        $this->load->model('mod_general');
        $this->load->model('mod_adjusment');
    }

    public function index()
    {
        redirect(BACKMIN_PATH . '/scmadjusment/index_adjusment');
    }

    public function index_adjusment()
    {
        $data['page_title'] = 'Pesanan Sekolah - Masuk';
        $data['content']    = $this->load->view(BACKMIN_PATH . '/scm/adjusment/list', $data, true);
        $data['script_js']  = $this->load->view(BACKMIN_PATH . '/scm/adjusment/list_js', '', true);
        $this->load->view(BACKMIN_PATH . '/main', $data);
    }

    public function list_data_adjusment()
    {
        if (!$this->input->is_ajax_request()) {
            return false;
        }
        $this->load->library('datatables');
        $this->output->set_header('Content-Type:application/json; charset=utf-8');
        $this->datatables->select('a.id_transaksi as id_transaksi,a.catatan AS catatan, a.total_jumlah AS total_jumlah, b.nama_gudang as gudang, IF(a.status_transaksi=1,"Dibuat", "Selesai") AS status_transaksi, a.created_date AS tanggal');
        $this->datatables->from('transaksi a');
        $this->datatables->join('master_gudang b', 'a.asal=b.id_gudang', 'inner');
        $this->datatables->where('a.id_tipe', '3');
        $this->datatables->where('a.status_transaksi', '1');
        $this->datatables->edit_column('id_transaksi', '<a href="' . base_url(BACKMIN_PATH . '/scmadjusment/detail_adjusment/$1') . '">$1</a>', 'id_transaksi');
        $this->output->set_output($this->datatables->generate());
    }

    public function detail_adjusment($id_transaksi)
    {
        if ($id_transaksi && is_numeric($id_transaksi)) {
            $data['adjusment'] = $this->mod_adjusment->getAll('transaksi', '*', array("id_transaksi" => $id_transaksi));
            $data['gudang'] = $this->mod_adjusment->getAll('master_gudang', '*', array("id_gudang" => $data['adjusment'][0]->asal));
            
            $id_transaksi = $data['adjusment'][0]->id_transaksi;
            $data['adjusment_detail'] = $this->mod_adjusment->get_detail_adjusment($id_transaksi);

            $data['gudang'] = $this->mod_adjusment->getAll('master_gudang', '*', array("id_gudang" => $data['adjusment'][0]->asal));

            $data['content']        = $this->load->view(BACKMIN_PATH . '/scm/adjusment/detil', $data, true);
            $data['script_js']      = $this->load->view(BACKMIN_PATH . '/scm/adjusment/detil_js', '', true);
            $this->load->view(BACKMIN_PATH . '/main', $data);
        }
        else
        {
            redirect(BACKMIN_PATH . '/gudangadjusment');
        }
    }

    public function detail_adjusment_post()
    {

        $this->db->trans_begin();
        $id_transaksi = $this->input->post('id_transaksi');
        // print_r($id_transaksi);

        $transaksi = $this->mod_adjusment->getAll("transaksi", "*", array("id_transaksi" => $id_transaksi));
        $transaksi_detail = $this->mod_adjusment->get_detail_adjusment($id_transaksi);
        // print_r($transaksi_detail);



        $id_gudang  = $transaksi[0]->asal;
        $periode    = date("Y", strtotime($transaksi[0]->created_date));
        // UPDATE STOK PADA TABEL info_gudang
        foreach($transaksi_detail as $d)
        {
            $id_produk = $d->id_produk;
            $where = array(
                "id_produk"     => $id_produk,
                "id_gudang"     => $id_gudang,
                "periode"       => $periode
            );

            $info_gudang = $this->mod_adjusment->getAll("info_gudang", "*", $where);
            $stok_fisik_update      = $info_gudang[0]->stok_fisik + $d->jumlah;
            $stok_available_update  = $info_gudang[0]->stok_available + $d->jumlah;

            $update_info_gudang = array(
                'stok_fisik'        => (int) $stok_fisik_update,
                'stok_available'    => (int) $stok_available_update
            );

            $this->mod_adjusment->edit("info_gudang", $where, $update_info_gudang);

            $transaksi_detail_history = array();
            $transaksi_detail_history['id_transaksi_detail']    = $d->id;
            $transaksi_detail_history['id_transaksi']           = $id_transaksi;
            $transaksi_detail_history['id_produk']              = $id_produk;
            $transaksi_detail_history['jumlah_awal']            = $info_gudang[0]->stok_fisik;
            $transaksi_detail_history['jumlah_akhir']           = $stok_fisik_update;
            $transaksi_detail_history['created_date']           = date('Y-m-d');
            $transaksi_detail_history['created_by']             = $this->adm_id;
            $this->mod_adjusment->add('transaksi_detail_history', $transaksi_detail_history);
        }

        // TRANSAKSI HISTORY
        $transaksi_history = array();
        $transaksi_history["id_employee"] = $this->adm_id;
        $transaksi_history["id_transaksi"] = $id_transaksi;
        $transaksi_history["status_transaksi"] = "6";
        $transaksi_history["date_add"] = date('Y-m-d');
        $this->mod_adjusment->add('transaksi_history', $transaksi_history);

        // UPDATE STATUS TRANSAKSI = 6
        if ($this->db->trans_status() === TRUE)
        {
            $this->mod_adjusment->edit("transaksi", array("id_transaksi" => $id_transaksi), array("status_transaksi" => "6"));
            $this->db->trans_commit();
            $this->session->set_flashdata('success', 'Data adjusment telah berhasil ditambahkan.');
            redirect('backmin/scmadjusment');
        }
        else
        {
            $this->db->trans_rollback();
            $this->session->set_flashdata('error', 'Data adjusment gagal ditambahkan.');
            redirect('backmin/scmadjusment');
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
        $this->load->view(BACKMIN_PATH . '/scm/adjusment/edit_detail', $data);
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
        $this->load->view(BACKMIN_PATH . '/scm/adjusment/add_book', $data);
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
                redirect('backmin/scmadjusment');
        }
        else
        {
                $this->db->trans_rollback();
                $this->session->set_flashdata('error', 'Data adjusment gagal ditambahkan.');
                redirect('backmin/scmadjusment');
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
        $data['content']    = $this->load->view(BACKMIN_PATH . '/scm/adjusment/list_diproses', $data, true);
        $data['script_js']  = $this->load->view(BACKMIN_PATH . '/scm/adjusment/list_diproses_js', '', true);
        $this->load->view(BACKMIN_PATH . '/main', $data);
    }

    public function list_data_adjusment_diproses()
    {
        if (!$this->input->is_ajax_request()) {
            return false;
        }
        $this->load->library('datatables');
        $this->output->set_header('Content-Type:application/json; charset=utf-8');
        $this->datatables->select('a.id_transaksi as id_transaksi,a.catatan AS catatan, a.total_jumlah AS total_jumlah, b.nama_gudang as gudang, IF(a.status_transaksi=1,"Dibuat", "Selesai") AS status_transaksi, a.created_date AS tanggal');
        $this->datatables->from('transaksi a');
        $this->datatables->join('master_gudang b', 'a.asal=b.id_gudang', 'inner');
        $this->datatables->where('a.id_tipe', '3');
        $this->datatables->where('a.status_transaksi > ', '1');
        $this->datatables->edit_column('id_transaksi', '<a href="' . base_url(BACKMIN_PATH . '/scmadjusment/detail_adjusment_diproses/$1') . '">$1</a>', 'id_transaksi');
        $this->output->set_output($this->datatables->generate());
    }

    public function detail_adjusment_diproses($id_transaksi)
    {
        if ($id_transaksi && is_numeric($id_transaksi)) {
            $data['adjusment'] = $this->mod_adjusment->getAll('transaksi', '*', array("id_transaksi" => $id_transaksi));
            $data['gudang'] = $this->mod_adjusment->getAll('master_gudang', '*', array("id_gudang" => $data['adjusment'][0]->asal));
            
            $id_transaksi = $data['adjusment'][0]->id_transaksi;
            $data['adjusment_detail'] = $this->mod_adjusment->get_detail_adjusment($id_transaksi);

            $data['gudang'] = $this->mod_adjusment->getAll('master_gudang', '*', array("id_gudang" => $data['adjusment'][0]->asal));

            $data['content']        = $this->load->view(BACKMIN_PATH . '/scm/adjusment/detil_diproses', $data, true);
            $data['script_js']      = $this->load->view(BACKMIN_PATH . '/scm/adjusment/detil_diproses_js', '', true);
            $this->load->view(BACKMIN_PATH . '/main', $data);
        }
        else
        {
            redirect(BACKMIN_PATH . '/gudangadjusment');
        }
    }

    public function index_adjusment_report()
    {
        $data['listgudang']     = $this->mod_adjusment->getAll('master_gudang', '*', 'status = 1', 'nama_gudang ASC');
        $data['page_title']     = 'Laporan Rekapitulasi Permintaan Stock Gudang';
        $data['content']        = $this->load->view(BACKMIN_PATH . '/scm/adjusment/list_rekap_requeststock', $data, true);
        $data['script_js']      = $this->load->view(BACKMIN_PATH . '/scm/adjusment/list_rekap_requeststock_js', '', true);
        $data['script_css']     = $this->load->view(BACKMIN_PATH . '/scm/adjusment/list_rekap_requeststock_css', '', true);

        $this->load->view(BACKMIN_PATH . '/main', $data);
    }

    public function cetak_rekapitulasi_adjusment()
    {

                // $start_date         = str_replace('/', '-', $this->input->post('start_date'));
                // $end_date           = str_replace('/', '-', $this->input->post('end_date'));

                // echo $start_date . " - " . $end_date;
        if ( ! $this->input->is_ajax_request()) {
            return false;
        } else {
            try {
                $id_gudang          = $this->input->post('id_gudang');
                $start_date         = str_replace('/', '-', $this->input->post('start_date'));
                $end_date           = str_replace('/', '-', $this->input->post('end_date'));
                $daytime            = strtotime(date('Y-m-d H:i:s'));

                $start_time         = strtotime($start_date);
                $end_time           = strtotime($end_date);
                
                $diff_month         = 1 + (date("Y", $end_time) - date("Y", $start_time)) * 12;
                $diff_month         += date("m", $end_time) - date("m", $start_time);

                if ($diff_month > 3) {
                    return false;
                } else {
                    $folder         = "uploads".DIRECTORY_SEPARATOR."scm".DIRECTORY_SEPARATOR."rekapitulasi".DIRECTORY_SEPARATOR;
                    $filename       = 'laporan_rekap_adjusment_' . $start_date . '_' . $end_date . '_' . $daytime . '.xlsx';
                    $path_fle       = $folder . $filename;

                    $startDate      = $start_date . ' 00:00:00';
                    $finishDate     = $end_date . ' 23:59:59';

                    $where = '';
                    if ($id_gudang)
                        $where = $id_gudang;

                    $list_request_stock = $this->mod_adjusment->getRekapitulasiAdjusment($startDate, $finishDate, $where, $order_by = "");
                    $header = [
                        'ID Transaksi'        => 'string',
                        'Tgl Transaksi'       => 'datetime',
                        'Kode Buku'        => 'string',
                        'Judul Buku'        => 'string',
                        'Kategori'          => 'string',
                        'Kelas'             => 'string',
                        'QTY'               => 'integer',
                        'HPP'               => 'integer',
                        'Total HPP'         => 'integer',
                        'Nama Gudang'       => 'string',
                        'Keterangan'       => 'string',
                        'Status'            => 'string',
                        'Tgl Status'        => 'datetime',
                        'Tahap'             => 'string'
                    ];
                    $writer = new XLSXWriter();
                    $writer->writeSheetHeader('Sheet1', $header);
                    foreach ($list_request_stock as $row) {
                        $value = [
                            $row['id_transaksi'],
                            $row['tgl_transaksi'],
                            $row['kode_buku'],
                            $row['judul_buku'],
                            $row['kategori'],
                            $row['kelas'],
                            $row['quantity'],
                            $row['hpp'],
                            $row['hpp_total'],
                            $row['nama_gudang'],
                            $row['catatan'],
                            $row['status_transaksi'],
                            $row['tgl_status'],
                            ''
                        ];
                        $writer->writeSheetRow('Sheet1', $value);
                    }
                    
                    $writer->writeToFile(FCPATH . $path_fle);
                    chmod($path_fle, 0777);
                    // force_download($path_fle, null);
                    $response = [
                        'success'   => 'true',
                        'pathfile'  => $path_fle
                    ];

                    echo json_encode($response);    
                }
            } catch (Exception $e) {
                $callBack = [
                    'success' => 'false',
                    'message' => 'Caught exception: ' . $e->getMessage()
                ];
                echo json_encode($callBack, true);
            }
        }
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
