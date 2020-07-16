<?php
defined('BASEPATH') || exit('No direct script access allowed');

class Mod_feedback extends CI_Model
{
    public function __construct()
    {
        parent::__construct();
    }

    public function detailTestimony($id)
    {
        $this->db->select('a.*,b.reference,c.*');
        $this->db->from('feedback a');
        $this->db->join('orders b', 'b.id_order=a.id_order', 'inner');
        $this->db->join('customer c', 'c.id_customer=b.id_customer', 'inner');
        $this->db->where('a.id_order', $id);
        $query = $this->db->get();
        return $query->row_array();
    }
}