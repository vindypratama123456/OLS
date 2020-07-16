<?php
defined('BASEPATH') || exit('No direct script access allowed');

class Mod_general extends CI_Model
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
        return $this->db->get()->num_rows();
    }

    public function checkExist($tableName, $field, $value, $objectField = null, $objectId = null)
    {
        $this->db->where_in("LOWER($field)", strtolower($value));
        if ($objectField != null and $objectId != null) {
            $this->db->where_not_in($objectField, $objectId);
        }
        return $this->db->get($tableName)->num_rows();
    }

    public function edit($table, $data, $where = null)
    {
        $this->db->set($data);
        if ($where) {
            $this->db->where($where);
        }
        $this->db->update($table);
    }

    public function get_list($key, $table, $limit = ['perpage' => 10, 'offset' => 0])
    {
        return $this->db->order_by($key . ' asc')
            ->limit($limit['perpage'], $limit['offset'])
            ->get($table)
            ->result();
    }

    public function getListWhere($key, $table, $where, $limit = ['perpage' => 10, 'offset' => 0])
    {
        return $this->db->where($where)
            ->order_by($key . ' asc')
            ->limit($limit['perpage'], $limit['offset'])
            ->get($table)
            ->result();
    }

    public function getTotalRows($table)
    {
        return $this->db->count_all($table);
    }

    public function getTotalRowsWhere($table, $where)
    {
        $query = $this->db->query("SELECT COUNT(*) AS `total` FROM $table WHERE $where");
        return $query->row('total');
    }

    public function detailData($table, $key = '', $id = '')
    {
        $this->db->where($key, $id);
        $query = $this->db->get($table);
        return $query->row_array();
    }

    public function addData($table, $data)
    {
        if ($this->db->insert($table, $data)) {
            return $this->db->insert_id();
        }
        return false;
    }

    public function addBatch($table, $data)
    {
        $this->db->insert_batch($table, $data);
    }

    public function addDataBatch($table, $data)
    {
        if($this->db->insert_batch($table, $data))
        {
            return true;
        }
        return false;
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

    public function getWhere($table, $where, $id, $key, $order)
    {
        $this->db->where($where, $id);
        $this->db->order_by($key, $order);
        $query = $this->db->get($table);
        if (!empty($query)) {
            return $query->result();
        } else {
            return [];
        }
    }

    public function getEmailCustomer($where, $select = null)
    {
        $this->db->select("orders.id_order, orders.reference, customer.id_customer, customer.email, customer.email_kepsek, customer.email_operator");
        if ($select) {
            $this->db->select($select);
        }
        $this->db->where($where);
        $this->db->join('customer', 'orders.id_customer = customer.id_customer', 'inner');
        return $this->db->get('orders')->result();
    }

    public function getZonaByKabupaten($kabupaten)
    {
        $this->db->select('zona');
        $this->db->where('kabupaten', $kabupaten);
        $query = $this->db->get('master_kabupaten_zona');
        return $query->row('zona');
    }
    
    public function getWarehouseOrder($id_order)
    {
        $this->db->select('c.asal as id_gudang');
        $this->db->where('a.id_order', $id_order);
        $this->db->from('orders a');
        $this->db->join('order_scm b', 'a.id_order = b.id_order', 'inner');
        $this->db->join('transaksi c', 'b.id_order = c.id_pesanan', 'inner');
        return $this->db->get()->result()[0];
    }

    public function getWhereIn($tabel, $field, $key, $data)
    {
        $this->db->select($field);
        $this->db->where_in($key, $data);
        $this->db->from($tabel);
        return $this->db->get()->result_array();
    }
}
