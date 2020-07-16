<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Auth
{
    public $CI;
    public $backoffice_area;
    public $backmin_area;
    public $steam_area;

    public function __construct()
    {
        $this->CI = &get_instance();

        $this->backoffice_area = [1, 2, 3, 4, 5, 6, 7, 8, 11, 14, 15];
        $this->backmin_area = [9, 10, 12, 13];
        $this->steam_area = [101, 102, 103, 104];

    }

    public function processLogin($login = null)
    {
        if ( ! isset($login) || count($login) !== 2) {
            return false;
        }
        $username = $login[0];
        $password = $login[1];

        $this->CI->db->where('email', $username);
        $this->CI->db->where('passwd', sha1($password));
        $this->CI->db->where('active', 1);

        $query = $this->CI->db->get('employee');
        if ($query->num_rows() == 1) {
            foreach ($query->result() as $row) {
                $id = $row->id_employee;
                $name = $row->name;
                $level = $row->level;
                $regional = $row->regional;
                $code = $row->code;

                // Our user exists, set session.
                $this->CI->session->set_userdata('adm_id', $id);
                $this->CI->session->set_userdata('adm_uname', $username);
                $this->CI->session->set_userdata('adm_name', $name);
                $this->CI->session->set_userdata('adm_level', $level);
                $this->CI->session->set_userdata('adm_region', $regional);
                $this->CI->session->set_userdata('adm_code', $code);

                $this->CI->logger->logAction('Login');
            }

            return true;
        }

        return false;
    }

    public function redirect()
    {
        if ($this->CI->session->userdata('redirect_admin') == false) {
            if (in_array((int)$this->CI->session->userdata('adm_level'), $this->backoffice_area) || in_array((int)$this->CI->session->userdata('adm_level'), $this->steam_area)) {
                redirect(ADMIN_PATH.'/dashboard');
            } else {
                redirect(BACKMIN_PATH.'/dashboard');
            }
        } else {
            $uriSegment = explode('/', $this->CI->session->userdata('redirect_admin'));
            if (strtolower($uriSegment[0]) == 'backoffice' && in_array((int)$this->CI->session->userdata('adm_level'),
                    $this->backoffice_area)) {
                redirect($this->CI->session->userdata('redirect_admin'));
            } elseif (strtolower($uriSegment[0]) == 'backmin' && in_array((int)$this->CI->session->userdata('adm_level'),
                    $this->backmin_area)) {
                redirect($this->CI->session->userdata('redirect_admin'));
            } elseif (in_array((int)$this->CI->session->userdata('adm_level'), $this->backoffice_area) || in_array((int)$this->CI->session->userdata('adm_level'), $this->steam_area)) {
                redirect(ADMIN_PATH.'/dashboard');
            } else {
                redirect(BACKMIN_PATH.'/dashboard');
            }
        }
    }

    public function restrict($logged_out = false)
    {
        if ($logged_out && $this->loggedIn()) {
            if (in_array((int)$this->CI->session->userdata('adm_level'), $this->backoffice_area) || in_array((int)$this->CI->session->userdata('adm_level'), $this->steam_area)) {
                redirect(ADMIN_PATH.'/dashboard');
            } else {
                redirect(BACKMIN_PATH.'/dashboard');
            }
        }
        if ( ! $logged_out && ! $this->loggedIn()) {
            $this->CI->session->set_userdata('redirect_admin', $this->CI->uri->uri_string());
            redirect(ADMIN_PATH.'/login');
        }
    }

    public function loggedIn()
    {
        return ! ($this->CI->session->userdata('adm_id') == false);
    }

    public function logout()
    {
        $logs = $this->CI->logger->logAction('Logout');

        if ($logs) {
            $this->CI->session->unset_userdata('adm_id');
            $this->CI->session->unset_userdata('adm_uname');
            $this->CI->session->unset_userdata('adm_level');
            $this->CI->session->unset_userdata('adm_code');
            $this->CI->session->unset_userdata('adm_region');
            $this->CI->session->unset_userdata('redirect_admin');
            $this->CI->session->unset_userdata('sess_fstart');
            $this->CI->session->unset_userdata('sess_fend');
            $this->CI->session->unset_userdata('sess_wil');
            $this->CI->session->unset_userdata('id_customer_offline');

            return true;
        }

    }
}

/* End of file: Auth.php */
/* Location: ./application/libraries/Auth.php */
