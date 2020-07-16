<?php
defined('BASEPATH') || exit('No direct script access allowed');

class Mod_mitra extends CI_Model
{
    public function __construct()
    {
        parent::__construct();
    }

    public function getDetail($id)
    {
        $this->db->select('a.id_employee, a.code, a.name, a.email, a.active, a.telp, b.*, c.id_employee AS id_korwil, c.email AS email_korwil, c.name AS korwil, d.name AS nama_referensi');
        $this->db->from('employee a');
        $this->db->join('mitra_profile b', 'b.id_employee=a.id_employee', 'inner');
        $this->db->join('employee c', 'c.code=b.code_korwil', 'inner');
        $this->db->join('employee d', 'd.code=b.code_referral', 'left');
        $this->db->where('a.id_employee', $id);
        $query = $this->db->get();
        return $query->row_array();
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

    public function checkExist($tableName, $field, $value, $objectField = null, $objectId = null)
    {
        $this->db->where_in("LOWER($field)", strtolower($value));
        if ($objectField != null and $objectId != null) {
            $this->db->where_not_in($objectField, $objectId);
        }
        return $this->db->get($tableName)->num_rows();
    }

    public function getMitraByKorwil($codeKorwil, $order_by = null)
    {
        $this->db->select('a.id_employee, a.code, a.name, a.email');
        $this->db->from('employee a');
        $this->db->join('mitra_profile b', 'b.id_employee=a.id_employee', 'inner');
        $this->db->where('b.code_korwil', $codeKorwil);
        $this->db->where('a.level', 4);
        $this->db->where('a.active', 1);
        $this->db->where('b.is_activated', 1);
        if ($order_by) {
            $this->db->order_by($order_by);
        }
        return $this->db->get()->result();
    }

    public function getMitraByEmail($email)
    {
        $this->db->select('a.id_employee, a.code, a.name, a.email, b.bank_account_type');
        $this->db->from('employee a');
        $this->db->join('mitra_profile b', 'b.id_employee=a.id_employee', 'inner');
        $this->db->where_in('a.email', $email);
        $this->db->order_by('b.bank_account_type', 'asc');
        return $this->db->get()->result();
    }

    public function get_data_kontrak($id)
    {
        $this->db->select('*');
        $this->db->from('mitra_kontrak');
        $this->db->where('mikon_employee_id', $id);
        $this->db->order_by('`mikon_employee_id` ASC, `mikon_tanggal_akhir` DESC');
        $this->db->limit(1);
        return $this->db->get()->row_array();

    }
}
