<?php
defined('BASEPATH') or exit('No direct script access allowed');

/**
 * @property Datatables $datatables
 * @property Mod_general $mod_general
 * @property Mod_mitra $mod_mitra
 * @property Mymail $mymail
 */
class Mitra extends MY_Controller
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
        $this->_output['content'] = $this->load->view('admin/mitra/list', '', true);
        $this->_output['script_js'] = $this->load->view('admin/mitra/js', '', true);
        $this->load->view('admin/template', $this->_output);
    }

    public function listMitra()
    {
        if ( ! $this->input->is_ajax_request()) {
            redirect(ADMIN_PATH . '/mitra');
        }
        $this->load->library('datatables');
        $this->output->set_header('Content-Type:application/json; charset=utf-8');
        $this->datatables->select("a.id_employee AS id_employee, 
                                   a.code AS kode, 
                                   b.identity_code AS identitas,
                                   LOWER(a.email) AS email,
                                   UPPER(a.name) AS nama_mitra,
                                   b.gender AS jekel, 
                                   b.address as alamat_mitra,
                                   a.telp AS telpon,
                                   b.name_npwp as name_npwp,
                                   b.no_npwp as no_npwp,
                                   b.address_npwp as alamat_npwp,
                                   d.bank_alias as bank_nama,
                                   b.bank_account_number as bank_no,
                                   b.bank_account_name as bank_an,
                                   e.name AS kode_korwil,
                                   c.name AS referensi,
                                   CASE b.is_activated WHEN 0 THEN CONCAT('<span class=\'label label-warning\'>Menunggu</span>') WHEN 1 THEN CONCAT('<span class=\'label label-primary\'>Ya</span>') END AS aktifasi,
                                   CASE a.active WHEN 0 THEN CONCAT('<span class=\'label label-danger\'>Nonaktif</span>') WHEN 1 THEN CONCAT('<span class=\'label label-success\'>Aktif</span>') END AS status,
                                   IF(ISNULL(tb.`mikon_employee_id`), 'Tidak aktif', 'Aktif') AS status_kontrak");


        $this->datatables->from($this->table . ' a');
        $this->datatables->join('mitra_profile b', 'b.id_employee=a.id_employee', 'inner');
        $this->datatables->join('employee c', 'c.code=b.code_referral', 'left');
        $this->datatables->join('master_bank d', 'd.id=b.bank_account_type', 'inner');
        $this->datatables->join('employee e', 'e.code=b.code_korwil', 'inner');
        $this->datatables->join("(SELECT * FROM mitra_kontrak WHERE '".date('Y-m-d')."' BETWEEN mikon_tanggal AND mikon_tanggal_akhir GROUP BY mikon_employee_id)tb", 'tb.mikon_employee_id=b.id_employee', 'left');
        if ($this->adm_level == 8) {
            $this->datatables->join('employee_kabupaten_kota d', 'd.id_employee=c.id_employee', 'inner');
            $this->datatables->where('d.kabupaten_kota IN (SELECT aa.kabupaten_kota FROM employee_kabupaten_kota aa WHERE aa.id_employee = ' . $this->adm_id . ')');
        } elseif ($this->adm_level == 3) {
            $this->datatables->where('b.code_korwil', $this->adm_code);
        }
        $this->datatables->where('a.level', 4);
        $this->datatables->edit_column('kode', '<a href="' . base_url(ADMIN_PATH . '/mitra/detail/$1') . '">$2</a>', 'id_employee, kode');
        $this->output->set_output($this->datatables->generate());
    }

    public function detail($id)
    {
        if ($id && is_numeric($id)) {
            $data['detil'] = $this->mod_mitra->getDetail($id);
            if ($data['detil']) {
                if (in_array($this->adm_level, $this->backoffice_superadmin_area)) {
                    $data['korwil'] = $this->mod_mitra->getAll("employee", "*", "level=3 and active=1", "code asc, name asc");
                }
                $data['referensi'] = $this->mod_mitra->getAll("employee", "*", "(level='3' OR level='4') and active = 1", "code asc, level asc, name asc");
                $data['listBank'] = $this->mod_mitra->getAll("master_bank", "*", "status = 1", "id asc");
                $data['adm_level'] = $this->adm_level;

                $data['data_kontrak'] = $this->mod_mitra->get_data_kontrak($id);

                // $this->_output['content'] = $this->load->view('admin/mitra/edit', $data, true);
                $this->_output['content'] = $this->load->view('admin/mitra/detail', $data, true);
                $this->_output['script_css'] = $this->load->view('admin/mitra/css', '', true);
                $this->_output['script_js'] = $this->load->view('admin/mitra/js', '', true);
                $this->load->view('admin/template', $this->_output);
            } else {
                redirect(ADMIN_PATH . '/mitra', 'refresh');
            }
        } else {
            redirect(ADMIN_PATH . '/mitra', 'refresh');
        }
    }

    public function update($id)
    {
        if ($id && is_numeric($id)) {
            $data['detil'] = $this->mod_mitra->getDetail($id);
            if ($data['detil']) {
                if (in_array($this->adm_level, $this->backoffice_superadmin_area)) {
                    $data['korwil'] = $this->mod_mitra->getAll("employee", "*", "level=3 and active=1", "code asc, name asc");
                }
                $data['referensi'] = $this->mod_mitra->getAll("employee", "*", "(level='3' OR level='4') and active = 1", "code asc, level asc, name asc");
                $data['listBank'] = $this->mod_mitra->getAll("master_bank", "*", "status = 1", "id asc");
                $data['adm_level'] = $this->adm_level;

                $data['data_kontrak'] = $this->mod_mitra->get_data_kontrak($id);

                // $this->_output['content'] = $this->load->view('admin/mitra/edit', $data, true);
                // // $this->_output['content'] = $this->load->view('admin/mitra/detail', $data, true);
                // $this->_output['script_css'] = $this->load->view('admin/mitra/css', '', true);
                // $this->_output['script_js'] = $this->load->view('admin/mitra/js', '', true);
                // $this->load->view('admin/template', $this->_output);


                $data['js'] = $this->load->view('admin/mitra/js', '', true);
                $this->load->view('admin/mitra/edit_popup', $data);
            } else {
                redirect(ADMIN_PATH . '/mitra', 'refresh');
            }
        } else {
            redirect(ADMIN_PATH . '/mitra', 'refresh');
        }
    }

    # TODO : add logs and block permission for auditor
    public function updatePost()
    {
        if ( ! $this->input->is_ajax_request()) {
            redirect(ADMIN_PATH . '/mitra');
        }
        try {
            if (in_array($this->adm_level, $this->auditor_area)) {
                $callBack   = [   
                    "success"   => "false",
                    "message"   => "Maaf, anda tidak dapat melakukan proses ini."
                ];
            } else {
                $id = $this->input->post('id_employee');
                $currentStatus = $this->input->post('current_status');
                $newStatus = $this->input->post('active');
                $isActivated = $this->input->post('is_activated');
                $emailSales = trim(strtolower($this->input->post('email', true)));
                $emailKorwil = trim(strtolower($this->input->post('email_korwil', true)));
                $percentTax = trim($this->input->post('percent_tax', true))/100;
                $npwp = trim($this->input->post('no_npwp', true));
                $codeReferral = $this->input->post('code_referral');
                $dataEmployee = [
                    'name' => trim($this->input->post('name', true)),
                    'telp' => trim($this->input->post('telp', true)),
                    'active' => $newStatus
                ];
                if ($npwp == trim($this->input->post('identity_code', true))) {
                    $validPercentTax = 0.030;
                } elseif ( ! empty($npwp) || $npwp != '') {
                    $validPercentTax = $percentTax;
                } else {
                    $validPercentTax = 0.030;
                }
                $dataMitra = [
                    'identity_code' => trim($this->input->post('identity_code', true)),
                    'gender' => $this->input->post('gender'),
                    'address' => trim($this->input->post('address', true)),
                    'name_npwp' => trim($this->input->post('name_npwp', true)),
                    'no_npwp' => $npwp,
                    'address_npwp' => trim($this->input->post('address_npwp', true)),
                    'code_referral' => $codeReferral,
                    'bank_account_number' => trim($this->input->post('bank_account_number', true)),
                    'bank_account_name' => trim($this->input->post('bank_account_name', true)),
                    'bank_account_type' => $this->input->post('bank_account_type'),
                    'percent_comission' => trim($this->input->post('percent_comission', true))/100,
                    'percent_tax' => $validPercentTax,
                    'date_modified' => date('Y-m-d H:i:s')
                ];
                if (0==$isActivated) {
                    $dataEmployee['email'] = $emailSales;
                }
                $this->db->trans_begin();
                $updateEmployee = $this->mod_general->updateData($this->table, $dataEmployee, 'id_employee', $id);
                if ($updateEmployee) {
                    $updateMitra = $this->mod_general->updateData('mitra_profile', $dataMitra, 'id_employee', $id);
                    if ($updateMitra) {
                        if ($currentStatus == 0 && $newStatus == 1 && $isActivated == 0) {
                            $this->mod_general->updateData('mitra_profile', ['is_activated' => 1], 'id_employee', $id);
                            $dataKorwilSales = [
                                'email_sales' => $emailSales,
                                'email_korwil' => $emailKorwil
                            ];
                            $korwilSalesInsert = $this->db->insert('korwil_sales', $dataKorwilSales);
                            if ($korwilSalesInsert) {
                                $this->mailNotification($emailSales);
                            }
                        }
                        $this->db->trans_commit();
                        $callBack = [
                            'success' => 'true',
                            'message' => 'Data successfully updated.'
                        ];
                        $this->session->set_flashdata('msg_success', 'Data mitra: <b>' . $dataEmployee['name'] . '</b> berhasil <b>DIPERBARUI</b></p>');
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

    private function mailNotification($to)
    {
        $this->load->library('mymail');
        $content = '<p>Selamat, Pendaftaran Diverifikasi</p>
                    <p>Pendaftaran anda sebagai Mitra Buku Sekolah - Gramedia telah diverifikasi dan diaktifasi.</p>
                    <p>Silahkan akses aplikasi kami di: <b><a href="' . base_url('backoffice') . '">' . base_url('backoffice') . '</a></b></p>
                    <br><br>
                    <p>Salam Pendidikan,</p>';
        $this->mymail->send('Pendaftaran Mitra Buku Sekolah Diverifikasi', $to, $content);
    }
}
