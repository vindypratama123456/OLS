<?php
defined('BASEPATH') or exit('No direct script access allowed');

/**
 * @property Authcustomer $authcustomer
 */
class Halaman extends CI_Controller
{
    private $data;

    public function __construct()
    {
        parent::__construct();
        $this->data = [];
    }

    public function index()
    {
        $this->bantuan();
    }

    public function bantuan()
    {
        $this->authcustomer->restrict();
        $this->data['title'] = 'Petunjuk Pemesanan';
        $this->load->view('tshops/bantuan', $this->data);
    }

    public function tatacarapemesanan()
    {
        $this->data['title'] = 'Tata Cara Pemesanan';
        $this->load->view('tshops/tatacarapemesanan', $this->data);
    }

    public function syaratketentuan()
    {
        $this->data['title'] = 'Syarat & Ketentuan Pemesanan';
        $this->load->view('tshops/syaratketentuan', $this->data);
    }

    public function hubungi_kami()
    {
        $this->data['title'] = 'Hubungi Kami';
        $this->load->view('tshops/hubungi_kami', $this->data);
    }

    public function tatacarapembayaran()
    {
        $this->data['title'] = 'Tata Cara Pembayaran BRI Virtual Account';
        $this->load->view('tshops/bri_virtual_account', $this->data);
    }
}
