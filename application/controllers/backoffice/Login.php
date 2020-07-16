<?php
defined('BASEPATH') or exit('No direct script access allowed');

/**
 * @property Auth $auth
 */
class Login extends CI_Controller
{
    protected $backoffice_area;
    protected $backmin_area;
    protected $steam_area;

    public function __construct()
    {
        parent::__construct();
        $this->backoffice_area = [1, 2, 3, 4, 5, 6, 7, 8, 11, 14, 15];
        $this->backmin_area = [9, 10, 12, 13];
        $this->steam_area = [101, 102, 103, 104];
    }

    public function index($error = false)
    {
        $this->auth->restrict(true);
        $this->load->view('admin/login');
    }

    public function verify()
    {
        if ( ! $this->input->is_ajax_request()) {
            return false;
        }

        $this->auth->restrict(true);
        if ($this->input->post('u_name') && $this->input->post('u_pass')) {
            $login = [
                $this->input->post('u_name', true),
                $this->input->post('u_pass', true),
            ];
            if ($this->auth->processLogin($login)) {
                $callBack = [
                    'success' => true,
                    'message' => 'Login berhasil',
                    'redirect' => $this->_getRedirectPath(),
                ];
                ajaxResponse(200, $callBack);
            } else {
                $callBack = [
                    'success' => false,
                    'message' => '<i class="fa fa-info-warning"></i><strong>Login Gagal!</strong> Email/Kata Sandi tidak sesuai.',
                    'csrf_token' => $this->security->get_csrf_hash(),
                ];
                ajaxResponse(400, $callBack);
            }
        } else {
            redirect('backoffice/login', 'true');
        }
    }

    private function _getRedirectPath()
    {
        $path = ADMIN_PATH.'/dashboard';
        if ($this->session->userdata('redirect_admin') == false) {
            if ( ! in_array((int)$this->session->userdata('adm_level'), $this->backoffice_area)) {
                $path = BACKMIN_PATH.'/dashboard';
            }
        } else {
            $uriSegment = explode('/', $this->session->userdata('redirect_admin'));
            if (strtolower($uriSegment[0]) == 'backoffice' && in_array((int)$this->session->userdata('adm_level'),
                    $this->backoffice_area)) {
                $path = $this->session->userdata('redirect_admin');
            } elseif (strtolower($uriSegment[0]) == 'backmin' && in_array((int)$this->session->userdata('adm_level'),
                    $this->backmin_area)) {
                $path = $this->session->userdata('redirect_admin');
            } elseif ( in_array((int)$this->session->userdata('adm_level'), $this->steam_area)) {
                $path = ADMIN_PATH.'/dashboard';
            }
        }

        return $path;
    }

    public function logout()
    {
        if ($this->auth->logout()) {
            redirect('backoffice/login', 'refresh');
        }
    }
}
