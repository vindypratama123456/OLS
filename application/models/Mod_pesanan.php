<?php
defined('BASEPATH') || exit('No direct script access allowed');

class Mod_pesanan extends CI_Model
{
    private $_sessIdCustomer;

    public function __construct()
    {
        parent::__construct();
        $this->_sessIdCustomer = $this->session->userdata('id_customer');
    }

    public function getDiscount()
    {
        if ($this->_sessIdCustomer) {
            $sql =
            "SELECT a.reduction
            FROM reduction a
            LEFT JOIN customer b ON (a.id_group = b.id_group)
            WHERE 1
            AND b.id_customer = " . $this->db->escape($this->_sessIdCustomer);
            return $this->db->query($sql)->result_array();
        } else {
            return array(array("reduction"=>0));
        }
    }

    public function getPesanan()
    {
        return $this->db->query("SELECT
            a.*,
            b.name as order_state_name,
            b.label AS label
            FROM orders a
            LEFT JOIN order_state b ON (a.current_state = b.id_order_state)
            WHERE 1
            AND a.id_customer = " . $this->db->escape($this->_sessIdCustomer)."
            ORDER BY a.date_add DESC")->result();
    }

    public function getPesananInfo($id_order)
    {
        return $this->db->query("SELECT
                a.*,
                a.date_add AS tgl_pesan,
                a.date_upd AS tgl_update,
                b.name AS order_state_name,
                c.*
            FROM orders a
            LEFT JOIN order_state b ON (a.current_state = b.id_order_state)
            LEFT JOIN customer c ON (c.id_customer = a.id_customer)
            WHERE 1
            AND a.id_customer = " . $this->db->escape($this->_sessIdCustomer)."
            AND a.id_order = " . $this->db->escape($id_order))->result();
    }

    public function getDetailPesanan($id_order)
    {
        return $this->db->query("SELECT a.type as type_alias,b.*,c.*,d.name AS category, e.name as parent_name
            FROM orders a
            LEFT JOIN order_detail b ON (a.id_order = b.id_order)
            LEFT JOIN product c ON (c.id_product = b.product_id)
            LEFT JOIN category d ON (d.id_category = c.id_category_default)
            LEFT JOIN category e ON (e.id_category = d.id_parent)
            WHERE 1
            AND a.id_customer = " . $this->db->escape($this->_sessIdCustomer)."
            AND a.id_order = " . $this->db->escape($id_order))->result();
    }

    public function tambahPesanan($order_reference,$orderDetail, $offline = false)
    {
        $category    = $orderDetail["category"];
        $type        = $orderDetail["type"];
        $total_pay   = $orderDetail["totalPay"];
        if ($offline) {
            $sql = "INSERT INTO orders (`reference`,`id_customer`,`current_state`,`category`,`type`,`total_paid`,`date_add`,`is_offline`) VALUES ('$order_reference','".$this->session->userdata('id_customer_offline')."','1','$category','$type','$total_pay','".date("Y-m-d H:i:s")."','1')";
        } else {
            $sql = "INSERT INTO orders (`reference`,`id_customer`,`current_state`,`category`,`type`,`total_paid`,`date_add`) VALUES ('$order_reference','".$this->_sessIdCustomer."','1','$category','$type','$total_pay','".date("Y-m-d H:i:s")."')";
        }
        $this->db->query($sql);
        return $this->db->insert_id();
    }

    public function editPesanan($id, $data)
    {
        $this->db->set($data);
        $this->db->where('id_order', $id);
        $this->db->update('orders');
    }

    public function tambahDetailPesanan($data, $id_order)
    {
        foreach ($data as $orders) {
            $sql = "INSERT INTO order_detail (`id_order`,`kode_buku`,`product_id`,`product_name`,`product_quantity`,`unit_price`,`total_price`) VALUES ('".$id_order."','".$orders['kode_buku']."','".$orders['product_id']."','".$orders['product_name']."','".$orders['product_quantity']."','".$orders['unit_price']."','".$orders['total_price']."')";
            $this->db->query($sql);
        }
        return true;
    }

    public function tambahDetailPesanan2020($orders, $id_order)
    {
        // foreach ($data as $orders) {
            $sql = "INSERT INTO order_detail (`id_order`,`kode_buku`,`product_id`,`product_name`,`product_quantity`,`unit_price`,`total_price`) VALUES ('".$id_order."','".$orders['kode_buku']."','".$orders['product_id']."','".$orders['product_name']."','".$orders['product_quantity']."','".$orders['unit_price']."','".$orders['total_price']."')";
            $query = $this->db->query($sql);
        // }
        
            if($query)
            {
                return true;        
            }
            else
            {
                return false;
            }
        
    }

    public function insertFeedback($data)
    {
        return $this->db->insert('feedback', $data);
    }

    public function is_commented($id_order)
    {
        $sql = "SELECT * FROM feedback WHERE id_order = " . $this->db->escape($id_order);
        $query = $this->db->query($sql);
        return $query->num_rows();
    }

    public function getFeedback($id_order)
    {
        return $this->db->query("SELECT * FROM feedback WHERE id_order = " . $this->db->escape($id_order))->result();
    }

    public function get_semester($idorder)
    {
        $this->db->select('tz.`id_order` as id_order, tx.`semester` as semester');
        $this->db->from('order_detail tz');
        $this->db->join('`product` ty', 'tz.`product_id`=ty.`id_product`', 'inner');
        $this->db->join('`product_semester` tx', 'ty.`id_product`=tx.`id_product`', 'left');
        $this->db->where('id_order', $idorder);
        $this->db->group_by('id_order');
        $query = $this->db->get();
        return $query;
    }

    public function upd_semester($id_order, $semester)
    {
        $this->db->set('semester', $semester);
        $this->db->where('id_order', $id_order);
        $this->db->update('orders');
    }
}
