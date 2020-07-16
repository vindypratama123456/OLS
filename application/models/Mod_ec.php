<?php
defined('BASEPATH') || exit('No direct script access allowed');

class Mod_ec extends CI_Model
{
    public function __construct()
    {
        parent::__construct();
    }

    public function ec_del($kabupaten, $id_employee_old)
    {
        // $this->db->from('employee_kabupaten_kota');
        $this->db->where_in('kabupaten_kota', $kabupaten);
        $this->db->where('id_employee', $id_employee_old);
        $this->db->delete('employee_kabupaten_kota');

        if($this->db->affected_rows())
        {
            return true;
        }
        return false;
    }

    public function getEcByKabupaten($kabupaten)
    {
        $this->db->select("a.id_employee");
        $this->db->from("employee_kabupaten_kota a");
        $this->db->join('employee b', 'a.id_employee=b.id_employee', 'inner');
        $this->db->where_in('kabupaten_kota', $kabupaten);
        $this->db->where('level', 3);
        $this->db->where('active', 1);
        $this->db->limit(1);

        return $this->db->get()->result();
    }

    public function getCodeEC()
    {
        $query = $this->db->query("SELECT tbx.Number FROM (SELECT (tens.Number * 10) + ones.Number +1 AS Number FROM (SELECT 0 AS Number UNION SELECT 1 UNION SELECT 2 UNION SELECT 3 UNION SELECT 4 UNION SELECT 5 UNION SELECT 6 UNION SELECT 7 UNION SELECT 8 UNION SELECT 9)tens CROSS JOIN (SELECT 0 AS Number UNION SELECT 1 UNION SELECT 2 UNION SELECT 3 UNION SELECT 4 UNION SELECT 5 UNION SELECT 6 UNION SELECT 7 UNION SELECT 8 UNION SELECT 9)ones ORDER BY Number LIMIT 99)tbx WHERE tbx.Number NOT IN (SELECT CODE AS Number FROM employee WHERE LEVEL=3 AND CODE IS NOT NULL) LIMIT 1");
        $row = $query->row();
        return $row;
    }

    public function updateDataBatch($table, $data, $title)
    {
        $query = $this->db->update_batch($table, $data, $title);
        return $query;
    }

    public function getCodeMitraByIdEmployee($id_employee)
    {
        $this->db->select('id_employee, email, code');
        $this->db->from('employee');
        $this->db->where_in('id_employee', $id_employee);
        $query = $this->db->get();
        return $query->result();
    }

    public function getDataReferral($code_referral)
    {
        $this->db->select('b.`id_employee`, a.`code_korwil`,a.`code_mitra`,a.`code_referral`,b.`code`,b.`name`');
        $this->db->from('mitra_profile a');
        $this->db->join('employee b', 'a.`code_referral`=b.`code`', 'inner');
        $this->db->where_in('a.code_referral', $code_referral);
        $query = $this->db->get();
        return $query->result();
    }

    public function updateDataBatch2($table, $data, $title)
    {
        $query = $this->db->query($table, $data, $title);
        return $query;
    }  
    public function updateDataBatch3($id_employee, $codeMitra)
    {
        $query = $this->db->query("update `mitra_profile` a inner join `employee` b on a.`code_referral` = b.`code` set a.`code_referral`='".$codeMitra."' where b.`id_employee`='".$id_employee."'");
        return $query;
    } 
}
