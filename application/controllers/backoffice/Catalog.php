<?php
defined('BASEPATH') or exit('No direct script access allowed');

/**
 * @property Mod_general $mod_general
 * @property Datatable $datatable
 * @property Datatables $datatables
 */
class Catalog extends MY_Controller
{
    private $table;
    private $_output;

    public function __construct()
    {
        parent::__construct();
        $this->load->model('mod_general');
        $this->table = 'product';
        $this->_output = array();
    }

    public function index()
    {
        $data['is_operator'] = ($this->session->userdata('adm_level') == 3) ? true : false;
        $this->_output['content'] = $this->load->view('admin/catalog/list', $data, true);
        $this->_output['script_js'] = $this->load->view('admin/catalog/js', '', true);
        $this->load->view('admin/template', $this->_output);
    }

    public function list_catalog()
    {
        if ( ! $this->input->is_ajax_request()) {
            redirect(ADMIN_PATH . '/catalog');
        }
        $this->load->library('datatables');
        $this->output->set_header('Content-Type:application/json; charset=utf-8');
        $this->datatables->select('o.id_product AS id_product, 
                o.name AS name, 
                o.reference AS isbn, 
                o.kode_buku AS kode_buku, 
                p.name AS category, 
                q.name AS type, 
                ROUND(o.price_1) AS price_1, 
                ROUND(o.price_2) AS price_2, 
                ROUND(o.price_3) AS price_3, 
                ROUND(o.price_4) AS price_4, 
                ROUND(o.price_5) AS price_5');
        $this->datatables->from($this->table . ' o');
        $this->datatables->join('category p', 'p.id_category=o.id_category_default', 'inner');
        $this->datatables->join('category q', 'q.id_category=p.id_parent', 'inner');
        $this->datatables->where('o.active', 1);
        $this->datatables->where('o.kode_buku !=', null);
        $this->datatables->where('o.kode_buku !=', '');
        $this->output->set_output($this->datatables->generate());
    }

    public function detail($id)
    {
        if ($this->session->userdata('adm_level') == 3) {
            if (false == $this->isHaveAccess($id)) {
                redirect(ADMIN_PATH . '/catalog', 'refresh');
            }
        }
        $data['detil'] = $this->mod_general->detailData($this->table, 'id_product', $id);
        $this->_output['content'] = $this->load->view('admin/catalog/detail', $data, true);
        $this->_output['script_js'] = $this->load->view('admin/catalog/js', '', true);
        $this->load->view('admin/template', $this->_output);
    }

    private function isHaveAccess($idProduct)
    {
        $raw_query = $this->db->query("SELECT b.id_group AS region FROM catalog a JOIN customer b ON b.id_customer=a.id_customer WHERE a.id_product=".$this->db->escape($idProduct));
        $region = $query->row('region');
        if ($region == $this->session->userdata('adm_region')) {
            return true;
        }
        return false;
    }

    public function editPost()
    {
        if ( ! $this->input->is_ajax_request()) {
            redirect(ADMIN_PATH . '/catalog');
        }
        try {
            $id = $this->input->post('id_product');
            $currentState = $this->input->post('current_state');
            $idState = $this->input->post('id_product_state');
            // update ke tabel catalog
            $data = [
                'current_state' => $idState,
                'date_upd' => date('Y-m-d H:i:s')
            ];
            $proc1 = $this->mod_general->updateData($this->table, $data, 'id_product', $id);
            if ($proc1) {
                if ($currentState == $idState) {
                    $callBack = [
                        'success' => 'true',
                        'message' => 'Data successfully updated.'
                    ];
                    echo json_encode($callBack, true);
                    exit();
                } else {
                    // insert ke tabel order_history
                    $dataHistory = [
                        'id_employee' => $this->session->userdata('adm_id'),
                        'id_product' => $id,
                        'id_product_state' => $idState,
                        'date_add' => date('Y-m-d H:i:s')
                    ];
                    $proc2 = $this->mod_general->addData('order_history', $dataHistory);
                    if ($proc2) {
                        $callBack = [
                            'success' => 'true',
                            'message' => 'Data successfully inserted.'
                        ];
                        $this->session->set_flashdata('msg_success',
                            'Data pesanan dengan ID: <b>' . $id . '</b> berhasil <b>DIPERBARUI</b></p>');
                    } else {
                        $callBack = [
                            'success' => 'false',
                            'message' => 'Failed to insert order history.'
                        ];
                    }
                }
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

    public function edit($id, $qty)
    {
        if ( ! $this->input->is_ajax_request()) {
            return false;
        }
        $data['old_qty'] = $qty;
        $data['detil'] = $this->mod_general->detailData('order_detail', 'id_product_detail', $id);
        $this->load->view('admin/catalog/edit_detail', $data);
    }

    public function updatePost()
    {
        if ( ! $this->input->is_ajax_request()) {
            redirect(ADMIN_PATH . '/catalog');
        }
        try {
            $idProduct = $this->input->post('id_product');
            $idProductDetail = $this->input->post('id_product_detail');
            $productId = $this->input->post('product_id');
            $productName = $this->input->post('product_name');
            $unitPrice = $this->input->post('unit_price');
            $productQuantity = $this->input->post('product_quantity');
            $oldQty = $this->input->post('old_qty');
            // jika quantity tidak berubah
            if ($productQuantity == $oldQty) {
                $callBack = array(
                    'success' => 'true',
                    'message' => 'Data successfully updated.',
                    'id_product' => $idProduct
                );
                echo json_encode($callBack, true);
                exit();
            } else {
                // insert ke tabel order_detail_revisi
                $data1 = [
                    'id_product' => $idProduct,
                    'id_product_detail' => $idProductDetail,
                    'product_id' => $productId,
                    'product_name' => $productName,
                    'quantity_before' => $oldQty,
                    'quantity_after' => $productQuantity,
                    'created_at' => date('Y-m-d H:i:s'),
                    'created_by' => $this->session->userdata('adm_id')
                ];
                $proc1 = $this->mod_general->addData('order_detail_history', $data1);
                if ($proc1) {
                    // update tabel order_detail
                    $data2 = [
                        'product_quantity' => $productQuantity,
                        'total_price' => $unitPrice * $productQuantity
                    ];
                    $proc2 = $this->mod_general->updateData('order_detail', $data2, 'id_product_detail',
                        $idProductDetail);
                    if ($proc2) {
                        // update tabel catalog
                        $q_total = $this->db->query("SELECT SUM(total_price) AS total FROM order_detail WHERE id_product=".$this->db->escape($idProduct));
                        $totalPaid = $q_total->row('total');
                        $data3 = ['total_paid' => $totalPaid];
                        $proc3 = $this->mod_general->updateData('catalog', $data3, 'id_product', $idProduct);
                        if ($proc3) {
                            $callBack = [
                                'success' => 'true',
                                'message' => 'Data successfully updated.',
                                'id_product' => $idProduct
                            ];
                        } else {
                            $callBack = [
                                'success' => 'false',
                                'message' => 'Failed to update catalog.'
                            ];
                        }
                    } else {
                        $callBack = [
                            'success' => 'false',
                            'message' => 'Failed to update catalog_detail'
                        ];
                    }
                } else {
                    $callBack = [
                        'success' => 'false',
                        'message' => 'Failed to insert data.'
                    ];
                }
                echo json_encode($callBack, true);
            }
        } catch (Exception $e) {
            echo 'Caught exception: ', $e->getMessage(), "\n";
        }
    }
}
