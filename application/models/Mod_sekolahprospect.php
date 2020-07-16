<?php
defined('BASEPATH') || exit('No direct script access allowed');

class Mod_sekolahprospect extends CI_Model
{

    public function __construct()
    {
        parent::__construct();
    }

    public function getAll($table, $select = '*', $where = null, $order_by = null, $group_by = null)
    {
        $this->db->select($select);
        $this->db->from($table);
        if ($where) {
            $this->db->where($where);
        }

        if ($order_by) {
            $this->db->order_by($order_by);
        }

        if ($group_by) {
            $this->db->group_by($group_by);
        }

        return $this->db->get()->result();
    }

    public function getList($table, $select = '*', $where = null, $order_by = null, $group_by = null)
    {
        $this->db->select($select);
        $this->db->from($table);
        if ($where) {
            $this->db->where($where);
        }

        if ($order_by) {
            $this->db->order_by($order_by);
        }

        if ($group_by) {
            $this->db->group_by($group_by);
        }

        return $this->db->get()->num_rows();
    }

    public function add($table, $data)
    {
        $this->db->insert($table, $data);
        return $this->db->insert_id();
    }

    public function addDetail($table, $data)
    {
        $this->db->insert($table, $data);
    }

    public function edit($table, $where, $data)
    {
        $this->db->set($data);
        $this->db->where($where);
        $this->db->update($table);
    }

    public function delete($table, $where)
    {
        $this->db->where($where);
        $this->db->delete($table);
    }

    public function getProspectHistory($select = 'customer_prospect_history.*, employee.name, employee.email, employee.telp', $where = null, $order_by = 'customer_prospect_history.id DESC', $group_by = null)
    {
        $this->db->select($select);
        $this->db->join("employee", "customer_prospect_history.id_mitra = employee.id_employee", 'INNER');
        $this->db->from("customer_prospect_history");
        if ($where) {
            $this->db->where($where);
        }

        if ($order_by) {
            $this->db->order_by($order_by);
        }

        if ($group_by) {
            $this->db->group_by($group_by);
        }

        return $this->db->get()->result();
    }

    public function getProspectRequest($select = 'mitra_profile.*', $where = null, $order_by = null, $group_by = null)
    {
        $this->db->select($select);
        $this->db->join("mitra_profile", "customer.id_mitra = mitra_profile.id_employee", 'INNER');
        $this->db->from("customer");
        if ($where) {
            $this->db->where($where);
        }

        if ($order_by) {
            $this->db->order_by($order_by);
        }

        if ($group_by) {
            $this->db->group_by($group_by);
        }

        return $this->db->get()->num_rows();
    }

    public function getSalesProspectRequest($select = 'employee.*, customer.date_prospect_start, customer_prospect_history.id as id_customer_prospect, customer_prospect_history.notes', $where = null, $order_by = null, $group_by = null)
    {
        $this->db->select($select);
        $this->db->from("customer");
        $this->db->join("customer_prospect_history", "customer.id_customer = customer_prospect_history.id_customer", 'INNER');
        $this->db->join("employee", "customer_prospect_history.id_mitra = employee.id_employee", 'INNER');
        if ($where) {
            $this->db->where($where);
        }

        if ($order_by) {
            $this->db->order_by($order_by);
        }

        if ($group_by) {
            $this->db->group_by($group_by);
        }

        return $this->db->get()->result();
    }

    public function getStateHistoryByCustomer($id_customer, $select = 'a.*, c.name', $where = null, $order_by = null, $group_by = null)
    {
        $this->db->select($select);
        $this->db->from('sekolah_prospect_history a');
        $this->db->join('customer_prospect_history b', 'a.id_customer_prospect_history = b.id', 'inner');
        $this->db->join('employee c', 'a.id_employee = c.id_employee', 'inner');
        $this->db->where('b.id_customer', $id_customer);
        if ($where) {
            $this->db->where($where);
        }

        if ($order_by) {
            $this->db->order_by($order_by);
        }

        if ($group_by) {
            $this->db->group_by($group_by);
        }

        return $this->db->get()->result();
    }

}

