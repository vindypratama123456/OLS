<?php
defined('BASEPATH') or exit('No direct script access allowed');

/**
 * @property Mod_gudang $mod_gudang
 * @property Mod_gudang $mod_general
 */
class Gudanglaporan extends MY_Controller
{
    public function __construct()
    {
        parent::__construct();
        if (!in_array($this->adm_level, $this->backmin_gudang_area)) {
            redirect(BACKMIN_PATH);
        }
        $this->load->model('mod_gudang');
        $this->load->model('mod_general');
    }

    public function index()
    {
        redirect(BACKMIN_PATH . '/gudanglaporan/indexStok');
    }

    public function indexStok()
    {
        $where              = "";
        $term               = "";
        if ($this->input->post('search_input', true)) {
            $term           = $this->input->post('search_input', true);
            $where          = "b.name LIKE '%" . $this->input->post('search_input') . "%' OR b.kode_buku LIKE '%" . $this->input->post('search_input') . "%' OR c.name LIKE '%" . $this->input->post('search_input') . "%' OR d.name LIKE '%" . $this->input->post('search_input') . "%'";
        }
        
        $data['term']       = $term;
        $list_stok          = $this->mod_gudang->listInfoStok($where);
        $data['list_stok']  = [];

        $count  = [];
        foreach ($list_stok as $datas) {
            $count[$datas->category] = 0;
        }
        foreach ($list_stok as $row) {
            $data['list_stok'][$row->parent_category_name][$row->category_name][$count[$row->category]] = $row;
            $count[$row->category]++;
        }

        $data['page_title']     = 'Stok Barang';
        $data['content']        = $this->load->view(BACKMIN_PATH . '/gudang/laporan/list_stok', $data, true);
        $data['script_js']      = $this->load->view(BACKMIN_PATH . '/gudang/laporan/list_stok_js', '', true);
        
        $this->load->view(BACKMIN_PATH . '/main', $data);
    }

    public function index_report_transaction()
    {
        $data['listgudang'] = $this->mod_general->getAll('master_gudang', '*', 'status = 1', 'nama_gudang ASC');

        $data['page_title'] = 'Laporan Receiving - Inventory Stock';
        $data['content'] = $this->load->view('backmin/gudang/laporan/report_transaction', $data, true);
        $data['script_js'] = $this->load->view('backmin/gudang/laporan/report_transaction_js', '', true);
        $data['script_css'] = $this->load->view('backmin/gudang/laporan/report_transaction_css', '', true);

        $this->load->view(BACKMIN_PATH . '/main', $data);
    }

    public function report_transaction()
    {
        try {
            $start_date = $this->input->post('start_date');
            $end_date = $this->input->post('end_date');
            $id_gudang = (int)$this->input->post('id_gudang') ?: null;

            $report_receiving = $this->inventoryReceiving($start_date, $end_date, $id_gudang);

            $response = array(
                'success' => 'true',
                'data' => $report_receiving,
                // 'pagination' => $links
            );
            echo json_encode($response);

        } catch (Exception $e) {
            $callBack = array(
                'success' => 'false',
                'message' => 'Caught exception: '.$e->getMessage(),
            );
            echo json_encode($callBack, true);
        }
    }

    public function inventoryReceiving($start_date, $end_date, $id_gudang = null)
    {
        try {
            $report_receiving = $this->mod_finance->getReportReceiving($start_date, $end_date, $id_gudang);

            $report = array();
            $count = 0;
            $id_periode = 0;
            $id_gudang = 0;
            $id_bulan = 0;
            foreach ($report_receiving as $row => $data) {
                if ($id_periode != $data['id_periode']) {
                    $id_periode = $data['id_periode'];
                    $id_gudang = $data['id_gudang'];
                    $id_bulan = $data['bln_transaksi'];
                    $count = 0;
                } elseif ($id_gudang != $data['id_gudang']) {
                    $id_gudang = $data['id_gudang'];
                    $id_bulan = $data['bln_transaksi'];
                    $count = 0;
                } elseif ($id_bulan != $data['bln_transaksi']) {
                    $id_bulan = $data['bln_transaksi'];
                    $count = 0;
                }

                $total_cost = $data['jumlah_buku'] * $data['unit_cost'];

                $report[$id_periode]['nama_periode'] = $data['nama_periode'];
                $report[$id_periode]['row1'][$id_gudang]['nama_gudang'] = $data['nama_gudang'];
                $report[$id_periode]['row1'][$id_gudang]['row2'][$id_bulan]['nama_bulan'] = bulanIndo($data['bln_transaksi']);
                $report[$id_periode]['row1'][$id_gudang]['row2'][$id_bulan]['row3'][$count]['kode_buku'] = $data['kode_buku'];
                $report[$id_periode]['row1'][$id_gudang]['row2'][$id_bulan]['row3'][$count]['judul_buku'] = $data['judul_buku'];
                $report[$id_periode]['row1'][$id_gudang]['row2'][$id_bulan]['row3'][$count]['jumlah_buku'] = $data['jumlah_buku'];
                $report[$id_periode]['row1'][$id_gudang]['row2'][$id_bulan]['row3'][$count]['unit_cost'] = $data['unit_cost'];
                $report[$id_periode]['row1'][$id_gudang]['row2'][$id_bulan]['row3'][$count]['total_cost'] = $total_cost;
                $count++;
            }

            return $report;
        } catch (Exception $e) {
            return $e->getMessage();
        }
    }
}
