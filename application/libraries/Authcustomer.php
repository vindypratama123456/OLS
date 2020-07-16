<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Authcustomer
{
    private $CI = null;
    public function __construct()
    {
        $this->CI =& get_instance();
    }

    public function process_login($login = null)
    {
        if (!isset($login)) {
            return false;
        }

        if (count($login) != 2) {
            return false;
        }

        $username = $login[0];
        $password = $login[1];

        $this->CI->db->where('no_npsn', $username);
        $this->CI->db->where('passwd', sha1($password));
        $this->CI->db->where('active', 1);
        $query = $this->CI->db->get('customer');
        if ($query->num_rows() == 1) {
            foreach ($query->result() as $row) {
                $id = $row->id_customer;
                $name = $row->name;
                $school_name = $row->school_name;
                $jenjang = $row->jenjang;
                $this->CI->session->set_userdata('id_customer', $id);
                $this->CI->session->set_userdata('name', $name);
                $this->CI->session->set_userdata('school_name', $school_name);
                $this->CI->session->set_userdata('jenjang', $jenjang);
            }
            return true;
        } else {
            return false;
        }
    }

    public function redirect()
    {
        if ($this->CI->session->userdata('redirected_from') == false) {
            redirect('pesanan/formpesanan');
        } else {
            redirect($this->CI->session->userdata('redirected_from'));
        }
    }

    public function restrict($logged_out = false)
    {
        if ($logged_out && $this->logged_in()) {
            redirect('', 'refresh');
        }

        // if($this->logged_in()){
            /*
			$this->CI->load->model('mod_akunku');
			$data = $this->CI->mod_akunku->restrict($this->CI->session->userdata('id_customer'), "email, no_npsn, passwd, name, nip_kepsek, phone_kepsek, nama_bendahara, nip_bendahara, phone_bendahara, nama_rekening, nomor_rekening, phone");

			if((empty($data[0]['email'])) ||
				(sha1($data[0]['no_npsn'])==$data[0]['passwd']) ||
				(empty($data[0]['name'])) ||
				(empty($data[0]['nip_kepsek'])) ||
				(empty($data[0]['phone_kepsek'])) ||
				(empty($data[0]['nama_bendahara'])) ||
				(empty($data[0]['nip_bendahara'])) ||
				(empty($data[0]['phone_bendahara'])) ||
				(empty($data[0]['nama_rekening'])) ||
				(empty($data[0]['nomor_rekening'])) ||
				(empty($data[0]['phone'])))
			{
				if($this->CI->uri->segment(1) <> 'akunsaya' && $this->CI->uri->segment(2) <> 'profil'){
					$this->CI->session->set_flashdata('message', '<div class="alert alert-danger alert-dismissable">Mohon lengkapi profil sekolah anda terlebih dahulu!<br />Perhatikan: <b>Kata Sandi</b> tidak boleh sama dengan <b>NPSN</b></div>');
					redirect('akunsaya/profil');
				}
			}
			*/
            /*
			if(empty($data[0]['phone'])){
				if($this->CI->uri->segment(1) <> 'akunsaya' && $this->CI->uri->segment(2) <> 'profil'){
					$this->CI->session->set_flashdata('message', '<div class="alert alert-danger alert-dismissable">Anda harus mengisi nomor telepon anda lebih dahulu!</div>');
					redirect('akunsaya/profil');
				}
			}

			if(sha1($data[0]['no_npsn']) == $data[0]['passwd']){
				if($this->CI->uri->segment(1) <> 'akunsaya' && $this->CI->uri->segment(2) <> 'profil'){
					$this->CI->session->set_flashdata('message', '<div class="alert alert-danger alert-dismissable">Anda harus mengganti password anda terlebih dahulu!</div>');
					redirect('akunsaya/profil');
				}
			}
			*/
        // }

        // If the user isn' logged in and he's trying to access a page
        // he's not allowed to see when logged out,
        // redirect him to the login page!
        if (!$logged_out && !$this->logged_in()) {
            $this->CI->session->set_userdata('redirected_from', $this->CI->uri->uri_string());
            redirect('akunsaya/loginLocal');
            // redirect('http://data.dikdasmen.kemdikbud.go.id/sso/auth/?response_type=code&client_id=bkk13ad&state=100100&redirect_uri=http://bukusekolah.gramedia.com/akunsaya/verify');
        }
    }

    public function logged_in()
    {
        if ($this->CI->session->userdata('id_customer') == false) {
            return false;
        }
        return true;
    }

    public function logout()
    {
        $this->CI->session->unset_userdata('id_customer');
        $this->CI->session->unset_userdata('name');
        $this->CI->session->unset_userdata('school_name');
        $this->CI->session->unset_userdata('redirected_from');
        $this->CI->session->unset_userdata('jenjang');
        $this->CI->session->unset_userdata('data_user');
        $this->CI->session->unset_userdata('access_token');
        $this->CI->session->unset_userdata('zona');
        return true;
    }
}
