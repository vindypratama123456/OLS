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
        if ($this->adm_level == 4) {
            $data['detil'] = $this->mod_mitra->getDetail($this->adm_id);
            $data['listBank'] = $this->mod_mitra->getAll("master_bank", "*", "status = 1", "id asc");
            $this->_output['content'] = $this->load->view('admin/profile/mitra', $data, true);
        } else {
            $data['detil'] = $this->mod_general->detailData($this->table, 'id_employee', $this->adm_id);
            $this->_output['content'] = $this->load->view('admin/profile/detail', $data, true);
        }
        $this->_output['script_js'] = $this->load->view('admin/profile/js', '', true);
        $this->_output['script_css'] = $this->load->view('admin/profile/css', '', true);
        $this->load->view('admin/template', $this->_output);
    }

    public function editPost()
    {
        if ( ! $this->input->is_ajax_request()) {
            redirect(ADMIN_PATH . '/profile');
        }
        try {
            $id = $this->adm_id;
            $data = [
                'name' => $this->input->post('name', true),
                'email' => $this->input->post('email', true),
                'passwd' => sha1($this->input->post('password'))
            ];
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

    public function updateMitra()
    {
        if ( ! $this->input->is_ajax_request()) {
            redirect(ADMIN_PATH);
        }
        try {
            $id = $this->adm_id;
            $dataEmployee = [
                'name' => trim($this->input->post('name', true)),
                'telp' => trim($this->input->post('telp', true))
            ];
            if ($this->input->post('passwd')) {
                $dataEmployee['passwd'] = sha1($this->input->post('passwd'));
            }
            $dataMitra = [
                'identity_code' => trim($this->input->post('identity_code', true)),
                'gender' => $this->input->post('gender'),
                'address' => trim($this->input->post('address', true)),
                'name_npwp' => trim($this->input->post('name_npwp', true)),
                'no_npwp' => trim($this->input->post('no_npwp', true)),
                'address_npwp' => trim($this->input->post('address_npwp', true)),
                'bank_account_number' => trim($this->input->post('bank_account_number', true)),
                'bank_account_name' => trim($this->input->post('bank_account_name', true)),
                'bank_account_type' => trim($this->input->post('bank_account_type', true)),
                'date_modified' => date('Y-m-d H:i:s')
            ];
            $this->db->trans_begin();
            $updateEmployee = $this->mod_general->updateData('employee', $dataEmployee, 'id_employee', $id);
            if ($updateEmployee) {
                $updateMitra = $this->mod_general->updateData('mitra_profile', $dataMitra, 'id_employee', $id);
                if ($updateMitra) {
                    $this->db->trans_commit();
                    $callBack = [
                        'success' => 'true',
                        'message' => 'Data successfully updated.'
                    ];
                    $this->session->set_flashdata('msg_success', 'Data profil berhasil <b>DIPERBARUI</b></p>');
                } else {
                    $this->db->trans_rollback();
                    $callBack = [
                        'success' => 'false',
                        'message' => 'Failed to update mitra.'
                    ];
                }
            } else {
                $this->db->trans_rollback();
                $callBack = [
                    'success' => 'false',
                    'message' => 'Failed to update employee.'
                ];
            }
            echo json_encode($callBack, true);
        } catch (Exception $e) {
            $callBack = [
                'success' => 'false',
                'message' => 'Caught exception: ' . $e->getMessage()
            ];
            echo json_encode($callBack, true);
        }
    }
}
