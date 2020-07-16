<?php
defined('BASEPATH') || exit('No direct script access allowed');

/**
 * @property Auth $auth
 */
class MY_Controller extends CI_Controller
{
    public $adm_id;
    public $adm_level;
    public $adm_uname;
    public $adm_name;
    public $adm_code;
    public $adm_id_gudang;
    public $nama_gudang;
    public $id_korwil;
    public $arrSSLContext;
    public $periode;
    public $backoffice_area;
    public $backmin_area;
    public $backmin_scm_area;
    public $backmin_gudang_area;
    public $auditor_area;
    public $steam_area;
    public $steam_superadmin_area;
    public $steam_admin_area;
    public $steam_sales_area;
    private $_nama_gudang;

    public function __construct()
    {
        parent::__construct();
        $this->auth->restrict();

        $this->adm_id = (int)$this->session->userdata('adm_id');
        $this->adm_level = (int)$this->session->userdata('adm_level');
        $this->adm_uname = $this->session->userdata('adm_uname');
        $this->adm_name = $this->session->userdata('adm_name');
        $this->adm_code = $this->session->userdata('adm_code');
        $this->nama_gudang = null;
        $this->backoffice_area = [1, 2, 3, 4, 5, 6, 7, 8, 11, 14];
        $this->backoffice_admin_area = [1, 2, 3, 8, 11];
        $this->backoffice_superadmin_area = [1, 11];
        $this->backmin_area = [9, 10, 12, 13];
        $this->backmin_scm_area = [9, 12];
        $this->backmin_gudang_area = [10, 13];
        $this->auditor_area = [11, 12, 13, 14];
        $this->steam_area = [101, 102];
        $this->steam_superadmin_area = [101];
        $this->steam_admin_area = [102];
        $this->_nama_gudang = 'nama_gudang';

        if (in_array($this->adm_level, $this->backmin_area, true) && $this->uri->segment(1) == ADMIN_PATH) {
            redirect(BACKMIN_PATH);
        } elseif (in_array($this->adm_level, $this->backoffice_area, true) && $this->uri->segment(1) == BACKMIN_PATH) {
            redirect(ADMIN_PATH);
        } elseif (in_array($this->adm_level, $this->steam_area, true) && $this->uri->segment(1) == BACKMIN_PATH) {
            redirect(ADMIN_PATH);
        } 

        if ($this->adm_level == 4) {
            $q1 = $this->db->query("SELECT `email_korwil` FROM `korwil_sales` WHERE `email_sales`=".$this->db->escape($this->session->userdata('adm_uname')));
            $r1 = $q1->row('email_korwil');
            $q2 = $this->db->query("SELECT `id_employee` FROM `employee` WHERE `email`=".$this->db->escape($r1));
            $r2 = $q2->row('id_employee');
            $this->id_korwil = $r2;
        } else {
            $this->id_korwil = $this->adm_id;
        }

        if (in_array($this->adm_level, $this->backmin_gudang_area, true)) {
            $this->adm_id_gudang = $this->session->userdata('adm_region');
            $qng = $this->db->query("SELECT `nama_gudang` FROM `master_gudang` WHERE `id_gudang`=".$this->db->escape($this->adm_id_gudang));
            $this->nama_gudang = $qng->row($this->_nama_gudang) ?: null;
            $this->session->set_userdata($this->_nama_gudang, $this->nama_gudang);
            $this->adm_nama_gudang = $this->session->userdata($this->_nama_gudang);
        }

        $this->periode = (int)getenv('PERIODE');
    }

    public function isCompleteProfile($id)
    {
        $q = $this->db->query("SELECT `is_complete` FROM `customer` WHERE `id_customer`=".$this->db->escape($id));
        $r = $q->row('is_complete');

        return ($r == 1);
    }

    public function myUpload($param)
    {
        $cfg['upload_path'] = config_item('upload_path').$param['path'];
        $cfg['allowed_types'] = 'jpg|jpeg|png|gif|tif';
        $cfg['max_size'] = '10240';
        $cfg['file_name'] = $param['nama_file'];
        $cfg['file_ext_tolower'] = true;
        $cfg['overwrite'] = true;
        $cfg['remove_spaces'] = true;
        if ( ! is_dir($cfg['upload_path'])) {
            if ( ! mkdir($concurrentDirectory = $cfg['upload_path'], 0777, true) && ! is_dir($concurrentDirectory)) {
                throw new \RuntimeException(sprintf('Directory "%s" was not created', $concurrentDirectory));
            }
            chmod($cfg['upload_path'], 0777);
        }
        // $this->load->library('upload', $cfg);
        $this->load->library('upload');
        $this->upload->initialize($cfg);
        if ( ! $this->upload->do_upload($param['fieldname'])) {
            $hasil = [
                'status' => 0,
                'datafile' => null,
                'pesan' => $this->upload->display_errors('<span>', '</span>'),
            ];
        } else {
            $my = $this->upload->data();
            $myfilename = $my['file_name'];
            $hasil = [
                'status' => 1,
                'datafile' => $myfilename,
                'pesan' => 'Upload success',
            ];
        }

        return $hasil;
    }
}
