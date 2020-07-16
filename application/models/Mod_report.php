<?php
defined('BASEPATH') || exit('No direct script access allowed');

class Mod_report extends CI_Model
{
    public function __construct()
    {
        parent::__construct();
    }

    public function getListReport(
        $limit = ['perpage' => 10, 'offset' => 0],
        $start,
        $end,
        $order = 'desc',
        $wil = false,
        $logistik = false
    ) {
        $this->db->select("a.`id_order` AS `id_pesanan`,
                            b.`reference` AS `kode_pesanan`,
                            c.`bentuk` AS `bentuk`,
                            c.`no_npsn` AS `npsn`,
                            c.`school_name` AS `nama_sekolah`,
                            c.`provinsi` AS `prop`,
                            c.`kabupaten` AS `kab_kota`,
                            c.`kecamatan` AS `kecamatan`,
                            c.`desa` AS `desa`,
                            c.`kodepos` AS `kodepos`,
                            c.`alamat` AS `alamat`,
                            c.`phone` AS `phone`,
                            c.`zona` AS `zona`,
                            a.`kode_buku` AS `p_kode_buku`,
                            d.`reference` AS `isbn`,
                            e.`name` AS `kelas`,
                            d.`name` AS `p_judul_buku`,
                            a.`product_quantity` AS `p_jml_buku`,
                            a.`unit_price` AS `p_harga_konfirm`,
                            a.`total_price` AS `p_total_harga`,
                            b.`date_add` AS `p_tgl_pesan`,
                            b.`tgl_konfirmasi` AS `p_tanggal_konfirmasi`,
                            b.`tgl_logistik` AS `p_tanggal_logistik`,
                            b.`jangka_waktu` AS `p_waktu_pelaksanaan`,
                            b.`tgl_kirim` AS `k_tgl_kirim`,
                            b.`tgl_sampai` AS `s_tgl_sampai`,
                            b.`nama_penerima` AS `s_nama_penerima`,
                            b.`tgl_terima` AS `t_tgl_terima`,
                            b.`nomor_surat` AS `t_nomor_surat`,
                            b.`tanggal_surat` AS `t_tanggal_bast`,
                            b.`tgl_bayar` AS `b_tgl_bayar`,
                            b.`jumlah_bayar` AS `b_jml_bayar`,
                            b.`current_state` AS `status`,
                            b.`is_intan` AS `logistik`,
                            b.`is_offline` AS `is_offline`,
                            b.`sales_referer` AS `sales_referer`,
                            b.`sales_name` AS `sales_name`,
                            b.`sales_phone` AS `sales_phone`,
                            b.`korwil_email` AS `korwil_email`,
                            b.`korwil_name` AS `korwil_name`,
                            b.`korwil_phone` AS `korwil_phone`,
                            a.`quantity_fullfil` AS `realisasi`");
        $this->db->from('order_detail a');
        $this->db->join('orders b', 'b.id_order=a.id_order', 'inner');
        $this->db->join('customer c', 'c.id_customer=b.id_customer', 'inner');
        $this->db->join('product d', 'd.id_product=a.product_id', 'inner');
        $this->db->join('category e', 'd.id_category_default=e.id_category', 'inner');
        $this->db->where('b.date_add >=', $start);
        $this->db->where('b.date_add <=', $end);
        $this->db->where('b.current_state NOT IN (1,2,4)');
        // $this->db->where('(c.`id_customer`<>1 OR c.`school_name`<>"SMP NEGERI 1 TUKDANA")');
        if ($wil) {
            $this->db->where('c.kabupaten', $wil);
        }
        if ($logistik) {
            $this->db->where('b.current_state', 5);
            $this->db->where('b.is_intan', 1);
        }
        $this->db->order_by('a.id_order', $order);
        $this->db->limit($limit['perpage'], $limit['offset']);
        $q = $this->db->get()->result_array();
        return $q;
    }

    public function getListExcel(
        $start,
        $end,
        $order = 'desc',
        $wil = false,
        $logistik = false,
        $korwil = false,
        $idEmployee = false
    ) {
        $this->db->select("a.`id_order` AS `id_pesanan`,
                            b.`reference` AS `kode_pesanan`,
                            c.`bentuk` AS `bentuk`,
                            c.`no_npsn` AS `npsn`,
                            c.`school_name` AS `nama_sekolah`,
                            c.`provinsi` AS `prop`,
                            c.`kabupaten` AS `kab_kota`,
                            c.`kecamatan` AS `kecamatan`,
                            c.`desa` AS `desa`,
                            c.`kodepos` AS `kodepos`,
                            c.`alamat` AS `alamat`,
                            c.`phone` AS `phone`,
                            c.`zona` AS `zona`,
                            a.`kode_buku` AS `p_kode_buku`,
                            d.`reference` AS `isbn`,
                            e.`name` AS `kelas`,
                            d.`name` AS `p_judul_buku`,
                            a.`product_quantity` AS `p_jml_buku`,
                            a.`unit_price` AS `p_harga_konfirm`,
                            a.`total_price` AS `p_total_harga`,
                            b.`date_add` AS `p_tgl_pesan`,
                            b.`tgl_konfirmasi` AS `p_tanggal_konfirmasi`,
                            b.`tgl_logistik` AS `p_tanggal_logistik`,
                            b.`jangka_waktu` AS `p_waktu_pelaksanaan`,
                            b.`tgl_kirim` AS `k_tgl_kirim`,
                            b.`tgl_sampai` AS `s_tgl_sampai`,
                            b.`nama_penerima` AS `s_nama_penerima`,
                            b.`tgl_terima` AS `t_tgl_terima`,
                            b.`nomor_surat` AS `t_nomor_surat`,
                            b.`tanggal_surat` AS `t_tanggal_bast`,
                            b.`tgl_bayar` AS `b_tgl_bayar`,
                            b.`jumlah_bayar` AS `b_jml_bayar`,
                            b.`current_state` AS `status`,
                            b.`is_intan` AS `logistik`,
                            b.`is_offline` AS `is_offline`,
                            b.`sales_referer` AS `sales_referer`,
                            b.`sales_name` AS `sales_name`,
                            b.`sales_phone` AS `sales_phone`,
                            b.`korwil_email` AS `korwil_email`,
                            b.`korwil_name` AS `korwil_name`,
                            b.`korwil_phone` AS `korwil_phone`,
                            a.`quantity_fullfil` AS `realisasi`");
        $this->db->from('order_detail a');
        $this->db->join('orders b', 'b.id_order=a.id_order', 'inner');
        $this->db->join('customer c', 'c.id_customer=b.id_customer', 'inner');
        $this->db->join('product d', 'd.id_product=a.product_id', 'inner');
        $this->db->join('category e', 'd.id_category_default=e.id_category', 'inner');
        $this->db->where('b.date_add >=', $start);
        $this->db->where('b.date_add <=', $end);
        $this->db->where('b.current_state NOT IN (1,2,4)');
        if ($wil) {
            $this->db->where('c.kabupaten', $wil);
        }
        if ($logistik) {
            $this->db->where('b.current_state', 5);
            $this->db->where('b.is_intan', 1);
        }
        if ($korwil) {
            $this->db->where('b.korwil_email', $korwil);
        }
        if ($idEmployee) {
            $this->db->where('c.kabupaten IN (SELECT kabupaten_kota FROM employee_kabupaten_kota WHERE id_employee=' . $idEmployee . ')');
        }
        $this->db->order_by('a.id_order', $order);
        $q = $this->db->get()->result_array();
        return $q;
    }

    public function getTotalSummary($start, $end, $wil = false, $logistik = false)
    {
        if ($wil) {
            $this->db->select('SUM(a.`total_price`) AS `total_omset`,
                                SUM(a.`product_quantity`) AS `total_buku`,
                                (SELECT COUNT(aa.`id_order`) FROM `orders` aa JOIN customer bb ON bb.id_customer=aa.id_customer WHERE aa.`current_state` NOT IN (1,2,4) AND aa.`date_add`>="' . $start . '" AND aa.`date_add`<="' . $end . '" AND bb.kabupaten="' . $wil . '") AS `total_pesanan`');
        } elseif ($logistik) {
            $this->db->select('SUM(a.`total_price`) AS `total_omset`,
                                SUM(a.`product_quantity`) AS `total_buku`,
                                (SELECT COUNT(`id_order`) FROM `orders` WHERE `current_state`=5 AND `is_intan`=1 AND `date_add` >= "' . $start . '" AND `date_add` <= "' . $end . '") AS `total_pesanan`');
        } else {
            $this->db->select('SUM(a.`total_price`) AS `total_omset`,
                                SUM(a.`product_quantity`) AS `total_buku`,
                                (SELECT COUNT(`id_order`) FROM `orders` WHERE `current_state` NOT IN (1,2,4) AND `date_add` >= "' . $start . '" AND `date_add` <= "' . $end . '") AS `total_pesanan`');
        }
        $this->db->from('order_detail a');
        $this->db->join('orders b', 'b.id_order=a.id_order', 'inner');
        $this->db->join('customer c', 'c.id_customer=b.id_customer', 'inner');
        $this->db->join('product d', 'd.id_product=a.product_id', 'inner');
        $this->db->join('category e', 'd.id_category_default=e.id_category', 'inner');
        $this->db->where('b.date_add >=', $start);
        $this->db->where('b.date_add <=', $end);
        $this->db->where('b.current_state NOT IN (1,2,4)');
        // $this->db->where('(c.`id_customer`<>1 OR c.`school_name`<>"SMP NEGERI 1 TUKDANA")');
        if ($wil) {
            $this->db->where('c.kabupaten', $wil);
        }
        if ($logistik) {
            $this->db->where('b.current_state', 5);
            $this->db->where('b.is_intan', 1);
        }
        $q = $this->db->get()->result();
        return $q;
    }

    public function getTotalReport($start, $end, $wil = false)
    {
        $qRawsql = "";
        $qRawsql .= "SELECT a.*, b.*
                FROM order_detail a
                INNER JOIN orders b ON b.id_order=a.id_order
                INNER JOIN customer c ON c.id_customer=b.id_customer
                WHERE b.date_add >= ?
                      AND b.date_add <= ?
                      AND b.current_state NOT IN ?";
        if ($wil) {
            $qRawsql .= " AND c.kabupaten = ?";
            $query = $this->db->query($qRawsql, [$start, $end, [1, 2, 4], $wil]);
        } else {
            $query = $this->db->query($qRawsql, [$start, $end, [1, 2, 4]]);
        }
        return $query->num_rows();
    }

    public function getListWilayah()
    {
        return $this->db->select('DISTINCT(b.kabupaten) AS `kabupaten`')
            ->from('orders a')
            ->join('customer b', 'b.id_customer=a.id_customer', 'left')
            ->order_by('b.kabupaten', 'asc')
            ->get()
            ->result();
    }

    public function getWilayahKoordinator($id)
    {
        return $this->db->select('DISTINCT(b.kabupaten) AS `kabupaten`')
            ->from('orders a')
            ->join('customer b', 'b.id_customer=a.id_customer', 'left')
            ->join('employee_kabupaten_kota c', 'c.kabupaten_kota=b.kabupaten', 'left')
            ->where('c.id_employee', $id)
            ->order_by('b.kabupaten', 'asc')
            ->get()
            ->result();
    }

    public function getListReportKorwil(
        $limit = ['perpage' => 10, 'offset' => 0],
        $start,
        $end,
        $order = 'desc',
        $korwil = false,
        $idEmployee = false
    ) {
        $this->db->select("a.`id_order` AS `id_pesanan`,
                            b.`reference` AS `kode_pesanan`,
                            c.`bentuk` AS `bentuk`,
                            c.`no_npsn` AS `npsn`,
                            c.`school_name` AS `nama_sekolah`,
                            c.`provinsi` AS `prop`,
                            c.`kabupaten` AS `kab_kota`,
                            c.`kecamatan` AS `kecamatan`,
                            c.`desa` AS `desa`,
                            c.`kodepos` AS `kodepos`,
                            c.`alamat` AS `alamat`,
                            c.`phone` AS `phone`,
                            c.`zona` AS `zona`,
                            a.`kode_buku` AS `p_kode_buku`,
                            d.`reference` AS `isbn`,
                            e.`name` AS `kelas`,
                            d.`name` AS `p_judul_buku`,
                            a.`product_quantity` AS `p_jml_buku`,
                            a.`unit_price` AS `p_harga_konfirm`,
                            a.`total_price` AS `p_total_harga`,
                            b.`date_add` AS `p_tgl_pesan`,
                            b.`tgl_konfirmasi` AS `p_tanggal_konfirmasi`,
                            b.`tgl_logistik` AS `p_tanggal_logistik`,
                            b.`jangka_waktu` AS `p_waktu_pelaksanaan`,
                            b.`tgl_kirim` AS `k_tgl_kirim`,
                            b.`tgl_sampai` AS `s_tgl_sampai`,
                            b.`nama_penerima` AS `s_nama_penerima`,
                            b.`tgl_terima` AS `t_tgl_terima`,
                            b.`nomor_surat` AS `t_nomor_surat`,
                            b.`tanggal_surat` AS `t_tanggal_bast`,
                            b.`tgl_bayar` AS `b_tgl_bayar`,
                            b.`jumlah_bayar` AS `b_jml_bayar`,
                            b.`current_state` AS `status`,
                            b.`is_intan` AS `logistik`,
                            b.`is_offline` AS `is_offline`,
                            b.`sales_referer` AS `sales_referer`,
                            b.`sales_name` AS `sales_name`,
                            b.`sales_phone` AS `sales_phone`,
                            b.`korwil_email` AS `korwil_email`,
                            b.`korwil_name` AS `korwil_name`,
                            b.`korwil_phone` AS `korwil_phone`,
                            a.`quantity_fullfil` AS `realisasi`");
        $this->db->from('order_detail a');
        $this->db->join('orders b', 'b.id_order=a.id_order', 'inner');
        $this->db->join('customer c', 'c.id_customer=b.id_customer', 'inner');
        $this->db->join('product d', 'd.id_product=a.product_id', 'inner');
        $this->db->join('category e', 'd.id_category_default=e.id_category', 'inner');
        $this->db->where('b.date_add >=', $start);
        $this->db->where('b.date_add <=', $end);
        $this->db->where('b.current_state NOT IN (1,2,4)');
        // $this->db->where('(c.`id_customer`<>1 OR c.`school_name`<>"SMP NEGERI 1 TUKDANA")');
        if ($idEmployee) {
            $this->db->where('c.kabupaten IN (SELECT kabupaten_kota FROM employee_kabupaten_kota WHERE id_employee=' . $idEmployee . ')');
        } else {
            $this->db->where('b.`korwil_email`', $korwil);
        }
        $this->db->order_by('a.id_order', $order);
        $this->db->limit($limit['perpage'], $limit['offset']);
        $q = $this->db->get()->result_array();
        return $q;
    }

    public function getTotalSummaryKorwil($start, $end, $korwil = false, $idEmployee = false)
    {
        $this->db->select('SUM(a.`total_price`) AS `total_omset`,
                            SUM(a.`product_quantity`) AS `total_buku`,
                            (SELECT COUNT(`id_order`) FROM `orders` WHERE `current_state` NOT IN (1,2,4) AND `date_add` >= "' . $start . '" AND `date_add` <= "' . $end . '") AS `total_pesanan`');
        $this->db->from('order_detail a');
        $this->db->join('orders b', 'b.id_order=a.id_order', 'inner');
        $this->db->join('customer c', 'c.id_customer=b.id_customer', 'inner');
        $this->db->join('product d', 'd.id_product=a.product_id', 'inner');
        $this->db->join('category e', 'd.id_category_default=e.id_category', 'inner');
        $this->db->where('b.date_add >=', $start);
        $this->db->where('b.date_add <=', $end);
        $this->db->where('b.current_state NOT IN (1,2,4)');
        // $this->db->where('(c.`id_customer`<>1 OR c.`school_name`<>"SMP NEGERI 1 TUKDANA")');
        if ($idEmployee) {
            $this->db->where('c.kabupaten IN (SELECT kabupaten_kota FROM employee_kabupaten_kota WHERE id_employee=' . $idEmployee . ')');
        } else {
            $this->db->where('b.korwil_email', $korwil);
        }
        $q = $this->db->get()->result();
        return $q;
    }

    public function getRekapitulasiDataSekolah($rekapitulasi, $isOffline, $dateEnd, $dateStart = null)
    {
        if ($dateStart) {
            $date = "k.date_add BETWEEN '$dateStart' AND '$dateEnd'";
        } else {
            $date = "k.date_add <= '$dateEnd'";
        }
        if ($rekapitulasi == 1) {
            $select = "
                (
                    SELECT COUNT(DISTINCT k.id_customer)
                    FROM orders k
                    INNER JOIN customer l ON k.id_customer = l.id_customer
                    WHERE k.is_offline = $isOffline AND $date AND l.bentuk = a.bentuk AND k.current_state NOT IN (1,2,4)
                ) as pesan,
                (
                    SELECT COUNT(DISTINCT k.id_customer)
                    FROM orders k
                    INNER JOIN customer l ON k.id_customer = l.id_customer
                    WHERE k.is_offline = $isOffline AND $date AND l.bentuk = a.bentuk AND k.current_state IN (6,7,8)
                ) as kirim,
                (
                    SELECT COUNT(DISTINCT k.id_customer)
                    FROM orders k
                    INNER JOIN customer l ON k.id_customer = l.id_customer
                    WHERE k.is_offline = $isOffline AND $date AND l.bentuk = a.bentuk AND k.current_state IN (9)
                ) as bayar
            ";
        } else {
            $select = "
                (
                    SELECT IFNULL(SUM(j.product_quantity), 0)
                    FROM order_detail j
                    INNER JOIN orders k ON j.id_order = k.id_order
                    INNER JOIN customer l ON k.id_customer = l.id_customer
                    WHERE $date AND l.bentuk = a.bentuk AND k.is_offline = $isOffline AND k.current_state NOT IN (1,2,4)
                ) as pesan_buku,
                (
                    SELECT IFNULL(SUM(j.total_price), 0)
                    FROM order_detail j
                    INNER JOIN orders k ON j.id_order = k.id_order
                    INNER JOIN customer l ON k.id_customer = l.id_customer
                    WHERE $date AND l.bentuk = a.bentuk AND k.is_offline = $isOffline AND k.current_state NOT IN (1,2,4)
                ) as pesan_harga,
                (
                    SELECT IFNULL(SUM(j.product_quantity), 0)
                    FROM order_detail j
                    INNER JOIN orders k ON j.id_order = k.id_order
                    INNER JOIN customer l ON k.id_customer = l.id_customer
                    WHERE $date AND l.bentuk = a.bentuk AND k.is_offline = $isOffline AND k.current_state NOT IN (6,7,8)
                ) as kirim_buku,
                (
                    SELECT IFNULL(SUM(j.total_price), 0)
                    FROM order_detail j
                    INNER JOIN orders k ON j.id_order = k.id_order
                    INNER JOIN customer l ON k.id_customer = l.id_customer
                    WHERE $date AND l.bentuk = a.bentuk AND k.is_offline = $isOffline AND k.current_state NOT IN (6,7,8)
                ) as kirim_harga,
                (
                    SELECT IFNULL(SUM(j.product_quantity), 0)
                    FROM order_detail j
                    INNER JOIN orders k ON j.id_order = k.id_order
                    INNER JOIN customer l ON k.id_customer = l.id_customer
                    WHERE $date AND l.bentuk = a.bentuk AND k.is_offline = $isOffline AND k.current_state >= 6 AND k.file_bast <> ''
                ) as bast,
                (
                    SELECT IFNULL(SUM(k.total_paid), 0)
                    FROM orders k
                    INNER JOIN customer l ON k.id_customer = l.id_customer
                    WHERE $date AND l.bentuk = a.bentuk AND k.is_offline = $isOffline AND k.current_state >= 5
                ) as bayar_tagihan,
                (
                    SELECT IFNULL(SUM(k.nilai_dibayar), 0)
                    FROM orders k
                    INNER JOIN customer l ON k.id_customer = l.id_customer
                    WHERE $date AND l.bentuk = a.bentuk AND k.is_offline = $isOffline AND k.current_state >= 5
                ) as bayar_terbayar
            ";
        }
        $this->db->select("a.jenjang, a.bentuk, $select");
        $this->db->from('customer a');
        $this->db->group_by('a.bentuk');
        $this->db->order_by('a.jenjang');
        return $this->db->get()->result();
    }
}