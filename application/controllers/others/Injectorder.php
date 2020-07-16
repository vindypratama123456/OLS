<?php
defined('BASEPATH') or exit('No direct script access allowed');

/**
 * @property Mod_cleansing $mod_cleansing
 */
class Injectorder extends CI_Controller
{
    public $periode;

    public function __construct()
    {
        parent::__construct();
        $this->load->model('mod_cleansing');
        $this->periode = (int)env('PERIODE');
    }

    public function injectSalesInfo()
    {
        $this->db->trans_begin();
        $salesInfo = $this->mod_cleansing->getSalesInfo("", "orders.sales_referer IS NOT NULL");
        $dataSales = [];
        foreach ($salesInfo as $value) {
            $dataSales["sales_name"] = $value->name;
            $dataSales["sales_phone"] = $value->telp;
            $this->mod_cleansing->edit('orders', 'id_order=' . $value->id_order, $dataSales);
        }
        if ($this->db->trans_status() === true) {
            $this->db->trans_commit();
            $callBack['message'] = "Update orders successfull";
            echo json_encode($callBack);
        } else {
            $this->db->trans_rollback();
            $callBack['message'] = "Update orders unsuccessfull";
            echo json_encode($callBack);
        }
    }

    public function injectSalesRefererAndInfo()
    {
        $this->db->trans_begin();
        $salesInfo = $this->mod_cleansing->getKorwilInfo("", "orders.sales_referer IS NULL");
        $dataSales = [];
        foreach ($salesInfo as $value) {
            $dataSales["sales_referer"] = $value->email;
            $dataSales["sales_name"] = $value->name;
            $dataSales["sales_phone"] = $value->telp;
            $this->mod_cleansing->edit('orders', 'id_order=' . $value->id_order, $dataSales);
        }
        if ($this->db->trans_status() === true) {
            $this->db->trans_commit();
            $callBack['message'] = "Update orders successfull";
            echo json_encode($callBack);
        } else {
            $this->db->trans_rollback();
            $callBack['message'] = "Update orders unsuccessfull";
            echo json_encode($callBack);
        }
    }

    public function injectKorwilInfo()
    {
        $this->db->trans_begin();
        $korwilInfo = $this->mod_cleansing->getKorwilInfo();
        $dataKorwil = [];
        foreach ($korwilInfo as $value) {
            $dataKorwil["korwil_email"] = $value->email;
            $dataKorwil["korwil_name"] = $value->name;
            $dataKorwil["korwil_phone"] = $value->telp;
            $this->mod_cleansing->edit('orders', 'id_order=' . $value->id_order, $dataKorwil);
        }
        if ($this->db->trans_status() === true) {
            $this->db->trans_commit();
            $callBack['message'] = "Update orders successfull";
            echo json_encode($callBack);
        } else {
            $this->db->trans_rollback();
            $callBack['message'] = "Update orders unsuccessfull";
            echo json_encode($callBack);
        }
    }
}
