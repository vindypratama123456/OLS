<?php
defined('BASEPATH') or exit('No direct script access allowed');

/**
 * @property Mod_general $mod_general
 * @property Datatable $datatable
 * @property Datatables $datatables
 */
class Partner extends MY_Controller
{
    private $table;
    private $_output;

    public function __construct()
    {
        parent::__construct();
        $this->load->model('mod_general');
        $this->table = 'product';
        $this->_output = array();
    }

    public function index()
    {
        $data['is_operator'] = ($this->session->userdata('adm_level') == 3) ? true : false;
        $data['partner'] = $this->mod_general->getAll('partner', '');
        $this->_output['content'] = $this->load->view('admin/partner/list', $data, true);
        $this->_output['script_js'] = $this->load->view('admin/partner/js', '', true);
        $this->load->view('admin/template', $this->_output);
    }

    // public function list_partner()
    // {
    //     if ( ! $this->input->is_ajax_request()) {
    //         redirect(ADMIN_PATH . '/catalog');
    //     }
    //     $this->load->library('datatables');
    //     $this->output->set_header('Content-Type:application/json; charset=utf-8');
    //     $this->datatables->select('o.id_product AS id_product, 
    //             o.name AS name, 
    //             o.reference AS isbn, 
    //             o.kode_buku AS kode_buku, 
    //             p.name AS category, 
    //             q.name AS type, 
    //             ROUND(o.price_1) AS price_1, 
    //             ROUND(o.price_2) AS price_2, 
    //             ROUND(o.price_3) AS price_3, 
    //             ROUND(o.price_4) AS price_4, 
    //             ROUND(o.price_5) AS price_5');
    //     $this->datatables->from($this->table . ' o');
    //     $this->datatables->join('category p', 'p.id_category=o.id_category_default', 'inner');
    //     $this->datatables->join('category q', 'q.id_category=p.id_parent', 'inner');
    //     $this->datatables->where('o.active', 1);
    //     $this->datatables->where('o.kode_buku !=', null);
    //     $this->datatables->where('o.kode_buku !=', '');
    //     $this->datatables->edit_column('kode_buku', '<a href="' . base_url(ADMIN_PATH . '/catalog/detail/$1') . '">$2</a>', 'id_product, kode_buku');
    //     $this->output->set_output($this->datatables->generate());
    // }

    public function add()
    {
        if ( ! $this->input->is_ajax_request()) {
            return false;
        }
        // $data['old_qty'] = $qty;
        // $data['detil'] = $this->mod_general->detailData('order_detail', 'id_product_detail', $id);
        $data = "";
        $this->load->view('admin/partner/add', $data);
    }

    public function add_post()
    {
        if ( ! $this->input->is_ajax_request()) {
            redirect(ADMIN_PATH . '/partner');
        }
        try {
            $name = strtoupper($this->input->post('name'));

            // insert ke tabel partner
            $data = [
                'name' => $name,
            ];
            $proc1 = $this->mod_general->addData("partner", $data);
            if ($proc1) {
                $callBack = [
                    'success' => 'true',
                    'message' => 'Data successfully updated.'
                ];
            } else {
                $callBack = [
                    'success' => 'false',
                    'message' => 'Failed to insert data.'
                ];
            }
            echo json_encode($callBack, true);
        } catch (Exception $e) {
            echo 'Caught exception: ', $e->getMessage(), "\n";
        }
    }

    public function update($id)
    {
        if ( ! $this->input->is_ajax_request()) {
            return false;
        }
        // $data['old_qty'] = $qty;
        $data['detil'] = $this->mod_general->detailData('partner', 'id', $id);
        $this->load->view('admin/partner/update', $data);
    }

    public function update_post()
    {
        if ( ! $this->input->is_ajax_request()) {
            redirect(ADMIN_PATH . '/partner');
        }
        try {
            $id = $this->input->post('id');
            $name = strtoupper($this->input->post('name'));

            // insert ke tabel partner
            $data = [
                'name' => $name,
            ];
            $proc1 = $this->mod_general->updateData("partner", $data, 'id', $id);
            if ($proc1) {
                $callBack = [
                    'success' => 'true',
                    'message' => 'Data successfully updated.'
                ];
            } else {
                $callBack = [
                    'success' => 'false',
                    'message' => 'Failed to insert data.'
                ];
            }
            echo json_encode($callBack, true);
        } catch (Exception $e) {
            echo 'Caught exception: ', $e->getMessage(), "\n";
        }
    }

    public function delete()
    {
        if ( ! $this->input->is_ajax_request()) {
            redirect(ADMIN_PATH . '/partner');
        }
        try {
            $id = $this->input->post('id');

            $proc1 = $this->mod_general->deleteData("partner", "id", $id);
            if ($proc1) {
                $callBack = [
                    'success' => 'true',
                    'message' => 'Data successfully deleted.'
                ];
            } else {
                $callBack = [
                    'success' => 'false',
                    'message' => 'Failed to delete data.'
                ];
            }
            echo json_encode($callBack, true);
        } catch (Exception $e) {
            echo 'Caught exception: ', $e->getMessage(), "\n";
        }
    }
}
