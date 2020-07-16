<?php
defined('BASEPATH') or exit('No direct script access allowed');

/**
 * @property Auth $auth
 */
class Dashboard extends MY_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->_output = [];
    }

    public function index()
    {
        $data['page_title']     = 'Dasbor';
        $data['total']          = false;
        $data['content']        = $this->load->view(BACKMIN_PATH . '/dashboard/dashboard', $data, true);
        $data['script_js']      = $this->load->view(BACKMIN_PATH . '/dashboard/script_js', '', true);
        
        $this->load->view(BACKMIN_PATH . '/main', $data);
    }

    public function logout()
    {
        if ($this->auth->logout()) {
            redirect('backmin', 'refresh');
        }
    }
}
