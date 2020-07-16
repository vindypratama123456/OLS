<?php
defined('BASEPATH') or exit('No direct script access allowed');

/**
 * @property Datatables $datatables
 * @property Mod_general $mod_general
 * @property Mod_feedback $mod_feedback
 */
class Feedback extends MY_Controller
{
    private $table;
    private $_output;

    public function __construct()
    {
        parent::__construct();
        if (!in_array($this->adm_level, $this->backoffice_superadmin_area)) {
            redirect(ADMIN_PATH, 'refresh');
        }
        $this->load->model('mod_general');
        $this->load->model('mod_feedback');
        $this->table = 'feedback';
        $this->_output = [];
    }

    public function index()
    {
        $this->_output['content'] = $this->load->view('admin/feedback/list', '', true);
        $this->_output['script_js'] = $this->load->view('admin/feedback/js', '', true);
        $this->load->view('admin/template', $this->_output);
    }

    public function list_feedback()
    {
        if ( ! $this->input->is_ajax_request()) {
            redirect(ADMIN_PATH . '/feedback');
        }
        $this->load->library('datatables');
        $this->output->set_header('Content-Type:application/json; charset=utf-8');
        $this->datatables->select('a.id_order AS id, 
                                   a.id_order AS id_order, 
                                   b.reference AS kode, 
                                   b.reference AS kode_pesanan, 
                                   c.school_name AS nama_sekolah, 
                                   a.comment AS testimoni, 
                                   a.created_at AS tgl_tulis, 
                                   IF(a.enable=\'0\', \'<span class="label btn-danger">Nonaktif</span>\', \'<span class="label btn-success">Aktif</span>\') AS status');
        $this->datatables->from($this->table . ' a');
        $this->datatables->join('orders b', 'b.id_order=a.id_order');
        $this->datatables->join('customer c', 'c.id_customer=b.id_customer');
        $this->datatables->unset_column('kode_pesanan');
        $this->datatables->edit_column('id_order', '<a href="' . base_url(ADMIN_PATH . '/feedback/detail/$1') . '">$1</a>', 'id_order, kode');
        $this->datatables->edit_column('kode_pesanan', '<a href="' . base_url(ADMIN_PATH . '/orders/detail/$1') . '" target="_blank">$2</a>', 'id, kode_pesanan');
        $this->output->set_output($this->datatables->generate());
    }

    public function detail($id)
    {
        $data['detil'] = $this->mod_feedback->detailTestimony($id);
        $this->_output['content'] = $this->load->view('admin/feedback/detail', $data, true);
        $this->_output['script_js'] = $this->load->view('admin/feedback/js', '', true);
        $this->load->view('admin/template', $this->_output);
    }

    # TODO : add logs and block permission for auditor
    public function editPost()
    {
        if ( ! $this->input->is_ajax_request()) {
            redirect(ADMIN_PATH . '/feedback');
        }
        try {
            if (in_array($this->adm_level, $this->auditor_area)) {
                $callBack   = [   
                    "success"   => "false",
                    "message"   => "Maaf, anda tidak dapat melakukan proses ini."
                ];
            } else {
                $id = $this->input->post('id_order');
                $status = $this->input->post('enable');
                $data = ['enable' => $status];
                $proc1 = $this->mod_general->updateData($this->table, $data, 'id_order', $id);
                if ($proc1) {
                    $callBack = [
                        'success' => 'true',
                        'message' => 'Data successfully updated.'
                    ];
                } else {
                    $callBack = [
                        'success' => 'false',
                        'message' => 'Failed to update data.'
                    ];
                }
            }
            echo json_encode($callBack, true);
        } catch (Exception $e) {
            echo 'Caught exception: ', $e->getMessage(), "\n";
        }
    }
}
