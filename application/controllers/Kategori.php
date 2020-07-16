<?php
defined('BASEPATH') or exit('No direct script access allowed');

/**
 * @property Mod_kategori $m_kategori
 */
class Kategori extends CI_Controller
{
    protected $data = array();

    public function __construct()
    {
        parent::__construct();
        $this->load->model('Mod_kategori', 'm_kategori');
    }

    public function index()
    {
        redirect('kategori/buku/3-buku-teks-2013');
    }

    public function buku($kategori, $offset = 0)
    {
        $idCategory = 3;
        $perPage = 12;
        $this->data['title'] = $this->m_kategori->getTitle($idCategory)->name ?: 'Buku Sekolah';
        $this->load->library('pagination');
        $config = [
            'base_url' => base_url() . 'kategori/buku/' . $kategori . '/',
            'num_links' => 3,
            'per_page' => $perPage,
            'total_rows' => $this->m_kategori->numRows($idCategory),
            'uri_segment' => 4,
            'next_link' => false,
            'prev_link' => false,
            'first_link' => '&laquo;',
            'last_link' => '&raquo;'
        ];
        $this->pagination->initialize($config);
        $this->data['links'] = $this->pagination->create_links();
        $this->data['data_product'] = $this->m_kategori->getAllData($idCategory, $perPage, (int)$offset);
        $this->load->view('tshops/kategori', $this->data);
    }
}
