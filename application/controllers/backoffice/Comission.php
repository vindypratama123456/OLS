<?php
defined('BASEPATH') or exit('No direct script access allowed');

use GuzzleHttp\Client;
use GuzzleHttp\Exception\ClientException;
use GuzzleHttp\Psr7;

/**
 * @property Datatables $datatables
 * @property Mod_general $mod_general
 * @property Mod_comission $mod_comission
 * @property Mod_mitra $mod_mitra
 */
class Comission extends MY_Controller
{
    private $_output;
    private $arrAccessKorwil;
    private $arrAccessKorwilSales;
    private $arrAccessFinance;
    private $client;
    private $tblPayout;

    public function __construct()
    {
        parent::__construct();
        if ( ! in_array($this->adm_level, $this->backoffice_area, true)) {
            redirect(ADMIN_PATH, 'refresh');
        }
        $this->load->model('mod_general');
        $this->load->model('mod_comission');
        $this->load->model('mod_mitra');
        $this->tblPayout = 'payout_detail';
        $this->arrAccessKorwil = [3, 8];
        $this->arrAccessKorwilSales = [3, 4, 7, 8];
        $this->arrAccessFinance = array_merge($this->backoffice_superadmin_area, [6, 7, 14]);
        $this->_output = [];
        $this->client = new Client([
            'base_uri' => (string)env('PD_API_URL'),
            'timeout' => 10.0,
        ]);
    }

    public function index()
    {
        if ( ! in_array($this->adm_level, $this->arrAccessKorwilSales, true)) {
            redirect(ADMIN_PATH, 'refresh');
        }
        $data['page_title'] = 'Pesanan Dikirim & Sudah Terkonfirmasi Lunas | '.date('Y-m-d_His');
        $this->_output['content'] = $this->load->view('admin/comission/list_new', $data, true);
        $this->_output['script_css'] = $this->load->view('admin/comission/css', '', true);
        $this->_output['script_js'] = $this->load->view('admin/comission/js', '', true);
        $this->load->view('admin/template', $this->_output);
    }

    public function listOrderNew()
    {
        if ( ! $this->input->is_ajax_request()) {
            return false;
        }
        $this->load->library('datatables');
        $this->output->set_header('Content-Type:application/json; charset=utf-8');
        $this->datatables->select('a.id_order AS id_order, 
            a.reference AS reference, 
            b.school_name AS school_name, 
            CONCAT(a.category, " (",a.type,")") AS class_name, 
            b.provinsi AS provinsi, 
            b.kabupaten AS kabupaten,
            CONCAT(c.name, "<br>(", a.sales_referer, ")<br>", c.telp) AS sales_person,
            FORMAT((a.percent_comission * 100), 2) AS percent_comission, 
            FORMAT((d.percent_tax * 100), 2) AS percent_tax, 
            (ROUND(a.percent_comission * a.total_paid) - ROUND(d.percent_tax * (a.percent_comission * a.total_paid))) AS amount_comission, 
            a.date_add AS date_add,
            a.total_paid');
        $this->datatables->from('orders a');
        $this->datatables->join('customer b', 'b.id_customer=a.id_customer', 'inner');
        $this->datatables->join('employee c', 'c.email=a.sales_referer AND c.level=4 AND c.active=1', 'inner');
        $this->datatables->join('mitra_profile d', 'd.id_employee=c.id_employee', 'inner');
        $this->datatables->where('a.current_state', 9);
        $this->datatables->where('a.sts_bayar', 2);
        $this->datatables->where('YEAR(a.tgl_lunas) >= 2018');
        $this->datatables->where('a.id_order IN (SELECT x.id_order FROM finance_history x)');
        if ($this->adm_level == 4) {
            $this->datatables->where('a.sales_referer', $this->adm_uname);
        } elseif (in_array($this->adm_level, $this->arrAccessKorwil, true)) {
            $this->datatables->where('b.kabupaten IN (SELECT aa.kabupaten_kota FROM employee_kabupaten_kota aa WHERE aa.id_employee = '.$this->adm_id.')');
        }
        $this->datatables->where('a.id_order NOT IN (SELECT id_order FROM '.$this->tblPayout.')');
        $this->datatables->where('a.sales_referer IN (SELECT x.email FROM employee x INNER JOIN mitra_profile y ON y.id_employee=x.id_employee WHERE x.active=1 AND y.is_activated=1)');
        $this->datatables->edit_column('reference', '<a href="'.base_url(ADMIN_PATH.'/comission/detail/$1').'">$2</a>',
            'id_order, reference');
        $this->output->set_output($this->datatables->generate());
    }

    public function detail($idOrder)
    {
        if ($idOrder && is_numeric($idOrder)) {
            $data['page_title'] = 'Detil Komisi';
            $data['order_states'] = $this->mod_general->getWhere('order_state', 'deleted', 0, 'id_order_state', 'asc');
            $data['detil'] = $this->mod_general->detailData('orders', 'id_order', $idOrder);
            $percentComission = 0.20;
            if ($data['detil']) {
                if ($data['detil']['sales_referer']) {
                    $detailMitra = $this->mod_general->detailData('employee', 'email', $data['detil']['sales_referer']);
                    if ($this->mod_comission->isMitra($data['detil']['sales_referer'])) {
                        $percentComission = $data['detil']['percent_comission'];
                        $data['referral'] = $this->mod_comission->getReferral($data['detil']['sales_referer']);
                        $data['uDirect'] = [
                            'nama' => $detailMitra['name'],
                            'email' => $detailMitra['email'],
                            'telpon' => $detailMitra['telp'],
                        ];
                    } else {
                        redirect(ADMIN_PATH.'/comission');
                    }
                } else {
                    redirect(ADMIN_PATH.'/comission');
                }
                $data['comission'] = $this->calculateComission($idOrder, $data['detil']['total_paid'],
                    $percentComission, $data['detil']['sales_referer']);
                $data['customer'] = $this->mod_general->detailData('customer', 'id_customer',
                    $data['detil']['id_customer']);
                $data['url_back'] = base_url().ADMIN_PATH.'/comission';
                $data['adm_level'] = $this->adm_level;
                if ($this->mod_comission->isInPayout($idOrder)) {
                    $data['data_payout'] = $this->mod_general->detailData($this->tblPayout, 'id_order', $idOrder);
                    $data['isHaveDeduction'] = $this->mod_comission->isHaveDeduction($data['data_payout']['id']);
                    if ($data['isHaveDeduction']) {
                        $data['listDeduction'] = $this->mod_comission->getListDeduction($data['data_payout']['id']);
                    }
                    $data['history_payout'] = $this->mod_general->getWhere('payout_history', 'id_payout',
                        $data['data_payout']['id'], 'id', 'asc');
                    switch ($data['data_payout']['id_payout_status']) {
                        case 1:
                            $data['url_back'] = base_url().ADMIN_PATH.'/comission/proposed';
                            break;
                        case 2:
                            $data['url_back'] = base_url().ADMIN_PATH.'/comission/processed';
                            break;
                        case 3:
                            $data['url_back'] = base_url().ADMIN_PATH.'/comission/paidOff';
                            break;
                    }
                }
                $this->_output['content'] = $this->load->view('admin/comission/detail_order', $data, true);
                $this->_output['script_css'] = $this->load->view('admin/comission/css', '', true);
                $this->_output['script_js'] = $this->load->view('admin/comission/js', '', true);
                $this->load->view('admin/template', $this->_output);
            } else {
                redirect(ADMIN_PATH.'/comission', 'refresh');
            }
        } else {
            redirect(ADMIN_PATH.'/comission', 'refresh');
        }
    }

    public function calculateComission($idOrder, $amount, $percentage, $emailSales = false)
    {
        $referralCommision = null;
        $percentTax = null;
        $percentTaxReferral = null;
        if ($this->mod_comission->isHaveSales($idOrder) && $this->mod_comission->isMitra($emailSales)) {
            if ($this->mod_comission->isHaveReferral($emailSales)) {
                $referral = $this->mod_comission->getReferral($emailSales);
                $emailReferral = $referral['email'];
                if ($this->mod_comission->isMitra($emailReferral)) {
                    $referralCommision = round($amount * 0.01);
                    $percentTaxReferral = $this->mod_comission->getPercentTax($emailReferral);
                }
            }
            $percentTax = $this->mod_comission->getPercentTax($emailSales);
        }
        $directComission = round($percentage * $amount);
        $comission = [
            'direct' => [
                'percentage' => $percentage,
                'amount' => $directComission,
                'tax' => $percentTax,
                'tax_value' => round($directComission * $percentTax),
                'payout' => $directComission - round($directComission * $percentTax),
            ],
            'referral' => [
                'amount' => $referralCommision,
                'tax' => $percentTaxReferral,
                'tax_value' => round($referralCommision * $percentTaxReferral),
                'payout' => $referralCommision - round($referralCommision * $percentTaxReferral),
            ],
        ];

        return $comission;
    }

    public function popupPercentage($idOrder)
    {
        if ( ! $this->input->is_ajax_request()) {
            return false;
        }
        $detil = $this->mod_general->detailData('orders', 'id_order', $idOrder);
        if ($detil['sales_referer'] && $this->mod_comission->isMitra($detil['sales_referer'])) {
            $data['percentage'] = $detil['percent_comission'];
            $data['id_order'] = $idOrder;
            $this->load->view('admin/comission/popup_percentage', $data);
        } else {
            return false;
        }
    }

    # TODO : add logs and block permission for auditor
    public function updatePercentage()
    {
        if ( ! $this->input->is_ajax_request()) {
            return false;
        }
        try {
            if (in_array($this->adm_level, $this->auditor_area, true)) {
                $callBack = [
                    'success' => 'false',
                    'message' => 'Maaf, anda tidak dapat melakukan proses ini.',
                ];
            } else {
                $idOrder = $this->input->post('id_order');
                $oldPercentage = $this->input->post('old_percentage');
                $percentage = $this->input->post('percentage');
                $isInPayout = $this->mod_comission->isInPayout($idOrder);
                $idPayout = $isInPayout ? $this->mod_comission->getIdPayout($idOrder) : false;
                $payoutState = $this->mod_comission->getStatus($idOrder);
                if ($payoutState && $payoutState == 4) {
                    $callBack = [
                        'success' => 'false',
                        'message' => 'Dana komisi sudah ditransfer, data bisa diubah',
                    ];
                } else {
                    $redirect = $isInPayout ? 'comission/payout/'.$idPayout.'#komisi-area' : 'comission/detail/'.$idOrder.'#komisi-area';
                    if ($percentage <= 0 || ! is_numeric($percentage)) {
                        $callBack = [
                            'success' => 'false',
                            'message' => 'Comission percentage cannot null or invalid.',
                            'redirect' => $redirect,
                        ];
                    } elseif ($percentage <= 1 || $percentage > env('MAX_COMISSION')) {
                        $callBack = [
                            'success' => 'false',
                            'message' => 'Comission percentage is invalid.',
                            'redirect' => $redirect,
                        ];
                    } elseif ($percentage == $oldPercentage) {
                        $callBack = [
                            'success' => 'false',
                            'message' => 'Comission percentage still same.',
                            'redirect' => $redirect,
                        ];
                    } else {
                        $this->db->trans_begin();
                        $percentFinal = $percentage / 100;
                        $data = ['percent_comission' => $percentFinal];
                        if ($isInPayout) {
                            $dataPayout = [
                                'percentage' => $percentFinal,
                                'modified_date' => date('Y-m-d H:i:s'),
                                'modified_by' => $this->adm_id,
                            ];
                            $this->mod_comission->updateComission($idOrder, $dataPayout);
                        }
                        $proc = $this->mod_general->updateData('orders', $data, 'id_order', $idOrder);
                        if ($proc) {
                            $this->db->trans_commit();
                            $callBack = [
                                'success' => 'true',
                                'message' => 'Data successfully updated.',
                                'redirect' => $redirect,
                            ];
                            $this->session->set_flashdata('msg_success_percentage',
                                'Data persentase komisi berhasil <b>DIUBAH</b></p>');
                        } else {
                            $this->db->trans_rollback();
                            $callBack = [
                                'success' => 'false',
                                'message' => 'Failed to update data.',
                            ];
                        }
                    }
                }
            }
            echo json_encode($callBack, true);
        } catch (Exception $e) {
            $callBack = [
                'success' => 'false',
                'message' => 'Caught exception: '.$e->getMessage(),
            ];
            echo json_encode($callBack, true);
        }
    }

    public function listProposed()
    {
        if ( ! $this->input->is_ajax_request()) {
            return false;
        }
        $this->load->library('datatables');
        $this->output->set_header('Content-Type:application/json; charset=utf-8');
        $this->datatables->select('a.id AS id,
                    a.id_order AS id_order, 
                    b.reference AS reference, 
                    c.school_name AS school_name, 
                    CONCAT(b.category, "<br>(",b.type,")") AS class_name, 
                    c.provinsi AS provinsi, 
                    c.kabupaten AS kabupaten,
                    CONCAT(d.name, "<br> (", d.email, ") <br>", d.telp) AS sales_person,
                    FORMAT((a.percentage * 100), 2) AS percent_comission, 
                    FORMAT((a.tax * 100), 2) AS percent_tax, 
                    (ROUND(a.percentage * b.total_paid) - ROUND(a.tax * (a.percentage * b.total_paid))) AS amount_comission, 
                    b.date_add AS date_add,
                    b.total_paid,
                    a.created_date AS date_proposed');
        $this->datatables->from($this->tblPayout.' a');
        $this->datatables->join('orders b', 'b.id_order=a.id_order', 'inner');
        $this->datatables->join('customer c', 'c.id_customer=b.id_customer', 'inner');
        $this->datatables->join('employee d', 'd.id_employee=a.id_employee', 'inner');
        $this->datatables->join('mitra_profile e', 'e.id_employee=d.id_employee', 'inner');
        $this->datatables->where('a.status', 1);
        if ($this->adm_level == 4) {
            $this->datatables->where('a.id_employee', $this->adm_id);
        } elseif (in_array($this->adm_level, $this->arrAccessKorwil, true)) {
            $this->datatables->where('c.kabupaten IN (SELECT aa.kabupaten_kota FROM employee_kabupaten_kota aa WHERE aa.id_employee = '.$this->adm_id.')');
        }
        $this->datatables->edit_column('reference', '<a href="'.base_url(ADMIN_PATH.'/comission/payout/$1').'">$2</a>',
            'id, reference');
        $this->output->set_output($this->datatables->generate());
    }

    public function proposed()
    {
        if ( ! in_array($this->adm_level, $this->arrAccessKorwilSales, true)) {
            redirect(ADMIN_PATH.'/comission/approved', 'refresh');
        }
        $data['page_title'] = 'Komisi Pesanan Diajukan | '.date('Y-m-d_His');
        $this->_output['content'] = $this->load->view('admin/comission/list_proposed', $data, true);
        $this->_output['script_js'] = $this->load->view('admin/comission/js', '', true);
        $this->load->view('admin/template', $this->_output);
    }

    # TODO : add logs and block permission for auditor
    public function proposedPost()
    {
        if ( ! $this->input->is_ajax_request()) {
            return false;
        }
        try {
            if (in_array($this->adm_level, $this->auditor_area, true)) {
                $callBack = [
                    'success' => 'false',
                    'message' => 'Maaf, anda tidak dapat melakukan proses ini.',
                ];
                $this->session->set_flashdata('msg_failed', $callBack['message']);
            } else {
                $orderReference = $this->input->post('order_reference', true);
                $idOrder = $this->input->post('id_order');
                $directEmail = $this->input->post('direct_email', true);
                $referalEmail = $this->input->post('referral_email', true);
                $personDirect = $this->mod_comission->getSales("employee.id_employee,
                    employee.name,
                    employee.email,
                    mitra_profile.id,
                    mitra_profile.bank_account_type", "employee.email = '$directEmail'")[0];
                $idEmployeeDirect = $personDirect->id_employee;

                $orders = $this->mod_general->detailData('orders', 'id_order', $idOrder);
                $tanggal_konfirmasi = date('Y-m-d', strtotime($orders['tgl_konfirmasi']));
                $check_kontrak = $this->mod_comission->check_kontrak2($personDirect->id_employee, $tanggal_konfirmasi);
                // print_r($check_kontrak);
                // exit();

                // if(count($check_kontrak) <= 0)

                if($check_kontrak <= 0)
                {
                    $callBack = [
                        'message' => 'Gagal mengajukan komisi pesanan! Mitra <b>'.$personDirect->name.'</b> belum memiliki kontrak atau masa berlaku kontrak habis',
                        'success' => 'false',
                    ];
                    $this->session->set_flashdata('msg_failed', $callBack['message']);
                    echo json_encode($callBack);
                    exit(); 
                }
                // print_r($this->db->last_query());
                
                $directComission = [
                    'id_order' => $idOrder,
                    'id_employee' => $idEmployeeDirect,
                    'percentage' => $this->input->post('direct_percent'),
                    'tax' => $this->input->post('direct_tax'),
                    'type' => 1,
                    'is_bca' => $personDirect->bank_account_type == 1 ? 1 : 0,
                    'created_date' => date('Y-m-d H:i:s'),
                    'created_by' => $this->adm_id,
                    'modified_date' => date('Y-m-d H:i:s'),
                    'modified_by' => $this->adm_id,
                ];
                if ($referalEmail) {
                    $personReferal = $this->mod_comission->getSales("employee.id_employee,
                    employee.name,
                    employee.email,
                    mitra_profile.id,
                    mitra_profile.bank_account_type", "employee.email = '$referalEmail'")[0];
                    $idEmployeeReferal = $personReferal->id_employee;
                    $referalComission = [
                        'id_order' => $idOrder,
                        'id_employee' => $idEmployeeReferal,
                        'percentage' => 0.01,
                        'tax' => $this->input->post('referral_tax'),
                        'type' => 2,
                        'is_bca' => $personReferal->bank_account_type == 1 ? 1 : 0,
                        'created_date' => date('Y-m-d H:i:s'),
                        'created_by' => $this->adm_id,
                        'modified_date' => date('Y-m-d H:i:s'),
                        'modified_by' => $this->adm_id,
                    ];
                }
                if ($this->mod_comission->isOrderExist($idOrder)) {
                    $callBack = [
                        'message' => 'Komisi untuk pesanan #'.$idOrder.' sudah diajukan!',
                        'success' => 'false',
                    ];
                    $this->session->set_flashdata('msg_failed', $callBack['message']);
                } else {
                    $this->db->trans_begin();
                    $insertDirect = $this->mod_general->addData($this->tblPayout, $directComission);
                    if ($insertDirect) {
                        $this->mod_comission->addHistory($insertDirect, $this->adm_id, 1);
                        if ($referalEmail) {
                            $insertReferal = $this->mod_general->addData($this->tblPayout, $referalComission);
                            if ($insertReferal) {
                                $this->mod_comission->addHistory($insertReferal, $this->adm_id, 1);
                            }
                        }
                        $this->db->trans_commit();
                        $callBack = [
                            'message' => 'Data Komisi Pesanan <b>#'.$orderReference.'</b>, berhasil diajukan.',
                            'success' => 'true',
                        ];
                        $this->session->set_flashdata('msg_success_commision', $callBack['message']);
                    } else {
                        $this->db->trans_rollback();
                        $callBack = [
                            'message' => 'Gagal mengajukan komisi pesanan!',
                            'success' => 'false',
                        ];
                        $this->session->set_flashdata('msg_failed', $callBack['message']);
                    }
                }
            }
            echo json_encode($callBack);
        } catch (Exception $e) {
            $callBack = [
                'success' => 'false',
                'message' => 'Caught exception: '.$e->getMessage(),
            ];
            echo json_encode($callBack);
        }
    }

    public function payout($id)
    {
        if ($id && is_numeric($id)) {
            $data['page_title'] = 'Detil Pengajuan Komisi';
            $data['payout'] = $this->mod_general->detailData($this->tblPayout, 'id', $id);
            $data['order'] = $this->mod_general->detailData('orders', 'id_order', $data['payout']['id_order']);
            if ($data['payout'] && $data['order']) {
                $total_order = $data['order']['total_paid'];
                $comission_percentage = $data['payout']['percentage'];
                $comission_amount = round($comission_percentage * $total_order);
                $tax_percentage = $data['payout']['tax'];
                $tax_amount = round($tax_percentage * $comission_amount);
                $final_comission = $comission_amount - $tax_amount;
                $data['comission'] = [
                    'type' => $data['payout']['type'] == 1 ? 'Langsung' : 'Referensi',
                    'comission_percent' => $comission_percentage,
                    'comission_amount' => $comission_amount,
                    'tax_percent' => $tax_percentage * 100,
                    'tax_amount' => $tax_amount,
                    'final_comission' => $final_comission,
                ];
                $data['mitra'] = $this->mod_general->detailData('employee', 'id_employee',
                    $data['payout']['id_employee']);
                $data['customer'] = $this->mod_general->detailData('customer', 'id_customer',
                    $data['order']['id_customer']);
                $data['adm_level'] = $this->adm_level;
                $data['history_payout'] = $this->mod_general->getWhere('payout_history', 'id_payout',
                    $data['payout']['id'], 'id', 'asc');
                switch ($data['payout']['status']) {
                    case 1:
                        $data['url_back'] = base_url().ADMIN_PATH.'/comission/proposed';
                        break;
                    case 2:
                        $path = in_array($this->adm_level, $this->arrAccessKorwil, true) ? 'approved' : 'toprocessed';
                        $data['url_back'] = base_url().ADMIN_PATH.'/comission/'.$path;
                        break;
                    case 3:
                        $data['url_back'] = base_url().ADMIN_PATH.'/comission/processed';
                        break;
                    case 4:
                        $data['url_back'] = base_url().ADMIN_PATH.'/comission/paidOff';
                        break;
                }
                $this->_output['content'] = $this->load->view('admin/comission/detail', $data, true);
                $this->_output['script_css'] = $this->load->view('admin/comission/css', '', true);
                $this->_output['script_js'] = $this->load->view('admin/comission/js', '', true);
                $this->load->view('admin/template', $this->_output);
            } else {
                redirect(ADMIN_PATH.'/comission', 'refresh');
            }
        } else {
            redirect(ADMIN_PATH.'/comission/proposed', 'refresh');
        }
    }

    public function listApproved()
    {
        if ( ! $this->input->is_ajax_request()) {
            return false;
        }
        $this->load->library('datatables');
        $this->output->set_header('Content-Type:application/json; charset=utf-8');
        $this->datatables->select('a.id AS id,
                    a.id_order AS id_order, 
                    b.reference AS reference, 
                    c.school_name AS school_name, 
                    CONCAT(b.category, "<br>(",b.type,")") AS class_name, 
                    c.provinsi AS provinsi, 
                    c.kabupaten AS kabupaten,
                    CONCAT(d.name, "<br> (", d.email, ") <br>", d.telp) AS sales_person,
                    FORMAT((a.percentage * 100), 2) AS percent_comission, 
                    FORMAT((a.tax * 100), 2) AS percent_tax, 
                    (ROUND(a.percentage * b.total_paid) - ROUND(a.tax * (a.percentage * b.total_paid))) AS amount_comission, 
                    b.date_add AS date_add,
                    b.total_paid,
                    a.created_date AS date_proposed');
        $this->datatables->from($this->tblPayout.' a');
        $this->datatables->join('orders b', 'b.id_order=a.id_order', 'inner');
        $this->datatables->join('customer c', 'c.id_customer=b.id_customer', 'inner');
        $this->datatables->join('employee d', 'd.id_employee=a.id_employee', 'inner');
        $this->datatables->join('mitra_profile e', 'e.id_employee=d.id_employee', 'inner');
        $this->datatables->where('a.status', 2);
        if ($this->adm_level == 4) {
            $this->datatables->where('a.id_employee', $this->adm_id);
        } elseif (in_array($this->adm_level, $this->arrAccessKorwil, true)) {
            $this->datatables->where('c.kabupaten IN (SELECT aa.kabupaten_kota FROM employee_kabupaten_kota aa WHERE aa.id_employee = '.$this->adm_id.')');
        }
        $this->datatables->edit_column('reference', '<a href="'.base_url(ADMIN_PATH.'/comission/payout/$1').'">$2</a>',
            'id, reference');
        $this->output->set_output($this->datatables->generate());
    }

    public function approved()
    {
        if ( ! in_array($this->adm_level, $this->arrAccessKorwilSales, true)) {
            redirect(ADMIN_PATH.'/comission', 'refresh');
        }
        $data['page_title'] = 'Komisi Pesanan Disetujui | '.date('Y-m-d_His');
        $this->_output['content'] = $this->load->view('admin/comission/list_approved', $data, true);
        $this->_output['script_js'] = $this->load->view('admin/comission/js', '', true);
        $this->load->view('admin/template', $this->_output);
    }

    # TODO : add logs and block permission for auditor
    public function approvePost()
    {
        if ( ! $this->input->is_ajax_request()) {
            return false;
        }
        try {
            if (in_array($this->adm_level, $this->auditor_area, true)) {
                $callBack = [
                    'success' => 'false',
                    'message' => 'Maaf, anda tidak dapat melakukan proses ini.',
                ];
                $this->session->set_flashdata('msg_failed', $callBack['message']);
            } else {
                $id = $this->input->post('id');
                $percentage = $this->input->post('percentage');
                $orderReference = $this->input->post('reference');
                $status = 2;
                $data = [
                    'status' => $status,
                    'modified_date' => date('Y-m-d H:i:s'),
                    'modified_by' => $this->adm_id,
                ];
                $this->db->trans_begin();
                $approveProc = $this->mod_general->updateData($this->tblPayout, $data, 'id', $id);
                if ($approveProc) {
                    $insertHistory = $this->mod_comission->addHistory($id, $this->adm_id, $status);
                    if ($insertHistory) {
                        $this->db->trans_commit();
                        $callBack = [
                            'message' => 'Data Komisi <b>('.$percentage * 100 .'%)</b> Pesanan <b>#'.$orderReference.'</b> akan diproses.',
                            'success' => 'true',
                        ];
                        $this->session->set_flashdata('msg_success_commision', $callBack['message']);
                    } else {
                        $this->db->trans_rollback();
                        $callBack = [
                            'message' => 'Gagal menyimpan riwayat pengajuan komisi pesanan!',
                            'success' => 'false',
                        ];
                        $this->session->set_flashdata('msg_failed', $callBack['message']);
                    }
                } else {
                    $this->db->trans_rollback();
                    $callBack = [
                        'message' => 'Gagal memproses komisi pesanan!',
                        'success' => 'false',
                    ];
                    $this->session->set_flashdata('msg_failed', $callBack['message']);
                }
            }
            echo json_encode($callBack);
        } catch (Exception $e) {
            $callBack = [
                'success' => 'false',
                'message' => 'Caught exception: '.$e->getMessage(),
            ];
            echo json_encode($callBack);
        }
    }

    public function listToProcessed()
    {
        if ( ! $this->input->is_ajax_request()) {
            return false;
        }
        $this->load->library('datatables');
        $this->output->set_header('Content-Type:application/json; charset=utf-8');
        $this->datatables->select('a.id AS id,
                    a.id AS id_payout,
                    a.id_order AS id_order, 
                    b.reference AS reference, 
                    c.school_name AS school_name, 
                    CONCAT(b.category, "<br>(",b.type,")") AS class_name, 
                    c.provinsi AS provinsi, 
                    c.kabupaten AS kabupaten,
                    CONCAT(d.name, "<br> (", d.email, ") <br>", d.telp) AS sales_person,
                    FORMAT((a.percentage * 100), 2) AS percent_comission, 
                    FORMAT((a.tax * 100), 2) AS percent_tax, 
                    (ROUND(a.percentage * b.total_paid) - ROUND(a.tax * (a.percentage * b.total_paid))) AS amount_comission, 
                    b.date_add AS date_add,
                    b.total_paid,
                    a.created_date AS date_proposed');
        $this->datatables->from($this->tblPayout.' a');
        $this->datatables->join('orders b', 'b.id_order=a.id_order', 'inner');
        $this->datatables->join('customer c', 'c.id_customer=b.id_customer', 'inner');
        $this->datatables->join('employee d', 'd.id_employee=a.id_employee', 'inner');
        $this->datatables->join('mitra_profile e', 'e.id_employee=d.id_employee', 'inner');
        $this->datatables->where('a.status', 2);
        $this->datatables->add_column('id', '<input type="checkbox" class="checkc_batch" value="$1" id="payout_$1"/>',
            'id');
        $this->datatables->edit_column('reference', '<a href="'.base_url(ADMIN_PATH.'/comission/payout/$1').'">$2</a>',
            'id_payout, reference');
        $this->output->set_output($this->datatables->generate());
    }

    public function toProcessed()
    {
        if ( ! in_array($this->adm_level, $this->backoffice_superadmin_area, true)) {
            redirect(ADMIN_PATH, 'refresh');
        }
        $data['page_title'] = 'Komisi Pesanan Disetujui | '.date('Y-m-d_His');
        $this->_output['content'] = $this->load->view('admin/comission/list_to_processed', $data, true);
        $this->_output['script_js'] = $this->load->view('admin/comission/js', '', true);
        $this->load->view('admin/template', $this->_output);
    }

    # TODO : add logs and block permission for auditor
    public function processBatch()
    {
        if ( ! $this->input->is_ajax_request()) {
            return false;
        }
        if (in_array($this->adm_level, $this->auditor_area, true)) {
            $callBack = [
                'success' => 'false',
                'message' => 'Maaf, anda tidak dapat melakukan proses ini.',
            ];
            $this->session->set_flashdata('msg_error_commision', $callBack['message']);
        } else {
            $this->db->trans_begin();
            $payoutId = explode(',', $this->input->post('payout_id'));
            $bankBCA = [];
            $bankNonBCA = [];
            $arrNoPD = [];
            foreach ($payoutId as $id) {
                $payoutData = $this->mod_general->getAll($this->tblPayout, '*', 'id='.$id)[0];
                $salesPerson = $this->mod_comission->getSales("employee.id_employee,
                    employee.name,
                    employee.email,
                    mitra_profile.id,
                    mitra_profile.bank_account_type", "employee.id_employee = '$payoutData->id_employee'")[0];
                $data['payout_comission'] = [
                    'id' => $payoutData->id,
                    'id_employee' => $payoutData->id_employee,
                    'comission_percent' => $payoutData->percentage,
                    'tax_percent' => $payoutData->tax,
                ];
                if ($salesPerson->bank_account_type == 1) {
                    $bankBCA[] = $data['payout_comission'];
                } else {
                    $bankNonBCA[] = $data['payout_comission'];
                }
            }
            // if group BCA bank has data, insert into database
            if ( ! empty($bankBCA)) {
                $noPdBCA = $this->getNoPD();
                if (is_numeric($noPdBCA)) {
                    $noPdColBCA = count($bankBCA) > 1 ? $this->getNoPDCollective() : 0;
                    foreach ($bankBCA as $dataBCA) {
                        $updateBCA = [
                            'no_pd' => $noPdBCA,
                            'no_pd_kolektif' => $noPdColBCA,
                            'status' => 3,
                            'modified_date' => date('Y-m-d H:i:s'),
                            'modified_by' => $this->adm_id,
                        ];
                        $this->mod_general->updateData($this->tblPayout, $updateBCA, 'id', $dataBCA['id']);
                        $this->mod_comission->addHistory($dataBCA['id'], $this->adm_id, 3);
                        if ( ! in_array($noPdBCA, $arrNoPD, true)) {
                            $arrNoPD[] = $noPdBCA;
                        }
                    }
                } else {
                    $this->db->trans_rollback();
                    $this->session->set_flashdata('msg_error_commision',
                        'Batch pesanan gagal dibuat. Gagal mendapatkan Nomor Pesanan Dana.');
                    $callBack = [
                        'success' => 'false',
                        'message' => 'Batch pesanan gagal dibuat. Gagal mendapatkan Nomor Pesanan Dana.',
                    ];
                    echo json_encode($callBack, true);
                    exit();
                }
            }
            // if group Non BCA bank has data, insert into database
            if ( ! empty($bankNonBCA)) {
                $noPdNonBCA = $this->getNoPD();
                if (is_numeric($noPdNonBCA)) {
                    $noPdColNonBCA = count($bankNonBCA) > 1 ? (int)$this->getNoPDCollective() : 0;
                    foreach ($bankNonBCA as $dataNonBCA) {
                        $updateNonBCA = [
                            'no_pd' => $noPdNonBCA,
                            'no_pd_kolektif' => $noPdColNonBCA,
                            'status' => 3,
                            'modified_date' => date('Y-m-d H:i:s'),
                            'modified_by' => $this->adm_id,
                        ];
                        $this->mod_general->updateData($this->tblPayout, $updateNonBCA, 'id', $dataNonBCA['id']);
                        $this->mod_comission->addHistory($dataNonBCA['id'], $this->adm_id, 3);
                        if ( ! in_array($noPdNonBCA, $arrNoPD, true)) {
                            $arrNoPD[] = $noPdNonBCA;
                        }
                    }
                } else {
                    $this->db->trans_rollback();
                    $this->session->set_flashdata('msg_error_commision',
                        'Batch pesanan gagal dibuat. Gagal mendapatkan Nomor Pesanan Dana.');
                    $callBack = [
                        'success' => 'false',
                        'message' => 'Batch pesanan gagal dibuat. Gagal mendapatkan Nomor Pesanan Dana.',
                    ];
                    echo json_encode($callBack, true);
                    exit();
                }
            }
            if ($this->db->trans_status() == true) {
                $this->db->trans_commit();
                $this->setNomorPPh($arrNoPD);
                $this->session->set_flashdata('msg_success_commision',
                    'Nomor Pesanan Dana: <b>['.implode(", ", $arrNoPD).']</b> berhasil dibuat.');
                $callBack = [
                    'success' => 'true',
                    'message' => 'Batch pesanan berhasil dibuat.',
                ];
            } else {
                $this->db->trans_rollback();
                $this->session->set_flashdata('msg_error_commision', 'Batch pesanan gagal dibuat.');
                $callBack = [
                    'success' => 'false',
                    'message' => error_form('Batch pesanan gagal dibuat.'),
                ];
            }
        }
        echo json_encode($callBack, true);
    }

    public function getNoPD()
    {
        try {
            $request = $this->client->get('/api/PDNo', [
                'headers' => [
                    'Authorization' => 'Bearer '.env('PD_AUTH'),
                ],
            ]);

            return \GuzzleHttp\json_decode($request->getBody())->pd_no;
        } catch (ClientException $e) {
            echo Psr7\str($e->getResponse());
        }
    }

    public function getNoPDCollective()
    {
        try {
            $request = $this->client->get('/api/NoKolektif', [
                'headers' => [
                    'Authorization' => 'Bearer '.env('PD_AUTH'),
                ],
                'query' => ['UnitId' => 100],
            ]);

            return \GuzzleHttp\json_decode($request->getBody())->no_transaksi;
        } catch (ClientException $e) {
            echo Psr7\str($e->getResponse());
        }
    }

    public function setNomorPPh($paramNoPD = false)
    {
        $listNoPD = $paramNoPD ?: $this->mod_comission->getListPDPPh();
        $no = 1;
        foreach ($listNoPD as $data) {
            $noPD = ! $paramNoPD ? $data['no_pd'] : $data;
            $listData = $this->mod_comission->getListMitraByPD($noPD);
            $arrNoPDIdEmployee = [];
            foreach ($listData as $val) {
                $noPDEmployee = $val['no_pd'].'-'.$val['id_employee'];
                if ( ! in_array($noPDEmployee, $arrNoPDIdEmployee, true)) {
                    $arrNoPDIdEmployee[] = $noPDEmployee;
                    $nomorPPH = $this->mod_comission->getNomorPPh();
                    $dataPayout = [
                        'no_pph' => $nomorPPH,
                        'modified_date' => date('Y-m-d H:i:s'),
                        'modified_by' => $this->adm_id,
                    ];
                    $proc = $this->mod_comission->updatePayoutPPh($val['no_pd'], $val['id_employee'], $dataPayout);
                    if ($proc) {
                        $this->mod_comission->updateNomorPPh($nomorPPH, ['status' => 2]);
                        if ( ! $paramNoPD) {
                            echo $no.'. UPDATE `'.$this->tblPayout.'` SET `no_pph`=\''.$nomorPPH.'\', `modified_date`=\''.date('Y-m-d H:i:s').'\', `modified_by`=\''.$this->adm_id.'\' WHERE `no_pd`=\''.$val['no_pd'].'\' AND `id_employee`=\''.$val['id_employee'].'\'<br>{\'message\': \'success\'}<br>';
                        }
                        $no++;
                    }
                }
            }
        }
    }

    public function listProcessed()
    {
        if ( ! $this->input->is_ajax_request()) {
            return false;
        }
        $startDate = $this->input->post('start_date') ?? '2016-01-01';
        $endDate = $this->input->post('end_date') ?? date('Y-m-d');
        $this->load->library('datatables');
        $this->output->set_header('Content-Type:application/json; charset=utf-8');
        $this->datatables->select('a.id AS id,
                    a.id_order AS id_order, 
                    b.reference AS reference, 
                    a.no_pd AS no_pd, 
                    c.school_name AS school_name, 
                    CONCAT(b.category, "<br>(",b.type,")") AS class_name, 
                    c.provinsi AS provinsi, 
                    c.kabupaten AS kabupaten,
                    CONCAT(d.name, "<br> (", d.email, ") <br>", d.telp) AS sales_person,
                    FORMAT((a.percentage * 100), 2) AS percent_comission, 
                    FORMAT((a.tax * 100), 2) AS percent_tax, 
                    (ROUND(a.percentage * b.total_paid) - ROUND(a.tax * (a.percentage * b.total_paid))) AS amount_comission, 
                    b.date_add AS date_add,
                    b.total_paid,
                    a.created_date AS date_proposed');
        $this->datatables->from($this->tblPayout.' a');
        $this->datatables->join('orders b', 'b.id_order=a.id_order', 'inner');
        $this->datatables->join('customer c', 'c.id_customer=b.id_customer', 'inner');
        $this->datatables->join('employee d', 'd.id_employee=a.id_employee', 'inner');
        $this->datatables->join('mitra_profile e', 'e.id_employee=d.id_employee', 'inner');
        $this->datatables->where('a.status', 3);
        $this->datatables->where('a.created_date BETWEEN \''.$startDate.' 00:00:00\' AND \''.$endDate.' 23:59:59\'');
        if ($this->adm_level == 4) {
            $this->datatables->where('a.id_employee', $this->adm_id);
        } elseif (in_array($this->adm_level, $this->arrAccessKorwil, true)) {
            $this->datatables->where('c.kabupaten IN (SELECT aa.kabupaten_kota FROM employee_kabupaten_kota aa WHERE aa.id_employee = '.$this->adm_id.')');
        }
        if (in_array($this->adm_level, array_merge($this->backoffice_superadmin_area, $this->arrAccessKorwil), true)) {
            $this->datatables->edit_column('no_pd', '<a href="'.base_url(ADMIN_PATH.'/comission/pd/$1').'">$1</a>',
                'no_pd');
        }
        $this->datatables->edit_column('reference', '<a href="'.base_url(ADMIN_PATH.'/comission/payout/$1').'">$2</a>',
            'id, reference');
        $this->output->set_output($this->datatables->generate());
    }

    public function processed()
    {
        $data['page_title'] = 'Komisi Pesanan Diproses | '.date('Y-m-d_His');
        $this->_output['content'] = $this->load->view('admin/comission/list_processed', $data, true);
        $this->_output['script_js'] = $this->load->view('admin/comission/js', '', true);
        $this->load->view('admin/template', $this->_output);
    }

    # TODO : add logs and block permission for auditor

    public function listPaidoff()
    {
        if ( ! $this->input->is_ajax_request()) {
            return false;
        }
        $this->load->library('datatables');
        $this->output->set_header('Content-Type:application/json; charset=utf-8');
        $this->datatables->select('a.id AS id,
                    a.id_order AS id_order, 
                    b.reference AS reference, 
                    a.no_pd AS no_pd, 
                    c.school_name AS school_name, 
                    CONCAT(b.category, "<br>(",b.type,")") AS class_name, 
                    c.provinsi AS provinsi, 
                    c.kabupaten AS kabupaten,
                    CONCAT(d.name, "<br> (", d.email, ") <br>", d.telp) AS sales_person,
                    FORMAT((a.percentage * 100), 2) AS percent_comission, 
                    FORMAT((a.tax * 100), 2) AS percent_tax, 
                    (ROUND(a.percentage * b.total_paid) - ROUND(a.tax * (a.percentage * b.total_paid))) AS amount_comission, 
                    b.date_add AS date_add,
                    b.total_paid,
                    a.transfer_date AS date_transfered');
        $this->datatables->from($this->tblPayout.' a');
        $this->datatables->join('orders b', 'b.id_order=a.id_order', 'inner');
        $this->datatables->join('customer c', 'c.id_customer=b.id_customer', 'inner');
        $this->datatables->join('employee d', 'd.id_employee=a.id_employee', 'inner');
        $this->datatables->join('mitra_profile e', 'e.id_employee=d.id_employee', 'inner');
        $this->datatables->where('a.status', 4);
        if ($this->adm_level == 4) {
            $this->datatables->where('a.id_employee', $this->adm_id);
        } elseif (in_array($this->adm_level, $this->arrAccessKorwil, true)) {
            $this->datatables->where('c.kabupaten IN (SELECT aa.kabupaten_kota FROM employee_kabupaten_kota aa WHERE aa.id_employee = '.$this->adm_id.')');
        }
        if (in_array($this->adm_level, array_merge($this->backoffice_superadmin_area, $this->arrAccessKorwil), true)) {
            $this->datatables->edit_column('no_pd', '<a href="'.base_url(ADMIN_PATH.'/comission/pd/$1').'">$1</a>',
                'no_pd');
        }
        $this->datatables->edit_column('reference', '<a href="'.base_url(ADMIN_PATH.'/comission/payout/$1').'">$2</a>',
            'id, reference');
        $this->output->set_output($this->datatables->generate());
    }

    public function paidoff()
    {
        $data['page_title'] = 'Komisi Pesanan Dibayar | '.date('Y-m-d_His');
        $this->_output['content'] = $this->load->view('admin/comission/list_paidoff', $data, true);
        $this->_output['script_js'] = $this->load->view('admin/comission/js', '', true);
        $this->load->view('admin/template', $this->_output);
    }

    public function popupPaidoff($noPD)
    {
        $data['noPD'] = $noPD;
        $this->load->view('admin/comission/popup_paidoff', $data);
    }

    public function updatePaidoff()
    {
        if ( ! $this->input->is_ajax_request()) {
            return false;
        }
        if (in_array($this->adm_level, $this->auditor_area, true)) {
            $callBack = [
                'success' => 'false',
                'message' => 'Maaf, anda tidak dapat melakukan proses ini.',
            ];
            $this->session->set_flashdata('msg_failed', $callBack['message']);
        } else {
            $noPD = $this->input->post('no_pd');
            $paidDate = $this->input->post('paid_date');
            $data = [
                'transfer_date' => $paidDate,
                'modified_date' => date('Y-m-d H:i:s'),
                'modified_by' => $this->adm_id,
            ];
            $this->db->trans_begin();
            $this->mod_general->updateData($this->tblPayout, $data, 'no_pd', $noPD);
            if ($this->db->trans_status() == true) {
                $this->db->trans_commit();
                $callBack = [
                    'success' => 'true',
                    'message' => 'Rencana tanggal transfer komisi berhasil disimpan.',
                ];
                $this->session->set_flashdata('msg_success', $callBack['message']);
            } else {
                $this->db->trans_rollback();
                $callBack = [
                    'success' => 'false',
                    'message' => 'Gagal menyimpan tanggal rencana transfer.',
                ];
                $this->session->set_flashdata('msg_failed', $callBack['message']);
            }
        }
        echo json_encode($callBack, true);
    }

    public function listProcessedBatch()
    {
        if ( ! $this->input->is_ajax_request()) {
            return false;
        }
        $this->load->library('datatables');
        $this->output->set_header('Content-Type:application/json; charset=utf-8');
        $this->datatables->select('a.no_pd AS no_pd,
                    (SELECT COUNT(DISTINCT x.id_employee) FROM '.$this->tblPayout.' x WHERE x.no_pd=a.no_pd) AS total_mitra, 
                    IF(a.is_bca=1, "BCA", "Lainnya") AS tipe,
                    IF(b.category="Kelas 1" OR b.category="Kelas 2" OR b.category="Kelas 3" OR b.category="Kelas 4" OR b.category="Kelas 5" OR b.category="Kelas 6" OR YEAR(b.date_add)>2018, "PT. Mitra Edukasi Nusantara (MEN)", "PT. Gramedia") AS company,
                    SUM(ROUND(a.percentage * b.total_paid) - ROUND(a.tax * (a.percentage * b.total_paid))) AS total_amount,
                    DATE_FORMAT(c.created_date, "%Y-%m-%d") AS tgl_diproses, 
                    a.transfer_date AS date_transfered');
        $this->datatables->from($this->tblPayout.' a');
        $this->datatables->join('orders b', 'b.id_order=a.id_order', 'inner');
        $this->datatables->join('payout_history c', 'c.id_payout=a.id AND c.id_payout_status=3 AND c.notes=""');
        $this->datatables->where('a.status', 3);
        $this->datatables->where('a.is_posting', 0);
        $this->datatables->group_by('a.no_pd');
        $this->datatables->edit_column('no_pd', '<a href="'.base_url(ADMIN_PATH.'/comission/pd/$1').'">$1</a>',
            'no_pd');
        $this->output->set_output($this->datatables->generate());
    }

    public function processedBatch()
    {
        if ( ! in_array($this->adm_level, $this->arrAccessFinance, true)) {
            redirect(ADMIN_PATH.'/comission', 'refresh');
        }
        $data['page_title'] = 'Komisi Pesanan Diproses | '.date('Y-m-d_His');
        $this->_output['content'] = $this->load->view('admin/comission/list_processed_batch', $data, true);
        $this->_output['script_js'] = $this->load->view('admin/comission/js', '', true);
        $this->load->view('admin/template', $this->_output);
    }

    public function listProcessedBatchFailed()
    {
        if ( ! $this->input->is_ajax_request()) {
            return false;
        }
        $this->load->library('datatables');
        $this->output->set_header('Content-Type:application/json; charset=utf-8');
        $this->datatables->select('a.no_pd AS no_pd,
                    (SELECT COUNT(DISTINCT x.id_employee) FROM '.$this->tblPayout.' x WHERE x.no_pd=a.no_pd) AS total_mitra, 
                    IF(a.is_bca=1, "BCA", "Lainnya") AS tipe,
                    IF(b.category="Kelas 1" OR b.category="Kelas 2" OR b.category="Kelas 3" OR b.category="Kelas 4" OR b.category="Kelas 5" OR b.category="Kelas 6" OR YEAR(b.date_add)>2018, "PT. Mitra Edukasi Nusantara (MEN)", "PT. Gramedia") AS company,
                    SUM(ROUND(a.percentage * b.total_paid) - ROUND(a.tax * (a.percentage * b.total_paid))) AS total_amount,
                    DATE_FORMAT(c.created_date, "%Y-%m-%d") AS tgl_diproses, 
                    a.transfer_date AS date_transfered, if(a.`is_posting`="0", "Belum diposting ", "Gagal posting ") as keterangan');
        $this->datatables->from($this->tblPayout.' a');
        $this->datatables->join('orders b', 'b.id_order=a.id_order', 'inner');
        $this->datatables->join('payout_history c', 'c.id_payout=a.id AND c.id_payout_status=3 AND c.notes=""');
        $this->datatables->where('a.status', 3);
        $this->datatables->where('a.is_posting', -1);
        $this->datatables->group_by('a.no_pd');
        $this->datatables->edit_column('no_pd', '<a href="'.base_url(ADMIN_PATH.'/comission/pd_failed/$1').'">$1</a>',
            'no_pd');
        $this->output->set_output($this->datatables->generate());
    }

    public function processedBatchFailed()
    {
        if ( ! in_array($this->adm_level, $this->arrAccessFinance, true)) {
            redirect(ADMIN_PATH.'/comission', 'refresh');
        }
        $data['page_title'] = 'Komisi Gagal Posting | '.date('Y-m-d_His');
        $this->_output['content'] = $this->load->view('admin/comission/list_processed_batch_failed', $data, true);
        $this->_output['script_js'] = $this->load->view('admin/comission/js', '', true);
        $this->load->view('admin/template', $this->_output);
    }

    public function listIsPosting()
    {
        if ( ! $this->input->is_ajax_request()) {
            return false;
        }
        $this->load->library('datatables');
        $this->output->set_header('Content-Type:application/json; charset=utf-8');
        $this->datatables->select('a.no_pd AS no_pd,
                    (SELECT COUNT(DISTINCT x.id_employee) FROM '.$this->tblPayout.' x WHERE x.no_pd=a.no_pd) AS total_mitra, 
                    IF(a.is_bca=1, "BCA", "Lainnya") AS tipe,
                    IF(b.category="Kelas 1" OR b.category="Kelas 2" OR b.category="Kelas 3" OR b.category="Kelas 4" OR b.category="Kelas 5" OR b.category="Kelas 6" OR YEAR(b.date_add)>2018, "PT. Mitra Edukasi Nusantara (MEN)", "PT. Gramedia") AS company,
                    SUM(ROUND(a.percentage * b.total_paid) - ROUND(a.tax * (a.percentage * b.total_paid))) as total_amount,
                    a.transfer_date AS date_transfered');
        $this->datatables->from($this->tblPayout.' a');
        $this->datatables->join('orders b', 'b.id_order=a.id_order', 'inner');
        $this->datatables->where('a.status', 3);
        $this->datatables->where('a.is_posting', 1);
        $this->datatables->group_by('a.no_pd');
        $this->datatables->edit_column('no_pd', '<a href="'.base_url(ADMIN_PATH.'/comission/pd/$1').'">$1</a>',
            'no_pd');
        $this->output->set_output($this->datatables->generate());
    }

    public function isPosting()
    {
        if ( ! in_array($this->adm_level, $this->arrAccessFinance, true)) {
            redirect(ADMIN_PATH.'/comission', 'refresh');
        }
        $data['page_title'] = 'Komisi Sudah Diposting';
        $this->_output['content'] = $this->load->view('admin/comission/list_is_posting', $data, true);
        $this->_output['script_js'] = $this->load->view('admin/comission/js', '', true);
        $this->load->view('admin/template', $this->_output);
    }

    public function pd($nopd)
    {
        if ($nopd && is_numeric($nopd)) {
            $data['payout_comission'] = $this->mod_general->getAll($this->tblPayout, '', 'no_pd='.$nopd);
            if ($data['payout_comission']) {
                $data['payout_status'] = $this->mod_general->getAll('payout_state', '',
                    'id='.$data['payout_comission'][0]->status)[0];
                $data['adm_level'] = $this->adm_level;
                if (in_array($this->adm_level, [6, 7, 14], true)) {
                    if ($data['payout_comission'][0]->status == 3 && $data['payout_comission'][0]->is_posting !== 1) {
                        $url = 'processedBatch';
                    } elseif ($data['payout_comission'][0]->status == 3 && $data['payout_comission'][0]->is_posting == 1) {
                        $url = 'isPosting';
                    } else {
                        $url = 'paidBatch';
                    }
                } else {
                    $url = ($data['payout_comission'][0]->status == 3) ? 'processed' : 'paidoff';
                }
                $data['url_back'] = base_url().ADMIN_PATH.'/comission/'.$url;
                $payoutComission = $this->mod_comission->getPayoutComission($nopd);
                $num = 0;
                foreach ($payoutComission as $row) {
                    foreach ($row as $field => $value) {
                        $data['detail'][$num][$field] = $value;
                    }
                    $payoutComissionDetail = $this->mod_comission->getPayoutComissionDetail($nopd, $row['id_employee']);
                    $totalAmount = 0;
                    $numb = 0;
                    foreach ($payoutComissionDetail as $datas) {
                        $data['detail'][$num]['orders'][$numb] = $datas;
                        $totalAmount += $datas['total_amount'];
                        $numb++;
                    }
                    $data['detail'][$num]['rows'] = count($payoutComissionDetail);
                    $data['detail'][$num]['total_amount'] = $totalAmount;
                    $num++;
                }
                $this->_output['content'] = $this->load->view('admin/comission/detail_payout', $data, true);
                $this->_output['script_css'] = $this->load->view('admin/comission/css', '', true);
                $this->_output['script_js'] = $this->load->view('admin/comission/js', '', true);
                $this->load->view('admin/template', $this->_output);
            } else {
                redirect(ADMIN_PATH.'/comission/processedBatch', 'refresh');
            }
        } else {
            redirect(ADMIN_PATH.'/comission/processedBatch', 'refresh');
        }
    }

    public function pd_failed($nopd)
    {
        if ($nopd && is_numeric($nopd)) {
            $data['payout_comission'] = $this->mod_general->getAll($this->tblPayout, '', 'no_pd='.$nopd);
            if ($data['payout_comission']) {
                $data['payout_status'] = $this->mod_general->getAll('payout_state', '',
                    'id='.$data['payout_comission'][0]->status)[0];
                $data['adm_level'] = $this->adm_level;
                if (in_array($this->adm_level, [6, 7, 14], true)) {
                    if ($data['payout_comission'][0]->status == 3 && $data['payout_comission'][0]->is_posting !== 1) {
                        $url = 'processedBatchFailed';
                    } elseif ($data['payout_comission'][0]->status == 3 && $data['payout_comission'][0]->is_posting == 1) {
                        $url = 'isPosting';
                    } else {
                        $url = 'paidBatch';
                    }
                } else {
                    $url = ($data['payout_comission'][0]->status == 3) ? 'processed' : 'paidoff';
                }
                $data['url_back'] = base_url().ADMIN_PATH.'/comission/'.$url;
                $payoutComission = $this->mod_comission->getPayoutComission($nopd);
                $num = 0;
                foreach ($payoutComission as $row) {
                    foreach ($row as $field => $value) {
                        $data['detail'][$num][$field] = $value;
                    }
                    $payoutComissionDetail = $this->mod_comission->getPayoutComissionDetail($nopd, $row['id_employee']);
                    $totalAmount = 0;
                    $numb = 0;
                    foreach ($payoutComissionDetail as $datas) {
                        $data['detail'][$num]['orders'][$numb] = $datas;
                        $totalAmount += $datas['total_amount'];
                        $numb++;
                    }
                    $data['detail'][$num]['rows'] = count($payoutComissionDetail);
                    $data['detail'][$num]['total_amount'] = $totalAmount;
                    $num++;
                }
                $this->_output['content'] = $this->load->view('admin/comission/detail_payout_failed', $data, true);
                $this->_output['script_css'] = $this->load->view('admin/comission/css', '', true);
                $this->_output['script_js'] = $this->load->view('admin/comission/js', '', true);
                $this->load->view('admin/template', $this->_output);
            } else {
                redirect(ADMIN_PATH.'/comission/processedBatchFailed', 'refresh');
            }
        } else {
            redirect(ADMIN_PATH.'/comission/processedBatchFailed', 'refresh');
        }
    }

    public function listPaidBatch()
    {
        if ( ! $this->input->is_ajax_request()) {
            return false;
        }
        $this->load->library('datatables');
        $this->output->set_header('Content-Type:application/json; charset=utf-8');
        $this->datatables->select('a.no_pd AS no_pd,
                    (SELECT COUNT(DISTINCT x.id_employee) FROM '.$this->tblPayout.' x WHERE x.no_pd=a.no_pd) AS total_mitra, 
                    IF(a.is_bca=1, "BCA", "Lainnya") AS tipe,
                    IF(b.category="Kelas 1" OR b.category="Kelas 2" OR b.category="Kelas 3" OR b.category="Kelas 4" OR b.category="Kelas 5" OR b.category="Kelas 6" OR YEAR(b.date_add)>2018, "PT. Mitra Edukasi Nusantara (MEN)", "PT. Gramedia") AS company,
                    SUM(ROUND(a.percentage * b.total_paid) - ROUND(a.tax * (a.percentage * b.total_paid))) as total_amount, 
                    DATE_FORMAT(c.created_date, "%Y-%m-%d") AS tgl_diproses, 
                    a.transfer_date AS date_transfered');
        $this->datatables->from($this->tblPayout.' a');
        $this->datatables->join('orders b', 'b.id_order=a.id_order', 'inner');
        $this->datatables->join('payout_history c', 'c.id_payout=a.id AND c.id_payout_status=3 AND c.notes=""');
        $this->datatables->where('a.status', 4);
        $this->datatables->group_by('a.no_pd');
        $this->datatables->edit_column('no_pd', '<a href="'.base_url(ADMIN_PATH.'/comission/pd/$1').'">$1</a>',
            'no_pd');
        $this->output->set_output($this->datatables->generate());
    }

    public function paidBatch()
    {
        if ( ! in_array($this->adm_level, [6, 7, 14], true)) {
            redirect(ADMIN_PATH, 'refresh');
        }
        $data['page_title'] = 'Komisi Pesanan Dibayar | '.date('Y-m-d_His');
        $this->_output['content'] = $this->load->view('admin/comission/list_paid_batch', $data, true);
        $this->_output['script_js'] = $this->load->view('admin/comission/js', '', true);
        $this->load->view('admin/template', $this->_output);
    }

    public function summary()
    {
        if ($this->adm_level !== 4) {
            redirect(ADMIN_PATH, 'refresh');
        }
        $data['payout'] = $this->mod_comission->getComissionOrder($this->tblPayout.'.*,
            payout_state.name AS status, 
            payout_state.label AS status_label, 
            orders.reference, 
            orders.total_paid, 
            orders.date_add AS date_order, 
            customer.school_name, 
            customer.provinsi, 
            customer.kabupaten', $this->tblPayout.'.id_employee='.$this->adm_id, $this->tblPayout.'.id ASC', null,'10');
        $data['pending_payout'] = $this->mod_comission->getComissionByEmployee($this->adm_id,
            $this->tblPayout.'.status < 4')[0]->total;
        $data['success_payout'] = $this->mod_comission->getComissionByEmployee($this->adm_id,
            $this->tblPayout.'.status = 4')[0]->total;
        $data['page_title'] = 'Ringkasan Komisi';
        $this->_output['content'] = $this->load->view('admin/comission/summary', $data, true);
        $this->_output['script_js'] = $this->load->view('admin/comission/js', '', true);
        $this->load->view('admin/template', $this->_output);
    }

    public function printPesananDana($noPD)
    {
        $data['payout_comission'] = $this->mod_general->getAll($this->tblPayout, '', 'no_pd='.$noPD)[0];
        $data['payout_status'] = $this->mod_general->getAll('payout_state', '',
            'id='.$data['payout_comission']->status)[0];
        $data['company'] = $this->mod_comission->getCompanyName($noPD);
        $data['detail'] = [];
        $payoutComission = $this->mod_comission->getPayoutComission($noPD);
        $num = 0;
        foreach ($payoutComission as $row) {
            foreach ($row as $field => $value) {
                $data['detail'][$num][$field] = $value;
            }
            $payoutComissionDetail = $this->mod_comission->getPayoutComissionDetail($noPD, $row['id_employee']);
            $totalAmount = 0;
            $numb = 0;
            foreach ($payoutComissionDetail as $datas) {
                $data['detail'][$num]['orders'][$numb] = $datas;
                $totalAmount += $datas['total_amount'];
                $numb++;
            }
            $data['detail'][$num]['rows'] = count($payoutComissionDetail);
            $data['detail'][$num]['total_amount'] = $totalAmount;
            $num++;
        }
        $this->load->view('admin/comission/print/pesanan_dana', $data);
    }

    public function printPajak($noPD, $idMitra)
    {
        if ($noPD && $idMitra) {
            $data['full_year'] = date("Y");
            $data['date_indo'] = tgl_indo(date('Y-m-d'), 2);
            $data['tax_slip'] = $this->mod_comission->getDetailPrintTax($noPD, $idMitra)[0];
            $data['tax_slip']['comission_tax'] *= 100;
            $data['tax_slip']['mitra_npwp'] = str_replace(['+', '-', '.', ',', '_', ' '], '',
                filter_var($data['tax_slip']['mitra_npwp'], FILTER_SANITIZE_NUMBER_INT));
            $transfer_date = strtotime($data['tax_slip']['transfer_date']);
            $data['day'] = $transfer_date ? date('d', $transfer_date) : date('d');
            $data['month'] = $transfer_date ? date('m', $transfer_date) : date('m');
            $data['year'] = $transfer_date ? date('y', $transfer_date) : date('y');
            if ($data['tax_slip']['comission_tax'] == 2) {
                $data['tax_slip']['total_comission_amount'] = round($data['tax_slip']['comission_amount'] * $data['tax_slip']['comission_tax'] / 100);
                $this->load->view('admin/comission/print/bukti_potong_badan/template', $data);
            } else {
                $data['no_pph'] = $data['tax_slip']['no_pph'];
                $data['tax_slip']['no_pesanan_dana'] = sprintf("%07s", $data['tax_slip']['no_pesanan_dana']);
                $data['tax_slip']['comission_tax_real'] = round($data['tax_slip']['comission_tax'] * 2);
                $data['tax_slip']['comission_amount_half'] = round($data['tax_slip']['comission_amount'] / 2);
                $data['tax_slip']['total_comission_amount'] = round($data['tax_slip']['comission_amount_half'] * $data['tax_slip']['comission_tax_real'] / 100);
                $this->load->view('admin/comission/print/bukti_potong_pribadi/template', $data);
            }
        } else {
            return false;
        }
    }

    # TODO : add logs and block permission for auditor

    public function sendPesananDana()
    {
        if (in_array($this->adm_level, $this->auditor_area, true)) {
            $callBack = [
                'success' => false,
                'message' => 'Maaf, anda tidak dapat melakukan proses ini.',
            ];
            ajaxResponse(400, $callBack);
        } else {
            $noPd = $this->input->post('no_pd', true);
            $dataComission = $this->mod_comission->getPayoutDetail($noPd);

            if ($dataComission) {
                $arrHeader = [];
                $arrDetail = [];
                foreach ($dataComission as $rows => $value) {
                    ++$rows;
                    if ($value == reset($dataComission)) {
                        $arrHeader = [
                            'no_pd' => $value->no_pd,
                            'created_date' => $value->created_date,
                            'transfer_date' => $value->tgl_transfer,
                            'no_pd_kolektif' => $value->no_pd_kolektif,
                        ];
                    }
                    $arrDetail[] = [
                        'hpd_no' => $value->no_pd,
                        'dpd_seq_no' => $rows,
                        'dpd_pay_id' => env('DPD_PAY_ID'),
                        'dpd_currency' => env('DPD_CURRENCY'),
                        'dpd_amount' => $value->total_amount,
                        'dpd_biaya_transfer' => 0,
                        'dpd_note' => 'Pesanan : '.$value->kode_pesanan,
                        'dpd_atas_nama' => strtoupper($value->nama),
                        'dpd_due_date' => tgl_indo($value->transfer_date, 8),
                        'dpd_bg_atas_nama' => '',
                        'dpd_transfer_atas_nama' => strtoupper($value->nama_rekening),
                        'dpd_bank_name' => strtoupper($value->alias_bank),
                        'dpd_bank_address' => '',
                        'dpd_bank_city' => '',
                        'dpd_bank_account' => $value->no_rekening,
                        'dpd_bank_iban' => '',
                        'dpd_curr_account' => env('DPD_CURR_ACCOUNT'),
                        'dpd_draft_atas_nama' => '',
                        'dpd_draft_address' => '',
                        'dpd_draft_country' => '',
                        'dpd_biaya_transfer_flag' => 0,
                        'dpd_bank_swift_code' => '',
                        'dpd_bank_sort_code' => '',
                        'dpd_bank_branch_id_code' => '',
                        'dpd_bank_vat' => '',
                        'dpd_bank_aba_routing' => '',
                        'sandi_bi' => $value->kode_bank,
                        'LegacyId' => env('LEGACYID'),
                    ];
                }

                $posting = $this->postPDOnline($arrHeader, $arrDetail);
                if ($posting == 200) {
                    $dataPayout = [
                        'is_posting' => 1,
                        'modified_date' => date('Y-m-d H:i:s'),
                        'modified_by' => $this->adm_id,
                    ];
                    $this->mod_general->updateData($this->tblPayout, $dataPayout, 'no_pd', $noPd);

                    $payout_list = $this->mod_general->getAll($this->tblPayout, 'id', 'no_pd='.$noPd, 'id ASC');
                    foreach ($payout_list as $idPayout) {
                        $notes = 'Posting ke CMS PD Online';
                        $this->mod_comission->addHistory($idPayout->id, $this->adm_id, 3, $notes);
                    }

                    $callBack = [
                        'success' => true,
                        'message' => 'Pesanan dana: #'.$noPd.' berhasil di-POSTING.',
                        'redirect' => 'comission/processedBatch',
                    ];
                    $this->session->set_flashdata($callBack['message'], 'msg_success_commision');
                    ajaxResponse(200, $callBack);
                } else {
                    $dataPayout = [
                        'is_posting' => -1,
                        'modified_date' => date('Y-m-d H:i:s'),
                        'modified_by' => $this->adm_id,
                    ];
                    $this->mod_general->updateData($this->tblPayout, $dataPayout, 'no_pd', $noPd);

                    $callBack = [
                        'success' => false,
                        'message' => 'Gagal melakukan POSTING PD. <br>'.$posting,
                    ];
                    ajaxResponse(400, $callBack);
                }
            } else {
                $callBack = [
                    'success' => false,
                    'message' => 'Maaf, data pesanan dana tidak ditemukan.',
                ];
                ajaxResponse(400, $callBack);
            }
        }
    }

    # TODO : add logs and block permission for auditor

    private function postPDOnline($header, $detail)
    {
        if ( ! empty($header)) {
            $constHeader = [
                'hpd_type' => env('HPD_TYPE'),
                'hpd_unit' => env('HPD_UNIT'),
                'hpd_kelompok' => env('HPD_KELOMPOK'),
                'hpd_bagian' => env('HPD_BAGIAN'),
                'hpd_beban_unit' => env('HPD_BEBAN_UNIT'),
                'hpd_note' => env('HPD_NOTE'),
                'hpd_penyusun' => env('HPD_PENYUSUN'),
                'hpd_dir_kel' => env('HPD_DIR_KEL'),
                'hpd_jabdirkel' => env('HPD_JABDIRKEL'),
                'hpd_jabdirkeu' => env('HPD_JABDIRKEU'),
                'hpd_nik_penyusun' => env('HPD_NIK_PENYUSUN'),
                'hpd_sign' => env('HPD_SIGN'),
                'LegacyId' => env('LEGACYID'),
            ];
            try {
                $request = $this->client->request('POST', '/api/PDHeader', [
                    'timeout' => 144000,
                    'headers' => [
                        'Authorization' => 'Bearer '.env('PD_AUTH'),
                        'Content-Type' => 'application/json',
                    ],
                    'json' => [
                        'hpd_no' => $header['no_pd'],
                        'hpd_trx_date' => tgl_indo($header['created_date'], 8),
                        'hpd_type' => $constHeader['hpd_type'],
                        'hpd_unit' => $constHeader['hpd_unit'],
                        'hpd_kelompok' => $constHeader['hpd_kelompok'],
                        'hpd_bagian' => $constHeader['hpd_bagian'],
                        'hpd_beban_unit' => $constHeader['hpd_beban_unit'],
                        'hpd_due_date' => tgl_indo($header['transfer_date'], 8),
                        'hpd_note' => $constHeader['hpd_note'],
                        'hpd_pemesan' => '',
                        'hpd_penyusun' => $constHeader['hpd_penyusun'],
                        'hpd_dir_kel' => $constHeader['hpd_dir_kel'],
                        'hpd_dir_keu' => '',
                        'hpd_jabdirkel' => $constHeader['hpd_jabdirkel'],
                        'hpd_jabdirkeu' => $constHeader['hpd_jabdirkeu'],
                        'hpd_nik_penyusun' => $constHeader['hpd_nik_penyusun'],
                        'hpd_kolektif_id' => $header['no_pd_kolektif'],
                        'hpd_sign' => $constHeader['hpd_sign'],
                        'LegacyId' => $constHeader['LegacyId'],
                        'details' => $detail,
                    ],
                ]);

                return $request->getStatusCode();
            } catch (GuzzleHttp\Exception\ClientException $e) {
                $response = $e->getResponse();
                $responseBodyAsString = $response->getBody()->getContents();

                return json_decode($responseBodyAsString)->Message;
            } catch (GuzzleHttp\Exception\GuzzleException $e) {
                // return json_decode($e);
                return $e->getMessage();
            }
        } else {
            return false;
        }
    }

    public function setKomisiDibayar()
    {
        if (in_array($this->adm_level, $this->auditor_area, true)) {
            $callBack = [
                'success' => false,
                'message' => 'Maaf, anda tidak dapat melakukan proses ini.',
            ];
            ajaxResponse(400, $callBack);
        } else {
            $noPD = $this->input->post('no_pd', true);
            $this->db->trans_begin();
            $dataComission = $this->mod_general->getAll($this->tblPayout, '',
                'no_pd='.$noPD.' AND status=3 AND is_posting=1', 'transfer_date asc, no_pd asc');
            if ($dataComission) {
                try {
                    foreach ($dataComission as $item) {
                        $dataPayoutComission = [
                            'status' => 4,
                            'modified_date' => date('Y-m-d H:i:s'),
                            'modified_by' => $this->adm_id,
                        ];
                        $tipe = $item->type == 1 ? 'langsung' : 'referensi';
                        $paidDate = $item->transfer_date;
                        $notes = 'Transfer dana komisi '.$tipe.' ('.$paidDate.')';
                        $this->mod_general->updateData($this->tblPayout, $dataPayoutComission, 'id', $item->id);
                        $this->mod_comission->addHistory($item->id, $this->adm_id, 4, $notes);
                    }
                    if ($this->db->trans_status() == true) {
                        $this->db->trans_commit();
                        $callBack = [
                            'success' => false,
                            'message' => 'Status Komisi Pesanan Dana <b>#'.$noPD.'</b> berhasil diubah menjadi <b>"Dibayar"</b>',
                            'redirect' => 'comission/isPosting',
                        ];
                        $this->session->set_flashdata('msg_success_commision', $callBack['message']);
                        ajaxResponse(200, $callBack);
                    }
                } catch (Exception $e) {
                    $this->db->trans_rollback();
                    $callBack = [
                        'success' => false,
                        'message' => $e->getMessage(),
                    ];
                    ajaxResponse(400, $callBack);
                }
            } else {
                $callBack = [
                    'success' => false,
                    'message' => 'Maaf, data pesanan dana tidak ditemukan.',
                ];
                ajaxResponse(400, $callBack);
            }
        }
    }

    public function setKomisiDibayarPdFailed()
    {
        if (in_array($this->adm_level, $this->auditor_area, true)) {
            $callBack = [
                'success' => false,
                'message' => 'Maaf, anda tidak dapat melakukan proses ini.',
            ];
            ajaxResponse(400, $callBack);
        } else {
            $noPD = $this->input->post('no_pd', true);
            $this->db->trans_begin();
            $dataComission = $this->mod_general->getAll($this->tblPayout, '',
                'no_pd='.$noPD.' AND status=3 AND is_posting=-1', 'transfer_date asc, no_pd asc');
            if ($dataComission) {
                try {
                    foreach ($dataComission as $item) {
                        $dataPayoutComission = [
                            'status' => 4,
                            'modified_date' => date('Y-m-d H:i:s'),
                            'modified_by' => $this->adm_id,
                        ];
                        $tipe = $item->type == 1 ? 'langsung' : 'referensi';
                        $paidDate = $item->transfer_date;
                        $notes = 'Transfer dana komisi '.$tipe.' ('.$paidDate.')';
                        $this->mod_general->updateData($this->tblPayout, $dataPayoutComission, 'id', $item->id);
                        $this->mod_comission->addHistory($item->id, $this->adm_id, 4, $notes);
                    }
                    if ($this->db->trans_status() == true) {
                        $this->db->trans_commit();
                        $callBack = [
                            'success' => false,
                            'message' => 'Status Komisi Pesanan Dana <b>#'.$noPD.'</b> berhasil diubah menjadi <b>"Dibayar"</b>',
                            'redirect' => 'comission/isPosting',
                        ];
                        $this->session->set_flashdata('msg_success_commision', $callBack['message']);
                        ajaxResponse(200, $callBack);
                    }
                } catch (Exception $e) {
                    $this->db->trans_rollback();
                    $callBack = [
                        'success' => false,
                        'message' => $e->getMessage(),
                    ];
                    ajaxResponse(400, $callBack);
                }
            } else {
                $callBack = [
                    'success' => false,
                    'message' => 'Maaf, data pesanan dana tidak ditemukan.',
                ];
                ajaxResponse(400, $callBack);
            }
        }
    }

    /*
    public function recapPPh($startDate, $endDate)
    {
        if ($startDate && $endDate) {
            $dataComission = $this->mod_comission->getListPPhByDate($startDate, $endDate);
            echo '<pre>';
            if ($dataComission) {
                foreach ($dataComission as $item) {
                    if ($item['no_pd'] && $item['id_mitra']) {
                        $dataPPHMitra = $this->mod_comission->getDetailPrintTax($item['no_pd'], $item['id_mitra'])[0];
                        print_r($dataPPHMitra);
//                        $data['tax_slip']['comission_tax'] = ($dataPPHMitra['comission_tax'] * 100);
//                        if ($data['tax_slip']['comission_tax'] == 2) {
//                            $data['tax_slip']['total_comission_amount'] = round($data['tax_slip']['comission_amount'] * $data['tax_slip']['comission_tax'] / 100);
//                        } else {
//                            $data['no_pph'] = $data['tax_slip']['no_pph'];
//                            $data['tax_slip']['comission_tax_real'] = round($data['tax_slip']['comission_tax'] * 2);
//                            $data['tax_slip']['comission_amount_half'] = round($data['tax_slip']['comission_amount'] / 2);
//                            $data['tax_slip']['total_comission_amount'] = round($data['tax_slip']['comission_amount_half'] * $data['tax_slip']['comission_tax_real'] / 100);
//                        }
                    } else {
                        return false;
                    }
                }
            }
        } else {
            return false;
        }
    }
    */

    public function indexPPhAmount()
    {
        $data['page_title'] = 'Laporan Rekap Bukti Potong PPh 21';
        $this->_output['content'] = $this->load->view('admin/comission/laporan_pph_amount', $data, true);
        $this->_output['script_js'] = $this->load->view('admin/comission/js', '', true);
        $this->load->view('admin/template', $this->_output);
    }

    public function listPPhAmount()
    {
        if ( ! $this->input->is_ajax_request()) {
            return false;
        }
        $this->load->library('datatables');
        $this->output->set_header('Content-Type:application/json; charset=utf-8');
        $start_date = $this->input->post('start_date') ?? null;
        $end_date = $this->input->post('end_date') ?? null;
        // $this->datatables->select(' 
        //     a.no_pd AS no_pd,
        //     UPPER(c.name) AS nama,
        //     d.no_npwp AS no_npwp,
        //     SUM(ROUND(b.total_paid * a.percentage)) AS nilai_komisi,
        //     FORMAT((a.tax * 100), 2) AS pph,
        //     SUM(ROUND((b.total_paid * a.percentage) * a.tax)) AS nilai_pph,
        //     a.transfer_date AS tgl_transfer');

        $this->datatables->select(' 
            a.`no_pd` AS no_pd,
            month(a.`created_date`) AS masa_pajak,
            YEAR(a.`created_date`) AS periode,
            0 AS pembetulan,
            "" AS no_bukti_potong,
            d.`no_npwp` AS no_npwp,
            d.`identity_code` AS ktp,
            UPPER(c.name) AS nama,
            d.`address` AS alamat,
            "N" AS wp_luar_negeri,
            "" AS kode_negara,
            IF(YEAR(b.`date_add`) > 2018,"21-100-09","21-100-09") AS kode_pajak,
            SUM(ROUND(b.total_paid * a.percentage)) AS nilai_komisi,
            0.5*SUM(ROUND(b.total_paid * a.percentage)) AS jumlah_dpp,
            if(isnull(d.`no_npwp`) or d.`no_npwp`="","Y","N") AS non_npwp,
            "5" AS tarif,
            SUM(ROUND((b.total_paid * a.percentage) * a.tax)) AS nilai_pph,
            IF(YEAR(b.`date_add`) > 2018,"849117700509000","10026896092000") AS npwp_pemotong,
            IF(YEAR(b.`date_add`) > 2018,"PT. MITRA EDUKASI NUSANTARA (MEN)","PT. GRAMEDIA") AS nama_pemotong,
            a.transfer_date AS tgl_transfer');
        $this->datatables->from($this->tblPayout.' a');
        $this->datatables->join('orders b', 'a.id_order = b.id_order', 'inner');
        $this->datatables->join('employee c', 'a.id_employee = c.id_employee', 'inner');
        $this->datatables->join('mitra_profile d', 'c.id_employee = d.id_employee', 'inner');
        $this->datatables->where('(a.transfer_date is not null OR a.transfer_date <> "")');
        $this->datatables->where('a.status >= 3');
        if ($start_date && $end_date) {
            $this->datatables->where('a.transfer_date BETWEEN \''.$start_date.'\' AND \''.$end_date.'\'');
        }
        $this->datatables->group_by('a.no_pd, a.id_employee');
        $this->output->set_output($this->datatables->generate());
    }
}
