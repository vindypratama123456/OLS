<?php
defined('BASEPATH') || exit('No direct script access allowed');

class Mod_product extends CI_Model
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
        return $this->db->get()->result();
    }

    public function getList($table, $select = '*', $where = null, $orderBy = null, $groupBy = null)
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
        return $this->db->get()->row_array();
    }

    public function productAdd($table, $product){
        $sql = $this->db->insert_batch($table, $product);
        return $sql;
    }

    public function Add($table, $product){
        $sql = $this->db->insert_batch($table, $product);
        return $sql;
    }

    public function Delete($table, $data, $where){
        $this->db->where_in($where, $data);
        $this->db->delete($table);
        return $this->db->affected_rows();
    }

    public function getGudangAktif($table, $select= '*', $where)
    {
        $this->db->select($select);
        $this->db->where('status', $where); // Must be id
        
        return $this->db->get($table)->result_array();
    }

    public function getIdProduct($table, $select, $where)
    {
        $this->db->select($select);
        $this->db->where('kode_buku', $where); // Must be id
        
        return $this->db->get($table)->result_array();
    }

    public function getIdPeriodeTerakhir()
    {
        $this->db->select('id, year_start');
        $this->db->from('master_periode');
        $this->db->order_by('id', 'desc');
        $this->db->limit(1);
        return $this->db->get()->row_array();
    }

    public function get_category_product()
    {
        $this->db->select('a.id_category as id_category, concat("[", b.name, "] ", a.name) as name');
        $this->db->from('category a');
        $this->db->join('category b', 'a.id_parent=b.id_category', 'inner');
        $this->db->where('a.active', 1);
        $this->db->where('a.id_parent <>', 0);
        return $this->db->get()->result();
    }

    public function check_category_product($id_product, $id_category)
    {
        $this->db->select('*');
        $this->db->from('category_product');
        $this->db->where('id_product', $id_product);
        $this->db->where('id_category', $id_category);
        return $this->db->get()->num_rows();
    }

    public function get_id_parent_category($id_category)
    {
        $this->db->select('id_parent');
        $this->db->from('category');
        $this->db->where('id_category', $id_category);
        return $this->db->get()->row_array();
    }

}
