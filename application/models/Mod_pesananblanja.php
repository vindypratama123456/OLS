<?php
defined('BASEPATH') || exit('No direct script access allowed');

class Mod_pesananblanja extends CI_Model
{
    public function __construct()
    {
        parent::__construct();
    }

    public function getAll($table, $select = '*', $where = null, $orderBy = null, $groupBy = null, $limit = null)
    {
        $this->db->select($select);
        $this->db->from($table);
        if ($where) {
            $this->db->where($where);
        }
        if ($orderBy) {
            $this->db->order_by($orderBy);
        }
        if ($groupBy) {
            $this->db->group_by($groupBy);
        }
        if ($limit) {
            $this->db->limit($limit);
        }
        return $this->db->get();
    }

    public function getWhereIn($table, $select = '*', $field, $where_in = null)
    {
        $this->db->select($select);
        $this->db->from($table);
        if ($where_in) {
            $this->db->where_in($field, $where_in);
        }
        return $this->db->get();
    }

    public function getDataKorwil($kabupaten)
    {
        $this->db->from('employee_kabupaten_kota a');
        $this->db->join('employee b', 'a.id_employee=b.id_employee','inner');
        $this->db->where('b.level', 3);
        $this->db->where('kabupaten_kota', $kabupaten);
        return $this->db->get();
    }

    public function getDataRSM($kabupaten)
    {
        $this->db->from('employee_kabupaten_kota a');
        $this->db->join('employee b', 'a.id_employee=b.id_employee','inner');
        $this->db->where('b.level', 8);
        $this->db->where('kabupaten_kota', $kabupaten);
        return $this->db->get();
    }

    public function tambahDetailPesananSiplah_temp($data, $id_order)
    {
        foreach ($data as $orders) {

            $sql = "insert into `orders_siplah_detail` (`order_siplah_id`, `kode_buku`, `product_id`, `product_name`, `product_quantity`, `unit_price`, `total_price`) values ('".$id_order."', '".$orders['kode_buku']."', '".$orders['product_id']."', '".$orders['product_name']."','".$orders['product_quantity']."','".$orders['unit_price']."','".$orders['total_price']."')";
            $this->db->query($sql);
        }
        return true;
    }

    public function tambahPesananSiplah($orderDetail)
    {    
        $data = Array ( 
            'reference' => $orderDetail['po_number']
            ,'current_state' => '1' 
            ,'id_customer' => $orderDetail['id_customer'] 
            ,'category' => $orderDetail['category'] 
            ,'type' => $orderDetail['type'] 
            ,'korwil_email' => $orderDetail['korwil_email']
            ,'korwil_name' => $orderDetail['korwil_name'] 
            ,'korwil_phone' => $orderDetail['korwil_phone'] 
            ,'periode' => $orderDetail['periode']
            ,'rsm_name' => $orderDetail['rsm_name'] 
            ,'total_paid' => $orderDetail['total_paid'] 
            ,'date_add' => $orderDetail['created_date'] 
            ,'reference_other' => $orderDetail['reference_other'] 
            ,'reference_other_from' => "Siplah.id" 
        );

        if ($this->db->insert('orders', $data)) {
            return $this->db->insert_id();
        }
        return false;
    }

    public function tambahDetailPesananSiplah($data, $id_order)
    {
        foreach ($data as $orders) {

            $sql = "insert into `order_detail` (`id_order`, `kode_buku`, `product_id`, `product_name`, `product_quantity`, `unit_price`, `total_price`) values ('".$id_order."', '".$orders['kode_buku']."', '".$orders['product_id']."', '".$orders['product_name']."','".$orders['product_quantity']."','".$orders['unit_price']."','".$orders['total_price']."')";
            $this->db->query($sql);
        }
        return true;
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

    public function isInSCMProcess($idOrder)
    {
        $this->db->select('id_order');
        $this->db->from('order_scm');
        $this->db->where('id_order', $idOrder);
        $this->db->where('status >', 1);
        $qSCM = $this->db->get();
        return ($qSCM->num_rows() > 0) ? true : false;
    }

    public function updateDataOrder()
    {
        
    }
}
