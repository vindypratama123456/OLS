<?php
defined('BASEPATH') || exit('No direct script access allowed');

class Mod_order extends CI_Model
{
    public function __construct()
    {
        parent::__construct();
    }

    public function getListStatus($idOrder)
    {
        return $this->db->select('a.date_add AS tanggal, b.id_order_state AS id_state, b.name AS order_state, c.name AS employee')
            ->from('order_history a')
            ->join('order_state b', 'b.id_order_state=a.id_order_state', 'left')
            ->join('employee c', 'c.id_employee=a.id_employee', 'left')
            ->where('a.id_order', $idOrder)
            ->order_by('a.id_order_history', 'asc')
            ->get()
            ->result();
    }

    public function getListHistory($idOrder)
    {
        return $this->db->select('a.created_at AS tanggal,
                                  a.product_name AS produk,
                                  a.quantity_before AS sebelum,
                                  a.quantity_after AS setelah,
                                  b.name AS admin')
            ->from('order_detail_history a')
            ->join('employee b', 'b.id_employee=a.created_by', 'left')
            ->where('a.id_order', $idOrder)
            ->order_by('a.id', 'desc')
            ->get()
            ->result();
    }

    public function getListAllBooks()
    {
        return $this->db->select('`o`.`id_product` AS `id`,
                                  `o`.`kode_buku` AS `kode_buku`,
                                  `o`.`name` AS `judul_buku`,
                                  `o`.`reference` AS `isbn`,
                                  `p`.`name` AS `kategori`,
                                  (SELECT SUM(`x`.`product_quantity`) FROM `order_detail` `x` WHERE `x`.`product_id`=`o`.`id_product`) AS `total`')
            ->from('`product` `o`')
            ->join('`category` `p`', '`p`.`id_category`=`o`.`id_category_default`', 'left')
            ->where('`o`.`active`', 1)
            ->where('`o`.`kode_buku` IS NOT NULL')
            ->where('`o`.`id_product` IN (SELECT `product_id` FROM `order_detail`)')
            ->order_by('`p`.`name` asc, `o`.`name` asc')
            ->get()
            ->result();
    }

    public function getOrderReadyToShip()
    {
        $this->db->select('a.id_order AS id_order,
                           a.reference AS reference,
                           b.email AS email,
                           b.no_npsn AS no_npsn,
                           b.school_name AS school_name,
                           b.provinsi AS provinsi,
                           b.kabupaten AS kabupaten,
                           b.kecamatan AS kecamatan,
                           c.name AS order_state,
                           c.label AS label,
                           a.total_paid AS total_paid,
                           a.date_add AS date_add,
                           c.name as status_name,
                           c.label as status_label');
        $this->db->from('orders a');
        $this->db->join('customer b', 'b.id_customer=a.id_customer', 'left');
        $this->db->join('order_state c', 'c.id_order_state=a.current_state', 'left');
        $this->db->where('a.current_state IN (5, 6, 7, 8, 9)');
        $this->db->where('a.is_intan !=', 1);
        $this->db->order_by('a.id_order', 'ASC');
        $q = $this->db->get()->result();
        return $q;
    }

    public function getSalesPerson($idKorwil, $active=false)
    {
        if ($idKorwil && is_numeric($idKorwil)) {
            $this->db->select('id_employee, code, name, email, telp');
            $this->db->from('employee');
            $this->db->where("email IN (SELECT aa.email_sales FROM korwil_sales aa WHERE aa.email_korwil = (SELECT bb.email FROM employee bb WHERE bb.id_employee = " . $idKorwil . "))");
            if ($active) {
                $this->db->where('active', 1);
            }
            $this->db->order_by('name', 'ASC');
            return $this->db->get()->result();
        }
        return false;
    }

    public function getKorwil($kabKota, $select = "a.email, a.code, a.name, a.level, a.telp, a.id_employee")
    {
        $this->db->select($select);
        $this->db->from('employee a');
        $this->db->join('employee_kabupaten_kota b', 'b.id_employee=a.id_employee', 'inner');
        $this->db->where('a.level', 3);
        $this->db->where('a.active', 1);
        $this->db->where('b.kabupaten_kota', $kabKota);
        $this->db->order_by('a.id_employee', 'desc');
        return $this->db->get()->result_array();
    }

    public function getKorwilById($kabKota)
    {
        $qRaw = "SELECT a.id_employee AS id_employee FROM employee a INNER JOIN employee_kabupaten_kota b ON b.id_employee=a.id_employee WHERE a.level=? AND a.active=? AND b.kabupaten_kota=? ORDER BY a.id_employee DESC LIMIT 1";
        $query = $this->db->query($qRaw, [3, 1, $kabKota]);
        if ($query->num_rows() > 0) {
            return $query->row('id_employee');
        }
        return false;
    }

    public function getRSM($kabKota)
    {
        $this->db->select('a.email, a.name');
        $this->db->from('employee a');
        $this->db->join('employee_kabupaten_kota b', 'b.id_employee=a.id_employee', 'inner');
        $this->db->where('a.level', 8);
        $this->db->where('a.active', 1);
        $this->db->where('b.kabupaten_kota', $kabKota);
        return $this->db->get()->result_array();
    }

    public function getOmset($idEmployee, $offline = false)
    {
        $this->db->select("sum(a.total_paid) as total_omset");
        $this->db->from("orders a");
        $this->db->join("customer b", "a.id_customer = b.id_customer", "left");
        if ($offline) {
            $this->db->where("a.is_offline", 1);
        } else {
            $this->db->where("a.is_offline !=", 1);
        }
        $this->db->where("b.kabupaten IN (SELECT aa.kabupaten_kota FROM employee_kabupaten_kota aa WHERE aa.id_employee = '$idEmployee')");
        $this->db->where("current_state NOT IN (1, 2, 4)");
        return $this->db->get()->result_array();
    }

    public function getCreatedOrder($idEmployee, $offline = false)
    {
        $this->db->select("count(a.id_order) as order_terbuat");
        $this->db->from("orders a");
        $this->db->join("customer b", "a.id_customer = b.id_customer", "left");
        if ($offline) {
            $this->db->where("a.is_offline", 1);
        } else {
            $this->db->where("a.is_offline !=", 1);
        }
        $this->db->where("b.kabupaten IN (SELECT aa.kabupaten_kota FROM employee_kabupaten_kota aa WHERE aa.id_employee = '$idEmployee')");
        return $this->db->get()->result_array();
    }

    public function getConfirmedOrder($idEmployee, $offline = false)
    {
        $this->db->select("count(a.id_order) as order_terkonfirmasi");
        $this->db->from("orders a");
        $this->db->join("customer b", "a.id_customer = b.id_customer", "left");
        if ($offline) {
            $this->db->where("a.is_offline", 1);
        } else {
            $this->db->where("a.is_offline !=", 1);
        }
        $this->db->where("b.kabupaten IN (SELECT aa.kabupaten_kota FROM employee_kabupaten_kota aa WHERE aa.id_employee = '$idEmployee')");
        $this->db->where("current_state NOT IN (1, 2, 4)");
        return $this->db->get()->result_array();
    }

    public function getListBooks($idOrder, $zona, $categoryBooks, $classBooks)
    {
        $qRawBooksOn = "
                SELECT  `o`.`id_product` AS `id_product`,
                        `o`.`kode_buku` AS `kode_buku`,
                        `o`.`reference` AS `isbn`,
                        `o`.`name` AS `judul`,
                        ROUND(`o`.`price_" . $zona . "`) AS `harga`,
                        `p`.`name` AS `kelas`,
                        `p`.`id_category` AS `category_id`, 
                        `q`.`name` AS type,
                        `q`.`id_category` AS type_id
                FROM    `product` `o`
                JOIN    `category` `p` ON `p`.`id_category`=`o`.`id_category_default`
                JOIN    `category` `q` ON `q`.`id_category`=`p`.`id_parent`
                WHERE   1
                AND     `o`.`enable` = 1
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
                AND `o`.`id_product` NOT IN (
                    SELECT `xx`.`product_id`
                    FROM `order_detail` `xx`
                    WHERE 1
                    AND `xx`.`id_order` = ?
                )
                AND `o`.`kode_buku` IS NOT NULL
                ORDER BY `p`.`name` ASC, `o`.`sort_order` ASC";
        $query = $this->db->query($qRawBooksOn, [1, $categoryBooks, [$classBooks], $idOrder]);
        if ($query) {
            return $query->result();
        } else {
            return $this->db->error();
        }
    }

    public function getListBooksOffline($zona, $jenjang)
    {
        $in_category = [];
        switch ($jenjang) {
            case '1-6':
                $in_category = [7, 10];
                break;
            case '7-9':
                $in_category = [13, 14, 15];
                break;
            case '10-12':
                $in_category = [16, 17, 18];
                break;
        }
        $qRawBooksOff = "
                SELECT  `o`.`id_product` AS `id_product`,
                        `o`.`kode_buku` AS `kode_buku`,
                        `o`.`reference` AS `isbn`,
                        `o`.`name` AS `judul`,
                        ROUND(`o`.`price_" . $zona . "`) AS `harga`,
                        `p`.`name` AS `kelas`,
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
                ORDER BY `p`.`name` ASC, `o`.`sort_order` ASC";
        $query = $this->db->query($qRawBooksOff, [1, 3, $in_category]);
        if ($query) {
            return $query->result_array();
        } else {
            return $this->db->error();
        }
    }

    public function getListBooksOfflineKTSP($zona, $jenjang)
    {
        switch ($jenjang) {
            case '1-6':
                $in_category = [38, 39, 40, 41, 42, 43];
                break;
            case '7-9':
                $in_category = [44, 45, 46];
                break;
            case '10-12':
                $in_category = [47, 48, 49];
                break;
        }
        $qRawKTSP = "
                SELECT  `o`.`id_product` AS `id_product`,
                        `o`.`kode_buku` AS `kode_buku`,
                        `o`.`reference` AS `isbn`,
                        `o`.`name` AS `judul`,
                        ROUND(`o`.`price_" . $zona . "`) AS `harga`,
                        `p`.`name` AS `kelas`,
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
                ORDER BY `p`.`name` ASC, `o`.`sort_order` ASC";
        $query = $this->db->query($qRawKTSP, [1, 2, $in_category]);
        if ($query) {
            return $query->result_array();
        } else {
            return $this->db->error();
        }
    }

    public function getAuto($type, $key)
    {
        $this->db->select($type);
        $this->db->like($type, $key);
        $this->db->order_by($type, 'ASC');
        $this->db->distinct();
        $query = $this->db->get('customer');
        $row_set = [];
        if ($query->num_rows() > 0) {
            foreach ($query->result_array() as $row) {
                $row_set[] = htmlentities(stripslashes($row[$type]));
            }
            echo json_encode($row_set);
        }
        return false;
    }

    public function getListProducts($idOrder)
    {
        $this->db->select('a.*, b.reference AS isbn, b.kode_buku AS pkode_buku, c.name AS kelas, d.name AS type, d.alias AS type_alias, c.id_parent AS category, b.id_category_default AS class');
        $this->db->from('order_detail a');
        $this->db->join('product b', 'b.id_product=a.product_id', 'inner');
        $this->db->join('category c', 'c.id_category=b.id_category_default', 'inner');
        $this->db->join('category d', 'c.id_parent=d.id_category', 'inner');
        $this->db->where('a.id_order', $idOrder);
        $this->db->order_by('a.id_order_detail asc, c.name asc');
        return $this->db->get()->result();
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

    public function isCoverageArea($kabKota)
    {
        $this->db->select('kabupaten');
        $this->db->from('gudang_kabupaten');
        $this->db->where('kabupaten', $kabKota);
        $query = $this->db->get();
        return ($query->num_rows() > 0) ? true : false;
    }

    public function getRecommendedWarehouse($kabupaten)
    {
        $this->db->select('a.*, b.kabupaten');
        $this->db->from('master_gudang a');
        $this->db->join('gudang_kabupaten b', 'b.id_gudang = a.id_gudang', 'inner');
        $this->db->where('b.kabupaten', $kabupaten);
        return $this->db->get()->result()[0];
    }

    public function isInComission($idOrder)
    {
        $this->db->select('id_order');
        $this->db->from('payout_detail');
        $this->db->where('id_order', $idOrder);
        $qOrder = $this->db->get();
        return ($qOrder->num_rows() > 0) ? true : false;
    }

    public function isInSCMProcess($idOrder)
    {
        $this->db->select('id_order');
        $this->db->from('order_scm');
        $this->db->where('id_order', $idOrder);
        $this->db->where('status >', 1);
        $qSCM = $this->db->get();
        return ($qSCM->num_rows() > 0) ? true : false;
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
        $this->db->where('a.`tgl_sampai` < DATE_SUB(NOW(), INTERVAL "6" MONTH)');   
        /**
         * 2019-06-19
         * Vindy Pratama
         * Menambahkan query kriteria pesanan yang tidak di blokir
         * Jika pesanan dibuat pada tahun 2016
         * awal
         */
        $this->db->where_not_in('a.id_order', 'SELECT id_order FROM orders h WHERE YEAR(h.`date_add`)=2016');
        /**
         * akhir
         */
        
        /**
         * 2019-06-18
         * Vindy Pratama
         * Menambahkan query kriteria pesanan yang tidak di blokir
         * Jika pesanan dibuat pada tahun 2016
         * Jika pesanan SD tahun 2018
         * awal
         */
        // $this->db->where_not_in('a.id_order', 'SELECT id_order FROM orders m INNER JOIN `customer` n ON m.`id_customer`=n.`id_customer` WHERE m.jenjang="1-6" AND YEAR(m.`date_add`)=2018 UNION SELECT id_order FROM orders h WHERE YEAR(h.`date_add`)=2016');
        /**
         * akhir
         */
        $this->db->where('b.`id_customer`', $id_customer);
        $this->db->group_by('b.`id_customer`'); 
        $query = $this->db->get();
        return $query;
    }
	
	public function getCategoryName($idCategory)
    {
        return $this->db->select('name')
            ->from('category')
            ->where('id_category', $idCategory)
            ->get()
            ->result();
    }

    public function check_kontrak($id_employee)
    {
        $this->db->select('*');
        $this->db->from('mitra_kontrak');
        $this->db->where("'".date('Y-m-d')."' BETWEEN mikon_tanggal AND mikon_tanggal_akhir", null, false);
        $this->db->where('mikon_employee_id', $id_employee);
        // return $this->db->get()->num_rows();
        return 1;
    }
}
