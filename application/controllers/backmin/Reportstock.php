<?php
defined('BASEPATH') or exit('No direct script access allowed');

/**
 * @property Mod_scm $mod_scm
 * @property Mod_finance $mod_finance
 */
class Reportstock extends CI_Controller
{
    public $periode;

    public function __construct()
    {
        parent::__construct();
        $this->load->model('mod_scm');
        $this->load->model('mod_finance');
        $this->periode = (int)env('PERIODE');
    }

    public function index()
    {
        $this->updateStokSupplyChain();
    }

    public function updateStokSupplyChain()
    {
        $this->db->trans_begin();
        $listreport = $this->mod_scm->getReportPesanan();
        $dataReport = [];
        foreach ($listreport as $key => $value) {
            $dataReport["stok_fisik"] = $value->stok_fisik;
            $dataReport["stok_ip"] = $value->stok_ip;
            $dataReport["stok_kirim"] = $value->stok_kirim;
            $dataReport["total_produksi"] = $value->stok_fisik + $value->stok_ip + $value->stok_kirim;
            $dataReport["stok_konfirmasi"] = $value->stok_konfirmasi + $value->stok_konfirmasi_sales;
            $dataReport["stok_booking"] = $value->stok_booking;
            $dataReport["stok_belum_kirim"] = ($value->stok_konfirmasi + $value->stok_konfirmasi_sales) + $value->stok_booking;
            $dataReport["total_pesanan"] = $value->stok_kirim + ($value->stok_konfirmasi + $value->stok_konfirmasi_sales) + $value->stok_booking;
            $dataReport["stok_available"] = $value->stok_fisik - ($value->stok_konfirmasi + $value->stok_booking);
            $this->mod_scm->editStock('report_stock', $value->id_produk, $dataReport, "id_gudang = " . $value->id_gudang);
        }
        if ($this->db->trans_status() === true) {
            $this->db->trans_commit();
            $callBack['message'] = "Update report stock supply chain successfull";
        } else {
            $this->db->trans_rollback();
            $callBack['message'] = "Update report stock supply chain unsuccessfull";
        }
        echo json_encode($callBack, true);
    }

    public function updateStokKonfirmasiGudang()
    {
        $this->db->trans_begin();
        $select = "
            (
                SELECT sum(d.product_quantity)
                FROM order_detail d
                inner join order_scm e on d.id_order = e.id_order
                inner join customer f on e.id_customer = f.id_customer
                inner join gudang_kabupaten g on f.kabupaten = g.kabupaten
                WHERE
                    e.status = 1 and g.id_gudang = 1 and d.product_id = a.id_produk
            ) +
            (
                select sum(q.jumlah)
                from transaksi_detail q
                inner join transaksi p on q.id_transaksi = p.id_transaksi
                where q.id_produk = a.id_produk and p.status_transaksi < 5 and p.is_to_school = 1 and p.is_forward = 0 and p.asal = 1
            )
            (
                select h.stok_booking
                from info_gudang h
                where h.id_produk = a.id_produk and h.id_gudang = 1 and h.periode = ".$this->periode."
            ) as gudang_medan,
            (
                SELECT sum(d.product_quantity)
                FROM order_detail d
                inner join order_scm e on d.id_order = e.id_order
                inner join customer f on e.id_customer = f.id_customer
                inner join gudang_kabupaten g on f.kabupaten = g.kabupaten
                WHERE
                    e.status = 1 and g.id_gudang = 6 and d.product_id = a.id_produk
            ) +
            (
                select sum(q.jumlah)
                from transaksi_detail q
                inner join transaksi p on q.id_transaksi = p.id_transaksi
                where q.id_produk = a.id_produk and p.status_transaksi < 5 and p.is_to_school = 1 and p.is_forward = 0 and p.asal = 6
            ) as gudang_palmerah,
            (
                SELECT sum(d.product_quantity)
                FROM order_detail d
                inner join order_scm e on d.id_order = e.id_order
                inner join customer f on e.id_customer = f.id_customer
                inner join gudang_kabupaten g on f.kabupaten = g.kabupaten
                WHERE
                    e.status = 1 and g.id_gudang = 8 and d.product_id = a.id_produk
            ) +
            (
                select sum(q.jumlah)
                from transaksi_detail q
                inner join transaksi p on q.id_transaksi = p.id_transaksi
                where q.id_produk = a.id_produk and p.status_transaksi < 5 and p.is_to_school = 1 and p.is_forward = 0 and p.asal = 8
            ) as gudang_bawen,
            (
                SELECT sum(d.product_quantity)
                FROM order_detail d
                inner join order_scm e on d.id_order = e.id_order
                inner join customer f on e.id_customer = f.id_customer
                inner join gudang_kabupaten g on f.kabupaten = g.kabupaten
                WHERE
                    e.status = 1 and g.id_gudang = 11 and d.product_id = a.id_produk
            ) +
            (
                select sum(q.jumlah)
                from transaksi_detail q
                inner join transaksi p on q.id_transaksi = p.id_transaksi
                where q.id_produk = a.id_produk and p.status_transaksi < 5 and p.is_to_school = 1 and p.is_forward = 0 and p.asal = 11
            ) as gudang_bandung,
            (
                SELECT sum(d.product_quantity)
                FROM order_detail d
                inner join order_scm e on d.id_order = e.id_order
                inner join customer f on e.id_customer = f.id_customer
                inner join gudang_kabupaten g on f.kabupaten = g.kabupaten
                WHERE
                    e.status = 1 and g.id_gudang = 14 and d.product_id = a.id_produk
            ) +
            (
                select sum(q.jumlah)
                from transaksi_detail q
                inner join transaksi p on q.id_transaksi = p.id_transaksi
                where q.id_produk = a.id_produk and p.status_transaksi < 5 and p.is_to_school = 1 and p.is_forward = 0 and p.asal = 14
            ) as gudang_cikarang,
            (
                SELECT sum(d.product_quantity)
                FROM order_detail d
                inner join order_scm e on d.id_order = e.id_order
                inner join customer f on e.id_customer = f.id_customer
                inner join gudang_kabupaten g on f.kabupaten = g.kabupaten
                WHERE
                    e.status = 1 and g.id_gudang = 17 and d.product_id = a.id_produk
            ) +
            (
                select sum(q.jumlah)
                from transaksi_detail q
                inner join transaksi p on q.id_transaksi = p.id_transaksi
                where q.id_produk = a.id_produk and p.status_transaksi < 5 and p.is_to_school = 1 and p.is_forward = 0 and p.asal = 17
            ) as gudang_surabaya,
            (
                SELECT sum(d.product_quantity)
                FROM order_detail d
                inner join order_scm e on d.id_order = e.id_order
                inner join customer f on e.id_customer = f.id_customer
                inner join gudang_kabupaten g on f.kabupaten = g.kabupaten
                WHERE
                    e.status = 1 and g.id_gudang = 19 and d.product_id = a.id_produk
            ) +
            (
                select sum(q.jumlah)
                from transaksi_detail q
                inner join transaksi p on q.id_transaksi = p.id_transaksi
                where q.id_produk = a.id_produk and p.status_transaksi < 5 and p.is_to_school = 1 and p.is_forward = 0 and p.asal = 19
            ) as gudang_gianyar,
            (
                SELECT sum(d.product_quantity)
                FROM order_detail d
                inner join order_scm e on d.id_order = e.id_order
                inner join customer f on e.id_customer = f.id_customer
                inner join gudang_kabupaten g on f.kabupaten = g.kabupaten
                WHERE
                    e.status = 1 and g.id_gudang = 12 and d.product_id = a.id_produk
            ) +
            (
                select sum(q.jumlah)
                from transaksi_detail q
                inner join transaksi p on q.id_transaksi = p.id_transaksi
                where q.id_produk = a.id_produk and p.status_transaksi < 5 and p.is_to_school = 1 and p.is_forward = 0 and p.asal = 12
            ) as gudang_tasikmalaya,
            (
                SELECT sum(d.product_quantity)
                FROM order_detail d
                inner join order_scm e on d.id_order = e.id_order
                inner join customer f on e.id_customer = f.id_customer
                inner join gudang_kabupaten g on f.kabupaten = g.kabupaten
                WHERE
                    e.status = 1 and g.id_gudang = 13 and d.product_id = a.id_produk
            ) +
            (
                select sum(q.jumlah)
                from transaksi_detail q
                inner join transaksi p on q.id_transaksi = p.id_transaksi
                where q.id_produk = a.id_produk and p.status_transaksi < 5 and p.is_to_school = 1 and p.is_forward = 0 and p.asal = 13
            ) as gudang_cirebon
        ";
        $listreport = $this->mod_scm->getStockGudangAll($select);
        $dataReport = [];
        foreach ($listreport as $key => $value) {
            $dataReport["gudang_medan"] = $value->gudang_medan;
            $dataReport["gudang_palmerah"] = $value->gudang_palmerah;
            $dataReport["gudang_bawen"] = $value->gudang_bawen;
            $dataReport["gudang_bandung"] = $value->gudang_bandung;
            $dataReport["gudang_cikarang"] = $value->gudang_cikarang;
            $dataReport["gudang_surabaya"] = $value->gudang_surabaya;
            $dataReport["gudang_gianyar"] = $value->gudang_gianyar;
            $dataReport["gudang_tasikmalaya"] = $value->gudang_tasikmalaya;
            $dataReport["gudang_cirebon"] = $value->gudang_cirebon;
            $this->mod_scm->editStock('report_stock_konfirmasi_gudang', $value->id_produk, $dataReport);
        }
        if ($this->db->trans_status() === true) {
            $this->db->trans_commit();
            $callBack['message'] = "Update report stock konfirmasi gudang successfull";
        } else {
            $this->db->trans_rollback();
            $callBack['message'] = "Update report stock konfirmasi gudang unsuccessfull";
        }
        echo json_encode($callBack, true);
    }

    public function updateStokFinance()
    {
        $this->db->trans_begin();
        $listreport = $this->mod_finance->getReportStock();
        $dataReport = [];
        foreach ($listreport as $key => $value) {
            $dataReport["stok_fisik"] = $value->stok_fisik;
            $dataReport["stok_ip"] = $value->stok_ip;
            $dataReport["stok_konfirmasi"] = $value->stok_konfirmasi;
            $dataReport["stok_booking"] = $value->stok_booking;
            $dataReport["stok_kirim"] = $value->stok_kirim;
            $dataReport["stok_belum_kirim"] = $value->stok_konfirmasi + $value->stok_booking;
            $dataReport["stok_diterima_sekolah"] = $value->stok_diterima_sekolah;
            $dataReport["stok_available"] = $value->stok_fisik - ($value->stok_konfirmasi + $value->stok_booking);
            $dataReport["total_produksi"] = $value->stok_fisik + $value->stok_ip + $value->stok_kirim;
            $dataReport["total_pesanan"] = $value->stok_kirim + $value->stok_konfirmasi + $value->stok_booking;
            $this->mod_finance->editStock('report_stock_finance', $value->id_produk, $dataReport);
        }
        if ($this->db->trans_status() === true) {
            $this->db->trans_commit();
            $callBack['message'] = "Update report stock finance successfull";
        } else {
            $this->db->trans_rollback();
            $callBack['message'] = "Update report stock finance unsuccessfull";
        }
        echo json_encode($callBack, true);
    }

    public function updateRupiahFinance()
    {
        $this->db->trans_begin();
        $listreport = $this->mod_finance->getReportRupiah();
        $dataReport = [];
        foreach ($listreport as $key => $value) {
            $dataReport["rupiah_kirim"] = $value->rupiah_kirim;
            $dataReport["rupiah_konfirmasi"] = $value->rupiah_konfirmasi;
            $dataReport["rupiah_booking"] = $value->rupiah_booking;
            $dataReport["rupiah_belum_kirim"] = $value->rupiah_konfirmasi + $value->rupiah_booking;
            $dataReport["rupiah_diterima_sekolah"] = $value->rupiah_diterima_sekolah;
            $dataReport["rupiah_total_pesanan"] = $value->rupiah_kirim + $value->rupiah_konfirmasi + $value->rupiah_booking;
            $this->mod_finance->editStock('report_stock_finance', $value->id_produk, $dataReport);
        }
        if ($this->db->trans_status() === true) {
            $this->db->trans_commit();
            $callBack['message'] = "Update report rupiah finance successfull";
        } else {
            $this->db->trans_rollback();
            $callBack['message'] = "Update report rupiah finance unsuccessfull";
        }
        echo json_encode($callBack, true);
    }
}
