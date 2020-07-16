<?php
defined('BASEPATH') || exit('No direct script access allowed');

class Mod_testimoni extends CI_Model
{
    public function __construct()
    {
        parent::__construct();
    }

    public function getFeedback($limit = 10, $start = 0)
    {
        $this->db->select('customer.school_name, feedback.rating, feedback.comment, feedback.created_at');
        $this->db->from('feedback');
        $this->db->join('orders', 'feedback.id_order = orders.id_order', 'inner');
        $this->db->join('customer', 'customer.id_customer = orders.id_customer', 'inner');
        $this->db->where('feedback.enable', 1);
        $this->db->limit($limit, $start);
        $this->db->order_by('feedback.created_at', 'DESC');
        return $this->db->get()->result();
    }

    public function getNumRows()
    {
        $this->db->select('customer.school_name, feedback.rating, feedback.comment, feedback.created_at');
        $this->db->from('feedback');
        $this->db->join('orders', 'feedback.id_order = orders.id_order', 'inner');
        $this->db->join('customer', 'customer.id_customer = orders.id_customer', 'inner');
        $this->db->where('feedback.enable', 1);
        return $this->db->get()->num_rows();
    }
}
