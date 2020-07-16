<?php
defined('BASEPATH') or exit('No direct script access allowed');

/**
 * @property Mod_testimoni $m_testimoni
 */
class Testimoni extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model("Mod_testimoni", "m_testimoni");
    }

    public function index($offset = 0)
    {
        $this->load->library('pagination');
        $config = [
            'base_url' => base_url() . "testimoni",
            'num_links' => 2,
            'per_page' => 15,
            'total_rows' => $this->m_testimoni->getNumRows(),
            'uri_segment' => 2,
            'next_link' => false,
            'prev_link' => false,
            'first_link' => '&laquo;',
            'last_link' => '&raquo;'
        ];
        $this->pagination->initialize($config);
        $data['title'] = "Testimoni pembeli &raquo; Mitra Edukasi Nusantara";
        $data['links'] = $this->pagination->create_links();
        $data['testimoni'] = $this->m_testimoni->getFeedback($config['per_page'], $offset);
        $this->load->view('tshops/testimoni', $data);
    }
}
