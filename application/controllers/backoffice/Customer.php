<?php
defined('BASEPATH') or exit('No direct script access allowed');

/**
 * @property Datatable $datatable
 * @property Datatables $datatables
 * @property Mod_general $mod_general
 */
class Customer extends MY_Controller
{
    private $table;
    private $_output;

    public function __construct()
    {
        parent::__construct();
        if (!in_array($this->adm_level, array_merge($this->backoffice_admin_area, [3,8]))) {
            redirect(ADMIN_PATH);
        }
        $this->load->model('mod_general');
        $this->table = 'customer';
        $this->_output = [];
    }

    public function index()
    {
        // $data['is_operator'] = ($this->adm_level==3) ? true : false;
        $data['page_title'] = 'Daftar Pelanggan | ' . date('Y-m-d_His');
        $data['is_operator'] = false;
        $this->_output['content'] = $this->load->view('admin/customer/list', $data, true);
        $this->_output['script_js'] = $this->load->view('admin/customer/js', '', true);
        $this->load->view('admin/template', $this->_output);
    }

    public function list_customer()
    {
        if ( ! $this->input->is_ajax_request()) {
            return false;
        }
        $this->load->library('datatables');
        $this->output->set_header('Content-Type:application/json; charset=utf-8');
        $this->datatables->select('a.id_customer AS id_customer,
                                   a.no_npsn AS npsn,
                                   a.school_name AS nama_sekolah,
                                   a.provinsi AS propinsi,
                                   a.kabupaten AS kabupaten,
                                   a.phone AS telpon');
        $this->datatables->from($this->table . ' a');
        if (in_array($this->adm_level, [3, 8])) {
            $this->datatables->where('a.kabupaten IN (SELECT aa.kabupaten_kota FROM employee_kabupaten_kota aa WHERE aa.id_employee = ' . $this->adm_id . ')');
        }
        $this->datatables->edit_column('npsn', '<a href="' . base_url(ADMIN_PATH . '/customer/edit/$1') . '" title="Klik untuk detil">$2</a>', 'id_customer, npsn');
        $this->datatables->edit_column('nama_sekolah', '<a href="' . base_url(ADMIN_PATH . '/customer/edit/$1') . '" title="Klik untuk detil">$2</a>', 'id_customer, nama_sekolah');
        $this->output->set_output($this->datatables->generate());
    }

    public function has_order()
    {
        if (!in_array($this->adm_level, $this->backoffice_superadmin_area)) {
            redirect(ADMIN_PATH, 'refresh');
        }
        $data['page_title'] = 'Daftar Pelanggan (Sudah Pesan) | ' . date('Y-m-d_His');
        $data['is_operator'] = false;
        $this->_output['content'] = $this->load->view('admin/customer/list_has_order', $data, true);
        $this->_output['script_js'] = $this->load->view('admin/customer/js', '', true);
        $this->load->view('admin/template', $this->_output);
    }

    public function list_customer_has_order()
    {
        if ( ! $this->input->is_ajax_request()) {
            redirect(ADMIN_PATH . 'customer');
        }
        $this->load->library('datatables');
        $this->output->set_header('Content-Type:application/json; charset=utf-8');
        $this->datatables->select('a.id_order AS id_order,
                                  b.id_customer AS id_customer, 
                                  a.reference AS kode,
                                  b.no_npsn AS npsn,
                                  b.school_name AS nama_sekolah,
                                  b.provinsi AS propinsi,
                                  b.kabupaten AS kabupaten,
                                  b.kecamatan AS kecamatan,
                                  b.alamat AS alamat,
                                  b.phone AS telpon,
                                  b.email AS email,
                                  b.name AS nama_kepsek,
                                  b.phone_kepsek AS phone_kepsek,
                                  b.email_kepsek AS email_kepsek,
                                  b.operator AS operator,
                                  b.hp_operator AS hp_operator,
                                  b.email_operator AS email_operator,
                                  a.date_add AS tgl_pesan,
                                  a.total_paid AS total_harga,
                                  c.label AS label,
                                  c.name AS status');
        $this->datatables->from('orders a');
        $this->datatables->join($this->table . ' b', 'b.id_customer=a.id_customer');
        $this->datatables->join('order_state c', 'c.id_order_state=a.current_state');
        if (in_array($this->adm_level, [3, 8])) {
            $this->datatables->where('b.kabupaten IN (SELECT aa.kabupaten_kota FROM employee_kabupaten_kota aa WHERE aa.id_employee = ' . $this->adm_id . ')');
        }
        $this->datatables->edit_column('kode', '<a href="' . base_url(ADMIN_PATH . '/orders/detail/$1') . '" title="Klik untuk detil">$2</a>', 'id_order, kode');
        $this->datatables->edit_column('npsn', '<a href="' . base_url(ADMIN_PATH . '/customer/edit/$1') . '" title="Klik untuk detil">$2</a>', 'id_customer, npsn');
        $this->datatables->edit_column('status', '<span class="label $1">$2</span>', 'label, status');
        $this->output->set_output($this->datatables->generate());
    }

    public function no_order()
    {
        $data['page_title'] = 'Daftar Pelanggan (Belum Pesan) | ' . date('Y-m-d_His');
        $data['is_operator'] = false;
        $this->_output['content'] = $this->load->view('admin/customer/list_no_order', $data, true);
        $this->_output['script_js'] = $this->load->view('admin/customer/js', '', true);
        $this->load->view('admin/template', $this->_output);
    }

    public function list_customer_no_order()
    {
        if ( ! $this->input->is_ajax_request()) {
            redirect(ADMIN_PATH . 'customer');
        }
        $this->load->library('datatables');
        $this->output->set_header('Content-Type:application/json; charset=utf-8');
        $this->datatables->select('id_customer, 
                                  no_npsn AS npsn, 
                                  school_name AS nama_sekolah,
                                  provinsi AS propinsi,
                                  kabupaten,
                                  kecamatan,
                                  alamat,
                                  phone AS telpon,
                                  email,
                                  name AS nama_kepsek,
                                  phone_kepsek,
                                  email_kepsek,
                                  operator,
                                  hp_operator,
                                  email_operator');
        $this->datatables->from($this->table);
        if (in_array($this->adm_level, [2, 3, 8])) {
            $this->datatables->where('kabupaten IN (SELECT aa.kabupaten_kota FROM employee_kabupaten_kota aa WHERE aa.id_employee = ' . $this->adm_id . ')');
        }
        $this->datatables->where('id_customer NOT IN (SELECT a.id_customer FROM orders a)');
        $this->datatables->edit_column('npsn', '<a href="' . base_url(ADMIN_PATH . '/customer/edit/$1') . '" title="Klik untuk detil">$2</a>', 'id_customer, npsn');
        $this->datatables->edit_column('nama_sekolah', '<a href="' . base_url(ADMIN_PATH . '/customer/edit/$1') . '" title="Klik untuk detil">$2</a>', 'id_customer, nama_sekolah');
        $this->output->set_output($this->datatables->generate());
    }

    public function add()
    {
        $data['groups'] = $this->mod_general->getWhere('group', 'active', 1, 'name', 'asc');
        $this->_output['content'] = $this->load->view('admin/customer/add', $data, true);
        $this->_output['script_js'] = $this->load->view('admin/customer/js', '', true);
        $this->load->view('admin/template', $this->_output);
    }

    # TODO : add logs and block permission for auditor
    public function addPost()
    {
        if ( ! $this->input->is_ajax_request()) {
            redirect(ADMIN_PATH . '/customer');
        }
        try {
            if (in_array($this->adm_level, $this->auditor_area)) {
                $callBack   = [   
                    "success"   => "false",
                    "message"   => "Maaf, anda tidak dapat melakukan proses ini."
                ];
            } else {
                $data = [
                    'no_npsn' => $this->input->post('no_npsn', true),
                    'jenjang' => $this->input->post('jenjang'),
                    'school_name' => $this->input->post('school_name', true),
                    'id_gender' => $this->input->post('id_gender'),
                    'email' => $this->input->post('email', true),
                    'name' => $this->input->post('name', true),
                    'id_group' => $this->input->post('id_group'),
                    'passwd' => sha1($this->input->post('passwd')),
                    'active' => 1,
                    'date_add' => date('Y-m-d H:i:s'),
                    'date_upd' => date('Y-m-d H:i:s')
                ];
                $this->db->trans_begin();
                $this->mod_general->addData($this->table, $data);
                if ($this->db->trans_status() === true) {
                    $this->db->trans_commit();
                    $callBack = [
                        'success' => 'true',
                        'message' => 'Data successfully added.'
                    ];
                    $this->session->set_flashdata('msg_success', 'Data dengan nama: <b>' . $data['name'] . '</b> berhasil <b>DITAMBAHKAN</b></p>');
                } else {
                    $this->db->trans_rollback();
                    $callBack = [
                        'success' => 'false',
                        'message' => 'Failed to add data.'
                    ];
                }
            }
            echo json_encode($callBack, true);
        } catch (Exception $e) {
            echo 'Caught exception: ', $e->getMessage(), "\n";
        }
    }

    public function edit($id)
    {
        $data['detil'] = $this->mod_general->detailData($this->table, 'id_customer', $id);
        if ($data['detil']) {
            $this->_output['content'] = $this->load->view('admin/customer/edit', $data, true);
            $this->_output['script_js'] = $this->load->view('admin/customer/js', '', true);
            $this->load->view('admin/template', $this->_output);
        } else {
            redirect(ADMIN_PATH . '/customer', 'refresh');
        }
    }

    public function editPopup($id)
    {
        if ( ! $this->input->is_ajax_request()) {
            return false;
        }

        // $data['jenjang'] = $this->mod_general->getAll('customer', 'jenjang, bentuk', '', 'bentuk ASC', 'jenjang, bentuk');
        $data['provinsi'] = $this->mod_general->getAll('customer', 'distinct(provinsi)', '', 'provinsi ASC');
        $data['bentuk']  = $this->mod_general->getAll('customer', 'distinct(bentuk)', '', 'bentuk ASC');
        $data['jenjang']  = $this->mod_general->getAll('customer', 'distinct(jenjang)', '', 'jenjang ASC');
        $data['zona'] = $this->mod_general->getAll('master_site', '*', '', 'id_site ASC');
        $data['detil'] = $this->mod_general->detailData('customer', 'id_customer', $id);
        $this->load->view('admin/customer/edit_popup', $data);
    }

    # TODO : add logs and block permission for auditor
    public function editPost()
    {
        if ( ! $this->input->is_ajax_request()) {
            redirect(ADMIN_PATH . '/customer');
        }
        try {
            if (in_array($this->adm_level, $this->auditor_area)) {
                $callBack   = [   
                    "success"   => "false",
                    "message"   => "Maaf, anda tidak dapat melakukan proses ini."
                ];
            } else {
                $id = $this->input->post('id_customer');
                $data = [
                    'no_npsn' => $this->input->post('no_npsn', true),
                    'school_name' => $this->input->post('school_name', true),
                    'bentuk' => $this->input->post('bentuk', true),
                    'jenjang' => $this->input->post('jenjang', true),
                    'phone' => $this->input->post('phone', true),
                    'email' => $this->input->post('email', true),
                    'zona' => $this->input->post('zona', true),
                    'alamat' => $this->input->post('alamat', true),
                    'provinsi' => $this->input->post('provinsi', true),
                    'kabupaten' => $this->input->post('kabupaten', true),
                    'kecamatan' => $this->input->post('kecamatan', true),
                    'desa' => $this->input->post('desa', true),
                    'kodepos' => $this->input->post('kodepos', true),
                    'name' => $this->input->post('name', true),
                    'nip_kepsek' => $this->input->post('nip_kepsek', true),
                    'phone_kepsek' => $this->input->post('phone_kepsek', true),
                    'email_kepsek' => $this->input->post('email_kepsek', true),
                    'nama_bendahara' => $this->input->post('nama_bendahara', true),
                    'nip_bendahara' => $this->input->post('nip_bendahara', true),
                    'phone_bendahara' => $this->input->post('phone_bendahara', true),
                    'operator' => $this->input->post('operator', true),
                    'hp_operator' => $this->input->post('hp_operator', true),
                    'email_operator' => $this->input->post('email_operator', true),
                    'date_upd' => date('Y-m-d H:i:s')
                ];
                $this->db->trans_begin();
                $this->mod_general->updateData($this->table, $data, 'id_customer', $id);
                if ($this->db->trans_status() === true) {
                    $this->db->trans_commit();
                    $callBack = [
                        'success' => 'true',
                        'message' => 'Data successfully updated.'
                    ];
                    $this->session->set_flashdata('msg_success', 'Data profil sekolah berhasil <b>DIPERBARUI</b></p>');
                } else {
                    $this->db->trans_rollback();
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

    # TODO : add logs and block permission for auditor
    public function delete()
    {
        if ( ! $this->input->is_ajax_request()) {
            redirect(ADMIN_PATH . '/customer');
        }
        try {            
            if (in_array($this->adm_level, $this->auditor_area)) {
                $this->session->set_flashdata('msg_failed', 'Maaf, anda tidak dapat melakukan proses ini');
            } else {
                $id = $this->input->post('id');
                $this->db->trans_begin();
                $this->mod_general->deleteData($this->table, 'id_customer', $id);
                if ($this->db->trans_status() === true) {
                    $this->db->trans_commit();
                    $this->session->set_flashdata('msg_success', 'Data dengan ID: <b>' . $id . '</b> berhasil <b>DIHAPUS</b></p>');
                } else {
                    $this->db->trans_rollback();
                    $this->session->set_flashdata('msg_failed', 'Data dengan ID: <b>' . $id . '</b> gagal <b>DIHAPUS</b></p>');
                }
            }
            echo json_encode(['success' => 'true']);
        } catch (Exception $e) {
            echo 'Caught exception: ', $e->getMessage(), "\n";
        }
    }

    public function getKabupatenByProvinsi()
    {
        $provinsi = $this->input->post('provinsi');
       
        if ($provinsi) {
            $kabupaten = $this->mod_general->getAll('customer', 'distinct(kabupaten)', 'provinsi = "' . $provinsi . '"', 'kabupaten ASC');
            $callBack = [
                "row"     => $kabupaten,
                "success" => true,
                'csrfName' => $this->security->get_csrf_token_name(),
                'csrfHash' => $this->security->get_csrf_hash()
            ];
        } else {
            $callBack = [
                "success" => false,
                'csrfName' => $this->security->get_csrf_token_name(),
                'csrfHash' => $this->security->get_csrf_hash()
            ];
        }
        echo json_encode($callBack);
    }
}
