<?php
defined('BASEPATH') or exit('No direct script access allowed');

/**
 * @property Mod_bank $mod_bank
 */
class Bank extends MY_Controller
{
    private $table;
    private $_output;

    public function __construct()
    {
        parent::__construct();
        $this->load->model('mod_bank');
        $this->_output = [];
    }

    public function index()
    {
        $data['page_title'] = 'Daftar Bank | ' . date('Y-m-d_His');
        $this->_output['content'] = $this->load->view('admin/bank/list', $data, true);
        $this->_output['script_js'] = $this->load->view('admin/bank/js', '', true);
        $this->load->view('admin/template', $this->_output);
    }

    public function list_bank()
    {
        if ( ! $this->input->is_ajax_request()) {
            return false;
        }
        $this->load->library('datatables');
        $this->output->set_header('Content-Type:application/json; charset=utf-8');
        $this->datatables->select('
                                a.`id` as id,
                                a.`bank_code` as bank_code, 
                                a.`bank_name` as bank_name, 
                                a.`bank_alias` as bank_alias,
                                if(a.status=1,"Aktif","Tidak Aktif") as status
                            ');
        $this->datatables->from('`master_bank` a');
       
        $this->datatables->edit_column('id', '<a href="' . base_url(ADMIN_PATH . '/bank/edit/$1') . '" title="Klik untuk detil">$1</a>', 'id');

        $this->datatables->add_column('aksi', '<a class="btn btn-warning btn-sm" href="' . base_url(ADMIN_PATH . '/bank/edit/$1') . '" title="Klik untuk detil">Edit</a>  <a href="#" data="$1" class="btn btn-danger btn-sm" id="del_data" title="Klik untuk Delete">Delete</a>', 'id');

        $this->output->set_output($this->datatables->generate());
    }

    public function add()
    {
        $data['databank'] = $this->mod_bank->getBank();
        $data['detail'] = array('id'=> '', 'bank_name'=>'', 'bank_code'=>'', 'bank_alias'=>'', 'status'=>'1');
        $data['add'] = true;
        $this->_output['content'] = $this->load->view('admin/bank/add', $data, true);
        $this->_output['script_js'] = $this->load->view('admin/bank/js', '', true);
        $this->load->view('admin/template', $this->_output);
    }

    public function addPost()
    {
        if ( ! $this->input->is_ajax_request()) {
            redirect(ADMIN_PATH . '/bank');
        }
        try {
            $data = [
                'id' => $this->input->post('id'),
                'bank_code' => $this->input->post("bank_code"),
                'bank_name' => $this->input->post('bank_name'),
                'bank_alias' => $this->input->post('bank_alias'),
                'status' => $this->input->post('status')
            ];
            $proc = $this->mod_bank->addData('master_bank', $data);
            if ($proc) {
                $callBack = [
                    'success' => 'true',
                    'message' => 'Data successfully added.'
                ];
                $this->session->set_flashdata('msg_success', 'Data kabupaten bank berhasil <b>DITAMBAHKAN</b></p>');
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

    public function edit($id_bank)
    {
        $data['detail'] = $this->mod_bank->getBankDetail($id_bank)[0];

        
        $data['databank'] = $this->mod_bank->getbank();
        $data['add'] = false;
        $this->_output['content'] = $this->load->view('admin/bank/add', $data, true);
        $this->_output['script_js'] = $this->load->view('admin/bank/js', '', true);
        $this->load->view('admin/template', $this->_output);
    }

    public function editPost()
    {
         if ( ! $this->input->is_ajax_request()) {
            redirect(ADMIN_PATH . '/bank');
        }
        try {
            $id = $this->input->post('id');
            $data = [
                'id' => $this->input->post('id'),
                'bank_code' => $this->input->post("bank_code"),
                'bank_name' => $this->input->post('bank_name'),
                'bank_alias' => $this->input->post('bank_alias'),
                'status' => $this->input->post('status')
            ];
            $proc = $this->mod_bank->updateData('master_bank', $data, 'id', $id);
            if ($proc) {
                $callBack = [
                    'success' => 'true',
                    'message' => 'Data successfully updated.'
                ];
                $this->session->set_flashdata('msg_success', 'Data bank berhasil <b>DIPERBARUI</b></p>');
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
            redirect(ADMIN_PATH . '/bank');
        }
        try {
            $id = $this->input->post('id');
            $proc = $this->mod_bank->deleteData("master_bank", 'id', $id);
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
