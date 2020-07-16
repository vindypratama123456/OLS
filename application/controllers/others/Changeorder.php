<?php
defined('BASEPATH') or exit('No direct script access allowed');

/**
 * @property Mod_scm $mod_scm
 */
class Changeorder extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('mod_scm');
    }

    public function index()
    {
        $this->editOrderDetail();
    }

    public function editOrderDetail()
    {
        $this->db->trans_begin();
        $tempOrderdetail = $this->mod_scm->getAll("temp_order_detail", "*", "", "id_order asc, kode_buku asc");
        $dataTemp = [];
        foreach ($tempOrderdetail as $key => $value) {
            $dataTemp["product_quantity"] = $value->product_quantity;
            $dataTemp["quantity_fullfil"] = $value->quantity_fullfil;
            $dataTemp["unit_price"] = $value->unit_price;
            $dataTemp["total_price"] = $value->total_price;
            $this->mod_scm->editAll('order_detail', $dataTemp, 'id_order=' . $value->id_order . ' and kode_buku="' . $value->kode_buku . '"');
        }
        if ($this->db->trans_status() === true) {
            $this->db->trans_commit();
            $callBack['message'] = "Update order detail successfully";
            echo json_encode($callBack);
        } else {
            $this->db->trans_rollback();
            $callBack['message'] = "Update order detail unsuccessfully";
            echo json_encode($callBack);
        }
    }

    public function editOrders()
    {
        $this->db->trans_begin();
        $tempIdOrder = $this->mod_scm->getAll("temp_order_detail", "id_order", "", "id_order, kode_buku asc", "id_order");
        $dataOrders = [];
        $dataOrderHistory = [];
        foreach ($tempIdOrder as $key => $value) {
            $totalPaid = $this->mod_scm->getAll("order_detail", "SUM(total_price) as total_paid", "id_order = " . $value->id_order)[0];
            $dataOrders["total_paid"] = $totalPaid->total_paid;
            $dataOrders["date_upd"] = date("Y-m-d H:i:s");
            $this->mod_scm->editAll('orders', $dataOrders, 'id_order=' . $value->id_order);
            $dataOrderHistory['id_employee'] = 2;
            $dataOrderHistory['id_order'] = $value->id_order;
            $dataOrderHistory['id_order_state'] = 8;
            $dataOrderHistory['notes'] = "Pesanan Batal Sebagian";
            $dataOrderHistory["date_add"] = date("Y-m-d H:i:s");
            $this->mod_scm->addDetail('order_history', $dataOrderHistory);
        }
        if ($this->db->trans_status() === true) {
            $this->db->trans_commit();
            $callBack['message'] = "Update update orders and add order history successfully";
            echo json_encode($callBack);
        } else {
            $this->db->trans_rollback();
            $callBack['message'] = "Update update orders and add order history unsuccessfully";
            echo json_encode($callBack);
        }
    }
}
