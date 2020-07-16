<?php
defined('BASEPATH') || exit('No direct script access allowed');

class Mod_comission extends MY_Model
{
    public function __construct()
    {
        parent::__construct();
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
            $qPercentage = $this->db->query("SELECT a.percent_comission FROM $this->tblMitraProfile a INNER JOIN employee b ON b.id_employee=a.id_employee WHERE b.email=".$this->db->escape($key)." $this->limitOne");
        } else {
            $qPercentage = $this->db->query("SELECT percent_comission FROM orders WHERE id_order='".$this->db->escape($key)."' $this->limitOne");
        }

        return $qPercentage->row('percent_comission') ?: 0.15;
    }

    public function getPercentTax($email)
    {
        $qPercentTax = $this->db->query("SELECT a.percent_tax FROM $this->tblMitraProfile a INNER JOIN employee b ON b.id_employee=a.id_employee WHERE b.email=".$this->db->escape($email)." $this->limitOne");

        return $qPercentTax->row('percent_tax');
    }

    public function getReferral($emailSales)
    {
        $qRawRef = "SELECT a.code_referral AS code  FROM $this->tblMitraProfile a INNER JOIN employee b ON b.id_employee=a.id_employee WHERE b.email=? AND b.level=? $this->limitOne";
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
        $qSales = $this->db->query("SELECT sales_referer FROM orders WHERE id_order=".$this->db->escape($idOrder)." $this->limitOne");

        return $qSales->row('sales_referer') !== null;
    }

    public function isHaveReferral($emailSales)
    {
        $qRawMitra = "SELECT a.code_referral FROM $this->tblMitraProfile a INNER JOIN employee b ON b.id_employee=a.id_employee WHERE b.email=? AND b.level=? $this->limitOne";
        $qMitra = $this->db->query($qRawMitra, [$emailSales, 4]);

        return $qMitra->row('code_referral') !== null;
    }

    public function isHaveNPWP($emailSales)
    {
        $qRawNPMP = "SELECT a.no_npwp FROM $this->tblMitraProfile a INNER JOIN employee b ON b.id_employee=a.id_employee WHERE b.email=? AND b.level=? $this->limitOne";
        $qNPMP = $this->db->query($qRawNPMP, [$emailSales, 4]);

        return $qNPMP->row('no_npwp') !== null;
    }

    public function isInPayout($idOrder)
    {
        $qSales = $this->db->query("SELECT id_order FROM payout_detail WHERE id_order=".$this->db->escape($idOrder)." $this->limitOne");

        return $qSales->row($this->colIdOrder) !== null;
    }

    public function isOrderExist($idOrder)
    {
        $qRawSales = "SELECT id_order FROM payout_detail WHERE id_order=? AND type=? $this->limitOne";
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
        $insert = $this->db->insert('payout_history', $data);
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
        $select = 'payout_detail.*, orders.*, customer.*',
        $where = null,
        $orderBy = null,
        $groupBy = null,
        $limit = null
    ) {
        $this->db->select($select);
        $this->db->from($this->tblPayout."");
        $this->db->join("payout_state", $this->tblPayout.".status = payout_state.id", "left");
        $this->db->join("orders", $this->tblPayout.".id_order = orders.id_order", "left");
        $this->db->join("customer", "orders.id_customer = customer.id_customer", "left");
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
                    if('$idEmployee' = payout_detail.id_employee, payout_detail.percentage * orders.total_paid, 0) -
                    ceil(if('$idEmployee' = payout_detail.id_employee, payout_detail.percentage * orders.total_paid, 0) * payout_detail.tax)
                ) as total
            FROM payout_detail 
            LEFT JOIN orders on orders.id_order = payout_detail.id_order
            WHERE 1 and (payout_detail.id_employee = ".$this->db->escape($idEmployee).") and $where
        ");

        return $query->result();
    }

    public function getSales($select, $where = null, $order_by = null)
    {
        $this->db->select($select);
        $this->db->from('employee');
        $this->db->join($this->tblMitraProfile, 'employee.id_employee = '.$this->tblMitraProfile.'.id_employee',
            $this->typeInner);
        $this->db->where("employee.active", 1);
        $this->db->where("employee.level", 4);
        $this->db->where("employee.code <> ''");
        $this->db->where($this->tblMitraProfile.".is_activated", 1);
        if ($where) {
            $this->db->where($where);
        }
        if ($order_by) {
            $this->db->order_by($order_by);
        }

        return $this->db->get()->result();
    }

    public function check_kontrak($id_employee)
    {
        $this->db->select('*');
        $this->db->from('mitra_kontrak');
        $this->db->where("'".date('Y-m-d')."' BETWEEN mikon_tanggal AND mikon_tanggal_akhir", null, false);
        $this->db->where('mikon_employee_id', $id_employee);
        return $this->db->get()->result_array();
    }

    public function check_kontrak2($employee_id, $konfirmasi_tanggal)
    {
        $this->db->select("*");
        $this->db->from('mitra_kontrak');
        $this->db->where('mikon_employee_id', $employee_id);
        $this->db->where("'".$konfirmasi_tanggal."' BETWEEN mikon_tanggal AND mikon_tanggal_akhir", null, false);

        $this->db->get()->num_rows();
        return 1;
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
        $this->db->from($this->tblPayout.' a');
        $this->db->join($this->tblMitraProfile.' b', 'b.id_employee = a.id_employee', $this->typeInner);
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
        $this->db->from($this->tblPayout." a");
        $this->db->join("orders b", "b.id_order = a.id_order", $this->typeInner);
        $this->db->join("employee c", "c.id_employee = a.id_employee", $this->typeInner);
        $this->db->join($this->tblMitraProfile." d", "d.id_employee = c.id_employee", $this->typeInner);
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
        $this->db->from($this->tblPayout." a");
        $this->db->join("orders b", "b.id_order = a.id_order", $this->typeInner);
        $this->db->join("employee c", "c.id_employee = a.id_employee", $this->typeInner);
        $this->db->join($this->tblMitraProfile." d", "d.id_employee = c.id_employee", $this->typeInner);
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
        $this->db->join($this->tblMitraProfile." b", "a.id_employee = b.id_employee", $this->typeInner);
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
        $this->db->join($this->tblMitraProfile.' d', 'd.id_employee=a.id_employee', $this->typeInner);
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
        $this->db->from($this->tblPayout." a");
        $this->db->join("employee b", "b.id_employee = a.id_employee", $this->typeInner);
        $this->db->join($this->tblMitraProfile." c", "c.id_employee = b.id_employee", $this->typeInner);
        $this->db->join("orders d", "d.id_order = a.id_order", $this->typeInner);
        $this->db->where("a.".$this->colNoPD, $noPD);
        $this->db->where("c.id", $idMitra);

        return $this->db->get()->result_array();
    }

    public function getListPDPPh()
    {
        $this->db->select($this->colNoPD);
        $this->db->from($this->tblPayout);
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
        $this->db->from($this->tblPayout." a");
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
        $qMaxNumber = $this->db->query("SELECT MAX(DISTINCT $column) AS nomor FROM payout_detail");

        return $qMaxNumber->row('nomor') + 1;
    }

    public function updateComission($idOrder, $data)
    {
        $this->db->where($this->colIdOrder, $idOrder);
        $this->db->where('percentage !=', '0.010');
        if ($this->db->update($this->tblPayout, $data)) {
            return true;
        }

        return false;
    }

    public function getIdPayout($idOrder, $type = 1)
    {
        $qPayout = $this->db->query("SELECT `id` FROM `payout_detail` WHERE `id_order`=".$this->db->escape($idOrder)." AND `type`=".$this->db->escape($type)." $this->limitOne");

        return $qPayout->row('id');
    }

    public function getStatus($idOrder, $type = 1)
    {
        $qPayout = $this->db->query("SELECT `status` FROM `payout_detail` WHERE `id_order`=".$this->db->escape($idOrder)." AND `type`=".$this->db->escape($type)." $this->limitOne");

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
        if ($this->db->update($this->tblPayout, $data)) {
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
        $this->db->from($this->tblPayout.' a');
        $this->db->join('employee b', 'b.id_employee = a.id_employee', $this->typeInner);
        $this->db->join($this->tblMitraProfile.' c', 'c.id_employee = b.id_employee', $this->typeInner);
        $this->db->join('orders d', 'd.id_order = a.id_order', $this->typeInner);
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
        $this->db->from($this->tblPayout.' a');
        $this->db->join('orders b', 'b.id_order = a.id_order', $this->typeInner);
        $this->db->where('a.no_pd', $noPD);
        $this->db->limit(1);

        return $this->db->get()->row('company');
    }
}
