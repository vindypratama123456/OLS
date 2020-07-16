<?php
defined('BASEPATH') or exit('No direct script access allowed');

/**
 * @property Mod_buku $m_buku
 */
class Buku extends CI_Controller
{
    public $data = [];

    public function __construct()
    {
        parent::__construct();
        $this->load->model('Mod_buku', 'm_buku');
    }

    public function index()
    {
        redirect('home');
    }

    public function detail($product)
    {
        $idProduct = explode('-', $product);
        $idProduct = $idProduct[0];
        $this->data['title'] = $this->m_buku->getTitle($idProduct)->name;
        $this->data['data_product'] = $this->m_buku->getDetailBuku($idProduct)[0];
        $this->load->view('tshops/buku', $this->data);
    }

    public function generateJson()
    {
        // di jalankan setiap ada action trigger update buku di backoffice;
        $this->load->helper('file');
        if ($this->m_buku->generateJsonBukuBos('1-6')) {
            $data = json_encode($this->m_buku->generateJsonBukuBos('1-6'));
            write_file('assets/data/json/1-6.json', $data);
            if ($this->m_buku->generateJsonBukuBos('7-9')) {
                $data = json_encode($this->m_buku->generateJsonBukuBos('7-9'));
                write_file('assets/data/json/7-9.json', $data);
                if ($this->m_buku->generateJsonBukuBos('10-12')) {
                    $data = json_encode($this->m_buku->generateJsonBukuBos('10-12'));
                    write_file('assets/data/json/10-12.json', $data);
                    if ($this->m_buku->generateJsonBukuNonBos()) {
                        $data = json_encode($this->m_buku->generateJsonBukuNonBos());
                        write_file('assets/data/json/0.json', $data);
                        return true;
                    }
                }
            }
        }
    }
}
