<?php
defined('BASEPATH') or exit('No direct script access allowed');

require_once APPPATH . 'third_party/PhpExportExcel.php';
require_once APPPATH . 'third_party/xlsxwriter.class.php';

/**
 * @property Datatables $datatables
 * @property Mod_general $mod_general
 * @property Mod_product $mod_product
 * @property Mod_mitra $mod_mitra
 * @property Mod_steam $mod_steam
 * @property Mod_general $mod_general
 * @property Mod_report $mod_report
 * @property Mymail $mymail
 */
class STEAM extends MY_Controller
{
    private $table;
    private $_output;

    private $arrAccessSteamAdmin;
    private $arrAccessSteamSales;

    public function __construct()
    {
        parent::__construct();
        $this->load->model('mod_general');
        $this->load->model('mod_product');
        $this->load->model('mod_steam');
        $this->load->helper('download');
        $this->table = 'product';
        $this->_output = [];

        $this->arrAccessSteamAdmin = [101, 102];
        $this->arrAccessSteamSales = [101, 102, 103, 104];
    }

    public function index()
    {
        $this->_output['content'] = $this->load->view('admin/product/list', '', true);
        $this->_output['script_js'] = $this->load->view('admin/product/js', '', true);
        $this->load->view('admin/template', $this->_output);
    }

    /**
     * CUSTOMER FEATURE
     */
    public function add_customer()
    {

    }

    public function add_customer_post()
    {

    }
    /**
     * END CUSTOMER FEATURE
     */
    
    /**
     * SALES FEATURE
     */
    public function add_sales()
    {

    }

    public function add_sales_post()
    {

    }
    /**
     * END SALES FEATURE
     */


    /**
     * ORDER FEATURE
     */
    public function get_customer()
    {
        $data = $this->input->post('search');
        // echo json_encode($data);
        $customer['results'] = $this->mod_steam->get_customer($data);
        echo json_encode($customer);
    }

    public function get_sales()
    {
        $data = $this->input->post('search');
        // echo json_encode($data);
        $sales['results'] = $this->mod_steam->get_sales($data);
        echo json_encode($sales);
    }

    public function order_add()
    {
        $data['customer'] = $this->mod_general->getAll("customer","*");
        // $data['sales'] = $this->mod_general->getAll("employee", "*", "divisi='STEAM'");

        $data['detil'] = array(
            "id_customer" => "",
            "total" => 0,
            "id_employee" => ""
        );


        // $data['kategori'] = $this->mod_product->getAll("category",'id_category, name',"active=1 and id_parent <> 0");
        // $data['detil'] = $this->mod_product->getList("product",'*',"id_product='$id_product'");
        $this->_output['content'] = $this->load->view('admin/steam/order_add', $data, TRUE);
        $this->_output['script_js'] = $this->load->view('admin/steam/order_js', '', true);
        $this->load->view('admin/template', $this->_output);
    }

    public function order_add_post()
    {
        // get kode reference
        
        $reference = generateRandomString();
        if($this->input->post('id_order') == true)
        {
            $reference = $this->input->post('id_order');
        }

        $id_customer = $this->input->post('customer');
        $category = "Produk STEAM";
        $type = "STEAM";
        $current_state = 9;
        $total_paid = $this->input->post('total_paid');
        $periode = getenv("PERIODE");
        $date_add = $this->input->post('date_add');
        // $date_upd = "";
        $tgl_konfirmasi = $date_add;

        $id_employee = $this->input->post('sales');
        $sales = $this->mod_general->detailData("employee", "id_employee", $id_employee);
        $sales_profile = $this->mod_general->detailData("mitra_profile", "id_employee", $id_employee);
        $sales_referer = $sales['email'];
        $sales_name = $sales['name'];
        $sales_phone = $sales['telp'];
        $percent_comission = $sales_profile['percent_comission_steam'];
        $kesepakatan_sampai = "14";
        $jangka_waktu = "14";
        // $tanggal_kirim = "";
        $sts_bayar = 2;
        $tgl_lunas = $date_add;

        $data = array(
            'reference' => $reference,
            'id_customer' => $id_customer,
            'category' => $category,
            'type' => $type,
            'current_state' => $current_state,
            'total_paid' => $total_paid,
            'periode' => $periode,
            'date_add' => $date_add,
            'jangka_waktu' => $jangka_waktu,
            'kesepakatan_sampai' => $kesepakatan_sampai,
            // 'date_upd' => $date_upd,
            'tgl_konfirmasi' => $tgl_konfirmasi,
            'sales_referer' => $sales_referer,
            'sales_name' => $sales_name,
            'sales_phone' => $sales_phone,
            'percent_comission' => $percent_comission,
            // 'tanggal_kirim' => $tanggal_kirim,
            'sts_bayar' => $sts_bayar,
            'tgl_lunas' => $tgl_lunas
        );

        $id_order = $this->mod_general->addData("order_steam", $data);
        if($id_order != null || $id_order != "")
        {
            $callBack = array(
                "success" => true,
                "message" => "Berhasil menambahkan data order"
            );
        }
        else
        {
            $callBack = array(
                "success" => false,
                "message" => "Gagal menambahkan data order"
            );
        }
        echo json_encode($callBack);
    }

    public function check_id_order()
    {
        $reference = $this->input->post("reference");
        $query = $this->mod_steam->check_id_order($reference);

        if($query)
        {
            if(count($query) > 0)
            {
                $callBack = array(
                    "success" => true,
                    "message" => "Maaf data dengan no pesanan : ". $reference. " sudah terinput ke sistem."
                ); 
            }
        }
        else
        {
            $callBack = array(
                "success" => false,
                "message" => "Belum ada data."
            ); 
        }
        echo json_encode($callBack);
    }
    /**
     * END ORDER FEATURE
     */
    
    /**
     * COMISSION FEATURE
     */
    public function comission_order_new()
    {
        // if ( ! in_array($this->adm_level, $this->arrAccessSteamSales, true)) {
        //     redirect(ADMIN_PATH, 'refresh');
        // }
        $data['page_title'] = 'Pesanan Dikirim & Sudah Terkonfirmasi Lunas | '.date('Y-m-d_His');
        $this->_output['content'] = $this->load->view('admin/steam/comission_order_new', $data, true);
        $this->_output['script_css'] = $this->load->view('admin/steam/comission_css', '', true);
        $this->_output['script_js'] = $this->load->view('admin/steam/comission_js', '', true);
        $this->load->view('admin/template', $this->_output);
    }

    public function comission_order_new_list()
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
        $this->datatables->from('order_steam a');
        $this->datatables->join('customer b', 'b.id_customer=a.id_customer', 'inner');
        $this->datatables->join('employee c', 'c.email=a.sales_referer AND c.level=4 AND c.active=1', 'inner');
        $this->datatables->join('mitra_profile d', 'd.id_employee=c.id_employee', 'inner');
        $this->datatables->where('a.current_state', 9);
        $this->datatables->where('a.sts_bayar', 2);
        // $this->datatables->where('YEAR(a.tgl_lunas) >= 2018');
        // $this->datatables->where('a.id_order IN (SELECT x.id_order FROM finance_history x)');
        if ($this->adm_level == 4) {
            $this->datatables->where('a.sales_referer', $this->adm_uname);
        } elseif (in_array($this->adm_level, $this->arrAccessSteamAdmin, true)) {
            // $this->datatables->where('b.kabupaten IN (SELECT aa.kabupaten_kota FROM employee_kabupaten_kota aa WHERE aa.id_employee = '.$this->adm_id.')');
        }
        $this->datatables->where('a.id_order NOT IN (SELECT id_order FROM payout_detail_steam)');
        $this->datatables->where('a.sales_referer IN (SELECT x.email FROM employee x INNER JOIN mitra_profile y ON y.id_employee=x.id_employee WHERE x.active=1 AND y.is_activated=1)');
        // $this->datatables->add_column('action', '<input type="checkbox" id="order_$1" class="cb_comission" value="$1" />', 'id_order, reference');
        $this->datatables->edit_column('reference', '<a href="'.base_url(ADMIN_PATH.'/steam/comission_order_new_detail/$1').'">$2</a>',
            'id_order, reference');
        $this->output->set_output($this->datatables->generate());
    }

    public function comission_order_new_detail($idOrder)
    {
        if ($idOrder && is_numeric($idOrder)) {
            $data['page_title'] = 'Detil Komisi';
            $data['order_states'] = $this->mod_general->getWhere('order_state', 'deleted', 0, 'id_order_state', 'asc');
            $data['detil'] = $this->mod_general->detailData('order_steam', 'id_order', $idOrder);
            $percentComission = 0.20;
            if ($data['detil']) {
                if ($data['detil']['sales_referer']) {
                    $detailMitra = $this->mod_general->detailData('employee', 'email', $data['detil']['sales_referer']);
                    if ($this->mod_steam->isMitra($data['detil']['sales_referer'])) {
                        $percentComission = $data['detil']['percent_comission'];
                        // $data['referral'] = $this->mod_steam->get_influencer($data['detil']['influencer_email']);
                        $data['uDirect'] = [
                            'nama' => $detailMitra['name'],
                            'email' => $detailMitra['email'],
                            'telpon' => $detailMitra['telp'],
                        ];
                    } else {
                        redirect(ADMIN_PATH.'/steam/comission_order_new');
                    }
                } else {
                    redirect(ADMIN_PATH.'/steam/comission_order_new');
                }
                $data['comission'] = $this->calculate_comission($idOrder, $data['detil']['total_paid'],
                    $percentComission, $data['detil']['sales_referer'], $data['detil']['influencer_email']);
                $data['customer'] = $this->mod_general->detailData('customer', 'id_customer',
                    $data['detil']['id_customer']);
                $data['url_back'] = base_url().ADMIN_PATH.'/steam/comission_order_new';
                $data['adm_level'] = $this->adm_level;
                if ($this->mod_steam->isInPayout($idOrder)) {
                    $data['data_payout'] = $this->mod_general->detailData('payout_detail_steam', 'id_order', $idOrder);
                    $data['history_payout'] = $this->mod_general->getWhere('payout_detail_steam_history', 'id_payout',
                        $data['data_payout']['id'], 'id', 'asc');
                    switch ($data['data_payout']['id_payout_status']) {
                        case 1:
                            $data['url_back'] = base_url().ADMIN_PATH.'/steam/comission_index';
                            break;
                        // case 2:
                        //     $data['url_back'] = base_url().ADMIN_PATH.'/comission/processed';
                        //     break;
                        // case 3:
                        //     $data['url_back'] = base_url().ADMIN_PATH.'/comission/paidOff';
                        //     break;
                    }
                }
                $this->_output['content'] = $this->load->view('admin/steam/comission_detail_order', $data, true);
                $this->_output['script_css'] = $this->load->view('admin/steam/comission_css', '', true);
                $this->_output['script_js'] = $this->load->view('admin/steam/comission_js', '', true);
                $this->load->view('admin/template', $this->_output);
            } else {
                redirect(ADMIN_PATH.'/steam/comission_order_new', 'refresh');
            }
        } else {
            redirect(ADMIN_PATH.'/steam/comission_order_new', 'refresh');
        }
    }

    public function influencer_popup($id_order)
    {
        $data['id_order'] = $id_order;
        $this->load->view('admin/steam/influencer_popup', $data);
    }

    public function influencer_popup_post()
    {
        $id_order = $this->input->post('id_order');
        $id_employee = $this->input->post('influencer');

        $query = $this->mod_steam->get_data_mitra($id_employee);

        if($query->num_rows() > 0)
        {
            $result = $query->row_array();

            $data = array(
                "influencer_email" => $result['email'],
                "influencer_name" => $result['name'],
                "influencer_phone" => $result['telp']
            );

            $q = $this->mod_general->updateData('order_steam', $data, 'id_order', $id_order);
            if($q)
            {
                $callBack = array(
                    'success' => true,
                    'message' => 'Berhasil menambahkan data influencer.'
                );
            }
            else
            {
                $callBack = array(
                    'success' => false,
                    'message' => 'Gagal menambahkan data influencer.'
                );
            }
        }
        else
        {
            $callBack = array(
                'success' => false,
                'message' => 'Data tidak influencer tidak lengkap, mohon lengkapi data.'
            );
        }

        echo json_encode($callBack);
    }

    # TODO : add logs and block permission for auditor
    public function comission_order_new_post()
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
                // $referalEmail = $this->input->post('referral_email', true);
                $personDirect = $this->mod_steam->getSales("employee.id_employee,
                    employee.name,
                    employee.email,
                    mitra_profile.id,
                    mitra_profile.bank_account_type", "employee.email = '$directEmail'")[0];
                $idEmployeeDirect = $personDirect->id_employee;
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
                // if ($referalEmail) {
                //     $personReferal = $this->mod_steam->getSales("employee.id_employee,
                //     employee.name,
                //     employee.email,
                //     mitra_profile.id,
                //     mitra_profile.bank_account_type,
                //     mitra_profile.percent_comission_steam", "employee.email = '$referalEmail'")[0];
                //     $idEmployeeReferal = $personReferal->id_employee;
                //     $referalComission = [
                //         'id_order' => $idOrder,
                //         'id_employee' => $idEmployeeReferal,
                //         'percentage' => $personReferal->percent_comission_steam,
                //         'tax' => $this->input->post('referral_tax'),
                //         'type' => 2,
                //         'is_bca' => $personReferal->bank_account_type == 1 ? 1 : 0,
                //         'created_date' => date('Y-m-d H:i:s'),
                //         'created_by' => $this->adm_id,
                //         'modified_date' => date('Y-m-d H:i:s'),
                //         'modified_by' => $this->adm_id,
                //     ];
                // }
                if ($this->mod_steam->isOrderExist($idOrder)) {
                    $callBack = [
                        'message' => 'Komisi untuk pesanan #'.$idOrder.' sudah diajukan!',
                        'success' => 'false',
                    ];
                    $this->session->set_flashdata('msg_failed', $callBack['message']);
                } else {
                    $this->db->trans_begin();
                    $insertDirect = $this->mod_general->addData('payout_detail_steam', $directComission);
                    if ($insertDirect) {
                        $this->mod_steam->addHistory($insertDirect, $this->adm_id, 1);

                        // if ($referalEmail) {
                        //     $insertReferal = $this->mod_general->addData('payout_detail_steam', $referalComission);
                        //     if ($insertReferal) {
                        //         $this->mod_steam->addHistory($insertReferal, $this->adm_id, 1);
                        //     }
                        // }
                        $update_order = array(
                            'date_upd' => date('Y-m-d H:i:s')
                        );
                        $this->mod_general->edit('order_steam',$update_order, array('id_order'=>$idOrder));

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

    public function calculate_comission($idOrder, $amount, $percentage, $emailSales = false, $emailInfluencer = false)
    {
        // $referralCommision = null;
        // $percentTaxReferral = null;
        $percentTax = null;
        if ($this->mod_steam->isHaveSales($idOrder) && $this->mod_steam->isMitra($emailSales)) {
            // if ($this->mod_steam->isHaveReferral($emailSales)) {
            //     $referral = $this->mod_steam->getReferral($emailSales);
            //     $emailReferral = $referral['email'];
            //     if ($this->mod_steam->isMitra($emailReferral)) {
            //         $referralCommision = round($amount * 0.01);
            //         $percentTaxReferral = $this->mod_steam->getPercentTax($emailReferral);
            //     }
            // }
            $percentTax = $this->mod_steam->getPercentTax($emailSales);
        }

        // if($emailInfluencer)
        // {
        //     $data_influencer = $this->mod_steam->get_influencer($emailInfluencer);
        //     if($data_influencer)
        //     {
        //         $referralCommision = round($amount * $data_influencer['percentage']);
        //         $percentTaxReferral = $data_influencer['percent_tax'];
        //     }
        // }

        $directComission = round($percentage * $amount);
        $comission = [
            'direct' => [
                'percentage' => $percentage,
                'amount' => $directComission,
                'tax' => $percentTax,
                'tax_value' => round($directComission * $percentTax),
                'payout' => $directComission - round($directComission * $percentTax),
            ]
            // ,
            // 'referral' => [
            //     'amount' => $referralCommision,
            //     'tax' => $percentTaxReferral,
            //     'tax_value' => round($referralCommision * $percentTaxReferral),
            //     'payout' => $referralCommision - round($referralCommision * $percentTaxReferral),
            // ],
        ];

        return $comission;
    }

    public function comission_index()
    {
        $data['page_title'] = 'Daftar Komisi | '.date('Y-m-d_His');
        $this->_output['content'] = $this->load->view('admin/steam/comission_list', $data, true);
        $this->_output['script_js'] = $this->load->view('admin/steam/comission_js', '', true);
        $this->load->view('admin/template', $this->_output);
    }

    public function comission_list()
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
        $this->datatables->from('payout_detail_steam a');
        $this->datatables->join('order_steam b', 'b.id_order=a.id_order', 'inner');
        $this->datatables->join('customer c', 'c.id_customer=b.id_customer', 'inner');
        $this->datatables->join('employee d', 'd.id_employee=a.id_employee', 'inner');
        $this->datatables->join('mitra_profile e', 'e.id_employee=d.id_employee', 'inner');
        $this->datatables->where('a.status', 1);
        if ($this->adm_level == 4) {
            $this->datatables->where('a.id_employee', $this->adm_id);
        } elseif (in_array($this->adm_level, $this->arrAccessSteamAdmin, true)) {
            // $this->datatables->where('c.kabupaten IN (SELECT aa.kabupaten_kota FROM employee_kabupaten_kota aa WHERE aa.id_employee = '.$this->adm_id.')');
            // $this->datatables->where('b.type', 'STEAM');
        }
        $this->datatables->add_column('action', '<input type="checkbox" id="komisi_$1" class="cb_comission" value="$1" />',
            'id, reference');
        $this->datatables->edit_column('reference', '<a href="'.base_url(ADMIN_PATH.'/steam/comission_detail/$1').'">$2</a>',
            'id, reference');
        $this->output->set_output($this->datatables->generate());
    }

    public function comission_detail($id)
    {
        if ($id && is_numeric($id)) {
            $data['page_title'] = 'Detil Pengajuan Komisi';
            $data['payout'] = $this->mod_general->detailData('payout_detail_steam', 'id', $id);
            $data['order'] = $this->mod_general->detailData('order_steam', 'id_order', $data['payout']['id_order']);
            if ($data['payout'] && $data['order']) {
                $total_order = $data['order']['total_paid'];
                $comission_percentage = $data['payout']['percentage'];
                $comission_amount = round($comission_percentage * $total_order);
                $tax_percentage = $data['payout']['tax'];
                $tax_amount = round($tax_percentage * $comission_amount);
                $final_comission = $comission_amount - $tax_amount;
                $data['comission'] = [
                    'type_int' => $data['payout']['type'],
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
                $data['history_payout'] = $this->mod_general->getWhere('payout_detail_steam_history', 'id_payout',
                    $data['payout']['id'], 'id', 'asc');
                switch ($data['payout']['status']) {
                    case 1:
                        $data['url_back'] = base_url().ADMIN_PATH.'/comission/proposed';
                        break;
                    case 2:
                        $path = in_array($this->adm_level, $this->arrAccessSteamAdmin, true) ? 'approved' : 'toprocessed';
                        $data['url_back'] = base_url().ADMIN_PATH.'/comission/'.$path;
                        break;
                    case 3:
                        $data['url_back'] = base_url().ADMIN_PATH.'/comission/processed';
                        break;
                    case 4:
                        $data['url_back'] = base_url().ADMIN_PATH.'/comission/paidOff';
                        break;
                }
                $this->_output['content'] = $this->load->view('admin/steam/comission_detail', $data, true);
                $this->_output['script_css'] = $this->load->view('admin/steam/comission_css', '', true);
                $this->_output['script_js'] = $this->load->view('admin/steam/comission_js', '', true);
                $this->load->view('admin/template', $this->_output);
            } else {
                redirect(ADMIN_PATH.'/comission', 'refresh');
            }
        } else {
            redirect(ADMIN_PATH.'/comission/proposed', 'refresh');
        }
    }

    public function comission_list_proses()
    {
        $new_number = "";
        $status = "2";

        // GET NUMBER SAP
        $last_number = $this->mod_steam->get_last_number_sap();
        if($last_number)
        {
            $new_number = $last_number["sap_no"] + 1;
        }
        else
        {
            $new_number = 1;
        }

        $id_comission = $this->input->post("id_comission");

        foreach($id_comission as $id)
        {
            $data = array(
                "status" => $status,
                "sap_no" => $new_number,
                "sap_date" => date("Y-m-d H:i:s"),
                "sap_by" => $this->adm_id
            );
            $this->mod_general->edit("payout_detail_steam", $data, array("id" => $id));

            // TAMBAHKAN FUNGSI INSERT HISTORY
            $this->mod_steam->addHistory($id, $this->adm_id, $status);
        }

        $callBack = [
            'success' => true,
            'message' => 'Berhasil melakukan proses approval',
            // 'redirect' => $redirect,
            'sap_no' => $new_number
        ];
        // $datas["excel"] = $this->export_excel($id_comission);
        echo json_encode($callBack);
    }

    public function comission_popup_percentage($idOrder)
    {
        if ( ! $this->input->is_ajax_request()) {
            return false;
        }
        $detil = $this->mod_general->detailData('order_steam', 'id_order', $idOrder);
        if ($detil['sales_referer'] && $this->mod_steam->isMitra($detil['sales_referer'])) {
            $data['percentage'] = $detil['percent_comission'];
            $data['id_order'] = $idOrder;
            $data['email'] = $detil['sales_referer'];
            $this->load->view('admin/steam/comission_popup_percentage', $data);
        } else {
            return false;
        }
    }

    public function comission_popup_percentage_influencer($idOrder)
    {
        if ( ! $this->input->is_ajax_request()) {
            return false;
        }
        $detil = $this->mod_general->detailData('order_steam', 'id_order', $idOrder);
        if ($detil['influencer_email'] && $this->mod_steam->isMitra($detil['influencer_email'])) {
            $id_employee =  $this->mod_general->detailData('employee', 'email', $detil['influencer_email'])['id_employee'];
            $data['percentage'] = $this->mod_general->detailData('mitra_profile', 'id_employee', $id_employee)['percent_comission_steam'];
            $data['id_order'] = $idOrder;
            $data['email'] = $detil['influencer_email'];
            $this->load->view('admin/steam/comission_popup_percentage_influencer', $data);
        } else {
            return false;
        }
    }

    # TODO : add logs and block permission for auditor
    public function comission_update_percentage()
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
                $email = $this->input->post('email');
                $isInPayout = $this->mod_steam->isInPayout($idOrder);
                $idPayout = $isInPayout ? $this->mod_steam->getIdPayout($idOrder) : false;
                $payoutState = $this->mod_steam->getStatus($idOrder);
                $id_employee = $this->mod_general->detailData('employee', 'email', $email)['id_employee'];
                if ($payoutState && $payoutState == 4) {
                    $callBack = [
                        'success' => 'false',
                        'message' => 'Dana komisi sudah ditransfer, data tidak bisa diubah',
                    ];
                } else {
                    $redirect = $isInPayout ? 'steam/comission_detail/'.$idPayout.'#komisi-area' : 'steam/comission_order_new_detail/'.$idOrder.'#komisi-area';
                    // $redirect = 'steam/comission_detail/'.$idPayout;
                    if ($percentage <= 0 || ! is_numeric($percentage)) {
                        $callBack = [
                            'success' => 'false',
                            'message' => 'Comission percentage cannot null or invalid.',
                            'redirect' => $redirect,
                        ];
                    }  elseif ($percentage == $oldPercentage) {
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
                            $this->mod_steam->updateComissionData($idOrder, $id_employee, $dataPayout);
                        }
                        $proc = $this->mod_general->updateData('order_steam', $data, 'id_order', $idOrder);
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

    public function comission_update_percentage_influencer()
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
                $email = $this->input->post("email");
                $isInPayout = $this->mod_steam->isInPayout($idOrder);
                $idPayout = $isInPayout ? $this->mod_steam->getIdPayout($idOrder) : false;
                $payoutState = $this->mod_steam->getStatus($idOrder);
                $id_employee = $this->mod_general->detailData('employee', 'email', $email)['id_employee'];
                if ($payoutState && $payoutState == 4) {
                    $callBack = [
                        'success' => 'false',
                        'message' => 'Dana komisi sudah ditransfer, data tidak bisa diubah',
                    ];
                } else {
                    $redirect = $isInPayout ? 'steam/comission_detail/'.$idPayout.'#komisi-area' : 'steam/comission_order_new_detail/'.$idOrder.'#komisi-area';
                    // $redirect = 'steam/comission_detail/'.$idPayout;
                    if ($percentage <= 0 || ! is_numeric($percentage)) {
                        $callBack = [
                            'success' => 'false',
                            'message' => 'Comission percentage cannot null or invalid.',
                            'redirect' => $redirect,
                        ];
                    }  elseif ($percentage == $oldPercentage) {
                        $callBack = [
                            'success' => 'false',
                            'message' => 'Comission percentage still same.',
                            'redirect' => $redirect,
                        ];
                    } else {
                        $this->db->trans_begin();
                        $percentFinal = $percentage / 100;
                        $data = ['percent_comission_steam' => $percentFinal];
                        if ($isInPayout) {
                            $dataPayout = [
                                'percentage' => $percentFinal,
                                'modified_date' => date('Y-m-d H:i:s'),
                                'modified_by' => $this->adm_id,
                            ];
                            $this->mod_steam->updateComissionData($idOrder, $id_employee, $dataPayout);
                        }
                        $proc = $this->mod_general->updateData('mitra_profile', $data, 'id_employee', $id_employee);
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

    public function comission_add()
    {
        $id_order = "";
        $id_employee = "";
        $percentage = "";
        $tax = "";
        $type = "";
        $transfer_date = "";
        $status = "1"; // 1=belum dibayar, 2=sudah dibayar

        $data = array(
            'id_order' => $id_order,
            'id_employee' => $id_employee,
            'percentage' => $percentage,
            'tax' => $tax,
            'type' => $type,
            'transfer_date' => $transfer_date,
            'status' => $status
        );
    }

    public function comission_add_post()
    {

    }

    public function comission_sap_index()
    {
        $data['page_title'] = 'Daftar Komisi | '.date('Y-m-d_His');
        $this->_output['content'] = $this->load->view('admin/steam/comission_sap_list', $data, true);
        $this->_output['script_js'] = $this->load->view('admin/steam/comission_js', '', true);
        $this->load->view('admin/template', $this->_output);
    }

    public function comission_sap_list()
    {
        if ( ! $this->input->is_ajax_request()) {
            return false;
        }
        $this->load->library('datatables');
        $this->output->set_header('Content-Type:application/json; charset=utf-8');
        // $this->datatables->select('a.id AS id,
        //             a.id_order AS id_order, 
        //             b.reference AS reference, 
        //             c.school_name AS school_name, 
        //             CONCAT(b.category, "<br>(",b.type,")") AS class_name, 
        //             c.provinsi AS provinsi, 
        //             c.kabupaten AS kabupaten,
        //             CONCAT(d.name, "<br> (", d.email, ") <br>", d.telp) AS sales_person,
        //             FORMAT((a.percentage * 100), 2) AS percent_comission, 
        //             FORMAT((a.tax * 100), 2) AS percent_tax, 
        //             (ROUND(a.percentage * b.total_paid) - ROUND(a.tax * (a.percentage * b.total_paid))) AS amount_comission, 
        //             b.date_add AS date_add,
        //             b.total_paid,
        //             a.created_date AS date_proposed');

        $this->datatables->select('
            a.id as id,
            a.sap_no as sap_no,
            a.`sap_date` AS sap_date,
            a.id_order AS id_order, 
            b.date_add AS `date_add`,
            b.reference AS reference, 
            c.school_name AS school_name, 
            CONCAT(d.name, "<br> (", d.email, ") <br>", d.telp) AS sales_person,
            d.name AS sales_name,
            d.email AS sales_email,
            d.telp AS sales_phone,
            SUM(b.total_paid) AS total_paid,
            FORMAT((a.percentage * 100), 2) AS percent_comission, 
            SUM(ROUND(a.percentage * b.total_paid)) AS amount_comission,
            FORMAT((a.tax * 100), 2) AS percent_tax, 
            SUM(ROUND(a.tax * (a.percentage * b.total_paid))) AS amount_tax,
            SUM((ROUND(a.percentage * b.total_paid) - ROUND(a.tax * (a.percentage * b.total_paid)))) AS total_comission, 
            a.created_date AS date_proposed,
            CONCAT(b.category, "<br>(",b.type,")") AS class_name, 
            c.provinsi AS provinsi, 
            c.kabupaten AS kabupaten,
            GROUP_CONCAT(b.`reference`) AS notes
        ');
        $this->datatables->from('payout_detail_steam a');
        $this->datatables->join('order_steam b', 'b.id_order=a.id_order', 'inner');
        $this->datatables->join('customer c', 'c.id_customer=b.id_customer', 'inner');
        $this->datatables->join('employee d', 'd.id_employee=a.id_employee', 'inner');
        $this->datatables->join('mitra_profile e', 'e.id_employee=d.id_employee', 'inner');
        $this->datatables->where('a.status', 2);
        // $this->datatables->where('a.status', 1);
        if ($this->adm_level == 4) {
            $this->datatables->where('a.id_employee', $this->adm_id);
        } elseif (in_array($this->adm_level, $this->arrAccessSteamAdmin, true)) {
            // $this->datatables->where('c.kabupaten IN (SELECT aa.kabupaten_kota FROM employee_kabupaten_kota aa WHERE aa.id_employee = '.$this->adm_id.')');
            // $this->datatables->where('b.type', 'STEAM');
        }
        $this->datatables->group_by("a.sap_no");
        // $this->datatables->add_column('action', '<input type="checkbox" id="komisi_$1" class="cb_comission" value="$1" />','id, reference');
        $this->datatables->edit_column('sap_no', '<a href="'.base_url(ADMIN_PATH.'/steam/comission_sap_detail/$2').'">$2</a>',
            'id, sap_no');
        $this->output->set_output($this->datatables->generate());
    }

    public function comission_sap_detail($sap_no)
    {
        // $data = array();
        $data['payout_comission'] = $this->mod_general->getAll("payout_detail_steam", '', 'sap_no='.$sap_no);
        $payoutComission = $this->mod_steam->get_comission_sap($sap_no);
        // print_r($payoutComission);

        // echo "<br><br>";
        $num = 0;
        foreach ($payoutComission as $row) {
            foreach ($row as $field => $value) {
                $data['detail'][$num][$field] = $value;
            }
            $payoutComissionDetail = $this->mod_steam->get_comission_sap_detail($sap_no, $row['id_employee']);
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
        
        $data["url_back"] = base_url(ADMIN_PATH."/steam/comission_sap_index");
        $this->_output['content'] = $this->load->view('admin/steam/comission_sap_detail', $data, true);
        $this->_output['script_css'] = $this->load->view('admin/steam/comission_sap_css', '', true);
        $this->_output['script_js'] = $this->load->view('admin/steam/comission_sap_js', '', true);
        $this->load->view('admin/template', $this->_output);
    }

    public function comission_sap_process_index()
    {
        $data['page_title'] = 'Daftar Komisi | '.date('Y-m-d_His');
        $this->_output['content'] = $this->load->view('admin/steam/comission_sap_process_list', $data, true);
        $this->_output['script_js'] = $this->load->view('admin/steam/comission_js', '', true);
        $this->load->view('admin/template', $this->_output);
    }

    public function comission_sap_process_list()
    {
        if ( ! $this->input->is_ajax_request()) {
            return false;
        }
        $this->load->library('datatables');
        $this->output->set_header('Content-Type:application/json; charset=utf-8');
        // $this->datatables->select('a.id AS id,
        //             a.id_order AS id_order, 
        //             b.reference AS reference, 
        //             c.school_name AS school_name, 
        //             CONCAT(b.category, "<br>(",b.type,")") AS class_name, 
        //             c.provinsi AS provinsi, 
        //             c.kabupaten AS kabupaten,
        //             CONCAT(d.name, "<br> (", d.email, ") <br>", d.telp) AS sales_person,
        //             FORMAT((a.percentage * 100), 2) AS percent_comission, 
        //             FORMAT((a.tax * 100), 2) AS percent_tax, 
        //             (ROUND(a.percentage * b.total_paid) - ROUND(a.tax * (a.percentage * b.total_paid))) AS amount_comission, 
        //             b.date_add AS date_add,
        //             b.total_paid,
        //             a.created_date AS date_proposed');

        $this->datatables->select('
            a.id as id,
            a.sap_no as sap_no,
            a.`sap_date` AS sap_date,
            a.id_order AS id_order, 
            b.date_add AS `date_add`,
            b.reference AS reference, 
            c.school_name AS school_name, 
            CONCAT(d.name, "<br> (", d.email, ") <br>", d.telp) AS sales_person,
            d.name AS sales_name,
            d.email AS sales_email,
            d.telp AS sales_phone,
            SUM(b.total_paid) AS total_paid,
            FORMAT((a.percentage * 100), 2) AS percent_comission, 
            SUM(ROUND(a.percentage * b.total_paid)) AS amount_comission,
            FORMAT((a.tax * 100), 2) AS percent_tax, 
            SUM(ROUND(a.tax * (a.percentage * b.total_paid))) AS amount_tax,
            SUM((ROUND(a.percentage * b.total_paid) - ROUND(a.tax * (a.percentage * b.total_paid)))) AS total_comission, 
            a.created_date AS date_proposed,
            CONCAT(b.category, "<br>(",b.type,")") AS class_name, 
            c.provinsi AS provinsi, 
            c.kabupaten AS kabupaten,
            GROUP_CONCAT(b.`reference`) AS notes
        ');
        $this->datatables->from('payout_detail_steam a');
        $this->datatables->join('order_steam b', 'b.id_order=a.id_order', 'inner');
        $this->datatables->join('customer c', 'c.id_customer=b.id_customer', 'inner');
        $this->datatables->join('employee d', 'd.id_employee=a.id_employee', 'inner');
        $this->datatables->join('mitra_profile e', 'e.id_employee=d.id_employee', 'inner');
        $this->datatables->where('a.status', 4);
        // $this->datatables->where('a.status', 1);
        if ($this->adm_level == 4) {
            $this->datatables->where('a.id_employee', $this->adm_id);
        } elseif (in_array($this->adm_level, $this->arrAccessSteamAdmin, true)) {
            // $this->datatables->where('c.kabupaten IN (SELECT aa.kabupaten_kota FROM employee_kabupaten_kota aa WHERE aa.id_employee = '.$this->adm_id.')');
            // $this->datatables->where('b.type', 'STEAM');
        }
        $this->datatables->group_by("a.sap_no");
        // $this->datatables->add_column('action', '<input type="checkbox" id="komisi_$1" class="cb_comission" value="$1" />','id, reference');
        $this->datatables->edit_column('sap_no', '<a href="'.base_url(ADMIN_PATH.'/steam/comission_sap_process_detail/$2').'">$2</a>',
            'id, sap_no');
        $this->output->set_output($this->datatables->generate());
    }

    public function comission_sap_process_detail($sap_no)
    {
        // $data = array();
        $data['payout_comission'] = $this->mod_general->getAll("payout_detail_steam", '', 'sap_no='.$sap_no);
        $payoutComission = $this->mod_steam->get_comission_sap($sap_no);
        // print_r($payoutComission);

        // echo "<br><br>";
        $num = 0;
        foreach ($payoutComission as $row) {
            foreach ($row as $field => $value) {
                $data['detail'][$num][$field] = $value;
            }
            $payoutComissionDetail = $this->mod_steam->get_comission_sap_detail($sap_no, $row['id_employee']);
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
        
        $data["url_back"] = base_url(ADMIN_PATH."/steam/comission_sap_process_index");
        $this->_output['content'] = $this->load->view('admin/steam/comission_sap_process_detail', $data, true);
        $this->_output['script_css'] = $this->load->view('admin/steam/comission_sap_css', '', true);
        $this->_output['script_js'] = $this->load->view('admin/steam/comission_sap_js', '', true);
        $this->load->view('admin/template', $this->_output);
    }

    public function cetak_komisi($sap_no)
    {
        if ($sap_no) {
            $data['payout_comission'] = $this->mod_general->getAll("payout_detail_steam", '', 'sap_no='.$sap_no);
            $payoutComission = $this->mod_steam->get_comission_sap($sap_no);

            $num = 0;
            foreach ($payoutComission as $row) {
                foreach ($row as $field => $value) {
                    $data['detail'][$num][$field] = $value;
                }
                $payoutComissionDetail = $this->mod_steam->get_comission_sap_detail($sap_no, $row['id_employee']);
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

            $this->load->view('admin/steam/cetak_komisi', $data);
        } else {
            redirect(ADMIN_PATH . '/steam');
        }   
    }

    /**
     * FEATURE LAPORAN KOMISI
     */
    
    public function export_excel($id_orders)
    {
        // $this->isAdmin();
        try {
            // ini_set('memory_limit', '1024');
            // set_time_limit(0);
                $tm = date("Y-m-d");
                $ta = date("H-i-s");
                $filename = 'laporan_komisi_' . $tm . '_' . $ta . '.xlsx';
                $folder = 'uploads/steam/laporan/';
                $startDate = $tm . ' 00:00:00';
                $finishDate = $ta . ' 23:59:59';
                $rListOrder = $this->mod_steam->report_comission($id_orders);
                $header = [
                    'Tanggal' => 'string',
                    'Nama Sales' => 'string',
                    'Email Sales' => 'string',
                    'Telpon Sales' => 'string',
                    'Total Omset' => 'price',
                    'Komisi (%)' => 'price',
                    'Komisi (Rp.)' => 'price',
                    'PPh (%)' => 'price',
                    'PPh (Rp.)' => 'price',
                    'Total Komisi' => 'price',
                    "Keterangan" => "string"
                ];
                $writer = new XLSXWriter();
                $writer->writeSheetHeader('Sheet1', $header);
                foreach ($rListOrder as $row) {
                    $value = [
                        $row['date_add'],
                        $row['sales_name'],
                        $row['sales_email'],
                        $row['sales_phone'],
                        $row['total_paid'],
                        $row['percent_comission'],
                        $row['amount_comission'],
                        $row['percent_tax'],
                        $row['amount_tax'],
                        $row['total_comission'],
                        $row['notes']
                    ];
                    $writer->writeSheetRow('Sheet1', $value);
                }
                $writer->writeToFile(FCPATH . $folder . $filename);
                chmod($folder . $filename, 0777);

                // force_download($folder . $filename, null);
                $link = $folder . $filename;
                $callBack = [
                    'success' => 'true',
                    'message' => 'Berhasil membuat data komisi',
                    'link' => $link
                ];
        } catch (Exception $e) {
            $callBack = [
                'success' => 'false',
                'message' => 'Caught exception: ' . $e->getMessage(),
                'link' => null
            ];
            // echo json_encode($callBack, true);
        }

        return $callBack;
    }

    public function export_excel_sap($sap_no)
    {
        // $this->isAdmin();
        try {
            // ini_set('memory_limit', '1024');
            // set_time_limit(0);
                $tm = date("Y-m-d");
                $ta = date("H-i-s");
                $filename = 'laporan_komisi_' . $tm . '_' . $ta . '.xlsx';
                $folder = 'uploads/steam/laporan_temp/';
                $startDate = $tm . ' 00:00:00';
                $finishDate = $ta . ' 23:59:59';
                $rListOrder = $this->mod_steam->report_comission_sap($sap_no);
                $header = [
                    'Tanggal' => 'string',
                    'Nama Sales' => 'string',
                    'Email Sales' => 'string',
                    'Telpon Sales' => 'string',
                    'Total Omset' => 'price',
                    'Komisi (%)' => 'price',
                    'Komisi (Rp.)' => 'price',
                    'PPh (%)' => 'price',
                    'PPh (Rp.)' => 'price',
                    'Total Komisi' => 'price',
                    "Keterangan" => "string"
                ];
                $writer = new XLSXWriter();
                $writer->writeSheetHeader('Sheet1', $header);
                foreach ($rListOrder as $row) {
                    $value = [
                        $row['date_add'],
                        $row['sales_name'],
                        $row['sales_email'],
                        $row['sales_phone'],
                        $row['total_paid'],
                        $row['percent_comission'],
                        $row['amount_comission'],
                        $row['percent_tax'],
                        $row['amount_tax'],
                        $row['total_comission'],
                        $row['notes']
                    ];
                    $writer->writeSheetRow('Sheet1', $value);
                }
                $writer->writeToFile(FCPATH . $folder . $filename);
                chmod($folder . $filename, 0777);

                force_download($folder . $filename, null);
                // $link = $folder . $filename;
                
                $callBack = [
                    'success' => 'true',
                    'message' => 'Berhasil membuat data komisi',
                    // 'link' => $link
                ];
        } catch (Exception $e) {
            $callBack = [
                'success' => 'false',
                'message' => 'Caught exception: ' . $e->getMessage(),
                // 'link' => null
            ];
            // echo json_encode($callBack, true);
        }

        return $callBack;
    }
    
    public function proses_sap_post($sap_no)
    {
        $this->db->trans_begin();
        // $this->isAdmin();
        $status = 4;
        $id_comission = $this->mod_general->getAll("payout_detail_steam", "id", array("sap_no" => $sap_no));

        foreach($id_comission as $id)
        {
            $data = array(
                "status" => $status
            );
            $this->mod_general->edit("payout_detail_steam", $data, array("id" => $id->id));

            // TAMBAHKAN FUNGSI INSERT HISTORY
            $this->mod_steam->addHistory($id->id, $this->adm_id, $status);
        }

        try {
            // ini_set('memory_limit', '1024');
            // set_time_limit(0);
                $tm = date("Y-m-d");
                $ta = date("H-i-s");
                $filename = 'laporan_komisi_' . $tm . '_' . $ta . '.xlsx';
                $folder = 'uploads/steam/laporan_temp/';
                $startDate = $tm . ' 00:00:00';
                $finishDate = $ta . ' 23:59:59';
                $rListOrder = $this->mod_steam->report_comission_sap($sap_no);
                $header = [
                    'Tanggal' => 'string',
                    'Nama Sales' => 'string',
                    'Email Sales' => 'string',
                    'Telpon Sales' => 'string',
                    'Total Omset' => 'price',
                    'Komisi (%)' => 'price',
                    'Komisi (Rp.)' => 'price',
                    'PPh (%)' => 'price',
                    'PPh (Rp.)' => 'price',
                    'Total Komisi' => 'price',
                    "Keterangan" => "string"
                ];
                $writer = new XLSXWriter();
                $writer->writeSheetHeader('Sheet1', $header);
                foreach ($rListOrder as $row) {
                    $value = [
                        $row['date_add'],
                        $row['sales_name'],
                        $row['sales_email'],
                        $row['sales_phone'],
                        $row['total_paid'],
                        $row['percent_comission'],
                        $row['amount_comission'],
                        $row['percent_tax'],
                        $row['amount_tax'],
                        $row['total_comission'],
                        $row['notes']
                    ];
                    $writer->writeSheetRow('Sheet1', $value);
                }
                $writer->writeToFile(FCPATH . $folder . $filename);
                chmod($folder . $filename, 0777);

                $link = $folder . $filename;
                
                // $callBack = [
                //     'success' => 'true',
                //     'message' => 'Berhasil membuat data komisi',
                //     // 'link' => $link
                // ];
                
                if ($this->db->trans_status() == FALSE)
                {
                    $this->db->trans_rollback();
                    $this->session->set_flashdata('msg_error_commision', 'Gagal melakukan proses SAP');
                    redirect("backoffice/steam/comission_sap_detail/".$sap_no, "refresh");
                }
                else
                {
            // if(count($order_temp) > 0)
            // {
            //     $message = "Berikut beberapa data yang sudah terinput sebelumnya : ".implode(",", $order_temp);
            // }
            // else
            // {
            //     $message = "";
            // }
                    $this->db->trans_commit();
                    $this->session->set_flashdata('msg_success_commision', 'Berhasil melakukan proses Sap.');

                    // force_download($folder . $filename, null);

                    // redirect("backoffice/steam/comission_order_new", "refresh");
                    // $link = $folder . $filename;

                    $callBack = [
                        'success' => true,
                        'message' => 'Berhasil membuat data komisi',
                        'link' => $link,
                        'redirect' => 'backoffice/steam/comission_sap_process_index'
                    ];
                }

        } catch (Exception $e) {
            $callBack = [
                'success' => false,
                'message' => 'Caught exception: ' . $e->getMessage(),
                'link' => null
            ];
            // echo json_encode($callBack, true);
        }



        echo json_encode($callBack, true);
        // return $callBack;
    }

    public function tesstt()
    {

        $folder = 'uploads'.DIRECTORY_SEPARATOR.'steam'.DIRECTORY_SEPARATOR.'laporan_temp'.DIRECTORY_SEPARATOR;
        $files    =glob(FCPATH.$folder.'*.xlsx');
        foreach ($files as $file) {
            if (is_file($file))
            {
                unlink($file); 
            }
        }
    }

    /**
     * END FEATURE LAPORAN KOMISI
     */
    
    /**
     * UPLOAD SO
     */
    public function import_sap()
    {
        $this->_output['content'] = $this->load->view('admin/steam/import_sap', '', true);
        $this->_output['script_js'] = ''; //$this->load->view('admin/product/js', '', true);
        $this->load->view('admin/template', $this->_output);
    }
    
    public function import_sap_post()
    {
        $this->db->trans_begin();
        $file = $_FILES['mikon_file']['tmp_name'];
 
        //load the excel library
        $this->load->library('excel');
         
        //read file from path
        $objPHPExcel = PHPExcel_IOFactory::load($file);

        $sheetActive = $objPHPExcel->getSheet(0);
        // $sheetActive = $objPHPExcel->getActiveSheet();
         
        //get only the Cell Collection
        // $cell_collection = $objPHPExcel->getActiveSheet()->getCellCollection();
        $cell_collection = $sheetActive->getCellCollection();
         
        //extract to a PHP readable array format
        foreach ($cell_collection as $cell) {
            $column = $sheetActive->getCell($cell)->getColumn();
            $row = $sheetActive->getCell($cell)->getRow();
            $data_value = $sheetActive->getCell($cell)->getValue();
         
            //The header will/should be in row 1 only. of course, this can be modified to suit your need.
            if ($row == 1) {
                $header[$row][$column] = $data_value;
            } else {
                $arr_data[$row][$column] = $data_value;
            }
        }

        //send the data in an array format
        $data['header'] = $header;
        $data['values'] = $arr_data;

        // print_r($data['header']);
        // echo "<br><br>";
        // print_r($data['values']);
        // echo "<br><br>";

        $sales = array();
        $customer = array();
        $order = array();

        $check_sales = array();
        $check_customer = array();
        $check_order = array();
        $order_temp = array();

        $id_customer = "";
        foreach($data['values'] as $row)
        {
            $check_order = $this->mod_general->detailData('order_steam', 'reference', $row["A"]);
            if($check_order)
            {
                $order_temp[] = $check_order["reference"];
            }
            else
            {
                $check_sales = $this->mod_general->detailData('employee', 'email', $row["J"]);
                if($check_sales)
                {
                    $id_sales = $check_sales["id_employee"];
                }
                else
                {
                // jika tidak ada data sales
                // input data sales
                    $sales = array(
                        'code' => $row["G"],
                        'name' => $row["H"],
                        'email' => $row["J"],
                        'telp' => $row["K"],
                        'level' => 4
                    );

                    $id_sales = $this->mod_general->addData('employee', $sales);

                    $sales_profile = array(
                        'id_employee' => $id_sales,
                        'code_mitra' => $row["G"],
                        'address' => $row["I"],
                        'is_activated' => 1
                    );

                    $this->mod_general->addData('mitra_profile', $sales_profile);
                }

                $check_customer = $this->mod_general->detailData('customer', 'email', $row["E"]);
                if($check_customer)
                {
                    $id_customer = $check_customer['id_customer'];
                }
                else
                {
                    $customer = array(
                        "no_npsn" => $row["B"],
                        "school_name" => $row["C"],
                        "alamat" => $row["D"],
                        "phone" => $row["F"],
                        "email" => $row["E"]
                    );

                    $id_customer = $this->mod_general->addData('customer', $customer);
                }

            // GET PERCENT COMISSION 
            // $percent_comission = $sales_profile['percent_comission_steam'];
                $percent_comission = "0.27";

                $tanggal = date('Y-m-d H:i:s', PHPExcel_Shared_Date::ExcelToPHP($row["M"]));
                $order = array(
                    'reference' => $row["A"],
                    'id_customer' => $id_customer,
                    'category' => "Produk STEAM",
                    'type' => "STEAM",
                    'current_state' => 9,
                    'total_paid' => $row["L"],
                    'periode' => getenv("PERIODE"),
                    'date_add' => $tanggal,
                    'jangka_waktu' => 14,
                    'kesepakatan_sampai' => 14,
                    'tgl_konfirmasi' => $tanggal,
                    'sales_referer' => $row["J"],
                    'sales_name' => $row["H"],
                    'sales_phone' => $row["K"],
                    'percent_comission' => $percent_comission,
                // 'tanggal_kirim' => $tanggal_kirim,
                    'sts_bayar' => 2,
                    'tgl_lunas' => $tanggal
                );
                $this->mod_general->addData('order_steam', $order);
            }

            
        }

        if ($this->db->trans_status() == FALSE)
        {
            $this->db->trans_rollback();
            $this->session->set_flashdata('msg_error_commision', 'Terjadi kesalahan ketika upload data SAP');
            redirect("backoffice/steam/comission_order_new", "refresh");
        }
        else
        {
            if(count($order_temp) > 0)
            {
                $message = "Berikut beberapa data yang sudah terinput sebelumnya : ".implode(",", $order_temp);
            }
            else
            {
                $message = "";
            }
            $this->db->trans_commit();
            $this->session->set_flashdata('msg_success_commision', 'Berhasil upload data SAP. '.$message);
            redirect("backoffice/steam/comission_order_new", "refresh");
        }
    }

    /**
     * END UPLOAD SO
     */

    /**
     * END COMISSION FEATURE
     */

    public function listProduct()
    {
        if ( ! $this->input->is_ajax_request()) {
            redirect(ADMIN_PATH . '/product');
        }
        $this->load->library('datatables');
        $this->output->set_header('Content-Type:application/json; charset=utf-8');
        $this->datatables->select('
            a.`id_product` as id_product
            ,a.`kode_buku` as kode_buku
            ,a.`reference` as reference
            ,b.`name` as category
            ,a.`name` as name
            ,a.`description` as description
            ,a.`supplier` as supplier
            ,a.`quantity` as quantity
            ,a.`price_1` as price_1
            ,a.`price_2` as price_2
            ,a.`price_3` as price_3
            ,a.`price_4` as price_4
            ,a.`price_5` as price_5
            ,a.`non_r1` as non_r1
            ,a.`non_r2` as non_r2
            ,a.`non_r3` as non_r3
            ,a.`non_r4` as non_r4
            ,a.`non_r5` as non_r5
            ,a.`width` as width
            ,a.`height` as height
            ,a.`weight` as weight
            ,a.`pages` as pages
            ,a.`capacity` as capacity
            ,a.`url_image` as image
            ,if(a.`active`=0, CONCAT("<span class=\"label label-danger\">Nonaktif</span>"), CONCAT("<span class=\"label label-success\">Aktif</span>")) AS active
            ,if(a.`enable`=0, CONCAT("<span class=\"label label-danger\">Tidak</span>"), CONCAT("<span class=\"label label-success\">Ya</span>")) AS enable
            ,a.`date_add` as date_add
            ,a.`date_upd` as date_upd
            ,CASE a.`active` 
            WHEN 0 THEN CONCAT("<span class=\"label label-danger\">Nonaktif</span>") 
            WHEN 1 THEN CONCAT("<span class=\"label label-success\">Aktif</span>") END AS `active`
            ,CASE a.`enable` 
            WHEN 0 THEN CONCAT("<span class=\"label label-danger\">Tidak</span>") 
            WHEN 1 THEN CONCAT("<span class=\"label label-success\">Ya</span>") END AS `enable`
            ,a.`date_add`,a.`date_upd`,CASE a.images WHEN 0 THEN CONCAT("<span class=\"label label-danger\">Tidak</span>") WHEN 1 THEN CONCAT("<span class=\"label label-success\">Ya</span>") END AS image

        ');

        $this->datatables->from($this->table.' a');
        $this->datatables->join('category b', 'b.`id_category`=a.`id_category_default`', 'inner');
        $this->datatables->edit_column('kode_buku', '<a href="' . base_url(ADMIN_PATH . '/product/detail/$2') . '">$1</a>', 'kode_buku, id_product');
        // $this->datatables->edit_column('image', '<a href="' . base_url('assets/img/product/$1.jpg') . '">$1</a>'.'<img src="' . base_url('assets/img/product/$1.jpg'), 'id_product');
        $this->datatables->add_column('image','<img id="imgView" src="'.base_url('assets/img/product/').'$1.jpg" width="25px">', 'id_product');
        $this->output->set_output($this->datatables->generate());
    }

    public function detail($id_product)
    {
        $data['kategori'] = $this->mod_product->getAll("category",'id_category, name',"active=1 and id_parent <> 0");
        $data['detil'] = $this->mod_product->getList("product",'*',"id_product='$id_product'");
        $this->_output['content'] = $this->load->view('admin/product/edit', $data, TRUE);
        $this->_output['script_js'] = $this->load->view('admin/product/js', '', true);
        $this->load->view('admin/template', $this->_output);
    }

    public function detail_post()
    {
        $id_product = $this->input->post('id_product');
        $kode_buku = $this->input->post('kode_buku');
        $reference = $this->input->post('reference');
        $id_category_default = $this->input->post('id_category_default');
        $name = $this->input->post('name');
        $description = $this->input->post('description');
        $supplier = $this->input->post('supplier');
        $quantity = $this->input->post('quantity');
        $price_1 = $this->input->post('price_1');
        $price_1 = $this->input->post('price_1');
        $price_2 = $this->input->post('price_2');
        $price_3 = $this->input->post('price_3');
        $price_4 = $this->input->post('price_4');
        $price_5 = $this->input->post('price_5');
        $non_r1 = $this->input->post('non_r1');
        $non_r2 = $this->input->post('non_r2');
        $non_r3 = $this->input->post('non_r3');
        $non_r4 = $this->input->post('non_r4');
        $non_r5 = $this->input->post('non_r5');
        $width = $this->input->post('width');
        $height = $this->input->post('height');
        $weight = $this->input->post('weight');
        $pages = $this->input->post('pages');
        $capacity = $this->input->post('capacity');
        $active = $this->input->post('active');
        $enable = $this->input->post('enable');

        $data = array(
            'kode_buku' => $kode_buku
            ,'reference' => $reference
            ,'id_category_default' => $id_category_default
            ,'name' => $name
            ,'description' => $description
            ,'supplier' => $supplier
            ,'quantity' => $quantity
            ,'price_1' => $price_1
            ,'price_2' => $price_2
            ,'price_3' => $price_3
            ,'price_4' => $price_4
            ,'price_5' => $price_5
            ,'non_r1' => $non_r1
            ,'non_r2' => $non_r2
            ,'non_r3' => $non_r3
            ,'non_r4' => $non_r4
            ,'non_r5' => $non_r5
            ,'width' => $width
            ,'height' => $height
            ,'weight' => $weight
            ,'pages' => $pages
            ,'capacity' => $capacity
            ,'active' => $active
            ,'enable' => $enable
        );

        $config['upload_path'] = 'assets/img/product/';
        $config['allowed_types'] = 'jpg';
        $config['file_name'] = $id_product;
        $config['overwrite'] = TRUE;
        $config['max-size'] = '2048';

        $this->load->library('upload', $config);
        // $this->upload->overwrite = TRUE;
        if($this->upload->do_upload('gambar'))
        {
            // echo 'berhasil unggah gambar';
            $data1 = array(
                'images' => 1
            );
            $data = array_merge($data,$data1);
        }
        else
        {
            // print_r($this->upload->display_errors());
        }

        // print_r($data);
        try {
            if (in_array($this->adm_level, $this->backoffice_admin_area) && in_array($this->adm_level, $this->auditor_area)) {
                $callBack   = [   
                    "success"   => "false",
                    "message"   => "Maaf, anda tidak dapat melakukan proses ini."
                ];
            } else {
                $this->db->trans_begin();
                $updateProduct = $this->mod_general->updateData($this->table, $data, 'id_product', $id_product);
                if ($updateProduct) {
                    $this->db->trans_commit();
                    $callBack = [
                        'success' => 'true',
                        'message' => 'Data successfully updated.'
                    ];
                    $this->session->set_flashdata('msg_success', 'Data product: <b>' . $name . '</b> berhasil <b>DIPERBARUI</b></p>');
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

    function importProduct()
    {
        // $this->load->view('admin/product/upload2');
        $this->_output['content'] = $this->load->view('admin/product/upload2', '', true);
        $this->_output['script_js'] = ''; //$this->load->view('admin/product/js', '', true);
        $this->load->view('admin/template', $this->_output);
    }

    function importProductPost()
    {
        $product = $this->importPost();
        if($product == true)
        {
            $this->uploadFiles2();
        }
    }

    function uploadFiles2()
    {
        $count = 0;
        if ($_SERVER['REQUEST_METHOD'] == 'POST'){
            foreach ($_FILES['files']['name'] as $i => $name) {
                if (strlen($_FILES['files']['name'][$i]) > 1) {
                    if (move_uploaded_file($_FILES['files']['tmp_name'][$i], $_SERVER['DOCUMENT_ROOT'].'/coba/'.$name)) {
                        $count++;
                    }
                    else
                    {
                        echo "gagal upload";
                    }
                }
            }
        }


        $file = $_FILES['mikon_file']['tmp_name'];
 
        //load the excel library
        $this->load->library('excel');
         
        //read file from path
        $objPHPExcel = PHPExcel_IOFactory::load($file);

        $sheetActive = $objPHPExcel->getSheet(0);
        // $sheetActive = $objPHPExcel->getActiveSheet();
         
        //get only the Cell Collection
        // $cell_collection = $objPHPExcel->getActiveSheet()->getCellCollection();
        $cell_collection = $sheetActive->getCellCollection();
         
        //extract to a PHP readable array format
        foreach ($cell_collection as $cell) {
            $column = $sheetActive->getCell($cell)->getColumn();
            $row = $sheetActive->getCell($cell)->getRow();
            $data_value = $sheetActive->getCell($cell)->getValue();
         
            //The header will/should be in row 1 only. of course, this can be modified to suit your need.
            if ($row == 1) {
                $header[$row][$column] = $data_value;
            } else {
                $arr_data[$row][$column] = $data_value;
            }
        }

        //send the data in an array format
        $data['header'] = $header;
        $data['values'] = $arr_data;

        $dataArray = [];

        foreach($arr_data as $vv){
            $idproduct = $this->mod_product->getIdProduct('product','id_product',$vv['B'])[0]['id_product'];
            $dataArray[] = array(
                'id_product' => $idproduct,
                'images' =>  $vv['U']
            );
        }

        // $this->imageDataUpload2($dataArray);
        $this->imageDataGet2($dataArray);
    }

    public function imageDataGet2($datas)
    {
        //===================
        // config php.ini
        //===================
        error_reporting(E_ALL ^ (E_NOTICE | E_WARNING));
        set_time_limit(21600000000);
        ini_set('memory_limit', '-1');

        //===================
        // Set variable path
        //===================
        // $path = "coba2/"; 
        $path = $_SERVER['DOCUMENT_ROOT']."/assets/img/product/";       

        //===================
        // set variable url
        //===================
        // $url = "http://bukusekolah.gramedia.test/coba/";
        $url = $_SERVER['DOCUMENT_ROOT']."/coba/";

        if(is_dir($path) && is_writable($path))
        {
            if(is_dir($path) && is_writable($path))
            {
                //===================
                // example array
                //===================
                // $datas = Array(Array ( 'id_product' => 1001, 'images' => 'gambar1.jpg' ), Array ( 'id_product' => 1002, 'images' => 'gambar2.jpg'));
                 
                // echo "<br>";
                // print_r($datas);

                foreach($datas as $data){
                    $images = $data['images'];
                    $imagesUrl = $url.$images;
                    $id = $data['id_product'];
                    // $name = strtolower(str_replace(" ", "-", str_replace(":","",$data['name']))).".jpg";
                    // $fileName = $id."-".$name;
                    echo $imagesUrl."  ##  ";
                    echo $id.".jpg"."<br>";

                    //===========================
                    // destination file location
                    //===========================
                    $destFileUrl = $path.$id.".jpg";

                    try {
                        file_put_contents( $destFileUrl, file_get_contents($imagesUrl));
                    } catch (Exception $e) {
                        echo $e;
                    }
                    
                    sleep(1);
                }
                // echo "<br/>";

                // $this->recursiveRemoveDirectory("coba/");
            }
            else
            {
                echo "Upload directory is not writable or does not exist.";
            }
        }
        else
        {
            echo "Upload directory is not writable or does not exist.";
        }

    }

    function recursiveRemoveDirectory($directory)
    {
        foreach(glob("{$directory}/*") as $file)
        {
            if(is_dir($file)) { 
                recursiveRemoveDirectory($file);
            } else {
                // unlink($file); 

                $type = explode(".", $file);
                $count = count($type) - 1;
                // echo $type[$count];
                if($type[$count] == 'jpg')
                {
                    unlink($file);   
                }
            }
        }
        echo "<br/> Berhasil upload image produk";
        echo "<br/> <a href='".base_url('backoffice/product')."'>Kembali ke daftar produk</a>";
        // rmdir($directory);
    }
    
    public function import()
    {
        // $this->load->view('admin/product/import');
        $this->_output['content'] = $this->load->view('admin/product/import', '', true);
        $this->_output['script_js'] = ''; //$this->load->view('admin/product/js', '', true);
        $this->load->view('admin/template', $this->_output);
    } 

    public function importPost()
    {
        $this->db->trans_begin();

        $data_periode = $this->mod_product->getIdPeriodeTerakhir();
        $id_periode = $data_periode['id'];
        $periode = $data_periode['year_start'];
        // $id_periode = '7';
        // $periode = '2019';

        $file = $_FILES['mikon_file']['tmp_name'];
 
        //load the excel library
        $this->load->library('excel');
         
        //read file from path
        $objPHPExcel = PHPExcel_IOFactory::load($file);

        $sheetActive = $objPHPExcel->getSheet(0);
        // $sheetActive = $objPHPExcel->getActiveSheet();
         
        //get only the Cell Collection
        // $cell_collection = $objPHPExcel->getActiveSheet()->getCellCollection();
        $cell_collection = $sheetActive->getCellCollection();
         
        //extract to a PHP readable array format
        foreach ($cell_collection as $cell) {
            $column = $sheetActive->getCell($cell)->getColumn();
            $row = $sheetActive->getCell($cell)->getRow();
            $data_value = $sheetActive->getCell($cell)->getValue();
         
            //The header will/should be in row 1 only. of course, this can be modified to suit your need.
            if ($row == 1) {
                $header[$row][$column] = $data_value;
            } else {
                $arr_data[$row][$column] = $data_value;
            }
        }

        //send the data in an array format
        $data['header'] = $header;
        $data['values'] = $arr_data;

        $productArray = [];
        $dataArray = [];

        $category_product = [];
        $category_product_1 = [];
        $category_product_2 = [];

        foreach($arr_data as $vv){
            $productArray[] = array(
            'id_product'=> 0,
            'kode_buku' => $vv['B'],
            'reference' => $vv['C'],
            'id_category_default' => $vv['D'],
            'name' => $vv['E'],
            'description' => $vv['F'],
            'supplier' => $vv['G'],
            'quantity' => 0,
            'price_1' => $vv['H'],
            'price_2' => $vv['I'],
            'price_3' => $vv['J'],
            'price_4' => $vv['K'],
            'price_5' => $vv['L'],
            'non_r1' => $vv['M'],
            'non_r2' => $vv['N'],
            'non_r3' => $vv['O'],
            'non_r4' => $vv['P'],
            'non_r5' => $vv['Q'],
            'width' => str_replace(',', '.', $vv['S']),
            'height' => str_replace(',', '.', $vv['T']),
            'weight' => str_replace(',', '.', $vv['V']),
            'pages' => $vv['R'],
            'capacity' => null,
            'active' => 1,
            'enable' => 1,
            'sort_order' => $vv['X'],
            'images' => 1,
            'url_image' => ''
            );

            $dataArray[] = array(
                'kode_buku' => $vv['B'],
                'hpp' =>  $vv['W']
            );
        }
        
        $queryProduct = $this->mod_product->productAdd('product', $productArray);

        if($queryProduct)
        {
            echo "Berhasil menyimpan data produk <br>";
            foreach($arr_data as $dd)
            {
                $idproduct = $this->mod_product->getIdProduct('product','id_product',$dd['B'])[0]['id_product'];
                $category_product_1[] = array(
                    'id_product' => $idproduct,
                    'id_category' =>  $dd['D']
                );

                $category_product_2[] = array(
                    'id_product' => $idproduct,
                    'id_category' =>  $dd['Y']
                );
            }

            $category_product = array_merge($category_product_1, $category_product_2);
            // print_r($category_product);

            $queryCategoryProduct = $this->mod_product->Add('category_product', $category_product);

            if($queryCategoryProduct){
                echo "Berhasil menyimpan data kategori produk <br>";

                $table = "master_gudang";
                $select = "id_gudang";
                $where = "1";
                $gudangAktif = $this->mod_product->getGudangAktif($table, $select, $where);

                $hppArray = [];
                $infoGudangArray = [];

                foreach($gudangAktif as $d)
                {
                    foreach ($dataArray as $da) {
                        $id_product = $this->mod_product->getIdProduct('product','id_product',$da['kode_buku'])[0]['id_product'];
                        $hppArray[] = array(
                            'id' => 0,
                            'id_gudang' => $d['id_gudang'],
                            'id_produk' => $id_product,
                            'id_periode' => $id_periode,
                            'hpp' =>  $da['hpp'],
                            'diskon' =>  0,
                            'created_date' =>  'now()',
                            'updated_date' =>  'now()'
                        );

                        $infoGudangArray[] = array(
                            'id' => 0,
                            'id_produk' => $id_product,
                            'id_gudang' => $d['id_gudang'],
                            'Stok_fisik' => 0,
                            'stok_booking' =>  0,
                            'stok_available' =>  0,
                            'periode' =>  $periode,
                            'date_created' =>  'now()',
                            'date_updated' =>  'now()'
                        );
                    }
                }
                $query = $this->mod_product->Add('master_hpp', $hppArray);

                if($query)
                {
                    echo "Berhasil menyimpan data HPP <br>";

                    $qry = $this->mod_product->Add('info_gudang', $infoGudangArray);

                    if($qry)
                    {
                        echo "Berhasil menyimpan data info gudang <br>";

                    }
                    else
                    {
                        echo "Gagal menyimpan data info gudang <br/>";
                    }
                }
                else
                {
                    echo "Gagal menyimpan data HPP <br/>";
                }
            }
            else
            {
                echo "Gagal menyimpan data kategori produk <br/>";
            }
        }
        else
        {
            echo "Gagal menyimpan data produk <br/>";
        }

        if ($this->db->trans_status() === FALSE)
        {
                $this->db->trans_rollback();
                return false;
        }
        else
        {
                $this->db->trans_commit();
                return true;
        }
    }
    
    public function importDelete()
    {
        // $this->load->view('admin/product/import');
        $this->_output['content'] = $this->load->view('admin/product/importdelete', '', true);
        $this->_output['script_js'] = ''; //$this->load->view('admin/product/js', '', true);
        $this->load->view('admin/template', $this->_output);
    } 

    public function importDeletePost()
    {
        $file = $_FILES['mikon_file']['tmp_name'];
 
        //load the excel library
        $this->load->library('excel');
         
        //read file from path
        $objPHPExcel = PHPExcel_IOFactory::load($file);

        $sheetActive = $objPHPExcel->getSheet(0);
        // $sheetActive = $objPHPExcel->getActiveSheet();
         
        //get only the Cell Collection
        // $cell_collection = $objPHPExcel->getActiveSheet()->getCellCollection();
        $cell_collection = $sheetActive->getCellCollection();
         
        //extract to a PHP readable array format
        foreach ($cell_collection as $cell) {
            $column = $sheetActive->getCell($cell)->getColumn();
            $row = $sheetActive->getCell($cell)->getRow();
            $data_value = $sheetActive->getCell($cell)->getValue();
         
            //The header will/should be in row 1 only. of course, this can be modified to suit your need.
            if ($row == 1) {
                $header[$row][$column] = $data_value;
            } else {
                $arr_data[$row][$column] = $data_value;
            }
        }

        //send the data in an array format
        $data['header'] = $header;
        $data['values'] = $arr_data;

        $dataArray = [];

        foreach($arr_data as $vv){
            $id_product = $this->mod_product->getIdProduct('product','id_product',$vv['B'])[0]['id_product'];
            // $dataArray[] = array(
            //     'id_product' => $id_product
            // );
            $dataArray[] = $id_product;
            // echo $id_product."-".$vv['B']."<br>";
        }

        // print_r($dataArray);

        $query = $this->mod_product->Delete('product',$dataArray,'id_product');
        if($query)
        {
            echo "Berhasil menghapus produk <br/>";
            $query1 = $this->mod_product->Delete('category_product',$dataArray,'id_product');  
            if($query1)
            {
                echo "Berhasil menghapus kategori produk <br/>";
                $query2 = $this->mod_product->Delete('master_hpp',$dataArray,'id_produk');  
                if($query2)
                {
                    echo "Berhasil menghapus hpp produk <br/>";
                    $query3 = $this->mod_product->Delete('info_gudang',$dataArray,'id_produk');  
                    if($query3)
                    {
                        echo "Berhasil menghapus info gudang <br/>";
                    }
                    else
                    {
                        echo "Gagal menghapus info_gudang <br/>";
                    }
                }
                else
                {
                    echo "Gagal menghapus hpp produk <br/>";
                }
            }
            else
            {
                echo "Gagal menghapus kategori produk <br/>";
            }
        }
        else
        {
            echo "Gagal menghapus produk <br/>";
        }

        echo "<br/> <a href='".base_url('backoffice/product')."'>Kembali ke daftar produk</a>";
    }

    public function imageDataUpload2($data){
        // $files = glob("coba2/*.jpg");
        // print_r($files);
        // $path = "coba2/";
        $path = "assets/img/product/";
        $newData = [];
        array_multisort(array_map($this->imageDataGet2($data), ($files = glob($path."*.jpg*"))), SORT_DESC, $files);
        foreach($files as $namafile){
            $id_product = str_replace($path,"",str_replace(".jpg", "", $namafile));
            $url_image = str_replace($path,"",$namafile);
            $images = 1;

            $newData[]=array(
                'id_product' => $id_product,
                'url_image' => $url_image,
                'images' => $images
            );

            // echo "update product set images = 1, url_image='$id_product.jpg' where id_product = '$id_product';<br />";
        }

        // echo "<br><br>";
        // print_r($newData);
    }

    function uploadFilesForm()
    {
        $this->load->view('admin/product/upload');
    }

    function uploadFiles()
    {
        $count = 0;
        if ($_SERVER['REQUEST_METHOD'] == 'POST'){
            foreach ($_FILES['files']['name'] as $i => $name) {
                if (strlen($_FILES['files']['name'][$i]) > 1) {
                    if (move_uploaded_file($_FILES['files']['tmp_name'][$i], $_SERVER['DOCUMENT_ROOT'].'/coba/'.$name)) {
                        $count++;
                    }
                    else
                    {
                        echo "gagal upload";
                    }
                }
            }
        }


        $file = $_FILES['mikon_file']['tmp_name'];
 
        //load the excel library
        $this->load->library('excel');
         
        //read file from path
        $objPHPExcel = PHPExcel_IOFactory::load($file);

        $sheetActive = $objPHPExcel->getSheet(0);
        // $sheetActive = $objPHPExcel->getActiveSheet();
         
        //get only the Cell Collection
        // $cell_collection = $objPHPExcel->getActiveSheet()->getCellCollection();
        $cell_collection = $sheetActive->getCellCollection();
         
        //extract to a PHP readable array format
        foreach ($cell_collection as $cell) {
            $column = $sheetActive->getCell($cell)->getColumn();
            $row = $sheetActive->getCell($cell)->getRow();
            $data_value = $sheetActive->getCell($cell)->getValue();
         
            //The header will/should be in row 1 only. of course, this can be modified to suit your need.
            if ($row == 1) {
                $header[$row][$column] = $data_value;
            } else {
                $arr_data[$row][$column] = $data_value;
            }
        }

        //send the data in an array format
        $data['header'] = $header;
        $data['values'] = $arr_data;

        $dataArray = [];

        foreach($arr_data as $vv){
            $idproduct = $this->mod_product->getIdProduct('product','id_product',$vv['B'])[0]['id_product'];
            $dataArray[] = array(
                'id_product' => $idproduct,
                'images' =>  $vv['U']
            );
        }

        $this->imageDataUpload($dataArray);
    }

    public function imageDataUpload($data){
        // $files = glob("coba2/*.jpg");
        // print_r($files);
        $path = "coba2/";
        $newData = [];
        array_multisort(array_map($this->imageDataGet($data), ($files = glob($path."*.jpg*"))), SORT_DESC, $files);
        foreach($files as $namafile){
            $id_product = str_replace($path,"",str_replace(".jpg", "", $namafile));
            $url_image = str_replace($path,"",$namafile);
            $images = 1;

            $newData[]=array(
                'id_product' => $id_product,
                'url_image' => $url_image,
                'images' => $images
            );

            echo "update product set images = 1, url_image='$id_product.jpg' where id_product = '$id_product';<br />";
        }

        // echo "<br><br>";
        // print_r($newData);
    }

    public function imageDataGet($datas)
    {
        //===================
        // config php.ini
        //===================
        error_reporting(E_ALL ^ (E_NOTICE | E_WARNING));
        set_time_limit(21600000000);
        ini_set('memory_limit', '-1');

        //===================
        // Set variable path
        //===================
        $path = "coba2/";        

        //===================
        // set variable url
        //===================
        // $url = "http://bukusekolah.gramedia.test/coba/";
        $url = "./coba/";

        //===================
        // example array
        //===================
        // $datas = Array(Array ( 'id_product' => 1001, 'images' => 'gambar1.jpg' ), Array ( 'id_product' => 1002, 'images' => 'gambar2.jpg'));
         
        echo "<br>";
        // print_r($datas);

        foreach($datas as $data){
            $images = $data['images'];
            $imagesUrl = $url.$images;
            $id = $data['id_product'];
            // $name = strtolower(str_replace(" ", "-", str_replace(":","",$data['name']))).".jpg";
            // $fileName = $id."-".$name;
            echo $imagesUrl."  ##  ";
            echo $id.".jpg"."<br>";

            //===========================
            // destination file location
            //===========================
            $destFileUrl = $path.$id.".jpg";

            try {
                file_put_contents( $destFileUrl, file_get_contents($imagesUrl));
            } catch (Exception $e) {
                echo $e;
            }
            
            sleep(1);
        }
        echo "<br/>";
    }
}
