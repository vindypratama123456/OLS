<?php
defined('BASEPATH') || exit('No direct script access allowed');

class Mod_finance extends CI_Model
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

    public function getRowsArray($table, $select = '*', $where = null, $orderBy = null, $groupBy = null, $limit = null)
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
        return $this->db->get()->result_array();
    }

    public function add($table, $data)
    {
        $this->db->insert($table, $data);
        return $this->db->insert_id();
    }

    public function deleteData($table, $where)
    {
        if ($where) {
            $this->db->where($where);
            $this->db->delete($table);
            return true;
        }
        return false;
    }

    public function edit($table, $where, $data)
    {
        $this->db->set($data);
        $this->db->where($where);
        $this->db->update($table);
        if ($this->db->affected_rows() > 0) {
            return true;
        } else {
            return false;
        }
    }

    public function getReportStock($select = null)
    {
        $this->db->select('
            a.id_produk as id_produk,
            b.name as judul_buku,
	        b.kode_buku as kode_buku,
            c.name as kelas,
            sum(a.stok_fisik) as stok_fisik,
            sum(a.stok_booking) as stok_booking,
            (
                select sum(z.jumlah)
                from transaksi_detail z
                inner join transaksi y on z.id_transaksi = y.id_transaksi
                where z.id_produk = a.id_produk and y.tujuan = 98
            ) as stok_ip,
            (
                select sum(x.jumlah)
                from transaksi_detail x
                inner join transaksi w on x.id_transaksi = w.id_transaksi
                where x.id_produk = a.id_produk and w.status_transaksi = 5
            ) as stok_kirim,
            (
                select sum(v.product_quantity)
                from order_detail v
                inner join order_scm u on v.id_order = u.id_order and u.status = 1
                where v.product_id = a.id_produk
                group by v.product_id
            ) as stok_konfirmasi,
            (
                select sum(t.jumlah)
                from transaksi_detail t
                inner join transaksi s on t.id_transaksi = s.id_transaksi
                where t.id_produk = a.id_produk and s.status_transaksi = 6 and s.is_to_school = 1
            ) as stok_diterima_sekolah
        ');
        if ($select) {
            $this->db->select($select);
        }
        $this->db->from('info_gudang a');
        $this->db->join('product b', 'a.id_produk = b.id_product', 'inner');
        $this->db->join('category c', 'b.id_category_default = c.id_category', 'inner');
        $this->db->where('a.periode', $this->periode);
        $this->db->group_by('a.id_produk');
        $this->db->order_by('c.id_category asc, b.name asc');
        return $this->db->get()->result();
    }

    public function getReportRupiah($select = null)
    {
        $this->db->select('
            a.id_produk as id_produk,
            b.name as judul_buku,
	        b.kode_buku as kode_buku,
            c.name as kelas,
            (
                select sum(x.harga)
                from transaksi_detail x
                inner join transaksi w on x.id_transaksi = w.id_transaksi
                where x.id_produk = a.id_produk and w.status_transaksi = 5
            ) as rupiah_kirim,
            (
                select sum(v.total_price)
                from order_detail v
                inner join order_scm u on v.id_order = u.id_order and u.status = 1
                where v.product_id = a.id_produk
                group by v.product_id
            ) as rupiah_konfirmasi,
            (
                select sum(t.harga)
                from transaksi_detail t
                inner join transaksi s on t.id_transaksi = s.id_transaksi
                where t.id_produk = a.id_produk and s.status_transaksi < 5
            ) as rupiah_booking,
            (
                select sum(r.harga)
                from transaksi_detail r
                inner join transaksi q on r.id_transaksi = q.id_transaksi
                where r.id_produk = a.id_produk and q.status_transaksi = 6 and q.is_to_school = 1
            ) as rupiah_diterima_sekolah
        ');
        if ($select) {
            $this->db->select($select);
        }
        $this->db->from('info_gudang a');
        $this->db->join('product b', 'a.id_produk = b.id_product', 'inner');
        $this->db->join('category c', 'b.id_category_default = c.id_category', 'inner');
        $this->db->where('a.periode', $this->periode);
        $this->db->group_by('a.id_produk');
        $this->db->order_by('c.id_category asc, b.name asc');
        return $this->db->get()->result();
    }

    public function editStock($table, $idProduk, $data, $where = null)
    {
        $this->db->set($data);
        $this->db->where('id_produk', $idProduk);
        if ($where) {
            $this->db->where($where);
        }
        $this->db->update($table);
    }

    public function getListLog($idOrder)
    {
        return $this->db->where('id_order', $idOrder)
            ->order_by('id', 'asc')
            ->get('finance_logs')
            ->result();
    }

    public function getListPay($idOrder)
    {
        return $this->db->where('id_order', $idOrder)
            ->order_by('id', 'asc')
            ->get('finance_history')
            ->result();
    }

    public function getTotalPay($idOrder)
    {
        $this->db->select('SUM(`amount`) AS `total`');
        $this->db->from('finance_history');
        $this->db->where('id_order', $idOrder);
        $query = $this->db->get();
        return $query->row('total');
    }

    public function getListProduct($id)
    {
        $this->db->select("d.`reference` AS `isbn`,
                            d.`name` AS `judul`,
                            (CASE WHEN (`c`.jenjang = '1-6') THEN 'SD'
                                  WHEN (`c`.jenjang = '7-9') THEN 'SMP'
                                  ELSE 'SMA/SMK' END) AS `jenjang`,
                            ('Kemdikbud RI') AS `penerbit`,
                            ('-') AS `pengarang`,
                            e.`name` AS `kelas`,
                            a.`product_quantity` AS `qty`,
                            a.`unit_price` AS `harga`,
                            d.`kode_buku` AS `kode_buku`");
        $this->db->from('order_detail a');
        $this->db->join('orders b', 'b.id_order=a.id_order', 'left');
        $this->db->join('customer c', 'c.id_customer=b.id_customer', 'left');
        $this->db->join('product d', 'd.id_product=a.product_id', 'left');
        $this->db->join('category e', 'd.id_category_default=e.id_category', 'left');
        $this->db->where('b.current_state NOT IN (1,2,4)');
        $this->db->where('a.id_order', $id);
        $this->db->order_by('kelas asc, judul asc');
        $q = $this->db->get()->result_array();
        return $q;
    }

    public function totalRupiah($type = 1)
    {
        $field = ($type == 1) ? 'total_paid' : 'nilai_dibayar';
        $this->db->select("sum(`$field`) as total");
        $this->db->from("orders");
        $this->db->where("current_state NOT IN (1, 2, 4)");
        if ($type == 2) {
            $this->db->where("sts_bayar", 2);
        }
        if ($type == 3) {
            $this->db->where("sts_bayar", 1);
        }
        $query = $this->db->get();
        return $query->row('total');
    }

    public function getReportReceiving($start_date, $end_date, $id_gudang = null, $limit = null, $offset = null)
    {
        $this->db->select('
            MONTH(b.tgl_transaksi) as bln_transaksi,
            c.id_periode AS id_periode,
            d.name AS nama_periode,
            b.id_gudang AS id_gudang,
            f.nama_gudang AS nama_gudang,
            e.kode_buku AS kode_buku,
            e.name AS judul_buku,
            SUM(a.jumlah) AS jumlah_buku,
            (select x.hpp from master_hpp x where x.id_gudang = b.id_gudang and x.id_produk = a.id_produk and x.id_periode = c.id_periode) AS unit_cost
        ');
        $this->db->where('b.is_tag', '2');
        $this->db->where('b.is_intan', '2');
        $this->db->where('b.status', '6');
        $this->db->where('(b.tgl_transaksi <> "" OR b.tgl_transaksi <> "0000-00-00")');
        $this->db->where('b.tgl_transaksi between "'.$start_date.'" AND "'.$end_date.'"');
        if ($id_gudang)
            $this->db->where('b.id_gudang', $id_gudang);
        $this->db->from('request_stock_detail a');
        $this->db->join('request_stock b', 'a.id_request = b.id_request', 'inner');
        $this->db->join('report_receiving c', 'b.id_request = c.id_request', 'inner');
        $this->db->join('master_periode d', 'c.id_periode = d.id', 'inner');
        $this->db->join('product e', 'a.id_produk = e.id_product', 'inner');
        $this->db->join('master_gudang f', 'b.id_gudang = f.id_gudang', 'inner');
        $this->db->group_by('c.id_periode, b.id_gudang, MONTH(b.tgl_transaksi), a.id_produk');
        $this->db->order_by('c.id_periode, b.id_gudang, MONTH(b.tgl_transaksi), e.sort_order ASC');
        if ($limit && $offset)
            $this->db->limit($limit, $offset);
        return $this->db->get()->result_array();
    }

    public function getReportStockStatus($month, $year, $id_gudang = null, $limit = null, $offset = null)
    {
        $this->db->select('
            b.kode_buku AS kode_buku,
            b.name AS judul_buku,
            SUM(a.stok_fisik) AS stok_fisik,
            SUM(a.stok_booking) AS stok_booking,
            SUM(a.stok_available) AS stok_available,
            SUM(a.average_cost) AS average_cost,
            SUM(a.total_cost) AS total_cost,
            SUM(a.allocated_cost) AS allocated_cost,
            ROUND(SUM(a.total_cost) / SUM(a.stok_fisik), 2) as average_cost_all
        ');
        if ($id_gudang)
            $this->db->where('a.id_gudang', $id_gudang);
        $this->db->where('a.bulan', $month);
        $this->db->where('a.tahun', $year);
        $this->db->from('report_stock_status a');
        $this->db->join('product b', 'a.id_produk = b.id_product', 'inner');
        $this->db->group_by('a.id_produk');
        $this->db->order_by('b.id_category_default, b.sort_order ASC');
        if ($limit && $offset)
            $this->db->limit($limit, $offset);
        return $this->db->get()->result_array();
    }

    public function getLastStockStatus($now, $id_gudang, $id_produk, $month, $year)
    {
        $this->db->select('a.*');
        $this->db->from('report_stock_status a');
        $this->db->where('a.id_gudang', $id_gudang);
        $this->db->where('a.id_produk', $id_produk);
        if ($now == 1) {
            $this->db->where('a.bulan', (int)$month);
            $this->db->where('a.tahun', (int)$year);
        } else {
            $this->db->where('a.bulan <=', (int)$month);
            $this->db->where('a.tahun <=', (int)$year);
            $this->db->order_by('a.tahun, a.bulan', 'DESC');
            $this->db->limit(1);
        }

        $query  = $this->db->get(); 
        $rows   = $query->num_rows();

        if ($rows > 0) {
            return $query->result_array()[0];
        } else {
            return false;
        }
    }

    public function getReportSalesAnalysis($month, $year, $id_gudang = null, $limit = null, $offset = null)
    {
        $date_month = $year . '-' . sprintf("%02s", $month);

        $where1         = null;
        $where2         = null;
        $where3         = null;
        if ($id_gudang) {
            $where1     = "and x.id_gudang = $id_gudang";
            $where2     = "and z.id_gudang = $id_gudang";
            $where3     = "and o.id_gudang = $id_gudang";
        }

        $this->db->select('
            a.product_id AS id_produk,
            e.kode_buku AS kode_buku,
            e.name AS judul_buku,
            SUM(a.product_quantity) AS qty,
            SUM(a.total_price) AS total_price,
            COALESCE((f.average_cost * SUM(a.product_quantity)), 0) AS cost,
            COALESCE(ROUND((f.total_cost / f.stok_fisik) * SUM(a.product_quantity), 2), 0) AS cost_all,
            g.y_qty AS year_qty,
            g.y_total_price AS year_total_price,
            COALESCE(g.y_cost, 0) AS year_cost,
            COALESCE(g.y_cost_all, 0) AS year_cost_all
        ');
        $this->db->from('order_detail a');
        $this->db->join('orders b', 'a.id_order = b.id_order', 'inner');
        $this->db->join('order_scm c', 'b.id_order = c.id_order', 'inner');
        $this->db->join('finance_history d', 'b.id_order = d.id_order', 'inner');
        $this->db->join('product e', 'a.product_id = e.id_product', 'inner');
        $this->db->join('(SELECT 
                            x.id_produk AS id_produk,
                            x.average_cost AS average_cost,
                            SUM(x.total_cost) AS total_cost,
                            SUM(x.stok_fisik) AS stok_fisik
                        FROM report_stock_status x 
                        WHERE 
                            x.bulan = '.$month.'
                            AND x.tahun = '.$year.'  
                            '.$where1.'
                        GROUP BY x.id_produk) f', 'a.product_id = f.id_produk', 'left');
        $this->db->join('(SELECT 
                            m.product_id AS y_id_produk, 
                            SUM(m.product_quantity) AS y_qty, 
                            SUM(m.total_price) AS y_total_price, 
                            (select q.y_average_cost) * SUM(m.product_quantity) AS y_cost,
                            ROUND(((select q.y_total_cost) / (select q.y_stok_fisik)) * SUM(m.product_quantity), 2) AS y_cost_all
                        FROM order_detail m 
                        INNER JOIN orders n ON m.id_order = n.id_order 
                        INNER JOIN order_scm o ON n.id_order = o.id_order 
                        INNER JOIN finance_history p ON n.id_order = p.id_order
                        LEFT JOIN (SELECT 
                                    z.id_produk AS yy_id_produk,
                                    z.average_cost AS y_average_cost,
                                    SUM(z.total_cost) AS y_total_cost,
                                    SUM(z.stok_fisik) AS y_stok_fisik
                                FROM report_stock_status z 
                                WHERE 
                                    z.bulan <= '.$month.' 
                                    AND z.tahun = '.$year.'
                                    '.$where2.'
                                GROUP BY z.id_produk) q ON m.product_id = q.yy_id_produk
                        WHERE 
                            n.periode = '.$this->periode.'
                            AND n.sts_bayar = 2 
                            AND n.current_state >= 5
                            AND DATE_FORMAT(n.tgl_lunas, "%Y-%m") <= "'.$date_month.'" 
                            '.$where3.'
                        GROUP BY m.product_id) g', 'a.product_id = g.y_id_produk', 'inner');
        if ($id_gudang) 
            $this->db->where('c.id_gudang', $id_gudang);
        $this->db->where('b.periode', $this->periode);
        $this->db->where('b.sts_bayar', 2);
        $this->db->where('b.current_state >= 5');
        $this->db->where('DATE_FORMAT(b.tgl_lunas, "%Y-%m") = "'.$date_month.'"');
        $this->db->group_by('a.product_id');
        $this->db->order_by('e.id_category_default, e.sort_order ASC');
        if ($limit && $offset)
            $this->db->limit($limit, $offset);
        return $this->db->get()->result_array();
    }

    public function getReportSalesAnalysisBeforeSend($month, $year, $id_gudang = null, $limit = null, $offset = null)
    {
        $date_month = $year . '-' . sprintf("%02s", $month);

        $where1         = null;
        $where2         = null;
        $where3         = null;
        if ($id_gudang) {
            $where1     = "and x.id_gudang = $id_gudang";
            $where2     = "and z.id_gudang = $id_gudang";
            $where3     = "and o.id_gudang = $id_gudang";
        }

        $this->db->select('            
            a.product_id AS id_produk,
            e.kode_buku AS kode_buku,
            e.name AS judul_buku,
            SUM(a.product_quantity) AS qty,
            SUM(a.total_price) AS total_price,
            COALESCE((f.average_cost * SUM(a.product_quantity)), 0) AS cost,
            COALESCE(ROUND((f.total_cost / f.stok_fisik) * SUM(a.product_quantity), 2), 0) AS cost_all,
            g.y_qty AS year_qty,
            g.y_total_price AS year_total_price,
            COALESCE(g.y_cost, 0) AS year_cost,
            COALESCE(g.y_cost_all, 0) AS year_cost_all
        ');
        $this->db->from('order_detail a');
        $this->db->join('orders b', 'a.id_order = b.id_order', 'inner');
        $this->db->join('customer cus', 'b.id_customer = cus.id_customer', 'inner');
        $this->db->join('gudang_kabupaten c', 'cus.kabupaten = c.kabupaten', 'inner');
        $this->db->join('finance_history d', 'b.id_order = d.id_order', 'inner');
        $this->db->join('product e', 'a.product_id = e.id_product', 'inner');
        $this->db->join('(SELECT 
                            x.id_produk AS id_produk,
                            x.average_cost AS average_cost,
                            SUM(x.total_cost) AS total_cost,
                            SUM(x.stok_fisik) AS stok_fisik
                        FROM report_stock_status x 
                        WHERE 
                            x.bulan = '.$month.'
                            AND x.tahun = '.$year.'  
                            '.$where1.'
                        GROUP BY x.id_produk) f', 'a.product_id = f.id_produk', 'left');
        $this->db->join('(SELECT 
                            m.product_id AS y_id_produk, 
                            SUM(m.product_quantity) AS y_qty, 
                            SUM(m.total_price) AS y_total_price, 
                            (select q.y_average_cost) * SUM(m.product_quantity) AS y_cost,
                            ROUND(((select q.y_total_cost) / (select q.y_stok_fisik)) * SUM(m.product_quantity), 2) AS y_cost_all
                        FROM order_detail m 
                        INNER JOIN orders n ON m.id_order = n.id_order 
                        INNER JOIN customer cust ON n.id_customer = cust.id_customer
                        INNER JOIN gudang_kabupaten o ON cust.kabupaten = o.kabupaten
                        INNER JOIN finance_history p ON n.id_order = p.id_order
                        LEFT JOIN (SELECT 
                                    z.id_produk AS yy_id_produk,
                                    z.average_cost AS y_average_cost,
                                    SUM(z.total_cost) AS y_total_cost,
                                    SUM(z.stok_fisik) AS y_stok_fisik
                                FROM report_stock_status z 
                                WHERE 
                                    z.bulan <= '.$month.' 
                                    AND z.tahun = '.$year.'
                                    '.$where2.'
                                GROUP BY z.id_produk) q ON m.product_id = q.yy_id_produk
                        WHERE 
                            n.periode = '.$this->periode.'
                            AND n.sts_bayar = 2 
                            AND n.current_state in (1,3,5)
                            AND DATE_FORMAT(n.tgl_lunas, "%Y-%m") <= "'.$date_month.'" 
                            '.$where3.'
                        GROUP BY m.product_id) g', 'a.product_id = g.y_id_produk', 'inner');
        if ($id_gudang) 
            $this->db->where('c.id_gudang', $id_gudang);
        $this->db->where('b.periode', $this->periode);
        $this->db->where('b.sts_bayar', 2);
        $this->db->where('b.current_state in (1,3,5)');
        $this->db->where('DATE_FORMAT(b.tgl_lunas, "%Y-%m") = "'.$date_month.'"');
        $this->db->group_by('a.product_id');
        $this->db->order_by('e.id_category_default, e.sort_order ASC');
        if ($limit && $offset)
            $this->db->limit($limit, $offset);
        return $this->db->get()->result_array();
    }
}
