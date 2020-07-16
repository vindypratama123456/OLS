<?php
defined('BASEPATH') or exit('No direct script access allowed');

/**
 * @property Mod_kabupaten_zona $mod_kabupaten_zona
 */
class Kabupaten_zona extends MY_Controller
{
    private $table;
    private $_output;

    public function __construct()
    {
        parent::__construct();
        $this->load->model('mod_kabupaten_zona');
        $this->load->model('mod_mitra');
        $this->table = 'employee';
        $this->_output = [];
    }

    public function index()
    {
        $data['page_title'] = 'Daftar Kabupaten Zona | ' . date('Y-m-d_His');
        $this->_output['content'] = $this->load->view('admin/kabupaten_zona/list', $data, true);
        $this->_output['script_js'] = $this->load->view('admin/kabupaten_zona/js', '', true);
        $this->load->view('admin/template', $this->_output);
    }

    public function list_kabupaten()
    {
        if ( ! $this->input->is_ajax_request()) {
            return false;
        }
        $this->load->library('datatables');
        $this->output->set_header('Content-Type:application/json; charset=utf-8');
        $this->datatables->select('
                                a.`id` as id,
                                a.kabupaten as kabupaten,
                                b.id_site as id_site,
                                b.nama_site as zona,
                                if(a.is_allowed_sd=1,"Ya","Tidak") as sd_aktif
                            ');
        $this->datatables->from('`master_kabupaten_zona` a');
        $this->datatables->join('`master_site` b', 'a.`zona`=b.`id_site`', 'inner');
       
        $this->datatables->edit_column('id', '<a href="' . base_url(ADMIN_PATH . '/kabupaten_zona/edit/$1') . '" title="Klik untuk detil">$1</a>', 'id');
        // $this->datatables->add_column('aksi', '<a class="btn btn-warning btn-sm" href="' . base_url(ADMIN_PATH . '/kabupaten_zona/edit/$1') . '" title="Klik untuk detil">Edit</a>  <a class="btn btn-danger btn-sm" href="' . base_url(ADMIN_PATH . '/kabupaten_zona/delete/$1') . '" id="del_data" onClick="confirm(\'Yakin ingin menghapus data ini?\')" href="#" title="Klik untuk Delete">Delete</a>', 'id');
        $this->datatables->add_column('aksi', '<a class="btn btn-warning btn-sm" href="' . base_url(ADMIN_PATH . '/kabupaten_zona/edit/$1') . '" title="Klik untuk detil">Edit</a>', 'id');
        $this->output->set_output($this->datatables->generate());
    }

    public function add()
    {
        $data['dataZona'] = $this->mod_kabupaten_zona->getZona();
        $data['detail'] = array('id'=> '', 'zona'=>'', 'kabupaten'=>'', 'is_allowed_sd'=>'1');
        $data['add'] = true;
        $this->_output['content'] = $this->load->view('admin/kabupaten_zona/add', $data, true);
        $this->_output['script_js'] = $this->load->view('admin/kabupaten_zona/js', '', true);
        $this->load->view('admin/template', $this->_output);
    }

    public function addPost()
    {
        if ( ! $this->input->is_ajax_request()) {
            redirect(ADMIN_PATH . '/category');
        }
        try {
            $data = [
                'id' => $this->input->post('id'),
                'kabupaten' => $this->input->post("kabupaten"),
                'zona' => $this->input->post('zona'),
                'is_allowed_sd' => $this->input->post('is_allowed_sd')
            ];
            $proc = $this->mod_kabupaten_zona->addData('master_kabupaten_zona', $data);
            if ($proc) {
                $callBack = [
                    'success' => 'true',
                    'message' => 'Data successfully added.'
                ];
                $this->session->set_flashdata('msg_success', 'Data kabupaten zona berhasil <b>DITAMBAHKAN</b></p>');
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

    public function edit($id_zona)
    {
        // $data['detail2'] = array('zona'=>'', 'kabupaten'=>'', 'is_allowed_sd'=>'1'); //get data detail from database
        $data['detail'] = $this->mod_kabupaten_zona->getKabupatenZonaDetail($id_zona)[0];

        // print_r($data['detail2']);
        // echo "<br>";
        // print_r($data['detail']);
        
        $data['dataZona'] = $this->mod_kabupaten_zona->getZona();
        $data['add'] = false;
        $this->_output['content'] = $this->load->view('admin/kabupaten_zona/add', $data, true);
        $this->_output['script_js'] = $this->load->view('admin/kabupaten_zona/js', '', true);
        $this->load->view('admin/template', $this->_output);
    }

    public function editPost()
    {
         if ( ! $this->input->is_ajax_request()) {
            redirect(ADMIN_PATH . '/category');
        }
        try {
            $id = $this->input->post('id');
            $data = [
                'kabupaten' => $this->input->post("kabupaten"),
                'zona' => $this->input->post('zona'),
                'is_allowed_sd' => $this->input->post('is_allowed_sd')
            ];
            $proc = $this->mod_kabupaten_zona->updateData('master_kabupaten_zona', $data, 'id', $id);
            if ($proc) {
                $callBack = [
                    'success' => 'true',
                    'message' => 'Data successfully updated.'
                ];
                $this->session->set_flashdata('msg_success', 'Data kabupaten zona berhasil <b>DIPERBARUI</b></p>');
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
            redirect(ADMIN_PATH . '/kabupaten_zona');
        }
        try {
            $id = $this->input->post('id');
            $proc = $this->mod_kabupaten_zona->deleteData("kabupaten_zona", 'id', $id);
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
