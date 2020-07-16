<?php
defined('BASEPATH') || exit('No direct script access allowed');

class Mod_kontrak extends CI_Model
{
    public function __construct()
    {
        parent::__construct();
    }

    public function getDetail($id)
    {
        $this->db->select(' `employee`.`id_employee` as id_employee,
                            `employee`.`code` as code,
                            `employee`.`name` as name,
                            `employee`.`email` as email,
                            `employee`.`regional` as regional,
                            date_format(`mitra_kontrak`.`mikon_tanggal`,"%d-%m-%Y") as mikon_tanggal,
                            `mitra_kontrak`.`mikon_periode` as mikon_periode,
                            `mitra_kontrak`.`mikon_file` as mikon_file
                        ');
        $this->db->from('employee');
        $this->db->join('mitra_kontrak', 'employee.id_employee=mitra_kontrak.mikon_employee_id', 'left');
        $this->db->where('employee.level', '4');
        $this->db->where('employee.active', '1');
        $this->db->where('employee.code', $id);
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

    public function updateKontrak($table, $where, $data)
    {
        $this->db->where($where);
        if ($this->db->update($table, $data)) {
            return true;
        }

        return false;
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
}
