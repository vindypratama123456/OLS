<?php
defined('BASEPATH') || exit('No direct script access allowed');

class Mod_steam extends MY_Model
{
    public function __construct()
    {
        parent::__construct();
    }

    public function get_comission_sap($sap_no)
    {
        $select = '
            a.id_employee as id_employee,
            b.id as id_mitra_profile,
            c.name as nama,
            d.bank_name as nama_bank,
            d.bank_code as kode_bank,
            d.bank_alias as alias_bank,
            b.bank_account_name as nama_rekening,
            b.bank_account_number as no_rekening,
            a.transfer_date as tgl_transfer,
            a.status as status
        ';
        $this->db->select($select);
        $this->db->from('payout_detail_steam a');
        $this->db->join('mitra_profile b', 'b.id_employee = a.id_employee', $this->typeInner);
        $this->db->join('employee c', 'c.id_employee = b.id_employee', $this->typeInner);
        $this->db->join('master_bank d', 'd.id = b.bank_account_type', $this->typeInner);
        $this->db->where('a.sap_no', $sap_no);
        $this->db->group_by('a.id_employee');

        return $this->db->get()->result_array();
    }

    public function get_comission_sap_detail($sap_no, $id_employee)
    {
        $select = "
            a.id AS id,
            a.`sap_no` AS sap_no,
            a.no_pd AS no_pd,
            a.no_pd_kolektif AS no_pd_kolektif,
            b.reference AS kode_pesanan,
            a.transfer_date AS transfer_date,
            b.total_paid AS nilai_pesanan,
            a.percentage AS persen_komisi,
            a.tax AS persen_pph,
            ROUND(a.tax * (a.percentage * b.total_paid)) AS nilai_pph,
            (ROUND(a.percentage * b.total_paid) - ROUND(a.tax * (a.percentage * b.total_paid))) AS total_amount,
            a.id_order AS id_order,
            a.id_employee AS id_employee,
            d.id AS id_mitra_profile,
            c.name AS nama,
            e.bank_name AS nama_bank,
            e.bank_code AS kode_bank,
            e.bank_alias AS alias_bank,
            d.bank_account_name AS nama_rekening,
            d.bank_account_number AS no_rekening,
            a.transfer_date AS tgl_transfer,
            a.status AS STATUS,
            a.created_date AS created_date
        ";
        $this->db->select($select);
        $this->db->from("payout_detail_steam a");
        $this->db->join("order_steam b", "b.id_order = a.id_order", $this->typeInner);
        $this->db->join("employee c", "c.id_employee = a.id_employee", $this->typeInner);
        $this->db->join("mitra_profile d", "d.id_employee = c.id_employee", $this->typeInner);
        $this->db->join("master_bank e", "e.id = d.bank_account_type", $this->typeInner);
        $this->db->where("a.sap_no", $sap_no);
        $this->db->where("a.id_employee", $id_employee);
        // $this->db->order_by("a.".$this->colNoPD, "asc");

        return $this->db->get()->result_array();
    }

    public function report_comission($id)
    {
        $this->db->select('
            a.id AS id,
            a.id_order AS id_order, 
            b.date_add AS `date_add`,
            b.reference AS reference, 
            c.school_name AS school_name, 
            CONCAT(d.name, "<br> (", d.email, ") <br>", d.telp) AS sales_person,
            d.name AS sales_name,
            d.email AS sales_email,
            d.telp AS sales_phone,
            SUM(b.total_paid) AS total_paid,
            FORMAT((a.percentage * 100), 2) AS percent_comission, 
            sum(round(a.percentage * b.total_paid)) as amount_comission,
            FORMAT((a.tax * 100), 2) AS percent_tax, 
            sum(ROUND(a.tax * (a.percentage * b.total_paid))) as amount_tax,
            sum((ROUND(a.percentage * b.total_paid) - ROUND(a.tax * (a.percentage * b.total_paid)))) AS total_comission, 
            a.created_date AS date_proposed,
            CONCAT(b.category, "<br>(",b.type,")") AS class_name, 
            c.provinsi AS provinsi, 
            c.kabupaten AS kabupaten,
            group_concat(b.`reference`) as notes
        ');
        $this->db->from('payout_detail_steam a');
        $this->db->join('order_steam b', 'b.id_order=a.id_order', 'inner');
        $this->db->join('customer c', 'c.id_customer=b.id_customer', 'inner');
        $this->db->join('employee d', 'd.id_employee=a.id_employee', 'inner');
        $this->db->join('mitra_profile e', 'e.id_employee=d.id_employee', 'inner');
        $this->db->where_in('a.id', $id);
        $this->db->group_by('d.`id_employee`');
        return $this->db->get()->result_array();
    }

    public function report_comission_sap($sap_no)
    {
        $this->db->select('
            a.id AS id,
            a.id_order AS id_order, 
            b.date_add AS `date_add`,
            b.reference AS reference, 
            c.school_name AS school_name, 
            CONCAT(d.name, "<br> (", d.email, ") <br>", d.telp) AS sales_person,
            d.name AS sales_name,
            d.email AS sales_email,
            d.telp AS sales_phone,
            SUM(b.total_paid) AS total_paid,
            FORMAT((a.percentage * 100), 2) AS percent_comission, 
            sum(round(a.percentage * b.total_paid)) as amount_comission,
            FORMAT((a.tax * 100), 2) AS percent_tax, 
            sum(ROUND(a.tax * (a.percentage * b.total_paid))) as amount_tax,
            sum((ROUND(a.percentage * b.total_paid) - ROUND(a.tax * (a.percentage * b.total_paid)))) AS total_comission, 
            a.created_date AS date_proposed,
            CONCAT(b.category, "<br>(",b.type,")") AS class_name, 
            c.provinsi AS provinsi, 
            c.kabupaten AS kabupaten,
            group_concat(b.`reference`) as notes
        ');
        $this->db->from('payout_detail_steam a');
        $this->db->join('order_steam b', 'b.id_order=a.id_order', 'inner');
        $this->db->join('customer c', 'c.id_customer=b.id_customer', 'inner');
        $this->db->join('employee d', 'd.id_employee=a.id_employee', 'inner');
        $this->db->join('mitra_profile e', 'e.id_employee=d.id_employee', 'inner');
        $this->db->where('a.sap_no', $sap_no);
        $this->db->group_by('d.`id_employee`');
        return $this->db->get()->result_array();
    }

    public function get_last_number_sap()
    {
        $this->db->select('sap_no');
        $this->db->from('payout_detail_steam');
        $this->db->group_by('sap_no');
        $this->db->order_by('sap_no', 'desc');
        $this->db->limit(1);
        return $this->db->get()->row_array();
    }

    public function get_customer($search)
    {
        $this->db->select('id_customer as id, school_name as text');
        $this->db->from('customer');
        $this->db->where('active', 1);
        $this->db->where('(school_name like "%'.$search.'%" or email like "%'.$search.'%")');
        return $this->db->get()->result();
    }

    public function check_id_order($reference)
    {
        $this->db->select('*');
        $this->db->from('order_steam');
        // $this->db->where('active', 1);
        $this->db->where('reference', $reference);
        return $this->db->get()->result();
    }

    public function get_sales($search)
    {
        $this->db->select('id_employee as id, concat("[",email,"] ",name) as text');
        $this->db->from('employee');
        $this->db->where('level', 4);
        $this->db->where('active', 1);
        $this->db->where('(name like "%'.$search.'%" or email like "%'.$search.'%")');
        return $this->db->get()->result();
    }

    public function get_data_mitra($id)
    {
        $this->db->select('*');
        $this->db->from('employee a');
        $this->db->join('mitra_profile b', 'b.id_employee=a.id_employee', 'inner');
        $this->db->where('a.id_employee', $id);
        return $this->db->get();
    }

    public function isMitra($email)
    {
        $qRawMitra = 'SELECT level FROM employee WHERE email = ? AND active = ? '.$this->limitOne;
        $qMitra = $this->db->query($qRawMitra, [$email, 1]);

        return $qMitra->row('level') == 4;
    }

    public function getPercentComission($key, $type = 1)
    {
        if (1 == $type) {
            $qPercentage = $this->db->query("SELECT a.percent_comission FROM mitra_profile a INNER JOIN employee b ON b.id_employee=a.id_employee WHERE b.email=".$this->db->escape($key)." $this->limitOne");
        } else {
            $qPercentage = $this->db->query("SELECT percent_comission FROM order_steam WHERE id_order='".$this->db->escape($key)."' $this->limitOne");
        }

        return $qPercentage->row('percent_comission') ?: 0.15;
    }

    public function getPercentTax($email)
    {
        $qPercentTax = $this->db->query("SELECT a.percent_tax FROM mitra_profile a INNER JOIN employee b ON b.id_employee=a.id_employee WHERE b.email=".$this->db->escape($email)." $this->limitOne");

        return $qPercentTax->row('percent_tax');
    }

    public function get_influencer($email)
    {
        $q = "SELECT a.name, a.email, a.telp, b.percent_comission_steam, b.percent_tax FROM employee a INNER JOIN mitra_profile b ON a.id_employee=b.id_employee WHERE a.email=?";
        $influencer = $this->db->query($q, [$email]);

        if($influencer->num_rows() > 0)
        {
            $inf = $influencer->row();
            $data_influencer = array(
                'nama' => $inf->name,
                'email' => $inf->email,
                'telpon' => $inf->telp,
                'percentage' => $inf->percent_comission_steam,
                'percent_tax' => $inf->percent_tax
            );
            return $data_influencer;
        }
        return false;
    }

    public function getReferral($emailSales)
    {
        $qRawRef = "SELECT a.code_referral AS code  FROM mitra_profile a INNER JOIN employee b ON b.id_employee=a.id_employee WHERE b.email=? AND b.level=? $this->limitOne";
        $qReferral = $this->db->query($qRawRef, [$emailSales, 4]);
        if ($qReferral->row('code') !== null) {
            $qEmployee = $this->db->query("SELECT name, email, telp FROM employee WHERE code=".$this->db->escape($qReferral->row('code'))." $this->limitOne");
            $rData = $qEmployee->row();
            if ($rData) {
                $dEmployee = [
                    'nama' => $rData->name,
                    'email' => $rData->email,
                    'telpon' => $rData->telp,
                ];

                return $dEmployee;
            }

            return false;
        }

        return false;
    }

    public function isHaveSales($idOrder)
    {
        $qSales = $this->db->query("SELECT sales_referer FROM order_steam WHERE id_order=".$this->db->escape($idOrder)." $this->limitOne");

        return $qSales->row('sales_referer') !== null;
    }

    public function isHaveReferral($emailSales)
    {
        $qRawMitra = "SELECT a.code_referral FROM mitra_profile a INNER JOIN employee b ON b.id_employee=a.id_employee WHERE b.email=? AND b.level=? $this->limitOne";
        $qMitra = $this->db->query($qRawMitra, [$emailSales, 4]);

        return $qMitra->row('code_referral') !== null;
    }

    public function isHaveNPWP($emailSales)
    {
        $qRawNPMP = "SELECT a.no_npwp FROM mitra_profile a INNER JOIN employee b ON b.id_employee=a.id_employee WHERE b.email=? AND b.level=? $this->limitOne";
        $qNPMP = $this->db->query($qRawNPMP, [$emailSales, 4]);

        return $qNPMP->row('no_npwp') !== null;
    }

    public function isInPayout($idOrder)
    {
        $qSales = $this->db->query("SELECT id_order FROM payout_detail_steam WHERE id_order=".$this->db->escape($idOrder)." $this->limitOne");

        return $qSales->row($this->colIdOrder) !== null;
    }

    public function isOrderExist($idOrder)
    {
        $qRawSales = "SELECT id_order FROM payout_detail_steam WHERE id_order=? AND type=? $this->limitOne";
        $qSales = $this->db->query($qRawSales, [$idOrder, 1]);

        return $qSales->row($this->colIdOrder) !== null;
    }

    public function addHistory($idPayout, $idEmployee, $idStatus, $notes = '')
    {
        $data = [
            'id_payout' => $idPayout,
            'id_employee' => $idEmployee,
            'id_payout_status' => $idStatus,
            'notes' => $notes,
            'created_date' => date('Y-m-d H:i:s'),
        ];
        $insert = $this->db->insert('payout_detail_steam_history', $data);
        if ($insert) {
            return true;
        }

        return false;
    }

    public function isCompletePaidoff($idOrder)
    {
        $qPaid = $this->db->query("SELECT direct_status, referral_email, referral_status FROM payout WHERE id_order=".$this->db->escape($idOrder)." $this->limitOne");
        if ($qPaid->row('referral_email') !== null) {
            if ($qPaid->row('direct_status') == 1 && $qPaid->row('referral_status') == 1) {
                return true;
            }
        } elseif ($qPaid->row('direct_status') == 1) {
            return true;
        }

        return false;
    }

    public function getComissionOrder(
        $select = 'payout_detail_steam.*, order_steam.*, customer.*',
        $where = null,
        $orderBy = null,
        $groupBy = null,
        $limit = null
    ) {
        $this->db->select($select);
        $this->db->from("payout_detail_steam");
        $this->db->join("payout_state", "payout_detail_steam.status = payout_state.id", "left");
        $this->db->join("order_steam", "payout_detail_steam.id_order = order_steam.id_order", "left");
        $this->db->join("customer", "order_steam.id_customer = customer.id_customer", "left");
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

    public function getComissionByEmployee($idEmployee, $where)
    {
        $query = $this->db->query("
            SELECT
                sum(
                    if('$idEmployee' = payout_detail_steam.id_employee, payout_detail_steam.percentage * order_steam.total_paid, 0) -
                    ceil(if('$idEmployee' = payout_detail_steam.id_employee, payout_detail_steam.percentage * order_steam.total_paid, 0) * payout_detail_steam.tax)
                ) as total
            FROM payout_detail_steam 
            LEFT JOIN order_steam on order_steam.id_order = payout_detail_steam.id_order
            WHERE 1 and (payout_detail_steam.id_employee = ".$this->db->escape($idEmployee).") and $where
        ");

        return $query->result();
    }

    public function getSales($select, $where = null, $order_by = null)
    {
        $this->db->select($select);
        $this->db->from('employee');
        $this->db->join('mitra_profile', 'employee.id_employee = '.'mitra_profile'.'.id_employee',
            $this->typeInner);
        $this->db->where("employee.active", 1);
        $this->db->where("employee.level", 4);
        $this->db->where("employee.code <> ''");
        $this->db->where('mitra_profile'.".is_activated", 1);
        if ($where) {
            $this->db->where($where);
        }
        if ($order_by) {
            $this->db->order_by($order_by);
        }

        return $this->db->get()->result();
    }

    public function getPayoutComission($noPD)
    {
        $select = '
            a.id_employee as id_employee,
            b.id as id_mitra_profile,
            c.name as nama,
            d.bank_name as nama_bank,
            d.bank_code as kode_bank,
            d.bank_alias as alias_bank,
            b.bank_account_name as nama_rekening,
            b.bank_account_number as no_rekening,
            a.transfer_date as tgl_transfer,
            a.status as status
        ';
        $this->db->select($select);
        $this->db->from('payout_detail_steam a');
        $this->db->join('mitra_profile b', 'b.id_employee = a.id_employee', $this->typeInner);
        $this->db->join('employee c', 'c.id_employee = b.id_employee', $this->typeInner);
        $this->db->join('master_bank d', 'd.id = b.bank_account_type', $this->typeInner);
        $this->db->where('a.'.$this->colNoPD, $noPD);
        $this->db->group_by('a.id_employee');

        return $this->db->get()->result_array();
    }

    public function getPayoutComissionDetail($noPD, $idEmployee)
    {
        $select = "
            a.id as id,
            a.no_pd as no_pd,
            a.no_pd_kolektif as no_pd_kolektif,
            b.reference as kode_pesanan,
            a.transfer_date as transfer_date,
            b.total_paid as nilai_pesanan,
            a.percentage as persen_komisi,
            a.tax as persen_pph,
            ROUND(a.tax * (a.percentage * b.total_paid)) as nilai_pph,
            (ROUND(a.percentage * b.total_paid) - ROUND(a.tax * (a.percentage * b.total_paid))) as total_amount,
            a.id_order as id_order,
            a.id_employee as id_employee,
            d.id as id_mitra_profile,
            c.name as nama,
            e.bank_name as nama_bank,
            e.bank_code as kode_bank,
            e.bank_alias as alias_bank,
            d.bank_account_name as nama_rekening,
            d.bank_account_number as no_rekening,
            a.transfer_date as tgl_transfer,
            a.status as status,
            a.created_date as created_date
        ";
        $this->db->select($select);
        $this->db->from("payout_detail_steam a");
        $this->db->join("order_steam b", "b.id_order = a.id_order", $this->typeInner);
        $this->db->join("employee c", "c.id_employee = a.id_employee", $this->typeInner);
        $this->db->join("mitra_profile d", "d.id_employee = c.id_employee", $this->typeInner);
        $this->db->join("master_bank e", "e.id = d.bank_account_type", $this->typeInner);
        $this->db->where("a.".$this->colNoPD, $noPD);
        $this->db->where("a.id_employee", $idEmployee);
        $this->db->order_by("a.".$this->colNoPD, "asc");

        return $this->db->get()->result_array();
    }

    public function getPayoutDetail($noPD)
    {
        $select = "
            a.id as id,
            a.no_pd as no_pd,
            a.no_pd_kolektif as no_pd_kolektif,
            GROUP_CONCAT('#',b.reference SEPARATOR ', ') as kode_pesanan,
            a.transfer_date as transfer_date,
            b.total_paid as nilai_pesanan,
            a.percentage as persen_komisi,
            a.tax as persen_pph,
            SUM(ROUND(a.tax * (a.percentage * b.total_paid))) as nilai_pph,
            SUM(ROUND(a.percentage * b.total_paid) - ROUND(a.tax * (a.percentage * b.total_paid))) as total_amount,
            a.id_order as id_order,
            a.id_employee as id_employee,
            d.id as id_mitra_profile,
            c.name as nama,
            e.bank_name as nama_bank,
            e.bank_code as kode_bank,
            e.bank_alias as alias_bank,
            d.bank_account_name as nama_rekening,
            d.bank_account_number as no_rekening,
            a.transfer_date as tgl_transfer,
            a.status as status,
            a.created_date as created_date
        ";
        $this->db->select($select);
        $this->db->from("payout_detail_steam a");
        $this->db->join("order_steam b", "b.id_order = a.id_order", $this->typeInner);
        $this->db->join("employee c", "c.id_employee = a.id_employee", $this->typeInner);
        $this->db->join("mitra_profile d", "d.id_employee = c.id_employee", $this->typeInner);
        $this->db->join("master_bank e", "e.id = d.bank_account_type", $this->typeInner);
        $this->db->where("a.".$this->colNoPD, $noPD);
        $this->db->group_by("a.id_employee");
        $this->db->order_by("a.id_employee", "asc");

        return $this->db->get()->result();
    }

    public function getPayoutComissionMitra($idPayoutComission)
    {
        $select = "
            SUM(a.comission_amount) as total_comission_amount, 
            a.id_employee as id_employee, 
            a.tax_percent as tax_percent, 
            b.id as id_mitra_profile, 
            b.bank_account_name as nama_rekening, 
            b.bank_account_number as no_rekening 
        ";
        $this->db->select($select);
        $this->db->from("payout_comission_detail a");
        $this->db->join("mitra_profile b", "a.id_employee = b.id_employee", $this->typeInner);
        $this->db->where("a.id_payout_comission", $idPayoutComission);
        $this->db->group_by("a.id_employee");

        return $this->db->get()->result_array();
    }

    public function getListPayoutByDate($date)
    {
        $this->db->select('c.id_order,
                    a.id_payout, 
                    a.id_payout_comission, 
                    d.id AS id_mitra_profile, 
                    a.comission_amount, 
                    a.tax_percent, 
                    a.is_utama');
        $this->db->from('payout_comission_detail a');
        $this->db->join('payout_comission b', 'b.id=a.id_payout_comission', $this->typeInner);
        $this->db->join('payout c', 'c.id=a.id_payout', $this->typeInner);
        $this->db->join('mitra_profile d', 'd.id_employee=a.id_employee', $this->typeInner);
        $this->db->where('b.transfer_date', $date);
        $this->db->where('b.id_payout_comission_status', 1);
        $this->db->order_by('b.created_date', 'asc');

        return $this->db->get()->result();
    }

    public function getDetailPrintTax($noPD, $idMitra)
    {
        $select = "
            SUM(ROUND(a.percentage * d.total_paid)) as comission_amount,
            a.tax as comission_tax,
            a.no_pd as no_pesanan_dana,
            c.identity_code as mitra_nik,
            c.no_npwp as mitra_npwp,
            c.address_npwp AS mitra_address_npwp,
            c.address as mitra_address, 
            b.name as mitra_name,
            a.transfer_date as transfer_date,
            a.no_pph as no_pph
        ";
        $this->db->select($select);
        $this->db->from("payout_detail_steam a");
        $this->db->join("employee b", "b.id_employee = a.id_employee", $this->typeInner);
        $this->db->join("mitra_profile c", "c.id_employee = b.id_employee", $this->typeInner);
        $this->db->join("order_steam d", "d.id_order = a.id_order", $this->typeInner);
        $this->db->where("a.".$this->colNoPD, $noPD);
        $this->db->where("c.id", $idMitra);

        return $this->db->get()->result_array();
    }

    public function getListPDPPh()
    {
        $this->db->select($this->colNoPD);
        $this->db->from('payout_detail_steam');
        $this->db->where('no_pd >=', 10482088);
        $this->db->where('no_pph', null);
        $this->db->group_by($this->colNoPD);
        $this->db->order_by($this->colNoPD, 'asc');

        return $this->db->get()->result_array();
    }

    public function getListMitraByPD($noPD = array())
    {
        $select = "a.no_pd, a.id_employee";
        $this->db->select($select);
        $this->db->from("payout_detail_steam a");
        $this->db->where_in("a.".$this->colNoPD, $noPD);
        $this->db->order_by('a.no_pd asc, a.id_employee asc');

        return $this->db->get()->result_array();
    }

    public function isHaveDeduction($idPayout)
    {
        $qDeduction = $this->db->query("SELECT id_payout FROM payout_deduction WHERE id_payout=".$this->db->escape($idPayout));

        return $qDeduction->num_rows();
    }

    public function getListDeduction($idPayout)
    {
        $this->db->select('a.id_payout, a.id_deduction, b.name, a.amount');
        $this->db->from('payout_deduction a');
        $this->db->join('master_deduction b', 'b.id=a.id_deduction', $this->typeInner);
        $this->db->where('a.id_payout', $idPayout);
        $this->db->order_by('a.id_deduction', 'asc');

        return $this->db->get()->result();
    }

    public function getNumber($column = 'no_pd')
    {
        $qMaxNumber = $this->db->query("SELECT MAX(DISTINCT $column) AS nomor FROM payout_detail_steam");

        return $qMaxNumber->row('nomor') + 1;
    }

    public function updateComission($idOrder, $data)
    {
        $this->db->where($this->colIdOrder, $idOrder);
        $this->db->where('percentage !=', '0.010');
        if ($this->db->update('payout_detail_steam', $data)) {
            return true;
        }

        return false;
    }

    public function updateComissionData($idOrder, $id_employee, $data)
    {
        $this->db->where('id_order', $idOrder);
        $this->db->where('id_employee', $id_employee);
        if ($this->db->update('payout_detail_steam', $data)) {
            return true;
        }

        return false;
    }

    public function getIdPayout($idOrder, $type = 1)
    {
        $qPayout = $this->db->query("SELECT `id` FROM `payout_detail_steam` WHERE `id_order`=".$this->db->escape($idOrder)." AND `type`=".$this->db->escape($type)." $this->limitOne");

        return $qPayout->row('id');
    }

    public function getStatus($idOrder, $type = 1)
    {
        $qPayout = $this->db->query("SELECT `status` FROM `payout_detail_steam` WHERE `id_order`=".$this->db->escape($idOrder)." AND `type`=".$this->db->escape($type)." $this->limitOne");

        return $qPayout->row('id');
    }

    public function getNomorPPh()
    {
        $qRawNomor = "SELECT MIN(`nomor`) AS `nomor_pph` FROM `master_pph` WHERE `tahun`=? AND `status`=? $this->limitOne";
        $qNomor = $this->db->query($qRawNomor, [$this->periode, 1]);

        return $qNomor->row('nomor_pph');
    }

    public function updateNomorPPh($nomor, $data)
    {
        $this->db->where('nomor', $nomor);
        $this->db->where('tahun', $this->periode);
        if ($this->db->update('master_pph', $data)) {
            return true;
        }

        return false;
    }

    public function updatePayoutPPh($noPD, $idEmployee, $data)
    {
        $this->db->where($this->colNoPD, $noPD);
        $this->db->where('id_employee', $idEmployee);
        if ($this->db->update('payout_detail_steam', $data)) {
            return true;
        }

        return false;
    }

    public function getListPPhByDate($startDate, $endDate)
    {
        $select = 'a.id AS id_payout, 
                   a.no_pd AS no_pd, 
                   b.name AS mitra_name,
                   c.id AS id_mitra,   
                   a.transfer_date AS transfer_date, 
                   a.no_pph AS no_pph';
        $this->db->select($select);
        $this->db->from('payout_detail_steam a');
        $this->db->join('employee b', 'b.id_employee = a.id_employee', $this->typeInner);
        $this->db->join('mitra_profile c', 'c.id_employee = b.id_employee', $this->typeInner);
        $this->db->join('order_steam d', 'd.id_order = a.id_order', $this->typeInner);
        $this->db->where('a.status', 4);
        $this->db->where('a.no_pph !=', '');
        $this->db->where('a.no_pph !=', null);
        $this->db->where('a.transfer_date >=', $startDate);
        $this->db->where('a.transfer_date <=', $endDate);
        $this->db->group_by('a.no_pph, a.id_employee');
        $this->db->order_by('transfer_date asc, no_pd asc');

        return $this->db->get()->result_array();
    }

    public function getCompanyName($noPD)
    {
        $this->db->select('IF(b.category="Kelas 1" OR b.category="Kelas 2" OR b.category="Kelas 3" OR b.category="Kelas 4" OR b.category="Kelas 5" OR b.category="Kelas 6" OR YEAR(b.date_add)>2018, "PT. Mitra Edukasi Nusantara (MEN)", "PT. Gramedia") AS company');
        $this->db->from('payout_detail_steam a');
        $this->db->join('order_steam b', 'b.id_order = a.id_order', $this->typeInner);
        $this->db->where('a.no_pd', $noPD);
        $this->db->limit(1);

        return $this->db->get()->row('company');
    }
}
