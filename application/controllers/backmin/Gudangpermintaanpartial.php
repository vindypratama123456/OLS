<?php
defined('BASEPATH') or exit('No direct script access allowed');

/**
 * @property Datatables $datatables
 * @property Mod_general $mod_general
 * @property Mod_gudang $mod_gudang
 * @property Mod_scm $mod_scm
 */
class Gudangpermintaanpartial extends MY_Controller
{
    public function __construct()
    {
        parent::__construct();
        if (!in_array($this->adm_level, $this->backmin_gudang_area)) {
            redirect(BACKMIN_PATH);
        }
        $this->load->model('mod_general');
        $this->load->model('mod_gudang');
        $this->load->model('mod_scm');
    }

    public function index()
    {
        redirect(BACKMIN_PATH . '/gudangpermintaan/indexBarangMasuk');
    }

    public function indexBarangMasuk()
    {
        $data['page_title']     = 'List Barang Masuk';
        $data['content']        = $this->load->view(BACKMIN_PATH . '/gudang/barang_masuk_partial/list', $data, true);
        $data['script_js']      = $this->load->view(BACKMIN_PATH . '/gudang/barang_masuk_partial/list_js', '', true);
        $this->load->view(BACKMIN_PATH . '/main', $data);
    }

    public function listBarangMasuk()
    {
        if (!$this->input->is_ajax_request()) {
            return false;
        }
        $this->load->library('datatables');
        $this->output->set_header('Content-Type:application/json; charset=utf-8');

        $this->datatables->select('
            a.id_transaksi AS id_transaksi, 
            if(isnull(a.id_request), a.`kode_pesanan`, a.`id_request`) as id_request,
            a.total_jumlah AS total_jumlah, 
            b.nama_gudang AS nama_gudang, 
            FORMAT(a.total_jumlah, 0, "de_DE") AS format_jumlah,
            CONCAT("<center>",a.created_date,"</center>") AS date_add,
            CASE a.status_transaksi WHEN 1 THEN CONCAT("<center><span class=\'label label-default\'>Dibuat</span></center>") WHEN 2 THEN CONCAT("<center><span class=\'label label-warning\'>Diproses</span></center>") WHEN 3 THEN CONCAT("<center><span class=\'label label-warning\'>Menunggu TAG</span></center>") WHEN 4 THEN CONCAT("<center><span class=\'label label-warning\'>SPK Dibuat</span></center>") WHEN 5 THEN CONCAT("<center><span class=\'label label-primary\'>Dikirim Ekspedisi</span></center>") WHEN 6 THEN CONCAT("<center><span class=\'label label-success\'>Telah Sampai</span></center>") END AS status_transaksi
        ');
        $this->datatables->from('transaksi a');
        $this->datatables->join('master_gudang b', 'a.asal = b.id_gudang', 'inner');
        $this->datatables->where('a.tujuan', $this->adm_id_gudang);
        $this->datatables->edit_column('id_transaksi', '<center><a href="' . base_url(BACKMIN_PATH . '/gudangpermintaanpartial/detailBarangMasuk/$1') . '">#$1</a></center>', 'id_transaksi');
        $this->datatables->edit_column('total_jumlah', '<center>$1</center>', 'format_jumlah');
        $this->datatables->add_column('detail', '<center><a href="' . base_url(BACKMIN_PATH . '/gudangpermintaanpartial/detailBarangMasuk/$1') . '" class="btn btn-default btn-rounded btn-condensed btn-sm"><span class="fa fa-search"></span></a></center>', 'id_transaksi');

        $this->output->set_output($this->datatables->generate());
    }

    public function detailBarangMasuk($id)
    {
        if ($id && is_numeric($id)) {
            $data['page_title']         = 'Detil Barang Masuk';
            $data['detail']             = $this->mod_general->detailData('transaksi', 'id_transaksi', $id);
            $data['gudang']             = $this->mod_general->detailData('master_gudang', 'id_gudang', $data['detail']['asal']);
            $data['listproducts']       = $this->mod_gudang->getListProducts($id);
            $data['customer']           = [];
            $data['periode']            = [];

            if ($data['detail']['ref_id'] != null) {
                $data['pesanan']        = $this->mod_gudang->getAll('transaksi', 'id_pesanan, kode_pesanan, status_transaksi', 'id_transaksi=' . $data['detail']['ref_id'])[0];
                $orders                 = $this->mod_gudang->getAll('orders', 'id_customer, periode', 'id_order = ' . $data['pesanan']->id_pesanan)[0];
                $data['customer']       = $this->mod_general->detailData('customer', 'id_customer', $orders->id_customer);
                $data['periode']        = $orders->periode;
            } elseif ($data['detail']['id_request'] != null) {
                $data['periode']        = $this->mod_gudang->getAll('request_stock', 'periode', 'id_request = ' . $data['detail']['id_request'])[0]->periode;
            }

            $data['status_transaksi']   = '';
            switch ($data['detail']['status_transaksi']) {
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

            $data['content']        = $this->load->view(BACKMIN_PATH . '/gudang/barang_masuk_partial/detil', $data, true);
            $data['script_js']      = $this->load->view(BACKMIN_PATH . '/gudang/barang_masuk_partial/detil_js', '', true);
            $this->load->view(BACKMIN_PATH . '/main', $data);
        } else {
            redirect(BACKMIN_PATH . '/gudangpermintaanpartial/indexBarangMasuk');
        }
    }

    ## TODO : Buat log dan auditor
    public function prosesBarangMasuk()
    {
        if (!$this->input->is_ajax_request()) {
            redirect(BACKMIN_PATH . '/gudangpermintaanpartial/indexBarangMasuk', 'refresh');
        }

        $idTransaksi    = $this->input->post('id_transaksi');
        if (in_array($this->adm_level, $this->auditor_area)) {
            $this->session->set_flashdata('error', 'Maaf, anda tidak dapat melakukan proses ini.');
            $callBack   = [   
                "success"       => "false",
                "message"       => "Tidak dapat melakukan proses ini.",
                "redirect"      => "backmin/gudangpermintaanpartial/detailBarangMasuk/$idTransaksi",
            ];
        } else {
            $this->db->trans_begin();
            $periodeRequest     = (int) $this->input->post('periode_request');
            $gudangAsal         = $this->input->post('gudang_asal');
            $gudangTujuan       = $this->input->post('gudang_tujuan');
            $refId              = $this->input->post('ref_id');
            $idProduk           = $this->input->post('id_produk');
            $jumlah             = $this->input->post('jumlah');

            $transaksi['status_transaksi']      = 6;
            $transaksi['updated_date']          = date('Y-m-d H:i:s');
            $transaksi['updated_by']            = $this->adm_id;

            $this->mod_gudang->edit('transaksi', 'id_transaksi =' . $idTransaksi, $transaksi);
            $this->mod_gudang->addTransaksiHistory($idTransaksi, 6);

            $idRequest      = $this->mod_general->detailData('transaksi', 'id_transaksi', $idTransaksi)['id_request'];
            if ($idRequest != '') {
                $check_status_transaksi = $this->mod_gudang->checkStatusTransaksi($idRequest);
                if($check_status_transaksi == 0)
                {
                    $query = $this->db->query("SELECT SUM(j.jumlah) AS jumlah, SUM(k.jumlah_request) AS request FROM (SELECT b.`id_produk`, SUM(b.`jumlah`) AS jumlah FROM transaksi a INNER JOIN transaksi_detail b ON a.`id_transaksi`=b.`id_transaksi` WHERE a.`id_request`='". $idRequest ."' GROUP BY id_produk)j INNER JOIN (SELECT yy.`id_produk`, yy.`jumlah` AS jumlah_request FROM `request_stock` xx INNER JOIN `request_stock_detail` yy ON xx.`id_request`=yy.`id_request` WHERE xx.`id_request`='". $idRequest ."')k ON j.id_produk=k.id_produk")->row_array();
                    if($query['jumlah'] == $query['request'])
                    {
                        
                    $request['status']              = 6;
                    $request['updated_date']        = date('Y-m-d H:i:s');
                    $request['updated_by']          = $this->adm_id;

                    $this->mod_gudang->edit('request_stock', 'id_request =' . $idRequest, $request);

                    }
                }
            }
            
            $transaksiSPK['nopol']              = $this->input->post('nopol');
            $transaksiSPK['nama_supir']         = $this->input->post('nama_supir');
            $transaksiSPK['hp_supir']           = $this->input->post('hp_supir');
            $transaksiSPK['status']             = 4;
            $transaksiSPK['modified_date']      = date('Y-m-d H:i:s');
            $transaksiSPK['modified_by']        = $this->adm_id;

            $this->mod_gudang->edit('spk_detail', 'id_transaksi =' . $idTransaksi, $transaksiSPK);

            $idSPK      = $this->mod_general->detailData('spk_detail', 'id_transaksi', $idTransaksi)['id_spk'];
            if ($this->mod_gudang->checkStatusPengiriman($idSPK) == 0) {
                $statusSPK['status']            = 4;
                $statusSPK['modified_date']     = date('Y-m-d H:i:s');
                $statusSPK['modified_by']       = $this->adm_id;
                
                $this->mod_gudang->edit('spk', 'id_spk =' . $idSPK, $statusSPK);
            }

            if ($this->db->trans_status() === true) {
                ## ACTION LOG USER
                $logs['id_transaksi'] = $idTransaksi;
                $this->logger->logAction('Proses Request Barang Masuk', $logs);
                
                $this->db->trans_commit();
                $this->session->set_flashdata('success', 'Status transaksi #<b>' . $idTransaksi . '</b> berhasil diperbarui.');
                $callBack = [
                    "success"   => "true",
                    "message"   => "Status transaksi berhasil diperbarui",
                    "redirect"  => "backmin/gudangpermintaanpartial/indexBarangMasuk",
                ];
            } else {
                $this->db->trans_rollback();
                $this->session->set_flashdata('error', 'Gagal melakukan proses ini.');
                $callBack = [
                    "success"   => "false",
                    "message"   => "Gagal melakukan proses.",
                    "redirect"  => "backmin/gudangpermintaanpartial/detailBarangMasuk/$idTransaksi",
                ];
            }
        }
        echo json_encode($callBack);
    }

    public function indexBarangKeluar()
    {
        $data['page_title']     = 'List Barang Keluar';
        $data['content']        = $this->load->view(BACKMIN_PATH . '/gudang/barang_keluar_partial/list', $data, true);
        $data['script_js']      = $this->load->view(BACKMIN_PATH . '/gudang/barang_keluar_partial/list_js', '', true);
        $this->load->view(BACKMIN_PATH . '/main', $data);
    }

    public function listBarangKeluar()
    {
        if (!$this->input->is_ajax_request()) {
            return false;
        }
        $this->load->library('datatables');
        $this->output->set_header('Content-Type:application/json; charset=utf-8');

        $this->datatables->select('
            a.id_transaksi AS id_transaksi, 
            if(isnull(a.id_request), a.`kode_pesanan`, a.`id_request`) as id_request,
            a.total_jumlah AS total_jumlah, 
            b.nama_gudang AS nama_gudang, 
            FORMAT(a.total_jumlah, 0, "de_DE") AS format_jumlah, 
            CONCAT("<center>",a.created_date,"</center>") AS date_add, 
            CASE a.status_transaksi WHEN 1 THEN CONCAT("<center><span class=\'label label-default\'>Dibuat</span></center>") WHEN 2 THEN CONCAT("<center><span class=\'label label-warning\'>Diproses</span></center>") WHEN 3 THEN CONCAT("<center><span class=\'label label-warning\'>Menunggu </span></center>") WHEN 4 THEN CONCAT("<center><span class=\'label label-warning\'>SPK Dibuat</span></center>") WHEN 5 THEN CONCAT("<center><span class=\'label label-primary\'>Dikirim Ekspedisi</span></center>") WHEN 6 THEN CONCAT("<center><span class=\'label label-success\'>Telah Sampai</span></center>") END AS status_transaksi
        ');
        $this->datatables->from('transaksi a');
        $this->datatables->join('master_gudang b', 'a.tujuan = b.id_gudang', 'inner');
        $this->datatables->where('a.asal', $this->adm_id_gudang);
        $this->datatables->where('a.is_to_school !=', 1);
        $this->datatables->edit_column('id_transaksi', '<center><a href="' . base_url(BACKMIN_PATH . '/gudangpermintaanpartial/detailBarangKeluar/$1') . '">#$1</a></center>', 'id_transaksi');
        $this->datatables->edit_column('total_jumlah', '<center>$1</center>', 'format_jumlah');
        $this->datatables->add_column('detail', '<center><a href="' . base_url(BACKMIN_PATH . '/gudangpermintaanpartial/detailBarangKeluar/$1') . '" class="btn btn-default btn-rounded btn-condensed btn-sm"><span class="fa fa-search"></span></a></center>', 'id_transaksi');

        $this->output->set_output($this->datatables->generate());
    }

    public function detailBarangKeluar($id)
    {
        if ($id && is_numeric($id)) {
            $data['page_title']         = 'Detil Barang Keluar';
            $data['detail']             = $this->mod_general->detailData('transaksi', 'id_transaksi', $id);
            $data['gudang']             = $this->mod_general->detailData('master_gudang', 'id_gudang', $data['detail']['tujuan']);
            $data['listproducts']       = $this->mod_gudang->getListProducts($id);
            $data['status_transaksi']   = '';
            switch ($data['detail']['status_transaksi']) {
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

            $data['content']            = $this->load->view(BACKMIN_PATH . '/gudang/barang_keluar_partial/detil', $data, true);
            $data['script_js']          = $this->load->view(BACKMIN_PATH . '/gudang/barang_keluar_partial/detil_js', '', true);
            $this->load->view(BACKMIN_PATH . '/main', $data);
        } else {
            redirect(BACKMIN_PATH . '/gudangpermintaanpartial/indexBarangKeluar');
        }
    }

    public function detail_barang_keluar_print($id)
    {
        if ($id) {
            $data['detail']             = $this->mod_general->detailData('transaksi', 'id_transaksi', $id);
            $data['gudang']             = $this->mod_general->detailData('master_gudang', 'id_gudang', $data['detail']['tujuan']);
            $data['listproducts']       = $this->mod_gudang->getListProducts($id);
            $data['status_transaksi']   = '';
            switch ($data['detail']['status_transaksi']) {
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

            $this->load->view(BACKMIN_PATH . '/gudang/barang_keluar_partial/cetak_barang_keluar', $data);
        } else {
            redirect(BACKMIN_PATH . '/gudangpermintaanpartial/indexBarangKeluar');
        }
    }

    public function detailBarangKeluarEdit($id, $qty)
    {
        if ( ! $this->input->is_ajax_request()) {
            return false;
        }

        $data['old_qty'] = $qty;
        $data['detil'] = $this->mod_gudang->getListTransaksiDetail($id);
        $this->load->view('backmin/gudang/request_stock_partial/edit_detail', $data);
    }

    public function detailBarangKeluarEditPost()
    {
        if ( ! $this->input->is_ajax_request()) {
            return false;
        }

        $id_transaksi_detail    = $this->input->post('id_transaksi_detail');
        $id_transaksi           = $this->input->post('id_transaksi');
        $id_produk              = $this->input->post('id_produk');
        $jumlah_awal            = $this->input->post('old_qty');
        $jumlah_akhir           = $this->input->post('jumlah');
        $weight                 = $this->input->post('weight');

        // Tambahkan data ke tabel transaksi detail history
        $data = array(
            "id_transaksi_detail"   => $id_transaksi_detail,
            "id_transaksi"          => $id_transaksi,
            "id_produk"             => $id_produk,
            "jumlah_awal"           => $jumlah_awal,
            "jumlah_akhir"          => $jumlah_akhir,
            "created_date"          => date('Y-m-d H:i:s'),
            "created_by"            => $this->adm_id
        );  

        $this->db->trans_begin();
        $this->mod_general->addData("transaksi_detail_history", $data);

        // Ubah data jumlah pada tabel transaksi detail
        $data_edit = array(
            "jumlah"    => $jumlah_akhir,
            "berat"     => $jumlah_akhir * $weight
        );
        $this->mod_general->edit("transaksi_detail", $data_edit, array("id" => $id_transaksi_detail));

        $data_request = $this->mod_gudang->get_data_request($id_transaksi_detail);
        $id_produk  = $data_request['id_produk'];
        $id_gudang  = $data_request['asal'];
        $periode    = $data_request['periode'];

        // Ubah data pada tabel info gudang
        // Jika periode permintaan = periode sekarang
        // stock booking berkurang, stok available bertambah
        // 
        $stok   = $this->mod_scm->getStok($id_gudang, $id_produk, "stok_booking, stok_available");
        
        if ($periode == $this->periode) {
            if($jumlah_awal > $jumlah_akhir)
            {
                $update_stok['stok_booking']     = $stok->stok_booking - ($jumlah_awal-$jumlah_akhir);
                $update_stok['stok_available']   = $stok->stok_available + ($jumlah_awal-$jumlah_akhir);
            }
            else if($jumlah_awal < $jumlah_akhir)
            {
                $update_stok['stok_booking']     = $stok->stok_booking + ($jumlah_akhir-$jumlah_awal);
                $update_stok['stok_available']   = $stok->stok_available - ($jumlah_akhir-$jumlah_awal);
            }
            // $stock_status   = $this->addReportStockStatusTAG($d['id_gudang'], $id_produk, $d['qty']);
            $this->mod_scm->updateStok($id_gudang, $id_produk, $update_stok);
        }

        // Jika jumlah = 0, maka hapus data produk dari detail transaksi
        if($jumlah_akhir == 0)
        {
            $this->mod_general->deleteData("transaksi_detail", "id", $id_transaksi_detail);
        }

        // cek detail transaksi, jika row count = 0
        // maka hapus data transaksi
        $check_data_transaksi = $this->mod_general->getList('transaksi_detail', '*', array('id_transaksi' => $data_request['id_transaksi']));

        if($check_data_transaksi == 0)
        {
            // hapus data transaksi
            $this->mod_general->deleteData("transaksi", "id_transaksi", $data_request['id_transaksi']);
            // hapus data transaksi history
            $this->mod_general->deleteData("transaksi_history", "id_transaksi", $data_request['id_transaksi']);
            // hapus data transaksi detail history
            $this->mod_general->deleteData("transaksi_detail_history", "id_transaksi", $data_request['id_transaksi']);
        }
        

        // Ubah data pada tabel transaksi ( total berat , total jumlah )
        $query = $this->db->query("SELECT SUM(jumlah) AS total_jumlah, SUM(berat) AS total_berat FROM transaksi_detail WHERE id_transaksi = '".$id_transaksi."'")->row_array();

        $data_transaksi_edit = array(
            "total_berat" => $query["total_berat"],
            "total_jumlah"  => $query["total_jumlah"]
        );        
        $this->mod_general->edit("transaksi", $data_transaksi_edit, array("id_transaksi" => $id_transaksi));

        $data = $this->mod_general->detailData('transaksi', 'id_transaksi', $id_transaksi);

        $request_stok = array(
            'status' => '1'
        );
        $this->mod_general->edit("request_stock", $request_stok, array("id_request" => $data['id_request']));

        if($this->db->trans_status() === TRUE)
        {
            $this->db->trans_commit();
            $callBack = [
                'success' => 'true',
                'message' => 'Berhasil mengubah jumlah buku',
                'id_transaksi' => $id_transaksi
            ];
            $this->session->set_flashdata('msg_success', 'Berhasil mengubah jumlah buku');
        }
        else
        {
            $this->db->trans_rollback();
            $callBack = [
                'success' => 'false',
                'message' => 'Gagal mengubah jumlah buku.'
            ];
            $this->session->set_flashdata('msg_error', 'Gagal mengubah jumlah buku');
        }

        echo json_encode($callBack);
    }

    ## TODO : Buat log dan auditor
    public function prosesBarangKeluar()
    {
        if (!$this->input->is_ajax_request()) {
            redirect(BACKMIN_PATH . '/gudangpermintaanpartial/indexBarangKeluar', 'refresh');
        }

        $idTransaksi    = $this->input->post('id_transaksi');
        if (in_array($this->adm_level, $this->auditor_area)) {
            $this->session->set_flashdata('error', 'Maaf, anda tidak dapat melakukan proses ini.');
            $callBack   = [   
                "success"       => "false",
                "message"       => "Tidak dapat melakukan proses ini.",
                "redirect"      => "backmin/gudangpermintaanpartial/detailBarangKeluar/$idTransaksi",
            ];
        } else {
            $this->db->trans_begin();
            $idRequest      = $this->input->post('id_request');
            $gudangAsal     = $this->input->post('gudang_asal');

            $transaksi['status_transaksi']  = 2;
            $transaksi['updated_date']      = date('Y-m-d H:i:s');
            $transaksi['updated_by']        = $this->adm_id;

            $this->mod_gudang->edit('transaksi', 'id_transaksi =' . $idTransaksi, $transaksi);
            $this->mod_gudang->addTransaksiHistory($idTransaksi, 2);

            if ($this->db->trans_status() === true) {
                ## ACTION LOG USER
                $logs['id_transaksi'] = $idTransaksi;
                $this->logger->logAction('Proses Request Barang Keluar', $logs);
                
                $this->db->trans_commit();
                $this->session->set_flashdata('success', 'Status transaksi #<b>' . $idTransaksi . '</b> berhasil diperbarui.');
                $callBack = [
                    "success"   => "true",
                    "message"   => "Status transaksi berhasil diperbarui",
                    "redirect"  => "backmin/gudangpermintaanpartial/indexBarangKeluar",
                ];
            } else {
                $this->db->trans_rollback();
                $this->session->set_flashdata('error', 'Gagal melakukan proses ini.');
                $callBack = [
                    "success"   => "false",
                    "message"   => "Gagal melakukan proses",
                    "redirect"  => "backmin/gudangpermintaanpartial/detailBarangKeluar/$idTransaksi",
                ];
            }
        }
        echo json_encode($callBack);
    }
    
}
