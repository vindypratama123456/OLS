<?php
defined('BASEPATH') or exit('No direct script access allowed');

/**
 * @property Mod_general $mod_general
 */
class Order_state extends MY_Controller
{
    private $table;
    private $_output;

    public function __construct()
    {
        parent::__construct();
        if (!in_array($this->adm_level, $this->backoffice_admin_area)) {
            redirect(ADMIN_PATH);
        }
        $this->load->model('mod_general');
        $this->table = 'order_state';
        $this->_output = [];
    }

    public function index($offset = 0)
    {
        $perpage = config_item('perpage');
        $this->load->library('pagination');
        $config = [
            'base_url' => base_url() . ADMIN_PATH . '/order_state/index/',
            'num_links' => config_item('num_links'),
            'per_page' => $perpage,
            'total_rows' => $this->mod_general->getTotalRowsWhere($this->table, 'deleted=0'),
            'uri_segment' => config_item('uri_segment')
        ];
        $this->pagination->initialize($config);
        $data['links'] = $this->pagination->create_links();
        $data['listdata'] = $this->mod_general->getListWhere('name', $this->table, 'deleted=0', ['perpage' => $perpage, 'offset' => $offset]);
        $this->_output['content'] = $this->load->view('admin/order_state/list', $data, true);
        $this->_output['script_js'] = $this->load->view('admin/order_state/js', '', true);
        $this->load->view('admin/template', $this->_output);
    }

    public function add()
    {
        $this->_output['content'] = $this->load->view('admin/order_state/add', '', true);
        $this->_output['script_js'] = $this->load->view('admin/order_state/js', '', true);
        $this->load->view('admin/template', $this->_output);
    }

    public function addPost()
    {
        if ( ! $this->input->is_ajax_request()) {
            redirect(ADMIN_PATH . '/order_state');
        }
        try {
            $data = ['name' => $this->input->post('name', true)];
            $proc = $this->mod_general->addData($this->table, $data);
            if ($proc) {
                $callBack = [
                    'success' => 'true',
                    'message' => 'Data successfully added.'
                ];
                $this->session->set_flashdata('msg_success', 'Data dengan nama: <b>' . $data['name'] . '</b> berhasil <b>DITAMBAHKAN</b></p>');
            } else {
                $callBack = [
                    'success' => 'false',
                    'message' => 'Failed to add data.'
                ];
            }
            echo json_encode($callBack, true);
        } catch (Exception $e) {
            echo 'Caught exception: ', $e->getMessage(), "\n";
        }
    }

    public function edit($id)
    {
        $data['detil'] = $this->mod_general->detailData($this->table, 'id_order_state', $id);
        $this->_output['content'] = $this->load->view('admin/order_state/edit', $data, true);
        $this->_output['script_js'] = $this->load->view('admin/order_state/js', '', true);
        $this->load->view('admin/template', $this->_output);
    }

    public function editPost()
    {
        if ( ! $this->input->is_ajax_request()) {
            redirect(ADMIN_PATH . '/order_state');
        }
        try {
            $id = $this->input->post('id_order_state');
            $data = ['name' => $this->input->post('name', true)];
            $proc = $this->mod_general->updateData($this->table, $data, 'id_order_state', $id);
            if ($proc) {
                $callBack = [
                    'success' => 'true',
                    'message' => 'Data successfully updated.'
                ];
                $this->session->set_flashdata('msg_success', 'Data dengan nama: <b>' . $data['name'] . '</b> berhasil <b>DIPERBARUI</b></p>');
            } else {
                $callBack = [
                    'success' => 'false',
                    'message' => 'Failed to update data.'
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
            redirect(ADMIN_PATH . '/order_state');
        }
        try {
            $id = $this->input->post('id');
            $data = ['deleted' => 1];
            $proc = $this->mod_general->updateData($this->table, $data, 'id_order_state', $id);
            if ($proc) {
                $this->session->set_flashdata('msg_success', 'Data dengan ID: <b>' . $id . '</b> berhasil <b>DIHAPUS</b></p>');
            } else {
                $this->session->set_flashdata('msg_failed', 'Data dengan ID: <b>' . $id . '</b> gagal <b>DIHAPUS</b></p>');
            }
            echo json_encode(['success' => 'true']);
        } catch (Exception $e) {
            echo 'Caught exception: ', $e->getMessage(), "\n";
        }
    }
}
