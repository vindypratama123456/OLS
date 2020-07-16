<?php
defined('BASEPATH') || exit('No direct script access allowed');

class Mod_gudang extends CI_Model
{

    public function __construct()
    {
        parent::__construct();
    }

    public function getAll($table, $select = '*', $where = null, $order_by = null)
    {
        $this->db->select($select);
        $this->db->from($table);
        if ($where) {
            $this->db->where($where);
        }
        if ($order_by) {
            $this->db->order_by($order_by);
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

    public function edit($table, $where, $data)
    {
        $this->db->set($data);
        $this->db->where($where);
        $this->db->update($table);
    }

    public function update($table, $where, $data)
    {
        $this->db->set($data);
        $this->db->where($where);
        return $this->db->update($table);
    }

    public function delete($table, $where)
    {
        $this->db->where($where);
        return $this->db->delete($table);
    }

    public function addTransaksiHistory($idTransaksi, $status, $notes = null)
    {
        $this->db->set('id_employee', $this->session->userdata('adm_id'));
        $this->db->set('date_add', date('Y-m-d H:i:s'));
        $this->db->set('id_transaksi', $idTransaksi);
        $this->db->set('status_transaksi', $status);
        if ($notes) {
            $this->db->set('notes', $notes);
        }
        $this->db->insert('transaksi_history');
    }

    public function addOrderHistory($idOrder, $status)
    {
        $this->db->set('id_employee', $this->session->userdata('adm_id'));
        $this->db->set('date_add', date('Y-m-d H:i:s'));
        $this->db->set('id_order', $idOrder);
        $this->db->set('id_order_state', $status);
        $this->db->insert('order_history');
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

    public function getListProducts($id)
    {
        $this->db->select('b.id as id_transaksi_detail, a.id_transaksi as id_transaksi, `b`.`jumlah` as jumlah, `c`.`capacity` as koli, `c`.`weight` as berat, (`b`.`jumlah` * `c`.`weight`) as total_berat, (b.jumlah div c.`capacity`) as total_koli, (`b`.`jumlah` % `c`.`capacity`) as sisa_koli, c.kode_buku as kode_buku, c.reference as isbn, c.name as judul_buku, d.name as kelas, c.id_product as id_product');
        $this->db->from('transaksi a');
        $this->db->join('transaksi_detail b', 'a.id_transaksi = b.id_transaksi', 'inner');
        $this->db->join('product c', 'b.id_produk = c.id_product', 'inner');
        $this->db->join('category d', 'd.id_category = c.id_category_default', 'inner');
        $this->db->where('a.id_transaksi', $id);
        $this->db->order_by('a.id_transaksi ASC, b.id ASC');
        return $this->db->get()->result();
    }

    public function get_status_parsial($id_order, $id_gudang)
    {
        // $this->db->select('a.id AS id, a.id_order AS id_order, a.reference AS reference, b.id_transaksi, c.school_name AS school_name, d.category AS class_name, d.type AS type_name, c.provinsi AS provinsi, c.kabupaten AS kabupaten, a.date_created AS date_add, DATE_FORMAT(ADDDATE(d.tgl_konfirmasi, IF(d.jangka_waktu <> "", d.jangka_waktu, 0)), "%Y-%m-%d") AS target_kirim, group_concat(CASE b.status_transaksi WHEN 1 THEN CONCAT("<span class=\'label label-default\'>",if(isnull(f.`kode_spk`),"Pesanan ",f.`kode_spk`), " : Dibuat</span>") WHEN 2 THEN CONCAT("<span class=\'label label-warning\'>",if(isnull(f.`kode_spk`),"Pesanan ",f.`kode_spk`), " : Diproses</span>") WHEN 3 THEN CONCAT("<span class=\'label label-warning\'>",if(isnull(f.`kode_spk`),"Pesanan ",f.`kode_spk`), " : Menunggu TAG</span>") WHEN 4 THEN CONCAT("<span class=\'label label-warning\'>",if(isnull(f.`kode_spk`),"Pesanan ",f.`kode_spk`), " : SPK Dibuat</span>") WHEN 5 THEN CONCAT("<span class=\'label label-primary\'>",if(isnull(f.`kode_spk`),"Pesanan ",f.`kode_spk`), " : Dikirim Ekspedisi</span>") WHEN 6 THEN CONCAT("<span class=\'label label-success\'>",if(isnull(f.`kode_spk`),"Pesanan ",f.`kode_spk`), " : Telah Sampai</span>") END) AS status_transaksi');
        $url = base_url('backmin/gudangpesanan/detailpesanandiprosesparsial');
        $this->db->select('a.id AS id, a.id_order AS id_order, a.reference AS reference, b.id_transaksi, c.school_name AS school_name, d.category AS class_name, d.type AS type_name, c.provinsi AS provinsi, c.kabupaten AS kabupaten, a.date_created AS date_add, DATE_FORMAT(ADDDATE(d.tgl_konfirmasi, IF(d.jangka_waktu <> "", d.jangka_waktu, 0)), "%Y-%m-%d") AS target_kirim, group_concat(CASE b.status_transaksi WHEN 1 THEN CONCAT("<span class=\'label label-default\'><a href=\''.$url.'/",b.id_transaksi,"\'>",if(isnull(f.`kode_spk`),"Pesanan ",f.`kode_spk`), " : Dibuat</a></span>") WHEN 2 THEN CONCAT("<span class=\'label label-warning\'><a href=\''.$url.'/",b.`id_transaksi`,"\'>",if(isnull(f.`kode_spk`),"Pesanan ",f.`kode_spk`), " : Diproses</a></span>") WHEN 3 THEN CONCAT("<span class=\'label label-warning\'><a href=\''.$url.'/",b.id_transaksi,"\'>",if(isnull(f.`kode_spk`),"Pesanan ",f.`kode_spk`), " : Menunggu TAG</a></span>") WHEN 4 THEN CONCAT("<span class=\'label label-warning\'><a href=\''.$url.'/",b.id_transaksi,"\'>",if(isnull(f.`kode_spk`),"Pesanan ",f.`kode_spk`), " : SPK Dibuat</a></span>") WHEN 5 THEN CONCAT("<span class=\'label label-primary\'><a href=\''.$url.'/",b.id_transaksi,"\'>",if(isnull(f.`kode_spk`),"Pesanan ",f.`kode_spk`), " : Dikirim Ekspedisi</a></span>") WHEN 6 THEN CONCAT("<span class=\'label label-success\'><a href=\''.$url.'/",b.id_transaksi,"\'>",if(isnull(f.`kode_spk`),"Pesanan ",f.`kode_spk`), " : Telah Sampai</a></span>") END) AS status_transaksi');
        $this->db->from('order_scm a');
        $this->db->join('transaksi b', 'b.id_pesanan=a.id_order', 'inner');
        $this->db->join('customer c', 'c.id_customer=a.id_customer', 'inner');
        $this->db->join('orders d', 'd.id_order=a.id_order', 'inner');
        $this->db->join('`spk_detail` e','e.`id_transaksi`=b.`id_transaksi`', 'left');
        $this->db->join('`spk` f','f.`id_spk`=e.`id_spk`', 'left');
        $this->db->where('b.asal', $id_gudang);
        // $this->db->where('a.status >', 1);
        $this->db->where('(b.status_transaksi = 2 OR b.status_transaksi > 3)');
        $this->db->where('d.id_order', $id_order);
        $this->db->group_by('d.reference');
        return $this->db->get()->row_array();
    }

    public function getListProductStock($id_order, $id_gudang)
    {
        $this->db->select('a.id_order_detail, e.name AS type, e.alias AS type_alias, a.product_id, a.product_name, a.product_quantity, a.quantity_fullfil, a.unit_price, a.total_price, b.reference as isbn, b.kode_buku as kode_buku, b.weight, c.name as kelas, b.capacity as koli, d.stok_booking, d.stok_fisik, d.stok_available,if(isnull(tba.id_produk),0,1) as is_process');
        $this->db->from('order_detail a');
        $this->db->join('product b', 'b.id_product=a.product_id', 'inner');
        $this->db->join('category c', 'c.id_category=b.id_category_default', 'inner');
        $this->db->join('info_gudang d', 'd.id_produk=a.product_id', 'inner');
        $this->db->join('category e', 'e.id_category=c.id_parent', 'inner');
        $this->db->join('(SELECT xx.id_produk FROM transaksi_detail xx INNER JOIN transaksi yy ON xx.`id_transaksi`=yy.`id_transaksi` WHERE yy.id_pesanan="'.$id_order.'")tba', 'b.`id_product`=tba.id_produk', 'left');
        $this->db->where('a.id_order', $id_order);
        $this->db->where('d.id_gudang', $id_gudang);
        $this->db->where('d.periode', $this->periode);
        $this->db->order_by('a.id_order_detail asc, c.name asc');
        return $this->db->get()->result();
    }

    public function getListProductStockParsial($id_order, $id_transaksi, $id_gudang)
    {
        $this->db->select('a.id_order_detail, e.name AS type, e.alias AS type_alias, a.product_id, a.product_name, a.product_quantity, a.quantity_fullfil, a.unit_price, a.total_price, b.reference as isbn, b.kode_buku as kode_buku, b.weight, c.name as kelas, b.capacity as koli, d.stok_booking, d.stok_fisik, d.stok_available,if(isnull(tba.id_produk),0,1) as is_process');
        $this->db->from('order_detail a');
        $this->db->join('product b', 'b.id_product=a.product_id', 'inner');
        $this->db->join('category c', 'c.id_category=b.id_category_default', 'inner');
        $this->db->join('info_gudang d', 'd.id_produk=a.product_id', 'inner');
        $this->db->join('category e', 'e.id_category=c.id_parent', 'inner');
        $this->db->join('(SELECT xx.id_produk FROM transaksi_detail xx INNER JOIN transaksi yy ON xx.`id_transaksi`=yy.`id_transaksi` WHERE yy.id_pesanan="'.$id_order.'")tba', 'b.`id_product`=tba.id_produk', 'left');
        $this->db->where('a.id_order', $id_order);
        $this->db->where('d.id_gudang', $id_gudang);
        $this->db->where('d.periode', $this->periode);
        $this->db->where('a.product_id in (select id_produk from transaksi_detail where id_transaksi="'.$id_transaksi.'")');
        $this->db->order_by('a.id_order_detail asc, c.name asc');
        return $this->db->get()->result();
    }

    public function getListProductByRequestID($id_request)
    {
        // $this->db->select('a.id, a.id_produk, b.name as product_name, a.jumlah as product_quantity, b.reference as isbn, b.kode_buku as kode_buku, c.name as kelas, a.no_oef as no_oef, d.name as type, e.stok_booking, e.stok_fisik, e.stok_available');
        $this->db->select('a.id, a.id_produk, b.name as product_name, a.jumlah as product_quantity, (a.jumlah - if(ISNULL(f.jumlah), 0, f.jumlah)) as sisa, b.reference as isbn, b.kode_buku as kode_buku, c.name as kelas, d.name as type, e.stok_booking, e.stok_fisik, e.stok_available');
        $this->db->from('request_stock_detail a');
        $this->db->join('product b', 'b.id_product=a.id_produk', 'inner');
        $this->db->join('category c', 'c.id_category=b.id_category_default', 'inner');
        $this->db->join('category d', 'd.id_category=c.id_parent', 'inner');
        $this->db->join('info_gudang e', 'e.id_produk=a.id_produk', 'inner');
        $this->db->join('(select yy.`id_produk`, sum(yy.`jumlah`) as jumlah from transaksi xx inner join transaksi_detail yy on xx.`id_transaksi`=yy.`id_transaksi` where xx.`id_request`='. $id_request .' group by yy.`id_produk`)f', 'a.`id_produk`=f.id_produk', 'left');
        $this->db->where('a.id_request', $id_request);
        $this->db->where('e.id_gudang', $this->adm_id_gudang);
        $this->db->where('e.periode', $this->periode);
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

    public function getListTransaksiByRequestID($id_request)
    {
        // $this->db->select('count(`id_request`) as count_id, a.`id_request`, group_concat(if(a.`status_transaksi`="1",concat("<span class=\'label label-default\'>", b.`nama_gudang`," : Dibuat</span>"),if(a.`status_transaksi`="2",CONCAT("<span class=\'label label-warning\'>",b.`nama_gudang`," : Diproses</span>"),if(a.`status_transaksi`="3",CONCAT("<span class=\'label label-warning\'>",b.`nama_gudang`," : Menunggu TAG</span>"),if(a.`status_transaksi`="4",CONCAT("<span class=\'label label-warning\'>",b.`nama_gudang`," : SPK Dibuat</span>"),if(a.`status_transaksi`="5",CONCAT("<span class=\'label label-primary\'>",b.`nama_gudang`," : Dikirim Ekspedisi</span>"),if(a.`status_transaksi`="6",CONCAT("<span class=\'label label-success\'>",b.`nama_gudang`," : Selesai</span>"),CONCAT("<span class=\'label label-danger\'>",b.`nama_gudang`," : Dibatalkan</span>")))))))," ") as status_transaksi');
        $test2 = '<a href="'.base_url(BACKMIN_PATH . '/gudangrequeststockpartial/detailRequestStockPerGudang/');

        $test = base_url(BACKMIN_PATH . '/gudangrequeststockpartial/detailRequestStockPerGudang/');
        // $this->db->select('COUNT(`id_request`) AS count_id, a.`id_request`, GROUP_CONCAT(CONCAT("<a href=\"","'.$test.'",a.`id_transaksi`,"\">"),IF(a.`status_transaksi`="1",CONCAT("<span class=\'label label-default\'>", b.`nama_gudang`," : Dibuat</span>"),IF(a.`status_transaksi`="2",CONCAT("<span class=\'label label-warning\'>",b.`nama_gudang`," : Diproses</span>"),IF(a.`status_transaksi`="3",CONCAT("<span class=\'label label-warning\'>",b.`nama_gudang`," : Menunggu TAG</span>"),IF(a.`status_transaksi`="4",CONCAT("<span class=\'label label-warning\'>",b.`nama_gudang`," : SPK Dibuat</span>"),IF(a.`status_transaksi`="5",CONCAT("<span class=\'label label-primary\'>",b.`nama_gudang`," : Dikirim Ekspedisi</span>"),IF(a.`status_transaksi`="6",CONCAT("<span class=\'label label-success\'>",b.`nama_gudang`," : Selesai</span>"),CONCAT("<span class=\'label label-danger\'>",b.`nama_gudang`," : Dibatalkan</span>"))))))),"</a> ") AS status_transaksi');
        $this->db->select('COUNT(`id_request`) AS count_id, a.`id_request`, a.`id_transaksi`, GROUP_CONCAT(IF(a.`status_transaksi`="1",CONCAT("<span class=\'label label-default\'>", b.`nama_gudang`," : Dibuat</span>"),IF(a.`status_transaksi`="2",CONCAT("<span class=\'label label-warning\'>",b.`nama_gudang`," : Diproses</span>"),IF(a.`status_transaksi`="3",CONCAT("<span class=\'label label-warning\'>",b.`nama_gudang`," : Menunggu TAG</span>"),IF(a.`status_transaksi`="4",CONCAT("<span class=\'label label-warning\'>",b.`nama_gudang`," : SPK Dibuat</span>"),IF(a.`status_transaksi`="5",CONCAT("<span class=\'label label-primary\'>",b.`nama_gudang`," : Dikirim Ekspedisi</span>"),IF(a.`status_transaksi`="6",CONCAT("<span class=\'label label-success\'>",b.`nama_gudang`," : Selesai</span>"),CONCAT("<span class=\'label label-danger\'>",b.`nama_gudang`," : Dibatalkan</span>")))))))) AS status_transaksi');
        $this->db->from('transaksi a');
        $this->db->join('`master_gudang` b', 'a.`asal`=b.`id_gudang`', 'inner');
        $this->db->where('a.id_request', $id_request);
        $this->db->where('a.`id_request` IS NOT NULL');
        $this->db->group_by('a.`id_request`, a.`id_transaksi`');
        return $this->db->get()->result_array();
    }

    public function getTransaksiDetailByRequestID($id_request)
    {
        // $this->db->select('count(`id_request`) as count_id, a.`id_request`, group_concat(if(a.`status_transaksi`="1",concat("<span class=\'label label-default\'>", b.`nama_gudang`," : Dibuat</span>"),if(a.`status_transaksi`="2",CONCAT("<span class=\'label label-warning\'>",b.`nama_gudang`," : Diproses</span>"),if(a.`status_transaksi`="3",CONCAT("<span class=\'label label-warning\'>",b.`nama_gudang`," : Menunggu TAG</span>"),if(a.`status_transaksi`="4",CONCAT("<span class=\'label label-warning\'>",b.`nama_gudang`," : SPK Dibuat</span>"),if(a.`status_transaksi`="5",CONCAT("<span class=\'label label-primary\'>",b.`nama_gudang`," : Dikirim Ekspedisi</span>"),if(a.`status_transaksi`="6",CONCAT("<span class=\'label label-success\'>",b.`nama_gudang`," : Selesai</span>"),CONCAT("<span class=\'label label-danger\'>",b.`nama_gudang`," : Dibatalkan</span>")))))))," ") as status_transaksi');

        $test = base_url(BACKMIN_PATH . '/gudangrequeststockpartial/detailRequestStockPerGudang/');
        $this->db->select('a.`id_transaksi`,a.`id_request`, a.`status_transaksi`');
        $this->db->from('transaksi a');
        $this->db->join('`master_gudang` b', 'a.`asal`=b.`id_gudang`', 'inner');
        $this->db->where('a.id_request', $id_request);
        $this->db->where('a.`id_request` IS NOT NULL');
        // $this->db->group_by('a.`id_request`');
        return $this->db->get()->result_array();
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

    public function get_detail_transaksi($id_transaksi)
    {
        $this->db->select('*');
        $this->db->from('transaksi a');
        $this->db->join('request_stock b', 'a.id_request=b.`id_request`', 'inner');
        $this->db->where('a.id_transaksi', $id_transaksi);
        return $this->db->get()->row_array();
    }

    public function getTransaksi($idOrder, $idGudang)
    {
        $this->db->select("*");
        $this->db->from('transaksi');
        $this->db->where('id_pesanan', $idOrder);
        $this->db->where('asal', $idGudang);
        $this->db->where('ref_id', null);
        return $this->db->get()->result()[0];
    }

    public function getTransaksiParsial($idTransaksi, $idGudang)
    {
        $this->db->select("*");
        $this->db->from('transaksi');
        $this->db->where('id_transaksi', $idTransaksi);
        $this->db->where('asal', $idGudang);
        $this->db->where('ref_id', null);
        return $this->db->get()->result()[0];
    }

    public function getListGudangTAG($idRef)
    {
        $this->db->select('a.id_transaksi as id_transaksi, a.status_transaksi as status_transaksi, c.jumlah as jumlah, b.nama_gudang as nama_gudang, d.kode_buku as kode_buku, d.reference as isbn, d.name as judul_buku, e.name as kelas');
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

    public function checkStatusTAG($idRef)
    {
        $this->db->select('status_transaksi');
        $this->db->from('transaksi');
        $this->db->where('ref_id', $idRef);
        return $this->db->get()->result();
    }

    public function getAllEkspeditur()
    {
        $this->db->select();
        $this->db->from('ekspeditur');
        return $this->db->get()->result();
    }

    public function getListTransaksiBySPK($id)
    {
        // $this->db->select('a.id_spk, b.id_transaksi, b.jumlah, b.berat, c.status_transaksi as status_transaksi, c.is_to_school, if(c.is_to_school=1, d.school_name, e.nama_gudang) AS tujuan, if(c.is_to_school=1, CONCAT(if(isnull(d.alamat),"",d.alamat),", ",if(isnull(d.desa),"",d.desa),", ",if(isnull(d.kecamatan),"",d.kecamatan),", ",if(isnull(d.kabupaten),"",d.kabupaten),", ",if(isnull(d.provinsi),"",d.provinsi)," - ",if(isnull(d.kodepos),"",d.kodepos), \', Kepala sekolah : \', if(isnull(d.name) or d.name="","-",d.name), " ( ", if(isnull(d.phone),"-",d.phone), " )"), e.alamat_gudang) AS alamat, if(c.is_to_school=1, c.id_pesanan, c.id_request) AS detail_id, if(c.is_to_school=1, c.kode_pesanan, c.id_request) AS detail_kode, c.id_pesanan, f.`kirim_parsial_request_by_id`, f.`reference_other`, f.`reference_other_from`');
        $this->db->select('a.id_spk, b.id_transaksi, b.jumlah as jumlah_x, tbla.jumlah as jumlah, b.berat as berat_x, tbla.total_berat as berat, c.status_transaksi as status_transaksi, c.is_to_school, if(c.is_to_school=1, d.school_name, e.nama_gudang) AS tujuan, if(c.is_to_school=1, CONCAT(if(isnull(d.alamat),"",d.alamat),", ",if(isnull(d.desa),"",d.desa),", ",if(isnull(d.kecamatan),"",d.kecamatan),", ",if(isnull(d.kabupaten),"",d.kabupaten),", ",if(isnull(d.provinsi),"",d.provinsi)," - ",if(isnull(d.kodepos),"",d.kodepos), \', Kepala sekolah : \', if(isnull(d.name) or d.name="","-",d.name), " ( ", if(isnull(d.phone),"-",d.phone), " )"), e.alamat_gudang) AS alamat, if(c.is_to_school=1, c.id_pesanan, c.id_request) AS detail_id, if(c.is_to_school=1, c.kode_pesanan, c.id_request) AS detail_kode, c.id_pesanan, f.`kirim_parsial_request_by_id`, f.`reference_other`, f.`reference_other_from`');
        $this->db->from('spk a');
        $this->db->join('spk_detail b', 'b.id_spk = a.id_spk', 'inner');
        $this->db->join('transaksi c', 'c.id_transaksi = b.id_transaksi', 'inner');
        $this->db->join('(SELECT b.`id_transaksi` AS id_transaksi, SUM(`b`.`jumlah`) AS jumlah, `c`.`capacity` AS koli, `c`.`weight` AS berat, SUM(`b`.`jumlah` * `c`.`weight`) AS total_berat, (b.jumlah DIV c.`capacity`) AS total_koli, (`b`.`jumlah` % `c`.`capacity`) AS sisa_koli, c.kode_buku AS kode_buku, c.reference AS isbn, c.name AS judul_buku, d.name AS kelas, c.id_product AS id_product 
            FROM transaksi_detail b 
            INNER JOIN product c ON b.id_produk = c.id_product
            INNER JOIN category d ON d.id_category = c.id_category_default
            GROUP BY b.`id_transaksi`)tbla', 'tbla.id_transaksi=c.`id_transaksi`', 'inner');
        $this->db->join('customer d', 'd.id_customer = c.tujuan', 'left');
        $this->db->join('master_gudang e', 'e.id_gudang = c.tujuan', 'left');
        $this->db->join('orders f','f.id_order=c.id_pesanan','left');
        $this->db->where('a.id_spk', $id);
        return $this->db->get()->result();
    }

    public function getListTransaksiBySPK_TAG($id)
    {
        // $this->db->select('e.`kode_buku` as kode_buku, e.`name` as judul_buku, e.`weight` as berat, e.`capacity` as koli, SUM(d.`jumlah`) AS jumlah, (sum(d.`jumlah`)*e.`weight`) as total_berat, round(SUM(d.`jumlah`)/e.`capacity`,0) as jumlah_per_koli, SUM(d.`jumlah`)%e.`capacity` as jumlah_sisa');
        $this->db->select('e.`kode_buku` as kode_buku, e.`name` as judul_buku, round((d.`berat` / d.`jumlah`),2) as berat, e.`capacity` as koli, SUM(d.`jumlah`) AS jumlah, (sum(d.`jumlah`) * e.`weight`) as total_berat_temp, sum(d.berat) as total_berat, round(SUM(d.`jumlah`) / e.`capacity`,0) as jumlah_per_koli, SUM(d.`jumlah`) % e.`capacity` as jumlah_sisa');
        $this->db->from('spk a');
        $this->db->join('spk_detail b', 'b.id_spk = a.id_spk', 'inner');
        $this->db->join('transaksi c', 'c.id_transaksi = b.id_transaksi', 'inner');
        $this->db->join('transaksi_detail d', 'd.id_transaksi = c.id_transaksi', 'inner');
        $this->db->join('product e', 'e.`id_product` = d.`id_produk`', 'inner');
        $this->db->join('customer f', 'f.id_customer = c.tujuan', 'left');
        $this->db->join('master_gudang g', 'g.id_gudang = c.tujuan', 'left');
        $this->db->group_by('e.id_product');
        $this->db->where('a.id_spk', $id);
        return $this->db->get()->result();
    }

    public function checkStatusPengiriman($idSPK)
    {
        $this->db->select('id');
        $this->db->where('id_spk', $idSPK);
        $this->db->where('status !=', 4);
        $this->db->from('spk_detail');
        return $this->db->get()->num_rows();
    }

    public function checkStatusTransaksi($id_request)
    {
        $this->db->select('id_transaksi');
        $this->db->where('id_request', $id_request);
        $this->db->where('status_transaksi !=', 6);
        $this->db->from('transaksi');
        return $this->db->get()->num_rows();
    }

    public function getBookLevel($jenjang)
    {
        $in_category = [];
        switch ($jenjang) {
            case '1-6':
                $in_category = explode(',', getenv('K13_SD'));
                break;
            case '7-9':
                $in_category = explode(',', getenv('K13_SMP'));
                break;
            case '10-12':
                $in_category = explode(',', getenv('K13_SMA'));
                break;
        }

        $qRawQuery = "
				SELECT 	`o`.`id_product` AS `id_product`,
						`o`.`kode_buku` AS `kode_buku`,
						`o`.`reference` AS `isbn`,
						`o`.`name` AS `judul`,
						ROUND(`o`.`price_1`) AS `harga`,
						`p`.`name` AS `kelas`,
						`o`.`weight` AS `weight`,
                        `p`.`id_category` AS `category_id`, 
                        `q`.`name` AS type,
                        `q`.`id_category` AS type_id
				FROM 	`product` `o`
				JOIN 	`category` `p` ON `p`.`id_category`=`o`.`id_category_default`
                JOIN    `category` `q` ON `q`.`id_category`=`p`.`id_parent`
				WHERE 	1
                AND     `o`.`active` = ?
				AND 	`o`.`id_product` IN (
					SELECT `a`.`id_product`
					FROM `category_product` `a`
					WHERE 1
					AND `a`.`id_category` = ?
					AND `a`.`id_product` IN (
					    SELECT `aa`.`id_product`
					    FROM `category_product` `aa`
					    INNER JOIN `category` `bb` ON (`aa`.`id_category` = `bb`.`id_category`)
					    WHERE 1
					    AND `bb`.`id_category` IN ?
					)
				)
				AND `o`.`kode_buku` IS NOT NULL
				ORDER BY `o`.`id_category_default` ASC, `o`.`sort_order` ASC";
        $qBookLevel = $this->db->query($qRawQuery, [1, 3, $in_category]);
        if ($qBookLevel) {
            return $qBookLevel->result();
        } else {
            return $this->db->error();
        }
    }

    public function getBookLevelKTSP($jenjang)
    {
        $in_category = [];
        switch ($jenjang) {
            case '1-6':
                $in_category = explode(',', getenv('K13_SD'));
                break;
            case '7-9':
                $in_category = explode(',', getenv('K13_SMP'));
                break;
            case '10-12':
                $in_category = explode(',', getenv('K13_SMA'));
                break;
        }

        $qRaw = "
				SELECT 	`o`.`id_product` AS `id_product`,
						`o`.`kode_buku` AS `kode_buku`,
						`o`.`reference` AS `isbn`,
						`o`.`name` AS `judul`,
						ROUND(`o`.`price_1`) AS `harga`,
						`p`.`name` AS `kelas`,
						`o`.`weight` AS `weight`,
                        `p`.`id_category` AS `category_id`, 
                        `q`.`name` AS type,
                        `q`.`id_category` AS type_id
				FROM 	`product` `o`
				JOIN 	`category` `p` ON `p`.`id_category`=`o`.`id_category_default`
                JOIN    `category` `q` ON `q`.`id_category`=`p`.`id_parent`
				WHERE 	1
                AND     `o`.`active` = ?
				AND 	`o`.`id_product` IN (
					SELECT `a`.`id_product`
					FROM `category_product` `a`
					WHERE 1
					AND `a`.`id_category` = ?
					AND `a`.`id_product` IN (
					    SELECT `aa`.`id_product`
					    FROM `category_product` `aa`
					    INNER JOIN `category` `bb` ON (`aa`.`id_category` = `bb`.`id_category`)
					    WHERE 1
					    AND `bb`.`id_category` IN ?
					)
				)
				AND `o`.`kode_buku` IS NOT NULL
				ORDER BY `o`.`id_category_default` ASC, `o`.`sort_order` ASC";
        $qBookLevelKTSP = $this->db->query($qRaw, [1, 2, $in_category]);
        if ($qBookLevelKTSP) {
            return $qBookLevelKTSP->result();
        } else {
            return $this->db->error();
        }
    }

    public function getBookLevelSMK($jenjang)
    {
        $in_category = [];
        switch ($jenjang) {
            case '10-12':
                $in_category = explode(',', getenv('MINAT_SMK'));
                break;
        }
        $queryRaw = "
				SELECT 	`o`.`id_product` AS `id_product`,
						`o`.`kode_buku` AS `kode_buku`,
						`o`.`reference` AS `isbn`,
						`o`.`name` AS `judul`,
						ROUND(`o`.`price_1`) AS `harga`,
						`p`.`name` AS `kelas`,
						`o`.`weight` AS `weight`,
                        `p`.`id_category` AS `category_id`, 
                        `q`.`name` AS type,
                        `q`.`id_category` AS type_id
				FROM 	`product` `o`
				JOIN 	`category` `p` ON `p`.`id_category`=`o`.`id_category_default`
                JOIN    `category` `q` ON `q`.`id_category`=`p`.`id_parent`
				WHERE 	1
                AND     `o`.`active` = ?
				AND 	`o`.`id_product` IN (
					SELECT `a`.`id_product`
					FROM `category_product` `a`
					WHERE 1
					AND `a`.`id_category` = ?
					AND `a`.`id_product` IN (
					    SELECT `aa`.`id_product`
					    FROM `category_product` `aa`
					    INNER JOIN `category` `bb` ON (`aa`.`id_category` = `bb`.`id_category`)
					    WHERE 1
					    AND `bb`.`id_category` IN ?
					)
				)
				AND `o`.`kode_buku` IS NOT NULL
				ORDER BY `o`.`id_category_default` ASC, `o`.`sort_order` ASC";
        $qBookLevelSMK = $this->db->query($queryRaw, [1, 50, $in_category]);
        if ($qBookLevelSMK) {
            return $qBookLevelSMK->result();
        } else {
            return $this->db->error();
        }
    }

    public function getBookLiterasi()
    {
        // $in_category = [];
        // switch ($jenjang) {
        //     case '10-12':
        //         $in_category = [51, 52, 53];
        //         break;
        // }
        $inCategory = explode(',', getenv('LITERASI'));

        $queryRaw = "
                SELECT  `o`.`id_product` AS `id_product`,
                        `o`.`kode_buku` AS `kode_buku`,
                        `o`.`reference` AS `isbn`,
                        `o`.`name` AS `judul`,
                        ROUND(`o`.`price_1`) AS `harga`,
                        `p`.`name` AS `kelas`,
                        `o`.`weight` AS `weight`,
                        `p`.`id_category` AS `category_id`, 
                        `q`.`name` AS type,
                        `q`.`id_category` AS type_id
                FROM    `product` `o`
                JOIN    `category` `p` ON `p`.`id_category`=`o`.`id_category_default`
                JOIN    `category` `q` ON `q`.`id_category`=`p`.`id_parent`
                WHERE   1
                AND     `o`.`active` = ?
                AND     `o`.`id_product` IN (
                    SELECT `a`.`id_product`
                    FROM `category_product` `a`
                    WHERE 1
                    AND `a`.`id_category` = ?
                    AND `a`.`id_product` IN (
                        SELECT `aa`.`id_product`
                        FROM `category_product` `aa`
                        INNER JOIN `category` `bb` ON (`aa`.`id_category` = `bb`.`id_category`)
                        WHERE 1
                        AND `bb`.`id_category` IN ?
                    )
                )
                AND `o`.`kode_buku` IS NOT NULL
                ORDER BY `o`.`id_category_default` ASC, `o`.`sort_order` ASC";
        $qBookLevelSMK = $this->db->query($queryRaw, [1, getenv('PARENT_LITERASI'), $inCategory]);
        if ($qBookLevelSMK) {
            return $qBookLevelSMK->result();
        } else {
            return $this->db->error();
        }
    }

    public function getBookPengayaan()
    {
        // $in_category = [];
        // switch ($jenjang) {
        //     case '10-12':
        //         $in_category = [51, 52, 53];
        //         break;
        // }
        $inCategory = explode(',', getenv('PENGAYAAN'));

        $queryRaw = "
                SELECT  `o`.`id_product` AS `id_product`,
                        `o`.`kode_buku` AS `kode_buku`,
                        `o`.`reference` AS `isbn`,
                        `o`.`name` AS `judul`,
                        ROUND(`o`.`price_1`) AS `harga`,
                        `p`.`name` AS `kelas`,
                        `o`.`weight` AS `weight`,
                        `p`.`id_category` AS `category_id`, 
                        `q`.`name` AS type,
                        `q`.`id_category` AS type_id
                FROM    `product` `o`
                JOIN    `category` `p` ON `p`.`id_category`=`o`.`id_category_default`
                JOIN    `category` `q` ON `q`.`id_category`=`p`.`id_parent`
                WHERE   1
                AND     `o`.`active` = ?
                AND     `o`.`id_product` IN (
                    SELECT `a`.`id_product`
                    FROM `category_product` `a`
                    WHERE 1
                    AND `a`.`id_category` = ?
                    AND `a`.`id_product` IN (
                        SELECT `aa`.`id_product`
                        FROM `category_product` `aa`
                        INNER JOIN `category` `bb` ON (`aa`.`id_category` = `bb`.`id_category`)
                        WHERE 1
                        AND `bb`.`id_category` IN ?
                    )
                )
                AND `o`.`kode_buku` IS NOT NULL
                ORDER BY `o`.`id_category_default` ASC, `o`.`sort_order` ASC";
        $qBookLevelSMK = $this->db->query($queryRaw, [1, getenv('PARENT_PENGAYAAN'), $inCategory]);
        if ($qBookLevelSMK) {
            return $qBookLevelSMK->result();
        } else {
            return $this->db->error();
        }
    }

    public function getBookReferensi()
    {
        // $in_category = [];
        // switch ($jenjang) {
        //     case '10-12':
        //         $in_category = [51, 52, 53];
        //         break;
        // }
        $inCategory = explode(',', getenv('REFERENSI'));

        $queryRaw = "
                SELECT  `o`.`id_product` AS `id_product`,
                        `o`.`kode_buku` AS `kode_buku`,
                        `o`.`reference` AS `isbn`,
                        `o`.`name` AS `judul`,
                        ROUND(`o`.`price_1`) AS `harga`,
                        `p`.`name` AS `kelas`,
                        `o`.`weight` AS `weight`,
                        `p`.`id_category` AS `category_id`, 
                        `q`.`name` AS type,
                        `q`.`id_category` AS type_id
                FROM    `product` `o`
                JOIN    `category` `p` ON `p`.`id_category`=`o`.`id_category_default`
                JOIN    `category` `q` ON `q`.`id_category`=`p`.`id_parent`
                WHERE   1
                AND     `o`.`active` = ?
                AND     `o`.`id_product` IN (
                    SELECT `a`.`id_product`
                    FROM `category_product` `a`
                    WHERE 1
                    AND `a`.`id_category` = ?
                    AND `a`.`id_product` IN (
                        SELECT `aa`.`id_product`
                        FROM `category_product` `aa`
                        INNER JOIN `category` `bb` ON (`aa`.`id_category` = `bb`.`id_category`)
                        WHERE 1
                        AND `bb`.`id_category` IN ?
                    )
                )
                AND `o`.`kode_buku` IS NOT NULL
                ORDER BY `o`.`id_category_default` ASC, `o`.`sort_order` ASC";
        $qBookLevelSMK = $this->db->query($queryRaw, [1, getenv('PARENT_REFERENSI'), $inCategory]);
        if ($qBookLevelSMK) {
            return $qBookLevelSMK->result();
        } else {
            return $this->db->error();
        }
    }

    public function getBookPandik()
    {
        $inCategory = explode(',', getenv('PANDIK'));

        $queryRaw = "
                SELECT  `o`.`id_product` AS `id_product`,
                        `o`.`kode_buku` AS `kode_buku`,
                        `o`.`reference` AS `isbn`,
                        `o`.`name` AS `judul`,
                        ROUND(`o`.`price_1`) AS `harga`,
                        `p`.`name` AS `kelas`,
                        `o`.`weight` AS `weight`,
                        `p`.`id_category` AS `category_id`, 
                        `q`.`name` AS type,
                        `q`.`id_category` AS type_id
                FROM    `product` `o`
                JOIN    `category` `p` ON `p`.`id_category`=`o`.`id_category_default`
                JOIN    `category` `q` ON `q`.`id_category`=`p`.`id_parent`
                WHERE   1
                AND     `o`.`active` = ?
                AND     `o`.`id_product` IN (
                    SELECT `a`.`id_product`
                    FROM `category_product` `a`
                    WHERE 1
                    AND `a`.`id_category` = ?
                    AND `a`.`id_product` IN (
                        SELECT `aa`.`id_product`
                        FROM `category_product` `aa`
                        INNER JOIN `category` `bb` ON (`aa`.`id_category` = `bb`.`id_category`)
                        WHERE 1
                        AND `bb`.`id_category` IN ?
                    )
                )
                AND `o`.`kode_buku` IS NOT NULL
                ORDER BY `o`.`id_category_default` ASC, `o`.`sort_order` ASC";
        $qBookLevelSMK = $this->db->query($queryRaw, [1, getenv('PARENT_PANDIK'), $inCategory]);
        if ($qBookLevelSMK) {
            return $qBookLevelSMK->result();
        } else {
            return $this->db->error();
        }
    }

    public function getProductIt()
    {
        $inCategory = explode(',', getenv('PRODUCT_IT'));

        $queryRaw = "
                SELECT  `o`.`id_product` AS `id_product`,
                        `o`.`kode_buku` AS `kode_buku`,
                        `o`.`reference` AS `isbn`,
                        `o`.`name` AS `judul`,
                        ROUND(`o`.`price_1`) AS `harga`,
                        `p`.`name` AS `kelas`,
                        `o`.`weight` AS `weight`,
                        `p`.`id_category` AS `category_id`, 
                        `q`.`name` AS type,
                        `q`.`id_category` AS type_id
                FROM    `product` `o`
                JOIN    `category` `p` ON `p`.`id_category`=`o`.`id_category_default`
                JOIN    `category` `q` ON `q`.`id_category`=`p`.`id_parent`
                WHERE   1
                AND     `o`.`active` = ?
                AND     `o`.`id_product` IN (
                    SELECT `a`.`id_product`
                    FROM `category_product` `a`
                    WHERE 1
                    AND `a`.`id_category` = ?
                    AND `a`.`id_product` IN (
                        SELECT `aa`.`id_product`
                        FROM `category_product` `aa`
                        INNER JOIN `category` `bb` ON (`aa`.`id_category` = `bb`.`id_category`)
                        WHERE 1
                        AND `bb`.`id_category` IN ?
                    )
                )
                AND `o`.`kode_buku` IS NOT NULL
                ORDER BY `o`.`id_category_default` ASC, `o`.`sort_order` ASC";
        $qBookLevelSMK = $this->db->query($queryRaw, [1, getenv('PARENT_PRODUCT_IT'), $inCategory]);
        if ($qBookLevelSMK) {
            return $qBookLevelSMK->result();
        } else {
            return $this->db->error();
        }
    }

    public function getProductCovid()
    {
        $inCategory = explode(',', getenv('PRODUCT_COVID'));

        $queryRaw = "
                SELECT  `o`.`id_product` AS `id_product`,
                        `o`.`kode_buku` AS `kode_buku`,
                        `o`.`reference` AS `isbn`,
                        `o`.`name` AS `judul`,
                        ROUND(`o`.`price_1`) AS `harga`,
                        `p`.`name` AS `kelas`,
                        `o`.`weight` AS `weight`,
                        `p`.`id_category` AS `category_id`, 
                        `q`.`name` AS type,
                        `q`.`id_category` AS type_id
                FROM    `product` `o`
                JOIN    `category` `p` ON `p`.`id_category`=`o`.`id_category_default`
                JOIN    `category` `q` ON `q`.`id_category`=`p`.`id_parent`
                WHERE   1
                AND     `o`.`active` = ?
                AND     `o`.`id_product` IN (
                    SELECT `a`.`id_product`
                    FROM `category_product` `a`
                    WHERE 1
                    AND `a`.`id_category` = ?
                    AND `a`.`id_product` IN (
                        SELECT `aa`.`id_product`
                        FROM `category_product` `aa`
                        INNER JOIN `category` `bb` ON (`aa`.`id_category` = `bb`.`id_category`)
                        WHERE 1
                        AND `bb`.`id_category` IN ?
                    )
                )
                AND `o`.`kode_buku` IS NOT NULL
                ORDER BY `o`.`id_category_default` ASC, `o`.`sort_order` ASC";
        $qBookLevelSMK = $this->db->query($queryRaw, [1, getenv('PARENT_PRODUCT_COVID'), $inCategory]);
        if ($qBookLevelSMK) {
            return $qBookLevelSMK->result();
        } else {
            return $this->db->error();
        }
    }

    public function getAlatTulis()
    {
        $inCategory = explode(',', getenv('ALAT_TULIS'));

        $queryRaw = "
                SELECT  `o`.`id_product` AS `id_product`,
                        `o`.`kode_buku` AS `kode_buku`,
                        `o`.`reference` AS `isbn`,
                        `o`.`name` AS `judul`,
                        ROUND(`o`.`price_1`) AS `harga`,
                        `p`.`name` AS `kelas`,
                        `o`.`weight` AS `weight`,
                        `p`.`id_category` AS `category_id`, 
                        `q`.`name` AS type,
                        `q`.`id_category` AS type_id
                FROM    `product` `o`
                JOIN    `category` `p` ON `p`.`id_category`=`o`.`id_category_default`
                JOIN    `category` `q` ON `q`.`id_category`=`p`.`id_parent`
                WHERE   1
                AND     `o`.`active` = ?
                AND     `o`.`id_product` IN (
                    SELECT `a`.`id_product`
                    FROM `category_product` `a`
                    WHERE 1
                    AND `a`.`id_category` = ?
                    AND `a`.`id_product` IN (
                        SELECT `aa`.`id_product`
                        FROM `category_product` `aa`
                        INNER JOIN `category` `bb` ON (`aa`.`id_category` = `bb`.`id_category`)
                        WHERE 1
                        AND `bb`.`id_category` IN ?
                    )
                )
                AND `o`.`kode_buku` IS NOT NULL
                ORDER BY `o`.`id_category_default` ASC, `o`.`sort_order` ASC";
        $qBookLevelSMK = $this->db->query($queryRaw, [1, getenv('PARENT_ALAT_TULIS'), $inCategory]);
        if ($qBookLevelSMK) {
            return $qBookLevelSMK->result();
        } else {
            return $this->db->error();
        }
    }

    public function getSmartLibrary()
    {
        $inCategory = explode(',', getenv('SART_LIBRARY'));

        $queryRaw = "
                SELECT  `o`.`id_product` AS `id_product`,
                        `o`.`kode_buku` AS `kode_buku`,
                        `o`.`reference` AS `isbn`,
                        `o`.`name` AS `judul`,
                        ROUND(`o`.`price_1`) AS `harga`,
                        `p`.`name` AS `kelas`,
                        `o`.`weight` AS `weight`,
                        `p`.`id_category` AS `category_id`, 
                        `q`.`name` AS type,
                        `q`.`id_category` AS type_id
                FROM    `product` `o`
                JOIN    `category` `p` ON `p`.`id_category`=`o`.`id_category_default`
                JOIN    `category` `q` ON `q`.`id_category`=`p`.`id_parent`
                WHERE   1
                AND     `o`.`active` = ?
                AND     `o`.`id_product` IN (
                    SELECT `a`.`id_product`
                    FROM `category_product` `a`
                    WHERE 1
                    AND `a`.`id_category` = ?
                    AND `a`.`id_product` IN (
                        SELECT `aa`.`id_product`
                        FROM `category_product` `aa`
                        INNER JOIN `category` `bb` ON (`aa`.`id_category` = `bb`.`id_category`)
                        WHERE 1
                        AND `bb`.`id_category` IN ?
                    )
                )
                AND `o`.`kode_buku` IS NOT NULL
                ORDER BY `o`.`id_category_default` ASC, `o`.`sort_order` ASC";
        $qSmartLibrary = $this->db->query($queryRaw, [1, getenv('PARENT_SMART_LIBRARY'), $inCategory]);
        if ($qSmartLibrary) {
            return $qSmartLibrary->result();
        } else {
            return $this->db->error();
        }
    }

    public function getBookPendampingK13($jenjang)
    {
        $in_category = [];
        switch ($jenjang) {
            case '1-6':
                $inCategory = explode(',', getenv('PENDAMPING_K13_SD'));
                break;
            case '7-9':
                $inCategory = explode(',', getenv('PENDAMPING_K13_SMP'));
                break;
            case '10-12':
                $inCategory = explode(',', getenv('PENDAMPING_K13_SMA'));
                break;
        }

        $qRawQuery = "
                SELECT  `o`.`id_product` AS `id_product`,
                        `o`.`kode_buku` AS `kode_buku`,
                        `o`.`reference` AS `isbn`,
                        `o`.`name` AS `judul`,
                        ROUND(`o`.`price_1`) AS `harga`,
                        `p`.`name` AS `kelas`,
                        `o`.`weight` AS `weight`,
                        `p`.`id_category` AS `category_id`, 
                        `q`.`name` AS type,
                        `q`.`id_category` AS type_id
                FROM    `product` `o`
                JOIN    `category` `p` ON `p`.`id_category`=`o`.`id_category_default`
                JOIN    `category` `q` ON `q`.`id_category`=`p`.`id_parent`
                WHERE   1
                AND     `o`.`active` = ?
                AND     `o`.`id_product` IN (
                    SELECT `a`.`id_product`
                    FROM `category_product` `a`
                    WHERE 1
                    AND `a`.`id_category` = ?
                    AND `a`.`id_product` IN (
                        SELECT `aa`.`id_product`
                        FROM `category_product` `aa`
                        INNER JOIN `category` `bb` ON (`aa`.`id_category` = `bb`.`id_category`)
                        WHERE 1
                        AND `bb`.`id_category` IN ?
                    )
                )
                AND `o`.`kode_buku` IS NOT NULL
                ORDER BY `o`.`id_category_default` ASC, `o`.`sort_order` ASC";
        $qBookLevel = $this->db->query($qRawQuery, [1, getenv('PARENT_PENDAMPING_K13'), $inCategory]);
        if ($qBookLevel) {
            return $qBookLevel->result();
        } else {
            return $this->db->error();
        }
    }

    public function getBookPeminatanSmaMA($jenjang)
    {
        $in_category = [];
        switch ($jenjang) {
            case '1-6':
                $inCategory = explode(',', getenv('PEMINATAN_SD'));
                break;
            case '7-9':
                $inCategory = explode(',', getenv('PEMINATAN_SMP'));
                break;
            case '10-12':
                $inCategory = explode(',', getenv('PEMINATAN_SMA_MA'));
                break;
        }

        $qRawQuery = "
                SELECT  `o`.`id_product` AS `id_product`,
                        `o`.`kode_buku` AS `kode_buku`,
                        `o`.`reference` AS `isbn`,
                        `o`.`name` AS `judul`,
                        ROUND(`o`.`price_1`) AS `harga`,
                        `p`.`name` AS `kelas`,
                        `o`.`weight` AS `weight`,
                        `p`.`id_category` AS `category_id`, 
                        `q`.`name` AS type,
                        `q`.`id_category` AS type_id
                FROM    `product` `o`
                JOIN    `category` `p` ON `p`.`id_category`=`o`.`id_category_default`
                JOIN    `category` `q` ON `q`.`id_category`=`p`.`id_parent`
                WHERE   1
                AND     `o`.`active` = ?
                AND     `o`.`id_product` IN (
                    SELECT `a`.`id_product`
                    FROM `category_product` `a`
                    WHERE 1
                    AND `a`.`id_category` = ?
                    AND `a`.`id_product` IN (
                        SELECT `aa`.`id_product`
                        FROM `category_product` `aa`
                        INNER JOIN `category` `bb` ON (`aa`.`id_category` = `bb`.`id_category`)
                        WHERE 1
                        AND `bb`.`id_category` IN ?
                    )
                )
                AND `o`.`kode_buku` IS NOT NULL
                ORDER BY `o`.`id_category_default` ASC, `o`.`sort_order` ASC";
        $qBookLevel = $this->db->query($qRawQuery, [1, getenv('PARENT_PEMINATAN_SMA_MA'), $inCategory]);
        if ($qBookLevel) {
            return $qBookLevel->result();
        } else {
            return $this->db->error();
        }
    }

    public function getBookHetK13($jenjang)
    {
        $in_category = [];
        switch ($jenjang) {
            case '1-6':
                $inCategory = explode(',', getenv('HET_K13_SD'));
                break;
            case '7-9':
                $inCategory = explode(',', getenv('HET_K13_SMP'));
                break;
            case '10-12':
                $inCategory = explode(',', getenv('HET_K13_SMA'));
                break;
        }

        $qRawQuery = "
                SELECT  `o`.`id_product` AS `id_product`,
                        `o`.`kode_buku` AS `kode_buku`,
                        `o`.`reference` AS `isbn`,
                        `o`.`name` AS `judul`,
                        ROUND(`o`.`price_1`) AS `harga`,
                        `p`.`name` AS `kelas`,
                        `o`.`weight` AS `weight`,
                        `p`.`id_category` AS `category_id`, 
                        `q`.`name` AS type,
                        `q`.`id_category` AS type_id
                FROM    `product` `o`
                JOIN    `category` `p` ON `p`.`id_category`=`o`.`id_category_default`
                JOIN    `category` `q` ON `q`.`id_category`=`p`.`id_parent`
                WHERE   1
                AND     `o`.`active` = ?
                AND     `o`.`id_product` IN (
                    SELECT `a`.`id_product`
                    FROM `category_product` `a`
                    WHERE 1
                    AND `a`.`id_category` = ?
                    AND `a`.`id_product` IN (
                        SELECT `aa`.`id_product`
                        FROM `category_product` `aa`
                        INNER JOIN `category` `bb` ON (`aa`.`id_category` = `bb`.`id_category`)
                        WHERE 1
                        AND `bb`.`id_category` IN ?
                    )
                )
                AND `o`.`kode_buku` IS NOT NULL
                ORDER BY `o`.`id_category_default` ASC, `o`.`sort_order` ASC";
        $qBookLevel = $this->db->query($qRawQuery, [1, getenv('PARENT_HET_K13'), $inCategory]);
        if ($qBookLevel) {
            return $qBookLevel->result();
        } else {
            return $this->db->error();
        }
    }

    public function getTransaksiSiapKirim()
    {
        $this->db->select('a.id_transaksi AS id_transaksi, a.total_jumlah AS total_jumlah, a.total_berat AS total_berat, if(a.is_to_school=1, b.school_name, c.nama_gudang) AS tujuan, if(a.is_to_school=1, CONCAT(b.alamat,", ",b.desa,", ",b.kecamatan,", ",b.kabupaten,", ",b.provinsi," - ",b.kodepos), c.alamat_gudang) AS alamat, a.id_pesanan AS id_pesanan, IF(a.kode_pesanan<>"", a.kode_pesanan, a.id_request) AS kode');
        $this->db->from('transaksi a');
        $this->db->join('customer b', 'b.id_customer = a.tujuan', 'left');
        $this->db->join('master_gudang c', 'c.id_gudang = a.tujuan', 'left');
        $this->db->where('a.asal', $this->session->userdata('adm_region'));
        $this->db->where('a.status_transaksi', 2);
        return $this->db->get()->result();
    }

    public function listInfoStok($where = null)
    {
        $this->db->select('
            b.name AS judul_buku, b.kode_buku, a.stok_fisik, a.stok_booking, a.stok_available, c.id_category as category, c.name as category_name, d.id_category as parent_category, d.name as parent_category_name,
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
                    y.id_gudang = '.$this->adm_id_gudang.'
                GROUP BY
                    y.id_gudang, z.product_id), 0
            ) AS stok_belum_kirim
        ');
        $this->db->from('info_gudang a');
        $this->db->join('product b', 'b.id_product=a.id_produk', 'inner');
        $this->db->join('category c', 'c.id_category=b.id_category_default', 'inner');
        $this->db->join('category d', 'd.id_category=c.id_parent', 'inner');
        $this->db->where('a.id_gudang', $this->adm_id_gudang);
        $this->db->where('a.periode', $this->periode);
        if ($where) {
            $this->db->where($where);
        }
        $this->db->group_by('a.id_produk');
        $this->db->order_by('b.id_category_default ASC, b.sort_order ASC');
        return $this->db->get()->result();
    }

    public function getListProduct($select = "*", $where = null, $where_in = null)
    {
        $this->db->select($select);
        $this->db->from('product a');
        $this->db->join('category b', 'b.id_category = a.id_category_default');
        $this->db->join('category c', 'c.id_category = b.id_parent');
        if ($where) {
            $this->db->where($where);
        }
        if ($where_in) {
            $this->db->where_in('id_product', $where_in);
        }
        $this->db->order_by('a.id_category_default ASC, a.sort_order ASC');
        return $this->db->get()->result();
    }

    public function getListOef($id_gudang)
    {
        $this->db->select('no_oef, kode_buku');
        $this->db->from('production_order');
        $this->db->where('status', 1);
        $this->db->where('id_gudang', $id_gudang);
        return $this->db->get()->result();
    }

    public function getListOrderProduct($id_transaksi, $id_order)
    {
        $this->db->select('a.id_produk AS id_produk,
                            c.kode_buku AS kode_buku,
                            c.product_name AS judul_buku,
                            d.reference AS isbn,
                            e.name AS kelas,
                            f.name AS type,
                            f.alias AS type_alias,
                            a.jumlah AS kuantitas,
                            a.berat AS total_berat,
                            c.unit_price AS harga_satuan,
                            a.harga AS total_harga');
        $this->db->from('transaksi_detail a');
        $this->db->join('transaksi b', 'b.id_transaksi=a.id_transaksi', 'inner');
        $this->db->join('order_detail c', 'c.id_order=b.id_pesanan AND c.product_id=a.id_produk', 'inner');
        $this->db->join('product d', 'd.id_product=c.product_id', 'inner');
        $this->db->join('category e', 'e.id_category=d.id_category_default', 'inner');
        $this->db->join('category f', 'f.id_category=e.id_parent', 'inner');
        $this->db->where('a.id_transaksi', $id_transaksi);
        $this->db->where('b.id_pesanan', $id_order);
        $this->db->order_by('e.name asc, c.product_name asc');
        return $this->db->get()->result();
    }

    public function getListProductBASTFull($id_order)
    {
        $this->db->select('a.id_produk AS id_produk,
                            c.kode_buku AS kode_buku,
                            c.product_name AS judul_buku,
                            d.reference AS isbn,
                            e.name AS kelas,
                            f.name AS type,
                            f.alias AS type_alias,
                            a.jumlah AS kuantitas,
                            a.berat AS total_berat,
                            c.unit_price AS harga_satuan,
                            a.harga AS total_harga');
        $this->db->from('transaksi_detail a');
        $this->db->join('transaksi b', 'b.id_transaksi=a.id_transaksi', 'inner');
        $this->db->join('order_detail c', 'c.id_order=b.id_pesanan AND c.product_id=a.id_produk', 'inner');
        $this->db->join('product d', 'd.id_product=c.product_id', 'inner');
        $this->db->join('category e', 'e.id_category=d.id_category_default', 'inner');
        $this->db->join('category f', 'f.id_category=e.id_parent', 'inner');
        // $this->db->where('a.id_transaksi', $id_transaksi);
        $this->db->where('b.id_pesanan', $id_order);
        $this->db->order_by('e.name asc, c.product_name asc');
        return $this->db->get()->result();
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

    public function checkStatusBayar($id_customer)
    {
        // $this->db->select('count(id_order) as id_order_count');
        // $this->db->where('periode <', date('Y'));
        // $this->db->where('sts_bayar <', 2);
        // $this->db->where('id_customer', $id_customer);
        // $query = $this->db->get('orders');
        // return $query;

        $this->db->select('                 
                a.id_order AS id_order, 
                a.reference AS reference, 
                b.school_name AS school_name, 
                a.category AS category, 
                a.type AS type, 
                a.date_add AS date_add, 
                c.name AS order_state, 
                c.label AS label, 
                a.korwil_name AS korwil_name,
                sum(a.total_paid) AS total_paid, 
                sum(a.nilai_dibayar) AS nilai_dibayar, 
                sum((a.total_paid - a.nilai_dibayar)) AS nilai_piutang, 
                b.phone AS phone, 
                b.operator AS operator, 
                b.hp_operator AS hp_operator, 
                b.name AS name, 
                b.phone_kepsek AS phone_kepsek,
                a.sales_name as nama_mitra,
                a.rsm_name as nama_rsm
        ');
        $this->db->from('orders a'); 
        $this->db->join('customer b', 'b.id_customer=a.id_customer', 'inner'); 
        $this->db->join('order_state c', 'c.id_order_state=a.current_state', 'inner');     
        $this->db->where_not_in('a.current_state', array(1, 2, 4, 9));
        $this->db->where('a.sts_bayar !=', 2);

        /**
         * 2019-06-24
         * Vindy Pratama
         * Mengubah a.`date_add` menjadi a.`tgl_sampai`
         */
        $this->db->where('a.`tgl_sampai` < DATE_SUB(NOW(), INTERVAL "6" MONTH)');   


        /**
         * 2019-06-24
         * Vindy Pratama
         * Menambahkan query kriteria pesanan yang tidak di blokir
         * Jika pesanan dibuat pada tahun 2016
         * awal
         */
        $this->db->where_not_in('a.id_order', 'SELECT id_order FROM orders h WHERE YEAR(h.`date_add`)=2016');
        /**
         * akhir
         */
        $this->db->where('b.`id_customer`', $id_customer);
        $this->db->group_by('b.`id_customer`'); 
        $query = $this->db->get();
        return $query;
    }

    public function checkPersetujuanRSM($id_order)
    {
        $this->db->select('*');
        $this->db->from('orders');
        $this->db->where('id_order', $id_order);
        $query = $this->db->get();
        return $query;
    }

    public function getListTransaksiDetail($id)
    {
        $this->db->select('b.id as id_transaksi_detail, a.id_transaksi as id_transaksi, `b`.`jumlah` as jumlah, `c`.`capacity` as koli, `c`.`weight` as berat, (`b`.`jumlah` * `c`.`weight`) as total_berat, (b.jumlah div c.`capacity`) as total_koli, (`b`.`jumlah` % `c`.`capacity`) as sisa_koli, c.kode_buku as kode_buku, c.reference as isbn, c.name as judul_buku, d.name as kelas, c.id_product as id_product');
        $this->db->from('transaksi a');
        $this->db->join('transaksi_detail b', 'a.id_transaksi = b.id_transaksi', 'inner');
        $this->db->join('product c', 'b.id_produk = c.id_product', 'inner');
        $this->db->join('category d', 'd.id_category = c.id_category_default', 'inner');
        $this->db->where('b.id', $id);
        $this->db->order_by('a.id_transaksi ASC, b.id ASC');
        return $this->db->get()->row_array();
    }

    public function get_list_product_leftover($id_order, $id_gudang)
    {
        $this->db->select('a.id_order_detail, e.name AS type, e.alias AS type_alias, a.product_id, a.product_name, a.product_quantity, a.quantity_fullfil, a.unit_price, a.total_price, b.reference as isbn, b.kode_buku as kode_buku, b.weight, c.name as kelas, b.capacity as koli, d.stok_booking, d.stok_fisik, d.stok_available');
        $this->db->from('order_detail a');
        $this->db->join('product b', 'b.id_product=a.product_id', 'inner');
        $this->db->join('category c', 'c.id_category=b.id_category_default', 'inner');
        $this->db->join('info_gudang d', 'd.id_produk=a.product_id', 'inner');
        $this->db->join('category e', 'e.id_category=c.id_parent', 'inner');
        $this->db->where('a.id_order', $id_order);
        $this->db->where('d.id_gudang', $id_gudang);
        $this->db->where('d.periode', $this->periode);
        $this->db->where('a.product_quantity <= d.stok_available');
        $this->db->where('a.product_id NOT IN (SELECT xx.id_produk FROM transaksi_detail xx INNER JOIN transaksi yy ON xx.`id_transaksi`=yy.`id_transaksi` WHERE yy.id_pesanan="'.$id_order.'")');
        $this->db->order_by('a.id_order_detail asc, c.name asc');
        return $this->db->get()->result();
    }

    public function check_list_product_leftover($id_order, $id_gudang)
    {
        $this->db->select('a.id_order_detail, e.name AS type, e.alias AS type_alias, a.product_id, a.product_name, a.product_quantity, a.quantity_fullfil, a.unit_price, a.total_price, b.reference as isbn, b.kode_buku as kode_buku, b.weight, c.name as kelas, b.capacity as koli, d.stok_booking, d.stok_fisik, d.stok_available');
        $this->db->from('order_detail a');
        $this->db->join('product b', 'b.id_product=a.product_id', 'inner');
        $this->db->join('category c', 'c.id_category=b.id_category_default', 'inner');
        $this->db->join('info_gudang d', 'd.id_produk=a.product_id', 'inner');
        $this->db->join('category e', 'e.id_category=c.id_parent', 'inner');
        $this->db->where('a.id_order', $id_order);
        $this->db->where('d.id_gudang', $id_gudang);
        $this->db->where('d.periode', $this->periode);
        // $this->db->where('a.product_quantity <= d.stok_available');
        $this->db->where('a.product_id NOT IN (SELECT xx.id_produk FROM transaksi_detail xx INNER JOIN transaksi yy ON xx.`id_transaksi`=yy.`id_transaksi` WHERE yy.id_pesanan="'.$id_order.'")');
        $this->db->order_by('a.id_order_detail asc, c.name asc');
        return $this->db->get()->result();
    }

    public function get_list_product_stock_available($id_order, $id_gudang)
    {
        $this->db->select('a.id_order_detail, e.name AS type, e.alias AS type_alias, a.product_id, a.product_name, a.product_quantity, a.quantity_fullfil, a.unit_price, a.total_price, b.reference as isbn, b.kode_buku as kode_buku, b.weight, c.name as kelas, b.capacity as koli, d.stok_booking, d.stok_fisik, d.stok_available');
        $this->db->from('order_detail a');
        $this->db->join('product b', 'b.id_product=a.product_id', 'inner');
        $this->db->join('category c', 'c.id_category=b.id_category_default', 'inner');
        $this->db->join('info_gudang d', 'd.id_produk=a.product_id', 'inner');
        $this->db->join('category e', 'e.id_category=c.id_parent', 'inner');
        $this->db->where('a.id_order', $id_order);
        $this->db->where('d.id_gudang', $id_gudang);
        $this->db->where('d.periode', $this->periode);
        $this->db->where('a.product_quantity <= d.stok_available');
        $this->db->order_by('a.id_order_detail asc, c.name asc');
        return $this->db->get()->result();
    }
  
    public function get_data_request($id_transaksi_detail)
    {
        $this->db->select('*');
        $this->db->from('request_stock a');
        $this->db->join('transaksi b', 'a.`id_request`=b.`id_request`', 'inner');
        $this->db->join('transaksi_detail c', 'b.`id_transaksi`=c.`id_transaksi`', 'inner');
        $this->db->where('c.`id`', $id_transaksi_detail);
        return $this->db->get()->row_array();
    }
}