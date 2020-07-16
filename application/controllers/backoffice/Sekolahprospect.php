<?php
defined('BASEPATH') or exit('No direct script access allowed');

/**
 * @property Datatable $datatable
 * @property Datatables $datatables
 * @property Excel $excel
 * @property Mod_sekolahprospect $mod_sekolahprospect
 * @property Mymail $mymail
 * @property Dompdf_gen $dompdf_gen
 */
class Sekolahprospect extends MY_Controller
{
    private $_output;

    public function __construct()
    {
        parent::__construct();
        $this->load->model('mod_sekolahprospect');
        $this->_output = [];
    }

    public function index()
    {
        $data['page_title'] = 'List Semua Sekolah';
        $this->_output['content'] = $this->load->view('admin/sekolahprospect/listAllUsers', $data, true);
        $this->_output['script_js'] = $this->load->view('admin/sekolahprospect/js', '', true);
        $this->load->view('admin/template', $this->_output);
    }

    public function listAllUsers()
    {
        if (!$this->input->is_ajax_request()) {
            return false;
        }
        $this->load->library('datatables');
        $this->output->set_header('Content-Type:application/json; charset=utf-8');
        $this->datatables->select('a.id_customer AS id_customer, 
                                   a.no_npsn AS npsn, 
                                   a.school_name AS nama_sekolah, 
                                   a.provinsi AS propinsi, 
                                   a.kabupaten AS kabupaten, 
                                   CASE a.status_prospect WHEN 1 THEN CONCAT("<center><span class=\'label label-success\'>Tersedia</span></center>") WHEN 2 THEN CONCAT("<center><span class=\'label label-warning\'>Diajukan</span></center>") WHEN 3 THEN CONCAT("<center><span class=\'label label-danger\'>Disetujui</span></center>") END AS status_prospek, 
                                   IF (a.id_mitra = "", CONCAT("<center>", "-", "</center>"), b.name) AS nama_mitra,
                                   IF (a.date_prospect_expired = "0000-00-00", CONCAT("<center>", "-", "</center>"), a.date_prospect_expired) AS tgl_expired');
        $this->datatables->from('customer a');
        $this->datatables->join('employee b', 'b.id_employee=a.id_mitra ', 'left');
        if ($this->adm_level == 4) {
            $this->datatables->join('employee_kabupaten_kota c', 'a.kabupaten = c.kabupaten_kota', 'inner');
            $this->datatables->join('employee d', 'c.id_employee = d.id_employee', 'inner');
            $this->datatables->join('mitra_profile e', 'd.code = e.code_korwil', 'inner');
            $this->datatables->where('e.id_employee = ' . $this->adm_id);
        } elseif (in_array($this->adm_level, [2, 3, 8])) {
            $this->datatables->join('employee_kabupaten_kota c', 'a.kabupaten = c.kabupaten_kota', 'inner');
            $this->datatables->where('c.id_employee = ' . $this->adm_id);
        }
        $this->datatables->where('a.no_npsn <> ""');
        $this->datatables->edit_column('npsn', '<a href="' . base_url(ADMIN_PATH . '/sekolahprospect/detail/$1') . '">$2</a>', 'id_customer, npsn');
        $this->output->set_output($this->datatables->generate());
    }

    public function request()
    {
        if (in_array($this->adm_level, [3, 4])) {
            $data['page_title'] = 'List Request';
            $data['user_code'] = $this->adm_code;
            $this->_output['content'] = $this->load->view('admin/sekolahprospect/list', $data, true);
            $this->_output['script_js'] = $this->load->view('admin/sekolahprospect/js', '', true);
            $this->load->view('admin/template', $this->_output);
        } else {
            redirect(ADMIN_PATH . '/sekolahprospect', 'refresh');
        }
    }

    public function listRequest($code)
    {
        if ( ! $this->input->is_ajax_request()) {
            redirect(ADMIN_PATH, 'refresh');
        }
        $this->load->library('datatables');
        $this->output->set_header('Content-Type:application/json; charset=utf-8');
        $this->datatables->select('a.id_customer AS id_customer, 
                                   b.id AS id_prospek, 
                                   a.no_npsn AS npsn, 
                                   a.school_name AS nama_sekolah, 
                                   a.provinsi AS propinsi, 
                                   a.kabupaten AS kabupaten, 
                                   CASE a.status_prospect WHEN 1 THEN CONCAT("<center><span class=\'label label-success\'>Tersedia</span></center>") WHEN 2 THEN CONCAT("<center><span class=\'label label-warning\'>Diajukan</span></center>") WHEN 3 THEN CONCAT("<center><span class=\'label label-danger\'>Disetujui</span></center>") END AS status_prospek, 
                                   IF (a.id_mitra = "", CONCAT("<center>", "-", "</center>"), c.name) AS nama_mitra,
                                   b.date_start AS tgl_awal,
                                   IF (b.date_finish = "0000-00-00", CONCAT("<center>", "-", "</center>"), b.date_finish) AS tgl_akhir,
                                   b.notes AS notes');
        $this->datatables->from('customer a');
        $this->datatables->join('customer_prospect_history b','b.id_customer=a.id_customer', 'inner');
        if ($this->adm_level == 4) {
            $this->datatables->join('employee c', 'c.id_employee=b.id_mitra', 'inner');
            $this->datatables->where('c.code = ' . $code);
        } elseif ($this->adm_level ==3) {
            $this->datatables->join('employee c', 'c.id_employee=b.id_mitra', 'inner');
            $this->datatables->join('mitra_profile d', 'd.id_employee=c.id_employee','inner');
            $this->datatables->where('d.code_korwil = ' . $code);
            $this->datatables->edit_column('notes', '<div class="text-center"><input type="checkbox" id="mitra_$1" value="$1" class="checkc_' . $this->adm_id . '"></div>', 'id_prospek');
        }
        $this->datatables->where('a.no_npsn <> ""');
        $this->datatables->edit_column('npsn', '<a href="' . base_url(ADMIN_PATH . '/sekolahprospect/detail/$1') . '">$2</a>', 'id_customer, npsn');
        $this->output->set_output($this->datatables->generate());
    }

    public function detail($id, $code = null)
    {
        if ($id && is_numeric($id)) {
            if ($code) {
                if ($this->adm_level == 3 || $this->adm_level == 8) {
                    $data['last_page'] = "List Sales Prospek";
                } elseif ($this->adm_level == 4) {
                    $data['last_page'] = "List Request";
                }
                $data['link_page'] = "/sekolahprospect/request";
            } else {
                $data['last_page'] = "List Semua Sekolah";
                $data['link_page'] = "/sekolahprospect";
            }
            $data['customer'] = $this->mod_sekolahprospect->getAll('customer', '*', 'id_customer = ' . $id)[0];
            $data['customer_history'] = $this->mod_sekolahprospect->getProspectHistory('', 'id_customer = ' . $id);
            $data['prospect_history'] = $this->mod_sekolahprospect->getStateHistoryByCustomer($id, 'a.*, c.name');
            $data['request_sales'] = false;
            if ($data['customer']->status_prospect == 2) {
                $own_request = $this->mod_sekolahprospect->getProspectRequest('', 'customer.id_customer = ' . $id . ' AND mitra_profile.code_korwil = ' . $this->adm_code);
                if ($own_request > 0) {
                    $data['request_sales'] = true;
                    $data['request_sales_data'] = $this->mod_sekolahprospect->getSalesProspectRequest('employee.*, customer.date_prospect_start, customer_prospect_history.id as id_customer_prospect, customer_prospect_history.notes', 'customer.id_mitra = ' . $data['customer']->id_mitra . ' AND customer.id_customer = ' . $id . ' AND customer_prospect_history.status_prospect = 1')[0];
                }
            }
            $this->_output['content'] = $this->load->view('admin/sekolahprospect/detail', $data, true);
            $this->_output['script_css'] = $this->load->view('admin/sekolahprospect/css', '', true);
            $this->_output['script_js'] = $this->load->view('admin/sekolahprospect/js', '', true);
            $this->load->view('admin/template', $this->_output);
        } else {
            redirect(ADMIN_PATH . '/sekolahprospect/' . $code, 'refresh');
        }
    }

    public function addRequest()
    {
        if ($this->adm_level != 4) {
            $callBack = [
                "message" => "Tidak ada hak akses!",
                "success" => false
            ];
            echo json_encode($callBack);
        } else {
            $idCustomer = $this->input->post('id_customer');
            if ($idCustomer) {
                $this->db->trans_begin();
                $dataRequest1 = [
                    'id_customer' => $idCustomer,
                    'id_mitra' => $this->adm_id,
                    'date_start' => $this->input->post('req_startdate'),
                    'date_finish' => date('Y-m-d', strtotime($this->input->post('req_startdate') . ' + 7 days')),
                    'status_prospect' => 1,
                    'notes' => $this->input->post('req_notes'),
                    'date_add' => date('Y-m-d H:i:s'),
                    'date_modified' => date('Y-m-d H:i:s')
                ];
                $idCustomerProspect = $this->mod_sekolahprospect->add('customer_prospect_history', $dataRequest1);
                if ($idCustomerProspect) {
                    $dataRequest2 = [
                        'id_mitra' => $this->adm_id,
                        'date_prospect_start' => $this->input->post('req_startdate'),
                        'date_prospect_expired' => date('Y-m-d', strtotime($this->input->post('req_startdate') . ' + 7 days')),
                        'status_prospect' => 2,
                        'date_upd' => date('Y-m-d H:i:s')
                    ];
                    $this->mod_sekolahprospect->edit('customer', 'id_customer = ' . $idCustomer, $dataRequest2);
                    $dataRequest3 = [
                        'id_customer_prospect_history' => $idCustomerProspect,
                        'id_employee' => $this->adm_id,
                        'status_prospect' => 1,
                        'duration_days' => 7,
                        'notes' => $this->input->post('req_notes'),
                        'date_add' => date('Y-m-d H:i:s'),
                        'date_modified' => date('Y-m-d H:i:s')
                    ];
                    $this->mod_sekolahprospect->add('sekolah_prospect_history', $dataRequest3);
                    $dataMitra = $this->mod_sekolahprospect->getAll('mitra_profile', '*', 'id_employee = ' . $this->adm_id)[0];
                    $dataKorwil = $this->mod_sekolahprospect->getAll('employee', '*', 'code = ' . $dataMitra->code_korwil)[0];
                    $dataSales = $this->mod_sekolahprospect->getAll('employee', '*', 'id_employee = ' . $this->adm_id)[0];
                    if ($this->db->trans_status() === true) {
                        if ($dataKorwil) {
                            $this->load->library('mymail');
                            $korwilSubject = "Pengajuan prospek sekolah - Buku Sekolah Mitra Edukasi Nusantara";
                            $korwilTo = [$dataKorwil->email];
                            $korwilContent = "<p>Telah mengajukan prospek sekolah dengan detil:<br></p>
                                        <table>
                                            <tr>
                                                <td colspan='3'>Yang mengajukan, </td>
                                            </tr>
                                            <tr>
                                                <td>Nama</td>
                                                <td>:</td>
                                                <td>" . $dataSales->name . "</td>
                                            </tr>
                                            <tr>
                                                <td>Email</td>
                                                <td>:</td>
                                                <td>" . $dataSales->email . "</td>
                                            </tr>
                                            <tr>
                                                <td colspan='3'><br></td>
                                            </tr>
                                            <tr>
                                                <td colspan='3'>Dengan data sekolah, </td>
                                            </tr>
                                            <tr>
                                                <td>Nama Sekolah</td>
                                                <td>:</td>
                                                <td>" . $this->input->post('cust_name') . "</td>
                                            </tr>
                                            <tr>
                                                <td>Alamat Sekolah</td>
                                                <td>:</td>
                                                <td>" . $this->input->post('cust_address') . "</td>
                                            </tr>
                                            <tr>
                                                <td>Email Sekolah</td>
                                                <td>:</td>
                                                <td>" . $this->input->post('cust_email') . "</td>
                                            </tr>
                                            <tr>
                                                <td>Telepon Sekolah</td>
                                                <td>:</td>
                                                <td>" . $this->input->post('cust_phone') . "</td>
                                            </tr>
                                        </table>

                                        <p>Mohon untuk segera dilakukan konfirmasi dan verifikasi.<br><br>Terima kasih</p>";
                            $this->mymail->send($korwilSubject, $korwilTo, $korwilContent);
                        }
                        $this->db->trans_commit();
                        $this->session->set_flashdata('message', '<div class="alert alert-success alert-dismissable"><span class="glyphicon glyphicon-ok-circle"></span>&nbsp; Pengajuan prospek anda untuk: <b>' . $this->input->post('cust_name') . '</b> berhasil <b>DIAJUKAN</b>!.</div>');
                        $callBack = [
                            "message" => "Pengajuan prospek berhasil!",
                            "success" => true
                        ];
                        echo json_encode($callBack);
                    } else {
                        $this->db->trans_rollback();
                        $this->session->set_flashdata('message', '<div class="alert alert-danger alert-dismissable"><span class="glyphicon glyphicon-remove-circle"></span>&nbsp; Pengajuan prospek anda untuk: <b>' . $this->input->post('cust_name') . '</b> gagal!.</div>');
                        $callBack = [
                            "message" => "Pengajuan prospek gagal!",
                            "success" => false
                        ];
                        echo json_encode($callBack);
                    }
                } else {
                    $callBack = [
                        "message" => "Gagal mengajukan prospek!",
                        "success" => false
                    ];
                    echo json_encode($callBack);
                }
            } else {
                $callBack = [
                    "message" => "Sekolah tidak terdeteksi!",
                    "success" => false
                ];
                echo json_encode($callBack);
            }
        }
    }

    public function updateRequest($id)
    {
        if ($this->adm_level != 3) {
            $callBack = [
                "message" => "Tidak ada hak akses!",
                "success" => false
            ];
            echo json_encode($callBack);
        } else {
            if ($id) {
                $this->db->trans_begin();
                $idCustomerProspect = $this->input->post('id_customer_prospect');
                $idMitra = $this->input->post('id_mitra');
                $dateStart = $this->input->post('req_startdate_acc');
                $accDays = $this->input->post('acc_days');
                $dateFinish = date('Y-m-d', strtotime($dateStart . ' + ' . $accDays . ' days'));
                $dataAccept1 = [
                    'date_start' => $dateStart,
                    'date_finish' => $dateFinish,
                    'status_prospect' => 2,
                    'date_modified' => date('Y-m-d H:i:s')
                ];
                $this->mod_sekolahprospect->edit('customer_prospect_history', 'id = ' . $idCustomerProspect, $dataAccept1);
                $dataAccept2 = [
                    'date_prospect_start' => $dateStart,
                    'date_prospect_expired' => $dateFinish,
                    'status_prospect' => 3,
                    'date_upd' => date('Y-m-d H:i:s')
                ];
                $this->mod_sekolahprospect->edit('customer', 'id_customer = ' . $id, $dataAccept2);
                $dataRequest3 = [
                    'id_customer_prospect_history' => $idCustomerProspect,
                    'id_employee' => $this->adm_id,
                    'status_prospect' => 2,
                    'duration_days' => $accDays,
                    'date_add' => date('Y-m-d H:i:s'),
                    'date_modified' => date('Y-m-d H:i:s')
                ];
                $this->mod_sekolahprospect->add('sekolah_prospect_history', $dataRequest3);
                $dataSalesPerson = $this->mod_sekolahprospect->getAll('employee', '*', 'id_employee = ' . $idMitra)[0];
                if ($this->db->trans_status() === true) {
                    if ($dataSalesPerson) {
                        $this->load->library('mymail');
                        $salesSubject = "Persetujuan prospek sekolah - Buku Sekolah Mitra Edukasi Nusantara";
                        $salesTo = [$dataSalesPerson->email];
                        $salesContent = "<p>Pengajuan prospek sekolah anda telah disetujui dengan detil sekolah:<br></p>
                                    <table>
                                        <tr>
                                            <td>Nama Sekolah</td>
                                            <td>:</td>
                                            <td>" . $this->input->post('cust_name') . "</td>
                                        </tr>
                                        <tr>
                                            <td>Alamat Sekolah</td>
                                            <td>:</td>
                                            <td>" . $this->input->post('cust_address') . "</td>
                                        </tr>
                                        <tr>
                                            <td>Email Sekolah</td>
                                            <td>:</td>
                                            <td>" . $this->input->post('cust_email') . "</td>
                                        </tr>
                                        <tr>
                                            <td>Telepon Sekolah</td>
                                            <td>:</td>
                                            <td>" . $this->input->post('cust_phone') . "</td>
                                        </tr>
                                        <tr>
                                            <td colspan='3'><br></td>
                                        </tr>
                                        <tr>
                                            <td colspan='3'>Dengan lama waktu yang telah ditentukan : </td>
                                        </tr>
                                        <tr>
                                            <td>Tanggal Mulai</td>
                                            <td>:</td>
                                            <td>" . date('d-m-Y', strtotime($dateStart)) . "</td>
                                        </tr>
                                        <tr>
                                            <td>Tanggal Selesai</td>
                                            <td>:</td>
                                            <td>" . date('d-m-Y', strtotime($dateFinish)) . "</td>
                                        </tr>
                                        <tr>
                                            <td>Lama Hari</td>
                                            <td>:</td>
                                            <td>" . $accDays . " Hari</td>
                                        </tr>
                                    </table>

                                    <p>Silahkan melakukan prospek kepada sekolah yang telah disetujui.<br><br>Terima kasih</p>";
                        $this->mymail->send($salesSubject, $salesTo, $salesContent);
                    }
                    $this->db->trans_commit();
                    $this->session->set_flashdata('message', '<div class="alert alert-success alert-dismissable"><span class="glyphicon glyphicon-ok-circle"></span>&nbsp; Persetujuan prospek mitra: <b>' . $dataSalesPerson->name . '</b> berhasil <b>DISETUJUI</b>!.</div>');
                    $callBack = [
                        "message" => "Persetujuan prospek sekolah mitra berhasil!",
                        "success" => true
                    ];
                    echo json_encode($callBack);
                } else {
                    $this->db->trans_rollback();
                    $this->session->set_flashdata('message', '<div class="alert alert-danger alert-dismissable"><span class="glyphicon glyphicon-remove-circle"></span>&nbsp; Persetujuan prospek mitra: <b>' . $dataSalesPerson->name . '</b> gagal!.</div>');
                    $callBack = [
                        "message" => "Persetujuan prospek sekolah mitra gagal!",
                        "success" => false
                    ];
                    echo json_encode($callBack);
                }
            } else {
                $callBack = [
                    "message" => "Sekolah tidak terdeteksi!",
                    "success" => false
                ];
                echo json_encode($callBack);
            }
        }
    }

    public function updateMultipleRequest()
    {
        if ($this->adm_level != 3) {
            $this->session->set_flashdata('message', '<div class="alert alert-danger alert-dismissable"><span class="glyphicon glyphicon-remove-circle"></span>&nbsp; Tidak ada hak akses!</div>');
            redirect(ADMIN_PATH . '/sekolahprospect/request', 'refresh');
        } else {
            $this->load->library('mymail');
            $idCustomerProspect = $this->input->post('id');
            $accDays = 7;
            if ($idCustomerProspect) {
                $this->db->trans_begin();
                $selects = "
                    customer_prospect_history.*,
                    customer.school_name as cust_name,
                    customer.alamat as cust_address,
                    customer.email as cust_email,
                    customer.phone as cust_phone,
                    employee.email as mitra_email
                ";
                $dataCustomerProspect = $this->mod_sekolahprospect->getSalesProspectRequest($selects, 'customer_prospect_history.id in (' . $idCustomerProspect . ')');
                foreach ($dataCustomerProspect as $key => $value) {
                    $dateFinish = date('Y-m-d', strtotime($value->date_start . ' + ' . $accDays . ' days'));
                    $dataAccept1 = [
                        'date_finish' => $dateFinish,
                        'status_prospect' => 2,
                        'date_modified' => date('Y-m-d H:i:s')
                    ];
                    $this->mod_sekolahprospect->edit('customer_prospect_history', 'id = ' . $value->id, $dataAccept1);
                    $dataAccept2 = [
                        'date_prospect_expired' => $dateFinish,
                        'status_prospect' => 3,
                        'date_upd' => date('Y-m-d H:i:s')
                    ];
                    $this->mod_sekolahprospect->edit('customer', 'id_customer = ' . $value->id_customer, $dataAccept2);
                    $dataRequest3 = [
                        'id_customer_prospect_history' => $value->id,
                        'id_employee' => $this->adm_id,
                        'status_prospect' => 2,
                        'duration_days' => $accDays,
                        'date_add' => date('Y-m-d H:i:s'),
                        'date_modified' => date('Y-m-d H:i:s'),
                    ];
                    $idHistory = $this->mod_sekolahprospect->add('sekolah_prospect_history', $dataRequest3);
                    if ($idHistory) {
                        $salesSubject = "Persetujuan prospek sekolah - Buku Sekolah Mitra Edukasi Nusantara";
                        $salesTo = [$value->mitra_email];
                        $salesContent = "<p>Pengajuan prospek sekolah anda telah disetujui dengan detil sekolah:<br></p>
                                    <table>
                                        <tr>
                                            <td>Nama Sekolah</td>
                                            <td>:</td>
                                            <td>" . $value->cust_name . "</td>
                                        </tr>
                                        <tr>
                                            <td>Alamat Sekolah</td>
                                            <td>:</td>
                                            <td>" . $value->cust_address . "</td>
                                        </tr>
                                        <tr>
                                            <td>Email Sekolah</td>
                                            <td>:</td>
                                            <td>" . $value->cust_email . "</td>
                                        </tr>
                                        <tr>
                                            <td>Telepon Sekolah</td>
                                            <td>:</td>
                                            <td>" . $value->cust_phone . "</td>
                                        </tr>
                                        <tr>
                                            <td colspan='3'><br></td>
                                        </tr>
                                        <tr>
                                            <td colspan='3'>Dengan lama waktu yang telah ditentukan : </td>
                                        </tr>
                                        <tr>
                                            <td>Tanggal Mulai</td>
                                            <td>:</td>
                                            <td>" . date('d-m-Y', strtotime($value->date_start)) . "</td>
                                        </tr>
                                        <tr>
                                            <td>Tanggal Selesai</td>
                                            <td>:</td>
                                            <td>" . date('d-m-Y', strtotime($dateFinish)) . "</td>
                                        </tr>
                                        <tr>
                                            <td>Lama Hari</td>
                                            <td>:</td>
                                            <td>" . $accDays . " Hari</td>
                                        </tr>
                                    </table>

                                    <p>Silahkan melakukan prospek kepada sekolah yang telah disetujui.<br><br>Terima kasih</p>";
                        $this->mymail->send($salesSubject, $salesTo, $salesContent);
                    } else {
                        $this->session->set_flashdata('message', '<div class="alert alert-danger alert-dismissable"><span class="glyphicon glyphicon-remove-circle"></span>&nbsp; Gagal menyetujui pengajuan prospek mitra!</div>');
                        redirect(ADMIN_PATH . '/sekolahprospect/request', 'refresh');
                    }
                }
                if ($this->db->trans_status() === true) {
                    $this->db->trans_commit();
                    $this->session->set_flashdata('message', '<div class="alert alert-success alert-dismissable"><span class="glyphicon glyphicon-ok-circle"></span>&nbsp; Persetujuan prospek sekolah mitra <b>BERHASIL DISETUJUI</b>!</div>');
                    redirect(ADMIN_PATH . '/sekolahprospect/request', 'refresh');
                } else {
                    $this->db->trans_rollback();
                    $this->session->set_flashdata('message', '<div class="alert alert-danger alert-dismissable"><span class="glyphicon glyphicon-remove-circle"></span>&nbsp; Persetujuan prospek sekolah mitra <b>GAGAL DISETUJUI</b>!</div>');
                    redirect(ADMIN_PATH . '/sekolahprospect/request', 'refresh');
                }
            } else {
                $this->session->set_flashdata('message', '<div class="alert alert-danger alert-dismissable"><span class="glyphicon glyphicon-remove-circle"></span>&nbsp; Sekolah tidak terdeteksi!</div>');
                redirect(ADMIN_PATH . '/sekolahprospect/request', 'refresh');
            }
        }
    }
}
