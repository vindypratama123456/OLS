<?php
defined('BASEPATH') || exit('No direct script access allowed');

class Mod_dashboard extends CI_Model
{
    public function __construct()
    {
        parent::__construct();
    }

    public function getTotalOrder($start = false, $end = false)
    {
        if ($start && $end) {
            $sql = "SELECT a.*,b.*
                    FROM orders a
                    LEFT JOIN customer b ON b.id_customer=a.id_customer
                    WHERE
                        a.date_add >= " . $this->db->escape($start) . " AND
                        a.date_add <= " . $this->db->escape($end);
        } else {
            $sql = "SELECT * FROM orders";
        }
        $query = $this->db->query($sql);
        return $query->num_rows();
    }

    public function countOrder($type = false)
    {
        $this->db->select("count(`id_order`) as total");
        $this->db->from("orders");
        $this->db->where("current_state NOT IN (1, 2, 4)");
        if ($type == 2) {
            $this->db->where("sts_bayar >", 0);
        }
        $query = $this->db->get();
        return $query->row('total');
    }

    public function getTotalOrders($status = false)
    {
        $this->db->select('COUNT(`id_order`) AS `total`');
        $this->db->from('orders');
        if ($status) {
            $this->db->where('current_state', $status);
        }
        $query = $this->db->get();
        return $query->row('total');
    }

    public function getListOrder(
        $limit = ['perpage' => 10, 'offset' => 0],
        $start = false,
        $end = false,
        $order = 'desc'
    ) {
        $this->db->select('a.id_order AS id_order,
                           a.reference AS reference,
                           a.category AS category,
                           a.type AS type,
                           b.email AS email,
                           b.no_npsn AS no_npsn,
                           b.school_name AS school_name,
                           b.provinsi AS provinsi,
                           b.kabupaten AS kabupaten,
                           c.name AS order_state,
                           c.label AS label,
                           a.total_paid AS total_paid,
                           a.date_add AS date_add');
        $this->db->from('orders a');
        $this->db->join('customer b', 'b.id_customer=a.id_customer', 'left');
        $this->db->join('order_state c', 'c.id_order_state=a.current_state', 'left');
        if ($start && $end) {
            $this->db->where('a.date_add >=', $start);
            $this->db->where('a.date_add <=', $end);
        }
        $this->db->order_by('a.id_order', $order);
        $this->db->limit($limit['perpage'], $limit['offset']);
        if (!in_array($this->session->userdata('adm_level'), $this->backoffice_superadmin_area)) {
            $this->db->where('b.kabupaten IN (SELECT aa.kabupaten_kota FROM employee_kabupaten_kota aa WHERE aa.id_employee = ' . $this->session->userdata('adm_id') . ')');
        }
        $q = $this->db->get()->result();
        return $q;
    }
}