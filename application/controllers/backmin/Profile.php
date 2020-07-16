<?php
defined('BASEPATH') or exit('No direct script access allowed');

/**
 * @property Mod_general $mod_general
 * @property Mod_mitra $mod_mitra
 */
class Profile extends MY_Controller
{
    private $table;
    private $_output;

    public function __construct()
    {
        parent::__construct();
        $this->load->model('mod_general');
        $this->load->model('mod_mitra');
        $this->table = 'employee';
        $this->_output = [];
    }

    public function index()
    {
        $data['detil'] = $this->mod_general->detailData($this->table, 'id_employee', $this->adm_id);
        $this->_output['content'] = $this->load->view('backmin/profile/detail', $data, true);
        $this->_output['script_js'] = $this->load->view('backmin/profile/js', '', true);
        $this->_output['script_css'] = $this->load->view('backmin/profile/css', '', true);
        $this->load->view('backmin/main', $this->_output);
    }

    public function editPost()
    {
        if ( ! $this->input->is_ajax_request()) {
            redirect(BACKMIN_PATH . '/profile');
        }
        try {
            $id = $this->adm_id;
            if(empty($this->input->post('password')))
            {
                $data = [
                    'name' => $this->input->post('name', true),
                    'email' => $this->input->post('email', true)
                ];   
            }
            else
            {
                $data = [
                    'name' => $this->input->post('name', true),
                    'email' => $this->input->post('email', true),
                    'passwd' => sha1($this->input->post('password'))
                ];
            }
            $proc = $this->mod_general->updateData($this->table, $data, 'id_employee', $id);
            if ($proc) {
                $callBack = [
                    'success' => 'true',
                    'message' => 'Data successfully updated.'
                ];
                $this->session->set_flashdata('msg_success', 'Data profil berhasil <b>DIPERBARUI</b></p>');
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
}
