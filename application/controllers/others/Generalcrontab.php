<?php
defined('BASEPATH') or exit('No direct script access allowed');

/**
 * @property Mod_general $mod_general
 */
class Generalcrontab extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('mod_general');
    }

    public function updateStatusSekolahProspect()
    {
        $today = date('Y-m-d');
        $customerActive = $this->mod_general->getAll('customer', 'id_customer, id_mitra, date_prospect_start, date_prospect_expired, status_prospect', 'status_prospect = 3');
        $customerExpired = [];
        foreach ($customerActive as $row => $value) {
            if (strtotime($value->date_prospect_expired) < strtotime($today)) {
                array_push($customerExpired, $value->id_customer);
            }
        }
        if ($customerExpired != null) {
            $this->db->trans_begin();
            $dataUpdate = [
                'id_mitra' => 0,
                'date_prospect_start' => '0000-00-00',
                'date_prospect_expired' => '0000-00-00',
                'status_prospect' => 1
            ];
            $this->mod_general->edit('customer', $dataUpdate, 'id_customer in (' . implode($customerExpired, ',') . ')');
            if ($this->db->trans_status() === true) {
                $this->db->trans_commit();
                $callBack['message'] = "Ubah status berhasil";
                echo json_encode($callBack);
            } else {
                $this->db->trans_rollback();
                $callBack['message'] = "Ubah status tidak berhasil";
                echo json_encode($callBack);
            }
        } else {
            $callBack['message'] = "Tidak ada customer expired";
            echo json_encode($callBack);
        }
    }
}
