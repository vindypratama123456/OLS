<?php
defined('BASEPATH') || exit('No direct script access allowed');

class Mod_akunku extends MY_Model
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

    public function getDetail($id_customer)
    {
        $sql = "SELECT * FROM customer WHERE 1 AND id_customer = ".$this->db->escape($id_customer);

        return $this->db->query($sql)->row();
    }

    public function updateProfil($data, $id_customer)
    {
        $this->db->where('id_customer', $id_customer);
        if ($this->db->update($this->tblCustomer, $data)) {
            return true;
        }

        return false;
    }

    public function restrict($id_customer, $select, $where = null)
    {
        $this->db->select($select);
        if ($where) {
            $this->db->where($where);
        }
        $this->db->where('id_customer', $id_customer);

        return $this->db->get($this->tblCustomer)->result_array();
    }

    public function checkExist($tableName, $field, $value, $objectField = null, $objectId = null)
    {
        $this->db->where_in("LOWER($field)", strtolower($value));
        if ($objectField != null && $objectId != null) {
            $this->db->where_not_in($objectField, $objectId);
        }

        return $this->db->get($tableName)->num_rows();
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

    public function checkUserEmail($email)
    {
        $query = $this->db->get_where($this->tblEmployee, ['email' => $email, 'active' => 1], 1);

        return $query->num_rows() > 0;
    }

    public function checkToken($token)
    {
        $query = $this->db->get_where($this->tblEmployee, ['token' => $token, 'active' => 1], 1);

        return $query->num_rows() > 0;
    }

    public function getSchoolLogin($data, $type = 1)
    {
        $this->db->select();
        $this->db->from('customer');
        $this->db->where('active', 1);
        $this->db->where('no_npsn', $data['no_npsn']);
        if ($data['email_operator']) {
            $this->db->where('email_operator', $data['email_operator']);
        }
        $query = $this->db->get();

        if ($type == 1) {
            return $query->num_rows();
        }

        return $query->result();
    }
}
