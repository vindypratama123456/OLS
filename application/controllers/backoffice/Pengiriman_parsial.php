<?php
defined('BASEPATH') or exit('No direct script access allowed');

/**
 * @property Datatable $datatable
 * @property Datatables $datatables
 * @property Excel $excel
 * @property Mod_general $mod_general
 * @property Mod_order $mod_order
 * @property Mod_pesanan $m_pesanan
 * @property Mod_gudang $m_gudang
 * @property Mymail $mymail
 * @property Dompdf_gen $dompdf_gen
 */
class Pengiriman_parsial extends MY_Controller
{
    private $table;
    private $_output;

    public function __construct()
    {
        parent::__construct();
        $this->load->model('mod_general');
        $this->load->model('mod_order');
        $this->load->model('mod_gudang');
        $this->table = 'orders';
        $this->_output = [];
    }

    public function index()
    {
        $data['page_title'] = 'Pesanan Online | '.date('Y-m-d_His');
        
        $this->_output['content'] = $this->load->view('admin/pengiriman_parsial/list', $data, true);
        $this->_output['script_js'] = $this->load->view('admin/pengiriman_parsial/list_js', '', true);
        $this->load->view('admin/template', $this->_output);
    }

    public function list($variant = 1)
    {
        if ( ! $this->input->is_ajax_request()) {
            redirect(ADMIN_PATH.'/pengiriman_parsial');
        }
        $sign = $variant == 1 ? '!=' : '=';
        $this->load->library('datatables');
        $this->output->set_header('Content-Type:application/json; charset=utf-8');
        $this->datatables->select('a.id_order AS id_order, 
                                   a.reference AS kode, 
                                   b.school_name AS nama_sekolah, 
                                   b.provinsi AS propinsi, 
                                   b.kabupaten AS kabupaten, 
                                   b.kecamatan AS kecamatan,
                                   a.category AS kelas, 
                                   a.type AS tipe,
                                   a.semester as semester,
                                   a.date_add AS tgl_pesan, 
                                   c.name AS status, 
                                   c.label AS label, 
                                   a.total_paid AS total_harga,
                                   a.sales_name AS mitra');
        $this->datatables->from('orders a');
        $this->datatables->join('customer b', 'b.id_customer=a.id_customer', 'inner');
        $this->datatables->join('order_state c', 'c.id_order_state=a.current_state', 'inner');
        $this->datatables->where('a.is_offline '.$sign, 1);
        $this->datatables->where('kirim_parsial_request_by_id <>', null);
        $this->datatables->where('kirim_parsial_accept_by_id', null);
        $this->datatables->where('b.kabupaten IN (SELECT aa.kabupaten_kota FROM employee_kabupaten_kota aa WHERE aa.id_employee = '.$this->adm_id.')');
        $this->datatables->edit_column('status', '<span class="label $1">$2</span>', 'label, status');
        $this->datatables->edit_column('kode', '<a href="'.base_url(ADMIN_PATH.'/pengiriman_parsial/detail/$1').'">$2</a>',
            'id_order, kode');
        $this->output->set_output($this->datatables->generate());
    }

    public function detail($id)
    {
        // if($this->adm_level==3) {
        //     if(false==$this->isHaveAccess($id))
        //         redirect(ADMIN_PATH.'/orders','refresh');
        // }
        if ($id && is_numeric($id)) {
            $data['order_states'] = $this->mod_general->getWhere('order_state', 'deleted', 0, 'id_order_state', 'asc');
            $data['detil'] = $this->mod_general->detailData($this->table, 'id_order', $id);
            if ($data['detil']) {
                $data['customer'] = $this->mod_general->detailData('customer', 'id_customer',
                    $data['detil']['id_customer']);
                if (in_array($this->adm_level, $this->backoffice_admin_area) || $this->adm_level == 8) {
                    $kabupaten = get_data([
                        'field' => 'kabupaten',
                        'table' => 'customer',
                        'key' => 'id_customer',
                        'data' => $data['detil']['id_customer'],
                    ]);
                    $korwil = $this->mod_order->getKorwilById($kabupaten);
                } else {
                    $korwil = $this->adm_id;
                }
                $data['listproducts'] = $this->mod_order->getListProducts($id);
                $data['category_books'] = $data['listproducts'][0]->category;
                $data['class_books'] = $data['listproducts'][0]->class;
                $data['liststatus'] = $this->mod_order->getListStatus($id);
                $data['listhistory'] = $this->mod_order->getListHistory($id);
                $data['listsales'] = $this->mod_order->getSalesPerson($korwil, true);
                $data['korwil'] = $this->mod_order->getKorwil($data['customer']['kabupaten'])[0];
                $data['adm_level'] = $this->adm_level;
                $data['isCoverageArea'] = $this->mod_order->isCoverageArea($data['customer']['kabupaten']);
                $data['isInComission'] = $this->mod_order->isInComission($id);
                $data['isInSCMProcess'] = $this->mod_order->isInSCMProcess($id);
                $this->_output['content'] = $this->load->view('admin/pengiriman_parsial/detail', $data, true);
                $this->_output['script_css'] = $this->load->view('admin/pengiriman_parsial/css', '', true);
                $this->_output['script_js'] = $this->load->view('admin/pengiriman_parsial/detail_js', '', true);
                $this->load->view('admin/template', $this->_output);
            } else {
                redirect(ADMIN_PATH.'/pengiriman_parsial', 'refresh');
            }
        } else {
            redirect(ADMIN_PATH.'/pengiriman_parsial', 'refresh');
        }
    }

    public function index_processed()
    {
        $data['page_title'] = 'Pesanan Online | '.date('Y-m-d_His');
        
        $this->_output['content'] = $this->load->view('admin/pengiriman_parsial/list_processed', $data, true);
        $this->_output['script_js'] = $this->load->view('admin/pengiriman_parsial/list_processed_js', '', true);
        $this->load->view('admin/template', $this->_output);
    }

    public function list_processed($variant = 1)
    {
        if ( ! $this->input->is_ajax_request()) {
            redirect(ADMIN_PATH.'/pengiriman_parsial');
        }
        $sign = $variant == 1 ? '!=' : '=';
        $this->load->library('datatables');
        $this->output->set_header('Content-Type:application/json; charset=utf-8');
        $this->datatables->select('a.id_order AS id_order, 
                                   a.reference AS kode, 
                                   b.school_name AS nama_sekolah, 
                                   b.provinsi AS propinsi, 
                                   b.kabupaten AS kabupaten, 
                                   b.kecamatan AS kecamatan,
                                   a.category AS kelas, 
                                   a.type AS tipe,
                                   a.semester as semester,
                                   a.date_add AS tgl_pesan, 
                                   c.name AS status, 
                                   c.label AS label, 
                                   a.total_paid AS total_harga,
                                   a.sales_name AS mitra');
        $this->datatables->from('orders a');
        $this->datatables->join('customer b', 'b.id_customer=a.id_customer', 'inner');
        $this->datatables->join('order_state c', 'c.id_order_state=a.current_state', 'inner');
        $this->datatables->where('a.is_offline '.$sign, 1);
        $this->datatables->where('kirim_parsial_request_by_id <>', null);
        $this->datatables->where('kirim_parsial_accept_by_id <>', null);
        $this->datatables->where('b.kabupaten IN (SELECT aa.kabupaten_kota FROM employee_kabupaten_kota aa WHERE aa.id_employee = '.$this->adm_id.')');
        $this->datatables->edit_column('status', '<span class="label $1">$2</span>', 'label, status');
        $this->datatables->edit_column('kode', '<a href="'.base_url(ADMIN_PATH.'/pengiriman_parsial/detail/$1').'">$2</a>',
            'id_order, kode');
        $this->output->set_output($this->datatables->generate());
    }

    public function accept_parsial()
    {
        $id_order = $this->input->post('id_order'); 
        $adm_id = $this->adm_id;
        $adm_name = $this->adm_name;
        $date = date('Y-m-d H:i:s');

        $data = array(
            "kirim_parsial_accept_by_id" => $adm_id,
            "kirim_parsial_accept_by_name" => $adm_name,
            "kirim_parsial_accept_date" => $date
        );

        $where  = array(
            "id_order" => $id_order
        );

        $query = $this->mod_gudang->update("orders", $where, $data);

        if($query)
        {
            $callBack   = [   
                "success"       => "true",
                "message"       => "Berhasil melakukan request pengiriman parsial",
                "redirect"      => "pengiriman_parsial/detail/$id_order",
            ];
        }
        else
        {
            $callBack   = [   
                "success"       => "false",
                "message"       => "Gagal melakukan request pengiriman parsial",
                "redirect"      => "pengiriman_parsial/detail/$id_order",
            ];
        }

        echo json_encode($callBack);
    }

    public function denied_parsial()
    {
        $id_order = $this->input->post('id_order'); 
        $adm_id = $this->adm_id;
        $adm_name = $this->adm_name;
        $date = date('Y-m-d H:i:s');

        $adm_id = null;
        $adm_name = null;
        $date = null;        

        $data = array(
            "kirim_parsial_request_by_id" => $adm_id,
            "kirim_parsial_request_by_name" => $adm_name,
            "kirim_parsial_request_date" => $date
        );

        $where  = array(
            "id_order" => $id_order
        );

        $query = $this->mod_gudang->update("orders", $where, $data);

        if($query)
        {
            $callBack   = [   
                "success"       => "true",
                "message"       => "Berhasil melakukan request pengiriman parsial",
                "redirect"      => "pengiriman_parsial/detail/$id_order",
            ];
        }
        else
        {
            $callBack   = [   
                "success"       => "false",
                "message"       => "Gagal menolak request pengiriman parsial",
                "redirect"      => "pengiriman_parsial/detail/$id_order",
            ];
        }

        echo json_encode($callBack);
    }
}
