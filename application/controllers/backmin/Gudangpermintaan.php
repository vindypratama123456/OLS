<?php
defined('BASEPATH') or exit('No direct script access allowed');

/**
 * @property Datatables $datatables
 * @property Mod_general $mod_general
 * @property Mod_gudang $mod_gudang
 */
class Gudangpermintaan extends MY_Controller
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
        redirect(BACKMIN_PATH . '/gudangpermintaan/indexBarangMasuk');
    }

    public function indexBarangMasuk()
    {
        $data['page_title']     = 'List Barang Masuk';
        $data['content']        = $this->load->view(BACKMIN_PATH . '/gudang/barang_masuk/list', $data, true);
        $data['script_js']      = $this->load->view(BACKMIN_PATH . '/gudang/barang_masuk/list_js', '', true);
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
            a.total_jumlah AS total_jumlah, 
            b.nama_gudang AS nama_gudang, 
            FORMAT(a.total_jumlah, 0, "de_DE") AS format_jumlah,
            CONCAT("<center>",a.created_date,"</center>") AS date_add,
            CASE a.status_transaksi WHEN 1 THEN CONCAT("<center><span class=\'label label-default\'>Dibuat</span></center>") WHEN 2 THEN CONCAT("<center><span class=\'label label-warning\'>Diproses</span></center>") WHEN 3 THEN CONCAT("<center><span class=\'label label-warning\'>Menunggu TAG</span></center>") WHEN 4 THEN CONCAT("<center><span class=\'label label-warning\'>SPK Dibuat</span></center>") WHEN 5 THEN CONCAT("<center><span class=\'label label-primary\'>Dikirim Ekspedisi</span></center>") WHEN 6 THEN CONCAT("<center><span class=\'label label-success\'>Telah Sampai</span></center>") END AS status_transaksi
        ');
        $this->datatables->from('transaksi a');
        $this->datatables->join('master_gudang b', 'a.asal = b.id_gudang', 'inner');
        $this->datatables->where('a.tujuan', $this->adm_id_gudang);
        $this->datatables->edit_column('id_transaksi', '<center><a href="' . base_url(BACKMIN_PATH . '/gudangpermintaan/detailBarangMasuk/$1') . '">#$1</a></center>', 'id_transaksi');
        $this->datatables->edit_column('total_jumlah', '<center>$1</center>', 'format_jumlah');
        $this->datatables->add_column('detail', '<center><a href="' . base_url(BACKMIN_PATH . '/gudangpermintaan/detailBarangMasuk/$1') . '" class="btn btn-default btn-rounded btn-condensed btn-sm"><span class="fa fa-search"></span></a></center>', 'id_transaksi');

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

            $data['content']        = $this->load->view(BACKMIN_PATH . '/gudang/barang_masuk/detil', $data, true);
            $data['script_js']      = $this->load->view(BACKMIN_PATH . '/gudang/barang_masuk/detil_js', '', true);
            $this->load->view(BACKMIN_PATH . '/main', $data);
        } else {
            redirect(BACKMIN_PATH . '/gudangpermintaan/indexBarangMasuk');
        }
    }

    ## TODO : Buat log dan auditor
    public function prosesBarangMasuk()
    {
        if (!$this->input->is_ajax_request()) {
            redirect(BACKMIN_PATH . '/gudangpermintaan/indexBarangMasuk', 'refresh');
        }

        $idTransaksi    = $this->input->post('id_transaksi');
        if (in_array($this->adm_level, $this->auditor_area)) {
            $this->session->set_flashdata('error', 'Maaf, anda tidak dapat melakukan proses ini.');
            $callBack   = [   
                "success"       => "false",
                "message"       => "Tidak dapat melakukan proses ini.",
                "redirect"      => "backmin/gudangpermintaan/detailBarangMasuk/$idTransaksi",
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
                $request['status']              = 6;
                $request['updated_date']        = date('Y-m-d H:i:s');
                $request['updated_by']          = $this->adm_id;

                $this->mod_gudang->edit('request_stock', 'id_request =' . $idRequest, $request);
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
                    "redirect"  => "backmin/gudangpermintaan/indexBarangMasuk",
                ];
            } else {
                $this->db->trans_rollback();
                $this->session->set_flashdata('error', 'Gagal melakukan proses ini.');
                $callBack = [
                    "success"   => "false",
                    "message"   => "Gagal melakukan proses.",
                    "redirect"  => "backmin/gudangpermintaan/detailBarangMasuk/$idTransaksi",
                ];
            }
        }
        echo json_encode($callBack);
    }

    public function indexBarangKeluar()
    {
        $data['page_title']     = 'List Barang Keluar';
        $data['content']        = $this->load->view(BACKMIN_PATH . '/gudang/barang_keluar/list', $data, true);
        $data['script_js']      = $this->load->view(BACKMIN_PATH . '/gudang/barang_keluar/list_js', '', true);
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
        $this->datatables->edit_column('id_transaksi', '<center><a href="' . base_url(BACKMIN_PATH . '/gudangpermintaan/detailBarangKeluar/$1') . '">#$1</a></center>', 'id_transaksi');
        $this->datatables->edit_column('total_jumlah', '<center>$1</center>', 'format_jumlah');
        $this->datatables->add_column('detail', '<center><a href="' . base_url(BACKMIN_PATH . '/gudangpermintaan/detailBarangKeluar/$1') . '" class="btn btn-default btn-rounded btn-condensed btn-sm"><span class="fa fa-search"></span></a></center>', 'id_transaksi');

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

            $data['content']            = $this->load->view(BACKMIN_PATH . '/gudang/barang_keluar/detil', $data, true);
            $data['script_js']          = $this->load->view(BACKMIN_PATH . '/gudang/barang_keluar/detil_js', '', true);
            $this->load->view(BACKMIN_PATH . '/main', $data);
        } else {
            redirect(BACKMIN_PATH . '/gudangpermintaan/indexBarangKeluar');
        }
    }

    ## TODO : Buat log dan auditor
    public function prosesBarangKeluar()
    {
        if (!$this->input->is_ajax_request()) {
            redirect(BACKMIN_PATH . '/gudangpermintaan/indexBarangKeluar', 'refresh');
        }

        $idTransaksi    = $this->input->post('id_transaksi');
        if (in_array($this->adm_level, $this->auditor_area)) {
            $this->session->set_flashdata('error', 'Maaf, anda tidak dapat melakukan proses ini.');
            $callBack   = [   
                "success"       => "false",
                "message"       => "Tidak dapat melakukan proses ini.",
                "redirect"      => "backmin/gudangpermintaan/detailBarangKeluar/$idTransaksi",
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
                    "redirect"  => "backmin/gudangpermintaan/indexBarangKeluar",
                ];
            } else {
                $this->db->trans_rollback();
                $this->session->set_flashdata('error', 'Gagal melakukan proses ini.');
                $callBack = [
                    "success"   => "false",
                    "message"   => "Gagal melakukan proses",
                    "redirect"  => "backmin/gudangpermintaan/detailBarangKeluar/$idTransaksi",
                ];
            }
        }
        echo json_encode($callBack);
    }
    
}
