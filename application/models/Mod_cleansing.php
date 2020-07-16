<?php
defined('BASEPATH') || exit('No direct script access allowed');

class Mod_cleansing extends CI_Model
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

    public function addBatch($table, $data)
    {
        $this->db->insert_batch($table, $data);
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

    public function checkSPKNumber($combine)
    {
        $this->db->select('id_spk');
        $this->db->where('substring(kode_spk, 5, 4) ='.$combine);
        $this->db->from('spk');
        return $this->db->get()->num_rows();
    }

    public function getSPKNumber($combine)
    {
        $this->db->select('kode_spk, substring(kode_spk, 9, 5) as last_number');
        $this->db->where('substring(kode_spk, 5, 4) ='.$combine);
        $this->db->from('spk');
        $this->db->order_by('last_number', 'desc');
        return $this->db->get()->result()[0];
    }

    // TEMPORARY
    public function getSalesInfo($select = null, $where = null)
    {
        $this->db->select("orders.id_order, orders.sales_referer, employee.*");
        if ($select) {
            $this->db->select($select);
        }
        $this->db->from("orders");
        $this->db->join("employee", "orders.sales_referer = employee.email", "inner");
        $this->db->where("orders.date_add >= '2017-03-01' AND orders.current_state NOT IN (1,2,4)");
        if ($where) {
            $this->db->where($where);
        }
        return $this->db->get()->result();
    }

    public function getKorwilInfo($select = null, $where = null)
    {
        $this->db->select("orders.id_order, orders.sales_referer, employee.*");
        if ($select) {
            $this->db->select($select);
        }
        $this->db->from("orders");
        $this->db->join("customer", "orders.id_customer = customer.id_customer", "inner");
        $this->db->join("employee_kabupaten_kota", "customer.kabupaten = employee_kabupaten_kota.kabupaten_kota", "inner");
        $this->db->join("employee", "employee_kabupaten_kota.id_employee = employee.id_employee", "inner");
        $this->db->where("employee.level = 3 AND orders.korwil_email IS NULL AND orders.date_add >= '2017-03-01'");
        if ($where) {
            $this->db->where($where);
        }
        return $this->db->get()->result();
    }
    
    public function getRealStockBooking()
    {
        $this->db->select('
            a.*,
            COALESCE((
                select sum(p.jumlah)
                from transaksi_detail p
                inner join transaksi q on p.id_transaksi = q.id_transaksi
                inner join orders r on r.id_order = q.id_pesanan
                where
                    q.asal = a.id_gudang and p.id_produk = a.id_produk and q.status_transaksi not in (1,5,6) and r.periode = 2018
            ), 0) as booking_1,
            COALESCE((
                select sum(m.jumlah)
                from transaksi_detail m
                inner join transaksi n on m.id_transaksi = n.id_transaksi
                inner join request_stock o on o.id_request = n.id_request
                where
                    n.asal = a.id_gudang and m.id_produk = a.id_produk and n.status_transaksi not in (5,6) and o.periode = 2018
            ), 0) as booking_2,
            (select booking_1) + (select booking_2) as real_booking
        ');
        $this->db->from('info_gudang a');
        $this->db->where('a.periode', $this->periode);
        $this->db->order_by('a.id_gudang, a.id_produk ASC');
        return $this->db->get()->result_array();
    }

    public function getRealStockKirim()
    {
        $this->db->select('
            a.*, 
            COALESCE((
                select sum(p.jumlah)
                from transaksi_detail p
                inner join transaksi q on p.id_transaksi = q.id_transaksi
                inner join orders r on r.id_order = q.id_pesanan
                where
                    q.asal = a.id_gudang and p.id_produk = a.id_produk and q.status_transaksi = 5 and r.periode = 2018 and q.is_to_school = 1
            ), 0) as real_kirim_1,
            COALESCE((
                select sum(m.jumlah)
                from transaksi_detail m
                inner join transaksi n on m.id_transaksi = n.id_transaksi
                inner join request_stock o on o.id_request = n.id_request
                where
                    n.asal = a.id_gudang and m.id_produk = a.id_produk and n.status_transaksi = 5 and o.periode = 2018 and n.is_to_school = 0
            ), 0) as real_kirim_2
        ');
        $this->db->from('info_gudang a');
        $this->db->where('a.periode', $this->periode);
        $this->db->order_by('a.id_gudang, a.id_produk ASC');
        return $this->db->get()->result_array();
    }

    public function getFirstStockStatus()
    {
        $this->db->select('
            a.*,
            COALESCE((
                select sum(p.jumlah)
                from transaksi_detail p
                inner join transaksi q on p.id_transaksi = q.id_transaksi
                inner join orders r on r.id_order = q.id_pesanan
                where q.asal = a.id_gudang 
                    and p.id_produk = a.id_produk 
                    and (q.status_transaksi between 2 and 4) 
                    and r.periode = 2018 
                    and q.is_to_school = 1 
                    and r.sts_bayar = 2 
                    and r.nilai_dibayar >= r.total_paid
            ), 0) as stok_lunas,
            COALESCE((
                select sum(m.jumlah)
                from transaksi_detail m
                inner join transaksi n on m.id_transaksi = n.id_transaksi
                inner join orders o on o.id_order = n.id_pesanan
                where n.asal = a.id_gudang 
                    and m.id_produk = a.id_produk 
                    and n.status_transaksi > 4 
                    and o.periode = 2018 
                    and n.is_to_school = 1 
                    and o.sts_bayar <> 2
            ), 0) as stok_belum_lunas,
            (
                select x.hpp
                from master_hpp x
                where x.id_gudang = a.id_gudang and x.id_produk = a.id_produk and x.id_periode = 4
            ) as hpp
        ');
        $this->db->from('info_gudang a');
        $this->db->where('a.periode', $this->periode);
        $this->db->where('a.id_produk not in (3086,3147)');
        $this->db->order_by('a.id_gudang, a.id_produk ASC');
        return $this->db->get()->result_array();
    }

    public function getStockPaidOffOrder($id_gudang, $id_produk)
    {
        $this->db->select('SUM(a.jumlah) AS jumlah');
        $this->db->from('transaksi_detail a');
        $this->db->join('transaksi b', 'a.id_transaksi = b.id_transaksi', 'inner');
        $this->db->join('orders c', 'b.id_pesanan = c.id_order', 'inner');
        $this->db->where('a.id_produk', $id_produk);
        $this->db->where('b.asal', $id_gudang);
        $this->db->where('b.status_transaksi < 6');
        $this->db->where('c.sts_bayar', 2);
        $this->db->where('c.periode', $this->periode);
        return $this->db->get()->result()[0];
    }

}
