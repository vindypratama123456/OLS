<?php
defined('BASEPATH') || exit('No direct script access allowed');

class Mod_scm extends CI_Model
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

    public function getRow($table, $where = null)
    {
        $this->db->select('*');
        $this->db->from($table);
        if ($where) {
            $this->db->where($where);
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

    public function edit($id, $data)
    {
        $this->db->set($data);
        $this->db->where('id_transaksi', $id);
        $this->db->update('transaksi');
    }

    public function addTransaksiHistory($idTransaksi, $status)
    {
        $this->db->set('id_employee', $this->session->userdata('adm_id'));
        $this->db->set('date_add', date('Y-m-d H:i:s'));
        $this->db->set('id_transaksi', $idTransaksi);
        $this->db->set('status_transaksi', $status);
        $this->db->insert('transaksi_history');
    }

    public function update($table, $where, $data)
    {
        $this->db->set($data);
        $this->db->where($where);
        $this->db->update($table);
        return $this->db->affected_rows();
    }

    public function updateBatch($table, $data, $key)
    {
        $this->db->update_batch($table, $data, $key);
    }

    public function delete($table, $key, $id, $where = null)
    {
        $this->db->where($key, $id);
        if ($where) {
            $this->db->where($where);    
        }
        $this->db->delete($table);
    }

    public function getStok($idGudang, $idProduk, $select)
    {
        $this->db->select($select);
        $this->db->from('info_gudang');
        $this->db->where('id_gudang', $idGudang);
        $this->db->where('id_produk', $idProduk);
        $this->db->where('periode', $this->periode);
        return $this->db->get()->result()[0];
    }

    public function updateStok($idGudang, $idProduk, $data)
    {
        $this->db->set($data);
        $this->db->where('id_gudang', $idGudang);
        $this->db->where('id_produk', $idProduk);
        $this->db->where('periode', $this->periode);
        $this->db->update('info_gudang');
    }

    public function getListProductStock($id_order, $id_gudang)
    {
        $this->db->select('a.id_order_detail, e.name AS type, e.alias AS type_alias, a.product_id, a.product_name, a.product_quantity, a.quantity_fullfil, a.unit_price, a.total_price, b.reference as isbn, b.kode_buku as kode_buku, b.weight, b.id_category_default as id_category, c.name as kelas, b.capacity as koli, d.stok_booking, d.stok_fisik, d.stok_available');
        $this->db->from('order_detail a');
        $this->db->join('product b', 'b.id_product=a.product_id', 'inner');
        $this->db->join('category c', 'c.id_category=b.id_category_default', 'inner');
        $this->db->join('info_gudang d', 'd.id_produk=a.product_id', 'inner');
        $this->db->join('category e', 'e.id_category=c.id_parent', 'inner');
        $this->db->where('a.id_order', $id_order);
        $this->db->where('d.id_gudang', $id_gudang);
        $this->db->where('d.periode', $this->periode);
        $this->db->order_by('a.id_order_detail asc, c.name asc');
        return $this->db->get()->result();
    }

    public function getListBooks($id_order, $id_gudang, $id_category, $zona)
    {
        $this->db->select('a.id_product AS id_produk, a.kode_buku AS kode_buku, a.reference AS reference, a.name AS judul_buku, a.weight AS berat_buku, a.price_'.$zona.' AS harga_buku, b.stok_fisik as stok_fisik, b.stok_booking AS stok_booking, b.stok_available AS stok_available');
        $this->db->from('product a');
        $this->db->join('info_gudang b', 'b.id_produk=a.id_product', 'inner');
        $this->db->where('b.periode', $this->periode);
        $this->db->where('b.id_gudang', $id_gudang);
        $this->db->where('a.id_category_default', $id_category);
        $this->db->where('a.id_product NOT IN (SELECT x.product_id FROM order_detail x WHERE x.id_order = '.$id_order.')');
        $this->db->where('a.active', 1);
        $this->db->order_by('a.sort_order ASC');
        return $this->db->get()->result();
    }

    public function getListProductByRequestID($id_request, $id_gudang)
    {
        $this->db->select('a.id, a.id_produk, b.name as product_name, a.jumlah as product_quantity, if(tba.jumlah_proses is null, a.jumlah, a.jumlah-tba.jumlah_proses) as jumlah_proses, b.reference as isbn, b.kode_buku as kode_buku, b.weight, c.name as kelas, d.name as type, e.stok_booking, e.stok_fisik, e.stok_available');
        $this->db->from('request_stock_detail a');
        $this->db->join('product b', 'b.id_product=a.id_produk', 'inner');
        $this->db->join('category c', 'c.id_category=b.id_category_default', 'inner');
        $this->db->join('category d', 'd.id_category=c.id_parent', 'inner');
        $this->db->join('info_gudang e', 'e.id_produk=a.id_produk', 'inner');
        $this->db->join('(SELECT id_request, asal, tujuan, `id_produk`, SUM(`jumlah`) AS jumlah_proses FROM `transaksi` xx JOIN `transaksi_detail` yy ON xx.`id_transaksi`=yy.`id_transaksi`  WHERE id_request="'.$id_request.'" GROUP BY id_produk)tba', 'a.`id_produk`=tba.id_produk', 'left');
        $this->db->where('a.id_request', $id_request);
        $this->db->where('e.id_gudang', $id_gudang);
        $this->db->where('e.periode', $this->periode);
        $this->db->order_by('b.id_category_default asc, b.sort_order asc');
        return $this->db->get()->result();
    }

    public function getListTransaksiByRequestID($id_request)
    {
        // $this->db->select('count(`id_request`) as count_id, a.`id_request`, group_concat(if(a.`status_transaksi`="1",concat("<span class=\'label label-default\'>", b.`nama_gudang`," : Dibuat</span>"),if(a.`status_transaksi`="2",CONCAT("<span class=\'label label-warning\'>",b.`nama_gudang`," : Diproses</span>"),if(a.`status_transaksi`="3",CONCAT("<span class=\'label label-warning\'>",b.`nama_gudang`," : Menunggu TAG</span>"),if(a.`status_transaksi`="4",CONCAT("<span class=\'label label-warning\'>",b.`nama_gudang`," : SPK Dibuat</span>"),if(a.`status_transaksi`="5",CONCAT("<span class=\'label label-primary\'>",b.`nama_gudang`," : Dikirim Ekspedisi</span>"),if(a.`status_transaksi`="6",CONCAT("<span class=\'label label-success\'>",b.`nama_gudang`," : Selesai</span>"),CONCAT("<span class=\'label label-danger\'>",b.`nama_gudang`," : Dibatalkan</span>")))))))," ") as status_transaksi');

        $test = base_url(BACKMIN_PATH . '/scmrequeststockpartial/detailRequestStockPerGudang/');
        $this->db->select('COUNT(`id_request`) AS count_id, a.`id_request`, a.`id_transaksi`, GROUP_CONCAT(IF(a.`status_transaksi`="1",CONCAT("<span class=\'label label-default\'>", b.`nama_gudang`," : Dibuat</span>"),IF(a.`status_transaksi`="2",CONCAT("<span class=\'label label-warning\'>",b.`nama_gudang`," : Diproses</span>"),IF(a.`status_transaksi`="3",CONCAT("<span class=\'label label-warning\'>",b.`nama_gudang`," : Menunggu TAG</span>"),IF(a.`status_transaksi`="4",CONCAT("<span class=\'label label-warning\'>",b.`nama_gudang`," : SPK Dibuat</span>"),IF(a.`status_transaksi`="5",CONCAT("<span class=\'label label-primary\'>",b.`nama_gudang`," : Dikirim Ekspedisi</span>"),IF(a.`status_transaksi`="6",CONCAT("<span class=\'label label-success\'>",b.`nama_gudang`," : Selesai</span>"),CONCAT("<span class=\'label label-danger\'>",b.`nama_gudang`," : Dibatalkan</span>")))))))) AS status_transaksi');
        $this->db->from('transaksi a');
        $this->db->join('`master_gudang` b', 'a.`asal`=b.`id_gudang`', 'inner');
        $this->db->where('a.id_request', $id_request);
        $this->db->where('a.`id_request` IS NOT NULL');
        $this->db->group_by('a.`id_request`, a.`id_transaksi`');
        return $this->db->get()->result_array();
    }

    public function get_detail_transaksi($id_transaksi)
    {
        $this->db->select('*');
        $this->db->from('transaksi a');
        $this->db->join('request_stock b', 'a.id_request=b.`id_request`', 'inner');
        $this->db->where('a.id_transaksi', $id_transaksi);
        return $this->db->get()->row_array();
    }

    public function getListProductByTransaksiID($id_transaksi)
    {
        $this->db->select('a.id, a.id_produk, b.name as product_name, a.jumlah as product_quantity, b.reference as isbn, b.kode_buku as kode_buku, c.name as kelas, d.name as type');
        $this->db->from('transaksi_detail a');
        $this->db->join('product b', 'b.id_product=a.id_produk', 'inner');
        $this->db->join('category c', 'c.id_category=b.id_category_default', 'inner');
        $this->db->join('category d', 'd.id_category=c.id_parent', 'inner');
        $this->db->where('a.id_transaksi', $id_transaksi);
        $this->db->order_by('b.id_category_default asc, b.sort_order asc');
        return $this->db->get()->result();
    }

    public function getListLog($id_request)
    {
        return $this->db->where('id_request', $id_request)
            ->order_by('id', 'asc')
            ->get('request_stock_logs')
            ->result();
    }

    public function getTransaksi($idOrder)
    {
        $this->db->select("*");
        $this->db->from('transaksi');
        $this->db->where('id_pesanan', $idOrder);
        $this->db->where('ref_id', null);
        return $this->db->get()->result()[0];
    }

    public function getListGudangTAG($idRef)
    {
        $this->db->select('a.id_transaksi as id_transaksi, c.jumlah as jumlah, b.nama_gudang as nama_gudang, d.kode_buku as kode_buku, d.reference as isbn, d.name as judul_buku, e.name as kelas');
        $this->db->from('transaksi a');
        $this->db->join('master_gudang b', 'a.asal = b.id_gudang', 'inner');
        $this->db->join('transaksi_detail c', 'a.id_transaksi = c.id_transaksi', 'inner');
        $this->db->join('product d', 'c.id_produk = d.id_product', 'inner');
        $this->db->join('category e', 'e.id_category = d.id_category_default', 'inner');
        $this->db->where('a.ref_id', $idRef);
        $this->db->where('a.id_tipe', 2);
        $this->db->order_by('a.id_transaksi ASC, c.id ASC');
        return $this->db->get()->result();
    }

    public function getRecommendedWarehouse($kabupaten)
    {
        $this->db->select('a.*, b.kabupaten');
        $this->db->from('master_gudang a');
        $this->db->join('gudang_kabupaten b', 'b.id_gudang = a.id_gudang', 'inner');
        $this->db->where('b.kabupaten', $kabupaten);
        return $this->db->get()->result()[0];
    }

    public function getNewRecommendedWarehouse($idOrder)
    {
        $this->db->select('b.*');
        $this->db->from('order_scm a');
        $this->db->join('master_gudang b', 'b.id_gudang = a.id_gudang', 'inner');
        $this->db->where('a.id_order', $idOrder);
        return $this->db->get()->result()[0];
    }

    public function getListWarehouse($idGudang, $idProduct)
    {
        $this->db->select('a.id_gudang, a.nama_gudang, c.nama_site, b.stok_available as stok');
        $this->db->from('master_gudang a');
        $this->db->join('info_gudang b', 'b.id_gudang = a.id_gudang', 'inner');
        $this->db->join('master_site c', 'c.id_site = a.id_site', 'inner');
        $this->db->where('a.id_gudang !=', $idGudang);
        $this->db->where('b.id_produk', $idProduct);
        $this->db->where('b.periode', $this->periode);
        $this->db->order_by('b.stok_available desc, c.id_site asc, a.nama_gudang asc');
        return $this->db->get()->result();
    }

    public function getStockGudangByStockType($stockType, $where = null)
    {
        $this->db->select('
            a.id_produk as id_produk,
            b.name AS judul_buku,
            b.kode_buku AS kode_buku,
            c.name AS kelas,
            (SELECT d.'.$stockType.' FROM info_gudang d WHERE d.periode = '.$this->periode.' and d.id_gudang = 1 and d.id_produk = a.id_produk) as gudang_medan,
            (SELECT d.'.$stockType.' FROM info_gudang d WHERE d.periode = '.$this->periode.' and d.id_gudang = 6 and d.id_produk = a.id_produk) as gudang_palmerah,
            (SELECT d.'.$stockType.' FROM info_gudang d WHERE d.periode = '.$this->periode.' and d.id_gudang = 8 and d.id_produk = a.id_produk) as gudang_bawen,
            (SELECT d.'.$stockType.' FROM info_gudang d WHERE d.periode = '.$this->periode.' and d.id_gudang = 11 and d.id_produk = a.id_produk) as gudang_bandung,
            (SELECT d.'.$stockType.' FROM info_gudang d WHERE d.periode = '.$this->periode.' and d.id_gudang = 14 and d.id_produk = a.id_produk) as gudang_cikarang,
            (SELECT d.'.$stockType.' FROM info_gudang d WHERE d.periode = '.$this->periode.' and d.id_gudang = 17 and d.id_produk = a.id_produk) as gudang_surabaya,
            (SELECT d.'.$stockType.' FROM info_gudang d WHERE d.periode = '.$this->periode.' and d.id_gudang = 19 and d.id_produk = a.id_produk) as gudang_gianyar
        ');
        $this->db->from('info_gudang a');
        $this->db->join('product b', 'a.id_produk = b.id_product', 'left');
        $this->db->join('category c', 'b.id_category_default = c.id_category', 'left');
        $this->db->where('a.periode', $this->periode);
        if ($where) {
            $this->db->where($where);
        }
        $this->db->group_by('a.id_produk');
        $this->db->order_by('b.id_category_default ASC, b.sort_order ASC');
        return $this->db->get()->result();
    }

    public function getStockGudangAll($select = null, $where = null)
    {
        $this->db->select('
            a.id_produk as id_produk,
            b.name AS judul_buku,
            b.kode_buku AS kode_buku,
            c.name AS kelas
        ');
        if ($select) {
            $this->db->select($select);
        }
        $this->db->from('info_gudang a');
        $this->db->join('product b', 'a.id_produk = b.id_product', 'left');
        $this->db->join('category c', 'b.id_category_default = c.id_category', 'left');
        $this->db->where('a.periode', $this->periode);
        if ($where) {
            $this->db->where($where);
        }
        $this->db->group_by('a.id_produk');
        $this->db->order_by('b.id_category_default ASC, b.sort_order ASC');
        return $this->db->get()->result();
    }

    public function getReportList($select = "*", $start_date, $end_date, $where = null, $orderBy = null)
    {
        $this->db->select($select);
        $this->db->from("order_detail a");
        $this->db->join("orders b", "b.id_order = a.id_order", "inner");
        $this->db->join("transaksi c", "c.id_pesanan = b.id_order", "right");
        $this->db->join("customer d", "d.id_customer = b.id_customer", "inner");
        $this->db->join("product e", "e.id_product = a.product_id", "inner");
        $this->db->join("category f", "f.id_category = e.id_category_default", "inner");
        $this->db->join("employee_kabupaten_kota g", "g.kabupaten_kota = d.kabupaten", "inner");
        $this->db->join("employee h", "h.id_employee = g.id_employee", "inner");
        $this->db->join("master_gudang i", "i.id_gudang = c.asal", "inner");
        $this->db->where("(b.date_add BETWEEN '".$start_date."' AND '".$end_date."')");
        $this->db->where("c.is_to_school = 1");
        $this->db->where("c.is_forward = 0");
        $this->db->where("b.current_state not in (1,2,4)");
        $this->db->where("d.id_customer <> 9353");
        if ($where) {
            $this->db->where($where);
        }

        if ($orderBy) {
            $this->db->order_by($orderBy);
        } else {
            $this->db->order_by("a.id_order desc");
        }

        return $this->db->get()->result();
    }

    public function getReportPesanan($select = null)
    {
        $this->db->select('
            a.id_produk as id_produk,
            a.id_gudang as id_gudang,
            b.name as judul_buku,
	        b.kode_buku as kode_buku,
            c.name as kelas,
            sum(a.stok_fisik) as stok_fisik,
            (
                select sum(q.jumlah)
                from transaksi_detail q
                inner join transaksi p on q.id_transaksi = p.id_transaksi
                where q.id_produk = a.id_produk and p.status_transaksi < 5 and p.is_to_school = 1 and p.is_forward = 0 and p.asal = a.id_gudang
            ) as stok_booking,
            (
                select sum(z.jumlah)
                from transaksi_detail z
                inner join transaksi y on z.id_transaksi = y.id_transaksi
                where z.id_produk = a.id_produk and y.tujuan = 98 and y.asal = a.id_gudang
            ) as stok_ip,
            (
                select sum(x.jumlah)
                from transaksi_detail x
                inner join transaksi w on x.id_transaksi = w.id_transaksi
                where x.id_produk = a.id_produk and w.status_transaksi >= 5 and w.is_to_school = 1 and w.is_forward = 0 and w.asal = a.id_gudang
            ) as stok_kirim,
            (
                select sum(v.product_quantity)
                from order_detail v
                inner join order_scm u on v.id_order = u.id_order
                inner join customer cus on cus.id_customer = u.id_customer
                inner join gudang_kabupaten guka on guka.kabupaten = cus.kabupaten
                where v.product_id = a.id_produk and u.status = 1 and guka.id_gudang = a.id_gudang
            ) as stok_konfirmasi,
            (
                select sum(t.product_quantity)
                from order_detail t
                inner join orders s on t.id_order = s.id_order
                inner join customer r on s.id_customer = r.id_customer
                inner join gudang_kabupaten guka on guka.kabupaten = r.kabupaten
                where t.product_id = a.id_produk and s.current_state = 3 and s.date_add >= "2017-03-01" and r.jenjang <> "1-6" and guka.id_gudang = a.id_gudang
            ) as stok_konfirmasi_sales
        ');
        if ($select) {
            $this->db->select($select);
        }
        $this->db->from('info_gudang a');
        $this->db->join('product b', 'a.id_produk = b.id_product', 'inner');
        $this->db->join('category c', 'b.id_category_default = c.id_category', 'inner');
        $this->db->where('a.periode', $this->periode);
        $this->db->group_by('a.id_produk, a.id_gudang');
        $this->db->order_by('a.id_gudang, c.id_category asc, b.name asc');
        return $this->db->get()->result();
    }

    public function listInfoStok($idGudang, $where = null)
    {
        $id_gudang = explode(',', $idGudang);

        $this->db->select('
            b.name AS judul_buku, b.kode_buku, sum(a.stok_fisik) as stok_fisik, sum(a.stok_booking) as stok_booking, sum(a.stok_available) as stok_available, c.id_category as category, c.name as category_name, d.id_category as parent_category, d.name as parent_category_name,
            COALESCE(
                (SELECT
                    SUM(z.product_quantity) AS jumlah_buku
                FROM order_detail z
                INNER JOIN order_scm y ON z.id_order = y.id_order
                INNER JOIN orders x ON x.id_order = y.id_order
                WHERE 
                    x.periode >= 2018 AND
                    y.`status` = 1 AND
                    z.product_id = a.id_produk AND
                    y.id_gudang in ('.$idGudang.')
                GROUP BY
                    z.product_id), 0
            ) AS stok_belum_kirim
        ');
        $this->db->from('info_gudang a');
        $this->db->join('product b', 'b.id_product=a.id_produk', 'inner');
        $this->db->join('category c', 'c.id_category=b.id_category_default', 'inner');
        $this->db->join('category d', 'd.id_category=c.id_parent', 'inner');
        $this->db->where('a.periode', $this->periode);
        $this->db->where_in('a.id_gudang', $id_gudang);
        if ($where) {
            $this->db->where($where);
        }
        $this->db->group_by('a.id_produk');
        $this->db->order_by('b.id_category_default ASC, b.sort_order ASC');
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

    public function getGudangPengirimRequestStock($id)
    {
        $this->db->select('a.*, b.nama_gudang');
        $this->db->from('transaksi a');
        $this->db->join('master_gudang b', 'a.asal=b.id_gudang', 'inner');
        $this->db->where('a.id_request', $id);
        return $this->db->get()->result()[0];
    }

    public function getRekapitulasiRequestStockProses($start_date, $end_date, $id_gudang, $order_by) 
    {
        $this->db->select('
            `c`.`id_request` AS `id_request`, 
            `c`.`created_date` AS `tgl_request`, 
            `d`.`kode_buku` AS `kode_buku`, 
            `d`.`name` AS `judul_buku`, 
            `f`.`name` AS `kategori`, 
            `e`.`name` AS `kelas`, 
            `d`.`weight` AS berat,
            `a`.`jumlah` AS `quantity`, 
            (`d`.`weight` * `a`.`jumlah`) AS `berat_total`, 
            `d`.`capacity` AS koli,
            (`a`.`jumlah` DIV `d`.`capacity`) AS `jumlah_per_koli`,
            (`a`.`jumlah` % `d`.`capacity`) AS sisa_koli,
            IF(`c`.`is_tag` = 2, IF(`c`.`is_intan` = 1, \'Request Intan\', \'Pengisian Stok Sendiri\'), \'Transfer Antar Gudang\') AS `request_type`, 
            IF(`c`.`is_tag` = 1 or `c`.`is_intan` = 1, (select `x`.`nama_gudang` from `master_gudang` `x` where `x`.`id_gudang` = `b`.`asal`), "-") AS `gudang_asal`, 
            IF(`c`.`is_intan` = 2, `g`.`nama_gudang`, "-") AS `gudang_tujuan`, 
            (CASE `b`.`status_transaksi` 
                WHEN 1 THEN "Dibuat" 
                WHEN 2 THEN "Diproses" 
                WHEN 3 THEN "Menunggu TAG" 
                WHEN 4 THEN "SPK Dibuat" 
                WHEN 5 THEN "Dikirim Ekspedisi" 
                WHEN 6 THEN "Selesai" 
            END) AS `request_status`, 
            (select `y`.`date_add` from `transaksi_history` `y` where `y`.`id_transaksi` = `b`.`id_transaksi` order by `y`.`date_add` DESC LIMIT 1) AS `tgl_status`,
            if(a.no_oef is null, "-", a.no_oef) as no_oef
        ');
        $this->db->from('transaksi_detail a');
        $this->db->join('transaksi b', 'a.id_transaksi = b.id_transaksi', 'inner');
        $this->db->join('request_stock c', 'b.id_request = c.id_request', 'inner');
        $this->db->join('product d', 'a.id_produk = d.id_product', 'inner');
        $this->db->join('category e', 'd.id_category_default = e.id_category', 'inner');
        $this->db->join('category f', 'e.id_parent = f.id_category', 'inner');
        $this->db->join('master_gudang g', 'c.id_gudang = g.id_gudang', 'inner');
        $this->db->where('c.created_date >= ', $start_date);
        $this->db->where('c.created_date <= ', $end_date);
        $this->db->where('a.`no_oef` is null');
        if ($id_gudang) 
            $this->db->where('c.id_gudang', $id_gudang);
        if ($order_by)
            $this->db->order_by($order_by);
        else
            $this->db->order_by('c.id_request, b.id_transaksi, c.created_date', 'ASC');
        return $this->db->get()->result_array();
    }



    public function getRekapitulasiPemenuhanRequestStock($start_date, $end_date, $id_gudang, $order_by) 
    {
        $this->db->select('
            tba.`id_request`, 
            tba.`created_date` AS `tgl_request`, 
            tba.`id_produk` AS `id_produk`, 
            aa.`kode_buku`, 
            aa.`name` AS `judul_buku`, 
            cc.`name` AS `kategori`, 
            bb.`name` AS `kelas`, 
            aa.`weight` AS `berat`, 
            tba.`jumlah` AS `qty_request`, 
            (`aa`.`weight` * `tba`.`jumlah`) AS `berat_total`, 
            aa.`capacity` AS `koli`, 
            (`tba`.`jumlah` - (`tba`.`jumlah` % `aa`.`capacity`)) / `aa`.`capacity` AS `jumlah_per_koli`, 
            (`tba`.`jumlah` % `aa`.`capacity`) AS `sisa_koli`, 
            IF(`tbb`.`jumlah` IS NULL, 0, `tbb`.`jumlah`) AS `qty_terpenuhi`, 
            `tba`.`jumlah`-(IF(`tbb`.`jumlah` IS NULL, 0, `tbb`.`jumlah`)) AS `sisa_request`,
            g.`nama_gudang` as `gudang_request`,
            (CASE tba.status 
                WHEN 1 THEN "Dibuat" 
                WHEN 2 THEN "Diproses" 
                WHEN 3 THEN "Menunggu TAG" 
                WHEN 4 THEN "Dikirim" 
                WHEN 5 THEN "Diterima" 
                WHEN 6 THEN "Selesai" 
                WHEN 7 THEN "Batal" 
            END) AS `request_status`
        ');
        $this->db->from('
            (
                SELECT a.`id_request`, a.`created_date`, a.`id_gudang`, b.`id_produk`, b.`jumlah`, a.`status`
                FROM `request_stock` a 
                INNER JOIN `request_stock_detail` b ON a.`id_request`=b.`id_request`
                WHERE a.status < 6
            )tba
        ');
        $this->db->join('`product` aa', 'tba.id_produk=aa.`id_product`', 'inner');
        $this->db->join('`category` bb', 'bb.`id_category`=aa.`id_category_default`', 'inner');
        $this->db->join('`category` cc', 'cc.`id_category`=bb.`id_parent`', 'inner');
        $this->db->join('
            (
                select count(a.`id_request`) as hitung, a.`id_request`, b.`id_produk`, sum(b.`jumlah`) as jumlah 
                from `transaksi` a 
                inner join `transaksi_detail` b on a.`id_transaksi`=b.`id_transaksi` 
                where a.`id_request` IS NOT NULL and b.`no_oef` is null 
                group by a.`id_request`, b.`id_produk`
            )tbb
            ', 'tba.id_request=tbb.id_request AND tba.id_produk=tbb.id_produk', 'left');
        $this->db->join('master_gudang g', 'tba.id_gudang = g.id_gudang', 'inner');
        $this->db->where('tba.created_date >= ', $start_date);
        $this->db->where('tba.created_date <= ', $end_date);
        if ($id_gudang) 
            $this->db->where('tba.id_gudang', $id_gudang);
        if ($order_by)
            $this->db->order_by($order_by);
        else
            $this->db->order_by('tba.created_date', 'DESC');
        return $this->db->get()->result_array();
    }

    public function getPeriodeHPP($date)
    {
        $date = explode('-', $date);

        $this->db->select('a.*');
        $this->db->from('master_periode a');
        $this->db->where('a.month_end >=', (int)$date[1]);
        $this->db->where('a.month_start <=', (int)$date[1]);
        $this->db->where('a.year_end >=', (int)$date[0]);
        $this->db->where('a.year_start <=', (int)$date[0]);
        return $this->db->get()->result_array()[0];
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

    public function getDataTransaksi($id_request)
    {
        $this->db->select('SUM(jumlah) AS jumlah');
        $this->db->from('transaksi a');
        $this->db->join('transaksi_detail b', 'a.`id_transaksi`=b.`id_transaksi`', 'inner');
        $this->db->where('id_request', $id_request);
        $this->db->group_by('id_request');
        $q = $this->db->get()->result();
        return $q;
    }

    // public function get_report_transaction($start_date, $end_date, $id_gudang = null, $limit = null, $offset = null)
    public function get_report_transaction($start_date, $end_date)
    {
        $this->db->select("
            IF(a.kode_pesanan IS NOT NULL, a.`kode_pesanan`, IF(a.`id_request` IS NOT NULL, a.`id_request`, a.`id_transaksi`)) AS kode_transaksi,
            a.`created_date` AS tgl_transaksi, CASE WHEN a.`status_transaksi`=1 THEN 'dibuat' WHEN a.`status_transaksi`=2 THEN 'diproses' WHEN a.`status_transaksi`=3 THEN 'menunggu TAG' WHEN a.`status_transaksi`=4 THEN 'SPK Dibuat' WHEN a.`status_transaksi`=5 THEN 'Dikirim Ekspedisi' WHEN a.`status_transaksi`=6 THEN 'Sampai' END AS status_transaksi, IF(a.`id_tipe`=3, 'ADJUSTMENT', IF(a.`id_tipe`=2, IF(b.`no_oef` IS NOT NULL, 'STOK RECEIVING', IF(c.`nama_gudang` IS NULL, 'STOK RECEIVING', IF(a.`kode_pesanan` IS NOT NULL, 'PENGRIMAN KE SEKOLAH', IF(a.`id_request` IS NOT NULL, 'TAG', a.`id_request`)))), 'MASUK')) AS keterangan,
            IF(c.`nama_gudang` IS NOT NULL, c.`nama_gudang`, IF(b.no_oef IS NOT NULL, 'PRODUKSI', a.asal)) AS asal,
            IF(a.kode_pesanan IS NOT NULL, e.school_name, IF(a.id_request IS NOT NULL, IF(d.nama_gudang IS NOT NULL, d.nama_gudang, a.`tujuan`), a.tujuan)) AS tujuan,
            h.`kode_buku` AS kode_buku,
            b.`jumlah` AS qty
        ");
        $this->db->where('DATE(a.created_date) between "'.$start_date.'" AND "'.$end_date.'"');
        // if ($id_gudang)
        // {
        //     $this->db->where('b.id_gudang', $id_gudang);
        // }
        $this->db->from('transaksi a');
        $this->db->join('transaksi_detail b', 'b.`id_transaksi`=a.`id_transaksi`', 'inner');
        $this->db->join('product h', 'h.`id_product`=b.`id_produk`', 'inner');
        $this->db->join('master_gudang c', 'c.`id_gudang`=a.`asal`', 'left');
        $this->db->join('master_gudang d', 'd.`id_gudang`=a.`tujuan`', 'left');
        $this->db->join('customer e', 'e.`id_customer`=a.`tujuan`', 'left');
        $this->db->join('request_stock f', 'f.`id_request`=a.`id_request`', 'left');
        $this->db->join('master_gudang g', 'g.`id_gudang`=f.`id_gudang`', 'left');
        $this->db->order_by('a.`id_transaksi` asc, b.`id_produk` asc');
        // if ($limit && $offset)
        //     $this->db->limit($limit, $offset);
        return $this->db->get()->result_array();
    }
}
