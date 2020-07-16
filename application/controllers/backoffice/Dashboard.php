<?php
defined('BASEPATH') or exit('No direct script access allowed');

/**
 * @property Mod_dashboard $mod_dashboard
 */
class Dashboard extends MY_Controller
{
    protected $_output;

    public function __construct()
    {
        parent::__construct();
        if (5 == $this->adm_level) {
            redirect(ADMIN_PATH . '/orders/deliverydetail');
        }
        if (6 == $this->adm_level || 7 == $this->adm_level  || 14 == $this->adm_level) {
            redirect(ADMIN_PATH . '/finance');
        }
        if (15 == $this->adm_level)
        {
            redirect("backoffice/kontrak");
        }
        if (in_array($this->adm_level, [101, 102, 103, 104], true)) {
            redirect(ADMIN_PATH . '/steam/comission_order_new');
        }
        $this->_output = [];
        $this->load->model('mod_dashboard');
    }

    public function index()
    {
        if (4 == $this->adm_level) {
            redirect("backoffice/orders/");
        }
        $data['totalOrder'] = $this->mod_dashboard->getTotalOrder();
        $data['totalPaid'] = $this->mod_dashboard->countOrder(2);
        $data['totalProcess'] = ($this->mod_dashboard->getTotalOrders(5) + $this->mod_dashboard->getTotalOrders(6));
        $data['totalUnpaid'] = $this->mod_dashboard->countOrder() - $data['totalPaid'];
        $data['listData'] = $this->mod_dashboard->getListOrder();
        $this->_output['content'] = $this->load->view('admin/dashboard/list', $data, true);
        $this->_output['script_css'] = $this->load->view('admin/dashboard/css', '', true);
        $this->_output['script_js'] = $this->load->view('admin/dashboard/js', '', true);
        $this->load->view('admin/template', $this->_output);
    }
}
