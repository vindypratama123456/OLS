<?php
defined('BASEPATH') or exit('No direct script access allowed');

// This can be removed if you use __autoload() in config.php OR use Modular Extensions
require APPPATH . '/libraries/REST_Controller.php';

class Paket extends REST_Controller
{
    protected $secretkey;
    protected $per_page;
    protected $offset;
    protected $curr_page;
    protected $start_date;
    protected $end_date;
    protected $order_by;
    protected $sort;
    protected $methods = [
        'detail_paket_get' => [
            'level' => 1,
            'limit' => 60
        ]
    ];

    public function __construct()
    {
        // Construct the parent class
        parent::__construct();

        $this->per_page = 50;
        $this->offset = 0;
        $this->curr_page = 1;
        $this->start_date = $this->get('start_date') ? $this->get('start_date') . ' 00:00:00' : false;
        $this->end_date = $this->get('end_date') ? $this->get('end_date') . ' 23:59:59' : false;
        $this->update_date = $this->get('update_date') ? $this->get('update_date') : false;
        $this->order_by = 'b.`date_upd`';
        $this->sort = 'DESC';

        // Configure limits on our controller methods
        $this->methods['detail_paket_get']['limit'] = 60; // requests per hour per user/key
        $this->methods['detail_paket_get']['time'] = 60;

        header('Cache-Control: max-age=60');
    }

    public function detail_paket_get($page = 1)
    {
        if ($this->get('per_page') && is_numeric($this->get('per_page'))) {
            if ($this->get('per_page') > 500) {
                return $this->set_response([
                    'status' => false,
                    'message' => 'Jumlah per halaman terlalu tinggi !!!'
                ], REST_Controller::HTTP_BAD_REQUEST);
                exit;
            } else {
                $this->per_page = $this->get('per_page');
            }
        }

        if ( ! $this->get('page')
            || ! is_numeric($this->get('page'))
            || $this->get('page') < 0
            || $this->get('page') == 1) {
            $this->offset = 0;
            $this->curr_page = 1;
        } else {
            $this->offset = ($this->get('page') - 1) * $this->per_page;
            $this->curr_page = $this->get('page');
        }

        if ($this->get('order_by')) {
            $this->order_by = ($this->get('order_by') == 'created_date') ? 'b.`date_add`' : 'b.`date_upd`';
        }

        if ($this->get('sort')) {
            $this->sort = ($this->get('sort') == 'asc') ? 'ASC' : 'DESC';
        }

        $sql = "";
        $sql .= "SELECT CONCAT('GRM',a.`id_order_detail`) AS `id`,
                        a.`id_order` AS `id_pesanan`,
                        c.`sekolah_id` AS `sekolah_id`,
                        (CASE WHEN (`c`.jenjang = '1-6') THEN 'SD'
                              WHEN (`c`.jenjang = '7-9') THEN 'SMP'
                              ELSE 'SMA' END) AS `bentuk`,
                        c.`no_npsn` AS `npsn`,
                        REPLACE(REPLACE(c.`school_name`, '\r', ''), '\n', '') AS `nama_sekolah`,
                        c.`kd_prop` AS `kd_prop`,
                        REPLACE(REPLACE(c.`provinsi`, '\r', ''), '\n', '') AS `prop`,
                        c.`kd_kab_kota` AS `kd_kab_kota`,
                        REPLACE(REPLACE(c.`kabupaten`, '\r', ''), '\n', '') AS `kab_kota`,
                        b.`date_add` AS `p_tgl_pesan`,
                        b.`tgl_konfirmasi` AS `p_tanggal_konfirmasi`,
                        b.`jangka_waktu` AS `p_waktu_pelaksanaan`,
                        REPLACE(REPLACE(a.`kode_buku`, '\r', ''), '\n', '') AS `p_kode_buku`,
                        a.`product_quantity` AS `p_jml_buku`,
                        a.`unit_price` AS `p_harga_konfirm`,
                        a.`total_price` AS `p_total_harga`,
                        b.`tgl_kirim` AS `k_tgl_kirim`,
                        REPLACE(REPLACE(a.`kode_buku`, '\r', ''), '\n', '') AS `k_kode_buku`,
                        a.`product_quantity` AS `k_jml_buku`,
                        b.`tgl_sampai` AS `s_tgl_sampai`,
                        REPLACE(REPLACE(a.`kode_buku`, '\r', ''), '\n', '') AS `s_kode_buku`,
                        a.`product_quantity` AS `s_jml_buku`,
                        REPLACE(REPLACE(b.`nama_penerima`, '\r', ''), '\n', '') AS `s_nama_penerima`,
                        b.`tgl_terima` AS `t_tgl_terima`,
                        REPLACE(REPLACE(a.`kode_buku`, '\r', ''), '\n', '') AS `t_kode_buku`,
                        a.`product_quantity` AS `t_jml_buku`,
                        REPLACE(REPLACE(b.`nomor_surat`, '\r', ''), '\n', '') AS `t_nomor_surat`,
                        b.`tanggal_surat` AS `t_tanggal_bast`,
                        b.`tgl_bayar` AS `b_tgl_bayar`,
                        REPLACE(REPLACE(a.`kode_buku`, '\r', ''), '\n', '') AS `b_kode_buku`,
                        a.`product_quantity` AS `b_jml_buku`,
                        b.`jumlah_bayar` AS `b_jml_bayar`,
                        IF(b.`current_state`=2,0,1) AS `active`,
                        b.`periode` AS `periode`,
                        b.`date_upd` AS `updated_date`
                FROM    `order_detail` a
                JOIN    `orders` b ON b.`id_order`=a.`id_order`
                JOIN    `customer` c ON c.`id_customer`=b.`id_customer`
                WHERE   b.`current_state` NOT IN ?";

        $arr = [[1,4]];

        if ($this->get('start_date') && $this->get('end_date')) {
            $sql .= " AND b.`date_add`>=? AND b.`date_add`<=?";
            array_push($arr, $this->start_date, $this->end_date);
        }

        if ($this->get('update_date') && ( ! $this->get('start_date') && ! $this->get('end_date'))) {
            $sql .= " AND b.`date_upd` LIKE '%".$this->db->escape_like_str($this->update_date)."%'";
        }

        $sql .= " ORDER BY $this->order_by $this->sort, a.id_order_detail DESC LIMIT $this->offset, $this->per_page";

        $query = $this->rest->db->query($sql, $arr);

        $sql_total = "";
        $sql_total .= "SELECT  COUNT(a.`id_order_detail`) AS `total`
                        FROM    `order_detail` a
                        JOIN    `orders` b ON b.`id_order`=a.`id_order`
                        JOIN    `customer` c ON c.`id_customer`=b.`id_customer`
                        WHERE   b.`current_state` NOT IN ('1','4')";

        $arr_total = [[1,4]];

        if ($this->get('start_date') && $this->get('end_date')) {
            $sql_total .= " AND b.`date_add`>='$this->start_date' AND b.`date_add`<='$this->end_date'";
            array_push($arr_total, $this->start_date, $this->end_date);
        }

        if ($this->get('update_date') && ( ! $this->get('start_date') && ! $this->get('end_date'))) {
            $sql_total .= " AND b.`date_upd` LIKE '%".$this->db->escape_like_str($this->update_date)."%'";
        }

        $q_total = $this->rest->db->query($sql_total, $arr_total);
        $total_rows = $q_total->row('total');


        if ($query) {
            $posts = array();
            foreach ($query->result() as $row) {
                $posts[] = $row;
            }
            $query->free_result();
            $pakets = array(
                "total" => (int)$total_rows,
                "current_page" => (int)$this->curr_page,
                "per_page" => (int)$this->per_page,
                "total_page" => ceil($total_rows / $this->per_page),
                "detail_paket" => $posts
            );
        } else {
            exit($this->rest->db->error());
        }

        $id = $this->get('id');

        // If the id parameter doesn't exist return all the pakets

        if ($id === null) {
            // Check if the pakets data store contains pakets (in case the database result returns NULL)
            if ($pakets) {
                $this->output->delete_cache();
                // Set the response and exit
                $this->response($pakets, REST_Controller::HTTP_OK); // OK (200) being the HTTP response code
            } else {
                // Set the response and exit
                $this->response([
                    'status' => false,
                    'message' => 'Data tidak ditemukan'
                ], REST_Controller::HTTP_NOT_FOUND); // NOT_FOUND (404) being the HTTP response code
            }
        }

        // Find and return a single record for a particular user.

        $id = str_replace('GRM', '', $id);
        $id = (int)$id;

        $raw_query_detil = "
                SELECT CONCAT('GRM',a.`id_order_detail`) AS `id`,
                        a.`id_order` AS `id_pesanan`,
                        c.`sekolah_id` AS `sekolah_id`,
                        (CASE WHEN (`c`.jenjang = '1-6') THEN 'SD'
                              WHEN (`c`.jenjang = '7-9') THEN 'SMP'
                              ELSE 'SMA' END) AS `bentuk`,
                        c.`no_npsn` AS `npsn`,
                        REPLACE(REPLACE(c.`school_name`, '\r', ''), '\n', '') AS `nama_sekolah`,
                        c.`kd_prop` AS `kd_prop`,
                        REPLACE(REPLACE(c.`provinsi`, '\r', ''), '\n', '') AS `prop`,
                        c.`kd_kab_kota` AS `kd_kab_kota`,
                        REPLACE(REPLACE(c.`kabupaten`, '\r', ''), '\n', '') AS `kab_kota`,
                        b.`date_add` AS `p_tgl_pesan`,
                        b.`tgl_konfirmasi` AS `p_tanggal_konfirmasi`,
                        b.`jangka_waktu` AS `p_waktu_pelaksanaan`,
                        REPLACE(REPLACE(a.`kode_buku`, '\r', ''), '\n', '') AS `p_kode_buku`,
                        a.`product_quantity` AS `p_jml_buku`,
                        a.`unit_price` AS `p_harga_konfirm`,
                        a.`total_price` AS `p_total_harga`,
                        b.`tgl_kirim` AS `k_tgl_kirim`,
                        REPLACE(REPLACE(a.`kode_buku`, '\r', ''), '\n', '') AS `k_kode_buku`,
                        a.`product_quantity` AS `k_jml_buku`,
                        b.`tgl_sampai` AS `s_tgl_sampai`,
                        REPLACE(REPLACE(a.`kode_buku`, '\r', ''), '\n', '') AS `s_kode_buku`,
                        a.`product_quantity` AS `s_jml_buku`,
                        REPLACE(REPLACE(b.`nama_penerima`, '\r', ''), '\n', '') AS `s_nama_penerima`,
                        b.`tgl_terima` AS `t_tgl_terima`,
                        REPLACE(REPLACE(a.`kode_buku`, '\r', ''), '\n', '') AS `t_kode_buku`,
                        a.`product_quantity` AS `t_jml_buku`,
                        REPLACE(REPLACE(b.`nomor_surat`, '\r', ''), '\n', '') AS `t_nomor_surat`,
                        b.`tanggal_surat` AS `t_tanggal_bast`,
                        b.`tgl_bayar` AS `b_tgl_bayar`,
                        REPLACE(REPLACE(a.`kode_buku`, '\r', ''), '\n', '') AS `b_kode_buku`,
                        a.`product_quantity` AS `b_jml_buku`,
                        b.`jumlah_bayar` AS `b_jml_bayar`,
                        IF(b.`current_state`=2,0,1) AS `active`,
                        b.`periode` AS `periode`,
                        b.`date_upd` AS `updated_date`
                FROM    `order_detail` a
                JOIN    `orders` b ON b.`id_order`=a.`id_order`
                JOIN    `customer` c ON c.`id_customer`=b.`id_customer`
                WHERE   b.`current_state` NOT IN ? AND a.`id_order_detail` = ?";

        $query_detil = $this->rest->db->query($raw_query_detil, [[1,4], $id]);

        if ($query_detil) {
            $datas = array();
            foreach ($query_detil->result_array() as $row) {
                $datas[] = $row;
            }
            $query_detil->free_result();
            if ($datas) {
                $detil = array(
                    "total" => 1,
                    "current_page" => 1,
                    "per_page" => 1,
                    "total_page" => 1,
                    "detail_paket" => $datas
                );
            }
        } else {
            exit($this->rest->db->error());
        }

        // Validate the id.
        if ($id <= 0) {
            // Invalid id, set the response and exit.
            $this->response(null, REST_Controller::HTTP_BAD_REQUEST); // BAD_REQUEST (400) being the HTTP response code
        }

        if ( ! empty($datas)) {
            $this->output->delete_cache();
            $this->set_response($detil, REST_Controller::HTTP_OK); // OK (200) being the HTTP response code
        } else {
            $this->set_response([
                'status' => false,
                'message' => 'Paket tidak ditemukan'
            ], REST_Controller::HTTP_NOT_FOUND); // NOT_FOUND (404) being the HTTP response code
        }
    }
}
