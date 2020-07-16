<?php
defined('BASEPATH') or exit('No direct script access allowed');

/**
 * @property Mod_general $mod_general
 */
class Employee extends MY_Controller
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
        $this->table = 'employee';
        $this->_output = [];
    }

    public function index($offset = 0)
    {
        $perpage = config_item('perpage');
        $this->load->library('pagination');
        $config = [
            'base_url' => base_url() . ADMIN_PATH . '/employee/index/',
            'num_links' => config_item('num_links'),
            'per_page' => $perpage,
            'total_rows' => $this->mod_general->getTotalRows($this->table),
            'uri_segment' => config_item('uri_segment')
        ];
        $this->pagination->initialize($config);
        $data['links'] = $this->pagination->create_links();
        $data['listdata'] = $this->mod_general->get_list('name', $this->table, ['perpage' => $perpage, 'offset' => $offset]);
        $this->_output['content'] = $this->load->view('admin/employee/list', $data, true);
        $this->_output['script_js'] = $this->load->view('admin/employee/js', '', true);
        $this->load->view('admin/template', $this->_output);
    }

    public function add()
    {
        if (!in_array($this->adm_level, $this->backoffice_superadmin_area)) {
            redirect(ADMIN_PATH . '/employee', 'refresh');
        }
        $data['regional'] = $this->mod_general->getWhere('group', 'active', 1, 'name', 'asc');
        $this->_output['content'] = $this->load->view('admin/employee/add', $data, true);
        $this->_output['script_js'] = $this->load->view('admin/employee/js', '', true);
        $this->load->view('admin/template', $this->_output);
    }

    public function addPost()
    {
        if ( ! $this->input->is_ajax_request()) {
            redirect(ADMIN_PATH . '/employee');
        }
        try {
            $data = [
                'name' => $this->input->post('name', true),
                'email' => $this->input->post('email', true),
                'passwd' => sha1($this->input->post('password', true)),
                'level' => $this->input->post('level'),
                'active' => 1
            ];
            if ( ! empty($this->input->post('regional'))) {
                $data['regional'] = $this->input->post('regional');
            }
            $proc = $this->mod_general->addData($this->table, $data);
            if ($proc) {
                $callBack = [
                    'success' => 'true',
                    'message' => 'Data successfully added.'
                ];
                $this->session->set_flashdata('msg_success',
                    'Data dengan nama: <b>' . $data['name'] . '</b> berhasil <b>DITAMBAHKAN</b></p>');
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
        $data['regional'] = $this->mod_general->getWhere('group', 'active', 1, 'name', 'asc');
        $data['detil'] = $this->mod_general->detailData($this->table, 'id_employee', $id);
        $this->_output['content'] = $this->load->view('admin/employee/edit', $data, true);
        $this->_output['script_js'] = $this->load->view('admin/employee/js', '', true);
        $this->load->view('admin/template', $this->_output);
    }

    public function editPost()
    {
        if ( ! $this->input->is_ajax_request()) {
            redirect(ADMIN_PATH . '/employee');
        }
        try {
            $id = $this->input->post('id_employee');
            $data = [
                'name' => $this->input->post('name', true),
                'email' => $this->input->post('email', true),
                'level' => $this->input->post('level'),
                'active' => $this->input->post('active')
            ];
            if ( ! empty($this->input->post('password'))) {
                $data['passwd'] = sha1($this->input->post('password'));
            }
            if ( ! empty($this->input->post('regional'))) {
                $data['regional'] = $this->input->post('regional');
            }
            $proc = $this->mod_general->updateData($this->table, $data, 'id_employee', $id);
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
            redirect(ADMIN_PATH . '/employee');
        }
        try {
            $id = $this->input->post('id');
            $proc = $this->mod_general->deleteData($this->table, 'id_employee', $id);
            if ($proc) {
                $this->session->set_flashdata('msg_success', 'Data dengan ID: <b>' . $id . '</b> berhasil <b>DIHAPUS</b></p>');
            } else {
                $this->session->set_flashdata('msg_failed', 'Data dengan judul: <b>' . $data['name'] . '</b> gagal <b>DIHAPUS</b></p>');
            }
            echo json_encode(['success' => 'true']);
        } catch (Exception $e) {
            echo 'Caught exception: ', $e->getMessage(), "\n";
        }
    }
}
