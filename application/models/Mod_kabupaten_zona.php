<?php
defined('BASEPATH') || exit('No direct script access allowed');

class Mod_kabupaten_zona extends CI_Model
{
    public function __construct()
    {
        parent::__construct();
    }

    public function getZona()
    {
    	$this->db->select('id_site, nama_site');
    	// $this->db->from('master_site');
    	$query = $this->db->get('master_site');
    	return $query->result();
    }

    public function getKabupatenZonaDetail($id)
    {
    	$this->db->select('*');
        $this->db->where('id',$id);
        $query = $this->db->get('master_kabupaten_zona');
        return $query->result_array();
    }
    
    public function updateData($table, $data, $key = '', $id = '')
    {
        $this->db->where($key, $id);
        if ($this->db->update($table, $data)) {
            return true;
        }
        return false;
    }
    
    public function deleteData($table, $key = '', $id = '')
    {
        if ($key && $id) {
            $this->db->where($key, $id);
            $this->db->delete($table);
            return true;
        }
        return false;
    }

    public function addData($table, $data)
    {
        if ($this->db->insert($table, $data)) {
            return $this->db->insert_id();
        }
        return false;
    }
}